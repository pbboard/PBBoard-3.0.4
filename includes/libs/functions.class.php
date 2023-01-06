<?php
class PowerBBSystemFunctions
{
    var $PowerBB;

	function __construct($PowerBB)
	{
		$this->Engine = $PowerBB;
	}

	function ListProc(&$rows,$x,$param)
	{

		if (empty($param['proc']))
		{
			trigger_error('ERROR::NEED_PARAMETER',E_USER_ERROR);
		}

		if (is_array($param['proc']))
		{
			foreach ($param['proc'] as $f => $p)
			{

				if (!is_array($p['method']))
				{
					if ($p['method'] == 'clean')
					{
						if ($f == '*')
						{
							$this->CleanVariable($rows[$x],$p['param']);
						}
						else
						{
							if (!empty($p['store']))
							{
								$rows[$x][$p['store']] = $this->CleanVariable($rows[$x][$f],$p['param']);
							}
							else
							{
								$rows[$x][$f] = $this->CleanVariable($rows[$x][$f],$p['param']);
							}
						}
					}
					elseif ($p['method'] == 'date')
					{
						if (is_numeric($rows[$x][$f]))
						{
							if (!empty($p['store']))
							{
								$rows[$x][$p['store']] = $this->_date($rows[$x][$f],$p['type']);
							}
							else
							{
								$rows[$x][$f] = $this->_date($rows[$x][$f],$p['type']);
							}
						}
						else
						{
							if (!empty($rows[$x][$p['store']]))
							{
								$rows[$x][$p['store']] = $rows[$x][$f];
							}
							else
							{
								$rows[$x][$f] = $rows[$x][$f]; // Very Power line :p
							}
						}
					}
					elseif ($p['method'] == 'time')
					{
						if (is_numeric($rows[$x][$f]))
						{
							if (!empty($p['store']))
							{
								$rows[$x][$p['store']] = $this->_time($rows[$x][$f]);
							}
							else
							{
								$rows[$x][$f] = $this->_time($rows[$x][$f]);
							}
						}
						else
						{
							if (!empty($p['store']))
							{
								$rows[$x][$p['store']] = $rows[$x][$f];
							}
							else
							{
								$rows[$x][$f] = $rows[$x][$f];
							}
						}
					}
					elseif ($p['method'] == 'time_ago')
					{
						if (is_numeric($rows[$x][$f]))
						{
							if (!empty($p['store']))
							{
								$rows[$x][$p['store']] = $this->time_ago($rows[$x][$f]);
							}
							else
							{
								$rows[$x][$f] = $this->time_ago($rows[$x][$f]);
							}
						}
						else
						{
							if (!empty($p['store']))
							{
								$rows[$x][$p['store']] = $rows[$x][$f];
							}
							else
							{
								$rows[$x][$f] = $rows[$x][$f];
							}
						}
					}
					elseif ($p['method'] == 'list')
					{
						$rows[$p['store']][$rows[$x][$p['id']]] = $rows[$x][$f];
					}
					elseif ($p['method'] == 'replace')
					{
						if (strstr($p['replace'],'rows{'))
						{
							$text = str_replace('rows{','',$p['replace']);
							$text = str_replace('}','',$text);

							$replace = $rows[$x][$text];
						}
						else
						{
							$replace = $p['replace'];
						}

						$rows[$x][$p['store']] = str_replace($p['search'],$replace,$rows[$x][$f]);
					}
					else
					{
						trigger_error('ERROR::BAD_VALUE_OF_METHOD_VARIABLE',E_USER_ERROR);
					}
				}
				else
				{
					if (in_array('clean',$p['method']))
					{
						if ($f == '*')
						{
							$this->CleanVariable($rows[$x],$p['param']);
						}
						else
						{
							if (!empty($p['store']))
							{
								$rows[$x][$p['store']] = $this->CleanVariable($rows[$x][$f],$p['param']);
							}
							else
							{
								$rows[$x][$f] = $this->CleanVariable($rows[$x][$f],$p['param']);
							}
						}
					}

					if (in_array('date',$p['method']))
					{
						if (is_numeric($rows[$x][$f]))
						{
							if (!empty($p['store']))
							{
								$rows[$x][$p['store']] = $this->_date($rows[$x][$f],$p['type']);
							}
							else
							{
								$rows[$x][$f] = $this->_date($rows[$x][$f],$p['type']);
							}
						}
						else
						{
							if (!empty($p['store']))
							{
								$rows[$x][$p['store']] = $rows[$x][$f];
							}
							else
							{
								$rows[$x][$f] = $rows[$x][$f]; // Very Power line :p
							}
						}
					}

					if (in_array('time',$p['method']))
					{
						if (is_numeric($rows[$x][$f]))
						{
							if (!empty($p['store']))
							{
								$rows[$x][$p['store']] = $this->_time($rows[$x][$f]);
							}
							else
							{
								$rows[$x][$f] = $this->_time($rows[$x][$f]);
							}
						}
						else
						{
							if (!empty($p['store']))
							{
								$rows[$x][$p['store']] = $rows[$x][$f];
							}
							else
							{
								$rows[$x][$f] = $rows[$x][$f];
							}
						}
					}

					if (in_array('list',$p['method']))
					{
						$rows[$p['store']][$rows[$x][$p['id']]] = $rows[$x][$f];
					}

					if (in_array('replace',$p['method']))
					{
						if (strstr($p['replace'],'rows{'))
						{
							$text = str_replace('rows{','',$p['replace']);
							$text = str_replace('}','',$p['replace']);

							$replace = &$rows[$x][$text];
						}
						else
						{
							$replace = $p['replace'];
						}

						$rows[$x][$p['store']] = str_replace($p['search'],$replace,$rows[$x][$f]);
					}
				}
			}
		}
		else
		{
			trigger_error('ERROR::PROC_SHOULD_BE_ARRAY',E_USER_ERROR);
		}
	}

function fetch_gzipped_text($source)
	{

	 $store = array();
	    $_store = 0;
	    $_offset = 0;
	    // Unify Line-Breaks to \n
	    $source = preg_replace('/\015\012|\015|\012/', "\n", $source);
	    // capture Internet Explorer and KnockoutJS Conditional Comments
	    if (preg_match_all(
	        '#<!--((\[[^\]]+\]>.*?<!\[[^\]]+\])|(\s*/?ko\s+.+))-->#is',
	        $source,
	        $matches,
	        PREG_OFFSET_CAPTURE | PREG_SET_ORDER
	    )
	    ) {
	        foreach ($matches as $match) {
	            $store[] = $match[ 0 ][ 0 ];
	            $_length = strlen($match[ 0 ][ 0 ]);
	            $replace = '@!pBB:' . $_store . ':PBB@!@';
	            $source = substr_replace($source, $replace, $match[ 0 ][ 1 ] - $_offset, $_length);
	            $_offset += $_length - strlen($replace);
	            $_store++;
	        }
	    }
	    // Strip all HTML-Comments
	    // yes, even the ones in <script> - see http://stackoverflow.com/a/808850/515124
	    $source = preg_replace('#<!--.*?-->#ms', '', $source);
	    // capture html elements not to be messed with
	    $_offset = 0;
	    if (preg_match_all(
	        '#(<script[^>]*>.*?</script[^>]*>)|(<textarea[^>]*>.*?</textarea[^>]*>)|(<pre[^>]*>.*?</pre[^>]*>)#is',
	        $source,
	        $matches,
	        PREG_OFFSET_CAPTURE | PREG_SET_ORDER
	    )
	    ) {
	        foreach ($matches as $match) {
	            $store[] = $match[ 0 ][ 0 ];
	            $_length = strlen($match[ 0 ][ 0 ]);
	            $replace = '@!pBB:' . $_store . ':PBB@!@';
	            $source = substr_replace($source, $replace, $match[ 0 ][ 1 ] - $_offset, $_length);
	            $_offset += $_length - strlen($replace);
	            $_store++;
	        }
	    }
	    $expressions = array(// replace multiple spaces between tags by a single space
	                         // can't remove them entirely, becaue that might break poorly implemented CSS display:inline-block elements
	                         '#(:PBB@!@|>)\s+(?=@!pBB:|<)#s'                                    => '\1 \2',
	                         // remove spaces between attributes (but not in attribute values!)
	                         '#(([a-z0-9]\s*=\s*("[^"]*?")|(\'[^\']*?\'))|<[a-z0-9_]+)\s+([a-z/>])#is' => '\1 \5',
	                         // note: for some very weird reason trim() seems to remove spaces inside attributes.
	                         // maybe a \0 byte or something is interfering?
	                         '#^\s+<#Ss'                                                               => '<',
	                         '#>\s+$#Ss'                                                               => '>',
	    );
	    $source = preg_replace(array_keys($expressions), array_values($expressions), $source);
	    // note: for some very weird reason trim() seems to remove spaces inside attributes.
	    // maybe a \0 byte or something is interfering?
	    // $source = trim( $source );
	    $_offset = 0;
	    if (preg_match_all('#@!pBB:([0-9]+):PBB@!@#is', $source, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER)) {
	        foreach ($matches as $match) {
	            $_length = strlen($match[ 0 ][ 0 ]);
	            $replace = $store[ $match[ 1 ][ 0 ] ];
	            $source = substr_replace($source, $replace, $match[ 0 ][ 1 ] + $_offset, $_length);
	            $_offset += strlen($replace) - $_length;
	            $_store++;
	        }
	    }
	    return $source;
	}

