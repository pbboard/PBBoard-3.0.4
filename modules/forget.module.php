<?php
session_start();
(!defined('IN_PowerBB')) ? die() : '';

$CALL_SYSTEM				=	array();
$CALL_SYSTEM['REQUEST'] 	= 	true;
$CALL_SYSTEM['MESSAGE'] 	= 	true;
$CALL_SYSTEM['CORE'] 	= 	true;
$CALL_SYSTEM['CACHE'] 		= 	true;
$CALL_SYSTEM['MEMBER'] 		= 	true;



define('CLASS_NAME','PowerBBForgetMOD');

include('common.php');
class PowerBBForgetMOD
{
	function run()
	{
		global $PowerBB;

		if ($PowerBB->_GET['index'])
		{
			$this->_Index();
		}
		elseif ($PowerBB->_GET['start'])
		{
			$this->_Start();
		}
		elseif ($PowerBB->_GET['active_member'])
		{
			$this->_IndexActiveMember();
		}
		elseif ($PowerBB->_GET['send_active_code'])
		{
			$this->_SendActiveCode();
		}
		else
		{
			header("Location: index.php");
			exit;
		}

		$PowerBB->functions->GetFooter();
	}

	function _Index()
	{
		global $PowerBB;

		$PowerBB->functions->ShowHeader($PowerBB->_CONF['template']['_CONF']['lang']['Lost_password']);

		if ($PowerBB->_CONF['info_row']['captcha_o'] == 1)
		{
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
       	 }

		$PowerBB->template->display('forget_password_form');
	}

