<?php
error_reporting(E_ERROR | E_PARSE);
/**
 * PBBoard  3
 * Copyright 2019 PBBoard  Group, All Rights Reserved
 *
 * Website: https://pbboard.info
 * License: https://pbboard.info/about/license
 *
 */

// Set to 1 if receiving a blank page (template failure).
define("MANUAL_WARNINGS", 0);

// Define Custom PBBoard  error handler constants with a value not used by php's error handler.
define("PBB_SQL", 20);
define("PBB_TEMPLATE", 30);
define("PBB_GENERAL", 40);
define("PBB_NOT_INSTALLED", 41);
define("PBB_NOT_UPGRADED", 42);
define("PBB_INSTALL_DIR_EXISTS", 43);
define("PBB_SQL_LOAD_ERROR", 44);
define("PBB_CACHE_NO_WRITE", 45);
define("PBB_CACHEHANDLER_LOAD_ERROR", 46);

if(!defined("E_RECOVERABLE_ERROR"))
{
	// This constant has been defined since PHP 5.2.
	define("E_RECOVERABLE_ERROR", 4096);
}

if(!defined("E_DEPRECATED"))
{
	// This constant has been defined since PHP 5.3.
	define("E_DEPRECATED", 8192);
}

if(!defined("E_USER_DEPRECATED"))
{
	// This constant has been defined since PHP 5.3.
	define("E_USER_DEPRECATED", 16384);
}

class errorHandler {

	/**
	 * Array of all of the error types
	 *
	 * @var array
	 */
	public $error_types = array(
		E_ERROR							=> 'Error',
		E_WARNING						=> 'Warning',
		E_PARSE							=> 'Parsing Error',
		E_NOTICE						=> 'Notice',
		E_CORE_ERROR					=> 'Core Error',
		E_CORE_WARNING					=> 'Core Warning',
		E_COMPILE_ERROR					=> 'Compile Error',
		E_COMPILE_WARNING				=> 'Compile Warning',
		E_DEPRECATED					=> 'Deprecated Warning',
		E_USER_ERROR					=> 'User Error',
		E_USER_WARNING					=> 'User Warning',
		E_USER_NOTICE					=> 'User Notice',
		E_USER_DEPRECATED	 			=> 'User Deprecated Warning',
		E_STRICT						=> 'Runtime Notice',
		E_RECOVERABLE_ERROR				=> 'Catchable Fatal Error',
		PBB_SQL 						=> 'PBBoard  SQL Error',
		PBB_TEMPLATE					=> 'PBBoard  Template Error',
		PBB_GENERAL					=> 'PBBoard  Error',
		PBB_NOT_INSTALLED				=> 'PBBoard  Error',
		PBB_NOT_UPGRADED				=> 'PBBoard  Error',
		PBB_INSTALL_DIR_EXISTS			=> 'PBBoard  Error',
		PBB_SQL_LOAD_ERROR				=> 'PBBoard  Error',
		PBB_CACHE_NO_WRITE				=> 'PBBoard  Error',
		PBB_CACHEHANDLER_LOAD_ERROR	=> 'PBBoard  Error',
	);

	/**
	 * Array of PBBoard  error types
	 *
	 * @var array
	 */
	public $PBB_error_types = array(
		PBB_SQL,
		PBB_TEMPLATE,
		PBB_GENERAL,
		PBB_NOT_INSTALLED,
		PBB_NOT_UPGRADED,
		PBB_INSTALL_DIR_EXISTS,
		PBB_SQL_LOAD_ERROR,
		PBB_CACHE_NO_WRITE,
		PBB_CACHEHANDLER_LOAD_ERROR,
	);

	/**
	 * Array of all of the error types to ignore
	 *
	 * @var array
	 */
	public $ignore_types = array(
		E_DEPRECATED,
		E_NOTICE,
		E_USER_NOTICE,
		E_STRICT
	);

	/**
	 * String of all the warnings collected
	 *
	 * @var string
	 */
	public $warnings = "";

	/**
	 * Is PBBoard  in an errornous state? (Have we received an error?)
	 *
	 * @var boolean
	 */
	public $has_errors = false;

	/**
	 * Initializes the error handler
	 *
	 */
	function __construct()
	{
		// Lets set the error handler in here so we can just do $handler = new errorHandler() and be all set up.
		$error_types = E_ALL;
		foreach($this->ignore_types as $bit)
		{
			$error_types = $error_types & ~$bit;
		}
		error_reporting($error_types);
		set_error_handler(array(&$this, "error"), $error_types);
	}

