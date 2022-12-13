<?php

/**
 * PowerBB Engine - The Engine Helps You To Create Bulletin Board System.
 */

/**
 * Warn system
 *
 * package		: 	PowerBBWarnLog
 * @author		: 	Feras Allaou <feras.allaougmail.com>
 * start 		: 	12/5/2009 2:45 PM
 * end   		: 	12/5/2009 4:09 PM
 * updated 	:
*/

/**
 * package PowerBBWarnLog
 */

class PowerBBWARNLOG
{

	var $PowerBB;

	function __construct($PowerBB)
	{
		$this->Engine = $PowerBB;
	}

	/**
	 * INSERT A NEW WARNING RECORD !
	 *
	 * param :
	 *			from	->	the username of the warner
	 *			to		->	the username of the warned member
	 *			text	->	the text of warning
	 *			date	->	the date of warning
	 */
	function Insert($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$query = $this->Engine->records->Insert($this->Engine->table['warnlog'],$param['field']);

		if (isset($param['get_id']))
		{
			$this->id = $this->Engine->DB->sql_insert_id();
		}

		return ($query) ? true : false;
	}

	/**
	 * Get Warnings Log
	 *
	 * param :
	 *			query	 -> if the way is query , this variable should value the query
	 */
	function Show($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['select'] 	= 	'*';
		$param['from']		=	$this->Engine->table['warnlog'];

		$rows = $this->Engine->records->GetList($param);

		return $rows;
	}

	/**
	 * Delete Warning Log
	 *
	 * param :
	 */
		function DeleteLog($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

	 	$param['table'] 	= 	$this->Engine->table['warnlog'];

	 	$del = $this->Engine->records->Delete($param);

	 	return ($del) ? true : false;
	}
}

?>
