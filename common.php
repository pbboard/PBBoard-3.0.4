<?php
error_reporting(E_ERROR | E_PARSE);
@set_time_limit(0);
@ini_set('max_execution_time', 123456);
@session_start();

if(function_exists('date_default_timezone_set') && !ini_get('date.timezone'))
{
	@date_default_timezone_set('GMT');
}
// Security REQUEST METHOD POST
/*
if ($_SERVER['REQUEST_METHOD'] == 'POST'
and !isset($_SESSION['csrf']))
{
exit;
}
*/
//Generate a key, print a form:
$Generatekey = @sha1(@microtime());
$_SESSION['csrf'] = $Generatekey;
//Set no caching
// Make sure is not cached (as it happens for example on iOS devices)
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.

define('DIR',dirname( __FILE__ ) . '/');
define('JAVASCRIPT_PowerCode',false);
  		// Stop any external post request.
         if ($_SERVER['REQUEST_METHOD'] == 'POST')
         {
             $Y = explode('/',$_SERVER['HTTP_REFERER']);
             $X = explode('/',$_SERVER['HTTP_HOST']);

             if ($Y[2] != $X[0])
             {
              exit('No direct script access allowed');
             }
             elseif ($Y[2] != $_SERVER['HTTP_HOST'])
             {
              exit('No direct script access allowed');
             }
             elseif($_POST['csrf'] != $_SESSION['csrf'])
             {
				if (!defined('IN_ADMIN'))
				{
                  //exit('No direct script access allowed');
				}
             }
         }
$page = empty($_GET['page']) ? 'index' : $_GET['page'];
//////////
if (!is_array($CALL_SYSTEM))
{
	$CALL_SYSTEM = array();
}
	$CALL_SYSTEM['INFO']               = 	true;
	$CALL_SYSTEM['CORE']                = 	true;
	$CALL_SYSTEM['ONLINE']             = 	true;
	$CALL_SYSTEM['BANNED']             = 	true;
	$CALL_SYSTEM['GROUP']              = 	true;
	$CALL_SYSTEM['MEMBER']             = 	true;
	$CALL_SYSTEM['PAGES']              = 	true;
	$CALL_SYSTEM['REPLY']              = 	true;
	$CALL_SYSTEM['SECTION']            = 	true;
	$CALL_SYSTEM['STYLE']              = 	true;
	$CALL_SYSTEM['SUBJECT']            = 	true;
	$CALL_SYSTEM['ICONS']              = 	true;
	$CALL_SYSTEM['LANG']               = 	true;
	if(CLASS_NAME == 'PowerBBChatMOD'  or $page == 'chat'){$CALL_SYSTEM['CHAT'] = true;}
	$CALL_SYSTEM['MODERATORS']         = 	true;
	$CALL_SYSTEM['CACHE']              = 	true;


	if ($page != 'index')
	{
	$CALL_SYSTEM['MISC']               = 	true;
	$CALL_SYSTEM['ATTACH']             = 	true;
	$CALL_SYSTEM['FILESEXTENSION']     = 	true;
	$CALL_SYSTEM['USERTITLE']          = 	true;
	$CALL_SYSTEM['REQUEST']            = 	true;
	$CALL_SYSTEM['WARNLOG']            = 	true;
	$CALL_SYSTEM['EXTRAFIELD']         = 	true;
	$CALL_SYSTEM['RATING']             = 	true;
	$CALL_SYSTEM['SUPERMEMBERLOGS']    = 	true;
	$CALL_SYSTEM['MESSAGE']            = 	true;
	$CALL_SYSTEM['EMAILED']            = 	true;
	$CALL_SYSTEM['TAG']                = 	true;
	$CALL_SYSTEM['TAG_SUBJECT']        = 	true;
	$CALL_SYSTEM['PM']                 = 	true;
	$CALL_SYSTEM['FRIENDS']            = 	true;
	$CALL_SYSTEM['VISITORMESSAGE']     = 	true;
	}

	if ($page == 'forum')
	{
	$CALL_SYSTEM['ANNOUNCEMENT']       = 	true;
	$CALL_SYSTEM['SEARCH']             = 	true;
	}
	if ($page == 'search')
	{
	$CALL_SYSTEM['SEARCH']             = 	true;
	}

	if ($page == 'usercp')
	{
	$CALL_SYSTEM['REPUTATION']         = 	true;
	$CALL_SYSTEM['CUSTOM_BBCODE']      = 	true;
	}

	if ($page == 'new_topic'
	or $page == 'topic'
	or $page == 'new_reply'
	or $page == 'usercp'
	or $page == 'pm_show'
	or $page == 'pm_send'
	or $page == 'management'
	or $page == 'managementreply'
	or $page == 'vote'
	or $page == 'profile')
	{
	$CALL_SYSTEM['TOOLBOX']            = 	true;
	$CALL_SYSTEM['TOPICMOD']           = 	true;
	$CALL_SYSTEM['POLL']               = 	true;
	$CALL_SYSTEM['VOTE']               = 	true;
	$CALL_SYSTEM['USERRATING']         = 	true;
	$CALL_SYSTEM['AVATAR']             = 	true;
	$CALL_SYSTEM['AWARD']              = 	true;
	$CALL_SYSTEM['CUSTOM_BBCODE']      = 	true;

	}


	if (defined('IN_ADMIN'))
	{
	$CALL_SYSTEM['TEMPLATESEDITS']     = 	true;
	$CALL_SYSTEM['EMAILMESSAGES']      = 	true;
	$CALL_SYSTEM['FIXUP']              = 	true;
	$CALL_SYSTEM['AWARD']              = 	true;
	$CALL_SYSTEM['CUSTOM_BBCODE']      = 	true;
	$CALL_SYSTEM['USERRATING']         = 	true;
	$CALL_SYSTEM['USERTITLE']          = 	true;
	$CALL_SYSTEM['ADDONS']             = 	true;
	$CALL_SYSTEM['HOOKS']              = 	true;
	$CALL_SYSTEM['ADSENSE']            = 	true;
	}

// Can't live without this file :)
require_once('pbboard.class.php');

// The master object
$PowerBB = new PowerBB;

//////////
	if (!defined('INSTALL'))
	{
		if (defined('IN_ADMIN'))
		{
		require_once($config['Misc']['admincpdir'].'/modulescp/common.module.php');
		}
		else
		{
		require_once('modules/common.module.php');
		}
	 }


//////////
class PowerBBLocalCommon
{
	function run()
	{
		global $PowerBB;

    	  // Set the important variables for the system
		// Important variables , all important variables should store in _CONF array
		$PowerBB->_CONF['member_permission']		=	false;
 		$PowerBB->_CONF['param']					=	array();
 		$PowerBB->_CONF['rows']					=	array();
        $PowerBB->_CONF['temp']['query_numbers'] =	0;
 		$PowerBB->_CONF['temp']['queries']		=	array();
 		$PowerBB->_CONF['template']				=	array();
 		$PowerBB->_CONF['template']['while']		=	array();
 		$PowerBB->_CONF['template']['foreach']	=	array();
 		//////////
 		// Make life easy for developers :)
 		//$PowerBB->DB->SetDebug(true);
 		//$PowerBB->DB->SetQueriesStore(true);
 		//////////
        if(isset($PowerBB->_POST['ajax']))
 		{
 		define('STOP_STYLE',$PowerBB->_POST['ajax'] ? true : false);
 		//////////
 		}
	}


}
//////////
$local_common = new PowerBBLocalCommon();
$local_common->run();
//////////
$common = new PowerBBCommon();
$common->run();
//////////
?>