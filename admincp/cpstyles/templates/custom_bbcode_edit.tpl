<br />

<div class="address_bar">{$lang['Control_Panel']}
 &raquo; <a href="index.php?page=custom_bbcode&amp;control=1&amp;main=1">{$lang['custom_bbcodes']}</a>
  &raquo;{$lang['edit_bbcode']} :
  {$Custom_bbcodeEdit['bbcode_title']} </div>

<br />

<form action="index.php?page=custom_bbcode&amp;edit=1&amp;start=1&amp;id={$Custom_bbcodeEdit['id']}" method="post">
	<table width="98%" class="t_style_b" border="0" cellspacing="1" align="center">
		<tr>
			<td class="main1" width="98%" colspan="2" align="center"><b>
			{$lang['edit_bbcode']}
			</b>
			</td>
			</tr>
		<tr>
			<td class="row2" valign="middle" width="40%">
			<b>{$lang['bbcode_title']}</b>
			</td>
			<td class="row2" valign="middle" width="60%">
			<input name="bbcode_title" size="30" class="textinput" type="text" value="{$Custom_bbcodeEdit['bbcode_title']}"></td>
		</tr>
		<tr>
			<td class="row2" valign="middle" width="40%">
			<b>{$lang['bbcode_description']}</b></td>
			<td class="row2" valign="middle" width="60%">
			<textarea name="bbcode_desc" cols="50" rows="5" wrap="soft" id="bbcode_desc" class="multitext">{$Custom_bbcodeEdit['bbcode_desc']}</textarea></td>
		</tr>
		<tr>
			<td class="row2" valign="middle" width="40%">
			<b>{$lang['bbcode_example']}</b>
				<br>
			<div style="color: gray;">{$lang['Example']}: [tag]{$lang['This_is_an_example']}![/tag] </div>
			</td>
			<td class="row2" valign="middle" width="60%">
			<textarea name="bbcode_example" cols="50" rows="5" wrap="soft" id="bbcode_example" class="multitext">{$Custom_bbcodeEdit['bbcode_example']}</textarea></td>
		</tr>
		<tr>
			<td class="row2" valign="middle" width="40%">
			<b>{$lang['bbcode_tag']}</b>
			<br>
				<div style="color: gray;">{$lang['Example']}: {$lang['bbcode_tag_example']} <b>tag</b></div>
			</td>
			<td class="row2" valign="middle" width="60%">[
			<input name="bbcode_tag" size="10" class="textinput" type="text" value="{$Custom_bbcodeEdit['bbcode_tag']}">
			]</td>
		</tr>
		<tr>
			<td class="row2" valign="middle" width="40%"><b>
			{$lang['bbcode_useoption1']}</b><div style="color: gray;">
				{$lang['bbcode_useoption1_example']}</div>
			</td>
			<td class="row2" valign="middle" width="60%">
{if {$Custom_bbcodeEdit['bbcode_useoption']} == '1'}
<input name="bbcode_useoption1" value="1" checked id="green" type="radio" checked>{$lang['yes']}
<input name="bbcode_useoption1" value="0" id="red" type="radio">{$lang['no']}
{else}
<input name="bbcode_useoption1" value="1" id="green" type="radio">{$lang['yes']}
<input name="bbcode_useoption1" value="0" checked id="red" type="radio" checked>{$lang['no']}
{/if}
			</td>
		</tr>
		<tr>
			<td class="row2" valign="middle" width="40%">
			{$lang['bbcode_switch']}
			</td>
			<td class="row2" valign="middle" width="60%">
			<input name="bbcode_switch" size="40" class="textinput" type="text" value="{$Custom_bbcodeEdit['bbcode_switch']}"></td>
		</tr>
			<tr>
			<td class="row2" valign="middle" width="40%">
			<b>{$lang['bbcode_replace']}</b><div style="color: gray;">
				&lt;tag&gt;{content}&lt;/tag&gt;<br>
				&lt;tag thing='{option}'&gt;{content}&lt;/tag&gt;</div>
			</td>
			<td class="row2" valign="middle" width="60%">
			<textarea name="bbcode_replace" cols="50" rows="5" wrap="soft" id="bbcode_replace" class="multitext">{$Custom_bbcodeEdit['bbcode_replace']}</textarea></td>
		</tr>
		<tr>
			<td class="row1" colspan="2" align="center">
			<input value="{$lang['Adopted_amendments']}" class="realbutton" accesskey="s" type="submit"></td>
		</tr>
	</table>

	<br />



	<br />

</form>