<?PHP
(!defined('IN_PowerBB')) ? die() : '';

define('IN_ADMIN',true);

$CALL_SYSTEM				=	array();
$CALL_SYSTEM['EMAILED'] 		= 	true;
$CALL_SYSTEM['STYLE'] 		= 	true;
$CALL_SYSTEM['INFO'] 		= 	true;


define('CLASS_NAME','PowerBBEmailedMOD');

include('../common.php');
class PowerBBEmailedMOD
{
	function run()
	{
		global $PowerBB;

		if ($PowerBB->_CONF['member_permission'])
			{
			$PowerBB->template->display('header');


			if ($PowerBB->_CONF['rows']['group_info']['admincp_emailed'] == '0')
			{
			  $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
			}


				if ($PowerBB->_GET['main'])
				{
					$this->_Emailed_Control();
				}
				elseif($PowerBB->_GET['main_del'])
				{
					$this->_Main_Del();
				}
				if($PowerBB->_GET['update'])
				{
					$this->Emailed_Update();
				}
				if($PowerBB->_GET['start_del'])
				{
					$this->_Start_Del();
				}
			}
			       $PowerBB->template->display('footer');
	}


	function _Emailed_Control()
	{
		global $PowerBB;

		$PowerBB->template->display('emailed_control');
	}

	function Emailed_Update()
	{
		global $PowerBB;

		$update = array();
		$update[0] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['allowed_emailed'],'var_name'=>'allowed_emailed'));
		$update[1] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['allowed_emailed_pm'],'var_name'=>'allowed_emailed_pm'));

		if ($update[0] and $update[1])
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['updated_successfully_Please_wait']);
			$PowerBB->functions->redirect('index.php?page=emailed&amp;main=1');
		}
	}

	function _Main_Del()
	{
		global $PowerBB;

		$PowerBB->template->display('emailed_main_del');
	}

	function _Start_Del()
	{
		global $PowerBB;

      	if ($PowerBB->_POST['del_all_emailed'] == '0')
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Choose_incorrectl']);
			$PowerBB->functions->redirect('index.php?page=emailed&amp;main_del=1');
		}
		else
		{
           $truncate = $PowerBB->DB->sql_query("TRUNCATE " . $PowerBB->table['emailed'] );

			if ($truncate)
			{
				$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Was_to_cancel_all_subscriptions_postal_successfully']);
		      	$PowerBB->functions->redirect('index.php?page=emailed&amp;main_del=1');
			}
		}

	 }



}
?>
