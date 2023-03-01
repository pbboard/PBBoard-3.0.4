<?php
session_start();
(!defined('IN_PowerBB')) ? die() : '';
include('common.php');
define('CLASS_NAME','PowerBBTopicAddMOD');
class PowerBBTopicAddMOD
{
	var $SectionInfo;
	var $SectionGroup;

	function run()
	{
		global $PowerBB;

		$this->_CommonCode();

		if ($PowerBB->_GET['index'])
		{
			$this->_Index();
		   $PowerBB->functions->GetFooter();
		}
		elseif ($PowerBB->_GET['start'])
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

		//////////
       	if ($PowerBB->_CONF['info_row']['MySBB_version'] == "3.0.0"
       	or $PowerBB->_CONF['info_row']['MySBB_version'] == "3.0.1")
        {

		$fileJsvk_popup = "look/ckeditor/plugins/Jsvk/jscripts/vk_popup.html";
		$fileJsvk_iframe = "look/ckeditor/plugins/Jsvk/jscripts/vk_iframe.html";
		$filewsc_ciframe = "look/ckeditor/plugins/wsc/dialogs/ciframe.html";
		$filewsc_tmpFrameset = "look/ckeditor/plugins/wsc/dialogs/tmpFrameset.html";
		$filecke_preview = "look/ckeditor/plugins/preview/preview.html";
		if (file_exists($fileJsvk_popup))
		{
		$delJsvkpopup = @unlink($fileJsvk_popup);
		}
		if (file_exists($fileJsvk_iframe))
		{
		$delJsvkiframe = @unlink($fileJsvk_iframe);
		}
		if (file_exists($filewsc_ciframe))
		{
		$delciframe = @unlink($filewsc_ciframe);
		}
		if (file_exists($filewsc_tmpFrameset))
		{
		$deltmpFrameset = @unlink($filewsc_tmpFrameset);
		}
		if (file_exists($filecke_preview))
		{
		$delpreview = @unlink($filecke_preview);
		}

	   }
		$PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');

		if (empty($PowerBB->_GET['id']))
		{
			$PowerBB->functions->ShowHeader($PowerBB->_CONF['template']['_CONF']['lang']['Mistake']);

			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}

		//////////

		$SecArr 			= 	array();
		$SecArr['where'] 	= 	array('id',$PowerBB->_GET['id']);

		$this->SectionInfo = $PowerBB->core->GetInfo($SecArr,'section');

		if (!$this->SectionInfo)
		{
			$PowerBB->functions->ShowHeader($PowerBB->_CONF['template']['_CONF']['lang']['Mistake']);

			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Section_does_not_exist']);
		}

		// Kill XSS
		$PowerBB->functions->CleanVariable($this->SectionInfo,'html');
		// Kill SQL Injection
		$PowerBB->functions->CleanVariable($this->SectionInfo,'sql');

		//////////

		// Finally get the permissions of group
		//////////
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

	           // The visitor can't show this section , so stop the page
	           if ($PowerBB->functions->section_group_permission($this->SectionInfo['id'],$PowerBB->_CONF['group_info']['id'],'view_section') == 0
	           or $PowerBB->functions->section_group_permission($this->SectionInfo['id'],$PowerBB->_CONF['group_info']['id'],'write_subject') == 0)
				{
					$PowerBB->functions->ShowHeader($PowerBB->_CONF['template']['_CONF']['lang']['Mistake']);
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

			}
	       else
			{

				/** Get section's group information and make some checks **/
				$SecGroupArr 						= 	array();
				$SecGroupArr['where'] 				= 	array();

				$SecGroupArr['where'][0]			=	array();
				$SecGroupArr['where'][0]['name']	=	'section_id = '.$this->SectionInfo['id'].' AND group_id';
				$SecGroupArr['where'][0]['oper']	=	'=';
				$SecGroupArr['where'][0]['value']	=	$PowerBB->_CONF['group_info']['id'];
				$this->SectionGroup = $PowerBB->group->GetSectionGroupInfo($SecGroupArr);
				// The visitor can't show this section , so stop the page
				if ($this->SectionGroup['view_section'] == 0
					or $this->SectionGroup['write_subject'] == 0)
				{
					$PowerBB->functions->ShowHeader($PowerBB->_CONF['template']['_CONF']['lang']['Mistake']);
		          if ($PowerBB->_CONF['member_permission'])
		            {
 			          $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Sorry_you_can_not_write_in_this_section']);
 					}
			        else
			        {		              $PowerBB->template->display('login');
		              $PowerBB->functions->error_stop();
			        }
			     }

			}

				// Instead of send a whole version of $this->SectionGroup to template engine
				// We just send options which we really need, we use this way to save memory
				$PowerBB->template->assign('upload_attach',$this->SectionGroup['upload_attach']);
				$PowerBB->template->assign('write_poll',$this->SectionGroup['write_poll']);



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


        if (empty($PowerBB->_COOKIE['pbb_sec'.$this->SectionInfo['id'].'_pass']))
         {
           @ob_start();
		   setcookie("pbb_sec".$this->Section['id']."_pass","");

			if (!empty($this->SectionInfo['section_password'])
				and !$PowerBB->_CONF['group_info']['admincp_allow']
				and !$PowerBB->functions->ModeratorCheck($this->SectionInfo['id']))
			{
				// The visitor don't give me password , so require it
	     		if (empty($PowerBB->_GET['password']))
	        	{
	               $PowerBB->functions->ShowHeader($PowerBB->_CONF['template']['_CONF']['lang']['password_forum']);
	               $PowerBB->template->assign('section_info',$this->SectionInfo);

	      			$PowerBB->template->display('forum_password');
	      			$PowerBB->functions->stop();
	     		}
	     		// The visitor give me password , so check
	     		elseif (!empty($PowerBB->_GET['password']))
	     		{
	     			$PassArr = array();

	     			// Section id
	     			$PassArr['id'] 	= $this->SectionInfo['id'];

	     			// The password to check
	     			$PassArr['password'] = base64_decode($PowerBB->_GET['password']);

	     			$IsTruePassword = $PowerBB->section->CheckPassword($PassArr);

	     			// Stop ! it's don't true password
	     			if (!$IsTruePassword)
	     			{
	     				$PowerBB->functions->ShowHeader($PowerBB->_CONF['template']['_CONF']['lang']['Mistake']);

	     				$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['PasswordIsnotTrue']);
	     			}

	     			$PowerBB->_CONF['template']['password'] = '&amp;password=' . $PowerBB->_GET['password'];
	     		}
	     	}
       }
     	//////////
	if ($PowerBB->_CONF['group_info']['topic_day_number']>0)
		{
        // gett user topic day number
			$day 	= 	date('j');
			$month 	= 	date('n');
			$year 	= 	date('Y');

			$from 	= 	mktime(0,0,0,$month,$day,$year);
			$to 	= 	mktime(23,59,59,$month,$day,$year);

            $user = $PowerBB->_CONF['rows']['member_row']['username'];
	        $user_topic_day_number = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT ID FROM " . $PowerBB->table['subject'] . " WHERE native_write_time BETWEEN " . $from . " AND " . $to . " AND writer = '$user' "));
	        if ($user_topic_day_number>= $PowerBB->_CONF['group_info']['topic_day_number'])
	        {
		        $PowerBB->_CONF['template']['_CONF']['lang']['sorry_can_not_add_topic_more_than_in_day'] = str_replace("{topic_day}",$PowerBB->_CONF['group_info']['topic_day_number'],$PowerBB->_CONF['template']['_CONF']['lang']['sorry_can_not_add_topic_more_than_in_day']);
		        $PowerBB->functions->ShowHeader();
	          	$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['sorry_can_not_add_topic_more_than_in_day']);
	        }

      }
     	$PowerBB->template->assign('section_info',$this->SectionInfo);

     	//////////
	}

	function _preview()
	{
		global $PowerBB;

		$PowerBB->functions->GetEditorTools();

     	$PowerBB->template->assign('id',$PowerBB->_GET['id']);


		////////

		$Admin = $PowerBB->functions->ModeratorCheck($PowerBB->_GET['id']);

		$PowerBB->template->assign('Admin',$Admin);

		////////
        $ExArr 						= 	array();
		$ExArr['order']				=	array();
		$ExArr['order']['field']	=	'id';
		$ExArr['order']['type']		=	'DESC';
		$ExArr['proc'] 				= 	array();
		$ExArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');

		$PowerBB->_CONF['template']['while']['ExList'] = $PowerBB->core->GetList($ExArr,'ex');

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

     	$PowerBB->template->display('new_topic');
	}

	function _Index()
	{
		global $PowerBB;

		$PowerBB->functions->ShowHeader();

		$PowerBB->functions->GetEditorTools();

     	$PowerBB->template->assign('id',$PowerBB->_GET['id']);

       eval($PowerBB->functions->get_fetch_hooks('new_topic_Index'));
		////////

		$Admin = $PowerBB->functions->ModeratorCheck($PowerBB->_GET['id']);

		$PowerBB->template->assign('Admin',$Admin);

		////////
        $ExArr 						= 	array();
		$ExArr['order']				=	array();
		$ExArr['order']['field']	=	'id';
		$ExArr['order']['type']		=	'DESC';
		$ExArr['proc'] 				= 	array();
		$ExArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');

		$PowerBB->_CONF['template']['while']['ExList'] = $PowerBB->core->GetList($ExArr,'ex');
		$SecInfoArr 			= 	array();
		$SecInfoArr['where'] 	= 	array('id',$PowerBB->_GET['id']);

		$section_info = $PowerBB->core->GetInfo($SecInfoArr,'section');
		if ($section_info['parent'] == '0')
        {       	$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['no_newthread_in_section_main']);
	    }

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

			$AttachArr 							= 	array();
			$AttachArr['where']					= 	array();
			$AttachArr['where'][0] 				=	array();
			$AttachArr['where'][0]['name'] 		=	'subject_id';
			$AttachArr['where'][0]['oper'] 		=	'=';
			$AttachArr['where'][0]['value'] 	=	-1;
			$AttachArr['where'][1] 				=	array();
			$AttachArr['where'][1]['con']		=	'AND';
			$AttachArr['where'][1]['name'] 		=	'u_id';
			$AttachArr['where'][1]['oper'] 		=	'=';
			$AttachArr['where'][1]['value'] 	=	$PowerBB->_CONF['member_row']['id'];
			$AttachArr['order'] 				=	 array();
			$AttachArr['order']['field'] 		= 	'id';
			$AttachArr['order']['type'] 	    = 	'DESC';

			$PowerBB->_CONF['template']['while']['AttachList'] = $PowerBB->core->GetList($AttachArr,'attach');

     	$PowerBB->template->display('new_topic');

	}

	function _empty_bac()
	{
		global $PowerBB;

		$PowerBB->functions->GetEditorTools();

     	$PowerBB->template->assign('id',$PowerBB->_GET['id']);

		////////

		$Admin = $PowerBB->functions->ModeratorCheck($PowerBB->_GET['id']);

		$PowerBB->template->assign('Admin',$Admin);

		////////
        $ExArr 						= 	array();
		$ExArr['order']				=	array();
		$ExArr['order']['field']	=	'id';
		$ExArr['order']['type']		=	'DESC';
		$ExArr['proc'] 				= 	array();
		$ExArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');

		$PowerBB->_CONF['template']['while']['ExList'] = $PowerBB->core->GetList($ExArr,'ex');
          $previewtext = $PowerBB->_POST['text'];
          $previewtext = $PowerBB->Powerparse->replace($previewtext);
          $previewtext = str_replace("\'","'",$previewtext);
          $PowerBB->Powerparse->replace_smiles($previewtext);
          $PowerBB->template->assign('preview',$previewtext);
          $PowerBB->_POST['text'] = str_replace("\'","'",$PowerBB->_POST['text']);
          $PowerBB->template->assign('prev',$PowerBB->_POST['text']);
          $PowerBB->template->assign('title_prev',$PowerBB->_POST['title']);
          $PowerBB->template->assign('describe_prev',$PowerBB->_POST['describe']);
          $PowerBB->template->assign('prefix_subject_prev',$PowerBB->_POST['prefix_subject']);
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

			$AttachArr 							= 	array();
			$AttachArr['where']					= 	array();
			$AttachArr['where'][0] 				=	array();
			$AttachArr['where'][0]['name'] 		=	'subject_id';
			$AttachArr['where'][0]['oper'] 		=	'=';
			$AttachArr['where'][0]['value'] 	=	-1;
			$AttachArr['where'][1] 				=	array();
			$AttachArr['where'][1]['con']		=	'AND';
			$AttachArr['where'][1]['name'] 		=	'u_id';
			$AttachArr['where'][1]['oper'] 		=	'=';
			$AttachArr['where'][1]['value'] 	=	$PowerBB->_CONF['member_row']['id'];
			$AttachArr['order'] 				=	 array();
			$AttachArr['order']['field'] 		= 	'id';
			$AttachArr['order']['type'] 	    = 	'DESC';

			$PowerBB->_CONF['template']['while']['AttachList'] = $PowerBB->core->GetList($AttachArr,'attach');


     	$PowerBB->template->display('new_topic');
	}


	function _Start()
	{
		global $PowerBB;

		$PowerBB->_POST['title'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['title'],'trim');
		$PowerBB->_POST['text'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['text'],'trim');

		// Kill SQL Injection
		//$PowerBB->_POST['title'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['title'],'sql');
        //$PowerBB->_POST['text'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['text'],'sql');
        //$PowerBB->_POST['describe'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['describe'],'sql');
       eval($PowerBB->functions->get_fetch_hooks('new_topic_Start'));

	   if ($PowerBB->_POST['preview'])
       {
    	    $PowerBB->functions->ShowHeader($PowerBB->_CONF['template']['_CONF']['lang']['add_new_topic']);
			$PowerBB->_POST['text'] = str_replace('target="_blank" ','',$PowerBB->_POST['text']);
			$PowerBB->template->assign('prev',$PowerBB->Powerparse->replace_htmlentities($PowerBB->_POST['text']));
			$PowerBB->_POST['text'] = $PowerBB->Powerparse->replace($PowerBB->_POST['text']);
			$PowerBB->Powerparse->replace_smiles($PowerBB->_POST['text']);
			$PowerBB->_POST['text'] = $PowerBB->Powerparse->censor_words($PowerBB->_POST['text']);

			$PowerBB->template->assign('title_prev',$PowerBB->_POST['title']);
			$PowerBB->template->assign('describe_prev',$PowerBB->_POST['describe']);
			$PowerBB->template->assign('preview',$PowerBB->_POST['text']);

			$PowerBB->template->assign('view_preview',$PowerBB->_POST['text']);
			$PowerBB->_POST['reason_edit'] = $PowerBB->Powerparse->censor_words($PowerBB->_POST['reason_edit']);
			$PowerBB->_POST['reason_edit'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['reason_edit'],'html');
			$PowerBB->_POST['reason_edit'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['reason_edit'],'sql');

			$PowerBB->template->assign('reason_edit',$PowerBB->_POST['reason_edit']);

			$PowerBB->template->assign('prefix_subject_prev',$PowerBB->_POST['prefix_subject']);;

			$this->_preview();


        }
       else
        {


				if (empty($PowerBB->_POST['title']))
				{
                   $PowerBB->functions->ShowHeader($PowerBB->_CONF['template']['_CONF']['lang']['add_new_topic']);
                    $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['no_title']);
                      $this->_empty_bac();
					$PowerBB->functions->error_stop();

				}

				if (empty($PowerBB->_POST['text']))
				{
                   $PowerBB->functions->ShowHeader($PowerBB->_CONF['template']['_CONF']['lang']['add_new_topic']);
					$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['no_text']);
				}
			 if (!$PowerBB->_CONF['group_info']['admincp_allow'])
			 {
				$writer = $PowerBB->_CONF['member_row']['username'];
                $last_subject_write_time = $PowerBB->DB->sql_query("SELECT  *   FROM " . $PowerBB->table['subject'] . " WHERE writer= '$writer' ORDER BY id desc");
                $last_write_time = $PowerBB->DB->sql_fetch_array($last_subject_write_time);
	            if ((time() - $PowerBB->_CONF['info_row']['floodctrl']) <= $last_write_time['native_write_time'])
	            {
					$PowerBB->functions->ShowHeader();
					$floodctrl = @time() - $PowerBB->_CONF['member_row']['lastpost_time'] - $PowerBB->_CONF['info_row']['floodctrl'] ;
					$PowerBB->_CONF['template']['_CONF']['lang']['floodctrl_subjects'] = str_replace('30', " <b>".$floodctrl."</b> ", $PowerBB->_CONF['template']['_CONF']['lang']['floodctrl_subjects']);
					$PowerBB->_CONF['template']['_CONF']['lang']['floodctrl_subjects'] = str_replace("-", "", $PowerBB->_CONF['template']['_CONF']['lang']['floodctrl_subjects']);
					$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['floodctrl_subjects'],$stop,$stop);
					$this->_empty_bac();
					$PowerBB->functions->error_stop();
				}

				if ($last_write_time['title'] == $PowerBB->_POST['title'])
				{
                   $PowerBB->functions->ShowHeader($PowerBB->_CONF['template']['_CONF']['lang']['add_new_topic']);
   			        $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['duplicatethread']);
		     	   // $PowerBB->functions->header_redirect('index.php?page=forum&amp;show=1&amp;id=' . $this->SectionInfo['id'] . $PowerBB->_CONF['template']['password'],'8');
		     		$PowerBB->functions->error_stop();
				}
             }
			// ADD poll
         if ($PowerBB->_POST['poll'])
         {
                // Filter Words
                  $PowerBB->_POST['question'] = $PowerBB->functions->CleanVariable($PowerBB->_POST['question'],'html');
                 // $PowerBB->_POST['question'] = $PowerBB->functions->CleanVariable($PowerBB->_POST['question'],'sql');
            $question = utf8_decode($PowerBB->_POST['question']);
            $question = preg_replace('/\s+/', '', $question);

              if (empty($question))
             {
             	$PowerBB->functions->ShowHeader();
                $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['fill_in_question']);
                 $PowerBB->template->assign('question',$PowerBB->_POST['question']);
                    $this->_empty_bac();
                    $PowerBB->functions->error_stop();
              }

                    if (isset($PowerBB->_POST['question'])
                        and isset($PowerBB->_POST['answer'][0])
                        and isset($PowerBB->_POST['answer'][1]))
                    {
                        $answers_number = 2;

                        if ($PowerBB->_POST['poll_answers_count'] > 0)
                        {
                           $answers_number = $PowerBB->_POST['poll_answers_count'];
                        }

                        $answers = array();

                        $x = 0;

                        while ($x < $answers_number)
                        {
                           // The text of the answer
                           $answers[$x][0] = $PowerBB->_POST['answer'][$x];
                        $PowerBB->functions->CleanVariable($PowerBB->_POST['answer'][$x],'html');
                        $PowerBB->functions->CleanVariable($PowerBB->_POST['answer'][$x],'sql');
						$PowerBB->_POST['answer'][$x] = $PowerBB->functions->CleanVariable($PowerBB->_POST['answer'][$x],'sql');

				            $answersss = utf8_decode($PowerBB->_POST['answer'][$x]);
				            $answersss = preg_replace('/\s+/', '', $answersss);
							if (empty($answersss))
							{
							$PowerBB->functions->ShowHeader();
							$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['fill_in_answer']);
							}

							if(strlen($answersss) >= "1")
							{
							// Continue
							}
							else
							{
							 $PowerBB->functions->ShowHeader();
							 $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['fill_in_answer']);
							}
                           // The result
                           $answers[$x][1] = 0;

                           $x += 1;
                        }

                    }

           }



					/*$IsFlood = $PowerBB->subject->IsFlood(array('last_time'=>$PowerBB->_CONF['member_row']['lastpost_time']));

					if ($IsFlood)
					{
						$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['floodctrl_subjects']);
					}*/
                    $TitlePost = utf8_decode($PowerBB->_POST['title']);
      				$Post_max_num = strlen($TitlePost) <= $PowerBB->_CONF['info_row']['post_title_max'];
		     		if ($Post_max_num)
		     		{                     // Continue
		    		}
					else
					{
						$PowerBB->functions->ShowHeader($PowerBB->_CONF['template']['_CONF']['lang']['add_new_topic']);
						$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['post_text_max_subjects']);
						$this->_empty_bac();
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
                        $PowerBB->functions->ShowHeader($PowerBB->_CONF['template']['_CONF']['lang']['add_new_topic']);
                        $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['post_text_min_subjects']);
                         $this->_empty_bac();
		      			$PowerBB->functions->error_stop();
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
					$this->_empty_bac();
					$PowerBB->functions->error_stop();
					}

		           $TextPost = preg_replace('/\s+/', '', $TextPost);
		        	if(strlen($TextPost) >= $PowerBB->_CONF['info_row']['post_text_min'])
		     		{
                     // Continue
		     		}
					else
					{
					$PowerBB->functions->ShowHeader($PowerBB->_CONF['template']['_CONF']['lang']['add_new_topic']);
					$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['post_text_min']);
					$this->_empty_bac();
					$PowerBB->functions->error_stop();
					}

             	if (!$PowerBB->_CONF['group_info']['admincp_allow'])
				{
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
                   // Filter Words
				$PowerBB->_POST['text'] = str_replace('target="_blank" ','',$PowerBB->_POST['text']);
				$PowerBB->_POST['title'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['title'],'sql');
				//$PowerBB->_POST['text'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['text'],'sql');
                   //


		$SecInfoArr 			= 	array();
		$SecInfoArr['where'] 	= 	array('id',$this->SectionInfo['id']);

		$section_info = $PowerBB->core->GetInfo($SecInfoArr,'section');
		       //Create last writer cache:  id & avater_path & username_style_cache
				$cache = array();
				$cache[1]['user_id']		 	                = 	$PowerBB->_CONF['rows']['member_row']['id'];
				$cache[2]['avater_path']		 	            = 	$PowerBB->_CONF['rows']['member_row']['avater_path'];
				$cache[3]['username_style']		 	            = 	$PowerBB->_CONF['rows']['member_row']['username_style_cache'];
				$cache[4]['section_title']		 	            = 	$this->SectionInfo['title'];

				$cache = serialize($cache);

		     	$SubjectArr 								= 	array();
		     	$SubjectArr['get_id']						=	true;
		     	$SubjectArr['field']						=	array();
		     	$SubjectArr['field']['title'] 				= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['title'],'html');
		     	if (!$PowerBB->_CONF['member_permission'])
				{		     	$SubjectArr['field']['text'] 			= 	$PowerBB->functions->CleanVariable('[color=#4000BF][i][guest_name]'.$PowerBB->_CONF['template']['_CONF']['lang']['LastsPostsWriter'].$PowerBB->_POST['guest_name'].'[/guest_name][/i][/color]<br />'.$PowerBB->_POST['text'],'nohtml');
				$SubjectArr['field']['writer'] 				= 	'Guest';
				}
		     	else
		     	{
		     	$SubjectArr['field']['text'] 				= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['text'],'nohtml');
				$SubjectArr['field']['writer'] 				= 	$PowerBB->_CONF['rows']['member_row']['username'];
				}
		     	$SubjectArr['field']['section'] 			= 	$this->SectionInfo['id'];
		     	$SubjectArr['field']['write_time'] 			= 	$PowerBB->_CONF['now'];
		     	$SubjectArr['field']['icon'] 				= 	$PowerBB->_POST['icon'];
		     	$SubjectArr['field']['subject_describe'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['describe'],'html');
		     	$SubjectArr['field']['native_write_time'] 	= 	$PowerBB->_CONF['now'];
		     	$SubjectArr['field']['sec_subject'] 		= 	$section_info['sec_section'];
		     	$SubjectArr['field']['lastreply_cache'] 	= 	$cache;
		     	$SubjectArr['field']['prefix_subject'] 		= 	$PowerBB->_POST['prefix_subject'];
		     	if ($PowerBB->_POST['poll'])
		     	{
		     	$SubjectArr['field']['poll_subject'] 		= 	1;
		     	}
		     	$SubjectArr['field']['attach_subject'] 		= 	0;
		     	$SubjectArr['field']['tags_cache']			=	$PowerBB->functions->CleanVariable($PowerBB->_POST['tags'],'sql');

		     	if (($PowerBB->_CONF['member_row']['review_subject'] or $PowerBB->_CONF['group_info']['review_subject'] or $this->SectionInfo['review_subject'])
					and !$PowerBB->functions->ModeratorCheck($PowerBB->_GET['id']))
				{
					$SubjectArr['field']['review_subject'] = 1;
				}


		     	if ($PowerBB->_POST['stick'])
		     	{
		     		$SubjectArr['field']['stick'] = 1;
		     	}

		     	if ($PowerBB->_POST['close'])
		     	{
		     		$SubjectArr['field']['close'] = 1;
		     	}

		     	$Insert = $PowerBB->subject->InsertSubject($SubjectArr);


		     	if ($Insert)
		     	{
		     		//////////

		     		if ($PowerBB->_POST['poll'])
		     		{

		     			if (isset($PowerBB->_POST['question'])
		     				and isset($PowerBB->_POST['answer'][0])
		     				and isset($PowerBB->_POST['answer'][1]))
		     			{
		     				$answers_number = 2;

		     				if ($PowerBB->_POST['poll_answers_count'] > 0)
		     				{
		     					$answers_number = $PowerBB->_POST['poll_answers_count'];
		     				}

								$PollArr 				= 	array();
								$PollArr['field']	=	array();
								$PollArr['field']['qus'] 	    = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['question'],'html');
								$PollArr['field']['answers'] 	= 	$PowerBB->_POST['answer'];
								$PollArr['field']['subject_id']	=	$PowerBB->subject->id;
								$InsertPoll = $PowerBB->poll->InsertPoll($PollArr);


		     			}
		     		}


				    //////////

		     		// Set tags for the subject
                       //$PowerBB->_POST['tags'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['tags'],'sql');
		     		$tags_size = sizeof($PowerBB->_POST['tags']);

		     		if ($tags_size > 0
		     			and strlen($PowerBB->_POST['tags'][0]) > 0)
		     		{
		     			foreach ($PowerBB->_POST['tags'] as $tag)
		     			{		     				if (!empty($tag))
		     				{		                            $tag 	= 	$PowerBB->functions->CleanVariable($tag,'sql');
		                            if (function_exists('mb_strlen'))
		                            {
		                                $tag_less_num = mb_strlen($tag, 'UTF-8') >= 4;
		                            }
		                            else
		                            {
		                                $tag_less_num = strlen(utf8_decode($tag)) >= 4;
		                            }
		                           if($tag_less_num)
		                           {
					     				$CheckArr 			= 	array();
					     				$CheckArr['where'] 	= 	array('tag',$tag);

					     				$tag_id = 1;

					     				$Tag = $PowerBB->tag->GetTagInfo($CheckArr);

					     				if (!$Tag)
					     				{
					     					$InsertArr 					=	array();
					     					$InsertArr['field']			=	array();
					     					$InsertArr['field']['tag']	=	$tag;
					     					$InsertArr['get_id']		=	true;

					     					$insert = $PowerBB->tag->InsertTag($InsertArr);

					     					$tag_id = $PowerBB->tag->id;

					     					unset($InsertArr);
					     				}
					     				else
					     				{
					     					$UpdateArr 			= 	array();
					     					$UpdateArr['field']	=	array();

					     					$UpdateArr['field']['number'] 	= 	$Tag['num'] + 1;
					     					$UpdateArr['where']				=	array('id',$Tag['id']);

					     					$update = $PowerBB->tag->UpdateTag($UpdateArr);

					     					$tag_id = $Tag['id'];
					     				}

					     				$InsertArr 						= 	array();
					     				$InsertArr['field']				=	array();

					     				$InsertArr['field']['tag_id'] 			= 	$tag_id;
					     				$InsertArr['field']['subject_id'] 		=	$PowerBB->subject->id;
					     				$InsertArr['field']['tag'] 				= 	$PowerBB->functions->CleanVariable($tag,'html');
					     				$InsertArr['field']['subject_title'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['title'],'html');

					     				// Note, this function is from tag system not subject system
					     				$insert = $PowerBB->tag->InsertSubject($InsertArr);
		                             }
		                    }
		     			}
		     		}

                    if ($PowerBB->_CONF['info_row']['add_tags_automatic'] == '1')
		     		{
                        //add tags Automatic from subject title
						$excludedWords = array();
						$doubleAr = $PowerBB->functions->CleanVariable($PowerBB->_POST['title'],'html');
						$censorwords = preg_split('/\s+/s', $doubleAr, -1, PREG_SPLIT_NO_EMPTY);
						$excludedWords = array_merge($excludedWords, $censorwords);
						unset($censorwords);

						// Trim current exclusions
						for ($x = 0; $x < count($excludedWords); $x++)
						{
						$excludedWords[$x] = trim($excludedWords[$x]);

                            if (function_exists('mb_strlen'))
                            {
                                $tag_less_num = mb_strlen($excludedWords[$x], 'UTF-8') >= 4;
                            }
                            else
                            {
                                $tag_less_num = strlen(utf8_decode($excludedWords[$x])) >= 4;
                            }

                           if($tag_less_num)
						  {
								$Insert2Arr 					=	array();
								$Insert2Arr['field']			=	array();
								$Insert2Arr['field']['tag']	=	$excludedWords[$x];
								$Insert2Arr['get_id']		=	true;

								$insert2 = $PowerBB->tag->InsertTag($Insert2Arr);

								$tag_id = $PowerBB->tag->id;

								unset($InsertArr);
		                        $excludedWords[$x] 	= 	$excludedWords[$x];

								$InsertArr 						= 	array();
								$InsertArr['field']				=	array();

						     	$InsertArr['field']['tag_id'] 			= 	$tag_id;
								$InsertArr['field']['subject_id'] 		=	$PowerBB->subject->id;
								$InsertArr['field']['tag'] 				= 	$PowerBB->functions->CleanVariable($excludedWords[$x],'html');
								$InsertArr['field']['subject_title'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['title'],'html');
								// Note, this function is from tag system not subject system
								$insert = $PowerBB->tag->InsertSubject($InsertArr);
                          }
						}
                    }
					//////////

		     		// Upload files
                  if ($PowerBB->_CONF['member_permission'])
				 {
						   $GetAttachArr 					= 	array();
						   $GetAttachArr['where'] 			= 	array('subject_id','-'.$PowerBB->_CONF['member_row']['id']);
						   $Attachinfo = $PowerBB->core->GetInfo($GetAttachArr,'attach');

				     		if ($Attachinfo)
				     		{
								$SubjectArr 							= 	array();
								$SubjectArr['field'] 					= 	array();
								$SubjectArr['field']['attach_subject'] 	= 	'1';
								$SubjectArr['where'] 					= 	array('id',$PowerBB->subject->id);

								$update = $PowerBB->subject->UpdateSubject($SubjectArr);

							//	Update All Attach
							 $member_id_Attach = '-'.$PowerBB->_CONF['member_row']['id'];
		                     $getAttach = $PowerBB->DB->sql_query("SELECT  *   FROM " . $PowerBB->table['attach'] . " WHERE subject_id = '$member_id_Attach' ");
		                     while ($getAttach_row = $PowerBB->DB->sql_fetch_array($getAttach))
		                      {
								// Count a new download
								$UpdateArr 						= 	array();
								$UpdateArr['field'] 			= 	array();
								$UpdateArr['field']['subject_id'] 	= 	$PowerBB->subject->id;
								$UpdateArr['field']['time'] 	= 	$PowerBB->_CONF['now'];
								$UpdateArr['where'] 			= 	array('id',$getAttach_row['id']);

		                 		$update = $PowerBB->core->Update($UpdateArr,'attach');
		                     }
				     		}

				     		//////////

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

				     			$UserTitle = $PowerBB->core->GetInfo($UsertitleArr,'usertitle');

				     			if ($UserTitle != false)
				     			{
				     				$usertitle = $UserTitle['usertitle'];
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


				     		// The overall number of subjects
				     		$UpdateSubjectNumber = $PowerBB->cache->UpdateSubjectNumber(array('subject_num'	=>	$PowerBB->_CONF['info_row']['subject_number']));

				     		//////////
				     		if (!$PowerBB->_CONF['member_permission'])
							{
			                $writer = 	$PowerBB->_CONF['template']['_CONF']['lang']['Guestp'];
							}
					     	else
					     	{
				            $writer = 	$PowerBB->_CONF['member_row']['username'];
							}

				     	if (($PowerBB->_CONF['member_row']['review_subject'] or $PowerBB->_CONF['group_info']['review_subject'] or $this->SectionInfo['review_subject'])
							and !$PowerBB->functions->ModeratorCheck($PowerBB->_GET['id']))
						{
							$review_subject = 1;
						}

			     		//////////
	                    //Enabled to be notified by the existence of new replies
	                    if ($PowerBB->_CONF['info_row']['allowed_emailed'] == '1')
			     		{

					     if ($PowerBB->_POST['emailed'])
					     {
					     	$EmailedArr 								= 	array();
					     	$EmailedArr['get_id']						=	true;
					     	$EmailedArr['field']						=	array();
					     	$EmailedArr['field']['user_id'] 			= 	$PowerBB->_CONF['member_row']['id'];
					     	$EmailedArr['field']['subject_id'] 			= 	$PowerBB->subject->id;
					     	$EmailedArr['field']['subject_title'] 		= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['title'],'html');

					     	$Insert = $PowerBB->core->Insert($EmailedArr,'emailed');
			             }

		               //Send email notification to all participants in this department with a new topic
	                   $SectionInfoid = $this->SectionInfo['id'];
	                    $Adress = $PowerBB->functions->GetForumAdress();
	                    $charset                =   $PowerBB->_CONF['info_row']['charset'];
		                 $PowerBB->_POST['message'] = $PowerBB->Powerparse->replace($PowerBB->_POST['message']);
		                 $starthtml = '<html dir=\"$charset\"><body>';
		                 $Endhtml = '</body></html>';
	                        $br = "\n <br>";
	                        $br = $PowerBB->Powerparse->replace($br);
	                        $title = $PowerBB->_CONF['template']['_CONF']['lang']['New_Topic'] ;
				         	$Form_Massege = $PowerBB->_CONF['template']['_CONF']['lang']['Peace_be_upon_you']  . $PowerBB->_CONF['member_row']['username'].$PowerBB->_CONF['template']['_CONF']['lang']['Has_written_a_new_topic'] .
				         $PowerBB->_CONF['template']['_CONF']['lang']['Please_login_on_the_following_link_to_access_the_subject']. $Adress . 'index.php?page=topic&show=1&id=' . $PowerBB->subject->id . $PowerBB->_CONF['template']['_CONF']['lang']['greetings_Management_Forum'] . $PowerBB->_CONF['info_row']['title'] .'<br>' . $Adress . 'index.php';

	                     $getmember_query = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['emailed'] . " WHERE section_id = '$SectionInfoid'");
						if ($PowerBB->core->Is(array('where' => array('section_id',$SectionInfoid)),'emailed'))
						{

	                      while ($getmember_row = $PowerBB->DB->sql_fetch_array($getmember_query))
	                      {
	                       		$MemArr 			= 	array();
								$MemArr['where'] 	= 	array('id',$getmember_row['user_id']);

								$MemInfo = $PowerBB->core->GetInfo($MemArr,'member');

	                            $username = $PowerBB->_CONF['template']['_CONF']['lang']['hello_your']  . $MemInfo['username']."\n <br>";

		                       if (!$PowerBB->_CONF['member_row']['username'] == $MemInfo['username'])
							   {

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
			                        $Send = $PowerBB->functions->send_this_smtp($to,$fromname,$message,$subject,$from);

									}
		                       }


	                      }
	                    }

	                  }
                   }


		     		$LastArr = array();

		     		$LastArr['writer'] 		= 	$PowerBB->_CONF['member_row']['username'];
		     		$LastArr['title'] 		= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['title'],'html');
		     		$LastArr['subject_id'] 	= 	$PowerBB->subject->id;
		     		$LastArr['date'] 		= 	$PowerBB->_CONF['now'];
		     		$LastArr['last_time'] 		= 	$PowerBB->_CONF['now'];
		     		$LastArr['icon'] 		= 	$PowerBB->_POST['icon'];
		     		$LastArr['last_reply'] 		= 	'0';

		     		$LastArr['where'] 		= 	(!$this->SectionInfo['parent']) ? array('id',$this->SectionInfo['id']) : array('id',$this->SectionInfo['parent']);

     		     // Update Last subject's information
     		       $UpdateLast = $PowerBB->section->UpdateLastSubject($LastArr);

		        	// Update section's cache
	               $UpdateSectionCache = $PowerBB->functions->UpdateSectionCache($this->SectionInfo['id']);
                  $PowerBB->functions->PBB_Create_last_posts_cache(0);

                   /*
			     	if (($PowerBB->_CONF['member_row']['review_subject'] or $PowerBB->_CONF['group_info']['review_subject'] or $this->SectionInfo['review_subject'])
						and !$PowerBB->_CONF['group_info']['admincp_allow'])
					{
		     			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Waiting_approved_by_management']);
		     			$PowerBB->functions->redirect('index.php?page=forum&amp;show=1&amp;id=' . $this->SectionInfo['id'] . $PowerBB->_CONF['template']['password'],'8');

					}
		     		else
		     		{
			     			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['subjects_Add_successfully1'].' ' . $PowerBB->Powerparse->censor_words($subjecttitle) . ' '.$PowerBB->_CONF['template']['_CONF']['lang']['subjects_Add_successfully2']);
			     			$PowerBB->functions->redirect('index.php?page=topic&amp;show=1&amp;id=' . $PowerBB->subject->id . $PowerBB->_CONF['template']['password']);
		     		}
                     */
                    $PowerBB->functions->get_hooks('insert_subject');

                 $PowerBB->functions->header_redirect('index.php?page=topic&amp;show=1&amp;id=' . $PowerBB->subject->id . $PowerBB->_CONF['template']['password']);

		     		//////////
		     	}

        }
	}

}

?>