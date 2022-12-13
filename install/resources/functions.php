<?php
/**
 * PBBoard 3.3
 * Copyright 2019 PBBoard Group, All Rights Reserved
 *
 * Website: http://www.PBBoard.info
 * License: https://www.pbboard.info/about/license
 *
 */

function my_setcookie($name, $value="", $expires="", $httponly=false)
{
	global $PBBoard;

	if(!$PBBoard->settings['cookiepath'])
	{
		$PBBoard->settings['cookiepath'] = "/";
	}

	if($expires == -1)
	{
		$expires = 0;
	}
	elseif($expires == "" || $expires == null)
	{
		$expires = TIME_NOW + (60*60*24*365); // Make the cookie expire in a years time
	}
	else
	{
		$expires = TIME_NOW + (int)$expires;
	}

	$PBBoard->settings['cookiepath'] = str_replace(array("\n","\r"), "", $PBBoard->settings['cookiepath']);
	$PBBoard->settings['cookiedomain'] = str_replace(array("\n","\r"), "", $PBBoard->settings['cookiedomain']);
	$PBBoard->settings['cookieprefix'] = str_replace(array("\n","\r", " "), "", $PBBoard->settings['cookieprefix']);

	// Versions of PHP prior to 5.2 do not support HttpOnly cookies and IE is buggy when specifying a blank domain so set the cookie manually
	$cookie = "Set-Cookie: {$PBBoard->settings['cookieprefix']}{$name}=".urlencode($value);

	if($expires > 0)
	{
		$cookie .= "; expires=".@gmdate('D, d-M-Y H:i:s \\G\\M\\T', $expires);
	}

	if(!empty($PBBoard->settings['cookiepath']))
	{
		$cookie .= "; path={$PBBoard->settings['cookiepath']}";
	}

	if(!empty($PBBoard->settings['cookiedomain']))
	{
		$cookie .= "; domain={$PBBoard->settings['cookiedomain']}";
	}

	if($httponly == true)
	{
		$cookie .= "; HttpOnly";
	}

	$PBBoard->cookies[$name] = $value;

	header($cookie, false);
}

/**
 * Unset a cookie set by PBBoard.
 *
 * @param string $name The cookie identifier.
 */
function my_unsetcookie($name)
{
	global $PBBoard;

	$expires = -3600;
	my_setcookie($name, "", $expires);

	unset($PBBoard->cookies[$name]);
}

function username_exists($username)
{
	$options = array(
		'username_method' => 2
	);

	return (bool)get_user_by_username($username, $options);
}


function get_user_by_username($username, $options=array())
{
	global $PBBoard, $db;

	$username = $db->escape_string(my_strtolower($username));
	if(!isset($options['username_method']))
	{
		$options['username_method'] = 0;
	}

	switch($db->type)
	{
		case 'mysql':
		case 'mysqli':
			$field = 'username';
			$efield = 'email';
			break;
		default:
			$field = 'LOWER(username)';
			$efield = 'LOWER(email)';
			break;
	}

	switch($options['username_method'])
	{
		case 1:
			$sqlwhere = "{$efield}='{$username}'";
			break;
		case 2:
			$sqlwhere = "{$field}='{$username}' OR {$efield}='{$username}'";
			break;
		default:
			$sqlwhere = "{$field}='{$username}'";
			break;
	}

	$fields = array('id');
	if(isset($options['fields']))
	{
		$fields = array_merge((array)$options['fields'], $fields);
	}

	$query = $db->simple_select('member', implode(',', array_unique($fields)), $sqlwhere, array('limit' => 1));

	if(isset($options['exists']))
	{
		return (bool)$db->num_rows($query);
	}

	return $db->fetch_array($query);
}

function validate_password_from_uid($uid, $password, $user = array())
{
	global $db, $PBBoard;
	if(isset($PBBoard->user['id']) && $PBBoard->user['id'] == $uid)
	{
		$user = $PBBoard->user;
	}
	if(!$user['password'])
	{
		$query = $db->simple_select("member", "id,username,password,usergroup", "id='".$uid."'");
		$user = $db->fetch_array($query);
	}

	if(md5($password) == $user['password'])
	{
		return $user;
	}
	else
	{
		return false;
	}
}

function my_strtolower($string)
{
	if(function_exists("mb_strtolower"))
	{
		$string = mb_strtolower($string);
	}
	else
	{
		$string = strtolower($string);
	}

	return $string;
}

/**
 * Finds a needle in a haystack and returns it position, mb strings accounted for
 *
 * @param string $haystack String to look in (haystack)
 * @param string $needle What to look for (needle)
 * @param int $offset (optional) How much to offset
 * @return int|bool false on needle not found, integer position if found
 */
