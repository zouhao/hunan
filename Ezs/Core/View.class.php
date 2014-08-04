<?php
/**
 * 视图类
 * 模板引擎相关
 * @author 邹颢  zouhao619@gmail.com
 */
class View {
	private static $_instance;
	private $var = array ();
	private $tplLeft = '{';
	private $tplRight = '}';
	private $phpLeft = '<?php ';
	private $phpRight = '?>';
	private $cache_tpl_path=CACHE_TPL_PATH;
	private $view_path=VIEW_PATH;
	// 标签 标签=>是否闭合 true是,false否
	// 闭合即 {foreach}xxx{/foreach} 非闭合 {foreach}
	private $tag = array (
			'foreach' => true 
	);
	private $defineTag = array (
			'__ROOT__',
			'__APP__',
			'__CONTENT__',
			'__UPLOAD__' 
	);
	public static function getInstance() {
		if (! self::$_instance) {
			self::$_instance = new self ();
			self::$_instance->tplLeft=C('VIEW_START_TAG');
			self::$_instance->tplRight=C('VIEW_END_TAG');
		}
		return self::$_instance;
	}
	/**
	 * 注册变量
	 * @param string $name        	
	 * @param mix $value        	
	 */
	public function assign($name, $value = null) {
		if ($value===null && is_array ( $name )) {
			foreach ( $name as $key => $vo ) {
				$this->var [$key] = $vo;
			}
		} else {
			$this->var [$name] = $value;
		}
	}
	// 将模板解析并输出为html
	public function fetch($tpl = null, $create_tpl = true) {
		extract ( $this->var );
		ob_start ();
		//如果是提示页面的话,就直接加在页面绝对路径
		if(isset($flag)&&in_array($flag,array('success','error'))){
			require C('DEFAULT_JUMP_TPL');
		}else{
			if ($create_tpl == true){
				$tpl = rtrim($this->getTpl ( $tpl ),'/');
			}
			is_dir( $this->cache_tpl_path .substr ( $tpl, 0, strrpos ( $tpl, '/' ) )) or mkdir ( $this->cache_tpl_path .  substr ( $tpl, 0, strrpos ( $tpl, '/' ) ),0777,true ); // 建立文件夹
			
			if ((defined ( 'DEBUG' ) && DEBUG == true) || ! file_exists ( $this->cache_tpl_path . '/' . $tpl . C ( 'CACHE_TPL_SUFFIX' ) ) || filemtime ( $this->view_path . '/' . $tpl . C ( 'HTML_SUFFIX' ) ) > filemtime ( $this->cache_tpl_path . '/' . $tpl . C ( 'CACHE_TPL_SUFFIX' ) )) {
				$this->parse ( $tpl );
			}
			require $this->cache_tpl_path .$tpl . C ( 'CACHE_TPL_SUFFIX' );
		}
		$content = ob_get_contents ();
		ob_end_clean ();
		return $content;
	}
	// 输出
	public function show($content) {
		echo $content;
	}
	/**
	 * 显示模板
	 *
	 * @param string $tpl        	
	 */
	public function display($tpl = null, $create_tpl) {
		// 生成html
		$content = $this->fetch ( $tpl, $create_tpl );
		echo $content;
	}
	// 开始解析
	private function parse($tpl) {
		$content = file_get_contents ( $this->view_path . $tpl . C ( 'HTML_SUFFIX' ) );
		$content = $this->parseInclude ( $content ); // 解析include
		//$content = $this->parseTagXml($content);//用xml解析
		$content = $this->parseVar ( $content ); // 解析变量
		$content = $this->parseFunction ( $content ); // 解析函数
		//$content = $this->parsePhp ( $content ); // 解析php
		$content = $this->parseIf ( $content ); // 解析if
		$content = $this->parseLogic ( $content ); // 解析逻辑运算
		$content = $this->parseTag ( $content ); // 解析标签
		$content = $this->parseDefineTag ( $content ); // 解析宏定义标签
		$content = $this->parseForm ( $content ); // 为form表单添加token验证
		//$content = preg_replace ( '/\s{2,}/', ' ', $content ); // 压缩html
		file_put_contents ( $this->cache_tpl_path .$tpl . C ( 'CACHE_TPL_SUFFIX' ), $content );
	}
	/**
	 * 获取tpl完整路径
	 * @param string $tpl        	
	 * @return string
	 */
	private function getTpl($tpl) {
		static $_tpl = array ();
		if (isset ( $_tpl [$tpl] )) {
			return $_tpl [$tpl];
		} else {
			$path = $tpl;
			$list= array_reverse ( explode ( '/', $tpl ) );
			$method=isset($list['0'])&&$list['0']!=''?$list['0']:METHOD_NAME;
			$controller=isset($list['1'])&&$list['1']!=''?$list['1']:CONTROLLER_NAME;
			if(isset($list['2'])&&$list['2']!=''){
				$group=$list['2'];
				substr($group,0,1)!='/' and $group='/'.$group;
				$this->cache_tpl_path=CACHE_PATH.$group;
				$this->view_path=ROOT_PATH.'/View'.$group;
				$group='/';
			}else{
				$group=str_replace('//','/', GROUP_NAME_BOTH);
			}
			$path = $group . $controller . '/' . $method;
			$_tpl [$tpl] = $path;
			return $path;
		}
	}
	// 解析函数 {:U("Index")}代表会输出 
	//{~U("Index')}执行不输出
	private function parseFunction($content) {
		$content = preg_replace ( '/\\' . $this->tplLeft . '\:(\w+\(.*?)\\' . $this->tplRight . '/', $this->phpLeft . 'echo $1;' . $this->phpRight, $content );
		$content = preg_replace ( '/\\' . $this->tplLeft . '\~(.*?)\\' . $this->tplRight . '/', $this->phpLeft . ' $1;' . $this->phpRight, $content );
		return $content;
	}
	private function parseVar($content) {
		//默认值解析 {$value1|$value2}
		$content = preg_replace ( '/\\' . $this->tplLeft . '(\$[\$\w\'\"\[\]\(\)]+)\|(.*?)\\' . $this->tplRight . '/', $this->phpLeft . 'if(isset($1)&&$1!=\'\'){echo $1;}else{echo $2;}' . $this->phpRight, $content );
		
		// 解析变量 {$meg} {$emg['id']}
		$content = preg_replace ( '/\\' . $this->tplLeft . '(\$\w+.*?\s*)\\' . $this->tplRight . '/', $this->phpLeft . 'echo $1;' . $this->phpRight, $content );
		return $content;
	}
	// 解析include {include "header"}
	private function parseInclude($content) {
		if (preg_match_all ( '/\\' . $this->tplLeft . 'include\s+[\'\"]?(.*?)[\'\"]?\s*\\' . $this->tplRight . '/', $content, $match )) {
			if (isset ( $match [1] )) {
				foreach ( $match [1] as $key => $vo ) {
					$includeContent = file_get_contents ( $this->view_path . $this->getTpl ( $vo ) . C ( 'HTML_SUFFIX' ) );
					$content = str_replace ( $match [0] [$key], $includeContent, $content );
				}
			}
		}
		return $content;
	}
	// 解析逻辑运算 {@$i++}
	private function parseLogic($content) {
		$content = preg_replace ( '/\\' . $this->tplLeft . '\@(.*?)' . $this->tplRight . '/', $this->phpLeft . '$1;' . $this->phpRight, $content );
		return $content;
	}
	// 解析原生php {php}echo '1';{/php}
	private function parsePhp($content) {
		$content = preg_replace ( '/\\' . $this->tplLeft . 'php\\' . $this->tplRight . '/', $this->phpLeft, $content );
		$content = preg_replace ( '/\\' . $this->tplLeft . '\/php\\' . $this->tplRight . '/', $this->phpRight, $content );
		return $content;
	}
	/**
	 * 解析if
	 * {if $id=1}1{elseif id=2}2{else}3{/if}
	 */
	private function parseIf($content) {
		$content = preg_replace ( '/\\' . $this->tplLeft . 'if\s+(.*?)\\' . $this->tplRight . '/', $this->phpLeft . 'if($1){' . $this->phpRight, $content );
		$content = preg_replace ( '/\\' . $this->tplLeft . 'elseif(.*?)\\' . $this->tplRight . '/', $this->phpLeft . '}elseif($1){' . $this->phpRight, $content );
		$content = preg_replace ( '/\\' . $this->tplLeft . 'else\\' . $this->tplRight . '/', $this->phpLeft . '}else{' . $this->phpRight, $content );
		$content = preg_replace ( '/\\' . $this->tplLeft . '\/if\\' . $this->tplRight . '/', $this->phpLeft . '}' . $this->phpRight, $content );
		return $content;
	}
	// 解析标签
	private function parseTag($content) {
		foreach ( $this->tag as $tag => $bool ) {
			if ($bool) {
				$content = preg_replace ( '/\\' . $this->tplLeft . $tag . '\s+(.*?)\\' . $this->tplRight . '/', $this->phpLeft . $tag . '($1){' . $this->phpRight, $content );
				$content = preg_replace ( '/\\' . $this->tplLeft . '\/' . $tag . '\\' . $this->tplRight . '/', $this->phpLeft . '}' . $this->phpRight, $content );
			} else {
				$content = preg_replace ( '/\\' . $this->tplLeft . $tag . '(.*?)\\' . $this->tplRight . '/', $this->phpLeft . $tag . '($1);' . $this->phpRight, $content );
			}
		}
		return $content;
	}
	// 解析宏定义标签
	private function parseDefineTag($content) {
		foreach ( $this->defineTag as $vo ) {
			$content = str_replace ( $vo , $this->phpLeft . 'echo ' . $vo . ';' . $this->phpRight, $content );
		}
		return $content;
	}
	/**
	 *	解析表单
	 * @param unknown $content
	 * @return mixed
	 */
	private function parseForm($content) {
		if(C('TOKEN_POWER'))
			$content = str_replace ( '</form>', '<input type="hidden" name="'.C('TOKEN_NAME').'" value="'.Token::getToken().'" /></form>', $content );
		return $content;
	}
}
?>