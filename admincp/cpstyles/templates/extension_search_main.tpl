<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=extension&amp;search=1&amp;main=1">{$lang['extensions']}</a> &raquo;
 {$lang['search_in_attachmint']} </div>

<br />
	<table width="60%" class="t_style_b" border="0" cellspacing="1" align="center">
		<tr>
			<td class="row1" align="center">
<a class="button" href="index.php?page=subject&amp;attach=1&amp;main=1" target="main">
&nbsp; {$lang['subjects_attach']} &nbsp;
</a>
			</td>
		</tr>
	</table>
 <br />
<form action="index.php?page=extension&amp;search=1&amp;start=1" method="post">
	<table width="60%" class="t_style_b" border="0" cellspacing="1" align="center">
		<tr align="center">
			<td class="main1" colspan="2">
			{$lang['search_in_attachmint']}
			</td>
		</tr>
		<tr>
			<td class="row1">
			{$lang['Word_Search']}
			</td>
			<td class="row1">
				<input type="text" name="keyword" id="input_keyword" />
			</td>
		</tr>
		<tr>
			<td class="row2">
			{$lang['search_by']}
			</td>
			<td class="row2">
				<select name="search_by" id="select_search_by">
					<option value="filename">{$lang['filename']}</option>
					<option value="filesize">{$lang['filesize']}</option>
					<option value="visitor">{$lang['DownloadsNum']}</option>
				</select>
			</td>
		</tr>
	</table>

	<br />

	<div align="center">
		<input type="submit" value="{$lang['acceptable']}" name="submit" />
	</div>

</form>
