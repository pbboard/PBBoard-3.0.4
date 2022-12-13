<?php

/**
*  upgrader to version 3.0.2
*/

define('NO_TEMPLATE',true);

include('../common.php');

class PowerBBTHETA extends PowerBBInstall
 {
	var $_TempArr 	= 	array();
	var $_Masseges	=	array();

	function CheckVersion()
	{
		global $PowerBB;

		return ($PowerBB->_CONF['info_row']['MySBB_version'] == '3.0.1') ? true : false;
	}


	function UpdateVersion()
	{
		global $PowerBB;

		$update = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['info'] . " SET value='3.0.2' WHERE var_name='MySBB_version'");

		return ($update) ? true : false;
	}

	function Update_allow_avatar()
	{
		global $PowerBB;

		$update = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['info'] . " SET value='1' WHERE var_name='allow_avatar'");

		return ($update) ? true : false;
	}
	function AddUsers_Security()
	{
		global $PowerBB;
		$insert = $PowerBB->DB->sql_query('INSERT INTO ' . $PowerBB->table['info'] . " SET var_name='users_security',value='1'");

		return ($insert) ? true : false;
	}

    function AddGroups_Security()
	{
		global $PowerBB;

		$this->_TempArr['AddArr']					=	array();
		$this->_TempArr['AddArr']['table']			=	$PowerBB->table['group'];
		$this->_TempArr['AddArr']['field_name']		=	'groups_security';
		$this->_TempArr['AddArr']['field_des']		=	"int( 1 ) NOT NULL DEFAULT '1'";

		$add = $this->add_field($this->_TempArr['AddArr']);

		unset($this->_TempArr['AddArr']);

		return ($add) ? true : false;
	}

    function AddProfilePhoto()
	{
		global $PowerBB;

		$this->_TempArr['AddArr']					=	array();
		$this->_TempArr['AddArr']['table']			=	$PowerBB->table['group'];
		$this->_TempArr['AddArr']['field_name']		=	'profile_photo';
		$this->_TempArr['AddArr']['field_des']		=	"int( 1 ) NOT NULL DEFAULT '1'";

		$add = $this->add_field($this->_TempArr['AddArr']);

		unset($this->_TempArr['AddArr']);

		return ($add) ? true : false;
	}

    function Add_send_security_code()
	{
		global $PowerBB;

		$this->_TempArr['AddArr']					=	array();
		$this->_TempArr['AddArr']['table']			=	$PowerBB->table['member'];
		$this->_TempArr['AddArr']['field_name']		=	'send_security_code';
		$this->_TempArr['AddArr']['field_des']		=	"int(1) NOT NULL DEFAULT '0'";

		$add = $this->add_field($this->_TempArr['AddArr']);

		unset($this->_TempArr['AddArr']);

		return ($add) ? true : false;
	}

    function Add_send_security_error_login()
	{
		global $PowerBB;

		$this->_TempArr['AddArr']					=	array();
		$this->_TempArr['AddArr']['table']			=	$PowerBB->table['member'];
		$this->_TempArr['AddArr']['field_name']		=	'send_security_error_login';
		$this->_TempArr['AddArr']['field_des']		=	"int(1) NOT NULL DEFAULT '0'";

		$add = $this->add_field($this->_TempArr['AddArr']);

		unset($this->_TempArr['AddArr']);

		return ($add) ? true : false;
	}

    function Add_date_message_chat()
	{
		global $PowerBB;

		$this->_TempArr['AddArr']					=	array();
		$this->_TempArr['AddArr']['table']			=	$PowerBB->table['chat'];
		$this->_TempArr['AddArr']['field_name']		=	'date';
		$this->_TempArr['AddArr']['field_des']		=	"VARCHAR( 100 ) NOT NULL";

		$add = $this->add_field($this->_TempArr['AddArr']);

		unset($this->_TempArr['AddArr']);

		return ($add) ? true : false;
	}

    function Add_profile_cover_photo()
	{
		global $PowerBB;

		$this->_TempArr['AddArr']					=	array();
		$this->_TempArr['AddArr']['table']			=	$PowerBB->table['member'];
		$this->_TempArr['AddArr']['field_name']		=	'profile_cover_photo';
		$this->_TempArr['AddArr']['field_des']		=	"VARCHAR( 255 ) NOT NULL";

		$add = $this->add_field($this->_TempArr['AddArr']);

		unset($this->_TempArr['AddArr']);

		return ($add) ? true : false;
	}

    function Add_profile_cover_photo_position()
	{
		global $PowerBB;

		$this->_TempArr['AddArr']					=	array();
		$this->_TempArr['AddArr']['table']			=	$PowerBB->table['member'];
		$this->_TempArr['AddArr']['field_name']		=	'profile_cover_photo_position';
		$this->_TempArr['AddArr']['field_des']		=	"VARCHAR( 255 ) NOT NULL";

		$add = $this->add_field($this->_TempArr['AddArr']);

		unset($this->_TempArr['AddArr']);

		return ($add) ? true : false;
	}

 function Add_membergroupids()
	{
		global $PowerBB;

		$this->_TempArr['AddArr']					=	array();
		$this->_TempArr['AddArr']['table']			=	$PowerBB->table['member'];
		$this->_TempArr['AddArr']['field_name']		=	'membergroupids';
		$this->_TempArr['AddArr']['field_des']		=	"CHAR( 250 ) NOT NULL";

		$add = $this->add_field($this->_TempArr['AddArr']);

		unset($this->_TempArr['AddArr']);

		return ($add) ? true : false;
	}
  function Add_attach_time()
	{
		global $PowerBB;

		$this->_TempArr['AddArr']					=	array();
		$this->_TempArr['AddArr']['table']			=	$PowerBB->table['attach'];
		$this->_TempArr['AddArr']['field_name']		=	'time';
		$this->_TempArr['AddArr']['field_des']		=	"int( 11 ) NOT NULL default '0'";

		$add = $this->add_field($this->_TempArr['AddArr']);

		unset($this->_TempArr['AddArr']);

		return ($add) ? true : false;
	}

 function Add_attach_last_down()
	{
		global $PowerBB;

		$this->_TempArr['AddArr']					=	array();
		$this->_TempArr['AddArr']['table']			=	$PowerBB->table['attach'];
		$this->_TempArr['AddArr']['field_name']		=	'last_down';
		$this->_TempArr['AddArr']['field_des']		=	"int( 11 ) NOT NULL default '0'";

		$add = $this->add_field($this->_TempArr['AddArr']);

		unset($this->_TempArr['AddArr']);

		return ($add) ? true : false;
	}

	function ChangeAttachFilesize()
	{
		global $PowerBB;

		$change = $PowerBB->DB->sql_query("ALTER TABLE " . $PowerBB->table['attach'] . " CHANGE filesize filesize VARCHAR( 20 ) NOT NULL DEFAULT '0'");

		return ($change) ? true : false;
	}

	function ChangeAttachVisitor()
	{
		global $PowerBB;

		$change = $PowerBB->DB->sql_query("ALTER TABLE " . $PowerBB->table['attach'] . " CHANGE visitor visitor INT( 9 ) NOT NULL DEFAULT '0'");

		return ($change) ? true : false;
	}

	function Add_sidebar_list_active()
	{
		global $PowerBB;
		$insert = $PowerBB->DB->sql_query('INSERT INTO ' . $PowerBB->table['info'] . " SET var_name='sidebar_list_active',value='1'");

		return ($insert) ? true : false;
	}

	function Add_sidebar_list_align()
	{
		global $PowerBB;
		$insert = $PowerBB->DB->sql_query('INSERT INTO ' . $PowerBB->table['info'] . " SET var_name='sidebar_list_align',value='left'");

		return ($insert) ? true : false;
	}


	function Add_sidebar_list_width()
	{
		global $PowerBB;
		$insert = $PowerBB->DB->sql_query('INSERT INTO ' . $PowerBB->table['info'] . " SET var_name='sidebar_list_width',value='25'");

		return ($insert) ? true : false;
	}

	function Add_sidebar_list_exclusion_forums()
	{
		global $PowerBB;
		$insert = $PowerBB->DB->sql_query('INSERT INTO ' . $PowerBB->table['info'] . " SET var_name='sidebar_list_exclusion_forums',value='254,545'");

		return ($insert) ? true : false;
	}

	function Add_sidebar_list_pages()
	{
		global $PowerBB;
		$insert = $PowerBB->DB->sql_query('INSERT INTO ' . $PowerBB->table['info'] . " SET var_name='sidebar_list_pages',value='index'");

		return ($insert) ? true : false;
	}

	function Add_sidebar_list_content()
	{
		global $PowerBB;
		$insert = $PowerBB->DB->sql_query('INSERT INTO ' . $PowerBB->table['info'] . " SET var_name='sidebar_list_content',value='{template}login_box{/template}\n{template}whatis_new{/template}'");

		return ($insert) ? true : false;
	}

	function Add_last_posts_cache()
	{
		global $PowerBB;
		$insert = $PowerBB->DB->sql_query('INSERT INTO ' . $PowerBB->table['info'] . " SET var_name='last_posts_cache',value=''");

		return ($insert) ? true : false;
	}

	function Add_last_time_cache()
	{
		global $PowerBB;
		$last_time_cache = time();
		$insert = $PowerBB->DB->sql_query('INSERT INTO ' . $PowerBB->table['info'] . " SET var_name='last_time_cache',value='$last_time_cache'");

		return ($insert) ? true : false;
	}

	function Add_groups_cache()
	{
	global $PowerBB;
	$insert = $PowerBB->DB->sql_query('INSERT INTO ' . $PowerBB->table['info'] . " SET var_name='groups_cache',value=''");

	return ($insert) ? true : false;
	}

	function Add_custom_bbcodes_list_cache()
	{
	global $PowerBB;
	$insert = $PowerBB->DB->sql_query('INSERT INTO ' . $PowerBB->table['info'] . " SET var_name='custom_bbcodes_list_cache',value=''");

	return ($insert) ? true : false;
	}

	function Add_rss_feeds_cache()
	{
	global $PowerBB;
	$insert = $PowerBB->DB->sql_query('INSERT INTO ' . $PowerBB->table['info'] . " SET var_name='rss_feeds_cache',value=''");

	return ($insert) ? true : false;
	}

	function Add_extrafields_cache()
	{
	global $PowerBB;
	$insert = $PowerBB->DB->sql_query('INSERT INTO ' . $PowerBB->table['info'] . " SET var_name='extrafields_cache',value=''");

	return ($insert) ? true : false;
	}


	function Add_awards_cache()
	{
	global $PowerBB;
	$insert = $PowerBB->DB->sql_query('INSERT INTO ' . $PowerBB->table['info'] . " SET var_name='awards_cache',value=''");

	return ($insert) ? true : false;
	}


	function Add_pages_cache()
	{
	global $PowerBB;
	$insert = $PowerBB->DB->sql_query('INSERT INTO ' . $PowerBB->table['info'] . " SET var_name='pages_cache',value=''");

	return ($insert) ? true : false;
	}


	function Add_adsenses_cache()
	{
	global $PowerBB;
	$insert = $PowerBB->DB->sql_query('INSERT INTO ' . $PowerBB->table['info'] . " SET var_name='adsenses_cache',value=''");

	return ($insert) ? true : false;
	}


	function Add_languages_list_cache()
	{
	global $PowerBB;
	$insert = $PowerBB->DB->sql_query('INSERT INTO ' . $PowerBB->table['info'] . " SET var_name='languages_list_cache',value=''");

	return ($insert) ? true : false;
	}


	function Add_styles_list_cache()
	{
	global $PowerBB;
	$insert = $PowerBB->DB->sql_query('INSERT INTO ' . $PowerBB->table['info'] . " SET var_name='styles_list_cache',value=''");

	return ($insert) ? true : false;
	}

 	function Add_p_cache()
	{
	global $PowerBB;
	$insert = $PowerBB->DB->sql_query('INSERT INTO ' . $PowerBB->table['info'] . " SET var_name='p_cache',value=''");

	return ($insert) ? true : false;
	}


	function Add_last_time_updates()
	{
		global $PowerBB;
		$last_time_updates = time();
		$insert = $PowerBB->DB->sql_query('INSERT INTO ' . $PowerBB->table['info'] . " SET var_name='last_time_updates',value='$last_time_updates'");

		return ($insert) ? true : false;
	}

	function Add_mobile_style_id()
	{
	global $PowerBB;
	$def_style = $PowerBB->_CONF['info_row']['def_style'];
	$insert = $PowerBB->DB->sql_query('INSERT INTO ' . $PowerBB->table['info'] . " SET var_name='mobile_style_id',value='$def_style'");

	return ($insert) ? true : false;
	}

	function UpdateNoActiveAddons()
	{
		global $PowerBB;

		$update = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['addons'] . " SET active='0'");

		return ($update) ? true : false;
	}

	function DisableAllOldStyles()
	{
		global $PowerBB;

		$update = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['style'] . " SET style_path ='../styles/default/css/style.css' WHERE image_path = '../styles/main/images'" );

		return ($update) ? true : false;
	}

	function DisableAllOldLangs ()
	{
		global $PowerBB;

		$update = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['lang'] . " SET lang_on ='0'");

		return ($update) ? true : false;
	}


	function _InsertStyle()
	{
		global $PowerBB;
		$xml_code = @file_get_contents("../PBBoard-Style.xml");
		preg_match_all('/<!\[CDATA\[(.*?)\]\]>/is', $xml_code, $match);
		foreach($match[0] as $val)
		{
		$xml_code = str_replace($val,base64_encode($val),$xml_code);
		}

		$import = $this->xml_array($xml_code);
		$title = $import['styles_attr']['name'];
		$pbbversion = $import['styles_attr']['pbbversion'];

		$image_path = $import['styles_attr']['image_path'];
		$style_path = $import['styles_attr']['style_path'];
		$Templates = $import['styles']['templategroup'];
		$Templates_number = sizeof($import['styles']['templategroup']['template'])/2;


		$StlArr 					= 	array();
		$StlArr['field']			=	array();

		$StlArr['field']['style_title'] 	= 	$title;
		$StlArr['field']['style_path'] 		= 	$style_path;
		$StlArr['field']['style_order'] 	= 	'1';
		$StlArr['field']['style_on'] 		= 	'1';
		$StlArr['field']['image_path'] 		= 	$image_path;
		//Edited----------------------------------------
		$insert = $PowerBB->style->InsertStyle($StlArr);
		$styleid = $PowerBB->DB->sql_insert_id();
		$x = 0;
		while ($x < $Templates_number)
		{
		$templatetitle = $Templates['template'][$x.'_attr']['name'];
		$version = $Templates['template'][$x.'_attr']['version'];
		$TemplateArr = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['template'] . " WHERE styleid = 1 AND title = '$templatetitle' ");
		$getstyle_row = $PowerBB->DB->sql_fetch_array($TemplateArr);
		$template = base64_decode($Templates['template'][$x]);
		$templatetype = $Templates['template'][$x.'_attr']['templatetype'];
		$dateline = $Templates['template'][$x.'_attr']['date'];
		$product = $Templates['template'][$x.'_attr']['product'];
		$version = $Templates['template'][$x.'_attr']['version'];
		$username = $Templates['template'][$x.'_attr']['username'];
		$template = str_replace("'", "&#39;", $template);
		$template = str_replace("//<![CDATA[", "", $template);
		$template = str_replace("//]]>", "", $template);
		$template = str_replace("<![CDATA[","", $template);
		$template = str_replace("]]>","", $template);

		$InsertTemplatesArr	=	array();
		$InsertTemplatesArr['field']	=	array();
		$InsertTemplatesArr['field']['styleid']	=	$styleid;
		$InsertTemplatesArr['field']['title']	=	$templatetitle;
		$InsertTemplatesArr['field']['template']	=	$template;
		$InsertTemplatesArr['field']['template_un']	=	$template;
		$InsertTemplatesArr['field']['templatetype']	=	$templatetype;
		$InsertTemplatesArr['field']['dateline']	=	$dateline;
		$InsertTemplatesArr['field']['username']	=	$username;
		$InsertTemplatesArr['field']['version']	=	$version;
		$InsertTemplatesArr['field']['product']	=	$product;
		$Insert = $PowerBB->core->Insert($InsertTemplatesArr,'template');

		$x += 1;
		}

		$deltemplates = $PowerBB->DB->sql_query("DELETE FROM " . $PowerBB->table['template'] . " WHERE styleid = '$styleid' and title = ''");
		$update = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['info'] . " SET value='$styleid' WHERE var_name='def_style'");
		$updatemember = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['member'] . " SET style = '$styleid'");
		//////////

		return ($updatemember) ? true : false;
	}

    function _InsertLang_ar()
	{
		global $PowerBB;

         $xml_code_ar = @file_get_contents("../ArabicLanguage.xml");

		$import = $this->xml_array($xml_code_ar);
		$title = $import['language_attr']['name'];
		$pbbversion = $import['language_attr']['version'];


		$Languages = $import['language']['phrasegroup'];
		$language_number = sizeof($import['language']['phrasegroup']['phrase'])/2;

	       	$LangArr 					= 	array();
			$LangArr['field']			=	array();

			$LangArr['field']['lang_title'] 	= 	$title;
			$LangArr['field']['lang_order'] 	= 	"1";
			$LangArr['field']['lang_on'] 		= 	"1";
			$LangArr['field']['lang_path'] 		= 	"rtl";
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
						$insertLanguages_ar = $PowerBB->core->Insert($InsertLanguagesArr,'phrase_language');
                     $x += 1;
     			}

           if ($insertLanguages_ar)
			{
		        $delLanguages_ar = $PowerBB->DB->sql_query("DELETE FROM " . $PowerBB->table['phrase_language'] . " WHERE languageid = '$langid' and varname = ''");
   		        $update = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['info'] . " SET value='$langid' WHERE var_name='def_lang'");
			}


		return ($insertLanguages_ar) ? true : false;
	}

	function _InsertLang_en()
	{
		global $PowerBB;

				$xml_code_en = @file_get_contents("../EnglishLanguage.xml");

				$import_en = $this->xml_array($xml_code_en);
				$titleen = $import_en['language_attr']['name'];
				$pbbversion = $import_en['language_attr']['version'];


				$Languages_en = $import_en['language']['phrasegroup'];
				$language_numbers = sizeof($import_en['language']['phrasegroup']['phrase'])/2;


				$LangenArr 					= 	array();
				$LangenArr['field']			=	array();

				$LangenArr['field']['lang_title'] 	= 	$titleen;
				$LangenArr['field']['lang_order'] 	= 	"2";
				$LangenArr['field']['lang_on'] 		= 	"1";
			    $LangenArr['field']['lang_path'] 		= 	"ltr";
				$insert_en = $PowerBB->lang->InsertLang($LangenArr);
			    $langid_en = $PowerBB->DB->sql_insert_id();

		            $x = 0;
     			while ($x < $language_numbers)
     			{
						$varname = $Languages_en['phrase'][$x.'_attr']['name'];
						$fieldname = $Languages_en['phrase'][$x.'_attr']['fieldname'];
						$version = $Languages_en['phrase'][$x.'_attr']['pbbversion'];
						$texten = $Languages_en['phrase'][$x];
						$product = $Languages_en['phrase'][$x.'_attr']['product'];
						$dateline = $Languages_en['phrase'][$x.'_attr']['date'];
						$username = $Languages_en['phrase'][$x.'_attr']['username'];
			            $texten = str_replace("'", "&#39;", $texten);

						$InsertLanguagesArr	=	array();
						$InsertLanguagesArr['field']	=	array();
						$InsertLanguagesArr['field']['languageid']	=	$langid_en;
						$InsertLanguagesArr['field']['varname']  	=	$varname;
						$InsertLanguagesArr['field']['fieldname']	=	$fieldname;
						$InsertLanguagesArr['field']['text']	    =	$texten;
						$InsertLanguagesArr['field']['dateline']	=	$dateline;
						$InsertLanguagesArr['field']['username']	=	$username;
						$InsertLanguagesArr['field']['version']	    =	$version;
						$InsertLanguagesArr['field']['product']	    =	$product;
						$insertLanguages_en = $PowerBB->core->Insert($InsertLanguagesArr,'phrase_language');
                     $x += 1;
     			}

	           if ($insertLanguages_en)
				{
			        $delLanguages = $PowerBB->DB->sql_query("DELETE FROM " . $PowerBB->table['phrase_language'] . " WHERE languageid = '$langid_en' and varname = ''");
				}




		return ($insertLanguages_en) ? true : false;
	}

   function Update_Cache_groups()
	{

	   global $PowerBB;

		$info_query_groups = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['group'] . " ");

		while ($groups = $PowerBB->DB->sql_fetch_array($info_query_groups))
		{

		$CacheGroupArr 			= 	array();
		$CacheGroupArr['id'] 	= 	$groups['id'];

		$Update_Group_Cache = $PowerBB->group->UpdateGroupCache($CacheGroupArr);
		}

       	return ($Update_Group_Cache) ? true : false;

	}


	function CreateCustom_bbcodeCache($table_name, $Info_var_name_cache)
 	{
 		global $PowerBB;

 		$Arr 						= 	array();
		$Arr['get_from']				=	'db';
		$Arr['proc'] 				= 	array();
		$Arr['proc']['*'] 			= 	array('method'=>'clean','param'=>'html');

		$Arr['order']				=	array();
		$Arr['order']['field']		=	'id';
		$Arr['order']['type']		=	'ASC';
		// Get $table_name List
		$Custom_bbcode = $PowerBB->core->GetList($Arr,$table_name);

 	 	$cache 	= 	array();
 		$x		=	0;
 		$n		=	sizeof($Custom_bbcode);

		while ($x < $n)
		{
			$cache[$x] 					                    = 	array();
			$cache[$x]['id']		 	                    = 	$Custom_bbcode[$x]['id'];
			$cache[$x]['bbcode_title']		 	            = 	$Custom_bbcode[$x]['bbcode_title'];
			$cache[$x]['bbcode_desc']		 	            = 	$Custom_bbcode[$x]['bbcode_desc'];
			$cache[$x]['bbcode_tag']		 	            = 	$Custom_bbcode[$x]['bbcode_tag'];
			$cache[$x]['bbcode_replace']		 	        = 	$Custom_bbcode[$x]['bbcode_replace'];
			$cache[$x]['bbcode_useoption']		 	        = 	$Custom_bbcode[$x]['bbcode_useoption'];
			$cache[$x]['bbcode_example']		 	        = 	$Custom_bbcode[$x]['bbcode_example'];
			$cache[$x]['bbcode_switch']		 	            = 	$Custom_bbcode[$x]['bbcode_switch'];
			$cache[$x]['bbcode_add_into_menu']		     	= 	$Custom_bbcode[$x]['bbcode_add_into_menu'];
			$cache[$x]['bbcode_menu_option_text']		 	= 	$Custom_bbcode[$x]['bbcode_menu_option_text'];
			$cache[$x]['bbcode_menu_content_text']		 	= 	$Custom_bbcode[$x]['bbcode_menu_content_text'];

			$x += 1;
		}

		$cache = json_encode($cache);
         $cache = $PowerBB->sys_functions->CleanVariable($cache,'html');
         $cache = $PowerBB->sys_functions->CleanVariable($cache,'sql');
		$update_cache = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['info'] . " SET value='".$cache."' WHERE var_name='".$Info_var_name_cache."'");
 	}

 }

