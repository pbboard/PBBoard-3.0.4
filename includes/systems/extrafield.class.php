<?php

class PowerBBExtraField
{
	var $PowerBB;

	function __construct($PowerBB)
	{
		$this->Engine = $PowerBB;
	}

 	function InsertField($param)
 	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

 		//get the last field id
		if( $this->Engine->records->Insert($this->Engine->table['extrafield'],$param['field']) ){
  		$inserted_field_id = $this->Engine->DB->sql_insert_id();
      return $this->Engine->member->InsertMemberField('extrafield_'.$inserted_field_id);
		}

		//here nothing is good
		return false;
 	}


 	function UpdateField($param)
 	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$query = $this->Engine->records->Update($this->Engine->table['extrafield'],$param['field'],$param['where']);

		return ($query) ? true : false;
 	}

	function DeleteField($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['table'] = $this->Engine->table['extrafield'];
		$param['where']       =   array();
		$param['where'][0]      =   array();
		$param['where'][0]['name']  = 'id';
		$param['where'][0]['oper']  = '=';
		$param['where'][0]['value'] = $param['id'];
		if($del = $this->Engine->records->Delete($param) )
		$this->Engine->member->DeleteMemberField('extrafield_'.$param['id']);
		return ($del) ? true : false;
	}

	function GetFieldInfo($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

 	 	$param['select'] 	= 	'*';
 	 	$param['from'] 		= 	$this->Engine->table['extrafield'];

 	 	$rows = $this->Engine->records->GetInfo($param);

 	 	return $rows;
	}

	function GetFieldsNumber($param)
	{
		if (!isset($param))
		{
			$param 	= array();
		}

		$param['select'] 	= 	'*';
		$param['from'] 		= 	$this->Engine->table['extrafield'];

		$num = $this->Engine->records->GetNumber($param);

		return $num;
	}

 	function GetFieldsList($param)
 	{
  		if (!isset($param)
  			or !is_array($param))
 		{
 			$param = array();
 		}

 		$param['select'] 	= 	'*';
 		$param['from'] 		= 	$this->Engine->table['extrafield'];

 	 	$rows = $this->Engine->records->GetList($param);
 		return $rows;
 	}

 	function getEmptyLoginFields($forum_only=false){
	$cond=( $forum_only==true ) ? array('where'=>array('show_in_forum','yes')) : array();
	$extrafields=self::GetFieldsList($cond);
	foreach($extrafields AS $key=>$field){
	if($field['type']=='select_option'){
	$extrafields[$key]['options']=json_decode($field['options'], true);
	}
	elseif($field['type']=='select_multiple'){
	$extrafields[$key]['options']=json_decode($field['options'], true);
	}
	$extrafields[$key]['name_tag']='extrafield_'.$field['id'];
	$extrafields[$key]['type_html']=self::_translateToHtml( $extrafields[$key] );
	}
    return $extrafields;
 	}

 	function getEmptyProfileFields($forum_only=false){
	$cond=( $forum_only==true ) ? array('where'=>array('show_in_forum','yes')) : array();
	$extrafields=self::GetFieldsList(true);
	foreach($extrafields AS $key=>$field){
	if($field['type']=='select_option'){
	$extrafields[$key]['options']=json_decode($field['options'], true);
	}
	elseif($field['type']=='select_multiple'){
	$extrafields[$key]['options']=json_decode($field['options'], true);
	}
	$extrafields[$key]['name_tag']='extrafield_'.$field['id'];
	$extrafields[$key]['type_html']=self::_translateToHtml( $extrafields[$key] );
	}
    return $extrafields;
 	}

  function getUserFields($admin=false){
    $extrafields=self::GetFieldsList(array());
    if($admin==false){
      $memberInfo=$this->Engine->_CONF['template']['_CONF']['rows']['member_row'];
    }else{
      $memberInfo=$this->Engine->_CONF['template']['Inf'];
    }


    $Inf;

    foreach($extrafields AS $key=>$field){
      if($field['type']=='select_option'){
        $extrafields[$key]['options']=json_decode($field['options'], true);
      }
      elseif($field['type']=='select_multiple'){
        $extrafields[$key]['options']=json_decode($field['options'], true);
      }
      $extrafields[$key]['name_tag']='extrafield_'.$field['id'];
      $extrafields[$key]['type_html']=self::_translateToHtml($extrafields[$key],true,$memberInfo[ $extrafields[$key]['name_tag'] ]);
    }

    return $extrafields;
  }

  function getPublicInfo(&$info){
    $extrafields=self::GetFieldsList(array());
    if($admin==false){
      $memberInfo=$this->Engine->_CONF['template']['_CONF']['rows']['member_row'];
    }else{
      $memberInfo=$this->Engine->_CONF['template']['Inf'];
    }


    $Inf;

    foreach($extrafields AS $key=>$field){
      if($field['type']=='select_option'){
        $extrafields[$key]['options']=json_decode($field['options'], true);
      }
      elseif($field['type']=='select_multiple'){
        $extrafields[$key]['options']=json_decode($field['options'], true);
      }
      $extrafields[$key]['name_tag']='extrafield_'.$field['id'];
      $extrafields[$key]['type_html']=self::_translateToHtml($extrafields[$key],true,$memberInfo[ $extrafields[$key]['name_tag'] ]);
    }

    return array();
  }


 	function _translateToHtml($field,$select=false,$value=''){
 	if($field['options'])
 	{
	    switch($field['type']){
	      case 'input_text':
	          if($select==true)
	            $return='<input type="text" value="'.$value.'" name="'.$field['name_tag'].'" id="'.$field['name_tag'].'_id" />';
	          else
	            $return='<input type="text" name="'.$field['name_tag'].'" id="'.$field['name_tag'].'_id" value="" />';
	          return $return;
	        break;
	      case 'box_text':
	          if($select==true)
	            $return='<textarea rows="5" style="width: 50%;" name="'.$field['name_tag'].'" id="'.$field['name_tag'].'_id" />'.$value.'</textarea>';
	          else
	            $return='<textarea rows="5" style="width: 50%;" name="'.$field['name_tag'].'" id="'.$field['name_tag'].'_id" /> </textarea>';
	          return $return;
	        break;
	      case 'select_option':
	          $return='<select name="'.$field['name_tag'].'" id="'.$field['name_tag'].'_id">';
	            foreach($field['options'] AS $option){
	              if($select==true){
	                if($value==$option)
	                  $return.='<option selected="selected">'.$option.'</option>';
	                else
	                  $return.='<option>'.$option.'</option>';
	              }else{
	                $return.='<option>'.$option.'</option>';
	              }
	            }
	          $return.='</select>';
	          return $return;
	        break;
	      case 'select_multiple':
	          $return='<select name="'.$field['name_tag'].'[]"  size="5" multiple="multiple" id="'.$field['name_tag'].'_id">';
	            foreach($field['options'] AS $option){

	              if($select==true){
	                if (in_array($option, explode(',', $value)))
	                  $return.='<option selected="selected">'.$option.'</option>';
	                else
	                  $return.='<option>'.$option.'</option>';
	              }else{
	                $return.='<option>'.$option.'</option>';
	              }
	            }
	          $return.='</select>';
	          return $return;
	        break;
	      default:
	          trigger_error('extrafield::_translateToHtml > given type not known !!',E_USER_WARNING);
	          return false;
	        break;
	    }
	  }
	  else
	  {
	  	return false;
	  }
 	}

}

 ?>
