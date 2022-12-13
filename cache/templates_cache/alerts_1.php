      <!-- Code alerts Menu start -->
<div id="alerts-content">
<div class="thead">{$lang['alerts']}</div>
<div class="menu_popup">
<!--Menu_1-->
<a href="index.php?page=pm_list&amp;list=1&amp;folder=inbox">
{if {$pm_num} > 0}
<b>{$lang['Private_messages_unread']}</b>
{else}
{$lang['Private_messages_unread']}
{/if}
:
{if {$pm_num} > 0}<b>
{$pm_num}
</b>
{else}
{$pm_num}
{/if}
</a></div>
<div class="menu_popup">
<a href="index.php?page=profile&amp;show=1&amp;id={$_CONF['member_row']['id']}">
{if {$visitor_message_Numrs} > 0}
<b>
{$lang['visitor_messages_unread']}
</b>
{else}
{$lang['visitor_messages_unread']}
{/if}
:
{if {$visitor_message_Numrs} > 0}
<b>
{$visitor_message_Numrs}
</b>
{else}
{$visitor_message_Numrs}
{/if}
</a>
</div>
<div class="menu_popup">
<a href="index.php?page=usercp&amp;options=1&amp;friends=1&amp;main=1">
{if {$friends_num} > 0}
<b>
{$lang['Friend_requests_awaiting_approval']}
</b>
{else}
{$lang['Friend_requests_awaiting_approval']}
{/if}
:
{if {$friends_num} > 0}
<b>
{$friends_num}
</b>
{else}
{$friends_num}
{/if}
</a>
</div>
<!--Menu_2-->
<div class="menu_popup">
<a href="index.php?page=usercp&amp;options=1&amp;mention=1&amp;main=1">
{if {$mention_num} > 0}
<b>
{$lang['mention']}
</b>
{else}
{$lang['mention']}
{/if}
:
{if {$mention_num} > 0}
<b>
{$mention_num}
</b>
{else}
{$mention_num}
{/if}
</a>
</div>
<!--Menu_4-->
<div class="menu_popup">
<a href="index.php?page=usercp&amp;options=1&amp;reputation=1&amp;main=1">
{if {$ReputationNum} > 0}
<b>
{$lang['reputation_unread']}
</b>
{else}
{$lang['reputation_unread']}
{/if}
:
{if {$ReputationNum} > 0}
<b>
{$ReputationNum}
</b>
{else}
0
{/if}
</a>
</div>
<!--Menu_3-->
</div>
<!-- Code alerts Menu End -->