<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=award&amp;control=1&amp;main=1">{$lang['control_awards']}</a></div>

<br />

<table width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
	<tr align="center">
		<td class="main1">{$lang['title_award']}</td>
		<td class="main1">{$lang['mem_name']}</td>
		<td class="main1">{$lang['image_award']}</td>
		<td class="main1">{$lang['edit']}</td>
		<td class="main1">{$lang['Delet']}</td>

	</tr>
	{Des::while}{AwardsList}
	<tr align="center">
		<td class="row1">
			{$AwardsList['award']}
		</td>
		<td class="row1">
			<a target="_blank" href="../index.php?page=profile&show=1&id={$AwardsList['user_id']}">{$AwardsList['username']}</a>
		</td>
		<td class="row1">
			<img border="0" src="{$AwardsList['award_path']}">
		</td>
		<td class="row1">
			<a href="index.php?page=award&amp;edit=1&amp;main=1&amp;id={$AwardsList['id']}">{$lang['edit']}</a>
		</td>
		<td class="row1">
			<a href="index.php?page=award&amp;del=1&amp;start=1&amp;id={$AwardsList['id']}">{$lang['Delet']}</a>
		</td>
	</tr>
	{/Des::while}
</table>