{Des::while}{AnnouncementList}
{if {$AnnouncementList['id']} !=''}
<tr>
<td class="row1 wd2 a-center forum_sub_dotrev">
<img alt="" src="{$image_path}/announcement.gif" class="brd0"
title="{$lang['announcement']}" />
</td>
<td class="row2 wd2 a-center forum_sub_icon">
<img src="look/images/icons/i1.gif" alt="" />
</td>
<td class="row1" style="width: 350px;">
{$lang['Announcementy']}
<a href="index.php?page=announcement&amp;show=1&amp;id={$AnnouncementList['id']}">
{$AnnouncementList['title']}</a>
<br />
<a href="index.php?page=profile&amp;show=1&amp;username={$AnnouncementList['writer']}">
{$AnnouncementList['writer']}
</a>
<br />
<span class="smallfont">
{$AnnouncementList['date']}
</span>
</td>
<td class="row2 forum_sub_rep wd4 a-center">
0
</td>
<td class="row1 forum_sub_vis wd4 a-center">
{$AnnouncementList['visitor']}
</td>
<td class="row2 forum_sub_lasts wd20 a-center">
{$lang['no_replys']}
</td>
{if !{$mod_toolbar}}
<td class="row1 wd1 a-center">
<input type="checkbox" name="che" value="" />
</td>
{/if}
</tr>
{/if}
{/Des::while}