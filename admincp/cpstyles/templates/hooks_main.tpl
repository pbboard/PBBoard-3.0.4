<br />

<div class="address_bar">
{$lang['Control_Panel']}
&raquo; {$lang['addons_pbb']}
&raquo; <a href="index.php?page=addons&amp;control=1&amp;main=1">{$lang['control_hooks']}</a>
</div>
<br />
<font color="#FF0000">{$lang['RewriteEnginetextarea']}</font>
<br />
<table width="98%" class="t_style_b" border="0" cellspacing="1" align="center">
	<tr>
		<td class="main1" align="center" width="39%">
    {$lang['sel_addons']}
		</td>
		<td class="main1" align="center" width="30%">
		{$lang['Location']}
		</td>
		<td class="main1" align="center" width="10%">
		{$lang['edit']}
		</td>
	</tr>
	{Des::while}{HooksList}
	<tr>
		<td class="row1" width="39%">
<?php

      	$addon_id = $PowerBB->_CONF['template']['while']['HooksList'][$this->x_loop]['addon_id'];
        $GetAddon = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['addons'] . " WHERE id = '$addon_id'");
        $Addon = $PowerBB->DB->sql_fetch_array($GetAddon);
        $title = $Addon['title'];
?>
<?php echo $title; ?>
		</td>
		<td class="row1" width="30%">
        {$HooksList['place_of_hook']}
		</td>
		<td class="row1" align="center" width="10%">
		<a href="index.php?page=addons&amp;edit_hook=1&amp;main=1&amp;id={$HooksList['id']}"> {$lang['edit']}</a>
		</td>
	</tr>
	{/Des::while}
	<tr>
		<td class="main2" align="center" colspan="4">
<a href="index.php?page=addons&amp;writing_addon=1&amp;main=1">[ {$lang['writing_addon']} ]</a>
		</td>
	</tr>
</table>
