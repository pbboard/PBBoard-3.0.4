<?php
error_reporting(E_ERROR | E_PARSE);
/**
 * PowerBBdisplay
 * Started : 17-10-2006 , 11:46 PM
 * Update By Abu Rakan  : 14-2-2010 , 11:46 PM
 * Version : 0.1
 * Update By Abu Rakan  : 10-9-2012 , 02:32 AM
 */

class PowerBBdisplay
{
	var $options	= array();
	var $javascript	= null;
	var $stylesheet	= null;
	var $colspan	= 2;
	var $lang		= array();

	function page_header($title = '',$is_body=true)
	{
		echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\r\n";
		echo "<html dir=\"" . $this->lang['direction'] . "\" xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"" . $this->lang['languagecode'] . "\" lang=\"" . $this->lang['languagecode'] . "\">\r\n";

		echo "<head>\r\n\t";
		echo "<title>" . ($title ? "$title - " : '') . "(Powered By PBBoard)</title>\r\n\t";
		echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=" . $this->lang['charset'] . "\" />\r\n";

		if (!empty($this->javascript))
		{
			if (is_array($this->javascript))
			{
				foreach ($this->javascript as $js_file)
				{
					echo "\t<script src=\"" . $js_file . "\" type=\"text/javascript\" language=\"javascript\"></script>\r\n";
				}
			}
			else
			{
				echo "\t<script src=\"" . $this->javascript . "\" type=\"text/javascript\" language=\"javascript\"></script>\r\n";
			}
		}

		if (!empty($this->stylesheet))
		{
			if (is_array($this->stylesheet))
			{
				foreach ($this->stylesheet as $css_file)
				{
					echo "\t<link rel=\"stylesheet\" href=\"" . $css_file . "\" type=\"text/css\" />\r\n";
				}
			}
			else
			{
				echo "\t<link rel=\"stylesheet\" href=\"" . $this->stylesheet . "\" type=\"text/css\" />\r\n";
			}
		}


		echo "</head>\r\n";
       	echo "<body>\r\n";

		// We use this when we use frame only
		/*
		if (!$is_body)
		{
			echo "<body>\r\n";
		}
       */
	}

	function page_body($body)
	{
		// We use this when we use frame only
		echo $body;
	}


	function page_footer($footer)
	{
		echo $footer;
	}

	function open_table($width = '90%', $class = false, $border = 0, $cellpadding = 3, $cellspacing = 1, $align = 'center', $param = null)
	{
		if($param['class'])
		{
			$class = $param['class'];
			unset($param['class']);
		}
		else if($class)
		{
			$class = "t_style_b";
		}

		echo "<table cellpadding=\"$cellpadding\" cellspacing=\"$cellspacing\" width=\"$width\" class=\"$class\" border=\"$border\" align=\"$align\"" . (count($param) > 0 ? ' ' . $this->elements($elements) : '') . ">\n";
	}

	function close_table($text = '', $class = '')
	{
		if ($text)
		{
			echo "<tr>\n\t<td" . ($class ? " class=\"$class\"" : '') . "" . ($this->colspan != 1 ? " colspan=\"$this->colspan\"" : '') . " align=\"center\">$text</td>\n</tr>\n";
		}

		echo "</table><br />\n";
	}

	function table_break($text = '', $width = '90%', $class = false, $border = 0, $cellpadding = 3, $cellspacing = 1, $align = 'center', $param = null)
	{
		$this->close_table();

		if ($text)
		{
			echo "\n$text\n\n\n<br />\n";
		}

		$this->open_table($width, $class, $border, $cellpadding, $cellspacing, $align, $param);
	}

	function open_form($phpscript, $method = 'post', $action = '', $uploadform = false, $name = 'myform', $width = '90%', $class = true, $border = 0)
	{
		$this->hidden = array();
		echo "\n<form action=\"$phpscript\" " . ($uploadform ? "enctype=\"multipart/form-data\" " : '') . " name=\"$name\" method=\"$method\">\n";

		if($action)
		{
			echo "<input type=\"hidden\" name=\"action\" value=\"" . ($action) . "\" />\n";
		}

		//$this->open_table($width, $class, $border);
	}

