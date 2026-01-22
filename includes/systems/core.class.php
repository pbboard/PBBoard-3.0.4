<?php

class PowerBBCore
{
	var $id;
	var $PowerBB;

	function __construct($PowerBB)
	{
		$this->Engine = $PowerBB;
	}

 	/**
 	 * Insert new Core
 	 *
 	 * @param :
 	 */
 	function Insert($param,$table)
 	{
		global $PowerBB;

  		if (!isset($param)
  			or !is_array($param))
 		{
 			$param = array();
 		}

		$query = $this->Engine->records->Insert($PowerBB->prefix.$table,$param['field']);

		if (isset($param['get_id']))
		{
			$this->id = $this->Engine->DB->sql_insert_id();
		}

		return ($query) ? true : false;
 	}


	function GetNumber($param,$table)
	{
		global $PowerBB;
		if (!isset($param))
		{
			$param 	= array();
		}

		$param['select'] 	= 	'*';
		$param['from'] 		= 	$PowerBB->prefix.$table;

		$num = $this->Engine->records->GetNumber($param);

		return $num;
	}


 	/**
 	 * Update Core information
 	 *
 	 * @param :
 	 *			long list :\
 	 */
 	function Update($param,$table)
 	{
		global $PowerBB;

  		if (!isset($param)
  			or !is_array($param))
 		{
 			$param = array();
 		}

		$query = $this->Engine->records->Update($PowerBB->prefix.$table,$param['field'],$param['where']);

		return ($query) ? true : false;
 	}

