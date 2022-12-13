<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
 <a href="index.php?page=usertitle&amp;control=1&amp;main=1">{$lang['usertitles']}</a> &raquo;
  {$lang['Delet']} :
  {$Inf['usertitle']}</div>

<br />

<table width="50%" class="t_style_b" border="0" cellspacing="1" align="center">
	<tr align="center">
		<td class="main1" colspan="2">
		{$lang['Confirm_the_deletion']}
		</td>
	</tr>
	<tr align="center">
		<td class="row1" colspan="2">
		{$lang['Are_you_sure_you_want_Delete']}
		</td>
	</tr>
	<tr align="center">
		<td class="row1">
			<a href="index.php?page=usertitle&amp;del=1&amp;start=1&amp;id={$Inf['id']}">{$lang['yes']}</a>
		</td>
		<td class="row1">
			<a href="index.php?page=usertitle&amp;control=1&amp;main=1">{$lang['no']}</a>
		</td>
	</tr>
</table>
