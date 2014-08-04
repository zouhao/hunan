<?php
/**
 * @author 邹颢	zouhao619@gmail.com
 */
class QuestionController extends CommonController{
	public function save(){
		$data['title']=filter_trim_htmlspecialchars($_POST['title']);
		$data['content']=filter_trim_htmlspecialchars($_POST['content']);
		$data['user_id']=$_SESSION['user']['id'];
		$db=D('Question');
		if($db->create($data)){
			$id=$db->save($data);
			if($id){
				$rs['result']=1;
				$rs['url']=U("Question/read?id={$id}");
			}else{
				$rs['result']=3;
				$rs['msg']='发布问题失败';
			}
		}else{
			$rs['result']=2;
			$rs['msg']=$db->getError();
		}
		echo json_encode($rs);
	}
	public function index(){
		$where='';
		isset($_GET['category_id']) and $where='category_id='.intval($_GET['category_id']);
		$count=M('Question')->where($where)->count();
		$p=new Page($count);
		$list=M('Question')->field('id,create_time,title,content,2 type')->where($where)->limit($p->firstRow.','.$p->listRows)->order('id desc')->select();
		$this->assign('list',$list);
		$this->assign('page',$p->show());
		$this->display('News/index');
	}
	public function reply(){
		$question_reply['user_id']=$_SESSION['user']['id'];
		$question_reply['question_id']=intval($_POST['question_id']);
		$question_reply['content']=filter_trim_htmlspecialchars($_POST['content']);
		$question_reply['create_time']=date('Y-m-d H:i:s');
		if(M('QuestionReply')->save($question_reply)){
			$this->redirect();
		}else{
			$this->error('回复失败');
		}
	}
	public function read(){
		isset($_GET['id']) or exit('缺少参数');
		$id=intval($_GET['id']);
		$question=M(array('Question'=>'q'))->field('q.*,u.username')->
		join('inner join '.C('DB_PREFIX').'user u on u.id=q.user_id')->where("q.id={$id}")->find();
		$this->assign('info',$question);
		$where="question_id={$id}";
		$count=M('QuestionReply')->where($where)->count();
		$p=new Page($count);
		$question_list=M(array('QuestionReply'=>'q'))->field('q.*,u.username')->
		join('inner join '.C('DB_PREFIX').'user u on u.id=q.user_id')->
		where($where)->order('id desc')->limit($p->firstRow.','.$p->listRows)->select();
		$this->assign('question_list',$question_list);
		$this->assign('page',$p->show());
		$this->display();
	}
}