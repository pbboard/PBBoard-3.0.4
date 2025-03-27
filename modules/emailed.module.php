<?php

// ##############################################################################||
// #
// #   PowerBB Version 2.0.4
// #   https://pbboard.info
// #   Copyright (c) 2009 by Abu.Rakan
// #
// #   filename : emailed.module.php
// #   members Subscriptions postal
// #
// ##############################################################################||

(!defined('IN_PowerBB')) ? die() : '';

$CALL_SYSTEM					=	array();
$CALL_SYSTEM['EMAILED'] 		= 	true;

define('JAVASCRIPT_PowerCode',true);



define('CLASS_NAME','PowerBBEmailedMOD');

include('common.php');
class PowerBBEmailedMOD
{

	function run()
	{
		global $PowerBB;


		/** Visitor can't use the Subscriptions postal system **/
		if (!$PowerBB->_CONF['member_permission'])
		{
			 $PowerBB->functions->ShowHeader();
             $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
             $PowerBB->functions->GetFooter();
		}
		/** **/

			/** Delete Subscriptions postal from usercp **/
			if ($PowerBB->_GET['del'] == '1')
			{
				$this->_DeleteSubscriptions();
			}
			else
			{
			 $PowerBB->functions->ShowHeader();
             $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Sorry_url_not_true']);
             $PowerBB->functions->GetFooter();
			}
		  /** **/

	}



    function _DeleteSubscriptions()
	{
		global $PowerBB;

		$PowerBB->functions->ShowHeader($PowerBB->_CONF['template']['_CONF']['lang']['deletion_process']);

		if (empty($PowerBB->_POST['check']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['You_do_not_select_any_Emailed']);
		}


       $Subscriptions_D = $PowerBB->_POST['check'];


       foreach ($Subscriptions_D as $DeleteSubscriptions)
       {

				// Delete Subscriptionsment from database
				$DelSubscriptionsArr 							= 	array();
		        $DelSubscriptionsArr['where'] 		    	= 	array('id',intval($DeleteSubscriptions));

				$DeleteSubscriptions = $PowerBB->emailed->DeleteEmailed($DelSubscriptionsArr);

       }


                $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['del_Emailed_successfully']);
				$PowerBB->functions->redirect('index.php?page=usercp&options=1&emailed=1&main=1');
				$PowerBB->functions->GetFooter();
	}

}
?>

