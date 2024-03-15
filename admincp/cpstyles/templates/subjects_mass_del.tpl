<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
 <a href="">{$lang['subjects_del']}</a></div>

<br />
<table cellpadding="3" cellspacing="1" width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
<tr valign="top" align="right">
<td class="row3" colspan="2">{$lang['Section_for_dele_subject']}</td>
</tr>
{Des::while}{SectionList}
<tr valign="top" align="right">
	<td class="row2" colspan="2">
	<i class="fa fa-dot-circle-o" aria-hidden="true" style="float:right;"></i>
		<a href="index.php?page=subject&amp;mass_del=1&amp;confirm=1&amp;id={$SectionList['id']}" target="main">{$SectionList['title']}</a>
	</td>
</tr>
{/Des::while}
</table>
