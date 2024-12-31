<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=options&amp;index=1">{$lang['mange_forum']}</a> &raquo;
 <a href="index.php?page=options&amp;general=1&amp;main=1">{$lang['General_Settings']}</a></div>

<br />

<form action="index.php?page=options&amp;general=1&amp;update=1"  name="myform" method="post">
<table cellpadding="3" cellspacing="1" width="80%" class="t_style_b" border="0" align="center">
<tr valign="top" align="center">
	<td class="main1" colspan="2">{$lang['General_Settings']}</td>
</tr>
<tr valign="top">
		<td class="row1">{$lang['Forum_Name']}</td>
		<td class="row1">
<input type="text" name="title" id="input_title" value="{$_CONF['info_row']['title']}" size="30" />
</td>
</tr>
<tr>
	<td class="row2">
	{$lang['pm_feature']}
	</td>
	<td class="row2">
		<select name="pm_feature">
		{if {$_CONF['info_row']['pm_feature']}}
			<option value="1" selected="selected">{$lang['yes']}</option>
			<option value="0">{$lang['no']}</option>
		{else}
			<option value="1">{$lang['yes']}</option>
			<option value="0" selected="selected">{$lang['no']}</option>
		{/if}
		</select>
	</td>
</tr>
<td class="row2">{$lang['members_send_pm']}</td>
		<td class="row2">
<input type="text" name="members_send_pm" id="input_members_send_pm" value="{$_CONF['info_row']['members_send_pm']}" size="1" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');">
</td>
</tr>
<tr>
	<td class="row2">
	{$lang['active_forum_online_number']}
	</td>
	<td class="row2">
		<select name="active_forum_online_number">
		{if {$_CONF['info_row']['active_forum_online_number']}}
			<option value="1" selected="selected">{$lang['yes']}</option>
			<option value="0">{$lang['no']}</option>
		{else}
			<option value="1">{$lang['yes']}</option>
			<option value="0" selected="selected">{$lang['no']}</option>
		{/if}
		</select>
	</td>
</tr>
<tr>
	<td class="row2">
	{$lang['no_moderators']}
	</td>
	<td class="row2">
		<select name="no_moderators">
		{if {$_CONF['info_row']['no_moderators']}}
			<option value="1" selected="selected">{$lang['yes']}</option>
			<option value="0">{$lang['no']}</option>
		{else}
			<option value="1">{$lang['yes']}</option>
			<option value="0" selected="selected">{$lang['no']}</option>
		{/if}
		</select>
	</td>
</tr>
	<tr>
	<td class="row2">
	{$lang['no_sub']}
	</td>
	<td class="row2">
		<select name="no_sub">
		{if {$_CONF['info_row']['no_sub']}}
			<option value="1" selected="selected">{$lang['yes']}</option>
			<option value="0">{$lang['no']}</option>
		{else}
			<option value="1">{$lang['yes']}</option>
			<option value="0" selected="selected">{$lang['no']}</option>
		{/if}
		</select>
	</td>
</tr>
	<tr>
	<td class="row2">
	{$lang['no_describe']}
	</td>
	<td class="row2">
		<select name="no_describe">
		{if {$_CONF['info_row']['no_describe']}}
			<option value="1" selected="selected">{$lang['yes']}</option>
			<option value="0">{$lang['no']}</option>
		{else}
			<option value="1">{$lang['yes']}</option>
			<option value="0" selected="selected">{$lang['no']}</option>
		{/if}
		</select>
	</td>
</tr>
	<tr>
	<td class="row2">
	{$lang['sub_columns_number']}
	</td>
	<td class="row2">
<input type="text" name="sub_columns_number" id="inputsub_columns_number" value="{$_CONF['info_row']['sub_columns_number']}" size="3" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');">
	</td>
</tr>
	<tr>
	<td class="row2">
	{$lang['Charset']}
	</td>
	<td class="row2">
<input type="text" name="charset" id="input_charset" value="{$_CONF['info_row']['charset']}" size="30" />
	</td>
</tr>
	<tr>
		<td class="row1">{$lang['content_dir']}</td>
		<td class="row1">
      <select name="content_dir" id="select_content_dir">
	{if {$_CONF['info_row']['content_dir']} != 'ltr'}
	<option value="rtl" selected="selected">{$lang['From_right_to_left']}</option>
	<option value="ltr">{$lang['From_left_to_right']}</option>
	{else}
	<option value="rtl">{$lang['From_right_to_left']}</option>
	<option value="ltr" selected="selected">{$lang['From_left_to_right']}</option>
	{/if}
	</select>
</tr>
	<tr>
	<td class="row2">
{$lang['content_language']}
	</td>
	<td class="row2">
<input type="text" name="content_language" id="input_content_language" value="{$_CONF['info_row']['content_language']}" size="3" maxlength="9" />
	</td>
</tr>
	<tr>
	<td class="row2">
{$lang['flood_search']}
	</td>
	<td class="row2">
<input type="text" name="flood_search" id="input_flood_search" value="{$_CONF['info_row']['flood_search']}" size="3" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');">

	</td>
</tr>
	<tr>
	<td class="row2">
{$lang['characters_keyword_search']}
	</td>
	<td class="row2">
