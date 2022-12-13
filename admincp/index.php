<?php
//    error_reporting(E_ERROR | E_PARSE);;
	define('IN_PowerBB',true);
	$page = empty($_GET['page']) ? 'index' : $_GET['page'];
	$page = str_replace( 'note', 'notes', $page );
	$page = str_replace( 'index', 'main', $page );
	$module = ('modulescp/'.$page.'.module.php');
	if(substr_count($module, "\0")
	or substr_count($module, "../")
	or substr_count($module, "..\\")
	or !file_exists($module))
	{
		header("Location: index.php");
		exit;
	}
	require_once($module);
	//////////
	$class_name = CLASS_NAME;
	$class_name = new $class_name;
	$class_name->run();

?>