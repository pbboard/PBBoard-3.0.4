<?php
(!defined('IN_PowerBB')) ? die() : '';
define('IN_ADMIN',true);
define('DONT_STRIPS_SLIASHES',true);
$CALL_SYSTEM				=	array();
$CALL_SYSTEM['ICONS'] 		= 	true;



define('CLASS_NAME','PowerBBSmileMOD');

include('../common.php');
class PowerBBSmileMOD extends _functions
{
	function run()
	{
		global $PowerBB;

		if ($PowerBB->_CONF['member_permission'])
		{
			$PowerBB->template->display('header');


			if ($PowerBB->_CONF['rows']['group_info']['admincp_smile'] == '0')
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
			if ($PowerBB->_GET['upload_smiles'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_UploadSmilesMain();
				}
				elseif ($PowerBB->_GET['start'])
				{
					$this->_UploadSmilesStart();
				}
				elseif ($PowerBB->_GET['imagespath_smiles'])
				{
					$this->_UploadImagesPathSmilesStart();
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
					$this->_DeleteSmiles();
				}
			}

			$PowerBB->template->display('footer');
		}
	}

	function _AddMain()
	{
		global $PowerBB;

		$PowerBB->template->display('smile_add');
	}

	function _AddStart()
	{
		global $PowerBB;

		if (empty($PowerBB->_POST['short'])
			or empty($PowerBB->_POST['path']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_fill_in_all_the_information']);
		}

        if ($PowerBB->functions->IsImage($PowerBB->_POST['path'],0) == false)
        {
          $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['INcorrect_information']);
		}

		$SmlArr 			= 	array();
		$SmlArr['field']	=	array();

		$SmlArr['field']['smile_short'] 	= 	$PowerBB->_POST['short'];
		$SmlArr['field']['smile_path'] 		= 	$PowerBB->_POST['path'];

		$insert = $PowerBB->icon->InsertSmile($SmlArr);

		if ($insert)
		{
			$cache = $PowerBB->icon->UpdateSmilesCache(null);

			if ($cache)
			{
				$num = $PowerBB->icon->GetSmilesNumber(null);

				$number = $PowerBB->info->UpdateInfo(array('value'=>$num,'var_name'=>'smiles_number'));

				if ($number)
				{
					$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Smile_has_been_added_successfully']);
					$PowerBB->functions->redirect('index.php?page=smile&amp;control=1&amp;main=1');
				}
			}
		}
	}

	function _UploadSmilesMain()
	{
		global $PowerBB;

		$PowerBB->template->display('smiles_upload');
	}

