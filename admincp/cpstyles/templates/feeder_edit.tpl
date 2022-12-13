<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
 <a href="index.php?page=feeder&amp;control=1&amp;main=1">{$lang['feed_rss']}</a> &raquo;<a href="index.php?page=feeder&amp;edit=1&amp;main=1&amp;id={$FeedEdit['id']}"> {$lang['postr_rss']}
- {$FeedEdit['title2']}</a></div>

<br />

<form action="index.php?page=feeder&amp;edit=1&amp;start=1&amp;id={$FeedEdit['id']}"  name="myform" method="post">
<div align="center">
<table cellpadding="3" cellspacing="1" width="90%" class="t_style_b" border="0" cellspacing="1">
<tr valign="top" align="center">
	<td class="main1" colspan="2">{$lang['postr_rss']}
	- {$FeedEdit['title2']}
	</td>
</tr>
<tr valign="top">
		<td class="row1" width="20%">{$lang['Feed_URL']}</td>
		<td class="row1">

<input type="text" name="link" id="input_name" value="{$FeedEdit['rsslink']}" size="50" />&nbsp;
</td>
</tr>
<tr valign="top">
		<td class="row1">{$lang['Effective_feeds']}</td>
<td class="row1">
{if {$FeedEdit['options']}}
            <label for="options">
			<input name="options" value="1" tabindex="1" checked type="radio">{$lang['yes']}
			<input name="options" value="0" tabindex="1" type="radio">{$lang['no']}
			</label>
			{else}
			<label for="options">
			<input name="options" value="1" tabindex="1" type="radio">{$lang['yes']}
			<input name="options" value="0" tabindex="1" type="radio" checked>{$lang['no']}
			</label>
			{/if}
			</td>
</tr>
<tr valign="top">
		<td class="row1">{$lang['title']}</td>
		<td class="row1">
 <input type="text" name="title2" id="input_name" value="{$FeedEdit['title2']}" size="30" />
<input type="hidden" name="title" id="input_name" value="{rss:title}" size="30" />&nbsp;
</td>
</tr>
<tr valign="top">
		<td class="row1">{$lang['mem_name']}</td>
		<td class="row1">

<input type="text" name="member" id="input_name" value="{$username}" size="30" />&nbsp;
</td>
</tr>
<tr valign="top">
		<td class="row1">{$lang['by_Forum']}
</td>
			<td class="row2">
           <select name="section" id="select_section">
				{Des::foreach}{forums_list}{forum}
        		{if {$forum['parent']} == 0}
        			<option value="{$forum['id']}" disabled="disabled">- {$forum['title']}</option>
                     {else}
			        {if {$forum['id']} == {$forumid}}
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

<tr valign="top">
		<td class="row1">
{$lang['Check_the_feed_of_all']}
</td>
		<td class="row1">
			<select name="ttl" id="sel_ttl" tabindex="1">
{if {$FeedEdit['ttl']} == 600}
<option value="600" selected="selected">
10 {$lang['minutes']}
</option>
{else}
<option value="600">
10 {$lang['minutes']}
</option>
{/if}
{if {$FeedEdit['ttl']} == 1200}
<option value="1200" selected="selected">
20 {$lang['minutes']}
</option>
{else}
<option value="1200">
20 {$lang['minutes']}
</option>
{/if}
{if {$FeedEdit['ttl']} == 1800}
<option value="1800" selected="selected">
30 {$lang['minutes']}
</option>
{else}
<option value="1800">
30 {$lang['minutes']}
</option>
{/if}
{if {$FeedEdit['ttl']} == 3600}
<option value="3600" selected="selected">
60 {$lang['minutes']}
</option>
{else}
<option value="3600">
60 {$lang['minutes']}
</option>
{/if}
{if {$FeedEdit['ttl']} == 7200}
<option value="7200" selected="selected">
2 {$lang['hours']}</option>
</option>
{else}
<option value="7200">
2 {$lang['hours']}</option>
</option>
{/if}
{if {$FeedEdit['ttl']} == 14400}
<option value="14400" selected="selected">
4 {$lang['hours']}</option>}
</option>
{else}
<option value="14400">
4 {$lang['hours']}</option>
</option>
{/if}
{if {$FeedEdit['ttl']} == 21600}
<option value="21600" selected="selected">
6 {$lang['hours']}
</option>
{else}
<option value="21600">
6 {$lang['hours']}
</option>
{/if}
{if {$FeedEdit['ttl']} == 28800}
<option value="21600" selected="selected">
8 {$lang['hours']}
</option>
{else}
<option value="28800">
8 {$lang['hours']}
</option>
{/if}
{if {$FeedEdit['ttl']} == 36000}
<option value="36000" selected="selected">
10 {$lang['hours']}
</option>
{else}
<option value="36000">
10 {$lang['hours']}
</option>
{/if}
{if {$FeedEdit['ttl']} == 43200}
<option value="43200" selected="selected">
12 {$lang['hours']}
</option>
{else}
<option value="43200">
12 {$lang['hours']}
</option>
{/if}
&nbsp;
</td>
</tr>
<tr valign="top">
		<td class="row2">{$lang['Text_of_topic']}</td>
		<td class="row2">
{$lang['Defining_element_of_the_type_of_rss']}
({rss:<i>example</i>})
{$lang['where_example_is_the_name_of_the_item']}
<textarea name="text" id="textarea_text" rows="17" cols="50" wrap="virtual" dir="{$_CONF['info_row']['content_dir']}">
{$FeedEdit['text']}
</textarea>&nbsp;

</td>
</tr>
</table></div>

<tr>
	<td class="submit-buttons" colspan="2" align="center">

	<input class="submit" type="submit" value="   {$lang['Save']}    " name="submit" accesskey="s" /></td>
</tr>
</table><br />
</form>
