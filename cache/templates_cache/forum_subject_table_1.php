<script type='text/javascript'>
function AjaxTitle()
{
var data = {};
var m_subject_id =	$("#m_subject_id").val();

var status_r_ =$("#status_r_"+m_subject_id+"");
var AjaxState = {ajaxSend : status_r_.html("<img alt='' class='brd0' src='{$image_path}/loading_smll.gif'>")};

data['title']	=	$("#title_id").val();
data['m_subject']		=	$("#m_subject_id").val();
data['ajax']	=	true;

$.post("index.php?page=ajax&subjects=1&rename=1",data,function Success(xml) { status_r_.html(xml); $("#title_" + $("#m_subject_id").val()).html(xml); });

}

function rename_title(valu,title)
{
if (valu){
$.cookie("PbbSubject_id", valu, { expires: 1 });
}
$("#status_rename_"+valu+"").show("slow");
$("#status_rename_"+valu+"").html('<br /><input type="text" name="title" id="title_id" value="' + title + '" size="30"/><input type="hidden" name="m_subject" id="m_subject_id" value="' + valu + '" /><input type="button" name="button" class="input_button" value="{$lang['Adopted']}" /><input type="button" name="button" class="input_cancel" value="{$lang['Cancel']}" />');
$(".input_button").click(AjaxTitle);
$(".input_button").click(function()
{
$("#status_rename_"+valu+"").hide("slow");
$.cookie("PbbSubject_id", '', { expires: 0 });
});
$(".input_cancel").click(function()
{
$("#status_rename_"+valu+"").hide("slow");
$.cookie("PbbSubject_id", '', { expires: 0 });
});
}
</script>

