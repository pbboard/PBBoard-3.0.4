<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=options&amp;index=1">{$lang['mange_forum']}</a> &raquo;
 <a href="index.php?page=options&amp;pages=1&amp;main=1">{$lang['pages_Settings']}</a></div>

<br />

<form action="index.php?page=options&amp;pages=1&amp;update=1"  name="myform" method="post">
<table cellpadding="3" cellspacing="1" width="80%" class="t_style_b" border="0" align="center">
<tr valign="top" align="center">
	<td class="main1" colspan="2">{$lang['pages_Settings']}</td>
</tr>
<tr valign="top">
		<td class="row1">{$lang['page_max']}</td>
		<td class="row1">
<input type="text" name="page_max" id="input_page_max" value="{$_CONF['info_row']['page_max']}" size="30" />&nbsp;
</td>
</tr>
<tr valign="top">
<td class="row2">{$lang['subject_perpage']}</td>
		<td class="row2">
<input type="text" name="subject_perpage" id="input_subject_perpage" value="{$_CONF['info_row']['subject_perpage']}" size="30" />&nbsp;
</td>
</tr>
<tr valign="top">
<td class="row1">{$lang['reply_perpage']}</td>
		<td class="row1">
<input type="text" name="reply_perpage" id="input_reply_perpage" value="{$_CONF['info_row']['perpage']}" size="30" />&nbsp;
</td>
</tr><tr valign="top">
		<td class="row1">{$lang['avatar_perpage']}</td>
		<td class="row1">
<input type="text" name="avatar_perpage" id="input_avatar_perpage" value="{$_CONF['info_row']['avatar_perpage']}" size="30" />&nbsp;
</td>
</tr>
<tr valign="top">
		<td class="row1" colspan="2" align="center">
		<input class="submit" type="submit" value="{$lang['acceptable']}" name="submit" accesskey="s" />
		</td>
</tr>
</table><br />
<br />

</form>