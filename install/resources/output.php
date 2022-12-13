<?php
/**
 * PBBoard 3.3
 * Copyright 2019 PBBoard Group, All Rights Reserved
 *
 * Website: http://www.pbboard.info
 * License: https://www.pbboard.info/about/license
 *
 */

class installerOutput {

	/**
	 * @var bool
	 */
	public $doneheader;
	/**
	 * @var bool
	 */
	public $openedform;
	/**
	 * @var string
	 */
	public $script = "index.php";
	/**
	 * @var array
	 */
	public $steps = array();
	/**
	 * @var string
	 */
	public $title = "PBBoard Installation Wizard";


	/**
	 * @param string $title
	 * @param string $image
	 * @param int    $form
	 * @param int    $error
	 */
	function print_header($title="Welcome", $image="welcome", $form=1, $error=0)
	{
		global $PBBoard;

		if($lang->title)
		{
			$this->title = $lang->title;
		}
		@header("Content-type: text/html; charset=utf-8");

		$this->doneheader = 1;
		$dbconfig_add = '';
		if($image == "dbconfig")
		{
			$dbconfig_add = "<script type=\"text/javascript\">document.write('<style type=\"text/css\">.db_type { display: none; }</style>');</script>";
		}
		echo <<<END
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>{$this->title} &gt; {$title}</title>
	<link rel="stylesheet" href="stylesheet.css" type="text/css" />
	<script type="text/javascript" src="../jscripts/jquery.js"></script>
	{$dbconfig_add}
</head>
<body>
END;
		if($form)
		{
			echo "\n	<form method=\"post\" action=\"".$this->script."\">\n";
			$this->openedform = 1;
		}

		echo <<<END
		<div id="container">
		<div id="logo">
			<h1><span class="invisible">PBBoard</span></h1>
		</div>
		<div id="inner_container">
		<div id="header">{$this->title}</div>
END;
		if($PBBoard->version_code >= 1700 && $PBBoard->version_code < 1800)
		{
			echo "<div class=\"error\"><h2 class=\"fail\">Warning</h2><p>This version of PBBoard is a development preview and is to be used for testing purposes only.</p><p>No official support, other than for plugins and theme development, will be provided for this version. By continuing with this install/upgrade you do so at your own risk.</p></div>";
		}
		if(empty($this->steps))
		{
			$this->steps = array();
		}
		if(is_array($this->steps))
		{
		echo "\n		<div id=\"progress\">";
				echo "\n			<ul>\n";
				foreach($this->steps as $action => $step)
				{
					if($action == $PBBoard->input['action'])
					{
						echo "				<li class=\"{$action} active\"><strong>{$step}</strong></li>\n";
					}
					else
					{
						echo "				<li class=\"{$action}\">{$step}</li>\n";
					}
				}
				echo "			</ul>";
		echo "\n		</div>";
		echo "\n		<div id=\"content\">\n";
		}
		else
		{
		echo "\n		<div id=\"progress_error\"></div>";
		echo "\n		<div id=\"content_error\">\n";
		}
		if($title != "")
		{
		echo <<<END
			<h2 class="{$image}">{$title}</h2>\n
END;
		}
	}

	/**
	 * @param string $contents
	 */
	function print_contents($contents)
	{
		echo $contents;
	}

	/**
	 * @param string $message
	 */
	function print_error($message)
	{
		if(!$this->doneheader)
		{
			$this->print_header('Error', "", 0, 1);
		}
		echo "			<div class=\"error\">\n				";
		echo "<h3>Error</h3>";
		$this->print_contents($message);
		echo "\n			</div>";
		$this->print_footer();
	}

	/**
	 * @param string $nextact
	 */
	function print_footer($nextact="")
	{
		global $footer_extra;
		if($nextact && $this->openedform)
		{
			echo "\n			<input type=\"hidden\" name=\"action\" value=\"{$nextact}\" />";
			echo "\n				<div id=\"next_button\"><input type=\"submit\" class=\"submit_button\" value=\"Next &raquo;\" /></div><br style=\"clear: both;\" />\n";
			$formend = "</form>";
		}
		else
		{
			$formend = "";
		}

		echo <<<END
		</div>
		<div id="footer">
END;

		$copyyear = date('Y');
		echo <<<END
			<div id="copyright">
				PBBoard &copy; 2009-{$copyyear} PBBoard Group
			</div>
		</div>
		</div>
		</div>
		{$formend}
		{$footer_extra}
</body>
</html>
END;
		exit;
	}


}
