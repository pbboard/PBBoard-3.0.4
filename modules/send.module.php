<?php
session_start();
(!defined('IN_PowerBB')) ? die() : '';
// anti flood protection
	$uri = md5($_SERVER['REQUEST_URI']);
	$exp = 3; // 3 seconds
	$hash = $uri .'|'. time();
	if (!isset($_SESSION['ddos'])) {
	    $_SESSION['ddos'] = $hash;
	}
	else
	{
		list($_uri, $_exp) = explode('|', $_SESSION['ddos']);
		if ($_uri == $uri && time() - $_exp < $exp) {
		    header('HTTP/1.1 503 Service Unavailable');
		    die;
		}
	}
// Save last request
$_SESSION['ddos'] = $hash;
define('CLASS_NAME','PowerBBCoreMOD');
include('common.php');
class PowerBBCoreMOD
{
	function run()
	{
		global $PowerBB;
 		if (empty($PowerBB->_CONF['ip']))
		{
		header('HTTP/1.1 403 FORBIDDEN');
		header('Status: 403 You Do Not Have Access To This Page');
		 exit;
        }
		if ($PowerBB->_GET['member'])
		{
			if ($PowerBB->_GET['index'])
			{
				$this->_MemberSendIndex();
			}
			elseif ($PowerBB->_GET['start'])
			{
				$this->_MemberSendStart();
			}
		}
        elseif ($PowerBB->_GET['sendmessage'])
		{
			$this->_SendIndex();
		}
		elseif ($PowerBB->_GET['startsendmessage'])
		{
			$this->_SendStart();
		}
		else
		{
			header("Location: index.php");
			exit;
		}

		$PowerBB->functions->GetFooter();
	}

	function _MemberSendIndex()
	{
		global $PowerBB;

		$PowerBB->functions->ShowHeader();

		if (!$PowerBB->_CONF['member_permission'])
     	{
		  $PowerBB->template->display('login');
		  $PowerBB->functions->error_stop();
		}

     	$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');

		$MemArr = array();

		$MemArr['get'] 	= 'id,username,send_allow';
		$MemArr['where'] = array('id',$PowerBB->_GET['id']);

		$PowerBB->_CONF['template']['MemberInfo'] = $PowerBB->core->GetInfo($MemArr,'member');

		if ($PowerBB->_CONF['template']['MemberInfo'] == false)
		{
		$MemusernameArr 			= 	array();
		$MemusernameArr['get'] 		= 	'*';
		$MemusernameArr['where'] 	= 	array('username',$PowerBB->_GET['username']);

		$PowerBB->_CONF['template']['MemberInfo'] = $PowerBB->core->GetInfo($MemusernameArr,'member');

		}

		if (!$PowerBB->_CONF['template']['MemberInfo'])
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Member_you_want_does_not_exist']);
		}

