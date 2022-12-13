<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=member&amp;control=1&amp;main=1">{$lang['members']}</a> &raquo;
<a href="index.php?page=member&amp;edit=1&amp;main=1&amp;id={$Inf['id']}"> {$lang['edit']} :
{$Inf['username']}</a></div>

<br />

<form action="index.php?page=member&amp;edit=1&amp;start=1&amp;id={$Inf['id']}" method="post">
<table cellpadding="3" cellspacing="1" width="95%" class="t_style_b" border="0" cellspacing="1" align="center">
<tr align="center">
<td class="main1" colspan="2">
{$lang['edit']} :
{$Inf['username']}
</td>
</tr>
<tr>
<td class="row1">
{$lang['username']}
</td>
<td class="row1">
<input type="hidden" name="username" value="{$Inf['username']}" />
<input type="text" name="new_username" value="{$Inf['username']}" />
</td>
</tr>
<tr>
<td class="row1">
{$lang['New_Password']}
</td>
<td class="row1">
<input type="text" name="new_password" size="40" />
</td>
</tr>
<tr>
<td class="row2">
{$lang['email']}
</td>
<td class="row2">
<input type="text" name="email" value="{$Inf['email']}" size="40" dir="ltr"/>
</td>
</tr>
<tr>
<tr>
<td class="row1">
{$lang['Group']}
</td>
<td class="row1">
<select name="usergroup" id="select_usergroup">
{Des::while}{GroupList}
{if {$Inf['usergroup']} == {$GroupList['id']} }
<option value="{$GroupList['id']}" selected="selected">{$GroupList['title']}</option>
{else}
<option value="{$GroupList['id']}">{$GroupList['title']}</option>
{/if}
{/Des::while}
</select>
</td>
</tr>

<tr valign="top">
	<td class="row1">{$lang['additional_user_groups']}</td>
	<td class="row1">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
	{Des::while}{GroupList}
