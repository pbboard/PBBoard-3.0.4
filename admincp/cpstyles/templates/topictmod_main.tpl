<br />

<div class="address_bar">{$lang['multi_moderation']} &raquo;
<a href="index.php?page=topic_mod&amp;control=1&amp;main=1">{$lang['mange_multi_moderation']}</a></div>

<br />

<table width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
	<tr align="center">
		<td class="main1" colspan="3">
      {$lang['mange_multi_moderation']}
		</td>
		</tr>
		<tr align="center">
		<td class="main1">
		{$lang['title_multi_moderation']}
		</td>
		<td class="main1">
		{$lang['edit']}
		</td>
		<td class="main1">
		{$lang['Delet']}
		</td>
	</tr>
	{Des::while}{TopicModsList}
	<tr align="center">
		<td class="row1">
{$TopicModsList['title']}
		</td>
		<td class="row1">
			<a href="index.php?page=topic_mod&amp;edit=1&amp;main=1&amp;id={$TopicModsList['id']}">{$lang['edit']}</a>
		</td>
		<td class="row1">
			<a href="index.php?page=topic_mod&amp;del=1&amp;start=1&amp;id={$TopicModsList['id']}">{$lang['Delet']}</a>
		</td>
	</tr>
	{/Des::while}
</table>
