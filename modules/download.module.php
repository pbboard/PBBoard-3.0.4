<?php

$CALL_SYSTEM 			= 	array();
$CALL_SYSTEM['SUBJECT'] = 	true;
$CALL_SYSTEM['SECTION'] = 	true;
$CALL_SYSTEM['ATTACH'] 	= 	true;
$CALL_SYSTEM['PM'] 		= 	true;
$CALL_SYSTEM['REPLY'] 	= 	true;

(!defined('IN_PowerBB')) ? die() : '';



define('CLASS_NAME','PowerBBDownloadMOD');

include('common.php');
class PowerBBDownloadMOD
{
	function run()
	{
		global $PowerBB;

		if ($PowerBB->_GET['subject'])
		{
			$this->_DownloadSubject();
		}
		elseif ($PowerBB->_GET['attach'])
		{
			$this->_DownloadAttach();
		}
		elseif ($PowerBB->_GET['pm'])
		{
			$this->_DownloadPM();
		}
		else
		{
			header("Location: index.php");
			exit;
		}
	}

	function _DownloadSubject()
	{
		global $PowerBB;

		//////////

		// Clean id from any string, that will protect us
		$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');

		// If the id is empty, so stop the page
		if (empty($PowerBB->_GET['id']))
		{
			$PowerBB->functions->ShowHeader();
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}

		//////////
		if (!$PowerBB->_CONF['info_row']['download_subject'])
		{
			$PowerBB->functions->ShowHeader();
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Sorry_You_do_not_have_powers_to_access_this_page']);
		}

		$SubjectArr = array();

		$SubjectArr['where'] 				= 	array();

		$SubjectArr['where'][0] 			= 	array();
		$SubjectArr['where'][0]['name'] 	= 	'id';
		$SubjectArr['where'][0]['oper'] 	= 	'=';
		$SubjectArr['where'][0]['value'] 	= 	$PowerBB->_GET['id'];

		$SubjectInfo = $PowerBB->core->GetInfo($SubjectArr,'subject');

		$PowerBB->functions->CleanVariable($SubjectInfo,'html');

		if ($SubjectInfo['delete_topic']
			and !$PowerBB->_CONF['group_info']['admincp_allow'])
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Subject_Was_Trasht']);
		}

		$SecArr 			= 	array();
		$SecArr['where'] 	= 	array('id',$SubjectInfo['section']);

		$SectionInfo = $PowerBB->core->GetInfo($SecArr,'section');


         if ($PowerBB->functions->section_group_permission($SectionInfo['id'],$PowerBB->_CONF['group_info']['id'],'view_section') == 0
         or $PowerBB->functions->section_group_permission($SectionInfo['id'],$PowerBB->_CONF['group_info']['id'],'view_subject') == 0)
		{
     		$PowerBB->functions->ShowHeader();
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['can_not_view_this_subject']);
			$PowerBB->functions->GetFooter();
		}

       		// if section Allw hide subject can't show this subject  , so stop the page
   		if ($SectionInfo['hide_subject']
   		and !$PowerBB->functions->ModeratorCheck($SectionInfo['moderators']))
   		{

	   		if ($PowerBB->_CONF['member_row']['username'] != $SubjectInfo['writer'])
	   		{
	   		$PowerBB->functions->ShowHeader();
	        $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Sorry_you_can_not_see_on_this_topic']);
			$PowerBB->functions->GetFooter();
	        }
        }

        if ($SubjectInfo['review_subject']
   		and !$PowerBB->functions->ModeratorCheck($SectionInfo['moderators']))
   		{

	   		if ($PowerBB->_CONF['member_row']['username'] != $SubjectInfo['writer']
	   		and 'Guest' != $SubjectInfo['writer'])
	   		{
	   		$PowerBB->functions->ShowHeader();
	        $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Sorry_you_can_not_see_on_this_topic']);
			$PowerBB->functions->GetFooter();
	        }
        }

		// hmmmmmmm , this subject deleted , so the members and visitor can't show it
		if ($SubjectInfo['delete_topic']
			and !$PowerBB->_CONF['group_info']['admincp_allow'])
		{
		    $PowerBB->functions->ShowHeader();
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Subject_Was_Trasht']);
			$PowerBB->functions->GetFooter();
		}

		$filename = str_replace(' ','_',$SubjectInfo['title']);
		$filename .= '.html';

		header('Content-Disposition: attachment;filename=' . $filename);
		header('Content-type: text/html');
       $charset1                =   $PowerBB->_CONF['info_row']['content_dir'];
       $title                =   $SubjectInfo['title'];
       $forum_title = $PowerBB->_CONF['info_row']['title'];
        echo "<html dir='$charset1'>
        <head>
        <style type='text/css'>
