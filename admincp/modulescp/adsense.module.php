<?php

(!defined('IN_PowerBB')) ? die() : '';

define('IN_ADMIN',true);

define('JAVASCRIPT_PowerCode',true);
define('CLASS_NAME','PowerBBCoreMOD');
include('../common.php');
class PowerBBCoreMOD
{
	function run()
	{
		global $PowerBB;

		if ($PowerBB->_CONF['member_permission'])
		{
			$PowerBB->template->display('header');


			if ($PowerBB->_CONF['rows']['group_info']['admincp_ads'] == '0')
			{
			  $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
			}


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
					$this->_AddAdsenseMain();
				}
                elseif ($PowerBB->_GET['start'])
				{
					$this->_AddAdsenseStart();
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
			elseif ($PowerBB->_GET['limited_sections'])
			{
                if ($PowerBB->_GET['limited'])
				{
					$this->_EditLimitedSections();
				}
			}

		$PowerBB->template->display('footer');
		}

	}


	/**
	 * add Adsense Main
	 */

	function _AddAdsenseMain()
	{
		global $PowerBB;

		$PowerBB->template->display('adsense_add');

    }

	/**
	 * add Adsense Start
	 */
	function _AddAdsenseStart()
	{
		global $PowerBB;

		if (empty($PowerBB->_POST['name'])
			or empty($PowerBB->_POST['text']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_fill_in_all_the_information']);
		}


			$AdsenseArr 			= 	array();
			$AdsenseArr['field']	=	array();

			$AdsenseArr['field']['name']            = 	$PowerBB->_POST['name'];
			$AdsenseArr['field']['adsense'] 		= 	$PowerBB->_POST['text'];
			$AdsenseArr['field']['home'] 		    = 	$PowerBB->_POST['home'];
			$AdsenseArr['field']['forum'] 	    	= 	$PowerBB->_POST['forum'];
			$AdsenseArr['field']['topic'] 		    = 	$PowerBB->_POST['topic'];
			$AdsenseArr['field']['downfoot'] 	    = 	$PowerBB->_POST['downfoot'];
			$AdsenseArr['field']['all_page'] 		= 	$PowerBB->_POST['all_page'];
			$AdsenseArr['field']['between_replys'] 	= 	$PowerBB->_POST['between_replys'];
			$AdsenseArr['field']['down_topic']   	= 	$PowerBB->_POST['down_topic'];
			$AdsenseArr['field']['in_page'] 		= 	$PowerBB->_POST['in_page'];
			$AdsenseArr['field']['mid_topic'] 		= 	$PowerBB->_POST['mid_topic'];
			$AdsenseArr['field']['side'] 		    = 	$PowerBB->_POST['side'];

			$insert = $PowerBB->core->Insert($AdsenseArr,'adsense');

			if ($insert)
			{
	          $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Add_the_declaration_successfully']);
               $PowerBB->functions->redirect('index.php?page=adsense&amp;control=1&amp;main=1');
			}

	}

	function _ControlMain()
	{
		global $PowerBB;

        // show Adsense List
		$AdsenseArr 					= 	array();
		$AdsenseArr['order']			=	array();
		$AdsenseArr['order']['field']	=	'id';
		$AdsenseArr['order']['type']	=	'DESC';
		$AdsenseArr['proc'] 			= 	array();
		$AdsenseArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');

		$PowerBB->_CONF['template']['while']['AdsensesList'] = $PowerBB->core->GetList($AdsenseArr,'adsense');


		$GroupArr 							= 	array();

		$GroupArr['order'] 					= 	array();
		$GroupArr['order']['field'] 		= 	'group_order';
		$GroupArr['order']['type'] 			= 	'ASC';

		$PowerBB->_CONF['template']['while']['AdsenseGroupList'] = $PowerBB->core->GetList($GroupArr,'group');

		$PowerBB->template->display('adsense_main');
	}




	function _EditMain()
	{
		global $PowerBB;

			if (empty($PowerBB->_GET['id']))
			{
				$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['The_declaration_does_not_exist']);
			}

			$AdsenseEditArr				=	array();
		    $AdsenseEditArr['where'] 	= 	array('id',$PowerBB->_GET['id']);

			$AdsenseEdit = $PowerBB->core->GetInfo($AdsenseEditArr,'adsense');

			$PowerBB->template->assign('AdsenseEdit',$AdsenseEdit);


		$PowerBB->template->display('adsense_edit');
	}

