<?php
session_start();
$CALL_SYSTEM = array();
$CALL_SYSTEM['USERRATING']         = 	true;

(!defined('IN_PowerBB')) ? die() : '';

define('CLASS_NAME','PowerBBTopicMOD');
include('common.php');
class PowerBBTopicMOD
{
	var $Info;
	var $SectionInfo;
	var $SectionGroup;
	var $RInfo;
	var $x = 0;
	var $foreach_array;

	/**
	 * The main function , will require from kernel file "index.php"
	 */
	function run()
	{
		global $PowerBB;
		$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');

		// Show the topic
		if ($PowerBB->_GET['show'])
		{		    $PowerBB->functions->ShowHeader();			$this->_ShowPost();
		}
		else
		{
			header("Location: index.php");
			exit;
		}

		// Can live without footer :) ?
		$PowerBB->functions->GetFooter();
	}



	function _ShowPost()
	{
		global $PowerBB;

	 // Check about everything
	 $this->__CheckSystem();
     $PowerBB->template->display('show_post');

	}

	function __CheckSystem()
	{
		global $PowerBB;
		$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');

		$ReplyArr = array();
		$ReplyArr['where'] = array('id',$PowerBB->_GET['id']);

		$PowerBB->_CONF['template']['ReplyInfo'] = $PowerBB->core->GetInfo($ReplyArr,'reply');


		 // If time out For Editing Disable View Icon Edite
		$SubjectArr = array();
		$SubjectArr['where'] = array('id',$PowerBB->_CONF['template']['ReplyInfo']['subject_id']);

		$PowerBB->_CONF['template']['SubjectInfo'] = $PowerBB->core->GetInfo($SubjectArr,'subject');

		// There is no subject, so show error message
		if (!$PowerBB->_CONF['template']['ReplyInfo'])
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Sorry_requested_topic_does_not_exist']);
		}

    	//$PowerBB->_CONF['template']['SubjectInfo']['title'] 				= 	$PowerBB->functions->CleanVariable($PowerBB->_CONF['template']['SubjectInfo']['title'],'html');
    	//$PowerBB->_CONF['template']['SubjectInfo']['title'] 				= 	$PowerBB->functions->CleanVariable($PowerBB->_CONF['template']['SubjectInfo']['title'],'sql');

		/** Get the section information **/
		$SecArr 			= 	array();
		$SecArr['where'] 	= 	array('id',$PowerBB->_CONF['template']['ReplyInfo']['section']);

		$this->SectionInfo = $PowerBB->core->GetInfo($SecArr,'section');

		// Kill XSS
		$PowerBB->functions->CleanVariable($this->SectionInfo,'html');
		$PowerBB->functions->CleanVariable($PowerBB->_CONF['template']['ReplyInfo'],'html');
		// Kill SQL Injection
		$PowerBB->functions->CleanVariable($PowerBB->_CONF['template']['ReplyInfo'],'sql');
		$PowerBB->functions->CleanVariable($this->SectionInfo,'sql');
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
		//////////

		// Moderator Check
		$Mod = $PowerBB->functions->ModeratorCheck($this->SectionInfo['id']);


