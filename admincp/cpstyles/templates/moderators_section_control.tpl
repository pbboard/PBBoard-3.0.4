<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=moderators&amp;control=1&amp;main=1">{$lang['moderators']}</a> &raquo;
 <a href="index.php?page=moderators&amp;control=1&amp;main=1">{$lang['mange_moderators']}</a> &raquo;
  {$Section['title']}</div>

<br />

<div align="center">

<table width="90%" class="t_style_b" border="0" cellspacing="1">
	<tr align="center">
		<td class="main1" width="40%">
		{$lang['moderator']}
		</td>
		<td class="main1" width="30%">
		{$lang['moderator_Cancel']}
		</td>
	</tr>
	{Des::while}{ModeratorsList}
	<tr align="center">
		<td class="row1" width="40%">
			<a href="../index.php?page=profile&show=1&id={$ModeratorsList['member_id']}" target="_blank">{$ModeratorsList['username']}</a>
		</td>
		<td class="row1" width="30%">
			<a href="index.php?page=moderators&amp;del=1&amp;id={$ModeratorsList['member_id']}&amp;section_id={$Section['id']}">{$lang['moderator_Cancel']}</a>
		</td>
	</tr>
	{/Des::while}
</table>
</div>
