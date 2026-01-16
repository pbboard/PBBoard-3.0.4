<br />
<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=options&amp;index=1">{$lang['mange_forum']}</a> &raquo;
<a href="index.php?page=options&amp;allgeneral=1&amp;main=1">{$lang['shwo_all_generals']}</a></div>
<br />
<form action="index.php?page=options&amp;allgeneral=1&amp;update=1"  name="myform" method="post">
<table cellspacing="5" width="80%" border="0" align="right" class="t_style_c">

<tr valign="top" align="center">
	<td class="main2" colspan="2">{$lang['General_Settings']}</td>
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
<tr>
<td class="row2">{$lang['members_send_pm']}</td>
		<td class="row2">
<input type="text" name="members_send_pm" id="input_members_send_pm" value="{$_CONF['info_row']['members_send_pm']}" size="1" dir="ltr" size="1" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');" maxlength="2" />
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
تفعيل مشاهدة اسماء المنتديات الفرعية المستوى الثاني اسفل الأباء
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
	{$lang['sub_columns_number']}
	</td>
	<td class="row2">
<input type="text" name="sub_columns_number" id="input_sub_columns_number" value="{$_CONF['info_row']['sub_columns_number']}" size="1" dir="ltr" size="1" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');" maxlength="3" />
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
<input type="text" name="content_language" id="input_content_language" value="{$_CONF['info_row']['content_language']}" size="1" dir="ltr" size="1" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');" maxlength="9" />
	</td>
</tr>
<tr>
	<td class="row2">
{$lang['flood_search']}
	</td>
	<td class="row2">
<input type="text" name="flood_search" id="input_flood_search" value="{$_CONF['info_row']['flood_search']}" size="1" dir="ltr" size="1" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');" maxlength="3" />
	</td>
</tr>
	<tr>
	<td class="row2">
{$lang['characters_keyword_search']}
	</td>
	<td class="row2">
<input type="text" name="characters_keyword_search" id="characters_keyword_search" value="{$_CONF['info_row']['characters_keyword_search']}" size="1" dir="ltr" size="1" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');" maxlength="2" />
	</td>
</tr>
	<tr>
	<td class="row2">
{$lang['visitor_message_chars']}
	</td>
	<td class="row2">
<input type="text" name="visitor_message_chars" id="visitor_message_chars" value="{$_CONF['info_row']['visitor_message_chars']}" size="7" size="1" dir="ltr" size="1" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');" maxlength="4" />
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
<tr valign="top">
<td class="row1">
إظهار إحصائيات الأداء (<b>Debug Info</b>)
<br />
<font size="1">عرض معلومات استهلاك الذاكرة، عدد الاستعلامات، ووقت تحميل الصفحات وعدد الملفات المستدعاة أسفل المنتدى
<b>للمديرين فقط</b>.</font>
<br />
<small><u>يُنصح بتعطيله في المواقع الكبيرة لتقليل استهلاك الموارد، وتفعيله فقط عند الحاجة لمراقبة الأداء.</u></small>
</td>
<td class="row1">
{if {$_CONF['info_row']['show_debug_info']}}
<input name="show_debug_info" value="1" id="show_debug_info" type="radio" checked="checked">{$lang['yes']}
&nbsp;&nbsp;<input name="show_debug_info" value="0" id="show_debug_info" type="radio">{$lang['no']}
{else}
<input name="show_debug_info" value="1" id="show_debug_info" type="radio">{$lang['yes']}
&nbsp;&nbsp;<input name="show_debug_info" value="0" id="show_debug_info" type="radio" checked="checked">{$lang['no']}
{/if}
</td>
</tr>

<tr valign="top" align="center">
<td class="main2" colspan="2">{$lang['description']}</td>
</tr>
<tr valign="top" align="center">
	<td class="row1" colspan="2">
<textarea name="description" id="textarea_description" rows="4" cols="70" wrap="virtual" dir="{$_CONF['info_row']['content_dir']}">{$_CONF['info_row']['description']}</textarea>
</td></tr>
<tr valign="top" align="center">
<td class="main2" colspan="2">{$lang['keywords']}</td>
</tr>
<tr valign="top" align="center">
	<td class="row1" colspan="2">
<textarea name="keywords" id="textarea_keywords" rows="4" cols="70" wrap="virtual" dir="{$_CONF['info_row']['content_dir']}">{$_CONF['info_row']['keywords']}</textarea>
</td></tr>
<tr valign="top" align="center">
	<td class="main2" colspan="2">{$lang['censorwords']}</td>
</tr>
<tr valign="top">
<td class="row1">{$lang['Make_every_word_in_a_line']}</td>
		<td class="row1">
<textarea name="censorwords" id="textarea_censorwords" rows="4" cols="57" wrap="virtual" dir="{$_CONF['info_row']['censorwords']}">{$_CONF['info_row']['censorwords']}</textarea>&nbsp;
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

		<tr align="center">
			<td class="main2" colspan="2">
{$lang['manage_human_verification']}
			</td>
		</tr>
<tr valign="top">
		<td class="row2">{$lang['activate_captcha_o']}</td>
		<td class="row2">
<select name="captcha_o" id="select_captcha_o">
	{if {$_CONF['info_row']['captcha_o']}}
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
		<td class="row2">{$lang['choose_type_verify_human']}
<br />
<br />
{$lang['help_verification_question_and_answer']}
<br />
		</td>
		<td class="row2">
<select name="captcha_type" id="select_captcha_type">
	{if {$_CONF['info_row']['captcha_type']} == 'captcha_IMG'}
	<option value="captcha_IMG" selected="selected">{$lang['image_verification']}</option>
	<option value="captcha_Q_A">{$lang['verification_question_and_answer']}</option>
	{else}
	<option value="captcha_Q_A" selected="selected">{$lang['verification_question_and_answer']}</option>
	<option value="captcha_IMG">{$lang['image_verification']}</option>
	{/if}
</select>
</td>
</tr>
<tr valign="top">
		<td class="row2" colspan="2">
{$lang['each_question_offset_answer']}
	<br />
	<br />
<table border="0" width="80%" cellpadding="0" style="border-collapse: collapse" align="center">
	<tr>
		<td>
		<div align="center">{$lang['questions']}<div/>
<textarea name="questions" rows="5" cols="50">{$_CONF['info_row']['questions']}</textarea>

<div align="center">{$lang['answers']}<div/>
<textarea name="answers" rows="5" cols="50">{$_CONF['info_row']['answers']}</textarea>
</td>
	</tr>
</table>
<br />
</td>
</tr>


<tr valign="top" align="center">
	<td class="main2" colspan="2">{$lang['Time_Settings']}</td>
</tr>
<tr valign="top">
		<td class="row1">{$lang['Forum_Time']}</td>
		<td class="row1">
