<?php

(!defined('IN_PowerBB')) ? die() : '';

define('STOP_STYLE',true);



define('CLASS_NAME','PowerBBLogoutMOD');

include('common.php');
class PowerBBLogoutMOD
{
	function run()
	{
		global $PowerBB;

		/** Simply , logout :) **/
		if ($PowerBB->_GET['index'])
		{
			$this->_StartLogout();
		}
		/** **/
		else
		{
			header("Location: index.php");
			exit;
		}

	}

	/**
	 * Delete cookies , and the member from online table then go to last page which the member was in it :)
	 */
	function _StartLogout()
	{
		global $PowerBB;

		$DelArr 						= 	array();
		$DelArr['where'] 				= 	array();
		$DelArr['where'][0] 			= 	array();
		$DelArr['where'][0]['name'] 	= 	'user_id';
		$DelArr['where'][0]['oper'] 	= 	'=';
		$DelArr['where'][0]['value'] 	= 	$PowerBB->_CONF['member_row']['id'];

		$PowerBB->core->Deleted($DelArr,'online');

		$Logout = $PowerBB->member->Logout();

		$PowerBB->template->assign('username',$PowerBB->_CONF['member_row']['username']);


		if ($Logout)
		{
			//$PowerBB->template->display('logout_msg');

			$url = parse_url($PowerBB->_SERVER['HTTP_REFERER']);
      		$url = $url['query'];
      		$url = explode('&',$url);
      		$url = $url[0];

     		$Y_url = explode('/',$PowerBB->_SERVER['HTTP_REFERER']);
      		$X_url = explode('/',$PowerBB->_SERVER['HTTP_HOST']);

      		if ($url != 'page=logout'
      			or empty($url)
      			or $url != 'page=login')
           	{
						header("Location: index.php");
						exit;
      		}

      		elseif ($Y_url[2] != $X_url[0]
      				or $url == 'page=logout'
      				or $url == 'page=login')
           	{
						header("Location: index.php");
						exit;
			}
		}
	}
}

?>
