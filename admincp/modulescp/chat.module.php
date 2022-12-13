<?php

(!defined('IN_PowerBB')) ? die() : '';

define('IN_ADMIN',true);

$CALL_SYSTEM			=	array();
$CALL_SYSTEM['CHAT'] 	= 	true;
$CALL_SYSTEM['ICONS'] 		= 	true;
$CALL_SYSTEM['CACHE'] 			= 	true;

define('JAVASCRIPT_PowerCode',true);



define('CLASS_NAME','PowerBBChatMOD');

include('../common.php');
class PowerBBChatMOD
{
	function run()
	{
		global $PowerBB;

		if ($PowerBB->_CONF['member_permission'])
		{
			$PowerBB->template->display('header');

			if ($PowerBB->_CONF['rows']['group_info']['admincp_chat'] == '0')
			{
			  $PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['error_permission']);
			}


			if ($PowerBB->_GET['control'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_ControlMain();
				}
			}
			elseif ($PowerBB->_GET['edit'])
			{
				if ($PowerBB->_GET['main'])
				{
					$this->_EditMain();
				}
				elseif ($PowerBB->_GET['start'])
				{
					$this->_EditStart();
				}
			}
			elseif ($PowerBB->_GET['del'])
			{
                if ($PowerBB->_GET['start'])
				{
					$this->_DelStart();
				}
				elseif ($PowerBB->_GET['del_all'])
				{
					$this->_DelAllStart();
				}
			}

		$PowerBB->template->display('footer');
		}

	}

	function _ControlMain()
	{
		global $PowerBB;

		$PowerBB->_GET['count'] = (!isset($PowerBB->_GET['count'])) ? 0 : $PowerBB->_GET['count'];
		$PowerBB->_GET['count'] = $PowerBB->functions->CleanVariable($PowerBB->_GET['count'],'intval');

		$TotleCahtArr 					= 	array();
		$TotleCahtArr['order']			=	array();
		$TotleCahtArr['order']['field']	=	'id';
		$TotleCahtArr['order']['type']	=	'DESC';

        // show Caht bar
		$CahtArr 					= 	array();
		$CahtArr['order']			=	array();
		$CahtArr['order']['field']	=	'id';
		$CahtArr['order']['type']	=	'DESC';

		$CahtArr['pager'] 				= 	array();
		$CahtArr['pager']['total']		= 	$PowerBB->core->GetNumber($TotleCahtArr,'chat');
		$CahtArr['pager']['perpage'] 	= 	"30";
		$CahtArr['pager']['count'] 		= 	$PowerBB->_GET['count'];
		$CahtArr['pager']['location'] 	= 	'index.php?page=chat&control=1&main=1';
		$CahtArr['pager']['var'] 		= 	'count';

		$CahtArr['proc'] 			= 	array();
		$CahtArr['limit']           = $PowerBB->_CONF['info_row']['chat_message_num'];
		$CahtArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');

		$PowerBB->_CONF['template']['while']['MessagesList'] = $PowerBB->chat->GetChatList($CahtArr);

       if ($PowerBB->core->GetNumber($TotleCahtArr,'chat') > 30)
        {
		$PowerBB->template->assign('pager',$PowerBB->pager->show());
        }
        $PowerBB->template->assign('CahtMessagesNumber',$PowerBB->core->GetNumber($TotleCahtArr,'chat'));

		$PowerBB->template->display('chat_main');
	}




	function _EditMain()
	{
		global $PowerBB;

			if (empty($PowerBB->_GET['id']))
			{
				$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Chat_message_requested_does_not_exist']);
			}

			$CahtEditArr				=	array();
		    $CahtEditArr['where'] 	= 	array('id',$PowerBB->_GET['id']);

			$chatEdit = $PowerBB->chat->GetChatInfo($CahtEditArr);
         $chatEdit['message'] = $PowerBB->Powerparse->censor_words($chatEdit['message']);
			$PowerBB->template->assign('chatEdit',$chatEdit);
            $PowerBB->template->assign('message',$chatEdit['message']);

		$smiles = $PowerBB->icon->GetCachedSmiles();
		foreach ($smiles as $smile)
		{
			$PowerBB->functions->CleanVariable($smile,'html');

			$chatEdit['message'] = str_replace('<img src="' . $smile['smile_path'] . '" border="0" alt="' . $smile['smile_short'] . '" />',$smile['smile_short'],$chatEdit['message']);
			$PowerBB->template->assign('message',$chatEdit['message']);

		}


		$SmlArr 					= 	array();
		$SmlArr['order'] 			=	array();
		$SmlArr['order']['field']	=	'id';
		$SmlArr['order']['type']	=	'ASC';
		$SmlArr['limit']			=	$PowerBB->_CONF['info_row']['smiles_nm'];
		$SmlArr['proc'] 			= 	array();
		$SmlArr['proc']['*'] 		= 	array('method'=>'clean','param'=>'html');

		$PowerBB->_CONF['template']['while']['SmileRows'] = $PowerBB->icon->GetSmileList($SmlArr);


		$PowerBB->template->display('chat_edit');
	}

	function _EditStart()
	{
		global $PowerBB;

			if (empty($PowerBB->_GET['id']))
			{
				$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Chat_message_requested_does_not_exist']);
			}

            //$PowerBB->Powerparse->replace_smiles($PowerBB->_POST['text']);
            $PowerBB->_POST['text'] = str_ireplace('{39}',"'",$PowerBB->_POST['text']);
            $PowerBB->_POST['text'] = str_ireplace('cookie','',$PowerBB->_POST['text']);


             // Filter Words
	        $censorwords = preg_split('#[ \r\n\t]+#', $PowerBB->_CONF['info_row']['censorwords'], -1, PREG_SPLIT_NO_EMPTY);
            $PowerBB->_POST['country'] = str_ireplace($censorwords,'', $PowerBB->_POST['country']);
            $PowerBB->_POST['text'] = str_ireplace($censorwords,'', $PowerBB->_POST['text']);
            $PowerBB->_POST['text'] = str_ireplace('&amp;','&',$PowerBB->_POST['text']);
            $PowerBB->_POST['text'] = str_ireplace('<br>','',$PowerBB->_POST['text']);
            $PowerBB->_POST['text'] = str_ireplace('</p>','',$PowerBB->_POST['text']);
            $PowerBB->_POST['text'] = str_ireplace('<p>','',$PowerBB->_POST['text']);
            $PowerBB->_POST['text'] = str_ireplace('XSS','',$PowerBB->_POST['text']);
            $PowerBB->_POST['text'] = str_ireplace('write','',$PowerBB->_POST['text']);
            $PowerBB->_POST['text'] = str_ireplace('document','',$PowerBB->_POST['text']);
            $PowerBB->_POST['text'] = str_ireplace('&quot;','',$PowerBB->_POST['text']);
            $PowerBB->_POST['country'] = str_ireplace('&amp;','&',$PowerBB->_POST['country']);
            $PowerBB->_POST['country'] = str_ireplace('<br>','',$PowerBB->_POST['country']);
            $PowerBB->_POST['country'] = str_ireplace('</p>','',$PowerBB->_POST['country']);
            $PowerBB->_POST['country'] = str_ireplace('<p>','',$PowerBB->_POST['country']);
            $PowerBB->_POST['country'] = str_ireplace('&quot;','',$PowerBB->_POST['country']);
            $PowerBB->_POST['country'] = str_ireplace('http://','',$PowerBB->_POST['country']);
            $PowerBB->_POST['country'] = str_ireplace('www','',$PowerBB->_POST['country']);
            $PowerBB->_POST['country'] = str_ireplace('com','',$PowerBB->_POST['country']);
            $PowerBB->_POST['country'] = str_ireplace('net','',$PowerBB->_POST['country']);
            $PowerBB->_POST['country'] = str_ireplace('org;','',$PowerBB->_POST['country']);
            $PowerBB->_POST['country'] = str_ireplace('iframe;','',$PowerBB->_POST['country']);
            $PowerBB->_POST['text'] = str_ireplace('iframe;','',$PowerBB->_POST['text']);

		$ChatArr 			= 	array();
		$ChatArr['field']	=	array();

		$ChatArr['field']['message'] 	= 	$PowerBB->_POST['text'];
		$ChatArr['field']['country'] 	    = 	$PowerBB->functions->CleanVariable($PowerBB->_POST['country'],'html');
		$ChatArr['field']['username'] 	= 	$PowerBB->_POST['username'];
		$ChatArr['where'] 				= 	array('id',$PowerBB->_GET['id']);

		$update = $PowerBB->chat->UpdateChat($ChatArr);

		if ($update)
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Chat_message_has_been_updated_successfully']);
			$PowerBB->functions->redirect('index.php?page=chat&amp;control=1&amp;main=1');
		}
	}

	function _DelStart()
	{
		global $PowerBB;

			if (empty($PowerBB->_GET['id']))
			{
				$PowerBB->functions->error($PowerBB->_CONF['template']['_CONF']['lang']['Chat_message_requested_does_not_exist']);
			}

			$DelArr 			= 	array();
			$DelArr['where'] 	= 	array('id',$PowerBB->_GET['id']);

			$del = $PowerBB->core->Deleted($DelArr,'chat');

		if ($del)
		{
				$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Chat_message_has_been_deleted_successfully']);
				$PowerBB->functions->redirect('index.php?page=chat&amp;control=1&amp;main=1');

		}
	}

	function _DelAllStart()
	{
		global $PowerBB;


			$truncate = $PowerBB->DB->sql_query("TRUNCATE " . $PowerBB->table['chat'] );


		if ($truncate)
		{
			$PowerBB->functions->msg($PowerBB->_CONF['template']['_CONF']['lang']['Chat_messages_has_been_deleted_successfully']);
			$PowerBB->functions->redirect('index.php?page=chat&amp;control=1&amp;main=1');
		}
	}

}

?>
