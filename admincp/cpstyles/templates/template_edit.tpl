<br />

<div class="address_bar">{$lang['Control_Panel']}
 <a href="index.php?page=template&amp;control=1&amp;main=1">{$lang['templates']}</a> &raquo;
  <a href="index.php?page=template&amp;control=1&amp;show=1&amp;id={$StyleInfo['id']}">{$StyleInfo['style_title']}</a> &raquo;
    <a href="index.php?page=template&amp;edit=1&main=1&amp;templateid={$TemplateEdit['templateid']}&amp;styleid={$StyleInfo['id']}">
   {$lang['edit']} :
   {$filename}</a></div>

<br />

<form action="index.php?page=template&amp;edit=1&amp;start=1&amp;templateid={$TemplateEdit['templateid']}" method="post">
	<table cellpadding="3" cellspacing="1" width="60%" class="t_style_b" border="0" align="center">
		<tr valign="top" align="center">
			<td class="main1" colspan="2">
			   {$lang['edit']} :
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
			<td class="row1">
			{$lang['TemplateForStyle']}
			</td>
			<td class="row1">
				{$StyleInfo['style_title']}
			</td>
		</tr>
		<tr valign="top">
			<td class="row1">
			{$lang['Free_on']}
			</td>
			<td class="row1">
				{$last_edit}
			</td>
		</tr>
		<tr valign="top">
			<td class="row1" colspan="2" align="center">
				<textarea rows="25" cols="50" name="context" dir="ltr">{$context}</textarea>
			</td>
		</tr>
<tr valign="top" align="center">
	<td class="row2" colspan="2">
<script type="text/javascript" src="../includes/js/find.js"></script>
<input type=button onClick="location.href='index.php?page=template&amp;view=1&main=1&amp;templateid={$TemplateEdit['templateid']}&amp;styleid={$StyleInfo['id']}'" value='{$lang['ViewOrginaltemplate']}'>
</td>
</tr>
		<tr valign="top">
			<td class="row1" colspan="2" align="center">
				<input type="submit" value="{$lang['Save_and_reload']}" name="submit" /></td>
		</tr>
	</table>

	<br />

</form>