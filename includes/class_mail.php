<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require 'mailer/phpmailer/phpmailer/src/Exception.php';
require 'mailer/phpmailer/phpmailer/src/PHPMailer.php';
require 'mailer/phpmailer/phpmailer/src/SMTP.php';
//Load Composer's autoloader
require 'mailer/autoload.php';
//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer();
?>

