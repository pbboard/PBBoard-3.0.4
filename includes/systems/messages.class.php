<?php

class PowerBBMessages
{
	var $PowerBB;

	function __construct($PowerBB)
	{
		$this->Engine = $PowerBB;
	}

	function GetMessageInfo($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

 	 	$param['select'] 	= 	'*';
 	 	$param['from'] 		= 	$this->Engine->table['email_msg'];

 	 	$rows = $this->Engine->records->GetInfo($param);

 	 	return $rows;
	}

	function GetMessageList($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['select'] 	= 	'*';
 		$param['from'] 		= 	$this->Engine->table['email_msg'];

		$rows = $this->Engine->records->GetList($param);

		return $rows;
	}

	 function UpdateMessage($param)
 	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$query = $this->Engine->records->Update($this->Engine->table['email_msg'],$param['field'],$param['where']);

		return ($query) ? true : false;
 	}

	function MessageProccess($param)
	{
		$search_array 		= 	array();
		$replace_array 		= 	array();

		$search_array[]		=	'[MySBB]username[/MySBB]';
		$replace_array[]	=	$param['username'];

		$search_array[]		=	'[MySBB]board_title[/MySBB]';
		$replace_array[]	=	$param['title'];

		$search_array[]		=	'[MySBB]url[/MySBB]';
		$replace_array[]	=	$param['active_url'];

		$search_array[]		=	'[MySBB]change_url[/MySBB]';
		$replace_array[]	=	$param['change_url'];

		$search_array[]		=	'[MySBB]cancel_url[/MySBB]';
		$replace_array[]	=	$param['cancel_url'];

		$text = str_replace($search_array,$replace_array,$param['text']);

		return $text;
	}
}

?>