<select name="time_stamp" id="select_time_stamp">
	<option {if {$_CONF['info_row']['timestamp']} == '-43200'} selected="selected" {/if} value="-43200" >GMT - 12</option>
	<option {if {$_CONF['info_row']['timestamp']} == '-39600'} selected="selected" {/if} value="-39600" >GMT - 11</option>
	<option {if {$_CONF['info_row']['timestamp']} == '-36000'} selected="selected" {/if} value="-36000" >GMT - 10</option>
	<option {if {$_CONF['info_row']['timestamp']} == '-32400'} selected="selected" {/if} value="-32400" >GMT - 9</option>
	<option {if {$_CONF['info_row']['timestamp']} == '-28800'} selected="selected" {/if} value="-28800" >GMT - 8</option>
	<option {if {$_CONF['info_row']['timestamp']} == '-25200'} selected="selected" {/if} value="-25200" >GMT - 7</option>
	<option {if {$_CONF['info_row']['timestamp']} == '-21600'} selected="selected" {/if} value="-21600" >GMT - 6</option>
	<option {if {$_CONF['info_row']['timestamp']} == '-18000'} selected="selected" {/if} value="-18000" >GMT - 5</option>
	<option {if {$_CONF['info_row']['timestamp']} == '-14400'} selected="selected" {/if} value="-14400" >GMT - 4</option>
	<option {if {$_CONF['info_row']['timestamp']} == '-10800'} selected="selected" {/if} value="-10800" >GMT - 3</option>
	<option {if {$_CONF['info_row']['timestamp']} == '-7200'} selected="selected" {/if} value="-7200" >GMT - 2</option>
	<option {if {$_CONF['info_row']['timestamp']} == '-3600'} selected="selected" {/if} value="-3600" >GMT - 1</option>
	<option {if {$_CONF['info_row']['timestamp']} == '0000'} selected="selected" {/if} value="0000">GMT 0</option>
	<option {if {$_CONF['info_row']['timestamp']} == '+3600'} selected="selected" {/if} value="+3600" >GMT + 1</option>
	<option {if {$_CONF['info_row']['timestamp']} == '+7200'} selected="selected" {/if} value="+7200" >GMT + 2</option>
	<option {if {$_CONF['info_row']['timestamp']} == '+10800'} selected="selected" {/if} value="+10800" >GMT + 3</option>
	<option {if {$_CONF['info_row']['timestamp']} == '+14400'} selected="selected" {/if} value="+14400" >GMT + 4</option>
	<option {if {$_CONF['info_row']['timestamp']} == '+18000'} selected="selected" {/if} value="+18000" >GMT + 5</option>
	<option {if {$_CONF['info_row']['timestamp']} == '+21600'} selected="selected" {/if} value="+21600" >GMT + 6</option>
	<option {if {$_CONF['info_row']['timestamp']} == '+25200'} selected="selected" {/if} value="+25200" >GMT + 7</option>
	<option {if {$_CONF['info_row']['timestamp']} == '+28800'} selected="selected" {/if} value="+28800" >GMT + 8</option>
	<option {if {$_CONF['info_row']['timestamp']} == '+32400'} selected="selected" {/if} value="+32400" >GMT + 9</option>
	<option {if {$_CONF['info_row']['timestamp']} == '+36000'} selected="selected" {/if} value="+36000" >GMT + 10</option>
	<option {if {$_CONF['info_row']['timestamp']} == '+39600'} selected="selected" {/if} value="+39600" >GMT + 11</option>
	<option {if {$_CONF['info_row']['timestamp']} == '+43200'} selected="selected" {/if} value="+43200" >GMT + 12</option>
</select>
</td>
</tr>
<tr valign="top">
		<td class="row1">{$lang['Forum_timeoffset']}</td>
		<td class="row2">
<input type="text" name="time_offset" id="select_time_offset" value="{$_CONF['info_row']['timeoffset']}" size="30" />
</td>
</tr>
<tr valign="top">
		<td class="row2">{$lang['Forum_timeformat']}</td>
		<td class="row2">
<input type="text" name="time_system" id="select_time_system" value="{$_CONF['info_row']['timesystem']}" size="10" />
</td>
</tr>
<tr valign="top">
		<td class="row2">{$lang['Forum_dateformat']}</td>
		<td class="row2">
<input type="text" name="date_system" id="select_date_system" value="{$_CONF['info_row']['datesystem']}" size="10" />
</td>
</tr>
<tr valign="top" align="center">
	<td class="main2" colspan="2">{$lang['pages_Settings']}</td>
</tr>
<tr valign="top">
		<td class="row1">{$lang['page_max']}</td>
		<td class="row1">
<input type="text" name="page_max" id="input_page_max" value="{$_CONF['info_row']['page_max']}" size="1" dir="ltr" size="1" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');" maxlength="2" />&nbsp;
</td>
</tr>
<tr valign="top">
<td class="row2">{$lang['subject_perpage']}</td>
		<td class="row2">
<input type="text" name="subject_perpage" id="input_subject_perpage" value="{$_CONF['info_row']['subject_perpage']}" size="1" dir="ltr" size="1" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');" maxlength="2" />&nbsp;
</td>
</tr>
<tr valign="top">
<td class="row1">{$lang['reply_perpage']}</td>
		<td class="row1">
<input type="text" name="reply_perpage" id="input_reply_perpage" value="{$_CONF['info_row']['perpage']}" size="1" dir="ltr" size="1" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');" maxlength="2" />&nbsp;
</td>
</tr><tr valign="top">
		<td class="row1">{$lang['avatar_perpage']}</td>
		<td class="row1">
<input type="text" name="avatar_perpage" id="input_avatar_perpage" value="{$_CONF['info_row']['avatar_perpage']}" size="1" dir="ltr" size="1" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');" maxlength="3" />&nbsp;
</td>
</tr>
<tr valign="top" align="center">
	<td class="main2" colspan="2">{$lang['reg_Settings']}</td>
</tr>
<tr valign="top">
		<td class="row1">{$lang['reg_close']}</td>
		<td class="row1">
<select name="reg_close" id="select_reg_close">
	{if {$_CONF['info_row']['reg_close']}}
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
		<td class="row2">{$lang['reg_def_group']}</td>
		<td class="row2">
<select name="def_group" id="select_def_group">
	{Des::while}{GroupList}
	<option value="{$GroupList['id']}" {if {$_CONF['info_row']['def_group']} == {$GroupList['id']}}selected="selected"{/if}>{$GroupList['title']}</option>
	{/Des::while}
</select>
</td>
</tr>
<tr valign="top">
		<td class="row1">{$lang['reg_adef_group']}</td>
		<td class="row1">
