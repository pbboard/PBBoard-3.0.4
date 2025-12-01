<?php
(!defined('IN_PowerBB')) ? die() : '';
$CALL_SYSTEM					=	array();
$CALL_SYSTEM['LOG_VISIT_PROFILE'] = true;

define('CLASS_NAME','PowerBBProfileMOD');
include('common.php');
class PowerBBProfileMOD
{
	function run()
	{
		global $PowerBB;

		if (!$PowerBB->_CONF['group_info']['memberlist_allow'])
		{

          if (!$PowerBB->_CONF['member_permission'])
              {
		      header("HTTP/1.1 403 Forbidden");
		      header("Status: 403 Forbidden");
		      $PowerBB->functions->ShowHeader();
              $PowerBB->template->assign('timer',$PowerBB->sys_functions->_time(time()));
              $PowerBB->template->display('login');
              $PowerBB->functions->error_stop();
			}
	        else
	        {
	        $PowerBB->functions->ShowHeader();
	        $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['no_online']);
	        }
	     }
     $PowerBB->_GET['id']	= 	$PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');
     $PowerBB->_GET['username']	= 	$PowerBB->functions->CleanVariable($PowerBB->_GET['username'],'sql');

		/** Show the profile of member **/
		if ($PowerBB->_GET['show'])
		{
		    $PowerBB->functions->ShowHeader();
			$this->_ShowProfile();
		}
		else
		{
		     header("HTTP/1.1 403 Forbidden");
		     header("Status: 403 Forbidden");
			 $PowerBB->functions->ShowHeader();
             $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Sorry_url_not_true']);
             $PowerBB->functions->GetFooter();
		}

