<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=emailed&amp;main=1">{$lang['mange_postal']}</a></div>

<br />

<form action="index.php?page=emailed&amp;update=1" method="post">
	<table cellpadding="3" cellspacing="1" width="80%" class="t_style_b" border="0" align="center">
		<tr align="center">
			<td class="main1" colspan="2">
			{$lang['mange_postal']}
			</td>
		</tr>
		<tr>
			<td class="row1">
{$lang['allowed_emailed']}
			</td>
			<td class="row1">
				<select name="allowed_emailed" id="select_allowed_emailed">
					{if {$_CONF['info_row']['allowed_emailed']}}
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
{$lang['allowed_emailed_pm']}
			</td>
			<td class="row2">
				<select name="allowed_emailed_pm" id="select_allowed_emailed_pm">
					{if {$_CONF['info_row']['allowed_emailed_pm']}}
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
			<td class="row1" colspan="2" align="center">
			<input type="submit" value="{$lang['acceptable']}" name="submit" /></td>
		</tr>
	</table>

	<br />
</form>
<br />