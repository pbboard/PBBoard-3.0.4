<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=member&amp;control=1&amp;main=1">merge_members</a> </div>

<br />

<form action="index.php?page=member&amp;merge=1&amp;start=1" method="post">

	<table width="60%" class="t_style_b" border="0" cellspacing="1" align="center">
		<tr>
			<td class="main1" colspan="2">merge_members</td>
		</tr>
		<tr>
			<td class="row1">
			{$lang['user_get']}
			</td>
			<td class="row1">
				<input type="text" name="user_get" size="30" />
			</td>
		</tr>
		<tr>
			<td class="row2">
			{$lang['user_to']}
			</td>
			<td class="row2">
				<input type="text" name="user_to" size="30" />
			</td>
		</tr>
		<tr>
			<td class="row2" colspan="2">
			{$lang['merge_annotation']}
			</td>
		</tr>
		<tr>
			<td class="row2" colspan="2" align="center">
		<input type="submit" value="{$lang['acceptable']}" name="submit" /></td>
		</tr>
	</table>

	<br />

	<br />

</form>