 	/**
 	 * Clean the variable from any dirty :) , we should be thankful for abuamal
 	 *
 	 * By : abuamal
 	 */
	function CleanVariable($variable, $type)
	{
		if (!is_array($variable))
		{
			return $this->SafeInput($variable,$type);
		}
		else
		{
			foreach ($variable as $key => $var)
			{

				if (is_array($var))
				{
					$this->CleanVariable($variable[$key], $type);
				}
				else
				{
					if (isset($variable[$key]))
					{
				    	$variable[$key] = $this->SafeInput($var, $type);
					}
				}
			}

			return true;
		}
	}

	/**
	 * Kill script kiddes and crackers!
	 */
	function SafeInput($var,$type)
	{
		switch ($type)
		{
			case 'sql':
				return addslashes($var);
			break;

			case 'html':
				return $this->htmlspecialchar($var);
				break;

			case 'intval':
				return intval($var);
				break;

			case 'trim':
				return trim($var);
				break;

			case 'unhtml':
				return $this->BackHTML($var);
				break;

			case 'nohtml':
				return ($var);
				break;

			default:
				trigger_error('ERROR::BAD_VALUE_OF_TYPE_VARIABLE',E_USER_ERROR);
				break;
		}
	}

	/**
	 * Clean the local arrays (like _POST , _GET , _SERVER etc ...)
	 */
	function LocalArraySetup()
	{
 		// Array with names and values
 		$vars				=	array();
 		$vars['_POST'] 		= 	$_POST;
 		$vars['_GET'] 		= 	$_GET;
 		$vars['_COOKIE'] 	= 	$_COOKIE;
 		$vars['_FILES'] 	= 	$_FILES;
 		$vars['_SERVER'] 	= 	$_SERVER;

		foreach ($vars as $name => $value)
		{
           // If using MySQL
			$this->Engine->$name = $value;
			$this->stripslashes_deep($this->Engine->$name); // TODO : Why not from xss also?
		}
		// Sorry, but we _should_ do that!
 		unset($_POST,$_GET,$_COOKIE,$_FILES,$_SERVER);
	}

