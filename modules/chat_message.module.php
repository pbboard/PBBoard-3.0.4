<?php

(!defined('IN_PowerBB')) ? die() : '';

$CALL_SYSTEM					=	array();
$CALL_SYSTEM['SUBJECT'] 		= 	true;
$CALL_SYSTEM['SECTION'] 		= 	true;
$CALL_SYSTEM['TOOLBOX'] 		= 	true;
$CALL_SYSTEM['ICONS'] 			= 	true;
$CALL_SYSTEM['CORE'] 			= 	true;

define('JAVASCRIPT_PowerCode',true);



define('CLASS_NAME','PowerBBCoreMOD');
define('chat_message',true);

include('common.php');
class PowerBBCOREMOD
{
	function run()
	{
		global $PowerBB;
		if ($PowerBB->_CONF['info_row']['activate_chat_bar'] == '0')
		{
			 $PowerBB->functions->ShowHeader();
             $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['You_can_not_use_this_feature']);
             $PowerBB->functions->GetFooter();
		}

          if (!$PowerBB->_CONF['member_permission'])
              {
              header("HTTP/1.1 404 Not Found");
              $PowerBB->functions->ShowHeader();
              $PowerBB->template->display('login');
              $PowerBB->functions->GetFooter();
              $PowerBB->functions->error_stop();
			}

