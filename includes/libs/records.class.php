<?php
/*
Started : 19-4-2007 11:55 AM
End : 19-4-2007 12:03 PM
Update : 31/09/2012 08:56:54 AM
Update : 02/04/2021 09:05:54 AM
*/

class PowerBBRecords
{
	var $from;
	var $PowerBB;

	function __construct($PowerBB)
	{
		$this->Engine = $PowerBB;
	}

	function Select($param)
	{
		if (empty($param['from']))
		{
			trigger_error('ERROR::NEED_PARAMETER -- FROM Select() -- FROM',E_USER_ERROR);
		}

		$statement 	= 	'SELECT ';
		$statement	.=	(!empty($param['select'])) ? $param['select'] : '*';
		$statement	.=	' FROM ' . $param['from'];

		if (@is_array($param['join']))
		{
			if ($param['join']['type'] == 'inner')
			{
				$statement .= ' INNER';
			}
			elseif ($param['join']['type'] == 'left')
			{
				$statement .= ' LEFT';
			}
			elseif ($param['join']['type'] == 'right')
			{
				$statement .= ' RIGHT';
			}
			else
			{
				trigger_error('ERROR::NEED_PARAMETER -- FROM Select() -- JOIN TYPE',E_USER_ERROR);
			}

			$statement .= ' JOIN ' . $param['join']['from'] . ' ON ' . $param['join']['where'];
		}

		if (@is_array($param['where'])
			or (@is_array($param['field'])
			and $this->from == 'info'))
		{
			$statement .= ' WHERE ';
			$k = @is_array($param['field']) ? 'field' : 'where';

			$y = sizeof($param[$k]);

			if ($y == 1)
			{
				$key = array_keys($param[$k]);

				if (empty($param[$k][$key[0]]['name']))
				{
					trigger_error('ERROR::NEED_PARAMETER -- FROM Select() -- WHERE NAME');
				}

				$oper = (!empty($param[$k][$key[0]]['oper'])) ? $param[$k][$key[0]]['oper'] : '=';

				$statement .= $param[$k][$key[0]]['name'];

				$oper_need_space = false;

				$oper_to_check = strtolower($oper);

				if ($oper_to_check == 'like'
					or $oper_to_check == 'between')
				{
					$oper_need_space = true;

					$statement .= ' ';
				}

				$statement .= $oper;

				if (isset($oper_need_space))
				{
					$statement .= ' ';
				}

				if ($oper_to_check != 'between')
				{
					$statement .= "'";
				}

				$statement .= $param[$k][$key[0]]['value'];

				if ($oper_to_check != 'between')
				{
					$statement .= "'";
				}
			}
			elseif ($y > 1)
			{
				$x = 0;

				while ($x < $y)
				{
                    $param[$k][$x]['value'] = $this->Engine->sys_functions->CleanVariable($param[$k][$x]['value'],'sql');
					if (!isset($param[$k][$x]['name']))
					{
						trigger_error('ERROR::NEED_PARAMETER -- FROM Select() -- WHERE NAME');
					}

					$oper = (!empty($param[$k][$x]['oper'])) ? $param[$k][$x]['oper'] : '=';
                   if (isset($param[$k][$x]['con']))
                    {
					$statement .= ' ' . $param[$k][$x]['con'] . ' ' . $param[$k][$x]['name'];
                    }
                    else
                    {
					$statement .= ' ' . $param[$k][$x]['name'];
                    }

					$oper_is_like = false;

					$oper_to_check = strtolower($oper);

					if ($oper_to_check == 'like'
						or $oper_to_check == 'between')
					{
						$oper_need_space = true;

						$statement .= ' ';
					}

					$statement .= $oper;

					if (isset($oper_need_space))
					{
						$statement .= ' ';
					}

					if ($oper_to_check != 'between')
					{
						$statement .= "'";
					}
					$statement .= $param[$k][$x]['value'];

					if ($oper_to_check != 'between')
					{
						$statement .= "'";
					}

					$x += 1;
				}
			}
			else
			{
				trigger_error('ERROR::EMPTY_ARRAY -- FROM Select()',E_USER_ERROR);
			}
		}

		if (@is_array($param['order']))
		{
			if ($param['order']['type'] != 'RAND()')
			{
				if (empty($param['order']['field']))
				{
					trigger_error('ERROR::NEED_PARAMETER -- FROM Select() -- ORDER FIELD',E_USER_ERROR);
				}
			}

			$statement .= ' ORDER BY ' . $param['order']['field'] . ' ';
			$statement .= (!empty($param['order']['type'])) ? $param['order']['type'] : 'DESC';
		}

		if (@is_array($param['pager']))
		{
			if (!isset($param['pager']['total'])
				or !isset($param['pager']['perpage'])
				or !isset($param['pager']['count'])
				or empty($param['pager']['location'])
				or empty($param['pager']['var']))
			{
				trigger_error('ERROR::NEED_PARAMETER -- FROM Select() -- PAGER',E_USER_ERROR);
			}

            $param['pager']['count'] = $param['pager']['perpage'] * $param['pager']['count'] - $param['pager']['perpage'];
			$param['pager']['perpage'] 	= ($param['pager']['perpage'] < 0) ? 10 : $param['pager']['perpage'];
			$param['pager']['count'] 	= ($param['pager']['count'] < 0) ? 0 : $param['pager']['count'];


			$this->Engine->pager->start(	$param['pager']['total'],
											$param['pager']['perpage'],
											$param['pager']['count'],
											$param['pager']['location'],
											$param['pager']['var']);

			$statement .= ' LIMIT ' . $param['pager']['count'] . ',' . $param['pager']['perpage'];
		}
		else
		{
			if (!empty($param['limit']))
			{
				$statement .= ' LIMIT ' . $param['limit'];
			}
		}

		if (!empty($param['sql_statment']))
		{
			$statement .= ' ' . $param['sql_statment'];
		}

		$query = $this->Engine->DB->sql_query($statement);

		return $query;
	}

