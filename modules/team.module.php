<?php

(!defined('IN_PowerBB')) ? die() : '';



define('CLASS_NAME','PowerBBTeamMOD');

include('common.php');
class PowerBBTeamMOD
{
	function run()
	{
		global $PowerBB;
 		if (!$PowerBB->_CONF['info_row']['active_team'])
		{
		  header("Location: index.php");
		  exit;
        }
		/** Show the team list **/
		if ($PowerBB->_GET['show'])
		{
			$this->_ShowTeam();
		}
		else
		{
			header("Location: index.php");
			exit;
		}

		$PowerBB->functions->GetFooter();
	}

	/**
	 * Get team list
	 */
	function _ShowTeam()
	{
		global $PowerBB;
         $PowerBB->functions->ShowHeader();
         $forum_team_query = $PowerBB->DB->sql_query("SELECT " . $PowerBB->table['member'] . ".* FROM " . $PowerBB->table['member'] . " AS " . $PowerBB->table['member'] . " LEFT JOIN " . $PowerBB->table['group'] . " ON (" . $PowerBB->table['member'] . ".usergroup = " . $PowerBB->table['group'] . ".id)    WHERE " . $PowerBB->table['group'] . ".forum_team = 1 ");
         $forum_team = array();
         $PowerBB->template->display('teamlist_top');
         while ($forum_team = $PowerBB->DB->sql_fetch_array($forum_team_query))
         {
          $PowerBB->template->assign('username',$forum_team['username']);
          $PowerBB->template->assign('user_title',$forum_team['user_title']);
          $PowerBB->template->assign('id',$forum_team['id']);
          $PowerBB->template->assign('forum_team',$forum_team);
          $PowerBB->template->assign('send_allow',$forum_team['send_allow']);
          $PowerBB->template->display('teamlist_down');
         }
         echo '<br />';

	}
}

?>
