<?php

$CALL_SYSTEM = array();
$CALL_SYSTEM['SUBJECT'] = true;
$CALL_SYSTEM['PM'] 	= 	true;

(!defined('IN_PowerBB')) ? die() : '';



define('CLASS_NAME','PowerBBReportMOD');

include('common.php');
class PowerBBReportMOD
{
	function run()
	{
		global $PowerBB;

		if ($PowerBB->_GET['index'])
		{
			$this->_MemberReportIndex();
		}
		elseif ($PowerBB->_GET['start'])
		{
			$this->_MemberReportStart();
		}
		else
		{
			header("Location: index.php");
			exit;
		}

		$PowerBB->functions->GetFooter();
	}

	function _MemberReportIndex()
	{
		global $PowerBB;

		$PowerBB->functions->ShowHeader();

		if (!$PowerBB->_CONF['member_permission'])
     	{
     		$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Visitors_can_not_send_reports']);
     	}

     $PowerBB->_GET['subject_id'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_GET['subject_id'],'intval');
     $PowerBB->_GET['reply_id'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_GET['reply_id'],'intval');
     $PowerBB->_GET['count'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_GET['count'],'intval');

      $PowerBB->_POST['member'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['member'],'trim');
      $PowerBB->_POST['member'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['member'],'html');
      $PowerBB->_POST['member'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['member'],'sql');


        $PowerBB->template->assign('member',$PowerBB->_GET['member']);
     	$PowerBB->template->assign('subject_id',$PowerBB->_GET['subject_id']);
     	$PowerBB->template->assign('reply_id',$PowerBB->_GET['reply_id']);
     	$PowerBB->template->assign('count',$PowerBB->_GET['count']);



     	//////////

		$PowerBB->template->display('send_report');
	}

	function _MemberReportStart()
	{
		global $PowerBB;

		$PowerBB->functions->ShowHeader();

		if (!$PowerBB->_CONF['member_permission'])
     	{
     		$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Visitors_can_not_send_reports']);
     	}


		// Form
		$MemFormArr 			= 	array();
		$MemFormArr['where'] 	= 	array('username',$PowerBB->_CONF['member_row']['username']);

		$MemberFormInfo = $PowerBB->member->GetMemberInfo($MemFormArr);

     	//////////

     $PowerBB->_POST['reply_id'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['reply_id'],'intval');
     $PowerBB->_POST['subject_id'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['subject_id'],'intval');
     $PowerBB->_POST['count'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['count'],'intval');
     $PowerBB->_POST['id'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['id'],'intval');

      $PowerBB->_POST['text'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['text'],'trim');
      $PowerBB->_POST['member'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['member'],'html');
      $PowerBB->_POST['member'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['member'],'sql');


		if (empty($PowerBB->_POST['text']))
		{
			$PowerBB->_POST['text'] = ' [i]'.$PowerBB->_CONF['template']['_CONF']['lang']['no_report_reason'].'[/i]';
		}


      $AdminUsername = $PowerBB->DB->sql_query("SELECT  *   FROM " . $PowerBB->table['member'] . " WHERE usergroup = 1 ");

       while ($getstyle_row = $PowerBB->DB->sql_fetch_array($AdminUsername))
      {

     $Adress	= 	$PowerBB->functions->GetForumAdress();

		if ($PowerBB->_POST['reply_id'])
		{
			$count = '&count='.$PowerBB->_POST['count'].'#'.$PowerBB->_POST['reply_id'].'';
		}

            $url = $Adress.'index.php?page=topic&show=1&id='.$PowerBB->_POST['id'].$count;


			$PowerBB->_POST['text'] = str_ireplace('{39}',"'",$PowerBB->_POST['text']);
			$PowerBB->_POST['text'] = str_ireplace('cookie','**',$PowerBB->_POST['text']);
			$censorwords = preg_split('#[ \r\n\t]+#', $PowerBB->_CONF['info_row']['censorwords'], -1, PREG_SPLIT_NO_EMPTY);
			$PowerBB->_POST['text'] = str_ireplace($censorwords,'**', $PowerBB->_POST['text']);


           	$url = $PowerBB->Powerparse->replace($url);
           	$PowerBB->_POST['text'] = $PowerBB->Powerparse->replace($PowerBB->_POST['text']);
           	$PowerBB->_POST['text'] = $PowerBB->Powerparse->censor_words($PowerBB->_POST['text']);

				$MsgArr 			= 	array();
				$MsgArr['field'] 	= 	array();
				$MsgArr['field']['user_from'] 	= 	$PowerBB->_CONF['member_row']['username'];
				$MsgArr['field']['user_to'] 	= 	$getstyle_row['username'];
				$MsgArr['field']['title'] 		= 	$PowerBB->_CONF['template']['_CONF']['lang']['Report_on_the_participation_of_violation'];
                $MsgArr['field']['text'] 		= 	$PowerBB->_CONF['member_row']['username'] . '
                <br />' .$PowerBB->_CONF['template']['_CONF']['lang']['report_subjects_url'].'
                <br />' . $url . '
                <br /> '.$PowerBB->_CONF['template']['_CONF']['lang']['report_reason'].'
                <br /> '.$PowerBB->_POST['text'].'' ;
				$MsgArr['field']['date'] 		= 	$PowerBB->_CONF['now'];
				$MsgArr['field']['folder'] 		= 	'inbox';
				$Send = $PowerBB->core->Insert($MsgArr,'pm');

				$NumberArr 				= 	array();
				$NumberArr['username'] 	= 	$getstyle_row['username'];

				$Number = $PowerBB->pm->NewMessageNumber($NumberArr);

				$CacheArr 					= 	array();
				$CacheArr['field']			=	array();

				$CacheArr['field']['unread_pm'] 	= 	$Number;
				$CacheArr['where'] 					= 	array('username',$getstyle_row['username']);

				$Cache = $PowerBB->member->UpdateMember($CacheArr);

             $Report_name = '<br />
             ' . $PowerBB->_CONF['template']['_CONF']['lang']['Sender_Massege'] . $PowerBB->_CONF['member_row']['username'] . '<br />
             '.$PowerBB->_CONF['template']['_CONF']['lang']['Topic_'] . $url . ' <br />  :
             ' . $PowerBB->_CONF['template']['_CONF']['lang']['message'] . '<br />
             <br /><i>'.$PowerBB->_CONF['template']['_CONF']['lang']['no_report_reason'].'</i> ';

         	if ($PowerBB->_CONF['info_row']['mailer']=='phpmail')
				{
     	          $Report = $PowerBB->functions->send_this_php($getstyle_row['email'],$PowerBB->_POST['title'],$Report_name,$MemberFormInfo['email']);
	            }
				elseif ($PowerBB->_CONF['info_row']['mailer']=='smtp')
				{
				$to = $getstyle_row['email'];
				$fromname = $PowerBB->_CONF['info_row']['title'];
				$message = $Report_name;
				$subject = $PowerBB->_POST['title'];
				$from = $MemberFormInfo['email'];
                 $Send_Report = $PowerBB->functions->send_this_smtp($to,$fromname,$message,$subject,$from);
				}


     }

			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Thank_you_will_look_at_the_subject']);
		    $PowerBB->functions->redirect('index.php?page=topic&amp;show=1&amp;id=' . $PowerBB->_POST['id'] . $PowerBB->_CONF['template']['password']);



	}
}

?>
