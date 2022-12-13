<?php

(!defined('IN_PowerBB')) ? die() : '';

$CALL_SYSTEM				=	array();
$CALL_SYSTEM['REQUEST'] 	= 	true;
$CALL_SYSTEM['MESSAGE'] 	= 	true;
$CALL_SYSTEM['CORE'] 	= 	true;
$CALL_SYSTEM['CACHE'] 		= 	true;
$CALL_SYSTEM['MEMBER'] 		= 	true;



define('CLASS_NAME','PowerBBPasswordMOD');

include('common.php');
class PowerBBPasswordMOD
{
	function run()
	{
		global $PowerBB;

  		// Stop any external post request.
         if ($PowerBB->_SERVER['REQUEST_METHOD'] == 'POST')
         {
         $Y = explode('/',$PowerBB->_SERVER['HTTP_REFERER']);
         $X = explode('/',$PowerBB->_SERVER['HTTP_HOST']);
         if ($Y[2] != $X[0])
         {
          exit('No direct script access allowed - المعذرة هذه الطريقة غير شرعية');
         }
         if ($Y[2] != $PowerBB->_SERVER['HTTP_HOST'])
         {
          exit('No direct script access allowed - المعذرة هذه الطريقة غير شرعية');
         }
       }


		if ($PowerBB->_GET['index'])
		{
			$this->Index();
		}
		elseif ($PowerBB->_GET['pass_change'])
		{
			$this->_PasswordChange_1();
		}
		else
		{
			header("Location: index.php");
			exit;
		}
		$PowerBB->functions->GetFooter();
	}

	function Index()
	{
		global $PowerBB;

	    $PowerBB->functions->ShowHeader($PowerBB->_CONF['template']['_CONF']['lang']['Completion_changing_the_password']);

	   	$PowerBB->functions->AddressBar($PowerBB->_CONF['template']['_CONF']['lang']['Completion_changing_the_password']);

		if (empty($PowerBB->_GET['code']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Sorry_url_not_true']);
		}

		if ($PowerBB->_GET['id'])
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Sorry_url_not_true']);
		}

		$ReqArr 			= 	array();
		$ReqArr['where'] 	= 	array('random_url',$PowerBB->_GET['code']);

		$RequestInfo = $PowerBB->request->GetRequestInfo($ReqArr);

		if (!$RequestInfo)
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Sorry_demand_does_not_exist']);
		}


		if ($RequestInfo['request_type'] !=1)
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Sorry_demand_does_not_exist']);
		}

		$MemberArr 			= 	array();
		$MemberArr['where'] 	= 	array('username',$RequestInfo['username']);

		$ForgetMemberInfo = $PowerBB->core->GetInfo($MemberArr,'member');


		if ($ForgetMemberInfo)
		{
		    $new_password = md5($ForgetMemberInfo['new_password']);
		      $GetForumAdress = $PowerBB->functions->GetForumAdress();
             if ($PowerBB->_CONF['template']['_CONF']['lang']['lostpw'] !='')
              {               $MassegeInfo['text'] = $PowerBB->_CONF['template']['_CONF']['lang']['lostpw'];
               $MassegeInfo['text'] = str_replace("{forum_url}", $GetForumAdress, $MassegeInfo['text']);
               $MassegeInfo['text'] = str_replace("{name_mem}", $RequestInfo['username'], $MassegeInfo['text']);
               $MassegeInfo['text'] = str_replace("{paas_mem}", $ForgetMemberInfo['new_password'], $MassegeInfo['text']);
               if ($PowerBB->_CONF['template']['_CONF']['lang']['resetpw'] !='')
               {
               $MassegeInfo['title'] = $PowerBB->_CONF['template']['_CONF']['lang']['resetpw'];
               }
               else
               {
               $MassegeInfo['title'] = "كلمة المرور الجديدة في {bbtitle} ";
               }

                $MassegeInfo['title'] = str_replace("{bbtitle}", $PowerBB->_CONF['info_row']['title'], $MassegeInfo['title']);

              }
              else
              {
			   $MassegeInfo['text'] = "According to your request, was restored your password. Your new found below: <br>User Name:<br>".$RequestInfo['username']."<br>Password:<br>".$ForgetMemberInfo['new_password'];
			   $MassegeInfo['title'] = "New password in the ".$PowerBB->_CONF['info_row']['title'];
              }
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
			{			$PassArr 			= 	array();
			$PassArr['field'] 	= 	array();

			$PassArr['field']['password'] 	= 	$new_password;
			$PassArr['field']['new_password'] 	= 	"";
			$PassArr['where'] 				= 	array('username',$RequestInfo['username']);

			$UpdatePassword = $PowerBB->core->Update($PassArr,'member');


	        $DelArr				=	array();
	        $DelArr['where'] 	= 	array('random_url',$PowerBB->_GET['code']);

			$CleanReq = $PowerBB->core->Deleted($DelArr,'requests');

			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Password_has_been_sent_to_E-mail']);
			$PowerBB->functions->redirect('index.php',2);
			}
			else
			{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['is_not_sent']);
			}


		}
       else
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Sorry_demand_does_not_exist']);
		}

	}

	function _PasswordChange_1()
	{
		global $PowerBB;

		$PowerBB->functions->ShowHeader($PowerBB->_CONF['template']['_CONF']['lang']['Ongoing_process']);

		if (empty($PowerBB->_GET['code']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Sorry_url_not_true']);
		}

		if ($PowerBB->_GET['id'])
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Sorry_url_not_true']);
		}

		$ReqArr 			= 	array();
		$ReqArr['where'] 	= 	array('random_url',$PowerBB->_GET['code']);

		$RequestInfo = $PowerBB->request->GetRequestInfo($ReqArr);

		if (!$RequestInfo)
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Sorry_demand_does_not_exist']);
		}
		$MemberArr 			= 	array();
		$MemberArr['where'] 	= 	array('username',$RequestInfo['username']);

		$MemberInfo = $PowerBB->core->GetInfo($MemberArr,'member');


		//////////

		   $MemberInfo['new_password'] = md5($MemberInfo['new_password']);

			$PassArr 				= 	array();
			$PassArr['field']		=	array();
			$PassArr['field']['password'] 			= 	$MemberInfo['new_password'];
			$PassArr['where'] = array('id',$MemberInfo['id']);

			$UpdatePassword = $PowerBB->core->Update($PassArr,'member');

			if ($UpdatePassword)
			{
                $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['password_was_changed_successfully']);
				$PowerBB->functions->redirect('index.php?page=login&sign=1');
			}

	}

}

?>
