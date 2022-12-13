<?php
(!defined('IN_PowerBB')) ? die() : '';

define('CLASS_NAME','PowerBBCoreMOD');
define('DONT_STRIPS_SLIASHES',true);
include('common.php');
class PowerBBCoreMOD
{

	function run()
	{
		global $PowerBB;

	 $PowerBB->_GET['subject_id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['subject_id'],'intval');

 		$SubjectArr = array();
		$SubjectArr['where'] = array('id',$PowerBB->_GET['subject_id']);

		$SubjectInfo = $PowerBB->core->GetInfo($SubjectArr,'subject');

        $SectionInfo1 = $SubjectInfo['section'].$PowerBB->_GET['section'];


			/** Get section's group information and make some checks **/
			 if ($PowerBB->_CONF['group_info']['upload_attach'] == 0
			 or $PowerBB->_CONF['group_info']['download_attach'] == 0)
			 {
	          echo($PowerBB->_CONF['template']['_CONF']['lang']['error_permission'].'');
	          exit();
			 }

		/** Action to uplud and Edit and Delete **/

            /** Show a nice form in Subject :) **/
			if ($PowerBB->_GET['edit_attach'])
			{
				$this->_Edit_attach_Subject();
			}
			/** **/
			/** Start uplud and Edit attach in Subject **/
			elseif ($PowerBB->_GET['edit_start'])
			{
				$this->_Start_Edit_Subject();
			}
			/** **/
			/** Start delete attach In Subject **/
			elseif ($PowerBB->_GET['delete_attach_subject'])
			{
				$this->_Delete_Attach_Subject();
			}

			/** Show a nice form in reply :) **/
			if ($PowerBB->_GET['index'])
			{
				$this->_Edit_attach_Reply();
			}
			/** **/
			/** Start uplud and Edit attach in reply **/
			elseif ($PowerBB->_GET['start'])
			{
				$this->_Start_Edit_Reply();
			}
			/** **/
			/** Start delete attach in reply **/
			elseif ($PowerBB->_GET['delete_attach'])
			{
				$this->_Delete_Attach_Reply();
			}

			/** ADD A New attach in New Subject :) **/
			if ($PowerBB->_GET['add_attach'])
			{
				$this->_Add_attach_Subject();
			}
			/** Start uplud and Add attach in New Subject **/
			elseif ($PowerBB->_GET['add_start'])
			{
				$this->_Star_Add_Subject();
			}
			/** Start delete attach In Subject **/
			elseif ($PowerBB->_GET['delete_attach_new_subject'])
			{
				$this->_Delete_Attach_New_Subject();
			}

			/** ADD A New attach in New reply :) **/
			if ($PowerBB->_GET['add_attach_reply'])
			{
				$this->_Add_attach_Reply();
			}
			/** Start uplud and Add attach in New Reply **/
			elseif ($PowerBB->_GET['add_start_reply'])
			{
				$this->_Star_Add_Reply();
			}
			/** Start delete attach In Reply **/
			elseif ($PowerBB->_GET['delete_attach_new_reply'])
			{
				$this->_Delete_Attach_New_Reply();
			}

		  /** **/

	}


    function _Edit_attach_Subject()
	{
		global $PowerBB;

        $PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');
        $PowerBB->_GET['subject_id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['subject_id'],'intval');

		if (empty($PowerBB->_GET['subject_id']))
		{
			echo($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
			exit();
		}

		$ReplyArr = array();
		$ReplyArr['where'] = array('id',$PowerBB->_GET['reply_id']);

		$PowerBB->_CONF['template']['ReplyInfo'] = $PowerBB->core->GetInfo($ReplyArr,'reply');

		$SubjectArr1 = array();
		$SubjectArr1['where'] = array('id',$PowerBB->_GET['subject_id']);

		$PowerBB->_CONF['template']['SubjectInfo'] = $PowerBB->core->GetInfo($SubjectArr1,'subject');
		$SubjectInfo1 = $PowerBB->subject->GetSubjectInfo($SubjectArr1);

		if ($PowerBB->functions->ModeratorCheck($SubjectInfo1['section'])
	        or $PowerBB->_CONF['member_row']['username'] == $SubjectInfo1['writer'])
		{



		// Get the attachment information

			$AttachArr 							= 	array();
			$AttachArr['where']					= 	array();
			$AttachArr['where'][0] 				=	array();
			$AttachArr['where'][0]['name'] 		=	'subject_id';
			$AttachArr['where'][0]['oper'] 		=	'=';
			$AttachArr['where'][0]['value'] 	=	$PowerBB->_GET['subject_id'];
			$AttachArr['where'][1] 				=	array();
			$AttachArr['where'][1]['con']		=	'AND';
			$AttachArr['where'][1]['name'] 		=	'reply';
			$AttachArr['where'][1]['oper'] 		=	'=';
			$AttachArr['where'][1]['value'] 	=	'0';

			$PowerBB->_CONF['template']['while']['AttachList'] = $PowerBB->core->GetList($AttachArr,'attach');

		////////

		$ExArr 						= 	array();
		$ExArr['order']				=	array();
		$ExArr['order']['field']	=	'id';
		$ExArr['order']['type']		=	'DESC';
		$ExArr['proc'] 				= 	array();
		$ExArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');

		$PowerBB->_CONF['template']['while']['ExList'] = $PowerBB->core->GetList($ExArr,'ex');

		// Finally , show the form :)
		$PowerBB->template->display('add_edit_attach_subject');

		}
		else
		{
		echo($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
		exit();
		}
	}


	function _Start_Edit_Subject()
	{
		global $PowerBB;


         $PowerBB->_GET['subject_id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['subject_id'],'intval');

     				$files_error	=	array();
     				$files_success	=	array();
     				$files_number 	= 	sizeof($PowerBB->_FILES['files']['name']);
     				$stop			=	false;

     					// All of these variables use for loop and arrays
     					$x = 0; // For the main loop
     					$y = 0; // For error array
     					$z = 0; // For success array


     			while ($files_number > $x)
     			{
			         if ($files_number == '1')
					{
				         if (empty($PowerBB->_FILES['files']['name'][$x]))
						{
							echo($PowerBB->_CONF['template']['_CONF']['lang']['You_do_not_choose_any_file']);
							exit();
						}
					}

				// Check if the extenstion is allowed or not
				$ext = $PowerBB->functions->GetFileExtension($PowerBB->_FILES['files']['name'][$x]);
				$IsExtensionArr 			= 	array();
				$IsExtensionArr['where'] 	= 	array('Ex',$ext);

				$Isextension = $PowerBB->core->Is($IsExtensionArr,'ex');
				if (!$Isextension)
				{
	              $PowerBB->functions->stop_ajax($PowerBB->_CONF['template']['_CONF']['lang']['Not_available'].' '. $ext .' ' .$PowerBB->_CONF['template']['_CONF']['lang']['Can_you_raise_file_extension']);
				}

		        // Get the extension of the file
				$ExtArr 			= 	array();
				$ExtArr['where'] 	= 	array('Ex',$ext);

				$extension = $PowerBB->core->GetInfo($ExtArr,'ex');

           		 if (!empty($PowerBB->_FILES['files']['name'][$x]))
				{
					if (!$Isextension)
					{
		              echo($PowerBB->_CONF['template']['_CONF']['lang']['Can_you_raise_file_extension'].'('. $ext .')');
		              exit();
					}


			        // Check if the extenstion max size is allowed or not
			        $size = ceil(($PowerBB->_FILES['files']['size'][$x] / 1024));


					if ($size > $extension['max_size'])
					{
		             echo($PowerBB->_CONF['template']['_CONF']['lang']['max_size_extension1'].'('. $ext .')'.$PowerBB->_CONF['template']['_CONF']['lang']['max_size_extension2'].$extension['max_size'].$PowerBB->_CONF['template']['_CONF']['lang']['max_size_extension3']);
		             exit();
					}


		            if ( strstr($PowerBB->_FILES['files']['name'][$x],'.php') )
		             {
		              echo($PowerBB->_CONF['template']['_CONF']['lang']['Can_you_raise_file_extension']);
		              exit();
					}
					if ( strstr($PowerBB->_FILES['files']['name'][$x],'.php3') )
		             {
		              $PowerBB->functions->stop_ajax($PowerBB->_CONF['template']['_CONF']['lang']['Can_you_raise_file_extension']);
					}
					if ( strstr($PowerBB->_FILES['files']['name'][$x],'.phtml') )
		             {
		              $PowerBB->functions->stop_ajax($PowerBB->_CONF['template']['_CONF']['lang']['Can_you_raise_file_extension']);
					}
					if ( strstr($PowerBB->_FILES['files']['name'][$x],'.pl') )
		             {
		              $PowerBB->functions->stop_ajax($PowerBB->_CONF['template']['_CONF']['lang']['Can_you_raise_file_extension']);
					}
					if ( strstr($PowerBB->_FILES['files']['name'][$x],'.cgi') )
		             {
		              $PowerBB->functions->stop_ajax($PowerBB->_CONF['template']['_CONF']['lang']['Can_you_raise_file_extension']);
					}
					if ( strstr($PowerBB->_FILES['files']['name'][$x],'.asp') )
		             {
		              $PowerBB->functions->stop_ajax($PowerBB->_CONF['template']['_CONF']['lang']['Can_you_raise_file_extension']);
					}
					if ( strstr($PowerBB->_FILES['files']['name'][$x],'.3gp') )
		             {
		              $PowerBB->functions->stop_ajax($PowerBB->_CONF['template']['_CONF']['lang']['Can_you_raise_file_extension']);
					}



		             $Random = $PowerBB->functions->RandomCode() .$PowerBB->_FILES['files']['name'][$x];

	                     $ext = str_replace('.','',$ext);


	                // Insert attachment to the database
					$AttachArr 							= 	array();
					$AttachArr['field'] 				= 	array();
					$AttachArr['field']['filename'] 	= 	$PowerBB->_FILES['files']['name'][$x];
					$AttachArr['field']['filepath'] 	= 	$PowerBB->_CONF['info_row']['download_path'] . '/' . $Random;
					$AttachArr['field']['filesize'] 	= 	$PowerBB->_FILES['files']['size'][$x];
					$AttachArr['field']['subject_id'] 	= 	$PowerBB->_GET['subject_id'];
					$AttachArr['field']['extension'] 	= 	$ext;
					$AttachArr['field']['reply']		=	'0';
					$AttachArr['field']['u_id']		    =	$PowerBB->_CONF['member_row']['id'];
				    $AttachArr['field']['time']		    =	$PowerBB->_CONF['now'];
					$InsertAttach = $PowerBB->core->Insert($AttachArr,'attach');



					if ($InsertAttach)
					{

						// Kill XSS
						$PowerBB->functions->CleanVariable($InsertAttach,'html');
						// Kill SQL Injection
						$PowerBB->functions->CleanVariable($InsertAttach,'sql');


						$SubjectArr 							= 	array();
						$SubjectArr['field'] 					= 	array();
						$SubjectArr['field']['attach_subject'] 	= 	'1';
						$SubjectArr['where'] 					= 	array('id',$PowerBB->_GET['subject_id']);

					  $update = $PowerBB->core->Update($SubjectArr,'subject');

				         move_uploaded_file($PowerBB->_FILES['files']['tmp_name'][$x] , $PowerBB->_CONF['info_row']['download_path'] . '/' . $Random);


	                }
	            }
                  $x += 1;
              }

			if (!$InsertAttach)
			{
			$PowerBB->functions->stop_ajax($PowerBB->_CONF['template']['_CONF']['lang']['You_do_not_choose_any_file']);
			}
			else
			{
			//$PowerBB->functions->stop_ajax($PowerBB->_CONF['template']['_CONF']['lang']['File_was_downloaded_successfully']);
			//$PowerBB->functions->redirect('index.php?page=attachments&amp;edit_attach=1&amp;subject_id=' . $PowerBB->_GET['subject_id']);
			}

			$AttachArr 							= 	array();
			$AttachArr['where']					= 	array();
			$AttachArr['where'][0] 				=	array();
			$AttachArr['where'][0]['name'] 		=	'subject_id';
			$AttachArr['where'][0]['oper'] 		=	'=';
			$AttachArr['where'][0]['value'] 	=	$PowerBB->_GET['subject_id'];
			$AttachArr['where'][1] 				=	array();
			$AttachArr['where'][1]['con']		=	'AND';
			$AttachArr['where'][1]['name'] 		=	'reply';
			$AttachArr['where'][1]['oper'] 		=	'=';
			$AttachArr['where'][1]['value'] 	=	'0';

			$AttachArr['order'] 				=	 array();
			$AttachArr['order']['field'] 		= 	'id';
			$AttachArr['order']['type'] 	    = 	'DESC';

			$PowerBB->_CONF['template']['while']['AttachList'] = $PowerBB->core->GetList($AttachArr,'attach');

			$PowerBB->template->assign('id',$PowerBB->_GET['subject_id']);
			$PowerBB->template->display('attach_list');

	 }


	 function _Delete_Attach_Subject()
	{
		global $PowerBB;

       	$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');
        $PowerBB->_GET['subject_id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['subject_id'],'intval');

		if (empty($PowerBB->_GET['id']))
		{
			$PowerBB->functions->stop_ajax($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}

       		$ReplyArr = array();
		$ReplyArr['where'] = array('id',$PowerBB->_GET['reply_id']);

		$PowerBB->_CONF['template']['ReplyInfo'] = $PowerBB->core->GetInfo($ReplyArr,'reply');

		$SubjectArr = array();
		$SubjectArr['where'] = array('id',$PowerBB->_GET['subject_id']);

		$PowerBB->_CONF['template']['SubjectInfo'] = $PowerBB->core->GetInfo($SubjectArr,'subject');
		$SubjectInfo = $PowerBB->core->GetInfo($SubjectArr,'subject');

		if ($PowerBB->functions->ModeratorCheck($SubjectInfo['section'])
	        or $PowerBB->_CONF['member_row']['username'] == $SubjectInfo['writer'])
		{
			$ReplyArr = array();
			$ReplyArr['where'] = array('id',$PowerBB->_GET['reply_id']);

			$PowerBB->_CONF['template']['ReplyInfo'] = $PowerBB->core->GetInfo($GetReplyInfo,'reply');


			$SubjectArr = array();
			$SubjectArr['where'] = array('id',$PowerBB->_GET['subject_id']);

			$PowerBB->_CONF['template']['SubjectInfo'] = $PowerBB->core->GetInfo($SubjectArr,'subject');

				   $GetSubjectAttachArr 					= 	array();
				   $GetSubjectAttachArr['where'] 			= 	array('id',$PowerBB->_GET['subject_id']);
				   $SubjectAttachinfo = $PowerBB->core->GetInfo($GetSubjectAttachArr,'attach');

           		   $GetattachNumrs = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT ID FROM " . $PowerBB->table['attach'] . " WHERE subject_id = ".$PowerBB->_GET['subject_id']." and reply = '0'"));

					if (!$SubjectAttachinfo)
					{		              if ($GetattachNumrs < 0)
				      {
						$SubjectArr 							= 	array();
						$SubjectArr['field'] 					= 	array();
						$SubjectArr['field']['attach_subject'] 	= 	'0';
						$SubjectArr['where'] 					= 	array('id',$PowerBB->_GET['subject_id']);

					     $update = $PowerBB->core->Update($SubjectArr,'subject');
					   }
					}

		   $GetAttachArr 					= 	array();
		   $GetAttachArr['where'] 			= 	array('id',$PowerBB->_GET['id']);
		   $Attachinfo = $PowerBB->core->GetInfo($GetAttachArr,'attach');

		   	 if (file_exists($Attachinfo['filepath']))
		      {
			   $del = @unlink($Attachinfo['filepath']);
              }

	        // Delete attachment to the database
			$AttachArr 							= 	array();
			$AttachArr['name'] 	        		=  	'id';
	        $AttachArr['where'] 		    	= 	array('id',$PowerBB->_GET['id']);

			$DeleteAttach = $PowerBB->core->Deleted($AttachArr,'attach');

			if($DeleteAttach)
		  {

			// Finally , Delete the Attach
	       // $PowerBB->functions->stop_ajax($PowerBB->_CONF['template']['_CONF']['lang']['File_was_deleted_successfully']);
			//$PowerBB->functions->redirect('index.php?page=attachments&amp;edit_attach=1&amp;subject_id=' . $PowerBB->_GET['subject_id']);

			$AttachArr 							= 	array();
			$AttachArr['where']					= 	array();
			$AttachArr['where'][0] 				=	array();
			$AttachArr['where'][0]['name'] 		=	'subject_id';
			$AttachArr['where'][0]['oper'] 		=	'=';
			$AttachArr['where'][0]['value'] 	=	$PowerBB->_GET['subject_id'];
			$AttachArr['where'][1] 				=	array();
			$AttachArr['where'][1]['con']		=	'AND';
			$AttachArr['where'][1]['name'] 		=	'reply';
			$AttachArr['where'][1]['oper'] 		=	'=';
			$AttachArr['where'][1]['value'] 	=	'0';

			$AttachArr['order'] 				=	 array();
			$AttachArr['order']['field'] 		= 	'id';
			$AttachArr['order']['type'] 	    = 	'DESC';

			$PowerBB->_CONF['template']['while']['AttachList'] = $PowerBB->core->GetList($AttachArr,'attach');

			$PowerBB->template->assign('id',$PowerBB->_GET['subject_id']);
			$PowerBB->template->display('attach_list');

		  }


		}
		else
		{
		$PowerBB->functions->stop_ajax($PowerBB->_CONF['template']['_CONF']['lang']['File_was_deleted_successfully']);
		}

	}


	function _Edit_attach_Reply()
	{
		global $PowerBB;


        $PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');
       	$PowerBB->_GET['reply_id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['reply_id'],'intval');
        $PowerBB->_GET['subject_id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['subject_id'],'intval');

		if (empty($PowerBB->_GET['reply_id'])
		or empty($PowerBB->_GET['subject_id']))
		{
			$PowerBB->functions->stop_ajax($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}

		$ReplyArr = array();
		$ReplyArr['where'] = array('id',$PowerBB->_GET['reply_id']);

		$PowerBB->_CONF['template']['ReplyInfo'] = $PowerBB->reply->GetReplyInfo($ReplyArr);
         // Get the attachment information

		$ReplyAttachArr 					= 	array();
		$ReplyAttachArr['where'] 			= 	array('subject_id',$PowerBB->_GET['reply_id']);

		$PowerBB->_CONF['template']['while']['ReplyAttachList'] = $PowerBB->core->GetList($ReplyAttachArr,'attach');

		if ($PowerBB->functions->ModeratorCheck($PowerBB->_CONF['template']['ReplyInfo']['section'])
	     or $PowerBB->_CONF['member_row']['username'] == $PowerBB->_CONF['template']['ReplyInfo']['writer'])
		{

		////////

		$ExArr 						= 	array();
		$ExArr['order']				=	array();
		$ExArr['order']['field']	=	'id';
		$ExArr['order']['type']		=	'DESC';
		$ExArr['proc'] 				= 	array();
		$ExArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');

		$PowerBB->_CONF['template']['while']['ExList'] = $PowerBB->core->GetList($ExArr,'ex');


		// Finally , show the form :)
		$PowerBB->template->display('add_edit_attach_reply');

		}
		else
		{
		$PowerBB->functions->stop_ajax($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
		}
	}


	function _Start_Edit_Reply()
	{
		global $PowerBB;

         $PowerBB->_GET['reply_id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['reply_id'],'intval');
         $PowerBB->_GET['subject_id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['subject_id'],'intval');

   				    $files_error	=	array();
     				$files_success	=	array();
     				$files_number 	= 	sizeof($PowerBB->_FILES['files']['name']);
     				$stop			=	false;

     					// All of these variables use for loop and arrays
     					$x = 0; // For the main loop
     					$y = 0; // For error array
     					$z = 0; // For success array


      			while ($files_number > $x)
     			{
			         if ($files_number == '1')
					{
				         if (empty($PowerBB->_FILES['files']['name'][$x]))
						{
							$PowerBB->functions->stop_ajax($PowerBB->_CONF['template']['_CONF']['lang']['You_do_not_choose_any_file']);
						}
					}

						// Check if the extenstion is allowed or not
						$ext = $PowerBB->functions->GetFileExtension($PowerBB->_FILES['files']['name'][$x]);
						$IsExtensionArr 			= 	array();
						$IsExtensionArr['where'] 	= 	array('Ex',$ext);

						$Isextension = $PowerBB->core->Is($IsExtensionArr,'ex');

						if (!$Isextension)
						{
			              $PowerBB->functions->stop_ajax($PowerBB->_CONF['template']['_CONF']['lang']['Not_available'].' '. $ext .' ' .$PowerBB->_CONF['template']['_CONF']['lang']['Can_you_raise_file_extension']);
						}

				        // Get the extension of the file
						$ExtArr 			= 	array();
						$ExtArr['where'] 	= 	array('Ex',$ext);

						$extension = $PowerBB->core->GetInfo($ExtArr,'ex');

				        // Check if the extenstion max size is allowed or not
				        $size = ceil(($PowerBB->_FILES['files']['size'][$x] / 1024));

						if ($size > $extension['max_size'])
						{
			              $PowerBB->functions->stop_ajax($PowerBB->_CONF['template']['_CONF']['lang']['max_size_extension1'].'('. $ext .')'.$PowerBB->_CONF['template']['_CONF']['lang']['max_size_extension2'].$extension['max_size'].$PowerBB->_CONF['template']['_CONF']['lang']['max_size_extension3']);
						}


	           		 if (!empty($PowerBB->_FILES['files']['name'][$x]))
					{

					  if (!$Isextension)
						{
			              $PowerBB->functions->stop_ajax($PowerBB->_CONF['template']['_CONF']['lang']['Can_you_raise_file_extension'].'('. $ext .')');
						}


			              if ( strstr($PowerBB->_FILES['files']['name'][$x],'.php') )
			             {
			              $PowerBB->functions->stop_ajax($PowerBB->_CONF['template']['_CONF']['lang']['Can_you_raise_file_extension']);
						}
						if ( strstr($PowerBB->_FILES['files']['name'][$x],'.php3') )
			             {
			              $PowerBB->functions->stop_ajax($PowerBB->_CONF['template']['_CONF']['lang']['Can_you_raise_file_extension']);
						}
						if ( strstr($PowerBB->_FILES['files']['name'][$x],'.phtml') )
			             {
			              $PowerBB->functions->stop_ajax($PowerBB->_CONF['template']['_CONF']['lang']['Can_you_raise_file_extension']);
						}
						if ( strstr($PowerBB->_FILES['files']['name'][$x],'.pl') )
			             {
			              $PowerBB->functions->stop_ajax($PowerBB->_CONF['template']['_CONF']['lang']['Can_you_raise_file_extension']);
						}
						if ( strstr($PowerBB->_FILES['files']['name'][$x],'.cgi') )
			             {
			              $PowerBB->functions->stop_ajax($PowerBB->_CONF['template']['_CONF']['lang']['Can_you_raise_file_extension']);
						}
						if ( strstr($PowerBB->_FILES['files']['name'][$x],'.asp') )
			             {
			             $PowerBB->functions->stop_ajax($PowerBB->_CONF['template']['_CONF']['lang']['Can_you_raise_file_extension']);
						}
						if ( strstr($PowerBB->_FILES['files']['name'][$x],'.3gp') )
			             {
			              $PowerBB->functions->stop_ajax($PowerBB->_CONF['template']['_CONF']['lang']['Can_you_raise_file_extension']);
						}

			             $Random = $PowerBB->functions->RandomCode() .$PowerBB->_FILES['files']['name'][$x];

			                     $ext = str_replace('.','',$ext);

			                // Insert attachment to the database
							$AttachArr 							= 	array();
							$AttachArr['field'] 				= 	array();
							$AttachArr['field']['filename'] 	= 	$PowerBB->_FILES['files']['name'][$x];
							$AttachArr['field']['filepath'] 	= 	$PowerBB->_CONF['info_row']['download_path'] . '/' . $Random;
							$AttachArr['field']['filesize'] 	= 	$PowerBB->_FILES['files']['size'][$x];
							$AttachArr['field']['extension'] 	= 	$ext;
							$AttachArr['field']['subject_id'] 	= 	$PowerBB->_GET['reply_id'];
							$AttachArr['field']['reply']		=	1;
							$AttachArr['field']['u_id']		    =	$PowerBB->_CONF['member_row']['id'];
				            $AttachArr['field']['time']		    =	$PowerBB->_CONF['now'];

							$InsertAttach = $PowerBB->core->Insert($AttachArr,'attach');



							if ($InsertAttach)
							{

								// Kill XSS
								$PowerBB->functions->CleanVariable($InsertAttach,'html');
								// Kill SQL Injection
								$PowerBB->functions->CleanVariable($InsertAttach,'sql');


								$ReplyArr 							= 	array();
								$ReplyArr['field'] 					= 	array();
								$ReplyArr['field']['attach_reply'] 	= 	'1';
								$ReplyArr['where'] 					= 	array('id',$PowerBB->_GET['reply_id']);

							  $update = $PowerBB->core->Update($ReplyArr,'reply');

						         move_uploaded_file($PowerBB->_FILES['files']['tmp_name'][$x] , $PowerBB->_CONF['info_row']['download_path'] . '/' . $Random);


			                }
                      }
			                $x += 1;
              }

			if (!$InsertAttach)
			{
			$PowerBB->functions->stop_ajax($PowerBB->_CONF['template']['_CONF']['lang']['You_do_not_choose_any_file']);
			}
			else
			{
			//$PowerBB->functions->stop_ajax($PowerBB->_CONF['template']['_CONF']['lang']['File_was_downloaded_successfully']);
			//$PowerBB->functions->redirect('index.php?page=attachments&amp;index=1&amp;subject_id=' . $PowerBB->_GET['subject_id'] . '&amp;reply_id=' . $PowerBB->_GET['reply_id']);
			}

					$AttachArr 					= 	array();
					$AttachArr['where'] 			= 	array('subject_id',$PowerBB->_GET['reply_id']);
					$AttachArr['order'] 				=	 array();
					$AttachArr['order']['field'] 		= 	'id';
					$AttachArr['order']['type'] 	    = 	'DESC';

             		$PowerBB->_CONF['template']['while']['AttachList'] = $PowerBB->core->GetList($AttachArr,'attach');

					$PowerBB->template->assign('id',$PowerBB->_GET['subject_id']);
					$PowerBB->template->display('attach_list');

	 }


	 function _Delete_Attach_Reply()
	{
		global $PowerBB;

		$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');
       	$PowerBB->_GET['reply_id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['reply_id'],'intval');
        $PowerBB->_GET['subject_id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['subject_id'],'intval');

		if (empty($PowerBB->_GET['id']))
		{
			$PowerBB->functions->stop_ajax($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}
       		$ReplyArr = array();
		$ReplyArr['where'] = array('id',$PowerBB->_GET['reply_id']);

		$PowerBB->_CONF['template']['ReplyInfo'] = $PowerBB->core->GetInfo($GetReplyInfo,'reply');
       $ReplyInfo = $PowerBB->core->GetInfo($ReplyArr,'reply');

         // Get the attachment information

		$ReplyAttachArr 					= 	array();
		$ReplyAttachArr['where'] 			= 	array('subject_id',$PowerBB->_GET['reply_id']);

		$PowerBB->_CONF['template']['while']['ReplyAttachList'] = $PowerBB->core->GetList($ReplyAttachArr,'attach');

		if ($PowerBB->functions->ModeratorCheck($ReplyInfo['section'])
        or $PowerBB->_CONF['member_row']['username'] == $ReplyInfo['writer'])
		{
				$ReplyArr = array();
				$ReplyArr['where'] = array('id',$PowerBB->_GET['reply_id']);

				$PowerBB->_CONF['template']['ReplyInfo'] = $PowerBB->core->GetInfo($GetReplyInfo,'reply');

		         $ReplyInfo = $PowerBB->core->GetInfo($GetReplyInfo,'reply');

           		$GetattachNumrs = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT ID FROM " . $PowerBB->table['attach'] . " WHERE subject_id = ".$PowerBB->_GET['reply_id']." and reply = '1'"));
              if ($GetattachNumrs < 0)
		      {
		            // Delete attachment to the database
					$ReplyArr 							= 	array();
					$ReplyArr['field'] 					= 	array();
					$ReplyArr['field']['attach_reply'] 	= 	'0';
					$ReplyArr['where'] 					= 	array('id',$PowerBB->_GET['reply_id']);

				   $update = $PowerBB->core->Update($ReplyArr,'reply');
                }

			   $GetAttachArr 					= 	array();
			   $GetAttachArr['where'] 			= 	array('id',$PowerBB->_GET['id']);
			   $Attachinfo = $PowerBB->core->GetInfo($GetAttachArr,'attach');

			  if (file_exists($Attachinfo['filepath']))
		      {
			   $del = @unlink($Attachinfo['filepath']);
              }

		        // Delete attachment to the database
				$AttachArr 							= 	array();
				$AttachArr['name'] 	        		=  	'id';
		        $AttachArr['where'] 		    	= 	array('id',$PowerBB->_GET['id']);

				$DeleteAttach = $PowerBB->core->Deleted($AttachArr,'attach');

			  if($DeleteAttach)
			  {

				       // Finally , Delete the Attach
		        //$PowerBB->functions->stop_ajax($PowerBB->_CONF['template']['_CONF']['lang']['File_was_deleted_successfully']);
				//$PowerBB->functions->redirect('index.php?page=attachments&amp;index=1&amp;subject_id=' . $ReplyInfo['subject_id'] . '&amp;reply_id=' . $PowerBB->_GET['reply_id']);

					$AttachArr 					= 	array();
					$AttachArr['where'] 			= 	array('subject_id',$PowerBB->_GET['reply_id']);
					$AttachArr['order'] 				=	 array();
					$AttachArr['order']['field'] 		= 	'id';
					$AttachArr['order']['type'] 	    = 	'DESC';

             		$PowerBB->_CONF['template']['while']['AttachList'] = $PowerBB->core->GetList($AttachArr,'attach');

					$PowerBB->template->assign('id',$PowerBB->_GET['subject_id']);
					$PowerBB->template->display('attach_list');

			  }

      	}
		else
		{
		$PowerBB->functions->stop_ajax($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
		}

	}

    function _Add_attach_Subject()
	{
		global $PowerBB;

		// Get the attachment information

			$AttachArr 							= 	array();
			$AttachArr['where']					= 	array();
			$AttachArr['where'][0] 				=	array();
			$AttachArr['where'][0]['name'] 		=	'subject_id';
			$AttachArr['where'][0]['oper'] 		=	'=';
			$AttachArr['where'][0]['value'] 	=	'-'.$PowerBB->_CONF['member_row']['id'];
			$AttachArr['where'][1] 				=	array();
			$AttachArr['where'][1]['con']		=	'AND';
			$AttachArr['where'][1]['name'] 		=	'reply';
			$AttachArr['where'][1]['oper'] 		=	'=';
			$AttachArr['where'][1]['value'] 	=	'0';

			$PowerBB->_CONF['template']['while']['AttachList'] = $PowerBB->core->GetList($AttachArr,'attach');

		////////

		$ExArr 						= 	array();
		$ExArr['order']				=	array();
		$ExArr['order']['field']	=	'id';
		$ExArr['order']['type']		=	'DESC';
		$ExArr['proc'] 				= 	array();
		$ExArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');

		$PowerBB->_CONF['template']['while']['ExList'] = $PowerBB->core->GetList($ExArr,'ex');

		// Finally , show the form :)
		$PowerBB->template->assign('section',$PowerBB->_GET['section']);
		$PowerBB->template->display('add_attach_subject');


	}

	function _Star_Add_Subject()
	{
		global $PowerBB;

		$files_error	=	array();
		$files_success	=	array();
		$files_number 	= 	sizeof($PowerBB->_FILES['files']['name']);
		$stop			=	false;

		// All of these variables use for loop and arrays
		$x = 0; // For the main loop
		$y = 0; // For error array
		$z = 0; // For success array


     	 while ($files_number > $x)
     	 {

	         if ($files_number == '1')
			{
		         if (empty($PowerBB->_FILES['files']['name'][$x]))
				{
					$PowerBB->functions->stop_ajax($PowerBB->_CONF['template']['_CONF']['lang']['You_do_not_choose_any_file']);
				}
			}

			// Check if the extenstion is allowed or not
			$ext = $PowerBB->functions->GetFileExtension($PowerBB->_FILES['files']['name'][$x]);

				$IsExtensionArr 			= 	array();
				$IsExtensionArr['where'] 	= 	array('Ex',$ext);

				$Isextension = $PowerBB->core->Is($IsExtensionArr,'ex');
				if (!$Isextension)
				{
	              $PowerBB->functions->stop_ajax($PowerBB->_CONF['template']['_CONF']['lang']['Not_available'].' '. $ext .' ' .$PowerBB->_CONF['template']['_CONF']['lang']['Can_you_raise_file_extension']);
				}


	        // Get the extension of the file
			$ExtArr 			= 	array();
			$ExtArr['where'] 	= 	array('Ex',$ext);

			$extension = $PowerBB->core->GetInfo($ExtArr,'ex');
	        // Check if the extenstion max size is allowed or not
	        $size = ceil(($PowerBB->_FILES['files']['size'][$x] / 1024));
	        $max_size = ceil(($extension['max_size'] / 1024));
			if ($size > $extension['max_size'])
			{
            $PowerBB->functions->stop_ajax($PowerBB->_CONF['template']['_CONF']['lang']['max_size_extension1'].'('. $ext .')'.$PowerBB->_CONF['template']['_CONF']['lang']['max_size_extension2'].$max_size.$PowerBB->_CONF['template']['_CONF']['lang']['max_size_extension3']);
			}

           		 if (!empty($PowerBB->_FILES['files']['name'][$x]))
				{
					if (!$Isextension)
					{
		              $PowerBB->functions->stop_ajax($PowerBB->_CONF['template']['_CONF']['lang']['Can_you_raise_file_extension'].'('. $ext .')');
					}


	            if ( strstr($PowerBB->_FILES['files']['name'][$x],'.php') )
	             {
	              $PowerBB->functions->stop_ajax($PowerBB->_CONF['template']['_CONF']['lang']['Can_you_raise_file_extension']);
				}
				if ( strstr($PowerBB->_FILES['files']['name'][$x],'.php3') )
	             {
	              $PowerBB->functions->stop_ajax($PowerBB->_CONF['template']['_CONF']['lang']['Can_you_raise_file_extension']);
				}
				if ( strstr($PowerBB->_FILES['files']['name'][$x],'.phtml') )
	             {
	              $PowerBB->functions->stop_ajax($PowerBB->_CONF['template']['_CONF']['lang']['Can_you_raise_file_extension']);
				}
				if ( strstr($PowerBB->_FILES['files']['name'][$x],'.pl') )
	             {
	              $PowerBB->functions->stop_ajax($PowerBB->_CONF['template']['_CONF']['lang']['Can_you_raise_file_extension']);
				}
				if ( strstr($PowerBB->_FILES['files']['name'][$x],'.cgi') )
	             {
	              $PowerBB->functions->stop_ajax($PowerBB->_CONF['template']['_CONF']['lang']['Can_you_raise_file_extension']);
				}
				if ( strstr($PowerBB->_FILES['files']['name'][$x],'.asp') )
	             {
	              $PowerBB->functions->stop_ajax($PowerBB->_CONF['template']['_CONF']['lang']['Can_you_raise_file_extension']);
				}
				if ( strstr($PowerBB->_FILES['files']['name'][$x],'.3gp') )
	             {
	              $PowerBB->functions->stop_ajax($PowerBB->_CONF['template']['_CONF']['lang']['Can_you_raise_file_extension']);
				}



              $Random = $PowerBB->functions->RandomCode() .$PowerBB->_FILES['files']['name'][$x];

                     $ext = str_replace('.','',$ext);


                // Insert attachment to the database
				$AttachArr 							= 	array();
				$AttachArr['field'] 				= 	array();
				$AttachArr['field']['filename'] 	= 	$PowerBB->_FILES['files']['name'][$x];
				$AttachArr['field']['filepath'] 	= 	$PowerBB->_CONF['info_row']['download_path'] . '/' . $Random;
				$AttachArr['field']['filesize'] 	= 	$PowerBB->_FILES['files']['size'][$x];
				$AttachArr['field']['subject_id'] 	= 	'-'.$PowerBB->_CONF['member_row']['id'];
				$AttachArr['field']['extension'] 	= 	$ext;
				$AttachArr['field']['reply']		=	'0';
				$AttachArr['field']['u_id']		    =	$PowerBB->_CONF['member_row']['id'];
				$AttachArr['field']['time']		    =	$PowerBB->_CONF['now'];
				$InsertAttach = $PowerBB->core->Insert($AttachArr,'attach');

				if ($InsertAttach)
				{

					// Kill XSS
					$PowerBB->functions->CleanVariable($InsertAttach,'html');
					// Kill SQL Injection
					$PowerBB->functions->CleanVariable($InsertAttach,'sql');


			         move_uploaded_file($PowerBB->_FILES['files']['tmp_name'][$x] , $PowerBB->_CONF['info_row']['download_path'] . '/' . $Random);
              }
             }

                 $x += 1;
         }



			if (!$InsertAttach)
			{
			$PowerBB->functions->stop_ajax($PowerBB->_CONF['template']['_CONF']['lang']['You_do_not_choose_any_file']);
			}
			else
			{
			//$PowerBB->functions->stop_ajax($PowerBB->_CONF['template']['_CONF']['lang']['File_was_downloaded_successfully']);
			//$PowerBB->functions->redirect('index.php?page=attachments&amp;add_attach=1&amp;section='.$PowerBB->_GET['section'].'');

			}


		$AttachArr 							= 	array();
		$AttachArr['where']					= 	array();
		$AttachArr['where'][0] 				=	array();
		$AttachArr['where'][0]['name'] 		=	'subject_id';
		$AttachArr['where'][0]['oper'] 		=	'=';
		$AttachArr['where'][0]['value'] 	=	'-'.$PowerBB->_CONF['member_row']['id'];
		$AttachArr['where'][1] 				=	array();
		$AttachArr['where'][1]['con']		=	'AND';
		$AttachArr['where'][1]['name'] 		=	'reply';
		$AttachArr['where'][1]['oper'] 		=	'=';
		$AttachArr['where'][1]['value'] 	=	'0';

		$AttachArr['order'] 				=	 array();
		$AttachArr['order']['field'] 		= 	'id';
		$AttachArr['order']['type'] 	    = 	'DESC';
		$PowerBB->_CONF['template']['while']['AttachList'] = $PowerBB->core->GetList($AttachArr,'attach');

		$PowerBB->template->display('attach_list');

	 }

	 function _Delete_Attach_New_Subject()
	{
		global $PowerBB;

       	$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');

		if (empty($PowerBB->_GET['id']))
		{
			$PowerBB->functions->stop_ajax($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}



		  $GetAttachArr 					= 	array();
		  $GetAttachArr['where'] 			= 	array('id',$PowerBB->_GET['id']);
		   $Attachinfo = $PowerBB->core->GetInfo($GetAttachArr,'attach');

		   	if (file_exists($Attachinfo['filepath']))
		      {
			   $del = @unlink($Attachinfo['filepath']);
              }


	        // Delete attachment to the database
			$AttachArr 							= 	array();
			$AttachArr['name'] 	        		=  	'id';
	        $AttachArr['where'] 		    	= 	array('id',$PowerBB->_GET['id']);

			$DeleteAttach = $PowerBB->core->Deleted($AttachArr,'attach');

		 if($DeleteAttach)
		  {
			// Finally , Delete the Attach
	        //$PowerBB->functions->stop_ajax($PowerBB->_CONF['template']['_CONF']['lang']['File_was_deleted_successfully']);
		    //$PowerBB->functions->redirect('index.php?page=attachments&amp;add_attach=1&amp;section='.$PowerBB->_GET['section'].'');
		  }


			$AttachArr 							= 	array();
			$AttachArr['where']					= 	array();
			$AttachArr['where'][0] 				=	array();
			$AttachArr['where'][0]['name'] 		=	'subject_id';
			$AttachArr['where'][0]['oper'] 		=	'=';
			$AttachArr['where'][0]['value'] 	=	'-'.$PowerBB->_CONF['member_row']['id'];
			$AttachArr['where'][1] 				=	array();
			$AttachArr['where'][1]['con']		=	'AND';
			$AttachArr['where'][1]['name'] 		=	'reply';
			$AttachArr['where'][1]['oper'] 		=	'=';
			$AttachArr['where'][1]['value'] 	=	'0';
			$AttachArr['order'] 				=	 array();
			$AttachArr['order']['field'] 		= 	'id';
			$AttachArr['order']['type'] 	    = 	'DESC';
			$PowerBB->_CONF['template']['while']['AttachList'] = $PowerBB->core->GetList($AttachArr,'attach');

			//$PowerBB->template->assign('id',$PowerBB->_GET['subject_id']);
			$PowerBB->template->display('attach_list');


	}

    function _Add_attach_Reply()
	{
		global $PowerBB;

		// Get the attachment information

			$AttachArr 							= 	array();
			$AttachArr['where']					= 	array();
			$AttachArr['where'][0] 				=	array();
			$AttachArr['where'][0]['name'] 		=	'subject_id';
			$AttachArr['where'][0]['oper'] 		=	'=';
			$AttachArr['where'][0]['value'] 	=	$PowerBB->_GET['subject_id'];
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

		// Finally , show the form :)
		$PowerBB->template->assign('subject_id',$PowerBB->_GET['subject_id']);
		$PowerBB->template->display('add_attach_reply');


	}

	function _Star_Add_Reply()
	{
		global $PowerBB;

		if (empty($PowerBB->_FILES['files']['name']))
		{
		$PowerBB->functions->stop_ajax($PowerBB->_CONF['template']['_CONF']['lang']['You_do_not_choose_any_file']);
		}

		$files_error	=	array();
		$files_success	=	array();
		$files_number 	= 	sizeof($PowerBB->_FILES['files']['name']);
		$stop			=	false;

		// All of these variables use for loop and arrays
		$x = 0; // For the main loop
		$y = 0; // For error array
		$z = 0; // For success array

     	 while ($files_number > $x)
     	 {

	         if ($files_number == '1')
			{
		         if (empty($PowerBB->_FILES['files']['name'][$x]))
				{
					$PowerBB->functions->stop_ajax($PowerBB->_CONF['template']['_CONF']['lang']['You_do_not_choose_any_file']);
				}
			}


			// Check if the extenstion is allowed or not
			$ext = $PowerBB->functions->GetFileExtension($PowerBB->_FILES['files']['name'][$x]);

			$IsExtensionArr 			= 	array();
			$IsExtensionArr['where'] 	= 	array('Ex',$ext);

			$Isextension = $PowerBB->core->Is($IsExtensionArr,'ex');

			if (!$Isextension)
			{
			$PowerBB->functions->stop_ajax($PowerBB->_CONF['template']['_CONF']['lang']['Not_available'].' '. $ext .' ' .$PowerBB->_CONF['template']['_CONF']['lang']['Can_you_raise_file_extension']);
			}
	        // Get the extension of the file
			$ExtArr 			= 	array();
			$ExtArr['where'] 	= 	array('Ex',$ext);

			$extension = $PowerBB->core->GetInfo($ExtArr,'ex');

	        // Check if the extenstion max size is allowed or not
	        $size = ceil(($PowerBB->_FILES['files']['size'][$x] / 1024));

			if ($size > $extension['max_size'])
			{
              echo ($PowerBB->_CONF['template']['_CONF']['lang']['max_size_extension1'].'('. $ext .')'.$PowerBB->_CONF['template']['_CONF']['lang']['max_size_extension2'].$extension['max_size'].$PowerBB->_CONF['template']['_CONF']['lang']['max_size_extension3']);
              exit();
			}

           		 if (!empty($PowerBB->_FILES['files']['name'][$x]))
				{
					if (!$Isextension)
					{
	              $PowerBB->functions->stop_ajax($PowerBB->_CONF['template']['_CONF']['lang']['Can_you_raise_file_extension'].'('. $ext .')');
				}


	            if ( strstr($PowerBB->_FILES['files']['name'][$x],'.php') )
	             {
	              $PowerBB->functions->stop_ajax($PowerBB->_CONF['template']['_CONF']['lang']['Can_you_raise_file_extension']);
				}
				if ( strstr($PowerBB->_FILES['files']['name'][$x],'.php3') )
	             {
	              $PowerBB->functions->stop_ajax($PowerBB->_CONF['template']['_CONF']['lang']['Can_you_raise_file_extension']);
				}
				if ( strstr($PowerBB->_FILES['files']['name'][$x],'.phtml') )
	             {
	              $PowerBB->functions->stop_ajax($PowerBB->_CONF['template']['_CONF']['lang']['Can_you_raise_file_extension']);
				}
				if ( strstr($PowerBB->_FILES['files']['name'][$x],'.pl') )
	             {
	              $PowerBB->functions->stop_ajax($PowerBB->_CONF['template']['_CONF']['lang']['Can_you_raise_file_extension']);
				}
				if ( strstr($PowerBB->_FILES['files']['name'][$x],'.cgi') )
	             {
	              $PowerBB->functions->stop_ajax($PowerBB->_CONF['template']['_CONF']['lang']['Can_you_raise_file_extension']);
				}
				if ( strstr($PowerBB->_FILES['files']['name'][$x],'.asp') )
	             {
	              $PowerBB->functions->stop_ajax($PowerBB->_CONF['template']['_CONF']['lang']['Can_you_raise_file_extension']);
				}
				if ( strstr($PowerBB->_FILES['files']['name'][$x],'.3gp') )
	             {
	              $PowerBB->functions->stop_ajax($PowerBB->_CONF['template']['_CONF']['lang']['Can_you_raise_file_extension']);
				}



             $Random = $PowerBB->functions->RandomCode() .$PowerBB->_FILES['files']['name'][$x];

                     $ext = str_replace('.','',$ext);


                // Insert attachment to the database
				$AttachArr 							= 	array();
				$AttachArr['field'] 				= 	array();
				$AttachArr['field']['filename'] 	= 	$PowerBB->_FILES['files']['name'][$x];
				$AttachArr['field']['filepath'] 	= 	$PowerBB->_CONF['info_row']['download_path'] . '/' . $Random;
				$AttachArr['field']['filesize'] 	= 	$PowerBB->_FILES['files']['size'][$x];
				$AttachArr['field']['subject_id'] 	= 	$PowerBB->_GET['subject_id'];
				$AttachArr['field']['extension'] 	= 	$ext;
				$AttachArr['field']['reply']		=	'-'.$PowerBB->_CONF['member_row']['id'];
				$AttachArr['field']['u_id']		    =	$PowerBB->_CONF['member_row']['id'];
				$AttachArr['field']['time']		    =	$PowerBB->_CONF['now'];
				$InsertAttach = $PowerBB->core->Insert($AttachArr,'attach');

				if ($InsertAttach)
				{

					// Kill XSS
					$PowerBB->functions->CleanVariable($InsertAttach,'html');
					// Kill SQL Injection
					$PowerBB->functions->CleanVariable($InsertAttach,'sql');

						$SubjectArr 							= 	array();
						$SubjectArr['field'] 					= 	array();
						$SubjectArr['field']['attach_subject'] 	= 	'1';
						$SubjectArr['where'] 					= 	array('id',$PowerBB->_GET['subject_id']);

					  $update = $PowerBB->core->Update($SubjectArr,'subject');

			         move_uploaded_file($PowerBB->_FILES['files']['tmp_name'][$x] , $PowerBB->_CONF['info_row']['download_path'] . '/' . $Random);


                }

             }

			$x += 1;
			}

			if (!$InsertAttach)
			{
			$PowerBB->functions->stop_ajax($PowerBB->_CONF['template']['_CONF']['lang']['You_do_not_choose_any_file']);
			}
			else
			{				   $GetSubjectAttachArr 					= 	array();
				   $GetSubjectAttachArr['where'] 			= 	array('id',$PowerBB->_GET['subject_id']);
				   $SubjectAttachinfo = $PowerBB->core->GetInfo($GetSubjectAttachArr,'attach');

					if (!$SubjectAttachinfo)
					{
						$SubjectArr 							= 	array();
						$SubjectArr['field'] 					= 	array();
						$SubjectArr['field']['attach_subject'] 	= 	'1';
						$SubjectArr['where'] 					= 	array('id',$PowerBB->_GET['subject_id']);

					   $update = $PowerBB->core->Update($SubjectArr,'subject');
					}

			//$PowerBB->functions->stop_ajax($PowerBB->_CONF['template']['_CONF']['lang']['File_was_downloaded_successfully']);
			//$PowerBB->functions->redirect('index.php?page=attachments&amp;add_attach_reply=1&amp;subject_id='.$PowerBB->_GET['subject_id'].'');

					// Get the attachment information

					$AttachArr 							= 	array();
					$AttachArr['where']					= 	array();
					$AttachArr['where'][0] 				=	array();
					$AttachArr['where'][0]['name'] 		=	'subject_id';
					$AttachArr['where'][0]['oper'] 		=	'=';
					$AttachArr['where'][0]['value'] 	=	$PowerBB->_GET['subject_id'];
					$AttachArr['where'][1] 				=	array();
					$AttachArr['where'][1]['con']		=	'AND';
					$AttachArr['where'][1]['name'] 		=	'reply';
					$AttachArr['where'][1]['oper'] 		=	'=';
					$AttachArr['where'][1]['value'] 	=	'-'.$PowerBB->_CONF['member_row']['id'];
					$AttachArr['order'] 				=	 array();
					$AttachArr['order']['field'] 		= 	'id';
					$AttachArr['order']['type'] 	    = 	'DESC';
					$PowerBB->_CONF['template']['while']['AttachList'] = $PowerBB->core->GetList($AttachArr,'attach');

                    $PowerBB->template->assign('id',$PowerBB->_GET['subject_id']);
			    	$PowerBB->template->display('attach_list');

			}

	 }

	 function _Delete_Attach_New_Reply()
	{
		global $PowerBB;

       	$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');

		if (empty($PowerBB->_GET['id']))
		{
			echo($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
            exit();
		}

		  $GetAttachArr 					= 	array();
		  $GetAttachArr['where'] 			= 	array('id',$PowerBB->_GET['id']);
		   $Attachinfo = $PowerBB->core->GetInfo($GetAttachArr,'attach');

		   	if (file_exists($Attachinfo['filepath']))
		      {
			   $del = @unlink($Attachinfo['filepath']);
              }


	        // Delete attachment to the database
			$AttachArr 							= 	array();
			$AttachArr['name'] 	        		=  	'id';
	        $AttachArr['where'] 		    	= 	array('id',$PowerBB->_GET['id']);

			$DeleteAttach = $PowerBB->core->Deleted($AttachArr,'attach');

		 if($DeleteAttach)
		  {

				   $GetSubjectAttachArr 					= 	array();
				   $GetSubjectAttachArr['where'] 			= 	array('id',$PowerBB->_GET['subject_id']);
				   $SubjectAttachinfo = $PowerBB->core->GetInfo($GetSubjectAttachArr,'attach');

					if (!$SubjectAttachinfo)
					{
						$SubjectArr 							= 	array();
						$SubjectArr['field'] 					= 	array();
						$SubjectArr['field']['attach_subject'] 	= 	'0';
						$SubjectArr['where'] 					= 	array('id',$PowerBB->_GET['subject_id']);

					   $update = $PowerBB->core->Update($SubjectArr,'subject');
					}


			// Finally , Delete the Attach
	        //$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['File_was_deleted_successfully']);
		   // $PowerBB->functions->redirect('index.php?page=attachments&amp;add_attach_reply=1&amp;subject_id='.$PowerBB->_GET['subject_id'].'');


					$AttachArr 							= 	array();
					$AttachArr['where']					= 	array();
					$AttachArr['where'][0] 				=	array();
					$AttachArr['where'][0]['name'] 		=	'subject_id';
					$AttachArr['where'][0]['oper'] 		=	'=';
					$AttachArr['where'][0]['value'] 	=	$PowerBB->_GET['subject_id'];
					$AttachArr['where'][1] 				=	array();
					$AttachArr['where'][1]['con']		=	'AND';
					$AttachArr['where'][1]['name'] 		=	'reply';
					$AttachArr['where'][1]['oper'] 		=	'=';
					$AttachArr['where'][1]['value'] 	=	'-'.$PowerBB->_CONF['member_row']['id'];
					$AttachArr['order'] 				=	 array();
					$AttachArr['order']['field'] 		= 	'id';
					$AttachArr['order']['type'] 	    = 	'DESC';
					$PowerBB->_CONF['template']['while']['AttachList'] = $PowerBB->core->GetList($AttachArr,'attach');

                    $PowerBB->template->assign('id',$PowerBB->_GET['subject_id']);
			    	$PowerBB->template->display('attach_list');
		  }




	}



}
?>