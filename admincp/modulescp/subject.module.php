<?php

(!defined('IN_PowerBB')) ? die() : '';

define('IN_ADMIN',true);

$CALL_SYSTEM				=	array();
$CALL_SYSTEM['SECTION'] 	= 	true;
$CALL_SYSTEM['REPLY'] 		= 	true;
$CALL_SYSTEM['SUBJECT'] 	= 	true;
$CALL_SYSTEM['CACHE'] 		= 	true;



define('CLASS_NAME','PowerBBSubjectMOD');

include('../common.php');
class PowerBBSubjectMOD extends _functions
{
	function run()
	{
		global $PowerBB;

		if ($PowerBB->_CONF['member_permission'])
		{
			$PowerBB->template->display('header');


			if ($PowerBB->_CONF['rows']['group_info']['admincp_subject'] == '0')
			{
			  $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
			}

			if ($PowerBB->_GET['close'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_CloseSubject();
				}
			}
			elseif ($PowerBB->_GET['attach'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_AttachSubject();
				}
			}
			elseif ($PowerBB->_GET['mass_del'])
			{
				if ($PowerBB->_GET['main'])
				{
					if (!in_array($PowerBB->_CONF['rows']['member_row']['id'], preg_split('#\s*,\s*#s', $PowerBB->superadministrators, -1, PREG_SPLIT_NO_EMPTY)))
					{
					 $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
					}
					$this->_MassDelMain();
				}
				elseif ($PowerBB->_GET['confirm'])
				{
					$this->_MassDelConfirm();
				}
				elseif ($PowerBB->_GET['start'])
				{
					$this->_MassDelStart();
				}
			}
			elseif ($PowerBB->_GET['mass_move'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_MassMoveMain();
				}
				elseif ($PowerBB->_GET['start'])
				{
					$this->_MassMoveStart();
				}
			}
			elseif ($PowerBB->_GET['deleted_subject'])
			{
				if ($PowerBB->_GET['main'])
				{
					if (!in_array($PowerBB->_CONF['rows']['member_row']['id'], preg_split('#\s*,\s*#s', $PowerBB->superadministrators, -1, PREG_SPLIT_NO_EMPTY)))
					{
					 $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
					}
					$this->_DeletedSubject();
				}
				elseif ($PowerBB->_GET['start'])
				{
					$this->_DeletedSubjectStart();
				}
			}
			elseif ($PowerBB->_GET['review'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_ReviewSubject();
				}
			}

			$PowerBB->template->display('footer');
		}
	}

	function _CloseSubject()
	{
		global $PowerBB;

		$CloseArr 							= 	array();
		$CloseArr['proc'] 					= 	array();
		$CloseArr['proc']['*'] 				= 	array('method'=>'clean','param'=>'html');

		$CloseArr['where']					=	array();
		$CloseArr['where'][0]				=	array();
		$CloseArr['where'][0]['name']		=	'close';
		$CloseArr['where'][0]['oper']		=	'=';
		$CloseArr['where'][0]['value']		=	'1';

		$CloseArr['order']					=	array();
		$CloseArr['order']['field']			=	'id';
		$CloseArr['order']['type']			=	'DESC';

		$PowerBB->_CONF['template']['while']['CloseList'] = $PowerBB->subject->GetSubjectList($CloseArr);

		$PowerBB->template->display('subjects_closed');
	}

	function _AttachSubject()
	{
		global $PowerBB;

		$AttachArr 							= 	array();
		$AttachArr['proc'] 					= 	array();
		$AttachArr['proc']['*'] 			= 	array('method'=>'clean','param'=>'html');

		$AttachArr['where']					=	array();
		$AttachArr['where'][0]				=	array();
		$AttachArr['where'][0]['name']		=	'attach_subject';
		$AttachArr['where'][0]['oper']		=	'=';
		$AttachArr['where'][0]['value']		=	'1';

		$AttachArr['order']					=	array();
		$AttachArr['order']['field']		=	'id';
		$AttachArr['order']['type']			=	'DESC';

		$PowerBB->_CONF['template']['while']['AttachList'] = $PowerBB->subject->GetSubjectList($AttachArr);

		$PowerBB->template->display('subjects_attach');
	}

