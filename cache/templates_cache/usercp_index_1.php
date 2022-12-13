{template}address_bar_part1{/template}
<a href="index.php?page=usercp&amp;index=1">{$lang['User_Control_Panel']}</a>
<div class="btn-nav"></div>
{$lang['home']}
{template}address_bar_part2{/template}
<!-- table -->
<div style="width:98%;border-collapse: collapse;margin: auto;" class="table">
<dl>
<dt></dt>
<dd class="center_text_align usercp_left">
<div class="center_text_align">
<table class="border wd98 brd0 clpc0 a-center">
<tr class="center_text_align">
<td class="tcat" colspan="5">
{$lang['show_reputation_number']}
</td>
</tr>
<tr class="center_text_align">
<td class="thead wd2">
</td>
<td class="thead wd30">
{$lang['subject_title']}
</td>
<td class="thead wd20">
{$lang['date']}
</td>
<td class="thead wd30">
{$lang['Comments']}
</td>
<td class="thead wd10">
{$lang['By_']}
</td>
</tr>
{if !{$No_Reputation}}
{Des::while}{MemberReputation}
<tr class="border center_text_align">
<td class="row2 wd2">
<i class="fi-icon fa fa-thumbs-o-up" aria-hidden="true"></i>
</td>
<td class="row2 wd30">
<a href="index.php?page=topic&amp;show=1&amp;id={$MemberReputation['subject_id']}&amp;count={$MemberReputation['peg_count']}#{$MemberReputation['reply_id']}">
{$MemberReputation['subject_title']}
</a>
</td>
<td class="row2 wd20">
<span class="smallfont">
{$MemberReputation['reputationdate']}
</span>
</td>
<td class="row2 wd30">
{$MemberReputation['comments']}
</td>
<td class="row2 wd10">
<a href="index.php?page=profile&amp;show=1&amp;username={$MemberReputation['by_username']}">{$MemberReputation['by_username']}</a>
</td>
</tr>
{/Des::while}
{else}
<tr class="center_text_align">
<td class="row1" colspan="5">
{$lang['noReputation']}
</td>
</tr>
{/if}
</table>
</div>
<br />
<div class="center_text_align">
<table class="border wd98 brd0 clpc0 a-center">
<tr class="center_text_align">
<td class="tcat" colspan="7">
{$lang['Last_subject_that_you_type']}
</td>
</tr>
</table></div>
<div class="center_text_align">
<table class="border wd98 brd1 clpc0 a-center">
<tr>
<td class="thead center_text_align wd60" colspan="3">
{$lang['subject_title']}
</td>
<td class="thead center_text_align wd15">
{$lang['subject_writer']}
</td>
<td class="thead center_text_align wd4">
{$lang['reply_num']}
</td>
<td class="thead center_text_align wd4">
{$lang['subject_visitor']}
</td>
<td class="thead center_text_align wd15">
{$lang['write_date']}
</td>
</tr>
{if !{$No_Subjects}}
{Des::while}{subject_list}
<?php
$subject_id = $PowerBB->_CONF['template']['while']['subject_list'][$this->x_loop]['id'];
$GetReplyNumberSubject = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['reply'] . " WHERE subject_id = '$subject_id'"));
$GetlastReplySubject = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['reply'] . " WHERE subject_id = '$subject_id' ORDER BY write_time DESC");
$LastReplySubject= $PowerBB->DB->sql_fetch_array($GetlastReplySubject);
?>
<tr>		<td class="row2 center_text_align wd3">
<img alt="" src="{$image_path}/dot_folder.gif"
title="{$lang['subject_personal']}" />
</td>
<td class="row1 center_text_align wd3">
<img src="{$subject_list['icon']}" alt="" />
</td>
<td class="row2 wd35">
<a target="_blank" href="index.php?page=topic&amp;show=1&amp;id={$subject_list['id']}{$password}">
<img class="brd0" alt="{$lang['opin_subject']}"
src="{$image_path}/open.gif" /></a>
{if {$subject_list['poll_subject']} == 1}
{$lang['poll_subject']}
{/if}
<?php $PowerBB->_CONF['template']['while']['subject_list'][$this->x_loop]['title'] = str_replace('&amp;','&',$PowerBB->_CONF['template']['while']['subject_list'][$this->x_loop]['title']); ?>
<span class="title_font">
<span dir="{$_CONF['info_row']['content_dir']}">
<b>{$subject_list['prefix_subject']}</b>
</span>
<a href="index.php?page=topic&amp;show=1&amp;id={$subject_list['id']}{$password}">
{$subject_list['title']}</a>
</span>
<?php if ($GetReplyNumberSubject > $PowerBB->_CONF['template']['_CONF']['info_row']['perpage']) {  ?>
{template}forum_perpage_reply{/template}
<img src="{$image_path}/multipage.gif"
alt="{$lang['subject_multipage']}"
title="{$lang['subject_multipage']}" class="left_text_align" />
{/if}
{if {$subject_list['attach_subject']} == 1}
<img src="{$image_path}/attach.gif"
alt="{$lang['subject_attach']}"
title="{$lang['subject_attach']}" class="left_text_align" />
{/if}
{if {$subject_list['review_reply']}  > 0}
<img alt="" src="{$image_path}/moderated_small.gif"
alt="{$subject_list['review_reply']}
{$lang['Posts_was_observed']}"
title="{$subject_list['review_reply']}
{$lang['Posts_was_observed']}" class="left_text_align" />
{/if}
{if {$subject_list['rating']} > {$_CONF['info_row']['show_rating_num_max']}}
<img alt="" src="{$image_path}/rating/rating_5.gif"
title="{$lang['Rate_Topic']}
{$subject_list['rating']} " class="left_text_align" />
{/if}			{if {$subject_list['close']}}
{$lang['subject_close']}
{/if}
{if {$subject_list['delete_topic']}}
{$lang['subject_delete']}
{/if}
{if {$subject_list['special']}}
{$lang['subject_special']}
<span class="l-left">
<img class="brd0" src="{$image_path}/star.gif"
alt="{$lang['s_special']}" /> </span>
{/if}
{if {$_CONF['info_row']['subject_describe_show']} == 1}
<br />
<span class="smallfont">{$subject_list['subject_describe']}</span>
{/if}
</td>
<td class="row1 center_text_align wd15">
{if {$subject_list['writer']} == 'Guest'}
<?php echo $PowerBB->_CONF['template']['while']['subject_list'][$this->x_loop]['writer'] = str_ireplace('Guest',$PowerBB->_CONF['template']['lang']['Guest_'],$PowerBB->_CONF['template']['while']['subject_list'][$this->x_loop]['writer']); ?>
{else}
<a href="index.php?page=profile&amp;show=1&amp;username={$subject_list['writer']}">
{$subject_list['writer']}</a>
{/if}
<br />
<span class="smallfont">
{$subject_list['write_date']}
</span>
</td>
<td class="row2 center_text_align wd4">
<?php if ($GetReplyNumberSubject == '0'){ ?>
<?php echo $GetReplyNumberSubject ?>
{else}
<a onclick="window.open('index.php?page=misc&amp;whoposted=1&amp;subject_id={$subject_list['id']}','mywindow','location=1,status=1,scrollbars=1,width=155,height=300')"><u><span style="color:#000080;"><?php echo $GetReplyNumberSubject ?></span></u></a>
{/if}
</td>
<td class="row1 center_text_align wd4">
{$subject_list['visitor']}
</td>
<td class="row2 center_text_align wd15">
<?php if ($GetReplyNumberSubject <= '0'){ ?>
{$lang['no_replys']}
{else}
<?php if ($LastReplySubject['writer'] == 'Guest') {?>
<?php echo $LastReplySubject['writer'] = str_ireplace('Guest',$PowerBB->_CONF['template']['lang']['Guest_'],$LastReplySubject['writer']); ?>
{else}
<a href="index.php?page=profile&amp;show=1&amp;username=<?php echo $LastReplySubject['writer'] ?>">
<?php echo $LastReplySubject['writer'] ?></a>
{/if}
<br />
<span class="smallfont">
<?php echo $PowerBB->functions->_date($LastReplySubject['write_time']) ?>
</span>
{/if}
</td>
</tr>
{/Des::while}
{else}
<tr class="center_text_align">
<td class="row1" colspan="7">
{$lang['No_Subjects']}
</td>
</tr>
{/if}
</table>
</div>
<br />
<div class="center_text_align">
<table class="border wd98 brd0 clpc0 a-center">
<tr class="center_text_align">
<td class="tcat" colspan="6">
{$lang['Last_post_that_you_type']}
</td>
</tr>
<tr>
<td class="thead small_text wd25" colspan="2">
{$lang['Subject']} / {$lang['sort_writer']}
</td>
<td class="thead center_text_align wd15">
{$lang['write_date']}
</td>
<td class="thead center_text_align wd4">
{$lang['reply_num']}
</td>
<td class="thead center_text_align wd4">
{$lang['subject_visitor']}
</td>
<td class="thead center_text_align wd15">
{$lang['forum']}
</td>
</tr>
{Des::while}{ReplyList}
<tr>
<td class="row2 center_text_align wd3">
<img src="{$ReplyList['icon']}" alt="" />
</td>
<td class="row1 wd25">
<?php
$subject_id = $PowerBB->_CONF['template']['while']['ReplyList'][$this->x_loop]['subject_id'];
$GetSubject = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['subject'] . " WHERE id = '$subject_id'");
$Subject = $PowerBB->DB->sql_fetch_array($GetSubject);
?>
<a href="index.php?page=topic&amp;show=1&amp;id={$ReplyList['subject_id']}">
{$ReplyList['title']}
</a>
<br />
<?php echo $PowerBB->functions->GetUsernameStyleAndUserId($Subject['writer']);?>
</td>
<td class="row1 center_text_align wd15">
<?php
$section_id = $PowerBB->_CONF['template']['while']['ReplyList'][$this->x_loop]['section'];
$GetForum = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['section'] . " WHERE id = '$section_id'");
$Forum = $PowerBB->DB->sql_fetch_array($GetForum);
$Forum_title = $Forum['title'];
$Forum_id = $Forum['id'];
?>
<?php echo $PowerBB->functions->GetUsernameStyleAndUserId($Subject['last_replier']);?>
<br />
<span class="smallfont">
{$ReplyList['reply_date']}
</span>
</td>
<td class="row1 center_text_align wd4">
<?php echo $Subject['reply_number']; ?>
</td>
<td class="row1 center_text_align wd4">
<?php echo $Subject['visitor']; ?>
</td>
<td class="row1 center_text_align wd15">
<a href="index.php?page=forum&amp;show=1&amp;id={$ReplyList['section']}">
<?php echo $Forum_title; ?>
</a>
</td>
</tr>
{/Des::while}
{if {$No_posts}}
<tr>
<td class="row1 center_text_align wd97" colspan="6">
{$lang['no_post']}
</td>
</tr>
{/if}
</table>
</div>

</dd>
<dd class="usercp_right">{template}usercp_menu{/template}</dd>
</dl>
</div><!-- /table -->

<span class="pager-left">{$pager} </span>
</div>
<br />