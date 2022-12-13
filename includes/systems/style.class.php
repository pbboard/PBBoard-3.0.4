<?php

class PowerBBStyle
{
	var $id;
	var $PowerBB;

	function __construct($PowerBB)
	{
		$this->Engine = $PowerBB;
	}

 	function InsertStyle($param)
 	{
  		if (!isset($param)
  			or !is_array($param))
 		{
 			$param = array();
 		}

		$query = $this->Engine->records->Insert($this->Engine->table['style'],$param['field']);

		if (isset($param['get_id']))
		{
			$this->id = $this->Engine->DB->sql_insert_id();
		}

		return ($query) ? true : false;
 	}

 	function UpdateStyle($param)
 	{
  		if (!isset($param)
  			or !is_array($param))
 		{
 			$param = array();
 		}

		$query = $this->Engine->records->Update($this->Engine->table['style'],$param['field'],$param['where']);

		return ($query) ? true : false;
 	}

	function DeleteStyle($param)
	{
  		if (!isset($param)
  			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['table'] = $this->Engine->table['style'];

		$del = $this->Engine->records->Delete($param);

		return ($del) ? true : false;
	}

	function GetStyleList($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['select'] 	= 	'*';
 		$param['from'] 		= 	$this->Engine->table['style'];

 	 	$rows = $this->Engine->records->GetList($param);

		return $rows;
  	 }

	function GetStyleInfo($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['select'] 	= 	'*';
		$param['from'] 		= 	$this->Engine->table['style'];

		$rows = $this->Engine->records->GetInfo($param);

		return $rows;
	}


	function ChangeStyle($param)
	{
		if (empty($param['style']))
		{
			trigger_error('ERROR::NEED_PARAMETER -- FROM ChangeStyle() -- EMPTY style',E_USER_ERROR);
		}

		$update = setcookie($this->Engine->_CONF['style_cookie'],$param['style'],$param['expire']);

		return ($update) ? true : false;
	}

 	function CreateStyleCache($param)
 	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$style 	= 	$this->GetStyleInfo($param);
		$cache 	= 	'';

		if ($style != false)
		{
			$cache = array();

			$cache['style_path'] 		= 	$style['style_path'];
			$cache['image_path'] 		= 	$style['image_path'];
			$cache['template_path'] 	= 	$style['template_path'];
			$cache['cache_path'] 		= 	$style['cache_path'];

			$cache = base64_encode(json_encode($cache));
		}
		else
		{
			return false;
		}

		return $cache;
 	}

 	function IsStyle($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['select'] 	= 	'*';
		$param['from'] 		= 	$this->Engine->table['style'];

		$num = $this->Engine->records->GetNumber($param);

		return ($num <= 0) ? false : true;
	}

}

?>
