<?php
(!defined('IN_PowerBB')) ? die() : '';
define('IN_ADMIN',true);
define('DONT_STRIPS_SLIASHES',true);
$CALL_SYSTEM				=	array();
$CALL_SYSTEM['AVATAR'] 		= 	true;


define('CLASS_NAME','PowerBBAvatarMOD');


include('../common.php');
class PowerBBAvatarMOD extends _functions
{
	function run()
	{
		global $PowerBB;

		if ($PowerBB->_CONF['member_permission'])
		{
			$PowerBB->template->display('header');


			if ($PowerBB->_CONF['rows']['group_info']['admincp_avater'] == '0')
			{
			  $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
			}

			if ($PowerBB->_GET['add'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_AddMain();
				}
				elseif ($PowerBB->_GET['start'])
				{
					$this->_AddStart();
				}
			}
			if ($PowerBB->_GET['upload_avatars'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_UploadAvatarsMain();
				}
				elseif ($PowerBB->_GET['start'])
				{
					$this->_UploadAvatarsStart();
				}
			}
			elseif ($PowerBB->_GET['control'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_ControlMain();
				}
			}
			elseif ($PowerBB->_GET['edit'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_EditMain();
				}
				elseif ($PowerBB->_GET['start'])
				{
					$this->_EditStart();
				}
			}
			elseif ($PowerBB->_GET['del'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_DelMain();
				}
				elseif ($PowerBB->_GET['start'])
				{
					$this->_DelStart();
				}
				elseif ($PowerBB->_GET['del_checked'])
				{
					$this->_DeleteAvatars();
				}
			}

			$PowerBB->template->display('footer');
		}
	}

	function _AddMain()
	{
		global $PowerBB;

		$PowerBB->template->display('avatar_add');
	}

