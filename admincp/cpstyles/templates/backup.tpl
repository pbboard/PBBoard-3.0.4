<div class="address_bar">{$lang['Control_Panel']} &raquo;
 {$lang['Maintenance']} &raquo;
 <a href="index.php?page=backup&amp;backup=1&amp;main=1">{$lang['backup']}</a></div>
<br />
<form action="index.php?page=backup&amp;backup=1&amp;start=1"  name="form1" method="post">
<table cellpadding="3" cellspacing="1" width="80%" class="t_style_b" border="0" cellspacing="1" align="center">
		<tr>
			<td class="main1" colspan="2" align="center">
			<b>{$lang['Download_a_copy_backup_of_the_database']}</b>
			</td>
		</tr>
		<tr valign="top">
			<td class="row2" colspan="2">
			{$lang['Warninig_a_backup_of_the_database']}
			</td>
		</tr>
		<tr valign="top">
			<td class="row2">
			{$lang['Attenuation_backup_of_the_database']}
			</td>
			<td class="row2">
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr valign="top">
					<td>
						<input name="filename" value="{$filename}" size="60" dir="ltr" tabindex="1" type="text">
					</td>
				</tr>
			</table>
			</td>
		</tr>
		<tr>
			<td class="row2" colspan="2" align="center">
			<input name="submit2" class="button" tabindex="1" value="{$lang['Save']}" accesskey="s" type="submit">
			</td>
		</tr>
	</table>
</form>

<br />
<br />
<br />