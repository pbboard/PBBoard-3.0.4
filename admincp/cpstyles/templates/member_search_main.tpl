<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=member&amp;control=1&amp;main=1">{$lang['members']}</a> &raquo;
 {$lang['Member_Search']}</div>

<br />

<form action="index.php?page=member&amp;search=1&amp;start=1"  name="myform" method="post">
<table cellpadding="3" cellspacing="1" width="60%" class="t_style_b" border="0" cellspacing="1" align="center">
<tr valign="top" align="center">
	<td class="main1" colspan="2">{$lang['Member_Search']}</td>
</tr>
<tr valign="top">
		<td class="row1">{$lang['Word_Search']}</td>
		<td class="row1">
<input type="text" name="keyword" id="input_keyword" value="" size="30" />&nbsp;
</td>
</tr>
<tr valign="top">

		<td class="row2">{$lang['search_by']}</td>
		<td class="row2">
<select name="search_by" id="select_search_by">
	<option value="username" >{$lang['username']}</option>
	<option value="email" >{$lang['email']}</option>
	<option value="mid" >{$lang['Member_Id']}</option>
	<option value="member_ip" >{$lang['member_ip']}</option>
</select>
</td>

</tr>
</table><br />
<tr>
	<td class="submit-buttons" colspan="2" align="center">
	<input class="submit" type="submit" value="{$lang['acceptable']}" name="submit" accesskey="s" /></td>
</tr>
</table><br />
</form>
