<?php
class PowerBBModerators
{
	var $id;
	var $PowerBB;

	function __construct($PowerBB)
	{
		$this->Engine = $PowerBB;
	}
	function InsertModerator($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$query = $this->Engine->records->Insert($this->Engine->table['moderators'],$param['field']);

		if (isset($param['get_id']))
		{
			$this->id = $this->Engine->DB->sql_insert_id();
		}

		return ($query) ? true : false;
	}

 	function UpdateModerator($param)
 	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$query = $this->Engine->records->Update($this->Engine->table['moderators'],$param['field'],$param['where']);

		return ($query) ? true : false;
 	}

	function DeleteModerator($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['table'] = $this->Engine->table['moderators'];

		$del = $this->Engine->records->Delete($param);

		return ($del) ? true : false;
	}

	function GetModeratorList($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

 		$param['select'] 	= 	'*';
 		$param['from']		=	$this->Engine->table['moderators'];

		$rows = $this->Engine->records->GetList($param);

		return $rows;
	}

	function GetModeratorInfo($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['select'] 	= 	'*';
		$param['from']		=	$this->Engine->table['moderators'];

		$rows = $this->Engine->records->GetInfo($param);

		return $rows;
	}

 	function GetModeratorsNumber($param)
 	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['select'] 	= 	'*';
		$param['from'] 		= 	$this->Engine->table['moderators'];

		$num   = $this->Engine->records->GetNumber($param);

		return $num;
 	}
 	function CreateModeratorsCache($param)
 	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}
		$moderators = $this->GetModeratorList($param);
 		$cache 	= 	array();
 		$x		=	0;
 		$n		=	sizeof($moderators);
		while ($x < $n)
		{
			$cache[$x] 					= 	array();
			$cache[$x]['id']		 	= 	$moderators[$x]['id'];
			$cache[$x]['section_id'] 	= 	$moderators[$x]['section_id'];
			$cache[$x]['member_id'] 	= 	$moderators[$x]['member_id'];
			$cache[$x]['username'] 		= 	$moderators[$x]['username'];

			$MemberArr 							= 	array();
			$MemberArr['get_from'] 				= 	'db';
			$MemberArr['where'] 					= 	array('id',$moderators[$x]['member_id']);
			$rows = $this->Engine->member->GetMemberInfo($MemberArr);

			$cache[$x]['avater_path'] 		    = 	$rows['avater_path'];
			$cache[$x]['username_style_cache']  = 	$rows['username_style_cache'];

			$x += 1;
		}
		  $cache = json_encode($cache,JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
		return $cache;
 	}
 	function IsModerator($param)
 	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}
 		$InfoArr = array();
 		$InfoArr['where'] = array();
 		if (isset($param['username']))
 		{
 			$InfoArr['where'][0] 			= 	array();
 			$InfoArr['where'][0]['name'] 	= 	'username';
 			$InfoArr['where'][0]['oper']	=	'=';
 			$InfoArr['where'][0]['value'] 	= 	$param['username'];
 		}
 		elseif (isset($param['member_id']))
 		{
 			$InfoArr['where'][0] 			= 	array();
 			$InfoArr['where'][0]['name'] 	= 	'member_id';
 			$InfoArr['where'][0]['oper']	=	'=';
 			$InfoArr['where'][0]['value'] 	= 	$param['member_id'];
 		}
 		$InfoArr['where'][1] 			= 	array();
 		$InfoArr['where'][1]['con'] 	= 	'AND';
 		$InfoArr['where'][1]['name'] 	= 	'section_id';
 		$InfoArr['where'][1]['value'] 	= 	$param['section_id'];
 		$Info = $this->GetModeratorInfo($InfoArr);
 		return is_array($Info) ? true : false;
 	}
	function IfModerator($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}
		$param['select'] 	= 	'*';
		$param['from'] 		= 	$this->Engine->table['moderators'];
		$num = $this->Engine->records->GetNumber($param);
		return ($num <= 0) ? false : true;
	}
}

?>
