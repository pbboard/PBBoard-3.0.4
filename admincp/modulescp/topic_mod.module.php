<?php

(!defined('IN_PowerBB')) ? die() : '';

define('IN_ADMIN',true);

$CALL_SYSTEM			        =	array();
$CALL_SYSTEM['TOPICMOD']           =   true;
$CALL_SYSTEM['SECTION']            = 	true;

define('JAVASCRIPT_PowerCode',true);



define('CLASS_NAME','PowerBBTopicmodrMod');

include('../common.php');
class PowerBBTopicmodrMod
{
	function run()
	{
		global $PowerBB;

		if ($PowerBB->_CONF['member_permission'])
		{
			$PowerBB->template->display('header');


			if ($PowerBB->_CONF['rows']['group_info']['admincp_multi_moderation'] == '0')
			{
			  $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
			}


			if ($PowerBB->_GET['control'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_ControlMain();
				}
			}
			elseif ($PowerBB->_GET['add'])
			{
              	if ($PowerBB->_GET['main'])
				{
					$this->_AddTopicModMain();
				}
                elseif ($PowerBB->_GET['start'])
				{
					$this->_AddTopicModStart();
				}
			}
			elseif ($PowerBB->_GET['edit'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_EditMain();
				}
				elseif ($PowerBB->_GET['start'])
				{
					$this->_EditStart();
				}
			}
			elseif ($PowerBB->_GET['del'])
			{
                if ($PowerBB->_GET['start'])
				{
					$this->_DelStart();
				}
			}

		$PowerBB->template->display('footer');
		}

	}


	/**
	 * add TopicMod Main
	 */

	function _AddTopicModMain()
	{
		global $PowerBB;
		//////////

		$SecArr 						= 	array();
		$SecArr['get_from']				=	'db';

		$SecArr['proc'] 				= 	array();
		$SecArr['proc']['*'] 			= 	array('method'=>'clean','param'=>'html');

		$SecArr['order']				=	array();
		$SecArr['order']['field']		=	'sort';
		$SecArr['order']['type']		=	'ASC';

		$SecArr['where']				=	array();
		$SecArr['where'][0]['name']		= 	'parent';
		$SecArr['where'][0]['oper']		= 	'=';
		$SecArr['where'][0]['value']	= 	'0';

		// Get main sections
		$cats = $PowerBB->core->GetList($SecArr,'section');

 		////////////

		// Loop to read the information of main sections
		foreach ($cats as $cat)
		{
             // foreach main sections
			$PowerBB->_CONF['template']['foreach']['forums_list'][$cat['id'] . '_m'] = $cat;

			unset($sectiongroup);

			@include("../cache/forums_cache/forums_cache_".$cat['id'].".php");
			if (!empty($forums_cache))
			{
                $forums = json_decode(base64_decode($forums_cache), true);

					foreach ($forums as $forum)
					{
						//////////////////////////

							$forum['is_sub'] 	= 	0;
							$forum['sub']		=	'';

                              @include("../cache/forums_cache/forums_cache_".$forum['id'].".php");
                               if (!empty($forums_cache))
	                           {

									$subs = json_decode(base64_decode($forums_cache), true);
	                               foreach ($subs as $sub)
									{
									   if ($forum['id'] == $sub['parent'])
	                                    {

												if (!$forum['is_sub'])
												{
													$forum['is_sub'] = 1;
												}
												 $forum['sub'] .= ('<option value="' .$sub['id'] . '">---- '  . $sub['title'] . '</option>');

										  }

					                         ///////////////

													$forum['is_sub_sub'] 	= 	0;
													$forum['sub_sub']		=	'';

		                                       @include("../cache/forums_cache/forums_cache_".$sub['id'].".php");
		                                   if (!empty($forums_cache))
				                           {

												$subs_sub = json_decode(base64_decode($forums_cache), true);
				                               foreach ($subs_sub as $sub_sub)
												{
												   if ($sub['id'] == $sub_sub['parent'])
				                                    {

																	if (!$forum['is_sub_sub'])
																	{
																		$forum['is_sub_sub'] = 1;
																	}

															 $forum['sub_sub'] .= ('<option value="' .$sub_sub['id'] . '">---- '  . $sub_sub['title'] . '</option>');
													  }
												 }

										   }
									 }
								}


							$PowerBB->_CONF['template']['foreach']['forums_list'][$forum['id'] . '_f'] = $forum;
							unset($groups);

		             } // end foreach ($forums)
			  } // end !empty($forums_cache)

				unset($SecArr);
				$SecArr = $PowerBB->DB->sql_free_result($SecArr);
		} // end foreach ($cats)

		$PowerBB->template->display('topictmod_add');

    }

	/**
	 * add TopicMod Start
	 */
	function _AddTopicModStart()
	{
		global $PowerBB;

		if (empty($PowerBB->_POST['title']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_fill_in_all_the_information']);
		}

		$forums = $this->get_activein_forums();

		if ( ! $forums )
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['You_must_select_the_forum']);
		}

