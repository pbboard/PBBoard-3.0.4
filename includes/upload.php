<?php
	session_start();
	if(!isset($_SESSION['csrf']))
	{
	exit();
	}

	// Stop any external post request.
	if ($_SERVER['REQUEST_METHOD'] != 'POST')
	{
	exit();
	}
	$pic = $_FILES['file']['tmp_name'];
	$size = @getimagesize($pic);
	if (!$size[0]
	or !$size[1])
	{
	exit();
	}
	$BAD_TYPES = array("image/gif",
	"image/pjpeg",
	"image/jpeg",
	"image/png",
	"image/jpg",
	"image/bmp",
	"image/x-png");
	if(!in_array($_FILES['file']['type'],$BAD_TYPES))
	{
	exit();
	}

	if ( stristr($_FILES['file']['name'],'.php') )
	{
	exit();
	}
	if ( stristr($_FILES['file']['name'],'.php3') )
	{
	exit();
	}
	if ( stristr($_FILES['file']['name'],'.phtml') )
	{
	exit();
	}
	if ( stristr($_FILES['file']['name'],'.pl') )
	{
	exit();
	}
	if ( stristr($_FILES['file']['name'],'.cgi') )
	{
	exit();
	}
	if ( stristr($_FILES['file']['name'],'.asp') )
	{
	exit();
	}
	if ( stristr($_FILES['file']['name'],'.3gp') )
	{
	exit();
	}
	$_SERVER['REQUEST_URI'] = str_replace( 'includes/upload.php', '', $_SERVER['REQUEST_URI'] );
	$dir =($_SERVER['DOCUMENT_ROOT'].$_SERVER['REQUEST_URI']);
	define('DONT_STRIPS_SLIASHES',true);
	define('STOP_STYLE',true);
	define('IN_PowerBB',true);
	include($dir.'common.php');



	if ($PowerBB->_POST['layout'])
	{
	$user_id = $PowerBB->_CONF['member_row']['id'];
	$profile_cover_photo_position =  "left:".$PowerBB->_POST['left']." top:".$PowerBB->_POST['top']." width:".$PowerBB->_POST['width']." height:".$PowerBB->_POST['height'];

	$UPDATE_user  = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['member'] . " SET profile_cover_photo_position = '$profile_cover_photo_position' WHERE id = '$user_id'");
	exit();
	}
	// 5 minutes execution time
	@set_time_limit(5 * 60);

	// Uncomment this one to fake upload time
	// usleep(5000);
	// Settings
	$targetDir = $dir.$PowerBB->_CONF['info_row']['download_path']."/upload_cover_photo/";

	//$targetDir = 'uploads';
	$cleanupTargetDir = true; // Remove old files
	$maxFileAge = 5 * 3600; // Temp file age in seconds


	// Get a file name
	if (isset($PowerBB->_REQUEST["name"])) {
		$fileName = $PowerBB->_REQUEST["name"];
	} elseif (!empty($PowerBB->_FILES)) {
		$fileName = $PowerBB->_FILES["file"]["name"];
	} else {
		$fileName = uniqid("file_");
	}
	$fileName = str_replace( " ", "_", $fileName);

	$filePath = $targetDir.$fileName;


	// Ching file name if exists
	if ($cleanupTargetDir)
	{
		if (file_exists($targetDir.$fileName)) {
		$fileName = time()."".$fileName;
	    }

		if (file_exists($dir."/".$PowerBB->_CONF['member_row']['profile_cover_photo'])) {
			@unlink($dir."/".$PowerBB->_CONF['member_row']['profile_cover_photo']);
		}
	}

	if (!empty($PowerBB->_FILES)) {
		if ($PowerBB->_FILES["file"]["error"] || !is_uploaded_file($PowerBB->_FILES["file"]["tmp_name"])) {
			die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
		}

	}



	move_uploaded_file($PowerBB->_FILES['file']['tmp_name'],$targetDir.$fileName);

	$user_id = $PowerBB->_CONF['member_row']['id'];
	$profile_cover_photo = $PowerBB->_CONF['info_row']['download_path']."/upload_cover_photo/".$fileName;

	$UPDATE_user  = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['member'] . " SET profile_cover_photo = '$profile_cover_photo' WHERE id = '$user_id'");

	?>

