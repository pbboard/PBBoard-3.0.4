<?php
session_start();
(!defined('IN_PowerBB')) ? die() : '';

define('STOP_STYLE',true);
define('LOGIN',true);



define('CLASS_NAME','PowerBBLoginMOD');

include('common.php');
class PowerBBLoginMOD
{
	function run()
	{
		global $PowerBB;
        $PowerBB->template->assign('login_page','primary_tabon');

		if ($PowerBB->_CONF['info_row']['num_entries_error'] <= $PowerBB->_COOKIE['pbb_entries_error'])
		{
		$PowerBB->functions->ShowHeader();
		$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['get_text_entries_error']);
		}

		/** Normal login **/
		if ($PowerBB->_GET['sign'])
		{
			$this->_StartSign();
		}
		elseif ($PowerBB->_GET['login'])
		{
			$this->_StartLogin();
		}
		/** **/
		/** Login after register **/
		elseif ($PowerBB->_GET['register_login'])
		{
			$this->_StartLogin(true);
		}
		/** **/
		else
		{
			header("Location: index.php");
			exit;
		}


	}

	function _StartSign()
	{		global $PowerBB;

		if (!$PowerBB->_CONF['member_permission'])
		{
         $PowerBB->functions->ShowHeader();
		$PowerBB->template->display('login');
		$PowerBB->functions->error_stop();
		}
		else
		{
		header("Location: index.php");
		exit;
		}	}

	/**
	 * Check if the username , password is true , then give the permisson .
	 * otherwise don't give the permisson
	 *
	 * param :
	 *			register_login	->
	 *								true : to use this function to login after register
	 *								false : to use this function to normal login
	 */
	function _StartLogin($register_login=false)
	{
		global $PowerBB;


		if ($PowerBB->_CONF['member_permission'])
		{
		header("Location: index.php");
		exit;
		}

         if (!$register_login)
		{
         if ($PowerBB->_SERVER['REQUEST_METHOD'] != 'POST')
         {
         	 header("Location: index.php");
		     exit;
         }

			if (empty($PowerBB->_POST['username'])
             or empty($PowerBB->_POST['password']))
             {

          $PowerBB->functions->ShowHeader();
          $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Message_Login_incorrect'],true);
          }
			$username = $PowerBB->functions->CleanVariable($PowerBB->_POST['username'],'trim');
			$username = $PowerBB->functions->CleanVariable($PowerBB->_POST['username'],'sql');
			$password = $PowerBB->functions->CleanVariable($PowerBB->_POST['password'],'sql');
			if ($PowerBB->_POST['otp'])
			{
			$password = $PowerBB->functions->CleanVariable($PowerBB->_POST['password'],'trim');
			}
			else
			{
			$password = $PowerBB->functions->CleanVariable(md5($PowerBB->_POST['password']),'trim');
			}
		}
		else
		{
             if (empty($PowerBB->_GET['username'])
             or empty($PowerBB->_GET['password']))
                {
                $PowerBB->functions->ShowHeader();
                 $PowerBB->functions->msgerror($PowerBB->_CONF['template']['_CONF']['lang']['Message_Login_incorrect']);
		         $PowerBB->template->display('login');
                 $PowerBB->functions->error_stop();
                }
			$username = $PowerBB->functions->CleanVariable($PowerBB->_GET['username'],'trim');
            $password = $PowerBB->functions->CleanVariable($PowerBB->_GET['password'],'trim');

		}

			$CheckMember = $PowerBB->member->CheckMember(array('username'	=>	$username,
			'password'	=>	$password));

				if ($CheckMember)
				{
					$MemberArr 				= 	array();
					$MemberArr['where']		=	array('username',$username);

					$MemberInfo = $PowerBB->core->GetInfo($MemberArr,'member');

					$GroupArr 				= 	array();
					$GroupArr['where']		=	array('id',$MemberInfo['usergroup']);

					$GroupInfo = $PowerBB->core->GetInfo($GroupArr,'group');
			       	 if ($MemberInfo['send_security_code']
			       	 and $PowerBB->_CONF['info_row']['users_security']
			       	 and $GroupInfo['groups_security'])
			          {

							if (empty($PowerBB->_POST['otp']))
							{
								$security_code = $PowerBB->functions->random_numbers();
								$_SESSION['LSC'] = $security_code;
								$fromname = $PowerBB->_CONF['info_row']['title'];
								$url_security_settings = 	$PowerBB->functions->GetForumAdress()."index.php?page=privacy&infosecurity=1&main=1";

								$PowerBB->_CONF['template']['_CONF']['lang']['security_code_1'] = str_replace('{LSC_1}', $fromname, $PowerBB->_CONF['template']['_CONF']['lang']['security_code_1']);
								$PowerBB->_CONF['template']['_CONF']['lang']['security_code_1'] = str_replace('{LSC_2}', $security_code, $PowerBB->_CONF['template']['_CONF']['lang']['security_code_1']);
								$PowerBB->_CONF['template']['_CONF']['lang']['security_code_title'] = str_replace('{LSC_title}', $fromname, $PowerBB->_CONF['template']['_CONF']['lang']['security_code_title']);
								$title = $PowerBB->_CONF['template']['_CONF']['lang']['security_code_title'];

								$PowerBB->_CONF['template']['_CONF']['lang']['alarm_6'] = str_replace('{url_security_settings}', $url_security_settings, $PowerBB->_CONF['template']['_CONF']['lang']['alarm_6']);

								$text_m = "<p style='direction: ".$PowerBB->_CONF['info_row']['content_dir']."'>".$PowerBB->_CONF['template']['_CONF']['lang']['security_code_1'];
								$text_m .= $PowerBB->_CONF['template']['_CONF']['lang']['security_code_2'];
								$text_m .= $PowerBB->_CONF['template']['_CONF']['lang']['alarm_6']."</p>";

								if ($PowerBB->_CONF['info_row']['mailer']=='phpmail')
								{
								$Send = $PowerBB->functions->send_this_php($MemberInfo['email'],$title,$text_m,$PowerBB->_CONF['info_row']['send_email']);
								}
								elseif ($PowerBB->_CONF['info_row']['mailer']=='smtp')
								{
								$Send = $PowerBB->functions->send_this_smtp($MemberInfo['email'],$fromname,$text_m,$title,$PowerBB->_CONF['info_row']['send_email']);
								}
								$PowerBB->functions->ShowHeader();
								$PowerBB->template->assign('username',$username);
								$PowerBB->template->assign('password',$password);
								$PowerBB->template->assign('temporary',$PowerBB->_POST['temporary']);
								$PowerBB->template->assign('email',$PowerBB->functions->obfuscate_email($MemberInfo['email']));
								$PowerBB->template->display('login_security_code');
								$PowerBB->functions->error_stop();
								exit();
							}
				          elseif ($PowerBB->_POST['otp'])
				           {
				              if (isset($_SESSION['LSC']))
				              {
					         	if ($_SESSION['LSC'] != $PowerBB->_POST['security_code'])
					         	{
									$PowerBB->functions->ShowHeader();
									$PowerBB->template->assign('error_security',"1");
									$PowerBB->template->assign('error_security_code',$PowerBB->_CONF['template']['_CONF']['lang']['temp_pass_no_correct']);
									$PowerBB->template->assign('username',$username);
									$PowerBB->template->assign('password',$password);
									$PowerBB->template->assign('temporary',$PowerBB->_POST['temporary']);
									$PowerBB->template->assign('email',$PowerBB->functions->obfuscate_email($MemberInfo['email']));
									$PowerBB->template->display('login_security_code');
									$PowerBB->functions->error_stop();
									exit();
					         	}
				         	 }
				         	 else
				         	 {				         	        $PowerBB->functions->ShowHeader();
				         			$PowerBB->template->display('login');
									$PowerBB->functions->error_stop();
									exit();
				         	 }
				           }

			          }


		       }



		$expire = ($PowerBB->_POST['temporary'] == 'on') ? time() + 31536000 : 0;
		$IsMember = $PowerBB->member->LoginMember(array(	'username'	=>	$username,
															'password'	=>	$password,
															'expire'	=>	$expire));


			if ($IsMember['usergroup'] == 6)
			{
				// Stop the page with small massege
				$PowerBB->template->assign('_CONF',$PowerBB->_CONF);
	        	$PowerBB->template->assign('image_path',$_VARS['path'] . $PowerBB->_CONF['rows']['style']['image_path']);
				$PowerBB->functions->ShowHeader($PowerBB->_CONF['template']['_CONF']['lang']['Can_you_enter_the_Forum']);
	            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['suspended_member']);
	        }

		if ($IsMember != false)
		{
			//////////

			$username = $PowerBB->functions->CleanVariable($username,'html');

			$PowerBB->template->assign('username',$username);

			//$PowerBB->template->display('login_msg');

			//////////

			if ($IsMember['style'] != $IsMember['style_id_cache'])
			{
				$style_cache = $PowerBB->style->CreateStyleCache(array('where'=>array('id',$IsMember['style'])));

				$UpdateArr						=	array();
				$UpdateArr['field']				=	array();

				$UpdateArr['field']['style_cache'] 		= 	$style_cache;
				$UpdateArr['field']['style_id_cache']	=	$IsMember['style'];
				$UpdateArr['where']						=	array('id',$IsMember['id']);

				$update_cache = $PowerBB->core->Update($UpdateArr,'member');
			}

			//////////

			$DelArr 						= 	array();
			$DelArr['where'] 				= 	array();
			$DelArr['where'][0] 			= 	array();
			$DelArr['where'][0]['name'] 	= 	'user_ip';
			$DelArr['where'][0]['oper'] 	= 	'=';
			$DelArr['where'][0]['value'] 	= 	$PowerBB->_CONF['ip'];

			$PowerBB->core->Deleted($DelArr,'online');

			//////////

			$UpdateArr 				= 	array();
			$UpdateArr['field']		=	array();

			$UpdateArr['field']['visitor'] 	= 	$IsMember['visitor'] + 1;
			$UpdateArr['where'] 			= 	array('id',$IsMember['id']);

			$PowerBB->core->Update($UpdateArr,'member');

			$url = parse_url($PowerBB->_SERVER['HTTP_REFERER']);
      		$url = $url['query'];
      		$url = explode('&',$url);
      		$url = $url[0];

     		$Y_url = explode('/',$PowerBB->_SERVER['HTTP_REFERER']);
      		$X_url = explode('/',$PowerBB->_SERVER['HTTP_HOST']);

      		//////////

      		if (!$register_login)
      		{
      			if ($url != 'page=logout'
      				or empty($url)
      				or $url != 'page=login')
           		{					if ($url == 'page=login')
					{
						header("Location: index.php");
						exit;
					}
					else
					{
						header("Location: ".$PowerBB->_SERVER['HTTP_REFERER']);
						exit;
					}
      			}

      			elseif ($Y_url[2] != $X_url[0]
      					or $url == 'page=logout'
      					or $url == 'page=login')
           		{
						header("Location: index.php");
						exit;
      			}
      		}
      		else
      		{
      		    /*
      			 // Can't find last visit cookie , so register it
				if (!$PowerBB->functions->IsCookie('PowerBB_lastvisit'))
				{
					$CookieArr 					= 	array();
					$CookieArr['last_visit'] 	= 	(empty($PowerBB->_CONF['member_row']['lastvisit'])) ? $PowerBB->_CONF['now'] : $PowerBB->_CONF['member_row']['lastvisit'];
					$CookieArr['date'] 			= 	$PowerBB->_CONF['now'];
					$CookieArr['id'] 			= 	$PowerBB->_CONF['member_row']['id'];

					$PowerBB->member->LastVisitCookie($CookieArr);
				}
				*/
						header("Location: index.php");
						exit;
      		}
		}
		else
		{
		         $pbb_entries_error = $PowerBB->_COOKIE['pbb_entries_error']+1;

					$MemberArr 				= 	array();
					$MemberArr['where']		=	array('username',$username);
					$MemberInfo = $PowerBB->core->GetInfo($MemberArr,'member');
                 if ($MemberInfo)
                  {
					$GroupArr 				= 	array();
					$GroupArr['where']		=	array('id',$MemberInfo['usergroup']);
					$GroupInfo = $PowerBB->core->GetInfo($GroupArr,'group');
			       	 if ($MemberInfo['send_security_error_login']
			       	 and $PowerBB->_CONF['info_row']['users_security']
			       	 and $GroupInfo['groups_security'])
			          {
						$fromname = $PowerBB->_CONF['info_row']['title'];
						$url_security_settings = 	$PowerBB->functions->GetForumAdress()."index.php?page=privacy&infosecurity=1&main=1";

						$PowerBB->_CONF['template']['_CONF']['lang']['alarm_1'] = str_replace('{alarm_0}', $fromname, $PowerBB->_CONF['template']['_CONF']['lang']['alarm_1']);
						$PowerBB->_CONF['template']['_CONF']['lang']['alarm_1'] = str_replace('{alarm_1}', $pbb_entries_error, $PowerBB->_CONF['template']['_CONF']['lang']['alarm_1']);
						$PowerBB->_CONF['template']['_CONF']['lang']['alarm_2'] = str_replace('{alarm_2}', $fromname, $PowerBB->_CONF['template']['_CONF']['lang']['alarm_2']);
						$PowerBB->_CONF['template']['_CONF']['lang']['alarm_2'] = str_replace('{alarm_3}', $pbb_entries_error, $PowerBB->_CONF['template']['_CONF']['lang']['alarm_2']);
						$PowerBB->_CONF['template']['_CONF']['lang']['alarm_3'] = str_replace('{alarm_4}', $fromname, $PowerBB->_CONF['template']['_CONF']['lang']['alarm_3']);
						$PowerBB->_CONF['template']['_CONF']['lang']['alarm_4'] = str_replace('{alarm_5}', $PowerBB->_SERVER['HTTP_USER_AGENT'], $PowerBB->_CONF['template']['_CONF']['lang']['alarm_4']);
						$PowerBB->_CONF['template']['_CONF']['lang']['alarm_4'] = str_replace('{alarm_6}', $PowerBB->_CONF['ip'], $PowerBB->_CONF['template']['_CONF']['lang']['alarm_4']);
						$PowerBB->_CONF['template']['_CONF']['lang']['alarm_4'] = str_replace('{alarm_7}', $username, $PowerBB->_CONF['template']['_CONF']['lang']['alarm_4']);
						$alarm_8= $PowerBB->functions->CleanVariable($PowerBB->_POST['password'],'sql');
			            $alarm_8 = $PowerBB->functions->CleanVariable($PowerBB->_POST['password'],'trim');
						$PowerBB->_CONF['template']['_CONF']['lang']['alarm_4'] = str_replace('{alarm_8}', $alarm_8, $PowerBB->_CONF['template']['_CONF']['lang']['alarm_4']);
						$PowerBB->_CONF['template']['_CONF']['lang']['alarm_6'] = str_replace('{url_security_settings}', $url_security_settings, $PowerBB->_CONF['template']['_CONF']['lang']['alarm_6']);

						$title = $PowerBB->_CONF['template']['_CONF']['lang']['alarm_1'];
						$text_m = "<p style='direction: ".$PowerBB->_CONF['info_row']['content_dir']."'>".$PowerBB->_CONF['template']['_CONF']['lang']['alarm_2'];
						$text_m .= $PowerBB->_CONF['template']['_CONF']['lang']['alarm_3'];
						$text_m .= $PowerBB->_CONF['template']['_CONF']['lang']['alarm_4'];
						$text_m .= $PowerBB->_CONF['template']['_CONF']['lang']['alarm_5'];
						$text_m .= $PowerBB->_CONF['template']['_CONF']['lang']['alarm_6']."</p>";
						if ($PowerBB->_CONF['info_row']['mailer']=='phpmail')
						{
						$Send = $PowerBB->functions->send_this_php($MemberInfo['email'],$title,$text_m,$PowerBB->_CONF['info_row']['send_email']);
						}
						elseif ($PowerBB->_CONF['info_row']['mailer']=='smtp')
						{
						$Send = $PowerBB->functions->send_this_smtp($MemberInfo['email'],$fromname,$text_m,$title,$PowerBB->_CONF['info_row']['send_email']);
						}

			          }
			     }
			@ob_start();
			setcookie("pbb_entries_error",$PowerBB->_COOKIE['pbb_entries_error']+1, time()+900);
			@ob_end_flush();
			$PowerBB->_CONF['template']['_CONF']['lang']['message_entries_error'] = str_replace('{err}', $PowerBB->_COOKIE['pbb_entries_error']+1, $PowerBB->_CONF['template']['_CONF']['lang']['message_entries_error']);
			$PowerBB->_CONF['template']['_CONF']['lang']['message_entries_error'] = str_replace('{dferr}', $PowerBB->_CONF['info_row']['num_entries_error'], $PowerBB->_CONF['template']['_CONF']['lang']['message_entries_error']);
			$PowerBB->functions->ShowHeader();
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['message_entries_error'],true);

		}
	}
}

?>