	function close_form($mysubmit = 'send', $resetname = '', $goback = '', $class = 'submit', $name = 'submit')
	{
		echo "<div align=\"center\">";

		$button1 = '';
		if(is_array($mysubmit))
		{
			foreach ($mysubmit as $name => $val)
			{
				$val = ($this->lang["$val"] ? $this->lang["$val"] : $val);
				$button1 .= "\n\t<input" . ($class ? " class=\"$class\"" : '') . " type=\"submit\" value=\"   $val   \" name=\"$name\" accesskey=\"s\" />";
			}
		}
		else
		{
			if($mysubmit != '')
			{
				$mysubmit = ($this->lang["$mysubmit"] ? $this->lang["$mysubmit"] : $mysubmit);
				$button1 = "\n\t<input class=\"$class\" type=\"submit\" value=\"   $mysubmit   \" name=\"$name\" accesskey=\"s\" />";
			}
		}

		if ($resetname != '')
		{
			$resetname = ($this->lang["$resetname"] ? $this->lang["$resetname"] : $resetname);
			$button2 = "\n\t<input class=\"resetbutton\" type=\"reset\" value=\"   $resetname   \" />\n";
		}

		if ($goback != '')
		{
			$goback = ($this->lang["$goback"] ? $this->lang["$goback"] : $goback);
			$button3 = "\n\t<input class=\"goback\" type=\"button\" value=\"   $goback   \" onclick=\"history.back(1)\" />\n";
			$this->close_table($button3 . $button2 . $button1, 'submit-buttons');
		}
		else
		{
			$this->close_table($button1 . $button2 . $button3, 'submit-buttons');
		}

		if (is_array($this->hidden))
		{
			foreach($this->hidden as $name => $value)
			{
				echo "<input type=\"hidden\" name=\"$name\" value=\"$value\" />\n";
			}
			$this->hidden = array();
		}
		echo "</form>\n\n\n";
		echo "</div>";
	}

	function addhidden($name, $value = '', $nohtml = true)
	{
		if(is_array($name))
		{
			foreach ($name as $key => $value)
			{
				$this->hidden[$key] = ($nohtml ? htmlspecialchars($value) : $value);
			}
		}
		else
		{
			$this->hidden[$name] = ($nohtml ? htmlspecialchars($value) : $value);
		}
	}

 	function input($name, $value = '', $size = 30, $nohtml = true, $class = '', $text = '',$read_only=false)
	{
		return "\n<input type=\"text\" name=\"$name\" id=\"input_$name\" value=\"" . ($nohtml ? htmlspecialchars($value) : $value) . "\" size=\"$size\"" . ($class != '' ? " class=\"$class\"" : '') . ($read_only ? "readonly=\"readonly\"" : '') . " />&nbsp;$text\n";
	}

 	function button($name, $value = '', $javascript = '', $size = 30, $nohtml = true, $class = '')
	{
		echo "\n<input type=\"button\" id=\"$name\" value=\"" . ($nohtml ? htmlspecialchars($value) : $value) . "\" size=\"$size\"" . ($class != '' ? " class=\"$class\"" : '') . " " . $javascript  . " />\n";
	}

	function lock_input($name, $value = '', $size = 30, $nohtml = true, $class = '', $text = '')
	{
		return $this->input($name,$value,$size,$nohtml,$class,$text,true);
	}

	function password($name, $value = '', $size = 30, $nohtml = true, $class = '', $text = '')
	{
		return "\n<input type=\"password\" name=\"$name\" id=\"input_$name\" value=\"" . ($nohtml ? htmlspecialchars($value) : $value) . "\" size=\"$size\"" . ($class != '' ? " class=\"$class\"" : '') . " />&nbsp;$text\n";
	}

	function textarea($name, $value = '', $rows = 10, $cols = 40, $nohtml = true, $direction = '', $text = '')
	{
		return "\n<textarea name=\"$name\" id=\"textarea_$name\" rows=\"$rows\" cols=\"$cols\" wrap=\"virtual\" dir=\"" . ($direction ? $direction : $this->lang['direction']) . "\">" . ($nohtml ? htmlspecialchars($value) : $value) . "</textarea>&nbsp;$text\n";
	}

	function yesno($name, $value = 1)
	{
	   return "\n<span style=\"white-space:nowrap\">
				<label for=\"radio_$name\"><input type=\"radio\" name=\"$name\" id=\"radio_$name\" value=\"1\"" .  ($value == 1 ? ' checked="checked"' : '') . " />" . $this->lang['yes'] . "</label>
				<label for=\"radio_0_$name\"><input type=\"radio\" name=\"$name\" id=\"radio_0_$name\" value=\"0\"" . ($value == 0 ? ' checked="checked"' : '') . " />" . $this->lang['no'] . "</label>\n\t</span>";
	}

	function checkbox($name, $checked = 1, $value = 1, $labeltext = '', $type = 'checkbox')
	{
		global $label;
		$label++;
		return "\n<label for=\"checkbox_$name$label\"><input type=\"$type\" name=\"$name\" id=\"checkbox_$name$label\" value=\"$value\"" . ($checked == $value ? ' checked="checked"' : '') . " />$labeltext</label>";
	}