	function Insert($table,$field)
	{
	global $config,$_VARS,$CALL_SYSTEM;
		if (empty($table)
			or empty($field))
		{
			trigger_error('ERROR::NEED_PARAMETER -- FROM Insert() -- TABLE OR FIELD',E_USER_ERROR);
		}

		$query_string = "INSERT INTO " . $table . " SET ";

		$size = count($field);

		$i = 0;

		foreach ($field as $name => $value)
		{
        $value = $this->Engine->sys_functions->CleanVariable($value,'sql');

			$query_string .= $name . '=' . "'$value'";

			if ($i < $size-1)
			{
				$i += 1;

				$query_string .= ',';
			}
		}

       $query = $this->Engine->DB->sql_unbuffered_query($query_string);

		return $query;
	}

	function Update($table,$field,$complete)
	{
		if (empty($table)
			or !isset($field))
		{
			trigger_error('ERROR::NEED_PARAMETER -- FROM Update() -- TABLE OR FIELD',E_USER_ERROR);
		}

		$statement = "UPDATE " . $table . " SET ";

		$f = array_filter($field,array('PowerBBRecords','_UpdateCallBack'));

		$size = sizeof($f);

		$i = 0;
		$x = 0;

		foreach ($f as $name => $value)
		{
        $value = $this->Engine->sys_functions->CleanVariable($value,'sql');

			$statement .= $name . '=' . "'$value'";
			if ($i < $size-1)
			{
				$i += 1;

				$statement .= ',';
			}
		}

		if (@is_array($complete))
		{
			$statement .= ' WHERE ';

			$y = sizeof($complete);

			$key = array_keys($complete);

			if (!@is_array($complete[$key[0]]))
			{
				$statement .= $complete[0] . "='" . $complete[1] . "'";
			}
			else
			{
				if ($y == 1)
				{
					if (empty($complete[$key[0]]['name']))
					{
						trigger_error('ERROR::NEED_PARAMETER -- FROM Update() -- WHERE NAME');
					}

					$oper = (!empty($complete[$key[0]]['oper'])) ? $complete[$key[0]]['oper'] : '=';

					$statement .= $complete[$key[0]]['name'] . $oper;

                    $statement .= "'";

					$statement .= $complete[$key[0]]['value'];

					$statement .= "'";

				}
				elseif ($y > 1)
				{
					$x = 0;

					while ($x < $y)
					{
						if (empty($complete[$x]['name']))
						{
							trigger_error('ERROR::NEED_PARAMETER -- FROM Update() -- WHERE NAME');
						}

						$oper = (!empty($complete[$x]['oper'])) ? $complete[$x]['oper'] : '=';

	                   if (isset($complete[$x]['con']))
	                    {
							$statement .= ' ' . $complete[$x]['con'] . ' ' . $complete[$x]['name'] . $oper;
	                    }
	                    else
	                    {
							$statement .= ' ' . $complete[$x]['name'] . $oper;
	                    }

						$statement .= "'";

						$statement .= $complete[$x]['value'];

						$statement .= "'";

						$x += 1;
					}
				}
				else
				{
					trigger_error('ERROR::EMPTY_ARRAY',E_USER_ERROR);
				}
			}
		}

		$query = $this->Engine->DB->sql_unbuffered_query($statement);

		return $query;
	}

