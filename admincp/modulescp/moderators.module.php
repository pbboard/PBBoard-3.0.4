<?php

(!defined('IN_PowerBB')) ? die() : '';

define('IN_ADMIN',true);

$CALL_SYSTEM				=	array();
$CALL_SYSTEM['MODERATORS'] 	= 	true;
$CALL_SYSTEM['SECTION'] 	= 	true;
$CALL_SYSTEM['SUPERMEMBERLOGS'] 			= 	true;



define('CLASS_NAME','PowerBBModeratorsMOD');

include('../common.php');
class PowerBBModeratorsMOD extends _functions
{
	function run()
	{
		global $PowerBB;

		if ($PowerBB->_CONF['member_permission'])
		{
			$PowerBB->template->display('header');

			if ($PowerBB->_CONF['rows']['group_info']['admincp_admin'] == '0')
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
				elseif ($PowerBB->_GET['section'])
				{
					$this->_ControlSection();
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
					$this->_DelStart();
			}
			elseif ($PowerBB->_GET['modaction'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_ModActionMain();
				}
				elseif ($PowerBB->_GET['del_all'])
				{
					$this->_DelAllStart();
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

					// Show Jump List to:)
		$result = $PowerBB->DB->sql_query("SELECT id,title,parent FROM " . $PowerBB->table['section'] . " ORDER BY id ASC");

		$Master = array();
		while ($row = $PowerBB->DB->sql_fetch_array($result)) {
			extract($row);
		    $Master = $PowerBB->section->GetSectionsList(array ('id'=>$id,'title'=>"".$title."",'parent'=>$parent."",'parent'=>$parent));
		    $PowerBB->_CONF['template']['foreach']['SecList'] = $PowerBB->section->GetSectionsList($Master);
		}

		$MainAndSub = new PowerBBCommon;
          	$PowerBB->template->assign('DoJumpList',$MainAndSub->DoJumpList($Master,$url,1));
		unset($Master);
	   ////////

		//////////

		$GroupArr 							= 	array();

		$GroupArr['where'] 					= 	array();
		$GroupArr['where'][0] 				= 	array();
		$GroupArr['where'][0]['name'] 		= 	'group_mod';
		$GroupArr['where'][0]['oper'] 		= 	'=';
		$GroupArr['where'][0]['value']		= 	1;

		$GroupArr['order'] 					= 	array();
		$GroupArr['order']['field'] 		= 	'group_order';
		$GroupArr['order']['type'] 			= 	'ASC';

		$PowerBB->_CONF['template']['while']['GroupList'] = $PowerBB->core->GetList($GroupArr,'group');

		//////////

		$PowerBB->template->display('moderator_add');
	}

	function _AddStart()
	{
		global $PowerBB;

		if (empty($PowerBB->_POST['username'])
			or empty($PowerBB->_POST['section']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}

		$CheckArr 					= 	array();
		$CheckArr['username'] 		= 	$PowerBB->_POST['username'];
		$CheckArr['section_id'] 	= 	$PowerBB->_POST['section'];

		$IsModerator = $PowerBB->moderator->IsModerator($CheckArr);

		if ($IsModerator)
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['You_can_not_add_the_same_member_of_the_section_supervisor_twice']);
		}


		$SecArr 			= 	array();
		$SecArr['where'] 	= 	array('id',$PowerBB->_POST['section']);

		$SectionInfo = $PowerBB->core->GetInfo($SecArr,'section');


		if ($SectionInfo == false)
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Section_requested_does_not_exist']);
		}

		       $PowerBB->functions->CleanVariable($SectionInfo,'html');

		 		$MemberArr 			= 	array();
				$MemberArr['get']	= 	'*';

				$MemberArr['where']	=	array();

				$MemberArr['where'][0]				=	array();
				$MemberArr['where'][0]['name']		=	'username';
				$MemberArr['where'][0]['oper']		=	'=';
				$MemberArr['where'][0]['value']		=	$PowerBB->_POST['username'];

				$Member = $PowerBB->core->GetInfo($MemberArr,'member');

				if ($Member == false)
				{
					$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['member_requested_does_not_exist']);
				}


			$ModArr 			= 	array();
			$ModArr['field']	=	array();

			$ModArr['field']['username'] 	= 	$PowerBB->_POST['username'];
			$ModArr['field']['section_id'] 	= 	$PowerBB->_POST['section'];
			$ModArr['field']['member_id'] 	= 	$Member['id'];

			$insert = $PowerBB->moderator->InsertModerator($ModArr);

			//////////
			$CacheArr 			= 	array();
			$CacheArr['where'] 	= 	array('section_id',$PowerBB->_POST['section']);

			$cache = $PowerBB->moderator->CreateModeratorsCache($CacheArr);


			$SecArr 				= 	array();
			$SecArr['field']		=	array();

			$SecArr['field']['moderators'] 	= 	$cache;
			$SecArr['where'] 			=	array('id',$PowerBB->_POST['section']);

			$update = $PowerBB->core->Update($SecArr,'section');
			//////////
           	$UpdateSectionCache = $PowerBB->functions->UpdateSectionCache($PowerBB->_POST['section']);

			$idSection = $PowerBB->_POST['section'];
			$GetALLSection = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['section'] . " WHERE parent = '$idSection' ");

