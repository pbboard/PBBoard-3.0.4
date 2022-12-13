<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=style&amp;control=1&amp;main=1">{$lang['mange_styles']}</a> &raquo;
 {$lang['edit']} :
  {$Inf['style_title']}</div>

<br />

<form action="index.php?page=style&amp;edit=1&amp;start=1&amp;id={$Inf['id']}"  name="myform" method="post">
<table cellpadding="3" cellspacing="1" width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
<tr valign="top" align="center">
	<td class="main1" colspan="2">
	 {$lang['edit']} :
  {$Inf['style_title']}
	</td>
</tr>
<tr valign="top">
		<td class="row1">{$lang['stylename']}</td>
		<td class="row1">
<input type="text" name="name" id="input_name" value="{$Inf['style_title']}" size="30" />&nbsp;
</td>
</tr>
<tr valign="top">

		<td class="row2">{$lang['Would_you_like_to_activate_the_Style']}</td>
		<td class="row2">
		<select name="style_on" id="select_style_on">
		{if {$Inf['style_on']}}
		<option value="1" selected="selected">{$lang['yes']}</option>
		<option value="0">{$lang['no']}</option>
		{else}
		<option value="1">{$lang['yes']}</option>
		<option value="0" selected="selected">{$lang['no']}</option>
		{/if}
</select>
</td>
</tr>
<tr valign="top">
		<td class="row1">{$lang['styleordr']}</td>
		<td class="row1">
<input type="text" name="order" id="input_order" value="{$Inf['style_order']}" size="30" />&nbsp;
</td>
</tr>
<tr valign="top">
		<td class="row2">{$lang['Style_file_path']}</td>
		<td class="row2">
<input type="text" name="style_path" id="input_style_path" value="{$Inf['style_path']}" size="30" />&nbsp;
</td>
</tr>
<tr valign="top">
		<td class="row1">{$lang['Style_file_image_path']}</td>

		<td class="row1">
<input type="text" name="image_path" id="input_image_path" value="{$Inf['image_path']}" size="30" />&nbsp;
</td>
</tr>
</table><br />
<tr>
	<td class="submit-buttons" colspan="2" align="center">
	<input class="submit" type="submit" value="{$lang['acceptable']}" name="submit" accesskey="s" /></td>
</tr>
</table><br />
</form>
