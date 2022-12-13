<br />

<div class="address_bar">
{$lang['Control_Panel']}
&raquo; {$lang['addons_pbb']}
&raquo; <a href="index.php?page=addons&amp;control=1&amp;main=1">{$lang['control_addons']}</a>
</div>

<br />

<table width="98%" class="t_style_b" border="0" cellspacing="1" align="center">
	<tr>
		<td class="main1" align="center">
    {$lang['title']}
		</td>
		<td class="main1" align="center">
		{$lang['version']}
		</td>
		<td class="main1" align="center">
		{$lang['addons_description']}
		</td>
		<td class="main1" align="center">
		{$lang['Control']}
		</td>
	</tr>
	{Des::while}{AddonsList}
	<tr>
		<td class="row1">
       	{if {$AddonsList['active']} == 1}
		 {$AddonsList['title']}
		{else}
		 <s>{$AddonsList['title']}</s>
		{/if}
		</td>
		<td class="row1">
      {$AddonsList['version']}
		</td>
		<td class="row1">
        {$AddonsList['description']}
		</td>
		<td class="row1" width="20%">
		{if {$AddonsList['active']} == 1}
		<a href="index.php?page=addons&amp;no_active=1&amp;start=1&amp;id={$AddonsList['id']}"> {$lang['Disable']}</a>|
        {else}
		<a href="index.php?page=addons&amp;active=1&amp;start=1&amp;id={$AddonsList['id']}">{$lang['active']}</a>|
        {/if}
		<a href="index.php?page=addons&amp;export=1&amp;start=1&amp;id={$AddonsList['id']}">{$lang['Export']}</a>|
		<a href="index.php?page=addons&amp;del=1&amp;start=1&amp;id={$AddonsList['id']}">{$lang['Delet']}</a>
		</td>

	</tr>
	{/Des::while}
	<tr>
		<td class="main2" align="center" colspan="4">
<a href="index.php?page=addons&amp;add=1&amp;main=1">[ {$lang['add_addons']} ]</a>
		</td>
	</tr>
</table>
