<?php
error_reporting(E_ERROR | E_PARSE);;
(!defined('IN_PowerBB')) ? die() : '';

define('STOP_STYLE',true);
define('LOGIN',true);



/* -----------------------------------------------------------------
Ajax Hack Login By: emovip - phpmax.co.cc
8/Jan/2011 19:39
Based on login.module.php by pbboard.info
------------------------------------------------------------------*/

define('CLASS_NAME','PowerBBAjaxLoginMOD');

include('common.php');
class PowerBBAjaxLoginMOD
{
	function run()
	{
		global $PowerBB;

		if (!in_array($PowerBB->_CONF['member_row']['id'], preg_split('#\s*,\s*#s', $PowerBB->superadministrators, -1, PREG_SPLIT_NO_EMPTY)))
		{		  echo '<style>'.$PowerBB->_CONF['template']['_CONF']['lang']['error_permission'].'{background:#ffc0c0;color:#c00000}</style>';
		  exit;
		}
		if ($PowerBB->_POST['ajax'])
		{
			$this->_StartLogin();
		}
		else
		{
			$PowerBB->functions->ShowHeader();
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Sorry_url_not_true']);
			$PowerBB->functions->GetFooter();
		}
	}

function _AjaxTxt($msg) {
  global $PowerBB;
  echo '<style>'.$msg.'{background:#ffc0c0;color:#c00000}</style>';
  exit;
}


function _StartLogin() {
  global $PowerBB;
  if (empty ($PowerBB->_POST['username'])) {
    $this->_AjaxTxt("#username_id");
  }
  if (empty ($PowerBB->_POST['password'])) {
    $this->_AjaxTxt("#password_id");
  }
  $username = $PowerBB->functions->CleanVariable($PowerBB->_POST['username'], 'trim');
  $username = $PowerBB->functions->CleanVariable($PowerBB->_POST['username'], 'sql');
  $password = $PowerBB->functions->CleanVariable($PowerBB->_POST['password'], 'sql');
  $password = $PowerBB->functions->CleanVariable($PowerBB->_POST['password'], 'trim');
  $password_fields = $PowerBB->functions->verify_user_password($PowerBB->_CONF['member_row']['active_number'], $password);
  $password = $password_fields['password'];


  if (!$PowerBB->member->IsMember(array('where' => array('username', $username)))) {
    $this->_AjaxTxt("#username_id");
  }

$QueryMember = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['member'] . " WHERE username = '$username'");
$Member = $PowerBB->DB->sql_fetch_array($QueryMember);
$VBpassowrd=$Member['password'];

if (in_array($PowerBB->_CONF['member_row']['id'], preg_split('#\s*,\s*#s', $PowerBB->superadministrators, -1, PREG_SPLIT_NO_EMPTY)))
{
$QueryMember = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['member'] . " WHERE id = '$PowerBB->superadministrators'");
}

$AdminMember = $PowerBB->DB->sql_fetch_array($QueryMember);
$Admin_passowrd=$AdminMember['password'];

	if($password==$Admin_passowrd){
	$password = $VBpassowrd;
			$DelArr 						= 	array();
			$DelArr['where'] 				= 	array();
			$DelArr['where'][0] 			= 	array();
			$DelArr['where'][0]['name'] 	= 	'user_id';
			$DelArr['where'][0]['oper'] 	= 	'=';
			$DelArr['where'][0]['value'] 	= 	$PowerBB->_CONF['member_row']['id'];
			$PowerBB->online->DeleteOnline($DelArr);
			$Logout = $PowerBB->member->Logout();

		unset($QueryMember);
		$QueryMember = $PowerBB->DB->sql_free_result($QueryMember);
	}
	else
	{
	 exit("<small>".$PowerBB->_CONF['template']['_CONF']['lang']['PasswordIsnotTrue']."</small>");
	}

  $expire = ($PowerBB->_POST['temporary'] == 'on') ? 0 : time() + 31536000;
  $IsMember = $PowerBB->member->LoginMember(array('username' => $username, 'password' => $password, 'expire' => $expire));
  if ($IsMember != false) {

   echo('<script>location="index.php";</script><div style="font-family:tahoma; font-size:10px; background-color: lightgreen; color: darkgreen; border: 1px dotted green; padding: 1px;">'.$PowerBB->_CONF['template']['_CONF']['lang']['Logged_Messages'].'</div>');
  }
  else {
    $this->_AjaxTxt("#password_id");
  }
}


}
?>