
<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
 <a href="index.php?page=template&amp;control=1&amp;main=1">{$lang['templates']}</a> &raquo;
 <a href="index.php?page=template&amp;control=1&amp;show=1&id={$StyleInfo['id']}">
  {$lang['Templates']} :
  {$StyleInfo['style_title']}</a></div>

<br />
<?php

$singleoriginalfile ="../cache/singles_templates_original_default_style.xml";

if (file_exists($singleoriginalfile))
{
$xml_code = @file_get_contents($singleoriginalfile);
}

		$xml_code = str_replace('decode="0"','decode="1"',$xml_code);
		preg_match_all('/<!\[CDATA\[(.*?)\]\]>/is', $xml_code, $match);
		foreach($match[0] as $val)
		{
		$xml_code = str_replace($val,base64_encode($val),$xml_code);
		}


$import = $PowerBB->functions->xml_array($xml_code);
$SingleTemplates = $import['templategroup'];
?>

<table class="t_style_b" width="95%" align="center">
<tr>
	<td class="row1">
		<b>بحث عن قالب:</b>
		<input type="text" id="templateSearch" onkeyup="searchTemplates()"
		       placeholder="اكتب اسم القالب..." style="width:250px;">
	</td>
</tr>
</table>
<br />


<table border="0" class="t_style_b" cellpadding="0" cellspacing="0" align="center" width="95%" dir="ltr">
<tr valign="top">
	<td class="main1" colspan="2">
Common Templates/القوالب الشائعة
	</td>
</tr>
{Des::while}{CommonTemplates}
<form action="index.php?page=template&amp;common_templates=1&amp;start=1&amp;templateid={$CommonTemplates['templateid']}&amp;styleid={$CommonTemplates['styleid']}" method="post">
<tr valign="top">
	<td class="row1" valign="top">

<?php

$PowerBB->_CONF['template']['while']['CommonTemplates'][$this->x_loop]['template']  = str_replace("'", "&#39;", $PowerBB->_CONF['template']['while']['CommonTemplates'][$this->x_loop]['template']);

?>
<textarea rows="10" cols="50" wrap="virtual" tabindex="1" name="context" dir="ltr">{$CommonTemplates['template']}</textarea>&nbsp;
	</td>
	<td class="row1" valign="top">
<b>{$CommonTemplates['title']}</b></td>
</tr>
<tr valign="top">
	<td class="row1" colspan="2" align="center">
		<input type="submit" value=" {$lang['Save']} " name="submit" /></td>
</tr>
</form>
{/Des::while}
</table>
<br />
<table border="0" class="t_style_b" cellpadding="0" cellspacing="0" align="center" width="50%">
<tr valign="top">
<td class="row1">
{$lang['template_has_been_original']}
<br />
<font color="#DF0701">{$lang['template_has_been_modified']}</font>
</td>
 </tr>
</table>
<br />
<table width="95%" class="t_style_b" border="1" cellspacing="8" align="center" dir="ltr">
<tr valign="top">
	<td class="row2" colspan="4">
<script type="text/javascript" language="JavaScript" src="../includes/js/find.js">
</script>
</td>
</tr>
<tr valign="top" align="center">
	<td class="main1">{$lang['Template_name']}</td>
	<td class="main1">{$lang['edit']}</td>
	<td class="main1">{$lang['Delet']}</td>
	<td class="main1">{$lang['ViewOrginaltemplate']}</td>
</tr>
{Des::while}{TemplatList}
<tr valign="top" class="template-row">
	<td class="row1 template-title">
<?php
$templatetitle =$PowerBB->_CONF['template']['while']['TemplatList'][$this->x_loop]['title'];
$template_un = $SingleTemplates[$templatetitle];
$template_un = @base64_decode($template_un);
$template_un = str_replace("//<![CDATA[", "", $template_un);
$template_un = str_replace("//]]>", "", $template_un);
$template_un = str_replace("<![CDATA[","", $template_un);
$template_un = str_replace("]]>","", $template_un);
$template_un = str_replace("'", "&#39;", $template_un);
$template =  $PowerBB->_CONF['template']['while']['TemplatList'][$this->x_loop]['template'];


?>
	<a href="index.php?page=template&amp;edit=1&amp;main=1&amp;templateid={$TemplatList['templateid']}&amp;styleid={$StyleInfo['id']}"
	 title="{$lang['edit']}">
	 <?php

 if($template_un == $template)
 {
?>
{$TemplatList['title']}
<?php
}else{
?>
<b><font color="#FF0000">{$TemplatList['title']}</font></b>
<?php
}
?>
</a>
	</td>
	<td class="row1" align="center"><a href="index.php?page=template&amp;edit=1&amp;main=1&amp;templateid={$TemplatList['templateid']}&amp;styleid={$StyleInfo['id']}">
	{$lang['edit']}</a></td>
	<td class="row1" align="center">
<a href="index.php?page=template&del=1&main=1&templateid={$TemplatList['templateid']}&styleid={$TemplatList['styleid']}" onclick="return confirm('{$lang['confirm']}')">
{$lang['Delet']}</a>
</td>
	<td class="row1" align="center">
	       <a onclick="window.open('index.php?page=template&amp;view=1&amp;main=1&amp;templateid={$TemplatList['templateid']}&amp;styleid={$StyleInfo['id']}','mywindow','location=1,status=1,scrollbars=1,width=700,height=550')">
	       <u><font color="#000080" style="cursor: pointer;">{$lang['ViewOrginaltemplate']}</font></u></a>
	       </td>
</tr>
{/Des::while}
<script type="text/javascript">
function searchTemplates()
{
	var input  = document.getElementById("templateSearch").value.toLowerCase();
	var rows   = document.getElementsByClassName("template-row");

	for (var i = 0; i < rows.length; i++)
	{
		var titleCell = rows[i].getElementsByClassName("template-title")[0];
		if (!titleCell) continue;

		var text = titleCell.textContent || titleCell.innerText;

		if (text.toLowerCase().indexOf(input) > -1)
		{
			rows[i].style.display = "";
		}
		else
		{
			rows[i].style.display = "none";
		}
	}
}
</script>

</table>
