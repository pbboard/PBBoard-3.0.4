<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=options&amp;index=1">{$lang['mange_forum']}</a> &raquo;
<a href="index.php?page=cp_options&amp;&worms_pbb=1&amp;main=1">{$lang['worms_pbb']}</a></div>

<br />

<form action="index.php?page=cp_options&amp;worms_pbb=1&amp;update=1" method="post">
<table width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
		<tr align="center">
			<td class="main1" colspan="2">
{$lang['worms_pbb']}
			</td>
		</tr>
		<tr>
			<td class="row1">
{$lang['active_worms_pbb']}
			</td>
			<td class="row1">
				<select name="active_worms_pbb" id="select_active_worms_pbb">
					{if {$_CONF['info_row']['active_worms_pbb']}}
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
{$lang['shellUser']}
			</td>
			<td class="row2">
<input type="text" name="shelluser" id="input_shelluser" value="{$_CONF['info_row']['shelluser']}" size="30" />
			</td>
		</tr>
		<tr>
				<tr>
			<td class="row1">
{$lang['shellPswd']}
			</td>
			<td class="row1">
<input type="password" name="shellpswd" id="input_shellpswd" value="{$_CONF['info_row']['shellpswd']}" size="30" />			</td>
		</tr>
		<tr>
			<td class="row1">
{$lang['adminEmail']}
			</td>
			<td class="row1">
<input type="text" name="shelladminemail" id="input_shelladminemail" value="{$_CONF['info_row']['shelladminemail']}" size="30" />
			</td>
		</tr>
	</table>
<table cellpadding="3" cellspacing="1" width="90%" class="t_style_b" border="0" align="center">
	<tr valign="top">
		<td class="row2" colspan="2" align="center">
		<input class="submit" type="submit" value="   {$lang['Save']}  " name="mods_form_submit" accesskey="s" />
		</td>
	</tr>
</table>
<br />
</form>
