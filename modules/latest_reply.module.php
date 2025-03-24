<?php

$CALL_SYSTEM = array();
$CALL_SYSTEM['SUBJECT']     = true;
$CALL_SYSTEM['SECTION'] 	= 	true;
$CALL_SYSTEM['REPLY'] 		= 	true;

(!defined('IN_PowerBB')) ? die() : '';



define('CLASS_NAME','PowerBBLatestMOD');

include('common.php');
class PowerBBLatestMOD
{
	function run()
	{
		global $PowerBB;

 		if ($PowerBB->_CONF['info_row']['active_reply_today'] == '0')
		{
			 $PowerBB->functions->ShowHeader();
             $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['You_can_not_use_this_feature']);
        }

        $PowerBB->template->assign('latest_reply_page','primary_tabon');
		$this->_GetJumpSectionsList();

		if ($PowerBB->_GET['today'] == '1')
		{
			$this->_TodayReply();
		}
		else
		{
			 $PowerBB->functions->ShowHeader();
             $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Sorry_url_not_true']);
		}

		$PowerBB->functions->GetFooter();
	}

	function _TodayReply()
	{
		global $PowerBB;

		$PowerBB->functions->ShowHeader();

    	$PowerBB->_GET['count'] = (!isset($PowerBB->_GET['count'])) ? 0 : $PowerBB->_GET['count'];
    	$PowerBB->_GET['count'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['count'],'intval');

		$day 	= 	date('j');
		$month 	= 	date('n');
		$year 	= 	date('Y');

		$from 	= 	mktime(0,0,0,$month,$day,$year);
		$to 	= 	mktime(23,59,59,$month,$day,$year);

        $deys = ($PowerBB->_CONF['now'] - (30 * 86400));

        $forum_not = $PowerBB->_CONF['info_row']['last_subject_writer_not_in'];

        $subject_today_nm = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(1),id FROM " . $PowerBB->table['subject'] . " WHERE write_time >= " . $deys . " AND section not in (" .$forum_not. ") AND review_subject<>1 AND delete_topic<>1 LIMIT 1"));

        //$reply_today_nm = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(1),id FROM " . $PowerBB->table['reply'] . " WHERE write_time >= " . $deys . " AND section not in (" .$forum_not. ") AND delete_topic<>'1' AND review_reply<>'1' LIMIT 1"));

		$LastSubjectArr 							= 	array();

		// Order data
		$LastSubjectArr['order'] 				= 	array();
		$LastSubjectArr['order']['field'] 	= 	'write_time';
		$LastSubjectArr['order']['type'] 		= 	'DESC';
       // $LastSubjectArr['limit'] 		= 	'20';

		// Ten rows only
        $LastSubjectArr['where'][1] 			= 	array();
		$LastSubjectArr['where'][1]['con']		=	'AND';
		$LastSubjectArr['where'][1]['name'] 	= 	'write_time >= ' . $deys . ' AND section not in (' .$forum_not. ') AND review_subject<>1 AND delete_topic';
		$LastSubjectArr['where'][1]['oper'] 	= 	'<>';
		$LastSubjectArr['where'][1]['value'] 	= 	'1';

		// Clean data
		$LastSubjectArr['proc'] 				= 	array();
		$LastSubjectArr['proc']['*'] 			= 	array('method'=>'clean','param'=>'html');
		$LastSubjectArr['proc']['native_write_time'] 	= 	array('method'=>'date','store'=>'write_date','type'=>$PowerBB->_CONF['info_row']['timesystem']);
		$LastSubjectArr['proc']['write_time'] 			= 	array('method'=>'date','store'=>'reply_date','type'=>$PowerBB->_CONF['info_row']['timesystem']);


		$LastSubjectArr['pager'] 				= 	array();
		$LastSubjectArr['pager']['total']		= 	$subject_today_nm;
		$LastSubjectArr['pager']['perpage'] 	= 	$PowerBB->_CONF['info_row']['perpage'];
		$LastSubjectArr['pager']['count'] 		= 	$PowerBB->_GET['count'];
		$LastSubjectArr['pager']['location'] 	= 	'index.php?page=latest_reply&amp;today=1';
		$LastSubjectArr['pager']['var'] 		= 	'count';

         $PowerBB->_CONF['template']['while']['LastSubject'] = $PowerBB->subject->GetSubjectList($LastSubjectArr);

        $PowerBB->template->assign('reply_today_nm',$subject_today_nm);

       if ($subject_today_nm  > $PowerBB->_CONF['info_row']['perpage'])
		{
		 $PowerBB->template->assign('pagerLastSubject',$PowerBB->pager->show());
		}

		$PowerBB->template->display('today_reply');

	}

	    /**
	 * Get the Jump Sections List
	 */
	function _GetJumpSectionsList()
    {
		global $PowerBB;
       $PowerBB->functions->JumpForumsList();
   }
}

?>
