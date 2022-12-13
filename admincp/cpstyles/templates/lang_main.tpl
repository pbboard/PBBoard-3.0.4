<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=lang&amp;control=1&amp;main=1">{$lang['langs']}</a> &raquo;
<a href="index.php?page=lang&amp;control=1&amp;main=1">{$lang['mange_langs']}</a></div>


<br />

<table cellpadding="3" cellspacing="1" width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
<tr valign="top" align="center">
    <td class="main1">{$lang['order']}</td>
	<td class="main1">{$lang['language_name']}</td>
	<td class="main1">{$lang['edit']}</td>
	<td class="main1">{$lang['Delet']}</td>
	<td class="main1">{$lang['Download']}</td>
	<td class="main1">{$lang['Case']}</td>
	<td class="main1">{$lang['content_dir']}</td>

</tr>
{Des::while}{LangList}
<tr valign="top" align="center">
   <td class="row1">{$LangList['lang_order']}</td>
	<td class="row1">
{if {$LangList['lang_on']}}
{$LangList['lang_title']}
{else}
<s>{$LangList['lang_title']}</s>
{/if}
	</td>
	<td class="row1"><a href="index.php?page=lang&amp;edit=1&amp;main=1&amp;id={$LangList['id']}">
	{$lang['edit']}</a></td>
	<td class="row1"><a href="index.php?page=lang&amp;del=1&amp;main=1&amp;id={$LangList['id']}">
	{$lang['Delet']}</a></td>
	<td class="row1"><a href="index.php?page=lang&amp;create_phrase_language=1&amp;download_language=1&amp;id={$LangList['id']}">
	{$lang['Download']}</a></td>
		<td class="row1">{if {$LangList['lang_on']}}
	<b>{$lang['activating']}<b>
	{else}
	<b>{$lang['Disabled']}<b>
	{/if}
	</td>
	<td class="row1">
	{if {$LangList['lang_path']} == 'rtl'}
	<b>RTL</b>
	{elseif {$LangList['lang_path']} == 'ltr'}
	<b>LTR</b>
	{elseif {$Inf['lang_path']} != ''}
	<b>
	{$lang['default_design_block_latest_news']}
	</b>
{/if}
</td>
</tr>
{/Des::while}
</table>
<br />

<form action="index.php?page=lang&amp;default=1" name="myform" method="post">
<table cellpadding="3" cellspacing="1" width="90%" class="t_style_b" border="0" align="center">
<tr valign="top" align="center">
	<td class="main1" colspan="2">{$lang['Default_Language']}</td>
</tr>
<tr valign="top">
		<td class="row1"> {$lang['The_default_language_of_the_Forum']}</td>
		<td class="row1">
<select name="default" id="select_default">
{Des::while}{LangDef}
		{if {$LangDef['id']} == {$_CONF['info_row']['def_lang']}}
		<option value="{$LangDef['id']}" selected="selected">{$LangDef['lang_title']}</option>
		{else}
		<option value="{$LangDef['id']}">{$LangDef['lang_title']}</option>
		{/if}
	{/Des::while}
</select>
</td>
</tr>
<tr valign="top">
		<td class="row1" colspan="2" align="center"><input class="submit" type="submit" value="{$lang['acceptable']}" name="submit" accesskey="s" />
</td>
</tr>
</table><br />
</form>