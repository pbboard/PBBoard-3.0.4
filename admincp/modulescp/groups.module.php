<?php
(!defined('IN_PowerBB')) ? die() : '';
define('IN_ADMIN',true);


define('CLASS_NAME','PowerBBGroupsMOD');

include('../common.php');
class PowerBBGroupsMOD extends _functions
{
	function run()
	{
		global $PowerBB;


		if ($PowerBB->_CONF['member_permission'])
		{
			$PowerBB->template->display('header');


			if ($PowerBB->_CONF['rows']['group_info']['admincp_member'] == '0')
			{
			  $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
			}

			if ($PowerBB->_GET['add'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_AddMain();
				}
				elseif ($PowerBB->_GET['start'])
				{
					$this->_AddStart();
				}
			}
			elseif ($PowerBB->_GET['control'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_ControlMain();
				}
			}
			elseif ($PowerBB->_GET['edit'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_EditMain();
				}
				elseif ($PowerBB->_GET['start'])
				{
					$this->_EditStart();
				}
			}
			elseif ($PowerBB->_GET['del'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_DelMain();
				}
				elseif ($PowerBB->_GET['start'])
				{
					$this->_DelStart();
				}
			}
			elseif ($PowerBB->_GET['move'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_MoveMain();
				}
				elseif ($PowerBB->_GET['start'])
				{
					$this->_MoveStart();
				}
			}
			elseif ($PowerBB->_GET['shwo'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_ShwoMemberGroup();
				}
			}
			elseif ($PowerBB->_GET['update_group_meter'])
			{
				if ($PowerBB->_GET['group_cache'])
				{
					$this->_AllCacheGroupStart();
				}
			}

			$PowerBB->template->display('footer');
		}
	}

	function _AddMain()
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

		// We will use forums_list to store list of forums which will view in main page
		$PowerBB->_CONF['template']['foreach']['forums_list'] = array();

