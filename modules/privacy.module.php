<?php
(!defined('IN_PowerBB')) ? die() : '';

define('CLASS_NAME','PowerBBCoreMOD');

include('common.php');
class PowerBBCoreMOD
{
	function run()
	{
		global $PowerBB;

		if (!$PowerBB->_CONF['info_row']['users_security'])
		{
			header("Location: index.php");
			exit;
		}

		if (!$PowerBB->_CONF['group_info']['groups_security'])
		{
            $PowerBB->functions->ShowHeader();
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['no_online']);
		}

		if ($PowerBB->_CONF['group_info']['banned'])
		{
            $PowerBB->functions->ShowHeader();
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['no_online']);
		}

		if (!$PowerBB->_CONF['member_permission'])
		{
            $PowerBB->functions->ShowHeader();
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['This_region_to_members_only']);
		}

		/** Persenol Information control **/
		if ($PowerBB->_GET['infosecurity'])
		{
			if ($PowerBB->_GET['main'])
			{
				$this->_InfosecurityMain();
			}
			elseif ($PowerBB->_GET['start'])
			{
				$this->_InfosecurityStart();
			}
		 }
		else
		{
			header("Location: index.php");
			exit;
		}


		$PowerBB->functions->GetFooter();
	}

	function _InfosecurityMain()
	{
		global $PowerBB;
		$PowerBB->functions->ShowHeader();
        $PowerBB->_CONF['template']['member_row'] = $PowerBB->_CONF['member_row'];

      	$PowerBB->template->display('usercp_security');
	}



	function _InfosecurityStart()
	 {
		global $PowerBB;

		$PowerBB->functions->ShowHeader();

		if (empty($PowerBB->_POST['old_password']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_fill_in_all_the_information']);
		}

		// Check old password
		if (md5($PowerBB->_POST['old_password']) != $PowerBB->_CONF['member_row']['password'])
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['bad_password']);
		}

     	$PowerBB->_POST['send_security_code']	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['send_security_code'],'intval');
     	$PowerBB->_POST['send_security_error_login']	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['send_security_error_login'],'intval');

				$UpdateArr 				= 	array();
				$UpdateArr['field']		= 	array();

				$UpdateArr['field']['send_security_code'] 	= 	$PowerBB->_POST['send_security_code'];
				$UpdateArr['field']['send_security_error_login'] 	= 	$PowerBB->_POST['send_security_error_login'];
				$UpdateArr['where'] 					= 	array('id',$PowerBB->_CONF['member_row']['id']);

				$UpdatePrivacy = $PowerBB->core->Update($UpdateArr,'member');

		if ($UpdatePrivacy)
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Updated_successfully']);
            $PowerBB->functions->redirect('index.php?page=privacy&amp;infosecurity=1&amp;main=1');
		}

	}

}

?>