<?php
(!defined('IN_PowerBB')) ? die() : '';


$CALL_SYSTEM			=	array();
$CALL_SYSTEM['SECTION'] 	= 	true;
$CALL_SYSTEM['GROUP'] 		= 	true;
$CALL_SYSTEM['SUBJECT'] 	= 	true;
$CALL_SYSTEM['REPLY'] 			= 	true;
$CALL_SYSTEM['CACHE'] 			= 	true;

define('CLASS_NAME','PowerBBPluginMOD');

include('common.php');
class PowerBBPluginMOD
{
	function run()
	{
		global $PowerBB;


            if ($PowerBB->_GET['main'])
			{
				if ($PowerBB->_GET['control'])
				{
					$this->_ControlMain();
				}

			}
			elseif ($PowerBB->_GET['update'])
			{
				$this->StartUpdate();
			}
			elseif ($PowerBB->_GET['hook'])
			{
				$this->Starthook_no_Header();
			}
			else
			{
				header("Location: index.php");
				exit;
			}
		$PowerBB->functions->GetFooter();
	}

	function _ControlMain()
	{
		global $PowerBB;

       $PowerBB->functions->ShowHeader();

     eval($PowerBB->functions->get_fetch_hooks('PluginHooksMain'));
     exit;
	}

	function StartUpdate()
	{
		global $PowerBB;
       $PowerBB->functions->ShowHeader();

      eval($PowerBB->functions->get_fetch_hooks('PluginHooksUpdate'));

     exit;
	}

	function Starthook_no_Header()
	{
		global $PowerBB;

      eval($PowerBB->functions->get_fetch_hooks('Plugin_no_Header'));
      exit;
	}

}



?>
