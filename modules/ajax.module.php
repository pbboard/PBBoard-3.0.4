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
			elseif ($PowerBB->_GET['coverPhotoRemove'])
			{
			 $this->_coverPhotoRemove();
			}
			elseif ($PowerBB->_GET['editreplyajax'])
			{
			 $this->_editreplyajax();
			}
			elseif ($PowerBB->_GET['editsubjectajax'])
			{
			 $this->_editsubjectajax();
			}
			else
			{
				header("Location: index.php");
				exit;
			}
		}
		else
		{
			header("Location: index.php");
			exit;
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
		$UPDATE_user  = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['member'] . " SET profile_cover_photo = '' WHERE id = '$user_id'");

		header("Location: ".$PowerBB->_SERVER['HTTP_REFERER']);
		exit;
	}

	function _editreplyajax()
	{
		global $PowerBB;
         if ($PowerBB->_SERVER['REQUEST_METHOD'] != 'POST')
         {
         	 header("Location: index.php");
		     exit;
         }
        $PowerBB->_POST['message'] = str_replace('target="_blank" ','',$PowerBB->_POST['message']);

		// mention users tag replace
		if($PowerBB->functions->mention_permissions())
		{
			if(preg_match('/\[mention\](.*?)\[\/mention\]/s', $PowerBB->_POST['message'], $tags_w))
			{
			$username = trim($tags_w[1]);
			$reply_id = $PowerBB->_POST['reply_id'];
			$MemArr = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['member'] . " WHERE username = '$username' ");
			$Member_row = $PowerBB->DB->sql_fetch_array($MemArr);
			if($Member_row)
			{
			if ($Member_row['username'] == $PowerBB->_CONF['member_row']['username'])
			{
	        $PowerBB->_POST['message'] = str_replace("[mention]", "@", $PowerBB->_POST['message']);
			$PowerBB->_POST['message'] = str_replace("[/mention]", "", $PowerBB->_POST['message']);
			$Member_row['username'] = '';
			}
			if (!empty($Member_row['username']))
			{
			$forum_url              =   $PowerBB->functions->GetForumAdress();
			$url = $forum_url."index.php?page=profile&amp;show=1&amp;id=".$Member_row['id'];
			$PowerBB->_POST['message'] = str_replace("[mention]", "[url=".$PowerBB->functions->rewriterule($url)."]@", $PowerBB->_POST['message']);
			$PowerBB->_POST['message'] = str_replace("[/mention]", "[/url]", $PowerBB->_POST['message']);
			// insert mention
			$Getmention_youNumrs = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT *  FROM " . $PowerBB->prefix . "mention WHERE you = '$username' AND reply_id = '$reply_id' AND user_read = '1'"));
			if($Getmention_youNumrs)
			{
			$insert_mention = 	false;
			}
			else
			{
			$insert_mention = 	true;
			}
			}
			}
			}
        }

		$ReplyArr 			= 	array();
		$ReplyArr['field'] 	= 	array();

		$ReplyArr['field']['text'] 	        = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['message'],'nohtml');
		$ReplyArr['field']['actiondate'] 	= 	$PowerBB->_CONF['now'];
     	$ReplyArr['field']['action_by'] 	= 	$PowerBB->_CONF['member_row']['username'];
		$ReplyArr['where']			        = 	array('id',$PowerBB->_POST['reply_id']);

		$update = $PowerBB->core->Update($ReplyArr,'reply');

		// show message
		//$PowerBB->_POST['message'] = $PowerBB->Powerparse->replace_htmlentities($PowerBB->_POST['message']);
		$PowerBB->_POST['message'] = $PowerBB->Powerparse->replace($PowerBB->_POST['message']);
		$PowerBB->Powerparse->replace_smiles($PowerBB->_POST['message']);
		$PowerBB->_POST['message'] = $PowerBB->Powerparse->censor_words($PowerBB->_POST['message']);

		// insert mention
		if($PowerBB->functions->mention_permissions())
		{
			if ($insert_mention)
			{
			$InsertArr 					= 	array();
			$InsertArr['field']			=	array();

			$InsertArr['field']['user_mention_about_you'] 			= 	$PowerBB->_CONF['member_row']['username'];
			$InsertArr['field']['you'] 			= 	$Member_row['username'];
			$InsertArr['field']['topic_id'] 				= 	intval($PowerBB->_POST['subject_id']);
			$InsertArr['field']['reply_id'] 			= 	intval($PowerBB->_POST['reply_id']);
			$InsertArr['field']['profile_id'] 			= 	$PowerBB->_CONF['member_row']['id'];
			$InsertArr['field']['date'] 		= 	$PowerBB->_CONF['now'];
			$InsertArr['field']['user_read'] 		    = 	'1';

			$insert = $PowerBB->core->Insert($InsertArr,'mention');
			}
        }

		echo '<div class="text">'.$PowerBB->_POST['message'].'</div>';
        exit;
	 }
	function _editsubjectajax()
	{
		global $PowerBB;
         if ($PowerBB->_SERVER['REQUEST_METHOD'] != 'POST')
         {
         	 header("Location: index.php");
		     exit;
         }
       $PowerBB->_POST['message'] = str_replace('target="_blank" ','',$PowerBB->_POST['message']);

		// mention users tag replace
		if($PowerBB->functions->mention_permissions())
		{
			if(preg_match('/\[mention\](.*?)\[\/mention\]/s', $PowerBB->_POST['message'], $tags_w))
			{
			$username = trim($tags_w[1]);
			$topic_id = $PowerBB->_POST['subject_id'];
			$MemArr = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['member'] . " WHERE username = '$username' ");
			$Member_row = $PowerBB->DB->sql_fetch_array($MemArr);
			if($Member_row)
			{
			if ($Member_row['username'] == $PowerBB->_CONF['member_row']['username'])
			{
	        $PowerBB->_POST['message'] = str_replace("[mention]", "@", $PowerBB->_POST['message']);
			$PowerBB->_POST['message'] = str_replace("[/mention]", "", $PowerBB->_POST['message']);
			$Member_row['username'] = '';
			}
			if (!empty($Member_row['username']))
			{
			$forum_url              =   $PowerBB->functions->GetForumAdress();
			$url = $forum_url."index.php?page=profile&amp;show=1&amp;id=".$Member_row['id'];
			$PowerBB->_POST['message'] = str_replace("[mention]", "[url=".$PowerBB->functions->rewriterule($url)."]@", $PowerBB->_POST['message']);
			$PowerBB->_POST['message'] = str_replace("[/mention]", "[/url]", $PowerBB->_POST['message']);
			// insert mention
			$Getmention_youNumrs = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT *  FROM " . $PowerBB->prefix . "mention WHERE you = '$username' AND topic_id = '$topic_id' AND user_read = '1'"));
			if($Getmention_youNumrs)
			{
			$insert_mention = 	false;
			}
			else
			{
			$insert_mention = 	true;
			}
			}
			}
			}
        }

		$SubjectArr 			= 	array();
		$SubjectArr['field'] 	= 	array();
        $SubjectArr['field']['text'] 	        = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['message'],'nohtml');
		$SubjectArr['field']['actiondate'] 	= 	$PowerBB->_CONF['now'];
     	$SubjectArr['field']['action_by'] 	= 	$PowerBB->_CONF['member_row']['username'];
		$SubjectArr['where']			    = 	array('id',$PowerBB->_POST['subject_id']);

		$update = $PowerBB->core->Update($SubjectArr,'subject');

		// show message
		//$PowerBB->_POST['message'] = $PowerBB->Powerparse->replace_htmlentities($PowerBB->_POST['message']);

		$PowerBB->_POST['message'] = $PowerBB->Powerparse->replace($PowerBB->_POST['message']);
		$PowerBB->Powerparse->replace_smiles($PowerBB->_POST['message']);
		$PowerBB->_POST['message'] = $PowerBB->Powerparse->censor_words($PowerBB->_POST['message']);

		// insert mention
		if($PowerBB->functions->mention_permissions())
		{
			if ($insert_mention)
			{
			$InsertArr 					= 	array();
			$InsertArr['field']			=	array();
			$InsertArr['field']['user_mention_about_you'] 			= 	$PowerBB->_CONF['member_row']['username'];
			$InsertArr['field']['you'] 			= 	$Member_row['username'];
			$InsertArr['field']['topic_id'] 				= 	intval($PowerBB->_POST['subject_id']);
			$InsertArr['field']['reply_id'] 			= 	0;
			$InsertArr['field']['profile_id'] 			= 	$PowerBB->_CONF['member_row']['id'];
			$InsertArr['field']['date'] 		= 	$PowerBB->_CONF['now'];
			$InsertArr['field']['user_read'] 		    = 	'1';
			$insert = $PowerBB->core->Insert($InsertArr,'mention');
			}
       }
		echo '<div class="text">'.$PowerBB->_POST['message'].'</div>';
        exit;
	 }
}

?>
