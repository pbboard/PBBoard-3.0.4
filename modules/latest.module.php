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

        $subject_today_nm = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(*) FROM " . $PowerBB->table['subject'] . " WHERE delete_topic=0 AND review_subject=0 AND native_write_time >= " . $deys . " AND section NOT IN (" .$forum_not. ")"));

       $subject_today_nmbr  = $subject_today_nm;

		// SELECT fields
		$SubjectArr['select'] = '
		    s.*,
		    m.id AS writer_id,
		    m.username_style_cache,
		    sec.id AS section_id,
		    sec.title AS section_title,
		    lr.id AS last_replier_id,
		    lr.username_style_cache AS last_replier_style';

		// FROM
		$SubjectArr['from'] = $PowerBB->table['subject'] . ' AS s';

		// JOINs
		$SubjectArr['join'] = array(
		    array(
		        'type'  => 'left',
		        'from'  => $PowerBB->table['member'] . ' AS m',
		        'where' => 'm.username = s.writer'
		    ),
		    array(
		        'type'  => 'left',
		        'from'  => $PowerBB->table['section'] . ' AS sec',
		        'where' => 'sec.id = s.section'
		    ),
		    array(
		        'type'  => 'left',
		        'from'  => $PowerBB->table['member'] . ' AS lr',
		        'where' => 'lr.username = s.last_replier'
		    )
		);

		// WHERE conditions
		$SubjectArr['where'] = array();
		$SubjectArr['where'][0] = array(
		    'con'   => 'AND',
		    'name'  => 's.native_write_time >= ' . $deys .
		               ' AND s.section NOT IN (' . $forum_not . ')' .
		               ' AND s.review_subject<>1 AND s.delete_topic',
		    'oper'  => '<>',
		    'value' => 1
		);

		// ORDER
		$SubjectArr['order'] = array(
		    'field' => 's.native_write_time',
		    'type'  => 'DESC'
		);

		// PAGER
		$SubjectArr['pager'] = array(
		    'total'    => $subject_today_nm,
		    'perpage'  => $PowerBB->_CONF['info_row']['perpage'],
		    'count'    => $PowerBB->_GET['count'],
		    'location' => 'index.php?page=latest&amp;today=1',
		    'var'      => 'count'
		);

		$SubjectArr['proc'] = array(
		    '*' => array('method'=>'clean','param'=>'html'),
		    'native_write_time' => array('method'=>'date','store'=>'write_date','type'=>$PowerBB->_CONF['info_row']['timesystem']),
		    'write_time' => array('method'=>'date','store'=>'reply_date','type'=>$PowerBB->_CONF['info_row']['timesystem'])
		);

		$PowerBB->_CONF['template']['while']['subject_list'] = $PowerBB->subject->GetSubjectListAdvanced($SubjectArr);
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
