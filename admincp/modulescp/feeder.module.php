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

      }

			       $PowerBB->template->display('footer');

	}

	function _AddFeedMain()
	{
		global $PowerBB;

		$PowerBB->template->display('header');

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

		// We will use forums_list to store list of forums which will view in main page
		$PowerBB->_CONF['template']['foreach']['forums_list'] = array();

		// Loop to read the information of main sections
		foreach ($cats as $cat)
		{
			// Get the groups information to know view this section or not

			$PowerBB->_CONF['template']['foreach']['forums_list'][$cat['id'] . '_m'] = $cat;

			if (!empty($cat['forums_cache']))
			{
				$forums = json_decode(base64_decode($forums_cache), true);

				foreach ($forums as $forum)
				{


							$forum['is_sub'] 	= 	0;
							$forum['sub']		=	'';

							if (!empty($forum['forums_cache']))
							{
								$subs = json_decode(base64_decode($forum['forums_cache']), true);

								if (is_array($subs))
								{
									foreach ($subs as $sub)
									{

												if (!$forum['is_sub'])
												{
													$forum['is_sub'] = 1;
												}

												$forum['sub'] .= ('<option value="' .$sub['id'] . '" selected="selected">---'  . $sub['title'] . '</option>');

									}
								}
							}


							$PowerBB->_CONF['template']['foreach']['forums_list'][$forum['id'] . '_f'] = $forum;
					} // end if is_array
				} // end foreach ($forums)
			} // end !empty($forums_cache)

					// Show Jump List to:)
		$result = $PowerBB->DB->sql_query("SELECT id,title,parent FROM " . $PowerBB->table['section'] . " ORDER BY id ASC");

		$Master = array();
		while ($row = $PowerBB->DB->sql_fetch_array($result)) {
			extract($row);
		    $Master = $PowerBB->core->GetList(array ('id'=>$id,'title'=>"".$title."",'parent'=>$parent."",'parent'=>$parent),'section');
		    $PowerBB->_CONF['template']['foreach']['SecList'] = $Master;
		}

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
		$Item['TITLE'] = $PowerBB->functions->CleanVariable($Item['TITLE'],'sql');
		$Item['TITLE'] = str_ireplace("'",'"', $Item['TITLE']);
		$Item['TITLE'] = str_ireplace("\\",'"', $Item['TITLE']);


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

			$SubjectArr	=	array();
			$SubjectArr['field']	=	array();
			$SubjectArr['field']['title']	=	$Item['TITLE'];
			$SubjectArr['field']['text']	=	$text;
			$SubjectArr['field']['writer']	=	$PowerBB->_POST['member'];
		    $SubjectArr['field']['write_time'] 			= 	$PowerBB->_CONF['now'];
		    $SubjectArr['field']['native_write_time'] 	= 	$PowerBB->_CONF['now'];
            $SubjectArr['field']['icon'] 				= 	'look/images/icons/i1.gif';
			$SubjectArr['field']['section']	=	$PowerBB->_POST['section'];

			$Insert = $PowerBB->core->Insert($SubjectArr,'subject');

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

           $exisT_s = '1';

		  }
          else
          {
          	$exisT_s = '0';
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

    		//////////

    		// Update section's cache
			$PowerBB->functions->UpdateSectionCache($FeedsInfo['forumid']);


    		//////////


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

			 foreach($Items as $Item)
			 {
				// Kill XSS & SQL Injection and clean the topic title
				$MemberArr 			= 	array();
				$MemberArr['where'] 	= 	array('username',$PowerBB->_POST['member']);
				$MemberInfo = $PowerBB->core->GetInfo($MemberArr,'member');

				$Item['TITLE'] = $PowerBB->functions->CleanVariable($Item['TITLE'],'html');
				$Item['TITLE'] = $PowerBB->functions->CleanVariable($Item['TITLE'],'sql');
				$Item['TITLE'] = str_ireplace("'",'"', $Item['TITLE']);
				$Item['TITLE'] = str_ireplace("\\",'"', $Item['TITLE']);
		                    $Item['TITLE'] 	= 	$PowerBB->functions->CleanVariable($Item['TITLE'],'html');
		                    //$Item['TITLE'] 	= 	$PowerBB->functions->CleanVariable($Item['TITLE'],'sql');
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

					$SubjectArr	=	array();
					$SubjectArr['field']	=	array();
					$SubjectArr['field']['title']	=	$Item['TITLE'];
					$SubjectArr['field']['text']	=	$text;
					$SubjectArr['field']['writer']	=	$PowerBB->_POST['member'];
				    $SubjectArr['field']['write_time'] 			= 	$PowerBB->_CONF['now'];
				    $SubjectArr['field']['native_write_time'] 	= 	$PowerBB->_CONF['now'];
		            $SubjectArr['field']['icon'] 				= 	'look/images/icons/i1.gif';
					$SubjectArr['field']['section']	=	$PowerBB->_POST['section'];

					$Insert = $PowerBB->core->Insert($SubjectArr,'subject');

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

					$exisT_s = '1';
				 }
				 else
				 {
				    $exisT_s = '0';
				 }
          	  }
			if ($exisT_s)
			{
		      $section = $PowerBB->_POST['section'];
		      $info_query = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['subject'] . " WHERE id and section = '$section' ORDER BY id ASC");
		      $info_row   = $PowerBB->DB->sql_fetch_array($info_query);
		   		$LastArr = array();
		        $LastArr['writer'] 		= 	$info_row['writer'];
		   		$LastArr['title'] 		= 	$PowerBB->functions->CleanVariable($info_row['title'],'html');
		   		$LastArr['subject_id'] 	= 	$info_row['id'];
		   		$LastArr['date'] 		= 	$PowerBB->_CONF['date'];
		   		$LastArr['icon'] 		= 	'look/images/icons/i1.gif';
		   		$LastArr['where'] 		= 	(!$this->SectionInfo['sub_section']) ? array('id',$PowerBB->_POST['section']) : array('id',$this->SectionInfo['from_sub_section']);

		   		// Update Last subject's information
		   		$UpdateLast = $PowerBB->core->Update($LastArr,'section');


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

		    		//////////

		    		// Update section's cache
				    $PowerBB->functions->UpdateSectionCache($PowerBB->_POST['section']);

		     }
        }
    		//////////

		$MemberArr 			= 	array();
		$MemberArr['where'] 	= 	array('username',$PowerBB->_POST['member']);
		$MemberInfo = $PowerBB->core->GetInfo($MemberArr,'member');

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

	       $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Has_been_saved_successfully_feed']);
           $PowerBB->functions->redirect('index.php?page=feeder&amp;control=1&amp;main=1');


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

				foreach($Items as $Item){
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
		                    //$Item['TITLE'] 	= 	$PowerBB->functions->CleanVariable($Item['TITLE'],'sql');
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

						$SubjectArr	=	array();
						$SubjectArr['field']	=	array();
						$SubjectArr['field']['title']	=	$Item['TITLE'];
						$SubjectArr['field']['text']	=	$text;
						$SubjectArr['field']['writer']	=	$MemberInfo['username'];
					    $SubjectArr['field']['write_time'] 			= 	$PowerBB->_CONF['now'];
					    $SubjectArr['field']['native_write_time'] 	= 	$PowerBB->_CONF['now'];
			            $SubjectArr['field']['icon'] 				= 	'look/images/icons/i1.gif';
						$SubjectArr['field']['section']	=	$FeedsInfo['forumid'];

						$Insert = $PowerBB->core->Insert($SubjectArr,'subject');

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

                     $exisT_s = '1';

					}
                   else
                   {
                   	$exisT_s = '0';
                   }
				  }
			  if ($exisT_s)
			  {
		      $section = $FeedsInfo['forumid'];
		      $info_query = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['subject'] . " WHERE id and section = '$section' ORDER BY id ASC");
		      $info_row   = $PowerBB->DB->sql_fetch_array($info_query);
		   		$LastArr = array();
		        $LastArr['writer'] 		= 	$info_row['writer'];
		   		$LastArr['title'] 		= 	$PowerBB->functions->CleanVariable($info_row['title'],'html');
		   		$LastArr['subject_id'] 	= 	$info_row['id'];
		   		$LastArr['date'] 		= 	$PowerBB->_CONF['date'];
		   		$LastArr['icon'] 		= 	'look/images/icons/i1.gif';
		   		$LastArr['where'] 		= 	(!$this->SectionInfo['sub_section']) ? array('id',$PowerBB->_POST['section']) : array('id',$this->SectionInfo['from_sub_section']);

		   		// Update Last subject's information
		   		$UpdateLast = $PowerBB->core->Update($LastArr,'section');


		         $Upsubject_nm = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['subject'] . " WHERE section='$section'"));

				$SecArr 			= 	array();
		        $SecArr['where'] 	= 	array('id',$FeedsInfo['forumid']);

				$this->SectionInfo = $PowerBB->core->GetInfo($SecArr,'section');

		              // The overall number of subjects
		    		$UpdateSubjectNumber = $PowerBB->cache->UpdateSubjectNumber(array('subject_num'	=>	$PowerBB->_CONF['info_row']['subject_number']));

		    		//////////

		    		// The number of section's subjects number
		    		// Update section's cache
		    		$PowerBB->functions->UpdateSectionCache($FeedsInfo['forumid']);

             }
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

}

?>
