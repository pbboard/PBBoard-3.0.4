<?php
error_reporting(E_ERROR | E_PARSE);
@set_time_limit(0);
@ini_set('max_execution_time', 123456);
class FeedParser{

	var $xmlParser      = null;
	var $insideItem     = array();                  // Keep track of current position in tag tree
	var $currentTag     = null;                     // Last entered tag name
	var $currentAttr    = null;                     // Attributes array of last entered tag

	var $namespaces     = array(
							'http://purl.org/rss/1.0/'                  => 'RSS 1.0',
							'http://purl.org/rss/1.0/modules/content/'  => 'RSS 2.0',
							'http://www.w3.org/2005/Atom'               => 'ATOM 1',
							);                          // Namespaces to detact feed version
	var $itemTags       = array('ITEM','ENTRY');    // List of tag names which holds a feed item
	var $channelTags    = array('CHANNEL','FEED');  // List of tag names which holds all channel elements
	var $dateTags       = array('UPDATED','PUBDATE','DC:DATE');
	var $hasSubTags     = array('IMAGE','AUTHOR');  // List of tag names which have sub tags
	var $channels       = array();
	var $items          = array();
	var $itemIndex      = 0;

	var $url            = null;                     // The parsed url
	var $version        = null;                     // Detected feed version


	/**
	* Constructor - Initialize and set event handler functions to xmlParser
	*/
	function __construct()
	{
		$this->xmlParser = xml_parser_create();

		xml_set_object($this->xmlParser, $this);
		xml_set_element_handler($this->xmlParser, "startElement", "endElement");
		xml_set_character_data_handler($this->xmlParser, "characterData");
	}

	/*-----------------------------------------------------------------------+
	|  Public functions. Use to parse feed and get informations.             |
	+-----------------------------------------------------------------------*/

	/**
	* Get all channel elements
	*
	* @access   public
	* @return   array   - All chennels as associative array
	*/
	public function getChannels()
	{
		return $this->channels;
	}

	/**
	* Get all feed items
	*
	* @access   public
	* @return   array   - All feed items as associative array
	*/
	public function getItems()
	{
		return $this->items;
	}

	/**
	* Get total number of feed items
	*
	* @access   public
	* @return   number
	*/
	public function getTotalItems()
	{
		return count($this->items);
	}

	/**
	* Get a feed item by index
	*
	* @access   public
	* param    number  index of feed item
	* @return   array   feed item as associative array of it's elements
	*/
	public function getItem($index)
	{
		if($index < $this->getTotalItems())
		{
			return $this->items[$index];
		}
	}

	/**
	* Get a channel element by name
	*
	* @access   public
	* param    string  the name of channel tag
	* @return   string
	*/
	public function getChannel($tagName)
	{
		if(array_key_exists(strtoupper($tagName), $this->channels))
		{
			return $this->channels[strtoupper($tagName)];
		}
	}

	/**
	* Get the parsed URL
	*
	* @access   public
	* @return   string
	*/
	public function getParsedUrl()
	{
	 return $this->url;
	}

	/**
	* Get the detected Feed version
	*
	* @access   public
	* @return   string
	*/
   public function getFeedVersion()
   {
		return $this->version;
   }
   public function get_item_value($item)
{
	return (is_array($item) ? $item['value'] : $item);
}

