<?php

/**
*  upgrader to version 2.1.4
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

		return ($PowerBB->_CONF['info_row']['MySBB_version'] == '2.1.3') ? true : false;
	}


	function UpdateVersion()
	{
		global $PowerBB;

		$update = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['info'] . " SET value='2.1.4' WHERE var_name='MySBB_version'");

		return ($update) ? true : false;
	}



	function _CreateBlocks()
	{
		global $PowerBB;
 	if (!$PowerBB->_CONF['info_row']['title_portal'])
	 {
		$this->_TempArr['CreateArr']				= 	array();
		$this->_TempArr['CreateArr']['table_name'] 	= 	$PowerBB->prefix."blocks";
		$this->_TempArr['CreateArr']['fields'] 		= 	array();
		$this->_TempArr['CreateArr']['fields'][] 	= 	'id INT( 9 ) NOT NULL AUTO_INCREMENT PRIMARY KEY';
		$this->_TempArr['CreateArr']['fields'][] 	= 	'title VARCHAR( 255 ) NOT NULL';
		$this->_TempArr['CreateArr']['fields'][] 	= 	'text longtext NOT NULL';
		$this->_TempArr['CreateArr']['fields'][] 	= 	'place_block VARCHAR( 100 ) NOT NULL';
		$this->_TempArr['CreateArr']['fields'][] 	= 	'sort int(5) NOT NULL';
		$this->_TempArr['CreateArr']['fields'][] 	= 	"active smallint(5) unsigned NOT NULL default '1'";

        $create = $this->create_table($this->_TempArr['CreateArr']);

		if ($create)
		{
		$PowerBB->DB->sql_query('INSERT INTO ' . $PowerBB->prefix."blocks" . " SET title='الأقسام الرئيسية',text='{template}portal_main_categories{/template}',place_block='left',sort='1' ");
		$PowerBB->DB->sql_query('INSERT INTO ' . $PowerBB->prefix."blocks" . " SET title='إحصائيات',text='{template}portal_static{/template}',place_block='left',sort='2' ");
		$PowerBB->DB->sql_query('INSERT INTO ' . $PowerBB->prefix."blocks" . " SET title='الساعة',text='{template}portal_clock{/template}',place_block='left',sort='3' ");
		$PowerBB->DB->sql_query('INSERT INTO ' . $PowerBB->prefix."blocks" . " SET title='التقويم',text='{template}portal_calendar{/template}',place_block='center',sort='2' ");
		$PowerBB->DB->sql_query('INSERT INTO ' . $PowerBB->prefix."blocks" . " SET title='آخر الأخبار',text='{template}portal_last_news{/template}',place_block='center',sort='1' ");
		$PowerBB->DB->sql_query('INSERT INTO ' . $PowerBB->prefix."blocks" . " SET title='القائمة الرئيسية',text='{template}portal_main_menu{/template}',place_block='right',sort='1' ");
		$PowerBB->DB->sql_query('INSERT INTO ' . $PowerBB->prefix."blocks" . " SET title='المتواجدين الآن',text='{template}portal_online{/template}',place_block='right',sort='2' ");
		$PowerBB->DB->sql_query('INSERT INTO ' . $PowerBB->prefix."blocks" . " SET title='آخر المشاركات',text='{template}portal_latest_posts{/template}',place_block='right',sort='3' ");

		$PowerBB->DB->sql_query('INSERT INTO ' . $PowerBB->table['info'] . " SET var_name='title_portal',value='PBB Portal' ");
		$PowerBB->DB->sql_query('INSERT INTO ' . $PowerBB->table['info'] . " SET var_name='active_portal',value='1' ");
		$PowerBB->DB->sql_query('INSERT INTO ' . $PowerBB->table['info'] . " SET var_name='portal_section_news',value='2' ");
		$PowerBB->DB->sql_query('INSERT INTO ' . $PowerBB->table['info'] . " SET var_name='portal_columns',value='3' ");
		$PowerBB->DB->sql_query('INSERT INTO ' . $PowerBB->table['info'] . " SET var_name='portal_news_num',value='4' ");
		}
		unset($this->_TempArr['CreateArr']);
      }
    return ($create) ? true : false;
	}



	function Addportal_news_along()
	{
		global $PowerBB;

		$insert = $PowerBB->DB->sql_query('INSERT INTO ' . $PowerBB->table['info'] . " SET var_name='portal_news_along',value='300'");

		return ($insert) ? true : false;
	}


    function Addlanguagevals()
	{
		global $PowerBB;

		$this->_TempArr['AddArr']					=	array();
		$this->_TempArr['AddArr']['table']			=	$PowerBB->table['addons'];
		$this->_TempArr['AddArr']['field_name']		=	'languagevals';
		$this->_TempArr['AddArr']['field_des']		=	"LONGTEXT NOT NULL";

		$add = $this->add_field($this->_TempArr['AddArr']);

		unset($this->_TempArr['AddArr']);

		return ($add) ? true : false;
	}

	function ChangeForums_cache()
	{
		global $PowerBB;

		$change = $PowerBB->DB->sql_query("ALTER TABLE " . $PowerBB->table['section'] . " CHANGE forums_cache forums_cache LONGTEXT NOT NULL");

		return ($change) ? true : false;
	}

	function ChangeSectiongroup_cache()
	{
		global $PowerBB;

		$change = $PowerBB->DB->sql_query("ALTER TABLE " . $PowerBB->table['section'] . " CHANGE sectiongroup_cache sectiongroup_cache LONGTEXT NOT NULL");

		return ($change) ? true : false;
	}

	function ChangeModerators()
	{
		global $PowerBB;

		$change = $PowerBB->DB->sql_query("ALTER TABLE " . $PowerBB->table['section'] . " CHANGE moderators moderators LONGTEXT NOT NULL");

		return ($change) ? true : false;
	}


}

$PowerBB->install = new PowerBBTHETA;

$PowerBB->html->page_header('معالج ترقية برنامج منتديات PBBoard 2.1.4');

$logo = $PowerBB->html->create_image(array('align'=>'right','alt'=>'PowerBB','src'=>'../images/logo.png','return'=>true));
$PowerBB->html->open_table('100%',true);
$PowerBB->html->cells($logo,'header_logo_side');

if (!$PowerBB->install->CheckVersion())
{
	$PowerBB->html->cells('اصدار غير صحيح','main1');
	$PowerBB->html->close_table();

	$PowerBB->functions->errorstop('يرجى التحقق من انك قمت بتشغيل تحديثات upg_213.php');
$PowerBB->html->close_table();
}
if ($PowerBB->_GET['step'] == 0)
{

$PowerBB->html->cells('الترقية إلى الإصدار 2.1.4 من  برنامج PBBoard','main1');
$PowerBB->html->close_p();
$PowerBB->html->close_table();
$PowerBB->html->make_link('الخطوه الأولى -> أدخال الإستعلامات','?step=1');
}
if ($PowerBB->_GET['step'] == 1)
{
	$PowerBB->html->cells('عمليات الاضافه','main1');
	$PowerBB->html->close_table();

	$p[1] 		= 	$PowerBB->install->Addlanguagevals();
	$msgs[1] 	= 	($p[1]) ? 'تم إنشاء حقل languagevals ' : 'لم يتم إنشاء حقل languagevals';

	$p[2] 		= 	$PowerBB->install->ChangeForums_cache();
	$msgs[2] 	= 	($p[2]) ? 'تم إنشاء حقل forums_cache ' : 'لم يتم إنشاء حقل forums_cache';

	$p[3] 		= 	$PowerBB->install->ChangeSectiongroup_cache();
	$msgs[3] 	= 	($p[3]) ? 'تم تغيير حقل sectiongroup_cache ' : 'لم يتم تغيير حقل sectiongroup_cache';

	$p[4] 		= 	$PowerBB->install->ChangeModerators();
	$msgs[4] 	= 	($p[4]) ? 'تم تغيير حقل moderators ' : 'لم يتم تغيير حقل moderators';

	$p[5] 		= 	$PowerBB->install->Addportal_news_along();
	$msgs[5] 	= 	($p[5]) ? 'تم إنشاء حقل portal_news_along ' : 'لم يتم إنشاء حقل portal_news_along';

	$PowerBB->html->open_p();

	foreach ($msgs as $msg)
	{
		$PowerBB->html->p_msg($msg);
	}

$PowerBB->html->close_p();

$PowerBB->html->close_table();
$PowerBB->html->make_link('الخطوه الثانية والأخيرة -> اتمام الترقية','?step=2');
}
elseif ($PowerBB->_GET['step'] == 2)
{
	$PowerBB->html->cells('الخطوة النهائية','main1');

	$PowerBB->html->close_table();
     $CreateBlocks = $PowerBB->install->_CreateBlocks();
     $NewVersion = $PowerBB->install->UpdateVersion();

		$PowerBB->html->open_p();
        $PowerBB->html->p_msg('تمت الترقية إلى الأصدار 2.1.4 بنجاح. ');
		$PowerBB->html->close_p();

		$PowerBB->html->open_p();
		$PowerBB->html->make_link('البدأ بالترقية إلى الإصدار 3.0.0','upg_300.php?step=1');
		$PowerBB->html->close_p();


}


?>
