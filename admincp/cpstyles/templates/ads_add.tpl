<br />

<div class="address_bar">{$lang['Control_Panel']}
 &raquo; <a href="index.php?page=ads&amp;control=1&amp;main=1">{$lang['Commercials']}</a>
 &raquo; {$lang['Add_new_Commercials']}</div>

<br />

<form action="index.php?page=ads&amp;add=1&amp;start=1" method="post">
	<table width="80%" class="t_style_b" border="0" cellspacing="1" align="center">
		<tr align="center">
			<td class="main1" colspan="2">
			{$lang['Add_new_Commercials']}
			</td>
		</tr>
		<tr>
			<td class="row1">
			{$lang['Site_Name']}
			</td>
			<td class="row1">
				<input type="text" name="name" />
			</td>
		</tr>
		<tr>
			<td class="row2">
			{$lang['link']}
			</td>
			<td class="row2">
				<input type="text" name="link" size="60" dir="ltr"/>
			</td>
		</tr>
		<tr>
			<td class="row1">
			{$lang['Link_to_image']}
			</td>
			<td class="row1">
				<input type="text" name="picture" size="60" dir="ltr" />
			</td>
		</tr>
		<tr>
			<td class="row2">
			{$lang['Width_of_the_image_is_not_necessary']}
			</td>
			<td class="row2">
				<input type="text" name="width" size="5" />
			</td>
		</tr>
		<tr>
			<td class="row1">
			{$lang['Height_of_the_image_is_not_necessary']}
			</td>
			<td class="row1">
				<input type="text" name="height" size="5"/>
			</td>
			</tr>
		<tr>
			<td class="row1" colspan="2">
		{$lang['ads_not']}</td>
		</tr>
		<tr>
			<td class="row1" colspan="2" align="center">
		<input type="submit" value="{$lang['acceptable']}" name="submit" /></td>
		</tr>
	</table>

	<br />

	<br />

</form>