	/**
	* Parses a feed url
	*
	* @access   public
	* param    srting  teh feed url
	* @return   void
	*/
	public function parse($url)
	{
		$this->url  = $url;
		$URLContent = $this->getUrlContent();

		if($URLContent)
		{

           if(stristr(strtolower($URLContent),"windows-1256"))
            {
	           $URLContent = str_ireplace("windows-1256", "utf-8", $URLContent);
	           $URLContent=iconv("Windows-1256", "UTF-8",$URLContent);
            }

			//remove imges code
			$search = '#<img([^>]*) src="([^"/]*/?[^".]*\.[^"]*)"([^>]*)>((?!</a>))#';
			$replace = '<img border="0" src="$2" />';
			$URLContent = preg_replace($search, $replace, $URLContent);

			//Replace imges links to img code
           // $URLContent = preg_replace('/(http:\\/\\/.+(png|jpeg|jpg|gif|bmp))/Ui', '<img border="0" src="$1" />', $URLContent);

           if(stristr(strtolower($URLContent),"bbcarabic.com"))
            {
                $URLContent = str_replace("summary", "CONTENT:ENCODED", $URLContent);
                $URLContent = str_replace("not restricted", "http://www.bbc.co.uk/arabic/", $URLContent);
                $URLContent = str_replace("rights", "LINK", $URLContent);
            }


            if(!stristr(strtolower($URLContent),"content:encoded"))
            {
		        $URLContent = str_replace("description", "CONTENT:ENCODED", $URLContent);
		        $URLContent = str_replace("<content", "<CONTENT:ENCODED", $URLContent);
		        $URLContent = str_replace("</content", "</CONTENT:ENCODED", $URLContent);
                $URLContent = str_replace("summary", "CONTENT:ENCODED", $URLContent);
                $URLContent = str_replace("not restricted", "http://www.bbc.co.uk/arabic/", $URLContent);
                $URLContent = str_replace("rights", "LINK", $URLContent);
            }

            $URLContent = str_replace(" type='html'", "", $URLContent);
            $URLContent = str_ireplace('<br />', '', $URLContent);
            preg_match("/<content:encoded>(.*?)<\\/content:encoded>/si", $URLContent, $match);
            $match[1] = str_ireplace("<![CDATA[", "", $match[1]);
            $match[1] = str_replace("]]>", "", $match[1]);
           if (function_exists('mb_strlen'))
			{
				$CONTENT_num_characters = mb_strlen($match[1], 'UTF-8');
			}
			else
			{
				$CONTENT_num_characters = strlen(utf8_decode($match[1]));
			}

			 if($CONTENT_num_characters < 20)
			{
				$URLContent = str_ireplace("description", "CONTENT:ENCODED", $URLContent);
				$URLContent = str_replace("*", " ", $URLContent);
				$URLContent = str_replace("صورة :", "", $URLContent);
				$URLContent = str_ireplace("Image:", "", $URLContent);
			}

			$segments   = str_split($URLContent);
			foreach($segments as $index=>$data)
			{
				$lastPiese = ((count($segments)-1) == $index)? true : false;
				xml_parse($this->xmlParser, $data, $lastPiese);
			}
			xml_parser_free($this->xmlParser);
       }

	}

   // End public functions -------------------------------------------------

   /*-----------------------------------------------------------------------+
   | Private functions. Be careful to edit them.                            |
   +-----------------------------------------------------------------------*/

