<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=emailed&amp;main_del=1">{$lang['Subscriptions_del']}</a></div>

<br />

<form action="index.php?page=emailed&amp;start_del=1" method="post">
	<table cellpadding="3" cellspacing="1" width="80%" class="t_style_b" border="0" align="center">
		<tr align="center">
			<td class="main1" colspan="2">
			{$lang['Subscriptions_del']}
			</td>
		</tr>
		<tr>
			<td class="row1">
			</td>
			<td class="row1">
				<select name="del_all_emailed" id="select_del_all_emailed">
					<option value="0" selected="selected">{$lang['Choices']}</option>
					<option value="1">{$lang['del_all_emailed']}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td class="row1" colspan="2" align="center">
			<input type="submit" value="{$lang['acceptable']}" name="submit" /></td>
		</tr>
	</table>

	<br />
</form>
<br />