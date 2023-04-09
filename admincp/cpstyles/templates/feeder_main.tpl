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
		<td class="row1 Disable">
{if {$feedersList['options']} == '0'}
<?php
$PowerBB->_CONF['template']['while']['feedersList'][$this->x_loop]['title2'] = "<s>".$PowerBB->_CONF['template']['while']['feedersList'][$this->x_loop]['title2']."</s>";
?>
{/if}
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
		<td class="row2">
		{$feedersList['feeds_time']}
		 <br />
<?php
$PowerBB->_CONF['template']['_CONF']['lang']['Check_the_feed_of_all'] = str_replace("...","",$PowerBB->_CONF['template']['_CONF']['lang']['Check_the_feed_of_all']);
$PowerBB->_CONF['template']['_CONF']['lang']['Check_the_feed_of_all'] = str_replace("اف","ف",$PowerBB->_CONF['template']['_CONF']['lang']['Check_the_feed_of_all']);
?>
		{$lang['Check_the_feed_of_all']}:
{if {$feedersList['ttl']} == 600}
10 {$lang['minutes']}
{elseif {$feedersList['ttl']} == 1200}
20 {$lang['minutes']}
{elseif {$feedersList['ttl']} == 1800}
30 {$lang['minutes']}
{elseif {$feedersList['ttl']} == 3600}
60 {$lang['minutes']}
{elseif {$feedersList['ttl']} == 7200}
2 {$lang['hours']}
{elseif {$feedersList['ttl']} == 14400}
4 {$lang['hours']}
{elseif {$feedersList['ttl']} == 21600}
6 {$lang['hours']}
{elseif {$feedersList['ttl']} == 28800}
8 {$lang['hours']}
{elseif {$feedersList['ttl']} == 36000}
10 {$lang['hours']}
{elseif {$feedersList['ttl']} == 43200}
12 {$lang['hours']}
{elseif {$feedersList['ttl']} == 86400}
24 {$lang['hours']}
{elseif {$feedersList['ttl']} == 172800}
48 {$lang['hours']}
{elseif {$feedersList['ttl']} == 259200}
72 {$lang['hours']}
{elseif {$feedersList['ttl']} == 604800}
7 {$lang['Day']}
{/if}

		</td>
		<td class="row2">
			<a href="index.php?page=feeder&amp;edit=1&amp;main=1&amp;id={$feedersList['id']}">{$lang['edit']}</a>
			{if {$feedersList['options']}}
			| <a href="index.php?page=feeder&amp;ective_feeds=1&amp;options=0&amp;id={$feedersList['id']}">{$lang['Disable']}</a>
			{else}
			| <a href="index.php?page=feeder&amp;ective_feeds=1&amp;options=1&amp;id={$feedersList['id']}"><b>{$lang['active']}</b></a>
			{/if}
			| <a href="index.php?page=feeder&amp;runfeed=1&amp;start=1&amp;id={$feedersList['id']}">{$lang['Bring_feed']}</a>
			| <a href="index.php?page=feeder&amp;delet=1&amp;start=1&amp;id={$feedersList['id']}">{$lang['Delet']}</a>

		</td>
	</tr>

	{/Des::while}
</table>
