

<br />
<div class="address_bar"><a href="index.php">{$lang['Control_Panel']}</a> &raquo;
ملحق منتدى إجابات
</div>
<br />
<form action="index.php?page=options&amp;hooks=1&amp;update=1&amp;ejabat=1" method="post">
<table cellpadding="3" cellspacing="1" width="500px" class="t_style_b" border="0" align="center">
<tr align="center">
<td class="main1" colspan="3">
<b>{$lang['Settings']}</b>
</td>
</tr>

<tr>
<td class="row1">
اكبس Ctrl وانقر على إسم المنتدى للإختيار المتعدد
</td>
<td class="row1">
منتدى إجابات
</td>
<td class="row1">
</td>
</tr>

<tr>
<td class="row1">
<select name="section[]" size="10" multiple="multiple">
<?php
$query = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['section'] . " WHERE  sec_section='0' AND hide_subject='0' AND parent<>'0' ORDER BY id DESC");
while ($pbb = $PowerBB->DB->sql_fetch_array($query)) {
  $pbb[title] = $PowerBB->Powerparse->_wordwrap($pbb['title'], 50);
  if ($pbb['ejabat_section'] == 1) {
    echo '<option value="' . $pbb[id] . '" selected="selected">' . $pbb[title] . '</option>';
  }
  else {
    echo '<option value="' . $pbb[id] . '">' . $pbb[title] . '</option>';
  }
}
unset ($query);
?>
</select>
</td>
<td class="row1" valign="top">
<select name="ejabat_section">
<option value="x"></option>
<option value="1">{$lang['yes']}</option>
<option value="0">{$lang['no']}</option>
</select>
</td>
<td class="row1" valign="top">
<input type="submit" value="{$lang['acceptable']}" name="submit">
</td>
</tr>

</table>
<br />
</form>
<br />

<table cellpadding="3" cellspacing="1" width="500px" class="t_style_b" border="0" align="center">
<tr>
<td class="row1" align="center">
<b>
ملحق منتدى إجابات
: <a href="http://php-max.com/" target="_blank"><u>Php-Max.com</u></a> </b>
</td>
</tr>
<tr>
<td class="row1" align="center">
<p style="margin-top:10px; margin-bottom:0; padding-bottom:0; text-align:center; line-height:0"><a target="_blank" href="http://feeds.feedburner.com/~r/Php-maxcom/~6/1"><img src="http://feeds.feedburner.com/Php-maxcom.1.gif" alt="php-max.com" style="border:0"></a></p>
</td>
</tr>
</table>
<br />