<script type='text/javascript'>
function checkAll(form){
for (var i = 0; i < form.elements.length; i++){
eval("form.elements[" + i + "].checked = form.elements[0].checked");
}
}
</script>
<div class="rRow">
<div class="r-right">
{template}add_subject_link{/template}
</div>
<div class="l-left morwidth">
{if {$pager}}
<?php echo $PowerBB->pager->show();?>
{/if}
</div>
</div>
<!-- Code search Menu start -->
{if {$_CONF['group_info']['search_allow']} == '1'}
<div id="searchContainer">
<form name="search" action="index.php?page=forum" method="get">
<input type="hidden" name="page" value="forum" />
<input type="hidden" name="start" value="1"/>
<input type="hidden" name="search_only" value="1" />
<input type="hidden" name="sort_order" value="desc" />
<input type="hidden" name="section" value="{$SECTION_ID}" />
<ul>
<li><input type="submit" name="submit" class="submit-id" title="{$lang['start_search']}" /></li>
<li><input type="text" name="keyword" id="field" value="{$lang['Search_Forums']}" onfocus="if (this.value == '{$lang['Search_Forums']}') this.value = '';"
dir="{$_CONF['info_row']['content_dir']}" /></li>
<li><input title="{$lang['advanced_search']}" type="button" id="advanced_search" onclick="window.open('index.php?page=search&amp;index=1','mywindow','')" value="{$lang['advanced_search']}" /></li>
</ul>
</form>
</div>
{/if}
<!-- Code search Menu End -->
<br />
<br />
<br />
<br />
<br />
<form name="tools_thread" method="post" enctype="multipart/form-data" action="index.php?page=management&amp;subject=1&amp;section={$SECTION_ID}&amp;operator=tools_thread">
<table class="border forum_sub wd98 brd1 clpc0 a-center">
{if {$NO_SUBJECTS}}
<tr>
<td class="thead a-center" colspan="2" style="width: 350px;">
<span class="l-left"><a href="index.php?page=forum&amp;show=1&amp;orderby=1&amp;id={$SECTION_ID}&amp;sort=rating"
title="{$lang['sort']}" rel="nofollow">
{$lang['sort_rating']}
{if {$sort_rating}}
&nbsp;<i title="{$lang['progressive']}" class="fa fa-sort"></i>
{/if}
</a>
</span>
<span class="r-right">
<a href="index.php?page=forum&amp;show=1&amp;orderby=1&amp;id={$SECTION_ID}&amp;sort=asc"
title="{$lang['sort']}" rel="nofollow">
{$lang['Subject']}
{if {$sort_asc}}
&nbsp;<i title="{$lang['progressive']}" class="fa fa-sort"></i>
{/if}
</a>  /
<a href="index.php?page=forum&amp;show=1&amp;orderby=1&amp;id={$SECTION_ID}&amp;sort=writer"
title="{$lang['sort']}" rel="nofollow">
{$lang['sort_writer']}
{if {$sort_writer}}
&nbsp;<i title="{$lang['progressive']}" class="fa fa-sort"></i>
{/if}
</a>
</span>
</td>
<td class="thead forum_sub_rep wd4 nwrp a-center">
<a href="index.php?page=forum&amp;show=1&amp;orderby=1&amp;id={$SECTION_ID}&amp;sort=reply_number"
title="{$lang['sort']}" rel="nofollow">
{$lang['reply_num']}
{if {$sort_reply_number}}
&nbsp;<i title="{$lang['progressive']}" class="fa fa-sort"></i>
{/if}
</a>
</td>
<td class="thead forum_sub_vis wd4 nwrp a-center">
<a href="index.php?page=forum&amp;show=1&amp;orderby=1&amp;id={$SECTION_ID}&amp;sort=visitor"
title="{$lang['sort']}" rel="nofollow">
{$lang['subject_visitor']}
{if {$sort_visitor}}
&nbsp;<i title="{$lang['progressive']}" class="fa fa-sort"></i>
{/if}
</a>
</td>
<td class="thead forum_sub_lasts wd20 nwrp a-center">
<a href="index.php?page=forum&amp;show=1&amp;orderby=1&amp;id={$SECTION_ID}&amp;sort=write_time"
title="{$lang['sort']}" rel="nofollow">
{$lang['write_date']}
{if {$sort_write_time}}
&nbsp;<i title="{$lang['progressive']}" class="fa fa-sort"></i>
{/if}
</a>
</td>
{if !{$mod_toolbar}}
<td class="thead wd1 nwrp">
<input type="checkbox" name="check_all" onclick="checkAll(this.form)" />
</td>
{/if}
</tr>
{template}adsense_forum{/template}
{template}forum_announcement_table{/template}

</table>
{if !{$NO_REVIEW_SUBJECTS}}
<table class="border collapse brd1 clpc0 a-center">
<tr>
<td colspan="{$colspan}" class="thead wd98 a-center">
{$lang['subjects_review']}
</td>
</tr>
</table>
<table class="border wd98 brd1 clpc0 a-center">
{Des::while}{review_subject_list}
{if {$review_subject_list['review_subject']}}
<tr>
<td class="row1 a-center forum_sub_dotrev" style="width: 3.5%">
<img alt="" src="{$image_path}/dot_review.gif" class="brd0"
title="{$lang['subject_review']}" />
</td>
<td class="row2 wd2 a-center forum_sub_icon">
<img src="{$review_subject_list['icon']}" alt="" />
</td>
<td class="row1" style="width: 350px;">
<?php $PowerBB->_CONF['template']['while']['review_subject_list'][$this->x_loop]['title'] = $PowerBB->Powerparse->censor_words($PowerBB->_CONF['template']['while']['review_subject_list'][$this->x_loop]['title']); ?>

<a target="_blank" href="index.php?page=topic&amp;show=1&amp;id={$review_subject_list['id']}{$password}">
<img class="brd0" alt="{$lang['opin_subject']}"
src="{$image_path}/open.gif" /></a>
<span dir="{$_CONF['info_row']['content_dir']}">
<b>{$review_subject_list['prefix_subject']}</b>
</span>

<a href="index.php?page=topic&show=1&id={$review_subject_list['id']}{$password}"><span class="title_font">
<span id="status_r_{$review_subject_list['id']}"><b>{$review_subject_list['title']}</b></span></span></a>

