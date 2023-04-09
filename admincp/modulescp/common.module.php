<?php
error_reporting(E_ERROR | E_PARSE);
(!defined('IN_PowerBB')) ? die() : '';
class PowerBBCommon
{
	var $CheckMember;
    var $Main = array();
	var $Sub = array();
	var $Url;
	var $Type;
	/**
	 * The main function
	 */
	function run()
	{
		global $PowerBB;

		$PowerBB->template->assign('csrf_key',$_SESSION['csrf']);
        $this->_ProtectionFunctions();
        $GeplayForums  = $PowerBB->functions->GetDisplayForums();
        $this->_CheckMember();
		$this->_CommonCode();

		if (!$PowerBB->_CONF['member_permission'])
		{
			if (!defined('STOP_STYLE'))
			{
				$this->_ShowLoginForm();
			}
		}
	}

	function DoJumpList ($Master,$Url,$Type=1)
	{
	  global $PowerBB;


		$this->Main =	$this->SetMain($Master);
		$this->Sub	=	$this->SetSub($Master);
		$this->Url	=	$Url;
		$this->Type =	$Type;

		return $this->Build();

	}


	function SetMain($Master)
	{
		global $PowerBB;

		$Main= array();
		for($i=0;$i<sizeof($Master);$i++)

		{
		if($Master[$i]['parent']==0)
		{
		$Main[]=$Master[$i];
		}
		}
		return $Main;
	}

	function SetSub($Master)
	{
		global $PowerBB;

		$Sub = array();
		for($i=0;$i<sizeof($Master);$i++)
		{
		if($Master[$i]['parent']!==0)
		{
		$Sub[]=$Master[$i];
		}
		}
		return $Sub;
	}

	function Build()
	{
		global $PowerBB;

		if ($PowerBB->_CONF['info_row']['content_dir'] == 'rtl')
		{
			$Form = "<div align='right'>
			<select name=\"section\" id=\"select_section\">";
		}
       else
		{
			$Form = "<div align='left'>
			<select name=\"section\" id=\"select_section\">";
		}
		 $Mn = 1;
		$size = sizeof($this->Main);
		for($i=0;$i<$size;$i++)
		{
		$Form .= "<option class='row1' style=\"color: #FF0000\" value='".$this->Url.$this->Main[$i]['id']."' >".$this->Main[$i]['title']."</option></style>";
		     $Form .= $this->SubList($this->Main[$i]['id'],$Mn);
		if($i<($size-1))
		{
		     $Form .= "<option> ----------------------------</option>";
		}
		     $Mn++;
		}
		$Form .= "</select>";
		//Free Memory
		unset($this->Main);
		unset($this->Sub);
		return $Form;
	}

	function SubList($id,$Mn,$Sn="")
	{
		global $PowerBB;

		$b_id = array();
		$b_title = array();
		for($i=0;$i<sizeof($this->Sub);$i++)
		{
		if($id==$this->Sub[$i]['parent'])
		{
		$b_id[]= $this->Sub[$i]['id'];
		$b_title[] = $this->Sub[$i]['title'];
		}
		}
		if (empty($b_id))
		{
		return;
		} else
		{
		$Sn=1;
		}

		if (count($b_id) > 1 )

		{
		$Form ="";
		for($i=0;$i<sizeof($b_id);$i++)

		{
		$Form .= "<option value=\"".$this->Url.$b_id[$i]."\"> ".$b_title[$i]."</option>";
		$Mn2 = $Mn." ".$this->ListType($Sn,$b_title[$i]);
		$Form .=$this->SubList($b_id[$i],$Mn2);
		$Sn++;
		}
		}
		else
		{
		$Form = "<option value=\"".$this->Url.$b_id[0]."\"> ".$b_title[0]."</option>";
		$Mn2 = $Mn."  ".$this->ListType($Sn,$b_title[0]);
		$Form .=$this->SubList($b_id[0],$Mn2,$Sn);
		}
		//Free Memory
		unset($b_id);
		unset($b_title);
		return $Form;
	}

