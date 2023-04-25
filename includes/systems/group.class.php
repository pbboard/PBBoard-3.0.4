<?php
class PowerBBGroup
{
	var $id;
	var $PowerBB;

	function __construct($PowerBB)
	{
		$this->Engine = $PowerBB;
	}

	function InsertGroup($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$query = $this->Engine->records->Insert($this->Engine->table['group'],$param['field']);

		if (isset($param['get_id']))
		{
			$this->id = $this->Engine->DB->sql_insert_id();
		}

		return ($query) ? true : false;
	}

	function DeleteGroup($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['table'] = $this->Engine->table['group'];

		$del = $this->Engine->records->Delete($param);

		return ($del) ? true : false;
	}

	function UpdateGroup($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$query = $this->Engine->records->Update($this->Engine->table['group'],$param['field'],$param['where']);

		return ($query) ? true : false;
	}

	function GetGroupInfo($param)
 	{
  		if (!isset($param)
  			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['select'] 	= 	'*';
		$param['from'] 		= 	$this->Engine->table['group'];

 		$rows = $this->Engine->records->GetInfo($param);

 		return $rows;
 	}

  	/**
 	 * Get the list of groups
 	 *
 	 * param :
 	 *			sql_statment	->	complete the sql statment
 	 *			way				->	(normal) or (online_table)
 	 */
 	function GetGroupList($param)
 	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

 		$param['select'] 	= 	'*';
 		$param['from'] 		= 	$this->Engine->table['group'];

 	 	$rows = $this->Engine->records->GetList($param);
       $this->Engine->DB->sql_free_result($rows);

 		return $rows;
 	}

