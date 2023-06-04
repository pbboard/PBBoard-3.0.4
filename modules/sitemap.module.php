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
	    $perpage_num = "150";
		$GetSubjectNumber = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(1),id FROM " . $PowerBB->table['subject'] . " WHERE review_subject<>1 AND sec_subject<>1 AND delete_topic<>1 LIMIT 1"));
		$SubjectArr = array();
		$SubjectArr['where'] 				= 	array();
		$SubjectArr['where'][0] 			= 	array();
		$SubjectArr['where'][0]['name'] 	= 	'review_subject<>1 AND sec_subject<>1 AND delete_topic';
		$SubjectArr['where'][0]['oper'] 	= 	'<>';
		$SubjectArr['where'][0]['value'] 	= 	'1';
		$SubjectArr['order'] 			= 	array();
		$SubjectArr['order']['field'] 	= 	'write_time';
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
        if($PowerBB->_GET['count']== 0)
        {
		$SecArr 						= 	array();
		$SecArr['get_from']				=	'db';
		$SecArr['proc'] 				= 	array();
		$SecArr['proc']['*'] 			= 	array('method'=>'clean','param'=>'html');
		$SecArr['order']				=	array();
		$SecArr['order']['field']		=	'sort';
		$SecArr['order']['type']		=	'DESC';
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
		$url = $PowerBB->functions->rewriterule($url);
		$forumurl = "index.php?page=forum&amp;show=1&amp;id=";
		$forumurl = $PowerBB->functions->rewriterule($forumurl);
		echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml" dir="'.$PowerBB->_CONF['info_row']['content_dir'].'" xml:lang="en" lang="en">
		<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta http-equiv="Content-Language" content="en" />
		<meta name="description" content="" />
		<link rel="shortcut icon" href="favicon.ico" />
		<title>' .$PowerBB->_CONF['template']['_CONF']['lang']['Sitemap_title'] . ' - ' .$PowerBB->_CONF['info_row']['title'] . ' Sitemape</title>
		</head>
		<body><ol>
		<li><a title="'.$PowerBB->_CONF['info_row']['title'].'" target="_blank" href="'.$PowerBB->functions->GetForumAdress().'">'.$PowerBB->_CONF['info_row']['title'].'</a><br />'.$PowerBB->_CONF['info_row']['description'].'<hr></hr></li>';
        if($PowerBB->_GET['count']== 0)
        {
			while ($catys_x < $catys_size)
			{
		       // Get the groups information to know view this section or not
		    if ($PowerBB->functions->section_group_permission($catys[$catys_x]['id'],$PowerBB->_CONF['group_info']['id'],'view_section'))
		      {
	            echo  '<li><a title="'.$catys[$catys_x]['title'].'" target="_blank" href="'.$PowerBB->functions->GetForumAdress() . $forumurl . $catys[$catys_x]['id'].$extention.'">'.$catys[$catys_x]['title'].'</a><br /><font color="#008000" size="-1">'.$PowerBB->functions->GetForumAdress() . $forumurl . $catys[$catys_x]['id'].$extention.'</font><br /><hr></hr></li>';
			  }

			 $catys_x += 1;
			}
		}
		while ($x < $size)
		{
            echo '<li><a title="'.$SubjectList[$x]['title'].'" target="_blank" href="'.$PowerBB->functions->GetForumAdress() . $url . $SubjectList[$x]['id'].$extention.'">'.$SubjectList[$x]['title'].'</a><br /><font color="#008000" size="-1">'.$PowerBB->functions->GetForumAdress() . $url . $SubjectList[$x]['id'].$extention.'</font><br /><hr></hr></li>';
			$x += 1;
		}
		echo '</ol>';
		if ($GetSubjectNumber > $perpage_num)
		{
		  print($PowerBB->pager->show());
		}
		echo '</body></html>';
	  }
      // End Html sitemap

       // Start XML sitemap
		if ($PowerBB->_GET['subject'])
		{
		    $forum_url              =   $PowerBB->functions->GetForumAdress();
			$charset                =   $PowerBB->_CONF['info_row']['charset'];
			header('Content-Type: text/xml; charset=utf-8');
			echo "<sitemapindex xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
			$this->_SubjectSitemap();
			echo '</sitemapindex>';
		}
		elseif ($PowerBB->_GET['section'])
		{
			$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');
			// No _GET['id'] , so ? show a small error :)
			if (empty($PowerBB->_GET['id']))
			{
				header("Location: index.php");
				exit;
		    }
			else
			{
				// Get section information and set it in $this->Section
				$SecArr 			= 	array();
				$SecArr['where'] 	= 	array('id',$PowerBB->_GET['id']);
				$Section = $PowerBB->core->GetInfo($SecArr,'section');
				// This section isn't exists
				if (!$Section)
				{
					header("Location: index.php");
					exit;
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
				{
					header("Location: index.php");
					exit;
			    }
				// This is main section , so we can't get subjects list from it
				if ($Section['main_section'])
				{
					header("Location: index.php");
					exit;
			    }
				if ($Section['hide_subject'])
				{
					header("Location: index.php");
					exit;
			    }
				if ($Section['sec_section'])
				{
					header("Location: index.php");
					exit;
			    }
				if (!empty($Section['section_password']))
				{
					header("Location: index.php");
					exit;
			    }
			    $forum_url              =   $PowerBB->functions->GetForumAdress();
				$charset                =   $PowerBB->_CONF['info_row']['charset'];
				$url = "index.php?page=topic&amp;show=1&amp;id=";
				$forumurl = "index.php?page=forum&amp;show=1&amp;id=";
				$url = $PowerBB->functions->rewriterule($url);
				header('Content-Type: text/xml; charset=utf-8');
				echo "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
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
				$forum_sitemap = "index.php?page=sitemap&amp;section=1&amp;id=";
				$forum_sitemap = $PowerBB->functions->rewriterule($forum_sitemap);
				if($SubjectList)
				{
					$size 	= 	sizeof($SubjectList);
					$x		=	0;
					while ($x < $size)
					{
					$extention = "";
					$url = "index.php?page=topic&amp;show=1&amp;id=";
					$url = $PowerBB->functions->rewriterule($url);
					echo '<url>';
					echo '<loc>'.$PowerBB->functions->GetForumAdress() . $url . $SubjectList[$x]['id'] . $extention . '</loc>';
	                echo '<lastmod>'.$this->lastmod_date($SubjectList[$x]['native_write_time']).'</lastmod>';
					echo '</url>'."\n";
					$x += 1;
					}
				}
				echo '</urlset>';
		    }
		}
	}

	function _SubjectSitemap()
	{
		global $PowerBB;

       $catys= $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['section'] . " WHERE sec_section<>1 AND hide_subject<>1 AND linksection<>1 AND parent > 0 AND subject_num > 0 ORDER BY id ASC");

		$forum_sitemap = "index.php?page=sitemap&amp;section=1&amp;id=";
		$forum_sitemap = $PowerBB->functions->rewriterule($forum_sitemap);
		while ($getSections_row = $PowerBB->DB->sql_fetch_array($catys))
		{	       // Get the groups information to know view this section or not
		if ($PowerBB->functions->section_group_permission($getSections_row['id'],$PowerBB->_CONF['group_info']['id'],'view_section') and $getSections_row['section_password'] == '')
	      {
				echo '<sitemap>';
				echo '<loc>'. $PowerBB->functions->GetForumAdress() . $forum_sitemap . $getSections_row['id'] . ".xml" . '</loc>';
				echo '<lastmod>'.$this->lastmod_date($getSections_row['last_date']).'</lastmod>';
				echo '</sitemap>'."\n";

		  }
		}
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