<select name="adef_group" id="select_adef_group">
	{Des::while}{GroupList}
	<option value="{$GroupList['id']}" {if {$_CONF['info_row']['adef_group']} == {$GroupList['id']}}selected="selected"{/if}>{$GroupList['title']}</option>
	{/Des::while}
</select>
</td></tr>
<tr valign="top">
		<td class="row2">{$lang['activate_reg_o']}</td>
		<td class="row2">
<select name="reg_o" id="select_reg_o">
	{if {$_CONF['info_row']['reg_o']}}
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
		<td class="row2">{$lang['active_birth_date']}</td>
		<td class="row2">
<select name="active_birth_date" id="select_active_birth_date">
	{if {$_CONF['info_row']['active_birth_date']}}
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
		<td class="row1">{$lang['reg_less_num']}</td>
		<td class="row1">
<input type="text" name="reg_less_num" id="input_reg_less_num" value="{$_CONF['info_row']['reg_less_num']}" dir="ltr" size="1" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');" />&nbsp;
</td>
</tr>
<tr valign="top">
		<td class="row2">{$lang['reg_max_num']}</td>
		<td class="row2">
<input type="text" name="reg_max_num" id="input_reg_max_num" value="{$_CONF['info_row']['reg_max_num']}" dir="ltr" size="1" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');" />&nbsp;
</td></tr>
<tr valign="top">
		<td class="row1">{$lang['reg_pass_min_num']}</td>
		<td class="row1">
<input type="text" name="reg_pass_min_num" id="input_reg_pass_min_num" value="{$_CONF['info_row']['reg_pass_min_num']}" dir="ltr" size="1" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');" />&nbsp;
</td>
</tr>
<tr valign="top">
		<td class="row2">{$lang['reg_pass_max_num']}</td>
		<td class="row2">
<input type="text" name="reg_pass_max_num" id="input_reg_pass_max_num" value="{$_CONF['info_row']['reg_pass_max_num']}" dir="ltr" size="1" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');" />&nbsp;</td>
</tr>
<tr valign="top" align="center">
	<td class="main2" colspan="2">{$lang['Days_allowed_for_visitors_to_register_on']}</td>
</tr>
<tr valign="top">
		<td class="row1">{$lang['Sat']}</td>
		<td class="row1">
<select name="Sat" id="select_Sat">
	{if {$_CONF['info_row']['reg_Sat']}}
		<option value="1" selected="selected">{$lang['Allowed']}</option>

		<option value="0">{$lang['Not_Allowed']}</option>

	{else}
		<option value="1">{$lang['Allowed']}</option>

		<option value="0" selected="selected">{$lang['Not_Allowed']}</option>

	{/if}
</select>
</td>
</tr>
<tr valign="top">
		<td class="row2">{$lang['Sun']}</td>
		<td class="row2">
<select name="Sun" id="select_Sun">
	{if {$_CONF['info_row']['reg_Sun']}}
		<option value="1" selected="selected">{$lang['Allowed']}</option>

		<option value="0">{$lang['Not_Allowed']}</option>

	{else}
		<option value="1">{$lang['Allowed']}</option>

		<option value="0" selected="selected">{$lang['Not_Allowed']}</option>

	{/if}</select>
</td>
</tr>
<tr valign="top">
		<td class="row1">{$lang['Mon']}</td>
		<td class="row1">
<select name="Mon" id="select_Mon">
	{if {$_CONF['info_row']['reg_Mon']}}
		<option value="1" selected="selected">{$lang['Allowed']}</option>

		<option value="0">{$lang['Not_Allowed']}</option>

	{else}
		<option value="1">{$lang['Allowed']}</option>

		<option value="0" selected="selected">{$lang['Not_Allowed']}</option>

	{/if}
</select></td>
</tr>
<tr valign="top">
		<td class="row2">{$lang['Tue']}</td>
		<td class="row2">
<select name="Tue" id="select_Tue">
	{if {$_CONF['info_row']['reg_Tue']}}
		<option value="1" selected="selected">{$lang['Allowed']}</option>

		<option value="0">{$lang['Not_Allowed']}</option>

	{else}
		<option value="1">{$lang['Allowed']}</option>

		<option value="0" selected="selected">{$lang['Not_Allowed']}</option>

	{/if}
</select>
</td></tr>
<tr valign="top">
		<td class="row1">{$lang['Wed']}</td>
		<td class="row1">
<select name="Wed" id="select_Wed">
	{if {$_CONF['info_row']['reg_Wed']}}
		<option value="1" selected="selected">{$lang['Allowed']}</option>

		<option value="0">{$lang['Not_Allowed']}</option>

	{else}
		<option value="1">{$lang['Allowed']}</option>

		<option value="0" selected="selected">{$lang['Not_Allowed']}</option>

	{/if}
</select>
</td>
</tr><tr valign="top">
		<td class="row2">{$lang['Thu']}</td>
		<td class="row2">
<select name="Thu" id="select_Thu">
	{if {$_CONF['info_row']['reg_Thu']}}
		<option value="1" selected="selected">{$lang['Allowed']}</option>
		<option value="0">{$lang['Not_Allowed']}</option>
	{else}
		<option value="1">{$lang['Allowed']}</option>

		<option value="0" selected="selected">{$lang['Not_Allowed']}</option>

	{/if}
</select>
</td>
</tr>
<tr valign="top">
<td class="row1">{$lang['Fri']}</td>
		<td class="row1">
<select name="Fri" id="select_Fri">
	{if {$_CONF['info_row']['reg_Fri']}}
		<option value="1" selected="selected">{$lang['Allowed']}</option>

		<option value="0">{$lang['Not_Allowed']}</option>

	{else}
		<option value="1">{$lang['Allowed']}</option>

		<option value="0" selected="selected">{$lang['Not_Allowed']}</option>

	{/if}
</select>
</td>
</tr>
<tr valign="top" align="center">
	<td class="main2" colspan="2">
{$lang['welcome_message_text_settings']}
	</td>
</tr>
<tr valign="top">
		<td class="row1">
{$lang['activ_welcome_message']}
		</td>
		<td class="row1">
<select name="activ_welcome_message" id="select_activ_welcome_message">
	{if {$_CONF['info_row']['activ_welcome_message']}}
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
		<td class="row2">
{$lang['send_welcome_message_to']}
		</td>
		<td class="row2">
