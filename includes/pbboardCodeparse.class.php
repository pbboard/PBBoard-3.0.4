<?php
class PowerBBCodeParse
{
	/**
	 * Internal cache for nested lists
	 *
	 * @access public
	 * @var array
	 */
	public $list_elements;
	/**
	 * Internal counter for nested lists
	 *
	 * @access public
	 * @var int
	 */
	public $list_count;
	/**
	 * Whether or not should a <br /> with clear: both be added at the end of the parsed message
	 *
	 * @access public
	 * @var boolean
	 */
	public $clear_needed = false;
	/**
	 * Parses a message with the specified options.
	 * @param string $string The $string to be parsed.
	 * @return string The parsed message.
	 */
 	function replace($string)
 	{
 		global $PowerBB;

 		$brackets = (strpos($string,'[') !== false) and (strpos($string,']') !== false);
        if ($brackets)
 		{


                 // start replaces code
			    $regexcode['[php]'] = '#\[php\](.*)\[/php\]#siU';
				$string = preg_replace_callback($regexcode, function($matches) {
				    return '[php]'.base64_encode($matches[1]).'[/php]';
				}, $string);
                 // start replaces code
			    $regexcodew['[code]'] = '#\[code\](.*)\[/code\]#siU';
				$string = preg_replace_callback($regexcodew, function($matchesw) {
				    return '[code]'.base64_encode($matchesw[1]).'[/code]';
				}, $string);

                // start replaces Quotes
	          $string = $this->PowerCode_Quote($string);
			// Replace base, meta,script and style tags in our post - these are > dangerous <
			//$string = str_ireplace("script", 's c r i p t', $string);
               $string = htmlspecialchars($string);

             $string = @strip_tags($string);

            // Parse quotes first
			$regexquoted['quoted'] = '#\[quoted\](.*)\[/quoted\]#siU';
			$string = preg_replace_callback($regexquoted, function($matches) {
			$matches[1] = base64_decode($matches[1]);
			$matches[1] = str_replace("&amp;#39;", "'", $matches[1]);
			return $matches[1];
			}, $string);

	         $string = str_replace("http://www.youtube.com", "https://www.youtube.com", $string);

                 // jwplayer tag replace
			    $jwplayer_search= '#\[jwplayer=(.*),(.*),(.*),(.*)\](.*)\[/jwplayer\]#siU';
				$string = preg_replace_callback($jwplayer_search, function($jwplayer) {
				    return $this->jwplayer($jwplayer[1],$jwplayer[2],$jwplayer[3],$jwplayer[4],$jwplayer[5]);
				}, $string);

			   //replace youtube;
			    $youtube_search = '#\[youtube\](.*)\[/youtube\]#siU';
				$string = preg_replace_callback($youtube_search, function($youtube) {
					$youtubematch = strstr( $youtube[1], 'youtube.com' ) ? 1 : 0;
					if(!$youtubematch)
					{
					 $youtube[1]= "https://www.youtube.com/watch?v=".$youtube[1];
					}
				    return $this->PowerCode_Youtube($youtube[1],$youtube[1]);
				}, $string);

			    $youtuber_search = '#\[youtube=(.*)\](.*)\[/youtube\]#siU';
				$string = preg_replace_callback($youtuber_search, function($youtuber) {
				    return $this->PowerCode_Youtube($youtuber[1],$youtuber[2]);
				}, $string);


				// Find all lists
				$string = str_replace('[ul]', '[list]', $string);
				$string = str_replace('[/ul]', '[/list]', $string);
				$string = str_replace('[ol]', '[list=1]', $string);
				$string = str_replace('[/ol]', '[/list]', $string);
				$string = str_replace('[li]', '[*]', $string);
				$string = str_replace('[/li]', '', $string);

				$this->list_elements = array();
				$this->list_count = 0;

				$string = preg_replace_callback("#(\[list(=(a|A|i|I|1))?\]|\[/list\])#si", array($this, 'mycode_prepare_list'), $string);

				// Replace all lists
				for($i = $this->list_count; $i > 0; $i--)
				{
				// Ignores missing end tags
				$string = preg_replace_callback("#\s?\[list(=(a|A|i|I|1))?&{$i}\](.*?)(\[/list&{$i}\]|$)(\r\n?|\n?)#si", array($this, 'mycode_parse_list_callback'), $string, 1);
				}

            $string = preg_replace('#\[b\](.+)\[\/b\]#iUs', '<b>$1</b>', $string);
            $string = preg_replace('#\[u\](.+)\[\/u\]#iUs', '<u>$1</u>', $string);
            $string = preg_replace('#\[i\](.+)\[\/i\]#iUs', '<i>$1</i>', $string);
            $string = preg_replace('#\[s\](.+)\[\/s\]#iUs', '<s>$1</s>', $string);
            $string = preg_replace('#\[h1\](.+)\[\/h1\]#iUs', '<h1>$1</h1>', $string);
            $string = preg_replace('#\[h2\](.+)\[\/h2\]#iUs', '<h2>$1</h2>', $string);
            $string = preg_replace('#\[h3\](.+)\[\/h3\]#iUs', '<h1>$1</h1>', $string);
            $string = preg_replace('#\[h4\](.+)\[\/h4\]#iUs', '<h1>$1</h1>', $string);
            $string = preg_replace('#\[h5\](.+)\[\/h5\]#iUs', '<h1>$1</h1>', $string);
            $string = preg_replace('#\[h6\](.+)\[\/h6\]#iUs', '<h1>$1</h1>', $string);
            $string = preg_replace('#\[hr\]\[\/hr\]#iUs', '<HR id=null>', $string);
            $string = preg_replace('#\[hr\]#iUs', '<HR id=null>', $string);
            $string = preg_replace('#\[\/hr\]#iUs', '', $string);
            $string = preg_replace('#\[sub\](.+)\[\/sub\]#iUs', '<SUB>$1</SUB>', $string);
            $string = preg_replace('#\[sup\](.+)\[\/sup\]#iUs', '<SUP>$1</SUP>', $string);
            $string = preg_replace('#\[guest_name\](.+)\[\/guest_name\]#iUs', '<br>$1</br>', $string);
            $string = preg_replace('#\[-WEBKIT-CENTER\](.+)\[\/-WEBKIT-CENTER\]#iUs', '<div align="center">$1</div>', $string);
            $string = preg_replace('#\[ltr\](.+)\[\/ltr\]#iUs', '<div style="text-align: left;" dir="ltr">$1</div>', $string);
            $string = preg_replace('#\[rtl\](.+)\[\/rtl\]#iUs', '<div style="text-align: right;" dir="rtl">$1</div>', $string);
            $string = preg_replace('#\[highlight\=(.+)\](.+)\[\/highlight\]#iUs', '<span style="background:$1">$2</span>', $string);
            $string = preg_replace('#\[blockquote\](.+)\[\/blockquote\]#iUs', '<blockquote class=\"quotemain\">$1</blockquote>', $string);
            $string = preg_replace('#\[indent\](.+)\[\/indent\]#iUs', '<indent>$1</indent>', $string);
	        $string = preg_replace('#\[table\](.+)\[\/table\]#iUs', '<table border="1" cellspacing="1" class="Code_table">$1</table>', $string);
	        $string = preg_replace('#\[tr\](.+)\[\/tr\]#iUs', '<tr>$1</tr>', $string);
	        $string = preg_replace('#\[td\](.+)\[\/td\]#iUs', '<td>$1</td>', $string);
            $string = preg_replace("#\[(left|center|right|justify)\](.*?)\[/(left|center|right|justify)\]#si", "<div style=\"text-align: $1;\" class=\"mycode_align\">$2</div>", $string);
            $string = preg_replace("#\[align=(left|center|right|justify)\](.*?)\[/align\]#si", "<div style=\"text-align: $1;\" class=\"mycode_align\">$2</div>", $string);
	        $string = preg_replace("#\[color=([a-zA-Z]*|\#?[\da-fA-F]{3}|\#?[\da-fA-F]{6})](.*?)\[/color\]#si", "<span style=\"color: $1;\" class=\"mycode_color\">$2</span>", $string);
	        $string = preg_replace('#\[color\=(.+)\](.+)\[\/color\]#iUs', "<span style=\"color: $1;\" class=\"mycode_color\">$2</span>", $string);
	        $string = preg_replace('#\[style\=(.+)\](.+)\[\/style\]#iUs', "<span $1 class=\"mycode_style\">$2</span>", $string);
            $string = preg_replace("#\[size=(xx-small|x-small|small|medium|large|x-large|xx-large)\](.*?)\[/size\]#si", "<span style=\"font-size: $1;\" class=\"mycode_size\">$2</span>", $string);
       	    $string = preg_replace('#\[size\=(.+)\](.+)\[\/size\]#iUs', "<font size=\"$1\" style=\"font-size: $1;\" class=\"mycode_size\">$2</font>", $string);
            $string = preg_replace('#\[font\=(.+)\](.+)\[\/font\]#iUs', "<font face=\"$1\" style=\"font-family: $1;\" class=\"mycode_font\">$2</font>", $string);
			$regexx_list = '#\[list=(1|2)\](.*)\[/list\]#siU';
			$string = preg_replace_callback($regexx_list, function($xx_list) {
			return $this->DoList($xx_list[1],$xx_list[2]);
			}, $string);
			$regex_list = '#\[list\](.*)\[/list\]#siU';
			$string = preg_replace_callback($regex_list, function($x_list) {
			return $this->DoList($x_list[1]);
			}, $string);
            $string = str_replace('[/list]', '', $string);

            eval($PowerBB->functions->get_fetch_hooks('BBCodeParseHooks1'));

         }


        $string = $this->text_with_hyperlink($string);
        If(strstr($string,"n-l-2-b-r"))
        {
         $string = str_replace("n-l-2-b-r", "", $string);
        }
        else
        {
        $string = nl2br($string);
        }
		// Fix up new lines and block level elements
		$string = preg_replace("#(</?(?:html|head|body|div|p|form|table|thead|tbody|tfoot|tr|td|th|ul|ol|li|div|p|blockquote|cite|hr)[^>]*>)\s*<br />#i", "$1", $string);
		$string = preg_replace("#(&nbsp;)+(</?(?:html|head|body|div|p|form|table|thead|tbody|tfoot|tr|td|th|ul|ol|li|div|p|blockquote|cite|hr)[^>]*>)#i", "$2", $string);
		if($this->clear_needed)
		{
			$string .= '<br class="clear" />';
		}

		return $string;
 	}


 	function Simplereplace($string)
 	{

	       $string = preg_replace('#\[b\](.+)\[\/b\]#iUs', '<b>$1</b>', $string);
            $string = preg_replace('#\[u\](.+)\[\/u\]#iUs', '<u>$1</u>', $string);
            $string = preg_replace('#\[i\](.+)\[\/i\]#iUs', '<i>$1</i>', $string);
            $string = preg_replace('#\[s\](.+)\[\/s\]#iUs', '<s>$1</s>', $string);
            $string = preg_replace('#\[h1\](.+)\[\/h1\]#iUs', '<h1>$1</h1>', $string);
            $string = preg_replace('#\[h2\](.+)\[\/h2\]#iUs', '<h2>$1</h2>', $string);
            $string = preg_replace('#\[h3\](.+)\[\/h3\]#iUs', '<h1>$1</h1>', $string);
            $string = preg_replace('#\[h4\](.+)\[\/h4\]#iUs', '<h1>$1</h1>', $string);
            $string = preg_replace('#\[h5\](.+)\[\/h5\]#iUs', '<h1>$1</h1>', $string);
            $string = preg_replace('#\[h6\](.+)\[\/h6\]#iUs', '<h1>$1</h1>', $string);
            $string = preg_replace('#\[hr\]\[\/hr\]#iUs', '<HR id=null>', $string);
            $string = preg_replace('#\[hr\]#iUs', '<HR id=null>', $string);
            $string = preg_replace('#\[sub\](.+)\[\/sub\]#iUs', '<SUB>$1</SUB>', $string);
            $string = preg_replace('#\[sup\](.+)\[\/sup\]#iUs', '<SUP>$1</SUP>', $string);
            $string = preg_replace('#\[guest_name\](.+)\[\/guest_name\]#iUs', '<br>$1</br>', $string);
            $string = preg_replace("#\[(left|center|right|justify)\](.*?)\[/(left|center|right|justify)\]#si", "<div style=\"text-align: $1;\" class=\"mycode_align\">$2</div>", $string);
            $string = preg_replace("#\[align=(left|center|right|justify)\](.*?)\[/align\]#si", "<div style=\"text-align: $1;\" class=\"mycode_align\">$2</div>", $string);
            $string = preg_replace('#\[highlight\=(.+)\](.+)\[\/highlight\]#iUs', '<span style="background:$1">$2</span>', $string);
            $string = preg_replace('#\[size\=(.+)\](.+)\[\/size\]#iUs', '<font size="$1">$2</font>', $string);
            $string = preg_replace('#\[blockquote\](.+)\[\/blockquote\]#iUs', '<blockquote class=\"quotemain\">$1</blockquote>', $string);
            $string = preg_replace('#\[indent\](.+)\[\/indent\]#iUs', '<indent>$1</indent>', $string);
	        $string = preg_replace('#\[color\=(.+)\](.+)\[\/color\]#iUs', '<font color="$1">$2</font>', $string);
	        $string = preg_replace('#\[font\=(.+)\](.+)\[\/font\]#iUs', '<font face="$1">$2</font>', $string);

        return $this->closetags($string);

 	}



