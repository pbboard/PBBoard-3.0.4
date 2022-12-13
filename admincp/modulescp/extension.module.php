<?php

(!defined('IN_PowerBB')) ? die() : '';

define('IN_ADMIN',true);

$CALL_SYSTEM						=	array();
$CALL_SYSTEM['FILESEXTENSION'] 		= 	true;
$CALL_SYSTEM['ATTACH'] 				= 	true;



define('CLASS_NAME','PowerBBExtensionMOD');

include('../common.php');
class PowerBBExtensionMOD extends _functions
{
	function run()
	{
		global $PowerBB;

		if ($PowerBB->_CONF['member_permission'])
		{
			$PowerBB->template->display('header');


			if ($PowerBB->_CONF['rows']['group_info']['admincp_attach'] == '0')
			{
			  $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
			}

			if ($PowerBB->_GET['add'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_AddExtensionMain();
				}
				elseif ($PowerBB->_GET['start'])
				{
					$this->_AddExtensionStart();
				}
			}
			elseif ($PowerBB->_GET['control'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_ControlExtensionMain();
				}
			}
			elseif ($PowerBB->_GET['edit'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_EditExtensionMain();
				}
				elseif ($PowerBB->_GET['start'])
				{
					$this->_EditExtensionStart();
				}
			}
			elseif ($PowerBB->_GET['del'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_DelExtensionMain();
				}
				elseif ($PowerBB->_GET['start'])
				{
					$this->_DelExtensionStart();
				}
			}
			elseif ($PowerBB->_GET['search'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_SearchAttachMain();
				}
				elseif ($PowerBB->_GET['start'])
				{
					$this->_SearchAttachStart();
				}
		   }

			$PowerBB->template->display('footer');
		}
	}

	function _AddExtensionMain()
	{
		global $PowerBB;

		$PowerBB->template->display('extension_add');
	}

	function _AddExtensionStart()
	{
		global $PowerBB;

		if (empty($PowerBB->_POST['extension'])
			or empty($PowerBB->_POST['max_size']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_fill_in_all_the_information']);
		}

		if (!strstr($PowerBB->_POST['extension'],'.'))
		{
			$PowerBB->_POST['extension'] = '.' . $PowerBB->_POST['extension'];
		}

		 $PowerBB->functions->GetFileExtension($PowerBB->_POST['extension']);

		$PowerBB->_POST['extension'] = strtolower($PowerBB->_POST['extension']);

		$ExArr 					= 	array();
		$ExArr['field']			=	array();

		$ExArr['field']['Ex'] 			= 	$PowerBB->_POST['extension'];
		$ExArr['field']['max_size'] 	= 	$PowerBB->_POST['max_size'];
		$ExArr['field']['mime_type'] 	= 	$PowerBB->_POST['mime_type'];

		$insert = $PowerBB->extension->InsertExtension($ExArr);

		if ($insert)
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Extension_has_been_added_successfully']);
			$PowerBB->functions->redirect('index.php?page=extension&amp;control=1&amp;main=1');
		}
	}

	function _ControlExtensionMain()
	{
		global $PowerBB;

		$ExArr 						= 	array();
		$ExArr['order']				=	array();
		$ExArr['order']['field']	=	'id';
		$ExArr['order']['type']		=	'DESC';
		$ExArr['proc'] 				= 	array();
		$ExArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');

		$PowerBB->_CONF['template']['while']['ExList'] = $PowerBB->core->GetList($ExArr,'ex');

		$PowerBB->template->display('extenstions_main');
	}

	function _EditExtensionMain()
	{
		global $PowerBB;

		$PowerBB->_CONF['template']['Inf'] = false;

		$this->check_by_id($PowerBB->_CONF['template']['Inf']);

		$PowerBB->template->display('extenstion_edit');
	}

	function _EditExtensionStart()
	{
		global $PowerBB;

		$PowerBB->_CONF['template']['Inf'] = false;

		$this->check_by_id($PowerBB->_CONF['template']['Inf']);


		if (empty($PowerBB->_POST['extension'])
			or empty($PowerBB->_POST['max_size']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_fill_in_all_the_information']);
		}

		if (!strstr($PowerBB->_POST['extension'],'.'))
		{
			$PowerBB->_POST['extension'] = '.' . $PowerBB->_POST['extension'];
		}

		 $PowerBB->functions->GetFileExtension($PowerBB->_POST['extension']);

		$ExArr 				= 	array();
		$ExArr['field']		=	array();

		$ExArr['field']['Ex'] 			= 	$PowerBB->_POST['extension'];
		$ExArr['field']['max_size'] 	= 	$PowerBB->_POST['max_size'];
		$ExArr['field']['mime_type'] 	= 	$PowerBB->_POST['mime_type'];
		$ExArr['where']					=	array('id',$PowerBB->_CONF['template']['Inf']['id']);

		$update = $PowerBB->extension->UpdateExtension($ExArr);

		if ($update)
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Extension_has_been_updated_successfully']);
			$PowerBB->functions->redirect('index.php?page=extension&amp;control=1&amp;main=1');
		}
	}

