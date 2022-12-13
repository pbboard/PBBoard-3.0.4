<?php

(!defined('IN_PowerBB')) ? die() : '';

define('IN_ADMIN',true);

$CALL_SYSTEM				=	array();
$CALL_SYSTEM['SECTION'] 	= 	true;



define('CLASS_NAME','PowerBBAjaxMOD');

include('../common.php');
class PowerBBAjaxMOD
{
	function run()
	{
		global $PowerBB;

		if ($PowerBB->_CONF['member_permission'])
		{
			if ($PowerBB->_GET['sections'])
			{
				if ($PowerBB->_GET['rename'])
				{
					$this->_SectionRename();
				}
			}
		}
	}

	function _SectionRename()
	{
		global $PowerBB;

		if (empty($PowerBB->_POST['title'])
			or empty($PowerBB->_POST['id']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}

		$SecArr 			= 	array();
		$SecArr['field'] 	= 	array();

		$SecArr['field']['title'] 	= 	$PowerBB->_POST['title'];
		$SecArr['where']			= 	array('id',$PowerBB->_POST['id']);

		$update = $PowerBB->core->Update($SecArr,'section');

		if ($update)
		{
			echo $PowerBB->_POST['title'];
		}
	}
}

?>
