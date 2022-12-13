<?php
(!defined('IN_PowerBB')) ? die() : '';

define('CLASS_NAME','PowerBBStaticMOD');

include('common.php');
class PowerBBStaticMOD
{
	function run()
	{
		global $PowerBB;

 		if (!$PowerBB->_CONF['info_row']['active_static'])
		{
			header("Location: index.php");
			exit;
		}


		if ($PowerBB->_GET['index'])
		{
	    	$this->_GetJumpSectionsList();
			$this->_ShowStatic();
		}
		else
		{
			header("Location: index.php");
			exit;
		}

		$PowerBB->functions->GetFooter();
	}

	function _ShowStatic()
	{
		global $PowerBB;
       $PowerBB->template->assign('static_page','primary_tabon');

		$PowerBB->functions->ShowHeader($PowerBB->_CONF['template']['_CONF']['lang']['statics']);

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

		$SubjectNumber              = array();
		$SubjectNumber['get_from']  = 'db';
		$SubjectNumber['where']     = array('delete_topic',0);

		$StaticInfo['GetSubjectNumber'] = $PowerBB->core->GetNumber($SubjectNumber,'subject');

		$ReplyNumber              = array();
		$ReplyNumber['get_from']  = 'db';
		$ReplyNumber['where']     = array('delete_topic',0);

		$StaticInfo['GetReplyNumber'] = $PowerBB->core->GetNumber($ReplyNumber,'reply');

		// Get Member Number
        $arr                   = array();
		$arr['get_from']       = 'db';

		$mn = $PowerBB->core->GetNumber($arr,'member');

		$StaticInfo['GetMemberNumber']	= $mn;
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

		/**
		 * Get top ten list of member who have big posts
		 */
		$TopTenArr 						= 	array();

		// Order data
		$TopTenArr['order'] 			= 	array();
		$TopTenArr['order']['field'] 	= 	'posts';
		$TopTenArr['order']['type'] 	= 	'DESC';

		// Ten rows only
		$TopTenArr['limit']				=	'10';

		// Clean data
		$TopTenArr['proc'] 				= 	array();
		$TopTenArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');

		$PowerBB->_CONF['template']['while']['TopTenList'] = $PowerBB->core->GetList($TopTenArr,'member');

		/**
		 * Get top ten list of subjects which have big replies
		 */
		$TopSubjectArr 						= 	array();

		// Order data
		$TopSubjectArr['order'] 			= 	array();
		$TopSubjectArr['order']['field'] 	= 	'reply_number';
		$TopSubjectArr['order']['type'] 	= 	'DESC';

		// Ten rows only
		$TopSubjectArr['limit']					=	'10';

        $TopSubjectArr['where'][1] 			= 	array();
		$TopSubjectArr['where'][1]['con']		=	'AND';
		$TopSubjectArr['where'][1]['name'] 	= 	'review_subject<>1 AND sec_subject<>1 AND delete_topic';
		$TopSubjectArr['where'][1]['oper'] 	= 	'<>';
		$TopSubjectArr['where'][1]['value'] 	= 	'1';

		// Clean data
		$TopSubjectArr['proc'] 				= 	array();
		$TopSubjectArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');

		$PowerBB->_CONF['template']['while']['TopSubject'] = $PowerBB->core->GetList($TopSubjectArr,'subject');

		/**
		 * Get top ten list of subjects which have big visitors
		 */
		$TopSubjectVisitorArr 							= 	array();

		// Order data
		$TopSubjectVisitorArr['order'] 				= 	array();
		$TopSubjectVisitorArr['order']['field'] 	= 	'visitor';
		$TopSubjectVisitorArr['order']['type'] 		= 	'DESC';

		// Ten rows only
		$TopSubjectVisitorArr['limit']				=	'10';

        $TopSubjectVisitorArr['where'][1] 			= 	array();
		$TopSubjectVisitorArr['where'][1]['con']		=	'AND';
		$TopSubjectVisitorArr['where'][1]['name'] 	= 	'review_subject<>1 AND sec_subject<>1 AND delete_topic';
		$TopSubjectVisitorArr['where'][1]['oper'] 	= 	'<>';
		$TopSubjectVisitorArr['where'][1]['value'] 	= 	'1';

		// Clean data
		$TopSubjectVisitorArr['proc'] 				= 	array();
		$TopSubjectVisitorArr['proc']['*'] 			= 	array('method'=>'clean','param'=>'html');

		$PowerBB->_CONF['template']['while']['TopSubjectVisitor'] = $PowerBB->core->GetList($TopSubjectVisitorArr,'subject');

		$PowerBB->template->display('static');


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