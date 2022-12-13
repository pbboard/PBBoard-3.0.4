<?php
(!defined('IN_PowerBB')) ? die() : '';

define('CLASS_NAME','PowerBBCoreMOD');

include('common.php');
class PowerBBCoreMOD
{
	function run()
	{
		global $PowerBB;

		/** Go to ads site **/
		if ($PowerBB->_GET['goto'])
		{
			$this->_GoToSite();
		}
		/** **/
		else
		{
			header("Location: index.php");
			exit;
		}
		$PowerBB->functions->GetFooter();
	}

	/**
	 * Get the information of ads , then go to the site of ads
	 */
	function _GoToSite()
	{
		global $PowerBB;

		// Show header, The parameter is the page title.
		$PowerBB->functions->ShowHeader($PowerBB->_CONF['template']['_CONF']['lang']['Go_to_site']);

		// Clean _GET['id'] from strings and protect ourself
		$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');

		// Show the address bar, It's make the browse easy
		$PowerBB->functions->AddressBar($PowerBB->_CONF['template']['_CONF']['lang']['Go_ads']);

		// No id ! stop the page :)
		if (empty($PowerBB->_GET['id']))
		{
          $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Sorry_url_not_true']);
		}

		// Get Ads information
		$AdsArr 			= 	array();
		$AdsArr['where'] 	= 	array('id',$PowerBB->_GET['id']);

		$AdsRows = $PowerBB->core->GetInfo($AdsArr,'ads');

		// Clean the ads information from XSS dirty
		$PowerBB->functions->CleanVariable($AdsRows,'html');

		// Ads isn't here !
		if (!$AdsRows)
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['The_ads_does_not_exist']);
		}

		// New visitor
		$NewClickArr 			= 	array();
		$NewClickArr['clicks'] 	= 	$AdsRows['clicks'];
		$NewClickArr['where'] 	= 	array('id',$AdsRows['id']);

		$PowerBB->core->NewVisit($NewClickArr,'ads');

		// Go to the site
		$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Please_wait_will_be_taken_to_the_following_location'] . $AdsRows['sitename']);
		$PowerBB->functions->redirect2($AdsRows['site']);
	}
}

?>
