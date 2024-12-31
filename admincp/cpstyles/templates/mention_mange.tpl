<br />
<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=options&amp;index=1">{$lang['mange_forum']}</a> &raquo;
<a href="index.php?page=options&amp;mention=1&amp;mainmention=1&amp;mention_main=1">{$lang['a_mention']}</a></div>
<br />
<form action="index.php?page=options&amp;mention=1&amp;mention_update=1" method="post">
<table cellpadding="4" cellspacing="0" border="0" align="center" width="90%" style="border-collapse:separate" class="t_style_b">
<tr>
<td class="main1" align="center" colspan="2">
<b>{$lang['a_mention']}</b>
</td>
</tr>
<tr valign="top">
<td class="main2" colspan="2">
<div>
	{$lang['c_mention']}</div>
</td>
</tr>
<tr valign="top">
<td class="row1">
<div class="smallfont">
{$lang['b_mention']}
</div>
</td>
<td class="row1">
{if {$_CONF['info_row']['mention_active']}}
<input name="mention_active" value="1" id="mention_active" type="radio" checked="checked">{$lang['yes']}
&nbsp;&nbsp;<input name="mention_active" value="0" id="mention_active" type="radio">{$lang['no']}
{else}
<input name="mention_active" value="1" id="mention_active" type="radio">{$lang['yes']}
&nbsp;&nbsp;<input name="mention_active" value="0" id="mention_active" type="radio" checked="checked">{$lang['no']}
{/if}
</td>
</tr>
</tbody>
<tr valign="top">
<td class="main2" colspan="2">
<div>
{$lang['mention_not_forum']}
	</div>
</td>
</tr>
<tr valign="top">
<td class="row1"><div class="smallfont">
	<div class="smallfont">
{$lang['mention_not_useforum']}
</div>
</td>
<td class="row1" width="30%">
<input type="text" class="bginput" name="mention_exforum" id="mention_exforum" value="{$_CONF['info_row']['mention_exforum']}" dir="ltr" size="2" min="1" max="5"">
</td>
</tr>

<tr valign="top">
<td class="main2" colspan="2">
<div>
{$lang['mention_usergroup']}
	</div>
</td>
</tr>
<tr valign="top">
<td class="row1"><div class="smallfont">
	<div class="smallfont">
{$lang['mention_usergroup_using']}
</div>
</td>
<td class="row1">
<input type="text" class="bginput" name="mention_exusergroups" id="mention_exusergroups" value="{$_CONF['info_row']['mention_exusergroups']}" dir="ltr" size="2" min="1" max="5"">
</td>
</tr>
<tr valign="top">
<td class="main2" colspan="2">
<div>
{$lang['mention_user']}
	</div>
	</td>
</tr>
<tr valign="top">
<td class="row1"><div class="smallfont">
{$lang['mention_user_useing']}
</div></td>
<td class="row1">
<input type="text" class="bginput" name="mention_exusers" id="mention_exusers" value="{$_CONF['info_row']['mention_exusers']}" dir="ltr" size="2" min="1" max="5"">
</td>
</tr>
<tr>
<td class="row3" colspan="2" align="center">
<input type="submit" id="submit0" class="button" tabindex="1" value="  {$lang['Save']}   " />
</td>
</tr>
</table>
</form>