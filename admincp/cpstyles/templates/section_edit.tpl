<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
 <a href="index.php?page=sections&amp;control=1&amp;main=1">{$lang['Sections_Mains']}</a> &raquo;
  {$lang['edit']} : {$Inf['title']}</div>

<br />

<form action="index.php?page=sections&amp;edit=1&amp;start=1&amp;id={$Inf['id']}"  name="myform" method="post">
<table cellpadding="3" cellspacing="1" width="60%" class="t_style_b" border="0" cellspacing="1" align="center">
<tr valign="top" align="center">
	<td class="main1" colspan="2">{$lang['edit_Section_Main']} :
	{$Inf['title']}</td>
</tr>
<tr valign="top">
		<td class="row1">{$lang['Section_name']}</td>
		<td class="row1">

<input type="text" name="name" id="input_name" value="{$Inf['title']}" size="30" />&nbsp;
</td>
</tr>
<tr valign="top">
		<td class="row2">{$lang['Section_ordr']}</td>
		<td class="row2">
<input type="text" name="sort" id="input_sort" value="{$Inf['sort']}" size="30" />&nbsp;
</td>
</tr>
</table><br />
<tr>
	<td class="submit-buttons" colspan="2" align="center">
	<input class="submit" type="submit" value="{$lang['acceptable']}" name="submit" accesskey="s" /></td>

</tr>
</table><br />
</form>

