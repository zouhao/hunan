<?php
/**
 * 模型基类
 * @author 邹颢  zouhao619@gmail.com
 */
class Model {
	protected $table = '';//表名
	protected $error = null;//错误提示
	protected $config = null;//配置
	protected $sql = null;//sql语句
	protected $where = '';//条件查询
	protected $order = null;//排序
	protected $group = null;//分组
	protected $field = '';//查询字段
	protected $force=null;//强制使用某索引
	protected $keyField=null;
	protected $oneField=null;
	protected $oneArray=null;
	protected $twoArrayField=null;
	protected $limit = null;
	protected $join = null;
	protected $having = null;
	protected $oldTable = null;
	private static $_intance=null;
	const EXISTS_VAILIDATE = 1; // 表单存在字段则验证
	const MUST_VALIDATE = 2; // 必须验证
	const MODEL_INSERT = 1; // 插入模型数据
	const MODEL_UPDATE = 2; // 更新模型数据
	const MODEL_BOTH = 3; // 包含上面两种方式
	const NO_AND = 1; // where不需要and
	const YES_AND = 2; // where需要and
	const YES_OR = 3; // where需要or
	                  // 初始化
	protected function initVar() {
		$this->oneField=null;
		$this->oneArray=null;
		$this->twoArrayField=null;
		$this->keyField=null;
		$this->field = '';
		$this->where = '';
		$this->group = null;
		$this->limit = null;
		$this->join = null;
		$this->order = null;
		$this->having = null;
		$this->force = null;
	}
	/**
	 * 单用例入口
	 * @param mix $table
	 * @param array $config
	 * @return Model
	 */
	public static function instance($table=null,$config=null){
		if(!self::$_intance){
			self::$_intance=new self();
			self::$_intance->initVar ();
			self::$_intance->config=empty($config)?C():$config;
		}
		empty($table) or self::$_intance->table($table);
		return self::$_intance;
	}
	/**
	 * 构造函数
	 * @param mix $table
	 * @param array $config
	 * @return Model
	 */
	public function __construct($table=null,$config=null){
		$this->initVar ();
		$this->config=empty($config)?C():$config;
		empty($table) or $this->table($table);
		return $this;
	}
	/**
	 * 对单个字段进行过滤
	 *
	 * @param array $structure        	
	 * @param array $field        	
	 * @param string $key        	
	 * @param mix $value        	
	 * @return string int float
	 */
	private function filterOne($structure, $field, $key, $value) {
		if (strpos ( $structure ['type'] [$field [$key]], 'bigint' ) !== false) {
		} else if (strpos ( $structure ['type'] [$field [$key]], 'int' ) !== false) {
			$value = intval ( $value );
		} else if (strpos ( $structure ['type'] [$field [$key]], 'float' ) !== false || strpos ( $structure ['type'] [$field [$key]], 'double' ) !== false) {
			$value = floatval ( $value );
		} else if (strpos ( $structure ['type'] [$field [$key]], 'bool' ) !== false) {
			$value = ( bool ) $value;
		} else {
			if (! MAGIC_QUOTES_GPC)
				$value = addslashes ( $value );
		}
		return $value;
	}
	/**
	 * 对字符串进行过滤
	 *
	 * @param string|array $name        	
	 * @return string array
	 */
	private function filter($data) {
		if (is_array ( $data )) {
			$structure = $this->getStructure ();
			if (! isset ( $structure ['field'] )) {
				$structureType=$structureField = array ();
				foreach ( $structure as $vo ) {
					$structureField = array_merge ( $vo ['field'] );
					$structureType=array_merge($vo['type']);
				}
				$structure ['field'] = $structureField;
				$structure['type']=$structureType;
			}
			$field = array_flip ( $structure ['field'] );
			$data = array_intersect_key ( $data, $field );
			foreach ( $data as $key => $value ) {
				if (is_array ( $value )) {
					list ( $k, $v ) = $value;
					if ($k == 'in'||$k=='between') {
						$vo=null;
						is_string ( $v ) and $v = explode ( ',', $v );
						foreach ( $v as $vValue ) {
							$vo [] = $this->filterOne ( $structure, $field, $key, $vValue );
						}
						$data [$key] ['1'] = $vo;
					} else {
						$data [$key] ['1'] = $this->filterOne ( $structure, $field, $key, $v );
					}
				} else {
					$data [$key] = $this->filterOne ( $structure, $field, $key, $value );
				}
			}
			return $data;
		} else {
			if (! MAGIC_QUOTES_GPC)
				return addslashes ( $data );
			else
				return $data;
		}
	}
	/**
	 * 执行sql语句
	 *
	 * @param string $sql
	 * @throws Exception
	 * @return 查询语句返回查询结构
	 */
	public function query($sql,$init=true) {
		$this->sql = $sql;
		$mysql=Mysql::getInstance ($this->config );
		$mysql->oneField=$this->oneField;
		$mysql->twoArrayField=$this->twoArrayField;
		$mysql->oneArray=$this->oneArray;
		$mysql->keyField=$this->keyField;
		$init and $this->initVar ();
		return $mysql->query ( $sql );
	}
	public function mysqlQuery($sql){
		$this->initVar ();
		$r=Mysql::getInstance ($this->config )->mysqlQuery($sql);
		if($r){
			return $r;
		}else{
			exit($sql);
		}
	}
	public function mysqlFetchArray($result){
		return Mysql::getInstance ($this->config )->mysqlFetchArray($result);
	}
	public function mysqlCount($result){
		$this->initVar ();
		return Mysql::getInstance ($this->config )->count($result);
	}
	/**
	 * 插入数据库
	 *
	 * @param array $data        	
	 * @return bool int 失败返回false
	 */
	public function save($data) {
		if (! empty ( $data ) && is_array ( $data )) {
			if (is_array (current($data))) {
				foreach ( $data as $k => $v ) {
					$data [$k] = $this->filter ($v);
				}
				$field = '`' . implode ( '`,`', array_keys (current($data)) ) . '`';
				$this->sql = 'insert into ' . $this->table . '(' . $field . ')' . 'values';
				$first=true;
				foreach ( $data as $v) {
					$first or $this->sql.=',';
					$this->sql .= '(' . "'" . implode ( "','", $v ) . "')";
					$first=false;
				}
			} else {
				$data = $this->filter ( $data );
				$value = "'" . implode ( "','", array_values ( $data ) ) . "'";
				$field = '`' . implode ( '`,`', array_keys ( $data ) ) . '`';
				$this->sql = 'insert into ' . $this->table . '(' . $field . ')' . 'values';
				$this->sql .= '(' . $value . ');';
			}
			$rs = $this->query ( $this->sql );
			$structure = $this->getStructure ();
			if (isset($structure ['auto_increment'])&&$structure ['auto_increment'] == 'auto_increment') {
				return mysql_insert_id ();
			} else {
				return $rs;
			}
		} else {
			return false;
		}
	}
	/**
	 * 数据更新
	 *
	 * @param array $data        	
	 * @return bool
	 */
	public function update($data) {
		if (! empty ( $data ) && is_array ( $data )) {
			$data = $this->filter ( $data );
			if (array_key_exists ( $this->getPrimaryKey (), $data )) {
				$this->where ( array (
						$this->getPrimaryKey () => $data [$this->getPrimaryKey ()] 
				) );
				unset ( $data [$this->getPrimaryKey ()] );
			}
			$flag = true;
			$field = '';
			foreach ( $data as $key => $value ) {
				if ($flag) {
					$flag = false;
				} else {
					$field .= ',';
				}
				if (is_int ( $value )||is_float($value)) {
					$field .= "`{$key}`={$value}";
				} else {
					$field .= "`{$key}`='{$value}'";
				}
			}
			$this->sql = 'update ' . $this->table . ' set ' . $field . ' where ' . $this->where;
			return $this->query ( $this->sql );
		} else {
			return false;
		}
	}
	/**
	 * 删除数据(不允许同时出现$db->where($condition)->delete($condition)这样的用法,where参数和delete里的参数只能存在一个)
	 * @return bool
	 */
	public function delete($data) {
		empty ( $this->where )&&empty($data) and exit ( 'sorry!不允许删除全部数据!' );
		empty($data) or $this->where ( $data );
		$this->sql = 'delete from ' . $this->table . ' where ' . $this->where;
		return $this->query ( $this->sql );
	}
	/**
	 * 将大写字母都转换为下划线风格
	 * @param array|string $data        	
	 * @return mix
	 */
	protected function upperToUnderline($data) {
		if (is_array ( $data )) {
			foreach ( $data as $key => $vo ) {
				$key = $this->upperToUnderline ( $key );
				$dataVo [$key] = $vo;
			}
			return $dataVo;
		} else {
			return trim ( strtolower ( preg_replace ( '/[A-Z]/', '_\\0', $data ) ), '_' );
		}
	}
	/**
	 * 取别名
	 * @param string $name        	
	 * @param string $alias        	
	 * @return mix
	 */
	protected function alias($name, $alias = null) {
		static $_alias = array ();
		if (empty ( $alias )) {
			return $_alias [$name];
		} else {
			$_alias [$name] = $alias;
			return true;
		}
	}
	/**
	 * 更改表名
	 *
	 * @param mix $table
	 *        	table(array('User'=>'user'));	查询前缀+User表,并重新命名为user
	 *        	table('user');					user
	 * @return Model
	 */
	public function table($table) {
		$this->table=$this->oldTable=null;
		$table = $this->upperToUnderline ( $table );
		if (is_string ( $table )) {
			$this->table = '`' . C ( 'DB_PREFIX' ) . $table . '`';
			$this->oldTable = C ( 'DB_PREFIX' ) . $table;
			$this->alias ( C ( 'DB_PREFIX' ) . $table, C ( 'DB_PREFIX' ) . $table );
		}
		if (is_array ( $table )) {
			$flag = true;
			foreach ( $table as $key => $vo ) {
				if ($flag) {
					$flag = false;
				} else {
					$this->table .= ',';
				}
				$this->table .= '`' . C ( 'DB_PREFIX' ) . $key . '` as `' . $vo . '`';
				$this->oldTable [] = C ( 'DB_PREFIX' ) . $key;
				$this->alias ( C ( 'DB_PREFIX' ) . $key, $vo );
			}
		}
		return $this;
	}
	// order排序
	public function order($order) {
		! empty ( $order ) and $this->order = $order;
		return $this;
	}
	// join
	public function join($join) {
		$this->join = $join;
		empty ( $this->field ) and $this->field ( '*' );
		return $this;
	}
	// group分组
	public function group($group) {
		! empty ( $group ) and $this->group = $group;
		return $this;
	}
	//取出二维数组,以$field作为键值(索引)
	public function twoArrayField($field){
		$this->twoArrayField=$field;
		return $this;
	}
	//取出以keyField为键值(索引),oneField为值的一维数组
	public function oneField($oneField,$keyField=null){
		empty($this->field) and $this->field="{$oneField},{$keyField}";
		$this->oneArray=true;
		$this->oneField=$oneField;
		$this->keyField=$keyField;
		return $this;
	}
	//强制使用索引
	public function force($force){
		$this->force=$force;
		return $this;
	}
	/**
	 * 查询的字段 默认为表里的各个字段
	 * field(array('user.id','user.username',array('title','t')))
	 * field('id,username');
	 * @param mix $fields        	
	 * @return Model
	 */
	public function field($fields = null,$oneArray=null) {
		$this->oneArray=$oneArray;
		if (empty ( $fields )) {
			$structure = $this->getStructure ();
			// 判断返回值是一维数组还是二维数组
			if (isset ( $structure ['field'] )) {
				$this->field ( $structure ['field'],$this->oneArray );
			} else {
				foreach ( $this->oldTable as $key => $table ) {
					$fields = $key == 0 ? '' : ',';
					foreach ( $structure [$key] ['field'] as $i => $vo ) {
						if ($i != 0) {
							$fields .= ',';
						}
						$fields .= $this->alias ( $table ) . '.' . $vo;
					}
					$this->field .= $fields;
				}
			}
		} else if (is_string ( $fields )) {
			$this->oneField=$this->field = $fields;
		} else if (is_array ( $fields )) {
			$flag = self::NO_AND;
			foreach ( $fields as $field ) {
				if (is_array ( $field )) {
					$array = each ( $field );
					$this->oneField=$array ['value'];
					$flag = $this->getOneField ( $array ['key'], $flag );
					$this->field .= ' as ';
					$flag = self::NO_AND;
					$flag = $this->getOneField ( $array ['value'], $flag );
				} else {
					$flag = $this->getOneField ( $field, $flag );
				}
			}
		}
		return $this;
	}
	/**
	 * 获取一个field 如果是date那么就是`date` 如果是user.id那么就是user.id
	 *
	 * @param string $field        	
	 * @param int $flag        	
	 * @return string
	 */
	private function getOneField($field, $flag) {
		if (strpos ( $field, '.' ) == false) {
			switch ($flag) {
				case self::NO_AND :
					$flag = self::YES_AND;
					break;
				case self::YES_AND :
					$this->field .= ',';
					break;
			}
			$this->field .= '`' . $field . '`';
		} else {
			switch ($flag) {
				case self::NO_AND :
					$flag = self::YES_AND;
					break;
				case self::YES_AND :
					$this->field .= ',';
					break;
			}
			$this->field .= $field;
		}
		return $flag;
	}
	// limit
	public function limit($limit) {
		! empty ( $limit ) and $this->limit = $limit;
		return $this;
	}
	public function having($having) {
		$this->having = $having;
		return $this;
	}
	public function lockTableRead($tableName){
		$tableName=strtolower($tableName);
		return $this->query("lock table {$this->config['DB_PREFIX']}{$tableName} read");
	}
	public function lockTableWrite($tableName){
		$tableName=strtolower($tableName);
		return $this->query("lock table {$this->config['DB_PREFIX']}{$tableName} write");
	}
	public function unlockTable(){
		return $this->query('unlock tables');
	}
	// 事务开启
	public function begin() {
		return $this->query ( 'begin' );
	}
	// 事务提交
	public function commit() {
		return $this->query ( 'commit' );
	}
	// 事务回滚
	public function rollback() {
		return $this->query ( 'rollback' );
	}
	/**
	 * where条件查询
	 * @param mix $map        	
	 * @return Model
	 */
	public function where($map) {
		if(empty($map)){
			return $this;
		}
		if (is_array ( $map )) {
			$map = $this->filter ( $map );
			$flag = self::NO_AND;
			foreach ( $map as $key => $value ) {
				if (strpos ( $key, '|' )) {
					$keys = explode ( '|', $key );
					$this->where .= '(';
					foreach ( $keys as $i => $keyVo ) {
						$i == 0 or $flag = self::YES_OR;
						if (is_array ( $value )) {
							$flag = $this->getOneWhere ( $keyVo, $value [$i], $flag );
						} else {
							$flag = $this->getOneWhere ( $keyVo, $value, $flag );
						}
					}
					$this->where .= ')';
				} else {
					$flag = $this->getOneWhere ( $key, $value, $flag );
				}
			}
		}
		if (is_string ( $map )) {
			$this->where = $map;
		}
		return $this;
	}
	// 单个解析where
	private function getOneWhere($key, $value, $flag = NO_AND) {
		switch ($flag) {
			case self::NO_AND :
				$flag = self::YES_AND;
				break;
			case self::YES_AND :
				$this->where .= ' and ';
				break;
			case self::YES_OR :
				$this->where .= ' or ';
				$flag = self::YES_AND;
				break;
		}
		$this->where .= '(';
		$this->where .= strpos ( $key, '.' ) ? $key : '`' . $key . '`';
		if (is_array ( $value )) {
			switch ($value [0]) {
				case 'in' :
					is_array ( $value [1] ) and $value [1] = implode ( ',', $value [1] );
					$this->where .= ' in(' . $value [1] . ')';
					break;
				case 'between' :
					$this->where .= ' between \'' . $value [1] [0] . '\' and \'' . $value [1] [1] . '\'';
					break;
				default :
					$this->where .= ' ' . $value [0] . '\'' . $value [1] . '\'';
					break;
			}
		} else if (is_int ( $value )||is_float($value)) {
			$this->where .= '=' . $value;
		} else {
			$this->where .= '=' . '\'' . $value . '\'';
		}
		$this->where .= ')';
		return $flag;
	}
	
