<?php

/**
*  upgrader to version 3.0.0
*/

define('NO_TEMPLATE',true);

$CALL_SYSTEM				= 	array();
$CALL_SYSTEM['SECTION'] 	= 	true;
$CALL_SYSTEM['GROUP']       = 	true;
$CALL_SYSTEM['CACHE']              = 	true;
$CALL_SYSTEM['STYLE']              = 	true;
$CALL_SYSTEM['SUBJECT']            = 	true;
$CALL_SYSTEM['LANG']               = 	true;
$CALL_SYSTEM['MODERATORS']         = 	true;
$CALL_SYSTEM['ADDONS']             = 	true;

include('../common.php');

class PowerBBTHETA extends PowerBBInstall
 {
	var $_TempArr 	= 	array();
	var $_Masseges	=	array();

	function CheckVersion()
	{
		global $PowerBB;

		return ($PowerBB->_CONF['info_row']['MySBB_version'] == '2.1.4') ? true : false;
	}


	function UpdateVersion()
	{
		global $PowerBB;

		$update = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['info'] . " SET value='3.0.0' WHERE var_name='MySBB_version'");

		return ($update) ? true : false;
	}

	function Update_var_name_admin_ajax_main_rename()
	{
		global $PowerBB;

 		$update = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['info'] . " SET var_name='get_group_username_style' WHERE var_name='admin_ajax_main_rename'");
	    $update = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['info'] . " SET value='60' WHERE var_name='wordwrap'");

		return ($update) ? true : false;
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

		$update = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['style'] . " SET style_on ='0'");

		return ($update) ? true : false;
	}

	function DisableAllOldLangs ()
	{
		global $PowerBB;

		$update = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['lang'] . " SET lang_on ='0'");

		return ($update) ? true : false;
	}

	function _CreateTemplate()
	{
		global $PowerBB;

		$this->_TempArr['CreateArr']				= 	array();
		$this->_TempArr['CreateArr']['table_name'] 	= 	$PowerBB->table['template'];
		$this->_TempArr['CreateArr']['fields'] 		= 	array();
		$this->_TempArr['CreateArr']['fields'][] 	= 	'templateid INT( 10 ) unsigned NOT NULL auto_increment PRIMARY KEY';
		$this->_TempArr['CreateArr']['fields'][] 	= 	"styleid smallint(6) NOT NULL default '0'";
		$this->_TempArr['CreateArr']['fields'][] 	= 	"title varchar(100) NOT NULL default ''";
		$this->_TempArr['CreateArr']['fields'][] 	= 	'template longtext';
		$this->_TempArr['CreateArr']['fields'][] 	= 	'template_un longtext';
		$this->_TempArr['CreateArr']['fields'][] 	= 	"templatetype enum('template','stylevar','css','replacement') NOT NULL default 'template'";
		$this->_TempArr['CreateArr']['fields'][] 	= 	"dateline int(10) unsigned NOT NULL default '0'";
		$this->_TempArr['CreateArr']['fields'][] 	= 	"username varchar(100) NOT NULL default ''";
		$this->_TempArr['CreateArr']['fields'][] 	= 	"version  varchar(30) NOT NULL default ''";
		$this->_TempArr['CreateArr']['fields'][] 	= 	"product varchar(25) NOT NULL default ''";
		$this->_TempArr['CreateArr']['fields'][] 	= 	'sort int(5) NOT NULL';
		$this->_TempArr['CreateArr']['fields'][] 	= 	"active smallint(5) unsigned NOT NULL default '1'";

        $create = $this->create_table($this->_TempArr['CreateArr']);

        return ($create) ? true : false;
	}

    function _CreatePhrase()
	{
		global $PowerBB;

		$this->_TempArr['CreateArr']				= 	array();
		$this->_TempArr['CreateArr']['table_name'] 	= 	$PowerBB->table['phrase_language'];
		$this->_TempArr['CreateArr']['fields'] 		= 	array();
		$this->_TempArr['CreateArr']['fields'][] 	= 	'phraseid INT( 10 ) unsigned NOT NULL auto_increment PRIMARY KEY';
		$this->_TempArr['CreateArr']['fields'][] 	= 	"languageid smallint(6) NOT NULL default '0'";
		$this->_TempArr['CreateArr']['fields'][] 	= 	"varname varchar(250) character set utf8 collate utf8_bin NOT NULL default ''";
		$this->_TempArr['CreateArr']['fields'][] 	= 	"fieldname varchar(20) NOT NULL default ''";
		$this->_TempArr['CreateArr']['fields'][] 	= 	'text mediumtext';
		$this->_TempArr['CreateArr']['fields'][] 	= 	"product varchar(25) NOT NULL default ''";
		$this->_TempArr['CreateArr']['fields'][] 	= 	"dateline int(10) unsigned NOT NULL default '0'";
		$this->_TempArr['CreateArr']['fields'][] 	= 	"username varchar(100) NOT NULL default ''";
		$this->_TempArr['CreateArr']['fields'][] 	= 	"version  varchar(30) NOT NULL default ''";

        $create = $this->create_table($this->_TempArr['CreateArr']);

        return ($create) ? true : false;
	}


	function _InsertStyle()
	{
		global $PowerBB;
		$xml_code = @file_get_contents("../PBBoard-Style.xml");
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
		$username = $Templates['template'][$x.'_attr']['username'];
		$template = str_replace("'", "&#39;", $template);

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
		$update = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['member'] . " SET style = '$styleid'");
		//////////
		if($Insert)
		{
		// add mobile style
        $xml_code = @file_get_contents("../PBBoard-mobile-style.xml");
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
						$username = $Templates['template'][$x.'_attr']['username'];
			           $template = str_replace("'", "&#39;", $template);

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

		}

		return ($insert) ? true : false;
	}

	function _InsertLang()
	{
		global $PowerBB;
		$xml_code = @file_get_contents("../PBBoard-language-ar.xml");
		$import = $this->xml_array($xml_code);
		$title = $import['language_attr']['name'];
		$pbbversion = $import['language_attr']['version'];


		$Languages = $import['language']['phrasegroup'];
		$language_number = sizeof($import['language']['phrasegroup']['phrase'])/2;


		$LangArr 					= 	array();
		$LangArr['field']			=	array();

		$LangArr['field']['lang_title'] 	= 	$title;
		$LangArr['field']['lang_order'] 	= 	"1";
		$LangArr['field']['lang_on'] 		= 	"1";
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
		$insertLanguages = $PowerBB->core->Insert($InsertLanguagesArr,'phrase_language');
		$x += 1;
		}

		if ($insertLanguages)
		{
		$delLanguages = $PowerBB->DB->sql_query("DELETE FROM " . $PowerBB->table['phrase_language'] . " WHERE languageid = '$langid' and varname = ''");
		$update = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['info'] . " SET value='$langid' WHERE var_name='def_lang'");
		$update = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['member'] . " SET lang = '$langid'");
		}


		return ($insertLanguages) ? true : false;
	}

	function AddCssPrefs ()
	{
		global $PowerBB;
		$insert = $PowerBB->DB->sql_query('INSERT INTO ' . $PowerBB->table['info'] . " SET var_name='cssprefs',value='PBBoard_1_Default'");

		return ($insert) ? true : false;
	}

	function Addcaptcha_type ()
	{
		global $PowerBB;
		$insert = $PowerBB->DB->sql_query('INSERT INTO ' . $PowerBB->table['info'] . " SET var_name='captcha_type',value='captcha_IMG'");

		return ($insert) ? true : false;
	}

	function Addquestions ()
	{
		global $PowerBB;
		$insert = $PowerBB->DB->sql_query('INSERT INTO ' . $PowerBB->table['info'] . " SET var_name='questions',value=''");

		return ($insert) ? true : false;
	}

	function Addanswers ()
	{
		global $PowerBB;
		$insert = $PowerBB->DB->sql_query('INSERT INTO ' . $PowerBB->table['info'] . " SET var_name='answers',value=''");

		return ($insert) ? true : false;
	}

   // Create Tables 2  PRIMARY KEY
	function _CreateProfileVisitor()
	{
		global $PowerBB;
		  $create_query = $PowerBB->DB->sql_query("CREATE TABLE ".$PowerBB->table['profile_view']." (
		  profile_user_id mediumint(8) unsigned NOT NULL,
		  viewer_user_id mediumint(8) unsigned NOT NULL,
		  viewer_user_counter mediumint(8) unsigned NOT NULL,
		  viewer_visit_time int(11) unsigned NOT NULL default '0',
		   KEY profile_user_id (profile_user_id),
		   KEY viewer_user_id (viewer_user_id))");

		return ($create_query) ? true : false;

	}

    function Addrprofile_viewers()
	{
		global $PowerBB;

		$this->_TempArr['AddArr']					=	array();
		$this->_TempArr['AddArr']['table']			=	$PowerBB->table['member'];
		$this->_TempArr['AddArr']['field_name']		=	'profile_viewers';
		$this->_TempArr['AddArr']['field_des']		=	"int( 1 ) NOT NULL DEFAULT '1'";

		$add = $this->add_field($this->_TempArr['AddArr']);

		unset($this->_TempArr['AddArr']);

		return ($add) ? true : false;
	}

    function Addsubjects_review_num()
	{
		global $PowerBB;

		$this->_TempArr['AddArr']					=	array();
		$this->_TempArr['AddArr']['table']			=	$PowerBB->table['section'];
		$this->_TempArr['AddArr']['field_name']		=	'subjects_review_num';
		$this->_TempArr['AddArr']['field_des']		=	"int( 1 ) NOT NULL DEFAULT '0'";

		$add = $this->add_field($this->_TempArr['AddArr']);

		unset($this->_TempArr['AddArr']);

		return ($add) ? true : false;
	}

    function Addreplys_review_num()
	{
		global $PowerBB;

		$this->_TempArr['AddArr']					=	array();
		$this->_TempArr['AddArr']['table']			=	$PowerBB->table['section'];
		$this->_TempArr['AddArr']['field_name']		=	'replys_review_num';
		$this->_TempArr['AddArr']['field_des']		=	"int( 1 ) NOT NULL DEFAULT '0'";

		$add = $this->add_field($this->_TempArr['AddArr']);

		unset($this->_TempArr['AddArr']);

		return ($add) ? true : false;
	}

    function AddMore_places_Adsense()
	{
		global $PowerBB;
    $add = array();
	$add[1] = $PowerBB->DB->sql_query("ALTER TABLE " . $PowerBB->table['adsense'] . " ADD downfoot INT( 9 ) NOT NULL");
	$add[2] = $PowerBB->DB->sql_query("ALTER TABLE " . $PowerBB->table['adsense'] . " ADD all_page INT( 9 ) NOT NULL");
	$add[3] = $PowerBB->DB->sql_query("ALTER TABLE " . $PowerBB->table['adsense'] . " ADD between_replys INT( 9 ) NOT NULL");
	$add[4] = $PowerBB->DB->sql_query("ALTER TABLE " . $PowerBB->table['adsense'] . " ADD down_topic INT( 9 ) NOT NULL");
	$add[5] = $PowerBB->DB->sql_query("ALTER TABLE " . $PowerBB->table['adsense'] . " ADD in_page VARCHAR( 255 ) NOT NULL");
	$add[6] = $PowerBB->DB->sql_query("ALTER TABLE " . $PowerBB->table['adsense'] . " ADD side INT( 9 ) NOT NULL");
	$add[7] = $PowerBB->DB->sql_query("ALTER TABLE " . $PowerBB->table['adsense'] . " ADD mid_topic INT( 9 ) NOT NULL");

		if ($add[1]
			and $add[2]
			and $add[3]
            and $add[4]
			and $add[5]
    		and $add[6]
			and $add[7])
		{
		return true;
		}
		else
		{
		return false;
		}
   }

   	function AddAdsenseLimitedSections()
	{
		global $PowerBB;
		$insert = $PowerBB->DB->sql_query('INSERT INTO ' . $PowerBB->table['info'] . " SET var_name='adsense_limited_sections',value='9,8'");

		return ($insert) ? true : false;
	}

   	function Addactiv_welcome_message()
	{
		global $PowerBB;
		$insert = $PowerBB->DB->sql_query('INSERT INTO ' . $PowerBB->table['info'] . " SET var_name='activ_welcome_message',value='0'");

		return ($insert) ? true : false;
	}

   	function Addwelcome_message_text()
	{
		global $PowerBB;
		$insert = $PowerBB->DB->sql_query('INSERT INTO ' . $PowerBB->table['info'] . " SET var_name='welcome_message_text',value='مرحباً بك معنا يا {username} في {title} الرجاء الإطلاع على قوانين المنتدى على الرابط التالي {rules} قبل المشاركة، ونتمنى لك قضاء وقت مفيد وممتع معنا !'");

		return ($insert) ? true : false;
	}

   	function Addwelcome_message_mail_or_private()
	{
		global $PowerBB;
		$insert = $PowerBB->DB->sql_query('INSERT INTO ' . $PowerBB->table['info'] . " SET var_name='welcome_message_mail_or_private',value='3'");

		return ($insert) ? true : false;
	}

    function Addrwarn_liftdate()
	{
		global $PowerBB;

		$this->_TempArr['AddArr']					=	array();
		$this->_TempArr['AddArr']['table']			=	$PowerBB->table['warnlog'];
		$this->_TempArr['AddArr']['field_name']		=	'warn_liftdate';
		$this->_TempArr['AddArr']['field_des']		=	"VARCHAR( 200 ) NOT NULL";

		$add = $this->add_field($this->_TempArr['AddArr']);

		unset($this->_TempArr['AddArr']);

		return ($add) ? true : false;
	}

    function Addtopic_day_number()
	{
		global $PowerBB;

		$this->_TempArr['AddArr']					=	array();
		$this->_TempArr['AddArr']['table']			=	$PowerBB->table['group'];
		$this->_TempArr['AddArr']['field_name']		=	'topic_day_number';
		$this->_TempArr['AddArr']['field_des']		=	"int( 1 ) NOT NULL DEFAULT '0'";

		$add = $this->add_field($this->_TempArr['AddArr']);

		unset($this->_TempArr['AddArr']);

		return ($add) ? true : false;
	}

	function Addnum_entries_error ()
	{
		global $PowerBB;
		$insert = $PowerBB->DB->sql_query('INSERT INTO ' . $PowerBB->table['info'] . " SET var_name='num_entries_error',value='7'");

		return ($insert) ? true : false;
	}

    function Addstyle_sheet_profile()
	{
		global $PowerBB;

		$this->_TempArr['AddArr']					=	array();
		$this->_TempArr['AddArr']['table']			=	$PowerBB->table['member'];
		$this->_TempArr['AddArr']['field_name']		=	'style_sheet_profile';
		$this->_TempArr['AddArr']['field_des']		=	"LONGTEXT NOT NULL";

		$add = $this->add_field($this->_TempArr['AddArr']);

		unset($this->_TempArr['AddArr']);

		return ($add) ? true : false;
	}

	function Addstyle_block_latest_news ()
	{
		global $PowerBB;
		$insert = $PowerBB->DB->sql_query('INSERT INTO ' . $PowerBB->table['info'] . " SET var_name='style_block_latest_news',value='1'");

		return ($insert) ? true : false;
	}

	function Addsearch_engine_spiders ()
	{
		global $PowerBB;
		$insert = $PowerBB->DB->sql_query('INSERT INTO ' . $PowerBB->table['info'] . " SET var_name='search_engine_spiders',value='Googlebot,Yahoo!,msnbot,Googlebot-Image,Gaisbot,GalaxyBot,msnbot,Rambler,AbachoBOT,accoona,AcoiRobot,ASPSeek,CrocCrawler,Dumbot,FAST-WebCrawler,GeonaBot,Lycos,MSRBOT,Scooter,AltaVista,IDBot,eStyle,Scrubby'");

		return ($insert) ? true : false;
	}

 }

