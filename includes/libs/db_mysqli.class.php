<?php
class PowerBBSQL
{
    var $host           = 'localhost';
    var $db_username    = '';
    var $db_password    = '';
    var $db_name        = '';
    var $db_type        = '';
    var $db_encoding    = 'utf8mb4';
    var $db_prefix      = '';
    var $connect        = null;
    var $_from;
    var $_query;
    var $query_count = 0;
    var $debug          = false;
    var $_mysqli_err_no;
    var $_mysqli_err_msg;
    var $UserAdmin;

    function SetInformation($host, $db_username, $db_password, $db_name, $db_type, $db_encoding, $db_prefix)
    {
        $this->host        = $host;
        $this->db_username = $db_username;
        $this->db_password = $db_password;
        $this->db_name      = $db_name;
        $this->db_type      = $db_type;
        $this->db_encoding     = empty($db_encoding) ? 'utf8mb4' : $db_encoding;
        $this->db_prefix    = $db_prefix;
        $this->UserAdmin    = isset($_SESSION['username_cookie']) ? $_SESSION['username_cookie'] : null;
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
		return $this->query_count;
	}

	function GetQueriesArray()
	{
		return array();
	}

  function sql_connect()
    {
        if (function_exists('mysqli_connect')) {
         // Enable error hiding and manual exception throwing to control formatting
            mysqli_report(MYSQLI_REPORT_OFF);

            try {
                $this->connect = @mysqli_connect($this->host, $this->db_username, $this->db_password, $this->db_name);

                if (!$this->connect) {
                    throw new Exception(mysqli_connect_error(), mysqli_connect_errno());
                }

                @mysqli_set_charset($this->connect, $this->db_encoding);
                @mysqli_query($this->connect, "SET @@session.sql_mode=''");
            } catch (Exception $e) {
                $this->_from = 'connect';
                $this->_mysqli_err_no = $e->getCode();
                $this->_mysqli_err_msg = $e->getMessage();
                $this->_error();
            }
        } else {
            die('mysqli_connect function does not exist, is mysqli extension installed?');
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
        $link = $this->sql_connect();
		$result = @mysqli_query($link,$query);
		if (!$result)
		{
		$this->_mysqli_err_no = mysqli_errno($link);
		$this->_mysqli_err_msg = mysqli_error($link);
		$this->_query = $query;
		$this->_from = 'query';
		$this->_error();
        }

		if (isset($PowerBB->_CONF['info_row']['show_debug_info']) && $PowerBB->_CONF['info_row']['show_debug_info'])
		{
         $this->query_count++;
		}

		return $result;
	}

	function sql_unbuffered_query($query)
	{
	    global $PowerBB;
	    $link = $this->sql_connect();
		$result = mysqli_query($link,$query);
		if (!$result)
		{
		$this->_from = 'query';
		$this->_query = $query;
		$this->_mysqli_err_no = mysqli_errno($link);
		$this->_mysqli_err_msg = mysqli_error($link);
		$this->_error();
        }

		if (isset($PowerBB->_CONF['info_row']['show_debug_info']) && $PowerBB->_CONF['info_row']['show_debug_info'])
		{
         $this->query_count++;
		}

		return $result;
	}

	function sql_fetch_array($result)
	{
		$out = mysqli_fetch_array($result);
		return $out;
	}

	function sql_fetch_assoc($result)
	{
		$out = mysqli_fetch_assoc($result);
		return $out;
	}

	function sql_fetchrow($result)
	{
		$out = mysqli_fetch_assoc($result);
		return $out;
	}
	function sql_fetch_row($result)
	{
		$rows = mysqli_fetch_row($result);
		return $rows[0];
	}

	function sql_num_rows($result)
	{
		$out = mysqli_num_rows($result);
		return $out;
	}

	function sql_insert_id()
	{
	 return ($this->connect) ? @mysqli_insert_id($this->connect) : false;
	}


	function sql_free_result($result)
	{
	    if ($result instanceof mysqli_result) {
	        mysqli_free_result($result);
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

    function sql_escape($text)
	{
		return mysqli_real_escape_string($this->sql_connect(), $text);
	}


	function simple_error()
	{
	 $error_sql = mysqli_error();

	 return $error_sql;
	}

   function _error()
	{
      while (ob_get_level()) {
        ob_end_clean();
     }

		if ($this->_from == 'connect') {
			$error_no = mysqli_connect_errno();
			$error_msg = mysqli_connect_error();
		} else {
			$error_no = $this->_mysqli_err_no;
			$error_msg = $this->_mysqli_err_msg;
		}

		$plain_text_error = "Technical Error Details:\n";
		$plain_text_error .= "Source: " . $this->get_error_source() . "\n";
		$plain_text_error .= "Number: " . $error_no . "\n";
		$plain_text_error .= "Message: " . $error_msg;
		if (!empty($this->_query)) {
			$plain_text_error .= "\nQuery: " . $this->_query;
		}

		echo '<!DOCTYPE html>
		<html dir="rtl" lang="ar">
		<head>
			<meta charset="utf-8" />
			<title>خطأ في النظام</title>
			<style type="text/css">
				body { background-color: #f7f9fb; font-family: Tahoma, Arial, sans-serif; color: #444; margin: 0; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
				.error-box { background: #fff; width: 95%; max-width: 550px; padding: 40px; border-radius: 15px; box-shadow: 0 10px 40px rgba(0,0,0,0.08); text-align: center; border-bottom: 5px solid #ebeef1; }
				.icon { width: 80px; height: 80px; background: #fff5f5; color: #e74c3c; font-size: 40px; line-height: 80px; border-radius: 50%; margin: 0 auto 20px; display: inline-block; }
				h1 { font-size: 20px; color: #2c3e50; margin: 0 0 15px; }
				p { font-size: 15px; line-height: 1.6; color: #7f8c8d; margin-bottom: 30px; }

				/* تصميم التفاصيل المخفية */
				.debug-trigger { color: #006ea0; cursor: pointer; font-size: 11px; text-decoration: none; transition: 0.3s; }
				.debug-trigger:hover { color: #7f8c8d; }
				.debug-content { display: none; margin-top: 20px; text-align: right; background: #2d3436; color: #fab1a0; padding: 15px; border-radius: 8px; font-family: monospace; font-size: 13px; line-height: 1.5; overflow-x: auto; direction: ltr; position: relative; }

				.copy-btn { background: #444; color: #fff; border: 1px solid #666; padding: 4px 8px; font-size: 10px; cursor: pointer; border-radius: 4px; position: absolute; top: 10px; left: 16px; font-family: Tahoma; transition: 0.3s; }
				.copy-btn:hover { background: #555; border-color: #fab1a0; }

				.footer { font-size: 12px; color: #bdc3c7; margin-top: 25px; border-top: 1px solid #f1f1f1; padding-top: 15px; }
			</style>
		</head>
		<body>
			<div class="error-box">
				<div class="icon">!</div>
				<h1>' . $this->get_error_source() . '</h1>
				<p>نواجه حالياً بعض التحديثات التقنية الضرورية على خوادم البيانات. يرجى محاولة زيارة الموقع بعد قليل.</p>

				<span class="debug-trigger" onclick="document.getElementById(\'debug\').style.display=\'block\'">عرض تفاصيل الخطأ في النظام</span>

				<div id="debug" class="debug-content">
					<button class="copy-btn" onclick="copyError()">نسخ التفاصيل</button>
					<div style="direction:rtl; text-align:right; margin-bottom:10px; border-bottom:1px solid #444; padding-bottom:5px; color:#fff;">
						<strong>مصدر الخطأ:</strong> ' . $this->get_error_source() . '
					</div>
					<div id="full_error_text" style="direction:ltr; text-align:left; margin-bottom:10px; border-bottom:1px solid #444; padding-bottom:5px; color:#fff;">
					<strong>[Technical Error Details]</strong><br>
					Error Number: ' . $error_no . '<br>
					Error Message: ' . $error_msg . '';

					if (!empty($this->_query)) {
						echo '<br><hr style="border:0; border-top:1px solid #444; margin:10px 0;">Query: ' . htmlspecialchars($this->_query);
					}
		echo '</div> </div>

				<script>
				function copyError() {
					const errorText = ' . json_encode($plain_text_error) . ';
					navigator.clipboard.writeText(errorText).then(() => {
						const btn = document.querySelector(".copy-btn");
						btn.innerText = "تم النسخ!";
						setTimeout(() => btn.innerText = "نسخ التفاصيل", 2000);
					});
				}
				</script>

				<div class="footer">
					نظام PBBoard &copy;
				</div>
			</div>
		</body>
		</html>';
		exit();
	}

	/**
	 * دالة مساعدة لتحديد نوع الخطأ بالعربية
	 */
	private function get_error_source()
	{
		switch ($this->_from) {
			case 'connect': return 'فشل الاتصال بالخادم (Connect)';
			case 'select':  return 'فشل اختيار قاعدة البيانات (Select DB)';
			case 'query':   return 'خطأ في الاستعلام (Query)';
			case 'close':   return 'خطأ في إغلاق الاتصال (Close)';
			default:        return 'غير معروف';
		}
	}

}
?>
