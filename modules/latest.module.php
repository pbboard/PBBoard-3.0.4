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
			header("Location: index.php");
			exit;
		 }

		if ($PowerBB->_GET['today'])
		{
    		$this->_GetJumpSectionsList();
			$this->_TodaySubject();
		}
		else
		{
			header("Location: index.php");
			exit;
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

		 /**
		 * Ok , are you ready to get subjects list ? :)
		 */
 		$PowerBB->_GET['count'] = (!isset($PowerBB->_GET['count'])) ? 0 : $PowerBB->_GET['count'];
 		$PowerBB->_GET['count'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['count'],'intval');
        $forum_not = $PowerBB->_CONF['info_row']['last_subject_writer_not_in'];

        $subject_today_nm = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['subject'] . " WHERE native_write_time BETWEEN " . $from . " AND " . $to . " AND section not in (" .$forum_not. ") AND review_subject<>1 AND delete_topic<>1 "));

       $subject_today_nmbr  = $subject_today_nm;

       $SubjectArr                    =    array();
       $SubjectArr['get_from']           =    'db';

       $SubjectArr = array();
       $SubjectArr['where']              =    array();
       $SubjectArr['where'][0]           =    array();
       $SubjectArr['where'][0]['name']    =    'sec_section';
       $SubjectArr['where'][0]['oper']    =    '<>';
       $SubjectArr['where'][0]['value']    =    1;
       $SubjectArr['where'][0]           =    array();
       //$SubjectArr['where'][0]['con']       =    'AND';
       $SubjectArr['where'][0]['name']    =    'native_write_time';
       $SubjectArr['where'][0]['oper']    =    'BETWEEN';
       $SubjectArr['where'][0]['value']    =    $from . ' AND ' . $to;

       $SubjectArr['where'][1]           =    array();
       $SubjectArr['where'][1]['con']       =    'AND';
       $SubjectArr['where'][1]['name']    =    'section not in (' .$forum_not. ') AND review_subject<>1 AND delete_topic';
       $SubjectArr['where'][1]['oper']    =    '<>';
       $SubjectArr['where'][1]['value']    =    '1';
       $SubjectArr['order'] = array();
       $SubjectArr['order']['field']        =    'id';
       $SubjectArr['order']['type']        =    'DESC';


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
