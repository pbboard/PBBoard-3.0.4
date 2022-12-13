{if {$write_reply}}
<a href="index.php?page=new_reply&amp;index=1&amp;id={$subject_id}{$password}" class="button_b" id="buttons_link"
title="{$lang['add_new_reply']}">
{$lang['add_new_reply']}
</a>
{else}
<a href="index.php?page=login&amp;sign=1" class="buttons_no_link" title="{$lang['add_new_reply']}">
{$lang['add_new_reply']}
</a>
{/if}