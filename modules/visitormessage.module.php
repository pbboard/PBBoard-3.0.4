<?php
session_start();
(!defined('IN_PowerBB')) ? die() : '';

define('CLASS_NAME','PowerBBCoreMOD');

include('common.php');
class PowerBBCoreMOD
{
	function run()
	{
		global $PowerBB;

		/** ADD Visitor Message **/

		if ($PowerBB->_CONF['info_row']['active_visitor_message'] == '1')
		{

			if ($PowerBB->_GET['del'])
			{
	          $this->_StartDelVisitorMessage();
			}
			elseif ($PowerBB->_GET['edit'])
			{
	          $this->_EditVisitorMessage();
			}
			elseif ($PowerBB->_GET['start_edit'])
			{
	          $this->_StartEditVisitorMessage();
			}
			elseif ($PowerBB->_GET['add_visitor_message'])
			{
			  $this->_StartAddVisitorMessage();
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



    function _StartDelVisitorMessage()
	{
		global $PowerBB;

		$PowerBB->functions->ShowHeader($PowerBB->_CONF['template']['_CONF']['lang']['deletion_process']);

		if (empty($PowerBB->_POST['check']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['You_do_not_select_any_visitormessage']);
		}


       $VisitorMessage_D = $PowerBB->_POST['check'];


       foreach ($VisitorMessage_D as $VisitorMessage)
       {

				// Delete Visitor Message from database
				$DelVisitorMessage 							= 	array();
				$DelVisitorMessage['name'] 	        		=  	'id';
		        $DelVisitorMessage['where'] 		    	= 	array('id',intval($VisitorMessage));

				$DeleteVisitorMessage = $PowerBB->core->Deleted($DelVisitorMessage,'visitormessage');

       }


                $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['del_visitormessage_successfully']);
				$PowerBB->functions->redirect('index.php?page=profile&show=1&id='.$PowerBB->_GET['id']);
                $PowerBB->functions->GetFooter();

	}

    function _EditVisitorMessage()
	{
		global $PowerBB;

		$PowerBB->functions->ShowHeader();
		$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');
		if (empty($PowerBB->_GET['id']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}
		$VisitorMessageArr 			= 	array();
		$VisitorMessageArr['where'] 	= 	array('id',$PowerBB->_GET['id']);

		$VisitorMessageinfo = $PowerBB->core->GetInfo($VisitorMessageArr,'visitormessage');
		if (!$VisitorMessageinfo)
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}
		$SmlArr 					= 	array();
		$SmlArr['order'] 			=	array();
		$SmlArr['order']['field']	=	'id';
		$SmlArr['order']['type']	=	'ASC';
		$SmlArr['limit']			=	$PowerBB->_CONF['info_row']['smiles_nm'];
		$SmlArr['proc'] 			= 	array();
		$SmlArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');

		$PowerBB->_CONF['template']['while']['SmileRows'] = $PowerBB->icon->GetSmileList($SmlArr);


		$MemberArr 			= 	array();
		$MemberArr['where'] 	= 	array('id',$VisitorMessageinfo['userid']);

		$MemberInfo = $PowerBB->core->GetInfo($MemberArr,'member');
        $PowerBB->template->assign('username',$MemberInfo['username']);
        $PowerBB->template->assign('visitormessageinfo',$VisitorMessageinfo);
        $VisitorMessageinfo['pagetext'] = str_ireplace('&amp;',"&",$VisitorMessageinfo['pagetext']);
        $VisitorMessageinfo['pagetext'] = str_replace('&quot;','',$VisitorMessageinfo['pagetext']);
		$VisitorMessageinfo['pagetext'] = $PowerBB->Powerparse->replace_htmlentities($VisitorMessageinfo['pagetext']);

		$PowerBB->template->assign('pagetext',$VisitorMessageinfo['pagetext']);

      $show1 = ($PowerBB->_CONF['member_row']['id'] == $VisitorMessageinfo['postuserid']);
      $show2 = ($PowerBB->_CONF['member_row']['id'] == $VisitorMessageinfo['userid']);
      if ($show2)
      {
			$PowerBB->template->display('visitormessage_edit');
			$PowerBB->functions->GetFooter();
      }
      elseif ($PowerBB->_CONF['group_info']['admincp_allow'])
      {
			$PowerBB->template->display('visitormessage_edit');
			$PowerBB->functions->GetFooter();
      }
      elseif ($show1)
      {
			$PowerBB->template->display('visitormessage_edit');
			$PowerBB->functions->GetFooter();
      }
      else
      {
	    $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Sorry_You_do_not_have_powers_to_access_this_page']);
      }


	}

    function _StartEditVisitorMessage()
	{
		global $PowerBB;

		$PowerBB->functions->ShowHeader($PowerBB->_CONF['template']['_CONF']['lang']['deletion_process']);

		$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');
		if (empty($PowerBB->_GET['id']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}
			if (!$PowerBB->_CONF['group_info']['visitormessage'])
			{
				$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Sorry_You_do_not_have_powers_to_access_this_page']);
			}
		$VisitorMessageArr 			= 	array();
		$VisitorMessageArr['where'] 	= 	array('id',$PowerBB->_GET['id']);

		$VisitorMessageinfo = $PowerBB->core->GetInfo($VisitorMessageArr,'visitormessage');
		if (!$VisitorMessageinfo)
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}


    		if (isset($PowerBB->_POST['text']{$PowerBB->_CONF['info_row']['visitor_message_chars']}))
    		{
                 $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['visitor_message_large_number_of_characters']);
             }


			$UpdateArr 				= 	array();
			$UpdateArr['field']		=	array();

			 $UpdateArr['field']['pagetext'] 				= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['text'],'nohtml');
		     $UpdateArr['where'] 						= 	array('id',$PowerBB->_GET['id']);

			$update = $PowerBB->core->Update($UpdateArr,'visitormessage');

            $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['EditVisitorMessage_successfully']);
            $PowerBB->functions->redirect('index.php?page=profile&show=1&id='.$PowerBB->_POST['userid']);
            $PowerBB->functions->GetFooter();

	}

	/**
	 * Start Add VisitorMessage
	 */
	function _StartAddVisitorMessage()
	{

		global $PowerBB;

		if (isset($PowerBB->_POST['member_id']))
		{
		 $PowerBB->_GET['id'] = $PowerBB->_POST['member_id'];
		}


			if (isset($PowerBB->_POST['pagetext']))
			{
			   $PowerBB->_POST['text'] = $PowerBB->_CONF['template']['_CONF']['lang']['rebly_visitor_message']." ".$PowerBB->_POST['pagetext']."\n".$PowerBB->_POST['comment'];
			}

		$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');

         if ($PowerBB->_SERVER['REQUEST_METHOD'] != 'POST')
         {
         	 header("Location: index.php?page=profile&show=1&id=".$PowerBB->_GET['id']);
		     exit;
         }
		if (empty($PowerBB->_GET['id']))
		{
			$PowerBB->functions->error_no_foot($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}
		$MemberArr 				= 	array();
		$MemberArr['where']		=	array('id',$PowerBB->_GET['id']);

		$member = $PowerBB->core->GetInfo($MemberArr,'member');

			if (!$PowerBB->_CONF['group_info']['visitormessage'])
			{
				$PowerBB->functions->error_no_foot($PowerBB->_CONF['template']['_CONF']['lang']['Sorry_You_do_not_have_powers_to_access_this_page']);
			}

			if (!$member['visitormessage'])
			{
				$PowerBB->functions->error_no_foot($PowerBB->_CONF['template']['_CONF']['lang']['Sorry_You_do_not_have_powers_to_access_this_page']);
			}

			if (empty($PowerBB->_POST['text']))
			{
				$PowerBB->functions->error_no_foot($PowerBB->_CONF['template']['_CONF']['lang']['Please_write_the_visitor_message']);
			}
                //IsFlood
				$writer_postusername = $PowerBB->_CONF['member_row']['username'];
                $last_visitormessage_write_time = $PowerBB->DB->sql_query("SELECT  *   FROM " . $PowerBB->table['visitormessage'] . " WHERE postusername= '$writer_postusername' ORDER BY id desc");
                $last_visitormessage_time = $PowerBB->DB->sql_fetch_array($last_visitormessage_write_time);
	            if ((time() - $PowerBB->_CONF['info_row']['floodctrl']) <= $last_visitormessage_time['dateline'])
	            {
				$PowerBB->functions->error_no_foot($PowerBB->_CONF['template']['_CONF']['lang']['floodctrl']);

				}

    		if (!$PowerBB->_CONF['member_permission'])
	         {
			        if($PowerBB->_CONF['info_row']['captcha_type'] == 'captcha_Q_A')
					 {
		                if($PowerBB->_POST['code_confirm'] != $PowerBB->_POST['code_answer'])
						 {
				            $PowerBB->functions->error_no_foot($PowerBB->_CONF['template']['_CONF']['lang']['random_answer_not_correct']);
					     }
				     }
			        if($PowerBB->_CONF['info_row']['captcha_type'] == 'captcha_IMG')
					 {
				        if(md5($PowerBB->_POST['code_confirm']) != $_SESSION['captcha_key'])
						 {
				            $PowerBB->functions->error_no_foot($PowerBB->_CONF['template']['_CONF']['lang']['Code_that_you_enter_the_wrong']);
					     }
				    }
              }

    		if (isset($PowerBB->_POST['text']{$PowerBB->_CONF['info_row']['visitor_message_chars']}))
    		{
                 $PowerBB->functions->error_no_foot($PowerBB->_CONF['template']['_CONF']['lang']['visitor_message_large_number_of_characters']);
             }



            if ($PowerBB->_GET['id'] == $PowerBB->_CONF['member_row']['id'])
    		{
               $messageread 	    = 	'0';
             }
			else
			{
               $messageread 	    = 	'1';
			}

			$VisitorMessageArr 			= 	array();
			$VisitorMessageArr['field']	=	array();

			$VisitorMessageArr['field']['pagetext'] 		= 	$PowerBB->_POST['text'];
			$VisitorMessageArr['field']['postusername'] 	= 	$PowerBB->_CONF['member_row']['username'];
			$VisitorMessageArr['field']['postuserid']    	= 	$PowerBB->_CONF['member_row']['id'];
			$VisitorMessageArr['field']['dateline'] 	    = 	$PowerBB->_CONF['now'];
			$VisitorMessageArr['field']['userid'] 	        = 	$PowerBB->_GET['id'];
			$VisitorMessageArr['field']['ipaddress'] 	    = 	$PowerBB->_CONF['ip'];
            $VisitorMessageArr['field']['messageread'] 	    = 	$messageread;


			$insert = $PowerBB->core->Insert($VisitorMessageArr,'visitormessage');

			if ($insert)
			{
			echo ('<SCRIPT LANGUAGE="JavaScript">window.location="index.php?page=profile&show=1&id='.$PowerBB->_GET['id'].'#'.$PowerBB->DB->sql_insert_id().'";</script>');

			}

	}

}

?>
