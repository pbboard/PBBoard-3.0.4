<br />

<div class="address_bar">
{$lang['Control_Panel']}
&raquo; {$lang['addons_pbb']}
&raquo; <a href="index.php?page=addons&amp;control_hooks=1&amp;main=1">{$lang['control_hooks']} </a>
&raquo; <a href="index.php?page=addons&amp;edit_hook=1&amp;main=1&amp;id={$HooksInfo['id']}">{$lang['edit_hook']} </a>
</div>
<br />
<form action="index.php?page=addons&amp;edit_hook=1&amp;start=1&amp;id={$HooksInfo['id']}" method="post">
	<table width="100%" class="t_style_b" border="0" cellspacing="1" align="center">
		<tr align="center">
			<td class="main1" colspan="2">
			{$lang['edit_hook']}
			</td>
		</tr>
<tr>
			<td class="row2">
			{$lang['sel_addons']}
			</td>
			<td class="row2">
  <?php

      	$addon_id = $PowerBB->_CONF['template']['HooksInfo']['addon_id'];
        $GetAddon = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['addons'] . " WHERE id = '$addon_id'");
        $Addon = $PowerBB->DB->sql_fetch_array($GetAddon);
        $title = $Addon['title'];
?>
<select name="addons" id="sel_addons" dir="ltr">
<option value="{$HooksInfo['addon_id']}" selected="selected"><?php echo $title; ?></option>
{Des::while}{AddonsList}
<option value="{$AddonsList['id']}">{$AddonsList['title']}</option>
{/Des::while}
<option value="">{$lang['no_place']}</option>

</select>
			</td>
		</tr>

<tr>
			<td class="row2">
			{$lang['Location']}
			</td>
			<td class="row2">
<input type="text" name="place_of_hook" id="place_of_hook" value="{$HooksInfo['main_place']}" size="30" dir="ltr">

			</td>
		</tr>

		<tr width="100%">
			<td class="row1" align="center" width="100%" colspan="2">
<?php

$PowerBB->_CONF['template']['HooksInfo']['phpcode']  = str_replace("{sq}", "'", $PowerBB->_CONF['template']['HooksInfo']['phpcode']);

?>
{$lang['PHP_Code_as_well_as_private']}
<br />
<font color="#FF0000">{$lang['RewriteEnginetextarea']}</font>
<br />

				<textarea name="phpcode" id="textarea_phpcode"  style="width: 80%;max-width: 80%;" rows="30" wrap="virtual" dir="ltr">{$HooksInfo['phpcode']}</textarea>
			</td>
		</tr>
		<tr>
			<td class="row2" align="center" colspan="2">
					<input type="submit" value="  {$lang['execution_submit']}  " name="start_writing" />
					</td>
		</tr>
		</table>

	<br />



	<br />

</form>