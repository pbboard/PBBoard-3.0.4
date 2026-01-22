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
        $PowerBB->functions->_GetSections_cache();
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
     $GetGuestTodayNumber = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(*) FROM " . $PowerBB->table['visitor'] . " "));
     $PowerBB->_CONF['template']['TodayNumber'] = sizeof($PowerBB->_CONF['template']['while']['TodayList']);
      $PowerBB->_CONF['template']['GuestTodayNumber'] = $GetGuestTodayNumber;
     $PowerBB->_CONF['template']['AllTodayNumber'] = $GetGuestTodayNumber+$PowerBB->_CONF['template']['TodayNumber'];

     $PowerBB->_CONF['template']['_CONF']['lang']['online_today']= str_replace(":","",$PowerBB->_CONF['template']['_CONF']['lang']['online_today']);
     }
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

      if ($PowerBB->_CONF['info_row']['active_static'] == 1)
       {
           $PowerBB->_CONF['template']['lm']['username_style_cache'] = $PowerBB->_CONF['info_row']['last_member'];
           $PowerBB->_CONF['template']['lm']['id'] = $PowerBB->_CONF['info_row']['last_member_id'];
	       $PowerBB->template->assign('mn',$PowerBB->functions->with_comma($PowerBB->_CONF['info_row']['member_number']));
	       $PowerBB->template->assign('sn',$PowerBB->functions->with_comma($PowerBB->_CONF['info_row']['subject_number']));
	       $PowerBB->template->assign('rn',$PowerBB->functions->with_comma($PowerBB->_CONF['info_row']['reply_number']));
      }
		 // Get Statistics
		if ($PowerBB->_CONF['info_row']['activate_last_static_list'] == 1) {
		    $limit = (int)$PowerBB->_CONF['info_row']['last_static_num'];

		    $sql_union = "
		        (SELECT id, username AS f1, register_date AS f2, username_style_cache AS f3, avater_path AS f4, 'new_reg' AS type FROM " . $PowerBB->table['member'] . " ORDER BY id DESC LIMIT $limit)
		        UNION ALL
		        (SELECT id, username AS f1, posts AS f2, username_style_cache AS f3, avater_path AS f4, 'top_posts' AS type FROM " . $PowerBB->table['member'] . " WHERE posts != 0 ORDER BY posts DESC LIMIT $limit)
		        UNION ALL
		        (SELECT id, username AS f1, reputation AS f2, username_style_cache AS f3, avater_path AS f4, 'top_rep' AS type FROM " . $PowerBB->table['member'] . " WHERE reputation != 0 ORDER BY reputation DESC LIMIT $limit)
		        UNION ALL
		        (SELECT id, username AS f1, invite_num AS f2, username_style_cache AS f3, avater_path AS f4, 'top_inv' AS type FROM " . $PowerBB->table['member'] . " WHERE invite_num != 0 ORDER BY invite_num DESC LIMIT $limit)
		        UNION ALL
		        (SELECT id, title AS f1, visitor AS f2, '' AS f3, '' AS f4, 'top_vis' AS type FROM " . $PowerBB->table['subject'] . " WHERE review_subject<>1 AND delete_topic<>1 AND visitor <> 0 ORDER BY visitor DESC LIMIT $limit)
		        UNION ALL
		        (SELECT id, title AS f1, reply_num AS f2, '' AS f3, '' AS f4, 'top_sec' AS type FROM " . $PowerBB->table['section'] . " WHERE sec_section<>1 AND reply_num <> 0 ORDER BY reply_num DESC LIMIT $limit)
		    ";

		    $result = $PowerBB->DB->sql_query($sql_union);

		    $expected = ['latest_registered', 'TopMemberList', 'TopMemberReputation', 'TopMemberInvite', 'TopSubjectVisitor', 'TopSectionsList'];
		    foreach($expected as $list) { $PowerBB->_CONF['template']['while'][$list] = []; }

		    while ($row = $PowerBB->DB->sql_fetch_array($result)) {
		        $type = $row['type'];
		        $clean_title = $PowerBB->functions->CleanVariable($row['f1'], 'html');
		        $f2_formatted = $PowerBB->functions->with_comma($row['f2']);

		        if ($type == 'new_reg') $PowerBB->_CONF['template']['while']['latest_registered'][] = ['id' => $row['id'], 'username' => $clean_title, 'register_date' => $PowerBB->functions->time_ago($row['f2']), 'user_style' => $row['f3'], 'avatar' => $row['f4']];
		        if ($type == 'top_posts') $PowerBB->_CONF['template']['while']['TopMemberList'][] = ['id' => $row['id'], 'username' => $clean_title, 'posts' => $f2_formatted, 'user_style' => $row['f3'], 'avatar' => $row['f4']];
		        if ($type == 'top_rep') $PowerBB->_CONF['template']['while']['TopMemberReputation'][] = ['id' => $row['id'], 'username' => $clean_title, 'reputation' => $f2_formatted, 'user_style' => $row['f3'], 'avatar' => $row['f4']];
		        if ($type == 'top_inv') $PowerBB->_CONF['template']['while']['TopMemberInvite'][] = ['id' => $row['id'], 'username' => $clean_title, 'invite_num' => $f2_formatted, 'user_style' => $row['f3'], 'avatar' => $row['f4']];
		        if ($type == 'top_vis') $PowerBB->_CONF['template']['while']['TopSubjectVisitor'][] = ['id' => $row['id'], 'title' => $clean_title, 'visitor' => $f2_formatted];
		        if ($type == 'top_sec') $PowerBB->_CONF['template']['while']['TopSectionsList'][] = ['id' => $row['id'], 'title' => $clean_title, 'reply_num' => $f2_formatted];
		    }

		    $PowerBB->template->assign('default_avatar', $PowerBB->_CONF['info_row']['default_avatar']);
		}

    }

	function _CallTemplate()
	{
		global $PowerBB;
		$PowerBB->template->display('main');
	}
}

?>
