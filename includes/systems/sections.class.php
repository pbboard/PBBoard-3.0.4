<?php

class PowerBBSection
{
	var $id;
	var $PowerBB;

	function __construct($PowerBB)
	{
		$this->Engine = $PowerBB;

	}

	function InsertSection($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$query = $this->Engine->records->Insert($this->Engine->table['section'],$param['field']);

		if (isset($param['get_id']))
		{
			$this->id = $this->Engine->DB->sql_insert_id();
		}

		return ($query) ? true : false;
	}

 	function UpdateSection($param)
 	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$query = $this->Engine->records->Update($this->Engine->table['section'],$param['field'],$param['where']);
        $this->Engine->DB->sql_free_result($query);

		return ($query) ? true : false;
 	}

	function DeleteSection($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['table'] = $this->Engine->table['section'];

		$del = $this->Engine->records->Delete($param);

		return ($del) ? true : false;
	}

	function GetSectionsList($param)
 	{
  		if (!isset($param)
  			or !is_array($param))
 		{
 			$param = array();
 		}

 		$param['select'] 	= 	'*';
 		$param['from'] 		= 	$this->Engine->table['section'];

 		$rows = $this->Engine->records->GetList($param);

		return $rows;
 	}

	function GetSectionInfo($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

 	 	$param['select'] 	= 	'*';
 	 	$param['from'] 		= 	$this->Engine->table['section'];

		$rows = $this->Engine->records->GetInfo($param);
        $this->Engine->DB->sql_free_result($param);

		return $rows;
	}

	function GetSectionNumber($param)
 	{
  		if (!isset($param)
  			or !is_array($param))
 		{
 			$param = array();
 		}

 		$param['select'] 	= 	'*';
 		$param['from']		=	$this->Engine->table['section'];

 		$num = $this->Engine->records->GetNumber($param);

 		return $num;
 	}

 	function CheckPassword($param)
 	{
 		if (empty($param['id'])
 			or empty($param['password']))
 		{
 			trigger_error('ERROR::NEED_PARAMETER -- FROM CheckPassword() -- EMPTY id OR password',E_USER_ERROR);
 		}

 		$param['select'] 				= 	'*';
 		$param['from'] 					= 	$this->Engine->table['section'];
 		$param['where']					=	array();

 		$param['where'][0]				=	array();
 		$param['where'][0]['name'] 		= 	'id';
 		$param['where'][0]['oper'] 		= 	'=';
 		$param['where'][0]['value'] 	= 	$param['id'];

 		$param['where'][1]				=	array();
 		$param['where'][1]['con'] 		= 	'AND';
 		$param['where'][1]['name'] 		= 	'section_password';
 		$param['where'][1]['oper'] 		= 	'=';
 		$param['where'][1]['value'] 	= 	$param['password'];

      	$num = $this->Engine->records->GetNumber($param);

      	return ($num <= 0) ? false : true;
 	}

 	function UpdateLastSubject($param)
 	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$field = array(
		           		'last_writer'		=> 	$param['writer']		,
		           		'last_subject'		=>	$param['title']			,
		           		'last_subjectid'	=>	$param['subject_id']	,
		           		'last_date'			=>	$param['date']		,
		           		'last_time'			=>	$param['last_time']		,
		           		'linkvisitor'		=>	$param['linkvisitor']   ,
		           		'icon'		    	=>	$param['icon']			,
		           		'hide_subject'		=>	$param['hide_subject']	,
		           		'last_reply'		=>	$param['last_reply']    ,
		           		'section_password'	=>	$param['section_password'] ,
		           		'last_berpage_nm'	=>	$param['last_berpage_nm'],
		           		'forum_title_color'	=>	$param['forum_title_color'],
		           	   );

		$query = $this->Engine->records->Update($this->Engine->table['section'],$field,$param['where']);
 		return ($query) ? true : false;
 	}

	function CreateSectionsCache($param)
 	{
 		   global $PowerBB;

 		 if (!isset($param)
 			or !is_array($param))
 		 {
 			$param = array();
 		 }

 		$arr 					= 	array();
 		$arr['get_from'] 		= 	'db';
 		$arr['type'] 			= 	'forum';
		$arr['order']			=	array();
		$arr['order']['field']	=	'sort';
		$arr['order']['type']	=	'ASC';
 		$arr['where'] 			= 	array('parent',$param['parent']);

 		$forums = $this->GetSectionsList($arr);
        if ($forums != false)
 		{
 			$x = 0;
 			$size = sizeof($forums);

 			$cache = array();

 			while ($x < $size)
 			{
 				$cache[$x] 							= 	array();
				$cache[$x]['id'] 					= 	$forums[$x]['id'];
				$cache[$x]['title'] 				= 	$forums[$x]['title'];
				if(!empty($forums[$x]['section_describe']))
				{
				$cache[$x]['section_describe'] 		= 	$forums[$x]['section_describe'];
				}
				$cache[$x]['parent'] 				= 	$forums[$x]['parent'];
				$cache[$x]['sort'] 					= 	$forums[$x]['sort'];
				if(!empty($forums[$x]['section_picture']))
				{
				$cache[$x]['section_picture'] 		= 	$forums[$x]['section_picture'];
				}
				if(!empty($forums[$x]['sectionpicture_type']))
				{
				$cache[$x]['sectionpicture_type'] 	= 	$forums[$x]['sectionpicture_type'];
				}
				if(!empty($forums[$x]['use_section_picture']))
				{
				$cache[$x]['use_section_picture'] 	= 	$forums[$x]['use_section_picture'];
				}
				if(!empty($forums[$x]['linksection']))
				{
				$cache[$x]['linksection'] 			= 	$forums[$x]['linksection'];
				}
				if(!empty($forums[$x]['linkvisitor']))
				{
				$cache[$x]['linkvisitor'] 			= 	$forums[$x]['linkvisitor'];
                }
                if(!empty($forums[$x]['last_writer']))
                {
 				$MemberArr 							= 	array();
 				$MemberArr['get_from'] 				= 	'db';
 				$MemberArr['where'] 					= 	array('username',$forums[$x]['last_writer']);
				$rows = $this->Engine->member->GetMemberInfo($MemberArr);
				$cache[$x]['last_writer_id'] 	    = 	$rows['id'];
				$cache[$x]['avater_path'] 		    = 	$rows['avater_path'];
				$cache[$x]['username_style_cache']  = 	$rows['username_style_cache'];

				$cache[$x]['last_writer'] 			= 	$forums[$x]['last_writer'];
				}
				if(!empty($forums[$x]['last_subject']))
				{
				$cache[$x]['last_subject'] 			= 	$forums[$x]['last_subject'];
				$cache[$x]['last_subjectid'] 		= 	$forums[$x]['last_subjectid'];
				$cache[$x]['last_date'] 			= 	$forums[$x]['last_date'];
				$cache[$x]['last_time'] 			= 	$forums[$x]['last_time'];
				}
				$cache[$x]['subject_num'] 			= 	$forums[$x]['subject_num'];
				$cache[$x]['reply_num'] 			= 	$forums[$x]['reply_num'];

				if(!empty($forums[$x]['moderators']) or $forums[$x]['moderators'] != '[]')
				{
				$cache[$x]['moderators'] 			= 	$forums[$x]['moderators'];
				}
				if(!empty($forums[$x]['icon']))
				{
				$cache[$x]['icon'] 	        		=  	$forums[$x]['icon'];
                }
				$cache[$x]['hide_subject'] 	        =  	$forums[$x]['hide_subject'];
				$cache[$x]['sec_section'] 	        =  	$forums[$x]['sec_section'];
				if(!empty($forums[$x]['section_password']))
                {
				$cache[$x]['section_password'] 	    =  	$forums[$x]['section_password'];
				}
				if(!empty($forums[$x]['last_berpage_nm']))
				{
				$cache[$x]['last_berpage_nm'] 	    =  	$forums[$x]['last_berpage_nm'];
				}
				$cache[$x]['last_reply'] 	        =  	$forums[$x]['last_reply'];
				if(!empty($forums[$x]['forums_cache']))
				{
				$cache[$x]['forums_cache'] 			= 	$forums[$x]['forums_cache'];
				}
				if(!empty($forums[$x]['forum_title_color']))
				{
				$cache[$x]['forum_title_color']     = 	$forums[$x]['forum_title_color'];
                }
				$cache[$x]['review_subject']        = 	$forums[$x]['review_subject'];
				$cache[$x]['replys_review_num']     = 	$forums[$x]['replys_review_num'];
				$cache[$x]['subjects_review_num']   = 	$forums[$x]['subjects_review_num'];
				$cache[$x]['groups'] 				= 	array();

 				$GroupArr 							= 	array();
 				$GroupArr['get_from'] 				= 	'db';
 				$GroupArr['order']					=	array();
 				$GroupArr['order']['field']			=	'id';
 				$GroupArr['order']['type']			=	'ASC';
 				$GroupArr['where'] 					= 	array('section_id',$forums[$x]['id']);

				$groups = $this->Engine->group->GetSectionGroupList($GroupArr);

 				$prefixArr 							= 	array();
 				$prefixArr['get_from'] 				= 	'db';
 				$prefixArr['order']					=	array();
 				$prefixArr['order']['field']			=	'id';
 				$prefixArr['order']['type']			=	'ASC';
 				$prefixArr['where'] 					= 	array('id',$forums[$x]['last_subjectid']);
				$rows = $this->Engine->subject->GetSubjectInfo($prefixArr);
				if(!empty($rows['prefix_subject']))
                {
				$cache[$x]['prefix_subject']   = 	$rows['prefix_subject'];
                }
				foreach ($groups as $group)
				{
					$cache[$x]['groups'][$group['group_id']] 					=	array();
					$cache[$x]['groups'][$group['group_id']]['view_section'] 	= 	$group['view_section'];
					$cache[$x]['groups'][$group['group_id']]['main_section'] 	= 	$group['main_section'];
				}

				$x += 1;
 			}

		  $cache = base64_encode(json_encode($cache));
 		}
 		else
 		{
 			return false;
 		}
        $cache = str_replace("'", '', $cache);
		return $cache;
	}

 	function UpdateSectionsCache($param)
 	{
    	global $PowerBB;

 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}
        $maxsize = '9999999999';
   		$SecArr 			= 	array();
		$SecArr['where'] 	= 	array('id',$param['id']);
		$SectionInfo = $this->GetSectionInfo($SecArr);

          if ($SectionInfo and !$SectionInfo['parent']== "0")
 	       {
              	$cache = $this->CreateSectionsCache($SectionInfo);
                $cache = str_replace("'", '', $cache);

				if ($cache == false)
				 {
				  $cache = '';
				 }
                   $size = strlen($cache);

                   if($size > $maxsize)
                   {
                   	$forums_cache = '';
                   }
                   else
                   {
                   	$forums_cache = $cache;
                   }
					$ForumCacheArr 				= 	array();
					$ForumCacheArr['field']['forums_cache'] 	= 	$forums_cache;
					$ForumCacheArr['where'] 		        = 	array('id',$SectionInfo['parent']);
					$UpdateForumCache = $this->UpdateSection($ForumCacheArr);
                if($this->Engine->_CONF['files_forums_Cache'])
                {
	            // get main dir
				$file_forums_cache = $PowerBB->functions->GetMianDir()."cache/forums_cache/forums_cache_".$SectionInfo['parent'].".php";
                $file_forums_cache = str_ireplace("index.php/", '', $file_forums_cache);
                $file_forums_cache = str_replace("index.php/", '', $file_forums_cache);
				$fp = fopen($file_forums_cache,'w');
				$Ds = '$';
				$parent = $SectionInfo['parent'];
				$forums_cache = "<?php \n".$Ds."forums_cache ='".$cache."';\n ?> ";

				$fw = fwrite($fp,$forums_cache);
		        fclose($fp);

 				if (!$fw)
 				{
 					$fail = true;
 				}
 			  }
           }
           else
           {
              if($param['parent'])
              {
	                $cache = $this->CreateSectionsCache($param);
                    $cache = str_replace("'", '', $cache);

					if ($cache == false)
					{
					$cache = '';
					}
                   $size = strlen($cache);
                   if($size > $maxsize)
                   {
                   	$forums_cache = '';
                   }
                   else
                   {
                   	$forums_cache = $cache;
                   }
					$ForumCacheArr 				= 	array();
					$ForumCacheArr['field']['forums_cache'] 	= 	$forums_cache;
					$ForumCacheArr['where'] 		        = 	array('id',$param['parent']);
					$UpdateForumCache = $this->UpdateSection($ForumCacheArr);
                 if($this->Engine->_CONF['files_forums_Cache'])
                 {
		            // get main dir
					$file_forums_cache = $PowerBB->functions->GetMianDir()."cache/forums_cache/forums_cache_".$param['parent'].".php";
	                $file_forums_cache = str_ireplace("index.php/", '', $file_forums_cache);
	                $file_forums_cache = str_replace("index.php/", '', $file_forums_cache);
					$fp = fopen($file_forums_cache,'w');
					$Ds = '$';
					$parent = $param['parent'];
					$forums_cache = "<?php \n".$Ds."forums_cache ='".$cache."';\n ?> ";

					$fw = fwrite($fp,$forums_cache);
			        fclose($fp);

	 				if (!$fw)
	 				{
	 					$fail = true;
	 				}
                 }
 			  }
           }



 		return ($fail) ? true : false;
 	}

 	function UpdateAllSectionsCache()
 	{
    	global $PowerBB;

 		$Sections = $this->GetSectionsList(null);

 		foreach ($Sections as $Section)
 		{
		 if ($Section['parent'] == '0')
 			{
 				$CacheArr 				= 	array();
 				$CacheArr['parent'] 	= 	$Section['id'];
 				 $Cache = $this->CreateSectionsCache($CacheArr);
		      if ($Cache)
 			  {

				  $file_forums_cache = $PowerBB->functions->GetMianDir()."cache/forums_cache/forums_cache_".$Section['id'].".php";
                  $file_forums_cache = str_ireplace("index.php/", '', $file_forums_cache);
                  $file_forums_cache = str_replace("index.php/", '', $file_forums_cache);

					$fp = fopen($file_forums_cache,'w');
					if (!$fp)
					{
					return 'ERROR::CAN_NOT_OPEN_THE_FILE';
					}
					$Ds = '$';
					$Section = $Section['id'];
				    $forums_cache = "<?php \n".$Ds."forums_cache ='".$cache."';\n ?> ";

					$fw = fwrite($fp,$forums_cache);
                     fclose($fp);
               }
 				if (!$fw)
 				{
 					$fail = true;
 				}

 			}
 		}

 		return ($fail) ? false : true;
 	}

	function IsSection($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['select'] 	= 	'*';
		$param['from'] 		= 	$this->Engine->table['section'];

		$num = $this->Engine->records->GetNumber($param);

		return ($num <= 0) ? false : true;
	}

}

?>
