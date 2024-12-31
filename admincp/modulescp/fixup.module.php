<?php

(!defined('IN_PowerBB')) ? die() : '';

define('IN_ADMIN',true);
define('DONT_STRIPS_SLIASHES',true);
$CALL_SYSTEM			        =	array();
$CALL_SYSTEM['ADDONS']         =   true;
$CALL_SYSTEM['HOOKS']         =   true;
$CALL_SYSTEM['STYLE'] 	= 	true;
$CALL_SYSTEM['TEMPLATE'] 	= 	true;
$CALL_SYSTEM['TEMPLATESEDITS'] 	= 	true;
define('JAVASCRIPT_PowerCode',true);

define('CLASS_NAME','PowerBBFixMOD');

include('../common.php');
class PowerBBFixMOD
{
	function run()
	{
		global $PowerBB;

		if ($PowerBB->_CONF['member_permission'])
		{
            if (!$PowerBB->_GET['pbboard_updates']
            or !$PowerBB->_GET['all_cache']){
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

        $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['section'] . " SET sectiongroup_cache = '' WHERE id=".$PowerBB->_POST['section']."");

		$REPAIR_TABLE = $PowerBB->DB->sql_query("REPAIR TABLE " . $PowerBB->table['section'] . "");
		$REPAIR_TABLE_info = $PowerBB->DB->sql_query("REPAIR TABLE " . $PowerBB->table['info'] . "");

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
               $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['section'] . " SET sectiongroup_cache = '' WHERE id=".$cat['id']."");


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
		              $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['section'] . " SET sectiongroup_cache = '' WHERE id=".$forum['id']."");


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
		               $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['section'] . " SET sectiongroup_cache = '' WHERE id=".$sub['id']."");

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
				              $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['section'] . " SET sectiongroup_cache = '' WHERE id=".$subsub['id']."");
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
		{
		$page = 1;
		}
		else
		{
		$page = $PowerBB->_GET['pag'];
		}
		$page = ($page == 0 ? 1 : $page);
		$perpage = 8;
		$startpoint = ($page * $perpage) - $perpage;
		if(!isset($PowerBB->_GET['pag']))
		{
		 $Empty_cache = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['section'] . " SET forums_cache = '', sectiongroup_cache = ''");

		  if($Empty_cache)
		  {
		  $REPAIR_TABLE = $PowerBB->DB->sql_query("REPAIR TABLE " . $PowerBB->table['section'] . "");
		  $REPAIR_TABLE_info = $PowerBB->DB->sql_query("REPAIR TABLE " . $PowerBB->table['info'] . "");
		  }
		}

 		$forumArr = $PowerBB->DB->sql_query("SELECT id,title FROM " . $PowerBB->table['section'] . " ORDER BY id DESC LIMIT ".$startpoint.",".$perpage." ");
		$forum_nm = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(id) FROM " . $PowerBB->table['section'] . ""));

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
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['updated_successfully']);

			$Update_Cache_groups = $PowerBB->functions->Update_Cache_groups();
			$permission = $PowerBB->functions->_MeterGroupsStart();
            $REPAIR_TABLE = $PowerBB->DB->sql_query("REPAIR TABLE " . $PowerBB->table['section'] . "");
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
	     {
                 $truncate_sectiongroup = $PowerBB->DB->sql_query("TRUNCATE " . $PowerBB->table['section_group'] );

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
              {
              		$DelReplys1Arr 						= 	array();
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
		$pbboard_last_time_updates = 'https://raw.githubusercontent.com/pbboard/updates/main/check_updates/pbboard_last_time_updates_304.txt';
		}
        elseif($PowerBB->_CONF['info_row']['MySBB_version'] == '3.0.3')
        {
		$pbboard_last_time_updates = 'https://raw.githubusercontent.com/pbboard/updates/main/check_updates/pbboard_last_time_updates_303.txt';
		}
		elseif($PowerBB->_CONF['info_row']['MySBB_version'] == '3.0.2')
		{
		$pbboard_last_time_updates = 'https://raw.githubusercontent.com/pbboard/updates/main/check_updates/pbboard_last_time_updates.txt';
		}

		$last_time_updates = @file_get_contents($pbboard_last_time_updates);

         if(!$last_time_updates)
		 {
		 $last_time_updates = $PowerBB->sys_functions->CURL_URL($pbboard_last_time_updates);
		 }

		$arr = explode('-',$last_time_updates);

		$url     = trim($arr[2]);
        $urls= $PowerBB->sys_functions->CURL_URL($url);

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

             // updates chmods sql
             if(trim($arr[3]) == 'sql')
             {
	         $xml_code_sql     = trim($arr[0]);
	         $updates_sql = $this->_pbboard_updates_sql($xml_code_sql);
			  echo("\n<br />✅ Query executed successfully \n");
	         }

             // updates templates
             if(trim($arr[4]) == 'templates')
             {
	         $xml_date     = trim($arr[0]);
	         $updates_templates = $this->_pbboard_updates_templates($xml_date);
			  echo("\n<br />✅ Templates eupdated successfully \n<br />");
	         }

			$PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_CONF['now'],'var_name'=>'last_time_updates'));
			unlink($file);
			echo $PowerBB->_CONF['template']['_CONF']['lang']['pbboard_updated'];

			  if(trim($arr[3]) == 'sql'
			  or trim($arr[4]) == 'templates')
			  {
			 	// get main dir
				$xml_file = $PowerBB->functions->GetMianDir();
				$xml_file = str_ireplace("index.php/", '', $xml_file);
				$file_x = $xml_file.'addons/'.$arr[0].'.xml';
				unlink($file_x);
			  }
			}
			else
	         {
			 echo $PowerBB->_CONF['template']['_CONF']['lang']['automatic_update_fails'];
			}
	}


	function _pbboard_updates_sql($xml_code_sql)
	{
	   global $PowerBB;
        $xml_dir = '../addons/'.$xml_code_sql.'.xml';
		if (file_exists($xml_dir))
		{
			$xml_code = @file_get_contents($xml_dir);
			$xml_code = $PowerBB->sys_functions->ReplaceMysqlExtension($xml_code);
			$plugin = $PowerBB->addons->xml_to_array($xml_code);
			$installcode = $plugin['plugin']['installcode']['value'];
			$Query_executed = eval($installcode);
	    }
    }

  function _pbboard_updates_templates($xml_date)
	{
	   global $PowerBB;
        $xml_dir = '../addons/'.$xml_date.'.xml';
		if (file_exists($xml_dir))
		{
			$xml_code = @file_get_contents($xml_dir);
			$xml_code = $PowerBB->sys_functions->ReplaceMysqlExtension($xml_code);
			$plugin = $PowerBB->addons->xml_to_array($xml_code);
			$StyleList = $PowerBB->style->GetStyleList(array());
            $Templates = $plugin['plugin']['templates']['template'];

			 if($Templates)
				 {
					if(!isset($plugin['plugin']['templates']['template'][1]))
					{

						$find = $Templates['find']['value'];
						$action = $Templates['action']['value'];
			        	$find	=	str_replace("'","{sq}",$find);
			        	$action	=	str_replace("'","{sq}",$action);
			            $find = @preg_replace( "/\\\(?!&amp;#|\?#)/", "&#092;", $find );
			            $action = @preg_replace( "/\\\(?!&amp;#|\?#)/", "&#092;", $action );
						$Templates['attributes']['name'] = @str_replace(".tpl",'',$Templates['attributes']['name']);


						$Templattitle = $Template['attributes']['name'];
						$Templattitle = @str_replace(".tpl",'',$Templattitle);
						foreach($StyleList as $Style)
						 {
						      $StyleId = $Style['id'];
							 $_query= $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['template'] . " WHERE title = '$Templattitle' and styleid = '$StyleId' ");
							 $row = $PowerBB->DB->sql_fetch_array($_query);

						    if($Template['attributes']['type'] == 'new')
							{
					                $Template['text']['value'] = str_replace("'", "&#39;", $Template['text']['value']);

									$TemplateArr 			= 	array();
									$TemplateArr['field']	=	array();

									$TemplateArr['field']['title'] 		    = 	$Templattitle;
									$TemplateArr['field']['template'] 	    = 	$Template['text']['value'];
									$TemplateArr['field']['template_un'] 	    	= 	$Template['text']['value'];
									$TemplateArr['field']['templatetype'] 		        = 	"template";
									$TemplateArr['field']['dateline'] 		            = 	$PowerBB->_CONF['now'];
									$TemplateArr['field']['version'] 	    = 	$PowerBB->_CONF['info_row']['MySBB_version'];
									$TemplateArr['field']['username'] 	    = 	$PowerBB->_CONF['rows']['member_row']['username'];
									$TemplateArr['field']['product'] 		    = 	"Addons";
									$TemplateArr['field']['styleid'] 		    = 	$StyleId;

									$insert = $PowerBB->core->Insert($TemplateArr,'template');

							}
			         		if($Template['attributes']['type'] == 'replace')
							{

								$contents = $row['template'];
								$contents = str_replace("&#39;", "'", $contents);
					            $new_contents	=	str_replace($Template['find']['value'],$Template['action']['value'], $contents);
					            $new_contents = str_replace("'", "&#39;", $new_contents);

								$TemplateArr 			= 	array();
								$TemplateArr['field']	=	array();

								$TemplateArr['field']['template'] 		= 	$new_contents;
								$TemplateArr['field']['dateline'] 		    = $PowerBB->_CONF['now'];
								$TemplateArr['where'] 				= 	array('templateid',$row['templateid']);

								$update = $PowerBB->core->Update($TemplateArr,'template');
			                     if ($update)
			                     {
									$TemplateEditArr				=	array();
									$TemplateEditArr['where'] 	= 	array('templateid',$row['templateid']);

									$TemplateEdit = $PowerBB->core->GetInfo($TemplateEditArr,'template');

									$templates_dir = ("../cache/templates_cache/".$TemplateEdit['title']."_".$TemplateEdit['styleid'].".php");

									if (file_exists($templates_dir))
									{
								 	 $cache_del = @unlink($templates_dir);
									}
								}
							 }
			         		if($Template['attributes']['type'] == 'before')
							{

								$contents = $row['template'];
								$contents = str_replace("&#39;", "'", $contents);
								$contents	=	str_replace(PHP_EOL.$Template['action']['value'],"", $contents);
					             $new_contents	=	str_replace($Template['find']['value'],$Template['action']['value']."\n".$Template['find']['value'], $contents);
					            $new_contents = str_replace("'", "&#39;", $new_contents);

								$TemplateArr 			= 	array();
								$TemplateArr['field']	=	array();

								$TemplateArr['field']['template'] 		= 	$new_contents;
								$TemplateArr['field']['dateline'] 		    = $PowerBB->_CONF['now'];
								$TemplateArr['where'] 				= 	array('templateid',$row['templateid']);

								$update = $PowerBB->core->Update($TemplateArr,'template');
			                     if ($update)
			                     {
									$TemplateEditArr				=	array();
									$TemplateEditArr['where'] 	= 	array('templateid',$row['templateid']);

									$TemplateEdit = $PowerBB->core->GetInfo($TemplateEditArr,'template');

									$templates_dir = ("../cache/templates_cache/".$TemplateEdit['title']."_".$TemplateEdit['styleid'].".php");

									if (file_exists($templates_dir))
									{
								 	 $cache_del = @unlink($templates_dir);
									}
								}

							 }
			         		if($Template['attributes']['type'] == 'after')
							{

								$contents = $row['template'];
								$contents = str_replace("&#39;", "'", $contents);
								 $contents	=	str_replace($Template['action']['value'].PHP_EOL,"", $contents);
					             $new_contents	=	str_replace($Template['find']['value'],$Template['find']['value']."\n".$Template['action']['value'], $contents);

					            $new_contents = str_replace("'", "&#39;", $new_contents);

								$TemplateArr 			= 	array();
								$TemplateArr['field']	=	array();

								$TemplateArr['field']['template'] 		= 	$new_contents;
								$TemplateArr['field']['dateline'] 		    = $PowerBB->_CONF['now'];
								$TemplateArr['where'] 				= 	array('templateid',$row['templateid']);

								$update = $PowerBB->core->Update($TemplateArr,'template');
			                     if ($update)
			                     {
									$TemplateEditArr				=	array();
									$TemplateEditArr['where'] 	= 	array('templateid',$row['templateid']);

									$TemplateEdit = $PowerBB->core->GetInfo($TemplateEditArr,'template');

									$templates_dir = ("../cache/templates_cache/".$TemplateEdit['title']."_".$TemplateEdit['styleid'].".php");

									if (file_exists($templates_dir))
									{
								 	 $cache_del = @unlink($templates_dir);
									}
								}

							 }
							if($Template['attributes']['type'] == 'retrieval')
							{
							 $this->_retrieved_template_original_Start($Templattitle);
							}

			              }

					}
					else
					{

					foreach($Templates as $Template)
					 {
						$find = $Template['find']['value'];
						$action = $Template['action']['value'];

						$Template['attributes']['name'] = @str_replace(".tpl",'',$Template['attributes']['name']);

						foreach($StyleList as $Style)
						{

							$Templattitle = $Template['attributes']['name'];
							$Templattitle = @str_replace(".tpl",'',$Templattitle);
							$StyleId = $Style['id'];
							$_query= $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['template'] . " WHERE title = '$Templattitle' and styleid = '$StyleId' ");
							$row = $PowerBB->DB->sql_fetch_array($_query);

						    if($Template['attributes']['type'] == 'new')
							{
					                $Template['text']['value'] = str_replace("'", "&#39;", $Template['text']['value']);

									$TemplateArr 			= 	array();
									$TemplateArr['field']	=	array();

									$TemplateArr['field']['title'] 		    = 	$Templattitle;
									$TemplateArr['field']['template'] 	    = 	$Template['text']['value'];
									$TemplateArr['field']['template_un'] 	    	= 	$Template['text']['value'];
									$TemplateArr['field']['templatetype'] 		        = 	"template";
									$TemplateArr['field']['dateline'] 		            = 	$PowerBB->_CONF['now'];
									$TemplateArr['field']['version'] 	    = 	$PowerBB->_CONF['info_row']['MySBB_version'];
									$TemplateArr['field']['username'] 	    = 	$PowerBB->_CONF['rows']['member_row']['username'];
									$TemplateArr['field']['product'] 		    = 	"Addons";
									$TemplateArr['field']['styleid'] 		    = 	$StyleId;

									$insert = $PowerBB->core->Insert($TemplateArr,'template');

							}
			         		if($Template['attributes']['type'] == 'replace')
							{

								$contents = $row['template'];
								$contents = str_replace("&#39;", "'", $contents);
					            $new_contents	=	str_replace($Template['find']['value'],$Template['action']['value'], $contents);
					            $new_contents = str_replace("'", "&#39;", $new_contents);

								$TemplateArr 			= 	array();
								$TemplateArr['field']	=	array();

								$TemplateArr['field']['template'] 		= 	$new_contents;
								$TemplateArr['field']['dateline'] 		    = $PowerBB->_CONF['now'];
								$TemplateArr['where'] 				= 	array('templateid',$row['templateid']);

								$update = $PowerBB->core->Update($TemplateArr,'template');
			                     if ($update)
			                     {
									$TemplateEditArr				=	array();
									$TemplateEditArr['where'] 	= 	array('templateid',$row['templateid']);

									$TemplateEdit = $PowerBB->core->GetInfo($TemplateEditArr,'template');

									$templates_dir = ("../cache/templates_cache/".$TemplateEdit['title']."_".$TemplateEdit['styleid'].".php");

									if (file_exists($templates_dir))
									{
								 	 $cache_del = @unlink($templates_dir);
									}
								}

							 }
			         		if($Template['attributes']['type'] == 'before')
							{

								$contents = $row['template'];
								$contents = str_replace("&#39;", "'", $contents);
					            $contents	=	str_replace(PHP_EOL.$Template['action']['value'],"", $contents);
					            $new_contents	=	str_replace($Template['find']['value'],$Template['action']['value']."\n".$Template['find']['value'], $contents);
					            $new_contents = str_replace("'", "&#39;", $new_contents);

								$TemplateArr 			= 	array();
								$TemplateArr['field']	=	array();

								$TemplateArr['field']['template'] 		= 	$new_contents;
								$TemplateArr['field']['dateline'] 		    = $PowerBB->_CONF['now'];
								$TemplateArr['where'] 				= 	array('templateid',$row['templateid']);

								$update = $PowerBB->core->Update($TemplateArr,'template');
			                     if ($update)
			                     {
									$TemplateEditArr				=	array();
									$TemplateEditArr['where'] 	= 	array('templateid',$row['templateid']);

									$TemplateEdit = $PowerBB->core->GetInfo($TemplateEditArr,'template');

									$templates_dir = ("../cache/templates_cache/".$TemplateEdit['title']."_".$TemplateEdit['styleid'].".php");

									if (file_exists($templates_dir))
									{
								 	 $cache_del = @unlink($templates_dir);
									}
								}

							 }
			         		if($Template['attributes']['type'] == 'after')
							{

								$contents = $row['template'];
								$contents = str_replace("&#39;", "'", $contents);
								$contents	=	str_replace($Template['action']['value'].PHP_EOL,"", $contents);
					            $new_contents	=	str_replace($Template['find']['value'],$Template['find']['value']."\n".$Template['action']['value'], $contents);

					            $new_contents = str_replace("'", "&#39;", $new_contents);

								$TemplateArr 			= 	array();
								$TemplateArr['field']	=	array();

								$TemplateArr['field']['template'] 		= 	$new_contents;
								$TemplateArr['field']['dateline'] 		    = $PowerBB->_CONF['now'];
								$TemplateArr['where'] 				= 	array('templateid',$row['templateid']);

								$update = $PowerBB->core->Update($TemplateArr,'template');
			                     if ($update)
			                     {
									$TemplateEditArr				=	array();
									$TemplateEditArr['where'] 	= 	array('templateid',$row['templateid']);

									$TemplateEdit = $PowerBB->core->GetInfo($TemplateEditArr,'template');

									$templates_dir = ("../cache/templates_cache/".$TemplateEdit['title']."_".$TemplateEdit['styleid'].".php");

									if (file_exists($templates_dir))
									{
								 	 $cache_del = @unlink($templates_dir);
									}
								}

							 }
							if($Template['attributes']['type'] == 'retrieval')
							{
							 $this->_retrieved_template_original_Start($Templattitle);
							}
						}


					}
			       }

				 }

	    }
    }


	function _retrieved_template_original_Start($template_name)
    {
    	global $PowerBB;

	 if (empty($template_name))
	  {
		return;
	  }
	  else
	  {

         $originalfile ="../cache/original_default_templates.xml";

			if (file_exists($originalfile))
			{

			   $xml_code = @file_get_contents($originalfile);

			}
			if (strstr($xml_code,'decode="0"'))
			{
				$xml_code = str_replace('decode="0"','decode="1"',$xml_code);
				preg_match_all('/<!\[CDATA\[(.*?)\]\]>/is', $xml_code, $match);
				foreach($match[0] as $val)
				{
				$xml_code = str_replace($val,base64_encode($val),$xml_code);
				}

			}
        		$import = $PowerBB->functions->xml_array($xml_code);
				$Templates = $import['styles']['templategroup'];
				$Templates_number = sizeof($import['styles']['templategroup']['template']);

			            $x = 0;
     			while ($x < $Templates_number)
     			{
						$templatetitle = $Templates['template'][$x.'_attr']['name'];
						$version = $Templates['template'][$x.'_attr']['version'];
						$template_version = $Templates['template'][$x.'_attr']['version'];
						$template_version = str_replace(".", "", $template_version);
						if ($Templates['template'][$x.'_attr']['decode'] == '1'
						or $template_version <= '301')
						{
						$template = @base64_decode($Templates['template'][$x]);
     				    }
     				    else
						{
						$template = $Templates['template'][$x];
     				    }
     				    $template = str_replace("//<![CDATA[", "", $template);
						$template = str_replace("//]]>", "", $template);
     				    $template = str_replace("<![CDATA[","", $template);
						$template = str_replace("]]>","", $template);

	                if($template_name == $templatetitle)
				    {
				      $row['template_un'] = $template;
				      $x = 0;
				      break;
				    }
                     $x += 1;
                 }



        $row['template_un'] = str_replace("'", "&#39;", $row['template_un']);

		$TemplateArr 			= 	array();
		$TemplateArr['field']	=	array();

		$TemplateArr['field']['template'] 		= 	$row['template_un'];
		$TemplateArr['field']['template_un'] 		= 	$row['template_un'];
		$TemplateArr['field']['dateline'] 		    = $PowerBB->_CONF['now'];
		$TemplateArr['where'] 				= 	array('title',$template_name);

		$update = $PowerBB->core->Update($TemplateArr,'template');

		if ($update)
		{

		 $_query1= $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['template'] . " WHERE title = '$template_name'");
		 while ($row1 = $PowerBB->DB->sql_fetch_array($_query1))
		 {			$TemplateEditArr				=	array();
			$TemplateEditArr['where'] 				    = 	array('templateid',$row1['templateid']);

			$TemplateEdit = $PowerBB->core->GetInfo($TemplateEditArr,'template');

            $templates_dir = ("../cache/templates_cache/".$TemplateEdit['title']."_".$TemplateEdit['styleid'].".php");
			 if (file_exists($templates_dir))
			 {
			  $cache_del = @unlink($templates_dir);
			 }
    	 }
		}
	  }
	}

}



?>
