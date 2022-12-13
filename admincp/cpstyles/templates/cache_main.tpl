<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=template&amp;delcache=1&amp;main=1">{$lang['template_delcache']}</a></div>

<br />

<table cellpadding="3" cellspacing="1" width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
<tr valign="top" align="center">
	<td class="main1">{$lang['style_name']}</td>

	<td class="main1">{$lang['Templates_Temporary']}</td>
</tr>
{Des::while}{StyleList}
<tr valign="top" align="center">
	<td class="row1">{$StyleList['style_title']}</td>
	<td class="row1"><a href="index.php?page=template&amp;delstartcache=1&amp;main=1&amp;id={$StyleList['id']}">{$lang['Delet']}</a></td>
</tr>
{/Des::while}
</table>
