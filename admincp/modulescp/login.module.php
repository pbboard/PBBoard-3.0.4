<?php

(!defined('IN_PowerBB')) ? die() : '';

define('IN_ADMIN',true);
define('STOP_STYLE',true);



define('CLASS_NAME','PowerBBLoginMOD');

include('../common.php');
class PowerBBLoginMOD
{
	function run()
	{
		global $PowerBB;


		if ($PowerBB->_CONF['info_row']['num_entries_error'] < $PowerBB->_COOKIE['pbb_entries_error'])
		{
		  $PowerBB->template->display('header');
		  $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['get_text_entries_error']);
		}

		if ($PowerBB->_GET['login'])
		{
			$this->_StartLogin();
		}
	}

	function _StartLogin()
	{
		global $PowerBB;

		if (empty($PowerBB->_POST['username'])
			or empty($PowerBB->_POST['password']))
		{
			$PowerBB->template->display('header');
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_fill_in_all_the_information']);
		    $PowerBB->template->display('footer');

		}

		$username = $PowerBB->functions->CleanVariable($PowerBB->_POST['username'],'trim');
		$password = $PowerBB->functions->CleanVariable(md5($PowerBB->_POST['password']),'trim');
        $username	= 	$PowerBB->functions->CleanVariable($username,'html');
        $username	= 	$PowerBB->functions->CleanVariable($username,'sql');
        $password	= 	$PowerBB->functions->CleanVariable($password,'html');
        $password	= 	$PowerBB->functions->CleanVariable($password,'sql');

		$expire = time() + 3600;
		$IsMember = $PowerBB->member->LoginAdmin(array(	'username'	=>	$username,
															'password'	=>	$password,
															'expire'	=>	$expire));

		if ($IsMember)
		{
			@header("Location:".$PowerBB->_SERVER['HTTP_REFERER']);
			exit;
		}
		else
		{

             if (empty($PowerBB->_COOKIE['pbb_entries_error']))
             {
             $PowerBB->_COOKIE['pbb_entries_error'] = "1";
             }
			$pbb_entries_error = $PowerBB->_COOKIE['pbb_entries_error']+1;
			@ob_start();
			@setcookie("pbb_entries_error",$pbb_entries_error, time()+900);
			@ob_end_flush();
			$PowerBB->_CONF['template']['_CONF']['lang']['message_entries_error'] = str_replace('{err}', $PowerBB->_COOKIE['pbb_entries_error'], $PowerBB->_CONF['template']['_CONF']['lang']['message_entries_error']);
			$PowerBB->_CONF['template']['_CONF']['lang']['message_entries_error'] = str_replace('{dferr}', $PowerBB->_CONF['info_row']['num_entries_error'], $PowerBB->_CONF['template']['_CONF']['lang']['message_entries_error']);

			$PowerBB->template->display('header');
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['message_entries_error']);
		    $PowerBB->template->display('footer');
		}
	}
}

?>