$PowerBB->install = new PowerBBTHETA;
$PowerBB->html->page_header('معالج ترقية برنامج منتديات PBBoard 3.0.2');
$logo = $PowerBB->html->create_image(array('align'=>'left','alt'=>'PowerBB','src'=>'../images/logo.png', 'border'=>'0', 'cellspacing'=>'0','return'=>true));
$PowerBB->html->page_body('<div class="pbboard_body">');
$PowerBB->html->open_table('100%',true);
$PowerBB->html->cells($logo,'header_logo_side');


if ($PowerBB->_GET['step'] == 0)
{

$PowerBB->html->cells('الترقية إلى الإصدار 3.0.2 من  برنامج PBBoard','main1');
$PowerBB->html->close_table();
$PowerBB->html->make_link('الخطوه الأولى -> أدخال الإستعلامات','?step=1');
}
if ($PowerBB->_GET['step'] == 1)
{
	$PowerBB->html->cells('عمليات الاضافة ','main1');
	$PowerBB->html->close_table();

	$p[1] 		= 	$PowerBB->install->AddUsers_Security();
	$msgs[1] 	= 	($p[1]) ? 'تم إنشاء حقل users_security ' : 'لم يتم إنشاء حقل users_security';
    $p[2] 		= 	$PowerBB->install->AddGroups_Security();
	$msgs[2] 	= 	($p[2]) ? 'تم إنشاء حقل groups_security في جدول group ' : 'لم يتم إنشاء حقل groups_security في جدول group';
    $p[3] 		= 	$PowerBB->install->Add_send_security_code();
	$msgs[3] 	= 	($p[3]) ? 'تم إنشاء حقل send_security_code في جدول member ' : 'لم يتم إنشاء حقل send_security_code في جدول member';
    $p[4] 		= 	$PowerBB->install->Add_send_security_error_login();
	$msgs[4] 	= 	($p[4]) ? 'تم إنشاء حقل send_security_error_login في جدول member ' : 'لم يتم إنشاء حقل send_security_error_login في جدول member';
    $p[5] 		= 	$PowerBB->install->Add_profile_cover_photo();
	$msgs[5] 	= 	($p[5]) ? 'تم إنشاء حقل profile_cover_photo في جدول member ' : 'لم يتم إنشاء حقل profile_cover_photo في جدول member';
    $p[6] 		= 	$PowerBB->install->Add_profile_cover_photo_position();
	$msgs[6] 	= 	($p[6]) ? 'تم إنشاء حقل Add_profile_cover_photo_position في جدول member ' : 'لم يتم إنشاء حقل Add_profile_cover_photo_position في جدول member';
    $p[7] 		= 	$PowerBB->install->AddProfilePhoto();
	$msgs[7] 	= 	($p[7]) ? 'تم إنشاء حقل profile_photo في جدول group ' : 'لم يتم إنشاء حقل profile_photo في جدول group';
    $p[8] 		= 	$PowerBB->install->Add_attach_last_down();
	$msgs[8] 	= 	($p[8]) ? 'تم إنشاء حقل last_down في جدول attach ' : 'لم يتم إنشاء حقل last_down في جدول attach';
    $p[9] 		= 	$PowerBB->install->Add_attach_time();
	$msgs[9] 	= 	($p[9]) ? 'تم إنشاء حقل time في جدول attach ' : 'لم يتم إنشاء حقل time في جدول attach';

	$PowerBB->html->open_p();

	foreach ($msgs as $msg)
	{
		$PowerBB->html->p_msg($msg);
	}

$PowerBB->html->close_p();

$PowerBB->html->close_table();
$PowerBB->html->make_link('الخطوه الثانية -> أدخال الإستعلامات','?step=2');
 }
