<?php

(!defined('IN_PowerBB')) ? die() : '';

define('STOP_STYLE',true);

$CALL_SYSTEM			=	array();
$CALL_SYSTEM['LANG'] 	= 	true;
$CALL_SYSTEM['VISITOR'] 	= 	true;



define('CLASS_NAME','PowerBBChangeLangMOD');

include('common.php');
class PowerBBChangeLangMOD
{
	function run()
	{
		global $PowerBB;

		$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');

		if (empty($PowerBB->_GET['id']))
		{
			header("Location: index.php");
			exit;
		}

		if ($PowerBB->_GET['change'])
		{

			if ($PowerBB->_CONF['member_permission'])
			{				$LangArr 				= 	array();
				$LangArr['field']		=	array();

				$LangArr['field']['lang'] = $PowerBB->_GET['id'];

				$LangArr['where'] = array('id',$PowerBB->_CONF['member_row']['id']);

				$change = $PowerBB->core->Update($LangArr,'member');
                ob_start();
                setcookie("PowerBB_lang", $PowerBB->_GET['id'], time()+2592000);
                ob_end_flush();
			}
			else
			{

                ob_start();
                setcookie("PowerBB_lang", $PowerBB->_GET['id'], time()+2592000);
                ob_end_flush();
				$change = 1;


			}

			if ($change)
			{
                    $redirect1 = $PowerBB->functions->redirect2('index.php');
                    $redirect2 = $PowerBB->functions->redirect2($PowerBB->_SERVER['HTTP_REFERER']);

		                if (strstr($PowerBB->_SERVER['HTTP_REFERER'],$PowerBB->functions->GetForumAdress()))
						{
							$PowerBB->functions->redirect2($PowerBB->_SERVER['HTTP_REFERER']);
						}
						else
						{
							$PowerBB->functions->redirect2('index.php');
						}
			}
		}
		else
		{
			header("Location: index.php");
			exit;
		}
	}
}

?>