<select name="welcome_message_mail_or_private" id="select_welcome_message_mail_or_private">
	{if {$_CONF['info_row']['welcome_message_mail_or_private']} == '1'}
		<option value="1" selected="selected">{$lang['email']}</option>
		<option value="2">{$lang['private_messages']}</option>
		<option value="3">{$lang['mail_and_private_messages']}</option>
	{elseif {$_CONF['info_row']['welcome_message_mail_or_private']} == '2'}
		<option value="1">{$lang['email']}</option>
		<option value="3">{$lang['mail_and_private_messages']}</option>
		<option value="2" selected="selected">{$lang['private_messages']}</option>
	{elseif {$_CONF['info_row']['welcome_message_mail_or_private']} == '3'}
		<option value="1">{$lang['email']}</option>
		<option value="3" selected="selected">{$lang['mail_and_private_messages']}</option>
		<option value="2">{$lang['private_messages']}</option>
	{/if}
</select>
</td>
</tr>
<tr valign="top" align="center">
<td class="main2" colspan="2">
{$lang['welcome_message']}
</td>
</tr>
<tr valign="top" align="center">
	<td class="row1" colspan="2">
<textarea name="welcome_message_text" id="textarea_welcome_message_text" rows="4" cols="70" wrap="virtual" dir="{$_CONF['info_row']['content_dir']}">
{$_CONF['info_row']['welcome_message_text']}</textarea>
</td></tr>
<tr valign="top" align="center">
	<td class="main2" colspan="2">{$lang['Settings_threads_and_replies']}</td>
</tr>
<tr valign="top">
		<td class="row1">{$lang['post_text_min']}</td>
		<td class="row1">
<input type="text" name="post_text_min" id="input_post_text_min" value="{$_CONF['info_row']['post_text_min']}" size="1" dir="ltr" size="1" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');" maxlength="3" />&nbsp;
</td>
</tr>
<tr valign="top">

		<td class="row2">{$lang['post_text_max']}</td>
		<td class="row2">
<input type="text" name="post_text_max" id="input_post_text_max" value="{$_CONF['info_row']['post_text_max']}" size="4" dir="ltr" size="1" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');" maxlength="5" />&nbsp;
</td>
</tr>
<tr valign="top">
		<td class="row1">{$lang['post_title_min']}</td>
		<td class="row1">
<input type="text" name="post_title_min" id="input_post_title_min" value="{$_CONF['info_row']['post_title_min']}" size="1" dir="ltr" size="1" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');" maxlength="1" />&nbsp;
</td>
</tr>

<tr valign="top">
		<td class="row2">{$lang['post_title_max']}</td>
		<td class="row2">
<input type="text" name="post_title_max" id="input_post_title_max" value="{$_CONF['info_row']['post_title_max']}" size="1" dir="ltr" size="1" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');" maxlength="2" />&nbsp;
</td>
</tr>
<tr valign="top">
		<td class="row1">{$lang['time_out']}</td>
		<td class="row1">
<input type="text" name="time_out" id="input_time_out" value="{$_CONF['info_row']['time_out']}" size="4" dir="ltr" size="1" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');" maxlength="6" />&nbsp;
</td>

</tr>
<tr valign="top">
		<td class="row2">{$lang['floodctrl']}</td>
		<td class="row2">
<input type="text" name="floodctrl" id="input_floodctrl" value="{$_CONF['info_row']['floodctrl']}" size="4" dir="ltr" size="1" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');" maxlength="6" />&nbsp;
</td>
</tr>
<tr valign="top">
		<td class="row1">{$lang['samesubject_show']}</td>
		<td class="row1">
<select name="samesubject_show" id="select_samesubject_show">
	{if {$_CONF['info_row']['samesubject_show']}}
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
		<td class="row1">{$lang['subject_describe_show']}</td>
		<td class="row1">
<select name="subject_describe_show" id="select_subject_describe_show">
	{if {$_CONF['info_row']['subject_describe_show']}}
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
		<td class="row2">{$lang['show_subject_all']}</td>
		<td class="row2">
<select name="show_subject_all" id="select_show_subject_all">
	{if {$_CONF['info_row']['show_subject_all']}}
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
		<td class="row1">{$lang['resize_imagesAllow']}</td>
		<td class="row1">
<select name="resize_imagesAllow" id="select_resize_imagesAllow">
	{if {$_CONF['info_row']['resize_imagesAllow']}}
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
		<td class="row2">{$lang['default_imagesW']}</td>
		<td class="row2">
<input type="text" name="default_imagesW" id="input_default_imagesW" value="{$_CONF['info_row']['default_imagesW']}" dir="ltr" size="1" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');" />&nbsp;
</td>
</tr>
<tr valign="top">
		<td class="row1">{$lang['default_imagesH']}</td>
		<td class="row1">

<input type="text" name="default_imagesH" id="input_default_imagesH" value="{$_CONF['info_row']['default_imagesH']}" dir="ltr" size="1" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');" />&nbsp;
</td>
</tr>
<tr valign="top">
		<td class="row2">{$lang['wordwrap']}</td>
		<td class="row2">
<input type="text" name="wordwrap" id="input_wordwrap" value="{$_CONF['info_row']['wordwrap']}" dir="ltr" size="1" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');" />&nbsp;
</td>
</tr>

<tr valign="top">
		<td class="row1">{$lang['rating_show']}</td>
		<td class="row1">
<select name="rating_show" id="select_rating_show">
	{if {$_CONF['info_row']['rating_show']}}
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
		<td class="row2">{$lang['show_rating_num_max1']}</td>
		<td class="row2">
{$lang['show_rating_num_max2']}
<input type="text" name="show_rating_num_max" id="input_show_rating_num_max" value="{$_CONF['info_row']['show_rating_num_max']}" size="1" dir="ltr" size="1" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');" maxlength="2" />
{$lang['show_rating_num_max3']}
</td>
</tr>
<tr valign="top">
			<td class="row1">
{$lang['input_smiles_nm']}
			</td>
			<td class="row1">
<input type="text" name="smiles_nm" id="input_smiles_nm" value="{$_CONF['info_row']['smiles_nm']}" size="1" dir="ltr" size="1" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');" maxlength="3" />

			</td>
		</tr>
<tr valign="top">
			<td class="row1">
{$lang['input_icons_nm']}
			</td>
			<td class="row1">
<input type="text" name="icons_numbers" id="icons_numbers" value="{$_CONF['info_row']['icons_numbers']}" size="1" dir="ltr" size="1" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');" maxlength="3" />
			</td>
		</tr>
<tr valign="top">
			<td class="row1">
{$lang['smil_columns_number']}
			</td>
			<td class="row1">
<input type="text" name="smil_columns_number" id="smil_columns_number" value="{$_CONF['info_row']['smil_columns_number']}" size="1" dir="ltr" size="1" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');" maxlength="2" />
			</td>
		</tr>
<tr valign="top">
			<td class="row1">
{$lang['icon_columns_number']}
			</td>
			<td class="row1">
<input type="text" name="icon_columns_number" id="icon_columns_number" value="{$_CONF['info_row']['icon_columns_number']}"  size="1" dir="ltr" size="1" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');" maxlength="2" />
			</td>
		</tr>
