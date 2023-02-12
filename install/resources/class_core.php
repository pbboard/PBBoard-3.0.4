<?php
/**
 * PBBoard 3.0.4
 * Copyright 2019 PBBoard Group, All Rights Reserved
 *
 * Website: https://pbboard.info
 * License: https://pbboard.info/about/license
 *
 */

class PBBoard {
	/**
	 * The friendly version number of PBBoard we're running.
	 *
	 * @var string
	 */
	public $version = "3.0.4";

	/**
	 * The version code of PBBoard we're running.
	 *
	 * @var integer
	 */
	public $version_code = 3004;

	/**
	 * The current working directory.
	 *
	 * @var string
	 */
	public $cwd = ".";

	/**
	 * Input variables received from the outer world.
	 *
	 * @var array
	 */
	public $input = array();

	/**
	 * Cookie variables received from the outer world.
	 *
	 * @var array
	 */
	public $cookies = array();

	/**
	 * Information about the current user.
	 *
	 * @var array
	 */
	public $user = array();

	/**
	 * Information about the current usergroup.
	 *
	 * @var array
	 */
	public $usergroup = array();

	/**
	 * PBBoard settings.
	 *
	 * @var array
	 */
	public $settings = array();

	/**
	 * Whether or not magic quotes are enabled.
	 *
	 * @var int
	 */
	public $magicquotes = 0;

	/**
	 * Whether or not PBBoard supports SEO URLs
	 *
	 * @var boolean
	 */
	public $seo_support = false;

	/**
	 * PBBoard configuration.
	 *
	 * @var array
	 */
	public $config = array();

	/**
	 * The request method that called this page.
	 *
	 * @var string
	 */
	public $request_method = "";

	/**
	 * Whether or not PHP's safe_mode is enabled
	 *
	 * @var boolean
	 */
	public $safemode = false;

	/**
	 * Loads templates directly from the master theme and disables the installer locked error
	 *
	 * @var boolean
	 */
	public $dev_mode = false;

	/**
	 * Variables that need to be clean.
	 *
	 * @var array
	 */
	public $clean_variables = array(
		"int" => array(
			"tid", "pid", "uid",
			"eid", "pmid", "fid",
			"aid", "rid", "sid",
			"vid", "cid", "bid",
			"hid", "gid", "mid",
			"wid", "lid", "iid",
			"did", "qid", "id"
		),
		"pos" => array(
			"page", "perpage"
		),
		"a-z" => array(
			"sortby", "order"
		)
	);

	/**
	 * Variables that are to be ignored from cleansing process
	 *
	 * @var array
	 */
	public $ignore_clean_variables = array();

	/**
	 * Using built in shutdown functionality provided by register_shutdown_function for < PHP 5?
	 *
	 * @var bool
	 */
	public $use_shutdown = true;

	/**
	 * Debug mode?
	 *
	 * @var bool
	 */
	public $debug_mode = false;

	/**
	 * Binary database fields need to be handled differently
	 *
	 * @var array
	 */
	public $binary_fields = array(
		'adminlog' => array('ipaddress' => true),
		'adminsessions' => array('ip' => true),
		'maillogs' => array('ipaddress' => true),
		'moderatorlog' => array('ipaddress' => true),
		'posts' => array('ipaddress' => true),
		'privatemessages' => array('ipaddress' => true),
		'searchlog' => array('ipaddress' => true),
		'sessions' => array('ip' => true),
		'threadratings' => array('ipaddress' => true),
		'users' => array('regip' => true, 'lastip' => true),
		'spamlog' => array('ipaddress' => true),
	);

	/**
	 * The cache instance to use.
	 *
	 * @var datacache
	 */
	public $cache;

	/**
	 * The base URL to assets.
	 *
	 * @var string
	 */
	public $asset_url = null;
	/**
	 * String input constant for use with get_input().
	 *
	 * @see get_input
	 */
	const INPUT_STRING = 0;
	/**
	 * Integer input constant for use with get_input().
	 *
	 * @see get_input
	 */
	const INPUT_INT = 1;
	/**
	 * Array input constant for use with get_input().
	 *
	 * @see get_input
	 */
	const INPUT_ARRAY = 2;
	/**
	 * Float input constant for use with get_input().
	 *
	 * @see get_input
	 */
	const INPUT_FLOAT = 3;
	/**
	 * Boolean input constant for use with get_input().
	 *
	 * @see get_input
	 */
	const INPUT_BOOL = 4;

