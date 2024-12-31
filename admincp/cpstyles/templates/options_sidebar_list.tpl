<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=options&amp;index=1">{$lang['mange_forum']}</a> &raquo;
 <a href="index.php?page=options&amp;sidebar_list=1&amp;main=1">{$lang['sidebar_list_settings']}</a></div>

<br />

<form action="index.php?page=options&amp;sidebar_list=1&amp;update=1"  name="myform" method="post">
<table cellpadding="3" cellspacing="1" width="80%" class="t_style_b" border="0" align="center">
<tr valign="top" align="center">
	<td class="main1" colspan="2">{$lang['sidebar_list_settings']}</td>
</tr>
<tr valign="top">
		<td class="row2">{$lang['sidebar_list_active']}</td>
		<td class="row1">
<select name="sidebar_list_active" id="sidebar_list_active">
	{if {$_CONF['info_row']['sidebar_list_active']}}
		<option value="1" selected="selected">{$lang['yes']}</option>
		<option value="0">{$lang['no']}</option>
	{else}
		<option value="0" selected="selected">{$lang['no']}</option>
		<option value="1">{$lang['yes']}</option>
	{/if}
</select>
</td>
</tr>
<tr valign="top">
		<td class="row1">{$lang['sidebar_list_align']}</td>
		<td class="row2">
<select name="sidebar_list_align" id="sidebar_list_align">
	{if {$_CONF['info_row']['sidebar_list_align']}== 'left'}
		<option value="left" selected="selected">{$lang['left']}</option>
		<option value="right">{$lang['right']}</option>
	{else}
		<option value="right" selected="selected">{$lang['right']}</option>
		<option value="left">{$lang['left']}</option>
	{/if}
</select>
</td>
</tr>
<tr valign="top">
		<td class="row1">{$lang['sidebar_list_width']}</td>
		<td class="row1">
<input type="text" name="sidebar_list_width" id="sidebar_list_width" value="{$_CONF['info_row']['sidebar_list_width']}" dir="ltr" size="2" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');">
</td>
</tr>
 <tr valign="top">
		<td class="row1"> {$lang['sidebar_list_exclusion_forums']}</td>
		<td class="row1">
<input type="text" name="sidebar_list_exclusion_forums" id="sidebar_list_exclusion_forums" dir="ltr" value="{$_CONF['info_row']['sidebar_list_exclusion_forums']}" size="30" />
</td>
</tr>

 <tr valign="top">
		<td class="row1" width="20%">{$lang['sidebar_list_pages']}</td>
		<td class="row1" width="60%">
<table border="0" width="80%" id="table1" cellpadding="0" style="border-collapse: collapse">
	<tr>
		<td width="10%"><textarea name="sidebar_list_pages" rows="10" dir="ltr" cols="10">{$_CONF['info_row']['sidebar_list_pages']}</textarea></td>
		<td width="70%">{$lang['sidebar_list_pages_info']}</td>
	</tr>
</table>
</td>
</tr>
 <tr valign="top">
		<td class="row1" colspan="2" align="center">
		<b>{$lang['sidebar_list_content']}</b>
		<br />
		{$lang['Writable_Bocuad_Html']}
		<br />
<textarea name="sidebar_list_content" rows="20" cols="50" dir="ltr">{$_CONF['info_row']['sidebar_list_content']}</textarea>
</td>
</tr>
<tr>
	<td class="row1" colspan="2" align="center">
	<input class="submit" type="submit" value="{$lang['acceptable']}" name="submit" accesskey="s" />
	<span class="l-left"><input class="submit" type="submit" value="{$lang['restore_defaults']}" name="submit" accesskey="s" /></span>
	</td>
</tr>
</table><br />
</form>