<tr valign="top">
	<td>
		<?php

		if (in_array($PowerBB->_CONF['template']['while']['GroupList'][$this->x_loop]['id'], explode(',', $PowerBB->_CONF['template']['Inf']['membergroupids']))){
		?>
		<input type="checkbox" tabindex="1" name="membergroupids[]" value="{$GroupList['id']}" checked="checked" />
		{else}
		<input type="checkbox" tabindex="1" name="membergroupids[]" value="{$GroupList['id']}" />
		{/if}
	{$GroupList['title']}
</td>
</tr>
{/Des::while}
</table>
</td>
</tr>

<tr>
<td class="row1">
{$lang['MemberTitle']}
</td>
<td class="row1">
<textarea name="user_title" rows="1" cols="50" wrap="virtual" >{$Inf['user_title']}</textarea>
</td>
</tr>
<tr>
<td class="row1">
{$lang['Gender']}
</td>
<td class="row1">
<select name="gender" id="select_gender">
{if {$Inf['user_gender']} == 'm'}
<option value="m" selected="selected">{$lang['Male']}</option>
<option value="f">{$lang['Female']}</option>
{else}
<option value="m">{$lang['Male']}</option>
<option value="f" selected="selected">{$lang['Female']}</option>
{/if}
</select>
</td>
</tr>
<tr>
<td class="row1">
{$lang['MemberAvatar']}
</td>
<td class="row1">
{if {$avater_path} != ''}
<img src="{$avater_path}" cellspacing="1" align="center" border="0">
<br />
{/if}
<textarea name="avater_path" rows="1" cols="50" wrap="virtual" dir="ltr">{$avater_path}</textarea>
</td>
</tr>
<tr>
<td class="row1">
{$lang['user_profile_cover_photo']}
</td>
<td class="row1">
{if {$profile_cover_photo} != ''}
<img src="{$profile_cover_photo}" cellspacing="1" align="center" border="0" width="424" height="85">
<br />
{/if}
<textarea name="profile_cover_photo" rows="1" cols="50" wrap="virtual" dir="ltr">{$profile_cover_photo}</textarea>
</td>
</tr>
<tr>
<td class="row2">
{$lang['user_info']}
</td>
<td class="row2">
<textarea name="user_info" rows="1" cols="50" wrap="virtual" >{$Inf['user_info']}</textarea>
</td>
</tr>
<tr>
<td class="row2">
{$lang['PostsNum']}
</td>
<td class="row2">
<input type="text" name="posts" value="{$Inf['posts']}" size="7"/>
</td>
</tr>
<tr>
<td class="row1">
{$lang['user_absence']}
</td>
<td class="row1">
<select name="away" size="1">
{if {$Inf['away']} == 0}
<option value="1">{$lang['yes']}</option>
<option selected="selected" value="0">{$lang['no']}</option>
{else}
<option selected="selected" value="1">{$lang['yes']}</option>
<option value="0">{$lang['no']}</option>
{/if}

</select>
</td>
</tr>
<tr>
<td class="row1">
{$lang['absence_reason']}
</td>
<td class="row1">
<textarea name="away_msg" rows="1" cols="50" wrap="virtual" dir="{$_CONF['info_row']['content_dir']}">{$Inf['away_msg']}</textarea>
</td>
</tr>
<tr>
<td class="row2">
{$lang['Birth_date']}
</td>
<td class="row2">
{if {$Inf['bday_year']} == '' }
{template}birth_date{/template}
{else}
{template}usercp_birth_date{/template}
{/if}
</td>
</tr>
<tr>
<td class="row2">
{$lang['user_country']}
</td>
<td class="row2">
<textarea name="user_country" rows="1" cols="50" wrap="virtual" >{$Inf['user_country']}</textarea>
</td>
</tr>
<tr>
<td class="row2">
{$lang['member_ip']}
</td>
<td class="row2">
<input type="text" name="ip" value="{$Inf['member_ip']}" size="30" dir="ltr"/>
</td>
</tr>
<tr>
<td class="row1">
{$lang['warningsNum']}
</td>
<td class="row1">
<input type="text" name="warnings" value="{$Inf['warnings']}" size="7"/>
</td>
</tr>
<tr>
<td class="row1">
{$lang['user_reputation']}
</td>
<td class="row1">
<input type="text" name="reputation" value="{$Inf['reputation']}" size="7"/>
</td>
</tr>
<tr>
<td class="row1">
{$lang['user_website']}
</td>
<td class="row1">
<textarea name="website" rows="1" cols="50" wrap="virtual" dir="ltr">{$Inf['user_website']}</textarea>
</td>
</tr>
</table>
<br />
<br />
<table cellpadding="3" cellspacing="1" width="80%" class="t_style_b" border="0" cellspacing="1" align="center">
<tr align="center">
<td class="main1" colspan="2">
{$lang['Edit_options']}
</td>
</tr>
<tr>
<td class="row2">
{$lang['MemberStyle']}
</td>
<td class="row2">
<select name="style" id="select_style">
{Des::while}{StyleList}
<?php if ($PowerBB->_CONF['template']['Inf']['style'] == $PowerBB->_CONF['template']['while']['StyleList'][$this->x_loop]['id']
OR $PowerBB->_CONF['template']['Inf']['style'] == '0' )
{  ?>
<option value="{$StyleList['id']}" selected="selected">{$StyleList['style_title']}</option>
{else}
<option value="{$StyleList['id']}">{$StyleList['style_title']}</option>
{/if}
{/Des::while}
</select>
</td>
</tr>
<tr>
<tr>
<td class="row2">
{$lang['language']}
</td>
<td class="row2">
<select name="lang" id="select_lang">
{Des::while}{LangList}
<?php if ($PowerBB->_CONF['template']['Inf']['lang'] == $PowerBB->_CONF['template']['while']['LangList'][$this->x_loop]['id']
OR $PowerBB->_CONF['template']['Inf']['lang'] == '0' )
{  ?>
<option value="{$LangList['id']}" selected="selected">{$LangList['lang_title']}</option>
{else}
<option value="{$LangList['id']}">{$LangList['lang_title']}</option>
{/if}
{/Des::while}
</select>
</td>
</tr>
<tr>
<td class="row1">
{$lang['hide_online']}
</td>
<td class="row1">

<select name="hide_online" size="1">
{if {$Inf['hide_online']} == 0}
<option value="1">{$lang['yes']}</option>
<option selected="selected" value="0">{$lang['no']}</option>
{else}
<option selected="selected" value="1">{$lang['yes']}</option>
<option value="0">{$lang['no']}</option>
{/if}

</select>
</td>
</tr>
<tr><tr>
<td class="row2">
{$lang['Edit_time']}
</td>
<td class="row2">
<select name="user_time" size="1" dir="ltr">
<option selected="selected" value="{$Inf['user_time']}">{$Inf['user_time']}</option>
<option value="0">0</option>
<option value="+1">+1</option>
<option value="+2">+2</option>
<option value="+3">+3</option>
<option value="+4">+4</option>
<option value="+5">+5</option>
<option value="+6">+6</option>
<option value="+7">+7</option>
<option value="+8">+8</option>
<option value="+9">+9</option>
<option value="+10">+10</option>
<option value="+11">+11</option>
<option value="+12">+12</option>
<option value="+13">+13</option>
<option value="-1">-1</option>
<option value="-2">-2</option>
<option value="-3">-3</option>
<option value="-4">-4</option>
<option value="-5">-5</option>
<option value="-6">-6</option>
<option value="-7">-7</option>
<option value="-8">-8</option>
<option value="-9">-9</option>
<option value="-10">-10</option>
<option value="-11">-11</option>
<option value="-12">-12</option>
</select>
GMT       </td>
</tr>    <tr>
<td class="row1">
{$lang['send_allow']}
</td>
<td class="row1">

<select name="send_allow" size="1">
{if {$Inf['send_allow']} == 0}
<option value="1">{$lang['yes']}</option>
<option selected="selected" value="0">{$lang['no']}</option>
{else}
<option selected="selected" value="1">{$lang['yes']}</option>
<option value="0">{$lang['no']}</option>
{/if}
</select>

</td>
</tr>

<tr>
<td class="row1">
{$lang['pm_emailed']}
</td>
<td class="row1">
<select name="pm_emailed" size="1">
{if {$Inf['pm_emailed']} == 0}
<option value="1">{$lang['yes']}</option>
<option selected="selected" value="0">{$lang['no']}</option>
{else}
<option selected="selected" value="1">{$lang['yes']}</option>
<option value="0">{$lang['no']}</option>
{/if}
</select>
</td>
</tr>

<tr>
<td class="row1">
{$lang['pm_window']}
</td>
<td class="row1">
<select name="pm_window" size="1">
{if {$Inf['pm_window']} == 0}
<option value="1">{$lang['yes']}</option>
<option selected="selected" value="0">{$lang['no']}</option>
{else}
<option selected="selected" value="1">{$lang['yes']}</option>
<option value="0">{$lang['no']}</option>
{/if}
</select>
</td>
</tr>
<tr>
<td class="row1" width="30%">
{$lang['Allw_visitormessage']}
</td>
<td class="row1" width="30%">
<select name="visitormessage" size="1">
{if {$Inf['visitormessage']} == 0}
<option value="1">{$lang['yes']}</option>
<option selected="selected" value="0">{$lang['no']}</option>
{else}
<option selected="selected" value="1">{$lang['yes']}</option>
<option value="0">{$lang['no']}</option>
{/if}
</select>
</td>
</tr>
</table>
<br />
<table align="center" border="0" cellspacing="1" cellpadding="2" cellspacing="2" width="80%" class="t_style_b">
<tr align="center">
<td class="main1" width="80%" colspan="2">
{$lang['security_settings_to_account']}
{$Inf['username']}</td>
</tr>
<tr>
<td class="row1" width="60%">
{$lang['send_temporary_pin']}
</td>
<td class="row1" width="30%">
<select name="send_security_code" size="1">
{if {$Inf['send_security_code']} == 0}
<option value="1">{$lang['yes']}</option>
<option selected="selected" value="0">{$lang['no']}</option>
{else}
<option selected="selected" value="1">{$lang['yes']}</option>
<option value="0">{$lang['no']}</option>
{/if}
</select>
</td>
</tr>
<tr>
<td class="row1" width="60%">
{$lang['send_email_notification_logon_error']}
</td>
<td class="row1" width="30%">
<select name="send_security_error_login" size="1">
{if {$Inf['send_security_error_login']} == 0}
<option value="1">{$lang['yes']}</option>
<option selected="selected" value="0">{$lang['no']}</option>
{else}
<option selected="selected" value="1">{$lang['yes']}</option>
<option value="0">{$lang['no']}</option>
{/if}
</select>
</td>
</tr>
</table>
<br />
<table cellpadding="3" cellspacing="1" width="80%" class="t_style_b" border="0" align="center">
<tr valign="top" align="center">
<td class="main1">{$lang['sig']} :
{$Inf['username']}</td>
</tr>
<tr valign="top">
<td class="row1" align="center">
<textarea name="user_sig" rows="9" cols="50" wrap="virtual" >{$Inf['user_sig']}</textarea>&nbsp;
</td>
</tr>
</table>
<br />
{if $PowerBB->_CONF['template']['while']['extrafields']!=null }
<table align="center" border="0" cellspacing="1" cellpadding="2" cellspacing="2" width="80%" class="t_style_b">
<tr align="center">
<td class="main1" width="80%" colspan="2">
{$lang['Additional_information']}
</td>
</tr>

{Des::while}{extrafields}

<tr>
<td width="30%" class="row1">
{$extrafields['name']}
</td>
<td width="30%" class="row1">
{$extrafields['type_html']}
</td>
</tr>

{/Des::while}

</table>
<br />
{/if}

<table cellpadding="3" cellspacing="1" width="80%" class="t_style_b" border="0" cellspacing="1" align="center">
<tr align="center">
<td class="main1" colspan="2">
{$lang['Other_Options']}
</td>
</tr>
<tr>
<td class="row1">
{$lang['user_review_subject']}
</td>
<td class="row1">
<select name="review_subject" id="select_review_subject">
{if {$Inf['review_subject']}}
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
{$lang['user_review_reply']}
</td>
<td class="row1">
<select name="review_reply" id="select_review_reply">
{if {$Inf['review_reply']}}
<option value="1" selected="selected">{$lang['yes']}</option>
<option value="0">{$lang['no']}</option>
{else}
<option value="1">{$lang['yes']}</option>
<option value="0" selected="selected">{$lang['no']}</option>
{/if}
</select>
</td>
</tr>
</table>

<br />

<div align="center">
<input type="submit" value="{$lang['acceptable']}" name="submit" />
</div>

</form>