<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=extension&amp;control=1&amp;main=1">{$lang['AEXtensions']}</a> &raquo;
 {$lang['edit']} :
 {$Inf['Ex']}</div>

<br />

<form action="index.php?page=extension&amp;edit=1&amp;start=1&amp;id={$Inf['id']}" method="post">
	<table width="60%" class="t_style_b" border="0" cellspacing="1" align="center">
		<tr align="center">
			<td class="main1" colspan="2">
			{$lang['edit']} :
 {$Inf['Ex']}
			</td>
		</tr>
		<tr>
			<td class="row1">
			{$lang['EXtension']}
			</td>
			<td class="row1">
				<input type="text" name="extension" dir="ltr" value="{$Inf['Ex']}" />
			</td>
		</tr>
		<tr>
			<td class="row2">
			{$lang['Maximum_Size_in_KB']}
			</td>
			<td class="row2">
				<input type="text" name="max_size" value="{$Inf['max_size']}" dir="ltr" size="5" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');">
			</td>
		</tr>
		<tr>
			<td class="row2">
			{$lang['MIME']}
			<br />
			<small>{$lang['optional']}</small>
			</td>
			<td class="row2">
				<input type="text" name="mime_type" dir="ltr" value="{$Inf['mime_type']}" />
			</td>
		</tr>
	</table>

	<br />

	<div align="center">
		<input type="submit" value="{$lang['acceptable']}" name="submit" />
	</div>

	<br />
</form>
