<?php
(!defined('IN_PowerBB')) ? die() : '';

define('CLASS_NAME','PowerBBForumMOD');
include('common.php');
class PowerBBForumMOD
{
	var $Section;
	var $SectionInfo;
	var $SectionGroup;

	function run()
	{
		global $PowerBB;
       $PowerBB->_GET['id']	= 	$PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');
 		if ($PowerBB->_CONF['info_row']['active_archive'] == '0')
		{
		print ('<script type="text/javascript">window.location="index.php?page=forum&show=1&id='.$PowerBB->_GET['id'].'";</script>');
		}
		$PowerBB->template->assign('SECTION_RSS',true);
		$PowerBB->template->assign('SECTION_ID',$PowerBB->_GET['id']);

		/** Browse the forum **/
		if ($PowerBB->_GET['show'])
		{
			$this->_BrowseForum();
		}
		/** **/
		elseif ($PowerBB->_GET['password_check'])
		{
			$this->_PasswordCheck();
		}
		else
		{
			header("Location: index.php");
			exit;
		}


	}

	/**
	 * Get all things about section , subjects of the sections , announcement and sub sections to show it
	 * Yes it's long list :\
	 */
	function _BrowseForum()
	{
		global $PowerBB;

		$this->_GeneralProcesses();

		$this->_GetSubSection();

		$this->_GetSubjectList();

		$this->_CallTemplate();
	}

