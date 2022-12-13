<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
 <a href="index.php?page=forums&amp;control=1&amp;main=1">{$lang['Forums']}</a> &raquo;
<a href="index.php?page=forums&amp;forum=1&amp;index=1&amp;id={$Inf['id']}">
  {$lang['Forums_subsidiary_of']}
  </a>
   <strong>{$Inf['title']}</strong></div>

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
			<td class="main2" colspan="4">{$forum['title']}</td>
		{else}
			<td class="row1">
{if {$forum['is_sub']}}
<a href="index.php?page=forums&amp;forum=1&amp;index=1&amp;id={$forum['id']}">{$forum['title']} </a>
<span>
<a title="{$lang['Forums_subsidiary_of']} {$forum['title']}" href="index.php?page=forums&amp;forum=1&amp;index=1&amp;id={$forum['id']}">
     <img border="0" alt="{$lang['Forums_subsidiary_of']} {$forum['title']}"
      src="{$admincpdir_cssprefs}/menu_open.gif" /></a>
     </span>
{else}
{$forum['title']}
{/if}
			</td>
			<td class="row1">
				<a href="index.php?page=forums&amp;groups=1&amp;control_group=1&amp;index=1&amp;id={$forum['id']}">{$lang['Powers']}</a>
			</td>
			<td class="row1">
				<a href="index.php?page=forums&amp;edit=1&amp;main=1&amp;id={$forum['id']}">{$lang['edit']}</a>
			</td>
			<td class="row1">
				<a href="index.php?page=forums&amp;del=1&amp;main=1&amp;id={$forum['id']}">{$lang['Delet']}</a>
			</td>
			<td class="row1">
				<input type="text" name="order-{$forum['id']}" id="input_order-{$forum['id']}" value="{$forum['sort']}" size="5" />
			</td>
		{/if}
		</tr>
		{/Des::foreach}
	</table>

	<br />

	<div align="center">
		<input type="submit" value="{$lang['acceptable']}" name="submit" />
	</div>

	<br />
</form>
