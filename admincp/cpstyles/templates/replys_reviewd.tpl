<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
 <a href="">{$lang['replys_review_num']}</a> <b>{$ReviewNumber}</b></div>

<br />
{if {$Reviewsubjects}}
<div class="expcol main1"><a href="index.php?page=subject&amp;review=1&amp;main=1"><i class="fa fa-commenting-o" aria-hidden="true"></i> {$lang['subjects_review']}</a></div>
{/if}
<br />
{if {$ReviewNumber}}
<div class="center_text_align">
 <script language="JavaScript">
function checkAll(form){
for (var i = 0; i < form.elements.length; i++){
eval("form.elements[" + i + "].checked = form.elements[0].checked");
}
}
</script>
<form action="index.php?page=subject&amp;review=1&amp;review_reply=1&amp;do_review_reply=1" name="form1" method="post">
{/if}
<table cellpadding="3" cellspacing="1" width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
<tr valign="top" align="center">
	<td class="row2" width="60%">{$lang['title']}</td>
	<td class="row2" width="10%">{$lang['writer']}</td>
{if {$ReviewNumber}}
	<td class="row2" width="auto"><div class="l-left">
<div align="left"><input type="checkbox" name="check_all" onClick="checkAll(this.form)" /></div>
<div class="r-right">
&nbsp;<input class="button expcol" name="deletposts" type="submit" value="{$lang['Delet']}" />
&nbsp;<input class="button expcol" name="approveposts" type="submit" value="{$lang['Approval_not_hidden']}" />
</div>
</div></td>
{/if}
</tr>
{Des::while}{ReviewList}
<tr valign="top">
	<td class="row1" align="right" width="60%"><a target="_blank" href="../post-{$ReviewList['id']}">({$ReviewList['id']}) {$ReviewList['title']}</a></td>
	<td class="row1" align="center" width="10%"><a target="_blank" href="../index.php?page=profile&amp;show=1&amp;username={$ReviewList['writer']}">{$ReviewList['writer']}</a></td>
	{if {$ReviewNumber}}
<td class="row1" width="auto">
	<div align="left"><input type="checkbox" name="check[]" value="{$ReviewList['id']}" /></div>
</td>
{/if}
</tr>
{/Des::while}
</table>
{if {$ReviewNumber}}
</form>
</div>
{/if}

<br />
<table class="wd98 brd1 clpc0 a-center" style="border-collapse: collapse">
<tr>
<td class="right_text_align wd2">
{if {$pager}}
{$pager}
{/if}
</td>
</tr>
</table><br />
<br />
