<br />
<div class="address_bar">{$lang['Control_Panel']} &raquo;
 <a href="index.php?page=lang&amp;search=1&amp;main=1">{$lang['search_in_phrases']}</a>
</div>

<br />

<table width="98%" class="t_style_b" border="0" cellspacing="1" align="center">
<tr valign="top" align="center">
<td class="main1">{$lang['language_name']}</td>
	<td class="main1">{$lang['phrase_text']}</td>
	<td class="main1">{$lang['phrase_name']}</td>
	<td class="main1">{$lang['phrase_type']}</td>
	<td class="main1">{$lang['edit']}</td>
</tr>
{Des::while}{LanguageList}
<?php
			$LangArr = $PowerBB->_CONF['template']['while']['LanguageList'][$this->x_loop]['languageid'];
            $Lang = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['lang'] . " WHERE id = '$LangArr' ");
			$getLang_row = $PowerBB->DB->sql_fetch_array($Lang);
        $PowerBB->_CONF['template']['while']['LanguageList'][$this->x_loop]['text'] = str_replace("&#39;", "'", $PowerBB->_CONF['template']['while']['LanguageList'][$this->x_loop]['text']);
			$PowerBB->_CONF['template']['while']['LanguageList'][$this->x_loop]['text'] = $PowerBB->functions->CleanVariable($PowerBB->_CONF['template']['while']['LanguageList'][$this->x_loop]['text'],'html');

	?>
<tr valign="top">
<td class="row1"><?php echo $getLang_row['lang_title']?></td>
	<td class="row1">{$LanguageList['text']}</td>
	<td class="row1">{$LanguageList['varname']}</td>
<td class="row1" align="center">{$LanguageList['fieldname']}</td>

	<td class="row1" align="center"> <a href="index.php?page=lang&amp;control_fieldname=1&amp;edit_fieldname=1&amp;phraseid={$LanguageList['phraseid']}">[{$lang['edit']}&lrm;]</a>&rlm; </span></td>
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