<tr valign="top">
			<td class="row1">
{$lang['active_like_facebook']}
			</td>
<td class="row1">
{if {$_CONF['info_row']['active_like_facebook']}}
<input name="active_like_facebook" value="1" id="active_like_facebook" type="radio" checked="checked">{$lang['yes']}
&nbsp;&nbsp;<input name="active_like_facebook" value="0" id="active_like_facebook" type="radio">{$lang['no']}
{else}
<input name="active_like_facebook" value="1" id="active_like_facebook" type="radio">{$lang['yes']}
&nbsp;&nbsp;<input name="active_like_facebook" value="0" id="active_like_facebook" type="radio" checked="checked">{$lang['no']}
{/if}
</td>
</tr>
<tr valign="top">
			<td class="row1">
{$lang['active_add_this']}
			</td>
<td class="row1">
{if {$_CONF['info_row']['active_add_this']}}
<input name="active_add_this" value="1" id="active_add_this" type="radio" checked="checked">{$lang['yes']}
&nbsp;&nbsp;<input name="active_add_this" value="0" id="active_add_this" type="radio">{$lang['no']}
{else}
<input name="active_add_this" value="1" id="active_add_this" type="radio">{$lang['yes']}
&nbsp;&nbsp;<input name="active_add_this" value="0" id="active_add_this" type="radio" checked="checked">{$lang['no']}
{/if}
</td>
</tr>
</tr>
<tr valign="top">
			<td class="row1">
{$lang['username_addthis']}
			</td>
			<td class="row1">
<input type="text" name="use_list" id="icon_columns_number" value="{$_CONF['info_row']['use_list']}" size="35" />
			</td>
</tr>
<tr valign="top">
		<td class="row1">{$lang['active']}
		{$lang['Download_Subject']}؟</td>
		<td class="row1">
<select name="download_subject" id="select_rating_show">
	{if {$_CONF['info_row']['download_subject']}}
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
		<td class="row1">{$lang['active']}
		{$lang['print_Subject']}؟</td>
		<td class="row1">
<select name="print_subject" id="select_rating_show">
	{if {$_CONF['info_row']['print_subject']}}
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
		<td class="row1">{$lang['active']}
		{$lang['sendsubjecttofriend']}؟</td>
		<td class="row1">
<select name="send_subject_to_friend" id="select_rating_show">
	{if {$_CONF['info_row']['send_subject_to_friend']}}
	<option value="1" selected="selected">{$lang['yes']}</option>
	<option value="0">{$lang['no']}</option>
	{else}
	<option value="1">{$lang['yes']}</option>
	<option value="0" selected="selected">{$lang['no']}</option>
	{/if}
</select>
</td>
</tr>
<tr valign="top" align="center">
	<td class="main2" colspan="2">{$lang['Settings_fastreply']}</td>
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
<tr valign="top" align="center">
	<td class="main2" colspan="2">{$lang['Settings_Members']}</td>
</tr>
<tr valign="top">
		<td class="row1">{$lang['confirm_on_change_mail']}</td>
		<td class="row1">
<select name="confirm_on_change_mail" id="select_confirm_on_change_mail">
	{if {$_CONF['info_row']['confirm_on_change_mail']}}
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
		<td class="row2">{$lang['confirm_on_change_pass']}</td>
		<td class="row2">
<select name="confirm_on_change_pass" id="select_confirm_on_change_pass">
	{if {$_CONF['info_row']['confirm_on_change_pass']}}
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
		<td class="row1">تفعيل  إعدادات أمان الحساب </td>
		<td class="row1">
<select name="users_security" id="users_security">
	{if {$_CONF['info_row']['users_security']}}
		<option value="1" selected="selected">{$lang['yes']}</option>
		<option value="0">{$lang['no']}</option>
	{else}
		<option value="1">{$lang['yes']}</option>
		<option value="0" selected="selected">{$lang['no']}</option>
	{/if}
</select></td>
</tr>
<tr valign="top">
		<td class="row1">{$lang['allow_apsent']}</td>
		<td class="row1">
<select name="allow_apsent" id="select_allow_apsent">
	{if {$_CONF['info_row']['allow_apsent']}}
		<option value="1" selected="selected">{$lang['yes']}</option>
		<option value="0">{$lang['no']}</option>
	{else}
		<option value="1">{$lang['yes']}</option>
		<option value="0" selected="selected">{$lang['no']}</option>
	{/if}
</select></td>
</tr>
<tr valign="top" align="center">
	<td class="main2" colspan="2">{$lang['Settings_Avatars']}</td>
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
<input type="text" name="max_avatar_width" id="input_max_avatar_width" value="{$_CONF['info_row']['max_avatar_width']}" dir="ltr" size="1" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');" />&nbsp;
</td>
</tr>
<tr valign="top">
		<td class="row2">{$lang['max_avatar_height']}</td>
		<td class="row2"><input type="text" name="max_avatar_height" id="input_max_avatar_height" value="{$_CONF['info_row']['max_avatar_height']}" dir="ltr" size="1" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');" />&nbsp;
</td>
</tr>
		<tr align="center">
			<td width="60%" class="main2" colspan="2">
				<span lang="ar-sa">{$lang['default_avatar']}</span>
			</td>
		</tr>
		<tr align="center">
			<td width="60%" class="row1">
				{$lang['input_default_avatar']}
			</td>
			<td width="60%" class="row1">
				<input name="default_avatar" id="input_default_avatar" value="{$_CONF['info_row']['default_avatar']}" />
			</td>
		</tr>
<tr valign="top">
			<td class="row1">
{$lang['avatar_columns_number']}
			</td>
			<td class="row1">
<input type="text" name="avatar_columns_number" id="avatar_columns_number" value="{$_CONF['info_row']['avatar_columns_number']}" dir="ltr" size="1" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');" />
			</td>
		</tr>
<tr valign="top" align="center">
	<td class="main2" colspan="2">{$lang['Days_allowed_for_visitors_to_login']}</td>
</tr>
<tr valign="top">
		<td class="row1">{$lang['Sat']}</td>
		<td class="row1">
<select name="Sat" id="select_Sat">
	{if {$_CONF['info_row']['Sat']}}
		<option value="1" selected="selected">{$lang['Allowed']}</option>

		<option value="0">{$lang['Not_Allowed']}</option>

	{else}
		<option value="1">{$lang['Allowed']}</option>

		<option value="0" selected="selected">{$lang['Not_Allowed']}</option>

	{/if}
</select>
</td>
</tr>
<tr valign="top">
		<td class="row2">{$lang['Sun']}</td>
		<td class="row2">