			while ($Sections = $PowerBB->DB->sql_fetch_array($GetALLSection))
			{
				$CheckArr 					= 	array();
				$CheckArr['username'] 		= 	$PowerBB->_POST['username'];
				$CheckArr['section_id'] 	= 	$Sections['id'];

				$IsModerator = $PowerBB->moderator->IsModerator($CheckArr);

				if ($IsModerator)
				{
				$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['You_can_not_add_the_same_member_of_the_section_supervisor_twice']);
				}

				$ModArr 			= 	array();
				$ModArr['field']	=	array();

				$ModArr['field']['username'] 	= 	$PowerBB->_POST['username'];
				$ModArr['field']['section_id'] 	= 	$Sections['id'];
				$ModArr['field']['member_id'] 	= 	$Member['id'];

				$insert = $PowerBB->moderator->InsertModerator($ModArr);


				//////////
				$CacheArr 			= 	array();
				$CacheArr['where'] 	= 	array('section_id',$Sections['id']);

				$cache = $PowerBB->moderator->CreateModeratorsCache($CacheArr);

				$SecArr 				= 	array();
				$SecArr['field']		=	array();

				$SecArr['field']['moderators'] 	= 	$cache;
				$SecArr['where'] 			=	array('id',$Sections['id']);

				$update = $PowerBB->core->Update($SecArr,'section');
				//////////
	             $UpdateSectionCache1 = $PowerBB->functions->UpdateSectionCache($Sections['id']);

