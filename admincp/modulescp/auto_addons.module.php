<?php


(!defined('IN_PowerBB')) ? die() : '';
define('IN_ADMIN',true);

$CALL_SYSTEM			        =	array();
$CALL_SYSTEM['ADDONS']         =   true;
$CALL_SYSTEM['HOOKS']         =   true;
$CALL_SYSTEM['STYLE'] 	= 	true;
$CALL_SYSTEM['TEMPLATE'] 	= 	true;
$CALL_SYSTEM['TEMPLATESEDITS'] 	= 	true;
define('JAVASCRIPT_PowerCode',true);



define('CLASS_NAME','PowerBBAddonsMOD');

include('../common.php');
class PowerBBAddonsMOD
{

	function run()
	{
		global $PowerBB;

		if ($PowerBB->_CONF['member_permission'])
		{
			 if ($PowerBB->_GET['add'])
			  {
				$PowerBB->template->display('header');
				if ($PowerBB->_GET['main'])
				{
					$this->_ControlMain();
				}
			  }
			elseif($PowerBB->_GET['installation'])
                {
					$this->_installation();
				}
                elseif($PowerBB->_GET['deladdon'])
				{
					$this->_DelStart();
				}
		$PowerBB->template->display('footer');
		}

	}

 function _get_pbboard_addons_url()
    {
      global $PowerBB;
	  $pbboard_addons_url = 'https://pbboard.info/pbboard_addons/pbb3x/';
      return $pbboard_addons_url;
    }
   function get_text($filename)
    {
        $fp_load = fopen("$filename", "rb");
        if ( $fp_load )
        {
            while ( !feof($fp_load) )
            {
                $content .= fgets($fp_load, 8192);
            }
            fclose($fp_load);
        return $content;
        }
    }

	/**
	 * add Addons Main
	 */
	function _ControlMain()
	{
		global $PowerBB;
        // show Addons List

       $matches = array();
	    preg_match_all("/(a href\=\")([^\?\"]*)(\")/i", $this->get_text($this->_get_pbboard_addons_url()), $matches);
        $resultado = array_unique($matches[2]);
	    foreach($resultado as $match)
	    {
	     	$match= @str_replace("pbboard_addons/", '', $match);
	     	$match= @str_replace("/", '', $match);

	       If($match != '')
	       {
             $foldersList[]['filename'] = $match;
           }

	    }
        arsort($foldersList);
  		$PowerBB->_CONF['template']['foreach']['addonsList'] = $foldersList;
      	$PowerBB->template->assign('AddonSDir',$this->_get_pbboard_addons_url());

		$PowerBB->template->display('auto_addons_main');
	}

   function _installation()
	{
		global $PowerBB;
        // show Addons List
    $PowerBB->_GET['filename'] = intval($PowerBB->_GET['filename']);
    $infoUrl = ($this->_get_pbboard_addons_url().$PowerBB->_GET['filename']);

	$infoTxt = @file_get_contents($infoUrl.'/info.txt');
	$arr = explode('|',$infoTxt);
	 if (!empty($arr['6']))
	 {
        $url = ($this->_get_pbboard_addons_url().$PowerBB->_GET['filename'].'/'.$arr['6']);

		$To = $PowerBB->functions->GetMianDir();
		$To = str_ireplace("index.php/", '', $To);

        $file_get = fopen($url, 'r');
        file_put_contents($To."Tmpfile.zip", $file_get);
		$zip = new ZipArchive;
		$file = $To.'Tmpfile.zip';
		//$path = pathinfo(realpath($file), PATHINFO_DIRNAME);
		if ($zip->open($file) === TRUE) {
		    $zip->extractTo($To);

		    $ziped = 1;
		} else {
		   $ziped = 0;
		}
	  }


	/**
	 * add Addons Start
	 */

	      $PowerBB->template->display('header');
         $xml_url = ($this->_get_pbboard_addons_url().$PowerBB->_GET['filename'].'/'.$arr['3']);

		if (empty($arr['3']))
		{
			$PowerBB->functions->error_no_foot('لم يتم العثور على ملف  تثبيت الآضافة .. يرجى المحاولة لاحقاً');
		}

         // chek chmod  HooksCache.php
		  $url = "../cache/hooks_cache/HooksCache.php";
          if (!is_writable($url))
          {
		   $Permission = @chmod('../cache/hooks_cache/HooksCache.php', 0777);
		   if (!$Permission)
		   {
		    $PowerBB->functions->error_no_foot("Change Permissions File: cache/hooks_cache/HooksCache.php To chmod 0777- يرجى اعطاء تصريح 777 لملف cache/hooks_cache/HooksCache.php ليتم تركيب الاضافات");
		   }
		 }

		$ext = $PowerBB->functions->GetFileExtension($arr['3']);

		if ($ext != '.xml')
		{
			$PowerBB->functions->error_no_foot($PowerBB->_CONF['template']['_CONF']['lang']['error_xml_file']);
		}

		if ($PowerBB->addons->IsAddons(array('where' => array('name',$arr['0']))))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_extension'].' '.$arr['1']. ' ' .$PowerBB->_CONF['template']['_CONF']['lang']['has_been_installed']);
		}




		        $uploads_dir = '../addons';
				$file_info = $uploads_dir .'/'.$arr['3'];
                $infoTxt = @file_get_contents($xml_url);

				$fp = @fopen($file_info,'w');
				if (!$fp)
				{
				exit ('ERROR::CAN_NOT_OPEN_THE_FILE');
				}

				$fw = @fwrite($fp,$infoTxt);
				@fclose($fp);

		if (!$fw)
		{
			$PowerBB->functions->error_no_foot($PowerBB->_CONF['template']['_CONF']['lang']['is_unable_permison_folder_addons'].' '.$PowerBB->_FILES['files']['tmp_name']);
		}

       	$xml_code = @file_get_contents('../addons/'.$arr['3']);
        $xml_code = $PowerBB->sys_functions->ReplaceMysqlExtension($xml_code);
		$plugin = $PowerBB->addons->xml_to_array($xml_code);