		/** Go to Chat site **/
		if ($PowerBB->_GET['chat'] == '1')
		{
		    $PowerBB->functions->ShowHeader();
			$this->_AddchatMessage();
		}
		elseif ($PowerBB->_GET['chatout'] == '1')
		{
		   $this->_OpinChatout();
		   $PowerBB->_POST['ajax'] = 1;
 		}
		elseif ($PowerBB->_GET['chat_w'] == '1')
		{
		   $this->_ChatWindow();
		   $PowerBB->_POST['ajax'] = 1;
 		}
		elseif ($PowerBB->_GET['chat_users'] == '1')
		{
		   $this->_onlineChat();
		   $PowerBB->_POST['ajax'] = 1;
 		}
		elseif ($PowerBB->_GET['start'] == '1')
		{
			$this->_StartchatMessage();
		}
		else
		{	if ($PowerBB->_CONF['member_permission'])
			{
				if ($PowerBB->_CONF['member_row']['usergroup'] == '1'
					or $PowerBB->_CONF['group_info']['vice'])
				{

                    $PowerBB->functions->ShowHeader();
					if ($PowerBB->_GET['control'] == '1')
					{
						if ($PowerBB->_GET['main'] == '1')
						{
							$this->_ControlMain();
						}
						else
						{
			             $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Sorry_url_not_true']);
						}
					}
					elseif ($PowerBB->_GET['edit'] == '1')
					{
						if ($PowerBB->_GET['main'] == '1')
						{
							$this->_EditMain();
						}
						elseif ($PowerBB->_GET['started'] == '1')
						{
							$this->_EditStart();
						}
						else
						{
			             $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Sorry_url_not_true']);
						}
					}
					elseif ($PowerBB->_GET['del'] == '1')
					{
		                if ($PowerBB->_GET['startdel'] == '1')
						{
							$this->_DelStart();
						}
						elseif ($PowerBB->_GET['del_all'] == '1')
						{
							$this->_DelAllStart();
						}
						else
						{
			             $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Sorry_url_not_true']);
						}
					}
					else
					{
		             $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Sorry_url_not_true']);
					}
				}
				else
				{
				header("Location: index.php");
				exit;
				}
	         }
			else
			{
			header("Location: index.php");
			exit;
			}

		}
		 if (!isset($PowerBB->_POST['ajax']))
		 {
		   $PowerBB->functions->GetFooter();
		 }
	}

	/**
	 * add chat message
	 */
	function _AddchatMessage()
	{
		global $PowerBB;


		/** member can't use the chat system if his posts was less than 20 posts **/

		$MemberArr 				= 	array();
		$MemberArr['where']		=	array('username',$PowerBB->_CONF['member_row']['username']);

		$member = $PowerBB->core->GetInfo($MemberArr,'member');
		if ($PowerBB->_CONF['member_row']['posts'] < $PowerBB->_CONF['info_row']['chat_num_mem_posts']
		and $PowerBB->_CONF['group_info']['banned'])
		{
          $PowerBB->template->assign('num_mem_posts',true);
		}

         $PowerBB->_CONF['template']['while']['SmileRows'] = $PowerBB->icon->GetCachedSmiles();

        $PowerBB->template->display('add_chat_message');

	}

	function _ChatWindow()
	{
		global $PowerBB;
	        /**
		 * Know who is in Chat ?
		 */
		$TotleCahtArr 					= 	array();
		$TotleCahtArr['order']			=	array();
		$TotleCahtArr['order']['field']	=	'id';
		$TotleCahtArr['order']['type']	=	'DESC';
		$chatTotle_message_num = $PowerBB->core->GetNumber($TotleCahtArr,'chat');
		if($chatTotle_message_num){
    	$PowerBB->template->display('chat_window');
    	}
	}

	function _onlineChat()
	{
		global $PowerBB;
	        /**
		 * Know who is in Chat ?
		 */
		$ChatWhoArr 						= 	array();
		$ChatWhoArr['get_from']				=	'db';
		$ChatWhoArr['proc'] 				= 	array();
		$ChatWhoArr['proc']['*'] 			= 	array('method'=>'clean','param'=>'html');
		$ChatWhoArr['order']				=	array();
		$ChatWhoArr['order']['field']		=	'last_move';
		$ChatWhoArr['order']['type']		=	'ASC';
		$ChatWhoArr['where']				=	array();
		$ChatWhoArr['where'][0]['name']		= 	'user_location';
		$ChatWhoArr['where'][0]['oper']		= 	'=';
		$ChatWhoArr['where'][0]['value']	= 	$PowerBB->_CONF['template']['_CONF']['lang']['chat_message'];

		$ChatWhoArr['where'][1] 			= 	array();
		$ChatWhoArr['where'][1]['con']		=	'AND';
		$ChatWhoArr['where'][1]['name'] 	= 	'last_move';
		$ChatWhoArr['where'][1]['oper'] 	= 	'>';
		$ChatWhoArr['where'][1]['value'] 	= 	time() - 300;

		$PowerBB->_CONF['template']['while']['ListonlineChat'] = $PowerBB->core->GetList($ChatWhoArr,'online');

		$online_number = @sizeof($PowerBB->_CONF['template']['while']['ListonlineChat']);
		$PowerBB->template->assign('online_chat_number',$online_number);
		if($online_number){
    	$PowerBB->template->display('chat_online');
    	}
	}

	function _OpinChatout()
	{
		global $PowerBB;


		/** member can't use the chat system if his posts was less than 20 posts **/

		$MemberArr 				= 	array();
		$MemberArr['where']		=	array('username',$PowerBB->_CONF['member_row']['username']);

		$member = $PowerBB->core->GetInfo($MemberArr,'member');
		if ($PowerBB->_CONF['member_row']['posts'] < $PowerBB->_CONF['info_row']['chat_num_mem_posts']
		and $PowerBB->_CONF['group_info']['banned'])
		{
		$PowerBB->template->assign('num_mem_posts',true);
		}



		$PowerBB->_CONF['template']['while']['SmlList'] = $PowerBB->icon->GetCachedSmiles();
		$PowerBB->template->assign('chatout',true);
		$TotleCahtArr 					= 	array();
		$TotleCahtArr['order']			=	array();
		$TotleCahtArr['order']['field']	=	'id';
		$TotleCahtArr['order']['type']	=	'DESC';
		$chatTotle_message_num = $PowerBB->core->GetNumber($TotleCahtArr,'chat');
		$PowerBB->template->assign('chatTotle_message_num',$chatTotle_message_num);

		// Finally we get Who is in Chat

		$ChatWhoArr 						= 	array();
		$ChatWhoArr['get_from']				=	'db';
		$ChatWhoArr['proc'] 				= 	array();
		$ChatWhoArr['proc']['*'] 			= 	array('method'=>'clean','param'=>'html');
		$ChatWhoArr['order']				=	array();
		$ChatWhoArr['order']['field']		=	'last_move';
		$ChatWhoArr['order']['type']		=	'ASC';
		$ChatWhoArr['where']				=	array();
		$ChatWhoArr['where'][0]['name']		= 	'user_location';
		$ChatWhoArr['where'][0]['oper']		= 	'=';
		$ChatWhoArr['where'][0]['value']	= 	$PowerBB->_CONF['template']['_CONF']['lang']['chat_message'];

		$PowerBB->_CONF['template']['while']['ListonlineChat'] = $PowerBB->core->GetList($ChatWhoArr,'online');

		$online_number = @sizeof($PowerBB->_CONF['template']['while']['ListonlineChat']);
		$PowerBB->template->assign('online_chat_number',$online_number);
        $PowerBB->template->display('chat');


	}
	function _StartchatMessage()
	{

		global $PowerBB;

		/** Visitor can't use the chat system **/
		if (!$PowerBB->_CONF['member_permission'])
		{
          $PowerBB->functions->error_no_foot($PowerBB->_CONF['template']['_CONF']['lang']['Visitor_can_not_use_the_chat_system']);
		}

			if (empty($PowerBB->_POST['textc']))
			{
				$PowerBB->functions->error_no_foot($PowerBB->_CONF['template']['_CONF']['lang']['Please_write_the_message']);
			}

		/** member can't use the chat system if his posts was less than 20 posts **/
		$MemberArr 				= 	array();
		$MemberArr['where']		=	array('username',$PowerBB->_CONF['member_row']['username']);

		$member = $PowerBB->core->GetInfo($MemberArr,'member');
		if ($member['posts'] < $PowerBB->_CONF['info_row']['chat_num_mem_posts'])
		{
		$PowerBB->template->assign('member',$member);
         $PowerBB->_CONF['template']['_CONF']['lang']['Member_can_not_use_the_chat_system_posts_less'] = str_ireplace('20',$PowerBB->_CONF['info_row']['chat_num_mem_posts'],$PowerBB->_CONF['template']['_CONF']['lang']['Member_can_not_use_the_chat_system_posts_less']);
         $PowerBB->functions->error_no_foot($PowerBB->_CONF['template']['_CONF']['lang']['Member_can_not_use_the_chat_system_posts_less']);
		}



			if ($PowerBB->_CONF['info_row']['chat_hide_country'] == 99)
			{

			if (empty($PowerBB->_POST['country']))
			{
	         $PowerBB->functions->error_no_foot($PowerBB->_CONF['template']['_CONF']['lang']['Please_write_the_country']);
			}
			}


            //$PowerBB->Powerparse->replace_smiles($PowerBB->_POST['textc']);
            $PowerBB->_POST['textc'] = str_ireplace('{39}',"'",$PowerBB->_POST['textc']);
            $PowerBB->_POST['textc'] = str_ireplace('cookie','',$PowerBB->_POST['textc']);


             // Filter Words
	        $censorwords = preg_split('#[ \r\n\t]+#', $PowerBB->_CONF['info_row']['censorwords'], -1, PREG_SPLIT_NO_EMPTY);
            $PowerBB->_POST['country'] = str_ireplace($censorwords,'', $PowerBB->_POST['country']);
            $PowerBB->_POST['textc'] = str_ireplace($censorwords,'', $PowerBB->_POST['textc']);
            $PowerBB->_POST['textc'] = str_ireplace('&amp;','&',$PowerBB->_POST['textc']);
            $PowerBB->_POST['textc'] = str_ireplace('<br>','',$PowerBB->_POST['textc']);
            $PowerBB->_POST['textc'] = str_ireplace('</p>','',$PowerBB->_POST['textc']);
            $PowerBB->_POST['textc'] = str_ireplace('<p>','',$PowerBB->_POST['textc']);
            $PowerBB->_POST['textc'] = str_ireplace('XSS','',$PowerBB->_POST['textc']);
            $PowerBB->_POST['textc'] = str_ireplace('write','',$PowerBB->_POST['textc']);
            $PowerBB->_POST['textc'] = str_ireplace('document','',$PowerBB->_POST['textc']);
            $PowerBB->_POST['textc'] = str_ireplace('&quot;','',$PowerBB->_POST['textc']);
            $PowerBB->_POST['country'] = str_ireplace('&amp;','&',$PowerBB->_POST['country']);
            $PowerBB->_POST['country'] = str_ireplace('<br>','',$PowerBB->_POST['country']);
            $PowerBB->_POST['country'] = str_ireplace('</p>','',$PowerBB->_POST['country']);
            $PowerBB->_POST['country'] = str_ireplace('<p>','',$PowerBB->_POST['country']);
            $PowerBB->_POST['country'] = str_ireplace('&quot;','',$PowerBB->_POST['country']);
            $PowerBB->_POST['country'] = str_ireplace('http://','',$PowerBB->_POST['country']);
            $PowerBB->_POST['country'] = str_ireplace('www','',$PowerBB->_POST['country']);
            $PowerBB->_POST['country'] = str_ireplace('com','',$PowerBB->_POST['country']);
            $PowerBB->_POST['country'] = str_ireplace('net','',$PowerBB->_POST['country']);
            $PowerBB->_POST['country'] = str_ireplace('org;','',$PowerBB->_POST['country']);
            $PowerBB->_POST['country'] = str_ireplace('iframe;','',$PowerBB->_POST['country']);
            $PowerBB->_POST['textc'] = str_ireplace('iframe;','',$PowerBB->_POST['textc']);
            //
        	   $PowerBB->_POST['country'] = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['country'],'html');
               //$PowerBB->_POST['textc'] =  $PowerBB->functions->CleanVariable($PowerBB->_POST['textc'],'nohtml');
		       // Kill SQL Injection
		        $PowerBB->_POST['country'] = $PowerBB->functions->CleanVariable($PowerBB->_POST['country'],'sql');
		        $PowerBB->_POST['textc'] = $PowerBB->functions->CleanVariable($PowerBB->_POST['textc'],'sql');

            $TextPost = utf8_decode($PowerBB->_POST['textc']);
    		if (isset($TextPost{$PowerBB->_CONF['info_row']['chat_num_characters']}))
    		{
    		     $PowerBB->_CONF['template']['_CONF']['lang']['message_large_number_of_characters'] = str_ireplace('970',$PowerBB->_CONF['info_row']['chat_num_characters'],$PowerBB->_CONF['template']['_CONF']['lang']['message_large_number_of_characters']);
                 $PowerBB->functions->error_no_foot($PowerBB->_CONF['template']['_CONF']['lang']['message_large_number_of_characters']);
             }
            elseif(strstr($TextPost,'<')
            or strstr($TextPost,'=')
            or strstr($TextPost,'{')
            or strstr($TextPost,'}'))
            {             $PowerBB->functions->error_no_foot($PowerBB->_CONF['template']['_CONF']['lang']['Please_write_the_message']);
            }

			$ChatArr 			= 	array();
			$ChatArr['field']	=	array();

			$ChatArr['field']['country'] 	    = 	$PowerBB->_CONF['member_row']['user_country'];
			$ChatArr['field']['message'] 		= 	$PowerBB->_POST['textc'];
			$ChatArr['field']['username'] 		= 	$PowerBB->_CONF['member_row']['username'];
			$ChatArr['field']['user_id'] 		= 	$PowerBB->_CONF['member_row']['id'];
			$ChatArr['field']['date'] 		    = 	$PowerBB->_CONF['now'];

			$insert = $PowerBB->core->Insert($ChatArr,'chat');


		$TotleCahtArr 					= 	array();
		$TotleCahtArr['order']			=	array();
		$TotleCahtArr['order']['field']	=	'id';
		$TotleCahtArr['order']['type']	=	'DESC';
		$chatTotle_message_num = $PowerBB->core->GetNumber($TotleCahtArr,'chat');
       if ($chatTotle_message_num > $PowerBB->_CONF['info_row']['chat_message_num'])
        {
			$LastChatArr 						= 	array();
			$LastChatArr['order'] 				= 	array();
			$LastChatArr['order']['field'] 		= 	'id';
			$LastChatArr['order']['type']	 	= 	' ASC';
			$LastChatArr['limit'] 				= 	'0,1';

			$PowerBB->_CONF['template']['LastChat'] = $PowerBB->core->GetInfo($LastChatArr,'chat');

			$DelArr 			= 	array();
			$DelArr['where'] 	= 	array('id',$PowerBB->_CONF['template']['LastChat']['id']);

			$del = $PowerBB->core->Deleted($DelArr,'chat');

		}

		 if (!isset($PowerBB->_POST['ajax']))
		 {
		  header("Location: ".$PowerBB->_SERVER['HTTP_REFERER']);
		  exit;
		 }

		$ChatMessages = $PowerBB->core->GetChatWriterInfo();
		$PowerBB->template->assign('ChatMessages',$ChatMessages);
		$PowerBB->template->assign('chatTotle_message_num2',$chatTotle_message_num);
	}

	function _ControlMain()
	{
		global $PowerBB;

		$PowerBB->_GET['count'] = (!isset($PowerBB->_GET['count'])) ? 0 : $PowerBB->_GET['count'];
		$PowerBB->_GET['count'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['count'],'intval');
        $Cahtperpage = 20;
		$TotleCahtArr 					= 	array();
		$TotleCahtArr['order']			=	array();
		$TotleCahtArr['order']['field']	=	'id';
		$TotleCahtArr['order']['type']	=	'DESC';

        // show Caht bar
		$CahtArr 					= 	array();
		$CahtArr['order']			=	array();
		$CahtArr['order']['field']	=	'id';
		$CahtArr['order']['type']	=	'ASC';

		$CahtArr['pager'] 				= 	array();
		$CahtArr['pager']['total']		= 	$PowerBB->core->GetNumber($TotleCahtArr,'chat');
		$CahtArr['pager']['perpage'] 	= 	$Cahtperpage;
		$CahtArr['pager']['count'] 		= 	$PowerBB->_GET['count'];
		$CahtArr['pager']['location'] 	= 	'index.php?page=chat_message&control=1&main=1';
		$CahtArr['pager']['var'] 		= 	'count';

		$CahtArr['proc'] 			= 	array();
		$CahtArr['limit']           = $PowerBB->_CONF['info_row']['chat_message_num'];
		$CahtArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');

		$PowerBB->_CONF['template']['while']['MessagesList'] = $PowerBB->core->GetList($CahtArr,'chat');

       if ($PowerBB->core->GetNumber($TotleCahtArr,'chat') > $Cahtperpage)
        {
		$PowerBB->template->assign('pager',$PowerBB->pager->show());
        }
        $PowerBB->template->assign('CahtMessagesNumber',$PowerBB->core->GetNumber($TotleCahtArr,'chat'));

		$PowerBB->template->display('chat_main');
	}




	function _EditMain()
	{
		global $PowerBB;

			if (empty($PowerBB->_GET['id']))
			{
				$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Chat_message_requested_does_not_exist']);
			}

			$CahtEditArr				=	array();
		    $CahtEditArr['where'] 	= 	array('id',$PowerBB->_GET['id']);

			$chatEdit = $PowerBB->core->GetInfo($CahtEditArr,'chat');

			if (empty($chatEdit['id']))
			{
				$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Chat_message_requested_does_not_exist']);
			}
         $chatEdit['message'] = $PowerBB->Powerparse->censor_words($chatEdit['message']);

			$PowerBB->template->assign('chatEdit',$chatEdit);
            $PowerBB->template->assign('message',$chatEdit['message']);
		$smiles = $PowerBB->icon->GetCachedSmiles();
		foreach ($smiles as $smile)
		{
			$PowerBB->functions->CleanVariable($smile,'html');

			$chatEdit['message'] = str_replace('<img src="' . $smile['smile_path'] . '" border="0" alt="' . $smile['smile_short'] . '" />',$smile['smile_short'],$chatEdit['message']);
			$PowerBB->template->assign('message',$chatEdit['message']);

		}

       $PowerBB->_CONF['template']['while']['SmileRows'] = $PowerBB->icon->GetCachedSmiles();
		$PowerBB->template->display('chat_edit');
	}

	function _EditStart()
	{
		global $PowerBB;

			if (empty($PowerBB->_GET['id']))
			{
				$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Chat_message_requested_does_not_exist']);
			}

           $PowerBB->_POST['text'] = str_ireplace('{39}',"'",$PowerBB->_POST['text']);
            $PowerBB->_POST['text'] = str_ireplace('cookie','',$PowerBB->_POST['text']);


             // Filter Words
	        $censorwords = preg_split('#[ \r\n\t]+#', $PowerBB->_CONF['info_row']['censorwords'], -1, PREG_SPLIT_NO_EMPTY);
            $PowerBB->_POST['country'] = str_ireplace($censorwords,'', $PowerBB->_POST['country']);
            $PowerBB->_POST['text'] = str_ireplace($censorwords,'', $PowerBB->_POST['text']);
            $PowerBB->_POST['text'] = str_ireplace('&amp;','&',$PowerBB->_POST['text']);
            $PowerBB->_POST['text'] = str_ireplace('<br>','',$PowerBB->_POST['text']);
            $PowerBB->_POST['text'] = str_ireplace('</p>','',$PowerBB->_POST['text']);
            $PowerBB->_POST['text'] = str_ireplace('<p>','',$PowerBB->_POST['text']);
            $PowerBB->_POST['text'] = str_ireplace('XSS','',$PowerBB->_POST['text']);
            $PowerBB->_POST['text'] = str_ireplace('write','',$PowerBB->_POST['text']);
            $PowerBB->_POST['text'] = str_ireplace('document','',$PowerBB->_POST['text']);
            $PowerBB->_POST['text'] = str_ireplace('&quot;','',$PowerBB->_POST['text']);
            $PowerBB->_POST['country'] = str_ireplace('&amp;','&',$PowerBB->_POST['country']);
            $PowerBB->_POST['country'] = str_ireplace('<br>','',$PowerBB->_POST['country']);
            $PowerBB->_POST['country'] = str_ireplace('</p>','',$PowerBB->_POST['country']);
            $PowerBB->_POST['country'] = str_ireplace('<p>','',$PowerBB->_POST['country']);
            $PowerBB->_POST['country'] = str_ireplace('&quot;','',$PowerBB->_POST['country']);
            $PowerBB->_POST['country'] = str_ireplace('http://','',$PowerBB->_POST['country']);
            $PowerBB->_POST['country'] = str_ireplace('www','',$PowerBB->_POST['country']);
            $PowerBB->_POST['country'] = str_ireplace('com','',$PowerBB->_POST['country']);
            $PowerBB->_POST['country'] = str_ireplace('net','',$PowerBB->_POST['country']);
            $PowerBB->_POST['country'] = str_ireplace('org;','',$PowerBB->_POST['country']);
            $PowerBB->_POST['country'] = str_ireplace('iframe;','',$PowerBB->_POST['country']);
            $PowerBB->_POST['text'] = str_ireplace('iframe;','',$PowerBB->_POST['text']);
            //
        	   $PowerBB->_POST['country'] = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['country'],'html');
               //$PowerBB->_POST['text'] =  $PowerBB->functions->CleanVariable($PowerBB->_POST['text'],'nohtml');
		       // Kill SQL Injection
		        $PowerBB->_POST['country'] = $PowerBB->functions->CleanVariable($PowerBB->_POST['country'],'sql');
		        $PowerBB->_POST['text'] = $PowerBB->functions->CleanVariable($PowerBB->_POST['text'],'sql');

            $TextPost = utf8_decode($PowerBB->_POST['text']);
    		if (isset($TextPost{$PowerBB->_CONF['info_row']['chat_num_characters']}))
    		{
    		     $PowerBB->_CONF['template']['_CONF']['lang']['message_large_number_of_characters'] = str_ireplace('970',$PowerBB->_CONF['info_row']['chat_num_characters'],$PowerBB->_CONF['template']['_CONF']['lang']['message_large_number_of_characters']);
                 $PowerBB->functions->error_no_foot($PowerBB->_CONF['template']['_CONF']['lang']['message_large_number_of_characters']);
             }

		$ChatArr 			= 	array();
		$ChatArr['field']	=	array();

		$ChatArr['field']['country'] 	    = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['country'],'html');
		$ChatArr['field']['message'] 		= 	$PowerBB->_POST['text'];
		$ChatArr['field']['country'] 	= 	$PowerBB->_POST['country'];
		$ChatArr['field']['username'] 	= 	$PowerBB->_POST['username'];
		$ChatArr['where'] 				= 	array('id',$PowerBB->_GET['id']);

        $update = $PowerBB->core->Update($ChatArr,'chat');
		if ($update)
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Chat_message_has_been_updated_successfully']);
			$PowerBB->functions->redirect('index.php?page=chat_message&amp;control=1&amp;main=1');
		}
	}

	function _DelStart()
	{
		global $PowerBB;

			if (empty($PowerBB->_GET['id']))
			{
				$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Chat_message_requested_does_not_exist']);
			}

			$CahtEditArr				=	array();
		    $CahtEditArr['where'] 	= 	array('id',$PowerBB->_GET['id']);

			$chatEdit = $PowerBB->core->GetInfo($CahtEditArr,'chat');
			if (empty($chatEdit['id']))
			{
				$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Chat_message_requested_does_not_exist']);
			}
			$DelArr 			= 	array();
			$DelArr['where'] 	= 	array('id',$PowerBB->_GET['id']);

			$del = $PowerBB->core->Deleted($DelArr,'chat');

		if ($del)
		{
				$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Chat_message_has_been_deleted_successfully']);
				$PowerBB->functions->redirect('index.php?page=chat_message&amp;control=1&amp;main=1');

		}
	}

	function _DelAllStart()
	{
		global $PowerBB;


			$truncate = $PowerBB->DB->sql_query("TRUNCATE " . $PowerBB->table['chat'] );


		if ($truncate)
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Chat_messages_has_been_deleted_successfully']);
			$PowerBB->functions->redirect('index.php?page=chat_message&amp;control=1&amp;main=1');
		}
	}

}

?>
