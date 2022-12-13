
<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=sections&amp;control=1&amp;main=1">{$lang['Sections_Mains']}</a></div>

<br />

<div id="status" align="center"></div>

<br />

<form action="index.php?page=sections&amp;change_sort=1&amp;start=1"  name="myform" method="post">
<table cellpadding="3" cellspacing="1" width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
<tr valign="top" align="center">
	<td class="main1">{$lang['Section_title']}</td>
	<td class="main1">{$lang['Powers']}</td>
	<td class="main1">{$lang['edit']}</td>
	<td class="main1">{$lang['Delet']}</td>
	<td class="main1">{$lang['Section_ordr']}</td>
</tr>
{Des::while}{SecList}
{if {$SecList['parent']} == 0}
<tr valign="top" align="center">
	<td class="row1">{$SecList['title']}</td>
	<td class="row1"><a href="index.php?page=sections&amp;groups=1&amp;control_group=1&amp;index=1&amp;id={$SecList['id']}">{$lang['Powers']}</a></td>
	<td class="row1"><a href="index.php?page=sections&amp;edit=1&amp;main=1&amp;id={$SecList['id']}">{$lang['edit']}</a></td>
	<td class="row1"><a href="index.php?page=sections&amp;del=1&amp;main=1&amp;id={$SecList['id']}">{$lang['Delet']}</a></td>
	<td class="row1"><input type="text" name="order-{$SecList['id']}" id="input_order-{$SecList['id']}" value="{$SecList['sort']}" size="5" /></td>
</tr>
{/if}
{/Des::while}
</table>

<br />
<div align="center">
<input class="submit" type="submit" value="{$lang['acceptable']}" name="submit" accesskey="s" />
</div>
</form>