	function _PasswordCheck()
	{
		global $PowerBB;

		if (empty($PowerBB->_POST['password']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['PasswordCheck']);
		}

		$this->_GeneralProcesses(true);

		$PassArr 				= 	array();
     	$PassArr['id'] 			= 	$this->Section['id'];
     	$PassArr['password'] 	= 	$PowerBB->_POST['password'];

     	$IsTruePassword = $PowerBB->section->CheckPassword($PassArr);

     	if (!$IsTruePassword)
     	{
     		$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['PasswordIsnotTrue']);
     	}
     	else
     	{
     		$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Please_wait']);
     		$PowerBB->functions->redirect('index.php?page=forum&amp;show=1&amp;id=' . $this->Section['id'] . '&amp;password=' . base64_encode($PowerBB->_POST['password']));
     	}
	}

	function _GeneralProcesses($check=false)
	{
		global $PowerBB;

		// Clean id from any strings
		$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');

		// No _GET['id'] , so ? show a small error :)
		if (empty($PowerBB->_GET['id']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}

		// Get section information and set it in $this->Section
		$SecArr 			= 	array();
		$SecArr['where'] 	= 	array('id',$PowerBB->_GET['id']);

		$this->Section = $PowerBB->core->GetInfo($SecArr,'section');

		// Clear section information from any denger
		$PowerBB->functions->CleanVariable($this->Section,'html');

		/** Get section's group information and make some checks **/
		$SecGroupArr 						= 	array();
		$SecGroupArr['where'] 				= 	array();
		$SecGroupArr['where'][0]			=	array();
		$SecGroupArr['where'][0]['name'] 	= 	'section_id';
		$SecGroupArr['where'][0]['value'] 	= 	$this->Section['id'];
		$SecGroupArr['where'][1]			=	array();
		$SecGroupArr['where'][1]['con']		=	'AND';
		$SecGroupArr['where'][1]['name']	=	'group_id';
		$SecGroupArr['where'][1]['value']	=	$PowerBB->_CONF['group_info']['id'];

		// Finally get the permissions of group
		$this->SectionGroup = $PowerBB->core->GetInfo($SecGroupArr,'sectiongroup');

		$PowerBB->template->assign('section_info',$this->Section);

		// This section isn't exists

		// This member can't view this section
		if ($this->SectionGroup['view_section'] != 1)
		{           $PowerBB->functions->ShowHeader();
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['can_not_view_section']);
		}

        if ($this->SectionGroup['view_subject'] == 0)
		{
         $PowerBB->functions->ShowHeader();
		 $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['You_are_not_allowed_access_to_the_contents_of_this_forum']);
		}

		unset($this->SectionGroup);
		// This section isn't exists
		if (!$this->Section)
		{			$PowerBB->functions->ShowHeader();
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Section_does_not_exist']);
		}

		// This is main section , so we can't get subjects list from it
		if ($this->Section['main_section'])
		{
		   $PowerBB->functions->ShowHeader();
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['thes_main_section']);
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
			$SectionArr = array();
			$SectionArr['where'] = array('id',$PowerBB->_GET['id']);

			$SectionInfov = $PowerBB->core->GetInfo($SectionArr,'section');

			$SectionInfov['linkvisitor'] +=1;
	        $visitor = $SectionInfov['linkvisitor'];
			$Sectionid = $PowerBB->_GET['id'];
		    $update_visitor_Section = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['section'] . " SET linkvisitor= '$visitor' WHERE id='$Sectionid'");

     		// Update section's cache
     		$UpdateArr 				= 	array();
     		$UpdateArr['parent'] 	= 	$SectionInfov['parent'];

     		$update_cache = $PowerBB->section->UpdateSectionsCache($UpdateArr);


		    $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Please_wait_will_be_referred_to'] . $this->Section['linksite']);
			$PowerBB->functions->redirect($this->Section['linksite'],3);
			$PowerBB->functions->stop();
		}

		// hmmmm , this section protect by password so request the password
		if (!$check)
		{
			if (!empty($this->Section['section_password'])
				and !$PowerBB->_CONF['group_info']['admincp_allow'])
			{
     			if (empty($PowerBB->_GET['password']))
        		{
      				$PowerBB->template->display('forum_password');
      				$PowerBB->functions->stop();
     			}
     			else
     			{
     				$PassArr = array();
     				$PassArr['id'] 			= 	$this->Section['id'];
     				$PassArr['password'] 	= 	base64_decode($PowerBB->_GET['password']);

     				$IsTruePassword = $PowerBB->section->CheckPassword($PassArr);

     				if (!$IsTruePassword)
     				{
     					$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['The_password_notTrue']);
     				}

     				$PowerBB->_CONF['template']['password'] = '&amp;password=' . $PowerBB->_GET['password'];
     			}
     		}
     	}

     	if ($PowerBB->_CONF['member_permission'])
     	{
     		$UpdateOnline 			= 	array();
			$UpdateOnline['field']	=	array();

			$UpdateOnline['field']['user_location'] 	= $PowerBB->_CONF['template']['_CONF']['lang']['Seen_the'] .	' <a href="index.php?page=forum&show=1&amp;id=' . $this->Section['id'] . '">' . $PowerBB->functions->CleanVariable($this->Section['title'],'sql') . '</a>';
			$UpdateOnline['field']['section_id'] 	    =  $this->Section['id'];
			$UpdateOnline['where']						=	array('username',$PowerBB->_CONF['member_row']['username']);

			$update = $PowerBB->core->Update($UpdateOnline,'online');
     	}
       else
     	{
     		$UpdateOnline 			= 	array();
			$UpdateOnline['field']	=	array();

			$UpdateOnline['field']['user_location'] 	= $PowerBB->_CONF['template']['_CONF']['lang']['Seen_the'] .	' <a href="index.php?page=forum&show=1&amp;id=' . $this->Section['id'] . '">' . $PowerBB->functions->CleanVariable($this->Section['title'],'sql') . '</a>';
			$UpdateOnline['field']['section_id'] 	    =  $this->Section['id'];
			$UpdateOnline['where']						=	array('user_ip',$PowerBB->_CONF['ip']);

			$update = $PowerBB->core->Update($UpdateOnline,'online');
     	}

    		unset($UpdateArr);
     		unset($update_visitor_Section);

			$UpdateArr = $PowerBB->DB->sql_free_result($UpdateArr);
			$update_visitor_Section = $PowerBB->DB->sql_free_result($update_visitor_Section);

			unset($UpdateOnline);
			$UpdateOnline = $PowerBB->DB->sql_free_result($UpdateOnline);

			unset($PassArr);
			$PassArr = $PowerBB->DB->sql_free_result($PassArr);
	}


	function _GetSubSection()
	{
		global $PowerBB;
   		@include("cache/forums_cache/forums_cache_".$this->Section['id'].".php");
		if (!empty($forums_cache))
		{		      @include("cache/sectiongroup_cache/sectiongroup_cache_".$this->Section['id'].".php");
	          $forums = json_decode(base64_decode($sectiongroup_cache), true);
	          $PowerBB->_CONF['template']['foreach']['forums_list'] = array();

			foreach ($forums as $forum)
			{
				if (is_array($forum['groups'][$PowerBB->_CONF['group_info']['id']]))
				{
					if ($forum['groups'][$PowerBB->_CONF['group_info']['id']]['view_section'])
					{
						$forum_last_time1 = $forum['last_date'];
						$forum['last_post_date'] = $forum['last_time'];
						$forum['last_date'] = $PowerBB->functions->_date($forum_last_time1);
						$forum['simple_title'] = $forum['title'];
						$forum['last_writer'] = '<a href="index.php?page=profile&amp;show=1&amp;username=' . $forum['last_writer'] . '">' . $forum['last_writer'] . '</a> ';
						$forum['last_subject'] = $PowerBB->Powerparse->censor_words($forum['last_subject']);
						$forum['last_subject'] =  $PowerBB->functions->words_count($forum['last_subject'],'60');


	                    if ($PowerBB->_CONF['info_row']['no_sub'])
					    {
							$forum['is_sub'] 	= 	0;
							$forum['sub']		=	'';

			               @include("cache/forums_cache/forums_cache_".$forum['id'].".php");
							if (!empty($forum['forums_cache']))
							{
								$subs = json_decode(base64_decode($forums_cache), true);

								if (is_array($subs))
								{
	                                foreach($subs as $sub)
									{
	                                        $forum_subject_num = $forum['subject_num']+ $sub['subject_num'];
	                                        $forum_reply_num =  $forum['reply_num']+ $sub['reply_num'];
	                                        $forum['subject_num']   = $forum_subject_num;
	                                        $forum['reply_num']   = $forum_reply_num;
	                                           $forum_last_subjectid = $forum['last_subjectid'];
	                                           $sub_last_subjectid = $sub['last_subjectid'];
	                                           $last_time = $sub['last_time'];

										if (is_array($sub['groups'][$PowerBB->_CONF['group_info']['id']]))
										{
											if ($sub['groups'][$PowerBB->_CONF['group_info']['id']]['view_section'])
											{
                                                        $forum_url = "index.php?page=forum_archive&amp;show=1&amp;id=";
														$forum['sub'] .= '<a href="'.$PowerBB->functions->rewriterule($forum_url).$sub['id'].'"><img border="0" alt="" src="' . $PowerBB->_CONF['template']['image_path'] . '/address_bar_start.gif" />'.$sub['title'].'</a>';

											}
										}
									}
								}


							}
	                     }
					   //////////


						$PowerBB->_CONF['template']['foreach']['forums_list'][$forum['id'] . '_f'] = $forum;
					}
				} // end if is_array
			} // end foreach ($forums)

			$PowerBB->template->assign('SHOW_SUB_SECTIONS',true);
		}
		else
		{
			$PowerBB->template->assign('SHOW_SUB_SECTIONS',false);
		}

	   $PowerBB->template->assign('Section_Title',$this->Section['title']);
       $PowerBB->template->assign('Section_Id',$this->Section['id']);
	}

	  /**
	 * /** Show the subject order by (reply_number - visitor - rating)**/
	function _StartorderBy()
	{

	global $PowerBB;


		if (isset($PowerBB->_GET['sort']))
		{
       	if ($PowerBB->_GET['sort'] != 'desc'
       	and $PowerBB->_GET['sort'] != 'asc'
       	and $PowerBB->_GET['sort'] != 'reply_number'
       	and $PowerBB->_GET['sort'] != 'visitor'
       	and $PowerBB->_GET['sort'] != 'rating'
       	and $PowerBB->_GET['sort'] != 'writer'
       	and $PowerBB->_GET['sort'] != 'write_time'
       	and $PowerBB->_GET['sort'] != 'title')
		{

			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);

	   // $PowerBB->functions->redirect('index.php?page=forum&amp;show=1&amp;id=' . $PowerBB->_GET['id']);

		}
      }
		/**
		 * Ok , are you ready to get subjects list ? :)
		 */

		$TotalArr 				= 	array();
		$TotalArr['get_from'] 	= 	'db';
		$TotalArr['where'] 		= 	array('section',$this->Section['id']);

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
		$SubjectArr['where'][2]['name'] 	= 	'delete_topic';
		$SubjectArr['where'][2]['oper'] 	= 	'<>';
		$SubjectArr['where'][2]['value'] 	= 	'1';

		$SubjectArr['where'][3] 			= 	array();
		$SubjectArr['where'][3]['con']		=	'AND';
		$SubjectArr['where'][3]['name'] 	= 	'review_subject';
		$SubjectArr['where'][3]['oper'] 	= 	'<>';
		$SubjectArr['where'][3]['value'] 	= 	'1';

		if ($this->Section['hide_subject']
			and !$PowerBB->_CONF['group_info']['admincp_allow'])
		{
			$SubjectArr['where'][4] 			= 	array();
			$SubjectArr['where'][4]['con'] 		= 	'AND';
			$SubjectArr['where'][4]['name'] 	= 	'writer';
			$SubjectArr['where'][4]['oper'] 	= 	'=';
			$SubjectArr['where'][4]['value'] 	= 	$PowerBB->_CONF['member_row']['username'];
		}

		$SubjectArr['order'] = array();

		if ($PowerBB->_GET['sort'] == 'reply_number')
		{
			$SubjectArr['order']['field'] 	= 	'reply_number';
			$SubjectArr['order']['type'] 	= 	'DESC';
		}
		elseif ($PowerBB->_GET['sort'] == 'visitor')
		{
			$SubjectArr['order']['field'] 	= 	'visitor';
			$SubjectArr['order']['type'] 	= 	'DESC';
		}
		elseif ($PowerBB->_GET['sort'] == 'rating')
		{
			$SubjectArr['order']['field'] 	= 	'rating';
			$SubjectArr['order']['type'] 	= 	'DESC';
		}
		elseif ($PowerBB->_GET['sort'] == 'writer')
		{
			$SubjectArr['order']['field'] 	= 	'writer';
			$SubjectArr['order']['type'] 	= 	'DESC';
		}
		elseif ($PowerBB->_GET['sort'] == 'title')
		{
			$SubjectArr['order']['field'] 	= 	'title';
			$SubjectArr['order']['type'] 	= 	'DESC';
		}
		else
		{
			$SubjectArr['order']['field'] 	= 	'write_time';
			$SubjectArr['order']['type'] 	= 	'DESC';
		}


		$PowerBB->_GET['count'] = (!isset($PowerBB->_GET['count'])) ? 0 : $PowerBB->_GET['count'];
		$PowerBB->_GET['count'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['count'],'intval');

		$SubjectArr['proc'] 						= 	array();
		// Ok Mr.XSS go to hell !
		$SubjectArr['proc']['*'] 					= 	array('method'=>'clean','param'=>'html');
		$SubjectArr['proc']['native_write_time'] 	= 	array('method'=>'date','store'=>'write_date','type'=>$PowerBB->_CONF['info_row']['timesystem']);
		$SubjectArr['proc']['write_time'] 			= 	array('method'=>'date','store'=>'reply_date','type'=>$PowerBB->_CONF['info_row']['timesystem']);



		// Pager setup
		$SubjectArr['pager'] 				= 	array();
		$SubjectArr['pager']['total']		= 	$PowerBB->core->GetNumber($TotalArr,'subject');
		$SubjectArr['pager']['perpage'] 	= 	150;
		$SubjectArr['pager']['count'] 		= 	$PowerBB->_GET['count'];
		$SubjectArr['pager']['location'] 	= 	'index.php?page=forum&amp;show=1&amp;id=' . $this->Section['id'];
		$SubjectArr['pager']['var'] 		= 	'count';

		$PowerBB->_CONF['template']['while']['subject_list'] = $PowerBB->core->GetList($SubjectArr,'subject');


				//////////

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

		$StickSubjectArr['where'][2] 			= 	array();
		$StickSubjectArr['where'][2]['con']		=	'AND';
		$StickSubjectArr['where'][2]['name'] 	= 	'delete_topic';
		$StickSubjectArr['where'][2]['oper'] 	= 	'<>';
		$StickSubjectArr['where'][2]['value'] 	= 	'1';

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

		//////////

		// Get the list of subjects that need review

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

			$ReviewSubjectArr['where'][2] 			= 	array();
			$ReviewSubjectArr['where'][2]['con']	=	'AND';
			$ReviewSubjectArr['where'][2]['name'] 	= 	'delete_topic';
			$ReviewSubjectArr['where'][2]['oper'] 	= 	'<>';
			$ReviewSubjectArr['where'][2]['value'] 	= 	'1';

			$ReviewSubjectArr['order'] 				= 	array();
			$ReviewSubjectArr['order']['field'] 	= 	'write_time';
			$ReviewSubjectArr['order']['type'] 		= 	'DESC';

			$ReviewSubjectArr['proc'] 						= 	array();
			// Ok Mr.XSS go to hell !
			$ReviewSubjectArr['proc']['*'] 					= 	array('method'=>'clean','param'=>'html');
			$ReviewSubjectArr['proc']['native_write_time'] 	= 	array('method'=>'date','store'=>'write_date','type'=>$PowerBB->_CONF['info_row']['timesystem']);
			$ReviewSubjectArr['proc']['write_time'] 		= 	array('method'=>'date','store'=>'reply_date','type'=>$PowerBB->_CONF['info_row']['timesystem']);

			$PowerBB->_CONF['template']['while']['review_subject_list'] = $PowerBB->core->GetList($ReviewSubjectArr,'subject');

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

		//////////

		if ($PowerBB->core->GetNumber($TotalArr,'subject') > $PowerBB->_CONF['info_row']['subject_perpage'])
		{
		$PowerBB->template->assign('pager',$PowerBB->pager->show());
		}

		$PowerBB->template->assign('section_id',$this->Section['id']);

	}

	  	/**
	 * order subject in forum
	 */
	function _Startorder()
	{
		global $PowerBB;

		if (isset($PowerBB->_GET['sort']))
		{
       	if ($PowerBB->_GET['sort'] != 'desc'
       	and $PowerBB->_GET['sort'] != 'asc'
       	and $PowerBB->_GET['sort'] != 'reply_number'
       	and $PowerBB->_GET['sort'] != 'visitor'
       	and $PowerBB->_GET['sort'] != 'rating'
       	and $PowerBB->_GET['sort'] != 'writer'
       	and $PowerBB->_GET['sort'] != 'write_time'
       	and $PowerBB->_GET['sort'] != 'title')
		{

			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);

	   // $PowerBB->functions->redirect('index.php?page=forum&amp;show=1&amp;id=' . $PowerBB->_GET['id']);

		}
      }

		/**
		 * Ok , are you ready to get subjects list ? :)
		 */
		$TotalArr 				= 	array();
		$TotalArr['get_from'] 	= 	'db';
		$TotalArr['where'] 		= 	array('section',$this->Section['id']);

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
		$SubjectArr['where'][2]['name'] 	= 	'delete_topic';
		$SubjectArr['where'][2]['oper'] 	= 	'<>';
		$SubjectArr['where'][2]['value'] 	= 	'1';

		$SubjectArr['where'][3] 			= 	array();
		$SubjectArr['where'][3]['con']		=	'AND';
		$SubjectArr['where'][3]['name'] 	= 	'review_subject';
		$SubjectArr['where'][3]['oper'] 	= 	'<>';
		$SubjectArr['where'][3]['value'] 	= 	'1';

		if ($this->Section['hide_subject']
			and !$PowerBB->_CONF['group_info']['admincp_allow'])
		{
			$SubjectArr['where'][4] 			= 	array();
			$SubjectArr['where'][4]['con'] 		= 	'AND';
			$SubjectArr['where'][4]['name'] 	= 	'writer';
			$SubjectArr['where'][4]['oper'] 	= 	'=';
			$SubjectArr['where'][4]['value'] 	= 	$PowerBB->_CONF['member_row']['username'];
		}

		$SubjectArr['order'] = array();

		if ($this->Section['subject_order'] == 2)
		{
			$SubjectArr['order']['field'] 	= 	'id';
			$SubjectArr['order']['type'] 	= 	$PowerBB->_GET['sort'];
		}
		elseif ($this->Section['subject_order'] == 3)
		{
			$SubjectArr['order']['field'] 	= 	'id';
			$SubjectArr['order']['type'] 	= 	'ASC';
		}
		else
		{
			$SubjectArr['order']['field'] 	= 	'write_time';
			$SubjectArr['order']['type'] 	= 	$PowerBB->_GET['sort'];
		}

		$PowerBB->_GET['count'] = (!isset($PowerBB->_GET['count'])) ? 0 : $PowerBB->_GET['count'];
		$PowerBB->_GET['count'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['count'],'intval');

		$SubjectArr['proc'] 						= 	array();
		// Ok Mr.XSS go to hell !
		$SubjectArr['proc']['*'] 					= 	array('method'=>'clean','param'=>'html');
		$SubjectArr['proc']['native_write_time'] 	= 	array('method'=>'date','store'=>'write_date','type'=>$PowerBB->_CONF['info_row']['timesystem']);
		$SubjectArr['proc']['write_time'] 			= 	array('method'=>'date','store'=>'reply_date','type'=>$PowerBB->_CONF['info_row']['timesystem']);



		// Pager setup
		$SubjectArr['pager'] 				= 	array();
		$SubjectArr['pager']['total']		= 	$PowerBB->core->GetNumber($TotalArr,'subject');
		$SubjectArr['pager']['perpage'] 	= 	$PowerBB->_CONF['info_row']['subject_perpage'];
		$SubjectArr['pager']['count'] 		= 	$PowerBB->_GET['count'];
		$SubjectArr['pager']['location'] 	= 	'index.php?page=forum&amp;show=1&amp;id=' . $this->Section['id'];
		$SubjectArr['pager']['var'] 		= 	'count';

		$PowerBB->_CONF['template']['while']['subject_list'] = $PowerBB->core->GetList($SubjectArr,'subject');

				//////////

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

		$StickSubjectArr['where'][2] 			= 	array();
		$StickSubjectArr['where'][2]['con']		=	'AND';
		$StickSubjectArr['where'][2]['name'] 	= 	'delete_topic';
		$StickSubjectArr['where'][2]['oper'] 	= 	'<>';
		$StickSubjectArr['where'][2]['value'] 	= 	'1';

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

		//////////

		// Get the list of subjects that need review

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

			$ReviewSubjectArr['where'][2] 			= 	array();
			$ReviewSubjectArr['where'][2]['con']	=	'AND';
			$ReviewSubjectArr['where'][2]['name'] 	= 	'delete_topic';
			$ReviewSubjectArr['where'][2]['oper'] 	= 	'<>';
			$ReviewSubjectArr['where'][2]['value'] 	= 	'1';

			$ReviewSubjectArr['order'] 				= 	array();
			$ReviewSubjectArr['order']['field'] 	= 	'write_time';
			$ReviewSubjectArr['order']['type'] 		= 	'DESC';

			$ReviewSubjectArr['proc'] 						= 	array();
			// Ok Mr.XSS go to hell !
			$ReviewSubjectArr['proc']['*'] 					= 	array('method'=>'clean','param'=>'html');
			$ReviewSubjectArr['proc']['native_write_time'] 	= 	array('method'=>'date','store'=>'write_date','type'=>$PowerBB->_CONF['info_row']['timesystem']);
			$ReviewSubjectArr['proc']['write_time'] 		= 	array('method'=>'date','store'=>'reply_date','type'=>$PowerBB->_CONF['info_row']['timesystem']);

			$PowerBB->_CONF['template']['while']['review_subject_list'] = $PowerBB->core->GetList($ReviewSubjectArr,'subject');

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

		//////////

		if ($PowerBB->core->GetNumber($TotalArr,'subject') > $PowerBB->_CONF['info_row']['subject_perpage'])
		{
		$PowerBB->template->assign('pager',$PowerBB->pager->show());
		}

		$PowerBB->template->assign('section_id',$this->Section['id']);

	}

	function _GetSubjectList()
	{
		global $PowerBB;


		$PowerBB->_GET['count'] = (!isset($PowerBB->_GET['count'])) ? 0 : $PowerBB->_GET['count'];
		$PowerBB->_GET['count'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['count'],'intval');

		/** Show the subject order by (oldr - new )**/
		if($PowerBB->_GET['order'])
		{
			$this->_Startorder();
		}
		/** Show the subject order by (reply_number - visitor - rating)**/
        elseif($PowerBB->_GET['orderby'])
        {
         $this->_StartorderBy();
        }
        else
        {

		/**
		 * Ok , are you ready to get subjects list ? :)
		 */
		$TotalArr 				= 	array();
		$TotalArr['get_from'] 	= 	'db';
		$TotalArr['where'] 		= 	array('section',$this->Section['id']);

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
			and !$PowerBB->_CONF['group_info']['admincp_allow'])
		{
			$SubjectArr['where'][3] 			= 	array();
			$SubjectArr['where'][3]['con'] 		= 	'AND';
			$SubjectArr['where'][3]['name'] 	= 	'writer';
			$SubjectArr['where'][3]['oper'] 	= 	'=';
			$SubjectArr['where'][3]['value'] 	= 	$PowerBB->_CONF['member_row']['username'];
		}

		$SubjectArr['order'] = array();

		if ($this->Section['subject_order'] == 2)
		{
			$SubjectArr['order']['field'] 	= 	'id';
			$SubjectArr['order']['type'] 	= 	'DESC';
		}
		elseif ($this->Section['subject_order'] == 3)
		{
			$SubjectArr['order']['field'] 	= 	'id';
			$SubjectArr['order']['type'] 	= 	'ASC';
		}
		else
		{
			$SubjectArr['order']['field'] 	= 	'write_time';
			$SubjectArr['order']['type'] 	= 	'DESC';
		}


		$SubjectArr['proc'] 						= 	array();
		// Ok Mr.XSS go to hell !
		$SubjectArr['proc']['*'] 					= 	array('method'=>'clean','param'=>'html');
		$SubjectArr['proc']['native_write_time'] 	= 	array('method'=>'date','store'=>'write_date','type'=>$PowerBB->_CONF['info_row']['timesystem']);
		$SubjectArr['proc']['write_time'] 			= 	array('method'=>'date','store'=>'reply_date','type'=>$PowerBB->_CONF['info_row']['timesystem']);



		// Pager setup
		$SubjectArr['pager'] 				= 	array();
		$SubjectArr['pager']['total']		= 	$PowerBB->core->GetNumber($TotalArr,'subject');
		$SubjectArr['pager']['perpage'] 	= 	$PowerBB->_CONF['info_row']['subject_perpage'];
		$SubjectArr['pager']['count'] 		= 	$PowerBB->_GET['count'];
		$SubjectArr['pager']['location'] 	= 	'index.php?page=forum&amp;show=1&amp;id=' . $this->Section['id'];
		$SubjectArr['pager']['var'] 		= 	'count';

		$PowerBB->_CONF['template']['while']['subject_list'] = $PowerBB->core->GetList($SubjectArr,'subject');



      /* Show part of the topic for a pause on the link
			$sectionid = $this->Section['id'];
			$SubjectTextArr = $PowerBB->DB->sql_query("SELECT  *   FROM " . $PowerBB->table['subject'] . " WHERE section = '$sectionid' ");

       while ($getstyleText = $PowerBB->DB->sql_fetch_array($SubjectTextArr))
       {

        $PowerBB->template->assign('subject_text',$getstyleText['text']);

       }

       */

		//////////

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

		$StickSubjectArr['where'][2] 			= 	array();
		$StickSubjectArr['where'][2]['con']		=	'AND';
		$StickSubjectArr['where'][2]['name'] 	= 	'review_subject';
		$StickSubjectArr['where'][2]['oper'] 	= 	'<>';
		$StickSubjectArr['where'][2]['value'] 	= 	'1';
        if (!$PowerBB->functions->ModeratorCheck($this->Section['id']))
   		{
		$StickSubjectArr['where'][3] 			= 	array();
		$StickSubjectArr['where'][3]['con']		=	'AND';
		$StickSubjectArr['where'][3]['name'] 	= 	'delete_topic';
		$StickSubjectArr['where'][3]['oper'] 	= 	'<>';
		$StickSubjectArr['where'][3]['value'] 	= 	'1';
		}

		if ($this->Section['hide_subject']
		and !$PowerBB->_CONF['group_info']['admincp_allow'])
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

		//////////

		// Get the list of subjects that need review

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

			$ReviewSubjectArr['where'][2] 			= 	array();
			$ReviewSubjectArr['where'][2]['con']	=	'AND';
			$ReviewSubjectArr['where'][2]['name'] 	= 	'delete_topic';
			$ReviewSubjectArr['where'][2]['oper'] 	= 	'<>';
			$ReviewSubjectArr['where'][2]['value'] 	= 	'1';

				if ($this->Section['hide_subject']
				and !$PowerBB->_CONF['group_info']['admincp_allow'])
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

			$PowerBB->_CONF['template']['while']['review_subject_list'] = $PowerBB->core->GetList($ReviewSubjectArr,'subject');

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

		//////////

		if ($PowerBB->core->GetNumber($TotalArr,'subject') > $PowerBB->_CONF['info_row']['subject_perpage'])
		{
		$PowerBB->template->assign('pager',$PowerBB->pager->show());
		}

		$PowerBB->template->assign('section_id',$this->Section['id']);
		}

			unset($SubjectArr);
			$SubjectArr = $PowerBB->DB->sql_free_result($SubjectArr);

			unset($StickSubjectArr);
			$StickSubjectArr = $PowerBB->DB->sql_free_result($StickSubjectArr);

			 unset($ReviewSubjectArr);
			$ReviewSubjectArr = $PowerBB->DB->sql_free_result($ReviewSubjectArr);

			unset($TotalArr);
			$TotalArr = $PowerBB->DB->sql_free_result($TotalArr);
	}

    /**
	 * Get the results of search one section
	 */

	function _SearchSection()
	{
		global $PowerBB;

         if (!$PowerBB->_GET['keyword'])
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

	function _CallTemplate()
	{
		global $PowerBB;

    	 $PowerBB->template->display('archive_forum');
		 $PowerBB->template->display('archive_footer');
   			$SecArr = $PowerBB->DB->sql_free_result($SecArr);
			unset($update_visitor_Section);
			unset($subject_nm);
			unset($UpdateOnline);
			unset($SecArr);
			unset($MemberNumberArr);
			unset($forumy);
			unset($groups);

	}


}

?>