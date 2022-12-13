<?php

/**
*  upgrader to version 2.1.3
*/

define('NO_TEMPLATE',true);

$CALL_SYSTEM				= 	array();
$CALL_SYSTEM['SECTION'] 	= 	true;

include('../common.php');

class PowerBBTHETA extends PowerBBInstall
{
	var $_TempArr 	= 	array();
	var $_Masseges	=	array();

	function CheckVersion()
	{
		global $PowerBB;

		return ($PowerBB->_CONF['info_row']['MySBB_version'] == '2.1.2') ? true : false;
	}


	function UpdateVersion()
	{
		global $PowerBB;

		$update = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['info'] . " SET value='2.1.3' WHERE var_name='MySBB_version'");

		return ($update) ? true : false;
	}

    function AddrOnline_section_id()
	{
		global $PowerBB;

		$this->_TempArr['AddArr']					=	array();
		$this->_TempArr['AddArr']['table']			=	$PowerBB->table['online'];
		$this->_TempArr['AddArr']['field_name']		=	'section_id';
		$this->_TempArr['AddArr']['field_des']		=	"int(9) NOT NULL";

		$add = $this->add_field($this->_TempArr['AddArr']);

		unset($this->_TempArr['AddArr']);

		return ($add) ? true : false;
	}

    function AddrOnline_is_bot()
	{
		global $PowerBB;

		$this->_TempArr['AddArr']					=	array();
		$this->_TempArr['AddArr']['table']			=	$PowerBB->table['online'];
		$this->_TempArr['AddArr']['field_name']		=	'is_bot';
		$this->_TempArr['AddArr']['field_des']		=	"int(1) NOT NULL";

		$add = $this->add_field($this->_TempArr['AddArr']);

		unset($this->_TempArr['AddArr']);

		return ($add) ? true : false;
	}

    function AddrOnline_bot_name()
	{
		global $PowerBB;

		$this->_TempArr['AddArr']					=	array();
		$this->_TempArr['AddArr']['table']			=	$PowerBB->table['online'];
		$this->_TempArr['AddArr']['field_name']		=	'bot_name';
		$this->_TempArr['AddArr']['field_des']		=	"varchar(255) NOT NULL";

		$add = $this->add_field($this->_TempArr['AddArr']);

		unset($this->_TempArr['AddArr']);

		return ($add) ? true : false;
	}


    function AddrView_action_edit()
	{
		global $PowerBB;

		$this->_TempArr['AddArr']					=	array();
		$this->_TempArr['AddArr']['table']			=	$PowerBB->table['group'];
		$this->_TempArr['AddArr']['field_name']		=	'view_action_edit';
		$this->_TempArr['AddArr']['field_des']		=	"int( 1 ) NOT NULL DEFAULT '1'";

		$add = $this->add_field($this->_TempArr['AddArr']);

		unset($this->_TempArr['AddArr']);

		return ($add) ? true : false;
	}

	function CreateCustomBBcode()
    {
    global $PowerBB;

	    $this->_TempArr['CreateArr']        =   array();
	    $this->_TempArr['CreateArr']['table_name']  =   $PowerBB->table['custom_bbcode'];
	    $this->_TempArr['CreateArr']['fields']    =   array();
	    $this->_TempArr['CreateArr']['fields'][]  =   'id INT( 9 ) NOT NULL AUTO_INCREMENT PRIMARY KEY';
	    $this->_TempArr['CreateArr']['fields'][]  =   "bbcode_title varchar(255) NOT NULL default ''";
	    $this->_TempArr['CreateArr']['fields'][]  =   "bbcode_desc text";
	    $this->_TempArr['CreateArr']['fields'][]  =   "bbcode_tag varchar(255) NOT NULL default ''";
	    $this->_TempArr['CreateArr']['fields'][]  =   "bbcode_replace text";
	    $this->_TempArr['CreateArr']['fields'][]  =   "bbcode_useoption tinyint(1) NOT NULL default '0'";
	    $this->_TempArr['CreateArr']['fields'][]  =   "bbcode_example text";
	    $this->_TempArr['CreateArr']['fields'][]  =   "bbcode_switch varchar(255) NOT NULL default ''";
	    $this->_TempArr['CreateArr']['fields'][]  =   "bbcode_add_into_menu int(1) NOT NULL default '0'";
	    $this->_TempArr['CreateArr']['fields'][]  =   "bbcode_menu_option_text varchar(200) NOT NULL default ''";
	    $this->_TempArr['CreateArr']['fields'][]  =   "bbcode_menu_content_text varchar(200) NOT NULL default ''";

    $create = $this->create_table($this->_TempArr['CreateArr']);

    return ($create) ? true : false;
    }

	function UpdateDefault_imagesW()
	{
		global $PowerBB;

		$update = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['info'] . " SET value='550' WHERE var_name='default_imagesW'");

		return ($update) ? true : false;
	}

	function UpdateDefault_imagesH()
	{
		global $PowerBB;

		$update = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['info'] . " SET value='800' WHERE var_name='default_imagesH'");

		return ($update) ? true : false;
	}

	function Addactive_calendar()
	{
		global $PowerBB;

		$insert = $PowerBB->DB->sql_query('INSERT INTO ' . $PowerBB->table['info'] . " SET var_name='active_calendar',value='1'");

		return ($insert) ? true : false;
	}

	function AddActive_like_facebook()
	{
		global $PowerBB;

		$insert = $PowerBB->DB->sql_query('INSERT INTO ' . $PowerBB->table['info'] . " SET var_name='active_like_facebook',value='0'");

		return ($insert) ? true : false;
	}

	function AddActive_add_this()
	{
		global $PowerBB;

		$insert = $PowerBB->DB->sql_query('INSERT INTO ' . $PowerBB->table['info'] . " SET var_name='active_add_this',value='0'");

		return ($insert) ? true : false;
	}

	function Addactive_visitor_message()
	{
		global $PowerBB;

		$insert = $PowerBB->DB->sql_query('INSERT INTO ' . $PowerBB->table['info'] . " SET var_name='active_visitor_message',value='1'");

		return ($insert) ? true : false;
	}


	function Addactive_friend()
	{
		global $PowerBB;

		$insert = $PowerBB->DB->sql_query('INSERT INTO ' . $PowerBB->table['info'] . " SET var_name='active_friend',value='1'");

		return ($insert) ? true : false;
	}


	function Addactive_archive()
	{
		global $PowerBB;

		$insert = $PowerBB->DB->sql_query('INSERT INTO ' . $PowerBB->table['info'] . " SET var_name='active_archive',value='1'");

		return ($insert) ? true : false;
	}

	function Addactive_rss()
	{
		global $PowerBB;

		$insert = $PowerBB->DB->sql_query('INSERT INTO ' . $PowerBB->table['info'] . " SET var_name='active_rss',value='1'");

		return ($insert) ? true : false;
	}

	function Addactive_send_admin_message()
	{
		global $PowerBB;

		$insert = $PowerBB->DB->sql_query('INSERT INTO ' . $PowerBB->table['info'] . " SET var_name='active_send_admin_message',value='1'");

		return ($insert) ? true : false;
	}


	function Addactive_reply_today()
	{
		global $PowerBB;

		$insert = $PowerBB->DB->sql_query('INSERT INTO ' . $PowerBB->table['info'] . " SET var_name='active_reply_today',value='1'");

		return ($insert) ? true : false;
	}


	function Addactive_subject_today()
	{
		global $PowerBB;

		$insert = $PowerBB->DB->sql_query('INSERT INTO ' . $PowerBB->table['info'] . " SET var_name='active_subject_today',value='1'");

		return ($insert) ? true : false;
	}


	function Addactive_static()
	{
		global $PowerBB;

		$insert = $PowerBB->DB->sql_query('INSERT INTO ' . $PowerBB->table['info'] . " SET var_name='active_static',value='1'");

		return ($insert) ? true : false;
	}

	function Addactive_team()
	{
		global $PowerBB;

		$insert = $PowerBB->DB->sql_query('INSERT INTO ' . $PowerBB->table['info'] . " SET var_name='active_team',value='1'");

		return ($insert) ? true : false;
	}

	function username_addthis()
	{
		global $PowerBB;

		return ($PowerBB->_CONF['info_row']['MySBB_version'] == '2.1.2') ? true : false;
	}

	function UpdateUsername_addthis()
	{
		global $PowerBB;

		$update = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['info'] . " SET value='xa-4c4700355e83e612' WHERE var_name='use_list'");

		return ($update) ? true : false;
	}

    function Addforum_title_color()
	{
		global $PowerBB;

		$this->_TempArr['AddArr']					=	array();
		$this->_TempArr['AddArr']['table']			=	$PowerBB->table['section'];
		$this->_TempArr['AddArr']['field_name']		=	'forum_title_color';
		$this->_TempArr['AddArr']['field_des']		=	"varchar(7) NOT NULL";

		$add = $this->add_field($this->_TempArr['AddArr']);

		unset($this->_TempArr['AddArr']);

		return ($add) ? true : false;
	}

    function AddrTrash()
	{
		global $PowerBB;

		$this->_TempArr['AddArr']					=	array();
		$this->_TempArr['AddArr']['table']			=	$PowerBB->table['section'];
		$this->_TempArr['AddArr']['field_name']		=	'trash';
		$this->_TempArr['AddArr']['field_des']		=	"int( 1 ) NOT NULL DEFAULT '0'";

		$add = $this->add_field($this->_TempArr['AddArr']);

		unset($this->_TempArr['AddArr']);

		return ($add) ? true : false;
	}

	function UpdateAjax_moderator_options()
	{
		global $PowerBB;

		$update = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['info'] . " SET value='0' WHERE var_name='ajax_moderator_options'");

		return ($update) ? true : false;
	}


}

