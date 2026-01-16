<?php

class PowerBBReply
{
	var $id;
	var $PowerBB;

	function __construct($PowerBB)
	{
		$this->Engine = $PowerBB;
	}

	function GetReplyList($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

 		$param['from'] 		= 	$this->Engine->table['reply'];

 	 	$rows = $this->Engine->records->GetList($param);

 		return $rows;
	}

	function GetReplyListAdvanced($param)
	{
	    if (!isset($param) || !is_array($param)) {
	        $param = array();
	    }

	    if (empty($param['select'])) {
	        $param['select'] = 's.*';
	    }

	    if (empty($param['from'])) {
	        $param['from'] = $this->Engine->table['reply'] . ' AS s';
	    }

        $rows = $this->Engine->records->GetList($param);

	    return $rows;
	}

	function GetReplyDistinctList($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

 		$param['from'] 		= 	$this->Engine->table['reply'];

 	 	$rows = $this->Engine->records->GetList($param);

 		return $rows;
	}

	function GetReplyInfo($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

 	 	$param['select'] 	= 	'*';
 	 	$param['from'] 		= 	$this->Engine->table['reply'];

 	 	$rows = $this->Engine->records->GetInfo($param);

 	 	return $rows;
	}

	function GetReplyNumber($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		if ($param['get_from'] == 'cache')
		{
			$num = $this->Engine->_CONF['info_row']['reply_number'];
		}
		elseif ($param['get_from'] == 'db')
		{
			$param['select'] 	= 	'*';
			$param['from'] 		= 	$this->Engine->table['reply'];

			$num = $this->Engine->records->GetNumber($param);
		}
		else
		{
			trigger_error('ERROR::BAD_VALUE_OF_GET_FROM_VARIABLE -- FROM GetReplyNumber() -- get_from SHOULD BE cache OR db',E_USER_ERROR);
		}

		return $num;
	}



	function GetReplyWriterInfo($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

 	 	$param['select'] 	= 	'*';
 		$param['from'] 		= 	$this->Engine->table['reply'];
 		$param['where']				=	array();
 		$param['where'][0]			=	array();
 		$param['where'][0]['name']	=	'subject_id';
 		$param['where'][0]['oper']	=	'=';
 		$param['where'][0]['value']	=	$param['subject_id'];

		$param['where'][1] 				= 	array();
		$param['where'][1]['con'] 		= 	'AND';
		$param['where'][1]['name'] 		= 	'delete_topic';
		$param['where'][1]['oper'] 		= 	'<>';
		$param['where'][1]['value'] 	= 	'1';

     	$rows = $this->Engine->records->GetList($param);

		return $rows;
	}

	function InsertReply($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$query = $this->Engine->records->Insert($this->Engine->table['reply'],$param['field']);

		if (isset($param['get_id']))
		{
			$this->id = $this->Engine->DB->sql_insert_id();
		}

		return ($query) ? true : false;
	}

 	function UpdateReply($param)
 	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$query = $this->Engine->records->Update($this->Engine->table['reply'],$param['field'],$param['where']);

		return ($query) ? true : false;
 	}

	function UnTrashReply($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

 		$field 					= 	array();
 		$field['delete_topic'] 	= 	0;

		$query = $this->Engine->records->Update($this->Engine->table['reply'],$field,$param['where']);

		return ($query) ? true : false;
	}

	function DeleteReply($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['table'] = $this->Engine->table['reply'];

		$del = $this->Engine->records->Delete($param);

		return ($del) ? true : false;
	}

	function MassDeleteReply($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['table'] = $this->Engine->table['reply'];

		if (!empty($param['section_id']))
		{
			$param['where'] = array('section',$param['section_id']);
		}

		$del = $this->Engine->records->Delete($param);

		return ($del) ? true : false;
	}

	function MassMoveReply($param)
	{
 		if (empty($param['to'])
 			or empty($param['from']))
 		{
 			trigger_error('ERROR::NEED_PARAMETER -- FROM MassMoveReply() -- EMPTY to OR from',E_USER_ERROR);
 		}

 		$field 					= 	array();
 		$field['section'] 		= 	$param['to'];

 		$where 					=	array('section',$param['from']);

		$query = $this->Engine->records->Update($this->Engine->table['reply'],$field,$where);

		return ($query) ? true : false;
	}

	function MoveReplyToTrash($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

 		$field 					= 	array();
 		$field['delete_topic'] 	= 	1;

		$query = $this->Engine->records->Update($this->Engine->table['reply'],$field,$param['where']);

		return ($query) ? true : false;
	}

	function MoveReply($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

 		$field = array('subject_id'	=> 	$param['subject_id']);

		$query = $this->Engine->records->Update($this->Engine->table['reply'],$field,$param['where']);

		return ($query) ? true : false;
	}

	function UnMoveReplyToTrash($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

 		$field 				= 	array();
 		$field['delete'] 	= 	0;

		$query = $this->Engine->records->Update($this->Engine->table['reply'],$field,$param['where']);

		return ($query) ? true : false;
	}

}

?>