		$name = $arr['0'];
		$title = $plugin['plugin']['attributes']['name'];
		$version = $plugin['plugin']['version']['value'];
		$description = $plugin['plugin']['description']['value'];
		$author = $plugin['plugin']['author']['value'];
		$url = $plugin['plugin']['url']['value'];
		$new_module_index = $plugin['plugin']['new_module_index']['value'];
		$new_module_admin = $plugin['plugin']['new_module_admin']['value'];
		$installcode = $plugin['plugin']['installcode']['value'];
		$uninstallcode = $plugin['plugin']['uninstallcode']['value'];
		//LanguageValues
		$LanguageVals		= '';
		@eval($plugin['plugin']['languagevals']['value']);
		if ( is_array($AddonLangValues) )
		{
			$LanguageVals = addslashes(json_encode($AddonLangValues));
		}
        eval($installcode);
		$installcode = str_replace("'","{sq}",$installcode);
		$uninstallcode = str_replace("'","{sq}",$uninstallcode);
        $installcode = @preg_replace( "/\\\(?!&amp;#|\?#)/", "&#092;", $installcode );
        $uninstallcode = @preg_replace( "/\\\(?!&amp;#|\?#)/", "&#092;", $uninstallcode );

		$new_module_index = str_replace("'","{sq}",$new_module_index);
		$new_module_admin = str_replace("'","{sq}",$new_module_admin);



		$AddonsArr 			= 	array();
		$AddonsArr['field']	=	array();

		$AddonsArr['field']['name'] 		    = 	$name;
		$AddonsArr['field']['title'] 		    = 	$title;
		$AddonsArr['field']['version'] 	    = 	$version;
		$AddonsArr['field']['description'] 	    	= 	$description;
		$AddonsArr['field']['author'] 		        = 	$author;
		$AddonsArr['field']['url'] 		            = 	$url;
		$AddonsArr['field']['module_index'] 	    = 	$new_module_index;
		$AddonsArr['field']['module_admin'] 	    = 	$new_module_admin;
		$AddonsArr['field']['installcode'] 		    = 	$installcode;
		$AddonsArr['field']['uninstallcode'] 	    = 	$uninstallcode;
		$AddonsArr['field']['languagevals']			=	$LanguageVals;

		$insert = $PowerBB->addons->InsertAddons($AddonsArr);

		$addon_arr = array();
		$addon_arr['where'] = array('name',$name);
		$addon_info = $PowerBB->addons->GetAddonsInfo($addon_arr);


		if ($insert){
	        	$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['addon_imported_successfully']);
			if($this->insert_hooks($plugin,$addon_info)){
				$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['eere_imported_hooks']);
			}
			elseif(!is_array($plugin['plugin']['hooks'])){
				$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['no_hooks_imported']);
			}


			if($this->edit_templates($plugin,$addon_info)){
				$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['was_modified_templates']);
			}
			if(!is_array($plugin['plugin']['templates'])){
				$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['no_modifications_templates']);
			}

			if($this->edit_templates_admin($plugin,$addon_info)){
				$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['was_modified_templates_admincp']);
			}
			if(!is_array($plugin['plugin']['admin_templates'])){
				$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['no_modifications_templates_admincp']);
			}

			if($this->insert_Language($plugin,$addon_info)){
				//$PowerBB->functions->msg("تمت اضافة العبارات بنجاح ..");
			}

			$this->update_cache();



			//$PowerBB->functions->redirect('index.php?page=addons&amp;control=1&amp;main=1');
		}
	 $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['addon_imported_successfully']);
	 $PowerBB->functions->redirect('index.php?page=auto_addons&add=1&main=1');

	}

