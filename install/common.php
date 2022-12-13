<?php
error_reporting(E_ERROR | E_PARSE);
// check Requirements
if (function_exists('idate')) {
$check_PHP ='0';
}
else
{
$check_PHP ='1';
}

if (function_exists('mysqli_connect')) {
$check_mysql_connect ='0';
}
else
{
$check_mysql_connect ='1';
}

if (function_exists('base64_decode')) {
$check_base64 ='0';
}
else
{
$check_base64 ='1';
}

if (function_exists('curl_version')
or ini_get('allow_url_fopen')) {
$check_allow_url_fopen ='0';
}
else
{
$check_allow_url_fopen ='1';
}
if (extension_loaded('gd') or function_exists('gd_info')) {
$check_gd ='0';
}
else
{
$check_gd ='1';
}
if ($check_PHP
or $check_mysql_connect
or $check_base64
or $check_allow_url_fopen
or $check_gd)
{
include('install/check.php');
}

$DIR = dirname( __FILE__ );
$DIR = str_replace('setup','',$DIR);

define('DIR',$DIR . '/');

define('STOP_STYLE',false);
define('JAVASCRIPT_PowerCode',false);
define('INSTALL',true);

if (!is_array($CALL_SYSTEM))
{
	$CALL_SYSTEM = array();
}

$CALL_SYSTEM['GROUP'] 		= 	true;
$CALL_SYSTEM['MEMBER'] 		= 	true;
$CALL_SYSTEM['INFO'] 		= 	true;
$CALL_SYSTEM['SECTION'] 	= 	true;
$CALL_SYSTEM['CORE']        = 	true;
$CALL_SYSTEM['LANG']        = 	true;


if (!defined('IN_ADMIN'))
{
	$CALL_SYSTEM['ADS'] 		= 	true;
	$CALL_SYSTEM['ONLINE'] 		= 	true;
	$CALL_SYSTEM['STYLE'] 		= 	true;
    $CALL_SYSTEM['SECTION'] 	= 	true;
    $CALL_SYSTEM['CORE']        = 	true;
	$CALL_SYSTEM['LANG']        = 	true;
    $CALL_SYSTEM['GROUP'] 		= 	true;
}

// Can't live without this file :)
include('../../pbboard.class.php');
// Use the class in this file instead of use (X)HTML directly
include('pbboard_display.class.php');

// The master object
$PowerBB = new PowerBB;

$PowerBB->html = new PowerBBdisplay;

class PowerBBInstall
{
	function add_field($param)
	{
		global $PowerBB;

		$query = $PowerBB->DB->sql_query('ALTER TABLE ' . $param['table'] . ' ADD ' . $param['field_name'] . ' ' . $param['field_des']);

		return ($query) ? true : false;
	}

	 function error($msg,$no_header = false,$no_style = false)
    {
    	global $PowerBB;

    	if (!$no_header and $no_style)
    	{
    		$this->ShowHeader('error');
    	}

  		$this->msg($msg,$no_style);
  		$this->stop($no_style);
 	}

	function rename_field($param)
	{
		global $PowerBB;

		$query = $PowerBB->DB->sql_query('ALTER TABLE ' . $param['old_name'] . ' RENAME ' . $param['new_name']);

		return ($query) ? true : false;
	}

	function drop_field($param)
	{
		global $PowerBB;

		$query = $PowerBB->DB->sql_query("ALTER TABLE " . $param['table_name'] . " DROP " . $param['field_name']);

		return ($query) ? true : false;
	}

	function change_field($param)
	{
		global $PowerBB;

		$query = $PowerBB->DB->sql_query("ALTER TABLE " . $param['table_name'] . " CHANGE " . $param['field_name'] . " " . $param['field_name'] . " " . $param['change']);

		return ($query) ? true : false;
	}

	function create_table($param)
	{
		global $PowerBB;

		$sql_statement = 'CREATE TABLE ' . $param['table_name'] . ' (';

		$x = 0;
		$z = sizeof($param['fields']);

		foreach ($param['fields'] as $f)
		{
			$sql_statement .= $f;

		    if ($x < $z-1)
		    {
		    	$x += 1;

		       	$sql_statement .= ',';
		    }
		}

		$sql_statement .= ') ENGINE = MYISAM AUTO_INCREMENT=1';

		$query = $PowerBB->DB->sql_query($sql_statement);

		return ($query) ? true : false;
	}

	function drop_table($table_name)
	{
		global $PowerBB;

		$query = $PowerBB->DB->sql_query("DROP TABLE " . $table_name);

		return ($query) ? true : false;
	}

	function truncate_table($table_name)
	{
		global $PowerBB;

		$query = $PowerBB->DB->sql_query("TRUNCATE TABLE " . $table_name);

		return ($query) ? true : false;
	}