{if !{$mod_toolbar}}
<a href="#rename_title_{$review_subject_list['id']}" class="renameIcon" onclick="return rename_title('{$review_subject_list['id']}','{$review_subject_list['title']}')" id="rename_t_{$review_subject_list['id']}">
<i class="renametitle fa fa-pencil"></i></a>
<span id="status_rename_{$review_subject_list['id']}" style="display: none;">
</span>
{/if}

{if {$review_subject_list['reply_number']} > {$_CONF['info_row']['perpage']}}
{template}forum_review_perpage_reply{/template}
<i class="l-left f_perpage fa fa-files-o" title="{$lang['subject_multipage']}" aria-hidden="true"></i>
{/if}
{if {$review_subject_list['attach_subject']} == 1}
<i class="l-left f_attach fa fa-paperclip" title="{$lang['subject_attach']}" aria-hidden="true"></i>
{/if}
{if {$review_subject_list['review_reply']}  > 0}
<i class="l-left f_observed fa fa-eye" title="{$review_subject_list['review_reply']}
{$lang['Posts_was_observed']}" aria-hidden="true"></i>
{/if}
{if {$review_subject_list['rating']} > {$_CONF['info_row']['show_rating_num_max']}}
<img alt="" src="{$image_path}/rating/rating_5.gif"
title="{$lang['Rate_Topic']}
{$review_subject_list['rating']} " class="l-left f_rating" />
{/if}
{if {$review_subject_list['close']}}
<span class="inline-block">{$lang['subject_close']}</span>
{/if}
{if {$review_subject_list['delete_topic']}}
<span class="inline-block">{$lang['subject_delete']}</span>
{/if}
{if {$review_subject_list['special']}}
<div class="vide"></div>
{$lang['subject_special']}
<span class="l-left">
<img src="{$image_path}/star.gif"
alt="{$lang['s_special']}" class="f_special brd0" /> </span>
{/if}
{if {$_CONF['info_row']['subject_describe_show']} and {$review_subject_list['subject_describe']} != ''}
<br />
<span class="smallfont">{$review_subject_list['subject_describe']}</span>
{/if}
<br />
<?php echo $PowerBB->functions->GetUsernameStyleAndUserId($PowerBB->_CONF['template']['while']['review_subject_list'][$this->x_loop]['writer']);?>
<div class="forum_resp">
<div class="forum_sub_n_rep">
{$lang['reply_num']} : {$review_subject_list['reply_number']}
</div>
<div class="forum_sub_n_vis">
{$lang['subject_visitor']} : {$review_subject_list['visitor']}
</div>
{if {$review_subject_list['reply_number']} > 0}
<div class="forum_sub_n_lasts">
<span class="n_lasts_guest">
{$lang['write_date']} :
{if {$review_subject_list['last_replier']} == 'Guest'}
{$lang['Guest_']} ,
</span>
{else}
<span class="n_lasts-rep">
<?php echo $PowerBB->functions->GetUsernameStyleAndUserId($PowerBB->_CONF['template']['while']['review_subject_list'][$this->x_loop]['last_replier']);?> ,
</span>
{/if}
<span class="n_lasts_date">
{$lang['last_date']}
{$review_subject_list['reply_date']}
</span>
</div>
{/if}
</div>
</td>
<td class="row2 forum_sub_rep wd4 a-center">
{if {$review_subject_list['reply_number']} == 0}
{$review_subject_list['reply_number']}
{else}
<a onclick="window.open('index.php?page=misc&amp;whoposted=1&amp;subject_id={$review_subject_list['id']}','mywindow','location=1,status=1,scrollbars=1,width=150,height=300')"><u class="whoposted">{$review_subject_list['reply_number']}</u></a>
{/if}
</td>
<td class="row1 forum_sub_vis wd4 a-center">
{$review_subject_list['visitor']}
</td>
<td class="row2 forum_sub_lasts wd20 a-center">
{if {$review_subject_list['reply_number']} <= 0}
{$lang['no_replys']}
{else}
<?php echo $PowerBB->functions->GetUsernameStyleAndUserId($PowerBB->_CONF['template']['while']['review_subject_list'][$this->x_loop]['last_replier']);?>			<br />
<span class="smallfont">
{$review_subject_list['reply_date']}
</span >
{/if}
</td>
{if !{$mod_toolbar}}
<td class="row1 wd1 a-center">
<input type="checkbox" name="check[]" value="{$review_subject_list['id']}" />
</td>
{/if}
</tr>
{/if}
{/Des::while}
</table>
{/if}
{if !{$NO_STICK_SUBJECTS}}
<table class="border wd98 brd1 clpc0 a-center">
<tr>
<td colspan="{$colspan}" class="thead wd98 a-center">
 {$lang['stick_subject_list']}
