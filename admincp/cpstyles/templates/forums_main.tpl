<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=forums&amp;control=1&amp;main=1">{$lang['Forums']}</a></div>

<br />

<form action="index.php?page=forums&amp;change_sort=1&amp;start=1"  name="myform" method="post">
<table cellpadding="3" cellspacing="1" width="90%" class="t_style_b" border="0" cellspacing="1" align="center">

<tr valign="top" align="center">
	<td class="main1">{$lang['Forum_title']}</td>
	<td class="main1">{$lang['Powers']}</td>
	<td class="main1">{$lang['edit']}</td>
	<td class="main1">{$lang['Delet']}</td>
	<td class="main1">{$lang['Forum_Order']}</td>
</tr>
{Des::foreach}{forums_list}{forum}
<tr valign="top" align="center">
    {if {$forum['parent']} == 0}
	<td class="row3" colspan="5">{$forum['title']}</td>
	{elseif {$forum['parent']} > 0}
	<td class="row1">
	<a href="index.php?page=forums&amp;forum=1&amp;index=1&amp;id={$forum['id']}">{$forum['title']} </a>
	{if {$forum['is_sub']}}
	<br />
{$forum['sub']}
     {/if}
	</td>
 {/if}
    {if {$forum['parent']}> 0}
	<td class="row1"><a href="index.php?page=forums&amp;groups=1&amp;control_group=1&amp;index=1&amp;id={$forum['id']}">{$lang['Powers']}</a></td>
	<td class="row1"><a href="index.php?page=forums&amp;edit=1&amp;main=1&amp;id={$forum['id']}">{$lang['edit']}</a></td>
	<td class="row1"><a href="index.php?page=forums&amp;del=1&amp;main=1&amp;id={$forum['id']}">{$lang['Delet']}</a></td>
	<td class="row1"><input type="text" name="order-{$forum['id']}" id="input_order-{$forum['id']}" value="{$forum['sort']}" size="5" /></td>
    {/if}
</tr>
{/Des::foreach}
</table><br />
<tr>
	<td class="submit-buttons" colspan="2" align="center">
	<input class="submit" type="submit" value="{$lang['acceptable']}" name="submit" accesskey="s" /></td>
</tr>
</table><br />
</form>
