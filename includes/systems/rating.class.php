<?php

// ##########################################||
// #
// #   PowerBB Version 2.1.2
// #   http://www.pbboard.info
// #   Copyright (c) 2009 by Abu.Rakan
// #
// #   filename : rating.class.php
// #   subject rating
// #
// #########################################||


class PowerBBRating
{
	var $PowerBB;

	function __construct($PowerBB)
	{
		$this->Engine = $PowerBB;
	}

	function InsertRating($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$query = $this->Engine->records->Insert($this->Engine->table['rating'],$param['field']);

		if (isset($param['get_id']))
		{
			$this->id = $this->Engine->DB->sql_insert_id();
		}

		return ($query) ? true : false;
	}

	 function UpdateRating($param)
 	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$query = $this->Engine->records->Update($this->Engine->table['rating'],$param['field'],$param['where']);

		return ($query) ? true : false;
 	}

	function GetRatingList($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

 		$param['select'] 	= 	'*';
 		$param['from']		=	$this->Engine->table['rating'];

		$rows = $this->Engine->records->GetList($param);

		return $rows;
	}

	function GetRatingInfo($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['select'] 	= 	'*';
		$param['from']		=	$this->Engine->table['rating'];

		$rows = $this->Engine->records->GetInfo($param);

		return $rows;
	}


	function GetRatingNumber($param)
 	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['select'] 	= 	'*';
		$param['from'] 		= 	$this->Engine->table['rating'];

		$num   = $this->Engine->records->GetNumber($param);

		return $num;
 	}


}


?>
