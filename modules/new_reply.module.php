<?php
session_start();
(!defined('IN_PowerBB')) ? die() : '';
define('CLASS_NAME','PowerBBReplyAddMOD');
require_once('common.php');
class PowerBBReplyAddMOD
{
	var $SectionInfo;
	var $SectionGroup;
	var $SubjectInfo;

	function run()
	{
		global $PowerBB;
		$this->_CommonCode();
  		 $PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');
		$PowerBB->_GET['count'] = (!isset($PowerBB->_GET['count'])) ? 1 : $PowerBB->_GET['count'];
		if(!$PowerBB->_GET['count'])
		{
		$PowerBB->_GET['count'] = '1';
		}
		$PowerBB->_GET['count'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['count'],'intval');
		if ($PowerBB->_GET['index'])
		{
			$this->_Index();
			$PowerBB->functions->GetFooter();
		}
		elseif($PowerBB->_GET['start'])
		{
			$this->_Start();
		}
		else
		{
			header("Location: index.php");
			exit;
		}
	}

	function _CommonCode()
	{
		global $PowerBB;


		$PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');
        $PowerBB->functions->CleanVariable($_GET['id'],'intval');

		if (empty($PowerBB->_GET['id']))
		{
			$PowerBB->functions->ShowHeader($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}

		$SubjectArr 			= 	array();
		$SubjectArr['where'] 	= 	array('id',$PowerBB->_GET['id']);

		$this->SubjectInfo = $PowerBB->core->GetInfo($SubjectArr,'subject');

		if ($PowerBB->_POST['preview'])
		{
         $this->SubjectInfo['title']  = stripslashes($this->SubjectInfo['title']);
		}
		// Kill XSS
		$PowerBB->functions->CleanVariable($this->SubjectInfo,'html');
		// Kill SQL Injection
		$PowerBB->functions->CleanVariable($this->SubjectInfo,'sql');

		$SecArr 			= 	array();
		$SecArr['where'] 	= 	array('id',$this->SubjectInfo['section']);

		$this->SectionInfo = $PowerBB->core->GetInfo($SecArr,'section');

		// Kill XSS
		$PowerBB->functions->CleanVariable($this->SectionInfo,'html');
		// Kill SQL Injection
		$PowerBB->functions->CleanVariable($this->SectionInfo,'sql');

		if (!$this->SubjectInfo)
		{
			$PowerBB->functions->ShowHeader($PowerBB->_CONF['template']['_CONF']['lang']['Requested_topic_does_not_exist']);
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Requested_topic_does_not_exist']);
		}

		$Admin = $PowerBB->functions->ModeratorCheck($this->SectionInfo['id']);

		$PowerBB->template->assign('Admin',$Admin);

		$Mod = false;

		if ($PowerBB->_CONF['member_permission'])
		{
			if ($PowerBB->_CONF['group_info']['admincp_allow']
				or $PowerBB->_CONF['group_info']['vice'])
			{
				$Mod = true;
			}
			else
			{
				if (isset($this->SectionInfo))
				{
					$ModArr 				= 	array();
					$ModArr['username'] 	= 	$PowerBB->_CONF['member_row']['username'];
					$ModArr['section_id']	=	$this->SectionInfo['id'];

					$Mod = $PowerBB->moderator->IsModerator($ModArr);
				}
			}
		}

		if (!$Mod)
		{
			if ($this->SubjectInfo['close'])
			{
			    $PowerBB->functions->ShowHeader($PowerBB->_CONF['template']['_CONF']['lang']['This_topic_is_locked']);
				$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['This_topic_is_locked']);
			}
		}

            /** Get section's group information and make some checks **/
			if($PowerBB->_CONF['member_row']['membergroupids'] !='')
	 		{
			  $membergroupid__s = $PowerBB->_CONF['member_row']['membergroupids'].",".$PowerBB->_CONF['group_info']['id'];
			  $PowerBB->_CONF['member_row']['membergroupids'] = str_replace("," , "','",$PowerBB->_CONF['member_row']['membergroupids']);
		      $SecGroupArr = $PowerBB->DB->sql_query("SELECT * FROM pbb_sectiongroup WHERE group_id in('".$PowerBB->_CONF['member_row']['membergroupids']."','".$PowerBB->_CONF['group_info']['id']."') ");
		    	while ($PermissionSectionGroup = $PowerBB->DB->sql_fetch_array($SecGroupArr))
				{
				  if (in_array($PermissionSectionGroup['group_id'], explode(',', $membergroupid__s))
				 and $PermissionSectionGroup['section_id'] == $this->SectionInfo['id']){

					 $this->SectionGroup['view_section'] .= $PermissionSectionGroup['view_section'];
					 $this->SectionGroup['write_subject'] .= $PermissionSectionGroup['write_subject'];
					 $this->SectionGroup['upload_attach'] .= $PermissionSectionGroup['upload_attach'];
					 $this->SectionGroup['no_posts'] .= $PermissionSectionGroup['no_posts'];
					 $this->SectionGroup['write_poll'] .= $PermissionSectionGroup['write_poll'];
		             //$PowerBB->template->assign('SectionGroup',$PermissionSectionGroup);
		            $PowerBB->_CONF['template']['SectionGroup'] = $PermissionSectionGroup;
		          }
				}

			}
	       else
			{

				$SecGroupArr 						= 	array();
				$SecGroupArr['where'] 				= 	array();

				$SecGroupArr['where'][0]			=	array();
				$SecGroupArr['where'][0]['name']	=	'section_id = '.$this->SectionInfo['id'].' AND group_id';
				$SecGroupArr['where'][0]['oper']	=	'=';
				$SecGroupArr['where'][0]['value']	=	$PowerBB->_CONF['group_info']['id'];
				$this->SectionGroup = $PowerBB->group->GetSectionGroupInfo($SecGroupArr);

			}

	           // The visitor can't show this section , so stop the page
	           if ($PowerBB->functions->section_group_permission($this->SectionInfo['id'],$PowerBB->_CONF['group_info']['id'],'view_section') == 0
	           or $PowerBB->functions->section_group_permission($this->SectionInfo['id'],$PowerBB->_CONF['group_info']['id'],'write_reply') == 0)
				{
					$PowerBB->functions->ShowHeader($PowerBB->_CONF['template']['_CONF']['lang']['Mistake']);
			           if($PowerBB->_CONF['member_permission'])
			            {
				         $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Sorry_you_can_not_write_in_this_section']);
						}
				        else
				        {
			              $PowerBB->template->display('login');
			              $PowerBB->functions->error_stop();
			             }
				}

     	$PowerBB->template->assign('upload_attach',$this->SectionGroup['upload_attach']);

		if ($PowerBB->_CONF['group_info']['view_subject'] == 0)
		{
          if (!$PowerBB->_CONF['member_permission'])
              {
              $PowerBB->template->display('login');
              $PowerBB->functions->error_stop();
			}
	        else
	        {

			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Sorry_you_can_not_write_in_this_section']);
	        }
		}

			if(!empty($this->SectionInfo['section_password']))
			{
			  $PowerBB->_CONF['template']['password'] = '&amp;password=' . base64_encode($this->SectionInfo['section_password']);
			}
		//////////


		// Where is the member now?
		if ($PowerBB->_CONF['member_permission'])
     	{
     		$UpdateOnline 			= 	array();
			$UpdateOnline['field']	=	array();
			$UpdateOnline['field']['user_location'] 	= 	$PowerBB->_CONF['template']['_CONF']['lang']['Written_in_reply_to'].' <a href="index.php?page=topic&amp;show=1&amp;id=' . $PowerBB->_GET['id'] . '">' . $this->SubjectInfo['title'] . '</a>';

			$UpdateOnline['where']						=	array('username',$PowerBB->_CONF['member_row']['username']);

			$update = $PowerBB->core->Update($UpdateOnline,'online');
     	}

     	//////////
     	$PowerBB->template->assign('section_info',$this->SectionInfo);
     	 $PowerBB->template->assign('subject_info',$this->SubjectInfo);
         $PowerBB->template->assign('count',$PowerBB->_GET['count']);


	}

