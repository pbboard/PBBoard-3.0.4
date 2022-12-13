<?php

class PowerBBToolbox
{
	var $id;
	var $PowerBB;

	function __construct($PowerBB)
	{
		$this->Engine = $PowerBB;
	}

 	function InsertFont($param)
 	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

 		if (!is_array($param['field']))
 		{
 			$param['field'] = array();
 		}

		$param['field']['tool_type'] = 1;

		$query = $this->Engine->records->Insert($this->Engine->table['toolbox'],$param['field']);

		if (isset($param['get_id']))
		{
			$this->id = $this->Engine->DB->sql_insert_id();
		}

		return ($query) ? true : false;
 	}

	function GetFontsList($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

 		$param['select'] 				= 	'*';
 		$param['from'] 					= 	$this->Engine->table['toolbox'];
 		$param['where'] 				= 	array('tool_type','1');

		$rows = $this->Engine->records->GetList($param);

		return $rows;
	}

 	function UpdateFont($param)
 	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

 		if (!is_array($param['field']))
 		{
 			$param['field'] = array();
 		}

		$param['field']['tool_type'] = 1;

		$query = $this->Engine->records->Update($this->Engine->table['toolbox'],$param['field'],$param['where']);

		return ($query) ? true : false;
 	}

	function GetFontInfo($param)
	{
 		if (empty($param['id']))
 		{
 			trigger_error('ERROR::NEED_PARAMETER -- FROM GetFontInfo() -- EMPTY id',E_USER_ERROR);
 		}

		$param['select'] 				= 	'*';
		$param['from'] 					= 	$this->Engine->table['toolbox'];

		$param['where'] 				= 	array();
		$param['where'][0] 				= 	array();
		$param['where'][0]['name'] 		= 	'tool_type';
		$param['where'][0]['oper'] 		= 	'=';
		$param['where'][0]['value'] 	= 	'1';

		$param['where'][1] 				= 	array();
		$param['where'][1]['con'] 		= 	'AND';
		$param['where'][1]['name'] 		= 	'id';
		$param['where'][1]['oper'] 		= 	'=';
		$param['where'][1]['value'] 	= 	$param['id'];

		$rows = $this->Engine->records->GetInfo($param);

		return $rows;
	}

	function DeleteFont($param)
	{
 		if (empty($param['id']))
 		{
 			trigger_error('ERROR::NEED_PARAMETER -- FROM DeleteFont() -- EMPTY id',E_USER_ERROR);
 		}

		$param['table'] 				= 	$this->Engine->table['toolbox'];
		$param['where'] 				= 	array();

		$param['where'][0] 				= 	array();
		$param['where'][0]['name'] 		= 	'id';
		$param['where'][0]['oper'] 		= 	'=';
		$param['where'][0]['value'] 	= 	$param['id'];

		$param['where'][1] 				= 	array();
		$param['where'][1]['con'] 		= 	'AND';
		$param['where'][1]['name'] 		= 	'tool_type';
		$param['where'][1]['oper'] 		= 	'=';
		$param['where'][1]['value'] 	= 	'1';

		$del = $this->Engine->records->Delete($param);

		return ($del) ? true : false;
	}

 	function InsertColor($param)
 	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

 		if (!is_array($param['field']))
 		{
 			$param['field'] = array();
 		}

		$param['field']['tool_type'] = 2;

		$query = $this->Engine->records->Insert($this->Engine->table['toolbox'],$param['field']);

		if (isset($param['get_id']))
		{
			$this->id = $this->Engine->DB->sql_insert_id();
		}

		return ($query) ? true : false;
 	}

	function GetColorsList($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

	 	$param['select'] 	= 	'*';
 		$param['from'] 		= 	$this->Engine->table['toolbox'];
		$param['where'] 	= 	array('tool_type','2');

		$rows = $this->Engine->records->GetList($param);

		return $rows;
	}

  	function UpdateColor($param)
 	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

 		if (!is_array($param['field']))
 		{
 			$param['field'] = array();
 		}

		$param['field']['tool_type'] = 2;

		$query = $this->Engine->records->Update($this->Engine->table['toolbox'],$param['field'],$param['where']);

		return ($query) ? true : false;
 	}

 	function GetColorInfo($param)
	{
 		if (empty($param['id']))
 		{
 			trigger_error('ERROR::NEED_PARAMETER -- FROM GetColorInfo() -- EMPTY id',E_USER_ERROR);
 		}

		$param['select'] 				= 	'*';
		$param['from'] 					= 	$this->Engine->table['toolbox'];

		$param['where'] 				= 	array();
		$param['where'][0] 				= 	array();
		$param['where'][0]['name'] 		= 	'tool_type';
		$param['where'][0]['oper'] 		= 	'=';
		$param['where'][0]['value'] 	= 	'2';

		$param['where'][1] 				= 	array();
		$param['where'][1]['con'] 		= 	'AND';
		$param['where'][1]['name'] 		= 	'id';
		$param['where'][1]['oper'] 		= 	'=';
		$param['where'][1]['value'] 	= 	$param['id'];

		$rows = $this->Engine->records->GetInfo($param);

		return $rows;
	}

	function DeleteColor($param)
	{
 		if (empty($param['id']))
 		{
 			trigger_error('ERROR::NEED_PARAMETER -- FROM DeleteColor() -- EMPTY id',E_USER_ERROR);
 		}

		$param['table'] 				= 	$this->Engine->table['toolbox'];
		$param['where'] 				= 	array();

		$param['where'][0] 				= 	array();
		$param['where'][0]['name'] 		= 	'id';
		$param['where'][0]['oper'] 		= 	'=';
		$param['where'][0]['value'] 	= 	$param['id'];

		$param['where'][1] 				= 	array();
		$param['where'][1]['con'] 		= 	'AND';
		$param['where'][1]['name'] 		= 	'tool_type';
		$param['where'][1]['oper'] 		= 	'=';
		$param['where'][1]['value'] 	= 	'2';

		$del = $this->Engine->records->Delete($param);

		return ($del) ? true : false;
	}
}

?>
