<?php

(!defined('IN_PowerBB')) ? die() : '';
define('IN_ADMIN',true);
$CALL_SYSTEM					=	array();
$CALL_SYSTEM['PM'] 				= 	true;
$CALL_SYSTEM['ICONS'] 			= 	true;
$CALL_SYSTEM['TOOLBOX'] 		= 	true;
$CALL_SYSTEM['FILESEXTENSION'] 	= 	true;
$CALL_SYSTEM['ATTACH'] 			= 	true;
$CALL_SYSTEM['CACHE'] 		= 	true;
$CALL_SYSTEM['MODERATORS'] 	= 	true;

define('JAVASCRIPT_PowerCode',true);



define('CLASS_NAME','PowerBBPrivateMassegeShowMOD');

include('../common.php');
class PowerBBPrivateMassegeShowMOD
{
		var $CheckMember;

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
			if (!in_array($PowerBB->_CONF['rows']['member_row']['id'], preg_split('#\s*,\s*#s', $PowerBB->superadministrators, -1, PREG_SPLIT_NO_EMPTY)))
			{
			 $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
			}

			if ($PowerBB->_GET['pm'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_ControlMain();
				}
				elseif ($PowerBB->_GET['showuserpm'])
				{
					$this->_ShowUserStart();
				}
				elseif ($PowerBB->_GET['show'])
				{
					$this->_ShowStart();
				}
				/** Read a massege **/
				elseif ($PowerBB->_GET['read'])
				{
					$this->_ShowMassege();
				}
				/** Delete private massege **/
			  elseif ($PowerBB->_GET['del'])
			   {
				$this->_DeletePrivateMassege();
			   }
			  /** Send private massege **/
			  elseif ($PowerBB->_GET['send_pm'])
			   {
				$this->_SendMain();
			   }
			  /** Start send the massege **/
				elseif ($PowerBB->_GET['start'])
				{
					$this->_StartSend();
				}
				elseif ($PowerBB->_GET['delet_all_pm'])
				{
					$this->_StartDeletAllPm();
				}
				/** **/
			}


