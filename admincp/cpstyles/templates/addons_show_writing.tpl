<br />

<div class="address_bar">
{$lang['Control_Panel']}
&raquo; {$lang['addons_pbb']}
&raquo; <a href="index.php?page=addons&amp;writing_addon=1&amp;main=1">{$lang['writing_addon']}</a>
&raquo; {$lang['shwo_addons']}</div>
<br />

<form action="index.php?page=addons&amp;export=1&amp;export_writing=1&amp;xml_name={$xml_name}" method="post">
	<table width="88%" class="t_style_b" border="0" cellspacing="1" align="center">
		<tr align="center">
			<td class="main1" colspan="2">
			 {$xml_name}
			</td>
		</tr>
		<tr>
			<td class="row2" align="center">
<textarea name="context" id="textarea_context" rows="30" cols="50" wrap="virtual" dir="ltr">{$context}</textarea>
			</td>
		</tr>
		<tr>
			<td class="row2" colspan="2" align="center">
					<input type="submit" value="  {$lang['Save_the_form_of_an_XML_file']}  " name="submit" /></td>
		</tr>
		</table>

	<br />



	<br />

</form>