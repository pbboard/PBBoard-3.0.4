<?php

(!defined('IN_PowerBB')) ? die() : '';

define('IN_ADMIN',true);

$CALL_SYSTEM					=	array();
$CALL_SYSTEM['ANNOUNCEMENT'] 	= 	true;



define('CLASS_NAME','PowerBBAnnouncementMOD');

include('../common.php');
class PowerBBAnnouncementMOD extends _functions
{
	function run()
	{
		global $PowerBB;

		if ($PowerBB->_CONF['member_permission'])
		{
			$PowerBB->template->display('header');

			if ($PowerBB->_CONF['rows']['group_info']['admincp_adminads'] == '0')
			{
			  $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
			}

			if ($PowerBB->_GET['add'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_AddMain();
				}
				elseif ($PowerBB->_GET['start'])
				{
					$this->_AddStart();
				}
			}
			elseif ($PowerBB->_GET['control'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_ControlMain();
				}
			}
			elseif ($PowerBB->_GET['edit'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_EditMain();
				}
				elseif ($PowerBB->_GET['start'])
				{
					$this->_EditStart();
				}
			}
			elseif ($PowerBB->_GET['del'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_DelMain();
				}
				elseif ($PowerBB->_GET['start'])
				{
					$this->_DelStart();
				}
			}

			$PowerBB->template->display('footer');
		}
	}

	function _AddMain()
	{
		global $PowerBB;

		$PowerBB->template->display('announcement_add');
	}

	function _AddStart()
	{
		global $PowerBB;

		if (empty($PowerBB->_POST['title'])
			or empty($PowerBB->_POST['text']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_fill_in_all_the_information']);
		}

		$AnnArr 			= 	array();
		$AnnArr['field']	=	array();

		$AnnArr['field']['title'] 	= 	$PowerBB->_POST['title'];
		$AnnArr['field']['text'] 	= 	$PowerBB->_POST['text'];
		$AnnArr['field']['writer'] 	= 	$PowerBB->_CONF['rows']['member_row']['username'];
		$AnnArr['field']['date'] 	= 	$PowerBB->_CONF['now'];

		$insert = $PowerBB->announcement->InsertAnnouncement($AnnArr);

		if ($insert)
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Add_the_declaration_successfully']);
			$PowerBB->functions->redirect('index.php?page=announcement&amp;control=1&amp;main=1');
		}
	}

	function _ControlMain()
	{
		global $PowerBB;

		$AnnArr 					= 	array();
		$AnnArr['order']			=	array();
		$AnnArr['order']['field']	=	'id';
		$AnnArr['order']['type']	=	'DESC';
		$AnnArr['proc'] 			= 	array();
		$AnnArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');
		$AnnArr['proc']['date'] 	= 	array('method'=>'date','store'=>'date');

		$PowerBB->_CONF['template']['while']['AnnList'] = $PowerBB->core->GetList($AnnArr,'announcement');

		$PowerBB->template->display('announcements_main');
	}

	function _EditMain()
	{
		global $PowerBB;

		$PowerBB->_CONF['template']['AnnInfo'] = false;

		$this->check_by_id($PowerBB->_CONF['template']['AnnInfo']);

		$PowerBB->template->display('announcement_edit');
	}

	function _EditStart()
	{
		global $PowerBB;

		$PowerBB->_CONF['template']['AnnInfo'] = false;

		$this->check_by_id($PowerBB->_CONF['template']['AnnInfo']);

		if (empty($PowerBB->_POST['title'])
			or empty($PowerBB->_POST['text']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_fill_in_all_the_information']);
		}

		$AnnArr 			= 	array();
		$AnnArr['field']	=	array();

		$AnnArr['field']['title'] 	= 	$PowerBB->_POST['title'];
		$AnnArr['field']['text'] 	= 	$PowerBB->_POST['text'];
		$AnnArr['field']['writer'] 	= 	$PowerBB->_CONF['template']['AnnInfo']['writer'];
		$AnnArr['field']['date'] 	= 	$PowerBB->_CONF['template']['AnnInfo']['date'];
		$AnnArr['where']			=	array('id',$PowerBB->_CONF['template']['AnnInfo']['id']);

		$insert = $PowerBB->announcement->UpdateAnnouncement($AnnArr);

		if ($insert)
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Announcement_has_been_updated_successfully']);
			$PowerBB->functions->redirect('index.php?page=announcement&amp;control=1&amp;main=1');
		}
	}

	function _DelMain()
	{
		global $PowerBB;

		$PowerBB->_CONF['template']['AnnInfo'] = false;

		$this->check_by_id($PowerBB->_CONF['template']['AnnInfo']);

		$PowerBB->template->display('announcement_del');
	}

	function _DelStart()
	{
		global $PowerBB;

		$PowerBB->_CONF['template']['AnnInfo'] = false;

		$this->check_by_id($PowerBB->_CONF['template']['AnnInfo']);

		$DelArr 			= 	array();
		$DelArr['where'] 	= 	array('id',$PowerBB->_GET['id']);

		$del = $PowerBB->core->Deleted($DelArr,'announcement');

		if ($del)
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Ad_Deleted_successfully']);
			$PowerBB->functions->redirect('index.php?page=announcement&amp;control=1&amp;main=1');
		}
	}
}

class _functions
{
	function check_by_id(&$AnnInfo)
	{
		global $PowerBB;

		if (empty($PowerBB->_GET['id']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['The_request_is_not_valid']);
		}

		$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');

        $CatArr = $PowerBB->DB->sql_query("SELECT  *   FROM " . $PowerBB->table['announcement'] . " WHERE id = ".$PowerBB->_GET['id']." ");
	    $AnnInfo = $PowerBB->DB->sql_fetch_array($CatArr);

		if ($AnnInfo == false)
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['The_declaration_does_not_exist']);
		}

		$PowerBB->functions->CleanVariable($AnnInfo,'html');
	}
}

?>