	function CreateGroupCache($param)
	{
       global $PowerBB;

  		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

        // get permissions group hooks
		$url = $PowerBB->functions->GetMianDir()."cache/hooks_cache/HooksCache.php";
		@include($url);
		$Hooks_number = @sizeof($Hooks, 1);

 		$cache = array();

 		$GroupArr 						= 	array();
 		$GroupArr['get_from'] 			= 	'db';

 		$GroupArr['order']				=	array();
 		$GroupArr['order']['field']		=	'id';
 		$GroupArr['order']['type']		=	'ASC';

 		$GroupArr['where']				=	array('id',$param['id']);

		$groups = $this->GetGroupList($GroupArr);

 		$x	=	0;
 		$n	=	sizeof($groups);

		while ($x < $n)
		{

	        $cache[$groups[$x]['id']] 					        = 	array();
			$cache[$groups[$x]['id']]['id']		 	            = 	$groups[$x]['id'];
			$cache[$groups[$x]['id']]['title'] 					= 	$groups[$x]['title'];
			$cache[$groups[$x]['id']]['username_style'] 			= 	$groups[$x]['username_style'];
			$cache[$groups[$x]['id']]['user_title'] 				= 	$groups[$x]['user_title'];
			$cache[$groups[$x]['id']]['forum_team'] 				= 	$groups[$x]['forum_team'];
			$cache[$groups[$x]['id']]['banned'] 					= 	$groups[$x]['banned'];
			$cache[$groups[$x]['id']]['view_section'] 			= 	$groups[$x]['view_section'];
			$cache[$groups[$x]['id']]['view_subject'] 		    = 	$groups[$x]['view_subject'];
			$cache[$groups[$x]['id']]['download_attach'] 			= 	$groups[$x]['download_attach'];
			$cache[$groups[$x]['id']]['download_attach_number'] 	= 	$groups[$x]['download_attach_number'];
			$cache[$groups[$x]['id']]['write_subject'] 			= 	$groups[$x]['write_subject'];
			$cache[$groups[$x]['id']]['write_reply'] 				= 	$groups[$x]['write_reply'];
			$cache[$groups[$x]['id']]['upload_attach'] 			= 	$groups[$x]['upload_attach'];
			$cache[$groups[$x]['id']]['upload_attach_num'] 		= 	$groups[$x]['upload_attach_num'];
			$cache[$groups[$x]['id']]['edit_own_subject'] 		= 	$groups[$x]['edit_own_subject'];
			$cache[$groups[$x]['id']]['edit_own_reply'] 			= 	$groups[$x]['edit_own_reply'];
			$cache[$groups[$x]['id']]['del_own_subject'] 			= 	$groups[$x]['del_own_subject'];
			$cache[$groups[$x]['id']]['del_own_reply']			= 	$groups[$x]['del_own_reply'];
			$cache[$groups[$x]['id']]['write_poll'] 				= 	$groups[$x]['write_poll'];
			$cache[$groups[$x]['id']]['vote_poll'] 				= 	$groups[$x]['vote_poll'];
			$cache[$groups[$x]['id']]['use_pm'] 					= 	$groups[$x]['use_pm'];
			$cache[$groups[$x]['id']]['send_pm'] 					= 	$groups[$x]['send_pm'];
			$cache[$groups[$x]['id']]['resive_pm'] 				= 	$groups[$x]['resive_pm'];
			$cache[$groups[$x]['id']]['max_pm'] 					= 	$groups[$x]['max_pm'];
			$cache[$groups[$x]['id']]['min_send_pm'] 				= 	$groups[$x]['min_send_pm'];
			$cache[$groups[$x]['id']]['sig_allow'] 				= 	$groups[$x]['sig_allow'];
			$cache[$groups[$x]['id']]['sig_len'] 					= 	$groups[$x]['sig_len'];
			$cache[$groups[$x]['id']]['group_mod'] 				= 	$groups[$x]['group_mod'];
			$cache[$groups[$x]['id']]['del_subject'] 				= 	$groups[$x]['del_subject'];
			$cache[$groups[$x]['id']]['del_reply'] 				= 	$groups[$x]['del_reply'];
			$cache[$groups[$x]['id']]['edit_subject'] 			= 	$groups[$x]['edit_subject'];
			$cache[$groups[$x]['id']]['edit_reply'] 				= 	$groups[$x]['edit_reply'];
			$cache[$groups[$x]['id']]['stick_subject'] 			= 	$groups[$x]['stick_subject'];
			$cache[$groups[$x]['id']]['unstick_subject'] 			= 	$groups[$x]['unstick_subject'];
			$cache[$groups[$x]['id']]['move_subject'] 			= 	$groups[$x]['move_subject'];
			$cache[$groups[$x]['id']]['close_subject'] 			= 	$groups[$x]['close_subject'];
			$cache[$groups[$x]['id']]['usercp_allow'] 			= 	$groups[$x]['usercp_allow'];
			$cache[$groups[$x]['id']]['admincp_allow'] 			= 	$groups[$x]['admincp_allow'];
			$cache[$groups[$x]['id']]['groups_security'] 			= 	$groups[$x]['groups_security'];
			$cache[$groups[$x]['id']]['search_allow'] 			= 	$groups[$x]['search_allow'];
			$cache[$groups[$x]['id']]['memberlist_allow'] 		= 	$groups[$x]['memberlist_allow'];
			$cache[$groups[$x]['id']]['vice'] 					= 	$groups[$x]['vice'];
			$cache[$groups[$x]['id']]['show_hidden'] 				= 	$groups[$x]['show_hidden'];
			$cache[$groups[$x]['id']]['hide_allow'] 				= 	$groups[$x]['hide_allow'];
			$cache[$groups[$x]['id']]['view_usernamestyle'] 		= 	$groups[$x]['view_usernamestyle'];
			$cache[$groups[$x]['id']]['usertitle_change'] 		= 	$groups[$x]['usertitle_change'];
			$cache[$groups[$x]['id']]['onlinepage_allow'] 		= 	$groups[$x]['onlinepage_allow'];
			$cache[$groups[$x]['id']]['allow_see_offstyles'] 		= 	$groups[$x]['allow_see_offstyles'];
			$cache[$groups[$x]['id']]['admincp_section'] 			= 	$groups[$x]['admincp_section'];
			$cache[$groups[$x]['id']]['admincp_option'] 			= 	$groups[$x]['admincp_option'];
			$cache[$groups[$x]['id']]['admincp_member'] 			= 	$groups[$x]['admincp_member'];
			$cache[$groups[$x]['id']]['admincp_membergroup'] 		= 	$groups[$x]['admincp_membergroup'];
			$cache[$groups[$x]['id']]['admincp_membertitle'] 		= 	$groups[$x]['admincp_membertitle'];
			$cache[$groups[$x]['id']]['admincp_admin'] 			= 	$groups[$x]['admincp_admin'];
			$cache[$groups[$x]['id']]['admincp_adminstep'] 		= 	$groups[$x]['admincp_adminstep'];
			$cache[$groups[$x]['id']]['admincp_subject'] 			= 	$groups[$x]['admincp_subject'];
			$cache[$groups[$x]['id']]['admincp_database'] 		= 	$groups[$x]['admincp_database'];
			$cache[$groups[$x]['id']]['admincp_fixup'] 			= 	$groups[$x]['admincp_fixup'];
			$cache[$groups[$x]['id']]['admincp_ads'] 				= 	$groups[$x]['admincp_ads'];
			$cache[$groups[$x]['id']]['admincp_template'] 		= 	$groups[$x]['admincp_template'];
			$cache[$groups[$x]['id']]['admincp_adminads'] 		= 	$groups[$x]['admincp_adminads'];
			$cache[$groups[$x]['id']]['admincp_attach'] 			= 	$groups[$x]['admincp_attach'];
			$cache[$groups[$x]['id']]['admincp_page'] 			= 	$groups[$x]['admincp_page'];
			$cache[$groups[$x]['id']]['admincp_block'] 			= 	$groups[$x]['admincp_block'];
			$cache[$groups[$x]['id']]['admincp_style'] 			= 	$groups[$x]['admincp_style'];
			$cache[$groups[$x]['id']]['admincp_toolbox'] 			= 	$groups[$x]['admincp_toolbox'];
			$cache[$groups[$x]['id']]['admincp_smile'] 			= 	$groups[$x]['admincp_smile'];
			$cache[$groups[$x]['id']]['admincp_icon'] 			= 	$groups[$x]['admincp_icon'];
			$cache[$groups[$x]['id']]['admincp_avater'] 			= 	$groups[$x]['admincp_avater'];
			$cache[$groups[$x]['id']]['group_order'] 				= 	$groups[$x]['group_order'];
			$cache[$groups[$x]['id']]['admincp_contactus'] 		= 	$groups[$x]['admincp_contactus'];
			$cache[$groups[$x]['id']]['admincp_chat'] 			= 	$groups[$x]['admincp_chat'];
			$cache[$groups[$x]['id']]['admincp_extrafield'] 	    = 	$groups[$x]['admincp_extrafield'];
			$cache[$groups[$x]['id']]['admincp_lang'] 			= 	$groups[$x]['admincp_lang'];
			$cache[$groups[$x]['id']]['admincp_emailed'] 			= 	$groups[$x]['admincp_emailed'];
			$cache[$groups[$x]['id']]['admincp_warn'] 			= 	$groups[$x]['admincp_warn'];
			$cache[$groups[$x]['id']]['admincp_award'] 			= 	$groups[$x]['admincp_award'];
			$cache[$groups[$x]['id']]['admincp_multi_moderation'] = 	$groups[$x]['admincp_multi_moderation'];
			$cache[$groups[$x]['id']]['no_posts'] 		    	= 	$groups[$x]['no_posts'];
			$cache[$groups[$x]['id']]['send_warning'] 	        = 	$groups[$x]['send_warning'];
			$cache[$groups[$x]['id']]['can_warned'] 		        = 	$groups[$x]['can_warned'];
			$cache[$groups[$x]['id']]['visitormessage'] 		    = 	$groups[$x]['visitormessage'];
	        $cache[$groups[$x]['id']]['see_who_on_topic']         =   $groups[$x]['see_who_on_topic'];
	        $cache[$groups[$x]['id']]['topic_day_number']         =   $groups[$x]['topic_day_number'];
	        $cache[$groups[$x]['id']]['reputation_number']        =   $groups[$x]['reputation_number'];
			$cache[$groups[$x]['id']]['review_subject'] 		    = 	$groups[$x]['review_subject'];
			$cache[$groups[$x]['id']]['review_reply'] 		    = 	$groups[$x]['review_reply'];
			$cache[$groups[$x]['id']]['view_action_edit'] 	    = 	$groups[$x]['view_action_edit'];

            // add permissions group hooks cache
			for ($s = 0; $s < $Hooks_number; $s++)
			{
			$Hooks['add_permissions_group_cache'][$s] = @str_replace("\'","'", $Hooks['add_permissions_group_cache'][$s]);
			@eval($Hooks['add_permissions_group_cache'][$s]);
			}

			$x += 1;
		}

		$cache = base64_encode(json_encode($cache));

		return $cache;
	}


