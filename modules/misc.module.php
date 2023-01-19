<?php
session_start();
(!defined('IN_PowerBB')) ? die() : '';
$CALL_SYSTEM					=	array();
$CALL_SYSTEM['REPUTATION']         = 	true;
$CALL_SYSTEM['RATING']             = 	true;
$CALL_SYSTEM['MESSAGE']            = 	true;
$CALL_SYSTEM['EMAILED']            = 	true;


define('JAVASCRIPT_PowerCode',true);

define('CLASS_NAME','PowerBBMiscMOD');

include('common.php');
class PowerBBMiscMOD
{
	function run()
	{
		global $PowerBB;

   		/** Show Rules form **/
		if ($PowerBB->_GET['rules'])
		{

		$this->_GetRules();

		}
		// Go to the page
		elseif ($PowerBB->_GET['pagenav'])
		{
			$this->_GoPagenav();
		}
		elseif ($PowerBB->_GET['pagenav_forum'])
		{
			$this->_GoPagenav_forum();
		}
       elseif ($PowerBB->_GET['pagenav_memberlist'])
       {
          $this->_GoPagenav_memberlist();
       }
       elseif ($PowerBB->_GET['pagenav_pm'])
       {
          $this->_GoPagenav_pm();
       }
       elseif ($PowerBB->_GET['pagenav_search'])
       {
          $this->_GoPagenav_search();
       }
		// rating subject
		elseif ($PowerBB->_GET['rating'])
		{
			$this->_RatingSubject();
		}
		// Who posted
		elseif ($PowerBB->_GET['whoposted'])
		{
			$this->_Whoposted();
		}
		// members reputation
		elseif ($PowerBB->_GET['subject_reputation'])
		{
			$this->_SubjectSendReputation();
		}
		elseif ($PowerBB->_GET['reply_reputation'])
		{
			$this->_ReplySendReputation();
		}
		// send subject to friend
		elseif ($PowerBB->_GET['sendtofriend'])
		{
			$this->_GoToPageSendToFriend();
		}
		elseif ($PowerBB->_GET['startsendtofriend'])
		{
			$this->_StartSendToFriend();
		}
		// add subscription
		elseif ($PowerBB->_GET['addsubscription'])
		{
			$this->_StartAddSubscription();
		}
		elseif ($PowerBB->_GET['frame_form'])
		{
			$this->_StartAddFrame();
		}
		elseif ($PowerBB->_GET['gradient_form'])
		{
			$this->_StartAddGradient();
		}
		elseif ($PowerBB->_GET['poem_form'])
		{
			$this->_StartAddPoem();
		}
		elseif ($PowerBB->_GET['poem_template'])
		{
			$this->_StartAddPoemTemplat();
		}
       elseif ($PowerBB->_GET['pagenav_general'])
       {
          $this->_GoPagenavGeneral();
       }
		else
		{
			header("Location: index.php");
			exit;
		}

	}


	/**
	 * Print Rules
	 */
	function _GetRules()
	{
		global $PowerBB;
       $PowerBB->template->assign('rules_page','primary_tabon');

         $PowerBB->functions->ShowHeader();

         $PowerBB->_CONF['info_row']['rules'] = $PowerBB->Powerparse->replace($PowerBB->_CONF['info_row']['rules']);
         $PowerBB->Powerparse->replace_smiles($PowerBB->_CONF['info_row']['rules']);
            $Adress = $PowerBB->functions->GetForumAdress();
		$PowerBB->_CONF['info_row']['rules']  = str_ireplace("../",$Adress, $PowerBB->_CONF['info_row']['rules']);
         $PowerBB->template->assign('rules',$PowerBB->_CONF['info_row']['rules']);

		$PowerBB->template->display('rules_board_main');
		$PowerBB->functions->GetFooter();

	}

	function _GoPagenav()
	{
		global $PowerBB;
		  if (!is_numeric($PowerBB->_POST['count'])) {
            $PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['the_page_number_must_contain_only_numbers']);
           }
        $PowerBB->_POST['count']    = $PowerBB->functions->CleanVariable($PowerBB->_POST['count'],'intval');
  		$PowerBB->_POST['count'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['count'],'html');
  		$PowerBB->_POST['count'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['count'],'sql');

