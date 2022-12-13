<?php
(!defined('IN_PowerBB')) ? die() : '';

define('CLASS_NAME','PowerBBCoreMOD');
include('common.php');
class PowerBBCoreMOD
{
	function run()
	{
		// Who can live without $PowerBB ? ;)
		global $PowerBB;

       $PowerBB->template->assign('main_page','primary_tabon');
		/**
		 * Show header
		 */
		$PowerBB->functions->ShowHeader();

		/**
		 * Firstly we get sections list
		 */
		$this->_GetSections();

		/**
		 * Get who are online
		 */
		$this->_GetOnline();

		/**
		 * Now we get 'Who visit site today'
		 */
		$this->_GetToday();

		/**
		 * Show main template
		 */
		$this->_GetFastStatic();

		/**
		 * Show main template
		 */
		$this->_CallTemplate();

		/**
		 * Show footer
		 */
		$PowerBB->functions->GetFooter();
	}

	/**
	 * Get sections list from cache and show it.
	 */
	function _GetSections()
	{
		global $PowerBB;
		$PowerBB->functions->_GetSections();
	}

	function _GetOnline()
	{
		global $PowerBB;

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
		if ($PowerBB->_CONF['template']['while']['GroupList'])
		{
		 $PowerBB->template->assign('view_usernamestyle','1');
        }
        else
		{
		 $PowerBB->template->assign('view_usernamestyle','0');
        }
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

		//3.0.1
		// get All online number
       	$PowerBB->_CONF['template']['all_online_number'] = $PowerBB->_CONF['template']['GuestNumber']+$PowerBB->_CONF['template']['MemberNumber']+$PowerBB->_CONF['template']['MemberNumberHide']+$PowerBB->_CONF['template']['SpidersNumber'];

		if ($PowerBB->_CONF['info_row']['show_online_list_today'] and $PowerBB->_CONF['info_row']['today_number_cache']< $PowerBB->_CONF['template']['GuestNumber'])
		{
		 $Update_today_number_cache = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_CONF['template']['GuestNumber'],'var_name'=>'today_number_cache'));
		}
	}

  function _GetToday()
    {
       global $PowerBB;

       //////////
     if ($PowerBB->_CONF['info_row']['show_online_list_today'] == 1)
     {

		$OldestVisitorArr 						= 	array();
		$OldestVisitorArr['order'] 			= 	array();
		$OldestVisitorArr['order']['field'] 	= 	'id';
		$OldestVisitorArr['order']['type'] 	= 	'ASC';
		$OldestVisitorArr['limit'] 			= 	'1';

		$GetOldestVisitor = $PowerBB->core->GetInfo($OldestVisitorArr,'visitor');

		if ($GetOldestVisitor)
		{
         if ($GetOldestVisitor['lang_id'] !='302')
		 {
			$Update_today_number_cache = $PowerBB->info->UpdateInfo(array('value'=>'1','var_name'=>'today_number_cache'));
			$truncate_visitor = $PowerBB->DB->sql_query("TRUNCATE " . $PowerBB->table['visitor'] );
		 }
		}

       $TodayArr                    =    array();
       $TodayArr['where']              =    array();
       $TodayArr['where'][0]           =    array();

       $TodayArr['where'][0]['name']    =    'username';
       $TodayArr['where'][0]['oper']    =    '!=';
       $TodayArr['where'][0]['value']    =    'Guest';

       if (!$PowerBB->_CONF['group_info']['show_hidden'])
       {
          $TodayArr['where'][1]          =    array();
          $TodayArr['where'][1]['con']    =    'AND';
          $TodayArr['where'][1]['name']    =    'hide_browse';
          $TodayArr['where'][1]['oper']    =    '<>';
          $TodayArr['where'][1]['value']    = '1';
       }

       $TodayArr['order']             =    array();
       $TodayArr['order']['field']       =    'user_id';
       $TodayArr['order']['type']       =    'DESC';


       $PowerBB->_CONF['template']['while']['TodayList'] = $PowerBB->core->GetList($TodayArr,'today');

       //////////
     $GetGuestTodayNumber = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT ID FROM " . $PowerBB->table['visitor'] . ""));
     $PowerBB->_CONF['template']['TodayNumber'] = sizeof($PowerBB->_CONF['template']['while']['TodayList']);
      $PowerBB->_CONF['template']['GuestTodayNumber'] = $GetGuestTodayNumber;
     $PowerBB->_CONF['template']['AllTodayNumber'] = $GetGuestTodayNumber+$PowerBB->_CONF['template']['TodayNumber'];

     $PowerBB->_CONF['template']['_CONF']['lang']['online_today']= str_replace(":","",$PowerBB->_CONF['template']['_CONF']['lang']['online_today']);
     }
       //////////
    }

   function _GetFastStatic()
    {
       global $PowerBB;
     if ($PowerBB->_CONF['info_row']['activate_special_bar'] == 1)
     {
     // special topics
       $SpecialArr                        =    array();
       $SpecialArr['proc']                 =    array();
       $SpecialArr['proc']['*']              =    array('method'=>'clean','param'=>'html');

       $SpecialArr['where']                =    array();
       $SpecialArr['where'][0]             =    array();
       $SpecialArr['where'][0]['name']       =    'special';
       $SpecialArr['where'][0]['oper']       =    '=';
       $SpecialArr['where'][0]['value']       =    '1';

       $SpecialArr['order']                =    array();
       $SpecialArr['order']['field']          =    'id';
       $SpecialArr['order']['type']          =    'DESC';

       // Clean data
       $SpecialArr['proc']              =    array();
       $SpecialArr['proc']['*']           =    array('method'=>'clean','param'=>'html');
       $SpecialArr['proc']['native_write_time']    =    array('method'=>'date','store'=>'write_date','type'=>$PowerBB->_CONF['info_row']['timesystem']);
       $SpecialArr['proc']['write_time']           =    array('method'=>'date','store'=>'reply_date','type'=>$PowerBB->_CONF['info_row']['timesystem']);


       $PowerBB->_CONF['template']['while']['Special_subjectList'] = $PowerBB->core->GetList($SpecialArr,'subject');

          if ($PowerBB->_CONF['template']['while']['Special_subjectList'] != false)
          {
             $PowerBB->template->assign('Special_Show',true);

          }
          else
          {
             $PowerBB->template->assign('Special_Show',false);
          }

      }
       ////
      if ($PowerBB->_CONF['info_row']['active_static'] == 1)
       {
	       $lastmember = array();
	       $lastmember['order']        = array();
	       $lastmember['order']['field'] = 'id';
	       $lastmember['order']['type']  = 'DESC';
	       $lastmember['limit']        = '1';

	       $lm = $PowerBB->core->GetInfo($lastmember,'member');
           if($lm['username'] != $PowerBB->_CONF['info_row']['last_member'])
           {
           	$PowerBB->info->UpdateInfo(array('var_name'=>'last_member','value'=>$lm['username']));
           	$PowerBB->info->UpdateInfo(array('var_name'=>'last_member_id','value'=>$lm['id']));
           }

	       $PowerBB->template->assign('lm',$lm);

	       $arr                = array();
	       $arr['get_from']      = 'db';
	       $mn = $PowerBB->core->GetNumber($arr,'member');
           if($mn != $PowerBB->_CONF['info_row']['member_number'])
           {
           	$PowerBB->info->UpdateInfo(array('var_name'=>'member_number','value'=>$mn));
           }
	       $PowerBB->template->assign('mn',$PowerBB->functions->with_comma($mn));

	       $arrr            = array();
	       $arrr['get_from']  = 'db';
	       $arrr['where']    = array('delete_topic',0);

	       $sn = $PowerBB->core->GetNumber($arrr,'subject');
           if($sn != $PowerBB->_CONF['info_row']['subject_number'])
           {
           	$PowerBB->info->UpdateInfo(array('var_name'=>'subject_number','value'=>$sn));
           }
	       $PowerBB->template->assign('sn',$PowerBB->functions->with_comma($sn));

	       $arrrr            = array();
	       $arrrr['get_from']  = 'db';
	       $arrrr['where']    = array('delete_topic',0);

	       $rn = $PowerBB->core->GetNumber($arrrr,'reply');
           if($rn != $PowerBB->_CONF['info_row']['reply_number'])
           {
           	$PowerBB->info->UpdateInfo(array('var_name'=>'reply_number','value'=>$rn));
           }
	       $PowerBB->template->assign('rn',$PowerBB->functions->with_comma($rn));

      }
        ////////////// Get Statistics

     if ($PowerBB->_CONF['info_row']['activate_last_static_list'] == 1)
       {

        $limit = $PowerBB->_CONF['info_row']['last_static_num'];
        $limitLastPost = $PowerBB->_CONF['info_row']['last_posts_static_num'];
       // Latest registered Member list
       $StaticInfo = array();
       $StaticInfo['order']        = array();
       $StaticInfo['order']['field'] = 'id';
       $StaticInfo['order']['type']  = 'DESC';
       $StaticInfo['limit']        = $limit;


       $StaticInfo['proc']                    =    array();
       // Ok Mr.** go to hell !
       $StaticInfo['proc']['*']                 =    array('method'=>'clean','param'=>'html');
       $StaticInfo['proc']['register_date']           =    array('method'=>'time_ago','store'=>'register_date','type'=>$PowerBB->_CONF['info_row']['timesystem']);

       $PowerBB->_CONF['template']['while']['latest_registered'] = $PowerBB->core->GetList($StaticInfo,'member');

       /**
        * Get top ten list of subjects which have big visitors
        */


       $TopSubjectVisitorArr                        =    array();
       // Order data
       $TopSubjectVisitorArr['order']              =    array();
       $TopSubjectVisitorArr['order']['field']    =    'visitor';
       $TopSubjectVisitorArr['order']['type']        =    'DESC';

       // Ten rows only
       $TopSubjectVisitorArr['limit']             =    $limit;

       $TopSubjectVisitorArr['where'][0]           =    array();
       $TopSubjectVisitorArr['where'][0]['name']    =    'review_subject<>1 AND sec_subject<>1 AND delete_topic';

       $TopSubjectVisitorArr['where'][0]['oper']    =    '<>';
       $TopSubjectVisitorArr['where'][0]['value']    =    '1';

       $TopSubjectVisitorArr['where'][1]['con']    =    ' AND';
       $TopSubjectVisitorArr['where'][1]['name']    =    ' visitor';
       $TopSubjectVisitorArr['where'][1]['oper']    =    '!=';
       $TopSubjectVisitorArr['where'][1]['value']    =    '0';

       // Clean data
       $TopSubjectVisitorArr['proc']              =    array();
       $TopSubjectVisitorArr['proc']['*']           =    array('method'=>'clean','param'=>'html');

       $PowerBB->_CONF['template']['while']['TopSubjectVisitor'] = $PowerBB->core->GetList($TopSubjectVisitorArr,'subject');
       $PowerBB->template->assign('TopSubjectVisitorNum',sizeof($PowerBB->_CONF['template']['while']['TopSubjectVisitor']));

       /**
        * Get top ten list of member who have big posts
        */
       $TopMemberList                    =    array();

       // Order data
       $TopMemberList['order']           =    array();
       $TopMemberList['order']['field']    =    'posts';
       $TopMemberList['order']['type']    =    'DESC';

       // Ten rows only
       $TopMemberList['limit']             =    $limit;

       //remove who thier posts is 0
       $TopMemberList['where'][0]['name']       =    'posts';
       $TopMemberList['where'][0]['oper']       =    '!=';
       $TopMemberList['where'][0]['value']       =    '0';

       // Clean data
       $TopMemberList['proc']              =    array();
       $TopMemberList['proc']['*']        =    array('method'=>'clean','param'=>'html');

       $PowerBB->_CONF['template']['while']['TopMemberList'] = $PowerBB->core->GetList($TopMemberList,'member');

       $PowerBB->template->assign('TopMemberListNum',sizeof($PowerBB->_CONF['template']['while']['TopMemberList']));


       /**
        * Get top ten list of sections who have big posts
        */

       $TopSectionsArr                    =    array();
       // Order data
       $TopSectionsArr['order']           =    array();
       $TopSectionsArr['order']['field']    =    'reply_num';
       $TopSectionsArr['order']['type']    =    'DESC';

       // Ten rows only
       $TopSectionsArr['limit']             =    $limit;

       $TopSectionsArr['where'][1]           =    array();
       $TopSectionsArr['where'][1]['con']       =    'AND';
       $TopSectionsArr['where'][1]['name']    =    'sec_section<>1 AND hide_subject <> 1 AND reply_num';
       $TopSectionsArr['where'][1]['oper']    =    '!=';
       $TopSectionsArr['where'][1]['value']    =    '0';
       // Clean data
       $TopSectionsArr['proc']              =    array();
       $TopSectionsArr['proc']['*']        =    array('method'=>'clean','param'=>'html');

       $PowerBB->_CONF['template']['while']['TopSectionsList'] = $PowerBB->core->GetList($TopSectionsArr,'section');
       $PowerBB->template->assign('TopSectionListNum',sizeof($PowerBB->_CONF['template']['while']['TopSectionsList']));


        /**
        * Get top member invite
        */
       $MemberInviteArr                        =    array();

       // Order data
       $MemberInviteArr['order']              =    array();
       $MemberInviteArr['order']['field']     =    'invite_num';
       $MemberInviteArr['order']['type']        =    'DESC';

       $MemberInviteArr['limit']             =    $limit;
       $MemberInviteArr['where'][0]['name']          =    'invite_num';
       $MemberInviteArr['where'][0]['oper']          =    '!=';
       $MemberInviteArr['where'][0]['value']          =    '0';
       // Clean data
       $MemberInviteArr['proc']              =    array();
       $MemberInviteArr['proc']['*']           =    array('method'=>'clean','param'=>'html');

       $PowerBB->_CONF['template']['while']['TopMemberInvite'] = $PowerBB->core->GetList($MemberInviteArr,'member');
       $PowerBB->template->assign('TopMemberInviteNum',sizeof($PowerBB->_CONF['template']['while']['TopMemberInvite']));

       ///////////////

        /**
        * Get top member Reputation
        */
       $MemberReputationArr                        =    array();

       // Order data
       $MemberReputationArr['order']              =    array();
       $MemberReputationArr['order']['field']     =    'reputation';
       $MemberReputationArr['order']['type']        =    'DESC';

       $MemberReputationArr['limit']             =    $limit;

       $MemberReputationArr['where'][0]['name']       =    'reputation';
       $MemberReputationArr['where'][0]['oper']       =    '!=';
       $MemberReputationArr['where'][0]['value']       =    '0';

       // Clean data
       $MemberReputationArr['proc']              =    array();
       $MemberReputationArr['proc']['*']           =    array('method'=>'clean','param'=>'html');

       $PowerBB->_CONF['template']['while']['TopMemberReputation'] = $PowerBB->core->GetList($MemberReputationArr,'member');
       $PowerBB->template->assign('MemberReputationNum',sizeof($PowerBB->_CONF['template']['while']['TopMemberReputation']));

     }

    }

	function _CallTemplate()
	{
		global $PowerBB;
		$PowerBB->template->display('main');
	}
}

?>
