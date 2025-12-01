<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
 <a href="index.php?page=award&amp;control=1&amp;main=1">{$lang['awards']}</a> &raquo;
  {$lang['add_new_award']}</div>

<br />

<form action="index.php?page=award&amp;add=1&amp;start=1" method="post" enctype="multipart/form-data">
	<table width="60%" class="t_style_b" border="0" cellspacing="1" align="center">
		<tr align="center">
			<td class="main1" colspan="2">
			{$lang['add_new_award']}
			</td>
		</tr>
		<tr>
			<td class="row1">
			{$lang['title_award']}
		   </td>
			<td class="row1">
				<input type="text" name="award" size="20" />
				</td>
		</tr>
		<tr>
			<td class="row1">
			{$lang['mem_name']}</td>
			<td class="row1">
				<input type="text" name="username" size="20" /></td>
		</tr>
		<tr>
			<td class="row1">
			{$lang['image_award']}
			</td>
			<td class="row1">
				<input name="award_path" style="border: 1px solid #666;" type="file" accept=".jpg, .jpeg, .png, .gif" size="25" dir="ltr" /><br /> النوع (mime) <b dir='ltr'><u>.jpg .jpeg .png .gif .bmp</u></b>
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