	function _Start()
	{
		global $PowerBB;
         if ($PowerBB->_SERVER['REQUEST_METHOD'] != 'POST')
         {
         	 header("Location: index.php?page=forget&index=1");
		     exit;
         }

		$PowerBB->functions->ShowHeader();
			if (!$PowerBB->_CONF['member_permission'])
			{
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

            }
		$PowerBB->functions->AddressBar($PowerBB->_CONF['template']['_CONF']['lang']['execution_Lost_password']);

		if (empty($PowerBB->_POST['email']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_fill_in_all_the_information']);
		}

		if (!$PowerBB->functions->CheckEmail($PowerBB->_POST['email']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_enter_your_correct_email']);
		}

		$CheckArr 			= 	array();
		$CheckArr['where']	=	array('email',$PowerBB->_POST['email']);

		$CheckEmail = $PowerBB->member->IsMember($CheckArr);

		if (!$CheckEmail)
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['E-mail_does_not_exist_in_our_databases']);
		}

		$MemberArr 			= 	array();
		$MemberArr['where'] 	= 	array('email',$PowerBB->_POST['email']);

		$ForgetMemberInfo = $PowerBB->core->GetInfo($MemberArr,'member');

		$Adress = 	$PowerBB->functions->GetForumAdress();
		$Code	=	$PowerBB->functions->RandomCode();

		$ChangeAdress = $Adress . 'index.php?page=new_password&index=1&code=' . $Code;
		$CancelAdress = $Adress . 'index.php?page=cancel_requests&index=1&type=1&code=' . $Code;

		$ReqArr 				= 	array();
		$ReqArr['field']		=	array();

		$ReqArr['field']['random_url'] 		= 	$Code;
		$ReqArr['field']['username'] 		= 	$ForgetMemberInfo['username'];
		$ReqArr['field']['request_type'] 	= 	1;

		$InsertReq = $PowerBB->core->Insert($ReqArr,'requests');

		if ($InsertReq)
		{
			$UpdateArr 					= 	array();
			$UpdateArr['field']			=	array();

			$UpdateArr['field']['new_password'] 	= 	$this->randomPassword();
			$UpdateArr['where'] 					= 	array('id',$ForgetMemberInfo['id']);

			$UpdateNewPassword = $PowerBB->core->Update($UpdateArr,'member');

			if ($UpdateNewPassword)
			{
				$MsgArr 			= 	array();
				$MsgArr['where'] 	= 	array('id','1');

				$MassegeInfo = $PowerBB->core->GetInfo($MsgArr,'email_msg');

				$MsgArr 				= 	array();
				$MsgArr['text'] 		= 	$MassegeInfo['text'];
				$MsgArr['change_url'] 	= 	$ChangeAdress;
				$MsgArr['cancel_url'] 	= 	$CancelAdress;
				$MsgArr['username']		=	$PowerBB->_CONF['member_row']['username'];
				$MsgArr['title']		=	$PowerBB->_CONF['info_row']['title'];

				$MassegeInfo['text'] = $PowerBB->core->MessageProccess($MsgArr,'email_msg');


				if ($PowerBB->_CONF['info_row']['mailer']=='phpmail')
						{
				         $Send = $PowerBB->functions->send_this_php($ForgetMemberInfo['email'],$MassegeInfo['title'],$MassegeInfo['text'],$PowerBB->_CONF['info_row']['send_email']);
			            }
						elseif ($PowerBB->_CONF['info_row']['mailer']=='smtp')
						{
						$to = $ForgetMemberInfo['email'];
						$fromname = $PowerBB->_CONF['info_row']['title'];
						$message = $MassegeInfo['text'];
						$subject = $MassegeInfo['title'];
						$from = $PowerBB->_CONF['info_row']['send_email'];
                        $Send = $PowerBB->functions->send_this_smtp($to,$fromname,$message,$subject,$from);
						}

				if ($Send)
				{
					$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Password_has_been_sent_to_E-mail']);
					$PowerBB->functions->redirect('index.php',2);
				}
				else
				{
					$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['is_not_sent']);
				}
			}
		}
	}

	function _IndexActiveMember()
	{
		global $PowerBB;

		$PowerBB->functions->ShowHeader();

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

		$MemberArr 			= 	array();
		$MemberArr['where'] 	= 	array('username',$PowerBB->_CONF['rows']['member_row']['username']);

		$GettMemberEmail = $PowerBB->core->GetInfo($MemberArr,'member');
		$PowerBB->template->assign('MemberEmail',$GettMemberEmail['email']);
        $PowerBB->_CONF['template']['_CONF']['member_permission'] = '0';
		$PowerBB->template->display('send_active_code');
	}

	function _SendActiveCode()
	{
		global $PowerBB;

        if($PowerBB->_CONF['info_row']['captcha_type'] == 'captcha_Q_A')
		 {
               if($PowerBB->_POST['code'] != $PowerBB->_POST['code_answer'])
			 {
			 	$PowerBB->functions->ShowHeader();
	            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['random_answer_not_correct']);
		     }
	     }
        if($PowerBB->_CONF['info_row']['captcha_type'] == 'captcha_IMG')
		 {
	        if(md5($PowerBB->_POST['code']) != $_SESSION['captcha_key'])
			 {
			 	$PowerBB->functions->ShowHeader();
	            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Code_that_you_enter_the_wrong']);
		     }
	    }
		if (!$PowerBB->functions->CheckEmail($PowerBB->_POST['email']))
		{		     $PowerBB->functions->ShowHeader();
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_enter_a_valid_e-mail']);
		}

		if (empty($PowerBB->_POST['email']))
		{
		     $PowerBB->functions->ShowHeader();
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_fill_in_all_the_information']);
		}

		if (!$PowerBB->member->IsMember(array('where' => array('email',$PowerBB->_POST['email']))))
		{
            $PowerBB->functions->ShowHeader();
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['E-mail_does_not_exist_in_our_databases']);
		}



		$MemberArr 			= 	array();
		$MemberArr['where'] 	= 	array('email',$PowerBB->_POST['email']);

		$ActiveMemberInfo = $PowerBB->core->GetInfo($MemberArr,'member');

        if ($ActiveMemberInfo['usergroup'] !=5)
         {
         $PowerBB->functions->ShowHeader();         $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['not_need_to_activate']);
         }

    	$Adress	= 	$PowerBB->functions->GetForumAdress();
		$Code	=	$PowerBB->functions->RandomCode();

		$ActiveAdress = $Adress . 'index.php?page=active_member&index=1&id=' . $ActiveMemberInfo['id'] . '&code=' . $Code;

		$ReqArr 			= 	array();
		$ReqArr['field'] 	= 	array();

		$ReqArr['field']['random_url'] 		= 	$Code;
		$ReqArr['field']['username'] 		= 	$ActiveMemberInfo['username'];
		$ReqArr['field']['request_type'] 	= 	3;

		$InsertReq = $PowerBB->core->Insert($ReqArr,'requests');

		if ($InsertReq)
		{
			$MsgArr 			= 	array();
			$MsgArr['where'] 	= 	array('id','4');

			$MassegeInfo = $PowerBB->core->GetInfo($MsgArr,'email_msg');

			$MsgArr = array();

			$MsgArr['text'] 		= 	$MassegeInfo['text'];
			$MsgArr['active_url'] 	= 	$ActiveAdress;
			$MsgArr['username']		=	$ActiveMemberInfo['username'];
			$MsgArr['title']		=	$PowerBB->_CONF['info_row']['title'];

			$MassegeInfo['text'] = $PowerBB->core->MessageProccess($MsgArr,'email_msg');

				if ($PowerBB->_CONF['info_row']['mailer']=='phpmail')
						{
			             $Send = $PowerBB->functions->send_this_php($PowerBB->_POST['email'],$MassegeInfo['title'],$MassegeInfo['text'],$PowerBB->_CONF['info_row']['send_email']);
			            }
						elseif ($PowerBB->_CONF['info_row']['mailer']=='smtp')
						{
						$to = $PowerBB->_POST['email'];
						$fromname = $PowerBB->_CONF['info_row']['title'];
						$message = $MassegeInfo['text'];
						$subject = $MassegeInfo['title'];
						$from = $PowerBB->_CONF['info_row']['send_email'];

                         $Send = $PowerBB->functions->send_this_smtp($to,$fromname,$message,$subject,$from);
						}

            $PowerBB->functions->ShowHeader();
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['has_been_sent_to_your_e-mail_activation']);

		   // $PowerBB->functions->redirect('index.php');

		}

	}

	function randomPassword()
	{
	    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
	    $pass = array(); //remember to declare $pass as an array
	    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
	    for ($i = 0; $i < 8; $i++) {
	        $n = rand(0, $alphaLength);
	        $pass[] = $alphabet[$n];
	    }
	    return implode($pass); //turn the array into a string
	}

}

?>