if ($PowerBB->_GET['step'] == 2)
{
	$PowerBB->html->cells('عمليات الاضافة ','main1');
	$PowerBB->html->close_table();

    $p[4] 		= 	$PowerBB->install->Add_date_message_chat();
    $msgs[4] 	= 	($p[4]) ? 'تم إنشاء حقل date في جدول chat' : 'لم يتم إنشاء حقل date في جدول chat';
	$p[5] 		= 	$PowerBB->install->Add_sidebar_list_active();
	$msgs[5] 	= 	($p[5]) ? 'تم إنشاء حقل sidebar_list_active ' : 'لم يتم إنشاء حقل sidebar_list_active';
    $p[6] 		= 	$PowerBB->install->Add_sidebar_list_align();
	$msgs[6] 	= 	($p[6]) ? 'تم إنشاء حقل sidebar_list_align ' : 'لم يتم إنشاء حقل sidebar_list_align';
    $p[7] 		= 	$PowerBB->install->Add_sidebar_list_pages();
	$msgs[7] 	= 	($p[7]) ? 'تم إنشاء حقل sidebar_list_pages ' : 'لم يتم إنشاء حقل sidebar_list_pages';
    $p[8] 		= 	$PowerBB->install->Add_sidebar_list_width();
	$msgs[8] 	= 	($p[8]) ? 'تم إنشاء حقل sidebar_list_width ' : 'لم يتم إنشاء حقل sidebar_list_width';
    $p[9] 		= 	$PowerBB->install->Add_sidebar_list_exclusion_forums();
	$msgs[9] 	= 	($p[9]) ? 'تم إنشاء حقل sidebar_list_exclusion_forums ' : 'لم يتم إنشاء حقل sidebar_list_exclusion_forums';
    $p[10] 		= 	$PowerBB->install->Add_sidebar_list_content();
	$msgs[10] 	= 	($p[10]) ? 'تم إنشاء حقل sidebar_list_content ' : 'لم يتم إنشاء حقل sidebar_list_content';
    $p[11] 		= 	$PowerBB->install->Add_last_posts_cache();
	$msgs[11] 	= 	($p[11]) ? 'تم إنشاء حقل last_posts_cache ' : 'لم يتم إنشاء حقل last_posts_cache';
    $p[11] 		= 	$PowerBB->install->Add_last_time_cache();
	$msgs[11] 	= 	($p[11]) ? 'تم إنشاء حقل last_time_cache ' : 'لم يتم إنشاء حقل last_time_cache';

	$PowerBB->html->open_p();

	foreach ($msgs as $msg)
	{
		$PowerBB->html->p_msg($msg);
	}

$PowerBB->html->close_p();
$PowerBB->html->close_table();
$PowerBB->html->make_link('الخطوه الثالثة -> أدخال الإستعلامات','?step=3');
 }
