<?php
/**
 * zouhao zouhao619@gmail.com
 */
class SliderModel extends Model{
	protected $_auto=array(
		array('create_time','function',array('date','Y-m-d H:i:s')),
	);
}