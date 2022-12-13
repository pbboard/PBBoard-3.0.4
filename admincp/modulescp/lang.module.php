<?php
(!defined('IN_PowerBB')) ? die() : '';
define('IN_ADMIN',true);
define('DONT_STRIPS_SLIASHES',true);
$CALL_SYSTEM			=	array();
$CALL_SYSTEM['LANG'] 	= 	true;



define('CLASS_NAME','PowerBBLangMOD');

include('../common.php');
class PowerBBLangMOD extends _functions
{
	function run()
	{
		global $PowerBB;

            if (!$PowerBB->_GET['create_phrase_language'])
			{
			 $PowerBB->template->display('header');
			}
			if ($PowerBB->_CONF['rows']['group_info']['admincp_lang'] == '0')
			{
			  $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
			}


		if ($PowerBB->_CONF['member_permission'])
		{
			if ($PowerBB->_GET['add'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_AddMain();
				}
				elseif ($PowerBB->_GET['start'])
				{
					$this->_ImportStart();
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
			}
			elseif ($PowerBB->_GET['add_fieldname'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_AddFieldnameMain();
				}
				elseif ($PowerBB->_GET['start'])
				{
					$this->_FieldnameStart();
				}
			}
			elseif ($PowerBB->_GET['search_fieldname'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_SearchMain();
				}
				elseif ($PowerBB->_GET['start'])
				{
					$this->_SearchStart();
				}
			}
			elseif ($PowerBB->_GET['control_fieldname'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_ControlFieldnameMain();
				}
				elseif ($PowerBB->_GET['edit_fieldname'])
				{
					$this->_EditFieldnameMain();
				}
				elseif ($PowerBB->_GET['start_edit'])
				{
					$this->_EditFieldnameStart();
				}
			}
			elseif ($PowerBB->_GET['create_phrase_language'])
			{
				if ($PowerBB->_GET['download_language'])
				{
					$this->_DownloadLanguage();
				}
				else
				{
				$this->_create_phrase_language_Start();
				}
			}
			if ($PowerBB->_GET['default'])
			{
				$this->_DefaultMain();
			}

			$PowerBB->template->display('footer');
		}
	}

	function _DownloadLanguage()
	{
		global $PowerBB;
		$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');

		if (empty($PowerBB->_GET['id']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}
		$LangArr 			= 	array();
		$LangArr['where'] 	= 	array('id',$PowerBB->_GET['id']);

		$LangInfo = $PowerBB->core->GetInfo($LangArr,'lang');

		if (!$LangInfo)
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Lang_requested_does_not_exist']);
		}

		$LangInfo['lang_title'] = str_replace(" ", "_", $LangInfo['lang_title']);
		$lang_title    = $LangInfo['lang_title'];
		$lang_dir    = $LangInfo['lang_path'];
		$langid = $PowerBB->_GET['id'];
        $LangInfo_query = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['phrase_language'] . " WHERE languageid = '$langid' ORDER BY phraseid ASC");

             while ($getLang_row = $PowerBB->DB->sql_fetch_array($LangInfo_query))
             {
                $text = $getLang_row['text'];
	            $text = str_replace("&#39;", "'", $text);
		        $languageid = $getLang_row['languageid'];
				$varname    = $getLang_row['varname'];
				$fieldname  = $getLang_row['fieldname'];
		        $version    = $getLang_row['version'];
		        $username   = $getLang_row['username'];
		        $dateline   = $PowerBB->_CONF['now'];
		        $product    = $getLang_row['product'];
		        $xml .= "<phrase name=\"$varname\" pbbversion=\"$version\" product=\"$product\" username=\"$username\" date=\"$dateline\" fieldname=\"$fieldname\"><![CDATA[$text]]></phrase>\r\n";
             }
	    $filename    = "PBBoard-language";
		$xmlup = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n<language name=\"$lang_title\" version=\"$version\" dir=\"$lang_dir\" product=\"$product\">\r\n<phrasegroup>\r\n";
        $xmldun = "</phrasegroup>\r\n</language>\r\n";
		header("Content-disposition: attachment; filename=".$lang_title.".xml");
		header("Content-type: application/octet-stream");
		header("Content-Length: ".strlen($xmlup.$xml.$xmldun));
		header("Pragma: no-cache");
		header("Expires: 0");
		echo $xmlup.$xml.$xmldun;
		exit;
	}