		if (empty($PowerBB->_POST['count']))
		{
         $PowerBB->functions->ShowHeader();
         $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_write_the_page_number']);
		}
               $PowerBB->_POST['subject_id'] = $PowerBB->functions->CleanVariable($PowerBB->_POST['subject_id'],'intval');
               $PowerBB->_POST['subject_id'] = $PowerBB->functions->CleanVariable($PowerBB->_POST['subject_id'],'html');
               $PowerBB->_POST['subject_id'] = $PowerBB->functions->CleanVariable($PowerBB->_POST['subject_id'],'sql');

		$PowerBB->functions->ShowHeader();

       $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['go_page']  . ' ('. $PowerBB->_POST['count'] .') '. $PowerBB->_CONF['template']['_CONF']['lang']['Please_wait']);
	 $PowerBB->functions->redirect($PowerBB->functions->rewriterule("index.php?page=topic&amp;show=1&amp;id=".$PowerBB->_POST['subject_id']."&amp;count=".$PowerBB->_POST['count']));

     $PowerBB->functions->GetFooter();

	}

	function _GoPagenav_forum()
	{
		global $PowerBB;
		  if (!is_numeric($PowerBB->_POST['count'])) {
            $PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['the_page_number_must_contain_only_numbers']);
           }
        $PowerBB->_POST['count'] = $PowerBB->functions->CleanVariable($PowerBB->_POST['count'],'intval');
  		$PowerBB->_POST['count'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['count'],'html');
  		$PowerBB->_POST['count'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['count'],'sql');

  		if (empty($PowerBB->_POST['count']))
		{
         $PowerBB->functions->ShowHeader();
         $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_write_the_page_number']);

		}
               $PowerBB->_POST['subject_id'] = $PowerBB->functions->CleanVariable($PowerBB->_POST['subject_id'],'intval');
               $PowerBB->_POST['subject_id'] = $PowerBB->functions->CleanVariable($PowerBB->_POST['subject_id'],'html');
               $PowerBB->_POST['subject_id'] = $PowerBB->functions->CleanVariable($PowerBB->_POST['subject_id'],'sql');

		$PowerBB->functions->ShowHeader();

       $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['go_page'] . ' ('. $PowerBB->_POST['count'] .') '. $PowerBB->_CONF['template']['_CONF']['lang']['Please_wait']);
	 $PowerBB->functions->redirect($PowerBB->functions->rewriterule("index.php?page=forum&amp;show=1&amp;id=".$PowerBB->_POST['section_id']."&amp;count=".$PowerBB->_POST['count']));
     $PowerBB->functions->GetFooter();

	}
    function _GoPagenav_pm()
    {
       global $PowerBB;
		  if (!is_numeric($PowerBB->_POST['count'])) {
            $PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['the_page_number_must_contain_only_numbers']);
           }
        $PowerBB->_POST['count'] = $PowerBB->functions->CleanVariable($PowerBB->_POST['count'],'intval');
  		$PowerBB->_POST['count'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['count'],'html');
  		$PowerBB->_POST['count'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['count'],'sql');

       if (empty($PowerBB->_POST['count']))
       {
         $PowerBB->functions->ShowHeader();
         $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_write_the_page_number']);

       }
               $PowerBB->_POST['subject_id'] = $PowerBB->functions->CleanVariable($PowerBB->_POST['subject_id'],'intval');
               $PowerBB->_POST['subject_id'] = $PowerBB->functions->CleanVariable($PowerBB->_POST['subject_id'],'html');
               $PowerBB->_POST['subject_id'] = $PowerBB->functions->CleanVariable($PowerBB->_POST['subject_id'],'sql');

       $PowerBB->functions->ShowHeader();


      $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['go_page'] . ' ('. $PowerBB->_POST['count'] .') '. $PowerBB->_CONF['template']['_CONF']['lang']['Please_wait']);
    $PowerBB->functions->redirect('index.php?page=pm_list&list=1&folder='.$PowerBB->_POST['folder'].'&count=' . $PowerBB->_POST['count']);
    $PowerBB->functions->GetFooter();

    }

   function _GoPagenav_search()
    {
       global $PowerBB;

         if ($PowerBB->_SERVER['REQUEST_METHOD'] != 'POST')
         {
         	 header("Location: index.php");
		     exit;
         }
		  if (!is_numeric($PowerBB->_POST['count'])) {
            $PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['the_page_number_must_contain_only_numbers']);
           }
       if (empty($PowerBB->_POST['count']))
       {
         $PowerBB->functions->ShowHeader();
         $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_write_the_page_number']);

       }
        $PowerBB->_POST['count'] = $PowerBB->functions->CleanVariable($PowerBB->_POST['count'],'intval');
  		$PowerBB->_POST['count'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['count'],'html');
  		$PowerBB->_POST['count'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['count'],'sql');

       $PowerBB->functions->ShowHeader();

      $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['go_page'] . ' ('. $PowerBB->_POST['count'] .') '. $PowerBB->_CONF['template']['_CONF']['lang']['Please_wait']);
    $PowerBB->functions->redirect($PowerBB->_POST['location'].'&count=' . $PowerBB->_POST['count']);
    $PowerBB->functions->GetFooter();

    }

  function _GoPagenav_memberlist()
    {
       global $PowerBB;
		  if (!is_numeric($PowerBB->_POST['count'])) {
            $PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['the_page_number_must_contain_only_numbers']);
           }
       if (empty($PowerBB->_POST['count']))
       {
         $PowerBB->functions->ShowHeader();
         $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_write_the_page_number']);

       }
        $PowerBB->_POST['count'] = $PowerBB->functions->CleanVariable($PowerBB->_POST['count'],'intval');
  		$PowerBB->_POST['count'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['count'],'html');
  		$PowerBB->_POST['count'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['count'],'sql');

       $PowerBB->functions->ShowHeader();


      $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['go_page'] . ' ('. $PowerBB->_POST['count'] .') '. $PowerBB->_CONF['template']['_CONF']['lang']['Please_wait']);
    $PowerBB->functions->redirect($PowerBB->functions->rewriterule("index.php?page=member_list&index=1&show=1&count=".$PowerBB->_POST['count']));
    $PowerBB->functions->GetFooter();

    }

  function _GoPagenavGeneral()
    {
       global $PowerBB;
		  if (!is_numeric($PowerBB->_POST['count'])) {
            $PowerBB->functions->ShowHeader();
            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['the_page_number_must_contain_only_numbers']);
           }
       if (empty($PowerBB->_POST['count']))
       {
         $PowerBB->functions->ShowHeader();
         $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_write_the_page_number']);

       }
        $PowerBB->_POST['count'] = $PowerBB->functions->CleanVariable($PowerBB->_POST['count'],'intval');
  		$PowerBB->_POST['count'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['count'],'html');
  		$PowerBB->_POST['count'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['count'],'sql');

       $PowerBB->functions->ShowHeader();


      $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['go_page'] . ' ('. $PowerBB->_POST['count'] .') '. $PowerBB->_CONF['template']['_CONF']['lang']['Please_wait']);
      $iscount = "&count=";
     if(strstr($PowerBB->_SERVER['HTTP_REFERER'],'whats_new'))
      {
      $PowerBB->functions->redirect("whats_new-".$PowerBB->_POST['count']);
      $PowerBB->functions->GetFooter();
      exit();
      }
      elseif(strstr($PowerBB->_SERVER['HTTP_REFERER'],'today_topics'))
      {
      $PowerBB->functions->redirect("today_topics-".$PowerBB->_POST['count']);
      $PowerBB->functions->GetFooter();
      exit();
      }
      elseif(strstr($PowerBB->_SERVER['HTTP_REFERER'],'count='))
      {      $PowerBB->_SERVER['HTTP_REFERER'] = str_replace($iscount.$PowerBB->_GET['count'],"",$PowerBB->_SERVER['HTTP_REFERER']);
      }
     $PowerBB->functions->redirect($PowerBB->functions->rewriterule($PowerBB->_SERVER['HTTP_REFERER'].$iscount.$PowerBB->_POST['count']));

    $PowerBB->functions->GetFooter();

    }
	function _SubjectSendReputation()
	{
		global $PowerBB;

		if (!$PowerBB->_CONF['member_permission'])
		{
		 $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['no_vistor_Reputation']);
		}

		$PowerBB->_POST['subject_id'] = $PowerBB->functions->CleanVariable($PowerBB->_POST['subject_id'],'intval');
		$PowerBB->_POST['subject_id']   = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['subject_id'],'sql');
		$PowerBB->_POST['subject_id']   = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['subject_id'],'html');

  		$PowerBB->_POST['reputationusername'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['reputationusername'],'html');
  		$PowerBB->_POST['reputationusername'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['reputationusername'],'sql');
  		$PowerBB->_POST['reputationcomment'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['reputationcomment'],'html');
  		$PowerBB->_POST['reputationcomment'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['reputationcomment'],'sql');
  		$PowerBB->_POST['username'] 	        = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['username'],'sql');
  		$PowerBB->_POST['username'] 	        = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['username'],'html');
  		$PowerBB->_POST['subject_title'] 	    = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['subject_title'],'html');
  		$PowerBB->_POST['subject_title'] 	    = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['subject_title'],'sql');


    	 if ($PowerBB->_POST['username'] == $PowerBB->_POST['reputationusername'])
		 {
           $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['you_cant_Reputation_self']);
         }


         /** Get the Reputation information **/
		$RepArr 			= 	array();
		$RepArr['where'] 	= 	array('subject_id',$PowerBB->_POST['subject_id']);

		$this->ReputationInfo = $PowerBB->reputation->GetReputationInfo($RepArr);

        if ($this->ReputationInfo['by_username'] == $PowerBB->_POST['reputationusername'])
        {
        $PowerBB->functions->reputation_alert($PowerBB->_CONF['template']['_CONF']['lang']['you_cant_Reputation_towez']);
        }

	  $reputation = $PowerBB->_POST['username'];
	  $reputation_number = $PowerBB->_CONF['group_info']['reputation_number'];
	  $update = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['member'] . " SET reputation = reputation + '$reputation_number' WHERE username ='$reputation'");

	  	if ($update)
		{
		    $time=time();

        	$ReputationArr 			= 	array();
			$ReputationArr['field']	=	array();

			$ReputationArr['field']['by_username'] 	        = 	$PowerBB->_POST['reputationusername'];
			$ReputationArr['field']['username'] 	        = 	$PowerBB->_POST['username'];
			$ReputationArr['field']['subject_title'] 	    = 	$PowerBB->_POST['subject_title'];
			$ReputationArr['field']['subject_id'] 	        = 	$PowerBB->_POST['subject_id'];
			$ReputationArr['field']['comments'] 	        = 	$PowerBB->_POST['reputationcomment'];
			$ReputationArr['field']['reputationdate'] 	    = 	$PowerBB->_CONF['now'];
			$ReputationArr['field']['reputationread'] 	    = 	'1';
			$ReputationArr['get_id']					    =	true;

			$insert = $PowerBB->reputation->InsertReputation($ReputationArr);
	        if ($insert)
	        {
	          $PowerBB->functions->reputation_alert($PowerBB->_CONF['template']['_CONF']['lang']['Reputation_successfully']);
	        }
		}


	}

	function _ReplySendReputation()
	{
		global $PowerBB;

        if (!$PowerBB->_CONF['member_permission'])
		{
		$PowerBB->functions->reputation_alert($PowerBB->_CONF['template']['_CONF']['lang']['no_vistor_Reputation']);
		}

		$PowerBB->_POST['subject_id'] = $PowerBB->functions->CleanVariable($PowerBB->_POST['subject_id'],'intval');
		$PowerBB->_POST['subject_id']   = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['subject_id'],'sql');
		$PowerBB->_POST['subject_id']   = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['subject_id'],'html');

        $PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');
        $PowerBB->_GET['count'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['count'],'intval');

  		$PowerBB->_POST['reputationusername'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['reputationusername'],'html');
  		$PowerBB->_POST['reputationusername'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['reputationusername'],'sql');
  		$PowerBB->_POST['reputationcomment'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['reputationcomment'],'html');
  		$PowerBB->_POST['reputationcomment'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['reputationcomment'],'sql');
  		$PowerBB->_POST['username'] 	        = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['username'],'sql');
  		$PowerBB->_POST['username'] 	        = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['username'],'html');
  		$PowerBB->_POST['subject_title'] 	    = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['subject_title'],'html');
  		$PowerBB->_POST['subject_title'] 	    = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['subject_title'],'sql');

    	 if ($PowerBB->_POST['username'] == $PowerBB->_POST['reputationusername'])
		 {
           $PowerBB->functions->reputation_alert($PowerBB->_CONF['template']['_CONF']['lang']['you_cant_Reputation_self']);
         }
         /** Get the Reputation information **/
		$RepArr 			= 	array();
		$RepArr['where'] 	= 	array('reply_id',$PowerBB->_POST['id']);

		$this->ReputationInfo = $PowerBB->reputation->GetReputationInfo($RepArr);

         if ($this->ReputationInfo['by_username'] == $PowerBB->_POST['reputationusername'])
		 {
          $PowerBB->functions->reputation_alert($PowerBB->_CONF['template']['_CONF']['lang']['you_cant_Reputation_towez_to_Reply']);
         }

	  $reputation = $PowerBB->_POST['username'];
	  $reputation_number = $PowerBB->_CONF['group_info']['reputation_number'];
	  $update = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['member'] . " SET reputation = reputation + '$reputation_number' WHERE username ='$reputation'");

	  	if ($update)
		{


        	$ReputationArr 			= 	array();
			$ReputationArr['field']	=	array();

			$ReputationArr['field']['by_username'] 	        = 	$PowerBB->_POST['reputationusername'];
			$ReputationArr['field']['username'] 	        = 	$PowerBB->_POST['username'];
			$ReputationArr['field']['subject_title'] 	    = 	$PowerBB->_POST['subject_title'];
			$ReputationArr['field']['reply_id'] 	        = 	$PowerBB->_POST['id'];
			$ReputationArr['field']['subject_id'] 	        = 	$PowerBB->_POST['subject_id'];
			$ReputationArr['field']['comments'] 	        = 	$PowerBB->_POST['reputationcomment'];
			$ReputationArr['field']['reputationdate'] 	    = 	$PowerBB->_CONF['now'];
			$ReputationArr['field']['peg_count'] 	        = 	$PowerBB->_GET['count'];
            $ReputationArr['field']['reputationread'] 	    = 	'1';
			$ReputationArr['get_id']					    =	true;

			$insert = $PowerBB->reputation->InsertReputation($ReputationArr);

		 if ($insert)
		 {
           $PowerBB->functions->reputation_alert($PowerBB->_CONF['template']['_CONF']['lang']['Reputation_successfully']);
         }

		}

	}

	/**
	 * rating subject
	 */
	function _RatingSubject()
	{
		global $PowerBB;

		if (!$PowerBB->_CONF['member_permission'])
		{
		 $PowerBB->functions->reputation_alert($PowerBB->_CONF['template']['_CONF']['lang']['no_vistor_Reputation']);
		}

		$PowerBB->_POST['subject_id'] = $PowerBB->functions->CleanVariable($PowerBB->_POST['subject_id'],'intval');
		$PowerBB->_POST['subject_id']   = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['subject_id'],'sql');
		$PowerBB->_POST['subject_id']   = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['subject_id'],'html');

		$PowerBB->_POST['vote']   = $PowerBB->functions->CleanVariable($PowerBB->_POST['vote'],'intval');
		$PowerBB->_POST['vote']   = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['vote'],'sql');
		$PowerBB->_POST['vote']   = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['vote'],'html');

  		$PowerBB->_POST['subject_title']   = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['subject_title'],'sql');
		$PowerBB->_POST['username']   = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['username'],'sql');
		$PowerBB->_POST['by_username']   = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['by_username'],'sql');

  		$PowerBB->_POST['subject_title']   = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['subject_title'],'html');
		$PowerBB->_POST['username']   = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['username'],'html');
		$PowerBB->_POST['by_username']   = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['by_username'],'html');

		// If time out For Editing Disable View Icon Edite
		$SubjectArr = array();
		$SubjectArr['where'] = array('id',$PowerBB->_POST['subject_id']);

		$SubjectInfo = $PowerBB->core->GetInfo($SubjectArr,'subject');

    	 if ($PowerBB->_POST['by_username'] == $SubjectInfo['writer'])
		 {
           $PowerBB->functions->reputation_alert($PowerBB->_CONF['template']['_CONF']['lang']['you_cant_Rating_self']);
         }

         /** Get the Rating information **/
		$RepArr 			= 	array();
		$RepArr['where'] 	= 	array('subject_id',$PowerBB->_POST['subject_id']);

		$this->RatingInfo = $PowerBB->rating->GetRatingInfo($RepArr);

         if ($this->RatingInfo['by_username'] == $PowerBB->_POST['by_username'])
		 {
            $PowerBB->functions->reputation_alert($PowerBB->_CONF['template']['_CONF']['lang']['you_cant_Rating_towez']);
         }

	  $rating = $PowerBB->_POST['subject_id'];
	  $rating_number = $PowerBB->_POST['vote'];
	  $update = $PowerBB->DB->sql_query("UPDATE " . $PowerBB->table['subject'] . " SET rating = rating + '$rating_number' WHERE id ='$rating'");

	  	if ($update)
		{

        	$RatingArr 			= 	array();
			$RatingArr['field']	=	array();

			$RatingArr['field']['username'] 	        = 	$PowerBB->_POST['username'];
			$RatingArr['field']['by_username'] 	        = 	$PowerBB->_POST['by_username'];
			$RatingArr['field']['subject_title'] 	    = 	$PowerBB->_POST['subject_title'];
			$RatingArr['field']['subject_id'] 	        = 	$PowerBB->_POST['subject_id'];
			$RatingArr['field']['ratingdate'] 	        = 	$PowerBB->_CONF['now'];
			$RatingArr['get_id']					    =	true;

			$insert = $PowerBB->rating->InsertRating($RatingArr);
		 if ($insert)
		 {
           $PowerBB->functions->reputation_alert($PowerBB->_CONF['template']['_CONF']['lang']['Rating_successfully']);
         }
		}
	}


 	/**
	 * Who posted
	 */
	function _Whoposted()
	{
		global $PowerBB;


		 // Who posted
        $PowerBB->_GET['subject_id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['subject_id'],'intval');
        $Posted_number = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['reply'] . " WHERE subject_id='" . $PowerBB->_GET['subject_id'] . "' and delete_topic <>1"));
        $PowerBB->template->assign('Posted_number',$Posted_number);

         $PowerBB->template->display('who_posted1');

		 $subject_id = $PowerBB->_GET['subject_id'];
		 $ReplyArr = $PowerBB->DB->sql_query("SELECT Distinct writer FROM " . $PowerBB->table['reply'] . " WHERE subject_id = '$subject_id'");

		 	while($r = $PowerBB->DB->sql_fetch_array($ReplyArr))
 			{

 				$PowerBB->template->assign('WhoPosted',$r);

 				  $Posted_writer_number = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['reply'] . " WHERE subject_id='" . $PowerBB->_GET['subject_id'] . "' and writer='" . $r['writer'] . "' and delete_topic <>1"));
                  $PowerBB->template->assign('Posted_writer_number',$Posted_writer_number);
                  $PowerBB->template->assign('subject_id',$subject_id);
                  $PowerBB->template->assign('reply_id',$r['id']);

 				$PowerBB->template->display('who_posted2');

 			}

 			$PowerBB->template->display('who_posted3');
	}

	/**
	 * send subject to friend
	 */
	function _GoToPageSendToFriend()
	{
		global $PowerBB;
         $PowerBB->functions->ShowHeader();
      	$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');
		if (empty($PowerBB->_GET['id']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}
		$SubjectArr = array();
		$SubjectArr['where'] = array('id',$PowerBB->_GET['id']);

		$this->Subject = $PowerBB->core->GetInfo($SubjectArr,'subject');

		$PowerBB->template->assign('SubjectInfo',$this->Subject);
		if ($PowerBB->_CONF['member_permission'])
		{
		$PowerBB->template->assign('Sender',$PowerBB->_CONF['member_row']['username']);
		}
		else
		{
		$PowerBB->template->assign('Sender',$PowerBB->_CONF['template']['_CONF']['lang']['Guest']);
		}
		$PowerBB->template->assign('Adress',$Adress = $PowerBB->functions->GetForumAdress());
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
		$PowerBB->template->display('send_subject_to_friend');
		$PowerBB->functions->GetFooter();

	}

	function _StartSendToFriend()
	{
		global $PowerBB;

		$PowerBB->functions->ShowHeader();
  		$PowerBB->_POST['text']   = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['text'],'sql');
		$PowerBB->_POST['username']   = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['username'],'sql');
		$PowerBB->_POST['sendername']   = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['sendername'],'sql');
        $PowerBB->_POST['title']   = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['title'],'sql');


     	//////////

		if (empty($PowerBB->_POST['text']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['You_do_not_type_the_letter']);
		}
		if (!$PowerBB->_CONF['member_permission'])
		{
			if (empty($PowerBB->_POST['username']))
			{
				$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['You_do_not_type_your_name']);
			}
		}

		if (empty($PowerBB->_POST['sendername']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['noSendername']);
		}
		if (empty($PowerBB->_POST['Sendermail']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['noSendermail']);
		}
		if (empty($PowerBB->_POST['title']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['notitlesendsubjecttofriend']);
		}
		// Check if the email is valid, This line will prevent any false email
		if (!$PowerBB->functions->CheckEmail($PowerBB->_POST['Sendermail']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_enter_a_valid_e-mail']);
		}

   		if (!$PowerBB->_CONF['member_permission'])
         {
		        if($PowerBB->_CONF['info_row']['captcha_type'] == 'captcha_Q_A')
				 {
	                if($PowerBB->_POST['code'] != $PowerBB->_POST['code_answer'])
					 {
			            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['random_answer_not_correct']);
				     }
			     }
		        if($PowerBB->_CONF['info_row']['captcha_type'] == 'captcha_IMG')
				 {
			        if(md5($PowerBB->_POST['code']) != $_SESSION['captcha_key'])
					 {
			            $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Code_that_you_enter_the_wrong']);
				     }
			    }
         }

            $PowerBB->_POST['text'] = $PowerBB->Powerparse->replace($PowerBB->_POST['text']);
          	$PowerBB->_POST['text'] = str_ireplace('{39}',"'",$PowerBB->_POST['text']);
	        $PowerBB->_POST['text'] = str_ireplace('cookie','**',$PowerBB->_POST['text']);
	        $censorwords = preg_split('#[ \r\n\t]+#', $PowerBB->_CONF['info_row']['censorwords'], -1, PREG_SPLIT_NO_EMPTY);
	        $PowerBB->_POST['text'] = str_ireplace($censorwords,'**', $PowerBB->_POST['text']);

		if ($PowerBB->_CONF['member_permission'])
		{
		$username = $PowerBB->_CONF['member_row']['username'];
		}
		else
		{
		$username = $PowerBB->_POST['username'];
		}

		if ($PowerBB->_CONF['member_permission'])
		{
		$email = $PowerBB->_CONF['member_row']['email'];
		}
		else
		{
		$email = $PowerBB->_CONF['info_row']['title'];
		}

       $Adress_end	= 	'<a href="'.$PowerBB->functions->GetForumAdress().'index.php'.'">'.$PowerBB->_CONF['info_row']['title'].'</a>';

		$Form_name = '<br />'.$PowerBB->_CONF['template']['_CONF']['lang']['welc_Sender'].''.$PowerBB->_POST['sendername'].' '.$PowerBB->_CONF['template']['_CONF']['lang']['thes_maseege_from_subjecttofriend'].$username.'<br />';
		$Form_Massege ='<br>
	---------------------------------------------------<br>
	'.$PowerBB->_CONF['template']['_CONF']['lang']['Warning_send2'].'<br>
	---------------------------------------------------<br>
