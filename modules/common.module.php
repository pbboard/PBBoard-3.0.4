<?php
(!defined('IN_PowerBB')) ? die() : '';
class PowerBBCommon
{

	var $CheckMember;
    var $Main = array();
	var $Sub = array();
	var $Url;
	var $Type;

	/**
	 * The main function
	 */
	function run()
	{
		global $PowerBB;

  		// Stop any external post request.
  	  if(isset($PowerBB->_SERVER['REQUEST_METHOD']))
       {
         if ($PowerBB->_SERVER['REQUEST_METHOD'] == 'POST')
         {
             $Y = explode('/',$PowerBB->_SERVER['HTTP_REFERER']);
             $X = explode('/',$PowerBB->_SERVER['HTTP_HOST']);

             if ($Y[2] != $X[0])
             {
              exit('No direct script access allowed - المعذرة هذه الطريقة غير شرعية');
             }
             elseif ($Y[2] != $PowerBB->_SERVER['HTTP_HOST'])
             {
              exit('No direct script access allowed - المعذرة هذه الطريقة غير شرعية');
             }
         }
      }
        // Get time zone
		// Force PHP 5.3.0+ to take time zone information from OS
		if (version_compare(phpversion(), '5.3.0', '>='))
		{
  	    if(isset($PowerBB->_CONF['info_row']['timeoffset']))
         {
			if ($PowerBB->_CONF['info_row']['timeoffset'] != '')
			{
			  // Get time zone
			  @date_default_timezone_set($PowerBB->_CONF['info_row']['timeoffset']);
			}

		  }
		}
		$PowerBB->template->assign('_CONF',$PowerBB->_CONF);
		$PowerBB->template->assign('_COOKIE',$PowerBB->_COOKIE);
		$PowerBB->template->assign('_SESSION',$_SESSION);
		$PowerBB->template->assign('admincpdir',$PowerBB->admincpdir);
		$PowerBB->template->assign('DISABLE_HOOKS',$PowerBB->DISABLE_HOOKS);
		$PowerBB->template->assign('_SERVER',$PowerBB->_SERVER);

		$this->_GeneralProc();

        if($PowerBB->_CONF['user_session_login'])
        {
		$this->_CheckSessionMember();
		}
		else
		{		$this->_CheckMember();
		}

		$this->_SetInformation();

		$this->_TemplateAssign();

		$this->_GetForumAdress();

		$this->_ProtectionFunctions();

		$this->_CheckClose();

		//////////
	}