	function select($name, $array, $selected = '', $nohtml = false, $size = 0, $multiple = '')
	{
		$select = $this->open_select($name, $size, $multiple);
		$select .= $this->options($array, $selected, $nohtml);
		$select .= $this->close_select();
		return $select;
	}

	function open_select($name, $size = 0, $multiple = '')
	{
		return "\n<select name=\"$name\" id=\"select_$name\"" . ($size ? " size=\"$size\"" : '') . ($multiple ? ' multiple="multiple"' : '') . ">\n";
	}

	function close_select()
	{
		return "</select>\n";
	}

	function options($array, $selectedid = '', $nohtml = false)
	{
		if (is_array($array))
		{
			$options = '';
			foreach($array as $key => $val)
			{
				if (is_array($val))
				{
					$options .= "\t\t<optgroup label=\"" . ($nohtml ? htmlspecialchars($key): $key) . "\">\n";
					$options .= $this->options($val, $selectedid, $nohtml);
					$options .= "\t\t</optgroup>\n";
				}
				else
				{
					if (!is_array($selectedid))
						$selected = ($key == $selectedid ? ' selected="selected"' : '');
					else
						$selected = (in_array($key, $selectedid) ? ' selected="selected"' : '');

					$val = ($this->lang["$val"] ? $this->lang["$val"] : $val);
					$options .= "\t<option value=\"" . $key . "\" $selected>" . ($nohtml ? htmlspecialchars($val) : $val) . "</option>\n";
				}
			}
		}
		return $options;
	}

	function open_table_head($title, $class = '', $colspan = '', $align = 'center', $param = null)
	{
		if($param['class'])
		{
			$class = " class=\"{$param['class']}\"";
			unset($param['class']);
		}

		$title = ($this->lang["$title"] ? $this->lang["$title"] : $title);
		echo "<thead>\n<tr>\n\t<th" . ($class ? " class=\"$class\"" : '') . " align=\"$align\"" . ($this->colspan != 1 ? " colspan=\"" . ($colspan ? $colspan : $this->colspan) . "\"" : '') . "" . (count($param) > 0 ? ' ' . $this->elements($param) : '') . ">$title</th>\n</tr>\n</thead>\n";
	}

	function row($title, $value = '&nbsp;', $class = '', $valign = 'top', $iswidth = false)
	{
		if (!$class)
		{
			$class = $this->get_row_bg();
		}

		$title = ($this->lang["$title"] ? $this->lang["$title"] : $title);
		echo "<tr valign=\"$valign\">
		<td class=\"$class\"" . ($iswidth ? ' width="30%"' : '') . ">$title</td>
		<td class=\"$class\"" . ($iswidth ? ' width="70%"' : '') . ">$value</td>\n</tr>\n";
	}

	function row1($title, $value = '&nbsp;', $class = '', $valign = 'top', $iswidth = false)
	{
		if (!$class)
		{
			$class = $this->get_row_bg();
		}

		$title = ($this->lang["$title"] ? $this->lang["$title"] : $title);
		echo "<tr valign=\"$valign\">
		<td align=\"center\" class=\"$class\"" . ($iswidth ? ' width="70%"' : '') . ">$value</td>\n</tr>\n";
	}

	function &cells($array, $class = null, $column = false, $align = '', $valign = 'top')
	{
		global $colspan, $bgcounter;

		if (is_array($array) and 0 != count($array))
		{
			$colspan = sizeof($array);
			$isecho = false;

			if ($class === null and $column === false)
			{
				$bgclass = $this->get_row_bg();
			}
			else
			{
				$bgclass = $class;
			}

			$bgcounter = ($column !== false ? 0 : $bgcounter);

			$out = "<tr valign=\"$valign\" align=\"center\">\n";

			foreach ($array as $val)
			{
				if ($class === null and $column !== false)
				{
					$bgclass = $this->get_row_bg();
				}

				if (!is_array($val))
				{
					if ($val !== '')
					{
						$isecho = true;
						$value = $val;
					}
					else
					{
						$value = '&nbsp;';
					}
				}
				else
				{
					$value = $val['value'];
				}

				$out .= "\t<td class=\"" . $bgclass . "\"" . ($this->colspan > $colspan ? " colspan=\"$this->colspan\"" : '') . (!empty($align) ? ' align="' . $align . '"' : '');

				if (is_array($val))
				{
					foreach ($val as $v)
					{
						if ($value == $v)
						{
							continue;
						}
						else
						{
							$out .= ' ' . $v;
						}
					}
				}

				$out .= ">$value</td>\n";
			}

			$out .= "</tr>\n";
		}
		else
		{
			$val = $array;

			$isecho = false;

			if ($class === null and $column === false)
			{
				$bgclass = $this->get_row_bg();
			}
			else
			{
				$bgclass = $class;
			}

			$bgcounter = ($column !== false ? 0 : $bgcounter);

			$out = "<tr valign=\"$valign\" align=\"center\">\n";

			if ($class === null and $column !== false)
			{
					$bgclass = $this->get_row_bg();
			}

			if ($val !== '')
				$isecho = true;
			else
				$val = '&nbsp;';

			$out .= "\t<td class=\"" . $bgclass . "\"" . ($this->colspan > $colspan ? " colspan=\"$this->colspan\"" : '') . (!empty($align) ? ' align="' . $align . '"' : '') . ">$val</td>\n";

			$out .= "</tr>\n";
		}
		if ($isecho) echo $out;
	}

