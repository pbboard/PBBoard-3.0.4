<span class="portal_online smallfont">
</strong> ({$MemberNumber})
{$lang['member_and']}
({$SpidersNumber})
{$lang['Spiders_and']}
({$GuestNumber})
{$lang['Guest']}
<br />
<hr>
</span>
<?php $x = 1; ?>
{Des::while}{OnlineList}
{if {$OnlineList['user_id']} == '-1'}
{if {$OnlineList['is_bot']}}
{$OnlineList['bot_name']} ,
{else}
<?php
echo $PowerBB->_CONF['template']['while']['OnlineList'][$this->x_loop]['username_style'] = str_ireplace('Guest',$PowerBB->_CONF['template']['lang']['Guest_'],$PowerBB->_CONF['template']['while']['OnlineList'][$this->x_loop]['username_style']).$x;
$x += 1;
?> ,
{/if}
{else}
{if {$OnlineList['username_style']} != 'Guest'}
<a href="index.php?page=profile&amp;show=1&amp;id={$OnlineList['user_id']}"
title="{$lang['time_logged_online']}{$OnlineList['logged']}">{$OnlineList['username_style']}</a>،
{/if}
{/if}
{/Des::while}
{if {$_CONF['info_row']['show_onlineguest']} == 1}
{if {$MemberNumber} + {$GuestNumber} <= 0}
{if !{$_CONF['rows']['member_row']['hide_online']} == 1}
{$lang['no_login_member']}
{/if}
{/if}
{else}
{if {$MemberNumber} <= 0}
{if !{$_CONF['rows']['member_row']['hide_online']} == 1}
{$lang['no_login_member']}
{/if}
{/if}
{/if}
</span>