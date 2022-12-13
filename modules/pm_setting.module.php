<?php

(!defined('IN_PowerBB')) ? die() : '';

$CALL_SYSTEM					=	array();
$CALL_SYSTEM['PM'] 				= 	true;
$CALL_SYSTEM['ICONS'] 			= 	true;
$CALL_SYSTEM['TOOLBOX'] 		= 	true;
$CALL_SYSTEM['FILESEXTENSION'] 	= 	true;
$CALL_SYSTEM['ATTACH'] 			= 	true;

define('JAVASCRIPT_PowerCode',true);



define('CLASS_NAME','PowerBBPrivateMassegeMOD');

include('common.php');
class PowerBBPrivateMassegeMOD
{
	function run()
	{
		global $PowerBB;

		if (!$PowerBB->_CONF['info_row']['pm_feature'])
		{
            $PowerBB->functions->ShowHeader();
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['no_pm']);
		}

		/** Can't use the private massege system **/
		if (!$PowerBB->_CONF['rows']['group_info']['use_pm'])
		{
            $PowerBB->functions->ShowHeader();
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Cant_use_pm']);
		}
		/** **/

		/** Visitor can't use the private massege system **/
		if (!$PowerBB->_CONF['member_permission'])
		{
            $PowerBB->functions->ShowHeader();
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Cant_see_pm']);
		}
		/** **/

		if ($PowerBB->_GET['setting'])
		{
			if ($PowerBB->_GET['index'])
			{
				$this->_SettingIndex();
			}
			elseif ($PowerBB->_GET['start'])
			{
				$this->_SettingStart();
			}
		}
		else
		{
			header("Location: index.php");
			exit;
		}

		$PowerBB->functions->GetFooter();
	}

	function _SettingIndex()
	{
		global $PowerBB;

		$PowerBB->functions->ShowHeader();

		$PowerBB->template->display('pm_setting');
	}

	function _SettingStart()
	{
		global $PowerBB;

		$PowerBB->functions->ShowHeader();

		if ($PowerBB->_POST['autoreply']
			and (!isset($PowerBB->_POST['title']) or !isset($PowerBB->_POST['msg'])))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_fill_in_all_the_information']);
		}
        $PowerBB->_POST['title'] = $PowerBB->functions->CleanVariable($PowerBB->_POST['title'],'trim');
        $PowerBB->_POST['msg'] = $PowerBB->functions->CleanVariable($PowerBB->_POST['msg'],'trim');
        $PowerBB->_POST['autoreply'] = $PowerBB->functions->CleanVariable($PowerBB->_POST['autoreply'],'trim');
        $PowerBB->_POST['pm_senders_msg'] = $PowerBB->functions->CleanVariable($PowerBB->_POST['pm_senders_msg'],'trim');
        $PowerBB->_POST['pm_senders'] = $PowerBB->functions->CleanVariable($PowerBB->_POST['pm_senders'],'trim');

        $PowerBB->_POST['title'] = strip_tags($PowerBB->_POST['title']);
        $PowerBB->_POST['msg'] = strip_tags($PowerBB->_POST['msg']);
        $PowerBB->_POST['autoreply'] = strip_tags($PowerBB->_POST['autoreply']);
        $PowerBB->_POST['pm_senders_msg'] = strip_tags($PowerBB->_POST['pm_senders_msg']);
        $PowerBB->_POST['pm_senders'] = strip_tags($PowerBB->_POST['pm_senders']);

		$PowerBB->_POST['title'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['title'],'sql');
		$PowerBB->_POST['msg']   = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['msg'] ,'sql');
		$PowerBB->_POST['autoreply'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['autoreply'],'sql');
		$PowerBB->_POST['pm_senders_msg'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['pm_senders_msg'],'sql');
		$PowerBB->_POST['pm_senders'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['pm_senders'],'sql');



		$UpdateArr 				= 	array();
		$UpdateArr['field']		=	array();

		$UpdateArr['field']['autoreply'] 		= 	$PowerBB->_POST['autoreply'];
		$UpdateArr['field']['autoreply_title'] 	= 	$PowerBB->_POST['title'];
		$UpdateArr['field']['autoreply_msg'] 	= 	$PowerBB->_POST['msg'];
		$UpdateArr['field']['pm_senders'] 		= 	$PowerBB->_POST['pm_senders'];
		$UpdateArr['field']['pm_senders_msg'] 	= 	$PowerBB->_POST['pm_senders_msg'];
		$UpdateArr['where']						=	array('id',$PowerBB->_CONF['member_row']['id']);

		$update = $PowerBB->core->Update($UpdateArr,'member');

		if ($update)
		{
		    	$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Data_has_been_updated_successfully']);
			$PowerBB->functions->redirect('index.php?page=pm_setting&amp;setting=1&amp;index=1');
		}
	}
}

?>
