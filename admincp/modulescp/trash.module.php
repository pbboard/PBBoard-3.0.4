<?php

(!defined('IN_PowerBB')) ? die() : '';

define('IN_ADMIN',true);

$CALL_SYSTEM				=	array();
$CALL_SYSTEM['SUBJECT'] 	= 	true;
$CALL_SYSTEM['REPLY'] 		= 	true;
$CALL_SYSTEM['SECTION'] 		= 	true;
$CALL_SYSTEM['CACHE'] 			= 	true;



define('CLASS_NAME','PowerBBTrashMOD');

include('../common.php');
class PowerBBTrashMOD extends _functions /** Yes it's Power Trash :D **/
{
	function run()
	{
		global $PowerBB;

		if ($PowerBB->_CONF['member_permission'])
		{
			$PowerBB->template->display('header');

			if ($PowerBB->_GET['subject'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_SubjectTrashMain();
				}
				elseif ($PowerBB->_GET['untrash'])
				{
					$this->_SubjectUnTrash();
				}
				elseif ($PowerBB->_GET['del'])
				{
					if ($PowerBB->_GET['confirm'])
					{
						$this->_SubjectDelMain();
					}
					elseif ($PowerBB->_GET['start'])
					{
						$this->_SubjectDelete();
					}
				}
				if ($PowerBB->_GET['del_all_subjects'])
				{
					$this->_SubjectDelStart();
				}
			}
			elseif ($PowerBB->_GET['reply'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_ReplyTrashMain();
				}
				elseif ($PowerBB->_GET['untrash'])
				{
					$this->_ReplyUnTrash();
				}
				elseif ($PowerBB->_GET['del'])
				{
					if ($PowerBB->_GET['confirm'])
					{
						$this->_ReplyDelMain();
					}
					elseif ($PowerBB->_GET['start'])
					{
						$this->_ReplyDelete();
					}
				}
				if ($PowerBB->_GET['del_all_replys'])
				{
					$this->_ReplyDelStart();
				}
			}

			$PowerBB->template->display('footer');
		}
	}

	function _SubjectTrashMain()
	{
		global $PowerBB;

		$TrashArr 						= 	array();

		$TrashArr['proc'] 				= 	array();
		$TrashArr['proc']['*'] 			= 	array('method'=>'clean','param'=>'html');

		$TrashArr['where']				=	array();
		$TrashArr['where'][0]			=	array();
		$TrashArr['where'][0]['name']	=	'delete_topic';
		$TrashArr['where'][0]['oper']	=	'=';
		$TrashArr['where'][0]['value']	=	'1';

		$TrashArr['order']				=	array();
		$TrashArr['order']['field']		=	'id';
		$TrashArr['order']['type']		=	'DESC';

		$PowerBB->_CONF['template']['while']['TrashList'] = $PowerBB->subject->GetSubjectList($TrashArr);

		$PowerBB->template->display('trash_subjects');
	}

