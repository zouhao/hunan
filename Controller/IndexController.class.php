<?php
/**
 * @author 邹颢	zouhao619@gmail.com
 */
class IndexController extends CommonController{
	public function index(){
		/**新闻类别*/
		$news_category_list=M('NewsCategory')->order('sort asc')->select();
		$this->assign('news_category_list',$news_category_list);
		/**最新文章*/
		$news_list=M('News')->twoArrayField('id')->order('id desc')->limit(10)->select();
		$this->assign('news_list',$news_list);
		$news_content_list=array();
		foreach($news_list as $k=>$v){
			$news_content_list[$k]['brief']=sub(strip_tags($v['content']),0,130);
			$news_content_list[$k]['detail']=strip_tags($v['content']);
		}
		$this->assign('news_content_list',$news_content_list);
		/**文章是否是我关注的*/
		$other_id=implode(',',array_merge(array(0),array_keys($news_list)));
		$news_attention_list=M('Attention')->field('other_id',true)->where("type='1' and other_id in ({$other_id})")->select();
		$this->assign('news_attention_list',$news_attention_list);
		/**我的关注*/
		if(isset($_SESSION['user'])){
			$where="where a.user_id={$_SESSION['user']['id']}";
			$sql='select id,title,content,create_time,type from (
				(select n.id,n.title,n.content,n.create_time,1 type
				from '.C('DB_PREFIX').'news n
		inner join '.C('DB_PREFIX')."attention a on a.other_id=n.id and a.type='1' {$where})
					union
					(select q.id,q.title,q.content,q.create_time,2 type
					from ".C('DB_PREFIX').'question q
		inner join '.C('DB_PREFIX')."attention a on a.other_id=q.id and a.type='2' {$where})
					) a order by create_time limit 10
					";
					$attention_list=M()->query($sql);
		}else{
			$attention_list=array();
		}
		$this->assign('attention_list',$attention_list);
		/**问答*/
		$question_list=M('Question')->twoArrayField('id')->order('id desc')->limit(10)->select();
		$this->assign('question_list',$question_list);
		/**问答是否是我关注的*/
		$other_id=implode(',',array_merge(array(0),array_keys($question_list)));
		if(isset($_SESSION['user'])){
			$question_attention_list=M('Attention')->field('other_id',true)->where("type='2' and other_id in ({$other_id}) and user_id={$_SESSION['user']['id']}")->select();
		}else{
			$question_attention_list=array();
		}
		$this->assign('question_attention_list',$question_attention_list);
		/**幻灯片*/
		$slider_list=M('Slider')->order('id desc')->select();
		$this->assign('slider_list',$slider_list);
		$this->display();
	}
	public function attention(){
		$condition['user_id']=$_SESSION['user']['id'];
		$condition['other_id']=intval($_GET['other_id']);
		$condition['type']=filter($_GET['type']);
		$attention=M('Attention')->field('id')->where($condition)->find();
		if(empty($attention)){
			if(M('Attention')->save($condition)){
				$rs['result']=2;
			}else{
				$rs['result']=3;
				$rs['msg']='关注失败';
			}
		}else{
			if(M('Attention')->delete("id={$attention['id']}")){
				$rs['result']=1;
			}else{
				$rs['result']=4;
				$rs['msg']='取消关注失败';
			}
		}
		echo json_encode($rs);
	}
}