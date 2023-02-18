<?php
error_reporting(E_ERROR | E_PARSE);
define('SAFEMODE', (ini_get('safe_mode') == 1 OR strtolower(ini_get('safe_mode')) == 'on') ? true : false);
@set_time_limit(0);
@ini_set('max_execution_time', 123456);
$Generatekey = @sha1(@microtime());
$_SESSION['csrf'] = $Generatekey;
define('PBB_ROOT', dirname(dirname(__FILE__))."/");
define("INSTALL_ROOT", dirname(__FILE__)."/");
define("TIME_NOW", time());
define('IN_PBBoard', 1);
define("IN_UPGRADE", 1);

if(function_exists('date_default_timezone_set') && !ini_get('date.timezone'))
{
	date_default_timezone_set('GMT');
}

//require_once PBB_ROOT.'includes/class_error.php';
//$error_handler = new errorHandler();

require_once INSTALL_ROOT."resources/functions.php";

require_once INSTALL_ROOT.'resources/class_core.php';
$PBBoard = new PBBoard;

$input = array();

if(file_exists(PBB_ROOT."/includes/config.php"))
{
require_once PBB_ROOT."includes/config.php";
}
else
{
if(file_exists(PBB_ROOT."/engine/config.php"))
{
require_once PBB_ROOT."engine/config.php";
}
}
$orig_config = $config;

if(!is_array($config['database']))
{
	$config['database'] = array(
		"type" => 'mysqli',
		"database" => $config['db']['name'],
		"table_prefix" => $config['db']['prefix'],
		"hostname" => $config['db']['server'],
		"username" => $config['db']['username'],
		"password" => $config['db']['password'],
		"encoding" => 'utf8mb4',
	);
}
$PBBoard->config = &$config;

// Include the files necessary for installation
require_once PBB_ROOT."includes/class_timers.php";
require_once PBB_ROOT."includes/class_xml.php";




// Load DB interface
require_once PBB_ROOT."includes/db_base.php";
require_once PBB_ROOT."includes/db_mysqli.php";
$db = new DB_MySQLi;
 $config['db']['dbtype'] = 'mysqli';

// Connect to Database
	$connect_array = array(
		"hostname" => $config['db']['server'],
		"username" => $config['db']['username'],
		"password" => $config['db']['password'] ,
		"database" => $config['db']['name'],
		"encoding" => 'utf8mb4'
	);
	define('TABLE_PREFIX', $config['db']['prefix']);
	$db->connect($connect_array);
	$db->set_table_prefix(TABLE_PREFIX);
	$db->type = 'mysqli';


require_once PBB_ROOT."includes/class_session.php";
$session = new session;
$session->init();
$PBBoard->session = $_COOKIE["TestCookie"];



// Include the installation resources
if(isset($_COOKIE['pbb_insall_lang']) == 'ar')
{
require_once INSTALL_ROOT.'resources/output_ar.php';
}
else
{
require_once INSTALL_ROOT.'resources/output.php';
}

$output = new installerOutput;

require_once INSTALL_ROOT.'resources/class_language.php';
$lang = new MyLanguage();
$lang->set_path(PBB_ROOT.'install/resources/');
if(isset($_COOKIE['pbb_insall_lang']))
{
$lang->load($_COOKIE['pbb_insall_lang']);
$output->loadlang = $_COOKIE['pbb_insall_lang'];
}
else
{
$lang->load('en');
}


$output->script = "upgrade.php";
$output->title = "PBBoard Upgrade Wizard";
$output->title = $lang->title_upgrade;
	function get_languag($c_lang)
	{
	   global $output, $lang, $PBBoard;

		if($c_lang)
		 {
		  setcookie('pbb_insall_lang', $c_lang, time() + (86400 * 30), "/"); // 86400 = 1 day
		  echo "<META HTTP-EQUIV=\"refresh\" CONTENT=\"0; URL=upgrade.php\">\n";
		 }
	}

	if(!empty($_POST['selectlang']))
	 {
	 	get_languag($_POST['selectlang']);

	 }
   if(!isset($_COOKIE['pbb_insall_lang']))
	{
	 $output->print_header($lang->choose_languag, "errormsg", 0);
	 echo $lang->choose_a_language_upgrade;
     $output->print_footer();
     $lang->load($_COOKIE['pbb_insall_lang']);
     exit();
	}

