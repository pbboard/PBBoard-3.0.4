<?php
define('ROOT_PATH', dirname(dirname(__FILE__)) . '/');
define('DONT_STRIPS_SLIASHES',true);
define('STOP_STYLE',true);
define('IN_PowerBB',true);
require(ROOT_PATH . 'common.php');
Run_Feeder();

	function Run_Feeder()
	{
       global $PowerBB;
	$expected_key = $PowerBB->_CONF['info_row']['extrafields_cache'];
	$received_key = isset($PowerBB->_GET['key']) ? $PowerBB->_GET['key'] : '';

		if (empty($expected_key) || $received_key !== $expected_key) {
		    header('HTTP/1.0 403 Forbidden');
		    die("Unauthorized cron access.");
		}

    }


require(dirname(__FILE__) . '/functions_feeder.php');

// 4. تأكيد التشغيل عبر سطر الأوامر (CLI)
/*
if (php_sapi_name() !== 'cli') {
    die("Access denied. This script must be run via the command line (Cron Job).");
}
*/
// 5. استدعاء الدالة (كما هو سابقاً)
// مثال افتراضي للاستدعاء:
$feeder_class = new Feeder();
$feeder_class->_RunFeedRss();

// 6. تسجيل وقت الانتهاء (اختياري)
error_log("RSS Feeds processed successfully at " . date('Y-m-d H:i:s'));

?>