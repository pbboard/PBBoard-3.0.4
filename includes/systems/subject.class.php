<?php

class PowerBBSubject
{
	var $id;
	var $PowerBB;

	function __construct($PowerBB)
	{
		$this->Engine = $PowerBB;
	}

	function GetSubjectNumber($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		if ($param['get_from'] == 'cache')
		{
			$num = $this->Engine->_CONF['info_row']['subject_number'];
		}
		elseif ($param['get_from'] == 'db')
		{
			$param['select'] 	= 	'*';
			$param['from'] 		= 	$this->Engine->table['subject'];

			$num   = $this->Engine->records->GetNumber($param);
		}
 		else
 		{
 			trigger_error('ERROR::BAD_VALUE_OF_GET_FROM_VARIABLE -- FROM GetSubjectNumber() -- get_from SHOULD BE cache OR db',E_USER_ERROR);
 		}

		return $num;
	}

	function DeleteSubject($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['table'] = $this->Engine->table['subject'];

		$del = $this->Engine->records->Delete($param);

		return ($del) ? true : false;
	}

 	function UpdateSubject($param)
 	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

 		if (isset($param['field']['tags_cache']))
 		{
 			$param['field']['tags_cache'] = json_encode($param['field']['tags_cache'],JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
 		}

		$query = $this->Engine->records->Update($this->Engine->table['subject'],$param['field'],$param['where']);

		return ($query) ? true : false;
 	}


	function GetSubjectList($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

 	 	$param['select'] 	= 	'*';
 		$param['from'] 		= 	$this->Engine->table['subject'];

 	 	$rows = $this->Engine->records->GetList($param);

 	 	return $rows;
	}

	function GetSubjectListAdvanced($param)
	{
	    if (!isset($param) || !is_array($param)) {
	        $param = array();
	    }

	    if (empty($param['select'])) {
	        $param['select'] = 's.*';
	    }

	    if (empty($param['from'])) {
	        $param['from'] = $this->Engine->table['subject'] . ' AS s';
	    }

        $rows = $this->Engine->records->GetList($param);

	    return $rows;
	}


	function GetSubjectInfo($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

 	    $param['select'] 	= 	'*';
 	    $param['from'] 		= 	$this->Engine->table['subject'];

 		$rows = $this->Engine->records->GetInfo($param);

		return $rows;
	}

	function InsertSubject($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

 		if (isset($param['field']['tags_cache']))
 		{
 			$param['field']['tags_cache'] = json_encode($param['field']['tags_cache']);
 		}

		$query = $this->Engine->records->Insert($this->Engine->table['subject'],$param['field']);

		if (isset($param['get_id']))
		{
			$this->id = $this->Engine->DB->sql_insert_id();
		}

		return ($query) ? true : false;
	}

	function MassDeleteSubject($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['table'] = $this->Engine->table['subject'];

		$del = $this->Engine->records->Delete($param);

		return ($del) ? true : false;
	}

	function MassMoveSubject($param)
	{
 		if (empty($param['to'])
 			or empty($param['from']))
 		{
 			trigger_error('ERROR::NEED_PARAMETER -- FROM MassMoveSubject() -- EMPTY to OR from',E_USER_ERROR);
 		}

		$field 				= 	array();
		$field['section'] 	= 	$param['to'];

		$where	 			= 	array('section',$param['from']);

		$query = $this->Engine->records->Update($this->Engine->table['subject'],$field,$where);

		return ($query) ? true : false;
	}

	function GetSubjectWriterInfo($param)
	{
		if (empty($param['id']))
		{
			trigger_error('ERROR::NEED_PARAMETER -- FROM GetSubjectWriterInfo() -- EMPTY id');
		}


 	    $SQL = 'SELECT *
		FROM ' . $this->Engine->table['subject'] . ' AS p INNER JOIN  ' . $this->Engine->table['member'] . ' AS m
		WHERE p.id = ' . $param['id'] . '
		AND m.username = p.writer';

		$result = $this->Engine->DB->sql_query($SQL);
        $rows = $this->Engine->DB->sql_fetch_array($result);


		return $rows;
	}

