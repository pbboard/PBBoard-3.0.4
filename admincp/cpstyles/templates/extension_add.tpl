<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=extension&amp;control=1&amp;main=1">{$lang['AEXtensions']}</a> &raquo;
 {$lang['add_new_extension']}</div>

<br />

<form action="index.php?page=extension&amp;add=1&amp;start=1" method="post">
	<table cellpadding="3" cellspacing="1" width="60%" class="t_style_b" border="0" cellspacing="1" align="center">
		<tr valign="top" align="center">
			<td class="main1" colspan="2">
			{$lang['add_new_extension']}
			</td>
		</tr>
		<tr valign="top">
			<td class="row1">
			{$lang['EXtension']}
		   <br />
		<small>{$lang['Example']}:</small> <b dir="ltr">.zip</b>
			</td>
			<td class="row1">
				<input type="text" name="extension" dir="ltr" />
			</td>
		</tr>
		<tr valign="top">
			<td class="row2">
			{$lang['Maximum_Size_in_KB']}
		   <br />
		<small>{$lang['Example']}:</small> <b>1000</b>
			</td>
			<td class="row2">
<input type="text" name="max_size" id="input_max_size" dir="ltr" size="5" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');">
			</td>
		</tr>
		<tr>
			<td class="row2">
			{$lang['MIME']}
			<br />
			<small>{$lang['optional']}</small>
			</td>
			<td class="row2">
				<input type="text" name="mime_type" dir="ltr" />
			</td>
		</tr>
	</table>

	<br />

	<div align="center">
		<input type="submit" value="{$lang['acceptable']}" name="submit" />
	</div>

	<br />
</form>
