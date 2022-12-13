<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=options&amp;index=1">{$lang['mange_forum']}</a> &raquo;
 <a href="index.php?page=options&amp;close=1&amp;main=1">{$lang['board_close']}</a></div>

<br />

<form action="index.php?page=options&amp;close=1&amp;update=1"  name="myform" method="post">
<table cellpadding="3" cellspacing="1" width="60%" class="t_style_b" border="0" cellspacing="1" align="center">
<tr valign="top" align="center">
	<td class="main1" colspan="2">{$lang['board_close']}</td>
</tr>
<tr valign="top">
		<td class="row1">{$lang['board_close']}</td>
		<td class="row1">
<select name="board_close" id="select_board_close">
	{if {$_CONF['info_row']['board_close']}}
		<option value="1" selected="selected">{$lang['yes']}</option>
		<option value="0">{$lang['no']}</option>
	{else}
		<option value="1">{$lang['yes']}</option>
		<option value="0" selected="selected">{$lang['no']}</option>
	{/if}
</select>
</td>
</tr>
<tr valign="top" align="center">
<td class="main2" colspan="2">{$lang['board_msg']}</td>
</tr>
<tr valign="top" align="center">
	<td class="row1" colspan="2">
<link rel="stylesheet" href="../look/sceditor/minified/themes/default.min.css" id="theme-style" />
<script src="../look/sceditor/minified/sceditor.min.js"></script>
<script src="../look/sceditor/minified/formats/bbcode.js"></script>
<script src="../look/sceditor/languages/{$_CONF['info_row']['content_language']}.js"></script>
<textarea cols="50" id="text" name="board_msg" style="height:300px;width:77%;" dir="{$_CONF['info_row']['content_dir']}">{$_CONF['info_row']['board_msg']}</textarea>

{template}editor_js{/template}
</td>
	</tr>
</table>
</td>
</tr>
</table><br />
<div align="center">
	<input class="submit" type="submit" value="    {$lang['Save']}     " name="submit" accesskey="s" onClick="comm._submit();" /></td>
</div>
<br />
</form>
