<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=lang&amp;control=1&amp;main=1">{$lang['langs']}</a> &raquo;
<a href="index.php?page=lang&amp;edit=1&amp;main=1&amp;id={$Inf['id']}"> {$lang['edit']} :
  {$Inf['lang_title']}</a></div>
<br />

<form action="index.php?page=lang&amp;edit=1&amp;start=1&amp;id={$Inf['id']}"  name="myform" method="post">
<table class="t_style_b" width="90%" align="center" border="0" align="center">
<tr valign="top" align="center">
	<td class="main1" colspan="2"> {$lang['edit']} :
  {$Inf['lang_title']}</td>
</tr>
<tr valign="top">
		<td class="row1">
		{$lang['language']}
		</td>
		<td class="row1">
<input type="text" name="name" id="input_name" value="{$Inf['lang_title']}" size="30" />&nbsp;
</td>
</tr>
<tr valign="top">
		<td class="row2">{$lang['content_dir']}
		</td>
		<td class="row2">
		<select name="dir" id="select_dir">
		{if {$Inf['lang_path']} == 'rtl'}
		<option value="rtl" selected="selected">RTL</option>
		<option value="ltr">LTR</option>
		<option value="{$_CONF['info_row']['content_dir']}">{$lang['default_design_block_latest_news']}</option>
		{elseif {$Inf['lang_path']} == 'ltr'}
		<option value="ltr" selected="selected">LTR</option>
		<option value="rtl">RTL</option>
		<option value="{$_CONF['info_row']['content_dir']}">{$lang['default_design_block_latest_news']}</option>
		{elseif {$Inf['lang_path']} != ''}
		<option value="ltr">LTR</option>
		<option value="rtl">RTL</option>
		<option value="{$_CONF['info_row']['content_dir']}" selected="selected">{$lang['default_design_block_latest_news']}</option>
		{/if}
</select>
</td>
</tr>
<tr valign="top">
		<td class="row2">{$lang['Would_you_like_to_activate_the_language']}
		</td>
		<td class="row2">
		<select name="lang_on" id="select_lang_on">
		{if {$Inf['lang_on']}}
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
		<td class="row2">{$lang['order_language']}</td>
		<td class="row2">
<input type="text" name="lang_order" id="input_lang_order" value="{$Inf['lang_order']}" size="1" />&nbsp;
</td>
</tr>
</table><br />
<tr>
	<td class="submit-buttons" colspan="2" align="center">
	<input class="submit" type="submit" value="{$lang['acceptable']}" name="submit" accesskey="s" /></td>
</tr>
</table><br />
</form>