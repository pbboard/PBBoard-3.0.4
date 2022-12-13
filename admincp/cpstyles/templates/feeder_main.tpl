<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=feeder&amp;control=1&amp;main=1">{$lang['postr_rss']}</a></div>

<br />

<table width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
	<tr align="center">
		<td class="main1" colspan="4">
    {$lang['postr_rss']}
		</td>
</tr>
	<tr align="center">
		<td class="main1">
{$lang['rss_feed']}
		</td>
		<td class="main1">
{$lang['Forum_User_Name']}
		</td>
		<td class="main1">
{$lang['Last_check']}
		</td>
		<td class="main1">
{$lang['Controls']}
		</td>
	</tr>
	{Des::while}{feedersList}
	<tr align="center">
		<td class="row1">
		<a target="_blank" href="{$feedersList['rsslink']}">{$feedersList['title2']}</a>
		</td>
		<td class="row1">
<?php
      	$forumid = $PowerBB->_CONF['template']['while']['feedersList'][$this->x_loop]['forumid'];
        $GetForum = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['section'] . " WHERE id = '$forumid'");
        $Forum = $PowerBB->DB->sql_fetch_array($GetForum);
        $forumname = $Forum['title'];
?>
<a target="_blank" href="../index.php?page=forum&amp;show=1&amp;id={$feedersList['forumid']}">
<?php echo $forumname; ?>
</a>
<br />
<?php

      	$userid = $PowerBB->_CONF['template']['while']['feedersList'][$this->x_loop]['userid'];
        $GetUser = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['member'] . " WHERE id = '$userid'");
        $User = $PowerBB->DB->sql_fetch_array($GetUser);
        $username = $User['username'];
?>
<a target="_blank" href="../index.php?page=profile&amp;show=1&amp;id={$feedersList['userid']}">
<?php echo $username; ?>
</a>
		</td>
		<td class="row1">
		{$feedersList['feeds_time']}
		</td>
		<td class="row1">
			<a href="index.php?page=feeder&amp;edit=1&amp;main=1&amp;id={$feedersList['id']}">{$lang['edit']}</a>
			| <a href="index.php?page=feeder&amp;runfeed=1&amp;start=1&amp;id={$feedersList['id']}">{$lang['Bring_feed']}</a>
			| <a href="index.php?page=feeder&amp;delet=1&amp;start=1&amp;id={$feedersList['id']}">{$lang['Delet']}</a>

		</td>
	</tr>
	{/Des::while}
</table>