				$SectionParentInfo = $Sections['id'];
				$GetParentInfo = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['section'] . " WHERE parent = '$SectionParentInfo' ");

			   while ($Parent = $PowerBB->DB->sql_fetch_array($GetParentInfo))
			   {
					//////////
					$CacheArr 			= 	array();
					$CacheArr['where'] 	= 	array('section_id',$Parent['id']);

					$cache = $PowerBB->moderator->CreateModeratorsCache($CacheArr);

					$SecArr 				= 	array();
					$SecArr['field']		=	array();

					$SecArr['field']['moderators'] 	= 	$cache;
					$SecArr['where'] 			=	array('id',$Parent['id']);

					$update = $PowerBB->core->Update($SecArr,'section');
					//////////

					$ModArr 			= 	array();
					$ModArr['field']	=	array();

					$ModArr['field']['username'] 	= 	$PowerBB->_POST['username'];
					$ModArr['field']['section_id']  = 	$Parent['id'];
					$ModArr['field']['member_id'] 	= 	$Member['id'];

					$insert = $PowerBB->moderator->InsertModerator($ModArr);

				    //////////

					$CacheArr 			= 	array();
					$CacheArr['where'] 	= 	array('section_id',$Parent['id']);

					$cache = $PowerBB->moderator->CreateModeratorsCache($CacheArr);

				     //////////

					$SecArr 				= 	array();
					$SecArr['field']		=	array();

					$SecArr['field']['moderators'] 	= 	$cache;
					$SecArr['where'] 			=	array('id',$Parent['id']);

					$update = $PowerBB->core->Update($SecArr,'section');
		            $UpdateSectionCache2 = $PowerBB->functions->UpdateSectionCache($Parent['id']);



						$SectionParent2Info = $Parent['id'];
						$GetParent2Info = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['section'] . " WHERE parent = '$SectionParent2Info' ");

					   while ($Parent2 = $PowerBB->DB->sql_fetch_array($GetParent2Info))
					   {
							//////////
							$CacheArr 			= 	array();
							$CacheArr['where'] 	= 	array('section_id',$Parent2['id']);

							$cache = $PowerBB->moderator->CreateModeratorsCache($CacheArr);

							$SecArr 				= 	array();
							$SecArr['field']		=	array();

							$SecArr['field']['moderators'] 	= 	$cache;
							$SecArr['where'] 			=	array('id',$Parent2['id']);

							$update = $PowerBB->core->Update($SecArr,'section');
							//////////

							$ModArr 			= 	array();
							$ModArr['field']	=	array();

							$ModArr['field']['username'] 	= 	$PowerBB->_POST['username'];
							$ModArr['field']['section_id']  = 	$Parent2['id'];
							$ModArr['field']['member_id'] 	= 	$Member['id'];

							$insert = $PowerBB->moderator->InsertModerator($ModArr);

						    //////////

							$CacheArr 			= 	array();
							$CacheArr['where'] 	= 	array('section_id',$Parent2['id']);

							$cache = $PowerBB->moderator->CreateModeratorsCache($CacheArr);

						     //////////

							$SecArr 				= 	array();
							$SecArr['field']		=	array();

							$SecArr['field']['moderators'] 	= 	$cache;
							$SecArr['where'] 			=	array('id',$Parent2['id']);

							$update = $PowerBB->core->Update($SecArr,'section');
				            $UpdateSectionCache2 = $PowerBB->functions->UpdateSectionCache($Parent2['id']);

		               }
                  }

			}



			$GroupArr 			= 	array();
			$GroupArr['where'] 	= 	array('id',$Member['usergroup']);

			$Group = $PowerBB->group->GetGroupInfo($GroupArr);

			// If the user isn't admin, so change the group
			if (!$Group['admincp_allow']
			and !$Group['vice'])
			{
			$ChangeArr 					= 	array();
			$ChangeArr['field']			=	array();

			$ChangeArr['field']['usergroup']	=	$PowerBB->_POST['group'];

			if (!empty($PowerBB->_POST['usertitle']))
			{
			$ChangeArr['field']['user_title'] = $PowerBB->_POST['usertitle'];
			}
			else
			{
			$ChangeArr['field']['user_title'] = $PowerBB->_CONF['template']['_CONF']['lang']['moderator_On_Forum'] . $SectionInfo['title'];
			}

			$ChangeArr['where'] 				= 	array('id',$Member['id']);

			$change = $PowerBB->member->UpdateMember($ChangeArr);

			/////////////
			$MemberArr 			= 	array();
			$MemberArr['where'] 	= 	array('username',$PowerBB->_POST['username']);

			$MemberInfo = $PowerBB->core->GetInfo($MemberArr,'member');

			$GrpArr 			= 	array();
			$GrpArr['where'] 	= 	array('id',$PowerBB->_POST['group']);

			$GroupInfo = $PowerBB->core->GetInfo($GrpArr,'group');

			$style = $GroupInfo['username_style'];
			$username_style_cache = str_replace('[username]',$PowerBB->_POST['username'],$style);

			$UpdateArr 				= 	array();
			$UpdateArr['field'] 	= 	array();

			$UpdateArr['field']['username_style_cache']	=	$username_style_cache;
			$UpdateArr['where']					    	 =	array('id',$MemberInfo['id']);

			$update = $PowerBB->core->Update($UpdateArr,'member');


			$UpdatetodayArr 				= 	array();
			$UpdatetodayArr['field'] 	= 	array();

			$UpdatetodayArr['field']['username_style']	=	$username_style_cache;
			$UpdatetodayArr['where']					    	 =	array('user_id',$MemberInfo['id']);

			$Updatetoday = $PowerBB->core->Update($UpdatetodayArr,'today');

			$UpdateonlineArr 				= 	array();
			$UpdateonlineArr['field'] 	= 	array();

			$UpdateonlineArr['field']['username_style']	=	$username_style_cache;
			$UpdateonlineArr['where']					    	 =	array('user_id',$MemberInfo['id']);

			$Updateonline = $PowerBB->core->Update($UpdateonlineArr,'today');
			//////////
			}


			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['moderator_has_been_added_successfully']);
			$PowerBB->functions->redirect('index.php?page=moderators&amp;control=1&amp;main=1');


	}

	function _ControlMain()
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

					// Show Jump List to:)
		$result = $PowerBB->DB->sql_query("SELECT id,title,parent FROM " . $PowerBB->table['section'] . " ORDER BY id ASC");

		$Master = array();
		while ($row = $PowerBB->DB->sql_fetch_array($result)) {
			extract($row);
		    $Master = $PowerBB->section->GetSectionsList(array ('id'=>$id,'title'=>"".$title."",'parent'=>$parent."",'parent'=>$parent));
		    $PowerBB->_CONF['template']['foreach']['SecList'] = $PowerBB->section->GetSectionsList($Master);
		}

		$MainAndSub = new PowerBBCommon;
          	$PowerBB->template->assign('DoJumpList',$MainAndSub->DoJumpList($Master,$url,1));
		unset($Master);
	   ////////

		$PowerBB->template->display('moderators_main');
	}

	function _ControlSection()
	{
		global $PowerBB;

		if (!isset($PowerBB->_POST['section']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}

		$PowerBB->_POST['section'] = $PowerBB->functions->CleanVariable($PowerBB->_POST['section'],'intval');

		$SecArr 			= 	array();
		$SecArr['where'] 	= 	array('id',$PowerBB->_POST['section']);

		$PowerBB->_CONF['template']['Section'] = $PowerBB->core->GetInfo($SecArr,'section');

		if (!is_array($PowerBB->_CONF['template']['Section']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Section_requested_does_not_exist']);
		}

		$PowerBB->functions->CleanVariable($PowerBB->_CONF['template']['Section'],'html');

		$ModArr 			= 	array();
		$ModArr['where'] 	= 	array('section_id',$PowerBB->_CONF['template']['Section']['id']);

		$PowerBB->_CONF['template']['while']['ModeratorsList'] = $PowerBB->moderator->GetModeratorList($ModArr);

		$PowerBB->template->display('moderators_section_control');
	}

	function _EditMain()
	{
		global $PowerBB;

		$this->check_by_id($PowerBB->_CONF['template']['Inf']);

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

		$GroupArr 							= 	array();

		$GroupArr['where'] 					= 	array();
		$GroupArr['where'][0] 				= 	array();
		$GroupArr['where'][0]['name'] 		= 	'group_mod';
		$GroupArr['where'][0]['oper'] 		= 	'=';
		$GroupArr['where'][0]['value']		= 	1;

		$GroupArr['order'] 					= 	array();
		$GroupArr['order']['field'] 		= 	'group_order';
		$GroupArr['order']['type'] 			= 	'ASC';

		$PowerBB->_CONF['template']['while']['GroupList'] = $PowerBB->core->GetList($GroupArr,'group');

		$ModArr 			= 	array();
		$ModArr['where'] 	= 	array('member_id',$PowerBB->_GET['id']);

		$ModeratorInfo = $PowerBB->moderator->GetModeratorInfo($ModArr);

		$SecArr 			= 	array();
		$SecArr['where'] 	= 	array('id',$PowerBB->_GET['section_id']);

		$SectionInfo = $PowerBB->core->GetInfo($SecArr,'section');
        $PowerBB->template->assign('Section',$SectionInfo);
		$PowerBB->template->assign('Inf',$ModeratorInfo);

		$PowerBB->template->display('moderator_edit');
	}

	function _EditStart()
	{
		global $PowerBB;

		$this->check_by_id($ModInfo);

 		$MemberArr 			= 	array();
		$MemberArr['get']	= 	'*';

		$MemberArr['where']	=	array();

		$MemberArr['where'][0]				=	array();
		$MemberArr['where'][0]['name']		=	'username';
		$MemberArr['where'][0]['oper']		=	'=';
		$MemberArr['where'][0]['value']		=	$PowerBB->_POST['username'];

		$Member = $PowerBB->core->GetInfo($MemberArr,'member');

		if ($Member == false)
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['member_requested_does_not_exist']);
		}
			$ModArr 				= 	array();
			$ModArr['field'] 		= 	array();

			$ModArr['field']['username'] 	= 	$PowerBB->_POST['username'];
			$ModArr['field']['section_id'] 	= 	$PowerBB->_POST['section'];
			$ModArr['field']['member_id'] 	= 	$Member['id'];
			$ModArr['where']				=	array('id',$PowerBB->_GET['id']);

			$update = $PowerBB->moderator->UpdateModerator($ModArr);

			if ($update)
			{
				//////////

				$GroupArr 			= 	array();
				$GroupArr['where'] 	= 	array('id',$Member['usergroup']);

				$Group = $PowerBB->group->GetGroupInfo($GroupArr);

				// If the user isn't admin, so change the group
				if (!$Group['admincp_allow'])
				{
					$ChangeArr 				= 	array();
					$ChangeArr['field']		=	array();

					$ChangeArr['field']['usergroup']	=	$PowerBB->_POST['group'];
					$ChangeArr['where'] 				= 	array('id',$Member['id']);

					$change = $PowerBB->member->UpdateMember($ChangeArr);
				}

				//////////

				$CacheArr 			= 	array();
				$CacheArr['where'] 	= 	array('section_id',$PowerBB->_POST['section']);

				$cache = $PowerBB->moderator->CreateModeratorsCache($CacheArr);

				//////////

				$SecArr 						= 	array();
				$SecArr['field']				=	array();
				$SecArr['field']['moderators'] 	= 	$cache;
			    $SecArr['where'] 			=	array('id',$PowerBB->_POST['section']);

				$update = $PowerBB->core->Update($SecArr,'section');

				if ($update)
				{
					$cache = $PowerBB->section->UpdateSectionsCache(array('type'=>'normal'));

					if ($cache)
					{
						$SecArr 					= 	array();
					$SecArr['get_from']			=	'db';
					$SecArr['proc'] 			= 	array();
					$SecArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');
					$SecArr['order']			=	array();
					$SecArr['order']['field']	=	'sort';
					$SecArr['order']['type']	=	'ASC';

					$SecArr['where']				=	array();
					$SecArr['where'][0]				=	array();
					$SecArr['where'][0]['name']		=	'parent';
					$SecArr['where'][0]['oper']		=	'<>';
					$SecArr['where'][0]['value']	=	'0';

					$SecList = $PowerBB->core->GetList($SecArr,'section');

					$x = 0;
					$y = sizeof($SecList);
					$s = array();

					while ($x < $y)
					{
						$name = 'order-' . $SecList[$x]['id'];

						if ($SecList[$x]['order'] != $PowerBB->_POST[$name])
						{
							$UpdateArr 						= 	array();

							$UpdateArr['field']		 		= 	array();
							$UpdateArr['field']['sort'] 	= 	$PowerBB->_POST[$name];

							$UpdateArr['where'] 			=	array('id',$SecList[$x]['id']);

							$update = $PowerBB->core->Update($UpdateArr,'section');

							if ($update)
							{
								$cache = $PowerBB->section->UpdateSectionsCache(array('parent'=>$SecList[$x]['parent']));
							}

							$s[$SecList[$x]['id']] = ($update) ? 'true' : 'false';
						}

						$x += 1;
					}

					if (in_array('false',$s))
					{
						$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['did_not_succeed_the_process']);
					}
						$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['moderator_has_been_updated_successfully']);
						$PowerBB->functions->redirect('index.php?page=moderators&amp;control=1&amp;main=1');
					}
				}
			}


	}


	function _DelStart()
	{
		global $PowerBB;

		$this->check_by_id($ModInfo);

		$SecArr 			= 	array();
		$SecArr['where'] 	= 	array('id',$PowerBB->_GET['section_id']);
		$SectionInfo = $PowerBB->core->GetInfo($SecArr,'section');

		if ($SectionInfo == false)
		{
		  $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Section_requested_does_not_exist']);
		}

		$MemberArr 			= 	array();
		$MemberArr['where'] 	= 	array('id',$PowerBB->_GET['id']);

		$Member = $PowerBB->core->GetInfo($MemberArr,'member');

		if (!$Member)
		{
		  $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['member_requested_does_not_exist']);
		}

		$ModArr 			= 	array();
		$ModArr['where'] 	= 	array('id',$PowerBB->_GET['id']);

		$ModeratorInfo = $PowerBB->moderator->GetModeratorInfo($ModArr);

		$idSection = $PowerBB->_GET['section_id'];
		$member_id = $PowerBB->_GET['id'];

			  $DelModerator = $PowerBB->DB->sql_query("DELETE FROM " . $PowerBB->table['moderators'] . " WHERE member_id = '$member_id' and section_id = '$idSection' ");

				$CacheArr 			= 	array();
				$CacheArr['where'] 	= 	array('section_id',$idSection);
				$cache = $PowerBB->moderator->CreateModeratorsCache($CacheArr);

				$SecArr 				= 	array();
				$SecArr['field']		=	array();
				$SecArr['field']['moderators'] 	= 	$cache;
				$SecArr['where'] 			=	array('id',$idSection);
				$update = $PowerBB->core->Update($SecArr,'section');
				//////////
			   $UpdateSectionCache = $PowerBB->functions->UpdateSectionCache($idSection);

			$GetALLSection = $PowerBB->DB->sql_query("SELECT  *   FROM " . $PowerBB->table['section'] . " WHERE parent = '$idSection' ");

			while ($Sections = $PowerBB->DB->sql_fetch_array($GetALLSection))
			{
				$Sections1 = $Sections['id'];
				$DelModerator1 = $PowerBB->DB->sql_query("DELETE FROM " . $PowerBB->table['moderators'] . " WHERE member_id = '$member_id' and section_id = '$Sections1' ");
                $SectionParentInfo = $Sections['id'];

				//////////

				$CacheArr 			= 	array();
				$CacheArr['where'] 	= 	array('section_id',$Sections1);
				$cache2 = $PowerBB->moderator->CreateModeratorsCache($CacheArr);

				$SecArr 				= 	array();
				$SecArr['field']		=	array();
				$SecArr['field']['moderators'] 	= 	$cache2;
				$SecArr['where'] 			=	array('id',$Sections1);
				$update = $PowerBB->core->Update($SecArr,'section');
				//////////
				$UpdateSectionCache2 = $PowerBB->functions->UpdateSectionCache($Sections1);

				$GetParentInfo = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['section'] . " WHERE parent = '$Sections1' ");
				while ($Parent = $PowerBB->DB->sql_fetch_array($GetParentInfo))
				{
					$Sections2 = $Parent['id'];
					$DelModerator2 = $PowerBB->DB->sql_query("DELETE FROM " . $PowerBB->table['moderators'] . " WHERE member_id = '$member_id' and section_id = '$Sections2' ");
					//////////
					$CacheArr 			= 	array();
					$CacheArr['where'] 	= 	array('section_id',$Sections2);
					$cache3 = $PowerBB->moderator->CreateModeratorsCache($CacheArr);

					$SecArr 				= 	array();
					$SecArr['field']		=	array();
					$SecArr['field']['moderators'] 	= 	$cache3;
					$SecArr['where'] 			=	array('id',$Sections2);
					$update = $PowerBB->core->Update($SecArr,'section');
					//////////
					$UpdateSectionCache3 = $PowerBB->functions->UpdateSectionCache($Sections2);

					/////
					$GetParent2Info = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['section'] . " WHERE parent = '$Sections2' ");
					while ($Parent2 = $PowerBB->DB->sql_fetch_array($GetParent2Info))
					{
						$Sections3 = $Parent2['id'];
						$DelModerator2 = $PowerBB->DB->sql_query("DELETE FROM " . $PowerBB->table['moderators'] . " WHERE member_id = '$member_id' and section_id = '$Sections3' ");
						//////////
						$CacheArr 			= 	array();
						$CacheArr['where'] 	= 	array('section_id',$Sections3);
						$cache4 = $PowerBB->moderator->CreateModeratorsCache($CacheArr);

						$SecArr 				= 	array();
						$SecArr['field']		=	array();
						$SecArr['field']['moderators'] 	= 	$cache4;
						$SecArr['where'] 			=	array('id',$Sections3);
						$update = $PowerBB->core->Update($SecArr,'section');
						//////////
						$UpdateSectionCache4 = $PowerBB->functions->UpdateSectionCache($Sections3);

					}

				}

			}

		$GroupArr 			= 	array();
		$GroupArr['where'] 	= 	array('id',$Member['usergroup']);

		$Group = $PowerBB->core->GetInfo($GroupArr,'group');


		$ModArr = array();
		$ModArr['where'] = array('member_id',$PowerBB->_GET['id']);
		$IsMod = $PowerBB->core->GetInfo($ModArr,'moderators');

		// If the user isn't admin, so change the group
		if (!$Group['admincp_allow']
		and !$Group['vice'])
		{

			if (!$IsMod)
			{

				$GrpArr 			= 	array();
				$GrpArr['where'] 	= 	array('id','4');

			    $GroupInfo = $PowerBB->group->GetGroupInfo($GrpArr);

			    $style = $GroupInfo['username_style'];
			    $username_style_cache = str_replace('[username]',$Member['username'],$style);

				$ChangeArr 					= 	array();
				$ChangeArr['field']			=	array();
				$ChangeArr['field']['usergroup']	=	'4';
				$ChangeArr['field']['user_title'] = $PowerBB->_CONF['template']['_CONF']['lang']['Member'];
				$ChangeArr['field']['username_style_cache']	=	$username_style_cache;
				$ChangeArr['where'] 				= 	array('id',$PowerBB->_GET['id']);
               	$change = $PowerBB->core->Update($ChangeArr,'member');


				$UpdatetodayArr 				= 	array();
				$UpdatetodayArr['field'] 	= 	array();

				$UpdatetodayArr['field']['username_style']	=	$username_style_cache;
				$UpdatetodayArr['where']					    	 =	array('user_id',$PowerBB->_GET['id']);

				$Updatetoday = $PowerBB->core->Update($UpdatetodayArr,'today');

				$UpdateonlineArr 				= 	array();
				$UpdateonlineArr['field'] 	= 	array();

				$UpdateonlineArr['field']['username_style']	=	$username_style_cache;
				$UpdateonlineArr['where']					    	 =	array('user_id',$PowerBB->_GET['id']);

				$Updateonline = $PowerBB->core->Update($UpdateonlineArr,'today');
			//////////

			}
			else
			{

				$SecArr 			= 	array();
				$SecArr['where'] 	= 	array('id',$IsMod['section_id']);
				$SectionInfo = $PowerBB->core->GetInfo($SecArr,'section');

				$ChangeArr 					= 	array();
				$ChangeArr['field']			=	array();
			    $ChangeArr['field']['user_title'] = $PowerBB->_CONF['template']['_CONF']['lang']['moderator_On_Forum'] . $SectionInfo['title'];
				$ChangeArr['where'] 				= 	array('id',$PowerBB->_GET['id']);

               	$change = $PowerBB->core->Update($ChangeArr,'member');

			}

		}

		$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['moderator_has_been_deleted_successfully']);
		$PowerBB->functions->redirect('index.php?page=moderators&amp;control=1&amp;main=1');

	}

	function _ModActionMain()
	{
		global $PowerBB;
       		$PowerBB->_GET['count'] = (!isset($PowerBB->_GET['count'])) ? 0 : $PowerBB->_GET['count'];
       		$PowerBB->_GET['count'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['count'],'intval');

		$SmLogsArr 					= 	array();
		$SmLogsArr['order']			=	array();
		$SmLogsArr['order']['field']	=	'id';
		$SmLogsArr['order']['type']	=	'DESC';
		$SmLogsArr['proc'] 			= 	array();
		$SmLogsArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');
		$SmLogsArr['proc']['date'] 	= 	array('method'=>'date','store'=>'date');

		$SmLogsArr['pager'] 				= 	array();
		$SmLogsArr['pager']['total']		= 	$PowerBB->supermemberlogs->GetSupermemberlogsNumber(array('get_from'=>'db'));
		$SmLogsArr['pager']['perpage'] 	    = 	$PowerBB->_CONF['info_row']['subject_perpage'];
		$SmLogsArr['pager']['count'] 		= 	$PowerBB->_GET['count'];
		$SmLogsArr['pager']['location'] 	= 	'index.php?page=moderators&modaction=1&main=1';
		$SmLogsArr['pager']['var'] 		    = 	'count';

		$PowerBB->_CONF['template']['while']['ActionList'] = $PowerBB->supermemberlogs->GetSupermemberlogsList($SmLogsArr);

         $PowerBB->template->assign('pagerNumber',$PowerBB->supermemberlogs->GetSupermemberlogsNumber(array('get_from'=>'db')));

       if ($PowerBB->supermemberlogs->GetSupermemberlogsNumber(array('get_from'=>'db')) > $PowerBB->_CONF['info_row']['subject_perpage'])
        {
		$PowerBB->template->assign('pager',$PowerBB->pager->show());
        }

		$PowerBB->template->display('show_moderators_action');
	}

	function _DelAllStart()
	{
		global $PowerBB;


			$truncate = $PowerBB->DB->sql_query("TRUNCATE " . $PowerBB->table['supermemberlogs'] );


		if ($truncate)
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['modactions_has_been_deleted_successfully']);
			$PowerBB->functions->redirect('index.php?page=moderators&modaction=1&main=1');
		}
	}

}

class _functions
{
	function check_by_id(&$ModeratorInfo)
	{
		global $PowerBB;

		if (empty($PowerBB->_GET['id']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['The_request_is_not_valid']);
		}

		$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');

        $CatArr = $PowerBB->DB->sql_query("SELECT  *   FROM " . $PowerBB->table['moderators'] . " WHERE id = ".$PowerBB->_GET['id']." ");
	    $ModeratorInfo = $PowerBB->DB->sql_fetch_array($CatArr);

     /*
		if ($ModeratorInfo == false)
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['moderator_requested_does_not_exist']);
		}
      */
		$PowerBB->functions->CleanVariable($ModeratorInfo,'html');
	}
}

?>
