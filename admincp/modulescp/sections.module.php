<?php

(!defined('IN_PowerBB')) ? die() : '';

define('IN_ADMIN',true);

$CALL_SYSTEM				=	array();
$CALL_SYSTEM['SECTION'] 	= 	true;
$CALL_SYSTEM['GROUP'] 		= 	true;
$CALL_SYSTEM['SUBJECT'] 	= 	true;



define('CLASS_NAME','PowerBBSectionMOD');

include('../common.php');
class PowerBBSectionMOD extends _functions
{
	function run()
	{
		global $PowerBB;

			if ($PowerBB->_CONF['rows']['group_info']['admincp_section'] == '0')
			{
			  $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
			}


		if ($PowerBB->_CONF['member_permission'])
		{
			$PowerBB->template->display('header');

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
			elseif ($PowerBB->_GET['change_sort'])
			{
				$this->_ChangeSort();
			}
			elseif ($PowerBB->_GET['groups'])
			{
				if ($PowerBB->_GET['control_group'])
				{
					if ($PowerBB->_GET['index'])
					{
						$this->_GroupControlMain();
					}
					elseif ($PowerBB->_GET['start'])
					{
						$this->_GroupControlStart();
					}
				}
			}

			$PowerBB->template->display('footer');
		}
	}

	function _AddMain()
	{
		global $PowerBB;

		//////////

		$GroupArr 						= 	array();
		$GroupArr['order'] 				= 	array();
		$GroupArr['order']['field'] 	= 	'id';
		$GroupArr['order']['type'] 		= 	'ASC';
		$GroupArr['proc'] 				= 	array();
		$GroupArr['proc']['*'] 			= 	array('method'=>'clean','param'=>'html');

		$PowerBB->_CONF['template']['while']['groups'] = $PowerBB->core->GetList($GroupArr,'group');

		//////////

		$PowerBB->template->display('sections_add');
	}

	function _AddStart()
	{
		global $PowerBB;

		if (empty($PowerBB->_POST['name'])
			or ($PowerBB->_POST['order_type'] == 'manual' and empty($PowerBB->_POST['sort'])))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_fill_in_all_the_information']);
		}

		//////////

		$sort = 0;

		if ($PowerBB->_POST['order_type'] == 'auto')
		{
			$SortArr = array();
			$SortArr['where'] = array('parent','0');
			$SortArr['order'] = array();
			$SortArr['order']['field'] = 'sort';
			$SortArr['order']['type'] = 'DESC';

			$SortSection = $PowerBB->section->GetSectionInfo($SortArr);

			// No section
			if (!$SortSection)
			{
				$sort = 1;
			}
			// There is a section
			else
			{
				$sort = $SortSection['sort'] + 1;
			}
		}
		else
		{
			$sort = $PowerBB->_POST['sort'];
		}

		//////////

		$SecArr 			= 	array();
		$SecArr['field']	=	array();

		$SecArr['field']['title'] 		= 	$PowerBB->_POST['name'];
		$SecArr['field']['sort'] 		= 	$sort;
		$SecArr['field']['parent'] 		= 	'0';
		$SecArr['get_id']				=	true;

		$insert = $PowerBB->section->InsertSection($SecArr);

