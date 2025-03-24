<?php
// PBBoard Sitemaps HTML AND XML Updated on 04-01-2016
(!defined('IN_PowerBB')) ? die() : '';
$CALL_SYSTEM					=	array();
$CALL_SYSTEM['SECTION'] 		= 	true;
$CALL_SYSTEM['SUBJECT'] 		= 	true;

define('CLASS_NAME','PowerBBSitemapMOD');
include('common.php');
class PowerBBSitemapMOD
{
	function run()
	{
		global $PowerBB;
	$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');
	$PowerBB->_GET['subject'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['subject'],'intval');
	$PowerBB->_GET['section'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['section'],'intval');
      // Html Sitemap Start
	 if ($PowerBB->_GET['sitemaps'])
		{
		global $PowerBB;
		$PowerBB->_GET['count'] = (!isset($PowerBB->_GET['count'])) ? 0 : $PowerBB->_GET['count'];
		$PowerBB->_GET['count'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['count'],'intval');
	    $perpage_num = "300";
		$GetSubjectNumber = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(1),id FROM " . $PowerBB->table['subject'] . " WHERE review_subject<>1 AND sec_subject<>1 AND delete_topic<>1 LIMIT 1"));
		$SubjectArr = array();
		$SubjectArr['where'] 				= 	array();
		$SubjectArr['where'][0] 			= 	array();
		$SubjectArr['where'][0]['name'] 	= 	'review_subject<>1 AND sec_subject<>1 AND delete_topic';
		$SubjectArr['where'][0]['oper'] 	= 	'<>';
		$SubjectArr['where'][0]['value'] 	= 	'1';
		$SubjectArr['order'] 			= 	array();
		$SubjectArr['order']['field'] 	= 	'native_write_time';
		$SubjectArr['order']['type'] 	= 	'DESC';
		// Ok Mr.XSS go to hell !
		// Pager setup
		$SubjectArr['pager'] 				= 	array();
		$SubjectArr['pager']['total']		= 	$GetSubjectNumber;
		$SubjectArr['pager']['perpage'] 	= 	$perpage_num;
		$SubjectArr['pager']['count'] 		= 	$PowerBB->_GET['count'];
		$SubjectArr['pager']['location'] 	= 	'index.php?page=sitemap&amp;sitemaps=1';
		$SubjectArr['pager']['var'] 		= 	'count';
		$SubjectList = $PowerBB->core->GetList($SubjectArr,'subject');
		$size 	= 	sizeof($SubjectList);
		$x		=	0;
        if($PowerBB->_GET['count'] == '0')
        {
		$SecArr 						= 	array();
		$SecArr['get_from']				=	'db';
		$SecArr['proc'] 				= 	array();
		$SecArr['proc']['*'] 			= 	array('method'=>'clean','param'=>'html');
		$SecArr['order']				=	array();
		$SecArr['order']['field']		=	'id';
		$SecArr['order']['type']		=	'ASC';
		$SecArr['where']				=	array();
		$SecArr['where'][0]['name']		= 	'sec_section<>1 AND hide_subject<>1 AND parent';
		$SecArr['where'][0]['oper']		= 	'>';
		$SecArr['where'][0]['value']	= 	'0';

		// Get main sections
		$catys = $PowerBB->core->GetList($SecArr,'section');

		$catys_size 	= 	sizeof($catys);
		$catys_x		=	0;
       }
		$charset1                =   $PowerBB->_CONF['info_row']['content_dir'];
		$extention = "";
		$url = "index.php?page=topic&amp;show=1&amp;id=";
		$forumurl = "index.php?page=forum&amp;show=1&amp;id=";

        // if multi pages get page number
		$page_num = $PowerBB->functions->CleanVariable($PowerBB->_GET['count'],'intval');
		if($page_num > 1)
		{
		 $page_num = str_replace("&count=","", $page_num);
		 $page_num_count = " | ".$PowerBB->_CONF['template']['_CONF']['lang']['Pagenum']."".$page_num;
		 $page_num_count = str_replace(" %no% من %pnu%", '', $page_num_count);
		 $page_num_count = str_replace(" %no% of %pnu%", '', $page_num_count);
		}


		echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml" dir="'.$PowerBB->_CONF['info_row']['content_dir'].'" xml:lang="'.$PowerBB->_CONF['info_row']['content_language'].'" lang="'.$PowerBB->_CONF['info_row']['content_language'].'">
		<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<link rel="canonical" href="'.$PowerBB->functions->GetPageUrl().'" />
		<meta http-equiv="Content-Language" content="'.$PowerBB->_CONF['info_row']['content_language'].'" />
		<meta name="description" content="" />
		<link rel="shortcut icon" href="favicon.ico" />
		<title>'.$PowerBB->_CONF['template']['_CONF']['lang']['Sitemap_title']." HTML". $page_num_count .'</title>
		</head>
		<body>
		<br /><h1>'.$PowerBB->_CONF['template']['_CONF']['lang']['view_full_version'].'<a title="'.$PowerBB->_CONF['info_row']['title'].'" target="_blank" href="'.$PowerBB->functions->GetForumAdress().'">'.$PowerBB->_CONF['info_row']['title'].'</a></h1><br />';

		if ($GetSubjectNumber > $perpage_num)
		{
		  print($PowerBB->pager->show());
		}

        if($PowerBB->_GET['count'] == '0')
        {
			while ($catys_x < $catys_size)
			{
		       // Get the groups information to know view this section or not
		    if ($PowerBB->functions->section_group_permission($catys[$catys_x]['id'],$PowerBB->_CONF['group_info']['id'],'view_section'))
		      {
	            echo  $PowerBB->functions->rewriterule('<br />&raquo; <a href="'.$PowerBB->functions->GetForumAdress() . $forumurl . $catys[$catys_x]['id'].$extention.'" title="'.$catys[$catys_x]['title'].'" target="_blank">'.$catys[$catys_x]['title'].'</a>');
			  }

			 $catys_x += 1;
			}
		}
		while ($x < $size)
		{
            echo $PowerBB->functions->rewriterule('<br />&raquo; <a href="'.$PowerBB->functions->GetForumAdress() . $url . $SubjectList[$x]['id'].$extention.'" title="'.$SubjectList[$x]['title'].'" target="_blank">'.$SubjectList[$x]['title'].'</a>');
			$x += 1;
		}

		echo '</body></html>';
	  }
      // End Html sitemap

       // Start XML sitemap
		elseif ($PowerBB->_GET['subject'] == 1)
		{
		    $forum_url              =   $PowerBB->functions->GetForumAdress();
			$charset                =   $PowerBB->_CONF['info_row']['charset'];
			header('Content-Type: text/xml; charset=utf-8');
			echo "<sitemapindex xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
			$this->_SubjectSitemap();
			echo '</sitemapindex>';
		}
		elseif ($PowerBB->_GET['topics'] == 1)
		{
			$this->_TopicsSitemap();
		}
		elseif ($PowerBB->_GET['posts'] == 1)
		{
			$this->_PostsSitemap();
		}
		elseif ($PowerBB->_GET['section'])
		{
			$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');
			// No _GET['id'] , so ? show a small error :)
			if (empty($PowerBB->_GET['id']))
			{
			    $PowerBB->functions->ShowHeader("HTTP/1.1 404 Not Found");
				$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Section_does_not_exist']);
		    }
			else
			{
				// Get section information and set it in $this->Section
				$SecArr 			= 	array();
				$SecArr['where'] 	= 	array('id',$PowerBB->_GET['id']);
				$Section = $PowerBB->core->GetInfo($SecArr,'section');
				// This section isn't exists
				if ($Section['parent'] == 0)
				{
	             header('HTTP/1.1 404 Not Found');
	             exit();
			    }
				if (!$Section)
				{
	             header('HTTP/1.1 404 Not Found');
	             exit();
			    }
				// Clear section information from any denger
				$PowerBB->functions->CleanVariable($Section,'html');
				// Temporary array to save the parameter of GetSectionGroupList() in nice way
				$SecGroupArr 						= 	array();
				$SecGroupArr['where'] 				= 	array();
				$SecGroupArr['where'][0]			=	array(	'name' 	=> 'section_id',
																'oper'	=>	'=',
																'value'	=>	$Section['id']);
				$SecGroupArr['where'][1]			=	array();
				$SecGroupArr['where'][1]['con']		=	'AND';
				$SecGroupArr['where'][1]['name']	=	'group_id';
				$SecGroupArr['where'][1]['oper']	=	'=';
				$SecGroupArr['where'][1]['value']	=	$PowerBB->_CONF['group_info']['id'];
				// Ok :) , the permssion for this visitor/member in this section
			    $SectionGroup = $PowerBB->core->GetInfo($SecGroupArr,'sectiongroup');
				// This member can't view this section
				if ($SectionGroup['view_section'] != 1)
				{					$PowerBB->functions->ShowHeader($PowerBB->_CONF['template']['_CONF']['lang']['can_not_view_section']);
					$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['can_not_view_section']);
			    }
				// This is main section , so we can't get subjects list from it
				if ($Section['main_section'])
				{
	             header('HTTP/1.1 404 Not Found');
	             exit();
			    }
				if ($Section['hide_subject'])
				{
					$PowerBB->functions->ShowHeader($PowerBB->_CONF['template']['_CONF']['lang']['can_not_view_section']);
					$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['can_not_view_section']);
			    }
				if ($Section['sec_section'])
				{
					$PowerBB->functions->ShowHeader($PowerBB->_CONF['template']['_CONF']['lang']['can_not_view_section']);
					$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['can_not_view_section']);
			    }
				if (!empty($Section['section_password']))
				{
					$PowerBB->functions->ShowHeader($PowerBB->_CONF['template']['_CONF']['lang']['can_not_view_section']);
					$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['can_not_view_section']);
			    }
			    $forum_url              =   $PowerBB->functions->GetForumAdress();
				$charset                =   $PowerBB->_CONF['info_row']['charset'];
				$forumurl = "index.php?page=forum&amp;show=1&amp;id=";

				// Clean id from any strings
				$SubjectArr = array();
				$SubjectArr['where'] 				= 	array();
				$SubjectArr['where'][0] 			= 	array();
				$SubjectArr['where'][0]['name'] 	= 	'section';
				$SubjectArr['where'][0]['oper'] 	= 	'=';
				$SubjectArr['where'][0]['value'] 	= 	$PowerBB->_GET['id'];
				$SubjectArr['where'][1] 			= 	array();
				$SubjectArr['where'][1]['con']		=	'AND';
				$SubjectArr['where'][1]['name'] 	= 	'review_subject<>1 AND sec_subject<>1 AND delete_topic';
				$SubjectArr['where'][1]['oper'] 	= 	'<>';
				$SubjectArr['where'][1]['value'] 	= 	'1';
				$SubjectArr['order'] 			= 	array();
				$SubjectArr['order']['field'] 	= 	'native_write_time';
				$SubjectArr['order']['type'] 	= 	'DESC';
				$SubjectArr['limit'] 			= 	'1000';
				$SubjectArr['proc'] 			= 	array();
				// Ok Mr.XSS go to hell !
				$SubjectArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');
				$SubjectList = $PowerBB->core->GetList($SubjectArr,'subject');
				if($SubjectList)
				{
				header('Content-Type: text/xml; charset=utf-8');
				echo "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
				}
				if($SubjectList)
				{
					$size 	= 	sizeof($SubjectList);
					$x		=	0;
					while ($x < $size)
					{
					echo '<url>';
					echo '<loc>'.$PowerBB->functions->rewriterule_singl('page=topic&amp;show=1&amp;id='.$SubjectList[$x]['id']).'</loc>';
					echo '<lastmod>'.$this->lastmod_date($SubjectList[$x]['native_write_time']).'</lastmod>';
					echo '</url>'."\n";
					$x += 1;
					}
				}
				if($SubjectList)
				{
				echo '</urlset>';
				}
				else
				{
	             header('HTTP/1.1 404 Not Found');
	             exit();
				}
		    }
		}
		else
		{
	             header('HTTP/1.1 404 Not Found');
	             exit();
		}
	}

	function _SubjectSitemap()
	{
		global $PowerBB;

       $catys= $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['section'] . " WHERE sec_section<>1 AND hide_subject<>1 AND linksection<>1 AND parent > 0 AND subject_num > 0 ORDER BY id ASC");

		$forum_sitemap = "index.php?page=sitemap&amp;section=1&amp;id=";
		$forum_sitemap = $PowerBB->functions->rewriterule($forum_sitemap);
		while ($getSections_row = $PowerBB->DB->sql_fetch_array($catys))
		{
	       // Get the groups information to know view this section or not
		if ($PowerBB->functions->section_group_permission($getSections_row['id'],$PowerBB->_CONF['group_info']['id'],'view_section') and $getSections_row['section_password'] == '')
	      {
				echo '<sitemap>';
				echo '<loc>'. $PowerBB->functions->GetForumAdress() . $forum_sitemap . $getSections_row['id'] . ".xml" . '</loc>';
				echo '<lastmod>'.$this->lastmod_date($getSections_row['last_date']).'</lastmod>';
				echo '</sitemap>'."\n";

		  }
		}
	}

	function _TopicsSitemap()
	{
		global $PowerBB;

       $Topics_query= $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['subject'] . " WHERE review_subject<>1 AND sec_subject<>1 AND delete_topic<>1 ORDER BY write_time DESC LIMIT 100");

		$subject_sitemap = "index.php?page=topic&amp;show=1&amp;id=";
		header('Content-Type: text/xml; charset=utf-8');
	   echo '<?xml version="1.0" encoding="windows-1256" ?>'."\n";
	   echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/09/sitemap.xsd">'."\n";
		while ($Topics_row = $PowerBB->DB->sql_fetch_array($Topics_query))
		{
		if ($PowerBB->functions->section_group_permission($Topics_row['section'],$PowerBB->_CONF['group_info']['id'],'view_section'))
	      {
				echo '<url>'."\n";
				echo '<loc>'.$PowerBB->functions->rewriterule_singl('page=topic&amp;show=1&amp;id='.$Topics_row['id']).'</loc>'."\n";
				echo '<changefreq>daily</changefreq>'."\n";
				echo '<priority>0.8</priority>'."\n";
				echo '</url>'."\n";
		  }
		}
	  echo '</urlset>';
	}

	function _PostsSitemap()
	{
		global $PowerBB;
       $forum_not = $PowerBB->_CONF['info_row']['last_subject_writer_not_in'];
       $Replys_query= $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['reply'] . " WHERE delete_topic = 0 AND section not in (" .$forum_not. ") AND review_reply = 0 ORDER BY write_time DESC LIMIT 100");

		header('Content-Type: text/xml; charset=utf-8');
	   echo '<?xml version="1.0" encoding="windows-1256" ?>'."\n";
	   echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/09/sitemap.xsd">'."\n";
		while ($Replys_row = $PowerBB->DB->sql_fetch_array($Replys_query))
		{
			if ($PowerBB->functions->section_group_permission($Replys_row['section'],$PowerBB->_CONF['group_info']['id'],'view_section'))
		      {
					echo '<url>'."\n";
					echo '<loc>'.$PowerBB->functions->rewriterule_singl('page=post&amp;show=1&amp;id='.$Replys_row['id']).'</loc>'."\n";
					echo '<changefreq>daily</changefreq>'."\n";
					echo '<priority>0.8</priority>'."\n";
					echo '</url>'."\n";
			  }
		}
	  echo '</urlset>';
	}

	function lastmod_date($time)
	{
		global $PowerBB;
		$returnValue = date('Y-m-d\TH:i:sP', $time);
		return $returnValue;


	}
   // End Xml sitemap
}
?>
