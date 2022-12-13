<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
{$lang['smiles']} &raquo; <a href="index.php?page=smile&amp;upload_smiles=1&amp;main=1">{$lang['upload_smiles']}</a></div>

<br />
<form name="addons" method="post" enctype="multipart/form-data" action="index.php?page=smile&amp;upload_smiles=1&amp;start=1">

 <form action="?upload=true" method="post" enctype="multipart/form-data">
<table width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
	<tr align="center">
		<td class="main1" colspan="2">
{$lang['upload_smiles']}
		</td>
	</tr>
	<tr align="center">
		<td class="row1">
			<input name="files_1" type="file" size="25" />
		</td>
				<td class="row1">
			<input name="files_2" type="file" size="25" />
		</td>
	</tr>
		</tr>
	<tr align="center">
		<td class="row1">
			<input name="files_3" type="file" size="25" />
		</td>
				<td class="row1">
			<input name="files_4" type="file" size="25" />
		</td>
	</tr>
		</tr>
	<tr align="center">
		<td class="row1">
			<input name="files_5" type="file" size="25" />
		</td>
				<td class="row1">
			<input name="files_6" type="file" size="25" />
		</td>
	</tr>
		</tr>
	<tr align="center">
		<td class="row1">
			<input name="files_7" type="file" size="25" />
		</td>
				<td class="row1">
			<input name="files_8" type="file" size="25" />
		</td>
	</tr>
		</tr>
	<tr align="center">
		<td class="row1">
			<input name="files_9" type="file" size="25" />
		</td>
				<td class="row1">
			<input name="files_10" type="file" size="25" />
		</td>
	</tr>
		</tr>
	<tr align="center">
		<td class="row1">
			<input name="files_11" type="file" size="25" />
		</td>
				<td class="row1">
			<input name="files_12" type="file" size="25" />
		</td>
	</tr>
	<tr align="center">
		<td class="row1" colspan="2">
		<input name="insert" type="submit" value="{$lang['upload']}" />
		</td>
	</tr>
</table>
</form>
<br />
<form name="imagespath" method="post" enctype="multipart/form-data" action="index.php?page=smile&amp;upload_smiles=1&amp;imagespath_smiles=1">
<table width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
	<tr align="center">
		<td class="main1" colspan="2">
{$lang['upload_file_smiles']}
		</td>
	</tr>
	<tr>
		<td class="row1">
{$lang['the_path_of_this_file_should_be_readable_and_writable']}
		</td>
				<td class="row1">
			<input name="files_imagespath" type="text" size="40" value="look/images/smiles" dir="ltr"/>
		</td>
	</tr>
	<tr align="center">
		<td class="row1" colspan="2">
		<input name="insert" type="submit" value="{$lang['upload']}" />
		</td>
	</tr>
</table>
</form>
<br />