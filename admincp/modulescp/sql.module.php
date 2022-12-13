<?php

(!defined('IN_PowerBB')) ? die() : '';

define('IN_ADMIN',true);

$CALL_SYSTEM				=	array();


define('CLASS_NAME','PowerBBSqlMOD');

include('../common.php');
class PowerBBSqlMOD
{

	function run()
	{
		global $PowerBB;

		if ($PowerBB->_CONF['member_permission'])
		{
			$PowerBB->template->display('header');

			if ($PowerBB->_CONF['rows']['group_info']['admincp_member'] == '0')
			{
			  $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
			}
			if (!in_array($PowerBB->_CONF['rows']['member_row']['id'], preg_split('#\s*,\s*#s', $PowerBB->superadministrators, -1, PREG_SPLIT_NO_EMPTY)))
			{
			 $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
			}

			if ($PowerBB->_GET['sql'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_SqlMain();
				}
				elseif ($PowerBB->_GET['start'])
				{
					$this->_SqlStart();
				}
			}

			$PowerBB->template->display('footer');
          }
	}



	function _SqlMain()
	{
		global $PowerBB;

	    $PowerBB->template->display('sql');
	}


	function _SqlStart()
	{
		global $PowerBB;

        $sqlstring = $PowerBB->_POST['sqlstring'];
        $sqlstring = str_replace("&#39;","'",$sqlstring);
        $sqlstring = str_replace('\\','',$sqlstring);
        $sqlstring  = str_replace('\\"', '"', $sqlstring );
        $sql = $PowerBB->DB->sql_query($sqlstring);

        if ($sql)
		{
        $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Entered_the_query_to_the_database_successfully']);
		$PowerBB->functions->redirect('index.php?page=sql&amp;sql=1&amp;main=1');
		}
		else
		{
         $PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Error'].' : ' . $PowerBB->DB->simple_error());
        }

	}


}

?>
