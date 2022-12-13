<span class="smallfont  portal_main_menu">
<img class="brd0" src="look/portal/images/news_icon.gif" alt="" />
<a href="index.php?page=portal">{$lang['home']}</a><br />
<!-- action_find_addons_2 -->
<img class="brd0" src="look/portal/images/news_icon.gif" alt="" />
<a href="index.php">{$lang['forum']}</a><br />
<img class="brd0" src="look/portal/images/news_icon.gif" alt="" />
<a href="index.php?page=misc&amp;rules=1&amp;show=1">{$lang['rules']}</a><br />
{if {$_CONF['member_permission']}}
<img class="brd0" src="look/portal/images/news_icon.gif" alt="" />
<a href="index.php?page=usercp&amp;index=1">{$lang['usercp']} </a><br />
{else}
<img class="brd0" src="look/portal/images/news_icon.gif" alt="" />
<a href="index.php?page=register&amp;index=1">{$lang['register']}</a><br />
{/if}
{if {$_CONF['info_row']['active_static']} == '1'}
<img class="brd0" src="look/portal/images/news_icon.gif" alt="" />
<a href="index.php?page=static&amp;index=1">{$lang['static']}</a><br />
{/if}
{if {$_CONF['info_row']['active_calendar']} == '1'}
<img class="brd0" src="look/portal/images/news_icon.gif" alt="" />
<a href="index.php?page=calendar&amp;show=1">{$lang['Calendar']}</a><br />
{/if}
{if {$_CONF['member_permission']}}
{else}
<img class="brd0" src="look/portal/images/news_icon.gif" alt="" />
<a href="index.php?page=latest_reply&amp;today=1">
{$lang['latest_reply']}
</a> <br />
{/if}      {if {$_CONF['member_permission']}}
{if {$_CONF['group_info']['memberlist_allow']} == '1'}
<img class="brd0" src="look/portal/images/news_icon.gif" alt="" />
<a href="index.php?page=member_list&amp;index=1&amp;show=1">{$lang['memberlist']}</a><br />
{/if}       <img class="brd0" src="look/portal/images/news_icon.gif" alt="" />
<a href="javascript:logout('index.php?page=logout&amp;index=1')">{$lang['logout']}</a><br />
{/if}
{if {$_CONF['info_row']['active_send_admin_message']} == '1'}
<img class="brd0" src="look/portal/images/news_icon.gif" alt="" />
<a href="index.php?page=send&amp;sendmessage=1">{$lang['send_message']}</a><br />
{/if}
{if {$_CONF['rows']['group_info']['admincp_allow']}}
<img class="brd0" src="look/portal/images/news_icon.gif" alt="" />
<a href="{$admincpdir}">
{$lang['cp_admin']}</a>
{/if}
<!-- action_find_addons_3 -->
</span>