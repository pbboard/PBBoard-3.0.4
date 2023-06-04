<?php

(!defined('IN_PowerBB')) ? die() : '';

define('IN_ADMIN',true);

define('CLASS_NAME','PowerBBFixMOD');

include('../common.php');
class PowerBBFixMOD
{
	function run()
	{
		global $PowerBB;

		if ($PowerBB->_CONF['member_permission'])
		{            if (!$PowerBB->_GET['pbboard_updates']){
			$PowerBB->template->display('header');
             }
			if ($PowerBB->_CONF['rows']['group_info']['admincp_fixup'] == '0')
			{
			  $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
			}

			if ($PowerBB->_GET['repair'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_RepairMain();
				}
			}
			elseif ($PowerBB->_GET['update_meter'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_MeterMain();
				}
				elseif ($PowerBB->_GET['start'])
				{
					$this->_MeterStart();
				}
				elseif ($PowerBB->_GET['all_cache'])
				{
					$this->_AllCacheStart();
				}
				elseif ($PowerBB->_GET['groups'])
				{
					$this->_MeterGroupsStart();
				}

			}
			elseif ($PowerBB->_GET['repair_mem_posts'])
			{
				$this->_RepairMemberPostsStart();
			}
			elseif ($PowerBB->_GET['update_posts'])
			{
				$this->_UpdatePostsStart();
			}
			elseif ($PowerBB->_GET['update_username_members'])
			{
				$this->_UpdatUsernameMembersStart();
			}
			elseif ($PowerBB->_GET['update_users_ratings'])
			{
				$this->_UpdatUsersRatingsStart();
			}
			elseif ($PowerBB->_GET['update_users_titles'])
			{
				$this->_UpdatUsersTitlesStart();
			}
			elseif ($PowerBB->_GET['update_static'])
			{
				$this->_UpdatStaticStart();
			}
			elseif ($PowerBB->_GET['info'])
			{
				$this->_php_infoStart();
			}
			elseif ($PowerBB->_GET['pbboard_updates']
			and $PowerBB->_GET['start'])
			{
				$this->_pbboard_updates_start();
			}
           if (!$PowerBB->_GET['pbboard_updates']){
			$PowerBB->template->display('footer');
			}
		}
	}



	function _MeterMain()
	{
		global $PowerBB;

		// Show Jump List to:)
		$Master = array();
		$Master = $PowerBB->section->GetSectionsList(array ('id'=>$id,'title'=>"".$title."",'parent'=>$parent));
		$MainAndSub = new PowerBBCommon;
        $PowerBB->template->assign('DoJumpList',$MainAndSub->DoJumpList($Master,false,1));
		unset($Master);
	   ////////

		$PowerBB->template->display('meter_edit');
	}