$PowerBB->install = new __THETA;

$PowerBB->html->page_header('معالج ترقية برنامج منتديات PBBoard 2.1.3');

$logo = $PowerBB->html->create_image(array('align'=>'right','alt'=>'PowerBB','src'=>'../images/logo.png','return'=>true));
$PowerBB->html->open_table('100%',true);
$PowerBB->html->cells($logo,'header_logo_side');

if (!$PowerBB->install->CheckVersion())
{
	$PowerBB->html->cells('اصدار غير صحيح','main1');
	$PowerBB->html->close_table();

	$PowerBB->functions->errorstop('يرجى التحقق من انك قمت بتشغيل تحديثات upg_212.php');
$PowerBB->html->close_table();
}
if ($PowerBB->_GET['step'] == 0)
{

$PowerBB->html->cells('الترقية إلى الإصدار 2.1.3 من  برنامج PBBoard','main1');
$PowerBB->html->close_p();
$PowerBB->html->close_table();
$PowerBB->html->make_link('الخطوه الأولى -> أدخال الإستعلامات','?step=1');
}
if ($PowerBB->_GET['step'] == 1)
{
	$PowerBB->html->cells('عمليات الاضافه','main1');
	$PowerBB->html->close_table();


	$p[1] 		= 	$PowerBB->install->AddrOnline_section_id();
	$msgs[1] 	= 	($p[1]) ? 'تم إنشاء حقل section_id ' : 'لم يتم إنشاء حقل section_id';

	$p[2] 		= 	$PowerBB->install->AddrView_action_edit();
	$msgs[2] 	= 	($p[2]) ? 'تم إنشاء حقل view_action_edit ' : 'لم يتم إنشاء حقل view_action_edit';

	$p[3] 		= 	$PowerBB->install->AddrOnline_is_bot();
	$msgs[3] 	= 	($p[3]) ? 'تم إنشاء حقل is_bot ' : 'لم يتم إنشاء حقل is_bot';

	$p[4] 		= 	$PowerBB->install->AddrOnline_bot_name();
	$msgs[4] 	= 	($p[4]) ? 'تم إنشاء حقل bot_name ' : 'لم يتم إنشاء حقل bot_name';

	$p[5] 		= 	$PowerBB->install->UpdateDefault_imagesW();
	$msgs[5] 	= 	($p[5]) ? 'تم تحديث قيمة حقل default_imagesW ' : 'لم يتم تحديث قيمة حقل default_imagesW';

	$p[6] 		= 	$PowerBB->install->UpdateDefault_imagesH();
	$msgs[6] 	= 	($p[6]) ? 'تم تحديث قيمة حقل default_imagesH ' : 'لم يتم تحديث قيمة حقل default_imagesH';

	$p[7] 		= 	$PowerBB->install->Addactive_calendar();
	$msgs[7] 	= 	($p[7]) ? 'تم إنشاء حقل active_calendar ' : 'لم يتم إنشاء حقل active_calendar';

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
	$PowerBB->html->cells('عمليات الاضافه','main1');
	$PowerBB->html->close_table();


	$p[8] 		= 	$PowerBB->install->AddActive_like_facebook();
	$msgs[8] 	= 	($p[8]) ? 'تم إنشاء حقل ctive_like_facebook ' : 'لم يتم إنشاء حقل ctive_like_facebook';

	$p[9] 		= 	$PowerBB->install->AddActive_add_this();
	$msgs[9] 	= 	($p[9]) ? 'تم إنشاء حقل active_add_this ' : 'لم يتم إنشاء حقل active_add_this';

	$p[10] 		= 	$PowerBB->install->Addactive_visitor_message();
	$msgs[10] 	= 	($p[10]) ? 'تم إنشاء حقل active_visitor_message ' : 'لم يتم إنشاء حقل active_visitor_message';

	$p[11] 		= 	$PowerBB->install->Addactive_friend();
	$msgs[11] 	= 	($p[11]) ? 'تم إنشاء حقل active_friend ' : 'لم يتم إنشاء حقل active_friend';

	$p[12] 		= 	$PowerBB->install->Addactive_archive();
	$msgs[12] 	= 	($p[12]) ? 'تم إنشاء حقل active_archive ' : 'لم يتم إنشاء حقل active_archive';

	$p[13] 		= 	$PowerBB->install->Addactive_rss();
	$msgs[13] 	= 	($p[13]) ? 'تم إنشاء حقل active_rss ' : 'لم يتم إنشاء حقل active_rss';

	$PowerBB->html->open_p();

	foreach ($msgs as $msg)
	{
		$PowerBB->html->p_msg($msg);
	}

$PowerBB->html->close_p();
$PowerBB->html->close_table();
$PowerBB->html->make_link('الخطوه الثانية -> أدخال الإستعلامات','?step=3');
 }
