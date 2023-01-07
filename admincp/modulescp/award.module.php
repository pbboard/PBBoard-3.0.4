<?php

(!defined('IN_PowerBB')) ? die() : '';

define('IN_ADMIN',true);

$CALL_SYSTEM			        =	array();
$CALL_SYSTEM['AWARD']           =   true;

define('JAVASCRIPT_PowerCode',true);
define('PowerBBAwardMOD',true);


define('CLASS_NAME','PowerBBAwardMOD');

include('../common.php');
class PowerBBAwardMOD
{
	function run()
	{
		global $PowerBB;

		if ($PowerBB->_CONF['member_permission'])
		{
			$PowerBB->template->display('header');


			if ($PowerBB->_CONF['rows']['group_info']['admincp_award'] == '0')
			{
			  $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
			}

			if ($PowerBB->_GET['control'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_ControlMain();
				}
			}
			elseif ($PowerBB->_GET['add'])
			{
              	if ($PowerBB->_GET['main'])
				{
					$this->_AddAwardMain();
				}
                elseif ($PowerBB->_GET['start'])
				{
					$this->_AddAwardStart();
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
                if ($PowerBB->_GET['start'])
				{
					$this->_DelStart();
				}
			}

		$PowerBB->template->display('footer');
		}

	}


	/**
	 * add Award Main
	 */

	function _AddAwardMain()
	{
		global $PowerBB;

		$PowerBB->template->display('award_add');

    }

	/**
	 * add Award Start
	 */
	function _AddAwardStart()
	{
		global $PowerBB;

		if (empty($PowerBB->_POST['username'])
			or empty($PowerBB->_POST['award_path']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_fill_in_all_the_information']);
		}

		$MemberArr 				= 	array();
		$MemberArr['where']		=	array('username',$PowerBB->_POST['username']);

		$member = $PowerBB->core->GetInfo($MemberArr,'member');

     		if (empty($member['username']))
		   {
		 	$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['The_name_of_the_user_does_not_exist']);
		   }

        if ($PowerBB->functions->IsImage($PowerBB->_POST['award_path'],0) == false)
        {
          $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['INcorrect_information']);
		}
			$AwardArr 			= 	array();
			$AwardArr['field']	=	array();

			$AwardArr['field']['award_path']    = 	 $PowerBB->_POST['award_path'];
			$AwardArr['field']['award'] 		= 	$PowerBB->_POST['award'];
			$AwardArr['field']['username'] 		= 	$PowerBB->_POST['username'];
			$AwardArr['field']['user_id'] 		= 	$member['id'];

			$insert = $PowerBB->award->InsertAward($AwardArr);

			if ($insert)
			{
	          $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Was_awarded_the_medal_for'].$PowerBB->_POST['username'].$PowerBB->_CONF['template']['_CONF']['lang']['Successfully']);
               $PowerBB->functions->redirect('index.php?page=award&amp;control=1&amp;main=1');
			}

	}

	function _ControlMain()
	{
		global $PowerBB;

        // show Award List
		$AwardArr 					= 	array();
		$AwardArr['order']			=	array();
		$AwardArr['order']['field']	=	'id';
		$AwardArr['order']['type']	=	'DESC';
		$AwardArr['proc'] 			= 	array();
		$AwardArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');

		$PowerBB->_CONF['template']['while']['AwardsList'] = $PowerBB->award->GetAwardList($AwardArr);

		$PowerBB->template->display('award_main');
	}




	function _EditMain()
	{
		global $PowerBB;

			if (empty($PowerBB->_GET['id']))
			{
				$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Medal_requested_does_not_exist']);
			}

			$AwardEditArr				=	array();
		    $AwardEditArr['where'] 	= 	array('id',$PowerBB->_GET['id']);

			$AwardEdit = $PowerBB->award->GetAwardInfo($AwardEditArr);

			$PowerBB->template->assign('AwardEdit',$AwardEdit);


		$PowerBB->template->display('award_edit');
	}

	function _EditStart()
	{
		global $PowerBB;

			if (empty($PowerBB->_GET['id']))
			{
				$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Medal_requested_does_not_exist']);
			}

		$MemberArr 				= 	array();
		$MemberArr['where']		=	array('username',$PowerBB->_POST['username']);

		$member = $PowerBB->core->GetInfo($MemberArr,'member');

     		if (empty($member['username']))
		   {
		 	$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['The_name_of_the_user_does_not_exist']);
		   }

        if ($PowerBB->functions->IsImage($PowerBB->_POST['award_path'],0) == false)
        {
          $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['INcorrect_information']);
		}

		$AwardArr 			= 	array();
		$AwardArr['field']	=	array();

		$AwardArr['field']['award_path']    = 	 $PowerBB->_POST['award_path'];
		$AwardArr['field']['award'] 		= 	$PowerBB->_POST['award'];
		$AwardArr['field']['username'] 		= 	$PowerBB->_POST['username'];
		$AwardArr['field']['user_id'] 		= 	$member['id'];
		$AwardArr['where'] 				= 	array('id',$PowerBB->_GET['id']);

		$update = $PowerBB->award->UpdateAward($AwardArr);

		if ($update)
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Medal_has_been_updated_successfully']);
			$PowerBB->functions->redirect('index.php?page=award&amp;control=1&amp;main=1');
		}
	}

	function _DelStart()
	{
		global $PowerBB;

			if (empty($PowerBB->_GET['id']))
			{
				$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Medal_requested_does_not_exist']);
			}

			$DelArr 			= 	array();
			$DelArr['where'] 	= 	array('id',$PowerBB->_GET['id']);

			$del = $PowerBB->award->DeleteAward($DelArr);

		if ($del)
		{
				$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Medal_has_been_deleted_successfully']);
				$PowerBB->functions->redirect('index.php?page=award&amp;control=1&amp;main=1');

		}
	}

}

?>
