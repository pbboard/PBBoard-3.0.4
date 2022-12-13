<br />
<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=member&amp;mail=1&amp;main=1">{$lang['members']}</a> &raquo;
<a href="index.php?page=pm&amp;send_pm=1&amp;pm=1"> {$lang['send_pm_members']}</a></div>
<br />
<form name="mail" action="index.php?page=pm&amp;start=1&amp;pm=1" name="myform" method="post">

  <div align="center">
    <table cellpadding="3" cellspacing="1" width="98%" class="t_style_b" border="0" cellspacing="1" align="center">
      <tr>
        <td class="main1" colspan="2">{$lang['send_pm_members']}</td>
      </tr>
      <tr>
        <td class="row1" width="30%">{$lang['Message_Title']}</td>
        <td class="row1" width="40%"><input type="text" name="title" size="28"></td>
      </tr>
	<tr>
		<td class="row1">
		{$lang['Select_the_group_you_want_by_sending_its_members']}
		</td>
		<td class="row1">
		     <select name="group" id="group_id">
             <option selected="selected" value="all">{$lang['All_groups']}</option>
			{Des::while}{GroupList}
				{if {$Inf['usergroup']} == {$GroupList['id']} }
				<option value="{$GroupList['id']}">{$GroupList['title']}</option>
				{else}
				<option value="{$GroupList['id']}">{$GroupList['title']}</option>
				{/if}
			{/Des::while}
			</select>
		</td>
	</tr>
      <tr>
		<td class="row1" colspan="2">
<link rel="stylesheet" href="../look/sceditor/minified/themes/default.min.css" id="theme-style" />
<script src="../look/sceditor/minified/sceditor.min.js"></script>
<script src="../look/sceditor/minified/formats/bbcode.js"></script>
<script src="../look/sceditor/languages/{$_CONF['info_row']['content_language']}.js"></script>
<textarea cols="50" id="text" name="text" style="height:300px;width:77%;" dir="{$_CONF['info_row']['content_dir']}"></textarea>
{template}editor_js{/template}</td>
	</tr>
	<tr>
		<td class="editoricon" valign="top" colspan="2">
{template}iconbox{/template}</td>
	</tr>
	<tr>
		<td class="row1" valign="top" colspan="2" align="center">
		     <input class="button" name="insert" type="submit" value=" {$lang['Send']} " onClick="comm._submit();" /></td>
	</tr>
</table></div>

</form>