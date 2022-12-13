<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=extension&amp;search=1&amp;main=1">{$lang['search_in_attachmint']}</a> &raquo;
&raquo; {$lang['Search_Results']}</div>

<br />

<div align="center">

	<table width="90%" class="t_style_b" border="0" cellspacing="1">
		<tr align="center">
			<td class="main1">
			{$lang['filename']}
			</td>
			<td class="main1">
			{$lang['DownloadsNum']}
			</td>
			<td class="main1">
			{$lang['filesize']}
			</td>
			<td class="main1">
			{$lang['View_Post']}
			</td>
		</tr>
{Des::while}{Inf}
		<tr align="center">
			<td class="row1">
				<a href="../index.php?page=download&amp;attach=1&amp;id={$Inf['id']}">{$Inf['filename']}</a>
			</td>
			<td class="row1">
				{$Inf['visitor']}
			</td>
			<td class="row1">
				{$Inf['filesize']}
			</td>
			<td class="row1">
				<a href="../index.php?page=topic&amp;show=1&amp;id={$Inf['subject_id']}" target="_blank">{$lang['View_Post']}</a>
			</td>
		</tr>
		{/Des::while}
	</table>
</div>
