<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
 <a href="">{$lang['review_subjects']}</a></div>

<br />

<table cellpadding="3" cellspacing="1" width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
<tr valign="top" align="center">
	<td class="main1">{$lang['subject_title']}</td>
	<td class="main1">{$lang['writer']}</td>
</tr>
{Des::while}{ReviewList}
<tr valign="top" align="center">
	<td class="row1"><a target="_blank" href="../index.php?page=topic&amp;show=1&amp;id={$ReviewList['id']}">{$ReviewList['title']}</a></td>
	<td class="row1"><a target="_blank" href="../index.php?page=profile&amp;show=1&amp;username={$ReviewList['writer']}">{$ReviewList['writer']}</a></td>
</tr>
{/Des::while}
</table>
