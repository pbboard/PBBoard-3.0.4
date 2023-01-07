<?php

(!defined('IN_PowerBB')) ? die() : '';

define('IN_ADMIN',true);

$CALL_SYSTEM			=	array();
$CALL_SYSTEM['PAGES'] 	= 	true;


define('PowerBBPagesMOD',true);
define('CLASS_NAME','PowerBBPagesMOD');

include('../common.php');
class PowerBBPagesMOD extends _functions
{
	function run()
	{
		global $PowerBB;

		if ($PowerBB->_CONF['member_permission'])
		{
			$PowerBB->template->display('header');

			if ($PowerBB->_GET['add'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_AddMain();
				}
				elseif ($PowerBB->_GET['start'])
				{
					$this->_AddStart();
				}
			}
			elseif ($PowerBB->_GET['control'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_ControlMain();
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
				if ($PowerBB->_GET['main'])
				{
					$this->_DelMain();
				}
				elseif ($PowerBB->_GET['start'])
				{
					$this->_DelStart();
				}
			}

			$PowerBB->template->display('footer');
		}
	}

	function _AddMain()
	{
		global $PowerBB;

		$PowerBB->template->display('page_add');
	}

	function _AddStart()
	{
		global $PowerBB;

		if (empty($PowerBB->_POST['name'])
			or ($PowerBB->_POST['order_type'] == 'manual' and empty($PowerBB->_POST['sort'])))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_fill_in_all_the_information']);
		}

		$sort = 0;

		if ($PowerBB->_POST['order_type'] == 'auto')
		{
			$SortArr = array();
			$SortArr['order'] = array();
			$SortArr['order']['field'] = 'sort';
			$SortArr['order']['type'] = 'DESC';

			$SortPage = $PowerBB->pages->GetPageInfo($SortArr);

			// No section
			if (!$SortPage)
			{
				$sort = 1;
			}
			// There is a section
			else
			{
				$sort = $SortPage['sort'] + 1;
			}
		}
		else
		{
			$sort = $PowerBB->_POST['sort'];
		}

		$PageArr 			= 	array();
		$PageArr['field']	=	array();

		$PageArr['field']['title'] 		= 	$PowerBB->_POST['name'];
		$PageArr['field']['link'] 		= 	$PowerBB->_POST['link'];
		$PageArr['field']['sort'] 		= 	$sort;
		$PageArr['field']['html_code'] 	= 	$PowerBB->_POST['text'];

		$insert = $PowerBB->pages->InsertPage($PageArr);

		if ($insert)
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['page_has_been_added_successfully']);
			$PowerBB->functions->redirect('index.php?page=pages&amp;control=1&amp;main=1');
		}
	}

	function _ControlMain()
	{
		global $PowerBB;

		$PageArr 					= 	array();
		$PageArr['order']			=	array();
		$PageArr['order']['field']	=	'sort';
		$PageArr['order']['type']	=	'ASC';
		$PageArr['proc'] 			= 	array();
		$PageArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');

		$PowerBB->_CONF['template']['while']['PagesList'] = $PowerBB->pages->GetPagesList($PageArr);
		$GetForumAdress = $PowerBB->functions->GetForumAdress();
        $GetForumAdress = str_replace($PowerBB->admincpdir."/", '', $GetForumAdress);
		$PowerBB->template->assign('forum_adress',$GetForumAdress);

		$PowerBB->template->display('pages_main');
	}

	function _EditMain()
	{
		global $PowerBB;

		$this->check_by_id($PowerBB->_CONF['template']['Inf']);

		$PowerBB->template->assign('Inf',$PowerBB->_CONF['template']['Inf']);

		$PowerBB->template->display('page_edit');
	}

	function _EditStart()
	{
		global $PowerBB;

		$this->check_by_id($PageInfo);

		if (empty($PowerBB->_POST['name']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_fill_in_all_the_information']);
		}

		$PageArr 				= 	array();
		$PageArr['field']		=	array();

		$PageArr['field']['title'] 		= 	$PowerBB->_POST['name'];
		$PageArr['field']['link'] 		= 	$PowerBB->_POST['link'];
		$PageArr['field']['sort'] 		= 	$PowerBB->_POST['sort'];
		$PageArr['field']['html_code'] 	= 	$PowerBB->_POST['text'];
		$PageArr['where']				=	array('id',$PageInfo['id']);

		$insert = $PowerBB->pages->UpdatePage($PageArr);

		if ($insert)
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['page_has_been_updated_successfully']);
			$PowerBB->functions->redirect('index.php?page=pages&amp;control=1&amp;main=1');
		}
	}

	function _DelMain()
	{
		global $PowerBB;

		$this->check_by_id($PowerBB->_CONF['template']['Inf']);

		$PowerBB->template->display('page_del');
	}

	function _DelStart()
	{
		global $PowerBB;

		$this->check_by_id($PageInfo);

		$DelArr 			= 	array();
		$DelArr['where'] 	= 	array('id',$PowerBB->_GET['id']);

		$del = $PowerBB->pages->DeletePage($DelArr);

		if ($del)
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['page_has_been_deleted_successfully']);
			$PowerBB->functions->redirect('index.php?page=pages&amp;control=1&amp;main=1');
		}
	}
}

class _functions
{
	function check_by_id(&$PageInfo)
	{
		global $PowerBB;

		if (empty($PowerBB->_GET['id']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['The_request_is_not_valid']);
		}

		$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');


	        $CatArr = $PowerBB->DB->sql_query("SELECT  *   FROM " . $PowerBB->table['pages'] . " WHERE id = ".$PowerBB->_GET['id']." ");
		    $PageInfo = $PowerBB->DB->sql_fetch_array($CatArr);


		if ($PageInfo == false)
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['page_requested_does_not_exist']);
		}

		$PowerBB->functions->CleanVariable($PageInfo,'html');
	}
}

?>
