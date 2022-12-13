<?php
/**
 * PBBoard 303
 * Copyright 2019 PBBoard Group, All Rights Reserved
 * Website: http://www.PBBoard.info
 */
/**
 * Upgrade Script: 3.0.2
 */

function upgrade303_dbchanges()
{
    global $db, $output ,$config, $lang;

    $output->steps = array($lang->upgrade);
    $output->print_header($lang->recheck_server_character, 'check_convert_utf8_1');

	$connect_array = array(
		"hostname" => $config['db']['server'],
		"username" => $config['db']['username'],
		"password" => $config['db']['password'] ,
		"database" => $config['db']['name'],
		"encoding" => $config['db']['encoding']
	);
	// Connect to Database
	define('TABLE_PREFIX', $config['db']['prefix']);
	$connect = $db->connect($connect_array);
	@mysqli_set_charset($connection, $config['db']['name']);
	mysqli_set_charset($connect, "latin1");
      $info_query = $db->query("SELECT * FROM " . $config['db']['prefix'] . "info WHERE var_name='rules'");
      $info_row   = $db->fetch_array($info_query);
      $rules = $info_row['value'];
      if(empty($info_row['value']))
      {      $info_query = $db->query("SELECT * FROM " . $config['db']['prefix'] . "info WHERE var_name='welcome_message_text'");
      $info_row   = $db->fetch_array($info_query);
      $rules = $info_row['value'];
      }
    if(strstr($rules,'ق')
    or strstr($rules,'م')
    or strstr($rules,'ا')
    or strstr($rules,'ر')
    or strstr($rules,'ه'))
    {
    echo "<br /><br />".$lang->req_convert_utf;
	$output->print_footer('upgrade303_convert_utf8');
    }
    else
    {
    echo "<br /><br />".$lang->go_update_section_cache;

	$output->print_footer('upgrade303_update_section_cache');
    }
}

