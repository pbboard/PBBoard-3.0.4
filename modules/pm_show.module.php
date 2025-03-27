<?php

(!defined('IN_PowerBB')) ? die() : '';

$CALL_SYSTEM					=	array();
$CALL_SYSTEM['PM'] 				= 	true;
$CALL_SYSTEM['ICONS'] 			= 	true;
$CALL_SYSTEM['TOOLBOX'] 		= 	true;
$CALL_SYSTEM['FILESEXTENSION'] 	= 	true;
$CALL_SYSTEM['ATTACH'] 			= 	true;
$CALL_SYSTEM['MODERATORS'] 	= 	true;
$CALL_SYSTEM['USERRATING'] 	= 	true;

define('JAVASCRIPT_PowerCode',true);



define('CLASS_NAME','PowerBBPrivateMassegeShowMOD');

include('common.php');
class PowerBBPrivateMassegeShowMOD
{
	function run()
	{
		global $PowerBB;

		if (!$PowerBB->_CONF['info_row']['pm_feature'])
		{
            $PowerBB->functions->ShowHeader();
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['no_pm']);
		}
		/** Can't use the private massege system **/
		$PowerBB->template->assign('use_pm',$PowerBB->_CONF['rows']['group_info']['use_pm']);

		/** Can't use the private massege system **/
		if ($PowerBB->_CONF['member_row']['posts']  < $PowerBB->_CONF['rows']['group_info']['min_send_pm'])
		{
		 $PowerBB->template->assign('use_pm',0);
		}


		/** **/

		/** Visitor can't use the private massege system **/
		if (!$PowerBB->_CONF['member_permission'])
		{
            $PowerBB->functions->ShowHeader();
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Cant_see_pm']);
		}
		/** **/

		/** Read a massege **/
		if ($PowerBB->_GET['show'])
		{
			$this->_ShowMassege();
		}
		else
		{
			 $PowerBB->functions->ShowHeader();
             $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Sorry_url_not_true']);
             $PowerBB->functions->GetFooter();
		}