 	function UpdateGroupCache($param)
 	{
    	global $PowerBB;

 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

 		   $cache = $this->CreateGroupCache($param);
            // get main dir
			$file_group_cache = $PowerBB->functions->GetMianDir()."cache/group_cache/group_cache".$param['id'].".php";
            $file_group_cache = str_ireplace("index.php/", '', $file_group_cache);
            $file_group_cache = str_replace("index.php/", '', $file_group_cache);

			$fp = @fopen($file_group_cache,'w');
			if (!$fp)
			{
			return 'ERROR::CAN_NOT_OPEN_THE_FILE';
			}
			$Ds = '$';
			$group = $param['id'];
			$group_cache = "<?php \n".$Ds."group_cache = '".$cache."';\n ?>";

			$fw = @fwrite($fp,$group_cache);
            @fclose($fp);

			if (!$fw)
			{
				$fail = true;
			}

 		return ($fail) ? true : false;
 	}

	function CreateSectionGroupCache($param)
	{
  		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}
 		$cache = array();

 		$GroupArr 						= 	array();
 		$GroupArr['get_from'] 			= 	'db';

 		$GroupArr['order']				=	array();
 		$GroupArr['order']['field']		=	'id';
 		$GroupArr['order']['type']		=	'ASC';

 		$GroupArr['where']				=	array('section_id',$param['id']);

