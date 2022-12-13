<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=extension&amp;control=1&amp;main=1">{$lang['AEXtensions']}</a></div>

<br />

<table width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
	<tr align="center">
		<td class="main1">
		{$lang['EXtension']}
		</td>
		<td class="main1">
		{$lang['Maximum_Size']}
		</td>
		<td class="main1">
		{$lang['edit']}
		</td>
		<td class="main1">
		{$lang['Delet']}
		</td>
	</tr>
	{Des::while}{ExList}
	<tr align="center">
		<td class="row1">
			{$ExList['Ex']}
		</td>
		<td class="row1">
			{$ExList['max_size']}
			{$lang['KB']}
		</td>
		<td class="row1">
			<a href="index.php?page=extension&amp;edit=1&amp;main=1&amp;id={$ExList['id']}">{$lang['edit']}</a>
		</td>
		<td class="row1">
			<a href="index.php?page=extension&amp;del=1&amp;main=1&amp;id={$ExList['id']}">{$lang['Delet']}</a>
		</td>
	</tr>
	{/Des::while}
</table>
