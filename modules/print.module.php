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
		{		      $PowerBB->functions->ShowHeader();
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}

		$SubjectArr = array();
		$SubjectArr['where'] = array('id',$PowerBB->_GET['id']);

		$this->Info = $PowerBB->core->GetInfo($SubjectArr,'subject');
		//////////


		/** Get the section information **/
		$SecArr 			= 	array();
		$SecArr['where'] 	= 	array('id',$this->Info['section']);

		$this->SectionInfo = $PowerBB->core->GetInfo($SecArr,'section');


		/** Get section's group information and make some checks **/
		$SecGroupArr 						= 	array();
		$SecGroupArr['where'] 				= 	array();
		$SecGroupArr['where'][0]			=	array();
		$SecGroupArr['where'][0]['name'] 	= 	'section_id';
		$SecGroupArr['where'][0]['value'] 	= 	$this->SectionInfo['id'];
		$SecGroupArr['where'][1]			=	array();
		$SecGroupArr['where'][1]['con']		=	'AND';
		$SecGroupArr['where'][1]['name']	=	'group_id';
		$SecGroupArr['where'][1]['value']	=	$PowerBB->_CONF['group_info']['id'];

		// Finally get the permissions of group
		$this->SectionGroup = $PowerBB->core->GetInfo($SecGroupArr,'sectiongroup');

		// The visitor can't show this section , so stop the page
		if ($this->SectionGroup['view_section'] == '0')
		{
          if (!$PowerBB->_CONF['member_permission'])
              {		      $PowerBB->functions->ShowHeader();
              $PowerBB->template->display('login');
              $PowerBB->functions->error_stop();
			}
	        else
	        {		      $PowerBB->functions->ShowHeader();
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Sorry_you_can_not_see_on_this_topic']);
	        }

		}
		// There is no subject, so show error message
		if (!$this->Info)
		{		    $PowerBB->functions->ShowHeader();
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Requested_topic_does_not_exist']);
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
