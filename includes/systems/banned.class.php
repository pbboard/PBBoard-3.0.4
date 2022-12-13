<?php

class PowerBBBanned
{
	var $PowerBB;

	function __construct($PowerBB)
	{
		$this->Engine = $PowerBB;
	}

	function IsUsernameBanned($param)
	{
		if (empty($param['username']))
		{
			trigger_error('ERROR::NEED_PARAMETER -- FROM IsUsernameBanned() -- EMPTY username',E_USER_ERROR);
		}

		$query_array 			= 	array();
		$query_array['text'] 	= 	$param['username'];
		$query_array['type'] 	= 	1;

		$num = $this->_BaseQueryNum($query_array);

		return ($num <= 0) ? false : true;
	}

 	function IsEmailBanned($param)
 	{
		if (empty($param['email']))
		{
			trigger_error('ERROR::NEED_PARAMETER -- FROM IsEmailBanned() -- EMPTY email',E_USER_ERROR);
		}

 		$query_array 			= 	array();
		$query_array['text'] 	= 	$param['email'];
		$query_array['type'] 	= 	2;

		$num = $this->_BaseQueryNum($query_array);

 		return ($num <= 0) ? false : true;
 	}

 	function IsProviderBanned($param)
 	{
		if (empty($param['provider']))
		{
			trigger_error('ERROR::NEED_PARAMETER -- FROM IsProviderBanned() -- EMPTY provider',E_USER_ERROR);
		}

 		$query_array 			= 	array();
		$query_array['text'] 	= 	$param['provider'];
		$query_array['type'] 	= 	3;

		$num = $this->_BaseQueryNum($query_array);

 		return ($num <= 0) ? false : true;
 	}

 	function _BaseQueryNum($param)
 	{
		if (empty($param['text'])
			or empty($param['type']))
		{
			trigger_error('ERROR::NEED_PARAMETER -- FROM _BaseQueryNum() -- EMPTY text or type',E_USER_ERROR);
		}

 		$arr 						= 	array();
 		$arr['select'] 				= 	'*';
 		$arr['from'] 				= 	$this->Engine->table['banned'];
 		$arr['where']				=	array();

 		$arr['where'][0]			=	array();
 		$arr['where'][0]['name']	=	'text';
 		$arr['where'][0]['oper']	=	'=';
 		$arr['where'][0]['value']	=	$param['text'];

 		$arr['where'][1]			=	array();
 		$arr['where'][1]['con']		=	'AND';
 		$arr['where'][1]['name']	=	'text_type';
 		$arr['where'][1]['oper']	=	'=';
 		$arr['where'][1]['value']	=	$param['type'];

 		$num = $this->Engine->records->GetNumber($arr);

 		return $num;
 	}

 	function InsertBanned($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$query = $this->Engine->records->Insert($this->Engine->table['banned'],$param['field']);

		if (isset($param['get_id']))
		{
			$this->id = $this->Engine->DB->sql_insert_id();
		}

		return ($query) ? true : false;
	}


	function DeleteBanned($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['table'] = $this->Engine->table['banned'];

 		$query = $this->Engine->records->Delete($param);

 		return ($query) ? true : false;
	}

	function GetBannedList($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

 		$param['select'] 	= 	'*';
 		$param['from']		=	$this->Engine->table['banned'];

		$rows = $this->Engine->records->GetList($param);

		return $rows;
	}

	function GetBannedInfo($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['select'] 	= 	'*';
		$param['from']		=	$this->Engine->table['banned'];

		$rows = $this->Engine->records->GetInfo($param);

		return $rows;
	}

	function IsBanned($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['select'] 	= 	'*';
		$param['from'] 		= 	$this->Engine->table['banned'];

		$num = $this->Engine->records->GetNumber($param);

		return ($num <= 0) ? false : true;
	}

}

?>
