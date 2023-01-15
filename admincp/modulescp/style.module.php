<?php
(!defined('IN_PowerBB')) ? die() : '';
define('IN_ADMIN',true);
define('DONT_STRIPS_SLIASHES',true);
//Edited------------------------------------
$CALL_SYSTEM                         =  array();
$CALL_SYSTEM['ADDONS']              =   true;
$CALL_SYSTEM['HOOKS']               =   true;
$CALL_SYSTEM['STYLE']               =  true;
$CALL_SYSTEM['TEMPLATE']            =  true;
$CALL_SYSTEM['TEMPLATESEDITS']      =  true;
$CALL_SYSTEM['GROUP']               = 	true;
$CALL_SYSTEM['MEMBER']              = 	true;


define('CLASS_NAME','PowerBBStyleMOD');
//We are going to use the addons module which previously includes common.php.
include('addons.module.php');
class PowerBBStyleMOD extends _functions
{
	function run()
	{
		global $PowerBB;

       if (!$PowerBB->_GET['download'])
        {
		 $PowerBB->template->display('header');
		}
			if ($PowerBB->_CONF['rows']['group_info']['admincp_style'] == '0')
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
					$this->_AddStart();
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
			elseif ($PowerBB->_GET['import'])
			{
				if ($PowerBB->_GET['start'])
				{
					$this->_ImportStart();
				}
			}
			elseif ($PowerBB->_GET['edit_css'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_CssMain();
				}
				elseif ($PowerBB->_GET['start'])
				{
					$this->_CssStart();
				}
			}
			elseif ($PowerBB->_GET['style_order'])
			{
				if ($PowerBB->_GET['start'])
				{
					$this->_OrderStyleStart();
				}
			}
			elseif ($PowerBB->_GET['download'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_DownloadStyle();
				}

			}
            elseif($PowerBB->_GET['default'])
			{
				$this->_DefaultMain();
			}
            elseif($PowerBB->_GET['mobile'])
			{
				$this->_choose_mobile_Style();
			}
          eval($PowerBB->functions->get_fetch_hooks('run_style'));
			$PowerBB->template->display('footer');
		}
	}

	function _AddMain()
	{
		global $PowerBB;

		$StyleDir = ('../look/styles/forum/');

		if (is_dir($StyleDir))
		{
			$dir = opendir($StyleDir);

			if ($dir)
			{
				while (($file = readdir($dir)) !== false)
				{
					if ($file == '.'
						or $file == '..')
					{
						continue;
					}

					$StylesList[]['filename'] = $file;
				}

				closedir($dir);
			}
		}

		$PowerBB->_CONF['template']['foreach']['StyleList'] = $StylesList;

		 $TotalStylesNm = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['style'] . " WHERE id"));
		 $PowerBB->template->assign('order',$TotalStylesNm+1);

		$PowerBB->template->display('style_add');
	}
	function _AddStart()
	{
		global $PowerBB;

		$this->_ImportStart();
	}

	function _ControlMain()
	{
		global $PowerBB;

		$StlArr 					= 	array();
		$StlArr['proc'] 			= 	array();
		$StlArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');
		$StlArr['order']			=	array();
		$StlArr['order']['field']	=	'style_order';
		$StlArr['order']['type']	=	'ASC ';

		$PowerBB->_CONF['template']['while']['StlList'] = $PowerBB->core->GetList($StlArr,'style');

		$StlDefArr 					= 	array();
		$StlDefArr['proc'] 			= 	array();
		$StlDefArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');
		$StlDefArr['order']			=	array();
		$StlDefArr['order']['field']	=	'id';
		$StlDefArr['order']['type']	=	'DESC';

		$PowerBB->_CONF['template']['while']['StlDef'] = $PowerBB->core->GetList($StlDefArr,'style');

		$PowerBB->template->display('styles_main');
	}

	function _EditMain()
	{
		global $PowerBB;

		$this->check_by_id($PowerBB->_CONF['template']['Inf']);

		$PowerBB->template->display('style_edit');
	}

	function _EditStart()
	{
		global $PowerBB;

		$this->check_by_id($Inf);

		if (empty($PowerBB->_POST['name'])
			or empty($PowerBB->_POST['style_path'])
			or empty($PowerBB->_POST['image_path']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_fill_in_all_the_information']);
		}

		if ($PowerBB->_POST['order'] == '')
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_fill_in_all_the_information']);
         }

		//////////

		$StlArr 			= 	array();
		$StlArr['field']	=	array();

		$StlArr['field']['style_title'] 	= 	$PowerBB->_POST['name'];
		$StlArr['field']['style_path'] 		= 	$PowerBB->_POST['style_path'];
		$StlArr['field']['style_order'] 	= 	$PowerBB->_POST['order'];
		$StlArr['field']['style_on'] 		= 	$PowerBB->_POST['style_on'];
		$StlArr['field']['image_path'] 		= 	$PowerBB->_POST['image_path'];
		$StlArr['where']					= 	array('id',$Inf['id']);

		$update = $PowerBB->core->Update($StlArr,'style');

		//////////

		if ($update)
		{
			//////////

			$UpdateArr 				= 	array();
			$UpdateArr['field']		=	array();

			$UpdateArr['field']['should_update_style_cache'] 	= 	1;
			$UpdateArr['where'] 								= 	array('style',$Inf['id']);

			$cache_update = $PowerBB->core->Update($UpdateArr,'member');

			if ($PowerBB->_POST['style_on'] == '0')
			{
			$UpdateMemArr 				= 	array();
			$UpdateMemArr['field']		=	array();

			$UpdateMemArr['field']['style'] 	= 	$PowerBB->_CONF['info_row']['def_style'];
			$UpdateMemArr['where'] 				= 	array('style',$Inf['id']);

			$update = $PowerBB->member->UpdateMember($UpdateMemArr);
			}

			//////////

			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Style_has_been_updated_successfully']);
			$PowerBB->functions->redirect('index.php?page=style&amp;control=1&amp;main=1');

			//////////
		}

		//////////
	}

	function _DelMain()
	{
		global $PowerBB;

		$this->check_by_id($PowerBB->_CONF['template']['Inf']);

		$PowerBB->template->display('style_del');
	}
	//Edited---------------
	function _DelStart()
	{
		global $PowerBB;

		$this->check_by_id($Inf);

		if ($PowerBB->_CONF['info_row']['def_style'] == $Inf['id'])
	     {
             $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['You_can_not_delete_the_default_Style']);
         }

        //
		if ($PowerBB->member->IsMember(array('where' => array('style',$Inf['id']))))
		{
		 $UpdateArr 				                = 	array();
		 $UpdateArr['field'] 	                    = 	array();
		 $UpdateArr['field']['style'] 			    = 	$PowerBB->_CONF['info_row']['def_style'];
		 $UpdateArr['where']						=	array('style',$Inf['id']);

		 $update = $PowerBB->core->Update($UpdateArr,'member');
		}

         // deletd all cache templates for thes id style
        $styleid			= $Inf['id'];
		$_query1= $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['template'] . " WHERE styleid = '$styleid'");
		while ($row1 = $PowerBB->DB->sql_fetch_array($_query1))
		{
		      $templates_dir = ("../cache/templates_cache/".$row1['title']."_".$row1['styleid'].".php");
			if (file_exists($templates_dir))
			{
			  $cache_del = @unlink($templates_dir);
			}

		}

		$DelArr 			= 	array();
		$DelArr['where'] 	= 	array('id',$Inf['id']);
		$del = $PowerBB->core->Deleted($DelArr,'style');

		$DelTemplatesArr 			= 	array();
		$DelTemplatesArr['where'] 	= 	array('styleid',$Inf['id']);
		$deltemplates = $PowerBB->core->Deleted($DelTemplatesArr,'template');

		if ($del)
		{			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Style_has_been_deleted_successfully']);
			$PowerBB->functions->redirect('index.php?page=style&amp;control=1&amp;main=1');
		}
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

			   $xml_code = @file_get_contents("../".$PowerBB->_POST['serverfile']);

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

			$xml_code = @file_get_contents($uploads_dir.'/'.$PowerBB->_FILES['files']['name']);

		}

			if (strstr($xml_code,'decode="0"'))
			{
				$xml_code = str_replace('decode="0"','decode="1"',$xml_code);
				preg_match_all('/<!\[CDATA\[(.*?)\]\]>/is', $xml_code, $match);
				foreach($match[0] as $val)
				{
				$xml_code = str_replace($val,base64_encode($val),$xml_code);
				}

			}

		$import = $PowerBB->functions->xml_array($xml_code);
		$title = $import['styles_attr']['name'];
		$pbbversion = $import['styles_attr']['pbbversion'];

	         if ($PowerBB->_POST['anyversion'] == '0')
	         {
			  if ($pbbversion != $PowerBB->_CONF['info_row']['MySBB_version'])
			  {
			    $PowerBB->_CONF['template']['_CONF']['lang']['version_style_Different'] = str_replace("{copyright}", $PowerBB->_CONF['info_row']['MySBB_version'], $PowerBB->_CONF['template']['_CONF']['lang']['version_style_Different']);
		       $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['version_style_Different']);
	          }
	 	  	}

				$image_path = $import['styles_attr']['image_path'];
				$style_path = $import['styles_attr']['style_path'];
				$Templates = $import['styles']['templategroup'];
				$Templates_number = sizeof($import['styles']['templategroup']['template'])/2;

		if ($PowerBB->core->Is(array('where' => array('style_title',$title)),'style'))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['This_Style']. ' ' . $title . ' ' .$PowerBB->_CONF['template']['_CONF']['lang']['Already_added']);
		}
		else
	    {
	         $singleoriginalfile ="../cache/singles_templates_original_default_style.xml";

			if (file_exists($singleoriginalfile))
			{
			   $xml_code = @file_get_contents($singleoriginalfile);
			}
       		$import_original = $PowerBB->functions->xml_array($xml_code_original);
			$SingleTemplates = $import_original['templategroup'];


	       	$StlArr 					= 	array();
			$StlArr['field']			=	array();

			$StlArr['field']['style_title'] 	= 	$title;
			$StlArr['field']['style_path'] 		= 	$style_path;
			$StlArr['field']['style_order'] 	= 	$PowerBB->_POST['order'];
			$StlArr['field']['style_on'] 		= 	$PowerBB->_POST['style_on'];
			$StlArr['field']['image_path'] 		= 	$image_path;

			//Edited----------------------------------------
			$insert = $PowerBB->style->InsertStyle($StlArr);


					$styleid = $PowerBB->DB->sql_insert_id();

		            $x = 0;

     			while ($x < $Templates_number)
     			{

						$templatetitle = $Templates['template'][$x.'_attr']['name'];
						$template_version = $Templates['template'][$x.'_attr']['version'];
						$template_version = str_replace(".", "", $template_version);
						if ($Templates['template'][$x.'_attr']['decode'] == '1'
						or $template_version <= '301')
						{
						$template = @base64_decode($Templates['template'][$x]);
     				    $template_un = @base64_decode($SingleTemplates[$templatetitle]);
     				    }
     				    else
						{
						$template = $Templates['template'][$x];
     				    $template_un = $SingleTemplates[$templatetitle];
     				    }
     				    $template = str_replace("<![CDATA[","", $template);
						$template = str_replace("]]>","", $template);
     				    $template = str_replace("//<![CDATA[", "", $template);
						$template = str_replace("//]]>", "", $template);
     				    $template_un = str_replace("//<![CDATA[", "", $template_un);
						$template_un = str_replace("//]]>", "", $template_un);

						$templatetype = $Templates['template'][$x.'_attr']['templatetype'];
						$dateline = $Templates['template'][$x.'_attr']['date'];
						$product = $Templates['template'][$x.'_attr']['product'];
						$version = $Templates['template'][$x.'_attr']['version'];
						$username = $Templates['template'][$x.'_attr']['username'];
			            $template = str_replace("'", "&#39;", $template);
			            $template_un = str_replace("'", "&#39;", $template_un);

						$InsertTemplatesArr	=	array();
						$InsertTemplatesArr['field']	=	array();
						$InsertTemplatesArr['field']['styleid']	=	$styleid;
						$InsertTemplatesArr['field']['title']	=	$templatetitle;
						$InsertTemplatesArr['field']['template']	=	$template;
						$InsertTemplatesArr['field']['template_un']	=	$template_un;
						$InsertTemplatesArr['field']['templatetype']	=	$templatetype;
						$InsertTemplatesArr['field']['dateline']	=	$dateline;
						$InsertTemplatesArr['field']['username']	=	$username;
						$InsertTemplatesArr['field']['version']	=	$version;
						$InsertTemplatesArr['field']['product']	=	$product;
						$Insert = $PowerBB->core->Insert($InsertTemplatesArr,'template');

                     $x += 1;
     			}

           if ($insert)
			{
		       $deltemplates = $PowerBB->DB->sql_query("DELETE FROM " . $PowerBB->table['template'] . " WHERE styleid = '$styleid' and title = ''");

				$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Style_has_been_added_successfully']);
				//Apply template edits of our addons
				$Addons = $PowerBB->addons->GetAddonsList(array('where' => array('active',1)));
				if ( !empty($Addons) )
				{
					//lets start using the addons module abit
					$AddonsMod = new PowerBBAddonsMOD();
					foreach ($Addons as $key => $addonrow )
					{
						$xml_code = @file_get_contents('../addons/'.$addonrow['name']);
						$plugin = $PowerBB->addons->xml_to_array($xml_code);
						if(is_array($plugin['plugin']['templates']))
						{
							$AddonsMod->edit_templates($plugin,$addonrow,$styleid);
						}
					}
					$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['edit_templates_style_addons']);
				}

				  // Delete the temporary file if possible
					@unlink($uploads_dir.'/'.$PowerBB->_FILES['files']['name']);
				$PowerBB->functions->redirect2('index.php?page=style&amp;control=1&amp;main=1',3);

			}
			//---------------------------------------------------
	        else
	        {
	        	$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Style_is_not_added']);
	        }

        }
    }


	//----------------------------------------
    function _CssMain()
	{
		global $PowerBB;

		$this->check_by_id($PowerBB->_CONF['template']['Inf']);

		if (empty($PowerBB->_GET['id']))
    	{
    		$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Style_requested_does_not_exist']);
    	}

		$CssArr 			= 	array();
		$CssArr['where'] 	= 	array('id',$PowerBB->_GET['id']);

        $Css = $PowerBB->core->GetInfo($CssArr,'style');

			if ($PowerBB->_CONF['info_row']['content_dir'] == 'ltr'
			or $PowerBB->_CONF['LangDir'] == 'ltr')
			{
				  $style_path= $Css['style_path'];
				  $style_path = str_replace('style.css','style_ltr.css',$style_path);
				 if (file_exists($style_path))
				  {
                   $path = $style_path;
					}
					else
					{
				       $path = $Css['style_path'];
					}

			}
			else
			{
		       $path = $Css['style_path'];
			}



        if (!file_exists($path))
    	{
    		$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Style_requested_does_not_exist']);
    	}
    	$lines = file($path);
    	$context = '';

    	foreach ($lines as $line)
    	{
    		$context .= $line;
    	}

    	$context = $PowerBB->functions->CleanVariable($context,'unhtml');
        $PowerBB->template->assign('style_path',$Css['style_path']);
    	$PowerBB->template->assign('css_context',$context);

		$PowerBB->template->display('css_edit');
	}

	function _CssStart()
	{
		global $PowerBB;

		$this->check_by_id($Inf);

    	$StyleInfo = false;

    	$this->check_by_id($StyleInfo);

    	if (empty($PowerBB->_GET['style_path']))
    	{
    		$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
    	}
    	if (empty($PowerBB->_GET['id']))
    	{
    		$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Style_requested_does_not_exist']);
    	}

    	$PowerBB->_GET['style_path'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['style_path'],'html');



			if ($PowerBB->_CONF['info_row']['content_dir'] == 'ltr'
			or $PowerBB->_CONF['LangDir'] == 'ltr')
			{
				  $style_path= $PowerBB->_GET['style_path'];
				  $style_path = str_replace('style.css','style_ltr.css',$style_path);
				 if (file_exists($style_path))
				  {
                   $path = $style_path;
					}
					else
					{
				       $path = $PowerBB->_GET['style_path'];
					}

			}
			else
			{
		       $path = $PowerBB->_GET['style_path'];
			}

    	if (!file_exists($path))
    	{
    		$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Style_requested_does_not_exist']);
    	}

    	// To be more advanced :D
    	if (!is_writable($path))
    	{
    		$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['is_not_writable']);
    	}

    	//$PowerBB->_POST['css_context'] = stripslashes($PowerBB->_POST['css_context']);

    	$fp = fopen($path,'w+');
    	$fw = fwrite($fp,$PowerBB->_POST['css_context']);

    	if ($fw)
    	{

            $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['CSS_has_been_updated_successfully']);
			$PowerBB->functions->redirect('index.php?page=style&amp;edit_css=1&amp;main=1&amp;id=' . $PowerBB->_GET['id']);
    	}

		//////////
	}


	function _DefaultMain()
	{
		global $PowerBB;

        $update = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['default'],'var_name'=>'def_style'));

		//////////

		if ($update)
		{
			//////////

           $getmember_query = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['member'] . " ORDER BY id DESC");

             while ($getmember_row = $PowerBB->DB->sql_fetch_array($getmember_query))
             {

				$UpdateArr 				                = 	array();
				$UpdateArr['field'] 	                    = 	array();
				$UpdateArr['field']['style'] 			    = 	$PowerBB->_POST['default'];
				$UpdateArr['where']						=	array('id',$getmember_row['id']);

				$update = $PowerBB->core->Update($UpdateArr,'member');

             }

			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Last_updated_successfully']);
			$PowerBB->functions->redirect('index.php?page=style&amp;control=1&amp;main=1');

			//////////
		}

    }

  function _choose_mobile_Style()
	{
		global $PowerBB;

        $update = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['mobile_style'],'var_name'=>'mobile_style_id'));

		//////////

		if ($update)
		{
			//////////

			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Last_updated_successfully']);
			$PowerBB->functions->redirect('index.php?page=style&amp;control=1&amp;main=1');

			//////////
		}

    }
	function _DownloadStyle()
    {
    	global $PowerBB;

		$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');

		if (empty($PowerBB->_GET['id']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}
		$StyleArr 			= 	array();
		$StyleArr['where'] 	= 	array('id',$PowerBB->_GET['id']);

		$StyleInfo = $PowerBB->core->GetInfo($StyleArr,'style');

		if (!$StyleInfo)
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Style_requested_does_not_exist']);
		}

      $styleid = $PowerBB->_GET['id'];
		$style_title = $StyleInfo['style_title'];
		$image_path  = $StyleInfo['image_path'];
		$style_path = $StyleInfo['style_path'];
      //$querytemplate = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['template'] . " WHERE styleid = '$styleid' ");
		$TemplateArr 						= 	array();
		$TemplateArr['get_from']				=	'db';

		$TemplateArr['proc'] 				= 	array();
		$TemplateArr['proc']['*'] 			= 	array('method'=>'clean','param'=>'html');

		$TemplateArr['order']				=	array();
		$TemplateArr['order']['field']		=	'templateid';
		$TemplateArr['order']['type']		=	'ASC';

		$TemplateArr['where']				=	array();
		$TemplateArr['where'][0]['name']		= 	'styleid';
		$TemplateArr['where'][0]['oper']		= 	'=';
		$TemplateArr['where'][0]['value']	= 	$styleid;

		// Get Templatets
		$querytemplate = $PowerBB->core->GetList($TemplateArr,'template');
	    $filename = "PBBoard_Style.xml";
        $version = $PowerBB->_CONF['info_row']['MySBB_version'];

     foreach ($querytemplate as $getTemplate_row)
      {
       $add_template = '1';
        $username = $getTemplate_row['username'];
        $dateline = $PowerBB->_CONF['now'];
        $product .= $getTemplate_row['product'];
        $title = $getTemplate_row['title'];
        $context = $getTemplate_row['template'];
		$context = str_replace("//<![CDATA[", "", $context);
		$context = str_replace("//]]>", "", $context);
        $context = str_replace("&#39;", "'", $context);

       // dont add addons code in style templates
      $batemplates_edits = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['templates_edits'] . "");
       while ($back_templates = $PowerBB->DB->sql_fetch_array($batemplates_edits))
       {
         if($title == $back_templates['template_name'])
         {

             if($back_templates['action'] == 'new'){
			  $add_template = '0';
			}
			 elseif($back_templates['action'] == 'replace')
			{
			  $context = str_replace("&#39;", "'", $context);
		      $context = str_replace($back_templates['text'],$back_templates['old_text'],$context);
		      $add_template = '1';
			}
			else
			{
				$context = str_replace("&#39;", "'", $context);
				$context	=	str_replace("\n".$back_templates['text'],"", $context);
				$context	=	str_replace($back_templates['text']."\n","", $context);
				$context	=	str_replace($back_templates['text'],"", $context);
				$add_template = '1';
			}

          ///

         }
       }

       // $template_un = str_replace("&#39;", "'", $template_un);
       if ($add_template)
       {
        $xml .= "<template name=\"$title\" templatetype=\"template\" date=\"$dateline\" username=\"$username\" decode=\"0\" version=\"$version\"><![CDATA[$context]]></template>\r\n";
       }
      }
        if (empty($product))
        {
         $product = "PBBoard";
        }
        $xmlup = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n<styles name=\"$style_title\" pbbversion=\"$version\" product=\"$product\" image_path =\"$image_path\" style_path=\"$style_path\" type=\"custom\">\r\n<templategroup>\r\n";
        $xmldun = "</templategroup>\r\n</styles>\r\n";
		header("Content-disposition: attachment; filename=".$filename."");
		header("Content-type: application/octet-stream");
		header("Content-Length: ".strlen($xmlup.$xml.$xmldun));
		header("Pragma: no-cache");
		header("Expires: 0");
		echo $xmlup.$xml.$xmldun;
		exit;

	}

	function _OrderStyleStart()
	{
		global $PowerBB;
       if($PowerBB->_POST['template_search']){
       $PowerBB->functions->redirect2('index.php?page=template&amp;search=1&amp;main=1');
       	exit;
       }

 		$StyleArr 					= 	array();
		$StyleArr['get_from']			=	'db';
		$StyleArr['proc'] 			= 	array();
		$StyleArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');
		$StyleArr['order']			=	array();
		$StyleArr['order']['field']	=	'style_order';
		$StyleArr['order']['type']	=	'ASC';

		$StyleList = $PowerBB->core->GetList($StyleArr,'style');

		$x = 0;
		$y = sizeof($StyleList);
		$s = array();

		while ($x < $y)
		{
			$name = 'order-' . $StyleList[$x]['id'];

			if ($StyleList[$x]['order'] != $PowerBB->_POST[$name])
			{
				$UpdateArr 						= 	array();

				$UpdateArr['field']		 		= 	array();
				$UpdateArr['field']['style_order'] 	= 	$PowerBB->_POST[$name];

				$UpdateArr['where'] 			=	array('id',$StyleList[$x]['id']);

				$update = $PowerBB->core->Update($UpdateArr,'style');


				$s[$StyleList[$x]['id']] = ($update) ? 'true' : 'false';
			}

			$x += 1;
		}
		//////////

		if ($update)
		{
			//////////
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['the_order_of_presentation_was_saved_successfully']);
			$PowerBB->functions->redirect('index.php?page=style&amp;control=1&amp;main=1');

			//////////
		}

		//////////
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


        $CatArr = $PowerBB->DB->sql_query("SELECT  *   FROM " . $PowerBB->table['style'] . " WHERE id = ".$PowerBB->_GET['id']." ");
		$Inf = $PowerBB->DB->sql_fetch_array($CatArr);

		if ($Inf == false)
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Style_requested_does_not_exist']);
		}

		$PowerBB->functions->CleanVariable($Inf,'html');
	}
}

?>
