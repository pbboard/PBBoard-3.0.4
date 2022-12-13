<?php

// ##########################################||
// #
// #   PowerBB Version 2.1.2
// #   http://www.pbboard.info
// #   Copyright (c) 2009 by Abu.Rakan
// #
// #   filename : reputation.class.php
// #   members reputation
// #
// #########################################||


class PowerBBReputation
{
	var $PowerBB;

	function __construct($PowerBB)
	{
		$this->Engine = $PowerBB;
	}

	function InsertReputation($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$query = $this->Engine->records->Insert($this->Engine->table['reputation'],$param['field']);

		if (isset($param['get_id']))
		{
			$this->id = $this->Engine->DB->sql_insert_id();
		}

		return ($query) ? true : false;
	}

	 function UpdateReputation($param)
 	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$query = $this->Engine->records->Update($this->Engine->table['reputation'],$param['field'],$param['where']);

		return ($query) ? true : false;
 	}

	function GetReputationList($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

 		$param['select'] 	= 	'*';
 		$param['from']		=	$this->Engine->table['reputation'];

		$rows = $this->Engine->records->GetList($param);

		return $rows;
	}

	function GetReputationInfo($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['select'] 	= 	'*';
		$param['from']		=	$this->Engine->table['reputation'];

		$rows = $this->Engine->records->GetInfo($param);

		return $rows;
	}


	function GetReputationNumber($param)
 	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['select'] 	= 	'*';
		$param['from'] 		= 	$this->Engine->table['reputation'];

		$num   = $this->Engine->records->GetNumber($param);

		return $num;
 	}


}


?>
