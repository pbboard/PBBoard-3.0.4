<?php

(!defined('IN_PowerBB')) ? die() : '';

define('IN_ADMIN',true);

$CALL_SYSTEM				=	array();
$CALL_SYSTEM['EMAILMESSAGES'] =   true;



define('CLASS_NAME','PowerBBMailsendingMOD');

include('../common.php');
class PowerBBMailsendingMOD
{
	function run()
	{
		global $PowerBB;

		if ($PowerBB->_CONF['member_permission'])
		{
            $PowerBB->template->display('header');

			if ($PowerBB->_CONF['rows']['group_info']['admincp_member'] == '0')
			{
			  $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
			}

			if ($PowerBB->_GET['mail'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_MailMain();
				}
				elseif ($PowerBB->_GET['start'])
				{
					$this->_MailStart();
				}
			}

		}
$PowerBB->template->display('footer');
	}

	 function _MailMain()
	  {
		global $PowerBB;

		if($PowerBB->_CONF['info_row']['send_email'] =='')
		{
		$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['you_be_must_ENTER_send_email']);
		}

		$SmlArr 					= 	array();
		$SmlArr['order'] 			=	array();
		$SmlArr['order']['field']	=	'id';
		$SmlArr['order']['type']	=	'ASC';
		$SmlArr['limit']			=	$PowerBB->_CONF['info_row']['smiles_nm'];
		$SmlArr['proc'] 			= 	array();
		$SmlArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');

		$PowerBB->_CONF['template']['while']['SmileRows'] = $PowerBB->icon->GetSmileList($SmlArr);


		// Get groups list
		$GroupArr 							= 	array();

		$GroupArr['order']					=	array();
		$GroupArr['order']['field']			=	'id';
		$GroupArr['order']['type']			=	'DESC';

		$GroupArr = array();
		$GroupArr['where'] 				= 	array();
		$GroupArr['where'][0] 			= 	array();
		$GroupArr['where'][0]['name'] 	= 	'id not in (7) AND banned';
		$GroupArr['where'][0]['oper'] 	= 	'<>';
		$GroupArr['where'][0]['value'] 	= 	6;

		$GroupArr['proc'] 					= 	array();
		$GroupArr['proc']['*'] 				= 	array('method'=>'clean','param'=>'html');

		// Store information in "GroupList"
		$PowerBB->_CONF['template']['while']['GroupList'] = $PowerBB->core->GetList($GroupArr,'group');