	function rename_table($old,$new)
	{
		global $PowerBB;

		$query = $PowerBB->DB->sql_query("RENAME TABLE " . $old  . " TO " . $new);

		return ($query) ? true : false;
	}



function xml_array($contents, $get_attributes=1, $priority = 'tag')
 {
	     global $PowerBB;

    if(!$contents) return array();

    if(!function_exists('xml_parser_create')) {
        //print "'xml_parser_create()' function not found!";
        return array();
    }

    //Get the XML parser of PHP - PHP must have this module for the parser to work
    $parser = xml_parser_create('');
    xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8"); # http://minutillo.com/steve/weblog/2004/6/17/php-xml-and-character-encodings-a-tale-of-sadness-rage-and-data-loss
    xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
    xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
    xml_parse_into_struct($parser, trim($contents), $xml_values);
    xml_parser_free($parser);

    if(!$xml_values) return;//Hmm...

    //Initializations
    $xml_array = array();
    $parents = array();
    $opened_tags = array();
    $arr = array();

    $current = &$xml_array; //Refference

    //Go through the tags.
    $repeated_tag_index = array();//Multiple tags with same name will be turned into an array
    foreach($xml_values as $data) {
        unset($attributes,$value);//Remove existing values, or there will be trouble

        //This command will extract these variables into the foreach scope
        // tag(string), type(string), level(int), attributes(array).
        extract($data);//We could use the array by itself, but this cooler.

        $result = array();
        $attributes_data = array();

        if(isset($value)) {
            if($priority == 'tag') $result = $value;
            else $result['value'] = $value; //Put the value in a assoc array if we are in the 'Attribute' mode
        }

        //Set the attributes too.
        if(isset($attributes) and $get_attributes) {
            foreach($attributes as $attr => $val) {
                if($priority == 'tag') $attributes_data[$attr] = $val;
                else $result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
            }
        }

        //See tag status and do the needed.
        if($type == "open") {//The starting of the tag '<tag>'
            $parent[$level-1] = &$current;
            if(!is_array($current) or (!in_array($tag, array_keys($current)))) { //Insert New tag
                $current[$tag] = $result;
                if($attributes_data) $current[$tag. '_attr'] = $attributes_data;
                $repeated_tag_index[$tag.'_'.$level] = 1;

                $current = &$current[$tag];

            } else { //There was another element with the same tag name

                if(isset($current[$tag][0])) {//If there is a 0th element it is already an array
                    $current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;
                    $repeated_tag_index[$tag.'_'.$level]++;
                } else {//This section will make the value an array if multiple tags with the same name appear together
                    $current[$tag] = array($current[$tag],$result);//This will combine the existing item and the new item together to make an array
                    $repeated_tag_index[$tag.'_'.$level] = 2;

                    if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well
                        $current[$tag]['0_attr'] = $current[$tag.'_attr'];
                        unset($current[$tag.'_attr']);
                    }

                }
                $last_item_index = $repeated_tag_index[$tag.'_'.$level]-1;
                $current = &$current[$tag][$last_item_index];
            }

        } elseif($type == "complete") { //Tags that ends in 1 line '<tag />'
            //See if the key is already taken.
            if(!isset($current[$tag])) { //New Key
                $current[$tag] = $result;
                $repeated_tag_index[$tag.'_'.$level] = 1;
                if($priority == 'tag' and $attributes_data) $current[$tag. '_attr'] = $attributes_data;

            } else { //If taken, put all things inside a list(array)
                if(isset($current[$tag][0]) and is_array($current[$tag])) {//If it is already an array...

                    // ...push the new element into that array.
                    $current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;

                    if($priority == 'tag' and $get_attributes and $attributes_data) {
                        $current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;
                    }
                    $repeated_tag_index[$tag.'_'.$level]++;

                } else { //If it is not an array...
                    $current[$tag] = array($current[$tag],$result); //...Make it an array using using the existing value and the new value
                    $repeated_tag_index[$tag.'_'.$level] = 1;
                    if($priority == 'tag' and $get_attributes) {
                        if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well

                            $current[$tag]['0_attr'] = $current[$tag.'_attr'];
                            unset($current[$tag.'_attr']);
                        }

                        if($attributes_data) {
                            $current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;
                        }
                    }
                    $repeated_tag_index[$tag.'_'.$level]++; //0 and 1 index is already taken
                }
            }

        } elseif($type == 'close') { //End of tag '</tag>'
            $current = &$parent[$level-1];
        }
    }

    return($xml_array);
 }


}

header('Content-Type: text/html; charset=UTF-8');
$langchoose = $PowerBB->_POST['lang'].$PowerBB->_GET['lang'];
	if ($langchoose=='en')
	{
	$PowerBB->html->lang 					= 	array();
	$PowerBB->html->lang['direction']		=	'ltr';
	$PowerBB->html->lang['languagecode']	=	'en';
	$PowerBB->html->lang['charset']		=	'UTF-8';
	$PowerBB->html->lang['yes']			=	'Yes';
	$PowerBB->html->lang['align']			=	'left';
	$PowerBB->html->lang['no']			=	'No';
	$PowerBB->html->lang['send']			=	'Continued';
	$PowerBB->html->lang['reset']			=	'reset fields';
	}
	elseif ($langchoose=='ar')
	{
	$PowerBB->html->lang 					= 	array();
	$PowerBB->html->lang['direction']		=	'rtl';
	$PowerBB->html->lang['languagecode']	=	'ar';
	$PowerBB->html->lang['charset']		=	'UTF-8';
	$PowerBB->html->lang['yes']			=	'نعم';
	$PowerBB->html->lang['align']			=	'right';
	$PowerBB->html->lang['no']			=	'لا';
	$PowerBB->html->lang['send']			=	'استكمال..';
	$PowerBB->html->lang['reset']			=	'اعادة الحقول';
	}
	else
	{
	$PowerBB->html->lang 					= 	array();
	$PowerBB->html->lang['direction']		=	'rtl';
	$PowerBB->html->lang['languagecode']	=	'en';
	$PowerBB->html->lang['charset']		=	'UTF-8';
	$PowerBB->html->lang['yes']			=	'نعم';
	$PowerBB->html->lang['align']			=	'right';
	$PowerBB->html->lang['no']			=	'لا';
	$PowerBB->html->lang['send']			=	'موافق - Continued';
	$PowerBB->html->lang['reset']			=	'اعادة الحقول';
    }
$PowerBB->html->stylesheet = '../setup.css';

?>