	/**
	 * Parses a error for processing.
	 *
	 * @param string $type The error type (i.e. E_ERROR, E_FATAL)
	 * @param string $message The error message
	 * @param string $file The error file
	 * @param integer $line The error line
	 * @return boolean True if parsing was a success, otherwise assume a error
	 */
	function error($type, $message, $file=null, $line=0)
	{
		global $PBBoard;

		// Error reporting turned off (either globally or by @ before erroring statement)
		if(error_reporting() == 0)
		{
			return true;
		}

		if(in_array($type, $this->ignore_types))
		{
			return true;
		}

		$file = str_replace(PBB_ROOT, "", $file);

		$this->has_errors = true;

		// For some reason in the installer this setting is set to "<"
		$accepted_error_types = array('both', 'error', 'warning', 'none');
		if(!in_array(isset($PBBoard->settings['errortypemedium']), $accepted_error_types))
		{
			$PBBoard->settings['errortypemedium'] = "both";
		}



		// Saving error to log file.
		if(isset($PBBoard->settings['errorlogmedium']) == "log" || isset($PBBoard->settings['errorlogmedium']) == "both")
		{
			$this->log_error($type, $message, $file, $line);
		}

		// Are we emailing the Admin a copy?
		if(isset($PBBoard->settings['errorlogmedium']) == "mail" || isset($PBBoard->settings['errorlogmedium']) == "both")
		{
			$this->email_error($type, $message, $file, $line);
		}

		// SQL Error
		if($type == PBB_SQL)
		{
			$this->output_error($type, $message, $file, $line);
		}
		else
		{
			// Do we have a PHP error?
			if(my_strpos(my_strtolower($this->error_types[$type]), 'warning') === false)
			{
				$this->output_error($type, $message, $file, $line);
			}
			// PHP Error
			else
			{
				if(isset($PBBoard->settings['errortypemedium']) == "none" || isset($PBBoard->settings['errortypemedium']) == "error")
				{
					//echo "<div class=\"php_warning\">PBBoard  Internal: One or more warnings occurred. Please contact your administrator for assistance.</div>";
				}
				else
				{
					global $templates;

					$warning = "<strong>{$this->error_types[$type]}</strong> [$type] $message - Line: $line - File: $file PHP ".PHP_VERSION." (".PHP_OS.")<br />\n";
					if(is_object($templates) && method_exists($templates, "get") && !defined("IN_ADMINCP"))
					{
						$this->warnings .= $warning;
						$this->warnings .= $this->generate_backtrace();
					}
					else
					{
						echo "<div class=\"php_warning\">{$warning}".$this->generate_backtrace()."</div>";
					}
				}
			}
		}

		return true;
	}



	/**
	 * Triggers a user created error
	 * Example: $error_handler->trigger("Some Warning", E_USER_ERROR);
	 *
	 * @param string $message Message
	 * @param string|int $type Type
	 */
	function trigger($message="", $type=E_USER_ERROR)
	{
		global $lang;

		if(!$message)
		{
			$message = $lang->unknown_user_trigger;
		}

		if(in_array($type, $this->PBB_error_types))
		{
			$this->error($type, $message);
		}
		else
		{
			trigger_error($message, $type);
		}
	}

	/**
	 * Logs the error in the specified error log file.
	 *
	 * @param string $type Warning type
	 * @param string $message Warning message
	 * @param string $file Warning file
	 * @param integer $line Warning line
	 */
	function log_error($type, $message, $file, $line)
	{
		global $PBBoard;

		if($type == PBB_SQL)
		{
			$message = "SQL Error: {$message['error_no']} - {$message['error']}\nQuery: {$message['query']}";
		}

		// Do not log something that might be executable
		$message = str_replace('<?', '< ?', $message);

		$error_data = "<error>\n";
		$error_data .= "\t<dateline>".TIME_NOW."</dateline>\n";
		$error_data .= "\t<script>".$file."</script>\n";
		$error_data .= "\t<line>".$line."</line>\n";
		$error_data .= "\t<type>".$type."</type>\n";
		$error_data .= "\t<friendly_type>".$this->error_types[$type]."</friendly_type>\n";
		$error_data .= "\t<message>".$message."</message>\n";
		$error_data .= "</error>\n\n";

		if(trim($PBBoard->settings['errorloglocation']) != "")
		{
			@error_log($error_data, 3, $PBBoard->settings['errorloglocation']);
		}
		else
		{
			@error_log($error_data, 0);
		}
	}

	/**
	 * Emails the error in the specified error log file.
	 *
	 * @param string $type Warning type
	 * @param string $message Warning message
	 * @param string $file Warning file
	 * @param integer $line Warning line
	 * @return bool returns false if no admin email is set
	 */
	function email_error($type, $message, $file, $line)
	{
		global $PBBoard;

		if(!$PBBoard->settings['adminemail'])
		{
			return false;
		}

		if($type == PBB_SQL)
		{
			$message = "SQL Error: {$message['error_no']} - {$message['error']}\nQuery: {$message['query']}";
		}

		$message = "Your copy of PBBoard  running on {$PBBoard->settings['bbname']} ({$PBBoard->settings['bburl']}) has experienced an error. Details of the error include:\n---\nType: $type\nFile: $file (Line no. $line)\nMessage\n$message";

		@my_mail($PBBoard->settings['adminemail'], "PBBoard  error on {$PBBoard->settings['bbname']}", $message, $PBBoard->settings['adminemail']);

		return true;
	}

