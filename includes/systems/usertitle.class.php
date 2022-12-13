<?php

class PowerBBUsertitle
{
	var $id;
	var $PowerBB;

	function __construct($PowerBB)
	{
		$this->Engine = $PowerBB;
	}

	function InsertUsertitle($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$query = $this->Engine->records->Insert($this->Engine->table['usertitle'],$param['field']);

		if (isset($param['get_id']))
		{
			$this->id = $this->Engine->DB->sql_insert_id();
		}

		return ($query) ? true : false;
	}

	function GetUsertitleList($param)
	{
  		if (!isset($param)
  			or !is_array($param))
 		{
 			$param = array();
 		}

 		$param['select'] 	= 	'*';
 		$param['from'] 		= 	$this->Engine->table['usertitle'];

		$param['order']					=	array();
		$param['order']['field']		=	'posts';
		$param['order']['type']			=	'ASC';

 	 	$rows = $this->Engine->records->GetList($param);

 		return $rows;
	}

	function GetUsertitleInfo($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

 	 	$param['select'] 	= 	'*';
 	 	$param['from'] 		= 	$this->Engine->table['usertitle'];

 	 	$rows = $this->Engine->records->GetInfo($param);

 	 	return $rows;
	}

	function UpdateUsertitle($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$query = $this->Engine->records->Update($this->Engine->table['usertitle'],$param['field'],$param['where']);

		return ($query) ? true : false;
	}

	function DeleteUsertitle($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['table'] = $this->Engine->table['usertitle'];

		$del = $this->Engine->records->Delete($param);

		return ($del) ? true : false;
	}

  	function GetCachedTitles()
	{
 		$cache = $this->Engine->_CONF['info_row']['users_titles_cache'];

        if(strstr($cache,'usertitle'))
        {
         $cache = $this->UpdateTitlesCache(null);
		$cache = json_decode(base64_decode($cache), true);
		return $cache;
        }
        else
        {
		$cache = json_decode(base64_decode($cache), true);
		return $cache;
        }
	}

	function CreateTitlesCache($param)
 	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$titles = $this->GetUsertitleList($param);

 		$cache 	= 	array();
 		$x		=	0;
 		$n		=	sizeof($titles);

		while ($x < $n)
		{
			$cache[$x]['order']					=	array();
			$cache[$x]['order']['field']		=	'posts';
			$cache[$x]['order']['type']			=	'ASC';
			$cache[$x] 					= 	array();
			$cache[$x]['id']		 	= 	$titles[$x]['id'];
			$cache[$x]['usertitle']     = 	$titles[$x]['usertitle'];
			$cache[$x]['posts'] 	    = 	$titles[$x]['posts'];

			$x += 1;
		}

		$cache = base64_encode(json_encode($cache));

		return $cache;
 	}

	function UpdateTitlesCache($param)
 	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

 		$cache = $this->CreateTitlesCache($param);

 		$update_cache = $this->Engine->info->UpdateInfo(array('value'=>$cache,'var_name'=>'users_titles_cache'));

 		return ($update_cache) ? true : false;
 	}


}

?>