	function GetList($param,$query=null)
	{

		$this->from = 'list';

		if (@is_array($param['where']))
		{
			$key = array_keys($param['where']);

			if (!@is_array($param['where'][$key[0]]))
			{
				$old_where = $param['where'];

				$param['where'] 			= 	array();
				$param['where'][0] 			= 	array();
				$param['where'][0]['name'] 	= 	$old_where[0];
				$param['where'][0]['oper']	=	'=';
				$param['where'][0]['value']	=	$old_where[1];
			}
		}

		if (empty($query))
		{
			$query = $this->Select($param);
		}

		$rows = array();

 	 	$x = 0;

 	 	while ($r = $this->Engine->DB->sql_fetch_array($query))
 	 	{
 	 	   $r['tag'] = @stripslashes($r['tag']);
 	 	   $r['title'] = @stripslashes($r['title']);
 	 	   $r['subject_describe'] = @stripslashes($r['subject_describe']);
 	 	   $r['tag'] = str_replace('&amp;','&',$r['tag']);
 	 	   $r['tag'] = str_replace('&#39;',"'",$r['tag']);
 	 	   $r['title'] = str_replace('&#39;',"'",$r['title']);
 	 	   $r['title'] = @htmlspecialchars($r['title']);

 	 		$rows[$x] = $r;

 	 		if (!empty($param['proc'])
 	 			and @is_array($param['proc']))
			{
 	 			$this->Engine->sys_functions->ListProc($rows,$x,$param);

 	 		}

 	 		$x += 1;
 	 	}

 	 	return (@is_array($rows)) ? $rows : $query;
 	 	$this->Engine->DB->sql_free_result($query);

	}

	function GetInfo($param,$query=null)
	{
		$this->from = 'info';

		if (@is_array($param['where']))
		{
			$key = array_keys($param['where']);

			if (!@is_array($param['where'][$key[0]]))
			{
				$old_where = $param['where'];

				unset($param['where']);

				$param['where'] 			= 	array();
				$param['where'][0] 			= 	array();
				$param['where'][0]['name'] 	= 	$old_where[0];
				$param['where'][0]['oper']	=	'=';
				$param['where'][0]['value']	=	$old_where[1];
			}
		}

		if (empty($query))
		{
			$query = $this->Select($param);
		}

		$rows = array();

		$rows = $this->Engine->DB->sql_fetch_array($query);

		return (@is_array($rows)) ? $rows : false;
 	 	$this->Engine->DB->sql_free_result($rows);

	}

	function GetNumber($param,$query=null)
	{
		$this->from = 'number';

		if (@is_array($param['where']))
		{
			$key = array_keys($param['where']);

			if (!@is_array($param['where'][$key[0]]))
			{
				$old_where = $param['where'];

				unset($param['where']);

				$param['where'] 			= 	array();
				$param['where'][0] 			= 	array();
				$param['where'][0]['name'] 	= 	$old_where[0];
				$param['where'][0]['oper']	=	'=';
				$param['where'][0]['value']	=	$old_where[1];
			}
		}

		if (empty($query))
		{
			$query = $this->Select($param);
		}

		$num = $this->Engine->DB->sql_num_rows($query);

		return is_numeric($num) ? $num : $query;
	}

