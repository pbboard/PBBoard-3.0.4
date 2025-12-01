<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=options&amp;index=1">{$lang['mange_forum']}</a> &raquo;
 <a href="index.php?page=options&amp;mailer=1&amp;main=1">{$lang['settings_mailer']}</a></div>

<br />

<form action="index.php?page=options&amp;mailer=1&amp;update=1"  name="myform" method="post">
<table cellpadding="3" cellspacing="1" width="80%" class="t_style_b" border="0" align="center">
<tr valign="top" align="center">
	<td class="main1" colspan="2">{$lang['settings_mailer']}</td>
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
</table><br />
<div align="center">
<table cellpadding="3" cellspacing="1" width="80%" class="t_style_b" border="0" align="center">
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
		<option value="TLS">TLS</option>
		<option value="" selected="selected">{$lang['no_place']}</option>
	{/if}
</select>
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
