<?php
(!defined('IN_PowerBB')) ? die() : '';

define('CLASS_NAME','PowerBBSearchEngineMOD');
include('common.php');
class PowerBBSearchEngineMOD
{
	function run()
	{
		global $PowerBB;

		$PowerBB->functions->ShowHeader();
		$this->_GetJumpSectionsList();


		$keyword 	= 	(isset($PowerBB->_GET['keyword'])) ? $PowerBB->_GET['keyword'] : $PowerBB->_POST['keyword'];
		$keyword=$PowerBB->functions->CleanVariable($keyword,'trim');

		$username 	= 	(isset($PowerBB->_GET['username'])) ? $PowerBB->_GET['username'] : $PowerBB->_POST['username'];
		$sort_order	= 	(isset($PowerBB->_GET['sort_order'])) ? $PowerBB->_GET['sort_order'] : $PowerBB->_POST['sort_order'];
        $exactname	= 	(isset($PowerBB->_GET['exactname'])) ? 0 : $PowerBB->_POST['exactname'];

		$starteronly	= 	(isset($PowerBB->_GET['starteronly'])) ? $PowerBB->_GET['starteronly'] : $PowerBB->_POST['starteronly'];
		$section	= 	(isset($PowerBB->_GET['section'])) ? $PowerBB->_GET['section'] : $PowerBB->_POST['section'];
		$option = (isset($PowerBB->_GET['option'])) ? $PowerBB->_GET['option'] : $PowerBB->_POST['option'];
		$search_only	= 	(isset($PowerBB->_GET['search_only'])) ? $PowerBB->_GET['search_only'] : $PowerBB->_POST['search_only'];

        $count         = $PowerBB->functions->CleanVariable($PowerBB->_GET['count'],'intval');
		$search_only	= 	$PowerBB->functions->CleanVariable($search_only,'intval');
		$option	= 	$PowerBB->functions->CleanVariable($option,'intval');
		if($section != 'all')
		{
		$section	= 	$PowerBB->functions->CleanVariable($section,'intval');
		}
		$sort_order    =    $PowerBB->functions->CleanVariable($sort_order,'trim');
		$keyword	= 	$PowerBB->functions->CleanVariable($keyword,'sql');
		$section	= 	$PowerBB->functions->CleanVariable($section,'sql');
		$username	= 	$PowerBB->functions->CleanVariable($username,'sql');
		$sort_order	= 	$PowerBB->functions->CleanVariable($sort_order,'sql');
		$search_only	= 	$PowerBB->functions->CleanVariable($search_only,'sql');
		$tag	= 	$PowerBB->functions->CleanVariable($tag,'sql');
		$starteronly	= 	$PowerBB->functions->CleanVariable($starteronly,'sql');
		$exactname	= 	$PowerBB->functions->CleanVariable($exactname,'sql');
		$option	= 	$PowerBB->functions->CleanVariable($option,'sql');
		$count	= 	$PowerBB->functions->CleanVariable($count,'sql');
        $PowerBB->_GET['review_reply']         = $PowerBB->functions->CleanVariable($PowerBB->_GET['review_reply'],'intval');

			if ($sort_order)
			{
		       	if (strtolower($sort_order) != 'desc'
		       	and strtolower($sort_order) != 'asc')
				{
				   $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['no_search_results']);
				}
			}



			if ($PowerBB->_GET['index'])
			{
				$this->_SearchForm();
			}
			elseif ($PowerBB->_GET['start'])
			{
				$this->_StartSearch();
			}
	        elseif($option == '1')
		    {
			$this->_StartSearchAllSection();
			}
			elseif($option == '2')
		    {
			$this->_StartSearchOneSection();
			}
			elseif($option == '3')
		    {
			$this->_StartSearchUsernameSubject();
			}
			elseif($option == '4')
		    {
			$this->_StartSearchUsernameReply();
			}
			elseif($option == '5')
			{
			$this->_StartSearchTag();
			}
			elseif($PowerBB->_GET['review_reply']
			and $PowerBB->_GET['option'] == '6')
			{
	          $this->_StartSearchReview_reply();

			}
			else
			{
			 $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
			}