 	    function PowerCode_Tag($tag, $message, $att = '')
        {
				if (trim($message) == '')
                {
                        return '';
                }
				if (count(explode('=',$att)) > 2)
                {
                        return $message;
                }
                $message = str_replace('\\"', '"', $message);
                if($tag == 'tag')
                {
                        return $message;
                }
                return "<$tag$att>$message</$tag>";
        }
         function PowerCode_Tag_BBcode($bbcode_replace,$bbcode_tag,$message)
        {
        	global $PowerBB;
				if (trim($message) == '')
                {
                        return '';
                }
                $bbcode_replace = ($bbcode_replace);
                $bbcode_replace = str_replace("&#39;","'",$bbcode_replace);
                $message = str_replace('\\"', '"', $message);
                $bbcode_replace = str_replace("#", '', $bbcode_replace);
                $bbcode_replace = str_replace("{content}",$message, $bbcode_replace);
                $bbcode_replace = str_replace("{option}","", $bbcode_replace);
                return $bbcode_replace;
        }

 	  function PowerCode_Quote($message)
      {
        	global $PowerBB;
		$message = trim($message);
       $message = str_replace("[/quote]<br />", "[/quote]", $message);
       $message = str_replace("[/quote]", "[/quote]<br />", $message);

		if(!$message)
		{
			return '';
		}
 		// Assign pattern and replace values.
		$pattern = "#\[quote\](.*?)\[\/quote\](\r\n?|\n?)#si";
		$pattern_callback = "#\[quote=([\"']|&quot;|)(.*?)(?:\\1)(.*?)(?:[\"']|&quot;)?\](.*?)\[/quote\](\r\n?|\n?)#si";

		if($text_only == false)
		{
			$replace = "<blockquote class=\"quotemain\"><cite><i class=\"fa fa-quote-left\" aria-hidden=\"true\"></i> ".$PowerBB->_CONF['template']['_CONF']['lang']['quote']." </cite>$1</blockquote>\n";
			$replace_callback = array($this, 'mycode_parse_post_quotes_callback1');
		}
		else
		{
			$replace = "\n".$PowerBB->_CONF['template']['_CONF']['lang']['quote']."\n--\n$1\n--\n";
			$replace_callback = array($this, 'mycode_parse_post_quotes_callback2');
		}
		do
		{
			// preg_replace has erased the message? Restore it...
			$previous_message = $message;
			$message = preg_replace($pattern, $replace, $message, -1, $count);
			$message = preg_replace_callback($pattern_callback, $replace_callback, $message, -1, $count_callback);
			if(!$message)
			{
				$message = $previous_message;
				break;
			}
		} while($count || $count_callback);

		if($text_only == false)
		{
			$find = array(
				"#(\r\n*|\n*)<\/cite>(\r\n*|\n*)#",
				"#(\r\n*|\n*)<\/blockquote>#"
			);

			$replace = array(
				"</cite><br />",
				"</blockquote>"
			);
			$message = preg_replace($find, $replace, $message);
			$message = "[quoted]". base64_encode($message)."[/quoted]";
		}
		return $message;
	  }


	/**
	* Parses quotes with post id and/or dateline.
	*
	* @param array $matches Matches.
	* @return string The parsed message.
	*/
	function mycode_parse_post_quotes_callback1($matches)
	{
		return $this->mycode_parse_post_quotes($matches[4],$matches[2].$matches[3]);
	}

	/**
	* Parses quotes with post id and/or dateline.
	*
	* @param array $matches Matches.
	* @return string The parsed message.
	*/
	function mycode_parse_post_quotes_callback2($matches)
	{
		return $this->mycode_parse_post_quotes($matches[4],$matches[2].$matches[3], true);
	}

	function mycode_parse_post_quotes($message, $username, $text_only=false)
	{
		global $PowerBB;

		$linkback = $date = "";

		$message = trim($message);
		$message = preg_replace("#(^<br(\s?)(\/?)>|<br(\s?)(\/?)>$)#i", "", $message);

		if(!$message)
		{
			return '';
		}
		$username .= "'";
		$delete_quote = true;

		preg_match("#id=(?:&quot;|\"|')?([0-9]+)[\"']?(?:&quot;|\"|')?#i", $username, $match);
		if(isset($match[1]) && (int)$match[1])
		{
			$id = (int)$match[1];
            $PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');
            $id = $PowerBB->functions->CleanVariable($id,'intval');
             $GetSubjectid = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['reply'] . " WHERE id='$id'");
             $SubjectidRow = $PowerBB->DB->sql_fetch_array($GetSubjectid);
             $Subjectid = $SubjectidRow['subject_id'];

            if(!$SubjectidRow)
            {
			$url = '';
			}
            elseif($id == $PowerBB->_GET['id'])
            {
			$pid= "index.php?page=topic&show=1&id=".$Subjectid."";
			$url = $pid;
			}
			else
            {

			$Reply_NumArr = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['reply'] . " WHERE subject_id='$Subjectid' and id <'$id' and delete_topic <>1"));
			$ss_r = $PowerBB->_CONF['info_row']['perpage']/2+1;
			$roun_ss_r = round($ss_r, 0);
			$reply_number_r = $Reply_NumArr-$roun_ss_r+1;
			$pagenum_r = $reply_number_r/$PowerBB->_CONF['info_row']['perpage'];
			$round0_r = round($pagenum_r, 0);
			$countpage = $round0_r+1;
			$countpage = str_replace("-", '', $countpage);

