<?php

(!defined('IN_PowerBB')) ? die() : '';

define('IN_ADMIN',true);
define('STOP_STYLE',true);



define('CLASS_NAME','PowerBBLogoutMOD');

include('../common.php');
class PowerBBLogoutMOD
{
	function run()
	{
		global $PowerBB;

		if ($PowerBB->_CONF['member_permission'])
		{
        @session_start();
    	$_SESSION['admin_expire'] = '';
		$_SESSION[$PowerBB->_CONF['admin_username_cookie']] = '';
     	$_SESSION[$PowerBB->_CONF['admin_password_cookie']] = '';
     	$_SESSION['admin_expire'] = '';
		header("Location: index.php");
		exit;
		}
	}
}

?>
