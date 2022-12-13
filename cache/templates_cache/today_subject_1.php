{template}address_bar_part1{/template}
{$lang['subject_today']}
{template}address_bar_part2{/template}
<table class="border wd98 brd1 clpc0 a-center">
<tr>
<td class="tcat center_text_align wd30" colspan="2">
{$lang['subject_title']}
</td>
<td class="tcat center_text_align wd20">
{$lang['subject_writer']}
</td>
<td class="tcat center_text_align resp-susp wd4">
{$lang['reply_num']}
</td>
<td class="tcat center_text_align resp-susp wd4">
{$lang['subject_visitor']}
</td>
<td class="tcat center_text_align resp-susp wd15">
{$lang['write_date']}
</td>
<td class="tcat center_text_align resp-susp wd25">
{$lang['forum']}
</td>
</tr>
{if {$subject_today_nm} == 0}
</table>
<table class="border wd98 brd1 clpc0 a-center">
<tr>
<td class="row1 center_text_align wd97" colspan="7">
{$lang['no_subjects_today']}
</td>
</tr>
{else}
{Des::while}{subject_list}
<tr>
<td class="row1 center_text_align wd3">
<img src="{$subject_list['icon']}" alt="" />
</td>
<td class="row2 wd30">
<?php $PowerBB->_CONF['template']['while']['subject_list'][$this->x_loop]['title'] = $PowerBB->functions->CleanVariable($PowerBB->_CONF['template']['while']['subject_list'][$this->x_loop]['title'],'html'); ?>
<?php $PowerBB->_CONF['template']['while']['subject_list'][$this->x_loop]['title'] = $PowerBB->Powerparse->censor_words($PowerBB->_CONF['template']['while']['subject_list'][$this->x_loop]['title']); ?>
<a href="index.php?page=topic&amp;show=1&amp;id={$subject_list['id']}{$password}">
{$subject_list['title']}
</a>
{if {$subject_list['close']}}
{$lang['subject_close']}
{/if}
<br />
<span class="small">{$subject_list['subject_describe']}</span>
</td>
<td class="row1 center_text_align wd20">
<?php echo $PowerBB->functions->GetUsernameStyleAndUserId($PowerBB->_CONF['template']['while']['subject_list'][$this->x_loop]['writer']);?>
<br />
<span class="smallfont">
{$subject_list['write_date']}
</span>
</td>
<td class="row2 center_text_align resp-susp wd4">
{$subject_list['reply_number']}
</td>
<td class="row1 center_text_align resp-susp wd4">
{$subject_list['visitor']}
</td>
<td class="row2 center_text_align resp-susp wd15">
{if {$subject_list['reply_number']} <= 0}
{$lang['no_replys']}
{else}
{if {$subject_list['last_replier']} == ''}
{$lang['Guest_']}
{else}
<?php echo $PowerBB->functions->GetUsernameStyleAndUserId($PowerBB->_CONF['template']['while']['subject_list'][$this->x_loop]['last_replier']);?>
{/if}
<br />
<span class="smallfont">
{$subject_list['reply_date']}
</span>
{/if}
</td>
<td class="row1 center_text_align resp-susp wd25">
<?php      	$section_id = $PowerBB->_CONF['template']['while']['subject_list'][$this->x_loop]['section'];
$GetForum = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['section'] . " WHERE id = '$section_id'");
$Forum = $PowerBB->DB->sql_fetch_array($GetForum);
$Forum_title = $Forum['title'];
$Forum_id = $Forum['id'];
?>
<a href="index.php?page=forum&amp;show=1&amp;id=<?php echo $Forum_id; ?>"><?php echo $Forum_title; ?></a>
</td>
</tr>
{/Des::while}
{/if}&nbsp;
</table>
<table class="wd95 brd1 clpc0" style="border-collapse: collapse">
<tr>
<td class="left_text_align">{$pager}</td>
</tr>
</table>
<br />
{template}jump_forums_list{/template}
<br />
<br />
<br />