<?php

class PowerBBMember
{
	var $id;
	var $PowerBB;

	function __construct($PowerBB)
	{
		$this->Engine = $PowerBB;
	}

	/**
	 * Insert new member in database
	 *
	 * @access : public
	 * @return :
	 *				false			->	if the function can't add the member
	 *				true			->	if the function success to add member
	 *
	 * param :
	 *			username -> the username
	 *			password -> of course the password :)
	 *			email	 -> the email :\
	 *			usergroup
	 *			user_gender
	 *			register_date
	 *			user_title
	 *			style
	 */
	function InsertMember($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$query = $this->Engine->records->Insert($this->Engine->table['member'],$param['field']);

		if (isset($param['get_id']))
		{
			$this->id = $this->Engine->DB->sql_insert_id();
		}

		return ($query) ? true : false;
	}

	/**
	 * Get members list
	 *
	 * param :
	 *				sql_statment -> complete the sql query
	 */
	function GetMemberList($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['select'] 	= 	'*';
 		$param['from'] 		= 	$this->Engine->table['member'];

		$rows = $this->Engine->records->GetList($param);

		return $rows;
	}

	/**
	 * Get member information
	 *
	 * param :
	 *			get	->	the list of fields
	 */
	function GetMemberInfo($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['select'] 	= 	(!empty($param['get'])) ? $param['get'] : '*';
		$param['from'] 		= 	$this->Engine->table['member'];

		$rows = $this->Engine->records->GetInfo($param);
		return $rows;
	}

	/**
	 * Get the total of members
	 *
	 * param :
	 *			get_from	->	cache or db
	 */
	function GetMemberNumber($param)
	{
		if ($param['get_from'] == 'cache')
		{
			$num = $this->Engine->_CONF['info_row']['member_number'];
		}
		elseif ($param['get_from'] == 'db')
		{
			$param['select'] 	= 	'*';
			$param['from'] 		= 	$this->Engine->table['member'];

			$num = $this->Engine->records->GetNumber($param);
		}
		else
		{
			trigger_error('ERROR::BAD_VALUE_OF_GET_FROM_VARIABLE -- FROM GetMemberNumber() -- get_from SHOULD BE cache OR db',E_USER_ERROR);
		}

		return $num;
	}

	function UpdateMember($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$query = $this->Engine->records->Update($this->Engine->table['member'],$param['field'],$param['where']);

		return ($query) ? true : false;
	}

	function DeleteMember($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['table'] = $this->Engine->table['member'];

		$del = $this->Engine->records->Delete($param);

		return ($del) ? true : false;
	}

	///

	/**
	 * Check if the member exists in database or not
	 *
	 * param :
	 *				way	->
	 						id
	 						username
	 						email
	 *
	 * @return :
	 *				false 	-> if isn't member
	 *				true	-> if is member
	 */
	function IsMember($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['select'] 	= 	'*';
		$param['from'] 		= 	$this->Engine->table['member'];

		$num = $this->Engine->records->GetNumber($param);

		return ($num <= 0) ? false : true;
	}

	/**
	 * Check if user is exists and set cookie to log in
	 *
	 * param :
	 *			username -> the usename
	 *			password -> the password with md5
	 */
	function LoginMember($param)
	{
		if (empty($param['username'])
			or empty($param['password']))
		{
			trigger_error('ERROR::NEED_PARAMETER -- FROM LoginMember() -- EMPTY username or password',E_USER_ERROR);
		}

        $sessionLogin = false;

     	$MemberArr['get'] = '*';

		if (!empty($param['username'])
			and !empty($param['password']))
		{
			$MemberArr['where'] 				= 	array();
			$MemberArr['where'][0] 				= 	array();
			$MemberArr['where'][0]['name'] 		= 	'username';
			$MemberArr['where'][0]['oper'] 		= 	'=';
			$MemberArr['where'][0]['value'] 	= 	$param['username'];

			$MemberArr['where'][1] 				= 	array();
			$MemberArr['where'][1]['con'] 		= 	'AND';
			$MemberArr['where'][1]['name'] 		= 	'password';
			$MemberArr['where'][1]['oper'] 		= 	'=';
			$MemberArr['where'][1]['value'] 	= 	$param['password'];
		}

		$CheckMember = $this->GetMemberInfo($MemberArr);


		if ($CheckMember)
		{
            if($sessionLogin)
            {
    		$_SESSION[$this->Engine->_CONF['username_cookie']] = $param['username'];
    		$_SESSION[$this->Engine->_CONF['password_cookie']] = $param['password'];
    		$_SESSION['expire'] = $param['expire'];
    		}
    		else
    		{

			$options 			 = 	array();
			$options['expires']	 =	$param['expire'];
			//$options['path'] 	 = 	'/';
			//$options['domain']   = 	$this->Engine->_SERVER["HTTP_HOST"];
			//$options['secure'] 	 = 	true;
			//$options['httponly'] = 	true;
			//$options['samesite'] = 	'lax';
			$this->Engine->functions->pbb_set_cookie($this->Engine->_CONF['username_cookie'],$param['username'],$options);
			$this->Engine->functions->pbb_set_cookie($this->Engine->_CONF['password_cookie'],$param['password'],$options);
    		}
    		 @session_start();
            $_SESSION['HTTP_USER_AGENT'] = strtolower(md5($this->Engine->_SERVER['HTTP_USER_AGENT']));

       		return true;
		}
		else
		{
         return false;
       	}
	}

