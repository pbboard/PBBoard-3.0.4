<?php
(!defined('IN_PowerBB')) ? die() : '';

define('CLASS_NAME','PowerBBMemberlistMOD');

include('common.php');
class PowerBBMemberlistMOD
{
	function run()
	{
		global $PowerBB;
      $PowerBB->template->assign('member_list_page','primary_tabon');
		$this->_GetJumpSectionsList();
        $PowerBB->functions->ShowHeader();
		if (!$PowerBB->_CONF['group_info']['memberlist_allow'])
		{
          if (!$PowerBB->_CONF['member_permission'])
              {
              $PowerBB->template->display('login');
              $PowerBB->functions->error_stop();
			}
	        else
	        {
	        $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['no_online']);
	        }
	     }

		if ($PowerBB->_GET['index'])
	  {         if ($PowerBB->_GET['sort'] == 'username')
         {
               $this->_GetUsernameListSort();
	    }
         if ($PowerBB->_GET['search_by_username'])
         {
               $this->_SearchByUsername();
	    }

	    if ($PowerBB->_GET['show'])
         {
            	$this->_GetMemberList();
         }
          elseif (($PowerBB->_GET['order'] <= 1) or ($PowerBB->_GET['order'] > 3))
         {

          if ($PowerBB->_GET['order_type'] == 'DESC')
            {
           	   $this->_GetMemberList1();
            }

         }
         elseif ($PowerBB->_GET['order'] == 2)
         {
             if ($PowerBB->_GET['order_type'] == 'DESC')
             {
             	$this->_GetMemberList2();
             }
         }

         elseif ($PowerBB->_GET['order'] == 3)
         {
             if ($PowerBB->_GET['order_type'] == 'DESC')
             {
               $this->_GetMemberList3();
             }

	    }
		else
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}

