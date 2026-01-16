<?php
(!defined('IN_PowerBB')) ? die() : '';

define('CLASS_NAME','PowerBBCoreMOD');

include('common.php');
class PowerBBCoreMOD
{

	function run()
	{
		global $PowerBB;
       // $PowerBB->_GET['id']	= 	$PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');

		// Show header with page title
		if ($PowerBB->_GET['show'])
		{
		   $PowerBB->functions->ShowHeader($PowerBB->_CONF['template']['_CONF']['lang']['tags']);
			$this->_Show();
		}
		elseif ($PowerBB->_GET['edit'])
		{
			$this->_edit();
		}
		elseif ($PowerBB->_GET['start'])
		{
		   $PowerBB->functions->ShowHeader($PowerBB->_CONF['template']['_CONF']['lang']['tags']);
			$this->_StartEdit();
		}
		else
		{
			header("Location: index.php");
			exit;
		}
		$PowerBB->functions->GetFooter();
	}

	function _Show()
	{
		global $PowerBB;

       $PowerBB->_GET['tag'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['tag'],'trim');

	   $PowerBB->_GET['tag'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['tag'],'html');

      	if (empty($PowerBB->_GET['tag']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}
		$PowerBB->_GET['count'] = (!isset($PowerBB->_GET['count'])) ? 0 : $PowerBB->_GET['count'];
		$PowerBB->_GET['count'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['count'],'intval');

		// Where is the Visitor and user now?
		$UpdateOnline 			= 	array();
		$UpdateOnline['field']	=	array();
		$UpdateOnline['field']['user_location'] 	= $PowerBB->_CONF['template']['_CONF']['lang']['Search_results_for_tag'] .	' <a href="index.php?page=tags&show=1&tag=' . $PowerBB->_GET['tag'] . '">' . $PowerBB->_GET['tag'] . '</a>';
		if ($PowerBB->_CONF['member_permission'])
     	{
		$UpdateOnline['where']						=	array('username',$PowerBB->_CONF['member_row']['username']);
        }
        else
        {		$UpdateOnline['where']						=	array('user_ip',$PowerBB->_CONF['ip']);
        }
		$update = $PowerBB->core->Update($UpdateOnline,'online');


		$TotalArr 			= 	array();
		$TotalArr['where'] 	= 	array('tag',$PowerBB->_GET['tag']);


		// Pager setup
		$TagArr 							= 	array();
		$TagArr['where'] 					= 	array();

		$TagArr['where'][0] 				= 	array();
		$TagArr['where'][0]['name'] 		= 	'tag';
		$TagArr['where'][0]['oper'] 		= 	'=';
		$TagArr['where'][0]['value'] 		= 	$PowerBB->_GET['tag'];

		$TagArr['proc'] 			= 	array();
		$TagArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');
		$TagArr['order']			=	array();
		$TagArr['order']['field']	=	'id';
		$TagArr['order']['type']	=	'DESC';

		$TagArr['pager'] 				= 	array();
		$TagArr['pager']['total']		= 	$PowerBB->tag_subject->GetSubjectNumber($TotalArr);
		$TagArr['pager']['perpage'] 	= 	$PowerBB->_CONF['info_row']['subject_perpage']; // TODO
		$TagArr['pager']['count'] 		= 	$PowerBB->_GET['count'];
		$TagArr['pager']['location'] 	= 	'index.php?page=tags&amp;show=1&amp;tag=' . $PowerBB->_GET['tag'];
		$TagArr['pager']['var'] 		= 	'count';

		$PowerBB->_CONF['template']['while']['Subject'] = $PowerBB->tag_subject->GetSubjectList($TagArr);

		if (!$PowerBB->_CONF['template']['while']['Subject'])
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['taag_requested_does_not_exist']);
		}