				if($Reply_NumArr <= $PowerBB->_CONF['info_row']['perpage'])
				{
				$pid= "index.php?page=topic&amp;show=1&amp;id=".$Subjectid."#".$id."";
				}
				elseif($Reply_NumArr > $PowerBB->_CONF['info_row']['perpage'])
				{
				$pid= "index.php?page=topic&amp;show=1&amp;id=".$Subjectid.'&amp;count=' . $countpage. "#".$id."";
				}
			$url = $pid;
			}

			$username = preg_replace("#(?:&quot;|\"|')? id=(?:&quot;|\"|')?[0-9]+[\"']?(?:&quot;|\"|')?#i", '', $username);
			$delete_quote = false;
		}

		unset($match);
		preg_match("#write_time=(?:&quot;|\"|')?([0-9]+)(?:&quot;|\"|')?#i", $username, $match);
		if(isset($match[1]) && (int)$match[1])
		{
			if($match[1] < $PowerBB->_CONF['now'])
			{
				 $postdate = $PowerBB->functions->_date((int)$match[1]);

				$date = $postdate;
			}
			$username = preg_replace("#(?:&quot;|\"|')? write_time=(?:&quot;|\"|')?[0-9]+(?:&quot;|\"|')?#i", '', $username);
			$delete_quote = false;
		}

		if($delete_quote)
		{
			$username = $PowerBB->functions->my_substr($username, 0, $PowerBB->functions->my_strlen($username)-1, true);
		}

		$username = $this->htmlspecialchars_uni($username);

       if(!$date)
		{
			$date = $PowerBB->functions->_date($SubjectidRow['write_time']);
		}

		if($text_only)
		{
			return "\n".$username." ".$wrote.$date."\n--\n".$message."\n--\n";
		}
		else
		{
			$span = "";
			if(!$delete_quote)
			{
				$span = "<span><i>".$PowerBB->_CONF['template']['_CONF']['lang']['last_date']."</i> <date>".$date."</date></span>";
			}
			if (!empty($url))
			{
            $linkback = '<a href="'.$url.'" rel="nofollow" title="'.$PowerBB->_CONF['template']['_CONF']['lang']['show_post_normal'].'"><i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a>';
            }

			$mycode_quote = '<blockquote class="quotemain"><cite><i class="fa fa-quote-left" aria-hidden="true"></i><span class="quotelang"> '.$PowerBB->_CONF['template']['_CONF']['lang']['quote_username'].' </span>  <b>'.$username.'</b> '.$span. $PowerBB->functions->rewriterule($linkback).'</cite>'.$message.'</blockquote><br />';
			return $mycode_quote;
		}
	}

	  function text_with_hyperlink($string)
        {
        	global $PowerBB;
			if (trim($string) == '')
			{
			return '';
			}
               eval($PowerBB->functions->get_fetch_hooks('BBCodeParseHooks5'));
              $string = str_replace('\\"', '"', $string);
				$Guest_message = ($PowerBB->_CONF['info_row']['guest_message_for_haid_links']);
				$register_link = ('index.php?page=register&amp;index=1');
				if ($PowerBB->_CONF['info_row']['haid_links_for_guest'] == 0)
				{
			        $string = preg_replace(",([^]_a-z0-9-=\"'\/])((https?|ftp|gopher|news|telnet):\/\/|www\.)([^ \r\n\(\)\*\^\$!`\"'\|\[\]\{\}<>]*),i", "<a href=\"$2$4\" target=\"_blank\" title=\"$2$4\">$2$4</a>",$string);
			        $string = preg_replace(",^((https?|ftp|gopher|news|telnet):\/\/|\.)([^ \r\n\(\)\*\^\$!`\"'\|\[\]\{\}<>]*),i", "<a href=\"$1$3\" target=\"_blank\" title=\"$1$3\">$1$3</a>",$string);
				}
				else
				{
	                 if ($PowerBB->_CONF['member_permission'])
					 {
			           $string = preg_replace(",([^]_a-z0-9-=\"'\/])((https?|ftp|gopher|news|telnet):\/\/|www\.)([^ \r\n\(\)\*\^\$!`\"'\|\[\]\{\}<>]*),i", "<a href=\"$2$4\" target=\"_blank\" title=\"$2$4\">$2$4</a>",$string);
			           $string = preg_replace(",^((https?|ftp|gopher|news|telnet):\/\/|\.)([^ \r\n\(\)\*\^\$!`\"'\|\[\]\{\}<>]*),i", "<a href=\"$1$3\" target=\"_blank\" title=\"$1$3\">$1$3</a>",$string);
                     }
	                if (!$PowerBB->_CONF['member_permission'])
					{
			        $string = preg_replace(",([^]_a-z0-9-=\"'\/])((https?|ftp|gopher|news|telnet):\/\/|www\.)([^ \r\n\(\)\*\^\$!`\"'\|\[\]\{\}<>]*),i", "<a href=\"$register_link\" target=\"_blank\" title=\"$Guest_message\">$Guest_message</a>",$string);
			        $string = preg_replace(",^((https?|ftp|gopher|news|telnet):\/\/|\.)([^ \r\n\(\)\*\^\$!`\"'\|\[\]\{\}<>]*),i", "<a href=\"$register_link\" target=\"_blank\" title=\"$Guest_message\">$Guest_message</a>",$string);
			        $string = preg_replace('/<a(.*?)>(.*?)<\/a>/is', "<a href=\"$register_link\" target=\"_blank\" title=\"$Guest_message\">$Guest_message</a>",$string);
  	                }
				}

			$regex_url = '#\[url\](.*)\[/url\]#siU';
			$string = preg_replace_callback($regex_url, function($x_url) {
			return $this->PowerCode_Tag_Url($x_url[1],$x_url[1]);
			}, $string);

			$regexx_url = '#\[url=(.*)\](.*)\[/url\]#siU';
			$string = preg_replace_callback($regexx_url, function($xx_url) {
			return $this->PowerCode_Tag_Url($xx_url[1],$xx_url[2]);
			}, $string);

			$regex_email = '#\[email\](.*)\[/email\]#siU';
			$string = preg_replace_callback($regex_email, function($x_email) {
			return $this->PowerCode_Tag_Url('mailto:'.$x_email[1],$x_email[1]);
			}, $string);

			$regexx_email = '#\[email=(.*)\](.*)\[/email\]#siU';
			$string = preg_replace_callback($regexx_email, function($xx_email) {
			return $this->PowerCode_Tag_Url('mailto:'.$xx_email[1],$xx_email[2]);
			}, $string);


			$regexx_email = '#\[url=mailto:(.*)\](.*)\[/url\]#siU';
			$string = preg_replace_callback($regexx_email, function($xx_email) {
			return $this->PowerCode_Tag_Url('mailto:'.$xx_email[1],$xx_email[2]);
			}, $string);

			$regexxx_email = '/([ \n\r\t])([_a-z0-9-+]+(\.[_a-z0-9-+]+)*@[^\s]+(\.[a-z0-9-]+)*(\.[a-z]{2,6}))/si';
			$string = preg_replace_callback($regexxx_email, function($xxx_email) {
			return $this->PowerCode_Tag_Url('mailto:'.$xxx_email[2],$xxx_email[2]);
			}, $string);

			$regexxxx_email = '/^([_a-z0-9-+]+(\.[_a-z0-9-+]+)*@[^\s]+(\.[a-z0-9-]+)*(\.[a-z]{2,6}))/si';
			$string = preg_replace_callback($regexxxx_email, function($xxxx_email) {
			return $this->PowerCode_Tag_Url('mailto:'.$xxxx_email[0],$xxxx_email[0]);
			}, $string);



            $string = str_replace('"<a', '"><a', $string);
            $string = str_replace('href="www.', 'href="http://www.', $string);
            eval($PowerBB->functions->get_fetch_hooks('BBCodeParseHooks4'));
			return $string;
        }


		function PowerCode_Youtube($linky, $messages)
        {
            	global $PowerBB;
                $linky = str_replace('\\"', '"', $linky);
                $linky = str_ireplace("youtube.com/embed/", "youtube.com/watch?v=", $linky);
                $linky = str_ireplace("youtu.be/", "youtube.com/watch?v=", $linky);
                $linky = str_replace("youtube.com/embed/", "youtube.com/watch?v=", $linky);
                $linky = str_replace("youtu.be/", "youtube.com/watch?v=", $linky);
                $linky = str_replace('&lt;br /&gt;"', "\r\n", $linky);
                $linky = str_replace("&lt;", "<", $linky);
                $linky = str_replace("&quot;", '"', $linky);
                $linky = str_replace("&gt;", ">", $linky);
                $linky = str_replace(array('"', "'"), array('&quot;', '&#39;'), $linky);
                $linky = str_replace(array('/watch?', "v="), array('/v', '/'), $linky);
                $linky = str_replace("/v/", "/embed/", $linky);
             return '<iframe width="560" height="315" src="'.$linky.'" title="YouTube video player" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" frameborder="0" allowfullscreen></iframe>';
         }
		function PowerCode_BBcode($option, $content, $bbcode_tag)
        {
		      global $PowerBB;
                $content = str_replace('\\"', '"', $content);
            if ($PowerBB->_POST['preview'] == false)
            {
                    $content = $PowerBB->functions->pbb_stripslashes($content);
            }
                $querybbcode1 = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['custom_bbcode'] . " WHERE bbcode_tag = '$bbcode_tag' ");
				$bbcode_row1 = $PowerBB->DB->sql_fetch_array($querybbcode1);
				$bbcode_replace = $bbcode_row1['bbcode_replace'];
                $bbcode_replace = str_replace("&#39;","'",$bbcode_replace);
                $bbcode_replace = str_replace( "{option}",$option , $bbcode_replace );
                $bbcode_replace = str_replace("{content}", $content, $bbcode_replace);
                return $bbcode_replace;
         }

 	    function DoListli($txt)
        {
        	global $PowerBB;
                if (trim($txt) == '')
                {
                        return '';
                }
                $txt = str_replace('\\"', '"', $txt);
           return '' . $txt . '';
        }

        function DoList($mark,$item = '')
         {
                  if ($mark=="1")
                  {
                      $tag = "ol";
                  }
                  else
                  {
                      $tag = "ul";
                  }
                  $return = "<".$tag.">";
                  $new_item = explode("[*]" , $item);
                  $new_item = str_replace('\\"', '"', $new_item);
                  for ($i=1; $i <= count($new_item); $i++)
                  {
                       if ($new_item[$i]!="")
                       {
                           $return .= "<li>".$new_item[$i]."</li>";
                       }
                  }
                  $return .= "</".$tag.">";
                  return $return;
         }

 	function censor_words($text)
	{
		global $PowerBB;
       // feltr words
    	 static $blanks = null;

         $text = str_replace('\\"', '"', $text);
		$li_not = '#\<li\>(.*)\</li\>#siU';
		$text = preg_replace_callback($li_not, function($lidd) {
		return $this->li_not_bar($lidd[1]);
		}, $text);
		 $text = str_replace("&amp;","&",$text);
         $text = str_replace('{39}',"'",$text);
         $text = str_replace('&#092;&#092;',"&#092;",$text);
         $text = str_replace("&amp;#39;","'",$text);
         $text = str_replace("&#39;","'",$text);
         $text = str_replace('<p align="left">','<p dir="ltr" align="left">',$text);
         $text = str_replace("&amp;#092;","\(:*:)",$text);
         $text = str_replace("(:*:)","",$text);
		 $text = str_replace( "#text_area#"   , "textarea ", $text );
         $text = str_ireplace("<script", "&lt;script", $text);
         $text = str_ireplace("<meta", "&lt;meta", $text);

          // nofollow links out said
		if (isset($PowerBB->_SERVER['HTTPS']) &&
		    ($PowerBB->_SERVER['HTTPS'] == 'on' || $PowerBB->_SERVER['HTTPS'] == 1) ||
		    isset($PowerBB->_SERVER['HTTP_X_FORWARDED_PROTO']) &&
		    $PowerBB->_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
		  $protocol = 'https://';
		}
		else {
		  $protocol = 'http://';
		}
        $text = str_ireplace('target="_blank"', '', $text);
        $text = str_ireplace("target='_blank'", '', $text);
        $text = str_ireplace('rel="dofollow"', '', $text);
        $text = str_ireplace('rel="nofollow"', '', $text);
		$GetHOSThttp  = $protocol.$PowerBB->_SERVER['HTTP_HOST'];
		$text = str_ireplace('href="'.$GetHOSThttp,'rel="dofollow" href="'.$GetHOSThttp,$text);
		$text = str_ireplace('href="'.!$GetHOSThttp,'rel="nofollow" href="'.!$GetHOSThttp,$text);
		$GetHOSThttps  = $protocol.$PowerBB->_SERVER['HTTP_HOST'];
		$text = str_ireplace('href="'.$GetHOSThttps,'rel="dofollow" href="'.$GetHOSThttps,$text);
		$text = str_ireplace('href="'.!$GetHOSThttps,'rel="nofollow" href="'.!$GetHOSThttps,$text);
		$GetHOSTwww  = "www.".$PowerBB->_SERVER['HTTP_HOST'];
		$text = str_ireplace('href="'.$GetHOSTwww,'rel="dofollow" href="'.$GetHOSTwww,$text);
		$text = str_ireplace('href="'.!$GetHOSTwww,'rel="nofollow" href="'.!$GetHOSTwww,$text);
		$GetHOSThttpwww  = $protocol."www.".$PowerBB->_SERVER['HTTP_HOST'];
		$text = str_ireplace('href="'.$GetHOSThttpwww,'rel="dofollow" href="'.$GetHOSThttpwww,$text);
		$text = str_ireplace('href="'.!$GetHOSThttpwww,'rel="nofollow" href="'.!$GetHOSThttpwww,$text);
		$GetHOSThttpswww  = $protocol."www.".$PowerBB->_SERVER['HTTP_HOST'];
		$text = str_ireplace('href="'.$GetHOSThttpswww,'rel="dofollow" href="'.$GetHOSThttpswww,$text);
		$text = str_ireplace('href="'.!$GetHOSThttpswww,'rel="nofollow" href="'.!$GetHOSThttpswww,$text);

        //
        $text = str_ireplace('rel="dofollow" rel="dofollow"', '', $text);
        $text = str_ireplace('rel="nofollow" rel="nofollow"', '', $text);
        $text = str_ireplace('rel="dofollow"  rel="nofollow"', 'rel="dofollow"', $text);
        $text = str_ireplace('"   rel="', '" rel="', $text);
        $text = str_ireplace('blank"  rel="', 'blank" rel="', $text);
        $text = str_ireplace('rel="nofollow" href="#', 'href="#', $text);
        $text = str_ireplace('rel="dofollow" href="#', 'href="#', $text);
            eval($PowerBB->functions->get_fetch_hooks('BBCodeParseHooks3'));
       // $text = strip_tags($text);
        $censorwords = preg_split('#[ \r\n\t]+#', $PowerBB->_CONF['info_row']['censorwords'], -1, PREG_SPLIT_NO_EMPTY);
        $text = str_ireplace($censorwords,'**', $text);
         $blankasciistrip ="160 173 u8205 u8204 u8237 u8238";
		if ($blanks === null AND trim($blankasciistrip) != '')
		{
			$blanks = array();
			$charset_unicode = (strtolower($PowerBB->_CONF['info_row']['charset']) == 'utf-8');
			$raw_blanks = preg_split('#\s+#', $blankasciistrip, -1, PREG_SPLIT_NO_EMPTY);
			foreach ($raw_blanks AS $code_point)
			{
				if ($code_point[0] == 'u')
				{
					// this is a unicode character to remove
					$code_point = intval(substr($code_point, 1));
					$force_unicode = true;
				}
				else
				{
					$code_point = intval($code_point);
					$force_unicode = false;
				}
				if ($code_point > 255 OR $force_unicode OR $charset_unicode)
				{
					// outside ASCII range or forced Unicode, so the chr function wouldn't work anyway
					$blanks[] = '&#' . $code_point . ';';
					$blanks[] = $this->convert_int_to_utf8($code_point);
				}
				else
				{
					$blanks[] = chr($code_point);
				}
			}
		}
		if ($blanks)
		{
			//$text = str_replace($blanks, '**', $text);
		}

         //Replace YouTube links the video directly
             /*
			$text = str_ireplace("[youtube]","",$text);
			$text = str_ireplace("[/youtube]","",$text);
			$search = '#<a(.*?)(?:href="https?://)?(?:www\.)?(?:youtu\.be/|youtube\.com(?:/embed/|/v/|/watch?.*?v=))([\w\-]{10,12}).*<\/a>#x';
			$replace = $this->PowerCode_Youtube("$2", "$2");
			$text =preg_replace($search, $replace, $text);
             */

          // long URL, Shortening Long URLs With PHP
 	    //$text = preg_replace('#\<a(.*)\">(.*)\</a\>#siU',"\$this->shortenurl('\\1','\\2','44')",$text);
       		//$text = str_replace("<br>","\r\n",$text);
       		//$text =preg_replace('#<span (.*) style="(.*)".*>#iU', '<span>', $text);



					$regeximgtrim= '#<img .*src="(.*)".*>#iU';
					$text = preg_replace_callback($regeximgtrim, function($img_array_trim) {
					return '[img]'.trim($img_array_trim[1].'[/img]');
					}, $text);

                    $regeximg = array();
					$regeximg= '#\[IMG\]\s*(https?://([^<>*"]+|[a-z0-9/\\._\- !]+))\[/IMG\]#iU';
					$regeximg= '#\[IMG\](.*)\[/IMG\]#siU';
					$regeximg= '#\[img\](.*)\[/img\]#siU';
					$text = preg_replace_callback($regeximg, function($img_array) {
					return $this->resize_image($img_array[1]);
					}, $text);


                    $regeximg_sized = array();
                    $regeximg_sized = "#\[img=([1-9][0-9]*)x([1-9][0-9]*)\]([^<>\"']+?)\[/img\]#is";
					$text = preg_replace_callback($regeximg_sized, function($img_array_sized) {
					return $this->resize_image_callback($img_array_sized[1],$img_array_sized[2],$img_array_sized[3]);
					}, $text);

                    $regeximg_sized = array();
                    $regeximg_sized = "#\[img=([1-9][0-9]*)x([1-9][0-9]*)\](\r\n?|\n?)(https?://([^<>\"']+?))\[/img\]#is";
					$text = preg_replace_callback($regeximg_sized, function($img_array_sized) {
					return $this->resize_image_callback($img_array_sized[1],$img_array_sized[2],$img_array_sized[4]);
					}, $text);


		// Convert videos when allow.
			$text = preg_replace_callback("#\[video=(.*?)\](.*?)\[/video\]#i", array($this, 'mycode_parse_video_callback'), $text);


			//replace Custom bbcode
	        $Custom_bbcodes = $PowerBB->functions->GetCachedCustom_bbcode();
	        if(!empty($Custom_bbcodes))
	        {
              foreach ($Custom_bbcodes as $getbbcode_row)
		      {

		      	$bbcode_tag = $getbbcode_row['bbcode_tag'];
		      	$bbcode_replace = $getbbcode_row['bbcode_replace'];
                $bbcode_replace = str_ireplace("'","&#39;",$bbcode_replace);
				if ($getbbcode_row['bbcode_useoption'] == '1')
				{

					if(preg_match("#\[".$bbcode_tag."=(.*?)\](.*?)\[/".$bbcode_tag."\](\r\n?|\n?)#is", $text, $matches))
					{
		              $textbcode = $this->PowerCode_BBcode($matches[1],$matches[2],$bbcode_tag);
                      $text = str_replace("[".$bbcode_tag."=".$matches[1]."]".$matches[2]."[/".$bbcode_tag."]",$textbcode, $text);
		            }
				}
				else
				{
					if(preg_match("#\[".$bbcode_tag."\](.*?)\[/".$bbcode_tag."\]#is", $text, $matches))
					{
		               $textbcode  = $this->PowerCode_Tag_BBcode($bbcode_replace,$bbcode_tag,$matches[1]);
                       $text = str_replace("[".$bbcode_tag."]".$matches[1]."[/".$bbcode_tag."]",$textbcode, $text);

		            }
			    }
		      }

            }

           $text = $this->xss_clean($text);

			$text = str_ireplace("{h-h}", "http", $text);
			$text = str_ireplace("{w-w}", "www.", $text);
            $text = str_replace('rel="dofollow" rel="nofollow"', '', $text);
            $text = str_replace('  rel="dofollow"   ', ' rel="dofollow" ', $text);

          $text = str_replace("<br>", "<br />", $text);
          //XSS filtering function
            eval($PowerBB->functions->get_fetch_hooks('BBCodeParseHooks_cr'));


			    $regexcode_html['[html]'] = '#\[html\](.*)\[/html\]#siU';
				$text = preg_replace_callback($regexcode_html, function($matches) {
					$matches[1] = ($matches[1]);
					$matches[1] = str_replace("&amp;#39;", "'", $matches[1]);
			        return '<div class="maxy"></div><div class="codediv">Html</div><pre><code class="language-html">'.$matches[1].'</code></pre><div class="maxy"></div>';
				}, $text);

			   $regexcode_js['[js]'] = '#\[js\](.*)\[/js\]#siU';
				$text = preg_replace_callback($regexcode_js, function($matches) {
					$matches[1] = $this->fix_javascript($matches[1]);
					$matches[1] = str_replace("&amp;#39;", "'", $matches[1]);
			        return '<div class="maxy"></div><div class="codediv">Java</div><pre><code class="language-java">'.$matches[1].'</code></pre><div class="maxy"></div>';
				}, $text);

			   $regexcode_css['[css]'] = '#\[css\](.*)\[/css\]#siU';
				$text = preg_replace_callback($regexcode_css, function($matches) {
					$matches[1] = ($matches[1]);
					$matches[1] = str_replace("&amp;#39;", "'", $matches[1]);
			        return '<div class="maxy"></div><div class="codediv">CSS</div><pre><code class="language-css">'.$matches[1].'</code></pre><div class="maxy"></div>';
				}, $text);

			    $regexcode_xml['[xml]'] = '#\[xml\](.*)\[/xml\]#siU';
				$text = preg_replace_callback($regexcode_xml, function($matches) {
					$matches[1] = ($matches[1]);
					$matches[1] = str_replace("&amp;#39;", "'", $matches[1]);
			        return '<div class="maxy"></div><div class="codediv">XML</div><pre><code class="language-xml">'.$matches[1].'</code></pre><div class="maxy"></div>';
				}, $text);

			    $regexcode_sql['[sql]'] = '#\[sql\](.*)\[/sql\]#siU';
				$text = preg_replace_callback($regexcode_sql, function($matches) {
					$matches[1] = ($matches[1]);
					$matches[1] = str_replace("&amp;#39;", "'", $matches[1]);
			        return '<div class="maxy"></div><div class="codediv">SQL</div><pre><code class="language-sql">'.$matches[1].'</code></pre><div class="maxy"></div>';
				}, $text);

                // end decode php code
			    $regexcode_code['[code]'] = '#\[code\](.*)\[/code\]#siU';
				$text = preg_replace_callback($regexcode_code, function($matches) {
				   $matches[1] = base64_decode($matches[1]);
				   $matches[1] = htmlspecialchars($matches[1]);
                    $matches[1] = $this->remove_strings($matches[1]);
			        return '<div class="maxy"></div><div class="codediv">CODE</div><pre><code class="language-php">'.$matches[1].'</code></pre><div class="maxy"></div>';
				}, $text);
				$regexcode['php'] = '#\[php\](.*)\[/php\]#siU';
				$text = preg_replace_callback($regexcode, function($matches) {
				   $matches[1] = base64_decode($matches[1]);
				   $matches[1] = htmlspecialchars($matches[1]);
				   $matches[1] = str_replace("&amp;", "&", $matches[1]);
				return '<div class="maxy"></div><div class="codediv">PHP</div><pre><code class="language-php">'.$matches[1].'</code></pre><div class="maxy"></div>';
				}, $text);


        return $this->replace($text."n-l-2-b-r");
	}

    //XSS filtering function
	function xss_clean($data)
	{

		// start filtering tags
		$regexcodexss = '#\<(.*)\>#siU';
		$data = preg_replace_callback($regexcodexss, function($matches) {
		$matches[1] = html_entity_decode($matches[1], ENT_COMPAT, 'UTF-8');
		$matches[1] = str_ireplace('alert', '', $matches[1]);
		$matches[1] = str_replace('(', '', $matches[1]);
		$matches[1] = str_replace(')', '', $matches[1]);
		$matches[1] = str_replace('<', '', $matches[1]);
		$matches[1] = str_ireplace('document.cookie', '', $matches[1]);
		$matches[1] = str_ireplace('onclick', '', $matches[1]);
		$matches[1] = str_ireplace('absolute',"a*bsolute",$matches[1]);
		$matches[1] = str_ireplace('equiv',"e*quiv",$matches[1]);
		$matches[1] = str_ireplace('refresh',"r*efresh",$matches[1]);
		$matches[1] = str_ireplace('meta',"m*eta",$matches[1]);
		$matches[1] = str_ireplace('input',"i*nput",$matches[1]);
		$matches[1] = str_ireplace('action',"a*ction",$matches[1]);
		return "<".$matches[1].">";
		}, $data);

	// Fix &entity\n;
	$data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
	$data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
	$data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
	$data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

	// Remove any attribute starting with "on" or xmlns
	$data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

	// Remove javascript: and vbscript: protocols
	$data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
	$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
	$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

	// Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
	$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
	$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
	$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

	// Remove namespaced elements (we do not need them)
	$data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

	do
	{
	    // Remove really unwanted tags
	    $old_data = $data;
        $input = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $input);
	}
	while ($old_data !== $data);

	// we are done...

	return $data;
	}



   // long URL, Shortening Long URLs With PHP
 	function shortenurl($Aurl,$Burl,$lg_max)
	{
		global $PowerBB;
       $Burl = str_replace('\\"', '"', $Burl);
	   $Burl = str_replace('\"','"',$Burl);
	   $Aurl = str_replace('\"','"',$Aurl);
       $Aurl = str_replace('\\"', '"', $Aurl);
       $Burl = preg_replace('#\<a(.*)\">#siU',"('')",$Burl);
	   $Burl = str_replace('"', '', $Burl);
	   $Burl = str_replace('</a>', '', $Burl);
	   $Burl = str_replace('  ', ' ', $Burl);
	  // $Burl = str_replace('>', '', $Burl);
		$num =$lg_max;
		$start ='0';
		$length = strlen(utf8_decode($Burl));
		if ($length >= $lg_max)
		{
		if (strstr($Burl,'https://')
		or strstr($Burl,'http://')
		or strstr($Burl,'http://www.'))
		{
		$Burl = preg_replace('#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'. $start .'}'.'((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'. $num .'}).*#s','$1', $Burl);
		}
		}
		$length2 = strlen(utf8_decode($Burl));
		if($length2 == $lg_max)
		{
		$sfdr = " ... ";
		}
		$url = "<a".$Aurl.'">'.$Burl.$sfdr."</a>";
		return $url;
	}
  // jwplayer run withe image url
 	function jwplayer($width, $height, $auto, $imageUrl, $fileUrl)
	{
		global $PowerBB;
			$width = str_replace('\\"', '"', $width);
			$height = str_replace('\\"', '"', $height);
			$auto = str_replace('\\"', '"', $auto);
			$imageUrl = str_replace('\\"', '"', $imageUrl);
			$fileUrl = str_replace('\\"', '"', $fileUrl);
			$imageUrl = str_ireplace("http", "{h-h}", $imageUrl);
			$imageUrl = str_ireplace("www.", "{w-w}", $imageUrl);
			$fileUrl = str_ireplace("http", "{h-h}", $fileUrl);
			$fileUrl = str_ireplace("www.", "{w-w}", $fileUrl);
	        $imageUrl = $PowerBB->functions->CleanVariable($imageUrl,'trim');
	        $fileUrl = $PowerBB->functions->CleanVariable($fileUrl,'trim');
	        $imageUrl = $PowerBB->functions->CleanVariable($imageUrl,'sql');
	        $fileUrl = $PowerBB->functions->CleanVariable($fileUrl,'sql');
	        $width = $PowerBB->functions->CleanVariable($width,'intval');
	        $height = $PowerBB->functions->CleanVariable($height,'intval');
	        if( $imageUrl == "false")
	        {
	        $url = $PowerBB->functions->GetForumAdress();
	         $imageUrl = $url."look/images/pbboard.png";
	        }
	       if ($PowerBB->functions->checkmobile())
			{
	        $width = '250';
	        $height = '250';
			}
			// jwplayer tag replace
			$jwplayer = "<div style=\" margin:0 auto;width:'$width'px;height:'$height'px;\" data-width=\"$width\" data-height=\"$height\" data-auto=\"$auto\" data-image=\"$imageUrl\" data-url=\"$fileUrl\" class=\"jwplayer-html5-item\"></div><br />";
			return $jwplayer;
	}
	/** * close all open xhtml tags at the end of the string
	* * param string $html
	* @return string
	* @author Milian <mailmili.de>
	*/
	 function closetags($html)
	 {
	  #put all opened tags into an array
	  preg_match_all('#<([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result);
	  $openedtags = $result[1];   #put all closed tags into an array
	  preg_match_all('#</([a-z]+)>#iU', $html, $result);
	  $closedtags = $result[1];
	  $len_opened = count($openedtags);
	  # all tags are closed
	  if (count($closedtags) == $len_opened) {
	    return $html;
	  }
	  $openedtags = array_reverse($openedtags);
	  # close tags
	  for ($i=0; $i < $len_opened; $i++) {
	    if (!in_array($openedtags[$i], $closedtags)){
	      $html .= '</'.$openedtags[$i].'>';
	    } else {
	      unset($closedtags[array_search($openedtags[$i], $closedtags)]);
	       }
	  }
      $html = str_ireplace('</img>','',$html);
	   return $html;
	   }
 	function feltr_words($text)
	{
		global $PowerBB;
       // feltr words2
         $text = str_replace('&lt;','<',$text);
         $text = str_replace('&quot;','"',$text);
         $text = str_replace('&gt;','>',$text);
         $text = str_replace("&amp;#39;","'",$text);
		 $text = $PowerBB->functions->pbb_stripslashes($text);
         $text = str_replace('{39}',"'",$text);
        $censorwords = preg_split('#[ \r\n\t]+#', $PowerBB->_CONF['info_row']['censorwords'], -1, PREG_SPLIT_NO_EMPTY);
        $text = str_ireplace($censorwords,'**', $text);
         $blankasciistrip ="160 173 u8205 u8204 u8237 u8238";
		if ($blanks === null AND trim($blankasciistrip) != '')
		{
			$blanks = array();
			$charset_unicode = (strtolower($PowerBB->_CONF['info_row']['charset']) == 'utf-8');
			$raw_blanks = preg_split('#\s+#', $blankasciistrip, -1, PREG_SPLIT_NO_EMPTY);
			foreach ($raw_blanks AS $code_point)
			{
				if ($code_point[0] == 'u')
				{
					// this is a unicode character to remove
					$code_point = intval(substr($code_point, 1));
					$force_unicode = true;
				}
				else
				{
					$code_point = intval($code_point);
					$force_unicode = false;
				}
				if ($code_point > 255 OR $force_unicode OR $charset_unicode)
				{
					// outside ASCII range or forced Unicode, so the chr function wouldn't work anyway
					$blanks[] = '&#' . $code_point . ';';
					$blanks[] = $this->convert_int_to_utf8($code_point);
				}
				else
				{
					$blanks[] = chr($code_point);
				}
			}
		}
		if ($blanks)
		{
			//$text = str_replace($blanks, '**', $text);
		}
        return $text;
	}
 	function replace_smiles(&$text)
	{
		global $PowerBB;
		$smiles = $PowerBB->icon->GetCachedSmiles();
		if($smiles)
		{
			$text = str_replace("../","", $text);
			foreach ($smiles as $smile)
			{
				$PowerBB->functions->CleanVariable($smile,'html');
			if (defined('IN_ADMIN'))
			{
				$smile['smile_short'] = str_ireplace(":#","{:#}", $smile['smile_short']);
				$text = str_replace($smile['smile_short'],'<img src="' . "../".$smile['smile_path'] . '" border="0" alt="' . $smile['smile_short'] . '" />',$text);
			}
			else
			{
				$smile['smile_short'] = str_ireplace(":#","{:#}", $smile['smile_short']);
				$text = str_replace($smile['smile_short'],'<img src="' . $smile['smile_path'] . '" border="0" alt="' . $smile['smile_short'] . '" />',$text);
			}
	        		$text = str_replace("smiles//","smiles/", $text);
			}
		}
		else
		{
		$cache = $PowerBB->icon->UpdateSmilesCache(null);
		}
	}
 	function replace_smiles_print(&$text)
	{
		global $PowerBB;
		$text = str_replace("../","", $text);
		$smiles = $PowerBB->icon->GetCachedSmiles();
		foreach ($smiles as $smile)
		{
            $Adress = $PowerBB->functions->GetForumAdress();
			$text = str_replace($smile['smile_short'],'<img src="' . $Adress.$smile['smile_path'] . '" border="0" />',$text);
			$text = str_replace("smiles//","smiles/", $text);
		}
	}
	function replace_wordwrap(&$text)
	{
		global $PowerBB;
		/*
        $wordwrap = $PowerBB->_CONF['info_row']['wordwrap'];
		$wordwrap = intval($wordwrap);
		if ($wordwrap > 0 AND !empty($text))
		{
		 $text = preg_replace("/([^\s]{".$wordwrap."})/", "$1 ", ($text));
		 return $text;
		}
		else
		{
		 return $text;
		}
		*/
	}
 	function _wordwrap($text,$lg_max)
	{
		global $PowerBB;
       $num =$lg_max;
       $start ='0';
       $sfdr = "";

       $text = preg_replace('#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'. $start .'}'.'((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'. $num .'}).*#s','$1', $text);
		if (function_exists('mb_strlen'))
		{
		$length = mb_strlen(utf8_decode($text));
		}
		else
		{
		$length = strlen(utf8_decode($text));
		}
       if($length == $lg_max)
       {
       	$sfdr = " .. ";
       }
       return $text.$sfdr;
	}

	function ShortPhrase($String, $Letters)
	{
		if (function_exists('mb_strlen'))
		{
		$strlen = mb_strlen(utf8_decode($String));
		}
		else
		{
		$strlen = strlen(utf8_decode($String));
		}
	    if ($strlen > $Letters) {
	        $sub = substr($String,$Letters,1);
	        if ($sub != " ") {
	            while($sub !=" "){
	                $Letters++;
	                $sub = substr($String,$Letters,1);
	            }
	        }
	        $newtext1 = substr($String,0,$Letters);
	    }else {
	        $newtext2 = $String;
	    }
	    if ($newtext1 != "") {
	        $newtext = $newtext1." ...";
	    }else {
	        $newtext = $newtext2;
	    }
	    return $newtext;
	}

       function PowerCode_Tag_Url($link, $message)
        {
        	global $PowerBB;
          $Guest_message = ($PowerBB->_CONF['info_row']['guest_message_for_haid_links']);
          $register_link = ('index.php?page=register&index=1');
                if (trim($message) == '')
                {
                    $message = $link;

                }
                $message = str_replace('\\"', '"', $message);
                $message = str_ireplace("&quot;", '"', $message);
                $link = str_ireplace("&quot;", '"', $link);


                $link = preg_replace('#<a href="(.*?)" (.*?)</a>#i', "$1", $link);

            eval($PowerBB->functions->get_fetch_hooks('BBCodeParseHooks6'));

				if ($PowerBB->_CONF['info_row']['haid_links_for_guest'] == 0)
				{
				 $url = "<a href=\"$link\" target=\"_blank\" title=\"$link\">$message</a>";
				}
				else
				{
	                 if ($PowerBB->_CONF['member_permission'])
					 {
						$url = "<a href=\"$link\" target=\"_blank\" title=\"$link\">$message</a>";
					 }
	                if (!$PowerBB->_CONF['member_permission'])
					{
					 $url = "<a href=\"$register_link\" target=\"_blank\" title=\"$register_link\">$Guest_message</a>";
	                }
				}
            eval($PowerBB->functions->get_fetch_hooks('BBCodeParseHooks_url'));
			return $url;
        }
    function li_not_bar($text)
    {
        global $PowerBB;
        $text = str_replace('\\"', '"', $text);
        $text = str_replace("&lt;br&gt;","<br>",$text);
        $text = str_replace("&lt;br /&gt;","<br>",$text);
        $text = str_replace("<br />", '',$text);
        $text = str_replace("<br>", '',$text);
        $text = str_replace("\n", '',$text);
        $text = str_replace("\t", '',$text);
        $text = str_replace("\r", '',$text);
        $text = str_replace("&nbsp;", '',$text);
        $text = str_replace("\s", '',$text);
        $text = $PowerBB->functions->pbb_stripslashes($text);
		return "<li>".$text."</li>";
     }
	function strip_smiles(&$text)
	{
   		global $PowerBB;
		$smiles = $PowerBB->icon->GetCachedSmiles();
		foreach ($smiles as $smile)
		{
			$PowerBB->functions->CleanVariable($smile,'html');
			$text = str_replace('<img src="' . $smile['smile_path'] . '" border="0" alt="' . $smile['smile_short'] . '" />',$smile['smile_short'],$text);
			$text = str_replace("smiles//","smiles/", $text);
		}
    }
    function resize_image($img)
    {
        global $PowerBB;
        $img = str_replace('\\"', '"', $img);
        $img = trim($img);
        /*
        $img = preg_replace('#&gt;(.*?)&lt;#i', "&gt;&lt;", $img);
        $img = preg_replace('#<(.*?)>(.*?)#i', "", $img);
        $img = preg_replace('#<(.*?)>#i', "", $img);
        $img = preg_replace('#&lt;(.*?)&gt;(.*?)#i', "", $img);
        $img = preg_replace("#&lt;(.*?)&gt;#i", "", $img);
        $img = preg_replace('#>(.*?)<#i', "", $img);
        $img = preg_replace("#&lt;(.*?)&gt;#i", "", $img);
        $img = str_replace("<>", '',$img);
        $img = str_replace("&gt;&lt;", '',$img);
        $img = str_replace("&lt;&gt;", '',$img);
        */
        $img = str_replace("&lt;br&gt;","<br>",$img);
       // $img = str_replace("&lt;br /&gt;","<br>",$img);
        $img = str_ireplace("&amp;quot;", "", $img);
		$img = str_ireplace("alt=", '', $img);
		$img = str_ireplace("border=0", '', $img);
   		//$img = str_ireplace('<br />', '', $img);
        $img = str_replace("<br>", '',$img);
   		$img = str_replace('\n', '', $img);
   		$img = str_replace('\s', '', $img);
   		$img = str_replace('\r', '', $img);
        $img = str_ireplace("&nbsp;", '',$img);
        //attach url img replace id
        $Adress = $PowerBB->functions->GetForumAdress();
        if(strstr($img,$PowerBB->_SERVER['HTTP_HOST']))
        {
        preg_match("/[^\/index.php?page=download&attach=1&id=]+$/", $img, $matches);
		 if (is_numeric($matches[0])) {
		  $img = $this->attach_url_img_replace($matches[0],$Adress);
		 }
        }
		$fileParts = pathinfo($img);
		if(isset($fileParts['filename']))
		{
		 $fileParts['filename'] = substr($fileParts['basename'], 0, strrpos($fileParts['basename'], '.'));
         $imagename= trim($fileParts['filename']);
		}
		else
		{
         $imagename= $img;
		}
		 $onload_resize_name ='ResizeIt(this,'.$PowerBB->_CONF['info_row']['default_imagesW'].','.$PowerBB->_CONF['info_row']['default_imagesH'].')';
         eval($PowerBB->functions->get_fetch_hooks('hook_resize_images'));
        if ($PowerBB->_CONF['info_row']['resize_imagesAllow']
         and!strstr($img,"smiles")
         and!isset($PowerBB->_GET['sign'])
         and!isset($PowerBB->_GET['send'])
         and!isset($PowerBB->_GET['start']))
        {
		 $img ='<img src="'.$img.'" border="0" alt="'.$imagename.'" onload="'.$onload_resize_name.'" />';
        }
	    else
	    {
         $img = '<img src="'.$img.'" border="0" alt="'.$imagename.'" />';
		}
		return $img;
     }

    function resize_image_callback($width,$height, $img)
    {
        global $PowerBB;
        $img = str_replace('\\"', '"', $img);
        $img = trim($img);
        $height = intval($height);
        $width = intval($width);
        $img = str_replace("&lt;br&gt;","<br>",$img);
       // $img = str_replace("&lt;br /&gt;","<br>",$img);
        $img = str_ireplace("&amp;quot;", "", $img);
		$img = str_ireplace("alt=", '', $img);
		$img = str_ireplace("border=0", '', $img);
   		//$img = str_ireplace('<br />', '', $img);
        $img = str_replace("<br>", '',$img);
   		$img = str_replace('\n', '', $img);
   		$img = str_replace('\s', '', $img);
   		$img = str_replace('\r', '', $img);
        $img = str_ireplace("&nbsp;", '',$img);
        //attach url img replace id
        $Adress = $PowerBB->functions->GetForumAdress();
        if(strstr($img,$PowerBB->_SERVER['HTTP_HOST']))
        {
        preg_match("/[^\/index.php?page=download&attach=1&id=]+$/", $img, $matches);
		 if (is_numeric($matches[0])) {
		  $img = $this->attach_url_img_replace($matches[0],$Adress);
		 }
        }
		$fileParts = pathinfo($img);
		if(isset($fileParts['filename']))
		{
		 $fileParts['filename'] = substr($fileParts['basename'], 0, strrpos($fileParts['basename'], '.'));
         $imagename= trim($fileParts['filename']);
		}
		else
		{
         $imagename= $img;
		}
		 $onload_resize_name ='ResizeIt(this,'.$PowerBB->_CONF['info_row']['default_imagesW'].','.$PowerBB->_CONF['info_row']['default_imagesH'].')';
         eval($PowerBB->functions->get_fetch_hooks('hook_resize_images'));
        if ($PowerBB->_CONF['info_row']['resize_imagesAllow']
         and!strstr($img,"smiles")
         and!isset($PowerBB->_GET['sign'])
         and!isset($PowerBB->_GET['send'])
         and!isset($PowerBB->_GET['start']))
        {
		 $img ='<img src="'.$img.'" border="0" alt="'.$imagename.'" width="'.$width.'" height="'.$height.'" onload="'.$onload_resize_name.'" />';
        }
	    else
	    {
         $img = '<img src="'.$img.'" border="0" width="'.$width.'" height="'.$height.'" alt="'.$imagename.'" />';
		}
		return $img;
     }

 	function attach_url_img_replace($string, $url)
 	{
 		global $PowerBB;
	   $GetAttachArr 					= 	array();
	   $GetAttachArr['where'] 			= 	array('id',$string);
	   $Attachinfo = $PowerBB->core->GetInfo($GetAttachArr,'attach');
	    return $url.$Attachinfo['filepath'];
	}
	function content_search_highlight( $text, $highlight )
	{
        global $PowerBB;
		$highlight  = urldecode( $highlight );
		$loosematch = strstr( $highlight, '*' ) ? 1 : 0;
		$keywords   = str_replace( '*', '', str_replace( "+", " ", str_replace( "++", "+", str_replace( '-', '', trim($highlight) ) ) ) );
		$word_array = array();
		$endmatch   = "(.)?";
		$beginmatch = "(.)?";
		if ( $keywords )
		{
			if ( preg_match("/,(and|or),/i", $keywords) )
			{
				while ( preg_match("/,(and|or),/i", $keywords, $match) )
				{
					$word_array = explode( ",".$match[1].",", $keywords );
					$keywords   = str_replace( $match[0], '' ,$keywords );
				}
			}
			else if ( strstr( $keywords, ' ' ) )
			{
				$word_array = explode( ' ', str_replace( '  ', ' ', $keywords ) );
			}
			else
			{
				$word_array[] = $keywords;
			}
			if ( ! $loosematch )
			{
				$beginmatch = "(^|\s|\>|;)";
				$endmatch   = "(\s|,|\.|!|<br|&|$)";
			}
			if ( is_array($word_array) )
			{
				foreach ( $word_array as $keywords )
				{
					preg_match_all( "/{$beginmatch}(".preg_quote($keywords, '/')."){$endmatch}/is", $text, $matches );
					for ( $i = 0; $i < count($matches[0]); $i++ )
					{
						$text = str_replace( $matches[0][$i], $matches[1][$i]."[color=#ff0000]".$matches[2][$i]."[/color]".$matches[3][$i], $text );
					}
				}
			}
		}
		return $text;
	}
	function bb_common(&$string)
	{
        global $PowerBB;
        $string = str_replace('\\"', '"', $string);
		$string = str_replace("\r\n", " ", $string);
		$string = str_replace("\r", " ", $string);
		$string = str_replace("\n", " ", $string);
		$string = str_replace('<br>', " ", $string);
		$string = str_replace('<br />', " ", $string);
		 $string = str_replace("&amp;","&",$string);
         $string = str_replace('{39}',"'",$string);
         $string = str_replace('&#092;&#092;',"&#092;",$string);
         $string = str_replace("&amp;#39;","'",$string);
         $string = str_replace("&#39;","'",$string);
         $string = str_replace('<p align="left">','<p dir="ltr" align="left">',$string);
         $string = str_replace("&amp;#092;","\(:*:)",$string);
         $string = str_replace("(:*:)","",$string);
         $string = str_replace("&gt;&lt;","><",$string);
         $string = str_replace("&lt;", "<", $string);
         $string = str_replace("&gt;", ">", $string);
         $string = str_replace("<img", '<img  width="20" height="20"', $string);
         $string = str_replace("<td", '', $string);
         $string = str_replace("<table", '', $string);
         $string = str_replace("<tr", '', $string);
         $string = str_replace("<div", '', $string);
         $string = str_replace("<p", '', $string);
         $string = str_replace("</div>", '', $string);
         $string = str_replace("</td>", '', $string);
         $string = str_replace("</table>", '', $string);
         $string = str_replace("</tr>", '', $string);
		return $string;
     }
	function html_common($string)
	{
        global $PowerBB;
		if (stristr($string, "<body"))
		{
      if (preg_match('#<body.*?>(.*)</body>#is', $string, $matches))
      {
        $string = $matches[1];
      }
      elseif (preg_match('#<body.*?>(.*)#is', $string, $matches))
      {
        $string = $matches[1];
      }
		}
		$string = preg_replace('#(</?)(\w+)([^>]*>)#e','"$1".strtolower("$2")."$3"',$string);
		preg_match_all('#<pre(| .*?)>(.*?)</pre>#s', $string, $matches);
		$preformated_strings = $matches[2];
		$cp = count($preformated_strings);
		for ($i = 0; $i < $cp; $i++)
		{
			$string = preg_replace('#<pre(| .*?)>(.*?)</pre>#s', "***pre_string***$i", $string, 1);
		}
        $string = str_replace('\\"', '"', $string);
		$string = str_replace('&amp;quot;', '"', $string);
		$string = str_replace("\r\n", ' ', $string);
		$string = str_replace("\r", ' ', $string);
		$string = str_replace("\n", ' ', $string);
		$string = str_replace('<br>', "\r\n", $string);
		$string = str_replace('<br />', "\r\n", $string);
		$string = str_replace('&nbsp;', ' ', $string);
		$string = str_replace('/ {2,}/', ' ', $string);
		for ($i = 0; $i < $cp; $i++)
		{
			$string = str_replace("***pre_string***$i", '<pre>' . $preformated_strings[$i] . '</pre>', $string);
		}
		return $string;
	}
	// Converts an HTML email into bbcode
	// This function is loosely based on cbparser.php by corz.org
	function html2bb($string)
	{
        global $PowerBB;
	  $string = str_replace("&quot;", '"', $string);
	  $string = str_replace("&lt;","<", $string);
	  $string = str_replace("&gt;",">", $string);

		  $tags = array(
            '#<strong>(.*?)</strong>#si' => '[b]\\1[/b]',
            '#<b>(.*?)</b>#si' => '[b]\\1[/b]',
            '#<em>(.*?)</em>#si' => '[i]\\1[/i]',
            '#<i>(.*?)</i>#si' => '[i]\\1[/i]',
            '#<u>(.*?)</u>#si' => '[u]\\1[/u]',
            '#<s>(.*?)</s>#si' => '[s]\\1[/s]',
			'#<h2>(.*?)</h2>#si' => '[h1]\\1[/h1]',
            '#<h3>(.*?)</h3>#si' => '[h2]\\1[/h2]',
            '#<h4>(.*?)</h4>#si' => '[h3]\\1[/h3]',
            '#<ul>(.*?)</ul>#si' => '[ul]\\1[/ul]',
            '#<ol>(.*?)</ol>#si' => '[ol]\\1[/ol]',
            '#<li>(.*?)</li>#si' => '[li]\\1[/li]',
            '#&nbsp;#si' => ' ',
            '#<center>(.*?)</center>#si' => '[center]\\1[/center]',
            '#<div style="text-align: center;">(.*?)</div>#si' => '[center]\\1[/center]',
            '#<br(.*?)>#si' => chr(13).chr(10),
			'#<p>(.*?)</p>#si' => chr(13).chr(10).chr(13).chr(10).'\\1',

            '#<font.*? color="(.*?)".*?>(.*?)</font>#si' => '[color=\\1]\\2[/color]',
            '#<img.*? src="(.*?)".*?>#si' => '[img]\\1[/img]',

            '#<a.*? href="(.*?)".*?>(.*?)</a>#si' => '[url=\\1]\\2[/url]',

            //'#<code>(.*?)</code>#si' => '[code]\\1[/code]',
            //'#<iframe style="(.*?)" id="ytplayer" type="text/html" width="534" height="401" src="(.*?)/embed/(.*?)" frameborder="0"/></iframe>#si' => '[youtube]\\3[/youtube]',

            '#<span.*? style="(.*?)".*?>(.*?)</span>#si' => '\\2',
			//'#<a href="mailto:"(.*?)" title="Email (.*?)">(.*?)</a>#si' => '[email]\\1[/email]',
			 //'#<img src="(.*?) >#si' => '[img]\\1[/img]',
		);

		foreach ($tags as $search => $replace)
		$string = preg_replace($search, $replace, $string);
       // Mor Convert  HTML to BBCode ++
	  $string = preg_replace('#<a(.*?)href="(.*?)" (.*?)>(.*?)</a>#i', " [url=$2]$4[/url] ", $string);
	  $string = preg_replace('#<a(.*?)href="(.*?)">(.*?)</a>#i', " [url=$2]$3[/url] ", $string);
	  $string = preg_replace('#<img src="(.*?) (.*?) /">#i', " [img]$1[/img] ", $string);
	  $string = preg_replace('#<img(.*?)src="(.*?)">#i', " [img]$2[/img] ", $string);
	  $string = preg_replace('#<img(.*?)src="(.*?)" />#i', " [img]$2[/img] ", $string);
	  $string = preg_replace('#<p>(.*?)</p>#i', " $1 <br />\r\n", $string);
	  $string = preg_replace('#<font color="(.*?)">(.*?)</font>#i', "[color=$1]$2[/$1] ", $string);
	  $string = preg_replace('#<font size="(.*?)">(.*?)</font>#i', "[size=$1]$2[/$1] ", $string);
	  $string = preg_replace('#<font face="(.*?)">(.*?)</font>#i', "[size=$1]$2[/$1] ", $string);
	  $string = preg_replace('#<p align="(.*?)">(.*?)</p>#i', "[$1] $2 [/$1]", $string);
	  $string = preg_replace('#<div align="(.*?)">(.*?)</div>#i', "[$1] $2 [/$1]", $string);
	  $string = preg_replace('#<div>(.*?)</div>#i', "$1 \r\n", $string);


	  $string = preg_replace('#<span>(.*?)</span>#i', "$1", $string);
	  $string = preg_replace('#<code>(.*?)</code>#i', "[code]$1[/code]", $string);
        $string = str_replace('\\"', '"', $string);
		//$string = str_replace('</b>',  '',    $string);
		$string = str_replace('</i>',  '[/i]',    $string);
		$string = str_replace('</u>',  '[/u]',    $string);
		$string = str_replace('</ul>', '[/list]', $string);
		$string = str_replace('</ol>', '[/list]', $string);
		$string = str_replace('</em>', '[/i]',    $string);
		$string = str_replace('</code>', '[/code]', $string);
		$string = str_replace('<code>', '[code]', $string);
		// Do simple reg expr replacements
		$string = preg_replace('#<b(| .*?)>#',      '',      $string);
		$string = preg_replace('#<i(| .*?)>#',      '[i]',      $string);
		$string = preg_replace('#<u(| .*?)>#',      '[u]',      $string);
		$string = preg_replace('#<ul(| .*?)>#',     '[list]',   $string);
		$string = preg_replace('#<ol(| .*?)>#',     '[list=1]', $string);
		$string = preg_replace('#<li(| .*?)>#',     '[*]',      $string);
		$string = preg_replace('#<em(| .*?)>#',     '[i]',      $string);
		$string = preg_replace('#<strong(| .*?)>#', '[b]',      $string);
		$string = str_replace('</strong>', '[/b]', $string);

		$string = preg_replace('#<blockquote(| .*?)>#i', '[quote]$1',  $string);
		$string = str_replace('</blockquote>', '[/quote]', $string);

		$string = preg_replace('#<pre(| .*?)>#', '[code]',  $string);
		$string = str_replace('</pre>', '[/code]', $string);

		// replace multiple instances of [b] or [i] with single tags
		$string = preg_replace('#(\[b\])+#',      '[b]',      $string);
		$string = preg_replace('#(\[i\])+#',      '[i]',      $string);
		$string = preg_replace('#(\[/b\])+#',     '[/b]',      $string);
		$string = preg_replace('#(\[/i\])+#',     '[/i]',      $string);
		// fix for thunderbird which chops up quotes into little chunks for some reason. Remove if necessary!
		$string = preg_replace('#\[\/quote\]\s*?\[quote\]#', '',  $string);
		// Replace email address
		$string = preg_replace('#<a .*?href=.*?"mailto:(.*?)".*?>(.*?)</a>#i', "$2 ([email]$1[/email])", $string);
		// Replace links
		$string = preg_replace('#<a .*href=.*"(.*)".*>(.*)</a>#iU', "'[url'. $1 ? '='$1 : '' .']$2[/url]'", $string);
		// Remove any image tags whose source starts with 'cid:' - this is an inline attachment, and will be added to the post as a normal attachment.
		$string = preg_replace('#<img[^>]+src="cid:[^>]+>#i', '', $string);
		// Replace image references
		$string = preg_replace('#<img .*src="(.*)".*>#iU', "'[img]$1[/img]'", $string);
		// Remove all remaining HTML tags
		$string= preg_replace('/<([^<>]*)>/', '',$string);
		$string = preg_replace('#<(/?\w+|!--)[^>]*>#', '', $string);

		// Convert HTML entities
		$string = html_entity_decode($string, ENT_QUOTES, 'UTF-8');
		$string = urldecode($string);
		// Convert quotes
	  return $PowerBB->functions->pbb_stripslashes($string);
	}

 	function mqtids_replace_cod($string)
 	{
 		global $PowerBB;
           $string = str_replace('\\"', '"', $string);
           if ($PowerBB->_POST['preview'] == false)
            {
              $string = $PowerBB->functions->pbb_stripslashes($string);
            }
	    return ($string);
	}
	function convert_int_to_utf8($intval)
	{
		$intval = intval($intval);
		switch ($intval)
		{
			// 1 byte, 7 bits
			case 0:
				return chr(0);
			case ($intval & 0x7F):
				return chr($intval);
			// 2 bytes, 11 bits
			case ($intval & 0x7FF):
				return chr(0xC0 | (($intval >> 6) & 0x1F)) .
					chr(0x80 | ($intval & 0x3F));
			// 3 bytes, 16 bits
			case ($intval & 0xFFFF):
				return chr(0xE0 | (($intval >> 12) & 0x0F)) .
					chr(0x80 | (($intval >> 6) & 0x3F)) .
					chr (0x80 | ($intval & 0x3F));
			// 4 bytes, 21 bits
			case ($intval & 0x1FFFFF):
				return chr(0xF0 | ($intval >> 18)) .
					chr(0x80 | (($intval >> 12) & 0x3F)) .
					chr(0x80 | (($intval >> 6) & 0x3F)) .
					chr(0x80 | ($intval & 0x3F));
		}
	}
   //end_function
 	function replace_htmlentities($string)
 	{
 		global $PowerBB;
          if ($PowerBB->_POST['preview'] == false)
          {
           $string = $PowerBB->functions->pbb_stripslashes($string);
          }
          $brackets = (strpos($string,'[') !== false) and (strpos($string,']') !== false);
         if ($brackets)
 		{

            $regexcode = array();
			$regexcode['[code]'] = '#\[code\](.*)\[/code\]#siU';
			$regexcode['[php]'] = '#\[php\](.*)\[/php\]#siU';
			$string = preg_replace_callback($regexcode, function($matches) {
			return $this->ent_quotesutf($matches[1],'code');
			}, $string);


		 }
        $string = str_replace('\\"', '"', $string);
        $string = str_replace("<br />", '',$string);
			    $li_not = '#\<li\>(.*)\</li\>#siU';
				$string = preg_replace_callback($li_not, function($lidd) {
				    return $this->li_not_bar($lidd[1]);
				}, $string);
        return $string;
 	}


	/**
	* Prepares list MyCode by finding the matching list tags.
	*
	* @param array $matches Matches
	* @return string Temporary replacements.
	*/
	function mycode_prepare_list($matches)
	{
		    global $PowerBB;

		// Append number to identify matching list tags
		if(strcasecmp($matches[1], '[/list]') == 0)
		{
			$count = array_pop($this->list_elements);
			if($count !== NULL)
			{
				return "[/list&{$count}]";
			}
			else
			{
				// No open list tag...
				return $matches[0];
			}
		}
		else
		{
			++$this->list_count;
			$this->list_elements[] = $this->list_count;
			if(!empty($matches[2]))
			{
				return "[list{$matches[2]}&{$this->list_count}]";
			}
			else
			{
				return "[list&{$this->list_count}]";
			}
		}
	}

 	/**
	* Parses list MyCode.
	*
	* @param string $message The message to be parsed
	* @param string $type The list type
	* @return string The parsed message.
	*/
	function mycode_parse_list($message, $type)
	{
	    global $PowerBB;
		// No list elements? That's invalid HTML
		if(strpos($message, '[*]') === false)
		{
			$message = "[*]{$message}";
		}

		$message = preg_split("#[^\S\n\r]*\[\*\]\s*#", $message);
		if(isset($message[0]) && trim($message[0]) == '')
		{
			array_shift($message);
		}
		$message = '<li>'.implode("</li><li>", $message)."</li>";

		if($type)
		{
			$list = "<ol type=\"$type\" class=\"mycode_list\">$message</ol>";
		}
		else
		{
			$list = "<ul class=\"mycode_list\">$message</ul>";
		}
		$list = preg_replace("#<(ol type=\"$type\"|ul)>\s*</li>#", "<$1>", $list);
		return $list;
	}

	/**
	* Parses list MyCode.
	*
	* @param array $matches Matches
	* @return string The parsed message.
	*/
	function mycode_parse_list_callback($matches)
	{
	  global $PowerBB;
		return $this->mycode_parse_list($matches[3], $matches[2]);
	}

	function ent_quotesutf($string, $message)
 	{
 		global $PowerBB;
 		$string = str_replace('\\"', '"', $string);
		$strings = htmlentities($string, ENT_QUOTES, "UTF-8");
		return "[".$message."]".$strings."[/".$message."]";
 	}

	/**
	* Parses font fonts.
	*
	* @param array $matches Matches.
	* @return string The HTML <span> tag with styled font.
	*/
	function mycode_parse_font_callback_issue($matches, $matchest)
	{

		$matches = str_replace("&quot;", "'", $matches);
		$matches = str_replace('"', "'", $matches);

		return "<span style=\"font-family: {$matches};\" class=\"mycode_font\">{$matchest}</span>";
	}

	function mycode_parse_font_callback($matches, $matchest, $matchestr)
	{
		$matches = str_replace("&quot;", "'", $matches);
		$matches = str_replace('"', "'", $matches);
		return "<span style=\"font-family: {$matches},{$matchest};\" class=\"mycode_font\">{$matchestr}</span>";
	}
	/**
	* Handles fontsize.
	*
	* @param int $size The original size.
	* @param string $text The text within a size tag.
	* @return string The parsed text.
	*/
	function mycode_handle_size($size, $text)
	{
		$size = intval($size);

		if($size == 1)
		{
		 $size = "10px";
		}
		elseif($size == 2)
		{
		 $size = "12px";
		}
		elseif($size == 3)
		{
		 $size = "14px";
		}
		elseif($size == 4)
		{
		 $size = "16px";
		}
		elseif($size == 5)
		{
		 $size = "20px";
		}
		elseif($size == 6)
		{
		 $size = "26px";
		}
		elseif($size == 7)
		{
		 $size = "37px";
		}
		elseif($size == 8)
		{
		 $size = "48px";
		}
		elseif($size > 8)
		{
			$size = "50px";
		}

		$text = str_replace("\'", "'", $text);
		$text = str_replace("&quot;", '"', $text);
		$mycode_size = "<span style=\"font-size: $size;\" class=\"mycode_size\">$text</span>";

		return $mycode_size;
	}
    // Delete all tags from text and along sort.
	function deletedalltags($text, $num)
	{
		$text = preg_replace('#\[(.+)](.+)\[(.+)]#iUs', '$2', $text);
		$text = preg_replace('#\[(.+)]#iUs', '', $text);
		$text = preg_replace('#\[]#iUs', '', $text);
	    $text = preg_replace("#\[color=([a-zA-Z]*|\#?[\da-fA-F]{3}|\#?[\da-fA-F]{6})](.*?)\[/color\]#si", "$2", $text);
	    $text = preg_replace("#\[size=([0-9\+\-]+?)\](.*?)\[/size\]#si", "$2", $text);
		$text = preg_replace(",([^]_a-z0-9-=\"'\/])((https?|ftp|gopher|news|telnet):\/\/|www\.)([^ \r\n\(\)\*\^\$!`\"'\|\[\]\{\}<>]*),i", "",$text);
		$text = preg_replace(",^((https?|ftp|gopher|news|telnet):\/\/|\.)([^ \r\n\(\)\*\^\$!`\"'\|\[\]\{\}<>]*),i", "",$text);
		$text = strip_tags($text);
		$text = str_replace('<br>',"",$text);
		$text = str_replace('<br />',"",$text);
		$text = $this->censor_words($text);
		$text = $this->ShortPhrase($text,$num);

		return $text;
	}
	// Find images and change width and height.
	function find_images_sizes($text, $images)
	{
        $text= preg_replace('#\[img\](.+)\[\/img\]#iUs', '<img src="$1">', $text);

		if(preg_match_all('#<img src="(.*?)">#s', $text, $images))
		{
         return $images[1];
		}
	    else
	    {
		 $images = 0;
		 return $images;
		}

	}

	function remove_message_quotes(&$text, $rmdepth=null)
	{
		if(!$text)
		{
			return $text;
		}
		if(!isset($rmdepth))
		{
			global $PowerBB;
		}

		// find all tokens
		// note, at various places, we use the prefix "s" to denote "start" (ie [quote]) and "e" to denote "end" (ie [/quote])
		preg_match_all("#\[quote(=(?:&quot;|\"|')?.*?(?:&quot;|\"|')?)?\]#si", $text, $smatches, PREG_OFFSET_CAPTURE | PREG_PATTERN_ORDER);
		preg_match_all("#\[/quote\]#i", $text, $ematches, PREG_OFFSET_CAPTURE | PREG_PATTERN_ORDER);

		if(empty($smatches) || empty($ematches))
		{
			return $text;
		}

		// make things easier by only keeping offsets
		$soffsets = $eoffsets = array();
		foreach($smatches[0] as $id => $match)
		{
			$soffsets[] = $match[1];
		}
		$first_token = 0;
		if(isset($soffsets[0])) {
			$first_token = $soffsets[0];
		}
		// whilst we loop, also remove unnecessary end tokens at the start of string
		foreach($ematches[0] as $id => $match)
		{
			if($match[1] > $first_token)
			{
				$eoffsets[] = $match[1];
			}
		}
		unset($smatches, $ematches);


		// elmininate malformed quotes by parsing like the parser does (preg_replace in a while loop)
		// NOTE: this is slightly inaccurate because the parser considers [quote] and [quote=...] to be different things
		$good_offsets = array();
		while(!empty($soffsets) && !empty($eoffsets)) // don't rely on this condition - an end offset before the start offset will cause this to loop indefinitely
		{
			$last_offset = 0;
			foreach($soffsets as $sk => &$soffset)
			{
				if($soffset >= $last_offset)
				{
					// search for corresponding eoffset
					foreach($eoffsets as $ek => &$eoffset) // use foreach instead of for to get around indexing issues with unset
					{
						if($eoffset > $soffset)
						{
							// we've found a pair
							$good_offsets[$soffset] = 1;
							$good_offsets[$eoffset] = -1;
							$last_offset = $eoffset;

							unset($soffsets[$sk], $eoffsets[$ek]);
							break;
						}
					}
				}
			}

			// remove any end offsets occurring before start offsets
			$first_start = reset($soffsets);
			foreach($eoffsets as $ek => &$eoffset)
			{
				if($eoffset < $first_start)
				{
					unset($eoffsets[$ek]);
				}
				else
				{
					break;
				}
			}
			// we don't need to remove start offsets after the last end offset, because the loop will deplete something before that
		}

		if(empty($good_offsets))
		{
			return $text;
		}
		ksort($good_offsets);


		// we now have a list of all the ordered tokens, ready to go through
		$depth = 0;
		$remove_regions = array();
		$tmp_start = 0;
		foreach($good_offsets as $offset => $dincr)
		{
			if($depth == $rmdepth && $dincr == 1)
			{
				$tmp_start = $offset;
			}
			$depth += $dincr;
			if($depth == $rmdepth && $dincr == -1)
			{
				$remove_regions[] = array($tmp_start, $offset);
			}
		}

		if(empty($remove_regions))
		{
			return $text;
		}

		// finally, remove the quotes from the string
		$newtext = '';
		$cpy_start = 0;
		foreach($remove_regions as &$region)
		{
			$newtext .= substr($text, $cpy_start, $region[0]-$cpy_start);
			$cpy_start = $region[1]+8; // 8 = strlen('[/quote]')
			// clean up newlines
			$next_char = $text[$region[1]+8];
			if($next_char == "\r" || $next_char == "\n")
			{
				++$cpy_start;
				if($next_char == "\r" && $text[$region[1]+9] == "\n")
				{
					++$cpy_start;
				}
			}
		}
		// append remaining end text
		if(strlen($text) != $cpy_start)
		{
			$newtext .= substr($text, $cpy_start);
		}

		// we're done
		return $newtext;
	}

	function CycleText($string)
 	{
        $string = strip_tags($string);
		$string = str_replace("\r","{s}", $string);
		$string = str_replace("\n","{s}", $string);
		$originally_text = $string;

		$string = 	explode(' ',$string);
			  // Cycle through the words
			foreach($string as $key)
			{

				if (function_exists('mb_strlen'))
				{
				$tag_less_num = mb_strlen($key, 'UTF-8') >= 4;
				}
				else
				{
				$tag_less_num = strlen(utf8_decode($key)) >= 4;
				}
			      // If the word has more than 4 letters
			    if(!$tag_less_num)
			    {

			     $originally_text = str_replace($key,"", $originally_text);

			    }
			}
			  // Recreate string from array
			  // See what we got
			$originally_text = str_replace(" ",",", $originally_text);
			$originally_text = str_replace("..","", $originally_text);
			$originally_text = str_replace("{s}",",", $originally_text);
			$originally_text = str_replace(",,",",", $originally_text);
			$originally_text = str_replace(",,",",", $originally_text);
			$originally_text = str_replace($originally_text,"'".$originally_text."'", $originally_text);
			$originally_text = str_replace("',","", $originally_text);
			$originally_text = str_replace(",'","", $originally_text);
			$originally_text = str_replace("'","", $originally_text);
			$originally_text = str_replace(",".$originally_text,$originally_text, $originally_text);
			$originally_text = str_replace($originally_text.",",$originally_text, $originally_text);

	        $_search = '#\[(.*)\]#siU';
	        $_replace = "";
	        $originally_text = preg_replace($_search,$_replace,$originally_text);
			$originally_text = str_replace(","," ", $originally_text);

		return ($originally_text);
    }


	/**
	* Parses video MyCode.
	*
	* @param string $video The video provider.
	* @param string $url The video to link to.
	* @return string The built-up video code.
	*/
	function mycode_parse_video($video, $url)
	{
		global $PowerBB;

		if(empty($video) || empty($url))
		{
			return "[video={$video}]{$url}[/video]";
		}

		// Check URL is a valid URL first, as `parse_url` doesn't check validity.
		if(false === filter_var($url, FILTER_VALIDATE_URL))
		{
			return "[video={$video}]{$url}[/video]";
		}

		$parsed_url = @parse_url(urldecode($url));
		if($parsed_url === false)
		{
			return "[video={$video}]{$url}[/video]";
		}


		$fragments = array();
		if($parsed_url['fragment'])
		{
			$fragments = explode("&", $parsed_url['fragment']);
		}

		if($video == "liveleak")
		{
			// The query part can start with any alphabet, but set only 'i' to catch in index key later
			$parsed_url['query'] = "i".substr($parsed_url['query'], 1);
		}

		$queries = explode("&", $parsed_url['query']);

		$input = array();
		foreach($queries as $query)
		{
			list($key, $value) = explode("=", $query);
			$key = str_replace("amp;", "", $key);
			$input[$key] = $value;
		}

		$path = explode('/', $parsed_url['path']);

		switch($video)
		{
			case "dailymotion":
				if(isset($path[2]))
				{
					list($id) = explode('_', $path[2], 2); // http://www.dailymotion.com/video/fds123_title-goes-here
				}
				else
				{
					$id = $path[1]; // http://dai.ly/fds123
				}
				break;
			case "instagram":
				$id = $path[2]; // https://www.instagram.com/tv/fds123/title_goes_here/
				$title = $this->htmlspecialchars_uni($path[3]);
				break;
			case "tiktok":
				$id = $path[3]; //  https://www.tiktok.com/embed/v2/
				break;
			case "facebook":
				if(isset($input['video_id']))
				{
					$id = $input['video_id']; // http://www.facebook.com/video/video.php?video_id=123
				}
				elseif(substr($path[3], 0, 3) == 'vb.')
				{
					$id = $path[4]; // https://www.facebook.com/fds/videos/vb.123/123/
				}
				else
				{
					$id = $path[3]; // https://www.facebook.com/fds/videos/123/
				}
				break;
			case "videoshub":
				$id = $path[1]; // https://videoshub.com/videos/
				break;
			case "veoh":
				$id = $path[2]; // https://www.veoh.com/watch/v
				break;
			case "liveleak":
				$id = $input['i']; // http://www.liveleak.com/view?i=123
				break;
			case "yahoo":
				if(isset($path[2]))
				{
					$id = $path[2]; // http://xy.screen.yahoo.com/fds/fds-123.html
				}
				else
				{
					$id = $path[1]; // http://xy.screen.yahoo.com/fds-123.html
				}
				// Support for localized portals
				$domain = explode('.', $PowerBB->_SERVER['HTTP_HOST']);
				if($domain[0] != 'screen' && preg_match('#^([a-z-]+)$#', $domain[0]))
				{
					$local = "{$domain[0]}.";
				}
				else
				{
					$local = '';
				}
				break;
			case "vimeo":
				if(isset($path[3]))
				{
					$id = $path[3]; // http://vimeo.com/fds/fds/fds123
				}
				else
				{
					$id = $path[2]; // http://vimeo.com/fds123
				}
				break;
			case "youtube":
				if($fragments[0])
				{
					$id = str_replace('!v=', '', $fragments[0]); // http://www.youtube.com/watch#!v=fds123
				}
				elseif($input['v'])
				{
					$id = $input['v']; // http://www.youtube.com/watch?v=fds123
				}
				else
				{
					$id = $path[2]; // http://www.youtu.be/fds123
				}
				break;
			case "twitch":
				if(isset($input['video']))
				{
					$id = 'video: "'.$input['video'].'"'; // //player.twitch.tv/?video=v
				}
				elseif(count($path) >= 3 && $input['video'])
				{
					// Direct video embed with URL like: https://www.twitch.tv/videos/179723472
					$id = 'video: "'.$input['video'].'"';
				}
				elseif(count($path) >= 4 && $path[2] == 'v')
				{
					// Direct video embed with URL like: https://www.twitch.tv/waypoint/v/179723472
					$id = 'video: "'.$input['video'].'"';
				}
				elseif(count($path) >= 2)
				{
					// Channel (livestream) embed with URL like: https://twitch.tv/waypoint
					$id = 'channel: "'.$input['channel'].'"';
				}
				break;
			default:
				return "[video={$video}]{$url}[/video]";
		}

		if(empty($id))
		{
			return "[video={$video}]{$url}[/video]";
		}
        $PageUrl = $PowerBB->functions->GetPageUrl();
		$id = $this->encode_url($id);
       if ($video == 'youtube')
       {
       $video_code = '<iframe width="560" height="315" src="https://www.youtube.com/embed/'.$id.'" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
       }
       elseif ($video == 'instagram')
       {
       $video_code = '<iframe src="http://instagram.com/p/'.$id.'/embed" width="440" height="510" allowfullscreen="true" frameborder=0></iframe>';
       }
       elseif ($video == 'facebook')
       {
       $video_code = '<script src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.2"></script><div class="fb-video" data-href="https://www.facebook.com/facebook/videos/'.$id.'/" data-width="430" data-show-text="false"><div class="fb-xfbml-parse-ignore"><blockquote cite="https://www.facebook.com/facebook/videos/'.$id.'/"></blockquote></div></div>';
       }
       elseif ($video == 'tiktok')
       {
       $video_code = '<iframe src="https://www.tiktok.com/embed/v2/'.$id.'" width="500" height="580" frameborder="0" scrolling="no" frameborder="0"></iframe>';
       }
        elseif ($video == 'vimeo')
       {
       $video_code = '<iframe src="//player.vimeo.com/video/'.$id.'" width="500" height="281" allowfullscreen="true" frameborder=0></iframe>';
       }
       elseif ($video == 'twitch')
       {
       $video_code = '<div id="twitch-embed"></div><script src="https://player.twitch.tv/js/embed/v1.js"></script><script type="text/javascript">var options = {width: "420", height: "300"}; new Twitch.Player("twitch-embed", {'.$id.' ,options} );</script>';
       $video_code = str_replace("%20", "", $video_code);
       $video_code = str_replace("%22", '"', $video_code);
       }
       elseif ($video == 'dailymotion')
       {
       $video_code = '<iframe src="//www.dailymotion.com/embed/video/'.$id.'" width="480" height="270" allowfullscreen="true" frameborder=0></iframe>';
       }
		return $video_code;
	}

	/**
	* Parses video MyCode.
	*
	* @param array $matches Matches.
	* @return string The built-up video code.
	*/
	function mycode_parse_video_callback($matches)
	{
		return $this->mycode_parse_video($matches[1], $matches[2]);
	}


	/**
	* Parses video MyCode disabled.
	*
	* @param array $matches Matches.
	* @return string The built-up video code.
	*/
	function mycode_parse_video_disabled_callback($matches)
	{
		return $this->mycode_parse_video_disabled($matches[2]);
	}

/**
 * Custom function for htmlspecialchars which takes in to account unicode
 *
 * @param string $message The string to format
 * @return string The string with htmlspecialchars applied
 */
function htmlspecialchars_uni($message)
{
	$message = preg_replace("#&(?!\#[0-9]+;)#si", "&amp;", $message); // Fix & but allow unicode
	$message = str_replace("<", "&lt;", $message);
	$message = str_replace(">", "&gt;", $message);
	$message = str_replace("\"", "&quot;", $message);
	return $message;
}

	/**
	 * Replaces certain characters with their entities in a URL.
	 *
	 * @param string $url The URL to be escaped.
	 * @return string The escaped URL.
	 */
	function encode_url($url)
	{
		$entities = array('$' => '%24', '&#36;' => '%24', '^' => '%5E', '`' => '%60', '[' => '%5B', ']' => '%5D', '{' => '%7B', '}' => '%7D', '"' => '%22', '<' => '%3C', '>' => '%3E', ' ' => '%20');

		$url = str_replace(array_keys($entities), array_values($entities), $url);

		return $url;
	}

	/**
	 * Attempts to move any javascript references in the specified message.
	 *
	 * @param string The message to be parsed.
	 * @return string The parsed message.
	 */
	function fix_javascript($string)
	{
		$js_array = array(
			"#(&\#(0*)106;?|&\#(0*)74;?|&\#x(0*)4a;?|&\#x(0*)6a;?|j)((&\#(0*)97;?|&\#(0*)65;?|a)(&\#(0*)118;?|&\#(0*)86;?|v)(&\#(0*)97;?|&\#(0*)65;?|a)(\s)?(&\#(0*)115;?|&\#(0*)83;?|s)(&\#(0*)99;?|&\#(0*)67;?|c)(&\#(0*)114;?|&\#(0*)82;?|r)(&\#(0*)105;?|&\#(0*)73;?|i)(&\#112;?|&\#(0*)80;?|p)(&\#(0*)116;?|&\#(0*)84;?|t)(&\#(0*)58;?|\:))#i",
			"#([\s\"']on)([a-z]+\s*=)#i",
		);

		// Add invisible white space
		$string = preg_replace($js_array, "$1\xE2\x80\x8C$2$6", $string);

		return $string;
	}

	function remove_strings($string)
	{
		$string = str_replace("&amp;", "&", $string);
		$find_matches1 = array("\n&lt;br&gt;","\n&lt;br /&gt;","\n&lt;hr&gt;");
		$find_matches2 = array("&lt;br&gt;\r","&lt;br /&gt;\r","&lt;hr&gt;\r");

		$replace_find_matches = array("");
		$string =  str_replace($find_matches1,$replace_find_matches, $string);
		$string =  str_replace($find_matches2,$replace_find_matches, $string);

		$string = strip_tags($string);

		return $string;
	}

 }
?>
