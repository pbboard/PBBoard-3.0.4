<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
{$lang['trash']} &raquo;
 <a href="">{$lang['trash_subjects']}</a></div>

<br />
<form method="post" action="index.php?page=trash&amp;subject=1&amp;del_all_subjects=1">
<table cellpadding="3" cellspacing="1" width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
<tr valign="top" align="center">
	<td class="main1"> {$lang['delete_all_subjects']} </td>
</tr>
<tr valign="top" align="center">
	<td class="row1">
	<input class="button" value="{$lang['delete_all_subjects']}" type="submit">
	</td>
</tr>
</table>
</form>
<br />
<table cellpadding="3" cellspacing="1" width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
<tr valign="top" align="center">
	<td class="main1">{$lang['subject_title']}</td>
	<td class="main1">{$lang['writer']}</td>
	<td class="main1">{$lang['retard']}</td>
	<td class="main1">{$lang['Delet']}</td>
</tr>
{Des::while}{TrashList}
<tr valign="top" align="center">
	<td class="row1"><a href="../index.php?page=topic&amp;show=1&amp;id={$TrashList['id']}" target="_blank">{$TrashList['title']}</a></td>
	<td class="row1"><a href="../index.php?page=profile&amp;show=1&amp;username={$TrashList['writer']}" target="_blank">{$TrashList['writer']}</a></td>
	<td class="row1"><a href="index.php?page=trash&amp;subject=1&amp;untrash=1&amp;start=1&amp;id={$TrashList['id']}">{$lang['retard']}</a></td>
	<td class="row1"><a href="index.php?page=trash&amp;subject=1&amp;del=1&amp;confirm=1&amp;id={$TrashList['id']}">{$lang['Delet']}</a></td>
</tr>
{/Des::while}
</table>
