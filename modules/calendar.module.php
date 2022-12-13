<?php
(!defined('IN_PowerBB')) ? die() : '';


define('JAVASCRIPT_PowerCode',true);

define('CLASS_NAME','PowerBBCalendarMOD');

include('common.php');
class PowerBBCalendarMOD
{
	function run()
	{
		global $PowerBB;

		if (version_compare(phpversion(), '5.3.0', '>='))
		{
			if ($PowerBB->_CONF['info_row']['timeoffset'] != '')
			{
			  // Get time zone
			  @date_default_timezone_set($PowerBB->_CONF['info_row']['timeoffset']);
			}
		}
     $PowerBB->_GET['show'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['show'],'intval');

 		if (!$PowerBB->_CONF['info_row']['active_calendar'])
		{
			header("Location: index.php");
			exit;
        }
			$PowerBB->template->assign('calendar_page','primary_tabon');
			$PowerBB->functions->ShowHeader();
			$PowerBB->template->display('address_bar_part1');
			echo ' '. $PowerBB->_CONF['template']['_CONF']['lang']['Calendar'];
			$PowerBB->template->display('address_bar_part2');

   		/** Show Calendar form **/
		if ($PowerBB->_GET['show'])
		{
			if ($PowerBB->_GET['show'] == '1')
			{
			$this->_GetCalendar();
			}

		}
		else
		{
          $this->_GetCalendar();
		}

		$this->_GetJumpSectionsList();

       $PowerBB->functions->GetFooter();
	}

	function _GetCalendar()
	{
   		global $PowerBB;

 	 $PowerBB->_GET['show'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['show'],'intval');
	 $PowerBB->template->display('calendar');
     }


	    /**
	 * Get the Jump Sections List
	 */
	function _GetJumpSectionsList()
    {
		global $PowerBB;
       $PowerBB->functions->JumpForumsList();
		//////////
       $PowerBB->template->display('jump_forums_list');
   }

}

?>