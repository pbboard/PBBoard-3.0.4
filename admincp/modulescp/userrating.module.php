<?php

(!defined('IN_PowerBB')) ? die() : '';

define('IN_ADMIN',true);

$CALL_SYSTEM				=	array();
$CALL_SYSTEM['USERRATING'] 	= 	true;



define('CLASS_NAME','PowerBBUserRatingMOD');

include('../common.php');
class PowerBBUserRatingMOD extends _functions
{
	function run()
	{
		global $PowerBB;

		if ($PowerBB->_CONF['member_permission'])
		{
			$PowerBB->template->display('header');

			if ($PowerBB->_CONF['rows']['group_info']['admincp_membertitle'] == '0')
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

		$PowerBB->template->display('userrating_add');
	}

	function _AddStart()
	{
		global $PowerBB;

		if (empty($PowerBB->_POST['rating'])
			or empty($PowerBB->_POST['posts']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_fill_in_all_the_information']);
		}

	    $PowerBB->functions->GetFileExtension($PowerBB->_POST['rating']);

		$URArr 			= 	array();
		$URArr['field']	=	array();

		$URArr['field']['rating'] 	= 	$PowerBB->_POST['rating'];
		$URArr['field']['posts'] 		= 	$PowerBB->_POST['posts'];

		$insert = $PowerBB->userrating->InsertUserRating($URArr);

		if ($insert)
		{
           $cache = $PowerBB->userrating->UpdateRatingsCache(null);

			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['UserRating_has_been_added_successfully']);
			$PowerBB->functions->redirect('index.php?page=userrating&amp;control=1&amp;main=1');
		}
	}

	function _ControlMain()
	{
		global $PowerBB;

		$URArr 						= 	array();
		$URArr['proc'] 				= 	array();
		$URArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');

		$URArr['order']				=	array();
		$URArr['order']['field']	=	'posts';
		$URArr['order']['type']		=	'ASC';

		$PowerBB->_CONF['template']['while']['URList'] = $PowerBB->userrating->GetUserRatingList($URArr);

		$PowerBB->template->display('userrating_main');
	}

	function _EditMain()
	{
		global $PowerBB;

		$this->check_by_id($PowerBB->_CONF['template']['Inf']);

		$PowerBB->template->display('userrating_edit');
	}

	function _EditStart()
	{
		global $PowerBB;

		$this->check_by_id($URInfo);

		if (empty($PowerBB->_POST['rating'])
			or empty($PowerBB->_POST['posts']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_fill_in_all_the_information']);
		}

        $PowerBB->functions->GetFileExtension($PowerBB->_POST['rating']);

		$URArr 			= 	array();
		$URArr['field']	=	array();

		$URArr['field']['rating'] 	= 	$PowerBB->_POST['rating'];
		$URArr['field']['posts'] 		= 	$PowerBB->_POST['posts'];
		$URArr['where']					=	array('id',$URInfo['id']);

		$update = $PowerBB->userrating->UpdateUserRating($URArr);

		if ($update)
		{
           $cache = $PowerBB->userrating->UpdateRatingsCache(null);

			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['UserRating_has_been_updated_successfully']);
			$PowerBB->functions->redirect('index.php?page=userrating&amp;control=1&amp;main=1');
		}
	}

	function _DelMain()
	{
		global $PowerBB;

		$this->check_by_id($PowerBB->_CONF['template']['Inf']);

		$PowerBB->template->display('userrating_del');
	}

	function _DelStart()
	{
		global $PowerBB;

		$this->check_by_id($URInfo);

		$DelArr 			= 	array();
		$DelArr['where'] 	= 	array('id',$PowerBB->_GET['id']);

		$del = $PowerBB->userrating->DeleteUserRating($DelArr);

		if ($del)
		{
           $cache = $PowerBB->userrating->UpdateRatingsCache(null);

			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['UserRating_has_been_deleted_successfully']);
			$PowerBB->functions->redirect('index.php?page=userrating&amp;control=1&amp;main=1');
		}
	}
}

class _functions
{
	function check_by_id(&$URInfo)
	{
		global $PowerBB;

		if (empty($PowerBB->_GET['id']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['The_request_is_not_valid']);
		}

		$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');

        $CatArr = $PowerBB->DB->sql_query("SELECT  *   FROM " . $PowerBB->table['userrating'] . " WHERE id = ".$PowerBB->_GET['id']." ");
	    $URInfo = $PowerBB->DB->sql_fetch_array($CatArr);

		if ($URInfo == false)
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['UserRating_requested_does_not_exist']);
		}

		$PowerBB->functions->CleanVariable($URInfo,'html');
	}
}

?>
