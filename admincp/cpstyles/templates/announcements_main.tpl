<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=announcement&amp;control=1&amp;main=1">{$lang['announcement']}</a></div>

<br />

<table width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
	<tr align="center">
		<td class="main1">
		{$lang['title']}
		</td>
		<td class="main1">
		{$lang['writer']}
		</td>
		<td class="main1">
		{$lang['date_announcement']}
		</td>
		<td class="main1">
		{$lang['edit']}
		</td>
		<td class="main1">
		{$lang['Delet']}
		</td>
	</tr>
	{Des::while}{AnnList}
	<tr align="center">
		<td class="row1">
			<a href="../index.php?page=announcement&amp;show=1&amp;id={$AnnList['id']}" target="_blank">{$AnnList['title']}</a>
		</td>
		<td class="row1">
			<a href="../index.php?page=profile&amp;show=1&amp;username={$AnnList['writer']}" target="_blank">{$AnnList['writer']}</a>
		</td>
		<td class="row1">
			{$AnnList['date']}
		</td>
		<td class="row1">
			<a href="index.php?page=announcement&amp;edit=1&amp;main=1&amp;id={$AnnList['id']}">{$lang['edit']}</a>
		</td>
		<td class="row1">
			<a href="index.php?page=announcement&amp;del=1&amp;main=1&amp;id={$AnnList['id']}">{$lang['Delet']}</a>
		</td>
	</tr>
	{/Des::while}
</table>