		$PowerBB->functions->GetFooter();
	}

	/**
	 * Get a massege information to show it
	 */
	function _ShowMassege()
	{
		global $PowerBB;

		$PowerBB->functions->ShowHeader();

		if (empty($PowerBB->_GET['id']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}

		$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');

		$id = $PowerBB->_GET['id'];
		$MsgArr = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['pm'] . " WHERE id = '$id'");
        $MassegeInfo = $PowerBB->DB->sql_fetch_array($MsgArr);

   		// Get the attachment information
		$date = $MassegeInfo['date'];
		$Attach_Info = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['attach'] . " WHERE time = '$date'");
        $Attach_Row = $PowerBB->DB->sql_fetch_array($Attach_Info);


        $AttachArr 				= 	array();
		if($MassegeInfo['date'] == $Attach_Row['time'])
		{
		$AttachArr['where'] 	= 	array('time',$Attach_Row['time']);
		}
		else
		{
		$AttachArr['where'] 	= 	array('pm_id',$PowerBB->_GET['id']);
		}


		$PowerBB->_CONF['template']['while']['AttachList'] = $PowerBB->core->GetList($AttachArr,'attach');

		$PowerBB->template->assign('ATTACH_SHOW_PM','1');


       /*
		if ($MassegeInfo['user_to'] != $PowerBB->_CONF['member_row']['username'] or $MassegeInfo['user_from'] != $PowerBB->_CONF['member_row']['username'])
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Letter_requested_does_not_exist']);
		}
       */

		$PrivateMsgArr 			= 	array();
		$PrivateMsgArr['id'] 		= 	$PowerBB->_GET['id'];
		$PrivateMsgArr['username'] = 	$PowerBB->_CONF['member_row']['username'];

		$PowerBB->_CONF['template']['MassegeRow'] = $PowerBB->pm->GetPrivateMassegeInfo($PrivateMsgArr);

		if (!$PowerBB->_CONF['template']['MassegeRow'])
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Letter_requested_does_not_exist']);
		}

		if ($PowerBB->_CONF['template']['MassegeRow']['user_to'] != $PowerBB->_CONF['member_row']['username'] AND $PowerBB->_CONF['template']['MassegeRow']['user_from'] != $PowerBB->_CONF['member_row']['username'])
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Letter_requested_does_not_exist']);
		}


		$PowerBB->functions->CleanVariable($PowerBB->_CONF['template']['MassegeRow'],'html');
		$PowerBB->functions->CleanVariable($PowerBB->_CONF['template']['MassegeRow'],'sql');


		$SenderArr = array();
		$SenderArr['where']		=	array('username',$PowerBB->_CONF['template']['MassegeRow']['user_from']);

		//$PowerBB->_CONF['template']['Info'] = $PowerBB->member->GetMemberInfo($SenderArr);
		$PowerBB->_CONF['template']['ReplierInfo'] = $PowerBB->member->GetMemberInfo($SenderArr);

		if (is_numeric($PowerBB->_CONF['template']['ReplierInfo']['register_date']))
		{
			$PowerBB->_CONF['template']['ReplierInfo']['register_date'] = $PowerBB->functions->year_date($PowerBB->_CONF['template']['ReplierInfo']['register_date']);
		}
		// Get username style
       $PowerBB->_CONF['template']['ReplierInfo']['display_username'] = $PowerBB->_CONF['template']['ReplierInfo']['username_style_cache'];

		$PowerBB->functions->CleanVariable($PowerBB->_CONF['template']['Info'],'html');


   		// Get username style
		$MemberArr 			= 	array();
		$MemberArr['where'] 	= 	array('username',$PowerBB->_CONF['template']['MassegeRow']['user_from']);

		$StyleMemberInfo = $PowerBB->core->GetInfo($MemberArr,'member');

	  	$GrpArr 			= 	array();
		$GrpArr['where'] 	= 	array('id',$StyleMemberInfo['usergroup']);

		$GroupStyleInfo = $PowerBB->core->GetInfo($GrpArr,'group');

	  	$GroupStyleInfo['username_style'] = str_ireplace('[username]',$PowerBB->_CONF['template']['MassegeRow']['user_from'],$GroupStyleInfo['username_style']);

       $PowerBB->template->assign('username_sender',$GroupStyleInfo['username_style']);
      ////////
         // replace away msg
		   $PowerBB->_CONF['template']['Info']['away_msg'] = $PowerBB->Powerparse->replace($PowerBB->_CONF['template']['Info']['away_msg']);
           $PowerBB->Powerparse->replace_smiles($PowerBB->_CONF['template']['Info']['away_msg']);

		// feltr away msg

        //
        $Adress = $PowerBB->functions->GetForumAdress();
        if(strstr($PowerBB->_CONF['template']['MassegeRow']['text'],"<a href='".$Adress."index.php?page=misc&rules=1&show=1'>"))
        {        $PowerBB->_CONF['template']['MassegeRow']['text']= str_replace("<a href='".$Adress."index.php?page=misc&rules=1&show=1'>","[url=".$Adress."rules.html]".$PowerBB->_CONF['template']['_CONF']['lang']['rules'],$PowerBB->_CONF['template']['MassegeRow']['text']);
        $PowerBB->_CONF['template']['MassegeRow']['text']= str_replace($PowerBB->_CONF['template']['_CONF']['lang']['rules']."</a>","[/url]",$PowerBB->_CONF['template']['MassegeRow']['text']);
        }

        $PowerBB->_CONF['template']['MassegeRow']['text']= str_replace('../look/','look/',$PowerBB->_CONF['template']['MassegeRow']['text']);
		$send_text = $PowerBB->_CONF['template']['MassegeRow']['text'];


		 // Moderator And admin Check for View the Icons Editing and Deletion
		$ModArr 			= 	array();
		$ModArr['where'] 	= 	array('username',$PowerBB->_CONF['member_row']['username']);

		$PowerBB->_CONF['template']['while']['ModeratorsList'] = $PowerBB->moderator->GetModeratorList($ModArr);

		if (is_array($PowerBB->_CONF['template']['while']['ModeratorsList'])
			and sizeof($PowerBB->_CONF['template']['while']['ModeratorsList']) > 0)
		{
			$PowerBB->template->assign('mod_toolbar',0);
		}
		else
		{
			$PowerBB->template->assign('mod_toolbar',1);
		}


		$PowerBB->_CONF['template']['MassegeRow']['title']	=	str_replace($PowerBB->_CONF['template']['_CONF']['lang']['Reply_pm'],'',$PowerBB->_CONF['template']['MassegeRow']['title']);

		if (is_numeric($PowerBB->_CONF['template']['MassegeRow']['date']))
		{
			$MassegeDate = $PowerBB->functions->_date($PowerBB->_CONF['template']['MassegeRow']['date']);

			$PowerBB->_CONF['template']['MassegeRow']['date'] = $MassegeDate;
		}



		// The writer signture isn't empty
		if (!empty($PowerBB->_CONF['template']['Info']['user_sig']))
		{
			// So , use the PowerCode in it
			$PowerBB->_CONF['template']['Info']['user_sig'] = $PowerBB->Powerparse->replace($PowerBB->_CONF['template']['Info']['user_sig']);
			$PowerBB->Powerparse->replace_smiles($PowerBB->_CONF['template']['Info']['user_sig']);
			$PowerBB->_CONF['template']['show_sig'] = 1;
		}
		$censorwords = preg_split('#[ \r\n\t]+#', $PowerBB->_CONF['info_row']['censorwords'], -1, PREG_SPLIT_NO_EMPTY);
		$send_text = str_ireplace($censorwords,'**', $send_text);
		$send_text = str_ireplace('{39}',"'",$send_text);
       $send_text = $PowerBB->Powerparse->censor_words($send_text);
       $send_text = $PowerBB->functions->CleanVariable($send_text,'html');

		$PowerBB->template->assign('send_title',$PowerBB->_CONF['template']['_CONF']['lang']['Reply_pm'] . $PowerBB->_CONF['template']['MassegeRow']['title']);
		$PowerBB->template->assign('send_text','[quote]' . $PowerBB->Powerparse->replace_htmlentities($send_text) . '[/quote]');
		if ($PowerBB->_CONF['template']['MassegeRow']['folder'] == 'sent')
		{
       		$PowerBB->template->assign('to',$PowerBB->_CONF['template']['MassegeRow']['user_to']);
		}
		else
		{
		$PowerBB->template->assign('to',$PowerBB->_CONF['template']['MassegeRow']['user_from']);
		}
				// feltr pm Text
        $censorwords = preg_split('#[ \r\n\t]+#', $PowerBB->_CONF['info_row']['censorwords'], -1, PREG_SPLIT_NO_EMPTY);
        $PowerBB->_CONF['template']['MassegeRow']['title'] = str_ireplace($censorwords,'**', $PowerBB->_CONF['template']['MassegeRow']['title']);
           $PowerBB->_CONF['template']['MassegeRow']['title'] = str_ireplace('cookie','**',$PowerBB->_CONF['template']['MassegeRow']['title']);
        $PowerBB->_CONF['template']['MassegeRow']['title'] = str_ireplace('{39}',"'",$PowerBB->_CONF['template']['MassegeRow']['title']);

	     $PowerBB->_CONF['template']['MassegeRow']['text'] = $PowerBB->Powerparse->replace($PowerBB->_CONF['template']['MassegeRow']['text']);
		$PowerBB->Powerparse->replace_smiles($PowerBB->_CONF['template']['MassegeRow']['text']);
        $PowerBB->Powerparse->replace_wordwrap($PowerBB->_CONF['template']['MassegeRow']['text']);

		// feltr Subject Text
         $PowerBB->_CONF['template']['MassegeRow']['text'] = $PowerBB->Powerparse->censor_words($PowerBB->_CONF['template']['MassegeRow']['text']);
		// Kill SQL Injection
      // $PowerBB->_CONF['template']['MassegeRow']['text'] = $PowerBB->functions->CleanVariable($PowerBB->_CONF['template']['MassegeRow']['text'],'sql');

        // Kill XSS