	function _UploadSmilesStart()
	{
		global $PowerBB;

		$uploads_dir = '../look/images/smiles/upload';
		$uploads_dir_s = 'look/images/smiles/upload';

        $smile_short = $PowerBB->_FILES['files_1']['name'];
        $ext = $PowerBB->functions->GetFileExtension($smile_short);
        $smile_short = str_replace($ext,'',$smile_short);
		if ($PowerBB->_FILES['files_1']['name'])
		{
		$SmlArr 			= 	array();
		$SmlArr['field']	=	array();

		$SmlArr['field']['smile_short'] 	= 	':'.$smile_short.':';
		$SmlArr['field']['smile_path'] 		= 	$uploads_dir_s . '/' .$PowerBB->_FILES['files_1']['name'];

		$insert = $PowerBB->icon->InsertSmile($SmlArr);

		$uploaded = move_uploaded_file($PowerBB->_FILES['files_1']['tmp_name'] , $uploads_dir . '/' .$PowerBB->_FILES['files_1']['name']);
        }
		// upload files_2
		if ($PowerBB->_FILES['files_2']['name'])
		{
        $smile_short = $PowerBB->_FILES['files_2']['name'];
        $ext = $PowerBB->functions->GetFileExtension($smile_short);
        $smile_short = str_replace($ext,'',$smile_short);

		$SmlArr 			= 	array();
		$SmlArr['field']	=	array();

		$SmlArr['field']['smile_short'] 	= 	':'.$smile_short.':';
		$SmlArr['field']['smile_path'] 		= 	$uploads_dir_s . '/' .$PowerBB->_FILES['files_2']['name'];

		$insert = $PowerBB->icon->InsertSmile($SmlArr);

		$uploaded = move_uploaded_file($PowerBB->_FILES['files_2']['tmp_name'] , $uploads_dir . '/' .$PowerBB->_FILES['files_2']['name']);
        }
        // upload files_3
		if ($PowerBB->_FILES['files_3']['name'])
		{
        $smile_short = $PowerBB->_FILES['files_3']['name'];
        $ext = $PowerBB->functions->GetFileExtension($smile_short);
        $smile_short = str_replace($ext,'',$smile_short);

		$SmlArr 			= 	array();
		$SmlArr['field']	=	array();

		$SmlArr['field']['smile_short'] 	= 	':'.$smile_short.':';
		$SmlArr['field']['smile_path'] 		= 	$uploads_dir_s . '/' .$PowerBB->_FILES['files_3']['name'];

		$insert = $PowerBB->icon->InsertSmile($SmlArr);

		$uploaded = move_uploaded_file($PowerBB->_FILES['files_3']['tmp_name'] , $uploads_dir . '/' .$PowerBB->_FILES['files_3']['name']);
       }
		// upload files_4
		if ($PowerBB->_FILES['files_4']['name'])
		{
        $smile_short = $PowerBB->_FILES['files_4']['name'];
        $ext = $PowerBB->functions->GetFileExtension($smile_short);
        $smile_short = str_replace($ext,'',$smile_short);

		$SmlArr 			= 	array();
		$SmlArr['field']	=	array();

		$SmlArr['field']['smile_short'] 	= 	':'.$smile_short.':';
		$SmlArr['field']['smile_path'] 		= 	$uploads_dir_s . '/' .$PowerBB->_FILES['files_4']['name'];

		$insert = $PowerBB->icon->InsertSmile($SmlArr);

		$uploaded = move_uploaded_file($PowerBB->_FILES['files_4']['tmp_name'] , $uploads_dir . '/' .$PowerBB->_FILES['files_4']['name']);
        }
		// upload files_5
		if ($PowerBB->_FILES['files_5']['name'])
		{
        $smile_short = $PowerBB->_FILES['files_5']['name'];
        $ext = $PowerBB->functions->GetFileExtension($smile_short);
        $smile_short = str_replace($ext,'',$smile_short);

		$SmlArr 			= 	array();
		$SmlArr['field']	=	array();

		$SmlArr['field']['smile_short'] 	= 	':'.$smile_short.':';
		$SmlArr['field']['smile_path'] 		= 	$uploads_dir_s . '/' .$PowerBB->_FILES['files_5']['name'];

		$insert = $PowerBB->icon->InsertSmile($SmlArr);

		$uploaded = move_uploaded_file($PowerBB->_FILES['files_5']['tmp_name'] , $uploads_dir . '/' .$PowerBB->_FILES['files_5']['name']);
        }
		// upload files_6
		if ($PowerBB->_FILES['files_6']['name'])
		{
        $smile_short = $PowerBB->_FILES['files_6']['name'];
        $ext = $PowerBB->functions->GetFileExtension($smile_short);
        $smile_short = str_replace($ext,'',$smile_short);

		$SmlArr 			= 	array();
		$SmlArr['field']	=	array();

		$SmlArr['field']['smile_short'] 	= 	':'.$smile_short.':';
		$SmlArr['field']['smile_path'] 		= 	$uploads_dir_s . '/' .$PowerBB->_FILES['files_6']['name'];

		$insert = $PowerBB->icon->InsertSmile($SmlArr);

		$uploaded = move_uploaded_file($PowerBB->_FILES['files_6']['tmp_name'] , $uploads_dir . '/' .$PowerBB->_FILES['files_6']['name']);
        }
		// upload files_7
		if ($PowerBB->_FILES['files_7']['name'])
		{
        $smile_short = $PowerBB->_FILES['files_7']['name'];
        $ext = $PowerBB->functions->GetFileExtension($smile_short);
        $smile_short = str_replace($ext,'',$smile_short);

		$SmlArr 			= 	array();
		$SmlArr['field']	=	array();

		$SmlArr['field']['smile_short'] 	= 	':'.$smile_short.':';
		$SmlArr['field']['smile_path'] 		= 	$uploads_dir_s . '/' .$PowerBB->_FILES['files_7']['name'];

		$insert = $PowerBB->icon->InsertSmile($SmlArr);

		$uploaded = move_uploaded_file($PowerBB->_FILES['files_7']['tmp_name'] , $uploads_dir . '/' .$PowerBB->_FILES['files_7']['name']);
       }
		// upload files_8
		if ($PowerBB->_FILES['files_8']['name'])
		{
        $smile_short = $PowerBB->_FILES['files_8']['name'];
        $ext = $PowerBB->functions->GetFileExtension($smile_short);
        $smile_short = str_replace($ext,'',$smile_short);

		$SmlArr 			= 	array();
		$SmlArr['field']	=	array();

		$SmlArr['field']['smile_short'] 	= 	':'.$smile_short.':';
		$SmlArr['field']['smile_path'] 		= 	$uploads_dir_s . '/' .$PowerBB->_FILES['files_8']['name'];

		$insert = $PowerBB->icon->InsertSmile($SmlArr);

		$uploaded = move_uploaded_file($PowerBB->_FILES['files_8']['tmp_name'] , $uploads_dir . '/' .$PowerBB->_FILES['files_8']['name']);
        }
		// upload files_9
		if ($PowerBB->_FILES['files_9']['name'])
		{
        $smile_short = $PowerBB->_FILES['files_9']['name'];
        $ext = $PowerBB->functions->GetFileExtension($smile_short);
        $smile_short = str_replace($ext,'',$smile_short);

		$SmlArr 			= 	array();
		$SmlArr['field']	=	array();

		$SmlArr['field']['smile_short'] 	= 	':'.$smile_short.':';
		$SmlArr['field']['smile_path'] 		= 	$uploads_dir_s . '/' .$PowerBB->_FILES['files_9']['name'];

		$insert = $PowerBB->icon->InsertSmile($SmlArr);

		$uploaded = move_uploaded_file($PowerBB->_FILES['files_9']['tmp_name'] , $uploads_dir . '/' .$PowerBB->_FILES['files_9']['name']);
        }
		// upload files_10
		if ($PowerBB->_FILES['files_10']['name'])
		{
        $smile_short = $PowerBB->_FILES['files_10']['name'];
        $ext = $PowerBB->functions->GetFileExtension($smile_short);
        $smile_short = str_replace($ext,'',$smile_short);

		$SmlArr 			= 	array();
		$SmlArr['field']	=	array();

		$SmlArr['field']['smile_short'] 	= 	':'.$smile_short.':';
		$SmlArr['field']['smile_path'] 		= 	$uploads_dir_s . '/' .$PowerBB->_FILES['files_10']['name'];

		$insert = $PowerBB->icon->InsertSmile($SmlArr);

		$uploaded = move_uploaded_file($PowerBB->_FILES['files_10']['tmp_name'] , $uploads_dir . '/' .$PowerBB->_FILES['files_10']['name']);
        }
		// upload files_11
		if ($PowerBB->_FILES['files_11']['name'])
		{
        $smile_short = $PowerBB->_FILES['files_11']['name'];
        $ext = $PowerBB->functions->GetFileExtension($smile_short);
        $smile_short = str_replace($ext,'',$smile_short);

		$SmlArr 			= 	array();
		$SmlArr['field']	=	array();

		$SmlArr['field']['smile_short'] 	= 	':'.$smile_short.':';
		$SmlArr['field']['smile_path'] 		= 	$uploads_dir_s . '/' .$PowerBB->_FILES['files_11']['name'];

		$insert = $PowerBB->icon->InsertSmile($SmlArr);

		$uploaded = move_uploaded_file($PowerBB->_FILES['files_11']['tmp_name'] , $uploads_dir . '/' .$PowerBB->_FILES['files_11']['name']);
       }
		// upload files_12
		if ($PowerBB->_FILES['files_12']['name'])
		{
        $smile_short = $PowerBB->_FILES['files_12']['name'];
        $ext = $PowerBB->functions->GetFileExtension($smile_short);
        $smile_short = str_replace($ext,'',$smile_short);

		$SmlArr 			= 	array();
		$SmlArr['field']	=	array();

		$SmlArr['field']['smile_short'] 	= 	':'.$smile_short.':';
		$SmlArr['field']['smile_path'] 		= 	$uploads_dir_s . '/' .$PowerBB->_FILES['files_12']['name'];

		$insert = $PowerBB->icon->InsertSmile($SmlArr);

		$uploaded = move_uploaded_file($PowerBB->_FILES['files_12']['tmp_name'] , $uploads_dir . '/' .$PowerBB->_FILES['files_12']['name']);
      }
        if (!$insert)
		{
			$PowerBB->functions->error_no_foot($PowerBB->_CONF['template']['_CONF']['lang']['Please_select_a_file']);
		}

		if (!$uploaded)
		{
			$PowerBB->functions->error_no_foot($PowerBB->_CONF['template']['_CONF']['lang']['Unable_to_raise_the_profile_Smile'].' '.$PowerBB->_FILES['files_1']['tmp_name']);
		}

		if ($uploaded)
		{
			$cache = $PowerBB->icon->UpdateSmilesCache(null);

			if ($cache)
			{
				$num = $PowerBB->icon->GetSmilesNumber(null);

				$number = $PowerBB->info->UpdateInfo(array('value'=>$num,'var_name'=>'smiles_number'));

				if ($number)
				{
					$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Smiles_has_been_loaded_successfully']);
					$PowerBB->functions->redirect('index.php?page=smile&amp;control=1&amp;main=1');
				}
			}
		}
	}

