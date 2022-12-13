<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
{$lang['trash']}&raquo;
 <a href="">{$lang['trash_reply']}</a></div>

<br />
<form method="post" action="index.php?page=trash&amp;reply=1&amp;del_all_replys=1">
<table cellpadding="3" cellspacing="1" width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
<tr valign="top" align="center">
	<td class="main1"> {$lang['delete_all_replys']}</td>
</tr>
<tr valign="top" align="center">
	<td class="row1">
	<input class="button" value="{$lang['delete_all_replys']}" name="submit1" type="submit">
	</td>
</tr>
</table>
</form>
<br />
<table cellpadding="3" cellspacing="1" width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
<tr valign="top" align="center">
	<td class="main1">{$lang['Reply_id']}</td>
	<td class="main1">{$lang['writer']}</td>
	<td class="main1">{$lang['retard']}</td>
	<td class="main1">{$lang['Delet']}</td>
</tr>
{Des::while}{TrashList}
<tr valign="top" align="center">
	<td class="row1"><a target="_blank" href="../index.php?page=topic&show=1&id={$TrashList['subject_id']}#{$TrashList['id']}">#{$TrashList['id']}</a></td>
	<td class="row1"><a target="_blank" href="../index.php?page=profile&amp;show=1&amp;username={$TrashList['writer']}">{$TrashList['writer']}</a></td>
	<td class="row1"><a href="index.php?page=trash&amp;reply=1&amp;untrash=1&amp;start=1&amp;subject_id={$TrashList['subject_id']}&amp;id={$TrashList['id']}">{$lang['retard']}</a></td>
	<td class="row1"><a href="index.php?page=trash&amp;reply=1&amp;del=1&amp;start=1&amp;subject_id={$TrashList['subject_id']}&amp;id={$TrashList['id']}" onclick="return confirm('{$lang['confirm']}')">{$lang['Delet']}</a></td>
</tr>
{/Des::while}
</table>
