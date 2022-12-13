<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
 <a href="index.php?page=template&amp;control=1&amp;main=1">{$lang['templates']}</a> &raquo;
  <a href="index.php?page=template&amp;search=1&amp;main=1">{$lang['search_templates']}</a>
 </div>

<br />

<form action="index.php?page=template&amp;search=1&amp;start=1"  name="myform" method="post">
<table cellpadding="3" cellspacing="1" width="80%" class="t_style_b" border="0" cellspacing="1" align="center">
<tr valign="top" align="center">
	<td class="main1" colspan="2">{$lang['search_templates']}</td>
</tr>
<tr valign="top">
		<td class="row2">{$lang['search_style']}</td>
		<td class="row2">
<select name="style" id="select_style">
<option value="all">{$lang['search_all_styles']}</option>
{Des::while}{StyleList}
	<option value="{$StyleList['id']}">{$StyleList['style_title']}</option>
{/Des::while}
</select>

</td>
</tr>
<tr valign="top">
		<td class="row1">{$lang['search_for_text']}</td>
		<td class="row1">
<textarea name="text" id="textarea_text" class="inputbox" tabindex="3" rows="5" cols="40" ></textarea>
</td>
</tr>

<tr valign="top">
		<td class="row1">{$lang['search_jast_titles']}</td>
		<td class="row1">
<input name="titlesonly" value="1" id="rb_1_titlesonly" type="radio" checked>{$lang['yes']}&nbsp;&nbsp;
<input name="titlesonly" value="0" checked id="red" type="radio">{$lang['no']}
		</td>
</tr>

<tr valign="top">
		<td class="row2" colspan="2" align="center"><input class="submit" type="submit" value="{$lang['search']}" name="submit" accesskey="s" /></td>
</tr>
</table><br />
</form>