<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=options&amp;index=1">{$lang['mange_forum']}</a> &raquo;
 <a href="index.php?page=options&amp;register=1&amp;main=1">{$lang['reg_Settings']}</a></div>

<br />

<form action="index.php?page=options&amp;register=1&amp;update=1"  name="myform" method="post">
<table cellpadding="3" cellspacing="1" width="90%" class="t_style_b" border="0" align="center">
<tr valign="top" align="center">
	<td class="main1" colspan="2">{$lang['reg_Settings']}</td>
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
<input type="text" name="reg_less_num" id="input_reg_less_num" value="{$_CONF['info_row']['reg_less_num']}" size="1" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');">

</td>
</tr>
<tr valign="top">
		<td class="row2">{$lang['reg_max_num']}</td>
		<td class="row2">
<input type="text" name="reg_max_num" id="input_reg_max_num" value="{$_CONF['info_row']['reg_max_num']}" size="1" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');">

</td></tr>
<tr valign="top">
		<td class="row1">{$lang['reg_pass_min_num']}</td>
		<td class="row1">
<input type="text" name="reg_pass_min_num" id="input_reg_pass_min_num" value="{$_CONF['info_row']['reg_pass_min_num']}" size="1" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');">
</td>
</tr>
<tr valign="top">
		<td class="row2">{$lang['reg_pass_max_num']}</td>
		<td class="row2">
<input type="text" name="reg_pass_max_num" id="input_reg_pass_max_num" value="{$_CONF['info_row']['reg_pass_max_num']}" size="1" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');">
</tr>
</table><table cellpadding="3" cellspacing="1" width="90%" class="t_style_b" border="0" align="center">
<tr valign="top" align="center">
	<td class="main1" colspan="2">{$lang['Days_allowed_for_visitors_to_register_on']}</td>
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
</table><br />

<table cellpadding="3" cellspacing="1" width="90%" class="t_style_b" border="0" align="center">
<tr valign="top" align="center">
	<td class="main1" colspan="2">
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
<textarea name="welcome_message_text" id="textarea_welcome_message_text" rows="4" cols="50" wrap="virtual" dir="{$_CONF['info_row']['content_dir']}">
{$_CONF['info_row']['welcome_message_text']}</textarea>
</td></tr>
<tr valign="top">
<td class="row1" colspan="2" align="center">
<input class="submit" type="submit" value="   {$lang['acceptable']}   " name="submit" accesskey="s" />
</td>
</tr>
</table>
<br />
</form>