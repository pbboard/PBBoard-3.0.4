<?php

(!defined('IN_PowerBB')) ? die() : '';

$CALL_SYSTEM				=	array();
$CALL_SYSTEM['SECTION'] 	= 	true;

/*$CALL_SYSTEM				=	array();
$CALL_SYSTEM['REQUEST'] 	= 	true;
$CALL_SYSTEM['MESSAGE'] 	= 	true;*/



define('CLASS_NAME','PowerBBOnlineMOD');

include('common.php');
class PowerBBOnlineMOD
{
	function run()
	{
		global $PowerBB;

		if ($PowerBB->_GET['show'])
		{		    $PowerBB->functions->ShowHeader($PowerBB->_CONF['template']['_CONF']['lang']['online_naw']);		    $this->_GetJumpSectionsList();
			$this->_Show();
		}
		else
		{
			header("Location: index.php?page=online&show=1");
			exit;
		}

		$PowerBB->functions->GetFooter();
	}

	    /**
	 * Get the Jump Sections List
	 */
	function _GetJumpSectionsList()
    {
		global $PowerBB;
       $PowerBB->functions->JumpForumsList();
   }

	function _Show()
	{
		global $PowerBB;

		if (!$PowerBB->_CONF['group_info']['onlinepage_allow'])
		{
          if (!$PowerBB->_CONF['member_permission'])
              {
              $PowerBB->template->display('login');
              $PowerBB->functions->error_stop();
			}
	        else
	        {
	        $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['no_online']);
	        }
	     }

		$PowerBB->_GET['count'] = (!isset($PowerBB->_GET['count'])) ? 0 : $PowerBB->_GET['count'];
		$PowerBB->_GET['count'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['count'],'intval');

      	$writer = $PowerBB->_CONF['rows']['member_row']['username'];
        $GetOnlineNum = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT id FROM " . $PowerBB->table['online'] . " WHERE id and hide_browse <>1"));
        $GetOnlinemem = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT id FROM " . $PowerBB->table['online'] . " WHERE id and hide_browse <>1 and user_id<>-1"));
       	$PowerBB->template->assign('GetOnlineNum',$GetOnlineNum);
       	$PowerBB->template->assign('GetOnlineNum_mem',$GetOnlinemem);

		$OnlineArr = array();
		$OnlineArr['order'] = array();
		$OnlineArr['order']['field'] = 'user_id > 0 DESC, last_move';
		$OnlineArr['order']['type'] = 'DESC';
		// Pager setup
		$OnlineArr['pager'] 				= 	array();
		$OnlineArr['pager']['total']		= 	$GetOnlineNum;
		$OnlineArr['pager']['perpage']  	= 	$PowerBB->_CONF['info_row']['subject_perpage'];
		$OnlineArr['pager']['count'] 		= 	$PowerBB->_GET['count'];
		$OnlineArr['pager']['location'] 	= 	'index.php?page=online&show=1';
		$OnlineArr['pager']['var'] 		    = 	'count';

		// This member can't see hidden member
		if (!$PowerBB->_CONF['group_info']['show_hidden'])
		{
			$OnlineArr['where'][0] 			= 	array();
			$OnlineArr['where'][0]['name'] 	= 	'hide_browse';
			$OnlineArr['where'][0]['oper'] 	= 	'<>';
			$OnlineArr['where'][0]['value'] = 	'1';
		}


        $OnlineArr['proc']['logged'] = array('method'=>'time_ago','store'=>'logged','type'=>$PowerBB->_CONF['info_row']['timesystem']);
		$PowerBB->_CONF['template']['while']['Online'] = $PowerBB->online->GetOnlineList($OnlineArr);

		foreach ($PowerBB->_CONF['template']['while']['Online'] as &$row) {

		    if (empty($row['last_move']) || (int)$row['last_move'] === 0) {
		        $row['last_move'] = $row['logged'];
		    }
		    elseif (is_numeric($row['last_move'])) {
		        $row['last_move'] = $PowerBB->functions->time_ago(
		            (int)$row['last_move'],
		            $PowerBB->_CONF['info_row']['timesystem']
		        );
		    }
		}
		unset($row);


            if ($GetOnlineNum > $PowerBB->_CONF['info_row']['subject_perpage'])
	        {
			$PowerBB->template->assign('pager',$PowerBB->pager->show());
	         }

		$PowerBB->template->display('online');

	}
}

?>
