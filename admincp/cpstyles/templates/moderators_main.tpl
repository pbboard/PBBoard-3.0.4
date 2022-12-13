<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=moderators&amp;control=1&amp;main=1">{$lang['moderators']}</a> &raquo;
 {$lang['mange_moderators']} </div>

<br />

 <form action="index.php?page=moderators&amp;control=1&amp;section=1" method="post">
<div align="center">
	<table width="60%" class="t_style_b" border="0" cellspacing="1">
	<tr align="center">
		<td class="main1">
		{$lang['mange_moderators']}

		</td>
	</tr>
		<tr>
		<td class="row2">
{$lang['Choice_Forum']} :
{$DoJumpList}
		</td>
	</tr>
		<tr align="center">
		<td class="row2">
<input type="submit" value="{$lang['Go']}" name="submit" /></td>
	</tr>
</table></div>



</form>