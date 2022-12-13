		<div style="visibility:hidden;position:absolute;">

<table border="0" width="100%" cellpadding="0" style="border-collapse: collapse">

<tr valign="top" align="center">
	<td> </td>
	<td></td>
	<td></td>
	<td></td>
	<td></td>
</tr>
{Des::foreach}{forums_list}{forum}
<tr valign="top" align="center">
	{if {$forum['parent']} == 0}
	<td colspan="5">{$forum['title']}</td>
	{else}
	<td >
		<a href="index.php?page=forums&amp;forum=1&amp;index=1&amp;id={$forum['id']}">{$forum['title']}</a>
	</td>
	<td><a href="index.php?page=forums&amp;groups=1&amp;control_group=1&amp;index=1&amp;id={$forum['id']}"></a></td>
	<td><a href="index.php?page=forums&amp;edit=1&amp;main=1&amp;id={$forum['id']}"></a></td>
	<td><a href="index.php?page=forums&amp;del=1&amp;main=1&amp;id={$forum['id']}"></a></td>
	<td ><input type="text" name="order-{$forum['id']}" id="input_order-{$forum['id']}" value="{$forum['sort']}" size="5" /></td>
	{/if}
</tr>
{/Des::foreach}
</table><br />

		</div>