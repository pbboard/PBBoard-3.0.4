<?php
class PBBTemplate
{
	protected $template;
	protected $while_num;

	protected $x_loop				=	0;
	protected $size_loop			=	0;
	protected $_while_var			=	null;
	protected $_while_var_num		=	0;
	protected $_foreach_var			=	null;
	protected $_foreach_var_num		=	0;
	private $vars_list				=	array();

	public $vars 					= 	array();
	public $while_array				=	array();
	public $foreach_array			=	array();



	public function __construct($PowerBB)
	{
		$this->while_array 		= 	array();
		$this->foreach_array 	= 	array();
		$this->_vars 			= 	array();
		$this->_while_var 		= 	array();
		$this->_foreach_var 	= 	array();
	}

	/**
	 * Display the template after compile it
	 */
	function display($template_name)
	{
		global $PowerBB;

       // Activating Cache Templates
	   $activating_cache_templates=1;

			if (!isset($PowerBB->_GET['debug']))
			{
				if ($activating_cache_templates)
				{
				$this->_TemplateFromFile($template_name);
				}
				else
				{
				$this->_TemplateFromFileSql($template_name);
				}
			}
	}


	function content($template_name)
	{
		if (!isset($PowerBB->_GET['debug']))
		{
			return $this->_TemplatepagerFromFile($template_name,true);
		}
	}


	function styleid()
	{
		global $PowerBB;

		$style_id = (!empty($PowerBB->_COOKIE[$PowerBB->_CONF['style_cookie']])) ? $PowerBB->_COOKIE[$PowerBB->_CONF['style_cookie']] : $PowerBB->_CONF['info_row']['def_style'];
		$style_id = $PowerBB->functions->CleanVariable($style_id,'intval');

	  return $style_id;
	}

	function _TemplateFromFileSql($template_name,$content=false)
	{
		global $PowerBB;

		if ($PowerBB->functions->checkmobile())
		{
		  $style_id = $PowerBB->functions->checkmobile();
		}
       else
        {
         $style_id = $this->styleid();
        }
		$text = $PowerBB->DB->sql_query("SELECT template,title FROM " . $PowerBB->prefix."template" . " WHERE title = '$template_name' AND styleid = '$style_id'");
		$template = $PowerBB->DB->sql_fetch_array($text);
		$this->_CompileTemplate($template['template'],$template['title']);
		unset($text);
		$text = $PowerBB->DB->sql_free_result($text);
	}

