<?php

(!defined('IN_PowerBB')) ? die() : '';

define('IN_ADMIN',true);

$CALL_SYSTEM			        =	array();
$CALL_SYSTEM['CUSTOM_BBCODE']           =   true;
$CALL_SYSTEM['CORE']           =   true;

define('JAVASCRIPT_PowerCode',true);

define('CLASS_NAME','PowerBBCustom_bbcodeMOD');

include('../common.php');
class PowerBBCustom_bbcodeMOD
{
	function run()
	{
		global $PowerBB;
		if ($PowerBB->_CONF['member_permission'])
		{
      		$PowerBB->template->display('header');
			if ($PowerBB->_GET['control'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_ControlMain();
				}
			}
			elseif ($PowerBB->_GET['add'])
			{
              	if ($PowerBB->_GET['main'])
				{
					$this->_AddCustom_bbcodeMain();
				}
                elseif ($PowerBB->_GET['start'])
				{
					$this->_AddCustom_bbcodeStart();
				}
			}
			elseif ($PowerBB->_GET['edit'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_EditMain();
				}
				elseif ($PowerBB->_GET['start'])
				{
					$this->_EditStart();
				}
			}
			elseif ($PowerBB->_GET['del'])
			{
                if ($PowerBB->_GET['start'])
				{
					$this->_DelStart();
				}
			}

		$PowerBB->template->display('footer');
		}
       }


	/**
	 * add Custom_bbcode Main
	 */

	function _AddCustom_bbcodeMain()
	{
		global $PowerBB;

		$PowerBB->template->display('custom_bbcode_add');

    }

	/**
	 * add Custom_bbcode Start
	 */
	function _AddCustom_bbcodeStart()
	{
		global $PowerBB;

		if (empty($PowerBB->_POST['bbcode_title'])
			or empty($PowerBB->_POST['bbcode_tag']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_fill_in_all_the_information']);
		}


			$Custom_bbcodeArr 			= 	array();
			$Custom_bbcodeArr['field']	=	array();

			$Custom_bbcodeArr['field']['bbcode_title']            = 	 $PowerBB->_POST['bbcode_title'];
			$Custom_bbcodeArr['field']['bbcode_desc'] 		= 	$PowerBB->_POST['bbcode_desc'];
			$Custom_bbcodeArr['field']['bbcode_example'] 		    = 	$PowerBB->_POST['bbcode_example'];
			$Custom_bbcodeArr['field']['bbcode_tag'] 	    	= 	$PowerBB->_POST['bbcode_tag'];
			$Custom_bbcodeArr['field']['bbcode_useoption'] 		    = 	$PowerBB->_POST['bbcode_useoption1'];
			$Custom_bbcodeArr['field']['bbcode_switch'] 		    = 	$PowerBB->_POST['bbcode_switch'];
			$Custom_bbcodeArr['field']['bbcode_replace'] 		    = 	$PowerBB->_POST['bbcode_replace'];

		    $insert = $PowerBB->core->insert($Custom_bbcodeArr,'custom_bbcode');

			if ($insert)
			{
				$cache = $PowerBB->custom_bbcode->UpdateCustom_bbcodeCache(null);
				$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Add_the_bbcode_successfully']);
				$PowerBB->functions->redirect('index.php?page=custom_bbcode&amp;control=1&amp;main=1');
			}

	}

	function _ControlMain()
	{
		global $PowerBB;

        // show Custom_bbcode List
		$Custom_bbcodeArr 					= 	array();
		$Custom_bbcodeArr['order']			=	array();
		$Custom_bbcodeArr['order']['field']	=	'id';
		$Custom_bbcodeArr['order']['type']	=	'DESC';
		$Custom_bbcodeArr['proc'] 			= 	array();
		$Custom_bbcodeArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');

		$PowerBB->_CONF['template']['while']['Custom_bbcodesList'] = $PowerBB->core->GetList($Custom_bbcodeArr,'custom_bbcode');

		$PowerBB->template->display('custom_bbcode_main');
	}




	function _EditMain()
	{
		global $PowerBB;

			if (empty($PowerBB->_GET['id']))
			{
				$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['The_bbcode_does_not_exist']);
			}

			$Custom_bbcodeEditArr				=	array();
		    $Custom_bbcodeEditArr['where'] 	= 	array('id',$PowerBB->_GET['id']);

			$Custom_bbcodeEdit = $PowerBB->core->GetInfo($Custom_bbcodeEditArr,'custom_bbcode');

			$PowerBB->template->assign('Custom_bbcodeEdit',$Custom_bbcodeEdit);


		$PowerBB->template->display('custom_bbcode_edit');
	}

	function _EditStart()
	{
		global $PowerBB;

			if (empty($PowerBB->_GET['id']))
			{
				$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['The_bbcode_does_not_exist']);
			}

			$Custom_bbcodeArr 			= 	array();
			$Custom_bbcodeArr['field']	=	array();

			$Custom_bbcodeArr['field']['bbcode_title']            = 	 $PowerBB->_POST['bbcode_title'];
			$Custom_bbcodeArr['field']['bbcode_desc'] 		= 	$PowerBB->_POST['bbcode_desc'];
			$Custom_bbcodeArr['field']['bbcode_example'] 		    = 	$PowerBB->_POST['bbcode_example'];
			$Custom_bbcodeArr['field']['bbcode_tag'] 	    	= 	$PowerBB->_POST['bbcode_tag'];
			$Custom_bbcodeArr['field']['bbcode_useoption'] 		    = 	$PowerBB->_POST['bbcode_useoption1'];
			$Custom_bbcodeArr['field']['bbcode_switch'] 		    = 	$PowerBB->_POST['bbcode_switch'];
			$Custom_bbcodeArr['field']['bbcode_replace'] 		    = 	$PowerBB->_POST['bbcode_replace'];
			$Custom_bbcodeArr['where'] 				= 	array('id',$PowerBB->_GET['id']);

		  $update = $PowerBB->core->Update($Custom_bbcodeArr,'custom_bbcode');


		if ($update)
		{
				$cache = $PowerBB->custom_bbcode->UpdateCustom_bbcodeCache(null);

			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['bbcode_has_been_updated_successfully']);
			$PowerBB->functions->redirect('index.php?page=custom_bbcode&amp;control=1&amp;main=1');
		}
	}

	function _DelStart()
	{
		global $PowerBB;

			if (empty($PowerBB->_GET['id']))
			{
				$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['The_declaration_does_not_exist']);
			}

			$DelArr 			= 	array();
			$DelArr['where'] 	= 	array('id',$PowerBB->_GET['id']);

			$del = $PowerBB->core->Deleted($DelArr,'custom_bbcode');


		if ($del)
		{				$cache = $PowerBB->custom_bbcode->UpdateCustom_bbcodeCache(null);
				$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['bbcode_Deleted_successfully']);
				$PowerBB->functions->redirect('index.php?page=custom_bbcode&amp;control=1&amp;main=1');

		}
	}

}

?>
