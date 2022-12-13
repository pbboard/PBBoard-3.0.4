 {template}address_bar_part1{/template}
{$lang['latest_reply']}
{template}address_bar_part2{/template}
<table class="border wd98 brd1 clpc0 a-center">
<tr>
<td class="tcat small_text wd40" colspan="2">
{$lang['Subject']} / {$lang['sort_writer']}
</td>
<td class="tcat center_text_align wd15">
{$lang['write_date']}
</td>
<td class="tcat center_text_align resp-susp wd4">
{$lang['reply_num']}
</td>
<td class="tcat center_text_align resp-susp wd4">
{$lang['subject_visitor']}
</td>
<td class="tcat center_text_align resp-susp wd15">
{$lang['forum']}
</td>
</tr>
{if {$reply_today_nm} == 0}
</table>
<table class="border wd98 brd1 clpc0 a-center">
<tr>
<td class="row1 center_text_align wd97" colspan="6">
{$lang['no_replies_today']}
</td>
</tr>
{else}
{Des::while}{LastSubject}
<tr>
<td class="row2 center_text_align wd3">
<img src="{$LastSubject['icon']}" alt="" />
</td>
<td class="row1 wd40">
<?php $PowerBB->_CONF['template']['while']['LastSubject'][$this->x_loop]['title'] = $PowerBB->functions->CleanVariable($PowerBB->_CONF['template']['while']['LastSubject'][$this->x_loop]['title'],'html'); ?>
<?php $PowerBB->_CONF['template']['while']['LastSubject'][$this->x_loop]['title'] = $PowerBB->Powerparse->censor_words($PowerBB->_CONF['template']['while']['LastSubject'][$this->x_loop]['title']); ?>			<a href="index.php?page=topic&amp;show=1&amp;id={$LastSubject['subject_id']}{$password}{$LastSubject['id']}">
{$LastSubject['title']}
</a>
<br />
<?php echo $PowerBB->functions->GetUsernameStyleAndUserId($PowerBB->_CONF['template']['while']['LastSubject'][$this->x_loop]['writer']);?>
</td>
<td class="row1 center_text_align wd15">
<a href="index.php?page=profile&amp;show=1&amp;username={$LastSubject['last_replier']}">
{if {$LastSubject['last_replier']}}
<?php echo $PowerBB->functions->GetUsernameStyleAndUserId($PowerBB->_CONF['template']['while']['LastSubject'][$this->x_loop]['last_replier']);?>
{else}
<?php echo $PowerBB->functions->GetUsernameStyleAndUserId($PowerBB->_CONF['template']['while']['LastSubject'][$this->x_loop]['writer']);?>
{/if}<br />
<span class="smallfont">
{$LastSubject['reply_date']}
</span>
</td>
<td class="row1 center_text_align resp-susp wd4">
{$LastSubject['reply_number']}
</td>
<td class="row1 center_text_align resp-susp wd4">
{$LastSubject['visitor']}
</td>
<td class="row1 center_text_align resp-susp wd15">
<?php      	$section_id = $PowerBB->_CONF['template']['while']['LastSubject'][$this->x_loop]['section'];
$GetForum = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['section'] . " WHERE id = '$section_id'");
$Forum = $PowerBB->DB->sql_fetch_array($GetForum);
$Forum_title = $Forum['title'];
$Forum_id = $Forum['id'];
?>
<a href="index.php?page=forum&amp;show=1&amp;id=<?php echo $Forum_id; ?>"><span class="smallfont"><?php echo $Forum_title; ?></span></a>
</td>
</tr>
{/Des::while}
</table>
<table style="border-collapse: collapse" class="center_text_align wd98 brd0">
<tr>
<td class="center_text_align">
{$pagerLastSubject}
</td>
</tr>
{/if}
</table>
<br />
{template}jump_forums_list{/template}
<br />
<br />
<br />