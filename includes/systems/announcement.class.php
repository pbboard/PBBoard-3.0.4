<?php

class PowerBBAnnouncement
{
	var $id;
	var $PowerBB;

	function __construct($PowerBB)
	{
		$this->Engine = $PowerBB;
	}

 	function InsertAnnouncement($param)
 	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$query = $this->Engine->records->Insert($this->Engine->table['announcement'],$param['field']);

		if (isset($param['get_id']))
		{
			$this->id = $this->Engine->DB->sql_insert_id();
		}

		return ($query) ? true : false;
 	}


 	function UpdateAnnouncement($param)
 	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$query = $this->Engine->records->Update($this->Engine->table['announcement'],$param['field'],$param['where']);

		return ($query) ? true : false;
 	}

	function DeleteAnnouncement($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['table'] = $this->Engine->table['announcement'];

		$del = $this->Engine->records->Delete($param);

		return ($del) ? true : false;
	}

	function GetAnnouncementList($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['select'] 	= 	'*';
 		$param['from'] 		= 	$this->Engine->table['announcement'];

		$rows = $this->Engine->records->GetList($param);

		return $rows;
	}

	function GetAnnouncementInfo($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['select'] 	= 	'*';
		$param['from'] 		= 	$this->Engine->table['announcement'];

		$rows = $this->Engine->records->GetInfo($param);

 	 	return $rows;
	}

	function GetAnnouncementNumber($param)
	{
		if (!isset($param))
		{
			$param 	= array();
		}

		$param['select'] 	= 	'*';
		$param['from'] 		= 	$this->Engine->table['announcement'];

		$num = $this->Engine->records->GetNumber($param);

		return $num;
	}
}

?>
