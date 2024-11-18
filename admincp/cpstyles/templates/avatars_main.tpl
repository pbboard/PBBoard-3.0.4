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
<style>
.row_avatar_path
{
max-width: 100px !important;
max-height: 100px !important;
}
</style>
<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=avatar&amp;control=1&amp;main=1">{$lang['avatars']}</a></div>

<br />
<form action="index.php?page=avatar&amp;del=1&amp;del_checked=1&amp;" name="form1" method="post">
<table width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
	<tr align="center">
		<td class="main1">
		{$lang['Image']}
		</td>
		<td class="main1">
		{$lang['edit']}
		</td>
		<td class="main1">
		{$lang['Delet']}
		</td>
      <td class="main1" width="3%">
		<input type="checkbox" name="check_all" onClick="checkAll(this.form)"/>
		</td>
	</tr>
	{Des::while}{AvrList}
	<tr align="center">
		<td class="row1">
			<img src="../{$AvrList['avatar_path']}" alt="{$AvrList['avatar_path']}" class="row_avatar_path" />
		</td>
		<td class="row1">
			<a href="index.php?page=avatar&amp;edit=1&amp;main=1&amp;id={$AvrList['id']}">{$lang['edit']}</a>
		</td>
		<td class="row1">
			<a href="index.php?page=avatar&amp;del=1&amp;main=1&amp;id={$AvrList['id']}">{$lang['Delet']}</a>
		</td>
        <td width="3%" class="row1" align="center">
		<input type="checkbox" name="check[]" value="{$AvrList['id']}" />
	  </td>
	</tr>
	{/Des::while}
	<tr align="center">
		<td class="row1" colspan="4">
<input class="button" name="delet" type="submit" value=" {$lang['Delet']}" /></td>
	</tr>
	</table>
</form>