{if {$write_subject}}
<a href="index.php?page=new_topic&amp;index=1&amp;id={$section_id}{$password}" class="button_b" id="buttons_link"
title="{$lang['add_new_topic']}">
{$lang['add_new_topic']}
</a>
{else}
<a href="index.php?page=login&amp;sign=1" class="buttons_no_link" title="{$lang['add_new_topic']}">
{$lang['add_new_topic']}
</a>
{/if}