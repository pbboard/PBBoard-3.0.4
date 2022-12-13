<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
 <a href="index.php?page=smile&amp;control=1&amp;main=1">{$lang['smiles']}</a> &raquo;
  {$lang['add_new_smile']}</div>

<br />

<form action="index.php?page=smile&amp;add=1&amp;start=1" name="myform" method="post">
<table cellpadding="3" cellspacing="1" width="60%" class="t_style_b" border="0" cellspacing="1" align="center">
<tr valign="top" align="center">
	<td class="main1" colspan="2">{$lang['add_new_smile']}</td>
</tr>
<tr valign="top">
		<td class="row1">{$lang['smile_short']}</td>
		<td class="row1">

<input type="text" name="short" id="input_short" value="" size="30" />&nbsp;
</td>
</tr>
<tr valign="top">
		<td class="row2">{$lang['Image_Path']}</td>
		<td class="row2">
<input type="text" name="path" id="input_path" value="" size="30" />&nbsp;
</td>
</tr>
</table><br />
<tr>
	<td class="submit-buttons" colspan="2" align="center">
	<input class="submit" type="submit" value="{$lang['acceptable']}" name="submit" accesskey="s" /></td>

</tr>
</table><br />
</form>
