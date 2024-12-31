<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=options&amp;index=1">{$lang['mange_forum']}</a> &raquo;
<a href="index.php?page=options&amp;warning=1&amp;main=1">{$lang['mange_warnings']}</a></div>

<br />

<form action="index.php?page=options&amp;warning=1&amp;update=1" method="post">
<table cellpadding="3" cellspacing="1" width="80%" class="t_style_b" border="0" align="center">
		<tr align="center">
			<td class="main1" colspan="2">
{$lang['mange_warnings']}
			</td>
		</tr>
		<tr>
			<td class="row1">
{$lang['warning_number_to_ban']}
			</td>
			<td class="row1">
			<input type="text" name="warning_number_to_ban" id="input_warning_number_to_ban" value="{$_CONF['info_row']['warning_number_to_ban']}" size="1" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');">
			</td>
		</tr>

	</table>

	<br />

	<div align="center">
		<input type="submit" value="{$lang['acceptable']}" name="submit" />
	</div>
</form>
