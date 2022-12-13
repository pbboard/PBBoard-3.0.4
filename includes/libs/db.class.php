<?php

class PowerBBSQL
{
	var $host			=	'localhost';
	var $db_username	=	'';
	var $db_password	=	'';
	var $db_name		=	'';
	var $db_type		=	'';
	var $db_encodinge	=	'';
	var $use_pconnect	=	true;
	var $debug			=	false;
	var $store_queries	=	false;
	var $queries		=	array();
	var $number;
	var $_from;
	var $_query;
	var $_x = 0;

	function SetInformation($host,$db_username,$db_password,$db_name,$db_type,$db_encodinge)
	{
		$this->host        = $host;
		$this->db_username = $db_username;
		$this->db_password = $db_password;
		$this->db_name     = $db_name;
		$this->db_type     = $db_type;
		if(empty($db_encodinge))
		{
		$this->encoding     = 'utf8mb4';
		}
		else
		{
		$this->encoding     = $db_encodinge;
		}
	}

	function SetDebug($debug)
	{
		$this->debug = $debug;
	}

	function SetQueriesStore($store)
	{
		$this->store_queries = $store;
	}

	function GetQueriesNumber()
	{
		return $this->number;
	}

	function GetQueriesArray()
	{
		return $this->queries;
	}

	function sql_connect()
	{

		if(function_exists('mysql_connect'))
		{
			$function = 'mysql_connect';
		}
		elseif(!function_exists('mysql_connect'))
		{
			$function = 'mysql_pconnect';
		}
		else
		{
          die ('function mysql_pconnect() And mysql_connect() called though not supported in the PHP build');
		}

        $connect = $function($this->host,$this->db_username,$this->db_password);

		$this->_from = 'connect';

		if (!$connect)
		{
			$this->_error();
		}

		return $connect;
	}

	function sql_select_db()
	{
		$select = mysql_select_db($this->db_name);
        @mysql_query("set character_set_server='".$this->encoding."'");
        @mysql_query("set names '".$this->encoding."'");
		@mysql_query("SELECT @@session.sql_mode=''", $this->sql_connect);

		$this->_from = 'select';

		if (!$select)
		{
			$this->_error();
		}

		return $select;
	}

	function sql_close()
	{
		$close = mysql_close();

		$this->_from = 'close';

		if (!$close)
		{
			$this->_error();
		}

		return $close;
	}

	function sql_query($query)
	{
        global $PowerBB;
		$result = mysql_query($query);

		$this->_from = 'query';

		if (!$result)
		{
			$this->_query = $query;

			$this->_error();
		}

       /*
		$PowerBB->_CONF['temp']['query_numbers']++;

		$PowerBB->_CONF['temp']['queries'][] = $query;

		if ($this->debug)
		{
			if ($this->store_queries)
			{
				$this->queries[$this->_x++] = $query;
			}
		}
        */
		return $result;
		unset($result);
		$this->sql_free_result($result);

	}

	function sql_unbuffered_query($query)
	{
		$result = mysql_unbuffered_query($query);

		$this->_from = 'query';

		if (!$result)
		{
			$this->_query = $query;

			$this->_error();
		}

        /*
		$PowerBB->_CONF['temp']['query_numbers']++;
		$PowerBB->_CONF['temp']['queries'][] = $query;

		if ($this->debug)
		{
			if ($this->store_queries)
			{
				$this->queries[$this->_x++] = $query;
			}
		}
        */
		return $result;
		unset($result);
		$this->sql_free_result($result);
	}

	function sql_fetch_array($result)
	{
		$out = mysql_fetch_array($result,MYSQL_ASSOC);

		return $out;
		unset($result);
		$this->sql_free_result($result);
	}

	function sql_fetch_assoc($result)
	{
		$out = mysql_fetch_assoc($result,MYSQL_ASSOC);
		return $out;
		$this->sql_free_result($result);
	}

	function sql_num_rows($result)
	{
		$out = mysql_num_rows($result);

		return $out;
		unset($result);
		$this->sql_free_result($result);
	}

	function sql_insert_id()
	{
		$out = mysql_insert_id();

		return $out;
	}


	function sql_free_result($result)
	{
	  $free = mysql_free_result($result);
      return $free;
	}


	function check($table)
	{
	    $query = ("SELECT * FROM " . $table . "");
		$result = mysql_query($query);

		if ($result)
		{
		 return true;
		}
		else
		{
		 return false;
		}
	}

	function simple_error()
	{

	 $error_msg = mysql_close();
	 return $error_msg;	}

	function _error()
	{
		$error_no  = mysql_errno();
		$error_msg = mysql_error();

		echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';

		echo '<html dir="rtl" xmlns="http://www.w3.org/1999/xhtml" xml:lang="ar" lang="ar">';

		echo '<head>';
		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
		echo '<title>Database Errorت</title>';
		echo '
	<style type="text/css">
	<!--
	body { background-color: white; color: black; }
	#container { width: 400px; }
	.message   { width: 450px; color: black; background-color: #FFFFCC; }
	#bodytitle { width: 400px; font: 13pt/15pt verdana, arial, sans-serif; height: 35px; vertical-align: top; }
	#bodytext  { width: 400px; font: 8pt/11pt verdana, arial, sans-serif; }
	#erytext     { font: 8pt/11pt Tahoma, arial, sans-serif; color: #4e4e4e; }
	a:visited  { font: 8pt/11pt verdana, arial, sans-serif; color: #4e4e4e; }
	-->
	</style>';
		echo '</head>';
		echo '<body dir="ltr">';
		echo "<br />";
		echo '<div id="bodytitle"><font color="#000080">PBBoard:</font>';
		echo "Database error </div><div id='bodytext'> The database has encountered a problem.<br /> <hr /> </div><div id='erytext'>";
		echo "An error has occurred with databases<br />";

		if (!empty($this->_from))
		{
			echo 'The cause of the error: <font color="#000080"><b>';

			if ($this->_from == 'connect')
			{
				echo 'Contact databases';
			}
			elseif ($this->_from == 'select')
			{
				echo 'Choose a database';
			}
			elseif ($this->_from == 'close')
			{
				echo 'Mysql Close Contact';
			}
			elseif ($this->_from == 'query')
			{
				echo 'Query';
			}
			else
			{
				echo 'Unknown';
			}

			echo "</b></font></div><br />";
		}

       echo '<textarea name="error" rows="4" cols="70%" wrap="virtual" class="message">';
		echo 'Error Number:' . $error_no . "\n";
		echo 'Error Message:'. $error_msg . "\n";

		if($this->_from == 'query')
		{
			echo "Error Query:";
			echo $this->_query;
		}

        echo '</textarea>';
		echo '</body>';
		exit();
	}
}
?>
