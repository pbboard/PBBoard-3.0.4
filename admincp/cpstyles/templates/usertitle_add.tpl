<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
 <a href="index.php?page=usertitle&amp;control=1&amp;main=1">{$lang['usertitles']}</a>  &raquo;
  {$lang['Add_new_usertitle']}</div>

<br />

<form action="index.php?page=usertitle&amp;add=1&amp;start=1" method="post">
	<table width="60%" class="t_style_b" border="0" cellspacing="1" align="center">
		<tr align="center">
			<td class="main1" colspan="2">
			{$lang['Add_new_usertitle']}
			</td>
		</tr>
		<tr>
			<td class="row1">
			{$lang['user_titles']}
			</td>
			<td class="row1">
         <textarea name="title" rows="1" cols="50" wrap="virtual"></textarea>
			</td>
		</tr>
		<tr>
			<td class="row2">
			{$lang['Posts_less_than']}
			</td>
			<td class="row2">
				<input type="text" name="posts" size="30" />
			</td>
		</tr>
	</table>

	<br />

	<div align="center">
		<input type="submit" value="{$lang['acceptable']}" name="submit" />
	</div>

	<br />
</form>

