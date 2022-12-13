<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
 <a href="index.php?page=template&amp;control=1&amp;main=1">{$lang['templates']}</a> &raquo;
 <a href="index.php?page=template&amp;add=1&amp;main=1">{$lang['add_new_template']}</a> </div>

<br />

<form action="index.php?page=template&amp;add=1&amp;start=1"  name="myform" method="post">
<table cellpadding="3" cellspacing="1" width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
<tr valign="top" align="center">
	<td class="main1" colspan="2">{$lang['add_new_template']}</td>
</tr>
<tr valign="top">
		<td class="row1">{$lang['Template_filename']}</td>
		<td class="row1">

<input type="text" name="filename" id="input_filename" value="" size="30" />&nbsp;
</td>
</tr>
<tr valign="top">
		<td class="row2">{$lang['TemplateForStyle']}</td>
		<td class="row2">
<select name="style" id="select_style">
{Des::while}{StyleList}
	<option value="{$StyleList['id']}">{$StyleList['style_title']}</option>
{/Des::while}
</select>

</td>
</tr>
<tr valign="top" align="center">
	<td class="row1" colspan="2">{$lang['TemplateContext']}</td>
</tr>
<tr valign="top" align="center">
	<td class="row1" colspan="2">
<textarea name="context" id="textarea_context" rows="40" cols="50" wrap="virtual" dir="ltr"></textarea>&nbsp;
</td>
</tr>
<tr valign="top" align="center">
	<td class="row2" colspan="2">
	<input class="submit" type="submit" value="{$lang['acceptable']}" name="submit" accesskey="s" /></td>
</td>
</tr>
</table><br />
<br />
</form>