	/**
	 * Check if the member information is correct
	 *
	 * param :
	 *			username -> the username
	 *			password -> the password
	 *			object	 -> if this function used in system file we sould identify the system object
	 */
	function CheckMember($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$MemberArr['get'] = '*';

		if (!empty($param['username']))
		{
			$MemberArr['where'] 				= 	array();
			$MemberArr['where'][0] 				= 	array();
			$MemberArr['where'][0]['name'] 		= 	'username';
			$MemberArr['where'][0]['oper'] 		= 	'=';
			$MemberArr['where'][0]['value'] 	= 	$param['username'];

		}

		$CheckMemberUsername = $this->GetMemberInfo($MemberArr);

        $salt = $CheckMemberUsername['active_number'];
        $password_verify = $this->Engine->functions->verify_user_password($salt,$param['password']);

		if($CheckMemberUsername and !empty($param['password']))
		{			if(md5($param['password']) == $CheckMemberUsername['password']
			or empty($CheckMemberUsername['active_number']))
			{
          	$UpdatePassword = $this->Engine->functions->update_password($CheckMemberUsername['id'], $param['password']);

			 $CheckMember = $UpdatePassword;
			}
			elseif($password_verify['password'] == $CheckMemberUsername['password'])
			{
             $CheckMember = true;

			}
			else
			{
			$CheckMember = false;
			}
        }
		else
		{
		$CheckMember = false;
		}

		return $CheckMember;
	}

	/**
	 * Member logout
	 *
	 */
	function Logout()
	{
        session_start();
    	$_SESSION['expire'] = '';
		$_SESSION[$this->Engine->_CONF['username_cookie']] = '';
     	$_SESSION[$this->Engine->_CONF['password_cookie']] = '';

		 $this->Engine->functions->pbb_set_cookie($this->Engine->_CONF['username_cookie'],'');
		 $this->Engine->functions->pbb_set_cookie($this->Engine->_CONF['password_cookie'],'');

     	session_destroy();
     	return true;
	}


	/**
	 * Get username with group style
	 *
	 * param :
	 *				username	->	the name of user
	 *				group_style	->	the style of gorup
	 */
	function GetUsernameByStyle($param)
	{
		// These codes from the first generation of PowerBB
		// Do you remember it ? :)

 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		if (empty($param['group_style'])
			or empty($param['username']))
		{
			trigger_error('ERROR::NEED_PARAMATER -- FROM GetUsernameByStyle() -- EMPTY group_style or username',E_USER_ERROR);
		}

		$general_style = $param['group_style'];
		$general_style = explode('[username]',$general_style);

		$style  = $general_style[0];
		$style .= $this->Engine->sys_functions->CleanVariable($param['username'],'html');
		$style .= $general_style[1];

		return $style;
	}

	/**
	 * Update the last visit date
	 *
	 * param :
	 *			last_visit	->	the date
	 */
	function LastVisitCookie($param)
	{

		// TODO :: store the name of cookie in a variable like username,password cookies.
			$options 			 = 	array();
			$options['expires']	 =	time()+85200;
            $this->Engine->functions->pbb_set_cookie('PowerBB_lastvisit',$param['last_visit'],$options);

		$UpdateArr 					= 	array();

		$UpdateArr['field']					=	array();
		$UpdateArr['field']['lastvisit'] 	= 	$param['date'];

		$UpdateArr['where']			=	array('id',$param['id']);

		$query = $this->UpdateMember($UpdateArr);
	}



