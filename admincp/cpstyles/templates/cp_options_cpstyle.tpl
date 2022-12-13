<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=options&amp;index=1">{$lang['mange_forum']}</a> &raquo;
<a href="index.php?page=cp_options&amp;&cpstyle=1&amp;main=1">{$lang['cpstyle_folder']}</a></div>

<br />

<form action="index.php?page=cp_options&amp;cpstyle=1&amp;update=1" method="post">
<table width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
<tr valign="top">
	<td class="main1" colspan="2">
	{$lang['cpstyle_folder']}
	</td>
</tr>
<tr valign="top">
	<td class="row1">
<?php
 $PowerBB->_CONF['template']['_CONF']['lang']['Choices_cpstyle_folder'] = str_replace('look/styles/admin/main/cpstyles', '<u>admincp/cpstyles</u>', $PowerBB->_CONF['template']['_CONF']['lang']['Choices_cpstyle_folder']);
?>
   {$lang['Choices_cpstyle_folder']}
</td>
	<td class="row1">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr valign="top">
	<td>
	<div id="ctrl_setting[cpstylefolder]">
    <select name="cssprefs" id="select_foldr" size="5" multiple>
    {Des::foreach}{StyleCpList}{foldrs}
    {if {$foldrs['filename']} != 'templates'}
		{if {$_CONF['info_row']['cssprefs']}== {$foldrs['filename']}}
	<option value="{$foldrs['filename']}" selected="selected">{$foldrs['filename']}</option>
		{else}
	<option value="{$foldrs['filename']}">{$foldrs['filename']}</option>
		{/if}
	{/if}
	{/Des::foreach}
</select>
</div>
</td>
</tr>
	</table>
</td>
</tr>
	</table>
<table cellpadding="3" cellspacing="1" width="90%" class="t_style_b" border="0" align="center">
	<tr valign="top">
		<td class="row2" colspan="2" align="center">
		<input class="submit" type="submit" value="   {$lang['Save']}  " name="mods_form_submit" accesskey="s" />
		</td>
	</tr>
</table>
<br />
</form>
