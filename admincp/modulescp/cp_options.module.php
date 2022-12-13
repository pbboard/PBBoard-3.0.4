<?php

(!defined('IN_PowerBB')) ? die() : '';

define('IN_ADMIN',true);



define('CLASS_NAME','PowerBBCPOptionsMOD');

include('../common.php');
class PowerBBCPOptionsMOD
{
	function run()
	{
		global $PowerBB;

		if ($PowerBB->_CONF['member_permission'])
		{
			$PowerBB->template->display('header');

			if ($PowerBB->_GET['index'])
			{
				$this->_IndexPage();
			}
			elseif ($PowerBB->_GET['worms_pbb'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_WormsPbbMain();
				}
				elseif ($PowerBB->_GET['update'])
				{
					$this->_WormsPbbUpdate();
				}
			}
			elseif ($PowerBB->_GET['cpstyle'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_CpstyleMain();
				}
				elseif ($PowerBB->_GET['update'])
				{
					$this->_CpstyleUpdate();
				}
			}

			$PowerBB->template->display('footer');
		}
	}

	function _IndexPage()
	{
		global $PowerBB;

		$PowerBB->template->display('cp_options_main');
	}

	function _WormsPbbMain()
	{
		global $PowerBB;

		$PowerBB->template->display('cp_options_worms');
	}

	function _WormsPbbUpdate()
	{
		global $PowerBB;

		$update = array();

		$update[0] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['active_worms_pbb'],'var_name'=>'active_worms_pbb'));
		$update[1] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['shelluser'],'var_name'=>'shelluser'));
		$update[2] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['shellpswd'],'var_name'=>'shellpswd'));
		$update[3] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['shelladminemail'],'var_name'=>'shelladminemail'));


		if ($update[0]
			and $update[1]
			and $update[2]
			and $update[3])
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['updated_successfully_Please_wait']);
			$PowerBB->functions->redirect('index.php?page=cp_options&amp;worms_pbb=1&amp;main=1');
		}
	}

	function _CpstyleMain()
	{
		global $PowerBB;
		$StyleDir = ("../".$PowerBB->admincpdir."/cpstyles");

		if (is_dir($StyleDir))
		{
			$dir = opendir($StyleDir);

			if ($dir)
			{
				while (($file = readdir($dir)) !== false)
				{
					if ($file == '.'
						or $file == '..')
					{
						continue;
					}

					$StylesList[]['filename'] = $file;
				}

				closedir($dir);
			}
		}

		$PowerBB->_CONF['template']['foreach']['StyleCpList'] = $StylesList;


		$PowerBB->template->display('cp_options_cpstyle');
	}

	function _CpstyleUpdate()
	{
		global $PowerBB;

		$update = array();

		$update[0] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['cssprefs'],'var_name'=>'cssprefs'));

		if ($update[0])
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['updated_successfully_Please_wait']);
			$PowerBB->functions->redirect('index.php?page=cp_options&amp;cpstyle=1&amp;main=1');
		}
	}

}

?>