	function _date($input,$type = 'ty')
	{
		global $PowerBB;
        $format = $PowerBB->_CONF['info_row']['datesystem'].' '.$PowerBB->_CONF['info_row']['timesystem'];
		if(is_numeric($input))
		{
		$timesystem = (new DateTime())->setTimestamp($input);
		$input =  $timesystem->format($format);
		$input = str_ireplace('PM',$PowerBB->_CONF['template']['_CONF']['lang']['PM'],$input);
		$input = str_ireplace('AM',$PowerBB->_CONF['template']['_CONF']['lang']['AM'],$input);
		return $input;
		}
		else
		{
         $timesystem = DateTime::createFromFormat($format,$input);
         return $timesystem;
		}

        /*
		$this_date_today					    =	date($PowerBB->_CONF['info_row']['datesystem']);
		$this_date_yesterday					=	date($PowerBB->_CONF['info_row']['datesystem'],mktime(0, 0, 0, date("m"),date("d")-1,date("Y")));
		$this_date_before_yesterday				=	date($PowerBB->_CONF['info_row']['datesystem'],mktime(0, 0, 0, date("m"),date("d")-2,date("Y")));
		$this_date_last_week					=	date($PowerBB->_CONF['info_row']['datesystem'],mktime(0, 0, 0, date("m"),date("d")-7,date("Y")));

		$this_date_last_two_weeks				=	date($PowerBB->_CONF['info_row']['datesystem'],mktime(0, 0, 0, date("m"),date("w")-2,date("Y")));
		$this_date_last_three_weeks				=	date($PowerBB->_CONF['info_row']['datesystem'],mktime(0, 0, 0, date("m"),date("w")-3,date("Y")));
		$this_date_last_month					=	date($PowerBB->_CONF['info_row']['datesystem'],mktime(0, 0, 0, date("m"),date("w")-4,date("Y")));


		$input = str_replace($this_date_today,$PowerBB->_CONF['template']['_CONF']['lang']['Today'].' '.$this_date_today,$input);
        $input = str_replace($this_date_yesterday,$PowerBB->_CONF['template']['_CONF']['lang']['yesterday'].' '.$this_date_today,$input);
        $input = str_replace($this_date_before_yesterday,$PowerBB->_CONF['template']['_CONF']['lang']['before_yesterday'].' '.$this_date_today,$input);
        $input = str_replace($this_date_last_week,$PowerBB->_CONF['template']['_CONF']['lang']['last_week'].' '.$this_date_today,$input);
        $input = str_replace($this_date_last_two_weeks,$PowerBB->_CONF['template']['_CONF']['lang']['last_two_weeks'].' '.$this_date_today,$input);
        $input = str_replace($this_date_last_three_weeks,$PowerBB->_CONF['template']['_CONF']['lang']['last_three_weeks'].' '.$this_date_today,$input);
        $input = str_replace($this_date_last_month,$PowerBB->_CONF['template']['_CONF']['lang']['last_month'].' '.$this_date_today,$input);
        */
	}