		if (!$PowerBB->_CONF['template']['MemberInfo']['send_allow'] == '1'
		and !$PowerBB->_CONF['group_info']['admincp_allow'])
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['MemberInfo']['username'] . ' '.$PowerBB->_CONF['template']['_CONF']['lang']['no_send_allow']);
		}

		// Kill XSS first
		$PowerBB->functions->CleanVariable($PowerBB->_CONF['template']['MemberInfo'],'html');
		// Second Kill SQL Injections
		$PowerBB->functions->CleanVariable($PowerBB->_CONF['template']['MemberInfo'],'sql');

		//////////
        if($PowerBB->_CONF['info_row']['captcha_type'] == 'captcha_Q_A')
			 {
				$question = $PowerBB->_CONF['info_row']['questions'];
				$answer = $PowerBB->_CONF['info_row']['answers'];
				$c1 = explode("\r\n",$question);
				$c2 = explode("\r\n",$answer);
				$rand = array_rand($c2);
				$question = $c1[$rand];
				$answer = $c2[$rand];
				$PowerBB->template->assign('question',$question);
				$PowerBB->template->assign('answer',$answer);
		     }

		$PowerBB->template->display('send_email');
	}

	function _MemberSendStart()
	{
		global $PowerBB;
     	$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');
 		if (!$PowerBB->_CONF['info_row']['active_send_admin_message'])
		{
			header('HTTP/1.1 403 FORBIDDEN');
			header('Status: 403 You Do Not Have Access To This Page');
			exit;
        }
         if ($PowerBB->_SERVER['REQUEST_METHOD'] != 'POST')
         {
			header('HTTP/1.1 403 FORBIDDEN');
			header('Status: 403 You Do Not Have Access To This Page');
			exit;
         }
         if ($PowerBB->_POST['insert'] != $PowerBB->_CONF['template']['_CONF']['lang']['Send_Message'])
         {
             header('HTTP/1.1 503 Service Unavailable');
		     exit;
         }

		$PowerBB->functions->ShowHeader();
		$flood_send_email_time = "40";
		if ((@time() - $flood_send_email_time) <= $_SESSION['last_send_email_time'])
		{
		$PowerBB->functions->error(" <p>It requires you to wait ".$flood_send_email_time." seconds to send Mail again.</p>");
		}

		if (!$PowerBB->_CONF['member_permission'])
     	{
     		$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Visitors_can_not_send_an_email']);
     	}

     	if (empty($PowerBB->_GET['id']))
     	{
     		$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
     	}

        if (empty($PowerBB->_POST['username']))
     	{
     	  $PowerBB->_POST['username'] = $PowerBB->_CONF['template']['_CONF']['lang']['Guest'];
     	}

       // to
		$MemArr 			= 	array();
		$MemArr['where'] 	= 	array('id',$PowerBB->_GET['id']);

		$MemberInfo = $PowerBB->core->GetInfo($MemArr,'member');

			if (!$MemberInfo)
			{
				$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Member_you_want_does_not_exist']);
			}

		// Kill XSS first
		$PowerBB->functions->CleanVariable($MemberInfo,'html');
		// Second Kill SQL Injections
		$PowerBB->functions->CleanVariable($MemberInfo,'sql');

		//////////

		if (empty($PowerBB->_POST['title'])
			or empty($PowerBB->_POST['text']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_fill_in_all_the_information']);
		}

		$PowerBB->_POST['title']   = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['title'],'trim');
		$PowerBB->_POST['title']   = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['title'],'sql');
		$PowerBB->_POST['title']   = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['title'],'html');

	    $PowerBB->_POST['text']   = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['text'],'sql');
		$PowerBB->_POST['text']   = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['text'],'html');


                 if($PowerBB->_CONF['info_row']['captcha_type'] == 'captcha_Q_A')
				 {

					$PowerBB->_POST['code']    =    $PowerBB->functions->CleanVariable($PowerBB->_POST['code'],'trim');
					$PowerBB->_POST['code']   = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['code'],'sql');
					$PowerBB->_POST['code']   = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['code'],'html');

	                if($PowerBB->_POST['code'] != $PowerBB->_POST['code_answer'])
					 {
			            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['random_answer_not_correct']);
				     }
			     }
		        if($PowerBB->_CONF['info_row']['captcha_type'] == 'captcha_IMG')
				 {

					$PowerBB->_POST['code_confirm']    =    $PowerBB->functions->CleanVariable($PowerBB->_POST['code_confirm'],'trim');
					$PowerBB->_POST['code_confirm']   = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['code_confirm'],'sql');
					$PowerBB->_POST['code_confirm']   = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['code_confirm'],'html');

			        if(md5($PowerBB->_POST['code_confirm']) != $_SESSION['captcha_key'])
					 {
			            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Code_that_you_enter_the_wrong']);
				     }
			    }

		$PowerBB->_POST['text'] = $PowerBB->Powerparse->replace($PowerBB->_POST['text']);

		$forums_name1 = $PowerBB->_CONF['template']['_CONF']['lang']['This_message_received_from_the_postal_model_for_the_Forums'].' (' .$PowerBB->_CONF['info_row']['title'] .')<br />';
		$forum_name2 = $PowerBB->_CONF['info_row']['title'] . '(' . $PowerBB->_CONF['info_row']['admin_email'] . ')';
		$Form_name = '<br />'.$PowerBB->_CONF['template']['_CONF']['lang']['Sender_Massege'] . $PowerBB->_POST['username'] .'<br /> '.$PowerBB->_CONF['template']['_CONF']['lang']['Massege_title'] .$PowerBB->_POST['title'] . '<br>'.$PowerBB->_POST['text'];
		$Form_Massege ='<br>
	---------------------------------------------------<br>
	'.$PowerBB->_CONF['template']['_CONF']['lang']['Warning_send2'].'
	---------------------------------------------------<br>
     '.$PowerBB->_CONF['template']['_CONF']['lang']['With_my_sincere_greetings_to_all'].'
	&nbsp;<br>'.$PowerBB->_CONF['template']['_CONF']['lang']['Team'].' ' . $PowerBB->_CONF['info_row']['title'] .'.<br>
	&nbsp;';
        $forums_name1 = $PowerBB->Powerparse->replace($forums_name1);
        $forum_name2 = $PowerBB->Powerparse->replace($forum_name2);
 		$Form_Massege = $PowerBB->Powerparse->replace($Form_Massege);
         	if ($PowerBB->_CONF['info_row']['mailer']=='phpmail')
			{
              $Send = $PowerBB->functions->send_this_php($MemberInfo['email'],$PowerBB->_POST['title'],$forums_name1 . $Form_name . $Form_Massege,$PowerBB->_CONF['info_row']['send_email']);
             }
			elseif ($PowerBB->_CONF['info_row']['mailer']=='smtp')
			{
				$to = $MemberInfo['email'];
				$fromname = $PowerBB->_CONF['info_row']['title'];
				$message = $forums_name1 . $Form_name . $Form_Massege;
				$subject = $PowerBB->_POST['title'];
				$from = $PowerBB->_CONF['info_row']['send_email'];
                $Send = $PowerBB->functions->send_this_smtp($to,$fromname,$message,$subject,$from);
			}

		if ($Send)
		{
		    $_SESSION['last_send_email_time'] = $PowerBB->_CONF['now'];
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['The_message_was_sent_successfully']);
			$PowerBB->functions->redirect('index.php');
		}
		else
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['There_was_an_error_no_transmission']);
		}
	}


	function _SendIndex()
	{
		global $PowerBB;

 		if (!$PowerBB->_CONF['info_row']['active_send_admin_message'])
		{		header('HTTP/1.1 403 FORBIDDEN');
		header('Status: 403 You Do Not Have Access To This Page');
		 exit;
        }

	        if($PowerBB->_CONF['info_row']['captcha_type'] == 'captcha_Q_A')
			 {
				$question = $PowerBB->_CONF['info_row']['questions'];
				$answer = $PowerBB->_CONF['info_row']['answers'];
				$c1 = explode("\r\n",$question);
				$c2 = explode("\r\n",$answer);
				$rand = array_rand($c2);
				$question = $c1[$rand];
				$answer = $c2[$rand];
				$PowerBB->template->assign('question',$question);
				$PowerBB->template->assign('answer',$answer);
		     }

		$PowerBB->functions->ShowHeader();

        if ($PowerBB->_CONF['member_permission'])
        {
             $username = $PowerBB->_CONF['member_row']['username'];
		     $Email = $PowerBB->DB->sql_query("SELECT  *   FROM " . $PowerBB->table['member'] . " WHERE username LIKE '$username' ");

		       while ($getstyle_row = $PowerBB->DB->sql_fetch_array($Email))
		      {
			   $PowerBB->template->assign('email',$getstyle_row['email']);
		     }
             	$PowerBB->template->display('send_admin');

      	}
		else
		{
		  $PowerBB->template->display('login');
		  $PowerBB->functions->error_stop();
		}

	}

	function _SendStart()
	{
		global $PowerBB;
		if (!$PowerBB->_CONF['info_row']['active_send_admin_message'])
		{
		header('HTTP/1.1 403 FORBIDDEN');
		header('Status: 403 You Do Not Have Access To This Page');
		exit;
		}
		if ($PowerBB->_POST['insert'] != $PowerBB->_CONF['template']['_CONF']['lang']['Send_Message'])
		{
		header('HTTP/1.1 503 Service Unavailable');
		exit;
		}

		if ($PowerBB->_SERVER['REQUEST_METHOD'] != 'POST')
		{
		header('HTTP/1.1 503 Service Unavailable');
		exit;
		}
		$PowerBB->functions->ShowHeader();

		if (!$PowerBB->_CONF['member_permission'])
     	{
     		$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Visitors_can_not_send_an_email']);
     	}

		$flood_send_email_time = "40";
		if ((@time() - $flood_send_email_time) <= $_SESSION['last_send_email_time'])
		{
		$PowerBB->functions->error(" <p>It requires you to wait ".$flood_send_email_time." seconds to send Mail again.</p>");
		}
  		$PowerBB->_POST['email'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['email'],'html');
		$PowerBB->_POST['text']   = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['text'] ,'html');

       	$PowerBB->_POST['username']   = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['username'] ,'trim');
       	$PowerBB->_POST['email']   = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['email'] ,'trim');

       	$PowerBB->_POST['username']   = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['username'] ,'html');

  		$PowerBB->_POST['email'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['email'],'sql');
		$PowerBB->_POST['text']   = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['text'] ,'sql');
       	$PowerBB->_POST['username']   = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['username'] ,'sql');

		$PowerBB->_POST['code']    =    $PowerBB->functions->CleanVariable($PowerBB->_POST['code'],'trim');
		$PowerBB->_POST['code']   = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['code'],'sql');
		$PowerBB->_POST['code']   = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['code'],'html');

		  // Check if the email is valid, This line will prevent any false email
		  if(!$PowerBB->functions->CheckEmail($PowerBB->_POST['email']))
		  {
		   $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_enter_your_correct_email']);
		  }
        if($PowerBB->_CONF['info_row']['captcha_type'] == 'captcha_Q_A')
		 {
             if($PowerBB->_POST['code'] != $PowerBB->_POST['code_answer'])
			 {
	            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['random_answer_not_correct']);
		     }
	     }

       if($PowerBB->_CONF['info_row']['captcha_type'] == 'captcha_IMG')
		 {
	        if(md5($PowerBB->_POST['code']) != $_SESSION['captcha_key'])
			 {
	            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Code_that_you_enter_the_wrong']);
		     }
	     }

		if (empty($PowerBB->_POST['text']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['You_do_not_type_the_letter']);
		}
		if (empty($PowerBB->_POST['username']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['You_do_not_type_your_name']);
		}
		if (empty($PowerBB->_POST['email']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['You_do_not_enter_your_email_address']);
		}
		if (!$PowerBB->functions->CheckEmail($PowerBB->_POST['email']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_enter_a_valid_e-mail']);
		}

		$PowerBB->_POST['text'] = str_replace("\n","<br>",$PowerBB->_POST['text']);
		$PowerBB->_POST['text'] = str_ireplace('{39}',"'",$PowerBB->_POST['text']);
		$PowerBB->_POST['text'] = str_ireplace('cookie','**',$PowerBB->_POST['text']);
		$censorwords = preg_split('#[ \r\n\t]+#', $PowerBB->_CONF['info_row']['censorwords'], -1, PREG_SPLIT_NO_EMPTY);
		$PowerBB->_POST['text'] = str_ireplace($censorwords,'**', $PowerBB->_POST['text']);
		$message = $PowerBB->_CONF['template']['_CONF']['lang']['This_message_received_from_the_Contact_Us_form'].'<br />'.$PowerBB->_POST['text'];
		$PowerBB->_POST['text'] = str_replace("\n","<br>",$PowerBB->_POST['text']);
		if ($PowerBB->_CONF['info_row']['mailer']=='phpmail')
		{
		$send_admin = $PowerBB->functions->send_this_php($PowerBB->_CONF['info_row']['admin_email'],$PowerBB->_POST['username'].' - '.$PowerBB->_CONF['template']['_CONF']['lang']['This_message_received_from_the_Contact_Us_form'],$message,$PowerBB->_POST['email']);
		}
		elseif ($PowerBB->_CONF['info_row']['mailer']=='smtp')
		{
		$to = $PowerBB->_CONF['info_row']['admin_email'];
		$fromname = $PowerBB->_CONF['info_row']['title'];
		$message = $message;
		$subject = $PowerBB->_POST['username'];
		$from = $PowerBB->_POST['email'];
		$Send = $PowerBB->functions->send_this_smtp($to,$fromname,$message,$subject,$from);
		}
		$_SESSION['last_send_email_time'] = $PowerBB->_CONF['now'];
		$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['your_message_has_been_sent_successfully']);
		$PowerBB->functions->redirect('index.php');

	}
}

?>
