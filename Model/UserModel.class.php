<?php
/**
 * zouhao zouhao619@gmail.com
 */
class UserModel extends Model{
	protected $_validate=array(
		array('username','unique','已经存在相同的用户名',null,self::MUST_VALIDATE,self::MODEL_INSERT),
		array('username','username','用户名必须填写且只能为中文、数字、英文和下划线',null,self::MUST_VALIDATE,self::MODEL_INSERT),
		array('username','length','用户名必须在4-20位之间',array(2,20),self::MUST_VALIDATE,self::MODEL_INSERT),
		array('password','length','密码必须6-32位之间',array(6,32),self::EXISTS_VAILIDATE,self::MODEL_BOTH)
	);
	protected $_auto=array(
		array('create_time','function',array('date','Y-m-d H:i:s')),
		array('password','callback','md5',self::MODEL_BOTH),
		array('last_login_ip','function','get_client_ip'),
		array('last_login_time','function',array('date','Y-m-d H:i:s')),
	);
}