//       $PowerBB->_CONF['template']['MassegeRow']['text'] = $PowerBB->functions->CleanVariable($PowerBB->_CONF['template']['MassegeRow']['text'],'html');
       $PowerBB->_CONF['template']['MassegeRow']['title'] = $PowerBB->functions->CleanVariable($PowerBB->_CONF['template']['MassegeRow']['title'],'html');
       $PowerBB->_CONF['template']['MassegeRow']['title'] = $PowerBB->functions->CleanVariable($PowerBB->_CONF['template']['MassegeRow']['title'],'sql');

				$PowerBB->functions->GetEditorTools();

		$cache = json_decode(base64_decode($PowerBB->_CONF['member_row']['style_cache']), true);
        $image_path = $PowerBB->_CONF['rows']['style']['image_path'];

        $MemberArr 			= 	array();
		$MemberArr['where'] 	= 	array('username',$PowerBB->_CONF['template']['MassegeRow']['user_from']);

		$MemberInfo = $PowerBB->core->GetInfo($MemberArr,'member');

		$CheckOnline = ($MemberInfo['logged'] < $PowerBB->_CONF['timeout']) ? false : true;

		($CheckOnline) ? $PowerBB->template->assign('user_online',true) : $PowerBB->template->assign('user_online',false);

		if (!$PowerBB->_CONF['template']['MassegeRow']['user_read'])
		{
			$ReadArr 						= 	array();
			$ReadArr['where'] 				= 	array();

			$ReadArr['where'][0] 			= 	array();
			$ReadArr['where'][0]['name'] 	= 	'id';
			$ReadArr['where'][0]['oper'] 	= 	'=';
			$ReadArr['where'][0]['value'] 	= 	$PowerBB->_GET['id'];

			$Read = $PowerBB->pm->MakeMassegeRead($ReadArr);

			if ($Read)
			{
				$NumArr 				= 	array();
				$NumArr['username'] 	= 	$PowerBB->_CONF['member_row']['username'];

				$Number = $PowerBB->pm->NewMessageNumber($NumArr);

				$CacheArr 					= 	array();
				$CacheArr['field']			=	array();

				$CacheArr['field']['unread_pm'] 	= 	$Number;
				$CacheArr['where'] 					= 	array('username',$PowerBB->_CONF['member_row']['username']);

				$Cache = $PowerBB->member->UpdateMember($CacheArr);
			}
		}
			$AttachArr 							= 	array();
			$AttachArr['where']					= 	array();
			$AttachArr['where'][0] 				=	array();
			$AttachArr['where'][0]['name'] 		=	'pm_id';
			$AttachArr['where'][0]['oper'] 		=	'=';
			$AttachArr['where'][0]['value'] 	=	'-'.$PowerBB->_CONF['member_row']['id'];

			$PowerBB->_CONF['template']['while']['PmAttachList'] = $PowerBB->core->GetList($AttachArr,'attach');

		////////////////////
		$ExArr 						= 	array();
		$ExArr['order']				=	array();
		$ExArr['order']['field']	=	'id';
		$ExArr['order']['type']		=	'DESC';
		$ExArr['proc'] 				= 	array();
		$ExArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');

		$PowerBB->_CONF['template']['while']['ExList'] = $PowerBB->core->GetList($ExArr,'ex');

        			//get user title
			$titles = $PowerBB->usertitle->GetCachedTitles();
            $size = sizeof($titles);
			for ($i = 0; $i <= $size; $i++)
			{
				if($titles[$size-1]['posts'] < $PowerBB->_CONF['template']['ReplierInfo']['posts'])
				{
				$user_titles = $titles[$size-1]['usertitle'];
				break;
				}
				if($titles[$i]['posts'] > $PowerBB->_CONF['template']['ReplierInfo']['posts'])
				{
				$user_titles = $titles[$i]['usertitle'];
				break;
				}
				if($PowerBB->_CONF['template']['ReplierInfo']['posts'] < $titles[1]['posts'])
				{
				$user_titles = $titles[1]['usertitle'];
				break;
				}
			}

            $PowerBB->template->assign('Usertitle',$user_titles);
            //////////

			//get user rating
			$ratings = $PowerBB->userrating->GetCachedRatings();
            $y = sizeof($ratings);
			for ($b = 0; $b <= $y; $b++)
			{
				if($ratings[$y-1]['posts'] < $PowerBB->_CONF['template']['ReplierInfo']['posts'])
				{
				$user_ratings = $ratings[$y-1]['rating'];
				$user_posts = $ratings[$y-1]['posts'];
				break;
				}
				if($ratings[$b]['posts'] > $PowerBB->_CONF['template']['ReplierInfo']['posts'])
				{
				$user_ratings = $ratings[$b]['rating'];
				$user_posts = $ratings[$b]['posts'];
				break;
				}
				if($PowerBB->_CONF['template']['ReplierInfo']['posts'] < $ratings[1]['posts'])
				{
				$user_ratings = $ratings[1]['rating'];
				$user_posts = $ratings[1]['posts'];
				break;
				}
			}

			 $PowerBB->_CONF['template']['RatingInfo']['rating'] = $user_ratings;
			 $PowerBB->_CONF['template']['RatingInfo']['posts'] = $user_posts;

		$GrpArr 			= 	array();
		$GrpArr['where'] 	= 	array('id',$PowerBB->_CONF['template']['Info']['usergroup']);

		$GroupInfo = $PowerBB->core->GetInfo($GrpArr,'group');
        $PowerBB->template->assign('GroupInfo',$GroupInfo);

		if ($PowerBB->_GET['page'] == 'pm_show')
		{
			$PowerBB->template->assign('address_bar_pm_send_SHOW',true);
		}

        // show Custom_bbcode List
		$Custom_bbcodeArr 					= 	array();
		$Custom_bbcodeArr['order']			=	array();
		$Custom_bbcodeArr['order']['field']	=	'id';
		$Custom_bbcodeArr['order']['type']	=	'DESC';
		$Custom_bbcodeArr['proc'] 			= 	array();
		$Custom_bbcodeArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');

		$PowerBB->_CONF['template']['while']['Custom_bbcodesList'] = $PowerBB->core->GetList($Custom_bbcodeArr,'custom_bbcode');


    	$PowerBB->_CONF['template']['_CONF']['info_row']['show_list_last_5_posts_member'] = '0';



		$PowerBB->template->display('pm_show');
	}
}

?>