</td>
</tr>
</table>
<table class="border wd98 brd1 clpc0 a-center">
{Des::while}{stick_subject_list}
{if {$stick_subject_list['review_subject']} !='1'}
{if {$stick_subject_list['stick']}}
<tr>
<td class="row1 a-center forum_sub_dotrev" style="width: 3.5%">
<img alt="" src="{$image_path}/sticky.gif"
title="{$lang['stick_subject']}" />
</td>
<td class="row2 wd2 a-center forum_sub_icon">
<img src="{$stick_subject_list['icon']}" alt="{$stick_subject_list['icon']}" />
</td>
<td class="row1" style="width: 350px;">
<?php
$PowerBB->_CONF['template']['while']['stick_subject_list'][$this->x_loop]['title'] = $PowerBB->Powerparse->censor_words($PowerBB->_CONF['template']['while']['stick_subject_list'][$this->x_loop]['title']);
$num ='99';
$PowerBB->_CONF['template']['while']['stick_subject_list'][$this->x_loop]['text'] = $PowerBB->functions->words_count_replace_strip_tags_html2bb($PowerBB->_CONF['template']['while']['stick_subject_list'][$this->x_loop]['text'],$num);?>
<a target="_blank" href="index.php?page=topic&amp;show=1&amp;id={$stick_subject_list['id']}{$password}">
<img class="brd0" alt="{$lang['opin_subject']}"
src="{$image_path}/open.gif" /></a>


<span dir="{$_CONF['info_row']['content_dir']}">
<b>{$stick_subject_list['prefix_subject']} </b>
</span>
<a href="index.php?page=topic&show=1&id={$stick_subject_list['id']}{$password}"><span class="title_font">
<span id="status_r_{$stick_subject_list['id']}"><b>{$stick_subject_list['title']}</b></span></span></a>

{if !{$mod_toolbar}}
<a href="#rename_title_{$stick_subject_list['id']}" class="renameIcon" onclick="return rename_title('{$stick_subject_list['id']}','{$stick_subject_list['title']}')" id="rename_t_{$stick_subject_list['id']}">
<i class="renametitle fa fa-pencil"></i></a>
<span id="status_rename_{$stick_subject_list['id']}" style="display: none;">
</span>
{/if}


