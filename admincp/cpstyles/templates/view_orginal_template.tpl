<br />

<div class="address_bar">{$lang['Control_Panel']}   &raquo;
 <a href="index.php?page=template&amp;control=1&amp;main=1">{$lang['templates']}</a> &raquo;
    <a href="index.php?page=template&amp;view=1&main=1&amp;templateid={$TemplateEdit['templateid']}&amp;styleid={$StyleInfo['id']}">
 {$lang['ViewOrginaltemplate']} :
   {$filename}</a></div>

<br />
<form action="index.php?page=template&amp;view=1&amp;start=1&amp;templateid={$TemplateEdit['templateid']}" method="post">

	<table cellpadding="3" cellspacing="1" width="60%" class="t_style_b" border="0" align="center">
		<tr valign="top" align="center">
			<td class="main1" colspan="2">
			   {$lang['ViewOrginaltemplate']} :
   {$filename}
			</td>
		</tr>
		<tr valign="top">
			<td class="row1">
			{$lang['Template_name']}
			</td>
			<td class="row1">
				{$filename}
			</td>
		</tr>
		<tr valign="top">
			<td class="row1" colspan="2" align="center">
<textarea rows="30" cols="50" name="context" dir="ltr">{$context}</textarea>
			</td>
		</tr>
		<tr valign="top">
			<td class="row2" colspan="2" align="center">
				<input type="submit" value="{$lang['reset']}" name="submit" /></td>
		</tr>
	</table>

	<br />
	</form>