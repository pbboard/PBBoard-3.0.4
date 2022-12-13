<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=extrafield&amp;control=1&amp;main=1">{$lang['extrafields']}</a></div>

<br />

<table width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
	<tr align="center">
		<td class="main1">
		{$lang['extrafieldname']}
		</td>
		<td class="main1">
		{$lang['edit']}
		</td>
		<td class="main1">
		{$lang['Delet']}
		</td>
	</tr>
	{Des::while}{FieldsList}
	<tr align="center">
		<td class="row1">
			{$FieldsList['name']}
		</td>
		<td class="row1">
			<a href="index.php?page=extrafield&amp;edit=1&amp;main=1&amp;id={$FieldsList['id']}">{$lang['edit']}</a>
		</td>
		<td class="row1">
			<a href="index.php?page=extrafield&amp;del=1&amp;main=1&amp;id={$FieldsList['id']}">{$lang['Delet']}</a>
		</td>
	</tr>
	{/Des::while}
</table>
