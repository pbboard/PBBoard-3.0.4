<?php
(!defined('IN_PowerBB')) ? die() : '';


define('JAVASCRIPT_PowerCode',true);

define('CLASS_NAME','PowerBBPortalMOD');

include('common.php');
class PowerBBPortalMOD
{
	function run()
	{
		global $PowerBB;
 		if (!$PowerBB->_CONF['info_row']['active_portal'])
		{
			header("Location: index.php");
			exit;
        }
       $PowerBB->template->assign('portal_page','primary_tabon');
		$PowerBB->functions->ShowHeader();

   		/** Show Portal form **/
		if ($PowerBB->_GET['page'] == 'portal')
		{

			$this->_GetPortal();

		}

		$this->_GetJumpSectionsList();

       $PowerBB->functions->GetFooter();
	}

	function _GetPortal()
	{
   		global $PowerBB;


		if ($PowerBB->_CONF['info_row']['portal_columns'] == '1' )
     	{
		 $PowerBB->template->assign('columns','100%');
		 $PowerBB->template->assign('active_right','0');
		 $PowerBB->template->assign('active_left','0');

     	}
       if ($PowerBB->_CONF['info_row']['portal_columns'] == '2' )
        {
		 $PowerBB->template->assign('columns','100%');
		 $PowerBB->template->assign('active_right','1');
		 $PowerBB->template->assign('active_left','0');

     	}
       if ($PowerBB->_CONF['info_row']['portal_columns'] == '3' )
        {
		 $PowerBB->template->assign('columns','60%');
		 $PowerBB->template->assign('active_right','1');
		 $PowerBB->template->assign('active_left','1');
     	}
		// Where is the member now?
		if ($PowerBB->_CONF['member_permission'])
     	{
     		$UpdateOnline 			= 	array();
			$UpdateOnline['field']	=	array();

			$UpdateOnline['field']['user_location'] 	= 	$PowerBB->_CONF['template']['_CONF']['lang']['Seen_the'].' <a href="index.php?page=portal">المجلة</a>';
			$UpdateOnline['where']						=	array('username',$PowerBB->_CONF['member_row']['username']);

			$update = $PowerBB->core->Update($UpdateOnline,'online');
     	}

     	// Where is the Visitor now?
		if (!$PowerBB->_CONF['member_permission'])
     	{
     		$UpdateOnline 			= 	array();
			$UpdateOnline['field']	=	array();

			$UpdateOnline['field']['user_location'] 	= 	$PowerBB->_CONF['template']['_CONF']['lang']['Seen_the'].' <a href="index.php?page=portal">المجلة</a>';
			$UpdateOnline['where']						=	array('user_ip',$PowerBB->_CONF['ip']);
			$update = $PowerBB->core->Update($UpdateOnline,'online');
     	}

    	$PowerBB->_GET['count'] = (!isset($PowerBB->_GET['count'])) ? 0 : $PowerBB->_GET['count'];
    	$PowerBB->_GET['count'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['count'],'intval');
       if (isset($PowerBB->_GET['sec']))
        {
        $PowerBB->_GET['sec'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['sec'],'intval');
        $PowerBB->_CONF['info_row']['portal_section_news'] = $PowerBB->_GET['sec'];
         $location = "&sec=".$PowerBB->_GET['sec']."";
        }
       if (empty($PowerBB->_CONF['info_row']['portal_section_news']))
        {
         $PowerBB->_CONF['info_row']['portal_section_news'] = '*';
        }
     if ($PowerBB->_CONF['info_row']['portal_section_news'] == '*')
        {
        $section = "review_subject<>1 AND sec_subject<>1";
        }
        else
        {
        $section = "section in (" .$PowerBB->_CONF['info_row']['portal_section_news']. ") AND review_subject<>1";
        }

        $News_Numr = $PowerBB->_CONF['info_row']['portal_news_num'];
        $LastNews_nm = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(1),id FROM " . $PowerBB->table['subject'] . " WHERE " .$section. " AND review_subject<>1 AND delete_topic<>1 LIMIT 1"));

        // LastNews topics in section
		$LastNewsArr                        =    array();
		$LastNewsArr['proc']                 =    array();
		$LastNewsArr['proc']['*']              =    array('method'=>'clean','param'=>'html');

		$LastNewsArr['where']                =    array();
		$LastNewsArr['where'][0]             =    array();
		$LastNewsArr['where'][0]['name']       =    $section ." AND delete_topic";
		$LastNewsArr['where'][0]['oper']       =    '=';
		$LastNewsArr['where'][0]['value']       =    0;

		$LastNewsArr['order']                =    array();
		$LastNewsArr['order']['field']          =    'id';
		$LastNewsArr['order']['type']          =    'DESC';


       // Clean data
       $LastNewsArr['proc']              =    array();
       $LastNewsArr['proc']['*']           =    array('method'=>'clean','param'=>'html');
       $LastNewsArr['proc']['native_write_time']    =    array('method'=>'date','store'=>'write_date','type'=>$PowerBB->_CONF['info_row']['timesystem']);
       $LastNewsArr['proc']['write_time']           =    array('method'=>'date','store'=>'reply_date','type'=>$PowerBB->_CONF['info_row']['timesystem']);

		$LastNewsArr['pager'] 				= 	array();
		$LastNewsArr['pager']['total']		= 	$LastNews_nm;
		$LastNewsArr['pager']['perpage'] 	= 	$News_Numr;
		$LastNewsArr['pager']['count'] 		= 	$PowerBB->_GET['count'];
        $LastNewsArr['pager']['location']     =     "index.php?page=portal".$location."";
		$LastNewsArr['pager']['var'] 		= 	'count';


       $PowerBB->_CONF['template']['while']['LastNews_subjectList'] = $PowerBB->core->GetList($LastNewsArr,'subject');
       if ($LastNews_nm  > $News_Numr)
		{
		 $PowerBB->template->assign('PagerLastNews',$PowerBB->pager->show());
		}

		//
		$StaticInfo = array();

		/**
		 * Get the age of forums and install date
		 */
		$StaticInfo['Age'] 			= 	$PowerBB->misc->GetForumAge(array('date'=>$PowerBB->_CONF['info_row']['create_date']));
		$StaticInfo['InstallDate']	=	$PowerBB->functions->_date($PowerBB->_CONF['info_row']['create_date']);

		/**
		 * Get the number of members , subjects , replies , active members and sections
		 */
		$SecArr 						= 	array();
		$SecArr['where'] 				= 	array();
		$SecArr['where'][0] 			= 	array();
		$SecArr['where'][0]['name'] 	= 	'parent';
		$SecArr['where'][0]['oper'] 	= 	'<>';
		$SecArr['where'][0]['value'] 	= 	'0';

		$StaticInfo['GetSubjectNumber'] = $PowerBB->_CONF['info_row']['subject_number'];
		$StaticInfo['GetReplyNumber'] = $PowerBB->_CONF['info_row']['reply_number'];
		$StaticInfo['GetMemberNumber']	= $PowerBB->_CONF['info_row']['member_number'];
		$StaticInfo['GetActiveMember']	= $PowerBB->member->GetActiveMemberNumber();
		$StaticInfo['GetSectionNumber']	= $PowerBB->core->GetNumber($SecArr,'section');

		/**
		 * Get the writer of oldest subject , the most subject of riplies and the newer subject
		 * should be in cache
		 */
		$OldestArr 						= 	array();
		$OldestArr['order'] 			= 	array();
		$OldestArr['order']['field'] 	= 	'id';
		$OldestArr['order']['type'] 	= 	'ASC';
		$OldestArr['limit'] 			= 	'1';

		$GetOldest = $PowerBB->core->GetInfo($OldestArr,'subject');
		$StaticInfo['OldestSubjectWriter'] = $GetOldest['writer'];

		$NewerArr 						= 	array();
		$NewerArr['order'] 				= 	array();
		$NewerArr['order']['field'] 	= 	'id';
		$NewerArr['order']['type'] 		= 	'DESC';
		$NewerArr['limit'] 				= 	'1';

		$GetNewer = $PowerBB->core->GetInfo($NewerArr,'subject');
		$StaticInfo['NewerSubjectWriter'] = $GetNewer['writer'];

		$MostVisitArr 						= 	array();
		$MostVisitArr['order'] 			= 	array();
		$MostVisitArr['order']['field'] 	= 	'visitor';
		$MostVisitArr['order']['type'] 	= 	'DESC';
		$MostVisitArr['limit'] 			= 	'1';

		$GetMostVisit = $PowerBB->core->GetInfo($MostVisitArr,'subject');
		$StaticInfo['MostSubjectWriter'] = $GetMostVisit['writer'];

		$PowerBB->functions->CleanVariable($StaticInfo,'html');

		$PowerBB->template->assign('StaticInfo',$StaticInfo);

//
		$LastPostsInfoArr 							= 	array();

		// Order data
		$LastPostsArr['order'] 				= 	array();
		$LastPostsInfoArr['order']['field'] 	= 	'write_time';
		$LastPostsInfoArr['order']['type'] 		= 	'DESC';

		// Ten rows only
		$LastPostsInfoArr['limit']				=	'6';

        $LastPostsInfoArr['where'][1] 			= 	array();
		$LastPostsInfoArr['where'][1]['con']		=	'AND';
		$LastPostsInfoArr['where'][1]['name'] 	= 	'review_subject<>1 AND sec_subject<>1 AND delete_topic';
		$LastPostsInfoArr['where'][1]['oper'] 	= 	'<>';
		$LastPostsInfoArr['where'][1]['value'] 	= 	'1';


		// Clean data
		$LastPostsInfoArr['proc'] 				= 	array();
		$LastPostsInfoArr['proc']['*'] 			= 	array('method'=>'clean','param'=>'html');
        $LastPostsInfoArr['proc']['native_write_time'] 	= 	array('method'=>'date','store'=>'write_date','type'=>$PowerBB->_CONF['info_row']['timesystem']);
		$LastPostsInfoArr['proc']['write_time'] 			= 	array('method'=>'date','store'=>'reply_date','type'=>$PowerBB->_CONF['info_row']['timesystem']);

        $PowerBB->_CONF['template']['while']['LastsPostsInfo'] = $PowerBB->core->GetList($LastPostsInfoArr,'subject');

        //

		//////////

		$GuestNumberArr 						= 	array();
		$GuestNumberArr['where'] 				= 	array();

		$GuestNumberArr['where'][0] 			= 	array();
		$GuestNumberArr['where'][0]['name'] 	= 	'username';
		$GuestNumberArr['where'][0]['oper'] 	= 	'=';
		$GuestNumberArr['where'][0]['value'] 	= 	'Guest';
		$GuestNumberArr['where'][1] 			= 	array();
		$GuestNumberArr['where'][1]['con'] 	    = 	'AND';
		$GuestNumberArr['where'][1]['name'] 	= 	'is_bot<>1 AND hide_browse<>1 AND user_id';
		$GuestNumberArr['where'][1]['oper'] 	= 	'<>';
		$GuestNumberArr['where'][1]['value'] 	= 	'';

		$GuestNumberArr['order'] 					= 	array();
		$GuestNumberArr['order']['field'] 		= 	'username';
		$GuestNumberArr['order']['type'] 			= 	'ASC';

		$PowerBB->_CONF['template']['GuestNumber'] = $PowerBB->core->GetNumber($GuestNumberArr,'online');

		//////////

		$MemberNumberArr 						= 	array();
		$MemberNumberArr['where'] 				= 	array();

		$MemberNumberArr['where'][0] 			= 	array();
		$MemberNumberArr['where'][0]['name'] 	= 	'username';
		$MemberNumberArr['where'][0]['oper'] 	= 	'<>';
		$MemberNumberArr['where'][0]['value'] 	= 	'Guest';
		$MemberNumberArr['where'][1] 			= 	array();
		$MemberNumberArr['where'][1]['con'] 	    = 	'AND';
		$MemberNumberArr['where'][1]['name'] 	= 	'hide_browse';
		$MemberNumberArr['where'][1]['oper'] 	= 	'<>';
		$MemberNumberArr['where'][1]['value'] 	= 	'1';

		$PowerBB->_CONF['template']['MemberNumber'] = $PowerBB->core->GetNumber($MemberNumberArr,'online');

		// The largest number of users And Guests ever online in one moment
		$MemAndGstNumber = $PowerBB->_CONF['template']['MemberNumber']+$PowerBB->_CONF['template']['GuestNumber'];
		if ( $MemAndGstNumber > $PowerBB->_CONF['info_row']['max_online'] )
		{
        $_date = $PowerBB->functions->_date($PowerBB->_CONF['now']);

		$update = $PowerBB->info->UpdateInfo(array('value'=>$MemAndGstNumber,'var_name'=>'max_online'));
		$update = $PowerBB->info->UpdateInfo(array('value'=>$_date,'var_name'=>'max_online_date'));

		}

		$PowerBB->template->assign('max_online_date',$PowerBB->_CONF['info_row']['max_online_date']);
		$PowerBB->template->assign('max_online',$PowerBB->_CONF['info_row']['max_online']);
		//////////

		$MemberNumberHideArr 						= 	array();
		$MemberNumberHideArr['where'] 				= 	array();

		$MemberNumberHideArr['where'][0] 			= 	array();
		$MemberNumberHideArr['where'][0]['name'] 	= 	'hide_browse';
		$MemberNumberHideArr['where'][0]['oper'] 	= 	'=';
		$MemberNumberHideArr['where'][0]['value'] 	= 	'1';

		$PowerBB->_CONF['template']['MemberNumberHide'] = $PowerBB->core->GetNumber($MemberNumberHideArr,'online');
		//////////

		$SpidersNumberArr 						= 	array();
		$SpidersNumberArr['where'] 				= 	array();

		$SpidersNumberArr['where'][0] 			= 	array();
		$SpidersNumberArr['where'][0]['name'] 	= 	'is_bot';
		$SpidersNumberArr['where'][0]['oper'] 	= 	'=';
		$SpidersNumberArr['where'][0]['value'] 	= 	'1';

		$PowerBB->_CONF['template']['SpidersNumber'] = $PowerBB->core->GetNumber($SpidersNumberArr,'online');
		$GroupArr 							= 	array();

		$GroupArr['where'] 					= 	array();
		$GroupArr['where'][0] 				= 	array();
		$GroupArr['where'][0]['name'] 		= 	'view_usernamestyle';
		$GroupArr['where'][0]['oper'] 		= 	'=';
		$GroupArr['where'][0]['value']		= 	1;

		$GroupArr['order'] 					= 	array();
		$GroupArr['order']['field'] 		= 	'group_order';
		$GroupArr['order']['type'] 			= 	'ASC';

		$GroupArr['proc']					=	array();
		$GroupArr['proc']['username_style']	=	array('method'=>'replace','search'=>'[username]','replace'=>'rows{title}','store'=>'h_title');


		$PowerBB->_CONF['template']['while']['GroupList'] = $PowerBB->core->GetList($GroupArr,'group');

		//////////
		$OnlineArr 						= 	array();
		$OnlineArr['order'] 			= 	array();
		$OnlineArr['order']['field'] 	= 	'user_id';
		$OnlineArr['order']['type'] 	= 	'DESC';


		$OnlineArr['proc'] 						= 	array();
		$OnlineArr['proc']['*'] 					= 	array('method'=>'clean','param'=>'html');
		$OnlineArr['proc']['logged'] 			= 	array('method'=>'time','store'=>'logged','type'=>$PowerBB->_CONF['info_row']['timesystem']);


		$OnlineArr['where'] = (!$PowerBB->_CONF['info_row']['show_onlineguest']
								or !$PowerBB->_CONF['rows']['group_info']['show_hidden']) ? array() : null;

		if (!$PowerBB->_CONF['info_row']['show_onlineguest'])
		{
			$OnlineArr['where'][0] 			= 	array();
			$OnlineArr['where'][0]['name'] 	= 	'username';
			$OnlineArr['where'][0]['oper'] 	= 	'<>';
			$OnlineArr['where'][0]['value'] = 	'Guest';
		}

		// This member can't see hidden member
		if (!$PowerBB->_CONF['group_info']['show_hidden'])
		{
			$OnlineArr['where'][1] 			= 	array();
			$OnlineArr['where'][1]['con'] 	= 	'AND';
			$OnlineArr['where'][1]['name'] 	= 	'hide_browse';
			$OnlineArr['where'][1]['oper'] 	= 	'<>';
			$OnlineArr['where'][1]['value'] = 	'1';
		}

		// Finally we get online list
		$PowerBB->_CONF['template']['while']['OnlineList'] = $PowerBB->core->GetList($OnlineArr,'online');

		//////////

		$SecArr 						= 	array();
		$SecArr['get_from']				=	'db';

		$SecArr['proc'] 				= 	array();
		$SecArr['proc']['*'] 			= 	array('method'=>'clean','param'=>'html');

		$SecArr['order']				=	array();
		$SecArr['order']['field']		=	'sort';
		$SecArr['order']['type']		=	'ASC';

		$SecArr['where']				=	array();
		$SecArr['where'][0]['name']		= 	'parent';
		$SecArr['where'][0]['oper']		= 	'=';
		$SecArr['where'][0]['value']	= 	'0';

         ///
		$SecArr 						= 	array();
		$SecArr['get_from']				=	'db';

		$SecArr['proc'] 				= 	array();
		$SecArr['proc']['*'] 			= 	array('method'=>'clean','param'=>'html');

		$SecArr['order']				=	array();
		$SecArr['order']['field']		=	'sort';
		$SecArr['order']['type']		=	'ASC';

		$SecArr['where']				=	array();
		$SecArr['where'][0]['name']		= 	'parent';
		$SecArr['where'][0]['oper']		= 	'=';
		$SecArr['where'][0]['value']	= 	'0';

		// Get main sections
		$catsy = $PowerBB->core->GetList($SecArr,'section');

		// We will use sections_list to store list of forums which will view in main page
		$PowerBB->_CONF['template']['foreach']['sections_list'] = array();

		// Loop to read the information of main sections
		foreach ($catsy as $caty)
		{
			if ($PowerBB->functions->section_group_permission($caty['id'],$PowerBB->_CONF['group_info']['id'],'view_section'))
			{
				$PowerBB->_CONF['template']['foreach']['sections_list'][$caty['id'] . '_m'] = $caty;
			}

		} // end foreach ($catsy)

		//////////
       eval($PowerBB->functions->get_fetch_hooks('portalHooks'));
             $PowerBB->template->assign('timer',$PowerBB->sys_functions->_time(time()));

         $PowerBB->template->display('portal');

     }


	    /**
	 * Get the Jump Sections List
	 */
	function _GetJumpSectionsList()
    {
		global $PowerBB;
       $PowerBB->functions->JumpForumsList();
		//////////
       $PowerBB->template->display('jump_forums_list');
   }

}

?>