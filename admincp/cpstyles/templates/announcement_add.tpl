<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
 <a href="index.php?page=announcement&amp;control=1&amp;main=1">{$lang['announcement']}</a>
 &raquo; {$lang['Add_new_announcement']}</div>

<br />

<form action="index.php?page=announcement&amp;add=1&amp;start=1" method="post">
	<div align="center">
	<table width="90%" class="t_style_b" border="0" cellspacing="1">
		<tr align="center">
			<td class="main1">
			{$lang['Add_new_announcement']}
			</td>
		</tr>
		<tr>
			<td class="row1">
			{$lang['title']}
				<input type="text" name="title" size="50"  />
			</td>
		</tr>
		<tr>
			<td class="row2">
					{$lang['text_announcement']}
<br />
<link rel="stylesheet" href="../look/sceditor/minified/themes/default.min.css" id="theme-style" />
<script src="../look/sceditor/minified/sceditor.min.js"></script>
<script src="../look/sceditor/minified/formats/bbcode.js"></script>
<script src="../look/sceditor/languages/{$_CONF['info_row']['content_language']}.js"></script>
<textarea cols="50" id="text" name="text" style="height:300px;width:77%;" dir="{$_CONF['info_row']['content_dir']}"></textarea>
{template}editor_js{/template}
</td>
		</tr>
		<tr>
			<td class="row2" align="center">
				<input type="submit" value="  {$lang['acceptable']}  " name="submit"/>

</td>
		</tr>
	</table>

	</div>

	<br />
	<br />

</form>