<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=options&amp;index=1">{$lang['mange_forum']}</a> &raquo;
 <a href="index.php?page=options&amp;member=1&amp;main=1">{$lang['Settings_Members']}</a></div>

<br />

<form action="index.php?page=options&amp;member=1&amp;update=1"  name="myform" method="post">
<table cellpadding="3" cellspacing="1" width="80%" class="t_style_b" border="0" align="center">
<tr valign="top" align="center">
	<td class="main1" colspan="2">{$lang['Settings_Members']}</td>
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
<tr valign="top">
		<td class="row1" colspan="2" align="center">
		<input class="submit" type="submit" value="{$lang['acceptable']}" name="submit" accesskey="s" />
		</td>
</tr>
</table><br />

</form>