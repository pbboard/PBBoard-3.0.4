<?php

// ##############################################################################||
// #
// #   PowerBB Version 2.0.0
// #   https://pbboard.info
// #   Copyright (c) 2009 by Abu.Rakan
// #
// #   filename : special_topics.module.php
// #
// ##############################################################################||

(!defined('IN_PowerBB')) ? die() : '';

$CALL_SYSTEM				=	array();
$CALL_SYSTEM['SECTION'] 	= 	true;
$CALL_SYSTEM['REPLY'] 		= 	true;
$CALL_SYSTEM['SUBJECT'] 	= 	true;




define('CLASS_NAME','PowerBBRevieweSubjectMOD');

include('common.php');
class PowerBBRevieweSubjectMOD
{
	function run()
	{
		global $PowerBB;

			header("Location: index.php");
			exit;
      	//////////
		// Show the header

  	  $PowerBB->functions->ShowHeader();

		if ($PowerBB->_CONF['member_permission'])
		{
				if ($PowerBB->_GET['index'])
				{
					$this->_RevieweSubject();
				}

         }
			$PowerBB->functions->GetFooter();

	}

	function _RevieweSubject()
	{
		global $PowerBB;


		$RevieweArr 							= 	array();
		$RevieweArr['proc'] 					= 	array();
		$RevieweArr['proc']['*'] 				= 	array('method'=>'clean','param'=>'html');

		$RevieweArr['where']					=	array();
		$RevieweArr['where'][0]				=	array();
		$RevieweArr['where'][0]['name']		=	'review_subject';
		$RevieweArr['where'][0]['oper']		=	'=';
		$RevieweArr['where'][0]['value']		=	'1';

		$RevieweArr['order']					=	array();
		$RevieweArr['order']['field']			=	'id';
		$RevieweArr['order']['type']			=	'DESC';

		$PowerBB->_CONF['template']['while']['Review_subjectList'] = $PowerBB->subject->GetSubjectList($RevieweArr);



		$PowerBB->template->display('review_subject');
	}


}

?>
