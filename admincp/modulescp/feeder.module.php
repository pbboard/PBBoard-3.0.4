<?php

(!defined('IN_PowerBB')) ? die() : '';

define('IN_ADMIN',true);

define('JAVASCRIPT_PowerCode',true);
define('CLASS_NAME','PowerBBCoreMOD');
include('../common.php');
class PowerBBCoreMOD
{
	function run()
	{
		global $PowerBB;

 		if ($PowerBB->_CONF['member_permission'])
		{


			if ($PowerBB->_CONF['rows']['group_info']['admincp_subject'] == '0')
			{
			  $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
			}


            if($PowerBB->_POST['ttl'] > 7200)
            {
             $Upttl_INT = $PowerBB->DB->sql_query("ALTER TABLE " . $PowerBB->table['feeds'] . " CHANGE ttl ttl INT(15) UNSIGNED NOT NULL DEFAULT '1500'");
            }

			if ($PowerBB->_GET['control'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_ControlFeedMain();
				}
			}
			elseif ($PowerBB->_GET['add'])
			{
              	if ($PowerBB->_GET['main'])
				{
					$this->_AddFeedMain();
				}
                elseif ($PowerBB->_GET['start'])
				{
					$this->_AddFeedStart();
				}
			}
			elseif ($PowerBB->_GET['edit'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_EditFeedMain();
				}
				elseif ($PowerBB->_GET['start'])
				{
					$this->_EditFeedStart();
				}
			}
			elseif ($PowerBB->_GET['runfeed'])
			{
                if ($PowerBB->_GET['start'])
				{
				$this->_RunFeedRss();

				}
			}

			elseif ($PowerBB->_GET['delet'])
			{
                if ($PowerBB->_GET['start'])
				{
					$this->_DelFeedStart();
				}
			}
			elseif ($PowerBB->_GET['ective_feeds'])
			{
					$this->_EctiveFeeds();

			}

      }

		$PowerBB->template->display('footer');

	}

	function _AddFeedMain()
	{
		global $PowerBB;

		$PowerBB->template->display('header');
		// Show Jump List to:)
		$result = $PowerBB->DB->sql_query("SELECT id,title,parent FROM " . $PowerBB->table['section'] . " ORDER BY id ASC");
		$Master = array();
		$row = $PowerBB->DB->sql_fetch_array($result);
			extract($row);
		    $Master = $PowerBB->core->GetList(array ('id'=>$id,'title'=>"".$title."",'parent'=>$parent."",'parent'=>$parent),'section');
		    $PowerBB->_CONF['template']['foreach']['SecList'] = $Master;

		$MainAndSub = new PowerBBCommon;
          	$PowerBB->template->assign('DoJumpList',$MainAndSub->DoJumpList($Master,$url,1));
		unset($Master);
	   ////////

		$PowerBB->template->display('feeder_add');

	}

	function _AddFeedStart()
	{

		global $PowerBB;

		$PowerBB->template->display('header');
		if(!$PowerBB->core->Is(array('where' => array('username',$PowerBB->_POST['member'])),'member') or empty($PowerBB->_POST['member'])){

			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['member_does_not_exist']);

		}

		if(empty($PowerBB->_POST['link']) or empty($PowerBB->_POST['title'])){

			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_fill_in_all_the_information']);
		}

