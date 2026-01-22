<?php

(!defined('IN_PowerBB')) ? die() : '';

$CALL_SYSTEM				=	array();
$CALL_SYSTEM['SUBJECT'] 	= 	true;
$CALL_SYSTEM['SECTION'] 	= 	true;
$CALL_SYSTEM['MODERATORS'] 	= 	true;
$CALL_SYSTEM['REPLY'] 		= 	true;
$CALL_SYSTEM['SUPERMEMBERLOGS'] 			= 	true;

define('JAVASCRIPT_PowerCode',true);



define('CLASS_NAME','PowerBBAJAXtMOD');

include('common.php');
class PowerBBAJAXtMOD
{
	function run()
	{
		global $PowerBB;

		if ($PowerBB->_CONF['member_permission'])
		{
			if ($PowerBB->_GET['subjects'])
			{
		      $PowerBB->_POST['m_subject'] = $PowerBB->functions->CleanVariable($PowerBB->_POST['m_subject'],'intval');

				if ($PowerBB->_GET['rename'])
				{
					$this->_SubjectRename();
				}
				else
				{
			  	    $SubjectArr = array();
					$SubjectArr['where'] = array('id',$PowerBB->_POST['m_subject']);

					$SubjectInfo = $PowerBB->core->GetInfo($SubjectArr,'subject');
					echo "<a href='index.php?page=topic&show=1&id=".$PowerBB->_POST['m_subject']."{$password}'>".$SubjectInfo['title']."</a>";
				}
			}
			elseif ($PowerBB->_GET['coverPhotoUpload'])
			{
			 $this->_coverPhotoUpload();
			}
			elseif ($PowerBB->_GET['coverPhotoUploadStart'])
			{
			if ($PowerBB->_GET['ajaxValidate'])
			 {
			 $this->_coverPhotoUploadStart();
			 }
			}
			elseif ($PowerBB->_GET['coverPhotoRemove'] == 1)
			{
			 $this->_coverPhotoRemove();
			}
			elseif ($PowerBB->_GET['editreplyajax'] == 1)
			{
			 $this->_editreplyajax();
			}
			elseif ($PowerBB->_GET['editsubjectajax'] == 1)
			{
			 $this->_editsubjectajax();
			}
			else
			{
			 $PowerBB->functions->ShowHeader();
             $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Sorry_url_not_true']);
             $PowerBB->functions->GetFooter();
			}
		}
		else
		{
			 $PowerBB->functions->ShowHeader();
             $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Sorry_url_not_true']);
             $PowerBB->functions->GetFooter();
		}
	}


	function _SubjectRename()
	{
		global $PowerBB;
		if (empty($PowerBB->_POST['title']))
		{
			exit($PowerBB->_CONF['template']['_CONF']['lang']['no_title']);
		}


		$TitlePost = utf8_decode($PowerBB->_POST['title']);
		$Post_max_num = strlen($TitlePost) <= $PowerBB->_CONF['info_row']['post_title_max'];
		if ($Post_max_num)
		{
		 // Continue
		}
		else
		{
		$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['post_text_max_subjects']);
		$PowerBB->functions->error_stop();
		}

