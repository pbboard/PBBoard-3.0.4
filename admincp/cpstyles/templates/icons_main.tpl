<script language="JavaScript">
<!--
function checkAll(form){
  for (var i = 0; i < form.elements.length; i++){
    eval("form.elements[" + i + "].checked = form.elements[0].checked");
  }
}
-->
</script>

<br />
<style>
.row_smile_path
{max-width: 50px !important;
max-height: 50px !important;
}
</style>
<div class="address_bar">{$lang['Control_Panel']} &raquo;
 <a href="index.php?page=icon&amp;control=1&amp;main=1">{$lang['icons']}</a></div>

<br />
<form action="index.php?page=icon&amp;del=1&amp;del_checked=1&amp;" name="form1" method="post">
<table width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
	<tr align="center">
		<td class="main1">
		{$lang['icon']}
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
	{Des::while}{IcnList}
	<tr align="center">
		<td class="row1">
			<img class="row_smile_path" src="../{$IcnList['smile_path']}" alt="{$IcnList['smile_path']}" />
		</td>
		<td class="row1">
			<a href="index.php?page=icon&amp;edit=1&amp;main=1&amp;id={$IcnList['id']}">{$lang['edit']}</a>
		</td>
		<td class="row1">
			<a href="index.php?page=icon&amp;del=1&amp;main=1&amp;id={$IcnList['id']}">{$lang['Delet']}</a>
		</td>
        <td width="3%" class="row1" align="center">
		<input type="checkbox" name="check[]" value="{$IcnList['id']}" />
	  </td>
	</tr>
	{/Des::while}
	<tr align="center">
		<td class="row1" colspan="4">
<input class="button" name="delet" type="submit" value=" {$lang['Delet']} " /></td>
	</tr>
	</table>
</form>