if ($PowerBB->_GET['step'] == 3)
{
	$PowerBB->html->cells('عمليات الاضافة ','main1');
	$PowerBB->html->close_table();

	$p[12] 		= 	$PowerBB->install->Add_groups_cache();
	$msgs[12] 	= 	($p[12]) ? 'تم إنشاء حقل groups_cache ' : 'لم يتم إنشاء حقل groups_cache';
	$p[13] 		= 	$PowerBB->install->Add_custom_bbcodes_list_cache();
	$msgs[13] 	= 	($p[13]) ? 'تم إنشاء حقل custom_bbcodes_list_cache ' : 'لم يتم إنشاء حقل custom_bbcodes_list_cache';
	$p[14] 		= 	$PowerBB->install->Add_rss_feeds_cache();
	$msgs[14] 	= 	($p[14]) ? 'تم إنشاء حقل rss_feeds_cache ' : 'لم يتم إنشاء حقل rss_feeds_cache';
	$p[15] 		= 	$PowerBB->install->Add_extrafields_cache();
	$msgs[15] 	= 	($p[15]) ? 'تم إنشاء حقل extrafields_cache ' : 'لم يتم إنشاء حقل extrafields_cache';
	$p[16] 		= 	$PowerBB->install->Add_awards_cache();
	$msgs[16] 	= 	($p[16]) ? 'تم إنشاء حقل awards_cache ' : 'لم يتم إنشاء حقل awards_cache';
	$p[17] 		= 	$PowerBB->install->Add_pages_cache();
	$msgs[17] 	= 	($p[17]) ? 'تم إنشاء حقل pages_cache ' : 'لم يتم إنشاء حقل pages_cache';
	$p[18] 		= 	$PowerBB->install->Add_adsenses_cache();
	$msgs[18] 	= 	($p[18]) ? 'تم إنشاء حقل adsenses_cache ' : 'لم يتم إنشاء حقل adsenses_cache';
	$p[19] 		= 	$PowerBB->install->Add_languages_list_cache();
	$msgs[19] 	= 	($p[19]) ? 'تم إنشاء حقل languages_list_cache ' : 'لم يتم إنشاء حقل languages_list_cache';
	$p[20] 		= 	$PowerBB->install->Add_styles_list_cache();
	$msgs[20] 	= 	($p[20]) ? 'تم إنشاء حقل styles_list_cache ' : 'لم يتم إنشاء حقل styles_list_cache';

	$PowerBB->html->open_p();

	foreach ($msgs as $msg)
	{
		$PowerBB->html->p_msg($msg);
	}

$PowerBB->html->close_p();
$PowerBB->html->close_table();
$PowerBB->html->make_link('الخطوه الرابعة -> تعطيل الملحقات البرمجية واللغات والاستايلات السابقة','?step=4');
}
if ($PowerBB->_GET['step'] == 4)
{
	$PowerBB->html->cells('عمليات التعطيل والتحديث','main1');
	$PowerBB->html->close_table();
	$p[21] 		= 	$PowerBB->install->Add_p_cache();
	$msgs[21] 	= 	($p[21]) ? 'تم إنشاء حقل p_cache ' : 'لم يتم إنشاء حقل p_cache';

    $p[14] 		= 	$PowerBB->install->UpdateNoActiveAddons();
	$msgs[14] 	= 	($p[14]) ? 'تم تعطيل كافة الاضافات البرمجية' : 'لم يتم تعطيل كافة الاضافات البرمجية';
    $p[15] 		= 	$PowerBB->install->DisableAllOldStyles();
	$msgs[15] 	= 	($p[15]) ? 'تم تعطيل كافة الإستايلات السابقة' : 'لم يتم تعطيل كافة الإستايلات';
    $p[16] 		= 	$PowerBB->install->DisableAllOldLangs();
	$msgs[16] 	= 	($p[16]) ? 'تم تعطيل كافة اللغات السابقة' : 'لم يتم تعطيل كافة اللغات';
	$PowerBB->html->open_p();

	foreach ($msgs as $msg)
	{
		$PowerBB->html->p_msg($msg);
	}

$PowerBB->html->close_p();
$PowerBB->html->close_table();
$PowerBB->html->make_link('الخطوه الخامسة -> إضافة اللغة والعبارات','?step=5');
}
if ($PowerBB->_GET['step'] == 5)
{
	$PowerBB->html->cells('عمليات اضافة اللغة والعبارات','main1');
	$PowerBB->html->close_table();

	$p[17] 		= 	$PowerBB->install->_InsertLang_ar();
	$msgs[17] 	= 	($p[17]) ? 'تم إدخال اللغة العربية  ' : 'لم يتم إدخال اللغة العربية';

	$p[18] 		= 	$PowerBB->install->_InsertLang_en();
	$msgs[18] 	= 	($p[18]) ? 'تم إدخال اللغة الإنجليزية' : 'لم يتم إدخال اللغة الإنجليزية';

	$PowerBB->html->open_p();

	foreach ($msgs as $msg)
	{
		$PowerBB->html->p_msg($msg);
	}

$PowerBB->html->close_p();
$PowerBB->html->close_table();
$PowerBB->html->make_link('الخطوه السادسة -> أضافة الاستايلات والقوالب','?step=6');
}
if ($PowerBB->_GET['step'] == 6)
{

$PowerBB->html->cells('عمليات اضافة الأستايل والقوالب','main1');
	$PowerBB->html->close_table();

	$p[18] 		= 	$PowerBB->install->_InsertStyle();
	$msgs[18] 	= 	($p[18]) ? 'تم إنشاء قوالب الأستايل ' : 'لم يتم إنشاء قوالب الإستايل';

	$PowerBB->html->open_p();

	foreach ($msgs as $msg)
	{
		$PowerBB->html->p_msg($msg);
	}


$PowerBB->html->close_p();
$PowerBB->html->close_table();
$PowerBB->html->make_link('الخطوه السابعة -> تعديل وإدخال الإستعلامات','?step=7');
}

