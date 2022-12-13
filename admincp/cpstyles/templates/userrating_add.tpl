<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
 <a href="index.php?page=userrating&amp;control=1&amp;main=1">{$lang['userrating']}</a> &raquo;
  {$lang['add_new_userrating']}</div>

<br />

<form action="index.php?page=userrating&amp;add=1&amp;start=1" method="post">
	<table width="60%" class="t_style_b" border="0" cellspacing="1" align="center">
		<tr align="center">
			<td class="main1" colspan="2">
{$lang['add_new_userrating']}
			</td>
		</tr>
		<tr>
			<td class="row1">
{$lang['Icon_path_grade']}

			</td>
			<td class="row1">
         <textarea name="rating" rows="1" cols="50" wrap="virtual"></textarea>
			</td>
		</tr>
		<tr>
			<td class="row2">
{$lang['Posts_less_than']}
			</td>
			<td class="row2">
				<input type="text" name="posts" size="7" />
			</td>
		</tr>
		<tr>
			<td class="row2" colspan="2" align="center">
		<input type="submit" value="{$lang['ADD']}" name="submit" /></td>
		</tr>
	</table>

</form>
<br />
	<table width="60%" class="t_style_b" border="0" cellspacing="1" align="center">
		<tr align="center">
			<td class="main1">
			{$lang['Help']}
			</td>
		</tr>
		<tr>
			<td class="row1">
{$lang['HelpText']}
			</td>
		</tr>
</table>