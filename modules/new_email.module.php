<?php

(!defined('IN_PowerBB')) ? die() : '';

$CALL_SYSTEM				=	array();
$CALL_SYSTEM['REQUEST'] 	= 	true;



define('CLASS_NAME','PowerBBEmailMOD');

include('common.php');
class PowerBBEmailMOD
{
	function run()
	{
		global $PowerBB;

		if ($PowerBB->_GET['index'])
		{
			$this->_Index();
		}
		else
		{
			header("Location: index.php");
			exit;
		}

		$PowerBB->functions->GetFooter();
	}

	function _Index()
	{
		global $PowerBB;

		$PowerBB->functions->ShowHeader($PowerBB->_CONF['template']['_CONF']['lang']['Complete_the_process_of_changing_e-mail']);

		$PowerBB->functions->AddressBar($PowerBB->_CONF['template']['_CONF']['lang']['Complete_the_process_of_changing_e-mail']);

		if (empty($PowerBB->_GET['code']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Sorry_url_not_true']);
		}
		if (!$PowerBB->_CONF['member_permission'])
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_log_in_first']);
		}


		$ReqArr 			= 	array();
		$ReqArr['where'] 	= 	array('random_url',$PowerBB->_GET['code']);

		$RequestInfo = $PowerBB->request->GetRequestInfo($ReqArr);

		if (!$RequestInfo)
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Sorry_demand_does_not_exist']);
		}

		$EmailArr 			= 	array();
		$EmailArr['field'] 	= 	array();

		$EmailArr['field']['email'] 	= 	$PowerBB->_CONF['member_row']['new_email'];
		$EmailArr['where'] 				= 	array('id',$PowerBB->_CONF['member_row']['id']);

		$UpdateEmail= $PowerBB->member->UpdateMember($EmailArr);

		if ($UpdateEmail)
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['email_was_changed_successfully']);
			$PowerBB->functions->redirect('index.php');
		}
	}
}

?>
