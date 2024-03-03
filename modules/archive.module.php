<?php
(!defined('IN_PowerBB')) ? die() : '';

define('CLASS_NAME','PowerBBCoreMOD');

include('common.php');
class PowerBBCoreMOD
{
	function run()
	{
		// Who can live without $PowerBB ? ;)
		global $PowerBB;
 		if ($PowerBB->_CONF['info_row']['active_archive'] == '0')
		{		   @header("Location: index.php");
		   exit;
		}
		/**
		 * Firstly we get sections list
		 */
		$this->_GetSections();

		/**
		 * Show main template
		 */
		$this->_CallTemplate();

	}

	/**
	 * Get sections list from cache and show it.
	 */
		/**
	 * Get sections list from cache and show it.
	 */
	function _GetSections()
	{
		global $PowerBB;
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

		// Loop to read the information of main sections
		foreach ($cats as $cat)
		{
	       // Get the groups information to know view this section or not
          if ($PowerBB->functions->section_group_permission($cat['id'],$PowerBB->_CONF['group_info']['id'],'view_section'))
	      {
             // foreach main sections
			$PowerBB->_CONF['template']['foreach']['forums_list'][$cat['id'] . '_m'] = $cat;

			if($PowerBB->_CONF['files_forums_Cache'])
			{
			@include("cache/forums_cache/forums_cache_".$cat['id'].".php");
			}
			else
			{
			$forums_cache = $PowerBB->functions->get_forum_cache($cat['id'],$cat['forums_cache']);
			}

			if (!empty($forums_cache))
			{
               $forums = $PowerBB->functions->decode_forum_cache($forums_cache);

					foreach ($forums as $forum)
					{

						if ($PowerBB->functions->section_group_permission($forum['id'],$PowerBB->_CONF['group_info']['id'],'view_section'))
						{

		                    if ($PowerBB->_CONF['info_row']['no_sub'])
						    {
								$forum['is_sub_archive'] 	= 	0;
								$forum['is_sub_archive']		=	'';

									if($PowerBB->_CONF['files_forums_Cache'])
									{
									@include("cache/forums_cache/forums_cache_".$forum['id'].".php");
									}
									else
									{
									$forums_cache = $PowerBB->functions->get_forum_cache($forum['id'],$forum['forums_cache']);
									}
									if (!empty($forums_cache))
		                           {
										$subs = $PowerBB->functions->decode_forum_cache($forums_cache);
		                               foreach($subs as $sub)
										{
										   if ($forum['id'] == $sub['parent'])
		                                    {

													if ($PowerBB->functions->section_group_permission($sub['id'],$PowerBB->_CONF['group_info']['id'],'view_section'))
												   {

														if (!$forum['is_sub_archive'])
														{
															$forum['is_sub_archive'] = 1;
														}
                                                        $forum_url = "index.php?page=forum_archive&amp;show=1&amp;id=";
														$forum['sub_archive'] .= '<ul><li><a href="'.$PowerBB->functions->rewriterule($forum_url).$sub['id'].'">'.$sub['title'].'</a></li></ul>';

                                                       // subs forum ++
														if($PowerBB->_CONF['files_forums_Cache'])
														{
														@include("cache/forums_cache/forums_cache_".$sub['id'].".php");
														}
														else
														{
														$forums_cache = $PowerBB->functions->get_forum_cache($sub['id'],$sub['forums_cache']);
														}
														if (!empty($forums_cache))
						                                {
														    $subsforum = $PowerBB->functions->decode_forum_cache($forums_cache);
						                                   foreach($subsforum as $subforum)
														    {
														      if ($sub['id'] == $subforum['parent'])
														     {
 														      $forum['sub_archive'] .= '<ul><li style="padding-right: 20px;"><a href="'.$PowerBB->functions->rewriterule($forum_url).$subforum['id'].'">'.$subforum['title'].'</a></li></ul>';
 														     }

		                                                        // subs forum +++
																if($PowerBB->_CONF['files_forums_Cache'])
																{
																@include("cache/forums_cache/forums_cache_".$subforum['id'].".php");
																}
																else
																{
																$forums_cache = $PowerBB->functions->get_forum_cache($subforum['id'],$subforum['forums_cache']);
																}
																if (!empty($forums_cache))
								                                {
																    $subs4forum = $PowerBB->functions->decode_forum_cache($forums_cache);

								                                   foreach($subs4forum  as $sub4forum)
																    {
																      if ($subforum['id'] == $sub4forum['parent'])
																     {

														              $forum['sub_archive'] .= '<ul><li style="padding-right: 60px;"><a href="'.$PowerBB->functions->rewriterule($forum_url).$sub4forum['id'].'">'.$sub4forum['title'].'</a></li></ul>';

																     }
																				if($PowerBB->_CONF['files_forums_Cache'])
																				{
																				@include("cache/forums_cache/forums_cache_".$sub4forum['id'].".php");
																				}
																				else
																				{
																				$forums_cache = $PowerBB->functions->get_forum_cache($sub4forum['id'],$sub4forum['forums_cache']);
																				}
																				if (!empty($forums_cache))
												                                {
																				    $subs5forum = $PowerBB->functions->decode_forum_cache($forums_cache);
												                                   foreach($subs5forum  as $sub5forum)
																				    {
																				      if ($sub4forum['id'] == $sub5forum['parent'])
																				     {

																		              $forum['sub_archive'] .= '<ul><li style="padding-right: 80px;"><a href="'.$PowerBB->functions->rewriterule($forum_url).$sub5forum['id'].'">'.$sub5forum['title'].'</a></li></ul>';

																				     }

																				    }

																				}

																    }

																}

															}

														}


											        }
											  }
										 }


								   }
		                     }
						   //////////

							$PowerBB->_CONF['template']['foreach']['forums_list'][$forum['id'] . '_f'] = $forum;
							unset($groups);

						}// end view forums
		             } // end foreach ($forums)
			  } // end !empty($forums_cache)
		   } // end view section

				unset($SecArr);
				$SecArr = $PowerBB->DB->sql_free_result($SecArr);
		} // end foreach ($cats)

		//////////
	}


	function _CallTemplate()
	{
		global $PowerBB;
		$PowerBB->template->display('archive_main');
		$PowerBB->template->display('archive_footer');
	}
}

// The end , Hey it's first module wrote for PowerBB 2.0 :) , 24/5/2009 -> 4:24 PM

?>