	function _MeterStart()
	{
		global $PowerBB;

		$SubjectArr = array();
		$SubjectArr['where'] = array('section',$PowerBB->_POST['section']);

		$subject = $PowerBB->core->GetInfo($SubjectArr,'subject');

		$SecArr 			= 	array();
		$SecArr['where'] 	= 	array('id',$PowerBB->_POST['section']);

		$SectionInfo = $PowerBB->core->GetInfo($SecArr,'section');

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
			$SecArr['where'][0]['value']	= 	$PowerBB->_POST['section'];

			$cats = $PowerBB->core->GetList($SecArr,'section');
			foreach ($cats as $cat)
			{
	           $UpdateSectionCache = $PowerBB->functions->UpdateSectionCache($cat['id']);

					$forumArr 						= 	array();
					$forumArr['get_from']				=	'db';

					$forumArr['proc'] 				= 	array();
					$forumArr['proc']['*'] 			= 	array('method'=>'clean','param'=>'html');

					$forumArr['order']				=	array();
					$forumArr['order']['field']		=	'sort';
					$forumArr['order']['type']		=	'ASC';

					$forumArr['where']				=	array();
					$forumArr['where'][0]['name']		= 	'parent';
					$forumArr['where'][0]['oper']		= 	'=';
					$forumArr['where'][0]['value']	= 	$cat['id'];

					// Get main sections
					$forums = $PowerBB->core->GetList($forumArr,'section');

					foreach ($forums as $forum)
					{

	                  $UpdateSectionCache = $PowerBB->functions->UpdateSectionCache($forum['id']);

						$subArr 						= 	array();
						$subArr['get_from']				=	'db';

						$subArr['proc'] 				= 	array();
						$subArr['proc']['*'] 			= 	array('method'=>'clean','param'=>'html');

						$subArr['order']				=	array();
						$subArr['order']['field']		=	'sort';
						$subArr['order']['type']		=	'ASC';

						$subArr['where']				=	array();
						$subArr['where'][0]['name']		= 	'parent';
						$subArr['where'][0]['oper']		= 	'=';
						$subArr['where'][0]['value']	= 	$forum['id'];

						// Get main forums
						$subs = $PowerBB->core->GetList($subArr,'section');

					  foreach ($subs as $sub)
					  {
	                  $UpdateSectionCache = $PowerBB->functions->UpdateSectionCache($sub['id']);

							$subsubArr 						= 	array();
							$subsubArr['get_from']				=	'db';

							$subsubArr['proc'] 				= 	array();
							$subsubArr['proc']['*'] 			= 	array('method'=>'clean','param'=>'html');

							$subsubArr['order']				=	array();
							$subsubArr['order']['field']		=	'sort';
							$subsubArr['order']['type']		=	'ASC';

							$subsubArr['where']				=	array();
							$subsubArr['where'][0]['name']		= 	'parent';
							$subsubArr['where'][0]['oper']		= 	'=';
							$subsubArr['where'][0]['value']	= 	$sub['id'];

							// Get main sub sections
							$subsubs = $PowerBB->core->GetList($subsubArr,'section');
                        foreach ($subsubs as $subsub)
                        {
	                      $UpdateSectionCache = $PowerBB->functions->UpdateSectionCache($subsub['id']);

	                     }

					  }



					}
			}

        $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['updated']  .  $SectionInfo['title']  .  $PowerBB->_CONF['template']['_CONF']['lang']['Successfully']);
		$PowerBB->functions->redirect('index.php?page=fixup&amp;update_meter=1&amp;main=1');

	}

	function _AllCacheStart()
	{
		global $PowerBB;

		if(!isset($PowerBB->_GET['pag']))
		{		$page = 1;
		}
		else
		{
		$page = $PowerBB->_GET['pag'];
		}
		$page = ($page == 0 ? 1 : $page);
		$perpage = 4;
		$startpoint = ($page * $perpage) - $perpage;
 		$forumArr = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['section'] . " WHERE parent > '0' ORDER BY id ASC LIMIT ".$startpoint.",".$perpage." ");
		$forum_nm = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(1),id FROM " . $PowerBB->table['section'] . " WHERE parent > 0"));

        $pagesnum = round(ceil($forum_nm / $perpage));
        echo('<br><br><table border="1" width="80%" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" style="border-collapse: collapse" align="center"><tr><td><font face="Tahoma" size="2">');

		while ($forum_row = $PowerBB->DB->sql_fetch_array($forumArr))
		{

		 $UpdateSectionCache = $PowerBB->functions->UpdateSectionCache($forum_row['id']);
          if($forum_row['id'])
          {
           $Updated = '1';
          }
		 if ($Updated)
		  {
		  echo($PowerBB->_CONF['template']['_CONF']['lang']['updated']  .  $forum_row['title']  .  $PowerBB->_CONF['template']['_CONF']['lang']['Successfully'] .' .. <br />');
		  }

		}
		echo('</font></td></tr></table>');
		$current_page = $page;

		if ($Updated)
		{
			if($pagesnum != $current_page or $pagesnum > $current_page)
			{
			$n_page = $current_page+1;
			$seconds= 1;
			$n_page = intval($n_page);
			echo('<br><table border="1" width="80%" cellspacing="0" cellpadding="0" bgcolor="#E5EBF0" style="border-collapse: collapse" align="center"><tr><td><font face="Tahoma" size="2">');
			$transition_click = $PowerBB->_CONF['template']['_CONF']['lang']['If_your_browser_does_not_support_automatic_transition_click_here'];
			echo('<a href="index.php?page=fixup&amp;update_meter=1&amp;all_cache=1&amp;pag='.$n_page.'">'.$transition_click.'</a>');
			echo($PowerBB->_CONF['template']['_CONF']['lang']['Waiting_Time'].$seconds.$PowerBB->_CONF['template']['_CONF']['lang']['seconds']);
			echo('</font></td></tr></table>');

			$PowerBB->functions->redirect('index.php?page=fixup&update_meter=1&all_cache=1&pag='.$n_page,$seconds);
			}
			else
			{
			$PowerBB->functions->Update_Cache_groups();
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['updated_successfully']);
			$PowerBB->functions->redirect('index.php?page=fixup&amp;update_meter=1&amp;main=1');
			}

		}
		else
		{
		echo('<br><table border="1" width="80%" cellspacing="0" cellpadding="0" bgcolor="#E5EBF0" style="border-collapse: collapse" align="center"><tr><td><font face="Tahoma" size="2">');
		echo($PowerBB->_CONF['template']['_CONF']['lang']['not_the_update']);
		echo('</font></td></tr></table>');
		}



	}

	function _MeterGroupsStart()
	  {
		global $PowerBB;

        $query = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['section'] . " ");

        while ($r = $PowerBB->DB->sql_fetch_array($query))
	    {
	     $query_section_group = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['section_group'] . " WHERE section_id=".$r['id']."");
	     $Info_section_group = $PowerBB->DB->sql_fetch_array($query_section_group);
	     if($Info_section_group['view_section'] == false)
	     {                 $truncate_sectiongroup = $PowerBB->DB->sql_query("TRUNCATE " . $PowerBB->table['section_group'] );

		        $query = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['section'] . " ");

		        while ($r = $PowerBB->DB->sql_fetch_array($query))
			    {

				    $forumid 	    = 	$r['id'];
			        $parentid 	    = 	$r['parent'];
			        $sec_section  	= 	$r['sec_section'];
			        $hide_subject  	= 	$r['hide_subject'];
			        $review_subject  	= 	$r['review_subject'];

		       		$info_query_groups = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['group'] . " ");

					while ($groups = $PowerBB->DB->sql_fetch_array($info_query_groups))
					{
						if ($parentid == '0')
						{
							$main_section = '1';
						}
						else
						{
							$main_section = '0';
						}

						if ($groups['id'] == 7)
						{
							$write = '0';
						}
						elseif ($groups['id'] == 6)
						{
							$write = '0';
						}
						elseif ($groups['id'] == 5)
						{
							$write = '0';
						}
						else
						{
							$write = '1';
						}


		                 if($hide_subject and $sec_section == 0)
		                 {
			                $groups['view_section'] = 1;
			                $groups['view_subject'] = 1;
			              }

						$SecArr 				= 	array();
						$SecArr['field']		=	array();

						$SecArr['field']['section_id'] 			= 	$forumid;
						$SecArr['field']['group_id'] 			= 	$groups['id'];

						$SecArr['field']['view_section'] 		= 	$groups['view_section'];
				        $SecArr['field']['view_subject'] 		= 	$groups['view_subject'];
						$SecArr['field']['download_attach'] 	= 	$groups['download_attach'];
						$SecArr['field']['write_subject'] 		= 	$groups['write_subject'];
						$SecArr['field']['write_reply'] 		= 	$groups['write_reply'];
						$SecArr['field']['upload_attach'] 		= 	$groups['upload_attach'];
						$SecArr['field']['edit_own_subject'] 	= 	$groups['edit_own_subject'];
						$SecArr['field']['edit_own_reply'] 		= 	$groups['edit_own_reply'];
						$SecArr['field']['del_own_subject'] 	= 	$groups['del_own_subject'];
						$SecArr['field']['del_own_reply'] 		= 	$groups['del_own_reply'];
						$SecArr['field']['write_poll'] 			= 	$groups['write_poll'];
						$SecArr['field']['vote_poll'] 			= 	$groups['vote_poll'];
						$SecArr['field']['no_posts'] 			= 	$groups['no_posts'];
						$SecArr['field']['main_section'] 		= 	$main_section;
						$SecArr['field']['group_name'] 			= 	$groups['title'];

						$insert = $PowerBB->core->Insert($SecArr,'sectiongroup');
						if ($insert)
						{

							$CacheArr 			= 	array();
							$CacheArr['id'] 	= 	$forumid;

							$cache = $PowerBB->group->UpdateSectionGroupCache($CacheArr);

						}
				     }
				}

	     }
	     else
	     {
			$truncate = 0;

			$CacheArr 			= 	array();
			$CacheArr['id'] 	= 	$r['id'];
			$cache = $PowerBB->group->UpdateSectionGroupCache($CacheArr);

	     }

	    }

		$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['updated_successfully_Please_wait']);
		$PowerBB->functions->redirect('index.php?page=fixup&amp;update_meter=1&amp;main=1');
       }

   function _php_infoStart()
	{
    global $PowerBB;
      phpinfo();

	}

   function _RepairMemberPostsStart()
	{
    global $PowerBB;

		if (!empty($PowerBB->_POST['perpage']))
		{
    	$perpage = $PowerBB->_POST['perpage'];
		}
		if (empty($PowerBB->_POST['perpage']))
		{
    	$perpage = '200';
		}
		$page = (int) (!isset($PowerBB->_GET['pag']) ? 1 : $PowerBB->_GET['pag']);
		$page = ($page == 0 ? 1 : $page);

		$startpoint = ($page * $perpage) - $perpage;
		$member_nm = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(1),id FROM " . $PowerBB->table['member'] . " WHERE id"));
              $br = '<br>';
		echo('<br><br><table border="1" width="80%" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" style="border-collapse: collapse" align="center"><tr><td><font face="Tahoma" size="2">');

		$getmember_query = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['member'] . " ORDER BY id DESC LIMIT ".$startpoint.",".$perpage." ");
	    while ($MemInfo = $PowerBB->DB->sql_fetch_array($getmember_query))
        {
        	$MemUsername = $MemInfo['username'];
		    $member_nm_reply = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(1),id FROM " . $PowerBB->table['reply'] . " WHERE writer = '$MemUsername' LIMIT 1"));
		    $member_nm_subject = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(1),id FROM " . $PowerBB->table['subject'] . " WHERE writer = '$MemUsername'"));

		  // change the cache of username style

			$GrpArr 			= 	array();
			$GrpArr['where'] 	= 	array('id',$MemInfo['usergroup']);

			$GroupInfo = $PowerBB->core->GetInfo($GrpArr,'group');

			$style = $GroupInfo['username_style'];
			$style = str_replace('[username]',$MemInfo['username'],$style);

		$UpdateArr 				= 	array();
		$UpdateArr['field'] 	= 	array();
		$UpdateArr['field']['posts'] 				= 	$member_nm_reply+$member_nm_subject;
		$UpdateArr['field']['username_style_cache']	=	$style;
		$UpdateArr['where']					    	 =	array('id',$MemInfo['id']);

		$update = $PowerBB->core->Update($UpdateArr,'member');
		// UPDATE username_style today
		$update_username_style_today = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['today'] . " SET username_style='" . $style . "' WHERE user_id='" . $MemInfo['id'] . "'");
		// UPDATE username_style online
		$update_username_style_online = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['online'] . " SET username_style='" . $style . "' WHERE user_id='" . $MemInfo['id'] . "'");

		echo($MemInfo['username'] .' ..'. $br);

	   }
      		echo('</font></td></tr></table>');

		$current_page = $page;
       $pagesnum = round(ceil($member_nm / $perpage));

		if ($update)
		{
			if($pagesnum != $current_page or $pagesnum > $current_page)
			{
			$n_page = $current_page+1;
			$seconds= '5';
			$n_page = intval($n_page);
			echo('<br><table border="1" width="80%" cellspacing="0" cellpadding="0" bgcolor="#E5EBF0" style="border-collapse: collapse" align="center"><tr><td><font face="Tahoma" size="2">');
			$transition_click = $PowerBB->_CONF['template']['_CONF']['lang']['If_your_browser_does_not_support_automatic_transition_click_here'];
			echo($PowerBB->_CONF['template']['_CONF']['lang']['Waiting_Time'].$seconds.$PowerBB->_CONF['template']['_CONF']['lang']['seconds']);
			echo('</font></td></tr></table>');

			$PowerBB->functions->redirect('index.php?page=fixup&amp;repair_mem_posts=1&amp;pag='.$n_page,$seconds);
			}
			else
			{

			$PowerBB->functions->redirect('index.php?page=fixup&amp;update_meter=1&amp;main=1');

			}

		}
		else
		{
		echo('<br><table border="1" width="80%" cellspacing="0" cellpadding="0" bgcolor="#E5EBF0" style="border-collapse: collapse" align="center"><tr><td><font face="Tahoma" size="2">');
		echo($PowerBB->_CONF['template']['_CONF']['lang']['forum_does_not_contain_any_posts']);
		echo('</font></td></tr></table>');
		}

	}


	function _UpdatStaticStart()
	{
		global $PowerBB;


	         $reply_number = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query('SELECT COUNT(1),id FROM '.$PowerBB->table['reply'].' WHERE delete_topic <> 1 LIMIT 1'));
	         $subject_number = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query('SELECT COUNT(1),id FROM '.$PowerBB->table['subject'].' WHERE delete_topic <> 1 LIMIT 1'));
	         $member_number = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query('SELECT COUNT(1),id FROM '.$PowerBB->table['member'].' LIMIT 1'));

	        $update = array();
			$update[0] = $PowerBB->info->UpdateInfo(array('value'=>$member_number,'var_name'=>'member_number'));
			$update[1] = $PowerBB->info->UpdateInfo(array('value'=>$reply_number,'var_name'=>'reply_number'));
			$update[2] = $PowerBB->info->UpdateInfo(array('value'=>$subject_number,'var_name'=>'subject_number'));

			if ($update[0] and $update[1] and $update[2])
			{
				$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['updated_successfully_Please_wait']);
				$PowerBB->functions->redirect('index.php?page=fixup&amp;update_meter=1&amp;main=1');
			}


    }

	function _UpdatePostsStart()
	{
		global $PowerBB;

    	$perpage = '50';

		$page = (int) (!isset($PowerBB->_GET['pag']) ? 1 : $PowerBB->_GET['pag']);
		$page = ($page == 0 ? 1 : $page);

		$startpoint = ($page * $perpage) - $perpage;
        $br = '<br>';
		echo('<br><br><table border="1" width="80%" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" style="border-collapse: collapse" align="center"><tr><td><font face="Tahoma" size="2">');
       	 $reply_num = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(1),id FROM " . $PowerBB->table['reply'] . " LIMIT 1"));

		$ReplyArr = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['reply'] . " ORDER BY id DESC LIMIT ".$startpoint.",".$perpage." ");

		while ($RepList = $PowerBB->DB->sql_fetch_array($ReplyArr))
		{

				$SubjectArr = array();
				$SubjectArr['where'] = array('id',$RepList['subject_id']);

                $SubjectInfo = $PowerBB->core->GetInfo($SubjectArr,'subject');
               if($SubjectInfo['id'])
               {

				$UpdateRepArr 						= 	array();
				$UpdateRepArr['field']		 		= 	array();
				$UpdateRepArr['field']['section'] 	= 	$SubjectInfo['section'];

				$UpdateRepArr['where'] 			=	array('id',$RepList['id']);

				$update = $PowerBB->reply->UpdateReply($UpdateRepArr);

				if ($update)
				{
					$cache = $PowerBB->section->UpdateSectionsCache(array('parent'=>$SubjectInfo['section']));
					//////////
                     $reply_nm = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(1),id FROM " . $PowerBB->table['reply'] . " WHERE section = ".$SubjectInfo['section']." LIMIT 1"));
                     $review_reply_nm = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(1),review_reply FROM " . $PowerBB->table['reply'] . " WHERE subject_id = ".$RepList['subject_id']." and review_reply = '1' LIMIT 1"));

					$SubArr 				= 	array();
					$SubArr['field']		=	array();
					$SubArr['field']['reply_num'] 	= 	$reply_nm;
					$SubArr['where'] 			=	array('id',$RepList['section']);
					$update = $PowerBB->section->UpdateSection($SubArr);
                    if ($RepList['review_reply'])
                    {
					$UpdateArr 				= 	array();
					$UpdateArr['field']		=	array();
					if ($SubjectInfo['write_time'] == $RepList['write_time'])
					{
					$UpdateArr['field']['last_replier'] 		= 	$RepList['writer'];
					}
					$UpdateArr['field']['review_reply'] 		= 	$review_reply_nm;
					$UpdateArr['where'] 						= 	array('id',$RepList['subject_id']);

					$update = $PowerBB->core->Update($UpdateArr,'subject');
                   }
                 $UpdateSectionCache6 = $PowerBB->functions->UpdateSectionCache($SubjectInfo['section']);
				}
              }
              else
              {              		$DelReplys1Arr 						= 	array();
					$DelReplys1Arr['where']				=	array();
					$DelReplys1Arr['where'][0]				=	array();
					$DelReplys1Arr['where'][0]['name']		=	'subject_id';
					$DelReplys1Arr['where'][0]['oper']		=	'=';
					$DelReplys1Arr['where'][0]['value']	=	$SubjectInfo['id'];

					$DelReplys = $PowerBB->reply->DeleteReply($DelReplys1Arr);
              }

				$s[$RepList['id']] = ($update) ? 'true' : 'false';

			echo($RepList['id'] .' ..'. $br);

		}

            echo('</font></td></tr></table>');

		$current_page = $page;
       $pagesnum = round(ceil($reply_num / $perpage));
		if ($update)
		{
			if($pagesnum != $current_page or $pagesnum > $current_page)
			{
			$n_page = $current_page+1;
			$seconds= '5';
			$n_page = intval($n_page);
			echo('<br><table border="1" width="80%" cellspacing="0" cellpadding="0" bgcolor="#E5EBF0" style="border-collapse: collapse" align="center"><tr><td><font face="Tahoma" size="2">');
			$transition_click = $PowerBB->_CONF['template']['_CONF']['lang']['If_your_browser_does_not_support_automatic_transition_click_here'];
			echo($PowerBB->_CONF['template']['_CONF']['lang']['Waiting_Time'].$seconds.$PowerBB->_CONF['template']['_CONF']['lang']['seconds']);
			echo('</font></td></tr></table>');

			$PowerBB->functions->redirect('index.php?page=fixup&amp;update_posts=1&amp;pag='.$n_page,$seconds);
			}
			else
			{

			$PowerBB->functions->redirect('index.php?page=fixup&amp;update_meter=1&amp;main=1');

			}

		}
		else
		{
		echo('<br><table border="1" width="80%" cellspacing="0" cellpadding="0" bgcolor="#E5EBF0" style="border-collapse: collapse" align="center"><tr><td><font face="Tahoma" size="2">');
		echo($PowerBB->_CONF['template']['_CONF']['lang']['forum_does_not_contain_any_posts']);
		echo('</font></td></tr></table>');
		}
    }



	function _UpdatUsernameMembersStart()
	{
		global $PowerBB;

		if (!empty($PowerBB->_POST['perpage']))
		{
    	$perpage = $PowerBB->_POST['perpage'];
		}
		if (empty($PowerBB->_POST['perpage']))
		{
    	$perpage = '200';
		}
		$page = (int) (!isset($PowerBB->_GET['pag']) ? 1 : $PowerBB->_GET['pag']);
		$page = ($page == 0 ? 1 : $page);

		$startpoint = ($page * $perpage) - $perpage;
		$member_nm = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(1),id FROM " . $PowerBB->table['member'] . " WHERE id"));
              $br = '<br>';
		echo('<br><br><table border="1" width="80%" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" style="border-collapse: collapse" align="center"><tr><td><font face="Tahoma" size="2">');

		$getmember_query = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['member'] . " ORDER BY id DESC LIMIT ".$startpoint.",".$perpage." ");
	    while ($MemInfo = $PowerBB->DB->sql_fetch_array($getmember_query))
        {
        	$MemUsername = $MemInfo['username'];
		  // change the cache of username style
			$GrpArr 			= 	array();
			$GrpArr['where'] 	= 	array('id',$MemInfo['usergroup']);

			$GroupInfo = $PowerBB->core->GetInfo($GrpArr,'group');

			$style = $GroupInfo['username_style'];
			$style = str_replace('[username]',$MemInfo['username'],$style);

		$UpdateArr 				= 	array();
		$UpdateArr['field'] 	= 	array();
		$UpdateArr['field']['username_style_cache']	=	$style;
		$UpdateArr['where']					    	 =	array('id',$MemInfo['id']);

		$update = $PowerBB->core->Update($UpdateArr,'member');

		// UPDATE username_style today
		$update_username_style_today = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['today'] . " SET username_style='" . $style . "' WHERE user_id='" . $MemInfo['id'] . "'");
		// UPDATE username_style online
		$update_username_style_online = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['online'] . " SET username_style='" . $style . "' WHERE user_id='" . $MemInfo['id'] . "'");


		echo($MemInfo['username'] .' ..'. $br);

	   }
      		echo('</font></td></tr></table>');

		$current_page = $page;
       $pagesnum = round(ceil($member_nm / $perpage));

		if ($update)
		{
			if($pagesnum != $current_page or $pagesnum > $current_page)
			{
			$n_page = $current_page+1;
			$seconds= '5';
			$n_page = intval($n_page);
			echo('<br><table border="1" width="80%" cellspacing="0" cellpadding="0" bgcolor="#E5EBF0" style="border-collapse: collapse" align="center"><tr><td><font face="Tahoma" size="2">');
			$transition_click = $PowerBB->_CONF['template']['_CONF']['lang']['If_your_browser_does_not_support_automatic_transition_click_here'];
			echo($PowerBB->_CONF['template']['_CONF']['lang']['Waiting_Time'].$seconds.$PowerBB->_CONF['template']['_CONF']['lang']['seconds']);
			echo('</font></td></tr></table>');

			$PowerBB->functions->redirect('index.php?page=fixup&amp;update_username_members=1&amp;pag='.$n_page,$seconds);
			}
			else
			{

			$PowerBB->functions->redirect('index.php?page=fixup&amp;update_meter=1&amp;main=1');

			}

		}
		else
		{
		echo('<br><table border="1" width="80%" cellspacing="0" cellpadding="0" bgcolor="#E5EBF0" style="border-collapse: collapse" align="center"><tr><td><font face="Tahoma" size="2">');
		echo($PowerBB->_CONF['template']['_CONF']['lang']['forum_does_not_contain_any_members']);
		echo('</font></td></tr></table>');
		}

    }

	function _UpdatUsersRatingsStart()
	{
		global $PowerBB;

		//////////

        $cache = $PowerBB->userrating->UpdateRatingsCache(null);
		//////////

		$PowerBB->functions->redirect('index.php?page=fixup&amp;update_meter=1&amp;main=1');

	}

	function _UpdatUsersTitlesStart()
	{
		global $PowerBB;

        //////////

       $cache = $PowerBB->usertitle->UpdateTitlesCache(null);
       //////////

      $PowerBB->functions->redirect('index.php?page=fixup&amp;update_meter=1&amp;main=1');
	}

	function _RepairMain()
	{
		global $PowerBB;

		$repair = $this->_RepairTables();

       $br = '<br>';
		echo('<br><br><table border="1" width="80%" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" style="border-collapse: collapse" align="center"><tr><td><font face="Tahoma" size="2">');

		foreach ($repair as $table => $success)
		{

			if ($success)
			{
				echo(" ".$PowerBB->_CONF['template']['_CONF']['lang']['Has_been_fixed_table'] . $table .' ..'. $br);
			}
			else
			{
				echo(" ".$PowerBB->_CONF['template']['_CONF']['lang']['Failure_in_the_repair_of_table'] . $table .' ..'. $br);
			}
		}


      		echo('</font><br></td></tr></table>');
	}

	function _RepairTables()
	{
	   global $PowerBB;
		$returns = array();

		foreach ($PowerBB->table as $k => $v)
		{
			$query = $PowerBB->DB->sql_query('REPAIR TABLE ' . $v);

			if ($query)
			{
				$returns[$v] = true;
			}
			else
			{
				$returns[$v] = false;
			}
		}

		return $returns;
	}

	function _pbboard_updates_start()
	{
		global $PowerBB;
            // get main dir
			$To = $PowerBB->functions->GetMianDir();
			$To = str_ireplace("index.php/", '', $To);
        if($PowerBB->_CONF['info_row']['MySBB_version'] == '3.0.4')
        {
		$pbboard_last_time_updates = 'https://pbboard.info/check_updates/pbboard_last_time_updates_304.txt';
		}
        elseif($PowerBB->_CONF['info_row']['MySBB_version'] == '3.0.3')
        {
		$pbboard_last_time_updates = 'https://pbboard.info/check_updates/pbboard_last_time_updates_303.txt';
		}
		elseif($PowerBB->_CONF['info_row']['MySBB_version'] == '3.0.2')
		{
		$pbboard_last_time_updates = 'https://pbboard.info/check_updates/pbboard_last_time_updates.txt';
		}

		$last_time_updates = @file_get_contents($pbboard_last_time_updates);

         if(!$last_time_updates)
		 {
		 $last_time_updates = $PowerBB->sys_functions->CURL_cloudFlareBypass($pbboard_last_time_updates);
		 }

		$arr = explode('-',$last_time_updates);

		$url     = trim($arr[2]);
        $urls= $PowerBB->sys_functions->CURL_cloudFlareBypass($url);

        $file_put = file_put_contents($To."Tmpfile.zip", $urls);

           $zip = new ZipArchive;
			$file = $To.'Tmpfile.zip';
			//$path = pathinfo(realpath($file), PATHINFO_DIRNAME);
			if ($zip->open($file) === TRUE) {
			    $zip->extractTo($To);

			    $ziped = true;
			} else {
			   $ziped = false;
			   echo 'Failed to open zip file';
			}

	         if($ziped)
	         {
	          if($PowerBB->admincpdir !='admincp')
			  {
					for ($i = 0; $i < $zip->numFiles; $i++) {
					   if(strstr($zip->getNameIndex($i),'admincp'))
					   {
						    $zip->renameIndex($i, str_replace("admincp",$PowerBB->admincpdir, $zip->getNameIndex($i)));
						     $zip->extractTo($To,$zip->getNameIndex($i));
					   }
					}
	          }
	         $zip->close();
			$PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_CONF['now'],'var_name'=>'last_time_updates'));
			unlink($file);
			echo $PowerBB->_CONF['template']['_CONF']['lang']['pbboard_updated'];
			}
			else
	         {
			 echo $PowerBB->_CONF['template']['_CONF']['lang']['automatic_update_fails'];
			}
	}

}



?>