	function Deleted($param,$table)
	{
		global $PowerBB;

  		if (!isset($param)
  			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['table'] = $PowerBB->prefix.$table;

		$del = $this->Engine->records->Delete($param);

		return ($del) ? true : false;
	}
 	/**
 	 * Get Core list
 	 *
 	 * @param :
 	 *			sql_statment	->	to complete SQL query
 	 */
	function GetList($param,$table)
	{
		global $PowerBB;

 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

 		$param['from'] 		= 	$PowerBB->prefix.$table;

 	 	$rows = $this->Engine->records->GetList($param);

		return $rows;
  	 }

	function GetListAdvanced($param,$table,$join_primary_letter)
	{
	    if (!isset($param) || !is_array($param)) {
	        $param = array();
	    }

	    if (empty($param['select'])) {
	        $param['select'] = ''.$join_primary_letter.'.*';
	    }

	    if (empty($param['from'])) {
	        $param['from'] = $PowerBB->prefix.$table . ' AS '.$join_primary_letter.'';
	    }

        $rows = $this->Engine->records->GetList($param);

	    return $rows;
	}

	/**
	 * Set the correct Core for member or user
	 *
	 * @return : the information about the correct Core
	 */
	function GetInfo($param,$table)
	{
		global $PowerBB;
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['select'] 	= 	'*';
		$param['from'] 		= 	$PowerBB->prefix.$table;

		$rows = $this->Engine->records->GetInfo($param);

		return $rows;
	}


 	function Is($param,$table)
	{
		global $PowerBB;

 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['select'] 	= 	'*';
		$param['from'] 		= 	$PowerBB->prefix.$table;

		$num = $this->Engine->records->GetNumber($param);

		return ($num <= 0) ? false : true;
	}

	function NewVisit($param,$table)
	{
		global $PowerBB;
		if (empty($param['clicks'])
			and $param['clicks'] != 0)
		{
			trigger_error('ERROR::NEED_PARAMETER -- FROM NewVisit() -- EMPTY clicks',E_USER_ERROR);
		}

		$param['field'] = array();
		$param['field']['clicks'] = $param['clicks'] + 1;

		$update = $this->Update($param,$table);

		return ($update) ? true : false;
	}

 	function CreateStyleCache($param,$table)
 	{
		global $PowerBB;
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$style 	= 	$this->GetInfo($param,$table);
		$cache 	= 	'';

		if ($style != false)
		{
			$cache = array();

			$cache['style_path'] 		= 	$style['style_path'];
			$cache['image_path'] 		= 	$style['image_path'];

			$cache = base64_encode(json_encode($cache));
		}
		else
		{
			return false;
		}

		return $cache;
 	}

	function GetRequestInfo($param,$table)
	{
		global $PowerBB;
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['select'] 	= 	'*';
		$param['from'] 		= 	$PowerBB->prefix.$table;

		if (!empty($param['code'])
			and !empty($param['type'])
			and !empty($param['username']))
		{
			$param['where'] 				= 	array();

			$param['where'][0] 				= 	array();
			$param['where'][0]['name'] 		= 	'random_url';
			$param['where'][0]['oper'] 		= 	'=';
			$param['where'][0]['value'] 	= 	$param['code'];

			$param['where'][1] 				= 	array();
			$param['where'][1]['con'] 		= 	'AND';
			$param['where'][1]['name'] 		= 	'request_type';
			$param['where'][1]['oper'] 		= 	'=';
			$param['where'][1]['value'] 	= 	$param['type'];

			$param['where'][2] 				= 	array();
			$param['where'][2]['con'] 		= 	'AND';
			$param['where'][2]['name'] 		= 	'username';
			$param['where'][2]['oper'] 		= 	'=';
			$param['where'][2]['value'] 	= 	$param['username'];
		}

		$rows = $this->Engine->records->GetInfo($param);

		return $rows;
	}

	function MessageProccess($param,$table)
	{
		global $PowerBB;

		$search_array 		= 	array();
		$replace_array 		= 	array();

		$search_array[]		=	'[MySBB]username[/MySBB]';
		$replace_array[]	=	$param['username'];

		$search_array[]		=	'[MySBB]board_title[/MySBB]';
		$replace_array[]	=	$param['title'];

		$search_array[]		=	'[MySBB]url[/MySBB]';
		$replace_array[]	=	$param['active_url'];

		$search_array[]		=	'[MySBB]change_url[/MySBB]';
		$replace_array[]	=	$param['change_url'];

		$search_array[]		=	'[MySBB]cancel_url[/MySBB]';
		$replace_array[]	=	$param['cancel_url'];

		$text = str_replace($search_array,$replace_array,$param['text']);

		return $text;
	}

	function Create_last_posts_cache($param, $time, $limit)
	{
	    global $PowerBB;

	    if (!isset($param) or !is_array($param)) {
	        $param = array();
	    }

	    $table = 'subject';
	    $param['select'] = '*';
	    $param['from']   = $PowerBB->prefix . $table;
	    $param['order']  = array('field' => 'write_time', 'type' => 'DESC');
	    $param['limit']  = $limit;

	    $Posts = $this->Engine->records->GetList($param);

	    if ($Posts) {
	        $cache   = array();
	        $x       = 0;
	        $numb    = sizeof($Posts);
	        $n       = min((int)$limit, (int)$numb);
	        $perpage = (int)$this->Engine->_CONF['info_row']['perpage'];

	        while ($x < $n) {
	            $InfSectionID = (int)$Posts[$x]['section'];

	            if ($InfSectionID) {
	                $l_last_reply = 0;
	                $countpage    = 1;
	                $reply_count  = (int)$Posts[$x]['reply_number'];

	                if ($reply_count > 0) {
	                    $sql_r = "SELECT id FROM " . $PowerBB->table['reply'] . "
	                              WHERE subject_id = " . (int)$Posts[$x]['id'] . "
	                              AND delete_topic = 0 AND review_reply = 0
	                              ORDER BY write_time DESC, id DESC LIMIT 1";

	                    $last_reply = $PowerBB->DB->sql_fetch_array($PowerBB->DB->sql_query($sql_r));

	                    if (isset($last_reply['id'])) {
	                        $l_last_reply = $last_reply['id'];
	                    }

	                    if ($reply_count >= $perpage) {
	                        $countpage = ceil(($reply_count) / $perpage);
	                    }
	                }

	                $cache[$x] = array(
	                    'id'                 => $Posts[$x]['id'],
	                    'section'            => $Posts[$x]['section'],
	                    'writer'             => $this->Engine->DB->sql_escape($Posts[$x]['writer']),
	                    'title'              => $this->Engine->DB->sql_escape($Posts[$x]['title']),
	                    'review_reply'       => $Posts[$x]['review_reply'],
	                    'write_time'         => $Posts[$x]['write_time'],
	                    'icon'               => $Posts[$x]['icon'],
	                    'visitor'            => $Posts[$x]['visitor'],
	                    'last_replier'       => $Posts[$x]['last_replier'],
	                    'sec_subject'        => $Posts[$x]['sec_subject'],
	                    'review_subject'     => $Posts[$x]['review_subject'],
	                    'prefix_subject'     => $Posts[$x]['prefix_subject'],
	                    'native_write_time'  => $Posts[$x]['native_write_time'],
	                    'special'            => $Posts[$x]['special'],
	                    'reply_number'       => $reply_count,
	                    'last_berpage_nm'    => $countpage,
	                    'last_reply'         => $l_last_reply
	                );

	                $last_writer = (!empty($Posts[$x]['last_replier'])) ? $Posts[$x]['last_replier'] : $Posts[$x]['writer'];

	                $rows = $this->Engine->member->GetMemberInfo(array(
	                    'get_from' => 'db',
	                    'where'    => array('username', $this->Engine->DB->sql_escape($last_writer))
	                ));

	                $cache[$x]['last_writer_id']       = isset($rows['id']) ? $rows['id'] : 0;
	                $cache[$x]['avater_path']          = isset($rows['avater_path']) ? $rows['avater_path'] : '';
	                $cache[$x]['username_style_cache'] = isset($rows['username_style_cache']) ? $rows['username_style_cache'] : '';
	            }
	            $x++;
	        }

	        $cache_data = serialize(array_filter($cache));
	        $this->Engine->info->UpdateInfo(array('var_name' => 'last_time_cache', 'value' => $time));
	        $update = $this->Engine->info->UpdateInfo(array('var_name' => 'last_posts_cache', 'value' => $cache_data));

	        return ($update) ? true : false;

	    } else {
	        $this->Engine->info->UpdateInfo(array('var_name' => 'last_time_cache', 'value' => $time));
	        $this->Engine->info->UpdateInfo(array('var_name' => 'last_posts_cache', 'value' => ''));
	        return false;
	    }
	}

     /**
 	 * Get the Server Protocol http or https
 	 */
 	function GetServerProtocol()
 	{
 		global $PowerBB;
		// Get server port
		if (isset($PowerBB->_SERVER['HTTPS']) &&
		    ($PowerBB->_SERVER['HTTPS'] == 'on' || $PowerBB->_SERVER['HTTPS'] == 1) ||
		    isset($PowerBB->_SERVER['HTTP_X_FORWARDED_PROTO']) &&
		    $PowerBB->_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
		  $protocol = 'https://';
		}
		else {
		  $protocol = 'http://';
		}

 		return $protocol;
 	}

	function GetChatWriterInfo()
	{
       global $PowerBB;

		$SQL = 'SELECT m.id , m.username_style_cache, m.avater_path, m.user_country, p.id, p.username, p.message, p.user_id, p.date
		FROM ' . $this->Engine->table['chat'] . ' AS p INNER JOIN  ' . $this->Engine->table['member'] . ' AS m
		ON p.user_id =  m.id
		ORDER BY p.id ASC LIMIT 0, ' . $this->Engine->_CONF['info_row']['chat_message_num'] . ' ';

		$result = $this->Engine->DB->sql_query($SQL);
		while($Chat_row = $this->Engine->DB->sql_fetch_array($result)) {
			$ChatList [] = $Chat_row;
		}

 		return $ChatList;

	}


}

?>