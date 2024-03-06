<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=style&amp;edit_css=1&amp;main=1&amp;id={$Inf['id']}"> {$lang['edit']} :
{$Inf['style_title']} &raquo;
{$lang['CSS']}</a> </div>

<br />

<form action="index.php?page=style&amp;edit_css=1&amp;start=1&amp;id={$Inf['id']}" method="post">

	<table cellpadding="3" cellspacing="1" width="60%" class="t_style_b" border="0" align="center">
		<tr valign="top" align="center">
			<td class="main1">
{$lang['CSS']}   :
{$Inf['style_title']}
			</td>
		</tr>
		<tr valign="top">
			<td class="row1" align="center">
				<textarea rows="45" cols="90" name="css_context" dir="ltr">{$css_context}</textarea>
				<input type="hidden" id="style_pathId" name="style_path" value="{$style_path}">
			</td>
		</tr>
	</table>

	<br />

	<div align="center">
		<input type="submit" value=" {$lang['Save_and_reload']} " name="submit" />
	</div>
</form>