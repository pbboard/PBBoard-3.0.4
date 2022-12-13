<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=moderators&amp;control=1&amp;main=1">{$lang['moderators']}</a> &raquo;
<a href="index.php?page=moderators&amp;control=1&amp;section=1&amp;id={$Section['id']}">{$Section['title']}</a> &raquo;
 {$lang['edit']} :
 {$Inf['username']}</div>

<br />

<form action="index.php?page=moderators&amp;edit=1&amp;start=1&amp;id={$Inf['id']}" method="post">

	<table width="60%" class="t_style_b" border="0" cellspacing="1" align="center">
		<tr align="center">
			<td class="main1" colspan="2">
			 {$lang['edit']} :
 {$Inf['username']}
			</td>
		</tr>
		<tr>
			<td class="row1">
			{$lang['username']}
			</td>
			<td class="row1">
				<input type="text" name="username" readonly="readonly" value="{$Inf['username']}" size="30" />
			</td>
		</tr>
		<tr>
			<td class="row2">
{$lang['moderator_On_Forum']
			</td>
			<td class="row2">
				<select name="section" id="section_id">
        		{Des::foreach}{forums_list}{forum}
        		{if {$forum['parent']} == 0}
				<option value="{$forum['id']}">- {$forum['title']}</option>
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
		<tr>
			<td class="row2">
			{$lang['moderator_Group']}
			</td>
			<td class="row2">
				<select size="1" name="group" id="group_id">
				{Des::while}{GroupList}
				<option value="{$GroupList['id']}">{$GroupList['title']}</option>
				{/Des::while}
				</select>
			</td>
		</tr>
	</table>

	<br />

	
		<tr>
			<td colspan="2" align="center">
				<input type="submit" value="{$lang['acceptable']}" name="submit" />
			</td>
		</tr>
	</table>
{template}forums_am{/template}
	<br />

</form>