	/**
	 * @param string $type
	 * @param string $message
	 * @param string $file
	 * @param int $line
	 */
	function output_error($type, $message, $file, $line)
	{
		global $PBBoard, $parser, $lang;

		if(!$PBBoard->settings['bbname'])
		{
			$PBBoard->settings['bbname'] = "PBBoard";
		}

		if($type == PBB_SQL)
		{
			$title = "PBBoard  SQL Error";
			$error_message = "<p>PBBoard  has experienced an internal SQL error and cannot continue.</p>";
			if(isset($PBBoard->settings['errortypemedium']) == "both" || isset($PBBoard->settings['errortypemedium']) == "error" || defined("IN_INSTALL") || defined("IN_UPGRADE"))
			{
				$message['query'] = htmlspecialchars_uni($message['query']);
				$message['error'] = htmlspecialchars_uni($message['error']);
				$error_message .= "<dl>\n";
				$error_message .= "<dt>SQL Error:</dt>\n<dd>{$message['error_no']} - {$message['error']}</dd>\n";
				if($message['query'] != "")
				{
					$error_message .= "<dt>Query:</dt>\n<dd>{$message['query']}</dd>\n";
				}
				$error_message .= "</dl>\n";
			}
		}
		else
		{
			$title = "PBBoard  Internal Error";
			$error_message = "<p>PBBoard  has experienced an internal error and cannot continue.</p>";
			if(isset($PBBoard->settings['errortypemedium']) == "both" || isset($PBBoard->settings['errortypemedium']) == "error" || defined("IN_INSTALL") || defined("IN_UPGRADE"))
			{
				$error_message .= "<dl>\n";
				$error_message .= "<dt>Error Type:</dt>\n<dd>{$this->error_types[$type]} ($type)</dd>\n";
				$error_message .= "<dt>Error Message:</dt>\n<dd>{$message}</dd>\n";
				if(!empty($file))
				{
					$error_message .= "<dt>Location:</dt><dd>File: {$file}<br />Line: {$line}</dd>\n";
					if(!@preg_match('#config\.php|settings\.php#', $file) && @file_exists($file))
					{
						$code_pre = @file($file);

						$code = "";

						if(isset($code_pre[$line-4]))
						{
							$code .= $line-3 . ". ".$code_pre[$line-4];
						}

						if(isset($code_pre[$line-3]))
						{
							$code .= $line-2 . ". ".$code_pre[$line-3];
						}

						if(isset($code_pre[$line-2]))
						{
							$code .= $line-1 . ". ".$code_pre[$line-2];
						}

						$code .= $line . ". ".$code_pre[$line-1]; // The actual line.

						if(isset($code_pre[$line]))
						{
							$code .= $line+1 . ". ".$code_pre[$line];
						}

						if(isset($code_pre[$line+1]))
						{
							$code .= $line+2 . ". ".$code_pre[$line+1];
						}

						if(isset($code_pre[$line+2]))
						{
							$code .= $line+3 . ". ".$code_pre[$line+2];
						}

						unset($code_pre);

						$parser_exists = false;

						if($parser_exists)
						{
							$code = $parser->mycode_parse_php($code, true);
						}
						else
						{
							$code = @nl2br($code);
						}

						$error_message .= "<dt>Code:</dt><dd>{$code}</dd>\n";
					}
				}
				$backtrace = $this->generate_backtrace();
				if($backtrace && !in_array($type, $this->PBB_error_types))
				{
					$error_message .= "<dt>Backtrace:</dt><dd>{$backtrace}</dd>\n";
				}
				$error_message .= "</dl>\n";
			}
		}

		if(isset($lang->settings['charset']))
		{
			$charset = $lang->settings['charset'];
		}
		else
		{
			$charset = 'UTF-8';
		}

		if(!headers_sent() && !defined("IN_INSTALL") && !defined("IN_UPGRADE"))
		{
			@header('HTTP/1.1 503 Service Temporarily Unavailable');
			@header('Status: 503 Service Temporarily Unavailable');
			@header('Retry-After: 1800');
			@header("Content-type: text/html; charset={$charset}");
			$file_name = basename(__FILE__);

			echo <<<EOF
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>{$PBBoard->settings['bbname']} - Internal Error</title>
	<style type="text/css">
		body { background: #efefef; color: #000; font-family: Tahoma,Verdana,Arial,Sans-Serif; font-size: 12px; text-align: center; line-height: 1.4; }
		a:link { color: #026CB1; text-decoration: none;	}
		a:visited {	color: #026CB1;	text-decoration: none; }
		a:hover, a:active {	color: #000; text-decoration: underline; }
		#container { width: 600px; padding: 20px; background: #fff;	border: 1px solid #e4e4e4; margin: 100px auto; text-align: left; -moz-border-radius: 6px; -webkit-border-radius: 6px; border-radius: 6px; }
		h1 { margin: 0; background: url({$file_name}?action=PBB_logo) no-repeat;	height: 82px; width: 248px; }
		#content { border: 1px solid #026CB1; background: #fff; -moz-border-radius: 3px; -webkit-border-radius: 3px; border-radius: 3px; }
		h2 { font-size: 12px; padding: 4px; background: #026CB1; color: #fff; margin: 0; }
		.invisible { display: none; }
		#error { padding: 6px; }
		#footer { font-size: 12px; border-top: 1px dotted #DDDDDD; padding-top: 10px; }
		dt { font-weight: bold; }
	</style>
</head>
<body>
	<div id="container">
		<div id="logo">
			<h1><a href="https://pbboard.info/" title="PBBoard"><span class="invisible">PBBoard</span></a></h1>
		</div>

		<div id="content">
			<h2>{$title}</h2>

			<div id="error">
				{$error_message}
				<p id="footer">Please contact the <a href="https://pbboard.info">PBBoard  Group</a> for technical support.</p>
			</div>
		</div>
	</div>
</body>
</html>
EOF;
		}
		else
		{
			echo <<<EOF
	<style type="text/css">
		#PBB_error_content { border: 1px solid #026CB1; background: #fff; -moz-border-radius: 3px; -webkit-border-radius: 3px; border-radius: 3px; }
		#PBB_error_content a:link { color: #026CB1; text-decoration: none;	}
		#PBB_error_content a:visited {	color: #026CB1;	text-decoration: none; }
		#PBB_error_content a:hover, a:active {	color: #000; text-decoration: underline; }
		#PBB_error_content h2 { font-size: 12px; padding: 4px; background: #026CB1; color: #fff; margin: 0; border-bottom: none; }
		#PBB_error_error { padding: 6px; }
		#PBB_error_footer { font-size: 12px; border-top: 1px dotted #DDDDDD; padding-top: 10px; }
		#PBB_error_content dt { font-weight: bold; }
	</style>
	<div id="PBB_error_content">
		<h2>{$title}</h2>
		<div id="PBB_error_error">
		{$error_message}
			<p id="PBB_error_footer">Please contact the <a href="https://pbboard.info">PBBoard  Group</a> for technical support.</p>
		</div>
	</div>
EOF;
		}
		exit(1);
	}

	/**
	 * Generates a backtrace if the server supports it.
	 *
	 * @return string The generated backtrace
	 */
	function generate_backtrace()
	{
		$backtrace = '';
		if(function_exists("debug_backtrace"))
		{
			$trace = debug_backtrace();
			$backtrace = "<table style=\"width: 100%; margin: 10px 0; border: 1px solid #aaa; border-collapse: collapse; border-bottom: 0;\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\n";
			$backtrace .= "<thead><tr>\n";
			$backtrace .= "<th style=\"border-bottom: 1px solid #aaa; background: #ccc; padding: 4px; text-align: left; font-size: 11px;\">File</th>\n";
			$backtrace .= "<th style=\"border-bottom: 1px solid #aaa; background: #ccc; padding: 4px; text-align: left; font-size: 11px;\">Line</th>\n";
			$backtrace .= "<th style=\"border-bottom: 1px solid #aaa; background: #ccc; padding: 4px; text-align: left; font-size: 11px;\">Function</th>\n";
			$backtrace .= "</tr></thead>\n<tbody>\n";

			// Strip off this function from trace
			array_shift($trace);

			foreach($trace as $call)
			{
				if(empty($call['file'])) $call['file'] = "[PHP]";
				if(empty($call['line'])) $call['line'] = "&nbsp;";
				if(!empty($call['class'])) $call['function'] = $call['class'].$call['type'].$call['function'];
				$call['file'] = str_replace(PBB_ROOT, "/", $call['file']);
				$backtrace .= "<tr>\n";
				$backtrace .= "<td style=\"font-size: 11px; padding: 4px; border-bottom: 1px solid #ccc;\">{$call['file']}</td>\n";
				$backtrace .= "<td style=\"font-size: 11px; padding: 4px; border-bottom: 1px solid #ccc;\">{$call['line']}</td>\n";
				$backtrace .= "<td style=\"font-size: 11px; padding: 4px; border-bottom: 1px solid #ccc;\">{$call['function']}</td>\n";
				$backtrace .= "</tr>\n";
			}
			$backtrace .= "</tbody></table>\n";
		}
		return $backtrace;
	}
}