		if($PowerBB->core->Is(array('where' => array('rsslink',$PowerBB->_POST['link'])),'feeds')){

		$PowerBB->_CONF['template']['_CONF']['lang']['please_choose_another_name']= str_replace("اسم المستخدم", $PowerBB->_CONF['template']['_CONF']['lang']['Feed_URL'],$PowerBB->_CONF['template']['_CONF']['lang']['please_choose_another_name']);
		$PowerBB->_CONF['template']['_CONF']['lang']['please_choose_another_name']= str_replace("اختيار اسم", $PowerBB->_CONF['template']['_CONF']['lang']['l_link'],$PowerBB->_CONF['template']['_CONF']['lang']['please_choose_another_name']);
		$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['please_choose_another_name']);
		}

		$file = $PowerBB->_POST['link'];
		$file_headers = @get_headers($file);
		if(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') {
		$PowerBB->_CONF['template']['_CONF']['lang']['feed_has_been_not_brought']= str_replace("{linkrss}", $PowerBB->_POST['link'],$PowerBB->_CONF['template']['_CONF']['lang']['feed_has_been_not_brought']);
		$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['feed_has_been_not_brought']);
		}
		$SectionArr = array();

		$SectionArr['where']	=	array();

		$SectionArr['where'][0]['name']	=	'parent';
		$SectionArr['where'][0]['oper']	=	'>';
		$SectionArr['where'][0]['value']	=	'0';

		$SectionArr['where'][1]['con']	=	'AND';
		$SectionArr['where'][1]['name']	=	'id';
		$SectionArr['where'][1]['oper']	=	'=';
		$SectionArr['where'][1]['value']	=	$PowerBB->_POST['section'];


		if(!$PowerBB->core->Is($SectionArr,'section')){

			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['section_does_not_exist_or_is_not_a_forum']);

		}

		$PowerBB->FeedParser->parse($PowerBB->_POST['link']);

		$Items	= $PowerBB->FeedParser->getItems();

		if (!$Items)
		{
		$PowerBB->_CONF['template']['_CONF']['lang']['feed_has_been_not_brought']= str_replace("{linkrss}", $PowerBB->_POST['link'],$PowerBB->_CONF['template']['_CONF']['lang']['feed_has_been_not_brought']);
		$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['feed_has_been_not_brought']);
		}


		foreach($Items as $Item){
		// Kill XSS & SQL Injection and clean the topic title
		$MemberArr 			= 	array();
		$MemberArr['where'] 	= 	array('username',$PowerBB->_POST['member']);
		$MemberInfo = $PowerBB->core->GetInfo($MemberArr,'member');

		$Item['TITLE'] = $PowerBB->functions->CleanVariable($Item['TITLE'],'html');
        $Item['TITLE'] 	= 	$PowerBB->sys_functions->CleanSymbols($Item['TITLE']);

			$find = "{rss:link}";
			if(stristr($PowerBB->_POST['text'],$find))
			{
		   	$LINK = "\n\n [url=".$Item['LINK']."]".$PowerBB->_CONF['template']['_CONF']['lang']['the_original_topic']."[/url]";
			}else{
			$LINK = "";
			}

			$text = $PowerBB->Powerparse->html2bb($Item['CONTENT:ENCODED']).$LINK;

         $section = $PowerBB->_POST['section'];
         $ItemTitle	=	$Item['TITLE'];

		 // Make sure that the topic does not exist before
           $exist_query = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['subject'] . " WHERE title LIKE '%$ItemTitle%' and section = '$section'");
           $exist_row   = $PowerBB->DB->sql_fetch_array($exist_query);
		 if (!$PowerBB->core->Is(array('where' => array('id',$exist_row['id'])),'subject'))
		  {
			$FROM_query = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['section'] . " WHERE id = '$section' ");
			$FROM__row  = $PowerBB->DB->sql_fetch_array($FROM_query);

			$SubjectArr	=	array();
			$SubjectArr['get_id'] =	true;
			$SubjectArr['field']	=	array();
			$SubjectArr['field']['title']	=	$Item['TITLE'];
			$SubjectArr['field']['text']	=	$text;
			if($FROM__row['review_subject'])
			{
			$SubjectArr['field']['review_subject'] = '1';
			}
			if($FROM__row['sec_section']
			or $FROM__row['hide_subject'])
			{
			$SubjectArr['field']['sec_subject'] = '1';
			}
			$SubjectArr['field']['writer']	=	$PowerBB->_POST['member'];
		    $SubjectArr['field']['write_time'] 			= 	$PowerBB->_CONF['now'];
		    $SubjectArr['field']['native_write_time'] 	= 	$PowerBB->_CONF['now'];
            $SubjectArr['field']['icon'] 				= 	'look/images/icons/i1.gif';
			$SubjectArr['field']['section']	=	$PowerBB->_POST['section'];

			$Insert = $PowerBB->subject->InsertSubject($SubjectArr);
             if($Insert)
             {
	     		// The overall number of subjects
	     		$UpdateSubjectNumber = $PowerBB->cache->UpdateSubjectNumber(array('subject_num'	=>	$PowerBB->_CONF['info_row']['subject_number']));

	            // The overall number of Member posts
				$posts = $MemberInfo['posts'] + 1;
				$MemberArr 				= 	array();
				$MemberArr['field'] 	= 	array();
				$MemberArr['field']['posts']			=	$posts;
				$MemberArr['field']['lastpost_time'] 	=	$PowerBB->_CONF['now'];
				$MemberArr['where']						=	array('id',$MemberInfo['id']);
				$UpdateMember = $PowerBB->core->Update($MemberArr,'member');

                   if ($PowerBB->_CONF['info_row']['add_tags_automatic'] == '1')
	     		   {
                       //add tags Automatic from subject title
                       $string_title = implode(' ', array_unique(explode(' ', $Item['TITLE'])));

					$excludedWords = array();
					$censorwords = preg_split('/\s+/s', $string_title, -1, PREG_SPLIT_NO_EMPTY);
					$excludedWords = array_merge($excludedWords, $censorwords);
					unset($censorwords);

					// Trim current exclusions
					for ($x = 0; $x < count($excludedWords); $x++)
					{
					   $excludedWords[$x] = trim($excludedWords[$x]);

                           if (function_exists('mb_strlen'))
                           {
                               $tag_less_num = mb_strlen($excludedWords[$x], 'UTF-8') >= 6;
                           }
                           else
                           {
                               $tag_less_num = strlen(utf8_decode($excludedWords[$x])) >= 6;
                           }

                          if($tag_less_num)
					     {

							$InsertArr 						= 	array();
							$InsertArr['field']				=	array();
					     	$InsertArr['field']['tag_id'] 			= 	"";
							$InsertArr['field']['subject_id'] 		=	$PowerBB->subject->id;
							$InsertArr['field']['tag'] 				= 	$PowerBB->functions->CleanVariable($excludedWords[$x],'html');
							$InsertArr['field']['subject_title'] 	= 	$PowerBB->functions->CleanVariable($Item['TITLE'],'html');
							// Note, this function is from tag system not subject system
							$insert_tags = $PowerBB->core->Insert($InsertArr,'tags_subject');
                         }
					}
                   }
              }
           $exisT_s = true;

		  }
          else
          {
          	$exisT_s = false;
          }

		}

	  if ($exisT_s)
	  {
		     $info_query = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['subject'] . " WHERE id and section = '$section' ORDER BY id ASC");
		     $info_row   = $PowerBB->DB->sql_fetch_array($info_query);

		$UpdateLastArr = array();
		$UpdateLastArr['field']			=	array();

		$UpdateLastArr['field']['last_writer'] 		= 	$info_row['writer'];
		$UpdateLastArr['field']['last_subject'] 	= 	$PowerBB->functions->CleanVariable($info_row['title'],'html');
		$UpdateLastArr['field']['last_subjectid'] 	= 	$info_row['id'];
		$UpdateLastArr['field']['last_date'] 	    = 	$PowerBB->_CONF['now'];
		$UpdateLastArr['field']['last_time'] 	    = 	$PowerBB->_CONF['now'];
		$UpdateLastArr['field']['icon'] 		    = 	'look/images/icons/i1.gif';

		$UpdateLastArr['where'] 		            = 	array('id',$PowerBB->_POST['section']);

		// Update Last subject's information
		$UpdateLast = $PowerBB->core->Update($UpdateLastArr,'section');

		// Free memory
		unset($UpdateLastArr);

         $Upsubject_nm = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['subject'] . " WHERE section='$section'"));

		$SecArr 			= 	array();
        $SecArr['where'] 	= 	array('id',$PowerBB->_POST['section']);

		$this->SectionInfo = $PowerBB->core->GetInfo($SecArr,'section');

              // The overall number of subjects
    		$UpdateSubjectNumber = $PowerBB->cache->UpdateSubjectNumber(array('subject_num'	=>	$PowerBB->_CONF['info_row']['subject_number']));

    		//////////

    		// The number of section's subjects number
    		$UpdateArr 					= 	array();
    		$UpdateArr['field']			=	array();

    		$UpdateArr['field']['subject_num'] 	= 	$this->SectionInfo['subject_num'] + $Upsubject_nm;
    		$UpdateArr['where']					= 	array('id',$PowerBB->_POST['section']);

    		$UpdateSubjectNumber = $PowerBB->core->Update($UpdateArr,'section');


			$FeedsArr	                    =	array();
			$FeedsArr['field']	            =	array();
			$FeedsArr['field']['title']	    =	$PowerBB->_POST['title'];
            $FeedsArr['field']['title2']	=	$PowerBB->_POST['title2'];
			$FeedsArr['field']['text']	    =	$PowerBB->_POST['text'];
			$FeedsArr['field']['userid']	=	$MemberInfo['id'];
		    $FeedsArr['field']['feeds_time']= 	$PowerBB->_CONF['now'];
		    $FeedsArr['field']['forumid'] 	= 	$PowerBB->_POST['section'];
            $FeedsArr['field']['ttl'] 		= 	$PowerBB->_POST['ttl'];
			$FeedsArr['field']['rsslink']	=	$PowerBB->_POST['link'];
			$FeedsArr['field']['options']	=	'1';

			$InsertFeeds = $PowerBB->core->Insert($FeedsArr,'feeds');
      }
    		// Update section's cache
			$PowerBB->functions->UpdateSectionCache($FeedsInfo['forumid']);

	       $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['feed_has_been_brought_successfully']);
           $PowerBB->functions->redirect('index.php?page=feeder&amp;control=1&amp;main=1');
	}

	function _ControlFeedMain()
	{
		global $PowerBB;

		$PowerBB->template->display('header');

        // show Feeders List
		$FeedersArr 					= 	array();
		$FeedersArr['order']			=	array();
		$FeedersArr['order']['field']	=	'id';
		$FeedersArr['order']['type']	=	'DESC';
		$FeedersArr['proc'] 			= 	array();
		$FeedersArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');
        $FeedersArr['proc']['feeds_time']  =    array('method'=>'date','store'=>'feeds_time','type'=>$PowerBB->_CONF['info_row']['timesystem']);

		$PowerBB->_CONF['template']['while']['feedersList'] = $PowerBB->core->GetList($FeedersArr,'feeds');


		$PowerBB->template->display('feeder_main');

	}

	function _EditFeedMain()
	{
		global $PowerBB;

		$PowerBB->template->display('header');

			if (empty($PowerBB->_GET['id']))
			{
				$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['feed_requested_does_not_exist']);
			}

			$FeedEditArr				=	array();
		    $FeedEditArr['where'] 	= 	array('id',$PowerBB->_GET['id']);

			$FeedEdit = $PowerBB->core->GetInfo($FeedEditArr,'feeds');

		$MemberEditArr 			= 	array();
		$MemberEditArr['where'] 	= 	array('id',$FeedEdit['userid']);
		$MemberEditInfo = $PowerBB->core->GetInfo($MemberEditArr,'member');

		$PowerBB->template->assign('username',$MemberEditInfo['username']);
		$PowerBB->template->assign('forumid',$FeedEdit['forumid']);
		$PowerBB->template->assign('FeedEdit',$FeedEdit);
		$ttl 			= 	$FeedEdit['ttl'] /60;
		$PowerBB->template->assign('ttl',$ttl);

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
             // foreach main sections
			$PowerBB->_CONF['template']['foreach']['forums_list'][$cat['id'] . '_m'] = $cat;

			unset($sectiongroup);

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
				$forums = $PowerBB->core->GetList($ForumArr,'section');
					foreach ($forums as $forum)
					{
							$forum['is_sub'] 	= 	0;
							$forum['sub']		=	'';

								if ($PowerBB->_CONF['files_forums_Cache'])
								{
								@include("../cache/forums_cache/forums_cache_".$forum['id'].".php");
								}
								else
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
									$subs = $PowerBB->core->GetList($SubArr,'section');
	                               foreach ($subs as $sub)
									{
									   if ($forum['id'] == $sub['parent'])
	                                    {

												if (!$forum['is_sub'])
												{
													$forum['is_sub'] = 1;
												}
                               		        if ($sub['id'] == $FeedEdit['forumid'])
                               		        {
											$forum['sub'] .= ('<option value="' .$sub['id'] . '" selected="selected" >---'  . $sub['title'] . '</option>');
											}
											else
											{
											$forum['sub'] .= ('<option value="' .$sub['id'] . '" >---'  . $sub['title'] . '</option>');
											}
										  }




					                         ///////////////

													$forum['is_sub_sub'] 	= 	0;
													$forum['sub_sub']		=	'';

											if ($PowerBB->_CONF['files_forums_Cache'])
											{
											@include("../cache/forums_cache/forums_cache_".$sub['id'].".php");
											}
											else
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
											$subs_sub = $PowerBB->core->GetList($SubsArr,'section');
				                               foreach ($subs_sub as $sub_sub)
												{
												   if ($sub['id'] == $sub_sub['parent'])
				                                    {

																	if (!$forum['is_sub_sub'])
																	{
																		$forum['is_sub_sub'] = 1;
																	}

                               		                           if ($sub_sub['id'] == $FeedEdit['forumid'])
				                                		        {
																$forum['sub_sub'] .= ('<option value="' .$sub_sub['id'] . '" selected="selected" >---'  . $sub_sub['title'] . '</option>');
																}
																else
																{
																$forum['sub_sub'] .= ('<option value="' .$sub_sub['id'] . '" >---'  . $sub_sub['title'] . '</option>');
																}
													  }
												 }

										   }
									 }
								}


							$PowerBB->_CONF['template']['foreach']['forums_list'][$forum['id'] . '_f'] = $forum;

		             } // end foreach ($forums)
			  } // end !empty($forums_cache)

				unset($SecArr);
				$SecArr = $PowerBB->DB->sql_free_result($SecArr);
		} // end foreach ($cats)

		$PowerBB->template->display('feeder_edit');

	}

	function _EditFeedStart()
	{

		global $PowerBB;

		$PowerBB->template->display('header');
		if(!$PowerBB->core->Is(array('where' => array('username',$PowerBB->_POST['member'])),'member') or empty($PowerBB->_POST['member'])){

			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['member_does_not_exist']);

		}

		if(empty($PowerBB->_POST['link']) or empty($PowerBB->_POST['title'])){

			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_fill_in_all_the_information']);

		}

		$MemberArr 			= 	array();
		$MemberArr['where'] 	= 	array('username',$PowerBB->_POST['member']);
		$MemberInfo = $PowerBB->core->GetInfo($MemberArr,'member');

		if($PowerBB->_POST['options'] == '1')
		{

				$SectionArr = array();

				$SectionArr['where']	=	array();

				$SectionArr['where'][0]['name']	=	'parent';
				$SectionArr['where'][0]['oper']	=	'>';
				$SectionArr['where'][0]['value']	=	'0';

				$SectionArr['where'][1]['con']	=	'AND';
				$SectionArr['where'][1]['name']	=	'id';
				$SectionArr['where'][1]['oper']	=	'=';
				$SectionArr['where'][1]['value']	=	$PowerBB->_POST['section'];


		if(!$PowerBB->core->Is($SectionArr,'section'))
		{
		  $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['section_does_not_exist_or_is_not_a_forum']);
		}


		$PowerBB->FeedParser->parse($PowerBB->_POST['link']);

		$Items	= $PowerBB->FeedParser->getItems();
		if (!$Items)
		{
		$PowerBB->_CONF['template']['_CONF']['lang']['feed_has_been_not_brought']= str_replace("{linkrss}", $PowerBB->_POST['link'],$PowerBB->_CONF['template']['_CONF']['lang']['feed_has_been_not_brought']);
		$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['feed_has_been_not_brought']);
		}
			  $x = 0;
			  $y = $x++;
			 foreach($Items as $Item)
			 {
				// Kill XSS & SQL Injection and clean the topic title
				$find = "{rss:link}";
				if(stristr($PowerBB->_POST['text'],$find))
				{
				$LINK = "\n\n [url=".$Item['LINK']."]".$PowerBB->_CONF['template']['_CONF']['lang']['the_original_topic']."[/url]";
				}else{
				$LINK = "";
				}

				$text = $PowerBB->Powerparse->html2bb($Item['CONTENT:ENCODED']).$LINK;
				$Item['TITLE'] 	= 	$PowerBB->functions->CleanVariable($Item['TITLE'],'html');
				$Item['TITLE'] 	= 	$PowerBB->sys_functions->CleanSymbols($Item['TITLE']);


				$section = $PowerBB->_POST['section'];
				$ItemTitle	=	$Item['TITLE'];

				 // Make sure that the topic does not exist before
		           $exist_query = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['subject'] . " WHERE title LIKE '%$ItemTitle%' and section = '$section'");
		           $exist_row   = $PowerBB->DB->sql_fetch_array($exist_query);
				if (!$exist_row)
				  {
					$FROM_query = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['section'] . " WHERE id = '$section' ");
					$FROM__row  = $PowerBB->DB->sql_fetch_array($FROM_query);

					$SubjectArr	=	array();
					$SubjectArr['get_id']						=	true;
					$SubjectArr['field']	=	array();
					$SubjectArr['field']['title']	=	$Item['TITLE'];
					$SubjectArr['field']['text']	=	$text;
					if($FROM__row['review_subject'])
					{
					$SubjectArr['field']['review_subject'] = '1';
					}
					if($FROM__row['sec_section']
					or $FROM__row['hide_subject'])
					{
					$SubjectArr['field']['sec_subject'] = '1';
					}
					$SubjectArr['field']['writer']	=	$PowerBB->_POST['member'];
				    $SubjectArr['field']['write_time'] 			= 	$PowerBB->_CONF['now'];
				    $SubjectArr['field']['native_write_time'] 	= 	$PowerBB->_CONF['now'];
		            $SubjectArr['field']['icon'] 				= 	'look/images/icons/i1.gif';
					$SubjectArr['field']['section']	=	$PowerBB->_POST['section'];

					$Insert = $PowerBB->subject->InsertSubject($SubjectArr);
                    if($Insert)
                    {
			            // The overall number of Member posts
						$posts = $MemberInfo['posts'] + 1;
						$MemberArr 				= 	array();
						$MemberArr['field'] 	= 	array();
						$MemberArr['field']['posts']			=	$posts;
						$MemberArr['field']['lastpost_time'] 	=	$PowerBB->_CONF['now'];
						$MemberArr['where']						=	array('username',$PowerBB->_POST['member']);
						$UpdateMember = $PowerBB->core->Update($MemberArr,'member');


	                    if ($PowerBB->_CONF['info_row']['add_tags_automatic'] == '1')
			     		{
	                        //add tags Automatic from subject title
	                        $string_title = implode(' ', array_unique(explode(' ', $Item['TITLE'])));

							$excludedWords = array();
							$censorwords = preg_split('/\s+/s', $string_title, -1, PREG_SPLIT_NO_EMPTY);
							$excludedWords = array_merge($excludedWords, $censorwords);
							unset($censorwords);

							// Trim current exclusions
							for ($x = 0; $x < count($excludedWords); $x++)
							{
							   $excludedWords[$x] = trim($excludedWords[$x]);

	                            if (function_exists('mb_strlen'))
	                            {
	                                $tag_less_num = mb_strlen($excludedWords[$x], 'UTF-8') >= 6;
	                            }
	                            else
	                            {
	                                $tag_less_num = strlen(utf8_decode($excludedWords[$x])) >= 6;
	                            }

	                           if($tag_less_num)
							  {

									$InsertArr 						= 	array();
									$InsertArr['field']				=	array();
							     	$InsertArr['field']['tag_id'] 			= 	"";
									$InsertArr['field']['subject_id'] 		=	$PowerBB->subject->id;
									$InsertArr['field']['tag'] 				= 	$PowerBB->functions->CleanVariable($excludedWords[$x],'html');
									$InsertArr['field']['subject_title'] 	= 	$PowerBB->functions->CleanVariable($Item['TITLE'],'html');
									// Note, this function is from tag system not subject system
									$insert_tags = $PowerBB->core->Insert($InsertArr,'tags_subject');
	                          }
							}
	                    }
                    }
				 }

				$x++;
				if($x==$y) break;

			 }

		$section = $PowerBB->_POST['section'];
		$info_query = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['subject'] . " WHERE id and section = '$section' ORDER BY id ASC");
		$info_row   = $PowerBB->DB->sql_fetch_array($info_query);

		$UpdateLastArr = array();
		$UpdateLastArr['field']			=	array();

		$UpdateLastArr['field']['last_writer'] 		= 	$info_row['writer'];
		$UpdateLastArr['field']['last_subject'] 	= 	$PowerBB->functions->CleanVariable($info_row['title'],'html');
		$UpdateLastArr['field']['last_subjectid'] 	= 	$info_row['id'];
		$UpdateLastArr['field']['last_date'] 	    = 	$PowerBB->_CONF['now'];
		$UpdateLastArr['field']['last_time'] 	    = 	$PowerBB->_CONF['now'];
		$UpdateLastArr['field']['icon'] 		    = 	'look/images/icons/i1.gif';

		$UpdateLastArr['where'] 		            = 	array('id',$PowerBB->_POST['section']);

		// Update Last subject's information
		$UpdateLast = $PowerBB->core->Update($UpdateLastArr,'section');

		// Free memory
		unset($UpdateLastArr);

		$Upsubject_nm = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['subject'] . " WHERE section='$section'"));

		$SecArr 			= 	array();
		$SecArr['where'] 	= 	array('id',$PowerBB->_POST['section']);

		$this->SectionInfo = $PowerBB->core->GetInfo($SecArr,'section');

		// The overall number of subjects
		$UpdateSubjectNumber = $PowerBB->cache->UpdateSubjectNumber(array('subject_num'	=>	$PowerBB->_CONF['info_row']['subject_number']));

		//////////

		// The number of section's subjects number
		$UpdateArr 					= 	array();
		$UpdateArr['field']			=	array();

		$UpdateArr['field']['subject_num'] 	= 	$this->SectionInfo['subject_num'] + $Upsubject_nm;
		$UpdateArr['where']					= 	array('id',$PowerBB->_POST['section']);

		$UpdateSubjectNumber = $PowerBB->core->Update($UpdateArr,'section');

		// Update section's cache
		$UpdateSectionCache = $PowerBB->functions->UpdateSectionCache($PowerBB->_POST['section']);
     }
		// Update last feeds time

			$FeedsArr	                    =	array();
			$FeedsArr['field']	            =	array();
			$FeedsArr['field']['title']	    =	$PowerBB->_POST['title'];
            $FeedsArr['field']['title2']	=	$PowerBB->_POST['title2'];
			$FeedsArr['field']['text']	    =	$PowerBB->_POST['text'];
			$FeedsArr['field']['userid']	=	$MemberInfo['id'];
		    $FeedsArr['field']['feeds_time']= 	$PowerBB->_CONF['now'];
		    $FeedsArr['field']['forumid'] 	= 	$PowerBB->_POST['section'];
            $FeedsArr['field']['ttl'] 		= 	$PowerBB->_POST['ttl'];
			$FeedsArr['field']['rsslink']	=	$PowerBB->_POST['link'];
			$FeedsArr['field']['options']	=	$PowerBB->_POST['options'];
		    $FeedsArr['where'] 				= 	array('id',$PowerBB->_GET['id']);


			$UpdateFeeds = $PowerBB->core->Update($FeedsArr,'feeds');
		if($PowerBB->_POST['options'] == '1')
		{
		$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['feed_has_been_brought_successfully']);
		}
		else
		{		$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['updated_successfully']);
		}

		$PowerBB->functions->redirect('index.php?page=feeder&control=1&main=1');

	}

	function _RunFeedRss()
	{
		global $PowerBB;

		$PowerBB->template->display('header');

		if (empty($PowerBB->_GET['id']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['The_declaration_does_not_exist']);
		}

		$FeedsInfoArr 			= 	array();
		$FeedsInfoArr['where'] 	= 	array('id',$PowerBB->_GET['id']);
		$FeedsInfo = $PowerBB->core->GetInfo($FeedsInfoArr,'feeds');

		$SectionArr = array();
		$SectionArr['where']	=	array();
		$SectionArr['where'][0]['name']	=	'parent';
		$SectionArr['where'][0]['oper']	=	'>';
		$SectionArr['where'][0]['value']	=	'0';

		$SectionArr['where'][1]['con']	=	'AND';
		$SectionArr['where'][1]['name']	=	'id';
		$SectionArr['where'][1]['oper']	=	'=';
		$SectionArr['where'][1]['value']	=	$FeedsInfo['forumid'];

		if(!$PowerBB->core->Is($SectionArr,'section'))
		{
		  $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['section_does_not_exist_or_is_not_a_forum']);
		}


		$PowerBB->FeedParser->parse($FeedsInfo['rsslink']);

		$Items	= $PowerBB->FeedParser->getItems();
		if (!$Items)
		{
		$PowerBB->_CONF['template']['_CONF']['lang']['feed_has_been_not_brought']= str_replace("{linkrss}", $FeedsInfo['rsslink'],$PowerBB->_CONF['template']['_CONF']['lang']['feed_has_been_not_brought']);
		$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['feed_has_been_not_brought']);
		}
			  $x = 0;
			  $y = $x++;
			 foreach($Items as $Item)
			 {
				// Kill XSS & SQL Injection and clean the topic title
				$find = "{rss:link}";
				if(stristr($FeedsInfo['text'],$find))
				{
				$LINK = "\n\n [url=".$Item['LINK']."]".$PowerBB->_CONF['template']['_CONF']['lang']['the_original_topic']."[/url]";
				}else{
				$LINK = "";
				}

				$text = $PowerBB->Powerparse->html2bb($Item['CONTENT:ENCODED']).$LINK;
				$Item['TITLE'] 	= 	$PowerBB->functions->CleanVariable($Item['TITLE'],'html');
				$Item['TITLE'] 	= 	$PowerBB->sys_functions->CleanSymbols($Item['TITLE']);
				$MemberArr 			= 	array();
				$MemberArr['where'] 	= 	array('id',$FeedsInfo['userid']);
				$MemberInfo = $PowerBB->core->GetInfo($MemberArr,'member');

				$section = $FeedsInfo['forumid'];
				$ItemTitle	=	$Item['TITLE'];

				 // Make sure that the topic does not exist before
		           $exist_query = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['subject'] . " WHERE title LIKE '%$ItemTitle%' and section = '$section'");
		           $exist_row   = $PowerBB->DB->sql_fetch_array($exist_query);
				if (!$exist_row)
				  {
					$FROM_query = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['section'] . " WHERE id = '$section' ");
					$FROM__row  = $PowerBB->DB->sql_fetch_array($FROM_query);

					$SubjectArr	=	array();
					$SubjectArr['get_id']						=	true;
					$SubjectArr['field']	=	array();
					$SubjectArr['field']['title']	=	$Item['TITLE'];
					$SubjectArr['field']['text']	=	$text;
					if($FROM__row['review_subject'])
					{
					$SubjectArr['field']['review_subject'] = '1';
					}
					if($FROM__row['sec_section']
					or $FROM__row['hide_subject'])
					{
					$SubjectArr['field']['sec_subject'] = '1';
					}
					$SubjectArr['field']['writer']	=	$MemberInfo['username'];
				    $SubjectArr['field']['write_time'] 			= 	$PowerBB->_CONF['now'];
				    $SubjectArr['field']['native_write_time'] 	= 	$PowerBB->_CONF['now'];
		            $SubjectArr['field']['icon'] 				= 	'look/images/icons/i1.gif';
					$SubjectArr['field']['section']	=	$FeedsInfo['forumid'];

					$Insert = $PowerBB->subject->InsertSubject($SubjectArr);
                    if($Insert)
                    {
			            // The overall number of Member posts
						$posts = $MemberInfo['posts'] + 1;
						$MemberArr 				= 	array();
						$MemberArr['field'] 	= 	array();
						$MemberArr['field']['posts']			=	$posts;
						$MemberArr['field']['lastpost_time'] 	=	$PowerBB->_CONF['now'];
						$MemberArr['where']						=	array('id',$MemberInfo['id']);
						$UpdateMember = $PowerBB->core->Update($MemberArr,'member');


	                    if ($PowerBB->_CONF['info_row']['add_tags_automatic'] == '1')
			     		{
	                        //add tags Automatic from subject title
	                        $string_title = implode(' ', array_unique(explode(' ', $Item['TITLE'])));

							$excludedWords = array();
							$censorwords = preg_split('/\s+/s', $string_title, -1, PREG_SPLIT_NO_EMPTY);
							$excludedWords = array_merge($excludedWords, $censorwords);
							unset($censorwords);

							// Trim current exclusions
							for ($x = 0; $x < count($excludedWords); $x++)
							{
							   $excludedWords[$x] = trim($excludedWords[$x]);

	                            if (function_exists('mb_strlen'))
	                            {
	                                $tag_less_num = mb_strlen($excludedWords[$x], 'UTF-8') >= 6;
	                            }
	                            else
	                            {
	                                $tag_less_num = strlen(utf8_decode($excludedWords[$x])) >= 6;
	                            }

	                           if($tag_less_num)
							  {

									$InsertArr 						= 	array();
									$InsertArr['field']				=	array();
							     	$InsertArr['field']['tag_id'] 			= 	"";
									$InsertArr['field']['subject_id'] 		=	$PowerBB->subject->id;
									$InsertArr['field']['tag'] 				= 	$PowerBB->functions->CleanVariable($excludedWords[$x],'html');
									$InsertArr['field']['subject_title'] 	= 	$PowerBB->functions->CleanVariable($Item['TITLE'],'html');
									// Note, this function is from tag system not subject system
									$insert_tags = $PowerBB->core->Insert($InsertArr,'tags_subject');
	                          }
							}
	                    }
                    }
				 }

				$x++;
				if($x==$y) break;

			 }

		$section = $FeedsInfo['forumid'];
		$info_query = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['subject'] . " WHERE id and section = '$section' ORDER BY id ASC");
		$info_row   = $PowerBB->DB->sql_fetch_array($info_query);

		$UpdateLastArr = array();
		$UpdateLastArr['field']			=	array();

		$UpdateLastArr['field']['last_writer'] 		= 	$info_row['writer'];
		$UpdateLastArr['field']['last_subject'] 	= 	$PowerBB->functions->CleanVariable($info_row['title'],'html');
		$UpdateLastArr['field']['last_subjectid'] 	= 	$info_row['id'];
		$UpdateLastArr['field']['last_date'] 	    = 	$PowerBB->_CONF['now'];
		$UpdateLastArr['field']['last_time'] 	    = 	$PowerBB->_CONF['now'];
		$UpdateLastArr['field']['icon'] 		    = 	'look/images/icons/i1.gif';

		$UpdateLastArr['where'] 		            = 	array('id',$FeedsInfo['forumid']);

		// Update Last subject's information
		$UpdateLast = $PowerBB->core->Update($UpdateLastArr,'section');

		// Free memory
		unset($UpdateLastArr);

		$Upsubject_nm = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['subject'] . " WHERE section='$section'"));

		$SecArr 			= 	array();
		$SecArr['where'] 	= 	array('id',$FeedsInfo['forumid']);

		$this->SectionInfo = $PowerBB->core->GetInfo($SecArr,'section');

		// The overall number of subjects
		$UpdateSubjectNumber = $PowerBB->cache->UpdateSubjectNumber(array('subject_num'	=>	$PowerBB->_CONF['info_row']['subject_number']));

		//////////

		// The number of section's subjects number
		$UpdateArr 					= 	array();
		$UpdateArr['field']			=	array();

		$UpdateArr['field']['subject_num'] 	= 	$this->SectionInfo['subject_num'] + $Upsubject_nm;
		$UpdateArr['where']					= 	array('id',$FeedsInfo['forumid']);

		$UpdateSubjectNumber = $PowerBB->core->Update($UpdateArr,'section');

		// Update section's cache
		$UpdateSectionCache = $PowerBB->functions->UpdateSectionCache($FeedsInfo['forumid']);

		// Update last feeds time
		$feeds_time = $PowerBB->_CONF['now'];
		$feeds_id = $FeedsInfo['id'];
		$Update_Feeds = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['feeds'] . " SET feeds_time ='$feeds_time' where id = '$feeds_id'");

		$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['feed_has_been_brought_successfully']);
		$PowerBB->functions->redirect('index.php?page=feeder&control=1&main=1');

    }



	function _DelFeedStart()
	{

		global $PowerBB;

		$PowerBB->template->display('header');
			if (empty($PowerBB->_GET['id']))
			{
				$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['The_declaration_does_not_exist']);
			}

			$DelArr 			= 	array();
			$DelArr['where'] 	= 	array('id',$PowerBB->_GET['id']);

			$del = $PowerBB->core->Deleted($DelArr,'feeds');

		if ($del)
		{
	       $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['feed_has_been_deleted_successfully']);
           $PowerBB->functions->redirect('index.php?page=feeder&control=1&main=1');
		}
  }


	function _EctiveFeeds()
	{

		global $PowerBB;

		$PowerBB->template->display('header');
			if (empty($PowerBB->_GET['id']))
			{
				$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['The_declaration_does_not_exist']);
			}

			$FeedsArr	                    =	array();
			$FeedsArr['field']	            =	array();
			$FeedsArr['field']['options']	=	$PowerBB->_GET['options'];
		    $FeedsArr['where'] 				= 	array('id',$PowerBB->_GET['id']);


			$UpdateFeeds = $PowerBB->core->Update($FeedsArr,'feeds');

		if ($UpdateFeeds)
		{
	       $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['action_was_successful']);
           $PowerBB->functions->redirect('index.php?page=feeder&control=1&main=1');
		}
  }

}

?>