		// Loop to read the information of main sections
		foreach ($cats as $cat)
		{
			// Get the groups information to know view this section or not
			$groups = json_decode(base64_decode($cat['sectiongroup_cache']), true);


					$PowerBB->_CONF['template']['foreach']['forums_list'][$cat['id'] . '_m'] = $cat;


			if (!empty($cat['forums_cache']))
			{
				$forums = json_decode(base64_decode($cat['forums_cache']), true);

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

		// Get groups list
		$GroupArr 							= 	array();
		$GroupArr['order']					=	array();
		$GroupArr['order']['field']			=	'id';
		$GroupArr['order']['type']			=	'ASC';

		$GroupArr['proc'] 					= 	array();
		$GroupArr['proc']['*'] 				= 	array('method'=>'clean','param'=>'html');

		// Store information in "GroupList"
		$PowerBB->_CONF['template']['while']['GroupList'] = $PowerBB->core->GetList($GroupArr,'group');

		//////////

		$PowerBB->template->display('group_add');
	}

	function _AddStart()
	{
		global $PowerBB;

		if (empty($PowerBB->_POST['name']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['please_enter_the_text']." (<b>".$PowerBB->_CONF['template']['_CONF']['lang']['Group_name']."</b>)");
		}

		if (empty($PowerBB->_POST['style']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['please_enter_the_text']." (<b>".$PowerBB->_CONF['template']['_CONF']['lang']['username_color']."</b>)");
		}

		if (empty($PowerBB->_POST['usertitle']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['please_enter_the_text']." (<b>".$PowerBB->_CONF['template']['_CONF']['lang']['usertitle']."</b>)");
		}

 		if ($PowerBB->_POST['order_type'] == 'manual' and empty($PowerBB->_POST['group_order']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['please_enter_the_text']." (<b>".$PowerBB->_CONF['template']['_CONF']['lang']['group_order']."</b>)");
		}


      $group_order = 0;

		if ($PowerBB->_POST['order_type'] == 'auto')
		{
			$GroupArr = array();
			$GroupArr['order'] = array();
			$GroupArr['order']['field'] = 'group_order';
			$GroupArr['order']['type'] = 'DESC';

			$OrderGroup = $PowerBB->core->GetInfo($GroupArr,'group');

			// No section
			if (!$OrderGroup)
			{
				$group_order = 1;
			}
			// There is a section
			else
			{
				$group_order = $OrderGroup['group_order'] + 1;
			}
		}
		else
		{
			$group_order = $PowerBB->_POST['group_order'];
		}

		// Enable HTML and (only) HTML
		$PowerBB->_POST['style'] = $PowerBB->functions->CleanVariable($PowerBB->_POST['style'],'unhtml');

		$GroupArr 			= 	array();
		$GroupArr['field']	=	array();

		$GroupArr['field']['title'] 					= 	$PowerBB->_POST['name'];
		$GroupArr['field']['username_style'] 			= 	$PowerBB->_POST['style'];
		$GroupArr['field']['user_title'] 				= 	$PowerBB->_POST['usertitle'];
		$GroupArr['field']['forum_team'] 				= 	$PowerBB->_POST['forum_team'];
		$GroupArr['field']['banned'] 					= 	$PowerBB->_POST['banned'];
		$GroupArr['field']['view_section'] 				= 	$PowerBB->_POST['view_section'];
		$GroupArr['field']['view_subject'] 				= 	$PowerBB->_POST['view_subject'];
		$GroupArr['field']['download_attach'] 			= 	$PowerBB->_POST['download_attach'];
		$GroupArr['field']['download_attach_number'] 	= 	$PowerBB->_POST['download_attach_number'];
		$GroupArr['field']['write_subject'] 			= 	$PowerBB->_POST['write_subject'];
		$GroupArr['field']['write_reply'] 				= 	$PowerBB->_POST['write_reply'];
		$GroupArr['field']['upload_attach'] 			= 	$PowerBB->_POST['upload_attach'];
		$GroupArr['field']['upload_attach_num'] 		= 	$PowerBB->_POST['upload_attach_num'];
		$GroupArr['field']['edit_own_subject'] 			= 	$PowerBB->_POST['edit_own_subject'];
		$GroupArr['field']['edit_own_reply'] 			= 	$PowerBB->_POST['edit_own_reply'];
		$GroupArr['field']['del_own_subject'] 			= 	$PowerBB->_POST['del_own_subject'];
		$GroupArr['field']['del_own_reply']			 	= 	$PowerBB->_POST['del_own_reply'];
		$GroupArr['field']['write_poll'] 				= 	$PowerBB->_POST['write_poll'];
		$GroupArr['field']['no_posts'] 		    		= 	'1';
		$GroupArr['field']['vote_poll'] 				= 	$PowerBB->_POST['vote_poll'];
		$GroupArr['field']['use_pm'] 					= 	$PowerBB->_POST['use_pm'];
		$GroupArr['field']['send_pm'] 					= 	$PowerBB->_POST['use_pm'];
		$GroupArr['field']['resive_pm'] 				= 	$PowerBB->_POST['use_pm'];
		$GroupArr['field']['max_pm'] 					= 	$PowerBB->_POST['max_pm'];
		$GroupArr['field']['min_send_pm'] 				= 	$PowerBB->_POST['min_send_pm'];
		$GroupArr['field']['sig_allow'] 				= 	$PowerBB->_POST['sig_allow'];
		$GroupArr['field']['sig_len'] 					= 	$PowerBB->_POST['sig_len'];
		$GroupArr['field']['group_mod'] 				= 	$PowerBB->_POST['group_mod'];
		$GroupArr['field']['del_subject'] 				= 	$PowerBB->_POST['del_subject'];
		$GroupArr['field']['del_reply'] 				= 	$PowerBB->_POST['del_reply'];
		$GroupArr['field']['edit_subject'] 				= 	$PowerBB->_POST['edit_subject'];
		$GroupArr['field']['edit_reply'] 				= 	$PowerBB->_POST['edit_reply'];
		$GroupArr['field']['stick_subject'] 			= 	$PowerBB->_POST['stick_subject'];
		$GroupArr['field']['unstick_subject'] 			= 	$PowerBB->_POST['unstick_subject'];
		$GroupArr['field']['move_subject'] 				= 	$PowerBB->_POST['move_subject'];
		$GroupArr['field']['close_subject'] 			= 	$PowerBB->_POST['close_subject'];
		$GroupArr['field']['usercp_allow'] 				= 	$PowerBB->_POST['usercp_allow'];
		$GroupArr['field']['admincp_allow'] 			= 	$PowerBB->_POST['admincp_allow'];
		$GroupArr['field']['groups_security'] 		    = 	$PowerBB->_POST['groups_security'];
		$GroupArr['field']['profile_photo'] 		    = 	$PowerBB->_POST['profile_photo'];
		$GroupArr['field']['search_allow'] 				= 	$PowerBB->_POST['search_allow'];
		$GroupArr['field']['memberlist_allow'] 			= 	$PowerBB->_POST['memberlist_allow'];
		$GroupArr['field']['vice'] 						= 	$PowerBB->_POST['vice'];
		$GroupArr['field']['show_hidden'] 				= 	$PowerBB->_POST['show_hidden'];
		$GroupArr['field']['hide_allow'] 				= 	$PowerBB->_POST['hide_allow'];
		$GroupArr['field']['view_usernamestyle'] 		= 	$PowerBB->_POST['view_usernamestyle'];
		$GroupArr['field']['usertitle_change'] 			= 	$PowerBB->_POST['usertitle_change'];
		$GroupArr['field']['onlinepage_allow'] 			= 	$PowerBB->_POST['onlinepage_allow'];
		$GroupArr['field']['allow_see_offstyles'] 		= 	$PowerBB->_POST['allow_see_offstyles'];
		$GroupArr['field']['admincp_section'] 			= 	$PowerBB->_POST['admincp_section'];
		$GroupArr['field']['admincp_option'] 			= 	$PowerBB->_POST['admincp_option'];
		$GroupArr['field']['admincp_member'] 			= 	$PowerBB->_POST['admincp_member'];
		$GroupArr['field']['admincp_membergroup'] 		= 	$PowerBB->_POST['admincp_membergroup'];
		$GroupArr['field']['admincp_membertitle'] 		= 	$PowerBB->_POST['admincp_membertitle'];
		$GroupArr['field']['admincp_admin'] 			= 	$PowerBB->_POST['admincp_admin'];
		$GroupArr['field']['admincp_adminstep'] 		= 	$PowerBB->_POST['admincp_adminstep'];
		$GroupArr['field']['admincp_subject'] 			= 	$PowerBB->_POST['admincp_subject'];
		$GroupArr['field']['admincp_database'] 			= 	$PowerBB->_POST['admincp_database'];
		$GroupArr['field']['admincp_fixup'] 			= 	$PowerBB->_POST['admincp_fixup'];
		$GroupArr['field']['admincp_ads'] 				= 	$PowerBB->_POST['admincp_ads'];
		$GroupArr['field']['admincp_template'] 			= 	$PowerBB->_POST['admincp_template'];
		$GroupArr['field']['admincp_adminads'] 			= 	$PowerBB->_POST['admincp_adminads'];
		$GroupArr['field']['admincp_attach'] 			= 	$PowerBB->_POST['admincp_attach'];
		$GroupArr['field']['admincp_page'] 				= 	$PowerBB->_POST['admincp_page'];
		$GroupArr['field']['admincp_block'] 			= 	$PowerBB->_POST['admincp_block'];
		$GroupArr['field']['admincp_style'] 			= 	$PowerBB->_POST['admincp_style'];
		$GroupArr['field']['admincp_toolbox'] 			= 	$PowerBB->_POST['admincp_toolbox'];
		$GroupArr['field']['admincp_smile'] 			= 	$PowerBB->_POST['admincp_smile'];
		$GroupArr['field']['admincp_icon'] 				= 	$PowerBB->_POST['admincp_icon'];
		$GroupArr['field']['admincp_avater'] 			= 	$PowerBB->_POST['admincp_avater'];
		$GroupArr['field']['admincp_contactus'] 		= 	$PowerBB->_POST['admincp_contactus'];
		$GroupArr['field']['admincp_chat'] 			    = 	$PowerBB->_POST['admincp_chat'];
		$GroupArr['field']['admincp_extrafield'] 	    = 	$PowerBB->_POST['admincp_extrafield'];
		$GroupArr['field']['admincp_lang'] 			    = 	$PowerBB->_POST['admincp_lang'];
		$GroupArr['field']['admincp_emailed'] 			= 	$PowerBB->_POST['admincp_emailed'];
		$GroupArr['field']['admincp_warn'] 			    = 	$PowerBB->_POST['admincp_warn'];
		$GroupArr['field']['admincp_award'] 			= 	$PowerBB->_POST['admincp_award'];
		$GroupArr['field']['admincp_multi_moderation']  = 	$PowerBB->_POST['admincp_multi_moderation'];
		$GroupArr['field']['group_order'] 				= 	$group_order;
		$GroupArr['field']['send_warning'] 		        = 	$PowerBB->_POST['send_warning'];
		$GroupArr['field']['can_warned'] 		        = 	$PowerBB->_POST['can_warned'];
		$GroupArr['field']['visitormessage'] 		    = 	$PowerBB->_POST['visitormessage'];
        $GroupArr['field']['see_who_on_topic']          =    $PowerBB->_POST['see_who_on_topic'];
        $GroupArr['field']['topic_day_number']          =    $PowerBB->_POST['topic_day_number'];
        $GroupArr['field']['reputation_number']         =    $PowerBB->_POST['reputation_number'];
		$GroupArr['field']['review_subject'] 		    = 	$PowerBB->_POST['review_subject'];
		$GroupArr['field']['review_reply'] 		        = 	$PowerBB->_POST['review_reply'];
		$GroupArr['field']['view_action_edit'] 		    = 	$PowerBB->_POST['view_action_edit'];

		$GroupArr['get_id']								=	true;

		$insert = $PowerBB->group->InsertGroup($GroupArr);

		if ($insert)
		{
			//////////

			$GroupId = $PowerBB->_POST['usergroup'];

			$SecArr 						= 	array();
			$SecArr['order'] 				= 	array();
			$SecArr['order']['field'] 		= 	'id';
			$SecArr['order']['type'] 		= 	'ASC';

			$sections = $PowerBB->core->GetList($SecArr,'section');

			//////////

			$x = 0;
			$n = sizeof($sections);

			while ($x < $n)
			{

				  $query_section_group = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['section_group'] . " WHERE group_id=".$GroupId." and section_id =".$sections[$x]['id']."");
				  $InfoGroup = $PowerBB->DB->sql_fetch_array($query_section_group);

                   	$UpdateGrpArr 					               = 	array();
					$UpdateGrpArr['field']			                =	array();
				    $UpdateGrpArr['field']['section_id'] 			= 	$sections[$x]['id'];
					$UpdateGrpArr['field']['group_id'] 			    = 	$PowerBB->group->id;
					$UpdateGrpArr['field']['view_section'] 		    = 	$InfoGroup['view_section'];
			        $UpdateGrpArr['field']['view_subject'] 		    = 	$InfoGroup['view_subject'];
					$UpdateGrpArr['field']['download_attach'] 	    = 	$InfoGroup['download_attach'];
					$UpdateGrpArr['field']['write_subject'] 		= 	$InfoGroup['write_subject'];
					$UpdateGrpArr['field']['write_reply'] 		    = 	$InfoGroup['write_reply'];
					$UpdateGrpArr['field']['upload_attach'] 		= 	$InfoGroup['upload_attach'];
					$UpdateGrpArr['field']['edit_own_subject'] 	    = 	$InfoGroup['edit_own_subject'];
					$UpdateGrpArr['field']['edit_own_reply'] 		= 	$InfoGroup['edit_own_reply'];
					$UpdateGrpArr['field']['del_own_subject'] 	    = 	$InfoGroup['del_own_subject'];
					$UpdateGrpArr['field']['del_own_reply'] 		= 	$InfoGroup['del_own_reply'];
					$UpdateGrpArr['field']['write_poll'] 			= 	$InfoGroup['write_poll'];
				    $UpdateGrpArr['field']['no_posts'] 		    	= 	$InfoGroup['no_posts'];
					$UpdateGrpArr['field']['vote_poll'] 		 	= 	$InfoGroup['vote_poll'];
				    $UpdateGrpArr['field']['main_section'] 		    = 	($sections[$x]['parent'] == 0) ? 1 : 0;
				    $UpdateGrpArr['field']['group_name']  			= 	$PowerBB->_POST['name'];

					$Update = $PowerBB->group->InsertSectionGroup($UpdateGrpArr);


				$x += 1;
			}


            $CacheArr 			= 	array();
			$CacheArr['id'] 	= 	$PowerBB->group->id;

			$cache = $PowerBB->group->UpdateGroupCache($CacheArr);

				$PowerBB->functions->msg("يرجى الإنتظار سيتم ادخال صلاحية المجموعة ".$PowerBB->_POST['name']." لجميع الأقسام والمنتديات");
				$PowerBB->functions->redirect('index.php?page=groups&amp;update_group_meter=1&amp;group_cache=1');
		}
	}

