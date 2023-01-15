<?php
(!defined('IN_PowerBB')) ? die() : '';

define('CLASS_NAME','PowerBBManagementMOD');
include('common.php');
class PowerBBManagementMOD
{
	function run()
	{
		global $PowerBB;

		$PowerBB->_GET['section'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['section'],'intval');
		$PowerBB->_GET['subject_id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['subject_id'],'intval');


			if ($PowerBB->_GET['subject'])
			{
				$this->_Subject();
			}
			elseif ($PowerBB->_GET['move'])
			{
				$this->_MoveStart();
			}
			elseif ($PowerBB->_GET['subject_edit'])
			{
				$this->_SubjectEditStart();
			}
			elseif ($PowerBB->_GET['repeat'])
			{
				$this->_SubjectRepeatStart();
			}
			elseif ($PowerBB->_GET['close'])
			{
				$this->_CloseStart();
			}
			elseif ($PowerBB->_GET['delete'])
			{
				$this->_DeleteStart();
			}
			elseif ($PowerBB->_GET['reply'])
			{
	         	$PowerBB->functions->ShowHeader();
				$this->_Reply();
		        $PowerBB->functions->GetFooter();
			}
			elseif ($PowerBB->_GET['reply_edit'])
			{
				$this->_ReplyEditStart();
			}
			elseif ($PowerBB->_GET['multimod'])
			{
				$this->_TopicModStart();
			}
			elseif ($PowerBB->_GET['startmerge'])
			{
				$this->_MergeStart();
			}
			elseif ($PowerBB->_GET['hooks'])
			{
				if ($PowerBB->_GET['mainhooks'])
				{
					$this->_ManagementMainHooks();
				}
               elseif ($PowerBB->_GET['update'])
				{
					$this->_ManagementUpdateHooks();
				}
			}

	}

	function _Subject()
	{
		global $PowerBB;

  	    $SubjectArr = array();
		$SubjectArr['where'] = array('id',$PowerBB->_GET['subject_id']);

		$SubjectInfo = $PowerBB->core->GetInfo($SubjectArr,'subject');
        $SubjectInfo['title']    =   $PowerBB->Powerparse->censor_words($SubjectInfo['title']);

		$MemberArr 				= 	array();
		$MemberArr['where']		=	array('username',$SubjectInfo['writer']);

		$WriterInfo = $PowerBB->core->GetInfo($MemberArr,'member');


	    if ($PowerBB->functions->ModeratorCheck($PowerBB->_GET['section'])
	    or $PowerBB->_CONF['member_row']['username'] == $WriterInfo['username'])
		{

		if ($PowerBB->_GET['operator'] == 'stick')
		{
			$this->__Stick();
		}
		elseif ($PowerBB->_GET['operator'] == 'unstick')
		{
			$this->__UnStick();
		}
		elseif ($PowerBB->_GET['operator'] == 'close')
		{
	        $PowerBB->functions->ShowHeader();
			$this->__Close();
        	$PowerBB->functions->GetFooter();
		}
		elseif ($PowerBB->_GET['operator'] == 'open')
		{
			$this->__Open();
		}
		elseif ($PowerBB->_GET['operator'] == 'delete')
		{
	        $PowerBB->functions->ShowHeader();
			$this->_DeleteIndex();
        	$PowerBB->functions->GetFooter();
		}
		elseif ($PowerBB->_GET['operator'] == 'delete_start')
		{
			$this->_DeleteStart();
		}
		elseif ($PowerBB->_GET['operator'] == 'move')
		{
	        $PowerBB->functions->ShowHeader();
			$this->__MoveIndex();
        	$PowerBB->functions->GetFooter();
		}
		elseif ($PowerBB->_GET['operator'] == 'edit')
		{
	        $PowerBB->functions->ShowHeader();
			$this->__SubjectEdit();
        	$PowerBB->functions->GetFooter();
        }
		elseif ($PowerBB->_GET['operator'] == 'repeated')
		{
	        $PowerBB->functions->ShowHeader();
			$this->__SubjectRepeat();
        	$PowerBB->functions->GetFooter();
		}
		elseif ($PowerBB->_GET['operator'] == 'up')
		{
			$this->__UpStart();
		}
		elseif ($PowerBB->_GET['operator'] == 'down')
		{
			$this->__DownStart();
		}
		elseif ($PowerBB->_GET['operator'] == 'unreview_subject')
		{
			$this->__UnReviewSubject();
		}
		elseif ($PowerBB->_GET['operator'] == 'review_subject')
		{
			$this->__ReviewSubject();
		}
		elseif ($PowerBB->_GET['operator'] == 'special')
		{
			$this->_SpecialStart();
		}
		elseif ($PowerBB->_GET['operator'] == 'nospecial')
		{
			$this->_NospecialStart();
		}
		elseif ($PowerBB->_GET['operator'] == 'merge')
		{
	        $PowerBB->functions->ShowHeader();
			$this->_MergeIndex();
        	$PowerBB->functions->GetFooter();
		}
		elseif ($PowerBB->_GET['operator'] == 'tools_thread')
		{
			$this->_ToolsThread();
		}


		}
		else
		{
			$PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['not_allowed_access']);
		}

	}

