<?PHP
(!defined('IN_PowerBB')) ? die() : '';

define('IN_ADMIN',true);

$CALL_SYSTEM				=	array();
$CALL_SYSTEM['WARNLOG'] 		= 	true;
$CALL_SYSTEM['STYLE'] 		= 	true;


define('CLASS_NAME','PowerBBWarnLogMOD');

include('../common.php');
class PowerBBWarnLogMOD
{
	function run()
	{
		global $PowerBB;

		if ($PowerBB->_CONF['member_permission'])
			{
			$PowerBB->template->display('header');

			if ($PowerBB->_CONF['rows']['group_info']['admincp_warn'] == '0')
			{
			  $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
			}

				if ($PowerBB->_GET['main'])
				{
					$this->_ShowLog();
				}
				elseif($PowerBB->_GET['del'])
				{
					$this->_DelALL();
				}
				elseif($PowerBB->_GET['deletone'])
				{
					$this->_DelOne();
				}
			}
			       $PowerBB->template->display('footer');
	}
		function _ShowLog()
		{
		global $PowerBB;
		$PowerBB->_GET['count'] = (!isset($PowerBB->_GET['count'])) ? 0 : $PowerBB->_GET['count'];
		$PowerBB->_GET['count'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['count'],'intval');

 	    $warn_nm = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(1),id FROM " . $PowerBB->table['warnlog'] . " "));
        $subject_perpage = '32';

		$LogArr 					= 	array();
		$LogArr['order']			=	array();
		$LogArr['order']['field']	=	'id';
		$LogArr['order']['type']	=	'DESC';
		$LogArr['proc'] 			= 	array();
		$LogArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');
		$LogArr['pager'] 				= 	array();
		$LogArr['pager']['total']		= 	$warn_nm;
		$LogArr['pager']['perpage'] 	= 	$subject_perpage;
		$LogArr['pager']['count'] 		= 	$PowerBB->_GET['count'];
		$LogArr['pager']['location'] 	= 	'index.php?page=warn&amp;warn=1&amp;main=1';
		$LogArr['pager']['var'] 		= 	'count';

		$PowerBB->_CONF['template']['while']['WarningLog'] = $PowerBB->warnlog->Show($LogArr);
        if ($warn_nm > $subject_perpage)
        {
		$PowerBB->template->assign('pager',$PowerBB->pager->show());
        }
        $PowerBB->template->assign('WarnNumber',$warn_nm);

		$PowerBB->template->display('warnlog');

		}

		function _DelOne()
		{
			global $PowerBB;

			if (empty($PowerBB->_GET['id']))
			{
				$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['The_declaration_does_not_exist']);
			}

			if (empty($PowerBB->_GET['user']))
			{
				$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['The_declaration_does_not_exist']);
			}
			$MemArr = array();
			$MemArr['where'] = array('username',$PowerBB->_GET['user']);
			$PowerBB->_CONF['member_row'] = $PowerBB->core->GetInfo($MemArr,'member');

			$StartArr['field']['warnings'] 	= 	$PowerBB->_CONF['member_row']['warnings']-1;
			$StartArr['where'] =	array('id',$PowerBB->_CONF['member_row']['id']);
			$Warn = $PowerBB->core->Update($StartArr,'member');

			$DelArr 			= 	array();
			$DelArr['where'] 	= 	array('id',$PowerBB->_GET['id']);

			$del = $PowerBB->core->Deleted($DelArr,'warnlog');

			  if ($del)
				{
				$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['action_was_successful']);
				$PowerBB->functions->redirect('index.php?page=warn&amp;main=1');
				}

		}

		function _DelALL()
		{
			global $PowerBB;

		$UPDATE_warnings = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['member'] . " SET warnings = '0'");

			$DelArr = array();
			$del = $PowerBB->warnlog->DeleteLog($DelArr);
			if ($del)
				{
				$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Been_emptied_record_successfully']);
				$PowerBB->functions->redirect('index.php?page=warn&amp;main=1');
				}

		}

}
?>
