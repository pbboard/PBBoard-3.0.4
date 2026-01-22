<?php
(!defined('IN_PowerBB')) ? die() : '';

define('CLASS_NAME','PowerBBForumMOD');
include('common.php');
class PowerBBForumMOD
{
	function run()
	{
		global $PowerBB;

		// Clean id from any strings
		$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');

		// Get section information and set it in $this->Section
		$SecArr 			= 	array();
		$SecArr['where'] 	= 	array('id',$PowerBB->_GET['id']);

		$this->Section = $PowerBB->core->GetInfo($SecArr,'section');

		$PowerBB->template->assign('SECTION_RSS',true);
		$PowerBB->template->assign('SECTION_ID',$PowerBB->_GET['id']);
		if (isset($PowerBB->_GET['sort']))
		{
			if ($PowerBB->_GET['sort'] != 'asc'
            and $PowerBB->_GET['sort'] != 'id'
			and $PowerBB->_GET['sort'] != 'reply_number'
			and $PowerBB->_GET['sort'] != 'visitor'
			and $PowerBB->_GET['sort'] != 'rating'
			and $PowerBB->_GET['sort'] != 'writer'
			and $PowerBB->_GET['sort'] != 'write_time')
			{
	             header('HTTP/1.1 404 Not Found');
	             exit();
			}
		}

		if (isset($PowerBB->_GET['orderby']))
		{
			if ($PowerBB->_GET['orderby'] != '1')
			{
	             header('HTTP/1.1 404 Not Found');
	             exit();
			}
		}


		/** Browse the forum **/
		if ($PowerBB->_GET['show'] == '1')
		{
	     if($PowerBB->_COOKIE['pbb_sec'.$this->Section['id'].'_pass'] != $this->Section['section_password'])
    	 {
    	 		@ob_start();
				setcookie("pbb_sec".$this->Section['id']."_pass","");

    	 }
    		$PowerBB->functions->ShowHeader();
			$this->_BrowseForum();
		}
		/** **/
		elseif ($PowerBB->_GET['password_check'])
		{
			$this->_PasswordCheck();
		}
		/** Show the results of search **/
		elseif ($PowerBB->_GET['start'] == '1')
		{
		    $PowerBB->functions->ShowHeader();
			$this->_SearchSection();
		}
        elseif($PowerBB->_GET['option'] == '1')
	    {
		  $PowerBB->functions->ShowHeader();
		 $this->_StartSearch();
		}
        elseif($PowerBB->_GET['forum_run_Hooks'] == '1')
	    {
         $PowerBB->functions->forum_run_Hooks();
		}

		else
		{
			 $PowerBB->functions->ShowHeader();
             $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Sorry_url_not_true']);
             $PowerBB->functions->GetFooter();
		}

		$PowerBB->functions->GetFooter();
	}


	/**
	 * Get all things about section , subjects of the sections , announcement and sub sections to show it
	 * Yes it's long list :\
	 */
	function _BrowseForum()
	{
		global $PowerBB;

		$this->_GeneralProcesses();

		$this->_SectionOnline();

		$this->_GetModeratorsList();

		$this->_GetAnnouncementList();

        $this->_GetSubSection();

		$this->_GetSubjectList();

		$this->_CallTemplate();

   		$this->_GetJumpSectionsList();

	}

