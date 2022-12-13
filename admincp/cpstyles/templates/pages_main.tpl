<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
 <a href="index.php?page=pages&amp;control=1&amp;main=1">{$lang['Pages']}</a>
</div>

<br />

<table cellpadding="3" cellspacing="1" width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
<tr valign="top" align="center">
	<td class="main1" colspan="4">{$lang['Pages']}</td>
</tr>
<tr valign="top" align="center">
	<td class="main2">{$lang['Page_title']}</td>
	<td class="main2"> {$lang['View']}</td>
	<td class="main2">{$lang['edit']}</td>
	<td class="main2">{$lang['Delet']}</td>
</tr>
{Des::while}{PagesList}
{if {$PagesList['id']}}
<tr valign="top" align="center">
{if {$PagesList['link']} !=''}
	<td class="row1"><a target="_blank" href="{$PagesList['link']}">{$PagesList['title']}</a></td>
	<td class="row1"><a target="_blank" href="{$PagesList['link']}">{$lang['View']}</a></td>

{else}
	<td class="row1"><a target="_blank" href="../index.php?page=pages&amp;show=1&amp;id={$PagesList['id']}">{$PagesList['title']}</a></td>
	<td class="row1"><a target="_blank" href="../index.php?page=pages&amp;show=1&amp;id={$PagesList['id']}">{$lang['View']}</a></td>
{/if}
	<td class="row1"><a href="index.php?page=pages&amp;edit=1&amp;main=1&amp;id={$PagesList['id']}">{$lang['edit']}</a></td>
	<td class="row1"><a href="index.php?page=pages&amp;del=1&amp;main=1&amp;id={$PagesList['id']}">{$lang['Delet']}</a></td>
</tr>
{else}
<tr valign="top" align="center">
<td class="row1" colspan="4">{$lang['No_Page']}</td>
</tr>
{/if}
{/Des::while}
</table>
<br />

<table cellpadding="3" cellspacing="1" width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
<tr valign="top" align="center">
	<td class="main1" colspan="3">{$lang['External_Links_Pages']}</td>
</tr>
<tr valign="top" align="center">
    <td class="main2">{$lang['order']}</td>
	<td class="main2">{$lang['Page_title']}</td>
	<td class="main2">{$lang['Page_Link']}</td>
</tr>
{Des::while}{PagesList}
<tr valign="top" align="center">
<td class="row1">{$PagesList['sort']}</td>
  {if {$PagesList['link']} !=''}
	<td class="row1"><a target="_blank" href="{$PagesList['link']}">{$PagesList['title']}</a></td>
	<td class="row1"><input type="text" value="{$PagesList['link']}" dir="ltr" size="81" /></td>
{else}
  	<td class="row1"><a target="_blank" href="../index.php?page=pages&amp;show=1&amp;id={$PagesList['id']}">{$PagesList['title']}</a></td>
	<td class="row1"><input type="text" value="{$forum_adress}index.php?page=pages&amp;show=1&amp;id={$PagesList['id']}" dir="ltr" size="81" /></td>
	{/if}

</tr>
{if !{$PagesList['id']}}
<tr valign="top" align="center">
<td class="row1" colspan="3">{$lang['No_links_to_external_pages']}</td>
</tr>
{/if}
{/Des::while}
</table>