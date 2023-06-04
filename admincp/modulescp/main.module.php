<?php
(!defined('IN_PowerBB')) ? die() : '';
define('IN_ADMIN',true);


define('CLASS_NAME','PowerBBMainMOD');

include('../common.php');
class PowerBBMainMOD
{
	function run()
	{
		global $PowerBB;

		if ($PowerBB->_CONF['member_permission'])
		{
		$this->_DisplayBodyPage();
		}
	}

	function _DisplayTopPage()
	{
		global $PowerBB;

		$PowerBB->template->display('header');
		$PowerBB->template->display('top');
		$PowerBB->template->display('footer');
	}

	function _DisplayMenuPage()
	{
		global $PowerBB;

		$PowerBB->template->display('header');
		$PowerBB->template->display('menu');
		$PowerBB->template->display('footer');
	}


	//------------------------------------------------------------


	function _DisplayBodyPage()
	{
		global $PowerBB;


		$day 	= 	date('j');
		$month 	= 	date('n');
		$year 	= 	date('Y');

		$from 	= 	mktime(0,0,0,$month,$day,$year);
		$to 	= 	mktime(23,59,59,$month,$day,$year);

		$SecArr 						= 	array();
		$SecArr['where'] 				= 	array();
		$SecArr['where'][0] 			= 	array();
		$SecArr['where'][0]['name'] 	= 	'parent';
		$SecArr['where'][0]['oper'] 	= 	'<>';
		$SecArr['where'][0]['value'] 	= 	'0';

        $PowerBB->_CONF['template']['MemberNumber']       = $PowerBB->functions->with_comma($PowerBB->_CONF['info_row']['member_number']);
		$PowerBB->_CONF['template']['SubjectNumber']      = $PowerBB->functions->with_comma($PowerBB->_CONF['info_row']['subject_number']);
		$PowerBB->_CONF['template']['ReplyNumber']        = $PowerBB->functions->with_comma($PowerBB->_CONF['info_row']['reply_number']);

        $PowerBB->_CONF['template']['ActiveMember']       = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(1),id FROM " . $PowerBB->table['member'] . " WHERE posts > 0 LIMIT 1"));
		$PowerBB->_CONF['template']['ForumsNumber']       = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(1),id FROM " . $PowerBB->table['section'] . " WHERE parent <> 0 LIMIT 1"));
        $PowerBB->_CONF['template']['TodayMemberNumber']  = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(1),id FROM " . $PowerBB->table['member'] . " WHERE register_date BETWEEN ".$from ." AND " . $to ." LIMIT 1"));
        $PowerBB->_CONF['template']['TodaySubjectNumber'] = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(1),id FROM " . $PowerBB->table['subject'] . " WHERE native_write_time BETWEEN ".$from ." AND " . $to ." LIMIT 1"));
        $PowerBB->_CONF['template']['TodayReplyNumber']   = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(1),id FROM " . $PowerBB->table['reply'] . " WHERE write_time BETWEEN ".$from ." AND " . $to ." LIMIT 1"));
        $PowerBB->_CONF['template']['MembersActiveList']  = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(1),id FROM " . $PowerBB->table['member'] . " WHERE usergroup = 5 LIMIT 1"));
        $PowerBB->_CONF['template']['ModeratorsNumber']   = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(1),id FROM " . $PowerBB->table['member'] . " WHERE usergroup = 3 LIMIT 1"));


		$PowerBB->_CONF['template']['_CONF']['lang']['copyright'] = $PowerBB->functions->copyright();
		$PowerBB->_CONF['template']['_CONF']['lang']['copyright_archive'] = $PowerBB->functions->copyright();

		//------------------------------------
		$PowerBB->template->display('header');
		$PowerBB->template->display('main_body');
		$PowerBB->template->display('footer');
	}
}

?>