	// 根据条件获取sql语句
	public function getSql() {
		empty ( $this->field ) and $this->field (null,$this->oneArray);
		$this->sql = 'select ';
		$this->sql .= $this->field;
		$this->sql .= ' from ' . $this->table;
		empty($this->force) or $this->sql.=" force index({$this->force})";
		empty ( $this->join ) or $this->sql .= ' ' . $this->join;
		empty ( $this->where ) or $this->sql .= ' where ' . $this->where;
		empty ( $this->group ) or $this->sql .= ' group by ' . $this->group;
		empty ( $this->having ) or $this->sql .= ' having ' . $this->having;
		empty ( $this->order ) or $this->sql .= ' order by ' . $this->order;
		empty ( $this->limit ) or $this->sql .= ' limit ' . $this->limit;
		return $this->sql;
	}
	/**
	 * 计算表中数据
	 *
	 * @return int
	 */
	public function count($sql='') {
		empty($sql) and $sql=$this->getSql();
		$sql=preg_replace('/(select)(.*?)(from)/','$1 count(*) count $3',$sql,1);//只替换一次
		$data = $this->query ( $sql);
		$this->initVar();
		return $data['0']['count'];
	}
	/**
	 * 查询数据库,获取二维数组
	 *
	 * @return array bool
	 */
	public function select() {
		$this->getSql ();
		return $this->query ( $this->sql );
	}
	public function selectForUpdate() {
		$this->getSql ();
		$this->sql.=' for update';
		return $this->query ( $this->sql );
	}
	/**
	 * 查询并且for update
	 * return array();
	 */
	public function findForUpdate(){
		$this->getSql ();
		$this->sql .= ' limit 1 for update';
		return $this->find($this->sql);
	}
	/**
	 * 查询数据
	 *
	 * @return array
	 */
	public function find($sql='') {
		if(empty($sql)){
			$this->getSql ();
			$this->sql .= ' limit 1';
		}else{
			$this->sql=$sql;
		}
		$data = $this->query ( $this->sql );
		if(empty($data))
			return $data;
		else
			return current($data);
	}
	/**
	 * 数据是否有存在
	 * @return boolean
	 */
	public function isExists(){
		if($this->mysqlFetchArray($this->mysqlQuery($this->getSql()))){
			return true;
		}else{
			return false;
		}
	}
	/**
	 * 获取最后一条sql语句
	 *
	 * @return string
	 */
	public function getLastSql() {
		return $this->sql;
	}
	/**
	 * 获取错误信息
	 *
	 * @return string
	 */
	public function getError() {
		return $this->error;
	}
	/**
	 * 获取表结构,并缓存起来
	 * @param string $table        	
	 * @return array
	 */
	protected function getStructure($table = null) {
		empty ( $table ) and $table = $this->oldTable;
		if (is_array ( $table )) {
			foreach ( $this->oldTable as $table ) {
				$data [] = $this->getStructure ( $table );
			}
		} else if (is_string ( $table )) {
			static $_structure = array ();
			if (isset ( $_structure [$table] )) {
				return $_structure [$table];
			}
			$data=null;
			//如果不是debug模式,则查询表结构缓存文件
			if(DEBUG==false){
				$data = $this->fileExport ( $table, null, 0, CACHE_TABLE_PATH );
			}else{
				$data=null;
			}
			if ($data == null) {
				$oneArray=$this->oneArray;
				$twoArrayFiled=$this->twoArrayField;
				$this->twoArrayField=null;
				$this->oneArray=null;
				$structure = $this->query ( 'describe ' . $table ,false);
				$this->sql = '';
				foreach ( $structure as $vo ) {
					if ($vo ['Key'] == 'PRI') {
						$data ['primary'] = $vo ['Field'];
						$data ['auto_increment'] = $vo ['Extra'];
					}
					$data ['field'] [] = $vo ['Field'];
					$data ['type'] [] = $vo ['Type'];
					$data ['null'] [] = $vo ['Null'];
					$data ['default'] [] = $vo ['Default'];
					$data ['extra'] [] = $vo ['Extra'];
				}
				$this->fileExport ( $table, $data, null, CACHE_TABLE_PATH );
				$_structure [$table] = $data;
				$this->oneArray=$oneArray;
				$this->twoArrayField=$twoArrayFiled;
			}
		}
		return $data;
	}
	/**
	 * 获取表主键
	 *
	 * @param string $table        	
	 * @return mix
	 */
	protected function getPrimaryKey($table = null) {
		empty ( $table ) and $table = $this->oldTable;
		static $_primaryKey = array ();
		if (isset ( $_primaryKey [$table] )) {
			return $_primaryKey [$table];
		}
		$structure = $this->getStructure ( $table );
		$_primaryKey [$table] = $structure ['primary'];
		return $_primaryKey [$table];
	}
	/**
	 * 设置/读取缓存 和F的区别是不用序列化字段,小文件速度更快
	 *
	 * @param string $name        	
	 * @param anytype $data        	
	 * @param int $time
	 *        	单位为秒 读取时才判断
	 * @throws Exception
	 * @return mixed NULL
	 */
	protected function fileExport($name, $data = null, $time = null, $path = null) {
		empty ( $path ) and $path = CACHE_EXPORT_PATH;
		if (isset ( $data )) {
			if (is_dir($path)||mkdir ( $path ,0777,true)) {
				if (file_put_contents ( $path . '/' . $name . C ( 'CACHE_TPL_SUFFIX' ), '<?php return ' . var_export ( $data, true ) . ';' ) > 0) {
					return true;
				} else {
					throw new Exception ( "生成缓存文件失败" );
				}
			} else {
				throw new Exception ( "权限不够,生成缓存目录失败" );
			}
		} else {
			if (file_exists ( $file = $path . '/' . $name . C ( 'CACHE_TPL_SUFFIX' ) )) {
				is_null ( $time ) and $time = C ( 'CACHE_EXPIRE' );
				if ($time == 0 || $time + filemtime ( $file ) >= strtotime ( 'now' )) {
					$content = require $file;
					return $content;
				} else {
					return null;
				}
			} else {
				return null;
			}
		}
	}
	/**
	 * 获取当前操作,是新增数据还是更新数据
	 *
	 * @param array $data        	
	 * @return bool
	 */
	private function getOperation($data) {
		if (array_key_exists ( $this->getPrimaryKey (), $data )) {
			return self::MODEL_UPDATE;
		} else {
			return self::MODEL_INSERT;
		}
	}
	/**
	 * 进行自动验证,自动完成
	 * 自动验证规则
	 * array(
	 *
	 * @param
	 *        	string field	验证字段
	 * @param
	 *        	string rule 验证规则
	 * @param
	 *        	string error	错误提示
	 * @param
	 *        	mix [addition]	附加规则
	 * @param
	 *        	int [condition]	验证条件:
	 *        	0:存在就验证EXISTS_VAILIDATE(默认)	1:必须验证:EXISTS_VAILIDATE
	 * @param
	 *        	int [operation]	验证条件2:	0:插入数据库时验证:MODEL_INSERT
	 *        	1:更新数据库时验证:MODEL_UPDATE 2:插入数据库和更新数据库都验证:MODEL_BOTH
	 *        	)f
	 * @param array $data        	
	 * @return boolean
	 */
	public function create(&$data = null) {
		$data == null and $data = &$_POST;
		// 进行自动验证
		if (isset ( $this->_validate )) {
			$keys = array (
					'field',
					'rule',
					'error',
					'addition',
					'condition',
					'operation' 
			);
			foreach ( $this->_validate as $vo ) {
				$keyList = $keys;
				if (! $this->isValidate ( $data, array_combine ( array_splice ( $keyList, 0, count ( $vo ) ), $vo ) )) {
					$this->error = $vo [2];
					return false;
					break;
				}
			}
		}
		// 进行自动完成
		if (isset ( $this->_auto )) {
			$keys = array (
					'field',
					'rule',
					'addition',
					'operation' 
			);
			foreach ( $this->_auto as $vo ) {
				$keyList = $keys;
				$this->isAuto ( $data, array_combine ( array_splice ( $keyList, 0, count ( $vo ) ), $vo ) );
			}
		}
		// 进行token验证
		if (C ( 'TOKEN_POWER' ) == true && ! Token::validateToken ()) {
			$this->error = Token::getError ();
			return false;
		}
		return true;
	}
	/**
	 * 是否进行验证
	 *
	 * @param array $data        	
	 * @param array $value        	
	 * @return boolean
	 */
	private function isValidate($data, $value) {
		( int ) $value ['condition'] = empty ( $value ['condition'] ) ? self::MUST_VALIDATE : $value ['condition'];
		( int ) $value ['operation'] = empty ( $value ['operation'] ) ? self::MODEL_BOTH : $value ['operation'];
		switch ($value ['condition']) {
			case self::EXISTS_VAILIDATE :
				if ((isset ( $data [$value ['field']] )) && ($value ['operation'] == self::MODEL_BOTH || $value ['operation'] == $this->getOperation ( $data ))) {
					return $this->validate ( $data, $value );
				} else {
					return true;
				}
				break;
			case self::MUST_VALIDATE :
				$operation=$this->getOperation($data);
				if (! isset ( $data [$value ['field']] )&&($value ['operation'] == self::MODEL_BOTH || $value ['operation'] == $operation)) {
					return false;
				} else if ($value ['operation'] == self::MODEL_BOTH || $value ['operation'] == $operation) {
					return $this->validate ( $data, $value );
				} else {
					return true;
				}
				break;
			default :
				return false;
				break;
		}
	}
	/**
	 * 进行自动验证
	 * @param array $data        	
	 * @param array $value        	
	 * @return boolean
	 */
	private function validate($data, $value) {
		switch ($value ['rule']) {
			case 'username':
				return is_check_string($data[$value['field']]);
				break;
			case 'phone':
				return is_phone($data[$value['field']]);
				break;
			case 'require' :
				if ($data [$value ['field']] === null || $data [$value ['field']] === '') {
					return false;
				} else {
					return true;
				}
				break;
			case 'in' :
				if (is_string ( $value ['addition'] )) {
					$value ['addition'] = explode ( ',', $value ['addition'] );
				}
				if (in_array ( $data [$value ['field']], $value ['addition'] )) {
					return true;
				} else {
					return false;
				}
				break;
			case 'between' :
				if (is_string ( $value ['addition'] )) {
					$value ['addition'] = explode ( ',', $value ['addition'] );
				}
				if ($data [$value ['field']] >= $value ['addition'] [0] && $data [$value ['field']] <= $value ['addition'] [1]) {
					return true;
				} else {
					return false;
				}
				break;
			case 'email' :
				return is_email( $data [$value ['field']]);
				break;
			case 'url' :
				return is_url( $data [$value ['field']]);
				break;
			case 'confirm' :
				if (md5 ( $data [$value ['field']] ) == md5 ( $data [$value ['addition']] )) {
					return true;
				} else {
					return false;
				}
				break;
			case 'ip' :
				return is_ip($data [$value ['field']]);
				break;
			case 'function' :
				if (is_array ( $value ['addition'] )) {
					$function = current($value ['addition']);
					array_shift ( $value ['addition'] );
					if ($function ( implode ( ',', $value ['addition'] ) )) {
						return true;
					} else {
						return false;
					}
				}
				if (is_string ( $value ['addition'] )) {
					if ($value ['addition'] ($data[$value['field']])) {
						return true;
					} else {
						return false;
					}
				}
				break;
			case 'length' :
				if (is_string ( $value ['addition'] )) {
					$value ['addition'] = explode ( ',', $value ['addition'] );
				}
				switch (count ( $value ['addition'] )) {
					case 1 :
						if (strlen ( $data [$value ['field']] ) == current($value ['addition'])) {
							return true;
						} else {
							return false;
						}
						break;
					case 2 :
						if (mb_strlen ( $data [$value ['field']], 'UTF8' ) >= $value ['addition'] [0] && mb_strlen ( $data [$value ['field']], 'UTF8' ) <= $value ['addition'] [1]) {
							return true;
						} else {
							return false;
						}
						break;
					default :
						return false;
						break;
				}
				break;
			case 'maxLength' :
				if (mb_strlen ( $data [$value ['field']], 'UTF8' ) <= intval ( $value ['addition'] )) {
					return true;
				} else {
					return false;
				}
			case 'unique' :
				if($this->where ( array (
						$value ['field'] => $data [$value ['field']] 
				) )->isExists()){
					return false;
				}else{
					return true;
				}
				break;
			case 'regex' :
				if (preg_match ( $value ['addition'], $data [$value ['field']] )) {
					return true;
				} else {
					return false;
				}
				break;
			case 'number':
				return is_number($data [$value ['field']]);
				break;
			case 'positive_number':
				return is_positive_number($data [$value ['field']]);
				break;
			case 'decimal':
				return is_decimal($data [$value ['field']]);
				break;
			case 'positive_decimal':
				return is_positive_decimal($data [$value ['field']]);
				break;
			case 'english':
				return is_english($data[$value['field']]);
				break;
			case 'chinese':
				return is_chinese($data[$value['field']]);
				break;
			case 'card':
				return is_card($data[$value['field']]);
				break;
			default :
				return true;
				break;
		}
	}
	/**
	 * 是否进行自动完成
	 *
	 * @param array $data        	
	 * @param array $value        	
	 */
	private function isAuto(&$data, $value) {
		( int ) $value ['operation'] = empty ( $value ['operation'] ) ? self::MODEL_INSERT : $value ['operation'];
		if (($value ['operation'] == self::MODEL_BOTH) || ($value ['operation'] == $this->getOperation ( $data ))) {
			$this->auto ( $data, $value );
		}
	}
	/**
	 * 进行自动完成操作
	 *
	 * @param array $data        	
	 * @param array $value        	
	 */
	private function auto(&$data, $value) {
		switch ($value ['rule']) {
			case 'function' :
				if (is_string ( $value ['addition'] )) {
					$value ['addition'] = explode ( ',', $value ['addition'] );
				}
				$function = current($value ['addition']);
				array_shift ( $value ['addition'] );
				$data [$value ['field']] = $function ( implode ( ',', $value ['addition'] ) );
				break;
			case 'equalTo' :
				!isset($data[$value['field']])||empty ( $data [$value ['field']] ) and $data [$value ['field']] = $data [$value ['addition']];
				break;
			case 'const' :
				!isset($data[$value['field']])||empty ( $data [$value ['field']] ) and $data [$value ['field']] = $value ['addition'];
				break;
			case 'callback' :
				isset($data[$value['field']]) and $data [$value ['field']] = $value ['addition'] ( $data [$value ['field']] );
				break;
			default :
				break;
		}
	}
}
?>