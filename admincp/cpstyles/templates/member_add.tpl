<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=member&amp;control=1&amp;main=1">{$lang['members']}</a> &raquo;
 {$lang['Add_new_member']}</div>

<br />

<form action="index.php?page=member&amp;add=1&amp;start=1"  name="myform" method="post">
	<table width="60%" class="t_style_b" border="0" cellspacing="1" align="center">
	<tr align="center">
		<td class="main1" colspan="2">
		{$lang['Add_new_member']}
		</td>
	</tr>
	<tr>
		<td class="row1">
		{$lang['username']}
		</td>
		<td class="row1">
			<input type="text" name="username" />
		</td>
	</tr>
	<tr>
		<td class="row2">
		{$lang['password']}
		</td>
		<td class="row2">
			<input type="password" name="password" />
		</td>
	</tr>
	<tr>
		<td class="row1">
		{$lang['email']}
		</td>
		<td class="row1">
			<input type="text" name="email" />
		</td>
	</tr>
	<tr>
		<td class="row2">
		{$lang['Gender']}
		</td>
		<td class="row2">
			<select name="gender">
				<option value="m">{$lang['Male']}</option>
				<option value="f">{$lang['Female']}</option>
			</select>
		</td>
	</tr>
	</table>

	<br />

	<div align="center">
		<input type="submit" value="{$lang['acceptable']}" name="submit" />
	</div>

</form>
