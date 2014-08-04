<?php
/**
 * zouhao zouhao619@gmail.com
 */
class QuestionModel extends Model{
	protected $_validate=array(
		array('title','length','标题必须2-50位之间',array(2,50),self::MUST_VALIDATE,self::MODEL_INSERT),
		array('content','maxLength','内容不能超过255个字',255,self::MUST_VALIDATE,self::MODEL_INSERT),
	);
	protected $_auto=array(
		array('create_time','function',array('date','Y-m-d H:i:s')),
	);
}