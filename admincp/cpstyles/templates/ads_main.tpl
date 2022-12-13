<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=ads&amp;control=1&amp;main=1">{$lang['Commercials']}</a></div>

<br />

<table width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
	<tr align="center">
		<td class="main1">
		{$lang['Site_Name']}
		</td>
		<td class="main1">
		{$lang['edit']}
		</td>
		<td class="main1">
		{$lang['Delet']}
		</td>
		<td class="main1">
		{$lang['clicks_num']}
		</td>
	</tr>
	{Des::while}{AdsList}
	<tr align="center">
		<td class="row1">
			{$AdsList['sitename']}
		</td>
		<td class="row1">
			<a href="index.php?page=ads&amp;edit=1&amp;main=1&amp;id={$AdsList['id']}">{$lang['edit']}</a>
		</td>
		<td class="row1">
			<a href="index.php?page=ads&amp;del=1&amp;main=1&amp;id={$AdsList['id']}">{$lang['Delet']}</a>
		</td>
			<td class="row1">
			{$AdsList['clicks']}
		</td>
	</tr>
	{/Des::while}
</table>
