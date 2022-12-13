<?php

(!defined('IN_PowerBB')) ? die() : '';

define('IN_ADMIN',true);



define('CLASS_NAME','PowerBBCoreMOD');

include('../common.php');
class PowerBBCoreMOD extends _functions
{
	function run()
	{
		global $PowerBB;

		if ($PowerBB->_CONF['member_permission'])
		{
			$PowerBB->template->display('header');


			if ($PowerBB->_CONF['rows']['group_info']['admincp_ads'] == '0')
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

		$PowerBB->template->display('ads_add');
	}

	function _AddStart()
	{
		global $PowerBB;

		if (empty($PowerBB->_POST['name'])
			or empty($PowerBB->_POST['link'])
			or empty($PowerBB->_POST['picture']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_fill_in_all_the_information']);
		}

		$AdsArr 			= 	array();
		$AdsArr['field']	=	array();

		$AdsArr['field']['sitename'] 	= 	$PowerBB->_POST['name'];
		$AdsArr['field']['site'] 		= 	$PowerBB->_POST['link'];
		$AdsArr['field']['picture'] 	= 	$PowerBB->_POST['picture'];
		$AdsArr['field']['width'] 		= 	$PowerBB->_POST['width'];
		$AdsArr['field']['height'] 		= 	$PowerBB->_POST['height'];
		$AdsArr['field']['clicks'] 		= 	0;

		$insert = $PowerBB->core->Insert($AdsArr,'ads');

		if ($insert)
		{

			$ads_num = $PowerBB->_CONF['info_row']['ads_num']+1;
			$update = $PowerBB->info->UpdateInfo(array('value'	=>	$ads_num,'var_name'	=>	'ads_num'));


			if ($update)
			{
				$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Add_the_declaration_successfully']);
				$PowerBB->functions->redirect('index.php?page=ads&amp;control=1&amp;main=1');
			}
		}
	}

	function _ControlMain()
	{
		global $PowerBB;

		$AdsArr 					= 	array();
		$AdsArr['order']			=	array();
		$AdsArr['order']['field']	=	'id';
		$AdsArr['order']['type']	=	'DESC';
		$AdsArr['proc'] 			= 	array();
		$AdsArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');

		$PowerBB->_CONF['template']['while']['AdsList'] = $PowerBB->core->GetList($AdsArr,'ads');

		$PowerBB->template->display('ads_main');
	}

	function _EditMain()
	{
		global $PowerBB;

		$PowerBB->_CONF['template']['Inf'] = false;

		$this->check_by_id($PowerBB->_CONF['template']['Inf']);

		$PowerBB->template->display('ads_edit');
	}

	function _EditStart()
	{
		global $PowerBB;

		$PowerBB->_CONF['template']['Inf'] = false;

		$this->check_by_id($PowerBB->_CONF['template']['Inf']);

		if (empty($PowerBB->_POST['name'])
			or empty($PowerBB->_POST['link'])
			or empty($PowerBB->_POST['picture']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_fill_in_all_the_information']);
		}

		$AdsArr 			= 	array();
		$AdsArr['field']	=	array();

		$AdsArr['field']['sitename'] 	= 	$PowerBB->_POST['name'];
		$AdsArr['field']['site'] 		= 	$PowerBB->_POST['link'];
		$AdsArr['field']['picture'] 	= 	$PowerBB->_POST['picture'];
		$AdsArr['field']['width'] 		= 	$PowerBB->_POST['width'];
		$AdsArr['field']['height'] 		= 	$PowerBB->_POST['height'];
		$AdsArr['field']['clicks'] 		= 	$PowerBB->_CONF['template']['Inf']['clicks'];
		$AdsArr['where'] 				= 	array('id',$PowerBB->_CONF['template']['Inf']['id']);

		$update = $PowerBB->core->Update($AdsArr,'ads');

		if ($update)
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Announcement_has_been_updated_successfully']);
			$PowerBB->functions->redirect('index.php?page=ads&amp;control=1&amp;main=1');
		}
	}

	function _DelMain()
	{
		global $PowerBB;

		$PowerBB->_CONF['template']['Inf'] = false;

		$this->check_by_id($PowerBB->_CONF['template']['Inf']);

		$PowerBB->template->display('ads_del');
	}

	function _DelStart()
	{
		global $PowerBB;

		$PowerBB->_CONF['template']['Inf'] = false;

		$this->check_by_id($PowerBB->_CONF['template']['Inf']);

			$DelArr 			= 	array();
			$DelArr['where'] 	= 	array('id',$PowerBB->_GET['id']);

			$del = $PowerBB->core->Deleted($DelArr,'ads');

		if ($del)
		{
			$ads_num = $PowerBB->_CONF['info_row']['ads_num']-1;

			$update = $PowerBB->info->UpdateInfo(array('value'	=>	$ads_num,'var_name'	=>	'ads_num'));

			if ($update)
			{
				$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Ad_Deleted_successfully']);
				$PowerBB->functions->redirect('index.php?page=ads&amp;control=1&amp;main=1');
			}
		}
	}
}

class _functions
{
	function check_by_id(&$AdsInfo)
	{
		global $PowerBB;

		if (empty($PowerBB->_GET['id']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['The_request_is_not_valid']);
		}

		$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');


	        $CatArr = $PowerBB->DB->sql_query("SELECT  *   FROM " . $PowerBB->table['ads'] . " WHERE id = ".$PowerBB->_GET['id']." ");
		    $AdsInfo = $PowerBB->DB->sql_fetch_array($CatArr);

	if ($AdsInfo == false)
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['The_declaration_does_not_exist']);
		}

		$PowerBB->functions->CleanVariable($AdsInfo,'html');
	}
}

?>