	function _preview()
	{
		global $PowerBB;
		$PowerBB->functions->GetEditorTools();
       $PowerBB->_GET['qu_Subject'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['qu_Subject'],'intval');
       $PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');
       $PowerBB->_GET['user'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['user'],'trim');
       $PowerBB->_GET['qu_Reply'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['qu_Reply'],'intval');
		$PowerBB->template->assign('id',$PowerBB->_GET['id']);

          if (!empty($PowerBB->_GET['qu_Subject']))
          {
          	 $SubjectArr = array();
		     $SubjectArr['where'] = array('id',$PowerBB->_GET['qu_Subject']);

		     $PowerBB->_CONF['template']['QuoteSubjectInfo'] = $PowerBB->core->GetInfo($SubjectArr,'subject');
		     $PowerBB->_CONF['template']['QuoteSubjectInfo']['text'] = $PowerBB->Powerparse->replace_htmlentities($PowerBB->_CONF['template']['QuoteSubjectInfo']['text']);
              $quote = '[quote="' . $PowerBB->_GET['user'] . '" id="'.$PowerBB->_GET['qu_Subject'].'" write_time="'.$PowerBB->_CONF['template']['QuoteSubjectInfo']['native_write_time'].'"]' . $PowerBB->_CONF['template']['QuoteSubjectInfo']['text'] . '[/quote]';
             $PowerBB->template->assign('quote',$quote);
         }

		 if (!empty($PowerBB->_GET['qu_Reply']))
         {
            $QuoteReplyInfo = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['reply'] . " WHERE id = " . $PowerBB->_GET['qu_Reply'] . "  ");
            $PowerBB->_CONF['template']['QuoteReplyInfo'] = $PowerBB->DB->sql_fetch_array($QuoteReplyInfo);
            $PowerBB->_CONF['template']['QuoteReplyInfo']['text'] = $PowerBB->Powerparse->remove_message_quotes($PowerBB->_CONF['template']['QuoteReplyInfo']['text']);
            $PowerBB->_CONF['template']['QuoteReplyInfo']['text'] = $PowerBB->Powerparse->replace_htmlentities($PowerBB->_CONF['template']['QuoteReplyInfo']['text']);
              $quote = '[quote="' . $PowerBB->_GET['user'] . '" id="'.$PowerBB->_GET['qu_Reply'].'" write_time="'.$PowerBB->_CONF['template']['QuoteReplyInfo']['write_time'].'"]' . $PowerBB->_CONF['template']['QuoteReplyInfo']['text'] . '[/quote]';
             $PowerBB->template->assign('quote',$quote);
         }


         // View subject in template new_reply
         if (!empty($PowerBB->_GET['id']))
         {

            $SubjectArr = $PowerBB->DB->sql_query("SELECT  *   FROM " . $PowerBB->table['subject'] . " WHERE id = " . $PowerBB->_GET['id'] . "  ");
            $GeSubjectInfo = array();
            while ($GeSubjectInfo = $PowerBB->DB->sql_fetch_array($SubjectArr))
            {
            $GeSubjectInfo['text'] = str_ireplace('&quot;','"',$GeSubjectInfo['text']);
            $GeSubjectInfo['text'] = str_ireplace('{39}',"'",$GeSubjectInfo['text']);
            $GeSubjectInfo['text'] = str_ireplace('cookie','**',$GeSubjectInfo['text']);
            $GeSubjectInfo['text'] = $PowerBB->Powerparse->replace($GeSubjectInfo['text']);
            $PowerBB->Powerparse->replace_smiles($GeSubjectInfo['text']);
            $PowerBB->template->assign('GeSubjectInfo',$GeSubjectInfo);
           }

         }

       // Get the attachment information

			$AttachArr 							= 	array();
			$AttachArr['where']					= 	array();
			$AttachArr['where'][0] 				=	array();
			$AttachArr['where'][0]['name'] 		=	'subject_id';
			$AttachArr['where'][0]['oper'] 		=	'=';
			$AttachArr['where'][0]['value'] 	=	$PowerBB->_GET['id'];
			$AttachArr['where'][1] 				=	array();
			$AttachArr['where'][1]['con']		=	'AND';
			$AttachArr['where'][1]['name'] 		=	'reply';
			$AttachArr['where'][1]['oper'] 		=	'=';
			$AttachArr['where'][1]['value'] 	=	'-'.$PowerBB->_CONF['member_row']['id'];

			$PowerBB->_CONF['template']['while']['AttachList'] = $PowerBB->core->GetList($AttachArr,'attach');


          //////////

        $ExArr 						= 	array();
		$ExArr['order']				=	array();
		$ExArr['order']['field']	=	'id';
		$ExArr['order']['type']		=	'DESC';
		$ExArr['proc'] 				= 	array();
		$ExArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');

		$PowerBB->_CONF['template']['while']['ExList'] = $PowerBB->core->GetList($ExArr,'ex');

        ///////////////////
         $PowerBB->template->assign('section_id',$this->SectionInfo['id']);
        // show Custom_bbcode List
		$Custom_bbcodeArr 					= 	array();
		$Custom_bbcodeArr['order']			=	array();
		$Custom_bbcodeArr['order']['field']	=	'id';
		$Custom_bbcodeArr['order']['type']	=	'DESC';
		$Custom_bbcodeArr['proc'] 			= 	array();
		$Custom_bbcodeArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');

		$PowerBB->_CONF['template']['while']['Custom_bbcodesList'] = $PowerBB->core->GetList($Custom_bbcodeArr,'custom_bbcode');

	        if($PowerBB->_CONF['info_row']['captcha_type'] == 'captcha_Q_A')
			 {
				$question = $PowerBB->_CONF['info_row']['questions'];
				$answer = $PowerBB->_CONF['info_row']['answers'];
				$c1 = explode("\r\n",$question);
				$c2 = explode("\r\n",$answer);
				$rand = array_rand($c2);
				$question = $c1[$rand];
				$answer = $c2[$rand];
				$PowerBB->template->assign('question',$question);
				$PowerBB->template->assign('answer',$answer);
		     }
	      $PowerBB->template->display('new_reply');


          //////////