	function _GeneralProc()
	{
		global $PowerBB;

		$PowerBB->template->assign('csrf_key',$_SESSION['csrf']);

			if ($PowerBB->_CONF['info_row']['mor_seconds_online'] == '300')
			{
	 		// Delete not important rows in online table
	 		$CleanOnline = $PowerBB->_CONF['timeout'];
	        $CleanDelOnline = $PowerBB->DB->sql_query("DELETE FROM " . $PowerBB->table['online'] . " WHERE logged <= $CleanOnline ");
	 		}
			else
			{
			// Delete not important rows in online table
		     if ($PowerBB->_CONF['info_row']['mor_seconds_online'])
		      {
	           $time_check_online=$PowerBB->_CONF['now']-$PowerBB->_CONF['info_row']['mor_seconds_online'];
	           $DelOnline = $PowerBB->DB->sql_query("DELETE FROM " . $PowerBB->table['online'] . " WHERE logged <= $time_check_online ");
	          }
			}



         // Delete not important rows in today table
	      if ($PowerBB->_CONF['info_row']['show_online_list_today'] == 1)
	      {
			if ($PowerBB->_CONF['info_row']['mor_hours_online_today'] == '0')
			{
				if ($PowerBB->_CONF['date'] != $PowerBB->_CONF['info_row']['today_date_cache'])
				{

			 		$CleanArr 			= 	array();
			 		$CleanArr['date'] 	= 	$PowerBB->_CONF['date'];
			 	 	$CleanToday = $PowerBB->online->CleanTodayTable($CleanArr);
					if (isset($PowerBB->_COOKIE[$PowerBB->_CONF['today_cookie']]) != $PowerBB->_CONF['info_row']['today_date_cache'])
					{
					 $Update_today_number_cache = $PowerBB->info->UpdateInfo(array('value'=>'1','var_name'=>'today_number_cache'));
                     $truncate_visitor = $PowerBB->DB->sql_query("TRUNCATE " . $PowerBB->table['visitor'] );
					}

			       $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_CONF['date'],'var_name'=>'today_date_cache'));
		 	 	}
	 	 	}
			else
			{
				$mor_online_today = ($PowerBB->_CONF['now'] - ($PowerBB->_CONF['info_row']['mor_hours_online_today'] * 86400));
				$CleanmorToday = $PowerBB->DB->sql_query("DELETE FROM " . $PowerBB->table['today'] . " WHERE logged <= $mor_online_today");
				$NewDate = ($PowerBB->_CONF['now'] - ($PowerBB->_CONF['info_row']['mor_hours_online_today'] * 86400));
				$l_date = @date('j/n/Y',$NewDate);
				$e_date = $PowerBB->_CONF['info_row']['today_date_cache'];
				//if($e_date <= $l_date)
				if ($PowerBB->_CONF['date'] != $PowerBB->_CONF['info_row']['today_date_cache'])
	            {

				    if ($PowerBB->_COOKIE['PowerBB_today_date'] != $PowerBB->_CONF['info_row']['today_date_cache'])
					{
					 $Update_today_number_cache = $PowerBB->info->UpdateInfo(array('value'=>'1','var_name'=>'today_number_cache'));
                     $truncate_visitor = $PowerBB->DB->sql_query("TRUNCATE " . $PowerBB->table['visitor'] );
					}

			        $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_CONF['date'],'var_name'=>'today_date_cache'));
	            }
	 	    }

	      }
		////////////
	}


	function _GetForumAdress()
	{
		global $PowerBB;

		$url = $PowerBB->functions->GetForumAdress();

		$PowerBB->template->assign('url',$url);
	}

	function _CheckMember()
	{
		global $PowerBB;

		////////////
		if ($PowerBB->functions->IsCookie($PowerBB->_CONF['username_cookie'])
			and $PowerBB->functions->IsCookie($PowerBB->_CONF['password_cookie']))
		{
			////////////

			$username = $PowerBB->_COOKIE[$PowerBB->_CONF['username_cookie']];
			$username = $PowerBB->functions->CleanVariable($username,'trim');
			$username = $PowerBB->functions->CleanVariable($username,'html');
			$username = $PowerBB->functions->CleanVariable($username,'sql');
			$password = $PowerBB->_COOKIE[$PowerBB->_CONF['password_cookie']];
           	$page = empty($PowerBB->_GET['page']) ? 'index' : $PowerBB->_GET['page'];
		       if ($page != 'login')
				{
				  if (empty($username))
		          {
		            session_start();
					if (isset($_SESSION['HTTP_USER_AGENT']))
					{
						if ($_SESSION['HTTP_USER_AGENT'] != strtolower(md5($PowerBB->_SERVER['HTTP_USER_AGENT'])))
						{
						 $Logout = $PowerBB->member->Logout();
						 header("Location: index.php");
			         	 exit;
						}
					}
					else
					{
                         $Logout = $PowerBB->member->Logout();
						 header("Location: index.php");
			         	 exit;
				    }
		          }
				}

			// Check if the visitor is member or not ?
 			$MemberArr 			= 	array();
			$MemberArr['get']	= 	'*';

			$MemberArr['where']	=	array();

			$MemberArr['where'][0]				=	array();
			$MemberArr['where'][0]['name']		=	'username';
			$MemberArr['where'][0]['oper']		=	'=';
			$MemberArr['where'][0]['value']		=	$username;

			$MemberArr['where'][1]				=	array();
			$MemberArr['where'][1]['con']		=	'AND';
			$MemberArr['where'][1]['name']		=	'password';
			$MemberArr['where'][1]['oper']		=	'=';
			$MemberArr['where'][1]['value']		=	$password;

			// If the information isn't valid CheckMember's value will be false
			// otherwise the value will be an array
			$this->CheckMember = $PowerBB->core->GetInfo($MemberArr,'member');

			// This is a member :)
			if ($this->CheckMember != false)
			{
				$this->__MemberProcesses();
			}
			// This is visitor
			else
			{
				$this->__VisitorProcesses();
			}
		}
		else
		{
			$this->__VisitorProcesses();
		}

		////////////
	}

	function _CheckSessionMember()
	{
		global $PowerBB;
		////////////
		if ($PowerBB->functions->IsSession($PowerBB->_CONF['username_cookie'])
			and $PowerBB->functions->IsSession($PowerBB->_CONF['password_cookie']))
		{
			////////////

			$username = $_SESSION[$PowerBB->_CONF['username_cookie']];
			$username = $PowerBB->functions->CleanVariable($username,'trim');
			$username = $PowerBB->functions->CleanVariable($username,'html');
			$username = $PowerBB->functions->CleanVariable($username,'sql');
			$password = $_SESSION[$PowerBB->_CONF['password_cookie']];
           	$page = empty($PowerBB->_GET['page']) ? 'index' : $PowerBB->_GET['page'];
		       if ($page != 'login')
				{
				  if (empty($username))
		          {

					if (isset($_SESSION['HTTP_USER_AGENT']))
					{
						if ($_SESSION['HTTP_USER_AGENT'] != strtolower(md5($PowerBB->_SERVER['HTTP_USER_AGENT'])))
						{

							unset($_SESSION[$PowerBB->_CONF['username_cookie']]);
							unset($_SESSION[$PowerBB->_CONF['password_cookie']]);
							unset($_SESSION['expire']);

						 header("Location: index.php");
			         	 exit;
						}
					}
					else
					{
							unset($_SESSION[$PowerBB->_CONF['username_cookie']]);
							unset($_SESSION[$PowerBB->_CONF['password_cookie']]);
							unset($_SESSION['expire']);

						 header("Location: index.php");
			         	 exit;
				    }
		          }
				}

			// Check if the visitor is member or not ?
 			$MemberArr 			= 	array();
			$MemberArr['get']	= 	'*';

			$MemberArr['where']	=	array();

			$MemberArr['where'][0]				=	array();
			$MemberArr['where'][0]['name']		=	'username';
			$MemberArr['where'][0]['oper']		=	'=';
			$MemberArr['where'][0]['value']		=	$username;

			$MemberArr['where'][1]				=	array();
			$MemberArr['where'][1]['con']		=	'AND';
			$MemberArr['where'][1]['name']		=	'password';
			$MemberArr['where'][1]['oper']		=	'=';
			$MemberArr['where'][1]['value']		=	$password;

			// If the information isn't valid CheckMember's value will be false
			// otherwise the value will be an array
			$this->CheckMember = $PowerBB->core->GetInfo($MemberArr,'member');

			// This is a member :)
			if ($this->CheckMember != false)
			{
				$this->__MemberProcesses();
			}
			// This is visitor
			else
			{
				$this->__VisitorProcesses();
			}
		}
		else
		{
			$this->__VisitorProcesses();
		}

		////////////
	}

	/**
	 * If the Guest is member , call this function
	 */
	function __MemberProcesses()
	{
		global $PowerBB;

		$PowerBB->_CONF['rows']['member_row'] 	= 	$this->CheckMember;
		$PowerBB->_CONF['member_permission']	 	= 	true;

		////////////

		// I hate SQL injections
		//$PowerBB->functions->CleanVariable($PowerBB->_CONF['rows']['member_row'],'sql');

		// I hate XSS
		$PowerBB->functions->CleanVariable($PowerBB->_CONF['rows']['member_row'],'html');

		////////////

		// alias name
		// TODO : Delete this line, it's get size from memory!
		$PowerBB->_CONF['member_row'] = $PowerBB->_CONF['rows']['member_row'];

		////////////

		if (in_array($PowerBB->_CONF['member_row']['id'], preg_split('#\s*,\s*#s', $PowerBB->superadministrators, -1, PREG_SPLIT_NO_EMPTY)))
		{
		  $PowerBB->template->assign('superadministrators','1');
		}

       // select all warninged users who are due to have their ban warn_liftdate
       if ($PowerBB->_CONF['member_row']['warnings'] > 0)
       {
			 $TIMENOW = date('d-m-Y', $PowerBB->_CONF['now']);
			 $Getwarnusers = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['warnlog'] . " WHERE warn_liftdate = '$TIMENOW'");
	        while ($Getusers = $PowerBB->DB->sql_fetch_array($Getwarnusers))
	          {
				$MemArr = array();
				$MemArr['where'] = array('username',$Getusers['warn_to']);
				$PowerBB->_CONF['member_row'] = $PowerBB->core->GetInfo($MemArr,'member');

				$StartArr['field']['warnings'] 	= 	$PowerBB->_CONF['member_row']['warnings']-1;
				if($PowerBB->_CONF['member_row']['usergroup'] == '6'){
				$StartArr['field']['usergroup'] 	= 	'4';
				}
				$StartArr['where'] =	array('username',$Getusers['warn_to']);
				$Warn = $PowerBB->core->Update($StartArr,'member');
	        }

	        $warnusers = $PowerBB->DB->sql_query("DELETE FROM " . $PowerBB->table['warnlog'] . " WHERE warn_liftdate = '$TIMENOW' ");
       }
 		////////////

		// Get the member's group info and store it in _CONF['group_info']

			if (defined('STOP_STYLE'))
			{
				$GrpArr 			= 	array();
				$GrpArr['where'] 	= 	array('id',$PowerBB->_CONF['member_row']['usergroup']);
				$PowerBB->_CONF['rows']['group_info'] = $PowerBB->core->GetInfo($GrpArr,'group');
			}
			else
			{
			  $group_info = $PowerBB->functions->get_cache_permissions_group_id_numbr($PowerBB->_CONF['member_row']['usergroup']);
			  $PowerBB->_CONF['rows']['group_info'] = $group_info;
			}


		// alias name
		// TODO : Delete this line, it's get size from memory!
		$PowerBB->_CONF['group_info'] = $PowerBB->_CONF['rows']['group_info'];

		////////////

		// Check if the member is already online
    	$IsOnlineInfo 				= 	array();
		$IsOnlineInfo['where'] 	= 	array('user_id',$PowerBB->_CONF['member_row']['id']);

		$IsOnline = $PowerBB->core->Is($IsOnlineInfo,'online');
				////////////

		// Where is the member now ?
		$MemberLocation = $PowerBB->_CONF['template']['_CONF']['lang']['home_Page'];

		$page = empty($PowerBB->_GET['page']) ? 'index' : $PowerBB->_GET['page'];

		$locations 					= 	array();
		$locations['index'] 		= 	$PowerBB->_CONF['template']['_CONF']['lang']['home_Page'];
		$locations['forum'] 		= 	$PowerBB->_CONF['template']['_CONF']['lang']['Seen_the_forum'];
		$locations['profile'] 		= 	$PowerBB->_CONF['template']['_CONF']['lang']['Seen_the_member'];
		$locations['static'] 		= 	$PowerBB->_CONF['template']['_CONF']['lang']['Seen_the_Statistics'];
		$locations['member_list'] 	= 	$PowerBB->_CONF['template']['_CONF']['lang']['Seen_the_members_list'];
		$locations['search'] 		= 	$PowerBB->_CONF['template']['_CONF']['lang']['Seen_the_search_page'];
		$locations['announcement'] 	= 	$PowerBB->_CONF['template']['_CONF']['lang']['Seen_the_announcemen'];
		$locations['team'] 			= 	$PowerBB->_CONF['template']['_CONF']['lang']['Seen_the_teams'];
		$locations['login'] 		= 	$PowerBB->_CONF['template']['_CONF']['lang']['He_Login'];
		$locations['logout'] 		= 	$PowerBB->_CONF['template']['_CONF']['lang']['He_logout'];
		$locations['usercp'] 		= 	$PowerBB->_CONF['template']['_CONF']['lang']['Seen_the_usercp'];
		$locations['pm'] 			= 	$PowerBB->_CONF['template']['_CONF']['lang']['Seen_the_pm'];
		$locations['topic'] 		= 	$PowerBB->_CONF['template']['_CONF']['lang']['Seen_the_topic'];
		$locations['new_topic'] 	= 	$PowerBB->_CONF['template']['_CONF']['lang']['Seen_the_new_topic'];
		$locations['new_reply'] 	= 	$PowerBB->_CONF['template']['_CONF']['lang']['Seen_the_new_reply'];
		$locations['vote'] 			= 	$PowerBB->_CONF['template']['_CONF']['lang']['Seen_the_vote'];
		$locations['tags'] 			= 	$PowerBB->_CONF['template']['_CONF']['lang']['Seen_the_tags'];
		$locations['online'] 		= 	$PowerBB->_CONF['template']['_CONF']['lang']['Seen_the_online'];
		$locations['chat_message'] 	= 	$PowerBB->_CONF['template']['_CONF']['lang']['chat_message'];

		if (array_key_exists($page,$locations))
		{
			$MemberLocation = $locations[$page];
		}
        else
		{
			$MemberLocation = $PowerBB->_CONF['template']['_CONF']['lang']['home_Page'];
		}
         $PowerBB->template->assign('Location',$MemberLocation);

		// Get username with group style
		$username_style = $PowerBB->_CONF['member_row']['username_style_cache'];


		////////////
		// Member don't exists in online table , so we insert member info
		if (!$IsOnline)
		{
			$InsertOnline 			= 	array();
			$InsertOnline['field'] 	= 	array();

			$InsertOnline['field']['username'] 			= 	$PowerBB->_CONF['member_row']['username'];
			$InsertOnline['field']['username_style'] 	= 	$username_style;
			$InsertOnline['field']['logged'] 			= 	$PowerBB->_CONF['now'];
			$InsertOnline['field']['path'] 				= 	addslashes($PowerBB->_SERVER['QUERY_STRING']);
			$InsertOnline['field']['user_ip'] 			= 	$PowerBB->_CONF['ip'];
			$InsertOnline['field']['hide_browse'] 		= 	$PowerBB->_CONF['member_row']['hide_online'];
			$InsertOnline['field']['user_location'] 	= 	$MemberLocation;
			$InsertOnline['field']['user_id'] 			= 	$PowerBB->_CONF['member_row']['id'];

			$insert = $PowerBB->core->Insert($InsertOnline,'online');
		}
		// Member is already online , just update information
		else
		{

			if ($IsOnline['logged'] < $PowerBB->_CONF['timeout']
				or $IsOnline['path'] != $PowerBB->_SERVER['QUERY_STRING']
				or $IsOnline['username_style'] != $username_style
				or $IsOnline['hide_browse'] != $PowerBB->_CONF['rows']['member_row']['hide_online'])
			{
				$UpdateOnline 					= 	array();
				$UpdateOnline['field']			=	array();

				$UpdateOnline['field']['username'] 			= 	$PowerBB->_CONF['member_row']['username'];
				$UpdateOnline['field']['username_style'] 	= 	$username_style;
				$UpdateOnline['field']['logged']			=	$PowerBB->_CONF['now'];
				$UpdateOnline['field']['path']				=	addslashes($PowerBB->_SERVER['QUERY_STRING']);
				$UpdateOnline['field']['user_ip']			=	$PowerBB->_CONF['ip'];
				$UpdateOnline['field']['hide_browse']		=	$PowerBB->_CONF['member_row']['hide_online'];
				$UpdateOnline['field']['user_location']		=	$MemberLocation;
				$UpdateOnline['field']['user_id']			=	$PowerBB->_CONF['member_row']['id'];
				$UpdateOnline['where']						=	array('username',$PowerBB->_CONF['member_row']['username']);

				$update = $PowerBB->core->Update($UpdateOnline,'online');
			}
		}

		////////////
     if ($PowerBB->_CONF['info_row']['show_online_list_today'] == 1)
     {

		// Ok , now we check if this member is exists in today list
		$onlineTodayArr 			= 	array();
		$onlineTodayArr['where'] 	= 	array('user_id',$PowerBB->_CONF['member_row']['id']);

		$TodayInfo = $PowerBB->online->GetTodayInfo($onlineTodayArr);

       if (!$TodayInfo
       and $PowerBB->_CONF['member_row']['id'])
       {

            // Member isn't exists in today table , so insert the member
			////////////
			$InsertTodayArr 			= 	array();
			$InsertTodayArr['field']	=	array();

			$InsertTodayArr['field']['username'] 		= 	$PowerBB->_CONF['member_row']['username'];
			$InsertTodayArr['field']['user_id'] 		= 	$PowerBB->_CONF['member_row']['id'];
			$InsertTodayArr['field']['user_date'] 		= 	$PowerBB->_CONF['date'];
			$InsertTodayArr['field']['logged'] 		    = 	$PowerBB->_CONF['now'];
			$InsertTodayArr['field']['hide_browse'] 	= 	$PowerBB->_CONF['member_row']['hide_online'];
			$InsertTodayArr['field']['username_style'] 	= 	$username_style;

			$InsertToday = $PowerBB->core->Insert($InsertTodayArr,'today');

			////////////

			if ($InsertToday)
			{
				////////////

				$UpdateArr 				= 	array();
				$UpdateArr['field']		=	array();

				$UpdateArr['field']['visitor'] 	= 	$PowerBB->_CONF['member_row']['visitor'] + 1;
				$UpdateArr['where'] 			= 	array('id',$PowerBB->_CONF['member_row']['id']);

				$PowerBB->core->Update($UpdateArr,'member');

				////////////
			}
			////////////
		}

	 }	////////////


		// Can't find last visit cookie , so register it
		if (!$PowerBB->functions->IsCookie('PowerBB_lastvisit'))
		{
			$CookieArr 					= 	array();
			$CookieArr['last_visit'] 	= 	(empty($PowerBB->_CONF['member_row']['lastvisit'])) ? $PowerBB->_CONF['now'] or  $PowerBB->_CONF['date']: $PowerBB->_CONF['member_row']['lastvisit'];
			$CookieArr['date'] 			= 	$PowerBB->_CONF['now'];
			$CookieArr['id'] 			= 	$PowerBB->_CONF['member_row']['id'];

			$PowerBB->member->LastVisitCookie($CookieArr);
		}

		if ($PowerBB->_CONF['member_row']['lastvisit'] =='')
		{
			$CookieArr 					= 	array();
			$CookieArr['last_visit'] 	= 	(empty($PowerBB->_CONF['member_row']['lastvisit'])) ? $PowerBB->_CONF['now'] or  $PowerBB->_CONF['date']: $PowerBB->_CONF['member_row']['lastvisit'];
			$CookieArr['date'] 			= 	$PowerBB->_CONF['now'];
			$CookieArr['id'] 			= 	$PowerBB->_CONF['member_row']['id'];

			$PowerBB->member->LastVisitCookie($CookieArr);
		}



		////////////

		// Get member style
			////////////
           $style_id = (!empty($PowerBB->_COOKIE[$PowerBB->_CONF['style_cookie']])) ? $PowerBB->_COOKIE[$PowerBB->_CONF['style_cookie']] : $PowerBB->_CONF['info_row']['def_style'];
          $PowerBB->template->assign('_CONF_style_id',$style_id);
			$GetStyleArr 			= 	array();
			$GetStyleArr['where']	=	array('id',$style_id);

			$PowerBB->_CONF['rows']['style'] = $PowerBB->core->GetInfo($GetStyleArr,'style');
			if (!$PowerBB->_CONF['rows']['style'])
			{
				$style_id = $PowerBB->_CONF['info_row']['def_style'];

			}
			////////////
			$style_cache = $PowerBB->style->CreateStyleCache(array('where'=>array('id',$style_id)));
			////////////

			$UpdateArr						=	array();
			$UpdateArr['field']				=	array();

			$UpdateArr['field']['style_cache'] 		= 	$style_cache;
			$UpdateArr['field']['style_id_cache']	=	$style_id;
			$UpdateArr['where']						=	array('id',$style_id);

			if ($PowerBB->_CONF['member_row']['should_update_style_cache'])
			{
				$UpdateArr['field']['should_update_style_cache'] = 0;
			}

			$update_cache = $PowerBB->core->Update($UpdateArr,'member');

			////////////

		////////////

		if ($PowerBB->_CONF['member_row']['logged'] < $PowerBB->_CONF['timeout'])
		{
			$LoggedArr 				= 	array();
			$LoggedArr['field'] 	= 	array();

			$LoggedArr['field']['logged'] 		= 	$PowerBB->_CONF['now'];
			$LoggedArr['field']['member_ip'] 	= 	$PowerBB->_CONF['ip'];
			$LoggedArr['where']					=	array('id',$PowerBB->_CONF['member_row']['id']);

			$PowerBB->core->Update($LoggedArr,'member');

			$UpdateOnline 					= 	array();
			$UpdateOnline['field']			=	array();
			$UpdateOnline['field']['last_move'] = $PowerBB->_CONF['now'];
			$UpdateOnline['where']                    =    array('username',$PowerBB->_CONF['member_row']['username']);
	        $update = $PowerBB->core->Update($UpdateOnline,'online');
		}

       	$PowerBB->_CONF['rows']['style']['image_path']= str_replace('../','',$PowerBB->_CONF['rows']['style']['image_path']);
		$PowerBB->_CONF['rows']['style']['style_path']= str_replace('../','',$PowerBB->_CONF['rows']['style']['style_path']);

            $PowerBB->template->assign('LangDir',$PowerBB->_CONF['LangDir']);

			if ($PowerBB->_CONF['LangDir'] == 'ltr')
			{
				  $style_path= $PowerBB->_CONF['rows']['style']['style_path'];
				  $style_path = str_replace('style.css','style_ltr.css',$style_path);
				 if (file_exists($style_path))
				  {
				   $PowerBB->_CONF['rows']['style']['style_path'] = $style_path;
				  }
				  $PowerBB->template->assign('align','left');
				  $PowerBB->_CONF['info_row']['content_dir'] = 'ltr';
				  $PowerBB->_CONF['info_row']['content_language'] = 'en';
			}
			else
			{				$PowerBB->template->assign('align','right');
				$PowerBB->_CONF['info_row']['content_dir'] = 'rtl';
				$PowerBB->_CONF['info_row']['content_language'] = 'ar';
			}


		$PowerBB->template->assign('image_path',$PowerBB->_CONF['rows']['style']['image_path']);

		//Set no caching to css file add modification time in end url
        $Gets_file_style_path = $PowerBB->_CONF['rows']['style']['style_path'];
		if(file_exists($Gets_file_style_path))
		{
		$Gets_file_modification_time = filemtime($PowerBB->_CONF['rows']['style']['style_path']);
		}
        if($Gets_file_modification_time)
        {
        $PowerBB->_CONF['rows']['style']['style_path'] = $PowerBB->_CONF['rows']['style']['style_path']."?v=".$Gets_file_modification_time;
        }
		$PowerBB->template->assign('style_path',$PowerBB->_CONF['rows']['style']['style_path']);

		if (empty($PowerBB->_CONF['member_row']['avater_path']))
		{
		 $avater_path = $PowerBB->_CONF['template']['image_path'].'/'.$PowerBB->_CONF['info_row']['default_avatar'];
		 $PowerBB->template->assign('avater_change','1');

		}
        else
		{
		 $avater_path = $PowerBB->_CONF['member_row']['avater_path'];
		}
		$PowerBB->template->assign('avater_path',$avater_path);

	}

	/**
	 * If the visitor isn't member, call this function
	 */
	function __VisitorProcesses()
	{
		global $PowerBB;

		$PowerBB->_CONF['member_permission'] = false;

		// Get the visitor's group info and store it in _CONF['group_info']

		if (defined('STOP_STYLE'))
		{
			$GrpArr 			= 	array();
			$GrpArr['where'] 	= 	array('id','7');
			$PowerBB->_CONF['group_info'] = $PowerBB->core->GetInfo($GrpArr,'group');
		}
		else
		{
		$group_info = $PowerBB->functions->get_cache_permissions_group_id_numbr(7);
		$PowerBB->_CONF['group_info'] = $group_info;
		}

		// Get username with group style
	   if (isset($PowerBB->_CONF['member_row']['username_style_cache']))
		{
		$username_style = $PowerBB->_CONF['member_row']['username_style_cache'];
        }


		// Where is the member now ?
		$GuestLocation = $PowerBB->_CONF['template']['_CONF']['lang']['home_Page'];

		$page = empty($PowerBB->_GET['page']) ? 'index' : $PowerBB->_GET['page'];
		$locations 					= 	array();
		$locations['index'] 		= 	$PowerBB->_CONF['template']['_CONF']['lang']['home_Page'];
		$locations['forum'] 		= 	$PowerBB->_CONF['template']['_CONF']['lang']['Seen_the_forum'];
		$locations['profile'] 		= 	$PowerBB->_CONF['template']['_CONF']['lang']['Seen_the_member'];
		$locations['static'] 		= 	$PowerBB->_CONF['template']['_CONF']['lang']['Seen_the_Statistics'];
		$locations['member_list'] 	= 	$PowerBB->_CONF['template']['_CONF']['lang']['Seen_the_members_list'];
		$locations['search'] 		= 	$PowerBB->_CONF['template']['_CONF']['lang']['Seen_the_search_page'];
		$locations['announcement'] 	= 	$PowerBB->_CONF['template']['_CONF']['lang']['Seen_the_announcemen'];
		$locations['team'] 			= 	$PowerBB->_CONF['template']['_CONF']['lang']['Seen_the_teams'];
		$locations['login'] 		= 	$PowerBB->_CONF['template']['_CONF']['lang']['He_Login'];
		$locations['topic'] 		= 	$PowerBB->_CONF['template']['_CONF']['lang']['Seen_the_topic'];
		$locations['vote'] 			= 	$PowerBB->_CONF['template']['_CONF']['lang']['Seen_the_vote'];
		$locations['tags'] 			= 	$PowerBB->_CONF['template']['_CONF']['lang']['Seen_the_tags'];
		$locations['online'] 		= 	$PowerBB->_CONF['template']['_CONF']['lang']['Seen_the_online'];
		$locations['chat_message'] 	= 	$PowerBB->_CONF['template']['_CONF']['lang']['chat_message'];

		if (array_key_exists($page,$locations))
		{
			$GuestLocation = $locations[$page];
		}
        else
		{
			$GuestLocation = $PowerBB->_CONF['template']['_CONF']['lang']['home_Page'];
		}

		$OnlineArr = array();
		$OnlineArr['where'] = array('user_ip',$PowerBB->_CONF['ip']);

		$OnlineInfo = $PowerBB->core->GetInfo($OnlineArr,'online');

		// Check if the visitor is already online
       	$IsGuestOnline = $OnlineInfo;

		if (!$IsGuestOnline)
		{
       	$isBot = $PowerBB->functions->is_bot();
        $BotName = $PowerBB->functions->bot_name();

			$InsertOnlineArr 			= 	array();
			$InsertOnlineArr['field'] 	= 	array();

			$InsertOnlineArr['field']['username'] 			= 	'Guest';
			$InsertOnlineArr['field']['username_style'] 	= 	'Guest';
			$InsertOnlineArr['field']['logged'] 			= 	$PowerBB->_CONF['now'];
			$InsertOnlineArr['field']['path'] 				= 	addslashes($PowerBB->_SERVER['QUERY_STRING']);
			$InsertOnlineArr['field']['user_ip'] 			= 	$PowerBB->_CONF['ip'];
			$InsertOnlineArr['field']['user_location'] 	    = 	$GuestLocation;
			$InsertOnlineArr['field']['user_id']			=	-1;
            $InsertOnlineArr['field']['last_move']          = $PowerBB->_CONF['now'];
            $InsertOnlineArr['field']['is_bot']             = $isBot;
            $InsertOnlineArr['field']['bot_name']           = $BotName;

			$insert = $PowerBB->core->Insert($InsertOnlineArr,'online');

		}
		else
		{

		   // visitor already online , just update information


			if ($OnlineInfo)
			{
				if ($OnlineInfo['user_id']=='-1')
				{
					$UpdateOnlineArr 					= 	array();
					$UpdateOnlineArr['field']			=	array();

					$UpdateOnlineArr['field']['path']				=	addslashes($PowerBB->_SERVER['QUERY_STRING']);
					$UpdateOnlineArr['field']['username'] 	= 	'Guest';
					$UpdateOnlineArr['field']['username_style'] 	= 	'Guest';
					$UpdateOnlineArr['field']['user_location']		=	$GuestLocation;
					$UpdateOnlineArr['field']['subject_show']		=	$subject_show;
					$UpdateOnlineArr['field']['subject_id']		    =	$subject_id;
					$UpdateOnlineArr['field']['last_move']          =   $PowerBB->_CONF['now'];
					$UpdateOnlineArr['where']						=	array('id',$OnlineInfo['id']);

				   $update = $PowerBB->core->Update($UpdateOnlineArr,'online');
              }
			}

		}

         if ($PowerBB->_CONF['info_row']['show_online_list_today'] == 1)
	      {
			//Insert ip Visitor i use that teble to register Today Visitor ip
			$VisitorArr 			= 	array();
			$VisitorArr['where'] 	= 	array('ip',$PowerBB->_CONF['ip']);

			$InfoVisitorArr = $PowerBB->core->GetInfo($VisitorArr,'visitor');
			if (!$InfoVisitorArr)
			{
			$VisitorIpArr 				= 	array();
			$VisitorIpArr['field']		=	array();

			$VisitorIpArr['field']['ip'] 		= 	$PowerBB->_CONF['ip'];
			// i but number 302 to check if that num is Existing or truncate visitor table
			$VisitorIpArr['field']['lang_id'] 	= 	302;

			$InsertBan = $PowerBB->core->Insert($VisitorIpArr,'visitor');
			}
           }
		// Get visitor's style
		$style_id = (!empty($PowerBB->_COOKIE[$PowerBB->_CONF['style_cookie']])) ? $PowerBB->_COOKIE[$PowerBB->_CONF['style_cookie']] : $PowerBB->_CONF['info_row']['def_style'];
		$style_id = $PowerBB->functions->CleanVariable($style_id,'intval');
		$PowerBB->template->assign('_CONF_style_id',$style_id);

		$StyleArr 			= 	array();
		$StyleArr['where'] 	= 	array('id',$style_id);

		$PowerBB->_CONF['rows']['style'] = $PowerBB->core->GetInfo($StyleArr,'style');
		if (!$PowerBB->_CONF['rows']['style'])
		{
		$style_id = $PowerBB->_CONF['info_row']['def_style'];
		$StyleArr 			= 	array();
		$StyleArr['where'] 	= 	array('id',$style_id);

		$PowerBB->_CONF['rows']['style'] = $PowerBB->core->GetInfo($StyleArr,'style');
		}

      	$PowerBB->_CONF['rows']['style']['image_path']= str_replace('../','',$PowerBB->_CONF['rows']['style']['image_path']);
		$PowerBB->_CONF['rows']['style']['style_path']= str_replace('../','',$PowerBB->_CONF['rows']['style']['style_path']);

				$PowerBB->template->assign('LangDir',$PowerBB->_CONF['LangDir']);
			if ($PowerBB->_CONF['LangDir'] == 'ltr')
			{
				  $style_path= $PowerBB->_CONF['rows']['style']['style_path'];
				  $style_path = str_replace('style.css','style_ltr.css',$style_path);
				 if (file_exists($style_path))
				  {
				   $PowerBB->_CONF['rows']['style']['style_path'] = $style_path;
				  }
				  $PowerBB->template->assign('align','left');
				  $PowerBB->_CONF['info_row']['content_dir'] = 'ltr';
				  $PowerBB->_CONF['info_row']['content_language'] = 'en';
			}
			else
			{
				$PowerBB->template->assign('align','right');
				$PowerBB->_CONF['info_row']['content_dir'] = 'rtl';
				$PowerBB->_CONF['info_row']['content_language'] = 'ar';
			}

		//Set no caching to css file add modification time in end url
        $Gets_file_style_path = $PowerBB->_CONF['rows']['style']['style_path'];
		if(file_exists($Gets_file_style_path))
		{
		$Gets_file_modification_time = filemtime($PowerBB->_CONF['rows']['style']['style_path']);
		}
        if($Gets_file_modification_time)
        {
        $PowerBB->_CONF['rows']['style']['style_path'] = $PowerBB->_CONF['rows']['style']['style_path']."?v=".$Gets_file_modification_time;
        }

        $PowerBB->template->assign('style_path',$PowerBB->_CONF['rows']['style']['style_path']);
		$PowerBB->template->assign('image_path',$PowerBB->_CONF['rows']['style']['image_path']);

	}

	function _SetInformation()
	{
		global $PowerBB;

		if (!isset($PowerBB->_CONF['rows']['style'])
			or !is_array($PowerBB->_CONF['rows']['style']))
		{
			$PowerBB->functions->ShowHeader($PowerBB->_CONF['template']['_CONF']['lang']['sorry_visitor_you_cant_visit_this_forum_today']);
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Not_Was_style_Information']);

		}

	}



	/**
	 * Close the forums
	 */
	function _CheckClose()
	{
		global $PowerBB;
     $page = empty($PowerBB->_GET['page']) ? 'index' : $PowerBB->_GET['page'];
		// This member is banned :/
		if (!defined('CLASS_NAME') == 'PowerBBLogoutMOD')
		{
			if ($PowerBB->_CONF['group_info']['banned'] == 1)
			{
				// Stop the page with small massege
    	        $PowerBB->_CONF['info_row']['sidebar_list_active'] = 0;
    	        $PowerBB->_CONF['template']['_CONF']['info_row']['sidebar_list_active']= 0;
				$PowerBB->template->assign('_CONF',$PowerBB->_CONF);
	        	$PowerBB->template->assign('image_path',$PowerBB->_CONF['rows']['style']['image_path']);
				$PowerBB->functions->ShowHeader($PowerBB->_CONF['template']['_CONF']['lang']['Can_you_enter_the_Forum']);
	            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['suspended_member']);
	        }
        }

       //  Stop ipaddress banned
		$BanInfoArr 							= 	array();
        $BanInfoArr['where'] 		    	= 	array('ip',$PowerBB->_CONF['ip']);

		$IsBanned = $PowerBB->core->GetInfo($BanInfoArr,'banned');

		// if the forum close by admin , stop the page
       if ($PowerBB->_CONF['info_row']['board_close'])
    	{

    	     $PowerBB->_CONF['info_row']['sidebar_list_active'] = 0;
    	     $PowerBB->_CONF['template']['_CONF']['info_row']['sidebar_list_active']= 0;
  			if ($PowerBB->_CONF['group_info']['admincp_allow'] != 1
  				and !defined('LOGIN'))
        	{

        		// If the PowerCode is allow , use it
      	       $PowerBB->_CONF['info_row']['board_msg']= str_replace('../look/','look/',$PowerBB->_CONF['info_row']['board_msg']);
				$PowerBB->_CONF['info_row']['board_msg'] = $PowerBB->Powerparse->replace($PowerBB->_CONF['info_row']['board_msg']);

				// Convert the smiles to image
        		$PowerBB->template->assign('_CONF',$PowerBB->_CONF);
        		$PowerBB->template->assign('image_path',$PowerBB->_CONF['rows']['style']['image_path']);
				$PowerBB->_CONF['template']['_CONF']['lang']['copyright'] = $PowerBB->functions->copyright();
				$PowerBB->_CONF['template']['_CONF']['lang']['copyright_archive'] = $PowerBB->functions->copyright();

        		$PowerBB->functions->ShowHeader();
    			$PowerBB->functions->error($PowerBB->_CONF['info_row']['board_msg']);
  			}

 		}
		elseif ($IsBanned)
		{
            if($page !='logout')
		    {
				$PowerBB->_CONF['info_row']['sidebar_list_active'] = 0;
				$PowerBB->_CONF['template']['_CONF']['info_row']['sidebar_list_active']= 0;
				$stop = ($PowerBB->_CONF['ip'] and !$PowerBB->_CONF['ip']) ? false : true;
				$PowerBB->functions->ShowHeader($PowerBB->_CONF['template']['_CONF']['lang']['ban_IP']);
				$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['ban_IP'].'<br />'.$PowerBB->_CONF['template']['_CONF']['lang']['reason_ban'].' '.$IsBanned['reason'],$stop,$stop);
				$PowerBB->functions->GetFooter();
				exit();
			}
		}
		elseif($PowerBB->_CONF['member_row']['usergroup'] == '6')
		{
		    if($page !='logout')
		    {
				$PowerBB->_CONF['info_row']['sidebar_list_active'] = 0;
				$PowerBB->_CONF['template']['_CONF']['info_row']['sidebar_list_active']= 0;
				$stop = ($PowerBB->_CONF['ip'] and !$PowerBB->_CONF['ip']) ? false : true;
				$PowerBB->functions->ShowHeader($PowerBB->_CONF['template']['_CONF']['lang']['suspended_member']);
				$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['suspended_member'].'<br />'.$PowerBB->_CONF['template']['_CONF']['lang']['ban_IP'],$stop,$stop);
				$PowerBB->functions->GetFooter();
				exit();
			}
		}




	}

