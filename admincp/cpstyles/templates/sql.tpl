<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
 {$lang['Maintenance']} &raquo;
 <a href="index.php?page=sql&amp;sql=1&amp;main=1">{$lang['Execute_a_Sql_in_the_databases']}</a></div>

<br />

<form action="index.php?page=sql&amp;sql=1&amp;start=1"  name="myform" method="post">
<div align="center">
<table cellpadding="3" cellspacing="1" width="80%" class="t_style_b" border="0" cellspacing="1">
<tr valign="top" align="center">
	<td class="main1">{$lang['Execute_a_Sql_in_the_databases']}</td>
</tr>
<tr valign="top">
		<td class="row1" width="80%" align="center">
<fieldset style="padding: 2px;">
<legend>{$lang['annotation']}</legend>
&nbsp;{$lang['If_you_execute_the_command_you_can_not_retreat']}</fieldset>
</td>
</tr>
<tr valign="top">
		<td class="row1" width="80%" align="center">
<textarea name="sqlstring"  rows="8" cols="50" wrap="virtual" dir="ltr"></textarea>&nbsp;
</td>
</tr>
<tr valign="top">
		<td class="row1" width="80%" align="center">
<input class="submit" type="submit" value="{$lang['Enter_the_command_in_the_database']}" name="submit"  /></td>
</tr>
</table></div>
<br />
</form>