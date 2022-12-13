<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
 <a href="index.php?page=usertitle&amp;control=1&amp;main=1">{$lang['usertitles']}</a></div>

<br />

<table width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
	<tr align="center">
		<td class="main1">
		{$lang['user_titles']}
		</td>
		<td class="main1">
		{$lang['Posts_less_than']}
		</td>
		<td class="main1">
		{$lang['edit']}
		</td>
		<td class="main1">
		{$lang['Delet']}
		</td>
	</tr>
	{Des::while}{UTList}
	<tr align="center">
		<td class="row1">
			{$UTList['usertitle']}
		</td>
		<td class="row1">
			{$UTList['posts']}
		</td>
		<td class="row1">
			<a href="index.php?page=usertitle&amp;edit=1&amp;main=1&amp;id={$UTList['id']}">
			{$lang['edit']}</a>
		</td>
		<td class="row1">
			<a href="index.php?page=usertitle&amp;del=1&amp;main=1&amp;id={$UTList['id']}">
			{$lang['Delet']}</a>
		</td>
	</tr>
	{/Des::while}
</table>
