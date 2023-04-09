<?php

/**
 * PowerBB Engine - The Engine Helps You To Create Bulletin Board System.
 */

/**
 * package 	: 	PowerBBLang
 * @author 		: 	SULAIMAN DAWOD SULAIMAN ALMUTAIRI <>
 * start 		: 	10/5/2009 , 8:38 PM
 * end   		: 	27/5/2009 , 8:47 PM
 */

class PowerBBLang
{
	var $id;
	var $PowerBB;

	function __construct($PowerBB)
	{
		$this->Engine = $PowerBB;
	}

 	/**
 	 * Insert new lang
 	 *
 	 * param :
 	 *			Oh :O it's a long list
 	 */
 	function InsertLang($param)
 	{
  		if (!isset($param)
  			or !is_array($param))
 		{
 			$param = array();
 		}

		$query = $this->Engine->records->Insert($this->Engine->table['lang'],$param['field']);

		if (isset($param['get_id']))
		{
			$this->id = $this->Engine->DB->sql_insert_id();
		}

		return ($query) ? true : false;
 	}

 	/**
 	 * Update lang information
 	 *
 	 * param :
 	 *			long list :\
 	 */
 	function UpdateLang($param)
 	{
  		if (!isset($param)
  			or !is_array($param))
 		{
 			$param = array();
 		}

		$query = $this->Engine->records->Update($this->Engine->table['lang'],$param['field'],$param['where']);

		return ($query) ? true : false;
 	}

	function DeleteLang($param)
	{
  		if (!isset($param)
  			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['table'] = $this->Engine->table['lang'];

		$del = $this->Engine->records->Delete($param);

		return ($del) ? true : false;
	}
 	/**
 	 * Get lang list
 	 *
 	 * param :
 	 *			sql_statment	->	to complete SQL query
 	 */
	function GetLangList($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['select'] 	= 	'*';
 		$param['from'] 		= 	$this->Engine->table['lang'];

 	 	$rows = $this->Engine->records->GetList($param);

		return $rows;
  	 }

	/**
	 * Set the correct lang for member or user
	 *
	 * @return : the information about the correct lang
	 */
	function GetLangInfo($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['select'] 	= 	'*';
		$param['from'] 		= 	$this->Engine->table['lang'];

		$rows = $this->Engine->records->GetInfo($param);

		return $rows;
	}

	function ChangeLang($param)
	{
		if (empty($param['lang']))
		{
			trigger_error('ERROR::NEED_PARAMETER -- FROM ChangeLang() -- EMPTY lang',E_USER_ERROR);
		}

		   $options 			 = 	array();
		   $options['expires']	 =	$param['expire'];
           $update = $this->Engine->functions->pbb_set_cookie($this->Engine->_CONF['lang_cookie'],$param['lang'],$options);

		return ($update) ? true : false;
	}

	function CreateLangCache($param)
 	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$lang 	= 	$this->GetLangInfo($param);
		$cache 	= 	'';

		if ($lang != false)
		{
			$cache = array();

			$cache['lang_path'] 		= 	$lang['lang_path'];
			$cache['cache_path'] 		= 	$lang['cache_path'];

			$cache = base64_encode(json_encode($cache));
		}
		else
		{
			return false;
		}

		return $cache;
 	}

 	function IsLang($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['select'] 	= 	'*';
		$param['from'] 		= 	$this->Engine->table['lang'];

		$num = $this->Engine->records->GetNumber($param);

		return ($num <= 0) ? false : true;
	}


}

?>
