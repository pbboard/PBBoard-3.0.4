<?php

class PowerBBIcons
{
	var $id;
	var $PowerBB;

	function __construct($PowerBB)
	{
		$this->Engine = $PowerBB;
	}

	function InsertSmile($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['field']['smile_type'] = 0;

		$query = $this->Engine->records->Insert($this->Engine->table['smiles'],$param['field']);

		if (isset($param['get_id']))
		{
			$this->id = $this->Engine->DB->sql_insert_id();
		}

		return ($query) ? true : false;
	}

 	function UpdateSmile($param)
 	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['field']['smile_type'] = 0;

		$query = $this->Engine->records->Update($this->Engine->table['smiles'],$param['field'],$param['where']);

		return ($query) ? true : false;
 	}

	function IsSmile($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['select'] 	= 	'*';
		$param['from'] 		= 	$this->Engine->table['smiles'];

		$num = $this->Engine->records->GetNumber($param);

		return ($num <= 0) ? false : true;
	}

	function DeleteSmile($param)
	{
 		if (empty($param['id']))
 		{
 			trigger_error('ERROR::NEED_PARAMETER -- FROM DeleteSmile() -- EMPTY id',E_USER_ERROR);
 		}

		$param['table'] 				= 	$this->Engine->table['smiles'];
		$param['where'] 				= 	array();

		$param['where'][0] 				= 	array();
		$param['where'][0]['name'] 		= 	'id';
		$param['where'][0]['oper'] 		= 	'=';
		$param['where'][0]['value'] 	= 	$param['id'];

		$param['where'][1] 				= 	array();
		$param['where'][1]['con'] 		= 	'AND';
		$param['where'][1]['name'] 		= 	'smile_type';
		$param['where'][1]['oper'] 		= 	'=';
		$param['where'][1]['value'] 	= 	'0';

		$del = $this->Engine->records->Delete($param);

		return ($del) ? true : false;
	}

	function GetSmileList($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

 		$param['select'] 			= 	'*';
 		$param['from'] 				= 	$this->Engine->table['smiles'];
 		$param['where']				=	array();
 		$param['where'][0]			=	array();
 		$param['where'][0]['name']	=	'smile_type';
 		$param['where'][0]['oper']	=	'=';
 		$param['where'][0]['value']	=	'0';

     	$rows = $this->Engine->records->GetList($param);

     	return $rows;
     }

  	function GetCachedSmiles()
	{
 		$cache = $this->Engine->_CONF['info_row']['smiles_cache'];

		$cache = json_decode(base64_decode($cache), true);

		return $cache;
	}

	function GetSmileInfo($param)
	{
		if (empty($param['id']))
		{
			trigger_error('ERROR::NEED_PARAMETER -- FROM GetSmileInfo() -- EMPTY id',E_USER_ERROR);
		}

		$param['select'] 				= 	'*';
		$param['from'] 					= 	$this->Engine->table['smiles'];
		$param['where'] 				= 	array();

		$param['where'][0] 				= 	array();
		$param['where'][0]['name'] 		= 	'smile_type';
		$param['where'][0]['oper'] 		= 	'=';
		$param['where'][0]['value'] 	= 	'0';

		$param['where'][1] 				= 	array();
		$param['where'][1]['con'] 		= 	'AND';
		$param['where'][1]['name'] 		= 	'id';
		$param['where'][1]['oper'] 		= 	'=';
		$param['where'][1]['value'] 	= 	$param['id'];

		$rows = $this->Engine->records->GetInfo($param);

		return $rows;
	}

 	function CreateSmilesCache($param)
 	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$smiles = $this->GetSmileList($param);

 		$cache 	= 	array();
 		$x		=	0;
 		$n		=	sizeof($smiles);

		while ($x < $n)
		{
			$cache[$x] 					= 	array();
			$cache[$x]['id']		 	= 	$smiles[$x]['id'];
			$cache[$x]['smile_short'] 	= 	$smiles[$x]['smile_short'];
			$cache[$x]['smile_path'] 	= 	$smiles[$x]['smile_path'];

			$x += 1;
		}

		$cache = base64_encode(json_encode($cache));
		return $cache;
 	}

 	function UpdateSmilesCache($param)
 	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

 		$cache = $this->CreateSmilesCache($param);

 		$update_cache = $this->Engine->info->UpdateInfo(array('value'=>$cache,'var_name'=>'smiles_cache'));

 		return ($update_cache) ? true : false;
 	}

 	function GetSmilesNumber($param)
 	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['select'] 				= 	'*';
		$param['from'] 					= 	$this->Engine->table['smiles'];
		$param['where'] 				= 	array();

		$param['where'][0] 				= 	array();
		$param['where'][0]['name'] 		= 	'smile_type';
		$param['where'][0]['oper'] 		= 	'=';
		$param['where'][0]['value'] 	= 	'0';

		$num   = $this->Engine->records->GetNumber($param);

		return $num;
 	}

	function InsertIcon($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['field']['smile_type'] = 1;

		$query = $this->Engine->records->Insert($this->Engine->table['smiles'],$param['field']);

		if (isset($param['get_id']))
		{
			$this->id = $this->Engine->DB->sql_insert_id();
		}

		return ($query) ? true : false;
	}

  	function UpdateIcon($param)
 	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['field']['smile_type'] = 1;

		$query = $this->Engine->records->Update($this->Engine->table['smiles'],$param['field'],$param['where']);

		return ($query) ? true : false;
 	}

	function DeleteIcon($param)
	{
 		if (empty($param['id']))
 		{
 			trigger_error('ERROR::NEED_PARAMETER -- FROM DeleteIcon() -- EMPTY id',E_USER_ERROR);
 		}

		$param['table'] 				= 	$this->Engine->table['smiles'];
		$param['where'] 				= 	array();

		$param['where'][0] 				= 	array();
		$param['where'][0]['name'] 		= 	'id';
		$param['where'][0]['oper'] 		= 	'=';
		$param['where'][0]['value'] 	= 	$param['id'];

		$param['where'][1] 				= 	array();
		$param['where'][1]['con'] 		= 	'AND';
		$param['where'][1]['name'] 		= 	'smile_type';
		$param['where'][1]['oper'] 		= 	'=';
		$param['where'][1]['value'] 	= 	'1';

		$del = $this->Engine->records->Delete($param);

		return ($del) ? true : false;
	}

     function GetIconList($param)
     {
		if (!isset($param)
			or !is_array($param))
 		{
 			$param = array();
 		}

     	$param['select'] 			= 	'*';
 		$param['from'] 				= 	$this->Engine->table['smiles'];
 		$param['where']				=	array();
 		$param['where'][0]			=	array();
 		$param['where'][0]['name']	=	'smile_type';
 		$param['where'][0]['oper']	=	'<>';
 		$param['where'][0]['value']	=	'0';

     	$rows = $this->Engine->records->GetList($param);

     	return $rows;
    }

	function GetIconInfo($param)
	{
		if (empty($param['id']))
		{
			trigger_error('ERROR::NEED_PARAMETER -- FROM GetIconInfo() -- EMPTY id',E_USER_ERROR);
		}

		$param['select'] 				= 	'*';
		$param['from'] 					= 	$this->Engine->table['smiles'];
		$param['where'] 				= 	array();

		$param['where'][0] 				= 	array();
		$param['where'][0]['name'] 		= 	'smile_type';
		$param['where'][0]['oper'] 		= 	'=';
		$param['where'][0]['value'] 	= 	'1';

		$param['where'][1] 				= 	array();
		$param['where'][1]['con'] 		= 	'AND';
		$param['where'][1]['name'] 		= 	'id';
		$param['where'][1]['oper'] 		= 	'=';
		$param['where'][1]['value'] 	= 	$param['id'];

		$rows = $this->Engine->records->GetInfo($param);

		return $rows;
	}

 	function GetIconsNumber($param)
 	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['select'] 				= 	'*';
		$param['from'] 					= 	$this->Engine->table['smiles'];
		$param['where'] 				= 	array();

		$param['where'][0] 				= 	array();
		$param['where'][0]['name'] 		= 	'smile_type';
		$param['where'][0]['oper'] 		= 	'=';
		$param['where'][0]['value'] 	= 	'1';

		$num   = $this->Engine->records->GetNumber($param);

		return $num;
 	}
}

?>