	function _GeneralProcesses($check=false)
	{
		global $PowerBB;


		// Clear section information from any denger
		$PowerBB->functions->CleanVariable($this->Section,'html');

		$PowerBB->template->assign('section_info',$this->Section);

		/** Get section's group information and make some checks **/
		// Finally get the permissions of group
		// This section isn't exists
		if (!$this->Section)
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Section_does_not_exist']);
		}
		// This member can't view this section
		if ($PowerBB->functions->section_group_permission($this->Section['id'],$PowerBB->_CONF['group_info']['id'],'view_section',$this->Section['sectiongroup_cache']) != '1')
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['can_not_view_section']);
		}

		// This is main section , so we can't get subjects list from it
		if ($this->Section['main_section'])
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['thes_main_section']);
		}

       if ($PowerBB->functions->section_group_permission($this->Section['id'],$PowerBB->_CONF['group_info']['id'],'view_subject',$this->Section['sectiongroup_cache']) == '0' and $this->Section['parent'])
		{
		$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['You_are_not_allowed_access_to_the_contents_of_this_forum']);
		}

		if ($PowerBB->_CONF['group_info']['view_section'] == 0)
		{
          if (!$PowerBB->_CONF['member_permission'])
              {
              $PowerBB->template->display('login');
              $PowerBB->functions->error_stop();
			}
	        else
	        {
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['can_not_view_section']);
	        }
		}

		// This section is link , so we should go to another site
		if ($this->Section['linksection'])
		{
   	        // The number of section's Visitors

			$this->Section['linkvisitor'] +=1;
	        $visitor = $this->Section['linkvisitor'];
			$Sectionid = $PowerBB->_GET['id'];
		    $update_visitor_Section = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['section'] . " SET linkvisitor= $visitor WHERE id= $Sectionid ");

     		// Update section's cache
     		$UpdateArr 				= 	array();
     		$UpdateArr['parent'] 	= 	$this->Section['parent'];

     		$update_cache = $PowerBB->section->UpdateSectionsCache($UpdateArr);


		    $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Please_wait_will_be_referred_to'] . $this->Section['linksite']);
			$PowerBB->functions->redirect($this->Section['linksite'],3);
			$PowerBB->functions->stop();
		}

		// hmmmm , this section protect by password so request the password
		if (!$check)
		{

			if (!empty($this->Section['section_password'])
				and !$PowerBB->_CONF['group_info']['admincp_allow']
				and !$PowerBB->functions->ModeratorCheck($this->Section['moderators']))
			{
     			if (empty($PowerBB->_COOKIE['pbb_sec'.$this->Section['id'].'_pass'])
     			or $PowerBB->_COOKIE['pbb_sec'.$this->Section['id'].'_pass'] != $this->Section['section_password'])
        		{
      				$PowerBB->template->display('forum_password');
      				$PowerBB->functions->stop();
     			}
     			else
     			{

     				$PassArr 	= 	($PowerBB->_GET['password'].$PowerBB->_COOKIE['pbb_sec'.$this->Section['id'].'_pass']);

     				if ($this->Section['section_password'] == $PassArr)
     				{
                     $PowerBB->_CONF['template']['password'] = $password;
     				}

     				$PowerBB->_CONF['template']['password'] = $password;
     			}
     		}
     	}

     	if ($PowerBB->_CONF['member_permission'])
     	{
     		$UpdateOnline 			= 	array();
			$UpdateOnline['field']	=	array();

			$UpdateOnline['field']['user_location'] 	= $PowerBB->_CONF['template']['_CONF']['lang']['Seen_the'] .	' <a href="index.php?page=forum&show=1&amp;id=' . $this->Section['id'] . '">' . $PowerBB->functions->CleanVariable($this->Section['title'],'sql') . '</a>';
			$UpdateOnline['field']['section_id'] 	    =  $this->Section['id'];
			$UpdateOnline['field']['subject_show'] 	    =  $this->Section['parent'];
			$UpdateOnline['where']						=	array('username',$PowerBB->_CONF['member_row']['username']);

			$update = $PowerBB->core->Update($UpdateOnline,'online');
     	}
       else
     	{
     		$UpdateOnline 			= 	array();
			$UpdateOnline['field']	=	array();

			$UpdateOnline['field']['user_location'] 	= $PowerBB->_CONF['template']['_CONF']['lang']['Seen_the'] .	' <a href="index.php?page=forum&show=1&amp;id=' . $this->Section['id'] . '">' . $PowerBB->functions->CleanVariable($this->Section['title'],'sql') . '</a>';
			$UpdateOnline['field']['section_id'] 	    =  $this->Section['id'];
			$UpdateOnline['field']['subject_show'] 	    =  $this->Section['parent'];
			$UpdateOnline['where']						=	array('user_ip',$PowerBB->_CONF['ip']);

			$update = $PowerBB->core->Update($UpdateOnline,'online');
     	}

        $PowerBB->template->assign('write_reply',$PowerBB->functions->section_group_permission($this->Section['id'],$PowerBB->_CONF['group_info']['id'],'write_subject',''));
        $PowerBB->template->assign('write_subject',$PowerBB->functions->section_group_permission($this->Section['id'],$PowerBB->_CONF['group_info']['id'],'write_subject',''));

	}


	function _PasswordCheck()
	{
		global $PowerBB;

           @ob_start();
		   setcookie("pbb_sec".$this->Section['id']."_pass","");

         if (empty($PowerBB->_COOKIE['pbb_sec'.$this->Section['id'].'_pass']))
         {

			if (empty($PowerBB->_POST['password']))
			{
				$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['PasswordCheck']);
			}
	     	elseif ($PowerBB->_POST['password'] != $this->Section['section_password'])
	     	{
		         $PowerBB->functions->ShowHeader();
	     		$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['PasswordIsnotTrue']);
	     	}
	     	else
	     	{
				@ob_start();
				setcookie("pbb_sec".$this->Section['id']."_pass",$PowerBB->_POST['password'], time()+3600);
				//@ob_end_flush();
	     	}

	       $PowerBB->functions->header_redirect($PowerBB->_SERVER['HTTP_REFERER']);
	     }
	}


	/**
	 * Know who is in section ?
	 */
	function _SectionOnline()
	{
		global $PowerBB;

		// Finally we get Who is in section
		$SecArr 						= 	array();
		$SecArr['proc'] 				= 	array();
		$SecArr['proc']['*'] 			= 	array('method'=>'clean','param'=>'html');
		$SecArr['where']				=	array();

		$SecArr['where'][0]				=	array();
		$SecArr['where'][0]['name'] 	= 	'section_id';
		$SecArr['where'][0]['oper'] 	= 	'=';
		$SecArr['where'][0]['value'] 	= 	$PowerBB->_GET['id'];

		$SecArr['order'] 				= 	array();
		$SecArr['order']['field'] 		= 	'user_id';
		$SecArr['order']['type'] 		= 	'DESC';

		$SecArr['proc'] 						= 	array();
		$SecArr['proc']['*'] 					= 	array('method'=>'clean','param'=>'html');
		$SecArr['proc']['logged'] 			= 	array('method'=>'time','store'=>'logged','type'=>$PowerBB->_CONF['info_row']['timesystem']);


		$x = 1;

		if (!$PowerBB->_CONF['info_row']['show_onlineguest'])
		{
			$SecArr['where'][$x]				=	array();
			$SecArr['where'][$x]['con']			=	'AND';
			$SecArr['where'][$x]['name']		=	'username';
			$SecArr['where'][$x]['oper']		=	'<>';
			$SecArr['where'][$x]['value']		=	'Guest';

			$x += 1;
		}

		// This member can't see hidden member
		if (!$PowerBB->_CONF['group_info']['show_hidden'])
		{
			$SecArr['where'][$x] 			= 	array();
			$SecArr['where'][$x]['con'] 	= 	'AND';
			$SecArr['where'][$x]['name'] 	= 	'hide_browse';
			$SecArr['where'][$x]['oper'] 	= 	'<>';
			$SecArr['where'][$x]['value'] 	= 	'1';
		}

		$PowerBB->_CONF['template']['while']['SectionVisitor'] = $PowerBB->core->GetList($SecArr,'online');


		$Forum_online_number = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(*) FROM " . $PowerBB->table['online'] . " WHERE section_id = " . $PowerBB->_GET['id'] . " AND username='Guest'"));
		$PowerBB->_CONF['template']['GuestNumber'] = $Forum_online_number;

    	$PowerBB->_CONF['template']['MemberNumber'] = sizeof($PowerBB->_CONF['template']['while']['SectionVisitor']);
		$online_number = $PowerBB->_CONF['template']['GuestNumber']+$PowerBB->_CONF['template']['MemberNumber'];

       $PowerBB->template->assign('online_number',$online_number);
	}

	function _GetModeratorsList()
	{
		global $PowerBB;

        $moderators = json_decode($this->Section['moderators'], true);
		if (is_array($moderators))
		{
			$PowerBB->template->assign('STOP_MODERATOR_TEMPLATE',false);
			$PowerBB->_CONF['template']['while']['ModeratorsList'] = $moderators;
		}
		else
		{
			$PowerBB->template->assign('STOP_MODERATOR_TEMPLATE',true);
		}
	}

	/**
	 * Get announcement list
	 */
	function _GetAnnouncementList()
	{
		global $PowerBB;

		$AnnArr 					= 	array();

		$AnnArr['proc'] 			= 	array();
		$AnnArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');
		$AnnArr['proc']['date'] 	= 	array('method'=>'date','type'=>$PowerBB->_CONF['info_row']['timesystem']);

		$AnnArr['order']			=	array();
		$AnnArr['order']['field']	=	'id';
		$AnnArr['order']['type']	=	'DESC';


		$PowerBB->_CONF['template']['while']['AnnouncementList'] = $PowerBB->core->GetList($AnnArr,'announcement');

		if ($PowerBB->_CONF['template']['while']['AnnouncementList'] != false)
		{
			$PowerBB->template->assign('STOP_ANNOUNCEMENT_TEMPLATE',false);
		}
		else
		{
			$PowerBB->template->assign('STOP_ANNOUNCEMENT_TEMPLATE',true);
		}
	}
   // Is files forums Cache  = true
	function _GetSubSection()
	{
		global $PowerBB;

		if (isset($this->Section['id'])) {
		$PowerBB->functions->_GetSections_cache($this->Section['id']);
		$PowerBB->template->assign('Section_Title',$this->Section['title']);
		$PowerBB->template->assign('Section_Id',$this->Section['id']);
		}

	}


	/**
	 * Get sections list.
	 * // Is files forums Cache  = false
	 */
	function _GetSections_direct()
	{
		global $PowerBB;
		if (isset($this->Section['id'])) {
		    // الدالة الآن سترجع true فقط إذا وجد أقسام فرعية فعلياً
		    $has_sub = $PowerBB->functions->_GetSections_direct($this->Section['id']);

		    if ($has_sub) {
		        $PowerBB->template->assign('SHOW_SUB_SECTIONS', true);
		    } else {
		        $PowerBB->template->assign('SHOW_SUB_SECTIONS', false);
		    }
		}

	}


	function forum_run_Hooks()
	{
	    global $PowerBB;

       eval($PowerBB->functions->get_fetch_hooks('forumdHooks'));
    }


