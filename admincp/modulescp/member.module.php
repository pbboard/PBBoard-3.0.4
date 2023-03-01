<?php

(!defined('IN_PowerBB')) ? die() : '';

define('IN_ADMIN',true);

$CALL_SYSTEM				=	array();
$CALL_SYSTEM['SECTION'] 	= 	true;



define('CLASS_NAME','PowerBBMemberMOD');

include('../common.php');
class PowerBBMemberMOD extends _functions
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
			elseif ($PowerBB->_GET['merge'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_MergeMain();
				}
				elseif ($PowerBB->_GET['start'])
				{
					$this->_MergeStart();
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
				if (!in_array($PowerBB->_CONF['rows']['member_row']['id'], preg_split('#\s*,\s*#s', $PowerBB->superadministrators, -1, PREG_SPLIT_NO_EMPTY)))
				{
				 $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['dnot_have_permit']);
				}
				if ($PowerBB->_GET['main'])
				{
					$this->_DelMain();
				}
				elseif ($PowerBB->_GET['start'])
				{
					$this->_DelStart();
				}
			}
			elseif ($PowerBB->_GET['search'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_SearchMain();
				}
				elseif ($PowerBB->_GET['start'])
				{
					$this->_SearchStart();
				}
			}
			elseif ($PowerBB->_GET['warnings'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_WarningsMain();
				}
				elseif ($PowerBB->_GET['warn_del'])
				{
					$this->_WarningsDel();
				}
			}
			elseif ($PowerBB->_GET['active_member'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_ActiveMemberMain();
				}
				elseif ($PowerBB->_GET['start'])
				{
					$this->_ActiveMemberStart();
				}

			}


			$PowerBB->template->display('footer');
		}
	}

	function _AddMain()
	{
		global $PowerBB;

		if (!in_array($PowerBB->_CONF['rows']['member_row']['id'], preg_split('#\s*,\s*#s', $PowerBB->superadministrators, -1, PREG_SPLIT_NO_EMPTY)))
		{
		 $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['dnot_have_permit']);
		}

		$PowerBB->template->display('member_add');
	}

	function _AddStart()
	{
		global $PowerBB;

		$PowerBB->_POST['username'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['username'],'trim');
		$PowerBB->_POST['email'] 		= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['email'],'trim');

		if (empty($PowerBB->_POST['username'])
			or empty($PowerBB->_POST['password'])
			or empty($PowerBB->_POST['email']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_fill_in_all_the_information']);
		}

		if (!$PowerBB->functions->CheckEmail($PowerBB->_POST['email']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_enter_a_valid_email']);
		}

		if ($PowerBB->member->IsMember(array('where' => array('username',$PowerBB->_POST['username']))))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['please_choose_another_name']);
		}

		if ($PowerBB->member->IsMember(array('where' => array('email',$PowerBB->_POST['email']))))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Email_is_registered_please_type_the_other']);
		}

		if ($PowerBB->_POST['username'] == 'Guest')
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['You_can_not_register_this_name']);
		}

	    if (strstr($PowerBB->_POST['username'],'"')
		or strstr($PowerBB->_POST['username'],"'")
		or strstr($PowerBB->_POST['username'],'>')
		or strstr($PowerBB->_POST['username'],'<')
		or strstr($PowerBB->_POST['username'],'*')
		or strstr($PowerBB->_POST['username'],'%')
		or strstr($PowerBB->_POST['username'],'$')
		or strstr($PowerBB->_POST['username'],'#')
		or strstr($PowerBB->_POST['username'],'+')
		or strstr($PowerBB->_POST['username'],'^')
		or strstr($PowerBB->_POST['username'],'&')
		or strstr($PowerBB->_POST['username'],',')
		or strstr($PowerBB->_POST['username'],'~')
		or strstr($PowerBB->_POST['username'],'@')
		or strstr($PowerBB->_POST['username'],'!')
		or strstr($PowerBB->_POST['username'],'{')
		or strstr($PowerBB->_POST['username'],'}')
		or strstr($PowerBB->_POST['username'],'(')
		or strstr($PowerBB->_POST['username'],')')
		or strstr($PowerBB->_POST['username'],'/'))
      	{
      		$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['You_can_not_register_this_name']);
      	}

        $password_fields = $PowerBB->functions->create_password($PowerBB->_POST['password'], false);

      	//////////

      	// Get the information of default group to set username style cache

		$GrpArr 			= 	array();
		$GrpArr['where'] 	= 	array('id',4);

		$GroupInfo = $PowerBB->core->GetInfo($GrpArr,'group');

		$style = $GroupInfo['username_style'];
		$username_style_cache = str_replace('[username]',$PowerBB->_POST['username'],$style);

      	//////////

		$InsertArr 					= 	array();
		$InsertArr['field']			=	array();

		$InsertArr['field']['username']				= 	$PowerBB->_POST['username'];
		$InsertArr['field']['password']				= 	$password_fields['password'];
		$InsertArr['field']['active_number']		= 	$password_fields['salt'];
		$InsertArr['field']['email']				= 	$PowerBB->_POST['email'];
		$InsertArr['field']['usergroup']			= 	4;
		$InsertArr['field']['user_gender']			= 	$PowerBB->_POST['gender'];
		$InsertArr['field']['register_date']		= 	$PowerBB->_CONF['now'];
		$InsertArr['field']['user_title']			= 	$PowerBB->_CONF['template']['_CONF']['lang']['Member'];
		$InsertArr['field']['style']				=	$PowerBB->_CONF['info_row']['def_style'];
      	$InsertArr['field']['lang'] 				= 	$PowerBB->_CONF['info_row']['def_lang'];
		$InsertArr['field']['username_style_cache']	=	$username_style_cache;
		$InsertArr['get_id']						=	true;

		$insert = $PowerBB->member->InsertMember($InsertArr);

		if ($insert)
		{
			$member_num = $PowerBB->member->GetMemberNumber(array('get_from'	=>	'cache'));

			$PowerBB->cache->UpdateLastMember(array(	'username'		=>	$PowerBB->_POST['username'],
      													'id'			=>	$PowerBB->member->id,
      													'member_num'	=>	$member_num));

			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['member_has_been_added_successfully']);
			$PowerBB->functions->redirect('index.php?page=member&amp;edit=1&amp;main=1&amp;id=' . $PowerBB->member->id);
		}
	}

	function _ControlMain()
	{
		global $PowerBB;

		$PowerBB->_GET['count'] = (!isset($PowerBB->_GET['count'])) ? 0 : $PowerBB->_GET['count'];
		$PowerBB->_GET['count'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['count'],'intval');

        $arr                   = array();
		$arr['get_from']       = 'db';

		$MemArr 					= 	array();
		$MemArr['order']			=	array();
		$MemArr['order']['field']	=	'id';
		$MemArr['order']['type']	=	'DESC';
		$MemArr['proc'] 			= 	array();
		$MemArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');


		$MemArr['pager'] 				= 	array();
		$MemArr['pager']['total']		= 	$PowerBB->member->GetMemberNumber($arr);
		$MemArr['pager']['perpage'] 	= 	$PowerBB->_CONF['info_row']['subject_perpage'];
		$MemArr['pager']['count'] 		= 	$PowerBB->_GET['count'];
		$MemArr['pager']['location'] 	= 	'index.php?page=member&amp;control=1&amp;main=1';
		$MemArr['pager']['var'] 		= 	'count';

		$PowerBB->_CONF['template']['while']['MembersList'] = $PowerBB->core->GetList($MemArr,'member');
        if ($PowerBB->member->GetMemberNumber($arr) > $PowerBB->_CONF['info_row']['subject_perpage'])
        {
		$PowerBB->template->assign('pager',$PowerBB->pager->show());
        }
        $PowerBB->template->assign('MemberNumber',$PowerBB->member->GetMemberNumber($arr));
		$PowerBB->template->display('members_main');


	}

	function _MergeMain()
	{
		global $PowerBB;

		if (!in_array($PowerBB->_CONF['rows']['member_row']['id'], preg_split('#\s*,\s*#s', $PowerBB->superadministrators, -1, PREG_SPLIT_NO_EMPTY)))
		{
		 $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['dnot_have_permit']);
		}
		$PowerBB->template->display('merge_users');
	}

	function _MergeStart()
	{
		global $PowerBB;

		//////////

		$PowerBB->_POST['user_get'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['user_get'],'trim');
		$PowerBB->_POST['user_to'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['user_to'],'trim');

		//////////

		if (empty($PowerBB->_POST['user_get'])
			or empty($PowerBB->_POST['user_to']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_fill_in_all_the_information']);
		}

		if (!$PowerBB->member->IsMember(array('where' => array('username',$PowerBB->_POST['user_get']))))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['user_to_take_data_that_does_not_exist_in_the_database']);
		}

		if (!$PowerBB->member->IsMember(array('where' => array('username',$PowerBB->_POST['user_to']))))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['user_to_transfer_his_data_does_not_exist_in_the_database']);
		}

		//////////

		$MemArr 			= 	array();
		$MemArr['get'] 		= 	'*';
		$MemArr['where'] 	= 	array('username',$PowerBB->_POST['user_get']);

		$GetMemInfo = $PowerBB->core->GetInfo($MemArr,'member');

		unset($MemArr);

		$MemArr 			= 	array();
		$MemArr['get'] 		= 	'*';
		$MemArr['where'] 	= 	array('username',$PowerBB->_POST['user_to']);

		$ToMemInfo = $PowerBB->core->GetInfo($MemArr,'member');

		if ($GetMemInfo['usergroup'] == '4')
		{
           // not do any thing
 		}
		else
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['user_to_take_data_that_does_not_member_group']);
		}

		if ($ToMemInfo['usergroup'] == '4')
		{
           // not do any thing
		}
		else
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['user_to_get_data_that_does_not_member_group']);
		}
		//////////

		$UpdateSubjectArr 						= 	array();
		$UpdateSubjectArr['field'] 				= 	array();
		$UpdateSubjectArr['field']['writer'] 	= 	$ToMemInfo['username'];
		$UpdateSubjectArr['where'] 				= 	array('writer',$GetMemInfo['username']);

		$u_subject = $PowerBB->subject->UpdateSubject($UpdateSubjectArr);

		$UpdateReplyArr 					= 	array();
		$UpdateReplyArr['field'] 			= 	array();
		$UpdateReplyArr['field']['writer'] 	= 	$ToMemInfo['username'];
		$UpdateReplyArr['where'] 			= 	array('writer',$GetMemInfo['username']);

		$u_reply = $PowerBB->reply->UpdateReply($UpdateReplyArr);

		$UpdateMemberArr 						= 	array();
		$UpdateMemberArr['field'] 				= 	array();
		$UpdateMemberArr['field']['posts'] 		= 	$ToMemInfo['posts']+$GetMemInfo['posts'];
		$UpdateMemberArr['field']['visitor'] 	= 	$ToMemInfo['visitor']+$GetMemInfo['visitor'];
		$UpdateMemberArr['where'] 				= 	array('username',$ToMemInfo['username']);

		$u_member = $PowerBB->member->UpdateMember($UpdateMemberArr);


		// DELETE ALL PM
		$MemArr 			= 	array();
		$MemArr['where'] 	= 	array('id',$GetMemInfo['id']);

		$MemInfo = $PowerBB->core->GetInfo($MemArr,'member');

		 $username = $MemInfo['username'];
         $Delpm1 = $PowerBB->DB->sql_query("DELETE FROM " . $PowerBB->table['pm'] . " WHERE user_from = '$username' ");
         $Delpm2 = $PowerBB->DB->sql_query("DELETE FROM " . $PowerBB->table['pm'] . " WHERE user_to = '$username' ");

		$CacheArr 					= 	array();
		$CacheArr['field']			=	array();

		$CacheArr['field']['unread_pm'] 	= 	'0';
		$CacheArr['where'] 					= 	array('username',$MemInfo['username']);

		$Cache = $PowerBB->member->UpdateMember($CacheArr);

		$DelArr 			= 	array();
		$DelArr['where'] 	= 	array('id',$GetMemInfo['id']);

		$del = $PowerBB->member->DeleteMember($DelArr);


		if ($u_subject
			and $u_reply
			and $u_member
			and $del)
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['member_has_been_Merge_successfully']);
			$PowerBB->functions->redirect('index.php?page=member&control=1&main=1');
		}
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
			 $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['dnot_have_permit']);
			}

		}


		/////// getting extra fields for admin editing user
       $PowerBB->_CONF['template']['while']['extrafields']=$PowerBB->extrafield->getUserFields(true);

		//////////

		// Get styles list
		$StyleArr 							= 	array();
		$StyleArr['order']					=	array();
		$StyleArr['order']['field']			=	'id';
		$StyleArr['order']['type']			=	'DESC';

		$StyleArr['proc']					=	array();
		$StyleArr['*']						=	array('method'=>'clean','param'=>'html');

		// Store information in "StyleList"
		$PowerBB->_CONF['template']['while']['StyleList'] = $PowerBB->style->GetStyleList($StyleArr);

       // Get Language list
		$GetLangArr 						= 	array();

		$GetLangArr['order'] 				= 	array();
		$GetLangArr['order']['field'] 		= 	'id';
		$GetLangArr['order']['type'] 		= 	'DESC';

		$PowerBB->_CONF['template']['while']['LangList'] = $PowerBB->core->GetList($GetLangArr,'lang');

		$PowerBB->functions->CleanVariable($PowerBB->_CONF['template']['while']['LangList'],'html');
		//////////

		// Get groups list
		$GroupArr 							= 	array();
		if ($PowerBB->_CONF['rows']['member_row']['usergroup'] != '1')
		{
		 $GroupArr['where'] 					= 	array();
		 $GroupArr['where'][0] 				= 	array();
		 $GroupArr['where'][0]['name'] 		= 	'id';
		 $GroupArr['where'][0]['oper'] 		= 	'=';

		 $GroupArr['where'][0]['value']		= 	$PowerBB->_CONF['template']['Inf']['usergroup'];
        }
        else
		{
		 $GroupArr['where'] 					= 	array();
		 $GroupArr['where'][0] 				= 	array();
		 $GroupArr['where'][0]['name'] 		= 	'id';
		 $GroupArr['where'][0]['oper'] 		= 	'!=';

		 $GroupArr['where'][0]['value']		= 	'7';
        }
		$GroupArr['order']					=	array();
		$GroupArr['order']['field']			=	'id';
		$GroupArr['order']['type']			=	'ASC';

		$GroupArr['proc'] 					= 	array();
		$GroupArr['proc']['*'] 				= 	array('method'=>'clean','param'=>'html');

		// Store information in "GroupList"
		$PowerBB->_CONF['template']['while']['GroupList'] = $PowerBB->core->GetList($GroupArr,'group');

		//////////

		$MemArr 			= 	array();
		$MemArr['where'] 	= 	array('id',$PowerBB->_GET['id']);

		$MemInfo = $PowerBB->core->GetInfo($MemArr,'member');

		$ForumAdress = $PowerBB->functions->GetForumAdress();
        $ForumAdress = str_replace($PowerBB->admincpdir."/", '', $ForumAdress);
        $ForumAdress = str_replace($PowerBB->admincpdir, '', $ForumAdress);

       if($MemInfo['avater_path'] !='')
        {
          if(strstr($MemInfo['avater_path'],$ForumAdress))
          {
		     $MemInfo['avater_path'] = str_ireplace($ForumAdress.$ForumAdress,'',$MemInfo['avater_path']);
             $PowerBB->template->assign('avater_path',$MemInfo['avater_path']);
          }
          else
          {
		     $MemInfo['avater_path'] = str_ireplace("look/images/avatar",$ForumAdress."look/images/avatar",$MemInfo['avater_path']);
             $PowerBB->template->assign('avater_path',$MemInfo['avater_path']);

          }
        }

        if($MemInfo['profile_cover_photo'] !='')
        {
          if(strstr($MemInfo['profile_cover_photo'],$ForumAdress))
          {
          $PowerBB->template->assign('profile_cover_photo',$MemInfo['profile_cover_photo']);
          }
          else
          {
          $PowerBB->template->assign('profile_cover_photo',$ForumAdress.$MemInfo['profile_cover_photo']);
          }
        }

        eval($PowerBB->functions->get_fetch_hooks('member_edit'));

		$PowerBB->template->display('member_edit');

		//////////
	}

	function _EditStart()
	{
		global $PowerBB;

		$extraFields=$PowerBB->extrafield->getEmptyLoginFields();

		$MemInfo = false;

		$this->check_by_id($MemInfo);

		if (isset($PowerBB->_POST['membergroupids']))
		{
			if (is_array($PowerBB->_POST['membergroupids']))
			{

				$membergroupids = array();
				foreach ($PowerBB->_POST['membergroupids'] as $l)
				{
				 $membergroupids[] = intval($l);
				}

				if ( count( $membergroupids  ) )
				{
					foreach( $membergroupids  as $f )
					{
						if ( is_array($f) and count($f) )
						{
						$membergroupids  = array_merge( $membergroupids , $f );
						}
					}
				}

		     $member_group_ids = implode( ",", $membergroupids );
		   }
         }
         else
         {          $member_group_ids = '';
         }
		if (empty($PowerBB->_POST['email'])
			or !isset($PowerBB->_POST['posts']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_fill_in_all_the_information']);
		}

       $PowerBB->_POST['username'] = $PowerBB->functions->CleanVariable($PowerBB->_POST['username'],'trim');
       $PowerBB->_POST['new_username'] = $PowerBB->functions->CleanVariable($PowerBB->_POST['new_username'],'trim');
       $PowerBB->_POST['email'] = $PowerBB->functions->CleanVariable($PowerBB->_POST['email'],'trim');
       $PowerBB->_POST['avater_path'] = $PowerBB->functions->CleanVariable($PowerBB->_POST['avater_path'],'trim');
       $PowerBB->_POST['website'] = $PowerBB->functions->CleanVariable($PowerBB->_POST['website'],'trim');
       $PowerBB->_POST['away_msg'] = $PowerBB->functions->CleanVariable($PowerBB->_POST['away_msg'],'trim');
       $PowerBB->_POST['year'] = $PowerBB->functions->CleanVariable($PowerBB->_POST['year'],'trim');
       $PowerBB->_POST['user_info'] = $PowerBB->functions->CleanVariable($PowerBB->_POST['user_info'],'trim');
       $PowerBB->_POST['ip'] = $PowerBB->functions->CleanVariable($PowerBB->_POST['ip'],'trim');
       $PowerBB->_POST['profile_cover_photo'] = $PowerBB->functions->CleanVariable($PowerBB->_POST['profile_cover_photo'],'trim');

		if (!$PowerBB->functions->CheckEmail($PowerBB->_POST['email']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_enter_a_valid_email']);
		}

       if($PowerBB->_POST['new_username'] != $PowerBB->_POST['username'])
       {
	       if ($PowerBB->member->IsMember(array('where' => array('username',$PowerBB->_POST['new_username']))))
	       {
	          $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['please_choose_another_name']);
	       }
       }

		if ($MemInfo['usergroup'] == '1')
		{
			if (!in_array($PowerBB->_CONF['rows']['member_row']['id'], preg_split('#\s*,\s*#s', $PowerBB->superadministrators, -1, PREG_SPLIT_NO_EMPTY)))
			{
			 $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['dnot_have_permit']);
			}

		}

		if ($PowerBB->_POST['username'] == 'Guest')
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['You_can_not_register_this_name']);
		}

		if ($PowerBB->_POST['new_username'] == 'Guest')
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['You_can_not_register_this_name']);
		}

	    if (strstr($PowerBB->_POST['new_username'],'"')
		or strstr($PowerBB->_POST['new_username'],"'")
		or strstr($PowerBB->_POST['new_username'],'>')
		or strstr($PowerBB->_POST['new_username'],'<')
		or strstr($PowerBB->_POST['new_username'],'*')
		or strstr($PowerBB->_POST['new_username'],'%')
		or strstr($PowerBB->_POST['new_username'],'$')
		or strstr($PowerBB->_POST['new_username'],'#')
		or strstr($PowerBB->_POST['new_username'],'+')
		or strstr($PowerBB->_POST['new_username'],'^')
		or strstr($PowerBB->_POST['new_username'],'&')
		or strstr($PowerBB->_POST['new_username'],',')
		or strstr($PowerBB->_POST['new_username'],'~')
		or strstr($PowerBB->_POST['new_username'],'@')
		or strstr($PowerBB->_POST['new_username'],'!')
		or strstr($PowerBB->_POST['new_username'],'{')
		or strstr($PowerBB->_POST['new_username'],'}')
		or strstr($PowerBB->_POST['new_username'],'(')
		or strstr($PowerBB->_POST['new_username'],')')
		or strstr($PowerBB->_POST['new_username'],'/'))
      	{
      		$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['You_can_not_register_this_name']);
      	}

		//////////

		$username = (!empty($PowerBB->_POST['new_username'])) ? $PowerBB->_POST['new_username'] : $MemInfo['username'];

		//////////

		// If the admin change the group of this member, so we should change the cache of username style

			$GrpArr 			= 	array();
			$GrpArr['where'] 	= 	array('id',$PowerBB->_POST['usergroup']);

			$GroupInfo = $PowerBB->core->GetInfo($GrpArr,'group');

			$style = $GroupInfo['username_style'];
			$style = str_replace('[username]',$username,$style);

		$MemberArr 			= 	array();
		$MemberArr['where'] 	= 	array('username',$username);

		$MemberInfo = $PowerBB->core->GetInfo($MemberArr,'member');


		if ($MemInfo['usergroup'] == '1')
		{
			if (!in_array($PowerBB->_CONF['rows']['member_row']['id'], preg_split('#\s*,\s*#s', $PowerBB->superadministrators, -1, PREG_SPLIT_NO_EMPTY)))
			{
			 $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['dnot_have_permit']);
			}

		}

		if ($PowerBB->_CONF['rows']['member_row']['usergroup'] != '1')
		{
			if ($GroupInfo['group_mod']
			or $GroupInfo['vice']
			or $GroupInfo['admincp_allow'])
			{
			$PowerBB->_POST['usergroup']             =     $MemInfo['usergroup'];
			}

			$Grp_rems_Arr             =     array();
			$Grp_rems_Arr['where']     =     array('id',$MemInfo['usergroup']);

			$Group_rems_Info = $PowerBB->core->GetInfo($Grp_rems_Arr,'group');

			if ($Group_rems_Info['group_mod']
			or $Group_rems_Info['vice']
			or $Group_rems_Info['admincp_allow'])
			{
			 $PowerBB->_POST['usergroup']             =     $MemInfo['usergroup'];
			}

		}

        if ($MemberInfo['user_title'] == $PowerBB->_POST['user_title'])
		{
          $user_title = $GroupInfo['user_title'];
		}
   		else
   		{
     	 $user_title = $PowerBB->_POST['user_title'];
        }
		//////////
        $password_fields = $PowerBB->functions->create_password($PowerBB->_POST['new_password'], false);

		$UpdateArr 				= 	array();
		$UpdateArr['field'] 	= 	array();

		$UpdateArr['field']['username'] 			= 	$username;
		$UpdateArr['field']['password'] 			= 	(!empty($PowerBB->_POST['new_password'])) ? $password_fields['password'] : $MemInfo['password'];
		$UpdateArr['field']['active_number'] 		= 	(!empty($PowerBB->_POST['new_password'])) ? $password_fields['salt'] : $MemInfo['active_number'];
		$UpdateArr['field']['email'] 				= 	$PowerBB->_POST['email'];
		$UpdateArr['field']['user_gender'] 			= 	$PowerBB->_POST['gender'];
		$UpdateArr['field']['style'] 				= 	$PowerBB->_POST['style'];
		$UpdateArr['field']['lang'] 				= 	$PowerBB->_POST['lang'];
		$UpdateArr['field']['avater_path'] 			= 	$PowerBB->_POST['avater_path'];
		$UpdateArr['field']['profile_cover_photo']  = 	$PowerBB->_POST['profile_cover_photo'];
		$UpdateArr['field']['user_info'] 			= 	$PowerBB->_POST['user_info'];
		$UpdateArr['field']['user_title'] 			= 	$PowerBB->_POST['user_title'];
		$UpdateArr['field']['posts'] 				= 	$PowerBB->_POST['posts'];
		$UpdateArr['field']['user_website'] 		= 	$PowerBB->_POST['website'];
		$UpdateArr['field']['user_country'] 		= 	$PowerBB->_POST['user_country'];
		$UpdateArr['field']['usergroup'] 			= 	$PowerBB->_POST['usergroup'];
		$UpdateArr['field']['membergroupids'] 		= 	$member_group_ids;
		$UpdateArr['field']['review_subject'] 		= 	$PowerBB->_POST['review_subject'];
		$UpdateArr['field']['review_reply'] 		= 	$PowerBB->_POST['review_reply'];
		$UpdateArr['field']['away'] 		= 	$PowerBB->_POST['away'];
		$UpdateArr['field']['away_msg'] 		= 	$PowerBB->_POST['away_msg'];
		$UpdateArr['field']['send_security_code'] 		    = 	$PowerBB->_POST['send_security_code'];
		$UpdateArr['field']['send_security_error_login']  = 	$PowerBB->_POST['send_security_error_login'];
		$UpdateArr['field']['user_sig'] 		    = 	$PowerBB->_POST['user_sig'];
		$UpdateArr['field']['username_style_cache']	=	$style;
        $UpdateArr['field']['hide_online']          =    $PowerBB->_POST['hide_online'];
        $UpdateArr['field']['user_time']            =    $PowerBB->_POST['user_time'];
        $UpdateArr['field']['send_allow']           =    $PowerBB->_POST['send_allow'];
        $UpdateArr['field']['pm_emailed']           =    $PowerBB->_POST['pm_emailed'];
        $UpdateArr['field']['pm_window']            =    $PowerBB->_POST['pm_window'];
        $UpdateArr['field']['visitormessage']       =    $PowerBB->_POST['visitormessage'];
		$UpdateArr['field']['member_ip']           	=	$PowerBB->_POST['ip'];
		$UpdateArr['field']['warnings']           	=	$PowerBB->_POST['warnings'];
        $UpdateArr['field']['reputation']           =    $PowerBB->_POST['reputation'];
		$UpdateArr['field']['bday_day'] 		    = 	$PowerBB->_POST['day'];
		$UpdateArr['field']['bday_month'] 	        = 	$PowerBB->_POST['month'];
		$UpdateArr['field']['bday_year'] 	        = 	$PowerBB->_POST['year'];
        eval($PowerBB->functions->get_fetch_hooks('arr_update'));
		//extra fields insertion
       foreach($extraFields AS $field){
       $UpdateArr['field'][ $field['name_tag'] ]     =   $PowerBB->_POST[ $field['name_tag'] ];
        }
		$UpdateArr['where']					    	 =	array('id',$MemInfo['id']);

		$update = $PowerBB->core->Update($UpdateArr,'member');

		$onlineArr 			= 	array();
		$onlineArr['where'] 	= 	array('user_id',$MemInfo['id']);

		$TodayInfo = $PowerBB->online->GetTodayInfo($onlineArr);
		$onlineInfo = $PowerBB->online->OnlineInfo($onlineArr);
       if (!empty($PowerBB->_POST['new_username']))
        {
          // TODO;;;
          // Don't forget the cache of username style here
            $oldusername = $PowerBB->_POST['username'];
            $Todayusername = $TodayInfo['username'];
            $onlineusername = $onlineInfo['username'];
            $update1 = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['subject'] . " SET last_replier='" . $username . "' WHERE last_replier='" . $oldusername . "'");
            $update2 = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['subject'] . " SET lastreply_cache='" . $username . "' WHERE lastreply_cache='" . $oldusername . "'");
            $update3 = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['subject'] . " SET writer='" . $username . "' WHERE writer='" . $oldusername . "'");
            $update4 = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['reply'] . " SET writer='" . $username . "' WHERE writer='" . $oldusername . "'");
            $update5 = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['section'] . " SET last_subject='" . $username . "' WHERE last_subject='" . $oldusername . "'");
            $update6 = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['section'] . " SET last_writer='" . $username . "' WHERE last_writer='" . $oldusername . "'");
            $update7 = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['pm'] . " SET user_from='" . $username . "' WHERE user_from='" . $oldusername . "'");
            $update8 = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['pm'] . " SET user_to='" . $username . "' WHERE user_to='" . $oldusername . "'");
            $update9 = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['online'] . " SET username='" . $username . "' WHERE username='" . $oldusername . "'");
            $update10 = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['online'] . " SET username_style='" . $style . "' WHERE username='" . $username . "'");
            $update11 = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['today'] . " SET username='" . $username . "' WHERE username='" . $oldusername . "'");
            $update12 = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['today'] . " SET username_style='" . $style . "' WHERE username='" . $username . "'");
            $update13 = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['reputation'] . " SET username='" . $username . "' WHERE username='" . $oldusername . "'");
            $update14 = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['reputation'] . " SET by_username='" . $username . "' WHERE by_username='" . $oldusername . "'");
            $update15 = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['friends'] . " SET username='" . $username . "' WHERE username='" . $oldusername . "'");
            $update16 = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['friends'] . " SET username_friend='" . $username . "' WHERE username_friend='" . $oldusername . "'");
            $update17 = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['visitormessage'] . " SET postusername='" . $username . "' WHERE postusername='" . $oldusername . "'");
            $update18 = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['chat'] . " SET username='" . $username . "' WHERE username='" . $oldusername . "'");
            $update19 = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['award'] . " SET username='" . $username . "' WHERE username='" . $oldusername . "'");
            $update20 = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['moderators'] . " SET username='" . $username . "' WHERE username='" . $oldusername . "'");
            $update21 = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['rating'] . " SET username='" . $username . "' WHERE username='" . $oldusername . "'");
            $update22 = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['rating'] . " SET by_username='" . $username . "' WHERE by_username='" . $oldusername . "'");
            $update23 = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['requests'] . " SET username='" . $username . "' WHERE username='" . $oldusername . "'");
            $update24 = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['supermemberlogs'] . " SET username='" . $username . "' WHERE username='" . $oldusername . "'");
            $update25 = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['vote'] . " SET username='" . $username . "' WHERE username='" . $oldusername . "'");
            $update26 = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['announcement'] . " SET writer='" . $username . "' WHERE writer='" . $oldusername . "'");
            $update27 = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['subject'] . " SET action_by='" . $username . "' WHERE action_by='" . $oldusername . "'");
            $update28 = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['reply'] . " SET action_by='" . $username . "' WHERE action_by='" . $oldusername . "'");
            eval($PowerBB->functions->get_fetch_hooks('username_update'));

          	if ($update1
			or $update2
			or $update3
			or $update4
			or $update5
			or $update6
			or $update7
			or $update8
			or $update9
			or $update10
			or $update11
			or $update12
            or $update13
            or $update14
            or $update15
            or $update16
            or $update17
            or $update18
            or $update19
            or $update20
            or $update21
            or $update22
            or $update23
            or $update24
            or $update25
            or $update26
            or $update27
            or $update28)
		  {
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['member_has_been_updated_successfully']);
			$PowerBB->functions->redirect('index.php?page=member&amp;edit=1&amp;main=1&amp;id='.$MemInfo['id']);
			}
		}


	}

	function _DelMain()
	{
		global $PowerBB;

		$PowerBB->_CONF['template']['Inf'] = false;

		$this->check_by_id($PowerBB->_CONF['template']['Inf']);

		$PowerBB->template->display('member_del');
	}

	function _DelStart()
	{
		global $PowerBB;

		$PowerBB->_CONF['template']['Inf'] = false;

		$this->check_by_id($PowerBB->_CONF['template']['Inf']);

		if ($PowerBB->_GET['id'] == 1)
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Can_not_delete_the_board_administrator']);
		}

		// DELETE ALL PM
		$MemArr 			= 	array();
		$MemArr['where'] 	= 	array('id',$PowerBB->_GET['id']);

		$MemInfo = $PowerBB->core->GetInfo($MemArr,'member');

		$username = $MemInfo['username'];
         $Delpm1 = $PowerBB->DB->sql_query("DELETE FROM " . $PowerBB->table['pm'] . " WHERE user_from = '$username' ");
         $Delpm2 = $PowerBB->DB->sql_query("DELETE FROM " . $PowerBB->table['pm'] . " WHERE user_to = '$username' ");

		$CacheArr 					= 	array();
		$CacheArr['field']			=	array();

		$CacheArr['field']['unread_pm'] 	= 	'0';
		$CacheArr['where'] 					= 	array('username',$MemInfo['username']);

		$Cache = $PowerBB->member->UpdateMember($CacheArr);


		// Delete Replys from database
		$DelReplyArr				=	array();
		$DelReplyArr['where'] 	= 	array('writer',$MemInfo['username']);

		$delReply = $PowerBB->core->Deleted($DelReplyArr,'reply');

		// Delete subjects from database
		$DeleteSubjectArr				=	array();
		$DeleteSubjectArr['where'] 	= 	array('writer',$MemInfo['username']);
		$delSubject = $PowerBB->subject->DeleteSubject($DeleteSubjectArr);

		// Delete Visitor Message from database
		$DelVisitorMessage 							= 	array();
        $DelVisitorMessage['where'] 		    	= 	array('postusername',$MemInfo['username']);
		$DeletedVisitorMessage = $PowerBB->core->Deleted($DelVisitorMessage,'visitormessage');

		// Delete Friends from database
		$Del1Arr 			= 	array();
		$Del1Arr['where'] 	= 	array('username_friend',$MemInfo['username']);

		$del1Friend = $PowerBB->core->Deleted($Del1Arr,'friends');

		// Delete attachments from database
		$DelAttachArr				=	array();
		$DelAttachArr['where'] 	= 	array('u_id',$PowerBB->_GET['id']);

		$DeleteAttach = $PowerBB->core->Deleted($DelAttachArr,'attach');

		$DelArr 			= 	array();
		$DelArr['where'] 	= 	array('id',$PowerBB->_GET['id']);

		$del = $PowerBB->member->DeleteMember($DelArr);

		if ($del)
		{
            $Update = $PowerBB->section->UpdateAllSectionsCache();
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['member_has_been_deleted_successfully']);
			$PowerBB->functions->redirect('index.php?page=member&amp;control=1&amp;main=1');
		}
	}

	function _SearchMain()
	{
		global $PowerBB;

		$PowerBB->template->display('member_search_main');
	}

	function _SearchStart()
	{
		global $PowerBB;

		if (empty($PowerBB->_POST['keyword']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_fill_in_all_the_information']);
		}

       $PowerBB->_POST['keyword'] = $PowerBB->functions->CleanVariable($PowerBB->_POST['keyword'],'trim');

		$MemInfoArr 				= 	array();
		$MemInfoArr['get_from'] 	= 	'db';
		$MemInfoArr['where'] 		= 	array();

		$MemInfoArr['where'][0] 			= 	array();
		if ($PowerBB->_POST['search_by'] == 'username')
		{
			$MemInfoArr['where'][0]['name'] = 'username';
		}
		elseif ($PowerBB->_POST['search_by'] == 'member_ip')
		{
			$MemInfoArr['where'][0]['name'] = 'member_ip';
           $PowerBB->_POST['keyword'] = '%' .$PowerBB->_POST['keyword'] .'%';
		}
		elseif ($PowerBB->_POST['search_by'] == 'email')
		{
			$MemInfoArr['where'][0]['name'] = 'email';
		}
		else
		{
			$MemInfoArr['where'][0]['name'] = 'id';
		}
		$MemInfoArr['where'][0]['oper'] 	= 	'LIKE';
		$MemInfoArr['where'][0]['value'] 	= 	$PowerBB->_POST['keyword'];
		$PowerBB->_CONF['template']['while']['MembersList'] = $PowerBB->core->GetList($MemInfoArr,'member');

		if ($PowerBB->_CONF['template']['while']['MembersList'] == false)
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['No_results']);
		}

		$PowerBB->functions->CleanVariable($PowerBB->_CONF['template']['while']['MembersList'],'html');

		$PowerBB->template->display('member_search_result');
	}

	function _WarningsMain()
	{
		global $PowerBB;
		$MemArr 				= 	array();
		$MemArr['proc'] 			= 	array();
		$MemArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');
		$MemArr['where']					=	array();
		$MemArr['where'][0]				=	array();
		$MemArr['where'][0]['name']		=	'warnings';
		$MemArr['where'][0]['oper']		=	'>';
		$MemArr['where'][0]['value']		=	'0';
		$MemArr['order']			=	array();
		$MemArr['order']['field']	=	'id';
		$MemArr['order']['type']	=	'DESC';
		$PowerBB->_CONF['template']['while']['WarnedMembersList'] = $PowerBB->core->GetList($MemArr,'member');
		$PowerBB->template->display('warnings_main');
	}
	function _WarningsDel()
	{
		global $PowerBB;

		$GrpArr 			= 	array();
		$GrpArr['where'] 	= 	array('id','4');

		$GroupInfo = $PowerBB->core->GetInfo($GrpArr,'group');

		$MemArr 			= 	array();
		$MemArr['where'] 	= 	array('id',$PowerBB->_GET['id']);

		$MemInfo = $PowerBB->core->GetInfo($MemArr,'member');

		$style = $GroupInfo['username_style'];
		$username_style_cache = str_replace('[username]',$MemInfo['username'],$style);

		$UpdateArr 				= 	array();
		$UpdateArr['field'] 	= 	array();

		$UpdateArr['field']['usergroup'] 		= 	'4';
		$UpdateArr['field']['username_style_cache'] 		= 	$username_style_cache;
		$UpdateArr['field']['user_title'] 		= 	$PowerBB->_CONF['template']['_CONF']['lang']['Member'];
		$UpdateArr['field']['warnings'] 		= 	0;
		$UpdateArr['where']		=	array('id',$PowerBB->_GET['id']);
		$update = $PowerBB->core->Update($UpdateArr,'member');

		if ($update)
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['member_has_been_updated_successfully']);
			$PowerBB->functions->redirect('index.php?page=member&amp;warnings=1&amp;main=1');
		}

	}



	function _ActiveMemberMain()
	{
		global $PowerBB;
		$PowerBB->_GET['count'] = (!isset($PowerBB->_GET['count'])) ? 0 : $PowerBB->_GET['count'];
		$PowerBB->_GET['count'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['count'],'intval');

		$WaitingMemberArr 				= 	array();
		$WaitingMemberArr['get_from'] 	= 	'db';
		$WaitingMemberArr['where'] 		= 	array();

		$WaitingMemberArr['where'][0] 			= 	array();
		$WaitingMemberArr['where'][0]['name'] 	= 	'usergroup';
		$WaitingMemberArr['where'][0]['oper'] 	= 	'=';
		$WaitingMemberArr['where'][0]['value'] 	= 	'5';


		$MemArr 					= 	array();
		$MemArr['where'] 				= 	array();
		$MemArr['where'][0] 			= 	array();
		$MemArr['where'][0]['name'] 	= 	'usergroup';
		$MemArr['where'][0]['oper'] 	= 	'=';
		$MemArr['where'][0]['value'] 	= 	'5';

		$MemArr['order']			=	array();
		$MemArr['order']['field']	=	'id';
		$MemArr['order']['type']	=	'DESC';
		$MemArr['proc'] 			= 	array();
		$MemArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');


		$MemArr['pager'] 				= 	array();
		$MemArr['pager']['total']		= 	$PowerBB->member->GetMemberNumber($WaitingMemberArr);
		$MemArr['pager']['perpage'] 	= 	$PowerBB->_CONF['info_row']['subject_perpage'];
		$MemArr['pager']['count'] 		= 	$PowerBB->_GET['count'];
		$MemArr['pager']['location'] 	= 	'index.php?page=member&active_member=1&main=1';
		$MemArr['pager']['var'] 		= 	'count';


		$PowerBB->_CONF['template']['while']['MembersList'] = $PowerBB->core->GetList($MemArr,'member');
       if ($PowerBB->member->GetMemberNumber($WaitingMemberArr) > $PowerBB->_CONF['info_row']['subject_perpage'])
        {
		$PowerBB->template->assign('pager',$PowerBB->pager->show());
        }
        $PowerBB->template->assign('MemberNumber',$PowerBB->member->GetMemberNumber($WaitingMemberArr));

		$PowerBB->template->display('active_users');


	}

	function _ActiveMemberStart()
	{
		global $PowerBB;

		//////////

		if (empty($PowerBB->_POST['check']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['You_do_not_select_any_member_of_the_deletion']);
		}


       $Active_M = $PowerBB->_POST['check'];


       foreach ($Active_M as $ActiveMember)
       {

	       	$MemArr 			= 	array();
			$MemArr['get'] 		= 	'*';
			$MemArr['where'] 	= 	array('id',$ActiveMember);

			$MemInfo = $PowerBB->core->GetInfo($MemArr,'member');

				$GrpArr 			= 	array();
				$GrpArr['where'] 	= 	array('id',4);

				$GroupInfo = $PowerBB->core->GetInfo($GrpArr,'group');

			$style = $GroupInfo['username_style'];
			$style = str_replace('[username]',$MemInfo['username'],$style);

				// Start Active Member
				$UpdateArr 				= 	array();
				$UpdateArr['field'] 	= 	array();
				$UpdateArr['field']['usergroup'] 			= 	'4';
				$UpdateArr['field']['user_title'] 			= 	$GroupInfo['user_title'];
		        $UpdateArr['field']['username_style_cache']	=	$style;
		        $UpdateArr['where'] 		    	= 	array('id',intval($ActiveMember));

				$update = $PowerBB->core->Update($UpdateArr,'member');


		$update = $PowerBB->core->Update($UpdateArr,'member');
		// UPDATE username_style today
		$update_username_style_today = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['today'] . " SET username_style='" . $style . "' WHERE user_id='" . $MemInfo['id'] . "'");
		// UPDATE username_style online
		$update_username_style_online = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['online'] . " SET username_style='" . $style . "' WHERE user_id='" . $MemInfo['id'] . "'");


       }


		if ($update)
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['members_has_been_active_successfully']);
			$PowerBB->functions->redirect('index.php?page=member&amp;active_member=1&amp;main=1');
		}

	}


}

class _functions
{
	function check_by_id(&$MemInfo)
	{
		global $PowerBB;



		$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');


		 $CatArr = $PowerBB->DB->sql_query("SELECT  *   FROM " . $PowerBB->table['member'] . " WHERE id = ".$PowerBB->_GET['id']." ");
		 $MemInfo = $PowerBB->DB->sql_fetch_array($CatArr);

		if ($MemInfo == false)
		{

		 $CatArr = $PowerBB->DB->sql_query("SELECT  *   FROM " . $PowerBB->table['member'] . " WHERE username = ".$PowerBB->_GET['username']." ");
		 $MemInfo = $PowerBB->DB->sql_fetch_array($CatArr);

		}



		if ($MemInfo == false)
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['member_requested_does_not_exist']);
		}

		$PowerBB->functions->CleanVariable($MemInfo,'html');
		$PowerBB->functions->CleanVariable($MemInfo,'sql');
	}
}

?>
