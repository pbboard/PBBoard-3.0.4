<?php
(!defined('IN_PowerBB')) ? die() : '';

define('CLASS_NAME','PowerBBCoreMOD');
define('DONT_STRIPS_SLIASHES',true);
include('common.php');
class PowerBBCoreMOD
{
	function run()
	{
		global $PowerBB;
     	$PowerBB->_GET['id']	= 	$PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');
		if ($PowerBB->_GET['setting'] and $PowerBB->_GET['start'] and $PowerBB->_CONF['member_permission'])
		{
			if ($PowerBB->_POST['style'] != $PowerBB->_CONF['member_row']['style'])
			{
			$Style_id = $PowerBB->_POST['style'];

			ob_start();
			setcookie("PowerBB_style", $Style_id, time()+2592000);
			ob_end_flush();
			}
		}
      eval($PowerBB->functions->get_fetch_hooks('usercp_hook_start'));
       $PowerBB->template->assign('usercp_page','primary_tabon');
		if (!$PowerBB->_CONF['member_permission'])
		{
            $PowerBB->functions->ShowHeader();
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['This_region_to_members_only']);
		}

		if ($PowerBB->_CONF['group_info']['banned'])
		{
            $PowerBB->functions->ShowHeader();
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['no_online']);
		}

		if ($PowerBB->_GET['index'])
		{
			$this->_Index();
		}
		/** Control **/
		elseif ($PowerBB->_GET['control'])
		{
			/** Persenol Information control **/
			if ($PowerBB->_GET['info'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_InfoMain();
				}
				elseif ($PowerBB->_GET['start'])
				{
					$this->_InfoChange();
				}
			    else
				{
					header("Location: index.php");
					exit;
				}
			}
			/** **/

			/** Options control **/
			elseif ($PowerBB->_GET['setting'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_SettingMain();
				}
				elseif ($PowerBB->_GET['start'])
				{
					$this->_SettingChange();
				}
				else
				{
					header("Location: index.php");
					exit;
				}
			}
			/** **/

			/** Signature control **/
			elseif ($PowerBB->_GET['sign'])
			{
				if (!$PowerBB->_CONF['group_info']['sig_allow'])
				{
		            $PowerBB->functions->ShowHeader();
					$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['You_can_not_use_this_feature']);
				}

				if ($PowerBB->_GET['main'])
				{
					$this->_SignMain();
				}
				elseif ($PowerBB->_GET['start'])
				{
					$this->_SignChange();
				}
				else
				{
					header("Location: index.php");
					exit;
				}
			}
			/** **/

			/** Password control **/
			elseif ($PowerBB->_GET['password'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_PasswordMain();
				}
				elseif ($PowerBB->_GET['start'])
				{
					$this->_PasswordChange();
				}
			}
			/** **/

			/** Email control **/
			elseif ($PowerBB->_GET['email'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_EmailMain();
				}
				elseif ($PowerBB->_GET['start'])
				{
					$this->_EmailChange();
				}
			}
			/** **/

			/** Avatar control **/
			elseif ($PowerBB->_GET['avatar'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_AvatarMain();
				}
				elseif ($PowerBB->_GET['start'])
				{
					$this->_AvatarChange();
				}
			}
			/** profile coordination control **/
			elseif ($PowerBB->_GET['coordination'])
			{
               $PowerBB->functions->ShowHeader();
			   $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['You_can_not_use_this_feature']);
	     	}
			else
			{
				header("Location: index.php");
				exit;
			}
		    /** **/
         }
		/** Options **/
		elseif ($PowerBB->_GET['options'])
		{
			if ($PowerBB->_GET['reply'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_ReplyListMain();
				}
			}
			elseif ($PowerBB->_GET['subject'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_SubjectListMain();
				}
			}
			elseif ($PowerBB->_GET['attach'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_attachListMain();
				}
				elseif($PowerBB->_GET['del'])
				{
			      $this->_DeleteAttachments();
				}
			}
			elseif ($PowerBB->_GET['emailed'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_EmailedListMain();
				}
			}
			elseif ($PowerBB->_GET['friends'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_FriendsMain();
				}
				elseif ($PowerBB->_GET['add'])
				{
					$this->_FriendsAdd();
				}
				elseif ($PowerBB->_GET['del'])
				{
					$this->_FriendsDel();
				}
				elseif ($PowerBB->_GET['approval'])
				{
					$this->_ApprovalFriendStart();
				}
			}
			elseif ($PowerBB->_GET['reputation'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_ReputationListMain();
				}
			}
			elseif ($PowerBB->_GET['mention'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_MentionListMain();
				}
				elseif ($PowerBB->_GET['start'])
				{
					$this->_MentionReadableStart();
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

      eval($PowerBB->functions->get_fetch_hooks('usercp_hook_end'));

		$PowerBB->functions->GetFooter();
	}

	function _Index()
	{
		global $PowerBB;

		$PowerBB->functions->ShowHeader();
        // Get Member Subjects
		$SubjectArr 								= 	array();
		$SubjectArr['where'] 						= 	array();

		$SubjectArr['where'][0] 					= 	array();
		$SubjectArr['where'][0]['name'] 			= 	'writer';
		$SubjectArr['where'][0]['oper'] 			= 	'=';
		$SubjectArr['where'][0]['value'] 			= 	$PowerBB->_CONF['rows']['member_row']['username'];

		// Clean data
		$SubjectArr['proc'] 			        	= 	array();
		$SubjectArr['proc']['*'] 		        	= 	array('method'=>'clean','param'=>'html');
		$SubjectArr['proc']['native_write_time'] 	= 	array('method'=>'date','store'=>'write_date','type'=>$PowerBB->_CONF['info_row']['timesystem']);
		$SubjectArr['proc']['write_time'] 			= 	array('method'=>'date','store'=>'reply_date','type'=>$PowerBB->_CONF['info_row']['timesystem']);

		$SubjectArr['order']			            =	array();
		$SubjectArr['order']['field']	            =	'write_time';
		$SubjectArr['order']['type']	            =	'DESC';
		$SubjectArr['limit'] 		             	= 	'5';


         $PowerBB->_CONF['template']['while']['subject_list'] = $PowerBB->core->GetList($SubjectArr,'subject');



		if ($PowerBB->_CONF['template']['while']['subject_list'] == false)
		{
			$PowerBB->template->assign('No_Subjects',true);
		}
		else
		{
			$PowerBB->template->assign('No_Subjects',false);
		}

        // Get Member Replys

     	$ReplyArr 								= 	array();
		$ReplyArr['where'] 						= 	array();
        $ReplyArr['select'] 	                = 	'DISTINCT subject_id,title,writer,icon,section';

		$ReplyArr['where'][0] 					= 	array();
		$ReplyArr['where'][0]['name'] 			= 	'writer';
		$ReplyArr['where'][0]['oper'] 			= 	'=';
		$ReplyArr['where'][0]['value'] 			= 	$PowerBB->_CONF['rows']['member_row']['username'];

		$ReplyArr['order'] 						=	 array();
		$ReplyArr['order']['field'] 			= 	'write_time';
		$ReplyArr['order']['type'] 				= 	'DESC';

		$ReplyArr['limit'] 						= 	'10';


		$ReplyArr['proc']['write_time'] 		= 	array('method'=>'date','store'=>'reply_date','type'=>$PowerBB->_CONF['info_row']['timesystem']);

		$PowerBB->_CONF['template']['while']['ReplyList'] = $PowerBB->reply->GetReplyList($ReplyArr);


		$PowerBB->functions->CleanVariable($PowerBB->_CONF['template']['while']['ReplyList'],'html');


		if ($PowerBB->_CONF['template']['while']['ReplyList'] == false)
		{
			$PowerBB->template->assign('No_posts',true);
		}
		else
		{
			$PowerBB->template->assign('No_posts',false);
		}

		/** Get the Reputation information **/
		$ReputArr 							= 	array();
		$ReputArr['where'] 					= 	array();

		$ReputArr['where'][0] 				= 	array();
		$ReputArr['where'][0]['name'] 		= 	'username';
		$ReputArr['where'][0]['oper'] 		= 	'=';
		$ReputArr['where'][0]['value'] 		= 	$PowerBB->_CONF['rows']['member_row']['username'];

		$ReputArr['order'] 					=	 array();
		$ReputArr['order']['field'] 			= 	'id';
		$ReputArr['order']['type'] 			= 	'DESC';

		$ReputArr['proc'] 						= 	array();
		$ReputArr['proc']['*'] 					= 	array('method'=>'clean','param'=>'html');
		$ReputArr['proc']['reputationdate'] 	= 	array('method'=>'date','store'=>'reputationdate','type'=>$PowerBB->_CONF['info_row']['timesystem']);


		$ReputArr['limit'] 					= 	$PowerBB->_CONF['info_row']['show_reputation_number'];


       $PowerBB->_CONF['template']['while']['MemberReputation'] = $PowerBB->core->GetList($ReputArr,'reputation');
       $this->ReputationInfo = $PowerBB->core->GetInfo($ReputArr,'reputation');

        // If Reputation  by reply Get subject_id for thes reply
		$ReplyArr = array();
		$ReplyArr['where'] = array('id',$this->ReputationInfo['reply_id']);

		$ReplyInfo = $PowerBB->core->GetInfo($GetReplyInfo,'reply');

		$PowerBB->template->assign('subject_id',$ReplyInfo['subject_id']);

		if ($PowerBB->_CONF['template']['while']['MemberReputation'] == false)
		{
			$PowerBB->template->assign('No_Reputation',true);
		}
		else
		{
			$PowerBB->template->assign('No_Reputation',false);
		}

         // reputation read
		 $UpdateArr 				= 	array();
		 $UpdateArr['field']		=	array();

		 $UpdateArr['field']['reputationread'] 		= 	'0';
	     $UpdateArr['where'] 						= 	array('username',$PowerBB->_CONF['rows']['member_row']['username']);

		 $update = $PowerBB->core->Update($UpdateArr,'reputation');

      	$PowerBB->template->display('usercp_index');
	}

	function _InfoMain()
	{
		global $PowerBB;

		$PowerBB->functions->ShowHeader();
				//getting extra fields
    $PowerBB->_CONF['template']['while']['extrafields']=$PowerBB->extrafield->getUserFields();

		if ($PowerBB->_CONF['template']['while']['extrafields'] == false)
		{
			$PowerBB->template->assign('No_extrafields',true);
		}
		else
		{
			$PowerBB->template->assign('No_extrafields',false);
		}

		$PowerBB->template->display('usercp_control_info');

	}

	function _InfoChange()
	 {
		global $PowerBB;
 		$PowerBB->functions->ShowHeader($PowerBB->_CONF['template']['_CONF']['lang']['execution_Process_Update']);

		//getting extra fields
    $extraFields=$PowerBB->extrafield->getEmptyLoginFields();
		//checking if the extra fields are required
	 foreach($extraFields AS $field)
	 {
		$FieldsArr = array();
		$FieldsArr['where'] = array('name',$field['name']);

		$FieldsInfo = $PowerBB->extrafield->GetFieldInfo($FieldsArr);
        if($FieldsInfo['type'] == 'select_multiple')
        {
		$multFields = array();

    	//-----------------------------------------
    	// Check for an array
    	//-----------------------------------------

    	if ( is_array( $PowerBB->_POST[ $field['name_tag']] )  )
    	{

    		if ( in_array( 'all', $PowerBB->_POST[ $field['name_tag']] ) )
    		{
    			//-----------------------------------------
    			// Searching all multiple..
    			//-----------------------------------------

    			return '*';
    		}
    		else
    		{
				//-----------------------------------------
				// Go loopy loo
				//-----------------------------------------

				foreach( $PowerBB->_POST[ $field['name_tag']] as $l )
				{

						$multFields[] = $l;
				}

				//-----------------------------------------
				// Do we have cats? Give 'em to Charles!
				//-----------------------------------------

				if ( count( $multFields  ) )
				{
					foreach( $multFields  as $f )
					{
						if ( is_array($f) and count($f) )
						{
							$multFields  = array_merge( $multFields , $f );
						}
					}
				}
				else
				{
					//-----------------------------------------
					// No multiple selected / we have available
					//-----------------------------------------

					return;
				}
    		}
		}
		else
		{
			//-----------------------------------------
			// Not an array...
			//-----------------------------------------

			if ($PowerBB->_POST[ $field['name_tag']] == 'all' )
			{
				return '*';
			}
			else
			{
				if ( $PowerBB->_POST[ $field['name_tag']] != "" )
				{
					$l = intval($PowerBB->_POST[ $field['name_tag']]);

					//-----------------------------------------
					// Single forum
					//-----------------------------------------


						$multFields[] = $l;


						if ( is_array($f) and count($f) )
						{
							$multFields  = array_merge( $multFields , $f );
						}
				}
			}
		}

         $PowerBB->_POST[ $field['name_tag']] = implode( ",", $multFields );

        }

   		if ($FieldsInfo['required'] == 'yes'
   		and $PowerBB->_POST[ $field['name_tag'] ] == '')
   		{
	          $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['You_do_not_enter'].' <i><b>'.$field['name'].'</b></i>');
        }
     }

        $PowerBB->_POST['away_msg'] = $PowerBB->functions->CleanVariable($PowerBB->_POST['away_msg'],'trim');
        $PowerBB->_POST['website'] = $PowerBB->functions->CleanVariable($PowerBB->_POST['website'],'trim');
        $PowerBB->_POST['country'] = $PowerBB->functions->CleanVariable($PowerBB->_POST['country'],'trim');
        $PowerBB->_POST['info'] = $PowerBB->functions->CleanVariable($PowerBB->_POST['info'],'trim');
        $PowerBB->_POST['year'] = $PowerBB->functions->CleanVariable($PowerBB->_POST['year'],'trim');

        $PowerBB->_POST['away_msg'] = strip_tags($PowerBB->_POST['away_msg']);
        $PowerBB->_POST['website'] = strip_tags($PowerBB->_POST['website']);
        $PowerBB->_POST['info'] = strip_tags($PowerBB->_POST['info']);
        $PowerBB->_POST['year'] = strip_tags($PowerBB->_POST['year']);

		if(!empty($PowerBB->_POST['birth_date']))
		{
       	$PowerBB->_POST['birth_date']   = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['birth_date'],'sql');
       	$PowerBB->_POST['birth_date']   = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['birth_date'],'html');
			  $birth = explode("-",$PowerBB->_POST['birth_date']);

			if(intval($birth[0]))
			{
			$PowerBB->_POST['day'] = $birth[0];
			}
			if(intval($birth[1]))
			{
			$PowerBB->_POST['month'] = $birth[1];
			}
			if(intval($birth[2]))
			{
			$PowerBB->_POST['year'] = $birth[2];
			}
		}



		$StartArr 			= 	array();
		$StartArr['field'] 	= 	array();

		$StartArr['field']['user_country'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['country'],'html');
		$StartArr['field']['user_gender']   = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['gender'],'html');
		$StartArr['field']['user_website'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['website'],'html');
		$StartArr['field']['user_info'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['info'],'html');
		$StartArr['field']['away'] 			= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['away'],'html');
		$StartArr['field']['away_msg'] 		= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['away_msg'],'html');
		$StartArr['field']['bday_day'] 		= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['day'],'html');
		$StartArr['field']['bday_month'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['month'],'html');
		$StartArr['field']['bday_year'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['year'],'html');

		$StartArr['field']['user_country'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['country'],'sql');
		$StartArr['field']['user_gender']   = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['gender'],'sql');
		$StartArr['field']['user_website'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['website'],'sql');
		$StartArr['field']['user_info'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['info'],'sql');
		$StartArr['field']['away'] 			= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['away'],'sql');
		$StartArr['field']['away_msg'] 		= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['away_msg'],'sql');
		$StartArr['field']['bday_day'] 		= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['day'],'sql');
		$StartArr['field']['bday_month'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['month'],'sql');
		$StartArr['field']['bday_year'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['year'],'sql');

		//extra fields insertion
        foreach($extraFields AS $field){

        $PowerBB->_POST[ $field['name_tag'] ] = strip_tags($PowerBB->_POST[ $field['name_tag'] ]);
        $PowerBB->_POST[ $field['name_tag'] ] = $PowerBB->Powerparse->censor_words($PowerBB->_POST[ $field['name_tag'] ]);
		$PowerBB->_POST[ $field['name_tag'] ] = $PowerBB->functions->CleanVariable($PowerBB->_POST[ $field['name_tag'] ],'html');
		$PowerBB->_POST[ $field['name_tag'] ] = $PowerBB->functions->CleanVariable($PowerBB->_POST[ $field['name_tag'] ],'sql');
		$StartArr['field'][ $field['name_tag'] ]     =   $PowerBB->Powerparse->censor_words($PowerBB->_POST[ $field['name_tag'] ]);
        }
		$StartArr['where']					=	array('id',$PowerBB->_CONF['member_row']['id']);

		$StartChange = $PowerBB->core->Update($StartArr,'member');

		if ($StartChange)
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Updated_successfully']);
            $PowerBB->functions->redirect('index.php?page=usercp&amp;control=1&amp;info=1&amp;main=1');
		}
	}

	function _SettingMain()
	{
		global $PowerBB;

		$PowerBB->functions->ShowHeader();

       // Select Style
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

		$PowerBB->functions->CleanVariable($PowerBB->_CONF['template']['while']['StyleList'],'html');

       // Select Language
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

		$PowerBB->functions->CleanVariable($PowerBB->_CONF['template']['while']['LangList'],'html');

       // info member
		$MemberArr 				= 	array();
		$MemberArr['where']		=	array('username',$PowerBB->_CONF['member_row']['username']);

		$member = $PowerBB->core->GetInfo($MemberArr,'member');
        $PowerBB->template->assign('member_lang',$member['lang']);
        $PowerBB->template->assign('member',$member);

		$PowerBB->template->display('usercp_control_setting');

	}

	function _SettingChange()
	{
		global $PowerBB;

		$PowerBB->_POST['style'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['style'],'html');
		$PowerBB->_POST['lang'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['lang'],'html');
		$PowerBB->_POST['hide_online'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['hide_online'],'html');
		$PowerBB->_POST['user_time'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['user_time'],'html');
		$PowerBB->_POST['send_allow'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['send_allow'],'html');
		$PowerBB->_POST['pm_emailed'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['pm_emailed'],'html');
		$PowerBB->_POST['pm_window'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['pm_window'],'html');
		$PowerBB->_POST['send_allow'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['send_allow'],'html');
		$PowerBB->_POST['visitormessage'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['visitormessage'],'html');
		$PowerBB->_POST['style'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['style'],'sql');
		$PowerBB->_POST['lang'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['lang'],'sql');
		$PowerBB->_POST['hide_online'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['hide_online'],'sql');
		$PowerBB->_POST['user_time'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['user_time'],'sql');
		$PowerBB->_POST['send_allow'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['send_allow'],'sql');
		$PowerBB->_POST['pm_emailed'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['pm_emailed'],'sql');
		$PowerBB->_POST['pm_window'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['pm_window'],'sql');
		$PowerBB->_POST['visitormessage'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['visitormessage'],'sql');
		$PowerBB->_POST['profile_viewers'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['profile_viewers'],'sql');


		$PowerBB->functions->ShowHeader($PowerBB->_CONF['template']['_CONF']['lang']['execution_Process_Update']);

		$PowerBB->functions->AddressBar('<a href="index.php?page=usercp&index=1"> '. $PowerBB->_CONF['template']['_CONF']['lang']['User_Control_Panel'] .'</a>'. $PowerBB->_CONF['info_row']['adress_bar_separate'] . $PowerBB->_CONF['template']['_CONF']['lang']['execution_Process_Update']);

		$UpdateArr 					= 	array();
		$UpdateArr['field']			=	array();

		$UpdateArr['field']['style'] 		= 	$PowerBB->_POST['style'];
		$UpdateArr['field']['lang'] 		= 	$PowerBB->_POST['lang'];
		$UpdateArr['field']['hide_online'] 	= 	$PowerBB->_POST['hide_online'];
		$UpdateArr['field']['user_time'] 	= 	$PowerBB->_POST['user_time'];
		$UpdateArr['field']['send_allow'] 	= 	$PowerBB->_POST['send_allow'];
		$UpdateArr['field']['pm_emailed'] 	= 	$PowerBB->_POST['pm_emailed'];
		$UpdateArr['field']['pm_window'] 	= 	$PowerBB->_POST['pm_window'];
		$UpdateArr['field']['visitormessage'] 	= 	$PowerBB->_POST['visitormessage'];
		$UpdateArr['field']['profile_viewers'] 	= 	$PowerBB->_POST['profile_viewers'];

		$UpdateArr['where']					=	array('id',$PowerBB->_CONF['member_row']['id']);

		$UpdateSetting = $PowerBB->core->Update($UpdateArr,'member');



		if ($UpdateSetting)
		{
		 $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Updated_successfully']);
         $PowerBB->functions->redirect('index.php?page=usercp&amp;control=1&amp;setting=1&amp;main=1',2);
		}
	}

	function _SignMain()
	{
		global $PowerBB;

		$PowerBB->functions->ShowHeader();

		$PowerBB->functions->GetEditorTools();
		$PowerBB->_CONF['template']['Sign'] =  $PowerBB->_CONF['rows']['member_row']['user_sig'];

		$PowerBB->_CONF['rows']['member_row']['user_sig'] = $PowerBB->Powerparse->replace($PowerBB->_CONF['rows']['member_row']['user_sig']);
		$PowerBB->Powerparse->replace_smiles($PowerBB->_CONF['rows']['member_row']['user_sig']);
		$PowerBB->_CONF['rows']['member_row']['user_sig'] = str_replace('&amp;','&',$PowerBB->_CONF['rows']['member_row']['user_sig']);
        $PowerBB->_CONF['template']['user_sig'] =  $PowerBB->_CONF['rows']['member_row']['user_sig'];
       	$PowerBB->_CONF['template']['user_sig'] = $PowerBB->Powerparse->censor_words($PowerBB->_CONF['template']['user_sig']);

        // show Custom_bbcode List
		$Custom_bbcodeArr 					= 	array();
		$Custom_bbcodeArr['order']			=	array();
		$Custom_bbcodeArr['order']['field']	=	'id';
		$Custom_bbcodeArr['order']['type']	=	'DESC';
		$Custom_bbcodeArr['proc'] 			= 	array();
		$Custom_bbcodeArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');

		$PowerBB->_CONF['template']['while']['Custom_bbcodesList'] = $PowerBB->core->GetList($Custom_bbcodeArr,'custom_bbcode');


		$PowerBB->template->display('usercp_control_sign');

	}

	function _SignChange()
	{
		global $PowerBB;

		$PowerBB->functions->ShowHeader($PowerBB->_CONF['template']['_CONF']['lang']['execution_Process_Update']);

		$PowerBB->functions->AddressBar('<a href="index.php?page=usercp&index=1"> ' . $PowerBB->_CONF['template']['_CONF']['lang']['User_Control_Panel']. '</a>' .$PowerBB->_CONF['info_row']['adress_bar_separate'] . $PowerBB->_CONF['template']['_CONF']['lang']['execution_Process_Update']);

             $TextPost = utf8_decode($PowerBB->_POST['text']);
             $TextPost = preg_replace('/\s+/', '', $TextPost);
		     $PowerBB->_CONF['template']['_CONF']['lang']['post_text_min'] = str_replace('المشاركة',$PowerBB->_CONF['template']['_CONF']['lang']['Signature'],$PowerBB->_CONF['template']['_CONF']['lang']['post_text_min']);
		     $PowerBB->_CONF['template']['_CONF']['lang']['sign_max'] = str_replace('{sig_len}',$PowerBB->_CONF['group_info']['sig_len'],$PowerBB->_CONF['template']['_CONF']['lang']['sign_max']);


			if (empty($TextPost))
			{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['post_text_min']);
			}

    		if(strlen($TextPost) <= $PowerBB->_CONF['group_info']['sig_len'])
    		{
               // Continue
            }
            else
    		{
             $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['sign_max']);
            }

    		if(strlen($TextPost) >= $PowerBB->_CONF['info_row']['post_text_min'])
    		{
             // Continue
            }
            else
    		{
             $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['post_text_min']);
            }

        $PowerBB->_POST['text'] = str_replace('target="_blank" ','',$PowerBB->_POST['text']);

		$SignArr 				= 	array();
		$SignArr['field']		=	array();

		$SignArr['field']['user_sig'] 	= 	$PowerBB->_POST['text'];
		$SignArr['where']				=	array('id',$PowerBB->_CONF['member_row']['id']);

		$UpdateSign = $PowerBB->core->Update($SignArr,'member');

		if ($UpdateSign)
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Signature_has_been_updated_successfully']);
          $PowerBB->functions->redirect('index.php?page=usercp&amp;control=1&amp;sign=1&amp;main=1');
		}
	}


	function _PasswordMain()
	{
		global $PowerBB;

		$PowerBB->functions->ShowHeader();

		$PowerBB->template->display('usercp_control_password');

	}

	function _PasswordChange()
	{
		global $PowerBB;

		$PowerBB->functions->ShowHeader($PowerBB->_CONF['template']['_CONF']['lang']['Ongoing_process']);

		$PowerBB->functions->AddressBar('<a href="index.php?page=usercp&index=1">' .$PowerBB->_CONF['template']['_CONF']['lang']['User_Control_Panel']. '</a> ' . $PowerBB->_CONF['info_row']['adress_bar_separate'] . $PowerBB->_CONF['template']['_CONF']['lang']['Ongoing_process']);

		//////////
       		$PowerBB->_POST['new_password'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['new_password'],'sql');
		// Check if the information aren't empty

		if (empty($PowerBB->_POST['old_password'])
		or empty($PowerBB->_POST['new_password'])
		or empty($PowerBB->_POST['confirm_new_password']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_fill_in_all_the_information']);
		}

		// Ensure the password is equal the confirm of password
		if ($PowerBB->_POST['new_password'] != $PowerBB->_POST['confirm_new_password'])
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['new_password_not_identical']);
		}
		//////////

		// Check old password
		if (md5($PowerBB->_POST['old_password']) != $PowerBB->_CONF['member_row']['password'])
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['bad_password']);
		}

		// Clean the information from white spaces (only in the begin and in the end)
		$PowerBB->_POST['new_password'] = $PowerBB->functions->CleanVariable($PowerBB->_POST['new_password'],'trim');

		//////////


		if ($PowerBB->_CONF['info_row']['confirm_on_change_pass'])
		{

       		$new_password = $PowerBB->_POST['new_password'];

			$Adress	= 	$PowerBB->functions->GetForumAdress();
			$Code	=	$PowerBB->functions->RandomCode();

		$ChangeAdress = $Adress . 'index.php?page=new_password&pass_change=1&code=' . $Code;
		$CancelAdress = $Adress . 'index.php?page=cancel_requests&index=1&type=1&code=' . $Code;

			$ReqArr 					= 	array();
			$ReqArr['field']			=	array();

			$ReqArr['field']['random_url'] 		= 	$Code;
			$ReqArr['field']['username'] 		= 	$PowerBB->_CONF['member_row']['username'];
			$ReqArr['field']['request_type'] 	= 	1;

			$InsertReq = $PowerBB->core->Insert($ReqArr,'requests');

			if ($InsertReq)
			{
				$UpdateArr 				= 	array();
				$UpdateArr['field']		= 	array();

				$UpdateArr['field']['new_password'] 	= 	$new_password;
				$UpdateArr['where'] 					= 	array('id',$PowerBB->_CONF['member_row']['id']);

				$UpdateNewPassword = $PowerBB->core->Update($UpdateArr,'member');

				if ($UpdateNewPassword)
				{

                    $MsgArr 			= 	array();
					$MsgArr['where'] 	= 	array('id','1');

					$MassegeInfo = $PowerBB->core->GetInfo($MsgArr,'email_msg');

					$MsgArr = array();
					$MsgArr['text'] 		= 	$MassegeInfo['text'];
					$MsgArr['change_url'] 	= 	$ChangeAdress;
					$MsgArr['cancel_url'] 	= 	$CancelAdress;
					$MsgArr['username']		=	$PowerBB->_CONF['member_row']['username'];
					$MsgArr['title']		=	$PowerBB->_CONF['info_row']['title'];

					$MassegeInfo['text'] = $PowerBB->core->MessageProccess($MsgArr,'email_msg');

					if ($PowerBB->_CONF['info_row']['mailer']=='phpmail')
					{
					$Send = $PowerBB->functions->send_this_php($PowerBB->_CONF['rows']['member_row']['email'],$MassegeInfo['title'],$MassegeInfo['text'],$PowerBB->_CONF['info_row']['send_email']);
		            }
					elseif ($PowerBB->_CONF['info_row']['mailer']=='smtp')
					{
					$to = $PowerBB->_CONF['rows']['member_row']['email'];
					$fromname = $PowerBB->_CONF['info_row']['title'];
					$message = $MassegeInfo['text'];
					$subject = $MassegeInfo['title'];
					$from = $PowerBB->_CONF['info_row']['send_email'];
                    $Send = $PowerBB->functions->send_this_smtp($to,$fromname,$message,$subject,$from);

					}

						$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Password_has_been_sent_to_E-mail'].' '.$PowerBB->_CONF['rows']['member_row']['email']." <br /><div style='margin: 10px;'><li><a href='index.php?page=usercp&control=1&password=1&main=1'>".$PowerBB->_CONF['template']['_CONF']['lang']['return_page_previously_viewing']."</a></li><li><a href='index.php'>".$PowerBB->_CONF['template']['_CONF']['lang']['go_to_the_home_page_of_the_forum']."</a></li></ul></div>");
						//$PowerBB->functions->redirect('index.php?page=usercp&index=1');

				}
			}
		}
		else
		{

		   // Convert password to md5
		  $PowerBB->_POST['new_password'] = md5($PowerBB->_POST['new_password']);

			$PassArr 				= 	array();
			$PassArr['field']		=	array();
			$PassArr['field']['password'] 			= 	$PowerBB->_POST['new_password'];
			$PassArr['where'] = array('id',$PowerBB->_CONF['member_row']['id']);

			$UpdatePassword = $PowerBB->core->Update($PassArr,'member');

			if ($UpdatePassword)
			{
				$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Updated_successfully']);
               $PowerBB->functions->redirect('index.php?page=login&sign=1');
			}
		}
	}


	function _EmailMain()
	{
		global $PowerBB;

		$PowerBB->functions->ShowHeader();

		$PowerBB->template->display('usercp_control_email');

	}

	function _EmailChange()
	{
		global $PowerBB;

		$PowerBB->functions->ShowHeader($PowerBB->_CONF['template']['_CONF']['lang']['Ongoing_process']);

		$PowerBB->functions->AddressBar('<a href="index.php?page=usercp&index=1">' .$PowerBB->_CONF['template']['_CONF']['lang']['User_Control_Panel']. '</a> ' . $PowerBB->_CONF['info_row']['adress_bar_separate'] . $PowerBB->_CONF['template']['_CONF']['lang']['Ongoing_process']);

       $PowerBB->_CONF['template']['_CONF']['lang']['E-mail_is_registered_please_type_the_other'] = str_replace("{forget}","index.php?page=forget&index=1", $PowerBB->_CONF['template']['_CONF']['lang']['E-mail_is_registered_please_type_the_other']);

		if (empty($PowerBB->_POST['new_email'])
		or empty($PowerBB->_POST['check_password']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_fill_in_all_the_information']);
		}

		$PowerBB->_POST['new_email']	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['new_email'],'sql');

        $PowerBB->_POST['check_password'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['check_password'],'sql');

		// Check old password
		if (md5($PowerBB->_POST['check_password']) != $PowerBB->_CONF['member_row']['password'])
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['bad_password']);
		}

		if ($PowerBB->_POST['new_email'] == $PowerBB->_CONF['member_row']['email'])
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['E-mail_is_registered_please_type_the_other']);
		}

		$EmailArr = array();
		$EmailArr['where']	=	array('email',$PowerBB->_POST['new_email']);

		$EmailExists = $PowerBB->core->Is($EmailArr,'member');

		if (!$PowerBB->functions->CheckEmail($PowerBB->_POST['new_email']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_enter_your_correct_email']);
		}
		if ($EmailExists)
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['E-mail_is_registered_please_type_the_other']);
		}

		$PowerBB->_POST['new_email'] = $PowerBB->functions->CleanVariable($PowerBB->_POST['new_email'],'trim');

		// We will send a confirm message, The confirm message will help user protect himself from crack
		if ($PowerBB->_CONF['info_row']['confirm_on_change_mail'])
		{
			$Adress	= 	$PowerBB->functions->GetForumAdress();
			$Code	=	$PowerBB->functions->RandomCode();

			$ChangeAdress = $Adress . 'index.php?page=new_email&index=1&code=' . $Code;
			$CancelAdress = $Adress . 'index.php?page=cancel_requests&index=1&type=2&code=' . $Code;

			$ReqArr 			= 	array();
			$ReqArr['field'] 	= 	array();

			$ReqArr['field']['random_url'] 		= 	$Code;
			$ReqArr['field']['username'] 		= 	$PowerBB->_CONF['member_row']['username'];
			$ReqArr['field']['request_type'] 	= 	2;

			$InsertReq = $PowerBB->core->Insert($ReqArr,'requests');

			if ($InsertReq)
			{
				$UpdateArr 				= 	array();
				$UpdateArr['field']		=	array();
				$UpdateArr['field']['new_email'] 			= 	$PowerBB->_POST['new_email'];
				$UpdateArr['where'] = array('id',$PowerBB->_CONF['member_row']['id']);

				$UpdateNewEmail = $PowerBB->core->Update($UpdateArr,'member');

				if ($UpdateNewEmail)
				{
                    $MsgArr 			= 	array();
					$MsgArr['where'] 	= 	array('id','2');

					$MassegeInfo = $PowerBB->core->GetInfo($MsgArr,'email_msg');

					$MsgArr = array();
					$MsgArr['text'] 		= 	$MassegeInfo['text'];
					$MsgArr['change_url'] 	= 	$ChangeAdress;
					$MsgArr['cancel_url'] 	= 	$CancelAdress;
					$MsgArr['username']		=	$PowerBB->_CONF['member_row']['username'];
					$MsgArr['title']		=	$PowerBB->_CONF['info_row']['title'];

					$MassegeInfo['text'] = $PowerBB->core->MessageProccess($MsgArr,'email_msg');

					if ($PowerBB->_CONF['info_row']['mailer']=='phpmail')
					{
					$Send = $PowerBB->functions->send_this_php($PowerBB->_POST['new_email'],$MassegeInfo['title'],$MassegeInfo['text'],$PowerBB->_CONF['info_row']['send_email']);
		            }
					elseif ($PowerBB->_CONF['info_row']['mailer']=='smtp')
					{
					$to = $PowerBB->_POST['new_email'];
					$fromname = $PowerBB->_CONF['info_row']['title'];
					$message = $MassegeInfo['text'];
					$subject = $MassegeInfo['title'];
					$from = $PowerBB->_CONF['info_row']['send_email'];
                     $Send = $PowerBB->functions->send_this_smtp($to,$fromname,$message,$subject,$from);
					}

					$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['has_been_sent_to_your_email'].' '.$PowerBB->_POST['new_email']." <br /><div style='margin: 10px;'><li><a href='index.php?page=usercp&control=1&email=1&main=1'>".$PowerBB->_CONF['template']['_CONF']['lang']['return_page_previously_viewing']."</a></li><li><a href='index.php'>".$PowerBB->_CONF['template']['_CONF']['lang']['go_to_the_home_page_of_the_forum']."</a></li></ul></div>");
                       //$PowerBB->functions->redirect('index.php?page=usercp&index=1');

				}
			}
		}
		// Confirm message is off, so change email direct
		else
		{
			$EmailArr 			= 	array();
			$EmailArr['field']	=	array();

			$EmailArr['field']['email'] 	= 	$PowerBB->_POST['new_email'];
			$EmailArr['where'] 				= 	array('id',$PowerBB->_CONF['member_row']['id']);

			$UpdateEmail= $PowerBB->member->UpdateMember($EmailArr);

			if ($UpdateEmail)
			{
				$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['email_was_changed_successfully']);
                $PowerBB->functions->redirect('index.php?page=usercp&amp;control=1&amp;email=1&amp;main=1');
			}
		}
	}

	function _AvatarMain()
	{
		global $PowerBB;

		// This line will include jQuery (Javascript library)
		$PowerBB->template->assign('JQUERY',true);

		$PowerBB->functions->ShowHeader();

		// Stop this feature if it's not allowed
		if (!$PowerBB->_CONF['info_row']['allow_avatar'])
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['You_can_not_use_this_feature']);
		}

		$PowerBB->_GET['count'] = (!isset($PowerBB->_GET['count'])) ? 0 : $PowerBB->_GET['count'];
		$PowerBB->_GET['count'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['count'],'intval');

		$AvaArr 					= 	array();
		$AvaArr['proc'] 			= 	array();
		$AvaArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');
		$AvaArr['order']			=	array();
		$AvaArr['order']['field']	=	'id';
		$AvaArr['order']['type']	=	'DESC';

		// Pager setup
		$AvaArr['pager'] 				= 	array();
		$AvaArr['pager']['total']		= 	$PowerBB->avatar->GetAvatarNumber(null);
		$AvaArr['pager']['perpage'] 	= 	$PowerBB->_CONF['info_row']['avatar_perpage'];
		$AvaArr['pager']['count'] 		= 	$PowerBB->_GET['count'];
		$AvaArr['pager']['location'] 	= 	'index.php?page=usercp&amp;control=1&amp;avatar=1&amp;main=1';
		$AvaArr['pager']['var'] 		= 	'count';

		$PowerBB->_CONF['template']['while']['AvatarList'] = $PowerBB->core->GetList($AvaArr,'avatar');
     	if ($PowerBB->avatar->GetAvatarNumber(null) > $PowerBB->_CONF['info_row']['avatar_perpage'])
		{
		$PowerBB->template->assign('pager',$PowerBB->pager->show());
		}
		$PowerBB->template->assign('count',$PowerBB->_GET['count']);

		$PowerBB->_CONF['template']['_CONF']['lang']['max_avatar'] = str_replace('هو 150',"هو ".$PowerBB->_CONF['info_row']['max_avatar_width'],$PowerBB->_CONF['template']['_CONF']['lang']['max_avatar']);
		$PowerBB->_CONF['template']['_CONF']['lang']['max_avatar'] = str_replace('في 150',"في ".$PowerBB->_CONF['info_row']['max_avatar_height'],$PowerBB->_CONF['template']['_CONF']['lang']['max_avatar']);
			if (strstr(!$PowerBB->_CONF['template']['_CONF']['lang']['max_avatar'],"هو"))
			{
				$PowerBB->_CONF['template']['_CONF']['lang']['max_avatar'] = "";
			}
		$PowerBB->template->display('usercp_control_avatar');

	}

	function _AvatarChange()
	{
		global $PowerBB;

		$PowerBB->_POST['options'] = $PowerBB->Powerparse->censor_words($PowerBB->_POST['options']);
		$PowerBB->_POST['options'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['options'],'html');
		$PowerBB->_POST['options'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['options'],'sql');

		$PowerBB->_POST['avatar'] = $PowerBB->Powerparse->censor_words($PowerBB->_POST['avatar']);
		$PowerBB->_POST['avatar'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['avatar'],'trim');
		$PowerBB->_POST['avatar'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['avatar'],'html');
		$PowerBB->_POST['avatar'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['avatar'],'sql');

		$PowerBB->_POST['avatar_list'] = $PowerBB->Powerparse->censor_words($PowerBB->_POST['avatar_list']);
		$PowerBB->_POST['avatar_list'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['avatar_list'],'trim');
		$PowerBB->_POST['avatar_list'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['avatar_list'],'html');
		$PowerBB->_POST['avatar_list'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['avatar_list'],'sql');


		$PowerBB->functions->ShowHeader($PowerBB->_CONF['template']['_CONF']['lang']['execution_Process_Update']);

		$PowerBB->functions->AddressBar('<a href="index.php?page=usercp&index=1">' .$PowerBB->_CONF['template']['_CONF']['lang']['User_Control_Panel']. '</a> ' . $PowerBB->_CONF['info_row']['adress_bar_separate'] . $PowerBB->_CONF['template']['_CONF']['lang']['execution_Process_Update']);

		$allowed_array = array('.jpg','.gif','.png','.bmp','.jpeg');

		$UpdateArr 					= 	array();
		$UpdateArr['field']			=	array();

		$UpdateArr['where']					= 	array('id',$PowerBB->_CONF['member_row']['id']);
		$UpdateArr['field']['avater_path'] 	= 	'';

		if ($PowerBB->_POST['options'] == 'no')
		{

		$MemberArr 			= 	array();
		$MemberArr['where'] 	= 	array('username',$PowerBB->_CONF['rows']['member_row']['username']);

		$MemberInfo = $PowerBB->core->GetInfo($MemberArr,'member');

	      if (file_exists($MemberInfo['avater_path']))
	      {
	       if(strstr($MemberInfo['avater_path'],'download/avatar'))
	       {
		    $del = @unlink($MemberInfo['avater_path']);
		   }
          }

			$PowerBB->_CONF['param']['UpdateArr']['path'] = '';
		}
		elseif ($PowerBB->_POST['options'] == 'list')
		{
			if (empty($PowerBB->_POST['avatar_list']))
			{
				$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_select_the_desired_image']);
			}

			$UpdateArr['field']['avater_path'] = $PowerBB->_POST['avatar_list'];
		}
		elseif ($PowerBB->_POST['options'] == 'site')
		{
			if (empty($PowerBB->_POST['avatar'])
				or $PowerBB->_POST['avatar'] == 'http://')
			{
				$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_select_the_desired_image']);
			}
			elseif (!$PowerBB->functions->IsSite($PowerBB->_POST['avatar']))
			{
				$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['The_site_that_you_typed_is_incorrect']);
			}

				$extension = $PowerBB->functions->GetURLExtension($PowerBB->_POST['avatar']);

				if (!in_array($extension,$allowed_array))
				{
					$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Along_the_image_is_not_permitted']);
				}
				if (strstr($PowerBB->_POST['avatar'],'alert')
					or strstr($PowerBB->_POST['avatar'],"body")
					or strstr($PowerBB->_POST['avatar'],'>')
					or strstr($PowerBB->_POST['avatar'],'<'))
		      	{
					   $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Along_the_image_is_not_permitted']);
		      	}

            if (strstr($PowerBB->_POST['avatar'],'.gif')
            or strstr($PowerBB->_POST['avatar'],'.png')
            or strstr($PowerBB->_POST['avatar'],'.bmp')
            or strstr($PowerBB->_POST['avatar'],'.jpg')
            or strstr($PowerBB->_POST['avatar'],'.jpeg'))
           {

                if(is_numeric($PowerBB->_POST['width'])
                and is_numeric($PowerBB->_POST['width']))
                {                $size[0] = $PowerBB->_POST['width'];
                $size[1] = $PowerBB->_POST['width'];
                }
                else
                 {
				 $size = @getimagesize($PowerBB->_POST['avatar']);
                 }

				if ($size[0] > $PowerBB->_CONF['info_row']['max_avatar_width'])
				{
					$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['width_the_image_is_not_acceptable']);
				}

				if ($size[1] > $PowerBB->_CONF['info_row']['max_avatar_height'])
				{
					$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['height_the_image_is_not_acceptable']);
				}

				$UpdateArr['field']['avater_path'] = $PowerBB->_POST['avatar'];

			}
			else
			{
			   $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Along_the_image_is_not_permitted']);
			}
		}
		elseif ($PowerBB->_POST['options'] == 'upload')
		{
			$pic = $PowerBB->_FILES['upload']['tmp_name'];

			$size = @getimagesize($pic);

			if ($size[0] > $PowerBB->_CONF['info_row']['max_avatar_width'])
			{
			 $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['width_the_image_is_not_acceptable']);
			}

			if ($size[1] > $PowerBB->_CONF['info_row']['max_avatar_height'])
			{
			 $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['height_the_image_is_not_acceptable']);
			}

	       if (!$size[0])
			{
             $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Can_you_raise_file_extension']);
			}

               $BAD_TYPES = array("image/gif",
                    "image/pjpeg",
                    "image/jpeg",
                    "image/png",
                    "image/jpg",
                    "image/bmp",
                    "image/x-png");


			   if(!in_array($PowerBB->_FILES['upload']['type'],$BAD_TYPES))
			   {
			      $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Along_the_image_is_not_permitted']);
			   }

     		if (!empty($PowerBB->_FILES['upload']['name']))
     		{
     			//////////

     			// Get the extension of the file
     			$ext = $PowerBB->functions->GetFileExtension($PowerBB->_FILES['upload']['name']);

     			// Bad try!
     			if ($ext == 'MULTIEXTENSION'
     				or !$ext)
     			{
     			}
     			else
     			{
	     			// Convert the extension to small case
    	 			$ext = strtolower($ext);

    	 			// The extension is not allowed
    	 			if (!array($ext,$allowed_array))
					{
						$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Along_the_image_is_not_permitted']);
					}
    	 			else
    	 			{
    	 				// Set the name of the file

    	 				$filename = $PowerBB->_FILES['upload']['name'];

    	 				// There is a file which has same name, so change the name of the new file
    	 				if (file_exists($PowerBB->_CONF['info_row']['download_path'] . '/avatar/' . $filename))
    	 				{
    	 					$filename = $PowerBB->functions->RandomCode(). '-' . $PowerBB->_FILES['upload']['name'];
    	 				}

    	 				//////////
    	 				// Copy the file to download dirctory
    	 				$copy = move_uploaded_file($PowerBB->_FILES['upload']['tmp_name'],$PowerBB->_CONF['info_row']['download_path'] . '/avatar/' . $filename);

    	 				// Success
    	 				if ($copy)
    	 				{
    	 					// Change avatar to the new one
    	 					$UpdateArr['field']['avater_path'] = $PowerBB->_CONF['info_row']['download_path'] . '/avatar/' . $filename;
    	 				}

    	 				//////////
    	 			}
    	 		}
    	 	}
    	}
		else
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Please_wait']);
            $PowerBB->functions->redirect('index.php?page=usercp&control=1&avatar=1&main=1',2);
			$PowerBB->functions->stop();
		}

		$UpdateAvatar = $PowerBB->core->Update($UpdateArr,'member');
       $PowerBB->functions->_AllCacheStart();
		if ($UpdateAvatar)
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Updated_successfully']);
           $PowerBB->functions->redirect('index.php?page=usercp&control=1&avatar=1&main=1',2);
		}

	}


	function _ReplyListMain()
	{
		//TODO later ...
	}

	function _SubjectListMain()
	{
		global $PowerBB;

		$PowerBB->functions->ShowHeader();

		$PowerBB->_GET['count'] = (!isset($PowerBB->_GET['count'])) ? 0 : $PowerBB->_GET['count'];
		$PowerBB->_GET['count'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['count'],'intval');

      	// Get the Member Subjects num
      	$writer = $PowerBB->_CONF['rows']['member_row']['username'];
        $GetMemberSubjectNum = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['subject'] . " WHERE writer = '$writer'"));
       	$PowerBB->template->assign('member_subject_num',$GetMemberSubjectNum );

       // Get the Member Subjects information
		$SubjectArr 							= 	array();
		$SubjectArr['where'] 					= 	array();

		$SubjectArr['where'][0] 				= 	array();
		$SubjectArr['where'][0]['name'] 		= 	'writer';
		$SubjectArr['where'][0]['oper'] 		= 	'=';
		$SubjectArr['where'][0]['value'] 		= 	$PowerBB->_CONF['rows']['member_row']['username'];

	   // Pager setup
		$SubjectArr['pager'] 				= 	array();
		$SubjectArr['pager']['total']		= 	$GetMemberSubjectNum;
		$SubjectArr['pager']['perpage'] 	= 	$PowerBB->_CONF['info_row']['subject_perpage'];
		$SubjectArr['pager']['count'] 		= 	$PowerBB->_GET['count'];
		$SubjectArr['pager']['location'] 	= 	'index.php?page=usercp&options=1&subject=1&main=1';
		$SubjectArr['pager']['var'] 		= 	'count';

		$SubjectArr['order'] 					=	 array();
		$SubjectArr['order']['field'] 			= 	'id';
		$SubjectArr['order']['type'] 			= 	'DESC';

		$SubjectArr['proc']['native_write_time'] 	= 	array('method'=>'date','store'=>'write_date','type'=>$PowerBB->_CONF['info_row']['timesystem']);
		$SubjectArr['proc']['write_time'] 			= 	array('method'=>'date','store'=>'reply_date','type'=>$PowerBB->_CONF['info_row']['timesystem']);


		$PowerBB->_CONF['template']['while']['MemberSubjects'] = $PowerBB->core->GetList($SubjectArr,'subject');
		$PowerBB->functions->CleanVariable($PowerBB->_CONF['template']['while']['MemberSubjects'],'html');

            if ($GetMemberSubjectNum > $PowerBB->_CONF['info_row']['subject_perpage'])
	        {
			$PowerBB->template->assign('pager',$PowerBB->pager->show());
	         }

		$PowerBB->template->display('usercp_options_subjects');

	}


	function _AttachListMain()
	{
		global $PowerBB;

		$PowerBB->functions->ShowHeader();

		$PowerBB->_GET['count'] = (!isset($PowerBB->_GET['count'])) ? 0 : $PowerBB->_GET['count'];
		$PowerBB->_GET['count'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['count'],'intval');

      	// Get the Member attachments num
      	$u_id = $PowerBB->_CONF['member_row']['id'];
        $GetMemberAttachmentNum = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['attach'] . " WHERE u_id = '$u_id'"));
       	$PowerBB->template->assign('member_Attachment_num',$GetMemberAttachmentNum );

		// Get the attachment information

			$AttachArr 							= 	array();
			$AttachArr['where']					= 	array();

			$AttachArr['where'][0] 				=	array();
			$AttachArr['where'][0]['name'] 		=	'u_id';
			$AttachArr['where'][0]['oper'] 		=	'=';
			$AttachArr['where'][0]['value'] 	=	$PowerBB->_CONF['member_row']['id'];

		   // Pager setup
			$AttachArr['pager'] 				= 	array();
			$AttachArr['pager']['total']		= 	$GetMemberAttachmentNum;
			$AttachArr['pager']['perpage'] 	= 	$PowerBB->_CONF['info_row']['subject_perpage'];
			$AttachArr['pager']['count'] 		= 	$PowerBB->_GET['count'];
			$AttachArr['pager']['location'] 	= 	'index.php?page=usercp&options=1&attach=1&main=1';
			$AttachArr['pager']['var'] 		= 	'count';

			$AttachArr['order'] 					=	 array();
			$AttachArr['order']['field'] 			= 	'id';
			$AttachArr['order']['type'] 			= 	'DESC';


			$PowerBB->_CONF['template']['while']['MemberAttachList'] = $PowerBB->core->GetList($AttachArr,'attach');


				if ($PowerBB->_CONF['template']['while']['MemberAttachList'] == false)
				{
					$PowerBB->template->assign('member_attach_nm',true);
				}
				else
				{
					$PowerBB->template->assign('member_attach_nm',false);
				}


		$PowerBB->functions->CleanVariable($PowerBB->_CONF['template']['while']['MemberAttachList'],'html');

            if ($GetMemberAttachmentNum > $PowerBB->_CONF['info_row']['subject_perpage'])
	        {
			$PowerBB->template->assign('pager',$PowerBB->pager->show());
	         }

        $PowerBB->template->assign('table_subject',$PowerBB->table['subject']);
        $PowerBB->template->assign('table_reply',$PowerBB->table['reply']);
		$PowerBB->template->display('usercp_options_attach');

	}

	function _EmailedListMain()
	{
		global $PowerBB;

		$PowerBB->functions->ShowHeader();


		$PowerBB->_GET['count'] = (!isset($PowerBB->_GET['count'])) ? 0 : $PowerBB->_GET['count'];
		$PowerBB->_GET['count'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['count'],'intval');

      		// Get the Emailed num
      	$user_id = $PowerBB->_CONF['member_row']['id'];
        $GetEmailedNum = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['emailed'] . " WHERE user_id = '$user_id'"));
       	$PowerBB->template->assign('emailed_num',$GetEmailedNum );
		// Get the Emailed information
			$EmailedArr 							= 	array();
			$EmailedArr['where']					= 	array();

			$EmailedArr['where'][0] 				=	array();
			$EmailedArr['where'][0]['name'] 		=	'user_id';
			$EmailedArr['where'][0]['oper'] 		=	'=';
			$EmailedArr['where'][0]['value'] 	=	$PowerBB->_CONF['member_row']['id'];

		   // Pager setup
			$EmailedArr['pager'] 				= 	array();
			$EmailedArr['pager']['total']		= 	$GetEmailedNum;
			$EmailedArr['pager']['perpage'] 	= 	$PowerBB->_CONF['info_row']['subject_perpage'];
			$EmailedArr['pager']['count'] 		= 	$PowerBB->_GET['count'];
			$EmailedArr['pager']['location'] 	= 	'index.php?page=usercp&options=1&emailed=1&main=1';
			$EmailedArr['pager']['var'] 		= 	'count';

			$EmailedArr['order'] 					=	 array();
			$EmailedArr['order']['field'] 			= 	'id';
			$EmailedArr['order']['type'] 			= 	'DESC';


			$PowerBB->_CONF['template']['while']['MemberEmailedList'] = $PowerBB->core->GetList($EmailedArr,'emailed');

				if ($PowerBB->_CONF['template']['while']['MemberEmailedList'] == false)
				{
					$PowerBB->template->assign('show_emailed',true);
				}
				else
				{
					$PowerBB->template->assign('show_emailed',false);
				}


		$PowerBB->functions->CleanVariable($PowerBB->_CONF['template']['while']['MemberEmailedList'],'html');


            if ($GetEmailedNum > $PowerBB->_CONF['info_row']['subject_perpage'])
	        {
			$PowerBB->template->assign('pager',$PowerBB->pager->show());
	        }


		$PowerBB->template->display('usercp_options_emailed');

	}

		/**
	 * add Friends Start
	 */
	function _FriendsAdd()
	{
		global $PowerBB;

		$PowerBB->functions->ShowHeader();
         		$PowerBB->_POST['username_friend'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['username_friend'],'trim');
         		$PowerBB->_POST['username_friend'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['username_friend'],'html');
      		    $PowerBB->_POST['username_friend'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['username_friend'],'sql');
        		$PowerBB->_GET['username_friend'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_GET['username_friend'],'trim');
         		$PowerBB->_GET['username_friend'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_GET['username_friend'],'html');
      		    $PowerBB->_GET['username_friend'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_GET['username_friend'],'sql');
      	$PowerBB->_POST['username_friend']= $PowerBB->_GET['username_friend'].$PowerBB->_POST['username_friend'];

  		$MemberArr 			= 	array();
		$MemberArr['where'] 	= 	array('username',$PowerBB->_POST['username_friend']);

		$MemberInfo = $PowerBB->core->GetInfo($MemberArr,'member');

       if (empty($PowerBB->_POST['username_friend']) && empty($PowerBB->_GET['username_friend']))
       {
          $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_fill_in_all_the_information']);
       }
       if (empty($PowerBB->_POST['username_friend']) && !empty($PowerBB->_GET['username_friend']))
       {
          $PowerBB->_POST['username_friend'] = $PowerBB->_GET['username_friend'];
       }

 		$PowerBB->functions->CleanVariable($PowerBB->_POST['username_friend'],'html');

		if (!$MemberInfo)
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Member_you_want_does_not_exist']);
		}

		if ($PowerBB->_POST['username_friend'] == $PowerBB->_CONF['member_row']['username'])
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Can_not_be_friends_with_yourself']);
		}


		  $username_friendInfo = $PowerBB->_POST['username_friend'];
		  $username_member_row = $PowerBB->_CONF['member_row']['username'];

		  $sql_friendInfo1 = $PowerBB->DB->sql_query("SELECT  *   FROM " . $PowerBB->table['friends'] . " WHERE username = '$username_member_row'");

	       while ($getSection_row1 = $PowerBB->DB->sql_fetch_array($sql_friendInfo1))
	      {
			if ($username_friendInfo == $getSection_row1['username_friend']
			or $username_member_row == $getSection_row1['username_friend'])
			{
				$PowerBB->functions->error($PowerBB->_POST['username_friend'].$PowerBB->_CONF['template']['_CONF']['lang']['thess_friend_adding_befor']);
			}

          }

		  $sql_friendInfo2 = $PowerBB->DB->sql_query("SELECT  *   FROM " . $PowerBB->table['friends'] . " WHERE username = '$username_friendInfo'");

	       while ($getSection_row2 = $PowerBB->DB->sql_fetch_array($sql_friendInfo2))
	      {
			if ($username_friendInfo == $getSection_row2['username_friend']
			or $username_member_row == $getSection_row2['username_friend'])
			{
				$PowerBB->functions->error($PowerBB->_POST['username_friend'].$PowerBB->_CONF['template']['_CONF']['lang']['thess_friend_adding_befor']);
			}

          }
      /*
		  $sql_friendInfo2 = $PowerBB->DB->sql_query("SELECT  *   FROM " . $PowerBB->table['friends'] . " WHERE username = '$username_friendInfo' ");

	       while ($getSection_row2 = $PowerBB->DB->sql_fetch_array($sql_friendInfo2))
	      {
			if ($PowerBB->_POST['username_friend'] == $getSection_row2['username'])
			{
				$PowerBB->functions->error($PowerBB->_POST['username_friend'].$PowerBB->_CONF['template']['_CONF']['lang']['thess_friend_adding_befor']);
			}

          }

         */
			$FriendsArr 			= 	array();
			$FriendsArr['field']	=	array();

			$FriendsArr['field']['username']                = 	$PowerBB->_CONF['member_row']['username'];
			$FriendsArr['field']['username_friend'] 		= 	$PowerBB->_POST['username_friend'];
			$FriendsArr['field']['userid_friend'] 		    = 	$MemberInfo['id'];
			$FriendsArr['field']['approval'] 	    	    = 	'0';

			$insert = $PowerBB->core->Insert($FriendsArr,'friends');

			if ($insert)
			{
	          $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Friendship_request_has_been_sent_successfully']);
             $PowerBB->functions->redirect($PowerBB->_SERVER['HTTP_REFERER']);
			}

	}

	function _FriendsMain()
	{
		global $PowerBB;
		$PowerBB->functions->ShowHeader();
		if ($PowerBB->_CONF['info_row']['active_friend'] == '0')
		{
			$PowerBB->functions->ShowHeader();
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Sorry_you_This feature was disabled']);
			$PowerBB->functions->GetFooter();
		}
        // show Friends List
		$FriendsArr 					= 	array();
		$FriendsArr['order']			=	array();
		$FriendsArr['order']['field']	=	'id';
		$FriendsArr['order']['type']	=	'DESC';
		$FriendsArr['proc'] 			= 	array();
		$FriendsArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');

		$FriendsArr['where'] 				= 	array();
		$FriendsArr['where'][0] 			= 	array();
		$FriendsArr['where'][0]['name'] 	=  'username';
		$FriendsArr['where'][0]['oper']		=  '=';
		$FriendsArr['where'][0]['value']    =  $PowerBB->_CONF['member_row']['username'];

		$PowerBB->_CONF['template']['while']['FriendsList'] = $PowerBB->core->GetList($FriendsArr,'friends');

		if ($PowerBB->_CONF['template']['while']['FriendsList'] == false)
		{
			$PowerBB->template->assign('No_Friends',true);
		}
     	else
		{
			$PowerBB->template->assign('No_Friends',false);
		}
		// show approval Friends List
		$FriendsApprovalArrr 					= 	array();
		$FriendsApprovalArrr['order']			=	array();
		$FriendsApprovalArrr['order']['field']	=	'id';
		$FriendsApprovalArrr['order']['type']	=	'DESC';
		$FriendsApprovalArrr['proc'] 			= 	array();
		$FriendsApprovalArrr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');

		$FriendsApprovalArrr['where'] 				= 	array();
		$FriendsApprovalArrr['where'][0] 			= 	array();
		$FriendsApprovalArrr['where'][0]['name'] 	=  'username_friend';
		$FriendsApprovalArrr['where'][0]['oper']		=  '=';
		$FriendsApprovalArrr['where'][0]['value']    =  $PowerBB->_CONF['member_row']['username'];

		$FriendsApprovalArrr['where'][1] 			= 	array();
		$FriendsApprovalArrr['where'][1]['con']		=	'AND';
		$FriendsApprovalArrr['where'][1]['name'] 	= 	'approval';
		$FriendsApprovalArrr['where'][1]['oper'] 	= 	'=';
		$FriendsApprovalArrr['where'][1]['value'] 	= 	'0';

		$PowerBB->_CONF['template']['while']['FriendsApprovalList'] = $PowerBB->core->GetList($FriendsApprovalArrr,'friends');

		if ($PowerBB->_CONF['template']['while']['FriendsApprovalList'] == false)
		{
			$PowerBB->template->assign('No_Approval_Friends',true);
		}
		else
		{
			$PowerBB->template->assign('No_Approval_Friends',false);
		}

		$PowerBB->template->display('friends_main');
	}


	function _ApprovalFriendStart()
	{
		global $PowerBB;
		$PowerBB->functions->ShowHeader();

        $PowerBB->_GET['id']	= 	$PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');

			if (empty($PowerBB->_GET['id']))
			{
				$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Friendship_request_is_required_does_not_exist']);
			}

		$FriendsArr 			= 	array();
		$FriendsArr['field']	=	array();

		$FriendsArr['field']['approval'] 	    	    = 	'1';
		$FriendsArr['where'] 				            = 	array('id',$PowerBB->_GET['id']);

		$update = $PowerBB->core->Update($FriendsArr,'friends');

		if ($update)
		{

			$Memberfriend1Arr 			= 	array();
		    $Memberfriend1Arr['where'] 	= 	array('username',$PowerBB->_GET['username']);

		    $Memberfriend1Info = $PowerBB->core->GetInfo($Memberfriend1Arr,'member');

			$FriendsArr 			= 	array();
			$FriendsArr['field']	=	array();

			$FriendsArr['field']['username']                = 	$PowerBB->_CONF['member_row']['username'];
			$FriendsArr['field']['username_friend'] 		= 	$PowerBB->_GET['username'];
			$FriendsArr['field']['userid_friend'] 		    = 	$Memberfriend1Info['id'];
			$FriendsArr['field']['approval'] 	    	    = 	'1';

			$insert = $PowerBB->core->Insert($FriendsArr,'friends');

			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Has_been_accepted_friendship_request_specific']);
			$PowerBB->functions->redirect('index.php?page=usercp&amp;options=1&amp;friends=1&amp;main=1');
		}
	}

	function _FriendsDel()
	{
		global $PowerBB;
		$PowerBB->functions->ShowHeader();

			if (empty($PowerBB->_GET['id']))
			{
				$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Friendship_request_is_required_does_not_exist']);
			}

       $PowerBB->_GET['id']	= 	$PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');
       $PowerBB->_GET['userid']	= 	$PowerBB->functions->CleanVariable($PowerBB->_GET['userid'],'intval');


			$DelArr 			= 	array();
			$DelArr['where'] 	= 	array('id',$PowerBB->_GET['id']);

			$del = $PowerBB->friends->DeleteFriends($DelArr);


       // info member
		$MemberArr 				= 	array();
		$MemberArr['where']		=	array('id',$PowerBB->_GET['userid']);

		$PowerBB->_CONF['template']['MemberInfo'] = $PowerBB->core->GetInfo($MemberArr,'member');

       $IsFreind = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['friends'] . " WHERE username='".$PowerBB->_CONF['template']['MemberInfo']['username']."' AND username_friend='".$PowerBB->_CONF['member_row']['username']."' or username_friend='".$PowerBB->_CONF['template']['MemberInfo']['username']."' AND username='".$PowerBB->_CONF['member_row']['username']."'" );
       $IsFreind_row = $PowerBB->DB->sql_fetch_array($IsFreind);
		if($PowerBB->DB->sql_num_rows($IsFreind) > 0)
		{
       		$Del1Arr 			= 	array();
			$Del1Arr['where'] 	= 	array('id',$IsFreind_row['id']);

			$del1 = $PowerBB->core->Deleted($Del1Arr,'friends');

		}



				$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['friends_delet_successfully']);
		$PowerBB->functions->redirect($PowerBB->_SERVER['HTTP_REFERER']);


	}

   function _DeleteAttachments()
	{
		global $PowerBB;
		$PowerBB->functions->ShowHeader();


		if (empty($PowerBB->_POST['check']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['You_do_not_select_any_attach']);
			$PowerBB->functions->GetFooter();

		}
		if ($PowerBB->_POST['check'] == 0)
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['You_do_not_select_any_attach']);
			$PowerBB->functions->GetFooter();
		 }

       $Attach_D = $PowerBB->_POST['check'];


       foreach ($Attach_D as $DeleteAttach)
       {
		$DeleteAttach = $PowerBB->functions->CleanVariable($DeleteAttach,'intval');
		$DeleteAttach = $PowerBB->functions->CleanVariable($DeleteAttach,'sql');

			   $GetAttachArr 					= 	array();
			   $GetAttachArr['where'] 			= 	array('id',intval($DeleteAttach));
			   $Attachinfo = $PowerBB->core->GetInfo($GetAttachArr,'attach');

		      if (file_exists($Attachinfo['filepath']))
		      {
			   $del = @unlink($Attachinfo['filepath']);
              }

		      if ($Attachinfo['u_id'] != $PowerBB->_CONF['member_row']['id'])
		      {
				$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
				$PowerBB->functions->error_stop();
				$PowerBB->functions->GetFooter();
              }
				// Delete attachment from database
				$DelAttachArr 							= 	array();
				$DelAttachArr['name'] 	        		=  	'id';
		        $DelAttachArr['where'] 		    	= 	array('id',intval($DeleteAttach));

				$DeleteAttach = $PowerBB->core->Deleted($DelAttachArr,'attach');

       }


                $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['deleted_Attachments_successfully']);
				$PowerBB->functions->redirect('index.php?page=usercp&amp;options=1&amp;attach=1&amp;main=1');
	}

	function _ReputationListMain()
	{
		global $PowerBB;

		$PowerBB->functions->ShowHeader();

		$PowerBB->_GET['count'] = (!isset($PowerBB->_GET['count'])) ? 0 : $PowerBB->_GET['count'];
		$PowerBB->_GET['count'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['count'],'intval');

      	// Get the Member Subjects num
      	$username = $PowerBB->_CONF['rows']['member_row']['username'];
        $GetMemberReputationNum = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['reputation'] . " WHERE username = '$username'"));
       	$PowerBB->template->assign('member_reputation_num',$GetMemberReputationNum );


		/** Get the Reputation information **/
		$ReputArr 							= 	array();
		$ReputArr['where'] 					= 	array();

		$ReputArr['where'][0] 				= 	array();
		$ReputArr['where'][0]['name'] 		= 	'username';
		$ReputArr['where'][0]['oper'] 		= 	'=';
		$ReputArr['where'][0]['value'] 		= 	$PowerBB->_CONF['rows']['member_row']['username'];

		$ReputArr['order'] 					=	 array();
		$ReputArr['order']['field'] 			= 	'id';
		$ReputArr['order']['type'] 			= 	'DESC';
	   // Pager setup
		$ReputArr['pager'] 				= 	array();
		$ReputArr['pager']['total']		= 	$GetMemberReputationNum;
		$ReputArr['pager']['perpage'] 	= 	$PowerBB->_CONF['info_row']['subject_perpage'];
		$ReputArr['pager']['count'] 		= 	$PowerBB->_GET['count'];
		$ReputArr['pager']['location'] 	= 	'index.php?page=usercp&amp;options=1&amp;reputation=1&amp;main=1';
		$ReputArr['pager']['var'] 		= 	'count';

		$ReputArr['proc'] 						= 	array();
		$ReputArr['proc']['*'] 					= 	array('method'=>'clean','param'=>'html');
		$ReputArr['proc']['reputationdate'] 	= 	array('method'=>'date','store'=>'reputationdate','type'=>$PowerBB->_CONF['info_row']['timesystem']);

       $PowerBB->_CONF['template']['while']['MemberReputation'] = $PowerBB->reputation->GetReputationList($ReputArr);
       $this->ReputationInfo = $PowerBB->core->GetInfo($ReputArr,'reputation');

            if ($GetMemberReputationNum > $PowerBB->_CONF['info_row']['subject_perpage'])
	        {
			  $PowerBB->template->assign('pager',$PowerBB->pager->show());
	         }

        // If Reputation  by reply Get subject_id for thes reply
		$ReplyArr = array();
		$ReplyArr['where'] = array('id',$this->ReputationInfo['reply_id']);

		$ReplyInfo = $PowerBB->core->GetInfo($GetReplyInfo,'reply');

		$PowerBB->template->assign('subject_id',$ReplyInfo['subject_id']);

		if ($PowerBB->_CONF['template']['while']['MemberReputation'] == false)
		{
			$PowerBB->template->assign('No_Reputation',true);
		}
		else
		{
			$PowerBB->template->assign('No_Reputation',false);
		}

         // Update reputation read
		 $UpdateArr 				= 	array();
		 $UpdateArr['field']		=	array();

		 $UpdateArr['field']['reputationread'] 		= 	'0';
	     $UpdateArr['where'] 						= 	array('username',$PowerBB->_CONF['rows']['member_row']['username']);

		 $update = $PowerBB->core->Update($UpdateArr,'reputation');
		$PowerBB->template->display('usercp_reputations');

	}

	function _MentionListMain()
	{
		global $PowerBB;

		$PowerBB->functions->ShowHeader();

		$PowerBB->_GET['count'] = (!isset($PowerBB->_GET['count'])) ? 0 : $PowerBB->_GET['count'];
		$PowerBB->_GET['count'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['count'],'intval');

      	// Get the Member Subjects num
      	$username = $PowerBB->_CONF['rows']['member_row']['username'];
        $allmentionNumrs = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT *  FROM " . $PowerBB->prefix . "mention WHERE you = '$member_username'"));
       	$PowerBB->template->assign('member_mention_num',$allmentionNumrs );


		/** Get the Reputation information **/
		$MentionArr 							= 	array();
		$MentionArr['where'] 					= 	array();

		$MentionArr['where'][0] 				= 	array();
		$MentionArr['where'][0]['name'] 		= 	'you';
		$MentionArr['where'][0]['oper'] 		= 	'=';
		$MentionArr['where'][0]['value'] 		= 	$PowerBB->_CONF['rows']['member_row']['username'];

		$MentionArr['order'] 					=	 array();
		$MentionArr['order']['field'] 			= 	'id';
		$MentionArr['order']['type'] 			= 	'DESC';
	   // Pager setup
		$MentionArr['pager'] 				= 	array();
		$MentionArr['pager']['total']		= 	20;
		$MentionArr['pager']['perpage'] 	= 	$PowerBB->_CONF['info_row']['subject_perpage'];
		$MentionArr['pager']['count'] 		= 	$PowerBB->_GET['count'];
		$MentionArr['pager']['location'] 	= 	'index.php?page=usercp&amp;options=1&amp;mention=1&amp;main=1';
		$MentionArr['pager']['var'] 		= 	'count';

		$MentionArr['proc'] 						= 	array();
		$MentionArr['proc']['*'] 					= 	array('method'=>'clean','param'=>'html');
		$MentionArr['proc']['date'] 	= 	array('method'=>'date','store'=>'date','type'=>$PowerBB->_CONF['info_row']['timesystem']);

       $PowerBB->_CONF['template']['while']['member_mentions'] = $PowerBB->core->GetList($MentionArr,'mention');

       $this->MentionInfo = $PowerBB->_CONF['template']['while']['member_mentions'];

            if ($allmentionNumrs > $PowerBB->_CONF['info_row']['subject_perpage'])
	        {
			  $PowerBB->template->assign('pager',$PowerBB->pager->show());
	         }

        // If mentions Get it
		if ($PowerBB->_CONF['template']['while']['member_mentions'] == false)
		{
			$PowerBB->template->assign('No_Mentions',true);
		}
		else
		{
			$PowerBB->template->assign('No_Mentions',false);
		}

       $PowerBB->template->assign('mentionNumrs',$allmentionNumrs);

		$PowerBB->template->display('usercp_mentions');

	}

	function _MentionReadableStart()
	{
		global $PowerBB;
         // Update mention read
         if($PowerBB->_POST['ajax'])
         {
		  $PowerBB->_POST['id'] = $PowerBB->functions->CleanVariable($PowerBB->_POST['id'],'intval');


			 $UpdateArr 				= 	array();
			 $UpdateArr['field']		=	array();

			 $UpdateArr['field']['user_read'] 		= 	'0';
		     $UpdateArr['where'] 						= 	array('id',$PowerBB->_POST['id']);

			 $update = $PowerBB->core->Update($UpdateArr,'mention');

		 }

     }

}

?>