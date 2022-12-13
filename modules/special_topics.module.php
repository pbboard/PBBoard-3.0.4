<?php
(!defined('IN_PowerBB')) ? die() : '';

define('CLASS_NAME','PowerBBCoreMOD');

include('common.php');
class PowerBBCoreMOD
{
	function run()
	{
		global $PowerBB;

        $PowerBB->template->assign('special_subject_page','primary_tabon');

		if ($PowerBB->_GET['index'])
		{  	        $PowerBB->functions->ShowHeader();
			$this->_SpecialSubject();
		}
		else
		{
			header("Location: index.php");
			exit;
		}

	  $PowerBB->functions->GetFooter();

	}

	function _SpecialSubject()
	{
		global $PowerBB;

		$SpecialArr 							= 	array();
		$SpecialArr['proc'] 					= 	array();
		$SpecialArr['proc']['*'] 				= 	array('method'=>'clean','param'=>'html');

		$SpecialArr['where']					=	array();
		$SpecialArr['where'][0]				=	array();
		$SpecialArr['where'][0]['name']		=	'special';
		$SpecialArr['where'][0]['oper']		=	'=';
		$SpecialArr['where'][0]['value']		=	'1';

		$SpecialArr['order']					=	array();
		$SpecialArr['order']['field']			=	'id';
		$SpecialArr['order']['type']			=	'DESC';

		$SpecialArr['proc'] 						= 	array();
		// Ok Mr.XSS go to hell !
		$SpecialArr['proc']['*'] 					= 	array('method'=>'clean','param'=>'html');
		$SpecialArr['proc']['native_write_time'] 	= 	array('method'=>'date','store'=>'write_date','type'=>$PowerBB->_CONF['info_row']['timesystem']);
		$SpecialArr['proc']['write_time'] 			= 	array('method'=>'date','store'=>'reply_date','type'=>$PowerBB->_CONF['info_row']['timesystem']);


		$PowerBB->_CONF['template']['while']['Special_subjectList'] = $PowerBB->core->GetList($SpecialArr,'subject');

         $subject_special_nm = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['subject'] . " WHERE special='1'"));
		 $PowerBB->template->assign('subject_special_nm',$subject_special_nm);

		$PowerBB->template->display('special_subject');
	}


}

?>
