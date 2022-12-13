<?php

(!defined('IN_PowerBB')) ? die() : '';

define('IN_ADMIN',true);

$CALL_SYSTEM			=	array();
$CALL_SYSTEM['FIXUP'] 	= 	true;
$CALL_SYSTEM['SECTION'] 	= 	true;
$CALL_SYSTEM['GROUP'] 		= 	true;
$CALL_SYSTEM['SUBJECT'] 	= 	true;
$CALL_SYSTEM['REPLY'] 			= 	true;
$CALL_SYSTEM['CACHE'] 			= 	true;



define('CLASS_NAME','PowerBBPluginMOD');

include('../common.php');
class PowerBBPluginMOD
{
	function run()
	{
		global $PowerBB;

		if ($PowerBB->_CONF['member_permission'])
		{
			$PowerBB->template->display('header');

            if ($PowerBB->_GET['main'])
			{
				if ($PowerBB->_GET['control'])
				{
					$this->_ControlMain();
				}

			}
				if ($PowerBB->_GET['update'])
				{
					$this->StartUpdate();
				}

			$PowerBB->template->display('footer');
		}
	}

	function _ControlMain()
	{
		global $PowerBB;
        $template = $PowerBB->_GET['template'];
		$PowerBB->template->display($template);

	}

	function StartUpdate()
	{
		global $PowerBB;

		$kv = array();
		foreach ($PowerBB->_POST as $var_name => $value) {
		$kv[] = "$var_name=$value";
		if ($value !='')
		{
		$update = $PowerBB->info->UpdateInfo(array('value'=>$value,'var_name'=>$var_name));
		}
		}
		if ($update)
		{
		$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['updated_successfully_Please_wait']);
		$template = $PowerBB->_GET['template'];
		$PowerBB->functions->redirect('index.php?page=plugin&amp;control=1&amp;template='.$template.'&amp;main=1');
		}

	}


}



?>
