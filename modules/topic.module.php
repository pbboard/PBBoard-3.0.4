<?php
session_start();
(!defined('IN_PowerBB')) ? die() : '';
define('CLASS_NAME','PowerBBTopicMOD');
include('common.php');
class PowerBBTopicMOD
{

	function run()
	{
		global $PowerBB;
		$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');
		if ($PowerBB->_GET['fastEdit'])
		{
			if($PowerBB->_GET['subject'])
			{
			$this->_ShowfastEditSubject();
			}
			else
			{
			$this->_ShowfastEdit();
			}
			exit();
		}
		$PowerBB->functions->ShowHeader();
		// Show the topic
		if ($PowerBB->_GET['show'])
		{
			$this->_ShowTopic();
		}
		else
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}

		// Can live without footer :) ?
		$PowerBB->functions->GetFooter();
	}

 	function _ShowfastEditSubject()
	{
		global $PowerBB;
		$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');
		 // If time out For Editing Disable View Icon Edite
		$SubjectArr = array();
		$SubjectArr['where'] = array('id',$PowerBB->_GET['id']);

		$PowerBB->_CONF['template']['SubjectInfo'] = $PowerBB->core->GetInfo($SubjectArr,'subject');

		if (!$PowerBB->functions->ModeratorCheck($PowerBB->_CONF['template']['SubjectInfo']['section']))
		{
		   if ($PowerBB->_CONF['member_row']['username'] != $PowerBB->_CONF['template']['SubjectInfo']['writer'])
		   {
			$PowerBB->functions->stop_no_foot($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
			}

			$time_out = $PowerBB->_CONF['info_row']['time_out']*60;
			if ($PowerBB->_CONF['now'] > $PowerBB->_CONF['template']['SubjectInfo']['write_time']+$time_out)
			{
			$PowerBB->functions->stop_no_foot($PowerBB->_CONF['template']['_CONF']['lang']['Reply_Editing_time_out']);
			}
		}

        $PowerBB->_CONF['template']['SubjectInfo']['text'] = $PowerBB->Powerparse->remove_strings($PowerBB->_CONF['template']['SubjectInfo']['text']);

		$PowerBB->template->assign('PostInfo',$PowerBB->_CONF['template']['SubjectInfo']);
		$PowerBB->template->assign('subject','1');
        $PowerBB->template->assign('text',$PowerBB->_CONF['template']['SubjectInfo']['text']);
         $PowerBB->_CONF['template']['while']['SmileRows'] = $PowerBB->icon->GetCachedSmiles();
        $PowerBB->template->display('fast_edit');
   }
 	function _ShowfastEdit()
	{
		global $PowerBB;
		$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');
		 // If time out For Editing Disable View Icon Edite
		$ReplyArr = array();
		$ReplyArr['where'] = array('id',$PowerBB->_GET['id']);

		$PowerBB->_CONF['template']['ReplyInfo'] = $PowerBB->core->GetInfo($ReplyArr,'reply');

		if (!$PowerBB->functions->ModeratorCheck($PowerBB->_CONF['template']['ReplyInfo']['section']))
		{
		   if ($PowerBB->_CONF['member_row']['username'] != $PowerBB->_CONF['template']['ReplyInfo']['writer'])
		   {
			$PowerBB->functions->stop_no_foot($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
			}

			$time_out = $PowerBB->_CONF['info_row']['time_out']*60;
			if ($PowerBB->_CONF['now'] > $PowerBB->_CONF['template']['ReplyInfo']['write_time']+$time_out)
			{
			$PowerBB->functions->stop_no_foot($PowerBB->_CONF['template']['_CONF']['lang']['Reply_Editing_time_out']);
			}
		}
        $PowerBB->_CONF['template']['ReplyInfo']['text'] = $PowerBB->Powerparse->remove_strings($PowerBB->_CONF['template']['ReplyInfo']['text']);

		$PowerBB->template->assign('PostInfo',$PowerBB->_CONF['template']['ReplyInfo']);
        $PowerBB->template->assign('text',$PowerBB->_CONF['template']['ReplyInfo']['text']);
         $PowerBB->_CONF['template']['while']['SmileRows'] = $PowerBB->icon->GetCachedSmiles();
        $PowerBB->template->display('fast_edit');
   }
	function _ShowTopic()
	{
		global $PowerBB;


		$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');
		$PowerBB->_GET['count'] = (!isset($PowerBB->_GET['count'])) ? 1 : $PowerBB->_GET['count'];
		$PowerBB->_GET['count'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['count'],'intval');

      eval($PowerBB->functions->get_fetch_hooks('topic'));

		// If the id is empty, so stop the page
		if (empty($PowerBB->_GET['id']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}

		 // If time out For Editing Disable View Icon Edite
		$SubjectArr = array();
		$SubjectArr['where'] = array('id',$PowerBB->_GET['id']);

		$PowerBB->_CONF['template']['SubjectInfo'] = $PowerBB->core->GetInfo($SubjectArr,'subject');

		// There is no subject, so show error message
		if (!$PowerBB->core->GetInfo($SubjectArr,'subject'))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Sorry_requested_topic_does_not_exist']);
		}

    	//$PowerBB->_CONF['template']['SubjectInfo']['title'] 				= 	$PowerBB->functions->CleanVariable($PowerBB->_CONF['template']['SubjectInfo']['title'],'html');
    	//$PowerBB->_CONF['template']['SubjectInfo']['title'] 				= 	$PowerBB->functions->CleanVariable($PowerBB->_CONF['template']['SubjectInfo']['title'],'sql');

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
              $PowerBB->template->display('login');
              $PowerBB->functions->error_stop();
			}
	        else
	        {
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Sorry_you_can_not_see_on_this_topic']);
	        }

		}
		if (!$PowerBB->functions->section_group_permission($this->SectionInfo['id'],$PowerBB->_CONF['group_info']['id'],'view_subject'))
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

		$TagArr 			= 	array();
		$TagArr['where'] 	= 	array('subject_id',$PowerBB->_GET['id']);

		$PowerBB->_CONF['template']['while']['tags'] = $PowerBB->tag->GetSubjectList($TagArr);
		if (is_array($PowerBB->_CONF['template']['while']['tags'])
			and @sizeof($PowerBB->_CONF['template']['while']['tags']) > 0)
		{
			$PowerBB->template->assign('STOP_TAGS_TEMPLATE',false);
		}
		else
		{
			$PowerBB->template->assign('STOP_TAGS_TEMPLATE',true);
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


       		// if section Allw hide subject can't show this subject  , so stop the page
   		if ($this->SectionInfo['hide_subject']
   		and !$PowerBB->functions->ModeratorCheck($this->SectionInfo['moderators']))
   		{

	   		if ($PowerBB->_CONF['member_row']['username'] != $PowerBB->_CONF['template']['SubjectInfo']['writer'])
	   		{
	        $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Sorry_you_can_not_see_on_this_topic']);
	        }
        }

        if ($PowerBB->_CONF['template']['SubjectInfo']['review_subject']
   		and !$PowerBB->functions->ModeratorCheck($this->SectionInfo['moderators']))
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

	     	if ($this->SectionInfo['hide_subject']
	       or $PowerBB->_CONF['template']['SubjectInfo']['sec_subject']
	       or $PowerBB->_CONF['template']['SubjectInfo']['review_subject']
	       or $PowerBB->_CONF['template']['SubjectInfo']['delete_topic'])
	       {

     		$UpdateOnline 			= 	array();
			$UpdateOnline['field']	=	array();

			$UpdateOnline['field']['user_location'] 	= $PowerBB->_CONF['template']['_CONF']['lang']['Seen_the'] .	' <a href="index.php?page=forum&show=1&amp;id=' . $this->SectionInfo['id'] . '">' . $PowerBB->functions->CleanVariable($this->SectionInfo['title'],'sql') . '</a>';
			$UpdateOnline['field']['section_id'] 	    =  $PowerBB->_CONF['template']['SubjectInfo']['section'];
			$UpdateOnline['field']['subject_show'] 	    =  $this->SectionInfo['parent'];
			$UpdateOnline['where']						=	array('username',$PowerBB->_CONF['member_row']['username']);

			$update = $PowerBB->core->Update($UpdateOnline,'online');

	       }
	       else
	       {
     		$UpdateOnline 			= 	array();
			$UpdateOnline['field']	=	array();

			$UpdateOnline['field']['user_location'] 	= 	$PowerBB->_CONF['template']['_CONF']['lang']['Seen_the_topic'].' <a href="index.php?page=topic&amp;show=1&amp;id=' . $PowerBB->_GET['id'] . '">' . $PowerBB->functions->CleanVariable($PowerBB->_CONF['template']['SubjectInfo']['title'],'sql') . '</a>';
			$UpdateOnline['field']['section_id'] 	    =  $PowerBB->_CONF['template']['SubjectInfo']['section'];
			$UpdateOnline['field']['subject_id'] 	    =  $PowerBB->_GET['id'];
			$UpdateOnline['field']['subject_show'] 	    =  $this->SectionInfo['parent'];
			$UpdateOnline['where']						=	array('username',$PowerBB->_CONF['member_row']['username']);

			$update = $PowerBB->core->Update($UpdateOnline,'online');
		   }
     	}

     	// Where is the Visitor now?
		if (!$PowerBB->_CONF['member_permission'])
     	{

	     	if ($this->SectionInfo['hide_subject']
	       or $PowerBB->_CONF['template']['SubjectInfo']['sec_subject']
	       or $PowerBB->_CONF['template']['SubjectInfo']['review_subject']
	       or $PowerBB->_CONF['template']['SubjectInfo']['delete_topic'])
	       {

     		$UpdateOnline 			= 	array();
			$UpdateOnline['field']	=	array();

			$UpdateOnline['field']['user_location'] 	= $PowerBB->_CONF['template']['_CONF']['lang']['Seen_the'] .	' <a href="index.php?page=forum&show=1&amp;id=' . $this->SectionInfo['id'] . '">' . $PowerBB->functions->CleanVariable($this->SectionInfo['title'],'sql') . '</a>';
			$UpdateOnline['field']['section_id'] 	    =  $PowerBB->_CONF['template']['SubjectInfo']['section'];
			$UpdateOnline['field']['subject_show'] 	    =  $this->SectionInfo['parent'];
			$UpdateOnline['where']						=	array('user_ip',$PowerBB->_CONF['ip']);

			$update = $PowerBB->core->Update($UpdateOnline,'online');

	       }
	       else
	       {
     		$UpdateOnline 			= 	array();
			$UpdateOnline['field']	=	array();

			$UpdateOnline['field']['user_location'] 	= 	$PowerBB->_CONF['template']['_CONF']['lang']['Seen_the_topic'].' <a href="index.php?page=topic&amp;show=1&amp;id=' . $PowerBB->_GET['id'] . '">' . $PowerBB->functions->CleanVariable($PowerBB->_CONF['template']['SubjectInfo']['title'],'sql') . '</a>';
			$UpdateOnline['field']['section_id'] 	    =  $PowerBB->_CONF['template']['SubjectInfo']['section'];
			$UpdateOnline['field']['subject_id'] 	    =  $PowerBB->_GET['id'];
			$UpdateOnline['field']['subject_show'] 	    =  $this->SectionInfo['parent'];
			$UpdateOnline['where']						=	array('user_ip',$PowerBB->_CONF['ip']);

			$update = $PowerBB->core->Update($UpdateOnline,'online');
		  }
     	}

       $PowerBB->template->assign('write_time',$PowerBB->_CONF['template']['SubjectInfo']['write_time']);


        $time_out = $PowerBB->_CONF['info_row']['time_out']*60;

        if ($PowerBB->_CONF['now'] > $PowerBB->_CONF['template']['SubjectInfo']['write_time']+$time_out != false)
        {
              $PowerBB->template->assign('timeout',false);
		}
		else
		{
			$PowerBB->template->assign('timeout',true);
		}


        $SecSubject2 			= 	array();
		$SecSubject2['where'] 	= 	array('id',$this->SectionInfo['parent']);

		$Section_rwo2 = $PowerBB->core->GetInfo($SecSubject2,'section');
        $PowerBB->_CONF['template']['SectionGroup']['write_reply'] = $PowerBB->functions->section_group_permission($PowerBB->_CONF['template']['SubjectInfo']['section'],$PowerBB->_CONF['group_info']['id'],'write_reply');
        $PowerBB->_CONF['template']['SectionGroup']['write_subject'] = $PowerBB->functions->section_group_permission($PowerBB->_CONF['template']['SubjectInfo']['section'],$PowerBB->_CONF['group_info']['id'],'write_subject');

		// assigns
		$PowerBB->template->assign('section_info',$this->SectionInfo);
        $PowerBB->template->assign('sec_main_title',$Section_rwo2['title']);
	    $PowerBB->template->assign('sec_main_id',$Section_rwo2['id']);
		$PowerBB->template->assign('Mod',$Mod);
		$PowerBB->template->assign('subject_id',$PowerBB->_GET['id']);
		$PowerBB->template->assign('section_id',$PowerBB->_CONF['template']['SubjectInfo']['section']);
       	$PowerBB->template->assign('show_sig',$this->SectionInfo['show_sig']);
        $PowerBB->template->assign('count_peg',$PowerBB->_GET['count']);
        $PowerBB->template->assign('write_reply',$PowerBB->functions->section_group_permission($PowerBB->_CONF['template']['SubjectInfo']['section'],$PowerBB->_CONF['group_info']['id'],'write_reply'));
        $PowerBB->template->assign('write_subject',$PowerBB->functions->section_group_permission($PowerBB->_CONF['template']['SubjectInfo']['section'],$PowerBB->_CONF['group_info']['id'],'write_subject'));
		// Aha, there are tags in this subject
		if ($PowerBB->_CONF['template']['while']['tags'] != false)
		{
			$PowerBB->template->assign('SHOW_TAGS',true);
		}
		else
		{
			$PowerBB->template->assign('SHOW_TAGS',false);
		}

		$PowerBB->template->assign('count_peg',$PowerBB->_GET['count']);


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

       if ($PowerBB->_CONF['group_info']['admincp_allow']
       && $PowerBB->_CONF['group_info']['admincp_member'])
       {
          $PowerBB->template->assign('mod_edit_member',1);
       }
       else
       {
          $PowerBB->template->assign('mod_edit_member',0);

       }

		//////////

		//show list last 5 posts member
		if ($PowerBB->_CONF['info_row']['show_list_last_5_posts_member'] == 1)
		{
		$getid = $PowerBB->_GET['id'];

		$LastSubjectWriterArr 							= 	array();
		$LastSubjectWriterArr['proc'] 					= 	array();
		$LastSubjectWriterArr['proc']['*'] 				= 	array('method'=>'clean','param'=>'html');

		$LastSubjectWriterArr['where']					=	array();
		$LastSubjectWriterArr['where'][0]				=	array();
		$LastSubjectWriterArr['where'][0]['name'] 	    = 	'review_subject<>1 AND sec_subject<>1 AND delete_topic<>1 and writer';
		$LastSubjectWriterArr['where'][0]['oper']		=	'=';
		$LastSubjectWriterArr['where'][0]['value']		=	$PowerBB->_CONF['template']['SubjectInfo']['writer'];

		$LastSubjectWriterArr['order']					=	array();
		$LastSubjectWriterArr['order']['field']			=	'id';
		$LastSubjectWriterArr['order']['type']			=	'DESC';
		$LastSubjectWriterArr['limit']			        =	$PowerBB->_CONF['info_row']['last_subject_writer_nm'];


       $PowerBB->_CONF['template']['while']['Writer_subjectList'] = $PowerBB->subject->GetSubjectList($LastSubjectWriterArr);
       $PowerBB->template->assign('LastSubjectWriter_nm',@sizeof($PowerBB->_CONF['template']['while']['Writer_subjectList']));


      }


		//////////
		//show Award member
       $ALL_Awards_nm = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT id FROM " . $PowerBB->table['award'] . " "));
       if ($ALL_Awards_nm > 0)
		{

		$AwardArr 							= 	array();
		$AwardArr['proc'] 					= 	array();
		$AwardArr['proc']['*'] 				= 	array('method'=>'clean','param'=>'html');

		$AwardArr['where']					=	array();
		$AwardArr['where'][0]				=	array();
		$AwardArr['where'][0]['name']		=	'username';
		$AwardArr['where'][0]['oper']		=	'=';
		$AwardArr['where'][0]['value']		=	$PowerBB->_CONF['template']['SubjectInfo']['writer'];

		$AwardArr['order']					=	array();
		$AwardArr['order']['field']			=	'id';
		$AwardArr['order']['type']			=	'DESC';


       $PowerBB->_CONF['template']['while']['AwardsList'] = $PowerBB->core->GetList($AwardArr,'award');
       $PowerBB->template->assign('Awards_nm',@sizeof($PowerBB->_CONF['template']['while']['AwardsList']));

       }

        //////////
 		  //$PowerBB->_CONF['template']['SubjectInfo']['title'] 				= 	$PowerBB->functions->CleanVariable($PowerBB->_CONF['template']['SubjectInfo']['title'],'html');
         // $PowerBB->_CONF['template']['SubjectInfo']['title'] = str_ireplace("'",'"', $PowerBB->_CONF['template']['SubjectInfo']['title']);
		 $PowerBB->_CONF['template']['SubjectInfo']['section'] = $PowerBB->functions->CleanVariable($PowerBB->_CONF['template']['SubjectInfo']['section'],'intval');
		 $PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');


     	//////////

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

		// Make register date in nice format to show it
		if (is_numeric($this->Info['register_date']))
		{
			$this->Info['register_date'] = $PowerBB->functions->year_date($this->Info['register_date']);
		}
       $cache = json_decode(base64_decode($PowerBB->_CONF['member_row']['style_cache']), true);
       $image_path = $PowerBB->_CONF['rows']['style']['image_path'];


		// Is writer online ?
		$CheckOnline = ($this->Info['logged'] < $PowerBB->_CONF['timeout']) ? false : true;

		// If the member is online , so store that in status variable
		($CheckOnline) ? $PowerBB->template->assign('user_online',true) : $PowerBB->template->assign('user_online',false);

		// Get username style
		if (empty($this->Info['username_style_cache']))
		{
			$this->Info['display_username'] = $this->Info['username'];
		}
		else
		{
			$this->Info['display_username'] = $this->Info['username_style_cache'];

			$this->Info['display_username'] = $PowerBB->functions->CleanVariable($this->Info['display_username'],'unhtml');
		}


			//get user title
			$titles = $PowerBB->usertitle->GetCachedTitles();
            $size = @sizeof($titles);
			for ($i = 0; $i <= $size; $i++)
			{
				if($titles[$size-1]['posts'] < $this->Info['posts'])
				{
				$user_titles = $titles[$size-1]['usertitle'];
				break;
				}
				if($titles[$i]['posts'] > $this->Info['posts'])
				{
				$user_titles = $titles[$i]['usertitle'];
				break;
				}
				if($this->Info['posts'] < $titles[1]['posts'])
				{
				$user_titles = $titles[1]['usertitle'];
				break;
				}
			}

            $PowerBB->template->assign('Usertitle',$user_titles);
            //////////

			//get user rating
			$ratings = $PowerBB->userrating->GetCachedRatings();
            $y = @sizeof($ratings);
			for ($b = 0; $b <= $y; $b++)
			{
				if($ratings[$y-1]['posts'] < $this->Info['posts'])
				{
				$user_ratings = $ratings[$y-1]['rating'];
				$user_posts = $ratings[$y-1]['posts'];
				break;
				}
				if($ratings[$b]['posts'] > $this->Info['posts'])
				{
				$user_ratings = $ratings[$b]['rating'];
				$user_posts = $ratings[$b]['posts'];
				break;
				}
				if($this->Info['posts'] < $ratings[1]['posts'])
				{
				$user_ratings = $ratings[1]['rating'];
				$user_posts = $ratings[1]['posts'];
				break;
				}
			}

			 $PowerBB->_CONF['template']['RatingInfo']['rating'] = $user_ratings;
			 $PowerBB->_CONF['template']['RatingInfo']['posts'] = $user_posts;
          /////////


		// The writer signture isn't empty
		if (!empty($this->Info['user_sig']))
		{
			// So , use the PowerCode in it
			$this->Info['user_sig'] = $PowerBB->Powerparse->replace($this->Info['user_sig']);
			$PowerBB->Powerparse->replace_smiles($this->Info['user_sig']);

		// feltr sig Text
        $this->Info['user_sig'] = $PowerBB->Powerparse->censor_words($this->Info['user_sig']);
		}

       if (!empty($this->Info['away_msg']))
		{
		// Kill SQL Injection
        $this->Info['away_msg'] = $PowerBB->functions->CleanVariable($this->Info['away_msg'],'html');
		$this->Info['away_msg'] =$PowerBB->functions->CleanVariable($this->Info['away_msg'],'sql');
        }

     $this->Info['user_website'] = $PowerBB->functions->CleanVariable($this->Info['user_website'],'html');
     $this->Info['user_info'] = $PowerBB->functions->CleanVariable($this->Info['user_info'],'html');



		// $this->Info['text'] = wordwrap($this->Info['text'], $PowerBB->_CONF['info_row']['wordwrap'], "<br />", true);

		// The visitor come from search engine , I don't mean Google :/ I mean the local search engine
		// so highlight the key word
		if (!empty($PowerBB->_GET['highlight']))
		{
			$PowerBB->_CONF['template']['SubjectInfo']['text'] = $PowerBB->Powerparse->content_search_highlight( $PowerBB->_CONF['template']['SubjectInfo']['text'], $PowerBB->_GET['highlight'] );
		}

		// If the PowerCode is allow , so use it :)
		if ($this->SectionInfo['use_power_code_allow'])
		{
	     $PowerBB->_CONF['template']['SubjectInfo']['text'] = $PowerBB->Powerparse->replace($PowerBB->_CONF['template']['SubjectInfo']['text']);
		}
		// The PowerCode isn't allow , don't use it :(
		else
		{
			$PowerBB->_CONF['template']['SubjectInfo']['text'] = nl2br($PowerBB->_CONF['template']['SubjectInfo']['text']);
		}

		// Convert smiles in subject to nice images :)

		$PowerBB->Powerparse->replace_smiles($PowerBB->_CONF['template']['SubjectInfo']['text']);
        $PowerBB->Powerparse->replace_wordwrap($PowerBB->_CONF['template']['SubjectInfo']['text']);

		// feltr Subject Text
         $this->Info['text'] = $PowerBB->Powerparse->censor_words($PowerBB->_CONF['template']['SubjectInfo']['text']);
        // Kill XSS
        $PowerBB->functions->CleanVariable($PowerBB->_CONF['template']['SubjectInfo']['text'],'html');
		// Kill SQL Injection
		$PowerBB->functions->CleanVariable($PowerBB->_CONF['template']['SubjectInfo']['text'],'sql');
			if ($PowerBB->_CONF['template']['SubjectInfo']['review_subject'])
			{
		      $PowerBB->template->assign('class_subject','tbar_review');
			}
			else
			{
	            $PowerBB->template->assign('class_subject','tbar_writer_info');
			}



		// We have attachment in this subject
		if ($PowerBB->_CONF['template']['SubjectInfo']['attach_subject'])
		{
			$AttachArr 							= 	array();
			$AttachArr['where']					= 	array();
			$AttachArr['where'][0] 				=	array();
			$AttachArr['where'][0]['name'] 		=	'subject_id';
			$AttachArr['where'][0]['oper'] 		=	'=';
			$AttachArr['where'][0]['value'] 	=	$PowerBB->_GET['id'];
			$AttachArr['where'][1] 				=	array();
			$AttachArr['where'][1]['con']		=	'AND';
			$AttachArr['where'][1]['name'] 		=	'reply';
			$AttachArr['where'][1]['oper'] 		=	'=';
			$AttachArr['where'][1]['value'] 	=	'0';

			// Get the attachment information
			$PowerBB->_CONF['template']['while']['AttachList'] = $PowerBB->core->GetList($AttachArr,'attach');
			if (!$PowerBB->_CONF['template']['while']['AttachList'])
			{
				$PowerBB->template->assign('ATTACH_SHOW',true);
			}
			else
			{
				$PowerBB->template->assign('ATTACH_SHOW',false);
			}
		}


		$topic_date = $PowerBB->functions->_date($this->Info['native_write_time']);
		//$topic_time = $PowerBB->functions->_time($this->Info['native_write_time']);

		$this->Info['native_write_time'] = $topic_date;

        $Aht=$PowerBB->_CONF['template']['_CONF']['lang']['THours'];
		$action_date = $PowerBB->functions->_date($this->Info['actiondate']);
        //$action_time = $PowerBB->functions->_time($this->Info['actiondate']);

		$this->Info['actiondate'] = $action_date;

       $this->Info['reason_edit'] = $PowerBB->Powerparse->censor_words($this->Info['reason_edit']);

		// Finally $this->Info to templates



        ///////////////////////////////////////////////
        // pager Up Subject
		$SubjectInfid = $PowerBB->_GET['id'];
		if ($PowerBB->functions->ModeratorCheck($this->SectionInfo['moderators']))
		{
		$SubjectInfReplyNum = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT id FROM " . $PowerBB->table['reply'] . " WHERE subject_id='$SubjectInfid' and delete_topic <>1"));
		}
		else
		{
		$SubjectInfReplyNum = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT id FROM " . $PowerBB->table['reply'] . " WHERE subject_id='$SubjectInfid' and delete_topic <>1 and review_reply <>1"));
		}
              // Update rely reply number to Subject & no Update in Again on the same link
             $Get_Page_URL  = "http://".$PowerBB->_SERVER['HTTP_HOST'].$PowerBB->_SERVER['REQUEST_URI'];
			   if ($PowerBB->_SERVER['HTTP_REFERER'] != $Get_Page_URL)
				{
		        	if ($SubjectInfReplyNum != $this->Info['reply_number'])
				   {
			     		$RepArr 					= 	array();
			     		$RepArr['reply_number']		=	$SubjectInfReplyNum;
			     		$RepArr['where'] 			= 	array('id',$PowerBB->_CONF['template']['SubjectInfo']['id']);

			     		$UpdateReplyNumber = $PowerBB->subject->UpdateReplyNumber($RepArr);
				   }
	           }
	           elseif ($SubjectInfReplyNum != $this->Info['reply_number'])
	           {
			     		$RepArr 					= 	array();
			     		$RepArr['reply_number']		=	$SubjectInfReplyNum;
			     		$RepArr['where'] 			= 	array('id',$PowerBB->_CONF['template']['SubjectInfo']['id']);

			     		$UpdateReplyNumber = $PowerBB->subject->UpdateReplyNumber($RepArr);
	           }

		   // Update subject review number
		    $SubjectInfReviewNum = $PowerBB->_CONF['template']['SubjectInfo']['review_reply'];
		    if ($SubjectInfReviewNum != $this->Info['review_reply'])
		     {
		      $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['subject'] . " SET review_reply='$SubjectInfReviewNum' WHERE id='$SubjectInfid'");
		     }

        	if ($SubjectInfReplyNum > $PowerBB->_CONF['info_row']['perpage'])
		   {


             if(isset($PowerBB->_GET['password']))
             {
             $passwor_d ='&amp;password=';
             }
            // Pager setup
			$ReplypagerArr = array();
			$ReplypagerArr['pager'] 				= 	array();
			$ReplypagerArr['pager']['total']		= 	$SubjectInfReplyNum;
			$ReplypagerArr['pager']['perpage'] 	= 	$PowerBB->_CONF['info_row']['perpage'];
			$ReplypagerArr['pager']['count'] 	= 	$PowerBB->_GET['count'];
			$ReplypagerArr['pager']['location'] 	= 	'index.php?page=topic&amp;show=1&amp;id=' . $PowerBB->_GET['id'] . $passwor_d . $PowerBB->_GET['password'];
			$ReplypagerArr['pager']['var'] 		= 	'count';
			$ReplypagerArr['subject_id'] 		= 	$PowerBB->_GET['id'];

			$this->RInfo = $PowerBB->core->GetInfo($ReplypagerArr,'reply');

		    $PowerBB->template->assign('pager_reply',$PowerBB->pager->show());

		   }

			if($PowerBB->_SERVER['HTTP_REFERER'] == $PowerBB->functions->GetForumAdress())
			{
			  $PowerBB->_GET['last_post'] = '1';
			}
         //// get count perpage
          if ($SubjectInfReplyNum > $PowerBB->_CONF['info_row']['perpage'])
          {
              	$subject_id = $PowerBB->_GET['id'];
				$Reply_NumArr = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['reply'] . " WHERE subject_id='$subject_id' and delete_topic <>1"));
				$ss_r = $PowerBB->_CONF['info_row']['perpage']/2+1;
				$roun_ss_r = round($ss_r, 0);
				$reply_number_r = $Reply_NumArr-$roun_ss_r;
				$pagenum_r = $reply_number_r/$PowerBB->_CONF['info_row']['perpage'];
				$round0_r = round($pagenum_r, 0);
				$countpage = $round0_r+1;
				$countpage = str_replace("-", '', $countpage);

		    $PowerBB->template->assign('count',$countpage);
			if ($PowerBB->_GET['last_post'])
			{
				$last_replyNumArr = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['reply'] . " WHERE subject_id='$subject_id' AND delete_topic<>1 AND review_reply<>1 ORDER BY id DESC LIMIT 0,1");
				$last_reply = $PowerBB->DB->sql_fetch_array($last_replyNumArr);

				if ($PowerBB->_CONF['info_row']['rewriterule'])
				{
				$PowerBB->functions->redirect2('t'.$PowerBB->_GET['id'].'&amp;count='.$countpage.'#'.$last_reply['id']);
				}
				else
				{
				$PowerBB->functions->redirect2('index.php?page=topic&amp;show=1&amp;id='.$PowerBB->_GET['id'].'&amp;count='.$countpage.'#'.$last_reply['id']);
				}
				exit;

			}
          }


        // Subject top
        $PowerBB->template->assign('Subjectinfo',$PowerBB->_CONF['template']['SubjectInfo']);
        $PowerBB->template->assign('subject_title',$PowerBB->Powerparse->censor_words($PowerBB->_CONF['template']['SubjectInfo']['title']));
        $PowerBB->template->assign('subject_id',$PowerBB->_GET['id']);
        $PowerBB->template->display('subject_top');

        // If the member isn't the writer , so register a new visit for the subject

		$PowerBB->_CONF['template']['SubjectInfo']['visitor'] +=1;
        $visitor = $PowerBB->_CONF['template']['SubjectInfo']['visitor'];

		$Subjectid = $PowerBB->_GET['id'];
		if ($PowerBB->_CONF['member_row']['username'] != $this->Info['writer'])
		{
		$update_visitor = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['subject'] . " SET visitor= '$visitor' WHERE id='$Subjectid'");
		}

         ////////

        ////////////	////////////////////////////////////////////////////////////
        // there is poll in this subject
          if ($PowerBB->_CONF['template']['SubjectInfo']['poll_subject'])
		   {
			 $PollArr 			= 	array();
			 $PollArr['where'] 	= 	array('subject_id',$PowerBB->_GET['id']);

			  $Poll = $PowerBB->core->GetInfo($PollArr,'poll');

			    // Aha, there is poll in this subject
	            $PowerBB->template->assign('Poll',$Poll);
                 $PowerBB->template->assign('Info',$PowerBB->_CONF['template']['SubjectInfo']);
	            $PowerBB->template->assign('subject_id',$PowerBB->_GET['id']);
		        $PowerBB->template->assign('poll_writer',$PowerBB->_CONF['template']['SubjectInfo']['writer']);
		        $PowerBB->template->assign('poll_section',$PowerBB->_CONF['template']['SubjectInfo']['section']);

				$PowerBB->template->display('show_poll_top');


			    $Poll['answers'] = json_decode($Poll['answers'], true);
				// Kill XSS
           if ($Poll['answers'])
		    {
	            foreach($Poll['answers'] as $answers_number => $answers)
	            {
                  if (!empty($answers))
                  {
					$subject_id  = $PowerBB->_GET['id'];
					$vote_nm = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['vote'] . " WHERE answer_number = " . $answers_number . " AND subject_id = " . $subject_id . " "));

					$answers =$PowerBB->Powerparse->censor_words($answers);
					$answers = $PowerBB->functions->CleanVariable($answers,'sql');
					$answers = $PowerBB->functions->CleanVariable($answers,'html');
					$PowerBB->template->assign('answers',$answers);
					$PowerBB->template->assign('answers_number',$answers_number);
					$PowerBB->template->assign('Vote',$vote_nm);

					$CheckArr 						= 	array();

					$CheckArr['where'][0] 			= 	array();
					$CheckArr['where'][0]['name'] 	= 	'subject_id';
					$CheckArr['where'][0]['oper'] 	= 	'=';
					$CheckArr['where'][0]['value'] 	= 	$PowerBB->_GET['id'];


					$CheckArr['where'][1] 			= 	array();
					$CheckArr['where'][1]['con'] 	= 	'AND';
					$CheckArr['where'][1]['name'] 	= 	'member_id';
					$CheckArr['where'][1]['oper'] 	= 	'=';
					$CheckArr['where'][1]['value'] 	= 	$PowerBB->_CONF['member_row']['id'];
					if (!$PowerBB->_CONF['member_permission'])
					{
					$CheckArr['where'][2] 			= 	array();
					$CheckArr['where'][2]['con'] 	= 	'AND';
					$CheckArr['where'][2]['name'] 	= 	'user_ip';
					$CheckArr['where'][2]['oper'] 	= 	'=';
					$CheckArr['where'][2]['value'] 	= 	$PowerBB->_CONF['ip'];
					}
					$ShowVote = $PowerBB->core->GetInfo($CheckArr,'vote');

					$PowerBB->template->assign('ShowVote',$ShowVote);
					$PowerBB->template->display('show_poll');
					}

					}
					$PowerBB->template->assign('Poll',$Poll);

					$CheckArr 						= 	array();

					$CheckArr['where'][0] 			= 	array();
					$CheckArr['where'][0]['name'] 	= 	'subject_id';
					$CheckArr['where'][0]['oper'] 	= 	'=';
					$CheckArr['where'][0]['value'] 	= 	$PowerBB->_GET['id'];

					$CheckArr['where'][1] 			= 	array();
					$CheckArr['where'][1]['con'] 	= 	'AND';
					$CheckArr['where'][1]['name'] 	= 	'member_id';
					$CheckArr['where'][1]['oper'] 	= 	'=';
					$CheckArr['where'][1]['value'] 	= 	$PowerBB->_CONF['member_row']['id'];

					if (!$PowerBB->_CONF['member_permission'])
					{

					$CheckArr['where'][1] 			= 	array();
					$CheckArr['where'][1]['con'] 	= 	'AND';
					$CheckArr['where'][1]['name'] 	= 	'user_ip';
					$CheckArr['where'][1]['oper'] 	= 	'=';
					$CheckArr['where'][1]['value'] 	= 	$PowerBB->_CONF['ip'];
					}
					$ShowVote = $PowerBB->core->GetInfo($CheckArr,'vote');

					$PowerBB->template->assign('ShowVote',$ShowVote);

					$subject_id  = $PowerBB->_GET['id'];
					$Allvote_nm = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['vote'] . " WHERE votes AND subject_id = " . $subject_id . " "));
					$PowerBB->template->assign('AllVote',$Allvote_nm);
					$PowerBB->template->assign('Info',$PowerBB->_CONF['template']['SubjectInfo']);
				}
		         $PowerBB->template->display('show_poll_down');
            }

         ////////////////////////////////////////////////////////////////////////

		$PowerBB->template->assign('Info',$this->Info);
		$PowerBB->template->assign('ReplierInfo',$this->Info);

         $start = ($PowerBB->_CONF['info_row']['perpage']);
		$PowerBB->template->assign('url',$PowerBB->functions->GetForumAdress().'index.php?page=topic&amp;show=1&amp;id='.$PowerBB->_GET['id']);


         if ($PowerBB->_CONF['info_row']['show_subject_all']== 1 or $PowerBB->_CONF['info_row']['show_subject_all']== 0 and $PowerBB->_GET['count']==1)
         {
            // show Subject
             $PowerBB->template->display('show_subject');
         }
         elseif ($PowerBB->_CONF['info_row']['show_subject_all']== 1 and $start != 0)
         {
                // Do nothing :)
         }
         elseif ($PowerBB->_CONF['info_row']['show_subject_all']== 0)
         {
           $PowerBB->template->display('show_subject_information');
         }


		// Show the replies
		$PowerBB->_GET['count'] = (!isset($PowerBB->_GET['count'])) ? 1 : $PowerBB->_GET['count'];
		$PowerBB->_GET['count'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['count'],'intval');

             if(isset($PowerBB->_GET['password']))
             {
             $passwor_d ='&amp;password=';
             }

		$ReplyArr = array();
		// Pager setup
		$ReplyArr['pager'] 				= 	array();
		$ReplyArr['pager']['total']		= 	$SubjectInfReplyNum;
		$ReplyArr['pager']['perpage'] 	= 	$PowerBB->_CONF['info_row']['perpage'];
		$ReplyArr['pager']['count'] 	= 	$PowerBB->_GET['count'];
		$ReplyArr['pager']['location'] 	= 	'index.php?page=topic&amp;show=1&amp;id=' . $PowerBB->_GET['id'] . $passwor_d . $PowerBB->_GET['password'];
		$ReplyArr['pager']['var'] 		= 	'count';
		$ReplyArr['subject_id'] 		= 	$PowerBB->_GET['id'];

		$ReplyArr['order'] 					= 	array();
		$ReplyArr['order']['field'] 		= 	'write_time';
		$ReplyArr['order']['type'] 			= 	'ASC';

		$this->RInfo = $PowerBB->reply->GetReplyWriterInfo($ReplyArr);
        if ($SubjectInfReplyNum > $PowerBB->_CONF['info_row']['perpage'])
		{
		 $PowerBB->template->assign('pager_reply',$PowerBB->pager->show());
		 }

		$PowerBB->template->assign('count',$PowerBB->_GET['count']);

        		//////////

		// Kill XSS
		// TODO :: it's better to kill XSS inside the loop
		$PowerBB->functions->CleanVariable($this->RInfo,'html');
		$n = @sizeof($this->RInfo);
		$this->x = 0;
		// Nice loop :D
		while ($n > $this->x)
		{

			$MemberArr 			= 	array();
			$MemberArr['where'] 	= 	array('username',$this->RInfo[$this->x]['writer']);

			$ReplierInfo = $PowerBB->core->GetInfo($MemberArr,'member');

			//get user title
			$titles = $PowerBB->usertitle->GetCachedTitles();
            $size = @sizeof($titles);
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
            $y = @sizeof($ratings);
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

			// Make register date in nice format to show it
			if (is_numeric($ReplierInfo['register_date']))
			{
			 $ReplierInfo['register_date'] = $PowerBB->functions->year_date($ReplierInfo['register_date']);
			}

			$cache = json_decode(base64_decode($PowerBB->_CONF['member_row']['style_cache']), true);
			$image_path = $PowerBB->_CONF['rows']['style']['image_path'];


			$CheckOnline = ($ReplierInfo['logged'] < $PowerBB->_CONF['timeout']) ? false : true;

		  ($CheckOnline) ? $PowerBB->template->assign('user_online',true) : $PowerBB->template->assign('user_online',false);

			// Get username style
			$ReplierInfo['display_username'] = $ReplierInfo['username_style_cache'];

			////////

			if (!empty($ReplierInfo['user_sig']))
			{
			$ReplierInfo['user_sig'] = $PowerBB->Powerparse->replace($ReplierInfo['user_sig']);
			$PowerBB->Powerparse->replace_smiles($ReplierInfo['user_sig']);

			// feltr sig Text
			$ReplierInfo['user_sig'] = $PowerBB->Powerparse->censor_words($ReplierInfo['user_sig']);
			// Kill XSS
			$PowerBB->functions->CleanVariable($ReplierInfo['user_sig'],'html');
			// Kill SQL Injection
			$PowerBB->functions->CleanVariable($ReplierInfo['user_sig'],'sql');
			}

			if (!empty($ReplierInfo['away_msg']))
			{
			// Kill XSS
			$ReplierInfo['away_msg'] = $PowerBB->functions->CleanVariable($ReplierInfo['away_msg'],'html');
			// Kill SQL Injection
			$ReplierInfo['away_msg'] = $PowerBB->functions->CleanVariable($ReplierInfo['away_msg'],'sql');

			}
			$ReplierInfo['user_website'] = $PowerBB->functions->CleanVariable($ReplierInfo['user_website'],'html');
			$ReplierInfo['user_info'] = $PowerBB->functions->CleanVariable($ReplierInfo['user_info'],'html');


			// get the permissions of groups
			$GroupInfo = $PowerBB->functions->get_cache_permissions_group_id_numbr($ReplierInfo['usergroup']);

			$PowerBB->template->assign('GroupInfo',$GroupInfo);

			$SubjectInfReplyNum = $SubjectInfReplyNum+$PowerBB->_GET['count']+1;

			$PowerBB->template->assign('ReplierInfo',$ReplierInfo);


			// The visitor come from search engine , I don't mean Google :/ I mean the local search engine
			// so highlight the key word
			if (!empty($PowerBB->_GET['highlight']))
			{
				$this->RInfo[$this->x]['text'] = $PowerBB->Powerparse->content_search_highlight( $this->RInfo[$this->x]['text'], $PowerBB->_GET['highlight'] );
			}
			// If the PowerCode is allow , use it
			if ($this->SectionInfo['use_power_code_allow'])
			{
				$this->RInfo[$this->x]['text'] = $PowerBB->Powerparse->replace($this->RInfo[$this->x]['text']);
			}
			// It's not allow , don't use it
			else
			{
				$this->RInfo[$this->x]['text'] = nl2br($this->RInfo[$this->x]['text']);
			}

                 eval($PowerBB->functions->get_fetch_hooks('topic_bady'));

			// Convert the smiles to image
			$PowerBB->Powerparse->replace_smiles($this->RInfo[$this->x]['text']);
			 $PowerBB->Powerparse->replace_wordwrap($this->RInfo[$this->x]['text']);

			// feltr Subject Text
	         $this->RInfo[$this->x]['text'] = $PowerBB->Powerparse->censor_words($this->RInfo[$this->x]['text']);
	        // Kill XSS
	        $PowerBB->functions->CleanVariable($this->RInfo[$this->x]['text'],'html');
			// Kill SQL Injection
			$PowerBB->functions->CleanVariable($this->RInfo[$this->x]['text'],'sql');
	    	$PowerBB->_CONF['template']['_CONF']['info_row']['show_list_last_5_posts_member'] = '0';
	          $PowerBB->template->assign('Awards_nm',0);
	         // If time out For Editing Disable View Icon Edite

			$PowerBB->_CONF['template']['ReplyInfo'] = $this->RInfo[$this->x];

	        $time_out = $PowerBB->_CONF['info_row']['time_out']*60;
             $PowerBB->template->assign('write_time',$PowerBB->_CONF['template']['ReplyInfo']['write_time']);

	        if ($PowerBB->_CONF['now'] > $PowerBB->_CONF['template']['ReplyInfo']['write_time']+$time_out != false)
	        {
	              $PowerBB->template->assign('timeout',false);
			}
			else
			{
				$PowerBB->template->assign('timeout',true);
			}

	       if ($PowerBB->_CONF['group_info']['admincp_allow'] && $PowerBB->_CONF['group_info']['admincp_member'])
	       {
	          $PowerBB->template->assign('mod_edit_member',1);
	       }
	       else
	       {
	          $PowerBB->template->assign('mod_edit_member',0);
	       }
	        $Aht=$PowerBB->_CONF['template']['_CONF']['lang']['THours'];
			$reply_date = $PowerBB->functions->_date($this->RInfo[$this->x]['write_time']);

			$action_date = $PowerBB->functions->_date($this->RInfo[$this->x]['actiondate']);

			$this->RInfo[$this->x]['write_time'] = $reply_date;
	        $this->RInfo[$this->x]['actiondate'] = $action_date;
			// We have attachment in this reply
			if ($this->RInfo[$this->x]['attach_reply'] and $this->RInfo[$this->x]['writer'] != 'Guest')
			{
				$u_id = $ReplierInfo['id'];
				$AttachArr 							= 	array();
				$AttachArr['where']					= 	array();
				$AttachArr['where'][0] 				=	array();
				$AttachArr['where'][0]['name'] 		=	'subject_id';
				$AttachArr['where'][0]['oper'] 		=	'=';
				$AttachArr['where'][0]['value'] 	=	$this->RInfo[$this->x]['id'];
				$AttachArr['where'][1] 				=	array();
				$AttachArr['where'][1]['con']		=	'AND';
				$AttachArr['where'][1]['name'] 		=	'reply';
				$AttachArr['where'][1]['oper'] 		=	'=';
				$AttachArr['where'][1]['value'] 	=	'1';

				// Get the attachment information
				$PowerBB->_CONF['template']['while']['AttachList'] = $PowerBB->core->GetList($AttachArr,'attach');
				if (!$PowerBB->_CONF['template']['while']['AttachList'])
				{
					$PowerBB->template->assign('ATTACH_SHOW',true);
				}
				else
				{
					$PowerBB->template->assign('ATTACH_SHOW',false);
				}

			}

			// $RInfo to templates


	         $this->RInfo[$this->x]['reason_edit'] = $PowerBB->Powerparse->censor_words($this->RInfo[$this->x]['reason_edit']);

	        $PowerBB->template->assign('resview_reply',$review_reply);
			$PowerBB->template->assign('ReviewInfo',$this->RInfo[$this->x]);
			$PowerBB->template->assign('Info',$this->RInfo[$this->x]);
			$PowerBB->template->assign('section',$this->Info['section']);
			$PowerBB->template->assign('reply_id',$this->RInfo[$this->x]['id']);
			$PowerBB->_CONF['template']['Info']['reply_id'] = $this->RInfo[$this->x]['id'];
	     		// reply number in Subject
				if ($PowerBB->_GET['count'] == '1')
				{
                $PowerBB->_CONF['template']['Info']['reply_number'] = $this->x+1;
				}
				elseif ($PowerBB->_GET['count'] == '2')
				{
				$PowerBB->_CONF['template']['Info']['reply_number'] = $this->x+1+$PowerBB->_CONF['info_row']['perpage'];
				}
				elseif ($PowerBB->_GET['count']> 2)
				{
				  $countn = $PowerBB->_GET['count']-1;
				$PowerBB->_CONF['template']['Info']['reply_number'] = $this->x+1+$PowerBB->_CONF['info_row']['perpage']*$countn;
				}
				if ($this->RInfo[$this->x]['review_reply'] and $PowerBB->functions->ModeratorCheck($this->SectionInfo['moderators']))
				{
				 $PowerBB->template->display('review_reply');
				}
	            elseif (!$this->RInfo[$this->x]['review_reply'])
	            {
				// Show the reply :)
				 $PowerBB->template->display('show_reply');
				}

                 $this->x += 1;

		}


     if ($PowerBB->_CONF['info_row']['samesubject_show'])
	 {
        $this->Info['title'] 	= 	$PowerBB->functions->CleanVariable($this->Info['title'],'html');
        $this->Info['title'] 	= 	$PowerBB->functions->CleanVariable($this->Info['title'],'sql');

		    $excludedWords = array();
		    $title = preg_split('#[ \r\n\t]+#', $this->Info['title'], -1, PREG_SPLIT_NO_EMPTY);
			$excludedWords = array_merge($excludedWords, $title);
	        $forum_not = $PowerBB->_CONF['info_row']['last_subject_writer_not_in'];
			if (!empty($excludedWords[0])
			 and $this->_MBstrlen($excludedWords[0]))
			{
		         $excludedWords0 = " AND title LIKE '%".$excludedWords[0]."%'";
			}
			if (!empty($excludedWords[1])
			and $this->_MBstrlen($excludedWords[1]))
			{
				$excludedWords1 = " AND title LIKE '%".$excludedWords[1]."%'";
			}
			if (!empty($excludedWords[2])
			and $this->_MBstrlen($excludedWords[2]))
			{
				$excludedWords2 = " AND title LIKE '%".$excludedWords[2]."%'";
			}
			if (!empty($excludedWords[3])
			and $this->_MBstrlen($excludedWords[3]))
			{
				$excludedWords3 = " AND title LIKE '%".$excludedWords[3]."%'";
			}
			if (!empty($excludedWords[4])
			and $this->_MBstrlen($excludedWords[4]))
			{
				$excludedWords4 = " AND title LIKE '%".$excludedWords[4]."%'";
			}
			if (!empty($excludedWords[5])
			and $this->_MBstrlen($excludedWords[5]))
			{
				$excludedWords5 = " AND title LIKE '%".$excludedWords[5]."%'";
			}
            $sqwer="'";
            if (empty($excludedWords1)
            and empty($excludedWords2))
			{
				$excludedWords1 = " AND title LIKE '%".$excludedWords[3]."%'";
				$excludedWords2 = " AND title LIKE '%".$excludedWords[4]."%'";
			}
            if (empty($excludedWords3)
            and empty($excludedWords4))
			{
				$excludedWords1 = " AND title LIKE '%".$excludedWords[0]."%'";
				$excludedWords2 = " AND title LIKE '%".$excludedWords[5]."%'";
			}

		$SubjectArr = array();
		$SubjectArr['where'] 				= 	array();
		$SubjectArr['where'][0] 			= 	array();
		$SubjectArr['where'][0]['name'] 	= 	'id not in ('.$PowerBB->_GET['id'].') AND section not in ('.$forum_not.') AND review_subject<>1 AND delete_topic<>1'.$excludedWords1.$excludedWords2.' AND sec_subject';
		$SubjectArr['where'][0]['oper'] 	= 	'<>';
		$SubjectArr['where'][0]['value'] 	= 	1;


		$SubjectArr['order'] = array();
		$SubjectArr['order']['field'] 	= 	'write_time';
		$SubjectArr['order']['type'] 	= 	'DESC';

		$SubjectArr['proc'] 						= 	array();
		$SubjectArr['proc']['*'] 					= 	array('method'=>'clean','param'=>'html');
		$SubjectArr['proc']['native_write_time'] 	= 	array('method'=>'date','store'=>'write_date','type'=>$PowerBB->_CONF['info_row']['timesystem']);
		$SubjectArr['proc']['write_time'] 			= 	array('method'=>'date','store'=>'reply_date','type'=>$PowerBB->_CONF['info_row']['timesystem']);

		$SubjectArr['limit'] = '5';

		$PowerBB->_CONF['template']['while']['SameSubject'] = $PowerBB->core->GetList($SubjectArr,'subject');

		if (!$PowerBB->_CONF['template']['while']['SameSubject'])
		{
			$PowerBB->_CONF['template']['NO_SAME'] = true;
		}

     }
		////////
		$Admin = false;

		if ($PowerBB->_CONF['member_permission'])
		{
			if ($PowerBB->_CONF['group_info']['admincp_allow']
				or $PowerBB->_CONF['group_info']['vice'])
			{
				$Admin = true;
			}
			else
			{
				if (isset($this->SectionInfo))
				{
					$AdminArr = array();
					$AdminArr['username'] = $PowerBB->_CONF['member_row']['username'];
					$AdminArr['section_id'] = $this->SectionInfo['id'];

					$Admin = $PowerBB->moderator->IsModerator($AdminArr);
				}
			}
		}


       // Show Next subject And previous subject:)
		 $idSubject = $PowerBB->_GET['id'];
		 $idSection = $this->Info['section'];
         $getnextsubject_query = $PowerBB->DB->sql_query("SELECT title,id FROM  " . $PowerBB->table['subject'] . " WHERE id > '$idSubject' and section='" . $idSection . "' AND review_subject<>1 AND delete_topic<>1 ORDER BY id ASC LIMIT 0,1");
         $getnextsubject_row   = $PowerBB->DB->sql_fetch_array($getnextsubject_query);


         $getpersubject_query = $PowerBB->DB->sql_query("SELECT title,id FROM " . $PowerBB->table['subject'] . " WHERE id < '$idSubject' and section='" . $idSection . "' AND review_subject<>1 AND delete_topic<>1 ORDER BY id DESC LIMIT 0,1");
         $getpersubject_row   = $PowerBB->DB->sql_fetch_array($getpersubject_query);

         $PowerBB->template->assign('getnextsubject_row',$getnextsubject_row['id']);
         $PowerBB->template->assign('getpersubject_row',$getpersubject_row['id']);
        $getpersubject_row['title'] 	= 	$PowerBB->functions->CleanVariable($getpersubject_row['title'],'html');
        $getnextsubject_row['title'] 	= 	$PowerBB->functions->CleanVariable($getnextsubject_row['title'],'html');

         $PowerBB->template->assign('getper_title_subject_row',$PowerBB->Powerparse->censor_words($getpersubject_row['title']));
         $PowerBB->template->assign('getnext_title_subject_row',$PowerBB->Powerparse->censor_words($getnextsubject_row['title']));

        ////////



		//$PowerBB->functions->GetEditorTools();

     	$PowerBB->template->assign('reply_title',$PowerBB->functions->CleanVariable($this->Info['title'],'html'));
     	$PowerBB->template->assign('subject_id',$PowerBB->_GET['id']);
     	$PowerBB->template->assign('id',$PowerBB->_GET['id']);
     	$PowerBB->template->assign('Admin',$Admin);
     	$PowerBB->template->assign('stick',$this->Info['stick']);
     	$PowerBB->template->assign('close',$this->Info['close']);
     	$PowerBB->template->assign('Info_row',$this->Info);
     	$PowerBB->template->assign('special',$this->Info['special']);
     	$PowerBB->template->assign('review_subject',$this->Info['review_subject']);
		$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');

       if($PowerBB->_CONF['group_info']['see_who_on_topic'])
       {
          $PowerBB->template->assign('see_who_on_topic',1);

	        /**
		 * Know who is in subject ?
		 */
		// Finally we get Who is in subject
		if (!$PowerBB->_CONF['group_info']['show_hidden'])
		{
		 $shwo_hide_browse = "hide_browse<>1 AND ";
		}
		$SubjectWhoArr 						= 	array();
		$SubjectWhoArr['proc'] 				= 	array();
		$SubjectWhoArr['proc']['*'] 			= 	array('method'=>'clean','param'=>'html');
		$SubjectWhoArr['where']				=	array();

		$SubjectWhoArr['where'][0]				=	array();
		$SubjectWhoArr['where'][0]['name'] 	= 	$shwo_hide_browse.'subject_id';
		$SubjectWhoArr['where'][0]['oper'] 	= 	'=';
		$SubjectWhoArr['where'][0]['value'] 	= 	$PowerBB->_GET['id'];

		$SubjectWhoArr['order'] 				= 	array();
		$SubjectWhoArr['order']['field'] 		= 	'last_move';
		$SubjectWhoArr['order']['type'] 		= 	'DESC';

		$PowerBB->_CONF['template']['while']['SubjectVisitor'] = $PowerBB->core->GetList($SubjectWhoArr,'online');

		$GuestNumberArr 						= 	array();
		$GuestNumberArr['where'] 				= 	array();

		$GuestNumberArr['where'][0] 			= 	array();
		$GuestNumberArr['where'][0]['name'] 	= 	'username_style';
		$GuestNumberArr['where'][0]['oper'] 	= 	'=';
		$GuestNumberArr['where'][0]['value'] 	= 	'Guest';

		$GuestNumberArr['where'][1] 			= 	array();
		$GuestNumberArr['where'][1]['con'] 		= 	'AND';
		$GuestNumberArr['where'][1]['name'] 	= 	'subject_id';
		$GuestNumberArr['where'][1]['oper'] 	= 	'=';
		$GuestNumberArr['where'][1]['value'] 	= 	$PowerBB->_GET['id'];

		 $GuestNumber = $PowerBB->core->GetNumber($GuestNumberArr,'online');

		$online_number = @sizeof($PowerBB->_CONF['template']['while']['SubjectVisitor']);
		$MemberNumber = $online_number-$GuestNumber;
		$PowerBB->template->assign('online_number',$online_number);
		$PowerBB->template->assign('GuestNumber',$GuestNumber);
		$PowerBB->template->assign('MemberNumber',$MemberNumber);
		if (!$PowerBB->_CONF['member_permission'])
		{
         $PowerBB->_CONF['template']['_CONF']['lang']['No_members_are_reading_this_topic_now'] = '';
		}


     }


		$PowerBB->template->assign('Subjectinfo',$Subjectinfo);
		$PowerBB->template->assign('id',$PowerBB->_GET['id']);

        ///////////////////////////////////////////////
        // pager Duwn Subject
		  //// get count perpage
			if ($SubjectInfReplyNum < $PowerBB->_CONF['info_row']['perpage'])
		   {

		   $PowerBB->template->assign('count',0);

		   }
          else
          {
			$last_page1 = $SubjectInfReplyNum/$PowerBB->_CONF['info_row']['perpage'];
			$last_pagef = round($last_page1, 0);
			$countpage = $last_pagef;
		    $PowerBB->template->assign('count',$countpage);
          }

 			if ($PowerBB->functions->ModeratorCheck($this->SectionInfo['moderators']))
			{

			        // show TopicMod List
					$TopicModArr 					= 	array();
					$TopicModArr['order']			=	array();
					$TopicModArr['order']['field']	=	'id';
					$TopicModArr['order']['type']	=	'DESC';
					$TopicModArr['proc'] 			= 	array();
					$TopicModArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');

					$PowerBB->_CONF['template']['while']['TopicModsList'] = $PowerBB->core->GetList($TopicModArr,'topicmod');

			      $TopicMods = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['topicmod'] . " WHERE id ");
				  while ($ModsList = $PowerBB->DB->sql_fetch_array($TopicMods))
			      {
					if ( strstr( ",".$ModsList['forums'].",", ",".$this->Info['section']."," ) and $ModsList['forums'] != '*' )
					{
						$PowerBB->template->assign('Multi_Moderation',1);
					}
					elseif ($ModsList['forums'] == '*')
					{
						$PowerBB->template->assign('Multi_Moderation',1);
					}
					else
					{
						$PowerBB->template->assign('Multi_Moderation',0);
					}
			       }

              }

            /////
	        if($PowerBB->_CONF['info_row']['captcha_type'] == 'captcha_Q_A')
			 {
				$question = $PowerBB->_CONF['info_row']['questions'];
				$answer = $PowerBB->_CONF['info_row']['answers'];
				$c1 = explode("\r\n",$question);
				$c2 = explode("\r\n",$answer);
				$rand = array_rand($c2);
				$question = $c1[$rand];
				$answer = $c2[$rand];
				$PowerBB->template->assign('question',$question);
				$PowerBB->template->assign('answer',$answer);
		     }
          // show Smiles in fast reply box
         $PowerBB->_CONF['template']['while']['SmileRows'] = $PowerBB->icon->GetCachedSmiles();

          $PowerBB->_CONF['template']['_CONF']['info_row']['title_quote'] = '1';
         eval($PowerBB->functions->get_fetch_hooks('topic_end'));

       $PowerBB->functions->JumpForumsList();

     	$PowerBB->template->display('topic_end');

   }

	function _MBstrlen($text)
	{
		global $PowerBB;

		if (function_exists('mb_strlen'))
		{
		$less_num = mb_strlen($text, 'UTF-8') >= 4;
		}
		else
		{
		$less_num = strlen(utf8_decode($text)) >= 4;
		}
		if($less_num)
		{
		 return true;
		}
		else
		{
		 return false;
		}

    }

}

?>