	function __Stick()
	{
		global $PowerBB;

		if ($PowerBB->_CONF['group_info']['group_mod'] == '1')
		{
			if ($PowerBB->_CONF['group_info']['stick_subject'] == '0')
			{
			$PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
			}
		}

		$PowerBB->_GET['subject_id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['subject_id'],'intval');

		if (empty($PowerBB->_GET['subject_id']))
		{
			$PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}

		$UpdateArr 			= array();
		$UpdateArr['where'] = array('id',$PowerBB->_GET['subject_id']);

		$update = $PowerBB->subject->StickSubject($UpdateArr);

		if ($update)
		{
          	// INSERT moderators Action
			$EditAction				=	array();
		    $EditAction['where'] 	= 	array('id',$PowerBB->_GET['subject_id']);

			$action = $PowerBB->core->GetInfo($EditAction,'subject');

		    $subject_title = $action['title'];
		    $time=time()+$PowerBB->_CONF['info_row']['timestamp'];

	        $SmLogsArr 			= 	array();
			$SmLogsArr['field']	=	array();

			$SmLogsArr['field']['username'] 	= 	$PowerBB->_CONF['member_row']['username'];
			$SmLogsArr['field']['edit_action'] 	= 	$PowerBB->_CONF['template']['_CONF']['lang']['Sticky_Topic'];
			$SmLogsArr['field']['subject_title']= 	$PowerBB->functions->CleanVariable($subject_title,'sql');
			$SmLogsArr['field']['subject_id'] 	= 	$PowerBB->_GET['subject_id'];
			$SmLogsArr['field']['edit_date'] 	= 	date("d/m/Y", $time);

			$insert = $PowerBB->core->Insert($SmLogsArr,'supermemberlogs');

			//$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Was_stick_subject']);
			$PowerBB->functions->header_redirect('index.php?page=topic&amp;show=1&amp;id=' . $PowerBB->_GET['subject_id']);
		}
	}

	function __UnStick()
	{
		global $PowerBB;

		if ($PowerBB->_CONF['group_info']['group_mod'] == '1')
		{
			if ($PowerBB->_CONF['group_info']['unstick_subject'] == '0')
			{
			$PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
			}
		}

		$PowerBB->_GET['subject_id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['subject_id'],'intval');

		if (empty($PowerBB->_GET['subject_id']))
		{
			$PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}

		$UpdateArr 			= array();
		$UpdateArr['where'] = array('id',$PowerBB->_GET['subject_id']);

		$update = $PowerBB->subject->UnstickSubject($UpdateArr);

		if ($update)
		{
			// INSERT moderators Action
			$EditAction				=	array();
		    $EditAction['where'] 	= 	array('id',$PowerBB->_GET['subject_id']);

			$action = $PowerBB->core->GetInfo($EditAction,'subject');

		    $subject_title = $action['title'];
		    $time=time()+$PowerBB->_CONF['info_row']['timestamp'];

	        $SmLogsArr 			= 	array();
			$SmLogsArr['field']	=	array();

			$SmLogsArr['field']['username'] 	= 	$PowerBB->_CONF['member_row']['username'];
			$SmLogsArr['field']['edit_action'] 	= 	$PowerBB->_CONF['template']['_CONF']['lang']['unstick_Subject'];
			$SmLogsArr['field']['subject_title']= 	$PowerBB->functions->CleanVariable($subject_title,'sql');
			$SmLogsArr['field']['subject_id'] 	= 	$PowerBB->_GET['subject_id'];
			$SmLogsArr['field']['edit_date'] 	= 	date("d/m/Y", $time);

			$insert = $PowerBB->core->Insert($SmLogsArr,'supermemberlogs');

			//$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Was_Cancel_stick_subject']);
			$PowerBB->functions->header_redirect('index.php?page=topic&amp;show=1&amp;id=' . $PowerBB->_GET['subject_id']);
		}
	}

	function __Close()
	{
		global $PowerBB;

		if ($PowerBB->_CONF['group_info']['group_mod'] == '1')
		{
			if ($PowerBB->_CONF['group_info']['close_subject'] == '0')
			{
			 $PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
			}
		}
			$PowerBB->_GET['subject_id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['subject_id'],'intval');

			if (empty($PowerBB->_GET['subject_id']))
			{
				$PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
			}

			$SubjectInfoArr				=	array();
		    $SubjectInfoArr['where'] 	= 	array('id',$PowerBB->_GET['subject_id']);

			$SubjectInfo = $PowerBB->subject->GetSubjectInfo($SubjectInfoArr);
			$PowerBB->template->assign('SubjectInfo',$SubjectInfo);

			$PowerBB->template->assign('subject',$PowerBB->_GET['subject_id']);

			$PowerBB->template->display('subject_close_index');


	}

	function __Open()
	{
		global $PowerBB;

		$PowerBB->_GET['subject_id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['subject_id'],'intval');

		if (empty($PowerBB->_GET['subject_id']))
		{
			$PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}

		$UpdateArr 			= array();
		$UpdateArr['where'] = array('id',$PowerBB->_GET['subject_id']);

		$update = $PowerBB->subject->OpenSubject($UpdateArr);

		if ($update)
		{
		          	// INSERT moderators Action
			$EditAction				=	array();
		    $EditAction['where'] 	= 	array('id',$PowerBB->_GET['subject_id']);

			$action = $PowerBB->core->GetInfo($EditAction,'subject');

		    $subject_title = $action['title'];
		    $time=time()+$PowerBB->_CONF['info_row']['timestamp'];

	        $SmLogsArr 			= 	array();
			$SmLogsArr['field']	=	array();

			$SmLogsArr['field']['username'] 	= 	$PowerBB->_CONF['member_row']['username'];
			$SmLogsArr['field']['edit_action'] 	= 	$PowerBB->_CONF['template']['_CONF']['lang']['open_Subject'];
			$SmLogsArr['field']['subject_title']= 	$PowerBB->functions->CleanVariable($subject_title,'sql');
			$SmLogsArr['field']['subject_id'] 	= 	$PowerBB->_GET['subject_id'];
			$SmLogsArr['field']['edit_date'] 	= 	date("d/m/Y", $time);

			$insert = $PowerBB->core->Insert($SmLogsArr,'supermemberlogs');

			//$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Was_Opin_subject']);
			$PowerBB->functions->header_redirect('index.php?page=topic&amp;show=1&amp;id=' . $PowerBB->_GET['subject_id']);
		}
	}

	function __SubjectDelete()
	{
		global $PowerBB;

		$PowerBB->_GET['subject_id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['subject_id'],'intval');

		if (empty($PowerBB->_GET['subject_id']))
		{
			$PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}
		$UpdateArr 			= array();
		$UpdateArr['where'] = array('id',$PowerBB->_GET['subject_id']);

		$update = $PowerBB->subject->MoveSubjectToTrash($UpdateArr);

		if ($update)
		{

			//$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Was_Trasht']);
			$PowerBB->functions->header_redirect('index.php?page=forum&show=1&id=' . $PowerBB->_GET['section']);
		}
	}

	function __MoveIndex()
	{
		global $PowerBB;

		if ($PowerBB->_CONF['group_info']['group_mod'] == '1')
		{
			if ($PowerBB->_CONF['group_info']['move_subject'] == '0')
			{
			 $PowerBB->functions->ShowHeader();
             $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
			}
		}

		$PowerBB->_GET['subject_id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['subject_id'],'intval');
		$PowerBB->_GET['section'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['section'],'intval');

		if (empty($PowerBB->_GET['subject_id'])
			or empty($PowerBB->_GET['section']))
		{
			$PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}

		$SecArr 						= 	array();
		$SecArr['get_from']				=	'db';

		$SecArr['proc'] 				= 	array();
		$SecArr['proc']['*'] 			= 	array('method'=>'clean','param'=>'html');

		$SecArr['order']				=	array();
		$SecArr['order']['field']		=	'sort';
		$SecArr['order']['type']		=	'ASC';

		$SecArr['where']				=	array();
		$SecArr['where'][0]['name']		= 	'parent';
		$SecArr['where'][0]['oper']		= 	'=';
		$SecArr['where'][0]['value']	= 	'0';

		// Get main sections
		$cats = $PowerBB->core->GetList($SecArr,'section');

 		////////////

		// Loop to read the information of main sections
		foreach ($cats as $cat)
		{
	       // Get the groups information to know view this section or not
         if ($PowerBB->functions->section_group_permission($cat['id'],$PowerBB->_CONF['group_info']['id'],'view_section'))
	      {
             // foreach main sections
			$PowerBB->_CONF['template']['foreach']['forums_list'][$cat['id'] . '_m'] = $cat;

			unset($sectiongroup);

			@include("cache/forums_cache/forums_cache_".$cat['id'].".php");
			if (!empty($forums_cache))
			{
                $forums = json_decode(base64_decode($forums_cache), true);

					foreach ($forums as $forum)
					{
						//////////////////////////
                        if ($PowerBB->functions->section_group_permission($forum['id'],$PowerBB->_CONF['group_info']['id'],'view_section'))
						{
							$forum['is_sub'] 	= 	0;
							$forum['sub']		=	'';

                              @include("cache/forums_cache/forums_cache_".$forum['id'].".php");
                               if (!empty($forums_cache))
	                           {

									$subs = json_decode(base64_decode($forums_cache), true);
	                               foreach ($subs as $sub)
									{
									   if ($forum['id'] == $sub['parent'])
	                                    {
												if (!$forum['is_sub'])
												{
													$forum['is_sub'] = 1;
												}
                                              if ($PowerBB->functions->section_group_permission($sub['id'],$PowerBB->_CONF['group_info']['id'],'view_section'))
											   {
												 $forum['sub'] .= ('<option value="' .$sub['id'] . '" selected="selected">---- '  . $sub['title'] . '</option>');

										        }
										  }




					                         ///////////////

													$forum['is_sub_sub'] 	= 	0;
													$forum['sub_sub']		=	'';

		                                       @include("cache/forums_cache/forums_cache_".$sub['id'].".php");
		                                   if (!empty($forums_cache))
				                           {

												$subs_sub = json_decode(base64_decode($forums_cache), true);
				                               foreach ($subs_sub as $sub_sub)
												{
												   if ($sub['id'] == $sub_sub['parent'])
				                                    {
														   if ($PowerBB->functions->section_group_permission($sub_sub['id'],$PowerBB->_CONF['group_info']['id'],'view_section'))
														   {
																	if (!$forum['is_sub_sub'])
																	{
																		$forum['is_sub_sub'] = 1;
																	}

															 $forum['sub_sub'] .= ('<option value="' .$sub_sub['id'] . '" selected="selected">---- '  . $sub_sub['title'] . '</option>');
													        }
													  }
												 }

										   }
									 }
								}


							$PowerBB->_CONF['template']['foreach']['forums_list'][$forum['id'] . '_f'] = $forum;
							unset($groups);

						}// end view forums
		             } // end foreach ($forums)
			  } // end !empty($forums_cache)
		   } // end view section

				unset($SecArr);
				$SecArr = $PowerBB->DB->sql_free_result($SecArr);
		} // end foreach ($cats)


		//////////


		//////////
    	$SubjectArr 			= 	array();
		$SubjectArr['where'] 	= 	array('id',$PowerBB->_GET['subject_id']);

		$this->SubjectInfo = $PowerBB->core->GetInfo($SubjectArr,'subject');

		$PowerBB->template->assign('section',$PowerBB->_GET['section']);
		$PowerBB->template->assign('subject',$PowerBB->_GET['subject_id']);
		$PowerBB->template->assign('subject_title',$this->SubjectInfo['title']);
        $PowerBB->template->assign('section_id',$this->SubjectInfo['section']);

		// Show Jump List to:)
		$result = $PowerBB->DB->sql_query("SELECT id,title,parent FROM " . $PowerBB->table['section'] . " ORDER BY id DESC");
		$Master = array();
		while ($row = $PowerBB->DB->sql_fetch_array($result)) {
			extract($row);
		    $Master = $PowerBB->core->GetList(array ('id'=>$id,'title'=>"".$title."",'parent'=>$parent."",'parent'=>$parent),'section');
		    $PowerBB->_CONF['template']['foreach']['SecList'] = $PowerBB->core->GetList($Master,'section');
		}

        $PowerBB->template->assign('DoJumpList',$PowerBB->functions->DoJumpList($Master,$url,1));
		unset($Master);
        //
		$PowerBB->template->display('subject_move_index');
	}

	function _MoveStart()
	{
		global $PowerBB;

         if ($PowerBB->_SERVER['REQUEST_METHOD'] != 'POST')
         {
         	 header("Location: index.php");
		     exit;
         }
		// Check the powers group mod  of transfer of the subject
		if ($PowerBB->_CONF['group_info']['group_mod'] == '1')
		{
			if ($PowerBB->_CONF['group_info']['move_subject'] == '0')
			{
			$PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
			}
		}
         // intval to subject_id
		$PowerBB->_GET['subject_id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['subject_id'],'intval');
		$PowerBB->_POST['section'] = $PowerBB->functions->CleanVariable($PowerBB->_POST['section'],'intval');
		$PowerBB->_GET['section'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['section'],'intval');
        ////
		if (empty($PowerBB->_GET['subject_id']))
		{
			$PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}
		if (empty($PowerBB->_POST['section']))
		{
			$PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Section_does_not_exist']);
		}
		     // subject_id
		     $subject_id = $PowerBB->_GET['subject_id'];
            // Get id Both Move from section AND Move to section
			$Move_from_section = $PowerBB->_GET['section'];
			$Move_to_section = $PowerBB->_POST['section'];
            ////
		if (empty($Move_from_section))
		{
			$PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}


        // Get Subject Info
  	    $SubjectArr = array();
		$SubjectArr['where'] = array('id',$PowerBB->_GET['subject_id']);

		$SubjectInfo = $PowerBB->core->GetInfo($SubjectArr,'subject');

     	// Get Section Form Info
     	$SecFormArr 			= 	array();
		$SecFormArr['where'] 	= 	array('id',$Move_from_section);

		$SectionFormInfo = $PowerBB->section->GetSectionInfo($SecFormArr);

     	// Get Section To Info
     	$SecToArr 			= 	array();
		$SecToArr['where'] 	= 	array('id',$Move_to_section );

		$SectionToInfo = $PowerBB->section->GetSectionInfo($SecToArr);


        $Move_replys = $PowerBB->DB->sql_query("SELECT  *   FROM " . $PowerBB->table['reply'] . " WHERE subject_id = '$subject_id' ");
         while ($Moved = $PowerBB->DB->sql_fetch_array($Move_replys))
	         {
				$UpdateArr 				= 	array();
				$UpdateArr['field']		= 	array();

				$UpdateArr['field']['section'] 		= 	$Move_to_section;
				$UpdateArr['where'] 				= 	array('id',$Moved['id']);

				$update = $PowerBB->reply->UpdateReply($UpdateArr);
	         }

		// Change the Section number to Section transferee
		    $UpdateArr 					= 	array();
		    $UpdateArr['field'] 				= 	array();
		    $UpdateArr['field']['section'] 		= 	$Move_to_section;
			$UpdateArr['where'] 				= 	array('id',$PowerBB->_GET['subject_id']);

			$update = $PowerBB->core->Update($UpdateArr,'subject');


		    // INSERT moderators Action

		    $subject_title = $SubjectInfo['title'];
		    $SectionInf = ('<a target="_blank" href="index.php?page=forum&show=1&id=' . $SectionToInfo['id'] . '">' .$SectionToInfo['title'] .'</a>');
		    $time=time()+$PowerBB->_CONF['info_row']['timestamp'];

	        $SmLogsArr 			= 	array();
			$SmLogsArr['field']	=	array();

			$SmLogsArr['field']['username'] 	= 	$PowerBB->_CONF['member_row']['username'];
			$SmLogsArr['field']['edit_action'] 	=  	$PowerBB->_CONF['template']['_CONF']['lang']['mov_Subject_to1'] ." ".$PowerBB->_CONF['template']['_CONF']['lang']['mov_Subject_to2']. "". $SectionInf;
			$SmLogsArr['field']['subject_title']= 	$PowerBB->functions->CleanVariable($subject_title,'sql');
			$SmLogsArr['field']['subject_id'] 	= 	$PowerBB->_GET['subject_id'];
			$SmLogsArr['field']['edit_date'] 	= 	date("d/m/Y", $time);

			$insert = $PowerBB->core->Insert($SmLogsArr,'supermemberlogs');

       			//$UpdateSectionCache1 =	$PowerBB->functions->UpdateSectionCache($Move_from_section);
                $UpdateSectionCache2 = $PowerBB->functions->UpdateSectionCache($PowerBB->_POST['section']);
                $UpdateSectionCache3 = $PowerBB->functions->UpdateSectionCache($PowerBB->_POST['id_section']);

     		//$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Was_mov_subject']);
			$PowerBB->functions->header_redirect('index.php?page=topic&amp;show=1&amp;id=' . $PowerBB->_GET['subject_id']);
   }

	function _CloseStart()
	{
		global $PowerBB;

         if ($PowerBB->_SERVER['REQUEST_METHOD'] != 'POST')
         {
         	 header("Location: index.php");
		     exit;
         }

		if ($PowerBB->_CONF['group_info']['group_mod'] == '1')
		{
			if ($PowerBB->_CONF['group_info']['close_subject'] == '0')
			{
			 $PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
			}
		}

		$PowerBB->_GET['subject_id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['subject_id'],'intval');

		if (empty($PowerBB->_GET['subject_id']))
		{
			$PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}

     // $PowerBB->_POST['reason']   = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['reason'],'sql');

		$UpdateArr 				= 	array();
		$UpdateArr['reason']	=	$PowerBB->_POST['reason'];
		$UpdateArr['where'] 	= 	array('id',$PowerBB->_GET['subject_id']);

		$update = $PowerBB->subject->CloseSubject($UpdateArr);

		if ($update)
		{
			// INSERT moderators Action
			$EditAction				=	array();
		    $EditAction['where'] 	= 	array('id',$PowerBB->_GET['subject_id']);

			$action = $PowerBB->core->GetInfo($EditAction,'subject');

		if ($action['poll_subject'] == '1')
		{
		 if ($PowerBB->_POST['close_poll'] == '1')
		 {
		    $UpdateArr 					= 	array();
		    $UpdateArr['field'] 				= 	array();
		    $UpdateArr['field']['close_poll_subject'] 		= 	'1';
			$UpdateArr['where'] 				= 	array('id',$PowerBB->_GET['subject_id']);

			$update = $PowerBB->core->Update($UpdateArr,'subject');
		 }

		}

		    $subject_title = $action['title'];
		    $time=time()+$PowerBB->_CONF['info_row']['timestamp'];

	        $SmLogsArr 			= 	array();
			$SmLogsArr['field']	=	array();

			$SmLogsArr['field']['username'] 	= 	$PowerBB->_CONF['member_row']['username'];
			$SmLogsArr['field']['edit_action'] 	= 	$PowerBB->_CONF['template']['_CONF']['lang']['locked_Topic'];
			$SmLogsArr['field']['subject_title']= 	$PowerBB->functions->CleanVariable($subject_title,'sql');
			$SmLogsArr['field']['subject_id'] 	= 	$PowerBB->_GET['subject_id'];
			$SmLogsArr['field']['edit_date'] 	= 	date("d/m/Y", $time);

			$insert = $PowerBB->core->Insert($SmLogsArr,'supermemberlogs');

			//$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Was_Close_subject']);
			$PowerBB->functions->header_redirect('index.php?page=topic&amp;show=1&amp;id=' . $PowerBB->_GET['subject_id']);
		}
	}

	function _DeleteIndex()
	{
		global $PowerBB;

			$PowerBB->_GET['subject_id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['subject_id'],'intval');

			if (empty($PowerBB->_GET['subject_id']))
			{
				$PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
			}
			    $SubjectArr = array();
			    $SubjectArr['where'] = array('id',$PowerBB->_GET['subject_id']);
			    $SubjectInfo = $PowerBB->subject->GetSubjectInfo($SubjectArr);

		if ($PowerBB->_CONF['group_info']['group_mod'] == '1')
		{
			if ($PowerBB->_CONF['group_info']['del_subject'] == '0')
			{
			$PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
			}
		}
        else
		{
			if ($PowerBB->_CONF['group_info']['id'] != '1'
			   AND  $PowerBB->_CONF['group_info']['id'] != '2')
			{

				if ($SubjectInfo['close'] == '1')
				{
				 $PowerBB->functions->ShowHeader();
                 $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['This_topic_is_locked']);
				}

		          if ($SubjectInfo['writer'] == $PowerBB->_CONF['member_row']['username'])
		         {
			       if ($PowerBB->functions->section_group_permission($PowerBB->_GET['section'],$PowerBB->_CONF['group_info']['id'],'del_own_subject') == 0)
			       {
					 $PowerBB->functions->ShowHeader();
                      $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
			       }
		         }

          }
      }
	     $PowerBB->template->assign('SubjectDelInfo',$SubjectInfo);
	     $PowerBB->template->assign('subject',$PowerBB->_GET['subject_id']);

	     $PowerBB->template->display('subject_delete_index');
	}

	function _DeleteStart()
	{
		global $PowerBB;

         if ($PowerBB->_SERVER['REQUEST_METHOD'] != 'POST')
         {
         	 header("Location: index.php");
		     exit;
         }

		$PowerBB->_GET['subject_id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['subject_id'],'intval');
		$PowerBB->_GET['section'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['section'],'intval');

		if (empty($PowerBB->_GET['subject_id']))
		{
			$PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}


		    // INSERT moderators Action
			$EditAction				=	array();
		    $EditAction['where'] 	= 	array('id',$PowerBB->_GET['subject_id']);

			$action = $PowerBB->core->GetInfo($EditAction,'subject');

	if ($PowerBB->_POST['deletetype'] == 1)
	 {

		if (empty($PowerBB->_GET['subject_id']))
		{
			$PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}
		$UpdateArr 			= array();
		$UpdateArr['where'] = array('id',$PowerBB->_GET['subject_id']);

		$update = $PowerBB->subject->MoveSubjectToTrash($UpdateArr);



		    $subject_title = $action['title'];
		    $time=time()+$PowerBB->_CONF['info_row']['timestamp'];

	        $SmLogsArr 			= 	array();
			$SmLogsArr['field']	=	array();

			$SmLogsArr['field']['username'] 	= 	$PowerBB->_CONF['member_row']['username'];
			$SmLogsArr['field']['edit_action'] 	= 	$PowerBB->_CONF['template']['_CONF']['lang']['Was_Trasht1'];
			$SmLogsArr['field']['subject_title']= 	$PowerBB->functions->CleanVariable($subject_title,'sql');
			$SmLogsArr['field']['subject_id'] 	= 	$PowerBB->_GET['subject_id'];
			$SmLogsArr['field']['edit_date'] 	= 	date("d/m/Y", $time);

			$insert = $PowerBB->core->Insert($SmLogsArr,'supermemberlogs');

          $UpdateSectionCache1 = $PowerBB->functions->UpdateSectionCache($PowerBB->_GET['section']);


			//$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Was_Trasht']);
			$PowerBB->functions->header_redirect('index.php?page=forum&show=1&id=' . $PowerBB->_GET['section']);

		 }
	  else
	  {



		if ($PowerBB->_CONF['group_info']['group_mod'] == '1')
		{
			if ($PowerBB->_CONF['group_info']['del_subject'] == '0')
			{
			$PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
			}
		}
		    // INSERT moderators Action
			$EditAction				=	array();
		    $EditAction['where'] 	= 	array('id',$PowerBB->_GET['subject_id']);

			$action = $PowerBB->core->GetInfo($EditAction,'subject');

		    $subject_title = $action['title'];
		    $time=time()+$PowerBB->_CONF['info_row']['timestamp'];

	        $SmLogsArr 			= 	array();
			$SmLogsArr['field']	=	array();

			$SmLogsArr['field']['username'] 	= 	$PowerBB->_CONF['member_row']['username'];
			$SmLogsArr['field']['edit_action'] 	= 	$PowerBB->_CONF['template']['_CONF']['lang']['Was_rely_delet_subject'];
			$SmLogsArr['field']['subject_title']= 	$PowerBB->functions->CleanVariable($subject_title,'sql');
			$SmLogsArr['field']['subject_id'] 	= 	$PowerBB->_GET['subject_id'];
			$SmLogsArr['field']['edit_date'] 	= 	date("d/m/Y", $time);

			$insert = $PowerBB->core->Insert($SmLogsArr,'supermemberlogs');

   	         $DelAttachArr				=	array();
	         $DelAttachArr['where'] 	= 	array('subject_id',$PowerBB->_GET['subject_id']);

			  $DeleteAttach = $PowerBB->core->Deleted($DelAttachArr,'attach');

            //  Delete Tags
             $subjectInfoid =  $PowerBB->_GET['subject_id'];
             $getTags_query = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['tag_subject'] . " WHERE subject_id = '$subjectInfoid'");
               while ($getTags_row = $PowerBB->DB->sql_fetch_array($getTags_query))
                 {
					  $DeleteTagArr				=	array();
			          $DeleteTagArr['where'] 	= 	array('id',$getTags_row['tag_id']);
					  $delTags = $PowerBB->tag->DeleteTag($DeleteTagArr);
                 }

			  $DeleteSubjectArr				=	array();
	          $DeleteSubjectArr['where'] 	= 	array('subject_id',$PowerBB->_GET['subject_id']);
			  $delSubject = $PowerBB->tag_subject->DeleteSubject($DeleteSubjectArr);

			     $DelReplyArr				=	array();
		         $DelReplyArr['where'] 	= 	array('subject_id',$PowerBB->_GET['subject_id']);

				  $delReply = $PowerBB->core->Deleted($DelReplyArr,'reply');

			     $DelArr				=	array();
		         $DelArr['where'] 	= 	array('id',$PowerBB->_GET['subject_id']);

				  $del = $PowerBB->core->Deleted($DelArr,'subject');

           $UpdateSectionCache2 = $PowerBB->functions->UpdateSectionCache($PowerBB->_GET['section']);

	        //$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Was_delet_subject']);
			$PowerBB->functions->header_redirect('index.php?page=forum&show=1&id=' . $PowerBB->_GET['section']);

		 }


	}

	function _empty_bac()
	{
		global $PowerBB;

		$PowerBB->_GET['subject_id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['subject_id'],'intval');

		if (empty($PowerBB->_GET['subject_id']))
		{
			$PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}

        // Moderator And admin Editing Subject any time limit

	     if (!$PowerBB->functions->ModeratorCheck($PowerBB->_GET['section']))
	     {

             // time_out For Editing Subject

				$SubjectArr = array();
				$SubjectArr['where'] = array('id',$PowerBB->_GET['subject_id']);

				$PowerBB->_CONF['template']['SubjectInfoTime'] = $PowerBB->core->GetInfo($SubjectArr,'subject');

               $time_out = $PowerBB->_CONF['info_row']['time_out']*60;
              if ($PowerBB->_CONF['now'] > $PowerBB->_CONF['template']['SubjectInfoTime']['write_time']+$time_out)
               {
               $PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Editing_time_out']);
               }
	     }

		$PowerBB->template->assign('edit_page','index.php?page=management&amp;subject_edit=1&amp;subject_id=' . $PowerBB->_GET['subject_id'] . '&amp;section=' . $PowerBB->_GET['section']);

		$PowerBB->functions->GetEditorTools();

		$PowerBB->template->assign('reply_id',$PowerBB->_GET['reply_id']);
		$PowerBB->template->assign('subject_id',$PowerBB->_GET['subject_id']);

		$SubjectArr = array();
		$SubjectArr['where'] = array('id',$PowerBB->_GET['subject_id']);

		$this->Subject = $PowerBB->core->GetInfo($SubjectArr,'subject');


		$PowerBB->template->assign('SubjectpreviewInfo',$this->Subject);

		// Get section information and set it in $this->Section
		$SecArr 			= 	array();
		$SecArr['where'] 	= 	array('id',$PowerBB->_GET['section']);

		$this->Section = $PowerBB->core->GetInfo($SecArr,'section');
		$PowerBB->template->assign('section_info',$this->Section);

         $previewtext = $PowerBB->_POST['text'];
         $previewtext = $PowerBB->Powerparse->replace($previewtext);
         $previewtext = $PowerBB->Powerparse->censor_words($previewtext);
         $PowerBB->Powerparse->replace_smiles($previewtext);
         $PowerBB->template->assign('preview',$previewtext);
         // $PowerBB->_POST['text'] = $PowerBB->Powerparse->censor_words($PowerBB->_POST['text']);
            $PowerBB->_POST['title'] = $PowerBB->Powerparse->censor_words($PowerBB->_POST['title']);
            $PowerBB->_POST['describe'] = $PowerBB->Powerparse->censor_words($PowerBB->_POST['describe']);
         $PowerBB->template->assign('prev',$PowerBB->_POST['text']);
         $PowerBB->template->assign('title_prev',$PowerBB->_POST['title']);
         $PowerBB->template->assign('describe_prev',$PowerBB->_POST['describe']);
         $PowerBB->template->assign('prefix_subject_prev',$PowerBB->_POST['prefix_subject']);
         $PowerBB->template->assign('SRInfo',$this->Subject);

		$PowerBB->template->display('subject_edit');
	}

	function _SubjectEditpreview()
	{
		global $PowerBB;
    	$PowerBB->functions->ShowHeader();
		$PowerBB->_GET['subject_id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['subject_id'],'intval');

		if (empty($PowerBB->_GET['subject_id']))
		{
			$PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}



        // Moderator And admin Editing Subject any time limit

	     if (!$PowerBB->functions->ModeratorCheck($PowerBB->_GET['section']))
	     {


             // time_out For Editing Subject

				$SubjectArr = array();
				$SubjectArr['where'] = array('id',$PowerBB->_GET['subject_id']);

				$PowerBB->_CONF['template']['SubjectInfoTime'] = $PowerBB->core->GetInfo($SubjectArr,'subject');

               $time_out = $PowerBB->_CONF['info_row']['time_out']*60;
              if ($PowerBB->_CONF['now'] > $PowerBB->_CONF['template']['SubjectInfoTime']['write_time']+$time_out)
               {
                $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Editing_time_out']);
               }
	     }

		$PowerBB->template->assign('edit_page','index.php?page=management&amp;subject_edit=1&amp;subject_id=' . $PowerBB->_GET['subject_id'] . '&amp;section=' . $PowerBB->_GET['section']);

		$PowerBB->functions->GetEditorTools();

		$PowerBB->template->assign('reply_id',$PowerBB->_GET['reply_id']);
		$PowerBB->template->assign('subject_id',$PowerBB->_GET['subject_id']);

		$SubjectArr = array();
		$SubjectArr['where'] = array('id',$PowerBB->_GET['subject_id']);

		$this->Subject = $PowerBB->core->GetInfo($SubjectArr,'subject');

        $this->Subject['title']    =   $PowerBB->Powerparse->censor_words($this->Subject['title']);
	   //$this->Subject['title'] = str_ireplace("\\",'', $this->Subject['title']);
        $this->Subject['title'] = $PowerBB->_POST['title'];

		$PowerBB->template->assign('SubjectpreviewInfo',$this->Subject);

		// Get section information and set it in $this->Section
		$SecArr 			= 	array();
		$SecArr['where'] 	= 	array('id',$PowerBB->_GET['section']);

		$this->Section = $PowerBB->core->GetInfo($SecArr,'section');
		$PowerBB->template->assign('section_info',$this->Section);
        $PowerBB->template->assign('SRInfo',$this->Subject);

			$AttachArr 							= 	array();
			$AttachArr['where']					= 	array();
			$AttachArr['where'][0] 				=	array();
			$AttachArr['where'][0]['name'] 		=	'subject_id';
			$AttachArr['where'][0]['oper'] 		=	'=';
			$AttachArr['where'][0]['value'] 	=	$PowerBB->_GET['subject_id'];
			$AttachArr['where'][1] 				=	array();
			$AttachArr['where'][1]['con']		=	'AND';
			$AttachArr['where'][1]['name'] 		=	'reply';
			$AttachArr['where'][1]['oper'] 		=	'=';
			$AttachArr['where'][1]['value'] 	=	'0';
			$AttachArr['order'] 				=	 array();
			$AttachArr['order']['field'] 		= 	'id';
			$AttachArr['order']['type'] 	    = 	'DESC';

			$PowerBB->_CONF['template']['while']['AttachList'] = $PowerBB->core->GetList($AttachArr,'attach');

		$PowerBB->template->display('subject_edit');
		$PowerBB->functions->GetFooter();

	}

 	function __SubjectEdit()
	{
		global $PowerBB;

		$PowerBB->_GET['subject_id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['subject_id'],'intval');


		if (empty($PowerBB->_GET['subject_id']))
		{
			$PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}

				$SubjectArr = array();
				$SubjectArr['where'] = array('id',$PowerBB->_GET['subject_id']);

				$SubjectInfo  = $PowerBB->core->GetInfo($SubjectArr,'subject');
         $SubjectInfo['title']    =   $PowerBB->Powerparse->censor_words($SubjectInfo['title']);
         $SubjectInfo['reason_edit']    =   $PowerBB->Powerparse->censor_words($SubjectInfo['reason_edit']);
         $SubjectInfo['subject_describe']    =   $PowerBB->Powerparse->censor_words($SubjectInfo['subject_describe']);
         $SubjectInfo['close_reason']    =   $PowerBB->Powerparse->censor_words($SubjectInfo['close_reason']);
         $SubjectInfo['delete_reason']    =   $PowerBB->Powerparse->censor_words($SubjectInfo['delete_reason']);
         $SubjectInfo['describe']    =   $PowerBB->Powerparse->censor_words($SubjectInfo['describe']);

				$PowerBB->_CONF['template']['SubjectInfo'] = $SubjectInfo;
		        $GetSubjectInfo = $SubjectInfo;

		if ($PowerBB->_CONF['group_info']['group_mod'] == '1')
		{
			if ($PowerBB->_CONF['group_info']['edit_subject'] == '0')
			{
			$PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
			}
		}
        else
		{
			if ($PowerBB->_CONF['group_info']['id'] != '1'
			   AND  $PowerBB->_CONF['group_info']['id'] != '2')
			{

				if ($SubjectInfo['close'] == '1')
				{
				   $PowerBB->functions->ShowHeader();
                    $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['This_topic_is_locked']);
				}
			}
		}
       $getmember_subscribtion = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['emailed'] . " WHERE user_id='" . $PowerBB->_CONF['member_row']['id'] . "' AND subject_id = '" .$PowerBB->_GET['subject_id'] . "'");
       $getmember_count = $PowerBB->DB->sql_num_rows($getmember_subscribtion);
       $PowerBB->template->assign('IsSubscribed',$getmember_count);



/** Get section's group information and make some checks **/

       if ($PowerBB->functions->section_group_permission($PowerBB->_GET['section'],$PowerBB->_CONF['group_info']['id'],'edit_own_subject') == 0)
       {
       	 $PowerBB->functions->ShowHeader();
		 $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
       }

       if ($PowerBB->functions->section_group_permission($this->SectionInfo['id'],$PowerBB->_CONF['group_info']['id'],'write_poll') == 0
       and $PowerBB->_CONF['group_info']['write_poll'])
       {
       $PowerBB->template->assign('write_poll',true);
       }


       // $GetSubjectInfo['text'] = $PowerBB->Powerparse->censor_words($GetSubjectInfo['text']);

		//$GetSubjectInfo['text'] = $PowerBB->Powerparse->replace_htmlentities($GetSubjectInfo['text']);
		//$GetSubjectInfo['text'] = str_replace('<br />', "", $GetSubjectInfo['text']);
		$GetSubjectInfo['title'] 	= 	$PowerBB->functions->CleanVariable($GetSubjectInfo['title'],'html');
		$GetSubjectInfo['title'] 	= 	$PowerBB->functions->CleanVariable($GetSubjectInfo['title'],'sql');
         $GetSubjectInfo['title']    =   $PowerBB->Powerparse->censor_words($GetSubjectInfo['title']);
        // $GetSubjectInfo['text'] = @strip_tags($GetSubjectInfo['text']);
        $PowerBB->template->assign('GetSubjectInfo',$GetSubjectInfo['text']);

		$MemberArr 				= 	array();
		$MemberArr['where']		=	array('username',$SubjectInfo['writer']);

		$WriterInfo = $PowerBB->core->GetInfo($MemberArr,'member');

		$MemberReplyArr 				= 	array();
		$MemberReplyArr['where']		=	array('username',$ReplyInfo['writer']);

		$ReplyWriterInfo = $PowerBB->member->GetMemberInfo($MemberReplyArr);
        $Admin = $PowerBB->functions->ModeratorCheck($SubjectInfo['section']);
        $PowerBB->template->assign('Admin',$Admin);

        // show Custom_bbcode List
		$Custom_bbcodeArr 					= 	array();
		$Custom_bbcodeArr['order']			=	array();
		$Custom_bbcodeArr['order']['field']	=	'id';
		$Custom_bbcodeArr['order']['type']	=	'DESC';
		$Custom_bbcodeArr['proc'] 			= 	array();
		$Custom_bbcodeArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');

		$PowerBB->_CONF['template']['while']['Custom_bbcodesList'] = $PowerBB->core->GetList($Custom_bbcodeArr,'custom_bbcode');


	  if ($PowerBB->functions->ModeratorCheck($PowerBB->_GET['section'])
	  or $PowerBB->_CONF['member_row']['username'] == $GetSubjectInfo['writer'])
		{

        // Moderator And admin Editing Subject any time limit

	     if (!$PowerBB->functions->ModeratorCheck($PowerBB->_GET['section']))
	     {

             // time_out For Editing Subject

               $time_out = $PowerBB->_CONF['info_row']['time_out']*60;
              if ($PowerBB->_CONF['now'] > $PowerBB->_CONF['template']['SubjectInfo']['write_time']+$time_out)
               {
                 $PowerBB->functions->ShowHeader();
                 $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Editing_time_out']);
               }
	     }

		$PowerBB->template->assign('edit_page','index.php?page=management&amp;subject_edit=1&amp;subject_id=' . $PowerBB->_GET['subject_id'] . '&amp;section=' . $PowerBB->_GET['section']);

		$PowerBB->functions->GetEditorTools();

		// Get the attachment information


		$PowerBB->template->assign('subject_id',$PowerBB->_GET['subject_id']);
        $PowerBB->template->assign('id',$PowerBB->_GET['subject_id']);

		// Get section information and set it in $this->Section
		$SecArr 			= 	array();
		$SecArr['where'] 	= 	array('id',$PowerBB->_GET['section']);

		$this->Section = $PowerBB->core->GetInfo($SecArr,'section');

		$PowerBB->template->assign('section_info',$this->Section);

        $PowerBB->template->assign('reason_edit',$PowerBB->_CONF['template']['SubjectInfo']['reason_edit']);
        $PowerBB->template->assign('SRInfo',$PowerBB->_CONF['template']['SubjectInfo']);
        eval($PowerBB->functions->get_fetch_hooks('subject_edit_main'));

			$AttachArr 							= 	array();
			$AttachArr['where']					= 	array();
			$AttachArr['where'][0] 				=	array();
			$AttachArr['where'][0]['name'] 		=	'subject_id';
			$AttachArr['where'][0]['oper'] 		=	'=';
			$AttachArr['where'][0]['value'] 	=	$PowerBB->_GET['subject_id'];
			$AttachArr['where'][1] 				=	array();
			$AttachArr['where'][1]['con']		=	'AND';
			$AttachArr['where'][1]['name'] 		=	'reply';
			$AttachArr['where'][1]['oper'] 		=	'=';
			$AttachArr['where'][1]['value'] 	=	'0';

			$PowerBB->_CONF['template']['while']['AttachList'] = $PowerBB->core->GetList($AttachArr,'attach');


		$PowerBB->template->display('subject_edit');

		}
		else
		{
			$PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['not_allowed_access']);
		}
	}

	function _SubjectEditStart()
	{
		global $PowerBB;

         if ($PowerBB->_SERVER['REQUEST_METHOD'] != 'POST')
         {
         	 header("Location: index.php");
		     exit;
         }
        $PowerBB->_POST['title']	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['title'],'html');
		$PowerBB->_GET['subject_id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['subject_id'],'intval');
		$PowerBB->_GET['section'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['section'],'intval');
		$PowerBB->_POST['describe']	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['describe'],'html');
		$censorwords = preg_split('#[ \r\n\t]+#', $PowerBB->_CONF['info_row']['censorwords'], -1, PREG_SPLIT_NO_EMPTY);
		$PowerBB->_POST['reason_edit'] = str_ireplace($censorwords,'**', $PowerBB->_POST['reason_edit']);
		$PowerBB->_POST['reason_edit'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['reason_edit'],'html');

		$SubjectInfoArr 			= 	array();
		$SubjectInfoArr['where'] 	= 	array('id',$PowerBB->_GET['subject_id']);

		$SubjectUpdate = $PowerBB->subject->GetSubjectInfo($SubjectInfoArr);


			$AttachArr 							= 	array();
			$AttachArr['where']					= 	array();
			$AttachArr['where'][0] 				=	array();
			$AttachArr['where'][0]['name'] 		=	'subject_id';
			$AttachArr['where'][0]['oper'] 		=	'=';
			$AttachArr['where'][0]['value'] 	=	$PowerBB->_GET['subject_id'];
			$AttachArr['where'][1] 				=	array();
			$AttachArr['where'][1]['con']		=	'AND';
			$AttachArr['where'][1]['name'] 		=	'reply';
			$AttachArr['where'][1]['oper'] 		=	'=';
			$AttachArr['where'][1]['value'] 	=	'0';
			$AttachArr['order'] 				=	 array();
			$AttachArr['order']['field'] 		= 	'id';
			$AttachArr['order']['type'] 	    = 	'DESC';

			$PowerBB->_CONF['template']['while']['AttachList'] = $PowerBB->core->GetList($AttachArr,'attach');

		if (!$PowerBB->functions->ModeratorCheck($SubjectUpdate['section']))
		{
		   if ($PowerBB->_CONF['member_row']['username'] != $SubjectUpdate['writer'])
		   {
			$PowerBB->functions->ShowHeader();
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
			}

               $time_out = $PowerBB->_CONF['info_row']['time_out']*60;
              if ($PowerBB->_CONF['now'] > $SubjectUpdate['write_time']+$time_out)
               {
                 $PowerBB->functions->ShowHeader();
                 $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Editing_time_out']);
               }
		}

				if ($PowerBB->_POST['stick'])
				{
					$UpdateArr = array();
					$UpdateArr['where'] = array('id',$PowerBB->_GET['subject_id']);

					$update = $PowerBB->subject->StickSubject($UpdateArr);
				}

				if ($PowerBB->_POST['close'])
				{
		          // $PowerBB->_POST['reason']   = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['reason'],'sql');
					$UpdateArr = array();
					$UpdateArr['reason'] = $PowerBB->_POST['reason'];
					$UpdateArr['where'] = array('id',$PowerBB->_GET['subject_id']);

					$update = $PowerBB->subject->CloseSubject($UpdateArr);
				}
                if ($PowerBB->_POST['unstick'])
                {
                   $UpdateArr = array();
                   $UpdateArr['where'] = array('id',$PowerBB->_GET['subject_id']);
                   $update = $PowerBB->subject->UnstickSubject($UpdateArr);
                }

                if ($PowerBB->_POST['unclose'])
                {
		           $PowerBB->_POST['reason']   = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['reason'],'sql');

                    $UpdateArr = array();
                    $UpdateArr['reason'] = $PowerBB->_POST['reason'];
                    $UpdateArr['where'] = array('id',$PowerBB->_GET['subject_id']);
                    $update = $PowerBB->subject->OpenSubject($UpdateArr);
                }


		if ($PowerBB->_POST['preview'])
       {
            define('DONT_STRIPS_SLIASHES',true);
			$PowerBB->_POST['text'] = str_replace('target="_blank" ','',$PowerBB->_POST['text']);
			$PowerBB->template->assign('prev',$PowerBB->_POST['text']);
			$PowerBB->_POST['text'] = $PowerBB->Powerparse->replace($PowerBB->_POST['text']);
			$PowerBB->Powerparse->replace_smiles($PowerBB->_POST['text']);
            $PowerBB->_POST['text'] = $PowerBB->Powerparse->censor_words($PowerBB->_POST['text']);
         $PowerBB->_POST['title']    =   $PowerBB->Powerparse->censor_words($PowerBB->_POST['title']);
         $PowerBB->_POST['reason_edit']    =   $PowerBB->Powerparse->censor_words($PowerBB->_POST['reason_edit']);
         $PowerBB->_POST['close_reason']    =   $PowerBB->Powerparse->censor_words($PowerBB->_POST['close_reason']);
         $PowerBB->_POST['delete_reason']    =   $PowerBB->Powerparse->censor_words($PowerBB->_POST['delete_reason']);
         $PowerBB->_POST['prefix_subject']    =   $PowerBB->Powerparse->censor_words($PowerBB->_POST['prefix_subject']);
         $PowerBB->_POST['describe']    =   $PowerBB->Powerparse->censor_words($PowerBB->_POST['describe']);

			$PowerBB->template->assign('title_prev',$PowerBB->_POST['title']);
			$PowerBB->template->assign('describe_prev',$PowerBB->_POST['describe']);
			$PowerBB->template->assign('preview',$PowerBB->_POST['text']);

			$PowerBB->template->assign('view_preview',$PowerBB->_POST['text']);
			$PowerBB->template->assign('reason_edit',$PowerBB->_POST['reason_edit']);

			$PowerBB->template->assign('prefix_subject_prev',$PowerBB->_POST['prefix_subject']);
			$PowerBB->template->assign('subject_id',$PowerBB->_GET['subject_id']);

			$this->_SubjectEditpreview();


        }
      else
       {

			if (empty($PowerBB->_GET['subject_id']))
			{
				$PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
			}

				if (empty($PowerBB->_POST['title']))
				{
					$PowerBB->functions->ShowHeader();
                    $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['no_title']);
                    $this->_empty_bac();
					$PowerBB->functions->error_stop();

				}

				if (empty($PowerBB->_POST['text']))
				{
					$PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['no_text']);
				}

                    $TitlePost = utf8_decode($PowerBB->_POST['title']);
		     		if (isset($TitlePost{$PowerBB->_CONF['info_row']['post_title_max']}))
		     		{
		     			$PowerBB->functions->ShowHeader();
                        $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['post_text_max_subjects']);
                         $this->_empty_bac();
		       			$PowerBB->functions->error_stop();
		    		}

		        	if  (!isset($TitlePost{$PowerBB->_CONF['info_row']['post_title_min']}))
		     		{
		     			$PowerBB->functions->ShowHeader();
                        $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['post_text_min_subjects']);
                         $this->_empty_bac();
		      			$PowerBB->functions->error_stop();
		     		}

                    $TextPost = utf8_decode($PowerBB->_POST['text']);
                    $TextPost = preg_replace('#\[IMG\](.*)\[/IMG\]#siU', '', $TextPost);
		       	 	if (isset($TextPost{$PowerBB->_CONF['info_row']['post_text_max']}))
		     		{
		     		$PowerBB->functions->ShowHeader();
                        $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['post_text_max']);
                         $this->_empty_bac();
		      			$PowerBB->functions->error_stop();
		     		}

		     		if (!isset($TextPost{$PowerBB->_CONF['info_row']['post_text_min']}))
		     		{
		     			$PowerBB->functions->ShowHeader();
		     			 $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['post_text_min']);
                         $this->_empty_bac();
		      			$PowerBB->functions->error_stop();
		     		}

	        // Moderator And admin Editing Subject any time limit

		     if (!$PowerBB->functions->ModeratorCheck($PowerBB->_GET['section']))
		     {
                    $non_mod_actiondate = true;
	             // time_out For Editing Subject

					$SubjectArr = array();
					$SubjectArr['where'] = array('id',$PowerBB->_GET['subject_id']);

					$PowerBB->_CONF['template']['SubjectInfo'] = $PowerBB->core->GetInfo($SubjectArr,'subject');

	               $time_out = $PowerBB->_CONF['info_row']['time_out']*60;
	              if ($PowerBB->_CONF['now'] > $PowerBB->_CONF['template']['SubjectInfo']['write_time']+$time_out)
	               {
	               $PowerBB->functions->ShowHeader();
                   $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Editing_time_out']);
	               }
		     }


            $time=time()+$PowerBB->_CONF['info_row']['timestamp'];

			// ADD poll
         if ($PowerBB->_POST['poll'])
         {
                // Filter Words
                  $PowerBB->_POST['question'] = $PowerBB->functions->CleanVariable($PowerBB->_POST['question'],'html');
                 // $PowerBB->_POST['question'] = $PowerBB->functions->CleanVariable($PowerBB->_POST['question'],'sql');

              if (empty($PowerBB->_POST['question']))
             {
             	$PowerBB->functions->ShowHeader();
                $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['fill_in_question']);
                 $PowerBB->template->assign('question',$PowerBB->_POST['question']);
                    $this->_empty_bac();
                    $PowerBB->functions->error_stop();
              }

                 $Answer = $PowerBB->_POST['answer'];
               foreach ($Answer as $Answer_x)
               {
                // Filter Answer Words
                $Answer_x = $PowerBB->functions->CleanVariable($Answer_x,'html');
                 $Answer_x = $PowerBB->functions->CleanVariable($Answer_x,'sql');
                $Answer_x = str_replace('<','',$Answer_x);
                  if (empty($Answer_x))
                {
                	$PowerBB->functions->ShowHeader();
                    $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['fill_in_answer']);
                    $PowerBB->template->assign('question',$PowerBB->_POST['question']);
                    $this->_empty_bac();
                    $PowerBB->functions->error_stop();
                }

               }
           }
			//
                   // Filter Words
				$PowerBB->_POST['text'] = str_replace('target="_blank" ','',$PowerBB->_POST['text']);
				//$PowerBB->_POST['title'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['title'],'sql');
				//$PowerBB->_POST['text'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['text'],'sql');

