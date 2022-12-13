<?php
(!defined('IN_PowerBB')) ? die() : '';
$CALL_SYSTEM				=	array();
$CALL_SYSTEM['SUBJECT'] 	= 	true;
$CALL_SYSTEM['SECTION'] 	= 	true;

define('CLASS_NAME','PowerBBRSSMOD');
include('common.php');
class PowerBBRSSMOD
{
	function run()
	{
	 global $PowerBB;
 	 if (!$PowerBB->_CONF['info_row']['active_rss'])
	 {
			@header("Location: index.php");
			exit;
	 }
		$charset                =   $PowerBB->_CONF['info_row']['charset'];
		$datenow                =   date(DATE_RFC2822);
	$PowerBB->_GET['subject'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['subject'],'intval');
	$PowerBB->_GET['section'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['section'],'intval');
	$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');
		if ($PowerBB->_GET['subject'])
		{
		$Forumtitle                =   $PowerBB->_CONF['info_row']['title'];
		}
		elseif ($PowerBB->_GET['section'])
		{
		$SecArr 		= 	array();
		$SecArr['where'] 	= 	array('id',$PowerBB->_GET['id']);
		$Section = $PowerBB->core->GetInfo($SecArr,'section');
		$Forumtitle                =   $PowerBB->_CONF['info_row']['title'] .' - ' .$PowerBB->functions->CleanText($Section['title'])." - " .$PowerBB->functions->CleanText($Section['section_describe']);
		}
 		$PowerBB->_CONF['info_row']['title'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_CONF['info_row']['title'],'html');
		$PowerBB->_CONF['info_row']['title'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_CONF['info_row']['title'],'sql');
        header('Content-Type: text/xml; charset=utf-8');
		echo "<rss version=\"2.0\" xmlns:atom=\"http://www.w3.org/2005/Atom\">\n";
		echo "<channel>\n";
		$atomLink = $PowerBB->_SERVER['REQUEST_URI'];
		if(strstr($atomLink,'rss.xml'))
		{		$atomLink = 'rss.xml';
		}
		else
		{
		$atomLink = 'index.php?page=rss&subject=1';
		}
		$atomLink = str_replace("&","&amp;",$atomLink);
		$PowerBB->_CONF['info_row']['title'] = $this->convert_int_to_utf8($PowerBB->_CONF['info_row']['title']);
		$Forumtitle = $this->convert_int_to_utf8($Forumtitle);

		echo "<atom:link href=\"".$PowerBB->functions->GetForumAdress().$atomLink."\" rel=\"self\" type=\"application/rss+xml\" />\n";
		echo "<title>" . $PowerBB->_CONF['info_row']['title'] . " - " . $PowerBB->functions->GetForumAdress() . "</title>\n";
		echo "<link>" . $PowerBB->functions->GetForumAdress() . "</link>\n";
		echo "<description>".$PowerBB->_CONF['template']['_CONF']['lang']['Abstracts_another_active_topics_in'] . ":".$Forumtitle ."</description>\n";
		echo "<generator>" . $PowerBB->_CONF['info_row']['title'] . "</generator>\n";
        echo "<pubDate>" . $datenow . "</pubDate>\n";
		if ($PowerBB->_GET['subject'])
		{
			$this->_SubjectRSS();
		}
		elseif ($PowerBB->_GET['section'])
		{
			$this->_SectionRSS();
		}
		echo "</channel>\n";
		echo "</rss>\n";
	}

