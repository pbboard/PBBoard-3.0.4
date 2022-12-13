<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
 <a href="index.php?page=template&amp;control=1&amp;main=1">{$lang['templates']}</a></div>

<br />

<table cellpadding="3" cellspacing="1" width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
<tr valign="top" align="center">
	<td class="main1">{$lang['style_name']}</td>

	<td class="main1">{$lang['edit_templates']}</td>
</tr>
{Des::while}{StyleList}
<tr valign="top" align="center">
	<td class="row1">{$StyleList['style_title']}</td>
	<td class="row1"><a href="index.php?page=template&amp;control=1&amp;show=1&amp;id={$StyleList['id']}">
	{$lang['edit_templates']}</a></td>
</tr>
{/Des::while}
</table>
