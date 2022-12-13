{if {$STOP_MODERATOR_TEMPLATE}}
{$lang['NO_MODERATOR']}
{/if}
{Des::while}{ModeratorsList}
<a href="index.php?page=profile&amp;show=1&amp;id={$ModeratorsList['member_id']}">
{if {$ModeratorsList['username_style_cache']} !=''}
<span class="inline-block">{$ModeratorsList['username_style_cache']} ,</span>
{else}
<span class="inline-block">{$ModeratorsList['username']} ,</span>
{/if}
{/Des::while}