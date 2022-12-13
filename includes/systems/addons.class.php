<?php

/**
 * PowerBB Engine - The Engine Helps You To Create Bulletin Board System.
 */

/**
 * package 	: 	PowerBBAddons (Addons)
 * @author 		: 	MSHRAQ abu-rakan ()
 * start 		: 	27/1/2010 , 09:35 PM
 */


class PowerBBAddons
{
	var $id;
	var $PowerBB;

	function __construct($PowerBB)
	{
		$this->Engine = $PowerBB;
	}

 	/**
 	 * Insert new Addons
 	 *
 	 * param :
 	 *			Oh :O it's a long list
 	 */
 	function InsertAddons($param)
 	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$query = $this->Engine->records->Insert($this->Engine->table['addons'],$param['field']);

		if (isset($param['get_id']))
		{
			$this->id = $this->Engine->DB->sql_insert_id();
		}

		return ($query) ? true : false;
 	}


	function DeleteAddons($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['table'] = $this->Engine->table['addons'];

		$del = $this->Engine->records->Delete($param);

		return ($del) ? true : false;
	}

	/**
	 * Get the list of Addons
	 *
	 * $param =
	 *			array(	'sql_statment'	=>	'complete SQL statement',
	 *					'proc'			=>	true // When you want proccess the outputs
	 *					);
	 *
	 * @return :
	 *				array -> of information
	 *				false -> when found no information
	 */
	function GetAddonsList($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['select'] 	= 	'*';
 		$param['from'] 		= 	$this->Engine->table['addons'];

		$rows = $this->Engine->records->GetList($param);

		return $rows;
	}

	/**
	 * Get the list of Multi-lang addons
	 *
	 * $param =
	 *			array('fields' => array());
	 *
	 * @return :
	 *				array -> of information
	 *				false -> when found no information
	 */
	function GetMultiLangAddonsList($param)
	{
		$wherestatement = '';
		if ( is_array($param['fields']) )
		{
			foreach ( $param['fields'] as $field => $value )
			{
				$wherestatement .= "and ".$field."='".$value."' ";
			}
		}
		$query = $this->Engine->DB->sql_query("SELECT * FROM ".$this->Engine->table['addons']." WHERE CHAR_LENGTH(languagevals) > '4' ".$wherestatement);
		$rows = array();
		while ( $row = $this->Engine->DB->sql_fetch_array($query) )
		{
			$rows[] = $row;
		}
		$result = (count($rows) > 0 ? $rows : 0);

		return $result;
	}

	/**
	 * Get Addons info
	 *
	 * $param =
	 *			array(	'id'	=>	'the id of Supermemberlogs');
	 *
	 * @return :
	 *			array -> of information
	 *			false -> when found no information
	 */
	function GetAddonsInfo($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['select'] 	= 	'*';
		$param['from'] 		= 	$this->Engine->table['addons'];

		$rows = $this->Engine->records->GetInfo($param);

 	 	return $rows;
	}

	function GetAddonsNumber($param)
	{
		if (!isset($param))
		{
			$param 	= array();
		}

		$param['select'] 	= 	'*';
		$param['from'] 		= 	$this->Engine->table['addons'];

		$num = $this->Engine->records->GetNumber($param);

		return $num;
	}

	 function UpdateAddons($param)
 	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$query = $this->Engine->records->Update($this->Engine->table['addons'],$param['field'],$param['where']);

		return ($query) ? true : false;
 	}

	function IsAddons($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['select'] 	= 	'*';
		$param['from'] 		= 	$this->Engine->table['addons'];

		$num = $this->Engine->records->GetNumber($param);

		return ($num <= 0) ? false : true;
	}
/*convert xml codes to array
* parameters : raw_xml : xml codes
*/

function xml_to_array($raw_xml)
{
		$parser = xml_parser_create();
		xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 0);
		xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
		if(xml_parse_into_struct($parser, $raw_xml, $vals, $index) === 0)
		{
			return false;
		}
		$i = -1;
		return $this->xml_get_children($vals, $i);
}

/*
 * xml_build_tag
* parameters : thisvals, vals, i, type
*/
function xml_build_tag($thisvals, $vals, &$i, $type)
{
		$tag['tag'] = $thisvals['tag'];
		if(isset($thisvals['attributes']))
		{
			$tag['attributes'] = $thisvals['attributes'];
		}

		if($type == "complete")
		{
			$tag['value'] = $thisvals['value'];
		}
		else
		{
			$tag = array_merge($tag, $this->xml_get_children($vals, $i));
		}
		return $tag;
}

/*
*xml_get_children
*parameters: vals, i
*/
function xml_get_children($vals, &$i)
{
		$collapse_dups = 1;
		$index_numeric = 0;
		$children = array();

		if($i > -1 && isset($vals[$i]['value']))
		{
			$children['value'] = $vals[$i]['value'];
		}

		while(++$i < count($vals))
		{
			$type = $vals[$i]['type'];
			if($type == "cdata")
			{
				$children['value'] .= $vals[$i]['value'];
			}
			elseif($type == "complete" || $type == "open")
			{
				$tag = $this->xml_build_tag($vals[$i], $vals, $i, $type);
				if($index_numeric)
				{
					$tag['tag']	= $vals[$i]['tag'];
					$children[]	= $tag;
				}
				else
				{
					$children[$tag['tag']][]	= $tag;
				}
			}
			elseif($type == "close")
			{
				break;
			}
		}
		if($collapse_dups)
		{
			foreach($children as $key => $value)
			{
				if(is_array($value) && (count($value) == 1))
				{
					$children[$key]	= $value[0];
				}
			}
		}
		return $children;
}


	function replacer($Action,$text,$find,$another,$line)
	{
	if(empty($find) or empty($another)){
		return FALSE;
	}

	if($Action == 'replace'){
		$md5_f = md5($text);
		$text	= preg_replace('#' . preg_quote($find, '#') . '#', $another, $text);

		//lets do it with another idea
		if($md5_f == md5($text))
		{
			$ex = explode($find, $text, 2);
			$text = $ex[0] . $another . $ex[1];
		}


	}

	if($Action == 'before'){
		$md5_f = md5($text);
		$text	=	preg_replace('#' . preg_quote($find, '#') . '#',   $another . (($line == 'new') ? "\n" : "")  . $find, $text);

		//lets do it with another idea
		if($md5_f == md5($text))
		{
			$ex = explode($find, $text, 2);
			$text = $ex[0] . $another . (($line == 'new') ? "\n" : "")  . $find . $ex[1];
		}


	}

	 if($Action == 'after'){
		$md5_f = md5($text);
		$text	=	preg_replace('#' . preg_quote($find, '#')  . '#', $find . (($line == 'new') ? "\n" : "") . $another, $text);

		//lets do it with another idea
		if($md5_f == md5($text))
		{
			$ex = explode($find, $text, 2);
			$text = $ex[0] . $find . (($line == 'new') ? "\n" : "") . $another . $ex[1];
		}

	}
	return $text ;


	}

}

?>