function my_strpos($haystack, $needle, $offset=0)
{
	if($needle == '')
	{
		return false;
	}

	if(function_exists("mb_strpos"))
	{
		$position = mb_strpos($haystack, $needle, $offset);
	}
	else
	{
		$position = strpos($haystack, $needle, $offset);
	}

	return $position;
}

function get_ip()
{
	global $PBBoard, $plugins;

	$ip = strtolower($_SERVER['REMOTE_ADDR']);

	if($PBBoard->settings['ip_forwarded_check'])
	{
		$addresses = array();

		if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
		{
			$addresses = explode(',', strtolower($_SERVER['HTTP_X_FORWARDED_FOR']));
		}
		elseif(isset($_SERVER['HTTP_X_REAL_IP']))
		{
			$addresses = explode(',', strtolower($_SERVER['HTTP_X_REAL_IP']));
		}

		if(is_array($addresses))
		{
			foreach($addresses as $val)
			{
				$val = trim($val);
				// Validate IP address and exclude private addresses
				if(my_inet_ntop(my_inet_pton($val)) == $val && !preg_match("#^(10\.|172\.(1[6-9]|2[0-9]|3[0-1])\.|192\.168\.|fe80:|fe[c-f][0-f]:|f[c-d][0-f]{2}:)#", $val))
				{
					$ip = $val;
					break;
				}
			}
		}
	}

	if(!$ip)
	{
		if(isset($_SERVER['HTTP_CLIENT_IP']))
		{
			$ip = strtolower($_SERVER['HTTP_CLIENT_IP']);
		}
	}

	if($plugins)
	{
		$ip_array = array("ip" => &$ip); // Used for backwards compatibility on this hook with the updated run_hooks() function.
		$plugins->run_hooks("get_ip", $ip_array);
	}

	return $ip;
}

function my_inet_pton($ip)
{
	if(function_exists('inet_pton'))
	{
		return @inet_pton($ip);
	}
	else
	{
		/**
		 * Replace inet_pton()
		 *
		 * @category    PHP
		 * @package     PHP_Compat
		 * @license     LGPL - http://www.gnu.org/licenses/lgpl.html
		 * @copyright   2004-2007 Aidan Lister <aidan@php.net>, Arpad Ray <arpad@php.net>
		 * @link        http://php.net/inet_pton
		 * @author      Arpad Ray <arpad@php.net>
		 * @version     $Revision: 269597 $
		 */
		$r = ip2long($ip);
		if($r !== false && $r != -1)
		{
			return pack('N', $r);
		}

		$delim_count = substr_count($ip, ':');
		if($delim_count < 1 || $delim_count > 7)
		{
			return false;
		}

		$r = explode(':', $ip);
		$rcount = count($r);
		if(($doub = array_search('', $r, 1)) !== false)
		{
			$length = (!$doub || $doub == $rcount - 1 ? 2 : 1);
			array_splice($r, $doub, $length, array_fill(0, 8 + $length - $rcount, 0));
		}

		$r = array_map('hexdec', $r);
		array_unshift($r, 'n*');
		$r = call_user_func_array('pack', $r);

		return $r;
	}
}

function htmlspecialchars_uni($message)
{
	$message = preg_replace("#&(?!\#[0-9]+;)#si", "&amp;", $message); // Fix & but allow unicode
	$message = str_replace("<", "&lt;", $message);
	$message = str_replace(">", "&gt;", $message);
	$message = str_replace("\"", "&quot;", $message);
	return $message;
}

function generate_loginkey()
{
	return random_str(50);
}

function random_str($length=8, $complex=false)
{
	$set = array_merge(range(0, 9), range('A', 'Z'), range('a', 'z'));
	$str = array();

	// Complex strings have always at least 3 characters, even if $length < 3
	if($complex == true)
	{
		// At least one number
		$str[] = $set[my_rand(0, 9)];

		// At least one big letter
		$str[] = $set[my_rand(10, 35)];

		// At least one small letter
		$str[] = $set[my_rand(36, 61)];

		$length -= 3;
	}

	for($i = 0; $i < $length; ++$i)
	{
		$str[] = $set[my_rand(0, 61)];
	}

	// Make sure they're in random order and convert them to a string
	shuffle($str);

	return implode($str);
}

function my_rand($min=null, $max=null, $force_seed=false)
{
	static $seeded = false;
	static $obfuscator = 0;


	if($min !== null && $max !== null)
	{
		$distance = $max - $min;
		if($distance > 0)
		{
			return $min + (int)((float)($distance + 1) * (float)(mt_rand() ^ $obfuscator) / (mt_getrandmax() + 1));
		}
		else
		{
			return mt_rand($min, $max);
		}
	}
	else
	{
		$val = mt_rand() ^ $obfuscator;
		return $val;
	}
}


