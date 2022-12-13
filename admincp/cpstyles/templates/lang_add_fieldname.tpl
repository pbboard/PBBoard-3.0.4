<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
{$lang['langs']} &raquo;
<a href="index.php?page=lang&amp;add_fieldname=1&amp;main=1" target="main">{$lang['add_new_phrase']}</a>  </div>

<br />

<form action="index.php?page=lang&amp;add_fieldname=1&amp;start=1" method="post">
<table cellpadding="4" cellspacing="0" border="0" align="center" width="90%" style="border-collapse:separate" class="t_style_b" id="cpform_table">
<tr>
	<td class="main1" align="center" colspan="2">
		<b>{$lang['add_new_phrase']}</b>
	</td>
</tr>
<tr valign="top">
	<td class="row1">{$lang['phrase_type']}</td>
	<td class="row1"><div id="ctrl_fieldname"><select name="fieldname" id="sel_fieldname_1" tabindex="1" class="bginput">
		<option value="forum">Forum</option>
		<option value="admincp">Admincp</option>
</select></div>
</td>
</tr>
<tr valign="top">
	<td class="row1">{$lang['phrase_name']}</td>
	<td class="row1"><table cellpadding="0" cellspacing="0" border="0" width="100%"><tr valign="top"><td><div id="ctrl_varname">
	<input type="text" class="bginput" name="varname" id="it_varname_3" size="60" dir="ltr" tabindex="1" /></div></td></tr></table></td>
</tr>
<tr valign="top">
	<td class="row2">{$lang['phrase_text']}</td>
	<td class="row2"><table cellpadding="0" cellspacing="0" border="0" width="100%"><tr valign="top"><td>
	<textarea name="text" id="default_phrase" rows="5" cols="50" wrap="virtual" tabindex="1" dir="{$_CONF['info_row']['content_dir']}"></textarea></td></tr></table></td>
</tr>
<tr>
	<td class="main2" align="center" colspan="2">
		<b>{$lang['translations']}</b>
	</td>
</tr>
<tr valign="top">
	<td class="row1" colspan="2">
			<ul><li>{$lang['phrase_translation_desc_1']}</li>
			<li>{$lang['phrase_translation_desc_2']}</li>
			<li>{$lang['phrase_translation_desc_3']}</li></ul>
		</td>
</tr>
{Des::while}{LangList}
<tr valign="top">
	<td class="row1">{$lang['translate']}
	<b>{$LangList['lang_title']}</b> <dfn>{$lang['optional']}</dfn><div class="smallfont">&nbsp;</div></td>
	<td class="row1"><textarea name="text_{$LangList['id']}" id="text_{$LangList['id']}" rows="5" cols="50" tabindex="1" wrap="virtual" dir="ltr"></textarea></td>
</tr>
{/Des::while}
<tr>
	<td class="tfoot" colspan="2" align="center">	<input type="submit" id="submit0" class="button" tabindex="1" value="  {$lang['Save']}   " accesskey="s" />
</td>
</tr>
</table>

	<br />



	<br />

</form>