$PowerBB->install = new PowerBBTHETA;
$PowerBB->html->page_body('<div class="pbboard_body">');
$PowerBB->html->page_header('معالج ترقية برنامج منتديات PBBoard 3.0.0');
$logo = $PowerBB->html->create_image(array('align'=>'left','alt'=>'PowerBB','src'=>'../images/logo.png', 'border'=>'0', 'cellspacing'=>'0','return'=>true));
$PowerBB->html->open_table('100%',true);
$PowerBB->html->cells($logo,'header_logo_side');


if ($PowerBB->_GET['step'] == 0)
{

$PowerBB->html->cells('الترقية إلى الإصدار 3.0.0 من  برنامج PBBoard','main1');
$PowerBB->html->close_p();
$PowerBB->html->close_table();
$PowerBB->html->make_link('الخطوه الأولى -> أدخال الإستعلامات','?step=1');
}
if ($PowerBB->_GET['step'] == 1)
{
	$PowerBB->html->cells('عمليات الاضافة والتعطيل والتحديث','main1');
	$PowerBB->html->close_table();

	$p[1] 		= 	$PowerBB->install->AddCssPrefs();
	$msgs[1] 	= 	($p[1]) ? 'تم إنشاء حقل cssprefs ' : 'لم يتم إنشاء حقل cssPrefs';
    $p[2] 		= 	$PowerBB->install->Update_var_name_admin_ajax_main_rename();
	$msgs[2] 	= 	($p[2]) ? 'تم إنشاء حقل get_group_username_style ' : 'لم يتم إنشاء حقل get_group_username_style';
    $p[3] 		= 	$PowerBB->install->UpdateNoActiveAddons();
	$msgs[3] 	= 	($p[3]) ? 'تم تعطيل كافة الاضافات البرمجية' : 'لم يتم تعطيل كافة الاضافات البرمجية';
    $p[4] 		= 	$PowerBB->install->DisableAllOldStyles();
	$msgs[4] 	= 	($p[4]) ? 'تم تعطيل كافة الإستايلات' : 'لم يتم تعطيل كافة الإستايلات';
    $p[5] 		= 	$PowerBB->install->DisableAllOldLangs();
	$msgs[5] 	= 	($p[5]) ? 'تم تعطيل كافة اللغات' : 'لم يتم تعطيل كافة اللغات';

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
	$PowerBB->html->cells('عمليات اضافة الأستايل والقوالب','main1');
	$PowerBB->html->close_table();

	$p[6] 		= 	$PowerBB->install->_CreateTemplate();
     $msgs[6] 	= 	($p[6]) ? 'تم إنشاء جدول template ' : 'لم يتم إنشاء جدول template';

	$p[7] 		= 	$PowerBB->install->_InsertStyle();
	$msgs[7] 	= 	($p[7]) ? 'تم إنشاء قوالب الأستايل ' : 'لم يتم إنشاء قوالب الإستايل';

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
	$PowerBB->html->cells('عمليات اضافة اللغة والعبارات','main1');
	$PowerBB->html->close_table();

	$p[8] 		= 	$PowerBB->install->_CreatePhrase();
	$msgs[8] 	= 	($p[8]) ? 'تم إنشاء جدول phrase_language ' : 'لم يتم إنشاء جدول phrase_language';

	$p[9] 		= 	$PowerBB->install->_InsertLang();
	$msgs[9] 	= 	($p[9]) ? 'تم إنشاء العبارات  ' : 'لم يتم إنشاء العبارات';

	$PowerBB->html->open_p();

	foreach ($msgs as $msg)
	{
		$PowerBB->html->p_msg($msg);
	}

$PowerBB->html->close_p();
$PowerBB->html->close_table();
$PowerBB->html->make_link('الخطوه الرابعة -> أدخال الإستعلامات','?step=4');
}
if ($PowerBB->_GET['step'] == 4)
{
	$PowerBB->html->cells('عمليات اضافة جداول وحقول جديدة','main1');
	$PowerBB->html->close_table();


		$p[12] 		= 	$PowerBB->install->_CreateProfileVisitor();
		$msgs[12] 	= 	($p[12]) ? 'تم إنشاء جدول profile_view ' : 'لم يتم إنشاء جدول profile_view';

		$p[13] 		= 	$PowerBB->install->Addrprofile_viewers();
		$msgs[13] 	= 	($p[13]) ? 'تم إنشاء حقل profile_viewers ' : 'لم يتم إنشاء حقل profile_viewers';

		$p[14] 		= 	$PowerBB->install->AddMore_places_Adsense();
		$msgs[14] 	= 	($p[14]) ? 'تم انشاء الحقول التالية في جدول اعلانات ادسنس (downfoot-all_page-between_replys-down_topic-in_page-side-mid_topic)' : 'لم يتم انشاء الحقول التالية في جدول اعلانات ادسنس (downfoot-all_page-between_replys-down_topic-in_page-side-mid_topic)';

		$p[15] 		= 	$PowerBB->install->Addcaptcha_type();
		$msgs[15] 	= 	($p[15]) ? 'تم إنشاء حقل captcha_type ' : 'لم يتم إنشاء حقل captcha_type';

		$p[16] 		= 	$PowerBB->install->Addquestions();
		$msgs[16] 	= 	($p[16]) ? 'تم إنشاء حقل questions ' : 'لم يتم إنشاء حقل questions';

		$p[17] 		= 	$PowerBB->install->Addanswers();
		$msgs[17] 	= 	($p[17]) ? 'تم إنشاء حقل answers ' : 'لم يتم إنشاء حقل answers';

	$PowerBB->html->open_p();

	foreach ($msgs as $msg)
	{
		$PowerBB->html->p_msg($msg);
	}

$PowerBB->html->close_p();
$PowerBB->html->close_table();
$PowerBB->html->make_link('الخطوه الخامسة -> أدخال الإستعلامات','?step=5');
}
if ($PowerBB->_GET['step'] == 5)
{
	$PowerBB->html->cells('عمليات اضافة جداول وحقول جديدة','main1');
	$PowerBB->html->close_table();

		$p[15] 		= 	$PowerBB->install->AddAdsenseLimitedSections();
		$msgs[15] 	= 	($p[15]) ? 'تم إنشاء حقل adsense_limited_sections ' : 'لم يتم إنشاء حقل adsense_limited_sections';

		$p[16] 		= 	$PowerBB->install->Addactiv_welcome_message();
		$msgs[16] 	= 	($p[16]) ? 'تم إنشاء حقل activ_welcome_message ' : 'لم يتم إنشاء حقل activ_welcome_message';

		$p[17] 		= 	$PowerBB->install->Addwelcome_message_text();
		$msgs[17] 	= 	($p[17]) ? 'تم إنشاء حقل welcome_message_text ' : 'لم يتم إنشاء حقل welcome_message_text';

		$p[18] 		= 	$PowerBB->install->Addwelcome_message_mail_or_private();
		$msgs[18] 	= 	($p[18]) ? 'تم إنشاء حقل welcome_message_mail_or_private ' : 'لم يتم إنشاء حقل welcome_message_mail_or_private';

		$p[19] 		= 	$PowerBB->install->Addrwarn_liftdate();
		$msgs[19] 	= 	($p[19]) ? 'تم إنشاء حقل warn_liftdate ' : 'لم يتم إنشاء حقل warn_liftdate';

		$p[20] 		= 	$PowerBB->install->Addtopic_day_number();
		$msgs[20] 	= 	($p[20]) ? 'تم إنشاء حقل topic_day_number ' : 'لم يتم إنشاء حقل topic_day_number';

	$PowerBB->html->open_p();

	foreach ($msgs as $msg)
	{
		$PowerBB->html->p_msg($msg);
	}

$PowerBB->html->close_p();
$PowerBB->html->close_table();
$PowerBB->html->make_link('الخطوه السادسة -> أدخال الإستعلامات','?step=6');
}
if ($PowerBB->_GET['step'] == 6)
{
	$PowerBB->html->cells('عمليات اضافة جداول وحقول جديدة','main1');
	$PowerBB->html->close_table();

		$p[21] 		= 	$PowerBB->install->Addsubjects_review_num();
		$msgs[21] 	= 	($p[21]) ? 'تم إنشاء حقل subjects_review_num ' : 'لم يتم إنشاء حقل subjects_review_num';

		$p[22] 		= 	$PowerBB->install->Addnum_entries_error();
		$msgs[22] 	= 	($p[22]) ? 'تم إنشاء حقل num_entries_error ' : 'لم يتم إنشاء حقل num_entries_error';

		$p[23] 		= 	$PowerBB->install->Addreplys_review_num();
		$msgs[23] 	= 	($p[23]) ? 'تم إنشاء حقل replys_review_num ' : 'لم يتم إنشاء حقل replys_review_num';

		$p[24] 		= 	$PowerBB->install->Addstyle_sheet_profile();
		$msgs[24] 	= 	($p[24]) ? 'تم إنشاء حقل style_sheet_profile ' : 'لم يتم إنشاء حقل style_sheet_profile';

		$p[25] 		= 	$PowerBB->install->Addstyle_block_latest_news();
		$msgs[25] 	= 	($p[25]) ? 'تم إنشاء حقل style_block_latest_news ' : 'لم يتم إنشاء حقل style_block_latest_news';

		$p[26] 		= 	$PowerBB->install->Addsearch_engine_spiders();
		$msgs[26] 	= 	($p[26]) ? 'تم إنشاء حقل search_engine_spiders ' : 'لم يتم إنشاء حقل search_engine_spiders';


	$PowerBB->html->open_p();

	foreach ($msgs as $msg)
	{
		$PowerBB->html->p_msg($msg);
	}

$PowerBB->html->close_p();
$PowerBB->html->close_table();
$PowerBB->html->make_link('الخطوه السابعة والأخيرة -> اتمام الترقية','?step=7');
}
elseif ($PowerBB->_GET['step'] == 7)
{
	$PowerBB->html->cells('الخطوة النهائية','main1');

	$PowerBB->html->close_table();
      $NewVersion = $PowerBB->install->UpdateVersion();
 		   $del_file_forums_cache = @unlink('../../cache/forums_cache_1.php');
		   $del_file_sectiongroup_cache1 = @unlink('../../cache/sectiongroup_cache1.php');
		   $del_file_sectiongroup_cache2 = @unlink('../../cache/sectiongroup_cache2.php');


		$PowerBB->html->open_p();
        $PowerBB->html->p_msg('تمت الترقية إلى الأصدار 3.0.0 بنجاح. ');
		$PowerBB->html->close_p();

		$PowerBB->html->make_link('البدأ بالترقية إلى الإصدار 3.0.1','upg_301.php?step=1');


}

     $PowerBB->html->page_footer("<br><br><br><div id='copyright'>Upgrade PBBoard <br>Powered by PBBoard © Version 3.0.0 </div></div>");

?>
