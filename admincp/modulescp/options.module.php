<?php

(!defined('IN_PowerBB')) ? die() : '';

define('IN_ADMIN',true);



define('CLASS_NAME','PowerBBOptionsMOD');

include('../common.php');
class PowerBBOptionsMOD
{
	function run()
	{
		global $PowerBB;

		if ($PowerBB->_CONF['member_permission'])
		{
			$PowerBB->template->display('header');

			if ($PowerBB->_CONF['rows']['group_info']['admincp_option'] == '0')
			{
			  $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
			}

			if ($PowerBB->_GET['index'])
			{
				$this->_IndexPage();
			}
			elseif ($PowerBB->_GET['general'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_GeneralMain();
				}
				elseif ($PowerBB->_GET['update'])
				{
					$this->_GeneralUpdate();
				}
			}
			elseif ($PowerBB->_GET['human_verification'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_HumanMain();
				}
				elseif ($PowerBB->_GET['update'])
				{
					$this->_HumanUpdate();
				}
			}
			elseif ($PowerBB->_GET['time'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_TimeMain();
				}
				elseif ($PowerBB->_GET['update'])
				{
					$this->_TimeUpdate();
				}
			}
			elseif ($PowerBB->_GET['email_msg'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_EmailMsgMain();
				}
				elseif ($PowerBB->_GET['update'])
				{
					$this->_EmailMsgUpdate();
				}
			}
			elseif ($PowerBB->_GET['pages'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_PagesMain();
				}
				elseif ($PowerBB->_GET['update'])
				{
					$this->_PagesUpdate();
				}
			}
			elseif ($PowerBB->_GET['register'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_RegisterMain();
				}
				elseif ($PowerBB->_GET['update'])
				{
					$this->_RegisterUpdate();
				}
			}
			elseif ($PowerBB->_GET['topics'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_TopicsMain();
				}
				elseif ($PowerBB->_GET['update'])
				{
					$this->_TopicsUpdate();
				}
			}
			elseif ($PowerBB->_GET['fast_reply'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_FastReplyMain();
				}
				elseif ($PowerBB->_GET['update'])
				{
					$this->_FastReplyUpdate();
				}
			}
			elseif ($PowerBB->_GET['member'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_MemberMain();
				}
				elseif ($PowerBB->_GET['update'])
				{
					$this->_MemberUpdate();
				}
			}
			elseif ($PowerBB->_GET['avatar'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_AvatarMain();
				}
				elseif ($PowerBB->_GET['update'])
				{
					$this->_AvatarUpdate();
				}
			}
			elseif ($PowerBB->_GET['close_days'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_CloseDaysMain();
				}
				elseif ($PowerBB->_GET['update'])
				{
					$this->_CloseDaysUpdate();
				}
			}
			elseif ($PowerBB->_GET['close'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_CloseMain();
				}
				elseif ($PowerBB->_GET['update'])
				{
					$this->_CloseUpdate();
				}
			}
			elseif ($PowerBB->_GET['sidebar_list'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_sidebar_list_Main();
				}
				elseif ($PowerBB->_GET['update'])
				{
					$this->_sidebar_list_Update();
				}
			}
			elseif ($PowerBB->_GET['mailer'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_MailerMain();
				}
				elseif ($PowerBB->_GET['update'])
				{
					$this->_MailerUpdate();
				}
			}
			elseif ($PowerBB->_GET['ajax'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_AjaxMain();
				}
				elseif ($PowerBB->_GET['update'])
				{
					$this->_AjaxUpdate();
				}
			}
			elseif ($PowerBB->_GET['warning'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_WarningMain();
				}
				elseif ($PowerBB->_GET['update'])
				{
					$this->_WarningUpdate();
				}
			}
			elseif ($PowerBB->_GET['reputation'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_ReputationMain();
				}
				elseif ($PowerBB->_GET['update'])
				{
					$this->_ReputationUpdate();
				}
			}
			elseif ($PowerBB->_GET['mods'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_ModsMain();
				}
				elseif ($PowerBB->_GET['update'])
				{
					$this->_ModsUpdate();
				}
			}
			elseif ($PowerBB->_GET['pbb_seo'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_PbbSeoMain();
				}
				elseif ($PowerBB->_GET['update'])
				{
					$this->_PbbSeoUpdate();
				}
				elseif ($PowerBB->_GET['update_sitemap'])
				{
					$this->_PbbSitemap();
				}
			}
			elseif ($PowerBB->_GET['allgeneral'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_AllGeneral();
				}
				elseif ($PowerBB->_GET['update'])
				{
					$this->_AllGeneralUpdate();
				}
			}
            elseif ($PowerBB->_GET['mention'])
			{
				if ($PowerBB->_GET['mainmention'])
				{
					$this->_MainMention();
				}
               elseif ($PowerBB->_GET['mention_update'])
				{
					$this->_UpdateMention();
				}
			}
			elseif ($PowerBB->_GET['hooks'])
			{
				if ($PowerBB->_GET['mainhooks'])
				{
					$this->_MainHooks();
				}
               elseif ($PowerBB->_GET['update'])
				{
					$this->_UpdateHooks();
				}
			}

			$PowerBB->template->display('footer');
		}
	}

	function _IndexPage()
	{
		global $PowerBB;

		$PowerBB->template->display('options_main');
	}

	function _GeneralMain()
	{
		global $PowerBB;

		$PowerBB->template->display('options_general');
	}

	function _MainHooks()
	{
		global $PowerBB;

        $code_parse = $PowerBB->functions->get_hooks("mainInfoHooks");
	}

	function _UpdateHooks()
	{
		global $PowerBB;

        $code_parse = $PowerBB->functions->get_hooks("updateInfoHooks");
	}

   	function _MainMention()
	{
		global $PowerBB;

        $PowerBB->template->display('mention_mange');
	}

