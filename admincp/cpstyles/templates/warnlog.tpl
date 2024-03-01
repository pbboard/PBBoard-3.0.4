<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
 <a href="index.php?page=warn&amp;main=1">{$lang['warns']}</a> <b>{$WarnNumber}</b></div>

<br />

<table cellpadding="3" cellspacing="1" width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
<tr valign="top" align="center">
	<td class="main1">{$lang['from']}</td>
	<td class="main1">{$lang['To']}</td>
	<td class="main1">{$lang['Reason']}</td>
	<td class="main1">{$lang['Date']}</td>
	<td class="main1">{$lang['Delet']}</td>
</tr>
{Des::while}{WarningLog}
<tr valign="top" align="center">
	<td class="row1"><a href="../index.php?page=profile&amp;show=1&amp;username={$WarningLog['warn_from']}" target="_blank">{$WarningLog['warn_from']}</a></td>
	<td class="row1"><a href="../index.php?page=profile&amp;show=1&amp;username={$WarningLog['warn_to']}" target="_blank">{$WarningLog['warn_to']}</a></td>
	<td class="row1">{$WarningLog['warn_text']}</a></td>
	<td class="row1">{$WarningLog['warn_date']}</td>
	<td class="row1">
	<a href="index.php?page=warn&amp;deletone=1&amp;id={$WarningLog['id']}&amp;user={$WarningLog['warn_to']}">{$lang['Delet']}</a>
   </td>
</tr>
{/Des::while}
<tr valign="top" align="center">
<td class="row1" colspan="2">
<span class="pager-left">{$pager} </span>
</td>
</tr>
</table>