	function _UploadImagesPathSmilesStart()
	{
		global $PowerBB;

        $files_smile_path	= $PowerBB->_POST['files_imagespath'];
        $PowerBB->_POST['files_imagespath']	= "../".$PowerBB->_POST['files_imagespath'];

	if (!file_exists($PowerBB->_POST['files_imagespath']))
	{
	   $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['smile_file_path_is_incorrect']);
	}
	if ($files_smile_path == "look/images/smiles")
	{
	   $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['smile_file_path_is_incorrect']);
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
                    $smile = $file;
					$smile = str_ireplace(".gif",'',$smile);
					$smile = str_ireplace(".png",'',$smile);
					$smile = str_ireplace(".jpg",'',$smile);
					$smile = str_ireplace(".jpeg",'',$smile);
					$smile = str_ireplace(".wmf",'',$smile);

					if (strstr($file,'.jpg')
						or strstr($file,".gif")
						or strstr($file,'.png')
						or strstr($file,'.jpeg')
						or strstr($file,'.wmf'))
			      	{
						$SmlArr 			= 	array();
						$SmlArr['field']	=	array();

						$SmlArr['field']['smile_short'] 	= 	':'.$smile.':';
						$SmlArr['field']['smile_path'] 		= 	$files_smile_path . '/' .$file;

						$insert = $PowerBB->icon->InsertSmile($SmlArr);
					  }

				}

				closedir($dir);
			}
		}

           $cache = $PowerBB->icon->UpdateSmilesCache(array());

	    $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Smiles_has_been_loaded_successfully']);
	    $PowerBB->functions->redirect('index.php?page=smile&amp;control=1&amp;main=1');
   }


	function _ControlMain()
	{
		global $PowerBB;

		$SmlArr 					= 	array();
		$SmlArr['proc'] 			= 	array();
		$SmlArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');
		$SmlArr['order']			=	array();
		$SmlArr['order']['field']	=	'id';
		$SmlArr['order']['type']	=	'DESC';

		$PowerBB->_CONF['template']['while']['SmlList'] = $PowerBB->icon->GetSmileList($SmlArr);

		$PowerBB->template->display('smiles_main');
	}

	function _EditMain()
	{
		global $PowerBB;

		$this->check_by_id($PowerBB->_CONF['template']['Inf']);

		$PowerBB->template->display('smile_edit');
	}

	function _EditStart()
	{
		global $PowerBB;

		$this->check_by_id($Inf);

		if (empty($PowerBB->_POST['short'])
			or empty($PowerBB->_POST['path']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_fill_in_all_the_information']);
		}

        if ($PowerBB->functions->IsImage($PowerBB->_POST['path'],0) == false)
        {
          $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['INcorrect_information']);
		}
		$SmlArr 			= 	array();
		$SmlArr['field']	=	array();

		$SmlArr['field']['smile_short'] 	= 	$PowerBB->_POST['short'];
		$SmlArr['field']['smile_path'] 		= 	$PowerBB->_POST['path'];
		$SmlArr['where']					= 	array('id',$Inf['id']);

		$update = $PowerBB->icon->UpdateSmile($SmlArr);

		if ($update)
		{
			$cache = $PowerBB->icon->UpdateSmilesCache(array());

			if ($cache)
			{
				$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Smile_has_been_updated_successfully']);
				$PowerBB->functions->redirect('index.php?page=smile&amp;control=1&amp;main=1');
			}
		}
	}

	function _DelMain()
	{
		global $PowerBB;

		$this->check_by_id($PowerBB->_CONF['template']['Inf']);

		$PowerBB->template->display('smile_del');
	}

	function _DelStart()
	{
		global $PowerBB;

		$this->check_by_id($Inf);

		$del_Smile = @unlink("../".$Inf['smile_path']);
		$del = $PowerBB->icon->DeleteSmile(array('id'	=>	$Inf['id']));

		if ($del)
		{
			$cache = $PowerBB->icon->UpdateSmilesCache(array());

			if ($cache)
			{
				$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Smile_has_been_deleted_successfully']);
				$PowerBB->functions->redirect('index.php?page=smile&amp;control=1&amp;main=1');
			}
		}
	}

   function _DeleteSmiles()
	{
		global $PowerBB;
		$PowerBB->template->display('header');

		if (empty($PowerBB->_POST['check']))
		{
			$PowerBB->functions->error_no_foot($PowerBB->_CONF['template']['_CONF']['lang']['You_do_not_select_any_Smile_of_the_deletion']);
			$PowerBB->template->display('footer');

		}


       $Smile_D = $PowerBB->_POST['check'];


       foreach ($Smile_D as $DeleteSmile)
       {

            $Inf = $PowerBB->icon->GetSmileInfo(array('id'	=>	intval($DeleteSmile)));

            $del_Smile = @unlink("../".$Inf['smile_path']);

			// Delete Smiles from database
		    $del = $PowerBB->icon->DeleteSmile(array('id'	=>	intval($DeleteSmile)));

       }
           $cache = $PowerBB->icon->UpdateSmilesCache(array());


            $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Smiles_has_been_deleted_successfully']);
			$PowerBB->functions->redirect('index.php?page=smile&amp;control=1&amp;main=1');
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
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Smile_requested_does_not_exist']);
		}

		$PowerBB->functions->CleanVariable($Inf,'html');
	}
}

?>
