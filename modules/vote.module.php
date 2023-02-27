<?php
$CALL_SYSTEM = array();
$CALL_SYSTEM['SUBJECT']      =  true;
$CALL_SYSTEM['SECTION'] 	 = 	true;
$CALL_SYSTEM['POLL']         = 	true;
$CALL_SYSTEM['VOTE']         = 	true;

(!defined('IN_PowerBB')) ? die() : '';

define('CLASS_NAME','PowerBBCoreMOD');

include('common.php');
class PowerBBCoreMOD
{
	function run()
	{
		global $PowerBB;

		// Show header with page title

		if ($PowerBB->_GET['start'])
		{
			$this->_Start();
		}
		elseif ($PowerBB->_GET['poll_edit'])
		{
			$this->_PollEdit();
		}
		elseif ($PowerBB->_GET['poll_start_edit'])
		{
			$this->_PollStartEdit();
		}
		elseif ($PowerBB->_GET['poll_delet'])
		{
			$this->_PollStartdelet();
		}
		elseif ($PowerBB->_GET['show_votes'])
		{
			$this->_StartShowVotes();
		}
		elseif ($PowerBB->_GET['poll_close'])
		{
			$this->_StartPollClose();
		}
		elseif ($PowerBB->_GET['poll_open'])
		{
			$this->_StartPollOpen();
		}
		else
		{
		  header("Location: index.php");
		  exit;
		}
		$PowerBB->functions->GetFooter();
	}

