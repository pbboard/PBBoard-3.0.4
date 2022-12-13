<?php

class PowerBBRequest
{
	var $id;
	var $PowerBB;

	function __construct($PowerBB)
	{
		$this->Engine = $PowerBB;
	}

	function GetRequestInfo($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['select'] 	= 	'*';
		$param['from'] 		= 	$this->Engine->table['requests'];

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

}


?>
