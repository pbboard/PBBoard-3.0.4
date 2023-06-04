<?php
(!defined('IN_PowerBB')) ? die() : '';

define('CLASS_NAME','PowerBBManagementMOD');
include('common.php');
class PowerBBManagementMOD
{
	function run()
	{
		global $PowerBB;

		$PowerBB->_GET['section'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['section'],'intval');

			if (!$PowerBB->_CONF['member_permission'])
			{
			 $PowerBB->functions->ShowHeader();
			 $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
			 $PowerBB->functions->GetFooter();
			}

			if ($PowerBB->_GET['do_replys'])
			{

				if ($PowerBB->_POST['do'] == 'moveposts')
				{
				   $PowerBB->functions->ShowHeader();
					$this->_Moveposts();
				   $PowerBB->functions->GetFooter();
   				}
				elseif ($PowerBB->_GET['startmoveposts'])
				{
					$this->_StartMovePosts();
				}
				elseif ($PowerBB->_POST['do'] == 'deleteposts')
				{
				   $PowerBB->functions->ShowHeader();
					$this->_Deleteposts();
				   $PowerBB->functions->GetFooter();
				}
				elseif ($PowerBB->_GET['startdeleteposts'])
				{
					$this->_StartDeleteposts();
				}
				elseif ($PowerBB->_POST['do'] == 'approveposts')
				{
					$this->_Approveposts();
				}
				elseif ($PowerBB->_POST['do'] == 'unapproveposts')
				{
					$this->_Unapproveposts();
				}
				elseif ($PowerBB->_GET['operator'] == 'delete')
				{
				   $PowerBB->functions->ShowHeader();
					$this->__ReplyDelete();
				   $PowerBB->functions->GetFooter();
			    }
				elseif ($PowerBB->_GET['start_del_reply'])
				{
					$this->__ReplyDeleteStart();
				}
				else
				{
					header("Location: index.php");
					exit;
				}

			}
			elseif($PowerBB->_GET['do_review_reply'])
			{			 if ($PowerBB->_POST['deletposts'])
				{
					$this->_deletposts_section();
   				}
				elseif($PowerBB->_POST['approveposts'])
				{
					$this->_approveposts_section();
				}
			}
			else
			{
				header("Location: index.php");
				exit;
			}
	}