<input type="text" name="characters_keyword_search" id="characters_keyword_search" value="{$_CONF['info_row']['characters_keyword_search']}" size="3" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');">

	</td>
</tr>
	<tr>
	<td class="row2">
{$lang['visitor_message_chars']}
	</td>
	<td class="row2">
<input type="text" name="visitor_message_chars" id="visitor_message_chars" value="{$_CONF['info_row']['visitor_message_chars']}" size="3" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');">
	</td>
</tr>
<tr valign="top">
			<td class="row1">
{$lang['active_visitor_message']}
			</td>
<td class="row1">
{if {$_CONF['info_row']['active_visitor_message']}}
<input name="active_visitor_message" value="1" id="active_visitor_message" type="radio" checked="checked">{$lang['yes']}
&nbsp;&nbsp;<input name="active_visitor_message" value="0" id="active_visitor_message" type="radio">{$lang['no']}
{else}
<input name="active_visitor_message" value="1" id="active_visitor_message" type="radio">{$lang['yes']}
&nbsp;&nbsp;<input name="active_visitor_message" value="0" id="active_visitor_message" type="radio" checked="checked">{$lang['no']}
{/if}
</td>
</tr>
<tr valign="top">
			<td class="row1">
{$lang['active_friend']}
			</td>
<td class="row1">
{if {$_CONF['info_row']['active_friend']}}
<input name="active_friend" value="1" id="active_friend" type="radio" checked="checked">{$lang['yes']}
&nbsp;&nbsp;<input name="active_friend" value="0" id="active_friend" type="radio">{$lang['no']}
{else}
<input name="active_friend" value="1" id="active_friend" type="radio">{$lang['yes']}
&nbsp;&nbsp;<input name="active_friend" value="0" id="active_friend" type="radio" checked="checked">{$lang['no']}
{/if}
</td>
</tr>
<tr valign="top">
			<td class="row1">
{$lang['active_archive']}
			</td>
<td class="row1">
{if {$_CONF['info_row']['active_archive']}}
<input name="active_archive" value="1" id="active_archive" type="radio" checked="checked">{$lang['yes']}
&nbsp;&nbsp;<input name="active_archive" value="0" id="active_archive" type="radio">{$lang['no']}
{else}
<input name="active_archive" value="1" id="active_archive" type="radio">{$lang['yes']}
&nbsp;&nbsp;<input name="active_archive" value="0" id="active_archive" type="radio" checked="checked">{$lang['no']}
{/if}
</td>
</tr>
<tr valign="top">
			<td class="row1">
{$lang['active_calendar']}
			</td>
<td class="row1">
{if {$_CONF['info_row']['active_calendar']}}
<input name="active_calendar" value="1" id="active_calendar" type="radio" checked="checked">{$lang['yes']}
&nbsp;&nbsp;<input name="active_calendar" value="0" id="active_calendar" type="radio">{$lang['no']}
{else}
<input name="active_calendar" value="1" id="active_calendar" type="radio">{$lang['yes']}
&nbsp;&nbsp;<input name="active_calendar" value="0" id="active_calendar" type="radio" checked="checked">{$lang['no']}
{/if}
</td>
</tr>
<tr valign="top">
			<td class="row1">
{$lang['active_send_admin_message']}
			</td>
<td class="row1">
{if {$_CONF['info_row']['active_send_admin_message']}}
<input name="active_send_admin_message" value="1" id="active_send_admin_message" type="radio" checked="checked">{$lang['yes']}
&nbsp;&nbsp;<input name="active_send_admin_message" value="0" id="active_send_admin_message" type="radio">{$lang['no']}
{else}
<input name="active_send_admin_message" value="1" id="active_send_admin_message" type="radio">{$lang['yes']}
&nbsp;&nbsp;<input name="active_send_admin_message" value="0" id="active_send_admin_message" type="radio" checked="checked">{$lang['no']}
{/if}
</td>
</tr>
<tr valign="top">
			<td class="row1">
{$lang['active_reply_today']}
			</td>
<td class="row1">
{if {$_CONF['info_row']['active_reply_today']}}
<input name="active_reply_today" value="1" id="active_reply_today" type="radio" checked="checked">{$lang['yes']}
&nbsp;&nbsp;<input name="active_reply_today" value="0" id="active_reply_today" type="radio">{$lang['no']}
{else}
<input name="active_reply_today" value="1" id="active_reply_today" type="radio">{$lang['yes']}
&nbsp;&nbsp;<input name="active_reply_today" value="0" id="active_reply_today" type="radio" checked="checked">{$lang['no']}
{/if}
</td>
</tr>
<tr valign="top">
			<td class="row1">
{$lang['active_subject_today']}
			</td>
<td class="row1">
{if {$_CONF['info_row']['active_subject_today']}}
<input name="active_subject_today" value="1" id="active_subject_today" type="radio" checked="checked">{$lang['yes']}
&nbsp;&nbsp;<input name="active_subject_today" value="0" id="active_subject_today" type="radio">{$lang['no']}
{else}
<input name="active_subject_today" value="1" id="active_subject_today" type="radio">{$lang['yes']}
&nbsp;&nbsp;<input name="active_subject_today" value="0" id="active_subject_today" type="radio" checked="checked">{$lang['no']}
{/if}
</td>
</tr>
<tr valign="top">
			<td class="row1">
{$lang['active_static']}
			</td>
