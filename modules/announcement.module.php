<?php
(!defined('IN_PowerBB')) ? die() : '';

define('CLASS_NAME','PowerBBCoreMOD');

include('common.php');
class PowerBBCoreMOD
{

	function run()
	{
		global $PowerBB;

		/** Show the announcement **/
		if ($PowerBB->_GET['show'])
		{
			$this->_ShowAnnouncement();
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
	 * Get the announcement and show it
	 */
	function _ShowAnnouncement()
	{
		global $PowerBB;

		// Show header with page title
	   	$PowerBB->functions->ShowHeader($PowerBB->_CONF['template']['_CONF']['lang']['View_administrative_announcement']);

		// Clean the id from any strings
		$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');

		if (empty($PowerBB->_GET['id']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}

		$AnnArr 			= 	array();
		$AnnArr['where']	=	array('id',$PowerBB->_GET['id']);

		// Get the announcement information
		$PowerBB->_CONF['template']['AnnInfo'] = $PowerBB->core->GetInfo($AnnArr,'announcement');

		// Clean the information
		$PowerBB->functions->CleanVariable($PowerBB->_CONF['template']['AnnInfo'],'html');

		// No announcement , stop the page
		if (!$PowerBB->_CONF['template']['AnnInfo'])
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['The_ads_does_not_exist']);
		}

	     if (!empty($PowerBB->_GET['id']))
		  {
			// Count a new announcement
			$UpdateArr 						= 	array();
			$UpdateArr['field'] 			= 	array();
			$UpdateArr['field']['visitor'] 	= 	$PowerBB->_CONF['template']['AnnInfo']['visitor'] + 1;
			$UpdateArr['where'] 			= 	array('id',$PowerBB->_GET['id']);

			$update = $PowerBB->core->Update($UpdateArr,'announcement');
	      }

		//////////

		// Where is the member now?
		if ($PowerBB->_CONF['member_permission'])
     	{
     		$UpdateOnline 			= 	array();
			$UpdateOnline['field']	=	array();
            $UpdateOnline['field']['path'] 		= 	$PowerBB->_SERVER['QUERY_STRING'];
			$UpdateOnline['field']['user_location']		=	 $PowerBB->_CONF['template']['_CONF']['lang']['user_location_inannouncement'] . $PowerBB->_CONF['template']['AnnInfo']['title'];
			$UpdateOnline['where']						=	array('username',$PowerBB->_CONF['member_row']['username']);

			$update = $PowerBB->core->Update($UpdateOnline,'online');
     	}
     	else
		{
		   // visitor already online , just update information
				$UpdateOnlineArr 			= 	array();
				$UpdateOnlineArr['field'] 	= 	array();

				$UpdateOnlineArr['field']['path'] 		= 	$PowerBB->_SERVER['QUERY_STRING'];
				$UpdateOnlineArr['field']['user_location'] 	    = 	$PowerBB->_CONF['template']['_CONF']['lang']['user_location_inannouncement'] . $PowerBB->_CONF['template']['AnnInfo']['title'];
				$UpdateOnlineArr['field']['user_id']			=	-1;
				$UpdateOnlineArr['where']				=	array('user_ip',$PowerBB->_CONF['ip']);

			   $update = $PowerBB->core->Update($UpdateOnlineArr,'online');
		}



     	//////////

     	// Change text format
        $PowerBB->_CONF['template']['AnnInfo']['text']= str_replace('../look/','look/',$PowerBB->_CONF['template']['AnnInfo']['text']);
		$PowerBB->_CONF['template']['AnnInfo']['text'] = $PowerBB->Powerparse->replace($PowerBB->_CONF['template']['AnnInfo']['text']);
		$PowerBB->Powerparse->replace_smiles($PowerBB->_CONF['template']['AnnInfo']['text']);

     	//////////

		// We check if the "date" is saved as Unix stamptime, if true proccess it otherwise do nothing
		// We wrote these lines to ensure PowerBB 2.x is compatible with PowerBB's 1.x time save method
		if (is_numeric($PowerBB->_CONF['template']['AnnInfo']['date']))
		{
			$PowerBB->_CONF['template']['AnnInfo']['date'] = $PowerBB->functions->_date($PowerBB->_CONF['template']['AnnInfo']['date']);
		}

     $PowerBB->_CONF['template']['ReplierInfo'] = $PowerBB->member->GetMemberInfo($PowerBB->_CONF['template']['AnnInfo']['writer']);

     	//////////

		$PowerBB->template->display('announcement');

     	//////////
	}
}

?>
