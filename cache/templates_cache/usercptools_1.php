      <!-- Code usercptools Menu start -->
<div id="usercptools-content">
<div class="thead">{$lang['quick_options']}</div>

{if {$_CONF['info_row']['active_reply_today']} == '1'}
<div class='menu_popup'>
<a href="index.php?page=latest_reply&amp;today=1">
{$lang['latest_reply']}</a>
</div>
{/if}
{if {$_CONF['info_row']['active_subject_today']} == '1'}
<div class='menu_popup'><a href="index.php?page=latest&amp;today=1">
{$lang['subject_today']}</a></div>
{/if}
{if {$_CONF['info_row']['active_calendar']} == '1'}
<div class='menu_popup'>
<a href="index.php?page=calendar&amp;show=1" title="{$lang['Calendar']}">
{$lang['Calendar']}</a>
</div>
{/if}
{if {$_CONF['info_row']['active_static']} == '1'}
<div class='menu_popup'>
<a href="index.php?page=static&amp;index=1" title="{$lang['static']}">
{$lang['static']}</a>
</div>
{/if}
<div class='menu_popup'>
<a href="index.php?page=misc&amp;rules=1&amp;show=1" title="{$lang['rules']}">
{$lang['rules']}</a>
</div>

{if {$subject_special_nm} > '0'}
<div class='menu_popup'>
<a href="index.php?page=special_subject&amp;index=1" title="{$lang['Special_Subject']}">
{$lang['Special_Subject']}</a>
</div>
{/if}


<div class="thead">{$lang['User_Control_Panel']}</div>
{if {$_CONF['member_permission']}}
<div class='menu_popup'>
<a href="index.php?page=usercp&amp;index=1" title="{$lang['usercp']}">
{$lang['usercp']}</a>
</div>
<div class='menu_popup'>
<a href="index.php?page=profile&amp;show=1&amp;username={$_CONF['member_row']['username']}">
{$lang['Your_profile']}</a>
</div>
{/if}

<div class='menu_popup'><a href="index.php?page=usercp&amp;control=1&amp;sign=1&amp;main=1">
{$lang['Change_Signature']}</a></div>
<div class='menu_popup'><a href="index.php?page=usercp&amp;control=1&amp;avatar=1&amp;main=1">
{$lang['Change_avatar']}</a></div>
<div class='menu_popup'><a href="index.php?page=usercp&amp;control=1&amp;info=1&amp;main=1">
{$lang['Your_Personal_Information']}</a></div>
<div class='menu_popup'><a href="index.php?page=usercp&amp;options=1&amp;subject=1&amp;main=1">
{$lang['Your_Subjects']}</a></div>
<div class="thead">{$lang['Other_options']}</div>
{if {$_CONF['info_row']['pm_feature']}}
{if {$_CONF['rows']['group_info']['use_pm']} == 1}
<div class='menu_popup'>
<a href="index.php?page=pm_list&amp;list=1&amp;folder=inbox">
{$lang['Private_Messages']}</a>
</div>
{/if}
{/if}
{if {$_CONF['group_info']['onlinepage_allow']} == '1'}
<div class='menu_popup'><a href="index.php?page=online&amp;show=1">
{$lang['Whos_Online']}</a>
</div>
{/if}

{if {$_CONF['info_row']['active_send_admin_message']} == '1'}
<div class='menu_popup'><a href="index.php?page=send&amp;sendmessage=1">
{$lang['send_message']}</a>
</div>
{/if}

</div>

<!-- Code usercptools Menu End -->