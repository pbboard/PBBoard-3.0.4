<?php
error_reporting(E_ERROR | E_PARSE);
$_SERVER['REQUEST_URI'] = str_replace('includes/class_mail.php', '', $_SERVER['REQUEST_URI'] );
	$dir =($_SERVER['DOCUMENT_ROOT'].$_SERVER['REQUEST_URI']);
	define('DONT_STRIPS_SLIASHES',true);
	define('STOP_STYLE',true);
	if (defined('STOP_COMMON'))
	{
	define('IN_PowerBB',true);
	include($dir.'common.php');
    }
	//Import PHPMailer classes into the global namespace
	//These must be at the top of your script, not inside a function
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;
	//Load Composer's autoloader
	require 'mailer/autoload.php';
	//Create an instance; passing `true` enables exceptions
	$mail = new PHPMailer();
	if($PowerBB->_CONF['info_row']['mailer']=='phpmail')
	{
	$SMTPmailer = false;
	}
	else
	{
	$SMTPmailer = true;
	}
	//$mail->SMTPDebug = 2;  // Enable verbose debug output
	$mail->isSMTP();   // Set mailer to use SMTP
	$mail->Host = $PowerBB->_CONF['info_row']['smtp_server'];  // Specify main and backup SMTP servers
	$mail->SMTPAuth = $SMTPmailer;  // Enable SMTP authentication
	$mail->Username = $PowerBB->_CONF['info_row']['smtp_username']; // SMTP username
	$mail->Password = $PowerBB->_CONF['info_row']['smtp_password']; // SMTP password
	$mail->SMTPSecure = $PowerBB->_CONF['info_row']['smtp_secure']; // Enable TLS encryption, ssl` also accepted
	$mail->Port = $PowerBB->_CONF['info_row']['smtp_port'];  // TCP port to connect to
	//Content format
	$mail->isHTML(true);        //Set email format to HTML
	$mail->CharSet = "UTF-8";
?>

