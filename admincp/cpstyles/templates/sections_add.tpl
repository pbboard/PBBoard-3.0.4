<script src="includes/js/jquery.js">-->
</script>

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
 <a href="index.php?page=sections&amp;control=1&amp;main=1">{$lang['Sections_Mains']}</a> &raquo;
  {$lang['Add_new_Main_section']}</div>

<br />

<form action="index.php?page=sections&amp;add=1&amp;start=1" name="myform" method="post">

<table cellspacing="1" width="60%" class="t_style_b" border="0" align="center">
<tr valign="top" align="center">
	<td class="main1" colspan="2">{$lang['Add_new_Main_section']}</td>
</tr>
<tr valign="top">
		<td class="row1">{$lang['Section_name']}</td>
		<td class="row1">
<input type="text" name="name" id="input_name" value="" size="30" />&nbsp;
</td>
</tr>
<tr valign="top">
		<td class="row2">{$lang['Section_ordr']}</td>

		<td class="row2">
			<select name="order_type" id="order_type_id">
				<option value="auto" selected="selected">{$lang['auto_order']}</option>
				<option value="manual">{$lang['manual_order']}</option>
			</select>
			<input type="text" name="sort" id="sort_id" value="" size="5" />
</td>
</tr>
</table><br />

<table cellspacing="1" width="60%" class="t_style_b" border="0" align="center">
<tr valign="top" align="center">
	<td class="main1" colspan="4">{$lang['Powers_short']}</td>
</tr>
<tr valign="top" align="center">
	<td class="main2">{$lang['Group']}</td>
	<td class="main2">{$lang['View_forums']}</td>
</tr>
{Des::while}{groups}
<tr valign="top" align="center">
	<td class="row1">{$groups['title']}</td>
	<td class="row2">
			<label for="groups[{$groups['group_id']}][view_section]">
			{if {$groups['id']} == '6'}
			<input name="groups[{$groups['id']}][view_section]" id="input_view_section" value="1" tabindex="1" type="radio">{$lang['yes']}
			<input name="groups[{$groups['id']}][view_section]" id="input_view_section" value="0" tabindex="1" checked type="radio">{$lang['no']}
			{else}
			<input name="groups[{$groups['id']}][view_section]" id="input_view_section" value="1" tabindex="1" checked type="radio">{$lang['yes']}
			<input name="groups[{$groups['id']}][view_section]" id="input_view_section" value="0" tabindex="1" type="radio">{$lang['no']}
			{/if}
            </label>
            </td>
</tr>
{/Des::while}
</table><br />

<div align="center">
	<input class="submit" type="submit" value="{$lang['acceptable']}" name="submit" accesskey="s" />
</div>
<br />

</form>
