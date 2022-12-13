<?php

/**
 * PowerBB Engine - The Engine Helps You To Create Bulletin Board System.
 */

/**
 * package 	: 	PowerBBUserRating (UserRating)
 * @author 		: 	MSHRAQ abu-rakan ()
 * start 		: 	7/2/2010 , 03:25 AM
 */
class PowerBBUserRating
{
	var $id;
	var $PowerBB;

	function __construct($PowerBB)
	{
		$this->Engine = $PowerBB;
	}

	function InsertUserRating($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$query = $this->Engine->records->Insert($this->Engine->table['userrating'],$param['field']);

		if (isset($param['get_id']))
		{
			$this->id = $this->Engine->DB->sql_insert_id();
		}

		return ($query) ? true : false;
	}

	function GetUserRatingList($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

 		$param['select'] 	= 	'*';
 		$param['from'] 		= 	$this->Engine->table['userrating'];

		$param['order']					=	array();
		$param['order']['field']		=	'posts';
		$param['order']['type']			=	'ASC';

 	 	$rows = $this->Engine->records->GetList($param);

 		return $rows;
	}


	function UpdateUserRating($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$query = $this->Engine->records->Update($this->Engine->table['userrating'],$param['field'],$param['where']);

		return ($query) ? true : false;
	}

	function DeleteUserRating($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

 		$param['table'] = $this->Engine->table['userrating'];

		$del = $this->Engine->records->Delete($param);

		return ($del) ? true : false;
	}

  	function GetCachedRatings()
	{
 		$cache = $this->Engine->_CONF['info_row']['users_ratings_cache'];
        if(strstr($cache,'rating'))
        {
         $cache = $this->UpdateRatingsCache(null);
		$cache = json_decode(base64_decode($cache), true);
		return $cache;
        }
        else
        {
		$cache = json_decode(base64_decode($cache), true);
		return $cache;
        }

	}

	function CreateRatingsCache($param)
 	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$ratings = $this->GetUserRatingList($param);

 		$cache 	= 	array();
 		$x		=	0;
 		$n		=	sizeof($ratings);

		while ($x < $n)
		{
			$cache[$x]['order']					=	array();
			$cache[$x]['order']['field']		=	'posts';
			$cache[$x]['order']['type']			=	'ASC';
			$cache[$x] 					= 	array();
			$cache[$x]['id']		 	= 	$ratings[$x]['id'];
			$cache[$x]['rating'] 	    = 	$ratings[$x]['rating'];
			$cache[$x]['posts'] 	    = 	$ratings[$x]['posts'];
			$x += 1;
		}

		$cache = base64_encode(json_encode($cache));

		return $cache;
 	}

	function UpdateRatingsCache($param)
 	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

 		$cache = $this->CreateRatingsCache($param);

 		$update_cache = $this->Engine->info->UpdateInfo(array('value'=>$cache,'var_name'=>'users_ratings_cache'));

 		return ($update_cache) ? true : false;
 	}


}

?>