		if ($insert)
		{
			$GroupArr 						= 	array();
			$GroupArr['order'] 				= 	array();
			$GroupArr['order']['field'] 	= 	'id';
			$GroupArr['order']['type'] 		= 	'ASC';

			$groups = $PowerBB->core->GetList($GroupArr,'group');

			$x = 0;
			$n = sizeof($groups);

			while ($x < $n)
			{
				$SecArr 				= 	array();
				$SecArr['field']		=	array();

				$SecArr['field']['section_id'] 			= 	$PowerBB->section->id;
				$SecArr['field']['group_id'] 			= 	$groups[$x]['id'];
				$SecArr['field']['view_section'] 		= 	$PowerBB->_POST['groups'][$groups[$x]['id']]['view_section'];
				$SecArr['field']['download_attach'] 	= 	$groups[$x]['download_attach'];
				$SecArr['field']['write_subject'] 		= 	$groups[$x]['write_subject'];
				$SecArr['field']['write_reply'] 		= 	$groups[$x]['write_reply'];
				$SecArr['field']['upload_attach'] 		= 	$groups[$x]['upload_attach'];
				$SecArr['field']['edit_own_subject'] 	= 	$groups[$x]['edit_own_subject'];
				$SecArr['field']['edit_own_reply'] 		= 	$groups[$x]['edit_own_reply'];
				$SecArr['field']['del_own_subject'] 	= 	$groups[$x]['del_own_subject'];
				$SecArr['field']['del_own_reply'] 		= 	$groups[$x]['del_own_reply'];
				$SecArr['field']['write_poll'] 			= 	$groups[$x]['write_poll'];
				$SecArr['field']['vote_poll'] 			= 	$groups[$x]['vote_poll'];
				$SecArr['field']['main_section'] 		= 	1;
				$SecArr['field']['group_name'] 			= 	$groups[$x]['title'];

				$insert = $PowerBB->group->InsertSectionGroup($SecArr);

				$x += 1;
			}

			$CacheArr 			= 	array();
			$CacheArr['id'] 	= 	$PowerBB->section->id;

			$cache = $PowerBB->group->UpdateSectionGroupCache($CacheArr);


				$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Section_has_been_added_successfully']);
				$PowerBB->functions->redirect('index.php?page=sections&amp;control=1&amp;main=1');
		}
		else
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['not_able_to_add_Section']);
		}
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

		$PowerBB->_CONF['template']['while']['SecList'] = $PowerBB->core->GetList($SecArr,'section');

		//////////

		$PowerBB->template->display('sections_main');

		//////////
	}

	function _EditMain()
	{
		global $PowerBB;

		$this->check_by_id($PowerBB->_CONF['template']['Inf']);

		$PowerBB->template->display('section_edit');
	}

	function _EditStart()
	{
		global $PowerBB;

		//////////

		$this->check_by_id($Inf);

		//////////

		if (empty($PowerBB->_POST['name'])
			or empty($PowerBB->_POST['sort']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_fill_in_all_the_information']);
		}

		//////////

		$SecArr 			= 	array();
		$SecArr['field']	=	array();

		$SecArr['field']['title'] 	= 	$PowerBB->_POST['name'];
		$SecArr['field']['sort'] 	= 	$PowerBB->_POST['sort'];
		$SecArr['where']			= 	array('id',$Inf['id']);

		$update = $PowerBB->core->Update($SecArr,'section');

		//////////

		if ($update)
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Forum_has_been_updated_successfully']);
			$PowerBB->functions->redirect('index.php?page=sections&amp;control=1&amp;main=1');
		}

		//////////
	}

    function _DelMain()
	{
		global $PowerBB;

		$PowerBB->_CONF['template']['Inf'] = false;

		$this->check_by_id($PowerBB->_CONF['template']['Inf']);

		$SecArr 					= 	array();
		$SecArr['get_from']			=	'db';
		$SecArr['proc'] 			= 	array();
		$SecArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');
		$SecArr['order']			=	array();
		$SecArr['order']['field']	=	'sort';
		$SecArr['order']['type']	=	'ASC';

		$SecArr['where']			=	array();
		$SecArr['where'][0]			=	array('name'=>'parent','oper'=>'<>','value'=>'0');
		$SecArr['where'][1]			=	array('con'=>'AND','name'=>'id','oper'=>'<>','value'=>$PowerBB->_CONF['template']['Inf']['id']);

		$PowerBB->_CONF['template']['while']['SecList'] = $PowerBB->core->GetList($SecArr,'section');

		$PowerBB->template->display('section_del');
	}

	function _DelStart()
	{
		global $PowerBB;

		$PowerBB->_CONF['template']['Inf'] = false;

		$this->check_by_id($PowerBB->_CONF['template']['Inf']);

		if ($PowerBB->_POST['choose'] == 'move')
		{


	        $InfSectionID = $PowerBB->_CONF['template']['Inf']['id'];
		    $sql_Section = $PowerBB->DB->sql_query("SELECT  *   FROM " . $PowerBB->table['section'] . " WHERE parent = '$InfSectionID' ");

		       while ($getSection_row = $PowerBB->DB->sql_fetch_array($sql_Section))
		      {

	     		    $UpdateArr 					= 	array();
	   				$UpdateArr['field']			=	array();

	   				$UpdateArr['field']['parent'] 	= 	$PowerBB->_POST['to'];
	   				$UpdateArr['where']					= 	array('parent',$getSection_row['parent']);

	     		$update = $PowerBB->core->Update($UpdateArr,'section');


				 $DelArr 			= 	array();
				 $DelArr['where'] 	= 	array('id',$PowerBB->_CONF['template']['Inf']['id']);

				$del = $PowerBB->core->Deleted($DelArr,'section');
               }


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

						$cache = $PowerBB->section->UpdateSectionsCache(array('parent'=>$SecList[$x]['parent']));

						    $section = $SecList[$x]['id'];
						    $subject_nm = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT  *   FROM " . $PowerBB->table['subject'] . " WHERE section = '$section' "));

				            // The number of section's subjects number
				     		$UpdateArr 					= 	array();
				     		$UpdateArr['field']			=	array();

				     		$UpdateArr['field']['subject_num'] 	= 	$subject_nm;
				     		$UpdateArr['where']					= 	array('id',$section);

				     		$UpdateSubjectNumber = $PowerBB->core->Update($UpdateArr,'section');
				            $PowerBB->cache->UpdateSubjectNumber(array('subject_num'	=>	$subject_nm));

				                 		// The number of section's subjects number
				            $section = $SecList[$x]['id'];
						    $reply_num = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT  *   FROM " . $PowerBB->table['reply'] . " WHERE section = '$section' "));

				     		$UpdateArr 					= 	array();
				     		$UpdateArr['field']			=	array();

				     		$UpdateArr['field']['reply_num'] 	= 	$reply_num;
				     		$UpdateArr['where']					= 	array('id',$section);

				     		$UpdateReplyNumber = $PowerBB->core->Update($UpdateArr,'section');
				     		$PowerBB->cache->UpdateReplyNumber(array('reply_num'	=>	$reply_num));


						$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Forums_have_been_moved_successfully']);

						$DelArr 						= 	array();
						$DelArr['where']				=	array();
						$DelArr['where'][0]				=	array();
						$DelArr['where'][0]['name']		=	'section_id';
						$DelArr['where'][0]['oper']		=	'=';
						$DelArr['where'][0]['value']	=	$PowerBB->_CONF['template']['Inf']['id'];

						$del = $PowerBB->core->Deleted($DelArr,'sectiongroup');

						if ($del)
						{
							$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['groups_have_been_deleted_successfully']);
							$PowerBB->functions->redirect('index.php?page=sections&amp;control=1&amp;main=1');
						}



                  }
		}
		elseif ($PowerBB->_POST['choose'] == 'del')
		{

    	$CatparentArr 			= 	array();
		$CatparentArr['where'] 	= 	array('parent',$PowerBB->_CONF['template']['Inf']['id']);
		$Infparent = $PowerBB->section->GetSectionInfo($CatparentArr);
		/////
    	  if ($Infparent)
		 {
	     	$parentArr 			= 	array();
			$parentArr['where'] 	= 	array('parent',$Infparent['id']);
			$parent = $PowerBB->section->GetSectionInfo($parentArr);
			////

			 if ($parent)
			 {
	         $section_parent1 = $parent['id'];
	         $get_section_parent1 = $PowerBB->DB->sql_query("SELECT  *   FROM " . $PowerBB->table['section'] . " WHERE parent = " . $section_parent1 . " ");

		       while ($Inf_row1 = $PowerBB->DB->sql_fetch_array($get_section_parent1))
		      {
					$DelSubjects1Arr 						= 	array();
					$DelSubjects1Arr['where']				=	array();
					$DelSubjects1Arr['where'][0]				=	array();
					$DelSubjects1Arr['where'][0]['name']		=	'section';
					$DelSubjects1Arr['where'][0]['oper']		=	'=';
					$DelSubjects1Arr['where'][0]['value']	=	$Inf_row1['id'];

					$DelSubjects1 = $PowerBB->subject->DeleteSubject($DelSubjects1Arr);

					$DelReplys1Arr 						= 	array();
					$DelReplys1Arr['where']				=	array();
					$DelReplys1Arr['where'][0]				=	array();
					$DelReplys1Arr['where'][0]['name']		=	'section';
					$DelReplys1Arr['where'][0]['oper']		=	'=';
					$DelReplys1Arr['where'][0]['value']	=	$Inf_row1['id'];

					$DelReplys = $PowerBB->reply->DeleteReply($DelReplys1Arr);

					$DelSectionsArr 			= 	array();
					$DelSectionsArr['where'] 	= 	array('id',$Inf_row1['id']);

					$DelSections= $PowerBB->core->Deleted($DelSectionsArr,'section');

					$DelSections2Arr 			= 	array();
					$DelSections2Arr['where'] 	= 	array('id',$Infparent['id']);

					$DelSections2= $PowerBB->section->DeleteSection($DelSections2Arr);
		      }


					$DelReply1Arr 						= 	array();
					$DelReply1Arr['where']				=	array();
					$DelReply1Arr['where'][0]				=	array();
					$DelReply1Arr['where'][0]['name']		=	'section';
					$DelReply1Arr['where'][0]['oper']		=	'=';
					$DelReply1Arr['where'][0]['value']	=	$parent['id'];

					$DelReply1 = $PowerBB->reply->DeleteReply($DelReply1Arr);

					$Del1Arr 						= 	array();
					$Del1Arr['where']				=	array();
					$Del1Arr['where'][0]				=	array();
					$Del1Arr['where'][0]['name']		=	'section';
					$Del1Arr['where'][0]['oper']		=	'=';
					$Del1Arr['where'][0]['value']	=	$parent['id'];

					$del1 = $PowerBB->subject->DeleteSubject($Del1Arr);
	          }
		  }					///////////

         $section_parent = $PowerBB->_CONF['template']['Inf']['id'];
         $get_section_parent = $PowerBB->DB->sql_query("SELECT  *   FROM " . $PowerBB->table['section'] . " WHERE parent = " . $section_parent . " ");
          if($get_section_parent)
          {
		       while ($Inf_row = $PowerBB->DB->sql_fetch_array($get_section_parent))
		      {
					$DelSubjectsArr 						= 	array();
					$DelSubjectsArr['where']				=	array();
					$DelSubjectsArr['where'][0]				=	array();
					$DelSubjectsArr['where'][0]['name']		=	'section';
					$DelSubjectsArr['where'][0]['oper']		=	'=';
					$DelSubjectsArr['where'][0]['value']	=	$Inf_row['id'];

					$DelSubjects = $PowerBB->core->Deleted($DelSubjectsArr,'subject');

					$DelReplysArr 						= 	array();
					$DelReplysArr['where']				=	array();
					$DelReplysArr['where'][0]				=	array();
					$DelReplysArr['where'][0]['name']		=	'section';
					$DelReplysArr['where'][0]['oper']		=	'=';
					$DelReplysArr['where'][0]['value']	=	$Inf_row['id'];

					$DelReplys = $PowerBB->core->Deleted($DelReplysArr,'reply');

					$DelSectionsArr 			= 	array();
					$DelSectionsArr['where'] 	= 	array('id',$Inf_row['id']);

					$DelSections= $PowerBB->core->Deleted($DelSectionsArr,'section');
		      }



				$DelReplyArr 						= 	array();
				$DelReplyArr['where']				=	array();
				$DelReplyArr['where'][0]				=	array();
				$DelReplyArr['where'][0]['name']		=	'section';
				$DelReplyArr['where'][0]['oper']		=	'=';
				$DelReplyArr['where'][0]['value']	=	$PowerBB->_CONF['template']['Inf']['id'];

				$DelReply = $PowerBB->core->Deleted($DelReplyArr,'reply');

				$DelArr 						= 	array();
				$DelArr['where']				=	array();
				$DelArr['where'][0]				=	array();
				$DelArr['where'][0]['name']		=	'section';
				$DelArr['where'][0]['oper']		=	'=';
				$DelArr['where'][0]['value']	=	$PowerBB->_CONF['template']['Inf']['id'];

				$del = $PowerBB->core->Deleted($DelArr,'subject');

			   $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Topic_has_been_deleted_successfully']);
           }
			   $cache = $PowerBB->section->UpdateSectionsCache(array('parent'=>$PowerBB->_CONF['template']['Inf']['parent']));


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
							$PowerBB->functions->error(did_not_succeed_the_process);
							}
							$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Information_has_been_updated_successfully']);

							$DelArr 						= 	array();
							$DelArr['where']				=	array();
							$DelArr['where'][0]				=	array();
							$DelArr['where'][0]['name']		=	'section_id';
							$DelArr['where'][0]['oper']		=	'=';
							$DelArr['where'][0]['value']	=	$PowerBB->_CONF['template']['Inf']['id'];

							$del = $PowerBB->core->Deleted($DelArr,'sectiongroup');

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

							$DelArr 			= 	array();
							$DelArr['where'] 	= 	array('id',$PowerBB->_CONF['template']['Inf']['id']);

							$del = $PowerBB->core->Deleted($DelArr,'section');
							$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Forum_has_been_deleted_successfully']);

							$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['groups_have_been_deleted_successfully']);
							$PowerBB->functions->redirect('index.php?page=sections&amp;control=1&amp;main=1');


		}
		else
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Choose_incorrectl']);
		}
	}

	function _ChangeSort()
	{
		global $PowerBB;

		$SecArr 						= 	array();
		$SecArr['get_from']				=	'db';

		$SecArr['proc'] 				= 	array();
		$SecArr['proc']['*'] 			= 	array('method'=>'clean','param'=>'html');

		$SecArr['where']				=	array();
		$SecArr['where'][0]['name']		= 	'parent';
		$SecArr['where'][0]['oper']		= 	'=';
		$SecArr['where'][0]['value']	= 	'0';

		$SecList = $PowerBB->core->GetList($SecArr,'section');

		$x = 0;
		$y = sizeof($SecList);
		$s = array();

		while ($x < $y)
		{
			$name = 'order-' . $SecList[$x]['id'];

			$UpdateArr 				= 	array();
			$UpdateArr['field']		=	array();

			$UpdateArr['field']['sort'] 	= 	$PowerBB->_POST[$name];
			$UpdateArr['where'] 			= 	array('id',$SecList[$x]['id']);

			$update = $PowerBB->core->Update($UpdateArr,'section');

			$s[$SecList[$x]['id']] = ($update) ? 'true' : 'false';

			$x += 1;
		}

		if (in_array('false',$s))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['did_not_succeed_the_process']);
		}
		else
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['updated_successfully']);
			$PowerBB->functions->redirect('index.php?page=sections&amp;control=1&amp;main=1');
		}
	}

	function _GroupControlMain()
	{
		global $PowerBB;

		$this->check_by_id($PowerBB->_CONF['template']['Inf']);

		$SecGroupArr 						= 	array();
		$SecGroupArr['where'] 				= 	array();

		$SecGroupArr['where'][0]			=	array();
		$SecGroupArr['where'][0]['name'] 	= 	'section_id';
		$SecGroupArr['where'][0]['oper']	=	'=';
		$SecGroupArr['where'][0]['value'] 	= 	$PowerBB->_CONF['template']['Inf']['id'];

		$SecGroupArr['where'][1]			=	array();
		$SecGroupArr['where'][1]['con']		=	'AND';
		$SecGroupArr['where'][1]['name']	=	'main_section';
		$SecGroupArr['where'][1]['oper']	=	'=';
		$SecGroupArr['where'][1]['value']	=	'1';

		$PowerBB->_CONF['template']['while']['SecGroupList'] = $PowerBB->core->GetList($SecGroupArr,'sectiongroup');

		$PowerBB->template->display('sections_groups_control_main');
	}

	function _GroupControlStart()
	{
		global $PowerBB;

		//////////

		$this->check_by_id($Inf);

		//////////

		$PowerBB->functions->CleanVariable($PowerBB->_GET['group_id'],'intval');

		//////////

		$success 	= 	array();
		$fail		=	array();
		$size		=	sizeof($PowerBB->_POST['groups']);

		foreach ($PowerBB->_POST['groups'] as $id => $val)
		{
			$UpdateArr 				= 	array();
			$UpdateArr['field']		=	array();

			$UpdateArr['field']['view_section'] 	= 	$val['view_section'];
			$UpdateArr['where'][0] 					= 	array('name'=>'group_id','oper'=>'=','value'=>$id);
			$UpdateArr['where'][1] 					= 	array('con'=>'AND','name'=>'section_id','oper'=>'=','value'=>$Inf['id']);

			$update = $PowerBB->core->Update($UpdateArr,'sectiongroup');

			unset($UpdateArr);

			if ($update)
			{
				$success[] = $id;
			}
			else
			{
				$fail[] = $id;
			}

			unset($update);
		}

		//////////

		$success_size 	= 	sizeof($success);
		$fail_size		=	sizeof($fail);

		//////////

		if ($success_size == $size)
		{
			//////////

			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Forum_has_been_updated_successfully']);

			//////////

			$UpdateArr 			= 	array();
			$UpdateArr['id'] 	= 	$Inf['id'];

			$cache = $PowerBB->group->UpdateSectionGroupCache($UpdateArr);

			//////////


				$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Updated_information_cached']);
				$PowerBB->functions->redirect('index.php?page=sections&amp;groups=1&amp;control_group=1&amp;index=1&amp;id=' . $Inf['id']);
		}
	}
}

class _functions
{
	function check_by_id(&$Inf)
	{
		global $PowerBB;

		//////////

		if (empty($PowerBB->_GET['id']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['The_request_is_not_valid']);
		}

		//////////

		$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');

		//////////


    	$CatArr = $PowerBB->DB->sql_query("SELECT  *   FROM " . $PowerBB->table['section'] . " WHERE id = ".$PowerBB->_GET['id']." ");
		$Inf = $PowerBB->DB->sql_fetch_array($CatArr);

		//////////

		if ($Inf == false)
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Section_requested_does_not_exist']);
		}

		//////////

		$PowerBB->functions->CleanVariable($Inf,'html');

		//////////
	}
}

?>