/**
 * Converts a packed internet address to a human readable representation
 *
 * @param string $ip IP in 32bit or 128bit binary format
 * @return string IP in human readable format
 */
function my_inet_ntop($ip)
{
	if(function_exists('inet_ntop'))
	{
		return @inet_ntop($ip);
	}
	else
	{
		/**
		 * Replace inet_ntop()
		 *
		 * @category    PHP
		 * @package     PHP_Compat
		 * @license     LGPL - http://www.gnu.org/licenses/lgpl.html
		 * @copyright   2004-2007 Aidan Lister <aidan@php.net>, Arpad Ray <arpad@php.net>
		 * @link        http://php.net/inet_ntop
		 * @author      Arpad Ray <arpad@php.net>
		 * @version     $Revision: 269597 $
		 */
		switch(strlen($ip))
		{
			case 4:
				list(,$r) = unpack('N', $ip);
				return long2ip($r);
			case 16:
				$r = substr(chunk_split(bin2hex($ip), 4, ':'), 0, -1);
				$r = preg_replace(
					array('/(?::?\b0+\b:?){2,}/', '/\b0+([^0])/e'),
					array('::', '(int)"$1"?"$1":"0$1"'),
					$r);
				return $r;
		}
		return false;
	}
}

/**
 * Get the current location taking in to account different web serves and systems
 *
 * @param boolean $fields True to return as "hidden" fields
 * @param array $ignore Array of fields to ignore if first argument is true
 * @param boolean $quick True to skip all inputs and return only the file path part of the URL
 * @return string The current URL being accessed
 */
function get_current_location($fields=false, $ignore=array(), $quick=false)
{
	if(defined("PBB_LOCATION"))
	{
		return PBB_LOCATION;
	}

	if(!empty($_SERVER['SCRIPT_NAME']))
	{
		$location = htmlspecialchars_uni($_SERVER['SCRIPT_NAME']);
	}
	elseif(!empty($_SERVER['PHP_SELF']))
	{
		$location = htmlspecialchars_uni($_SERVER['PHP_SELF']);
	}
	elseif(!empty($_ENV['PHP_SELF']))
	{
		$location = htmlspecialchars_uni($_ENV['PHP_SELF']);
	}
	elseif(!empty($_SERVER['PATH_INFO']))
	{
		$location = htmlspecialchars_uni($_SERVER['PATH_INFO']);
	}
	else
	{
		$location = htmlspecialchars_uni($_ENV['PATH_INFO']);
	}

	if($quick)
	{
		return $location;
	}

	if($fields == true)
	{
		global $PBBoard;

		if(!is_array($ignore))
		{
			$ignore = array($ignore);
		}

		$form_html = '';
		if(!empty($PBBoard->input))
		{
			foreach($PBBoard->input as $name => $value)
			{
				if(in_array($name, $ignore) || is_array($name) || is_array($value))
				{
					continue;
				}

				$form_html .= "<input type=\"hidden\" name=\"".htmlspecialchars_uni($name)."\" value=\"".htmlspecialchars_uni($value)."\" />\n";
			}
		}

		return array('location' => $location, 'form_html' => $form_html, 'form_method' => $PBBoard->request_method);
	}
	else
	{
		if(isset($_SERVER['QUERY_STRING']))
		{
			$location .= "?".htmlspecialchars_uni($_SERVER['QUERY_STRING']);
		}
		else if(isset($_ENV['QUERY_STRING']))
		{
			$location .= "?".htmlspecialchars_uni($_ENV['QUERY_STRING']);
		}

		if((isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "POST") || (isset($_ENV['REQUEST_METHOD']) && $_ENV['REQUEST_METHOD'] == "POST"))
		{
			$post_array = array('action', 'fid', 'pid', 'tid', 'uid', 'eid');

			foreach($post_array as $var)
			{
				if(isset($_POST[$var]))
				{
					$addloc[] = urlencode($var).'='.urlencode($_POST[$var]);
				}
			}

			if(isset($addloc) && is_array($addloc))
			{
				if(strpos($location, "?") === false)
				{
					$location .= "?";
				}
				else
				{
					$location .= "&amp;";
				}
				$location .= implode("&amp;", $addloc);
			}
		}

		return $location;
	}
}

function sprin_tf($string)
	{
		$arg_list = func_get_args();
		$num_args = count($arg_list);

		for($i = 1; $i < $num_args; $i++)
		{
			$string = str_replace('{'.$i.'}', $arg_list[$i], $string);
		}

		return $string;
	}