		$PowerBB->functions->GetFooter();
	}

	/** Get member information and show it **/
	function _ShowProfile()
	{
		global $PowerBB;

		//////////
		// Show the header
		eval($PowerBB->functions->get_fetch_hooks('profileHooksStart'));

       		////////
	    // Extra Field info
	    $extraEmptyFields=$PowerBB->extrafield->getEmptyProfileFields();
	    $fieldsRow='';
	    foreach($extraEmptyFields AS $field)
	    $fieldsRow.=$field['name_tag'].',';
	    $PowerBB->_CONF['template']['while']['extrafield']=&$extraEmptyFields;

		//////////
		// Get the member information

		$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');

		$MemArr = array();
		// Well I think we are the biggest sneaky in the world after wrote these lines :D
		if (!empty($PowerBB->_GET['id']))
		{
			$id = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');

			$MemArr['where'] = array('id',$id);

			$do_query = ($PowerBB->_CONF['member_row']['id'] == $PowerBB->_GET['id']) ? false : true;
		}
		elseif (!empty($PowerBB->_GET['username']))
		{
			$MemArr['where'] 	= 	array('username',$PowerBB->_GET['username']);

			$do_query = ($PowerBB->_CONF['member_row']['username'] == $PowerBB->_GET['username']) ? false : true;
		}
		else
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}

		$PowerBB->_CONF['template']['MemberInfo'] = ($do_query) ? $PowerBB->member->GetMemberInfo($MemArr) : $PowerBB->_CONF['member_row'];

		if ($PowerBB->_CONF['template']['MemberInfo']['style_sheet_profile']!="")
		{
	        $PowerBB->_CONF['template']['MemberInfo']['style_sheet_profile'] = $PowerBB->Powerparse->feltr_words($PowerBB->_CONF['template']['MemberInfo']['style_sheet_profile']);
			$PowerBB->_CONF['template']['MemberInfo']['style_sheet_profile'] = preg_replace('#<(.*?)>#i', "", $PowerBB->_CONF['template']['MemberInfo']['style_sheet_profile']);
			$PowerBB->_CONF['template']['MemberInfo']['style_sheet_profile'] = preg_replace('#&lt;(.*?)&gt;#i', "", $PowerBB->_CONF['template']['MemberInfo']['style_sheet_profile']);
			$PowerBB->_CONF['template']['MemberInfo']['style_sheet_profile']     = str_ireplace("<", "", $PowerBB->_CONF['template']['MemberInfo']['style_sheet_profile'] );
			$PowerBB->_CONF['template']['MemberInfo']['style_sheet_profile']     = str_ireplace(">", "", $PowerBB->_CONF['template']['MemberInfo']['style_sheet_profile'] );
			$PowerBB->_CONF['template']['MemberInfo']['style_sheet_profile']     = str_ireplace("&lt;", "", $PowerBB->_CONF['template']['MemberInfo']['style_sheet_profile'] );
			$PowerBB->_CONF['template']['MemberInfo']['style_sheet_profile']     = str_ireplace("&gt;", "", $PowerBB->_CONF['template']['MemberInfo']['style_sheet_profile'] );
			$PowerBB->_CONF['template']['MemberInfo']['style_sheet_profile']     = str_ireplace("$", "", $PowerBB->_CONF['template']['MemberInfo']['style_sheet_profile'] );
		}


		if (!$PowerBB->_CONF['template']['MemberInfo']['username'])
		{
		 $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Member_you_want_does_not_exist']);
		}

		/**
		* Log profile visits
		* @var unknown_type
		*/
		if ($PowerBB->_CONF['template']['MemberInfo']['profile_viewers'])
		{
		$PowerBB->log_profile_visit->startLog($PowerBB->_CONF['member_row']['id'], $PowerBB->_CONF['template']['MemberInfo']['id']);
		$PowerBB->log_profile_visit->getViewersList($PowerBB->_CONF['template']['MemberInfo']['id']);
        }


		// Getting member group
		$GroupInfo = array();
		$GroupInfo['where'] = array('id',$PowerBB->_CONF['template']['MemberInfo']['usergroup']);
		$PowerBB->_CONF['group_info'] = $PowerBB->core->GetInfo($GroupInfo,'group');

		//////////

		// Kill XSS first
		$PowerBB->functions->CleanVariable($PowerBB->_CONF['template']['MemberInfo'],'html');
		// Second Kill SQL Injections
		$PowerBB->functions->CleanVariable($PowerBB->_CONF['template']['MemberInfo'],'sql');

		//////////

		// Warning Process !
		if ($PowerBB->_CONF['rows']['group_info']['send_warning'] == 1 && $PowerBB->_CONF['group_info']['can_warned'] == 1)
			{
			$PowerBB->_CONF['template']['MemberInfo']['Warnings'] = 1;
			}else{
			$PowerBB->_CONF['template']['MemberInfo']['Warnings'] = 0;
			}

            // edit member by exchangeboss
       if ($PowerBB->_CONF['rows']['group_info']['admincp_member'] == 1 && $PowerBB->_CONF['rows']['group_info']['admincp_allow'] == 1)
          {
          $PowerBB->_CONF['template']['MemberInfo']['edit_member'] = 1;
          }else{
          $PowerBB->_CONF['template']['MemberInfo']['edit_member'] = 0;
          }

		// Where is the member now?
		if ($PowerBB->_CONF['member_permission'])
     	{
     		$UpdateOnline 			= 	array();
			$UpdateOnline['field']	=	array();

			$UpdateOnline['field']['user_location']		=	 $PowerBB->_CONF['template']['_CONF']['lang']['See_User_Profile'] .' ' . $PowerBB->_CONF['template']['MemberInfo']['username'];
			$UpdateOnline['where']						=	array('username',$PowerBB->_CONF['member_row']['username']);

			$update = $PowerBB->core->Update($UpdateOnline,'online');
     	}
     	// Where is the Visitor now?
		if (!$PowerBB->_CONF['member_permission'])
     	{
     		$UpdateOnline 			= 	array();
			$UpdateOnline['field']	=	array();

			$UpdateOnline['field']['user_location']		=	 $PowerBB->_CONF['template']['_CONF']['lang']['See_User_Profile'] .' ' . $PowerBB->_CONF['template']['MemberInfo']['username'];
			$UpdateOnline['where']						=	array('user_ip',$PowerBB->_CONF['ip']);

			$update = $PowerBB->core->Update($UpdateOnline,'online');
     	}

     	//////////
     	$user_time= $PowerBB->_CONF['template']['MemberInfo']['user_time'];
     	$format = $PowerBB->_CONF['info_row']['datesystem'].' '.$PowerBB->_CONF['info_row']['timesystem'];
        if($PowerBB->_CONF['template']['MemberInfo']['user_time'] == 0)
        {		$timer = @date($format, @time());
        }
        else
        {        $timer = @date($format, @strtotime($user_time));
        }
		$timer = str_ireplace('PM',$PowerBB->_CONF['template']['_CONF']['lang']['PM'],$timer);
		$timer = str_ireplace('AM',$PowerBB->_CONF['template']['_CONF']['lang']['AM'],$timer);
		$PowerBB->_CONF['template']['MemberInfo']['user_time'] = $timer;

		if (is_numeric($PowerBB->_CONF['template']['MemberInfo']['register_date']))
		{
			$PowerBB->_CONF['template']['MemberInfo']['register_date'] = $PowerBB->functions->_date($PowerBB->_CONF['template']['MemberInfo']['register_date']);
		}

		// We should be sneaky sometime ;)
		if ($PowerBB->_CONF['member_row']['usergroup'] == $PowerBB->_CONF['template']['MemberInfo']['usergroup'])
		{
			$GroupInfo = $PowerBB->_CONF['rows']['group_info'];
		}
		else
		{
			$GroupInfo 				= 	array();
			$GroupInfo['where'] 	= 	array('id',$PowerBB->_CONF['template']['MemberInfo']['usergroup']);

			$GroupInfo = $PowerBB->core->GetInfo($GroupInfo,'group');
		}

		$PowerBB->_CONF['template']['MemberInfo']['usergroup'] = $GroupInfo['title'];

        $MemberIDArr 			= 	array();
		$MemberIDArr['where'] 	= 	array('id',$PowerBB->_GET['id']);

		$MemberIDInfo = $PowerBB->member->GetMemberInfo($MemberIDArr);

		$MemberUsernameArr 			= 	array();
		$MemberUsernameArr['where'] 	= 	array('username',$PowerBB->_GET['username']);

		$MemberUsernameInfo= $PowerBB->core->GetInfo($MemberArr,'member');

        $MemberuserArr 			= 	array();
		$MemberuserArr['where'] 	= 	array('username',$PowerBB->_CONF['member_row']['username']);

		$MemberuserInfo = $PowerBB->member->GetMemberInfo($MemberuserArr);

		// Is writer online ?
		$CheckOnlineId = ($MemberIDInfo['logged'] < $PowerBB->_CONF['timeout']) ? false : true;
        $CheckOnlineUsername = ($MemberIDInfo['logged'] < $PowerBB->_CONF['timeout']) ? false : true;
        $CheckOnlineUsername_1 = ($MemberuserInfo['logged'] < $PowerBB->_CONF['timeout']) ? false : true;
		// If the member is online , so store that in status variable
		 $image_path = $PowerBB->_CONF['rows']['style']['image_path'];
		($CheckOnlineId) ? $PowerBB->template->assign('user_online',true) : $PowerBB->template->assign('user_online',false);
        ($CheckOnlineUsername) ? $PowerBB->template->assign('user_online',true) : $PowerBB->template->assign('user_online',false);
 		if ($PowerBB->_CONF['member_row']['username'] == $PowerBB->_GET['username'])
		{
        ($CheckOnlineUsername_1) ? $PowerBB->template->assign('user_online',true) : $PowerBB->template->assign('user_online',false);
        }
             $forum_not_1 = $PowerBB->_CONF['info_row']['last_subject_writer_not_in'];

		if ($PowerBB->_CONF['template']['MemberInfo']['posts'] > 0)
		{

			$LastSubjectArr 						= 	array();
			$LastSubjectArr['where'] 				= 	array();
			$LastSubjectArr['where'][0] 			= 	array();
			$LastSubjectArr['where'][0]['name'] 	= 	'writer';
			$LastSubjectArr['where'][0]['oper'] 	= 	'=';
			$LastSubjectArr['where'][0]['value'] 	= 	$PowerBB->_CONF['template']['MemberInfo']['username'];

            $LastSubjectArr['where'][1] 			= 	array();
			$LastSubjectArr['where'][1]['con']		=	'AND';
		    $LastSubjectArr['where'][1]['name'] 	= 	'section not in (' .$forum_not_1. ') AND review_subject<>1 AND sec_subject<>1 AND delete_topic';
			$LastSubjectArr['where'][1]['oper'] 	= 	'<>';
			$LastSubjectArr['where'][1]['value'] 	= 	'1';

			$LastSubjectArr['order'] 				= 	array();
			$LastSubjectArr['order']['field'] 		= 	'id';
			$LastSubjectArr['order']['type']	 	= 	'DESC';

			$LastSubjectArr['limit'] 				= 	'0,1';

			$PowerBB->_CONF['template']['LastSubject'] = $PowerBB->subject->GetSubjectInfo($LastSubjectArr);

			$PowerBB->functions->CleanVariable($PowerBB->_CONF['template']['LastSubject'],'html');
          }

            $forum_not_1 = $PowerBB->_CONF['info_row']['last_subject_writer_not_in'];
			$LastReplyArr 						= 	array();

			$LastReplyArr['where'] 				= 	array();
			$LastReplyArr['where'][0] 			= 	array();
			$LastReplyArr['where'][0]['name'] 	= 	'writer';
			$LastReplyArr['where'][0]['oper'] 	= 	'=';
			$LastReplyArr['where'][0]['value'] 	= 	$PowerBB->_CONF['template']['MemberInfo']['username'];

            $LastReplyArr['where'][1] 			= 	array();
			$LastReplyArr['where'][1]['con']		=	'AND';
			$LastReplyArr['where'][1]['name'] 	= 	'section not in (' .$forum_not_1. ') AND review_reply<>1 AND delete_topic';
			$LastReplyArr['where'][1]['oper'] 	= 	'<>';
			$LastReplyArr['where'][1]['value'] 	= 	'1';

			$LastReplyArr['order'] 				= 	array();
			$LastReplyArr['order']['field'] 	= 	'id';
			$LastReplyArr['order']['type']	 	= 	'DESC';

			$LastReplyArr['limit'] 				= 	'1';

			$GetLastReplyInfo = $PowerBB->reply->GetReplyInfo($LastReplyArr);

			$PowerBB->functions->CleanVariable($GetLastReplyInfo,'sql');

			if ($GetLastReplyInfo != false)
			{
				$SubjectArr 			= 	array();
				$SubjectArr['where'] 	= 	array('id',$GetLastReplyInfo['subject_id']);

				$PowerBB->_CONF['template']['LastReply'] = $PowerBB->core->GetInfo($SubjectArr,'subject');
				$PowerBB->_CONF['template']['LastReply']['reply_id'] = $GetLastReplyInfo['id'];

				$PowerBB->functions->CleanVariable($PowerBB->_CONF['template']['LastReply'],'html');
			}


		$OnlineArr 				= 	array();
        $OnlineArr['where'] 	= 	array('username',$PowerBB->_CONF['template']['MemberInfo']['username']);
		$PowerBB->_CONF['template']['Location'] = $PowerBB->online->GetOnlineInfo($OnlineArr);
        $PowerBB->_CONF['template']['Location']['path'] = $PowerBB->functions->rewriterule('index.php?'.$PowerBB->_CONF['template']['Location']['path']);

			$PowerBB->_CONF['template']['MemberInfo']['user_sig'] = $PowerBB->Powerparse->replace($PowerBB->_CONF['template']['MemberInfo']['user_sig']);
			$PowerBB->_CONF['template']['MemberInfo']['user_sig'] = str_replace('&amp;','&',$PowerBB->_CONF['template']['MemberInfo']['user_sig']);
			$PowerBB->Powerparse->replace_smiles($PowerBB->_CONF['template']['MemberInfo']['user_sig']);
		$PowerBB->_GET['count'] = (!isset($PowerBB->_GET['count'])) ? 0 : $PowerBB->_GET['count'];
		$PowerBB->_GET['count'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['count'],'intval');

        $perpage_Friends = '300';
        $user_namee  = $PowerBB->_CONF['template']['MemberInfo']['username'];
    	$GetFriendsNum = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT distinct username_friend, userid_friend FROM " . $PowerBB->table['friends'] . " WHERE username = '$user_namee' and approval = '1'"));

		// show Friends
		$FriendsArr 					= 	array();
		$FriendsArr['where']			= 	array();
        $FriendsArr['select'] 	        = 	'distinct username_friend, userid_friend';

		$FriendsArr['where'][0] 			= 	array();
		$FriendsArr['where'][0]['name'] 	=  'username';
		$FriendsArr['where'][0]['oper']		=  '=';
		$FriendsArr['where'][0]['value']    =  $PowerBB->_CONF['template']['MemberInfo']['username'];

		$FriendsArr['order']			=	array();
		$FriendsArr['order']['field']	=	'id';
		$FriendsArr['order']['type']	=	'DESC';
        $FriendsArr['limit']             =    $perpage_Friends;
		$FriendsArr['proc'] 			= 	array();
		$FriendsArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');

       $PowerBB->_CONF['template']['while']['FriendsList'] = $PowerBB->core->GetList($FriendsArr,'friends');

	   $nm = sizeof($PowerBB->_CONF['template']['while']['FriendsList']);
       $PowerBB->template->assign('nm_friend',$nm);
       $IsFreind = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['friends'] . " WHERE username='".$PowerBB->_CONF['template']['MemberInfo']['username']."' AND username_friend='".$PowerBB->_CONF['member_row']['username']."' or username_friend='".$PowerBB->_CONF['template']['MemberInfo']['username']."' AND username='".$PowerBB->_CONF['member_row']['username']."'" );

       $IsFreind_row = $PowerBB->DB->sql_fetch_array($IsFreind);
		if($PowerBB->DB->sql_num_rows($IsFreind) > 0)
		{
          $PowerBB->template->assign('is_friend',1);
          $PowerBB->template->assign('friendship_id',$IsFreind_row['id']."&amp;userid=".$PowerBB->_CONF['template']['MemberInfo']['id']);

		}
       else
        {
		$PowerBB->template->assign('is_friend',0);
        }

       if ($nm > 0)
		{
            $PowerBB->template->assign('edit_friend',1);
			$PowerBB->template->assign('No_Friends',false);
		}
     	else
		{
			$PowerBB->template->assign('No_Friends',true);
		}

       if($PowerBB->_CONF['template']['MemberInfo']['username'] != $PowerBB->_CONF['member_row']['username'])
       {
          $PowerBB->template->assign('dont_shwo_friend',3);
       }


		$SmlArr 					= 	array();
		$SmlArr['order'] 			=	array();
		$SmlArr['order']['field']	=	'id';
		$SmlArr['order']['type']	=	'ASC';
		$SmlArr['limit']			=	$PowerBB->_CONF['info_row']['smiles_nm'];
		$SmlArr['proc'] 			= 	array();
		$SmlArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');

		$PowerBB->_CONF['template']['while']['SmileRows'] = $PowerBB->icon->GetSmileList($SmlArr);

		//////////
		//show Award member
       $ALL_Awards_nm = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(1),id FROM " . $PowerBB->table['award'] . " "));
       if ($ALL_Awards_nm > 0)
		{

		 $username = $PowerBB->_CONF['template']['MemberInfo']['username'];
         $Award_nm = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(1),id FROM " . $PowerBB->table['award'] . " WHERE username='$username'"));
         $PowerBB->template->assign('Awards_nm',$Award_nm);

		$AwardArr 							= 	array();
		$AwardArr['proc'] 					= 	array();
		$AwardArr['proc']['*'] 				= 	array('method'=>'clean','param'=>'html');

		$AwardArr['where']					=	array();
		$AwardArr['where'][0]				=	array();
		$AwardArr['where'][0]['name']		=	'username';
		$AwardArr['where'][0]['oper']		=	'=';
		$AwardArr['where'][0]['value']		=	$PowerBB->_CONF['template']['MemberInfo']['username'];

		$AwardArr['order']					=	array();
		$AwardArr['order']['field']			=	'id';
		$AwardArr['order']['type']			=	'DESC';


       $PowerBB->_CONF['template']['while']['AwardsList'] = $PowerBB->core->GetList($AwardArr,'award');
       }


		// Show VisitorMessage
		$PowerBB->_GET['count'] = (!isset($PowerBB->_GET['count'])) ? 0 : $PowerBB->_GET['count'];
		$PowerBB->_GET['count'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['count'],'intval');

      	// Get the Visitor Message num
 		 if ($PowerBB->_CONF['info_row']['active_visitor_message'] == '1')
		 {
		      	$userid = $PowerBB->_CONF['template']['MemberInfo']['id'];
		        $GetVisitorMessageNum = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(1),id FROM " . $PowerBB->table['visitormessage'] . " WHERE userid = '$userid'"));
		       	$PowerBB->template->assign('visitor_message_num',$GetVisitorMessageNum);
		       	$perpage = '8';

				$VisitorMessageArr 					        = 	array();

				$VisitorMessageArr['where'] 				= 	array();
				$VisitorMessageArr['where'][0] 		     	= 	array();
				$VisitorMessageArr['where'][0]['name']   	=  'userid';
				$VisitorMessageArr['where'][0]['oper']		=  '=';
				$VisitorMessageArr['where'][0]['value']     =  $PowerBB->_CONF['template']['MemberInfo']['id'];

			   // Pager setup
				$VisitorMessageArr['pager'] 				= 	array();
				$VisitorMessageArr['pager']['total']		= 	$GetVisitorMessageNum;
				$VisitorMessageArr['pager']['perpage']  	= 	$perpage;
				$VisitorMessageArr['pager']['count'] 		= 	$PowerBB->_GET['count'];
				$VisitorMessageArr['pager']['location'] 	= 	'index.php?page=profile&show=1&id='.$PowerBB->_CONF['template']['MemberInfo']['id'];
				$VisitorMessageArr['pager']['var'] 		    = 	'count';

				$VisitorMessageArr['order']		        	=	array();
				$VisitorMessageArr['order']['field']    	=	'id';
				$VisitorMessageArr['order']['type']     	=	'DESC';

				$VisitorMessageArr['proc'] 			        = 	array();
				$VisitorMessageArr['proc']['*'] 	     	= 	array('method'=>'clean','param'=>'html');
				$VisitorMessageArr['proc']['dateline']      = 	array('method'=>'date','type'=>$PowerBB->_CONF['info_row']['timesystem']);



			    $PowerBB->_CONF['template']['while']['VisitorMessageList'] = $PowerBB->core->GetList($VisitorMessageArr,'visitormessage');

				if ($PowerBB->_CONF['template']['while']['VisitorMessageList'] == false)
				{
					$PowerBB->template->assign('No_VisitorMessageList',true);
				}

			    if ($GetVisitorMessageNum > $perpage)
		        {
				$PowerBB->template->assign('pager',$PowerBB->pager->show());
		        }

		}
       $PowerBB->template->assign('MemberInfoid',$PowerBB->_CONF['template']['MemberInfo']['id']);
		if ($PowerBB->_CONF['member_row']['id'] == $PowerBB->_CONF['template']['MemberInfo']['id'])
		{
			$PowerBB->template->assign('show',true);
		}
     	else
		{
			$PowerBB->template->assign('show',false);
		}


		if ($PowerBB->_CONF['member_row']['id'] == $PowerBB->_CONF['template']['MemberInfo']['id'])
		{
		 $UpdateArr 				= 	array();
		 $UpdateArr['field']		=	array();

		 $UpdateArr['field']['messageread'] 		= 	'0';
	     $UpdateArr['where'] 						= 	array('userid',$PowerBB->_CONF['template']['MemberInfo']['id']);

		 $update = $PowerBB->core->Update($UpdateArr,'visitormessage');
        }

       $PowerBB->template->assign('group_info_visitormessage',$PowerBB->_CONF['group_info']['visitormessage']);

			//get user title
			$titles = $PowerBB->usertitle->GetCachedTitles();
            $size = sizeof($titles);
			for ($i = 0; $i <= $size; $i++)
			{
				if($titles[$size-1]['posts'] < $PowerBB->_CONF['template']['MemberInfo']['posts'])
				{
				$user_titles = $titles[$size-1]['usertitle'];
				break;
				}
				elseif($titles[$i]['posts'] > $PowerBB->_CONF['template']['MemberInfo']['posts'])
				{
				$user_titles = $titles[$i]['usertitle'];
				break;
				}
				elseif($PowerBB->_CONF['template']['MemberInfo']['posts'] < $titles[1]['posts'])
				{
				$user_titles = $titles[1]['usertitle'];
				break;
				}
			}

            //////////

			//get user rating
			$ratings = $PowerBB->userrating->GetCachedRatings();
            $y = sizeof($ratings);
			for ($b = 0; $b <= $y; $b++)
			{
				if($ratings[$y-1]['posts'] < $PowerBB->_CONF['template']['MemberInfo']['posts'])
				{
				$user_ratings = $ratings[$y-1]['rating'];
				$user_posts = $ratings[$y-1]['posts'];
				break;
				}
				if($ratings[$b]['posts'] > $PowerBB->_CONF['template']['MemberInfo']['posts'])
				{
				$user_ratings = $ratings[$b]['rating'];
				$user_posts = $ratings[$b]['posts'];
				break;
				}
				if($PowerBB->_CONF['template']['MemberInfo']['posts'] < $ratings[1]['posts'])
				{
				$user_ratings = $ratings[1]['rating'];
				$user_posts = $ratings[1]['posts'];
				break;
				}
			}


			 if($PowerBB->_CONF['group_info']['user_title'] != '')
			 {			 	$PowerBB->_CONF['template']['MemberInfo']['user_title'] = $PowerBB->_CONF['group_info']['user_title'];
			 }
			 //$PowerBB->_CONF['template']['RatingInfo']['rating'] = $user_ratings;
			 $PowerBB->_CONF['template']['RatingInfo']['posts'] = $user_posts;
			 $PowerBB->_CONF['template']['MemberInfo']['user_title'] = $PowerBB->_CONF['template']['MemberInfo']['user_title'];
			 $PowerBB->template->assign('Usertitle',$user_titles);
			 $PowerBB->template->assign('user_ratings',$user_ratings);
          /////////
        $PowerBB->template->assign('GroupInfo',$PowerBB->_CONF['group_info']);
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

		   eval($PowerBB->functions->get_fetch_hooks('profileHooksEnd'));
		$PowerBB->template->display('profile');
	}
}

?>