// Protect the forums from script kiddie and crackers
	function _ProtectionFunctions()
	{
		global $PowerBB;

	 if (isset($PowerBB->_GET))
	 {
		//////////
		// Check if $_GET don't value any HTML or Javascript codes
			foreach ($PowerBB->_GET as $var_name => $value)
			{
				if ($value !='')
				{
		       	     $PowerBB->_GET[$var_name] = htmlspecialchars($PowerBB->_GET[$var_name]);
		       	     $var_name = htmlspecialchars($var_name);
		       	     $value = htmlspecialchars($value);
				}
			}

      }
 		//////////
	}

	/**
	 * Assign the important variables for template
	 */
	function _TemplateAssign()
	{
		global $PowerBB;

		if ($PowerBB->_CONF['group_info']['admincp_allow']
		or $PowerBB->_CONF['group_info']['vice'])
		{
			$PowerBB->template->assign('admin_mod_toolbar',0);
		}
		else
		{
			$PowerBB->template->assign('admin_mod_toolbar',1);
		}
       		$PowerBB->_CONF['member_row'] = $this->CheckMember;

		$PowerBB->template->assign('_CONF',$PowerBB->_CONF);
		$PowerBB->template->assign('_COOKIE',$PowerBB->_COOKIE);
		$PowerBB->template->assign('_SESSION',$_SESSION);
		$PowerBB->template->assign('admincpdir',$PowerBB->admincpdir);
		$PowerBB->template->assign('_SERVER',$PowerBB->_SERVER);

		if (in_array($PowerBB->_CONF['member_row']['id'], preg_split('#\s*,\s*#s', $PowerBB->superadministrators, -1, PREG_SPLIT_NO_EMPTY)))
		{
		  $PowerBB->template->assign('superadministrators','1');
		}

 		if ($PowerBB->_CONF['info_row']['active_subject_today'])
		{
		      /**
		   * Ok , are you ready to get subject today nm ? :)
		   */
			$day 	= 	@date('j');
			$month 	= 	@date('n');
			$year 	= 	@date('Y');

			$from 	= 	@mktime(0,0,0,$month,$day,$year);
			$to 	= 	@mktime(23,59,59,$month,$day,$year);

		     $forum_not = $PowerBB->_CONF['info_row']['last_subject_writer_not_in'];
		     $subject_today_nm = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(1),id FROM " . $PowerBB->table['subject'] . " WHERE native_write_time BETWEEN " . $from . " AND " . $to . " AND section not in (" .$forum_not. ") AND review_subject<>1 AND delete_topic<>1 LIMIT 1"));
			 $PowerBB->template->assign('subject_today_nm',$subject_today_nm);

		}
			/**
		 * Get subject special nm
		 */
         $subject_special_nm = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(1),id FROM " . $PowerBB->table['subject'] . " WHERE special='1' LIMIT 1"));
		 $PowerBB->template->assign('subject_special_nm',$subject_special_nm);
			/**
		 * Get last posts bar
		 */

     if ($PowerBB->_CONF['info_row']['activate_lasts_posts_bar'] == 1)
	 {

		$LastPostsArr 							= 	array();

		// Order data
		$LastPostsArr['order'] 				= 	array();
		$LastPostsArr['order']['field'] 	= 	'write_time';
		$LastPostsArr['order']['type'] 		= 	'DESC';

		// Ten rows only
		$LastPostsArr['limit']				=	$PowerBB->_CONF['info_row']['lasts_posts_bar_num'];

        $LastPostsArr['where'][1] 			= 	array();
		$LastPostsArr['where'][1]['con']		=	'AND';
		$LastPostsArr['where'][1]['name'] 	= 	'review_subject<>1 AND sec_subject<>1 AND delete_topic';
		$LastPostsArr['where'][1]['oper'] 	= 	'<>';
		$LastPostsArr['where'][1]['value'] 	= 	'1';


		// Clean data
		$LastPostsArr['proc'] 				= 	array();
		$LastPostsArr['proc']['*'] 			= 	array('method'=>'clean','param'=>'html');
        $LastPostsArr['proc']['native_write_time'] 	= 	array('method'=>'date','store'=>'write_date','type'=>$PowerBB->_CONF['info_row']['timesystem']);
		$LastPostsArr['proc']['write_time'] 			= 	array('method'=>'date','store'=>'reply_date','type'=>$PowerBB->_CONF['info_row']['timesystem']);

        $PowerBB->_CONF['template']['while']['LastsPosts'] = $PowerBB->core->GetList($LastPostsArr,'subject');

        	if ($PowerBB->_CONF['template']['while']['LastsPosts'] != false)
			{
				$PowerBB->template->assign('LastsPosts_Show',true);

			}
			else
			{
				$PowerBB->template->assign('LastsPosts_Show',false);
			}
        }

		// time
        $timestamp= $PowerBB->_CONF['info_row']['timesystem'];
        $timest = $PowerBB->functions->_time($timestamp);
		$PowerBB->template->assign('timer',$timest);

     if ($PowerBB->_CONF['info_row']['show_ads'] == 1)
		{
		// show ads
		 $AdsArr = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['ads'] . " ORDER BY RAND() limit 1");
         $PowerBB->_CONF['template']['AdsInfo'] = $PowerBB->DB->sql_fetch_array($AdsArr);

		 $PowerBB->template->assign('adsnum',$PowerBB->_CONF['info_row']['ads_num']);
		if ($PowerBB->_CONF['template']['AdsInfo'] == false)
		{
			$PowerBB->template->assign('No_Ads',true);
		}
		else
		{
			$PowerBB->template->assign('No_Ads',false);
		}

      }
        //////
        if (isset($PowerBB->_GET['page']) =='chat_message')
        {
			if (isset($PowerBB->_GET['edit']))
			{
			 if (isset($PowerBB->_GET['main']))
			 {
			  $PowerBB->_CONF['template']['_CONF']['info_row']['activate_chat_bar']= 0;
			 }

			}
         }
			if ($PowerBB->_CONF['info_row']['activate_chat_bar'])
			{				if ($PowerBB->_CONF['member_row']['posts'] < $PowerBB->_CONF['info_row']['chat_num_mem_posts']
				and $PowerBB->_CONF['group_info']['banned'])
				{
		          $PowerBB->template->assign('num_mem_posts',true);
				}
	        // GET Caht Messages

	       $PowerBB->_CONF['template']['while']['Messages'] = $PowerBB->core->GetChatWriterInfo();
	       }


		/** Visitor can't use the private massege system **/
		if ($PowerBB->_CONF['member_permission'])
		{
			if ($PowerBB->_CONF['info_row']['pm_feature'])
			{
	        	/** Can't use the private massege system **/
				if ($PowerBB->_CONF['rows']['group_info']['use_pm'])
				{
				      if ($PowerBB->_CONF['member_row']['unread_pm'] > 0)
				      {
	                     //Get the New masseges In pm popup
				        $user_to = $PowerBB->_CONF['member_row']['username'];
				        $GetMassegeinfo = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['pm'] . " WHERE user_to = '$user_to' and user_read = '' and folder = 'inbox'");
				        $GetMassege = $PowerBB->DB->sql_fetch_array($GetMassegeinfo);
				        $num ='100';
				        $GetMassege['text'] = str_replace('[quote]','',$GetMassege['text']);
				        $GetMassege['text'] = str_replace('[/quote]','',$GetMassege['text']);
		                $GetMassege['text'] = $PowerBB->Powerparse->replace($GetMassege['text']);
		                $PowerBB->Powerparse->replace_smiles($GetMassege['text']);
		                $GetMassege['text'] = $PowerBB->functions->words_count_replace_strip_tags_html2bb($GetMassege['text'],$num);
						$PowerBB->template->assign('Massege_date',$GetMassege['date']);
				        $PowerBB->template->assign('Massege_text',$GetMassege['text']);
				        $Massege_id = $GetMassege['id'];
				        $PowerBB->template->assign('Massege_id',$Massege_id);
				        $PowerBB->template->assign('Massege',$GetMassege);
				      }
				}

			}

		}

            // show Adsense List
			$page = empty($PowerBB->_GET['page']) ? 'index' : $PowerBB->_GET['page'];
			$PowerBB->template->assign('page',$page);

			if (defined('CLASS_NAME') == 'PowerBBForumMOD')
			{
			$Subject_Section = $PowerBB->_GET['id'];
			}
			elseif(defined('CLASS_NAME') == 'PowerBBTopicMOD')
			{
	            $PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');
				$SubjectArr = array();
				$SubjectArr['where'] = array('id',$PowerBB->_GET['id']);

				$SubjectInfo = $PowerBB->core->GetInfo($SubjectArr,'subject');

				$Subject_Section = $SubjectInfo['section'];
			}
			 if (empty($Subject_Section))
			 {
			   $Subject_Section = "000";
             }
		 if (!in_array($Subject_Section, preg_split('#\s*,\s*#s', $PowerBB->_CONF['info_row']['adsense_limited_sections'], -1, PREG_SPLIT_NO_EMPTY)))
		  {
			$AdsenseArr 					= 	array();
			$AdsenseArr['order']			=	array();
			$AdsenseArr['order']['field']	=	'id';
			$AdsenseArr['order']['type']	=	'DESC';
			$AdsenseArr['proc'] 			= 	array();
			$AdsenseArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');

			$PowerBB->_CONF['template']['while']['AdsensesList'] = $PowerBB->core->GetList($AdsenseArr,'adsense');
			if (is_array($PowerBB->_CONF['template']['while']['AdsensesList'])
				and sizeof($PowerBB->_CONF['template']['while']['AdsensesList']) > 0)
			{
				$PowerBB->template->assign('STOP_ADSENSES_TEMPLATE',false);
			}
			else
			{
				$PowerBB->template->assign('STOP_ADSENSES_TEMPLATE',true);
			}
          }
		// Sorry visitor you can't visit this forum today :(
		if (!$PowerBB->_CONF['member_permission'])
		{
			if (!$PowerBB->_CONF['info_row'][$PowerBB->_CONF['day']])
	   		{
				$PowerBB->functions->ShowHeader($PowerBB->_CONF['template']['_CONF']['lang']['sorry_visitor_you_cant_visit_this_forum_today']);
				$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['sorry_visitor_you_cant_visit_this_forum_today']);
	   		}
   		}
   		////////////////////////////

   		// Get username style
       $PowerBB->template->assign('username_style_cache ',$PowerBB->_CONF['member_row']['username_style_cache']);
       // Get username
       $PowerBB->template->assign('username_style',$PowerBB->_CONF['member_row']['username']);
      ////////

    // Get member alerts
     $user_name = $PowerBB->_CONF['member_row']['username'];
     $user_id = $PowerBB->_CONF['member_row']['id'];
		if ($PowerBB->_CONF['member_permission'])
		{
			 if ($PowerBB->_CONF['info_row']['pm_feature'])
			 {
			   $GetPmNum = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(1),id FROM " . $PowerBB->table['pm'] . " WHERE user_to = '$user_name' and user_read = '' and folder = 'inbox' LIMIT 1"));
		       $GetTotalPmNum = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(1),id FROM " . $PowerBB->table['pm'] . " WHERE user_to = '$user_name' and folder = 'inbox' LIMIT 1"));
			 }
			 if ($PowerBB->_CONF['info_row']['active_visitor_message'])
			 {
		       $GetVisitorMessageNumrs = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(1),id FROM " . $PowerBB->table['visitormessage'] . " WHERE userid = '$user_id' and messageread = '1' LIMIT 1"));
			 }
			 if ($PowerBB->_CONF['info_row']['active_friend'])
			 {
		       $GetFriendsNum = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(1),id FROM " . $PowerBB->table['friends'] . " WHERE username_friend = '$user_name' and approval = '0' LIMIT 1"));
			 }
			 if ($PowerBB->_CONF['info_row']['reputationallw'])
			 {
		      $GetReputationNum = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(1),id FROM " . $PowerBB->table['reputation'] . " WHERE username = '$user_name' and reputationread = '1' LIMIT 1"));
			 }
        }

			// Get alerts num mention
			if($PowerBB->functions->mention_permissions())
			{
			$GetMentionNum = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(1),id FROM " . $PowerBB->prefix . "mention WHERE you = '$user_name' AND user_read = '1' LIMIT 1"));

			if (!$GetMentionNum)
			{
			$GetMentionNum = '0';
			}
			$PowerBB->template->assign('mention_num',$GetMentionNum);
			}

	     $PowerBB->template->assign('alerts_num',$GetFriendsNum+$GetVisitorMessageNumrs+$GetReputationNum+$GetMentionNum);
	     $PowerBB->template->assign('all_alerts_num',$GetFriendsNum+$GetVisitorMessageNumrs+$GetPmNum+$GetReputationNum+$GetMentionNum);
	     $PowerBB->template->assign('pm_num',$GetPmNum);
	     $PowerBB->template->assign('TotalPmNum',$GetTotalPmNum);
	     $PowerBB->template->assign('visitor_message_Numrs',$GetVisitorMessageNumrs);
	     $PowerBB->template->assign('friends_num',$GetFriendsNum);
	     $PowerBB->template->assign('ReputationNum',$GetReputationNum);
		 $PowerBB->template->assign('mention_num',$GetMentionNum);

     ////////
       // Get All Smile - SmlList
	  $PowerBB->_CONF['template']['while']['SmlList'] = $PowerBB->icon->GetCachedSmiles();


       // Get Pages
		$PageArr 					= 	array();
		$PageArr['order']			=	array();
		$PageArr['order']['field']	=	'sort';
		$PageArr['order']['type']	=	'ASC';
		$PageArr['proc'] 			= 	array();
		$PageArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');

		$PowerBB->_CONF['template']['while']['PagesList'] = $PowerBB->core->GetList($PageArr,'pages');
		if ($PowerBB->_CONF['template']['while']['PagesList'] == false)
		{
			$PowerBB->template->assign('No_PagesList',true);
		}
		//
       $PowerBB->_CONF['template']['_CONF']['lang']['copyright'] = str_replace("3.0.0",$PowerBB->_CONF['info_row']['MySBB_version'],$PowerBB->_CONF['template']['_CONF']['lang']['copyright']);
       $PowerBB->_CONF['template']['_CONF']['lang']['copyright_archive'] = str_replace("3.0.0",$PowerBB->_CONF['info_row']['MySBB_version'],$PowerBB->_CONF['template']['_CONF']['lang']['copyright_archive']);
       $PowerBB->_CONF['template']['_CONF']['lang']['copyright'] = str_ireplace("pbboard.com","pbboard.info",$PowerBB->_CONF['template']['_CONF']['lang']['copyright']);
       $PowerBB->_CONF['template']['_CONF']['lang']['copyright_archive'] = str_ireplace("pbboard.com","pbboard.info",$PowerBB->_CONF['template']['_CONF']['lang']['copyright_archive']);
       $PowerBB->_CONF['template']['_CONF']['lang']['copyright'] = str_replace("{copyright}",$PowerBB->_CONF['info_row']['MySBB_version'],$PowerBB->_CONF['template']['_CONF']['lang']['copyright']);

      // Get register date
   		$register_date = $PowerBB->functions->_date($PowerBB->_CONF['member_row']['register_date']);
         $PowerBB->template->assign('register_date',$register_date);
         // Get Last visit
   		$last_visit = $PowerBB->functions->_date($PowerBB->_CONF['member_row']['lastvisit']);
         $PowerBB->template->assign('lastvisit',$last_visit);
         $PowerBB->template->assign('ForumAdress',$PowerBB->functions->GetForumAdress());

          // Get On sidebar list pages  05-09-2015 v3.0.3
		  if ($PowerBB->_CONF['info_row']['sidebar_list_active'])
		  {
		     $page = empty($PowerBB->_GET['page']) ? 'index' : $PowerBB->_GET['page'];
			if($page)
			{
                $sidebar_list_pages_array = preg_split('#[ \r\n\t]+#', $PowerBB->_CONF['info_row']['sidebar_list_pages'], -1, PREG_SPLIT_NO_EMPTY);
				if(in_array($page,$sidebar_list_pages_array))
				{
				$PowerBB->template->assign('on_sidebar_list_thes_page',1);
				}
				else
				{
				$PowerBB->template->assign('on_sidebar_list_thes_page',0);
				}

				$sidebar_list_exclusion_forums_array = preg_split('#[,]+#', $PowerBB->_CONF['info_row']['sidebar_list_exclusion_forums'], -1, PREG_SPLIT_NO_EMPTY);

				if(in_array($page,$sidebar_list_pages_array) == 'forum')
				{
               if($page == 'forum')
               {
                if(in_array($PowerBB->_GET['id'],$sidebar_list_exclusion_forums_array))
                {               	 $PowerBB->template->assign('on_sidebar_list_thes_page',0);
                }
               }
              }
			}

			if ($PowerBB->_CONF['info_row']['sidebar_list_align'] == 'left')
			{			$PowerBB->template->assign('opposite_direction','right');
			}
			elseif ($PowerBB->_CONF['info_row']['sidebar_list_align'] == 'right')
			{
			$PowerBB->template->assign('opposite_direction','left');
			}
	      }

	      // Get info last posts cache
	      if ($PowerBB->_CONF['info_row']['activate_lasts_posts_bar']
	      or $PowerBB->_CONF['info_row']['sidebar_list_active']
	      or $PowerBB->_CONF['info_row']['activate_last_static_list'])
	      {
	        // $PowerBB->functions->PBB_Create_last_posts_cache(0);
	        $cache = $PowerBB->_CONF['info_row']['last_posts_cache'];
			$pbb_last_posts_cache = json_decode(base64_decode($cache), true);
			$PowerBB->_CONF['template']['while']['lastPostsList'] = $pbb_last_posts_cache;
          }
        // main_bar template Change background color to class primary_on
		$PowerBB->_CONF['template']['latest_reply_page'] = 'primary_oof';
		$PowerBB->_CONF['template']['main_page'] = 'primary_oof';
		$PowerBB->_CONF['template']['portal_page'] = 'primary_oof';
       //
        $sidebar_list_width = 99-$PowerBB->_CONF['info_row']['sidebar_list_width']."%";
		$PowerBB->template->assign('sidebar_list_width',$sidebar_list_width);

         $code_parse = $PowerBB->functions->get_hooks("commonHooks");

	 }

}

?>