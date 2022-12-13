<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=groups&amp;control=1&amp;main=1">{$lang['groups']}</a> &raquo;
 {$lang['Transfer_of_members_of_the_group']} :
 {$Inf['title']}</div>

<br />
<form action="index.php?page=groups&amp;move=1&amp;start=1&amp;id={$group_id}" method="post">
<table width="70%" class="t_style_b" border="0" cellspacing="1" align="center">
	<tr align="center">
		<td class="main1">
{$lang['Transfer_of_members_of_the_group']}
 : {$Inf['title']}
 {$lang['To_the_group']}
		</td>
	</tr>
	<tr align="center">
		<td class="row1">
				<select size="1" name="group" id="group_id">
				{Des::while}{GroupList}
				<option value="{$GroupList['id']}">{$GroupList['title']}</option>
				{/if}
				{/Des::while}
				</select>
				</td>

		</tr>
	<tr align="center">
		<td class="row1">
<input class="submit" type="submit" value="{$lang['acceptable']}" name="submit" accesskey="s" />
		</td>
	</tr>
</table>
</form>
