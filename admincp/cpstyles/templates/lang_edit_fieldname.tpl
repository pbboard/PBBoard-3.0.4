<br />

<div class="address_bar">{$lang['Control_Panel']}
 &raquo;
{$lang['langs']} &raquo;
 <a href="index.php?page=lang&amp;control_fieldname=1&amp;edit_fieldname=1&amp;phraseid={$InfoField['phraseid']}">
   &raquo;{$lang['edit']} :
 {$InfoField['varname']}
 </a>

 </div>

<br />

<form action="index.php?page=lang&amp;control_fieldname=1&amp;start_edit=1&amp;phraseid={$InfoField['phraseid']}" method="post">
<table width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
<tr>
	<td class="main1" align="center" colspan="2">
		<b>{$lang['edit']} :
 {$InfoField['varname']}</b>
	</td>
</tr>
<tr valign="top">
	<td class="row1"><span>{$lang['phrase_type']}</span></td>
	<td class="row1"><div id="ctrl_product"><select name="fieldname" id="sel_product_1" tabindex="1" class="bginput">
		{if {$InfoField['fieldname']} == 'admincp'}
<option value="admincp" selected="selected">Admincp </option>
<option value="forum">Forum</option>
		{else}
<option value="forum" selected="selected">Forum</option>
<option value="admincp">Admincp </option>
		{/if}

</select>
</div>
</td>
</tr>
<tr valign="top">
	<td class="row2">{$lang['phrase_name']}</td>
	<td class="row2">
	<input type="text" class="bginput" name="varname" id="it_varname_3" size="40" dir="ltr" value="{$InfoField['varname']}" />
</td>

</tr>
<tr>
	<td class="main2" align="center" colspan="2">
		<b>{$lang['phrase_text']}</b>
	</td>
</tr>
<?php
		$languageid= $PowerBB->_CONF['template']['InfoField']['languageid'];
	    $PowerBB->_CONF['template']['InfoField']['text'] = str_replace("&#39;", "'", $PowerBB->_CONF['template']['InfoField']['text']);
		$Getlanguagetitle = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['lang'] . " WHERE id='$languageid'");
		$getlanguage_row = $PowerBB->DB->sql_fetch_array($Getlanguagetitle);
		$languagetitle = $getlanguage_row['lang_title'];
		?>
<input type="hidden" name="phraseid" value="{$VarnameList['phraseid']}"/>
<tr valign="top">
	<td class="row1">{$lang['translate']} <b><?php echo $languagetitle ?></b> <br />&nbsp;<div class="smallfont">&nbsp;</div></td>
	<td class="row1"><textarea name="text" id="text_id" rows="5" cols="50" tabindex="1" wrap="virtual" dir="rtl">{$InfoField['text']}</textarea></td>
</tr>
<tr>
	<td class="tfoot" colspan="2" align="center">	<input type="submit" id="submit0" class="button" tabindex="1" value="  {$lang['Save']}   " accesskey="s" />
</td>
</tr>
</table>

	<br />



	<br />

</form>