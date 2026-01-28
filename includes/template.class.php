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

        @eval($PowerBB->functions->get_fetch_hooks('template_class_styleid'));

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
							 //echo("<br /><div class='direct'><div class='message'><u>ERROR</u>: Template Name: <b>".$template_name."</b> not found.<br /> <hr /> </div></div><div class='clear'></div><br />");
							 // for old style IF not found template Get original from original default templates and Insert. 2025
							 $this->_retrieved_template_original_Start($template_name,$style_id);
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
          $write_b = ob_start();
            $page = empty($PowerBB->_GET['page']) ? 'index' : $PowerBB->_GET['page'];

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
			if ($filename == 'online')
			{
			 $string = str_replace("Powerparse->censor_words","functions->rewriterule",$string);
            }
			if ($filename == 'portal_main_menu')
			{
			 $string = str_replace("javascript:logout('index.php?page=logout&amp;index=1')","index.php?page=logout&amp;index=1",$string);
            }
			if ($filename == 'portal_last_news')
			{
			 $string = str_replace('<span class="read_more_button"> &nbsp; </span>','<span class="fa fa-arrow-circle-left"></span> <u>{$LastNews_subjectList["title"]}</u>',$string);
            }
			if ($filename == 'visitor_messag')
			{
			 $string = str_replace(']}">&nbsp;',']}">', $string);
            }

			 $string = str_replace("applications/core/archive.css","applications/core/archive/archive.css",$string);



            if ($filename == 'info_bar')
			{
			 if($PowerBB->functions->is_bot())
			  {
				$first_search = "!{$_CONF['member_permission']}";
				$first_replace = "{$_CONF['member_permission']}";
				$string = str_replace($first_search,$first_replace,$string);
			  }
			$string = str_replace("'http://'","'".$PowerBB->functions->GetServerProtocol()."'",$string);
			}
            if ($filename == 'writer_info')
			{
              $string = str_replace("search_for_all_replys']}", "search_for_all_replys']} ", $string);
              $string = str_replace("search_for_all_posts']}", "search_for_all_posts']} ", $string);
              $string = str_replace("send_a_private_message_to']}", "send_a_private_message_to']} ", $string);
              $string = str_replace("send_a_message_to_the_mailing']}", "send_a_private_message_to']} ", $string);
              $string = str_replace("edit_member_data']}", "edit_member_data']} ", $string);
              $string = str_replace("a target", 'a rel="nofollow" target', $string);
              $string = str_replace("a href", 'a rel="nofollow" href', $string);
			}

            if ($filename == 'jump_forums_list')
			{
				$search_attach_array = "<option disabled='disabled'>-------------------------------</option>";
				$replace_attach_array  = "";
				$string = str_replace($search_attach_array,$replace_attach_array,$string);
			}
			$string = str_replace('<label for="emailed_id">',"\n", $string);
			$string = str_replace("ForumAdress}look/","ForumAdress}look/", $string);

	     	//Set no caching to javascript files add modification time in end url
           if($filename == 'headinclud')
           {
	            //Set no caching to javascript files add modification time in end url
	           	$regex_js = '#src="(.*?)"></script>#is';
					$string = preg_replace_callback($regex_js, function($matches) {
					   $matches[1] = str_replace("{\$ForumAdress}","",$matches[1]);
					   if(file_exists($matches[1]))
					    {
						$matchtime = filemtime($matches[1]);
						}
						if($matchtime)
						{
						$matches[1] = 'src="{$ForumAdress}'.$matches[1]."?v=".$matchtime.'"></script>';
						}
	                    else
						{
						$matches[1] = 'src="'.$matches[1].'?v=1"></script>';
						}
					    return $matches[1];
					}, $string);

             }
			$editor_dir = ("./look/sceditor/minified/PBB_bbcode.js");
			if(file_exists($editor_dir))
			{
			$edit_time = filemtime($editor_dir);
			}
			if($edit_time)
			{
			$string = str_replace("look/sceditor/minified/PBB_bbcode.js","look/sceditor/minified/PBB_bbcode.js?v=".$edit_time,$string);
			}
			else
			{
			$string = str_replace("look/sceditor/minified/PBB_bbcode.js","look/sceditor/minified/PBB_bbcode.js?v=1574",$string);
			}

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
           /*
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
            */

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

			elseif ($filename == 'portal_main_categories')
			{
			$first_search = "forumsy_list";
			$first_replace = "sections_list";
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
			elseif ($filename == 'show_subject_control'
			or $filename == 'subject_close_index'
			or $filename == 'subject_move_index'
			or $filename == 'subject_repeat_index')
			{
			$first_search = 'ajax_moderator_options';
			$first_replace = 'ajax_search"';
			$string = str_replace($first_search,$first_replace,$string);

			$first2_search = 'tabindex';
			$first2_replace = 'style="height:auto;" tabindex';
			$string = str_replace($first2_search,$first2_replace,$string);

			}
			elseif ($filename == 'forum_subject_table'
			or $filename == 'forum_announcement_table')
			{
			$search_coordination_array 	= 	array();
			$replace_coordination_array 	= 	array();
			$search_coordination_array[] = "forum_sub_vis wd4";
			$replace_coordination_array[] = "forum_sub_vis wd6";

			$search_coordination_array[] = "forum_sub_rep wd4";
			$replace_coordination_array[] = "forum_sub_rep wd6";

			$string = str_replace($search_coordination_array,$replace_coordination_array,$string);
			}
            elseif ($filename == 'send_admin')
			{
			$first_search = "member_permission";
			$first_replace = "captcha";
			$string = str_replace($first_search,$first_replace,$string);
			}
            elseif ($filename == 'usercp_options_attach')
			{
				if ($PowerBB->_CONF['group_info']['del_own_subject'] == '0'
				or $PowerBB->_CONF['group_info']['del_own_reply'] == '0')
				{
				$search_attach_array 	= 	array();
				$replace_attach_array 	= 	array();
				$search_attach_array[] = "button button_b";
				$replace_attach_array[] = "buttons_no_link";

				$search_attach_array[] = 'type="submit"';
				$replace_attach_array[] = 'type="button"';

				$search_attach_array[] = 'index.php?page=usercp&amp;attach=1&amp;del=1&amp;options=1';
				$replace_attach_array[]= 'index.php?page=usercp&amp;attach=1&amp;main=1&amp;options=1';

				$string = str_replace($search_attach_array,$replace_attach_array,$string);
				}

			}
            elseif ($filename == 'search')
			{
			$first_search = ">- ";
			$first_replace = ' class="row2" disabled="disabled">';
			$string = str_replace($first_search,$first_replace,$string);
			}
            elseif ($filename == 'subject_top')
			{
			$first_search = 'item"href';
			$first_replace = 'item" href';
			$string = str_replace($first_search,$first_replace,$string);
			}
            elseif ($filename == 'show_subject_information')
			{
			$first_search = '("#vote")';
			$first_replace = "('[name=\"vote\"]:checked')";
			$string = str_replace($first_search,$first_replace,$string);
			}
            elseif ($filename == 'statistics_list')
			{
			$first_search = 'lastPostsList';
			$first_replace = 'lastStaticPostsList';
			$string = str_replace($first_search,$first_replace,$string);
			}

			$first_searchss = "function uploadFile() {";
			$first_replacess = 'function uploadFile() { var x = document.getElementById("files").value;if (!x){return false;}';
			$string = str_replace($first_searchss,$first_replacess,$string);

			$string = str_replace('alt=""','alt="icon"',$string);
			$string = str_replace("alt=''","alt='icon'",$string);
			$string = str_replace("<!--copyright-->",$PowerBB->functions->copyright(),$string);
             $string = str_replace('emoticons/','look/sceditor/emoticons/',$string);

			$string = str_replace("Jsvk","",$string);
			$string = str_replace('action="index.php?page=login','name="login" action="index.php?page=login',$string);
			$string = str_replace('"index.php"','"'.$PowerBB->functions->GetForumAdress().'"',$string);
			$string = str_replace("'index.php'","'".$PowerBB->functions->GetForumAdress()."'",$string);
			@eval($PowerBB->functions->get_fetch_hooks('template_class_end'));

			$string = $PowerBB->sys_functions->ReplaceMysqlExtension($string);

             $url = $PowerBB->functions->GetForumAdress();
             $string = str_replace('href="index.php','href="'.$url.'index.php',$string);


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
				if ($page == 'index')
				{
				$string = str_replace('<div class="btn-nav"></div>','',$string);
				}
                else
				{
				$string = str_replace('<div class="btn-nav"></div>','<b class="btn-nav"></b>',$string);
				}


	        $write  = eval(" ?> $string <?php ");
            $write_b = ob_get_clean();

			if ($filename == 'headinclud') {
			    if (preg_match('/<title>(.*?)<\/title>/is', $write_b, $matches)) {
			        $fullTitle = $matches[1];

			        if (strpos($fullTitle, ' - ') !== false) {
			            $parts = explode(' - ', $fullTitle);
			            $GLOBALS['captured_subject_title'] = trim($parts[0]);
			        } else {
			            $GLOBALS['captured_subject_title'] = trim($fullTitle);
			        }
			        $GLOBALS['captured_subject_title'] = htmlspecialchars(strip_tags($GLOBALS['captured_subject_title']), ENT_QUOTES, 'UTF-8');
			    }
			}

			$write_b = str_replace("href='index.php","href='".$PowerBB->functions->GetForumAdress()."index.php",$write_b);

				// start auto link titles php
				  $write_b = str_replace("option value='index.php","option value='".$PowerBB->functions->GetForumAdress()."index.php",$write_b);
				  $write_b = str_replace('option value="index.php','option value="'.$PowerBB->functions->GetForumAdress().'index.php',$write_b);

                    // if not active latest reply today Remove the’s lines.
					if ($PowerBB->_CONF['info_row']['active_reply_today'] == '0')
					{
					$write_b = str_replace('<li><a title="'.$PowerBB->_CONF['template']['_CONF']['lang']['latest_reply'].'" href="'.$PowerBB->functions->GetForumAdress().'index.php?page=latest_reply&today=1"><i class="fa fa-message"></i> '.$PowerBB->_CONF['template']['_CONF']['lang']['latest_reply'].'</a></li>','', $write_b);
					$write_b = str_replace('<a href="'.$PowerBB->functions->GetForumAdress().'index.php?page=latest_reply&amp;today=1">'.$PowerBB->_CONF['template']['_CONF']['lang']['whatis_new'].'</a>','', $write_b);
					$write_b = str_replace('<a href="'.$PowerBB->functions->GetForumAdress().'index.php?page=latest_reply&amp;today=1">'.$PowerBB->_CONF['template']['_CONF']['lang']['latest_reply'].'</a>','', $write_b);
					$write_b = str_replace('<a href="'.$PowerBB->functions->GetForumAdress().'index.php?page=latest_reply&amp;today=1" title="'.$PowerBB->_CONF['template']['_CONF']['lang']['latest_reply'].'">'.$PowerBB->_CONF['template']['_CONF']['lang']['latest_reply'].'</a>','', $write_b);
					$write_b = str_replace("<option value='".$PowerBB->functions->GetForumAdress()."index.php?page=latest_reply&amp;today=1'>»".$PowerBB->_CONF['template']['_CONF']['lang']['latest_reply']."</option>","", $write_b);
		             if ($filename == 'header_bar')
					 {
                     $write_b = preg_replace('#<li>(\r\n?|\n?)<span class="fa fa-clipboard"></span>(\r\n?|\n?)</li>#si', '', $write_b);
					 }
					}
				 if ($PowerBB->_CONF['info_row']['auto_links_titles'])
				  {
					if ($filename == 'forum_perpage_reply'
					or $filename == 'forum_stick_perpage_reply'
					or $filename == 'forum_review_perpage_reply')
					{
						$patterntitle='#title="(.*)"#siU';
						preg_match($patterntitle, $write_b, $matctitle);
                        $matctitle[1] = $PowerBB->functions->strip_auto_url_titles($matctitle[1]);
						$write_b = preg_replace('#page=topic&amp;show=1&amp;id=(.*)&amp;count=(.*)#siU', 'topic/'.$matctitle[1].'.$1/page-$3', $write_b);
						$write_b = preg_replace('#page=topic&amp;show=1&amp;id=(.*)#siU', 'topic/'.$matctitle[1].'.$1', $write_b);
						$write_b = str_replace("index.php?","",$write_b);
					}
				  }

					if (!preg_match('#\<div class="text"\>(.*)\</div\>#siU', $write_b)
					or !preg_match('#\<div class="textemain"\>(.*)\</div\>#siU', $write_b))
					{
					$write_b = str_replace('<link rel="stylesheet" href=','<link rel="stylesheet" hrref=',$write_b);
					$write_b = $PowerBB->functions->rewriterule($write_b);
					$write_b = str_replace('<link rel="stylesheet" hrref=','<link rel="stylesheet" href=',$write_b);
					}


				 if ($PowerBB->_CONF['info_row']['auto_links_titles'])
				  {
	 				$page_title = trim($PowerBB->_CONF['template']['title']);
					$page_title = $PowerBB->functions->strip_auto_url_titles($page_title);
					$page_title = str_replace("الصفحة-","",$page_title);
					$page_title = str_replace("Page-","",$page_title);
					$page_title = preg_replace('/[0-9]+/', '', $page_title);
					$page_title = rtrim($page_title, '-');
					$page_title = ltrim($page_title, '-');
					$write_b = str_replace("empty_tIt_empty",$page_title,$write_b);
					$write_b = str_replace("empty_tIt_empty","",$write_b);
					$write_b = preg_replace('#_tIt_(.*).(.*)/page-#siU', $page_title.'_tSt_$2/page-', $write_b);
					$write_b = preg_replace('#_tSt_([1-9][0-9]*).#si', '.', $write_b);
					$write_b = preg_replace('#_tSt_(.*).#siU', '$1.', $write_b);
					//$write_b  = preg_replace("#/1.([0-9]+).#si", '/'.$updated_title.'.', $write_b );
					if (!preg_match('#\<pre\>(.*)\</pre\>#siU', $write_b))
		            {
					$write_b = str_replace('/1.', '/'.$page_title.'.', $write_b);
					}
					$write_b = str_replace("_tSt_","",$write_b);
					$write_b = str_replace("_tIt_","",$write_b);
	               	$write_b = str_replace(".ptyempty","",$write_b);

					if ($filename == 'sections_list')
					{
					$dcount = explode('#.([1-9][0-9]*)#si',$write_b);
					$write_b = str_replace('topic/','topic/'.$dcount[1],$write_b);
					}
					if ($filename == 'show_post')
					{
                    $write_b = str_replace(".شاهدة-المشاركة-في-الموضوع-بالعرض-العادي","",$write_b);
                    }
			        $write_b = str_replace('a href="member/','a href="'.$PowerBB->functions->GetForumAdress().'member/', $write_b);
			        $write_b = str_replace('a href="forum/','a href="'.$PowerBB->functions->GetForumAdress().'forum/', $write_b);
			        $write_b = str_replace('a href="topic/','a href="'.$PowerBB->functions->GetForumAdress().'topic/', $write_b);
				   // end auto link titles php
		          }

				$write_b = str_replace('src="look/','src="'.$PowerBB->functions->GetForumAdress().'look/', $write_b);
				$write_b = str_replace("src='look/","src='".$PowerBB->functions->GetForumAdress()."look/", $write_b);
				$write_b = str_replace('src="includes/','src="'.$PowerBB->functions->GetForumAdress().'includes/', $write_b);
				$write_b = str_replace("src='includes/","src='".$PowerBB->functions->GetForumAdress()."includes/", $write_b);
				$write_b = str_replace('src="styles/','src="'.$PowerBB->functions->GetForumAdress().'styles/', $write_b);
				$write_b = str_replace("src='styles/","src='".$PowerBB->functions->GetForumAdress()."styles/", $write_b);
				$write_b = str_replace('src="download/','src="'.$PowerBB->functions->GetForumAdress().'download/', $write_b);
				$write_b = str_replace("src='download/","src='".$PowerBB->functions->GetForumAdress()."download/", $write_b);
				$write_b = str_replace('href="download/','href="'.$PowerBB->functions->GetForumAdress().'download/', $write_b);
				$write_b = str_replace('"index.php','"'.$PowerBB->functions->GetForumAdress().'index.php', $write_b);
				$write_b = str_replace("'index.php","'".$PowerBB->functions->GetForumAdress().'index.php', $write_b);
				$write_b = str_replace('"'.$PowerBB->_CONF['template']['admincpdir'].'?page=','"'.$PowerBB->functions->GetForumAdress().$PowerBB->_CONF['template']['admincpdir'].'?page=', $write_b);
				$write_b = str_replace('href="'.$PowerBB->_CONF['template']['admincpdir'].'"','href="'.$PowerBB->functions->GetForumAdress().$PowerBB->_CONF['template']['admincpdir'].'"', $write_b);
				$write_b = str_replace('("index.php?page=','("'.$PowerBB->functions->GetForumAdress().'index.php?page=', $write_b);
				$write_b = str_replace('(look/','('.$PowerBB->functions->GetForumAdress().'look/', $write_b);
				$write_b = str_replace('(styles/','('.$PowerBB->functions->GetForumAdress().'styles/', $write_b);
				$write_b = str_replace('"./applications','"'.$PowerBB->functions->GetForumAdress().'applications', $write_b);
				$write_b = str_replace('src="applications/','src="'.$PowerBB->functions->GetForumAdress().'applications/', $write_b);
				$write_b = str_replace('url(download/','url('.$PowerBB->functions->GetForumAdress().'download/', $write_b);
				$write_b = str_replace($PowerBB->functions->GetForumAdress().'http','http', $write_b);
                $write_b = str_replace('href="forum/','href="'.$PowerBB->functions->GetForumAdress().'forum/', $write_b);


				 if (strstr($write_b,'<!--adding_route_sequence_numbers-->'))
				 {
				  $write_b = str_replace('<b class="btn-nav"></b>','',$write_b);
				 }


                if ($PowerBB->_CONF['info_row']['auto_links_titles'])
				{
					// Regex لفك ترميز اللغة العربية فقط داخل الروابط
					$write_b = preg_replace_callback(
					    '/https?:\/\/[^\s"\'<>]+/',
					    function ($matches) {
					        return urldecode($matches[0]);
					    },
					    $write_b
					);
                }

if (!empty($GLOBALS['captured_subject_title'])) {
    $write_b = str_replace("--SUBJECT_TITLE--", $GLOBALS['captured_subject_title'], $write_b);
} else {
    // احتياطاً إذا لم يجد تايتل
    $write_b = str_replace("--SUBJECT_TITLE--", "عرض الصورة", $write_b);
}

   @eval($PowerBB->functions->get_fetch_hooks('template_ob_get_clean'));

		echo $write_b;

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
		{
		$loop = '<?php while (0 < 0) { ?>';
		}

		return $loop;
	}

