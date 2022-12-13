		     <fieldset>
<legend>{$lang['icons']}</legend>
	<?php $t=0;	?>
	<table width="80%" border="0" cellpadding="0" style="border-collapse: collapse" align="center">
	<tr valign="top">
	{Des::while}{IconRows}
<?php
if($t== $PowerBB->_CONF['template']['_CONF']['info_row']['icon_columns_number']){
$t=0;
echo "</tr><tr>";
}?>
		<td width="4%" class="smbox">
		 <?php if ($PowerBB->_CONF['template']['SRInfo']['icon'] == $PowerBB->_CONF['template']['while']['IconRows'][$this->x_loop]['smile_path']) {  ?>
		      <input type="radio" value="../{$IconRows['smile_path']}" checked="checked" name="icon" id="fp{$IconRows['id']}" />
		{else}
		      <input type="radio" value="../{$IconRows['smile_path']}" name="icon" id="fp{$IconRows['id']}" />
		{/if}
				<label for="fp{$IconRows['id']}">
					<img src="../{$IconRows['smile_path']}" alt="../{$IconRows['smile_path']}" border="0" cellspacing="1" />
				</label>
</td>
<?php $t=$t+1;?>
{/Des::while}
</tr>
	<tr valign="top">
		<td width="4%" class="smbox" colspan="{$_CONF['info_row']['icon_columns_number']}">
		{if {$SRInfo['icon']} == ''}
		<input type="radio" value="{$_CONF['info_row']['icon_path']}i1.gif" checked="checked" name="icon" id="fp1" />
		{else}
		<input type="radio" value="{$_CONF['info_row']['icon_path']}i1.gif" name="icon" id="fp1" />
		{/if}
		<label for="fp1">
		{$lang['non_icon']}</label>
		</td>
		</tr>
</table>
</fieldset>
