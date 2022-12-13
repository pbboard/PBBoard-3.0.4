<?php

/**
 * PowerBB Engine - The Engine Helps You To Create Bulletin Board System.
 */

/**
 * package 	: 	PowerBBSupermemberlogs (moderators Action)
 * @author 		: 	MSHRAQ abu-rakan ()
 * start 		: 	15/10/2009 , 03:25 AM
 */


class PowerBBSupermemberlogs
{
	var $id;
	var $PowerBB;

	function __construct($PowerBB)
	{
		$this->Engine = $PowerBB;
	}

 	/**
 	 * Insert new supermemberlogs
 	 *
 	 * param :
 	 *			Oh :O it's a long list
 	 */
 	function InsertSupermemberlogs($param)
 	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$query = $this->Engine->records->Insert($this->Engine->table['sm_logs'],$param['field']);

		if (isset($param['get_id']))
		{
			$this->id = $this->Engine->DB->sql_insert_id();
		}

		return ($query) ? true : false;
 	}


	function DeleteSupermemberlogs($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['table'] = $this->Engine->table['sm_logs'];

		$del = $this->Engine->records->Delete($param);

		return ($del) ? true : false;
	}

	/**
	 * Get the list of Supermemberlogs
	 *
	 * $param =
	 *			array(	'sql_statment'	=>	'complete SQL statement',
	 *					'proc'			=>	true // When you want proccess the outputs
	 *					);
	 *
	 * @return :
	 *				array -> of information
	 *				false -> when found no information
	 */
	function GetSupermemberlogsList($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['select'] 	= 	'*';
 		$param['from'] 		= 	$this->Engine->table['sm_logs'];

		$rows = $this->Engine->records->GetList($param);

		return $rows;
	}

	/**
	 * Get Supermemberlogs info
	 *
	 * $param =
	 *			array(	'id'	=>	'the id of Supermemberlogs');
	 *
	 * @return :
	 *			array -> of information
	 *			false -> when found no information
	 */
	function GetSupermemberlogsInfo($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['select'] 	= 	'*';
		$param['from'] 		= 	$this->Engine->table['sm_logs'];

		$rows = $this->Engine->records->GetInfo($param);

 	 	return $rows;
	}

	function GetSupermemberlogsNumber($param)
	{
		if (!isset($param))
		{
			$param 	= array();
		}

		$param['select'] 	= 	'*';
		$param['from'] 		= 	$this->Engine->table['sm_logs'];

		$num = $this->Engine->records->GetNumber($param);

		return $num;
	}
}

?>