function _DelStart()
	{
		global $PowerBB;
		$PowerBB->template->display('header');

	    $PowerBB->_GET['filename'] = intval($PowerBB->_GET['filename']);
	    $infoUrl = ($this->_get_pbboard_addons_url().$PowerBB->_GET['filename']);
		$infoTxt = @file_get_contents($infoUrl.'/info.txt');
		$arr = explode('|',$infoTxt);
		$addon_arr = array();
		$addon_arr['where'] = array('name',$arr['0']);
		$Addons_Info = $PowerBB->addons->GetAddonsInfo($addon_arr);

			if (empty($Addons_Info['id']))
			{
				$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['addon_requested_does_not_exist']);
			}
			$AddonsInfoArr				=	array();
			$AddonsInfoArr['where'] 	= 	array('id',$Addons_Info['id']);

			$AddonsInfo = $PowerBB->addons->GetAddonsInfo($AddonsInfoArr);

			$uninstallcode = $AddonsInfo['uninstallcode'];
			$uninstallcode = str_replace("{sq}","'",$uninstallcode);
			$uninstallcode = str_replace( "&#092;", "\\", $uninstallcode);
			eval($uninstallcode);

			if($this->del_hooks($AddonsInfo)){
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['deleted_hooks_successfully']);
			}
			if($this->back_templates($AddonsInfo))
			{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['templates_inauguration_Added']);
			}

			if($this->del_phrases($AddonsInfo)){
			//$PowerBB->functions->msg("تم حذف العبارات بنجاح ،،");
			}
             $this->update_cache();

			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['addon_delet_successfully']);
	         $PowerBB->functions->redirect('index.php?page=auto_addons&add=1&main=1');
	}

	function insert_hooks($xml_array,$inserted_plugin_info)
	{
		global $PowerBB;
     	 $hooks = $xml_array['plugin']['hooks']['hook'];
      if($hooks)
	  {
          if(!isset($xml_array['plugin']['hooks']['hook'][1]))
		  {
				$HooksArr 	=	array();
				$HooksArr['field']	=	array();
				$HooksArr['field']['addon_id']	=	$inserted_plugin_info['id'];
				$HooksArr['field']['main_place']	=	$hooks['attributes']['main_place'];
				$HooksArr['field']['place_of_hook']	=	$hooks['attributes']['place'];
				$HooksArr['field']['phpcode']	=	str_replace("'","{sq}",$hooks['value']);

				$InsertHook = $PowerBB->hooks->InsertHooks($HooksArr);
	            if($InsertHook){
					return TRUE;
				}
				else{
					return FALSE;
				}
		  }
		 else
		 {
				foreach($hooks as $hook){

				$HooksArr 	=	array();
				$HooksArr['field']	=	array();
				$HooksArr['field']['addon_id']	=	$inserted_plugin_info['id'];
				$HooksArr['field']['main_place']	=	$hook['attributes']['main_place'];
				$HooksArr['field']['place_of_hook']	=	$hook['attributes']['place'];
				$HooksArr['field']['phpcode']	=	str_replace("'","{sq}",$hook['value']);

				$InsertHook = $PowerBB->hooks->InsertHooks($HooksArr);

				}
				if($InsertHook){
					return TRUE;
				}
				else{
					return FALSE;
				}

		  }

	  }
	  else
	  {
		return FALSE;
	  }

	}

	function insert_Language($xml_array,$inserted_plugin_info)
	{
		global $PowerBB;

          $phrases = $xml_array['plugin']['phrases']['phrase'];
      if($phrases)
	  {
			  if(!isset($xml_array['plugin']['phrases']['phrase'][1]))
	         {
				$varname = $phrases['attributes']['varname'];
				$fieldname = $phrases['attributes']['fieldname'];
				$version = $PowerBB->_CONF['info_row']['MySBB_version'];
				$text = $phrases['value'];
				$product = $inserted_plugin_info['id'];
				$dateline = $PowerBB->_CONF['now'];
				$username = $PowerBB->_CONF['rows']['member_row']['username'];
				$text = str_replace("'", "&#39;", $text);

				$language_query = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['lang'] . " ");
				while ($getlang_row = $PowerBB->DB->sql_fetch_array($language_query))
				{
				$getlangid = $getlang_row['id'];

				$InsertLanguagesArr	=	array();
				$InsertLanguagesArr['field']	=	array();
				$InsertLanguagesArr['field']['languageid']	=	$getlangid;
				$InsertLanguagesArr['field']['varname']  	=	$varname;
				$InsertLanguagesArr['field']['fieldname']	=	$fieldname;
				$InsertLanguagesArr['field']['text']	    =	$text;
				$InsertLanguagesArr['field']['dateline']	=	$dateline;
				$InsertLanguagesArr['field']['username']	=	$username;
				$InsertLanguagesArr['field']['version']	    =	$version;
				$InsertLanguagesArr['field']['product']	    =	$product;
				$insertLanguages = $PowerBB->core->Insert($InsertLanguagesArr,'phrase_language');
				}
				  if($insertLanguages){
					return TRUE;
				  }
				  else{
					return FALSE;
				  }
			}
			else
			{

				foreach($phrases as $phrase){


						$varname = $phrase['attributes']['varname'];
						$fieldname = $phrase['attributes']['fieldname'];
						$version = $PowerBB->_CONF['info_row']['MySBB_version'];
						$text = $phrase['value'];
						$product = $inserted_plugin_info['id'];
						$dateline = $PowerBB->_CONF['now'];
						$username = $PowerBB->_CONF['rows']['member_row']['username'];
				        $text = str_replace("'", "&#39;", $text);

	                 $language_query = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['lang'] . " ");
	                     while ($getlang_row = $PowerBB->DB->sql_fetch_array($language_query))
	                     {
	                     	  $getlangid = $getlang_row['id'];

							$InsertLanguagesArr	=	array();
							$InsertLanguagesArr['field']	=	array();
							$InsertLanguagesArr['field']['languageid']	=	$getlangid;
							$InsertLanguagesArr['field']['varname']  	=	$varname;
							$InsertLanguagesArr['field']['fieldname']	=	$fieldname;
							$InsertLanguagesArr['field']['text']	    =	$text;
							$InsertLanguagesArr['field']['dateline']	=	$dateline;
							$InsertLanguagesArr['field']['username']	=	$username;
							$InsertLanguagesArr['field']['version']	    =	$version;
							$InsertLanguagesArr['field']['product']	    =	$product;
						    $insertLanguages = $PowerBB->core->Insert($InsertLanguagesArr,'phrase_language');
	                     }

				}
				  if($insertLanguages){
					return TRUE;
				  }
				  else{
					return FALSE;
				  }
			}
	  }
	  else
	  {
		return FALSE;
	  }

	}

	//Edited--------------------------------------------------------
	//Original Line	function edit_templates($xml_array,$plugin_info)
	function edit_templates($xml_array,$plugin_info,$SpecificStyle = '')	//specific new style's id
	{
		global $PowerBB;

		if (empty($SpecificStyle))
		{
			$StyleList = $PowerBB->style->GetStyleList(array());
		}
		else
		{
			$StyleList = $PowerBB->style->GetStyleList(array('where' => array('id',$SpecificStyle)));
		}


		//---------------------------------------------------------------
		$Templates = $xml_array['plugin']['templates']['template'];

      if($Templates)
	 {
		if(!isset($xml_array['plugin']['templates']['template'][1]))
		{

			$find = $Templates['find']['value'];
			$action = $Templates['action']['value'];
        	$find	=	str_replace("'","{sq}",$find);
        	$action	=	str_replace("'","{sq}",$action);
            $find = @preg_replace( "/\\\(?!&amp;#|\?#)/", "&#092;", $find );
            $action = @preg_replace( "/\\\(?!&amp;#|\?#)/", "&#092;", $action );
			$Templates['attributes']['name'] = @str_replace(".tpl",'',$Templates['attributes']['name']);


			$TemplateseditsArr	=	array();
			$TemplateseditsArr['field']	=	array();
			$TemplateseditsArr['field']['addon_id']	=	$plugin_info['id'];
			$TemplateseditsArr['field']['template_name']	=	$Templates['attributes']['name'];
			$TemplateseditsArr['field']['action']	=	$Templates['attributes']['type'];
			$TemplateseditsArr['field']['old_text']	=	$find;
			$TemplateseditsArr['field']['text']	=	$action;

			$Insert = $PowerBB->templates_edits->InsertTemplatesEdits($TemplateseditsArr);

			$Templattitle = $Template['attributes']['name'];
			$Templattitle = @str_replace(".tpl",'',$Templattitle);
			foreach($StyleList as $Style)
			 {
				if (empty($SpecificStyle))
				{
			      $StyleId = $Style['id'];
				}
				else
				{
			      $StyleId = $SpecificStyle;
				}

				 $_query= $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['template'] . " WHERE title = '$Templattitle' and styleid = '$StyleId' ");
				 $row = $PowerBB->DB->sql_fetch_array($_query);


			    if($Template['attributes']['type'] == 'new')
				{
		                $Template['text']['value'] = str_replace("'", "&#39;", $Template['text']['value']);

						$TemplateArr 			= 	array();
						$TemplateArr['field']	=	array();

						$TemplateArr['field']['title'] 		    = 	$Templattitle;
						$TemplateArr['field']['template'] 	    = 	$Template['text']['value'];
						$TemplateArr['field']['template_un'] 	    	= 	$Template['text']['value'];
						$TemplateArr['field']['templatetype'] 		        = 	"template";
						$TemplateArr['field']['dateline'] 		            = 	$PowerBB->_CONF['now'];
						$TemplateArr['field']['version'] 	    = 	$PowerBB->_CONF['info_row']['MySBB_version'];
						$TemplateArr['field']['username'] 	    = 	$PowerBB->_CONF['rows']['member_row']['username'];
						$TemplateArr['field']['product'] 		    = 	"Addons";
						$TemplateArr['field']['styleid'] 		    = 	$StyleId;

						$insert = $PowerBB->core->Insert($TemplateArr,'template');

				}
         		if($Template['attributes']['type'] == 'replace')
				{

					$contents = $row['template'];
					$contents = str_replace("&#39;", "'", $contents);
		            $new_contents	=	str_replace($Template['find']['value'],$Template['action']['value'], $contents);
		            $new_contents = str_replace("'", "&#39;", $new_contents);

					$TemplateArr 			= 	array();
					$TemplateArr['field']	=	array();

					$TemplateArr['field']['template'] 		= 	$new_contents;
					$TemplateArr['field']['dateline'] 		    = $PowerBB->_CONF['now'];
					$TemplateArr['where'] 				= 	array('templateid',$row['templateid']);

					$update = $PowerBB->core->Update($TemplateArr,'template');
                     if ($update)
                     {
						$TemplateEditArr				=	array();
						$TemplateEditArr['where'] 	= 	array('templateid',$row['templateid']);

						$TemplateEdit = $PowerBB->core->GetInfo($TemplateEditArr,'template');

						$templates_dir = ("../cache/templates_cache/".$TemplateEdit['title']."_".$TemplateEdit['styleid'].".php");

						if (file_exists($templates_dir))
						{
					 	 $cache_del = @unlink($templates_dir);
						}
					}
				 }
         		if($Template['attributes']['type'] == 'before')
				{

					$contents = $row['template'];
					$contents = str_replace("&#39;", "'", $contents);
		             $new_contents	=	str_replace($Template['find']['value'],$Template['action']['value']."\n".$Template['find']['value'], $contents);
		            $new_contents = str_replace("'", "&#39;", $new_contents);

					$TemplateArr 			= 	array();
					$TemplateArr['field']	=	array();

					$TemplateArr['field']['template'] 		= 	$new_contents;
					$TemplateArr['field']['dateline'] 		    = $PowerBB->_CONF['now'];
					$TemplateArr['where'] 				= 	array('templateid',$row['templateid']);

					$update = $PowerBB->core->Update($TemplateArr,'template');
                     if ($update)
                     {
						$TemplateEditArr				=	array();
						$TemplateEditArr['where'] 	= 	array('templateid',$row['templateid']);

						$TemplateEdit = $PowerBB->core->GetInfo($TemplateEditArr,'template');

						$templates_dir = ("../cache/templates_cache/".$TemplateEdit['title']."_".$TemplateEdit['styleid'].".php");

						if (file_exists($templates_dir))
						{
					 	 $cache_del = @unlink($templates_dir);
						}
					}

				 }
         		if($Template['attributes']['type'] == 'after')
				{

					$contents = $row['template'];
					$contents = str_replace("&#39;", "'", $contents);
		             $new_contents	=	str_replace($Template['find']['value'],$Template['find']['value']."\n".$Template['action']['value'], $contents);

		            $new_contents = str_replace("'", "&#39;", $new_contents);

					$TemplateArr 			= 	array();
					$TemplateArr['field']	=	array();

					$TemplateArr['field']['template'] 		= 	$new_contents;
					$TemplateArr['field']['dateline'] 		    = $PowerBB->_CONF['now'];
					$TemplateArr['where'] 				= 	array('templateid',$row['templateid']);

					$update = $PowerBB->core->Update($TemplateArr,'template');
                     if ($update)
                     {
						$TemplateEditArr				=	array();
						$TemplateEditArr['where'] 	= 	array('templateid',$row['templateid']);

						$TemplateEdit = $PowerBB->core->GetInfo($TemplateEditArr,'template');

						$templates_dir = ("../cache/templates_cache/".$TemplateEdit['title']."_".$TemplateEdit['styleid'].".php");

						if (file_exists($templates_dir))
						{
					 	 $cache_del = @unlink($templates_dir);
						}
					}

				 }
              }

		}
		else
		{

		foreach($Templates as $Template)
		 {
			$find = $Template['find']['value'];
			$action = $Template['action']['value'];

			$Template['attributes']['name'] = @str_replace(".tpl",'',$Template['attributes']['name']);

			$TemplateseditsArr	=	array();
			$TemplateseditsArr['field']	=	array();
			$TemplateseditsArr['field']['addon_id']	=	$plugin_info['id'];
			$TemplateseditsArr['field']['template_name']	=	$Template['attributes']['name'];
			$TemplateseditsArr['field']['action']	=	$Template['attributes']['type'];
			$TemplateseditsArr['field']['old_text']	=	$find;
			$TemplateseditsArr['field']['text']	=	$action;

			$Insert = $PowerBB->templates_edits->InsertTemplatesEdits($TemplateseditsArr);

			foreach($StyleList as $Style)
			 {

			$Templattitle = $Template['attributes']['name'];
			$Templattitle = @str_replace(".tpl",'',$Templattitle);
				if (empty($SpecificStyle))
				{
			      $StyleId = $Style['id'];
				}
				else
				{
			      $StyleId = $SpecificStyle;
				}
			 $_query= $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['template'] . " WHERE title = '$Templattitle' and styleid = '$StyleId' ");
			 $row = $PowerBB->DB->sql_fetch_array($_query);


			    if($Template['attributes']['type'] == 'new')
				{
		                $Template['text']['value'] = str_replace("'", "&#39;", $Template['text']['value']);

						$TemplateArr 			= 	array();
						$TemplateArr['field']	=	array();

						$TemplateArr['field']['title'] 		    = 	$Templattitle;
						$TemplateArr['field']['template'] 	    = 	$Template['text']['value'];
						$TemplateArr['field']['template_un'] 	    	= 	$Template['text']['value'];
						$TemplateArr['field']['templatetype'] 		        = 	"template";
						$TemplateArr['field']['dateline'] 		            = 	$PowerBB->_CONF['now'];
						$TemplateArr['field']['version'] 	    = 	$PowerBB->_CONF['info_row']['MySBB_version'];
						$TemplateArr['field']['username'] 	    = 	$PowerBB->_CONF['rows']['member_row']['username'];
						$TemplateArr['field']['product'] 		    = 	"Addons";
						$TemplateArr['field']['styleid'] 		    = 	$StyleId;

						$insert = $PowerBB->core->Insert($TemplateArr,'template');

				}
         		if($Template['attributes']['type'] == 'replace')
				{

					$contents = $row['template'];
					$contents = str_replace("&#39;", "'", $contents);
		            $new_contents	=	str_replace($Template['find']['value'],$Template['action']['value'], $contents);
		            $new_contents = str_replace("'", "&#39;", $new_contents);

					$TemplateArr 			= 	array();
					$TemplateArr['field']	=	array();

					$TemplateArr['field']['template'] 		= 	$new_contents;
					$TemplateArr['field']['dateline'] 		    = $PowerBB->_CONF['now'];
					$TemplateArr['where'] 				= 	array('templateid',$row['templateid']);

					$update = $PowerBB->core->Update($TemplateArr,'template');
                     if ($update)
                     {
						$TemplateEditArr				=	array();
						$TemplateEditArr['where'] 	= 	array('templateid',$row['templateid']);

						$TemplateEdit = $PowerBB->core->GetInfo($TemplateEditArr,'template');

						$templates_dir = ("../cache/templates_cache/".$TemplateEdit['title']."_".$TemplateEdit['styleid'].".php");

						if (file_exists($templates_dir))
						{
					 	 $cache_del = @unlink($templates_dir);
						}
					}

				 }
         		if($Template['attributes']['type'] == 'before')
				{

					$contents = $row['template'];
					$contents = str_replace("&#39;", "'", $contents);
		             $new_contents	=	str_replace($Template['find']['value'],$Template['action']['value']."\n".$Template['find']['value'], $contents);
		            $new_contents = str_replace("'", "&#39;", $new_contents);

					$TemplateArr 			= 	array();
					$TemplateArr['field']	=	array();

					$TemplateArr['field']['template'] 		= 	$new_contents;
					$TemplateArr['field']['dateline'] 		    = $PowerBB->_CONF['now'];
					$TemplateArr['where'] 				= 	array('templateid',$row['templateid']);

					$update = $PowerBB->core->Update($TemplateArr,'template');
                     if ($update)
                     {
						$TemplateEditArr				=	array();
						$TemplateEditArr['where'] 	= 	array('templateid',$row['templateid']);

						$TemplateEdit = $PowerBB->core->GetInfo($TemplateEditArr,'template');

						$templates_dir = ("../cache/templates_cache/".$TemplateEdit['title']."_".$TemplateEdit['styleid'].".php");

						if (file_exists($templates_dir))
						{
					 	 $cache_del = @unlink($templates_dir);
						}
					}

				 }
         		if($Template['attributes']['type'] == 'after')
				{

					$contents = $row['template'];
					$contents = str_replace("&#39;", "'", $contents);
		             $new_contents	=	str_replace($Template['find']['value'],$Template['find']['value']."\n".$Template['action']['value'], $contents);

		            $new_contents = str_replace("'", "&#39;", $new_contents);

					$TemplateArr 			= 	array();
					$TemplateArr['field']	=	array();

					$TemplateArr['field']['template'] 		= 	$new_contents;
					$TemplateArr['field']['dateline'] 		    = $PowerBB->_CONF['now'];
					$TemplateArr['where'] 				= 	array('templateid',$row['templateid']);

					$update = $PowerBB->core->Update($TemplateArr,'template');
                     if ($update)
                     {
						$TemplateEditArr				=	array();
						$TemplateEditArr['where'] 	= 	array('templateid',$row['templateid']);

						$TemplateEdit = $PowerBB->core->GetInfo($TemplateEditArr,'template');

						$templates_dir = ("../cache/templates_cache/".$TemplateEdit['title']."_".$TemplateEdit['styleid'].".php");

						if (file_exists($templates_dir))
						{
					 	 $cache_del = @unlink($templates_dir);
						}
					}

				 }
			}


		}
       }

		if($Insert && $update)
		{
			return TRUE;
		}
      }
	}

	function edit_templates_admin($xml_array,$plugin_info)
	{
		global $PowerBB;

		$Templates = $xml_array['plugin']['admin_templates']['template'];
      if($Templates)
	 {
		if(!isset($xml_array['plugin']['admin_templates']['template'][1]))
		{
			$find = $Templates['find']['value'];
			$action = $Templates['action']['value'];
        	$find	=	str_replace("'","{sq}",$find);
        	$action	=	str_replace("'","{sq}",$action);
            $find = @preg_replace( "/\\\(?!&amp;#|\?#)/", "&#092;", $find );
            $action = @preg_replace( "/\\\(?!&amp;#|\?#)/", "&#092;", $action );

			$TemplateseditsArr	=	array();
			$TemplateseditsArr['field']	=	array();
			$TemplateseditsArr['field']['addon_id']	=	$plugin_info['id'];
			$TemplateseditsArr['field']['template_name']	=	$Templates['attributes']['name'];
			$TemplateseditsArr['field']['action']	=	$Templates['attributes']['type'];
			$TemplateseditsArr['field']['old_text']	=	$find;
			$TemplateseditsArr['field']['text']	=	$action;

			$Insert = $PowerBB->templates_edits->InsertTemplatesEdits($TemplateseditsArr);

			$Path = "../".$PowerBB->admincpdir."/cpstyles/templates/".$Templates['attributes']['name'];
			    if($Templates['attributes']['type'] == 'new')
				{
					if(!file_exists($Path))
					{
						$new = fopen($Path,'x');

						fwrite($new,$Templates['text']['value']);

						fclose($new);
					}

				 }
			    if($Templates['attributes']['type'] == 'replace')
				{
					if(file_exists($Path)){

					$contents = @file_get_contents($Path);
					$contents = $PowerBB->sys_functions->ReplaceMysqlExtension($contents);
		            $new	=	str_replace($Templates['find']['value'],$Templates['action']['value'], $contents);
					$put = @file_put_contents($Path,$new);
					}
				}
			    if($Templates['attributes']['type'] == 'before')
				{
					if(file_exists($Path)){

					$contents = @file_get_contents($Path);
					$contents = $PowerBB->sys_functions->ReplaceMysqlExtension($contents);
		            $new	=	str_replace($Templates['find']['value'],$Templates['action']['value']."\n".$Templates['find']['value'], $contents);

					$put = @file_put_contents($Path,$new);
					}
				}
			    if($Templates['attributes']['type'] == 'after')
				{
					if(file_exists($Path)){

					$contents = @file_get_contents($Path);
					$contents = $PowerBB->sys_functions->ReplaceMysqlExtension($contents);
		            $new	=	str_replace($Templates['find']['value'],$Templates['find']['value']."\n".$Templates['action']['value'], $contents);

					$put = @file_put_contents($Path,$new);
					}
				}
		}
		else
		{
		  foreach($Templates as $Template)
		   {

			$find = $Template['find']['value'];
			$action = $Template['action']['value'];
        	$find	=	str_replace("'","{sq}",$find);
        	$action	=	str_replace("'","{sq}",$action);
            $find = @preg_replace( "/\\\(?!&amp;#|\?#)/", "&#092;", $find );
            $action = @preg_replace( "/\\\(?!&amp;#|\?#)/", "&#092;", $action );

			$TemplateseditsArr	=	array();
			$TemplateseditsArr['field']	=	array();
			$TemplateseditsArr['field']['addon_id']	=	$plugin_info['id'];
			$TemplateseditsArr['field']['template_name']	=	$Template['attributes']['name'];
			$TemplateseditsArr['field']['action']	=	$Template['attributes']['type'];
			$TemplateseditsArr['field']['old_text']	=	$find;
			$TemplateseditsArr['field']['text']	=	$action;

			$Insert = $PowerBB->templates_edits->InsertTemplatesEdits($TemplateseditsArr);
			$Path = "../".$PowerBB->admincpdir."/cpstyles/templates/".$Template['attributes']['name'];
			    if($Template['attributes']['type'] == 'new')
				{
					if(!file_exists($Path))
					{
						$new = fopen($Path,'x');

						fwrite($new,$Template['text']['value']);

						fclose($new);
					}

				 }
			    if($Template['attributes']['type'] == 'replace')
				{
					if(file_exists($Path)){

					$contents = @file_get_contents($Path);
					$contents = $PowerBB->sys_functions->ReplaceMysqlExtension($contents);
		            $new	=	str_replace($Template['find']['value'],$Template['action']['value'], $contents);
					$put = @file_put_contents($Path,$new);
					}
				}
			    if($Template['attributes']['type'] == 'before')
				{
					if(file_exists($Path)){

					$contents = @file_get_contents($Path);
					$contents = $PowerBB->sys_functions->ReplaceMysqlExtension($contents);
		            $new	=	str_replace($Template['find']['value'],$Template['action']['value']."\n".$Template['find']['value'], $contents);

					$put = @file_put_contents($Path,$new);
					}
				}
			    if($Template['attributes']['type'] == 'after')
				{
					if(file_exists($Path)){

					$contents = @file_get_contents($Path);
					$contents = $PowerBB->sys_functions->ReplaceMysqlExtension($contents);
		            $new	=	str_replace($Template['find']['value'],$Template['find']['value']."\n".$Template['action']['value'], $contents);

					$put = @file_put_contents($Path,$new);
					}
				}

		   }
        }
		if($Insert && $put)
		{
			return TRUE;
		}
      }
	}

	function del_hooks($plugin_info)
	{

		global $PowerBB;

		if(empty($plugin_info) or !isset($plugin_info['id'])){

			return FALSE;

		}
		else{

		$HooksList = $PowerBB->hooks->GetHooksList(array('where'=>array('addon_id',$plugin_info['id'])));

		foreach($HooksList as $Hook){

			$Delete = $PowerBB->hooks->DeleteHooks(array('where'=>array('addon_id',$plugin_info['id'])));

			if($Delete){

				return TRUE;

			}
			else
			{
				return FALSE;
			}
		}
		}
	}

	function del_phrases($plugin_info)
	{

		global $PowerBB;

		if(empty($plugin_info) or !isset($plugin_info['id'])){

			return FALSE;

		}
		else
		{
			$plugin_info_id = $plugin_info['id'];
		    $Delete = $PowerBB->DB->sql_query("DELETE FROM " . $PowerBB->table['phrase_language'] . " WHERE product = '$plugin_info_id' ");
			if($Delete){

				return TRUE;

			}
			else
			{
				return FALSE;
			}
		}
	}

	//Edited--------------------------------------------------------
	//Original Line	function :: function back_templates($plugin_info)
	function back_templates($plugin_info)	//specific style's id
	{
		global $PowerBB;
		$Addons = $PowerBB->addons->GetAddonsList(array('where' => array('id',$plugin_info['id'])));

		$AddonsMod = new PowerBBAddonsMOD();
		foreach ($Addons as $key => $addonrow )
		{
				$xml_code = @file_get_contents('../addons/'.$plugin_info['name'].".xml");
			   $xml_code = $PowerBB->sys_functions->ReplaceMysqlExtension($xml_code);
				$plugin = $PowerBB->addons->xml_to_array($xml_code);
				$Templates = $plugin['plugin']['templates']['template'];
				       $StyleList = $PowerBB->style->GetStyleList(array());
				//---------------------------------------------------------------
					if(!isset($plugin['plugin']['templates']['template'][1]))
					{
			       		foreach($StyleList as $Style)
						{
							$StyleId = $Style['id'];
							$Templattitle = $Templates['attributes']['name'];
							$Templattitle = @str_replace(".tpl",'',$Templattitle);

							$_query= $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['template'] . " WHERE title = '$Templattitle' AND styleid = '$StyleId'");
							$row = $PowerBB->DB->sql_fetch_array($_query);
							 $StyleIdrow = $row['id'];
							$old_text = $Templates['find']['value'];
							$text = $Templates['action']['value'];
			                 $text = str_replace("'", "&#39;", $text);
			                 $old_text = str_replace("'", "&#39;", $old_text);

							if($Templates['attributes']['type'] == 'new'){

								$DelArr 			= 	array();
								$DelArr['where'] 	= 	array('title',$Templattitle);

								$del = $PowerBB->core->Deleted($DelArr,'template');
								$templates_dir = ("../cache/templates_cache/".$Templattitle."_".$StyleId.".php");
								if (file_exists($templates_dir))
								{
							 	 $cache_del = @unlink($templates_dir);
								}
							}
							elseif($Templates['attributes']['type'] == 'replace')
							{
								$contents = $row['template'];
								$contents = str_replace("&#39;", "'", $contents);
						        $new = str_replace($Templates['action']['value'],$Templates['find']['value'],$contents);
			                    $new = str_replace("'", "&#39;", $new);
								$TemplateArr 			= 	array();
								$TemplateArr['field']	=	array();

								$TemplateArr['field']['template'] 		= 	$new;
								$TemplateArr['field']['dateline'] 		    = $PowerBB->_CONF['now'];
								$TemplateArr['where'] 				= 	array('templateid',$row['templateid']);

								$update = $PowerBB->core->Update($TemplateArr,'template');

			                     if ($update)
			                     {
									$TemplateEditArr				=	array();
									$TemplateEditArr['where'] 	= 	array('templateid',$row['templateid']);

									$TemplateEdit = $PowerBB->core->GetInfo($TemplateEditArr,'template');

									$templates_dir = ("../cache/templates_cache/".$TemplateEdit['title']."_".$TemplateEdit['styleid'].".php");

									if (file_exists($templates_dir))
									{
								 	 $cache_del = @unlink($templates_dir);
									}
								}

							}
							else
							{

								$contents = $row['template'];
								$contents = str_replace("&#39;", "'", $contents);
								$new_contents	=	str_replace($Template['action']['value'],"", $contents);
								$new_contents = str_replace("'", "&#39;", $new_contents);

			                    $new_contents = str_replace("'", "&#39;", $new_contents);

								$TemplateArr 			= 	array();
								$TemplateArr['field']	=	array();
								$TemplateArr['field']['template'] 		= 	$new_contents;
								$TemplateArr['field']['dateline'] 		    = $PowerBB->_CONF['now'];
								$TemplateArr['where'] 				= 	array('templateid',$row['templateid']);

								$update = $PowerBB->core->Update($TemplateArr,'template');


			                     if ($update)
			                     {
									$TemplateEditArr				=	array();
									$TemplateEditArr['where'] 	= 	array('templateid',$row['templateid']);

									$TemplateEdit = $PowerBB->core->GetInfo($TemplateEditArr,'template');

									$templates_dir = ("../cache/templates_cache/".$TemplateEdit['title']."_".$TemplateEdit['styleid'].".php");

									if (file_exists($templates_dir))
									{
								 	 $cache_del = @unlink($templates_dir);
									}
								}
							}
                        }
					}
				    else
				    {

					   foreach($Templates as $Template)
					    {
				       		foreach($StyleList as $Style)
							{
							    $StyleId = $Style['id'];

								 $Templattitle = $Template['attributes']['name'];
							     $Templattitle = @str_replace(".tpl",'',$Templattitle);

							   $_query= $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['template'] . " WHERE title = '$Templattitle' AND styleid = '$StyleId'");
								$row = $PowerBB->DB->sql_fetch_array($_query);
							    $StyleIdrow = $row['id'];
								$old_text = $Template['find']['value'];
								$text = $Template['action']['value'];
			                    $text = str_replace("'", "&#39;", $text);
			                    $old_text = str_replace("'", "&#39;", $old_text);

								if($Template['attributes']['type'] == 'new'){
								$DelArr 			= 	array();
								$DelArr['where'] 	= 	array('title',$Templattitle);

								$del = $PowerBB->core->Deleted($DelArr,'template');
									$templates_dir = ("../cache/templates_cache/".$Templattitle."_".$StyleId.".php");
									if (file_exists($templates_dir))
									{
								 	 $cache_del = @unlink($templates_dir);
									}
								}
								elseif($Template['attributes']['type'] == 'replace')
								{
									$contents = $row['template'];
									$contents = str_replace("&#39;", "'", $contents);
							        $new = str_replace($Template['action']['value'],$Template['find']['value'],$contents);
				                    $new = str_replace("'", "&#39;", $new);
									$TemplateArr 			= 	array();
									$TemplateArr['field']	=	array();

									$TemplateArr['field']['template'] 		= 	$new;
									$TemplateArr['field']['dateline'] 		    = $PowerBB->_CONF['now'];
									$TemplateArr['where'] 				= 	array('templateid',$row['templateid']);

									$update = $PowerBB->core->Update($TemplateArr,'template');

				                     if ($update)
				                     {
										$TemplateEditArr				=	array();
										$TemplateEditArr['where'] 	= 	array('templateid',$row['templateid']);

										$TemplateEdit = $PowerBB->core->GetInfo($TemplateEditArr,'template');

										$templates_dir = ("../cache/templates_cache/".$TemplateEdit['title']."_".$TemplateEdit['styleid'].".php");

										if (file_exists($templates_dir))
										{
									 	 $cache_del = @unlink($templates_dir);
										}
									}

								}
								else
								{

									$contents = $row['template'];
									$contents = str_replace("&#39;", "'", $contents);
									$contents	=	str_replace($Template['action']['value'],"", $contents);
									$contents	=	str_replace("\n".$Template['find']['value'],$Template['find']['value'], $contents);
									$contents = str_replace("'", "&#39;", $contents);

									$TemplateArr 			= 	array();
									$TemplateArr['field']	=	array();
									$TemplateArr['field']['template'] 		= 	$contents;
									$TemplateArr['field']['dateline'] 		    = $PowerBB->_CONF['now'];
									$TemplateArr['where'] 				= 	array('templateid',$row['templateid']);

									$update = $PowerBB->core->Update($TemplateArr,'template');
				                     if ($update)
				                     {
										$TemplateEditArr				=	array();
										$TemplateEditArr['where'] 	= 	array('templateid',$row['templateid']);

										$TemplateEdit = $PowerBB->core->GetInfo($TemplateEditArr,'template');

										$templates_dir = ("../cache/templates_cache/".$TemplateEdit['title']."_".$TemplateEdit['styleid'].".php");

										if (file_exists($templates_dir))
										{
									 	 $cache_del = @unlink($templates_dir);
										}
									}

								}

							}
						}

	                }

                  	$Admin_Templates = $plugin['plugin']['admin_templates']['template'];
				if(!isset($plugin['plugin']['admin_templates']['template'][1]))
				{
					$PathAdmin = "../".$PowerBB->admincpdir."/cpstyles/templates/".$Admin_Templates['attributes']['name'];

				    $old_text = $Admin_Templates['find']['value'];
					$text  = $Admin_Templates['action']['value'];
				 	$Templattitle = $Admin_Templates['attributes']['name'];
					if($Admin_Templates['attributes']['type'] == 'new')
					{
			          if(file_exists($PathAdmin))
			          {
	                   $delAdmin = @unlink($PathAdmin);
	                  }
					}
					elseif($Admin_Templates['attributes']['type'] == 'replace')
					{
						if(file_exists($PathAdmin)){
						 $contents = @file_get_contents($PathAdmin);
            			 $contents = $PowerBB->sys_functions->ReplaceMysqlExtension($contents);
						 $contents = str_replace($Admin_Templates['action']['value'],$Admin_Templates['find']['value'],$contents);
						 $put = @file_put_contents($PathAdmin,$contents);
						}
					}
					else
					{
						if(file_exists($PathAdmin)){

						$contents = @file_get_contents($PathAdmin);
            			$contents = $PowerBB->sys_functions->ReplaceMysqlExtension($contents);
						$contents	=	str_replace($Admin_Templates['action']['value'],"", $contents);
						$contents	=	str_replace("\n".$Admin_Templates['find']['value'],$Admin_Templates['find']['value'], $contents);
						 $put = @file_put_contents($PathAdmin,$contents);
						}
					}
				}
				else
				{
					 foreach($Admin_Templates as $Template)
				    {
					$PathAdmin = "../".$PowerBB->admincpdir."/cpstyles/templates/".$Template['attributes']['name'];

				    	$old_text = $Template['find']['value'];
						$text  = $Template['action']['value'];

					 	$Templattitle = $Template['attributes']['name'];
						if($Template['attributes']['type'] == 'new')
						{
				          if(file_exists($PathAdmin))
				          {
		                   $delAdmin = @unlink($PathAdmin);
		                  }
						}
						elseif($Template['attributes']['type'] == 'replace')
						{
							if(file_exists($PathAdmin)){
							 $contents = @file_get_contents($PathAdmin);
							 $contents = $PowerBB->sys_functions->ReplaceMysqlExtension($contents);
							 $contents = str_replace($Template['action']['value'],$Template['find']['value'],$contents);
							 $put = @file_put_contents($PathAdmin,$contents);
							}
						}
						else
						{
							if(file_exists($PathAdmin)){

							$contents = @file_get_contents($PathAdmin);
							$contents = $PowerBB->sys_functions->ReplaceMysqlExtension($contents);
							$contents	=	str_replace($Template['action']['value'],"", $contents);
							$contents	=	str_replace("\n".$Template['find']['value'],$Template['find']['value'], $contents);
							 $put = @file_put_contents($PathAdmin,$contents);
							}
						}

				    }

				}


		}

           if (!$PowerBB->_GET['no_active'])
			{


			     //$del_file = @unlink('../addons/'.$plugin_info['name'].".xml");
			    //
				$Delete = $PowerBB->templates_edits->DeleteTemplatesEdits(array('where'=>array('addon_id',$addon_info['id'])));
				//
			    $DelArr 			= 	array();
				$DelArr['where'] 	= 	array('id',$plugin_info['id']);

				$del = $PowerBB->addons->DeleteAddons($DelArr);
	             //
					if($Delete && $del){

						return TRUE;
					}
             }
             else
             {
			   return TRUE;
			 }
	}


	function update_cache(){
		global $PowerBB;

		$HooksList = $PowerBB->hooks->GetHooksList(array());


			$data = '<?php
$Hooks = array();
';

       $x=1;
		foreach($HooksList as $Hook){

			$AddonInfo = $PowerBB->addons->GetAddonsInfo(array('where'=>array('id',$Hook['addon_id'])));

			if($AddonInfo['active'] == '1'){

			$data .= '$Hooks[\''.$Hook['main_place'].'\'][\''.$x.'\'] = \''.str_replace("{sq}","\'",str_replace("'","\'",$Hook['phpcode']))."';\n\n\n";

			}
            $x += 1;
		}

		$data .= "\n\n".'//end of cache file'."\n";
		$data .= '?>';
		$open = fopen('../cache/hooks_cache/HooksCache.php','w');
		if (!$open)
		{
		  $Permission = @chmod('../cache/hooks_cache/HooksCache.php', 0777);
		  if (!$Permission)
		  {
		    $PowerBB->functions->error_no_foot("Change Permissions File: cache/hooks_cache/HooksCache.php To chmod 0777- يرجى اعطاء تصريح 777 لملف cache/hooks_cache/HooksCache.php ليتم تركيب الاضافات");
		  }
		}
		fwrite($open,$data);
		fclose($open);


	}
}

?>