	function _Start()
	{
		global $PowerBB;
		$PowerBB->functions->ShowHeader();

      $PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');

		// Clean the id from any strings
		$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');
		$PowerBB->_GET['subject_id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['subject_id'],'intval');


		if (empty($PowerBB->_GET['id']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}

		if (empty($PowerBB->_GET['subject_id']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}

		if ($PowerBB->_CONF['group_info']['vote_poll'] == 0)
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['You_have_no_powers_to_use_this_system']);
		}
           if ($PowerBB->_CONF['member_permission'])
         {
            $poll_id  = $PowerBB->_GET['id'];
             $username   =  $PowerBB->_CONF['member_row']['username'];
             $votey = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['vote'] . " WHERE username = '$username' AND poll_id = '$poll_id' "));
	           if ($votey)
	           {
				$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['you_canot_votes']);
	           }
		  }
		$PollArr = array();
		$PollArr['where'] = array('id',$PowerBB->_GET['id']);

		$Poll = $PowerBB->core->GetInfo($PollArr,'poll');

		if (!$Poll)
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Vote_be_non-existent']);
		}

		if (!isset($PowerBB->_POST['answer']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['You_must_choose_to_accept_your_Vote']);
		}

		if (!$PowerBB->_CONF['member_row']['username'])
		{
			$PowerBB->_CONF['member_row']['username'] = $PowerBB->_CONF['template']['_CONF']['lang']['Guest'];
		}

          $PowerBB->_POST['answer'] = $PowerBB->Powerparse->censor_words($PowerBB->_POST['answer']);
         // Kill XSS
		$PowerBB->functions->CleanVariable($PowerBB->_POST['answer'],'html');
		$PowerBB->functions->CleanVariable($PowerBB->_POST['answer'],'sql');
		$CheckArr 						= 	array();

		$CheckArr['where'][0] 			= 	array();
		$CheckArr['where'][0]['name'] 	= 	'poll_id';
		$CheckArr['where'][0]['oper'] 	= 	'=';
		$CheckArr['where'][0]['value'] 	= 	$PowerBB->_GET['id'];

		$CheckArr['where'][1] 			= 	array();
		$CheckArr['where'][1]['con'] 	= 	'AND';
		$CheckArr['where'][1]['name'] 	= 	'member_id';
		$CheckArr['where'][1]['oper'] 	= 	'=';
		$CheckArr['where'][1]['value'] 	= 	$PowerBB->_CONF['member_row']['id'];
      if (!$PowerBB->_CONF['member_permission'])
       {
		$CheckArr['where'][2] 			= 	array();
		$CheckArr['where'][2]['con'] 	= 	'AND';
		$CheckArr['where'][2]['name'] 	= 	'user_ip';
		$CheckArr['where'][2]['oper'] 	= 	'=';
		$CheckArr['where'][2]['value'] 	= 	$PowerBB->_CONF['ip'];
       }


		$Vote = $PowerBB->core->GetInfo($CheckArr,'vote');

		if ($Vote != false)
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['You_are_not_allowed_to_vote_more_than_once']);
		}

		$VoteArr 				= 	array();
		$VoteArr['field']		=	array();
		$VoteArr['field']['answer_number'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['answer'],'html');
		$VoteArr['field']['votes']      	+= 	1;
		$VoteArr['field']['poll_id'] 	    = 	$PowerBB->_GET['id'];
		$VoteArr['field']['subject_id'] 	= 	$PowerBB->_GET['subject_id'];
		$VoteArr['field']['member_id'] 	    = 	$PowerBB->_CONF['member_row']['id'];
		$VoteArr['field']['username'] 	    = 	$PowerBB->_CONF['member_row']['username'];
		$VoteArr['field']['user_ip'] 	    = 	$PowerBB->_CONF['ip'];


		 $Insert = $PowerBB->core->Insert($VoteArr,'vote');
        if ($Insert)
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Your_vote_has_been_calculated']);
			$PowerBB->functions->redirect('index.php?page=topic&amp;show=1&amp;id=' . $Poll['subject_id']);
		}
	}

	function _PollEdit()
	{
		global $PowerBB;
		$PowerBB->functions->ShowHeader();
  		$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');

		if (empty($PowerBB->_GET['id']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}
		if (empty($PowerBB->_GET['poll_section']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}
		if (empty($PowerBB->_GET['user']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}


	  if ($PowerBB->functions->ModeratorCheck($PowerBB->_GET['poll_section'])
	  or !$PowerBB->_CONF['member_row']['username'] == $PowerBB->_GET['user']
	  or $PowerBB->_CONF['member_row']['username'] == $PowerBB->_GET['user'])
		{

		$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');

   		$PowerBB->_GET['subject_id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['subject_id'],'intval');


				$PollArr 			= 	array();
				$PollArr['where'] 	= 	array('id',$PowerBB->_GET['id']);

				$Poll = $PowerBB->core->GetInfo($PollArr,'poll');
				if(strstr($Poll['answers'],'['))
				{			      $answers__number = sizeof(json_decode($Poll['answers'], true));
	              $PowerBB->template->assign('answers__number',$answers__number);
				}


			    // Aha, there is poll in this subject
	            $PowerBB->template->assign('Poll',$Poll);
	            $PowerBB->template->assign('subject_id',$PowerBB->_GET['subject_id']);
                $PowerBB->template->display('edit_poll_table1');

				if(strstr($Poll['answers'],'['))
				{
			    $Poll['answers'] = json_decode($Poll['answers'], true);
                }
                else
				{
			    $Poll['answers'] = unserialize($Poll['answers']);
                }

	            foreach($Poll['answers'] as $answers_number => $answers)
	            {
                 if (!empty($answers))
                 {
					$subject_id  = $PowerBB->_GET['id'];
					$vote_nm = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['vote'] . " WHERE answer_number = " . $answers_number . " AND subject_id = " . $subject_id . " "));

					$answers = $PowerBB->Powerparse->censor_words($answers);
					$PowerBB->template->assign('answers',$answers);
					$PowerBB->template->assign('answers_number',$answers_number);
					$PowerBB->template->assign('Vote',$vote_nm);

					$CheckArr 						= 	array();

					$CheckArr['where'][0] 			= 	array();
					$CheckArr['where'][0]['name'] 	= 	'subject_id';
					$CheckArr['where'][0]['oper'] 	= 	'=';
					$CheckArr['where'][0]['value'] 	= 	$PowerBB->_GET['id'];


					$CheckArr['where'][1] 			= 	array();
					$CheckArr['where'][1]['con'] 	= 	'AND';
					$CheckArr['where'][1]['name'] 	= 	'username';
					$CheckArr['where'][1]['oper'] 	= 	'=';
					$CheckArr['where'][1]['value'] 	= 	$PowerBB->_CONF['member_row']['username'];

					$ShowVote = $PowerBB->core->GetInfo($CheckArr,'vote');

					$PowerBB->template->assign('ShowVote',$ShowVote);
					$PowerBB->template->display('edit_poll_table2');
				  }
				}

				$PowerBB->template->display('edit_poll_table3');

		}
		else
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['not_allowed_access']);
		}
	}

	function _PollStartEdit()
	{
		global $PowerBB;
		$PowerBB->functions->ShowHeader();

		$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');

	    if (isset($PowerBB->_POST['question'])
		and isset($PowerBB->_POST['answer'][0])
		and isset($PowerBB->_POST['answer'][1]))
	  {

		$answers_number = $PowerBB->_POST['poll_answers_count']+$PowerBB->_POST['poll_answers_old'];

		$answers = array();

		$x = 0;

		while ($x < $answers_number)
		{
			// The text of the answer
            $answersss = utf8_decode($PowerBB->_POST['answer'][$x]);
            $answersss = preg_replace('/\s+/', '', $answersss);
			$answers[$x][0] = $PowerBB->_POST['answer'][$x];

			if (empty($answersss))
	      	{
				$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['fill_in_answer']);
	      	}

			if(strlen($answersss) >= "1")
			{
			// Continue
			}
			else
			{
			 $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['fill_in_answer']);
			}
			// The result
			$answers[$x][1] = 0;

			$x += 1;
		}
         // Kill XSS
		$PowerBB->functions->CleanVariable($PowerBB->_POST['question'],'html');
		$PowerBB->functions->CleanVariable($PowerBB->_POST['question'],'sql');

		$PowerBB->functions->CleanVariable($PowerBB->_POST['answer'],'html');
		$PowerBB->functions->CleanVariable($PowerBB->_POST['answer'],'sql');

            $question = utf8_decode($PowerBB->_POST['question']);
            $question = preg_replace('/\s+/', '', $question);

		if (empty($question))
      	{
      		$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['fill_in_question']);
      	}

 		$UpdateArr 			= 	array();
		$UpdateArr['field']	=	array();

		$UpdateArr['field']['qus']   	= 	$PowerBB->_POST['question'];
		$UpdateArr['field']['answers'] 	= 	$PowerBB->_POST['answer'];
		$UpdateArr['where']				=	array('id',$PowerBB->_GET['id']);

		$UpdatePoll = $PowerBB->poll->UpdatePoll($UpdateArr);

		if ($UpdatePoll)
		{
		$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Updated_successfully']);
		$PowerBB->functions->redirect('index.php?page=topic&amp;show=1&amp;id=' . $PowerBB->_GET['subject_id']);

		}
	  }

	}

	function _PollStartdelet()
	{
		global $PowerBB;
		$PowerBB->functions->ShowHeader();

		$PowerBB->_GET['subject_id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['subject_id'],'intval');
		$PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');

		if (!empty($PowerBB->_GET['id']))
		{
		$PollArr = array();
		$PollArr['where'] = array('id',$PowerBB->_GET['id']);

		$Poll = $PowerBB->poll->GetPollInfo($PollArr);

			if (!$Poll)
			{
				$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Vote_be_non-existent']);
			}

        }

		if (!empty($PowerBB->_GET['subject_id']))
		{
	  	    $SubjectArr = array();
			$SubjectArr['where'] = array('id',$PowerBB->_GET['subject_id']);

			$SubjectInfo = $PowerBB->core->GetInfo($SubjectArr,'subject');

				if (!$SubjectInfo)
				{
				 $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Sorry_requested_topic_does_not_exist']);
				}


			    if (!$PowerBB->functions->ModeratorCheck($SubjectInfo['section'])
			    or $PowerBB->_CONF['member_row']['username'] != $SubjectInfo['writer'])
				{
	              $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['not_allowed_access']);
				}



			    $UpdateArr 					= 	array();
			    $UpdateArr['field'] 				= 	array();
			    $UpdateArr['field']['poll_subject'] 		= 	'0';
				$UpdateArr['where'] 				= 	array('id',$PowerBB->_GET['subject_id']);

				$update = $PowerBB->core->Update($UpdateArr,'subject');
		}

	 		$DeleteArr 			= 	array();
			$DeleteArr['where']				=	array('subject_id',$PowerBB->_GET['subject_id']);
			$DeletePoll = $PowerBB->poll->DeletePoll($DeleteArr);

			if ($DeletePoll)
			{
			$VoteId = $PowerBB->_GET['id'];
	        $GetVoteInfo = $PowerBB->DB->sql_query("SELECT  *   FROM " . $PowerBB->table['vote'] . " WHERE id = '$VoteId' ");
	        while ($getvoteInfo_row = $PowerBB->DB->sql_fetch_array($GetVoteInfo))
	        {
	 		$DeleteArr 			            = 	array();
			$DeleteArr['where']				=	array('poll_id',$getvoteInfo_row['id']);

			$DeleteVote = $PowerBB->vote->DeleteVote($DeleteArr);
	        }


			$UpdateArr = array();
			$UpdateArr['poll_subject'] = "0";
			$UpdateArr['where'] = array('id',$PowerBB->_GET['subject_id']);

			$update = $PowerBB->subject->CloseSubject($UpdateArr);


	       }

		$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['poll_delet_successfully']);
		$PowerBB->functions->redirect('index.php?page=topic&amp;show=1&amp;id=' . $PowerBB->_GET['subject_id']);



	}

	function _StartPollClose()
	{
		global $PowerBB;
		$PowerBB->functions->ShowHeader();

		$PowerBB->_GET['subject_id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['subject_id'],'intval');

		if (empty($PowerBB->_GET['subject_id']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}


			// INSERT moderators Action
			$EditAction				=	array();
		    $EditAction['where'] 	= 	array('id',$PowerBB->_GET['subject_id']);

			$action = $PowerBB->core->GetInfo($EditAction,'subject');


		    $UpdateArr 					= 	array();
		    $UpdateArr['field'] 				= 	array();
		    $UpdateArr['field']['close_poll_subject'] 		= 	'1';
			$UpdateArr['where'] 				= 	array('id',$PowerBB->_GET['subject_id']);

			$update = $PowerBB->core->Update($UpdateArr,'subject');


		    $subject_title = $action['title'];
		    $time=time()+$PowerBB->_CONF['info_row']['timestamp'];

	        $SmLogsArr 			= 	array();
			$SmLogsArr['field']	=	array();

			$SmLogsArr['field']['username'] 	= 	$PowerBB->_CONF['member_row']['username'];
			$SmLogsArr['field']['edit_action'] 	= 	$PowerBB->_CONF['template']['_CONF']['lang']['poll_close'];
			$SmLogsArr['field']['subject_title']= 	$subject_title;
			$SmLogsArr['field']['subject_id'] 	= 	$PowerBB->_GET['subject_id'];
			$SmLogsArr['field']['edit_date'] 	= 	date("d/m/Y", $time);

			$insert = $PowerBB->core->Insert($SmLogsArr,'supermemberlogs');

			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Has_been_poll_close']);
			$PowerBB->functions->redirect('index.php?page=topic&amp;show=1&amp;id=' . $PowerBB->_GET['subject_id']);
	}

	function _StartPollOpen()
	{
		global $PowerBB;
		$PowerBB->functions->ShowHeader();

		$PowerBB->_GET['subject_id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['subject_id'],'intval');

		if (empty($PowerBB->_GET['subject_id']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}


			// INSERT moderators Action
			$EditAction				=	array();
		    $EditAction['where'] 	= 	array('id',$PowerBB->_GET['subject_id']);

			$action = $PowerBB->core->GetInfo($EditAction,'subject');


		    $UpdateArr 					= 	array();
		    $UpdateArr['field'] 				= 	array();
		    $UpdateArr['field']['close_poll_subject'] 		= 	'0';
			$UpdateArr['where'] 				= 	array('id',$PowerBB->_GET['subject_id']);

			$update = $PowerBB->core->Update($UpdateArr,'subject');


		    $subject_title = $action['title'];
		    $time=time()+$PowerBB->_CONF['info_row']['timestamp'];

	        $SmLogsArr 			= 	array();
			$SmLogsArr['field']	=	array();

			$SmLogsArr['field']['username'] 	= 	$PowerBB->_CONF['member_row']['username'];
			$SmLogsArr['field']['edit_action'] 	= 	$PowerBB->_CONF['template']['_CONF']['lang']['poll_open'];
			$SmLogsArr['field']['subject_title']= 	$subject_title;
			$SmLogsArr['field']['subject_id'] 	= 	$PowerBB->_GET['subject_id'];
			$SmLogsArr['field']['edit_date'] 	= 	date("d/m/Y", $time);

			$insert = $PowerBB->core->Insert($SmLogsArr,'supermemberlogs');

			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Has_been_poll_open']);
			$PowerBB->functions->redirect('index.php?page=topic&amp;show=1&amp;id=' . $PowerBB->_GET['subject_id']);
	}

	function _StartShowVotes()
	{
		global $PowerBB;

       $PowerBB->functions->ShowHeader();

		if (empty($PowerBB->_GET['id']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}

       $PowerBB->_GET['id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['id'],'intval');
	   $PowerBB->_GET['subject_id'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['subject_id'],'intval');

		$PollArr = array();
		$PollArr['where'] = array('id',$PowerBB->_GET['id']);

		$Poll = $PowerBB->poll->GetPollInfo($PollArr);


		if (!$Poll)
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Vote_be_non-existent']);
		}

 		 $PollArr 			= 	array();
		 $PollArr['where'] 	= 	array('subject_id',$PowerBB->_GET['subject_id']);

		  $Poll = $PowerBB->poll->GetPollInfo($PollArr);
           if ($Poll)
		   {


			    // Aha, there is poll in this subject
	            $PowerBB->template->assign('Poll',$Poll);
	            $PowerBB->template->assign('subject_id',$PowerBB->_GET['subject_id']);
	            $SubjectArr = array();
		        $SubjectArr['where'] = array('id',$PowerBB->_GET['subject_id']);
		        $SubjectInfov = $PowerBB->core->GetInfo($SubjectArr,'subject');
		        $PowerBB->template->assign('poll_writer',$SubjectInfov['writer']);
		        $PowerBB->template->assign('poll_section',$SubjectInfov['section']);
		        $PowerBB->template->assign('subject_title',$SubjectInfov['title']);
		        $PowerBB->template->assign('subject_id',$PowerBB->_GET['subject_id']);

		        $SectionArr = array();
		        $SectionArr['where'] = array('id',$SubjectInfov['section']);
		        $SectionInfov = $PowerBB->core->GetInfo($SectionArr,'section');
                $PowerBB->template->assign('section_title',$SectionInfov['title']);

		         $subject_id  = $PowerBB->_GET['subject_id'];
                 $Allvote_nm = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['vote'] . " WHERE subject_id = '$subject_id' "));
                 $PowerBB->template->assign('AllVote',$Allvote_nm);

				$PowerBB->template->display('show_votes_top');


				if(strstr($Poll['answers'],'['))
				{
			    $Poll['answers'] = json_decode($Poll['answers'], true);
                }
                else
				{
			    $Poll['answers'] = unserialize($Poll['answers']);
                }


	            foreach($Poll['answers'] as $answers_number => $answers)
	            {
                  if (!empty($answers))
                  {
					$subject_id  = $PowerBB->_GET['subject_id'];
					$vote_nm = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['vote'] . " WHERE answer_number = " . $answers_number . " AND subject_id = " . $subject_id . " "));

					$PowerBB->template->assign('answers',$answers);
					$PowerBB->template->assign('answers_number',$answers_number);
					$PowerBB->template->assign('Vote',$vote_nm);
					$PowerBB->template->assign('username',$vote_nm['username']);

					$CheckArr 						= 	array();

					$CheckArr['where'][0] 			= 	array();
					$CheckArr['where'][0]['name'] 	= 	'subject_id';
					$CheckArr['where'][0]['oper'] 	= 	'=';
					$CheckArr['where'][0]['value'] 	= 	$PowerBB->_GET['subject_id'];


					$CheckArr['where'][1] 			= 	array();
					$CheckArr['where'][1]['con'] 	= 	'AND';
					$CheckArr['where'][1]['name'] 	= 	'answer_number';
					$CheckArr['where'][1]['oper'] 	= 	'=';
					$CheckArr['where'][1]['value'] 	= 	$answers_number;

					$ShowVote = $PowerBB->core->GetInfo($CheckArr,'vote');
					$PowerBB->_CONF['template']['while']['VoteList'] = $PowerBB->core->GetList($CheckArr,'vote');


					$PowerBB->template->assign('ShowVote',$ShowVote);

					$PowerBB->template->display('show_votes');
				  }
				}


			    $PowerBB->template->assign('Poll',$Poll);

				$CheckArr 						= 	array();

				$CheckArr['where'][0] 			= 	array();
				$CheckArr['where'][0]['name'] 	= 	'subject_id';
				$CheckArr['where'][0]['oper'] 	= 	'=';
				$CheckArr['where'][0]['value'] 	= 	$PowerBB->_GET['subject_id'];


				$CheckArr['where'][1] 			= 	array();
				$CheckArr['where'][1]['con'] 	= 	'AND';
				$CheckArr['where'][1]['name'] 	= 	'username';
				$CheckArr['where'][1]['oper'] 	= 	'=';
				$CheckArr['where'][1]['value'] 	= 	$PowerBB->_CONF['member_row']['username'];

				$ShowVote = $PowerBB->core->GetInfo($CheckArr,'vote');

		         $PowerBB->template->assign('ShowVote',$ShowVote);

		         $PowerBB->template->display('show_votes_down');

              }

     }

}

?>
