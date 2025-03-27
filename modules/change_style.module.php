<?php
(!defined('IN_PowerBB')) ? die() : '';
define('STOP_STYLE',true);
define('CLASS_NAME','PowerBBChangeStyleMOD');
include('common.php');
class PowerBBChangeStyleMOD
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
			$StyleArr 				= 	array();
			$StyleArr['field']		=	array();

			$StyleArr['field']['style'] = $PowerBB->_GET['id'];

			if ($PowerBB->_CONF['member_permission'])
			{
				$StyleArr['where'] = array('id',$PowerBB->_CONF['member_row']['id']);
				$change = $PowerBB->core->Update($StyleArr,'member');

               $Style_id = $PowerBB->_GET['id'];
				$options 			 = 	array();
				$options['expires']	 =	time()+2592000;
	            $PowerBB->functions->pbb_set_cookie('PowerBB_style',$Style_id,$options);

			}
			else
			{
				$Style_id = $PowerBB->_GET['id'];
				$options 			 = 	array();
				$options['expires']	 =	time()+2592000;
	            $PowerBB->functions->pbb_set_cookie('PowerBB_style',$Style_id,$options);

                if (strstr($PowerBB->_SERVER['HTTP_REFERER'],$PowerBB->functions->GetForumAdress()))
				{
					$PowerBB->functions->redirect2($PowerBB->_SERVER['HTTP_REFERER']);
				}
				else
				{
					$PowerBB->functions->redirect2('index.php');
				}

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