		$PowerBB->template->display('member_send_msg');
	}
	function _MailStart()
	{
		global $PowerBB;


	 if($PowerBB->_POST['group'])
	 {
		if (empty($PowerBB->_POST['title'])
			or empty($PowerBB->_POST['text']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_fill_in_all_the_information']);
		}

     	$MailsendingArr 								= 	array();
     	$MailsendingArr['get_id']					=	true;
     	$MailsendingArr['field']						=	array();

     	$MailsendingArr['field']['title'] 				= 	$PowerBB->_POST['title'];
     	$MailsendingArr['field']['message'] 			= 	$PowerBB->_POST['text'];
     	$MailsendingArr['field']['user_group'] 		    =  	$PowerBB->_POST['group'];
     	$MailsendingArr['field']['number_messages'] 	= 	$PowerBB->_POST['number_messages'];
     	$MailsendingArr['field']['seconds'] 	        = 	$PowerBB->_POST['seconds'];


     	$Insert = $PowerBB->emailmessages->InsertEmailMessages($MailsendingArr);


	 }

		$MailArr 							= 	array();

		$MailArr['order']					=	array();
		$MailArr['order']['field']			=	'id';
		$MailArr['order']['type']			=	'DESC';

		$MailInfo = $PowerBB->emailmessages->GetEmailMessagesInfo($MailArr);


		$page = (int) (!isset($PowerBB->_GET['pag']) ? 1 : $PowerBB->_GET['pag']);
		$page = ($page == 0 ? 1 : $page);
		$perpage = $MailInfo['number_messages'];
		$startpoint = ($page * $perpage) - $perpage;
     	$GetForumAdress = $PowerBB->functions->GetForumAdress();
        $GetForumAdress = str_replace($PowerBB->admincpdir."/", '', $GetForumAdress);

		$Adress	= 	'<a href="'.$GetForumAdress.'index.php'.'">'.$GetForumAdress.'index.php'.'</a>';
		$greetings	= 	$PowerBB->_CONF['template']['_CONF']['lang']['With_my_sincere_greetings_to_all'];
		$Management_Forum	= 	$PowerBB->_CONF['template']['_CONF']['lang']['Management_Forum'];
		$info_row_title	= 	$PowerBB->_CONF['info_row']['title'];
       $br = "\n";
			$Form_Massege ="\n---------------------------------------------------\n<b>".$greetings."\n".$Management_Forum . $info_row_title."\n".$Adress ."&nbsp;</b>";


		$MailInfo['message'] = $PowerBB->Powerparse->replace($MailInfo['message']);
		$Form_Massege = $PowerBB->Powerparse->replace($Form_Massege);
		$br = $PowerBB->Powerparse->replace($br);
        $GetForumAdress = str_replace("http://", '', $GetForumAdress);
        $MailInfo['message'] = str_replace("../look/images/smiles", $GetForumAdress."look/images/smiles", $MailInfo['message']);
        $MailInfo['message'] = str_replace("http://","", $MailInfo['message']);

		echo('<br><br><table border="1" width="80%" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" style="border-collapse: collapse" align="center"><tr><td><font face="Tahoma" size="2">');
		if($MailInfo['user_group'] == 'all')
		{
		$getmember_query = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['member'] . " ORDER BY id ASC LIMIT ".$startpoint.",".$perpage." ");
		$member_nm = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['member'] . " WHERE id"));
		}
		else
		{
		$getmember_query = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['member'] . " WHERE usergroup='" . intval($MailInfo['user_group']). "'  ORDER BY id ASC LIMIT ".$startpoint.",".$perpage." ");
		$member_nm = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['member'] . " WHERE usergroup='" . intval($MailInfo['user_group']). "' "));
		}
		$pagesnum = round(ceil($member_nm / $perpage));
		while ($getmember_row = $PowerBB->DB->sql_fetch_array($getmember_query))
		{

		$username = $PowerBB->_CONF['template']['_CONF']['lang']['hello_your']. ' : ' . $getmember_row['username'] .'
		';

          $MailInfo['message'] = str_replace("{username}", $getmember_row['username'],$MailInfo['message']);
          $MailInfo['message'] = str_replace("{user_id}", $getmember_row['id'],$MailInfo['message']);
          $MailInfo['message'] = str_replace("{user_email}", $getmember_row['email'],$MailInfo['message']);
          $MailInfo['message'] = str_replace("{forum_title}", $PowerBB->_CONF['info_row']['title'],$MailInfo['message']);
          $MailInfo['message'] = str_replace("{forum_url}", $GetForumAdress,$MailInfo['message']);
          $user_url	= '<a href="http://'.$GetForumAdress.'index.php?page=profile&show=1&id='.$getmember_row['id'].'">http://'.$GetForumAdress.'index.php?page=profile&show=1&id='.$getmember_row['id'].'</a>';
          $MailInfo['message'] = str_replace("{user_url}",$user_url,$MailInfo['message']);

			if ($PowerBB->_CONF['info_row']['mailer']=='phpmail')
			{
			$send = $PowerBB->functions->send_this_php($getmember_row['email'],$MailInfo['title'],$username . $MailInfo['message'] . $Form_Massege,$PowerBB->_CONF['info_row']['send_email']);
			}
			elseif ($PowerBB->_CONF['info_row']['mailer']=='smtp')
			{
			$to = $getmember_row['email'];
			$fromname = $PowerBB->_CONF['info_row']['title'];
			$message = $MailInfo['message'].$Form_Massege;
			$subject = $MailInfo['title'];
			$from = $PowerBB->_CONF['info_row']['send_email'];
            $send = $PowerBB->functions->send_this_smtp($to,$fromname,$message,$subject,$from);
			}

		if ($send)
		{
		echo($PowerBB->_CONF['template']['_CONF']['lang']['Transmissions_were_successfully'] .' '. $getmember_row['username'] .' .. <br />');
		}
		}
		echo('</font></td></tr></table>');
		$current_page = $page;

		if ($send)
		{
		if($pagesnum != $current_page or $pagesnum > $current_page)
		{
		$n_page = $current_page+1;
		$seconds= $MailInfo['seconds'];
		$n_page = intval($n_page);
		echo('<br><table border="1" width="80%" cellspacing="0" cellpadding="0" bgcolor="#E5EBF0" style="border-collapse: collapse" align="center"><tr><td><font face="Tahoma" size="2">');
		$transition_click = $PowerBB->_CONF['template']['_CONF']['lang']['If_your_browser_does_not_support_automatic_transition_click_here'];
		//echo('<a href="index.php?page=emailsend&amp;mail=1&amp;start=1&amp;pag='.$n_page.'">'.$transition_click.'</a>');
		echo($PowerBB->_CONF['template']['_CONF']['lang']['Waiting_Time'].$seconds.$PowerBB->_CONF['template']['_CONF']['lang']['seconds']);
		echo('</font></td></tr></table>');

		$PowerBB->functions->redirect('index.php?page=emailsend&amp;mail=1&amp;start=1&amp;pag='.$n_page,$seconds);
		}
		else
		{
		$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Mail_has_been_sent_successfully']);
		$PowerBB->functions->redirect('index.php?page=emailsend&amp;mail=1&amp;main=1');

		}

		}
		else
		{
		echo('<br><table border="1" width="80%" cellspacing="0" cellpadding="0" bgcolor="#E5EBF0" style="border-collapse: collapse" align="center"><tr><td><font face="Tahoma" size="2">');
		echo($PowerBB->_CONF['template']['_CONF']['lang']['Failure_in_transmission']);
		echo('</font></td></tr></table>');
		}


	}


}

?>
