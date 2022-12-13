<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
{$lang['custom_bbcodes']}
&nbsp;&raquo; <a href="index.php?page=custom_bbcode&amp;add=1&amp;main=1">{$lang['add_custom_bbcode']}</a> </div>

<br />

<form action="index.php?page=custom_bbcode&amp;add=1&amp;start=1" method="post">
	<table width="98%" class="t_style_b" border="0" cellspacing="1" align="center">
		<tr>
			<td class="main1" width="98%" colspan="2" align="center"><b>
			{$lang['add_custom_bbcode']}
			</b>
			</td>
			</tr>
		<tr>
			<td class="row1" valign="middle" width="40%">
			<b>{$lang['bbcode_title']}</b>
			</td>
			<td class="row1" valign="middle" width="60%">
			<input name="bbcode_title" value size="30" class="textinput" type="text"></td>
		</tr>
		<tr>
			<td class="row1" valign="top" width="40%">
			<b>{$lang['bbcode_description']}</b></td>
			<td class="row1" valign="middle" width="60%">
			<textarea name="bbcode_desc" cols="50" rows="5" wrap="soft" id="bbcode_desc" class="multitext"></textarea></td>
		</tr>
		<tr>
			<td class="row1" valign="top" width="40%">
			<b>{$lang['bbcode_example']}</b>
				<br><div style="color: gray;">
			{$lang['Example']}: [tag]{$lang['This_is_an_example']}![/tag] </div>
			</td>
			<td class="row1" valign="middle" width="60%">
			<textarea name="bbcode_example" cols="50" rows="5" wrap="soft" id="bbcode_example" class="multitext"></textarea></td>
		</tr>
		<tr>
			<td class="row1" valign="middle" width="40%">
			<b>{$lang['bbcode_tag']}</b>
			<br>
				<div style="color: gray;">{$lang['Example']}: {$lang['bbcode_tag_example']} <b>tag</b></div>
			</td>
			<td class="row1" valign="middle" width="60%">[
			<input name="bbcode_tag" value size="10" class="textinput" type="text">
			]</td>
		</tr>
		<tr>
			<td class="row1" valign="middle" width="40%"><b>
			{$lang['bbcode_useoption1']}</b><div style="color: gray;">
				{$lang['bbcode_useoption1_example']}</div>
			</td>
			<td class="row1" valign="middle" width="60%">

<input name="bbcode_useoption1" value="1" id="green" type="radio" checked>{$lang['yes']}
<input name="bbcode_useoption1" value="0" checked id="red" type="radio">{$lang['no']}
			</td>
		</tr>
		<tr>
			<td class="row1" valign="middle" width="40%">
			{$lang['bbcode_switch']}
			</td>
			<td class="row1" valign="middle" width="60%">
			<input name="bbcode_switch" value size="40" class="textinput" type="text"></td>
		</tr>
			<tr>
			<td class="row1" valign="top" width="40%">
			<b>{$lang['bbcode_replace']}</b><div style="color: gray;">
				&lt;tag&gt;{content}&lt;/tag&gt;<br>
				&lt;tag thing='{option}'&gt;{content}&lt;/tag&gt;</div>
			</td>
			<td class="row1" valign="middle" width="60%">
			<textarea name="bbcode_replace" cols="50" rows="5" wrap="soft" id="bbcode_replace" class="multitext"></textarea></td>
		</tr>
		<tr>
			<td class="row1" colspan="2" align="center">
			<input value="{$lang['Add_BBCode']}" class="realbutton" accesskey="s" type="submit"></td>
		</tr>
	</table>

	<br />



	<br />

</form>