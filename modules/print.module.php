<?php

// ##############################################################################||
// #
// #   PowerBB Version 2.0.0
// #   https://pbboard.info
// #   Copyright (c) 2009 by Abu.Rakan
// #
// #   filename : print.module.php
// #   print Subject
// #
// ##############################################################################||

(!defined('IN_PowerBB')) ? die() : '';

define('JAVASCRIPT_PowerCode',true);



define('CLASS_NAME','PowerBBPrintMOD');

include('common.php');
class PowerBBPrintMOD
{


	/**
	 * The main function , will require from kernel file "index.php"
	 */
	function run()
	{
		global $PowerBB;

		// Show the topic
		if ($PowerBB->_GET['show'])
		{
			$this->__GetSubject();
		}
		else
		{
			header("Location: index.php");
			exit;
		}
      $PowerBB->template->display('print_footer');
	}


	function __GetSubject()
	{
		global $PowerBB;

		//////////

		// Clean id from any string, that will protect us
		$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');

		// If the id is empty, so stop the page
		if (empty($PowerBB->_GET['id']))
		{            $PowerBB->functions->ShowHeader();
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}


      eval($PowerBB->functions->get_fetch_hooks('print_topic'));

		 // If time out For Editing Disable View Icon Edite
		$SubjectArr = array();
		$SubjectArr['where'] = array('id',$PowerBB->_GET['id']);

		$PowerBB->_CONF['template']['SubjectInfo'] = $PowerBB->core->GetInfo($SubjectArr,'subject');

		// There is no subject, so show error message
		if (!$PowerBB->core->GetInfo($SubjectArr,'subject'))
		{
     		$PowerBB->functions->ShowHeader();
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Sorry_requested_topic_does_not_exist']);
		}

		/** Get the section information **/
		$SecArr 			= 	array();
		$SecArr['where'] 	= 	array('id',$PowerBB->_CONF['template']['SubjectInfo']['section']);

		$this->SectionInfo = $PowerBB->core->GetInfo($SecArr,'section');


 			if (!empty($this->SectionInfo['section_password'])
				and !$PowerBB->_CONF['group_info']['admincp_allow']
				and !$PowerBB->functions->ModeratorCheck($this->SectionInfo['moderators']))
			{
     			if (empty($PowerBB->_COOKIE['pbb_sec'.$this->SectionInfo['id'].'_pass']))
        		{
        		    $PowerBB->_CONF['template']['section_info']['id'] = $this->SectionInfo['id'];
      				$PowerBB->template->display('forum_password');
      				$PowerBB->functions->stop();
     			}
              }

		/** Get section's group information and make some checks **/
		// Finally get the permissions of group
		// Get the subject and the subject's writer information
		$this->Info = $PowerBB->subject->GetSubjectWriterInfo(array('id'	=>	$PowerBB->_GET['id']));
       	if (!$this->Info)
		{
           if ($PowerBB->functions->section_group_permission($this->SectionInfo['id'],$PowerBB->_CONF['group_info']['id'],'write_subject'))
           {
		    $this->Info = $PowerBB->subject->GetSubjectGuestInfo(array('id'	=>	$PowerBB->_GET['id']));
		   }
		}

		// get the permissions of groups
		$GroupInfo = $PowerBB->functions->get_cache_permissions_group_id_numbr($this->Info['usergroup']);

        $PowerBB->template->assign('GroupInfo',$GroupInfo);

		// Moderator Check
		$Mod = $PowerBB->functions->ModeratorCheck($this->SectionInfo['moderators']);

       // Section Check subject Are not displayed in this category in public places
       	if ($this->SectionInfo['sec_section'])
		{
	       	if ($PowerBB->_CONF['template']['SubjectInfo']['sec_subject'] == '0')
			{
				$SubjectArr = array();
				$SubjectArr['field'] = array();
				$SubjectArr['field']['sec_subject'] = '1';

				$SubjectArr['where'] = array('id',$PowerBB->_GET['id']);

				$Update = $PowerBB->subject->UpdateSubject($SubjectArr);
			}
		}
		elseif ($this->SectionInfo['sec_section'] == '0'
		and $this->SectionInfo['hide_subject'] == '0'
		and $this->SectionInfo['review_subject'] == '0')
		{
	       	if ($PowerBB->_CONF['template']['SubjectInfo']['sec_subject'] == '1'
	       	and $PowerBB->_CONF['template']['SubjectInfo']['delete_topic'] == '0'
	       	and $PowerBB->_CONF['template']['SubjectInfo']['review_subject'] == '0')
			{
				$SubjectArr = array();
				$SubjectArr['field'] = array();
				$SubjectArr['field']['sec_subject'] = '0';

				$SubjectArr['where'] = array('id',$PowerBB->_GET['id']);

				$Update = $PowerBB->subject->UpdateSubject($SubjectArr);
			}
		}
		// The visitor can't show this section , so stop the page
        if ($PowerBB->functions->section_group_permission($this->SectionInfo['id'],$PowerBB->_CONF['group_info']['id'],'view_section') == '0')
		{
          if (!$PowerBB->_CONF['member_permission'])
              {
              $PowerBB->functions->ShowHeader();
              $PowerBB->template->display('login');
              $PowerBB->functions->error_stop();
			}
	        else
	        {
	        $PowerBB->functions->ShowHeader();
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Sorry_you_can_not_see_on_this_topic']);
	        }

		}
		if (!$PowerBB->functions->section_group_permission($this->SectionInfo['id'],$PowerBB->_CONF['group_info']['id'],'view_subject'))
		 {
          if (!$PowerBB->_CONF['member_permission'])
              {              	$PowerBB->functions->ShowHeader();
              $PowerBB->template->display('login');
              $PowerBB->functions->error_stop();
			}
	        else
	        {
	        $PowerBB->functions->ShowHeader();
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['can_not_view_this_subject']);
	        }
		}


