<?php
   // error_reporting(E_ERROR | E_PARSE);;
	define('IN_PowerBB',true);
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
	$page = empty($_GET['page']) ? 'index' : $_GET['page'];
	$page = str_replace( 'member_list', 'memberlist', $page );
	$page = str_replace( 'index', 'main', $page );
	$page = str_replace( 'special_subject', 'special_topics', $page );
	$module = ('modules/'.$page.'.module.php');
	if(!@file_exists($module))
	{
		@header("Location: index.php");
		exit;
	}
	require_once($module);
	//////////
	$class_name = CLASS_NAME;
	$class_name = new $class_name;
	$class_name->run();
?>