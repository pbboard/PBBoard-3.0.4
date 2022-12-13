<script language="JavaScript">
<!--
function checkAll(form){
  for (var i = 0; i < form.elements.length; i++){
    eval("form.elements[" + i + "].checked = form.elements[0].checked");
  }
}
-->
</script>
</head>
<body>

<br />

<div class="address_bar">{$lang['Control_Panel']}
 &raquo; <a href="index.php?page=member&amp;active_member=1&amp;main=1">{$lang['active_member']}</a></div>

<br />
<form action="index.php?page=member&amp;active_member=1&amp;start=1" name="form1" method="post">
<table cellpadding="3" cellspacing="1" width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
<tr valign="top">
	<td class="main1" colspan="4">{$lang['ActiveMemberNumber']}
	 ({$MemberNumber})</td>
</tr>
<tr valign="top" align="center">
	<td class="main2">{$lang['username']}</td>
	<td class="main2">{$lang['email']}</td>
	<td class="main2">{$lang['IP_addresses']}</td>
	<td width="3%" class="main2"><input type="checkbox" name="check_all" onClick="checkAll(this.form)"/></td>
</tr>
{Des::while}{MembersList}
<tr valign="top" align="center">
	<td class="row1"><a href="../index.php?page=profile&amp;show=1&amp;id={$MembersList['id']}" target="_blank">{$MembersList['username']}</a></td>
	<td class="row1">{$MembersList['email']}</td>
	<td class="row1">{$MembersList['member_ip']}</td>
	<td width="3%" class="row1"><input type="checkbox" name="check[]" value="{$MembersList['id']}" /></td>
</tr>
{/Des::while}
<tr valign="top" align="center">
	<td class="row1" colspan="4"><input class="button" name="active" type="submit" value="{$lang['acceptable']}" /></td>
</tr>
</table>
</form>
<span class="pager-left">{$pager} </span>