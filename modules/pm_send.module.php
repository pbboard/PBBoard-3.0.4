<?php

(!defined('IN_PowerBB')) ? die() : '';

$CALL_SYSTEM					=	array();
$CALL_SYSTEM['PM'] 				= 	true;
$CALL_SYSTEM['ICONS'] 			= 	true;
$CALL_SYSTEM['TOOLBOX'] 		= 	true;
$CALL_SYSTEM['FILESEXTENSION'] 	= 	true;
$CALL_SYSTEM['ATTACH'] 			= 	true;

define('JAVASCRIPT_PowerCode',true);
define('CLASS_NAME','PowerBBPrivateMassegeSendMOD');
define('DONT_STRIPS_SLIASHES',true);
include('common.php');
class PowerBBPrivateMassegeSendMOD
{
	function run()
	{
		global $PowerBB;

		if (!$PowerBB->_CONF['info_row']['pm_feature'])
		{
            $PowerBB->functions->ShowHeader();
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['no_pm']);
		}

		/** Can't use the private massege system **/
		if (!$PowerBB->_CONF['rows']['group_info']['use_pm'])
		{
        	$PowerBB->functions->ShowHeader();
		     /** Visitor can't use the private massege system **/
			if (!$PowerBB->_CONF['member_permission'])
			{
				  $PowerBB->template->display('login');
	              $PowerBB->functions->error_stop();
			 }
		    else
            {
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Cant_use_pm']);
	        }
		}
		/** **/

			$SendPmArr 				= 	array();
			$SendPmArr['where']		=	array('username',$PowerBB->_CONF['member_row']['username']);

			$GetSendPmInfo = $PowerBB->member->GetMemberInfo($SendPmArr);

		$GroupInfo 				= 	array();
		$GroupInfo['where'] 	= 	array('id',$GetSendPmInfo['usergroup']);

		$GetMemberOptions = $PowerBB->core->GetInfo($GroupInfo,'group');

		/** Can't use the private massege system **/
		if ($GetSendPmInfo['posts']  < $GetMemberOptions['min_send_pm'])
		{
			$PowerBB->functions->ShowHeader($PowerBB->_CONF['template']['_CONF']['lang']['Send_PM']);
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_min_send_pm_1'].' ('.$GetMemberOptions['min_send_pm'].') '.$PowerBB->_CONF['template']['_CONF']['lang']['error_min_send_pm_2'].' ('.$GetSendPmInfo['posts'].')');
		}

		/** Action to send the masseges **/
		if ($PowerBB->_GET['send'])
		{
			/** Show a nice form :) **/
			if ($PowerBB->_GET['index'])
			{
				$this->_SendForm();
			}
			/** **/
			/** Start send the massege **/
			elseif ($PowerBB->_GET['start'])
			{
				$this->_StartSend();
			}

		$PowerBB->functions->GetFooter();

		}