	function _ControlMain()
	{
		global $PowerBB;

		//////////
      $groups = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['group'] . " WHERE id");

             $PowerBB->template->display('groups_main_top');

       while ($GetGroupRow = $PowerBB->DB->sql_fetch_array($groups))
      {
      	$group_id = $GetGroupRow['id'];
        $group_member_nm = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['member'] . " WHERE usergroup='$group_id'"));

       $GetGroupRow['username_style'] = str_ireplace('[username]',$GetGroupRow['title'],$GetGroupRow['username_style']);
       $PowerBB->template->assign('groups_title',$GetGroupRow['username_style']);
       $PowerBB->template->assign('groups_id',$GetGroupRow['id']);

      	$PowerBB->template->assign('group_member_nm',$group_member_nm);
       $PowerBB->template->display('groups_main');

      }
      $PowerBB->template->display('groups_main_down');
		//////////

	}

	function _ShwoMemberGroup()
	{
		global $PowerBB;

		$PowerBB->_GET['count'] = (!isset($PowerBB->_GET['count'])) ? 0 : $PowerBB->_GET['count'];
		$PowerBB->_GET['count'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['count'],'intval');
        $PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');
        $Groupid = $PowerBB->_GET['id'];
        $MemberNumArr = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['member'] . " WHERE usergroup='$Groupid'"));


		$MemArr 					= 	array();
		$MemArr['order']			=	array();
		$MemArr['order']['field']	=	'id';
		$MemArr['order']['type']	=	'DESC';
		$MemArr['proc'] 			= 	array();
		$MemArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');

		$MemArr['where']					=	array();
		$MemArr['where'][0]				=	array();
		$MemArr['where'][0]['name']		=	'usergroup';
		$MemArr['where'][0]['oper']		=	'=';
		$MemArr['where'][0]['value']   =	$PowerBB->_GET['id'];

		$MemArr['pager'] 				= 	array();
		$MemArr['pager']['total']		= 	$MemberNumArr;
		$MemArr['pager']['perpage'] 	= 	$PowerBB->_CONF['info_row']['subject_perpage'];
		$MemArr['pager']['count'] 		= 	$PowerBB->_GET['count'];
		$MemArr['pager']['location'] 	= 	'index.php?page=groups&amp;shwo=1&amp;main=1&amp;id='.$PowerBB->_GET['id'];
		$MemArr['pager']['var'] 		= 	'count';

		$PowerBB->_CONF['template']['while']['MembersList'] = $PowerBB->core->GetList($MemArr,'member');
        if ($MemberNumArr > $PowerBB->_CONF['info_row']['subject_perpage'])
        {
		$PowerBB->template->assign('pager',$PowerBB->pager->show());
        }
        $PowerBB->template->assign('MemberNumber',$MemberNumArr);
		$PowerBB->template->display('members_main');

	}

	function _EditMain()
	{
		global $PowerBB;

		$PowerBB->_CONF['template']['Inf'] = false;

		$this->check_by_id($PowerBB->_CONF['template']['Inf']);

		if ($PowerBB->_GET['id'] == '1')
		{
			if (!in_array($PowerBB->_CONF['rows']['member_row']['id'], preg_split('#\s*,\s*#s', $PowerBB->superadministrators, -1, PREG_SPLIT_NO_EMPTY)))
			{
			 $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
			}

		}

		$GroupArr 			= 	array();
		$GroupArr['where'] 	= 	array('id',$PowerBB->_GET['id']);

		$PowerBB->_CONF['template']['group_inf'] = $PowerBB->group->GetGroupInfo($GroupArr);

		// Get groups list
		$GroupArr 							= 	array();
		$GroupArr['order']					=	array();
		$GroupArr['order']['field']			=	'id';
		$GroupArr['order']['type']			=	'ASC';

		$GroupArr['proc'] 					= 	array();
		$GroupArr['proc']['*'] 				= 	array('method'=>'clean','param'=>'html');

		// Store information in "GroupList"
		$PowerBB->_CONF['template']['while']['GroupList'] = $PowerBB->core->GetList($GroupArr,'group');

		$PowerBB->template->display('group_edit');
	}

	function _EditStart()
	{
		global $PowerBB;


		$GrpArr 			= 	array();
		$GrpArr['where'] 	= 	array('id',$PowerBB->_GET['id']);

		$GroupInfo = $PowerBB->core->GetInfo($GrpArr,'group');


		if (empty($PowerBB->_POST['name']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['please_enter_the_text']." (<b>".$PowerBB->_CONF['template']['_CONF']['lang']['Group_name']."</b>)");
		}

		if (empty($PowerBB->_POST['style']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['please_enter_the_text']." (<b>".$PowerBB->_CONF['template']['_CONF']['lang']['username_color']."</b>)");
		}

		if (empty($PowerBB->_POST['usertitle']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['please_enter_the_text']." (<b>".$PowerBB->_CONF['template']['_CONF']['lang']['usertitle']."</b>)");
		}


		// Enable HTML and (only) HTML
		$PowerBB->_POST['style'] = $PowerBB->functions->CleanVariable($PowerBB->_POST['style'],'unhtml');



		$GroupArr 			= 	array();
		$GroupArr['field']	=	array();

		$GroupArr['field']['title'] 					= 	$PowerBB->_POST['name'];
		$GroupArr['field']['username_style'] 			= 	$PowerBB->_POST['style'];
		$GroupArr['field']['user_title'] 				= 	$PowerBB->_POST['usertitle'];
		$GroupArr['field']['forum_team'] 				= 	$PowerBB->_POST['forum_team'];
		$GroupArr['field']['banned'] 					= 	$PowerBB->_POST['banned'];
		$GroupArr['field']['view_section'] 				= 	$PowerBB->_POST['view_section'];
		$GroupArr['field']['view_subject'] 		        = 	$PowerBB->_POST['view_subject'];
		$GroupArr['field']['download_attach'] 			= 	$PowerBB->_POST['download_attach'];
		$GroupArr['field']['download_attach_number'] 	= 	$PowerBB->_POST['download_attach_number'];
		$GroupArr['field']['write_subject'] 			= 	$PowerBB->_POST['write_subject'];
		$GroupArr['field']['write_reply'] 				= 	$PowerBB->_POST['write_reply'];
		$GroupArr['field']['upload_attach'] 			= 	$PowerBB->_POST['upload_attach'];
		$GroupArr['field']['upload_attach_num'] 		= 	$PowerBB->_POST['upload_attach_num'];
		$GroupArr['field']['edit_own_subject'] 			= 	$PowerBB->_POST['edit_own_subject'];
		$GroupArr['field']['edit_own_reply'] 			= 	$PowerBB->_POST['edit_own_reply'];
		$GroupArr['field']['del_own_subject'] 			= 	$PowerBB->_POST['del_own_subject'];
		$GroupArr['field']['del_own_reply']			 	= 	$PowerBB->_POST['del_own_reply'];
		$GroupArr['field']['write_poll'] 				= 	$PowerBB->_POST['write_poll'];
		$GroupArr['field']['vote_poll'] 				= 	$PowerBB->_POST['vote_poll'];
		$GroupArr['field']['use_pm'] 					= 	$PowerBB->_POST['use_pm'];
		$GroupArr['field']['send_pm'] 					= 	$PowerBB->_POST['send_pm'];
		$GroupArr['field']['resive_pm'] 				= 	$PowerBB->_POST['resive_pm'];
		$GroupArr['field']['max_pm'] 					= 	$PowerBB->_POST['max_pm'];
		$GroupArr['field']['min_send_pm'] 				= 	$PowerBB->_POST['min_send_pm'];
		$GroupArr['field']['sig_allow'] 				= 	$PowerBB->_POST['sig_allow'];
		$GroupArr['field']['sig_len'] 					= 	$PowerBB->_POST['sig_len'];
		$GroupArr['field']['group_mod'] 				= 	$PowerBB->_POST['group_mod'];
		$GroupArr['field']['del_subject'] 				= 	$PowerBB->_POST['del_subject'];
		$GroupArr['field']['del_reply'] 				= 	$PowerBB->_POST['del_reply'];
		$GroupArr['field']['edit_subject'] 				= 	$PowerBB->_POST['edit_subject'];
		$GroupArr['field']['edit_reply'] 				= 	$PowerBB->_POST['edit_reply'];
		$GroupArr['field']['stick_subject'] 			= 	$PowerBB->_POST['stick_subject'];
		$GroupArr['field']['unstick_subject'] 			= 	$PowerBB->_POST['unstick_subject'];
		$GroupArr['field']['move_subject'] 				= 	$PowerBB->_POST['move_subject'];
		$GroupArr['field']['close_subject'] 			= 	$PowerBB->_POST['close_subject'];
		$GroupArr['field']['usercp_allow'] 				= 	$PowerBB->_POST['usercp_allow'];
		$GroupArr['field']['admincp_allow'] 			= 	$PowerBB->_POST['admincp_allow'];
		$GroupArr['field']['groups_security'] 			= 	$PowerBB->_POST['groups_security'];
		$GroupArr['field']['profile_photo'] 		    = 	$PowerBB->_POST['profile_photo'];
		$GroupArr['field']['search_allow'] 				= 	$PowerBB->_POST['search_allow'];
		$GroupArr['field']['memberlist_allow'] 			= 	$PowerBB->_POST['memberlist_allow'];
		$GroupArr['field']['vice'] 						= 	$PowerBB->_POST['vice'];
		$GroupArr['field']['show_hidden'] 				= 	$PowerBB->_POST['show_hidden'];
		$GroupArr['field']['hide_allow'] 				= 	$PowerBB->_POST['hide_allow'];
		$GroupArr['field']['view_usernamestyle'] 		= 	$PowerBB->_POST['view_usernamestyle'];
		$GroupArr['field']['usertitle_change'] 			= 	$PowerBB->_POST['usertitle_change'];
		$GroupArr['field']['onlinepage_allow'] 			= 	$PowerBB->_POST['onlinepage_allow'];
		$GroupArr['field']['allow_see_offstyles'] 		= 	$PowerBB->_POST['allow_see_offstyles'];
		$GroupArr['field']['admincp_section'] 			= 	$PowerBB->_POST['admincp_section'];
		$GroupArr['field']['admincp_option'] 			= 	$PowerBB->_POST['admincp_option'];
		$GroupArr['field']['admincp_member'] 			= 	$PowerBB->_POST['admincp_member'];
		$GroupArr['field']['admincp_membergroup'] 		= 	$PowerBB->_POST['admincp_membergroup'];
		$GroupArr['field']['admincp_membertitle'] 		= 	$PowerBB->_POST['admincp_membertitle'];
		$GroupArr['field']['admincp_admin'] 			= 	$PowerBB->_POST['admincp_admin'];
		$GroupArr['field']['admincp_adminstep'] 		= 	$PowerBB->_POST['admincp_adminstep'];
		$GroupArr['field']['admincp_subject'] 			= 	$PowerBB->_POST['admincp_subject'];
		$GroupArr['field']['admincp_database'] 			= 	$PowerBB->_POST['admincp_database'];
		$GroupArr['field']['admincp_fixup'] 			= 	$PowerBB->_POST['admincp_fixup'];
		$GroupArr['field']['admincp_ads'] 				= 	$PowerBB->_POST['admincp_ads'];
		$GroupArr['field']['admincp_template'] 			= 	$PowerBB->_POST['admincp_template'];
		$GroupArr['field']['admincp_adminads'] 			= 	$PowerBB->_POST['admincp_adminads'];
		$GroupArr['field']['admincp_attach'] 			= 	$PowerBB->_POST['admincp_attach'];
		$GroupArr['field']['admincp_page'] 				= 	$PowerBB->_POST['admincp_page'];
		$GroupArr['field']['admincp_block'] 			= 	$PowerBB->_POST['admincp_block'];
		$GroupArr['field']['admincp_style'] 			= 	$PowerBB->_POST['admincp_style'];
		$GroupArr['field']['admincp_toolbox'] 			= 	$PowerBB->_POST['admincp_toolbox'];
		$GroupArr['field']['admincp_smile'] 			= 	$PowerBB->_POST['admincp_smile'];
		$GroupArr['field']['admincp_icon'] 				= 	$PowerBB->_POST['admincp_icon'];
		$GroupArr['field']['admincp_avater'] 			= 	$PowerBB->_POST['admincp_avater'];
		$GroupArr['field']['group_order'] 				= 	$PowerBB->_POST['group_order'];
		$GroupArr['field']['admincp_contactus'] 		= 	$PowerBB->_POST['admincp_contactus'];
		$GroupArr['field']['admincp_chat'] 			    = 	$PowerBB->_POST['admincp_chat'];
		$GroupArr['field']['admincp_extrafield'] 	    = 	$PowerBB->_POST['admincp_extrafield'];
		$GroupArr['field']['admincp_lang'] 			    = 	$PowerBB->_POST['admincp_lang'];
		$GroupArr['field']['admincp_emailed'] 			= 	$PowerBB->_POST['admincp_emailed'];
		$GroupArr['field']['admincp_warn'] 			    = 	$PowerBB->_POST['admincp_warn'];
		$GroupArr['field']['admincp_award'] 			= 	$PowerBB->_POST['admincp_award'];
		$GroupArr['field']['admincp_multi_moderation']  = 	$PowerBB->_POST['admincp_multi_moderation'];
		$GroupArr['field']['no_posts'] 		    		= 	$PowerBB->_POST['no_posts'];
		$GroupArr['field']['send_warning'] 	           	= 	$PowerBB->_POST['send_warning'];
		$GroupArr['field']['can_warned'] 		        = 	$PowerBB->_POST['can_warned'];
		$GroupArr['field']['visitormessage'] 		    = 	$PowerBB->_POST['visitormessage'];
        $GroupArr['field']['see_who_on_topic']          =   $PowerBB->_POST['see_who_on_topic'];
        $GroupArr['field']['topic_day_number']          =   $PowerBB->_POST['topic_day_number'];
        $GroupArr['field']['reputation_number']         =   $PowerBB->_POST['reputation_number'];
		$GroupArr['field']['review_subject'] 		    = 	$PowerBB->_POST['review_subject'];
		$GroupArr['field']['review_reply'] 		        = 	$PowerBB->_POST['review_reply'];
		$GroupArr['field']['view_action_edit'] 		    = 	$PowerBB->_POST['view_action_edit'];

		$GroupArr['where']								=	array('id',$GroupInfo['id']);

		$update = $PowerBB->group->UpdateGroup($GroupArr);
        $GroupId = $PowerBB->_POST['usergroup'];

          if($PowerBB->_GET['id'] != $GroupId)
          {

			$SecArr 						= 	array();
			$SecArr['order'] 				= 	array();
			$SecArr['order']['field'] 		= 	'id';
			$SecArr['order']['type'] 		= 	'ASC';

			$sections = $PowerBB->core->GetList($SecArr,'section');

			$del = $PowerBB->group->DeleteSectionGroup(array('where'=>array('group_id',$PowerBB->_GET['id'])));

         	$x = 0;
			$n = sizeof($sections);
			while ($x < $n)
			{

				  $query_section_group = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['section_group'] . " WHERE group_id=".$GroupId." and section_id =".$sections[$x]['id']."");
				  $InfoGroup = $PowerBB->DB->sql_fetch_array($query_section_group);

                   	$UpdateGrpArr 					               = 	array();
					$UpdateGrpArr['field']			                =	array();
				    $UpdateGrpArr['field']['section_id'] 			= 	$sections[$x]['id'];
					$UpdateGrpArr['field']['group_id'] 			    = 	$PowerBB->_GET['id'];
					$UpdateGrpArr['field']['view_section'] 		    = 	$InfoGroup['view_section'];
			        $UpdateGrpArr['field']['view_subject'] 		    = 	$InfoGroup['view_subject'];
					$UpdateGrpArr['field']['download_attach'] 	    = 	$InfoGroup['download_attach'];
					$UpdateGrpArr['field']['write_subject'] 		= 	$InfoGroup['write_subject'];
					$UpdateGrpArr['field']['write_reply'] 		    = 	$InfoGroup['write_reply'];
					$UpdateGrpArr['field']['upload_attach'] 		= 	$InfoGroup['upload_attach'];
					$UpdateGrpArr['field']['edit_own_subject'] 	    = 	$InfoGroup['edit_own_subject'];
					$UpdateGrpArr['field']['edit_own_reply'] 		= 	$InfoGroup['edit_own_reply'];
					$UpdateGrpArr['field']['del_own_subject'] 	    = 	$InfoGroup['del_own_subject'];
					$UpdateGrpArr['field']['del_own_reply'] 		= 	$InfoGroup['del_own_reply'];
					$UpdateGrpArr['field']['write_poll'] 			= 	$InfoGroup['write_poll'];
				    $UpdateGrpArr['field']['no_posts'] 		    	= 	$InfoGroup['no_posts'];
					$UpdateGrpArr['field']['vote_poll'] 		 	= 	$InfoGroup['vote_poll'];
				    $UpdateGrpArr['field']['main_section'] 		    = 	($sections[$x]['parent'] == 0) ? 1 : 0;
				    $UpdateGrpArr['field']['group_name']  			= 	$PowerBB->_POST['name'];

					$Update = $PowerBB->group->InsertSectionGroup($UpdateGrpArr);

				$x += 1;
			}

           }

			if ($PowerBB->_POST['hide_allow'] == '0')
			{
				$UpdateMemberallowArr 						= 	array();
				$UpdateMemberallowArr['field'] 				= 	array();
				$UpdateMemberallowArr['field']['hide_online'] 		= 	'0';
				$UpdateMemberallowArr['where'] 				= 	array('usergroup',$GroupInfo['id']);

				$u_allowmember = $PowerBB->member->UpdateMember($UpdateMemberallowArr);
			}

			if ($GroupInfo['username_style'] != $PowerBB->_POST['style'])
			{
					$group_id = $GroupInfo['id'];
                    $usern_style = $PowerBB->_POST['style'];

			      	// UPDATE username_style_cache member
			        $member_query = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['member'] . " WHERE usergroup='$group_id'");

			       while ($GetMemberRow = $PowerBB->DB->sql_fetch_array($member_query))
			      {
					$username_style_cache = str_replace('[username]',$GetMemberRow['username'],$usern_style);
					$update = $PowerBB->DB->sql_query('UPDATE ' . $PowerBB->table['member'] . " SET username_style_cache='" . $username_style_cache . "' WHERE id='" . $GetMemberRow['id'] . "'");
                    // UPDATE username_style today
				    $update_username_style_today = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['today'] . " SET username_style='" . $username_style_cache . "' WHERE user_id='" . $GetMemberRow['id'] . "'");
                    // UPDATE username_style online
				    $update_username_style_online = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['online'] . " SET username_style='" . $username_style_cache . "' WHERE user_id='" . $GetMemberRow['id'] . "'");
			      }

			}

            $CacheArr 			= 	array();
			$CacheArr['id'] 	= 	$GroupInfo['id'];

			$cache = $PowerBB->group->UpdateGroupCache($CacheArr);

				$PowerBB->functions->msg("يرجى الإنتظار سيتم ادخال صلاحية المجموعة ".$PowerBB->_POST['name']." لجميع الأقسام والمنتديات");
				$PowerBB->functions->redirect('index.php?page=groups&amp;update_group_meter=1&amp;group_cache=1');

	}

	function _DelMain()
	{
		global $PowerBB;

		$PowerBB->_CONF['template']['Inf'] = false;
		if (!in_array($PowerBB->_CONF['rows']['member_row']['id'], preg_split('#\s*,\s*#s', $PowerBB->superadministrators, -1, PREG_SPLIT_NO_EMPTY)))
		{
		 $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
		}
		if ($PowerBB->_GET['id'] == '1'
			or $PowerBB->_GET['id'] == '2'
			or $PowerBB->_GET['id'] == '3'
			or $PowerBB->_GET['id'] == '4'
			or $PowerBB->_GET['id'] == '5'
			or $PowerBB->_GET['id'] == '6'
			or $PowerBB->_GET['id'] == '7'
			or $PowerBB->_GET['id'] == '8')
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['This_core_group_of_Can_you_delete']);
		}

		$this->check_by_id($PowerBB->_CONF['template']['Inf']);

		$PowerBB->template->display('group_del');
	}

	function _DelStart()
	{
		global $PowerBB;
		if (!in_array($PowerBB->_CONF['rows']['member_row']['id'], preg_split('#\s*,\s*#s', $PowerBB->superadministrators, -1, PREG_SPLIT_NO_EMPTY)))
		{
		 $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
		}
         $id_group = $PowerBB->_GET['id'];
        $member_Group_nm = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(id) FROM " . $PowerBB->table['member'] . " WHERE usergroup='$id_group'"));

 		if ($member_Group_nm > 0)
		{
			$GrpArr 			= 	array();
			$GrpArr['where'] 	= 	array('id',4);

			$GroupInfo = $PowerBB->core->GetInfo($GrpArr,'group');


	       $GET_member_groups = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['member'] . " WHERE usergroup='$id_group'");

	       while ($move_member = $PowerBB->DB->sql_fetch_array($GET_member_groups))
	      {

			// Move all Member to Member Group nm '4'
			$UpdateArr 				= 	array();
			$UpdateArr['field'] 	= 	array();
			$UpdateArr['field']['usergroup'] 			= 	'4';
			$UpdateArr['field']['user_title'] 			= 	$GroupInfo['user_title'];
	        $UpdateArr['where'] 		    	= 	array('id',$move_member['id']);

			$update = $PowerBB->core->Update($UpdateArr,'member');
	      }
	 		if ($update)
			{
					$del = $PowerBB->group->DeleteGroup(array('where'=>array('id',$PowerBB->_GET['id'])));

				 if ($del)
				{

					$del = $PowerBB->group->DeleteSectionGroup(array('where'=>array('group_id',$PowerBB->_GET['id'])));


					//$cache = $PowerBB->section->UpdateAllSectionsCache();

					$CacheArr 			= 	array();
					$CacheArr['id'] 	= 	$GroupInfo['id'];

					$cache = $PowerBB->group->UpdateGroupCache($CacheArr);

					$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['group_has_been_deleted_successfully']);
					$PowerBB->functions->redirect('index.php?page=groups&amp;control=1&amp;main=1');

				}

	    	}

		}
      	else
		{
				$del = $PowerBB->group->DeleteGroup(array('where'=>array('id',$PowerBB->_GET['id'])));

				 if ($del)
				{

					$del = $PowerBB->group->DeleteSectionGroup(array('where'=>array('group_id',$PowerBB->_GET['id'])));


					$cache = $PowerBB->section->UpdateAllSectionsCache();

					$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['group_has_been_deleted_successfully']);
					$PowerBB->functions->redirect('index.php?page=groups&amp;control=1&amp;main=1');

				}

		}



	}

	function _MoveMain()
	{
		global $PowerBB;
		if (!in_array($PowerBB->_CONF['rows']['member_row']['id'], preg_split('#\s*,\s*#s', $PowerBB->superadministrators, -1, PREG_SPLIT_NO_EMPTY)))
		{
		 $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
		}

		$PowerBB->_CONF['template']['Inf'] = false;

		$this->check_by_id($PowerBB->_CONF['template']['Inf']);

		// Get groups list
		$GroupArr 							= 	array();

		$GroupArr['order']					=	array();
		$GroupArr['order']['field']			=	'id';
		$GroupArr['order']['type']			=	'DESC';

		$GroupArr = array();
		$GroupArr['where'] 				= 	array();
		$GroupArr['where'][0] 			= 	array();
		$GroupArr['where'][0]['name'] 	= 	'id not in (7) AND banned';
		$GroupArr['where'][0]['oper'] 	= 	'<>';
		$GroupArr['where'][0]['value'] 	= 	6;

		$GroupArr['proc'] 					= 	array();
		$GroupArr['proc']['*'] 				= 	array('method'=>'clean','param'=>'html');

		// Store information in "GroupList"
		$PowerBB->_CONF['template']['while']['GroupList'] = $PowerBB->core->GetList($GroupArr,'group');

       $PowerBB->template->assign('group_id',$PowerBB->_GET['id']);
		$PowerBB->template->display('group_move');
	}

	function _MoveStart()
	{
		global $PowerBB;
		if (!in_array($PowerBB->_CONF['rows']['member_row']['id'], preg_split('#\s*,\s*#s', $PowerBB->superadministrators, -1, PREG_SPLIT_NO_EMPTY)))
		{
		 $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
		}

		$PowerBB->_CONF['template']['Inf'] = false;

		$this->check_by_id($PowerBB->_CONF['template']['Inf']);

		$GrpArr 			= 	array();
		$GrpArr['where'] 	= 	array('id',$PowerBB->_GET['id']);

		$GroupInfo = $PowerBB->core->GetInfo($GrpArr,'group');

		$GrpMoveToArr 			= 	array();
		$GrpMoveToArr['where'] 	= 	array('id',$PowerBB->_POST['group']);

		$GroupMoveToInfo = $PowerBB->group->GetGroupInfo($GrpMoveToArr);

       $id_group = $PowerBB->_GET['id'];
       $GET_member_groups = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['member'] . " WHERE usergroup='$id_group'");

       while ($move_member = $PowerBB->DB->sql_fetch_array($GET_member_groups))
      {

       $GroupMoveToInfo['username_style'] = str_ireplace('[username]',$move_member['username_style_cache'],$GroupMoveToInfo['username_style']);

		// Move all Member to $PowerBB->_POST['group']
		$UpdateArr 				= 	array();
		$UpdateArr['field'] 	= 	array();
		$UpdateArr['field']['usergroup'] 			= 	$PowerBB->_POST['group'];
		$UpdateArr['field']['user_title'] 			= 	$GroupMoveToInfo['user_title'];
		$UpdateArr['field']['username_style_cache'] = 	$GroupMoveToInfo['username_style'];
        $UpdateArr['where'] 		    	= 	array('id',$move_member['id']);

		$update = $PowerBB->core->Update($UpdateArr,'member');
      }
 		if ($update)
		{

			$PowerBB->functions->Update_Cache_groups();

			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['All_members_were_transported_to_the_group'] .$GroupMoveToInfo['title']. $PowerBB->_CONF['template']['_CONF']['lang']['Successfully']);
			$PowerBB->functions->redirect('index.php?page=groups&amp;control=1&amp;main=1');


    	}


	}


	function _AllCacheGroupStart()
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

 		$forumArr = $PowerBB->DB->sql_query("SELECT id,title,parent FROM " . $PowerBB->table['section'] . " ORDER BY id DESC LIMIT ".$startpoint.",".$perpage." ");
		$forum_nm = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(id) FROM " . $PowerBB->table['section'] . ""));

        $pagesnum = round(ceil($forum_nm / $perpage));
        echo('<br><br><table border="1" width="80%" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" style="border-collapse: collapse" align="center"><tr><td><font face="Tahoma" size="2">');

		while ($forum_row = $PowerBB->DB->sql_fetch_array($forumArr))
		{
         $UpdateSectionCache = $PowerBB->section->UpdateSectionsCache(array('parent'=>$forum_row['parent']));

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
			echo('<a href="index.php?page=groups&amp;update_group_meter=1&amp;group_cache=1&amp;pag='.$n_page.'">'.$transition_click.'</a>');
			echo($PowerBB->_CONF['template']['_CONF']['lang']['Waiting_Time'].$seconds.$PowerBB->_CONF['template']['_CONF']['lang']['seconds']);
			echo('</font></td></tr></table>');

			$PowerBB->functions->redirect('index.php?page=groups&amp;update_group_meter=1&amp;group_cache=1&pag='.$n_page,$seconds);
			}
			else
			{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['updated_successfully']);

			$Update_Cache_groups = $PowerBB->functions->Update_Cache_groups();
			$permission = $PowerBB->functions->_MeterGroupsStart();
            $REPAIR_TABLE = $PowerBB->DB->sql_query("REPAIR TABLE " . $PowerBB->table['section'] . "");

            $CacheArr 			= 	array();
			$CacheArr['id'] 	= 	$PowerBB->group->id;

			$cache = $PowerBB->group->UpdateGroupCache($CacheArr);

			$PowerBB->functions->redirect('index.php?page=groups&amp;control=1&amp;main=1');
			}

		}
		else
		{
		echo('<br><table border="1" width="80%" cellspacing="0" cellpadding="0" bgcolor="#E5EBF0" style="border-collapse: collapse" align="center"><tr><td><font face="Tahoma" size="2">');
		echo($PowerBB->_CONF['template']['_CONF']['lang']['not_the_update']);
		echo('</font></td></tr></table>');
		}



	}

}

class _functions
{
	function check_by_id(&$GroupInfo)
	{
		global $PowerBB;

		if (empty($PowerBB->_GET['id']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['The_request_is_not_valid']);
		}

		$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');

        $CatArr = $PowerBB->DB->sql_query("SELECT  *   FROM " . $PowerBB->table['group'] . " WHERE id = ".$PowerBB->_GET['id']." ");
	    $GroupInfo = $PowerBB->DB->sql_fetch_array($CatArr);

		if ($GroupInfo == false)
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['group_requested_does_not_exist']);
		}

		$PowerBB->functions->CleanVariable($GroupInfo,'html');
	}
}

?>