{if {$stick_subject_list['reply_number']} > {$_CONF['info_row']['perpage']}}
{template}forum_stick_perpage_reply{/template}
<i class="l-left f_perpage fa fa-files-o" title="{$lang['subject_multipage']}" aria-hidden="true"></i>
{/if}
{if {$stick_subject_list['attach_subject']} == 1}
<i class="l-left f_attach fa fa-paperclip" title="{$lang['subject_attach']}" aria-hidden="true"></i>
{/if}
{if {$stick_subject_list['review_reply']}  > 0}
<i class="l-left f_observed fa fa-eye" title="{$stick_subject_list['review_reply']}
{$lang['Posts_was_observed']}" aria-hidden="true"></i>
{/if}
{if {$stick_subject_list['rating']} > {$_CONF['info_row']['show_rating_num_max']}}
<img alt="" src="{$image_path}/rating/rating_5.gif"
title="{$lang['Rate_Topic']}
{$stick_subject_list['rating']} " class="l-left f_rating" />
{/if}
{if {$stick_subject_list['close']}}
<span class="inline-block">{$lang['subject_close']}</span>
{/if}
{if {$stick_subject_list['delete_topic']}}
<span class="inline-block">{$lang['subject_delete']}</span>
{/if}
{if {$stick_subject_list['special']}}
<div class="vide"></div>
{$lang['subject_special']}
<span class="l-left">
<img src="{$image_path}/star.gif"
alt="{$lang['s_special']}" class="f_special brd0" /> </span>
{/if}
{if {$_CONF['info_row']['subject_describe_show']} and {$stick_subject_list['subject_describe']} != ''}
<br />
<span class="smallfont">{$stick_subject_list['subject_describe']}</span>
{/if}
<br />
<?php echo $PowerBB->functions->GetUsernameStyleAndUserId($PowerBB->_CONF['template']['while']['stick_subject_list'][$this->x_loop]['writer']);?>
<div class="forum_resp">
<div class="forum_sub_n_rep">
{$lang['reply_num']} : {$stick_subject_list['reply_number']}
</div>
<div class="forum_sub_n_vis">
{$lang['subject_visitor']} : {$stick_subject_list['visitor']}
</div>
{if {$stick_subject_list['reply_number']} > 0}
<div class="forum_sub_n_lasts">
<span class="n_lasts_guest">
{$lang['write_date']} :
{if {$stick_subject_list['last_replier']} == 'Guest'}
{$lang['Guest_']} ,
</span>
{else}
<span class="n_lasts-rep">
<?php echo $PowerBB->functions->GetUsernameStyleAndUserId($PowerBB->_CONF['template']['while']['stick_subject_list'][$this->x_loop]['last_replier']);?> ,
</span>
{/if}
<span class="n_lasts_date">
{$lang['last_date']}
{$stick_subject_list['reply_date']}
</span>
</div>
{/if}
</div>
</td>
<td class="row2 forum_sub_rep wd4 a-center">
{if {$stick_subject_list['reply_number']} == 0}
{$stick_subject_list['reply_number']}
{else}
<a onclick="window.open('index.php?page=misc&amp;whoposted=1&amp;subject_id={$stick_subject_list['id']}','mywindow','location=1,status=1,scrollbars=1,width=150,height=300')"><u class="whoposted">{$stick_subject_list['reply_number']}</u></a>
{/if}
</td>
<td class="row1 forum_sub_vis wd4 a-center">
{$stick_subject_list['visitor']}
</td>
<td class="row2 forum_sub_lasts wd20 a-center">
{if {$stick_subject_list['reply_number']} <= 0}
{$lang['no_replys']}
{else}
<?php echo $PowerBB->functions->GetUsernameStyleAndUserId($PowerBB->_CONF['template']['while']['stick_subject_list'][$this->x_loop]['last_replier']);?>
<br />
<span class="smallfont">
{$stick_subject_list['reply_date']}
</span >
{/if}
</td>
{if !{$mod_toolbar}}
<td class="row1 wd1 a-center">
<input type="checkbox" name="check[]" value="{$stick_subject_list['id']}" />
</td>
{/if}
</tr>
{/if}
{/if}
{/Des::while}
</table>
{/if}
<table class="border wd98 brd1 clpc0 a-center">
<tr>
<td colspan="{$colspan}" class="thead wd98 a-center">
 {$lang['subject_list']}
