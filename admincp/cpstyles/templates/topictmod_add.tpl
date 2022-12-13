<br />

<div class="address_bar">{$lang['multi_moderation']} &raquo;
<a href="index.php?page=topic_mod&amp;add=1&amp;start=1">{$lang['add_new_multi_moderation']}</a>
&raquo; {$lang['add_new_multi_moderation']} </div>

<br />

<form action="index.php?page=topic_mod&amp;add=1&amp;start=1" method="post">
	<table width="98%" class="t_style_b" border="0" cellspacing="1" align="center">
		<tr align="center">
			<td class="main1" colspan="2">
{$lang['add_new_multi_moderation']}
			</td>
		</tr>
<tr>
			<td class="row1">
			{$lang['title_multi_moderation']}
			</td>
			<td class="row1">
				<input name="title" id="input_title" value size="30" type="text">
			</td>
		</tr>
		<tr>
			<td class="row1">
			<b>{$lang['Activating_property_in_the_forums']}</b><br />
{$lang['You_can_select_more_than_one_forum']}
			</td>
			<td class="row1">
               <select name="forums[]" size="15" multiple="multiple">
               <option value="all">-- {$lang['All']} --</option>
        		{Des::foreach}{forums_list}{forum}
        		{if {$forum['parent']} == 0}
				<option value="{$forum['id']}" disabled="disabled">- {$forum['title']}</option>
				{else}
				<option value="{$forum['id']}">-- {$forum['title']}</option>
				{/if}
				{if {$forum['parent']} != 0}
		       {if {$forum['linksection']} != '1'}
				 {if {$forum['is_sub']}}
					{$forum['sub']}
				 {if {$forum['is_sub_sub']}}
					{$forum['sub_sub']}
				{/if}
				{/if}
					{/if}
	            {/if}
				{/Des::foreach}
			</select>
			 </td>
		</tr>
</table>
	<table width="98%" class="t_style_b" border="0" cellspacing="1" align="center">
		<tr>
			<td class="main1" colspan="2">
{$lang['Options_moderators']}
			</td>
			</tr>
			<tr>
			<td class="row1">
{$lang['Add_a_prefix_to_the_subject']}
			</td>
			<td class="row1">
				<input name="title_st" id="input_title_st" value size="30" type="text">
			</td>
		</tr>
			<tr>
			<td class="row1">
{$lang['Add_to_the_end_of_the_subject']}
			</td>
			<td class="row1">
				<input name="title_end" id="input_title_end" value size="30" type="text">
			</td>
		</tr>
			<tr>
			<td class="row1">
{$lang['Change_the_status_of_a_topic']}
			</td>
			<td class="row1">
				<select name="state" class="dropdown">
<option value="leave">{$lang['no_place']}</option>
<option value="close">{$lang['close']}</option>
<option value="open">{$lang['open']}</option>
</select>
			</td>
		</tr>
			<tr>
			<td class="row1">
{$lang['Change_the_status_of_the_pin']}
			</td>
			<td class="row1">
<select name="pin" class="dropdown">
<option value="leave">{$lang['no_place']}</option>
<option value="pin">{$lang['pin']}</option>
<option value="unpin">{$lang['un_pin']}</option>
</select>
			</td>
		</tr>
			<tr>
			<td class="row1">
{$lang['Change_if_approved']}
			</td>
			<td class="row1">
<select name="approve" class="dropdown">
<option value="0">{$lang['no_place']}</option>
<option value="1">{$lang['Approval_not_hidden']}</option>
<option value="2">{$lang['Approval_hidden']}</option>
</select>
</td>
		</tr>
			<tr>
			<td class="row1">
{$lang['move_thread']}
			</td>
			<td class="row1">
				<select name="move" id="move_id">
				<option value="-1" selected='selected'>{$lang['no_move']}</option>
        		{Des::foreach}{forums_list}{forum}
        		{if {$forum['parent']} == 0}
				<option value="{$forum['id']}" disabled="disabled">- {$forum['title']}</option>
				{else}
				<option value="{$forum['id']}">-- {$forum['title']}</option>
				{/if}
				{if {$forum['parent']} != 0}
		       {if {$forum['linksection']} != '1'}
				 {if {$forum['is_sub']}}
					{$forum['sub']}
				{/if}
					{/if}
	            {/if}
				{/Des::foreach}
				</select>
							</td>
		</tr>
</table>
	<table width="98%" class="t_style_b" border="0" cellspacing="1" align="center">
		<tr>
			<td class="main1" colspan="2">
{$lang['reply_options']}
			</td>
			</tr>
			<tr>
			<td class="row1">

{$lang['Enable_this_reply']}
<input name="reply" value="1" id="green" type="radio" checked>{$lang['yes']}
<input name="reply" value="0" checked id="red" type="radio">{$lang['no']}
<br />
	{$lang['Reply_to_this_topic']}
<br />

<link rel="stylesheet" href="../look/sceditor/minified/themes/default.min.css" id="theme-style" />
<script src="../look/sceditor/minified/sceditor.min.js"></script>
<script src="../look/sceditor/minified/formats/bbcode.js"></script>
<script src="../look/sceditor/languages/{$_CONF['info_row']['content_language']}.js"></script>
<textarea cols="50" id="text" name="reply_content" style="height:300px;width:77%;" dir="{$_CONF['info_row']['content_dir']}"></textarea>

{template}editor_js{/template}
			</td>
		</tr>
		</table>
			</td>
		</tr>
		</table>
	<br />
<div align="center">
				<input type="submit" value="  {$lang['acceptable']}  " name="submit" onClick="comm._submit();"/>
</div>
	<br />

</form>