	function _UpdateMention()
	{
		global $PowerBB;

 		$update = array();
		$update[0] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['mention_active'],'var_name'=>'mention_active'));
		$update[1] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['mention_exforum'],'var_name'=>'mention_exforum'));
		$update[2] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['mention_exusergroups'],'var_name'=>'mention_exusergroups'));
		$update[3] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['mention_exusers'],'var_name'=>'mention_exusers'));

		if ($update[0] and $update[1] and $update[2] and $update[3])
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['updated_successfully_Please_wait']);
			$PowerBB->functions->redirect('index.php?page=options&amp;mention=1&amp;mainmention=1&amp;mention_main=1');
		}

	}

	function _GeneralUpdate()
	{
		global $PowerBB;

		$update = array();
		$update[0] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['title'],'var_name'=>'title'));
		$update[1] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['pm_feature'],'var_name'=>'pm_feature'));
		$update[2] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['no_describe'],'var_name'=>'no_describe'));
		$update[3] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['no_moderators'],'var_name'=>'no_moderators'));
	    $update[4] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['no_sub'],'var_name'=>'no_sub'));
	    $update[5] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['members_send_pm'],'var_name'=>'members_send_pm'));
	    $update[6] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['keywords'],'var_name'=>'keywords'));
	    $update[7] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['charset'],'var_name'=>'charset'));
	    $update[8] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['description'],'var_name'=>'description'));
	    $update[9] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['content_language'],'var_name'=>'content_language'));
	    $update[10] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['content_dir'],'var_name'=>'content_dir'));
	    $update[11] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['rules'],'var_name'=>'rules'));
	    $update[12] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['censorwords'],'var_name'=>'censorwords'));
	    $update[13] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['flood_search'],'var_name'=>'flood_search'));
	    $update[14] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['characters_keyword_search'],'var_name'=>'characters_keyword_search'));
	    $update[15] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['allowed_powered'],'var_name'=>'allowed_powered'));
	    $update[16] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['visitor_message_chars'],'var_name'=>'visitor_message_chars'));
	    $update[17] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['sub_columns_number'],'var_name'=>'sub_columns_number'));
	    $update[18] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['active_forum_online_number'],'var_name'=>'active_forum_online_number'));
	    $update[21] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['active_visitor_message'],'var_name'=>'active_visitor_message'));
	    $update[22] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['active_friend'],'var_name'=>'active_friend'));
	    $update[23] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['active_archive'],'var_name'=>'active_archive'));
	    $update[24] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['active_calendar'],'var_name'=>'active_calendar'));
	    $update[25] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['active_send_admin_message'],'var_name'=>'active_send_admin_message'));
	    $update[26] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['active_reply_today'],'var_name'=>'active_reply_today'));
	    $update[27] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['active_subject_today'],'var_name'=>'active_subject_today'));
	    $update[28] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['active_static'],'var_name'=>'active_static'));
	    $update[29] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['active_team'],'var_name'=>'active_team'));
	    $update[30] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['active_rss'],'var_name'=>'active_rss'));

		if ($update[0]
		    and $update[1]
			and $update[2]
			and $update[3]
            and $update[4]
			and $update[5]
    		and $update[6]
			and $update[7]
			and $update[8]
			and $update[9]
			and $update[10]
			and $update[11]
			and $update[12]
			and $update[13]
			and $update[14]
			and $update[15]
			and $update[16]
			and $update[17]
			and $update[18]
			and $update[21]
			and $update[22]
			and $update[24]
			and $update[25]
			and $update[26]
			and $update[27]
			and $update[28]
			and $update[29]
			and $update[30])
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['updated_successfully_Please_wait']);
			$PowerBB->functions->redirect('index.php?page=options&amp;general=1&amp;main=1');
		}
	}

	function _HumanMain()
	{
		global $PowerBB;

		$PowerBB->template->display('options_human_verification');
	}

	function _HumanUpdate()
	{
		global $PowerBB;

		$update = array();
		$update[0] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['captcha_o'],'var_name'=>'captcha_o'));
		$update[1] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['captcha_type'],'var_name'=>'captcha_type'));
		$update[2] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['questions'],'var_name'=>'questions'));
		$update[3] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['answers'],'var_name'=>'answers'));

		if ($update[0] and $update[1] and $update[2] and $update[3])
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['updated_successfully_Please_wait']);
			$PowerBB->functions->redirect('index.php?page=options&amp;human_verification=1&amp;main=1');
		}
	}

	function _TimeMain()
	{
		global $PowerBB;

		$PowerBB->template->display('options_time');
	}

	function _TimeUpdate()
	{
		global $PowerBB;

		$update = array();
		$update[0] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['time_stamp'],'var_name'=>'timestamp'));
		$update[1] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['time_system'],'var_name'=>'timesystem'));
       	$update[2] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['date_system'],'var_name'=>'datesystem'));
        $update[3] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['time_offset'],'var_name'=>'timeoffset'));

		if ($update[0] and $update[1] and $update[2] and $update[3])
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['updated_successfully_Please_wait']);
			$PowerBB->functions->redirect('index.php?page=options&amp;time=1&amp;main=1');
		}
	}

	function _EmailMsgMain()
	{
		global $PowerBB;

		$GetMelMsgArr 						= 	array();
		$GetMelMsgArr['order'] 				= 	array();
		$GetMelMsgArr['order']['field'] 	= 	'id';
		$GetMelMsgArr['order']['type'] 		= 	'ASC';

		$PowerBB->_CONF['template']['while']['GetMessageList'] = $PowerBB->core->GetList($GetMelMsgArr,'email_msg');

		$PowerBB->functions->CleanVariable($PowerBB->_CONF['template']['while']['GetMessageList'],'html');

		$PowerBB->template->display('options_email_msg');
	}

	function _EmailMsgUpdate()
	{
		global $PowerBB;

      $Message_info = $PowerBB->DB->sql_query("SELECT  *   FROM " . $PowerBB->prefix."email_msg" . " ORDER BY id ASC");
       while ($get_Messages_row = $PowerBB->DB->sql_fetch_array($Message_info))
      {
			$title = 'title-' . $get_Messages_row['id'];
			$text = 'text-' . $get_Messages_row['id'];
				$t 	= 	$PowerBB->_POST[$title];
				$a 	= 	$PowerBB->_POST[$text];
                $id = $get_Messages_row['id'];
               $update = $PowerBB->DB->sql_query('UPDATE ' . $PowerBB->prefix."email_msg" . " SET title='$t',text='$a'  WHERE " . $PowerBB->prefix."email_msg" . ".id = '$id'");
     }
		if ($update)
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['updated_successfully_Please_wait']);
			$PowerBB->functions->redirect('index.php?page=options&amp;email_msg=1&amp;main=1');
		}
	}

	function _PagesMain()
	{
		global $PowerBB;

		$PowerBB->template->display('options_page');
	}

	function _PagesUpdate()
	{
		global $PowerBB;

		if (empty($PowerBB->_POST['page_max'])
			or empty($PowerBB->_POST['subject_perpage'])
			or empty($PowerBB->_POST['reply_perpage'])
			or empty($PowerBB->_POST['avatar_perpage']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_fill_in_all_the_information']);
		}

		$update = array();

		$update[0] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['page_max'],'var_name'=>'page_max'));

		$update[1] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['subject_perpage'],'var_name'=>'subject_perpage'));

		$update[2] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['reply_perpage'],'var_name'=>'perpage'));

		$update[3] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['avatar_perpage'],'var_name'=>'avatar_perpage'));

		if ($update[0]
			and $update[1]
			and $update[2]
			and $update[3])
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['updated_successfully_Please_wait']);
			$PowerBB->functions->redirect('index.php?page=options&amp;pages=1&amp;main=1');
		}
	}

	function _RegisterMain()
	{
		global $PowerBB;

		//////////

		$GroupArr 							= 	array();

		$GroupArr['order'] 					= 	array();
		$GroupArr['order']['field'] 		= 	'group_order';
		$GroupArr['order']['type'] 			= 	'ASC';

		$PowerBB->_CONF['template']['while']['GroupList'] = $PowerBB->core->GetList($GroupArr,'group');

		//////////

		$PowerBB->template->display('options_register');
	}

	function _RegisterUpdate()
	{
		global $PowerBB;

		if (empty($PowerBB->_POST['reg_less_num'])
			or empty($PowerBB->_POST['reg_max_num'])
			or empty($PowerBB->_POST['reg_pass_min_num'])
			or empty($PowerBB->_POST['reg_pass_max_num']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_fill_in_all_the_information']);
		}

		$update = array();
		$update[0] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['reg_close'],'var_name'=>'reg_close'));

		$update[1] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['def_group'],'var_name'=>'def_group'));

		$update[2] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['adef_group'],'var_name'=>'adef_group'));

		$update[3] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['reg_o'],'var_name'=>'reg_o'));

		$update[4] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['reg_less_num'],'var_name'=>'reg_less_num'));

		$update[5] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['reg_max_num'],'var_name'=>'reg_max_num'));

		$update[6] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['reg_pass_min_num'],'var_name'=>'reg_pass_min_num'));

		$update[7] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['reg_pass_max_num'],'var_name'=>'reg_pass_max_num'));

		$update[8] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['Sat'],'var_name'=>'reg_Sat'));

		$update[9] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['Sun'],'var_name'=>'reg_Sun'));

		$update[10] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['Mon'],'var_name'=>'reg_Mon'));

		$update[11] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['Tue'],'var_name'=>'reg_Tue'));

		$update[12] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['Wed'],'var_name'=>'reg_Wed'));

		$update[13] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['Thu'],'var_name'=>'reg_Thu'));

		$update[14] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['Fri'],'var_name'=>'reg_Fri'));

		$update[15] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['active_birth_date'],'var_name'=>'active_birth_date'));
		$update[16] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['activ_welcome_message'],'var_name'=>'activ_welcome_message'));
		$update[17] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['welcome_message_text'],'var_name'=>'welcome_message_text'));
		$update[18] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['welcome_message_mail_or_private'],'var_name'=>'welcome_message_mail_or_private'));

		if ($update[0]
			and $update[1]
			and $update[2]
			and $update[3]
			and $update[4]
			and $update[5]
			and $update[6]
			and $update[7]
			and $update[8]
			and $update[9]
			and $update[10]
			and $update[11]
			and $update[12]
			and $update[13]
			and $update[14]
			and $update[15]
			and $update[16]
			and $update[17]
			and $update[18])
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['updated_successfully_Please_wait']);
			$PowerBB->functions->redirect('index.php?page=options&amp;register=1&amp;main=1');
		}
	}

	function _TopicsMain()
	{
		global $PowerBB;

		$PowerBB->template->display('options_topics');
	}

	function _TopicsUpdate()
	{
		global $PowerBB;

		if (!isset($PowerBB->_POST['post_text_min'])
			or !isset($PowerBB->_POST['post_text_max'])
			or !isset($PowerBB->_POST['post_title_min'])
			or !isset($PowerBB->_POST['post_title_max'])
			or !isset($PowerBB->_POST['time_out'])
			or !isset($PowerBB->_POST['floodctrl'])
			or !isset($PowerBB->_POST['default_imagesW'])
			or !isset($PowerBB->_POST['default_imagesH']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_fill_in_all_the_information']);
		}

		$update = array();
		$update[0] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['post_text_min'],'var_name'=>'post_text_min'));
		$update[1] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['post_text_max'],'var_name'=>'post_text_max'));
		$update[2] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['post_title_min'],'var_name'=>'post_title_min'));
		$update[3] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['post_title_max'],'var_name'=>'post_title_max'));
		$update[4] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['time_out'],'var_name'=>'time_out'));
		$update[5] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['floodctrl'],'var_name'=>'floodctrl'));
		$update[6] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['samesubject_show'],'var_name'=>'samesubject_show'));
		$update[7] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['show_subject_all'],'var_name'=>'show_subject_all'));
		$update[8] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['resize_imagesAllow'],'var_name'=>'resize_imagesAllow'));
		$update[9] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['default_imagesW'],'var_name'=>'default_imagesW'));
		$update[10] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['default_imagesH'],'var_name'=>'default_imagesH'));
		$update[11] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['wordwrap'],'var_name'=>'wordwrap'));
		$update[12] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['subject_describe_show'],'var_name'=>'subject_describe_show'));
		$update[13] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['rating_show'],'var_name'=>'rating_show'));
		$update[14] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['show_rating_num_max'],'var_name'=>'show_rating_num_max'));
		$update[15] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['smiles_nm'],'var_name'=>'smiles_nm'));
		$update[16] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['icons_numbers'],'var_name'=>'icons_numbers'));
		$update[17] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['smil_columns_number'],'var_name'=>'smil_columns_number'));
		$update[18] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['icon_columns_number'],'var_name'=>'icon_columns_number'));
		$update[19] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['active_like_facebook'],'var_name'=>'active_like_facebook'));
		$update[20] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['active_add_this'],'var_name'=>'active_add_this'));
		$update[21] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['use_list'],'var_name'=>'use_list'));

		if ($update[0]
			and $update[1]
			and $update[2]
			and $update[3]
			and $update[4]
			and $update[5]
			and $update[6]
			and $update[7]
			and $update[8]
			and $update[9]
			and $update[10]
			and $update[11]
			and $update[12]
			and $update[13]
			and $update[14]
			and $update[15]
			and $update[16]
			and $update[17]
			and $update[18]
			and $update[19]
			and $update[20]
			and $update[21])
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['updated_successfully_Please_wait']);
			$PowerBB->functions->redirect('index.php?page=options&amp;topics=1&amp;main=1');
		}
	}

	function _FastReplyMain()
	{
		global $PowerBB;

		$PowerBB->template->display('options_fast_reply');
	}

	function _FastReplyUpdate()
	{
		global $PowerBB;

		$update = array();
		$update[0] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['fastreply_allow'],'var_name'=>'fastreply_allow'));

		$update[1] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['toolbox_show'],'var_name'=>'toolbox_show'));

		$update[2] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['smiles_show'],'var_name'=>'smiles_show'));

		$update[3] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['icons_show'],'var_name'=>'icons_show'));

		$update[4] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['title_quote'],'var_name'=>'title_quote'));

		$update[5] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['activate_closestick'],'var_name'=>'activate_closestick'));

		if ($update[0]
			and $update[1]
			and $update[2]
			and $update[3]
			and $update[4]
			and $update[5])
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['updated_successfully_Please_wait']);
			$PowerBB->functions->redirect('index.php?page=options&amp;fast_reply=1&amp;main=1');
		}
	}

	function _MemberMain()
	{
		global $PowerBB;

		$PowerBB->template->display('options_member');
	}

	function _MemberUpdate()
	{
		global $PowerBB;

		$update = array();
		$update[0] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['confirm_on_change_mail'],'var_name'=>'confirm_on_change_mail'));

		$update[1] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['confirm_on_change_pass'],'var_name'=>'confirm_on_change_pass'));

		$update[2] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['allow_apsent'],'var_name'=>'allow_apsent'));
		$update[3] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['users_security'],'var_name'=>'users_security'));

		if ($update[0]
			and $update[1]
			and $update[2]
			and $update[3])
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['updated_successfully_Please_wait']);
			$PowerBB->functions->redirect('index.php?page=options&amp;member=1&amp;main=1');
		}
	}

	function _AvatarMain()
	{
		global $PowerBB;

		$PowerBB->template->display('options_avatar');
	}

	function _AvatarUpdate()
	{
		global $PowerBB;


		$update = array();
		$update[0] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['allow_avatar'],'var_name'=>'allow_avatar'));

		$update[1] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['upload_avatar'],'var_name'=>'upload_avatar'));

		$update[2] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['max_avatar_width'],'var_name'=>'max_avatar_width'));

		$update[3] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['max_avatar_height'],'var_name'=>'max_avatar_height'));

		$update[4] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['default_avatar'],'var_name'=>'default_avatar'));
		$update[5] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['avatar_columns_number'],'var_name'=>'avatar_columns_number'));

		if ($update[0]
			and $update[1]
			and $update[2]
			and $update[3]
			and $update[4]
			and $update[5])
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['updated_successfully_Please_wait']);
			$PowerBB->functions->redirect('index.php?page=options&amp;avatar=1&amp;main=1');
		}
	}

	function _CloseDaysMain()
	{
		global $PowerBB;

		$PowerBB->template->display('options_days');
	}

	function _CloseDaysUpdate()
	{
		global $PowerBB;

		$update = array();
		$update[0] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['Sat'],'var_name'=>'Sat'));

		$update[1] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['Sun'],'var_name'=>'Sun'));

		$update[2] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['Mon'],'var_name'=>'Mon'));

		$update[3] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['Tue'],'var_name'=>'Tue'));

		$update[4] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['Wed'],'var_name'=>'Wed'));

		$update[5] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['Thu'],'var_name'=>'Thu'));

		$update[6] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['Fri'],'var_name'=>'Fri'));

		if ($update[0]
			and $update[1]
			and $update[2]
			and $update[3]
			and $update[4]
			and $update[5]
			and $update[6])
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['updated_successfully_Please_wait']);
			$PowerBB->functions->redirect('index.php?page=options&amp;close_days=1&amp;main=1');
		}
	}

	function _CloseMain()
	{
		global $PowerBB;

		$PowerBB->template->display('options_close');
	}

	function _CloseUpdate()
	{
		global $PowerBB;

		$update = array();
		$update[0] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['board_close'],'var_name'=>'board_close'));

		$update[1] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['board_msg'],'var_name'=>'board_msg'));

		if ($update[0] and $update[1])
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['updated_successfully_Please_wait']);
			$PowerBB->functions->redirect('index.php?page=options&amp;close=1&amp;main=1');
		}
	}
	function _sidebar_list_Main()
	{
		global $PowerBB;

		$PowerBB->template->display('options_sidebar_list');
	}

	function _sidebar_list_Update()
	{
		global $PowerBB;
        if ($PowerBB->_POST['submit'] == $PowerBB->_CONF['template']['_CONF']['lang']['restore_defaults'])
        {             $sidebar_list_active ='1';
             $sidebar_list_align = 'left';
             $sidebar_list_pages ='index';
             $sidebar_list_width ='25';
             $sidebar_list_exclusion_forums ='254,545';
             $sidebar_list_content ="{template}login_box{/template}\n{template}whatis_new{/template}";
			$update = array();
			$update[0] = $PowerBB->info->UpdateInfo(array('value'=>$sidebar_list_active,'var_name'=>'sidebar_list_active'));
			$update[1] = $PowerBB->info->UpdateInfo(array('value'=>$sidebar_list_align,'var_name'=>'sidebar_list_align'));
			$update[2] = $PowerBB->info->UpdateInfo(array('value'=>$sidebar_list_pages,'var_name'=>'sidebar_list_pages'));
			$update[3] = $PowerBB->info->UpdateInfo(array('value'=>$sidebar_list_width,'var_name'=>'sidebar_list_width'));
			$update[4] = $PowerBB->info->UpdateInfo(array('value'=>$sidebar_list_exclusion_forums,'var_name'=>'sidebar_list_exclusion_forums'));
			$update[5] = $PowerBB->info->UpdateInfo(array('value'=>$sidebar_list_content,'var_name'=>'sidebar_list_content'));

			if ($update[0] and $update[1]and $update[2]and $update[3]and $update[4]and $update[5])
			{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['updated_successfully_Please_wait']);
			$PowerBB->functions->redirect('index.php?page=options&amp;sidebar_list=1&amp;main=1');
			}
        }
        elseif ($PowerBB->_POST['submit'] == $PowerBB->_CONF['template']['_CONF']['lang']['acceptable'])
        {
			$update = array();
			$update[0] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['sidebar_list_active'],'var_name'=>'sidebar_list_active'));
			$update[1] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['sidebar_list_align'],'var_name'=>'sidebar_list_align'));
			$update[2] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['sidebar_list_pages'],'var_name'=>'sidebar_list_pages'));
			$update[3] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['sidebar_list_width'],'var_name'=>'sidebar_list_width'));
			$update[4] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['sidebar_list_exclusion_forums'],'var_name'=>'sidebar_list_exclusion_forums'));
			$update[5] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['sidebar_list_content'],'var_name'=>'sidebar_list_content'));

			if ($update[0] and $update[1]and $update[2]and $update[3]and $update[4]and $update[5])
			{
				$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['updated_successfully_Please_wait']);
				$PowerBB->functions->redirect('index.php?page=options&amp;sidebar_list=1&amp;main=1');
			}
		}
	}
	function _MailerMain()
	{
		global $PowerBB;

		$PowerBB->template->display('options_mailer');
	}

	function _MailerUpdate()
	{
		global $PowerBB;

		$update = array();
		$update[0] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['admin_email'],'var_name'=>'admin_email'));
		$update[1] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['send_email'],'var_name'=>'send_email'));
		$update[2] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['mailer'],'var_name'=>'mailer'));
		$update[3] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['smtp_secure'],'var_name'=>'smtp_secure'));
		$update[4] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['smtp_port'],'var_name'=>'smtp_port'));
		$update[5] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['smtp_username'],'var_name'=>'smtp_username'));
		$update[6] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['smtp_password'],'var_name'=>'smtp_password'));
		$update[7] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['smtp_server'],'var_name'=>'smtp_server'));

		if ($update[0]
			and $update[1]
			and $update[2]
			and $update[3]
			and $update[4]
			and $update[5]
			and $update[6]
			and $update[7])
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['updated_successfully_Please_wait']);
			$PowerBB->functions->redirect('index.php?page=options&amp;mailer=1&amp;main=1');
		}
	}

	function _AjaxMain()
	{
		global $PowerBB;

		$PowerBB->template->display('options_ajax');
	}

	function _AjaxUpdate()
	{
		global $PowerBB;

		$update = array();

		$update[0] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['ajax_freply'],'var_name'=>'ajax_freply'));

		//$update[3] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['ajax_moderator_options'],'var_name'=>'ajax_moderator_options'));

		if ($update[0])
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['updated_successfully_Please_wait']);
			$PowerBB->functions->redirect('index.php?page=options&amp;ajax=1&amp;main=1');
		}
	}

	function _WarningMain()
	{
		global $PowerBB;

		$PowerBB->template->display('options_warning');
	}
	function _WarningUpdate()
	{
		global $PowerBB;

		if (empty($PowerBB->_POST['warning_number_to_ban']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_fill_in_all_the_information']);
		}
		$update = array();

		$update[0] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['warning_number_to_ban'],'var_name'=>'warning_number_to_ban'));

		if ($update[0])
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['updated_successfully_Please_wait']);
			$PowerBB->functions->redirect('index.php?page=options&amp;warning=1&amp;main=1');
		}
	}

	function _ReputationMain()
	{
		global $PowerBB;

		$PowerBB->template->display('options_reputation');
	}
	function _ReputationUpdate()
	{
		global $PowerBB;

		if (empty($PowerBB->_POST['show_reputation_number']))
		{
        $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_enter_the_number_of_fair_to_be_displayed']);
		}
		$update = array();

		$update[0] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['reputationallw'],'var_name'=>'reputationallw'));
		$update[1] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['show_reputation_number'],'var_name'=>'show_reputation_number'));


		if ($update[0]
			and $update[1])
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['updated_successfully_Please_wait']);
			$PowerBB->functions->redirect('index.php?page=options&amp;reputation=1&amp;main=1');
		}
	}

	function _ModsMain()
	{
		global $PowerBB;

		$PowerBB->template->display('options_mods');
	}

	function _ModsUpdate()
	{
		global $PowerBB;

		if (empty($PowerBB->_POST['last_static_num']))
		{
         $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_write_the_number_of_posts']);
		}
		if (empty($PowerBB->_POST['last_posts_static_num']))
		{
         $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_write_a_number_of_other_posts']);
		}
		if (empty($PowerBB->_POST['lasts_posts_bar_num']))
		{
         $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_write_the_number_of_posts_that_appear_Ribbon']);
		}

		$update = array();

		$update[1] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['activate_last_static_list'],'var_name'=>'activate_last_static_list'));
		$update[2] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['last_static_num'],'var_name'=>'last_static_num'));
		$update[3] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['last_posts_static_num'],'var_name'=>'last_posts_static_num'));
		$update[4] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['activate_lasts_posts_bar'],'var_name'=>'activate_lasts_posts_bar'));
		$update[5] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['lasts_posts_bar_num'],'var_name'=>'lasts_posts_bar_num'));
		$update[6] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['lasts_posts_bar_dir'],'var_name'=>'lasts_posts_bar_dir'));
		$update[8] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['activate_special_bar'],'var_name'=>'activate_special_bar'));
		$update[9] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['special_bar_dir'],'var_name'=>'special_bar_dir'));
		$update[10] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['random_ads'],'var_name'=>'random_ads'));
		$update[11] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['show_ads'],'var_name'=>'show_ads'));
		$update[12] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['show_online_list_today'],'var_name'=>'show_online_list_today'));
		$update[13] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['show_list_last_5_posts_member'],'var_name'=>'show_list_last_5_posts_member'));
		$update[14] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['last_subject_writer_nm'],'var_name'=>'last_subject_writer_nm'));
		$update[15] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['activate_chat_bar'],'var_name'=>'activate_chat_bar'));
		$update[16] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['chat_message_num'],'var_name'=>'chat_message_num'));
		$update[17] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['chat_num_mem_posts'],'var_name'=>'chat_num_mem_posts'));
		$update[18] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['chat_num_characters'],'var_name'=>'chat_num_characters'));
		$update[19] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['chat_hide_country'],'var_name'=>'chat_hide_country'));
		//$update[20] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['chat_bar_dir'],'var_name'=>'chat_bar_dir'));
		$update[21] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['haid_links_for_guest'],'var_name'=>'haid_links_for_guest'));
		$update[22] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['guest_message_for_haid_links'],'var_name'=>'guest_message_for_haid_links'));
		$update[23] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['add_tags_automatic'],'var_name'=>'add_tags_automatic'));
		$update[24] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['show_onlineguest'],'var_name'=>'show_onlineguest'));
		$update[25] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['mor_hours_online_today'],'var_name'=>'mor_hours_online_today'));
		$update[26] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['mor_seconds_online'],'var_name'=>'mor_seconds_online'));
		$update[27] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['get_group_username_style'],'var_name'=>'get_group_username_style'));
		$update[28] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['num_entries_error'],'var_name'=>'num_entries_error'));
		$update[29] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['search_engine_spiders'],'var_name'=>'search_engine_spiders'));

		$kv = array();
		foreach ($PowerBB->_POST as $var_name => $value) {
		$kv[] = "$var_name=$value";
		if ($value !='')
		{
		$update = $PowerBB->info->UpdateInfo(array('value'=>$value,'var_name'=>$var_name));
		}
		}
		if ($update)
		{
		$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['updated_successfully_Please_wait']);
		$PowerBB->functions->redirect('index.php?page=options&amp;mods=1&amp;main=1');
		}

	}

	function _PbbSeoMain()
	{
		global $PowerBB;

 	    $path = '../.htaccess';

    	// To be more advanced :D
    	if (is_writable($path))
    	{

    	$lines = file($path);
    	$context = '';

    	foreach ($lines as $line)
    	{
    		$context .= $line;
    	}

    	$context = $PowerBB->functions->CleanVariable($context,'html');

    	$last_edit = date("d. M. Y", filectime($path));

    	$PowerBB->template->assign('last_edit',$last_edit);
    	$PowerBB->template->assign('context',$context);
       }
       else
       {      	$context = '<span dir="ltr">.htaccess file does not exist </span><br /><span dir="rtl"> يجب ان ترفع ملف .htaccess بداخل مجلد منتداك لتعمل مع خاصية تحويل الروابط</span>';
		$PowerBB->functions->msg($context);

       }

		$PowerBB->template->display('options_pbbseo');
	}

	function _PbbSeoUpdate()
	{
		global $PowerBB;

	   // if ($PowerBB->_POST['rewriterule'] == '1')
	   // {
	        $context = ($PowerBB->_POST['context']);

		     $context = $PowerBB->functions->CleanVariable($context,'unhtml');
		     $context = stripslashes($context);
	         $filename = '../.htaccess';
		     $fp = fopen($filename,'w');
		     $fw = fwrite($fp,$context);

		     fclose($fp);


  	    // }
        //  else
       //  {

          // $filename = '.htaccess';
		  // $del = unlink($filename);

	   // }

       $update = array();
      	$update[0] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['rewriterule'],'var_name'=>'rewriterule'));

		if ($update[0])
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['updated_successfully_Please_wait']);
			$PowerBB->functions->redirect('index.php?page=options&amp;pbb_seo=1&amp;main=1');
		}
	}

	function _PbbSitemap()
	{
		global $PowerBB;

        $update = array();
		$update[0] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['sitemap'],'var_name'=>'sitemap'));

		if ($update[0])
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['updated_successfully_Please_wait']);
			$PowerBB->functions->redirect('index.php?page=options&amp;pbb_seo=1&amp;main=1');
		}

	}

	function _AllGeneral()
	{
		global $PowerBB;

		$GroupArr 							= 	array();

		$GroupArr['order'] 					= 	array();
		$GroupArr['order']['field'] 		= 	'group_order';
		$GroupArr['order']['type'] 			= 	'ASC';

		$PowerBB->_CONF['template']['while']['GroupList'] = $PowerBB->core->GetList($GroupArr,'group');

     $PowerBB->template->display('options_all_general');
	}
	function _AllGeneralUpdate()
	{
		global $PowerBB;
       /*
		if (empty($PowerBB->_POST['title']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_write_the_name_of_the_Forum']);
		}
       */
		$update = array();
		$update[0] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['title'],'var_name'=>'title'));
		$update[1] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['pm_feature'],'var_name'=>'pm_feature'));
		$update[2] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['no_describe'],'var_name'=>'no_describe'));
		$update[3] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['no_moderators'],'var_name'=>'no_moderators'));
	    $update[4] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['no_sub'],'var_name'=>'no_sub'));
	    $update[5] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['members_send_pm'],'var_name'=>'members_send_pm'));
	    $update[6] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['keywords'],'var_name'=>'keywords'));
	    $update[7] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['charset'],'var_name'=>'charset'));
	    $update[8] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['description'],'var_name'=>'description'));
	    $update[9] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['content_language'],'var_name'=>'content_language'));
	    $update[10] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['content_dir'],'var_name'=>'content_dir'));
	    $update[11] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['rules'],'var_name'=>'rules'));
	    $update[12] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['censorwords'],'var_name'=>'censorwords'));
	    $update[13] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['flood_search'],'var_name'=>'flood_search'));
	    $update[14] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['characters_keyword_search'],'var_name'=>'characters_keyword_search'));
	    $update[15] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['allowed_powered'],'var_name'=>'allowed_powered'));
	    $update[16] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['visitor_message_chars'],'var_name'=>'visitor_message_chars'));
	    $update[17] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['sub_columns_number'],'var_name'=>'sub_columns_number'));
	    $update[18] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['active_forum_online_number'],'var_name'=>'active_forum_online_number'));
	    $update[21] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['active_visitor_message'],'var_name'=>'active_visitor_message'));
	    $update[22] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['active_friend'],'var_name'=>'active_friend'));
	    $update[23] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['active_archive'],'var_name'=>'active_archive'));
	    $update[24] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['active_calendar'],'var_name'=>'active_calendar'));
	    $update[25] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['active_send_admin_message'],'var_name'=>'active_send_admin_message'));
	    $update[26] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['active_reply_today'],'var_name'=>'active_reply_today'));
	    $update[27] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['active_subject_today'],'var_name'=>'active_subject_today'));
	    $update[28] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['active_static'],'var_name'=>'active_static'));
	    $update[29] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['active_team'],'var_name'=>'active_team'));
	    $update[30] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['active_rss'],'var_name'=>'active_rss'));

		$update = array();
		$update[0] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['captcha_o'],'var_name'=>'captcha_o'));
		$update[1] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['captcha_type'],'var_name'=>'captcha_type'));
		$update[2] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['questions'],'var_name'=>'questions'));
		$update[3] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['answers'],'var_name'=>'answers'));


		$update = array();
		$update[0] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['time_stamp'],'var_name'=>'timestamp'));
		$update[1] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['time_system'],'var_name'=>'timesystem'));
       	$update[2] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['date_system'],'var_name'=>'datesystem'));
        $update[3] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['time_offset'],'var_name'=>'timeoffset'));
        /*
         if (empty($PowerBB->_POST['page_max'])
			or empty($PowerBB->_POST['subject_perpage'])
			or empty($PowerBB->_POST['reply_perpage'])
			or empty($PowerBB->_POST['avatar_perpage']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_fill_in_all_the_information']);
		}
       */
		$update = array();

		$update[0] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['page_max'],'var_name'=>'page_max'));

		$update[1] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['subject_perpage'],'var_name'=>'subject_perpage'));

		$update[2] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['reply_perpage'],'var_name'=>'perpage'));

		$update[3] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['avatar_perpage'],'var_name'=>'avatar_perpage'));
         /*
			if (empty($PowerBB->_POST['reg_less_num'])
			or empty($PowerBB->_POST['reg_max_num'])
			or empty($PowerBB->_POST['reg_pass_min_num'])
			or empty($PowerBB->_POST['reg_pass_max_num']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_fill_in_all_the_information']);
		}
        */
		$update = array();
		$update[0] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['reg_close'],'var_name'=>'reg_close'));

		$update[1] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['def_group'],'var_name'=>'def_group'));

		$update[2] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['adef_group'],'var_name'=>'adef_group'));

		$update[3] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['reg_o'],'var_name'=>'reg_o'));

		$update[4] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['reg_less_num'],'var_name'=>'reg_less_num'));

		$update[5] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['reg_max_num'],'var_name'=>'reg_max_num'));

		$update[6] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['reg_pass_min_num'],'var_name'=>'reg_pass_min_num'));

		$update[7] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['reg_pass_max_num'],'var_name'=>'reg_pass_max_num'));

		$update[8] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['Sat'],'var_name'=>'reg_Sat'));

		$update[9] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['Sun'],'var_name'=>'reg_Sun'));

		$update[10] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['Mon'],'var_name'=>'reg_Mon'));

		$update[11] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['Tue'],'var_name'=>'reg_Tue'));

		$update[12] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['Wed'],'var_name'=>'reg_Wed'));

		$update[13] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['Thu'],'var_name'=>'reg_Thu'));

		$update[14] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['Fri'],'var_name'=>'reg_Fri'));

		$update[15] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['active_birth_date'],'var_name'=>'active_birth_date'));

		$update[16] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['activ_welcome_message'],'var_name'=>'activ_welcome_message'));
		$update[17] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['welcome_message_text'],'var_name'=>'welcome_message_text'));
		$update[18] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['welcome_message_mail_or_private'],'var_name'=>'welcome_message_mail_or_private'));

        /*
		if (!isset($PowerBB->_POST['post_text_min'])
			or !isset($PowerBB->_POST['post_text_max'])
			or !isset($PowerBB->_POST['post_title_min'])
			or !isset($PowerBB->_POST['post_title_max'])
			or !isset($PowerBB->_POST['time_out'])
			or !isset($PowerBB->_POST['floodctrl'])
			or !isset($PowerBB->_POST['default_imagesW'])
			or !isset($PowerBB->_POST['default_imagesH']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_fill_in_all_the_information']);
		}
       */
		$update = array();
		$update[0] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['post_text_min'],'var_name'=>'post_text_min'));

		$update[1] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['post_text_max'],'var_name'=>'post_text_max'));

		$update[2] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['post_title_min'],'var_name'=>'post_title_min'));

		$update[3] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['post_title_max'],'var_name'=>'post_title_max'));

		$update[4] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['time_out'],'var_name'=>'time_out'));

		$update[5] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['floodctrl'],'var_name'=>'floodctrl'));

		$update[6] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['samesubject_show'],'var_name'=>'samesubject_show'));

		$update[7] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['show_subject_all'],'var_name'=>'show_subject_all'));

		$update[8] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['resize_imagesAllow'],'var_name'=>'resize_imagesAllow'));

		$update[9] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['default_imagesW'],'var_name'=>'default_imagesW'));

		$update[10] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['default_imagesH'],'var_name'=>'default_imagesH'));

		$update[11] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['wordwrap'],'var_name'=>'wordwrap'));

		$update[12] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['subject_describe_show'],'var_name'=>'subject_describe_show'));

		$update[13] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['rating_show'],'var_name'=>'rating_show'));

		$update[14] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['show_rating_num_max'],'var_name'=>'show_rating_num_max'));

		$update[15] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['smiles_nm'],'var_name'=>'smiles_nm'));
		$update[16] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['icons_numbers'],'var_name'=>'icons_numbers'));
		$update[17] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['smil_columns_number'],'var_name'=>'smil_columns_number'));
		$update[18] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['icon_columns_number'],'var_name'=>'icon_columns_number'));
		$update[19] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['active_like_facebook'],'var_name'=>'active_like_facebook'));
		$update[20] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['active_add_this'],'var_name'=>'active_add_this'));
		$update[21] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['use_list'],'var_name'=>'use_list'));


		$update = array();
		$update[0] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['fastreply_allow'],'var_name'=>'fastreply_allow'));

		$update[1] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['toolbox_show'],'var_name'=>'toolbox_show'));

		$update[2] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['smiles_show'],'var_name'=>'smiles_show'));

		$update[3] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['icons_show'],'var_name'=>'icons_show'));

		$update[4] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['title_quote'],'var_name'=>'title_quote'));

		$update[5] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['activate_closestick'],'var_name'=>'activate_closestick'));

		$update = array();
		$update[0] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['confirm_on_change_mail'],'var_name'=>'confirm_on_change_mail'));

		$update[1] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['confirm_on_change_pass'],'var_name'=>'confirm_on_change_pass'));

		$update[2] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['allow_apsent'],'var_name'=>'allow_apsent'));

		$update[3] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['users_security'],'var_name'=>'users_security'));

       /*
		if (empty($PowerBB->_POST['max_avatar_width'])
			and empty($PowerBB->_POST['max_avatar_height']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_fill_in_all_the_information']);
		}
        */
		$update = array();
		$update[0] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['allow_avatar'],'var_name'=>'allow_avatar'));

		$update[1] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['upload_avatar'],'var_name'=>'upload_avatar'));

		$update[2] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['max_avatar_width'],'var_name'=>'max_avatar_width'));

		$update[3] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['max_avatar_height'],'var_name'=>'max_avatar_height'));

		$update[4] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['default_avatar'],'var_name'=>'default_avatar'));
		$update[5] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['avatar_columns_number'],'var_name'=>'avatar_columns_number'));


		$update = array();
		$update[0] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['Sat'],'var_name'=>'Sat'));

		$update[1] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['Sun'],'var_name'=>'Sun'));

		$update[2] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['Mon'],'var_name'=>'Mon'));

		$update[3] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['Tue'],'var_name'=>'Tue'));

		$update[4] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['Wed'],'var_name'=>'Wed'));

		$update[5] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['Thu'],'var_name'=>'Thu'));

		$update[6] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['Fri'],'var_name'=>'Fri'));

		$update = array();
		$update[0] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['board_close'],'var_name'=>'board_close'));

		$update[1] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['board_msg'],'var_name'=>'board_msg'));

		$update = array();
		$update[0] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['admin_email'],'var_name'=>'admin_email'));
		$update[1] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['send_email'],'var_name'=>'send_email'));
		$update[2] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['mailer'],'var_name'=>'mailer'));
		$update[3] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['smtp_secure'],'var_name'=>'smtp_secure'));
		$update[4] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['smtp_port'],'var_name'=>'smtp_port'));
		$update[5] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['smtp_username'],'var_name'=>'smtp_username'));
		$update[6] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['smtp_password'],'var_name'=>'smtp_password'));
		$update[7] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['smtp_server'],'var_name'=>'smtp_server'));

		$update = array();

		$update[0] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['ajax_freply'],'var_name'=>'ajax_freply'));

		//$update[3] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['ajax_moderator_options'],'var_name'=>'ajax_moderator_options'));
       /*
		if (empty($PowerBB->_POST['warning_number_to_ban']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_fill_in_all_the_information']);
		}
		*/
		$update = array();

		$update[0] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['warning_number_to_ban'],'var_name'=>'warning_number_to_ban'));

        /*
		if (empty($PowerBB->_POST['show_reputation_number']))
		{
        $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_enter_the_number_of_fair_to_be_displayed']);
		}
		*/
		$update = array();

		$update[0] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['reputationallw'],'var_name'=>'reputationallw'));
		$update[1] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['show_reputation_number'],'var_name'=>'show_reputation_number'));

       /*
		if (empty($PowerBB->_POST['last_static_num']))
		{
         $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_write_the_number_of_posts']);
		}
		if (empty($PowerBB->_POST['last_posts_static_num']))
		{
         $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_write_a_number_of_other_posts']);
		}
		if (empty($PowerBB->_POST['lasts_posts_bar_num']))
		{
         $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_write_the_number_of_posts_that_appear_Ribbon']);
		}
        */
		$update = array();

		$update[1] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['activate_last_static_list'],'var_name'=>'activate_last_static_list'));
		$update[2] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['last_static_num'],'var_name'=>'last_static_num'));
		$update[3] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['last_posts_static_num'],'var_name'=>'last_posts_static_num'));
		$update[4] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['activate_lasts_posts_bar'],'var_name'=>'activate_lasts_posts_bar'));
		$update[5] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['lasts_posts_bar_num'],'var_name'=>'lasts_posts_bar_num'));
		$update[6] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['lasts_posts_bar_dir'],'var_name'=>'lasts_posts_bar_dir'));
		$update[8] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['activate_special_bar'],'var_name'=>'activate_special_bar'));
		$update[9] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['special_bar_dir'],'var_name'=>'special_bar_dir'));
		$update[10] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['random_ads'],'var_name'=>'random_ads'));
		$update[11] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['show_ads'],'var_name'=>'show_ads'));
		$update[12] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['show_online_list_today'],'var_name'=>'show_online_list_today'));
		$update[13] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['show_list_last_5_posts_member'],'var_name'=>'show_list_last_5_posts_member'));
		$update[14] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['last_subject_writer_nm'],'var_name'=>'last_subject_writer_nm'));
		$update[15] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['activate_chat_bar'],'var_name'=>'activate_chat_bar'));
		$update[16] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['chat_message_num'],'var_name'=>'chat_message_num'));
		$update[17] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['chat_num_mem_posts'],'var_name'=>'chat_num_mem_posts'));
		$update[18] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['chat_num_characters'],'var_name'=>'chat_num_characters'));
		$update[19] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['chat_hide_country'],'var_name'=>'chat_hide_country'));
		//$update[20] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['chat_bar_dir'],'var_name'=>'chat_bar_dir'));
		$update[21] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['haid_links_for_guest'],'var_name'=>'haid_links_for_guest'));
		$update[22] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['guest_message_for_haid_links'],'var_name'=>'guest_message_for_haid_links'));
		$update[23] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['add_tags_automatic'],'var_name'=>'add_tags_automatic'));
		$update[24] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['show_onlineguest'],'var_name'=>'show_onlineguest'));
		$update[25] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['mor_hours_online_today'],'var_name'=>'mor_hours_online_today'));
		$update[26] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['mor_seconds_online'],'var_name'=>'mor_seconds_online'));
		$update[27] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['get_group_username_style'],'var_name'=>'get_group_username_style'));
		$update[28] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['num_entries_error'],'var_name'=>'num_entries_error'));
		$update[29] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['search_engine_spiders'],'var_name'=>'search_engine_spiders'));

        $code_parse = $PowerBB->functions->get_hooks("options_cp_last");

			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['updated_successfully_Please_wait']);
			$PowerBB->functions->redirect('index.php?page=options&amp;allgeneral=1&amp;main=1');

	}

}

?>
