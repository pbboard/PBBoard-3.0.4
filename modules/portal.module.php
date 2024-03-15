<?php
(!defined('IN_PowerBB')) ? die() : '';
define('IN_ADMIN',true);
define('JAVASCRIPT_PowerCode',true);
define('CLASS_NAME','PowerBBPortalMOD');
include('../common.php');
class PowerBBPortalMOD
{
	function run()
	{
		global $PowerBB;

		if ($PowerBB->_CONF['member_permission'])
		{


			if ($PowerBB->_GET['control'])
			{
			    $PowerBB->template->display('header');
				if ($PowerBB->_GET['main'])
				{
					$this->_ControlMain();
				}
                elseif ($PowerBB->_GET['start'])
				{
					$this->_ControlStart();
				}
			}
			elseif ($PowerBB->_GET['add_block'])
			{
              	if ($PowerBB->_GET['main'])
				{
			        $PowerBB->template->display('header');
					$this->_AddBlockMain();
				}
                elseif ($PowerBB->_GET['start'])
				{
					$this->_AddBlockStart();
				}
			}
			elseif ($PowerBB->_GET['control_blocks'])
			{
			    $PowerBB->template->display('header');
              	if ($PowerBB->_GET['main'])
				{
					$this->_ControlBlocksMain();
				}
                elseif ($PowerBB->_GET['start'])
				{
					$this->_ControlBlocksStart();
				}
			}
			elseif ($PowerBB->_GET['edit_block'])
			{
			   $PowerBB->template->display('header');
				if ($PowerBB->_GET['main'])
				{
					$this->_EditMain();
				}
				elseif ($PowerBB->_GET['start'])
				{
					$this->_EditStart();
				}
			}
			elseif ($PowerBB->_GET['del_block'])
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
	 * add Portal Main
	 */

	function _AddBlockMain()
	{
		global $PowerBB;

		$PowerBB->template->display('portal_add_block');

    }

	/**
	 * add Portal Start
	 */
	function _AddBlockStart()
	{
		global $PowerBB;

		if (empty($PowerBB->_POST['title'])
			or empty($PowerBB->_POST['text']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_fill_in_all_the_information']);
		}

			$title         = 	 $PowerBB->_POST['title'];
			$text 		   = 	$PowerBB->_POST['text'];
			$place_block   = 	$PowerBB->_POST['place_block'];

		$sort = 0;
		if ($PowerBB->_POST['order_type'] == 'auto')
		{
          $blocks_info = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->prefix."blocks" . " ORDER BY sort DESC");
           $get_blocks_row = $PowerBB->DB->sql_fetch_array($blocks_info);

			// No section
			if (!$get_blocks_row)
			{
				$sort = 1;
			}
			// There is a section
			else
			{
				$sort = $get_blocks_row['sort'] + 1;
			}
		}
		else
		{
			$sort = $PowerBB->_POST['sort'];
		}

			$BlocksArr 			= 	array();
			$BlocksArr['field']	=	array();
			$BlocksArr['field']['title']                  = 	 $title;
			$BlocksArr['field']['text']                   = 	 $text;
			$BlocksArr['field']['place_block']            = 	 $place_block;
			$BlocksArr['field']['sort']                   = 	 $sort;

			$insert = $PowerBB->core->Insert($BlocksArr,'blocks');

			if ($insert)
			{
               $PowerBB->functions->header_redirect('index.php?page=portal&control_blocks=1&amp;main=1');
			}
	}

	function _ControlMain()
	{
		global $PowerBB;

        // show Portal List

           $selected_forums = explode(',', $PowerBB->_CONF['info_row']['portal_section_news']);

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
             // foreach main sections
			$PowerBB->_CONF['template']['foreach']['forums_list'][$cat['id'] . '_m'] = $cat;
			if($PowerBB->_CONF['files_forums_Cache'])
			{
			@include("../cache/forums_cache/forums_cache_".$cat['id'].".php");
			}
			else
			{
			$forums_cache = $cat['forums_cache'];
			}
			if (!empty($forums_cache))
			{
               	$ForumArr 						= 	array();
				$ForumArr['get_from']				=	'db';
				$ForumArr['proc'] 				= 	array();
				$ForumArr['proc']['*'] 			= 	array('method'=>'clean','param'=>'html');
				$ForumArr['order']				=	array();
				$ForumArr['order']['field']		=	'sort';
				$ForumArr['order']['type']		=	'ASC';
				$ForumArr['where']				=	array();
				$ForumArr['where'][0]['name']		= 	'parent';
				$ForumArr['where'][0]['oper']		= 	'=';
				$ForumArr['where'][0]['value']	= 	$cat['id'];
				$forums = $PowerBB->core->GetList($ForumArr,'section');
					foreach ($forums as $forum)
					{
						//////////////////////////

							$forum['is_sub'] 	= 	0;
							$forum['sub']		=	'';

							if ($PowerBB->_CONF['files_forums_Cache'])
							{
							@include("../cache/forums_cache/forums_cache_".$forum['id'].".php");
							}
							else
							{
							$forums_cache = $forum['forums_cache'];
							}
							  if (!empty($forums_cache))
	                           {
					               	$SubArr 						= 	array();
									$SubArr['get_from']				=	'db';
									$SubArr['proc'] 				= 	array();
									$SubArr['proc']['*'] 			= 	array('method'=>'clean','param'=>'html');
									$SubArr['order']				=	array();
									$SubArr['order']['field']		=	'sort';
									$SubArr['order']['type']		=	'ASC';
									$SubArr['where']				=	array();
									$SubArr['where'][0]['name']		= 	'parent';
									$SubArr['where'][0]['oper']		= 	'=';
									$SubArr['where'][0]['value']	= 	$forum['id'];
									$subs = $PowerBB->core->GetList($SubArr,'section');
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

											$forum['is_sub_sub'] 	= 	0;
											$forum['sub_sub']		=	'';

											if ($PowerBB->_CONF['files_forums_Cache'])
											{
											@include("../cache/forums_cache/forums_cache_".$sub['id'].".php");
											}
											else
											{
											$forums_cache = $sub['forums_cache'];
											}
											if (!empty($forums_cache))
				                           {
							               	$SubsArr 						= 	array();
											$SubsArr['get_from']				=	'db';
											$SubsArr['proc'] 				= 	array();
											$SubsArr['proc']['*'] 			= 	array('method'=>'clean','param'=>'html');
											$SubsArr['order']				=	array();
											$SubsArr['order']['field']		=	'sort';
											$SubsArr['order']['type']		=	'ASC';
											$SubsArr['where']				=	array();
											$SubsArr['where'][0]['name']		= 	'parent';
											$SubsArr['where'][0]['oper']		= 	'=';
											$SubsArr['where'][0]['value']	= 	$sub['id'];
											$subs_sub = $PowerBB->core->GetList($SubsArr,'section');
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


		$PowerBB->template->display('portal_main');
	}

	function _ControlStart()
	{
		global $PowerBB;

        // show Portal List
		$forums = $this->get_activein_forums();

		$update = array();
		$update[0] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['title_portal'],'var_name'=>'title_portal'));
		$update[1] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['active_portal'],'var_name'=>'active_portal'));
		$update[2] = $PowerBB->info->UpdateInfo(array('value'=>$forums,'var_name'=>'portal_section_news'));
		$update[3] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['portal_news_num'],'var_name'=>'portal_news_num'));
	    $update[4] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['portal_columns'],'var_name'=>'portal_columns'));
	    $update[5] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['portal_news_along'],'var_name'=>'portal_news_along'));
	    $update[6] = $PowerBB->info->UpdateInfo(array('value'=>$PowerBB->_POST['style_block_latest_news'],'var_name'=>'style_block_latest_news'));

		if ($update[0]
		    and $update[1]
			and $update[2]
			and $update[3]
            and $update[4]
            and $update[5]
            and $update[6])
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['updated_successfully_Please_wait']);
			$PowerBB->functions->redirect('index.php?page=portal&amp;control=1&amp;main=1');
		}

	}

	function _ControlBlocksMain()
	{
		global $PowerBB;

		$PowerBB->template->display('portal_control_blocks');
	}

	function _ControlBlocksStart()
	{
		global $PowerBB;

      $blocks_info = $PowerBB->DB->sql_query("SELECT  *   FROM " . $PowerBB->prefix."blocks" . " ORDER BY id ASC");
       while ($get_blocks_row = $PowerBB->DB->sql_fetch_array($blocks_info))
      {
			$title = 'title-' . $get_blocks_row['id'];
			$active = 'active-' . $get_blocks_row['id'];
			$place_block = 'place_block-' . $get_blocks_row['id'];
			$sort = 'sort-' . $get_blocks_row['id'];
				$t 	= 	$PowerBB->_POST[$title];
				$a 	= 	$PowerBB->_POST[$active];
				$p 	= 	$PowerBB->_POST[$place_block];
				$s 	= 	$PowerBB->_POST[$sort];
                $id = $get_blocks_row['id'];

	    $BlocksArr 			= 	array();
		$BlocksArr['field']	=	array();

		$BlocksArr['field']['title']                  = 	 $t;
		$BlocksArr['field']['active']                 = 	 $a;
		$BlocksArr['field']['place_block']            = 	 $p;
		$BlocksArr['field']['sort']                   = 	 $s;
		$BlocksArr['where'] 				          = 	array('id',$id);

		$update = $PowerBB->core->Update($BlocksArr,'blocks');

     }
		if ($update)
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['updated_successfully_Please_wait']);
			$PowerBB->functions->redirect('index.php?page=portal&amp;control_blocks=1&amp;main=1');
		}


	}

	function _EditMain()
	{
		global $PowerBB;

			if (empty($PowerBB->_GET['id']))
			{
				$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['The_declaration_does_not_exist']);
			}

           $PowerBB->template->assign('id',$PowerBB->_GET['id']);

		$PowerBB->template->display('portal_edit_block');
	}

	function _EditStart()
	{
		global $PowerBB;

			if (empty($PowerBB->_GET['id']))
			{
				$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['The_declaration_does_not_exist']);
			}


	    $BlocksArr 			= 	array();
		$BlocksArr['field']	=	array();

		$BlocksArr['field']['text']            = 	 $PowerBB->_POST['text'];
		$BlocksArr['where'] 				= 	array('id',$PowerBB->_GET['id']);

		$update = $PowerBB->core->Update($BlocksArr,'blocks');

		if ($update)
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['updated_successfully_Please_wait']);
			$PowerBB->functions->redirect('index.php?page=portal&amp;control_blocks=1&amp;main=1');
		}
	}

	function _DelStart()
	{
		global $PowerBB;

			if (empty($PowerBB->_GET['id']))
			{
				$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['The_declaration_does_not_exist']);
			}
            $id = $PowerBB->_GET['id'];

			$del = $PowerBB->DB->sql_query('DELETE FROM ' . $PowerBB->prefix."blocks" . " WHERE id='$id'");

		if ($del)
		{
			$PowerBB->functions->header_redirect('index.php?page=portal&control_blocks=1&main=1');
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

    	if ( is_array( $PowerBB->_POST['portal_section_news'] )  )
    	{

    		if ( in_array( 'all', $PowerBB->_POST['portal_section_news'] ) )
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

				foreach( $PowerBB->_POST['portal_section_news'] as $l )
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

			if ($PowerBB->_POST['portal_section_news'] == 'all' )
			{
				return '*';
			}
			else
			{
				if ( $PowerBB->_POST['portal_section_news'] != "" )
				{
					$l = intval($PowerBB->_POST['portal_section_news']);

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