function upgrade303_convert_utf8()
{
    global $db, $output ,$config, $lang;
	$action = "convert_utf8";
	$output->steps = array($lang->upgrade);
    $output->print_header($lang->convert_latin1, 'convert_utf8_1');

    $host = $config['db']['server'];
    $user = $config['db']['username'];
    $pass = $config['db']['password'];
    $db = $config['db']['name'];

    $mysqli = mysqli_connect($host, $user, $pass, $db);

	 // Check connection
	 if (mysqli_connect_errno())
	  {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	  }

  	//change DEFAULT CHARACTER database to character utf8mb4_unicode_ci
    mysqli_query($mysqli,"ALTER DATABASE ".$config['db']['name']." CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

    //show tables

 if ($result = mysqli_query($mysqli,"SHOW TABLES from ".$db.""))
  {
        //print_r($result);
	    while($tableName = mysqli_fetch_row($result))
	    {
	        $table = $tableName[0];

		    if (strstr($table,$config['db']['prefix'])
			or strstr($table,"main_"))
	      	{
				// CONVERT ALL TABLES IN A MYSQL DATABASE TO utf8mb4
				mysqli_query($mysqli,"ALTER TABLE ".$table." CONVERT TO CHARACTER SET utf8mb4");

		        $result2 = mysqli_query($mysqli,"SHOW COLUMNS from ".$table.""); //$result2 = mysqli_query($table, 'SHOW COLUMNS FROM') or die("cannot show columns");
		        if(mysqli_num_rows($result2))
		        {
		            while($row2 = mysqli_fetch_row($result2))
		            {
		                foreach ($row2 as $key=>$value)
		                {
		                    if($value == 'longtext')
			                {
							mysqli_query($mysqli,'UPDATE ' .$table. ' SET
							    '.$row2[0].'=convert(cast(convert('.$row2[0].' using  latin1) as binary) using utf8mb4)
							WHERE 1');
							 }
			                elseif(strstr($value,'varchar'))
			                {
			                if($row2[0] != 'filepath')
			                {
							mysqli_query($mysqli,'UPDATE ' .$table. ' SET
							    '.$row2[0].'=convert(cast(convert('.$row2[0].' using  latin1) as binary) using utf8mb4)
							WHERE 1');
							}
			                }
			                elseif($value == 'mediumtext')
			                {
							mysqli_query($mysqli,'UPDATE ' .$table. ' SET
							    '.$row2[0].'=convert(cast(convert('.$row2[0].' using  latin1) as binary) using utf8mb4)
							WHERE 1');
			                }
		                }
		            }
		        }
	        }
					echo '<br />'. $lang->convert_table.' <b>'.$table.'</b> '. $lang->done.' ..<br />';
	    }

	      @mysqli_free_result($result2);
          @mysqli_free_result($result);
  }


	echo $lang->req_convert_reqcomplete;

	$output->print_footer('upgrade303_update_section_cache');
}

/**
*Update Section Cache ;)
*/
function upgrade303_update_section_cache()
{
	global $db, $output ,$config, $lang, $PowerBB;
	$output->steps = array($lang->upgrade);
	$output->print_header($lang->update_all_sections_cache);
	echo '<br />';
     $SecListquery = $db->query("SELECT * FROM ".$config['db']['prefix']."section");
      while ($Section = $db->fetch_array($SecListquery))
		{
		     echo $lang->update_section_cache." " .$Section['title']." ".$lang->done."<br />";

		   if ($Section['parent'] == 0)
 			{
				$Section_parent 	= 	$Section['id'];
				$CacheArrquery = $db->query("SELECT * FROM ".$config['db']['prefix']."section WHERE parent = '$Section_parent'");
				$CacheArr = $db->fetch_array($CacheArrquery);
				$arrparent = $CacheArr['parent'];
				$arr = $db->query("SELECT * FROM ".$config['db']['prefix']."section WHERE parent = '$arrparent' ORDER by sort ASC");
				$cache = array();
				$x = 0;
	 			while ($forums = $db->fetch_array($arr))
	 			{
                       $cache[$x] 							= 	array();
					$cache[$x]['id'] 					= 	$forums['id'];
					$cache[$x]['title'] 				= 	$forums['title'];
					$cache[$x]['section_describe'] 		= 	$forums['section_describe'];
					$cache[$x]['parent'] 				= 	$forums['parent'];
					$cache[$x]['sort'] 					= 	$forums['sort'];
					$cache[$x]['section_picture'] 		= 	$forums['section_picture'];
					$cache[$x]['sectionpicture_type'] 	= 	$forums['sectionpicture_type'];
					$cache[$x]['use_section_picture'] 	= 	$forums['use_section_picture'];
					$cache[$x]['linksection'] 			= 	$forums['linksection'];
					$cache[$x]['linkvisitor'] 			= 	$forums['linkvisitor'];
					$cache[$x]['last_writer'] 			= 	$forums['last_writer'];

					$last_writer 			= 	$forums['last_writer'];
	                $MemberArr = $db->query("SELECT * FROM ".$config['db']['prefix']."member WHERE username = '$last_writer'");
	                $rows = $db->fetch_array($MemberArr);

					$cache[$x]['last_writer_id'] 	    = 	$rows['id'];
					$cache[$x]['avater_path'] 		    = 	$rows['avater_path'];
					$cache[$x]['username_style_cache']  = 	$rows['username_style_cache'];

					$cache[$x]['last_subject'] 			= 	$forums['last_subject'];
					$cache[$x]['last_subjectid'] 		= 	$forums['last_subjectid'];
					$cache[$x]['last_date'] 			= 	$forums['last_date'];
					$cache[$x]['last_time'] 			= 	$forums['last_time'];
					$cache[$x]['subject_num'] 			= 	$forums['subject_num'];
					$cache[$x]['reply_num'] 			= 	$forums['reply_num'];
					$cache[$x]['moderators'] 			= 	$forums['moderators'];
					$cache[$x]['icon'] 	        		=  	$forums['icon'];
					$cache[$x]['hide_subject'] 	        =  	$forums['hide_subject'];
					$cache[$x]['sec_section'] 	        =  	$forums['sec_section'];
					$cache[$x]['section_password'] 	    =  	$forums['section_password'];
					$cache[$x]['last_berpage_nm'] 	    =  	$forums['last_berpage_nm'];
					$cache[$x]['last_reply'] 	        =  	$forums['last_reply'];
					$cache[$x]['forums_cache'] 			= 	$forums['forums_cache'];
					$cache[$x]['forum_title_color']     = 	$forums['forum_title_color'];
					$cache[$x]['review_subject']        = 	$forums['review_subject'];
					$cache[$x]['replys_review_num']     = 	$forums['replys_review_num'];
					$cache[$x]['subjects_review_num']   = 	$forums['subjects_review_num'];
					$cache[$x]['groups'] 				= 	array();

	 				$section_id 					= 	$forums['id'];
	                $GroupArr = $db->query("SELECT * FROM ".$config['db']['prefix']."sectiongroup WHERE section_id = '$section_id' ORDER by id ASC");

	 				$last_subjectid 					= 	$forums['last_subjectid'];
	                $prefixArr = $db->query("SELECT * FROM ".$config['db']['prefix']."subject WHERE id = '$last_subjectid' ORDER by id ASC");
	                $rows = $db->fetch_array($prefixArr);

					$cache[$x]['prefix_subject']   = 	$rows['prefix_subject'];
				     $cache[$x]['groups'][$group['group_id']] 					=	array();
					while ($group = $db->fetch_array($GroupArr))
					{
						$cache[$x]['groups'][$group['group_id']]['view_section'] 	= 	$group['view_section'];
						$cache[$x]['groups'][$group['group_id']]['main_section'] 	= 	$group['main_section'];
					}
					$x += 1;
	 			}

				$cache = base64_encode(json_encode($cache));

				if ($cache)
				{
					$file_forums_cache = PBB_ROOT."cache/forums_cache/forums_cache_".$Section['id'].".php";
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

 			}

 		}

      echo $lang->finish_upgrade_sections_cache;
	 $output->print_footer('upgrade303_finish_upgrade');

}


function upgrade303_finish_upgrade()
{
 	global $db, $output ,$config, $lang, $PowerBB;
    $output->steps = array($lang->upgrade);
	$output->print_header($lang->done_step_upgraded_success);
	echo $lang->finish_upgrade;
	$time	   = @time();

    $update = $db->query("UPDATE " . $config['db']['prefix'] . "info SET value='3.0.3' WHERE var_name='MySBB_version'");
    $update = $db->query("UPDATE " . $config['db']['prefix'] . "info SET value='".$time."' WHERE var_name='last_time_cache'");
    $update = $db->query("UPDATE " . $config['db']['prefix'] . "info SET value='".$time."' WHERE var_name='last_time_updates'");

	$written = 0;
	if(is_writable('./'))
	{
		$lock = @fopen('./lock', 'w');
		$written = @fwrite($lock, '1');
		@fclose($lock);
		if($written)
		{
			echo $lang->done_step_locked;
		}
	}
	if(!$written)
	{
		echo $lang->done_step_dirdelete;
	}

      $info_query = $db->query("SELECT * FROM " . $config['db']['prefix'] . "info WHERE var_name='title'");
      $info_row   = $db->fetch_array($info_query);
      $title = $info_row['value'];

		$protocol = "http://";
		if((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != "off"))
		{
		$protocol = "https://";
		}

		// Attempt auto-detection
		if($_SERVER['HTTP_HOST'])
		{
		$hostname = $protocol.$_SERVER['HTTP_HOST'];
		}
		elseif($_SERVER['SERVER_NAME'])
		{
		$hostname = $protocol.$_SERVER['SERVER_NAME'];
		}

		$currentlocation = get_current_location('', '', true);
		$noinstall = substr($currentlocation, 0, strrpos($currentlocation, '/install/'));
		$bburl = $hostname.$noinstall;
		$websiteurl = $hostname.'/';

		$file = @fopen(PBB_ROOT."includes/settings.php", "w");

		@fwrite($file, "<?php
/**
 * Website Details
 *  Forum URL
 *  Website URL
 *  Website Name
 */

\$setting['forum_url'] = '{$bburl}';
\$setting['website_url'] = '{$websiteurl}';
\$setting['forum_title'] = '{$title}';
\$setting['website_name'] = '{$title}';");

		@fclose($file);



	$output->print_footer('');

}




