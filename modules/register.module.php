<?php
@session_start();

$CALL_SYSTEM				=	array();
$CALL_SYSTEM['BANNED'] 		= 	true;
$CALL_SYSTEM['CACHE'] 		= 	true;
$CALL_SYSTEM['REQUEST'] 	= 	true;
$CALL_SYSTEM['MESSAGE'] 	= 	true;
$CALL_SYSTEM['EXTRAFIELD']   =   true;

(!defined('IN_PowerBB')) ? die() : '';



define('CLASS_NAME','PowerBBRegisterMOD');

include('common.php');
class PowerBBRegisterMOD
{
	function run()
	{
		global $PowerBB;

		if ($PowerBB->_CONF['info_row']['reg_close'])
		{
			$PowerBB->functions->ShowHeader();
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Registration_is_closed']);
			$PowerBB->functions->GetFooter();
		}

		if (!$PowerBB->_CONF['info_row']['reg_' . $PowerBB->_CONF['day']])
   		{
   			$PowerBB->functions->ShowHeader();
   			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['You_can_not_register_today']);
   			$PowerBB->functions->GetFooter();
   		}


		/** Show register form **/
		if ($PowerBB->_GET['index'])
		{
			if ($PowerBB->_CONF['info_row']['reg_o']
				and $PowerBB->_POST['agree'] !=1)
			{
				$this->_RegisterRules();
			}
			else
			{
				$this->_RegisterForm();
			}
		}
		/** **/
		  elseif ($PowerBB->_GET['checkname'])
		  {
		   $this->_CheckNameStart();
		  }
		  elseif ($PowerBB->_GET['checkemail'])
		  {
		   $this->_CheckEmailStart();
		  }
		/** Start registetr **/
		elseif ($PowerBB->_GET['start'])
		{
			$this->_RegisterStart();
		}
		else
		{
			header("Location: index.php");
			exit;
		}


	}

	/**
	 * Print registeration rules
	 */
	function _RegisterRules()
	{
		global $PowerBB;
		 $PowerBB->functions->ShowHeader();
		if ($PowerBB->_CONF['member_permission'])
		{
			$PowerBB->functions->error($PowerBB->_CONF['rows']['member_row']['username']." ".$PowerBB->_CONF['template']['_CONF']['lang']['You_are_already_registered']);
		}
		else
		{
		  $PowerBB->Powerparse->replace_smiles($PowerBB->_CONF['info_row']['rules']);
         $PowerBB->_CONF['info_row']['rules'] = $PowerBB->Powerparse->replace($PowerBB->_CONF['info_row']['rules']);
         $PowerBB->template->assign('rules',$PowerBB->_CONF['info_row']['rules']);

		$PowerBB->template->display('register_rules');
		}
		$PowerBB->functions->GetFooter();
	}


	/**
	 * Show nice form for register :)
	 */
	function _RegisterForm()
	{
		global $PowerBB;

		$PowerBB->functions->ShowHeader();


		if ($PowerBB->_CONF['member_permission'])
		{
			$PowerBB->functions->error($PowerBB->_CONF['rows']['member_row']['username']." ".$PowerBB->_CONF['template']['_CONF']['lang']['You_are_already_registered']);
        	$PowerBB->functions->GetFooter();
		}
				//getting extra fields
         $PowerBB->_CONF['template']['while']['extrafields']=$PowerBB->extrafield->getEmptyLoginFields();

		if ($PowerBB->_GET['invite'])
		{
         $PowerBB->_GET['invite']   =     $PowerBB->functions->CleanVariable($PowerBB->_GET['invite'],'intval');
         $MemberArr             =     array();
         $MemberArr['where']     =     array('id',$PowerBB->_GET['invite']);
         $GetInviterMemberInfo = $PowerBB->core->GetInfo($MemberArr,'member');
         $PowerBB->template->assign('invite',$GetInviterMemberInfo['username']);
		}

		if ($PowerBB->_CONF['info_row']['captcha_o'] == 1)
		{
	        if($PowerBB->_CONF['info_row']['captcha_type'] == 'captcha_Q_A')
			 {
				$question = $PowerBB->_CONF['info_row']['questions'];
				$answer = $PowerBB->_CONF['info_row']['answers'];
				$c1 = explode("\r\n",$question);
				$c2 = explode("\r\n",$answer);
				$rand = array_rand($c2);
				$question = $c1[$rand];
				$answer = $c2[$rand];
				$PowerBB->template->assign('question',$question);
				$PowerBB->template->assign('answer',$answer);
		     }
       	 }
        eval($PowerBB->functions->get_fetch_hooks('register_form'));

		$PowerBB->template->display('register');
        $PowerBB->functions->GetFooter();
	}

	/**
	 * Some checks then add the member to database
	 */
	function _RegisterStart()
	{
		global $PowerBB;



		if ($PowerBB->_CONF['member_permission'])
		{
			  header("Location: index.php");
			  exit;
        }
		if(is_numeric($PowerBB->_POST['username']))
		{
      		   $PowerBB->functions->ShowHeader();
			   $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['You_can_register_this_numbrs_name']);
			   $PowerBB->functions->GetFooter();
		}
	    if (strstr($PowerBB->_POST['username'],'"')
		or strstr($PowerBB->_POST['username'],"'")
		or strstr($PowerBB->_POST['username'],'>')
		or strstr($PowerBB->_POST['username'],'<')
		or strstr($PowerBB->_POST['username'],'*')
		or strstr($PowerBB->_POST['username'],'%')
		or strstr($PowerBB->_POST['username'],'$')
		or strstr($PowerBB->_POST['username'],'#')
		or strstr($PowerBB->_POST['username'],'+')
		or strstr($PowerBB->_POST['username'],'^')
		or strstr($PowerBB->_POST['username'],'&')
		or strstr($PowerBB->_POST['username'],',')
		or strstr($PowerBB->_POST['username'],'~')
		or strstr($PowerBB->_POST['username'],'@')
		or strstr($PowerBB->_POST['username'],'!')
		or strstr($PowerBB->_POST['username'],'{')
		or strstr($PowerBB->_POST['username'],'}')
		or strstr($PowerBB->_POST['username'],'(')
		or strstr($PowerBB->_POST['username'],')')
		or strstr($PowerBB->_POST['username'],'/'))
      	{
      		$PowerBB->functions->ShowHeader();
      		$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['You_can_not_register_these_symbols']);
			$PowerBB->functions->GetFooter();
      	}

	   // Stop any external post request.
		$Y = explode('/',$PowerBB->_SERVER['HTTP_REFERER']);
		$X = explode('/',$PowerBB->_SERVER['HTTP_HOST']);

		if (function_exists("curl_init")) {

			$url_ch_ip = "https://www.stopforumspam.com/api?ip=" .$PowerBB->_CONF['ip'];
		    $stop_ch_ip = $PowerBB->sys_functions->CURL_URL($url_ch_ip);
			if (stristr($stop_ch_ip, "yes")) {
			exit('spammer ip');
			}

			$url_ch_email = "https://www.stopforumspam.com/api?email=" .$PowerBB->_POST['email'];
		    $stop_ch_email = $PowerBB->sys_functions->CURL_URL($url_ch_email);

			if (stristr($stop_ch_email, "yes")) {
			exit('spammer email');
			}

		}
		elseif ($Y[2] != $X[0])
		{
		exit('No direct script access register allowed');
		}
		elseif ($Y[2] != $PowerBB->_SERVER['HTTP_HOST'])
		{
		exit('No direct script access register allowed');
		}
		elseif(empty($_SESSION['csrf']))
		{
		exit('No direct script access register allowed');
		}
		elseif(is_numeric($PowerBB->_POST['gender']))
		{
		exit('No direct script access register allowed');
		}


       ////

        eval($PowerBB->functions->get_fetch_hooks('register_start'));

		// Clean the username and email from white spaces
		$PowerBB->_POST['username'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['username'],'trim');
		$PowerBB->_POST['email'] 		= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['email'],'trim');

		$PowerBB->_POST['username'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['username'],'html');
		$PowerBB->_POST['username'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['username'],'sql');
		$PowerBB->_POST['email']   = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['email'],'sql');
		$PowerBB->_POST['email_confirm']   = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['email_confirm'],'sql');
       	$PowerBB->_POST['password_confirm']   = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['password_confirm'],'sql');
      	$PowerBB->_POST['invite']   = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['invite'],'sql');
        $PowerBB->_POST['code']   = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['code'],'sql');
       	$PowerBB->_POST['year']   = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['year'],'intval');
		$PowerBB->_POST['year']   = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['year'],'sql');
      	$PowerBB->_POST['username']  =  $PowerBB->Powerparse->censor_words($PowerBB->_POST['username']);

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


		// Store the email provider in explode_email[1] and the name of email in explode_email[0]
		// That will be useful to ban email provider
		$explode_email = explode('@',$PowerBB->_POST['email']);

		// Well , we get the provider of email
		$EmailProvider = $explode_email[1];

		// Ensure all necessary information are valid
		if (empty($PowerBB->_POST['username'])
			or empty($PowerBB->_POST['password'])
			or empty($PowerBB->_POST['email']))
		{

      		   $PowerBB->functions->ShowHeader();
			   $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_fill_in_all_the_information']);
			   $PowerBB->functions->GetFooter();
		}

		// Ensure the email is equal the confirm of email
		if ($PowerBB->_POST['email'] != $PowerBB->_POST['email_confirm'])
		{
      		$PowerBB->functions->ShowHeader();
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['email_confirm_Incorrectly']);
			$PowerBB->functions->GetFooter();
		}

		// Ensure the password is equal the confirm of password
		if ($PowerBB->_POST['password'] != $PowerBB->_POST['password_confirm'])
		{
      		$PowerBB->functions->ShowHeader();
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['password_confirm_Incorrectly']);
			$PowerBB->functions->GetFooter();
		}

		// Check if the email is valid, This line will prevent any false email
		if (!$PowerBB->functions->CheckEmail($PowerBB->_POST['email']))
		{
      		$PowerBB->functions->ShowHeader();
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_enter_your_correct_email']);
			$PowerBB->functions->GetFooter();
		}

		// Ensure there is no person used the same username
		if ($PowerBB->member->IsMember(array('where' => array('username',$PowerBB->_POST['username']))))
		{
      		$PowerBB->functions->ShowHeader();
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['please_choose_another_name']);
			$PowerBB->functions->GetFooter();
		}

		// Ensure there is no person used the same email
		if ($PowerBB->member->IsMember(array('where' => array('email',$PowerBB->_POST['email']))))
		{
      		$PowerBB->functions->ShowHeader();
		    $PowerBB->_CONF['template']['_CONF']['lang']['E-mail_is_registered_please_type_the_other'] = str_replace("{forget}","index.php?page=forget&index=1",$PowerBB->_CONF['template']['_CONF']['lang']['E-mail_is_registered_please_type_the_other']);
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['E-mail_is_registered_please_type_the_other']);
			$PowerBB->functions->GetFooter();
		}

		if ($PowerBB->banned->IsUsernameBanned(array('username'	=>	$PowerBB->_POST['username'])))
		{
      		$PowerBB->functions->ShowHeader();
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['You_can_not_register_this_name']);
			$PowerBB->functions->GetFooter();
		}

		if ($PowerBB->banned->IsEmailBanned(array('email'	=>	$PowerBB->_POST['email'])))
		{
      		$PowerBB->functions->ShowHeader();
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['You_can_not_register_this_e-mail']);
			$PowerBB->functions->GetFooter();
		}

		if ($PowerBB->banned->IsProviderBanned(array('provider'	=>	$EmailProvider)))
		{
      		$PowerBB->functions->ShowHeader();
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['You_can_not_register_this_provider_mail']);
			$PowerBB->functions->GetFooter();
		}

		if ($PowerBB->_POST['username'] == 'Guest'
		 or($PowerBB->_POST['username'] == 'admin'))
		{
      		$PowerBB->functions->ShowHeader();
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['can_not_register_Guest_name']);
			$PowerBB->functions->GetFooter();
		}

			if (function_exists('mb_strlen'))
			{
				$reg_less_num = @mb_strlen($PowerBB->_POST['username'], 'UTF-8') >= $PowerBB->_CONF['info_row']['reg_less_num'];
				$reg_max_num = @mb_strlen($PowerBB->_POST['username'], 'UTF-8') <= $PowerBB->_CONF['info_row']['reg_max_num'];
			}
			else
			{
				$reg_less_num = strlen(@utf8_decode($PowerBB->_POST['username'])) >= $PowerBB->_CONF['info_row']['reg_less_num'];
				$reg_max_num = strlen(@utf8_decode($PowerBB->_POST['username'])) <= $PowerBB->_CONF['info_row']['reg_max_num'];
			}

		   if($reg_less_num)
			{
			 // $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Character_name_a_few_user']);

			}
			else
			{
      		$PowerBB->functions->ShowHeader();
   			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Character_name_a_few_user']);
			$PowerBB->functions->GetFooter();
			}

		   if($reg_max_num)
			{
			 // $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['characters_Username_many']);
			}
			else
			{
      		$PowerBB->functions->ShowHeader();
      		$PowerBB->_CONF['template']['_CONF']['lang']['characters_Username_many'] = str_replace("{User_num}", $PowerBB->_CONF['info_row']['reg_max_num'], $PowerBB->_CONF['template']['_CONF']['lang']['characters_Username_many']);
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['characters_Username_many']);
			$PowerBB->functions->GetFooter();
			}

      	if (isset($PowerBB->_POST['password']{$PowerBB->_CONF['info_row']['reg_pass_max_num']+1}))
      	{
      		$PowerBB->functions->ShowHeader();
      		$PowerBB->_CONF['template']['_CONF']['lang']['Character_pass_many'] = str_replace("{paas_num}", $PowerBB->_CONF['info_row']['reg_pass_max_num'], $PowerBB->_CONF['template']['_CONF']['lang']['Character_pass_many']);
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Character_pass_many']);
			$PowerBB->functions->GetFooter();
      	}

      	if (!isset($PowerBB->_POST['password']{$PowerBB->_CONF['info_row']['reg_pass_min_num']-1}))
      	{
      		$PowerBB->functions->ShowHeader();
        	$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Character_pass_few']);
			$PowerBB->functions->GetFooter();
      	}

      	// Ensure there is no the same Member invite
      	if ($PowerBB->_POST['invite'] =='')
      	{
         // dont work any thing
        }
		else
   		{
			if (!$PowerBB->member->IsMember(array('where' => array('username',$PowerBB->_POST['invite']))))
			{
      		    $PowerBB->functions->ShowHeader();
				$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['please_correct_inviter_name']);
			    $PowerBB->functions->GetFooter();
			}
		}


		if ($PowerBB->_CONF['info_row']['captcha_o'] == 1)
		{
	        if($PowerBB->_CONF['info_row']['captcha_type'] == 'captcha_Q_A')
			 {
                if($PowerBB->_POST['code'] != $PowerBB->_POST['code_answer'])
				 {
	      		    $PowerBB->functions->ShowHeader();
		            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['random_answer_not_correct']);
				    $PowerBB->functions->GetFooter();
			     }
		     }
	        if($PowerBB->_CONF['info_row']['captcha_type'] == 'captcha_IMG')
			 {
		        if(md5($PowerBB->_POST['code']) != $_SESSION['captcha_key'])
				 {
	      		    $PowerBB->functions->ShowHeader();
		            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Code_that_you_enter_the_wrong']);
				    $PowerBB->functions->GetFooter();
			     }
		    }
       	 }

		//getting extra fields
         $extraFields=$PowerBB->extrafield->getEmptyLoginFields();
		//checking if the extra fields are required
		 foreach($extraFields AS $field)
		 {
		$field['name']   = 	$PowerBB->functions->CleanVariable($field['name'],'sql');

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

	   		if ($FieldsInfo['required'] == 'yes')
	   		{
		   		if (empty( $PowerBB->_POST[ $field['name_tag'] ] ))
		   		{
      		      $PowerBB->functions->ShowHeader();
		          $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['You_do_not_enter'].' <i><b>'.$field['name'].'</b></i>');
			      $PowerBB->functions->GetFooter();
		    	}
	      }

	    }

        $_SESSION['register_password'] = $PowerBB->_POST['password'];
        $_SESSION['register_username'] = $PowerBB->_POST['username'];
      	$password_fields = $PowerBB->functions->create_password($PowerBB->_POST['password'], false);
        $_SESSION['register_salt'] = $password_fields['salt'];

      	//////////

      	// Get the information of default group to set username style cache

		$GrpArr 			= 	array();
		$GrpArr['where'] 	= 	array('id',$PowerBB->_CONF['info_row']['def_group']);

		$GroupInfo = $PowerBB->core->GetInfo($GrpArr,'group');

		// invite_num
    $invite = $PowerBB->_POST['invite'];
	$update = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['member'] . " SET invite_num = invite_num + 1 WHERE username ='$invite'");

		$style = $GroupInfo['username_style'];
		$username_style_cache = str_replace('[username]',$PowerBB->_POST['username'],$style);

      	//////////

      	$InsertArr 					= 	array();
      	$InsertArr['field']			=	array();

      	$InsertArr['field']['username'] 			= 	$PowerBB->_POST['username'];
      	$InsertArr['field']['password'] 			= 	$password_fields['password'];
      	$InsertArr['field']['active_number'] 		= 	$password_fields['salt'];
      	$InsertArr['field']['email'] 				= 	$PowerBB->_POST['email'];
      	$InsertArr['field']['usergroup'] 			= 	$PowerBB->_CONF['info_row']['def_group'];
      	$InsertArr['field']['user_gender'] 			= 	$PowerBB->_POST['gender'];
      	$InsertArr['field']['inviter'] 			    = 	$PowerBB->_POST['invite'];
      	$InsertArr['field']['register_date'] 		= 	$PowerBB->_CONF['now'];
      	$InsertArr['field']['lastvisit'] 		    = 	$PowerBB->_CONF['now'];
      	$InsertArr['field']['user_title'] 			= 	$PowerBB->_CONF['template']['_CONF']['lang']['member'];
      	$InsertArr['field']['style'] 				= 	$PowerBB->_CONF['info_row']['def_style'];
      	$InsertArr['field']['lang'] 				= 	$PowerBB->_CONF['info_row']['def_lang'];
      	$InsertArr['field']['username_style_cache']	=	$username_style_cache;
      	$InsertArr['field']['bday_day']	            =	$PowerBB->_POST['day'];
      	$InsertArr['field']['bday_month']	        =	$PowerBB->_POST['month'];
      	$InsertArr['field']['bday_year']	        =	$PowerBB->_POST['year'];
        eval($PowerBB->functions->get_fetch_hooks('register_Insert_Member'));

      	      	//extra fields insertion
      	foreach($extraFields AS $field){
          $InsertArr['field'][ $field['name_tag'] ]     =   $PowerBB->functions->CleanVariable($PowerBB->_POST[ $field['name_tag'] ],'sql');
      	}
      	$InsertArr['get_id']						=	true;

      	$insert = $PowerBB->member->InsertMember($InsertArr);


      	// Ouf finally , but we still have work in this module
      	if ($insert)
      	{

      		if ($PowerBB->_CONF['info_row']['def_group'] != 5)
      		{
		      		// Send a private message welcoming new user.
			      	if($PowerBB->_CONF['info_row']['activ_welcome_message'])
			      	{
						// Send a private message welcoming new user.
						$Adress	= 	$PowerBB->functions->GetForumAdress();
						$Massege  = $PowerBB->_CONF['info_row']['welcome_message_text'] ;
						$Massege = str_replace('{username}',$PowerBB->_POST['username'],$Massege);
						$Massege = str_replace('{title}',$PowerBB->_CONF['info_row']['title']."\n",$Massege);
						$Massege = str_replace('{rules}',"<a href='".$Adress."index.php?page=misc&rules=1&show=1'>".$PowerBB->_CONF['template']['_CONF']['lang']['rules']."</a>",$Massege);
						$PowerBB->_CONF['template']['_CONF']['lang']['welcome_message_title'] = str_replace('{title}',$PowerBB->_CONF['info_row']['title'],$PowerBB->_CONF['template']['_CONF']['lang']['welcome_message_title']);
						$Massege_title = $PowerBB->_CONF['template']['_CONF']['lang']['welcome_message_title'];
						$user_from = "1";
						$MemberArr          =    array();
						$MemberArr['where']  =   array('id',$user_from);
						$MemberInfo = $PowerBB->member->GetMemberInfo($MemberArr);

				      	if($PowerBB->_CONF['info_row']['welcome_message_mail_or_private'] == '1')
				      	{
					       if ($PowerBB->_CONF['info_row']['mailer']=='phpmail')
							{
						      $Send = $PowerBB->functions->send_this_php($PowerBB->_POST['email'],$Massege_title,$Massege,$PowerBB->_CONF['info_row']['send_email']);
				            }
				           elseif ($PowerBB->_CONF['info_row']['mailer']=='smtp')
							{
								$to = $PowerBB->_POST['email'];
								$fromname = $PowerBB->_CONF['info_row']['title'];
								$messagetext = $Massege;
								$subject = $Massege_title;
								$from = $PowerBB->_CONF['info_row']['send_email'];
			                    $Send = $PowerBB->functions->send_this_smtp($to,$fromname,$messagetext,$subject,$from);
							}

				      	}
				      	elseif($PowerBB->_CONF['info_row']['welcome_message_mail_or_private'] == '2')
				      	{

							$MsgArr       =    array();
							$MsgArr['field']   =   array();
							$MsgArr['field']['user_from']  =   $MemberInfo['username'];
							$MsgArr['field']['user_to']    =   $PowerBB->_POST['username'];
							$MsgArr['field']['title']  =   $Massege_title;
							$MsgArr['field']['text']   =   $Massege;
							$MsgArr['field']['date']   =   $PowerBB->_CONF['now'];
							$MsgArr['field']['folder']   =   'inbox';
							$MsgArr['field']['icon']       =   'look/images/icons/i1.gif';

							$Send = $PowerBB->pm->InsertMassege($MsgArr);

							$NumberArr    = array();
							$NumberArr['username'] =   $PowerBB->_POST['username'];
							$Number = $PowerBB->pm->NewMessageNumber($NumberArr);

							$CacheArr        =  array();
							$CacheArr['field'] =    array();
							$CacheArr['field']['unread_pm']    =   $Number;

							$CacheArr['where']      =  array('username',$PowerBB->_POST['username']);

							$Cache = $PowerBB->member->UpdateMember($CacheArr);
						}
				      	elseif($PowerBB->_CONF['info_row']['welcome_message_mail_or_private'] == '3')
				      	{
							$MsgArr       =    array();
							$MsgArr['field']   =   array();
							$MsgArr['field']['user_from']  =   $MemberInfo['username'];
							$MsgArr['field']['user_to']    =   $PowerBB->_POST['username'];
							$MsgArr['field']['title']  =   $Massege_title;
							$MsgArr['field']['text']   =   $Massege;
							$MsgArr['field']['date']   =   $PowerBB->_CONF['now'];
							$MsgArr['field']['folder']   =   'inbox';
							$MsgArr['field']['icon']       =   'look/images/icons/i1.gif';

							$Send = $PowerBB->pm->InsertMassege($MsgArr);

							$NumberArr    = array();
							$NumberArr['username'] =   $PowerBB->_POST['username'];
							$Number = $PowerBB->pm->NewMessageNumber($NumberArr);

							$CacheArr        =  array();
							$CacheArr['field'] =    array();
							$CacheArr['field']['unread_pm']    =   $Number;

							$CacheArr['where']      =  array('username',$PowerBB->_POST['username']);

							$Cache = $PowerBB->member->UpdateMember($CacheArr);

					       if ($PowerBB->_CONF['info_row']['mailer']=='phpmail')
							{
						      $Send = $PowerBB->functions->send_this_php($PowerBB->_POST['email'],$Massege_title,$Massege,$PowerBB->_CONF['info_row']['send_email']);
				            }
				           elseif ($PowerBB->_CONF['info_row']['mailer']=='smtp')
							{
								$to = $PowerBB->_POST['email'];
								$fromname = $PowerBB->_CONF['info_row']['title'];
								$messagetext = $Massege;
								$subject = $Massege_title;
								$from = $PowerBB->_CONF['info_row']['send_email'];
			                    $Send = $PowerBB->functions->send_this_smtp($to,$fromname,$messagetext,$subject,$from);
							}

				      	}

			       }
            }
      		$member_num = $PowerBB->member->GetMemberNumber(array('get_from'	=>	'cache'));
      		$PowerBB->cache->UpdateLastMember(array(	'username'		=>	$PowerBB->_POST['username'],
      													'id'			=>	$PowerBB->member->id,
      													'member_num'	=>	$member_num));

      		if ($PowerBB->_CONF['info_row']['def_group'] == 5)
      		{
      			$Adress	= 	$PowerBB->functions->GetForumAdress();
				$Code	=	$PowerBB->functions->RandomCode();

				$ActiveAdress = $Adress . 'index.php?page=active_member&index=1&id=' . $PowerBB->member->id . '&code=' . $Code;

				$ReqArr 			= 	array();
				$ReqArr['field'] 	= 	array();

				$ReqArr['field']['random_url'] 		= 	$Code;
				$ReqArr['field']['username'] 		= 	$PowerBB->_POST['username'];
				$ReqArr['field']['request_type'] 	= 	3;

				$InsertReq = $PowerBB->core->Insert($ReqArr,'requests');

				if ($InsertReq)
				{
					$MessageInfArr 			= 	array();
					$MessageInfArr['where'] 	= 	array('id','4');

					$Massege_Info = $PowerBB->message->GetMessageInfo($MessageInfArr);

					$MsgArr = array();
					$MsgArr['text'] 		= 	$Massege_Info['text'];
					$MsgArr['active_url'] 	= 	$ActiveAdress;
					$MsgArr['username']		=	$PowerBB->_POST['username'];
					$MsgArr['title']		=	$PowerBB->_CONF['info_row']['title'];

					$Massege_Info['text'] = $PowerBB->core->MessageProccess($MsgArr,'email_msg');


				       if ($PowerBB->_CONF['info_row']['mailer']=='phpmail')
						{
					      $Send = $PowerBB->functions->send_this_php($PowerBB->_POST['email'],$Massege_Info['title'],$Massege_Info['text'],$PowerBB->_CONF['info_row']['send_email']);
			            }
			           elseif ($PowerBB->_CONF['info_row']['mailer']=='smtp')
						{
							$to = $PowerBB->_POST['email'];
							$fromname = $PowerBB->_CONF['info_row']['title'];
							$message = $Massege_Info['text'];
							$subject = $Massege_Info['title'];
							$from = $PowerBB->_CONF['info_row']['send_email'];
                            $Send = $PowerBB->functions->send_this_smtp($to,$fromname,$message,$subject,$from);
						}

						$PowerBB->member->id   =     $PowerBB->functions->CleanVariable($PowerBB->member->id,'intval');
						$MemberArr             =     array();
						$MemberArr['where']     =     array('id',$PowerBB->member->id);
						$GetMemberInfo = $PowerBB->core->GetInfo($MemberArr,'member');
						$PowerBB->functions->ShowHeader();
						$PowerBB->template->assign('username',$GetMemberInfo['username']);
						$PowerBB->template->assign('email',$GetMemberInfo['email']);
						$PowerBB->template->display('register_mail_message');
						$PowerBB->functions->GetFooter();
				}
			}
			else
      		{
      			    $PowerBB->functions->header_redirect('index.php?page=login&register_login=1');
             }



      	}
	}

		function _AjaxTxtGreen($msg,$color='green')
		{
		  global $PowerBB;
		  echo '<style type="text/css">.Ajaxwarning1 { font-family:tahoma; font-size:10px; background-color: #008000; color: #fff; border: 1px solid #6E704B; padding: 2px; -moz-border-radius: 5px; overflow: auto;}</style><span class="Ajaxwarning1" align="center">'.$msg.'</span>';
			$jQsubmit='<script type="text/javascript">
			$(document).ready(function(){
			$("input[type=submit]").removeAttr("disabled");
			 })
			 </script>';
			echo $jQsubmit;
		  exit;
		}
		function _AjaxTxtRed($msg,$color='red')
		{
		  global $PowerBB;
		  echo '<style type="text/css">.Ajaxwarning2 { font-family:tahoma; font-size:10px; background-color: #BD2530; color: #fff; border: 1px solid #6E704B; padding: 2px; -moz-border-radius: 5px; overflow: auto;}</style><span class="Ajaxwarning2" align="center">'.$msg.'</span>';
			$jQsubmit='<script type="text/javascript">
			$(document).ready(function(){
			$("input[type=submit]").attr("disabled", "disabled");
			 })
			 </script>';
			echo $jQsubmit;
		  exit;
		}
		  /**
		   * Some checks then add the member to database
		  */
		function _CheckNameStart()
		{
		  global $PowerBB;
		// Ensure all necessary information are valid
		    if (strstr($PowerBB->_POST['username'],'"')
			or strstr($PowerBB->_POST['username'],"'")
			or strstr($PowerBB->_POST['username'],'>')
			or strstr($PowerBB->_POST['username'],'<')
			or strstr($PowerBB->_POST['username'],'*')
			or strstr($PowerBB->_POST['username'],'%')
			or strstr($PowerBB->_POST['username'],'$')
			or strstr($PowerBB->_POST['username'],'#')
			or strstr($PowerBB->_POST['username'],'+')
			or strstr($PowerBB->_POST['username'],'^')
			or strstr($PowerBB->_POST['username'],'&')
			or strstr($PowerBB->_POST['username'],',')
			or strstr($PowerBB->_POST['username'],'~')
			or strstr($PowerBB->_POST['username'],'@')
			or strstr($PowerBB->_POST['username'],'!')
			or strstr($PowerBB->_POST['username'],'{')
			or strstr($PowerBB->_POST['username'],'}')
			or strstr($PowerBB->_POST['username'],'(')
			or strstr($PowerBB->_POST['username'],')')
			or strstr($PowerBB->_POST['username'],'/'))
			{
			$this->_AjaxTxtRed($PowerBB->_CONF['template']['_CONF']['lang']['You_can_not_register_these_symbols']);
			}

			if (empty($PowerBB->_POST['username']))
			{
			 $this->_AjaxTxtRed($PowerBB->_CONF['template']['_CONF']['lang']['You_can_register_this_numbrs_name']);
			}
			  // Clean the username from white spaces
			if(is_numeric($PowerBB->_POST['username']))
			{
			$this->_AjaxTxtRed($PowerBB->_CONF['template']['_CONF']['lang']['You_can_register_this_numbrs_name']);
			}
			 $PowerBB->_POST['username']  =  $PowerBB->functions->CleanVariable($PowerBB->_POST['username'],'trim');
			 $PowerBB->_POST['username']  =  $PowerBB->Powerparse->censor_words($PowerBB->_POST['username']);

			if (function_exists('mb_strlen'))
			{
				$reg_less_num = mb_strlen($PowerBB->_POST['username'], 'UTF-8') >= $PowerBB->_CONF['info_row']['reg_less_num'];
				$reg_max_num = mb_strlen($PowerBB->_POST['username'], 'UTF-8') <= $PowerBB->_CONF['info_row']['reg_max_num'];
			}
			else
			{
				$reg_less_num = strlen(@utf8_decode($PowerBB->_POST['username'])) >= $PowerBB->_CONF['info_row']['reg_less_num'];
				$reg_max_num = strlen(@utf8_decode($PowerBB->_POST['username'])) <= $PowerBB->_CONF['info_row']['reg_max_num'];

			}

		   if($reg_less_num)
			{
			 // $this->_AjaxTxtRed($PowerBB->_CONF['template']['_CONF']['lang']['Character_name_a_few_user']);

			}
			else
			{
			$this->_AjaxTxtRed($PowerBB->_CONF['template']['_CONF']['lang']['Character_name_a_few_user']);
			}

		   if($reg_max_num)
			{
			 // $this->_AjaxTxtRed($PowerBB->_CONF['template']['_CONF']['lang']['characters_Username_many']);
			}
			else
			{      		$PowerBB->_CONF['template']['_CONF']['lang']['characters_Username_many'] = str_replace("{User_num}", $PowerBB->_CONF['info_row']['reg_max_num'], $PowerBB->_CONF['template']['_CONF']['lang']['characters_Username_many']);
			$this->_AjaxTxtRed($PowerBB->_CONF['template']['_CONF']['lang']['characters_Username_many']);
			}
			if ($PowerBB->banned->IsUsernameBanned(array('username' => $PowerBB->_POST['username'])))
			{
			$this->_AjaxTxtRed($PowerBB->_CONF['template']['_CONF']['lang']['You_can_not_register_this_name']);
			}
			if ($PowerBB->_POST['username'] == 'Guest')
			{
			$this->_AjaxTxtRed($PowerBB->_CONF['template']['_CONF']['lang']['can_not_register_Guest_name']);
			}


			// Ensure there is no person used the same username
			if ($PowerBB->member->IsMember(array('where' =>
			array('username',$PowerBB->_POST['username']))))
			{
			$this->_AjaxTxtRed($PowerBB->_CONF['template']['_CONF']['lang']['please_choose_another_name']);
			}
			if (!$PowerBB->member->IsMember(array('where' => array('username',$PowerBB->_POST['username']))))
			{
			$this->_AjaxTxtGreen($PowerBB->_CONF['template']['_CONF']['lang']['You_can_register_this_name'],'green');
			$this->_AjaxTxtGreen('?','green');
			}

		}

		function _CheckEmailStart()
		{
		  global $PowerBB;
		  // Clean the username from white spaces
		  $PowerBB->_POST['email']   =  $PowerBB->functions->CleanVariable($PowerBB->_POST['email'],'trim');
		  // Check if the email is valid, This line will prevent any false email
		  if(!$PowerBB->functions->CheckEmail($PowerBB->_POST['email']))
		  {
		   $this->_AjaxTxtRed($PowerBB->_CONF['template']['_CONF']['lang']['Please_enter_your_correct_email']);
		  }
		  // Ensure there is no person used the same email
		  if ($PowerBB->member->IsMember(array('where' =>array('email',$PowerBB->_POST['email']))))
		  {
		    $PowerBB->_CONF['template']['_CONF']['lang']['E-mail_is_registered_please_type_the_other'] = str_replace("{forget}","index.php?page=forget&index=1",$PowerBB->_CONF['template']['_CONF']['lang']['E-mail_is_registered_please_type_the_other']);
		   $this->_AjaxTxtRed($PowerBB->_CONF['template']['_CONF']['lang']['E-mail_is_registered_please_type_the_other']);
		  }
		  if ($PowerBB->banned->IsEmailBanned(array('email' => $PowerBB->_POST['email'])))
		  {
		   $this->_AjaxTxtRed($PowerBB->_CONF['template']['_CONF']['lang']['You_can_not_register_this_e-mail']);
		  }


		   $this->_AjaxTxtGreen($PowerBB->_CONF['template']['_CONF']['lang']['You_can_register_this_email'],'green');
		   $this->_AjaxTxtGreen('?','green');
		}


}

?>