                 // mention users tag replace
                if($PowerBB->functions->mention_permissions())
                {
				  if(preg_match('/\[mention\](.*?)\[\/mention\]/s', $PowerBB->_POST['text'], $tags_w))
					{
					$username = trim($tags_w[1]);
					$topic_id = $PowerBB->_GET['subject_id'];
					$MemArr = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['member'] . " WHERE username = '$username' ");
					$Member_row = $PowerBB->DB->sql_fetch_array($MemArr);
                    if($Member_row)
                    {
						if ($Member_row['username'] == $PowerBB->_CONF['member_row']['username'])
						{
				        $PowerBB->_POST['text'] = str_replace("[mention]", "@", $PowerBB->_POST['text']);
						$PowerBB->_POST['text'] = str_replace("[/mention]", "", $PowerBB->_POST['text']);
						 $Member_row['username'] = '';
						}
						if (!empty($Member_row['username']))
						{
						$forum_url              =   $PowerBB->functions->GetForumAdress();
						$url = $forum_url."index.php?page=profile&amp;show=1&amp;id=".$Member_row['id'];
						$PowerBB->_POST['text'] = str_replace("[mention]", "[url=".$PowerBB->functions->rewriterule($url)."]@", $PowerBB->_POST['text']);
						$PowerBB->_POST['text'] = str_replace("[/mention]", "[/url]", $PowerBB->_POST['text']);
	                    // insert mention
				         $Getmention_youNumrs = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT *  FROM " . $PowerBB->prefix . "mention WHERE you = '$username' AND topic_id = '$topic_id' AND user_read = '1'"));
	                     if($Getmention_youNumrs)
	                      {
	                      $insert_mention = 	false;
	                      }
	                      else
	                      {
	                      $insert_mention = 	true;
	                      }
						}
				    }
                 }
               }
			$UpdateArr 				= 	array();
			$UpdateArr['field']		=	array();

			$UpdateArr['field']['title'] 				= 	$PowerBB->_POST['title'];
			$UpdateArr['field']['text'] 		        = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['text'],'nohtml');
			$UpdateArr['field']['icon'] 				= 	$PowerBB->_POST['icon'];
			if ($PowerBB->_POST['non_actiondate'] or $non_mod_actiondate){
			$UpdateArr['field']['actiondate'] 	= 	$PowerBB->_CONF['now'];
			$UpdateArr['field']['action_by'] 	= 	$PowerBB->_CONF['member_row']['username'];
			}
			else
			{
			$UpdateArr['field']['actiondate'] 	= 	"";
			$UpdateArr['field']['action_by'] 	= 	"";
			}
			$UpdateArr['field']['reason_edit'] 	        = 	$PowerBB->_POST['reason_edit'];
             if ($PowerBB->_POST['poll'])
             {
             $UpdateArr['field']['poll_subject']        =    1;
             }
			$UpdateArr['field']['subject_describe'] 	= 	$PowerBB->_POST['describe'];
            $UpdateArr['field']['stick']                =    $stick;
            $UpdateArr['field']['close']                =    $close;
		    $UpdateArr['field']['prefix_subject'] 		= 	$PowerBB->_POST['prefix_subject'];
			$UpdateArr['where'] 						= 	array('id',$PowerBB->_GET['subject_id']);

			$update = $PowerBB->core->Update($UpdateArr,'subject');

			if ($update)
			{

              if ($PowerBB->_CONF['info_row']['allowed_emailed'] == '1')
                 {

                 if ($PowerBB->_POST['emailed'])
                 {
                 $EmailedArr                           =    array();
                 $EmailedArr['get_id']                    =    true;
                 $EmailedArr['field']                    =    array();
                 $EmailedArr['field']['user_id']           =    $PowerBB->_CONF['member_row']['id'];
                 $EmailedArr['field']['subject_id']           =    $PowerBB->_GET['subject_id'];
                 $EmailedArr['field']['subject_title']        =    $UpdateArr['field']['title'];
                $Insert = $PowerBB->core->Insert($EmailedArr,'emailed');

                }else{

                $Del = $PowerBB->emailed->UnScubscribe($PowerBB->_GET['subject_id']);
                }
                }

               if ($PowerBB->_POST['poll'])
                 {

                    if (isset($PowerBB->_POST['question'])
                        and isset($PowerBB->_POST['answer'][0])
                        and isset($PowerBB->_POST['answer'][1]))
                    {
                        $answers_number = 2;

                        if ($PowerBB->_POST['poll_answers_count'] > 0)
                        {
                           $answers_number = $PowerBB->_POST['poll_answers_count'];
                        }

                        $answers = array();

                        $x = 0;

                        while ($x < $answers_number)
                        {
                           // The text of the answer
                           $answers[$x][0] = $PowerBB->_POST['answer'][$x];
                        $PowerBB->functions->CleanVariable($PowerBB->_POST['answer'][$x],'html');
                        $PowerBB->functions->CleanVariable($PowerBB->_POST['answer'][$x],'sql');
						$PowerBB->_POST['answer'][$x] = str_replace('SCRIPT','',$PowerBB->_POST['answer'][$x]);
						$PowerBB->_POST['answer'][$x] = $PowerBB->functions->CleanVariable($PowerBB->_POST['answer'][$x],'sql');

                           // The result
                           $answers[$x][1] = 0;

                           $x += 1;
                        }

                        $PollArr              =    array();
                        $PollArr['field']    =    array();
                        $PollArr['field']['qus']        =    $PowerBB->functions->CleanVariable($PowerBB->_POST['question'],'html');
                        $PollArr['field']['answers']    =    $PowerBB->_POST['answer'];
                        $PollArr['field']['subject_id']    =    $PowerBB->_GET['subject_id'];

                        $InsertPoll = $PowerBB->poll->InsertPoll($PollArr);
                    }
                 }

				// insert mention
			if($PowerBB->functions->mention_permissions())
			  {
				if ($insert_mention)
				{
				$InsertArr 					= 	array();
				$InsertArr['field']			=	array();

				$InsertArr['field']['user_mention_about_you'] 			= 	$PowerBB->_CONF['member_row']['username'];
				$InsertArr['field']['you'] 			= 	$Member_row['username'];
				$InsertArr['field']['topic_id'] 				= 	intval($PowerBB->_GET['subject_id']);
				$InsertArr['field']['reply_id'] 			= 	0;
				$InsertArr['field']['profile_id'] 			= 	$PowerBB->_CONF['member_row']['id'];
				$InsertArr['field']['date'] 		= 	$PowerBB->_CONF['now'];
				$InsertArr['field']['user_read'] 		    = 	'1';

				$insert = $PowerBB->core->Insert($InsertArr,'mention');
				}
             }

     		// The number of section's subjects number
     		$UpdateArr 					= 	array();
     		$UpdateArr['field']			=	array();

     		$UpdateArr['field']['icon'] 	= 	$PowerBB->_POST['icon'];
     		$UpdateArr['field']['last_subject'] 	= 	$PowerBB->_POST['title'];
     		$UpdateArr['where']					= 	array('last_subjectid',$PowerBB->_GET['subject_id']);

     		$UpdateSubject = $PowerBB->core->Update($UpdateArr,'section');
     		$PowerBB->cache->UpdateSubjectNumber(array('icon'	=>	$PowerBB->_POST['icon']));
     		$PowerBB->cache->UpdateSubjectNumber(array('last_subject'	=>	$PowerBB->_POST['title']));

              // Update section's cache
                $UpdateSectionCache = $PowerBB->functions->UpdateSectionCache($SubjectUpdate['section']);
                eval($PowerBB->functions->get_fetch_hooks('subject_edit_start'));

				//$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Updated_successfully']);
				 $PowerBB->functions->header_redirect('index.php?page=topic&amp;show=1&amp;id=' . $PowerBB->_GET['subject_id']);
			}

		}
	}

	function _Reply()
	{
		global $PowerBB;

		if (!isset($PowerBB->_GET['operator'])
			or !isset($PowerBB->_GET['section'])
			or !isset($PowerBB->_GET['reply']))
		{
			$PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}
		elseif ($PowerBB->_GET['operator'] == 'edit')
		{
			$this->__ReplyEdit();
		}
		elseif ($PowerBB->_GET['operator'] == 'unreview_reply')
		{
			$this->_UnReviewReply();
		}
		else
		{
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['not_allowed_access']);
		}

	}



	function _empty_bac_ReplyEdit()
	{
		global $PowerBB;

		$PowerBB->_GET['reply_id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['reply_id'],'intval');

		if (empty($PowerBB->_GET['reply_id']))
		{
		    $PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}

		 // Moderator And admin Editing Reply any time limit

	     if (!$PowerBB->functions->ModeratorCheck($PowerBB->_GET['section']))
	     {

             // time out For Editing Reply

			$ReplyArr = array();
			$ReplyArr['where'] = array('id',$PowerBB->_GET['reply_id']);

			$PowerBB->_CONF['template']['ReplyInfoTime'] = $PowerBB->core->GetInfo($ReplyArr,'reply');
	               $time_out = $PowerBB->_CONF['info_row']['time_out']*60;
	             if ($PowerBB->_CONF['now'] > $PowerBB->_CONF['template']['ReplyInfoTime']['write_time']+$time_out)
	             {
	                $PowerBB->functions->ShowHeader();
                    $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Reply_Editing_time_out']);
	            }
	     }


		$PowerBB->template->assign('edit_page','index.php?page=management&amp;reply_edit=1&amp;reply_id=' . $PowerBB->_GET['reply_id'] . '&amp;section=' . $PowerBB->_GET['section'] . '&amp;subject_id=' . $PowerBB->_GET['subject_id']);

		$PowerBB->functions->GetEditorTools();

		$PowerBB->template->assign('reply_id',$PowerBB->_GET['reply_id']);
		$PowerBB->template->assign('subject_id',$PowerBB->_GET['subject_id']);


		// Get Reply preview information and set it in $this->Reply
			$ReplyArr = array();
			$ReplyArr['where'] = array('id',$PowerBB->_GET['reply_id']);

			$this->Reply = $PowerBB->core->GetInfo($ReplyArr,'reply');
           $this->Reply['title']  = stripslashes($this->Reply['title']);
		$PowerBB->template->assign('ReplyInfoTime',$this->Reply);

				// Get section information and set it in $this->Section
		$SecArr 			= 	array();
		$SecArr['where'] 	= 	array('id',$PowerBB->_GET['section']);

		$this->Section = $PowerBB->core->GetInfo($SecArr,'section');
		$PowerBB->template->assign('section_info',$this->Section);
        $previewtext = $PowerBB->_POST['text'];
        $previewtext = $PowerBB->Powerparse->replace($previewtext);
        $previewtext = $PowerBB->Powerparse->censor_words($previewtext);
        $PowerBB->Powerparse->replace_smiles($previewtext);
        $PowerBB->_POST['text'] = $PowerBB->Powerparse->censor_words($PowerBB->_POST['text']);
            $PowerBB->_POST['title'] = $PowerBB->Powerparse->censor_words($PowerBB->_POST['title']);
            $PowerBB->_POST['describe'] = $PowerBB->Powerparse->censor_words($PowerBB->_POST['describe']);
        $PowerBB->_POST['text'] = str_replace('target="_blank" ','',$PowerBB->_POST['text']);
        $PowerBB->template->assign('preview',$previewtext);
        $PowerBB->template->assign('prev',$PowerBB->_POST['text']);
       	$PowerBB->template->assign('reply_id',$PowerBB->_GET['reply_id']);
		$PowerBB->template->assign('subject_id',$PowerBB->_GET['subject_id']);
		$PowerBB->template->assign('SRInfo',$this->Reply);

		$PowerBB->template->display('reply_edit');
	}

	function _ReplyEditpreview()
	{
		global $PowerBB;
     $PowerBB->functions->ShowHeader();
		$PowerBB->_GET['reply_id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['reply_id'],'intval');

		if (empty($PowerBB->_GET['reply_id']))
		{
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}

		 // Moderator And admin Editing Reply any time limit

	     if (!$PowerBB->functions->ModeratorCheck($PowerBB->_GET['section']))
	     {


             // time out For Editing Reply

			$ReplyArr = array();
			$ReplyArr['where'] = array('id',$PowerBB->_GET['reply_id']);

			$PowerBB->_CONF['template']['ReplyInfoTime'] = $PowerBB->core->GetInfo($ReplyArr,'reply');
            $PowerBB->_CONF['template']['ReplyInfoTime']['title']  = stripslashes($PowerBB->_CONF['template']['ReplyInfoTime']['title']);

	               $time_out = $PowerBB->_CONF['info_row']['time_out']*60;
	             if ($PowerBB->_CONF['now'] > $PowerBB->_CONF['template']['ReplyInfoTime']['write_time']+$time_out)
	             {
                  $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Reply_Editing_time_out']);
	            }
	     }


		$PowerBB->template->assign('edit_page','index.php?page=management&amp;reply_edit=1&amp;reply_id=' . $PowerBB->_GET['reply_id'] . '&amp;section=' . $PowerBB->_GET['section'] . '&amp;subject_id=' . $PowerBB->_GET['subject_id']);

		$PowerBB->functions->GetEditorTools();

		$PowerBB->template->assign('reply_id',$PowerBB->_GET['reply_id']);
		$PowerBB->template->assign('subject_id',$PowerBB->_GET['subject_id']);


		// Get Reply preview information and set it in $this->Reply
			$ReplyArr = array();
			$ReplyArr['where'] = array('id',$PowerBB->_GET['reply_id']);

			$this->Reply = $PowerBB->core->GetInfo($ReplyArr,'reply');
           $this->Reply['title']  = stripslashes($this->Reply['title']);
		$PowerBB->template->assign('ReplyInfoTime',$this->Reply);

				// Get section information and set it in $this->Section
		$SecArr 			= 	array();
		$SecArr['where'] 	= 	array('id',$PowerBB->_GET['section']);

		$this->Section = $PowerBB->core->GetInfo($SecArr,'section');
		$PowerBB->template->assign('section_info',$this->Section);
       		$PowerBB->template->assign('SRInfo',$this->Reply);

			$AttachArr 							= 	array();
			$AttachArr['where']					= 	array();
			$AttachArr['where'][0] 				=	array();
			$AttachArr['where'][0]['name'] 		=	'subject_id';
			$AttachArr['where'][0]['oper'] 		=	'=';
			$AttachArr['where'][0]['value'] 	=	$PowerBB->_GET['reply_id'];
			$AttachArr['where'][1] 				=	array();
			$AttachArr['where'][1]['con']		=	'AND';
			$AttachArr['where'][1]['name'] 		=	'reply';
			$AttachArr['where'][1]['oper'] 		=	'=';
			$AttachArr['where'][1]['value'] 	=	'1';
			$AttachArr['order'] 				=	 array();
			$AttachArr['order']['field'] 		= 	'id';
			$AttachArr['order']['type'] 	    = 	'DESC';

			$PowerBB->_CONF['template']['while']['AttachList'] = $PowerBB->core->GetList($AttachArr,'attach');

		$PowerBB->template->display('reply_edit');
         $PowerBB->functions->GetFooter();
	}



	function __ReplyEdit()
	{
		global $PowerBB;

		if ($PowerBB->_CONF['group_info']['group_mod'] == '1')
		{
			if ($PowerBB->_CONF['group_info']['edit_reply'] == '0')
			{
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
			}
		}
		else
		{
			if ($PowerBB->_CONF['group_info']['id'] != '1'
			   AND  $PowerBB->_CONF['group_info']['id'] != '2')
			{
			    $SubjectArr = array();
			    $SubjectArr['where'] = array('id',$PowerBB->_GET['subject_id']);
			    $SubjectInfo = $PowerBB->core->GetInfo($SubjectArr,'subject');
				if ($SubjectInfo['close'] == '1')
				{
                  $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['This_topic_is_locked']);
				}
			}
		}
		/** Get section's group information and make some checks **/
       if ($PowerBB->functions->section_group_permission($PowerBB->_GET['section'],$PowerBB->_CONF['group_info']['id'],'edit_own_reply') == 0)
         {
		 $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
         }

       $getmember_subscribtion = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['emailed'] . " WHERE user_id ='" . $PowerBB->_CONF['member_row']['id'] . "' AND subject_id = '" .$PowerBB->_GET['subject_id'] . "'");
       $getmember_count = $PowerBB->DB->sql_num_rows($getmember_subscribtion);
       $PowerBB->template->assign('IsSubscribed',$getmember_count);

		$PowerBB->_GET['reply_id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['reply_id'],'intval');

		if (empty($PowerBB->_GET['reply_id']))
		{
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}

		$ReplyArr = array();
		$ReplyArr['where'] = array('id',$PowerBB->_GET['reply_id']);

       $ReplyInfo = $PowerBB->core->GetInfo($ReplyArr,'reply');


		$MemberReplyArr 				= 	array();
		$MemberReplyArr['where']		=	array('username',$ReplyInfo['writer']);

		$ReplyWriterInfo = $PowerBB->member->GetMemberInfo($MemberReplyArr);

        // show Custom_bbcode List
		$Custom_bbcodeArr 					= 	array();
		$Custom_bbcodeArr['order']			=	array();
		$Custom_bbcodeArr['order']['field']	=	'id';
		$Custom_bbcodeArr['order']['type']	=	'DESC';
		$Custom_bbcodeArr['proc'] 			= 	array();
		$Custom_bbcodeArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');

		$PowerBB->_CONF['template']['while']['Custom_bbcodesList'] = $PowerBB->core->GetList($Custom_bbcodeArr,'custom_bbcode');


	  if ($PowerBB->functions->ModeratorCheck($PowerBB->_GET['section'])
	  or $PowerBB->_CONF['member_row']['username'] == $ReplyWriterInfo['username'])
		{

		 // Moderator And admin Editing Reply any time limit

	     if (!$PowerBB->functions->ModeratorCheck($PowerBB->_GET['section']))
	     {

             // time out For Editing Reply

			$ReplyArr = array();
			$ReplyArr['where'] = array('id',$PowerBB->_GET['reply_id']);

			$PowerBB->_CONF['template']['ReplyInfo'] = $PowerBB->core->GetInfo($ReplyArr,'reply');

	               $time_out = $PowerBB->_CONF['info_row']['time_out']*60;
	             if ($PowerBB->_CONF['now'] > $PowerBB->_CONF['template']['ReplyInfo']['write_time']+$time_out)
	             {
                    $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Reply_Editing_time_out']);
	             }
	     }


		$PowerBB->template->assign('edit_page','index.php?page=management&amp;reply_edit=1&amp;reply_id=' . $PowerBB->_GET['reply_id'] . '&amp;section=' . $PowerBB->_GET['section'] . '&amp;subject_id=' . $PowerBB->_GET['subject_id']);

		$PowerBB->functions->GetEditorTools();

		$ReplyArr = array();
		$ReplyArr['where'] = array('id',$PowerBB->_GET['reply_id']);

		$PowerBB->_CONF['template']['ReplyInfo'] = $PowerBB->core->GetInfo($ReplyArr,'reply');

         $PowerBB->_CONF['template']['ReplyInfo']['text'] = $PowerBB->Powerparse->remove_strings($PowerBB->_CONF['template']['ReplyInfo']['text']);

		$SubjectArr = array();
		$SubjectArr['where'] = array('id',$PowerBB->_GET['subject_id']);

		$PowerBB->_CONF['template']['SubjectInfo'] = $PowerBB->core->GetInfo($SubjectArr,'subject');

		$PowerBB->template->assign('reply_id',$PowerBB->_GET['reply_id']);
		$PowerBB->template->assign('subject_id',$PowerBB->_GET['subject_id']);
		$PowerBB->template->assign('id',$PowerBB->_GET['subject_id']);

         // Get the attachment information

		$ReplyAttachArr 					= 	array();
		$ReplyAttachArr['where'] 			= 	array('subject_id',$PowerBB->_GET['reply_id']);

		$PowerBB->_CONF['template']['while']['AttachList'] = $PowerBB->core->GetList($ReplyAttachArr,'attach');

		// Get section information and set it in $this->Section
		$SecArr 			= 	array();
		$SecArr['where'] 	= 	array('id',$PowerBB->_GET['section']);

		$this->Section = $PowerBB->core->GetInfo($SecArr,'section');

        $PowerBB->_CONF['template']['ReplyInfo']['reason_edit'] = $PowerBB->Powerparse->censor_words($PowerBB->_CONF['template']['ReplyInfo']['reason_edit']);
        $PowerBB->_CONF['template']['ReplyInfo']['reason_edit'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_CONF['template']['ReplyInfo']['reason_edit'],'html');

		$PowerBB->template->assign('reason_edit',$PowerBB->_CONF['template']['ReplyInfo']['reason_edit']);
		$PowerBB->template->assign('section_info',$this->Section);
		$PowerBB->template->assign('reply_number',$PowerBB->_GET['reply_number']);

          $PowerBB->template->assign('count_peg',$PowerBB->_GET['count']);
          $Admin = $PowerBB->functions->ModeratorCheck($PowerBB->_GET['section']);
          $PowerBB->template->assign('Admin',$Admin);
          $PowerBB->template->assign('SRInfo',$PowerBB->_CONF['template']['ReplyInfo']);

		//$PowerBB->_CONF['template']['ReplyInfo']['text'] = $PowerBB->Powerparse->replace_htmlentities($PowerBB->_CONF['template']['ReplyInfo']['text']);

         $PowerBB->template->assign('GetReplyInfo',$PowerBB->_CONF['template']['ReplyInfo']['text']);
		$PowerBB->template->display('reply_edit');

		}
		else
		{
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['not_allowed_access']);
		}
	}

	function _ReplyEditStart()
	{
		global $PowerBB;

         if ($PowerBB->_SERVER['REQUEST_METHOD'] != 'POST')
         {
         	 header("Location: index.php");
		     exit;
         }

		$PowerBB->_GET['reply_id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['reply_id'],'intval');
		$PowerBB->_GET['section'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['section'],'intval');

		$ReplyArr = array();
		$ReplyArr['where'] = array('id',$PowerBB->_GET['reply_id']);

		$PowerBB->_CONF['template']['ReplyInfo'] = $PowerBB->core->GetInfo($ReplyArr,'reply');

		if (!$PowerBB->functions->ModeratorCheck($PowerBB->_CONF['template']['ReplyInfo']['section']))
		{
		   if ($PowerBB->_CONF['member_row']['username'] != $PowerBB->_CONF['template']['ReplyInfo']['writer'])
		   {
			$PowerBB->functions->ShowHeader();
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
			}

			$time_out = $PowerBB->_CONF['info_row']['time_out']*60;
			if ($PowerBB->_CONF['now'] > $PowerBB->_CONF['template']['ReplyInfo']['write_time']+$time_out)
			{
			$PowerBB->functions->ShowHeader();
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Reply_Editing_time_out']);
			}
		}

			$AttachArr 							= 	array();
			$AttachArr['where']					= 	array();
			$AttachArr['where'][0] 				=	array();
			$AttachArr['where'][0]['name'] 		=	'subject_id';
			$AttachArr['where'][0]['oper'] 		=	'=';
			$AttachArr['where'][0]['value'] 	=	$PowerBB->_GET['reply_id'];
			$AttachArr['where'][1] 				=	array();
			$AttachArr['where'][1]['con']		=	'AND';
			$AttachArr['where'][1]['name'] 		=	'reply';
			$AttachArr['where'][1]['oper'] 		=	'=';
			$AttachArr['where'][1]['value'] 	=	'1';
			$AttachArr['order'] 				=	 array();
			$AttachArr['order']['field'] 		= 	'id';
			$AttachArr['order']['type'] 	    = 	'DESC';

			$PowerBB->_CONF['template']['while']['AttachList'] = $PowerBB->core->GetList($AttachArr,'attach');

		if ($PowerBB->_POST['preview'])
       {
         define('DONT_STRIPS_SLIASHES',true);
		$PowerBB->_POST['text'] = str_replace('target="_blank" ','',$PowerBB->_POST['text']);
		$PowerBB->template->assign('prev',$PowerBB->_POST['text']);
		$PowerBB->_POST['text'] = $PowerBB->Powerparse->replace($PowerBB->_POST['text']);
		$PowerBB->Powerparse->replace_smiles($PowerBB->_POST['text']);
        $PowerBB->_POST['text'] = $PowerBB->Powerparse->censor_words($PowerBB->_POST['text']);


		$PowerBB->template->assign('reply_id',$PowerBB->_GET['reply_id']);
		$PowerBB->template->assign('subject_id',$PowerBB->_GET['subject_id']);
		$PowerBB->template->assign('preview',$PowerBB->_POST['text']);
		$PowerBB->template->assign('view_preview',$PowerBB->_POST['text']);

		$PowerBB->_POST['reason_edit'] = $PowerBB->Powerparse->censor_words($PowerBB->_POST['reason_edit']);
		$PowerBB->_POST['reason_edit'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['reason_edit'],'html');
		//$PowerBB->_POST['reason_edit'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['reason_edit'],'sql');

		$PowerBB->template->assign('reason_edit',$PowerBB->_POST['reason_edit']);

		$this->_ReplyEditpreview();


        }
      else
       {
          $PowerBB->_GET['reply_id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['reply_id'],'intval');

			if (empty($PowerBB->_GET['reply_id']))
			{
				$PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
			}

            if (empty($PowerBB->_POST['text']))
			{
             $PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['post_text_min']);
			}
            $TextPost = utf8_decode($PowerBB->_POST['text']);
             $TextPost = preg_replace('#\[IMG\](.*)\[/IMG\]#siU', '', $TextPost);

     		if (isset($TextPost{$PowerBB->_CONF['info_row']['post_text_max']}))
     		{
     			$PowerBB->functions->ShowHeader();
     			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['post_text_max']);
                $this->_empty_bac_ReplyEdit();
                $PowerBB->functions->error_stop();
	     	 }

     		 if (!isset($TextPost{$PowerBB->_CONF['info_row']['post_text_min']}))
     		{
             $PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['post_text_min']);
     		}

			 // Moderator And admin Editing Reply any time limit
		     if (!$PowerBB->functions->ModeratorCheck($PowerBB->_GET['section']))
		     {
                      $non_mod_actiondate = true;

	                // time_out For Editing Subject
		               $time_out = $PowerBB->_CONF['info_row']['time_out']*60;
		             if ($PowerBB->_CONF['now'] > $PowerBB->_CONF['template']['ReplyInfo']['write_time']+$time_out)
		             {
		               $PowerBB->functions->ShowHeader();
                      $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Reply_Editing_time_out']);
		            }
		     }

                 // mention users tag replace
              if($PowerBB->functions->mention_permissions())
			  {
				  if(preg_match('/\[mention\](.*?)\[\/mention\]/s', $PowerBB->_POST['text'], $tags_w))
					{
					$username = trim($tags_w[1]);
					$reply_id = $PowerBB->_GET['reply_id'];
					$MemArr = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['member'] . " WHERE username = '$username' ");
					$Member_row = $PowerBB->DB->sql_fetch_array($MemArr);
                    if($Member_row)
                    {
						if ($Member_row['username'] == $PowerBB->_CONF['member_row']['username'])
						{
				        $PowerBB->_POST['text'] = str_replace("[mention]", "@", $PowerBB->_POST['text']);
						$PowerBB->_POST['text'] = str_replace("[/mention]", "", $PowerBB->_POST['text']);
						 $Member_row['username'] = '';
						}
						if (!empty($Member_row['username']))
						{
						$forum_url              =   $PowerBB->functions->GetForumAdress();
						$url = $forum_url."index.php?page=profile&amp;show=1&amp;id=".$Member_row['id'];
						$PowerBB->_POST['text'] = str_replace("[mention]", "[url=".$PowerBB->functions->rewriterule($url)."]@", $PowerBB->_POST['text']);
						$PowerBB->_POST['text'] = str_replace("[/mention]", "[/url]", $PowerBB->_POST['text']);
	                    // insert mention
				         $Getmention_youNumrs = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT *  FROM " . $PowerBB->prefix . "mention WHERE you = '$username' AND reply_id = '$reply_id' AND user_read = '1'"));
	                     if($Getmention_youNumrs)
	                      {
	                      $insert_mention = 	false;
	                      }
	                      else
	                      {
	                      $insert_mention = 	true;
	                      }
						}
				    }
                 }
              }
            $time=time()+$PowerBB->_CONF['info_row']['timestamp'];
        //$PowerBB->_POST['text'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['text'],'sql');
		//$PowerBB->_POST['title'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['title'],'sql');
       $PowerBB->_POST['reason_edit'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['reason_edit'],'html');
       //$PowerBB->_POST['reason_edit'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['reason_edit'],'sql');

			$UpdateArr 				= 	array();
			$UpdateArr['field']		= 	array();

			$UpdateArr['field']['title'] 		= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['title'],'html');
			$UpdateArr['field']['text'] 		= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['text'],'nohtml');
			if ($PowerBB->_POST['non_actiondate'] or $non_mod_actiondate){
			$UpdateArr['field']['actiondate'] 	= 	$PowerBB->_CONF['now'];
			$UpdateArr['field']['action_by'] 	= 	$PowerBB->_CONF['member_row']['username'];
			}
			else
			{
			$UpdateArr['field']['actiondate'] 	= 	"";
			$UpdateArr['field']['action_by'] 	= 	"";
			}
			$UpdateArr['field']['reason_edit']  = 	$PowerBB->_POST['reason_edit'];
			$UpdateArr['field']['icon'] 		= 	$PowerBB->_POST['icon'];
			$UpdateArr['where'] 				= 	array('id',$PowerBB->_GET['reply_id']);

			$update = $PowerBB->reply->UpdateReply($UpdateArr);

               if ($PowerBB->_POST['stick'])
                {
                $UpdateArr = array();
                $UpdateArr['where'] = array('id',$PowerBB->_GET['subject_id']);

                $update = $PowerBB->subject->StickSubject($UpdateArr);
                }

                if ($PowerBB->_POST['unstick'])
                {
                $UpdateArr = array();
                $UpdateArr['where'] = array('id',$PowerBB->_GET['subject_id']);

                $update = $PowerBB->subject->UnstickSubject($UpdateArr);
                }

                if ($PowerBB->_POST['close'])
                    {
		             // $PowerBB->_POST['reason']   = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['reason'],'sql');

                    $UpdateArr = array();
                    $UpdateArr['reason'] = $PowerBB->_POST['reason'];
                    $UpdateArr['where'] = array('id',$PowerBB->_GET['subject_id']);

                    $update = $PowerBB->subject->CloseSubject($UpdateArr);
                       }
                    if ($PowerBB->_POST['unclose'])
                    {
                    $PowerBB->_POST['reason']   = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['reason'],'sql');
                    $UpdateArr = array();
                    $UpdateArr['reason'] = $PowerBB->_POST['reason'];
                    $UpdateArr['where'] = array('id',$PowerBB->_GET['subject_id']);

                    $update = $PowerBB->subject->OpenSubject($UpdateArr);
                       }

			if ($update)
			{

                   if ($PowerBB->_CONF['info_row']['allowed_emailed'] == '1')
                       {

                          if ($PowerBB->_POST['emailed'])
                          {
                          $EmailedArr                           =    array();
                          $EmailedArr['get_id']                    =    true;
                          $EmailedArr['field']                    =    array();
                          $EmailedArr['field']['user_id']           =    $PowerBB->_CONF['member_row']['id'];
                          $EmailedArr['field']['subject_id']           =    $PowerBB->_GET['subject_id'];
                          $EmailedArr['field']['subject_title']        =    $UpdateArr['field']['title'];
                          $Insert = $PowerBB->emailed->InsertEmailed($EmailedArr);

                          }else{

                          $Del = $PowerBB->emailed->UnScubscribe($PowerBB->_GET['subject_id']);
                       }
                    }

					// insert mention
                if($PowerBB->functions->mention_permissions())
			      {
					if ($insert_mention)
					{
					$InsertArr 					= 	array();
					$InsertArr['field']			=	array();

					$InsertArr['field']['user_mention_about_you'] 			= 	$PowerBB->_CONF['member_row']['username'];
					$InsertArr['field']['you'] 			= 	$Member_row['username'];
					$InsertArr['field']['topic_id'] 				= 	intval($PowerBB->_GET['subject_id']);
					$InsertArr['field']['reply_id'] 			= 	intval($PowerBB->_GET['reply_id']);
					$InsertArr['field']['profile_id'] 			= 	$PowerBB->_CONF['member_row']['id'];
					$InsertArr['field']['date'] 		= 	$PowerBB->_CONF['now'];
					$InsertArr['field']['user_read'] 		    = 	'1';

					$insert = $PowerBB->core->Insert($InsertArr,'mention');
					}
			      }

			$SubjectArr_1 			= 	array();
			$SubjectArr_1['where'] 	= 	array('id',$PowerBB->_GET['subject_id']);

			$this->SubjectInfo_1 = $PowerBB->subject->GetSubjectInfo($SubjectArr_1);

				$ReplyArr_1 = array();
				$ReplyArr_1['where'] = array('id',$PowerBB->_GET['reply_id']);

				$ReplyInfo_1 = $PowerBB->reply->GetReplyInfo($ReplyArr_1);

				$subject_id_1 = $PowerBB->_GET['subject_id'];
		        $reply__nm = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['reply'] . " WHERE subject_id='$subject_id_1' and delete_topic <>1 "));

				//$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Updated_successfully']);




	   		  if ($reply__nm < $PowerBB->_CONF['info_row']['perpage'])
			   {
               $PowerBB->functions->header_redirect('index.php?page=topic&amp;show=1&amp;id=' . $this->SubjectInfo_1['id'] . '#' . $PowerBB->_GET['reply_id']);
			   }
	          else
	          {
				  $PowerBB->functions->header_redirect('index.php?page=topic&amp;show=1&amp;id=' . $this->SubjectInfo_1['id'] . '&amp;count=' . $PowerBB->_POST['count']  . '#' . $PowerBB->_GET['reply_id']);
	          }
		   }



	   }

  }
  	function _TopicModStart()
	{
		global $PowerBB;

         if ($PowerBB->_SERVER['REQUEST_METHOD'] != 'POST')
         {
         	 header("Location: index.php");
		     exit;
         }
		$PowerBB->_GET['subject_id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['subject_id'],'intval');

  	    $SubjectArr = array();
		$SubjectArr['where'] = array('id',$PowerBB->_GET['subject_id']);

		$SubjectInfo = $PowerBB->core->GetInfo($SubjectArr,'subject');

		// Check the powers group mod  of transfer of the subject
		 if (!$PowerBB->functions->ModeratorCheck($SubjectInfo['section']))
		{

			$PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);

		}


		if (empty($PowerBB->_GET['subject_id']))
		{
			$PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}

			$TopicModArr				=	array();
		    $TopicModArr['where'] 	= 	array('id',$PowerBB->_POST['mod_id']);

			$TopicMod = $PowerBB->core->GetInfo($TopicModArr,'topicmod');


     	// Get Section Form Info
     	$SecFormArr 			= 	array();
		$SecFormArr['where'] 	= 	array('id',$SubjectInfo['section']);

		$SectionFormInfo = $PowerBB->core->GetInfo($SecFormArr,'section');

		if ($TopicMod['state'] == 'leave')
		{
		 $state = '0';
		}
		elseif ($TopicMod['state'] == 'close')
		{
         $state = '1';
		}
		else
		{
        $state = '0';
		}

		if ($TopicMod['pin'] == 'leave')
		{
		 $pin = '0';
		}
		elseif ($TopicMod['pin'] == 'pin')
		{
         $pin = '1';
		}
		else
		{
        $pin = '0';
		}

		if ($TopicMod['approve'] == '0')
		{
		 $approve = '0';
		}
		elseif ($TopicMod['approve'] == '1')
		{
         $approve = '0';
		}
		else
		{
        $approve = '1';
		}

		if ($TopicMod['move'] == '-1')
		{
         $move = $SubjectInfo['section'];
		}
		else
		{
        $move = $TopicMod['move'];
		}


     	// Get Section move Form Info
     	$SecmoveFormArr 			= 	array();
		$SecmoveFormArr['where'] 	= 	array('id',$move);

		$SectionmoveFormInfo = $PowerBB->section->GetSectionInfo($SecmoveFormArr);

			$UpdateArr 				= 	array();
			$UpdateArr['field']		=	array();

			$UpdateArr['field']['title'] 				= 	$TopicMod['title_st'].$SubjectInfo['title'].$TopicMod['title_end'];
			$UpdateArr['field']['close'] 				= 	$state;
			$UpdateArr['field']['stick'] 				= 	$pin;
			$UpdateArr['field']['review_subject'] 	     = 	$approve;
			$UpdateArr['field']['section'] 	     = 	$move;
			$UpdateArr['where'] 						= 	array('id',$PowerBB->_GET['subject_id']);

			$update = $PowerBB->core->Update($UpdateArr,'subject');
		     if ($TopicMod['move'] > '1')
		       {
				$subject_id = $PowerBB->_GET['subject_id'];
				$Move_from_section = $SubjectInfo['section'];
					if ($SectionFormInfo['last_subjectid'] == $PowerBB->_GET['subject_id'])
					{

						$GetLastSubject = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['subject'] . " WHERE section = '$Move_from_section' ORDER by write_time DESC");
						$LastSubjectInfo = $PowerBB->DB->sql_fetch_array($GetLastSubject);

						$GetLastReply = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['reply'] . " WHERE section = '$Move_from_section' ORDER by write_time DESC");
						$LastReplyInfo = $PowerBB->DB->sql_fetch_array($GetLastReply);

						if ($LastSubjectInfo['write_time']< $LastReplyInfo['write_time'])
						{
			             $LastInfo = $PowerBB->DB->sql_fetch_array($GetLastReply);
			             $last_subjectid = $LastInfo['subject_id'];
			             $last_berpage_nm = $LastInfo['id'];
						}
						else
						{
			              $LastInfo = $PowerBB->DB->sql_fetch_array($GetLastSubject);
			              $last_subjectid = $LastInfo['id'];
			              $last_berpage_nm = $LastInfo['reply_number'];
						}

						$Subjectid = $PowerBB->_GET['subject_id'];
						$PagerReplyNumArr = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['reply'] . " WHERE subject_id='$Subjectid' and delete_topic <>1"));
						$last_reply = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['reply'] . " WHERE subject_id='$Subjectid' and delete_topic <>1 ORDER by ID DESC limit 0,1");
						$LastReply = $PowerBB->DB->sql_fetch_array($last_reply);
			 		$Subjectid = $PowerBB->_GET['subject_id'];
			        $PagerReplyNumArr = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['reply'] . " WHERE subject_id='$Subjectid' and delete_topic <>1"));
			     		if($PagerReplyNumArr > $PowerBB->_CONF['info_row']['perpage'])
			     		{
							$last_page1 = $PagerReplyNumArr/$PowerBB->_CONF['info_row']['perpage'];
							$last_pagef = round($last_page1, 0);
							$countpage = $last_pagef;
                           $countpage = str_replace("-", '', $countpage);
                         }


						// Update Last subject's information in Section Form
			     		$UpdateLastFormSecArr = array();
			     		$UpdateLastFormSecArr['field']			=	array();

						$UpdateLastFormSecArr['field']['last_writer'] 		= 	$LastInfo['writer'];
			     		$UpdateLastFormSecArr['field']['last_subject'] 		= 	$LastInfo['title'];
			     		$UpdateLastFormSecArr['field']['last_subjectid'] 	= 	$last_subjectid;
			     		$UpdateLastFormSecArr['field']['last_date'] 	= 	$LastInfo['write_time'];
			     		$UpdateLastFormSecArr['field']['last_time'] 	= 	$LastInfo['write_time'];
			     		$UpdateLastFormSecArr['field']['icon'] 		    = 	$LastInfo['icon'];
					    $UpdateLastFormSecArr['field']['last_reply'] 	= 	$LastReply['id'];
					    $UpdateLastFormSecArr['field']['last_berpage_nm']  = 	$perpage_r;

			     		$UpdateLastFormSecArr['where'] 		        = 	array('id',$Move_from_section);

			     		// Update Last To Sec subject's information
			     		$UpdateLastFormSec = $PowerBB->section->UpdateSection($UpdateLastFormSecArr);

					 }

						$Subjectid = $PowerBB->_GET['subject_id'];
						$PagerReplyNumArr = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['reply'] . " WHERE subject_id='$Subjectid' and delete_topic <>1"));
						$last_reply = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['reply'] . " WHERE subject_id='$Subjectid' and delete_topic <>1 ORDER by ID DESC limit 0,1");
						$LastReply = $PowerBB->DB->sql_fetch_array($last_reply);
			 		$Subjectid = $PowerBB->_GET['subject_id'];
			        $PagerReplyNumArr = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['reply'] . " WHERE subject_id='$Subjectid' and delete_topic <>1"));
			     		if($PagerReplyNumArr > $PowerBB->_CONF['info_row']['perpage'])
			     		{

							$last_page1 = $PagerReplyNumArr/$PowerBB->_CONF['info_row']['perpage'];
							$last_pagef = round($last_page1, 0);
							$countpage = $last_pagef;
                           $countpage = str_replace("-", '', $countpage);
                         }

						// Update Last subject's information in Section To
			     		$UpdateLastToSecArr = array();
			     		$UpdateLastToSecArr['field']			=	array();

						$UpdateLastToSecArr['field']['last_writer'] 		= 	$SubjectInfo['writer'];
			     		$UpdateLastToSecArr['field']['last_subject'] 		= 	$SubjectInfo['title'];
			     		$UpdateLastToSecArr['field']['last_subjectid'] 	= 	$PowerBB->_GET['subject_id'];
			     		$UpdateLastToSecArr['field']['last_date'] 	= 	$PowerBB->_CONF['now'];
			     		$UpdateLastToSecArr['field']['last_time'] 	= 	$PowerBB->_CONF['now'];
			     		$UpdateLastToSecArr['field']['icon'] 		= 	$SubjectInfo['icon'];
					    $UpdateLastToSecArr['field']['last_reply'] 	= 	$LastReply['id'];
					    $UpdateLastToSecArr['field']['last_berpage_nm']  = 	$perpage_r;

			     		$UpdateLastToSecArr['where'] 		        = 	array('id',$TopicMod['move']);

			     		// Update Last To Sec subject's information
			     		$UpdateLastToSec = $PowerBB->section->UpdateSection($UpdateLastToSecArr);

				$Move_replys = $PowerBB->DB->sql_query("SELECT  *   FROM " . $PowerBB->table['reply'] . " WHERE subject_id = '$subject_id' ");
				while ($Moved = $PowerBB->DB->sql_fetch_array($Move_replys))
				{
				$UpdateArr 				= 	array();
				$UpdateArr['field']		= 	array();

				$UpdateArr['field']['section'] 		= 	$move;
				$UpdateArr['where'] 				= 	array('id',$Moved['id']);

				$update = $PowerBB->reply->UpdateReply($UpdateArr);
				}
             }

		    if ($TopicMod['reply'] == '1')
		    {

		     	$ReplyArr 			                = 	array();
		     	$ReplyArr['get_id']					=	true;
		     	$ReplyArr['field']               	= 	array();
		     	$ReplyArr['field']['title'] 		= 	$TopicMod['title_st'].$SubjectInfo['title'].$TopicMod['title_end'];
		     	$ReplyArr['field']['text'] 			= 	$TopicMod['reply_content'];
				$ReplyArr['field']['writer']		= 	$PowerBB->_CONF['member_row']['username'];
		     	$ReplyArr['field']['subject_id'] 	= 	$PowerBB->_GET['subject_id'];
		     	$ReplyArr['field']['write_time'] 	= 	$PowerBB->_CONF['now'];
		     	$ReplyArr['field']['section'] 		= 	$move;
		     	$ReplyArr['field']['icon'] 			= 	'look/images/icons/i1.gif';

		     	$Insert = $PowerBB->reply->InsertReply($ReplyArr);

		     		//////////

		     		$TimeArr = array();

		     		$TimeArr['write_time'] 	= 	$PowerBB->_CONF['now'];
		     		$TimeArr['where']		=	array('id',$PowerBB->_GET['subject_id']);

		     		$UpdateWriteTime = $PowerBB->subject->UpdateWriteTime($TimeArr);

		     		$RepArr 					= 	array();
		     		$RepArr['reply_number']		=	$SubjectInfo['reply_number'];
		     		$RepArr['where'] 			= 	array('id',$PowerBB->_GET['subject_id']);

		     		$UpdateReplyNumber = $PowerBB->subject->UpdateReplyNumber($RepArr);

					$this->SectionInfo = $SectionFormInfo;

		     	     if ($PowerBB->_CONF['member_permission'])
				    {
		     		$LastArr = array();
		     		$LastArr['replier'] 	= 	$PowerBB->_CONF['member_row']['username'];
		     		$LastArr['where']		=	array('id',$PowerBB->_GET['subject_id']);

		     		$UpdateLastReplier = $PowerBB->subject->UpdateLastReplier($LastArr);
		     		}

					$this->SectionLastInfo = $SectionFormInfo;

		   			//////////

		     		$UpdateSubjectNumber = $PowerBB->cache->UpdateReplyNumber(array('reply_num'	=>	$PowerBB->_CONF['info_row']['reply_number']));

		     		// Free Lasts in subject
					$UpdateSubjectArr 						= 	array();
					$UpdateSubjectArr['field'] 				= 	array();

					$UpdateSubjectArr['field']['last_replier'] 	= 	$PowerBB->_CONF['member_row']['username'];
					$UpdateSubjectArr['field']['last_time'] 	= 	$PowerBB->_CONF['now'];
					$UpdateSubjectArr['field']['write_date'] 	= 	$PowerBB->_CONF['now'];
					$UpdateSubjectArr['field']['review_reply'] 	= 	$PowerBB->_CONF['member_row']['review_reply'];
					$UpdateSubjectArr['where'] 				= 	array('id',$Subjectinfo['section']);

					$UpdateLastReplier = $PowerBB->subject->UpdateSubject($UpdateSubjectArr);

					// The number of section's reply number
              		$sectionid = $SectionFormInfo['id'];
                    $ReplyNumArr = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['reply'] . " WHERE section='$sectionid' and delete_topic <>1 and review_reply <>1"));
			 		$Subjectid = $PowerBB->_GET['subject_id'];
			        $PagerReplyNumArr = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['reply'] . " WHERE subject_id='$Subjectid' and delete_topic <>1"));
			     		if($PagerReplyNumArr > $PowerBB->_CONF['info_row']['perpage'])
			     		{
							$last_page1 = $PagerReplyNumArr/$PowerBB->_CONF['info_row']['perpage'];
							$last_pagef = round($last_page1, 0);
							$countpage = $last_pagef;
                           $countpage = str_replace("-", '', $countpage);
                         }

					// The number of section's subjects number
		     		$UpdateArr 					= 	array();
		     		$UpdateArr['field']			=	array();

		     		$UpdateArr['field']['reply_num'] 	= 	$ReplyNumArr;
					$UpdateArr['field']['last_writer'] 		= 	$PowerBB->_CONF['member_row']['username'];
		     		$UpdateArr['field']['last_subject'] 		= 	$SubjectInfo['title'];
		     		$UpdateArr['field']['last_subjectid'] 	= 	$SubjectInfo['id'];
		     		$UpdateArr['field']['last_date'] 	= 	$PowerBB->_CONF['now'];
		     		$UpdateArr['field']['last_time'] 	= 	$PowerBB->_CONF['now'];
		     		$UpdateArr['field']['last_reply'] 	= 	$PowerBB->reply->id;
		     		$UpdateArr['field']['icon'] 	    = 	'look/images/icons/i1.gif';
		     		$UpdateArr['field']['last_berpage_nm']  = 	$perpage_r;

		     		$UpdateArr['where']					= 	array('id',$SectionFormInfo['id']);

		     		$UpdateSubjectNumber = $PowerBB->core->Update($UpdateArr,'section');

		     		// Free memory
		     		unset($UpdateArr);

		     		//////////


		     }

              // Update section's cache
					if ($TopicMod['move'] == '-1')
					{
					$UpdateSectionCache = $PowerBB->functions->UpdateSectionCache($SubjectInfo['section']);
					}
					else
					{
					$UpdateSectionCache = $PowerBB->functions->UpdateSectionCache($move);
					$UpdateSectionCache = $PowerBB->functions->UpdateSectionCache($SubjectInfo['section']);

					}
		     		//////////
            // INSERT moderators Action
			$EditAction				=	array();
		    $EditAction['where'] 	= 	array('id',$PowerBB->_GET['subject_id']);

			$action = $PowerBB->core->GetInfo($EditAction,'subject');

		    $subject_title = $TopicMod['title'].$SubjectInfo['title'];
		    $time=time()+$PowerBB->_CONF['info_row']['timestamp'];

	        $SmLogsArr 			= 	array();
			$SmLogsArr['field']	=	array();

			$SmLogsArr['field']['username'] 	= 	$PowerBB->_CONF['member_row']['username'];
			$SmLogsArr['field']['edit_action'] 	= 	 $PowerBB->_CONF['template']['_CONF']['lang']['Multi_Moderation'] .":". $subject_title;
			$SmLogsArr['field']['subject_title']= 	$PowerBB->functions->CleanVariable($subject_title,'sql');
			$SmLogsArr['field']['subject_id'] 	= 	$PowerBB->_GET['subject_id'];
			$SmLogsArr['field']['edit_date'] 	= 	date("d/m/Y", $time);

			$insert = $PowerBB->core->Insert($SmLogsArr,'supermemberlogs');

				//$PowerBB->functions->msg('&Ecirc;&atilde; &Ecirc;&auml;&Yacute;&iacute;&ETH; &Ccedil;&aacute;&Icirc;&Ccedil;&Otilde;&iacute;&Eacute; &Ccedil;&aacute;&Aring;&Ocirc;&Ntilde;&Ccedil;&Yacute;&iacute;&Eacute; '.$subject_title.' &Egrave;&auml;&Igrave;&Ccedil;&Iacute;');
				$PowerBB->functions->header_redirect('index.php?page=topic&amp;show=1&amp;id=' . $PowerBB->_GET['subject_id']);

	}

  	function _UnReviewReply()
	{
		global $PowerBB;

		$PowerBB->_GET['reply_id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['reply_id'],'intval');
		$PowerBB->_GET['subject_id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['subject_id'],'intval');

		if (empty($PowerBB->_GET['reply_id']))
		{
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}

		$PowerBB->_GET['section'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['section'],'intval');

		$ReplyArr = array();
		$ReplyArr['where'] = array('id',$PowerBB->_GET['reply_id']);

		$PowerBB->_CONF['template']['ReplyInfo'] = $PowerBB->core->GetInfo($ReplyArr,'reply');

		if (!$PowerBB->functions->ModeratorCheck($PowerBB->_CONF['template']['ReplyInfo']['section'])
		or !$PowerBB->_CONF['member_row']['username'] == $PowerBB->_CONF['template']['ReplyInfo']['writer'])
		{
		$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
		}

			$SubjectArr = array();
			$SubjectArr['where'] 				= 	array();
			$SubjectArr['where'] 			= 	array('id',$PowerBB->_GET['subject_id']);
			$SubjectInfo = $PowerBB->core->GetInfo($SubjectArr,'subject');

            $ReviewReply 					= 	array();
     		$ReviewReply['review_reply']	=	 $SubjectInfo['review_reply'] -1;
     		$ReviewReply['where'] 			= 	array('id',$PowerBB->_GET['subject_id']);

     		$UpdateReviewReply = $PowerBB->subject->UpdateReviewReply($ReviewReply);
     		if ($UpdateReviewReply)
			{

			$UpdateArr 				= 	array();
			$UpdateArr['field']		= 	array();

			$UpdateArr['field']['review_reply'] = '0';
			$UpdateArr['where'] 				= 	array('id',$PowerBB->_GET['reply_id']);

			$update = $PowerBB->reply->UpdateReply($UpdateArr);
        if ($PowerBB->_POST['stick'])
                {
                $UpdateArr = array();
                $UpdateArr['where'] = array('id',$this->SubjectInfo['id']);

                $update = $PowerBB->subject->StickSubject($UpdateArr);
                }

                if ($PowerBB->_POST['unstick'])
                {
                $UpdateArr = array();
                $UpdateArr['where'] = array('id',$this->SubjectInfo['id']);

                $update = $PowerBB->subject->UnstickSubject($UpdateArr);
                }

                if ($PowerBB->_POST['close'])
                    {
                    $PowerBB->_POST['reason']   = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['reason'],'sql');

                    $UpdateArr = array();
                    $UpdateArr['reason'] = $PowerBB->_POST['reason'];
                    $UpdateArr['where'] = array('id',$this->SubjectInfo['id']);

                    $update = $PowerBB->subject->CloseSubject($UpdateArr);
                       }
                    if ($PowerBB->_POST['unclose'])
                    {

                    $PowerBB->_POST['reason']   = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['reason'],'sql');

                    $UpdateArr = array();
                    $UpdateArr['reason'] = $PowerBB->_POST['reason'];
                    $UpdateArr['where'] = array('id',$this->SubjectInfo['id']);

                    $update = $PowerBB->subject->OpenSubject($UpdateArr);
                       }

                			// INSERT moderators Action
			$EditAction				=	array();
		    $EditAction['where'] 	= 	array('id',$PowerBB->_GET['subject_id']);

			$action = $PowerBB->core->GetInfo($EditAction,'subject');

		    $subject_title = $action['title'];
		    $time=time()+$PowerBB->_CONF['info_row']['timestamp'];

	        $SmLogsArr 			= 	array();
			$SmLogsArr['field']	=	array();

			$SmLogsArr['field']['username'] 	= 	$PowerBB->_CONF['member_row']['username'];
			$SmLogsArr['field']['edit_action'] 	= 	 $PowerBB->_CONF['template']['_CONF']['lang']['Agreed_reply_ID'] . $PowerBB->_GET['reply_id'];
			$SmLogsArr['field']['subject_title']= 	$PowerBB->functions->CleanVariable($subject_title,'sql');
			$SmLogsArr['field']['subject_id'] 	= 	$PowerBB->_GET['subject_id'];
			$SmLogsArr['field']['edit_date'] 	= 	date("d/m/Y", $time);

			$insert = $PowerBB->core->Insert($SmLogsArr,'supermemberlogs');

				//$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Agreed_reply_successfully']);
				$PowerBB->functions->header_redirect('index.php?page=topic&amp;show=1&amp;id=' . $PowerBB->_GET['subject_id']);
			}

	}

	function __SubjectRepeat()
	{
		global $PowerBB;

		$PowerBB->_GET['subject_id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['subject_id'],'intval');

		if (empty($PowerBB->_GET['subject_id']))
		{
			$PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}

		$SubjectArr 			= 	array();
		$SubjectArr['where'] 	= 	array('id',$PowerBB->_GET['subject_id']);

		$Subject = $PowerBB->core->GetInfo($SubjectArr,'subject');

		if (!$PowerBB->functions->ModeratorCheck($Subject['section']))
		{
		$PowerBB->functions->ShowHeader();
		$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
		}

		$PowerBB->template->assign('subject',$PowerBB->_GET['subject_id']);

		$PowerBB->template->display('subject_repeat_index');
	}

	function _SubjectRepeatStart()
	{
		global $PowerBB;

         if ($PowerBB->_SERVER['REQUEST_METHOD'] != 'POST')
         {
         	 header("Location: index.php");
		     exit;
         }
		$PowerBB->_GET['subject_id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['subject_id'],'intval');
    		$PowerBB->_POST['url']   = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['url'],'sql');

		if (empty($PowerBB->_GET['subject_id']))
		{
			$PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}

		$SubjectArr 			= 	array();
		$SubjectArr['where'] 	= 	array('id',$PowerBB->_GET['subject_id']);

		$Subject = $PowerBB->core->GetInfo($SubjectArr,'subject');

		if (!$Subject)
		{
			$PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Requested_topic_does_not_exist']);
		}

		if (!$PowerBB->functions->ModeratorCheck($Subject['section']))
		{
		$PowerBB->functions->ShowHeader();
		$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
		}

		$SectionArr 			= 	array();
		$SectionArr['where'] 	= 	array('id',$Subject['section']);

		$Section = $PowerBB->core->GetInfo($SectionArr,'section');

		if (!isset($PowerBB->_POST['url']))
		{
			$PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_fill_in_all_the_information']);
		}

		$UpdateArr 				= 	array();
		$UpdateArr['reason']	=	$PowerBB->_CONF['template']['_CONF']['lang']['repeated_Subject'];
		$UpdateArr['where'] 	= 	array('id',$PowerBB->_GET['subject_id']);

		$update = $PowerBB->subject->CloseSubject($UpdateArr);

		if ($update)
		{
     		$ReplyArr 			= 	array();
     		$ReplyArr['field'] 	= 	array();

     		$ReplyArr['field']['text'] 			= 	  $PowerBB->_CONF['template']['_CONF']['lang']['Duplicate_this_topic_see_the_original'] .'[url=' . $PowerBB->_POST['url'] . '] ' . $PowerBB->_CONF['template']['_CONF']['lang']['Here'] .' [/url]';
     		$ReplyArr['field']['writer'] 		= 	$PowerBB->_CONF['member_row']['username'];
     		$ReplyArr['field']['subject_id'] 	= 	$PowerBB->_GET['subject_id'];
     		$ReplyArr['field']['write_time'] 	= 	$PowerBB->_CONF['now'];
     		$ReplyArr['field']['section'] 		= 	$Subject['section'];
		    $ReplyArr['field']['icon'] 			= 	'look/images/icons/i1.gif';
     		$ReplyArr['get_id']					=	true;

     		$insert = $PowerBB->reply->InsertReply($ReplyArr);

     		if ($insert)
     		{
	   			$MemberArr 				= 	array();
	   			$MemberArr['field'] 	= 	array();

     			$MemberArr['field']['lastpost_time'] 	=	$PowerBB->_CONF['now'];
     			$MemberArr['where']						=	array('id',$PowerBB->_CONF['member_row']['id']);

   				$UpdateMember = $PowerBB->member->UpdateMember($MemberArr);

     			$TimeArr = array();

     			$TimeArr['write_time'] 	= 	$PowerBB->_CONF['now'];
     			$TimeArr['where']		=	array('id',$PowerBB->_GET['subject_id']);

     			$UpdateWriteTime = $PowerBB->subject->UpdateWriteTime($TimeArr);

     			$RepArr 					= 	array();
     			$RepArr['reply_number']		=	$Subject['reply_number'];
     			$RepArr['where'] 			= 	array('id',$PowerBB->_GET['subject_id']);

     			$UpdateReplyNumber = $PowerBB->subject->UpdateReplyNumber($RepArr);

              //$Subject['title']   = 	$PowerBB->functions->CleanVariable($Subject['title'],'sql');
		     		if (!$PowerBB->_CONF['member_permission'])
					{
	                $writer = 	$PowerBB->_CONF['template']['_CONF']['lang']['Guestp'];
					}
			     	else
			     	{
		            $writer = 	$PowerBB->_CONF['member_row']['username'];
					}
		     		$UpdateLastArr = array();
		     		$UpdateLastArr['field']			=	array();

					$UpdateLastArr['field']['last_writer'] 		= 	$writer;
		     		$UpdateLastArr['field']['last_subject'] 	= 	$PowerBB->functions->CleanVariable($Subject['title'],'html');
		     		$UpdateLastArr['field']['last_subjectid'] 	= 	$Subject['id'];
		     		$UpdateLastArr['field']['last_date'] 	    = 	$PowerBB->_CONF['now'];
		     		$UpdateLastArr['field']['last_time'] 	    = 	$PowerBB->_CONF['now'];

		     		$UpdateLastArr['where'] 		            = 	array('id',$Section['id']);

		     		// Update Last subject's information
		     		$UpdateLast = $PowerBB->section->UpdateSection($UpdateLastArr);

		     		// Free memory
		     		unset($UpdateLastArr);

     			$UpdateSubjectNumber = $PowerBB->cache->UpdateReplyNumber(array('reply_num'	=>	$PowerBB->_CONF['info_row']['reply_number']));

     			$LastArr = array();

     			$LastArr['replier'] 	= 	$PowerBB->_CONF['member_row']['username'];
     			$LastArr['where']		=	array('id',$PowerBB->_GET['subject_id']);

     			$UpdateLastReplier = $PowerBB->subject->UpdateLastReplier($LastArr);

     			// Free memory
     			unset($LastArr);

     			$UpdateArr 					= 	array();
     			$UpdateArr['field']			=	array();

     			$UpdateArr['field']	['reply_num'] 	= 	$Section['reply_num'] + 1;
     			$UpdateArr['where']					= 	array('id',$Section['id']);

     			$UpdateSubjectNumber = $PowerBB->core->Update($UpdateArr,'section');

     			// Free memory
     			unset($UpdateArr);

     						// INSERT moderators Action
			$EditAction				=	array();
		    $EditAction['where'] 	= 	array('id',$PowerBB->_GET['subject_id']);

			$action = $PowerBB->core->GetInfo($EditAction,'subject');

		    $subject_title = $action['title'];
		    $time=time()+$PowerBB->_CONF['info_row']['timestamp'];

	        $SmLogsArr 			= 	array();
			$SmLogsArr['field']	=	array();

			$SmLogsArr['field']['username'] 	= 	$PowerBB->_CONF['member_row']['username'];
			$SmLogsArr['field']['edit_action'] 	= 	$PowerBB->_CONF['template']['_CONF']['lang']['repeated_Subject'];
			$SmLogsArr['field']['subject_title']= 	$PowerBB->functions->CleanVariable($subject_title,'sql');
			$SmLogsArr['field']['subject_id'] 	= 	$PowerBB->_GET['subject_id'];
			$SmLogsArr['field']['edit_date'] 	= 	date("d/m/Y", $time);

			$insert = $PowerBB->core->Insert($SmLogsArr,'supermemberlogs');

				//$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Updated_successfully']);
				$PowerBB->functions->header_redirect('index.php?page=topic&amp;show=1&amp;id=' . $PowerBB->_GET['subject_id']);
     		}
		}
	}

	function __UpStart()
	{
		global $PowerBB;

	  	$PowerBB->_GET['subject_id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['subject_id'],'intval');

		if (empty($PowerBB->_GET['subject_id']))
		{
			$PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}

		$SubjectArr = array();
		$SubjectArr['field'] = array();
		$SubjectArr['field']['write_time'] = time() - ( intval('-42') );

		$SubjectArr['where'] = array('id',$PowerBB->_GET['subject_id']);

		$Update = $PowerBB->subject->UpdateSubject($SubjectArr);

		if ($Update)
		{
						// INSERT moderators Action
			$EditAction				=	array();
		    $EditAction['where'] 	= 	array('id',$PowerBB->_GET['subject_id']);

			$action = $PowerBB->core->GetInfo($EditAction,'subject');

		    $subject_title = $action['title'];
		    $time=time()+$PowerBB->_CONF['info_row']['timestamp'];

	        $SmLogsArr 			= 	array();
			$SmLogsArr['field']	=	array();

			$SmLogsArr['field']['username'] 	= 	$PowerBB->_CONF['member_row']['username'];
			$SmLogsArr['field']['edit_action'] 	= 	$PowerBB->_CONF['template']['_CONF']['lang']['topic_Up'];
			$SmLogsArr['field']['subject_title']= 	$PowerBB->functions->CleanVariable($subject_title,'sql');
			$SmLogsArr['field']['subject_id'] 	= 	$PowerBB->_GET['subject_id'];
			$SmLogsArr['field']['edit_date'] 	= 	date("d/m/Y", $time);

			$insert = $PowerBB->core->Insert($SmLogsArr,'supermemberlogs');

            // Update Section Cache
			if ($action['reply_number'] == '0')
			{

				$writer = $action['writer'];
			}
			else
			{
			  $writer = $action['last_replier'];
			}

				$Subjectid = $PowerBB->_GET['subject_id'];
				$PagerReplyNumArr = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['reply'] . " WHERE subject_id='$Subjectid' and delete_topic <>1"));

				$last_reply = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['reply'] . " WHERE subject_id='$Subjectid' and delete_topic <>1 ORDER by ID DESC limit 0,1");
				$LastReply = $PowerBB->DB->sql_fetch_array($last_reply);

				$Subjectid = $PowerBB->_GET['subject_id'];
				$PagerReplyNumArr = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['reply'] . " WHERE subject_id='$Subjectid' and delete_topic <>1"));
				if($PagerReplyNumArr > $PowerBB->_CONF['info_row']['perpage'])
				{
					$last_page1 = $PagerReplyNumArr/$PowerBB->_CONF['info_row']['perpage'];
					$last_pagef = round($last_page1, 0);
					$countpage = $last_pagef;
				    $countpage = str_replace("-", '', $countpage);
				}


     		$UpdateSectionCache = $PowerBB->functions->UpdateSectionCache($action['section']);

			//$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Was_Up_subject']);
			$PowerBB->functions->header_redirect('index.php?page=topic&amp;show=1&amp;id=' . $PowerBB->_GET['subject_id']);
		}
	}

	function __DownStart()
	{
		global $PowerBB;

		$PowerBB->_GET['subject_id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['subject_id'],'intval');

		if (empty($PowerBB->_GET['subject_id']))
		{
			$PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}

		$SubjectArr = array();
		$SubjectArr['field'] = array();
		$SubjectArr['field']['write_time'] = time() - ( intval('420000000000000000000') );

		$SubjectArr['where'] = array('id',$PowerBB->_GET['subject_id']);

		$Update = $PowerBB->subject->UpdateSubject($SubjectArr);

		if ($Update)
		{
						// INSERT moderators Action
			$EditAction				=	array();
		    $EditAction['where'] 	= 	array('id',$PowerBB->_GET['subject_id']);

			$action = $PowerBB->core->GetInfo($EditAction,'subject');

		    $subject_title = $action['title'];
		    $time=time()+$PowerBB->_CONF['info_row']['timestamp'];

	        $SmLogsArr 			= 	array();
			$SmLogsArr['field']	=	array();

			$SmLogsArr['field']['username'] 	= 	$PowerBB->_CONF['member_row']['username'];
			$SmLogsArr['field']['edit_action'] 	= 	$PowerBB->_CONF['template']['_CONF']['lang']['down_Subject'];
			$SmLogsArr['field']['subject_title']= 	$PowerBB->functions->CleanVariable($subject_title,'sql');
			$SmLogsArr['field']['subject_id'] 	= 	$PowerBB->_GET['subject_id'];
			$SmLogsArr['field']['edit_date'] 	= 	date("d/m/Y", $time);

			$insert = $PowerBB->core->Insert($SmLogsArr,'supermemberlogs');

     		$UpdateSectionCache = $PowerBB->functions->UpdateSectionCache($action['section']);

	    	//$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Was_Down_subject']);
			$PowerBB->functions->header_redirect('index.php?page=topic&amp;show=1&amp;id=' . $PowerBB->_GET['subject_id']);
		}
	}

	function __ReviewSubject()
	{
		global $PowerBB;

	  	$PowerBB->_GET['subject_id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['subject_id'],'intval');

		if (empty($PowerBB->_GET['subject_id']))
		{
			$PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}

		$SubjectArr = array();
		$SubjectArr['field'] = array();
		$SubjectArr['field']['review_subject'] = 1;

		$SubjectArr['where'] = array('id',$PowerBB->_GET['subject_id']);

		$Update = $PowerBB->subject->UpdateSubject($SubjectArr);

		if ($Update)
		{
			 // INSERT moderators Action
			$EditAction				=	array();
		    $EditAction['where'] 	= 	array('id',$PowerBB->_GET['subject_id']);

			$action = $PowerBB->core->GetInfo($EditAction,'subject');

		    $subject_title = $action['title'];
		    $time=time()+$PowerBB->_CONF['info_row']['timestamp'];

	        $SmLogsArr 			= 	array();
			$SmLogsArr['field']	=	array();

			$SmLogsArr['field']['username'] 	= 	$PowerBB->_CONF['member_row']['username'];
			$SmLogsArr['field']['edit_action'] 	= 	$PowerBB->_CONF['template']['_CONF']['lang']['Subject_hide'];
			$SmLogsArr['field']['subject_title']= 	$PowerBB->functions->CleanVariable($subject_title,'sql');
			$SmLogsArr['field']['subject_id'] 	= 	$PowerBB->_GET['subject_id'];
			$SmLogsArr['field']['edit_date'] 	= 	date("d/m/Y", $time);

			$insert = $PowerBB->core->Insert($SmLogsArr,'supermemberlogs');
			  //
				$SecArr 			= 	array();
		        $SecArr['where'] 	= 	array('id',$action['section']);

		        $this->SectionInfo = $PowerBB->core->GetInfo($SecArr,'section');

    		// Update Section Cache

	           $UpdateSectionCache = $PowerBB->functions->UpdateSectionCache($this->SectionInfo['id']);

			//$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Subject_hide_successfully']);
			$PowerBB->functions->header_redirect('index.php?page=topic&amp;show=1&amp;id=' . $PowerBB->_GET['subject_id']);
		}
	}
		function __UnReviewSubject()
	{
		global $PowerBB;

	  	$PowerBB->_GET['subject_id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['subject_id'],'intval');

		if (empty($PowerBB->_GET['subject_id']))
		{
			$PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}

		$SubjectArr = array();
		$SubjectArr['field'] = array();
		$SubjectArr['field']['review_subject'] = 0;

		$SubjectArr['where'] = array('id',$PowerBB->_GET['subject_id']);

		$Update = $PowerBB->subject->UpdateSubject($SubjectArr);

		if ($Update)
		{

					// INSERT moderators Action
			$EditAction				=	array();
		    $EditAction['where'] 	= 	array('id',$PowerBB->_GET['subject_id']);

			$action = $PowerBB->core->GetInfo($EditAction,'subject');

		    $subject_title = $action['title'];
		    $time=time()+$PowerBB->_CONF['info_row']['timestamp'];

	        $SmLogsArr 			= 	array();
			$SmLogsArr['field']	=	array();

			$SmLogsArr['field']['username'] 	= 	$PowerBB->_CONF['member_row']['username'];
			$SmLogsArr['field']['edit_action'] 	= 	$PowerBB->_CONF['template']['_CONF']['lang']['approved_subject'];
			$SmLogsArr['field']['subject_title']= 	$PowerBB->functions->CleanVariable($subject_title,'sql');
			$SmLogsArr['field']['subject_id'] 	= 	$PowerBB->_GET['subject_id'];
			$SmLogsArr['field']['edit_date'] 	= 	date("d/m/Y", $time);

			$insert = $PowerBB->core->Insert($SmLogsArr,'supermemberlogs');

			  //
	           $UpdateSectionCache6 = $PowerBB->functions->UpdateSectionCache($action['section']);


			//$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Was_approved_on_the_subject_successfully']);
			$PowerBB->functions->header_redirect('index.php?page=topic&amp;show=1&amp;id=' . $PowerBB->_GET['subject_id']);
		}
	}



	function _SpecialStart()
	{
		global $PowerBB;

	  	$PowerBB->_GET['subject_id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['subject_id'],'intval');

		if (empty($PowerBB->_GET['subject_id']))
		{
			$PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}

		$SubjectArr = array();
		$SubjectArr['field'] = array();
		$SubjectArr['field']['special'] = 1;

		$SubjectArr['where'] = array('id',$PowerBB->_GET['subject_id']);

		$Update = $PowerBB->subject->UpdateSubject($SubjectArr);

		if ($Update)
		{

					// INSERT moderators Action
			$EditAction				=	array();
		    $EditAction['where'] 	= 	array('id',$PowerBB->_GET['subject_id']);

			$action = $PowerBB->core->GetInfo($EditAction,'subject');

		    $subject_title = $action['title'];
		    $time=time()+$PowerBB->_CONF['info_row']['timestamp'];

	        $SmLogsArr 			= 	array();
			$SmLogsArr['field']	=	array();

			$SmLogsArr['field']['username'] 	= 	$PowerBB->_CONF['member_row']['username'];
			$SmLogsArr['field']['edit_action'] 	= 	$PowerBB->_CONF['template']['_CONF']['lang']['s_special'];
			$SmLogsArr['field']['subject_title']= 	$PowerBB->functions->CleanVariable($subject_title,'sql');
			$SmLogsArr['field']['subject_id'] 	= 	$PowerBB->_GET['subject_id'];
			$SmLogsArr['field']['edit_date'] 	= 	date("d/m/Y", $time);

			$insert = $PowerBB->core->Insert($SmLogsArr,'supermemberlogs');

	    	//$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['special_subject_successfully']);
			$PowerBB->functions->header_redirect('index.php?page=topic&amp;show=1&amp;id=' . $PowerBB->_GET['subject_id']);
		}
	}

	function _NospecialStart()
	{
		global $PowerBB;

	  	$PowerBB->_GET['subject_id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['subject_id'],'intval');

		if (empty($PowerBB->_GET['subject_id']))
		{
			$PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}

		$SubjectArr = array();
		$SubjectArr['field'] = array();
		$SubjectArr['field']['special'] = 0;

		$SubjectArr['where'] = array('id',$PowerBB->_GET['subject_id']);

		$Update = $PowerBB->subject->UpdateSubject($SubjectArr);


		if ($Update)
		{
					// INSERT moderators Action
			$EditAction				=	array();
		    $EditAction['where'] 	= 	array('id',$PowerBB->_GET['subject_id']);

			$action = $PowerBB->core->GetInfo($EditAction,'subject');

		    $subject_title = $action['title'];
		    $time=time()+$PowerBB->_CONF['info_row']['timestamp'];

	        $SmLogsArr 			= 	array();
			$SmLogsArr['field']	=	array();

			$SmLogsArr['field']['username'] 	= 	$PowerBB->_CONF['member_row']['username'];
			$SmLogsArr['field']['edit_action'] 	= 	$PowerBB->_CONF['template']['_CONF']['lang']['nospecial_subject'];
			$SmLogsArr['field']['subject_title']= 	$PowerBB->functions->CleanVariable($subject_title,'sql');
			$SmLogsArr['field']['subject_id'] 	= 	$PowerBB->_GET['subject_id'];
			$SmLogsArr['field']['edit_date'] 	= 	date("d/m/Y", $time);

			$insert = $PowerBB->core->Insert($SmLogsArr,'supermemberlogs');

	    	//$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['nospecial_subject_successfully']);
			$PowerBB->functions->header_redirect('index.php?page=topic&amp;show=1&amp;id=' . $PowerBB->_GET['subject_id']);
		}
	}

	function _MergeIndex()
	{
		global $PowerBB;

	  	$PowerBB->_GET['subject_id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['subject_id'],'intval');

	   if (empty($PowerBB->_GET['subject_id']))
		{
			$PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}
      		$PowerBB->template->assign('subject',$PowerBB->_GET['subject_id']);

	     $PowerBB->template->display('subject_merge_index');

    }


	function _MergeStart()
	{
		global $PowerBB;

         if ($PowerBB->_SERVER['REQUEST_METHOD'] != 'POST')
         {
         	 header("Location: index.php");
		     exit;
         }

		$PowerBB->_GET['subject_id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['subject_id'],'intval');
    		$PowerBB->_POST['url']   = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['url'],'sql');

		if (empty($PowerBB->_GET['subject_id']))
		{
			$PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}

		$SubjectArr 			= 	array();
		$SubjectArr['where'] 	= 	array('id',$PowerBB->_GET['subject_id']);

		$Subject = $PowerBB->core->GetInfo($SubjectArr,'subject');

		if (!$Subject)
		{
			$PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Requested_topic_does_not_exist']);
		}

		if (!isset($PowerBB->_POST['url']))
		{
			$PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_fill_in_all_the_information']);
		}


        $PowerBB->_POST['url'] = str_replace($PowerBB->functions->GetForumAdress()."index.php?page=topic&show=1&id=","", $PowerBB->_POST['url'] );
        $PowerBB->_POST['url'] = str_replace($PowerBB->functions->GetForumAdress()."t","", $PowerBB->_POST['url'] );
        $urlhtml = ".html";
        $PowerBB->_POST['url'] = str_replace($urlhtml,'', $PowerBB->_POST['url'] );
		$PowerBB->_POST['url'] = $PowerBB->functions->CleanVariable($PowerBB->_POST['url'],'intval');

		$SubjecturlArr 			= 	array();
		$SubjecturlArr['where'] 	= 	array('id',$PowerBB->_POST['url']);

		$Subjecturl = $PowerBB->subject->GetSubjectInfo($SubjecturlArr);
       		//$Subjecturl['text'] 	= 	$PowerBB->functions->CleanVariable($Subjecturl['text'],'sql');
      		//$Subject['text'] 	= 	$PowerBB->functions->CleanVariable($Subject['text'],'sql');
      		$Subjecturl['text'] = str_replace("('",'("', $Subjecturl['text']);
      		$Subjecturl['text'] = str_replace("')",'")',$Subjecturl['text']);

      		$Subject['text'] = str_replace("('",'("', $Subject['text']);
      		$Subject['text'] = str_replace("')",'")', $Subject['text']);
      		 if (strstr($Subjecturl['title'],$PowerBB->_CONF['template']['_CONF']['lang']['subject_merged']))
            {
            $PowerBB->_CONF['template']['_CONF']['lang']['subject_merged'] = "";
            $PowerBB->_CONF['template']['_CONF']['lang']['subject_merged_from_multiple_subjects'] = "";
            }
		    $UpdateArr 					= 	array();
		    $UpdateArr['field'] 				= 	array();
		    $UpdateArr['field']['title'] 		= 	$PowerBB->_CONF['template']['_CONF']['lang']['subject_merged'].$Subjecturl['title'];
		    $UpdateArr['field']['text'] 		= 	$Subjecturl['text']."\n".$PowerBB->_CONF['template']['_CONF']['lang']['subject_merged_from_multiple_subjects'];
			$UpdateArr['where'] 				= 	array('id',$PowerBB->_POST['url']);

			$update = $PowerBB->core->Update($UpdateArr,'subject');

                $subject_id = $PowerBB->_GET['subject_id'];
                $Subjectid = $PowerBB->_POST['url'];
				$Move_replys = $PowerBB->DB->sql_query("SELECT  *   FROM " . $PowerBB->table['reply'] . " WHERE subject_id = '$subject_id' ");
				while ($Moved = $PowerBB->DB->sql_fetch_array($Move_replys))
				{
				$UpdateArr 				= 	array();
				$UpdateArr['field']		= 	array();

				$UpdateArr['field']['section'] 		= 	$Subjecturl['section'];
				$UpdateArr['field']['subject_id'] 		= 	$PowerBB->_POST['url'];
				$UpdateArr['where'] 				= 	array('id',$Moved['id']);

				$update = $PowerBB->reply->UpdateReply($UpdateArr);
				}


		     	$ReplyArr 			                = 	array();
		     	$ReplyArr['get_id']					=	true;
		     	$ReplyArr['field']               	= 	array();
		     	$ReplyArr['field']['title'] 		= 	$Subject['title'];
		     	$ReplyArr['field']['text'] 			= 	$Subject['text'];
				$ReplyArr['field']['writer']		= 	$Subject['writer'];
		     	$ReplyArr['field']['subject_id'] 	= 	$PowerBB->_POST['url'];
		     	$ReplyArr['field']['write_time'] 	= 	$Subject['native_write_time'];
		     	$ReplyArr['field']['section'] 		= 	$Subjecturl['section'];
		     	$ReplyArr['field']['icon'] 			= 	$Subject['icon'];

		     	$Insert = $PowerBB->reply->InsertReply($ReplyArr);

		     		//////////

		     		$TimeArr = array();

		     		$TimeArr['write_time'] 	= 	$PowerBB->_CONF['now'];
		     		$TimeArr['where']		=	array('id',$PowerBB->_POST['url']);

		     		$UpdateWriteTime = $PowerBB->subject->UpdateWriteTime($TimeArr);

                    $PagerReplyNumArr = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['reply'] . " WHERE subject_id='$Subjectid' and delete_topic <>1"));

		     		$RepArr 					= 	array();
		     		$RepArr['reply_number']		=	$PagerReplyNumArr;
		     		$RepArr['where'] 			= 	array('id',$PowerBB->_POST['url']);

		     		$UpdateReplyNumber = $PowerBB->subject->UpdateReplyNumber($RepArr);

					$SecArr 			= 	array();
					$SecArr['where'] 	= 	array('id',$Subjecturl['section']);

					$this->SectionInfo = $PowerBB->core->GetInfo($SecArr,'section');

					if($PagerReplyNumArr == '0')
					{
                      $replier = $Subject['writer'];
                      $last_time = $Subject['write_time'];
                      $write_date = $Subject['native_write_time'];
                      $review_reply = $Subject['review_reply'];
                     }
                     else
					{
                    $replierArr = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['reply'] . " WHERE subject_id='$Subjectid' and delete_topic <>1 ORDER BY write_time DESC");
                    $replierInfo = $PowerBB->DB->sql_fetch_array($replierArr);
                      $replier = $replierInfo['writer'];
                      $last_time = $replierInfo['write_time'];
                      $write_date = $replierInfo['write_time'];
                      $review_reply = $replierInfo['review_reply'];
                     }

		     		$LastArr = array();
		     		$LastArr['replier'] 	= 	$replier;
		     		$LastArr['where']		=	array('id',$PowerBB->_POST['url']);

		     		$UpdateLastReplier = $PowerBB->subject->UpdateLastReplier($LastArr);

					$SecLastArr 			= 	array();
					$SecLastArr['where'] 	= 	array('id',$Subjecturl['section']);

					$this->SectionLastInfo = $PowerBB->section->GetSectionInfo($SecLastArr);

		   			//////////

		     		//$UpdateSubjectNumber = $PowerBB->cache->UpdateReplyNumber(array('reply_num'	=>	$PowerBB->_CONF['info_row']['reply_number']));

		     		// Free Lasts in subject
					$UpdateSubjectArr 						= 	array();
					$UpdateSubjectArr['field'] 				= 	array();

					$UpdateSubjectArr['field']['last_replier'] 	= 	$replier;
					$UpdateSubjectArr['field']['last_time'] 	= 	$last_time;
					$UpdateSubjectArr['field']['write_date'] 	= 	$write_date;
					$UpdateSubjectArr['field']['review_reply'] 	= 	$review_reply;
					$UpdateSubjectArr['where'] 				= 	array('id',$Subjecturl['section']);

					$UpdateLastReplier = $PowerBB->subject->UpdateSubject($UpdateSubjectArr);

					// The number of section's reply number
              		$sectionid = $Subjecturl['section'];
                    $ReplyNumArr = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['reply'] . " WHERE section='$sectionid' and delete_topic <>1 and review_reply <>1"));
                    $lastreplierInfo = $PowerBB->DB->sql_fetch_array($ReplyNumArr);
			 		$Subjectid = $PowerBB->_GET['subject_id'];
			        $PagerReplyNumArr = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['reply'] . " WHERE subject_id='$Subjectid' and delete_topic <>1"));
			     		if($PagerReplyNumArr > $PowerBB->_CONF['info_row']['perpage'])
			     		{
							$last_page1 = $PagerReplyNumArr/$PowerBB->_CONF['info_row']['perpage'];
							$last_pagef = round($last_page1, 0);
							$countpage = $last_pagef;
						    $countpage = str_replace("-", '', $countpage);
                         }

					// The number of section's subjects number
		     		$UpdateArr 					= 	array();
		     		$UpdateArr['field']			=	array();

		     		$UpdateArr['field']['reply_num'] 	= 	$ReplyNumArr;
					$UpdateArr['field']['last_writer'] 		= 	$lastreplierInfo['writer'];
		     		$UpdateArr['field']['last_subject'] 		= 	$lastreplierInfo['title'];
		     		$UpdateArr['field']['last_subjectid'] 	= 	$lastreplierInfo['subject_id'];
		     		$UpdateArr['field']['last_date'] 	= 	$replierInfo['write_time'];
		     		$UpdateArr['field']['last_time'] 	= 	$lastreplierInfo['write_time'];
		     		$UpdateArr['field']['last_reply'] 	= 	$lastreplierInfo['id'];
		     		$UpdateArr['field']['icon'] 	    = 	$lastreplierInfo['icon'];
		     		$UpdateArr['field']['last_berpage_nm']  = 	$perpage_r;

		     		$UpdateArr['where']					= 	array('id',$sectionid);

		     		$UpdateSubjectNumber = $PowerBB->core->Update($UpdateArr,'section');

		     		// Free memory
		     		unset($UpdateArr);

		     		//////////

		     		// Update section's cache
		     		$UpdateArr 				= 	array();
		     		$UpdateArr['parent'] 	= 	$this->SectionLastInfo['parent'];

		     		$update_cache = $PowerBB->section->UpdateSectionsCache($UpdateArr);

					$UpdateSectionCache = $PowerBB->functions->UpdateSectionCache($Subject['section']);

		     		unset($UpdateArr);
		     		//////////

		if ($update)
		{

     		// INSERT moderators Action
			$EditAction				=	array();
		    $EditAction['where'] 	= 	array('id',$PowerBB->_GET['subject_id']);

			$action = $PowerBB->core->GetInfo($EditAction,'subject');

		    $subject_title = $action['title'];
		    $time=time()+$PowerBB->_CONF['info_row']['timestamp'];

	        $SmLogsArr 			= 	array();
			$SmLogsArr['field']	=	array();

			$SmLogsArr['field']['username'] 	= 	$PowerBB->_CONF['member_row']['username'];
			$SmLogsArr['field']['edit_action'] 	= 	$PowerBB->_CONF['template']['_CONF']['lang']['Merge_topics'];
			$SmLogsArr['field']['subject_title']= 	$PowerBB->functions->CleanVariable($subject_title,'sql');
			$SmLogsArr['field']['subject_id'] 	= 	$PowerBB->_POST['url'];
			$SmLogsArr['field']['edit_date'] 	= 	date("d/m/Y", $time);

			$insert = $PowerBB->core->Insert($SmLogsArr,'supermemberlogs');

	           $UpdateSectionCache6 = $PowerBB->functions->UpdateSectionCache($action['section']);

			     $DelArr				=	array();
		         $DelArr['where'] 	= 	array('id',$PowerBB->_GET['subject_id']);

				  $del = $PowerBB->core->Deleted($DelArr,'subject');

			//$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Has_been_Merge_topic_successfully']);
	        $PowerBB->functions->header_redirect('index.php?page=topic&amp;show=1&amp;id=' . $PowerBB->_POST['url']);
	}
 }
    //  Start forum tools menu

	function _ToolsThread()
	{
		global $PowerBB;

		$PowerBB->_GET['section'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['section'],'intval');

         if ($PowerBB->_SERVER['REQUEST_METHOD'] != 'POST')
         {
         	 header("Location: index.php?page=forum&show=1&id=".$PowerBB->_GET['section']);
		     exit;
         }
			if ($PowerBB->_POST['deletethread'])
			{
	         $PowerBB->functions->ShowHeader();
			 $this->_deletethread();
        	 $PowerBB->functions->GetFooter();
        	}
			elseif ($PowerBB->_POST['deletetype'] == 1)
			{
				$this->_Trashthreadstart();
			}
			elseif ($PowerBB->_POST['deletetype'] == 2)
			{
				$this->_deletethreadstart();
			}
			elseif ($PowerBB->_POST['undeletethread'])
			{
				$this->_undeletethread();
			}
			elseif ($PowerBB->_POST['openthread'])
			{
				$this->_openthread();
			}
			elseif ($PowerBB->_POST['closethread'])
			{
				$this->_closethread();
			}
			elseif ($PowerBB->_POST['approvethread'])
			{
				$this->_approvethread();
			}
			elseif ($PowerBB->_POST['unapprovethread'])
			{
				$this->_unapprovethread();
			}
			elseif ($PowerBB->_POST['stickthread'])
			{
				$this->_stickthread();
			}
			elseif ($PowerBB->_POST['unstickthread'])
			{
				$this->_unstickthread();
			}
			elseif ($PowerBB->_POST['movethread'])
			{
	            $PowerBB->functions->ShowHeader();
				$this->_movethread();
        	    $PowerBB->functions->GetFooter();
        	}
			elseif ($PowerBB->_POST['movethreadstart'])
			{
				$this->_movethreadstart();
			}
		   elseif ($PowerBB->_POST['deletereplies'])
		   {
		    $this->_DeleteReplies();
		   }

	}


	function _deletethread()
	{
		global $PowerBB;

		if (empty($PowerBB->_POST['check']))
		{
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['You_do_not_select_any_subject']);
		}


	$PowerBB->template->assign('section',$PowerBB->_GET['section']);
	$PowerBB->template->display('subjects_delete_index');

	 $Thread_D = $PowerBB->_POST['check'];

       foreach ($Thread_D as $GetThread)
       {
		$subjects =intval($GetThread);
		$PowerBB->template->assign('subjects',$subjects);
       $PowerBB->template->display('subjects_delete_index2');

		}

	}

	function _Trashthreadstart()
	{
		global $PowerBB;

         if ($PowerBB->_SERVER['REQUEST_METHOD'] != 'POST')
         {
         	 header("Location: index.php");
		     exit;
         }
		if (empty($PowerBB->_POST['check']))
		{
			$PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['You_do_not_select_any_subject']);
		}

		       $Thread_D = $PowerBB->_POST['check'];

       foreach ($Thread_D as $GetThread)
       {

       		      // start Update LastSubjec in Section

			        $GetSecArr 			= 	array();
					$GetSecArr['where'] 	= 	array('id',$PowerBB->_GET['section']);

					$this->GetSectionInfo = $PowerBB->section->GetSectionInfo($GetSecArr);
			      $subjects =intval($GetThread);

		     		//////////

	          	// INSERT moderators Action
				$EditAction				=	array();
			    $EditAction['where'] 	= 	array('id',$subjects);

				$action = $PowerBB->core->GetInfo($EditAction,'subject');

			    $subject_title = $action['title'];
			    $time=time()+$PowerBB->_CONF['info_row']['timestamp'];

		        $SmLogsArr 			= 	array();
				$SmLogsArr['field']	=	array();

				$SmLogsArr['field']['username'] 	= 	$PowerBB->_CONF['member_row']['username'];
				$SmLogsArr['field']['edit_action'] 	= 	$PowerBB->_CONF['template']['_CONF']['lang']['Was_Trasht1'];
				$SmLogsArr['field']['subject_title']= 	$PowerBB->functions->CleanVariable($subject_title,'sql');
				$SmLogsArr['field']['subject_id'] 	= 	$PowerBB->_POST['subject'];
				$SmLogsArr['field']['edit_date'] 	= 	date("d/m/Y", $time);

				$insert = $PowerBB->core->Insert($SmLogsArr,'supermemberlogs');



    		// The number of section's subjects number
        $section = $PowerBB->_GET['section'];

            	// Get Section Form Info
     	$SecFormArr 			= 	array();
		$SecFormArr['where'] 	= 	array('id',$section);

		$SectionFormInfo = $PowerBB->section->GetSectionInfo($SecFormArr);
         $from_section = $PowerBB->_GET['section'];

		if ($SectionFormInfo['last_subjectid'] == $action['id'])
		{
			$GetLastSubject = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['subject'] . " WHERE section = '$from_section' ORDER by write_time DESC");
			$LastSubjectInfo = $PowerBB->DB->sql_fetch_array($GetLastSubject);

			$GetLastReply = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['reply'] . " WHERE section = '$from_section' ORDER by write_time DESC");
			$LastReplyInfo = $PowerBB->DB->sql_fetch_array($GetLastReply);

			if ($LastSubjectInfo['write_time']< $LastReplyInfo['write_time'])
			{
             $LastInfo = $PowerBB->DB->sql_fetch_array($GetLastReply);
             $last_subjectid = $LastInfo['subject_id'];
             $last_berpage_nm = $LastInfo['id'];
			}
			else
			{
              $LastInfo = $PowerBB->DB->sql_fetch_array($GetLastSubject);
              $last_subjectid = $LastInfo['id'];
              $last_berpage_nm = $LastInfo['reply_number'];
			}

				$Subjectid = $last_subjectid;
				$PagerReplyNumArr = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['reply'] . " WHERE subject_id='$Subjectid' and delete_topic <>1"));

				$last_reply = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['reply'] . " WHERE subject_id='$Subjectid' and delete_topic <>1 ORDER by ID DESC limit 0,1");
				$LastReply = $PowerBB->DB->sql_fetch_array($last_reply);
				$PagerReplyNumArr = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['reply'] . " WHERE subject_id='$Subjectid' and delete_topic <>1"));
				if($PagerReplyNumArr > $PowerBB->_CONF['info_row']['perpage'])
				{
					$last_page1 = $PagerReplyNumArr/$PowerBB->_CONF['info_row']['perpage'];
					$last_pagef = round($last_page1, 0);
					$countpage = $last_pagef;
				    $countpage = str_replace("-", '', $countpage);
				}


			// Update Last subject's information in Section Form
     		$UpdateLastFormSecArr = array();
     		$UpdateLastFormSecArr['field']			=	array();

			$UpdateLastFormSecArr['field']['last_writer'] 		= 	$LastInfo['writer'];
     		$UpdateLastFormSecArr['field']['last_subject'] 		= 	$PowerBB->functions->CleanVariable($LastInfo['title'],'html');
     		$UpdateLastFormSecArr['field']['last_subjectid'] 	= 	$last_subjectid;
     		$UpdateLastFormSecArr['field']['last_date'] 	= 	$LastInfo['write_time'];
     		$UpdateLastFormSecArr['field']['last_time'] 	= 	$LastInfo['write_time'];
     		$UpdateLastFormSecArr['field']['icon'] 		    = 	$LastInfo['icon'];
		    $UpdateLastFormSecArr['field']['last_reply'] 	= 	$LastReply['id'];
		    $UpdateLastFormSecArr['field']['last_berpage_nm']  = 	$perpage_r;

     		$UpdateLastFormSecArr['where'] 		        = 	array('id',$from_section);

     		// Update Last To Sec subject's information
     		$UpdateLastFormSec = $PowerBB->section->UpdateSection($UpdateLastFormSecArr);

			if (!$last_subjectid)
			{
			// Update Last subject's information in Section Form
     		$UpdateLastFormSecArr = array();
     		$UpdateLastFormSecArr['field']			=	array();

     		$UpdateLastFormSecArr['field']['last_subject'] 		= 	'';

     		$UpdateLastFormSecArr['where'] 		        = 	array('id',$from_section);
			}

		 }


		$UpdateArr 			= array();
		$UpdateArr['where'] = array('id',$subjects);

		$update = $PowerBB->subject->MoveSubjectToTrash($UpdateArr);


                // Get Section Info
				$SecArr 			= 	array();
				$SecArr['where'] 	= 	array('id',$PowerBB->_GET['section']);

				$this->SectionInfo = $PowerBB->core->GetInfo($SecArr,'section');

                $update_cache = $PowerBB->section->UpdateSectionsCache($UpdateArr);

                $cache = $PowerBB->section->UpdateSectionsCache(array('parent'=>$this->SectionInfo['parent']));



				$cache = $PowerBB->section->UpdateSectionsCache(array('parent'=>$SectionInfo['parent']));
				$Update = $PowerBB->section->UpdateAllSectionsCache();
	           $UpdateSectionCache6 = $PowerBB->functions->UpdateSectionCache($SectionInfo['id']);


       }



            //$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Was_Trasht']);
			$PowerBB->functions->header_redirect('index.php?page=forum&show=1&id=' . $PowerBB->_GET['section']);

	}

	function _deletethreadstart()
	{
		global $PowerBB;

         if ($PowerBB->_SERVER['REQUEST_METHOD'] != 'POST')
         {
         	 header("Location: index.php");
		     exit;
         }

		if ($PowerBB->_CONF['group_info']['group_mod'] == '1')
		{
			if ($PowerBB->_CONF['group_info']['del_subject'] == '0')
			{
			$PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
			}
		}

		if (empty($PowerBB->_POST['check']))
		{
		    $PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['You_do_not_select_any_subject']);
		}

		       $Thread_D = $PowerBB->_POST['check'];

       foreach ($Thread_D as $GetThread)
       {

       		      // start Update LastSubjec in Section

			        $GetSecArr 			= 	array();
					$GetSecArr['where'] 	= 	array('id',$PowerBB->_GET['section']);

					$this->GetSectionInfo = $PowerBB->section->GetSectionInfo($GetSecArr);
			      $subjects =intval($GetThread);

		     		//////////

		         // INSERT moderators Action
				$EditAction				=	array();
			    $EditAction['where'] 	= 	array('id',intval($GetThread));

				$action = $PowerBB->core->GetInfo($EditAction,'subject');

			    $subject_title = intval($action['title']);
			    $time=time()+$PowerBB->_CONF['info_row']['timestamp'];

		        $SmLogsArr 			= 	array();
				$SmLogsArr['field']	=	array();

				$SmLogsArr['field']['username'] 	= 	$PowerBB->_CONF['member_row']['username'];
				$SmLogsArr['field']['edit_action'] 	= 	$PowerBB->_CONF['template']['_CONF']['lang']['Was_rely_delet_subject'];
				$SmLogsArr['field']['subject_title']= 	$PowerBB->_CONF['template']['_CONF']['lang']['Numo_Subject'] .intval($GetThread);
				$SmLogsArr['field']['subject_id'] 	= 	intval($GetThread);
				$SmLogsArr['field']['edit_date'] 	= 	date("d/m/Y", $time);

				$insert = $PowerBB->core->Insert($SmLogsArr,'supermemberlogs');





    		// The number of section's subjects number
        $section = $PowerBB->_GET['section'];

            	// Get Section Form Info
     	$SecFormArr 			= 	array();
		$SecFormArr['where'] 	= 	array('id',$section);

		$SectionFormInfo = $PowerBB->section->GetSectionInfo($SecFormArr);
         $from_section = $PowerBB->_GET['section'];

		     $DelAttachArr				=	array();
	         $DelAttachArr['where'] 	= 	array('subject_id',intval($GetThread));

			  $DeleteAttach = $PowerBB->core->Deleted($DelAttachArr,'attach');

             $subjectInfoid =  intval($GetThread);
             $getTags_query = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['tag_subject'] . " WHERE subject_id = '$subjectInfoid'");
               while ($getTags_row = $PowerBB->DB->sql_fetch_array($getTags_query))
                 {
					  $DeleteTagArr				=	array();
			          $DeleteTagArr['where'] 	= 	array('id',$getTags_row['tag_id']);
					  $delTags = $PowerBB->tag->DeleteTag($DeleteTagArr);
                 }


			  $DeleteSubjectArr				=	array();
	          $DeleteSubjectArr['where'] 	= 	array('subject_id',intval($GetThread));
			  $delSubject = $PowerBB->tag_subject->DeleteSubject($DeleteSubjectArr);

			     $DelAttachArr				=	array();
		         $DelAttachArr['where'] 	= 	array('subject_id',intval($GetThread));

				  $DeleteAttach = $PowerBB->core->Deleted($DelAttachArr,'attach');

				  $DeleteSubjectArr				=	array();
		          $DeleteSubjectArr['where'] 	= 	array('subject_id',intval($GetThread));
				  $delSubject = $PowerBB->tag_subject->DeleteSubject($DeleteSubjectArr);

		     $DelReplyArr				=	array();
	         $DelReplyArr['where'] 	= 	array('subject_id',intval($GetThread));

			  $delReply = $PowerBB->core->Deleted($DelReplyArr,'reply');


				$DelArr				=	array();
				$DelArr['where'] 	= 	array('id',intval($GetThread));

                 $del = $PowerBB->core->Deleted($DelArr,'subject');

        $UpdateSectionCache1 = $PowerBB->functions->UpdateSectionCache($PowerBB->_GET['section']);

       }



           // $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['deleted_topics_successfully']);
			$PowerBB->functions->header_redirect('index.php?page=forum&show=1&id=' . $PowerBB->_GET['section']);

	}


	function _undeletethread()
	{
		global $PowerBB;

		if (empty($PowerBB->_POST['check']))
		{
		    $PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['You_do_not_select_any_subject']);
		}

		$Thread_D = $PowerBB->_POST['check'];

       foreach ($Thread_D as $GetThread)
       {

		$Trash['where']				=	array('id',intval($GetThread));

		$UnTrash = $PowerBB->subject->UnTrashSubject($Trash);

       $UpdateSectionCache2 = $PowerBB->functions->UpdateSectionCache($action['section']);


		    // INSERT moderators Action
			$EditAction				=	array();
		    $EditAction['where'] 	= 	array('id',intval($GetThread));

			$action = $PowerBB->core->GetInfo($EditAction,'subject');

		    $subject_title = $action['title'];
		    $time=time()+$PowerBB->_CONF['info_row']['timestamp'];

	        $SmLogsArr 			= 	array();
			$SmLogsArr['field']	=	array();

			$SmLogsArr['field']['username'] 	= 	$PowerBB->_CONF['member_row']['username'];
			$SmLogsArr['field']['edit_action'] 	= 	$PowerBB->_CONF['template']['_CONF']['lang']['restore_Deleted_Subject'];
			$SmLogsArr['field']['subject_title']= 	$PowerBB->_CONF['template']['_CONF']['lang']['Numo_Subject'] .intval($GetThread);
			$SmLogsArr['field']['subject_id'] 	= 	intval($GetThread);
			$SmLogsArr['field']['edit_date'] 	= 	date("d/m/Y", $time);

			$insert = $PowerBB->core->Insert($SmLogsArr,'supermemberlogs');
        	$UpdateSectionCache6 = $PowerBB->functions->UpdateSectionCache($action['section']);


		}

		if ($insert)
		{
			//$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['restore_topics_successfully']);
			$PowerBB->functions->header_redirect('index.php?page=forum&show=1&id=' . $PowerBB->_GET['section']);
		}

	}

	function _openthread()
	{
		global $PowerBB;

		if (empty($PowerBB->_POST['check']))
		{
		    $PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['You_do_not_select_any_subject']);
		}

		$Thread_D = $PowerBB->_POST['check'];

       foreach ($Thread_D as $GetThread)
       {

        $UpdateArr 			= array();
		$UpdateArr['where'] = array('id',intval($GetThread));

		$update = $PowerBB->subject->OpenSubject($UpdateArr);

       	// INSERT moderators Action
			$EditAction				=	array();
		    $EditAction['where'] 	= 	array('id',intval($GetThread));

			$action = $PowerBB->core->GetInfo($EditAction,'subject');

		    $subject_title = $action['title'];
		    $time=time()+$PowerBB->_CONF['info_row']['timestamp'];

	        $SmLogsArr 			= 	array();
			$SmLogsArr['field']	=	array();

			$SmLogsArr['field']['username'] 	= 	$PowerBB->_CONF['member_row']['username'];
			$SmLogsArr['field']['edit_action'] 	= 	$PowerBB->_CONF['template']['_CONF']['lang']['Subject_open'];
			$SmLogsArr['field']['subject_title']= 	$PowerBB->functions->CleanVariable($subject_title,'sql');
			$SmLogsArr['field']['subject_id'] 	= 	intval($GetThread);
			$SmLogsArr['field']['edit_date'] 	= 	date("d/m/Y", $time);

			$insert = $PowerBB->core->Insert($SmLogsArr,'supermemberlogs');
         }
			//$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['open_topics_successfully']);
			$PowerBB->functions->header_redirect('index.php?page=forum&show=1&id=' . $PowerBB->_GET['section']);


	}

	function _closethread()
	{
		global $PowerBB;

		if (empty($PowerBB->_POST['check']))
		{
		    $PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['You_do_not_select_any_subject']);
		}

		$Thread_D = $PowerBB->_POST['check'];

       foreach ($Thread_D as $GetThread)
       {

		$UpdateArr 				= 	array();
		$UpdateArr['where'] 	= 	array('id',intval($GetThread));

		$update = $PowerBB->subject->CloseSubject($UpdateArr);



			// INSERT moderators Action
			$EditAction				=	array();
		    $EditAction['where'] 	= 	array('id',intval($GetThread));

			$action = $PowerBB->core->GetInfo($EditAction,'subject');

		    $subject_title = $action['title'];
		    $time=time()+$PowerBB->_CONF['info_row']['timestamp'];

	        $SmLogsArr 			= 	array();
			$SmLogsArr['field']	=	array();

			$SmLogsArr['field']['username'] 	= 	$PowerBB->_CONF['member_row']['username'];
			$SmLogsArr['field']['edit_action'] 	= 	$PowerBB->_CONF['template']['_CONF']['lang']['Closing_Subject'];
			$SmLogsArr['field']['subject_title']= 	$PowerBB->functions->CleanVariable($subject_title,'sql');
			$SmLogsArr['field']['subject_id'] 	= 	intval($GetThread);
			$SmLogsArr['field']['edit_date'] 	= 	date("d/m/Y", $time);

			$insert = $PowerBB->core->Insert($SmLogsArr,'supermemberlogs');
          }

			//$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Closing_topics_successfully']);
			$PowerBB->functions->header_redirect('index.php?page=forum&show=1&id=' . $PowerBB->_GET['section']);


	}

	function _approvethread()
	{
		global $PowerBB;

		if (empty($PowerBB->_POST['check']))
		{
		    $PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['You_do_not_select_any_subject']);
		}

		$Thread_D = $PowerBB->_POST['check'];

       foreach ($Thread_D as $GetThread)
       {

		$SubjectArr = array();
		$SubjectArr['field'] = array();
		$SubjectArr['field']['review_subject'] = 0;

		$SubjectArr['where'] = array('id',intval($GetThread));

		$Update = $PowerBB->subject->UpdateSubject($SubjectArr);



			// INSERT moderators Action
			$EditAction				=	array();
		    $EditAction['where'] 	= 	array('id',intval($GetThread));

			$action = $PowerBB->core->GetInfo($EditAction,'subject');

		    $subject_title = $action['title'];
		    $time=time()+$PowerBB->_CONF['info_row']['timestamp'];

	        $SmLogsArr 			= 	array();
			$SmLogsArr['field']	=	array();

			$SmLogsArr['field']['username'] 	= 	$PowerBB->_CONF['member_row']['username'];
			$SmLogsArr['field']['edit_action'] 	= 	$PowerBB->_CONF['template']['_CONF']['lang']['approved_subject'];
			$SmLogsArr['field']['subject_title']= 	$PowerBB->functions->CleanVariable($subject_title,'sql');
			$SmLogsArr['field']['subject_id'] 	= 	intval($GetThread);
			$SmLogsArr['field']['edit_date'] 	= 	date("d/m/Y", $time);

			$insert = $PowerBB->core->Insert($SmLogsArr,'supermemberlogs');
          }

        	$UpdateSectionCache6 = $PowerBB->functions->UpdateSectionCache($action['section']);

			//$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['approved_subjects_successfully']);
			$PowerBB->functions->header_redirect('index.php?page=forum&show=1&id=' . $PowerBB->_GET['section']);


	}

	function _unapprovethread()
	{
		global $PowerBB;

		if (empty($PowerBB->_POST['check']))
		{
		    $PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['You_do_not_select_any_subject']);
		}

		$Thread_D = $PowerBB->_POST['check'];

       foreach ($Thread_D as $GetThread)
       {

		$SubjectArr = array();
		$SubjectArr['field'] = array();
		$SubjectArr['field']['review_subject'] = 1;

		$SubjectArr['where'] = array('id',intval($GetThread));

		$Update = $PowerBB->subject->UpdateSubject($SubjectArr);



			// INSERT moderators Action
			$EditAction				=	array();
		    $EditAction['where'] 	= 	array('id',intval($GetThread));

			$action = $PowerBB->core->GetInfo($EditAction,'subject');

		    $subject_title = $action['title'];
		    $time=time()+$PowerBB->_CONF['info_row']['timestamp'];

	        $SmLogsArr 			= 	array();
			$SmLogsArr['field']	=	array();

			$SmLogsArr['field']['username'] 	= 	$PowerBB->_CONF['member_row']['username'];
			$SmLogsArr['field']['edit_action'] 	= 	$PowerBB->_CONF['template']['_CONF']['lang']['Subject_hide'];
			$SmLogsArr['field']['subject_title']= 	$PowerBB->functions->CleanVariable($subject_title,'sql');
			$SmLogsArr['field']['subject_id'] 	= 	intval($GetThread);
			$SmLogsArr['field']['edit_date'] 	= 	date("d/m/Y", $time);

			$insert = $PowerBB->core->Insert($SmLogsArr,'supermemberlogs');
          }
          $UpdateSectionCache6 = $PowerBB->functions->UpdateSectionCache($action['section']);

			//$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['hide_subjects_successfully']);
			$PowerBB->functions->header_redirect('index.php?page=forum&show=1&id=' . $PowerBB->_GET['section']);


	}

	function _stickthread()
	{
		global $PowerBB;

		if (empty($PowerBB->_POST['check']))
		{
		    $PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['You_do_not_select_any_subject']);
		}

		$Thread_D = $PowerBB->_POST['check'];

       foreach ($Thread_D as $GetThread)
       {

		$UpdateArr 			= array();
		$UpdateArr['where'] = array('id',intval($GetThread));

		$update = $PowerBB->subject->StickSubject($UpdateArr);



			// INSERT moderators Action
			$EditAction				=	array();
		    $EditAction['where'] 	= 	array('id',intval($GetThread));

			$action = $PowerBB->core->GetInfo($EditAction,'subject');

		    $subject_title = $action['title'];
		    $time=time()+$PowerBB->_CONF['info_row']['timestamp'];

	        $SmLogsArr 			= 	array();
			$SmLogsArr['field']	=	array();

			$SmLogsArr['field']['username'] 	= 	$PowerBB->_CONF['member_row']['username'];
			$SmLogsArr['field']['edit_action'] 	= 	$PowerBB->_CONF['template']['_CONF']['lang']['Sticky_Topic'];
			$SmLogsArr['field']['subject_title']= 	$PowerBB->functions->CleanVariable($subject_title,'sql');
			$SmLogsArr['field']['subject_id'] 	= 	intval($GetThread);
			$SmLogsArr['field']['edit_date'] 	= 	date("d/m/Y", $time);

			$insert = $PowerBB->core->Insert($SmLogsArr,'supermemberlogs');
          }

			//$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Stick_subjects_successfully']);
			$PowerBB->functions->header_redirect('index.php?page=forum&show=1&id=' . $PowerBB->_GET['section']);


	}

	function _unstickthread()
	{
		global $PowerBB;

		if (empty($PowerBB->_POST['check']))
		{
		    $PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['You_do_not_select_any_subject']);
		}

		$Thread_D = $PowerBB->_POST['check'];

       foreach ($Thread_D as $GetThread)
       {

		$UpdateArr 			= array();
		$UpdateArr['where'] = array('id',intval($GetThread));

		$update = $PowerBB->subject->UnstickSubject($UpdateArr);



			// INSERT moderators Action
			$EditAction				=	array();
		    $EditAction['where'] 	= 	array('id',intval($GetThread));

			$action = $PowerBB->core->GetInfo($EditAction,'subject');

		    $subject_title = $action['title'];
		    $time=time()+$PowerBB->_CONF['info_row']['timestamp'];

	        $SmLogsArr 			= 	array();
			$SmLogsArr['field']	=	array();

			$SmLogsArr['field']['username'] 	= 	$PowerBB->_CONF['member_row']['username'];
			$SmLogsArr['field']['edit_action'] 	= 	$PowerBB->_CONF['template']['_CONF']['lang']['unstick_Subject'];
			$SmLogsArr['field']['subject_title']= 	$PowerBB->functions->CleanVariable($subject_title,'sql');
			$SmLogsArr['field']['subject_id'] 	= 	intval($GetThread);
			$SmLogsArr['field']['edit_date'] 	= 	date("d/m/Y", $time);

			$insert = $PowerBB->core->Insert($SmLogsArr,'supermemberlogs');
          }

			//$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['unstick_subjects_successfully']);
			$PowerBB->functions->header_redirect('index.php?page=forum&show=1&id=' . $PowerBB->_GET['section']);


	}

	function _movethread()
	{
		global $PowerBB;


		if (empty($PowerBB->_POST['check'])
			or empty($PowerBB->_GET['section']))
		{
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['You_do_not_select_any_subject']);
		}


				// Show Jump List to:)
		$result = $PowerBB->DB->sql_query("SELECT id,title,parent FROM " . $PowerBB->table['section'] . " ORDER BY id DESC");
		$Master = array();
		while ($row = $PowerBB->DB->sql_fetch_array($result)) {
			extract($row);
		    $Master = $PowerBB->core->GetList(array ('id'=>$id,'title'=>"".$title."",'parent'=>$parent."",'parent'=>$parent),'section');
		    $PowerBB->_CONF['template']['foreach']['SecList'] = $PowerBB->core->GetList($Master,'section');
		}

        $PowerBB->template->assign('DoJumpList',$PowerBB->functions->DoJumpList($Master,$url,1));
		unset($Master);
        //

		//////////

		$PowerBB->template->assign('section',$PowerBB->_GET['section']);
		$PowerBB->template->display('subjects_move_index');

	 $Thread_D = $PowerBB->_POST['check'];

       foreach ($Thread_D as $GetThread)
       {
		$subjects =intval($GetThread);
		$PowerBB->template->assign('subjects',$subjects);
       $PowerBB->template->display('subjects_move_index2');
		}

	}

	function _movethreadstart()
	{
		global $PowerBB;

         if ($PowerBB->_SERVER['REQUEST_METHOD'] != 'POST')
         {
         	 header("Location: index.php");
		     exit;
         }

		if (empty($PowerBB->_POST['check']))
		{
		    $PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['You_do_not_select_any_subject']);
		}
		$PowerBB->_POST['section'] = $PowerBB->functions->CleanVariable($PowerBB->_POST['section'],'intval');
		$PowerBB->_GET['section'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['section'],'intval');
        ////
		if (empty($PowerBB->_GET['section']))
		{
			$PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}
		if (empty($PowerBB->_POST['section']))
		{
			$PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Section_does_not_exist']);
		}

		$Thread_D = $PowerBB->_POST['check'];
       foreach ($Thread_D as $GetThread)
       {

      	$subject_id = intval($GetThread);

		$SecArr 			= 	array();
		$SecArr['where'] 	= 	array('id',$PowerBB->_POST['section']);

		$this->SectionInfo = $PowerBB->core->GetInfo($SecArr,'section');


			// INSERT moderators Action
			$EditAction				=	array();
		    $EditAction['where'] 	= 	array('id',intval($GetThread));

			$action = $PowerBB->core->GetInfo($EditAction,'subject');

            $SectionInf = ('<a target="_blank" href="index.php?page=forum&show=1&id=' . $this->SectionInfo['id'] . '">' .$this->SectionInfo['title'] .'</a>');
		    $subject_title = $action['title'];
		    $time=time()+$PowerBB->_CONF['info_row']['timestamp'];

	        $SmLogsArr 			= 	array();
			$SmLogsArr['field']	=	array();

			$SmLogsArr['field']['username'] 	= 	$PowerBB->_CONF['member_row']['username'];
			$SmLogsArr['field']['edit_action'] 	=  	$PowerBB->_CONF['template']['_CONF']['lang']['mov_Subject_to1'] ." ".$PowerBB->_CONF['template']['_CONF']['lang']['mov_Subject_to2']. "". $SectionInf;
			$SmLogsArr['field']['subject_title'] = 	$PowerBB->functions->CleanVariable($subject_title,'sql');
			$SmLogsArr['field']['subject_id'] 	= 	intval($GetThread);
			$SmLogsArr['field']['edit_date'] 	= 	date("d/m/Y", $time);

			$insert = $PowerBB->core->Insert($SmLogsArr,'supermemberlogs');

			      // start Update LastSubjec in Section
			      $subjects =intval($GetThread);
			// End Update Last Subjec in Section from
		$SecidArr 			= 	array();
		$SecidArr['where'] 	= 	array('id',$PowerBB->_GET['section']);

		$this->id_section = $PowerBB->section->GetSectionInfo($SecidArr);

        $id_section = $PowerBB->_GET['section'];


			$UpdateArr 					= 	array();
			$UpdateArr['section_id']	=	$PowerBB->_POST['section'];
			$UpdateArr['where'] 		= 	array('id',intval($GetThread));

			$update = $PowerBB->subject->MoveSubject($UpdateArr);

	        $Move_replys = $PowerBB->DB->sql_query("SELECT  *   FROM " . $PowerBB->table['reply'] . " WHERE subject_id = '$subject_id' ");
	         while ($Moved = $PowerBB->DB->sql_fetch_array($Move_replys))
		         {
						$UpdateArr 				= 	array();
						$UpdateArr['field']		= 	array();

						$UpdateArr['field']['section'] 		= 	$PowerBB->_POST['section'];
						$UpdateArr['where'] 				= 	array('id',$Moved['id']);

						$update = $PowerBB->reply->UpdateReply($UpdateArr);

		         }
       }

          $UpdateSectionCache1 = $PowerBB->functions->UpdateSectionCache($PowerBB->_GET['section']);
          $UpdateSectionCache2 = $PowerBB->functions->UpdateSectionCache($PowerBB->_POST['section']);

            //$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['mov_subjects_successfully']);
			$PowerBB->functions->header_redirect('index.php?page=forum&show=1&id=' . $PowerBB->_POST['section']);


	}

	function _ManagementMainHooks()
	{
		global $PowerBB;

        eval($PowerBB->functions->get_fetch_hooks('management_a'));
	}

	function _ManagementUpdateHooks()
	{
		global $PowerBB;

        eval($PowerBB->functions->get_fetch_hooks('management_b'));

	}


}
?>