	function elements($array)
	{
		if(is_array($array))
		{
			foreach ($array as $name => $value)
			{
				echo " $name=\"$value\"";
			}
		}
	}

	function get_row_bg()
	{
		global $bgcounter;
		return ($bgcounter++ % 2) == 0 ? 'row1' : 'row2';
	}

	function make_link($title,$site,$return=false,$target=null)
	{
	 $replyLink = '<script type="text/javascript">
	 function replyLink() {
      document.getElementById("make_link").style.display = "none";
  }
  </script>';
       echo $replyLink;

		$display = '<a href="' . $site . '"';

		if (!empty($target))
		{
			$display .= ' target="' . $target . '"';
		}

		$display .= '><div onclick="replyLink()" id="make_link">' . $title . '</div></a>';

		if ($return)
		{
			return $display;
		}
		else
		{
			echo $display;
		}
	}

	function frameset_open($rows=false,$cols=false)
	{
		$display = '<frameset ';

		if ($rows != false)
		{
			$display .= 'rows="' . $rows . '" ';
		}

		if ($cols != false)
		{
			$display .= 'cols="' . $cols . '" ';
		}

		$display .= '>';

		echo $display;
	}

	function new_frame($src,$name)
	{
		echo '<frame src="' . $src . '" name="' . $name . '" />';
	}

	function create_image($param)
	{
		$display = '<img ';

		if (!empty($param['align']))
		{
			$display .= 'align="' . $param['align'] . '" ';
		}

		if (!empty($param['alt']))
		{
			$display .= 'alt="' . $param['alt'] . '" ';
		}
		else
		{
			$display .= 'alt="" ';
		}

		if (!empty($param['border']))
		{
			$display .= 'border="' . $param['border'] . '" ';
		}
		else
		{
			$display .= 'border="0" ';
		}

		if (!empty($param['class']))
		{
			$display .= 'class="' . $param['class'] . '" ';
		}

		if (!empty($param['src']))
		{
			$display .= 'src="' . $param['src'] . '" ';
		}

		if (!empty($param['title']))
		{
			$display .= 'title="' . $param['title'] . '" ';
		}

		if (!empty($param['width']))
		{
			$display .= 'width="' . $param['width'] . '" ';
		}

		$display .= '/>';

		if ($param['return'])
		{
			return $display;
		}
		elseif ($param['print'])
		{
			echo $display;
		}
		else
		{
			echo $display;
		}
	}

	function set_bold($param)
	{
		$display = '<strong>' . $param['text'] . '</strong>';

		if ($param['return'])
		{
			return $display;
		}
		elseif ($param['print'])
		{
			echo $display;
		}
		else
		{
			echo $display;
		}
	}

	function space()
	{
		echo '<br />';
	}

	function make_path($path)
	{
		echo '<p>لوحة التحكم -> ' . $path . '</p>';
	}

	function msg($msg,$align=null)
	{
		$tag = '<p align="';
		$tag .= (!empty($align)) ? $align : $this->lang['align'];
		$tag .= '">' . $msg . '</p>';

		echo $tag;
	}

	function open_p($align=null)
	{
		$tag = '<p align="';
		$tag .= (!empty($align)) ? $align : $this->lang['align'];
		$tag .= '">';

		echo $tag;
	}

	function p_msg($msg)
	{
		echo $msg . '<br />';
	}

	function close_p()
	{
		echo '</p>';
	}

	function javascript($lib)
	{
		echo "\t<script src=\"" . $lib . "\" type=\"text/javascript\" language=\"javascript\"></script>\r\n";
	}

	function div($id)
	{
		echo "<div id=\"" . $id . "\"></div>";
	}
}

?>