		$PowerBB->template->assign('tag',$PowerBB->_GET['tag']);
		if ($PowerBB->tag_subject->GetSubjectNumber($TotalArr) > $PowerBB->_CONF['info_row']['subject_perpage'])
		{
		$PowerBB->template->assign('pager',$PowerBB->pager->show());
        }
		$PowerBB->template->display('tags_show_subject');
	}

	function _edit()
	{
		global $PowerBB;

	   $PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');

      	if (empty($PowerBB->_GET['id']))
		{
		    $PowerBB->functions->ShowHeader($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}


		$SubjectinfArr = array();
		$SubjectinfArr['where'] = array('id',$PowerBB->_GET['id']);

		$Subjectinfo = $PowerBB->core->GetInfo($SubjectinfArr,'subject');
		$PowerBB->functions->ShowHeader($PowerBB->_CONF['template']['_CONF']['lang']['tags']." ".$PowerBB->_CONF['template']['_CONF']['lang']['for_subject']." ".$Subjectinfo['title']);

         $PowerBB->template->assign('SubjectTitle',$Subjectinfo['title']);
         $PowerBB->template->assign('Subjectinfo',$Subjectinfo);

		$SecArr 			= 	array();
		$SecArr['where'] 	= 	array('id',$Subjectinfo['section']);

		$SectionInfo = $PowerBB->core->GetInfo($SecArr,'section');
		$PowerBB->template->assign('SectionInfo',$SectionInfo);

		// Moderator And admin Check for View the Tags Editing
		$ModArr 			= 	array();
		$ModArr['where'] 	= 	array('section_id',$Subjectinfo['section']);

		$ModeratorInfo = $PowerBB->core->GetInfo($ModArr,'moderators');

		if ($Subjectinfo['writer'] == $PowerBB->_CONF['member_row']['username']
		OR $PowerBB->_CONF['group_info']['admincp_allow']
		OR $PowerBB->_CONF['group_info']['vice']
		OR $PowerBB->functions->ModeratorCheck($Subjectinfo['section']))
		{



		$TagSubjectArr 							= 	array();
		$TagSubjectArr['proc'] 					= 	array();
		$TagSubjectArr['proc']['*'] 				= 	array('method'=>'clean','param'=>'html');

		$TagSubjectArr['where']					=	array();
		$TagSubjectArr['where'][0]				=	array();
		$TagSubjectArr['where'][0]['name']		=	'subject_id';
		$TagSubjectArr['where'][0]['oper']		=	'=';
		$TagSubjectArr['where'][0]['value']		=	$PowerBB->_GET['id'];

		$TagSubjectArr['order']					=	array();
		$TagSubjectArr['order']['field']		=	'id';
		$TagSubjectArr['order']['type']			=	'ASC';

		$PowerBB->_CONF['template']['while']['TagSubjectList'] = $PowerBB->tag_subject->GetSubjectList($TagSubjectArr);
		$subject_id = $PowerBB->_GET['id'];
        $SubjectTagNm = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(1),id FROM " . $PowerBB->table['tag_subject'] . " WHERE subject_id='$subject_id' LIMIT 1"));
		$PowerBB->template->assign('SubjectTagNm',$SubjectTagNm);
		$PowerBB->template->assign('subjectid',$PowerBB->_GET['id']);
		$PowerBB->template->assign('tag_counter',0);

         $PowerBB->template->display('tags_edit_subject');
	 }
   	else
     {
     $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);

      }


     }

	function _StartEdit()
	{
	    global $PowerBB;

	    $subject_id = intval($PowerBB->_POST['subjectid']);

	    $SubjectinfArr = array();
	    $SubjectinfArr['where'] = array('id', $subject_id);
	    $Subjectinfo = $PowerBB->subject->GetSubjectInfo($SubjectinfArr);

	    if (isset($PowerBB->_POST['remove']) && $PowerBB->_POST['remove'])
	    {
	        $DelTags = $PowerBB->_POST['del_tag'];

	        if (empty($DelTags)) {
	            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['fill_in_tags_remove']);
	        }

	        foreach ($DelTags as $DelTags_x) {
	            $DeleteSubjectArr = array();
	            $DeleteSubjectArr['where'] = array('id', intval($DelTags_x));
	            $PowerBB->tag_subject->DeleteSubject($DeleteSubjectArr);
	        }

	        $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['tags_delet_successfully']);
	        $PowerBB->functions->redirect('index.php?page=tags&edit=1&id=' . $subject_id);
	    }
	    else
	    {
	        $tags = $PowerBB->_POST['tags'];
	        $existing_ids = $PowerBB->_POST['tag_existing_ids'];

	        if (is_array($tags) && count($tags) > 0)
	        {
	            foreach ($tags as $key => $tag_text)
	            {
				  // 1. Remove HTML tags entirely instead of converting them
				    $tag_text = strip_tags($tag_text);

				    // 2. Basic cleaning (trim and sql protection)
				    $clean_tag = $PowerBB->functions->CleanVariable($tag_text, 'trim,sql');

				    if (empty($clean_tag)) continue;

				    // 3. SEO Logic: Keep only letters, numbers, spaces, and hyphens
				    $clean_tag = preg_replace('/[^\p{L}\p{N}\s\-]/u', '', $clean_tag);

				    // Convert spaces to hyphens
				    $clean_tag = preg_replace('/[\s\-]+/u', '-', $clean_tag);

				    $clean_tag = trim($clean_tag, '-');

				    if (empty($clean_tag)) continue;

	                if (isset($existing_ids[$key]) && !empty($existing_ids[$key]))
	                {
	                    $UpdateArr = array();
	                    $UpdateArr['field'] = array('tag' => $clean_tag);
	                    $UpdateArr['where'] = array('id', intval($existing_ids[$key]));

	                    $PowerBB->tag_subject->UpdateSubject($UpdateArr);
	                }
	                else
	                {
	                    $InsertArr = array();
	                    $InsertArr['field'] = array(
	                        'tag_id'        => 1,
	                        'subject_id'    => $subject_id,
	                        'tag'           => $clean_tag,
	                        'subject_title' => $PowerBB->functions->CleanVariable($Subjectinfo['title'], 'sql')
	                    );

	                    $PowerBB->tag_subject->InsertSubject($InsertArr);
	                }
	            }
	        }

	        $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Updated_successfully']);
	        $PowerBB->functions->redirect('index.php?page=topic&show=1&id=' . $subject_id);
	    }
	}

}

?>