<td class="row1">
{if {$_CONF['info_row']['active_static']}}
<input name="active_static" value="1" id="active_static" type="radio" checked="checked">{$lang['yes']}
&nbsp;&nbsp;<input name="active_static" value="0" id="active_static" type="radio">{$lang['no']}
{else}
<input name="active_static" value="1" id="active_static" type="radio">{$lang['yes']}
&nbsp;&nbsp;<input name="active_static" value="0" id="active_static" type="radio" checked="checked">{$lang['no']}
{/if}
</td>
</tr>
<tr valign="top">
			<td class="row1">
{$lang['active_team']}
			</td>
<td class="row1">
{if {$_CONF['info_row']['active_team']}}
<input name="active_team" value="1" id="active_team" type="radio" checked="checked">{$lang['yes']}
&nbsp;&nbsp;<input name="active_team" value="0" id="active_team" type="radio">{$lang['no']}
{else}
<input name="active_team" value="1" id="active_team" type="radio">{$lang['yes']}
&nbsp;&nbsp;<input name="active_team" value="0" id="active_team" type="radio" checked="checked">{$lang['no']}
{/if}
</td>
</tr>
<tr valign="top">
			<td class="row1">
{$lang['active_rss']}
			</td>
<td class="row1">
{if {$_CONF['info_row']['active_rss']}}
<input name="active_rss" value="1" id="active_rss" type="radio" checked="checked">{$lang['yes']}
&nbsp;&nbsp;<input name="active_rss" value="0" id="active_rss" type="radio">{$lang['no']}
{else}
<input name="active_rss" value="1" id="active_rss" type="radio">{$lang['yes']}
&nbsp;&nbsp;<input name="active_rss" value="0" id="active_rss" type="radio" checked="checked">{$lang['no']}
{/if}
</td>
</tr>
<tr valign="top">
			<td class="row1">
تفعيل ظهور المنتديات في قائمة الإنتقال السريع في اسفل صفحات المنتدى
<br />
{$lang['server_resource_consumption']}
			</td>
<td class="row1">
{if {$_CONF['info_row']['allowed_powered']}}
<input name="allowed_powered" value="1" id="allowed_powered" type="radio" checked="checked">{$lang['yes']}
&nbsp;&nbsp;<input name="allowed_powered" value="0" id="allowed_powered" type="radio">{$lang['no']}
{else}
<input name="allowed_powered" value="1" id="allowed_powered" type="radio">{$lang['yes']}
&nbsp;&nbsp;<input name="allowed_powered" value="0" id="allowed_powered" type="radio" checked="checked">{$lang['no']}
{/if}
</td>
</tr>
<tr valign="top" align="center">
<td class="main2" colspan="2">{$lang['description']}</td>
</tr>
<tr valign="top" align="center">
	<td class="row1" colspan="2">
<textarea name="description" id="textarea_description" rows="4" cols="50" wrap="virtual" dir="{$_CONF['info_row']['content_dir']}">{$_CONF['info_row']['description']}</textarea>
</td></tr>
<tr valign="top" align="center">
<td class="main2" colspan="2">{$lang['keywords']}</td>
</tr>
<tr valign="top" align="center">
	<td class="row1" colspan="2">
<textarea name="keywords" id="textarea_keywords" rows="4" cols="50" wrap="virtual" dir="{$_CONF['info_row']['content_dir']}">{$_CONF['info_row']['keywords']}</textarea>
</td></tr>
<tr valign="top" align="center">
	<td class="main2" colspan="2">{$lang['censorwords']}</td>
</tr>
<tr valign="top">
<td class="row1">{$lang['Make_every_word_in_a_line']}</td>
		<td class="row1">
<textarea name="censorwords" id="textarea_censorwords" rows="4" cols="50" wrap="virtual" dir="{$_CONF['info_row']['censorwords']}">{$_CONF['info_row']['censorwords']}</textarea>&nbsp;
</td>
</tr>
<tr valign="top" align="center">
<td class="main2" colspan="2">{$lang['rules']}</td>
</tr>
<tr valign="top" align="center">
	<td class="row1" colspan="2">
<link rel="stylesheet" href="../look/sceditor/minified/themes/default.min.css" id="theme-style" />
<script src="../look/sceditor/minified/sceditor.min.js"></script>
<script src="../look/sceditor/minified/formats/bbcode.js"></script>
<script src="../look/sceditor/languages/{$_CONF['info_row']['content_language']}.js"></script>
<textarea cols="50" id="text" name="rules" style="height:300px;width:77%;" dir="{$_CONF['info_row']['content_dir']}">
{$_CONF['info_row']['rules']}
</textarea>
{template}editor_js{/template}
</td>
</tr>
</table><br />
<div align="center">
	<input class="submit" type="submit" value="    {$lang['Save']}   " name="submit"  /></td>
</div>
<br />
</form>
