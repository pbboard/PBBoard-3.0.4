{Des::while}{LastsPostsInfo}
<?php $PowerBB->_CONF['template']['while']['LastsPostsInfo'][$this->x_loop]['title'] = $PowerBB->Powerparse->censor_words($PowerBB->_CONF['template']['while']['LastsPostsInfo'][$this->x_loop]['title']);
$nums ='30';
$PowerBB->_CONF['template']['while']['LastsPostsInfo'][$this->x_loop]['title'] =  $PowerBB->Powerparse->_wordwrap($PowerBB->_CONF['template']['while']['LastsPostsInfo'][$this->x_loop]['title'],$nums);
?>
<span class="smallfont portal_latest_posts">
<img class="brd0" src="look/portal/images/info.gif" alt="Last Posts" />
<a target="_blank" title="{$lang['LastsPostsWriter']}
{$LastsPostsInfo['writer']}
{$lang['LastsPostsDate']}
{$LastsPostsInfo['reply_date']}
{if {$LastsPostsInfo['last_replier']}}
{$lang['LastsPostsReplyWriter']}
{$LastsPostsInfo['last_replier']}
{else}
{$lang['LastsPostsReplyWriter']}
{$LastsPostsInfo['writer']}
{/if}
{$lang['LastsPostsReply_number']}
{$LastsPostsInfo['reply_number']}
{$lang['LastsPostsvisitor']}
{$LastsPostsInfo['visitor']} " href="
index.php?page=topic&amp;show=1&amp;id={$LastsPostsInfo['id']}">
{$LastsPostsInfo['title']}
</a>
<br />
</span>
{/Des::while}
