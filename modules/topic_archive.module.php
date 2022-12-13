<?php
(!defined('IN_PowerBB')) ? die() : '';

define('CLASS_NAME','PowerBBTopicArchiveMOD');
include('common.php');
class PowerBBTopicArchiveMOD
{

	function run()
	{
		global $PowerBB;

		$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');

		if ($PowerBB->_CONF['info_row']['rewriterule'] == '1')
		{
		$topic_url= "t".$PowerBB->_GET['id'];
		}
		else
		{
		$topic_url= "index.php?page=topic&show=1&id=".$PowerBB->_GET['id'];
		}

		if ($PowerBB->_CONF['info_row']['active_archive'] == '0')
		{
		header("Location: $topic_url");
		exit;
		}
		else
		{
		header("Location: $topic_url");
		exit;
		}
	}
}
	?>
