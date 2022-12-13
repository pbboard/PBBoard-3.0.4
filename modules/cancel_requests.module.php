<?php

(!defined('IN_PowerBB')) ? die() : '';

$CALL_SYSTEM				=	array();
$CALL_SYSTEM['REQUEST'] 	= 	true;



define('CLASS_NAME','PowerBBCReqMOD');

include('common.php');
class PowerBBCReqMOD
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

		// Show header with page title
		$PowerBB->functions->ShowHeader($PowerBB->_CONF['template']['_CONF']['lang']['Cancel_request']);

		$PowerBB->functions->AddressBar($PowerBB->_CONF['template']['_CONF']['lang']['Cancel_request']);

		if (empty($PowerBB->_GET['type'])
			or empty($PowerBB->_GET['code']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Sorry_url_not_true']);
		}

        $DelArr				=	array();
        $DelArr['where'] 	= 	array('random_url',$PowerBB->_GET['code']);

		$CleanReq = $PowerBB->core->Deleted($DelArr,'requests');

		if ($CleanReq)
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Was_Cancel_request_successfully']);
			$PowerBB->functions->redirect('index.php');
		}
	}
}

?>
