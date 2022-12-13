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

 		////////////

		// Loop to read the information of main sections
		foreach ($cats as $cat)
		{
         if (!file_exists("cache/sectiongroup_cache/sectiongroup_cache_".$cat['id'].".php"))
         {
				$PowerBB->functions->_AllCacheStart();
				$PowerBB->functions->_MeterGroupsStart();
         }
			//////////////////////////

			@include("cache/sectiongroup_cache/sectiongroup_cache_".$cat['id'].".php");
	       // Get the groups information to know view this section or not
	      $sectiongroup = json_decode(base64_decode($sectiongroup_cache), true);
		  if ($sectiongroup[$PowerBB->_CONF['group_info']['id']]['view_section'])
	      {
             // foreach main sections
			$PowerBB->_CONF['template']['foreach']['forums_list'][$cat['id'] . '_m'] = $cat;

			unset($sectiongroup);

			@include("cache/forums_cache/forums_cache_".$cat['id'].".php");
			if (!empty($forums_cache))
			{
                $forums = json_decode(base64_decode($forums_cache), true);

					foreach ($forums as $forum)
					{

						//////////////////////////
					     @include("cache/sectiongroup_cache/sectiongroup_cache_".$forum['id'].".php");
						$groups = json_decode(base64_decode($sectiongroup_cache), true);
						if ($groups[$PowerBB->_CONF['group_info']['id']]['view_section'])
						{

		                    if ($PowerBB->_CONF['info_row']['no_sub'])
						    {
								$forum['is_sub_archive'] 	= 	0;
								$forum['is_sub_archive']		=	'';

                                       @include("cache/forums_cache/forums_cache_".$forum['id'].".php");
                                   if (!empty($forums_cache))
		                           {

										$subs = json_decode(base64_decode($forums_cache), true);
		                               foreach($subs as $sub)
										{
										   if ($forum['id'] == $sub['parent'])
		                                    {
									            @include("cache/sectiongroup_cache/sectiongroup_cache_".$sub['id'].".php");
										        $groups = json_decode(base64_decode($sectiongroup_cache), true);

												  if ($groups[$PowerBB->_CONF['group_info']['id']]['view_section'])
												   {

														if (!$forum['is_sub_archive'])
														{
															$forum['is_sub_archive'] = 1;
														}
                                                        $forum_url = "index.php?page=forum_archive&amp;show=1&amp;id=";
														$forum['sub_archive'] .= '<li><a href="'.$PowerBB->functions->rewriterule($forum_url).$sub['id'].'">'.$sub['title'].'</a></li>';

                                                       // subs forum ++
														@include("cache/forums_cache/forums_cache_".$sub['id'].".php");
														if (!empty($forums_cache))
						                                {
														    $subsforum = json_decode(base64_decode($forums_cache), true);
						                                   foreach($subsforum as $subforum)
														    {
														      if ($sub['id'] == $subforum['parent'])
														     {

														$forum['sub_archive'] .= '<ul><li><a href="'.$PowerBB->functions->rewriterule($forum_url).$subforum['id'].'">'.$subforum['title'].'</a></li></ul>';


														     }

														    }

														}

		                                                   // subs forum +++
																@include("cache/forums_cache/forums_cache_".$subforum['id'].".php");
																if (!empty($forums_cache))
								                                {
																    $subs4forum = json_decode(base64_decode($forums_cache), true);
								                                   foreach($subs4forum  as $sub4forum)
																    {
																      if ($subforum['id'] == $sub4forum['parent'])
																     {

														              $forum['sub_archive'] .= '<ul><li><a href="'.$PowerBB->functions->rewriterule($forum_url).$sub4forum['id'].'">'.$sub4forum['title'].'</a></li></ul>';

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