   /**
	* Load the whole contents of a RSS/ATOM page
	*
	* @access   private
	* @return   string
	*/
	private function getUrlContent()
	{
		if(empty($this->url))
		{
			//throw new Exception("URL to parse is empty!.");
			//return false;
		}

		if($content = @file_get_contents($this->url))
		{
			return $content;
		}
		else
		{

			if($cloudFlarecontent = $this->cloudFlareBypass($this->url))
			{
				return $cloudFlarecontent;
			}
			else
			{
				$ch         = @curl_init();
				curl_setopt($ch, CURLOPT_URL, $this->url);
				curl_setopt($ch, CURLOPT_HEADER, false);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION , true);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
				$content    = curl_exec($ch);
				$error      = curl_error($ch);

				curl_close($ch);
				if($content)
				{
					return $content;
				}
				else
				{
					throw new Exception("Erroe occured while loading url by cURL.\n" . $error) ;
					return false;
				}
			}
		}

	}


   function cloudFlareBypass($url)
   {

	$useragent = "Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/W.X.Y.Z‡ Mobile Safari/537.36 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)";

	$ct = curl_init();

	curl_setopt_array($ct, Array(
		CURLOPT_URL => $url,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_HTTPHEADER => array("X-Requested-With: XMLHttpRequest"),
		CURLOPT_REFERER => $url,
		CURLOPT_USERAGENT =>  $useragent,
		CURLOPT_HEADER => false,
		CURLOPT_POST => true,
		CURLOPT_POSTFIELDS => 'schn=csrf'
	));

	$html = @curl_exec($ct);

	$dochtml = new DOMDocument();
	@$dochtml->loadHTML($html);
	$xpath = new DOMXpath($dochtml);

	// Auth
	if(isset($xpath->query("//input[@name='r']/@value")->item(0)->textContent)){

		$action = $url . $xpath->query("//form/@action")->item(0)->textContent;
		$r = $xpath->query("//input[@name='r']/@value")->item(0)->textContent;
		$jschl_vc = $xpath->query("//input[@name='jschl_vc']/@value")->item(0)->textContent;
		$pass = $xpath->query("//input[@name='pass']/@value")->item(0)->textContent;

		// Generate curl post data
		$post_data = array(
			'r' => $r,
			'jschl_vc' => $jschl_vc,
			'pass' => $pass,
			'jschl_answer' => ''
		);

		curl_close($ct); // Close curl

		return $html;

		$ct = curl_init();

		// Post cloudflare auth parameters
		curl_setopt_array($ct, Array(
			CURLOPT_HTTPHEADER => array(
				'Accept: application/json, text/javascript, */*; q=0.01',
				'Accept-Language: ro-RO,ro;q=0.8,en-US;q=0.6,en-GB;q=0.4,en;q=0.2',
				'Referer: '. $url,
				'Origin: '. $url,
				'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
				'X-Requested-With: XMLHttpRequest'
			),
			CURLOPT_URL => $action,
			CURLOPT_REFERER => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_USERAGENT => $useragent,
			CURLOPT_POSTFIELDS => http_build_query($post_data)

		));

		$html_reponse = curl_exec($ct);

		curl_close($ct); // Close curl

	}else{

		// Already auth
		return $html;

	}

  }
	/**
	* Handle the start event of a tag while parsing
	*
	* @access   private
	* param    object  the xmlParser object
	* param    string  name of currently entering tag
	* param    array   array of attributes
	* @return   void
	*/
	private function startElement($parser, $tagName, $attrs)
	{
		if(!$this->version)
		{
			$this->findVersion($tagName, $attrs);
		}

		array_push($this->insideItem, $tagName);

		$this->currentTag  = $tagName;
		$this->currentAttr = $attrs;
	}

	/**
	* Handle the end event of a tag while parsing
	*
	* @access   private
	* param    object  the xmlParser object
	* param    string  name of currently ending tag
	* @return   void
	*/
	private function endElement($parser, $tagName)
	{
		if (in_array($tagName, $this->itemTags))
		{
		   $this->itemIndex++;
		}

		array_pop($this->insideItem);
		$this->currentTag = $this->insideItem[count($this->insideItem)-1];
	}

	/**
	* Handle character data of a tag while parsing
	*
	* @access   private
	* param    object  the xmlParser object
	* param    string  tag value
	* @return   void
	*/
	private function characterData($parser, $data)
	{
		//Converting all date formats to timestamp
		if(in_array($this->currentTag, $this->dateTags))
		{
			$data = strtotime($data);

		}

	   if($this->inChannel())
	   {
			// If has subtag, make current element an array and assign subtags as it's element
			if(in_array($this->getParentTag(), $this->hasSubTags))
			{
				if(! is_array($this->channels[$this->getParentTag()]))
				{
					$this->channels[$this->getParentTag()] = array();
				}

				$this->channels[$this->getParentTag()][$this->currentTag] .= $this->html2bb(($data));
				return;
			}
			else
			{
				if(! in_array($this->currentTag, $this->hasSubTags))
				{
					$this->channels[$this->currentTag] .= $this->html2bb(($data));
				}
			}

			if(!empty($this->currentAttr))
			{
				$this->channels[$this->currentTag . '_ATTRS'] = $this->currentAttr;

				//If the tag has no value
				if(strlen($this->channels[$this->currentTag]) < 2)
				{
					//If there is only one attribute, assign the attribute value as channel value
					if(count($this->currentAttr) == 1)
					{
						foreach($this->currentAttr as $attrVal)
						{
							$this->channels[$this->currentTag] = $attrVal;
						}
					}
					//If there are multiple attributes, assign the attributs array as channel value
					else
					{
						$this->channels[$this->currentTag] = $this->currentAttr;
					}
				}
			}
	   }
	   elseif($this->inItem())
	   {
		   // If has subtag, make current element an array and assign subtags as it's elements
		   if(in_array($this->getParentTag(), $this->hasSubTags))
			{
				if(! is_array($this->items[$this->itemIndex][$this->getParentTag()]))
				{
					$this->items[$this->itemIndex][$this->getParentTag()] = array();
				}

				$this->items[$this->itemIndex][$this->getParentTag()][$this->currentTag] .= $this->html2bb(($data));
				return;
			}
			else
			{
				if(! in_array($this->currentTag, $this->hasSubTags))
				{
					$this->items[$this->itemIndex][$this->currentTag] .= $this->html2bb(($data));
				}
			}


			if(!empty($this->currentAttr))
			{
				$this->items[$this->itemIndex][$this->currentTag . '_ATTRS'] = $this->currentAttr;

				//If the tag has no value

				if(strlen($this->items[$this->itemIndex][$this->currentTag]) < 2)
				{
					//If there is only one attribute, assign the attribute value as feed element's value
					if(count($this->currentAttr) == 1)
					{
						foreach($this->currentAttr as $attrVal)
						{
						   $this->items[$this->itemIndex][$this->currentTag] = $attrVal;
						}
					}
					//If there are multiple attributes, assign the attribute array as feed element's value
					else
					{
					   $this->items[$this->itemIndex][$this->currentTag] = $this->currentAttr;
					}
				}
			}
	   }
	}

	/**
	* Find out the feed version
	*
	* @access   private
	* param    string  name of current tag
	* param    array   array of attributes
	* @return   void
	*/
	private function findVersion($tagName, $attrs)
	{
		$namespace = array_values($attrs);
		foreach($this->namespaces as $value =>$version)
		{
			if(in_array($value, $namespace))
			{
				$this->version = $version;
				return;
			}
		}
	}

	private function getParentTag()
	{
		return $this->insideItem[count($this->insideItem) - 2];
	}

	/**
	* Detect if current position is in channel element
	*
	* @access   private
	* @return   bool
	*/
	private function inChannel()
	{
		   $this->version =  'RSS 2.0';

		if($this->version == 'RSS 1.0')
		{
			if(in_array('CHANNEL', $this->insideItem) && $this->currentTag != 'CHANNEL')
			return TRUE;
		}
		elseif($this->version == 'RSS 2.0')
		{
			if(in_array('CHANNEL', $this->insideItem) && !in_array('ITEM', $this->insideItem) && $this->currentTag != 'CHANNEL')
			return TRUE;
		}
		elseif($this->version == 'ATOM 1')
		{
			if(in_array('FEED', $this->insideItem) && !in_array('ENTRY', $this->insideItem) && $this->currentTag != 'FEED')
			return TRUE;
		}

		return FALSE;
	}

	/**
	* Detect if current position is in Item element
	*
	* @access   private
	* @return   bool
	*/
	private function inItem()
	{
	   $this->version =  'RSS 2.0';
		if($this->version == 'RSS 1.0' || $this->version == 'RSS 2.0')
		{
			if(in_array('ITEM', $this->insideItem) && $this->currentTag != 'ITEM')
			return TRUE;
		}
		elseif($this->version == 'ATOM 1')
		{
			if(in_array('ENTRY', $this->insideItem) && $this->currentTag != 'ENTRY')
			return TRUE;
		}
       else
        {
			if(in_array('ITEM', $this->insideItem) && $this->currentTag != 'ITEM')
			return TRUE;
        }
		return FALSE;
	}

	//This function is taken from lastRSS
	/**
	* Replace HTML entities &something; by real characters
	*
	*
	* @access   private
	* @author   Vojtech Semecky <webmaster@oslab.net>
	* @link     http://lastrss.oslab.net/
	* param    string
	* @return   string
	*/
	private function unhtmlentities($string)
	{
		// Get HTML entities table
		$trans_tbl = get_html_translation_table (HTML_ENTITIES, ENT_QUOTES);
		// Flip keys<==>values
		$trans_tbl = array_flip ($trans_tbl);
		// Add support for &apos; entity (missing in HTML_ENTITIES)
		$trans_tbl += array('&apos;' => "'");
		// Replace entities by values
		return strtr ($string, $trans_tbl);
	}

	// Converts an HTML email into bbcode
	// This function is loosely based on cbparser.php by corz.org
	function html2bb($string)
	{
		// Do common conversion stuff
		$string = str_ireplace('</blockquote>', '[/quote]', $string);
		$string = str_ireplace('</pre>', '[/code]', $string);
		$string = preg_replace('#<blockquote(| .*?)>#', '[quote]',  $string);
		$string = preg_replace('#<pre(| .*?)>#', '[code]',  $string);

		// Convert quotes
          return ($string);

	}

}
?>
