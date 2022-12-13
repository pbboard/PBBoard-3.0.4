<?php
(!defined('IN_PowerBB')) ? die() : '';

define('CLASS_NAME','PowerBBFixMOD');
include('common.php');
class PowerBBFixMOD
{
var $group;
	function run()
	{
		global $PowerBB;


	    if($PowerBB->_GET['USGCache'] == '1')
	    {

	        $query = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['section'] . " ");
	        while ($r = $PowerBB->DB->sql_fetch_array($query))
		    {
				$CacheArr 			= 	array();
				$CacheArr['id'] 	= 	$r['id'];

				$cache = $PowerBB->group->UpdateSectionGroupCache($CacheArr);
				if($cache)
				{
                 echo $r['id'];
				}
			 }
			 /*
	       if($cache)
	       {
			header("Location: index.php");
			exit;
		   }
		   */
		}

	}


}

?>
