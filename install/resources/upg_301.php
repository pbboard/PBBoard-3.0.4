<?php

/**
*  upgrader to version 3.0.1
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

		return ($PowerBB->_CONF['info_row']['MySBB_version'] == '3.0.0') ? true : false;
	}


	function UpdateVersion()
	{
		global $PowerBB;

		$update = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['info'] . " SET value='3.0.1' WHERE var_name='MySBB_version'");

		return ($update) ? true : false;
	}

	function AddUserRatingCache ()
	{
		global $PowerBB;
		$insert = $PowerBB->DB->sql_query('INSERT INTO ' . $PowerBB->table['info'] . " SET var_name='users_ratings_cache',value=''");

		return ($insert) ? true : false;
	}

	function AddUsersTitlesCache ()
	{
		global $PowerBB;
		$insert = $PowerBB->DB->sql_query('INSERT INTO ' . $PowerBB->table['info'] . " SET var_name='users_titles_cache',value=''");

		return ($insert) ? true : false;
	}

	function UpdateNoActiveAddons()
	{
		global $PowerBB;

		$update = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['addons'] . " SET active='0'");

		return ($update) ? true : false;
	}

    function AddrPages_sort()
	{
		global $PowerBB;

		$this->_TempArr['AddArr']					=	array();
		$this->_TempArr['AddArr']['table']			=	$PowerBB->table['pages'];
		$this->_TempArr['AddArr']['field_name']		=	'sort';
		$this->_TempArr['AddArr']['field_des']		=	"INT( 9 ) NOT NULL";

		$add = $this->add_field($this->_TempArr['AddArr']);

		unset($this->_TempArr['AddArr']);

		return ($add) ? true : false;
	}

    function AddrPages_link()
	{
		global $PowerBB;

		$this->_TempArr['AddArr']					=	array();
		$this->_TempArr['AddArr']['table']			=	$PowerBB->table['pages'];
		$this->_TempArr['AddArr']['field_name']		=	'link';
		$this->_TempArr['AddArr']['field_des']		=	"text NOT NULL";

		$add = $this->add_field($this->_TempArr['AddArr']);

		unset($this->_TempArr['AddArr']);

		return ($add) ? true : false;
	}


	function ChangePages_html_code()
	{
		global $PowerBB;

		$change = $PowerBB->DB->sql_query("ALTER TABLE " . $PowerBB->table['pages'] . " CHANGE html_code html_code LONGTEXT NOT NULL");

		return ($change) ? true : false;
	}

	function UpdateRatingsCache()
 	{
		global $PowerBB;

		$param = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['userrating'] . " ");

 		$cache 	= 	array();
 		$x		=	0;

		while ($ratings = $PowerBB->DB->sql_fetch_array($param))
		{
			$cache[$x] 					= 	array();
			$cache[$x]['id']		 	= 	$ratings['id'];
			$cache[$x]['rating'] 	    = 	$ratings['rating'];
			$cache[$x]['posts'] 	    = 	$ratings['posts'];

			$x += 1;
		}

		$cache = json_encode($cache);

		$update_cache = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['info'] . " SET value='$cache' WHERE var_name='users_ratings_cache'");
 		return ($update_cache) ? true : false;
 	}

	function UpdateTitlesCache()
 	{
		global $PowerBB;

		$param = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['usertitle'] . " ");

 		$cache 	= 	array();
 		$x		=	0;

		while ($titles = $PowerBB->DB->sql_fetch_array($param))
		{
			$cache[$x] 					= 	array();
			$cache[$x]['id']		 	= 	$titles['id'];
			$cache[$x]['usertitle']    = 	$titles['usertitle'];
			$cache[$x]['posts'] 	    = 	$titles['posts'];

			$x += 1;
		}

		$cache = json_encode($cache);

		$update_cache = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['info'] . " SET value='$cache' WHERE var_name='users_titles_cache'");

 		return ($update_cache) ? true : false;
 	}

 }

$PowerBB->install = new PowerBBTHETA;
$PowerBB->html->page_body('<div class="pbboard_body">');
$PowerBB->html->page_header('معالج ترقية برنامج منتديات PBBoard 3.0.1');
$logo = $PowerBB->html->create_image(array('align'=>'left','alt'=>'PowerBB','src'=>'../images/logo.png', 'border'=>'0', 'cellspacing'=>'0','return'=>true));
$PowerBB->html->open_table('100%',true);
$PowerBB->html->cells($logo,'header_logo_side');


if ($PowerBB->_GET['step'] == 0)
{

$PowerBB->html->cells('الترقية إلى الإصدار 3.0.1 من  برنامج PBBoard','main1');
$PowerBB->html->close_p();
$PowerBB->html->close_table();
$PowerBB->html->make_link('الخطوه الأولى -> أدخال الإستعلامات','?step=1');
}
if ($PowerBB->_GET['step'] == 1)
{
	$PowerBB->html->cells('عمليات الاضافة ','main1');
	$PowerBB->html->close_table();

	$p[1] 		= 	$PowerBB->install->AddUserRatingCache();
	$msgs[1] 	= 	($p[1]) ? 'تم إنشاء حقل users_ratings_cache ' : 'لم يتم إنشاء حقل users_ratings_cache';
    $p[2] 		= 	$PowerBB->install->AddUsersTitlesCache();
	$msgs[2] 	= 	($p[2]) ? 'تم إنشاء حقل users_titles_cache ' : 'لم يتم إنشاء حقل users_titles_cache';

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

    $p[4] 		= 	$PowerBB->install->AddrPages_sort();
	$msgs[4] 	= 	($p[4]) ? 'تم إنشاء حقل sort في جدول pages ' : 'لم يتم إنشاء حقل sort في جدول pages';

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

    $p[5] 		= 	$PowerBB->install->AddrPages_link();
	$msgs[5] 	= 	($p[5]) ? 'تم إنشاء حقل link في جدول pages ' : 'لم يتم إنشاء حقل link في جدول pages';

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
	$PowerBB->html->cells('تحديث حقل html_code في جدول pages ','main1');
	$PowerBB->html->close_table();

    $p[6] 		= 	$PowerBB->install->ChangePages_html_code();
	$msgs[6] 	= 	($p[6]) ? 'تم تغيير حقل html_code في جدول pages ' : 'لم يتم تغيير حقل html_code في جدول pages';


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
	$PowerBB->html->cells('إنشاء كاش رتب الأعضاء','main1');
	$PowerBB->html->close_table();

	$p[7] 		= 	$PowerBB->install->UpdateRatingsCache();
	$msgs[7] 	= 	($p[7]) ? 'تم إنشاء كاش رتب الأعضاء' : 'تم إنشاء كاش رتب الأعضاء';


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
	$PowerBB->html->cells('إنشاء كاش مسميات الأعضاء','main1');
	$PowerBB->html->close_table();

	$p[8] 		= 	$PowerBB->install->UpdateTitlesCache();
	$msgs[8] 	= 	($p[8]) ? 'تم إنشاء كاش مسميات الأعضاء' : 'تم إنشاء كاش مسميات الأعضاء';


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
        $PowerBB->html->p_msg('تمت الترقية إلى الأصدار 3.0.1 بنجاح. ');
		$PowerBB->html->close_p();

		$PowerBB->html->make_link('البدأ بالترقية إلى الإصدار 3.0.2','upg_302.php?step=1');

}

     $PowerBB->html->page_footer("<br><br><br><div id='copyright'>Upgrade PBBoard <br>Powered by PBBoard © Version 3.0.1 </div></div>");

?>
