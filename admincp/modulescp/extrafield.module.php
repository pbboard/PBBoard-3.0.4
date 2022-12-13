<?php

(!defined('IN_PowerBB')) ? die() : '';

define('IN_ADMIN',true);

$CALL_SYSTEM			=	array();
$CALL_SYSTEM['EXTRAFIELD'] 	= 	true;
$CALL_SYSTEM['MEMBER']  =   true;



define('CLASS_NAME','PowerBBExtraFieldModule');

include('../common.php');
class PowerBBExtraFieldModule
{
	function run()
	{
		global $PowerBB;
		if ($PowerBB->_CONF['member_permission'])
		{
		$PowerBB->template->display('header');


			if ($PowerBB->_CONF['rows']['group_info']['admincp_extrafield'] == '0')
			{
			  $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
			}

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
		$PowerBB->template->display('extrafield_add');
	}

	function _AddStart()
	{
		global $PowerBB;

		if (empty($PowerBB->_POST['name']))
		{
			$PowerBB->_CONF['template']['errors'] = $PowerBB->_CONF['template']['_CONF']['lang']['Please_enter_the_name_of_the_field'];
			$PowerBB->template->display('extrafield_add');
			return;
		}

		if(0!=$PowerBB->extrafield->GetFieldsNumber( array('where'=>array('name',$PowerBB->_POST['name'])) ) ){
      $PowerBB->_CONF['template']['errors'] = $PowerBB->_CONF['template']['_CONF']['lang']['The_field_name_that_you_entered_already_exists'];
      $PowerBB->template->display('extrafield_add');
      return;
		}
  //getting new lines in array
  $options=explode("\n",$PowerBB->_POST['options']);
  foreach($options AS $key=>$option){
    //cleaning new lines from dirt
    $options[$key]=trim($option);
  }
  //here no input at all but it's expldoe making empty field
  if(count($options)==1 AND $options[0]==''){
    $options=array();
  }

	if( $PowerBB->_POST['type']=='select_option' AND  0==count($options) ){
      $PowerBB->_CONF['template']['errors'] = $PowerBB->_CONF['template']['_CONF']['lang']['you_must_insert_the_option_of_at_least_one'];
      $PowerBB->template->display('extrafield_add');
      return;
    }
	if( $PowerBB->_POST['type']=='select_multiple' AND  0==count($options) ){
      $PowerBB->_CONF['template']['errors'] = $PowerBB->_CONF['template']['_CONF']['lang']['you_must_insert_the_option_of_at_least_one'];
      $PowerBB->template->display('extrafield_add');
      return;
    }
		$FieldsArr 			= 	array();
		$FieldsArr['field']	=	array();
		$FieldsArr['field']['name'] 	= 	$PowerBB->_POST['name'];
		$FieldsArr['field']['show_in_forum'] 		= 	$PowerBB->_POST['show_in_forum'];
		$FieldsArr['field']['required'] 	= 	$PowerBB->_POST['required'];
		$FieldsArr['field']['type'] 		= 	$PowerBB->_POST['type'];
		$FieldsArr['field']['options']    =   json_encode($options);

		$insert = $PowerBB->extrafield->InsertField($FieldsArr);

		if ($insert)
		{
				$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['field_has_been_added_successfully']);
				$PowerBB->functions->redirect('index.php?page=extrafield&amp;control=1&amp;main=1');
		}
	}

	function _ControlMain()
	{
		global $PowerBB;

		$FieldArr 					= 	array();
		$FieldArr['order']			=	array();
		$FieldArr['order']['field']	=	'id';
		$FieldArr['order']['type']	=	'DESC';

		$PowerBB->_CONF['template']['while']['FieldsList'] = $PowerBB->extrafield->GetFieldsList($FieldArr);
		$PowerBB->template->display('extrafield_main');
	}

	function _EditMain()
	{
		global $PowerBB;

    $PowerBB->_CONF['template']['field']=$PowerBB->extrafield->GetFieldInfo( array('where'=> array('id', intval($PowerBB->_GET['id'])) ) );
    $PowerBB->_CONF['template']['field']['options']=implode("\n",json_decode($PowerBB->_CONF['template']['field']['options'], true) );
		$PowerBB->template->display('extrafield_edit');
	}

