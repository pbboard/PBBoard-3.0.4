<?php
error_reporting(E_ERROR | E_PARSE);
(!defined('IN_PowerBB')) ? die() : '';
define('IN_ADMIN',true);
define('DONT_STRIPS_SLIASHES',true);
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
			if ($PowerBB->_GET['control'])
			{
				$PowerBB->template->display('header');
				if ($PowerBB->_GET['main'])
				{
					$this->_ControlMain();
				}
			}
			elseif ($PowerBB->_GET['add'])
			{
				$PowerBB->template->display('header');

              	if ($PowerBB->_GET['main'])
				{
					$this->_AddAddonsMain();
				}
                elseif ($PowerBB->_GET['start'])
				{
					$this->_AddAddonsStart();
				}
			}
			elseif ($PowerBB->_GET['writing_addon'])
			{
				$PowerBB->template->display('header');

              	if ($PowerBB->_GET['main'])
				{
					$this->_WritingAddonsMain();
				}
                elseif ($PowerBB->_GET['start'])
				{
					$this->_WritingAddonsStart();
				}
			}
			elseif ($PowerBB->_GET['edit'])
			{
				$PowerBB->template->display('header');

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
				$PowerBB->template->display('header');

                if ($PowerBB->_GET['start'])
				{
					$this->_DelStart();
				}
			}
			elseif ($PowerBB->_GET['active'])
			{
				$PowerBB->template->display('header');
                if ($PowerBB->_GET['start'])
				{
					$this->_ActiveStart();
				}
			}
			elseif ($PowerBB->_GET['no_active'])
			{
				$PowerBB->template->display('header');

                if ($PowerBB->_GET['start'])
				{
					$this->_NoActiveStart();
				}
			}
			elseif ($PowerBB->_GET['export'])
			{
                if ($PowerBB->_GET['start'])
				{
					$this->_ExportStart();
				}
				elseif ($PowerBB->_GET['export_writing'])
				{
					$this->_ExportWriting();
				}
			}
			elseif ($PowerBB->_GET['control_hooks'])
			{
				$PowerBB->template->display('header');

                if ($PowerBB->_GET['main'])
				{
					$this->_ControlHook();
				}
			}
			elseif ($PowerBB->_GET['edit_hook'])
			{
				$PowerBB->template->display('header');

                if ($PowerBB->_GET['main'])
				{
					$this->_MainEditHook();
				}
				elseif ($PowerBB->_GET['start'])
				{
					$this->_StartEditHook();
				}
			}


		$PowerBB->template->display('footer');
		}

	}


	/**
	 * add Addons Main
	 */

	function _AddAddonsMain()
	{
		global $PowerBB;

		$PowerBB->template->display('addons_add');

    }

	/**
	 * add Addons Start
	 */
	function _AddAddonsStart()
	{

		global $PowerBB;

		if (empty($PowerBB->_FILES['files']['name']))
		{
			$PowerBB->functions->error_no_foot($PowerBB->_CONF['template']['_CONF']['lang']['do_not_choose_to_file']);
		}

         // chek chmod  HooksCache.php
		  $url = "../cache/hooks_cache/HooksCache.php";
          if (!is_writable($url))
          {
		   $Permission = chmod('../cache/hooks_cache/HooksCache.php', 0777);
		   if (!$Permission)
		   {
		    $PowerBB->functions->error_no_foot("Change Permissions File: cache/hooks_cache/HooksCache.php To chmod 0777- يرجى اعطاء تصريح 777 لملف cache/HooksCache.php ليتم تركيب الاضافات");
		   }
		 }

		$ext = $PowerBB->functions->GetFileExtension($PowerBB->_FILES['files']['name']);

		if ($ext != '.xml')
		{
			$PowerBB->functions->error_no_foot($PowerBB->_CONF['template']['_CONF']['lang']['error_xml_file']);
		}

		if ($PowerBB->addons->IsAddons(array('where' => array('name',$PowerBB->_FILES['files']['name']))))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_extension'].' '.$PowerBB->_FILES['files']['name']. ' ' .$PowerBB->_CONF['template']['_CONF']['lang']['has_been_installed']);
		}


		$uploads_dir = '../addons';
		$uploaded = move_uploaded_file($PowerBB->_FILES['files']['tmp_name'] ,$uploads_dir .'/'.$PowerBB->_FILES['files']['name']);

		if (!$uploaded)
		{
			$PowerBB->functions->error_no_foot($PowerBB->_CONF['template']['_CONF']['lang']['is_unable_permison_folder_addons'].' '.$PowerBB->_FILES['files']['tmp_name']);
		}

       	$xml_code = @file_get_contents('../addons/'.$PowerBB->_FILES['files']['name']);
        $xml_code = $PowerBB->sys_functions->ReplaceMysqlExtension($xml_code);
		$plugin = $PowerBB->addons->xml_to_array($xml_code);

		$name = $PowerBB->_FILES['files']['name'];
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



			$PowerBB->functions->redirect('index.php?page=addons&amp;control=1&amp;main=1');
		}

	}

	/**
	 * Writing Addons Main
	 */

	function _WritingAddonsMain()
	{
		global $PowerBB;

        // show Addons List
		$AddonsArr 					= 	array();
		$AddonsArr['order']			=	array();
		$AddonsArr['order']['field']	=	'id';
		$AddonsArr['order']['type']	=	'DESC';
		$AddonsArr['proc'] 			= 	array();
		$AddonsArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');

		$PowerBB->_CONF['template']['while']['AddonsList'] = $PowerBB->addons->GetAddonsList($AddonsArr);

		$PowerBB->template->display('addons_writing');

    }


	/**
	 * Writing Addons Start
	 */
	function _WritingAddonsStart()
	{

		global $PowerBB;

			$HooksArr 	=	array();
			$HooksArr['field']	=	array();
			$HooksArr['field']['addon_id']           =       $PowerBB->_POST['addons'];
			$HooksArr['field']['main_place']         =       $PowerBB->_POST['place_of_hook'];
			$HooksArr['field']['place_of_hook']      =       $PowerBB->_POST['place_of_hook'];
			$HooksArr['field']['phpcode']	=	str_replace("'","{sq}",$PowerBB->_POST['phpcode']);

			$InsertHook = $PowerBB->hooks->InsertHooks($HooksArr);

			if ($InsertHook)
			{
   			   $this->update_cache();
	           $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['addon_added_successfully']);
			   $PowerBB->functions->redirect('index.php?page=addons&amp;control_hooks=1&amp;main=1');
			}



	}

	function _ControlMain()
	{
		global $PowerBB;

        // show Addons List
		$AddonsArr 					= 	array();
		$AddonsArr['order']			=	array();
		$AddonsArr['order']['field']	=	'id';
		$AddonsArr['order']['type']	=	'DESC';
		$AddonsArr['proc'] 			= 	array();
		$AddonsArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');

		$PowerBB->_CONF['template']['while']['AddonsList'] = $PowerBB->addons->GetAddonsList($AddonsArr);

		$PowerBB->template->display('addons_main');
	}


	function _EditMain()
	{
		global $PowerBB;
         /*
			if (empty($PowerBB->_GET['id']))
			{
				$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['addon_requested_does_not_exist']);
			}

			$AddonsEditArr				=	array();
		    $AddonsEditArr['where'] 	= 	array('id',$PowerBB->_GET['id']);

			$AddonsEdit = $PowerBB->addons->GetAddonsInfo($AddonsEditArr);
			$PowerBB->template->assign('AddonsEdit',$AddonsEdit);

            $AddonsEdit['phpcode'] = str_replace('{sq}',"'",$AddonsEdit['phpcode']);
            $PowerBB->template->assign('phpcode',$AddonsEdit['phpcode']);

            $AddonsEdit['installcode'] = str_replace('{sq}',"'",$AddonsEdit['installcode']);
            $PowerBB->template->assign('installcode',$AddonsEdit['installcode']);

            $AddonsEdit['uninstallcode'] = str_replace('{sq}',"'",$AddonsEdit['uninstallcode']);
            $PowerBB->template->assign('uninstallcode',$AddonsEdit['uninstallcode']);

            $AddonsEdit['hookname'] = str_replace('.tpl','',$AddonsEdit['hookname']);
            $PowerBB->template->assign('hookname',$AddonsEdit['hookname']);

		$PowerBB->template->display('addons_edit');
		*/
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['property_addon_editing_is_off']);
			$PowerBB->functions->redirect('index.php?page=addons&amp;control=1&amp;main=1');


	}

	function _EditStart()
	{
		global $PowerBB;
        /*

			if (empty($PowerBB->_GET['id']))
			{
				$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['addon_requested_does_not_exist']);
			}

		    $AddonsInfoArr				=	array();
		    $AddonsInfoArr['where'] 	= 	array('id',$PowerBB->_GET['id']);

			$AddonsInfo = $PowerBB->addons->GetAddonsInfo($AddonsInfoArr);
		    $del_file = @unlink('addons/'.$AddonsInfo['name']);

		$PowerBB->_POST['installcode'] = str_replace("'","{sq}",$PowerBB->_POST['installcode']);
		$PowerBB->_POST['uninstallcode'] = str_replace("'","{sq}",$PowerBB->_POST['uninstallcode']);

		$AddonsArr 			= 	array();
		$AddonsArr['field']	=	array();

		$AddonsArr['field']['name']             =       $PowerBB->_POST['name'];
		$AddonsArr['field']['title']            =       $PowerBB->_POST['title'];
		$AddonsArr['field']['version'] 		    =       $PowerBB->_POST['version'];
		$AddonsArr['field']['description'] 	    =       $PowerBB->_POST['description'];
		$AddonsArr['field']['author'] 	    	=       $PowerBB->_POST['author'];
		$AddonsArr['field']['url'] 		        =       $PowerBB->_POST['url'];
		$AddonsArr['field']['installcode'] 		=       $PowerBB->_POST['installcode'];
		$AddonsArr['field']['uninstallcode'] 	=       $PowerBB->_POST['uninstallcode'];
		$AddonsArr['field']['active'] 		    =       $PowerBB->_POST['active'];
		$AddonsArr['where'] 				    = 	array('id',$PowerBB->_GET['id']);

		$update = $PowerBB->addons->UpdateAddons($AddonsArr);

		if ($update)
		{

	          $start_xml = '<?xml version="1.0" encoding="ISO-8859-1"?>
	          ';
	          $x_title = '<title>'.$PowerBB->_POST['title'].'</title>
	          ';
	          $x_installcode = '<installcode><![CDATA['.$PowerBB->_POST['installcode'].']]></installcode>
	          ';
	          $x_uninstallcode = '<uninstallcode><![CDATA['.$PowerBB->_POST['uninstallcode'].']]></uninstallcode>
	          ';
	          $x_version = '<version>'.$PowerBB->_POST['version'].'</version>
	          ';
	          $x_description = '<description>'.$PowerBB->_POST['description'].'</description>
	          ';
	          $x_url = '<url>'.$PowerBB->_POST['url'].'</url>
	          ';
	          $x_author = '<author>'.$PowerBB->_POST['author'].'</author>
	          ';

	          $context = $start_xml.$x_hookname.$x_title.$x_phpcode.$x_installcode.$x_uninstallcode.$x_version.$x_description.$x_url.$x_author ;
              $context = str_replace('\'"',"'", $context);
              $context = str_replace('\\"','"', $context);
              $context = str_replace("\\'","'", $context);
              $context = str_replace('{sq}',"'", $context);

	          $uploads_dir = 'addons';
		      $fp = fopen($uploads_dir . '/' . $PowerBB->_POST['name'],'w');
		      $fw = fwrite($fp,$context);
		      fclose($fp);


			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['addon_updated_successfully']);
			$PowerBB->functions->redirect('index.php?page=addons&amp;control=1&amp;main=1');
		}
		*/
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['property_addon_editing_is_off']);

	   $PowerBB->functions->redirect('index.php?page=addons&amp;control=1&amp;main=1');

	}

	function _DelStart()
	{
		global $PowerBB;

			if (empty($PowerBB->_GET['id']))
			{
				$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['addon_requested_does_not_exist']);
			}
			$AddonsInfoArr				=	array();
			$AddonsInfoArr['where'] 	= 	array('id',$PowerBB->_GET['id']);

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
			$PowerBB->functions->redirect('index.php?page=addons&amp;control=1&amp;main=1');
	}

	function _ActiveStart()
	{
		global $PowerBB;

			if (empty($PowerBB->_GET['id']))
			{
				$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['addon_requested_does_not_exist']);
			}
		$addon_arr = array();
		$addon_arr['where'] = array('id',$PowerBB->_GET['id']);
		$addon_info = $PowerBB->addons->GetAddonsInfo($addon_arr);

       	$xml_code = @file_get_contents('../addons/'.$addon_info['name']);
        $xml_code = $PowerBB->sys_functions->ReplaceMysqlExtension($xml_code);
		$plugin = $PowerBB->addons->xml_to_array($xml_code);

		$name = $addon_info['name'];
		$title = $plugin['plugin']['attributes']['name'];
		$version = $plugin['plugin']['version']['value'];
		$description = $plugin['plugin']['description']['value'];
		$author = $plugin['plugin']['author']['value'];
		$url = $plugin['plugin']['url']['value'];


	        $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['addon_activated_successfully']);
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


		$AddonsArr 			= 	array();
		$AddonsArr['field']	=	array();

		$AddonsArr['field']['active'] 		    =       '1';
		$AddonsArr['where'] 				    = 	array('id',$PowerBB->_GET['id']);

		$update = $PowerBB->addons->UpdateAddons($AddonsArr);

		if ($update)
		{
             $this->update_cache();

			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['addon_activated_successfully']);
			$PowerBB->functions->redirect('index.php?page=addons&amp;control=1&amp;main=1');
		}
	}

	function _NoActiveStart()
	{
		global $PowerBB;

			if (empty($PowerBB->_GET['id']))
			{
				$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['addon_requested_does_not_exist']);
			}
			    $AddonsInfoArr				=	array();
		    	$AddonsInfoArr['where'] 	= 	array('id',$PowerBB->_GET['id']);

			$AddonsInfo = $PowerBB->addons->GetAddonsInfo($AddonsInfoArr);


				if($this->del_hooks($AddonsInfo)){
					$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['addon_hooks_disabled_successfully']);
				}
				if($this->back_templates($AddonsInfo)){
					$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['addon_hooks_disabled_successfully']);
				}
				if($this->del_phrases($AddonsInfo)){
					//$PowerBB->functions->msg("تم حذف العبارات بنجاح ،،");
				}

     $this->update_cache();

		$AddonsArr 			= 	array();
		$AddonsArr['field']	=	array();

		$AddonsArr['field']['active'] 		    =       '0';
		$AddonsArr['where'] 				    = 	array('id',$PowerBB->_GET['id']);

		$update = $PowerBB->addons->UpdateAddons($AddonsArr);

		if ($update)
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['addon_disabled_successfully']);
			$PowerBB->functions->redirect('index.php?page=addons&amp;control=1&amp;main=1');
		}
	}

	function _ExportStart()
	{
		global $PowerBB;

		$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');

		if (empty($PowerBB->_GET['id']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['message']);
		}


		$AddonsInfoArr				=	array();
	    $AddonsInfoArr['where'] 	= 	array('id',$PowerBB->_GET['id']);

		$AddonsInfo = $PowerBB->addons->GetAddonsInfo($AddonsInfoArr);

		if ($AddonsInfo)
		{

			$Adress	= 	$PowerBB->functions->GetForumAdress();
           $Adress = str_replace($PowerBB->admincpdir."/", '', $Adress);
		   $filename = $Adress.'addons/'.$AddonsInfo['name'];
           @header("Content-Type: application/octet-stream");
           @header("Content-Disposition: attachment;filename=" . rawurlencode($AddonsInfo['name']) . "");
		   @header('Content-type: application/download');
		   @header("Content-Transfer-Encoding: binary");
          $read = @readfile($Adress.'addons/'.rawurlencode($AddonsInfo['name']));
		   exit;
		}

	}

	function _ExportWriting()
	{
		global $PowerBB;

         $PowerBB->_POST['context'] = str_replace('\\"', '"', $PowerBB->_POST['context']);
         $PowerBB->_POST['context'] = str_replace("\\'", "'", $PowerBB->_POST['context']);

           $uploads_dir = 'addons';
	       $fp = fopen($uploads_dir . '/' . $PowerBB->_GET['xml_name'],'w');
	       $fw = fwrite($fp,$PowerBB->_POST['context']);
	       fclose($fp);
		if ($fp)
		{
          $Adress	= 	$PowerBB->functions->GetForumAdress();
           $Adress = str_replace($PowerBB->admincpdir."/", '', $Adress);
		  $filename = $Adress.'addons/'.$PowerBB->_GET['xml_name'];
          @header("Content-Type: application/octet-stream");
          @header("Content-Disposition: attachment;filename=" . rawurlencode($PowerBB->_GET['xml_name']) . "");
	      @header('Content-transfer-encoding: binary');
		  @header('Content-type: application/download');
          $read = @readfile($Adress.'addons/'.rawurlencode($PowerBB->_GET['xml_name']));
		  exit;
       }
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
				$xml_code = @file_get_contents('../addons/'.$plugin_info['name']);
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


			     $del_file = @unlink('../addons/'.$plugin_info['name']);
			    //
				$Delete = $PowerBB->templates_edits->DeleteTemplatesEdits(array('where'=>array('addon_id',$PowerBB->_GET['id'])));
				//
			    $DelArr 			= 	array();
				$DelArr['where'] 	= 	array('id',$PowerBB->_GET['id']);

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
		  $Permission = chmod('../cache/hooks_cache/HooksCache.php', 0777);
		  if (!$Permission)
		  {
		    $PowerBB->functions->error_no_foot("Change Permissions File: cache/hooks_cache/HooksCache.php To chmod 0777- يرجى اعطاء تصريح 777 لملف cache/hooks_cache/HooksCache.php ليتم تركيب الاضافات");
		  }
		}
		fwrite($open,$data);
		fclose($open);


	}


	function _ControlHook()
	{
		global $PowerBB;

        // show Addons List
		$HooksArr 					= 	array();
		$HooksArr['order']			=	array();
		$HooksArr['order']['field']	=	'id';
		$HooksArr['order']['type']	=	'DESC';
		$HooksArr['proc'] 			= 	array();
		$HooksArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');

		$PowerBB->_CONF['template']['while']['HooksList'] = $PowerBB->hooks->GetHooksList($HooksArr);

		$PowerBB->template->display('hooks_main');
	}

	function _MainEditHook()
	{
		global $PowerBB;

		$HooksInfoArr				=	array();
	    $HooksInfoArr['where'] 	= 	array('id',$PowerBB->_GET['id']);

		$HooksInfo = $PowerBB->hooks->GetHooksInfo($HooksInfoArr);

        $PowerBB->template->assign('HooksInfo',$HooksInfo);

        // show Addons List
		$HooksArr 					= 	array();
		$HooksArr['order']			=	array();
		$HooksArr['order']['field']	=	'id';
		$HooksArr['order']['type']	=	'DESC';
		$HooksArr['proc'] 			= 	array();
		$HooksArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');

		$PowerBB->_CONF['template']['while']['HooksList'] = $PowerBB->hooks->GetHooksList($HooksArr);

        $Addonid =  $PowerBB->_GET['id'];
        // show Addons List
		$AddonsArr 					= 	array();
		$AddonsArr['order']			=	array();
		$AddonsArr['order']['field']	=	'id';
		$AddonsArr['order']['type']	=	'DESC';

		$AddonsArr = array();
		$AddonsArr['where'] 				= 	array();
		$AddonsArr['where'][0] 			= 	array();
		$AddonsArr['where'][0]['name'] 	= 	'id not in ('.$Addonid.') AND active';
		$AddonsArr['where'][0]['oper'] 	= 	'=';
		$AddonsArr['where'][0]['value'] 	= 	'0';

		$AddonsArr['proc'] 			= 	array();
		$AddonsArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');

		$PowerBB->_CONF['template']['while']['AddonsList'] = $PowerBB->addons->GetAddonsList($AddonsArr);

		$PowerBB->template->display('hook_edit');
	}

	function _StartEditHook()
	{
		global $PowerBB;
		$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');

		if (empty($PowerBB->_GET['id']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['plugin_does_not_exist']);
		}

		$HooksInfoArr				=	array();
	    $HooksInfoArr['where'] 	= 	array('id',$PowerBB->_GET['id']);

		$HooksInfo = $PowerBB->hooks->GetHooksInfo($HooksInfoArr);

		$HookArr 			= 	array();
		$HookArr['field']	=	array();

		$HookArr['field']['addon_id']           =       $PowerBB->_POST['addons'];
		$HookArr['field']['main_place']         =       $PowerBB->_POST['place_of_hook'];
		$HookArr['field']['place_of_hook']      =       $PowerBB->_POST['place_of_hook'];
		$HookArr['field']['phpcode'] 		    =       $PowerBB->_POST['phpcode'];
		$HookArr['where'] 				        = 	array('id',$PowerBB->_GET['id']);

		$update = $PowerBB->hooks->UpdateHooks($HookArr);

         $this->update_cache();

			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['plugin_updated_successfully']);
			$PowerBB->functions->redirect('index.php?page=addons&amp;control_hooks=1&amp;main=1');
	}

}

?>
