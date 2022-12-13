{Des::while}{SectionVisitor}
{if {$SectionVisitor['username']} == 'Guest'}
<span class="inline-block">
<?php echo $PowerBB->_CONF['template']['while']['SectionVisitor'][$this->x_loop]['username_style'] = str_ireplace('Guest',$PowerBB->_CONF['template']['lang']['Guest_'],$PowerBB->_CONF['template']['while']['SectionVisitor'][$this->x_loop]['username_style']); ?>،
</span>
{else}
<span class="inline-block">
<a href="index.php?page=profile&amp;show=1&amp;id={$SectionVisitor['user_id']}"
title="{$lang['time_logged_online']}
{$SectionVisitor['logged']}">
{$SectionVisitor['username_style']}</a>،
</span>
{/if}
{/Des::while}		{if {$_CONF['info_row']['show_onlineguest']} != 1}
{if {$MemberNumber} <= 0}
{$lang['no_online_section']}
{/if}
{/if}