function _GetSubjectList()
{
    global $PowerBB;

      $PowerBB->_GET['orderby'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['orderby'],'intval');


		if (isset($PowerBB->_GET['sort'])
		and $PowerBB->_GET['orderby'] == 1)
		{
			if ($PowerBB->_GET['sort'] != 'asc'
            and $PowerBB->_GET['sort'] != 'id'
			and $PowerBB->_GET['sort'] != 'reply_number'
			and $PowerBB->_GET['sort'] != 'visitor'
			and $PowerBB->_GET['sort'] != 'rating'
			and $PowerBB->_GET['sort'] != 'writer'
			and $PowerBB->_GET['sort'] != 'write_time')
			{
			 $PowerBB->_GET['sort'] = 'write_time';
			}
		}

		$PowerBB->_GET['count'] = (!isset($PowerBB->_GET['count'])) ? 0 : $PowerBB->_GET['count'];
		$PowerBB->_GET['count'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['count'],'intval');
         eval($PowerBB->functions->get_fetch_hooks('forum_subjectlist_Hooks'));

		//if subjects numprs in Section = 0 get the masseg
       $PowerBB->template->assign('NO_SUBJECTS',$this->Section['subject_num']);

       if ($this->Section['password'] !='')
        {
     	  $password = '&amp;password=' . $PowerBB->_GET['password'];
        }
         // assign sort
		if ($PowerBB->_GET['sort'] == 'reply_number')
		{
		$PowerBB->template->assign('sort_reply_number',1);
		}
		elseif ($PowerBB->_GET['sort'] == 'visitor')
		{
		$PowerBB->template->assign('sort_visitor',1);
		}
		elseif ($PowerBB->_GET['sort'] == 'rating')
		{
		$PowerBB->template->assign('sort_rating',1);
		}
		elseif ($PowerBB->_GET['sort'] == 'writer')
		{
		$PowerBB->template->assign('sort_writer',1);
		}
		elseif ($PowerBB->_GET['sort'] == 'asc')
		{
		$PowerBB->template->assign('sort_asc',1);
		}
		elseif ($PowerBB->_GET['sort'] == 'write_time')
		{
		$PowerBB->template->assign('sort_write_time',1);
		}
		else
		{
		 $PowerBB->template->assign('sort_write_time',1);
		}

	    /**
		 * Ok , are you ready to get subjects list ? :)
		 */
        $SubjectArr = array();
		$SubjectArr['select'] = 's.*';
		$SubjectArr['from'] = $PowerBB->table['subject'] . ' AS s';
		$SubjectArr['join'] = array();
		$SubjectArr['where'] = array();

		$SubjectArr['where'][0] = array();
		$SubjectArr['where'][0]['name'] = 's.section';
		$SubjectArr['where'][0]['oper'] = '=';
		$SubjectArr['where'][0]['value'] = (int)$this->Section['id'];

		$SubjectArr['where'][1] = array();
		$SubjectArr['where'][1]['con'] = 'AND';
		$SubjectArr['where'][1]['name'] = 's.stick';
		$SubjectArr['where'][1]['oper'] = '<>';
		$SubjectArr['where'][1]['value'] = 1;

		$SubjectArr['where'][2] = array();
		$SubjectArr['where'][2]['con'] = 'AND';
		$SubjectArr['where'][2]['name'] = 's.review_subject';
		$SubjectArr['where'][2]['oper'] = '<>';
		$SubjectArr['where'][2]['value'] = 1;

		if (!$PowerBB->functions->ModeratorCheck($this->Section['id']))
		{
			$SubjectArr['where'][3] = array();
			$SubjectArr['where'][3]['con'] = 'AND';
			$SubjectArr['where'][3]['name'] = 's.delete_topic';
			$SubjectArr['where'][3]['oper'] = '<>';
			$SubjectArr['where'][3]['value'] = 1;
		}

		if ($this->Section['hide_subject'] and !$PowerBB->functions->ModeratorCheck($this->Section['moderators']))
		{
			$SubjectArr['where'][4] = array();
			$SubjectArr['where'][4]['con'] = 'AND';
			$SubjectArr['where'][4]['name'] = 's.writer';
			$SubjectArr['where'][4]['oper'] = '=';
			$SubjectArr['where'][4]['value'] = $PowerBB->_CONF['member_row']['username'];
		}

		if($PowerBB->_GET['orderby'] == '1')
		{
			if ($PowerBB->_GET['sort'] == 'reply_number') {
				$order = 's.reply_number';
				$type  = 'DESC';
			} elseif ($PowerBB->_GET['sort'] == 'visitor') {
				$order = 's.visitor';
				$type  = 'DESC';
			} elseif ($PowerBB->_GET['sort'] == 'rating') {
				$order = 's.rating';
				$type  = 'DESC';
			} elseif ($PowerBB->_GET['sort'] == 'writer') {
				$order = 's.writer';
				$type  = 'DESC';
			} elseif ($PowerBB->_GET['sort'] == 'asc' || $PowerBB->_GET['sort'] == 'id') {
				$order = 's.id';
				$type  = 'ASC';
				$PowerBB->_GET['sort'] = 'id';
			} else {
				$order = 's.write_time';
				$type  = 'DESC';
			}

			$location = "index.php?page=forum&amp;show=1&amp;orderby=1&amp;id=".$PowerBB->_GET['id']."&amp;sort=".$PowerBB->_GET['sort'];
			$location = preg_replace('/&count='.$PowerBB->_GET['count'].'/is', "", $location);

			$SubjectArr['order'] = array();
			$SubjectArr['order']['field'] = $order;
			$SubjectArr['order']['type'] = $type;
		}
		else
		{
			$SubjectArr['order'] = array();
			$SubjectArr['order']['field'] = 's.write_time';
			$SubjectArr['order']['type'] = 'DESC';
			$location = 'index.php?page=forum&amp;show=1&amp;id=' . (int)$this->Section['id'] . $password;
		}

		if ($this->Section['hide_subject'])
		{
			if ($PowerBB->functions->ModeratorCheck($this->Section['moderators']))
			{
				$subject_nums = $this->Section['subject_num'];
			}
			else
			{
				$username_clean = $PowerBB->DB->sql_escape($PowerBB->_CONF['member_row']['username']);
				$section_id_clean = (int)$this->Section['id'];
				$Forum_user_subject_number = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(*) FROM " . $PowerBB->table['subject'] . " WHERE section = $section_id_clean AND writer = '$username_clean'"));
				$subject_nums = $Forum_user_subject_number;
			}
		}
		else
		{
			if ($PowerBB->functions->ModeratorCheck($this->Section['moderators']))
			{
				$subject_nums = $this->Section['subject_num'];
			}
			else
			{
				$subject_nums = $this->Section['subjects_review_num'];
			}
		}

		$SubjectArr['proc'] = array();
		$SubjectArr['proc']['*'] = array('method'=>'clean','param'=>'html');
		$SubjectArr['proc']['native_write_time'] = array('method'=>'date','store'=>'write_date','type'=>$PowerBB->_CONF['info_row']['timesystem']);
		$SubjectArr['proc']['write_time'] = array('method'=>'date','store'=>'reply_date','type'=>$PowerBB->_CONF['info_row']['timesystem']);

		$SubjectArr['pager'] = array();
		$SubjectArr['pager']['total'] = $subject_nums;
		$SubjectArr['pager']['perpage'] = $PowerBB->_CONF['info_row']['subject_perpage'];
		$SubjectArr['pager']['count'] = $PowerBB->_GET['count'];
		$SubjectArr['pager']['location'] = $location;
		$SubjectArr['pager']['var'] = 'count';

		$PowerBB->_CONF['template']['while']['subject_list'] = $PowerBB->subject->GetSubjectListAdvanced($SubjectArr);


        $StickSubjectArr = array();
		$StickSubjectArr['select'] = 's.*';
		$StickSubjectArr['from'] = $PowerBB->table['subject'] . ' AS s';
		$StickSubjectArr['join'] = array();

		$StickSubjectArr['where'] = array();
		$StickSubjectArr['where'][0] = array();
		$StickSubjectArr['where'][0]['name'] = 's.section';
		$StickSubjectArr['where'][0]['oper'] = '=';
		$StickSubjectArr['where'][0]['value'] = (int)$this->Section['id'];

		$StickSubjectArr['where'][1] = array();
		$StickSubjectArr['where'][1]['con'] = 'AND';
		$StickSubjectArr['where'][1]['name'] = 's.stick';
		$StickSubjectArr['where'][1]['oper'] = '=';
		$StickSubjectArr['where'][1]['value'] = 1;

		if (!$PowerBB->functions->ModeratorCheck($this->Section['id']))
		{
			$StickSubjectArr['where'][2] = array();
			$StickSubjectArr['where'][2]['con'] = 'AND';
			$StickSubjectArr['where'][2]['name'] = 's.delete_topic';
			$StickSubjectArr['where'][2]['oper'] = '<>';
			$StickSubjectArr['where'][2]['value'] = 1;
		}

		if ($this->Section['hide_subject'] and !$PowerBB->functions->ModeratorCheck($this->Section['id']))
		{
			$StickSubjectArr['where'][3] = array();
			$StickSubjectArr['where'][3]['con'] = 'AND';
			$StickSubjectArr['where'][3]['name'] = 's.writer';
			$StickSubjectArr['where'][3]['oper'] = '=';
			$StickSubjectArr['where'][3]['value'] = $PowerBB->_CONF['member_row']['username'];
		}

		$StickSubjectArr['order'] = array();
		$StickSubjectArr['order']['field'] = 's.write_time';
		$StickSubjectArr['order']['type'] = 'DESC';

		$StickSubjectArr['proc'] = array();
		$StickSubjectArr['proc']['*'] = array('method'=>'clean','param'=>'html');
		$StickSubjectArr['proc']['native_write_time'] = array('method'=>'date','store'=>'write_date','type'=>$PowerBB->_CONF['info_row']['timesystem']);
		$StickSubjectArr['proc']['write_time'] = array('method'=>'date','store'=>'reply_date','type'=>$PowerBB->_CONF['info_row']['timesystem']);

		$PowerBB->_CONF['template']['while']['stick_subject_list'] = $PowerBB->subject->GetSubjectListAdvanced($StickSubjectArr);

		if (sizeof($PowerBB->_CONF['template']['while']['stick_subject_list']) <= 0)
		{
			$PowerBB->template->assign('NO_STICK_SUBJECTS',true);
		}
		else
		{
			$PowerBB->template->assign('NO_STICK_SUBJECTS',false);
		}

                // Get the list of subjects that need review
			$username = $PowerBB->DB->sql_escape($PowerBB->_CONF['member_row']['username']);
			$section_id_clean = (int)$this->Section['id'];

			$res_review = $PowerBB->DB->sql_query("SELECT COUNT(*) FROM " . $PowerBB->table['subject'] . " WHERE section = $section_id_clean AND writer = '$username' AND review_subject = 1");
			$SUBJECTS_review_subject_mem = $PowerBB->DB->sql_fetch_row($res_review);

			if ($PowerBB->functions->ModeratorCheck($this->Section['id']))
			{
			    $ReviewSubjectArr = array();
			    $ReviewSubjectArr['select'] = 's.*';
			    $ReviewSubjectArr['from'] = $PowerBB->table['subject'] . ' AS s';
			    $ReviewSubjectArr['join'] = array();
			    $ReviewSubjectArr['where'] = array();

			    $ReviewSubjectArr['where'][0] = array();
			    $ReviewSubjectArr['where'][0]['name'] = 's.section';
			    $ReviewSubjectArr['where'][0]['oper'] = '=';
			    $ReviewSubjectArr['where'][0]['value'] = $section_id_clean;

			    $ReviewSubjectArr['where'][1] = array();
			    $ReviewSubjectArr['where'][1]['con'] = 'AND';
			    $ReviewSubjectArr['where'][1]['name'] = 's.review_subject';
			    $ReviewSubjectArr['where'][1]['oper'] = '=';
			    $ReviewSubjectArr['where'][1]['value'] = 1;

			    if (!$PowerBB->functions->ModeratorCheck($this->Section['id']))
			    {
			        $ReviewSubjectArr['where'][2] = array();
			        $ReviewSubjectArr['where'][2]['con'] = 'AND';
			        $ReviewSubjectArr['where'][2]['name'] = 's.delete_topic';
			        $ReviewSubjectArr['where'][2]['oper'] = '<>';
			        $ReviewSubjectArr['where'][2]['value'] = 1;
			    }

			    if ($this->Section['hide_subject'] and !$PowerBB->functions->ModeratorCheck($this->Section['id']))
			    {
			        $ReviewSubjectArr['where'][3] = array();
			        $ReviewSubjectArr['where'][3]['con'] = 'AND';
			        $ReviewSubjectArr['where'][3]['name'] = 's.writer';
			        $ReviewSubjectArr['where'][3]['oper'] = '=';
			        $ReviewSubjectArr['where'][3]['value'] = $PowerBB->_CONF['member_row']['username'];
			    }

			    $ReviewSubjectArr['order'] = array();
			    $ReviewSubjectArr['order']['field'] = 's.write_time';
			    $ReviewSubjectArr['order']['type'] = 'DESC';

			    $ReviewSubjectArr['proc'] = array();
			    $ReviewSubjectArr['proc']['*'] = array('method'=>'clean','param'=>'html');
			    $ReviewSubjectArr['proc']['native_write_time'] = array('method'=>'date','store'=>'write_date','type'=>$PowerBB->_CONF['info_row']['timesystem']);
			    $ReviewSubjectArr['proc']['write_time'] = array('method'=>'date','store'=>'reply_date','type'=>$PowerBB->_CONF['info_row']['timesystem']);

			    $PowerBB->_CONF['template']['while']['review_subject_list'] = $PowerBB->subject->GetSubjectListAdvanced($ReviewSubjectArr);

			    $PowerBB->template->assign('NO_REVIEW_SUBJECTS', (sizeof($PowerBB->_CONF['template']['while']['review_subject_list']) <= 0));
			}
			elseif ($SUBJECTS_review_subject_mem > 0)
			{
			    $ReviewSubjectArr = array();
			    $ReviewSubjectArr['select'] = 's.*';
			    $ReviewSubjectArr['from'] = $PowerBB->table['subject'] . ' AS s';
			    $ReviewSubjectArr['join'] = array();
			    $ReviewSubjectArr['where'] = array();

			    $ReviewSubjectArr['where'][0] = array();
			    $ReviewSubjectArr['where'][0]['name'] = 's.section';
			    $ReviewSubjectArr['where'][0]['oper'] = '=';
			    $ReviewSubjectArr['where'][0]['value'] = $section_id_clean;

			    $ReviewSubjectArr['where'][1] = array();
			    $ReviewSubjectArr['where'][1]['con'] = 'AND';
			    $ReviewSubjectArr['where'][1]['name'] = 's.review_subject';
			    $ReviewSubjectArr['where'][1]['oper'] = '=';
			    $ReviewSubjectArr['where'][1]['value'] = 1;

			    $ReviewSubjectArr['where'][2] = array();
			    $ReviewSubjectArr['where'][2]['con'] = 'AND';
			    $ReviewSubjectArr['where'][2]['name'] = 's.writer';
			    $ReviewSubjectArr['where'][2]['oper'] = '=';
			    $ReviewSubjectArr['where'][2]['value'] = $PowerBB->_CONF['member_row']['username'];

			    $ReviewSubjectArr['order'] = array();
			    $ReviewSubjectArr['order']['field'] = 's.write_time';
			    $ReviewSubjectArr['order']['type'] = 'DESC';

			    $ReviewSubjectArr['proc'] = array();
			    $ReviewSubjectArr['proc']['*'] = array('method'=>'clean','param'=>'html');
			    $ReviewSubjectArr['proc']['native_write_time'] = array('method'=>'date','store'=>'write_date','type'=>$PowerBB->_CONF['info_row']['timesystem']);
			    $ReviewSubjectArr['proc']['write_time'] = array('method'=>'date','store'=>'reply_date','type'=>$PowerBB->_CONF['info_row']['timesystem']);

			    $PowerBB->_CONF['template']['while']['review_subject_list'] = $PowerBB->subject->GetSubjectListAdvanced($ReviewSubjectArr);

			    $PowerBB->template->assign('NO_REVIEW_SUBJECTS', (sizeof($PowerBB->_CONF['template']['while']['review_subject_list']) <= 0));
			}
			else
			{
			    $PowerBB->template->assign('NO_REVIEW_SUBJECTS', true);
			}

		$all_active_lists = array();
		if (!empty($PowerBB->_CONF['template']['while']['subject_list']))
		    $all_active_lists[] = &$PowerBB->_CONF['template']['while']['subject_list'];
		if (!empty($PowerBB->_CONF['template']['while']['stick_subject_list']))
		    $all_active_lists[] = &$PowerBB->_CONF['template']['while']['stick_subject_list'];
		if (!empty($PowerBB->_CONF['template']['while']['review_subject_list']))
		    $all_active_lists[] = &$PowerBB->_CONF['template']['while']['review_subject_list'];

		if (!empty($all_active_lists)) {
		    $usernames_to_fetch = array();

		    foreach ($all_active_lists as $list) {
		        foreach ($list as $row) {
		            if (!empty($row['writer'])) $usernames_to_fetch[] = $row['writer'];
		            if (!empty($row['last_replier'])) $usernames_to_fetch[] = $row['last_replier'];
		        }
		    }

		    if (!empty($usernames_to_fetch)) {
		        $usernames_to_fetch = array_unique($usernames_to_fetch);
		        $escaped_names = array();
		        foreach($usernames_to_fetch as $uname) {
		            if (trim($uname) != '') {
		                $escaped_names[] = "'" . $PowerBB->DB->sql_escape($uname) . "'";
		            }
		        }

		        if (!empty($escaped_names)) {
		            // بفضل الفهرس الجديد على username، هذا الاستعلام أصبح سريعاً جداً
		            $user_sql = "SELECT id, username, username_style_cache FROM " . $PowerBB->table['member'] . " WHERE username IN (" . implode(',', $escaped_names) . ")";
		            $user_res = $PowerBB->DB->sql_query($user_sql);

		            $users_info_map = array();
		            while ($u = $PowerBB->DB->sql_fetch_assoc($user_res)) {
		                $users_info_map[$u['username']] = $u;
		            }

		            foreach ($all_active_lists as &$list) {
		                foreach ($list as $key => $val) {
		                    $w_name = $val['writer'];
		                    $lr_name = $val['last_replier'];

		                    // ربط بيانات الكاتب - مع حماية ضد القيم المفقودة
		                    $list[$key]['writer_id'] = isset($users_info_map[$w_name]) ? $users_info_map[$w_name]['id'] : 0;
		                    $list[$key]['username_style_cache'] = isset($users_info_map[$w_name]) ? $users_info_map[$w_name]['username_style_cache'] : '';

		                    // ربط بيانات آخر رد - مع حماية ضد القيم المفقودة
		                    $list[$key]['last_replier_id'] = isset($users_info_map[$lr_name]) ? $users_info_map[$lr_name]['id'] : 0;
		                    $list[$key]['last_replier_style'] = isset($users_info_map[$lr_name]) ? $users_info_map[$lr_name]['username_style_cache'] : '';
		                }
		            }
		        }
		    }
		}

		 if ($subject_nums > $PowerBB->_CONF['info_row']['subject_perpage'])
		 {
		   $PowerBB->template->assign('pager',$PowerBB->pager->show());
         }

		$PowerBB->template->assign('section_id',$this->Section['id']);

	}

    /**
	 * Get the results of search one section
	 */

	function _SearchSection()
	{
		global $PowerBB;

         if (empty($PowerBB->_GET['keyword']))
	      {
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['please_enter_a_single_search']);
	      }

		//////////

		$keyword 	= 	(isset($PowerBB->_GET['keyword'])) ? $PowerBB->_GET['keyword'] : $PowerBB->_POST['keyword'];
		$section 	= 	(isset($PowerBB->_GET['section'])) ? $PowerBB->_GET['section'] : $PowerBB->_POST['section'];
     	 $section 	= 	(isset($PowerBB->_GET['section'])) ? $PowerBB->_GET['section'] : $PowerBB->_POST['section'];

       if ($section != is_numeric($section))
		{
           $PowerBB->functions->ShowHeader();
		   $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['no_search_results']);
		 }

          	$subject_nm = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(*) FROM " . $PowerBB->table['subject'] . " WHERE text LIKE '%$keyword%' AND section = $section "));


            $sec = ' AND section =  ';

	          if ($subject_nm > '0')
	          {
		       $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Search_successful']);
		       $PowerBB->functions->redirect('index.php?page=forum&amp;option=1&amp;section=' . $section . '&amp;keyword=' . $keyword);
	           }
		      else
		       {
		       $stop = ($PowerBB->_CONF['info_row']['ajax_search'] and !$PowerBB->_POST['ajax']) ? false : true;
			   $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['no_search_results'],$stop);
		       }

    }
	function _StartSearch()
	{
		global $PowerBB;

		if (!$PowerBB->_CONF['group_info']['search_allow'])
		{
          if (!$PowerBB->_CONF['member_permission'])
              {
              $PowerBB->template->display('login');
              $PowerBB->functions->error_stop();
			}
	        else
	        {
	        $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['no_online']);
	        }
	     }

         if (empty($PowerBB->_GET['keyword']))
	      {
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['please_enter_a_single_search']);
	      }



           $PowerBB->_GET['count'] = (!isset($PowerBB->_GET['count'])) ? 0 : $PowerBB->_GET['count'];
           $PowerBB->_GET['count'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['count'],'intval');
		//////////

		$keyword 	= 	(isset($PowerBB->_GET['keyword'])) ? $PowerBB->_GET['keyword'] : $PowerBB->_POST['keyword'];
		$section 	= 	(isset($PowerBB->_GET['section'])) ? $PowerBB->_GET['section'] : $PowerBB->_POST['section'];
		$sort_order	= 	(isset($PowerBB->_GET['sort_order'])) ? $PowerBB->_GET['sort_order'] : $PowerBB->_POST['sort_order'];
		$PowerBB->_GET['section'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['section'],'intval');


		$sort_order	= 	$PowerBB->functions->CleanVariable($sort_order,'trim');
		$sort_order	= 	$PowerBB->functions->CleanVariable($sort_order,'sql');
		$sort_order	= 	$PowerBB->functions->CleanVariable($sort_order,'html');

      	$section	= 	$PowerBB->functions->CleanVariable($section,'intval');


       if ($section != is_numeric($section))
		{
           $PowerBB->functions->ShowHeader();
		   $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['no_search_results']);
		 }

			if($PowerBB->_GET['orderby'])
			{
			$location = $PowerBB->_SERVER['REQUEST_URI'];
			$location = preg_replace('/&submit=(.*?)&count='.$PowerBB->_GET['count'].'/is', "", $location);
			$location = preg_replace('/&count='.$PowerBB->_GET['count'].'/is', "", $location);
		    }
		    else
			{
			$location = 'index.php?page=forum&amp;option=1&amp;section=' . $section . '&amp;keyword=' . $keyword . '&amp;password=' . $PowerBB->_GET['password'];
		    }

		// Get section information and set it in $this->Section
		$SectiondArr 			= 	array();
		$SectiondArr['where'] 	= 	array('id',$section);

		$tSection = $PowerBB->core->GetInfo($SectiondArr,'section');

			if ($PowerBB->functions->ModeratorCheck($tSection['moderators']))
			{
          	 $subject_nm = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(*) FROM " . $PowerBB->table['subject'] . " WHERE text LIKE '%$keyword%' AND section = $section "));
            }
             else
			{
          	 $subject_nm = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(*) FROM " . $PowerBB->table['subject'] . " WHERE text LIKE '%$keyword%' AND section = $section AND sec_subject = 0 "));
            }

            $PowerBB->template->assign('nm',$subject_nm);

            $sec = ' AND section =  ';

            $SubjectSearchArr = array();

			$SubjectSearchArr['where'] 				= 	array();

			$SubjectSearchArr['where'][0] 			= 	array();
			$SubjectSearchArr['where'][0]['name'] 	= 	'text LIKE ';
			$SubjectSearchArr['where'][0]['oper']		=  "'".'%' .$keyword .'%'."'  $sec";
			$SubjectSearchArr['where'][0]['value']    =  $section;
           	if ($PowerBB->functions->ModeratorCheck($tSection['moderators']) == false)
			{
			$SubjectSearchArr['where'][1] 			= 	array();
			$SubjectSearchArr['where'][1]['con'] 		= 	'AND';
			$SubjectSearchArr['where'][1]['name'] 	= 	'sec_subject';
			$SubjectSearchArr['where'][1]['oper'] 	= 	'=';
			$SubjectSearchArr['where'][1]['value'] 	= 	'0';
           }
			$SubjectSearchArr['order'] 			= 	array();
			$SubjectSearchArr['order']['field'] 	= 	'id';
			$SubjectSearchArr['order']['type'] 	= 	$sort_order;

			$SubjectSearchArr['proc'] 						= 	array();
			// Ok Mr.XSS go to hell !
			$SubjectSearchArr['proc']['*'] 					= 	array('method'=>'clean','param'=>'html');
			$SubjectSearchArr['proc']['native_write_time'] 	= 	array('method'=>'date','store'=>'write_date','type'=>$PowerBB->_CONF['info_row']['timesystem']);
			$SubjectSearchArr['proc']['write_time'] 			= 	array('method'=>'date','store'=>'reply_date','type'=>$PowerBB->_CONF['info_row']['timesystem']);
            // Pager setup
			$SubjectSearchArr['pager'] 				= 	array();
			$SubjectSearchArr['pager']['total']		= 	$subject_nm;
			$SubjectSearchArr['pager']['perpage'] 	= 	$PowerBB->_CONF['info_row']['perpage'];
			$SubjectSearchArr['pager']['count'] 	= 	$PowerBB->_GET['count'];
			$SubjectSearchArr['pager']['location'] 	= 	$location;
			$SubjectSearchArr['pager']['var'] 		= 	'count';

			$PowerBB->_CONF['template']['while']['SubjectList'] = $PowerBB->core->GetList($SubjectSearchArr,'subject');

       if (!$PowerBB->_CONF['template']['while']['SubjectList'])
	   {
		   $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['No_search_results'],false);
       }

		//////////

		if ($subject_nm > $PowerBB->_CONF['info_row']['perpage'])
		{
		$PowerBB->template->assign('pager',$PowerBB->pager->show());
		}

		$PowerBB->template->assign('keyword',$keyword);
		$PowerBB->template->display('search_results_all');

	}


	function _CallTemplate()
	{
		global $PowerBB;

        $PowerBB->template->assign('section_id',$this->Section['id']);

         // Moderator And admin Check for View the Icons Editing and Deletion
		if ($PowerBB->functions->ModeratorCheck($this->Section['moderators']))
		{
			$PowerBB->template->assign('mod_toolbar',0);
			$PowerBB->template->assign('colspan',8);
		}
		else
		{
			$PowerBB->template->assign('mod_toolbar',1);
			$PowerBB->template->assign('colspan',7);
		}

        $SecSubject1 			= 	array();
		$SecSubject1['where'] 	= 	array('id',$this->Section['parent']);

		$Section_rwo1 = $PowerBB->core->GetInfo($SecSubject1,'section');

        $SecSubject2 			= 	array();
		$SecSubject2['where'] 	= 	array('id',$Section_rwo1['parent']);

		$Section_rwo2 = $PowerBB->core->GetInfo($SecSubject2,'section');

	 $PowerBB->template->assign('sec_address_id',$Section_rwo1['id']);
      $PowerBB->template->assign('section_parent',$Section_rwo1['parent']);
      $PowerBB->template->assign('sec_address_title',$Section_rwo1['title']);
      $PowerBB->template->assign('sec_main_title',$Section_rwo2['title']);
	 $PowerBB->template->assign('sec_main_id',$Section_rwo2['id']);
         // nav bar get all child sections of parent
		 $ParentList = $this->get_parent($this->Section['id']);
		 $nmy = sizeof($ParentList);
		 $nmy_neg = $nmy-1;
		 $PowerBB->template->assign('child_num',$nmy);
		 $PowerBB->template->assign('neg_num',$nmy_neg);
         $PowerBB->_CONF['template']['while']['ParentList'] = $ParentList;

         eval($PowerBB->functions->get_fetch_hooks('start_forum_template_hooks'));
		$PowerBB->template->display('forum');

	}

    //get all child sections of parent
	function get_parent($catid = 0)
	{
	    global $PowerBB;
	    $parent = array();
	    $query_child =$PowerBB->DB->sql_query("SELECT id,parent,title  FROM " . $PowerBB->table['section'] . " WHERE id = $catid ORDER BY parent DESC");
		$child = $PowerBB->DB->sql_fetch_array($query_child);

	    $parent[] = $child;

	       if ($child['parent'] == 0) {
	           return $parent;
	       } else {
	           $item = $this->get_parent($child['parent']);
	         return array_merge($item,$parent);
	       }
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