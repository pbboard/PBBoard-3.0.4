<?php
(!defined('IN_PowerBB')) ? die() : '';
define('FOOTER_NAME','PowerBBFooterMOD');
class PowerBBFooterMOD
{
	function run()
	{
		global $PowerBB;
		// Get style list
		$StyleListArr 							= 	array();

		// Clean data
		$StyleListArr['proc']					=	array();
		$StyleListArr['proc']['*']				=	array('method'=>'clean','param'=>'html');

		// Where setup
		$StyleListArr['where'][0]				=	array();
		$StyleListArr['where'][0]['con']		=	'AND';
		$StyleListArr['where'][0]['name']		=	'style_on';
		$StyleListArr['where'][0]['oper']		=	'=';
		$StyleListArr['where'][0]['value']		=	'1';

		// Order setup
		$StyleListArr['order'] 					= 	array();
		$StyleListArr['order']['field'] 		= 	'style_order';
		$StyleListArr['order']['type'] 			= 	'ASC';

		$PowerBB->_CONF['template']['while']['StyleList'] = $PowerBB->style->GetStyleList($StyleListArr);
        $StylesNm = sizeof($PowerBB->_CONF['template']['while']['StyleList']);
        $PowerBB->template->assign('StylesNo',$StylesNm);
        if (isset($PowerBB->_COOKIE[$PowerBB->_CONF['style_cookie']]))
        {
         $PowerBB->_CONF['template']['_CONF']['rows']['style']['id'] = $PowerBB->_COOKIE[$PowerBB->_CONF['style_cookie']];

         $PowerBB->template->assign('style_id',$PowerBB->_CONF['template']['_CONF']['rows']['style']['id']);

        }
        else
        {
			if ($PowerBB->functions->checkmobile())
			{
			  $PowerBB->_CONF['template']['_CONF']['rows']['style']['id'] = $PowerBB->functions->checkmobile();
			}
	       else
	        {
			  $PowerBB->_CONF['template']['_CONF']['rows']['style']['id'] = $PowerBB->_CONF['info_row']['def_style'];
	        }

          $PowerBB->template->assign('style_id',$PowerBB->_CONF['template']['_CONF']['rows']['style']['id']);
        }

		///////////

		// Get lang list
		$LangListArr 							= 	array();

		// Clean data
		$LangListArr['proc']					=	array();
		$LangListArr['proc']['*']				=	array('method'=>'clean','param'=>'html');

		// Where setup
		$LangListArr['where'][0]				=	array();
		$LangListArr['where'][0]['con']		=	'AND';
		$LangListArr['where'][0]['name']		=	'lang_on';
		$LangListArr['where'][0]['oper']		=	'=';
		$LangListArr['where'][0]['value']		=	'1';

		// Order setup
		$LangListArr['order'] 					= 	array();
		$LangListArr['order']['field'] 		= 	'lang_order';
		$LangListArr['order']['type'] 			= 	'ASC';

		$PowerBB->_CONF['template']['while']['LangList'] = $PowerBB->lang->GetLangList($LangListArr);


        $LangsNm = sizeof($PowerBB->_CONF['template']['while']['LangList']);
        $PowerBB->template->assign('LangsNo',$LangsNm);
        $PowerBB->template->assign('lang_id',$PowerBB->_CONF['LangId']);
        if (isset($PowerBB->_COOKIE[$PowerBB->_CONF['lang_cookie']]))
        {
         $PowerBB->_CONF['template']['_CONF']['rows']['lang']['id']= $PowerBB->_COOKIE[$PowerBB->_CONF['lang_cookie']];
         $PowerBB->_CONF['template']['_CONF']['member_row']['lang']  = $PowerBB->_COOKIE[$PowerBB->_CONF['lang_cookie']];
         $PowerBB->template->assign('lang_id',$PowerBB->_COOKIE[$PowerBB->_CONF['lang_cookie']]);
        }
		////////////
       if ($PowerBB->_CONF['info_row']['active_subject_today'])
		{
	       /**
		   * Ok , are you ready to get subject today nm ? :)
            */
			$day 	= 	date('j');
			$month 	= 	date('n');
			$year 	= 	date('Y');

			$from 	= 	mktime(0,0,0,$month,$day,$year);
			$to 	= 	mktime(23,59,59,$month,$day,$year);

	        $forum_not = $PowerBB->_CONF['info_row']['last_subject_writer_not_in'];

	        $subject_today_nm = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(1),id FROM " . $PowerBB->table['subject'] . " WHERE native_write_time BETWEEN " . $from . " AND " . $to . " AND section not in (" .$forum_not. ") AND review_subject<>1 AND delete_topic<>1 LIMIT 1"));

			$PowerBB->template->assign('subject_today_nm',$subject_today_nm);
        }
       $PowerBB->template->assign('timer',$PowerBB->sys_functions->_time(time()));

        // Get Footer
		$Get_Footer = $PowerBB->template->display('footer');

        // slurp all enabled feeds from the database
        $PowerBB->functions->_RunFeedRss();

        ///////


		if (!empty($PowerBB->_GET['debug']))
		{
			if ($PowerBB->_CONF['member_row']['usergroup'] == '1')
			{
				$x = 1;
				foreach ($PowerBB->_CONF['temp']['queries'] as $k => $v)
				{
					echo $x . ': ' . $v . '<hr />';

					$x++;

				}
			}
			else
			{
              exit;
		    }
		}
		// $PowerBB->template->assign('query_num',$PowerBB->_CONF['temp']['query_numbers']);
      // echo '<a target="_blank" href="'.$PowerBB->_SERVER['REQUEST_URI'].'&debug=1">Debug</a></div>';
      //echo ''.$PowerBB->_CONF['temp']['query_numbers'].'<br />';

		// Kill everything , Hey PowerBB you should be lovely with server because it's Powered by Linux ;)
		unset($PowerBB->_CONF);
		unset($PowerBB->table);
		unset($PowerBB->DB->sql_query);
		unset($PowerBB->DB->sql_num_rows);
		unset($PowerBB->DB->sql_fetch_row);
 		unset($PowerBB->template->_vars);
 		unset($PowerBB->_GET);
 		unset($PowerBB->_POST);
 		unset($PowerBB->_SERVER);
 		unset($PowerBB->_COOKIE);
 		unset($PowerBB->_POST);
		unset($PowerBB->template->display);
		unset($text);
		unset($PowerBB->template->content);
		unset($PowerBB->template->_CompileTemplate);
		unset($PowerBB->template->_ProccessWhile);
		unset($PowerBB->template->_ProccessForeach);
		unset($PowerBB->template->_StoreForeachVarName);
		unset($PowerBB->template->_ProccessIfStatement);
		unset($PowerBB->template->_ProccessIfStatementVariables);
		unset($PowerBB->template->_GetCompiledFile);
		unset($PowerBB->template->assign);
		unset($PowerBB->template->PowerBBTemplate);
        unset($forums_cache);
        unset($sectiongroup_cache);
        unset($CALL_SYSTEM);
        /*
		if (phpversion() < '5.0.5')
		{
           @register_shutdown_function($this->cleans());
		}

		$PowerBB->template->assign('memory_usage',memory_get_usage());
		$mem_usage = memory_get_usage();


		if ($mem_usage < 1024)
		echo $mem_usage." bytes";
		elseif ($mem_usage < 1048576)
		echo round($mem_usage/1024,2)." kilobytes";
		else
		echo round($mem_usage/1048576,2)." megabytes";
		unset($PowerBB);

		// shutdown we register this but it might not be used
		if (phpversion() < '5.0.5')
		{
           @register_shutdown_function($this->cleans());
		}
 	     echo'<br />';
		$this->getDebugHtml();
        */
     //$sql_close = $PowerBB->DB->sql_close();
	}

