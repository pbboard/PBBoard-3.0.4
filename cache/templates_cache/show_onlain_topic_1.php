
<table class="border wd98 brd1 clpc0 a-center">
<tr>
<td class="thead1 right_text_align wd98">{$lang['onlain_members_topic']}
{$online_number}
({$MemberNumber}
{$lang['member_and']}
{$GuestNumber}
{$lang['Guest_']})</td>
</tr>
<tr>
<td class="row1 right_text_align wd98">
{Des::while}{SubjectVisitor}
{if {$SubjectVisitor['user_id']} == '-1'}
{if {$SubjectVisitor['is_bot']}}
{$SubjectVisitor['bot_name']}،
{else}
{$lang['Guest_']}،
{/if}
{else}
<a href="index.php?page=profile&amp;show=1&amp;username={$SubjectVisitor['username']}">{$SubjectVisitor['username_style']}</a>،
{/if}
{/Des::while}		{if {$_CONF['info_row']['show_onlineguest']} != 1}
{if {$MemberNumber} <= 0}
{$lang['No_members_are_reading_this_topic_now']}
{/if}
{/if}
</td>
</tr>
</table>