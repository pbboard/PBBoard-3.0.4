<?php
(!defined('IN_PowerBB')) ? die() : '';
define('CLASS_NAME','PowerBBCoreMOD');
include('common.php');
class PowerBBCoreMOD
{
	function run()
	{
		global $PowerBB;

			if ($PowerBB->_GET['index'] == '1')
			{
				$this->_MemberWarnIndex();
			}
			elseif($PowerBB->_GET['start'] == '1')
			{
				$this->_MemberWarnStart();
			}
			else
			{
			 $PowerBB->functions->ShowHeader();
             $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Sorry_url_not_true']);
             $PowerBB->functions->GetFooter();
			}


		$PowerBB->functions->GetFooter();
	}



	function _MemberWarnIndex()
	{
		global $PowerBB;
		$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');

		$PowerBB->functions->ShowHeader();

   		if (empty($PowerBB->_GET['id']))
   		{
   		 $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
   		}
		// Getting member info
		$MemArr = array();
		$MemArr['where'] = array('id',$PowerBB->_GET['id']);
		$PowerBB->_CONF['member_row'] = $PowerBB->core->GetInfo($MemArr,'member');
		$PowerBB->_CONF['template']['MemberInfo'] = $PowerBB->_CONF['member_row'];
		//////////
		if (!$PowerBB->_CONF['template']['MemberInfo'])
     	{
     		$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Member_does_not_exist']);
     	}
		// Getting member group
		$GroupInfo = array();
		$GroupInfo['where'] = array('id',$PowerBB->_CONF['member_row']['usergroup']);
		$PowerBB->_CONF['group_info'] = $PowerBB->core->GetInfo($GroupInfo,'group');
		//////////

		if (!$PowerBB->_CONF['member_permission'])
     	{
     		$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Visitors_can_not_use_your_warning']);
     	}
		elseif(!$PowerBB->_CONF['rows']['group_info']['send_warning'])
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['You_have_no_powers_to_use_this_system']);
		}
		elseif(!$PowerBB->_CONF['group_info']['can_warned'])
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['You_can_not_notice_this_member']);
		}

     	$PowerBB->template->display('warn_send');
     }


 function _MemberWarnStart()
	{
		global $PowerBB;

		$PowerBB->functions->ShowHeader();

 		$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');
		$PowerBB->_POST['warn_liftdate'] = $PowerBB->functions->CleanVariable($PowerBB->_POST['warn_liftdate'],'intval');
		if (empty($PowerBB->_GET['id']))
		{
		 $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}
		if (empty($PowerBB->_POST['text']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_enter_the_reason_for_warning_this_member']);
		}
		// Getting member info
		$MemArr = array();
		$MemArr['where'] = array('id',$PowerBB->_GET['id']);
		$PowerBB->_CONF['member_row'] = $PowerBB->core->GetInfo($MemArr,'member');
		if (!$PowerBB->_CONF['member_row'])
     	{
     		$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Member_does_not_exist']);
     	}
		// Getting member group
		$GroupInfo = array();
		$GroupInfo['where'] = array('id',$PowerBB->_CONF['member_row']['usergroup']);
		$PowerBB->_CONF['group_info'] = $PowerBB->core->GetInfo($GroupInfo,'group');

        if(!$PowerBB->_CONF['rows']['group_info']['send_warning'])
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['You_have_no_powers_to_use_this_system']);
		}
		elseif(!$PowerBB->_CONF['group_info']['can_warned'])
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['You_can_not_notice_this_member']);
		}
		///////
		$StartArr = array();
		$StartArr['field'] = array();

		$StartArr['field']['warnings'] 	= 	$PowerBB->_CONF['member_row']['warnings']+1;
		$StartArr['where'] =	array('id',$PowerBB->_GET['id']);

		$Warn = $PowerBB->core->Update($StartArr,'member');


		if ($Warn)
		{					$PowerBB->_POST['text'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['text'],'sql');
				$StartArr = array();
				$StartArr['field'] = array();
				$StartArr['field']['unread_pm'] 	= 	$PowerBB->_CONF['member_row']['unread_pm']+1;
				$StartArr['where'] =	array('id',$PowerBB->_GET['id']);
				$Update = $PowerBB->core->Update($StartArr,'member');
				//////////
				$MsgArr 	= 	array();
				$MsgArr['get_id']=	true;
				$MsgArr['field']	=	array();
				$MsgArr['field']['user_from'] 	= 	$PowerBB->_CONF['rows']['member_row']['username'];
				$MsgArr['field']['user_to'] 	= 	$PowerBB->_CONF['member_row']['username'];
				$MsgArr['field']['title'] 		= 	$PowerBB->_CONF['template']['_CONF']['lang']['Warning'];
				$MsgArr['field']['text'] 		= 	$PowerBB->_POST['text'];
				$MsgArr['field']['date'] 		= 	date('Y-m-d', $PowerBB->_CONF['now']);
				$MsgArr['field']['icon'] 		= 	'look/images/icons/i1.gif';
				$MsgArr['field']['folder'] 		= 	'inbox';
				$Send = $PowerBB->core->Insert($MsgArr,'pm');

		$warn_liftdate =	date($PowerBB->_CONF['info_row']['datesystem'],mktime(0, 0, 0, date("m"),date("d")+$PowerBB->_POST['warn_liftdate'],date("Y")));
         if ($warn_liftdate == date('d-m-Y', $PowerBB->_CONF['now']))
		 {
		   $warn_liftdate =	"23-12-2080";
		 }

				$LogArr = array();
				$LogArr['field'] = array();
				$LogArr['field']['warn_from'] = $PowerBB->_CONF['rows']['member_row']['username'];
				$LogArr['field']['warn_to'] = $PowerBB->_CONF['member_row']['username'];
				$LogArr['field']['warn_text'] = $PowerBB->_POST['text'];
				$LogArr['field']['warn_liftdate'] = $warn_liftdate;
				$LogArr['field']['warn_date'] = date('d-m-Y', $PowerBB->_CONF['now']);
				$LogInsert = $PowerBB->core->Insert($LogArr,'warnlog');

                 //  if warning number More The number of warnings suspended Member
				if ($PowerBB->_CONF['member_row']['warnings'] >= $PowerBB->_CONF['info_row']['warning_number_to_ban']-1)
				{					$GrpArr 			= 	array();
					$GrpArr['where'] 	= 	array('id','6');

					$GroupInfo = $PowerBB->core->GetInfo($GrpArr,'group');

					$MemArr 			= 	array();
					$MemArr['where'] 	= 	array('id',$PowerBB->_GET['id']);

					$MemInfo = $PowerBB->core->GetInfo($MemArr,'member');

					$style = $GroupInfo['username_style'];
					$username_style_cache = str_replace('[username]',$MemInfo['username'],$style);


					$BandArr = array();
					$BandArr['field'] = array();

					$BandArr['field']['usergroup'] 	= 	'6';
		            $BandArr['field']['username_style_cache'] 		= 	$username_style_cache;
					$BandArr['field']['user_title'] 	= 	$PowerBB->_CONF['template']['_CONF']['lang']['suspended'];
					$BandArr['where'] =	array('id',$PowerBB->_GET['id']);

					$Band = $PowerBB->core->Update($BandArr,'member');
                // UPDATE username_style today
                $update_username_style_today = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['today'] . " SET username_style='" . $username_style_cache . "' WHERE user_id='" . $MemInfo['id'] . "'");
                // UPDATE username_style online
                $update_username_style_online = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['online'] . " SET username_style='" . $username_style_cache . "' WHERE user_id='" . $MemInfo['id'] . "'");

				 if ($Band)
				 {
			        $SmLogsArr 			= 	array();
					$SmLogsArr['field']	=	array();

					$SmLogsArr['field']['username'] 	= 	$PowerBB->_CONF['rows']['member_row']['username'];
					$SmLogsArr['field']['edit_action'] 	= 	$PowerBB->_CONF['template']['_CONF']['lang']['Warning_by'] . $PowerBB->_CONF['member_row']['username'];
					$SmLogsArr['field']['subject_title']= 	$PowerBB->_CONF['template']['_CONF']['lang']['suspended_by'];
					$SmLogsArr['field']['subject_id'] 	= 	$PowerBB->_GET['id'];
					$SmLogsArr['field']['edit_date'] 	= 	date("d/m/Y", $PowerBB->_CONF['now']);

					$insert = $PowerBB->core->Insert($SmLogsArr,'supermemberlogs');
                 }
				}

			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Was_warning_member_successfully']);
			$PowerBB->functions->redirect('index.php');
		}
		else
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['There_was_an_error_no_warning_Member']);
		}
	}
}

?>