	function _AddStart()
	{
		global $PowerBB;

		if (empty($PowerBB->_POST['path']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_fill_in_all_the_information']);
		}

        if ($PowerBB->functions->IsImage($PowerBB->_POST['path'],0) == false)
        {
          $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['INcorrect_information']);
		}

		$AvrArr 			= 	array();
		$AvrArr['field']	=	array();

		$AvrArr['field']['avatar_path'] = $PowerBB->_POST['path'];

		$insert = $PowerBB->avatar->InsertAvatar($AvrArr);

		if ($insert)
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Add_the_image_is_successfully']);
			$PowerBB->functions->redirect('index.php?page=avatar&amp;control=1&amp;main=1');
		}
	}

	function _UploadAvatarsMain()
	{
		global $PowerBB;

		$PowerBB->template->display('avatars_upload');
	}

	function _UploadAvatarsStart()
	{
		global $PowerBB;
		$uploads_dir_i = '../look/images/avatar/upload';
		$uploads_dir = 'look/images/avatar/upload';
		if ($PowerBB->_FILES['files_1']['name'])
		{	    $PowerBB->functions->GetFileExtension($PowerBB->_FILES['files_1']['name']);
		$AvrArr			= 	array();
		$AvrArr['field']	=	array();

		$AvrArr['field']['avatar_path'] 		= 	$uploads_dir . '/' .$PowerBB->_FILES['files_1']['name'];

		$insert = $PowerBB->avatar->InsertAvatar($AvrArr);

		$uploaded = move_uploaded_file($PowerBB->_FILES['files_1']['tmp_name'] , $uploads_dir_i . '/' .$PowerBB->_FILES['files_1']['name']);
        }
		// upload files_2
		if ($PowerBB->_FILES['files_2']['name'])
		{
	    $PowerBB->functions->GetFileExtension($PowerBB->_FILES['files_2']['name']);
		$AvrArr			= 	array();
		$AvrArr['field']	=	array();

		$AvrArr['field']['avatar_path'] 		= 	$uploads_dir . '/' .$PowerBB->_FILES['files_2']['name'];

		$insert = $PowerBB->avatar->InsertAvatar($AvrArr);

		$uploaded = move_uploaded_file($PowerBB->_FILES['files_2']['tmp_name'] , $uploads_dir_i . '/' .$PowerBB->_FILES['files_2']['name']);
       }
        // upload files_3
		if ($PowerBB->_FILES['files_3']['name'])
		{
	    $PowerBB->functions->GetFileExtension($PowerBB->_FILES['files_3']['name']);
		$AvrArr			= 	array();
		$AvrArr['field']	=	array();

		$AvrArr['field']['avatar_path'] 		= 	$uploads_dir . '/' .$PowerBB->_FILES['files_3']['name'];

		$insert = $PowerBB->avatar->InsertAvatar($AvrArr);

		$uploaded = move_uploaded_file($PowerBB->_FILES['files_3']['tmp_name'] , $uploads_dir_i . '/' .$PowerBB->_FILES['files_3']['name']);
        }
		if ($PowerBB->_FILES['files_4']['name'])
		{
	    $PowerBB->functions->GetFileExtension($PowerBB->_FILES['files_4']['name']);
		// upload files_4
		$AvrArr			= 	array();
		$AvrArr['field']	=	array();

		$AvrArr['field']['avatar_path'] 		= 	$uploads_dir . '/' .$PowerBB->_FILES['files_4']['name'];

		$insert = $PowerBB->avatar->InsertAvatar($AvrArr);

		$uploaded = move_uploaded_file($PowerBB->_FILES['files_4']['tmp_name'] , $uploads_dir_i . '/' .$PowerBB->_FILES['files_4']['name']);
        }
		// upload files_5
		if ($PowerBB->_FILES['files_5']['name'])
		{
	    $PowerBB->functions->GetFileExtension($PowerBB->_FILES['files_5']['name']);
		$AvrArr			= 	array();
		$AvrArr['field']	=	array();

		$AvrArr['field']['avatar_path'] 		= 	$uploads_dir . '/' .$PowerBB->_FILES['files_5']['name'];

		$insert = $PowerBB->avatar->InsertAvatar($AvrArr);

		$uploaded = move_uploaded_file($PowerBB->_FILES['files_5']['tmp_name'] , $uploads_dir_i . '/' .$PowerBB->_FILES['files_5']['name']);
        }
		// upload files_6
		if ($PowerBB->_FILES['files_6']['name'])
		{
	    $PowerBB->functions->GetFileExtension($PowerBB->_FILES['files_6']['name']);
		$AvrArr			= 	array();
		$AvrArr['field']	=	array();

		$AvrArr['field']['avatar_path'] 		= 	$uploads_dir . '/' .$PowerBB->_FILES['files_6']['name'];

		$insert = $PowerBB->avatar->InsertAvatar($AvrArr);

		$uploaded = move_uploaded_file($PowerBB->_FILES['files_6']['tmp_name'] , $uploads_dir_i . '/' .$PowerBB->_FILES['files_6']['name']);
        }
		// upload files_7
		if ($PowerBB->_FILES['files_7']['name'])
		{
	    $PowerBB->functions->GetFileExtension($PowerBB->_FILES['files_7']['name']);
		$AvrArr			= 	array();
		$AvrArr['field']	=	array();

		$AvrArr['field']['avatar_path'] 		= 	$uploads_dir . '/' .$PowerBB->_FILES['files_7']['name'];

		$insert = $PowerBB->avatar->InsertAvatar($AvrArr);

		$uploaded = move_uploaded_file($PowerBB->_FILES['files_7']['tmp_name'] , $uploads_dir_i . '/' .$PowerBB->_FILES['files_7']['name']);
        }
		// upload files_8
		if ($PowerBB->_FILES['files_8']['name'])
		{
	    $PowerBB->functions->GetFileExtension($PowerBB->_FILES['files_8']['name']);
		$AvrArr			= 	array();
		$AvrArr['field']	=	array();

		$AvrArr['field']['avatar_path'] 		= 	$uploads_dir . '/' .$PowerBB->_FILES['files_8']['name'];

		$insert = $PowerBB->avatar->InsertAvatar($AvrArr);

		$uploaded = move_uploaded_file($PowerBB->_FILES['files_8']['tmp_name'] , $uploads_dir_i . '/' .$PowerBB->_FILES['files_8']['name']);
        }
		// upload files_9
		if ($PowerBB->_FILES['files_9']['name'])
		{
	    $PowerBB->functions->GetFileExtension($PowerBB->_FILES['files_9']['name']);
		$AvrArr			= 	array();
		$AvrArr['field']	=	array();

		$AvrArr['field']['avatar_path'] 		= 	$uploads_dir . '/' .$PowerBB->_FILES['files_9']['name'];

		$insert = $PowerBB->avatar->InsertAvatar($AvrArr);

		$uploaded = move_uploaded_file($PowerBB->_FILES['files_9']['tmp_name'] , $uploads_dir_i . '/' .$PowerBB->_FILES['files_9']['name']);
       }
		// upload files_10
		if ($PowerBB->_FILES['files_10']['name'])
		{
	    $PowerBB->functions->GetFileExtension($PowerBB->_FILES['files_10']['name']);
		$AvrArr			= 	array();
		$AvrArr['field']	=	array();

		$AvrArr['field']['avatar_path'] 		= 	$uploads_dir . '/' .$PowerBB->_FILES['files_10']['name'];

		$insert = $PowerBB->avatar->InsertAvatar($AvrArr);

		$uploaded = move_uploaded_file($PowerBB->_FILES['files_10']['tmp_name'] , $uploads_dir_i . '/' .$PowerBB->_FILES['files_10']['name']);
        }
		// upload files_11
		if ($PowerBB->_FILES['files_11']['name'])
		{
	    $PowerBB->functions->GetFileExtension($PowerBB->_FILES['files_11']['name']);
		$AvrArr			= 	array();
		$AvrArr['field']	=	array();

		$AvrArr['field']['avatar_path'] 		= 	$uploads_dir . '/' .$PowerBB->_FILES['files_11']['name'];

		$insert = $PowerBB->avatar->InsertAvatar($AvrArr);

		$uploaded = move_uploaded_file($PowerBB->_FILES['files_11']['tmp_name'] , $uploads_dir_i . '/' .$PowerBB->_FILES['files_11']['name']);
        }
		// upload files_12
		if ($PowerBB->_FILES['files_12']['name'])
		{
	    $PowerBB->functions->GetFileExtension($PowerBB->_FILES['files_12']['name']);
		$AvrArr			= 	array();
		$AvrArr['field']	=	array();

		$AvrArr['field']['avatar_path'] 		= 	$uploads_dir . '/' .$PowerBB->_FILES['files_12']['name'];

		$insert = $PowerBB->avatar->InsertAvatar($AvrArr);

		$uploaded = move_uploaded_file($PowerBB->_FILES['files_12']['tmp_name'] , $uploads_dir_i . '/' .$PowerBB->_FILES['files_12']['name']);
        }
        if (!$insert)
		{
			$PowerBB->functions->error_no_foot($PowerBB->_CONF['template']['_CONF']['lang']['Please_select_a_file']);
		}

		if (!$uploaded)
		{
			$PowerBB->functions->error_no_foot($PowerBB->_CONF['template']['_CONF']['lang']['Unable_to_raise_the_profile'].' '.$PowerBB->_FILES['files_1']['tmp_name']);
		}

		if ($uploaded)
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Been_successfully_raise_personal_photos']);
			$PowerBB->functions->redirect('index.php?page=avatar&amp;control=1&amp;main=1');

		}
 }

	function _ControlMain()
	{
		global $PowerBB;

		$AvrArr 					= 	array();
		$AvrArr['order']			=	array();
		$AvrArr['order']['field']	=	'id';
		$AvrArr['order']['type']	=	'DESC';
		$AvrArr['proc'] 			= 	array();
		$AvrArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');

		$PowerBB->_CONF['template']['while']['AvrList'] = $PowerBB->avatar->GetAvatarList($AvrArr);

		$PowerBB->template->display('avatars_main');
	}

	function _EditMain()
	{
		global $PowerBB;

		$PowerBB->_CONF['template']['Inf'] = false;

		$this->check_by_id($PowerBB->_CONF['template']['Inf']);

		$PowerBB->template->display('avatar_edit');
	}

	function _EditStart()
	{
		global $PowerBB;

		$PowerBB->_CONF['template']['Inf'] = false;

		$this->check_by_id($PowerBB->_CONF['template']['Inf']);

		if (empty($PowerBB->_POST['path']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_fill_in_all_the_information']);
		}

        if ($PowerBB->functions->IsImage($PowerBB->_POST['path'],0) == false)
        {
          $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['INcorrect_information']);
		}

		$AvrArr 			= 	array();
		$AvrArr['field'] 	= 	array();

		$AvrArr['field']['avatar_path'] 	= 	$PowerBB->_POST['path'];
		$AvrArr['where']					= 	array('id',$PowerBB->_CONF['template']['Inf']['id']);

		$update = $PowerBB->avatar->UpdateAvatar($AvrArr);

		if ($update)
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Has_been_updated_successfully_to_enlarge']);
			$PowerBB->functions->redirect('index.php?page=avatar&amp;control=1&amp;main=1');
		}
	}

	function _DelMain()
	{
		global $PowerBB;

		$PowerBB->_CONF['template']['Inf'] = false;

		$this->check_by_id($PowerBB->_CONF['template']['Inf']);

		$PowerBB->template->display('avatar_del');
	}

	function _DelStart()
	{
		global $PowerBB;

		$PowerBB->_CONF['template']['Inf'] = false;

		$this->check_by_id($PowerBB->_CONF['template']['Inf']);

        $del_Avatar = @unlink("../".$PowerBB->_CONF['template']['Inf']['avatar_path']);

		$DelArr 			= 	array();
		$DelArr['where'] 	= 	array('id',$PowerBB->_CONF['template']['Inf']['id']);

		$del = $PowerBB->avatar->DeleteAvatar($DelArr);

		if ($del)
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Image_has_been_deleted_successfully']);
			$PowerBB->functions->redirect('index.php?page=avatar&amp;control=1&amp;main=1');
		}
	}

   function _DeleteAvatars()
	{
		global $PowerBB;
		$PowerBB->template->display('header');

		if (empty($PowerBB->_POST['check']))
		{
			$PowerBB->functions->error_no_foot($PowerBB->_CONF['template']['_CONF']['lang']['You_do_not_select_any_photograph_of_the_deletion']);
			$PowerBB->template->display('footer');

		}


       $Avatar_D = $PowerBB->_POST['check'];


       foreach ($Avatar_D as $DeleteAvatar)
       {

		     $AvArr 			= 	array();
			 $AvArr['where']	= 	array('id',intval($DeleteAvatar));

			 $Inf = $PowerBB->avatar->GetAvatarInfo($AvArr);

            $del_Avatar = @unlink("../".$Inf['avatar_path']);

				// Delete avatars from database
				$DelArr 							= 	array();
		        $DelArr['where'] 		    	= 	array('id',intval($DeleteAvatar));

				$Delete =$PowerBB->avatar->DeleteAvatar($DelArr);

       }


            $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Personal_pictures_have_been_deleted_successfully']);
			$PowerBB->functions->redirect('index.php?page=avatar&amp;control=1&amp;main=1');
	}
}

class _functions
{
	function check_by_id(&$Inf)
	{
		global $PowerBB;

		if (empty($PowerBB->_GET['id']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['The_request_is_not_valid']);
		}

		$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');

        $CatArr = $PowerBB->DB->sql_query("SELECT  *   FROM " . $PowerBB->table['avatar'] . " WHERE id = ".$PowerBB->_GET['id']." ");
	    $Inf = $PowerBB->DB->sql_fetch_array($CatArr);

		if ($Inf == false)
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Photo_requested_does_not_exist']);
		}

		$PowerBB->functions->CleanVariable($Inf,'html');
	}
}

?>
