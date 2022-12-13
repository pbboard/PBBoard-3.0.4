<div id="primary_nav">
<ul class="y_nav r-right">
<li class="{$main_page}">
<a href="index.php" title="Forum">{$lang['forum']}</a></li>
<!-- actikon_find_addokns_1 -->
{if {$_CONF['info_row']['active_portal']} == '1'}
<li class="{$portal_page}">
<a href="index.php?page=portal" title="{$lang['portal']}">
{$_CONF['info_row']['title_portal']}</a></li>
{/if}
<li class="{$latest_reply_page}">
<a href="index.php?page=latest_reply&amp;today=1">{$lang['whatis_new']}</a></li>

<!-- acation_fidnd_adwdons_2 -->
{get_hook}main_bar_1{/get_hook}
</ul>
<!-- actikon_find_addokns_5 -->
</div>
<div id="subnavigation">
<div class="l-left">
<!-- actiown_finwd_addons_6 -->
</div>
<ul>
<!-- action_find_addosans_1 -->
{if {$_CONF['info_row']['activate_chat_bar']}}
<li>
<a target="_blank" href="index.php?page=chat_message&amp;chatout=1" title="{$lang['chat_message']}">
<i class="fa fa-commenting" aria-hidden="true"></i> {$lang['chat_message']}</a>
</li>
{/if}

{if {$_CONF['group_info']['memberlist_allow']} == '1'}
<li class="{$member_list_page}">
<a href="index.php?page=member_list&amp;index=1&amp;show=1" title="{$lang['memberlist']}">
<i class="fa fa-users" aria-hidden="true"></i> {$lang['memberlist']}</a></li>
{/if}
{if {$_CONF['member_permission']}}
<li id="quick_options"><a id="usercptools-trigger" href="#" title="{$lang['quick_options']}">
<i class="fa fa-bolt" aria-hidden="true"></i> {$lang['quick_options']}
<span class="arrow_y">&#9660</span></a>{template}usercptools{/template}</li>
{/if}
{if !{$No_PagesList}}
<li id="quick_pages"><a id="pages-trigger" href="#" title="{$lang['pages']}">
<i class="fa fa-clone" aria-hidden="true"></i> {$lang['pages']}
<span class="arrow_y">&#9660</span></a>{template}pages{/template}
</li>
{/if}
{if {$_CONF['member_permission']}}
<li class="{$usercp_page}">
<a href="index.php?page=usercp&amp;index=1" title="{$lang['usercp']}"><i class="fa fa-cogs" aria-hidden="true"></i> {$lang['usercp']}</a>
</li>
{/if}
<!-- actioddn_find_addons_4 -->
{get_hook}main_bar_2{/get_hook}
</ul>
</div>
<div class="body_wrapper"></div>
<!-- action_find_adasdons_3 -->
<div class="pbb_content">
<div class="pbboard_body">
<div class="pbb_main">
{template}info_bar{/template}