<select name="Sun" id="select_Sun">
	{if {$_CONF['info_row']['Sun']}}
		<option value="1" selected="selected">{$lang['Allowed']}</option>

		<option value="0">{$lang['Not_Allowed']}</option>

	{else}
		<option value="1">{$lang['Allowed']}</option>

		<option value="0" selected="selected">{$lang['Not_Allowed']}</option>

	{/if}</select>
</td>
</tr>
<tr valign="top">
		<td class="row1">{$lang['Mon']}</td>
		<td class="row1">
<select name="Mon" id="select_Mon">
	{if {$_CONF['info_row']['Mon']}}
		<option value="1" selected="selected">{$lang['Allowed']}</option>

		<option value="0">{$lang['Not_Allowed']}</option>

	{else}
		<option value="1">{$lang['Allowed']}</option>

		<option value="0" selected="selected">{$lang['Not_Allowed']}</option>

	{/if}
</select></td>
</tr>
<tr valign="top">
		<td class="row2">{$lang['Tue']}</td>
		<td class="row2">
<select name="Tue" id="select_Tue">
	{if {$_CONF['info_row']['Tue']}}
		<option value="1" selected="selected">{$lang['Allowed']}</option>

		<option value="0">{$lang['Not_Allowed']}</option>

	{else}
		<option value="1">{$lang['Allowed']}</option>

		<option value="0" selected="selected">{$lang['Not_Allowed']}</option>

	{/if}
</select>
</td></tr>
<tr valign="top">
		<td class="row1">{$lang['Wed']}</td>
		<td class="row1">
<select name="Wed" id="select_Wed">
	{if {$_CONF['info_row']['Wed']}}
		<option value="1" selected="selected">{$lang['Allowed']}</option>

		<option value="0">{$lang['Not_Allowed']}</option>

	{else}
		<option value="1">{$lang['Allowed']}</option>

		<option value="0" selected="selected">{$lang['Not_Allowed']}</option>

	{/if}
</select>
</td>
</tr><tr valign="top">
		<td class="row2">{$lang['Thu']}</td>
		<td class="row2">
<select name="Thu" id="select_Thu">
	{if {$_CONF['info_row']['Thu']}}
		<option value="1" selected="selected">{$lang['Allowed']}</option>

		<option value="0">{$lang['Not_Allowed']}</option>

	{else}
		<option value="1">{$lang['Allowed']}</option>

		<option value="0" selected="selected">{$lang['Not_Allowed']}</option>

	{/if}
</select>
</td>
</tr>
<tr valign="top">
	<td class="row1">{$lang['Fri']}</td>
		<td class="row1">
<select name="Fri" id="select_Fri">
	{if {$_CONF['info_row']['Fri']}}
		<option value="1" selected="selected">{$lang['Allowed']}</option>

		<option value="0">{$lang['Not_Allowed']}</option>

	{else}
		<option value="1">{$lang['Allowed']}</option>

		<option value="0" selected="selected">{$lang['Not_Allowed']}</option>

	{/if}
</select>
</td>
</tr>
		<tr align="center">
			<td class="main2" colspan="2">
			{$lang['mange_ajax']}
			</td>
		</tr>

		<tr>
			<td class="row1">
			{$lang['ajax_freply']}
			</td>
			<td class="row1">
				<select name="ajax_freply" id="select_ajax_freply">
					{if {$_CONF['info_row']['ajax_freply']}}
					<option value="1" selected="selected">{$lang['yes']}</option>
					<option value="0">{$lang['no']}</option>
					{else}
					<option value="1">{$lang['yes']}</option>
					<option value="0" selected="selected">{$lang['no']}</option>
					{/if}
				</select>
			</td>
		</tr>

<tr valign="top" align="center">
	<td class="main2" colspan="2">{$lang['board_close']}</td>
</tr>
<tr valign="top">
		<td class="row1">{$lang['board_close']}</td>
		<td class="row1">
<select name="board_close" id="select_board_close">
	{if {$_CONF['info_row']['board_close']}}
		<option value="1" selected="selected">{$lang['yes']}</option>
		<option value="0">{$lang['no']}</option>
	{else}
		<option value="1">{$lang['yes']}</option>
		<option value="0" selected="selected">{$lang['no']}</option>
	{/if}
</select>
</td>
</tr>
<tr valign="top" align="center">
	<td class="main2" colspan="2">{$lang['board_msg']}</td>
</tr>
<tr valign="top" align="center">
	<td class="row1" colspan="2">
<textarea name="board_msg" id="textarea_board_msg" rows="10" cols="40" wrap="virtual" dir="{$_CONF['info_row']['content_dir']}">{$_CONF['info_row']['board_msg']}</textarea>&nbsp;
<br />
<a href="index.php?page=options&amp;close=1&amp;main=1">{$lang['Jump_to_advanced_editor']}</a>
</td></tr>
<tr valign="top" align="center">
	<td class="main2" colspan="2">{$lang['settings_mailer']}</td>
</tr>
<tr valign="top">
		<td class="row2">{$lang['send_email']}</td>
		<td class="row2">
<input type="text" name="send_email" id="input_send_email" value="{$_CONF['info_row']['send_email']}" dir="ltr" size="30" />
</td>
</tr>
<tr valign="top">
		<td class="row1">{$lang['admin_email']}</td>
		<td class="row1">
<input type="text" name="admin_email" id="input_admin_email" value="{$_CONF['info_row']['admin_email']}" dir="ltr" size="30" />
</td>
</tr>
<tr valign="top">
		<td class="row1">{$lang['Mail_Delivery_Method']}</td>
		<td class="row1">
<select name="mailer" id="select_mailer">
	{if {$_CONF['info_row']['mailer']}== 'phpmail'}
		<option value="phpmail" selected="selected">{$lang['phpmail']}</option>
		<option value="smtp">{$lang['smtp_mail']}</option>
	{else}
		<option value="smtp" selected="selected">{$lang['smtp_mail']}</option>
		<option value="phpmail">{$lang['phpmail']}</option>
	{/if}
</select>
</td>
</tr>
		<tr align="center">
			<td width="60%" class="main1" colspan="2">
				{$lang['settings_smtp']}
			</td>
		</tr>
<tr valign="top">
		<td class="row2">{$lang['smtp_Server']}</td>
		<td class="row2">

<input type="text" name="smtp_server" id="input_smtp_server" value="{$_CONF['info_row']['smtp_server']}" dir="ltr" size="30" />&nbsp;
</td>
</tr>
<tr valign="top">
		<td class="row2">{$lang['smtp_port']}</td>
		<td class="row2">

<input type="text" name="smtp_port" id="input_smtp_port" value="{$_CONF['info_row']['smtp_port']}" dir="ltr" size="1" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');">