body,
html {
	margin: 0 auto;
	padding: 0;
	width: auto;
	line-height: 30px;
	-webkit-font-smoothing: subpixel-antialiased;
	text-rendering: optimizeLegibility;
}
        </style>
        <meta http-equiv='Content-Type' content='text/html; charset=utf-8'><title>". $forum_title ." : ".$title."</title></head><body>";
        echo '<a target="_blank" href="'.$PowerBB->functions->GetForumAdress().'">'.$PowerBB->_CONF['info_row']['title'].'</a>';
        echo '<br />  '.$crlf . $crlf . $crlf;
		echo $PowerBB->_CONF['template']['_CONF']['lang']['Topic_entitled'] . $SubjectInfo['title'];
		echo '<br />  '.$crlf . $crlf . $crlf;
		echo $PowerBB->_CONF['template']['_CONF']['lang']['LastsPostsWriter'] . $SubjectInfo['writer'];
		echo ' <br /> '.$crlf . $crlf . $crlf;
		$SubjectInfo['text'] = $PowerBB->Powerparse->replace($SubjectInfo['text']);
		$SubjectInfo['text'] = str_ireplace('&quot;','"',$SubjectInfo['text']);
		$SubjectInfo['text'] = str_ireplace('&amp;','&',$SubjectInfo['text']);
	    $SubjectInfo['text'] = $PowerBB->Powerparse->censor_words($SubjectInfo['text']);

		echo '<br /><hr>' . $SubjectInfo['text'];
        echo "</body></html>";

	}

	function _DownloadAttach()
	{
		global $PowerBB;

		//////////

		// Clean id from any string, that will protect us
		$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');

		// If the id is empty, so stop the page
		if (empty($PowerBB->_GET['id']))
		{
		       $PowerBB->functions->ShowHeader();
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}

		if (!$PowerBB->attach->IsAttach(array('where' => array('id',$PowerBB->_GET['id']))))
		{
		       $PowerBB->functions->ShowHeader();
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Attach_you_want_does_not_exist']);
		}

		//////////

		// Get attachment information
		$AttachArr = array();

		$AttachArr['where'] 				= 	array();

		$AttachArr['where'][0] 				= 	array();
		$AttachArr['where'][0]['name'] 		= 	'id';
		$AttachArr['where'][0]['oper'] 		= 	'=';
		$AttachArr['where'][0]['value'] 	= 	$PowerBB->_GET['id'];

		$AttachInfo = $PowerBB->attach->GetAttachInfo($AttachArr);

		// Clean the information from XSS
		$PowerBB->functions->CleanVariable($AttachInfo,'html');

		//////////

		// Get subject information
		$SubjectArr = array();

		$SubjectArr['where'] 				= 	array();

		$SubjectArr['where'][0] 			= 	array();
		$SubjectArr['where'][0]['name'] 	= 	'id';
		$SubjectArr['where'][0]['oper'] 	= 	'=';
		$SubjectArr['where'][0]['value'] 	= 	$AttachInfo['subject_id'];

		$SubjectInfo = $PowerBB->core->GetInfo($SubjectArr,'subject');

		// Clean the information from XSS
		$PowerBB->functions->CleanVariable($SubjectInfo,'html');

		//////////

		// The subject isn't available
		if ($SubjectInfo['delete_topic']
			and !$PowerBB->_CONF['group_info']['admincp_allow'])
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Subject_Was_Trasht']);
		}

		//////////

		// We can't stop the admin :)
		if ($PowerBB->_CONF['group_info']['admincp_allow'] ==0)
		{

			// The user can't download this attachment
			if ($PowerBB->_CONF['group_info']['download_attach'] == 0)
			{
			    $PowerBB->functions->ShowHeader();

	           if ($PowerBB->_CONF['member_permission'] == 0)
	              {
	              $PowerBB->template->display('login');
	              $PowerBB->functions->error_stop();
				}
		        else
		        {
		        $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Sorry_you_can_not_download_this_Attachment']);
		        }
	        }

			// These checks are special for members
			if ($PowerBB->_CONF['member_permission'])
			{
				// No enough posts
				if ($PowerBB->_CONF['group_info']['download_attach_number'] > $PowerBB->_CONF['member_row']['posts'])
				{
					$PowerBB->functions->ShowHeader();
		            $PowerBB->_CONF['template']['_CONF']['lang']['Member_can_not_download_the_Attachment_posts_less'] = str_ireplace("(1)","(".$PowerBB->_CONF['group_info']['download_attach_number'].")",$PowerBB->_CONF['template']['_CONF']['lang']['Member_can_not_download_the_Attachment_posts_less']);
		            $PowerBB->_CONF['template']['_CONF']['lang']['Member_can_not_download_the_Attachment_posts_less'] = str_ireplace("()","(".$PowerBB->_CONF['group_info']['download_attach_number'].")",$PowerBB->_CONF['template']['_CONF']['lang']['Member_can_not_download_the_Attachment_posts_less']);
		            $PowerBB->_CONF['template']['_CONF']['lang']['Member_can_not_download_the_Attachment_posts_less'] = str_ireplace("{num}","(".$PowerBB->_CONF['group_info']['download_attach_number'].")",$PowerBB->_CONF['template']['_CONF']['lang']['Member_can_not_download_the_Attachment_posts_less']);
					$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Member_can_not_download_the_Attachment_posts_less']);
				}
			}
		}

		//////////

     if ($PowerBB->_CONF['group_info']['download_attach'])
	  {
		// Count a new download
		$UpdateArr 						= 	array();
		$UpdateArr['field'] 			= 	array();
		$UpdateArr['field']['visitor'] 	= 	$AttachInfo['visitor'] + 1;
		$UpdateArr['field']['last_down']= 	$PowerBB->_CONF['now'];
		$UpdateArr['where'] 			= 	array('id',$AttachInfo['id']);

		$update = $PowerBB->attach->UpdateAttach($UpdateArr);
      }
		//////////

	// required for IE, otherwise Content-Disposition may be ignored
	if(ini_get('zlib.output_compression'))
	{
		ini_set('zlib.output_compression', 'Off');
	}

		//////////

          if (file_exists('./' . $AttachInfo['filepath']))
          {
        	$file_mime = $AttachInfo['extension'];
         	$filesize = $AttachInfo['filesize'];
	        header('Date: ' . gmdate('D, d M Y H:i:s') . ' GMT');
			header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
			header('Content-Encoding: none');
			header('Cache-Control: no-cache, must-revalidate');           // HTTP/1.1
			header("Pragma: public");
            header('Expires: 0');
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            if ($AttachInfo['extension'] == 'jpg'
			or $AttachInfo['extension'] == 'gif'
			or $AttachInfo['extension'] == 'png'
			or $AttachInfo['extension'] == 'jepg')
			{
			header ('content-type: image/'.$AttachInfo['extension'].'');
			}
			else
			{
			header ('content-type: $file_mime');
			}
		    header("Content-Disposition: attachment; filename=" . $AttachInfo['filename'] . "");
			header("Content-Transfer-Encoding: binary");
		    header("Content-Length: $filesize");
	        header('Content-Length: ' . $filesize);
	        set_time_limit(0);
		    ob_clean();
		    flush();
		    $read = readfile('./' . $AttachInfo['filepath']);
		    exit;
		  }
		  elseif (!file_exists('./' . $AttachInfo['filepath']))
		  {
            extract($AttachInfo);
         	$file_contents = $AttachInfo['contents'];
         	$file_mime = $AttachInfo['extension'];
			$file_name = rawurlencode($AttachInfo['filename']);
			$filesize = $AttachInfo['filesize'];
            if ($AttachInfo['extension'] == 'jpg'
			or $AttachInfo['extension'] == 'gif'
			or $AttachInfo['extension'] == 'png'
			or $AttachInfo['extension'] == 'jepg')
			{
			header ('content-type: image/'.$AttachInfo['extension'].'');
			}
			else
			{
			header ('content-type: $file_mime');
			}
			header("Content-disposition: filename=$file_name");
			header("Content-Transfer-Encoding: binary");
		    ob_clean();
		    flush();
		    print( $file_contents);
			exit;
		  }
		  else
		  {
           $PowerBB->functions->ShowHeader();
	       $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Attach_you_want_does_not_exist']);
		  }





		//////////

	}

	function _DownloadPM()
	{
		global $PowerBB;

		if (empty($PowerBB->_GET['id'])
		 or $PowerBB->_GET['id'] == 0)
		{
			 $PowerBB->functions->ShowHeader();
			 $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
			 $PowerBB->functions->error_stop();

		}

		if (!$PowerBB->_CONF['member_permission'])
		{
			  $PowerBB->functions->ShowHeader();
              $PowerBB->template->display('login');
              $PowerBB->functions->error_stop();
		}

		$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');
		$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'sql');

		//$MsgArr 			= 	array();
		//$MsgArr['id'] 		= 	$PowerBB->_GET['id'];
		//$MsgArr['username'] = 	$PowerBB->_CONF['member_row']['username'];

		//$MsgInfo = $PowerBB->pm->GetPrivateMassegeInfo($MsgArr);

		$MsgArr 			= 	array();
		$MsgArr['where'] 	= 	array('id',intval($PowerBB->_GET['id']));

		$MsgInfo = $PowerBB->core->GetInfo($MsgArr,'pm');

		if (!$MsgInfo)
		{
			 $PowerBB->functions->ShowHeader();
			 $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Massege_requested_does_not_exist']);
			 $PowerBB->functions->error_stop();
		}
        $usersent= $MsgInfo['user_to'].",".$MsgInfo['user_from'];
        if (!in_array($PowerBB->_CONF['member_row']['username'], explode(",",$usersent)))
        {
			 $PowerBB->functions->ShowHeader();
			 $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
			 $PowerBB->functions->error_stop();
        }

		$MsgInfo['title'] = $PowerBB->functions->CleanVariable($MsgInfo['title'],'html');
		$filename = str_replace(' ','_',$MsgInfo['title']);
		$filename .= '.html';

		header('Content-Disposition: attachment;filename=' . $filename);
		header('Content-type: text/html');
       $charset1                =   $PowerBB->_CONF['info_row']['content_dir'];
       $title                =   $MsgInfo['title'];
       $forum_title = $PowerBB->_CONF['info_row']['title'];
        echo "<html dir='$charset1'> <head> <meta http-equiv='Content-Type' content='text/html; charset=utf-8'><title>". $forum_title ." : ".$title."</title></head><body>";
        echo '<a target="_blank" href="'.$PowerBB->functions->GetForumAdress().'">'.$PowerBB->_CONF['info_row']['title'].'</a>';
        echo '<br />  '.$crlf . $crlf . $crlf;
		echo $PowerBB->_CONF['template']['_CONF']['lang']['Pm_entitled'] . ' ' . $MsgInfo['title'];
		echo '<br />  '.$crlf . $crlf . $crlf;
		echo $PowerBB->_CONF['template']['_CONF']['lang']['Sender'] . ' : ' . $MsgInfo['user_from'];
		echo ' <br /> '.$crlf . $crlf . $crlf;
		$MsgInfo['text'] = $PowerBB->Powerparse->replace($MsgInfo['text']);
		$MsgInfo['text'] = str_ireplace('&quot;','"',$MsgInfo['text']);
		$MsgInfo['text'] = str_ireplace('&amp;','&',$MsgInfo['text']);
		$PowerBB->Powerparse->replace_smiles_print($MsgInfo['text']);
		echo '<br /><hr>' . $MsgInfo['text'];
        echo "</body></html>";
	}
}

?>