	function _TemplateFromFile($template_name,$content=false)
	{
		global $PowerBB;

		if ($PowerBB->functions->checkmobile())
		{
		  $style_id = $PowerBB->functions->checkmobile();
		}
       else
        {
         $style_id = $this->styleid();
        }

		if (function_exists('set_time_limit') AND !SAFEMODE)
		{
			@set_time_limit(1200);
		}
			$templates_dir = ("./cache/templates_cache/".$template_name."_".$style_id.".php");

					if (file_exists($templates_dir))
					{
						$fp = fopen($templates_dir,'r');
						if (!$fp)
						{
							trigger_error('ERROR::CAN_NOT_OPEN_THE_FILE',E_USER_ERROR);
						}
						$fr = fread($fp,filesize($templates_dir));
						if (!$fr)
						{
							trigger_error('ERROR::CAN_NOT_READ_FROM_THE_FILE',E_USER_ERROR);
						}
                  	   $this->_CompileTemplate($fr,$template_name);
				       fclose($fp);

					}
                   else
                   {

						$text = $PowerBB->DB->sql_query("SELECT template,title FROM " . $PowerBB->prefix."template" . " WHERE title = '$template_name' AND styleid = '$style_id'");
						$template = $PowerBB->DB->sql_fetch_array($text);
						if ($template)
						{
                            $template['template'] = str_replace("&#39;", "'", $template['template']);

							$fp = fopen($templates_dir,'w+');
							if (!$fp)
							{
							 trigger_error('ERROR::CAN_NOT_OPEN_THE_FILE',E_USER_ERROR);
							}
							$fw = fwrite($fp,$template['template']);
							fclose($fp);

							 if ($fw)
							 {
								$fp2 = fopen($templates_dir,'r');
								if (!$fp2)
								{
									trigger_error('ERROR::CAN_NOT_OPEN_THE_FILE',E_USER_ERROR);
								}

								$fr2 = fread($fp2,filesize($templates_dir));
								if (!$fr2)
								{
									trigger_error('ERROR::CAN_NOT_READ_FROM_THE_FILE',E_USER_ERROR);
								}
								$this->_CompileTemplate($fr2,$template_name);
								fclose($fp2);
	                         }

	                         unset($text);
					         $text = $PowerBB->DB->sql_free_result($text);

                        }
                        else
                        {
                            // if style not found in Database Change to default Style
							if (!$PowerBB->core->Is(array('where' => array('id',$style_id)),'style'))
							{
							$def_style = $PowerBB->_CONF['info_row']['def_style'];
							@ob_start();
							@setcookie("PowerBB_style", $def_style, time()+2592000);
							@ob_end_flush();
							}

                           // Show ERROR message to admin of this Template not found in style
							echo '
							<style type="text/css">
							.message   { width: 35%; color: black; background-color: #FFFFCC;  font: 8pt/11pt verdana, arial, sans-serif;}
							.message b { color: #0090c3; }
							.message u { color: #FF0000; font-weight: bold;}
							.clear { clear:both; }
							.direct { direction:ltr; }
							</style>';
							if ($PowerBB->_CONF['rows']['member_row']['usergroup'] == '1')
							{
							 echo("<br /><div class='direct'><div class='message'><u>ERROR</u>: Template Name: <b>".$template_name."</b> not found.<br /> <hr /> </div></div><div class='clear'></div><br />");
							}
                         }

                   }



	}

	/**
	 * If the template isn't compiled , we search it in template directory and if we found it we will compile it
	 */
	function _TemplatepagerFromFile($template_name,$content=false)
	{
			if (!$content)
			{
				$this->_GetCompiledFile($template_name,$content);
			}
			else
			{
				return $this->_GetCompiledFile($template_name,$content);
			}
	}

	function _CompileTemplate($string, $filename)
	{
		global $PowerBB;


         	$Template_name_dont_show = array("add_this", "add_reply_link", "add_subject_link", "signature_show" , "add_tags_table", "awards", "chat", "chat_edit", "chat_main", "editor_simple", "editor_js", "fast_reply_js", "imgs_resize", "jump_forums_list", "lasts_posts_bar", "last_subject_writer", "profile_cover_photo_upload", "statistics_list", "tags_edit_subject", "topic_end_fast_reply", "visitor_messag", "info_bar", "main_static_table", "visitor_message_js", "whatis_new");
			$string = str_replace("&#39;", "'", $string);
			 //$string = str_replace("config.removePlugins = 'contextmenu,liststyle,tabletools';","",$string);
			 $string = str_replace("contextmenu,","",$string);
			$string = str_replace('config.fontSize_defaultLabel',"config.allowedContent = true; \n config.fontSize_defaultLabel", $string);
			if ($filename == 'archive_main')
			{
			 $string = str_replace("show_sub_sections}}","show_sub_sections}} \n",$string);
			 $string = str_replace("{Des::foreach}","\n {Des::foreach}",$string);
			 $string = str_replace("{forum}","{forum} \n",$string);
            }
			 $string = str_replace("applications/core/archive.css","applications/core/archive/archive.css",$string);

			if ($filename == 'sections_list')
			{
		     $CatArr = $PowerBB->DB->sql_query("SELECT sort FROM " . $PowerBB->table['section'] . " WHERE parent = '0' ORDER BY sort ASC");
			 $catsort = $PowerBB->DB->sql_fetch_array($CatArr);
			 $string = str_replace("!= 1}", "!=". $catsort['sort']."}", $string);
            }
			$string = str_replace('<label for="emailed_id">',"\n", $string);
			$string = str_replace("ForumAdress}look/","ForumAdress}look/", $string);

			// CSRF protect all your forms
			//$string = str_ireplace("</form>",'<input type="hidden" name="csrf" value="{$csrf_key}" />'."\n</form>",$string);
			@eval($PowerBB->functions->get_fetch_hooks('template_class_start'));

			// We have loop
			if (preg_match('~\{Des::while}{([^[<].*?)}~',$string)
			or preg_match('~\{Des::while::complete}~',$string))
			{
			$string = $this->_ProccessWhile($string);
			}

			if (preg_match('~\{Des::foreach}{([^[<].*?)}~',$string))
			{
			$string = $this->_ProccessForeach($string);
			}

			if (preg_match('~\{if (.*)\}~',$string))
			{
			$string = $this->_ProccessIfStatement($string);
			}

			$search_array 	= 	array();
			$replace_array 	= 	array();

			$search_array[] 	= 	'~\{\$([^[<].*?)\[\'([^[<].*?)\'\]\[\'([^[<].*?)\'\]\}~';
			$replace_array[] 	= 	'<?php echo $PowerBB->_CONF[\'template\'][\'\\1\'][\'\\2\'][\'\\3\']; ?>';

			$search_array[] 	= 	'~\{\$([^[<].*?)\[([^[<].*?)\]\[([^[<].*?)\]\}~';
			$replace_array[] 	= 	'<?php echo $PowerBB->_CONF[\'template\'][\'\\1\'][\'\\2\'][\'\\3\']; ?>';

			$search_array[] 	= 	'~\{\$([^[<].*?)\[\'([^[<].*?)\'\]\}~';
			$replace_array[] 	= 	'<?php echo $PowerBB->_CONF[\'template\'][\'\\1\'][\'\\2\']; ?>';

			$search_array[] 	= 	'~\{\$([^[<].*?)\[([^[<].*?)\]\}~';
			$replace_array[] 	= 	'<?php echo $PowerBB->_CONF[\'template\'][\'\\1\'][\'\\2\']; ?>';

			$search_array[] 	= 	'~\{\$([^[<].*?)\}~';
			$replace_array[] 	= 	'<?php echo $PowerBB->_CONF[\'template\'][\'\\1\']; ?>';

			$search_array[] 	= 	'~\{template}([^[<].*?){/template}~';
			$replace_array[] 	= 	'<?php $this->display(\'\\1\'); ?>';

			$search_array[] 	= 	'~\{include}([^[<].*?){/include}~';
			$replace_array[] 	= 	'<?php include(\\1); ?>';

			$search_array[] 	= 	'~\{info_row}([^[<].*?){/info_row}~';
			$replace_array[] 	= 	'<?php $this->info_row(\'\\1\'); ?>';

			$search_array[] 	= 	'~\{get_hook}([^[<].*?){/get_hook}~';
			$replace_array[] 	= 	'<?php $this->get_hooks_template(\'\\1\'); ?>';

			//////////

			$string = preg_replace($search_array,$replace_array,$string);

			$string = str_replace("['template']['lang']","['template']['_CONF']['lang']",$string);
			$string = str_replace("['lang']['addons']","['lang']",$string);
			$string = str_replace("['mange_chat']","['chat_message']",$string);

			if ($PowerBB->functions->is_bot())
			{
			 $string = str_replace("['active_like_facebook']","['board_close']",$string);
			 $string = str_replace("['allow_avatar']","['board_close']",$string);

				if ($filename)
				{
					if (in_array($filename, $Template_name_dont_show))
					{
					 $string  = "<!-- Hide Template content:".$filename." -->";
					}
			   }
			}


			if ($filename == 'multi_quote')
			{
			$first_search = "censor_words";
			$first_replace = "mqtids_replace_cod";
			$string = str_replace($first_search,$first_replace,$string);
			}
			elseif ($filename == 'reply_edit')
			{
			$first_search = 'id="non_actiondate" type="checkbox" checked="checked"';
			$first_replace = 'id="non_actiondate" type="checkbox"';
			$string = str_replace($first_search,$first_replace,$string);
			}
			elseif ($filename == 'subject_edit')
			{
			$first_search = 'id="non_actiondate" type="checkbox" checked="checked"';
			$first_replace = 'id="non_actiondate" type="checkbox"';
			$string = str_replace($first_search,$first_replace,$string);
			}
			elseif ($filename == 'headinclud')
			{
			$first_search = 'meta name="description" content=" ';
			$first_replace = 'meta name="description" content="';
			$string = str_replace($first_search,$first_replace,$string);
			}
			elseif ($filename == 'pm_show')
			{
			$first_search = "ATTACH_SHOW";
			$first_replace = "ATTACH_SHOW_PM";
			$string = str_replace($first_search,$first_replace,$string);
			}
			elseif ($filename == 'new_topic')
			{
			$first_search = "[n]";
			$first_replace = "[\n]";
			$string = str_replace($first_search,$first_replace,$string);
			}
			elseif ($filename == 'add_chat_message')
			{
			$first_search = 'name="text"';
			$first_replace = 'name="textc"';
			$string = str_replace($first_search,$first_replace,$string);
			}
			elseif ($filename == 'sections_list')
			{
			$first_search = "count=";
			$first_replace = "last_post=1&amp;count=";
			$string = str_replace($first_search,$first_replace,$string);
			}
			elseif ($filename == 'usercp_menu')
			{
			$search_coordination_array 	= 	array();
			$replace_coordination_array 	= 	array();
			$search_coordination_array[] = "'profile_coordination'";
			$replace_coordination_array[] = "profile_coordination_nun";
			$search_coordination_array[] = ('<tr>
			<td class="row1">
			<a href="index.php?page=usercp&amp;control=1&amp;coordination=1&amp;main=1">');
			$replace_coordination_array[] = "";
			$string = str_replace($search_coordination_array,$replace_coordination_array,$string);
			}

			$string = str_replace('alt=""','alt="icon"',$string);
			$string = str_replace("alt=''","alt='icon'",$string);
			$string = str_replace("<!--copyright-->",$PowerBB->functions->copyright(),$string);

			$string = str_replace("Jsvk","",$string);
			$string = str_replace('action="index.php?page=login','name="login" action="index.php?page=login',$string);
			$string = str_replace('"index.php"','"'.$PowerBB->functions->GetForumAdress().'"',$string);
			$string = str_replace("'index.php'","'".$PowerBB->functions->GetForumAdress()."'",$string);
			@eval($PowerBB->functions->get_fetch_hooks('template_class_end'));

			$string = $PowerBB->sys_functions->ReplaceMysqlExtension($string);
			$string = $PowerBB->functions->rewriterule($string);

               $string = str_replace("php if","phpif",$string);
               $string = str_replace("if($.","T54T",$string);

            	$regex_if = '#phpif(.*?){#is';
				$string = preg_replace_callback($regex_if, function($matches) {
					$matches[1] = str_replace('<?php echo ','',$matches[1]);
					$matches[1] = str_replace('; ?>','',$matches[1]);

				    return 'php if'.$matches[1].'{ ';
				}, $string);

               $string = str_replace("T54T","if($.",$string);

            	$regex_elseif = '#elseif(.*?){#is';
				$string = preg_replace_callback($regex_elseif, function($matches) {
					$matches[1] = str_replace('<?php echo ','',$matches[1]);
					$matches[1] = str_replace('; ?>','',$matches[1]);

				    return 'elseif'.$matches[1].'{ ';
				}, $string);

               $string = str_replace("}) {  ?>","']) {  ?>",$string);

             $string = str_replace(">time(",">_date(",$string);
             $string = str_replace(">date(",">_date(",$string);

			$write  = @eval(" ?>".$string."<?php ");
	}


	/**
	 * We have {Des::while} loop , that's mean probably we will fetch information from database
	 */
	function _ProccessWhile($string)
	{
		global $PowerBB;
		$search_array 		= 	array();
		$replace_array 		= 	array();

		// I am sorry, but we _must_ do that
           $string =preg_replace_callback("~\{Des::while}{([^[<].*?)}~i", function ($matches) {
        return $this->_StoreWhileVarName($matches[1]);
         }, $string);


		// If statement _must_ be first
		if (preg_match('~\{if (.*)\}~',$string)
			or preg_match('~if (.*) {~',$string))
		{
			$string = $this->_ProccessIfStatement($string,'while');
		}

		foreach ($this->_while_var as $var_name)
		{
			$search_array[] 	=	'~\{\{\$' . $var_name . '\[([^[<].*?)\]\}\}~';
			$replace_array[] 	=	'$PowerBB->_CONF[\'template\'][\'while\'][\'' . $var_name . '\'][$this->x_loop][\\1]';

			$search_array[] 	=	'~\{\$' . $var_name . '\[([^[<].*?)\]\}~';
			$replace_array[] 	=	'<?php echo $PowerBB->_CONF[\'template\'][\'while\'][\'' . $var_name . '\'][$this->x_loop][\\1]; ?>';
		}

		$string 	= 	preg_replace($search_array,$replace_array,$string);

		$string 	= 	str_replace('{/Des::while}','<?php $this->x_loop = $this->x_loop + 1; } ?>',$string);
		$string 	= 	str_replace('{Des::while::complete}','',$string);
		$string 	= 	str_replace('{/Des::while::complete}','',$string);

		$this->_while_var 		= 	null;
		$this->_while_var_num 	= 	0;

		return $string;
	}


	function _StoreWhileVarName($varname)
	{
		global $PowerBB;
		//@error_reporting(0);

		$this->_while_var[$this->_while_var_num] = $varname;

		$this->_while_var_num += 1;
		if (isset($PowerBB->_CONF['template']['while'][''.$varname.'']))
		{
		$loop = '<?php $this->x_loop = 0; $this->size_loop = sizeof($PowerBB->_CONF[\'template\'][\'while\'][\'' . $varname . '\']); while ($this->x_loop < $this->size_loop) { ?>';
		}
		else
		{		$loop = '<?php while (0 < 0) { ?>';
		}

		return $loop;
	}

	function _ProccessForeach($string)
	{
		global $PowerBB;
		$search_array 		= 	array();
		$replace_array 		= 	array();


        $string =preg_replace_callback("~\{Des::foreach}{([^[<].*?)}{([^[<].*?)}~Ui", function ($matches) {
        return $this->_StoreForeachVarName($matches[2],$matches[1]);
         }, $string);

		if (preg_match('~\{if (.*)\}~',$string)
			or preg_match('~if (.*) {~',$string))
		{
			$string = $this->_ProccessIfStatement($string,'foreach');
		}

		foreach ($this->_foreach_var as $var_name)
		{
			// Variable (Without print) :
			//				{$var} -> $var
			$search_array[] 	= 	'~\{{\$' . $var_name . '\}}~';
			$replace_array[] 	= 	'$' . $var_name;

			$search_array[] 	=	'~\{{\$' . $var_name . '\[([^[<].*?)\]}}~';
			$replace_array[] 	=	'$' . $var_name . '[\\1]';

			// Variable :
			//				{$var} -> $var
			$search_array[] 	= 	'~\{\$' . $var_name . '\}~';
			$replace_array[] 	= 	'<?php echo $' . $var_name . '; ?>';

			$search_array[] 	=	'~\{\$' . $var_name . '\[([^[<].*?)\]\}~';
			$replace_array[] 	=	'<?php echo $' . $var_name . '[\\1]; ?>';
		}

		$search_array[] 	=	'~\{counter}~';
		$replace_array[] 	=	'<?php echo $this->x_loop ?>';

		$search_array[] 	=	'~\{{counter}}~';
		$replace_array[] 	=	'$this->x_loop';

		$string 			= 	preg_replace($search_array,$replace_array,$string);

		$string 			= 	str_replace('{/Des::foreach}','<?php $this->x_loop += 1; } ?>',$string);

		return $string;
	}

	function _StoreForeachVarName($varname,$oldname)
	{
		global $PowerBB;
		$this->_foreach_var[$this->_foreach_var_num] = $varname;

		$this->_foreach_var_num += 1;

		return '<?php foreach ($PowerBB->_CONF[\'template\'][\'foreach\'][\'' . $oldname . '\'] as $' . $varname . ') { ?>';
	}


	function _ProccessIfStatement($string,$type = null)
	{
		global $PowerBB;

		$search_array = array();
		$replace_array = array();

		$search_array[] 	= 	'~\{if (.*)}(.*){/if}~'; // SEE : We have a problem here, \\2 may contain "else" or "elseif"
		$replace_array[] 	= 	'<?php if (\\1) { ?> \\2 <?php } ?>';

		$search_array[] 	= 	'~\{if (.*)}~';
		$replace_array[] 	= 	'<?php if (\\1) { ?>';

		$search_array[] 	= 	'~\{/if}~';
		$replace_array[] 	= 	'<?php } ?>';

		// Elseif statement
		$search_array[] 	= 	'~\{elseif (.*)}(.*){/if}~';
		$replace_array[] 	= 	'<?php elseif (\\1) { ?> \\2 <?php } ?>';

		$search_array[] 	= 	'~\{elseif (.*)}~';
		$replace_array[] 	= 	'<?php } elseif (\\1) { ?>';

		// Else statement
		$search_array[] 	= 	'~\{else}~';
		$replace_array[] 	= 	'<?php } else { ?>';

		$string = preg_replace($search_array,$replace_array,$string);


         //  $string =preg_replace_callback('~\if (.*) \{~i', function ($matches) {
      //  return $this->_ProccessIfStatementVariables($matches[1],$type);
       //  }, $string);

		return $string;
	}

	function _ProccessIfStatementVariables($input,$type = null)
	{
		global $PowerBB;

		$string = 'if ' . $input . ' { ';

		if ($type == 'while')
		{
			foreach ($this->_while_var as $var_name)
			{
				$search_array[] 	=	'~\{\$' . $var_name . '\[([^[<].*?)\]\}~';
				$replace_array[] 	=	'$PowerBB->_CONF[\'template\'][\'while\'][\'' . $var_name . '\'][$this->x_loop][\\1]';
			}
		}
		elseif ($type == 'foreach')
		{
			foreach ($this->_foreach_var as $var_name)
			{
				// Variable (Without print) :
				//				{$var} -> $var
				$search_array[] 	= 	'~\{\$' . $var_name . '\}~';
				$replace_array[] 	= 	'$' . $var_name;

				$search_array[] 	=	'~\{\$' . $var_name . '\[([^[<].*?)\]}~';
				$replace_array[] 	=	'$' . $var_name . '[\\1]';
			}
		}


		$search_array[] 	= 	'~\{\$([^[<].*?)\[\'([^[<].*?)\'\]\[\'([^[<].*?)\'\]\}~';
		$replace_array[] 	= 	'$PowerBB->_CONF[\'template\'][\'\\1\'][\'\\2\'][\'\\3\']';

		$search_array[] 	= 	'~\{\$([^[<].*?)\[([^[<].*?)\]\[([^[<].*?)\]\}~';
		$replace_array[] 	= 	'$PowerBB->_CONF[\'template\'][\'\\1\'][\'\\2\'][\'\\3\']';

		$search_array[] 	= 	'~\{\$([^[<].*?)\[\'([^[<].*?)\'\]\}~';
		$replace_array[] 	= 	'$PowerBB->_CONF[\'template\'][\'\\1\'][\'\\2\']';

		$search_array[] 	= 	'~\{\$([^[<].*?)\[([^[<].*?)\]\}~';
		$replace_array[] 	= 	'$PowerBB->_CONF[\'template\'][\'\\1\'][\'\\2\']';

		$search_array[] 	= 	'~\{\$([^[<].*?)\}~';
		$replace_array[] 	= 	'$PowerBB->_CONF[\'template\'][\'\\1\']';

		$string = preg_replace($search_array,$replace_array,$string);
  		$string = str_replace("['template']['lang']","['template']['_CONF']['lang']",$string);
 		$string = str_replace("['lang']['addons']","['lang']",$string);

		return $string;
	}

	/**
	 * If the template is already compiled , so include it
	 */
	function _GetCompiledFile($template_name,$content=false)
	{
		global $PowerBB;

				$style_id = $this->styleid();
				$templates_dir = ("./cache/templates_cache/".$template_name."_".$style_id.".php");

					if (file_exists($templates_dir))
					{
						$fp = fopen($templates_dir,'r');
						if (!$fp)
						{
							trigger_error('ERROR::CAN_NOT_OPEN_THE_FILE',E_USER_ERROR);
						}
						$fr = fread($fp,filesize($templates_dir));
						if (!$fr)
						{
							trigger_error('ERROR::CAN_NOT_READ_FROM_THE_FILE',E_USER_ERROR);
						}
                  	   return($fr);
				       fclose($fp);

					}
                   else
                   {
					   $text = $PowerBB->DB->sql_query("SELECT template,title,styleid FROM " . $PowerBB->prefix."template" . " WHERE title = '$template_name' AND styleid = '$style_id'");
						$template = $PowerBB->DB->sql_fetch_array($text);
						if ($template)
						{
                            $template['template'] = str_replace("&#39;", "'", $template['template']);

							$fp = fopen($templates_dir,'w+');
							if (!$fp)
							{
							 trigger_error('ERROR::CAN_NOT_OPEN_THE_FILE',E_USER_ERROR);
							}
							$fw = fwrite($fp,$template['template']);
							fclose($fp);

							 if ($fw)
							 {
								$fp2 = fopen($templates_dir,'r');
								if (!$fp2)
								{
									trigger_error('ERROR::CAN_NOT_OPEN_THE_FILE',E_USER_ERROR);
								}

								$fr2 = fread($fp2,filesize($templates_dir));
								if (!$fr2)
								{
									trigger_error('ERROR::CAN_NOT_READ_FROM_THE_FILE',E_USER_ERROR);
								}
								$this->_CompileTemplate($fr2,$template_name);
								fclose($fp2);
	                         }
                          }

	                    unset($text);
					    $text = $PowerBB->DB->sql_free_result($text);

                   }
	}


	// Define variable to use it in template
	function assign($varname,$value)
	{
		global $PowerBB;

		$PowerBB->_CONF['template'][$varname] = $value;
	}

	// Define variable Info Rows to use it in template
	function info_row($value)
	{
		global $PowerBB;
		$this->_CompileTemplate($PowerBB->_CONF['info_row'][$value],0);
	}

	function get_hooks_template($value)
	{
		global $PowerBB;
		return $PowerBB->functions->get_hooks_template($value);
	}
	// Stop script
	function _error($msg)
	{
		global $PowerBB;

		die($msg);

	}

}

?>