<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
{$lang['custom_bbcodes']}
&nbsp;&raquo; <a href="index.php?page=custom_bbcode&amp;control=1&amp;main=1">{$lang['control_custom_bbcodes']}</a></div>

<br />

<table width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
		<tr>
			<td class="main1" width="98%" colspan="4" align="center"><b>
			{$lang['control_custom_bbcodes']}
			</b>
			</td>
			</tr>
		<td class="main1">
    {$lang['bbcode_title']}
		</td>
		<td class="main1">
		{$lang['bbcode_tag']}
		</td>
		<td class="main1">
		{$lang['edit']}
		</td>
		<td class="main1">
		{$lang['Delet']}
		</td>
	</tr>
	{Des::while}{Custom_bbcodesList}
	<tr align="center">
		<td class="row1">
        {$Custom_bbcodesList['bbcode_title']}
		</td>
		<td class="row1">
<div dir="ltr">
[{$Custom_bbcodesList['bbcode_tag']}]
{content}
[/{$Custom_bbcodesList['bbcode_tag']}]
</div>
		</td>
		<td class="row1">
			<a href="index.php?page=custom_bbcode&amp;edit=1&amp;main=1&amp;id={$Custom_bbcodesList['id']}">{$lang['edit']}</a>
		</td>
		<td class="row1">
			<a href="index.php?page=custom_bbcode&amp;del=1&amp;start=1&amp;id={$Custom_bbcodesList['id']}">{$lang['Delet']}</a>
		</td>
	</tr>
	{/Des::while}
</table>
