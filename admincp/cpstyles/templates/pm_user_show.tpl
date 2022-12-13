<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a target="main" href="index.php?page=pm&pm=1&main=1">	{$lang['Views_Private_Messages']}</a>&nbsp; »&nbsp;
{if {$Dousername}}
 {$lang['B_Private_Messages']}
  {$Dousername}
			{else}
{$lang['View_all_your_messages_sent_and_received']}
			{/if}

</div>
<br />

<div align="center">
<table border="0" cellspacing="1" class="border" width="90%">
	<tr align="center">
		<td width="80%" class="main1" colspan="6">
{if {$Dousername}}
{$lang['Messages_sent_to']}
 : {$Dousername}
			{else}
{$lang['The_number_of_messages_sent_and_received_messages']}
 ({$Msg_Num})
			{/if}
		</td>
	</tr>
	<tr align="center">
	<td class="main2" width="3%">
		{$lang['Case']}
	</td>
		<td class="main2" width="40%">
			{$lang['Message_Title']}
		</td>
		<td class="main2" width="10%">
			{$lang['Sender']}
		</td>
		<td class="main2" width="10%">
			{$lang['SenderTo']}
		</td>
		<td class="main2" width="10%">
			{$lang['Date_Sent']}
		</td>
	<td class="main2" width="3%">
			{$lang['Delet']}
		</td>
	</tr>
	{Des::while}{MassegeList}
	<tr>
				<td class="row1" width="3%" align="center">
			{if {$MassegeList['user_read']} == 1}
			<img alt="{$lang['Message_readable']}"
src="../{$admincpdir}/cpstyles/
{$_CONF['info_row']['cssprefs']}/dot_nonewfolder.gif" border="0" cellspacing="1" />
			{else}
			<img alt="{$lang['New_Message']}"
src="../{$admincpdir}/cpstyles/
{$_CONF['info_row']['cssprefs']}/newfolder.gif" border="0" cellspacing="1" />
			{/if}
			</td>
		<td class="row1" width="40%" align="right">
		<?php $PowerBB->_CONF['template']['while']['MassegeList'][$this->x_loop]['title'] = $PowerBB->functions->CleanVariable($PowerBB->_CONF['template']['while']['MassegeList'][$this->x_loop]['title'],'html'); ?>
        <?php $PowerBB->_CONF['template']['while']['MassegeList'][$this->x_loop]['title'] = $PowerBB->Powerparse->censor_words($PowerBB->_CONF['template']['while']['MassegeList'][$this->x_loop]['title']); ?>

			<a href="index.php?page=pm&amp;read=1&amp;pm=1&amp;username={$MassegeList['user_to']}&amp;id={$MassegeList['id']}">{$MassegeList['title']}</a>
			<br />
			{if {$MassegeList['user_read']} == 1}
			<font class="readpm">{$lang['Message_readable']}</font>
			{else}
			<font class="unreadpm">{$lang['New_Message']}</font>
			{/if}
		</td>
		<td class="row1" width="10%" align="center">
			<a target="_blank" href="../index.php?page=profile&amp;show=1&amp;username={$MassegeList['user_from']}">{$MassegeList['user_from']}</a>
					      <input TYPE="hidden" name="user_from" id="user_from" value="{$MassegeList['user_from']}" />

		</td>
		<td class="row1" width="10%" align="center">
			<a target="_blank" href="../index.php?page=profile&amp;show=1&amp;username={$MassegeList['user_to']}">{$MassegeList['user_to']}</a>
			<input TYPE="hidden" name="user_to" id="user_to" value="{$MassegeList['user_to']}" />
		</td>
		<td class="row1" width="10%" align="center">
			{$MassegeList['date']}
		</td>
		<td class="row1" width="3%" align="center">
      <a href="index.php?page=pm&amp;del=1&amp;pm=1&amp;id={$MassegeList['id']}&amp;user_to={$MassegeList['user_to']}">{$lang['Delet']}</a>
		</td>
	</tr>
	{if {$Dousername}}
	{if {$Msg_Num} == 0}
	<tr>
	<td class="row1" width="76%" align="center" colspan="6">

{$lang['Does_not_have_private_messages']}
			{else}
			</td>
	</tr>
	{/if}
	{/if}
	{/Des::while}
	<tr>
				<td class="row1" width="66%" align="right" colspan="6">
					<img alt="{$lang['Message_readable']}"
src="../{$admincpdir}/cpstyles/
{$_CONF['info_row']['cssprefs']}/dot_nonewfolder.gif" border="0" cellspacing="1" />  {$lang['Message_readable']}
			<br />
				<img alt="{$lang['New_Message']}"
src="../{$admincpdir}/cpstyles/
{$_CONF['info_row']['cssprefs']}/newfolder.gif" border="0" cellspacing="1" />   {$lang['New_Message']}
				</td>
	</tr>

	</table>
</div>

<span class="pager-left">{$pager} </span>
<br />