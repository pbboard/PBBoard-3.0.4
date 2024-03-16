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


		/** Browse the forum **/
		if ($PowerBB->_GET['show'])
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
		elseif ($PowerBB->_GET['start'])
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
        elseif($PowerBB->_GET['all_cache'] == '1')
	    {
         $this->_AllCacheStart();
		}
		else
		{
		header("Location: index.php");
		exit;
		}

		$PowerBB->functions->GetFooter();
	}

function _AllCacheStart()
	{
		global $PowerBB;

		$SecArr 					= 	array();
		$SecArr['get_from']			=	'db';
		$SecArr['proc'] 			= 	array();
		$SecArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');
		$SecArr['order']			=	array();
		$SecArr['order']['field']	=	'sort';
		$SecArr['order']['type']	=	'ASC';

		$SecArr['where']				=	array();
		$SecArr['where'][0]				=	array();
		$SecArr['where'][0]['name']		=	'parent';
		$SecArr['where'][0]['oper']		=	'<>';
		$SecArr['where'][0]['value']	=	'0';

		$SecList = $PowerBB->core->GetList($SecArr,'section');

		$x = 0;
		$y = sizeof($SecList);
		$s = array();

		while ($x < $y)
		{

	     $UpdateSectionCache = $PowerBB->functions->UpdateSectionCache($SecList[$x]['id']);

			$x += 1;

			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['updated']  .  $SecList[$x]['title']  .  $PowerBB->_CONF['template']['_CONF']['lang']['Successfully']);
		}


       	  $PowerBB->functions->Update_Cache_groups();

          $PowerBB->functions->redirect('index.php');

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

		if($PowerBB->_CONF['forums_parent_direct'])
		{
		 $this->_GetSections_direct();
		}
		else
		{
		 $this->_GetSubSection();
		}
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
		if ($PowerBB->functions->section_group_permission($this->Section['id'],$PowerBB->_CONF['group_info']['id'],'view_section',$this->Section['sectiongroup_cache']) != 1)
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['can_not_view_section']);
		}

		// This is main section , so we can't get subjects list from it
		if ($this->Section['main_section'])
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['thes_main_section']);
		}

       if ($PowerBB->functions->section_group_permission($this->Section['id'],$PowerBB->_CONF['group_info']['id'],'view_subject') == 0 and $this->Section['parent'])
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
		    $update_visitor_Section = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['section'] . " SET linkvisitor= '$visitor' WHERE id='$Sectionid'");

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

        $PowerBB->template->assign('write_reply',$PowerBB->functions->section_group_permission($this->Section['id'],$PowerBB->_CONF['group_info']['id'],'write_subject'));
        $PowerBB->template->assign('write_subject',$PowerBB->functions->section_group_permission($this->Section['id'],$PowerBB->_CONF['group_info']['id'],'write_subject'));

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


		$Forum_online_number = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(1),id FROM " . $PowerBB->table['online'] . " WHERE section_id='" . $PowerBB->_GET['id'] . "' AND username='Guest' LIMIT 1"));
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

	function _GetSubSection()
	{
		global $PowerBB;


			if($PowerBB->_CONF['files_forums_Cache'])
			{
			 @include("cache/forums_cache/forums_cache_".$this->Section['id'].".php");
			}
			else
			{
			 $forums_cache = $PowerBB->functions->get_forum_cache($this->Section['id'],$this->Section['forums_cache']);
			}
			if (!empty($forums_cache))
			{
                $forums = $PowerBB->functions->decode_forum_cache($forums_cache);

					foreach ($forums as $forum)
					{

						if ($PowerBB->_CONF['group_info']['vice']
						or $PowerBB->_CONF['member_row']['usergroup'] == '1')
						{
			               if ($forum['subjects_review_num']>0)
							{
							$forum['num_subjects_awaiting_approval'] =	$PowerBB->functions->with_comma($forum['subjects_review_num']);
							}
                           if ($forum['replys_review_num']>0)
							{
							$forum['num_replys_awaiting_approval'] =	$PowerBB->functions->with_comma($forum['replys_review_num']);
							}
						}

						//////////////////////////
						if ($PowerBB->functions->section_group_permission($forum['id'],$PowerBB->_CONF['group_info']['id'],'view_section',$forum['sectiongroup_cache']))
						{
                         if (!$PowerBB->functions->section_group_permission($forum['id'],$PowerBB->_CONF['group_info']['id'],'view_subject',$forum['sectiongroup_cache']))
						 {
						   $forum['hide_subject']	= '1';
                         }

                           if (!empty($forum['last_date']))
                           {
							$forum_last_time1 = $forum['last_date'];
							$forum['last_subject'] = $PowerBB->Powerparse->censor_words($forum['last_subject']);
							$forum['last_subject_title'] =  $forum['last_subject'];
							$forum['last_subject'] =  $PowerBB->Powerparse->_wordwrap($forum['last_subject'],'35');
							$forum['last_post_date'] = $PowerBB->sys_functions->_date($forum['last_time']);
							$forum['l_date'] = $forum_last_time1;
							$forum['last_date'] = $PowerBB->sys_functions->time_ago($forum_last_time1);
							$forum['last_time_ago'] = $PowerBB->sys_functions->time_ago($forum_last_time1);
							$forum['last_date_ago'] = $PowerBB->sys_functions->_time($forum_last_time1);
							$forum['last_subjectid'] = $forum['last_subjectid'];
							$forum['last_time'] = $forum['last_time'];
							$forum['last_reply'] = $forum['last_reply'];
							$forum['icon'] = $forum['icon'];
							$forum['review_subject'] = $forum['review_subject'];
							$forum['last_berpage_nm'] = $forum['last_berpage_nm'];
							$forum['last_writer']= $forum['last_writer'];
							$forum['username_style_cache'] = $forum['username_style_cache'];
							$forum['writer_photo']= $forum['writer_photo'];
							$forum['avater_path']= $forum['avater_path'];
							$forum['last_subject'] =  $forum['prefix_subject']." ".$PowerBB->functions->pbb_stripslashes($forum['last_subject']);
                            $forum['sec_section']= $forum['sec_section'];
                            $forum['last_writer_id']= $forum['last_writer_id'];
                           }
                            $forum['subject_num'] = $forum['subject_num'];
			                $forum['reply_num'] = $forum['reply_num'];

                            $kay =$cat['id'];
							$forum['collapse']= $PowerBB->_COOKIE["pbboard_collapse_forumid_$kay"];


									$forum['is_sub'] 	= 	0;
									$forum['sub']		=	'';
									$t_sub=0;
									if($PowerBB->_CONF['files_forums_Cache'])
									 {
                                     @include("cache/forums_cache/forums_cache_".$forum['id'].".php");
                                     }
                                     else
									 {
                                     $forums_cache = $PowerBB->functions->get_forum_cache($forum['id'],$forum['forums_cache']);
                                     }
                                   if (!empty($forums_cache))
		                           {
									   $subs = $PowerBB->functions->decode_forum_cache($forums_cache);
		                               foreach($subs as $sub)
										{
										   if ($forum['id'] == $sub['parent'])
		                                    {

										        if (!empty($sub['last_date']))
										         {
										           if ($sub['last_time'] > $forum['last_time'])
										           {
	                                             	$forum_last_time1 = $sub['last_date'];
													$forum['last_subject'] = $PowerBB->Powerparse->censor_words($sub['last_subject']);
                                                   	$forum['last_subject_title'] =  $forum['last_subject'];
													$forum['last_subject'] =  $PowerBB->Powerparse->_wordwrap($sub['last_subject'],'35');
													$forum['last_post_date'] = $PowerBB->sys_functions->_date($forum_last_time1);
				                                     $forum['l_date'] = $forum_last_time1;
													 $forum['last_date'] = $PowerBB->sys_functions->time_ago($forum_last_time1);
													$forum['last_time_ago'] = $PowerBB->sys_functions->time_ago($forum_last_time1);
													$forum['last_date_ago'] = $PowerBB->sys_functions->_time($forum_last_time1);
													$forum['last_subjectid'] = $sub['last_subjectid'];
													$forum['last_time'] = $sub['last_time'];
													$forum['last_reply'] = $sub['last_reply'];
													$forum['icon'] = $sub['icon'];
													$forum['review_subject'] = $sub['review_subject'];
													$forum['last_berpage_nm'] = $sub['last_berpage_nm'];
													$forum['last_writer']= $sub['last_writer'];
													$forum['username_style_cache'] = $sub['username_style_cache'];
													$forum['writer_photo']= $sub['writer_photo'];
													$forum['avater_path']= $sub['avater_path'];
								                    $forum['last_subject'] =  $sub['prefix_subject']." ".$PowerBB->functions->pbb_stripslashes($sub['last_subject']);
								                    $forum['sec_section']= $sub['sec_section'];
								                    $forum['last_writer_id']= $sub['last_writer_id'];
								                  }
                                               }
			                                        $forum['subject_num'] += $sub['subject_num'];
			                                        $forum['reply_num'] += $sub['reply_num'];
										            $forum['num_subjects_awaiting_approval'] += $sub['subjects_review_num'];
										            $forum['num_replys_awaiting_approval'] += $sub['replys_review_num'];

												  if ($PowerBB->functions->section_group_permission($sub['id'],$PowerBB->_CONF['group_info']['id'],'view_section',$sub['sectiongroup_cache']))
												   {
												        if ($sub['forum_title_color'] !='')
												         {
														    $forum_title_color = $sub['forum_title_color'];
														    $sub['title'] = "<span style=color:".$forum_title_color.">".$PowerBB->functions->pbb_stripslashes($sub['title'])."</span>";
														 }
														if ($sub['id'])
														{
														$forum['is_sub'] = 1;
														}
														if($t_sub== $PowerBB->_CONF['info_row']['sub_columns_number']){
														$t_sub=0;
														$forum['sub'] .='</ol><br /><ol class="home-sub-forums-columns-2">';
														}
														$forum_url = "index.php?page=forum&amp;show=1&amp;id=";
														$forum['sub'] .= '<li class="home-sub-forums">';
														$forum['sub'] .= '<a class="sub-forums-title" href="'.$PowerBB->functions->rewriterule($forum_url).$sub['id'].'">'.$sub['title'].'</a>';
														$forum['sub'] .= "</li>";
														$t_sub=$t_sub+1;

											        }

                                                   // subs forum ++
                                                   	if($PowerBB->_CONF['files_forums_Cache'])
													 {
													 @include("cache/forums_cache/forums_cache_".$sub['id'].".php");
													 }
													 else
													 {
													 $forums_cache = $PowerBB->functions->get_forum_cache($sub['id'],$sub['forums_cache']);
													 }
				                                   if (!empty($forums_cache))
						                           {
														$subsforum = $PowerBB->functions->decode_forum_cache($forums_cache);
						                               foreach($subsforum as $subforum)
														{
														    if ($sub['id'] == $subforum['parent'])
														    {

														        if (!empty($subforum['last_date']))
														         {
															           if ($subforum['last_time'] > $sub['last_time'] and $subforum['last_time'] > $forum['last_time'])
															           {
						                                             	$forum_last_time1 = $subforum['last_date'];
																		$forum['last_subject'] = $PowerBB->Powerparse->censor_words($subforum['last_subject']);
																		$forum['last_subject_title'] =  $forum['last_subject'];
																		$forum['last_subject'] =  $PowerBB->Powerparse->_wordwrap($subforum['last_subject'],'35');
																		$forum['last_post_date'] = $PowerBB->sys_functions->_date($forum_last_time1);
									                                    $forum['l_date'] = $forum_last_time1;
																		$forum['last_date'] = $PowerBB->sys_functions->time_ago($forum_last_time1);
																		$forum['last_time_ago'] = $PowerBB->sys_functions->time_ago($forum_last_time1);
																		$forum['last_date_ago'] = $PowerBB->sys_functions->_time($forum_last_time1);
																		$forum['last_subjectid'] = $subforum['last_subjectid'];
																		$forum['last_time'] = $subforum['last_time'];
																		$forum['last_reply'] = $subforum['last_reply'];
																		$forum['icon'] = $subforum['icon'];
																		$forum['review_subject'] = $subforum['review_subject'];
																		$forum['last_berpage_nm'] = $subforum['last_berpage_nm'];
																		$forum['last_writer']= $subforum['last_writer'];
																		$forum['username_style_cache'] = $subforum['username_style_cache'];
																		$forum['writer_photo']= $subforum['writer_photo'];
																		$forum['avater_path']= $subforum['avater_path'];
													                    $forum['last_subject'] =  $subforum['prefix_subject']." ".$PowerBB->functions->pbb_stripslashes($subforum['last_subject']);
													                    $forum['sec_section']= $subforum['sec_section'];
													                    $forum['last_writer_id']= $subforum['last_writer_id'];
	                                                                   }
				                                                 }

							                                        $forum['subject_num'] += $subforum['subject_num'];
							                                        $forum['reply_num'] += $subforum['reply_num'];
														            $forum['num_subjects_awaiting_approval'] += $subforum['subjects_review_num'];
														            $forum['num_replys_awaiting_approval'] += $subforum['replys_review_num'];

                                                            }

	                                                              // subs forum +++
			                                                   	if($PowerBB->_CONF['files_forums_Cache'])
																 {
																 @include("cache/forums_cache/forums_cache_".$subforum['id'].".php");
																 }
																 else
																 {
																 $forums_cache = $PowerBB->functions->get_forum_cache($subforum['id'],$subforum['forums_cache']);
																 }

							                                   if (!empty($forums_cache))
									                           {
																	$subs4forum = $PowerBB->functions->decode_forum_cache($forums_cache);
									                               foreach($subs4forum  as $sub4forum)
																	{
																	    if ($subforum['id'] == $sub4forum['parent'])
																	    {
																	        if (!empty($sub4forum['last_date']))
																	         {
																		           if ($sub4forum['last_time'] > $sub['last_time'] and $sub4forum['last_time'] > $subforum['last_time'] and $sub4forum['last_time'] > $forum['last_time'])
																		           {
									                                             	$forum_last_time1 = $sub4forum['last_date'];
																					$forum['last_subject'] = $PowerBB->Powerparse->censor_words($sub4forum['last_subject']);
																					$forum['last_subject_title'] =  $forum['last_subject'];
																					$forum['last_subject'] =  $PowerBB->Powerparse->_wordwrap($sub4forum['last_subject'],'35');
																		            $forum['last_post_date'] = $PowerBB->sys_functions->_date($forum_last_time1);
												                                    $forum['l_date'] = $forum_last_time1;
																					$forum['last_date'] = $PowerBB->sys_functions->time_ago($forum_last_time1);
																					$forum['last_time_ago'] = $PowerBB->sys_functions->time_ago($forum_last_time1);
																					$forum['last_date_ago'] = $PowerBB->sys_functions->_time($forum_last_time1);
																					$forum['last_subjectid'] = $sub4forum['last_subjectid'];
																					$forum['last_time'] = $sub4forum['last_time'];
																					$forum['last_reply'] = $sub4forum['last_reply'];
																					$forum['icon'] = $sub4forum['icon'];
																					$forum['review_subject'] = $sub4forum['review_subject'];
																					$forum['last_berpage_nm'] = $sub4forum['last_berpage_nm'];
																					$forum['last_writer']= $sub4forum['last_writer'];
			                                       							        $forum['username_style_cache'] = $sub4forum['username_style_cache'];
			                                       							        $forum['writer_photo']= $sub4forum['writer_photo'];
			                                       							        $forum['avater_path']= $sub4forum['avater_path'];
																                    $forum['last_subject'] =  $sub4forum['prefix_subject']." ".$PowerBB->functions->pbb_stripslashes($sub4forum['last_subject']);
																                    $forum['sec_section']= $sub4forum['sec_section'];
																                    $forum['last_writer_id']= $sub4forum['last_writer_id'];
				                                                                   }
							                                                 }

										                                        $forum['subject_num']+= $sub4forum['subject_num'];
										                                        $forum['reply_num']+= $sub4forum['reply_num'];
														                        $forum['num_subjects_awaiting_approval'] += $sub4forum['subjects_review_num'];
														                        $forum['num_replys_awaiting_approval'] += $sub4forum['replys_review_num'];


			                                                            }
																	}
							                                   }

														}

				                                   }
                                                   //
                                               }
										 }

		                                    if ($PowerBB->_CONF['info_row']['no_sub'] == 0)
		                                     {
		                                       $forum['sub'] ='0';
		                                     }
                                         $PowerBB->template->assign('SHOW_SUB_SECTIONS',true);
								   }

						   //////////


							// get writer username style cache And  writer photo
	                         $username = $forum['last_writer'];
	                         $forum['username'] = $forum['last_writer'];
	                         if ($PowerBB->_CONF['info_row']['allow_avatar'])
							 {
	                           $forum['avater_path'] = $forum['avater_path'];
	                         }
                             $user_id =  $forum['last_writer_id'];
	                       if ($username == $PowerBB->_CONF['template']['_CONF']['lang']['Guestp'])
							{
							   if ($PowerBB->_CONF['info_row']['allow_avatar'])
							    {
								 $forum['writer_photo'] = $PowerBB->functions->GetForumAdress().$PowerBB->_CONF['template']['image_path'].'/'.$PowerBB->_CONF['info_row']['default_avatar'];
								}
								 $forum['username'] = $PowerBB->_CONF['template']['_CONF']['lang']['Guestp'];
								 $forum['last_writer'] = $PowerBB->_CONF['template']['_CONF']['lang']['Guestp'];
							}
							else
							{
							   if ($PowerBB->_CONF['info_row']['allow_avatar'])
							    {
	                               if (empty($forum['avater_path']))
	                               {
									$forum['writer_photo'] = $PowerBB->functions->GetForumAdress().$PowerBB->_CONF['template']['image_path'].'/'.$PowerBB->_CONF['info_row']['default_avatar'];
								   }
								   else
	                               {
								     $forum['writer_photo'] = $forum['avater_path'];
								   }
                                }
						    if ($PowerBB->_CONF['info_row']['get_group_username_style'])
						      {
	                               if (empty($user_id))
	                               {
									$username_style_cache = '<a href="index.php?page=profile&amp;show=1&amp;username=' . $username . '">' . $forum['username_style_cache'] . '</a> ';
								   }
								   else
	                               {
									$username_style_cache = '<a href="index.php?page=profile&amp;show=1&amp;id=' . $user_id . '">' . $forum['username_style_cache'] . '</a> ';
								   }
      	                        $forum['last_writer'] = $PowerBB->functions->rewriterule($username_style_cache);
						      }
						      else
						      {
	                               if (empty($user_id))
	                               {
									$username_style = '<a href="index.php?page=profile&amp;show=1&amp;username=' . $username . '">' . $forum['last_writer'] . '</a> ';
								   }
								   else
	                               {
									$username_style = '<a href="index.php?page=profile&amp;show=1&amp;id=' . $user_id . '">' . $forum['last_writer'] . '</a> ';
								   }
      	                        $forum['last_writer'] = $PowerBB->functions->rewriterule($username_style);
						      }

							}
	                          //
                                  if (@!strstr($forum['writer_photo'],'http')
								 or @!strstr($forum['writer_photo'],'www.'))
								 {
									if (@strstr($forum['writer_photo'],'download/avatar/')
									or @strstr($forum['writer_photo'],'look/images/avatar/'))
									{
									 $forum['writer_photo'] = $PowerBB->functions->GetForumAdress().$forum['writer_photo'];
									}
								 }

                            if ($PowerBB->functions->GetServerProtocol() == 'https://')
							 {
                              $https_  = "https://".$PowerBB->_SERVER['HTTP_HOST'];
                              $httpswww_  = "https://www.".$PowerBB->_SERVER['HTTP_HOST'];
                              $http_  = "http://".$PowerBB->_SERVER['HTTP_HOST'];
                              $http_www_  = "http://www.".$PowerBB->_SERVER['HTTP_HOST'];

	       					  $forum['writer_photo'] = str_replace($http_, $https_, $forum['writer_photo']);
							  $forum['writer_photo'] = @str_ireplace($http_, $https_, $forum['writer_photo']);
	       					  $forum['writer_photo'] = str_replace($http_www_, $httpswww_, $forum['writer_photo']);
							  $forum['writer_photo'] = @str_ireplace($http_www_, $httpswww_, $forum['writer_photo']);
                             }

                             $forum['writer_photo'] = str_replace($PowerBB->functions->GetForumAdress().$PowerBB->functions->GetForumAdress(), $PowerBB->functions->GetForumAdress(), $forum['writer_photo']);
						  // Get the moderators list as a _link_ and store it in $forum['moderators_list']
		                   if ($PowerBB->_CONF['info_row']['no_moderators'])
						   {
								$forum['is_moderators'] 		= 	0;
								$forum['moderators_list']		=	'';

								if (!empty($forum['moderators']))
								{
									$moderators = json_decode($forum['moderators'], true);

									if (is_array($moderators))
									{
		                               foreach($moderators as $moderator)
										{
											if (!$forum['is_moderators'])
											{
												$forum['is_moderators'] = 1;
											}
											if ($moderator['username'] == $PowerBB->_CONF['member_row']['username']
											or $PowerBB->_CONF['group_info']['vice']
											or $PowerBB->_CONF['member_row']['usergroup'] == '1')
											{
								               if ($forum['subjects_review_num']>0)
												{
												$forum['num_subjects_awaiting_approval'] =	$PowerBB->functions->with_comma($forum['subjects_review_num']);
												}
					                           if ($forum['replys_review_num']>0)
												{
												$forum['num_replys_awaiting_approval'] =	$PowerBB->functions->with_comma($forum['replys_review_num']);
												}
											}
							               if ($PowerBB->_CONF['info_row']['rewriterule'] == '1')
											{
											$forum['moderators_list'] .= '<a href="u' . $moderator['member_id'] . '.html">' . $PowerBB->functions->GetUsernameStyle($moderator['username']) . '</a> , ';
											}
											else
											{
								            $forum['moderators_list'] .= '<a href="index.php?page=profile&amp;show=1&amp;id=' . $moderator['member_id'] . '">' . $PowerBB->functions->GetUsernameStyle($moderator['username']) .'</a> , ';
											}
										}
									}
								}
		                    }
							//////////


							// Get online forums
							if ($PowerBB->_CONF['info_row']['active_forum_online_number'])
							{
							  $Forum_online_number = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(1),id FROM " . $PowerBB->table['online'] . " WHERE section_id='" . $forum['id'] . "' LIMIT 1"));
							  if ($forum['is_sub'])
							  {
							  $Forum_online_number_sub = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(1),id FROM " . $PowerBB->table['online'] . " WHERE subject_show ='" . $forum['id'] . "' LIMIT 1"));
							  $forum['forum_online'] = $Forum_online_number+$Forum_online_number_sub;
							  }
							  else
							  {
							   $forum['forum_online'] = $Forum_online_number;
							  }
							}

							if ($forum['forum_title_color'] !='')
					         {
							    $forum_title_color = $forum['forum_title_color'];
							    $forum['title'] = "<span style=color:".$forum_title_color.">".$forum['title']."</span>";
							 }

                            if ($forum['linksection'])
							{
							  $forum['forum_icon'] = "f_redirect";
							  $forum['forum_icon_alt'] = $PowerBB->_CONF['template']['_CONF']['lang']['Guidance_re'];
							}
							else
							{
		                       if ($PowerBB->_CONF['group_info']['write_subject'] == 0)
								{
								  $forum['forum_icon'] = "f_pass_unread";
								  $forum['forum_icon_alt'] = $PowerBB->_CONF['template']['_CONF']['lang']['no_write_subject'];
								}
								elseif ($forum['last_time'] > $PowerBB->_CONF['member_row']['lastvisit'])
								{
								  $forum['forum_icon'] = "f_unread";
								  $forum['forum_icon_alt'] = $PowerBB->_CONF['template']['_CONF']['lang']['new_posts'];
								}
								else
								{
								  $forum['forum_icon'] = "f_read";
								  $forum['forum_icon_alt'] = $PowerBB->_CONF['template']['_CONF']['lang']['no_new_posts'];
								}
                             }
                             if($PowerBB->functions->ModeratorCheck($forum['moderators']))
                             {
                              $forum['ModeratorCheck'] = 0;
                              $forum['IsModeratorCheck'] = 1;
                             }
                             else
                             {
                              $forum['ModeratorCheck'] = 1;
                              $forum['IsModeratorCheck'] = 0;
                             }

							$PowerBB->_CONF['template']['foreach']['forums_list'][$forum['id'] . '_f'] = $forum;
							unset($groups);

						}// end view forums
		             } // end foreach ($forums)

			  } // end !empty($forums_cache) // end foreach ($forums)
            else
            {
            $PowerBB->template->assign('SHOW_SUB_SECTIONS',false);
            }
	   $PowerBB->template->assign('Section_Title',$this->Section['title']);
       $PowerBB->template->assign('Section_Id',$this->Section['id']);

	}


	/**
	 * Get sections list.
	 */
	function _GetSections_direct()
	{
		global $PowerBB;

			if($PowerBB->_CONF['files_forums_Cache'])
			{
			@include("cache/forums_cache/forums_cache_".$this->Section['id'].".php");
			}
			elseif($PowerBB->_CONF['forums_parent_direct'])
			{
			$forums_cache = $PowerBB->functions->get_forum_cache($this->Section['id'],$this->Section['forums_cache']);
			}
			$cache = $PowerBB->functions->decode_forum_cache($forums_cache);
			 $x = 0;

 			 if (!empty($forums_cache))
			 {
               	$ForumArr 						= 	array();
				$ForumArr['get_from']				=	'db';
				$ForumArr['proc'] 				= 	array();
				$ForumArr['proc']['*'] 			= 	array('method'=>'clean','param'=>'html');
				$ForumArr['order']				=	array();
				$ForumArr['order']['field']		=	'sort';
				$ForumArr['order']['type']		=	'ASC';
				$ForumArr['where']				=	array();
				$ForumArr['where'][0]['name']		= 	'parent';
				$ForumArr['where'][0]['oper']		= 	'=';
				$ForumArr['where'][0]['value']	= 	$this->Section['id'];
				// Get main sections
				$forums = $PowerBB->core->GetList($ForumArr,'section');

					foreach($forums as $forum)
					{
                        $forum['last_writer_id'] = $cache[$x]['last_writer_id'];
                        $forum['avater_path'] = $cache[$x]['avater_path'];
                        $forum['username_style_cache'] = $cache[$x]['username_style_cache'];
                        $forum['prefix_subject'] = $cache[$x]['prefix_subject'];

						if ($PowerBB->_CONF['group_info']['vice']
						or $PowerBB->_CONF['member_row']['usergroup'] == '1')
						{
			               if ($forum['subjects_review_num']>0)
							{
							$forum['num_subjects_awaiting_approval'] =	$PowerBB->functions->with_comma($forum['subjects_review_num']);
							}
                           if ($forum['replys_review_num']>0)
							{
							$forum['num_replys_awaiting_approval'] =	$PowerBB->functions->with_comma($forum['replys_review_num']);
							}
						}
						//////////////////////////

						if ($PowerBB->functions->section_group_permission($forum['id'],$PowerBB->_CONF['group_info']['id'],'view_section',$forum['sectiongroup_cache']))
						{
                         if (!$PowerBB->functions->section_group_permission($forum['id'],$PowerBB->_CONF['group_info']['id'],'view_subject',$forum['sectiongroup_cache']))
						 {
						   $forum['hide_subject']	= '1';
                         }
                           if (!empty($forum['last_date']))
                           {
							$forum_last_time1 = $forum['last_date'];
							$forum['last_subject'] = $PowerBB->Powerparse->censor_words($forum['last_subject']);
							$forum['last_subject_title'] =  $forum['last_subject'];
							$forum['last_subject'] =  $PowerBB->Powerparse->_wordwrap($forum['last_subject'],'35');
							$forum['last_post_date'] = $PowerBB->sys_functions->_date($forum['last_time']);
							$forum['l_date'] = $forum_last_time1;
							$forum['last_date'] = $PowerBB->sys_functions->time_ago($forum_last_time1);
							$forum['last_time_ago'] = $PowerBB->sys_functions->time_ago($forum_last_time1);
							$forum['last_date_ago'] = $PowerBB->sys_functions->_time($forum_last_time1);
							$forum['last_subjectid'] = $forum['last_subjectid'];
							$forum['last_time'] = $forum['last_time'];
							$forum['last_reply'] = $forum['last_reply'];
							$forum['icon'] = $forum['icon'];
							$forum['review_subject'] = $forum['review_subject'];
							$forum['last_berpage_nm'] = $forum['last_berpage_nm'];
							$forum['last_writer']= $forum['last_writer'];
							$forum['username_style_cache'] = $forum['username_style_cache'];
							if (isset($forum['writer_photo']))
							{
							$forum['writer_photo']= $forum['writer_photo'];
							}
							$forum['avater_path']= $forum['avater_path'];
							$forum['last_subject'] =  $forum['prefix_subject']." ".$PowerBB->functions->pbb_stripslashes($forum['last_subject']);
                            $forum['sec_section']= $forum['sec_section'];
                            $forum['last_writer_id']= $forum['last_writer_id'];
                           }

                            $kay =$cat['id'];
                            if (isset($PowerBB->_COOKIE["pbboard_collapse_forumid_$kay"]))
                            {
							$forum['collapse']= $PowerBB->_COOKIE["pbboard_collapse_forumid_$kay"];
							}
									$forum['is_sub'] 	= 	0;
									$forum['sub']		=	'';
									$t_sub=0;
								if($PowerBB->_CONF['files_forums_Cache'])
								{
								@include("cache/forums_cache/forums_cache_".$forum['id'].".php");
								}
								elseif($PowerBB->_CONF['forums_parent_direct'])
								{
								$forums_cache = $PowerBB->functions->get_forum_cache($forum['id'],$forum['forums_cache']);
								}
								$cache1 = $PowerBB->functions->decode_forum_cache($forums_cache);
								 $xs = 0;
                                    if (!empty($forums_cache))
		                           {

					               	$SubArr 						= 	array();
									$SubArr['get_from']				=	'db';
									$SubArr['proc'] 				= 	array();
									$SubArr['proc']['*'] 			= 	array('method'=>'clean','param'=>'html');
									$SubArr['order']				=	array();
									$SubArr['order']['field']		=	'sort';
									$SubArr['order']['type']		=	'ASC';
									$SubArr['where']				=	array();
									$SubArr['where'][0]['name']		= 	'parent';
									$SubArr['where'][0]['oper']		= 	'=';
									$SubArr['where'][0]['value']	= 	$forum['id'];
									// Get sub sections
									$subs = $PowerBB->core->GetList($SubArr,'section');

		                               foreach($subs as $sub)
										{

										   if ($forum['id'] == $sub['parent'])
		                                    {
										        if (!empty($sub['last_date']))
										         {

										          $forum['subject_num'] += $sub['subject_num'];
										          $forum['reply_num'] += $sub['reply_num'];
										          $forum['num_subjects_awaiting_approval'] += $sub['subjects_review_num'];
										          $forum['num_replys_awaiting_approval'] += $sub['replys_review_num'];

										           if ($sub['last_time'] > $forum['last_time'])
										           {
	                                             	$forum_last_time1 = $sub['last_date'];
													$forum['last_subject'] = $PowerBB->Powerparse->censor_words($sub['last_subject']);
                                                   	$forum['last_subject_title'] =  $forum['last_subject'];
													$forum['last_subject'] =  $PowerBB->Powerparse->_wordwrap($sub['last_subject'],'35');
													$forum['last_post_date'] = $PowerBB->sys_functions->_date($forum_last_time1);
				                                     $forum['l_date'] = $forum_last_time1;
													 $forum['last_date'] = $PowerBB->sys_functions->time_ago($forum_last_time1);
													 $forum['last_time_ago'] = $PowerBB->sys_functions->time_ago($forum_last_time1);
													 $forum['last_date_ago'] = $PowerBB->sys_functions->_time($forum_last_time1);
													$forum['last_subjectid'] = $sub['last_subjectid'];
													$forum['last_time'] = $sub['last_time'];
													$forum['last_reply'] = $sub['last_reply'];
													$forum['icon'] = $sub['icon'];
													$forum['review_subject'] = $sub['review_subject'];
													$forum['last_berpage_nm'] = $sub['last_berpage_nm'];
													$forum['last_writer']= $sub['last_writer'];
                       							    $forum['username_style_cache'] = $sub['username_style_cache'];

													$forum['last_writer_id'] = $cache1[$xs]['last_writer_id'];
													$forum['avater_path'] = $cache1[$xs]['avater_path'];
													$forum['username_style_cache'] = $cache1[$xs]['username_style_cache'];
													$forum['prefix_subject'] = $cache1[$xs]['prefix_subject'];

								                    $forum['last_subject'] =  $forum['prefix_subject']." ".$PowerBB->functions->pbb_stripslashes($sub['last_subject']);
								                    $forum['sec_section']= $sub['sec_section'];
								                  }
                                               }

												  if ($PowerBB->functions->section_group_permission($sub['id'],$PowerBB->_CONF['group_info']['id'],'view_section',$sub['sectiongroup_cache']))
												   {
												        if ($sub['forum_title_color'] !='')
												         {
														    $forum_title_color = $sub['forum_title_color'];
														    $sub['title'] = "<span style=color:".$forum_title_color.">".$PowerBB->functions->pbb_stripslashes($sub['title'])."</span>";
														 }
														if ($sub['id'])
														{
														$forum['is_sub'] = 1;
														}
														if($t_sub== $PowerBB->_CONF['info_row']['sub_columns_number']){
														$t_sub=0;
														$forum['sub'] .='</ol><br /><ol class="home-sub-forums-columns-2">';
														}
														$forum_url = "index.php?page=forum&amp;show=1&amp;id=";
														$forum['sub'] .= '<li class="home-sub-forums">';
														$forum['sub'] .= ' <a class="sub-forums-title" style="padding-right:11px;" href="'.$PowerBB->functions->rewriterule($forum_url).$sub['id'].'"> '.$sub['title'].'</a>';
														$forum['sub'] .= "</li>";
														$t_sub=$t_sub+1;
											        }
                                                   // subs forum ++
													if($PowerBB->_CONF['files_forums_Cache'])
													{
													@include("cache/forums_cache/forums_cache_".$sub['id'].".php");
													}
													elseif($PowerBB->_CONF['forums_parent_direct'])
													{
													$forums_cache = $PowerBB->functions->get_forum_cache($sub['id'],$sub['forums_cache']);
													}
													$cache2 = $PowerBB->functions->decode_forum_cache($forums_cache);
													 $xsu = 0;

 				                                   if (!empty($forums_cache))
						                           {
										               	$SubsArr 						= 	array();
														$SubsArr['get_from']				=	'db';
														$SubsArr['proc'] 				= 	array();
														$SubsArr['proc']['*'] 			= 	array('method'=>'clean','param'=>'html');
														$SubsArr['order']				=	array();
														$SubsArr['order']['field']		=	'sort';
														$SubsArr['order']['type']		=	'ASC';
														$SubsArr['where']				=	array();
														$SubsArr['where'][0]['name']		= 	'parent';
														$SubsArr['where'][0]['oper']		= 	'=';
														$SubsArr['where'][0]['value']	= 	$sub['id'];
														$subsforum = $PowerBB->core->GetList($SubsArr,'section');

						                               foreach($subsforum as $subforum)
														{

														    if ($sub['id'] == $subforum['parent'])
														    {
														        if (!empty($subforum['last_date']))
														         {

														           $forum['subject_num'] += $subforum['subject_num'];
										                           $forum['reply_num'] += $subforum['reply_num'];
														           $forum['num_subjects_awaiting_approval'] += $subforum['subjects_review_num'];
														           $forum['num_replys_awaiting_approval'] += $subforum['replys_review_num'];

															           if ($subforum['last_time'] > $sub['last_time'] and $subforum['last_time'] > $forum['last_time'])
															           {
						                                             	$forum_last_time1 = $subforum['last_date'];
																		$forum['last_subject'] = $PowerBB->Powerparse->censor_words($subforum['last_subject']);
																		$forum['last_subject_title'] =  $forum['last_subject'];
																		$forum['last_subject'] =  $PowerBB->Powerparse->_wordwrap($subforum['last_subject'],'35');
																		$forum['last_post_date'] = $PowerBB->sys_functions->_date($forum_last_time1);
									                                    $forum['l_date'] = $forum_last_time1;
																		$forum['last_date'] = $PowerBB->sys_functions->time_ago($forum_last_time1);
																		$forum['last_time_ago'] = $PowerBB->sys_functions->time_ago($forum_last_time1);
																		$forum['last_date_ago'] = $PowerBB->sys_functions->_time($forum_last_time1);
																		$forum['last_subjectid'] = $subforum['last_subjectid'];
																		$forum['last_time'] = $subforum['last_time'];
																		$forum['last_reply'] = $subforum['last_reply'];
																		$forum['icon'] = $subforum['icon'];
																		$forum['review_subject'] = $subforum['review_subject'];
																		$forum['last_berpage_nm'] = $subforum['last_berpage_nm'];
																		$forum['last_writer']= $subforum['last_writer'];
                                       							        $forum['username_style_cache'] = $subforum['username_style_cache'];

												                        $forum['last_writer_id'] = $cache2[$xsu]['last_writer_id'];
												                        $forum['avater_path'] = $cache2[$xsu]['avater_path'];
												                        $forum['username_style_cache'] = $cache2[$xsu]['username_style_cache'];
												                        $forum['prefix_subject'] = $cache2[$xsu]['prefix_subject'];

													                    $forum['last_subject'] =  $forum['prefix_subject']." ".$PowerBB->functions->pbb_stripslashes($subforum['last_subject']);
													                    $forum['sec_section']= $subforum['sec_section'];
	                                                                   }
				                                                 }

                                                            }

                                                              // subs forum +++
																if($PowerBB->_CONF['files_forums_Cache'])
																{
																@include("cache/forums_cache/forums_cache_".$subforum['id'].".php");
																}
																elseif($PowerBB->_CONF['forums_parent_direct'])
																{
																$forums_cache = $PowerBB->functions->get_forum_cache($subforum['id'],$subforum['forums_cache']);
																}
																$cache3 = $PowerBB->functions->decode_forum_cache($forums_cache);
																 $xsub = 0;
 						                                   if (!empty($forums_cache))
								                           {
												               	$Subs4Arr 						= 	array();
																$Subs4Arr['get_from']				=	'db';
																$Subs4Arr['proc'] 				= 	array();
																$Subs4Arr['proc']['*'] 			= 	array('method'=>'clean','param'=>'html');
																$Subs4Arr['order']				=	array();
																$Subs4Arr['order']['field']		=	'sort';
																$Subs4Arr['order']['type']		=	'ASC';
																$Subs4Arr['where']				=	array();
																$Subs4Arr['where'][0]['name']		= 	'parent';
																$Subs4Arr['where'][0]['oper']		= 	'=';
																$Subs4Arr['where'][0]['value']	= 	$subforum['id'];
																$subs4forum = $PowerBB->core->GetList($Subs4Arr,'section');

								                               foreach($subs4forum  as $sub4forum)
																{

																    if ($subforum['id'] == $sub4forum['parent'])
																    {
																        if (!empty($sub4forum['last_date']))
																         {
																            $forum['subject_num'] += $sub4forum['subject_num'];
										                                    $forum['reply_num'] += $sub4forum['reply_num'];
            														        $forum['num_subjects_awaiting_approval'] += $sub4forum['subjects_review_num'];
														                    $forum['num_replys_awaiting_approval'] += $sub4forum['replys_review_num'];

																	           if ($sub4forum['last_time'] > $sub['last_time'] and $sub4forum['last_time'] > $subforum['last_time'] and $sub4forum['last_time'] > $forum['last_time'])
																	           {
								                                             	$forum_last_time1 = $sub4forum['last_date'];
																				$forum['last_subject'] = $PowerBB->Powerparse->censor_words($sub4forum['last_subject']);
																				$forum['last_subject_title'] =  $forum['last_subject'];
																				$forum['last_subject'] =  $PowerBB->Powerparse->_wordwrap($sub4forum['last_subject'],'35');
																				$forum['last_post_date'] = $PowerBB->sys_functions->_date($forum_last_time1);
											                                    $forum['l_date'] = $forum_last_time1;
																				$forum['last_date'] = $PowerBB->sys_functions->time_ago($forum_last_time1);
																				$forum['last_time_ago'] = $PowerBB->sys_functions->time_ago($forum_last_time1);
																				$forum['last_date_ago'] = $PowerBB->sys_functions->_time($forum_last_time1);
																				$forum['last_subjectid'] = $sub4forum['last_subjectid'];
																				$forum['last_time'] = $sub4forum['last_time'];
																				$forum['last_reply'] = $sub4forum['last_reply'];
																				$forum['icon'] = $sub4forum['icon'];
																				$forum['review_subject'] = $sub4forum['review_subject'];
																				$forum['last_berpage_nm'] = $sub4forum['last_berpage_nm'];
																				$forum['last_writer']= $sub4forum['last_writer'];

														                        $forum['last_writer_id'] = $cache3[$xsub]['last_writer_id'];
														                        $forum['avater_path'] = $cache3[$xsub]['avater_path'];
														                        $forum['username_style_cache'] = $cache3[$xsub]['username_style_cache'];
														                        $forum['prefix_subject'] = $cache3[$xsub]['prefix_subject'];

															                    $forum['last_subject'] =  $forum['prefix_subject']." ".$PowerBB->functions->pbb_stripslashes($sub4forum['last_subject']);
															                    $forum['sec_section']= $sub4forum['sec_section'];
			                                                                   }

						                                                 }

		                                                            }


																	   // subs forum ++++
																		if($PowerBB->_CONF['files_forums_Cache'])
																		{
																		@include("cache/forums_cache/forums_cache_".$sub4forum['id'].".php");
																		}
																		elseif($PowerBB->_CONF['forums_parent_direct'])
																		{
																		$forums_cache = $PowerBB->functions->get_forum_cache($sub4forum['id'],$sub4forum['forums_cache']);
																		}
																		$cache4 = $PowerBB->functions->decode_forum_cache($forums_cache);
																		 $xsub5 = 0;

			 						                                   if (!empty($forums_cache))
											                           {
										                                    $Subs5Arr 						= 	array();
																			$Subs5Arr['get_from']				=	'db';
																			$Subs5Arr['proc'] 				= 	array();
																			$Subs5Arr['proc']['*'] 			= 	array('method'=>'clean','param'=>'html');
																			$Subs5Arr['order']				=	array();
																			$Subs5Arr['order']['field']		=	'sort';
																			$Subs5Arr['order']['type']		=	'ASC';
																			$Subs5Arr['where']				=	array();
																			$Subs5Arr['where'][0]['name']		= 	'parent';
																			$Subs5Arr['where'][0]['oper']		= 	'=';
																			$Subs5Arr['where'][0]['value']	= 	$sub4forum['id'];
																			$subs5forum = $PowerBB->core->GetList($Subs5Arr,'section');
											                               foreach($subs5forum  as $sub5forum)
																			{
																			    if ($sub4forum['id'] == $sub5forum['parent'])
																			    {
																			        if (!empty($sub5forum['last_date']))
																			         {
																			            $forum['subject_num'] += $sub5forum['subject_num'];
													                                    $forum['reply_num'] += $sub5forum['reply_num'];
			            														        $forum['num_subjects_awaiting_approval'] += $sub5forum['subjects_review_num'];
																	                    $forum['num_replys_awaiting_approval'] += $sub5forum['replys_review_num'];

																				           if ($sub5forum['last_time'] > $sub['last_time'] and $sub5forum['last_time'] > $subforum['last_time'] and $sub5forum['last_time'] > $forum['last_time'])
																				           {
											                                             	$forum_last_time1 = $sub5forum['last_date'];
																							$forum['last_subject'] = $PowerBB->Powerparse->censor_words($sub5forum['last_subject']);
																							$forum['last_subject_title'] =  $forum['last_subject'];
																							$forum['last_subject'] =  $PowerBB->Powerparse->_wordwrap($sub5forum['last_subject'],'35');
																							$forum['last_post_date'] = $PowerBB->sys_functions->_date($forum_last_time1);
														                                    $forum['l_date'] = $forum_last_time1;
																							$forum['last_date'] = $PowerBB->sys_functions->time_ago($forum_last_time1);
																							$forum['last_time_ago'] = $PowerBB->sys_functions->time_ago($forum_last_time1);
																							$forum['last_date_ago'] = $PowerBB->sys_functions->_time($forum_last_time1);
																							$forum['last_subjectid'] = $sub5forum['last_subjectid'];
																							$forum['last_time'] = $sub5forum['last_time'];
																							$forum['last_reply'] = $sub5forum['last_reply'];
																							$forum['icon'] = $sub5forum['icon'];
																							$forum['review_subject'] = $sub5forum['review_subject'];
																							$forum['last_berpage_nm'] = $sub5forum['last_berpage_nm'];
																							$forum['last_writer']= $sub5forum['last_writer'];

																	                        $forum['last_writer_id'] = $cache4[$xsub5]['last_writer_id'];
																	                        $forum['avater_path'] = $cache4[$xsub5]['avater_path'];
																	                        $forum['username_style_cache'] = $cache4[$xsub5]['username_style_cache'];
																	                        $forum['prefix_subject'] = $cache4[$xsub5]['prefix_subject'];

																		                    $forum['last_subject'] =  $forum['prefix_subject']." ".$PowerBB->functions->pbb_stripslashes($sub5forum['last_subject']);
																		                    $forum['sec_section']= $sub5forum['sec_section'];
						                                                                   }

									                                                 }

					                                                            }


                                                                                       // subs forum +++++
																						if($PowerBB->_CONF['files_forums_Cache'])
																						{
																						@include("cache/forums_cache/forums_cache_".$sub5forum['id'].".php");
																						}
																						elseif($PowerBB->_CONF['forums_parent_direct'])
																						{
																						$forums_cache = $PowerBB->functions->get_forum_cache($sub5forum['id'],$sub5forum['forums_cache']);
																						}
																						$cache5 = $PowerBB->functions->decode_forum_cache($forums_cache);
																						 $xsub6 = 0;
							 						                                   if (!empty($forums_cache))
															                           {
																		               	$Subs6Arr 						= 	array();
																						$Subs6Arr['get_from']				=	'db';
																						$Subs6Arr['proc'] 				= 	array();
																						$Subs6Arr['proc']['*'] 			= 	array('method'=>'clean','param'=>'html');
																						$Subs6Arr['order']				=	array();
																						$Subs6Arr['order']['field']		=	'sort';
																						$Subs6Arr['order']['type']		=	'ASC';
																						$Subs6Arr['where']				=	array();
																						$Subs6Arr['where'][0]['name']		= 	'parent';
																						$Subs6Arr['where'][0]['oper']		= 	'=';
																						$Subs6Arr['where'][0]['value']	= 	$sub5forum['id'];
																						$subs = $PowerBB->core->GetList($Subs6Arr,'section');
															                               foreach($subs6forum  as $sub6forum)
																							{
																							    if ($subforum['id'] == $sub6forum['parent'])
																							    {
																							        if (!empty($sub6forum['last_date']))
																							         {
																							            $forum['subject_num'] += $sub6forum['subject_num'];
																	                                    $forum['reply_num'] += $sub6forum['reply_num'];
							            														        $forum['num_subjects_awaiting_approval'] += $sub6forum['subjects_review_num'];
																					                    $forum['num_replys_awaiting_approval'] += $sub6forum['replys_review_num'];

																								           if ($sub6forum['last_time'] > $sub['last_time'] and $sub6forum['last_time'] > $subforum['last_time'] and $sub6forum['last_time'] > $forum['last_time'])
																								           {
															                                             	$forum_last_time1 = $sub6forum['last_date'];
																											$forum['last_subject'] = $PowerBB->Powerparse->censor_words($sub6forum['last_subject']);
																											$forum['last_subject_title'] =  $forum['last_subject'];
																											$forum['last_subject'] =  $PowerBB->Powerparse->_wordwrap($sub6forum['last_subject'],'35');
																											$forum['last_post_date'] = $PowerBB->sys_functions->_date($forum_last_time1);
																		                                    $forum['l_date'] = $forum_last_time1;
																											$forum['last_date'] = $PowerBB->sys_functions->time_ago($forum_last_time1);
																											$forum['last_time_ago'] = $PowerBB->sys_functions->time_ago($forum_last_time1);
																											$forum['last_date_ago'] = $PowerBB->sys_functions->_time($forum_last_time1);
																											$forum['last_subjectid'] = $sub6forum['last_subjectid'];
																											$forum['last_time'] = $sub6forum['last_time'];
																											$forum['last_reply'] = $sub6forum['last_reply'];
																											$forum['icon'] = $sub6forum['icon'];
																											$forum['review_subject'] = $sub6forum['review_subject'];
																											$forum['last_berpage_nm'] = $sub6forum['last_berpage_nm'];
																											$forum['last_writer']= $sub6forum['last_writer'];

																				                            $forum['last_writer_id'] = $cache5[$xsub6]['last_writer_id'];
																					                        $forum['avater_path'] = $cache5[$xsub6]['avater_path'];
																					                        $forum['username_style_cache'] = $cache5[$xsub6]['username_style_cache'];
																					                        $forum['prefix_subject'] = $cache5[$xsub6]['prefix_subject'];

																						                    $forum['last_subject'] =  $forum['prefix_subject']." ".$PowerBB->functions->pbb_stripslashes($sub6forum['last_subject']);
																						                    $forum['sec_section']= $sub6forum['sec_section'];
										                                                                   }

													                                                 }

									                                                            }
                                                                                             $xsu6 += 1;
																							}
													                                   }


                                                                             $xsub5 += 1;
																			}
									                                   }
                                                                 $xsub += 1;
																}
						                                   }

                                                          $xsu += 1;

														}
				                                   }
                                                   //

                                               }


                                            $xs += 1;

										 }
		                                    if ($PowerBB->_CONF['info_row']['no_sub'] == 0)
		                                     {
		                                       $forum['sub'] ='0';
		                                     }
		                                     $PowerBB->template->assign('SHOW_SUB_SECTIONS',true);
								   }


                            /*
							if($sub['reply_num'] > 0)
							{
							$sub['reply_num']   = $sub['reply_num']-1;
							}
							if($forum['subject_num'] > 0)
							{
							$forum['subject_num']   = $forum['subject_num']-1;
							}
							*/
						   //////////
							// get writer username style cache And  writer photo
							$username = $forum['last_writer'];
	                         $forum['username'] = $forum['last_writer'];
	                         $forum['writer_photo'] = $forum['avater_path'];

                             $user_id =  $forum['last_writer_id'];
	                        if ($forum['last_writer'] == $PowerBB->_CONF['template']['_CONF']['lang']['Guestp']
	                         or $forum['last_writer'] == 'Guest')
							{
							   if ($PowerBB->_CONF['info_row']['allow_avatar'])
							    {
								 $forum['writer_photo'] = $PowerBB->functions->GetForumAdress().$PowerBB->_CONF['template']['image_path'].'/'.$PowerBB->_CONF['info_row']['default_avatar'];
								}
								 $forum['username'] = $PowerBB->_CONF['template']['_CONF']['lang']['Guest_'];
								 $forum['last_writer'] = $PowerBB->_CONF['template']['_CONF']['lang']['Guest_'];
							}
							else
							{
							   if ($PowerBB->_CONF['info_row']['allow_avatar'])
							    {
	                               if (empty($forum['avater_path']))
	                               {
									$forum['writer_photo'] = $PowerBB->functions->GetForumAdress().$PowerBB->_CONF['template']['image_path'].'/'.$PowerBB->_CONF['info_row']['default_avatar'];
								   }
								   else
	                               {
								     $forum['writer_photo'] = $forum['avater_path'];
								   }
                                }
						    if ($PowerBB->_CONF['info_row']['get_group_username_style'])
						      {
	                               if (empty($forum['last_writer_id']))
	                               {
									$username_style_cache = '<a href="index.php?page=profile&amp;show=1&amp;username=' . $username . '">' . $forum['username_style_cache'] . '</a> ';
								   }
								   else
	                               {
									$username_style_cache = '<a href="index.php?page=profile&amp;show=1&amp;id=' . $forum['last_writer_id'] . '">' . $forum['username_style_cache'] . '</a> ';
								   }
      	                        $forum['last_writer'] = $PowerBB->functions->rewriterule($username_style_cache);
						      }
						      else
						      {
	                               if (empty($forum['last_writer_id']))
	                               {
									$username_style = '<a href="index.php?page=profile&amp;show=1&amp;username=' . $username . '">' . $forum['last_writer'] . '</a> ';
								   }
								   else
	                               {
									$username_style = '<a href="index.php?page=profile&amp;show=1&amp;id=' . $forum['last_writer_id'] . '">' . $forum['last_writer'] . '</a> ';
								   }
      	                        $forum['last_writer'] = $PowerBB->functions->rewriterule($username_style);
						      }
							}


								if (!@strstr($forum['writer_photo'],'http')
									or !strstr($forum['writer_photo'],'www.'))
								{
									if (@strstr($forum['writer_photo'],'download/avatar/')
									or @strstr($forum['writer_photo'],'look/images/avatar/'))
									{
									 $forum['writer_photo'] = $PowerBB->functions->GetForumAdress().$forum['writer_photo'];
									}
								}

                            if ($PowerBB->functions->GetServerProtocol() == 'https://')
							 {
                              $https_  = "https://".$PowerBB->_SERVER['HTTP_HOST'];
                              $httpswww_  = "https://www.".$PowerBB->_SERVER['HTTP_HOST'];
                              $http_  = "http://".$PowerBB->_SERVER['HTTP_HOST'];
                              $http_www_  = "http://www.".$PowerBB->_SERVER['HTTP_HOST'];

	       					  $forum['writer_photo'] = str_replace($http_, $https_, $forum['writer_photo']);
							  $forum['writer_photo'] = @str_ireplace($http_, $https_, $forum['writer_photo']);
	       					  $forum['writer_photo'] = str_replace($http_www_, $httpswww_, $forum['writer_photo']);
							  $forum['writer_photo'] = @str_ireplace($http_www_, $httpswww_, $forum['writer_photo']);
                             }

                             $forum['writer_photo'] = str_replace($PowerBB->functions->GetForumAdress().$PowerBB->functions->GetForumAdress(), $PowerBB->functions->GetForumAdress(), $forum['writer_photo']);
	                          //
						  // Get the moderators list as a _link_ and store it in $forum['moderators_list']
		                   if ($PowerBB->_CONF['info_row']['no_moderators'])
						   {
								$forum['is_moderators'] 		= 	0;
								$forum['moderators_list']		=	'';
								if (!empty($forum['moderators']))
								{
									$moderators = json_decode($forum['moderators'], true);
									if (is_array($moderators))
									{
		                               foreach($moderators as $moderator)
										{
											if (!$forum['is_moderators'])
											{
												$forum['is_moderators'] = 1;
											}
											if ($moderator['username'] == $PowerBB->_CONF['member_row']['username']
											or $PowerBB->_CONF['group_info']['vice']
											or $PowerBB->_CONF['member_row']['usergroup'] == '1')
											{
								               if ($forum['subjects_review_num']>0)
												{
												$forum['num_subjects_awaiting_approval'] =	$PowerBB->functions->with_comma($forum['subjects_review_num']);
												}
					                           if ($forum['replys_review_num']>0)
												{
													$forum['num_replys_awaiting_approval'] =	$PowerBB->functions->with_comma($forum['replys_review_num']);
												}
											}
							               if ($PowerBB->_CONF['info_row']['rewriterule'] == '1')
											{
											$forum['moderators_list'] .= '<a href="u' . $moderator['member_id'] . '.html">' . $PowerBB->functions->GetUsernameStyle($moderator['username']) . '</a> , ';
											}
											else
											{
								            $forum['moderators_list'] .= '<a href="index.php?page=profile&amp;show=1&amp;id=' . $moderator['member_id'] . '">' . $PowerBB->functions->GetUsernameStyle($moderator['username']) .'</a> , ';
											}
										}
									}
								}
		                    }
							//////////
							// Get online forums
							if ($PowerBB->_CONF['info_row']['active_forum_online_number'])
							{
							  $Forum_online_number = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(1),id FROM " . $PowerBB->table['online'] . " WHERE section_id='" . $forum['id'] . "' LIMIT 1"));
							  if ($forum['is_sub'])
							  {
							  $Forum_online_number_sub = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(1),id FROM " . $PowerBB->table['online'] . " WHERE subject_show ='" . $forum['id'] . "' LIMIT 1"));
							  $forum['forum_online'] = $Forum_online_number+$Forum_online_number_sub;
							  }
							  else
							  {
							   $forum['forum_online'] = $Forum_online_number;
							  }
							}
							if ($forum['forum_title_color'] !='')
					         {
							    $forum_title_color = $forum['forum_title_color'];
							    $forum['title'] = "<span style=color:".$forum_title_color.">".$forum['title']."</span>";
							 }
                            if ($forum['linksection'])
							{
							  $forum['forum_icon'] = "f_redirect";
							  $forum['forum_icon_alt'] = $PowerBB->_CONF['template']['_CONF']['lang']['Guidance_re'];
							}
							else
							{
								if ($PowerBB->_CONF['group_info']['write_subject'] == 0)
								{
								  $forum['forum_icon'] = "f_pass_unread";
								  $forum['forum_icon_alt'] = $PowerBB->_CONF['template']['_CONF']['lang']['no_write_subject'];
								}
								elseif ($forum['last_time'] > $PowerBB->_CONF['member_row']['lastvisit'])
								{
								  $forum['forum_icon'] = "f_unread";
								  $forum['forum_icon_alt'] = $PowerBB->_CONF['template']['_CONF']['lang']['new_posts'];
								}
								else
								{
								  $forum['forum_icon'] = "f_read";
								  $forum['forum_icon_alt'] = $PowerBB->_CONF['template']['_CONF']['lang']['no_new_posts'];
								}
                             }
                             if($PowerBB->functions->ModeratorCheck($forum['moderators']))
                             {
                              $forum['ModeratorCheck'] = 0;
                              $forum['IsModeratorCheck'] = 1;
                             }
                             else
                             {
                              $forum['ModeratorCheck'] = 1;
                              $forum['IsModeratorCheck'] = 0;
                             }

                         if ($forum['review_subject'])
						 {
						   $forum['hide_subject']	= '1';
                         }


							$PowerBB->_CONF['template']['foreach']['forums_list'][$forum['id'] . '_f'] = $forum;
							unset($groups);
						}// end view forums
                         $x += 1;
		             } // end foreach ($forums)



			} // end !empty($forums_cache)
            else
            {
            $PowerBB->template->assign('SHOW_SUB_SECTIONS',false);
            }
	   $PowerBB->template->assign('Section_Title',$this->Section['title']);
       $PowerBB->template->assign('Section_Id',$this->Section['id']);

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
		$SubjectArr['where'] 				= 	array();
		$SubjectArr['where'][0] 			= 	array();
		$SubjectArr['where'][0]['name'] 	= 	'section';
		$SubjectArr['where'][0]['oper'] 	= 	'=';
		$SubjectArr['where'][0]['value'] 	= 	$this->Section['id'];

		$SubjectArr['where'][1] 			= 	array();
		$SubjectArr['where'][1]['con']		=	'AND';
		$SubjectArr['where'][1]['name'] 	= 	'stick';
		$SubjectArr['where'][1]['oper'] 	= 	'<>';
		$SubjectArr['where'][1]['value'] 	= 	'1';

		$SubjectArr['where'][2] 			= 	array();
		$SubjectArr['where'][2]['con']		=	'AND';
		$SubjectArr['where'][2]['name'] 	= 	'review_subject';
		$SubjectArr['where'][2]['oper'] 	= 	'<>';
		$SubjectArr['where'][2]['value'] 	= 	'1';
        if (!$PowerBB->functions->ModeratorCheck($this->Section['id']))
   		{
		$SubjectArr['where'][3] 			= 	array();
		$SubjectArr['where'][3]['con']		=	'AND';
		$SubjectArr['where'][3]['name'] 	= 	'delete_topic';
		$SubjectArr['where'][3]['oper'] 	= 	'<>';
		$SubjectArr['where'][3]['value'] 	= 	'1';
        }

		if ($this->Section['hide_subject']
			and !$PowerBB->functions->ModeratorCheck($this->Section['moderators']))
		{
			$SubjectArr['where'][1] 			= 	array();
			$SubjectArr['where'][1]['con'] 		= 	'AND';
			$SubjectArr['where'][1]['name'] 	= 	'writer';
			$SubjectArr['where'][1]['oper'] 	= 	'=';
			$SubjectArr['where'][1]['value'] 	= 	$PowerBB->_CONF['member_row']['username'];
		}


		/** Show the subject order by (oldr - new )**/
		if($PowerBB->_GET['orderby'] == '1')
		{
		/** Show the subject order by (reply_number - visitor - rating)**/
			if ($PowerBB->_GET['sort'] == 'reply_number')
			{
				$order 	= 	'reply_number';
                 $type 	= 	'DESC';
			}
			elseif ($PowerBB->_GET['sort'] == 'visitor')
			{
				$order 	= 	'visitor';
                 $type 	= 	'DESC';
			}
			elseif ($PowerBB->_GET['sort'] == 'rating')
			{
                 $order 	= 	'rating';
                 $type 	= 	'DESC';
			}
			elseif ($PowerBB->_GET['sort'] == 'writer')
			{
                 $order 	= 	'writer';
                 $type 	= 	'DESC';
			}
			elseif ($PowerBB->_GET['sort'] == 'asc')
			{
                 $order 	= 	'id';
                 $type 	= 	'ASC';
		    }
			elseif ($PowerBB->_GET['sort'] == 'write_time')
			{
                 $order 	= 	'write_time';
                 $type 	= 	'DESC';
		    }
			else
			{
                 $order 	= 	'write_time';
                 $type 	= 	'DESC';
			}

			$location = $PowerBB->_SERVER['REQUEST_URI'];
			$location = preg_replace('/&submit=(.*?)&count='.$PowerBB->_GET['count'].'/is', "", $location);
	    	$location = preg_replace('/&count='.$PowerBB->_GET['count'].'/is', "", $location);
		    $SubjectArr['order'] = array();
			$SubjectArr['order']['field'] 	= 	$order;
			$SubjectArr['order']['type'] 	= 	$type;
		}
        else
        {
		    $SubjectArr['order'] = array();
			$SubjectArr['order']['field'] 	= 	'write_time';
			$SubjectArr['order']['type'] 	= 	'DESC';
			$location = 'index.php?page=forum&amp;show=1&amp;id=' . $this->Section['id'] . $password;


          }

		if ($this->Section['hide_subject'])
		{
		  if ($PowerBB->functions->ModeratorCheck($this->Section['moderators']))
		  {
          $subject_nums = $this->Section['subject_num'];
          }
          else
		  {
		  $Forum_user_subject_number = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(1),id FROM " . $PowerBB->table['subject'] . " WHERE section ='" . $this->Section['id'] . "' AND writer='" . $PowerBB->_CONF['member_row']['username'] . "' LIMIT 1"));
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
		  $Forum_no_review_subject = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(1),id FROM " . $PowerBB->table['subject'] . " WHERE section ='" . $this->Section['id'] . "' AND review_subject <> 1 and delete_topic <> 1 LIMIT 1"));
          $subject_nums = $Forum_no_review_subject;
          }
        }

	    $SubjectArr['proc'] 						= 	array();
		$SubjectArr['proc']['*'] 					= 	array('method'=>'clean','param'=>'html');
		$SubjectArr['proc']['native_write_time'] 	= 	array('method'=>'date','store'=>'write_date','type'=>$PowerBB->_CONF['info_row']['timesystem']);
		$SubjectArr['proc']['write_time'] 			= 	array('method'=>'date','store'=>'reply_date','type'=>$PowerBB->_CONF['info_row']['timesystem']);

		// Pager setup
		$SubjectArr['pager'] 				= 	array();
		$SubjectArr['pager']['total']		= 	$subject_nums;
		$SubjectArr['pager']['perpage'] 	= 	$PowerBB->_CONF['info_row']['subject_perpage'];
		$SubjectArr['pager']['count'] 		= 	$PowerBB->_GET['count'];
		$SubjectArr['pager']['location'] 	= 	$location;
		$SubjectArr['pager']['var'] 		= 	'count';

		$PowerBB->_CONF['template']['while']['subject_list'] = $PowerBB->core->GetList($SubjectArr,'subject');


		$StickSubjectArr = array();

		$StickSubjectArr['where'] 				= 	array();

		$StickSubjectArr['where'][0] 			= 	array();
		$StickSubjectArr['where'][0]['name'] 	= 	'section';
		$StickSubjectArr['where'][0]['oper'] 	= 	'=';
		$StickSubjectArr['where'][0]['value'] 	= 	$this->Section['id'];

		$StickSubjectArr['where'][1] 			= 	array();
		$StickSubjectArr['where'][1]['con']		=	'AND';
		$StickSubjectArr['where'][1]['name'] 	= 	'stick';
		$StickSubjectArr['where'][1]['oper'] 	= 	'=';
		$StickSubjectArr['where'][1]['value'] 	= 	'1';


        if (!$PowerBB->functions->ModeratorCheck($this->Section['id']))
   		{
		$StickSubjectArr['where'][2] 			= 	array();
		$StickSubjectArr['where'][2]['con']		=	'AND';
		$StickSubjectArr['where'][2]['name'] 	= 	'delete_topic';
		$StickSubjectArr['where'][2]['oper'] 	= 	'<>';
		$StickSubjectArr['where'][2]['value'] 	= 	'1';
		}

		if ($this->Section['hide_subject']
		and ! $PowerBB->functions->ModeratorCheck($this->Section['id']))
		{
		$StickSubjectArr['where'][3] 			= 	array();
		$StickSubjectArr['where'][3]['con'] 		= 	'AND';
		$StickSubjectArr['where'][3]['name'] 	= 	'writer';
		$StickSubjectArr['where'][3]['oper'] 	= 	'=';
		$StickSubjectArr['where'][3]['value'] 	= 	$PowerBB->_CONF['member_row']['username'];
		}

		$StickSubjectArr['order'] = array();
		$StickSubjectArr['order']['field'] 	= 	'write_time';
		$StickSubjectArr['order']['type'] 	= 	'DESC';

		$StickSubjectArr['proc'] 						= 	array();
		// Ok Mr.XSS go to hell !
		$StickSubjectArr['proc']['*'] 					= 	array('method'=>'clean','param'=>'html');
		$StickSubjectArr['proc']['native_write_time'] 	= 	array('method'=>'date','store'=>'write_date','type'=>$PowerBB->_CONF['info_row']['timesystem']);
		$StickSubjectArr['proc']['write_time'] 			= 	array('method'=>'date','store'=>'reply_date','type'=>$PowerBB->_CONF['info_row']['timesystem']);

		$PowerBB->_CONF['template']['while']['stick_subject_list'] = $PowerBB->core->GetList($StickSubjectArr,'subject');

		if (sizeof($PowerBB->_CONF['template']['while']['stick_subject_list']) <= 0)
		{
			$PowerBB->template->assign('NO_STICK_SUBJECTS',true);
		}
		else
		{
			$PowerBB->template->assign('NO_STICK_SUBJECTS',false);
		}




// Get the list of subjects that need review
				 $username = $PowerBB->_CONF['member_row']['username'];
			     $SUBJECTS_review_subject_mem = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(1),id FROM " . $PowerBB->table['subject'] . " WHERE section = ".$PowerBB->_GET['id']." AND writer='$username' AND review_subject='1' LIMIT 1"));

			 if ($PowerBB->functions->ModeratorCheck($this->Section['id']))
			 {
				$ReviewSubjectArr = array();

				$ReviewSubjectArr['where'] 				= 	array();

				$ReviewSubjectArr['where'][0] 			= 	array();
				$ReviewSubjectArr['where'][0]['name'] 	= 	'section';
				$ReviewSubjectArr['where'][0]['oper'] 	= 	'=';
				$ReviewSubjectArr['where'][0]['value'] 	= 	$this->Section['id'];

				$ReviewSubjectArr['where'][1] 			= 	array();
				$ReviewSubjectArr['where'][1]['con']	=	'AND';
				$ReviewSubjectArr['where'][1]['name'] 	= 	'review_subject';
				$ReviewSubjectArr['where'][1]['oper'] 	= 	'=';
				$ReviewSubjectArr['where'][1]['value'] 	= 	'1';

		        if (!$PowerBB->functions->ModeratorCheck($this->Section['id']))
		   		{
				$StickSubjectArr['where'][2] 			= 	array();
				$StickSubjectArr['where'][2]['con']		=	'AND';
				$StickSubjectArr['where'][2]['name'] 	= 	'delete_topic';
				$StickSubjectArr['where'][2]['oper'] 	= 	'<>';
				$StickSubjectArr['where'][2]['value'] 	= 	'1';
				}

					if ($this->Section['hide_subject']
					and ! $PowerBB->functions->ModeratorCheck($this->Section['id']))
				{
					$ReviewSubjectArr['where'][3] 			= 	array();
					$ReviewSubjectArr['where'][3]['con'] 		= 	'AND';
					$ReviewSubjectArr['where'][3]['name'] 	= 	'writer';
					$ReviewSubjectArr['where'][3]['oper'] 	= 	'=';
					$ReviewSubjectArr['where'][3]['value'] 	= 	$PowerBB->_CONF['member_row']['username'];
				}

				$ReviewSubjectArr['order'] 				= 	array();
				$ReviewSubjectArr['order']['field'] 	= 	'write_time';
				$ReviewSubjectArr['order']['type'] 		= 	'DESC';

				$ReviewSubjectArr['proc'] 						= 	array();
				// Ok Mr.XSS go to hell !
				$ReviewSubjectArr['proc']['*'] 					= 	array('method'=>'clean','param'=>'html');
				$ReviewSubjectArr['proc']['native_write_time'] 	= 	array('method'=>'date','store'=>'write_date','type'=>$PowerBB->_CONF['info_row']['timesystem']);
				$ReviewSubjectArr['proc']['write_time'] 		= 	array('method'=>'date','store'=>'reply_date','type'=>$PowerBB->_CONF['info_row']['timesystem']);

				$PowerBB->_CONF['template']['while']['review_subject_list'] = $PowerBB->subject->GetSubjectList($ReviewSubjectArr);

				if (sizeof($PowerBB->_CONF['template']['while']['review_subject_list']) <= 0)
				{
					$PowerBB->template->assign('NO_REVIEW_SUBJECTS',true);
				}
				else
				{
					$PowerBB->template->assign('NO_REVIEW_SUBJECTS',false);
				}
			 }
			elseif ($SUBJECTS_review_subject_mem>0)
			{

					$ReviewSubjectArr = array();

					$ReviewSubjectArr['where'] 				= 	array();

					$ReviewSubjectArr['where'][0] 			= 	array();
					$ReviewSubjectArr['where'][0]['name'] 	= 	'section';
					$ReviewSubjectArr['where'][0]['oper'] 	= 	'=';
					$ReviewSubjectArr['where'][0]['value'] 	= 	$this->Section['id'];

					$ReviewSubjectArr['where'][1] 			= 	array();
					$ReviewSubjectArr['where'][1]['con']	=	'AND';
					$ReviewSubjectArr['where'][1]['name'] 	= 	'review_subject';
					$ReviewSubjectArr['where'][1]['oper'] 	= 	'=';
					$ReviewSubjectArr['where'][1]['value'] 	= 	'1';

					$ReviewSubjectArr['where'][2] 			= 	array();
					$ReviewSubjectArr['where'][2]['con']	=	'AND';
					$ReviewSubjectArr['where'][2]['name'] 	= 	'writer';
					$ReviewSubjectArr['where'][2]['oper'] 	= 	'=';
					$ReviewSubjectArr['where'][2]['value'] 	= 	$PowerBB->_CONF['member_row']['username'];

					$ReviewSubjectArr['order'] 				= 	array();
					$ReviewSubjectArr['order']['field'] 	= 	'write_time';
					$ReviewSubjectArr['order']['type'] 		= 	'DESC';

					$ReviewSubjectArr['proc'] 						= 	array();
					// Ok Mr.XSS go to hell !
					$ReviewSubjectArr['proc']['*'] 					= 	array('method'=>'clean','param'=>'html');
					$ReviewSubjectArr['proc']['native_write_time'] 	= 	array('method'=>'date','store'=>'write_date','type'=>$PowerBB->_CONF['info_row']['timesystem']);
					$ReviewSubjectArr['proc']['write_time'] 		= 	array('method'=>'date','store'=>'reply_date','type'=>$PowerBB->_CONF['info_row']['timesystem']);

					$PowerBB->_CONF['template']['while']['review_subject_list'] = $PowerBB->subject->GetSubjectList($ReviewSubjectArr);

					if (sizeof($PowerBB->_CONF['template']['while']['review_subject_list']) <= 0)
					{
						$PowerBB->template->assign('NO_REVIEW_SUBJECTS',true);
					}
					else
					{
						$PowerBB->template->assign('NO_REVIEW_SUBJECTS',false);
					}

			}
			else
			{
				$PowerBB->template->assign('NO_REVIEW_SUBJECTS',true);
			}


		 if ($subject_nums > $PowerBB->_CONF['info_row']['subject_perpage'])
		 {
		   $PowerBB->template->assign('pager',$PowerBB->pager->show());
         }

		$PowerBB->template->assign('section_id',$this->Section['id']);
     	$PowerBB->template->assign('count',$PowerBB->_GET['count']);
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

          	$subject_nm = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(1),id FROM " . $PowerBB->table['subject'] . " WHERE text LIKE '%$keyword%' AND section = '$section' LIMIT 1"));


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
          	 $subject_nm = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(1),id FROM " . $PowerBB->table['subject'] . " WHERE text LIKE '%$keyword%' AND section = '$section' LIMIT 1"));
            }
             else
			{
          	 $subject_nm = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(1),id FROM " . $PowerBB->table['subject'] . " WHERE text LIKE '%$keyword%' AND section = '$section' AND sec_subject = 0 LIMIT 1"));
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

		$PowerBB->template->display('forum');

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