	function _create_phrase_language_Start()
	{
		global $PowerBB;
		$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');

		if (empty($PowerBB->_GET['id']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}
		$LangArr 			= 	array();
		$LangArr['where'] 	= 	array('id',$PowerBB->_GET['id']);

		$LangInfo = $PowerBB->core->GetInfo($LangArr,'lang');

		if (!$LangInfo)
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Lang_requested_does_not_exist']);
		}

      $langid = $PowerBB->_GET['id'];
		$lang_title = $LangInfo['lang_title'];
		$lang_dir = $LangInfo['lang_path'];

	    $filename = "../lang/".$lang_path."/language_Admin.php";
	    include($filename);
		$kv = array();
		foreach ($lang as $var_name => $value) {
		$kv[] = "$var_name=$value";
		 if ($value !='')
		 {

            $value = str_replace("&#39;", "'", $value);
	        $languageid = $langid;
			$varname = $var_name;
			$fieldname = "admincp";
	        $version = "3.0.0";
	        $username = "Soliman";
	        $dateline = $PowerBB->_CONF['now'];
	        $product = "PBBoard";
	        $text  = $value;
	        $xml .= "<phrase name=\"$varname\" pbbversion=\"$version\" product=\"$product\" username=\"$username\" date=\"$dateline\" fieldname=\"$fieldname\"><![CDATA[$text]]></phrase>\r\n";
		 }
		}
		$xmlup = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n<language name=\"MASTER LANGUAGE\" version=\"3.0.0\" dir=\"$lang_dir\" product=\"pbboard\">\r\n<phrasegroup>\r\n";
        $xmldun = "</phrasegroup>\r\n</language>\r\n";
		header("Content-disposition: attachment; filename=PBBoard-language-admincp-".$LangInfo['lang_path'].".xml");
		header("Content-type: application/octet-stream");
		header("Content-Length: ".strlen($xmlup.$xml.$xmldun));
		header("Pragma: no-cache");
		header("Expires: 0");
		echo $xmlup.$xml.$xmldun;
		exit;

	}

   function _ImportStart()
   {
     global $PowerBB;


		if (empty($PowerBB->_FILES['files']['name'])
			AND !file_exists("../".$PowerBB->_POST['serverfile']))
		{
			$PowerBB->functions->error_no_foot($PowerBB->_CONF['template']['_CONF']['lang']['do_not_choose_to_file']);
		}

		if (empty($PowerBB->_FILES['files']['name']))
		{
			if (file_exists("../".$PowerBB->_POST['serverfile']))
			{
				$ext = $PowerBB->functions->GetFileExtension($PowerBB->_POST['serverfile']);

				if ($ext != '.xml')
				{
					$PowerBB->functions->error_no_foot($PowerBB->_CONF['template']['_CONF']['lang']['error_xml_file']);
				}

			   $xml_code = file_get_contents("../".$PowerBB->_POST['serverfile']);

			}

		}
		else
		{

			$ext = $PowerBB->functions->GetFileExtension($PowerBB->_FILES['files']['name']);

			if ($ext != '.xml')
			{
				$PowerBB->functions->error_no_foot($PowerBB->_CONF['template']['_CONF']['lang']['error_xml_file']);
			}

			$uploads_dir = '../cache';
			$uploaded = move_uploaded_file($PowerBB->_FILES['files']['tmp_name'] , $uploads_dir . '/' .$PowerBB->_FILES['files']['name']);

			if (!$uploaded)
			{
				$PowerBB->functions->error_no_foot($PowerBB->_CONF['template']['_CONF']['lang']['is_unable_permison_folder_cache'].' '.$PowerBB->_FILES['files']['tmp_name']);
			}

			$xml_code = file_get_contents($uploads_dir.'/'.$PowerBB->_FILES['files']['name']);

		}

		$import = $PowerBB->functions->xml_array($xml_code);
		$title = $import['language_attr']['name'];
		$dir = $import['language_attr']['dir'];

		$pbbversion = $import['language_attr']['version'];

	         if ($PowerBB->_POST['anyversion'] == '0')
	         {
			  if ($pbbversion != $PowerBB->_CONF['info_row']['MySBB_version'])
			  {
		      $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['version_Different']);
	          }
	 	  	}