	function _MassDelMain()
	{
		global $PowerBB;

		$SecArr 						= 	array();
		$SecArr['get_from']				=	'db';

		$SecArr['proc'] 				= 	array();
		$SecArr['proc']['*'] 			= 	array('method'=>'clean','param'=>'html');

		$SecArr['order']				=	array();
		$SecArr['order']['field']		=	'sort';
		$SecArr['order']['type']		=	'ASC';

		$SecArr['where']				=	array();
		$SecArr['where'][0]['name']		= 	'parent';
		$SecArr['where'][0]['oper']		= 	'!=';
		$SecArr['where'][0]['value']	= 	'0';

		// Get main sections
		$PowerBB->_CONF['template']['while']['SectionList'] = $PowerBB->section->GetSectionsList($SecArr);


		$PowerBB->template->display('subjects_mass_del');
	}

	function _MassDelConfirm()
	{
		global $PowerBB;

		$this->check_section_by_id($PowerBB->_CONF['template']['Inf'],$z);

		$PowerBB->template->display('subjects_mass_del_confirm');
	}

	function _MassDelStart()
	{
		global $PowerBB;

		$this->check_section_by_id($SectionInf,$z);

			  $DeleteSubjectArr				=	array();
	          $DeleteSubjectArr['where'] 	= 	array('section',$SectionInf['id']);
			  $delSubject = $PowerBB->subject->DeleteSubject($DeleteSubjectArr);

		if ($delSubject)
		{
			 $DelReplyArr				=	array();
		     $DelReplyArr['where'] 	= 	array('section',$SectionInf['id']);

			 $delReply = $PowerBB->core->Deleted($DelReplyArr,'reply');

			if ($delReply)
			{

	            // The number of section's subjects number And reply number
	     		$UpdateArr 					= 	array();
	     		$UpdateArr['field']			=	array();

	     		$UpdateArr['field']['subject_num'] 	= 	'0';
	     		$UpdateArr['field']['reply_num'] 	= 	'0';
	     		$UpdateArr['field']['last_writer'] 	= 	'';
	     		$UpdateArr['field']['last_subject'] = 	'';
	     		$UpdateArr['field']['last_subjectid'] 	= 	'';
	     		$UpdateArr['field']['last_date'] 	= 	'';
	     		$UpdateArr['field']['last_reply'] 	= 	'';
	     		$UpdateArr['field']['last_berpage_nm'] 	= 	'';


	     		$UpdateArr['where']					= 	array('id',$SectionInf['id']);

	     		$UpdateSubjectNumber = $PowerBB->core->Update($UpdateArr,'section');

				$SecArr 			= 	array();
				$SecArr['where'] 	= 	array('id',$SectionInf['id']);

				$this->SectionInfo = $PowerBB->core->GetInfo($SecArr,'section');
				$cache = $PowerBB->section->UpdateSectionsCache(array('parent'=>$this->SectionInfo['parent']));
                $UpdateSectionCache = $PowerBB->functions->UpdateSectionCache($SectionInf['id']);

				$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Topic_has_been_deleted_successfully']);
				$PowerBB->functions->redirect('index.php?page=subject&amp;mass_del=1&amp;main=1');
			}
		}
	}

