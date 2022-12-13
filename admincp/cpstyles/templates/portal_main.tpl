<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=portal&amp;control=1&amp;main=1">{$lang['settings_portal']}</a></div>

<br />

<br />

<form action="index.php?page=portal&amp;control=1&amp;start=1"  name="myform" method="post">
<table cellpadding="3" cellspacing="1" width="80%" class="t_style_b" border="0" align="center">
<tr valign="top" align="center">
	<td class="main1" colspan="2">{$lang['settings_portal']}</td>
</tr>
<tr valign="top">
		<td class="row1">{$lang['title_portal']}</td>
		<td class="row1">
<input type="text" name="title_portal" id="input_title_portal" value="{$_CONF['info_row']['title_portal']}" size="30" />
</td>
</tr>
<tr>
	<td class="row2">
	{$lang['active_portal']}
	</td>
	<td class="row2">
		<select name="active_portal">
		{if {$_CONF['info_row']['active_portal']}}
			<option value="1" selected="selected">{$lang['yes']}</option>
			<option value="0">{$lang['no']}</option>
		{else}
			<option value="1">{$lang['yes']}</option>
			<option value="0" selected="selected">{$lang['no']}</option>
		{/if}
		</select>
	</td>
</tr>
<td class="row2">{$lang['portal_section_news']}</td>
		<td class="row2">
               <select name="portal_section_news[]" size="15" multiple="multiple">
		        <?php if ($PowerBB->_CONF['info_row']['portal_section_news'] == '*'){?>
               <option value="all" selected="selected">-- {$lang['All']} --</option>
               {else}
               <option value="all">-- {$lang['All']} --</option>
               {/if}
        				{Des::foreach}{forums_list}{forum}
        		{if {$forum['parent']} == 0}
        			<option value="{$forum['id']}" disabled="disabled">- {$forum['title']}</option>
                     {else}
			        <?php if (in_array($forum['id'], explode(',', $PowerBB->_CONF['info_row']['portal_section_news']))){?>
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
		<td class="row1">{$lang['portal_news_num']}</td>
		<td class="row1">
<input type="text" name="portal_news_num" id="input_portal_news_num" value="{$_CONF['info_row']['portal_news_num']}" size="2" maxlength="2" />
</td>
</tr>
<tr valign="top">
		<td class="row1">{$lang['portal_news_along']}</td>
		<td class="row1">
<input type="text" name="portal_news_along" id="input_portal_news_along" value="{$_CONF['info_row']['portal_news_along']}" size="2" maxlength="3" />
</td>
</tr>
<tr>
	<td class="row2">
	{$lang['style_block_latest_news']}
	</td>
	<td class="row2">
		<select name="style_block_latest_news">
		{if {$_CONF['info_row']['style_block_latest_news']} == '1'}
			<option value="1" selected="selected">{$lang['default_design_block_latest_news']}</option>
			<option value="2">{$lang['developer_design_block_latest_news']}</option>
		{else}
			<option value="1">{$lang['default_design_block_latest_news']}</option>
			<option value="2" selected="selected">{$lang['developer_design_block_latest_news']}</option>
		{/if}
		</select>
	</td>
</tr>
<tr>
	<td class="row2">
{$lang['portal_columns']}
	</td>
	<td class="row2">
		<select name="portal_columns">
		{if {$_CONF['info_row']['portal_columns']} == '1'}
			<option value="1" selected="selected">1</option>
			<option value="2">2</option>
			<option value="3">3</option>
		{elseif {$_CONF['info_row']['portal_columns']} == '2'}
			<option value="1">1</option>
			<option value="2" selected="selected">2</option>
			<option value="3">3</option>
		{else}
			<option value="1">1</option>
			<option value="2">2</option>
			<option value="3" selected="selected">3</option>
		{/if}
		</select>
	</td>
</tr>
		</table>

<div align="center">
	<input class="submit" type="submit" value="    {$lang['Save']}     " name="submit" accesskey="s" onClick="comm._submit();" /></td>
</div>
<br />
</form>