         $PowerBB->functions->GetFooter();
	}



	/**
	 * Get the list of sections to show it in a list , and show search form
	 */
	function _SearchForm()
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

		$SecArr['where'][1] 			= 	array();
		$SecArr['where'][1]['con'] 		= 	'AND';
		$SecArr['where'][1]['name'] 	= 	'sec_section';
		$SecArr['where'][1]['oper'] 	= 	'<>';
		$SecArr['where'][1]['value'] 	= 	'1';

		$SecArr['where'][2] 			= 	array();
		$SecArr['where'][2]['con'] 		= 	'AND';
		$SecArr['where'][2]['name'] 	= 	'hide_subject';
		$SecArr['where'][2]['oper'] 	= 	'<>';
		$SecArr['where'][2]['value'] 	= 	'1';

		// Get main sections
		$cats = $PowerBB->core->GetList($SecArr,'section');

		// Loop to read the information of main sections
		foreach ($cats as $cat)
		{
			@include("cache/sectiongroup_cache/sectiongroup_cache_".$cat['id'].".php");
	       // Get the groups information to know view this section or not
	      $sectiongroup = json_decode(base64_decode($sectiongroup_cache), true);
		  if ($sectiongroup[$PowerBB->_CONF['group_info']['id']]['view_section'])
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
					     @include("cache/sectiongroup_cache/sectiongroup_cache_".$forum['id'].".php");
						$groups = json_decode(base64_decode($sectiongroup_cache), true);
						if ($groups[$PowerBB->_CONF['group_info']['id']]['view_section'])
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
								            @include("cache/sectiongroup_cache/sectiongroup_cache_".$sub['id'].".php");
									        $groups = json_decode(base64_decode($sectiongroup_cache), true);

												if (!$forum['is_sub'])
												{
													$forum['is_sub'] = 1;
												}
											  if ($groups[$PowerBB->_CONF['group_info']['id']]['view_section'])
											   {
												 $forum['sub'] .= ('<option value="' .$sub['id'] . '" >--- '  . $sub['title'] . '</option>');

										        }
										  }


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
											            @include("cache/sectiongroup_cache/sectiongroup_cache_".$sub_sub['id'].".php");
												        $groups = json_decode(base64_decode($sectiongroup_cache), true);

														  if ($groups[$PowerBB->_CONF['group_info']['id']]['view_section'])
														   {
																	if (!$forum['is_sub_sub'])
																	{
																		$forum['is_sub_sub'] = 1;
																	}

															 $forum['sub_sub'] .= ('<option value="' .$sub_sub['id'] . '">---- '  . $sub_sub['title'] . '</option>');
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

		$PowerBB->template->display('search');

			 ////////
			// Where is the member now?
		if ($PowerBB->_CONF['member_permission'])
     	{
     		$UpdateOnline 			= 	array();
			$UpdateOnline['field']	=	array();

			$UpdateOnline['field']['user_location'] 	= (	$PowerBB->_CONF['template']['_CONF']['lang']['search_in_Forum'].'<a href="index.php?page=search&amp;index=1">' .$PowerBB->_CONF['template']['_CONF']['lang']['Search_Engine'].'</a>');
			$UpdateOnline['where']						=	array('username',$PowerBB->_CONF['member_row']['username']);

			$update = $PowerBB->core->Update($UpdateOnline,'online');
     	}

	}

	function __GetGroup()
	{
		global $PowerBB;
		$section 	= 	(isset($PowerBB->_GET['section'])) ? $PowerBB->_GET['section'] : $PowerBB->_POST['section'];

		/** Get section's group information and make some checks **/
		$SecGroupArr 						= 	array();
		$SecGroupArr['where'] 				= 	array();
		$SecGroupArr['where'][0]			=	array();
		$SecGroupArr['where'][0]['name'] 	= 	'section_id';
		$SecGroupArr['where'][0]['value'] 	= 	$section;
		$SecGroupArr['where'][1]			=	array();
		$SecGroupArr['where'][1]['con']		=	'AND';
		$SecGroupArr['where'][1]['name']	=	'group_id';
		$SecGroupArr['where'][1]['value']	=	$PowerBB->_CONF['group_info']['id'];
		// Finally get the permissions of group
		$this->SectionGroup = $PowerBB->group->GetSectionGroupInfo($SecGroupArr);
		$PowerBB->template->assign('section_group',$this->SectionGroup);

	}

	function _StartSearch()
	{
		global $PowerBB;
		if (is_numeric($PowerBB->_CONF['member_row']['lastsearch_time']) && is_numeric($PowerBB->_CONF['info_row']['flood_search'])) {
		 $flood_search = ($PowerBB->_CONF['member_row']['lastsearch_time'] - @time() + $PowerBB->_CONF['info_row']['flood_search']);
		} else {
		  $flood_search = $PowerBB->_CONF['info_row']['flood_search'];
		}
       if ((@time() - $PowerBB->_CONF['info_row']['flood_search']) <= $PowerBB->_CONF['member_row']['lastsearch_time'])
       {
          $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['flood_search1']. ' ' .$flood_search . ' ' .$PowerBB->_CONF['template']['_CONF']['lang']['flood_search2']);
       }
      else
	   {

		$keyword 	= 	(isset($PowerBB->_GET['keyword'])) ? $PowerBB->_GET['keyword'] : $PowerBB->_POST['keyword'];
		$keyword=$PowerBB->functions->CleanVariable($keyword,'trim');
		$tag = (isset($PowerBB->_GET['tag'])) ? $PowerBB->_GET['tag'] : $PowerBB->_POST['tag'];

		$username 	= 	(isset($PowerBB->_GET['username'])) ? $PowerBB->_GET['username'] : $PowerBB->_POST['username'];
		$sort_order	= 	(isset($PowerBB->_GET['sort_order'])) ? $PowerBB->_GET['sort_order'] : $PowerBB->_POST['sort_order'];
        $exactname	= 	(isset($PowerBB->_GET['exactname'])) ? $PowerBB->_GET['exactname'] : $PowerBB->_POST['exactname'];
		$starteronly	= 	(isset($PowerBB->_GET['starteronly'])) ? $PowerBB->_GET['starteronly'] : $PowerBB->_POST['starteronly'];
		$section	= 	(isset($PowerBB->_GET['section'])) ? $PowerBB->_GET['section'] : $PowerBB->_POST['section'];
		$option = (isset($PowerBB->_GET['option'])) ? $PowerBB->_GET['option'] : $PowerBB->_POST['option'];
		$search_only	= 	(isset($PowerBB->_GET['search_only'])) ? $PowerBB->_GET['search_only'] : $PowerBB->_POST['search_only'];
		$tag	= 	$PowerBB->functions->CleanVariable($tag,'sql');
        $count         = $PowerBB->functions->CleanVariable($PowerBB->_GET['count'],'intval');
		$search_only	= 	$PowerBB->functions->CleanVariable($search_only,'intval');
		$option	= 	$PowerBB->functions->CleanVariable($option,'intval');
		if($section != 'all')
		{
		$section	= 	$PowerBB->functions->CleanVariable($section,'intval');
		}
		$sort_order    =    $PowerBB->functions->CleanVariable($sort_order,'trim');
		$keyword	= 	$PowerBB->functions->CleanVariable($keyword,'sql');
		$section	= 	$PowerBB->functions->CleanVariable($section,'sql');
		$username	= 	$PowerBB->functions->CleanVariable($username,'sql');
		$sort_order	= 	$PowerBB->functions->CleanVariable($sort_order,'sql');
		$search_only	= 	$PowerBB->functions->CleanVariable($search_only,'sql');
		$tag	= 	$PowerBB->functions->CleanVariable($tag,'sql');
		$starteronly	= 	$PowerBB->functions->CleanVariable($starteronly,'sql');
		$exactname	= 	$PowerBB->functions->CleanVariable($exactname,'sql');
		$option	= 	$PowerBB->functions->CleanVariable($option,'sql');
		$count	= 	$PowerBB->functions->CleanVariable($count,'sql');

            if (!$keyword And !$username And !$tag)
			{
				$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_enter_the_word_search']);
			}


        $characters >= '1';
		if  (isset($keyword{$characters}))
		{
			$this->_StartSearchKeyword();
		}
		if  (isset($username{$characters}))
		{
			$this->_StartSearchUser();
		}
       	if  (isset($tag{$characters}))
		{
			$this->_SearchTag();
		}

	    $MemberArr 				= 	array();
  		$MemberArr['field'] 	= 	array();
   		$MemberArr['field']['lastsearch_time'] 	=	$PowerBB->_CONF['now'];
   		$MemberArr['where']						=	array('id',$PowerBB->_CONF['member_row']['id']);
 		$UpdateMember = $PowerBB->member->UpdateMember($MemberArr);
     }
	}

	function _StartSearchKeyword()
	{
		global $PowerBB;

		$keyword 	= 	(isset($PowerBB->_GET['keyword'])) ? $PowerBB->_GET['keyword'] : $PowerBB->_POST['keyword'];
		$keyword=$PowerBB->functions->CleanVariable($keyword,'trim');

		$username 	= 	(isset($PowerBB->_GET['username'])) ? $PowerBB->_GET['username'] : $PowerBB->_POST['username'];
		$sort_order	= 	(isset($PowerBB->_GET['sort_order'])) ? $PowerBB->_GET['sort_order'] : $PowerBB->_POST['sort_order'];
        $exactname	= 	(isset($PowerBB->_GET['exactname'])) ? $PowerBB->_GET['exactname'] : $PowerBB->_POST['exactname'];
		$starteronly	= 	(isset($PowerBB->_GET['starteronly'])) ? $PowerBB->_GET['starteronly'] : $PowerBB->_POST['starteronly'];
		$section	= 	(isset($PowerBB->_GET['section'])) ? $PowerBB->_GET['section'] : $PowerBB->_POST['section'];
		$option = (isset($PowerBB->_GET['option'])) ? $PowerBB->_GET['option'] : $PowerBB->_POST['option'];
		$search_only	= 	(isset($PowerBB->_GET['search_only'])) ? $PowerBB->_GET['search_only'] : $PowerBB->_POST['search_only'];

        $count         = $PowerBB->functions->CleanVariable($PowerBB->_GET['count'],'intval');
		$search_only	= 	$PowerBB->functions->CleanVariable($search_only,'intval');
		$option	= 	$PowerBB->functions->CleanVariable($option,'intval');
		if($section != 'all')
		{
		$section	= 	$PowerBB->functions->CleanVariable($section,'intval');
		}
		$sort_order    =    $PowerBB->functions->CleanVariable($sort_order,'trim');
		$keyword	= 	$PowerBB->functions->CleanVariable($keyword,'sql');
		$section	= 	$PowerBB->functions->CleanVariable($section,'sql');
		$username	= 	$PowerBB->functions->CleanVariable($username,'sql');
		$sort_order	= 	$PowerBB->functions->CleanVariable($sort_order,'sql');
		$search_only	= 	$PowerBB->functions->CleanVariable($search_only,'sql');
		$tag	= 	$PowerBB->functions->CleanVariable($tag,'sql');
		$starteronly	= 	$PowerBB->functions->CleanVariable($starteronly,'sql');
		$exactname	= 	$PowerBB->functions->CleanVariable($exactname,'sql');
		$option	= 	$PowerBB->functions->CleanVariable($option,'sql');
		$count	= 	$PowerBB->functions->CleanVariable($count,'sql');

        if ($PowerBB->core->Is(array('where' => array('sec_section','1')),'section'))
		{
         $SectionInfo = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['section'] . " WHERE sec_section<>0");
		}
		else
		{
         $SectionInfo = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['section'] . " WHERE sec_section<>1");
		}


		$characters_keyword_search = $keyword;
		$characters_keyword_search = preg_replace('/\s+/', '', $characters_keyword_search);
		$keyword_less_num = strlen($characters_keyword_search) >= $PowerBB->_CONF['info_row']['characters_keyword_search'];
		if  ($keyword_less_num)
		{
		// Continue
		}
		else
		{
		$stop = ($PowerBB->_CONF['info_row']['ajax_search'] and !$PowerBB->_POST['ajax']) ? false : true;
		$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['characters_keyword_search'],$stop);
		}

   	   if ($username)
		{
			$this->_StartSearchUser();
		}


       if ($section == 'all')
       {
           if ($search_only == '1')
	        {

				$subject_nm = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['subject'] . "  WHERE text LIKE '%$keyword%' or title LIKE '%$keyword%' AND delete_topic<>'1' AND review_subject<>'1' AND sec_subject<>'1'"));

	          if ($subject_nm  > '0')
	          {
		       $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Search_successful']);
		       $PowerBB->functions->redirect('index.php?page=search&amp;option=1&amp;keyword='.$keyword.'&amp;search_only=1&amp;section=all&amp;sort_order='.strtoupper($sort_order));
	           }
		      else
		       {
		       $stop = ($PowerBB->_CONF['info_row']['ajax_search'] and !$PowerBB->_POST['ajax']) ? false : true;
			   $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['no_search_results'],$stop);
		       }

	        }
	        if ($search_only == '2')
	        {
             	 $reply_nm = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['reply'] . " WHERE text LIKE '%$keyword%' or title LIKE '%$keyword%' AND delete_topic<>'1' AND review_reply<>'1'"));
		          if ($reply_nm  > '0')
		          {
			       $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Search_successful']);
			       $PowerBB->functions->redirect('index.php?page=search&amp;option=1&amp;keyword='.$keyword.'&amp;search_only=2&amp;section=all&amp;sort_order='.strtoupper($sort_order));
		           }
			      else
			       {
			       $stop = ($PowerBB->_CONF['info_row']['ajax_search'] and !$PowerBB->_POST['ajax']) ? false : true;
				   $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['no_search_results'],$stop);
			       }

	        }

	        if ($search_only == '3')
	        {
				$subject_title = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['subject'] . "  WHERE title LIKE '%$keyword%' AND delete_topic<>'1' AND review_subject<>'1' AND sec_subject<>'1'"));
	          if ($subject_title > '0')
	          {
		       $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Search_successful']);
		       $PowerBB->functions->redirect('index.php?page=search&amp;option=1&amp;keyword='.$keyword.'&amp;search_only=3&amp;section=all&amp;sort_order='.strtoupper($sort_order));
	           }
		      else
		       {
		       $stop = ($PowerBB->_CONF['info_row']['ajax_search'] and !$PowerBB->_POST['ajax']) ? false : true;
			   $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['no_search_results'],$stop);
		       }
	        }
        }
      else
       {

            if ($search_only == '1')
	        {
          	$subject_nm = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['subject'] . " WHERE text LIKE '%$keyword%' AND section = '$section' AND delete_topic<>'1' AND review_subject<>'1' AND sec_subject<>'1'"));

	          if ($subject_nm  > '0')
	          {
		       $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Search_successful']);
		       $PowerBB->functions->redirect('index.php?page=search&amp;option=2&amp;keyword='.$keyword.'&amp;search_only=1&amp;section=' . $section . '&amp;sort_order='.strtoupper($sort_order));
	           }
		      else
		       {
		       $stop = ($PowerBB->_CONF['info_row']['ajax_search'] and !$PowerBB->_POST['ajax']) ? false : true;
			   $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['no_search_results'],$stop);
		       }

	        }

	        if ($search_only == '2')
	        {
			$reply_nm = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['reply'] . " WHERE text LIKE '%$keyword%' AND section = '$section' AND delete_topic<>'1' AND review_reply<>'1'"));

	          if ($reply_nm  > '0')
	          {
		       $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Search_successful']);
		       $PowerBB->functions->redirect('index.php?page=search&amp;option=2&amp;keyword='.$keyword.'&amp;search_only=2&amp;section=' . $section . '&amp;sort_order='.strtoupper($sort_order));
	           }
		      else
		       {
		       $stop = ($PowerBB->_CONF['info_row']['ajax_search'] and !$PowerBB->_POST['ajax']) ? false : true;
			   $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['no_search_results'],$stop);
		       }

	        }

	        if ($search_only == '3')
	        {
            $subject_title = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['subject'] . " WHERE title LIKE '%$keyword%' AND section = '$section' AND delete_topic<>'1' AND review_subject<>'1' AND sec_subject<>'1'"));

	          if ($subject_title > '0')
	          {
		       $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Search_successful']);
		       $PowerBB->functions->redirect('index.php?page=search&amp;option=2&amp;keyword='.$keyword.'&amp;search_only=3&amp;section=' . $section . '&amp;sort_order='.strtoupper($sort_order));
	          }
		      else
		       {
		       $stop = ($PowerBB->_CONF['info_row']['ajax_search'] and !$PowerBB->_POST['ajax']) ? false : true;
			   $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['no_search_results'],$stop);
		       }

	        }
       }

    }

	function _StartSearchUser()
	{
		global $PowerBB;

		$keyword 	= 	(isset($PowerBB->_GET['keyword'])) ? $PowerBB->_GET['keyword'] : $PowerBB->_POST['keyword'];
		$keyword=$PowerBB->functions->CleanVariable($keyword,'trim');

		$username 	= 	(isset($PowerBB->_GET['username'])) ? $PowerBB->_GET['username'] : $PowerBB->_POST['username'];
		$sort_order	= 	(isset($PowerBB->_GET['sort_order'])) ? $PowerBB->_GET['sort_order'] : $PowerBB->_POST['sort_order'];
        $exactname	= 	(isset($PowerBB->_GET['exactname'])) ? $PowerBB->_GET['exactname'] : $PowerBB->_POST['exactname'];
			   if (!isset($exactname))
	           {
	           	$exactname    =  0;
		       }
		$starteronly	= 	(isset($PowerBB->_GET['starteronly'])) ? $PowerBB->_GET['starteronly'] : $PowerBB->_POST['starteronly'];
		$section	= 	(isset($PowerBB->_GET['section'])) ? $PowerBB->_GET['section'] : $PowerBB->_POST['section'];
		$option = (isset($PowerBB->_GET['option'])) ? $PowerBB->_GET['option'] : $PowerBB->_POST['option'];
		$search_only	= 	(isset($PowerBB->_GET['search_only'])) ? $PowerBB->_GET['search_only'] : $PowerBB->_POST['search_only'];

        $count         = $PowerBB->functions->CleanVariable($PowerBB->_GET['count'],'intval');
		$search_only	= 	$PowerBB->functions->CleanVariable($search_only,'intval');
		$option	= 	$PowerBB->functions->CleanVariable($option,'intval');
		if($section != 'all')
		{
		$section	= 	$PowerBB->functions->CleanVariable($section,'intval');
		}
		$sort_order    =    $PowerBB->functions->CleanVariable($sort_order,'trim');
		$keyword	= 	$PowerBB->functions->CleanVariable($keyword,'sql');
		$section	= 	$PowerBB->functions->CleanVariable($section,'sql');
		$username	= 	$PowerBB->functions->CleanVariable($username,'sql');
		$sort_order	= 	$PowerBB->functions->CleanVariable($sort_order,'sql');
		$search_only	= 	$PowerBB->functions->CleanVariable($search_only,'sql');
		$tag	= 	$PowerBB->functions->CleanVariable($tag,'sql');
		$starteronly	= 	$PowerBB->functions->CleanVariable($starteronly,'sql');
		$exactname	= 	$PowerBB->functions->CleanVariable($exactname,'sql');
		$option	= 	$PowerBB->functions->CleanVariable($option,'sql');
		$count	= 	$PowerBB->functions->CleanVariable($count,'sql');

        if ($PowerBB->core->Is(array('where' => array('sec_section','1')),'section'))
		{
         $SectionInfo = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['section'] . " WHERE sec_section<>0");
		}
		else
		{
         $SectionInfo = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['section'] . " WHERE sec_section<>1");
		}

		if (empty($username))
		{
		 $stop = ($PowerBB->_CONF['info_row']['ajax_search'] and !$PowerBB->_POST['ajax']) ? false : true;
		 $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_write_the_name_of_the_member'],$stop);
		}


        if ($section == 'all')
		{
              $section = "";
              $sectionall = "all";
		 }
		 else
		{
              $section = " AND section = '".$section."' ";
              $sectionall = $PowerBB->_GET['section'];
		 }

       $forum_not = $PowerBB->_CONF['info_row']['last_subject_writer_not_in'];
			   if ($exactname == 0)
	           {
	           	$user_name    =  '%' .$username .'%';
		       }
		       else
	           {
	           	$user_name    =  $username;
		       }

           if ($starteronly == '0')
	        {
       	        $username_subject_nm = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['subject'] . " WHERE writer LIKE '$user_name' AND delete_topic<>'1' AND review_subject<>'1' AND sec_subject<>'1' ".$section." "));
	          if ($username_subject_nm > '0')
	          {
		       $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Search_successful']);
		       $PowerBB->functions->redirect('index.php?page=search&amp;option=3&amp;username=' . $username . '&amp;starteronly=0&amp;section=' . $sectionall . '&amp;exactname=' . $exactname . '&amp;sort_order='.strtoupper($sort_order));
	           }
		      else
		       {
		       $stop = ($PowerBB->_CONF['info_row']['ajax_search'] and !$PowerBB->_POST['ajax']) ? false : true;
			   $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['no_search_results'],$stop);
		       }
	           }
		      elseif ($starteronly == '1')
		       {
	             $username_reply_nm = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['reply'] . " WHERE writer LIKE '$username' AND section not in (" .$forum_not. ") AND delete_topic<>1 AND review_reply<>1 ".$section." "));
		         $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Search_successful']);
	          if ($username_reply_nm > '0')
	          {
		       if ($exactname)
	           {
		       $PowerBB->functions->redirect('index.php?page=search&amp;option=4&amp;username=' . $username . '&amp;starteronly=0&amp;section=' . $sectionall . '&amp;exactname=' . $exactname . '&amp;sort_order='.strtoupper($sort_order));
		       }
		       else
		       {
		       $PowerBB->functions->redirect('index.php?page=search&amp;option=4&amp;username=' . $username . '&amp;starteronly=0&amp;section=' . $sectionall . '&amp;exactname=' . $exactname . '&amp;sort_order='.strtoupper($sort_order));
		       }
	           }
		      else
		       {
		       $stop = ($PowerBB->_CONF['info_row']['ajax_search'] and !$PowerBB->_POST['ajax']) ? false : true;
			   $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['no_search_results'],$stop);
		       }
	        }


    }

	function _SearchTag()
	{
		global $PowerBB;

		$tag = (isset($PowerBB->_GET['tag'])) ? $PowerBB->_GET['tag'] : $PowerBB->_POST['tag'];
		$sort_order	= 	(isset($PowerBB->_GET['sort_order'])) ? $PowerBB->_GET['sort_order'] : $PowerBB->_POST['sort_order'];

		$username 	= 	(isset($PowerBB->_GET['username'])) ? $PowerBB->_GET['username'] : $PowerBB->_POST['username'];
		$sort_order	= 	(isset($PowerBB->_GET['sort_order'])) ? $PowerBB->_GET['sort_order'] : $PowerBB->_POST['sort_order'];
        $exactname	= 	(isset($PowerBB->_GET['exactname'])) ? $PowerBB->_GET['exactname'] : $PowerBB->_POST['exactname'];
		$starteronly	= 	(isset($PowerBB->_GET['starteronly'])) ? $PowerBB->_GET['starteronly'] : $PowerBB->_POST['starteronly'];
		$section	= 	(isset($PowerBB->_GET['section'])) ? $PowerBB->_GET['section'] : $PowerBB->_POST['section'];
		$option = (isset($PowerBB->_GET['option'])) ? $PowerBB->_GET['option'] : $PowerBB->_POST['option'];
		$search_only	= 	(isset($PowerBB->_GET['search_only'])) ? $PowerBB->_GET['search_only'] : $PowerBB->_POST['search_only'];

        $count         = $PowerBB->functions->CleanVariable($PowerBB->_GET['count'],'intval');
		$search_only	= 	$PowerBB->functions->CleanVariable($search_only,'intval');
		$option	= 	$PowerBB->functions->CleanVariable($option,'intval');
		if($section != 'all')
		{
		$section	= 	$PowerBB->functions->CleanVariable($section,'intval');
		}
		$sort_order    =    $PowerBB->functions->CleanVariable($sort_order,'trim');
		$keyword	= 	$PowerBB->functions->CleanVariable($keyword,'sql');
		$section	= 	$PowerBB->functions->CleanVariable($section,'sql');
		$username	= 	$PowerBB->functions->CleanVariable($username,'sql');
		$sort_order	= 	$PowerBB->functions->CleanVariable($sort_order,'sql');
		$search_only	= 	$PowerBB->functions->CleanVariable($search_only,'sql');
		$tag	= 	$PowerBB->functions->CleanVariable($tag,'sql');
		$starteronly	= 	$PowerBB->functions->CleanVariable($starteronly,'sql');
		$exactname	= 	$PowerBB->functions->CleanVariable($exactname,'sql');
		$option	= 	$PowerBB->functions->CleanVariable($option,'sql');
		$count	= 	$PowerBB->functions->CleanVariable($count,'sql');

		if (empty($tag))
		{
			$stop = ($PowerBB->_CONF['info_row']['ajax_search'] and !$PowerBB->_POST['ajax']) ? false : true;
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_write_tag_word'],$stop);
		}

        $TotalArr 			= 	array();
		$TotalArr['where'] 	= 	array('tag',$tag);
        $tag_nm = $PowerBB->tag->GetSubjectNumber($TotalArr);

	          if ($tag_nm > '0')
	          {
		       $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Search_successful']);
		       $PowerBB->functions->redirect('index.php?page=search&amp;option=5&amp;tag=' . $tag . '&amp;sort_order='.strtoupper($sort_order));
	           }
		      else
		       {
		       $stop = ($PowerBB->_CONF['info_row']['ajax_search'] and !$PowerBB->_POST['ajax']) ? false : true;
			   $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['no_search_results'],$stop);
		       }

    }

    function _StartSearchAllSection()
	{
	      global $PowerBB;

		$keyword 	= 	(isset($PowerBB->_GET['keyword'])) ? $PowerBB->_GET['keyword'] : $PowerBB->_POST['keyword'];
		$keyword=$PowerBB->functions->CleanVariable($keyword,'trim');

		$username 	= 	(isset($PowerBB->_GET['username'])) ? $PowerBB->_GET['username'] : $PowerBB->_POST['username'];
		$sort_order	= 	(isset($PowerBB->_GET['sort_order'])) ? $PowerBB->_GET['sort_order'] : $PowerBB->_POST['sort_order'];
        $exactname	= 	(isset($PowerBB->_GET['exactname'])) ? $PowerBB->_GET['exactname'] : $PowerBB->_POST['exactname'];
		$starteronly	= 	(isset($PowerBB->_GET['starteronly'])) ? $PowerBB->_GET['starteronly'] : $PowerBB->_POST['starteronly'];
		$section	= 	(isset($PowerBB->_GET['section'])) ? $PowerBB->_GET['section'] : $PowerBB->_POST['section'];
		$option = (isset($PowerBB->_GET['option'])) ? $PowerBB->_GET['option'] : $PowerBB->_POST['option'];
		$search_only	= 	(isset($PowerBB->_GET['search_only'])) ? $PowerBB->_GET['search_only'] : $PowerBB->_POST['search_only'];

        $count         = $PowerBB->functions->CleanVariable($PowerBB->_GET['count'],'intval');
		$search_only	= 	$PowerBB->functions->CleanVariable($search_only,'intval');
		$option	= 	$PowerBB->functions->CleanVariable($option,'intval');
		if($section != 'all')
		{
		$section	= 	$PowerBB->functions->CleanVariable($section,'intval');
		}
		$sort_order    =    $PowerBB->functions->CleanVariable($sort_order,'trim');
		$keyword	= 	$PowerBB->functions->CleanVariable($keyword,'sql');
		$section	= 	$PowerBB->functions->CleanVariable($section,'sql');
		$username	= 	$PowerBB->functions->CleanVariable($username,'sql');
		$sort_order	= 	$PowerBB->functions->CleanVariable($sort_order,'sql');
		$search_only	= 	$PowerBB->functions->CleanVariable($search_only,'sql');
		$tag	= 	$PowerBB->functions->CleanVariable($tag,'sql');
		$starteronly	= 	$PowerBB->functions->CleanVariable($starteronly,'sql');
		$exactname	= 	$PowerBB->functions->CleanVariable($exactname,'sql');
		$option	= 	$PowerBB->functions->CleanVariable($option,'sql');
		$count	= 	$PowerBB->functions->CleanVariable($count,'sql');

       if ($section != 'all'
       and $section != is_numeric($section))
		{
		   $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['no_search_results']);
		 }

        if ($PowerBB->core->Is(array('where' => array('sec_section','1')),'section'))
		{
         $SectionInfo = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['section'] . " WHERE sec_section<>0");
		}
		else
		{
         $SectionInfo = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['section'] . " WHERE sec_section<>1");
		}

        $sec = ' AND section =  ';
	        if ($search_only == '1')
             {

				$subject_nm = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['subject'] . "  WHERE text LIKE '%$keyword%' or title LIKE '%$keyword%' AND delete_topic<>'1' AND review_subject<>'1' AND sec_subject<>'1'"));
				$PowerBB->template->assign('nm',$subject_nm);
                $SubjectArr 						= 	array();
				$SubjectArr['where'] 				= 	array();
				$SubjectArr['where'][0] 			= 	array();
				$SubjectArr['where'][0]['name'] 	=  'text LIKE "%'.$keyword.'%" or title';
				$SubjectArr['where'][0]['oper']		=  'LIKE';
				$SubjectArr['where'][0]['value']    =  '%'.$keyword.'%';
                $SubjectArr['where'][1] 			= 	array();
				$SubjectArr['where'][1]['con']		=	'AND';
				$SubjectArr['where'][1]['name'] 	= 	'review_subject<>1 AND sec_subject<>1 AND delete_topic';
				$SubjectArr['where'][1]['oper'] 	= 	'<>';
				$SubjectArr['where'][1]['value'] 	= 	'1';
				$SubjectArr['order'] 			= 	array();
				$SubjectArr['order']['field'] 	= 	'id';
				$SubjectArr['order']['type'] 	= 	strtoupper($sort_order);
				$SubjectArr['proc'] 						= 	array();
				// Ok Mr.XSS go to hell !
				$SubjectArr['proc']['*'] 					= 	array('method'=>'clean','param'=>'html');
				$SubjectArr['proc']['native_write_time'] 	= 	array('method'=>'date','store'=>'write_date','type'=>$PowerBB->_CONF['info_row']['timesystem']);
				$SubjectArr['proc']['write_time'] 			= 	array('method'=>'date','store'=>'reply_date','type'=>$PowerBB->_CONF['info_row']['timesystem']);

               if ($subject_nm  == '0')
	          {
		       $stop = ($PowerBB->_CONF['info_row']['ajax_search'] and !$PowerBB->_POST['ajax']) ? false : true;
			   $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['no_search_results'],$stop);
	          }
	         else
	          {
	               	// Pager setup
					$SubjectArr['pager'] 				= 	array();
					$SubjectArr['pager']['total']		= 	$subject_nm;
					if ($subject_nm > $PowerBB->_CONF['info_row']['perpage'])
			        {
					$SubjectArr['pager']['perpage'] 	= 	$PowerBB->_CONF['info_row']['perpage'];
			        }
			       else
			        {
		            $SubjectArr['pager']['perpage'] 	= 	$subject_nm;
			        }
			        $SubjectArr['pager']['count'] 		= 	$count;
					$SubjectArr['pager']['location'] 	= 	'index.php?page=search&amp;option=1&amp;keyword='.$keyword.'&amp;search_only=1&amp;section=all&amp;sort_order='.strtoupper($sort_order);
					$SubjectArr['pager']['var'] 		= 	'count';
	           }

				$PowerBB->_CONF['template']['while']['SubjectList'] = $PowerBB->core->GetList($SubjectArr,'subject');
               $PowerBB->_CONF['template']['PagerLocation'] = $SubjectArr['pager']['location'];
             }

             if ($search_only == '2')
             {
             	 $reply_nm = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['reply'] . " WHERE text LIKE '%$keyword%' or title LIKE '%$keyword%' AND delete_topic<>'1' AND review_reply<>'1'"));
                 $PowerBB->template->assign('nm',$reply_nm);
				$ReplyArr 						= 	array();
				$ReplyArr['where']				=	array();
				$ReplyArr['where'][0]			=	array();
				$ReplyArr['where'][0]['name']	=	'review_reply<>1 AND delete_topic<>1 AND text LIKE "%'.$keyword.'%" or title';
				$ReplyArr['where'][0]['oper'] 	= 	'LIKE';
				$ReplyArr['where'][0]['value']	=    '%'.$keyword.'%';

				$ReplyArr['order'] 			= 	array();
				$ReplyArr['order']['field'] 	= 	'id';
				$ReplyArr['order']['type'] 	= 	strtoupper($sort_order);

				$ReplyArr['proc'] 						= 	array();
				// Ok Mr.XSS go to hell !
				$ReplyArr['proc']['*'] 					= 	array('method'=>'clean','param'=>'html');
				$ReplyArr['proc']['write_time'] 		= 	array('method'=>'date','store'=>'reply_date','type'=>$PowerBB->_CONF['info_row']['timesystem']);

			 if ($reply_nm  == '0')
	          {
		       $stop = ($PowerBB->_CONF['info_row']['ajax_search'] and !$PowerBB->_POST['ajax']) ? false : true;
			   $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['no_search_results'],$stop);
	          }
	         else
	          {
                // Pager setup
				$ReplyArr['pager'] 				= 	array();
				$ReplyArr['pager']['total']		= 	$reply_nm ;
				if ($reply_nm > $PowerBB->_CONF['info_row']['perpage'])
		        {
				$ReplyArr['pager']['perpage'] 	= 	$PowerBB->_CONF['info_row']['perpage'];
		        }
		       else
		        {
	            $ReplyArr['pager']['perpage'] 	= 	$reply_nm;
		        }
		        $ReplyArr['pager']['count'] 	= 	$count;
				$ReplyArr['pager']['location'] 	= 	'index.php?page=search&amp;option=1&amp;keyword='.$keyword.'&amp;search_only=2&amp;section=all&amp;sort_order='.strtoupper($sort_order);
				$ReplyArr['pager']['var'] 		= 	'count';
               }
				$PowerBB->_CONF['template']['while']['ReplyList'] = $PowerBB->core->GetList($ReplyArr,'reply');
                $PowerBB->_CONF['template']['PagerLocation'] = $ReplyArr['pager']['location'];
                $PowerBB->_CONF['template']['_CONF']['ReplyList']['id'] = 1;
                $PowerBB->_CONF['template']['ReplyList']['id'] = 1;
                $PowerBB->template->assign('ReplyList',1);

              }

            if ($search_only == '3')
             {
             	$subject_title = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['subject'] . " WHERE title LIKE '%$keyword%' AND delete_topic<>'1' AND review_subject<>'1' AND sec_subject<>'1'"));
                $PowerBB->template->assign('nm',$subject_title);
				$SubjectTitleArr = array();

				$SubjectTitleArr['where'] 				= 	array();

				$SubjectTitleArr['where'][0] 			= 	array();
				$SubjectTitleArr['where'][0]['name'] 	= 	'title LIKE "%'.$keyword.'%" or title';
				$SubjectTitleArr['where'][0]['oper'] 	= 	'LIKE';
				$SubjectTitleArr['where'][0]['value'] 	= 	'%'.$keyword.'%';

                $SubjectTitleArr['where'][1] 			= 	array();
				$SubjectTitleArr['where'][1]['con']		=	'AND';
				$SubjectTitleArr['where'][1]['name'] 	= 	'review_subject<>1 AND sec_subject<>1 AND delete_topic';
				$SubjectTitleArr['where'][1]['oper'] 	= 	'<>';
				$SubjectTitleArr['where'][1]['value'] 	= 	'1';

				$SubjectTitleArr['order'] 			= 	array();
				$SubjectTitleArr['order']['field'] 	= 	'id';
				$SubjectTitleArr['order']['type'] 	= 	strtoupper($sort_order);

				$SubjectTitleArr['proc'] 						= 	array();
				// Ok Mr.XSS go to hell !
				$SubjectTitleArr['proc']['*'] 					= 	array('method'=>'clean','param'=>'html');
				$SubjectTitleArr['proc']['native_write_time'] 	= 	array('method'=>'date','store'=>'write_date','type'=>$PowerBB->_CONF['info_row']['timesystem']);
				$SubjectTitleArr['proc']['write_time'] 			= 	array('method'=>'date','store'=>'reply_date','type'=>$PowerBB->_CONF['info_row']['timesystem']);
			 if ($subject_title  == '0')
	          {
		       $stop = ($PowerBB->_CONF['info_row']['ajax_search'] and !$PowerBB->_POST['ajax']) ? false : true;
			   $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['no_search_results'],$stop);

	          }
	        else
	         {
                // Pager setup
				$SubjectTitleArr['pager'] 				= 	array();
				$SubjectTitleArr['pager']['total']		= 	$subject_title;
				if ($subject_title > $PowerBB->_CONF['info_row']['perpage'])
		        {
				$SubjectTitleArr['pager']['perpage'] 	= 	$PowerBB->_CONF['info_row']['perpage'];
		        }
		       else
		        {
	            $SubjectTitleArr['pager']['perpage'] 	= 	$subject_title;
		        }
				$SubjectTitleArr['pager']['count'] 		= 	$count;
				$SubjectTitleArr['pager']['location'] 	= 	'index.php?page=search&amp;option=1&amp;keyword='.$keyword.'&amp;search_only=3&amp;section=all&amp;sort_order='.strtoupper($sort_order);
				$SubjectTitleArr['pager']['var'] 		= 	'count';
              }
				$PowerBB->_CONF['template']['while']['SubjectTitleList'] = $PowerBB->core->GetList($SubjectTitleArr,'subject');
               $PowerBB->_CONF['template']['PagerLocation'] = $SubjectTitleArr['pager']['location'];

           }

                	if ($subject_nm > $PowerBB->_CONF['info_row']['perpage'])
			        {
					$PowerBB->template->assign('pager',$PowerBB->pager->show());
			        }
			        if ($subject_title > $PowerBB->_CONF['info_row']['perpage'])
			        {
					$PowerBB->template->assign('pager',$PowerBB->pager->show());
			        }
			       if ($reply_nm > $PowerBB->_CONF['info_row']['perpage'])
			        {
					$PowerBB->template->assign('pager',$PowerBB->pager->show());
			        }

				$PowerBB->template->assign('keyword',$keyword);
				$PowerBB->template->display('search_results_all');
	}



  function _StartSearchOneSection()
	{
	      global $PowerBB;

		$keyword 	= 	(isset($PowerBB->_GET['keyword'])) ? $PowerBB->_GET['keyword'] : $PowerBB->_POST['keyword'];
		$keyword=$PowerBB->functions->CleanVariable($keyword,'trim');

		$username 	= 	(isset($PowerBB->_GET['username'])) ? $PowerBB->_GET['username'] : $PowerBB->_POST['username'];
		$sort_order	= 	(isset($PowerBB->_GET['sort_order'])) ? $PowerBB->_GET['sort_order'] : $PowerBB->_POST['sort_order'];
        $exactname	= 	(isset($PowerBB->_GET['exactname'])) ? $PowerBB->_GET['exactname'] : $PowerBB->_POST['exactname'];
		$starteronly	= 	(isset($PowerBB->_GET['starteronly'])) ? $PowerBB->_GET['starteronly'] : $PowerBB->_POST['starteronly'];
		$section	= 	(isset($PowerBB->_GET['section'])) ? $PowerBB->_GET['section'] : $PowerBB->_POST['section'];
		$option = (isset($PowerBB->_GET['option'])) ? $PowerBB->_GET['option'] : $PowerBB->_POST['option'];
		$search_only	= 	(isset($PowerBB->_GET['search_only'])) ? $PowerBB->_GET['search_only'] : $PowerBB->_POST['search_only'];

        $count         = $PowerBB->functions->CleanVariable($PowerBB->_GET['count'],'intval');
		$search_only	= 	$PowerBB->functions->CleanVariable($search_only,'intval');
		$option	= 	$PowerBB->functions->CleanVariable($option,'intval');
		if($section != 'all')
		{
		$section	= 	$PowerBB->functions->CleanVariable($section,'intval');
		}
		$sort_order    =    $PowerBB->functions->CleanVariable($sort_order,'trim');
		$keyword	= 	$PowerBB->functions->CleanVariable($keyword,'sql');
		$section	= 	$PowerBB->functions->CleanVariable($section,'sql');
		$username	= 	$PowerBB->functions->CleanVariable($username,'sql');
		$sort_order	= 	$PowerBB->functions->CleanVariable($sort_order,'sql');
		$search_only	= 	$PowerBB->functions->CleanVariable($search_only,'sql');
		$tag	= 	$PowerBB->functions->CleanVariable($tag,'sql');
		$starteronly	= 	$PowerBB->functions->CleanVariable($starteronly,'sql');
		$exactname	= 	$PowerBB->functions->CleanVariable($exactname,'sql');
		$option	= 	$PowerBB->functions->CleanVariable($option,'sql');
		$count	= 	$PowerBB->functions->CleanVariable($count,'sql');


       if ($section != 'all'
       and $section != is_numeric($section))
		{

		   $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['no_search_results']);
		 }


        $sec = ' AND section =  ';


          if ($search_only == '1')
          {
          	$subject_nm = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['subject'] . " WHERE text LIKE '%$keyword%' AND section = '$section' AND delete_topic<>'1' AND review_subject<>'1' AND sec_subject<>'1'"));

			$PowerBB->template->assign('nm',$subject_nm);

            $SubjectOneArr = array();

			$SubjectOneArr['where'] 				= 	array();
			$SubjectOneArr['where'][0] 			= 	array();
			$SubjectOneArr['where'][0]['name'] 	= 	'text LIKE ';
			$SubjectOneArr['where'][0]['oper']		=  "'".'%'.$keyword.'%'."'  $sec";
			$SubjectOneArr['where'][0]['value']    =  $section;


			$SubjectOneArr['where'][1] 			= 	array();
			$SubjectOneArr['where'][1]['con']		=	'AND';
			$SubjectOneArr['where'][1]['name'] 	= 	'review_subject<>1 AND sec_subject<>1 AND delete_topic';
			$SubjectOneArr['where'][1]['oper'] 	= 	'<>';
			$SubjectOneArr['where'][1]['value'] = 	'1';

			$SubjectOneArr['order'] 			= 	array();
			$SubjectOneArr['order']['field'] 	= 	'id';
			$SubjectOneArr['order']['type'] 	= 	strtoupper($sort_order);

			$SubjectOneArr['proc'] 						= 	array();
			$SubjectOneArr['proc']['*'] 					= 	array('method'=>'clean','param'=>'html');
			$SubjectOneArr['proc']['native_write_time'] 	= 	array('method'=>'date','store'=>'write_date','type'=>$PowerBB->_CONF['info_row']['timesystem']);
			$SubjectOneArr['proc']['write_time'] 			= 	array('method'=>'date','store'=>'reply_date','type'=>$PowerBB->_CONF['info_row']['timesystem']);

			 if ($subject_nm  == '0')
	          {
		       $stop = ($PowerBB->_CONF['info_row']['ajax_search'] and !$PowerBB->_POST['ajax']) ? false : true;
			   $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['no_search_results'],$stop);

	          }
	       else
	         {

			     // Pager setup
				$SubjectOneArr['pager'] 				= 	array();
				$SubjectOneArr['pager']['total']		= 	$subject_nm;
		        if ($subject_nm > $PowerBB->_CONF['info_row']['perpage'])
		        {
				$SubjectOneArr['pager']['perpage'] 	= 	$PowerBB->_CONF['info_row']['perpage'];
		        }
		       else
		        {
	            $SubjectOneArr['pager']['perpage'] 	= 	$subject_nm;
		        }
				$SubjectOneArr['pager']['count'] 		= 	$count;
				$SubjectOneArr['pager']['location'] 	= 	'index.php?page=search&amp;option=2&amp;keyword='.$keyword.'&amp;search_only=1&amp;section=' . $section . '&amp;sort_order='.strtoupper($sort_order);
				$SubjectOneArr['pager']['var'] 		= 	'count';
             }

			$PowerBB->_CONF['template']['while']['SubjectListOne'] = $PowerBB->core->GetList($SubjectOneArr,'subject');
            $PowerBB->_CONF['template']['PagerLocation'] = $SubjectOneArr['pager']['location'];
          }

         if ($search_only == '2')
          {

			$reply_nm = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['reply'] . " WHERE text LIKE '%$keyword%' AND section = '$section' AND delete_topic<>'1' AND review_reply<>'1'"));
            $PowerBB->template->assign('nm',$reply_nm);

			$ReplyOneArr 						= 	array();

			$ReplyOneArr['where']				=	array();
			$ReplyOneArr['where'][0]			=	array();
			$ReplyOneArr['where'][0]['name']	=	'text LIKE ';
			$ReplyOneArr['where'][0]['oper'] 	= 	"'".'%'.$keyword.'%'."'  $sec";
			$ReplyOneArr['where'][0]['value']	=    $section;

			$ReplyOneArr['where'][1] 			= 	array();
			$ReplyOneArr['where'][1]['con']		=	'AND';
			$ReplyOneArr['where'][1]['name'] 	= 	'review_reply<>1 AND delete_topic';
			$ReplyOneArr['where'][1]['oper'] 	= 	'<>';
			$ReplyOneArr['where'][1]['value'] 	= 	'1';


			$ReplyOneArr['order'] 			= 	array();
			$ReplyOneArr['order']['field'] 	= 	'id';
			$ReplyOneArr['order']['type'] 	= 	strtoupper($sort_order);


			$ReplyOneArr['proc'] 						= 	array();
			// Ok Mr.XSS go to hell !
			$ReplyOneArr['proc']['*'] 					= 	array('method'=>'clean','param'=>'html');
			$ReplyOneArr['proc']['native_write_time'] 	= 	array('method'=>'date','store'=>'write_date','type'=>$PowerBB->_CONF['info_row']['timesystem']);
			$ReplyOneArr['proc']['write_time'] 			= 	array('method'=>'date','store'=>'reply_date','type'=>$PowerBB->_CONF['info_row']['timesystem']);

			 if ($reply_nm  == '0')
	          {
		       $stop = ($PowerBB->_CONF['info_row']['ajax_search'] and !$PowerBB->_POST['ajax']) ? false : true;
			   $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['no_search_results'],$stop);

	          }
	        else
	          {
					// Pager setup
					$ReplyOneArr['pager'] 				= 	array();
					$ReplyOneArr['pager']['total']		= 	$reply_nm;
					if ($reply_nm > $PowerBB->_CONF['info_row']['perpage'])
			        {
					$ReplyOneArr['pager']['perpage'] 	= 	$PowerBB->_CONF['info_row']['perpage'];
			        }
			       else
			        {
		            $ReplyOneArr['pager']['perpage'] 	= 	$reply_nm;
			        }
					$ReplyOneArr['pager']['count'] 		= 	$count;
					$ReplyOneArr['pager']['location'] 	= 	'index.php?page=search&amp;option=2&amp;keyword='.$keyword.'&amp;search_only=2&amp;section=' . $section . '&amp;sort_order='.strtoupper($sort_order);

					$ReplyOneArr['pager']['var'] 		= 	'count';
              }

				$PowerBB->_CONF['template']['while']['ReplyListOne'] = $PowerBB->reply->GetReplyList($ReplyOneArr);
               $PowerBB->_CONF['template']['PagerLocation'] = $ReplyOneArr['pager']['location'];
              $PowerBB->template->assign('ReplyListOne',1);
           }

         if ($search_only == '3')
          {
            $subject_title = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['subject'] . " WHERE title LIKE '%$keyword%' AND section = '$section' AND delete_topic<>'1' AND review_subject<>'1' AND sec_subject<>'1'"));
            $PowerBB->template->assign('nm',$subject_title);

			$SubjectTitleOneArr = array();
			$SubjectTitleOneArr['where'] 				= 	array();
			$SubjectTitleOneArr['where'][0] 			= 	array();
			$SubjectTitleOneArr['where'][0]['name'] 	= 	'title LIKE ';
			$SubjectTitleOneArr['where'][0]['oper'] 	= 	"'".'%'.$keyword.'%'."'  $sec";
			$SubjectTitleOneArr['where'][0]['value'] 	= 	$section;

			$SubjectTitleOneArr['where'][1] 			= 	array();
			$SubjectTitleOneArr['where'][1]['con']		=	'AND';
			$SubjectTitleOneArr['where'][1]['name'] 	= 	'review_subject<>1 AND sec_subject<>1 AND delete_topic';
			$SubjectTitleOneArr['where'][1]['oper'] 	= 	'<>';
			$SubjectTitleOneArr['where'][1]['value'] 	= 	'1';

			$SubjectTitleOneArr['order'] 			= 	array();
			$SubjectTitleOneArr['order']['field'] 	= 	'id';
			$SubjectTitleOneArr['order']['type'] 	= 	strtoupper($sort_order);

			$SubjectTitleOneArr['proc'] 						= 	array();
			// Ok Mr.XSS go to hell !
			$SubjectTitleOneArr['proc']['*'] 					= 	array('method'=>'clean','param'=>'html');
			$SubjectTitleOneArr['proc']['native_write_time'] 	= 	array('method'=>'date','store'=>'write_date','type'=>$PowerBB->_CONF['info_row']['timesystem']);
			$SubjectTitleOneArr['proc']['write_time'] 			= 	array('method'=>'date','store'=>'reply_date','type'=>$PowerBB->_CONF['info_row']['timesystem']);

			 if ($subject_title  == '0')
	          {
		       $stop = ($PowerBB->_CONF['info_row']['ajax_search'] and !$PowerBB->_POST['ajax']) ? false : true;
			   $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['no_search_results'],$stop);

	          }
	        else
	         {
					    // Pager setup
					$SubjectTitleOneArr['pager'] 				= 	array();
					$SubjectTitleOneArr['pager']['total']		= 	$subject_title;
					if ($subject_title > $PowerBB->_CONF['info_row']['perpage'])
			        {
					$SubjectTitleOneArr['pager']['perpage'] 	= 	$PowerBB->_CONF['info_row']['perpage'];
			        }
			       else
			        {
		            $SubjectTitleOneArr['pager']['perpage'] 	= 	$subject_title;
			        }
					$SubjectTitleOneArr['pager']['count'] 		= 	$count;
					$SubjectTitleOneArr['pager']['location'] 	= 	'index.php?page=search&amp;option=2&amp;keyword='.$keyword.'&amp;search_only=3&amp;section=' . $section . '&amp;sort_order='.strtoupper($sort_order);

					$SubjectTitleOneArr['pager']['var'] 		= 	'count';

	          }

			$PowerBB->_CONF['template']['while']['SubjectTitleListOne'] = $PowerBB->core->GetList($SubjectTitleOneArr,'subject');
            $PowerBB->_CONF['template']['PagerLocation'] = $SubjectTitleOneArr['pager']['location'];
         }





		//////////

        	        if ($subject_nm > $PowerBB->_CONF['info_row']['perpage'])
			        {
					$PowerBB->template->assign('pager',$PowerBB->pager->show());
			        }
			        if ($subject_title > $PowerBB->_CONF['info_row']['perpage'])
			        {
					$PowerBB->template->assign('pager',$PowerBB->pager->show());
			        }
			       if ($reply_nm > $PowerBB->_CONF['info_row']['perpage'])
			        {
					$PowerBB->template->assign('pager',$PowerBB->pager->show());
			        }

				$PowerBB->template->assign('keyword',$keyword .$username);
				$PowerBB->template->display('search_results_one');

    }



   function _StartSearchUsernameSubject()
	 {
	   global $PowerBB;

		$keyword 	= 	(isset($PowerBB->_GET['keyword'])) ? $PowerBB->_GET['keyword'] : $PowerBB->_POST['keyword'];
		$keyword=$PowerBB->functions->CleanVariable($keyword,'trim');

		$username 	= 	(isset($PowerBB->_GET['username'])) ? $PowerBB->_GET['username'] : $PowerBB->_POST['username'];
		$sort_order	= 	(isset($PowerBB->_GET['sort_order'])) ? $PowerBB->_GET['sort_order'] : $PowerBB->_POST['sort_order'];
        $exactname	= 	(isset($PowerBB->_GET['exactname'])) ? $PowerBB->_GET['exactname'] : $PowerBB->_POST['exactname'];
		$starteronly	= 	(isset($PowerBB->_GET['starteronly'])) ? $PowerBB->_GET['starteronly'] : $PowerBB->_POST['starteronly'];
		$section	= 	(isset($PowerBB->_GET['section'])) ? $PowerBB->_GET['section'] : $PowerBB->_POST['section'];
		$option = (isset($PowerBB->_GET['option'])) ? $PowerBB->_GET['option'] : $PowerBB->_POST['option'];
		$search_only	= 	(isset($PowerBB->_GET['search_only'])) ? $PowerBB->_GET['search_only'] : $PowerBB->_POST['search_only'];

        $count         = $PowerBB->functions->CleanVariable($PowerBB->_GET['count'],'intval');
		$search_only	= 	$PowerBB->functions->CleanVariable($search_only,'intval');
		$option	= 	$PowerBB->functions->CleanVariable($option,'intval');
		if($section != 'all')
		{
		$section	= 	$PowerBB->functions->CleanVariable($section,'intval');
		}
		$sort_order    =    $PowerBB->functions->CleanVariable($sort_order,'trim');
		$keyword	= 	$PowerBB->functions->CleanVariable($keyword,'sql');
		$section	= 	$PowerBB->functions->CleanVariable($section,'sql');
		$username	= 	$PowerBB->functions->CleanVariable($username,'sql');
		$sort_order	= 	$PowerBB->functions->CleanVariable($sort_order,'sql');
		$search_only	= 	$PowerBB->functions->CleanVariable($search_only,'sql');
		$tag	= 	$PowerBB->functions->CleanVariable($tag,'sql');
		$starteronly	= 	$PowerBB->functions->CleanVariable($starteronly,'sql');
		$exactname	= 	$PowerBB->functions->CleanVariable($exactname,'sql');
		$option	= 	$PowerBB->functions->CleanVariable($option,'sql');
		$count	= 	$PowerBB->functions->CleanVariable($count,'sql');

       if ($section != 'all'
       and $section != is_numeric($section))
		{

		   $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['no_search_results']);
		 }

        if ($PowerBB->core->Is(array('where' => array('sec_section','1')),'section'))
		{
         $SectionInfo = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['section'] . " WHERE sec_section<>0");
		}
		else
		{
         $SectionInfo = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['section'] . " WHERE sec_section<>1");
		}



      	if (empty($username))
		{

			$stop = ($PowerBB->_CONF['info_row']['ajax_search'] and !$PowerBB->_POST['ajax']) ? false : true;
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_write_the_name_of_the_member'],$stop);
		}

        if ($section == 'all')
		{
              $section = "";
              $sectionall = "all";

		 }
		 else
		{
              $section = " AND section = '".$section."' ";
              $sectionall = $PowerBB->_GET['section'];
		 }

			  if ($exactname == '0')
	           {
	           	$username    =  '%' .$username .'%';
		       }

       	        $username_subject_nm = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['subject'] . " WHERE writer LIKE '$username' AND delete_topic<>'1' AND review_subject<>'1' AND sec_subject<>'1' ".$section." "));

				$PowerBB->template->assign('username_nm',$username_subject_nm);


                $MemSubjectArr 						= 	array();
				$MemSubjectArr['where'] 				= 	array();
				$MemSubjectArr['where'][0] 			= 	array();
				$MemSubjectArr['where'][0]['name'] 	=  'writer';
				$MemSubjectArr['where'][0]['oper']		=  'LIKE';
	           	$MemSubjectArr['where'][0]['value']    =  $username;

                $MemSubjectArr['where'][1] 			= 	array();
				$MemSubjectArr['where'][1]['con']		=	'AND';
				$MemSubjectArr['where'][1]['name'] 	= 	"review_subject<>1 AND sec_subject<>1 ".$section." AND delete_topic";
				$MemSubjectArr['where'][1]['oper'] 	= 	'<>';
				$MemSubjectArr['where'][1]['value'] = 	'1';

				$MemSubjectArr['order'] 			= 	array();
				$MemSubjectArr['order']['field'] 	= 	'id';
				$MemSubjectArr['order']['type'] 	= 	strtoupper($sort_order);

				$MemSubjectArr['proc'] 						= 	array();
				// Ok Mr.XSS go to hell !
				$MemSubjectArr['proc']['*'] 					= 	array('method'=>'clean','param'=>'html');
				$MemSubjectArr['proc']['native_write_time'] 	= 	array('method'=>'date','store'=>'write_date','type'=>$PowerBB->_CONF['info_row']['timesystem']);
				$MemSubjectArr['proc']['write_time'] 			= 	array('method'=>'date','store'=>'reply_date','type'=>$PowerBB->_CONF['info_row']['timesystem']);

             if ($username_subject_nm  == '0')
	          {
		       $stop = ($PowerBB->_CONF['info_row']['ajax_search'] and !$PowerBB->_POST['ajax']) ? false : true;
			   $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['no_search_results'],$stop);

	          }
	         else
	          {

	               	// Pager setup
					$MemSubjectArr['pager'] 				= 	array();
					$MemSubjectArr['pager']['total']		= 	$username_subject_nm;
					if ($username_subject_nm > $PowerBB->_CONF['info_row']['perpage'])
			        {
					$MemSubjectArr['pager']['perpage'] 	= 	$PowerBB->_CONF['info_row']['perpage'];
			        }
			       else
			        {
		            $MemSubjectArr['pager']['perpage'] 	= 	$username_subject_nm;
			        }
			        $MemSubjectArr['pager']['count'] 		= 	$count;
					$MemSubjectArr['pager']['location'] 	= 	'index.php?page=search&amp;option=3&amp;username=' . $username . '&amp;starteronly=0&amp;section=' . $sectionall . '&amp;exactname=' . $exactname . '&amp;sort_order='.strtoupper($sort_order);

					$MemSubjectArr['pager']['var'] 		= 	'count';
	           }



               	$PowerBB->_CONF['template']['while']['MembersListSubject'] = $PowerBB->subject->GetSubjectList($MemSubjectArr);
                $PowerBB->_CONF['template']['PagerLocation'] = $MemSubjectArr['pager']['location'];

                    if ($username_subject_nm > $PowerBB->_CONF['info_row']['perpage'])
			        {
					$PowerBB->template->assign('pager',$PowerBB->pager->show());
			        }
                $PowerBB->template->assign('search_username_subjects',1);
				$PowerBB->template->assign('keyword',$username);
				$PowerBB->template->display('search_results_username_subject');

    }

	 function _StartSearchUsernameReply()
	  {
	   global $PowerBB;
		$keyword 	= 	(isset($PowerBB->_GET['keyword'])) ? $PowerBB->_GET['keyword'] : $PowerBB->_POST['keyword'];
		$keyword=$PowerBB->functions->CleanVariable($keyword,'trim');

		$username 	= 	(isset($PowerBB->_GET['username'])) ? $PowerBB->_GET['username'] : $PowerBB->_POST['username'];
		$sort_order	= 	(isset($PowerBB->_GET['sort_order'])) ? $PowerBB->_GET['sort_order'] : $PowerBB->_POST['sort_order'];
        $exactname	= 	(isset($PowerBB->_GET['exactname'])) ? $PowerBB->_GET['exactname'] : $PowerBB->_POST['exactname'];
		$starteronly	= 	(isset($PowerBB->_GET['starteronly'])) ? $PowerBB->_GET['starteronly'] : $PowerBB->_POST['starteronly'];
		$section	= 	(isset($PowerBB->_GET['section'])) ? $PowerBB->_GET['section'] : $PowerBB->_POST['section'];
		$option = (isset($PowerBB->_GET['option'])) ? $PowerBB->_GET['option'] : $PowerBB->_POST['option'];
		$search_only	= 	(isset($PowerBB->_GET['search_only'])) ? $PowerBB->_GET['search_only'] : $PowerBB->_POST['search_only'];

        $count         = $PowerBB->functions->CleanVariable($PowerBB->_GET['count'],'intval');
		$search_only	= 	$PowerBB->functions->CleanVariable($search_only,'intval');
		$option	= 	$PowerBB->functions->CleanVariable($option,'intval');
		if($section != 'all')
		{
		$section	= 	$PowerBB->functions->CleanVariable($section,'intval');
		}
		$sort_order    =    $PowerBB->functions->CleanVariable($sort_order,'trim');
		$keyword	= 	$PowerBB->functions->CleanVariable($keyword,'sql');
		$section	= 	$PowerBB->functions->CleanVariable($section,'sql');
		$username	= 	$PowerBB->functions->CleanVariable($username,'sql');
		$sort_order	= 	$PowerBB->functions->CleanVariable($sort_order,'sql');
		$search_only	= 	$PowerBB->functions->CleanVariable($search_only,'sql');
		$tag	= 	$PowerBB->functions->CleanVariable($tag,'sql');
		$starteronly	= 	$PowerBB->functions->CleanVariable($starteronly,'sql');
		$exactname	= 	$PowerBB->functions->CleanVariable($exactname,'sql');
		$option	= 	$PowerBB->functions->CleanVariable($option,'sql');
		$count	= 	$PowerBB->functions->CleanVariable($count,'sql');


       if ($section != 'all'
       and $section != is_numeric($section))
		{

		   $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['no_search_results']);
		 }


        if ($PowerBB->core->Is(array('where' => array('sec_section','1')),'section'))
		{
         $SectionInfo = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['section'] . " WHERE sec_section<>0");
		}
		else
		{
         $SectionInfo = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['section'] . " WHERE sec_section<>1");
		}



        if (empty($username))
		{

			$stop = ($PowerBB->_CONF['info_row']['ajax_search'] and !$PowerBB->_POST['ajax']) ? false : true;
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_write_the_name_of_the_member'],$stop);

		}

        if ($section == 'all')
		{
              $section = "";
              $sectionall = "all";

		 }
		 else
		{
              $section = " AND section = '".$section."' ";
              $sectionall = $PowerBB->_GET['section'];
		 }


                  $forum_not = $PowerBB->_CONF['info_row']['last_subject_writer_not_in'];
			  if ($exactname == '0')
	           {
		       	$username    =  '%' .$username .'%';
		       }
	           $username_reply_nm = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['reply'] . " WHERE writer LIKE '$username' AND section not in (" .$forum_not. ") AND delete_topic<>1 AND review_reply<>1 ".$section." "));

				$PowerBB->template->assign('username_nm',$username_reply_nm);
		     	$MemReplyArr 								= 	array();
				$MemReplyArr['where'] 						= 	array();

				$MemReplyArr['where'][0] 			= 	array();
				$MemReplyArr['where'][0]['name'] 	=  'writer';
				$MemReplyArr['where'][0]['oper']		=  'LIKE';
	           	$MemReplyArr['where'][0]['value']    =  $username;

                $MemReplyArr['where'][1] 			= 	array();
				$MemReplyArr['where'][1]['con']		=	'AND';
				$MemReplyArr['where'][1]['name'] 	= 	'section not in (' .$forum_not. ') AND review_reply<>1 '.$section.' AND delete_topic';
				$MemReplyArr['where'][1]['oper'] 	= 	'<>';
				$MemReplyArr['where'][1]['value'] 	= 	'1';

				$MemReplyArr['order'] 			= 	array();
				$MemReplyArr['order']['field'] 	= 	'id';
				$MemReplyArr['order']['type'] 	= 	strtoupper($sort_order);

				$MemReplyArr['proc'] 						= 	array();
				// Ok Mr.XSS go to hell !
				$MemReplyArr['proc']['*'] 					= 	array('method'=>'clean','param'=>'html');
				$MemReplyArr['proc']['native_write_time'] 	= 	array('method'=>'date','store'=>'write_date','type'=>$PowerBB->_CONF['info_row']['timesystem']);
				$MemReplyArr['proc']['write_time'] 			= 	array('method'=>'date','store'=>'reply_date','type'=>$PowerBB->_CONF['info_row']['timesystem']);

               if ($username_reply_nm  == '0')
	          {
		       $stop = ($PowerBB->_CONF['info_row']['ajax_search'] and !$PowerBB->_POST['ajax']) ? false : true;
			   $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['no_search_results'],$stop);

	          }
	         else
	          {
                  $PowerBB->_CONF['info_row']['perpage'] = '32';
	               	// Pager setup
					$MemReplyArr['pager'] 				= 	array();
					$MemReplyArr['pager']['total']		= 	$username_reply_nm;
					if ($username_reply_nm > $PowerBB->_CONF['info_row']['perpage'])
			        {
					$MemReplyArr['pager']['perpage'] 	= 	$PowerBB->_CONF['info_row']['perpage'];
			        }
			       else
			        {
		            $MemReplyArr['pager']['perpage'] 	= 	$username_reply_nm;
			        }
			        $MemReplyArr['pager']['count'] 		= 	$count;
					$MemReplyArr['pager']['location'] 	= 	'index.php?page=search&amp;option=4&amp;username=' . $username . '&amp;starteronly=0&amp;section=' . $sectionall . '&amp;exactname=' . $exactname . '&amp;sort_order='.strtoupper($sort_order);

					$MemReplyArr['pager']['var'] 		= 	'count';
	           }

			$PowerBB->_CONF['template']['while']['MembersListReply'] = $PowerBB->reply->GetReplyList($MemReplyArr);
            $PowerBB->_CONF['template']['PagerLocation'] = $MemReplyArr['pager']['location'];


		            if ($username_reply_nm > $PowerBB->_CONF['info_row']['perpage'])
			        {
					$PowerBB->template->assign('pager',$PowerBB->pager->show());
			        }

				$PowerBB->template->assign('keyword',$username);
				$PowerBB->template->display('search_results_username_reply');

	   }

	function _StartSearchTag()
	{
		global $PowerBB;
		$tag = (isset($PowerBB->_GET['tag'])) ? $PowerBB->_GET['tag'] : $PowerBB->_POST['tag'];
		$sort_order	= 	(isset($PowerBB->_GET['sort_order'])) ? $PowerBB->_GET['sort_order'] : $PowerBB->_POST['sort_order'];
		$count	= 	(isset($PowerBB->_GET['count'])) ? $PowerBB->_GET['count'] : $PowerBB->_POST['count'];

		$keyword	= 	$PowerBB->functions->CleanVariable($keyword,'sql');
		$section	= 	$PowerBB->functions->CleanVariable($section,'sql');
		$username	= 	$PowerBB->functions->CleanVariable($username,'sql');
		$sort_order	= 	$PowerBB->functions->CleanVariable($sort_order,'sql');
		$search_only	= 	$PowerBB->functions->CleanVariable($search_only,'sql');
		$tag	= 	$PowerBB->functions->CleanVariable($tag,'sql');
		$starteronly	= 	$PowerBB->functions->CleanVariable($starteronly,'sql');
		$exactname	= 	$PowerBB->functions->CleanVariable($exactname,'sql');
		$option	= 	$PowerBB->functions->CleanVariable($option,'sql');

	    $count = $PowerBB->functions->CleanVariable($count,'intval');
		$search_only	= 	$PowerBB->functions->CleanVariable($search_only,'intval');
		$option	= 	$PowerBB->functions->CleanVariable($option,'intval');

		$sort_order    =    $PowerBB->functions->CleanVariable($sort_order,'trim');

       if ($section != 'all'
       and $section != is_numeric($section))
		{
		   $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['no_search_results']);
		 }

       	if ($sort_order != 'DESC'
       	and $sort_order != 'ASC')
		{
		   $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['no_search_results']);
		}


		// Clean the id from any strings
		 if (empty($tag))
		{
			$stop = ($PowerBB->_CONF['info_row']['ajax_search'] and !$PowerBB->_POST['ajax']) ? false : true;
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true'],$stop);

		}
        $limt_perpage = '32';
        $TotalArr 			= 	array();
		$TotalArr['where'] 	= 	array('tag',$tag);
        $tag_nm = $PowerBB->tag->GetSubjectNumber($TotalArr);

        $TagArr 						= 	array();
		$TagArr['where'] 				= 	array();
		$TagArr['where'][0] 			= 	array();
		$TagArr['where'][0]['name'] 	=  'tag';
		$TagArr['where'][0]['oper']		=  'LIKE';
        $TagArr['where'][0]['value']    =  $tag;

		// Pager setup
		$TagArr['pager'] 				= 	array();
		$TagArr['pager']['total']		= 	$tag_nm;
		if ($tag_nm > $limt_perpage)
        {
		$TagArr['pager']['perpage'] 	= 	$limt_perpage; // TODO
        }
       else
        {
         $TagArr['pager']['perpage'] 	= 	$tag_nm;
        }
		$TagArr['pager']['count'] 		= 	$count;
		$TagArr['pager']['location'] 	= 	'index.php?page=search&amp;option=5&amp;tag=' . $tag . '&amp;sort_order='.$sort_order;
		$TagArr['pager']['var'] 		= 	'count';

		$TagArr['order'] 			= 	array();
		$TagArr['order']['field'] 	= 	'id';
		$TagArr['order']['type'] 	= 	$sort_order;


		$PowerBB->_CONF['template']['while']['Subject'] = $PowerBB->tag->GetSubjectList($TagArr);
        $PowerBB->_CONF['template']['PagerLocation'] = $TagArr['pager']['location'];
		if (!$PowerBB->_CONF['template']['while']['Subject'])
		{
			$stop = ($PowerBB->_CONF['info_row']['ajax_search'] and !$PowerBB->_POST['ajax']) ? false : true;
			$PowerBB->functions->error($tag." ".$PowerBB->_CONF['template']['_CONF']['lang']['taag_requested_does_not_exist'],$stop);
		}

		$PowerBB->template->assign('tags',$tag);
		if ($tag_nm > $limt_perpage)
		{
		$PowerBB->template->assign('pager',$PowerBB->pager->show());
        }
        $PowerBB->template->assign('tags_nm',$tag_nm);
		$PowerBB->template->display('search_results_tags');
	}

 function _StartSearchReview_reply()
	  {
	   global $PowerBB;

		$section	= 	(isset($PowerBB->_GET['section'])) ? $PowerBB->_GET['section'] : $PowerBB->_POST['section'];
		$section	= 	$PowerBB->functions->CleanVariable($section,'intval');
        $count         = $PowerBB->functions->CleanVariable($PowerBB->_GET['count'],'intval');

		if ($PowerBB->functions->ModeratorCheck($section))
		{

		$SecArr 			= 	array();
		$SecArr['where'] 	= 	array('id',$section);

		$this->SectionInfo = $PowerBB->core->GetInfo($SecArr,'section');

				$option	= 	$PowerBB->functions->CleanVariable($option,'intval');

				$sort_order    =    $PowerBB->functions->CleanVariable($sort_order,'trim');
				$section	= 	$PowerBB->functions->CleanVariable($section,'sql');
				$count	= 	$PowerBB->functions->CleanVariable($count,'sql');

                 $review_reply_nm = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT id FROM " . $PowerBB->table['reply'] . " WHERE section = '$section' and review_reply = '1' "));

				if ($review_reply_nm  == '0')
				{
				$stop = ($PowerBB->_CONF['info_row']['ajax_search'] and !$PowerBB->_POST['ajax']) ? false : true;
				$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['forum'].': '.$this->SectionInfo['title'].'<br />'.$review_reply_nm .' ' .$PowerBB->_CONF['template']['_CONF']['lang']['replys_review_num'],$stop);

				}

				$PowerBB->template->assign('username_nm',$username_reply_nm);

			    $MemReplyArr                        =   array();
				$MemReplyArr['where'] 				= 	array();
				$MemReplyArr['where'][0] 			= 	array();
				$MemReplyArr['where'][0]['name'] 	= 	'section';
				$MemReplyArr['where'][0]['oper'] 	= 	'=';
				$MemReplyArr['where'][0]['value'] 	= 	$section;

				$MemReplyArr['where'][1] 			= 	array();
				$MemReplyArr['where'][1]['con']		=	'AND';
				$MemReplyArr['where'][1]['name'] 	= 	'review_reply';
				$MemReplyArr['where'][1]['oper'] 	= 	'=';
				$MemReplyArr['where'][1]['value'] 	= 	'1';

				$MemReplyArr['order'] 			= 	array();
				$MemReplyArr['order']['field'] 	= 	'id';
				$MemReplyArr['order']['type'] 	= 	strtoupper($sort_order);

				$MemReplyArr['proc'] 						= 	array();
				// Ok Mr.XSS go to hell !
				$MemReplyArr['proc']['*'] 					= 	array('method'=>'clean','param'=>'html');
				$MemReplyArr['proc']['write_time'] 			= 	array('method'=>'date','store'=>'reply_date','type'=>$PowerBB->_CONF['info_row']['timesystem']);

				// Pager setup
				$MemReplyArr['pager'] 				= 	array();
				$MemReplyArr['pager']['total']		= 	$review_reply_nm;
				if ($review_reply_nm > $PowerBB->_CONF['info_row']['perpage'])
				{
				$MemReplyArr['pager']['perpage'] 	= 	$PowerBB->_CONF['info_row']['perpage'];
				}
				else
				{
				$MemReplyArr['pager']['perpage'] 	= 	$review_reply_nm;
				}
				$MemReplyArr['pager']['count'] 		= 	$count;
				$MemReplyArr['pager']['location'] 	= 	'index.php?page=search&amp;option=6&amp;review_reply=1&amp;section=' . $section . '&amp;sort_order='.strtoupper($sort_order);

				$MemReplyArr['pager']['var'] 		= 	'count';



				$PowerBB->_CONF['template']['while']['MembersListReply'] = $PowerBB->reply->GetReplyList($MemReplyArr);
				$PowerBB->_CONF['template']['PagerLocation'] = $MemReplyArr['pager']['location'];


				if ($review_reply_nm > $PowerBB->_CONF['info_row']['perpage'])
				{
				  $PowerBB->template->assign('pager',$PowerBB->pager->show());
				}
				  $PowerBB->template->assign('review_reply','1');
				  $PowerBB->template->assign('section',$section);

				$PowerBB->template->assign('keyword',$review_reply_nm.' '.$PowerBB->_CONF['template']['_CONF']['lang']['replys_review_num'].' - '.$PowerBB->_CONF['template']['_CONF']['lang']['forum'].': '.$this->SectionInfo['title']);
				$PowerBB->_CONF['template']['_CONF']['lang']['Search_results_for_all_reply_user'] = '';

				$PowerBB->template->display('search_results_username_reply');
		 }
		 else
		 {
		 $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['not_allowed_access']);
		 }

	   }

 	    /**
	 * Get the Jump Sections List
	 */
	function _GetJumpSectionsList()
    {
		global $PowerBB;
       $PowerBB->functions->JumpForumsList();
   }
}

?>