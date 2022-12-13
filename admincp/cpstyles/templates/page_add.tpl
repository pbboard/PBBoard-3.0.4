<script src="../includes/js/jquery.js"></script>
<script language="javascript">

function OrderChange()
{
	value = $("#order_type_id").val();

	if (value == 'manual')
	{
		$("#sort_id").show();
	}
	else
	{
		$("#sort_id").hide();
	}
}

function Ready()
{
	value = $("#order_type_id").val();

	if (value == 'manual')
	{
		$("#sort_id").show();
	}
	else
	{
		$("#sort_id").hide();
	}

	$("#order_type_id").change(OrderChange);
}

$(document).ready(Ready);

-->
</script>
<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
 <a href="index.php?page=pages&amp;control=1&amp;main=1">{$lang['Pages']}</a> &raquo;
  <a href="index.php?page=pages&amp;add=1&amp;main=1">{$lang['Add_new_Page']}</a></div>


<br />

<form action="index.php?page=pages&amp;add=1&amp;start=1"  name="myform" method="post">
<div align="center">
<table cellpadding="3" cellspacing="1" width="97%" class="t_style_b" border="0" cellspacing="1">
<tr valign="top" align="center">
	<td class="main1" colspan="2">{$lang['Add_new_Page']}</td>
</tr>
<tr valign="top">
		<td class="row1">{$lang['Page_title']}</td>
		<td class="row1">

<input type="text" name="name" id="input_name" value="" size="30" />&nbsp;
</td>
</tr>
<tr valign="top">
		<td class="row1">{$lang['Page_Link']}</td>
		<td class="row1">

<input type="text" name="link" id="input_link" value="{$Inf['link']}" size="50" dir="ltr"/>&nbsp;
</td>
</tr>
<tr valign="top">
		<td class="row1">{$lang['order']}</td>
		<td class="row1">
<select name="order_type" id="order_type_id">
<option value="auto" selected="selected">{$lang['auto_order']}</option>
<option value="manual">{$lang['manual_order']}</option>
</select>
<input type="text" name="sort" id="sort_id" value="" size="2" />&nbsp;
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
<textarea cols="50" id="text" name="text" style="height:300px;width:77%;" dir="{$_CONF['info_row']['content_dir']}"></textarea>
{template}editor_js{/template}
</td>
</tr>
</table></div>
<br />
<div align="center">
	<input class="submit" type="submit" value="    {$lang['Save']}     " name="submit" accesskey="s"  /></td>
</div>
</form>
