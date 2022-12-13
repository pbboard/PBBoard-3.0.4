<?php
class PowerBBSQL
{
	var $host			=	'localhost';
	var $db_username	=	'';
	var $db_password	=	'';
	var $db_name		=	'';
	var $db_type		=	'';
	var $db_encodinge	=	'';
	var $db_prefix		=	'';
	var $use_pconnect	=	true;
	var $error_query	=	true;
	var $debug			=	false;
	var $store_queries	=	false;
	var $queries		=	array();
	var $number;
	var $_from;
	var $_query;
	var $_mysqli_err_no;
	var $_mysqli_err_msg;
	var $_x = 0;

	function SetInformation($host,$db_username,$db_password,$db_name,$db_type,$db_encodinge,$db_prefix)
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
		$this->db_prefix     = $db_prefix;
        $this->UserAdmin     = $_SESSION[$PowerBB->_CONF['username_cookie']];
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

		if(function_exists('mysqli_connect'))
		{
		    $this->connect = mysqli_init();

            $this->connect = @mysqli_connect($this->host,$this->db_username,$this->db_password,$this->db_name);
            @mysqli_set_charset($this->connect, $this->encoding);
            $result = @mysqli_query($this->connect, "SET @@session.sql_mode=''");
		}
		else
		{
          die ('mysqli_connect function does not exist, is mysqli extension installed?');
		}

		if (!$this->connect)
		{
	    	$this->_from = 'connect';
			$this->_error();
		}

		return $this->connect;
	}

	function sql_select_db()
	{
		$select = @mysqli_select_db($this->sql_connect(),$this->db_name);
		/* change character set to utf8mb4 */
       // mysqli_query("set character_set_server='utf8mb4'");
       // mysqli_query("set names 'utf8mb4'");

		if (!$select)
		{
		    $this->_from = 'select';
			$this->_error();
		}

		return $select;
	}

	function sql_close()
	{
		$close = mysqli_close($this->sql_connect());

		if (!$close)
		{
		    $this->_from = 'close';
			$this->_error();
		}
		return $close;
	}

	function sql_query($query)
	{
        global $PowerBB;
		$result = @mysqli_query($this->sql_connect(),$query);
		if (!$result)
		{
		$this->_mysqli_err_no = mysqli_errno($this->sql_connect());
		$this->_mysqli_err_msg = mysqli_error($this->sql_connect());
		$this->_query = $query;
		$this->_from = 'query';
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
	}

	function sql_unbuffered_query($query)
	{
		$result = mysqli_query($this->sql_connect(),$query);
		if (!$result)
		{
		$this->_from = 'query';
		$this->_query = $query;
		$this->_mysqli_err_no = mysqli_errno($this->sql_connect());
		$this->_mysqli_err_msg = mysqli_error($this->sql_connect());
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
		$this->sql_free_result($result);
	}

	function sql_fetch_array($result)
	{
		$out = mysqli_fetch_array($result);
		return $out;
		$this->sql_free_result($result);
	}

	function sql_fetch_assoc($result)
	{
		$out = mysqli_fetch_assoc($result);
		return $out;
		$this->sql_free_result($result);
	}

	function sql_num_rows($result)
	{
		$out = mysqli_num_rows($result);
		return $out;
		$this->sql_free_result($result);
	}

	function sql_insert_id()
	{
		return ($this->connect) ? @mysqli_insert_id($this->connect) : false;

	}


	function sql_free_result($result)
	{
	 if(!empty($result))
	  {
	 // $free = mysqli_free_result($result);
	  $free = $this->sql_close($result);
	  return $free;
	  }
	}

	function check($table)
	{
	    $query = ("SELECT * FROM " . $table . "");
		$result = mysqli_query($this->sql_connect(),$query);

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
	 $error_sql = mysqli_error();

	 return $error_sql;
	}

	function _error()
	{
      global $PowerBB;


        $UserAdmin = $this->UserAdmin;
		$error_no = $this->_mysqli_err_no;
		$error_msg = $this->_mysqli_err_msg;

		echo '<div class="clear"></div><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';

		echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ar" lang="ar">';

		echo '<head>';
		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
		echo '<title>Database Errorت</title>';
		echo '
	<style type="text/css">
	body { background-color: white; color: black;}
	#container { width: 400px; }
	.message   { width: 450px; color: black; background-color: #FFFFCC;  font: 8pt/11pt verdana, arial, sans-serif;}
	#bodytitle { width: 400px; font: 13pt/15pt verdana, arial, sans-serif; height: 35px; vertical-align: top; }
	#bodytext  { width: 400px; font: 8pt/11pt verdana, arial, sans-serif; }
	#erytext     { font: 8pt/11pt Tahoma, arial, sans-serif; color: #4e4e4e; }
	a:visited  { font: 8pt/11pt verdana, arial, sans-serif; color: #4e4e4e; }
	.clear { clear:both; }
	.direct { direction:ltr; }

	</style>';
		echo '</head>';
		echo '<body><div class="direct">';
		echo "<br /><div class='clear'></div>";
		echo '<div id="bodytitle"><font color="#000080">PBBoard:</font>';
		echo "Database error </div><div id='bodytext'> The database has encountered a problem.<br /> <hr /> </div><div id='erytext'>";
		echo "An error has occurred with databases<br />";

		if (!empty($this->_from))
		{
			echo 'The cause of the error: <font color="#000080">';

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
				echo 'Mysqli Close Contact';
			}
			elseif ($this->_from == 'query')
			{
				echo 'Query';
			}
			else
			{
				echo 'Unknown';
			}

			echo "</font></div><br />";
		}
       if (isset($UserAdmin))
        {
           $UserAdmin = trim($UserAdmin);
           $UserAdmin = strip_tags($UserAdmin);
        }

			$Rows = $this->sql_query("SELECT usergroup  FROM " . $this->db_prefix.'member' . " WHERE username = '".$UserAdmin."' ");
			$MemberRows = $this->sql_fetch_array($Rows);

		       echo '<textarea name="error" rows="4" cols="70%" wrap="virtual" class="message">';
				if ($this->_from == 'connect')
				{
			      echo "Error Number: " . mysqli_connect_errno()."\n";
			      echo "Error Message: " . mysqli_connect_error();
				}
				else
				{
				echo 'Error Number:' . $error_no . "\n";
				//echo 'Error Message:' ."\n". $error_msg . "\n";
				}

				if($this->_from == 'query')
				{
					echo "Error Query: ";
					echo $this->_query;
				}

		        echo '</textarea>';

		   echo '<div class="clear"></div></div></body>';
		   exit();

	}
}
?>