	/**
	 * Get the member time
	 */
	 // Probabbly this way is wrong
	function GetMemberTime($param)
	{
		$time   = $this->Engine->_CONF['gmt_hour'] + $param['time'];
     	$time   = $time . $this->Engine->_CONF['gmt_seconds'];

     	return $time;
	}

	/**
	 * Get the number of member who have posts > 0
	 */
	function GetActiveMemberNumber()
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$param['select'] 			= 	'*';
		$param['from'] 				= 	$this->Engine->table['member'];

		$param['where'] 			= 	array();
		$param['where'][0] 			= 	array();
		$param['where'][0]['name'] 	= 	'posts';
		$param['where'][0]['oper'] 	= 	'>';
		$param['where'][0]['value'] = 	'0';

		$num   = $this->Engine->records->GetNumber($param);

		return $num;
	}


	function CleanNewPassword($param)
	{
 		if (!isset($param)
 			or !is_array($param))
 		{
 			$param = array();
 		}

		$UpdateArr 					= 	array();
		$UpdateArr['new_password'] 	= 	'';
		$UpdateArr['where']			=	array('id',$param['id']);

		$query = $this->UpdateMember($UpdateArr);

		return ($query) ? true : false;
	}

	function CheckAdmin($param)
	{
 		if (!isset($param['username'])
 			or !isset($param['password']))
 		{
 			trigger_error('ERROR::NEED_PARAMETER -- FROM CheckAdmin() -- EMPTY username OR password',E_USER_ERROR);
 		}

    	$MemberArr['get'] = '*';

		if (!empty($param['username'])
			and !empty($param['password']))
		{
			$MemberArr['where'] 				= 	array();
			$MemberArr['where'][0] 				= 	array();
			$MemberArr['where'][0]['name'] 		= 	'username';
			$MemberArr['where'][0]['oper'] 		= 	'=';
			$MemberArr['where'][0]['value'] 	= 	$param['username'];

			$MemberArr['where'][1] 				= 	array();
			$MemberArr['where'][1]['con'] 		= 	'AND';
			$MemberArr['where'][1]['name'] 		= 	'password';
			$MemberArr['where'][1]['oper'] 		= 	'=';
			$MemberArr['where'][1]['value'] 	= 	$param['password'];
		}

		$CheckMember = $this->GetMemberInfo($MemberArr);


			$GroupArr 			= 	array();
			$GroupArr['where'] 	= 	array('id',$CheckMember['usergroup']);

			$GroupInfo = $this->Engine->core->GetInfo($GroupArr,'group');

			if ($CheckMember and $GroupInfo['admincp_allow'] and !empty($param['password']))
			{
		      $CheckMember = true;

             }
			else
			{
				$CheckMember = false;
			}

			return $CheckMember;
	}

	function LoginAdmin($param)
	{
 		if (empty($param['username'])
 			or empty($param['password']))
 		{
 			trigger_error('ERROR::NEED_PARAMETER -- FROM LoginAdmin() -- EMPTY username OR password',E_USER_ERROR);
 		}

			$Check = $this->CheckAdmin($param);
		   if ($Check)
		   {
    		@session_start();
    		$_SESSION[$this->Engine->_CONF['admin_username_cookie']] = $param['username'];
    		$_SESSION[$this->Engine->_CONF['admin_password_cookie']] = $param['password'];
    		$_SESSION['admin_expire'] = $param['expire'];
            $_SESSION['HTTP_USER_AGENT_CP'] = strtolower(md5($this->Engine->_SERVER['HTTP_USER_AGENT']));
       		return true;
       		}
			else
			{
				return false;
			}

	}

	/**
			 * Insert new field in the members table , Default type TEXT
			 * param string $name the new field name
			 * @return bool result!
			 */
			function InsertMemberField($_name){
		      ( true==empty($_name) )
		      ? trigger_error('',E_USER_ERROR)
		      : $alterParams=array(
		            'table' => $this->Engine->table['member'],
		            'new_name' => $_name,
		            'def'   => 'TEXT NOT NULL',
		            'type' => 'add'
		          );
		      return $this->Engine->records->Alter($alterParams);
			}

			 /**
		   * Insert new field in the members table
		   * param string $name the new field name
		   * @return bool result!
		   */
		 function DeleteMemberField($_name){
		      ( true==empty($_name) )
		      ? trigger_error('member::DeleteMemberField > empty field name !',E_USER_ERROR)
		      : $alterParams=array(
		            'table' => $this->Engine->table['member'],
		            'name' => $_name,
		            'type' => 'drop'
		          );
		      return $this->Engine->records->Alter($alterParams);
		  }

}

?>