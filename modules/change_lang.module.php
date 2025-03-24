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
			 $PowerBB->functions->ShowHeader();
             $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Sorry_url_not_true']);
             $PowerBB->functions->GetFooter();
		}

		if ($PowerBB->_GET['change'] == '1')
		{

			if ($PowerBB->_CONF['member_permission'])
			{				$LangArr 				= 	array();
				$LangArr['field']		=	array();

				$LangArr['field']['lang'] = $PowerBB->_GET['id'];

				$LangArr['where'] = array('id',$PowerBB->_CONF['member_row']['id']);

				$change = $PowerBB->core->Update($LangArr,'member');
				$options 			 = 	array();
				$options['expires']	 =	time()+2592000;
	            $PowerBB->functions->pbb_set_cookie('PowerBB_lang',$PowerBB->_GET['id'],$options);
			}
			else
			{
				$options 			 = 	array();
				$options['expires']	 =	time()+2592000;
				$PowerBB->functions->pbb_set_cookie('PowerBB_lang',$PowerBB->_GET['id'],$options);
				$change = 1;


			}

			if ($change)
			{
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
			 $PowerBB->functions->ShowHeader();
             $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Sorry_url_not_true']);
             $PowerBB->functions->GetFooter();
		}
	}
}

?>