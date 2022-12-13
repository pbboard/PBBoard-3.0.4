{template}pbboard_code_js{/template}
<fieldset>
<legend>{$lang['smiles']}</legend>
    <div style="display:none;position: absolute; z-index: 1" id="All_Smiles">
    	<?php $t=0;	?>
<table border="0" cellspacing="1" class="t_style_b">
	<tr>
		<td class="row1" colspan="{$_CONF['info_row']['smil_columns_number']}">
		<a href="javascript:switchMenuNone('All_Smiles')">
		<div class="r-right"> <img border="0" cellspacing="1"
src="../{$admincpdir}/cpstyles/{$_CONF['info_row']['cssprefs']}/colorpicker_close.png" /></div></a>
		</td>
	</tr>
	<tr valign="top">
			{Des::while}{SmlList}
<?php
if($t== $PowerBB->_CONF['template']['_CONF']['info_row']['smil_columns_number']){
$t=0;
echo "</tr><tr>";
}?>
					<td class="row1">
				<img src="../{$SmlList['smile_path']}" OnClick="AddSmileyIcon('../{$SmlList['smile_path']}');" alt="../{$SmlList['smile_path']}" border="0" cellspacing="1" />
</td>
<?php $t=$t+1;?>
{/Des::while}
</tr>
</table>
</div>
	<?php $t=0;	?>
	<table width="100%" border="0" cellpadding="0" cellspacing="1">
	<tr valign="top">
				{Des::while}{SmileRows}
<?php
if($t== $PowerBB->_CONF['template']['_CONF']['info_row']['smil_columns_number']){
$t=0;
echo "</tr><tr>";
}?>
<td width="10%">
<img src="../{$SmileRows['smile_path']}" OnClick="AddSmileyIcon('../{$SmileRows['smile_path']}');" alt="../{$SmileRows['smile_path']}" border="0" cellspacing="1" />
</td>
<?php $t=$t+1;?>
{/Des::while}
</tr>
</table>
<div align="center">
 	<a href="javascript:switchMenuNone('All_Smiles')">
 	<strong><u>{$lang['All_Smiles']}</u></strong>
     <img border="0" cellspacing="1"
src="../{$admincpdir}/cpstyles/{$_CONF['info_row']['cssprefs']}/menu_open.gif" /></a>
  </div>
</fieldset>