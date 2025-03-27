<?php

(!defined('IN_PowerBB')) ? die() : '';

$CALL_SYSTEM			=	array();
$CALL_SYSTEM['PM'] 		= 	true;

define('JAVASCRIPT_PowerCode',true);



define('CLASS_NAME','PowerBBPrivateMassegeListMOD');

include('common.php');
class PowerBBPrivateMassegeListMOD
{
	function run()
	{
		global $PowerBB;

       $this->_GetJumpSectionsList();

		if (!$PowerBB->_CONF['info_row']['pm_feature'])
		{
            $PowerBB->functions->ShowHeader();
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['no_pm']);
		}

		/** Can't use the private massege system **/
		if (!$PowerBB->_CONF['rows']['group_info']['use_pm'])
		{
        	$PowerBB->functions->ShowHeader();
		     /** Visitor can't use the private massege system **/
			if (!$PowerBB->_CONF['member_permission'])
			{
				  $PowerBB->template->display('login');
	              $PowerBB->functions->error_stop();
			 }
		    else
            {
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Cant_use_pm']);
	        }
		}

		/** **/

		/** **/

		/** Get the list of masseges **/
		if ($PowerBB->_GET['list'])
		{
			$this->_ShowList();
		}
		else
		{
			 $PowerBB->functions->ShowHeader();
             $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Sorry_url_not_true']);
             $PowerBB->functions->GetFooter();
		}

		$PowerBB->functions->GetFooter();
	}

	/**
	 * Get the list of masseges
	 */
	function _ShowList()
	{
		global $PowerBB;

		$PowerBB->functions->ShowHeader($PowerBB->_CONF['template']['_CONF']['lang']['Private_Messages']);

		if (empty($PowerBB->_GET['folder']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}


		if ($PowerBB->_GET['folder'] == 'inbox'
		or $PowerBB->_GET['folder'] == 'sent')
		{
			//continue;
		}
		else
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}

		$PowerBB->_GET['count'] = (!isset($PowerBB->_GET['count'])) ? 0 : $PowerBB->_GET['count'];
		$PowerBB->_GET['count'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['count'],'intval');

		//////////

		$NumArr 						= 	array();
		$NumArr['where'] 				= 	array();

		$NumArr['where'][0] 			= 	array();
		$NumArr['where'][0]['name'] 	= 	($PowerBB->_GET['folder'] == 'inbox') ? 'user_to' : 'user_from';
		$NumArr['where'][0]['oper'] 	= 	'=';
		$NumArr['where'][0]['value'] 	= 	$PowerBB->_CONF['member_row']['username'];

		$NumArr['where'][1] 			= 	array();
		$NumArr['where'][1]['con'] 		= 	'AND';
		$NumArr['where'][1]['name'] 	= 	'folder';
		$NumArr['where'][1]['oper'] 	= 	'=';
		$NumArr['where'][1]['value'] 	= 	($PowerBB->_GET['folder'] == 'inbox') ? 'inbox' : 'sent';

		//////////

		$MsgArr = array();

		$MsgArr['username'] 			= 	$PowerBB->_CONF['member_row']['username'];

		$MsgArr['proc'] 				= 	array();
		$MsgArr['proc']['*'] 			= 	array('method'=>'clean','param'=>'html');
		$MsgArr['proc']['date'] 		= 	array('method'=>'date','store'=>'date','type'=>$PowerBB->_CONF['info_row']['timesystem']);

		$MsgArr['order']				=	array();
		$MsgArr['order']['field']		=	'id';
		$MsgArr['order']['type']		=	'DESC';

		// Pager setup
		$MsgArr['pager'] 				= 	array();
		$MsgArr['pager']['total']		= 	$PowerBB->pm->GetPrivateMassegeNumber($NumArr);
		$MsgArr['pager']['perpage'] 	= 	$PowerBB->_CONF['info_row']['perpage'];
		$MsgArr['pager']['count'] 		= 	$PowerBB->_GET['count'];
		if ($PowerBB->_GET['folder'] == 'sent')
		{
		$MsgArr['pager']['location'] 	= 	'index.php?page=pm_list&list=1&folder=sent';
		}
		else
		{
		$MsgArr['pager']['location'] 	= 	'index.php?page=pm_list&list=1&folder=inbox';
		}
		$MsgArr['pager']['var'] 		= 	'count';

		if ($PowerBB->_GET['folder'] == 'sent')
		{
            $PowerBB->template->assign('folder','sent');
			$GetMassegeList = $PowerBB->pm->GetSentList($MsgArr);
			$PowerBB->_CONF['template']['while']['MassegeList'] = $GetMassegeList;
		}
		else
		{
			$GetMassegeList = $PowerBB->pm->GetInboxList($MsgArr);
			$PowerBB->template->assign('folder','inbox');
		}

		$PowerBB->_CONF['template']['while']['MassegeList'] = $GetMassegeList;
           $AllPmNum = $PowerBB->pm->GetAllPmNum($MsgArr);
           $PowerBB->template->assign('AllPmNum',$AllPmNum);
           $PowerBB->template->assign('AllowedPmNum',$PowerBB->_CONF['group_info']['max_pm']);
           $PowerBB->template->assign('MsgsNum',$PowerBB->pm->GetPrivateMassegeNumber($NumArr));
		if ($PowerBB->pm->GetPrivateMassegeNumber($NumArr) > $PowerBB->_CONF['info_row']['perpage'])
		{
		$PowerBB->template->assign('pager',$PowerBB->pager->show());
		}
		$PowerBB->template->assign('folder',$PowerBB->_GET['folder']);

		$PowerBB->template->display('pm_list');
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