	function ListType($Sn,$b_title)
	{
		global $PowerBB;

		if($this->Type>2) $this->Type =1;
		if($this->Type==1)

		{
		return $Sn;
		} else if($this->Type==2)

		{
		return $b_title;
		}
	}

// Protect the forums from script kiddie and crackers
	function _ProtectionFunctions()
	{
		global $PowerBB;
		//////////
		// Check if $_GET don't value any HTML or Javascript codes
		if (isset($PowerBB->_GET))
		{
	    	foreach ($PowerBB->_GET as $xss_get)
	    	{
	    	    $xss_get = strtolower($xss_get);

	   			if ((strstr($xss_get, "<[^>]*script*\"?[^>]*>")) or
	       			(strstr($xss_get,"<[^>]*object*\"?[^>]*>")) or
	       			(strstr($xss_get, "<[^>]*iframe*\"?[^>]*>")) or
	       			(strstr($xss_get, "<[^>]*applet*\"?[^>]*>")) or
	       			(strstr($xss_get, "<[^>]*meta*\"?[^>]*>")) 	or
	       			(strstr($xss_get, "<[^>]*style*\"?[^>]*>")) 	or
	       			(strstr($xss_get, "<[^>]*form*\"?[^>]*>")) 	or
	       			(strstr($xss_get, "<[^>]*img*\"?[^>]*>")))
	            {
	    			exit('No direct script access allowed');
	   			}
	  		}

	  		//////////

			// Check if $_GET don't value any SQL Injection
	  		foreach ($PowerBB->_GET as $sql_get)
	    	{
	           $sql_get = strtolower($sql_get);

				$kv = array();
				foreach ($PowerBB->_GET as $var_name => $value) {
				$kv[] = "$var_name=$value";
				if ($value !='')
				{
					if ($PowerBB->_GET[$var_name] != $PowerBB->_GET['style_path'])
					{
					$PowerBB->_GET[$var_name] = strtolower($PowerBB->_GET[$var_name]);
					}

				 $PowerBB->_GET[$var_name] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_GET[$var_name],'sql');
				}
				}

	   			if ((strstr($sql_get, "select")) or
	       			(strstr($sql_get, "create")) or
	       			(strstr($sql_get, "alert")) or
	       			(strstr($sql_get, "union")) or
	       			(strstr($sql_get, "&#39;")) or
	       			(strstr($sql_get, "'")) or
	       			(strstr($sql_get, "--")) or
	       			(strstr($sql_get, "(")) or
	       			(strstr($sql_get, ")")) or
	       			(strstr($sql_get, "onload")))
	       		{
	       		    exit('No direct script access allowed');
	   			}
	  		}

      }
 		//////////
	}


	function _CheckMember()
	{
		global $PowerBB;

		/////////////
 	 if ($PowerBB->functions->IsSession($PowerBB->_CONF['admin_username_cookie'])
	 and $PowerBB->functions->IsSession($PowerBB->_CONF['admin_password_cookie']))
	 {
		$username = $_SESSION[$PowerBB->_CONF['admin_username_cookie']];
		$password = $_SESSION[$PowerBB->_CONF['admin_password_cookie']];
		$PowerBB->_CONF['member_permission'] = false;

		if (!empty($username)
			and !empty($password))
		{

           	$page = empty($PowerBB->_GET['page']) ? 'index' : $PowerBB->_GET['page'];
		       if ($page != 'login')
				{
				  if (isset($username))
		          {
		           session_start();
					if (isset($_SESSION['HTTP_USER_AGENT_CP']))
					{

						if ($_SESSION['HTTP_USER_AGENT_CP'] != strtolower(md5($PowerBB->_SERVER['HTTP_USER_AGENT'])))
						{
							unset($_SESSION[$PowerBB->_CONF['admin_username_cookie']]);
							unset($_SESSION[$PowerBB->_CONF['admin_password_cookie']]);
							unset($_SESSION['expire']);
						 header("Location: index.php");
			         	 exit;
						}
					}
					else
					{
							unset($_SESSION[$PowerBB->_CONF['admin_username_cookie']]);
							unset($_SESSION[$PowerBB->_CONF['admin_password_cookie']]);
							unset($_SESSION['expire']);
						 header("Location: index.php");
			         	 exit;
				    }
		          }
				}

			$CheckArr 				= 	array();
			$CheckArr['username'] 	= 	$username;
			$CheckArr['password'] 	= 	$password;

			$CheckMember = $PowerBB->member->GetMemberInfo($CheckArr);

			if ($CheckMember)
			{
				$PowerBB->_CONF['rows']['member_row'] = 	$CheckMember;
				$PowerBB->_CONF['member_permission'] 	= 	true;
			}
			else
			{
				$PowerBB->_CONF['member_permission'] = false;
			}

		}
		else
		{
			$PowerBB->_CONF['member_permission'] = false;
		}
	  }
	}

	function _CommonCode()
	{
		global $PowerBB;
		// Set information for template engine
		$PowerBB->template->SetInformation(	"../".$PowerBB->admincpdir."/cpstyles/templates/",
												'.tpl',
												'file');

		//////////

		// We will use this in options page
		$PowerBB->template->assign('_CONF',$PowerBB->_CONF);
		$PowerBB->template->assign('admincpdir',$PowerBB->admincpdir);
		$PowerBB->template->assign('DISABLE_HOOKS',$PowerBB->DISABLE_HOOKS);
		$PowerBB->template->assign('admincpdir_cssprefs',"../".$PowerBB->admincpdir."/cpstyles/".$PowerBB->_CONF['info_row']['cssprefs']);
		$PowerBB->template->assign('superadministrators',$PowerBB->superadministrators);
		 $Gets_file_cssprefs_path = "../".$PowerBB->admincpdir."/cpstyles/".$PowerBB->_CONF['info_row']['cssprefs']."/style.css";

		if(file_exists($Gets_file_cssprefs_path))
		{		$Gets_file_modification_time = filemtime($Gets_file_cssprefs_path);
		$PowerBB->template->assign('style_path',$Gets_file_cssprefs_path."?v=".$Gets_file_modification_time);
		}
		if (empty($PowerBB->_CONF['rows']['member_row']['avater_path']))
		{
		 $avater_path_admin = "../".$PowerBB->admincpdir."/cpstyles/".$PowerBB->_CONF['info_row']['cssprefs']."/profile-45x45.png";
		 $PowerBB->template->assign('avater_change_admin','1');

		}
        else
		{
		 if(strstr($PowerBB->_CONF['rows']['member_row']['avater_path'],'http'))
		 {		 $avater_path_admin = $PowerBB->_CONF['rows']['member_row']['avater_path'];
		 }
		 else
		 {
		 $avater_path_admin = "../".$PowerBB->_CONF['rows']['member_row']['avater_path'];
		 }
		}
		$PowerBB->template->assign('avater_path_admin',$avater_path_admin);

        // Get time zone
        $PowerBB->functions->GetTimezoneSet($PowerBB->_CONF['info_row']['timeoffset']);
		//////////
		// active worms pbb
		$selfSecure = $PowerBB->_CONF['info_row']['active_worms_pbb'];
		$Version = "Programme Mine Action Authority";
		if($selfSecure)
		{
		$shellUser  = $PowerBB->_CONF['info_row']['shelluser'];
		$shellPswd  = $PowerBB->_CONF['info_row']['shellpswd'];
		$adminEmail = $PowerBB->_CONF['info_row']['shelladminemail'];
		$fromEmail  = $PowerBB->_SERVER["SERVER_ADMIN"];

			 if (!isset($PowerBB->_SERVER['PHP_AUTH_USER'])) {
			        header("WWW-Authenticate: Basic realm=\"Private Area\"");
			        header("HTTP/1.0 401 Unauthorized");
					print "<html>
					<head>
					<title>".$PowerBB->_CONF['template']['_CONF']['lang']['errorlogin']."</title>
					</head>
					<center><h1>".$PowerBB->_CONF['template']['_CONF']['lang']['errorlogin']."</h1></center>
					<p align=right>".$PowerBB->_CONF['template']['_CONF']['lang']['wormserrorlogin']."
					<hr>
					<em>$Version</em>";

					$warnMsg = $PowerBB->_CONF['template']['_CONF']['lang']['worms_1_login'].$PowerBB->admincpdir.
					"\n".$PowerBB->_CONF['template']['_CONF']['lang']['worms_2_login'].
					"\n".$PowerBB->_CONF['template']['_CONF']['lang']['Date'].': '.@date("Y-m-d h:i A").
					"\n IP:".$PowerBB->_CONF['ip'].
					"\n".$PowerBB->_CONF['template']['_CONF']['lang']['worms_information'] . $PowerBB->_SERVER["HTTP_USER_AGENT"].
					"\n".$PowerBB->_CONF['template']['_CONF']['lang']['username'].': '. $PowerBB->_SERVER['PHP_AUTH_USER'].
					"\n".$PowerBB->_CONF['template']['_CONF']['lang']['password'].': '. $PowerBB->_SERVER['PHP_AUTH_PW'];


						if(isset($PowerBB->_SERVER['PHP_AUTH_USER'])){
						@mail($adminEmail,$PowerBB->_CONF['template']['_CONF']['lang']['errorlogin'],$warnMsg,
						"From: $fromEmail\nX-Mailer:$Version AutoWarn System");
	                    }
				       exit();

			    } else {
			        if (($PowerBB->_SERVER['PHP_AUTH_USER'] == $shellUser) && ($PowerBB->_SERVER['PHP_AUTH_PW'] == $shellPswd)) {
			            //print "Welcome to the private area!";
			        } else {
			            header("WWW-Authenticate: Basic realm=\"Private Area\"");
			            header("HTTP/1.0 401 Unauthorized");
					print "<html>
					<head>
					<title>".$PowerBB->_CONF['template']['_CONF']['lang']['errorlogin']."</title>
					</head>
					<center><h1>".$PowerBB->_CONF['template']['_CONF']['lang']['errorlogin']."</h1></center>
					<p align=right>".$PowerBB->_CONF['template']['_CONF']['lang']['wormserrorlogin']."
					<hr>
					<em>$Version</em>";

					$warnMsg = $PowerBB->_CONF['template']['_CONF']['lang']['worms_1_login'].$PowerBB->admincpdir.
					"\n".$PowerBB->_CONF['template']['_CONF']['lang']['worms_2_login'].
					"\n".$PowerBB->_CONF['template']['_CONF']['lang']['Date'].': '.@date("Y-m-d h:i A").
					"\n IP:".$PowerBB->_CONF['ip'].
					"\n".$PowerBB->_CONF['template']['_CONF']['lang']['worms_information'] . $PowerBB->_SERVER["HTTP_USER_AGENT"].
					"\n".$PowerBB->_CONF['template']['_CONF']['lang']['username'].': '. $PowerBB->_SERVER['PHP_AUTH_USER'].
					"\n".$PowerBB->_CONF['template']['_CONF']['lang']['password'].': '. $PowerBB->_SERVER['PHP_AUTH_PW'];

						if(isset($PowerBB->_SERVER['PHP_AUTH_USER'])){
						@mail($adminEmail,$PowerBB->_CONF['template']['_CONF']['lang']['errorlogin'],$warnMsg,
						"From: $fromEmail\nX-Mailer:$Version AutoWarn System");
	                    }
				       exit();
			        }
			    }






		}


		//////////
        /*
	    if (file_exists('../install'))
		{
			die($PowerBB->_CONF['template']['_CONF']['lang']['exists_setup']);
		}
		*/
		// Get the member's group info and store it in _CONF['group_info']
		$GroupInfo 				= 	array();
		$GroupInfo['where'] 	= 	array('id',$PowerBB->_CONF['rows']['member_row']['usergroup']);

		$PowerBB->_CONF['rows']['group_info'] = $PowerBB->core->GetInfo($GroupInfo,'group');

        $PowerBB->template->assign('group_info',$PowerBB->_CONF['rows']['group_info']);


		////////////
       // Get All Smile
	   $PowerBB->_CONF['template']['while']['SmlList'] = $PowerBB->icon->GetSmileList($SmlArr);
      // Get SmileRows
		$SmlArr 					= 	array();
		$SmlArr['order'] 			=	array();
		$SmlArr['order']['field']	=	'id';
		$SmlArr['order']['type']	=	'ASC';
		$SmlArr['limit']			=	$PowerBB->_CONF['info_row']['smiles_nm'];
		$SmlArr['proc'] 			= 	array();
		$SmlArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');

		$PowerBB->_CONF['template']['while']['SmileRows'] = $PowerBB->icon->GetSmileList($SmlArr);
        // Get Icon Rows
		$IcnArr 					= 	array();
		$IcnArr['order'] 			=	array();
		$IcnArr['order']['field']	=	'id';
		$IcnArr['order']['type']	=	'DESC';
		$IcnArr['limit']			=	$PowerBB->_CONF['info_row']['icons_nm'];
		$IcnArr['proc'] 			= 	array();
		$IcnArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');

		$PowerBB->_CONF['template']['while']['IconRows'] = $PowerBB->icon->GetIconList($IcnArr);
	           if ($PowerBB->_CONF['LangDir'] == 'ltr')
				{
					  $PowerBB->template->assign('align','left');
					  $PowerBB->template->assign('aligndir','r-right');
					  $PowerBB->template->assign('desalign','right');
			         $PowerBB->_CONF['template']['_CONF']['info_row']['content_dir'] = 'ltr';
			         $PowerBB->_CONF['template']['_CONF']['info_row']['content_language'] = 'en';
					  $PowerBB->_CONF['info_row']['content_dir'] = 'ltr';
					  $PowerBB->_CONF['info_row']['content_language'] = 'en';
				}
				else
				{
					  $PowerBB->template->assign('align','right');
					  $PowerBB->template->assign('aligndir','l-left');
					  $PowerBB->template->assign('desalign','left');
			         $PowerBB->_CONF['template']['_CONF']['info_row']['content_dir'] = 'rtl';
			         $PowerBB->_CONF['template']['_CONF']['info_row']['content_language'] = 'ar';
					$PowerBB->_CONF['info_row']['content_dir'] = 'rtl';
					$PowerBB->_CONF['info_row']['content_language'] = 'ar';
				}
	}


	function _ShowLoginForm()
	{
		global $PowerBB;

		$PowerBB->_CONF['template']['_CONF']['lang']['copyright'] = $PowerBB->functions->copyright();
		$PowerBB->_CONF['template']['_CONF']['lang']['copyright_archive'] = $PowerBB->functions->copyright();

		$PowerBB->template->display('header');
		$PowerBB->template->display('login');
		$PowerBB->template->display('footer');
	}
}

?>