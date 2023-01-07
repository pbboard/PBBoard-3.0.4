<?php
class PowerBBCustom_bbcode
{
	var $id;
	var $PowerBB;

	function __construct($PowerBB)
	{
		$this->Engine = $PowerBB;
	}

 	/**
 	 * Insert new Custom_bbcode
 	 *
 	 * param :
 	 *			Oh :O it's a long list
 	 */
 	function InsertCustom_bbcode($param)
 	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$query = $this->Engine->records->Insert($this->Engine->table['custom_bbcode'],$param['field']);

		if (isset($param['get_id']))
		{
			$this->id = $this->Engine->DB->sql_insert_id();
		}

		return ($query) ? true : false;
 	}


	function DeleteCustom_bbcode($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['table'] = $this->Engine->table['custom_bbcode'];

		$del = $this->Engine->records->Delete($param);

		return ($del) ? true : false;
	}

	function GetCustom_bbcodeInfo($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['select'] 	= 	'*';
		$param['from'] 		= 	$this->Engine->table['custom_bbcode'];

		$rows = $this->Engine->records->GetInfo($param);

 	 	return $rows;
	}

	 function UpdateCustom_bbcode($param)
 	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$query = $this->Engine->records->Update($this->Engine->table['custom_bbcode'],$param['field'],$param['where']);

		return ($query) ? true : false;
 	}

	function GetCustom_bbcodeList($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['select'] 	= 	'*';
 		$param['from'] 		= 	$this->Engine->table['custom_bbcode'];

		$rows = $this->Engine->records->GetList($param);

		return $rows;
	}


	function CreateCustom_bbcodeCache($param)
 	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$Custom_bbcode = $this->GetCustom_bbcodeList($param);

 		$cache 	= 	array();
 		$x		=	0;
 		$n		=	sizeof($Custom_bbcode);

		while ($x < $n)
		{
			$cache[$x] 					                    = 	array();
			$cache[$x]['id']		 	                    = 	$Custom_bbcode[$x]['id'];
			$cache[$x]['bbcode_title']		 	            = 	$Custom_bbcode[$x]['bbcode_title'];
			$cache[$x]['bbcode_desc']		 	            = 	$Custom_bbcode[$x]['bbcode_desc'];
			$cache[$x]['bbcode_tag']		 	            = 	$Custom_bbcode[$x]['bbcode_tag'];
			$cache[$x]['bbcode_replace']		 	        = 	$Custom_bbcode[$x]['bbcode_replace'];
			$cache[$x]['bbcode_useoption']		 	        = 	$Custom_bbcode[$x]['bbcode_useoption'];
			$cache[$x]['bbcode_example']		 	        = 	$Custom_bbcode[$x]['bbcode_example'];
			$cache[$x]['bbcode_switch']		 	            = 	$Custom_bbcode[$x]['bbcode_switch'];
			$cache[$x]['bbcode_add_into_menu']		     	= 	$Custom_bbcode[$x]['bbcode_add_into_menu'];
			$cache[$x]['bbcode_menu_option_text']		 	= 	$Custom_bbcode[$x]['bbcode_menu_option_text'];
			$cache[$x]['bbcode_menu_content_text']		 	= 	$Custom_bbcode[$x]['bbcode_menu_content_text'];

			$x += 1;
		}

		$cache = json_encode($cache);

		return $cache;
 	}


 	function UpdateCustom_bbcodeCache($param)
 	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

 		$cache = $this->CreateCustom_bbcodeCache($param);

 		$update_cache = $this->Engine->info->UpdateInfo(array('value'=>$cache,'var_name'=>'custom_bbcodes_list_cache'));

 		return ($update_cache) ? true : false;
 	}

}

?>
