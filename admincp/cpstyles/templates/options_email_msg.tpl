<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=options&amp;index=1">{$lang['mange_forum']}</a> &raquo;
 <a href="index.php?page=options&amp;email_msg=1&amp;main=1">{$lang['mail_messages']}</a></div>

<br />
<form action="index.php?page=options&amp;email_msg=1&amp;update=1"  name="myform" method="post">
<table width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
	{Des::while}{GetMessageList}
{if {$GetMessageList['title']} !=''}

<tr align="center" valign="top">
		<td class="main1">
  {$lang['Message_Title']}:<input type="text" name="title-{$GetMessageList['id']}" value="{$GetMessageList['title']}" size="30" />
</td>
</tr>
		<tr align="center" valign="top">
		<td class="row1" valign="top" >
		{$lang['message_text']}
		<br />
<textarea name="text-{$GetMessageList['id']}" rows="6" cols="50" wrap="virtual" dir="{$_CONF['info_row']['content_dir']}">{$GetMessageList['text']}</textarea>
		</td>
	</tr>
<tr>
	<td class="main2" align="center">
	<input class="submit-buttons" type="submit" value="{$lang['Save']}" name="submit" accesskey="s" /></td>
</tr>
{/if}
		{/Des::while}
</table>
</form>