'.$PowerBB->_CONF['template']['_CONF']['lang']['Team'].' ' . $Adress_end .'.<br>
	&nbsp;</p>';

			if ($PowerBB->_CONF['info_row']['mailer']=='phpmail')
			{
	         $send = $PowerBB->functions->send_this_php($PowerBB->_POST['Sendermail'],$PowerBB->_POST['title'],$Form_name.$PowerBB->_POST['text'].$Form_Massege,$email);
			}
			elseif ($PowerBB->_CONF['info_row']['mailer']=='smtp')
			{
			$to = $PowerBB->_POST['Sendermail'];
			$fromname = $PowerBB->_CONF['info_row']['title'];
			$message = $Form_name.$PowerBB->_POST['text'].$Form_Massege;
			$subject = $PowerBB->_POST['title'];
			$from = $email;
            $Send = $PowerBB->functions->send_this_smtp($to,$fromname,$message,$subject,$from);
			}
	$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['your_message_has_been_sent_successfully']);
	$PowerBB->functions->redirect('index.php?page=topic&amp;show=1&amp;id='.$PowerBB->_POST['subject_id']);
	$PowerBB->functions->GetFooter();

	}

	function _StartAddSubscription()
	{
		global $PowerBB;

		$PowerBB->functions->ShowHeader();

      	$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');
		if (empty($PowerBB->_GET['id']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}


		if ($PowerBB->_CONF['info_row']['allowed_emailed'] == '1')
		{

		$SubjectArr = array();
		$SubjectArr['where'] = array('id',$PowerBB->_GET['id']);

		$this->Subject = $PowerBB->core->GetInfo($SubjectArr,'subject');

		$SectionInfoid = $this->Subject['section'];
		$SubjectInfoid = $PowerBB->_GET['id'];
		$member_row_id = $PowerBB->_CONF['member_row']['id'];

		$subject_user_emailed_nm = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['emailed'] . " WHERE subject_id='$SubjectInfoid' and user_id ='$member_row_id'"));



		$EmailedArr 			= 	array();
		$EmailedArr['where'] 	= 	array('subject_id',$PowerBB->_GET['id']);

		$this->EmailedInfo = $PowerBB->emailed->GetEmailedInfo($EmailedArr);


			if ($subject_user_emailed_nm < 1)
			{
			$EmailedArr 								= 	array();
			$EmailedArr['get_id']						=	true;
			$EmailedArr['field']						=	array();
			$EmailedArr['field']['user_id'] 			= 	$PowerBB->_CONF['member_row']['id'];
			$EmailedArr['field']['subject_id'] 			= 	$PowerBB->_GET['id'];
			$EmailedArr['field']['subject_title'] 		= 	$this->Subject['title'];

			$Insert = $PowerBB->emailed->InsertEmailed($EmailedArr);
			}

		///////////
	$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['addsubscription_successfully']);
	$PowerBB->functions->redirect('index.php?page=topic&amp;show=1&amp;id='.$PowerBB->_GET['id']);
      $PowerBB->functions->GetFooter();
		}

    }

 	/**
	 * Open frame peg
	 */
	function _StartAddFrame()
	{
		global $PowerBB;
	$PowerBB->template->display('frame_form');
	}
 	/**
	 * Open Gradient peg
	 */
	function _StartAddGradient()
	{
		global $PowerBB;
	$PowerBB->template->display('gradient_form');
	}

 	/**
	 * Open Poem peg
	 */
	function _StartAddPoem()
	{
		global $PowerBB;
	$PowerBB->template->display('poem_form');
	}
 	/**
	 * Open PoemTemplat peg
	 */
	function _StartAddPoemTemplat()
	{
		global $PowerBB;
	$PowerBB->template->display('poem_template');
	}



}

?>