</td>
</tr>
<tr valign="top">
		<td class="row2">{$lang['smtp_username']}</td>
		<td class="row2">

<input type="text" name="smtp_username" id="input_smtp_username" value="{$_CONF['info_row']['smtp_username']}" dir="ltr" size="30" />&nbsp;
</td>
</tr>
<tr valign="top">
		<td class="row2">{$lang['smtp_password']}</td>
		<td class="row2">

<input type="text" name="smtp_password" id="input_smtp_password" value="{$_CONF['info_row']['smtp_password']}" dir="ltr" size="30" /></td>
</tr>
<tr valign="top">
		<td class="row2">{$lang['smtp_secure']}</td>
		<td class="row2">
<select name="smtp_secure" id="select_smtp_secure">
	{if {$_CONF['info_row']['smtp_secure']}== 'TLS'}
		<option value="TLS" selected="selected">TLS</option>
		<option value="SSL">SSL</option>
		<option value="">{$lang['no_place']}</option>
	{elseif {$_CONF['info_row']['smtp_secure']}== 'SSL'}
		<option value="TLS">TLS</option>
		<option value="SSL" selected="selected">SSL</option>
		<option value="">{$lang['no_place']}</option>
	{else}
		<option value="SSL">SSL</option>
		<option value="smtp">TLS</option>
		<option value="" selected="selected">{$lang['no_place']}</option>
	{/if}
</select>
</td>
</tr>
		<tr align="center">
			<td class="main2" colspan="2">
{$lang['mange_warnings']}
			</td>
		</tr>
		<tr>
			<td class="row1">
{$lang['warning_number_to_ban']}
			</td>
			<td class="row1">
			<input type="text" name="warning_number_to_ban" id="input_warning_number_to_ban" value="{$_CONF['info_row']['warning_number_to_ban']}"dir="ltr" size="1" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');" />&nbsp;
			</td>
		</tr>
		<tr align="center">
			<td class="main2" colspan="2">
{$lang['Settings_last_static']}
			</td>
		</tr>
		<tr>
			<td class="row1">
{$lang['activate_last_static_list']}
<div class="smallfont">
{$lang['server_resource_consumption']}
 </div>
			</td>
			<td class="row1">
				<select name="activate_last_static_list" id="select_activate_last_static_list">
					{if {$_CONF['info_row']['activate_last_static_list']}}
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
{$lang['last_static_num']}
			</td>
			<td class="row2">
<input type="text" name="last_static_num" id="input_last_static_num" value="{$_CONF['info_row']['last_static_num']}"dir="ltr" size="1" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');" />
			</td>
		</tr>
		<tr>
			<td class="row1">
{$lang['last_posts_static_num']}
			</td>
			<td class="row1">
<input type="text" name="last_posts_static_num" id="input_last_posts_static_num" value="{$_CONF['info_row']['last_posts_static_num']}" dir="ltr" size="1" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');" />

			</td>
		</tr>
		<tr align="center">
			<td class="main2" colspan="2">
{$lang['lasts_posts_bar_num']}
			</td>
		</tr>
		<tr>
			<td class="row1">
{$lang['activate_lasts_posts_bar']}
			</td>
			<td class="row1">
				<select name="activate_lasts_posts_bar" id="select_activate_lasts_posts_bar">
					{if {$_CONF['info_row']['activate_lasts_posts_bar']}}
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
{$lang['lasts_posts_num_bar']}
			</td>
			<td class="row2">
<input type="text" name="lasts_posts_bar_num" id="input_lasts_posts_bar_num" value="{$_CONF['info_row']['lasts_posts_bar_num']}" dir="ltr" size="1" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');" />
			</td>
		</tr>
		<tr>
			<td class="row1">
{$lang['lasts_posts_bar_dir']}
			</td>
		<td class="row1">
      <select name="lasts_posts_bar_dir" id="select_lasts_posts_bar_dir">
	{if {$_CONF['info_row']['lasts_posts_bar_dir']} == 'right'}
	<option value="right" selected="selected">{$lang['From_right_to_left']}</option>
	<option value="left">{$lang['From_left_to_right']}</option>
	{else}
	<option value="right">{$lang['From_right_to_left']}</option>
	<option value="left" selected="selected">{$lang['From_left_to_right']}</option>
	{/if}
		</tr>
		<tr align="center">
			<td class="main2" colspan="2">
{$lang['Settings_special_bar']}
			</td>
		</tr>
		<tr>
			<td class="row1">
{$lang['activate_special_bar']}
			</td>
			<td class="row1">
				<select name="activate_special_bar" id="select_activate_special_bar">
					{if {$_CONF['info_row']['activate_special_bar']}}
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
			<td class="row1">
{$lang['special_bar_dir']}
			</td>
		<td class="row1">
      <select name="special_bar_dir" id="select_special_bar_dir">
		{if {$_CONF['info_row']['special_bar_dir']} == 'right'}
	<option value="right" selected="selected">{$lang['From_right_to_left']}</option>
	<option value="left">{$lang['From_left_to_right']}</option>
	{else}
	<option value="right">{$lang['From_right_to_left']}</option>
	<option value="left" selected="selected">{$lang['From_left_to_right']}</option>
	{/if}
	</select>
	</td>
		</tr>
		<tr align="center">
			<td class="main2" colspan="2">
{$lang['Settings_ads']}
			</td>
		</tr>

			<td class="row1">
{$lang['show_ads']}
			</td>
		<td class="row1">
<input TYPE="hidden" name="random_ads" id="select_random_ads" value="0" />
      <select name="show_ads" id="select_show_ads">
	{if {$_CONF['info_row']['show_ads']}}
					<option value="1" selected="selected">{$lang['yes']}</option>
					<option value="0">{$lang['no']}</option>
					{else}
					<option value="1">{$lang['yes']}</option>
					<option value="0" selected="selected">{$lang['no']}</option>
					{/if}
				</select>
		</tr>
		<tr align="center">
			<td class="main2" colspan="2">
{$lang['Settings_online_today']}
			</td>
		</tr>
		<tr>
			<td class="row1">
{$lang['show_online_list_today']}
			</td>
			<td class="row1">
				<select name="show_online_list_today" id="select_show_online_list_today">
					{if {$_CONF['info_row']['show_online_list_today']}}
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
			<td class="row1">
{$lang['mor_hours_online_today']}
			</td>
			<td class="row1">
<input type="text" name="mor_hours_online_today" id="input_mor_hours_online_today" value="{$_CONF['info_row']['mor_hours_online_today']}" dir="ltr" size="1" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');" />
			</td>
		</tr>
		<tr align="center">
			<td class="main2" colspan="2">
{$lang['Settings_online']}
			</td>
		</tr>
