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

		// تنظيف المتغيرات
		$PowerBB->_GET['count'] = (empty($PowerBB->_GET['count'])) ? 0 : $PowerBB->functions->CleanVariable($PowerBB->_GET['count'], 'intval');

		// حساب الوقت (آخر 30 يوم)
		$deys = ($PowerBB->_CONF['now'] - (30 * 86400));

		// الأقسام المستثناة
		$forum_not = (empty($PowerBB->_CONF['info_row']['last_subject_writer_not_in'])) ? '0' : $PowerBB->_CONF['info_row']['last_subject_writer_not_in'];

		// 1. استعلام العد السريع
		$subject_today_nm_query = $PowerBB->DB->sql_query("
			SELECT COUNT(*)
			FROM " . $PowerBB->table['subject'] . "
			WHERE section NOT IN (" . $forum_not . ")
			AND review_subject = 0
			AND delete_topic = 0
			AND write_time >= " . $deys
		);
		$subject_today_nm_row = $PowerBB->DB->sql_fetch_row($subject_today_nm_query);
		$subject_today_nm = $subject_today_nm_row;

		$LastSubjectArr = array();

		// SELECT fields
		$LastSubjectArr['select'] = '
			s.*,
			m.id AS writer_id,
			m.username_style_cache,
			sec.id AS section_id,
			sec.title AS section_title,
			lr.id AS last_replier_id,
			lr.username_style_cache AS last_replier_style';

		$LastSubjectArr['from'] = $PowerBB->table['subject'] . ' AS s';

		$LastSubjectArr['join'] = array(
			array('type' => 'left', 'from' => $PowerBB->table['member'] . ' AS m', 'where' => 'm.username = s.writer'),
			array('type' => 'left', 'from' => $PowerBB->table['section'] . ' AS sec', 'where' => 'sec.id = s.section'),
			array('type' => 'left', 'from' => $PowerBB->table['member'] . ' AS lr', 'where' => 'lr.username = s.last_replier')
		);

		// WHERE - تم تعديل التنسيق ليتوافق مع كلاس القاعدة والترقيم
		$LastSubjectArr['where'] = array();
		$LastSubjectArr['where'][0] = array(
			'con'   => 'AND',
			'name'  => 's.section NOT IN (' . $forum_not . ') AND s.review_subject = 0 AND s.delete_topic = 0 AND s.write_time',
			'oper'  => '>=',
			'value' => $deys
		);

		$LastSubjectArr['order'] = array(
			'field' => 's.write_time',
			'type'  => 'DESC'
		);

		$LastSubjectArr['pager'] = array(
			'total'    => $subject_today_nm,
			'perpage'  => $PowerBB->_CONF['info_row']['perpage'],
			'count'    => $PowerBB->_GET['count'],
			'location' => 'index.php?page=latest_reply&amp;today=1',
			'var'      => 'count'
		);

		$LastSubjectArr['proc'] = array(
			'*' => array('method'=>'clean','param'=>'html'),
			'native_write_time' => array('method'=>'date','store'=>'write_date','type'=>$PowerBB->_CONF['info_row']['timesystem']),
			'write_time' => array('method'=>'date','store'=>'reply_date','type'=>$PowerBB->_CONF['info_row']['timesystem'])
		);

		$PowerBB->_CONF['template']['while']['LastSubject'] = $PowerBB->subject->GetSubjectListAdvanced($LastSubjectArr);

		$PowerBB->template->assign('reply_today_nm', $subject_today_nm);

		if ($subject_today_nm > $PowerBB->_CONF['info_row']['perpage'])
		{
			$PowerBB->template->assign('pagerLastSubject', $PowerBB->pager->show());
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