	function _SubjectRSS()
	{
	global $PowerBB;
	$SubjectArr = array();
	$SubjectArr['where'] 		= 	array();
	$SubjectArr['where'][0] 		= 	array();
	$SubjectArr['where'][0]['name'] 	= 	'review_subject<>1 AND sec_subject<>1 AND delete_topic';
	$SubjectArr['where'][0]['oper'] 	= 	'<>';
	$SubjectArr['where'][0]['value'] 	= 	'1';
	$SubjectArr['order'] 		= 	array();
	$SubjectArr['order']['field'] 	= 	'write_time';
	$SubjectArr['order']['type'] 	= 	'DESC';
	$SubjectArr['limit'] 		= 	'12';
	$SubjectArr['proc'] 		= 	array();
	$SubjectArr['proc']['*'] 	= 	array('method'=>'clean','param'=>'html');
	$SubjectArr['proc']['native_write_time'] 	= 	array('method'=>'date','store'=>'write_date','type'=>$datenow);
	$SubjectArr['proc']['write_time'] 			= 	array('method'=>'date','store'=>'reply_date','type'=>$datenow);
	$SubjectList = $PowerBB->core->GetList($SubjectArr,'subject');
	$size 	= 	sizeof($SubjectList);
	$x	=	0;
	while ($x < $size)
	 {        $SubjectList[$x]['text'] = @preg_replace('#<img .*src="(.*)".*>#iU', "{img_s}$1{img_e}", $SubjectList[$x]['text']);
	    //$_searchBB = '#\[(.*)\]#esiU';
	   // $_replaceBB = "";
       	$SubjectList[$x]['text'] = str_replace($PowerBB->_CONF['template']['_CONF']['lang']['resize_image_w_h'], "", $SubjectList[$x]['text']);

		$description = $PowerBB->Powerparse->ShortPhrase($PowerBB->functions->CleanText($SubjectList[$x]['text']),300);
		$SubjectList[$x]['text'] = $PowerBB->Powerparse->ShortPhrase($PowerBB->functions->CleanText($SubjectList[$x]['text']),400);

		//$SubjectList[$x]['text'] = @preg_replace($_searchBB,$_replaceBB,$SubjectList[$x]['text']);
		//$SubjectList[$x]['title'] = @preg_replace($_searchBB,$_replaceBB,$SubjectList[$x]['title']);
        $SubjectList[$x]['text'] = $PowerBB->Powerparse->censor_words($SubjectList[$x]['text']);
        $SubjectList[$x]['title'] = $PowerBB->Powerparse->censor_words($SubjectList[$x]['title']);
		$SubjectList[$x]['text'] =@str_ireplace("\n","<br />",$SubjectList[$x]['text']);
		//$_search = '#\&(.*)\;#esiU';
		//$_replace = "";
		//$SubjectList[$x]['text'] = @preg_replace($_search,$_replace,$SubjectList[$x]['text']);
		//$SubjectList[$x]['title'] = @preg_replace($_search,$_replace,$SubjectList[$x]['title']);

		$bad_characters = array_diff(range(chr(0), chr(31)), array(chr(9), chr(10), chr(13)));
		$SubjectList[$x]['text'] = str_replace($bad_characters, "", $SubjectList[$x]['text']);
		$SubjectList[$x]['title'] = str_replace($bad_characters, "", $SubjectList[$x]['title']);
		$SubjectList[$x]['text'] =str_replace("الموضوع الأصلي","",$SubjectList[$x]['text']);
        $censorwords = preg_split('#[ \r\n\t]+#', $PowerBB->_CONF['info_row']['censorwords'], -1, PREG_SPLIT_NO_EMPTY);
        $SubjectList[$x]['text'] = @str_ireplace($censorwords,'**', $SubjectList[$x]['text']);

     	$description = str_replace(" .","", $description);
        $description = str_replace(". ","", $description);
       $description = str_replace("{img_s}",'[img]', $description);
       $description = str_replace("{img_e}",'[/img]', $description);
		$description = $this->convert_int_to_utf8($description);
		$description = str_replace("[img]look/","[img]".$PowerBB->functions->GetForumAdress()."look/", $description);

		$SubjectList[$x]['title'] = $this->convert_int_to_utf8($SubjectList[$x]['title']);

		$extention = "";
		$url = "index.php?page=topic&amp;show=1&amp;id=";
		$url = $PowerBB->functions->rewriterule($url);
		echo "<item>";
		echo "<title>" . $PowerBB->functions->CleanText($SubjectList[$x]['title'])  . "</title>\n";
		echo "<description>" . trim($description) . "</description>\n";
		echo "<link>" . $PowerBB->functions->GetForumAdress() . $url . $SubjectList[$x]['id'] . $extention . "</link>\n";
		echo '<pubDate>' . date("r", $PowerBB->functions->CleanText($SubjectList[$x]['write_time'])) . '</pubDate>' . "\n";
		echo "<guid>" . $PowerBB->functions->GetForumAdress() . $url . $SubjectList[$x]['id'] . $extention . "</guid>\n";
		echo "</item>\n";
		$x += 1;
	  }
	}

