<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=userrating&amp;control=1&amp;main=1">{$lang['userrating']}</a></div>

<br />

<table width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
	<tr align="center">
		<td class="main1">
 {$lang['rating_grade']}
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
	{Des::while}{URList}
	<tr align="center">
		<td class="row1">
		<img border="0" src="../{$URList['rating']}">
		</td>
		<td class="row1">
			{$URList['posts']}
		</td>
		<td class="row1">
			<a href="index.php?page=userrating&amp;edit=1&amp;main=1&amp;id={$URList['id']}">
			{$lang['edit']}</a>
		</td>
		<td class="row1">
			<a href="index.php?page=userrating&amp;del=1&amp;main=1&amp;id={$URList['id']}">
			{$lang['Delet']}</a>
		</td>
	</tr>
	{/Des::while}
</table>
