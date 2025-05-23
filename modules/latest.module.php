<?php

$CALL_SYSTEM = array();
$CALL_SYSTEM['SUBJECT'] = true;
$CALL_SYSTEM['SECTION'] 	= 	true;

(!defined('IN_PowerBB')) ? die() : '';



define('CLASS_NAME','PowerBBLatestMOD');

include('common.php');
class PowerBBLatestMOD
{
	function run()
	{
		global $PowerBB;
 		if (!$PowerBB->_CONF['info_row']['active_subject_today'])
		{
			 $PowerBB->functions->ShowHeader();
             $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['You_can_not_use_this_feature']);
		 }

		if ($PowerBB->_GET['today'] == '1')
		{
    		$this->_GetJumpSectionsList();
			$this->_TodaySubject();
		}
		else
		{
			 $PowerBB->functions->ShowHeader();
             $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Sorry_url_not_true']);
		}


		$PowerBB->functions->GetFooter();
	}

	function _TodaySubject()
	{
		global $PowerBB;

		$PowerBB->functions->ShowHeader($PowerBB->_CONF['template']['_CONF']['lang']['subject_today']);


		$day 	= 	date('j');
		$month 	= 	date('n');
		$year 	= 	date('Y');

		$from 	= 	mktime(0,0,0,$month,$day,$year);
		$to 	= 	mktime(23,59,59,$month,$day,$year);
        $deys = ($PowerBB->_CONF['now'] - (30 * 86400));

		 /**
		 * Ok , are you ready to get subjects list ? :)
		 */
 		$PowerBB->_GET['count'] = (!isset($PowerBB->_GET['count'])) ? 0 : $PowerBB->_GET['count'];
 		$PowerBB->_GET['count'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['count'],'intval');
        $forum_not = $PowerBB->_CONF['info_row']['last_subject_writer_not_in'];

        $subject_today_nm = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(1),id FROM " . $PowerBB->table['subject'] . " WHERE native_write_time >= " . $deys . " AND section not in (" .$forum_not. ") AND review_subject<>1 AND delete_topic<>1 LIMIT 1"));

       $subject_today_nmbr  = $subject_today_nm;



		$SubjectArr = array();

		$SubjectArr['order'] 				= 	array();
		$SubjectArr['order']['field'] 	= 	'native_write_time';
		$SubjectArr['order']['type'] 		= 	'DESC';

		$SubjectArr['where'][1]           =    array();
		$SubjectArr['where'][1]['con']       =    'sec_section <> 1 AND';
		$SubjectArr['where'][1]['name']    =    'native_write_time >= ' . $deys . ' AND section not in (' .$forum_not. ') AND review_subject<>1 AND delete_topic';
		$SubjectArr['where'][1]['oper']    =    '<>';
		$SubjectArr['where'][1]['value']    =    '1';


		$SubjectArr['proc']                    =    array();
		// Ok Mr.** go to hell !
		$SubjectArr['proc']['*']                 =    array('method'=>'clean','param'=>'html');
		$SubjectArr['proc']['native_write_time']    =    array('method'=>'date','store'=>'write_date','type'=>$PowerBB->_CONF['info_row']['timesystem']);
		$SubjectArr['proc']['write_time']           =    array('method'=>'date','store'=>'reply_date','type'=>$PowerBB->_CONF['info_row']['timesystem']);
		// Pager setup


		$SubjectArr['pager']              =    array();
		$SubjectArr['pager']['total']       =    $subject_today_nm;
		$SubjectArr['pager']['perpage']    =    $PowerBB->_CONF['info_row']['perpage'];
		$SubjectArr['pager']['count']        =    $PowerBB->_GET['count'];
		$SubjectArr['pager']['location']    =    'index.php?page=latest&today=1';
		$SubjectArr['pager']['var']        =    'count';


       $PowerBB->_CONF['template']['while']['subject_list'] = $PowerBB->core->GetList($SubjectArr,'subject');
      if ($subject_today_nm > $PowerBB->_CONF['info_row']['perpage'])
       {
         $PowerBB->template->assign('pager',$PowerBB->pager->show());
       }
       $PowerBB->template->assign('subject_today_nm',$subject_today_nmbr);

       $PowerBB->template->display('today_subject');

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
