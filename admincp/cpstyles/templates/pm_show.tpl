<br />
<?php $PowerBB->_CONF['template']['MassegeRow']['title'] = $PowerBB->functions->CleanVariable($PowerBB->_CONF['template']['MassegeRow']['title'],'html'); ?>
<?php $PowerBB->_CONF['template']['MassegeRow']['title'] = $PowerBB->Powerparse->censor_words($PowerBB->_CONF['template']['MassegeRow']['title']); ?>
<?php $PowerBB->_CONF['template']['MassegeRow']['text'] = $PowerBB->Powerparse->censor_words($PowerBB->_CONF['template']['MassegeRow']['text']); ?>

<div class="address_bar">{$lang['Control_Panel']} &raquo;
 <a href="javascript: history.back()"> {$lang['B_Private_Messages']}
 {$MassegeRow['user_to']}</a>  &raquo;
  <a href="index.php?page=pm&amp;read=1&amp;pm=1&amp;username={$MassegeRow['user_to']}&amp;id={$MassegeRow['id']}">{$MassegeRow['title']}</a></div>

<br />
	<table border="0" cellspacing="1" class="border" width="98%" align="center">
		<tr>
			<td class="main1" align="right">
			<div class="r-right">
{$lang['Message_Title']} :
 				{$MassegeRow['title']}
			</td>
			</div>
		</tr>
      	<tr align="center">
        	<td class="main2" width="70%">
{$lang['message_text']}
        	</td>
      	</tr>
      	<tr>
		<td name="text" class="row1" width="70%" valign="top">
			{$MassegeRow['text']}
			</td>
		</tr>
      	<tr align="center">
        	<td class="row1" width="20%">
{$lang['This_message_sent_from']} :
 <a target="_blank" href="../index.php?page=profile&amp;show=1&amp;username={$MassegeRow['user_from']}"><b><u>{$MassegeRow['user_from']}</u></b></a>
{$lang['into']}
: <a target="_blank" href="../index.php?page=profile&amp;show=1&amp;username={$MassegeRow['user_to']}"><b><u>{$MassegeRow['user_to']}</u></b></a>
        		{$lang['On_date']} : {$MassegeRow['date']}
        	</td>
      	</tr>
</table>

<br />