	function Delete($param)
	{
		$statement = 'DELETE FROM ' . $param['table'];

		if (@is_array($param['where']))
		{
			$statement .= ' WHERE ';

			$y = sizeof($param['where']);

			$key = array_keys($param['where']);

			if (!@is_array($param['where'][$key[0]]))
			{
				$statement .= $param['where'][0] . "='" . $param['where'][1] . "'";
			}
			else
			{
				if ($y == 1)
				{
					if (empty($param['where'][$key[0]]['name']))
					{
						trigger_error('ERROR::NEED_PARAMETER -- FROM Delete() -- WHERE NAME');
					}

					$oper = (!empty($param['where'][$key[0]]['oper'])) ? $param['where'][$key[0]]['oper'] : '=';

					$statement .= $param['where'][$key[0]]['name'] . $oper;

					$statement .= "'";

					$statement .= $param['where'][$key[0]]['value'];

					$statement .= "'";

				}
				elseif ($y > 1)
				{
					$x = 0;

					while ($x < $y)
					{
						if (empty($param['where'][$x]['name']))
						{
							trigger_error('ERROR::NEED_PARAMETER -- FROM Delete() -- WHERE NAME');
						}

						$oper = (!empty($param['where'][$x]['oper'])) ? $param['where'][$x]['oper'] : '=';

	                   if (isset($param['where'][$x]['con']))
	                    {
						$statement .= ' ' . $param['where'][$x]['con'] . ' ' . $param['where'][$x]['name'] . $oper;
	                    }
	                    else
	                    {
						$statement .= ' ' . $param['where'][$x]['name'] . $oper;
	                    }

						$statement .= "'";

						$statement .= $param['where'][$x]['value'];

						$statement .= "'";

						$x += 1;
					}
				}
				else
				{
					trigger_error('ERROR::EMPTY_ARRAY -- FROM Delete()',E_USER_ERROR);
				}
			}
		}

		if (@is_array($param['order']))
		{
			if (empty($param['order']['field']))
			{
				trigger_error('ERROR::NEED_PARAMETER -- FROM Delete() -- ORDER FIELD',E_USER_ERROR);
			}

			$statement .= ' ORDER BY ' . $param['order']['field'] . ' ';
			$statement .= (!empty($param['order']['type'])) ? $param['order']['type'] : 'DESC';
		}

		if (!empty($param['limit']))
		{
			$statement .= ' LIMIT ' . $param['limit'];
		}

		if (!empty($param['sql_statment']))
		{
			$statement .= ' ' . $param['sql_statment'];
		}

		$query = $this->Engine->DB->sql_unbuffered_query($statement);

		return ($query) ? true : false;
	}

	function _UpdateCallBack($var)
	{
		return ((isset($var) or !empty($var))) ? true : false;
	}

	/**
	 * Alter Existing Table
	 * @param array $_params Need parameter as follows <br />
	 *   'table' => (NEEDED) The target table name <br />
	 *   'type'  => (NEEDED) Alter Type [Add | drop | change] <br />
	 *   'new_name' => (NEEDED) The new field name - used in [Change | Add] <br />
	 *   'name'  => (NEEDED) Existing Field Name - used in [Change | Drop] <br />
	 *   'def'  => (NEEDED) The new field definition like VARCHAR(250) - used in [Change | Add] <br />
	 *
	 * @return bool Only true if altered good
	 */
	function Alter($_params){
	  //parameters shoukd be an array
    if( true==@is_array($_params) ){
      //build query head
      ( true==isset($_params['table']) AND false==empty($_params['table']) )
        ? $query='ALTER TABLE `'.$_params['table'].'` '
        : trigger_error('records::Alter > Need Table name',E_USER_ERROR);
      //testing what to do exactely
      if($_params['type']=='add'){
        $query.=' ADD ';
        ( true==isset($_params['new_name']) AND false==empty($_params['new_name']) AND true==isset($_params['def']) AND false==empty($_params['def']))
          ? $query.='`'.$_params['new_name'].'` '.$_params['def']
          : trigger_error('records::Alter > Need new_name of the field and definition',E_USER_ERROR);

      }else if($_params['type']=='drop'){
        $query.=' DROP ';
        ( true==isset($_params['name']) AND false==empty($_params['name']) )
          ? $query.='`'.$_params['name'].'`'
          : trigger_error('records::Alter > Need field name',E_USER_ERROR);

      }else if($_params['type']=='change'){
        $query.=' CHANGE ';
        ( true==isset($_params['name']) AND false==empty($_params['name']) AND true==isset($_params['def']) AND false==empty($_params['def']) AND true==isset($_params['new_name']) AND false==empty($_params['new_name']))
          ? $query.='`'.$_params['name'].'` '.$_params['new_name'].' '.$_params['def']
          : trigger_error('records::Alter > Need new field name, deifnition and new_name',E_USER_ERROR);
      }

      //now execute and return home
      return $this->Engine->DB->sql_unbuffered_query($query);
    }

    //here there's no success
    return false;
	}

}

?>