function _ProccessForeach($string)
{
    global $PowerBB;
    $search_array       = array();
    $replace_array      = array();

    // التعديل هنا: السماح بالرموز الخاصة في الجزء الأول من الفوريتش
    $string = preg_replace_callback("~\{Des::foreach}\{([^\}]+)\}\{([^\}]+)\}~Ui", function ($matches) {
        return $this->_StoreForeachVarName($matches[2], $matches[1]);
    }, $string);

    if (preg_match('~\{if (.*)\}~', $string) or preg_match('~if (.*) {~', $string)) {
        $string = $this->_ProccessIfStatement($string, 'foreach');
    }

    foreach ($this->_foreach_var as $var_name) {
        $search_array[]     = '~\{{\$' . $var_name . '\}}~';
        $replace_array[]    = '$' . $var_name;

        $search_array[]     = '~\{{\$' . $var_name . '\[([^[<].*?)\]}}~';
        $replace_array[]    = '$' . $var_name . '[\\1]';

        $search_array[]     = '~\{\$' . $var_name . '\}~';
        $replace_array[]    = '<?php echo $' . $var_name . '; ?>';

        $search_array[]     = '~\{\$' . $var_name . '\[([^[<].*?)\]\}~';
        $replace_array[]    = '<?php echo $' . $var_name . '[\\1]; ?>';
    }

    $search_array[]     = '~\{counter}~';
    $replace_array[]    = '<?php echo $this->x_loop ?>';

    $search_array[]     = '~\{{counter}}~';
    $replace_array[]    = '$this->x_loop';

    $string             = preg_replace($search_array, $replace_array, $string);
    $string             = str_replace('{/Des::foreach}', '<?php $this->x_loop += 1; } ?>', $string);

    return $string;
}