if(file_exists("lock"))
{
	$output->print_error($lang->locked);
}
else
{
	$PBBoard->input['action'] = $PBBoard->get_input('action');

	if($PBBoard->input['action'] == "logout" && $PBBoard->user['id'])
	{
		// Check session ID if we have one
		if($PBBoard->get_input('logoutkey') != $PBBoard->user['logoutkey'])
		{
			$output->print_error($lang->check_logoutkey);
		}

		my_unsetcookie("PBBoarduser");

		if($PBBoard->user['id'])
		{
			$time = TIME_NOW;
			$lastvisit = array(
				"lastvisit" => $time,
			);
			$db->update_query("member", $lastvisit, "id='".$PBBoard->user['id']."'");
		}
		header("Location: upgrade.php");
	}
	elseif($PBBoard->input['action'] == "do_login" && $PBBoard->request_method == "post")
	{

		if(!username_exists($PBBoard->get_input('username')))
		{
			$output->print_error($lang->username_invalid);
		}

		   $options = array(
				'fields' => array('username', 'password', 'usergroup')
			);
		$user = get_user_by_username($PBBoard->get_input('username'), $options);
		$user['loginkey'] = $user['logged'];
        $PBBoard->user = $user;
        $PBBoard->user['loginkey'] = $user['logged'];

		if(!$user['id'])
		{
			$output->print_error($lang->username_invalid);
		}
		else
		{
			$user = validate_password_from_uid($user['id'], $PBBoard->get_input('password'), $user);
			if(!$user['id'])
			{
				$output->print_error($lang->password_incorrect);
			}
		}

	//	@setcookie("PBBoarduser", $user['id']."_".$user['logged'], null, true);

		//header("Location: upgrade.php");
	}
    elseif($PBBoard->input['action'] == "upgrade303_update_section_cache")
	{
		 require_once INSTALL_ROOT."resources/upg_303.php";
		 upgrade303_update_section_cache();
		 exit();
	}
    elseif($PBBoard->input['action'] == "upgrade303_finish_upgrade")
	{
		 require_once INSTALL_ROOT."resources/upg_303.php";
		 upgrade303_finish_upgrade();
		 exit();
	}
    elseif($PBBoard->input['action'] == "upgrade303_convert_utf8")
	{
		 require_once INSTALL_ROOT."resources/upg_303.php";
		 upgrade303_convert_utf8();
		 exit();
	}
	elseif($PBBoard->input['action'] == "upgrade304_finish_upgrade")
	{
		 require_once INSTALL_ROOT."resources/upg_304_finish_upgrade.php";
		 upgrade304_finish_upgrade();
		 exit();
	}
    elseif($PBBoard->input['action'] == "doupgrade")
	{
      $info_query = $db->query("SELECT * FROM " . $config['db']['prefix'] . "info WHERE var_name='MySBB_version'");
      $info_row   = $db->fetch_array($info_query);
      $version = $info_row['value'];
      	if($version == '2.1.4')
      	{
	       echo "<META HTTP-EQUIV=\"refresh\" CONTENT=\"0; URL=resources/upg_300.php?step=0\">\n";
      	}
      	elseif($version == '3.0.0')
      	{
	       echo "<META HTTP-EQUIV=\"refresh\" CONTENT=\"0; URL=resources/upg_301.php?step=0\">\n";
      	}
      	elseif($version == '3.0.1')
      	{
	       echo "<META HTTP-EQUIV=\"refresh\" CONTENT=\"0; URL=resources/upg_302.php?step=0\">\n";
      	}
      	elseif($version == '3.0.2')
      	{
		 require_once INSTALL_ROOT."resources/upg_303.php";
		 upgrade303_dbchanges();
		 exit();
      	}
      	elseif($version == '3.0.3')
      	{
	       echo "<META HTTP-EQUIV=\"refresh\" CONTENT=\"0; URL=resources/upg_304.php?step=1\">\n";
      	}
      	elseif($version == '3.0.4')
      	{
      	$output->steps = array($lang->upgrade);
         $output->print_header($lang->no_latest_upgraded.$version);
         echo $lang->done_step_upgraded_success;
         $output->print_footer();
         exit();
      	}
	}
	$output->steps = array($lang->upgrade);

	if($PBBoard->user['id'] == 0)
	{
		$output->print_header($lang->please_login, "errormsg", 0, 1);

		$output->print_contents('<p>'.$lang->login_desc.'</p>
<form action="upgrade.php" method="post">
	<div class="border_wrapper">
		<table class="general" cellspacing="0">
		<thead>
			<tr>
				<th colspan="2" class="first last">'.$lang->login.'</th>
			</tr>
		</thead>
		<tbody>
			<tr class="first">
				<td class="first">'.$lang->login_username.':</td>
				<td class="last alt_col"><input type="text" class="textbox" name="username" size="25" maxlength="'.$PBBoard->settings['maxnamelength'].'" style="width: 200px;" /></td>
			</tr>
			<tr class="alt_row last">
				<td class="first">'.$lang->login_password.':<br /><small>'.$lang->login_password_desc.'</small></td>
				<td class="last alt_col"><input type="password" class="textbox" name="password" size="25" style="width: 200px;" /></td>
			</tr>
		</tbody>
		</table>
	</div>
	<div id="next_button">
		<input type="submit" class="submit_button" name="submit" value="'.$lang->login.'" />
		<input type="hidden" name="action" value="do_login" />
	</div>
</form>');
		$output->print_footer("");

		exit;
	}
	elseif($user['usergroup'] != 1)
	{
		$output->print_error($lang->sprintf($lang->no_permision, $PBBoard->user['logoutkey']));
	}




    $host   = $config['db']['server'];
    $user   = $config['db']['username'];
    $pass   = $config['db']['password'];
    $db     = $config['db']['name'];
    $prefix = $config['db']['prefix'];
    $dbtype = 'mysqli';
    $superadministrators =        $config['SpecialUsers']['superadministrators'];
    $admincpdir = $config['Misc']['admincpdir'];
    $HOOKS = $config['HOOKS']['DISABLE_HOOKS'];

	// Write the configuration file
	$configdata = "<?php
/**
 * DATABASE TYPE
 *
 * Please see the PBBoard Docs for advanced
 * database configuration for larger installations
 * https://pbboard.info/
 */

\$config['db']['name'] = '".$db."';
\$config['db']['server'] = '".$host."';
\$config['db']['username'] = '".$user."';
\$config['db']['password'] = '".$pass."';
\$config['db']['prefix'] = '".$prefix."';
\$config['db']['dbtype'] = '".$dbtype."';


/**
 * Admin CP directory
 *  For security reasons, it is recommended you
 *  rename your Admin CP directory. You then need
 *  to adjust the value below to point to the
 *  new directory.
 */

\$config['Misc']['admincpdir'] = '".$admincpdir."';

/**
 * To disable the plugin/hooks system
 */

\$config['HOOKS']['DISABLE_HOOKS'] = '".$HOOKS."';

/**
 * Super Administrators
 *  A comma separated list of user IDs who cannot
 *  be edited, deleted or banned in the Admin CP.
 *  The administrator permissions for these users
 *  cannot be altered either.
 */

\$config['SpecialUsers']['superadministrators'] = '".$superadministrators."';

/**
* The database encoding
*/

\$config['db']['encoding'] = 'utf8mb4';

?>";

	$file = fopen(PBB_ROOT.'includes/config.php', 'w');
	fwrite($file, $configdata);
	fclose($file);

	$output->print_header();
	$output->print_contents($lang->sprintf($lang->upgrade_welcome, $PBBoard->version));
	$output->print_footer("doupgrade");
}