	function _Moveposts()
	{
		global $PowerBB;

			$PowerBB->_GET['subject_id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['subject_id'],'intval');

		if (empty($PowerBB->_POST['check']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['You_do_not_select_any_posts']);
		}

		if (empty($PowerBB->_GET['subject_id']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}

	 $PowerBB->template->assign('subject_id',$PowerBB->_GET['subject_id']);
      $PowerBB->template->display('replys_move_index');
	 $Replys_M = $PowerBB->_POST['check'];
      	$x = 0;
       foreach ($Replys_M as $GetReplys)
       {
		$Replys =intval($GetReplys);
		$PowerBB->template->assign('replys',$Replys);
			$x += 1;
	      $PowerBB->template->assign('x',$x);
       $PowerBB->template->display('replys_move_index2');
		}

	}

	function _StartMovePosts()
	{
		global $PowerBB;

        $PowerBB->_POST['subject_id'] = str_replace($PowerBB->functions->GetForumAdress()."index.php?page=topic&show=1&id=","", $PowerBB->_POST['subject_id'] );
        $PowerBB->_POST['subject_id'] = str_replace($PowerBB->functions->GetForumAdress()."t","", $PowerBB->_POST['subject_id'] );
        $urlhtml = ".html";
        $PowerBB->_POST['subject_id'] = str_replace($urlhtml,'', $PowerBB->_POST['subject_id'] );
		$PowerBB->_POST['subject_id'] = $PowerBB->functions->CleanVariable($PowerBB->_POST['subject_id'],'intval');

		if (empty($PowerBB->_POST['check']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['You_do_not_select_any_posts']);
		}

		if (empty($PowerBB->_POST['subject_id']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_enter_the_link_to_the_topic']);
		}


        $SubjectArr = array();
		$SubjectArr['where'] = array('id',$PowerBB->_POST['subject_id']);

		$SubjectInfo = $PowerBB->core->GetInfo($SubjectArr,'subject');


		if (!$SubjectInfo)
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Sorry_requested_topic_does_not_exist']);
		}


        $SubjectOldArr = array();
		$SubjectOldArr['where'] = array('id',$PowerBB->_POST['subject_old']);

		$SubjectInfoOld = $PowerBB->subject->GetSubjectInfo($SubjectOldArr);

		$SecArr 			= 	array();
        $SecArr['where'] 	= 	array('id',$SubjectInfoOld['section']);

        $this->SectionInfo = $PowerBB->core->GetInfo($SecArr,'section');



		$Reply_M = $PowerBB->_POST['check'];
       foreach ($Reply_M as $GetReply)
       {

            /////
			$ReplyArr = array();
			$ReplyArr['where'] = array('id',intval($GetReply));

			$ReplyInfo = $PowerBB->core->GetInfo($ReplyArr,'reply');

            //////
			$UpdateArr 				= 	array();
			$UpdateArr['field']		= 	array();

			$UpdateArr['field']['section'] 		= 	$SubjectInfo['section'];
			$UpdateArr['field']['subject_id'] 		= 	$PowerBB->_POST['subject_id'];
			$UpdateArr['field']['title'] 		= 	$SubjectInfo['title'];

			$UpdateArr['where'] 				= 	array('id',intval($GetReply));

			$updateMove = $PowerBB->reply->UpdateReply($UpdateArr);
            /////

              if($updateMove)
              {
              $subject_id = $PowerBB->_POST['subject_id'];              $reply_nm = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(1),id FROM " . $PowerBB->table['reply'] . " WHERE subject_id='$subject_id' LIMIT 1"));

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
				$SubjectArr['field']['reply_number'] 	= 	$reply_nm;
				$SubjectArr['field']['last_replier'] 	= 	$last_replier;
				$SubjectArr['field']['write_time']   	= 	$write_time;
				$SubjectArr['where'] 					= 	array('id',$PowerBB->_POST['subject_id']);

				$updates = $PowerBB->subject->UpdateSubject($SubjectArr);
                //

              $Subjectoldid = $PowerBB->_POST['subject_old'];
              $replyold_nm = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(1),id FROM " . $PowerBB->table['reply'] . " WHERE subject_id='$Subjectoldid' LIMIT 1"));

				$Getlast_replierold = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['reply'] . " WHERE subject_id = '$Subjectoldid' AND delete_topic<>1 AND review_reply<>1 ORDER by write_time DESC");
				$GetLast_replierFormold = $PowerBB->DB->sql_fetch_array($Getlast_replierold);
				if (!$GetLast_replierFormold)
				{
				$last_replierold = '';
				}
				else
				{
				$last_replierold = $GetLast_replierFormold['writer'];
				}

				if (empty($GetLast_replierFormold['write_time']))
				{
				$write_timeold = $SubjectInfoOld['native_write_time'];
				}
				else
				{
				$write_timeold = $GetLast_replierFormold['write_time'];
				}
				$SubjectoldArr 							= 	array();
				$SubjectoldArr['field'] 					= 	array();
				$SubjectoldArr['field']['reply_number'] 	= 	$replyold_nm;
				$SubjectoldArr['field']['last_replier'] 	= 	$last_replierold;
				$SubjectoldArr['field']['write_time']   	= 	$write_timeold;

				$SubjectoldArr['where'] 					= 	array('id',$PowerBB->_POST['subject_old']);

				$updateSubjectold = $PowerBB->subject->UpdateSubject($SubjectoldArr);
             }


	     }

           $UpdateSectionCache1 = $PowerBB->functions->UpdateSectionCache($SubjectInfo['section']);
           $UpdateSectionCache2 = $PowerBB->functions->UpdateSectionCache($SubjectInfoOld['section']);

           // $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Specific_posts_have_been_moved_successfully']);
			$PowerBB->functions->header_redirect('index.php?page=topic&show=1&id=' . $PowerBB->_POST['subject_id']);



	}

	function _Deleteposts()
	{
		global $PowerBB;

			$PowerBB->_GET['subject_id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['subject_id'],'intval');

		if (empty($PowerBB->_POST['check']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['You_do_not_select_any_posts']);
		}

		if (empty($PowerBB->_GET['subject_id']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}


	 	 $SubjectInfoArr				=	array();
	     $SubjectInfoArr['where'] 	= 	array('id',$PowerBB->_GET['subject_id']);

		 $SubjectDelInfo = $PowerBB->subject->GetSubjectInfo($SubjectInfoArr);

	     $PowerBB->template->assign('SubjectDelInfo',$SubjectDelInfo);
	     $PowerBB->template->assign('subject',$PowerBB->_GET['subject_id']);

	  $PowerBB->template->assign('subject_id',$PowerBB->_GET['subject_id']);
	  $PowerBB->template->assign('section_id',$SubjectDelInfo['section']);
      $PowerBB->template->display('replys_delet_index');
	  $Replys_D = $PowerBB->_POST['check'];
       foreach ($Replys_D as $GetReplys)
       {
		$PowerBB->template->assign('replys',$GetReplys);
       $PowerBB->template->display('replys_delet_index2');
		}

	}


	function _StartDeleteposts()
	{
		global $PowerBB;

		$Reply_D = $PowerBB->_POST['check'];
       foreach ($Reply_D as $GetReply)
       {


            /////

		    if ($PowerBB->_POST['deletetype'] == 1)
		     {
			   $UpdateArr 			= array();
			   $UpdateArr['where'] = array('id',intval($GetReply));

		     	$update = $PowerBB->reply->MoveReplyToTrash($UpdateArr);

				$subject_id = $PowerBB->_POST['subject_id'];
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
				$SubjectArr['where'] = array('id',$PowerBB->_POST['subject_id']);

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
				$SubjectArr['where'] 					= 	array('id',$PowerBB->_POST['subject_id']);

				$updates = $PowerBB->subject->UpdateSubject($SubjectArr);

			 }
		   else
		     {
				  $DeleteReplyArr				=	array();
		          $DeleteReplyArr['where'] 	= 	array('id',intval($GetReply));
				  $delReply = $PowerBB->reply->DeleteReply($DeleteReplyArr);

				$subject_id = $PowerBB->_POST['subject_id'];
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
				$SubjectArr['where'] = array('id',$PowerBB->_POST['subject_id']);

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
				$SubjectArr['where'] 					= 	array('id',$PowerBB->_POST['subject_id']);

				$updates = $PowerBB->subject->UpdateSubject($SubjectArr);
		     }



		  }


                  $UpdateSectionCache1 = $PowerBB->functions->UpdateSectionCache($PowerBB->_POST['section_id']);

           // $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Has_been_deleted_successfully_identified_Posts']);
			$PowerBB->functions->header_redirect('index.php?page=topic&show=1&id=' . $PowerBB->_POST['subject_id']);

	}

	function _Approveposts()
	{
		global $PowerBB;

			$PowerBB->_GET['subject_id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['subject_id'],'intval');

		if (empty($PowerBB->_POST['check']))
		{
		    $PowerBB->functions->ShowHeader();
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['You_do_not_select_any_posts']);
			$PowerBB->functions->GetFooter();
		}

		$Reply_M = $PowerBB->_POST['check'];
       foreach ($Reply_M as $GetReply)
       {
 			$UpdateArr 				= 	array();
			$UpdateArr['field']		= 	array();
			$UpdateArr['field']['review_reply'] 		= 	'0';
			$UpdateArr['where'] 				= 	array('id',intval($GetReply));

			$update = $PowerBB->reply->UpdateReply($UpdateArr);


				$ReplyArr 			= 	array();
		        $ReplyArr['where'] 	= 	array('id',intval($GetReply));

		        $this->ReplyInfo = $PowerBB->core->GetInfo($ReplyArr,'reply');


				$SecArr 			= 	array();
		        $SecArr['where'] 	= 	array('id',$this->ReplyInfo['section']);

		        $this->SectionInfo = $PowerBB->core->GetInfo($SecArr,'section');

		      $UpdateSectionCache = $PowerBB->functions->UpdateSectionCache($this->ReplyInfo['section']);



	    }

		     // Update subject review number
		      $SubjectInfid = $PowerBB->_GET['subject_id'];
		      $SubjectInfReviewNum = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(1),id FROM " . $PowerBB->table['reply'] . " WHERE subject_id='$SubjectInfid' and review_reply='1' LIMIT 1"));
		      $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['subject'] . " SET review_reply='$SubjectInfReviewNum' WHERE id='$SubjectInfid'");

           // $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Approved_posts_specific_successfully']);
			$PowerBB->functions->header_redirect('index.php?page=topic&show=1&id=' . $PowerBB->_GET['subject_id']);
   }

	function _Unapproveposts()
	{
		global $PowerBB;

			$PowerBB->_GET['subject_id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['subject_id'],'intval');

		if (empty($PowerBB->_POST['check']))
		{
		    $PowerBB->functions->ShowHeader();
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['You_do_not_select_any_posts']);
			$PowerBB->functions->GetFooter();
		}

		$Reply_M = $PowerBB->_POST['check'];
       foreach ($Reply_M as $GetReply)
       {
 			$UpdateArr 				= 	array();
			$UpdateArr['field']		= 	array();
			$UpdateArr['field']['review_reply'] 		= 	'1';
			$UpdateArr['where'] 				= 	array('id',intval($GetReply));

			$update = $PowerBB->reply->UpdateReply($UpdateArr);

				$ReplyArr 			= 	array();
		        $ReplyArr['where'] 	= 	array('id',intval($GetReply));

		        $this->ReplyInfo = $PowerBB->core->GetInfo($ReplyArr,'reply');


				$SecArr 			= 	array();
		        $SecArr['where'] 	= 	array('id',$this->ReplyInfo['section']);

		        $this->SectionInfo = $PowerBB->core->GetInfo($SecArr,'section');

		      $UpdateSectionCache = $PowerBB->functions->UpdateSectionCache($this->ReplyInfo['section']);


	     }
		     // Update subject review number
		      $SubjectInfid = $PowerBB->_GET['subject_id'];
		      $SubjectInfReviewNum = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(1),id FROM " . $PowerBB->table['reply'] . " WHERE subject_id='$SubjectInfid' and review_reply='1' LIMIT 1"));
		      $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['subject'] . " SET review_reply='$SubjectInfReviewNum' WHERE id='$SubjectInfid'");

            //$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Was_not_to_approve_the_posts_identified_successfully']);
			$PowerBB->functions->header_redirect('index.php?page=topic&show=1&id=' . $PowerBB->_GET['subject_id']);
   }

	function __ReplyDelete()
	{
		global $PowerBB;
			$PowerBB->_GET['subject_id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['subject_id'],'intval');
			$PowerBB->_GET['section'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['section'],'intval');
			$PowerBB->_GET['reply_id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['reply_id'],'intval');


		if (empty($PowerBB->_GET['subject_id']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}
		if (empty($PowerBB->_GET['reply_id']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}
		if (empty($PowerBB->_GET['section']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}


	 	 $SubjectInfoArr				=	array();
	     $SubjectInfoArr['where'] 	= 	array('id',$PowerBB->_GET['subject_id']);

		 $SubjectDelInfo = $PowerBB->subject->GetSubjectInfo($SubjectInfoArr);

		if (!$SubjectDelInfo)
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Sorry_requested_topic_does_not_exist']);
		}
			$ReplyArr = array();
			$ReplyArr['where'] = array('id',$PowerBB->_GET['reply_id']);

			$ReplyInfo = $PowerBB->core->GetInfo($ReplyArr,'reply');

		if (!$ReplyInfo)
		{
			$PowerBB->functions->error('لن نتمكن من حذف هذه المشاركة لعدم وجودها في قاعدة البيانات.');
		}
		if ($PowerBB->_CONF['group_info']['group_mod'] == '1')
		{
			if ($PowerBB->_CONF['group_info']['del_reply'] == '0')
			{
			//$PowerBB->functions->ShowHeader();
			 if ($PowerBB->_CONF['group_info']['id'] != '1'
			 AND  $PowerBB->_CONF['group_info']['id'] != '2')
			 {
              $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
              }
			}

		}
        else
		{

			if ($PowerBB->_CONF['group_info']['id'] != '1'
			   AND  $PowerBB->_CONF['group_info']['id'] != '2')
			{

				if ($PowerBB->_CONF['group_info']['group_mod'] == 0
				and $ReplyInfo['writer'] != $PowerBB->_CONF['member_row']['username'])
				{                      $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
				}
				if ($SubjectDelInfo['close'] == '1')
				{
				 //$PowerBB->functions->ShowHeader();
                 $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['This_topic_is_locked']);
				}


		        if ($ReplyInfo['writer'] == $PowerBB->_CONF['member_row']['username'])
		        {
	              if ($PowerBB->functions->section_group_permission($PowerBB->_GET['section'],$PowerBB->_CONF['group_info']['id'],'del_own_reply') == 0)
				  {
					 //$PowerBB->functions->ShowHeader();
                      $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
			       }
		         }

          }
      }


	     $PowerBB->template->assign('SubjectDelInfo',$SubjectDelInfo);
	     $PowerBB->template->assign('subject_id',$PowerBB->_GET['subject_id']);
	     $PowerBB->template->assign('reply_id',$PowerBB->_GET['reply_id']);
	     $PowerBB->template->assign('section_id',$PowerBB->_GET['section']);


	     $PowerBB->template->display('reply_delete_index');


    }

	function __ReplyDeleteStart()
	{
		global $PowerBB;

		if ($PowerBB->_CONF['group_info']['group_mod'] == '1')
		{
			if ($PowerBB->_CONF['group_info']['del_reply'] == '0')
			{
    			if ($PowerBB->_CONF['group_info']['id'] != '1'
			   AND  $PowerBB->_CONF['group_info']['id'] != '2')
			  {
			    $PowerBB->functions->ShowHeader();
			   $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
			  }
			}
		}

			$PowerBB->_GET['subject_id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['subject_id'],'intval');
			$PowerBB->_GET['section'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['section'],'intval');
			$PowerBB->_GET['reply_id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['reply_id'],'intval');



		if (empty($PowerBB->_GET['subject_id']))
		{
		    $PowerBB->functions->ShowHeader();
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}
		if (empty($PowerBB->_GET['reply_id']))
		{
		     $PowerBB->functions->ShowHeader();
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}
		if (empty($PowerBB->_GET['section']))
		{
		    $PowerBB->functions->ShowHeader();
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}

				$SecArr 			= 	array();
		        $SecArr['where'] 	= 	array('id',$PowerBB->_GET['section']);

		        $this->SectionInfo = $PowerBB->core->GetInfo($SecArr,'section');


        $SubjectArr = array();
		$SubjectArr['where'] = array('id',$PowerBB->_GET['subject_id']);

		$SubjectInfo = $PowerBB->core->GetInfo($SubjectArr,'subject');

			$ReplyArr = array();
			$ReplyArr['where'] = array('id',$PowerBB->_GET['reply_id']);

			$ReplyInfo = $PowerBB->core->GetInfo($ReplyArr,'reply');

		if (!$ReplyInfo)
		{
		     $PowerBB->functions->ShowHeader();
			$PowerBB->functions->error('لن نتمكن من حذف هذه المشاركة لعدم وجودها في قاعدة البيانات.');
		}
    			if ($PowerBB->_CONF['group_info']['id'] != '1'
			   AND  $PowerBB->_CONF['group_info']['id'] != '2')
			  {
				if ($PowerBB->_CONF['group_info']['group_mod'] == 0
				and $ReplyInfo['writer'] != $PowerBB->_CONF['member_row']['username'])
				{
				$PowerBB->functions->ShowHeader();
				$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
				}
		      }
	  if ($PowerBB->_POST['deletetype'] == 1)
	  {

		$UpdateArr 			= array();
		$UpdateArr['where'] = array('id',$PowerBB->_GET['reply_id']);

		$update = $PowerBB->reply->MoveReplyToTrash($UpdateArr);

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
		//////////

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

		$updates = $PowerBB->subject->UpdateSubject($SubjectArr);

      }
      else
      {
       			  $DeleteReplyArr				=	array();
		          $DeleteReplyArr['where'] 	= 	array('id',$PowerBB->_GET['reply_id']);
				  $delReply = $PowerBB->reply->DeleteReply($DeleteReplyArr);


					$UpdateSubjectNumber = $PowerBB->cache->UpdateReplyNumber(array('reply_num'	=>	$PowerBB->_CONF['info_row']['reply_number']));

		     		$UpdateArr 					= 	array();
		     		$UpdateArr['field']			=	array();

		     		$UpdateArr['field']['reply_num'] 	= 	$this->SectionInfo['reply_num'] - 1;
		     		$UpdateArr['where']					= 	array('id',$this->SectionInfo['id']);

		     		$UpdateSubjectNumber = $PowerBB->core->Update($UpdateArr,'section');

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
			   //////////
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
       }

  				if ($this->SectionInfo['last_subjectid'] == $PowerBB->_GET['subject_id'])
				{
				 	/**
				 	 *Update Section Cache ;)
				 	 */
                    $SectionCache = $PowerBB->_GET['section'];
					// The number of section's subjects number
					$reply_num = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(1),id FROM " . $PowerBB->table['reply'] . " WHERE section = '$SectionCache' LIMIT 1"));

					$UpdateArr 					= 	array();
					$UpdateArr['field']			=	array();
					$UpdateArr['field']['reply_num'] 	= 	$reply_num;
					$UpdateArr['where']					= 	array('id',$SectionCache);

					$UpdateReplyNumber = $PowerBB->core->Update($UpdateArr,'section');


					$PowerBB->cache->UpdateReplyNumber(array('reply_num'	=>	$reply_num));


					$subject_nm = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(1),id FROM " . $PowerBB->table['subject'] . " WHERE section = '$SectionCache' "));

					// The number of section's subjects number
					$UpdateArr 					= 	array();
					$UpdateArr['field']			=	array();

					$UpdateArr['field']['subject_num'] 	= 	$subject_nm;
					$UpdateArr['where']					= 	array('id',$SectionCache);

					$UpdateSubjectNumber = $PowerBB->core->Update($UpdateArr,'section');


					$PowerBB->cache->UpdateSubjectNumber(array('subject_num'	=>	$subject_nm));


                    $subject_id = $PowerBB->_GET['subject_id'];
					$GetLastqueryReplyForm = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['reply'] . " WHERE subject_id = '$subject_id' AND delete_topic<>1 AND review_reply<>1 ORDER by write_time DESC");
					$GetLastReplyForm = $PowerBB->DB->sql_fetch_array($GetLastqueryReplyForm);

					if (!$GetLastReplyForm)
					{
					// Update Last subject's information in Section Form
					$UpdateLastFormSecArr = array();
					$UpdateLastFormSecArr['field']			=	array();

					$UpdateLastFormSecArr['field']['last_writer'] 		= 	$SubjectInfo['writer'];
					$UpdateLastFormSecArr['field']['last_subject'] 		= 	$SubjectInfo['title'];
					$UpdateLastFormSecArr['field']['last_subjectid'] 	= 	$PowerBB->_GET['subject_id'];
					$UpdateLastFormSecArr['field']['last_date'] 	= 	$PowerBB->_CONF['now'];
					$UpdateLastFormSecArr['field']['last_time'] 	= 	$PowerBB->_CONF['now'];
					$UpdateLastFormSecArr['field']['icon'] 		    = 	$SubjectInfo['icon'];
					$UpdateLastFormSecArr['field']['last_reply'] 	= 	0;
					$UpdateLastFormSecArr['field']['last_berpage_nm']  = 	0;

					$UpdateLastFormSecArr['where'] 		        = 	array('id',$SectionCache);

					// Update Last Form Sec subject's information
					$UpdateLastFormSec = $PowerBB->section->UpdateSection($UpdateLastFormSecArr);
					}
					else
					{

					// Update Last subject's information in Section Form
					$UpdateLastFormSecArr = array();
					$UpdateLastFormSecArr['field']			=	array();

					$UpdateLastFormSecArr['field']['last_writer'] 		= 	$GetLastReplyForm['writer'];
					$UpdateLastFormSecArr['field']['last_subject'] 		= 	$GetLastReplyForm['title'];
					$UpdateLastFormSecArr['field']['last_subjectid'] 	= 	$GetLastReplyForm['subject_id'];
					$UpdateLastFormSecArr['field']['last_date'] 	= 	$GetLastReplyForm['write_time'];
					$UpdateLastFormSecArr['field']['last_time'] 	= 	$GetLastReplyForm['write_time'];
					$UpdateLastFormSecArr['field']['icon'] 		    = 	$GetLastReplyForm['icon'];
					$UpdateLastFormSecArr['field']['last_reply'] 	= 	0;
					$UpdateLastFormSecArr['field']['last_berpage_nm']  = 	0;

					$UpdateLastFormSecArr['where'] 		        = 	array('id',$SectionCache);

					// Update Last Form Sec subject's information
					$UpdateLastFormSec = $PowerBB->section->UpdateSection($UpdateLastFormSecArr);
				   }

					// Get Section Info
					$SecaArr 			= 	array();
					$SecaArr['where'] 	= 	array('id',$SectionCache);

					$this->SecInfo = $PowerBB->section->GetSectionInfo($SecaArr);

					// Update section's cache
					$UpdateArr 				= 	array();
					$UpdateArr['parent'] 	= 	$this->SecInfo['parent'];

					$update_cache = $PowerBB->section->UpdateSectionsCache($UpdateArr);


					unset($UpdateArr);
				}
			// INSERT moderators Action
			$EditAction				=	array();
		    $EditAction['where'] 	= 	array('id',$PowerBB->_GET['subject_id']);

			$action = $PowerBB->core->GetInfo($EditAction,'subject');

		    $subject_title = $action['title'];
		    $time=time()+$PowerBB->_CONF['info_row']['timestamp'];

	        $SmLogsArr 			= 	array();
			$SmLogsArr['field']	=	array();

			$SmLogsArr['field']['username'] 	= 	$PowerBB->_CONF['member_row']['username'];
			$SmLogsArr['field']['edit_action'] 	= 	$PowerBB->_CONF['template']['_CONF']['lang']['Delete_reply_ID'] . $PowerBB->_GET['reply_id'];
			$SmLogsArr['field']['subject_title']= 	$PowerBB->functions->CleanVariable($subject_title,'sql');
			$SmLogsArr['field']['subject_id'] 	= 	$PowerBB->_GET['subject_id'];
			$SmLogsArr['field']['edit_date'] 	= 	date("d/m/Y", $time);

			$insert = $PowerBB->core->Insert($SmLogsArr,'supermemberlogs');

           $UpdateSectionCache2 = $PowerBB->functions->UpdateSectionCache($PowerBB->_GET['section']);

			//$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Deleted_reply_successfully']);
			$PowerBB->functions->header_redirect('index.php?page=topic&amp;show=1&amp;id=' . $PowerBB->_GET['subject_id']);

	}

	function _approveposts_section()
	{
		global $PowerBB;

		$PowerBB->_GET['section'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['section'],'intval');
       $section = $PowerBB->_GET['section'];
		if (empty($PowerBB->_POST['check']))
		{
		    $PowerBB->functions->ShowHeader();
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['You_do_not_select_any_posts']);
			$PowerBB->functions->GetFooter();
		}

		$Reply_M = $PowerBB->_POST['check'];
       foreach ($Reply_M as $GetReply)
       {
 			$UpdateArr 				= 	array();
			$UpdateArr['field']		= 	array();
			$UpdateArr['field']['review_reply'] 		= 	'0';
			$UpdateArr['where'] 				= 	array('id',intval($GetReply));

			$update = $PowerBB->reply->UpdateReply($UpdateArr);


				$ReplyArr 			= 	array();
		        $ReplyArr['where'] 	= 	array('id',intval($GetReply));

		        $this->ReplyInfo = $PowerBB->core->GetInfo($ReplyArr,'reply');


				$SecArr 			= 	array();
		        $SecArr['where'] 	= 	array('id',$this->ReplyInfo['subject_id']);

		        $this->SubjectInfo = $PowerBB->core->GetInfo($SecArr,'subject');


				$Subjectid =  $this->ReplyInfo['subject_id'];
				//$review_reply_nm = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(1),id FROM " . $PowerBB->table['reply'] . " WHERE section = '$section' and subject_id='$Subjectid' and review_reply='1' LIMIT 1"));

				$SubjectArr 							= 	array();
				$SubjectArr['field'] 					= 	array();
				$SubjectArr['field']['review_reply'] 	= 	$this->SubjectInfo['review_reply']-1;
				$SubjectArr['where'] 					= 	array('id',$this->ReplyInfo['subject_id']);

				$update = $PowerBB->subject->UpdateSubject($SubjectArr);

		      $UpdateSectionCache = $PowerBB->functions->UpdateSectionCache($this->ReplyInfo['section']);



	    }

		     // Update subject review number
		      $SubjectInfid = $PowerBB->_GET['subject_id'];
		      $SubjectInfReviewNum = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(1),id FROM " . $PowerBB->table['reply'] . " WHERE subject_id='$SubjectInfid' and review_reply='1' LIMIT 1"));
		      $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['subject'] . " SET review_reply='$SubjectInfReviewNum' WHERE id='$SubjectInfid'");

				$PowerBB->functions->ShowHeader();
                $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Approved_posts_specific_successfully']);
				$PowerBB->functions->redirect($PowerBB->_SERVER['HTTP_REFERER']);
				$PowerBB->functions->GetFooter();
   }






	function _deletposts_section()
	{
		global $PowerBB;

		if ($PowerBB->_CONF['group_info']['group_mod'] == '1')
		{
			if ($PowerBB->_CONF['group_info']['del_reply'] == '0')
			{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
			}
		}
     $PowerBB->_GET['section'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['section'],'intval');
      $section = $PowerBB->_GET['section'];



			$Reply_D = $PowerBB->_POST['check'];
       foreach ($Reply_D as $GetReply)
       {

 			$UpdateArr 				= 	array();
			$UpdateArr['field']		= 	array();
			$UpdateArr['field']['review_reply'] 		= 	'0';
			$UpdateArr['where'] 				= 	array('id',intval($GetReply));

			$update = $PowerBB->reply->UpdateReply($UpdateArr);

				$ReplyArr 			= 	array();
		        $ReplyArr['where'] 	= 	array('id',intval($GetReply));

		        $this->ReplyInfo = $PowerBB->core->GetInfo($ReplyArr,'reply');


          $Subjectid =  $this->ReplyInfo['subject_id'];
           $review_reply_nm = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(1),id FROM " . $PowerBB->table['reply'] . " WHERE section = '$section' and subject_id='$Subjectid' and review_reply='1' LIMIT 1"));
           $reply_nm = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(1),id FROM " . $PowerBB->table['reply'] . " WHERE section = '$section' and subject_id='$Subjectid' LIMIT 1"));

				$SecArr 			= 	array();
		        $SecArr['where'] 	= 	array('id',$PowerBB->_GET['section']);

		        $this->SectionInfo = $PowerBB->core->GetInfo($SecArr,'section');



		        $SubjectArr = array();
				$SubjectArr['where'] = array('id',$this->ReplyInfo['subject_id']);

				$SubjectInfo = $PowerBB->core->GetInfo($SubjectArr,'subject');


                    $subject_id = $this->ReplyInfo['subject_id'];
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
				    $SubjectArr['field']['review_reply'] 	= 	$this->SubjectInfo['review_reply']-1;
					$SubjectArr['field']['reply_number'] 	= 	$reply_nm-1;
					$SubjectArr['field']['last_replier'] 	= 	$last_replier;
					$SubjectArr['field']['write_time']   	= 	$write_time;
					$SubjectArr['where'] 					= 	array('id',$subject_id);

					$update = $PowerBB->subject->UpdateSubject($SubjectArr);

 				if ($this->SectionInfo['last_subjectid'] == $subject_id)
				{
				 	/**
				 	 *Update Section Cache ;)
				 	 */
                    $SectionCache = $PowerBB->_GET['section'];
					// The number of section's subjects number
					$reply_num = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(1),id FROM " . $PowerBB->table['reply'] . " WHERE section = '$SectionCache' LIMIT 1"));

					$UpdateArr 					= 	array();
					$UpdateArr['field']			=	array();
					$UpdateArr['field']['reply_num'] 	= 	$reply_num;
					$UpdateArr['where']					= 	array('id',$SectionCache);

					$UpdateReplyNumber = $PowerBB->core->Update($UpdateArr,'section');


					$PowerBB->cache->UpdateReplyNumber(array('reply_num'	=>	$reply_num));


					$subject_nm = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(1),id FROM " . $PowerBB->table['subject'] . " WHERE section = '$SectionCache' "));

					// The number of section's subjects number
					$UpdateArr 					= 	array();
					$UpdateArr['field']			=	array();

					$UpdateArr['field']['subject_num'] 	= 	$subject_nm;
					$UpdateArr['where']					= 	array('id',$SectionCache);

					$UpdateSubjectNumber = $PowerBB->core->Update($UpdateArr,'section');


					$PowerBB->cache->UpdateSubjectNumber(array('subject_num'	=>	$subject_nm));

					$GetLastqueryReplyForm = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['reply'] . " WHERE subject_id = '$subject_id' AND delete_topic<>1 AND review_reply<>1 ORDER by write_time DESC");
					$GetLastReplyForm = $PowerBB->DB->sql_fetch_array($GetLastqueryReplyForm);

					if (!$GetLastReplyForm)
					{
					// Update Last subject's information in Section Form
					$UpdateLastFormSecArr = array();
					$UpdateLastFormSecArr['field']			=	array();

					$UpdateLastFormSecArr['field']['last_writer'] 		= 	$SubjectInfo['writer'];
					$UpdateLastFormSecArr['field']['last_subject'] 		= 	$SubjectInfo['title'];
					$UpdateLastFormSecArr['field']['last_subjectid'] 	= 	$subject_id;
					$UpdateLastFormSecArr['field']['last_date'] 	= 	$PowerBB->_CONF['now'];
					$UpdateLastFormSecArr['field']['last_time'] 	= 	$PowerBB->_CONF['now'];
					$UpdateLastFormSecArr['field']['icon'] 		    = 	$SubjectInfo['icon'];
					$UpdateLastFormSecArr['field']['last_reply'] 	= 	0;
					$UpdateLastFormSecArr['field']['last_berpage_nm']  = 	0;

					$UpdateLastFormSecArr['where'] 		        = 	array('id',$SectionCache);

					// Update Last Form Sec subject's information
					$UpdateLastFormSec = $PowerBB->section->UpdateSection($UpdateLastFormSecArr);
					}
					else
					{

					// Update Last subject's information in Section Form
					$UpdateLastFormSecArr = array();
					$UpdateLastFormSecArr['field']			=	array();

					$UpdateLastFormSecArr['field']['last_writer'] 		= 	$GetLastReplyForm['writer'];
					$UpdateLastFormSecArr['field']['last_subject'] 		= 	$GetLastReplyForm['title'];
					$UpdateLastFormSecArr['field']['last_subjectid'] 	= 	$GetLastReplyForm['subject_id'];
					$UpdateLastFormSecArr['field']['last_date'] 	= 	$GetLastReplyForm['write_time'];
					$UpdateLastFormSecArr['field']['last_time'] 	= 	$GetLastReplyForm['write_time'];
					$UpdateLastFormSecArr['field']['icon'] 		    = 	$GetLastReplyForm['icon'];
					$UpdateLastFormSecArr['field']['last_reply'] 	= 	0;
					$UpdateLastFormSecArr['field']['last_berpage_nm']  = 	0;

					$UpdateLastFormSecArr['where'] 		        = 	array('id',$SectionCache);

					// Update Last Form Sec subject's information
					$UpdateLastFormSec = $PowerBB->section->UpdateSection($UpdateLastFormSecArr);
				   }

					// Get Section Info
					$SecaArr 			= 	array();
					$SecaArr['where'] 	= 	array('id',$SectionCache);

					$this->SecInfo = $PowerBB->section->GetSectionInfo($SecaArr);

					// Update section's cache
					$UpdateArr 				= 	array();
					$UpdateArr['parent'] 	= 	$this->SecInfo['parent'];

					$update_cache = $PowerBB->section->UpdateSectionsCache($UpdateArr);


					unset($UpdateArr);
				}

		  $DeleteReplyArr				=	array();
          $DeleteReplyArr['where'] 	= 	array('id',intval($GetReply));
		  $delReply = $PowerBB->reply->DeleteReply($DeleteReplyArr);

		  }

                $UpdateSubjectNumber = $PowerBB->cache->UpdateReplyNumber(array('reply_num'	=>	$PowerBB->_CONF['info_row']['reply_number']));
                  $UpdateSectionCache1 = $PowerBB->functions->UpdateSectionCache($PowerBB->_GET['section']);

				$PowerBB->functions->ShowHeader();
				$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Deleted_reply_successfully']);
				$PowerBB->functions->redirect($PowerBB->_SERVER['HTTP_REFERER']);
				$PowerBB->functions->GetFooter();


	}

}
?>