function _StoreForeachVarName($varname, $oldname)
{
    global $PowerBB;
    $this->_foreach_var[$this->_foreach_var_num] = $varname;
    $this->_foreach_var_num += 1;

    // إذا كان الاسم يبدأ بـ $ (مثل $cat['sub_data'])
    if (strpos($oldname, '$') === 0) {
        // تحويل الصيغة من {$var} إلى $var لتكون صالحة في PHP
        $php_var = str_replace(array('{', '}', '$'), array('', '', '$'), $oldname);
        return '<?php if(isset(' . $php_var . ') && is_array(' . $php_var . ')) foreach (' . $php_var . ' as $' . $varname . ') { ?>';
    }
    // إذا كان اسماً عادياً (مثل main_forums)
    else {
        return '<?php if(isset($PowerBB->_CONF[\'template\'][\'foreach\'][\'' . $oldname . '\'])) foreach ($PowerBB->_CONF[\'template\'][\'foreach\'][\'' . $oldname . '\'] as $' . $varname . ') { ?>';
    }
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

	function _retrieved_template_original_Start($template_name, $style_id)
    {
    	global $PowerBB;

	 if (empty($template_name))
	  {
		return;
	  }
	  else
	  {

         $originalfile ="./cache/original_default_templates.xml";

			if (file_exists($originalfile))
			{

			   $xml_code = @file_get_contents($originalfile);

			}
			if (strstr($xml_code,'decode="0"'))
			{
				$xml_code = str_replace('decode="0"','decode="1"',$xml_code);
				preg_match_all('/<!\[CDATA\[(.*?)\]\]>/is', $xml_code, $match);
				foreach($match[0] as $val)
				{
				$xml_code = str_replace($val,base64_encode($val),$xml_code);
				}

			}
        		$import = $PowerBB->functions->xml_array($xml_code);
				$Templates = $import['styles']['templategroup'];
				$Templates_number = sizeof($import['styles']['templategroup']['template']);

			            $x = 0;
     			while ($x < $Templates_number)
     			{
						$templatetitle = $Templates['template'][$x.'_attr']['name'];
						$version = $Templates['template'][$x.'_attr']['version'];
						$template_version = $Templates['template'][$x.'_attr']['version'];
						$template_version = str_replace(".", "", $template_version);
						if ($Templates['template'][$x.'_attr']['decode'] == '1'
						or $template_version <= '301')
						{
						$template = @base64_decode($Templates['template'][$x]);
     				    }
     				    else
						{
						$template = $Templates['template'][$x];
     				    }
     				    $template = str_replace("//<![CDATA[", "", $template);
						$template = str_replace("//]]>", "", $template);
     				    $template = str_replace("<![CDATA[","", $template);
						$template = str_replace("]]>","", $template);

	                if($template_name == $templatetitle)
				    {
				      $row['template_un'] = $template;
				      $x = 0;
				      break;
				    }
                     $x += 1;
                 }



        $row['template_un'] = str_replace("'", "&#39;", $row['template_un']);


		$TemplateArr 			= 	array();
		$TemplateArr['field']	=	array();

		$TemplateArr['field']['styleid'] 		    = 	$style_id;
		$TemplateArr['field']['title'] 		    = 	$template_name;
		$TemplateArr['field']['template'] 	    = 	$row['template_un'];
		$TemplateArr['field']['template_un'] 	    	= 	$row['template_un'];
		$TemplateArr['field']['templatetype'] 		        = 	"template";
		$TemplateArr['field']['dateline'] 		            = 	$PowerBB->_CONF['now'];
		$TemplateArr['field']['version'] 	    = 	$PowerBB->_CONF['info_row']['MySBB_version'];
		$TemplateArr['field']['username'] 	    = 	"AUTO";
		$TemplateArr['field']['product'] 		    = 	"PBBoard";

		$insert = $PowerBB->core->Insert($TemplateArr,'template');

		if ($insert)
		{

		 $_query1= $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['template'] . " WHERE title = '$template_name'");
		 while ($row1 = $PowerBB->DB->sql_fetch_array($_query1))
		 {
			$TemplateEditArr				=	array();
			$TemplateEditArr['where'] 				    = 	array('templateid',$row1['templateid']);

			$TemplateEdit = $PowerBB->core->GetInfo($TemplateEditArr,'template');

            $templates_dir = ("./cache/templates_cache/".$TemplateEdit['title']."_".$TemplateEdit['styleid'].".php");
			 if (file_exists($templates_dir))
			 {
			  $cache_del = @unlink($templates_dir);
			 }
    	 }
		}
	  }
	}

}

?>