	function _MassMoveMain()
	{
		global $PowerBB;



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
		$cats = $PowerBB->section->GetSectionsList($SecArr);

 		////////////

		// Loop to read the information of main sections
		foreach ($cats as $cat)
		{
			include("../cache/sectiongroup_cache/sectiongroup_cache_".$cat['id'].".php");

             // foreach main sections
			$PowerBB->_CONF['template']['foreach']['forums_list'][$cat['id'] . '_m'] = $cat;

			unset($sectiongroup);

			@include("../cache/forums_cache/forums_cache_".$cat['id'].".php");
			if (!empty($forums_cache))
			{
                $forums = json_decode(base64_decode($forums_cache), true);

					foreach ($forums as $forum)
					{
						//////////////////////////

							$forum['is_sub'] 	= 	0;
							$forum['sub']		=	'';

                              @include("../cache/forums_cache/forums_cache_".$forum['id'].".php");
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
 											$forum['sub'] .= ('<option value="' .$sub['id'] . '" >---'  . $sub['title'] . '</option>');

										  }




					                         ///////////////

													$forum['is_sub_sub'] 	= 	0;
													$forum['sub_sub']		=	'';

		                                       @include("../cache/forums_cache/forums_cache_".$sub['id'].".php");
		                                   if (!empty($forums_cache))
				                           {

												$subs_sub = json_decode(base64_decode($forums_cache), true);
				                               foreach ($subs_sub as $sub_sub)
												{
												   if ($sub['id'] == $sub_sub['parent'])
				                                    {

																	if (!$forum['is_sub_sub'])
																	{
																		$forum['is_sub_sub'] = 1;
																	}

																$forum['sub_sub'] .= ('<option value="' .$sub_sub['id'] . '" >---'  . $sub_sub['title'] . '</option>');

													  }
												 }

										   }
									 }
								}


							$PowerBB->_CONF['template']['foreach']['forums_list'][$forum['id'] . '_f'] = $forum;
							unset($groups);

		             } // end foreach ($forums)
			  } // end !empty($forums_cache)

				unset($SecArr);
				$SecArr = $PowerBB->DB->sql_free_result($SecArr);
		} // end foreach ($cats)

				$PowerBB->template->display('subjects_mass_move');
	}

	function _MassMoveStart()
	{
		global $PowerBB;


				$SecArr 			= 	array();
				$SecArr['where'] 	= 	array('id',$PowerBB->_POST['from']);

				$this->SectionInfo = $PowerBB->core->GetInfo($SecArr,'section');

                 ////
				$SecToInfArr 			= 	array();
				$SecToInfArr['where'] 	= 	array('id',$PowerBB->_POST['to']);

				$this->SectionToInf = $PowerBB->section->GetSectionInfo($SecToInfArr);


	            // The number of section'To subjects number And reply number
	     		$UpdateSecToArr 					= 	array();
	     		$UpdateSecToArr['field']			=	array();

	     		$UpdateSecToArr['field']['subject_num'] 	= 	$this->SectionInfo['subject_num']+$this->SectionToInf['subject_num'];
	     		$UpdateSecToArr['field']['reply_num'] 	    = 	$this->SectionInfo['reply_num']+$this->SectionToInf['reply_num'];
	     		$UpdateSecToArr['field']['last_writer'] 	= 	$this->SectionInfo['last_writer'];
	     		$UpdateSecToArr['field']['last_subject']    = 	$this->SectionInfo['last_subject'];
	     		$UpdateSecToArr['field']['last_subjectid'] 	= 	$this->SectionInfo['last_subjectid'];
	     		$UpdateSecToArr['field']['last_date'] 	    = 	$this->SectionInfo['last_date'];
	     		$UpdateSecToArr['field']['last_reply'] 	    = 	$this->SectionInfo['last_reply'];
	     		$UpdateSecToArr['field']['last_berpage_nm'] = 	$this->SectionInfo['last_berpage_nm'];
	     		$UpdateSecToArr['field']['icon'] 	         = 	$this->SectionInfo['icon'];

	     		$UpdateSecToArr['where']					= 	array('id',$PowerBB->_POST['to']);

	     		$updateSecTo = $PowerBB->section->UpdateSection($UpdateSecToArr);
		$move = array();

		$move[0] = $PowerBB->subject->MassMoveSubject(array('from'	=>	$PowerBB->_POST['from'],'to'	=>	$PowerBB->_POST['to']));

		if ($move[0])
		{
			$move[1] = $PowerBB->reply->MassMoveReply(array('from'	=>	$PowerBB->_POST['from'],'to'	=>	$PowerBB->_POST['to']));

			if ($move[1])
			{



	     		 $cache = $PowerBB->section->UpdateSectionsCache(array('parent'=>$this->SectionToInf['parent']));

	            // The number of section'From subjects number And reply number
	     		$UpdateArr 					= 	array();
	     		$UpdateArr['field']			=	array();

	     		$UpdateArr['field']['subject_num']  	= 	'0';
	     		$UpdateArr['field']['reply_num']    	= 	'0';
	     		$UpdateArr['field']['last_writer']  	= 	'';
	     		$UpdateArr['field']['last_subject']     = 	'';
	     		$UpdateArr['field']['last_subjectid'] 	= 	'';
	     		$UpdateArr['field']['last_date']    	= 	'';
	     		$UpdateArr['field']['last_reply']    	= 	'';
	     		$UpdateArr['field']['last_berpage_nm'] 	= 	'';
	     		$UpdateArr['field']['icon'] 	        = 	'';

	     		$UpdateArr['where']					= 	array('id',$PowerBB->_POST['from']);

	     		$UpdateSubjectNumber = $PowerBB->core->Update($UpdateArr,'section');


     			$cache = $PowerBB->section->UpdateSectionsCache(array('parent'=>$this->SectionInfo['parent']));

		        $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Topic_has_been_moved_successfully']);
		        $PowerBB->functions->redirect('index.php?page=subject&amp;mass_move=1&amp;main=1');

			}
		}
	}

	function _DeletedSubject()
	{
		global $PowerBB;

		//////////

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


		// Loop to read the information of main sections
		foreach ($cats as $cat)
		{
				// foreach main sections
				$PowerBB->_CONF['template']['foreach']['forums_list'][$cat['id'] . '_m'] = $cat;

				// Get main Forums
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
				$ForumArr['where'][0]['value']	= 	$cat['id'];

				// Get parent sections
				$forums = $PowerBB->core->GetList($ForumArr,'section');

				foreach ($forums as $forum)
				{
					// Get parent forums
					$sub_section = $forum['id'];
					$sub_section_num = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['section'] . " WHERE parent = '$sub_section'");
					while($r=$PowerBB->DB->sql_fetch_array($sub_section_num))
					{
					if ($r['id'])
					{
					$forum['is_sub'] = 1;
					}

					$forum['sub'] .= ('<option value="' .$r['id'] . '">---'  . $r['title'] . '</option>');
					}

					 $PowerBB->_CONF['template']['foreach']['forums_list'][$forum['id'] . '_f'] = $forum;
					} // end if is_array
			} // end !empty($forums_cache)

		//////////


	   $PowerBB->template->display('deleted_subjects');
	}

	function _DeletedSubjectStart()
	{
		global $PowerBB;

		if ($PowerBB->_POST['submit1'])
		{
			if (!$PowerBB->_POST['options_1'] == '0')
			{
	 		     $deys = ($PowerBB->_CONF['now'] - ($PowerBB->_POST['options_1'] * 86400));
	             $get_subject = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['subject'] . " WHERE write_time <= $deys");
		       while ($row = $PowerBB->DB->sql_fetch_array($get_subject))
		       {
				  $DeleteSubjectArr				=	array();
		          $DeleteSubjectArr['where'] 	= 	array('id',$row['id']);
				  $delSubject = $PowerBB->subject->DeleteSubject($DeleteSubjectArr);
				 if ($delSubject)
				 {
					 $DelReplyArr				=	array();
				     $DelReplyArr['where'] 	= 	array('subject_id',$row['id']);

					 $delReply = $PowerBB->core->Deleted($DelReplyArr,'reply');

				  }
				  $PowerBB->functions->UpdateSectionCache($row['section']);
		        }
				 if ($delSubject
				 or $delReply)
				 {
					$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Topic_has_been_deleted_successfully'].'<br /><a href="index.php?page=subject&amp;deleted_subject=1&amp;main=1"><b>عودة</b></a>');
				 }
				 else
				 {
					$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Was_not_found_on_any_topics'].'<br /><a href="index.php?page=subject&amp;deleted_subject=1&amp;main=1">'.$PowerBB->_CONF['template']['_CONF']['lang']['Return'].'</a>');
				 }
			}

			if (!$PowerBB->_POST['options_2'] == '0')
			{
	 		     $deys = ($PowerBB->_CONF['now'] - ($PowerBB->_POST['options_2'] * 86400));
	             $get_subject = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['reply'] . " WHERE write_time <= $deys");
		       while ($row = $PowerBB->DB->sql_fetch_array($get_subject))
		       {
				  $DeleteSubjectArr				=	array();
		          $DeleteSubjectArr['where'] 	= 	array('id',$row['subject_id']);
				  $delSubject = $PowerBB->subject->DeleteSubject($DeleteSubjectArr);
				 if ($delSubject)
				 {
					 $DelReplyArr				=	array();
				     $DelReplyArr['where'] 	= 	array('id',$row['id']);

					 $delReply = $PowerBB->core->Deleted($DelReplyArr,'reply');

				  }
				  $PowerBB->functions->UpdateSectionCache($row['section']);
		        }
				 if ($delSubject
				 or $delReply)
				 {
					$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Topic_has_been_deleted_successfully'].'<br /><a href="index.php?page=subject&amp;deleted_subject=1&amp;main=1"><b>عودة</b></a>');
				 }
				 else
				 {
					$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Was_not_found_on_any_topics'].'<br /><a href="index.php?page=subject&amp;deleted_subject=1&amp;main=1">'.$PowerBB->_CONF['template']['_CONF']['lang']['Return'].'</a>');
				 }
			}
		  }
		//////  submit2
		if ($PowerBB->_POST['submit2'])
		{
			if (!empty($PowerBB->_POST['posts_num']))
			{
	 		     $posts_num = $PowerBB->_POST['posts_num'];
	             $get_subject = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['subject'] . " WHERE reply_number<$posts_num");
		       while ($row = $PowerBB->DB->sql_fetch_array($get_subject))
		       {
				  $DeleteSubjectArr				=	array();
		          $DeleteSubjectArr['where'] 	= 	array('id',$row['id']);
				  $delSubject = $PowerBB->subject->DeleteSubject($DeleteSubjectArr);
				 if ($delSubject)
				 {
					 $DelReplyArr				=	array();
				     $DelReplyArr['where'] 	= 	array('subject_id',$row['id']);

					 $delReply = $PowerBB->core->Deleted($DelReplyArr,'reply');

				  }
				  $PowerBB->functions->UpdateSectionCache($row['section']);
		        }
				 if ($delSubject
				 or $delReply)
				 {
					$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Topic_has_been_deleted_successfully'].'<br /><a href="index.php?page=subject&amp;deleted_subject=1&amp;main=1"><b>عودة</b></a>');
				 }
				 else
				 {
					$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Was_not_found_on_any_topics'].'<br /><a href="index.php?page=subject&amp;deleted_subject=1&amp;main=1">'.$PowerBB->_CONF['template']['_CONF']['lang']['Return'].'</a>');
				 }
			}

			if (!empty($PowerBB->_POST['view_num']))
			{
	 		     $view_num = $PowerBB->_POST['view_num'];
	             $get_subject = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['subject'] . " WHERE visitor<$view_num");
		       while ($row = $PowerBB->DB->sql_fetch_array($get_subject))
		       {
				  $DeleteSubjectArr				=	array();
		          $DeleteSubjectArr['where'] 	= 	array('id',$row['id']);
				  $delSubject = $PowerBB->subject->DeleteSubject($DeleteSubjectArr);
				 if ($delSubject)
				 {
					 $DelReplyArr				=	array();
				     $DelReplyArr['where'] 	= 	array('subject_id',$row['id']);

					 $delReply = $PowerBB->core->Deleted($DelReplyArr,'reply');

				  }
				  $PowerBB->functions->UpdateSectionCache($row['section']);
		        }
				 if ($delSubject
				 or $delReply)
				 {
					$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Topic_has_been_deleted_successfully'].'<br /><a href="index.php?page=subject&amp;deleted_subject=1&amp;main=1"><b>عودة</b></a>');
				 }
				 else
				 {
					$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Was_not_found_on_any_topics'].'<br /><a href="index.php?page=subject&amp;deleted_subject=1&amp;main=1">'.$PowerBB->_CONF['template']['_CONF']['lang']['Return'].'</a>');
				 }
			}
		  }

		//////  submit3
		if ($PowerBB->_POST['submit3'])
		{
			if (empty($PowerBB->_POST['user_name']))
			{
	          $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Enter_the_name_of_a_member']);
			}

			if ($PowerBB->_POST['forum'] == 'all')
			{
	 		     $user_name = $PowerBB->_POST['user_name'];
		         $SubjectNm = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['subject'] . " WHERE writer='$user_name'"));
		         $ReplyNm = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['reply'] . " WHERE writer='$user_name'"));

	             $get_subject = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['subject'] . " WHERE writer='$user_name'");
		       while ($row = $PowerBB->DB->sql_fetch_array($get_subject))
		       {
				  $DeleteSubjectArr				=	array();
		          $DeleteSubjectArr['where'] 	= 	array('id',$row['id']);
				  $delSubject = $PowerBB->subject->DeleteSubject($DeleteSubjectArr);
				 if ($delSubject)
				 {
					 $DelReplyArr				=	array();
				     $DelReplyArr['where'] 	= 	array('subject_id',$row['id']);

					 $delReply = $PowerBB->core->Deleted($DelReplyArr,'reply');

				  }
				  $PowerBB->functions->UpdateSectionCache($row['section']);
		        }

					$MemArr 			= 	array();
					$MemArr['where'] 	= 	array('username',$user_name);

					$MemInfo = $PowerBB->member->GetMemberInfo($MemArr);

		            $posts = $MemInfo['posts']-$SubjectNm+$ReplyNm;
			   		$MemberArr 				= 	array();
			   		$MemberArr['field'] 	= 	array();

		     		$MemberArr['field']['posts']			=	$posts;
		     		$MemberArr['where']						=	array('username',$user_name);

		   			$UpdateMember = $PowerBB->member->UpdateMember($MemberArr);

				 if ($delSubject
				 or $delReply
				 or $UpdateMember)
				 {
					$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Topic_has_been_deleted_successfully'].'<br /><a href="index.php?page=subject&amp;deleted_subject=1&amp;main=1"><b>عودة</b></a>');
				 }
				 else
				 {
					$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Was_not_found_on_any_topics'].'<br /><a href="index.php?page=subject&amp;deleted_subject=1&amp;main=1">'.$PowerBB->_CONF['template']['_CONF']['lang']['Return'].'</a>');
				 }
			}
			 else
			{
 		         $user_name = $PowerBB->_POST['user_name'];
	 		     $forum = $PowerBB->_POST['forum'];
		         $SubjectNm = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['subject'] . " WHERE section='$forum' and writer='$user_name'"));
		         $ReplyNm = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['reply'] . " WHERE section='$forum' and writer='$user_name'"));

	             $get_subject = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['subject'] . " WHERE section='$forum' and writer='$user_name'");
		       while ($row = $PowerBB->DB->sql_fetch_array($get_subject))
		       {
				  $DeleteSubjectArr				=	array();
		          $DeleteSubjectArr['where'] 	= 	array('id',$row['id']);
				  $delSubject = $PowerBB->subject->DeleteSubject($DeleteSubjectArr);
				 if ($delSubject)
				 {
					 $DelReplyArr				=	array();
				     $DelReplyArr['where'] 	= 	array('subject_id',$row['id']);

					 $delReply = $PowerBB->core->Deleted($DelReplyArr,'reply');

				  }
				  $PowerBB->functions->UpdateSectionCache($row['section']);
				  $PowerBB->functions->UpdateSectionCache($forum);
		        }

					$MemArr 			= 	array();
					$MemArr['where'] 	= 	array('username',$user_name);

					$MemInfo = $PowerBB->member->GetMemberInfo($MemArr);

		            $posts = $MemInfo['posts']-$SubjectNm+$ReplyNm;
			   		$MemberArr 				= 	array();
			   		$MemberArr['field'] 	= 	array();

		     		$MemberArr['field']['posts']			=	$posts;
		     		$MemberArr['where']						=	array('username',$user_name);

		   			$UpdateMember = $PowerBB->member->UpdateMember($MemberArr);

				 if ($delSubject
				 or $delReply
				 or $UpdateMember)
				 {
					$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Topic_has_been_deleted_successfully'].'<br /><a href="index.php?page=subject&amp;deleted_subject=1&amp;main=1"><b>عودة</b></a>');
				 }
				 else
				 {
					$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Was_not_found_on_any_topics'].'<br /><a href="index.php?page=subject&amp;deleted_subject=1&amp;main=1">'.$PowerBB->_CONF['template']['_CONF']['lang']['Return'].'</a>');
				 }

			}


		  }

	}
}

