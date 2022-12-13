<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=avatar&amp;control=1&amp;main=1">{$lang['avatars']}</a></div>

<br />

<form action="index.php?page=avatar&amp;add=1&amp;start=1" method="post">
	<table width="70%" class="t_style_b" border="0" cellspacing="1" align="center">
		<tr align="center">
			<td class="main1" colspan="2">
			{$lang['add_new_avatar']}
			</td>
		</tr>
		<tr>
			<td class="row1">
			{$lang['Image_Path']}
			</td>
			<td class="row1">
				<input type="text" name="path" dir="ltr" size="60" />
			</td>
		</tr>
 		<tr>
			<td class="row1" align="center" colspan="2">
		<input type="submit" value="{$lang['acceptable']}" name="submit" />
		</td>
		</tr>
   </table>
	<br />
</form>
