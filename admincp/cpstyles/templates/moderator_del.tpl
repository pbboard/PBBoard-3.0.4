<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=moderators&amp;control=1&amp;main=1">{$lang['moderators']}</a> &raquo;
<a href="index.php?page=moderators&amp;control=1&amp;section=1&amp;id={$Section['id']}">{$Section['title']}</a> &raquo;
{$lang['mod_Cancel']} :
{$Inf['username']}</div>

<br />
<form action="index.php?page=moderators&amp;del=1&amp;start=1&amp;id={$Inf['id']}" method="post">
{template}forums_am{/template}
<table width="60%" class="t_style_b" border="0" cellspacing="1" align="center">
	<tr align="center">
		<td class="main1" colspan="2">
		{$lang['Confirm_moderator_Cancel']}
		</td>
	</tr>
	<tr align="center">
		<td class="row1" colspan="2">
		{$lang['Are_you_sure_you_want_moderator_Cancel']}
		{$Inf['username']}
		</td>
	</tr>
		<br />
		
		<tr>
			<td align="center">
				<input type="submit" value="{$lang['acceptable']}" name="submit" />
			</td>
		</tr>
</table>



</form>