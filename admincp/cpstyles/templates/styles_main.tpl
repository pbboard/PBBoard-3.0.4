<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=style&amp;control=1&amp;main=1">{$lang['mange_styles']}</a></div>
<br />
<form action="index.php?page=style&amp;style_order=1&amp;start=1&amp;id={$StlList['id']}""  name="myform" method="post">
<table cellpadding="3" cellspacing="1" width="98%" class="t_style_b" border="0" cellspacing="1" align="center">
<tr valign="top" align="center">
    <td class="main1" colspan="2">{$lang['mange_styles']}</td>
</tr>
{Des::while}{StlList}
<tr valign="top">
   <td class="row1">
   <input type="text" class="bginput" name="order-{$StlList['id']}" value="{$StlList['style_order']}" tabindex="1" size="2" title="{$lang['order']}" />
{if {$StlList['style_on']}}
{$StlList['style_title']}
{else}
<s>{$StlList['style_title']}</s>
{/if}
</td>
<td class="row1">
<form name="mange_styles" id="mange_styles_frm" action="../">
                <select name="myDestination" class="bginput">
				<optgroup label="{$lang['edittemplates']}">
					<option value="index.php?page=template&amp;control=1&amp;show=1&amp;id={$StlList['id']}">{$lang['edit_templates']}</option>
					<option value="index.php?page=template&amp;add=1&amp;show=1&amp;id={$StlList['id']}&amp;main=1">{$lang['add_new_template']}</option>
					{if {$StlList['cache_path']}}
					<option> {$lang['upgraded_style_dun']}
					<?php $PowerBB->_CONF['template']['while']['StlList'][$this->x_loop]['cache_path'] = $PowerBB->functions->_date($PowerBB->_CONF['template']['while']['StlList'][$this->x_loop]['cache_path']); ?>
					{$StlList['cache_path']}
					</option>
					{else}
					<option value="index.php?page=template&amp;upgrade_style=1&amp;start=1&amp;id={$StlList['id']}" class="col-c"> {$lang['upgrade_style']}</option>
					{/if}
					<option value="index.php?page=template&amp;revertall=1&amp;start=1&amp;id={$StlList['id']}">{$lang['template_revertall']}</option>
				</optgroup>
				<optgroup label="{$lang['editclorsfonts']}">
					<option value="index.php?page=style&amp;edit_css=1&amp;main=1&amp;id={$StlList['id']}">{$lang['edit_css']}</option>
				</optgroup>
				<optgroup label="{$lang['editmangestyles']}">
					<option value="index.php?page=style&amp;edit=1&amp;main=1&amp;id={$StlList['id']}" selected="selected">{$lang['editstyle']}</option>
					<option value="index.php?page=style&amp;download=1&amp;main=1&amp;id={$StlList['id']}">{$lang['Download']}</option>
					<option value="index.php?page=style&amp;del=1&amp;main=1&amp;id={$StlList['id']}" class="col-c">{$lang['deletestyle']}</option>
				</optgroup>
			</select>
			<input type="button" class="submit" value="{$lang['Go']}" onclick="ob=this.form.myDestination;window.open(ob.options[ob.selectedIndex].value,'_self')" >
			</form>

			</td>
</tr>
{/Des::while}
</tr>
<tr valign="top">
		<td class="main2" colspan="2" align="center">
		<input class="submit" name="style_order" type="submit" value="{$lang['save_display_order']}" accesskey="s" />&nbsp;
		&nbsp;<input class="submit" type="submit" name="template_search" value="{$lang['search_templates']}" accesskey="s" />
</td>
</tr>
</table>
</form>
<br />

<form action="index.php?page=style&amp;default=1" name="myform" method="post">
<table cellpadding="3" cellspacing="1" width="98%" class="t_style_b" border="0" align="center">
<tr valign="top" align="center">
	<td class="main1" colspan="2">{$lang['Default_style']}</td>
</tr>
<tr valign="top">
		<td class="row1">
	{$lang['Choose_Default_Style']}
		</td>
		<td class="row1">
<select name="default" id="select_default">
{Des::while}{StlDef}
		{if {$StlDef['id']} == {$_CONF['info_row']['def_style']}}
		<option value="{$StlDef['id']}" selected="selected">{$StlDef['style_title']}</option>
		{else}
		<option value="{$StlDef['id']}">{$StlDef['style_title']}</option>
		{/if}
	{/Des::while}
</select>
</td>
</tr>
<tr valign="top">
		<td class="main2" colspan="2" align="center"><input class="submit" type="submit" value="{$lang['acceptable']}" name="submit" accesskey="s" />
</td>
</tr>
</table>
</form>
<br />

<form action="index.php?page=style&amp;mobile=1" name="myform" method="post">
<table cellpadding="3" cellspacing="1" width="98%" class="t_style_b" border="0" align="center">
<tr valign="top" align="center">
	<td class="main1" colspan="2">{$lang['choose_mobile_Style']}</td>
</tr>
<tr valign="top">
		<td class="row1">
	{$lang['choose_mobile_Style_from_the_list']}
		</td>
		<td class="row1">
<select name="mobile_style" id="select_mobile_style">
{Des::while}{StlDef}
		{if {$StlDef['id']} == {$_CONF['info_row']['mobile_style_id']}}
		<option value="{$StlDef['id']}" selected="selected">{$StlDef['style_title']}</option>
		{else}
		<option value="{$StlDef['id']}">{$StlDef['style_title']}</option>
		{/if}
	{/Des::while}
</select>
</td>
</tr>
<tr valign="top">
<td class="main2" colspan="2" align="center"><input class="submit" type="submit" value="{$lang['acceptable']}" name="submit" accesskey="s" />
</td>
</tr>
</table><br />

</form>