<tr valign="top">
		<td class="row2">{$lang['shwo_guest_online']}</td>
		<td class="row2">
			<select name="show_onlineguest" id="select_guest_online">
				{if {$_CONF['info_row']['show_onlineguest']}}
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
			<td class="row1">
{$lang['mor_seconds_online']}
			</td>
			<td class="row1">
<input type="text" name="mor_seconds_online" id="input_mor_seconds_online" value="{$_CONF['info_row']['mor_seconds_online']}" dir="ltr" size="1" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');" />
			</td>
		</tr>
<tr valign="top">
<td class="row1">{$lang['search_engine_spiders']}</td>
		<td class="row1">
<textarea name="search_engine_spiders" id="textarea_search_engine_spiders" rows="6" cols="57" wrap="virtual" dir="ltr">{$_CONF['info_row']['search_engine_spiders']}</textarea>&nbsp;
</td>
</tr>
		<tr align="center">
			<td class="main2" colspan="2">
{$lang['Settings_subject_writer']}
			</td>
		</tr>
		<tr>
			<td class="row1">
{$lang['show_list_last_5_posts_member']}
<div class="smallfont">
{$lang['server_resource_consumption']}
 </div>
			</td>
			<td class="row1">
				<select name="show_list_last_5_posts_member" id="select_show_list_last_5_posts_member">
					{if {$_CONF['info_row']['show_list_last_5_posts_member']}}
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
{$lang['last_subject_writer_nm']}
			</td>
			<td class="row2">
<input type="text" name="last_subject_writer_nm" id="input_last_subject_writer_nm" value="{$_CONF['info_row']['last_subject_writer_nm']}" dir="ltr" size="1" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');" />
			</td>
		</tr>
		<tr align="center">
			<td class="main2" colspan="2">
{$lang['Settings_reputation']}
			</td>
		</tr>
		<tr>
			<td class="row1">
{$lang['reputationallw']}
			</td>
			<td class="row1">
<select name="reputationallw" id="select_reputationallw">
	{if {$_CONF['info_row']['reputationallw']}}
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
			<td class="row1">
{$lang['show_reputation_number']}
			</td>
			<td class="row1">
			<input type="text" name="show_reputation_number" id="input_show_reputation_number" value="{$_CONF['info_row']['show_reputation_number']}" dir="ltr" size="1" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');" />&nbsp;

			</td>
		</tr>
		<tr align="center">
			<td class="main2" colspan="2">
{$lang['Settings_chat_message_bar']}
			</td>
		</tr>
		<tr>
			<td class="row1">
{$lang['activate_chat_bar']}
			</td>
			<td class="row1">
				<select name="activate_chat_bar" id="select_activate_chat_bar">
					{if {$_CONF['info_row']['activate_chat_bar']}}
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
{$lang['chat_message_num']}
			</td>
			<td class="row2">
<input type="text" name="chat_message_num" id="input_chat_message_num" value="{$_CONF['info_row']['chat_message_num']}" dir="ltr" size="1" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');" />
			</td>
		</tr>
		<tr>
			<td class="row1">
{$lang['chat_num_mem_posts']}
			</td>
			<td class="row1">
<input type="text" name="chat_num_mem_posts" id="input_chat_num_mem_posts" value="{$_CONF['info_row']['chat_num_mem_posts']}" dir="ltr" size="1" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');" />
			</td>
		</tr>
			<tr>
			<td class="row1">
{$lang['chat_num_characters']}
			</td>
			<td class="row1">
<input type="text" name="chat_num_characters" id="input_chat_num_characters" value="{$_CONF['info_row']['chat_num_characters']}" dir="ltr" size="1" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');" />
			</td>
		</tr>
				<tr>
			<td class="row1">
{$lang['chat_hide_country']}
			</td>
			<td class="row1">
				<select name="chat_hide_country" id="select_chat_hide_country">
					{if {$_CONF['info_row']['chat_hide_country']}}
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
		<tr align="center">
			<td class="main2" colspan="2">
{$lang['Hide_links_for_visitors']}
			</td>
		</tr>
		<tr>
			<td class="row1">
{$lang['Activation_of_hidden_links_on_visitors']}
			</td>
			<td class="row1">
				<select name="haid_links_for_guest" id="haid_links_for_guest">
					{if {$_CONF['info_row']['haid_links_for_guest']}}
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
<td class="row1">{$lang['guest_message_for_haid_links']}</td>
		<td class="row1">
<textarea name="guest_message_for_haid_links" id="textarea_guest_message_for_haid_links" rows="4" cols="57" wrap="virtual" dir="{$_CONF['info_row']['guest_message_for_haid_links']}">{$_CONF['info_row']['guest_message_for_haid_links']}</textarea>&nbsp;
</td>
</tr>
		<tr align="center">
			<td class="main2" colspan="2">
{$lang['tags_automatic']}
			</td>
		</tr>
		<tr>
			<td class="row1">
{$lang['add_tags_automatic']}
			</td>
			<td class="row1">
				<select name="add_tags_automatic" id="select_add_tags_automatic">
					{if {$_CONF['info_row']['add_tags_automatic']}}
					<option value="1" selected="selected">{$lang['yes']}</option>
					<option value="0">{$lang['no']}</option>
					{else}
					<option value="1">{$lang['yes']}</option>
					<option value="0" selected="selected">{$lang['no']}</option>
					{/if}
				</select>
			</td>
		</tr>
		<tr align="center">
			<td class="main2" colspan="2">
{$lang['color_groups_of_members']}
			</td>
		</tr>
		<tr>
			<td class="row1">
{$lang['view_group_username_style']}
<div class="smallfont">
{$lang['server_resource_consumption']}
 </div>
			</td>
			<td class="row1">
				<select name="get_group_username_style" id="select_get_group_username_style">
					{if {$_CONF['info_row']['get_group_username_style']}}
					<option value="1" selected="selected">{$lang['yes']}</option>
					<option value="0">{$lang['no']}</option>
					{else}
					<option value="1">{$lang['yes']}</option>
					<option value="0" selected="selected">{$lang['no']}</option>
					{/if}
				</select>
			</td>
		</tr>
		<tr align="center">
			<td class="main2" colspan="2">
{$lang['how_many_entries_error_num']}
			</td>
		</tr>
			<tr>
			<td class="row1">
{$lang['how_many_entries_error_num']}
			</td>
			<td class="row1">
<input type="text" name="num_entries_error" id="input_num_entries_error" value="{$_CONF['info_row']['num_entries_error']}" dir="ltr" size="1" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');" />
			</td>
		</tr>
<!-- action_find_addons_54 -->
</table>
<br />
<div align="center">
	<input type="submit" value="  {$lang['Update_all_the_settings']}  " name="submit" onClick="comm._submit();" /> </div>
</form>

<br />
<br />
<br />
<br />
<br />