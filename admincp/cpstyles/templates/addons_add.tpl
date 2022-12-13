<br />

<div class="address_bar">
{$lang['Control_Panel']}
&raquo; {$lang['addons_pbb']}
&raquo; <a href="index.php?page=addons&amp;add=1&amp;main=1">{$lang['add_addons']}</a></div>

<br />
<form name="addons" method="post" enctype="multipart/form-data" action="index.php?page=addons&amp;add=1&amp;start=1">

 <form action="?upload=true" method="post" enctype="multipart/form-data">
<table width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
	<tr align="center">
		<td class="main1" colspan="2">
		{$lang['addons_Import_XML_file']}
		</td>
	</tr>
	<tr align="center">
		<td class="row1">
			<input name="files" type="file" size="40" />
		</td>
	</tr>
	<tr align="center">
		<td class="row1" colspan="2">
		<input name="insert" type="submit" value="{$lang['import']}" />
		</td>
	</tr>
</table>
</form>
<br />

