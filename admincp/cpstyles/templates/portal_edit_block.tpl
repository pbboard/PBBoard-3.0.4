<br />
<?php
$id  =  $PowerBB->_CONF['template']['id'];
      $blocks_info = $PowerBB->DB->sql_query("SELECT  *   FROM " . $PowerBB->prefix."blocks" . " WHERE id = '$id'");
      $get_blocks_row = $PowerBB->DB->sql_fetch_array($blocks_info);
       ?>
<div class="address_bar">{$lang['Control_Panel']}
 &raquo; <a href="index.php?page=portal&amp;control=1&amp;main=1">{$lang['portal']}</a>
  &raquo; <a href="index.php?page=portal&amp;control_blocks=1&amp;main=1">{$lang['control_blocks']}</a>
  &raquo;{$lang['edit']}:
 <a href="index.php?page=portal&edit_block=1&main=1&id=<?php echo $get_blocks_row['id'];?>"><?php echo $get_blocks_row['title'];?> </a>
</div>

<br />

<form action="index.php?page=portal&amp;edit_block=1&amp;start=1&amp;id=<?php echo $get_blocks_row['id'];?>" method="post">
	<table width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
		<tr align="center">
			<td class="main1" colspan="2">
			{$lang['edit']} :
			<?php echo $get_blocks_row['title'];?>
			</td>
		</tr>
			<td class="row1" colspan="2">
{$lang['block_text']}
<br />
<b>{$lang['Writable_Bocuad_Html']}</b>
<br />
<link rel="stylesheet" href="../look/sceditor/minified/themes/default.min.css" id="theme-style" />
<script src="../look/sceditor/minified/sceditor.min.js"></script>
<script src="../look/sceditor/minified/formats/bbcode.js"></script>
<script src="../look/sceditor/languages/{$_CONF['info_row']['content_language']}.js"></script>
<textarea cols="50" id="text" name="text" style="height:300px;width:77%;" dir="{$_CONF['info_row']['content_dir']}">
<?php echo $get_blocks_row['text'];?>
</textarea>
{template}editor_js{/template}
			</td>
		</tr>
		<tr>
			<td class="row2" colspan="2" align="center">
					<input type="submit" value="{$lang['acceptable']}" name="submit" /></td>
		</tr>
		</table>

	<br />



	<br />

</form>