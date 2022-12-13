<?php

class PowerBBTag
{
	var $id;
	var $PowerBB;

	function __construct($PowerBB)
	{
		$this->Engine = $PowerBB;
	}

	function InsertTag($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$query = $this->Engine->records->Insert($this->Engine->table['tag'],$param['field']);

		if (isset($param['get_id']))
		{
			$this->id = $this->Engine->DB->sql_insert_id();
		}

		return ($query) ? true : false;
	}

 	function UpdateTag($param)
 	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$query = $this->Engine->records->Update($this->Engine->table['tag'],$param['field'],$param['where']);

		return ($query) ? true : false;
 	}

	function DeleteTag($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['table'] = $this->Engine->table['tag'];

		$del = $this->Engine->records->Delete($param);

		return ($del) ? true : false;
	}

	function GetTagList($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

 		$param['select'] 	= 	'*';
 		$param['from']		=	$this->Engine->table['tag'];

		$rows = $this->Engine->records->GetList($param);

		return $rows;
	}

	function GetTagInfo($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['select'] 	= 	'*';
		$param['from']		=	$this->Engine->table['tag'];

		$rows = $this->Engine->records->GetInfo($param);

		return $rows;
	}

	///

	function InsertSubject($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$query = $this->Engine->records->Insert($this->Engine->table['tag_subject'],$param['field']);

		if (isset($param['get_id']))
		{
			$this->id = $this->Engine->DB->sql_insert_id();
		}

		return ($query) ? true : false;
	}

 	function UpdateSubject($param)
 	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$query = $this->Engine->records->Update($this->Engine->table['tag_subject'],$param['field'],$param['where']);

		return ($query) ? true : false;
 	}

	function DeleteSubject($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['table'] = $this->Engine->table['tag_subject'];

		$del = $this->Engine->records->Delete($param);

		return ($del) ? true : false;
	}

	function GetSubjectList($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

 		$param['select'] 	= 	'*';
 		$param['from']		=	$this->Engine->table['tag_subject'];

		$rows = $this->Engine->records->GetList($param);

		return $rows;
	}

	function GetSubjectInfo($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['select'] 	= 	'*';
		$param['from']		=	$this->Engine->table['tag_subject'];

		$rows = $this->Engine->records->GetInfo($param);

		return $rows;
	}

 	function GetSubjectNumber($param)
 	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['select'] 	= 	'*';
		$param['from'] 		= 	$this->Engine->table['tag_subject'];

		$num   = $this->Engine->records->GetNumber($param);

		return $num;
 	}
}

?>