    function year_date($input)
	{
		global $PowerBB;
         $format = $PowerBB->_CONF['info_row']['datesystem'];
		if(is_numeric($input))
		{
		$timesystem = (new DateTime())->setTimestamp($input);
		return $timesystem->format($format);
		}
		else
		{
         $timesystem = DateTime::createFromFormat($format,$input);
         return $timesystem;
		}
	}

	function _time($time)
	{
		global $PowerBB;
        $format = $PowerBB->_CONF['info_row']['timesystem'];
		if(is_numeric($time))
		{
		$timesystem = (new DateTime())->setTimestamp($time);
		return $timesystem->format($format);
		}
		else
		{
         $timesystem = DateTime::createFromFormat($format,$time);
         return $timesystem;
		}
	}

	function dtime($time,$format)
	{
		global $PowerBB;
		if(is_numeric($time))
		{
		$timesystem = (new DateTime())->setTimestamp($time);
		return $timesystem->format($format);
		}
		else
		{
         $timesystem = DateTime::createFromFormat($format,$time);
         return $timesystem;
		}
	}
	function time_ago($input,$type = 'ty')
	{
		global $PowerBB;

	   if($input > time()){
	   return false;
	   exit;
	   }
	   $period = array($PowerBB->_CONF['template']['_CONF']['lang']['second'], $PowerBB->_CONF['template']['_CONF']['lang']['minute'], $PowerBB->_CONF['template']['_CONF']['lang']['hour'], $PowerBB->_CONF['template']['_CONF']['lang']['Day'], $PowerBB->_CONF['template']['_CONF']['lang']['week'], $PowerBB->_CONF['template']['_CONF']['lang']['month'], $PowerBB->_CONF['template']['_CONF']['lang']['year'], "decade");
	   $periods = array($PowerBB->_CONF['template']['_CONF']['lang']['seconds'], $PowerBB->_CONF['template']['_CONF']['lang']['minutes'], $PowerBB->_CONF['template']['_CONF']['lang']['hours'], $PowerBB->_CONF['template']['_CONF']['lang']['days'], $PowerBB->_CONF['template']['_CONF']['lang']['weeks'], $PowerBB->_CONF['template']['_CONF']['lang']['months'], $PowerBB->_CONF['template']['_CONF']['lang']['years'], "decade");
	   $periodtw = array($PowerBB->_CONF['template']['_CONF']['lang']['two_seconds'], $PowerBB->_CONF['template']['_CONF']['lang']['two_minutes'], $PowerBB->_CONF['template']['_CONF']['lang']['two_hours'], $PowerBB->_CONF['template']['_CONF']['lang']['two_days'], $PowerBB->_CONF['template']['_CONF']['lang']['two_weeks'], $PowerBB->_CONF['template']['_CONF']['lang']['two_months'], $PowerBB->_CONF['template']['_CONF']['lang']['two_years'], "decade");
	   $lengths = array("60","60","24","7","4.35","12","10");

	   $now = time();
	    $difference = $now - $input;
	   for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
	      $difference /= $lengths[$j];
	   }