        // View 10 replys Inverse in template new_reply
         if (!empty($PowerBB->_GET['id']))
         {
         $ReplyArr = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['reply'] . " WHERE subject_id = " . $PowerBB->_GET['id'] . " and delete_topic <>1 and review_reply <>1 ORDER by ID DESC limit 10 ");
         while ($GeReplyInfo = $PowerBB->DB->sql_fetch_array($ReplyArr))
         {
            $GeReplyInfo['text'] = $PowerBB->Powerparse->replace($GeReplyInfo['text']);
            $GeReplyInfo['text'] = $PowerBB->Powerparse->censor_words($GeReplyInfo['text']);
            $GeReplyInfo['title'] = $PowerBB->Powerparse->censor_words($GeReplyInfo['title']);
            $PowerBB->Powerparse->replace_smiles($GeReplyInfo['text']);

            $PowerBB->_CONF['template']['GeReplyInfo'] = $GeReplyInfo;

            $PowerBB->template->display('view_reply');
         }
        }

	}


	function _Index()
	{
		global $PowerBB;

		$PowerBB->functions->GetEditorTools();
        $PowerBB->_GET['qu_Subject'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['qu_Subject'],'intval');
        $PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');
        $PowerBB->_GET['user'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['user'],'trim');
        $PowerBB->_GET['qu_Reply'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['qu_Reply'],'intval');
		$PowerBB->template->assign('id',$PowerBB->_GET['id']);

          if (!empty($PowerBB->_GET['qu_Subject']))
          {
          	 $SubjectArr = array();
		     $SubjectArr['where'] = array('id',$PowerBB->_GET['qu_Subject']);

		     $PowerBB->_CONF['template']['QuoteSubjectInfo'] = $PowerBB->core->GetInfo($SubjectArr,'subject');
             $PowerBB->_CONF['template']['QuoteSubjectInfo']['text'] = $PowerBB->Powerparse->remove_message_quotes($PowerBB->_CONF['template']['QuoteSubjectInfo']['text']);
             $PowerBB->_CONF['template']['QuoteSubjectInfo']['text'] = $PowerBB->Powerparse->replace_htmlentities($PowerBB->_CONF['template']['QuoteSubjectInfo']['text']);
             $quote = '[quote="' . $PowerBB->_GET['user'] . '" id="'.$PowerBB->_GET['qu_Subject'].'" write_time="'.$PowerBB->_CONF['template']['QuoteSubjectInfo']['native_write_time'].'"]' . $PowerBB->_CONF['template']['QuoteSubjectInfo']['text'] . '[/quote]';
             $PowerBB->template->assign('quote',$quote);
         }


		 //-----------------------------------------
		// Reset multi-quote cookie
		//-----------------------------------------

		if ( ! $mqtids )
		{
            $mqtids = $PowerBB->_COOKIE[$PowerBB->_CONF['mqtids']];


          if ($mqtids!='')
          {

          	if ($mqtids == ",")
			{
				$mqtids = "";
			}
           else
			{
				$PowerBB->template->assign('mqtids', $mqtids);

            }
           }
       }
		 if (!empty($PowerBB->_GET['qu_Reply']))
         {

            $QuoteReplyInfo = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['reply'] . " WHERE id = " . $PowerBB->_GET['qu_Reply'] . "  ");
            $PowerBB->_CONF['template']['QuoteReplyInfo'] = $PowerBB->DB->sql_fetch_array($QuoteReplyInfo);
            $PowerBB->_CONF['template']['QuoteReplyInfo']['text'] = $PowerBB->Powerparse->remove_message_quotes($PowerBB->_CONF['template']['QuoteReplyInfo']['text']);
             $PowerBB->_CONF['template']['QuoteReplyInfo']['text'] = $PowerBB->Powerparse->replace_htmlentities($PowerBB->_CONF['template']['QuoteReplyInfo']['text']);
              $quote = '[quote="' . $PowerBB->_GET['user'] . '" id="'.$PowerBB->_GET['qu_Reply'].'" write_time="'.$PowerBB->_CONF['template']['QuoteReplyInfo']['write_time'].'"]' . $PowerBB->_CONF['template']['QuoteReplyInfo']['text'] . '[/quote]';
             $PowerBB->template->assign('quote',$quote);
         }



    	$PowerBB->functions->ShowHeader($PowerBB->_CONF['template']['_CONF']['lang']['add_new_reply']);


         // View subject in template new_reply
         if (!empty($PowerBB->_GET['id']))
         {

            $SubjectArr = $PowerBB->DB->sql_query("SELECT  *   FROM " . $PowerBB->table['subject'] . " WHERE id = " . $PowerBB->_GET['id'] . "  ");
            $GeSubjectInfo = array();
            while ($GeSubjectInfo = $PowerBB->DB->sql_fetch_array($SubjectArr))
            {
            $GeSubjectInfo['text'] = str_ireplace('&quot;','',$GeSubjectInfo['text']);
            $GeSubjectInfo['text'] = str_ireplace('{39}',"'",$GeSubjectInfo['text']);
            $GeSubjectInfo['text'] = str_ireplace('cookie','**',$GeSubjectInfo['text']);
            $GeSubjectInfo['text'] = $PowerBB->Powerparse->replace($GeSubjectInfo['text']);
            $PowerBB->Powerparse->replace_smiles($GeSubjectInfo['text']);

            $PowerBB->template->assign('GeSubjectInfo',$GeSubjectInfo);
           }

         }


       // Get the attachment information

			$AttachArr 							= 	array();
			$AttachArr['where']					= 	array();
			$AttachArr['where'][0] 				=	array();
			$AttachArr['where'][0]['name'] 		=	'subject_id';
			$AttachArr['where'][0]['oper'] 		=	'=';
			$AttachArr['where'][0]['value'] 	=	$PowerBB->_GET['id'];
			$AttachArr['where'][1] 				=	array();
			$AttachArr['where'][1]['con']		=	'AND';
			$AttachArr['where'][1]['name'] 		=	'reply';
			$AttachArr['where'][1]['oper'] 		=	'=';
			$AttachArr['where'][1]['value'] 	=	'-'.$PowerBB->_CONF['member_row']['id'];

			$PowerBB->_CONF['template']['while']['AttachList'] = $PowerBB->core->GetList($AttachArr,'attach');


		////////


        $ExArr 						= 	array();
		$ExArr['order']				=	array();
		$ExArr['order']['field']	=	'id';
		$ExArr['order']['type']		=	'DESC';
		$ExArr['proc'] 				= 	array();
		$ExArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');

		$PowerBB->_CONF['template']['while']['ExList'] = $PowerBB->core->GetList($ExArr,'ex');

        ///////////////////
         $PowerBB->template->assign('section_id',$this->SectionInfo['id']);

        $checked 			= 	'no_icon';
	    $PowerBB->template->assign('checked',$checked);

        // show Custom_bbcode List
		$Custom_bbcodeArr 					= 	array();
		$Custom_bbcodeArr['order']			=	array();
		$Custom_bbcodeArr['order']['field']	=	'id';
		$Custom_bbcodeArr['order']['type']	=	'DESC';
		$Custom_bbcodeArr['proc'] 			= 	array();
		$Custom_bbcodeArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');

		$PowerBB->_CONF['template']['while']['Custom_bbcodesList'] = $PowerBB->core->GetList($Custom_bbcodeArr,'custom_bbcode');


	        if($PowerBB->_CONF['info_row']['captcha_type'] == 'captcha_Q_A')
			 {
				$question = $PowerBB->_CONF['info_row']['questions'];
				$answer = $PowerBB->_CONF['info_row']['answers'];
				$c1 = explode("\r\n",$question);
				$c2 = explode("\r\n",$answer);
				$rand = array_rand($c2);
				$question = $c1[$rand];
				$answer = $c2[$rand];
				$PowerBB->template->assign('question',$question);
				$PowerBB->template->assign('answer',$answer);
		     }

         eval($PowerBB->functions->get_fetch_hooks('new_reply'));
	      $PowerBB->template->display('new_reply');


          //////////

        // View 10 replys Inverse in template new_reply
          if (!empty($PowerBB->_GET['id']))
         {
         $ReplyArr = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['reply'] . " WHERE subject_id = " . $PowerBB->_GET['id'] . " and delete_topic <>1 and review_reply <>1 ORDER by ID DESC limit 10 ");
         while ($GeReplyInfo = $PowerBB->DB->sql_fetch_array($ReplyArr))
         {
            $GeReplyInfo['text'] = $PowerBB->Powerparse->replace($GeReplyInfo['text']);
            $GeReplyInfo['text'] = $PowerBB->Powerparse->censor_words($GeReplyInfo['text']);
            $GeReplyInfo['title'] = $PowerBB->Powerparse->censor_words($GeReplyInfo['title']);
            $PowerBB->Powerparse->replace_smiles($GeReplyInfo['text']);

            $PowerBB->_CONF['template']['GeReplyInfo'] = $GeReplyInfo;
            $PowerBB->template->display('view_reply');
         }
        }

	}

	function _Start()
	{
		global $PowerBB;

		 setcookie('mqtids','',time()-360000);
        $PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');
        $PowerBB->_GET['reply_id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['reply_id'],'intval');
        $PowerBB->_GET['subject_id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['subject_id'],'intval');
  		$Subjectid = $PowerBB->_GET['id'];
        $PagerReplyNumArr = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['reply'] . " WHERE subject_id='$Subjectid' and delete_topic <>1"));

		$PowerBB->_POST['title'] = $PowerBB->functions->CleanVariable($PowerBB->_POST['title'],'trim');

      if ($PowerBB->_POST['preview'])
       {
        define('DONT_STRIPS_SLIASHES',true);
		$PowerBB->_POST['text'] = str_replace('target="_blank" ','',$PowerBB->_POST['text']);
		$PowerBB->template->assign('prev',$PowerBB->Powerparse->replace_htmlentities($PowerBB->_POST['text']));
		$PowerBB->_POST['text'] = $PowerBB->Powerparse->replace($PowerBB->_POST['text']);
		$PowerBB->Powerparse->replace_smiles($PowerBB->_POST['text']);
        $PowerBB->_POST['text'] = $PowerBB->Powerparse->censor_words($PowerBB->_POST['text']);

		$PowerBB->template->assign('reply_id',$PowerBB->_GET['reply_id']);
		$PowerBB->template->assign('subject_id',$PowerBB->_GET['subject_id']);
		$PowerBB->template->assign('preview',$PowerBB->_POST['text']);
		$PowerBB->template->assign('view_preview',$PowerBB->_POST['text']);

		$PowerBB->_POST['reason_edit'] = $PowerBB->Powerparse->censor_words($PowerBB->_POST['reason_edit']);
		$PowerBB->_POST['reason_edit'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['reason_edit'],'html');
		$PowerBB->_POST['reason_edit'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['reason_edit'],'sql');

		$PowerBB->template->assign('reason_edit',$PowerBB->_POST['reason_edit']);
          $PowerBB->functions->ShowHeader();
           $this->_preview();
        	$PowerBB->functions->GetFooter();


        }
      else
       {
			 if (!isset($PowerBB->_POST['ajax']))
			 {

		            if ((time() - $PowerBB->_CONF['info_row']['floodctrl']) <= $PowerBB->_CONF['member_row']['lastpost_time'])
		            {
						if (!$PowerBB->functions->ModeratorCheck($this->SectionInfo['id']))
						{
						   $PowerBB->functions->ShowHeader();
  			               $floodctrl = @time() - $PowerBB->_CONF['member_row']['lastpost_time'] - $PowerBB->_CONF['info_row']['floodctrl'] ;
  			               $PowerBB->_CONF['template']['_CONF']['lang']['floodctrl'] = str_replace($PowerBB->_CONF['info_row']['floodctrl'], " <b>".$floodctrl."</b> ", $PowerBB->_CONF['template']['_CONF']['lang']['floodctrl']);
  			               $PowerBB->_CONF['template']['_CONF']['lang']['floodctrl'] = str_replace("-", "", $PowerBB->_CONF['template']['_CONF']['lang']['floodctrl']);
						   $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['floodctrl']);
						}
		            }
		     		else
		     		{

		              	if (empty($PowerBB->_POST['text']))
						{
		     				   $PowerBB->functions->ShowHeader($PowerBB->_CONF['template']['_CONF']['lang']['Mistake']);
		                       $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['post_text_min']);
						}


			                    $TextPost = utf8_decode($PowerBB->_POST['text']);
			                    $TextPost = preg_replace('#\[IMG\](.*)\[/IMG\]#siU', '', $TextPost);

			      				$text_max_num = strlen($TextPost) <= $PowerBB->_CONF['info_row']['post_text_max'];
					     		if ($text_max_num)
					     		{
			                     // Continue
					    		}
								else
								{
								$PowerBB->functions->ShowHeader($PowerBB->_CONF['template']['_CONF']['lang']['add_new_topic']);
								$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['post_text_max']);
								$PowerBB->functions->error_stop();
								}

                                $TextPost = preg_replace('/\s+/', '', $TextPost);
					        	if  (strlen($TextPost) >= $PowerBB->_CONF['info_row']['post_text_min'])
					     		{
			                     // Continue
					     		}
								else
								{
								$PowerBB->functions->ShowHeader($PowerBB->_CONF['template']['_CONF']['lang']['add_new_topic']);
								$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['post_text_min']);
								$PowerBB->functions->error_stop();
								}


				     	if (!$PowerBB->_CONF['member_permission'])
			            {

					        if($PowerBB->_CONF['info_row']['captcha_type'] == 'captcha_Q_A')
							 {
				                if($PowerBB->_POST['code'] != $PowerBB->_POST['code_answer'])
								 {
					      		    $PowerBB->functions->ShowHeader();
						            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['random_answer_not_correct']);
							     }
						     }
					        if($PowerBB->_CONF['info_row']['captcha_type'] == 'captcha_IMG')
							 {
						        if(md5($PowerBB->_POST['code']) != $_SESSION['captcha_key'])
								 {
					      		    $PowerBB->functions->ShowHeader();
						            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Code_that_you_enter_the_wrong']);
							     }
						    }
							if (empty($PowerBB->_POST['guest_name']))
							{
			     				   $PowerBB->functions->ShowHeader($PowerBB->_CONF['template']['_CONF']['lang']['Mistake']);
			                       $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['You_do_not_type_your_name']);
							}
			            }



		     	   }

		     }
		     else
		     {
						     if ((time() - 1800) <= $PowerBB->_CONF['member_row']['lastpost_time'])
						     {
		     	              $ajax_lastpost_time = true;
						     }
						     else
		     	             {
		     	              $ajax_lastpost_time = false;
						     }

                        	if ($this->SubjectInfo['last_replier'] == $PowerBB->_CONF['member_row']['username'] and $ajax_lastpost_time)
							{
						      $PowerBB->template->assign('ajax_last_replier',true);
						      $ajax_last_replier = true;
							}
							else
							{
						      $PowerBB->template->assign('ajax_last_replier',false);
						      $ajax_last_replier = false;
							}

		            if ((time() - $PowerBB->_CONF['info_row']['floodctrl']) <= $PowerBB->_CONF['member_row']['lastpost_time'])
		            {
						if (!$PowerBB->functions->ModeratorCheck($this->SectionInfo['id']))
						{
  			               $floodctrl = @time() - $PowerBB->_CONF['member_row']['lastpost_time'] - $PowerBB->_CONF['info_row']['floodctrl'] ;
  			               $PowerBB->_CONF['template']['_CONF']['lang']['floodctrl'] = str_replace($PowerBB->_CONF['info_row']['floodctrl'], " <b>".$floodctrl."</b> ", $PowerBB->_CONF['template']['_CONF']['lang']['floodctrl']);
  			               $PowerBB->_CONF['template']['_CONF']['lang']['floodctrl'] = str_replace("-", "", $PowerBB->_CONF['template']['_CONF']['lang']['floodctrl']);
  			               echo "<!-- #flood: -->";
						   $PowerBB->functions->error_no_foot($PowerBB->_CONF['template']['_CONF']['lang']['floodctrl']);

						}
		            }

				     	if (!$PowerBB->_CONF['member_permission'])
			            {

					        if($PowerBB->_CONF['info_row']['captcha_type'] == 'captcha_Q_A')
							 {
				                if($PowerBB->_POST['code'] != $PowerBB->_POST['code_answer'])
								 {
								    echo "<!-- #flood: -->";
						            $PowerBB->functions->error_no_foot($PowerBB->_CONF['template']['_CONF']['lang']['random_answer_not_correct']);
							     }
						     }
					        if($PowerBB->_CONF['info_row']['captcha_type'] == 'captcha_IMG')
							 {
						        if(md5($PowerBB->_POST['code']) != $_SESSION['captcha_key'])
								 {
								    echo "<!-- #flood: -->";
						            $PowerBB->functions->error_no_foot($PowerBB->_CONF['template']['_CONF']['lang']['Code_that_you_enter_the_wrong']);
							     }
						    }
							if (empty($PowerBB->_POST['guest_name']))
							{
							       echo "<!-- #flood: -->";
			                       $PowerBB->functions->error_no_foot($PowerBB->_CONF['template']['_CONF']['lang']['You_do_not_type_your_name']);
							}
			            }

			 }
				if (empty($PowerBB->_POST['text']))
				{
					if (!isset($PowerBB->_POST['ajax']))
		     		{
					$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_fill_in_all_the_information']);
					}
					else
					{
					echo "<!-- #flood: -->";
					$PowerBB->functions->error_no_foot($PowerBB->_CONF['template']['_CONF']['lang']['Please_fill_in_all_the_information']);
					}
				}


				if ($PowerBB->_POST['stick'])
				{
					$UpdateArr = array();
					$UpdateArr['where'] = array('id',$this->SubjectInfo['id']);

					$update = $PowerBB->subject->StickSubject($UpdateArr);
				}

				if ($PowerBB->_POST['close'])
				{
					$UpdateArr = array();
					$UpdateArr['reason'] = $PowerBB->_POST['reason'];
					$UpdateArr['where'] = array('id',$this->SubjectInfo['id']);

					$update = $PowerBB->subject->CloseSubject($UpdateArr);
				}
                if ($PowerBB->_POST['unstick'])
                {
                   $UpdateArr = array();
                   $UpdateArr['where'] = array('id',$this->SubjectInfo['id']);
                   $update = $PowerBB->subject->UnstickSubject($UpdateArr);
                }

                if ($PowerBB->_POST['unclose'])
                {
                    $UpdateArr = array();
                    $UpdateArr['reason'] = $PowerBB->_POST['reason'];
                    $UpdateArr['where'] = array('id',$this->SubjectInfo['id']);
                    $update = $PowerBB->subject->OpenSubject($UpdateArr);
                }

                   // Filter Words
				$PowerBB->_POST['text'] = str_replace('target="_blank" ','',$PowerBB->_POST['text']);
				$PowerBB->_POST['title'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['title'],'sql');
				//$PowerBB->_POST['text'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['text'],'sql');
                //

                  // mention users tag replace
                if($PowerBB->functions->mention_permissions())
                {
				  if(preg_match('/\[mention\](.*?)\[\/mention\]/s', $PowerBB->_POST['text'], $tags_w))
					{
					$username = trim($tags_w[1]);
					$MemArr = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['member'] . " WHERE username = '$username' ");
					$Member_row = $PowerBB->DB->sql_fetch_array($MemArr);
                    if($Member_row)
                    {
						if ($Member_row['username'] == $PowerBB->_CONF['member_row']['username'])
						{
				        $PowerBB->_POST['text'] = str_replace("[mention]", "@", $PowerBB->_POST['text']);
						$PowerBB->_POST['text'] = str_replace("[/mention]", "", $PowerBB->_POST['text']);
						 $Member_row['username'] = '';
						}
						if (!empty($Member_row['username']))
						{
						$forum_url              =   $PowerBB->functions->GetForumAdress();
						$url = $forum_url."index.php?page=profile&amp;show=1&amp;id=".$Member_row['id'];
						$PowerBB->_POST['text'] = str_replace("[mention]", "[url=".$PowerBB->functions->rewriterule($url)."]@", $PowerBB->_POST['text']);
						$PowerBB->_POST['text'] = str_replace("[/mention]", "[/url]", $PowerBB->_POST['text']);
	                    // insert mention
	                    $insert_mention = 	true;
						}
				    }
                  }
                 }

		     	$ReplyArr 			                = 	array();
		     	$ReplyArr['get_id']					=	true;
		     	$ReplyArr['field']               	= 	array();
		     	$ReplyArr['field']['title'] 		= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['title'],'html');
		     	if (!$PowerBB->_CONF['member_permission'])
				{
		     	$ReplyArr['field']['text'] 			= 	$PowerBB->functions->CleanVariable('[color=#4000BF][i][guest_name]'.$PowerBB->_CONF['template']['_CONF']['lang']['LastsPostsWriter'].$PowerBB->_POST['guest_name'].'[/guest_name][/i][/color]<br />'.$PowerBB->_POST['text'],'nohtml');
				$ReplyArr['field']['writer'] 		= 	'Guest';
				}
		     	else
		     	{
		     	$ReplyArr['field']['text'] 			= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['text'],'nohtml');
				$ReplyArr['field']['writer']				= 	$PowerBB->_CONF['rows']['member_row']['username'];
				}
		     	$ReplyArr['field']['subject_id'] 	= 	$this->SubjectInfo['id'];
		     	$ReplyArr['field']['write_time'] 	= 	$PowerBB->_CONF['now'];
		     	$ReplyArr['field']['section'] 		= 	$this->SubjectInfo['section'];
		     	$ReplyArr['field']['icon'] 			= 	$PowerBB->_POST['icon'];
				if (($PowerBB->_CONF['member_row']['review_reply'] or $PowerBB->_CONF['group_info']['review_reply'])
				and !$PowerBB->functions->ModeratorCheck($this->SectionInfo['id']))
				{
					$ReplyArr['field']['review_reply'] = 1;
				}


		     	$Insert = $PowerBB->reply->InsertReply($ReplyArr);

		     	if ($Insert)
		     	{
		     		//////////
			     	if (($PowerBB->_CONF['member_row']['review_reply'] or $PowerBB->_CONF['group_info']['review_reply'])
						and !$PowerBB->functions->ModeratorCheck($this->SectionInfo['id']))
					{

	                    $ReviewReply 					= 	array();
			     		$ReviewReply['review_reply']	=	 $this->SubjectInfo['review_reply'] +1;
			     		$ReviewReply['where'] 			= 	array('id',$this->SubjectInfo['id']);

			     		$UpdateReviewReply = $PowerBB->subject->UpdateReviewReply($ReviewReply);
                    }


		           if ($PowerBB->functions->section_group_permission($this->SectionInfo['id'],$PowerBB->_CONF['group_info']['id'],'no_posts'))
		     		{
		     			$posts = $PowerBB->_CONF['member_row']['posts'] + 1;
		     		}
		     		else
		     		{
		     			$posts = $PowerBB->_CONF['member_row']['posts'];
		     		}

		     		if ($PowerBB->_CONF['group_info']['usertitle_change'])
		     		{
		     			$UsertitleArr 			= 	array();
		     			$UsertitleArr['where'] 	= 	array('posts',$posts);

		     			$UserTitle = $PowerBB->usertitle->GetUsertitleInfo($UsertitleArr);

		     			if ($UserTitle != false)
		     			{
		     				$usertitle = $UserTitle['usertitle'];
		     			}
		     			else
			     		{
							$GrpArr 			= 	array();
							$GrpArr['where'] 	= 	array('id',$PowerBB->_CONF['rows']['member_row']['usergroup']);

							$GroupStyleInfo = $PowerBB->core->GetInfo($GrpArr,'group');

			     			$usertitle = $GroupStyleInfo['user_title'];
			     		}
		     		}

		     		//////////

			   		$MemberArr 				= 	array();
			   		$MemberArr['field'] 	= 	array();

		     		$MemberArr['field']['posts']			=	$posts;
		     		$MemberArr['field']['lastpost_time'] 	=	$PowerBB->_CONF['now'];
		     		$MemberArr['field']['user_title']		=	(isset($usertitle)) ? $usertitle : null;
		     		$MemberArr['where']						=	array('id',$PowerBB->_CONF['member_row']['id']);

		   			$UpdateMember = $PowerBB->member->UpdateMember($MemberArr);

		     		$TimeArr = array();

		     		$TimeArr['write_time'] 	= 	$PowerBB->_CONF['now'];
		     		$TimeArr['where']		=	array('id',$this->SubjectInfo['id']);

		     		$UpdateWriteTime = $PowerBB->subject->UpdateWriteTime($TimeArr);

		     		$RepArr 					= 	array();
		     		$RepArr['reply_number']		=	$PagerReplyNumArr+1;
		     		$RepArr['where'] 			= 	array('id',$this->SubjectInfo['id']);

		     		$UpdateReplyNumber = $PowerBB->subject->UpdateReplyNumber($RepArr);





                   $UpdateSubjectNumber = $PowerBB->cache->UpdateReplyNumber(array('reply_num'	=>	$PowerBB->_CONF['info_row']['reply_number']));

		     		//////////
		     		if (!$PowerBB->_CONF['member_permission'])
					{
	                $writer = 	$PowerBB->_CONF['template']['_CONF']['lang']['Guestp'];
					}
			     	else
			     	{
		            $writer = 	$PowerBB->_CONF['member_row']['username'];
					}

			     	if (($PowerBB->_CONF['member_row']['review_reply'] or $PowerBB->_CONF['group_info']['review_reply'])
						and !$PowerBB->functions->ModeratorCheck($this->SectionInfo['id']))
					{
						$review_reply = 1;
					}

		     		// The number of section's subjects number
		     		$UpdateArr 					= 	array();
		     		$UpdateArr['field']			=	array();

		     		$UpdateArr['field']['reply_num'] 	= 	$this->SectionInfo['reply_num'] + 1;
                   if(!$this->SubjectInfo['review_subject']){
					$UpdateArr['field']['last_writer'] 		= 	$writer;
		     		$UpdateArr['field']['last_subject'] 		= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['title'],'html');
		     		$UpdateArr['field']['last_subjectid'] 	= 	$this->SubjectInfo['id'];
		     		$UpdateArr['field']['last_date'] 	= 	$PowerBB->_CONF['now'];
		     		$UpdateArr['field']['last_time'] 	= 	$PowerBB->_CONF['now'];
		     		$UpdateArr['field']['last_reply'] 	= 	$PowerBB->reply->id;
		     		$UpdateArr['field']['icon'] 	    = 	$this->SubjectInfo['icon'];
		     		$UpdateArr['field']['last_berpage_nm']  = 	$PowerBB->_POST['count'];
		     		}
		     		$UpdateArr['field']['replys_review_num'] 		= 	$this->SectionInfo['replys_review_num'] + $review_reply;

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

                    $get_section_parent = $PowerBB->DB->sql_query("SELECT  *   FROM " . $PowerBB->table['section'] . " WHERE parent = " . $this->SectionInfo['parent'] . " ");
	                $Inf_row = $PowerBB->DB->sql_fetch_array($get_section_parent);
                        if ($Inf_row)
						{

	                           //////////
				     		if (!$PowerBB->_CONF['member_permission'])
							{
			                $writer = 	$PowerBB->_CONF['template']['_CONF']['lang']['Guestp'];
							}
					     	else
					     	{
				            $writer = 	$PowerBB->_CONF['member_row']['username'];
							}

				     		$UpdateLastprantArr = array();
				     		$UpdateLastprantArr['field']			=	array();
							$UpdateLastprantArr['field']['last_writer'] 		= 	$writer;
				     		$UpdateLastprantArr['field']['last_subject'] 		= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['title'],'html');
				     		$UpdateLastprantArr['field']['last_subjectid'] 	= 	$this->SubjectInfo['id'];
				     		$UpdateLastprantArr['field']['last_date'] 	= 	$PowerBB->_CONF['now'];
				     		$UpdateLastprantArr['field']['last_time'] 	= 	$PowerBB->_CONF['now'];
		     	         	$UpdateLastprantArr['field']['icon'] 	    = 	$this->SubjectInfo['icon'];
		     		        $UpdateLastprantArr['field']['last_reply'] 	= 	$PowerBB->reply->id;
		     		        $UpdateLastprantArr['field']['last_berpage_nm']  = 	$PowerBB->_POST['count'];

				     		$UpdateLastprantArr['where'] 		        = 	array('id',$Inf_row['parent']);

				     		// Update Last subject's information
				     		$UpdateprantLast = $PowerBB->section->UpdateSection($UpdateLastprantArr);
							unset($UpdateLastprantArr);


				     	}

                     $get_sections_parent = $PowerBB->DB->sql_query("SELECT  *   FROM " . $PowerBB->table['section'] . " WHERE parent = " . $this->SectionInfo['parent'] . " ");
	                $Inf_rows = $PowerBB->DB->sql_fetch_array($get_section_parent);
                        if ($Inf_rows)
						{

	                           //////////
				     		if (!$PowerBB->_CONF['member_permission'])
							{
			                $writer = 	$PowerBB->_CONF['template']['_CONF']['lang']['Guestp'];
							}
					     	else
					     	{
				            $writer = 	$PowerBB->_CONF['member_row']['username'];
							}

				     		$UpdateLastprantArr = array();
				     		$UpdateLastprantArr['field']			=	array();
							$UpdateLastprantArr['field']['last_writer'] 		= 	$writer;
				     		$UpdateLastprantArr['field']['last_subject'] 		= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['title'],'html');
				     		$UpdateLastprantArr['field']['last_subjectid'] 	= 	$this->SubjectInfo['id'];
				     		$UpdateLastprantArr['field']['last_date'] 	= 	$PowerBB->_CONF['now'];
				     		$UpdateLastprantArr['field']['last_time'] 	= 	$PowerBB->_CONF['now'];
		     		        $UpdateLastprantArr['field']['last_reply'] 	= 	$PowerBB->reply->id;
		     		        $UpdateLastprantArr['field']['last_berpage_nm']  = 	$PowerBB->_POST['count'];
		     	         	$UpdateLastprantArr['field']['icon'] 	    = 	$this->SubjectInfo['icon'];
				     		$UpdateLastprantArr['where'] 		        = 	array('parent',$Inf_rows['id']);

				     		// Update Last subject's information
				     		$UpdateprantLast = $PowerBB->section->UpdateSection($UpdateLastprantArr);
							unset($UpdateLastprantArr);

					     		// Update section's cache
					     		$UpdateArr 				= 	array();
					     		$UpdateArr['parent'] 	= 	$Inf_rows['parent'];

					     		$update_cache = $PowerBB->section->UpdateSectionsCache($UpdateArr);

					     		unset($UpdateArr);

				     		if ($UpdateprantLast)
							{

		                           //////////
					     		if (!$PowerBB->_CONF['member_permission'])
								{
				                $writer = 	$PowerBB->_CONF['template']['_CONF']['lang']['Guestp'];
								}
						     	else
						     	{
					            $writer = 	$PowerBB->_CONF['member_row']['username'];
								}

					     		$UpdateLastprantArr = array();
					     		$UpdateLastprantArr['field']			=	array();
								$UpdateLastprantArr['field']['last_writer'] 		= 	$writer;
					     		$UpdateLastprantArr['field']['last_subject'] 		= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['title'],'html');
					     		$UpdateLastprantArr['field']['last_subjectid'] 	= 	$this->SubjectInfo['id'];
					     		$UpdateLastprantArr['field']['last_date'] 	= 	$PowerBB->_CONF['now'];
					     		$UpdateLastprantArr['field']['last_time'] 	= 	$PowerBB->_CONF['now'];
		     		            $UpdateLastprantArr['field']['last_reply'] 	= 	$PowerBB->reply->id;
		     		            $UpdateLastprantArr['field']['last_berpage_nm']  = 	$PowerBB->_POST['count'];
		     	         	    $UpdateLastprantArr['field']['icon'] 	    = 	$this->SubjectInfo['icon'];
					     		$UpdateLastprantArr['where'] 		        = 	array('id',$Inf_rows['parent']);

					     		// Update Last subject's information
					     		$UpdateprantLast = $PowerBB->section->UpdateSection($UpdateLastprantArr);
								unset($UpdateLastprantArr);

						     		// Update section's cache
						     		$UpdateArr 				= 	array();
						     		$UpdateArr['parent'] 	= 	$Inf_rows['parent'];

						     		$update_cache = $PowerBB->section->UpdateSectionsCache($UpdateArr);

						     		unset($UpdateArr);
						     }

				     	}

								// Update reply_num & subject_num
					   if ($this->SectionInfo['parent']<1)
						{
							$SectionCache = $this->SectionInfo['id'];

								$Section_Rnum = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT section FROM " . $PowerBB->table['reply'] . " WHERE section = " . $SectionCache . " AND delete_topic<>1 AND review_reply<>1"));
								$Section_Snum = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT section FROM " . $PowerBB->table['subject'] . " WHERE section = " . $SectionCache . " AND delete_topic<>1 AND review_subject<>1 "));


								$subject_num = $Section_Snum ;
								$reply_num   = $Section_Rnum;

								$UpdatesrFormSecArr = array();
								$UpdatesrFormSecArr['field']			=	array();

								$UpdatesrFormSecArr['field']['reply_num'] 	= 	$reply_num;
								$UpdatesrFormSecArr['field']['subject_num']  = 	$subject_num;

								$UpdatesrFormSecArr['where'] 		        = 	array('id',$SectionCache);

								// Update reply_num & subject_num
								$UpdaterSFormSec = $PowerBB->section->UpdateSection($UpdatesrFormSecArr);
                                 $UpdateSectionCache1 = $PowerBB->functions->UpdateSectionCache($SectionCache);
						}
						else
						{
						$SectionCache = $this->SectionInfo['parent'];


								$S_R = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['section'] . " WHERE parent = ".$SectionCache." ");
								while ($f_S_R = $PowerBB->DB->sql_fetch_array($S_R))
								{
								$Section_Rnum = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT section FROM " . $PowerBB->table['reply'] . " WHERE section = " . $SectionCache . " AND delete_topic<>1 AND review_reply<>1"));
								$Section_Snum = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT section FROM " . $PowerBB->table['subject'] . " WHERE section = " . $SectionCache . " AND delete_topic<>1 AND review_subject<>1 "));

								$Rnum = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT section FROM " . $PowerBB->table['reply'] . " WHERE section = " . $f_S_R['id'] . " AND delete_topic<>1 AND review_reply<>1"));
								$Snum = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT section FROM " . $PowerBB->table['subject'] . " WHERE section = " . $f_S_R['id'] . " AND delete_topic<>1 AND review_subject<>1 "));

								$subject_num = $Section_Snum + $Snum;
								$reply_num   = $Section_Rnum + $Rnum;

								$UpdatesrFormSecArr = array();
								$UpdatesrFormSecArr['field']			=	array();

								$UpdatesrFormSecArr['field']['reply_num'] 	= 	$reply_num;
								$UpdatesrFormSecArr['field']['subject_num']  = 	$subject_num;

								$UpdatesrFormSecArr['where'] 		        = 	array('id',$SectionCache);

								// Update reply_num & subject_num
								$UpdaterSFormSec = $PowerBB->section->UpdateSection($UpdatesrFormSecArr);
                                 $UpdateSectionCache1 = $PowerBB->functions->UpdateSectionCache($SectionCache);
				               }
						}

			     	 if ($PowerBB->_CONF['member_permission'])
					 {
			     		$LastArr = array();
			     		$LastArr['replier'] 	= 	$PowerBB->_CONF['member_row']['username'];
			     		$LastArr['where']		=	array('id',$this->SubjectInfo['id']);

				     	$UpdateLastReplier = $PowerBB->subject->UpdateLastReplier($LastArr);


				     		//////////

				     		// Upload files

						   $GetAttachArr 					= 	array();
						   $GetAttachArr['where'] 			= 	array('reply','-'.$PowerBB->_CONF['member_row']['id']);
						   $Attachinfo = $PowerBB->core->GetInfo($GetAttachArr,'attach');

				     		if ($Attachinfo)
				     		{

							$ReplyArr 							= 	array();
							$ReplyArr['field'] 					= 	array();
							$ReplyArr['field']['attach_reply'] 	= 	'1';
							$ReplyArr['where'] 					= 	array('id',$PowerBB->reply->id);

							$update = $PowerBB->core->Update($ReplyArr,'reply');

							//	Update All Attach
							 $member_id_Attach = '-'.$PowerBB->_CONF['member_row']['id'];
		                     $getAttach = $PowerBB->DB->sql_query("SELECT  *   FROM " . $PowerBB->table['attach'] . " WHERE reply = '$member_id_Attach' ");
		                     while ($getAttach_row = $PowerBB->DB->sql_fetch_array($getAttach))
		                      {
								// Count a new download
								$UpdateArr 						= 	array();
								$UpdateArr['field'] 			= 	array();
		                        $UpdateArr['field']['subject_id']	=	$PowerBB->reply->id;
								$UpdateArr['field']['reply'] 	= 	'1';
								$UpdateArr['field']['time'] 	= 	$PowerBB->_CONF['now'];
								$UpdateArr['where'] 			= 	array('id',$getAttach_row['id']);

		                 		$update = $PowerBB->attach->UpdateAttach($UpdateArr);
		                     }
				     		}

				     		//////////

							if ($PowerBB->_CONF['info_row']['allowed_emailed'] == '1')
							{



							$SectionInfoid = $this->SectionInfo['id'];
							$SubjectInfoid = $this->SubjectInfo['id'];
							$member_row_id = $PowerBB->_CONF['member_row']['id'];

							$subject_user_emailed_nm = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['emailed'] . " WHERE subject_id='$SubjectInfoid' and user_id ='$member_row_id'"));


							if ($PowerBB->_POST['emailed'])
							{

							$EmailedArr 			= 	array();
							$EmailedArr['where'] 	= 	array('subject_id',$this->SubjectInfo['id']);

							$this->EmailedInfo = $PowerBB->emailed->GetEmailedInfo($EmailedArr);


							if ($subject_user_emailed_nm < 1)
							{
							$EmailedArr 								= 	array();
							$EmailedArr['get_id']						=	true;
							$EmailedArr['field']						=	array();
							$EmailedArr['field']['user_id'] 			= 	$PowerBB->_CONF['member_row']['id'];
							$EmailedArr['field']['subject_id'] 			= 	$this->SubjectInfo['id'];
							$EmailedArr['field']['subject_title'] 		= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['title'],'html');

							$Insert = $PowerBB->emailed->InsertEmailed($EmailedArr);
							}

							}
							//Send email notification to all participants in this department with a new reply
							$Adress_end	= 	'<a href="'.$PowerBB->functions->GetForumAdress().'index.php'.'">'.$PowerBB->functions->GetForumAdress().'index.php'.'</a>';
							$Adress = $PowerBB->functions->GetForumAdress();
							$topic_url	= 	'<a href="'.$PowerBB->functions->GetForumAdress().'index.php?page=topic&show=1&id=' . $SubjectInfoid . '">'.$PowerBB->functions->GetForumAdress().'index.php?page=topic&show=1&id=' . $SubjectInfoid . '</a>';
							$charset                =   $PowerBB->_CONF['info_row']['charset'];
							$PowerBB->_POST['message'] = $PowerBB->Powerparse->replace($PowerBB->_POST['message']);
							$starthtml = '<html dir=\"$charset\"><body>';
							$Endhtml = '</body></html>';
							$br = '<br>';
							$br = $PowerBB->Powerparse->replace($br);
							$title = $PowerBB->_CONF['template']['_CONF']['lang']['New_Reply'] ;
							$Form_Massege = $PowerBB->_CONF['member_row']['username'].$PowerBB->_CONF['template']['_CONF']['lang']['Has_written_new_Reply'] .
							$PowerBB->_POST['title'] . $br . $PowerBB->_CONF['template']['_CONF']['lang']['Please_login_on_the_following_link_to_access_the_subject'] . $topic_url .'<br>'. $PowerBB->_CONF['template']['_CONF']['lang']['greetings_Management_Forum']  . $PowerBB->_CONF['info_row']['title'] .'<br>' . $Adress_end . '';
							$Mem_not = $PowerBB->_CONF['member_row']['id'];

							$getmember_query = $PowerBB->DB->sql_query("SELECT Distinct user_id FROM " . $PowerBB->table['emailed'] . " WHERE user_id NOT IN ('$Mem_not') AND subject_id = '$SubjectInfoid'");



							if ($PowerBB->emailed->IsEmailed(array('where' => array('subject_id',$SubjectInfoid))))
							{

							while ($getmember_row = $PowerBB->DB->sql_fetch_array($getmember_query))
							{
							$MemArr 			= 	array();
							$MemArr['where'] 	= 	array('id',$getmember_row['user_id']);

							$MemInfo = $PowerBB->core->GetInfo($MemArr,'member');

							$username = $PowerBB->_CONF['template']['_CONF']['lang']['hello_your']  . $MemInfo['username'].'<br>';

		                    	if ($PowerBB->_CONF['info_row']['mailer']=='phpmail')
								{
							     $Send = $PowerBB->functions->send_this_php($MemInfo['email'],$title.':'.$PowerBB->_POST['title'],$starthtml.$username.$Form_Massege.$Endhtml,$PowerBB->_CONF['info_row']['send_email']);
					            }
								elseif ($PowerBB->_CONF['info_row']['mailer']=='smtp')
								{
								$to = $MemInfo['email'];
								$fromname = $PowerBB->_CONF['info_row']['title'];
								$message = $starthtml.$username.$Form_Massege.$Endhtml;
								$subject = $PowerBB->functions->CleanVariable($PowerBB->_POST['title'],'html');
								$from = $PowerBB->_CONF['info_row']['send_email'];
                                require_once("includes/class_mail.php");
                                define('STOP_COMMON',false);
								$mail->setFrom($from, $fromname);
								$mail->addAddress($to);     // Add a recipient
								$mail->Subject = $subject;
								$mail->Body    = $message;
								$mail->send();
					            $mail->ClearAddresses();
								}

							}
							}

							///////////

							}


				     		$LastArr = array();

				     		$LastArr['writer'] 		= 	$PowerBB->_CONF['member_row']['username'];
				     		$LastArr['title'] 		= 	$this->SubjectInfo['title'];
				     		$LastArr['subject_id'] 	= 	$this->SubjectInfo['id'];
				     		$LastArr['date'] 		= 	$PowerBB->_CONF['now'];
				     		$LastArr['last_time'] 		= 	$PowerBB->_CONF['now'];
				     		$LastArr['icon'] 		= 	$PowerBB->_POST['icon'];
				     		$LastArr['last_reply'] 		= 	$PowerBB->reply->id;
				     		$LastArr['last_berpage_nm'] 		= 	$PowerBB->_POST['count'];
				     		$LastArr['where'] 		= 	(!$this->SectionInfo['parent']) ? array('id',$this->SectionInfo['id']) : array('id',$this->SectionInfo['parent']);


				     		$UpdateLast = $PowerBB->section->UpdateLastSubject($LastArr);
				     		$PowerBB->functions->PBB_Create_last_posts_cache(0);
	                 }
	                 else
					 {
			     		$LastArr = array();
			     		$LastArr['replier'] 	= 	'Guest';
			     		$LastArr['where']		=	array('id',$this->SubjectInfo['id']);

				     	$UpdateLastReplier = $PowerBB->subject->UpdateLastReplier($LastArr);

	                        $LastArr = array();

				     		$LastArr['writer'] 		= 	'Guest';
				     		$LastArr['title'] 		= 	$this->SubjectInfo['title'];
				     		$LastArr['subject_id'] 	= 	$this->SubjectInfo['id'];
				     		$LastArr['date'] 		= 	$PowerBB->_CONF['now'];
				     		$LastArr['last_time'] 		= 	$PowerBB->_CONF['now'];
				     		$LastArr['icon'] 		= 	$PowerBB->_POST['icon'];
				     		$LastArr['last_reply'] 		= 	$PowerBB->reply->id;
				     		$LastArr['last_berpage_nm'] 		= 	$PowerBB->_POST['count'];
				     		$LastArr['where'] 		= 	(!$this->SectionInfo['parent']) ? array('id',$this->SectionInfo['id']) : array('id',$this->SectionInfo['parent']);


				     		$UpdateLast = $PowerBB->section->UpdateLastSubject($LastArr);
				     		$PowerBB->functions->PBB_Create_last_posts_cache(0);
					 }

                        // insert mention
                     if($PowerBB->functions->mention_permissions())
                     {
						if ($insert_mention)
						{
						$InsertArr 					= 	array();
						$InsertArr['field']			=	array();

						$InsertArr['field']['user_mention_about_you'] 			= 	$PowerBB->_CONF['member_row']['username'];
						$InsertArr['field']['you'] 			= 	$Member_row['username'];
						$InsertArr['field']['topic_id'] 				= 	intval($this->SubjectInfo['id']);
						$InsertArr['field']['reply_id'] 			= 	intval($PowerBB->reply->id);
						$InsertArr['field']['profile_id'] 			= 	$PowerBB->_CONF['member_row']['id'];
						$InsertArr['field']['date'] 		= 	$PowerBB->_CONF['now'];
						$InsertArr['field']['user_read'] 		    = 	'1';

						$insert = $PowerBB->core->Insert($InsertArr,'mention');
						}
                    }
                   eval($PowerBB->functions->get_fetch_hooks('insert_reply'));

					// get url to last reply
					$Reply_NumArr = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['reply'] . " WHERE subject_id='$Subjectid' and delete_topic <>1"));
					$ss_r = $PowerBB->_CONF['info_row']['perpage']/2+1;
					$roun_ss_r = round($ss_r, 0);
					$reply_number_r = $Reply_NumArr-$roun_ss_r+1;
					$pagenum_r = $reply_number_r/$PowerBB->_CONF['info_row']['perpage'];
					$round0_r = round($pagenum_r, 0);
					$countpage = $round0_r+1;
					$countpage = str_replace("-", '', $countpage);

					if($Reply_NumArr <= $PowerBB->_CONF['info_row']['perpage'])
					{
					$header_redirect = 'index.php?page=topic&amp;show=1&amp;id=' . $this->SubjectInfo['id'] . '#' . $PowerBB->reply->id;
					}
					elseif($Reply_NumArr > $PowerBB->_CONF['info_row']['perpage'])
					{
					$header_redirect = 'index.php?page=topic&amp;show=1&amp;id=' . $this->SubjectInfo['id'] . '&amp;count=' . $countpage  . '#' . $PowerBB->reply->id;
					}

		     		if (!isset($PowerBB->_POST['ajax']))
		     		{
						if (($PowerBB->_CONF['member_row']['review_reply'] or $PowerBB->_CONF['group_info']['review_reply'])
						and !$PowerBB->functions->ModeratorCheck($this->SectionInfo['id']))
						{
						    $PowerBB->functions->ShowHeader($PowerBB->_CONF['template']['_CONF']['lang']['execution_add_reply']);
							$PowerBB->functions->AddressBar('<a href="index.php?page=forum&amp;show=1&amp;id=' . $this->SectionInfo['id'] . $PowerBB->_CONF['template']['password'] . '">' . $this->SectionInfo['title'] . '</a>' . $PowerBB->_CONF['info_row']['adress_bar_separate'] . '<a href="index.php?page=topic&amp;show=1&amp;id=' . $this->SubjectInfo['id'] . $PowerBB->_CONF['template']['password'] . '">' . ($PowerBB->functions->CleanVariable($this->SubjectInfo['title'],'sql')) . '</a>' . $PowerBB->_CONF['info_row']['adress_bar_separate'] . $PowerBB->_CONF['template']['_CONF']['lang']['execution_add_reply']);
							$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['reply_Add_successfully1'] .'   "' . $PowerBB->functions->CleanVariable($PowerBB->_POST['title'],'sql') . '"  ' .$PowerBB->_CONF['template']['_CONF']['lang']['Waiting_approved_by_management']);
                            $PowerBB->functions->GetFooter();
						    $PowerBB->functions->redirect('index.php?page=forum&amp;show=1&amp;id=' . $this->SectionInfo['id'],'8');
						}
		     			else
		     			{
						   $PowerBB->functions->header_redirect($header_redirect);
	                    }
		            }
		     		else
		     		{
		     			$GetArr 			= 	array();
		     			$GetArr['where'] 	= 	array('id',$PowerBB->reply->id);

		     			$PowerBB->_CONF['template']['Info'] = $PowerBB->reply->GetReplyInfo($GetArr);

						$MemberArr 			= 	array();
						$MemberArr['where'] 	= 	array('username',$PowerBB->_CONF['template']['Info']['writer']);

						$PowerBB->_CONF['template']['ReplierInfo'] = $PowerBB->core->GetInfo($MemberArr,'member');

		     			$PowerBB->_CONF['template']['ReplierInfo']['id'] 				= 	$PowerBB->_CONF['member_row']['id'];
		     			$PowerBB->_CONF['template']['ReplierInfo']['username'] 			= 	$PowerBB->_CONF['member_row']['username'];
		     			$PowerBB->_CONF['template']['ReplierInfo']['avater_path'] 		= 	$PowerBB->_CONF['member_row']['avater_path'];
		     			$PowerBB->_CONF['template']['ReplierInfo']['posts'] 				= 	$PowerBB->_CONF['member_row']['posts'];
		     			$PowerBB->_CONF['template']['ReplierInfo']['user_country'] 		= 	$PowerBB->_CONF['member_row']['user_country'];
		     			$PowerBB->_CONF['template']['ReplierInfo']['visitor'] 			= 	$PowerBB->_CONF['member_row']['visitor'];
		     			$PowerBB->_CONF['template']['ReplierInfo']['away'] 				= 	$PowerBB->_CONF['member_row']['away'];
		     			$PowerBB->_CONF['template']['ReplierInfo']['away_msg'] 			= 	$PowerBB->_CONF['member_row']['away_msg'];
		     			$PowerBB->_CONF['template']['ReplierInfo']['register_date'] 		= 	$PowerBB->functions->year_date($PowerBB->_CONF['member_row']['register_date']);
		     			$PowerBB->_CONF['template']['ReplierInfo']['user_title'] 		= 	$PowerBB->_CONF['member_row']['user_title'];
		     			$PowerBB->_CONF['template']['ReplierInfo']['reply_id'] 		= 	$PowerBB->reply->id;
		     			$PowerBB->_CONF['template']['Info']['reply_id'] 		= 	$PowerBB->reply->id;
		     			$PowerBB->_CONF['template']['ReplierInfo']['user_gender'] 		= 	$PowerBB->_CONF['member_row']['user_gender'];
		     			$PowerBB->_CONF['template']['ReplierInfo']['bday_day'] 		= 	$PowerBB->_CONF['member_row']['bday_day'];
		     			$PowerBB->_CONF['template']['ReplierInfo']['bday_month'] 		= 	$PowerBB->_CONF['member_row']['bday_month'];
		     			$PowerBB->_CONF['template']['ReplierInfo']['bday_year'] 		= 	$PowerBB->_CONF['member_row']['bday_year'];
		     			$PowerBB->_CONF['template']['ReplierInfo']['invite_num'] 		    = 	$PowerBB->_CONF['member_row']['invite_num'];
		     			$PowerBB->_CONF['template']['ReplierInfo']['warnings'] 		= 	$PowerBB->_CONF['member_row']['warnings'];
                        $PowerBB->_CONF['template']['Info']['show_list_last_5_posts_member'] = '0';
                        $PowerBB->_CONF['info_row']['show_list_last_5_posts_member']= '0';
		     			// Make register date in nice format to show it
						if (is_numeric($PowerBB->_CONF['template']['ReplierInfo']['register_date']))
						{
							$PowerBB->_CONF['template']['ReplierInfo']['register_date'] = $PowerBB->functions->_date($PowerBB->_CONF['template']['ReplierInfo']['register_date']);
						}
						       $cache = json_decode(base64_decode($PowerBB->_CONF['member_row']['style_cache']), true);
                               $image_path = $PowerBB->_CONF['rows']['style']['image_path'];

						// Make member gender as a readable text
						$CheckOnline = ($PowerBB->_CONF['member_row']['logged'] < $PowerBB->_CONF['timeout']) ? false : true;

                     	($CheckOnline) ? $PowerBB->template->assign('user_online',true) : $PowerBB->template->assign('user_online',false);


						if (empty($PowerBB->_CONF['member_row']['username_style_cache']))
						{
							$PowerBB->_CONF['template']['ReplierInfo']['display_username'] = $PowerBB->_CONF['member_row']['username'];
						}
						else
						{
							$PowerBB->_CONF['template']['ReplierInfo']['display_username'] = $PowerBB->_CONF['member_row']['username_style_cache'];

							$PowerBB->_CONF['template']['ReplierInfo']['display_username'] = $PowerBB->functions->CleanVariable($PowerBB->_CONF['template']['ReplierInfo']['display_username'],'unhtml');
						}

						$PowerBB->_CONF['template']['Info']['text'] = $PowerBB->Powerparse->replace($PowerBB->_CONF['template']['Info']['text']);
						// Convert the smiles to image
						$PowerBB->Powerparse->replace_smiles($PowerBB->_CONF['template']['Info']['text']);
                        $PowerBB->_CONF['template']['Info']['text'] = $PowerBB->Powerparse->censor_words($PowerBB->_CONF['template']['Info']['text']);
						// Member signture is not empty , show make it nice with PowerCode
						if (!empty($PowerBB->_CONF['member_row']['user_sig']))
						{
							$PowerBB->_CONF['template']['ReplierInfo']['user_sig'] = $PowerBB->Powerparse->replace($PowerBB->_CONF['member_row']['user_sig']);

							$PowerBB->Powerparse->replace_smiles($PowerBB->_CONF['template']['ReplierInfo']['user_sig']);
						}

						$reply_date = $PowerBB->functions->_date($PowerBB->_CONF['template']['Info']['write_time']);
						$reply_time = $PowerBB->functions->_time($PowerBB->_CONF['template']['Info']['write_time']);
                       	$PowerBB->_CONF['template']['ReplierInfo']['usergroup'] = $PowerBB->_CONF['member_row']['usergroup'];
                        $PowerBB->template->assign('subject_title',$PowerBB->_CONF['template']['Info']['title']);
                        $PowerBB->_CONF['template']['Info']['write_time'] = $reply_date;
                        $PowerBB->_CONF['template']['Info']['icon'] = "look/images/icons/i1.gif";
                        $PowerBB->_CONF['template']['reply_number'] = $PagerReplyNumArr+1;
                        $PowerBB->_CONF['template']['Info']['reply_number'] = $PagerReplyNumArr+1;
                        $PowerBB->_CONF['template']['Info']['subject_id'] = $PowerBB->_GET['id'];
                        $PowerBB->_CONF['template']['Awards_nm'] = '0';
                        $PowerBB->_CONF['template']['SubjectInfo'] = $this->SubjectInfo;
                        $PowerBB->template->assign('timeout',true);
                       $PowerBB->template->assign('id',$PowerBB->_GET['id']);
                       $PowerBB->template->assign('password',$this->SectionInfo['section_password']);
                       $PowerBB->template->assign('count_peg',$PowerBB->_GET['count']);
                       $PowerBB->template->assign('subject','0');
                        $PowerBB->template->assign('ajax','1');

							if ($PowerBB->_CONF['template']['Info']['review_reply'])
							{
						      $PowerBB->template->assign('class_reply','tbar_review');
							}
							else
							{
					          $PowerBB->template->assign('class_reply','tbar_writer_info');
							}

					       if ($PowerBB->functions->ModeratorCheck($this->SectionInfo['moderators']))
							{
								$PowerBB->template->assign('mod_toolbar',0);
							}
							else
							{
								$PowerBB->template->assign('mod_toolbar',1);


						       if ($PowerBB->_CONF['group_info']['edit_own_reply']== '1')
						       {
						        $PowerBB->_CONF['template']['_CONF']['group_info']['edit_own_reply'] = $PowerBB->functions->section_group_permission($this->SectionInfo['id'],$PowerBB->_CONF['group_info']['id'],'edit_own_reply');
						       }
						       if ($PowerBB->_CONF['group_info']['del_own_reply']== '1')
						       {
						        $PowerBB->_CONF['template']['_CONF']['group_info']['del_own_reply'] = $PowerBB->functions->section_group_permission($this->SectionInfo['id'],$PowerBB->_CONF['group_info']['id'],'del_own_reply');
						       }

							}



							//get user rating
								$ratings = $PowerBB->userrating->GetCachedRatings();
					            $y = @sizeof($ratings);
								for ($b = 0; $b <= $y; $b++)
								{
									if($ratings[$y-1]['posts'] < $PowerBB->_CONF['member_row']['posts'])
									{
									$user_ratings = $ratings[$y-1]['rating'];
									$user_posts = $ratings[$y-1]['posts'];
									break;
									}
									if($ratings[$b]['posts'] > $PowerBB->_CONF['member_row']['posts'])
									{
									$user_ratings = $ratings[$b]['rating'];
									$user_posts = $ratings[$b]['posts'];
									break;
									}
									if($this->Info['posts'] < $ratings[1]['posts'])
									{
									$user_ratings = $ratings[1]['rating'];
									$user_posts = $ratings[1]['posts'];
									break;
									}
								}

								 $PowerBB->_CONF['template']['RatingInfo']['rating'] = $user_ratings;
								 $PowerBB->_CONF['template']['RatingInfo']['posts'] = $user_posts;
					          /////////

                        if (($PowerBB->_CONF['member_row']['review_reply'] or $PowerBB->_CONF['group_info']['review_reply'])
						and !$PowerBB->functions->ModeratorCheck($this->SectionInfo['id']))
						{
							$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['reply_Add_successfully1'] .'   "' . $PowerBB->functions->CleanVariable($PowerBB->_POST['title'],'sql') . '" <br /> ' .$PowerBB->_CONF['template']['_CONF']['lang']['review_reply']);
						}
		     			else
		     			{
		     			 if($ajax_last_replier)
							{
						      $PowerBB->template->assign('ajax_last_replier',true);
						      $header_redirect = $PowerBB->functions->rewriterule($header_redirect);
						      $header_redirect = str_replace('&amp;','&',$header_redirect);
						      $PowerBB->template->assign('location',$PowerBB->functions->GetForumAdress().$header_redirect);
							}
							else
							{
						      $PowerBB->template->assign('ajax_last_replier',false);
							}

                           $PowerBB->template->display('show_reply');




                        }

                  }
		     	}
     	}
	}
}


?>