		if ($PowerBB->_CONF['group_info']['view_subject'] == 0)
		{
          if (!$PowerBB->_CONF['member_permission'])
              {
              $PowerBB->functions->ShowHeader();
              $PowerBB->template->display('login');
              $PowerBB->functions->error_stop();
			}
	        else
	        {
	        $PowerBB->functions->ShowHeader();
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['can_not_view_this_subject']);
	        }
		}


       		// if section Allw hide subject can't show this subject  , so stop the page
   		if ($this->SectionInfo['hide_subject']
   		and !$PowerBB->functions->ModeratorCheck($this->SectionInfo['moderators']))
   		{

	   		if ($PowerBB->_CONF['member_row']['username'] != $PowerBB->_CONF['template']['SubjectInfo']['writer'])
	   		{
	   		$PowerBB->functions->ShowHeader();
	        $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Sorry_you_can_not_see_on_this_topic']);
	        }
        }

        if ($PowerBB->_CONF['template']['SubjectInfo']['review_subject']
   		and !$PowerBB->functions->ModeratorCheck($this->SectionInfo['moderators']))
   		{

	   		if ($PowerBB->_CONF['member_row']['username'] != $PowerBB->_CONF['template']['SubjectInfo']['writer']
	   		and 'Guest' != $PowerBB->_CONF['template']['SubjectInfo']['writer'])
	   		{
	   		$PowerBB->functions->ShowHeader();
	        $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Sorry_you_can_not_see_on_this_topic']);
	        }
        }

		//////////

		// hmmmmmmm , this subject deleted , so the members and visitor can't show it
		if ($PowerBB->_CONF['template']['SubjectInfo']['delete_topic']
			and !$PowerBB->_CONF['group_info']['admincp_allow'])
		{
		    $PowerBB->functions->ShowHeader();
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Subject_Was_Trasht']);
		}


		// The ID of subject
		$this->Info['subject_id'] = $PowerBB->_GET['id'];

		//////////

		// Kill XSS
		$PowerBB->functions->CleanVariable($this->Info,'html');
		// Kill SQL Injection
		$PowerBB->functions->CleanVariable($this->Info,'sql');
		$this->Info['native_write_time'] = $PowerBB->functions->_date($this->Info['native_write_time']);
		//////////
			$this->Info['text'] = $PowerBB->Powerparse->replace($this->Info['text']);
			$PowerBB->Powerparse->replace_smiles($this->Info['text']);

		// feltr sig Text
        $this->Info['text'] = $PowerBB->Powerparse->censor_words($this->Info['text']);
		// Send subject id to template engine
		$PowerBB->template->assign('subject_id',$PowerBB->_GET['id']);
		$PowerBB->template->assign('section_id',$this->Info['section']);
		$PowerBB->template->assign('Info',$this->Info);
         $PowerBB->template->display('print_subject');

		//////////

	}

}

?>