  function getDebugHtml()
	{
	global $PowerBB;

        $pageTime= microtime(true);
		$memoryUsage = memory_get_usage();
		$memoryUsagePeak = memory_get_peak_usage();


			$dbPercent = ($PowerBB->_CONF['temp']['query_numbers'] / $pageTime) * 100;

		$Queries = $PowerBB->_CONF['temp']['query_numbers'];

		$return = "<h1>Page Time: " . number_format($pageTime, 4) . "s</h1>"
			. "<h2>Memory: " . number_format($memoryUsage / 1024 / 1024, 4) . " MB "
			. "(Peak: " . number_format($memoryUsagePeak / 1024 / 1024, 4) . " MB)</h2>"
			. "<h2>Queries (".$Queries.", time: " . number_format($dbPercent, 4) . "s, "
			. number_format($dbPercent, 1) . "%)</h2>"
			. "<h2>Included Files (".$this->includedFiles().")</h2>";

		echo $return;

	}

   function cleans()
   {
	global $PowerBB;

	$arr=get_defined_vars ();
	unset($arr);

   }

   function includedFiles()
   {
	global $PowerBB;
		$includedFiles = get_included_files();
			foreach ($includedFiles as $filename)
			 {
			 	echo $filename."<br>";
			 }

   }

}


?>
