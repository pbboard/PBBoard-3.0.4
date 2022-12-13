<?php
(!defined('IN_PowerBB')) ? die() : '';
define('IN_ADMIN',true);
$CALL_SYSTEM				=	array();
$CALL_SYSTEM['GROUP'] 		    = 	true;
$CALL_SYSTEM['MODERATORS'] 	= 	true;


define('CLASS_NAME','PowerBBBackupMOD');

include('../common.php');
class PowerBBBackupMOD
{
 var $Info;
	function run()
	{
		global $PowerBB;

		if ($PowerBB->_CONF['member_permission'])
		{

			if ($PowerBB->_CONF['rows']['group_info']['admincp_member'] == '0')
			{
			  $PowerBB->template->display('header');
			  $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
			}
			if (!in_array($PowerBB->_CONF['rows']['member_row']['id'], preg_split('#\s*,\s*#s', $PowerBB->superadministrators, -1, PREG_SPLIT_NO_EMPTY)))
			{
			  $PowerBB->template->display('header');
			 $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
			}

			if ($PowerBB->_GET['backup'])
			{
				if ($PowerBB->_GET['main'])
				{
			       $PowerBB->template->display('header');
					$this->_BackupMain();
				}
				elseif ($PowerBB->_GET['start'])
				{
					$this->_BackupStart();
				}
			}

			$PowerBB->template->display('footer');
        }
	}



	function _BackupMain()
	{
		global $PowerBB;
		$PowerBB->template->assign('filename',$PowerBB->admincpdir."/".'backup-'. gmdate('d-m-y').'.sql.gz');
    	$PowerBB->template->display('backup');
	}


	function _BackupStart()
	{
		global $PowerBB;

        /*
		$tables = $PowerBB->_POST['check'];
		$outta = "";
		foreach($tables as $table)
		{
			$query = mysql_query("SHOW CREATE TABLE ". $table);
			$que = mysql_fetch_array($query);
			$outta .= $que['Create Table'] . "\r\n\n";
			$query2 = mysql_query("SELECT * FROM `$que[Table]`");
			while($result = mysql_fetch_array($query2))
			{
				while($res = current($result))
				{
					$fields[] .= "`".key($result)."`";
					$values[] .= "'$res'";
					next($result);
				}
				$fields = join(", ", $fields);
				$values = join(", ", $values);
				$q = "INSERT INTO `$que[Table]` ($fields) VALUES ($values);";
				$outta .= $q . "\r\n\n";
				unset($fields);
				unset($values);
			}
		}
                $outta = str_replace("CHARSET=utf8", "CHARSET=utf8 AUTO_INCREMENT=1 ; \n\n", $outta);
                $outta = str_replace("AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1", "DEFAULT CHARSET=utf8 AUTO_INCREMENT=2", $outta);
                $outta = str_replace("AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1", "DEFAULT CHARSET=utf8 AUTO_INCREMENT=19", $outta);
                $outta = str_replace("AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1", "DEFAULT CHARSET=utf8 AUTO_INCREMENT=12", $outta);
                $outta = str_replace("AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1", "DEFAULT CHARSET=utf8 AUTO_INCREMENT=6", $outta);
                $outta = str_replace("AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1", "DEFAULT CHARSET=utf8 AUTO_INCREMENT=9", $outta);
                $outta = str_replace("AUTO_INCREMENT=36 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1", "DEFAULT CHARSET=utf8 AUTO_INCREMENT=36", $outta);
                $outta = str_replace("AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1", "DEFAULT CHARSET=utf8 AUTO_INCREMENT=17", $outta);
                $outta = str_replace("AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1", "DEFAULT CHARSET=utf8 AUTO_INCREMENT=3", $outta);
                $outta = str_replace("AUTO_INCREMENT=169 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1", "DEFAULT CHARSET=utf8 AUTO_INCREMENT=169", $outta);
                $outta = str_replace("AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1", "DEFAULT CHARSET=utf8 AUTO_INCREMENT=7", $outta);
                $outta = str_replace("AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1", "DEFAULT CHARSET=utf8 AUTO_INCREMENT=9", $outta);
                $outta = str_replace("AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1", "DEFAULT CHARSET=utf8 AUTO_INCREMENT=9", $outta);
                $outta = str_replace("AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1", "DEFAULT CHARSET=utf8 AUTO_INCREMENT=9", $outta);
                $outta = str_replace("AUTO_INCREMENT=29 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1", "DEFAULT CHARSET=utf8 AUTO_INCREMENT=29", $outta);
              */

				        require("../includes/config.php");
						require_once("../includes/iam_backup.php");
						$filename = $PowerBB->_POST['filename'];
                       $backup = new iam_backup($config['db']['server'], $config['db']['name'], $config['db']['username'], $config['db']['password'], true, false, true, $filename);
                       $backup->perform_backup();
						$GetForumAdress = $PowerBB->functions->GetForumAdress();
				        $GetForumAdress = str_replace($PowerBB->admincpdir."/", '', $GetForumAdress);
                        $Adressbackup = $GetForumAdress.$filename;
                       	$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['msg_backup1'].' <br />'.$Adressbackup.'<br /><a href="index.php?page=backup&amp;backup=1&amp;main=1"><b>'.$PowerBB->_CONF['template']['_CONF']['lang']['Return'].'</b></a>');
                       	$linkfilename	= 	'<b><a href="'.$Adressbackup.'">'.$PowerBB->_CONF['template']['_CONF']['lang']['msg_backup2'].'</a></b>';
			            $PowerBB->template->display('header');
                       	$PowerBB->functions->msg($linkfilename);


	}


}

?>
