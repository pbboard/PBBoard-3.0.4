<br />
<div class="address_bar">{$lang['Control_Panel']} &raquo;
{$lang['langs']} &raquo;
<a href="index.php?page=lang&amp;control_fieldname=1&amp;main=1">{$lang['phrase_manager']}</a>
</div>

<br />

<table cellpadding="1" cellspacing="0" border="1" align="center" width="90%">
	<tr>
		<td class="row1">
<form name="mange_fields" id="mange_fields_frm" action="../">
	<select name="fieldname" class="bginput">
{Des::while}{LangList}
		{if {$LangList['id']} == {$lang_id}}
<option value="index.php?page=lang&amp;control_fieldname=1&amp;main=1&amp;languageid={$LangList['id']}&amp;count={$perpage}&amp;count={$count}" selected="selected">{$LangList['lang_title']}</option>
		{else}
<option value="index.php?page=lang&amp;control_fieldname=1&amp;main=1&amp;languageid={$LangList['id']}&amp;perpage={$perpage}&amp;count={$count}">{$LangList['lang_title']}</option>
		{/if}
{/Des::while}
</select>
<input type="button" class="submit" value="{$lang['Go']}" onclick="ob=this.form.fieldname;window.open(ob.options[ob.selectedIndex].value,'_self')" >
  </form>
</td>
         <td class="row1">{$pager}</td>
		<form id="mange_fields_frm" action="index.php?page=lang" method="get">
		<input type="hidden" name="page" value="lang" />
		<input type="hidden" name="control_fieldname" value="1"/>
		<input type="hidden" name="main" value="1" />
		<input type="hidden" name="languageid" value="{$LangList['id']}" />
		<input type="hidden" name="perpage" value="{$perpage}" />
		<input type="hidden" name="count" value="{$count}" />
		<td class="row1"><input type="text" class="bginput" name="perpage" value="{$perpage}" tabindex="1" size="5" /></td>
		<td class="row1"><input type="submit" class="button" value=" {$lang['Go']} " tabindex="1" accesskey="s" /></td>
		</form>
	</tr></table>

<table cellpadding="4" cellspacing="1" border="0" align="center" width="80%">
<tr>
	<td class="main1" align="center" colspan="5">
		<b>{$lang['phrase_manager']}</b>
	</td>
</tr>
<tr valign="top" align="center">
	<td class="main3">{$lang['phrase_type']}</td>
	<td class="main3">{$lang_title}</td>
	<td class="main3" align="left">{$lang['edit']}</td>
	<td class="main3" align="right">{$lang['phrase_name']}</td>

</tr>
{Des::while}{FieldList}
<?php
$PowerBB->_CONF['template']['while']['FieldList'][$this->x_loop]['text'] = htmlspecialchars($PowerBB->_CONF['template']['while']['FieldList'][$this->x_loop]['text']);
?>
<tr valign="top" align="center">
<td class="row1" align="center">{$FieldList['fieldname']}</td>
	<td class="row1"><img src="../{$admincpdir}
	/cpstyles/{$_CONF['info_row']['cssprefs']}/cp_tick_yes.gif" alt="" /></td>
	<td class="row1" align="center"><span class="smallfont">
	<a href="index.php?page=lang&amp;control_fieldname=1&amp;edit_fieldname=1&amp;phraseid={$FieldList['phraseid']}">[{$lang['edit']}&lrm;]</a>&rlm; </span></td>
	<td class="row1" align="left" title="{$FieldList['text']}">
	<span class="smallfont" style="word-spacing:-5px; font-weight:bold;">{$FieldList['varname']}</span>
	</td>
</tr>
{/Des::while}
 </tr>
</table>
<div class="pbb-menu" style="width:160px;">
<ul style="display: block;"><li><span class="headerbar">
<a href="index.php?page=lang&amp;search_fieldname=1&amp;main=1">{$lang['search_in_phrases']}</a>
</span></li>
<li><span class="headerbar">
<a href="index.php?page=lang&amp;add_fieldname=1&amp;main=1">{$lang['add_new_phrase']}</a>
</span></li></ul>
 </div>
<br />
	<br />

