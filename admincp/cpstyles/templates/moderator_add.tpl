
<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=moderators&amp;control=1&amp;main=1">{$lang['moderators']}</a> &raquo;
{$lang['Add_new_moderator']}</div>

<br />

<form action="index.php?page=moderators&amp;add=1&amp;start=1" method="post">

	<table width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
		<tr align="center">
			<td class="main1" colspan="2">
			{$lang['Add_new_moderator']}
			</td>
		</tr>
		<tr>
			<td class="row1">
			{$lang['username']}
			</td>
			<td class="row1">
				<input type="text" name="username" value="" size="30" />
			</td>
		</tr>
		<tr>
			<td class="row2">
			{$lang['moderator_On_Forum']}
			</td>
			<td class="row2">
					{$DoJumpList}
			</td>
		</tr>
		<tr>
			<td class="row2">
			{$lang['moderator_Group']}
			</td>
			<td class="row2">
				<select size="1" name="group" id="group_id">
				{Des::while}{GroupList}
              {if {$GroupList['id']} != '1'}
				<option value="{$GroupList['id']}">{$GroupList['title']}</option>
				{/if}
				{/Des::while}
				</select>
			</td>
		</tr>
		<tr>
			<td class="row1">
			{$lang['moderator_usertitle']}
			</td>
			<td class="row1">
				<input type="text" name="usertitle" value="" size="30" />
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
