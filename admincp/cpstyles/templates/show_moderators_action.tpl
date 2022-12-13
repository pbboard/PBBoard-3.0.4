<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=moderators&amp;modaction=1&amp;main=1">{$lang['shwo_modaction']}</a></div>

<br />
<form action="index.php?page=moderators&amp;&amp;modaction=1&amp;del_all=1" method="post">
<table width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
<tr valign="top">
	<td class="main1">{$lang['Delete_all_the_processes']}</td>
</tr>
	<tr align="center">
		<td class="main2">
			<input type="submit" class="button" value="{$lang['Delete_all_the_processes']}" name="submit1" />
		</td>
	</tr>
</table>
</form>
<br />
<table width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
<tr valign="top">
	<td class="main1" colspan="5"> {$lang['The_number_of_operations']} :
	({$pagerNumber})</td>
</tr>
	<tr align="center">
		<td class="main2">
      {$lang['Number_ID']}
		</td>
		<td class="main2">
		{$lang['mem_name']}
		</td>
		<td class="main2">
		{$lang['Date']}
		</td>
		<td class="main2">
		{$lang['Implementation']}
		</td>
		<td class="main2">
		{$lang['subject']}
		</td>
	</tr>
	{Des::while}{ActionList}
	<tr align="center">
		<td class="row1">
       {$ActionList['id']}
		</td>
		<td class="row1">
		<a href="../index.php?page=profile&amp;show=1&amp;username={$ActionList['username']}" target="_blank">{$ActionList['username']}</a>
		</td>
		<td class="row1">
			{$ActionList['edit_date']}
		</td>
		<td class="row1">
			{$ActionList['edit_action']}
		</td>
		<td class="row1">
			<a target="_blank" href="../index.php?page=topic&amp;show=1&amp;id={$ActionList['subject_id']}">{$ActionList['subject_title']}</a>
		</td>
	</tr>
	{/Des::while}
</table>
<span class="pager-left">{$pager} </span>