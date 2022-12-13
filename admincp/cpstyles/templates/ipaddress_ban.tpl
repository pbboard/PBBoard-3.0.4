<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
{$lang['members']} &raquo;
<a href="index.php?page=banned&amp;banning=1&amp;main=1">{$lang['banning_ip']}</a>
 </div>

<br />

<form action="index.php?page=banned&amp;banning=1&amp;start=1"  name="myform" method="post">
<table cellpadding="3" cellspacing="1" width="60%" class="t_style_b" border="0" cellspacing="1" align="center">
<tr valign="top" align="center">
	<td class="main1" colspan="2">{$lang['banning_ip']}</td>
</tr>
<tr valign="top">
		<td class="row1">{$lang['Enter_the_IP_address']} </td>
		<td class="row1">
<input type="text" name="ipaddress" id="input_ipaddress" value="" size="30" />&nbsp;
</td>
</tr>
<tr valign="top">
		<td class="row1">{$lang['reason_ban']} </td>
		<td class="row1">
<textarea name="reason_ban" id="textarea_reason_ban" rows="5" cols="36" wrap="virtual" dir="rtl"></textarea>&nbsp;
</td>
</tr>
</tr>
<tr valign="top">
		<td class="row1" colspan="2" align="center"><input class="submit" type="submit" value="{$lang['acceptable']}" name="submit" accesskey="s" /></td>
</tr>
</table>
</form>
<br />
<form action="index.php?page=banned&amp;banning=1&amp;start=1"  name="myform" method="post">
<table width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
<tr align="center">
		<td class="main1" colspan="3">
{$lang['IP_addresses_banned']}
		</td>
	</tr>
	<tr align="center">
		<td class="main2">
		{$lang['IP_addresses']}
		</td>
		<td class="main2">
		{$lang['reason_ban']}
		</td>
		<td class="main2">
		{$lang['Delete_Ban']}
		</td>
	</tr>
	{Des::while}{BannedList}
	<tr align="center">
		<td class="row1">
			{$BannedList['ip']}
		</td>
		<td class="row1">
		    {$BannedList['reason']}
		</td>
		<td class="row1">
			<a href="index.php?page=banned&amp;banning=1&amp;del=1&amp;id={$BannedList['id']}">{$lang['Delete_Ban']}</a>
		</td>
	</tr>
	{/Des::while}
</table>
</form>
<br />