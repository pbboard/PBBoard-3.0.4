<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
{$lang['langs']} &raquo;
<a href="index.php?page=lang&amp;search_fieldname=1&amp;main=1" target="main">{$lang['search_in_phrases']}</a>  </div>

<br />

<form action="index.php?page=lang&amp;search_fieldname=1&amp;start=1" method="post">

<table cellpadding="4" cellspacing="0" border="0" align="center" width="90%" style="border-collapse:separate" class="t_style_b" id="cpform_table">
<tr>
	<td class="main1" align="center" colspan="2">
		<b>{$lang['search_in_phrases']}</b>
	</td>
</tr>
<tr valign="top">
	<td class="row1">{$lang['search_for_text']}</td>
	<td class="row1"><table cellpadding="0" cellspacing="0" border="0" width="100%"><tr valign="top"><td>
	<div id="ctrl_searchstring"><input type="text" class="bginput" name="searchstring" id="it_searchstring_1" value="" size="50" dir="rtl" tabindex="1" /></div></td></tr></table></td>
</tr>
<tr valign="top">
	<td class="row2">{$lang['search_in_language']}</td>
	<td class="row2"><table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr valign="top">
	<td><div id="ctrl_languageid">
	<select name="languageid" id="sel_languageid_2" tabindex="1" class="bginput">
		<option value="all" selected="selected">{$lang['all_languages']}</option>
		<optgroup label="{$lang['translations']}">
{Des::while}{LangList}
		<option value="{$LangList['id']}">
		{$LangList['lang_title']}</option>
{/Des::while}
		</optgroup>
</select></div>
</td></tr></table></td>
</tr>
<tr valign="top">
	<td class="row2">{$lang['search_in']}</td>
	<td class="row2"><table cellpadding="0" cellspacing="0" border="0" width="100%"><tr valign="top"><td>
		<label for="rb_sw_0">
		<input type="radio" name="searchwhere" id="rb_sw_0" value="0" tabindex="1" checked="checked" />{$lang['phrase_text_only']}</label><br />
		<label for="rb_sw_1"><input type="radio" name="searchwhere" id="rb_sw_1" value="1" tabindex="1" />{$lang['phrase_name_only']}</label><br />
		</td></tr></table></td>
</tr>
<tr valign="top">
	<td class="row1">{$lang['exact_match']}</td>
	<td class="row1"><div id="ctrl_exactmatch" class="smallfont" style="white-space:nowrap">
		<label for="rb_1_exactmatch_7"><input type="radio" name="exactmatch" id="rb_1_exactmatch_7" value="1" tabindex="1" />{$lang['yes']}</label>
		<label for="rb_0_exactmatch_7"><input type="radio" name="exactmatch" id="rb_0_exactmatch_7" value="0" tabindex="1" checked="checked" />{$lang['no']}</label>
	</div></td>
</tr>
<tr>
	<td class="tfoot" colspan="2" align="center">	<input type="submit" id="submit0" class="button" tabindex="1" value=" {$lang['search']}  " accesskey="s" />
</td>
</tr>
</table>

	<br />



	<br />

</form>