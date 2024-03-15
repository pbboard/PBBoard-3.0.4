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
                elseif ($PowerBB->_GET['do_review_subject'])
				{
					$this->_Do_Review_Subject();
				}
				elseif ($PowerBB->_GET['review_reply'])
				{				  if ($PowerBB->_GET['main_replys'])
				  {
					$this->_Review_Reply();
				  }
				 elseif ($PowerBB->_GET['do_review_reply'])
				  {
					$this->_Do_Review_Reply();
				  }
				}
			}

			$PowerBB->template->display('footer');
		}
	}

	function _CloseSubject()
	{
		global $PowerBB;

		$PowerBB->_GET['count'] = (!isset($PowerBB->_GET['count'])) ? 0 : $PowerBB->_GET['count'];
		$PowerBB->_GET['count'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['count'],'intval');

 	    $close_nm = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(1),id FROM " . $PowerBB->table['subject'] . " WHERE close = 1 "));
        $subject_perpage = '32';

		$CloseArr 					= 	array();
		$CloseArr['order']			=	array();
		$CloseArr['order']['field']	=	'id';
		$CloseArr['order']['type']	=	'DESC';
		$CloseArr['proc'] 			= 	array();
		$CloseArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');
		$CloseArr['where']					=	array();
		$CloseArr['where'][0]				=	array();
		$CloseArr['where'][0]['name']		=	'close';
		$CloseArr['where'][0]['oper']		=	'=';
		$CloseArr['where'][0]['value']		=	'1';

		$CloseArr['pager'] 				= 	array();
		$CloseArr['pager']['total']		= 	$close_nm;
		$CloseArr['pager']['perpage'] 	= 	$subject_perpage;
		$CloseArr['pager']['count'] 		= 	$PowerBB->_GET['count'];
		$CloseArr['pager']['location'] 	= 	'index.php?page=subject&amp;close=1&amp;main=1';
		$CloseArr['pager']['var'] 		= 	'count';

		$PowerBB->_CONF['template']['while']['CloseList'] = $PowerBB->core->GetList($CloseArr,'subject');
        if ($close_nm > $subject_perpage)
        {
		$PowerBB->template->assign('pager',$PowerBB->pager->show());
        }
        $PowerBB->template->assign('CloseNumber',$close_nm);

		$PowerBB->template->display('subjects_closed');
	}

	function _AttachSubject()
	{
		global $PowerBB;
		$PowerBB->_GET['count'] = (!isset($PowerBB->_GET['count'])) ? 0 : $PowerBB->_GET['count'];
		$PowerBB->_GET['count'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['count'],'intval');

 	    $attach_nm = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(1),id FROM " . $PowerBB->table['subject'] . " WHERE attach_subject = 1 "));
        $subject_perpage = '32';

		$AttachArr 					= 	array();
		$AttachArr['order']			=	array();
		$AttachArr['order']['field']	=	'id';
		$AttachArr['order']['type']	=	'DESC';
		$AttachArr['proc'] 			= 	array();
		$AttachArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');
		$AttachArr['where']					=	array();
		$AttachArr['where'][0]				=	array();
		$AttachArr['where'][0]['name']		=	'attach_subject';
		$AttachArr['where'][0]['oper']		=	'=';
		$AttachArr['where'][0]['value']		=	'1';

		$AttachArr['pager'] 				= 	array();
		$AttachArr['pager']['total']		= 	$attach_nm;
		$AttachArr['pager']['perpage'] 	= 	$subject_perpage;
		$AttachArr['pager']['count'] 		= 	$PowerBB->_GET['count'];
		$AttachArr['pager']['location'] 	= 	'index.php?page=subject&amp;attach=1&amp;main=1';
		$AttachArr['pager']['var'] 		= 	'count';

		$PowerBB->_CONF['template']['while']['AttachList'] = $PowerBB->core->GetList($AttachArr,'subject');
        if ($attach_nm > $subject_perpage)
        {
		$PowerBB->template->assign('pager',$PowerBB->pager->show());
        }
        $PowerBB->template->assign('AttachNumber',$attach_nm);
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
		$SecArr['where'][0]['oper']		= 	'>';
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
           // foreach main sections
			$PowerBB->_CONF['template']['foreach']['forums_list'][$cat['id'] . '_m'] = $cat;

			if($PowerBB->_CONF['files_forums_Cache'])
			{
			@include("../cache/forums_cache/forums_cache_".$cat['id'].".php");
			}
			else
			{
			$forums_cache = $cat['forums_cache'];
			}
			if (!empty($forums_cache))
			{
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
				$SecArr['where'][0]['value']	= 	$cat['id'];
				$forums = $PowerBB->core->GetList($SecArr,'section');
	                foreach($forums as $forum)
					{


								if($PowerBB->_CONF['files_forums_Cache'])
								{
								@include("../cache/forums_cache/forums_cache_".$forum['id'].".php");
								}
								elseif($PowerBB->_CONF['forums_parent_direct'])
								{
								$forums_cache = $forum['forums_cache'];
								}

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
														if ($sub['id'])
														{
														$forum['is_sub'] = 1;
														}

												$forum['sub'] .= ('<option value="' .$sub['id'] . '" >---'  . $sub['title'] . '</option>');


                                                   // subs forum ++
													if($PowerBB->_CONF['files_forums_Cache'])
													{
													@include("../cache/forums_cache/forums_cache_".$sub['id'].".php");
													}
													elseif($PowerBB->_CONF['forums_parent_direct'])
													{
													$forums_cache = $sub['forums_cache'];
													}
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
															  $forum['sub'] .= ('<option value="' .$subforum['id'] . '" >----'  . $subforum['title'] . '</option>');
                                                             }
                                                              // subs forum +++
																if($PowerBB->_CONF['files_forums_Cache'])
																{
																@include("../cache/forums_cache/forums_cache_".$subforum['id'].".php");
																}
																elseif($PowerBB->_CONF['forums_parent_direct'])
																{
																$forums_cache = $subforum['forums_cache'];
																}

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
																	  $forum['sub'] .= ('<option value="' .$sub4forum['id'] . '" >-----'  . $sub4forum['title'] . '</option>');
                                                                     }

																	   // subs forum ++++
																		if($PowerBB->_CONF['files_forums_Cache'])
																		{
																		@include("../cache/forums_cache/forums_cache_".$sub4forum['id'].".php");
																		}
																		elseif($PowerBB->_CONF['forums_parent_direct'])
																		{
																		$forums_cache = $sub4forum['forums_cache'];
																		}

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
																				   $forum['sub'] .= ('<option value="' .$sub5forum['id'] . '" >------'  . $sub5forum['title'] . '</option>');
                                                                                 }

                                                                                       // subs forum +++++
																						if($PowerBB->_CONF['files_forums_Cache'])
																						{
																						@include("../cache/forums_cache/forums_cache_".$sub5forum['id'].".php");
																						}
																						elseif($PowerBB->_CONF['forums_parent_direct'])
																						{
																						$forums_cache = $sub5forum['forums_cache'];
																						}

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
																							   $forum['sub'] .= ('<option value="' .$sub6forum['id'] . '" >-------'  . $sub6forum['title'] . '</option>');
                                                                                             }
																							}
													                                   }


																			}
									                                   }
																}
						                                   }


														}
				                                   }
                                                   //

                                               }


                                            $xs += 1;

										 }

								   }



							$PowerBB->_CONF['template']['foreach']['forums_list'][$forum['id'] . '_f'] = $forum;
							unset($groups);
						}// end view forums
                         $x += 1;
		             } // end foreach ($forums)

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

		$PowerBB->_GET['count'] = (!isset($PowerBB->_GET['count'])) ? 0 : $PowerBB->_GET['count'];
		$PowerBB->_GET['count'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['count'],'intval');

 	    $review_nm = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(1),id FROM " . $PowerBB->table['subject'] . " WHERE review_subject = 1 "));
        $subject_perpage = $PowerBB->_CONF['info_row']['subject_perpage'];

		$ReviewArr 					= 	array();
		$ReviewArr['order']			=	array();
		$ReviewArr['order']['field']	=	'id';
		$ReviewArr['order']['type']	=	'DESC';
		$ReviewArr['proc'] 			= 	array();
		$ReviewArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');
		$ReviewArr['where']					=	array();
		$ReviewArr['where'][0]				=	array();
		$ReviewArr['where'][0]['name']		=	'review_subject';
		$ReviewArr['where'][0]['oper']		=	'=';
		$ReviewArr['where'][0]['value']		=	'1';

		$ReviewArr['pager'] 				= 	array();
		$ReviewArr['pager']['total']		= 	$review_nm;
		$ReviewArr['pager']['perpage'] 	= 	$subject_perpage;
		$ReviewArr['pager']['count'] 		= 	$PowerBB->_GET['count'];
		$ReviewArr['pager']['location'] 	= 	'index.php?page=subject&amp;review=1&amp;main=1';
		$ReviewArr['pager']['var'] 		= 	'count';

		$PowerBB->_CONF['template']['while']['ReviewList'] = $PowerBB->core->GetList($ReviewArr,'subject');
        if ($review_nm > $subject_perpage)
        {
		$PowerBB->template->assign('pager',$PowerBB->pager->show());
        }
        $PowerBB->template->assign('ReviewNumber',$review_nm);
        $PowerBB->template->assign('Reviewreplys','1');
		$PowerBB->template->display('subjects_reviewd');
	}


	function _Do_Review_Subject()
	{
       global $PowerBB;

		if ($PowerBB->_POST['deletposts'])
		{

		if ($PowerBB->_CONF['group_info']['group_mod'] == '1')
		{
		if ($PowerBB->_CONF['group_info']['del_subject'] == '0')
		{
		$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
		}
		}

		if (empty($PowerBB->_POST['check']))
		{
		$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Choose_incorrectl']);
		}

		$Subject_D = $PowerBB->_POST['check'];
		foreach ($Subject_D as $GetSubject)
		{

		$UpdateArr 				= 	array();
		$UpdateArr['field']		= 	array();
		$UpdateArr['field']['review_subject'] 		= 	'0';
		$UpdateArr['where'] 				= 	array('id',intval($GetSubject));

		$update = $PowerBB->subject->UpdateSubject($UpdateArr);

		$SubjectArr 			= 	array();
		$SubjectArr['where'] 	= 	array('id',intval($GetSubject));

		$this->SubjectInfo = $PowerBB->core->GetInfo($SubjectArr,'subject');


		$Subjectid =  $this->SubjectInfo['id'];
		$review_subject_nm = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['subject'] . " WHERE id='$Subjectid' and review_subject='1' "));
		$subject_nm = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['subject'] . " WHERE section = '$section' and id='$Subjectid' "));

		$SecArr 			= 	array();
		$SecArr['where'] 	= 	array('id',$this->SubjectInfo['section']);

		$this->SectionInfo = $PowerBB->core->GetInfo($SecArr,'section');



		$SubjectArr = array();
		$SubjectArr['where'] = array('id',$this->SubjectInfo['id']);

		$SubjectInfo = $PowerBB->core->GetInfo($SubjectArr,'subject');


		$subject_id = $this->SubjectInfo['id'];
		$Getlast_Subjectr = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['subject'] . " WHERE id = '$subject_id' AND delete_topic<>1 AND review_subject<>1 ORDER by write_time DESC");
		$GetLast_SubjectForm = $PowerBB->DB->sql_fetch_array($Getlast_Subjectr);
		if (!$GetLast_SubjectForm)
		{
		$last_Subjectr = '';
		}
		else
		{
		$last_Subjectr = $GetLast_replierForm['writer'];
		}

		// Update Subject
		if (empty($GetLast_SubjectForm['write_time']))
		{
		$write_time = $SubjectInfo['native_write_time'];
		}
		else
		{
		$write_time = $GetLast_SubjectForm['write_time'];
		}


		if ($this->SectionInfo['last_subjectid'] == $subject_id)
		{
		/**
		*Update Section Cache ;)
		*/
		$SectionCache = $this->SubjectInfo['section'];
		// The number of section's subjects number
		$subject_num = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT id FROM " . $PowerBB->table['subject'] . " WHERE section = '$SectionCache' "));

		$UpdateArr 					= 	array();
		$UpdateArr['field']			=	array();
		$UpdateArr['field']['subject_num'] 	= 	$subject_num;
		$UpdateArr['where']					= 	array('id',$SectionCache);

		$UpdateSubjectNumber = $PowerBB->core->Update($UpdateArr,'section');


		$PowerBB->cache->UpdateSubjectNumber(array('subject_num'	=>	$subject_num));


		$subject_nm = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT id FROM " . $PowerBB->table['subject'] . " WHERE section = '$SectionCache' "));

		// The number of section's subjects number
		$UpdateArr 					= 	array();
		$UpdateArr['field']			=	array();

		$UpdateArr['field']['subject_num'] 	= 	$subject_nm;
		$UpdateArr['where']					= 	array('id',$SectionCache);

		$UpdateSubjectNumber = $PowerBB->core->Update($UpdateArr,'section');


		$PowerBB->cache->UpdateSubjectNumber(array('subject_num'	=>	$subject_nm));

		$GetLastquerySubjectForm = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['subject'] . " WHERE id = '$subject_id' AND delete_topic<>1 ORDER by write_time DESC");
		$GetLastSubjectForm = $PowerBB->DB->sql_fetch_array($GetLastquerySubjectForm);

		if (!$GetLastReplyForm)
		{
		// Update Last subject's information in Section Form
		$UpdateLastFormSecArr = array();
		$UpdateLastFormSecArr['field']			=	array();

		$UpdateLastFormSecArr['field']['last_writer'] 		= 	$SubjectInfo['writer'];
		$UpdateLastFormSecArr['field']['last_subject'] 		= 	$SubjectInfo['title'];
		$UpdateLastFormSecArr['field']['last_subjectid'] 	= 	$subject_id;
		$UpdateLastFormSecArr['field']['last_date'] 	= 	$PowerBB->_CONF['now'];
		$UpdateLastFormSecArr['field']['last_time'] 	= 	$PowerBB->_CONF['now'];
		$UpdateLastFormSecArr['field']['icon'] 		    = 	$SubjectInfo['icon'];
		$UpdateLastFormSecArr['field']['last_reply'] 	= 	0;
		$UpdateLastFormSecArr['field']['last_berpage_nm']  = 	0;

		$UpdateLastFormSecArr['where'] 		        = 	array('id',$SectionCache);

		// Update Last Form Sec subject's information
		$UpdateLastFormSec = $PowerBB->section->UpdateSection($UpdateLastFormSecArr);
		}
		else
		{

		// Update Last subject's information in Section Form
		$UpdateLastFormSecArr = array();
		$UpdateLastFormSecArr['field']			=	array();

		$UpdateLastFormSecArr['field']['last_writer'] 		= 	$GetLastSubjectForm['writer'];
		$UpdateLastFormSecArr['field']['last_subject'] 		= 	$GetLastSubjectForm['title'];
		$UpdateLastFormSecArr['field']['last_subjectid'] 	= 	$GetLastSubjectForm['id'];
		$UpdateLastFormSecArr['field']['last_date'] 	= 	$GetLastSubjectForm['write_time'];
		$UpdateLastFormSecArr['field']['last_time'] 	= 	$GetLastSubjectForm['write_time'];
		$UpdateLastFormSecArr['field']['icon'] 		    = 	$GetLastSubjectForm['icon'];
		$UpdateLastFormSecArr['field']['last_reply'] 	= 	0;
		$UpdateLastFormSecArr['field']['last_berpage_nm']  = 	0;

		$UpdateLastFormSecArr['where'] 		        = 	array('id',$SectionCache);

		// Update Last Form Sec subject's information
		$UpdateLastFormSec = $PowerBB->section->UpdateSection($UpdateLastFormSecArr);
		}

		// Get Section Info
		$SecaArr 			= 	array();
		$SecaArr['where'] 	= 	array('id',$SectionCache);

		$this->SecInfo = $PowerBB->section->GetSectionInfo($SecaArr);

		// Update section's cache
		$UpdateArr 				= 	array();
		$UpdateArr['parent'] 	= 	$this->SecInfo['parent'];

		$update_cache = $PowerBB->section->UpdateSectionsCache($UpdateArr);


		unset($UpdateArr);
		}

		$DeleteSubjectArr				=	array();
		$DeleteSubjectArr['where'] 	= 	array('id',intval($GetSubject));
		$delSubject = $PowerBB->subject->DeleteSubject($DeleteSubjectArr);

		$UpdateSectionCache1 = $PowerBB->functions->UpdateSectionCache($this->SubjectInfo['section']);

		}

		$UpdateSubjectNumber = $PowerBB->cache->UpdateSubjectNumber(array('subject_num'	=>	$PowerBB->_CONF['info_row']['subject_number']));

		$PowerBB->functions->redirect($PowerBB->_SERVER['HTTP_REFERER']);

 		}
		elseif($PowerBB->_POST['approveposts'])
		{

		if (empty($PowerBB->_POST['check']))
		{
		$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Choose_incorrectl']);
		}

		$Subject_M = $PowerBB->_POST['check'];
		foreach ($Subject_M as $GetSubject)
		{
		$UpdateArr 				= 	array();
		$UpdateArr['field']		= 	array();
		$UpdateArr['field']['review_subject'] 		= 	'0';
		$UpdateArr['where'] 				= 	array('id',intval($GetSubject));

		$update = $PowerBB->subject->UpdateSubject($UpdateArr);


		$SubjectArr 			= 	array();
		$SubjectArr['where'] 	= 	array('id',intval($GetSubject));

		$this->SubjectInfo = $PowerBB->core->GetInfo($SubjectArr,'subject');



		$Subjectid =  $this->SubjectInfo['id'];

		$SubjectArr 							= 	array();
		$SubjectArr['field'] 					= 	array();
		$SubjectArr['field']['review_subject'] 	= 	$this->SubjectInfo['review_subject']-1;
		$SubjectArr['where'] 					= 	array('id',$this->SubjectInfo['id']);

		$update = $PowerBB->subject->UpdateSubject($SubjectArr);

		$SubjectNegArr 							= 	array();
		$SubjectNegArr['field'] 					= 	array();
		$SubjectNegArr['field']['review_subject'] 	= 	'0';
		$SubjectNegArr['where'] 					= 	array('review_subject','-1');

		$updateNeg = $PowerBB->subject->UpdateSubject($SubjectNegArr);

		$UpdateSectionCache = $PowerBB->functions->UpdateSectionCache($this->SubjectInfo['section']);

		}

		// Update subject review number
		$SubjectInfid = $this->SubjectInfo['id'];
		$SubjectInfReviewNum = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT id FROM " . $PowerBB->table['subject'] . " WHERE id='$SubjectInfid' and review_subject='1'"));
		$PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['subject'] . " SET review_subject='$SubjectInfReviewNum' WHERE id='$SubjectInfid'");

		$PowerBB->functions->redirect($PowerBB->_SERVER['HTTP_REFERER']);

		}

    }

	function _Review_Reply()
	{
		global $PowerBB;

		$PowerBB->_GET['count'] = (!isset($PowerBB->_GET['count'])) ? 0 : $PowerBB->_GET['count'];
		$PowerBB->_GET['count'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['count'],'intval');

 	    $review_nm = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(1),id FROM " . $PowerBB->table['reply'] . " WHERE review_reply = 1 "));
        $reply_perpage = $PowerBB->_CONF['info_row']['subject_perpage'];

		$ReviewArr 					= 	array();
		$ReviewArr['order']			=	array();
		$ReviewArr['order']['field']	=	'id';
		$ReviewArr['order']['type']	=	'DESC';
		$ReviewArr['proc'] 			= 	array();
		$ReviewArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');
		$ReviewArr['where']					=	array();
		$ReviewArr['where'][0]				=	array();
		$ReviewArr['where'][0]['name']		=	'review_reply';
		$ReviewArr['where'][0]['oper']		=	'=';
		$ReviewArr['where'][0]['value']		=	'1';

		$ReviewArr['pager'] 				= 	array();
		$ReviewArr['pager']['total']		= 	$review_nm;
		$ReviewArr['pager']['perpage'] 	= 	$reply_perpage;
		$ReviewArr['pager']['count'] 		= 	$PowerBB->_GET['count'];
		$ReviewArr['pager']['location'] 	= 	'index.php?page=subject&amp;review=1&amp;replys=1&amp;main=1';
		$ReviewArr['pager']['var'] 		= 	'count';

		$PowerBB->_CONF['template']['while']['ReviewList'] = $PowerBB->core->GetList($ReviewArr,'reply');
        if ($review_nm > $reply_perpage)
        {
		$PowerBB->template->assign('pager',$PowerBB->pager->show());
        }
        $PowerBB->template->assign('ReviewNumber',$review_nm);
        $PowerBB->template->assign('Reviewsubjects','1');

		$PowerBB->template->display('replys_reviewd');
	}

	function _Do_Review_Reply()
	{
        global $PowerBB;

		if ($PowerBB->_POST['deletposts'])
		{

		if ($PowerBB->_CONF['group_info']['group_mod'] == '1')
		{
		if ($PowerBB->_CONF['group_info']['del_reply'] == '0')
		{
		$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
		}
		}

		$Reply_D = $PowerBB->_POST['check'];
		foreach ($Reply_D as $GetReply)
		{

		$UpdateArr 				= 	array();
		$UpdateArr['field']		= 	array();
		$UpdateArr['field']['review_reply'] 		= 	'0';
		$UpdateArr['where'] 				= 	array('id',intval($GetReply));

		$update = $PowerBB->reply->UpdateReply($UpdateArr);

		$ReplyArr 			= 	array();
		$ReplyArr['where'] 	= 	array('id',intval($GetReply));

		$this->ReplyInfo = $PowerBB->core->GetInfo($ReplyArr,'reply');


		$Subjectid =  $this->ReplyInfo['subject_id'];
		$review_reply_nm = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['reply'] . " WHERE subject_id='$Subjectid' and review_reply='1' "));
		$reply_nm = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['reply'] . " WHERE section = '$section' and subject_id='$Subjectid' "));

		$SecArr 			= 	array();
		$SecArr['where'] 	= 	array('id',$this->ReplyInfo['section']);

		$this->SectionInfo = $PowerBB->core->GetInfo($SecArr,'section');



		$SubjectArr = array();
		$SubjectArr['where'] = array('id',$this->ReplyInfo['subject_id']);

		$SubjectInfo = $PowerBB->core->GetInfo($SubjectArr,'subject');


		$subject_id = $this->ReplyInfo['subject_id'];
		$Getlast_replier = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['reply'] . " WHERE subject_id = '$subject_id' AND delete_topic<>1 AND review_reply<>1 ORDER by write_time DESC");
		$GetLast_replierForm = $PowerBB->DB->sql_fetch_array($Getlast_replier);
		if (!$GetLast_replierForm)
		{
		$last_replier = '';
		}
		else
		{
		$last_replier = $GetLast_replierForm['writer'];
		}

		// Update Subject
		if (empty($GetLast_replierForm['write_time']))
		{
		$write_time = $SubjectInfo['native_write_time'];
		}
		else
		{
		$write_time = $GetLast_replierForm['write_time'];
		}
		$SubjectArr 							= 	array();
		$SubjectArr['field'] 					= 	array();
		$SubjectArr['field']['review_reply'] 	= 	$SubjectInfo['review_reply']-1;
		$SubjectArr['field']['reply_number'] 	= 	$reply_nm-1;
		$SubjectArr['field']['last_replier'] 	= 	$last_replier;
		$SubjectArr['field']['write_time']   	= 	$write_time;
		$SubjectArr['where'] 					= 	array('id',$subject_id);

		$update = $PowerBB->subject->UpdateSubject($SubjectArr);

		if ($this->SectionInfo['last_subjectid'] == $subject_id)
		{
		/**
		*Update Section Cache ;)
		*/
		$SectionCache = $this->ReplyInfo['section'];
		// The number of section's subjects number
		$reply_num = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT id FROM " . $PowerBB->table['reply'] . " WHERE section = '$SectionCache' "));

		$UpdateArr 					= 	array();
		$UpdateArr['field']			=	array();
		$UpdateArr['field']['reply_num'] 	= 	$reply_num;
		$UpdateArr['where']					= 	array('id',$SectionCache);

		$UpdateReplyNumber = $PowerBB->core->Update($UpdateArr,'section');


		$PowerBB->cache->UpdateReplyNumber(array('reply_num'	=>	$reply_num));


		$subject_nm = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT id FROM " . $PowerBB->table['subject'] . " WHERE section = '$SectionCache' "));

		// The number of section's subjects number
		$UpdateArr 					= 	array();
		$UpdateArr['field']			=	array();

		$UpdateArr['field']['subject_num'] 	= 	$subject_nm;
		$UpdateArr['where']					= 	array('id',$SectionCache);

		$UpdateSubjectNumber = $PowerBB->core->Update($UpdateArr,'section');


		$PowerBB->cache->UpdateSubjectNumber(array('subject_num'	=>	$subject_nm));

		$GetLastqueryReplyForm = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['reply'] . " WHERE subject_id = '$subject_id' AND delete_topic<>1 AND review_reply<>1 ORDER by write_time DESC");
		$GetLastReplyForm = $PowerBB->DB->sql_fetch_array($GetLastqueryReplyForm);

		if (!$GetLastReplyForm)
		{
		// Update Last subject's information in Section Form
		$UpdateLastFormSecArr = array();
		$UpdateLastFormSecArr['field']			=	array();

		$UpdateLastFormSecArr['field']['last_writer'] 		= 	$SubjectInfo['writer'];
		$UpdateLastFormSecArr['field']['last_subject'] 		= 	$SubjectInfo['title'];
		$UpdateLastFormSecArr['field']['last_subjectid'] 	= 	$subject_id;
		$UpdateLastFormSecArr['field']['last_date'] 	= 	$PowerBB->_CONF['now'];
		$UpdateLastFormSecArr['field']['last_time'] 	= 	$PowerBB->_CONF['now'];
		$UpdateLastFormSecArr['field']['icon'] 		    = 	$SubjectInfo['icon'];
		$UpdateLastFormSecArr['field']['last_reply'] 	= 	0;
		$UpdateLastFormSecArr['field']['last_berpage_nm']  = 	0;

		$UpdateLastFormSecArr['where'] 		        = 	array('id',$SectionCache);

		// Update Last Form Sec subject's information
		$UpdateLastFormSec = $PowerBB->section->UpdateSection($UpdateLastFormSecArr);
		 // Update section's cache
    	 $PowerBB->functions->UpdateSectionCache($SectionCache);
		}
		else
		{

		// Update Last subject's information in Section Form
		$UpdateLastFormSecArr = array();
		$UpdateLastFormSecArr['field']			=	array();

		$UpdateLastFormSecArr['field']['last_writer'] 		= 	$GetLastReplyForm['writer'];
		$UpdateLastFormSecArr['field']['last_subject'] 		= 	$GetLastReplyForm['title'];
		$UpdateLastFormSecArr['field']['last_subjectid'] 	= 	$GetLastReplyForm['subject_id'];
		$UpdateLastFormSecArr['field']['last_date'] 	= 	$GetLastReplyForm['write_time'];
		$UpdateLastFormSecArr['field']['last_time'] 	= 	$GetLastReplyForm['write_time'];
		$UpdateLastFormSecArr['field']['icon'] 		    = 	$GetLastReplyForm['icon'];
		$UpdateLastFormSecArr['field']['last_reply'] 	= 	0;
		$UpdateLastFormSecArr['field']['last_berpage_nm']  = 	0;

		$UpdateLastFormSecArr['where'] 		        = 	array('id',$SectionCache);

		// Update Last Form Sec subject's information
		$UpdateLastFormSec = $PowerBB->section->UpdateSection($UpdateLastFormSecArr);
		}

		// Get Section Info
		$SecaArr 			= 	array();
		$SecaArr['where'] 	= 	array('id',$SectionCache);

		$this->SecInfo = $PowerBB->section->GetSectionInfo($SecaArr);

		// Update section's cache
		$UpdateArr 				= 	array();
		$UpdateArr['parent'] 	= 	$this->SecInfo['parent'];

		$update_cache = $PowerBB->section->UpdateSectionsCache($UpdateArr);


		unset($UpdateArr);
		}

		$DeleteReplyArr				=	array();
		$DeleteReplyArr['where'] 	= 	array('id',intval($GetReply));
		$delReply = $PowerBB->reply->DeleteReply($DeleteReplyArr);

		$UpdateSectionCache1 = $PowerBB->functions->UpdateSectionCache($this->ReplyInfo['section']);
		}

		$UpdateSubjectNumber = $PowerBB->cache->UpdateReplyNumber(array('reply_num'	=>	$PowerBB->_CONF['info_row']['reply_number']));

		$PowerBB->functions->redirect($PowerBB->_SERVER['HTTP_REFERER']);


 		}
		elseif($PowerBB->_POST['approveposts'])
		{

		if (empty($PowerBB->_POST['check']))
		{
		$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Choose_incorrectl']);
		}

		$Reply_M = $PowerBB->_POST['check'];
		foreach ($Reply_M as $GetReply)
		{
		$UpdateArr 				= 	array();
		$UpdateArr['field']		= 	array();
		$UpdateArr['field']['review_reply'] 		= 	'0';
		$UpdateArr['where'] 				= 	array('id',intval($GetReply));

		$update = $PowerBB->reply->UpdateReply($UpdateArr);


		$ReplyArr 			= 	array();
		$ReplyArr['where'] 	= 	array('id',intval($GetReply));

		$this->ReplyInfo = $PowerBB->core->GetInfo($ReplyArr,'reply');


		$SecArr 			= 	array();
		$SecArr['where'] 	= 	array('id',$this->ReplyInfo['subject_id']);

		$this->SubjectInfo = $PowerBB->core->GetInfo($SecArr,'subject');


		$Subjectid =  $this->ReplyInfo['subject_id'];
		//$review_reply_nm = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['reply'] . " WHERE section = '$section' and subject_id='$Subjectid' and review_reply='1' "));

		$SubjectArr 							= 	array();
		$SubjectArr['field'] 					= 	array();
		$SubjectArr['field']['review_reply'] 	= 	$this->SubjectInfo['review_reply']-1;
		$SubjectArr['where'] 					= 	array('id',$this->ReplyInfo['subject_id']);

		$update = $PowerBB->subject->UpdateSubject($SubjectArr);

		$SubjectNegArr 							= 	array();
		$SubjectNegArr['field'] 					= 	array();
		$SubjectNegArr['field']['review_reply'] 	= 	'0';
		$SubjectNegArr['where'] 					= 	array('review_reply','-1');

		$updateNeg = $PowerBB->subject->UpdateSubject($SubjectNegArr);

		$UpdateSectionCache = $PowerBB->functions->UpdateSectionCache($this->ReplyInfo['section']);



		}

		// Update subject review number
		$SubjectInfid = $this->ReplyInfo['subject_id'];
		$SubjectInfReviewNum = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT id FROM " . $PowerBB->table['reply'] . " WHERE subject_id='$SubjectInfid' and review_reply='1'"));
		$PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['subject'] . " SET review_reply='$SubjectInfReviewNum' WHERE id='$SubjectInfid'");

		$PowerBB->functions->redirect($PowerBB->_SERVER['HTTP_REFERER']);
		}

   }

}

?>
