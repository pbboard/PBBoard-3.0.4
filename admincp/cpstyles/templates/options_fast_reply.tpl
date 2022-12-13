<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=options&amp;index=1">{$lang['mange_forum']}</a> &raquo;
 <a href="index.php?page=options&amp;fast_reply=1&amp;main=1">{$lang['Settings_fastreply']}</a></div>

<br />

<form action="index.php?page=options&amp;fast_reply=1&amp;update=1"  name="myform" method="post">
<table cellpadding="3" cellspacing="1" width="80%" class="t_style_b" border="0" align="center">
<tr valign="top" align="center">
	<td class="main1" colspan="2">اعدادات الرد السريع</td>
</tr>
<tr valign="top">
		<td class="row1">{$lang['fastreply_allow']}</td>
		<td class="row1">
<select name="fastreply_allow" id="select_fastreply_allow">
	{if {$_CONF['info_row']['fastreply_allow']}}
		<option value="1" selected="selected">{$lang['yes']}</option>
		<option value="0">{$lang['no']}</option>
	{else}
		<option value="1">{$lang['yes']}</option>
		<option value="0" selected="selected">{$lang['no']}</option>
	{/if}
</select>
</td>
</tr>
<tr valign="top">
		<td class="row2">{$lang['toolbox_show']}</td>
		<td class="row2">
<select name="toolbox_show" id="select_toolbox_show">
	{if {$_CONF['info_row']['toolbox_show']}}
		<option value="1" selected="selected">{$lang['yes']}</option>
		<option value="0">{$lang['no']}</option>
	{else}
		<option value="1">{$lang['yes']}</option>
		<option value="0" selected="selected">{$lang['no']}</option>
	{/if}</select>
</td>
</tr>
<tr valign="top">
		<td class="row1">{$lang['smiles_show']}</td>
		<td class="row1">
<select name="smiles_show" id="select_smiles_show">
	{if {$_CONF['info_row']['smiles_show']}}
		<option value="1" selected="selected">{$lang['yes']}</option>
		<option value="0">{$lang['no']}</option>
	{else}
		<option value="1">{$lang['yes']}</option>
		<option value="0" selected="selected">{$lang['no']}</option>
	{/if}
</select></td>
</tr>
<tr valign="top">
		<td class="row2">{$lang['icons_show']}</td>
		<td class="row2">
<select name="icons_show" id="select_icons_show">
	{if {$_CONF['info_row']['icons_show']}}
		<option value="1" selected="selected">{$lang['yes']}</option>
		<option value="0">{$lang['no']}</option>
	{else}
		<option value="1">{$lang['yes']}</option>
		<option value="0" selected="selected">{$lang['no']}</option>
	{/if}
</select>
</td></tr>
<tr valign="top">
		<td class="row1">{$lang['title_quote']}</td>
		<td class="row1">
<select name="title_quote" id="select_title_quote">
	{if {$_CONF['info_row']['title_quote']}}
		<option value="1" selected="selected">{$lang['yes']}</option>
		<option value="0">{$lang['no']}</option>
	{else}
		<option value="1">{$lang['yes']}</option>
		<option value="0" selected="selected">{$lang['no']}</option>
	{/if}
</select>
</td>
</tr><tr valign="top">
		<td class="row2">{$lang['activate_closestick']}</td>
		<td class="row2">
<select name="activate_closestick" id="select_activate_closestick">
	{if {$_CONF['info_row']['activate_closestick']}}
		<option value="1" selected="selected">{$lang['yes']}</option>
		<option value="0">{$lang['no']}</option>
	{else}
		<option value="1">{$lang['yes']}</option>
		<option value="0" selected="selected">{$lang['no']}</option>
	{/if}
</select>
</td>
</tr>
<tr valign="top">
		<td class="row2" colspan="2" align="center">
	<input class="submit" type="submit" value="{$lang['acceptable']}" name="submit" accesskey="s" /></td>
</tr>
</table><br />
<br />
</form>