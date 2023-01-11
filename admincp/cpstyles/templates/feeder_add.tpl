<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
{$lang['feed_rss']} &raquo;<a href="index.php?page=feeder&amp;add=1&amp;main=1"> {$lang['feed']}</a></div>

<br />

<form action="index.php?page=feeder&amp;add=1&amp;start=1"  name="myform" method="post">
<div align="center">
<table cellpadding="3" cellspacing="1" width="90%" class="t_style_b" border="0" cellspacing="1">
<tr valign="top" align="center">
	<td class="main1" colspan="2">{$lang['feed']}</td>
</tr>
<tr valign="top">
		<td class="row1" width="20%">{$lang['Feed_URL']}</td>
		<td class="row1">

<input type="text" name="link" id="input_name" value="" size="30" />&nbsp;
</td>
</tr>
<tr valign="top">
		<td class="row1">{$lang['title']}</td>
		<td class="row1">
<input type="text" name="title2" id="input_name" value="" size="30" />
<input type="hidden" name="title" id="input_name" value="{rss:title}" size="30" />&nbsp;
</td>
</tr>
<tr valign="top">
		<td class="row1">{$lang['mem_name']}</td>
		<td class="row1">

<input type="text" name="member" id="input_name" value="" size="30" />&nbsp;
</td>
</tr>
<tr valign="top">
		<td class="row1">{$lang['by_Forum']}
</td>
		<td class="row1">
{$DoJumpList}
</td>
</tr>

<tr valign="top">
		<td class="row1">
{$lang['Check_the_feed_of_all']}
</td>
		<td class="row1">
			<select name="ttl" id="sel_ttl" tabindex="1">
			<option value="600">
			10 {$lang['minutes']}</option>
<option value="1200">
			20 {$lang['minutes']}</option>
<option value="1800" selected="selected">
			30 {$lang['minutes']}</option>
<option value="3600">
			60 {$lang['minutes']}</option>
<option value="7200">
			2 {$lang['hours']}</option>
<option value="14400">
			4 {$lang['hours']}</option>
<option value="21600">
			6 {$lang['hours']}</option>
<option value="28800">
			8 {$lang['hours']}</option>
<option value="36000">
			10 {$lang['hours']}</option>
<option value="43200">
			12 {$lang['hours']}</option>
<option value="86400">
			24 {$lang['hours']}</option>
<option value="172800">
			48 {$lang['hours']}</option>
<option value="259200">
			72 {$lang['hours']}</option>
<option value="604800">
			7 {$lang['Day']}</option>
			</select>

</td>
</tr>
<tr valign="top">
		<td class="row2">{$lang['Text_of_topic']}</td>
		<td class="row2">
{$lang['Defining_element_of_the_type_of_rss']}
({rss:<i>example</i>})
{$lang['where_example_is_the_name_of_the_item']}
<textarea name="text" id="textarea_text" rows="5" cols="50" wrap="virtual" dir="ltr">
{rss:title}
{rss:description}
{rss:link}
</textarea>&nbsp;
</td>
</tr>
</table></div>

<tr>
	<td class="submit-buttons" colspan="2" align="center">

	<input class="submit" type="submit" value="   {$lang['Save']}    " name="submit" accesskey="s" /></td>
</tr>
</table><br />
</form>
