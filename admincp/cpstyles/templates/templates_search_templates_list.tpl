<br />
<div class="address_bar">{$lang['Control_Panel']} &raquo;
 <a href="index.php?page=template&amp;search=1&amp;main=1">{$lang['search_templates']}</a>
</div>

<br />

<table width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
<tr valign="top" align="center">
<td class="main1">{$lang['style_name']}</td>
	<td class="main1">{$lang['Template_name']}</td>
	<td class="main1">{$lang['edit']}</td>
	<td class="main1">{$lang['Delet']}</td>
	<td class="main1">{$lang['ViewOrginaltemplate']}</td>
</tr>
{Des::while}{TemplateList}
<?php
			$StyleArr = 	$PowerBB->_CONF['template']['while']['TemplateList'][$this->x_loop]['styleid'];
            $style = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['style'] . " WHERE id = '$StyleArr' ");
			$getstyle_row = $PowerBB->DB->sql_fetch_array($style);
	?>
<tr valign="top">
<td class="row1"><?php echo $getstyle_row['style_title']?></td>
	<td class="row1">{$TemplateList['title']}</td>
	<td class="row1" align="center"><a href="index.php?page=template&amp;edit=1&amp;main=1&amp;templateid={$TemplateList['templateid']}&amp;styleid={$TemplateList['styleid']}">
	{$lang['edit']}</a></td>
	<td class="row1" align="center">
<a href="index.php?page=template&amp;del=1&amp;main=1&amp;templateid={$TemplateList['templateid']}&amp;styleid={$TemplateList['styleid']}">{$lang['Delet']}</a></td>
	<td class="row1" align="center">
	       <a onclick="window.open('index.php?page=template&amp;view=1&amp;main=1&amp;templateid={$TemplateList['templateid']}&amp;styleid={$TemplateList['styleid']}','mywindow','location=1,status=1,scrollbars=1,width=700,height=550')">
	       <u><font color="#000080" style="cursor: pointer;">{$lang['ViewOrginaltemplate']}</font></u></a>
	       </td>
</tr>
{/Des::while}
{if {$found}}
<tr valign="top" align="center">
<td class="row1" colspan="5">
{$lang['No_search_results']}
</td>
</tr>
{/if}
</table>
<br />
