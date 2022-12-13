<?php

class PowerBBPM
{
	var $id;
	var $PowerBB;

	function __construct($PowerBB)
	{
		$this->Engine = $PowerBB;
	}

	function InsertMassege($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$query = $this->Engine->records->Insert($this->Engine->table['pm'],$param['field']);

		if (isset($param['get_id']))
		{
			$this->id = $this->Engine->DB->sql_insert_id();
		}

		return ($query) ? true : false;
	}

	function GetPrivateMassegeNumber($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['select'] 	= 	'*';
		$param['from']		=	$this->Engine->table['pm'];

		$num = $this->Engine->records->GetNumber($param);

		return $num;
	}

	function GetPrivateMassegeList($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

 		$param['select'] 	= 	'*';
 		$param['from'] 		= 	$this->Engine->table['pm'];

 	 	$rows = $this->Engine->records->GetList($param);

		return $rows;
	}

	function GetPrivateMassegeInfo($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['select'] 			= 	'*';
		$param['from']				=	$this->Engine->table['pm'];
		$param['where']				=	array();

		if (!empty($param['id'])
			and !empty($param['username']))
		{
			$param['where'][0]			=	array();
			$param['where'][0]['name']	=	'id';
			$param['where'][0]['oper']	=	'=';
			$param['where'][0]['value']	=	$param['id'];

		}

		$rows = $this->Engine->records->GetInfo($param);

		return $rows;
	}

	function MakeMassegeRead($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$field 				= 	array();
		$field['user_read'] = 	'1';

		$update = $this->Engine->records->Update($this->Engine->table['pm'],$field,$param['where']);

		return ($update) ? true : false;
	}

   function MakeMassegeUnRead($param)
    {
        if (!isset($param)
           or !is_array($param))
        {
           $param = array();
        }

       $field              =    array();
       $field['user_read'] =    '0';

       $update = $this->Engine->records->Update($this->Engine->table['pm'],$field,$param['where']);

       return ($update) ? true : false;
    }

	function NewMessageNumber($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$arr 							= 	array();
		$arr['where'] 					= 	array();

		$arr['where'][0] 				= 	array();
		$arr['where'][0]['name'] 		= 	'user_to';
		$arr['where'][0]['oper'] 		= 	'=';
		$arr['where'][0]['value'] 		= 	$param['username'];

		$arr['where'][1] 				= 	array();
		$arr['where'][1]['con'] 		= 	'AND';
		$arr['where'][1]['name'] 		= 	'folder';
		$arr['where'][1]['oper'] 		= 	'=';
		$arr['where'][1]['value'] 		= 	'inbox';

		$arr['where'][2] 				= 	array();
		$arr['where'][2]['con'] 		= 	'AND';
		$arr['where'][2]['name'] 		= 	'user_read';
		$arr['where'][2]['oper'] 		= 	'<>';
		$arr['where'][2]['value'] 		= 	'1';

		return $this->GetPrivateMassegeNumber($arr);
	}

	function GetPmList($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['select'] 	= 	'*';
 		$param['from'] 		= 	$this->Engine->table['pm'];

		$rows = $this->Engine->records->GetList($param);

		return $rows;
     }

   function GetAllPmNum($param)
    {
        if (!isset($param)
           or !is_array($param))
        {
           $param = array();
        }

       $param['select']    =    '*';
        $param['from']        =    $this->Engine->table['pm'];

       $param['where']    =  array();

       $param['where'][0] = array();
       $param['where'][0]['name'] =   '(user_to';
       $param['where'][0]['oper']    =    '=';
       $param['where'][0]['value']    =    $param['username'];

       $param['where'][1]              =    array();
       $param['where'][1]['con']        =    'AND';
       $param['where'][1]['name']        =    'folder';
       $param['where'][1]['oper']        =    '=';
       $param['where'][1]['value']    =    'inbox';

       $param['where'][2]              =    array();
       $param['where'][2]['con']        =    ') OR';
       $param['where'][2]['name']        =    'user_from';
       $param['where'][2]['oper']        =    '=';
       $param['where'][2]['value']    =    $param['username'];

       $param['where'][3]              =    array();
       $param['where'][3]['con']        =    'AND';
       $param['where'][3]['name']        =    'folder';
       $param['where'][3]['oper']        =    '=';
       $param['where'][3]['value']    =    'sent';


       $rows = $this->GetPrivateMassegeNumber($param);

       return $rows;
    }

	function GetInboxList($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['where'] 				= 	array();

		$param['where'][0] 				= 	array();
		$param['where'][0]['name'] 		= 	'user_to';
		$param['where'][0]['oper'] 		= 	'=';
		$param['where'][0]['value'] 	= 	$param['username'];

		$param['where'][1] 				= 	array();
		$param['where'][1]['con'] 		= 	'AND';
		$param['where'][1]['name'] 		= 	'folder';
		$param['where'][1]['oper'] 		= 	'=';
		$param['where'][1]['value'] 	= 	'inbox';

		$rows = $this->GetPrivateMassegeList($param);

		return $rows;
	}

	function GetSentList($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['where'] 				= 	array();

		$param['where'][0] 				= 	array();
		$param['where'][0]['name'] 		= 	'user_from';
		$param['where'][0]['oper'] 		= 	'=';
		$param['where'][0]['value'] 	= 	$param['username'];

		$param['where'][1] 				= 	array();
		$param['where'][1]['con'] 		= 	'AND';
		$param['where'][1]['name'] 		= 	'folder';
		$param['where'][1]['oper'] 		= 	'=';
		$param['where'][1]['value'] 	= 	'sent';

		$rows = $this->GetPrivateMassegeList($param);

		return $rows;
	}

	function UpdatePrivateMessage($param)
 	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$query = $this->Engine->records->Update($this->Engine->table['pm'],$param['field'],$param['where']);

		return ($query) ? true : false;
 	}

 	function DeletePrivateMessage($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['table'] = $this->Engine->table['pm'];

		$del = $this->Engine->records->Delete($param);

		return ($del) ? true : false;
	}

	function GetMessageInfo($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['select'] 	= 	'*';
		$param['from'] 		= 	$this->Engine->table['pm'];

		$rows = $this->Engine->records->GetInfo($param);

		return $rows;
	}
}

?>
