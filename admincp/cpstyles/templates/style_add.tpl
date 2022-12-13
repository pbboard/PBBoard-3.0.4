<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=style&amp;control=1&amp;main=1">{$lang['mange_styles']}</a> &raquo;
 <a href="index.php?page=style&amp;add=1&amp;main=1">{$lang['add_new_style']}</a>
 </div>

<br />
<form name="importstyle" method="post" enctype="multipart/form-data" action="index.php?page=style&amp;import=1&amp;start=1">
<table cellpadding="3" cellspacing="1" width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
<tr valign="top" align="center">
<td class="main1" colspan="2">{$lang['uploadstyle']}
</td>
</tr>

	<tr valign="top">
<td class="row1">{$lang['style_upload_XML_file']}</td>
<td class="row1">
<table width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
	<tr align="center">
		<td class="row1">
			<input name="files" type="file" size="40" />
		</td>
	</tr>
</table>
</td>
</tr>
<tr valign="top">
<td class="row1">{$lang['import_xml_file']}</td>
	<td class="row1">
<table width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
<tr valign="top"><td>
<input type="text" class="input" name="serverfile" id="it_serverfile" value="install/PBBoard-style.xml" size="45" dir="ltr" tabindex="1" /></td></tr></table></td>
</tr>
<tr valign="top">
<td class="row1">{$lang['Would_you_like_to_activate_the_Style']}</td>
<td class="row1">
<select name="style_on" id="select_style_on">
	<option value="1" selected="selected">{$lang['yes']}</option>
	<option value="0" >{$lang['no']}</option>
</select></td>
</tr>
<tr valign="top">
<td class="row1">
{$lang['ignore_the_version_style']}
<br />
{$lang['use_the_style_anyway']}
</td>
<td class="row1">
<input name="anyversion" value="1" id="anyversion" type="radio">{$lang['yes']}
&nbsp;&nbsp;<input name="anyversion" value="0" id="anyversion" type="radio" checked="checked">{$lang['no']}
</select>
</td>
</tr>
<tr valign="top">
<td class="main2" colspan="2" align="center">
<input TYPE="hidden" type="text" name="order" id="input_order" value="{$order}" size="30" />
    <input name="insert" type="submit" value="{$lang['Import']}" />
     </td>
</tr>
</table>
</form>
