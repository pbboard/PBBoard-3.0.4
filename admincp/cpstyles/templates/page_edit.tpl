<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
 <a href="index.php?page=pages&amp;control=1&amp;main=1">{$lang['Pages']}</a> &raquo;
  {$lang['edit']} :
   {$Inf['title']}</div>

<br />

<form action="index.php?page=pages&amp;edit=1&amp;start=1&amp;id={$Inf['id']}"  name="myform" method="post">
<table cellpadding="3" cellspacing="1" width="97%" class="t_style_b" border="0" cellspacing="1" align="center">
<tr valign="top" align="center">
	<td class="main1" colspan="2">
	{$lang['edit']} :
   {$Inf['title']}</td>
</tr>
<tr valign="top">
		<td class="row1">{$lang['Page_title']}</td>
		<td class="row1">

<input type="text" name="name" id="input_name" value="{$Inf['title']}" size="30" />&nbsp;
</td>
</tr>
<tr valign="top">
		<td class="row1">{$lang['Page_Link']}</td>
		<td class="row1">

<input type="text" name="link" id="input_link" value="{$Inf['link']}" size="50" />&nbsp;
</td>
</tr>
<tr valign="top">
		<td class="row1">{$lang['order']}</td>
		<td class="row1">

<input type="text" name="sort" id="input_sort" value="{$Inf['sort']}" size="2" />&nbsp;
</td>
</tr>
<tr valign="top">
<td class="row1" colspan="2">
{$lang['CodeH']}
<br />
<b>{$lang['Page_info']}</b>
<br />
<link rel="stylesheet" href="../look/sceditor/minified/themes/default.min.css" id="theme-style" />
<script src="../look/sceditor/minified/sceditor.min.js"></script>
<script src="../look/sceditor/minified/formats/bbcode.js"></script>
<script src="../look/sceditor/languages/{$_CONF['info_row']['content_language']}.js"></script>
<textarea cols="50" id="text" name="text" style="height:300px;width:77%;" dir="{$_CONF['info_row']['content_dir']}">{$Inf['html_code']}</textarea>

{template}editor_js{/template}
</td>
</tr>
</table><br />
<tr>
	<td class="submit-buttons" colspan="2" align="center">

	<input class="submit" type="submit" value="{$lang['acceptable']}" name="submit" accesskey="s" /></td>
</tr>
</table><br />
</form>