	function _DelExtensionMain()
	{
		global $PowerBB;

		$PowerBB->_CONF['template']['Inf'] = false;

		$this->check_by_id($PowerBB->_CONF['template']['Inf']);

		$PowerBB->template->display('extenstion_del');
	}

	function _DelExtensionStart()
	{
		global $PowerBB;

		$PowerBB->_CONF['template']['Inf'] = false;

		$this->check_by_id($PowerBB->_CONF['template']['Inf']);

		$DelArr 			= 	array();
		$DelArr['where'] 	= 	array('id',$PowerBB->_CONF['template']['Inf']['id']);

		$del = $PowerBB->extension->DeleteExtension($DelArr);

		if ($del)
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Extension_has_been_deleted_successfully']);
			$PowerBB->functions->redirect('index.php?page=extension&amp;control=1&amp;main=1');
		}
	}

	function _SearchAttachMain()
	{
		global $PowerBB;

		$PowerBB->template->display('extension_search_main');
	}


	function _SearchAttachStart()
	{
		global $PowerBB;

		if ($PowerBB->_POST['keyword'] == '')
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_enter_your_search_criteria']);
		}

		if ($PowerBB->_POST['search_by'] == 'filename')
		{
		$GetArr							=	array();
		$GetArr['where'] 				= 	array();
		$GetArr['where'][0]				=	array();
		$GetArr['where'][0]['name']		=	'filename';
		$GetArr['where'][0]['oper']		=	'LIKE';
		$GetArr['where'][0]['value']	=	'%' . $PowerBB->_POST['keyword'] . '%';

		$PowerBB->_CONF['template']['while']['Inf'] = $PowerBB->attach->GetAttachList($GetArr);
		 }
		elseif ($PowerBB->_POST['search_by'] == 'filesize')
		{
		$PowerBB->_POST['keyword'] = $PowerBB->functions->CleanVariable($PowerBB->_POST['keyword'],'intval');

		$GetArr							=	array();
		$GetArr['where'] 				= 	array();
		$GetArr['where'][0]				=	array();
		$GetArr['where'][0]['name']		=	'filesize';
		$GetArr['where'][0]['oper']		=	'=';
		$GetArr['where'][0]['value']	=	$PowerBB->_POST['keyword'];

		$PowerBB->_CONF['template']['while']['Inf'] = $PowerBB->attach->GetAttachList($GetArr);
		}
		elseif ($PowerBB->_POST['search_by'] == 'visitor')
		{
        $PowerBB->_POST['keyword'] = $PowerBB->functions->CleanVariable($PowerBB->_POST['keyword'],'intval');
		$GetArr							=	array();
		$GetArr['where'] 				= 	array();
		$GetArr['where'][0]				=	array();
		$GetArr['where'][0]['name']		=	'visitor';
		$GetArr['where'][0]['oper']		=	'=';
		$GetArr['where'][0]['value']	=	$PowerBB->_POST['keyword'];

		$PowerBB->_CONF['template']['while']['Inf'] = $PowerBB->attach->GetAttachList($GetArr);
		}
		else
		{
			$field = 'filename';
		}

		if ($PowerBB->_CONF['template']['while']['Inf'] == false)
		{
			$PowerBB->functions->error('لا يوجد نتائج');
		}

		$PowerBB->functions->CleanVariable($PowerBB->_CONF['template']['while']['Inf'],'html');

		$PowerBB->template->display('extension_search_result');
	}
}

class _functions
{
	function check_by_id(&$Inf)
	{
		global $PowerBB;

		if (empty($PowerBB->_GET['id']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['The_request_is_not_valid']);
		}

		$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');

		$InfArr 			= 	array();
		$InfArr['where'] 	= 	array('id',$PowerBB->_GET['id']);

		$Inf = $PowerBB->extension->GetExtensionInfo($InfArr);

        $CatArr = $PowerBB->DB->sql_query("SELECT  *   FROM " . $PowerBB->table['extension'] . " WHERE id = ".$PowerBB->_GET['id']." ");
	    $Inf = $PowerBB->DB->sql_fetch_array($CatArr);

		if ($Inf == false)
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['The_Extension_does_not_exist']);
		}

		$PowerBB->functions->CleanVariable($Inf,'html');
	}
}

?>
