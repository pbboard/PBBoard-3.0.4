<br />

<div class="address_bar">{$lang['multi_moderation']} &raquo;
<a href="index.php?page=topic_mod&amp;control=1&amp;main=1" target="main">{$lang['mange_multi_moderation']}</a>&raquo;
<a href="index.php?page=topic_mod&amp;edit=1&amp;main=1&amp;id={$TopicModEdit['id']}">
{$lang['Edit_multi_moderation']}
({$TopicModEdit['title']})</a> </div>

<br />

<form action="index.php?page=topic_mod&amp;edit=1&amp;start=1&amp;id={$TopicModEdit['id']}" method="post">

	<table width="98%" class="t_style_b" border="0" cellspacing="1" align="center">
		<tr align="center">
			<td class="main1" colspan="2">
{$lang['Edit_multi_moderation']}
 ({$TopicModEdit['title']})
			</td>
		</tr>
<tr>
			<td class="row1">
			{$lang['title_multi_moderation']}
			</td>
			<td class="row1">
				<input name="title" id="input_title" value="{$TopicModEdit['title']}" size="30" type="text">
			</td>
		</tr>
		<tr>
			<td class="row1">
			<b>{$lang['Activating_property_in_the_forums']}</b><br />
{$lang['You_can_select_more_than_one_forum']}
			</td>
			<td class="row1">
             <select name="forums[]" size="15" multiple="multiple">
             {if {$TopicModEdit['forums']} == '*'}
				<option value="all" selected="selected">-- {$lang['All']} --</option>
					{else}
				<option value="all">-- {$lang['All']} --</option>
					{/if}
				{Des::foreach}{forums_list}{forum}
        		{if {$forum['parent']} == 0}
        			<option value="{$forum['id']}" disabled="disabled">- {$forum['title']}</option>
                     {else}
			        <?php if (in_array($forum['id'], explode(',', $PowerBB->_CONF['template']['TopicModEdit']['forums']))){?>
					<option value="{$forum['id']}" selected="selected">-- {$forum['title']}</option>
					{else}
					<option value="{$forum['id']}">-- {$forum['title']}</option>
					  {/if}
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
				<input name="title_st" id="input_title_st" value="{$TopicModEdit['title_st']}" size="30" type="text">
			</td>
		</tr>
			<tr>
			<td class="row1">
{$lang['Add_to_the_end_of_the_subject']}
			</td>
			<td class="row1">
				<input name="title_end" id="input_title_end" value="{$TopicModEdit['title_end']}" size="30" type="text">
			</td>
		</tr>
			<tr>
			<td class="row1">
{$lang['Change_the_status_of_a_topic']}
			</td>
			<td class="row1">
				<select name="state" class="dropdown">
{if {$TopicModEdit['state']} == 'leave'}
<option value="leave" selected="selected">{$lang['no_place']}</option>
{else}
<option value="leave">{$lang['no_place']}</option>
{/if}
{if {$TopicModEdit['state']} == 'close'}
<option value="close" selected="selected">{$lang['close']}</option>
{else}
<option value="close">{$lang['close']}</option>
{/if}
{if {$TopicModEdit['state']} == 'open'}
<option value="open" selected="selected">{$lang['open']}</option>
{else}
<option value="open">{$lang['open']}</option>
{/if}
</select>
			</td>
		</tr>
			<tr>
			<td class="row1">
{$lang['Change_the_status_of_the_pin']}
			</td>
			<td class="row1">
<select name="pin" class="dropdown">
{if {$TopicModEdit['pin']} == 'leave'}
<option value="leave" selected="selected">{$lang['no_place']}</option>
{else}
<option value="leave">{$lang['no_place']}</option>
{/if}
{if {$TopicModEdit['pin']} == 'pin'}
<option value="pin" selected="selected">{$lang['pin']}</option>
{else}
<option value="pin">{$lang['pin']}</option>
{/if}
{if {$TopicModEdit['pin']} == 'unpin'}
<option value="unpin" selected="selected">{$lang['un_pin']}</option>
{else}
<option value="unpin">{$lang['un_pin']}</option>
{/if}
</select>
			</td>
		</tr>
			<tr>
			<td class="row1">
{$lang['Change_if_approved']}
			</td>
			<td class="row1">
<select name="approve" class="dropdown">
{if {$TopicModEdit['approve']} == '0'}
<option value="0" selected="selected">{$lang['no_place']}</option>
{else}
<option value="0">{$lang['no_place']}</option>
{/if}
{if {$TopicModEdit['approve']} == '1'}
<option value="1" selected="selected">{$lang['Approval_not_hidden']}</option>
{else}
<option value="1">{$lang['Approval_not_hidden']}</option>
{/if}
{if {$TopicModEdit['approve']} == '2'}
<option value="2" selected="selected">{$lang['Approval_hidden']}</option>
{else}
<option value="2">{$lang['Approval_hidden']}</option>
{/if}
</select>
			</td>
		</tr>
			<tr>
			<td class="row1">
{$lang['move_thread']}
			</td>
			<td class="row1">
				<select name="move" id="move_id">
				{if {$TopicModEdit['move']} == '-1'}
				<option value="-1" selected="selected">{$lang['no_move']}</option>
				{else}
				<option value="-1">{$lang['no_move']}</option>
				{/if}
        				{Des::foreach}{forums_list}{forum}
        		{if {$forum['parent']} == 0}
        			<option value="{$forum['id']}" disabled="disabled">- {$forum['title']}</option>
                     {else}
					{if {$forum['id']} == {$TopicModEdit['move']}}
					<option value="{$forum['id']}" selected="selected">-- {$forum['title']}</option>
					{else}
					<option value="{$forum['id']}">-- {$forum['title']}</option>
					  {/if}
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
{$lang['reply_options']}
			</td>
			</tr>
			<tr>
			<td class="row1">

{$lang['Enable_this_reply']}
{if {$TopicModEdit['reply']} == '1'}
<input name="reply" value="1" id="reply" type="radio" checked="checked">{$lang['yes']}
&nbsp;&nbsp;<input name="reply" value="0" id="reply" type="radio">{$lang['no']}
{else}
<input name="reply" value="1" id="reply" type="radio">{$lang['yes']}
&nbsp;&nbsp;<input name="reply" value="0" id="reply" type="radio" checked="checked">{$lang['no']}
{/if}
<br />
			{$lang['Reply_to_this_topic']}
<br />


<link rel="stylesheet" href="../look/sceditor/minified/themes/default.min.css" id="theme-style" />
<script src="../look/sceditor/minified/sceditor.min.js"></script>
<script src="../look/sceditor/minified/formats/bbcode.js"></script>
<script src="../look/sceditor/languages/{$_CONF['info_row']['content_language']}.js"></script>
<textarea cols="50" id="text" name="reply_content" style="height:300px;width:77%;" dir="{$_CONF['info_row']['content_dir']}">
{$TopicModEdit['reply_content']}
</textarea>

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