<?php
(!defined('IN_PowerBB')) ? die() : '';
define('IN_ADMIN',true);
define('DONT_STRIPS_SLIASHES',true);
$CALL_SYSTEM				=	array();
$CALL_SYSTEM['ICONS'] 		= 	true;



define('CLASS_NAME','PowerBBIconMOD');

include('../common.php');
class PowerBBIconMOD extends _functions
{
	function run()
	{
		global $PowerBB;

		if ($PowerBB->_CONF['member_permission'])
		{
			$PowerBB->template->display('header');

			if ($PowerBB->_CONF['rows']['group_info']['admincp_icon'] == '0')
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
			if ($PowerBB->_GET['upload_icons'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_UploadIconsMain();
				}
				elseif ($PowerBB->_GET['start'])
				{
					$this->_UploadIconsStart();
				}
				elseif ($PowerBB->_GET['imagespath_icons'])
				{
					$this->_UploadImagesPathIconsStart();
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
					$this->_DeleteIcons();
				}
			}

			$PowerBB->template->display('footer');
		}
	}

	function _AddMain()
	{
		global $PowerBB;

		$PowerBB->template->display('icon_add');
	}

	function _AddStart()
	{
		global $PowerBB;

		if (empty($PowerBB->_POST['path']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_fill_in_all_the_information']);
		}

		$SmlArr 				= 	array();
		$SmlArr['field']		=	array();

		$SmlArr['field']['smile_path'] 	= 	$PowerBB->_POST['path'];

		$insert = $PowerBB->icon->InsertIcon($SmlArr);

		if ($insert)
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['icon_has_been_added_successfully']);
			$PowerBB->functions->redirect('index.php?page=icon&amp;control=1&amp;main=1');
		}
	}

	function _UploadIconsMain()
	{
		global $PowerBB;

		$PowerBB->template->display('icons_upload');
	}

	function _UploadIconsStart()
	{
		global $PowerBB;

		$uploads_dir_i = '../look/images/icons/upload';
		$uploads_dir = 'look/images/icons/upload';

		if ($PowerBB->_FILES['files_1']['name'])
		{        $PowerBB->functions->GetFileExtension($PowerBB->_FILES['files_1']['name']);

		$IcnArr 			= 	array();
		$IcnArr['field']	=	array();

		$IcnArr['field']['smile_path'] 		= 	$uploads_dir . '/' .$PowerBB->_FILES['files_1']['name'];

		$insert = $PowerBB->icon->InsertIcon($IcnArr);

		$uploaded = move_uploaded_file($PowerBB->_FILES['files_1']['tmp_name'] , $uploads_dir_i . '/' .$PowerBB->_FILES['files_1']['name']);
       }

		if ($PowerBB->_FILES['files_2']['name'])
		{
		// upload files_2
	    $PowerBB->functions->GetFileExtension($PowerBB->_FILES['files_2']['name']);

		$IcnArr 			= 	array();
		$IcnArr['field']	=	array();

		$IcnArr['field']['smile_path'] 		= 	$uploads_dir . '/' .$PowerBB->_FILES['files_2']['name'];

		$insert = $PowerBB->icon->InsertIcon($IcnArr);

		$uploaded = move_uploaded_file($PowerBB->_FILES['files_2']['tmp_name'] , $uploads_dir_i . '/' .$PowerBB->_FILES['files_2']['name']);
        }

		if ($PowerBB->_FILES['files_3']['name'])
		{
        // upload files_3
	    $PowerBB->functions->GetFileExtension($PowerBB->_FILES['files_3']['name']);
		$IcnArr 			= 	array();
		$IcnArr['field']	=	array();

		$IcnArr['field']['smile_path'] 		= 	$uploads_dir . '/' .$PowerBB->_FILES['files_3']['name'];

		$insert = $PowerBB->icon->InsertIcon($IcnArr);

		$uploaded = move_uploaded_file($PowerBB->_FILES['files_3']['tmp_name'] , $uploads_dir_i . '/' .$PowerBB->_FILES['files_3']['name']);
        }
		if ($PowerBB->_FILES['files_4']['name'])
		{
		// upload files_4
	    $PowerBB->functions->GetFileExtension($PowerBB->_FILES['files_4']['name']);
		$IcnArr 			= 	array();
		$IcnArr['field']	=	array();

		$IcnArr['field']['smile_path'] 		= 	$uploads_dir . '/' .$PowerBB->_FILES['files_4']['name'];

		$insert = $PowerBB->icon->InsertIcon($IcnArr);

		$uploaded = move_uploaded_file($PowerBB->_FILES['files_4']['tmp_name'] , $uploads_dir_i . '/' .$PowerBB->_FILES['files_4']['name']);
       }
		if ($PowerBB->_FILES['files_5']['name'])
		{
		// upload files_5
	    $PowerBB->functions->GetFileExtension($PowerBB->_FILES['files_5']['name']);
		$IcnArr 			= 	array();
		$IcnArr['field']	=	array();

		$IcnArr['field']['smile_path'] 		= 	$uploads_dir . '/' .$PowerBB->_FILES['files_5']['name'];

		$insert = $PowerBB->icon->InsertIcon($IcnArr);

		$uploaded = move_uploaded_file($PowerBB->_FILES['files_5']['tmp_name'] , $uploads_dir_i . '/' .$PowerBB->_FILES['files_5']['name']);
        }
		if ($PowerBB->_FILES['files_6']['name'])
		{
		// upload files_6
	    $PowerBB->functions->GetFileExtension($PowerBB->_FILES['files_6']['name']);
		$IcnArr 			= 	array();
		$IcnArr['field']	=	array();

		$IcnArr['field']['smile_path'] 		= 	$uploads_dir . '/' .$PowerBB->_FILES['files_6']['name'];

		$insert = $PowerBB->icon->InsertIcon($IcnArr);

		$uploaded = move_uploaded_file($PowerBB->_FILES['files_6']['tmp_name'] , $uploads_dir_i . '/' .$PowerBB->_FILES['files_6']['name']);
       }
		if ($PowerBB->_FILES['files_7']['name'])
		{
		// upload files_7
	    $PowerBB->functions->GetFileExtension($PowerBB->_FILES['files_7']['name']);
		$IcnArr 			= 	array();
		$IcnArr['field']	=	array();

		$IcnArr['field']['smile_path'] 		= 	$uploads_dir . '/' .$PowerBB->_FILES['files_7']['name'];

		$insert = $PowerBB->icon->InsertIcon($IcnArr);

		$uploaded = move_uploaded_file($PowerBB->_FILES['files_7']['tmp_name'] , $uploads_dir_i . '/' .$PowerBB->_FILES['files_7']['name']);
        }
		if ($PowerBB->_FILES['files_8']['name'])
		{
		// upload files_8
	    $PowerBB->functions->GetFileExtension($PowerBB->_FILES['files_8']['name']);
		$IcnArr 			= 	array();
		$IcnArr['field']	=	array();

		$IcnArr['field']['smile_path'] 		= 	$uploads_dir . '/' .$PowerBB->_FILES['files_8']['name'];

		$insert = $PowerBB->icon->InsertIcon($IcnArr);

		$uploaded = move_uploaded_file($PowerBB->_FILES['files_8']['tmp_name'] , $uploads_dir_i . '/' .$PowerBB->_FILES['files_8']['name']);
       }
       if ($PowerBB->_FILES['files_9']['name'])
	   {
		// upload files_9
	    $PowerBB->functions->GetFileExtension($PowerBB->_FILES['files_9']['name']);
		$IcnArr 			= 	array();
		$IcnArr['field']	=	array();

		$IcnArr['field']['smile_path'] 		= 	$uploads_dir . '/' .$PowerBB->_FILES['files_9']['name'];

		$insert = $PowerBB->icon->InsertIcon($IcnArr);

		$uploaded = move_uploaded_file($PowerBB->_FILES['files_9']['tmp_name'] , $uploads_dir_i . '/' .$PowerBB->_FILES['files_9']['name']);
       }
       if ($PowerBB->_FILES['files_10']['name'])
	   {
		// upload files_10
	    $PowerBB->functions->GetFileExtension($PowerBB->_FILES['files_10']['name']);
		$IcnArr 			= 	array();
		$IcnArr['field']	=	array();

		$IcnArr['field']['smile_path'] 		= 	$uploads_dir . '/' .$PowerBB->_FILES['files_10']['name'];

		$insert = $PowerBB->icon->InsertIcon($IcnArr);

		$uploaded = move_uploaded_file($PowerBB->_FILES['files_10']['tmp_name'] , $uploads_dir_i . '/' .$PowerBB->_FILES['files_10']['name']);
        }
       if ($PowerBB->_FILES['files_11']['name'])
	   {
		// upload files_11
	    $PowerBB->functions->GetFileExtension($PowerBB->_FILES['files_11']['name']);
		$IcnArr 			= 	array();
		$IcnArr['field']	=	array();

		$IcnArr['field']['smile_path'] 		= 	$uploads_dir . '/' .$PowerBB->_FILES['files_11']['name'];

		$insert = $PowerBB->icon->InsertIcon($IcnArr);

		$uploaded = move_uploaded_file($PowerBB->_FILES['files_11']['tmp_name'] , $uploads_dir_i . '/' .$PowerBB->_FILES['files_11']['name']);
        }
       if ($PowerBB->_FILES['files_12']['name'])
	   {
		// upload files_12
	    $PowerBB->functions->GetFileExtension($PowerBB->_FILES['files_12']['name']);
		$IcnArr 			= 	array();
		$IcnArr['field']	=	array();

		$IcnArr['field']['smile_path'] 		= 	$uploads_dir . '/' .$PowerBB->_FILES['files_12']['name'];

		$insert = $PowerBB->icon->InsertIcon($IcnArr);

		$uploaded = move_uploaded_file($PowerBB->_FILES['files_12']['tmp_name'] , $uploads_dir_i . '/' .$PowerBB->_FILES['files_12']['name']);
       }
        if (!$insert)
		{
			$PowerBB->functions->error_no_foot($PowerBB->_CONF['template']['_CONF']['lang']['Please_select_a_file']);
		}

		if (!$uploaded)
		{
			$PowerBB->functions->error_no_foot($PowerBB->_CONF['template']['_CONF']['lang']['Unable_to_raise_the_profile_icon'].$PowerBB->_FILES['files_1']['tmp_name']);
		}

		if ($uploaded)
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['icons_has_been_loaded_successfully']);
			$PowerBB->functions->redirect('index.php?page=icon&amp;control=1&amp;main=1');
		}
	}

	function _UploadImagesPathIconsStart()
	{
		global $PowerBB;

        $files_icon_path	= $PowerBB->_POST['files_imagespath'];
        $PowerBB->_POST['files_imagespath']	= "../".$PowerBB->_POST['files_imagespath'];

	if (!file_exists($PowerBB->_POST['files_imagespath']))
	{
	   $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['icon_file_path_is_incorrect']);
	}
	if ($files_icon_path == "look/images/icons")
	{
	   $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['icon_file_path_is_incorrect']);
	}
		$files_imagespathDir = ($PowerBB->_POST['files_imagespath']);

		if (is_dir($files_imagespathDir))
		{
			$dir = opendir($files_imagespathDir);

			if ($dir)
			{
				while (($file = readdir($dir)) !== false)
				{
					if ($file == '.'
						or $file == '..')
					{
						continue;
					}

					if (strstr($file,'.jpg')
						or strstr($file,".gif")
						or strstr($file,'.png')
						or strstr($file,'.jpeg')
						or strstr($file,'.wmf'))
			      	{

						$IcnArr 			= 	array();
						$IcnArr['field']	=	array();

						$IcnArr['field']['smile_path'] 		= 	$files_icon_path . '/' .$file;

						$insert = $PowerBB->icon->InsertIcon($IcnArr);

					  }

				}

				closedir($dir);
			}
		}

			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['icons_has_been_loaded_successfully']);
			$PowerBB->functions->redirect('index.php?page=icon&amp;control=1&amp;main=1');
   }

	function _ControlMain()
	{
		global $PowerBB;

		$IcnArr 					= 	array();
		$IcnArr['order']			=	array();
		$IcnArr['order']['field']	=	'id';
		$IcnArr['order']['type']	=	'DESC';
		$IcnArr['proc'] 			= 	array();
		$IcnArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');

		$PowerBB->_CONF['template']['while']['IcnList'] = $PowerBB->icon->GetIconList($IcnArr);

		$PowerBB->template->display('icons_main');
	}

	function _EditMain()
	{
		global $PowerBB;

		$PowerBB->_CONF['template']['Inf'] = false;

		$this->check_by_id($PowerBB->_CONF['template']['Inf']);

		$PowerBB->template->display('icon_edit');
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

		$IcnArr 			= 	array();
		$IcnArr['field']	=	array();

		$IcnArr['field']['smile_path'] 	= 	$PowerBB->_POST['path'];
		$IcnArr['where']				= 	array('id',$PowerBB->_CONF['template']['Inf']['id']);

		$update = $PowerBB->icon->UpdateIcon($IcnArr);

		if ($update)
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['icon_has_been_updated_successfully']);
			$PowerBB->functions->redirect('index.php?page=icon&amp;control=1&amp;main=1');
		}
	}

	function _DelMain()
	{
		global $PowerBB;

		$PowerBB->_CONF['template']['Inf'] = false;

		$this->check_by_id($PowerBB->_CONF['template']['Inf']);

		$PowerBB->template->display('icon_del');
	}

	function _DelStart()
	{
		global $PowerBB;

		$PowerBB->_CONF['template']['Inf'] = false;

		$this->check_by_id($PowerBB->_CONF['template']['Inf']);
        $del_Icon = @unlink("../".$PowerBB->_CONF['template']['Inf']['smile_path']);
		$del = $PowerBB->icon->DeleteIcon(array('id'	=>	$PowerBB->_CONF['template']['Inf']['id']));

		if ($del)
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['icon_has_been_deleted_successfully']);
			$PowerBB->functions->redirect('index.php?page=icon&amp;control=1&amp;main=1');
		}
	}

   function _DeleteIcons()
	{
		global $PowerBB;
		$PowerBB->template->display('header');

		if (empty($PowerBB->_POST['check']))
		{
			$PowerBB->functions->error_no_foot($PowerBB->_CONF['template']['_CONF']['lang']['You_do_not_select_any_icon_of_the_deletion']);
			$PowerBB->template->display('footer');

		}


       $Icon_D = $PowerBB->_POST['check'];


       foreach ($Icon_D as $DeleteIcon)
       {

            $Inf = $PowerBB->icon->GetIconInfo(array('id'	=>	intval($DeleteIcon)));

            $del_Icon = @unlink("../".$Inf['smile_path']);

			// Delete Icons from database
		    $del = $PowerBB->icon->DeleteIcon(array('id'	=>	intval($DeleteIcon)));


       }


            $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['icons_has_been_deleted_successfully']);
			$PowerBB->functions->redirect('index.php?page=icon&amp;control=1&amp;main=1');
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

			$CatArr = $PowerBB->DB->sql_query("SELECT  *   FROM " . $PowerBB->table['smiles'] . " WHERE id = ".$PowerBB->_GET['id']." ");
		    $Inf = $PowerBB->DB->sql_fetch_array($CatArr);


		if ($Inf == false)
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['icon_requested_does_not_exist']);
		}

		$PowerBB->functions->CleanVariable($Inf,'html');
	}
}

?>
