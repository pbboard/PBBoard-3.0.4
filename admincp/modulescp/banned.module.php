<?PHP
(!defined('IN_PowerBB')) ? die() : '';

define('IN_ADMIN',true);

$CALL_SYSTEM				=	array();
$CALL_SYSTEM['BANNED']      =   true;



define('CLASS_NAME','PowerBBBannedMOD');

include('../common.php');
class PowerBBBannedMOD
{
	function run()
	{
		global $PowerBB;

		if ($PowerBB->_CONF['member_permission'])
			{
			$PowerBB->template->display('header');

			if ($PowerBB->_CONF['rows']['group_info']['admincp_member'] == '0')
			{
			  $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
			}

				if ($PowerBB->_GET['banning'])
				{
					if ($PowerBB->_GET['main'])
					{
						$this->_BanningMain();
					}
					elseif ($PowerBB->_GET['start'])
					{
						$this->_BanningStart();
					}
					elseif ($PowerBB->_GET['del'])
					{
						$this->_BanningDelete();
					}

				}
			}
$PowerBB->template->display('footer');
	}


	function _BanningMain()
	  {
		global $PowerBB;

	    $ListBannedArr 						= 	array();
		// Order data
		$ListBannedArr['order']				=	array();
		$ListBannedArr['order']['field']	=	'id';
		$ListBannedArr['order']['type']		=	'ASC';

      $PowerBB->_CONF['template']['while']['BannedList'] = $PowerBB->banned->GetBannedList($ListBannedArr);

		$PowerBB->template->display('ipaddress_ban');
	}

	function _BanningStart()
	  {
		global $PowerBB;


		$BanArr 				= 	array();
		$BanArr['field']		=	array();

		$BanArr['field']['ip'] 		= 	$PowerBB->_POST['ipaddress'];
		$BanArr['field']['reason'] 	= 	$PowerBB->_POST['reason_ban'];

		$InsertBan = $PowerBB->banned->InsertBanned($BanArr);

		 if($InsertBan)
		  {
			// Finally , Start the banned
	        $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['IP_has_been_blocked_successfully']);
			$PowerBB->functions->redirect('index.php?page=banned&amp;banning=1&amp;main=1');

			}

	}

	function _BanningDelete()
	  {
		global $PowerBB;

	        // Delete attachment to the database
			$BanDelArr 							= 	array();
			$BanDelArr['name'] 	        		=  	'id';
	        $BanDelArr['where'] 		    	= 	array('id',$PowerBB->_GET['id']);

			$DeleteBan = $PowerBB->banned->DeleteBanned($BanDelArr);

			if($DeleteBan)
		  {
			// Finally , Delete the banned
	        $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Ban_has_been_deleted_successfully']);
			$PowerBB->functions->redirect('index.php?page=banned&amp;banning=1&amp;main=1');
		  }

	}

}
?>