	function _SectionRSS()
	{
	global $PowerBB;
	$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');
	$SecArr 		= 	array();
	$SecArr['where'] 	= 	array('id',$PowerBB->_GET['id']);
	$Section = $PowerBB->core->GetInfo($SecArr,'section');
		if ($PowerBB->functions->section_group_permission($PowerBB->_GET['id'],$PowerBB->_CONF['group_info']['id'],'view_section') == 0)
		{
		$Section['hide_subject'] = '1';
		}
		elseif ($PowerBB->functions->section_group_permission($PowerBB->_GET['id'],$PowerBB->_CONF['group_info']['id'],'view_subject') == 0)
		{
		$Section['hide_subject']	= '1';
		}
		if ($Section['hide_subject'] == '1')
		{
		echo '	<item>';
		echo '		<title>' . $PowerBB->_CONF['template']['_CONF']['lang']['can_not_view_section'] . '</title>';
		echo '		<link>' . $PowerBB->_CONF['template']['_CONF']['lang']['can_not_view_section'] . '</link>';
		echo '		<description>' . $PowerBB->_CONF['template']['_CONF']['lang']['can_not_view_section'] . '</description>';
		echo '	</item>';
		$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['can_not_view_section']);
		}
		if (!$Section)
		{
		echo '	<item>';
		echo '		<title>' . $PowerBB->_CONF['template']['_CONF']['lang']['path_not_true'] . '</title>';
		echo '		<link>' . $PowerBB->_CONF['template']['_CONF']['lang']['path_not_true'] . '</link>';
		echo '		<description>' . $PowerBB->_CONF['template']['_CONF']['lang']['path_not_true'] . '</description>';
		echo '	</item>';
		}
	$SubjectArr = array();
	$SubjectArr['where'] 		= 	array();
	$SubjectArr['where'][0] 		= 	array();
	$SubjectArr['where'][0]['name'] 	= 	'section';
	$SubjectArr['where'][0]['oper'] 	= 	'=';
	$SubjectArr['where'][0]['value'] 	= 	$PowerBB->_GET['id'];
	$SubjectArr['where'][1] 		    = 	array();
	$SubjectArr['where'][1]['con']	    =	'AND';
	$SubjectArr['where'][1]['name'] 	= 	'review_subject<>1 AND sec_subject<>1 AND delete_topic';
	$SubjectArr['where'][1]['oper'] 	= 	'<>';
	$SubjectArr['where'][1]['value'] 	= 	'1';
	$SubjectArr['order'] 		= 	array();
	$SubjectArr['order']['field'] 	= 	'write_time';
	$SubjectArr['order']['type'] 	= 	'DESC';
	$SubjectArr['limit'] 		= 	'15';
	$SubjectArr['proc'] 		= 	array();
	$SubjectArr['proc']['*'] 	= 	array('method'=>'clean','param'=>'html');
	$SubjectList = $PowerBB->core->GetList($SubjectArr,'subject');
	$size 	= 	sizeof($SubjectList);
	$x	=	0;
	while ($x < $size)
	{
       $SubjectList[$x]['text'] = @preg_replace('#<img .*src="(.*)".*>#iU', "{img_s}$1{img_e}", $SubjectList[$x]['text']);
       	$SubjectList[$x]['text'] = str_replace($PowerBB->_CONF['template']['_CONF']['lang']['resize_image_w_h'], "", $SubjectList[$x]['text']);
     	$description = $PowerBB->Powerparse->ShortPhrase($PowerBB->functions->CleanText($SubjectList[$x]['text']),300);
        $SubjectList[$x]['text'] = $PowerBB->Powerparse->ShortPhrase($PowerBB->functions->CleanText($SubjectList[$x]['text']),400);

	    //$_searchBB = '#\[(.*)\]#esiU';
	   // $_replaceBB = "";
		//$SubjectList[$x]['text'] = @preg_replace($_searchBB,$_replaceBB,$SubjectList[$x]['text']);
		//$SubjectList[$x]['title'] = @preg_replace($_searchBB,$_replaceBB,$SubjectList[$x]['title']);
        $SubjectList[$x]['text'] = $PowerBB->Powerparse->censor_words($SubjectList[$x]['text']);
        $SubjectList[$x]['title'] = $PowerBB->Powerparse->censor_words($SubjectList[$x]['title']);
		$SubjectList[$x]['text'] =@str_ireplace("\n","<br />",$SubjectList[$x]['text']);
		$SubjectList[$x]['text'] =str_replace($PowerBB->_CONF['template']['_CONF']['lang']['the_original_topic'],"",$SubjectList[$x]['text']);
		//$_search = '#\&(.*)\;#esiU';
		//$_replace = "";
		//$SubjectList[$x]['text'] = @preg_replace($_search,$_replace,$SubjectList[$x]['text']);
		//$SubjectList[$x]['title'] = @preg_replace($_search,$_replace,$SubjectList[$x]['title']);
		$bad_characters = array_diff(range(chr(0), chr(31)), array(chr(9), chr(10), chr(13)));
		$SubjectList[$x]['text'] = str_replace($bad_characters, "", $SubjectList[$x]['text']);
		$SubjectList[$x]['title'] = str_replace($bad_characters, "", $SubjectList[$x]['title']);

		$SubjectList[$x]['text'] =str_replace("الموضوع الأصلي","",$SubjectList[$x]['text']);
        $censorwords = preg_split('#[ \r\n\t]+#', $PowerBB->_CONF['info_row']['censorwords'], -1, PREG_SPLIT_NO_EMPTY);
        $SubjectList[$x]['text'] = @str_ireplace($censorwords,'**', $SubjectList[$x]['text']);
        $description = str_replace(" .","", $description);
        $description = str_replace(". ","", $description);
        $description = str_replace("{img_s}","[img]", $description);
        $description = str_replace("{img_e}","[/img]", $description);
		$description = $this->convert_int_to_utf8($description);
		$SubjectList[$x]['title'] = $this->convert_int_to_utf8($SubjectList[$x]['title']);
		$extention = "";
		$url = "index.php?page=topic&amp;show=1&amp;id=";
		$url = $PowerBB->functions->rewriterule($url);
		$description = str_replace("[img]look/","[img]".$PowerBB->functions->GetForumAdress()."look/", $description);
		echo "<item>";
		echo "<title>" . $PowerBB->functions->CleanText($SubjectList[$x]['title'])  . "</title>\n";
		echo "<description>" . trim($description) . "</description>\n";
		echo "<link>" . $PowerBB->functions->GetForumAdress() . $url . $SubjectList[$x]['id'] . $extention . "</link>\n";
		echo '<pubDate>' . date("r", $PowerBB->functions->CleanText($SubjectList[$x]['write_time'])) . '</pubDate>' . "\n";
		echo "<guid>" . $PowerBB->functions->GetForumAdress() . $url . $SubjectList[$x]['id'] . $extention . "</guid>\n";
		echo "</item>\n";
		$x += 1;
	}
	}

  function convert_int_to_utf8($content)
	{
    	global $PowerBB;

		$content = preg_replace('/[\x00-\x1F\x7F]/', '', $content);
		$find=array("’","“","”"); //special characters other then characters starting with & and ending with ;
		$content=htmlspecialchars_decode($content);
		$content=strip_tags($content);
		$content=str_replace($find,"",$content);
		$content=preg_replace('/\&.*\;/','',$content); //Replace all words starting with & and ending with ; with ''
		$content=preg_replace("/(\r?\n){2,}/",'', $content); //Replace all newline characters with ''
		$content=preg_replace( "/\s+/", " ", $content );//Replace multiple spaces with single space
		$content=trim($content);
		$content=str_replace("&","&amp;",$content);
		$content=str_replace("<","&lt;",$content);
		$content=str_replace(">","&gt;",$content);
		$content=str_replace("'","&apos;",$content);
		$content=str_replace('"',"&quot;",$content);
		return $content;
	}

}
?>