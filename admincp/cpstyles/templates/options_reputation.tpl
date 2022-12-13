<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=options&amp;index=1">{$lang['mange_forum']}</a> &raquo;
 <a href="index.php?page=options&amp;reputation=1&amp;main=1">{$lang['Settings_reputation']}</a></div>

<br />

<form action="index.php?page=options&amp;reputation=1&amp;update=1" method="post">
<table cellpadding="3" cellspacing="1" width="80%" class="t_style_b" border="0" align="center">
		<tr align="center">
			<td class="main1" colspan="2">
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
			<input type="text" name="show_reputation_number" id="input_show_reputation_number" value="{$_CONF['info_row']['show_reputation_number']}" size="30" />&nbsp;

			</td>
		</tr>

						<tr>
			<td class="row1" colspan="2" align="center">
<input type="submit" value="{$lang['acceptable']}" name="submit" />
</td>
		</tr>

	</table>

	<br />
</form>