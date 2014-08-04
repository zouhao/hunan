<?php
class PublicController extends CommonController{
	public function verify (){
		Image::buildImageVerify();
	}
	public function tongji(){
		if(!empty($_SESSION['user'])){
			$tongji['user_id']=$_SESSION['user']['id'];
			$tongji['access_time']=$_GET['access_time'];
			$tongji['url']=$_GET['url'];
			$tongji['time_stamp']=date('Y-m-d H:i:s');
			M('Tongji')->save($tongji);
		}
	}
}