	function _EditStart()
	{
    global $PowerBB;

    if (empty($PowerBB->_POST['name']))
    {
      $PowerBB->_CONF['template']['errors'] = $PowerBB->_CONF['template']['_CONF']['lang']['Please_enter_the_name_of_the_field'];
      $PowerBB->_CONF['template']['field']=$PowerBB->extrafield->GetFieldInfo( array('where'=> array('id', intval($PowerBB->_GET['id'])) ) );
      $PowerBB->_CONF['template']['field']['options']=implode("\n",json_decode($PowerBB->_CONF['template']['field']['options'], true) );
      $PowerBB->template->display('extrafield_edit');
      return;
    }

    $Others['where'][0]['name'] = 'id';
    $Others['where'][0]['oper'] = '!=';
    $Others['where'][0]['value'] = $PowerBB->_GET['id'];
    $Others['where'][1]['con'] = 'AND';
    $Others['where'][1]['name'] = 'name';
    $Others['where'][1]['oper'] = '=';
    $Others['where'][1]['value'] = $PowerBB->_POST['name'];

    if(0!=$PowerBB->extrafield->GetFieldsNumber( $Others ) ){
      $PowerBB->_CONF['template']['errors'] = $PowerBB->_CONF['template']['_CONF']['lang']['The_field_name_that_you_entered_already_exists'];
      $PowerBB->_CONF['template']['field']=$PowerBB->extrafield->GetFieldInfo( array('where'=> array('id', intval($PowerBB->_GET['id'])) ) );
      $PowerBB->_CONF['template']['field']['options']=implode("\n",json_decode($PowerBB->_CONF['template']['field']['options'], true) );
      $PowerBB->template->display('extrafield_edit');
      return;
    }
  //getting new lines in array
  $options=explode("\n",$PowerBB->_POST['options']);
  foreach($options AS $key=>$option){
    //cleaning new lines from dirt
    $options[$key]=trim($option);
  }
  //here no input at all but it's expldoe making empty field
  if(count($options)==1 AND $options[0]==''){
    $options=array();
  }

  if( $PowerBB->_POST['type']=='select_option' AND  0==count($options) ){
      $PowerBB->_CONF['template']['errors'] = $PowerBB->_CONF['template']['_CONF']['lang']['you_must_insert_the_option_of_at_least_one'];
      $PowerBB->_CONF['template']['field']=$PowerBB->extrafield->GetFieldInfo( array('where'=> array('id', intval($PowerBB->_GET['id'])) ) );
      $PowerBB->_CONF['template']['field']['options']=implode("\n",json_decode($PowerBB->_CONF['template']['field']['options'], true) );
      $PowerBB->template->display('extrafield_edit');
      return;
    }

    $FieldsArr      =   array();
    $FieldsArr['field'] = array();
    $FieldsArr['field']['name']   =   $PowerBB->_POST['name'];
    $FieldsArr['field']['show_in_forum']    =   $PowerBB->_POST['show_in_forum'];
    $FieldsArr['field']['required']   =   $PowerBB->_POST['required'];
    $FieldsArr['field']['type']     =   $PowerBB->_POST['type'];
    $FieldsArr['field']['options']    =   json_encode($options);
		$FieldsArr['where'] 				= 	array('id',$PowerBB->_GET['id']);

		$update = $PowerBB->extrafield->UpdateField($FieldsArr);

		if ($update)
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['field_has_been_updated_successfully']);
			$PowerBB->functions->redirect('index.php?page=extrafield&amp;control=1&amp;main=1');
		}
	}

	function _DelMain()
	{
		global $PowerBB;

    $PowerBB->_CONF['template']['field']=$PowerBB->extrafield->GetFieldInfo( array('where'=> array('id', intval($PowerBB->_GET['id'])) ) );
		$PowerBB->template->display('extrafield_del');
	}

	function _DelStart()
	{
		global $PowerBB;


		$del = $PowerBB->extrafield->DeleteField(array('id'	=>	$PowerBB->_GET['id']));

		if ($del)
		{
				$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['field_has_been_deleted_successfully']);
				$PowerBB->functions->redirect('index.php?page=extrafield&amp;control=1&amp;main=1');
		}
	}
}

?>
