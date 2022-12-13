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

<div class="address_bar">{$lang['Control_Panel']} &raquo;
 <a href="index.php?page=smile&amp;control=1&amp;main=1">{$lang['smiles']}</a></div>

<br />
<form action="index.php?page=smile&amp;del=1&amp;del_checked=1&amp;" name="form1" method="post">
<table cellpadding="3" cellspacing="1" width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
<tr valign="top" align="center">
	<td class="main1">{$lang['smile']}</td>

	<td class="main1">{$lang['edit']}</td>
	<td class="main1">{$lang['Delet']}</td>
      <td class="main1" width="3%">
		<input type="checkbox" name="check_all" onClick="checkAll(this.form)"/>
		</td>
</tr>
{Des::while}{SmlList}
<tr valign="top" align="center">
	<td class="row1"><img src="../{$SmlList['smile_path']}" alt="" /></td>
	<td class="row1"><a href="index.php?page=smile&amp;edit=1&amp;main=1&amp;id={$SmlList['id']}">{$lang['edit']}</a></td>
	<td class="row1"><a href="index.php?page=smile&amp;del=1&amp;main=1&amp;id={$SmlList['id']}">{$lang['Delet']}</a></td>
   <td width="3%" class="row1" align="center"><input type="checkbox" name="check[]" value="{$SmlList['id']}" /></td>
</tr>
	{/Des::while}
	<tr align="center">
		<td class="row1" colspan="4">
<input class="button" name="delet" type="submit" value=" {$lang['Delet']}" /></td>
	</tr>
	</table>
</form>