	function GetSubjectGuestInfo($param)
	{
		if (empty($param['id']))
		{
			trigger_error('ERROR::NEED_PARAMETER -- FROM GetSubjectGuestInfo() -- EMPTY id');
		}

 		$arr							=	array();
 		$arr['select']					=	'*';
 		$arr['from']					=	$this->Engine->table['subject'];

 	    $arr['where'] 					= 	array();
 	    $arr['where'][0] 				= 	array();
 	    $arr['where'][0]['name'] 		= 	'id';
 	    $arr['where'][0]['oper'] 		= 	'=';
 	    $arr['where'][0]['value'] 		= 	$param['id'];


 		$rows = $this->Engine->records->GetInfo($arr);

		return $rows;
	}

	function IsFlood()
	{
		if (empty($param['last_time']))
		{
			trigger_error('ERROR::NEED_PARAMETER -- FROM IsFlood() -- EMPTY last_time',E_USER_ERROR);
		}

		return (($this->Engine->_CONF['now'] - $this->Engine->_CONF['info_row']['floodctrl']) <= $param['last_time']) ? true : false;
	}

	function IsSubject($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['select'] 	= 	'*';
		$param['from'] 		= 	$this->Engine->table['subject'];

		$num = $this->Engine->records->GetNumber($param);

		return ($num <= 0) ? false : true;
	}

	function UpdateWriteTime($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$field = array('write_time'	=> 	$param['write_time']);

		$query = $this->Engine->records->Update($this->Engine->table['subject'],$field,$param['where']);

		return ($query) ? true : false;
	}

	function UpdateReviewReply($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$field = array('review_reply'	=> 	$param['review_reply']);

		$query = $this->Engine->records->Update($this->Engine->table['subject'],$field,$param['where']);

		return ($query) ? true : false;
	}

	function UpdateReplyNumber($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$field = array('reply_number'	=> 	$param['reply_number']);

		$query = $this->Engine->records->Update($this->Engine->table['subject'],$field,$param['where']);

		return ($query) ? true : false;
	}

	function UpdateLastReplier($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$field = array('last_replier'	=> 	$param['replier']);

		$query = $this->Engine->records->Update($this->Engine->table['subject'],$field,$param['where']);

		return ($query) ? true : false;
	}


	function StickSubject($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

 		$field = array('stick'	=> 	1);

		$query = $this->Engine->records->Update($this->Engine->table['subject'],$field,$param['where']);

		return ($query) ? true : false;
	}

	function CloseSubject($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

 		$field = array(	'close'				=> 	1,
 						'close_reason'		=>	$param['reason']);

		$query = $this->Engine->records->Update($this->Engine->table['subject'],$field,$param['where']);

		return ($query) ? true : false;
	}

	function MoveSubject($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

 		$field = array('section'	=> 	$param['section_id']);

		$query = $this->Engine->records->Update($this->Engine->table['subject'],$field,$param['where']);

		return ($query) ? true : false;
	}

	function MoveSubjectToTrash($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

 		$field = array(	'delete_topic'	=> 	1,
 						'delete_reason' => $param['reason']);

		$query = $this->Engine->records->Update($this->Engine->table['subject'],$field,$param['where']);

		return ($query) ? true : false;
	}

	function UnTrashSubject($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

 		$field = array('delete_topic'	=> 	0);

		$query = $this->Engine->records->Update($this->Engine->table['subject'],$field,$param['where']);

		return ($query) ? true : false;
	}

	function UnstickSubject($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

 		$field = array('stick'	=> 	0);

		$query = $this->Engine->records->Update($this->Engine->table['subject'],$field,$param['where']);

		return ($query) ? true : false;
	}

	function OpenSubject($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

 		$field = array('close'	=> 	0);

		$query = $this->Engine->records->Update($this->Engine->table['subject'],$field,$param['where']);

		return ($query) ? true : false;
	}
}

?>
