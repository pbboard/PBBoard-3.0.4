<?php
(!defined('IN_PowerBB')) ? die() : '';
include('../common.php');
define('CLASS_NAME','PowerBBFixMOD');
class PowerBBFixMOD
{
	function run()
	{
		global $PowerBB;

		if ($PowerBB->_CONF['member_permission'])
		{
			if ($PowerBB->_GET['update_meter'])
			{
                if ($PowerBB->_GET['all_cache'])
				{
					$this->_AllCacheStart();
				}

			}
		}
	}



	function _AllCacheStart()
	{
		global $PowerBB;

		$SecArr 					= 	array();
		$SecArr['get_from']			=	'db';
		$SecArr['proc'] 			= 	array();
		$SecArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');
		$SecArr['order']			=	array();
		$SecArr['order']['field']	=	'sort';
		$SecArr['order']['type']	=	'ASC';

		$SecArr['where']				=	array();
		$SecArr['where'][0]				=	array();
		$SecArr['where'][0]['name']		=	'parent';
		$SecArr['where'][0]['oper']		=	'<>';
		$SecArr['where'][0]['value']	=	'0';

		$SecList = $PowerBB->core->GetList($SecArr,'section');

		$x = 0;
		$y = sizeof($SecList);
		$s = array();

		while ($x < $y)
		{

	        $UpdateSectionCache = $PowerBB->functions->UpdateSectionCache($SecList[$x]['id']);
			$x += 1;

		}


       	  $Update_Cache_groups = $PowerBB->functions->Update_Cache_groups();

                if ($Update_Cache_groups)
				{
	       echo "<META HTTP-EQUIV=\"refresh\" CONTENT=\"0; URL=upgrade?step=upgrade303_update_section_cache\">\n";

				}


	}
}



?>