class _functions
{
	function check_section_by_id(&$Inf,&$ToInf,$move=false)
	{
		global $PowerBB;

		if (!$move)
		{
			if (empty($PowerBB->_GET['id']))
			{
				$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['The_request_is_not_valid']);
			}

			$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');

			$SecArr 			= 	array();
			$SecArr['where'] 	= 	array('id',$PowerBB->_GET['id']);

			$Inf = $PowerBB->core->GetInfo($SecArr,'section');

			if ($Inf == false)
			{
				$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['ForuM_requested_does_not_exist']);
			}

			$PowerBB->functions->CleanVariable($Inf,'html');
		}
		else
		{
			if (empty($PowerBB->_POST['from'])
				or empty($PowerBB->_POST['to']))
			{
				$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['The_request_is_not_valid']);
			}


			$SecArr 			= 	array();
			$SecArr['where'] 	= 	array('id',$PowerBB->_POST['from']);

			$Inf = $PowerBB->core->GetInfo($SecArr,'section');

			$ToArr 				= 	array();
			$ToArr['where'] 	= 	array('id',$PowerBB->_POST['to']);

			$ToInf = $PowerBB->section->GetSectionInfo($ToArr);

			if ($Inf == false)
			{
				$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['ForuM_requested_does_not_exist']);
			}
			elseif ($ToInf == false)
			{
				$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['ForuM_requested_does_not_exist']);
			}

			$PowerBB->functions->CleanVariable($Inf,'html');
			$PowerBB->functions->CleanVariable($ToInf,'html');
		}
	}

	function _ReviewSubject()
	{
		global $PowerBB;

		$ReviewArr 							= 	array();
		$ReviewArr['proc'] 					= 	array();
		$ReviewArr['proc']['*'] 				= 	array('method'=>'clean','param'=>'html');

		$ReviewArr['where']					=	array();
		$ReviewArr['where'][0]				=	array();
		$ReviewArr['where'][0]['name']		=	'review_subject';
		$ReviewArr['where'][0]['oper']		=	'=';
		$ReviewArr['where'][0]['value']		=	'1';

		$ReviewArr['order']					=	array();
		$ReviewArr['order']['field']			=	'id';
		$ReviewArr['order']['type']			=	'DESC';

		$PowerBB->_CONF['template']['while']['ReviewList'] = $PowerBB->subject->GetSubjectList($ReviewArr);

		$PowerBB->template->display('subjects_reviewd');
	}


}

?>
