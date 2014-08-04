<?php
class TongjiController extends CommonController{
	public function userAccessLog(){
		$db=M(array('Tongji'=>'t'));
		$join='inner join '.C('DB_PREFIX').'user u on u.id=t.user_id';
		$count=$db->join($join)->count();
		$p=new Page($count);
		$list=$db->field('u.username,t.access_time,t.url,t.time_stamp')->join($join)
		->limit($p->firstRow.','.$p->listRows)->order('t.id desc')->select();
		$this->assign('page',$p->show());
		$this->assign('list',$list);
		$this->display();
	}
	public function userTongji(){
		$db=M('Tongji');
		$count=$db->group('url')->count();
		$p=new Page($count);
		$list=$db->field('count(*) count,url,sum(access_time) access_time')->limit($p->firstRow.','.$p->listRows)
		->group('url')->order('count desc')->select();
		$this->assign('page',$p->show());
		$this->assign('list',$list);
		$this->display();
	}
	public function search(){
		$db=M(array('TongjiSearch'=>'t'));
		$join='left join '.C('DB_PREFIX').'user u on u.id=t.user_id';
		$count=$db->join($join)->count();
		$p=new Page($count);
		$list=$db->field('u.username,t.keyword,t.time_stamp')->join($join)
		->limit($p->firstRow.','.$p->listRows)->order('t.id desc')->select();
		$this->assign('page',$p->show());
		$this->assign('list',$list);
		$this->display();
	}
	public function searchTongji(){
		$db=M('TongjiSearch');
		$count=$db->group('keyword')->count();
		$p=new Page($count);
		$list=$db->field('count(*) count,keyword')->order('count desc')->group('keyword')->select();
		$this->assign('page',$p->show());
		$this->assign('list',$list);
		$this->display();
	}
}