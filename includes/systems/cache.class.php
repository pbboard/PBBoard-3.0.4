<?php

class PowerBBCache
{
	var $PowerBB;

	function __construct($PowerBB)
	{
		$this->Engine = $PowerBB;
	}

	function UpdateLastMember($param)
	{
		if (!isset($param['member_num'])
			or empty($param['id']))
		{
			trigger_error('ERROR::NEED_PARAMETER -- FROM UpdateLastMember() -- EMPTY member_num or id',E_USER_ERROR);
		}
		$updates = array();
		$member_num 	= 	$param['member_num'];
		$member_num 	+= 	1;
		$update[0] = $this->Engine->info->UpdateInfo(array('var_name'=>'last_member','value'=>$param['username']));
		$update[1] = $this->Engine->info->UpdateInfo(array('var_name'=>'last_member_id','value'=>$param['id']));
		$update[2] = $this->Engine->info->UpdateInfo(array('var_name'=>'member_number','value'=>$member_num));
		return ($update[0] and $update[1] and $update[2]) ? true : false;
	}

	function UpdateSubjectNumber($param)
	{
		if (empty($param['subject_num'])
			and $param['subject_num'] != 0)
		{
			trigger_error('ERROR::NEED_PARAMETER -- FROM UpdateSubjectNumber() -- EMPTY subject_num',E_USER_ERROR);
		}
		$val = $param['subject_num'] + 1;
		$update = $this->Engine->info->UpdateInfo(array('var_name'=>'subject_number','value'=>$val));
		return ($update) ? true : false;
	}

	function UpdateReplyNumber($param)
	{
		if (empty($param['reply_num'])
		 and $param['reply_num'] != 0)
		{
			trigger_error('ERROR::NEED_PARAMETER -- FROM UpdateReplyNumber() -- EMPTY reply_num',E_USER_ERROR);
		}
		$val = $param['reply_num'] + 1;
		$update = $this->Engine->info->UpdateInfo(array('var_name'=>'reply_number','value'=>$val));
		return ($update) ? true : false;
	}
}

?>