			/** ADD A New attach in New Pm :) **/
			if ($PowerBB->_GET['add_attach_pm'])
			{
				$this->_Add_attach_Pm();
			}
			/** Start uplud and Add attach in New pm **/
			elseif ($PowerBB->_GET['add_start_pm'])
			{
				$this->_Star_Add_pm();
			}
			/** Start delete attach In Pm **/
			elseif ($PowerBB->_GET['delete_attach_pm'])
			{
				$this->_Delete_Attach_Pm();
			}

	}



	function _preview()
	{
		global $PowerBB;

		$PowerBB->functions->GetEditorTools();

		if (isset($PowerBB->_GET['username']))
		{
			$ToArr 				= 	array();
			$ToArr['where']		=	array('username',$PowerBB->_GET['username']);

			$GetToInfo = $PowerBB->member->GetMemberInfo($ToArr);

			if (!$GetToInfo)
			{
				$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Member_does_not_exist']);
			}
           $GetToInfo['away_msg'] = $PowerBB->functions->CleanVariable($GetToInfo['away_msg'],'html');

			$PowerBB->template->assign('SHOW_MSG',$GetToInfo['pm_senders']);
			$PowerBB->template->assign('SHOW_MSG1',$GetToInfo['away']);
			$PowerBB->template->assign('MSG',$GetToInfo['pm_senders_msg']);
			$PowerBB->template->assign('MSG1',$GetToInfo['away_msg']);
			$PowerBB->template->assign('to',$GetToInfo['username']);
		}
         //////////////////////
        $ExArr 						= 	array();
		$ExArr['order']				=	array();
		$ExArr['order']['field']	=	'id';
		$ExArr['order']['type']		=	'DESC';
		$ExArr['proc'] 				= 	array();
		$ExArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');

		$PowerBB->_CONF['template']['while']['ExList'] = $PowerBB->core->GetList($ExArr,'ex');
		// Finally , show the form :)
		$PowerBB->template->display('pm_send');
	}

	/**
	 * Show send form for the sender , Get the colors , fonts , icons and smiles list
	 */
	function _SendForm()
	{
		global $PowerBB;



		$PowerBB->functions->ShowHeader($PowerBB->_CONF['template']['_CONF']['lang']['Send_PM']);

		$PowerBB->functions->GetEditorTools();

		if (isset($PowerBB->_GET['username']))
		{
			$ToArr 				= 	array();
			$ToArr['get'] 		= 	'usergroup,username,pm_senders,pm_senders_msg,away,away_msg';
			$ToArr['where']		=	array('username',$PowerBB->_GET['username']);

			$GetToInfo = $PowerBB->member->GetMemberInfo($ToArr);

			if (!$GetToInfo)
			{
				$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Member_does_not_exist']);
			}

           //
			$SendPmArr 				= 	array();
			$SendPmArr['where']		=	array('username',$PowerBB->_GET['username']);

			$GetSendPm = $PowerBB->member->GetMemberInfo($SendPmArr);

			$GroupInfo 				= 	array();
			$GroupInfo['where'] 	= 	array('id',$GetSendPm['usergroup']);

			$GetMemberGroup = $PowerBB->core->GetInfo($GroupInfo,'group');
			$user_name = $PowerBB->_GET['username'];
			$PrivateMassegeNumber = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['pm'] . " WHERE user_to = '$user_name' and user_read = '' and folder = 'inbox'"));
			if (!$GetMemberGroup['max_pm']== '0')
            {
				if ($PrivateMassegeNumber > $GetMemberGroup['max_pm'])
				{
				$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Sorry_Consumed_this_member_limit_messages']);
				}
           }


		// feltr away msg
		   $GetToInfo['away_msg'] = str_ireplace('{39}',"'",$GetToInfo['away_msg']);
	       $GetToInfo['away_msg'] = str_ireplace('cookie','**',$GetToInfo['away_msg']);
	       $censorwords = preg_split('#[ \r\n\t]+#', $PowerBB->_CONF['info_row']['censorwords'], -1, PREG_SPLIT_NO_EMPTY);
	       $GetToInfo['away_msg'] = str_ireplace($censorwords,'**', $GetToInfo['away_msg']);
	       $GetToInfo['away_msg'] = str_replace('&amp;','&',$GetToInfo['away_msg']);
	       //
          $GetToInfo['away_msg'] = $PowerBB->functions->CleanVariable($GetToInfo['away_msg'],'html');

			$PowerBB->template->assign('SHOW_MSG',$GetToInfo['pm_senders']);
			$PowerBB->template->assign('SHOW_MSG1',$GetToInfo['away']);
			$PowerBB->template->assign('MSG',$GetToInfo['pm_senders_msg']);
			$PowerBB->template->assign('MSG1',$GetToInfo['away_msg']);
			$PowerBB->template->assign('to',$GetToInfo['username']);
		}
         //////////////////////
        $ExArr 						= 	array();
		$ExArr['order']				=	array();
		$ExArr['order']['field']	=	'id';
		$ExArr['order']['type']		=	'DESC';
		$ExArr['proc'] 				= 	array();
		$ExArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');

		$PowerBB->_CONF['template']['while']['ExList'] = $PowerBB->core->GetList($ExArr,'ex');

        // show Custom_bbcode List
		$Custom_bbcodeArr 					= 	array();
		$Custom_bbcodeArr['order']			=	array();
		$Custom_bbcodeArr['order']['field']	=	'id';
		$Custom_bbcodeArr['order']['type']	=	'DESC';
		$Custom_bbcodeArr['proc'] 			= 	array();
		$Custom_bbcodeArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');

		$PowerBB->_CONF['template']['while']['Custom_bbcodesList'] = $PowerBB->core->GetList($Custom_bbcodeArr,'custom_bbcode');

		// Get the attachment information

			$AttachArr 							= 	array();
			$AttachArr['where']					= 	array();
			$AttachArr['where'][0] 				=	array();
			$AttachArr['where'][0]['name'] 		=	'pm_id';
			$AttachArr['where'][0]['oper'] 		=	'=';
			$AttachArr['where'][0]['value'] 	=	'-'.$PowerBB->_CONF['member_row']['id'];

			$PowerBB->_CONF['template']['while']['PmAttachList'] = $PowerBB->core->GetList($AttachArr,'attach');

		// Finally , show the form :)
		$PowerBB->template->display('pm_send');

	}

	/**
	 * Check if the necessary informations is not empty ,
	 * and some checks about the sender and resiver then send the massege .
	 */
	function _StartSend()
	{
		global $PowerBB;

$PowerBB->_POST['title'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['title'],'trim');
$PowerBB->_POST['title'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['title'],'sql');
$PowerBB->_POST['title'] = strip_tags($PowerBB->_POST['title']);
$PowerBB->_POST['to'][0] = strip_tags($PowerBB->_POST['to'][0]);

$PowerBB->_POST['to'][0] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['to'][0],'trim');
$PowerBB->_POST['to'][0] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['to'][0],'html');
$PowerBB->_POST['to'][0] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['to'][0],'sql');

 		// Get the attachment information

			$AttachArr 							= 	array();
			$AttachArr['where']					= 	array();
			$AttachArr['where'][0] 				=	array();
			$AttachArr['where'][0]['name'] 		=	'pm_id';
			$AttachArr['where'][0]['oper'] 		=	'=';
			$AttachArr['where'][0]['value'] 	=	'-'.$PowerBB->_CONF['member_row']['id'];

			$AttachArr['order'] 				=	 array();
			$AttachArr['order']['field'] 		= 	'id';
			$AttachArr['order']['type'] 	    = 	'DESC';
			$PowerBB->_CONF['template']['while']['PmAttachList'] = $PowerBB->core->GetList($AttachArr,'attach');



	   if ($PowerBB->_POST['preview'])
       {

		  $PowerBB->_POST['text'] = str_replace('target="_blank" ','',$PowerBB->_POST['text']);

    	    $PowerBB->functions->ShowHeader($PowerBB->_CONF['template']['_CONF']['lang']['Send_PM']);
            $previewtext = $PowerBB->_POST['text'];
            $previewtext = $PowerBB->Powerparse->replace($previewtext);
            $PowerBB->Powerparse->replace_smiles($previewtext);
            $previewtext = $PowerBB->Powerparse->censor_words($previewtext);


            $PowerBB->template->assign('to',$PowerBB->_POST['to'][0]);
            $PowerBB->template->assign('send_title',stripslashes($PowerBB->functions->CleanVariable($PowerBB->_POST['title'],'html')));
            $PowerBB->template->assign('preview',stripslashes($previewtext));
       	    $PowerBB->template->assign('view_preview',stripslashes($PowerBB->_POST['text']));
            $PowerBB->template->assign('prev',$PowerBB->Powerparse->replace_htmlentities(stripslashes($PowerBB->functions->CleanVariable($PowerBB->_POST['text'],'html'))));

			$this->_preview();

        }
      else
       {

				$PowerBB->functions->ShowHeader();

                $PowerBB->functions->AddressBar('<a href="index.php?page=pm_list&list=1&folder=inbox"> ' .$PowerBB->_CONF['template']['_CONF']['lang']['Private_Messages'] .'</a>'. $PowerBB->_CONF['info_row']['adress_bar_separate'] . $PowerBB->_CONF['template']['_CONF']['lang']['execution_add_pm']);
				if (empty($PowerBB->_POST['to'][0]))
				{
					$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['You_must_type_username']);
				}

				if (empty($PowerBB->_POST['title']))
				{
					$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['You_must_type_pm_title']);
				}

				if (empty($PowerBB->_POST['text']))
				{
					$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['You_must_type_pm_text']);
				}

				$size = sizeof($PowerBB->_POST['to']);

				$success 	= 	array();
				$fail		=	array();

		     	if ($size > 0)
		     	{
		     		$x = 0;

		     		while ($x < $size)
		     		{
		     			// Ensure there is no repeat
		     			if (in_array($PowerBB->_POST['to'][$x],$success)
		     				or in_array($PowerBB->_POST['to'][$x],$fail))
		     			{
		     				$x += 1;

		     				continue;
		     			}

		     		   $PowerBB->_POST['to'][$x] = strip_tags($PowerBB->_POST['to'][$x]);
                       $PowerBB->_POST['to'][$x] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['to'][$x],'trim');
                       $PowerBB->_POST['to'][$x] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['to'][$x],'html');
                       $PowerBB->_POST['to'][$x] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['to'][$x],'sql');

						$ToArr 				= 	array();
						$ToArr['get'] 		= 	'usergroup,username,autoreply,autoreply_title,autoreply_msg';
						$ToArr['where']		=	array('username',$PowerBB->_POST['to'][$x]);

						$GetToInfo = $PowerBB->member->GetMemberInfo($ToArr);

						if (!$GetToInfo
							and $size > 1)
						{
							$fail[] = $PowerBB->_POST['to'][$x];

							unset($GetToInfo,$GetMemberOptions);

							$x += 1;

							continue;
						}
						elseif (!$GetToInfo
								and $size == 1)
						{
							$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Member_does_not_exist']);
						}

                         // Report mail .. New Massege
						$MemberArr 			= 	array();
						$MemberArr['where'] 	= 	array('username',$PowerBB->_POST['to'][$x]);

						$MemberInfo = $PowerBB->core->GetInfo($MemberArr,'member');


						$MemberFormArr 			= 	array();
						$MemberFormArr['where'] 	= 	array('username',$PowerBB->_CONF['member_row']['username']);

						$MemberFormInfo = $PowerBB->member->GetMemberInfo($MemberFormArr);

                		$Adress = 	$PowerBB->functions->GetForumAdress();

						if ($MemberInfo['pm_emailed'] == '1')
						{
						 $title = $PowerBB->_CONF['template']['_CONF']['lang']['you_have_new_pm'];
						 $username = $PowerBB->_CONF['template']['_CONF']['lang']['hello_your']  . $PowerBB->_POST['to'][$x].'<br>';
 			         	 $Form_Massege =  $PowerBB->_CONF['member_row']['username'].$PowerBB->_CONF['template']['_CONF']['lang']['Has_written_a_new_Pm'] .
                         $PowerBB->_CONF['template']['_CONF']['lang']['Please_login_on_the_following_link_to_access_the_pm'].'<a target="_blank" href="'. $Adress . 'index.php?page=pm_list&list=1&folder=inbox">'. $Adress . 'index.php?page=pm_list&list=1&folder=inbox</a>'. $PowerBB->_CONF['template']['_CONF']['lang']['greetings_Management_Forum']  . $PowerBB->_CONF['info_row']['title'] .'<br>' . $Adress . 'index.php';

				         	if ($PowerBB->_CONF['info_row']['mailer']=='phpmail')
							{
							 $Send = $PowerBB->functions->send_this_php($MemberInfo['email'],$title,$username.$Form_Massege,$PowerBB->_CONF['info_row']['send_email']);
				            }
							elseif ($PowerBB->_CONF['info_row']['mailer']=='smtp')
							{
							$to = $MemberInfo['email'];
							$fromname = $PowerBB->_CONF['info_row']['title'];
							$message = $username . $Form_Massege;
							$subject = $title;
							$from = $PowerBB->_CONF['info_row']['send_email'];
                            $Send = $PowerBB->functions->send_this_smtp($to,$fromname,$message,$subject,$from);
							}

                        }

						$GroupInfo 				= 	array();
						$GroupInfo['where'] 	= 	array('id',$GetToInfo['usergroup']);

						$GetMemberOptions = $PowerBB->core->GetInfo($GroupInfo,'group');

						if (!$GetMemberOptions['resive_pm']
							and $size > 1)
						{
							$fail[] = $PowerBB->_POST['to'][$x];

							unset($GetToInfo,$GetMemberOptions);

							$x += 1;

							continue;
						}
						elseif (!$GetMemberOptions['resive_pm']
								and $size == 1)
						{
							$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Sorry_this_member_can_not_receive_private_messages']);
						}

						if ($GetMemberOptions['max_pm'] > 0)
						{
							  $user_name = $GetToInfo['username'];
                              $PrivateMassegeNumber = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['pm'] . " WHERE user_to = '$user_name' and user_read = '' and folder = 'inbox'"));

							if ($PrivateMassegeNumber > $GetMemberOptions['max_pm']
								and $size > 1)
							{
								$fail[] = $PowerBB->_POST['to'][$x];

								unset($GetToInfo,$GetMemberOptions);

								$x += 1;

								continue;
							}
							elseif ($PrivateMassegeNumber > $GetMemberOptions['max_pm']
									and $size == 1)
							{
								$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Sorry_Consumed_this_member_limit_messages']);
							}
						}

						// Filter Words
			     	   $censorwords = preg_split('#[ \r\n\t]+#', $PowerBB->_CONF['info_row']['censorwords'], -1, PREG_SPLIT_NO_EMPTY);
	                   $PowerBB->_POST['title'] = str_ireplace($censorwords,'**', $PowerBB->_POST['title']);
	                   $PowerBB->_POST['text'] = str_ireplace($censorwords,'**', $PowerBB->_POST['text']);
		               $PowerBB->_POST['text'] = str_replace('&amp;','&',$PowerBB->_POST['text']);
		               $PowerBB->_POST['text'] = str_ireplace('{39}',"'",$PowerBB->_POST['text']);
                       $PowerBB->_POST['text'] = str_ireplace('cookie','**',$PowerBB->_POST['text']);


	                   //

						$MsgArr 				= 	array();
						$MsgArr['get_id']		=	true;
						$MsgArr['field']		=	array();

						$MsgArr['field']['user_from'] 	= 	$PowerBB->_CONF['member_row']['username'];
						$MsgArr['field']['user_to'] 	= 	$GetToInfo['username'];
						$MsgArr['field']['title'] 		= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['title'],'html');
						$MsgArr['field']['text'] 		= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['text'],'nohtml');
						$MsgArr['field']['date'] 		= 	$PowerBB->_CONF['now'];
						$MsgArr['field']['icon'] 		= 	$PowerBB->_POST['icon'];
						$MsgArr['field']['folder'] 		= 	'inbox';

						$Send = $PowerBB->pm->InsertMassege($MsgArr);

						if ($Send)
						{
				     		// Upload files
						   $GetAttachArr 					= 	array();
						   $GetAttachArr['where'] 			= 	array('pm_id','-'.$PowerBB->_CONF['member_row']['id']);
						   $Attachinfo = $PowerBB->core->GetInfo($GetAttachArr,'attach');

				     		if ($Attachinfo)
				     		{

								$SubjectArr 							= 	array();
								$SubjectArr['field'] 					= 	array();
								$SubjectArr['field']['attach_subject'] 	= 	'1';
								$SubjectArr['where'] 					= 	array('id',$PowerBB->subject->id);

								$update = $PowerBB->subject->UpdateSubject($SubjectArr);

							//	Update All Attach
							 $member_id_Attach = '-'.$PowerBB->_CONF['member_row']['id'];
		                     $getAttach = $PowerBB->DB->sql_query("SELECT  *   FROM " . $PowerBB->table['attach'] . " WHERE pm_id = '$member_id_Attach' ");
		                     while ($getAttach_row = $PowerBB->DB->sql_fetch_array($getAttach))
		                      {
								// Count a new download
								$UpdateArr 						= 	array();
								$UpdateArr['field'] 			= 	array();
								$UpdateArr['field']['pm_id'] 	= 	$PowerBB->pm->id;
								$UpdateArr['field']['time'] 	= 	$PowerBB->_CONF['now'];
								$UpdateArr['where'] 			= 	array('id',$getAttach_row['id']);

		                 		$update = $PowerBB->attach->UpdateAttach($UpdateArr);
		                     }
		     		      }

		     		//////////

		     				//////////

							$MsgArr 				= 	array();
							$MsgArr['field']		=	array();

						    $MsgArr['field']['user_from'] 	= $PowerBB->_CONF['member_row']['username'];
						    $MsgArr['field']['user_to'] 	= $GetToInfo['username'];
							$MsgArr['field']['title'] 		= $PowerBB->_POST['title'];
							$MsgArr['field']['text'] 		= $PowerBB->_POST['text'];
							$MsgArr['field']['date'] 		= $PowerBB->_CONF['now'];
							$MsgArr['field']['icon'] 		= $PowerBB->_POST['icon'];
							$MsgArr['field']['folder'] 		= 'sent';

							$SentBox = $PowerBB->core->Insert($MsgArr,'pm');

							if ($SentBox)
							{
								/** Auto reply **/
								if ($GetToInfo['autoreply'])
								{
									$MsgArr 			= 	array();
									$MsgArr['field'] 	= 	array();

									$MsgArr['field']['user_from'] 	= 	$GetToInfo['username'];
									$MsgArr['field']['user_to'] 	= 	$PowerBB->_CONF['member_row']['username'];
									$MsgArr['field']['title'] 		= 	$PowerBB->_CONF['template']['_CONF']['lang']['_IVR_'] . $GetToInfo['autoreply_title'];
									$MsgArr['field']['text'] 		= 	$GetToInfo['autoreply_msg'];
									$MsgArr['field']['date'] 		= 	$PowerBB->_CONF['now'];
									$MsgArr['field']['icon'] 		= "look/images/icons/i1.gif";
									$MsgArr['field']['folder'] 		= 	'inbox';

									$AutoReply = $PowerBB->core->Insert($MsgArr,'pm');
								}

								$NumberArr 				= 	array();
								$NumberArr['username'] 	= 	$GetToInfo['username'];

								$Number = $PowerBB->pm->NewMessageNumber($NumberArr);

								$CacheArr 					= 	array();
								$CacheArr['field']			=	array();

								$CacheArr['field']['unread_pm'] 	= 	$Number;
								$CacheArr['where'] 					= 	array('username',$GetToInfo['username']);

								$Cache = $PowerBB->member->UpdateMember($CacheArr);

								if ($Cache)
								{
									$success[] = $PowerBB->_POST['to'][$x];
								}
							}
						}

						unset($GetToInfo,$GetMemberOptions);

						$x += 1;
					}
		     	}
		     	else
		     	{
		     		$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		     	}

		     	$sucess_number 	= 	sizeof($success);
		     	$fail_numer		=	sizeof($fail);

		     	if ($sucess_number == $size)
		     	{
		     		$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['pm_send_successfully']);
		     	}
		     	elseif ($fail_number == $size)
		     	{
		     		$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['no_send_pm']);
		     	}
		     	elseif ($sucess_number < $size)
		     	{
		     		$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['pm_send_successfully_to_some']);
		     	}
                $PowerBB->functions->redirect('index.php?page=pm_list&amp;list=1&amp;folder=inbox');

     	}
	}

    function _Add_attach_Pm()
	{
		global $PowerBB;

		// Get the attachment information

			$AttachArr 							= 	array();
			$AttachArr['where']					= 	array();
			$AttachArr['where'][0] 				=	array();
			$AttachArr['where'][0]['name'] 		=	'pm_id';
			$AttachArr['where'][0]['oper'] 		=	'=';
			$AttachArr['where'][0]['value'] 	=	'-'.$PowerBB->_CONF['member_row']['id'];

			$AttachArr['order'] 				=	 array();
			$AttachArr['order']['field'] 		= 	'id';
			$AttachArr['order']['type'] 	    = 	'DESC';
			$PowerBB->_CONF['template']['while']['PmAttachList'] = $PowerBB->core->GetList($AttachArr,'attach');


		////////

		$ExArr 						= 	array();
		$ExArr['order']				=	array();
		$ExArr['order']['field']	=	'id';
		$ExArr['order']['type']		=	'DESC';
		$ExArr['proc'] 				= 	array();
		$ExArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');

		$PowerBB->_CONF['template']['while']['ExList'] = $PowerBB->core->GetList($ExArr,'ex');

		// Finally , show the form :)
		$PowerBB->template->assign('section',$PowerBB->_GET['section']);
		$PowerBB->template->display('add_attach_pm');


	}

	function _Star_Add_pm()
	{
		global $PowerBB;


 		if (empty($PowerBB->_FILES['files']['name']))
		{
		$PowerBB->functions->error_no_foot($PowerBB->_CONF['template']['_CONF']['lang']['You_do_not_choose_any_file']);
		}


		$files_error	=	array();
		$files_success	=	array();
		$files_number 	= 	sizeof($PowerBB->_FILES['files']['name']);
		$stop			=	false;

		// All of these variables use for loop and arrays
		$x = 0; // For the main loop
		$y = 0; // For error array
		$z = 0; // For success array

     	 while ($files_number > $x)
     	 {

	         if ($files_number == '1')
			{
		         if (empty($PowerBB->_FILES['files']['name'][$x]))
				{
					$PowerBB->functions->error_no_foot($PowerBB->_CONF['template']['_CONF']['lang']['You_do_not_choose_any_file']);
				}
			}

			// Check if the extenstion is allowed or not
			$ext = $PowerBB->functions->GetFileExtension($PowerBB->_FILES['files']['name'][$x]);

			$IsExtensionArr 			= 	array();
			$IsExtensionArr['where'] 	= 	array('Ex',$ext);

			$Isextension = $PowerBB->core->Is($IsExtensionArr,'ex');

			if (!$Isextension)
			{
			$PowerBB->functions->stop_ajax($PowerBB->_CONF['template']['_CONF']['lang']['Not_available'].' '. $ext .' ' .$PowerBB->_CONF['template']['_CONF']['lang']['Can_you_raise_file_extension']);
			}

	        // Get the extension of the file
			$ExtArr 			= 	array();
			$ExtArr['where'] 	= 	array('Ex',$ext);

			$extension = $PowerBB->core->GetInfo($ExtArr,'ex');

	        // Check if the extenstion max size is allowed or not
	        $size = ceil(($PowerBB->_FILES['files']['size'][$x] / 1024));

			if ($size > $extension['max_size'])
			{
              $PowerBB->functions->error_no_foot($PowerBB->_CONF['template']['_CONF']['lang']['max_size_extension1'].'('. $ext .')'.$PowerBB->_CONF['template']['_CONF']['lang']['max_size_extension2'].$extension['max_size'].$PowerBB->_CONF['template']['_CONF']['lang']['max_size_extension3']);
			}




           		if (!empty($PowerBB->_FILES['files']['name'][$x]))
				{
				if (!$Isextension)
				{
	              $PowerBB->functions->error_no_foot($PowerBB->_CONF['template']['_CONF']['lang']['Can_you_raise_file_extension'].'('. $ext .')');
				}


	            if ( stristr($PowerBB->_FILES['files']['name'][$x],'.php') )
	             {
	              $PowerBB->functions->error_no_foot($PowerBB->_CONF['template']['_CONF']['lang']['Can_you_raise_file_extension']);
				}
				if ( stristr($PowerBB->_FILES['files']['name'][$x],'.php3') )
	             {
	              $PowerBB->functions->error_no_foot($PowerBB->_CONF['template']['_CONF']['lang']['Can_you_raise_file_extension']);
				}
				if ( stristr($PowerBB->_FILES['files']['name'][$x],'.phtml') )
	             {
	              $PowerBB->functions->error_no_foot($PowerBB->_CONF['template']['_CONF']['lang']['Can_you_raise_file_extension']);
				}
				if ( stristr($PowerBB->_FILES['files']['name'][$x],'.pl') )
	             {
	              $PowerBB->functions->error_no_foot($PowerBB->_CONF['template']['_CONF']['lang']['Can_you_raise_file_extension']);
				}
				if ( stristr($PowerBB->_FILES['files']['name'][$x],'.cgi') )
	             {
	              $PowerBB->functions->error_no_foot($PowerBB->_CONF['template']['_CONF']['lang']['Can_you_raise_file_extension']);
				}
				if ( stristr($PowerBB->_FILES['files']['name'][$x],'.asp') )
	             {
	              $PowerBB->functions->error_no_foot($PowerBB->_CONF['template']['_CONF']['lang']['Can_you_raise_file_extension']);
				}
				if ( stristr($PowerBB->_FILES['files']['name'][$x],'.3gp') )
	             {
	              $PowerBB->functions->error_no_foot($PowerBB->_CONF['template']['_CONF']['lang']['Can_you_raise_file_extension']);
				}



             $Random = $PowerBB->functions->RandomCode() .$PowerBB->_FILES['files']['name'][$x];

                     $ext = str_replace('.','',$ext);


                // Insert attachment to the database
				$AttachArr 							= 	array();
				$AttachArr['field'] 				= 	array();
				$AttachArr['field']['filename'] 	= 	$PowerBB->_FILES['files']['name'][$x];
				$AttachArr['field']['filepath'] 	= 	$PowerBB->_CONF['info_row']['download_path'] . '/' . $Random;
				$AttachArr['field']['filesize'] 	= 	$PowerBB->_FILES['files']['size'][$x];
				$AttachArr['field']['extension'] 	= 	$ext;
				$AttachArr['field']['pm_id']		=	'-'.$PowerBB->_CONF['member_row']['id'];
				$AttachArr['field']['u_id']		    =	$PowerBB->_CONF['member_row']['id'];
				$AttachArr['field']['time']		    =	$PowerBB->_CONF['now'];


				$InsertAttach = $PowerBB->core->Insert($AttachArr,'attach');

				if ($InsertAttach)
				{

					// Kill XSS
					$PowerBB->functions->CleanVariable($InsertAttach,'html');
					// Kill SQL Injection
					$PowerBB->functions->CleanVariable($InsertAttach,'sql');


			         move_uploaded_file($PowerBB->_FILES['files']['tmp_name'][$x] , $PowerBB->_CONF['info_row']['download_path'] . '/' . $Random);



                }

             }

			$x += 1;
			}

		//$PowerBB->functions->redirect2('index.php?page=pm_send&amp;add_attach_pm=1');


 		// Get the attachment information

			$AttachArr 							= 	array();
			$AttachArr['where']					= 	array();
			$AttachArr['where'][0] 				=	array();
			$AttachArr['where'][0]['name'] 		=	'pm_id';
			$AttachArr['where'][0]['oper'] 		=	'=';
			$AttachArr['where'][0]['value'] 	=	'-'.$PowerBB->_CONF['member_row']['id'];

			$AttachArr['order'] 				=	 array();
			$AttachArr['order']['field'] 		= 	'id';
			$AttachArr['order']['type'] 	    = 	'DESC';
			$PowerBB->_CONF['template']['while']['PmAttachList'] = $PowerBB->core->GetList($AttachArr,'attach');


		 $PowerBB->template->display('add_attach_pm');


	 }

	 function _Delete_Attach_Pm()
	{
		global $PowerBB;

       	$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');

		if (empty($PowerBB->_GET['id']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}


		  $GetAttachArr 					= 	array();
		  $GetAttachArr['where'] 			= 	array('id',$PowerBB->_GET['id']);
		   $Attachinfo = $PowerBB->core->GetInfo($GetAttachArr,'attach');

		   	if (file_exists($Attachinfo['filepath']))
		      {
			   $del = unlink($Attachinfo['filepath']);
              }


	        // Delete attachment to the database
			$AttachArr 							= 	array();
			$AttachArr['name'] 	        		=  	'id';
	        $AttachArr['where'] 		    	= 	array('id',$PowerBB->_GET['id']);

			$DeleteAttach = $PowerBB->core->Deleted($AttachArr,'attach');

		 if($DeleteAttach)
		  {
			// Finally , Delete the Attach
		   // $PowerBB->functions->redirect2('index.php?page=pm_send&amp;add_attach_pm=1');

		   // Get the attachment information

			$AttachArr 							= 	array();
			$AttachArr['where']					= 	array();
			$AttachArr['where'][0] 				=	array();
			$AttachArr['where'][0]['name'] 		=	'pm_id';
			$AttachArr['where'][0]['oper'] 		=	'=';
			$AttachArr['where'][0]['value'] 	=	'-'.$PowerBB->_CONF['member_row']['id'];

			$AttachArr['order'] 				=	 array();
			$AttachArr['order']['field'] 		= 	'id';
			$AttachArr['order']['type'] 	    = 	'DESC';
			$PowerBB->_CONF['template']['while']['PmAttachList'] = $PowerBB->core->GetList($AttachArr,'attach');

		   $PowerBB->template->display('add_attach_pm');
		  }

	}


}

?>

