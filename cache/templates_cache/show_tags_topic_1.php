<table class="border wd98 brd1 clpc0 a-center">
<tr>
<td class="thead1 right_text_align wd98">
<div class="r-right">{$lang['tags']}</div>
<?php
 if(!$PowerBB->_CONF['template']['mod_toolbar']
 or $PowerBB->_CONF['member_row']['username'] == $PowerBB->_CONF['template']['SubjectInfo']['writer'])
 {
?>
<div class="l-left">
<a href="index.php?page=tags&amp;edit=1&amp;id={$subject_id}">
{$lang['edit_tags']}</a>
</div>
{/if}
</td>
</tr>
<tr>
<td class="row1 right_text_align wd98">
{if {$STOP_TAGS_TEMPLATE}}
{$lang['no_tags']}
{else}
{Des::while}{tags}
<a href="index.php?page=tags&amp;show=1&amp;id={$tags['id']}">{$tags['tag']}</a> ،
{/Des::while}
{/if}
</td>
</tr>
</table>
