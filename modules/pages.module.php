<?php

(!defined('IN_PowerBB')) ? die() : '';

$CALL_SYSTEM			=	array();
$CALL_SYSTEM['PAGES'] 	= 	true;
define('CLASS_NAME','PowerBBPagesMOD');

include('common.php');
class PowerBBPagesMOD
{
	function run()
	{
		global $PowerBB;

		if ($PowerBB->_GET['show'])
		{
			$this->_ShowPage();
		}
		else
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}

		$PowerBB->functions->GetFooter();
	}

	function _ShowPage()
	{
		global $PowerBB;

		$PowerBB->functions->ShowHeader();

		$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');

		if (empty($PowerBB->_GET['id']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}

		$PageArr 			= 	array();
		$PageArr['where'] 	= 	array('id',$PowerBB->_GET['id']);

		$PowerBB->_CONF['template']['GetPage'] = $PowerBB->core->GetInfo($PageArr,'pages');
        $PowerBB->_CONF['template']['GetPage']['html_code']= str_replace('../look/','look/',$PowerBB->_CONF['template']['GetPage']['html_code']);

		if (!$PowerBB->_CONF['template']['GetPage'])
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['pagenotavailable']);
		}

		$PowerBB->template->display('show_page');
	}
}

?>