		$PowerBB->functions->GetFooter();
	  }
}

	    /**
	 * Get the Jump Sections List
	 */
	function _GetJumpSectionsList()
    {
		global $PowerBB;
       $PowerBB->functions->JumpForumsList();
   }


	function _GetMemberList()
	{
		global $PowerBB;

		$PowerBB->_GET['count'] = (!isset($PowerBB->_GET['count'])) ? 0 : $PowerBB->_GET['count'];
       $PowerBB->_GET['count'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['count'],'intval');

       $mn = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(*) FROM " . $PowerBB->table['member'] . ""));

		$ListArr 						= 	array();
		$ListArr['select'] = '
		    m.*,
		    g.id AS group_id,
		    g.user_title AS group_user_title,
		    g.usertitle_change AS group_usertitle_change';

		$ListArr['from'] = $PowerBB->table['member'] . ' AS m';

		// JOINs
		$ListArr['join'] = array(
		    array(
		        'type'  => 'left',
		        'from'  => $PowerBB->table['group'] . ' AS g',
		        'where' => 'm.usergroup = g.id'
		    ),

		);

		// Order data
		$ListArr['order']				=	array();
		$ListArr['order']['field']		=	'm.username';
		$ListArr['order']['type']		=	'ASC';

		$ListArr['limit']		        =	$PowerBB->_CONF['info_row']['perpage'];
		// Clean data from HTML
		$ListArr['proc'] 				= 	array();
		$ListArr['proc']['*'] 			= 	array('method'=>'clean','param'=>'html');

		// Pager setup
		$ListArr['pager'] 				= 	array();
		$ListArr['pager']['total']		= 	$mn;
		$ListArr['pager']['perpage'] 	= 	$PowerBB->_CONF['info_row']['perpage'];
		$ListArr['pager']['count'] 		= 	$PowerBB->_GET['count'];
		$ListArr['pager']['location'] 	= 	'index.php?page=member_list&amp;index=1&amp;show=1';
		$ListArr['pager']['var'] 		= 	'count';

		$GetMemberList = $PowerBB->member->GetMemberListAdvanced($ListArr);

		$PowerBB->_CONF['template']['while']['MemberList'] = $GetMemberList;

		if ($mn > $PowerBB->_CONF['info_row']['perpage'])
		{
		$PowerBB->template->assign('pager',$PowerBB->pager->show());
		}



		$PowerBB->template->display('show_memberlist');

	}

	function _GetMemberList1()
	{
		global $PowerBB;

       $mn = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(*) FROM " . $PowerBB->table['member'] . ""));


		$PowerBB->_GET['count'] = (!isset($PowerBB->_GET['count'])) ? 0 : $PowerBB->_GET['count'];
		$PowerBB->_GET['count'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['count'],'intval');

		$ListArr 						= 	array();
		$ListArr['select'] = '
		    m.*,
		    g.id AS group_id,
		    g.user_title AS group_user_title,
		    g.usertitle_change AS group_usertitle_change';

		$ListArr['from'] = $PowerBB->table['member'] . ' AS m';

		// JOINs
		$ListArr['join'] = array(
		    array(
		        'type'  => 'left',
		        'from'  => $PowerBB->table['group'] . ' AS g',
		        'where' => 'm.usergroup = g.id'
		    ),

		);

		// Order data
		$ListArr['order']				=	array();
		$ListArr['order']['field']		=	'm.posts';
		$ListArr['order']['type']		=	'DESC';

		$ListArr['limit']		        =	$PowerBB->_CONF['info_row']['perpage'];

		// Clean data from HTML
		$ListArr['proc'] 				= 	array();
		$ListArr['proc']['*'] 			= 	array('method'=>'clean','param'=>'html');

		// Pager setup
		$ListArr['pager'] 				= 	array();
		$ListArr['pager']['total']		= 	$mn;
		$ListArr['pager']['perpage'] 	= 	$PowerBB->_CONF['info_row']['perpage'];
		$ListArr['pager']['count'] 		= 	$PowerBB->_GET['count'];
		$ListArr['pager']['location'] 	= 	'index.php?page=member_list&amp;index=1&amp;order=1&amp;order_type=DESC';
		$ListArr['pager']['var'] 		= 	'count';

		$GetMemberList = $PowerBB->member->GetMemberListAdvanced($ListArr);

		$PowerBB->_CONF['template']['while']['MemberList'] = $GetMemberList;

		if ($mn > $PowerBB->_CONF['info_row']['perpage'])
		{
		$PowerBB->template->assign('pager',$PowerBB->pager->show());
		}

		$PowerBB->template->display('show_memberlist');

	}

	function _GetMemberList2()
	{
		global $PowerBB;

       $mn = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(*) FROM " . $PowerBB->table['member'] . ""));


		$PowerBB->_GET['count'] = (!isset($PowerBB->_GET['count'])) ? 0 : $PowerBB->_GET['count'];
		$PowerBB->_GET['count'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['count'],'intval');

		$ListArr 						= 	array();
		$ListArr['select'] = '
		    m.*,
		    g.id AS group_id,
		    g.user_title AS group_user_title,
		    g.usertitle_change AS group_usertitle_change';

		$ListArr['from'] = $PowerBB->table['member'] . ' AS m';

		// JOINs
		$ListArr['join'] = array(
		    array(
		        'type'  => 'left',
		        'from'  => $PowerBB->table['group'] . ' AS g',
		        'where' => 'm.usergroup = g.id'
		    ),

		);

		// Order data
		$ListArr['order']				=	array();
		$ListArr['order']['field']		=	'm.id';
		$ListArr['order']['type']		=	'ASC';

		$ListArr['limit']		        =	$PowerBB->_CONF['info_row']['perpage'];

		// Clean data from HTML
		$ListArr['proc'] 				= 	array();
		$ListArr['proc']['*'] 			= 	array('method'=>'clean','param'=>'html');

		// Pager setup
		$ListArr['pager'] 				= 	array();
		$ListArr['pager']['total']		= 	$mn;
		$ListArr['pager']['perpage'] 	= 	$PowerBB->_CONF['info_row']['perpage'];
		$ListArr['pager']['count'] 		= 	$PowerBB->_GET['count'];
		$ListArr['pager']['location'] 	= 	'index.php?page=member_list&amp;index=1&amp;order=2&amp;order_type=DESC';
		$ListArr['pager']['var'] 		= 	'count';

		$GetMemberList = $PowerBB->member->GetMemberListAdvanced($ListArr);

		$PowerBB->_CONF['template']['while']['MemberList'] = $GetMemberList;

		if ($mn > $PowerBB->_CONF['info_row']['perpage'])
		{
		$PowerBB->template->assign('pager',$PowerBB->pager->show());
		}

		$PowerBB->template->display('show_memberlist');

	}

	function _GetMemberList3()
	{
		global $PowerBB;

       $mn = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(*) FROM " . $PowerBB->table['member'] . ""));


		$PowerBB->_GET['count'] = (!isset($PowerBB->_GET['count'])) ? 0 : $PowerBB->_GET['count'];
		$PowerBB->_GET['count'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['count'],'intval');

		$ListArr 						= 	array();
		$ListArr['select'] = '
		    m.*,
		    g.id AS group_id,
		    g.user_title AS group_user_title,
		    g.usertitle_change AS group_usertitle_change';

		$ListArr['from'] = $PowerBB->table['member'] . ' AS m';

		// JOINs
		$ListArr['join'] = array(
		    array(
		        'type'  => 'left',
		        'from'  => $PowerBB->table['group'] . ' AS g',
		        'where' => 'm.usergroup = g.id'
		    ),

		);

		// Order data
		$ListArr['order']				=	array();
		$ListArr['order']['field']		=	'm.visitor';
		$ListArr['order']['type']		=	'DESC';

		$ListArr['limit']		        =	$PowerBB->_CONF['info_row']['perpage'];

		// Clean data from HTML
		$ListArr['proc'] 				= 	array();
		$ListArr['proc']['*'] 			= 	array('method'=>'clean','param'=>'html');

		// Pager setup
		$ListArr['pager'] 				= 	array();
		$ListArr['pager']['total']		= 	$mn;
		$ListArr['pager']['perpage'] 	= 	$PowerBB->_CONF['info_row']['perpage'];
		$ListArr['pager']['count'] 		= 	$PowerBB->_GET['count'];
		$ListArr['pager']['location'] 	= 	'index.php?page=member_list&amp;index=1&amp;order=3&amp;order_type=DESC';
		$ListArr['pager']['var'] 		= 	'count';

		$GetMemberList = $PowerBB->member->GetMemberListAdvanced($ListArr);

		$PowerBB->_CONF['template']['while']['MemberList'] = $GetMemberList;

		if ($mn > $PowerBB->_CONF['info_row']['perpage'])
		{
		$PowerBB->template->assign('pager',$PowerBB->pager->show());
		}

		$PowerBB->template->display('show_memberlist');


	}

	function _GetUsernameListSort()
	{
		global $PowerBB;
		$PowerBB->_GET['count'] = (!isset($PowerBB->_GET['count'])) ? 0 : $PowerBB->_GET['count'];
        $PowerBB->_GET['count'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['count'],'intval');
  		$PowerBB->_GET['count'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_GET['count'],'html');
  		$PowerBB->_GET['count'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_GET['count'],'sql');
  		$PowerBB->_GET['letr'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_GET['letr'],'html');
  		$PowerBB->_GET['letr'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_GET['letr'],'sql');
  		$PowerBB->_GET['letr'] 	= 	urlencode($PowerBB->_GET['letr']);
     	$PowerBB->_GET['letr'] = str_replace("%23", "%#", $PowerBB->_GET['letr']);

			if(is_numeric($PowerBB->_GET['letr']))
			{				 if($PowerBB->_GET['letr'] > 29)
				 {
				  $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
				 }

				$letter_show=array('ا','ي','و','ه','ن','م','ل','ك','ق','ف','غ','ع','ظ','ط','ض','ص','ش','س','ز','ر','ذ','د','خ','ح','ج','ث','ت','ب','أ');
				$count_letter_a= count($letter_show);
                $count_letter  = $count_letter_a - $PowerBB->_GET['letr'];
				$PowerBB->_GET['letr'] = $letter_show[$count_letter];

			 }


  		$PowerBB->_GET['sort'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_GET['sort'],'html');
  		$PowerBB->_GET['sort'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_GET['sort'],'sql');
		if (!$PowerBB->_GET['sort'] == "username")
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['path_not_true']);
		}
  		$letr 	= 	$PowerBB->_GET['letr'] . "%";
       $mn = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(*) FROM " . $PowerBB->table['member'] . " WHERE username LIKE '$letr' "));

		$ListArr 						= 	array();
		$ListArr['select'] = '
		    m.*,
		    g.id AS group_id,
		    g.user_title AS group_user_title,
		    g.usertitle_change AS group_usertitle_change';

		$ListArr['from'] = $PowerBB->table['member'] . ' AS m';

		// JOINs
		$ListArr['join'] = array(
		    array(
		        'type'  => 'left',
		        'from'  => $PowerBB->table['group'] . ' AS g',
		        'where' => 'm.usergroup = g.id'
		    ),

		);

		$ListArr['where'][0] 			= 	array();
		$ListArr['where'][0]['name'] 	= 	'm.username';
		$ListArr['where'][0]['oper'] 	= 	'LIKE';
		$ListArr['where'][0]['value'] 	= 	$PowerBB->_GET['letr'] . "%";

		// Order data
		$ListArr['order']				=	array();
		$ListArr['order']['field']		=	'm.username';
		$ListArr['order']['type']		=	'ASC';

		$ListArr['limit']		        =	$PowerBB->_CONF['info_row']['perpage'];

		// Clean data from HTML
		$ListArr['proc'] 				= 	array();
		$ListArr['proc']['*'] 			= 	array('method'=>'clean','param'=>'html');

		// Pager setup
		$ListArr['pager'] 				= 	array();
		$ListArr['pager']['total']		= 	$mn;
		$ListArr['pager']['perpage'] 	= 	$PowerBB->_CONF['info_row']['perpage'];
		$ListArr['pager']['count'] 		= 	$PowerBB->_GET['count'];
		$ListArr['pager']['location'] 	= 	'index.php?page=member_list&amp;index=1&amp;letr=' . $PowerBB->_GET['letr'] . '&amp;sort=username';
		$ListArr['pager']['var'] 		= 	'count';

		$GetMemberList = $PowerBB->member->GetMemberListAdvanced($ListArr);

		$PowerBB->_CONF['template']['while']['MemberList'] = $GetMemberList;

		if (is_array($PowerBB->_CONF['template']['while']['MemberList'])
			and sizeof($PowerBB->_CONF['template']['while']['MemberList']) == 0)
		{
		 $PowerBB->template->assign('results',1);
		 $PowerBB->template->assign('no_search_results',$PowerBB->_CONF['template']['_CONF']['lang']['no_search_results']);
		}

		if ($mn > $PowerBB->_CONF['info_row']['perpage'])
		{
		$PowerBB->template->assign('pager',$PowerBB->pager->show());
		}

		$PowerBB->template->display('show_memberlist');

	}

	function _SearchByUsername()
	{
		global $PowerBB;
		$PowerBB->_GET['count'] = (!isset($PowerBB->_GET['count'])) ? 0 : $PowerBB->_GET['count'];
        $PowerBB->_GET['count'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['count'],'intval');
  		$PowerBB->_GET['count'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_GET['count'],'html');
  		$PowerBB->_GET['count'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_GET['count'],'sql');
		$PowerBB->_POST['username'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['username'],'trim');
  		$PowerBB->_POST['username'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['username'],'html');
  		$PowerBB->_POST['username'] 	= 	$PowerBB->functions->CleanVariable($PowerBB->_POST['username'],'sql');
  		//$PowerBB->_POST['username'] 	= 	urlencode($PowerBB->_POST['username']);

		if (empty($PowerBB->_POST['username']))
		{
			$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Please_fill_in_all_the_information']);
		}
		if ($PowerBB->_POST['exactname'])
		{
  		$username 	= 	"'".$PowerBB->_POST['username']."'";
  		$username1 	= 	$PowerBB->_POST['username'];
  		$oper 	= 	" = ";
  		}
  		else
		{
  		$username 	= 	"'%".$PowerBB->_POST['username']."%'";
  		$username1 	= 	"%".$PowerBB->_POST['username']."%";
  		$oper 	= 	" LIKE";
  		}

       $mn = $PowerBB->DB->sql_fetch_row($PowerBB->DB->sql_query("SELECT COUNT(*) FROM " . $PowerBB->table['member'] . " WHERE username " .$oper.$username. " "));


		$ListArr 						= 	array();
		$ListArr['select'] = '
		    m.*,
		    g.id AS group_id,
		    g.user_title AS group_user_title,
		    g.usertitle_change AS group_usertitle_change';

		$ListArr['from'] = $PowerBB->table['member'] . ' AS m';

		// JOINs
		$ListArr['join'] = array(
		    array(
		        'type'  => 'left',
		        'from'  => $PowerBB->table['group'] . ' AS g',
		        'where' => 'm.usergroup = g.id'
		    ),

		);

		$ListArr['where'][0] 			= 	array();
		$ListArr['where'][0]['name'] 	= 	'm.username';
		$ListArr['where'][0]['oper'] 	= 	$oper;
		$ListArr['where'][0]['value'] 	= 	$username1;

		// Order data
		$ListArr['order']				=	array();
		$ListArr['order']['field']		=	'm.username';
		$ListArr['order']['type']		=	'ASC';

		$ListArr['limit']		        =	$PowerBB->_CONF['info_row']['perpage'];

		// Clean data from HTML
		$ListArr['proc'] 				= 	array();
		$ListArr['proc']['*'] 			= 	array('method'=>'clean','param'=>'html');

		// Pager setup
		$ListArr['pager'] 				= 	array();
		$ListArr['pager']['total']		= 	$mn;
		$ListArr['pager']['perpage'] 	= 	$PowerBB->_CONF['info_row']['perpage'];
		$ListArr['pager']['count'] 		= 	$PowerBB->_GET['count'];
		$ListArr['pager']['location'] 	= 	'index.php?page=member_list&amp;index=1&amp;search_by_username=1';
		$ListArr['pager']['var'] 		= 	'count';

		$GetMemberList = $PowerBB->member->GetMemberListAdvanced($ListArr);

		$PowerBB->_CONF['template']['while']['MemberList'] = $GetMemberList;


		if (is_array($PowerBB->_CONF['template']['while']['MemberList'])
			and sizeof($PowerBB->_CONF['template']['while']['MemberList']) == 0)
		{
		 $PowerBB->template->assign('results',1);
		 $PowerBB->template->assign('no_search_results',$PowerBB->_CONF['template']['_CONF']['lang']['no_search_results']);
		}


		if ($mn > $PowerBB->_CONF['info_row']['perpage'])
		{
		 $PowerBB->template->assign('pager',$PowerBB->pager->show());
		}

		$PowerBB->template->display('show_memberlist');


	}

}
?>
