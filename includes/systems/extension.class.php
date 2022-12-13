<?php

class PowerBBFileExtension
{
	var $id;
	var $PowerBB;

	function __construct($PowerBB)
	{
		$this->Engine = $PowerBB;
	}

	function InsertExtension($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$query = $this->Engine->records->Insert($this->Engine->table['extension'],$param['field']);

		if (isset($param['get_id']))
		{
			$this->id = $this->Engine->DB->sql_insert_id();
		}

		return ($query) ? true : false;
	}

	function GetExtensionList($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

 		$param['select'] 	= 	'*';
 		$param['from'] 		= 	$this->Engine->table['extension'];

 	 	$rows = $this->Engine->records->GetList($param);

 		return $rows;
	}

	function GetExtensionInfo($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

 	 	$param['select'] 	= 	'*';
 	 	$param['from'] 		= 	$this->Engine->table['extension'];

 	 	$rows = $this->Engine->records->GetInfo($param);

 	 	return $rows;
	}

	function UpdateExtension($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$query = $this->Engine->records->Update($this->Engine->table['extension'],$param['field'],$param['where']);

		return ($query) ? true : false;
	}

	function DeleteExtension($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

 		$param['table'] = $this->Engine->table['extension'];

		$del = $this->Engine->records->Delete($param);

		return ($del) ? true : false;
	}

	function IsExtension($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['select'] 	= 	'*';
		$param['from'] 		= 	$this->Engine->table['extension'];

		$num = $this->Engine->records->GetNumber($param);

		return ($num <= 0) ? false : true;
	}
}

?>
