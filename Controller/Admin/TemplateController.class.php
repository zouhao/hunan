<?php
class TemplateController extends Controller{
	public function display($tpl = null, $createTpl = true) {
		if($tpl==null){
			$tpl=C('GROUP_NAME').'/'.C('CONTROLLER_NAME').'/'.C('METHOD_NAME');
		}
		parent::display($tpl,$createTpl);
	}
	/**
	 * 行业页面
	 *
	 * @param int $data['tradeId']
	 */
	public function selectTrade($data=array()) {
		$data['tradePid'] = 0;
		if (! empty ( $data['tradeId'] )) {
			$trade = M ( 'Trade' )->field ( 'pid' )->where ( "id={$data['tradeId']}" )->find ();
			if(empty($trade['pid'])){
				$data['tradePid']=$data['tradeId'];
			}else{
				$data['tradePid'] = $trade ['pid'];
			}
		}else{
			$data['tradeId']=0;
		}
		$this->assign ($data);
		$tradeList = M ( 'Trade' )->order ( 'sort asc' )->select ();
		$this->assign ( 'tradeList', $tradeList );
		$this->display ();
	}
	/**
	 * 上传缩略图
	 * @param string $img
	 */
	public function uploadFile($data=array('img'=>'','imageName'=>'logo')) {
		!isset($data['img']) and $data['img']='';
		!isset($data['imageName']) and $data['imageName']='logo';
		$this->assign($data);
		$this->display ();
	}
	/**
	 * 上传多张图片
	 * @param array $data  
	 */
	public function uploadMultiFile($data=array('img'=>array(),'imageName'=>'image')){
		!isset($data['img']) and $data['img']=array();
		!isset($data['imageName']) and $data['imageName']='image';
		$this->assign($data);
		$this->display();
	}
	/**
	 * 上传多张图片
	 * @param array $data
	 */
	public function uploadMultiFileText($data=array('img'=>array(),'imageName'=>'image')){
		!isset($data['img']) and $data['img']=array();
		!isset($data['imageName']) and $data['imageName']='image';
		$this->assign($data);
		$this->display();
	}
	/**
	 * 地区选择框
	 * @param int $provinceId
	 * @param int $cityId
	 * @param int $areaId
	 */
	public function selectArea($data){
		!isset($data['provinceId']) and $data['provinceId']='';
		!isset($data['cityId']) and $data['cityId']='';
		!isset($data['areaId']) and $data['areaId']='';
		!isset($data['provinceHeader']) and $data['provinceHeader']=true;
		!isset($data['cityHeader']) and $data['cityHeader']=true;
		!isset($data['areaHeader']) and $data['areaHeader']=true;
		!isset($data['areaHidden']) and $data['areaHidden']=false;
		$area=require FUNCTION_PATH.'/area.data.php';
		$this->assign($data);
		$this->assign('area',$area);
		$this->display();
	}
	/**
	 * 地区查询框
	 * @param int $provinceId
	 * @param int $cityId
	 * @param int $areaId
	 */
	public function searchArea($data){
		$data['province_id']=isset($_GET['province_id'])?$_GET['province_id']:'';
		$data['city_id']=isset($_GET['city_id'])?$_GET['city_id']:'';
		$data['area_id']=isset($_GET['area_id'])?$_GET['area_id']:'';
		$area=require FUNCTION_PATH.'/area.data.php';
		$this->assign($data);
		$this->assign('area',$area);
		$this->display();
	}
	/**
	 * 当是管理员时会显示所属商家
	 * 当不是管理员时不会显示
	 * @param array $data
	 */
	public function inpuCompany($data=array()){
		!isset($data['company_name']) and $data['company_name']='company_id';
		!isset($data['company_val']) and $data['company_val']=0;
		$this->assign($data);
		$companyList=M('Company')->where("is_admin='0' and pid=0")->select();
		$this->assign('companyList',$companyList);
		$this->display();
	}
	public function putOn($data=array()){
		$area=require FUNCTION_PATH.'/area.data.php';
		if(!empty($data)){
			$this->assign('info',$data);
		}
		$this->assign('area',$area);
		$this->display();
	}
	/**
	 * 行业选择
	 * @param array $data
	 */
	public function selectIndustry($data=array()){
		!isset($data['industry_id_list']) and $data['industry_id_list']=array();
		$industry_list=require FUNCTION_PATH.'/industry.data.php';
		$this->assign('industry_list',$industry_list);
		$this->assign($data);
		$this->display();
	}
	/**
	 * 职能
	 * @param array $data
	 */
	public function selectPosition($data=array()){
		!isset($data['position_id_list']) and $data['position_id_list']=array();
		$position_list=require FUNCTION_PATH.'/position.data.php';
		$this->assign('position_list',$position_list);
		$this->assign($data);
		$this->display();
	}
}