		// The visitor can't show this section , so stop the page
		if ($this->SectionGroup['view_section'] == '0')
		{
          if (!$PowerBB->_CONF['member_permission'])
              {
              $PowerBB->template->display('login');
              $PowerBB->functions->error_stop();
			}
	        else
	        {
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Sorry_you_can_not_see_on_this_topic']);
	        }

		}
		if ($this->SectionGroup['view_subject'] == '0')
		{
          if (!$PowerBB->_CONF['member_permission'])
              {
              $PowerBB->template->display('login');
              $PowerBB->functions->error_stop();
			}
	        else
	        {
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['can_not_view_this_subject']);
	        }
		}
        if(!empty($this->SectionInfo['section_password'])){
     	$PowerBB->_CONF['template']['password'] = '&amp;password=' . base64_encode($this->SectionInfo['section_password']);
        }
		$TagArr 			= 	array();
		$TagArr['where'] 	= 	array('subject_id',$PowerBB->_CONF['template']['ReplyInfo']['subject_id']);

		$PowerBB->_CONF['template']['while']['tags'] = $PowerBB->tag->GetSubjectList($TagArr);
		if (is_array($PowerBB->_CONF['template']['while']['tags'])
			and sizeof($PowerBB->_CONF['template']['while']['tags']) > 0)
		{
			$PowerBB->template->assign('STOP_TAGS_TEMPLATE',false);
		}
		else
		{
			$PowerBB->template->assign('STOP_TAGS_TEMPLATE',true);
		}

		// Clean id from any string, that will protect us
		$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');
		$PowerBB->_GET['num'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['num'],'intval');


		// If the id is empty, so stop the page
		if (empty($PowerBB->_GET['id']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}

		if ($PowerBB->_CONF['group_info']['view_subject'] == 0)
		{
          if (!$PowerBB->_CONF['member_permission'])
              {
              $PowerBB->template->display('login');
              $PowerBB->functions->error_stop();
			}
	        else
	        {
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['can_not_view_this_subject']);
	        }
		}

       if ($PowerBB->functions->ModeratorCheck($this->SectionInfo['moderators']))
		{
			$PowerBB->template->assign('mod_toolbar',0);
		}
		else
		{
			$PowerBB->template->assign('mod_toolbar',1);
	       if ($PowerBB->_CONF['group_info']['edit_own_reply']== '1')
	       {
	        $PowerBB->_CONF['template']['_CONF']['group_info']['edit_own_reply'] = $PowerBB->functions->section_group_permission($this->SectionInfo['id'],$PowerBB->_CONF['group_info']['id'],'edit_own_reply');
	       }
	       if ($PowerBB->_CONF['group_info']['del_own_reply']== '1')
	       {
	        $PowerBB->_CONF['template']['_CONF']['group_info']['del_own_reply'] = $PowerBB->functions->section_group_permission($this->SectionInfo['id'],$PowerBB->_CONF['group_info']['id'],'del_own_reply');
	       }
	       if ($PowerBB->_CONF['group_info']['del_own_subject']== '1')
	       {
	        $PowerBB->_CONF['template']['_CONF']['group_info']['del_own_subject'] = $PowerBB->functions->section_group_permission($this->SectionInfo['id'],$PowerBB->_CONF['group_info']['id'],'del_own_subject');
	       }
	       if ($PowerBB->_CONF['group_info']['edit_own_subject']== '1')
	       {
	        $PowerBB->_CONF['template']['_CONF']['group_info']['edit_own_subject'] = $PowerBB->functions->section_group_permission($this->SectionInfo['id'],$PowerBB->_CONF['group_info']['id'],'edit_own_subject');
	       }
		}
       		// if section Allw hide subject can't show this subject  , so stop the page
   		if ($this->SectionInfo['hide_subject']
   		and !$PowerBB->functions->ModeratorCheck($PowerBB->_CONF['template']['SubjectInfo']['section']))
   		{

	   		if ($PowerBB->_CONF['member_row']['username'] != $PowerBB->_CONF['template']['SubjectInfo']['writer'])
	   		{
	        $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Sorry_you_can_not_see_on_this_topic']);
	        }
        }

        if ($PowerBB->_CONF['template']['SubjectInfo']['review_subject']
   		and !$PowerBB->functions->ModeratorCheck($PowerBB->_CONF['template']['SubjectInfo']['section']))
   		{

	   		if ($PowerBB->_CONF['member_row']['username'] != $PowerBB->_CONF['template']['SubjectInfo']['writer']
	   		and 'Guest' != $PowerBB->_CONF['template']['SubjectInfo']['writer'])
	   		{
	        $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Sorry_you_can_not_see_on_this_topic']);
	        }
        }

		//////////

		// hmmmmmmm , this subject deleted , so the members and visitor can't show it
		if ($PowerBB->_CONF['template']['SubjectInfo']['delete_topic']
			and !$PowerBB->_CONF['group_info']['admincp_allow'])
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Subject_Was_Trasht']);
		}

		//////////
		// Where is the member now?
		if ($PowerBB->_CONF['member_permission'])
     	{
     		$UpdateOnline 			= 	array();
			$UpdateOnline['field']	=	array();

			$UpdateOnline['field']['user_location'] 	= 	$PowerBB->_CONF['template']['_CONF']['lang']['Seen_the_topic'].' <a href="index.php?page=topic&amp;show=1&amp;id=' . $PowerBB->_GET['id'] . '">' . $PowerBB->_CONF['template']['SubjectInfo']['title'] . '</a>';
			$UpdateOnline['field']['section_id'] 	    =  $PowerBB->_CONF['template']['SubjectInfo']['section'];
			$UpdateOnline['field']['subject_id'] 	    =  $PowerBB->_GET['id'];
			$UpdateOnline['where']						=	array('username',$PowerBB->_CONF['member_row']['username']);

			$update = $PowerBB->core->Update($UpdateOnline,'online');
     	}

     	// Where is the Visitor now?
		if (!$PowerBB->_CONF['member_permission'])
     	{
     		$UpdateOnline 			= 	array();
			$UpdateOnline['field']	=	array();

			$UpdateOnline['field']['user_location'] 	= 	$PowerBB->_CONF['template']['_CONF']['lang']['Seen_the_topic'].' <a href="index.php?page=topic&amp;show=1&amp;id=' . $PowerBB->_CONF['template']['ReplyInfo']['subject_id'] . '">' . $PowerBB->_CONF['template']['SubjectInfo']['title'] . '</a>';
			$UpdateOnline['field']['section_id'] 	    =  $PowerBB->_CONF['template']['SubjectInfo']['section'];
			$UpdateOnline['field']['subject_id'] 	    =  $PowerBB->_CONF['template']['ReplyInfo']['subject_id'];
			$UpdateOnline['where']						=	array('user_ip',$PowerBB->_CONF['ip']);

			$update = $PowerBB->core->Update($UpdateOnline,'online');
     	}



        $time_out = $PowerBB->_CONF['info_row']['time_out']*60;

        if ($PowerBB->_CONF['now'] > $PowerBB->_CONF['template']['SubjectInfo']['write_time']+$time_out != false)
        {
              $PowerBB->template->assign('timeout',false);
		}
		else
		{
			$PowerBB->template->assign('timeout',true);
		}
		$MemberArr 			= 	array();
		$MemberArr['where'] 	= 	array('username',$PowerBB->_CONF['template']['ReplyInfo']['writer']);

		$ReplierInfo = $PowerBB->core->GetInfo($MemberArr,'member');

		if ($PowerBB->_CONF['template']['ReplyInfo']['attach_reply'])
		{
			$u_id = $ReplierInfo['id'];
			$AttachArr 							= 	array();
			$AttachArr['where']					= 	array();
			$AttachArr['where'][0] 				=	array();
			$AttachArr['where'][0]['name'] 		=	'subject_id';
			$AttachArr['where'][0]['oper'] 		=	'=';
			$AttachArr['where'][0]['value'] 	=	$PowerBB->_CONF['template']['ReplyInfo']['id'];
			$AttachArr['where'][1] 				=	array();
			$AttachArr['where'][1]['con']		=	'AND';
			$AttachArr['where'][1]['name'] 		=	'reply';
			$AttachArr['where'][1]['oper'] 		=	'=';
			$AttachArr['where'][1]['value'] 	=	'1';

			// Get the attachment information
			$PowerBB->_CONF['template']['while']['AttachList'] = $PowerBB->core->GetList($AttachArr,'attach');

			if ($PowerBB->_CONF['template']['while']['AttachList'] != false)
			{
				$PowerBB->functions->CleanVariable($PowerBB->_CONF['template']['while']['AttachList'],'html');
			}

			$PowerBB->template->assign('AttachList',$PowerBB->_CONF['template']['while']['AttachList']);

		}

		////////
	    // Extra Field info
	    $extraEmptyFields=$PowerBB->extrafield->getEmptyLoginFields(true);
	    $fieldsRow='';
	    foreach($extraEmptyFields AS $field)
         // Kill XSS
		$PowerBB->functions->CleanVariable($extraEmptyFields,'html');
		$PowerBB->functions->CleanVariable($extraEmptyFields,'sql');
	    $fieldsRow.=$field['name_tag'].',';
	    $PowerBB->_CONF['template']['while']['extrafield']=&$extraEmptyFields;

 			//get user title
			$titles = $PowerBB->usertitle->GetCachedTitles();
            $size = sizeof($titles);
			for ($i = 0; $i <= $size; $i++)
			{
				if($titles[$size-1]['posts'] < $ReplierInfo['posts'])
				{
				$user_titles = $titles[$size-1]['usertitle'];
				break;
				}
				if($titles[$i]['posts'] > $ReplierInfo['posts'])
				{
				$user_titles = $titles[$i]['usertitle'];
				break;
				}
				if($ReplierInfo['posts'] < $titles[1]['posts'])
				{
				$user_titles = $titles[1]['usertitle'];
				break;
				}
			}

            $PowerBB->template->assign('Usertitle',$user_titles);
            //////////

			//get user rating
			$ratings = $PowerBB->userrating->GetCachedRatings();
            $y = sizeof($ratings);
			for ($b = 0; $b <= $y; $b++)
			{
				if($ratings[$y-1]['posts'] < $ReplierInfo['posts'])
				{
				$user_ratings = $ratings[$y-1]['rating'];
				$user_posts = $ratings[$y-1]['posts'];
				break;
				}
				if($ratings[$b]['posts'] > $ReplierInfo['posts'])
				{
				$user_ratings = $ratings[$b]['rating'];
				$user_posts = $ratings[$b]['posts'];
				break;
				}
				if($ReplierInfo['posts'] < $ratings[1]['posts'])
				{
				$user_ratings = $ratings[1]['rating'];
				$user_posts = $ratings[1]['posts'];
				break;
				}
			}

			 $PowerBB->_CONF['template']['RatingInfo']['rating'] = $user_ratings;
			 $PowerBB->_CONF['template']['RatingInfo']['posts'] = $user_posts;

			if ($PowerBB->_CONF['template']['ReplyInfo']['review_reply'])
			{
		      $PowerBB->template->assign('class_reply','tbar_review');
			}
			else
			{
	          $PowerBB->template->assign('class_reply','tbar_writer_info');
			}


        $SecSubject2 			= 	array();
		$SecSubject2['where'] 	= 	array('id',$this->SectionInfo['parent']);

		$Section_rwo2 = $PowerBB->core->GetInfo($SecSubject2,'section');

		// The visitor come from search engine , I don't mean Google :/ I mean the local search engine
		// so highlight the key word
		if (!empty($PowerBB->_GET['highlight']))
		{
			$PowerBB->_CONF['template']['ReplyInfo']['text'] = $PowerBB->Powerparse->content_search_highlight( $PowerBB->_CONF['template']['ReplyInfo']['text'], $PowerBB->_GET['highlight'] );
		}
		// If the PowerCode is allow , use it
		if ($this->SectionInfo['use_power_code_allow'])
		{
			$PowerBB->_CONF['template']['ReplyInfo']['text'] = $PowerBB->Powerparse->replace($PowerBB->_CONF['template']['ReplyInfo']['text']);
		}
		// It's not allow , don't use it
		else
		{
			$PowerBB->_CONF['template']['ReplyInfo']['text'] = nl2br($PowerBB->_CONF['template']['ReplyInfo']['text']);
		}

		// Convert the smiles to image
		$PowerBB->Powerparse->replace_smiles($PowerBB->_CONF['template']['ReplyInfo']['text']);
		 $PowerBB->Powerparse->replace_wordwrap($PowerBB->_CONF['template']['ReplyInfo']['text']);

		// feltr Subject Text
         $PowerBB->_CONF['template']['ReplyInfo']['text'] = $PowerBB->Powerparse->censor_words($PowerBB->_CONF['template']['ReplyInfo']['text']);
        // Kill XSS
        $PowerBB->functions->CleanVariable($PowerBB->_CONF['template']['ReplyInfo']['text'],'html');
		// Kill SQL Injection
		$PowerBB->functions->CleanVariable($PowerBB->_CONF['template']['ReplyInfo']['text'],'sql');

			$last_page1 = $PowerBB->_GET['num']/$PowerBB->_CONF['info_row']['perpage'];
			$last_pagef = round($last_page1, 0);
			$perpage_r = $last_pagef;

		// assigns
		$PowerBB->template->assign('section_info',$this->SectionInfo);
        $PowerBB->template->assign('sec_main_title',$Section_rwo2['title']);
	    $PowerBB->template->assign('sec_main_id',$Section_rwo2['id']);
		 $PowerBB->template->assign('ReplierInfo',$ReplierInfo);

		 $PowerBB->template->assign('Info',$PowerBB->_CONF['template']['ReplyInfo']);
         $PowerBB->_CONF['template']['Info']['reply_id'] = $PowerBB->_CONF['template']['ReplyInfo']['id'];
         $PowerBB->_CONF['template']['Info']['reply_number'] = $PowerBB->_GET['num'];
		 $PowerBB->template->assign('SubjectInfo',$PowerBB->_CONF['template']['SubjectInfo']);
        $PowerBB->template->assign('subject_title',$PowerBB->Powerparse->censor_words($PowerBB->_CONF['template']['SubjectInfo']['title']));
		 $PowerBB->template->assign('subject_id',$PowerBB->_CONF['template']['SubjectInfo']['id']);
		 $PowerBB->template->assign('view_single_post','0');
		$PowerBB->_CONF['template']['ReplierInfo']['display_username'] = $ReplierInfo['username_style_cache'];
       $image_path = $PowerBB->_CONF['rows']['style']['image_path'];

		$CheckOnline = ($ReplierInfo['logged'] < $PowerBB->_CONF['timeout']) ? false : true;
		($CheckOnline) ? $PowerBB->template->assign('user_online',true) : $PowerBB->template->assign('user_online',false);

 		$PowerBB->_CONF['template']['Info']['count'] = $perpage_r;
 		$PowerBB->_CONF['template']['Info']['write_time'] = $PowerBB->functions->_date($PowerBB->_CONF['template']['ReplyInfo']['write_time']);
		$PowerBB->_CONF['template']['Info']['actiondate'] = $PowerBB->functions->_date($PowerBB->_CONF['template']['ReplyInfo']['actiondate']);

		// Make register date in nice format to show it
		if (is_numeric($PowerBB->_CONF['template']['ReplierInfo']['register_date']))
		{
			$PowerBB->_CONF['template']['ReplierInfo']['register_date'] = $PowerBB->functions->year_date($PowerBB->_CONF['template']['ReplierInfo']['register_date']);
		}


 	}


}

?>
