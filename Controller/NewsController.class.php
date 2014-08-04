<?php
/**
 * @author 邹颢	zouhao619@gmail.com
 */
class NewsController extends CommonController{
	public function index(){
		$where='';
		isset($_GET['category_id']) and $where='category_id='.intval($_GET['category_id']);
		$count=M('News')->where($where)->count();
		$p=new Page($count);
		$list=M('News')->field('id,create_time,title,content,1 type')->where($where)->limit($p->firstRow.','.$p->listRows)->order('id desc')->select();
		$this->assign('list',$list);
		$this->assign('page',$p->show());
		$this->display();
	}
	public function read(){
		isset($_GET['id']) or exit('缺少参数');
		$id=intval($_GET['id']);
		$news=M('News')->where("id={$id}")->find();
		$this->assign('info',$news);
		$this->display();
	}
	public function search(){
		!isset($_GET['keyword'])||empty($_GET['keyword']) and exit('必须输入参数');
		$keyword=filter_trim($_GET['keyword']);
		/**记录搜索关键字*/
		$tongji_search['user_id']=isset($_SESSION['user']['id'])?$_SESSION['user']['id']:0;
		$tongji_search['keyword']=$keyword;
		$tongji_search['time_stamp']=date('Y-m-d H:i:s');
		M('TongjiSearch')->save($tongji_search);
			
		$sql="select id,create_time,title,content,type from 
		(select id,create_time,title,content,1 type from ".C('DB_PREFIX')."news where title like '%{$keyword}%'
		union
		select id,create_time,title,content,2 type from ".C('DB_PREFIX')."question where title like '%{$keyword}%')
		 a";
		$count=mysql_count($sql);
		$p=new Page($count);
		$sql.=' limit '.$p->firstRow.','.$p->listRows;
		$list=M()->query($sql);
		$this->assign('list',$list);
		$this->assign('page',$p->show());
		$this->display('News/index');
	}
}