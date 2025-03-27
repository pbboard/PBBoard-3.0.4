<?php
(!defined('IN_PowerBB')) ? die() : '';
$CALL_SYSTEM				=	array();
$CALL_SYSTEM['BANNED'] 		= 	true;
$CALL_SYSTEM['CACHE'] 		= 	true;
$CALL_SYSTEM['REQUEST'] 	= 	true;
$CALL_SYSTEM['MESSAGE'] 	= 	true;
$CALL_SYSTEM['EXTRAFIELD']   =   true;
$CALL_SYSTEM['GROUP'] 	= 	true;


define('CLASS_NAME','PowerBBCoreMOD');
include('common.php');
class PowerBBCoreMOD
{
	function run()
	{
		global $PowerBB;
		// The index page for active
		if ($PowerBB->_GET['index'] == '1')
		{
			$this->_Index();
		}
		else
		{
			$PowerBB->functions->header_redirect('index.php');
		}

	}

	function _Index()
	{
		global $PowerBB;

		// No code !
        $PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');

		if (empty($PowerBB->_GET['code'])
			or empty($PowerBB->_GET['id']))
		{
			$PowerBB->functions->header_redirect('index.php');
		}

		$MemberReqArr 			= 	array();
		$MemberReqArr['where'] 	= 	array('id',$PowerBB->_GET['id']);

		$ReqMemberInfo = $PowerBB->core->GetInfo($MemberReqArr,'member');

		if ($ReqMemberInfo['usergroup'] !='5')
		{
		    $PowerBB->functions->ShowHeader($PowerBB->_CONF['template']['_CONF']['lang']['Activation_members']);
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Sorry_demand_does_not_exist']);
		    $PowerBB->functions->GetFooter();
		}

		$ReqArr 			= 	array();
		$ReqArr['where'] 	= 	array('random_url',$PowerBB->_GET['code']);
		// Get request information
		$RequestInfo = $PowerBB->core->GetInfo($ReqArr,'requests');

		if ($RequestInfo['username'] != $ReqMemberInfo['username'])
		{
		    $PowerBB->functions->ShowHeader($PowerBB->_CONF['template']['_CONF']['lang']['Activation_members']);
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Sorry_demand_does_not_exist']);
		    $PowerBB->functions->GetFooter();
		}

		$ReqArr 			= 	array();
		$ReqArr['where'] 	= 	array('username',$ReqMemberInfo['username']);
		// Get request information
		$RequestInfo = $PowerBB->core->GetInfo($ReqArr,'requests');

		// No request , so stop the page
		if (!$RequestInfo['request_type'] =='3')
		{
		    $PowerBB->functions->ShowHeader($PowerBB->_CONF['template']['_CONF']['lang']['Activation_members']);
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Sorry_demand_does_not_exist']);
		    $PowerBB->functions->GetFooter();
		}

      	//////////

      	// Get the information of default group to set username style cache

		$GroupArr 			= 	array();
		$GroupArr['where'] 	= 	array('id',$PowerBB->_CONF['info_row']['adef_group']);

		$GroupInfo = $PowerBB->core->GetInfo($GroupArr,'group');


		$style = $GroupInfo['username_style'];
		$username_style_cache = str_replace('[username]',$ReqMemberInfo['username'],$style);

      	//////////

		$GroupArr 				= 	array();
		$GroupArr['field'] 		= 	array();

		$GroupArr['field']['usergroup'] 			= 	$PowerBB->_CONF['info_row']['adef_group'];
		$GroupArr['field']['username_style_cache']	=	$username_style_cache;
		$GroupArr['where'] 							= 	array('id',$PowerBB->_GET['id']);

		// We found the request , so active the member
		$UpdateGroup = $PowerBB->core->Update($GroupArr,'member');

		// Update username_style to Members Group username_style in Online Today
		$UpdateTodayArr 			= 	array();
		$UpdateTodayArr['field']	=	array();

		$UpdateTodayArr['field']['username_style'] 	= 	$username_style_cache;
       	$UpdateTodayArr['where']						=	array('user_id',$PowerBB->_GET['id']);

		$UpdateToday = $PowerBB->core->Update($UpdateTodayArr,'today');


		// The active is success
		if ($UpdateGroup)
		{

		      		// Send a private message welcoming new user.
			      	if($PowerBB->_CONF['info_row']['activ_welcome_message'])
			      	{
						// Send a private message welcoming new user.
						$Adress	= 	$PowerBB->functions->GetForumAdress();
						$Massege  = $PowerBB->_CONF['info_row']['welcome_message_text'] ;
						$Massege = str_replace('{username}',$ReqMemberInfo['username'],$Massege);
						$Massege = str_replace('{title}',$PowerBB->_CONF['info_row']['title']."\n",$Massege);
						$Massege = str_replace('{rules}',"<a href='".$Adress."index.php?page=misc&rules=1&show=1'>".$PowerBB->_CONF['template']['_CONF']['lang']['rules']."</a>",$Massege);
						$PowerBB->_CONF['template']['_CONF']['lang']['welcome_message_title'] = str_replace('{title}',$PowerBB->_CONF['info_row']['title'],$PowerBB->_CONF['template']['_CONF']['lang']['welcome_message_title']);
						$Massege_title = $PowerBB->_CONF['template']['_CONF']['lang']['welcome_message_title'];
						$user_from = "1";
						$MemberArr          =    array();
						$MemberArr['where']  =   array('id',$user_from);
						$MemberInfo = $PowerBB->member->GetMemberInfo($MemberArr);

				      	if($PowerBB->_CONF['info_row']['welcome_message_mail_or_private'] == '1')
				      	{
					       if ($PowerBB->_CONF['info_row']['mailer']=='phpmail')
							{
						      $Send = $PowerBB->functions->send_this_php($ReqMemberInfo['email'],$Massege_title,$Massege,$PowerBB->_CONF['info_row']['send_email']);
				            }
				           elseif ($PowerBB->_CONF['info_row']['mailer']=='smtp')
							{
								$to = $ReqMemberInfo['email'];
								$fromname = $PowerBB->_CONF['info_row']['title'];
								$messagetext = $Massege;
								$subject = $Massege_title;
								$from = $PowerBB->_CONF['info_row']['send_email'];
			                    $Send = $PowerBB->functions->send_this_smtp($to,$fromname,$messagetext,$subject,$from);
							}

				      	}
				      	elseif($PowerBB->_CONF['info_row']['welcome_message_mail_or_private'] == '2')
				      	{

							$MsgArr       =    array();
							$MsgArr['field']   =   array();
							$MsgArr['field']['user_from']  =   $MemberInfo['username'];
							$MsgArr['field']['user_to']    =   $ReqMemberInfo['username'];
							$MsgArr['field']['title']  =   $Massege_title;
							$MsgArr['field']['text']   =   $Massege;
							$MsgArr['field']['date']   =   $PowerBB->_CONF['now'];
							$MsgArr['field']['folder']   =   'inbox';
							$MsgArr['field']['icon']       =   'look/images/icons/i1.gif';

							$Send = $PowerBB->pm->InsertMassege($MsgArr);

							$NumberArr    = array();
							$NumberArr['username'] =   $ReqMemberInfo['username'];
							$Number = $PowerBB->pm->NewMessageNumber($NumberArr);

							$CacheArr        =  array();
							$CacheArr['field'] =    array();
							$CacheArr['field']['unread_pm']    =   $Number;

							$CacheArr['where']      =  array('username',$ReqMemberInfo['username']);

							$Cache = $PowerBB->member->UpdateMember($CacheArr);
						}
				      	elseif($PowerBB->_CONF['info_row']['welcome_message_mail_or_private'] == '3')
				      	{
							$MsgArr       =    array();
							$MsgArr['field']   =   array();
							$MsgArr['field']['user_from']  =   $MemberInfo['username'];
							$MsgArr['field']['user_to']    =   $ReqMemberInfo['username'];
							$MsgArr['field']['title']  =   $Massege_title;
							$MsgArr['field']['text']   =   $Massege;
							$MsgArr['field']['date']   =   $PowerBB->_CONF['now'];
							$MsgArr['field']['folder']   =   'inbox';
							$MsgArr['field']['icon']       =   'look/images/icons/i1.gif';

							$Send = $PowerBB->pm->InsertMassege($MsgArr);

							$NumberArr    = array();
							$NumberArr['username'] =   $ReqMemberInfo['username'];
							$Number = $PowerBB->pm->NewMessageNumber($NumberArr);

							$CacheArr        =  array();
							$CacheArr['field'] =    array();
							$CacheArr['field']['unread_pm']    =   $Number;

							$CacheArr['where']      =  array('username',$ReqMemberInfo['username']);

							$Cache = $PowerBB->member->UpdateMember($CacheArr);

					       if ($PowerBB->_CONF['info_row']['mailer']=='phpmail')
							{
						      $Send = $PowerBB->functions->send_this_php($ReqMemberInfo['email'],$Massege_title,$Massege,$PowerBB->_CONF['info_row']['send_email']);
				            }
				           elseif ($PowerBB->_CONF['info_row']['mailer']=='smtp')
							{
								$to = $ReqMemberInfo['email'];
								$fromname = $PowerBB->_CONF['info_row']['title'];
								$messagetext = $Massege;
								$subject = $Massege_title;
								$from = $PowerBB->_CONF['info_row']['send_email'];
			                    $Send = $PowerBB->functions->send_this_smtp($to,$fromname,$messagetext,$subject,$from);
							}

				      	}

			       }

		$RequesArr 			= 	array();
		$RequesArr['where'] 	= 	array('random_url',$PowerBB->_GET['code']);
		$RequesInfo = $PowerBB->core->Deleted($RequesArr,'requests');

			$username = $PowerBB->functions->CleanVariable($ReqMemberInfo['username'],'trim');
			$username = $PowerBB->functions->CleanVariable($ReqMemberInfo['username'],'sql');
			$password = $PowerBB->functions->CleanVariable($ReqMemberInfo['password'],'sql');
			$password = $PowerBB->functions->CleanVariable($ReqMemberInfo['password'],'trim');


    		@session_start();
    		$expire = time()+31536000;
    		$_SESSION[$PowerBB->_CONF['username_cookie']] = $ReqMemberInfo['username'];
    		$_SESSION[$PowerBB->_CONF['password_cookie']] = $ReqMemberInfo['password'];
    		$_SESSION['expire'] = $expire;

		    $PowerBB->functions->ShowHeader($PowerBB->_CONF['template']['_CONF']['lang']['Activation_members']);
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['activated_successfully']);
		    $PowerBB->functions->GetFooter();
			$PowerBB->functions->redirect2('index.php');

		}
	}
}

?>