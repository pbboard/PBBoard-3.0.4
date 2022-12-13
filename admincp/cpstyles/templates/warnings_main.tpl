<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
 <a href="index.php?page=member&amp;warnings=1&amp;main=1">{$lang['View_warns']}</a></div>

<br />

<table cellpadding="3" cellspacing="1" width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
<tr valign="top" align="center">
	<td class="main1">{$lang['username']}</td>
	<td class="main1">{$lang['warns_num']}</td>
	<td class="main1">{$lang['warns_Cancel']}</td>
</tr>
{Des::while}{WarnedMembersList}
<tr valign="top" align="center">
	<td class="row1"><a href="../index.php?page=profile&amp;show=1&amp;id={$WarnedMembersList['id']}" target="_blank">{$WarnedMembersList['username']}</a></td>
	<td class="row1">{$WarnedMembersList['warnings']}</a></td>
	<td class="row1"><a href="index.php?page=member&amp;warnings=1&amp;warn_del=1&amp;id={$WarnedMembersList['id']}">{$lang['warns_Cancel']}</a></td>
</tr>
{/Des::while}
</table>

