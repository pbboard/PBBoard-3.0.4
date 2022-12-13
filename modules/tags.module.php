<?php
(!defined('IN_PowerBB')) ? die() : '';

define('CLASS_NAME','PowerBBCoreMOD');

include('common.php');
class PowerBBCoreMOD
{

	function run()
	{
		global $PowerBB;
       $PowerBB->_GET['id']	= 	$PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');

		// Show header with page title
		if ($PowerBB->_GET['show'])
		{
		   $PowerBB->functions->ShowHeader($PowerBB->_CONF['template']['_CONF']['lang']['Atags']);
			$this->_Show();
		}
		elseif ($PowerBB->_GET['edit'])
		{
		   $PowerBB->functions->ShowHeader($PowerBB->_CONF['template']['_CONF']['lang']['Atags']);
			$this->_edit();
		}
		elseif ($PowerBB->_GET['start'])
		{
		   $PowerBB->functions->ShowHeader($PowerBB->_CONF['template']['_CONF']['lang']['Atags']);
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

		// Clean the id from any strings
		$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');

		if (empty($PowerBB->_GET['id']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}

		$PowerBB->_GET['count'] = (!isset($PowerBB->_GET['count'])) ? 0 : $PowerBB->_GET['count'];
		$PowerBB->_GET['count'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['count'],'intval');


		$TagInfoArr 			= 	array();
		$TagInfoArr['where'] 	= 	array('id',$PowerBB->_GET['id']);
		$TagInfo = $PowerBB->tag_subject->GetSubjectInfo($TagInfoArr);

		if (!$TagInfo)
		{
         $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['taag_requested_does_not_exist']);
		}

		$TotalArr 			= 	array();
		$TotalArr['where'] 	= 	array('tag',$TagInfo['tag']);


		// Pager setup
		$TagArr 							= 	array();
		$TagArr['where'] 					= 	array();

		$TagArr['where'][0] 				= 	array();
		$TagArr['where'][0]['name'] 		= 	'tag';
		$TagArr['where'][0]['oper'] 		= 	'=';
		$TagArr['where'][0]['value'] 		= 	$TagInfo['tag'];

		$TagArr['proc'] 			= 	array();
		$TagArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');
		$TagArr['order']			=	array();
		$TagArr['order']['field']	=	'id';
		$TagArr['order']['type']	=	'DESC';

		$TagArr['pager'] 				= 	array();
		$TagArr['pager']['total']		= 	$PowerBB->tag_subject->GetSubjectNumber($TotalArr);
		$TagArr['pager']['perpage'] 	= 	32; // TODO
		$TagArr['pager']['count'] 		= 	$PowerBB->_GET['count'];
		$TagArr['pager']['location'] 	= 	'index.php?page=tags&amp;show=1&amp;id=' . $PowerBB->_GET['id'];
		$TagArr['pager']['var'] 		= 	'count';

		$PowerBB->_CONF['template']['while']['Subject'] = $PowerBB->tag_subject->GetSubjectList($TagArr);

		if (!$PowerBB->_CONF['template']['while']['Subject'])
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['taag_requested_does_not_exist']);
		}

		$PowerBB->template->assign('tag',$TagInfo['tag']);
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
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}


		$SubjectinfArr = array();
		$SubjectinfArr['where'] = array('id',$PowerBB->_GET['id']);

		$Subjectinfo = $PowerBB->core->GetInfo($SubjectinfArr,'subject');

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
        $SubjectTagNm = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['tag_subject'] . " WHERE subject_id='$subject_id'"));
		 $PowerBB->template->assign('SubjectTagNm',$SubjectTagNm);
		 $PowerBB->template->assign('subjectid',$PowerBB->_GET['id']);
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

		$SubjectinfArr = array();
		$SubjectinfArr['where'] = array('id',$PowerBB->_POST['subjectid']);

		$Subjectinfo = $PowerBB->subject->GetSubjectInfo($SubjectinfArr);

		if ($PowerBB->_POST['remove'])
    		{

    		 $DelTags = $PowerBB->_POST['del_tag'];


		      	if (empty($DelTags))
				{
					$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['fill_in_tags_remove']);
				}
		      foreach ($DelTags as $DelTags_x)
		      {


				  $DeleteSubjectArr				=	array();
		          $DeleteSubjectArr['where'] 	= 	array('id',intval($DelTags_x));
				  $delSubject = $PowerBB->tag_subject->DeleteSubject($DeleteSubjectArr);

		      }

		    $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['tags_delet_successfully']);
			$PowerBB->functions->redirect('index.php?page=tags&edit=1&id=' . $PowerBB->_POST['subject_id']);

		   }
		   else
		   {

		    		// Set tags for the subject
		   		$tags_size = sizeof($PowerBB->_POST['tags']);

		   		if ($tags_size > 0
		   			and strlen($PowerBB->_POST['tags'][0]) > 0)
		   		{
		   			foreach ($PowerBB->_POST['tags'] as $tag)
		   			{
		   			  if (!empty($tag))
						{
		   				$CheckArr 			= 	array();
		   				$CheckArr['where'] 	= 	array('tag',$PowerBB->functions->CleanVariable($PowerBB->functions->CleanVariable($PowerBB->functions->CleanVariable($tag,'trim'),'html'),'sql'));

		   				$tag_id = 1;

		   				$Tag = $PowerBB->tag_subject->GetSubjectInfo($CheckArr);

		   				if ($Tag['subject_id'] != $PowerBB->_POST['subjectid'])
		   				{
		   				$InsertArr 						= 	array();
		     	        $InsertArr['get_id']						=	true;
		   				$InsertArr['field']				=	array();

		   				$InsertArr['field']['tag_id'] 			= 	$tag_id;
		   				$InsertArr['field']['subject_id'] 		=	$PowerBB->_POST['subjectid'];
		   				$InsertArr['field']['tag'] 				= 	$PowerBB->functions->CleanVariable($PowerBB->functions->CleanVariable($PowerBB->functions->CleanVariable($tag,'trim'),'html'),'sql');
		   				$InsertArr['field']['subject_title'] 	= 	$Subjectinfo['title'];

		   				// Note, this function is from tag system not subject system
		   				$insert = $PowerBB->tag_subject->InsertSubject($InsertArr);

		   					unset($InsertArr);
				        }
				     	else
				     	{
		   					$UpdateArr 			= 	array();
		   					$UpdateArr['field']	=	array();

		   					$UpdateArr['field']['tag'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->functions->CleanVariable($PowerBB->functions->CleanVariable($tag,'trim'),'html'),'sql');;
		   					$UpdateArr['where']				=	array('id',$Tag['id']);

		   					$update = $PowerBB->tag_subject->UpdateSubject($UpdateArr);

		   					$tag_id = $Tag['id'];
		   				}
                      }
		   			}
		   		}




   			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Updated_successfully']);
			$PowerBB->functions->redirect('index.php?page=topic&show=1&id=' . $PowerBB->_POST['subjectid']);
         }
    }

}

?>
