<?php

class PowerBBMisc
{
	var $PowerBB;

	function __construct($PowerBB)
	{
		$this->Engine = $PowerBB;
	}

	function GetForumAge($param)
	{
     	$age = time() - $param['date'];
     	$age = ceil($age/(60*60*24));

     	return $age;
	}
}

?>