		$TitlePost = preg_replace('/\s+/', '', $TitlePost);
		$Post_less_num = strlen($TitlePost) >= $PowerBB->_CONF['info_row']['post_title_min'];
		if  ($Post_less_num)
		{
		 // Continue
		}
		else
		{
		 $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['post_text_min_subjects']);
		  $PowerBB->functions->error_stop();
		}


		$PowerBB->_POST['title'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['title'],'html');
		$PowerBB->_POST['title'] = $PowerBB->functions->CleanVariable($PowerBB->_POST['title'],'sql');
        $PowerBB->_POST['title'] = $PowerBB->Powerparse->censor_words($PowerBB->_POST['title']);

		$SecArr 			= 	array();
		$SecArr['field'] 	= 	array();

		$SecArr['field']['title'] 	= 	$PowerBB->_POST['title'];
		$SecArr['field']['actiondate'] 	= 	$PowerBB->_CONF['now'];
     	$SecArr['field']['action_by'] 	= 	$PowerBB->_CONF['member_row']['username'];
		$SecArr['where']			= 	array('id',$PowerBB->_POST['m_subject']);

		$update = $PowerBB->core->Update($SecArr,'subject');

		$SectionArr 			= 	array();
		$SectionArr['field'] 	= 	array();

		$SectionArr['field']['last_subject'] 	= 	$PowerBB->_POST['title'];
		$SectionArr['where']			= 	array('last_subjectid',$PowerBB->_POST['m_subject']);

		$updateSection = $PowerBB->core->Update($SectionArr,'section');

		if ($updateSection)
		{
			  	    $SubjectArr = array();
					$SubjectArr['where'] = array('id',$PowerBB->_POST['m_subject']);

					$SubjectInfo = $PowerBB->core->GetInfo($SubjectArr,'subject');

     				$SecArr 			= 	array();
					$SecArr['where'] 	= 	array('id',$SubjectInfo['section']);

					$SectionInfo = $PowerBB->core->GetInfo($SecArr,'section');

     		// Update section's cache
     		$UpdateArr 				= 	array();
     		$UpdateArr['parent'] 	= 	$SectionInfo['parent'];

     		$update_cache = $PowerBB->section->UpdateSectionsCache($UpdateArr);

     		unset($UpdateArr);

			$cache = $PowerBB->section->UpdateSectionsCache(array('parent'=>$SubjectInfo['section']));
		}

	        $UpdateSectionCache = $PowerBB->functions->UpdateSectionCache($SubjectInfo['section']);

		if ($update)
		{
			echo "<a href='index.php?page=topic&show=1&id=".$PowerBB->_POST['m_subject']."{$password}'>".$PowerBB->_POST['title']."</a>";
		}
	}



	function _coverPhotoRemove()
	{
		global $PowerBB;

		if(!isset($_SESSION['csrf']))
		{
		header("Location: index.php");
		exit;
		}

		$user_id = $PowerBB->_CONF['member_row']['id'];

		$user_id = $PowerBB->functions->CleanVariable($user_id,'intval');

		if (file_exists($PowerBB->_CONF['member_row']['profile_cover_photo'])) {
		unlink($PowerBB->_CONF['member_row']['profile_cover_photo']);
		}
		$UPDATE_user  = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['member'] . " SET profile_cover_photo = '' WHERE id = $user_id");

		header("Location: ".$PowerBB->_SERVER['HTTP_REFERER']);
		exit;
	}

	function _editreplyajax()
	{
		global $PowerBB;
         if ($PowerBB->_SERVER['REQUEST_METHOD'] != 'POST')
         {
			 $PowerBB->functions->ShowHeader();
             $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Sorry_url_not_true']);
             $PowerBB->functions->GetFooter();
         }
        $PowerBB->_POST['message'] = str_replace('target="_blank" ','',$PowerBB->_POST['message']);

			// mention users tag replace
			if($PowerBB->functions->mention_permissions())
			{
			 $PowerBB->_POST['message'] = preg_replace_callback('#\[mention\](.+)\[\/mention\]#iUs', array($this, 'mention_subject_callback'), $PowerBB->_POST['message']);
			}
        $error_stop = true;
		$TextPost = utf8_decode($PowerBB->_POST['message']);
 		$TextPost = preg_replace('/\s+/', '', $TextPost);
		$TextPost = preg_replace('#\[IMG\](.*)\[/IMG\]#siU', '', $TextPost);
		$TextPost = preg_replace('#[(.*)]#siU', '', $TextPost);
		$TextPost = preg_replace('#[/(.*)]#siU', '', $TextPost);
		$TextPost = $PowerBB->Powerparse->remove_message_quotes($TextPost);

		if (empty($TextPost))
		{
		$PowerBB->_CONF['template']['_CONF']['lang']['post_text_min'] = str_replace("5", $PowerBB->_CONF['info_row']['post_text_min'], $PowerBB->_CONF['template']['_CONF']['lang']['post_text_min']);
		$error_msg =($PowerBB->_CONF['template']['_CONF']['lang']['post_text_min']);
		$error_stop = false;

		}

		if(strlen($TextPost) >= $PowerBB->_CONF['info_row']['post_text_min'])
		{
		// Continue
		}
		else
		{
		$PowerBB->_CONF['template']['_CONF']['lang']['post_text_min'] = str_replace("5", $PowerBB->_CONF['info_row']['post_text_min'], $PowerBB->_CONF['template']['_CONF']['lang']['post_text_min']);
		$error_msg =($PowerBB->_CONF['template']['_CONF']['lang']['post_text_min']);
		$error_stop = false;
		}

		if (isset($TextPost{$PowerBB->_CONF['info_row']['post_text_max']}))
		{
		 $PowerBB->_CONF['template']['_CONF']['lang']['post_text_max'] = str_replace("30000", $PowerBB->_CONF['info_row']['post_text_max'], $PowerBB->_CONF['template']['_CONF']['lang']['post_text_max']);
		 $error_msg =($PowerBB->_CONF['template']['_CONF']['lang']['post_text_max']);
		 $error_stop = false;
		}

       if($error_stop)
       {
		$ReplyArr 			= 	array();
		$ReplyArr['field'] 	= 	array();

		$ReplyArr['field']['text'] 	        = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['message'],'nohtml');
		$ReplyArr['field']['actiondate'] 	= 	$PowerBB->_CONF['now'];
     	$ReplyArr['field']['action_by'] 	= 	$PowerBB->_CONF['member_row']['username'];
		$ReplyArr['where']			        = 	array('id',$PowerBB->_POST['reply_id']);

		$update = $PowerBB->core->Update($ReplyArr,'reply');
       }
		// show message
		//$PowerBB->_POST['message'] = $PowerBB->Powerparse->replace_htmlentities($PowerBB->_POST['message']);
		$PowerBB->_POST['message'] = $PowerBB->Powerparse->replace($PowerBB->_POST['message']);
		$PowerBB->Powerparse->replace_smiles($PowerBB->_POST['message']);
		$PowerBB->_POST['message'] = $PowerBB->Powerparse->censor_words($PowerBB->_POST['message']);

		echo '<div class="text">'.$PowerBB->_POST['message'].'</div>';
		if($error_stop == false)
		{
		echo '<br /><div class="msg_row1" style="width:auto;padding:10px;border:1px solid #F9AA00;">'.$error_msg.'</div><br />';
		}
        exit;
	 }

	function _editsubjectajax()
	{
		global $PowerBB;
         if ($PowerBB->_SERVER['REQUEST_METHOD'] != 'POST')
         {
			 $PowerBB->functions->ShowHeader();
             $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Sorry_url_not_true']);
             $PowerBB->functions->GetFooter();
         }
       $PowerBB->_POST['message'] = str_replace('target="_blank" ','',$PowerBB->_POST['message']);

			// mention users tag replace
			if($PowerBB->functions->mention_permissions())
			{
			 $PowerBB->_POST['message'] = preg_replace_callback('#\[mention\](.+)\[\/mention\]#iUs', array($this, 'mention_reply_callback'), $PowerBB->_POST['message']);
			}

        $error_stop = true;
		$TextPost = utf8_decode($PowerBB->_POST['message']);
 		$TextPost = preg_replace('/\s+/', '', $TextPost);
		$TextPost = preg_replace('#\[IMG\](.*)\[/IMG\]#siU', '', $TextPost);
		$TextPost = preg_replace('#[(.*)]#siU', '', $TextPost);
		$TextPost = preg_replace('#[/(.*)]#siU', '', $TextPost);
		$TextPost = $PowerBB->Powerparse->remove_message_quotes($TextPost);

		if (empty($TextPost))
		{
		$PowerBB->_CONF['template']['_CONF']['lang']['post_text_min'] = str_replace("5", $PowerBB->_CONF['info_row']['post_text_min'], $PowerBB->_CONF['template']['_CONF']['lang']['post_text_min']);
		$error_msg =($PowerBB->_CONF['template']['_CONF']['lang']['post_text_min']);
		$error_stop = false;
		}

		if(strlen($TextPost) >= $PowerBB->_CONF['info_row']['post_text_min'])
		{
		// Continue
		}
		else
		{
		$PowerBB->_CONF['template']['_CONF']['lang']['post_text_min'] = str_replace("5", $PowerBB->_CONF['info_row']['post_text_min'], $PowerBB->_CONF['template']['_CONF']['lang']['post_text_min']);
		$error_msg =($PowerBB->_CONF['template']['_CONF']['lang']['post_text_min']);
		$error_stop = false;
		}

		if (isset($TextPost{$PowerBB->_CONF['info_row']['post_text_max']}))
		{
		 $PowerBB->_CONF['template']['_CONF']['lang']['post_text_max'] = str_replace("30000", $PowerBB->_CONF['info_row']['post_text_max'], $PowerBB->_CONF['template']['_CONF']['lang']['post_text_max']);
		 $error_msg =($PowerBB->_CONF['template']['_CONF']['lang']['post_text_max']);
		 $error_stop = false;
		}

       if($error_stop)
       {
		$SubjectArr 			= 	array();
		$SubjectArr['field'] 	= 	array();
        $SubjectArr['field']['text'] 	        = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['message'],'nohtml');
		$SubjectArr['field']['actiondate'] 	= 	$PowerBB->_CONF['now'];
     	$SubjectArr['field']['action_by'] 	= 	$PowerBB->_CONF['member_row']['username'];
		$SubjectArr['where']			    = 	array('id',$PowerBB->_POST['subject_id']);

		$update = $PowerBB->core->Update($SubjectArr,'subject');
       }
		// show message
		//$PowerBB->_POST['message'] = $PowerBB->Powerparse->replace_htmlentities($PowerBB->_POST['message']);

		$PowerBB->_POST['message'] = $PowerBB->Powerparse->replace($PowerBB->_POST['message']);
		$PowerBB->Powerparse->replace_smiles($PowerBB->_POST['message']);
		$PowerBB->_POST['message'] = $PowerBB->Powerparse->censor_words($PowerBB->_POST['message']);

		echo '<div class="text">'.$PowerBB->_POST['message'].'</div>';

		if($error_stop == false)
		{
		echo '<br /><div class="msg_row1" style="width:auto;padding:10px;border:1px solid #F9AA00;">'.$error_msg.'</div><br />';
		}
        exit;
	 }


	function mention_reply_callback($matches)
	{
		global $PowerBB;

        $username = trim($matches[1]);
        if (!empty($username))
         {

           $safe_username = $PowerBB->DB->sql_escape($username);

			if($username == $PowerBB->_CONF['member_row']['username'])
			{
             return "@".$username."";
			}
	        $reply_id = intval($PowerBB->_POST['reply_id']);
			// insert mention
			$Getmention_youNumrs = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(*) FROM " . $PowerBB->prefix . "mention WHERE you = '$safe_username' AND reply_id = $reply_id AND user_read = 1"));
			if(!$Getmention_youNumrs)
			{
			$InsertArr 					= 	array();
			$InsertArr['field']			=	array();

			$InsertArr['field']['user_mention_about_you'] 			= 	$PowerBB->_CONF['member_row']['username'];
			$InsertArr['field']['you'] 			= 	$safe_username;
			$InsertArr['field']['topic_id'] 				= 	intval($PowerBB->_POST['subject_id']);
			$InsertArr['field']['reply_id'] 			= 	intval($PowerBB->_POST['reply_id']);
			$InsertArr['field']['profile_id'] 			= 	$PowerBB->_CONF['member_row']['id'];
			$InsertArr['field']['date'] 		= 	$PowerBB->_CONF['now'];
			$InsertArr['field']['user_read'] 		    = 	'1';

			$insert = $PowerBB->core->Insert($InsertArr,'mention');
			}
			$MemArr = $PowerBB->DB->sql_query("SELECT id FROM " . $PowerBB->table['member'] . " WHERE username = '$safe_username' LIMIT 1 ");
			$Member_row = $PowerBB->DB->sql_fetch_array($MemArr);
			if ($Member_row) {
			$url = $forum_url."index.php?page=profile&amp;show=1&amp;id=".$Member_row['id'];
			return "[url=".$PowerBB->functions->rewriterule($url)."]@".$username."[/url]";
			}

			return "@" . $username;
		}

	}


	function mention_subject_callback($matches)
	{
		global $PowerBB;

        $username = trim($matches[1]);
        if (!empty($username))
         {
         	$safe_username = $PowerBB->DB->sql_escape($username);
			if($username == $PowerBB->_CONF['member_row']['username'])
			{
             return "@".$username."";
			}
	        $reply_id = 0;
	        $topic_id = intval($PowerBB->_POST['subject_id']);
			// insert mention
			$Getmention_youNumrs = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(*) FROM " . $PowerBB->prefix . "mention WHERE you = '$safe_username' AND topic_id = $topic_id AND user_read = 1"));
			if(!$Getmention_youNumrs)
			{
				$InsertArr 					= 	array();
				$InsertArr['field']			=	array();
				$InsertArr['field']['user_mention_about_you'] 			= 	$PowerBB->_CONF['member_row']['username'];
				$InsertArr['field']['you'] 			= 	$safe_username;
				$InsertArr['field']['topic_id'] 				= 	intval($PowerBB->_POST['subject_id']);
				$InsertArr['field']['reply_id'] 			= 	0;
				$InsertArr['field']['profile_id'] 			= 	$PowerBB->_CONF['member_row']['id'];
				$InsertArr['field']['date'] 		= 	$PowerBB->_CONF['now'];
				$InsertArr['field']['user_read'] 		    = 	'1';
				$insert = $PowerBB->core->Insert($InsertArr,'mention');
			}

			$MemArr = $PowerBB->DB->sql_query("SELECT id FROM " . $PowerBB->table['member'] . " WHERE username = '$safe_username' LIMIT 1 ");
			$Member_row = $PowerBB->DB->sql_fetch_array($MemArr);
			if ($Member_row) {
			$url = $forum_url."index.php?page=profile&amp;show=1&amp;id=".$Member_row['id'];
			return "[url=".$PowerBB->functions->rewriterule($url)."]@".$username."[/url]";
			}

			return "@" . $username;
	     }
	}

}

?>