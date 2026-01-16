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
            // 1. Prevent Header for AJAX and Specific Actions
	        if (!isset($PowerBB->_GET['ajax_process']) && !isset($PowerBB->_GET['get_total']) && !isset($PowerBB->_GET['pbboard_updates']) && !isset($PowerBB->_GET['clear_all_files_cache']) && !isset($PowerBB->_GET['delete_orphan_replies'])) {
	            $PowerBB->template->display('header');
	        }

			if ($PowerBB->_CONF['rows']['group_info']['admincp_fixup'] == '0')
			{
			    $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
			}

            // --- Priority Check: Update Posts (Move to Top) ---
			if (isset($PowerBB->_GET['update_posts']) && (isset($PowerBB->_GET['ajax_process']) || isset($PowerBB->_GET['get_total'])))
			{
                 $this->_UpdatePostsStart();
			}
            // --- End Priority Check ---
			if (isset($PowerBB->_GET['delete_orphan_replies']))
			{
                 $this->_DeleteOrphanReplies();
			}
			elseif ($PowerBB->_GET['repair'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_RepairMain();
				}
			}
			elseif ($PowerBB->_GET['update_meter'])
			{
				if ($PowerBB->_GET['main'])
				{					$this->_MeterMain();
				}
				elseif ($PowerBB->_GET['start'])
				{
					$this->_MeterStart();
				}
				elseif (isset($PowerBB->_GET['all_cache']) && isset($PowerBB->_GET['ajax_process']))
				{
                    $this->_AllCacheStart();
				}
				elseif ($PowerBB->_GET['clear_all_files_cache'])
				{
					$deletedCacheFiles = $PowerBB->functions->clearForumsCacheFiles();
					if ($deletedCacheFiles) { die("SUCCESS"); }
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
			elseif ($PowerBB->_GET['pbboard_updates'] && $PowerBB->_GET['start'])
			{
				$this->_pbboard_updates_start();
			}

            // 2. Prevent Footer for AJAX and Specific Actions
	        if (!isset($PowerBB->_GET['ajax_process']) && !isset($PowerBB->_GET['get_total']) && !isset($PowerBB->_GET['pbboard_updates']) && !isset($PowerBB->_GET['delete_orphan_replies']) && !isset($PowerBB->_GET['clear_all_files_cache'])) {
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
	    @session_write_close();
	    // جلب رقم القسم عبر نظام السكربت المفلتر
	    $sec_id = isset($PowerBB->_GET['sec_id']) ? $PowerBB->_GET['sec_id'] : 0;

	    if ($sec_id > 0) {

         // 1. عدد الردود بانتظار الموافقة لهذا القسم (منطق الدالة القديمة)
	        $rev_q = $PowerBB->DB->sql_fetch_array($PowerBB->DB->sql_query("
	            SELECT COUNT(R.id) as total
	            FROM " . $PowerBB->table['reply'] . " R
	            INNER JOIN " . $PowerBB->table['subject'] . " S ON R.subject_id = S.id
	            WHERE S.section = '$sec_id' AND R.review_reply = '1' AND R.delete_topic = '0'
	        "));
	        $r_review_num = $rev_q['total'];

	        // 2. عدد المواضيع بانتظار الموافقة لهذا القسم (منطق الدالة القديمة)
	        $s_review = $PowerBB->DB->sql_fetch_array($PowerBB->DB->sql_query("
	            SELECT COUNT(id) as total FROM " . $PowerBB->table['subject'] . "
	            WHERE section = '$sec_id' AND review_subject = '1' AND delete_topic = '0'
	        "));
	        $s_review_num = $s_review['total'];

	        // 3. إحصائيات المواضيع والردود المعتمدة (الظاهرة للزوار)
	        $stats = $PowerBB->DB->sql_fetch_array($PowerBB->DB->sql_query("
	            SELECT COUNT(id) as total_s, SUM(reply_number) as total_r
	            FROM " . $PowerBB->table['subject'] . "
	            WHERE section = '$sec_id' AND delete_topic = '0' AND review_subject = '0'
	        "));
	        $s_num = $stats['total_s'];
	        $r_num = $stats['total_r'];

	        // 4. جلب بيانات آخر نشاط بناءً على write_time
	        $last_act = $PowerBB->DB->sql_fetch_array($PowerBB->DB->sql_query("
	            SELECT id, title, writer, last_replier, write_time, reply_number
	            FROM " . $PowerBB->table['subject'] . "
	            WHERE section = '$sec_id' AND delete_topic = '0' AND review_subject = '0'
	            ORDER BY CAST(write_time AS UNSIGNED) DESC LIMIT 1
	        "));

	        $l_id    = isset($last_act['id']) ? $last_act['id'] : 0;
	        $l_title = isset($last_act['title']) ? $last_act['title'] : '';
	        $l_time  = isset($last_act['write_time']) ? $last_act['write_time'] : 0;
	        $l_writer = (isset($last_act['reply_number']) && $last_act['reply_number'] > 0) ? $last_act['last_replier'] : (isset($last_act['writer']) ? $last_act['writer'] : '');


	        // 5. جلب بيانات آخر نشاط بناءً على last_reply
	        $last_reply = $PowerBB->DB->sql_fetch_array($PowerBB->DB->sql_query("
	            SELECT id
	            FROM " . $PowerBB->table['reply'] . "
	            WHERE section = '$sec_id' AND subject_id = '$l_id' AND delete_topic = '0' AND review_reply = '0'
	            ORDER BY CAST(write_time AS UNSIGNED) DESC LIMIT 1
	        "));
            $l_last_reply    = isset($last_reply['id']) ? $last_reply['id'] : 0;

	        // 6. جلب وتجهيز بيانات المشرفين
	        $mod_cache = array();
	        $mod_query = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['moderators'] . " WHERE section_id = '$sec_id'");
	        while ($mod = $PowerBB->DB->sql_fetch_array($mod_query)) {
	            $u_info = $PowerBB->DB->sql_fetch_array($PowerBB->DB->sql_query("SELECT id, avater_path, username_style_cache FROM " . $PowerBB->table['member'] . " WHERE id = '" . $mod['member_id'] . "' LIMIT 1"));
	            $mod_cache[] = array(
	                'id'                   => $mod['id'],
	                'section_id'           => $mod['section_id'],
	                'member_id'            => $mod['member_id'],
	                'username'             => $mod['username'],
	                'avater_path'          => (isset($u_info['avater_path']) ? $u_info['avater_path'] : ''),
	                'username_style_cache' => (isset($u_info['username_style_cache']) ? $u_info['username_style_cache'] : '')
	            );
	        }
	        $mod_json = (!empty($mod_cache)) ? json_encode($mod_cache, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : '[]';

	        // 7. التحديث الشامل لجدول الأقسام
	        $PowerBB->DB->sql_query("
	            UPDATE " . $PowerBB->table['section'] . "
	            SET subject_num = '$s_num',
	                reply_num = '$r_num',
	                subjects_review_num = '$s_review_num',
	                replys_review_num = '$r_review_num',
	                last_subjectid = '$l_id',
	                last_subject = '" . $PowerBB->DB->sql_escape($l_title) . "',
	                last_writer = '" . $PowerBB->DB->sql_escape($l_writer) . "',
	                last_reply = '$l_last_reply',
	                last_date = '$l_time',
	                last_time = '$l_time',
	                moderators = '" . $PowerBB->DB->sql_escape($mod_json) . "'
	            WHERE id = '$sec_id'
	        ");

	        // 8. بناء حقل forums_cache الهجين (خفيف وسريع)
	        $cache_array = array();
	        $parent_array = array();
	        $sub_forums_query = $PowerBB->DB->sql_query("SELECT id, title, subject_num, reply_num, last_subject, last_subjectid, last_date, last_writer, moderators FROM " . $PowerBB->table['section'] . " WHERE parent = '$sec_id' ORDER BY sort ASC");

	        if ($PowerBB->DB->sql_num_rows($sub_forums_query) > 0) {
	            while ($sub = $PowerBB->DB->sql_fetch_array($sub_forums_query)) {
	                $temp = array(
	                    'id' => $sub['id'], 'title' => $sub['title'], 'subject_num' => $sub['subject_num'],
	                    'reply_num' => $sub['reply_num'], 'last_subject' => $sub['last_subject'],
	                    'last_subjectid' => $sub['last_subjectid'], 'last_date' => $sub['last_date'],
	                    'last_writer' => $sub['last_writer'], 'moderators' => $sub['moderators']
	                );

						if($sub['parent'])
						{
						$parents['parent'] 	= 	$sub;
						}
	                // جلب ستايل وأفاتار آخر كاتب للقسم الفرعي
	                if (!empty($sub['last_writer'])) {
	                    $u = $PowerBB->DB->sql_fetch_array($PowerBB->DB->sql_query("SELECT id, avater_path, username_style_cache FROM " . $PowerBB->table['member'] . " WHERE username = '" . $PowerBB->DB->sql_escape($sub['last_writer']) . "' LIMIT 1"));
	                    if ($u) {
	                        $temp['last_writer_id'] = $u['id'];
	                        $temp['avater_path'] = $u['avater_path'];
	                        $temp['username_style_cache'] = $u['username_style_cache'];
	                    }
	                }
	                $cache_array[] = $temp;
	                $parent_array[] = $parents;
	            }
	        } else {
	            // بيانات العضو الأخير للقسم الرئيسي (إذا لم يكن له أقسام فرعية)
	            if (!empty($l_writer)) {
	                $u = $PowerBB->DB->sql_fetch_array($PowerBB->DB->sql_query("SELECT id, avater_path, username_style_cache FROM " . $PowerBB->table['member'] . " WHERE username = '" . $PowerBB->DB->sql_escape($l_writer) . "' LIMIT 1"));
	                if ($u) {
	                    $cache_array['u_last'] = array('last_writer_id' => $u['id'], 'avater_path' => $u['avater_path'], 'username_style_cache' => $u['username_style_cache']);
	                }
	            }
	        }

	        $json_cache = json_encode($json_cache,JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

	        $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['section'] . " SET forums_cache = '" . $PowerBB->DB->sql_escape($json_cache) . "' WHERE id = '$sec_id'");

            $UpdateSectionCache = $PowerBB->functions->UpdateSectionCache($sec_id);
	        // 9. تحديث الكاش البرمجي للسيرفر
	        if ($UpdateSectionCache) { die("SUCCESS"); }


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
		$page =  (!isset($PowerBB->_GET['pag']) ? 1 : $PowerBB->_GET['pag']);
		$page = ($page == 0 ? 1 : $page);

		$startpoint = ($page * $perpage) - $perpage;
		$member_nm = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(*),id FROM " . $PowerBB->table['member'] . " WHERE id"));
              $br = '<br>';
		echo('<br><br><table border="1" width="80%" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" style="border-collapse: collapse" align="center"><tr><td><font face="Tahoma" size="2">');

		$getmember_query = $PowerBB->DB->sql_query("SELECT id,usergroup,username FROM " . $PowerBB->table['member'] . " ORDER BY id DESC LIMIT ".$startpoint.",".$perpage." ");
	    while ($MemInfo = $PowerBB->DB->sql_fetch_array($getmember_query))
        {
        	$MemUsername = $MemInfo['username'];
		    $member_nm_reply = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(*) FROM " . $PowerBB->table['reply'] . " WHERE writer = '$MemUsername'"));
		    $member_nm_subject = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(*) FROM " . $PowerBB->table['subject'] . " WHERE writer = '$MemUsername'"));

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
	    @session_write_close();
	    @set_time_limit(0);

	    // -----------------------------
	    // 1. جلب العدد الإجمالي للمواضيع
	    // -----------------------------
	    if (isset($PowerBB->_GET['get_total'])) {
	        $query = $PowerBB->DB->sql_query(
	            "SELECT COUNT(*) FROM " . $PowerBB->table['subject'] . " WHERE delete_topic = '0'"
	        );
	        $row = $PowerBB->DB->sql_fetch_row($query); // يرجع الرقم مباشرة
	        echo json_encode(array("total" => $row));
	        exit;
	    }

	    // -----------------------------
	    // 2. معالجة الدفعات باستخدام last_id
	    // -----------------------------
	    if (isset($PowerBB->_GET['ajax_process'])) {
	        $perpage = isset($PowerBB->_GET['perpage']) ? $PowerBB->_GET['perpage'] : 100;
	        $last_id = isset($PowerBB->_GET['last_id']) ? $PowerBB->_GET['last_id'] : 0;

	        $sql = "
	            SELECT
	                s.id,
	                s.writer,
	                s.reply_number,
	                s.section,
	                (
	                    SELECT r.id
	                    FROM {$PowerBB->table['reply']} r
	                    WHERE r.subject_id = s.id
	                      AND r.review_reply = '0'
	                      AND r.delete_topic = '0'
	                    ORDER BY r.id DESC
	                    LIMIT 1
	                ) AS last_r_id
	            FROM {$PowerBB->table['subject']} s
	            WHERE s.delete_topic='0' AND s.id > $last_id
	            ORDER BY s.id ASC
	            LIMIT $perpage
	        ";

	        $res = $PowerBB->DB->sql_query($sql);
	        $max_id = 0; // لتخزين آخر ID تم معالجته

	        while ($sub = $PowerBB->DB->sql_fetch_array($res)) {
	            $max_id = $sub['id'];

	            // تحديد آخر كاتب
	            if (!empty($sub['last_r_id'])) {
	                $reply = $PowerBB->DB->sql_fetch_array(
	                    $PowerBB->DB->sql_query(
	                        "SELECT writer FROM " . $PowerBB->table['reply'] . " WHERE id = ".$sub['last_r_id']
	                    )
	                );
	                $target_user = $reply['writer'];
	                $is_reply = true;
	            } else {
	                $target_user = $sub['writer'];
	                $is_reply = false;
	            }

	            // جلب بيانات العضو
	            $user_data = $PowerBB->DB->sql_fetch_array(
	                $PowerBB->DB->sql_query(
	                    "SELECT id, username_style_cache, avater_path
	                     FROM " . $PowerBB->table['member'] . "
	                     WHERE username = '".$PowerBB->DB->sql_escape($target_user)."'
	                     LIMIT 1"
	                )
	            );

	            $style = (!empty($user_data['username_style_cache'])) ? $user_data['username_style_cache'] : '{username}';
	            $styled_name = str_replace('{username}', $target_user, $style);

	            // بناء الكاش
	            if ($is_reply) {
	                $perpage_view = $PowerBB->_CONF['info_row']['perpage'];
	                $countpage = ceil(($sub['reply_number'] + 1) / $perpage_view);

	                $cache = array(
	                    1 => $user_data['id'],
	                    2 => $user_data['avater_path'],
	                    3 => $style,
	                    4 => $sub['section'],
	                    5 => $sub['reply_number'],
	                    6 => $sub['last_r_id'],
	                    7 => $countpage,
	                    8 => $styled_name
	                );
	            } else {
	                $cache = array(
	                    1 => $user_data['id'],
	                    8 => $styled_name
	                );
	            }

	            // تحديث الموضوع
	            $PowerBB->DB->sql_query(
	                "UPDATE " . $PowerBB->table['subject'] . " SET
	                    last_replier = '".$PowerBB->DB->sql_escape($target_user)."',
	                    lastreply_cache = '".$PowerBB->DB->sql_escape(serialize($cache))."'
	                 WHERE id = ".$sub['id']
	            );
	        }

	        // إعادة آخر ID للـ JS
	        if ($max_id > 0) {
	            echo "SUCCESS|$max_id";
	        } else {
	            echo "SUCCESS|0"; // انتهت جميع الدفعات
	        }
	        exit;
	    }
	}


	function _DeleteOrphanReplies()
	{
	    global $PowerBB;
	    @set_time_limit(0);

	    // عدّ الردود اليتيمة قبل الحذف
	    $count_res = $PowerBB->DB->sql_query("
	        SELECT COUNT(*) AS total
	        FROM {$PowerBB->table['reply']} r
	        LEFT JOIN {$PowerBB->table['subject']} s
	            ON r.subject_id = s.id
	        WHERE s.id IS NULL
	    ");
	    $row = $PowerBB->DB->sql_fetch_row($count_res);
	    $orphan_count = (int)$row;

	    // الحذف المباشر لجميع الردود التي لا تتبع أي موضوع موجود
	    $sql = "
	        DELETE r
	        FROM {$PowerBB->table['reply']} r
	        LEFT JOIN {$PowerBB->table['subject']} s
	            ON r.subject_id = s.id
	        WHERE s.id IS NULL
	    ";
	    $PowerBB->DB->sql_query($sql);

	    echo json_encode(array(
	        'status' => 'success',
	        'message' => "تم حذف {$orphan_count} رد/ردود يتيماً بنجاح."
	    ));
	    exit;
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
		$page =  (!isset($PowerBB->_GET['pag']) ? 1 : $PowerBB->_GET['pag']);
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

		// 1. Initialize main directory path
		$To = $PowerBB->functions->GetMianDir();
		$To = str_ireplace("index.php/", '', $To);

		// 2. Determine update source based on version
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

		// 3. Fetch update metadata from GitHub
		$last_time_updates = @file_get_contents($pbboard_last_time_updates);
		if(!$last_time_updates) {
			$last_time_updates = $PowerBB->sys_functions->CURL_URL($pbboard_last_time_updates);
		}

		$arr = explode('-', $last_time_updates);
		$url = trim($arr[2]);
		$zipFile = $To . "Tmpfile.zip";

		/**
		 * STEP 4: SELF-PATCHING PHASE
		 * If 'is_fixup_module' is flagged, we extract ONLY the fixup module first.
		 * This replaces the OLD logic on disk before the main extraction.
		 */
		if(trim($arr[6]) == 'is_fixup_module') {
			$urls = $PowerBB->sys_functions->CURL_URL($url);
			file_put_contents($zipFile, $urls);

			$zip = new ZipArchive;
			if ($zip->open($zipFile) === TRUE) {
				$adminDir = $PowerBB->admincpdir;

				// Path of the new fixup module inside the ZIP (Default structure)
				$fixup_in_zip = 'admincp/modulescp/fixup.module.php';
				$new_content = $zip->getFromName($fixup_in_zip);

				if ($new_content) {
					// Path to the current running module on the server
					$fixup_on_disk = '../' . $adminDir . '/modulescp/fixup.module.php';

					if (@file_put_contents($fixup_on_disk, $new_content)) {
						$zip->close();
						@unlink($zipFile); // Clean up to start fresh

						// Trigger AJAX restart to load the NEW code into RAM
						echo "<div style='background:#e0f2fe; color:#0369a1; padding:15px; border-radius:8px; border:1px solid #bae6fd;'>
								<i class='fa fa-sync fa-spin'></i> Core updated. Restarting to apply new logic...
							  </div>";
						echo "<script type='text/javascript'>setTimeout(function(){ AjaxUpdated(); }, 1500);</script>";
						exit;
					}
				}
				$zip->close();
			}
		}

		/**
		 * STEP 5: MAIN EXTRACTION PHASE
		 * This runs only when the file has the NEW logic (either patched or already updated).
		 */
		$urls = $PowerBB->sys_functions->CURL_URL($url);
		file_put_contents($zipFile, $urls);

		$zip = new ZipArchive;
		$ziped = false;

		if ($zip->open($zipFile) === TRUE) {
			$adminDir = $PowerBB->admincpdir;

			for ($i = 0; $i < $zip->numFiles; $i++) {
				$fileName = $zip->getNameIndex($i);
				$targetName = $fileName;

				// Map 'admincp/' from ZIP to the user's custom admin folder
				if (strpos($fileName, 'admincp/') === 0) {
					$targetName = str_replace('admincp/', $adminDir . '/', $fileName);
				}

				$fullPath = $To . $targetName;

				if (substr($fileName, -1) === '/') {
					if (!is_dir($fullPath)) @mkdir($fullPath, 0755, true);
				} else {
					$parentDir = dirname($fullPath);
					if (!is_dir($parentDir)) @mkdir($parentDir, 0755, true);

					$content = $zip->getFromIndex($i);
					@file_put_contents($fullPath, $content);
				}
			}
			$zip->close();
			$ziped = true;
		} else {
			$ziped = false;
			echo 'Failed to open zip file';
		}

		/**
		 * STEP 6: POST-UPDATE (SQL, Templates, Cache, Cleanup)
		 */
		if($ziped) {
			// SQL Execution
			if(trim($arr[3]) == 'sql') {
				$this->_pbboard_updates_sql(trim($arr[0]));
				echo "\n<br />✅ SQL Queries executed successfully \n";
			}

			// Template Execution
			if(trim($arr[4]) == 'templates') {
				$this->_pbboard_updates_templates(trim($arr[0]));
				echo "\n<br />✅ Templates updated successfully \n<br />";
			}

			// Cache Notification
			if(trim($arr[5]) == 'cache') {
				printf('<div style="font-family: \'Droid Arabic Kufi\', Tahoma, sans-serif; background: #fffcf5; border: 1px solid #fce8c3; border-right: 5px solid #f59e0b; color: #92400e; padding: 12px 15px; margin: 15px 0; border-radius: 6px; font-size: 13px; direction: rtl; line-height: 1.8;">
					<strong>تنبيه:</strong> يستوجب بعد هذا التحديث: :
					<code>ان تحدث كاش الأقسام بالتوجه الى: الصيانة تحديث العدادات تحديث كافة الأقسام دفعة واحدة</code>
				</div>');
			}

			// Finalize and Clean up
			$PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_CONF['now'],'var_name'=>'last_time_updates'));
			@unlink($zipFile);

			echo $PowerBB->_CONF['template']['_CONF']['lang']['pbboard_updated'];

			if(trim($arr[3]) == 'sql' || trim($arr[4]) == 'templates') {
				@unlink($To . 'addons/' . $arr[0] . '.xml');
			}
		} else {
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