	function _SubjectUnTrash()
	{
		global $PowerBB;

		if (empty($PowerBB->_GET['id']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['The_request_is_not_valid']);
		}

		$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');

		$Trash['where']				=	array('id',$PowerBB->_GET['id']);

		$UnTrash = $PowerBB->subject->UnTrashSubject($Trash);

		if ($UnTrash)
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['subject_has_been_trash_successfully']);
			$PowerBB->functions->redirect('index.php?page=trash&amp;subject=1&amp;main=1');
		}
	}

	function _SubjectDelMain()
	{
		global $PowerBB;

		$this->check_subject_by_id($PowerBB->_CONF['template']['Inf']);

		$PowerBB->template->display('trash_subject_del');
	}

	function _SubjectDelete()
	{
		global $PowerBB;

		$this->check_subject_by_id($Inf);

		$DelArr				=	array();
		$DelArr['where'] 	= 	array('id',$Inf['id']);

		$del = $PowerBB->core->Deleted($DelArr,'subject');

		if ($del)
		{
             		$SecArr 			= 	array();
					$SecArr['where'] 	= 	array('id',$PowerBB->_GET['section']);

					$this->SectionInfo = $PowerBB->core->GetInfo($SecArr,'section');

					// The number of section's subjects number
		     		$UpdateArr 					= 	array();
		     		$UpdateArr['field']			=	array();

		     		$UpdateArr['field']['subject_num'] 	= 	$this->SectionInfo['subject_num'] - 1;
		     		$UpdateArr['where']					= 	array('id',$this->SectionInfo['id']);

		     		$UpdateSubjectNumber = $PowerBB->core->Update($UpdateArr,'section');


		     		// Free memory
		     		unset($UpdateArr);

		     		//////////

		     		// Update section's cache
		     		$UpdateArr 				= 	array();
		     		$UpdateArr['parent'] 	= 	$this->SectionInfo['parent'];

		     		$update_cache = $PowerBB->section->UpdateSectionsCache($UpdateArr);

		     		unset($UpdateArr);

		     		//////////

		     		//////////

		     		// The overall number of subjects
		     		$UpdateSubjectNumber = $PowerBB->cache->UpdateSubjectNumber(array('subject_num'	=>	$PowerBB->_CONF['info_row']['subject_number']));

		     		//////////

					$DelArr				=	array();
					$DelArr['where'] 	= 	array('subject_id',$Inf['id']);

					$del = $PowerBB->reply->DeleteReply($DelArr);


			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Topic_has_been_deleted_and_all_its_replies_successfully']);
			$PowerBB->functions->redirect('index.php?page=trash&amp;subject=1&amp;main=1');
		}
	}

	function _ReplyTrashMain()
	{
		global $PowerBB;

		$TrashArr 						= 	array();
		$TrashArr['proc'] 				= 	array();
		$TrashArr['proc']['*'] 			= 	array('method'=>'clean','param'=>'html');

		$TrashArr['order']				=	array();
		$TrashArr['order']['field']		=	'id';
		$TrashArr['order']['type']		=	'DESC';

		$TrashArr['where']				=	array();
		$TrashArr['where'][0]			=	array();
		$TrashArr['where'][0]['name']	=	'delete_topic';
		$TrashArr['where'][0]['oper']	=	'=';
		$TrashArr['where'][0]['value']	=	'1';

		$PowerBB->_CONF['template']['while']['TrashList'] = $PowerBB->reply->GetReplyList($TrashArr);

		$PowerBB->template->display('trash_replies');
	}

	function _ReplyUnTrash()
	{
		global $PowerBB;

		if (empty($PowerBB->_GET['id']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['The_request_is_not_valid']);
		}

		$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');
		$PowerBB->_GET['subject_id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['subject_id'],'intval');

		$Trash['where']				=	array('id',$PowerBB->_GET['id']);
		$UnTrash = $PowerBB->reply->UnTrashReply($Trash);

		$subject_id = $PowerBB->_GET['subject_id'];
		$Getlast_replier = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['reply'] . " WHERE subject_id = '$subject_id' AND delete_topic<>1 AND review_reply<>1 ORDER by write_time DESC");
		$GetLast_replierForm = $PowerBB->DB->sql_fetch_array($Getlast_replier);
		if (!$GetLast_replierForm)
		{
		$last_replier = '';
		}
		else
		{
		$last_replier = $GetLast_replierForm['writer'];
		}

		// Update Subject
		$SubjectArr = array();
		$SubjectArr['where'] = array('id',$subject_id);

		$SubjectInfo = $PowerBB->core->GetInfo($SubjectArr,'subject');
		if (empty($GetLast_replierForm['write_time']))
		{
		$write_time = $SubjectInfo['native_write_time'];
		}
		else
		{
		$write_time = $GetLast_replierForm['write_time'];
		}
		$SubjectArr 							= 	array();
		$SubjectArr['field'] 					= 	array();
		$SubjectArr['field']['reply_number'] 	= 	$SubjectInfo['reply_number'] +1;
		$SubjectArr['field']['last_replier'] 	= 	$last_replier;
		$SubjectArr['field']['write_time']   	= 	$write_time;
		$SubjectArr['where'] 					= 	array('id',$subject_id);

		$update = $PowerBB->subject->UpdateSubject($SubjectArr);

		if ($UnTrash)
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Reply_has_been_trash_successfully']);
			$PowerBB->functions->redirect('index.php?page=trash&amp;reply=1&amp;main=1');
		}
	}

	function _ReplyDelMain()
	{
		global $PowerBB;

		$this->check_reply_by_id($PowerBB->_CONF['template']['Inf']);

		$PowerBB->template->display('trash_reply_del');
	}

	function _ReplyDelete()
	{
		global $PowerBB;

		$this->check_reply_by_id($ReplyInf);
		$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');
		$PowerBB->_GET['subject_id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['subject_id'],'intval');


		$Tdel['where']	=	array('id',$PowerBB->_GET['id']);

		$del            = $PowerBB->reply->DeleteReply($Tdel);

		$subject_id = $PowerBB->_GET['subject_id'];
		$Getlast_replier = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['reply'] . " WHERE subject_id = '$subject_id' AND delete_topic<>1 AND review_reply<>1 ORDER by write_time DESC");
		$GetLast_replierForm = $PowerBB->DB->sql_fetch_array($Getlast_replier);
		if (!$GetLast_replierForm)
		{
		$last_replier = '';
		}
		else
		{
		$last_replier = $GetLast_replierForm['writer'];
		}

		// Update Subject
		$SubjectArr = array();
		$SubjectArr['where'] = array('id',$PowerBB->_GET['subject_id']);

		$SubjectInfo = $PowerBB->core->GetInfo($SubjectArr,'subject');
		if (empty($GetLast_replierForm['write_time']))
		{
		$write_time = $SubjectInfo['native_write_time'];
		}
		else
		{
		$write_time = $GetLast_replierForm['write_time'];
		}
		$SubjectArr 							= 	array();
		$SubjectArr['field'] 					= 	array();
		$SubjectArr['field']['reply_number'] 	= 	$SubjectInfo['reply_number'] -1;
		$SubjectArr['field']['last_replier'] 	= 	$last_replier;
		$SubjectArr['field']['write_time']   	= 	$write_time;
		$SubjectArr['where'] 					= 	array('id',$PowerBB->_GET['subject_id']);

		$update = $PowerBB->subject->UpdateSubject($SubjectArr);



		if ($del)
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Reply_has_been_deleted_successfully']);
			$PowerBB->functions->redirect('index.php?page=trash&amp;reply=1&amp;main=1');
		}
	}

	function _SubjectDelStart()
	{
		global $PowerBB;

	//  Delete Subjects
	$GetSubjects_query = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['subject'] . " WHERE delete_topic = '1'");
	while ($getSubjects_row = $PowerBB->DB->sql_fetch_array($GetSubjects_query))
	 {

		     $DelAttachArr				=	array();
	         $DelAttachArr['where'] 	= 	array('subject_id',$getSubjects_row['id']);

			  $DeleteAttach = $PowerBB->core->Deleted($DelAttachArr,'attach');

            //  Delete Tags
             $subjectInfoid =  $getSubjects_row['id'];
             $getTags_query = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['tag_subject'] . " WHERE subject_id = '$subjectInfoid'");
               while ($getTags_row = $PowerBB->DB->sql_fetch_array($getTags_query))
                 {
					  $DeleteTagArr				=	array();
			          $DeleteTagArr['where'] 	= 	array('id',$getTags_row['tag_id']);
					  $delTags = $PowerBB->tag->DeleteTag($DeleteTagArr);
                 }

			  $DeleteSubjectArr				=	array();
	          $DeleteSubjectArr['where'] 	= 	array('subject_id',$getSubjects_row['id']);
			  $delSubject = $PowerBB->tag_subject->DeleteSubject($DeleteSubjectArr);

			     $DelReplyArr				=	array();
		         $DelReplyArr['where'] 	= 	array('subject_id',$getSubjects_row['id']);

				  $delReply = $PowerBB->core->Deleted($DelReplyArr,'reply');

			     $DelArr				=	array();
		         $DelArr['where'] 	= 	array('id',$getSubjects_row['id']);

				  $DeleteSubject_ = $PowerBB->core->Deleted($DelArr,'subject');
      }
		if ($DeleteSubject_)
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Topic_has_been_deleted_successfully']);
			$PowerBB->functions->redirect('index.php?page=trash&amp;subject=1&amp;main=1');
		}
		else
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['nodevelopment']);
			$PowerBB->functions->redirect('index.php?page=trash&amp;subject=1&amp;main=1');
		}
	}

	function _ReplyDelStart()
	{
		global $PowerBB;

	//  Delete Subjects
	$GetReplys_query = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['reply'] . " WHERE delete_topic = '1'");
	while ($getReplys_row = $PowerBB->DB->sql_fetch_array($GetReplys_query))
	 {

     			  $DeleteReplyArr				=	array();
		          $DeleteReplyArr['where'] 	= 	array('id',$getReplys_row['id']);
				  $delReply = $PowerBB->reply->DeleteReply($DeleteReplyArr);

				$SecArr 			= 	array();
		        $SecArr['where'] 	= 	array('id',$getReplys_row['section']);

		        $this->SectionInfo = $PowerBB->core->GetInfo($SecArr,'section');



					$UpdateSubjectNumber = $PowerBB->cache->UpdateReplyNumber(array('reply_num'	=>	$PowerBB->_CONF['info_row']['reply_number']));

		     		$UpdateArr 					= 	array();
		     		$UpdateArr['field']			=	array();

		     		$UpdateArr['field']['reply_num'] 	= 	$this->SectionInfo['reply_num'] - 1;
		     		$UpdateArr['where']					= 	array('id',$this->SectionInfo['id']);

		     		$UpdateSubjectNumber = $PowerBB->core->Update($UpdateArr,'section');

		     		// Free memory
		     		unset($UpdateArr);

		     		//////////

		     		// Update section's cache
		     		$UpdateArr 				= 	array();
		     		$UpdateArr['parent'] 	= 	$this->SectionInfo['parent'];

		     		$update_cache = $PowerBB->section->UpdateSectionsCache($UpdateArr);

		     		unset($UpdateArr);

                    $subject_id = $getReplys_row['subject_id'];
					$Getlast_replier = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['reply'] . " WHERE subject_id = '$subject_id' AND delete_topic<>1 AND review_reply<>1 ORDER by write_time DESC");
					$GetLast_replierForm = $PowerBB->DB->sql_fetch_array($Getlast_replier);
					if (!$GetLast_replierForm)
					{
					$last_replier = '';
					}
					else
					{
					$last_replier = $GetLast_replierForm['writer'];
					}

					   //////////
					 // Update Subject
					$SubjectArr = array();
					$SubjectArr['where'] = array('id',$getReplys_row['subject_id']);

					$SubjectInfo = $PowerBB->core->GetInfo($SubjectArr,'subject');

					if (!$GetLast_replierForm)
					{
					$write_time = $SubjectInfo['native_write_time'];
					}
					else
					{
					$write_time = $GetLast_replierForm['write_time'];
					}

                    $SubjectArr 							= 	array();
					$SubjectArr['field'] 					= 	array();
					$SubjectArr['field']['reply_number'] 	= 	$SubjectInfo['reply_number'] -1;
					$SubjectArr['field']['last_replier'] 	= 	$last_replier;
					$SubjectArr['field']['write_time']   	= 	$SubjectInfo['native_write_time'];
					$SubjectArr['where'] 					= 	array('id',$PowerBB->_GET['subject_id']);

					$update = $PowerBB->subject->UpdateSubject($SubjectArr);
      }
		if ($delReply)
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Replys_has_been_deleted_successfully']);
			$PowerBB->functions->redirect('index.php?page=trash&amp;reply=1&amp;main=1');
		}
		else
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['nodevelopment']);
			$PowerBB->functions->redirect('index.php?page=trash&amp;reply=1&amp;main=1');
		}
	}

}

class _functions
{
	function check_subject_by_id(&$Inf)
	{
		global $PowerBB;

		if (empty($PowerBB->_GET['id']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['The_request_is_not_valid']);
		}

		$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');

		$InfArr 			= 	array();
		$InfArr['where'] 	= 	array('id',$PowerBB->_GET['id']);

		$Inf = $PowerBB->subject->GetSubjectInfo($InfArr);

		if ($Inf == false)
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['subject_requested_does_not_exist']);
		}

		$PowerBB->functions->CleanVariable($Inf,'html');
	}

	function check_reply_by_id(&$ReplyInf)
	{
		global $PowerBB;

		if (empty($PowerBB->_GET['id']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['The_request_is_not_valid']);
		}

		$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');

		$ReplyArr = array();
		$ReplyArr['where'] = array('id',$PowerBB->_GET['id']);

		$ReplyInf = $PowerBB->core->GetInfo($GetReplyInfo,'reply');

		if ($ReplyInf == false)
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['subject_requested_does_not_exist']);
		}

		$PowerBB->functions->CleanVariable($ReplyInf,'html');
	}
}

?>