if ($PowerBB->_GET['step'] == 3)
{
	$PowerBB->html->cells('عمليات الاضافه','main1');
	$PowerBB->html->close_table();


		$p[14] 		= 	$PowerBB->install->Addactive_send_admin_message();
		$msgs[14] 	= 	($p[14]) ? 'تم إنشاء حقل active_send_admin_message ' : 'لم يتم إنشاء حقل active_send_admin_message';

		$p[15] 		= 	$PowerBB->install->Addactive_reply_today();
		$msgs[15] 	= 	($p[15]) ? 'تم إنشاء حقل active_reply_today ' : 'لم يتم إنشاء حقل active_reply_today';

		$p[16] 		= 	$PowerBB->install->Addactive_subject_today();
		$msgs[16] 	= 	($p[16]) ? 'تم إنشاء حقل active_subject_today ' : 'لم يتم إنشاء حقل active_subject_today';

		$p[17] 		= 	$PowerBB->install->Addactive_static();
		$msgs[17] 	= 	($p[17]) ? 'تم إنشاء حقل active_static ' : 'لم يتم إنشاء حقل active_static';

		$p[18] 		= 	$PowerBB->install->Addactive_team();
		$msgs[18] 	= 	($p[18]) ? 'تم إنشاء حقل active_team ' : 'لم يتم إنشاء حقل active_team';

		$p[19] 		= 	$PowerBB->install->Addforum_title_color();
		$msgs[19] 	= 	($p[19]) ? 'تم إنشاء حقل forum_title_color ' : 'لم يتم إنشاء حقل forum_title_color';

		$p[20] 		= 	$PowerBB->install->AddrTrash();
		$msgs[20] 	= 	($p[20]) ? 'تم إنشاء حقل trash ' : 'لم يتم إنشاء حقل trash';

	$PowerBB->html->open_p();

	foreach ($msgs as $msg)
	{
		$PowerBB->html->p_msg($msg);
	}

$PowerBB->html->close_p();
$PowerBB->html->close_table();
$PowerBB->html->make_link('الخطوه الثانية والأخيرة -> اتمام الترقية','?step=4');
}
elseif ($PowerBB->_GET['step'] == 4)
{
	$PowerBB->html->cells('الخطوة النهائية','main1');

	$PowerBB->html->close_table();
     $CreateCustomBBcode = $PowerBB->install->CreateCustomBBcode();
	 $UpdateUsernameAddthis = $PowerBB->install->UpdateUsername_addthis();
	 $Username_addthis = $PowerBB->install->UpdateUsername_addthis();
	 $Ajax_moderator_options = $PowerBB->install->UpdateAjax_moderator_options();
     $NewVersion = $PowerBB->install->UpdateVersion();

		$PowerBB->html->open_p();
        $PowerBB->html->p_msg('تمت الترقية إلى الأصدار 2.1.3 بنجاح. يرجى التأكد من حذفك لهذا المجلد setup');
		$PowerBB->html->close_p();

		$PowerBB->html->open_p();
		$PowerBB->html->make_link('البدأ بالترقية إلى الإصدار 2.1.4','upg_214.php?step=1');
		$PowerBB->html->close_p();


}


?>
