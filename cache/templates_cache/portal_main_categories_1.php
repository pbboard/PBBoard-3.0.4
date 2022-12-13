{Des::foreach}{forumsy_list}{forumy}
{if {$forumy['parent']} == 0}
<span class="portal_main_categories smallfont">
<img class="brd0" src="look/portal/images/news_icon.gif" alt="" />
<a href="index.php?page=forum&amp;show=1&amp;id={$forumy['id']}">
{$forumy['title']}</a>
   <br />
</span>
{/if}
{/Des::foreach}