	   $difference = round($difference);
	    if($difference == 2){
	    $x = $periodtw[$j];
	      return $PowerBB->_CONF['template']['_CONF']['lang']['since']." $x";
	   }elseif($difference != 1 and $difference <= 10) {
	    $x= $periods[$j];
	       return $PowerBB->_CONF['template']['_CONF']['lang']['since']." $difference $x";
	   }else{
	    $x = $period[$j];
	     return $PowerBB->_CONF['template']['_CONF']['lang']['since']." $difference $x";
	   }


	}

	function BackHTML($text)
	{
		$text = str_replace('&amp;','&',$text);
		$text = str_replace('&lt;','<',$text);
		$text = str_replace('&quot;','"',$text);
		$text = str_replace('&gt;','>',$text);
		$text = str_replace('document.cookie','',$text);
		$text = str_replace('document.location','',$text);
		$text = str_replace('javascript','',$text);

		return $text;
	}

	function htmlspecialchar($text)
	{
	    $text = htmlspecialchars($text);
		$text = str_replace("'",'&#39;',$text);
		$text = stripslashes($text);
		return $text;
	}

	/**
	* Reverses the effects of magic_quotes on an entire array of variables
	*
	* param	array	The array on which we want to work
	*/
	function stripslashes_deep(&$value, $depth = 0)
	{
		if (is_array($value))
		{
		    foreach ($value AS $key => $val)
		    {
		        if (is_string($val))
		        {

					if (defined('DONT_STRIPS_SLIASHES'))
					{
					 $value["$key"] = $val;
					}
					else
					{
					$value["$key"] = stripslashes($val);
					}
				}
		        else if (is_array($val) AND $depth < 10)
		        {
		            $this->stripslashes_deep($value["$key"], $depth + 1);
		        }
		    }
		}
	}

	function CleanSymbols($text)
	{
		$text = str_replace("'",'',$text);
		$text = str_replace(">",'',$text);
		$text = str_replace("<",'',$text);
		$text = str_replace("*",'',$text);
		$text = str_replace("%",'',$text);
		$text = str_replace("$",'',$text);
		$text = str_replace("#",'',$text);
		$text = str_replace("+",'',$text);
		$text = str_replace("^",'',$text);
		$text = str_replace("&",'',$text);
		$text = str_replace(",",'',$text);
		$text = str_replace("~",'',$text);
		$text = str_replace("@",'',$text);
		$text = str_replace("!",'',$text);
		$text = str_replace("{",'',$text);
		$text = str_replace("}",'',$text);
		$text = str_replace("(",'',$text);
		$text = str_replace("/",'',$text);
		$text = str_ireplace("union",'',$text);
		$text = str_replace("-",'',$text);
		$text = str_replace("]",'',$text);
		$text = str_replace("[",'',$text);
		$text = str_replace(";",'',$text);

		return $text;
	}

   function ReplaceMysqlExtension($text)
	{
		$text = str_replace("mysql_",'mysql_',$text);
		$text = str_ireplace("mysql_fetch_array",'$PowerBB->DB->sql_fetch_array',$text);
		$text = str_replace("mysql_free_result",'$PowerBB->DB->mysql_free_result',$text);
		$text = str_replace("mysql_query",'$PowerBB->DB->sql_query',$text);
		$text = str_replace("mysql_unbuffered_query",'$PowerBB->DB->sql_unbuffered_query',$text);
		$text = str_replace("mysql_num_rows",'$PowerBB->DB->sql_num_rows',$text);
		$text = str_replace("mysql_insert_id",'$PowerBB->DB->sql_insert_id',$text);
		$text = str_replace("mysql_fetch_assoc",'$PowerBB->DB->sql_fetch_assoc',$text);

		return $text;
	}
}

?>
