<br />

<div class="address_bar">
{$lang['Control_Panel']}
&raquo; {$lang['addons_pbb']}
&raquo; <a href="index.php?page=addons&amp;control=1&amp;main=1">{$lang['control_addons']}</a>
 &raquo; {$lang['edit']} :
 {$AddonsEdit['title']}</div>
<br />

<form action="index.php?page=addons&amp;edit=1&amp;start=1&amp;id={$AddonsEdit['id']}" method="post">
	<table width="88%" class="t_style_b" border="0" cellspacing="1" align="center">
		<tr align="center">
			<td class="main1" colspan="2">
			{$lang['edit']} : {$AddonsEdit['name']}
			</td>
		</tr>
<tr>
			<td class="row1">
			{$lang['File_Name']} XML
			<br />
			{$lang['Example']} : test.xml
			</td>
			<td class="row1">
				<input name="name" id="input_nme" value="{$AddonsEdit['name']}" size="20" type="text">
			</td>
		</tr>
<!--
<tr>
			<td class="row1">
			{$lang['Location']}
			</td>
			<td class="row1">
<select name="hookname" id="hookname" dir="ltr">
<option value="{$AddonsEdit['hookname']}" selected="selected">{$hookname}</option>
<option value="on_top_of_replace_links">on top of replace links</option>
<option value="on_buttom_of_replace_links">on buttom of replace links</option>
</select>
			</td>
		</tr>
-->
<tr>
			<td class="row1">
			{$lang['title']}
			</td>
			<td class="row1">
				<input name="title" id="input_title" value="{$AddonsEdit['title']}" size="30" type="text">
			</td>
		</tr>
<!--
		<tr>
			<td class="row1" valign="top">
{$lang['PHP_Code_as_well_as_private']}
			</td>
			<td class="row1">
				<textarea name="phpcode" id="textarea_phpcode" rows="12" cols="56" wrap="virtual" dir="ltr">{$phpcode}</textarea>
			</td>
		</tr>
-->
<tr>
			<td class="row1" valign="top">
{$lang['installcode']}
<br />
			</td>
			<td class="row1">
				<textarea name="installcode" id="textarea_installcode" rows="5" cols="56" wrap="virtual" dir="ltr">{$installcode}</textarea>
			</td>
		</tr>
<tr>
			<td class="row1" valign="top">
{$lang['uninstallcode']}
			</td>
			<td class="row1">
				<textarea name="uninstallcode" id="textarea_uninstallcode" rows="5" cols="56" wrap="virtual" dir="ltr">{$uninstallcode}</textarea>
			</td>
		</tr>
		<tr>
			<td class="row1">
			{$lang['version']}
			</td>
			<td class="row1">
				<input name="version" id="input_version" value="{$AddonsEdit['version']}" size="7" type="text">
			</td>
		</tr>
		<tr>
			<td class="row1">
			{$lang['addons_description']}
			</td>
			<td class="row1">
		 	<input name="description" id="input_description" value="{$AddonsEdit['description']}" size="55" type="text">
			</td>
		</tr>
		<tr>
			<td class="row1">
			{$lang['addons_url']}
			</td>
			<td class="row1">
		 	<input name="url" id="input_url" value="{$AddonsEdit['url']}" size="55" type="text">
			</td>
		</tr>
		<tr>
			<td class="row1">
			{$lang['addons_author']}
			</td>
			<td class="row1">
              <input name="author" id="input_author" value="{$AddonsEdit['author']}" size="55" type="text">
			</td>
		</tr>
		<tr>
			<td class="row1">
			{$lang['addons_Case']}
			</td>
			<td class="row1">
				<select name="active" id="select_active">
			     {if {$AddonsEdit['active']}}
				<option value="1" selected="selected">{$lang['activet']}</option>
				<option value="0">{$lang['noactive']}</option>
			    {else}
				<option value="1">{$lang['activet']}</option>
				<option value="0" selected="selected">{$lang['noactive']}</option>
			    {/if}
				</select>
			</td>
		</tr>
		<tr>
			<td class="row1" colspan="2" align="center">
					<input type="submit" value="  {$lang['Save']}  " name="submit" /></td>
		</tr>
		</table>

	<br />



	<br />

</form>