			$TopicModArr 			= 	array();
			$TopicModArr['field']	=	array();

			$TopicModArr['field']['title']            = 	 $PowerBB->_POST['title'];
			$TopicModArr['field']['forums'] 		= 	$forums;
			$TopicModArr['field']['title_st'] 		    = 	$PowerBB->_POST['title_st'];
			$TopicModArr['field']['title_end'] 	    	= 	$PowerBB->_POST['title_end'];
			$TopicModArr['field']['state'] 		    = 	$PowerBB->_POST['state'];
			$TopicModArr['field']['pin'] 		    = 	$PowerBB->_POST['pin'];
			$TopicModArr['field']['enabled'] 		    = 	1;
			$TopicModArr['field']['approve'] 		    = 	$PowerBB->_POST['approve'];
			$TopicModArr['field']['move'] 		    = 	$PowerBB->_POST['move'];
			$TopicModArr['field']['reply'] 		    = 	$PowerBB->_POST['reply'];
			$TopicModArr['field']['reply_content'] 		    = 	$PowerBB->_POST['reply_content'];

			$insert = $PowerBB->core->Insert($TopicModArr,'topicmod');


			if ($insert)
			{
	          $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Supervisory_property_was_added_successfully']);
               $PowerBB->functions->redirect('index.php?page=topic_mod&amp;control=1&amp;main=1');
			}

	}

	function _ControlMain()
	{
		global $PowerBB;

        // show TopicMod List
		$TopicModArr 					= 	array();
		$TopicModArr['order']			=	array();
		$TopicModArr['order']['field']	=	'id';
		$TopicModArr['order']['type']	=	'DESC';
		$TopicModArr['proc'] 			= 	array();
		$TopicModArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');

		$PowerBB->_CONF['template']['while']['TopicModsList'] = $PowerBB->core->GetList($TopicModArr,'topicmod');


		$PowerBB->template->display('topictmod_main');
	}




	function _EditMain()
	{
		global $PowerBB;

			if (empty($PowerBB->_GET['id']))
			{
				$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Supervisory_property_requested_does_not_exist']);
			}

			$TopicModEditArr				=	array();
		    $TopicModEditArr['where'] 	= 	array('id',$PowerBB->_GET['id']);

			$TopicModEdit = $PowerBB->core->GetInfo($TopicModEditArr,'topicmod');

           $selected_forums = explode(',', $TopicModEdit['forums']);

		//////////

		$SecArr 						= 	array();
		$SecArr['get_from']				=	'db';

		$SecArr['proc'] 				= 	array();
		$SecArr['proc']['*'] 			= 	array('method'=>'clean','param'=>'html');

		$SecArr['order']				=	array();
		$SecArr['order']['field']		=	'sort';
		$SecArr['order']['type']		=	'ASC';

		$SecArr['where']				=	array();
		$SecArr['where'][0]['name']		= 	'parent';
		$SecArr['where'][0]['oper']		= 	'=';
		$SecArr['where'][0]['value']	= 	'0';

		// Get main sections
		$cats = $PowerBB->section->GetSectionsList($SecArr);

 		////////////

		// Loop to read the information of main sections
		foreach ($cats as $cat)
		{
			include("../cache/sectiongroup_cache/sectiongroup_cache_".$cat['id'].".php");

             // foreach main sections
			$PowerBB->_CONF['template']['foreach']['forums_list'][$cat['id'] . '_m'] = $cat;

			unset($sectiongroup);

			@include("../cache/forums_cache/forums_cache_".$cat['id'].".php");
			if (!empty($forums_cache))
			{
                $forums = json_decode(base64_decode($forums_cache), true);

					foreach ($forums as $forum)
					{
						//////////////////////////

							$forum['is_sub'] 	= 	0;
							$forum['sub']		=	'';

                              @include("../cache/forums_cache/forums_cache_".$forum['id'].".php");
                               if (!empty($forums_cache))
	                           {

									$subs = json_decode(base64_decode($forums_cache), true);
	                               foreach ($subs as $sub)
									{
									   if ($forum['id'] == $sub['parent'])
	                                    {

												if (!$forum['is_sub'])
												{
													$forum['is_sub'] = 1;
												}
                               		        if (in_array($sub['id'] , $selected_forums))
                               		        {
											$forum['sub'] .= ('<option value="' .$sub['id'] . '" selected="selected" >---'  . $sub['title'] . '</option>');
											}
											else
											{
											$forum['sub'] .= ('<option value="' .$sub['id'] . '" >---'  . $sub['title'] . '</option>');
											}
										  }




					                         ///////////////

													$forum['is_sub_sub'] 	= 	0;
													$forum['sub_sub']		=	'';

		                                       @include("../cache/forums_cache/forums_cache_".$sub['id'].".php");
		                                   if (!empty($forums_cache))
				                           {

												$subs_sub = json_decode(base64_decode($forums_cache), true);
				                               foreach ($subs_sub as $sub_sub)
												{
												   if ($sub['id'] == $sub_sub['parent'])
				                                    {

																	if (!$forum['is_sub_sub'])
																	{
																		$forum['is_sub_sub'] = 1;
																	}

                               		                            if (in_array($sub_sub['id'] , $selected_forums))
				                                		        {
																$forum['sub_sub'] .= ('<option value="' .$sub_sub['id'] . '" selected="selected" >---'  . $sub_sub['title'] . '</option>');
																}
																else
																{
																$forum['sub_sub'] .= ('<option value="' .$sub_sub['id'] . '" >---'  . $sub_sub['title'] . '</option>');
																}
													  }
												 }

										   }
									 }
								}


							$PowerBB->_CONF['template']['foreach']['forums_list'][$forum['id'] . '_f'] = $forum;
							unset($groups);

		             } // end foreach ($forums)
			  } // end !empty($forums_cache)

				unset($SecArr);
				$SecArr = $PowerBB->DB->sql_free_result($SecArr);
		} // end foreach ($cats)

			$PowerBB->template->assign('TopicModEdit',$TopicModEdit);


		$PowerBB->template->display('topictmod_edit');
	}

	function _EditStart()
	{
		global $PowerBB;

			if (empty($PowerBB->_GET['id']))
			{
				$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Supervisory_property_requested_does_not_exist']);
			}



		$forums = $this->get_activein_forums();

		if ( ! $forums )
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['You_must_select_the_forum']);
		}


			$TopicModArr 			= 	array();
			$TopicModArr['field']	=	array();

			$TopicModArr['field']['title']            = 	 $PowerBB->_POST['title'];
			$TopicModArr['field']['forums'] 		= 	$forums;
			$TopicModArr['field']['title_st'] 		    = 	$PowerBB->_POST['title_st'];
			$TopicModArr['field']['title_end'] 	    	= 	$PowerBB->_POST['title_end'];
			$TopicModArr['field']['state'] 		    = 	$PowerBB->_POST['state'];
			$TopicModArr['field']['pin'] 		    = 	$PowerBB->_POST['pin'];
			$TopicModArr['field']['enabled'] 		    = 	1;
			$TopicModArr['field']['approve'] 		    = 	$PowerBB->_POST['approve'];
			$TopicModArr['field']['move'] 		    = 	$PowerBB->_POST['move'];
			$TopicModArr['field']['reply'] 		    = 	$PowerBB->_POST['reply'];
			$TopicModArr['field']['reply_content'] 		    = 	$PowerBB->_POST['reply_content'];
		    $TopicModArr['where'] 				= 	array('id',$PowerBB->_GET['id']);

		    $update = $PowerBB->core->Update($TopicModArr,'topicmod');

		if ($update)
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Supervisory_property_has_been_updated_successfully']);
               $PowerBB->functions->redirect('index.php?page=topic_mod&amp;control=1&amp;main=1');
		}
	}

	function _DelStart()
	{
		global $PowerBB;

			if (empty($PowerBB->_GET['id']))
			{
				$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Supervisory_property_requested_does_not_exist']);
			}

			$DelArr 			= 	array();
			$DelArr['where'] 	= 	array('id',$PowerBB->_GET['id']);

			$del = $PowerBB->core->Deleted($DelArr,'topicmod');

		if ($del)
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Supervisory_property_was_deleted_successfully']);
               $PowerBB->functions->redirect('index.php?page=topic_mod&amp;control=1&amp;main=1');

		}
	}

	//-----------------------------------------
    // Get the active in forums
    //-----------------------------------------

    function get_activein_forums()
    {

		global $PowerBB;

		$forumids = array();

    	//-----------------------------------------
    	// Check for an array
    	//-----------------------------------------

    	if ( is_array( $PowerBB->_POST['forums'] )  )
    	{

    		if ( in_array( 'all', $PowerBB->_POST['forums'] ) )
    		{
    			//-----------------------------------------
    			// Searching all forums..
    			//-----------------------------------------

    			return '*';
    		}
    		else
    		{
				//-----------------------------------------
				// Go loopy loo
				//-----------------------------------------

				foreach( $PowerBB->_POST['forums'] as $l )
				{

						$forumids[] = intval($l);
				}

				//-----------------------------------------
				// Do we have cats? Give 'em to Charles!
				//-----------------------------------------

				if ( count( $forumids  ) )
				{
					foreach( $forumids  as $f )
					{
						if ( is_array($f) and count($f) )
						{
							$forumids  = array_merge( $forumids , $f );
						}
					}
				}
				else
				{
					//-----------------------------------------
					// No forums selected / we have available
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

			if ($PowerBB->_POST['forums'] == 'all' )
			{
				return '*';
			}
			else
			{
				if ( $PowerBB->_POST['forums'] != "" )
				{
					$l = intval($PowerBB->_POST['forums']);

					//-----------------------------------------
					// Single forum
					//-----------------------------------------


						$forumids[] = intval($l);


						if ( is_array($f) and count($f) )
						{
							$forumids  = array_merge( $forumids , $f );
						}
				}
			}
		}

		return implode( ",", $forumids );
    }

}

?>