	function _EditStart()
	{
		global $PowerBB;

			if (empty($PowerBB->_GET['id']))
			{
				$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['The_declaration_does_not_exist']);
			}

		$AdsenseArr 			= 	array();
		$AdsenseArr['field']	=	array();

		$AdsenseArr['field']['name']            = 	 $PowerBB->_POST['name'];
		$AdsenseArr['field']['adsense'] 		= 	$PowerBB->_POST['text'];
		$AdsenseArr['field']['home'] 		    = 	$PowerBB->_POST['home'];
		$AdsenseArr['field']['forum'] 	    	= 	$PowerBB->_POST['forum'];
		$AdsenseArr['field']['topic'] 		    = 	$PowerBB->_POST['topic'];
		$AdsenseArr['field']['downfoot'] 	    = 	$PowerBB->_POST['downfoot'];
		$AdsenseArr['field']['all_page'] 		= 	$PowerBB->_POST['all_page'];
		$AdsenseArr['field']['between_replys'] 	= 	$PowerBB->_POST['between_replys'];
		$AdsenseArr['field']['down_topic']   	= 	$PowerBB->_POST['down_topic'];
		$AdsenseArr['field']['in_page'] 		= 	$PowerBB->_POST['in_page'];
		$AdsenseArr['field']['mid_topic'] 		= 	$PowerBB->_POST['mid_topic'];
		$AdsenseArr['field']['side'] 		    = 	$PowerBB->_POST['side'];
		$AdsenseArr['where'] 				= 	array('id',$PowerBB->_GET['id']);

		$update = $PowerBB->core->Update($AdsenseArr,'adsense');

		if ($update)
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Announcement_has_been_updated_successfully']);
			$PowerBB->functions->redirect('index.php?page=adsense&amp;control=1&amp;main=1');
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

			$del = $PowerBB->core->Deleted($DelArr,'adsense');

		if ($del)
		{
				$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Ad_Deleted_successfully']);
				$PowerBB->functions->redirect('index.php?page=adsense&amp;control=1&amp;main=1');

		}
	}

	function _EditLimitedSections()
	{
		global $PowerBB;

		if (isset($PowerBB->_POST['adsense_limited_usergroups']))
			{
				if (is_array($PowerBB->_POST['adsense_limited_usergroups']))
				{

					$adsense_limited_usergroups = array();
					foreach ($PowerBB->_POST['adsense_limited_usergroups'] as $l)
					{
					 $adsense_limited_usergroups[] = intval($l);
					}

					if ( count( $adsense_limited_usergroups  ) )
					{
						foreach( $adsense_limited_usergroups  as $f )
						{
							if ( is_array($f) and count($f) )
							{
							$adsense_limited_usergroups  = array_merge( $adsense_limited_usergroups , $f );
							}
						}
					}

			     $member_group_ids = implode( ",", $adsense_limited_usergroups );
			   }
	         }
	         else
	         {
	          $member_group_ids = '';
	         }

	    $update1 = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['adsense_limited_sections'],'var_name'=>'adsense_limited_sections'));
	    $update2 = $PowerBB->info->UpdateInfo(array('value'=>$member_group_ids,'var_name'=>'adsense_limited_usergroups'));

		$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['updated_successfully']);
	    $PowerBB->functions->redirect('index.php?page=adsense&amp;control=1&amp;main=1');

    }

}

?>
