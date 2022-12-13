<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
 <a href="index.php?page=avatar&amp;control=1&amp;main=1">{$lang['avatars']}</a> &raquo;
  {$lang['edit']} : {$Inf['avatar_path']}</div>

<br />

<form action="index.php?page=avatar&amp;edit=1&amp;start=1&amp;id={$Inf['id']}" method="post">
	<table width="80%" class="t_style_b" border="0" cellspacing="1" align="center">
		<tr align="center">
			<td class="main1" colspan="2">
			{$lang['edit']} : {$Inf['avatar_path']}
			</td>
		</tr>
		<tr>
			<td class="row1">
			{$lang['Image_Path']}
			</td>
			<td class="row1">
				<input type="text" name="path" value="{$Inf['avatar_path']}" dir="ltr" size="60" />
			</td>
		</tr>
		<tr>
			<td class="row1" colspan="2" align="center">
		<input type="submit" value="{$lang['acceptable']}" name="submit" /></td>
		</tr>
	</table>

	<br />
	<br />

</form>