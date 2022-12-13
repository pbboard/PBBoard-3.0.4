<script src="includes/js/jquery.js"></script>
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
{$lang['portal']}
&raquo; <a href="index.php?page=portal&amp;add_block=1&amp;main=1">{$lang['add_block']}</a>
</div>

<br />

<form action="index.php?page=portal&amp;add_block=1&amp;start=1" method="post">
	<table width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
		<tr align="center">
			<td class="main1" colspan="2">
{$lang['add_block']}
			</td>
		</tr>
<tr>
			<td class="row2">
			{$lang['block_title']}
			</td>
			<td class="row2">
				<input name="title" id="input_title" value size="30" type="text">
			</td>
		</tr>
		<tr>
			<td class="row1" colspan="2">
{$lang['block_text']}
<br />
<b>{$lang['Writable_Bocuad_Html']}</b>
<br />
<link rel="stylesheet" href="../look/sceditor/minified/themes/default.min.css" id="theme-style" />
<script src="../look/sceditor/minified/sceditor.min.js"></script>
<script src="../look/sceditor/minified/formats/bbcode.js"></script>
<script src="../look/sceditor/languages/{$_CONF['info_row']['content_language']}.js"></script>
<textarea cols="50" id="text" name="text" style="height:300px;width:77%;" dir="{$_CONF['info_row']['content_dir']}"></textarea>
{template}editor_js{/template}
			</td>
		</tr>
		<tr>
			<td class="row2">
{$lang['block_place']}
	</td>
			<td class="row2">
				<select name="place_block" id="select_place_block">
			<option value="left">{$lang['left']}</option>
			<option value="right" selected="selected">{$lang['right']}</option>
			<option value="center">{$lang['center']}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td class="row2">
			{$lang['block_order']}
			</td>
			<td class="row2">
				<select name="order_type" id="order_type_id">
					<option value="auto" selected="selected">{$lang['auto_order']}</option>
					<option value="manual">{$lang['manual_order']}</option>
				</select>
				<input type="text" name="sort" id="sort_id" value="" size="5" />
			</td>
		</tr>

		<tr>
			<td class="row2" colspan="2" align="center">
			<input type="submit" value="{$lang['acceptable']}" name="submit" /></td>
		</tr>

		</table>

	<br />



	<br />

</form>