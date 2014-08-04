<?php
/**
 * @author 邹颢	zouhao619@gmail.com
 */
class UserController extends CommonController{
	private function _validateCode(){
		if($_SESSION['verify'] != md5($_POST['code'])){
			exit(json_encode(array('result'=>1000,'msg'=>'验证不正确')));
		}
	}
	public function index(){
		isset($_SESSION['user']['id']) or $this->redirect('Index/index');
		$this->display();
	}
	public function login(){
		$this->_validateCode();
		$username=filter_trim($_POST['username']);
		$user=M('User')->where("username='{$username}'")->find();
		if(empty($user)){
			$rs['result']=2;
			$rs['msg']='用户名不存在';
		}else if($user['password']!=md5($_POST['password'])){
			$rs['result']=3;
			$rs['msg']='密码不正确';
		}else{
			$_SESSION['user']=$user;
			$u['id']=$user['id'];
			$u['last_login_time']=date('Y-m-d H:i:s');
			$u['last_login_ip']=get_client_ip();
			M('User')->update($u);
			$rs['result']=1;
		}
		echo json_encode($rs);
	}
	public function register(){
		$this->_validateCode();
		$db=D('User');
		if(!$db->create()){
			echo json_encode(array('result'=>2,'msg'=>$db->getError()));
			exit;
		}
		$id=$db->save($_POST);
		if($id){
			$_SESSION['user']=M('User')->where("id={$id}")->find();
			$rs['result']=1;
		}else{
			$rs['result']=3;
			$rs['msg']='注册失败';
		}
		echo json_encode($rs);
	}
	public function update(){
		isset($_SESSION['user']['id']) or exit;
		isset($_POST['password'])&&isset($_POST['repassword'])&&isset($_POST['old_password']) or exit;
		if($_SESSION['user']['password']!=md5($_POST['old_password'])){
			exit(json_encode(array('result'=>2,'msg'=>'原密码不正确')));
		}
		$password=md5($_POST['password']);
		$repassword=md5($_POST['repassword']);
		if($password!=$repassword){
			exit(json_encode(array('result'=>3,'msg'=>'两次输入密码不一致')));
		}
		$user['id']=$_SESSION['user']['id'];
		$user['password']=$password;
		if(M('User')->update($user)){
			$_SESSION['user']['password']=$password;
			$rs['result']=1;
		}else{
			$rs['result']=4;
			$rs['msg']='数据库插入失败';
		}
		echo json_encode($rs);
	}
	public function updateInfo(){
		$user=$_POST;
		$user['id']=$_SESSION['user']['id'];
		if(M('User')->update($user)){
			$_SESSION['user']=array_merge($_SESSION['user'],$user);
			$this->redirect();
		}else{
			$this->error('更新信息失败');
		}
	}
	public function layout(){
		$_SESSION['user']=null;
		session_destroy();
		$this->redirect();
	}
}