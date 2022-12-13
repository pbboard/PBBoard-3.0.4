<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=options&amp;index=1">{$lang['mange_forum']}</a> &raquo;
 <a href="index.php?page=options&amp;close_days=1&amp;main=1">{$lang['Days_allowed_for_visitors_to_login']}</a></div>

<br />

<form action="index.php?page=options&amp;close_days=1&amp;update=1"  name="myform" method="post">
<table cellpadding="3" cellspacing="1" width="80%" class="t_style_b" border="0" align="center">
<tr valign="top" align="center">
	<td class="main1" colspan="2">{$lang['Days_allowed_for_visitors_to_login']}</td>
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

<tr valign="top">
	<td class="row1" colspan="2" align="center">
	<input class="submit" type="submit" value="{$lang['acceptable']}" name="submit" accesskey="s" /></td>
</tr>

</table><br />
<br />
</form>