</td>
</tr>
</table>
<table class="border wd98 brd1 clpc0 a-center">
{Des::while}{subject_list}
<?php
if($PowerBB->_CONF['template']['while']['subject_list'][$this->x_loop]['stick'] == 0
and $PowerBB->_CONF['template']['while']['subject_list'][$this->x_loop]['review_subject'] == 0)
{
?>
<tr>
<td class="row1 wd2 a-center forum_sub_dotrev">
{if {$subject_list['delete_topic']} == 1}
<img alt="" src="{$image_path}/dot_trash.gif"
title="{$lang['s_delete']}" />
{elseif {$subject_list['poll_subject']} == 1}
<img alt="" src="{$image_path}/dot_poll.gif"
title="{$lang['dot_poll']}" />
{elseif {$subject_list['close']}  == 1}
<img alt="" src="{$image_path}/dot_lockfolder.gif" class="brd0"
title="{$lang['s_close']}" />
{elseif {$subject_list['writer']} == {$_CONF['member_row']['username']}}
<img alt="" src="{$image_path}/dot_folder.gif"
title="{$lang['subject_personal']}" />
{elseif {$subject_list['reply_number']} > 15 and {$subject_list['visitor']} > 150}
<img alt="" src="{$image_path}/dot_hotfolder.gif"
title="{$lang['Hot_subject']}" />
{elseif {$subject_list['stick']} == 1}
<img alt="" src="{$image_path}/sticky.gif"
title="{$lang['stick_subject']}" />
{elseif {$_CONF['member_row']['lastvisit']} > {$subject_list['write_time']}}
<img alt="" src="{$image_path}/dot_nonewfolder.gif"
title="{$lang['no_newPosts']}" />
{elseif {$subject_list['write_time']} > {$_CONF['member_row']['lastvisit']}}
<img alt="" src="{$image_path}/dot_newfolder.gif"
title="{$lang['there_are_new_posts']}" />
{else}
<img alt="" src="{$image_path}/dot_nonewfolder.gif"
title="{$lang['no_newPosts']}" />
{/if}
</td>
<td class="row2 wd2 a-center forum_sub_icon">
<img src="{$subject_list['icon']}" alt="{$subject_list['icon']}" />
</td>
<td class="row1" style="width: 350px;">
<?php
$PowerBB->_CONF['template']['while']['subject_list'][$this->x_loop]['title'] = $PowerBB->Powerparse->censor_words($PowerBB->_CONF['template']['while']['subject_list'][$this->x_loop]['title']);
$num ='99';
$PowerBB->_CONF['template']['while']['subject_list'][$this->x_loop]['text'] = $PowerBB->functions->words_count_replace_strip_tags_html2bb($PowerBB->_CONF['template']['while']['subject_list'][$this->x_loop]['text'],$num);
?>
<a target="_blank" href="index.php?page=topic&amp;show=1&amp;id={$subject_list['id']}{$password}">
<img class="brd0" alt="{$lang['opin_subject']}"
src="{$image_path}/open.gif" /></a>
<?php $PowerBB->_CONF['template']['while']['subject_list'][$this->x_loop]['title'] = str_replace('&','&',$PowerBB->_CONF['template']['while']['subject_list'][$this->x_loop]['title']); ?>
<span class="title_font">
<span dir="{$_CONF['info_row']['content_dir']}">
<b>{$subject_list['prefix_subject']}</b>
</span>
</span>
<a title="{$subject_list['text']}" href="index.php?page=topic&show=1&id={$subject_list['id']}{$password}"><span class="title_font"><span id="status_r_{$subject_list['id']}">{$subject_list['title']}</span></span></a>
{if !{$mod_toolbar}}
<a href="#rename_title_{$subject_list['id']}" class="renameIcon" onclick="return rename_title('{$subject_list['id']}','{$subject_list['title']}')" id="rename_t_{$subject_list['id']}">
<i class="renametitle fa fa-pencil"></i></a>
<span id="status_rename_{$subject_list['id']}" style="display: none;">
</span>

{/if}
{if {$subject_list['reply_number']} > {$_CONF['info_row']['perpage']}}
{template}forum_perpage_reply{/template}
<i class="l-left f_perpage fa fa-files-o" title="{$lang['subject_multipage']}" aria-hidden="true"></i>
{/if}
{if {$subject_list['attach_subject']} == 1}
<i class="l-left f_attach fa fa-paperclip" title="{$lang['subject_attach']}" aria-hidden="true"></i>
{/if}
{if {$subject_list['review_reply']}  > 0}
<i class="l-left f_observed fa fa-eye" title="{$subject_list['review_reply']}
{$lang['Posts_was_observed']}" aria-hidden="true"></i>
{/if}
{if {$subject_list['rating']} > {$_CONF['info_row']['show_rating_num_max']}}
<img alt="" src="{$image_path}/rating/rating_5.gif"
title="{$lang['Rate_Topic']}
{$subject_list['rating']} " class="l-left f_rating" />
{/if}
{if {$subject_list['close']}}
<span class="inline-block">{$lang['subject_close']}</span>
{/if}
{if {$subject_list['delete_topic']}}
<span class="inline-block">{$lang['subject_delete']}</span>
{/if}
{if {$subject_list['special']}}
<div class="vide"></div>
{$lang['subject_special']}
<span class="l-left">
<img class="brd0" src="{$image_path}/star.gif"
alt="{$lang['s_special']}" /> </span>
{/if}
{if {$_CONF['info_row']['subject_describe_show']} and {$subject_list['subject_describe']} != ''}
<br />
<span class="smallfont">{$subject_list['subject_describe']}</span>
{/if}
<br />
{if {$subject_list['writer']} == 'Guest'}
{$lang['Guest_']}
{else}
<?php echo $PowerBB->functions->GetUsernameStyleAndUserId($PowerBB->_CONF['template']['while']['subject_list'][$this->x_loop]['writer']);?>
{/if}
<div class="forum_resp">
<div class="forum_sub_n_rep">
{$lang['reply_num']} : {$subject_list['reply_number']}
</div>
<div class="forum_sub_n_vis">
{$lang['subject_visitor']} : {$subject_list['visitor']}
</div>
{if {$subject_list['reply_number']} > 0}
<div class="forum_sub_n_lasts">
<span class="n_lasts_guest">
{$lang['write_date']} :
{if {$subject_list['last_replier']} == 'Guest'}
{$lang['Guest_']} ,
</span>
{else}
<span class="n_lasts-rep">
<?php echo $PowerBB->functions->GetUsernameStyleAndUserId($PowerBB->_CONF['template']['while']['subject_list'][$this->x_loop]['last_replier']);?> ,
</span>
{/if}
<span class="n_lasts_date">
{$lang['last_date']}
{$subject_list['reply_date']}
</span>
</div>
{/if}
</div>
</td>
<td class="row2 forum_sub_rep wd4 a-center">
{if {$subject_list['reply_number']} == 0}
{$subject_list['reply_number']}
{else}
<a onclick="window.open('index.php?page=misc&amp;whoposted=1&amp;subject_id={$subject_list['id']}','mywindow','location=1,status=1,scrollbars=1,width=150,height=300')"><u class="whoposted">{$subject_list['reply_number']}</u></a>
{/if}
</td>
<td class="row1 forum_sub_vis wd4 a-center">
{$subject_list['visitor']}
</td>
<td class="row2 forum_sub_lasts wd20 a-center">
{if {$subject_list['reply_number']} <= 0}
{$lang['no_replys']}
{else}
{if {$subject_list['last_replier']} == 'Guest'}
{$lang['Guest_']}
{else}
<?php echo $PowerBB->functions->GetUsernameStyleAndUserId($PowerBB->_CONF['template']['while']['subject_list'][$this->x_loop]['last_replier']);?>
<br />
<span class="smallfont">
{$subject_list['reply_date']}
</span>
{/if}
{/if}
</td>
{if !{$mod_toolbar}}
<td class="row1 wd1 a-center">
<input type="checkbox" name="check[]" value="{$subject_list['id']}" />
</td>
{/if}
</tr>
{/if}
{/Des::while}
{else}
</table>
<table class="border wd98 brd1 clpc0 a-center">
<tr>
<td colspan="{$colspan}" class="thead wd98 a-center">

{$lang['No_thread_forum']}

</td>
</tr>
{/if}
</table>
{if {$NO_SUBJECTS}}
<br />
{template}options_mod{/template}
<br />
<!-- table --><div style="width:98%; border-collapse: collapse;" class="table mrgTable">
<dl>
<dt></dt>
<dd class="f_sub_link" style="text-align: {$align};">
{template}add_subject_link{/template}
</dd>
<dd class="l-left f_sub_pager">
{if {$pager}}
<?php echo $PowerBB->pager->show();?>
{/if}
</dd>
</dl>
</div><!-- /table -->
<br />
<table class="border wd98 brd1 clpc0 a-center">
<tr>

<td class="thead wd30">{$lang['online_naw']} : {$online_number}
<span class="inline-block">({$MemberNumber}
{$lang['Member_and']}
{$GuestNumber}
{$lang['Guest_']})
</span>
</td>
<td class="thead wd20">{$lang['moderators_forum']}</td>
</tr>
<tr>

<td class="row1 wd30">{template}forum_online_table{/template}</td>
<td class="row1 wd20">
{template}forum_moderator_table{/template}
</td>
</tr>
</table>
<br />
<table class="border wd98 brd1 clpc0 a-center">
<tr>
<td class="row1 a-{$align}" style="width: 350px;">
<div class="a-{$align}">
<table class="wd100 brd0 clp0 a-{$align}" style="border-collapse: collapse">
<tr>
<td class="wd2">
<img title="{$lang['newPosts']}"
src="{$image_path}/dot_newfolder.gif"
alt="{$lang['newPosts']}" class="brd0" /></td>
<td class="smallfont">{$lang['newPosts']}</td>
<td class="wd2">
<img title="{$lang['no_newPosts']}"
src="{$image_path}/dot_nonewfolder.gif"
alt="{$lang['no_newPosts']}" class="brd0" /></td>
<td class="smallfont">{$lang['no_newPosts']}</td>
<td class="smallfont"> </td>
</tr>
<tr>
<td>
<img title="{$lang['s_delete']}"
src="{$image_path}/dot_trash.gif"
alt="{$lang['s_delete']}" class="brd0" /></td>
<td class="smallfont">{$lang['s_delete']}</td>
<td>
<img title="{$lang['dot_hotfolder']}"
src="{$image_path}/dot_hotfolder.gif"
alt="{$lang['dot_hotfolder']}" class="brd0" /></td>
<td class="smallfont">{$lang['Hot_subject']} </td>
<td class="smallfont"> </td>
</tr>
<tr>
<td>
<img title="{$lang['dot_poll']}"
src="{$image_path}/dot_poll.gif"
alt="{$lang['dot_poll']}" class="brd0" /></td>
<td class="smallfont">{$lang['dot_poll']}</td>
<td>
<img title="{$lang['dot_newposts']}"
src="{$image_path}/dot_newposts.gif"
alt="{$lang['dot_newposts']}" class="brd0" /></td>
<td class="smallfont">{$lang['dot_newposts']}</td>
<td class="smallfont"> </td>
</tr>
<tr>
<td>
<img title="{$lang['su_close']}"
src="{$image_path}/dot_lockfolder.gif"
alt="{$lang['su_close']}" class="brd0" /></td>
<td class="smallfont">{$lang['su_close']}</td>
<td>
<img title="{$lang['your_subject_personal']}"
alt="" src="{$image_path}/dot_folder.gif" class="brd0" /></td>
<td class="smallfont">{$lang['your_subject_personal']}</td>
<td class="smallfont" class="l-left">
{if {$_CONF['info_row']['active_rss']} == '1'}
<span class="smallfont" class="left_text_align">
<a href="index.php?page=rss&amp;section=1&amp;id={$SECTION_ID}">
<i class="fi-icon l-left fa fa-rss"
title="{$lang['rss_section']}" aria-hidden="true"></i>
</a></span>{/if}</td>
</tr>
</table>
</div>
</td>
</tr>
</table>
{/if}
<br />