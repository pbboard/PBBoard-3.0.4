<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=options&amp;index=1">{$lang['mange_forum']}</a> &raquo;
 <a href="index.php?page=options&amp;avatar=1&amp;main=1">{$lang['Settings_Avatars']}</a></div>

<br />

<form action="index.php?page=options&amp;avatar=1&amp;update=1"  name="myform" method="post">
<table cellpadding="3" cellspacing="1" width="80%" class="t_style_b" border="0" align="center">
<tr valign="top" align="center">
	<td class="main1" colspan="2">{$lang['Settings_Avatars']}</td>
</tr>
<tr valign="top">
		<td class="row1">{$lang['allow_avatar']}</td>
		<td class="row1">
<select name="allow_avatar" id="select_allow_avatar">
	{if {$_CONF['info_row']['allow_avatar']}}
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
		<td class="row2">{$lang['upload_avatar']}</td>
		<td class="row2">
<select name="upload_avatar" id="select_upload_avatar">
	{if {$_CONF['info_row']['upload_avatar']}}
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
		<td class="row2">{$lang['ADD']}
		{$lang['photo_to_external_site']}</td>
		<td class="row2">
<select name="ajax_moderator_options" id="select_ajax_moderator_options">
	{if {$_CONF['info_row']['ajax_moderator_options']}}
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
		<td class="row1">{$lang['max_avatar_width']}</td>
		<td class="row1">
<input type="text" name="max_avatar_width" id="input_max_avatar_width" value="{$_CONF['info_row']['max_avatar_width']}" size="3" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');">

</td>
</tr>
<tr valign="top">
		<td class="row2">{$lang['max_avatar_height']}</td>
		<td class="row2">

<input type="text" name="max_avatar_height" id="input_max_avatar_height" value="{$_CONF['info_row']['max_avatar_height']}" size="3" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');">

</td>
</tr>
<tr valign="top">
			<td class="row1">
{$lang['avatar_columns_number']}
			</td>
			<td class="row1">
<input type="text" name="avatar_columns_number" id="avatar_columns_number" value="{$_CONF['info_row']['avatar_columns_number']}" size="1" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');">

			</td>
		</tr>
</table><br />
<div align="center">
<table cellpadding="3" cellspacing="1" width="80%" class="t_style_b" border="0" align="center">
		<tr align="center">
			<td width="60%" class="main1" colspan="2">
				<span lang="ar-sa">{$lang['default_avatar']}</span>
			</td>
		</tr>
		<tr>
			<td width="30%" class="row1">
				{$lang['input_default_avatar']}
			</td>
			<td width="60%" class="row1">
				<input name="default_avatar" id="input_default_avatar" dir="ltr" value="{$_CONF['info_row']['default_avatar']}" />
				<br />
				{$lang['default_avatar']} : <b><font color="#800000">default_avatar.gif</font></b>
			</td>
		</tr>
	</table>
</div>
<br />

<tr>
	<td class="submit-buttons" colspan="2" align="center">
	<input class="submit" type="submit" value="{$lang['acceptable']}" name="submit" accesskey="s" /></td>
</tr>
</table><br />
</form>
