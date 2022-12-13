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
/*			if (empty($PowerBB->_GET['top'])
				and empty($PowerBB->_GET['right'])
				and empty($PowerBB->_GET['left']))
			{
				$PowerBB->template->display('main');
			}

			elseif ($PowerBB->_GET['top'])
			{
				$this->_DisplayTopPage();
			}

			elseif ($PowerBB->_GET['right'])
			{
				$this->_DisplayMenuPage();
			}

			elseif ($PowerBB->_GET['left'])
			{*/
				$this->_DisplayBodyPage();
//			}
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

		$PowerBB->_CONF['template']['MemberNumber'] = $PowerBB->member->GetMemberNumber(array('get_from'	=>	'db'));

		$PowerBB->_CONF['template']['ActiveMember'] = $PowerBB->member->GetActiveMemberNumber();

		$SecArr 						= 	array();
		$SecArr['where'] 				= 	array();
		$SecArr['where'][0] 			= 	array();
		$SecArr['where'][0]['name'] 	= 	'parent';
		$SecArr['where'][0]['oper'] 	= 	'<>';
		$SecArr['where'][0]['value'] 	= 	'0';

		$PowerBB->_CONF['template']['ForumsNumber'] = $PowerBB->core->GetNumber($SecArr,'section');

		$PowerBB->_CONF['template']['SubjectNumber'] = $PowerBB->core->GetNumber(array('get_from'	=>	'db'),'subject');

		$PowerBB->_CONF['template']['ReplyNumber'] = $PowerBB->core->GetNumber(array('get_from'	=>	'db'),'reply');

		$day 	= 	date('j');
		$month 	= 	date('n');
		$year 	= 	date('Y');

		$from 	= 	mktime(0,0,0,$month,$day,$year);
		$to 	= 	mktime(23,59,59,$month,$day,$year);

		$TodayMemberArr 				= 	array();
		$TodayMemberArr['get_from'] 	= 	'db';
		$TodayMemberArr['where'] 		= 	array();

		$TodayMemberArr['where'][0] 			= 	array();
		$TodayMemberArr['where'][0]['name'] 	= 	'register_date';
		$TodayMemberArr['where'][0]['oper'] 	= 	'BETWEEN';
		$TodayMemberArr['where'][0]['value'] 	= 	$from . ' AND ' . $to;

		$PowerBB->_CONF['template']['TodayMemberNumber'] = $PowerBB->member->GetMemberNumber($TodayMemberArr);

		$TodaySubjectArr 				= 	array();
		$TodaySubjectArr['get_from'] 	= 	'db';
		$TodaySubjectArr['where'] 		= 	array();

		$TodaySubjectArr['where'][0] 			= 	array();
		$TodaySubjectArr['where'][0]['name'] 	= 	'native_write_time';
		$TodaySubjectArr['where'][0]['oper'] 	= 	'BETWEEN';
		$TodaySubjectArr['where'][0]['value'] 	= 	$from . ' AND ' . $to;

		$PowerBB->_CONF['template']['TodaySubjectNumber'] = $PowerBB->core->GetNumber($TodaySubjectArr,'subject');

		$TodayReplyArr 				= 	array();
		$TodayReplyArr['get_from'] 	= 	'db';
		$TodayReplyArr['where'] 	= 	array();

		$TodayReplyArr['where'][0] 				= 	array();
		$TodayReplyArr['where'][0]['name'] 		= 	'write_time';
		$TodayReplyArr['where'][0]['oper'] 		= 	'BETWEEN';
		$TodayReplyArr['where'][0]['value'] 	= 	$from . ' AND ' . $to;

		$PowerBB->_CONF['template']['TodayReplyNumber'] = $PowerBB->core->GetNumber($TodayReplyArr,'reply');

     //  Waiting Members Number

		$WaitingMemberArr 				= 	array();
		$WaitingMemberArr['get_from'] 	= 	'db';
		$WaitingMemberArr['where'] 		= 	array();

		$WaitingMemberArr['where'][0] 			= 	array();
		$WaitingMemberArr['where'][0]['name'] 	= 	'usergroup';
		$WaitingMemberArr['where'][0]['oper'] 	= 	'=';
		$WaitingMemberArr['where'][0]['value'] 	= 	'5';

		$PowerBB->_CONF['template']['MembersActiveList'] = $PowerBB->member->GetMemberNumber($WaitingMemberArr);

      // Forums moderators users Number

		$WaitingMemberArr 				= 	array();
		$WaitingMemberArr['get_from'] 	= 	'db';
		$WaitingMemberArr['where'] 		= 	array();

		$WaitingMemberArr['where'][0] 			= 	array();
		$WaitingMemberArr['where'][0]['name'] 	= 	'usergroup';
		$WaitingMemberArr['where'][0]['oper'] 	= 	'=';
		$WaitingMemberArr['where'][0]['value'] 	= 	'3';

		$PowerBB->_CONF['template']['ModeratorsNumber'] = $PowerBB->member->GetMemberNumber($WaitingMemberArr);


		$PowerBB->_CONF['template']['_CONF']['lang']['copyright'] = $PowerBB->functions->copyright();
		$PowerBB->_CONF['template']['_CONF']['lang']['copyright_archive'] = $PowerBB->functions->copyright();

		//------------------------------------
		$PowerBB->template->display('header');
		$PowerBB->template->display('main_body');
		$PowerBB->template->display('footer');
	}
}

?>
