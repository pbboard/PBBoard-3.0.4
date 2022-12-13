<?php
class PowerBBInfo
{
	var $PowerBB;
	function __construct($PowerBB)
	{
		$this->Engine = $PowerBB;
	}

 	function UpdateInfo($param)
 	{
 		if (!isset($param['var_name']))
 		{
 			trigger_error('ERROR::NEED_PARAMETER -- FROM UpdateInfo() -- EMPTY var_name',E_USER_ERROR);
 		}
 		$field = array('value'		=>	$param['value']);
		$where = array('var_name',$param['var_name']);
		$query = $this->Engine->records->Update($this->Engine->table['info'],$field,$where);
		return ($query) ? true : false;
 	}
}
?>