	/**
	 * Constructor of class.
	 */
	function __construct()
	{
		// Set up PBBoard
		$protected = array("_GET", "_POST", "_SERVER", "_COOKIE", "_FILES", "_ENV", "GLOBALS");
		foreach($protected as $var)
		{
			if(isset($_POST[$var]) || isset($_GET[$var]) || isset($_COOKIE[$var]) || isset($_FILES[$var]))
			{
				die("Hacking attempt");
			}
		}

		if(defined("IGNORE_CLEAN_VARS"))
		{
			if(!is_array(IGNORE_CLEAN_VARS))
			{
				$this->ignore_clean_variables = array(IGNORE_CLEAN_VARS);
			}
			else
			{
				$this->ignore_clean_variables = IGNORE_CLEAN_VARS;
			}
		}

		// Determine input
		$this->parse_incoming($_GET);
		$this->parse_incoming($_POST);

		if($_SERVER['REQUEST_METHOD'] == "POST")
		{
			$this->request_method = "post";
		}
		else if($_SERVER['REQUEST_METHOD'] == "GET")
		{
			$this->request_method = "get";
		}

		// If we've got register globals on, then kill them too
		if(@ini_get("register_globals") == 1)
		{
			$this->unset_globals($_POST);
			$this->unset_globals($_GET);
			$this->unset_globals($_FILES);
			$this->unset_globals($_COOKIE);
		}
		$this->clean_input();

		$safe_mode_status = @ini_get("safe_mode");
		if($safe_mode_status == 1 || strtolower($safe_mode_status) == 'on')
		{
			$this->safemode = true;
		}

		// Are we running on a development server?
		if(isset($_SERVER['PBB_DEV_MODE']) && $_SERVER['PBB_DEV_MODE'] == 1)
		{
			$this->dev_mode = 1;
		}

		// Are we running in debug mode?
		if(isset($this->input['debug']) && $this->input['debug'] == 1)
		{
			$this->debug_mode = true;
		}

		if(isset($this->input['action']) && $this->input['action'] == "pbb_logo")
		{
			require_once dirname(__FILE__)."/pbboard_group.php";
			output_logo();
		}

		if(isset($this->input['intcheck']) && $this->input['intcheck'] == 1)
		{
			die("PBBoard");
		}
	}

      /**
	 * Checks the input data type before usage.
	 *
	 * @param string $name Variable name ($pbboard->input)
	 * @param int $type The type of the variable to get. Should be one of PBBoard::INPUT_INT, PBBoard::INPUT_ARRAY or PBBoard::INPUT_STRING.
	 *
	 * @return int|float|array|string Checked data. Type depending on $type
	 */
	function get_input($name, $type = PBBoard::INPUT_STRING)
	{
		switch($type)
		{
			case PBBoard::INPUT_ARRAY:
				if(!isset($this->input[$name]) || !is_array($this->input[$name]))
				{
					return array();
				}
				return $this->input[$name];
			case PBBoard::INPUT_INT:
				if(!isset($this->input[$name]) || !is_numeric($this->input[$name]))
				{
					return 0;
				}
				return (int)$this->input[$name];
			case PBBoard::INPUT_FLOAT:
				if(!isset($this->input[$name]) || !is_numeric($this->input[$name]))
				{
					return 0.0;
				}
				return (float)$this->input[$name];
			case PBBoard::INPUT_BOOL:
				if(!isset($this->input[$name]) || !is_scalar($this->input[$name]))
				{
					return false;
				}
				return (bool)$this->input[$name];
			default:
				if(!isset($this->input[$name]) || !is_scalar($this->input[$name]))
				{
					return '';
				}
				return $this->input[$name];
		}
	}

	/**
	 * Parses the incoming variables.
	 *
	 * @param array $array The array of incoming variables.
	 */
	function parse_incoming($array)
	{
		if(!is_array($array))
		{
			return;
		}

		foreach($array as $key => $val)
		{
			$this->input[$key] = $val;
		}
	}

	/**
	 * Cleans predefined input variables.
	 *
	 */
	function clean_input()
	{
		foreach($this->clean_variables as $type => $variables)
		{
			foreach($variables as $var)
			{
				// If this variable is in the ignored array, skip and move to next.
				if(in_array($var, $this->ignore_clean_variables))
				{
					continue;
				}

				if(isset($this->input[$var]))
				{
					switch($type)
					{
						case "int":
							$this->input[$var] = $this->get_input($var, PBBoard::INPUT_INT);
							break;
						case "a-z":
							$this->input[$var] = preg_replace("#[^a-z\.\-_]#i", "", $this->get_input($var));
							break;
						case "pos":
							if(($this->input[$var] < 0 && $var != "page") || ($var == "page" && $this->input[$var] != "last" && $this->input[$var] < 0))
								$this->input[$var] = 0;
							break;
					}
				}
			}
		}
	}
	function __destruct()
	{
		// Run shutdown function
		if(function_exists("run_shutdown"))
		{
			run_shutdown();
		}
	}
}

?>