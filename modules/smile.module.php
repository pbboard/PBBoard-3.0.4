<?php

(!defined('IN_PowerBB')) ? die() : '';

$CALL_SYSTEM				=	array();
$CALL_SYSTEM['ICONS'] 		= 	true;



define('CLASS_NAME','PowerBBSmileMOD');

include('common.php');
class PowerBBSmileMOD
{
	function run()
	{
		global $PowerBB;

		if ($PowerBB->_GET['all'])
		{
			$this->_OpinWindoSmile();
		}
		else
		{
			header("Location: index.php");
			exit;
		}
	}

	function _OpinWindoSmile()
	{
		global $PowerBB;

    $PowerBB->_CONF['template']['while']['SmlList'] = $PowerBB->icon->GetSmileList($SmlArr);

		$PowerBB->template->display('smile_all');
	}



}

?>