if ($PowerBB->_GET['step'] == 7)
{

$PowerBB->html->cells('تعديل وإدخال الإستعلامات','main1');
	$PowerBB->html->close_table();

    $p[21] 		= 	$PowerBB->install->Add_membergroupids();
	$msgs[21] 	= 	($p[21]) ? 'تم إنشاء حقل membergroupids في جدول member ' : 'لم يتم إنشاء حقل membergroupids في جدول member';
    $p[19] 		= 	$PowerBB->install->ChangeAttachVisitor();
	$msgs[19] 	= 	($p[19]) ? 'تم تغيير حقل visitor في جدول attach ' : 'لم يتم تغيير حقل visitor في جدول attach';
    $p[20] 		= 	$PowerBB->install->ChangeAttachFilesize();
	$msgs[20] 	= 	($p[20]) ? 'تم تغيير حقل filesize في جدول attach ' : 'لم يتم تغيير حقل filesize في جدول attach';
	$p[21] 		= 	$PowerBB->install->Add_mobile_style_id();
	$msgs[21] 	= 	($p[21]) ? 'تم إنشاء حقل mobile_style_id ' : 'لم يتم إنشاء حقل mobile_style_id';

	$PowerBB->html->open_p();

	foreach ($msgs as $msg)
	{
		$PowerBB->html->p_msg($msg);
	}


$PowerBB->html->close_p();
$PowerBB->html->close_table();
$PowerBB->html->make_link('الخطوه الثامنة والأخيرة -> اتمام الترقية','?step=8');
}
elseif ($PowerBB->_GET['step'] == 8)
{
	$PowerBB->html->cells('الخطوة النهائية','main1');

	$PowerBB->html->close_table();
$NewVersion = $PowerBB->install->UpdateVersion();
$allow_avatar = $PowerBB->install->Update_allow_avatar();
$create_cache_Custom_bbcode = $PowerBB->install->CreateCustom_bbcodeCache('custom_bbcode','custom_bbcodes_list_cache');
$create_cache_statement_group = $PowerBB->install->Update_Cache_groups();
$updateLastTime = $PowerBB->install->Add_last_time_updates();

 		   $del_file_forums_cache = @unlink('../../cache/forums_cache_1.php');
		   $del_file_sectiongroup_cache1 = @unlink('../../cache/sectiongroup_cache1.php');
		   $del_file_sectiongroup_cache2 = @unlink('../../cache/sectiongroup_cache2.php');


		$PowerBB->html->open_p();
        $PowerBB->html->p_msg('تمت الترقية إلى الإصدار 3.0.2 بنجاح. ');
		$PowerBB->html->close_p();

		$PowerBB->html->make_link('البدأ بالترقية إلى الإصدار 3.0.3','../../install/upgrade.php');

}

     $PowerBB->html->page_footer("<br><br><br><div id='copyright'>Upgrade PBBoard <br>Powered by PBBoard © Version 3.0.2 </div></div></body></html>");

?>