		$PowerBB->template->display('footer');
		}

	}

	function _ControlMain()
	{
		global $PowerBB;

		$PowerBB->template->display('pm_main');
	}

	function _ShowUserStart()
	{
		global $PowerBB;

		$PowerBB->_GET['count'] = (!isset($PowerBB->_GET['count'])) ? 0 : $PowerBB->_GET['count'];
		$PowerBB->_GET['count'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['count'],'intval');

        if (empty($PowerBB->_POST['username']))
		{
        $username = $PowerBB->_GET['username'];
		}
		else
		{
        $username = $PowerBB->_POST['username'];
		}
		if (!$username)
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_write_the_username']);
		}

		//
		if (!$PowerBB->member->IsMember(array('where' => array('username',$username))))
		{
         	$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['username_does_not_exist']);
		}


        // show user Private Massege

			$PrivateMassegeNumber = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['pm'] . " WHERE user_to = '$username' and folder = 'sent'"));

		//////////

		$MsgArr = array();

		$MsgArr['username'] 			= 	$username;

	   // Pager setup
		$MsgArr['pager'] 				= 	array();
		$MsgArr['pager']['total']		= 	$PrivateMassegeNumber;
		$MsgArr['pager']['perpage'] 	= 	$PowerBB->_CONF['info_row']['subject_perpage'];
		$MsgArr['pager']['count'] 		= 	$PowerBB->_GET['count'];
		$MsgArr['pager']['location'] 	= 	'index.php?page=pm&amp;showuserpm=1&amp;pm=1&amp;username='.$username;
		$MsgArr['pager']['var'] 		= 	'count';

		$MsgArr['proc'] 				= 	array();
		$MsgArr['proc']['date'] 		= 	array('method'=>'date','store'=>'date','type'=>$PowerBB->_CONF['info_row']['timesystem']);

		$MsgArr['order']				=	array();
		$MsgArr['order']['field']		=	'id';
		$MsgArr['order']['type']		=	'DESC';

		$GetMassegeList = $PowerBB->pm->GetSentList($MsgArr);


		$PowerBB->_CONF['template']['while']['MassegeList'] = $GetMassegeList;

      		 $PowerBB->template->assign('Msg_Num',$PrivateMassegeNumber);
			$PowerBB->template->assign('Dousername',$username);

        if ($PrivateMassegeNumber > $PowerBB->_CONF['info_row']['subject_perpage'])
        {
		$PowerBB->template->assign('pager',$PowerBB->pager->show());
        }


		$PowerBB->template->display('pm_user_show');
	}


    	/**
	 * Get a massege information to show it
	 */
	function _ShowMassege()
	{
		global $PowerBB;

		if (empty($PowerBB->_GET['id']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}

		$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');

		$MsgArr 			= 	array();
		$MsgArr['id'] 		= 	$PowerBB->_GET['id'];
		$MsgArr['username'] = 	$PowerBB->_GET['username'];
		$MsgArr['proc'] 				= 	array();

		$PowerBB->_CONF['template']['MassegeRow'] = $PowerBB->pm->GetPrivateMassegeInfo($MsgArr);

		    $PowerBB->_CONF['template']['MassegeRow']['text'] = $PowerBB->Powerparse->replace($PowerBB->_CONF['template']['MassegeRow']['text']);
            $PowerBB->Powerparse->replace_smiles($PowerBB->_CONF['template']['MassegeRow']['text']);


		if (!$PowerBB->_CONF['template']['MassegeRow'])
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Massege_requested_does_not_exist']);
		}

		if (is_numeric($PowerBB->_CONF['template']['MassegeRow']['date']))
		{
			$MassegeDate = $PowerBB->functions->_date($PowerBB->_CONF['template']['MassegeRow']['date']);
			//$MassegeTime = $PowerBB->functions->_time($PowerBB->_CONF['template']['MassegeRow']['date']);

			$PowerBB->_CONF['template']['MassegeRow']['date'] = $MassegeDate;
		}

		$PowerBB->template->display('pm_show');
	}

	function _DeletePrivateMassege()
	{
		global $PowerBB;

		$PowerBB->template->display('header');

				 $DelArr				=	array();
		         $DelArr['where'] 	= 	array('id',$PowerBB->_GET['id']);

			$del = $PowerBB->pm->DeletePrivateMessage($DelArr);



			if ($del)
			{
				// Recount the number of new messages after delete this message
				$NumArr 						= 	array();
				$NumArr['where'] 				= 	array();

				$NumArr['where'][0] 			= 	array();
				$NumArr['where'][0]['name'] 	= 	'user_to';
				$NumArr['where'][0]['oper'] 	= 	'=';
				$NumArr['where'][0]['value'] 	= 	$PowerBB->_GET['user_to'];

				$NumArr['where'][1] 			= 	array();
				$NumArr['where'][1]['con'] 		= 	'AND';
				$NumArr['where'][1]['name'] 	= 	'folder';
				$NumArr['where'][1]['oper'] 	= 	'=';
				$NumArr['where'][1]['value'] 	= 	'inbox';

				$NumArr['where'][2] 			= 	array();
				$NumArr['where'][2]['con'] 		= 	'AND';
				$NumArr['where'][2]['name'] 	= 	'folder';
				$NumArr['where'][2]['oper'] 	= 	'=';
				$NumArr['where'][2]['value'] 	= 	'sent';

				$NumArr['where'][3] 			= 	array();
				$NumArr['where'][3]['con'] 		= 	'AND';
				$NumArr['where'][3]['name'] 	= 	'user_read';
				$NumArr['where'][3]['oper'] 	= 	'=';
				$NumArr['where'][3]['value'] 	= 	'0';

				$Number = $PowerBB->pm->GetPrivateMassegeNumber($NumArr);

				$CacheArr 					= 	array();
				$CacheArr['field']			=	array();

				$CacheArr['field']['unread_pm'] 	= 	$Number;
				$CacheArr['where'] 					= 	array('username',$PowerBB->_GET['user_to']);

				$Cache = $PowerBB->member->UpdateMember($CacheArr);

			}


	          $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Massege_has_been_deleted_successfully']);
			 $PowerBB->functions->redirect('index.php?page=pm&pm=1&main=1');


	}


	function _ShowStart()
	{
		global $PowerBB;

		$PowerBB->_GET['count'] = (!isset($PowerBB->_GET['count'])) ? 0 : $PowerBB->_GET['count'];
		$PowerBB->_GET['count'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['count'],'intval');
		$PrivateMassegeNumber = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['pm'] . " WHERE folder = 'inbox'"));
        // show user Private Massege


		$MsgArr 						= 	array();
		$MsgArr['where'] 				= 	array();

		$MsgArr['where'][0] 			= 	array();
		$MsgArr['where'][0]['name'] 	= 	'folder';
		$MsgArr['where'][0]['oper'] 	= 	'=';
		$MsgArr['where'][0]['value'] 	= 	'inbox';

		// Order data
		$MsgArr['order']				=	array();
		$MsgArr['order']['field']		=	'id';
		$MsgArr['order']['type']		=	'DESC';

	   // Pager setup
		$MsgArr['pager'] 				= 	array();
		$MsgArr['pager']['total']		= 	$PrivateMassegeNumber;
		$MsgArr['pager']['perpage'] 	= 	$PowerBB->_CONF['info_row']['subject_perpage'];
		$MsgArr['pager']['count'] 		= 	$PowerBB->_GET['count'];
		$MsgArr['pager']['location'] 	= 	'index.php?page=pm&amp;show=1&amp;pm=1';
		$MsgArr['pager']['var'] 		= 	'count';

		// Clean data from HTML
		$MsgArr['proc'] 				= 	array();
		$MsgArr['proc']['*'] 			= 	array('method'=>'clean','param'=>'html');
		$MsgArr['proc']['date'] 		= 	array('method'=>'date','store'=>'date','type'=>$PowerBB->_CONF['info_row']['timesystem']);


		$PowerBB->_CONF['template']['while']['MassegeList'] = $PowerBB->pm->GetPrivateMassegeList($MsgArr);

		$PowerBB->template->assign('Msg_Num',$PrivateMassegeNumber);

            if ($PrivateMassegeNumber > $PowerBB->_CONF['info_row']['subject_perpage'])
	        {
			$PowerBB->template->assign('pager',$PowerBB->pager->show());
	        }

		$PowerBB->template->display('pm_user_show');
	}

	function _SendMain()
	{
		global $PowerBB;

				if (!is_object($PowerBB->icon))
		{
			trigger_error('ERROR::ICON_OBJECT_DID_NOT_FOUND',E_USER_ERROR);
		}

		if (!is_object($PowerBB->toolbox))
		{
			trigger_error('ERROR::TOOLBOX_OBJECT_DID_NOT_FOUND',E_USER_ERROR);
		}

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

		$PowerBB->template->display('member_send_pm');
	}


	function _StartSend()
	{
		global $PowerBB;

				if (empty($PowerBB->_POST['title']))
				{
					$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Must_write_the_title_of_the_message']);
				}

				if (empty($PowerBB->_POST['text']))
				{
					$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Must_write_the_message']);
				}
              	$PowerBB->_POST['text']= str_replace($PowerBB->admincpdir."/", '', $PowerBB->_POST['text']);


          if ($PowerBB->_POST['group'] == 'all')
              {
                     $br = "\n <br />";
                     //$PowerBB->_POST['text'] = $PowerBB->Powerparse->replace($PowerBB->_POST['text']);
				//	$GetForumAdress = $PowerBB->functions->GetForumAdress();
                  //  $PowerBB->_POST['text'] = str_replace($GetForumAdress, "", $PowerBB->_POST['text']);
              	$PowerBB->_POST['icon']= str_replace("../", '', $PowerBB->_POST['icon']);

               echo('<br /><br /><table border="1" width="80%" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" style="border-collapse: collapse" align="center"><tr><td><font face="Tahoma" size="2">');

                  $getmember_query = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['member'] . " ORDER BY id DESC");


                  while ($getmember_row = $PowerBB->DB->sql_fetch_array($getmember_query))
                  {

                     	$MsgArr 				= 	array();
						$MsgArr['get_id']		=	true;
						$MsgArr['field']		=	array();

						$MsgArr['field']['user_from'] 	= 	$PowerBB->_CONF['rows']['member_row']['username'];
						$MsgArr['field']['user_to'] 	= 	$getmember_row['username'];
						$MsgArr['field']['title'] 		= 	$PowerBB->_POST['title'];
						$MsgArr['field']['text'] 		= 	str_replace("../", '', $PowerBB->_POST['text']);
						$MsgArr['field']['date'] 		= 	$PowerBB->_CONF['now'];
						$MsgArr['field']['icon'] 		= 	$PowerBB->_POST['icon'];
						$MsgArr['field']['folder'] 		= 	'inbox';

						$Send = $PowerBB->core->Insert($MsgArr,'pm');

                      			$NumberArr 				= 	array();
								$NumberArr['username'] 	= 	$getmember_row['username'];

								$Number = $PowerBB->pm->NewMessageNumber($NumberArr);

								$CacheArr 					= 	array();
								$CacheArr['field']			=	array();

								$CacheArr['field']['unread_pm'] 	= 	$Number;
								$CacheArr['where'] 					= 	array('username',$getmember_row['username']);

								$Cache = $PowerBB->member->UpdateMember($CacheArr);

								if ($Cache)
								{
									$success[] = $getmember_row['username'];
								}

                         // Report mail .. New Massege
					     	$GetForumAdress = $PowerBB->functions->GetForumAdress();
					        $GetForumAdress = str_replace($PowerBB->admincpdir."/", '', $GetForumAdress);
			        //$GetForumAdress = str_replace("http://", '', $GetForumAdress);


                		 $Adress = 	$GetForumAdress;
                           $greetings_Management_Forum = "\n <br />".$PowerBB->_CONF['template']['_CONF']['lang']['With_my_sincere_greetings_to_all'];
						 $title = $PowerBB->_CONF['template']['_CONF']['lang']['you_have_new_pm'];
						 $username = $PowerBB->_CONF['template']['_CONF']['lang']['hello_your'] .' : '  . $getmember_row['username'] . "\n <br />";
					     $pm_list	= 	'<a href="'.$Adress.'index.php?page=pm_list&list=1&folder=inbox'.'">'.$Adress.'index.php?page=pm_list&list=1&folder=inbox'.'</a>';
                       		$Formurl	= 	'<a href="'.$GetForumAdress.'index.php'.'">'.$PowerBB->_CONF['info_row']['title'].'</a>';
 			         	 $Form_Massege =  "\n <br />".$PowerBB->_CONF['template']['_CONF']['lang']['Please_login_on_the_following_link_to_access_the_pm'] . "\n <br />".$pm_list."\n <br />".$greetings_Management_Forum."\n <br />".$Formurl;

						if ($PowerBB->_CONF['info_row']['mailer']=='phpmail')
						{
						    $send_mail = $PowerBB->functions->send_this_php($getmember_row['email'],$title,$username . $Form_Massege,$PowerBB->_CONF['info_row']['send_email']);
			            }
						elseif ($PowerBB->_CONF['info_row']['mailer']=='smtp')
						{
						$to = $getmember_row['email'];
						$fromname = $PowerBB->_CONF['info_row']['title'];
						$message = $username . $Form_Massege;
						$subject = $title;
						$from = $PowerBB->_CONF['info_row']['send_email'];
                         $send_mail = $PowerBB->functions->send_this_smtp($to,$fromname,$message,$subject,$from);

						}
                        echo($PowerBB->_CONF['template']['_CONF']['lang']['The_message_was_sent_successfully_to_the_private'].'  : ' . $getmember_row['username'] . "<br />");


                  }

                  echo('</font></td></tr></table>');
                  echo('<br><table border="1" width="80%" cellspacing="0" cellpadding="0" bgcolor="#E5EBF0" style="border-collapse: collapse" align="center"><tr><td><font face="Tahoma" size="2"><a href="index.php?page=pm&amp;send_pm=1&amp;pm=1">');
                  echo($PowerBB->_CONF['template']['_CONF']['lang']['Back_to_model_transmission']);
                  echo('</a></font></td></tr></table>');

              }
              else
              {
                     $br = "\n <br />";
                    // $PowerBB->_POST['text'] = $PowerBB->Powerparse->replace($PowerBB->_POST['text']);
                    $PowerBB->_POST['text'] = str_replace($PowerBB->admincpdir."/", '', $PowerBB->_POST['text']);
					//$GetForumAdress = $PowerBB->functions->GetForumAdress();
                    //$PowerBB->_POST['text'] = str_replace($GetForumAdress, "", $PowerBB->_POST['text']);
              	$PowerBB->_POST['icon']= str_replace("../", '', $PowerBB->_POST['icon']);
               echo('<br><br><table border="1" width="80%" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" style="border-collapse: collapse" align="center"><tr><td><font face="Tahoma" size="2">');

		          $getmember_query = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['member'] . " WHERE usergroup='" . intval($PowerBB->_POST['group']). "'  ORDER BY id DESC");

              	while ($getmember_row = $PowerBB->DB->sql_fetch_array($getmember_query))
                 {

                     	$MsgArr 				= 	array();
						$MsgArr['get_id']		=	true;
						$MsgArr['field']		=	array();

						$MsgArr['field']['user_from'] 	= 	$PowerBB->_CONF['rows']['member_row']['username'];
						$MsgArr['field']['user_to'] 	= 	$getmember_row['username'];
						$MsgArr['field']['title'] 		= 	$PowerBB->_POST['title'];
						$MsgArr['field']['text'] 		= 	str_replace("../", '', $PowerBB->_POST['text']);
						$MsgArr['field']['date'] 		= 	$PowerBB->_CONF['now'];
						$MsgArr['field']['icon'] 		= 	$PowerBB->_POST['icon'];
						$MsgArr['field']['folder'] 		= 	'inbox';

						$Send = $PowerBB->core->Insert($MsgArr,'pm');

                      			$NumberArr 				= 	array();
								$NumberArr['username'] 	= 	$getmember_row['username'];

								$Number = $PowerBB->pm->NewMessageNumber($NumberArr);

								$CacheArr 					= 	array();
								$CacheArr['field']			=	array();

								$CacheArr['field']['unread_pm'] 	= 	$Number;
								$CacheArr['where'] 					= 	array('username',$getmember_row['username']);

								$Cache = $PowerBB->member->UpdateMember($CacheArr);

								if ($Cache)
								{
									$success[] = $getmember_row['username'];
								}

                         // Report mail .. New Massege
					     	$GetForumAdress = $PowerBB->functions->GetForumAdress();
					        $GetForumAdress = str_replace($PowerBB->admincpdir."/", '', $GetForumAdress);
                		$Adress = 	$GetForumAdress;
                        $greetings_Management_Forum = "\n <br />".$PowerBB->_CONF['template']['_CONF']['lang']['With_my_sincere_greetings_to_all'];

						 $title = $PowerBB->_CONF['template']['_CONF']['lang']['you_have_new_pm'];
						 $username = $PowerBB->_CONF['template']['_CONF']['lang']['hello_your'] .' : '  . $getmember_row['username'] ."\n <br />";
					    $pm_list	= 	'<a href="'.$Adress.'index.php?page=pm_list&list=1&folder=inbox'.'">'.$Adress.'index.php?page=pm_list&list=1&folder=inbox'.'</a>';
                       	$Formurl	= 	'<a href="'.$GetForumAdress.'index.php'.'">'.$PowerBB->_CONF['info_row']['title'].'</a>';
 			         	 $Form_Massege =  $PowerBB->_CONF['template']['_CONF']['lang']['Please_login_on_the_following_link_to_access_the_pm'] ."\n <br />".$pm_list."\n <br />".$greetings_Management_Forum."\n <br />".$Formurl;

						if ($PowerBB->_CONF['info_row']['mailer']=='phpmail')
						{
						    $send_mail = $PowerBB->functions->send_this_php($getmember_row['email'],$title,$username . $Form_Massege,$PowerBB->_CONF['info_row']['send_email']);
			            }
						elseif ($PowerBB->_CONF['info_row']['mailer']=='smtp')
						{
						$to = $getmember_row['email'];
						$fromname = $PowerBB->_CONF['info_row']['title'];
						$message = $username . $Form_Massege;
						$subject = $title;
						$from = $PowerBB->_CONF['info_row']['send_email'];
                         $send_mail = $PowerBB->functions->send_this_smtp($to,$fromname,$message,$subject,$from);

						}

                  echo($PowerBB->_CONF['template']['_CONF']['lang']['The_message_was_sent_successfully_to_the_private'].'  : ' . $getmember_row['username'] . "<br />");

                 }


                  echo('</font></td></tr></table>');
                  echo('<br><table border="1" width="80%" cellspacing="0" cellpadding="0" bgcolor="#E5EBF0" style="border-collapse: collapse" align="center"><tr><td><font face="Tahoma" size="2"><a href="index.php?page=pm&amp;send_pm=1&amp;pm=1">');
                  echo($PowerBB->_CONF['template']['_CONF']['lang']['Back_to_model_transmission']);
                  echo('</a></font></td></tr></table>');

              }
	}

	function _StartDeletAllPm()
	{
		global $PowerBB;
		   $UPDATE_unread_pm = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['member'] . " SET unread_pm = '0'");
			$DelArr = array();
			$del = $PowerBB->pm->DeletePrivateMessage($DelArr);
		   if ($del)
			{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Been_emptied_record_successfully']);
		    $PowerBB->functions->redirect('index.php?page=pm&pm=1&main=1');
			}

	}


}

?>