				$Languages = $import['language']['phrasegroup'];
				$language_number = sizeof($import['language']['phrasegroup']['phrase'])/2;
		if ($PowerBB->core->Is(array('where' => array('lang_title',$title)),'lang'))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['This_language']. ' ' . $title . ' ' .$PowerBB->_CONF['template']['_CONF']['lang']['Already_added']);
		}
		else
	    {

	       	$LangArr 					= 	array();
			$LangArr['field']			=	array();

			$LangArr['field']['lang_title'] 	= 	$title;
			$LangArr['field']['lang_order'] 	= 	$PowerBB->_POST['order'];
			$LangArr['field']['lang_on'] 		= 	$PowerBB->_POST['lang_on'];
			$LangArr['field']['lang_path'] 	= 	$dir;

			$insert = $PowerBB->lang->InsertLang($LangArr);
			$langid = $PowerBB->DB->sql_insert_id();
		            $x = 0;
     			while ($x < $language_number)
     			{
						$varname = $Languages['phrase'][$x.'_attr']['name'];
						$fieldname = $Languages['phrase'][$x.'_attr']['fieldname'];
						$version = $Languages['phrase'][$x.'_attr']['pbbversion'];
						$text = $Languages['phrase'][$x];
						$product = $Languages['phrase'][$x.'_attr']['product'];
						$dateline = $Languages['phrase'][$x.'_attr']['date'];
						$username = $Languages['phrase'][$x.'_attr']['username'];
			            $text = str_replace("'", "&#39;", $text);
                        $text = stripslashes($text);

						$InsertLanguagesArr	=	array();
						$InsertLanguagesArr['field']	=	array();
						$InsertLanguagesArr['field']['languageid']	=	$langid;
						$InsertLanguagesArr['field']['varname']  	=	$varname;
						$InsertLanguagesArr['field']['fieldname']	=	$fieldname;
						$InsertLanguagesArr['field']['text']	    =	$text;
						$InsertLanguagesArr['field']['dateline']	=	$dateline;
						$InsertLanguagesArr['field']['username']	=	$username;
						$InsertLanguagesArr['field']['version']	    =	$version;
						$InsertLanguagesArr['field']['product']	    =	$product;
						$insertLanguages = $PowerBB->core->Insert($InsertLanguagesArr,'phrase_language');
                     $x += 1;
     			}

           if ($insertLanguages)
			{
		        $delLanguages = $PowerBB->DB->sql_query("DELETE FROM " . $PowerBB->table['phrase_language'] . " WHERE languageid = '$langid' and varname = ''");
				$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['language_has_been_added_successfully']);
				unlink($uploads_dir.'/'.$PowerBB->_FILES['files']['name']);
             	$PowerBB->functions->redirect2('index.php?page=lang&amp;control=1&amp;main=1',3);

			}

        }
    }

	function _AddMain()
	{
		global $PowerBB;

		$LangDir = ('lang/');

		if (is_dir($LangDir))
		{
			$dir = opendir($LangDir);

			if ($dir)
			{
				while (($file = readdir($dir)) !== false)
				{
					if ($file == '.'
						or $file == '..')
					{
						continue;
					}

					$LanguageList[]['filename'] = $file;
				}

				closedir($dir);
			}
		}

		$PowerBB->_CONF['template']['foreach']['LanguageList'] = $LanguageList;

		 $TotalLanguageNm = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['lang'] . " WHERE id"));
		 $PowerBB->template->assign('order',$TotalLanguageNm+1);

		$PowerBB->template->display('lang_add');
	}

	function _AddStart()
	{
		global $PowerBB;

		if (empty($PowerBB->_POST['name']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_write_the_name_of_Language']);
		}

		if (empty($PowerBB->_POST['lang_order']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_enter_the_order_number_language']);
		}

		if ($PowerBB->lang->GetLangList(array('where' => array('lang_title',$PowerBB->_POST['name']))))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['please_write_the_Language_name_of_another']);
		}

		if ($PowerBB->_POST['default'] == '1')
	     {
		   $update = array();
           $update = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['lang_path'],'var_name'=>'def_lang'));
         }



		$LangArr 					= 	array();
		$LangArr['field']			=	array();

		$LangArr['field']['lang_title'] 	= 	$PowerBB->_POST['name'];
		$LangArr['field']['lang_order'] 	= 	$PowerBB->_POST['lang_order'];
		$LangArr['field']['lang_on'] 		= 	$PowerBB->_POST['lang_on'];
        $LangArr['field']['lang_path'] 	    = 	$PowerBB->_POST['dir'];

		$insert = $PowerBB->lang->InsertLang($LangArr);


		if ($insert)
		{
               	$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['language_has_been_added_successfully']);
	            $PowerBB->functions->redirect('index.php?page=lang&amp;control=1&amp;main=1');

         }

	}

	function _ControlMain()
	{
		global $PowerBB;

		$LangArr 					= 	array();
		$LangArr['proc'] 			= 	array();
		$LangArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');
		$LangArr['order']			=	array();
		$LangArr['order']['field']	=	'lang_order';
		$LangArr['order']['type']	=	'ASC ';

		$PowerBB->_CONF['template']['while']['LangList'] = $PowerBB->lang->GetLangList($LangArr);

		$LangDefArr 					= 	array();
		$LangDefArr['proc'] 			= 	array();
		$LangDefArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');
		$LangDefArr['order']			=	array();
		$LangDefArr['order']['field']	=	'id';
		$LangDefArr['order']['type']	=	'DESC';

		$PowerBB->_CONF['template']['while']['LangDef'] = $PowerBB->lang->GetLangList($LangDefArr);

		$PowerBB->template->display('lang_main');
	}

	function _EditMain()
	{
		global $PowerBB;

		$this->check_by_id($PowerBB->_CONF['template']['Inf']);

		$PowerBB->template->display('lang_edit');
	}

	function _EditStart()
	{
		global $PowerBB;

		$this->check_by_id($Inf);

		if (empty($PowerBB->_POST['name']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_write_the_name_of_Language']);
		}

		if (empty($PowerBB->_POST['lang_order']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_enter_the_order_number_language']);
		}

		//////////

		$LangArr 					= 	array();
		$LangArr['field']			=	array();

		$LangArr['field']['lang_title'] 	= 	$PowerBB->_POST['name'];
		$LangArr['field']['lang_order'] 	= 	$PowerBB->_POST['lang_order'];
		$LangArr['field']['lang_on'] 		= 	$PowerBB->_POST['lang_on'];
		$LangArr['field']['lang_path'] 		= 	$PowerBB->_POST['dir'];
		$LangArr['where']					= 	array('id',$Inf['id']);

		$update = $PowerBB->lang->UpdateLang($LangArr);


		//////////

		if ($update)
		{
  			if ($PowerBB->_POST['lang_on'] == '0')
			{
			$UpdateMemArr 				= 	array();
			$UpdateMemArr['field']		=	array();

			$UpdateMemArr['field']['lang'] 	= 	$PowerBB->_CONF['info_row']['def_lang'];
			$UpdateMemArr['where'] 				= 	array('lang',$Inf['id']);

			$update = $PowerBB->member->UpdateMember($UpdateMemArr);
			}

			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['language_has_been_updated_successfully']);
			$PowerBB->functions->redirect('index.php?page=lang&amp;control=1&amp;main=1');

			//////////
		}

		//////////
	}

	function _DelMain()
	{
		global $PowerBB;

		$this->check_by_id($PowerBB->_CONF['template']['Inf']);

		$PowerBB->template->display('lang_del');
	}

	function _DelStart()
	{
		global $PowerBB;

		$this->check_by_id($Inf);

	     if ($PowerBB->_CONF['info_row']['def_lang'] == $Inf['id'])
	     {
             $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['You_can_not_delete_the_default_language']);
         }

         //
		if ($PowerBB->member->IsMember(array('where' => array('lang',$Inf['id']))))
		{
		 $UpdateArr 				                = 	array();
		 $UpdateArr['field'] 	                    = 	array();
		 $UpdateArr['field']['lang'] 			    = 	'1';
		 $UpdateArr['where']						=	array('lang',$Inf['id']);

		 $update = $PowerBB->core->Update($UpdateArr,'member');
		}

		$DelArr 			= 	array();
		$DelArr['where'] 	= 	array('id',$Inf['id']);

		$del = $PowerBB->lang->DeleteLang($DelArr);

		if ($del)
		{

		$DelLanguageArr 			= 	array();
		$DelLanguageArr['where'] 	= 	array('languageid',$Inf['id']);
		$delLanguages = $PowerBB->core->Deleted($DelLanguageArr,'phrase_language');

			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['language_has_been_deleted_successfully']);
			$PowerBB->functions->redirect('index.php?page=lang&amp;control=1&amp;main=1');
		}
	}

	function _DefaultMain()
	{
		global $PowerBB;

        $update = array();
        $update = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['default'],'var_name'=>'def_lang'));


		//////////

		if ($update)
		{
			//////////

           $getmember_query = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['member'] . " ORDER BY id DESC");

             while ($getmember_row = $PowerBB->DB->sql_fetch_array($getmember_query))
             {

				$UpdateArr 				                = 	array();
				$UpdateArr['field'] 	                    = 	array();
				$UpdateArr['field']['lang'] 			    = 	$PowerBB->_POST['default'];
				$UpdateArr['where']						=	array('id',$getmember_row['id']);

				$update = $PowerBB->core->Update($UpdateArr,'member');

             }

            $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['language_has_been_updated_successfully']);
			$PowerBB->functions->redirect('index.php?page=lang&amp;control=1&amp;main=1');

			//////////
		}

    }

	function _AddFieldnameMain()
	{
		global $PowerBB;

		// Get lang list
		$LangListArr 							= 	array();

		// Clean data
		$LangListArr['proc']					=	array();
		$LangListArr['proc']['*']				=	array('method'=>'clean','param'=>'html');

		// Where setup
		$LangListArr['where'][0]				=	array();
		$LangListArr['where'][0]['con']		=	'AND';
		$LangListArr['where'][0]['name']		=	'id';
		$LangListArr['where'][0]['oper']		=	'<>';
		$LangListArr['where'][0]['value']		=	$PowerBB->_CONF['info_row']['def_lang'];

		// Order setup
		$LangListArr['order'] 					= 	array();
		$LangListArr['order']['field'] 		= 	'lang_order';
		$LangListArr['order']['type'] 			= 	'ASC';

		$PowerBB->_CONF['template']['while']['LangList'] = $PowerBB->lang->GetLangList($LangListArr);

		$PowerBB->template->display('lang_add_fieldname');
	}

	function _FieldnameStart()
	{
		global $PowerBB;
		if (empty($PowerBB->_POST['text']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['please_enter_the_text']);
		}

		if (empty($PowerBB->_POST['varname']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['please_enter_the_name_of_the_variable']);
		}

		if (!preg_match('#^[a-z0-9_\[\]]+$#i', $PowerBB->_POST['varname'])) // match a-z, A-Z, 0-9, ',', _ only .. allow [] for help items
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['please_enter_the_name_of_the_variable']);
		}
        $already_phrase = $PowerBB->DB->sql_query("SELECT phraseid FROM " . $PowerBB->table['phrase_language'] . " WHERE varname = '" . $PowerBB->_POST['varname'] . "' AND languageid IN(0,1) AND fieldname = '" . $PowerBB->_POST['fieldname'] . "'");
        $phrase_row = $PowerBB->DB->sql_fetch_array($already_phrase);
		if ($phrase_row)
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['there_already_is_the_same_name']."  " .$PowerBB->_POST['varname']);
		}

		$varname = $PowerBB->_POST['varname'];
		$fieldname = $PowerBB->_POST['fieldname'];
		$version = $PowerBB->_CONF['info_row']['MySBB_version'];
		$text = $PowerBB->_POST['text'];
		$product = "PBBoard";
		$dateline = $PowerBB->_CONF['now'];
		$username = $PowerBB->_CONF['rows']['member_row']['username'];
        $text = str_replace("'", "&#39;", $text);
         $text = stripslashes($text);

		$InsertLanguagesDefArr	=	array();
		$InsertLanguagesDefArr['field']	=	array();
		$InsertLanguagesDefArr['field']['languageid']	=	$PowerBB->_CONF['info_row']['def_lang'];
		$InsertLanguagesDefArr['field']['varname']  	=	$varname;
		$InsertLanguagesDefArr['field']['fieldname']	=	$fieldname;
		$InsertLanguagesDefArr['field']['text']	    =	$text;
		$InsertLanguagesDefArr['field']['dateline']	=	$dateline;
		$InsertLanguagesDefArr['field']['username']	=	$username;
		$InsertLanguagesDefArr['field']['version']	    =	$version;
		$InsertLanguagesDefArr['field']['product']	    =	$product;
		$insertLanguagesDef = $PowerBB->core->Insert($InsertLanguagesDefArr,'phrase_language');

              if($insertLanguagesDef)
              {

                  $language_query = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['lang'] . " WHERE id AND id <> " . $PowerBB->_CONF['info_row']['def_lang'] . "");
                     while ($getlang_row = $PowerBB->DB->sql_fetch_array($language_query))
                     {
                     	  $getlangid = $getlang_row['id'];
                     	  if(!$PowerBB->_POST['text_'.$getlangid])
                     	  {
                     	  	$txt = $PowerBB->_POST['text'];
                     	  }
                     	  else
                     	  {
                     	  	$txt = $PowerBB->_POST['text_'.$getlangid];
                     	  }

			            $txt = str_replace("'", "&#39;", $txt);
                        $txt = stripslashes($txt);

			            $languageid = $txt;

						$InsertLanguagesArr	=	array();
						$InsertLanguagesArr['field']	=	array();
						$InsertLanguagesArr['field']['languageid']	=	$getlangid;
						$InsertLanguagesArr['field']['varname']  	=	$varname;
						$InsertLanguagesArr['field']['fieldname']	=	$fieldname;
						$InsertLanguagesArr['field']['text']	    =	$txt;
						$InsertLanguagesArr['field']['dateline']	=	$dateline;
						$InsertLanguagesArr['field']['username']	=	$username;
						$InsertLanguagesArr['field']['version']	    =	$version;
						$InsertLanguagesArr['field']['product']	    =	$product;
					    $insertLanguages = $PowerBB->core->Insert($InsertLanguagesArr,'phrase_language');
                     }
               }

            $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['phrase_was_added_successfully']);
			$PowerBB->functions->redirect('index.php?page=lang&amp;control=1&amp;main=1');

	}

	function _SearchMain()
	{
		global $PowerBB;

		// Get lang list
		$LangListArr 							= 	array();

		// Clean data
		$LangListArr['proc']					=	array();
		$LangListArr['proc']['*']				=	array('method'=>'clean','param'=>'html');

		// Order setup
		$LangListArr['order'] 					= 	array();
		$LangListArr['order']['field'] 		= 	'lang_order';
		$LangListArr['order']['type'] 			= 	'ASC';

		$PowerBB->_CONF['template']['while']['LangList'] = $PowerBB->lang->GetLangList($LangListArr);

		$PowerBB->template->display('lang_search');
	}

	function _SearchStart()
	{
		global $PowerBB;

		if (empty($PowerBB->_POST['searchstring']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['no_enter_text']);
		}

		$PowerBB->_POST['searchstring'] = $PowerBB->functions->CleanVariable($PowerBB->_POST['searchstring'],'trim');

		if ($PowerBB->_POST['languageid'] == 'all')
		{
            $PowerBB->_POST['searchstring'] = str_replace("'", "&#39;", $PowerBB->_POST['searchstring']);
             $text = $PowerBB->_POST['searchstring'];
			$LangeArr 							= 	array();
			$LangeArr['where'] 					= 	array();
			$LangeArr['where'][0] 				= 	array();
          if ($PowerBB->_POST['searchwhere'] == '0')
          {
			$LangeArr['where'][0]['name'] 		= 	'text';
           }
           elseif ($PowerBB->_POST['searchwhere'] == '1')
          {
			$LangeArr['where'][0]['name'] 		= 	'varname';
	      }
          if ($PowerBB->_POST['exactmatch'] == '1')
          {
		    $LangeArr['where'][0]['oper'] 		= 	'=';
		    $LangeArr['where'][0]['value']		= 	$text;
		 }
		 else
          {
		   $LangeArr['where'][0]['oper'] 		= 	'LIKE';
		   $LangeArr['where'][0]['value']		= 	'%' .$text .'%';
		 }


			$LangeArr['order'] 					= 	array();
			$LangeArr['order']['field'] 		= 	'languageid';
			$LangeArr['order']['type'] 			= 	'DESC';

			$PowerBB->_CONF['template']['while']['LanguageList'] = $PowerBB->core->GetList($LangeArr,'phrase_language');

			if (is_array($PowerBB->_CONF['template']['while']['LanguageList'])
				and sizeof($PowerBB->_CONF['template']['while']['LanguageList']) > 0)
			{
				$PowerBB->template->assign('found',false);
			}
			else
			{
				$PowerBB->template->assign('found',true);
			}

		}
        else
        {
			$LangArr 				= 	array();
			$LangArr['where'] 		= 	array('id',$PowerBB->_POST['languageid']);

			$LangInfo = $PowerBB->core->GetInfo($LangArr,'lang');
            $PowerBB->template->assign('Lang',$LangInfo);

			if (!$LangInfo)
			{
				$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Style_requested_does_not_exist']);
			}

			 $PowerBB->functions->CleanVariable($StyleInfo,'html');
             $lange = $PowerBB->_POST['languageid'];
            $PowerBB->_POST['searchstring'] = str_replace("'", "&#39;", $PowerBB->_POST['searchstring']);
             $text = $PowerBB->_POST['searchstring'];
			$LangeArr 							= 	array();
			$LangeArr['where'] 					= 	array();
			$LangeArr['where'][0] 				= 	array();
			$LangeArr['where'][0]['name'] 		= 	'languageid';
			$LangeArr['where'][0]['oper'] 		= 	'=';
			$LangeArr['where'][0]['value']		= 	$lange;

			$LangeArr['where'][1] 					= 	array();
		    $LangeArr['where'][1]['con']		=	'AND';
          if ($PowerBB->_POST['searchwhere'] == '0')
          {
			$LangeArr['where'][1]['name'] 		= 	'text';
           }
           elseif ($PowerBB->_POST['searchwhere'] == '1')
          {
			$LangeArr['where'][1]['name'] 		= 	'varname';
		   }
          if ($PowerBB->_POST['exactmatch'] == '1')
          {
		    $LangeArr['where'][1]['oper'] 		= 	'=';
		    $LangeArr['where'][1]['value']		= 	$text;
		 }
		 else
          {
		   $LangeArr['where'][1]['oper'] 		= 	'LIKE';
		   $LangeArr['where'][1]['value']		= 	'%' .$text .'%';
		 }


			$LangeArr['order'] 					= 	array();
			$LangeArr['order']['field'] 		= 	'languageid';
			$LangeArr['order']['type'] 			= 	'DESC';

			$PowerBB->_CONF['template']['while']['LanguageList'] = $PowerBB->core->GetList($LangeArr,'phrase_language');

			if (is_array($PowerBB->_CONF['template']['while']['LanguageList'])
				and sizeof($PowerBB->_CONF['template']['while']['LanguageList']) > 0)
			{
				$PowerBB->template->assign('found',false);
			}
			else
			{
				$PowerBB->template->assign('found',true);
			}

         }

        $PowerBB->template->display('lang_search_results');

	}

	function _ControlFieldnameMain()
	{
		global $PowerBB;

		$PowerBB->_GET['count'] = (!isset($PowerBB->_GET['count'])) ? 0 : $PowerBB->_GET['count'];
		$PowerBB->_GET['count'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['count'],'intval');
        $def_perpage = "20";
		$perpage = (!empty($PowerBB->_GET['perpage'])) ? $PowerBB->_GET['perpage'] : $def_perpage;

		$LangArr 					= 	array();
		$LangArr['proc'] 			= 	array();
		$LangArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');
		$LangArr['order']			=	array();
		$LangArr['order']['field']	=	'id';
		$LangArr['order']['type']	=	'DESC';

		$PowerBB->_CONF['template']['while']['LangList'] = $PowerBB->lang->GetLangList($LangArr);
		$Getlanguageid = $PowerBB->_GET['languageid'];
		$languageid = (!empty($Getlanguageid)) ? $Getlanguageid : $PowerBB->_CONF['info_row']['def_lang'];

        $GetNumber = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['phrase_language'] . " WHERE languageid='$languageid'"));


		$LangArr 			= 	array();
		$LangArr['where'] 	= 	array('id',$languageid);

		$Inf = $PowerBB->lang->GetLangInfo($LangArr);
       	$PowerBB->template->assign('lang_title',$Inf['lang_title']);
       	$PowerBB->template->assign('lang_id',$languageid);
       	$PowerBB->template->assign('perpage',$perpage);
       	$PowerBB->template->assign('count',$PowerBB->_GET['count']);

		// Get lang list
		$LangFieldListArr 							= 	array();
		$LangFieldListArr['where'] 					= 	array();
		$LangFieldListArr['where'][0] 				= 	array();
		$LangFieldListArr['where'][0]['name'] 		= 	'languageid';
		$LangFieldListArr['where'][0]['oper'] 		= 	'=';
		$LangFieldListArr['where'][0]['value']		= 	$languageid;

		// Clean data
		$LangFieldListArr['proc']					=	array();
		$LangFieldListArr['proc']['*']				=	array('method'=>'clean','param'=>'html');

		// Order setup
		$LangFieldListArr['order'] 					= 	array();
		$LangFieldListArr['order']['field'] 		= 	'fieldname';
		$LangFieldListArr['order']['type'] 			= 	'DESC';

		$LangFieldListArr['pager'] 				= 	array();
		$LangFieldListArr['pager']['total']		= 	$GetNumber;
		$LangFieldListArr['pager']['perpage'] 	= 	$perpage;
		$LangFieldListArr['pager']['count'] 		= 	$PowerBB->_GET['count'];
		$LangFieldListArr['pager']['location'] 	= 	'index.php?page=lang&amp;control_fieldname=1&amp;main=1&amp;languageid='.$languageid.'&amp;perpage='.$perpage;
		$LangFieldListArr['pager']['var'] 		= 	'count';

		$PowerBB->_CONF['template']['while']['FieldList'] = $PowerBB->core->GetList($LangFieldListArr,'phrase_language');
        $PowerBB->template->assign('pager',$PowerBB->pager->show());
		$PowerBB->template->display('lang_control_fieldname');
	}

	function _EditFieldnameMain()
	{
		global $PowerBB;

		// Get lang Fieldname
		$LangFieldArr 			= 	array();
		$LangFieldArr['where'] 	= 	array('phraseid',$PowerBB->_GET['phraseid']);

		$InfoField = $PowerBB->core->GetInfo($LangFieldArr,'phrase_language');
       	$PowerBB->template->assign('InfoField',$InfoField);

		$LangvarnameArr 							= 	array();
		$LangvarnameArr['where'] 					= 	array();
		$LangvarnameArr['where'][0] 				= 	array();
		$LangvarnameArr['where'][0]['name'] 		= 	'varname';
		$LangvarnameArr['where'][0]['oper'] 		= 	'=';
		$LangvarnameArr['where'][0]['value']		= 	$InfoField['varname'];

		$LangvarnameArr['proc']					=	array();
		$LangvarnameArr['proc']['*']				=	array('method'=>'clean','param'=>'html');

		// Order setup
		$LangvarnameArr['order'] 					= 	array();
		$LangvarnameArr['order']['field'] 		= 	'languageid';
		$LangvarnameArr['order']['type'] 			= 	'ASC';

		$PowerBB->_CONF['template']['while']['VarnameList'] = $PowerBB->core->GetList($LangvarnameArr,'phrase_language');

		$PowerBB->template->display('lang_edit_fieldname');
	}

	function _EditFieldnameStart()
	{
		global $PowerBB;

		//////////
		if (empty($PowerBB->_GET['phraseid']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}

		$txt = $PowerBB->_POST['text'];
        $txt = stripslashes($txt);

		$LangFieldArr 					            = 	array();
		$LangFieldArr['field']			            =	array();

		$LangFieldArr['field']['text'] 		        = 	$txt;
		$LangFieldArr['field']['varname'] 		    = 	$PowerBB->_POST['varname'];
		$LangFieldArr['field']['fieldname'] 		= 	$PowerBB->_POST['fieldname'];
		$LangFieldArr['where']					    = 	array('phraseid',$PowerBB->_GET['phraseid']);

		$update = $PowerBB->core->Update($LangFieldArr,'phrase_language');

		if ($update)
		{

			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['the_statement_has_been_updated_successfully']);
			$PowerBB->functions->redirect('index.php?page=lang&amp;control_fieldname=1&amp;main=1');

		}
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

        $CatArr = $PowerBB->DB->sql_query("SELECT  *   FROM " . $PowerBB->table['lang'] . " WHERE id = ".$PowerBB->_GET['id']." ");
	    $Inf = $PowerBB->DB->sql_fetch_array($CatArr);

		if ($Inf == false)
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['language_requested_does_not_exist']);
		}

		$PowerBB->functions->CleanVariable($Inf,'html');
	}
}

?>
