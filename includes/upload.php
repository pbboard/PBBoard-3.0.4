<?php
error_reporting(E_ERROR | E_PARSE);
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
    // Stop any external post request.
	if ($_SERVER['REQUEST_METHOD'] == 'POST')
	{
	   $Y = explode('/',$_SERVER['HTTP_REFERER']);
	   $X = explode('/',$_SERVER['HTTP_HOST']);

	   if ($Y[2] != $X[0])
	   {
	    exit('No direct script access allowed');
	   }
	   elseif ($Y[2] != $_SERVER['HTTP_HOST'])
	   {
	    exit('No direct script access allowed');
	   }
	}
	// Exit if no file uploaded
	if (!isset($_FILES['file'])) {
	    die('No file uploaded.');
	}

	$pic = $_FILES['file']['tmp_name'];
	$size = @getimagesize($pic);
	if (empty($size[0])
	or empty($size[1]))
	{
	exit("Uploaded file is not an image.");
	die();
	}

	if(!is_array($size))
	{
	exit("Uploaded file is not an image.");
	die();
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
	exit("Uploaded file is not an image.");
	die();
	}

	// Exit if is not a valid image file
	$image_type = exif_imagetype($pic);
	if (!$image_type) {
	 exit("Uploaded file is not an image.");
	 die();
	}


	$_SERVER['REQUEST_URI'] = str_replace( 'includes/upload.php', '', $_SERVER['REQUEST_URI'] );
	$dir =($_SERVER['DOCUMENT_ROOT'].$_SERVER['REQUEST_URI']);
	define('DONT_STRIPS_SLIASHES',true);
	define('STOP_STYLE',true);
	define('IN_PowerBB',true);
	include($dir.'common.php');

	    // Clean Variable extenstion
	    // I hate SQL injections
		// I hate XSS
		$PowerBB->_FILES["file"]["name"]	= 	$PowerBB->functions->CleanVariable($PowerBB->_FILES["file"]["name"],'sql');
		$PowerBB->_FILES["file"]["name"]	= 	$PowerBB->functions->CleanVariable($PowerBB->_FILES["file"]["name"],'html');

		$filename = $PowerBB->_FILES["file"]["name"];
		$temparray = explode(".", $filename);
		$extension = $temparray[count($temparray) - 1];
		$extension = strtolower($extension);
		$BAD_IMAGE = array("gif",
		"jpeg",
		"png",
		"jpg",
		"bmp",
		"tiff");
		if(!in_array($extension,$BAD_IMAGE))
		{
		exit("Nice try but this file is not an image.");
		die();
		}


	if ($PowerBB->_POST['layout'] == '1')
	{
		 // check & Clean variable is a number string
		 if (is_numeric($PowerBB->_POST['left'])
		 and is_numeric($PowerBB->_POST['width'])
		 and is_numeric($PowerBB->_POST['top'])
		 and is_numeric($PowerBB->_POST['height']))
		 {
			$user_id = $PowerBB->functions->CleanVariable($PowerBB->_CONF['member_row']['id'],'intval');
			$profile_cover_photo_position =  "left:".$PowerBB->_POST['left']." top:".$PowerBB->_POST['top']." width:".$PowerBB->_POST['width']." height:".$PowerBB->_POST['height'];
			$UPDATE_user  = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['member'] . " SET profile_cover_photo_position = '$profile_cover_photo_position' WHERE id = '$user_id'");
			exit("Updated Successfully");
		 }
		else
		 {
		  exit("Error:Photo Positions Strings Is Not Numers");
		 }
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
		  if ($_SERVER['REQUEST_METHOD'] == 'POST')
		   {
		    if(isset($_SESSION['csrf']))
		     {
			   @unlink($dir."/".$PowerBB->_CONF['member_row']['profile_cover_photo']);
			 }
		   }
		}
	}

	if (!empty($PowerBB->_FILES)) {
		if ($PowerBB->_FILES["file"]["error"] || !is_uploaded_file($PowerBB->_FILES["file"]["tmp_name"])) {
			die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
		}
		else
		{
				if(isset($_SESSION['csrf']))
				{
					// Get the extension of the file
					$ext = $PowerBB->functions->GetFileExtension($PowerBB->_FILES['file']['name']);

					// Bad try!
					if ($ext == 'MULTIEXTENSION'
					or !$ext)
					{
					echo("Uploaded file is not an image.<br />");
					}
					else
					{
					// Convert the extension to small case
					$ext = strtolower($ext);

					// The extension is not allowed
					if (!array($ext,$allowed_array))
					{
					$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Along_the_image_is_not_permitted']);
					}

				     move_uploaded_file($PowerBB->_FILES['file']['tmp_name'],$targetDir.$fileName);
					$user_id = $PowerBB->_CONF['member_row']['id'];
					$profile_cover_photo = $PowerBB->_CONF['info_row']['download_path']."/upload_cover_photo/".$fileName;

					$UPDATE_user  = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['member'] . " SET profile_cover_photo = '$profile_cover_photo' WHERE id = '$user_id'");
					}
                }
        }
	}





	?>

