<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=forums&amp;control=1&amp;main=1">{$lang['Forums']}</a> &raquo;
 {$lang['powers_of_the_groups']}</div>

<br />

<table width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
	<tr align="center">
		<td class="main1">
		{$lang['Forum_title']}
		</td>
		<td class="main1">
		{$lang['Control_the_powers_of_the_groups']}
		</td>
	</tr>
	{Des::while}{SecList}
	<tr align="center">
		<td class="row1">
			{$SecList['title']}
		</td>
		<td class="row1">
			<a href="index.php?page=forums&amp;groups=1&amp;show_group=1&amp;id={$SecList['id']}">{$lang['ControL']}</a>
		</td>
	</tr>
	{/Des::while}
</table>
