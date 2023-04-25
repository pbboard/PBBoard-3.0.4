<br />

<div class="address_bar">
{$lang['Control_Panel']}
&raquo; {$lang['addons_pbb']}
&raquo; <a href="index.php?page=addons&amp;writing_addon=1&amp;main=1">{$lang['writing_addon']} </a>
</div>
<br />
<form action="index.php?page=addons&amp;writing_addon=1&amp;start=1" method="post">
	<table width="88%" class="t_style_b" border="0" cellspacing="1" align="center" dir="ltr">
		<tr align="center">
			<td class="main1" colspan="2">
			{$lang['writing_addon']}
			</td>
		</tr>
<tr>
			<td class="row2">
			New Plugin Name:
			</td>
			<td class="row2">
<input type="text" name="plugin_name" id="plugin_name" value="" size="30" dir="ltr">
			</td>
		</tr>

<tr>
			<td class="row2">
			Plugin Name:
			</td>
			<td class="row2">
<select name="addons" id="sel_addons" dir="ltr">
<option value="">{$lang['no_place']}</option>
{Des::while}{AddonsList}
<option value="{$AddonsList['id']}">{$AddonsList['title']}</option>
{/Des::while}
</select>
			</td>
		</tr>
<tr>
			<td class="row2">
			Hook Name:
			</td>
			<td class="row2">
<input type="text" name="place_of_hook" id="sel_place_of_hook" value="" size="30" dir="ltr">

			</td>
		</tr>

		<tr width="100%">
			<td class="row1" align="center" width="100%" colspan="2">
			{$lang['PHP_Code_as_well_as_private']}
			<br />
<font color="#FF0000">{$lang['RewriteEnginetextarea']}</font>
<br />
				<textarea name="phpcode" id="textarea_phpcode" style="width: 80%;max-width: 80%;" rows="30" wrap="virtual" dir="ltr"></textarea>
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