		$groups = $this->GetSectionGroupList($GroupArr);

 		$x	=	0;
 		$n	=	sizeof($groups);

		while ($x < $n)
		{
			$cache[$groups[$x]['group_id']] 					= 	array();
			$cache[$groups[$x]['group_id']]['id'] 				= 	$groups[$x]['id'];
			$cache[$groups[$x]['group_id']]['section_id'] 	    = 	$groups[$x]['section_id'];
			$cache[$groups[$x]['group_id']]['group_id'] 	    = 	$groups[$x]['group_id'];
			$cache[$groups[$x]['group_id']]['view_section'] 	= 	$groups[$x]['view_section'];
			$cache[$groups[$x]['group_id']]['view_subject'] 	= 	$groups[$x]['view_subject'];
			$cache[$groups[$x]['group_id']]['download_attach'] 	= 	$groups[$x]['download_attach'];
			$cache[$groups[$x]['group_id']]['write_subject'] 	= 	$groups[$x]['write_subject'];
			$cache[$groups[$x]['group_id']]['write_reply'] 	    = 	$groups[$x]['write_reply'];
			$cache[$groups[$x]['group_id']]['upload_attach'] 	= 	$groups[$x]['upload_attach'];
			$cache[$groups[$x]['group_id']]['edit_own_subject'] = 	$groups[$x]['edit_own_subject'];
			$cache[$groups[$x]['group_id']]['edit_own_reply'] 	= 	$groups[$x]['edit_own_reply'];
			$cache[$groups[$x]['group_id']]['del_own_subject'] 	= 	$groups[$x]['del_own_subject'];
			$cache[$groups[$x]['group_id']]['del_own_reply'] 	= 	$groups[$x]['del_own_reply'];
			$cache[$groups[$x]['group_id']]['write_poll'] 	    = 	$groups[$x]['write_poll'];
			$cache[$groups[$x]['group_id']]['vote_poll'] 	    = 	$groups[$x]['vote_poll'];
			$cache[$groups[$x]['group_id']]['no_posts'] 	    = 	$groups[$x]['no_posts'];
			$cache[$groups[$x]['group_id']]['main_section'] 	= 	$groups[$x]['main_section'];
            $cache[$groups[$x]['group_id']]['group_name']       = 	$groups[$x]['group_name'];

			$x += 1;
		}

