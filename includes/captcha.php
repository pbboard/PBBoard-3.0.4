<?php
error_reporting(E_ERROR | E_PARSE);
// ##############################################################################||
// #   PowerBB Version 2.0.0
// #   https://pbboard.info
// #   Copyright (c) 2009 by Suliman
// #   filename : captcha.php
// #   Change name session key to captcha_key 5-1-2016
// ##############################################################################||
		//Start the session so we can store what the code actually is.
		session_start();

	// Check for GD library
	if( !function_exists('gd_info') ) {
		throw new Exception('Required GD library is missing');
	}

		//Now lets use md5 to generate a totally random string
		 $md5 = md5(time());

		/*
		We dont need a 32 character long string so we trim it down to 5
		*/
		$string = substr($md5,0,5);
		$string = str_replace('0', 'w', $string);
		$string = str_replace('g', '8', $string);
		/*
		Now for the GD stuff, for ease of use lets create
		 the image from a background image.
		*/

		$captcha = imagecreatefrompng("../look/images/captcha.png");
		/*
		Lets set the colours, the colour $line is used to generate lines.
		 Using a blue misty colours. The colour codes are in RGB
		*/
		$black = imagecolorallocate($captcha, 139, 69, 19);

		/*
		Now for the all important writing of the randomly generated string to the image.
		*/
		imagestring($captcha,5, 20, 0,$string,$black);

		/*
		Encrypt and store the key inside of a session
		*/

		$_SESSION['captcha_key'] = md5($string);

		/*
		Output the image
		*/
		header("Content-type: image/png");
		imagepng($captcha);

?>