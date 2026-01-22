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
            $deys = ($PowerBB->_CONF['now'] - (30 * 86400));
	        $forum_not = $PowerBB->_CONF['info_row']['last_subject_writer_not_in'];

	        $subject_today_nm = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(*) FROM " . $PowerBB->table['subject'] . " WHERE native_write_time >= " . $deys . " AND section not in (" .$forum_not. ") AND review_subject<>1 AND delete_topic<>1 "));

			$PowerBB->template->assign('subject_today_nm',$subject_today_nm);
        }

       $PowerBB->template->assign('timer',$PowerBB->sys_functions->_time(time()));

        // Get Footer
		$Get_Footer = $PowerBB->template->display('footer');

	    // slurp all enabled feeds from the database
	    $feeds_query = "SELECT * FROM " . $PowerBB->table['feeds'] . "
	                    WHERE options = 1";
	    $feeds_result = $PowerBB->DB->sql_query($feeds_query);
	    $FeedsInfo = $PowerBB->DB->sql_fetch_array($feeds_result);
		if ($FeedsInfo) {

				$cron_interval = 600;
				$last_run = (int)$PowerBB->_CONF['info_row']['rss_feeds_cache'];
				$cron_secret_key = $PowerBB->_CONF['info_row']['extrafields_cache'];
				if (empty($cron_secret_key)) {
				$new_key = $this->Feeder_Setup_Initial_Key();
				exit();
				}

			 if ($PowerBB->_CONF['now'] > $last_run + $cron_interval)
			 {
		        $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['info'] . " SET value='" . $PowerBB->_CONF['now'] . "' WHERE var_name='rss_feeds_cache'");

			    $cron_script_path = 'includes/cron_rss_feeder.php';

			    $ch = curl_init();
			    $cron_url = $PowerBB->functions->GetForumAdress() . $cron_script_path . '?key=' . $cron_secret_key;

			    curl_setopt($ch, CURLOPT_URL, $cron_url);
			    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			    curl_setopt($ch, CURLOPT_TIMEOUT, 1);
			    curl_setopt($ch, CURLOPT_HEADER, 0);
			    curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);

			    curl_exec($ch);
			    curl_close($ch);

			        ///////

		      }
		  }

     $user_group = (int)$PowerBB->_CONF['group_info']['id'];
     $is_admin   = ($PowerBB->_CONF['group_info']['vice'] or $user_group == 1);
     if($is_admin and $PowerBB->_CONF['info_row']['show_debug_info'])
     {
      $PowerBB->functions->ShowDebugInfo();
     }
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
        unset($PowerBB);

         ob_end_flush();

	}

	function Feeder_Generate_Key($length = 32)
	{
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';

	    if (function_exists('random_int')) {
	        for ($i = 0; $i < $length; $i++) {
	            $randomString .= $characters[random_int(0, $charactersLength - 1)];
	        }
	    } else {
	        for ($i = 0; $i < $length; $i++) {
	            $randomString .= $characters[mt_rand(0, $charactersLength - 1)];
	        }
	    }

	    return $randomString;
	}

	function Feeder_Setup_Initial_Key()
	{
	    global $PowerBB;

	    $current_key = $PowerBB->_CONF['info_row']['extrafields_cache'];

	    if (empty($current_key))
	    {
	        $new_key = $this->Feeder_Generate_Key(40);

            $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['info'] . " SET value='" . $new_key . "' WHERE var_name='extrafields_cache'");
	    }
	}

}


?>
