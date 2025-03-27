<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
 <a href="index.php?page=member&amp;warnings=1&amp;main=1">{$lang['View_warns']}</a></div>

<br />

<table cellpadding="3" cellspacing="1" width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
<tr valign="top" align="center">
	<td class="main1">{$lang['username']}</td>
	<td class="main1">بواسطة</td>
	<td class="main1">سبب الانذار</td>
	<td class="main1">بتاريخ</td>
	<td class="main1">ينتهي </td>
	<td class="main1">{$lang['warns_Cancel']}</td>

</tr>
{Des::while}{WarnedMembersList}
<tr valign="top" align="center">
	<td class="row1"><a href="../index.php?page=profile&amp;show=1&amp;username={$WarnedMembersList['warn_to']}" target="_blank">{$WarnedMembersList['warn_to']}</a></td>
	<td class="row1">{$WarnedMembersList['warn_from']}</td>
	<td class="row1"><textarea class="inputbox" tabindex="2" rows="3" cols="15" style="height: 39px; width: 127px;">{$WarnedMembersList['warn_text']}</textarea></td>
	<td class="row1">{$WarnedMembersList['warn_date']}</td>
	<td class="row1">{$WarnedMembersList['warn_liftdate']}</td>
	<td class="row1"><a href="index.php?page=member&amp;warnings=1&amp;warn_del=1&amp;username={$WarnedMembersList['warn_to']}&amp;id={$WarnedMembersList['id']}"><b>{$lang['warns_Cancel']}</b></a></td>


</tr>
{/Des::while}
</table>
<br />
{if {$pager}}
<span class="pager-left">{$pager} </span>
{/if}

