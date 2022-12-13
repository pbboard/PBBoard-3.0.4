<?php

class PowerBBOnline
{
	var $id;
	var $PowerBB;

	function __construct($PowerBB)
	{
		$this->Engine = $PowerBB;
	}

 	function InsertOnline($param)
 	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$query = $this->Engine->records->Insert($this->Engine->table['online'],$param['field']);

		if (isset($param['get_id']))
		{
			$this->id = $this->Engine->DB->sql_insert_id();
		}

		return ($query) ? true : false;
 	}

 	function UpdateOnline($param)
 	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$query = $this->Engine->records->Update($this->Engine->table['online'],$param['field'],$param['where']);

		return ($query) ? true : false;
 	}


	function GetOnlineInfo($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['select'] 	= 	(!empty($param['get'])) ? $param['get'] : '*';
		$param['from'] 		= 	$this->Engine->table['online'];

		$rows = $this->Engine->records->GetInfo($param);

		return $rows;
	}

 	function GetOnlineList($param)
 	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

 		$param['select'] 	= 	'*';
 		$param['from'] 		= 	$this->Engine->table['online'];

 	 	$rows = $this->Engine->records->GetList($param);

 		return $rows;
 	}

 	function GetOnlineNumber($param)
 	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

 		$param['select'] 	= 	'*';
 		$param['from'] 		= 	$this->Engine->table['online'];

 		$num = $this->Engine->records->GetNumber($param);

 		return $num;
 	}

 	function DeleteOnline($param)
 	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

 		$param['table'] = $this->Engine->table['online'];

 		$query = $this->Engine->records->Delete($param);

 		return ($query) ? true : false;
 	}


 	function IsOnline($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['select'] 	= 	'*';
		$param['from'] 		= 	$this->Engine->table['online'];

		$num = $this->Engine->records->GetNumber($param);

		return ($num <= 0) ? false : true;
	}

 	function UpdateToday($param)
 	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$query = $this->Engine->records->Update($this->Engine->table['today'],$param['field'],$param['where']);

		return ($query) ? true : false;
 	}

 	function CleanTodayTable($param)
 	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

 		$param['table'] = $this->Engine->table['today'];

        if (!empty($param['date']))
        {
           $param['where']           =    array();
           $param['where'][0]           =    array();
           $param['where'][0]['name']    =    'user_date';
           $param['where'][0]['oper']    =    '<>';
           $param['where'][0]['value']    =    $param['date'];

        }

 		$query = $this->Engine->records->Delete($param);

 		return ($query) ? true : false;
 	}

    function IsToday($param)
    {
        if (!isset($param)
           or !is_array($param))
        {
           $param = array();
        }

       $param['select']    =    '*';
       $param['from']        =    $this->Engine->table['today'];

       $num = $this->Engine->records->GetNumber($param);

       return ($num <= 0) ? false : true;
    }

 	function InsertToday($param)
 	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$query = $this->Engine->records->Insert($this->Engine->table['today'],$param['field']);

		if (isset($param['get_id']))
		{
			$this->id = $this->Engine->DB->sql_insert_id();
		}

		return ($query) ? true : false;
 	}

 	function GetTodayList($param)
 	{
  		if (!isset($param)
  			or !is_array($param))
 		{
 			$param = array();
 		}

  		$param['select'] 	= 	'*';
 		$param['from'] 		= 	$this->Engine->table['today'];

 	 	$rows = $this->Engine->records->GetList($param);

 		return $rows;
 	}

 	function GetTodayInfo($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['select'] 	= 	'*';
		$param['from']		=	$this->Engine->table['today'];

		$rows = $this->Engine->records->GetInfo($param);

		return $rows;
	}


 	function OnlineInfo($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['select'] 	= 	'*';
		$param['from']		=	$this->Engine->table['online'];

		$rows = $this->Engine->records->GetInfo($param);

		return $rows;
	}


}

?>
