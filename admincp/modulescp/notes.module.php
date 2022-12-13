<?php

(!defined('IN_PowerBB')) ? die() : '';

define('IN_ADMIN',true);



define('CLASS_NAME','PowerBBNotesMOD');

include('../common.php');
class PowerBBNotesMOD
{
	function run()
	{
		global $PowerBB;
		if ($PowerBB->_CONF['member_permission'])
		{
		$PowerBB->template->display('header');

		// No changes
		if ($PowerBB->_POST['note'] == $PowerBB->_CONF['info_row']['admin_notes'])
		{
			$PowerBB->functions->redirect('index.php?page=index&left=1');
		}

		$update = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['note'],'var_name'=>'admin_notes'));

		if ($update)
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['note_has_been_updated_successfully']);
			$PowerBB->functions->redirect('index.php?page=index&left=1');
		}

		$PowerBB->template->display('footer');
	  }
	}
}

?>
