<br />

<div class="address_bar">
{$lang['Control_Panel']}
&raquo; {$lang['addons_pbb']}
&raquo; {$lang['writing_addon']}
&raquo; {$lang['edit_hook']}
</div>
<br />
<form action="index.php?page=addons&amp;edit_hook=1&amp;start=1&amp;id={$HooksInfo['id']}" method="post">
	<table width="88%" class="t_style_b" border="0" cellspacing="1" align="center">
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
			{$lang['main_location_place']}
			</td>
			<td class="row2">
<select name="main_place" id="main_place" dir="ltr">
<option value="{$HooksInfo['main_place']}" selected="selected">{$HooksInfo['main_place']}</option>
<option value="index">index</option>
<option value="forum">forum</option>
<option value="topic">topic</option>
<option value="profile">profile</option>
<option value="usercp">usercp</option>
<option value="archive">archive</option>
<option value="new_topic">new_topic</option>
<option value="new_reply">new_reply</option>
<option value="pm">pm</option>
<option value="search">search</option>
<option value="register">register</option>
<option value="">{$lang['no_place']}</option>
</select>
			</td>
		</tr>
<tr>
			<td class="row2">
			{$lang['Location']}
			</td>
			<td class="row2">
<select name="place_of_hook" id="place_of_hook" dir="ltr">
<option value="{$HooksInfo['place_of_hook']}" selected="selected">{$HooksInfo['place_of_hook']}</option>
<option value="on_top_of_replace_links">on_top_of_replace_links</option>
<option value="on_buttom_of_replace_links">on_buttom_of_replace_links</option>
<option value="">{$lang['no_place']}</option>
</select>
			</td>
		</tr>

		<tr>
			<td class="row1" valign="top">
{$lang['PHP_Code_as_well_as_private']}

			</td>
			<td class="row1">
				<textarea name="phpcode" id="textarea_phpcode" rows="12" cols="56" wrap="virtual" dir="ltr">{$HooksInfo['phpcode']}</textarea>
			</td>
		</tr>
		<tr>
			<td class="row2" colspan="2" align="center">
					<input type="submit" value="  {$lang['execution_submit']}  " name="start_writing" />
					</td>
		</tr>
		</table>

	<br />



	<br />

</form>