		$cache = base64_encode(json_encode($cache));

		return $cache;
	}

 	function UpdateSectionGroupCache($param)
 	{
 	global $PowerBB;

  		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

 		   $cache = $this->CreateSectionGroupCache($param);

 				 $CacheArr 				= 	array();
 				 $CacheArr['field']			=	array();
 				 $CacheArr['field']['sectiongroup_cache'] 	= 	$cache;
 				 $CacheArr['where'] 		        = 	array('id',$param['id']);
               	$Update_sectiongroup_cache = $this->Engine->records->Update($this->Engine->table['section'],$CacheArr['field'],$CacheArr['where']);


			$file_sectiongroup_cache = $PowerBB->functions->GetMianDir()."cache/sectiongroup_cache/sectiongroup_cache_".$param['id'].".php";
            $file_sectiongroup_cache = str_ireplace("index.php/", '', $file_sectiongroup_cache);
            $file_sectiongroup_cache = str_replace("index.php/", '', $file_sectiongroup_cache);

			$fp = fopen($file_sectiongroup_cache,'w');
			if (!$fp)
			{
			//exit ('ERROR:cannot open the file '.$file_sectiongroup_cache);
			}
			$Ds = '$';
			$Section = $param['id'];
			$sectiongroup_cache = "<?php \n".$Ds."sectiongroup_cache = '".$cache."';\n ?>";

			$fw = fwrite($fp,$sectiongroup_cache);
            fclose($fp);

			if (!$fw)
			{
				$fail = true;
			}

 		return ($fail) ? true : false;
 	}

	function InsertSectionGroup($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$query = $this->Engine->records->Insert($this->Engine->table['section_group'],$param['field']);

		if (isset($param['get_id']))
		{
			$this->id = $this->Engine->DB->sql_insert_id();
		}

		return ($query) ? true : false;
	}

	function DeleteSectionGroup($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['table'] = $this->Engine->table['section_group'];

		$del = $this->Engine->records->Delete($param);

		return ($del) ? true : false;
	}

	function UpdateSectionGroup($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$query = $this->Engine->records->Update($this->Engine->table['section_group'],$param['field'],$param['where']);

		return ($query) ? true : false;
	}

 	function GetSectionGroupList($param)
 	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['select'] 	= 	'*';
 		$param['from'] 		= 	$this->Engine->table['section_group'];

		$rows = $this->Engine->records->GetList($param);
        $this->Engine->DB->sql_free_result($rows);

 		return $rows;
 	}


	function GetSectionGroupInfo($param)
 	{
  		if (!isset($param)
  			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['select'] 	= 	'*';
		$param['from'] 		= 	$this->Engine->table['section_group'];

 		$rows = $this->Engine->records->GetInfo($param);

 		return $rows;
 	}
}

?>