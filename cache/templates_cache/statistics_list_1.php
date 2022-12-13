{if {$_CONF['info_row']['activate_last_static_list']} == 1}
<div id="activate_last_static_list">
<br />
<div class="border_radius">
<ul class="rUlRow">
<li class="tcat">
<i id="heading_up_statistics_list" class="CollapseIcon">
<img src="{$image_path}/expanded.png" alt="" />
</i>
<i id="heading_down_statistics_list" class="CollapseIcon">
<img src="{$image_path}/collapsed.png" alt="" />
</i>
<a title="{$lang['mor_static']}" href="index.php?page=static&amp;index=1">
<span>{$lang['last_static_num']}</span>
</a>
</li>
</ul>
</div>
<div class="Ajax-static" id="active_statistics_list">
<div class="right-mainbox">
<div style="margin: 2px;">
<option value="pp-topparticipants" id='pp-topparticipants'>{$lang['Top_participants']}</option>
<option value="pp-reputation" id='pp-reputation'>{$lang['strongest_reputation']}</option>
<option value="pp-register" id='pp-register'>{$lang['New_members']}</option>
<option value="pp-invite" id='pp-invite'>{$lang['TopMemberInvite']}</option>
</div>
<div id='topparticipants_table'>
<table class="wd100 brd0 clp0" style="border-collapse: collapse">
{if {$TopMemberListNum} > 0}
<tr style="vertical-align: top;">
<td class="thead1 right_text_align">
{$lang['user_name']}</td>
<td class="thead1 right_text_align">
&nbsp;{$lang['posts']}</td>
</tr>
{Des::while}{TopMemberList}
<tr style="vertical-align: top;">
<td class="row2 right_text_align">
<span class="smallfont">
<a href="index.php?page=profile&amp;show=1&amp;id={$TopMemberList['id']}">{$TopMemberList['username']}</a>
</span>
</td>
<td class="row2 center_text_align wd1" title="{$lang['posts']}">
{$TopMemberList['posts']}</td>
</tr>
{/Des::while}
{else}
{$lang['No_Posts']}
{/if}
</table>
</div>
<div id='reputation_table'>
<table class="wd100 brd0 clp0" style="border-collapse: collapse">
{if {$MemberReputationNum} > 0}
<tr style="vertical-align: top;">
<td class="thead1 right_text_align">
{$lang['user_name']}</td>
<td class="thead1 right_text_align">
&nbsp;{$lang['usercp_reputations']}</td>
</tr>
{Des::while}{TopMemberReputation}
<tr style="vertical-align: top;">
<td class="row2 right_text_align">
<span class="smallfont">
<a href="index.php?page=profile&amp;show=1&amp;id={$TopMemberReputation['id']}">
<?php
$num ='35';
$text = $PowerBB->_CONF['template']['while']['TopMemberReputation'][$this->x_loop]['username'];
echo $PowerBB->Powerparse->_wordwrap($text,$num);
?>
</a>
</span>
</td>
<td class="row2 center_text_align wd1" title="{$lang['Reputable']}">
{$TopMemberReputation['reputation']}</td>
</tr>
{/Des::while}
{else}
<td class="thead1 right_text_align">
{$lang['No_Reputaions']}</td>
{/if}
</table>
</div>
<div id='register_table'>
<table class="wd100 brd0 clp0" style="border-collapse: collapse">
<tr style="vertical-align: top;">
<td class="thead1 right_text_align wd50">
{$lang['user_name']}</td>
<td class="thead1 left_text_align wd50">
&nbsp;{$lang['date_register']}</td>
</tr>
</tr>
{Des::while}{latest_registered}
<tr style="vertical-align: top;">
<td class="row2 right_text_align wd50">
<span class="smallfont">
<a href="index.php?page=profile&amp;show=1&amp;id={$latest_registered['id']}" title="{$latest_registered['register_date']}">
<?php
$num ='35';
$text = $PowerBB->_CONF['template']['while']['latest_registered'][$this->x_loop]['username'];
echo $PowerBB->Powerparse->_wordwrap($text,$num);
?>
</a>
</span>
</td>
<td class="row2 left_text_align wd50" title="{$lang['date_register']}">
&nbsp;<span class="smallfont">{$latest_registered['register_date']}</span></td>
</tr>
{/Des::while}
</table>
</div>
<div id='invite_table'>
<table class="wd100 brd0 clp0" style="border-collapse: collapse">
{if {$TopMemberInviteNum} > 0}
<tr style="vertical-align: top;">
<td class="thead1 right_text_align">
{$lang['user_name']}</td>
<td class="thead1 right_text_align">
&nbsp;{$lang['Invites']}</td>
</tr>
{Des::while}{TopMemberInvite}
<tr style="vertical-align: top;">
<td class="row2 right_text_align">
<a href="index.php?page=profile&amp;show=1&amp;id={$TopMemberInvite['id']}">
<span class="smallfont">
<?php
$num ='35';
$text = $PowerBB->_CONF['template']['while']['TopMemberInvite'][$this->x_loop]['username'];
echo $PowerBB->Powerparse->_wordwrap($text,$num);
?>
</span>
</a>
</td>
<td class="row2 left_text_align wd1" title="{$lang['Invites']}">
{$TopMemberInvite['invite_num']}</td>
</tr>
{/Des::while}
{else}
<tr>
<td class="row2 right_text_align">
{$lang['No_Invits']}</td>
</tr>
{/if}
</table>
</div>
</div>
<div class="left-mainbox">
<ul class="rUlRow">
<li class="pp-tab">
<div class='pp-taboff3' id='pp-lastsubject'>{$lang['LastSubject']}</div>
<div class='pp-taboff3' id='pp-topsubjects'>{$lang['TopSubjectVisitor']}</div>
<div class='pp-taboff3' id='pp-topsections'>{$lang['most_popular_forums']}</div>
</li>
</ul>
<div id='lastsubject_table'>
<table class="wd100 brd0 clp0" style="border-collapse: collapse">
<tr style="vertical-align: top;">
<td class="thead1 right_text_align">
{$lang['Subject']}</td>
<td class="thead1 right_text_align">
{$lang['By_']}</td>
<td class="thead1 right_text_align">
&nbsp;&nbsp;{$lang['date']}</td>
</tr>
{Des::while}{lastPostsList}
{if {$lastPostsList['title']} !=''}
<tr class="va-t">
<td class="row2 right_text_align" title="{$lastPostsList['title']}">
<i class="fa fa-sticky-note"></i>&nbsp;
<a href="index.php?page=topic&amp;show=1&amp;id={$lastPostsList['id']}" title="{$lastPostsList['title']}">
<span class="smallfont">
<?php
$num ='35';
$text = $PowerBB->_CONF['template']['while']['lastPostsList'][$this->x_loop]['title'];
echo $PowerBB->Powerparse->_wordwrap($text,$num);
?>
</span>
</a>
</td>
<td class="row2 lastP_user right_text_align" title="{$lastPostsList['last_replier']}">
<span class="smallfont">
{if {$lastPostsList['last_replier']} !=''}
<a href="index.php?page=profile&amp;show=1&amp;username={$lastPostsList['last_replier']}">
{$lastPostsList['last_replier']}
</a>
{else}
<a href="index.php?page=profile&amp;show=1&amp;username={$lastPostsList['writer']}">
{$lastPostsList['writer']}
</a>
{/if}
</span>
</td>
<td class="row2 lastP_date right_text_align" title="{$lastPostsList['reply_date']}">
&nbsp;<span class="smallfont">
<?php echo $PowerBB->functions->time_ago($PowerBB->_CONF['template']['while']['lastPostsList'][$this->x_loop]['write_time']); ?>
</span>
</td>
</tr>
{/if}
{/Des::while}
</table>
</div>		<div id='topsubjects_table'>
<table class="wd100 brd0 clp0" style="border-collapse: collapse">
{if {$TopSubjectVisitorNum} > 0}
<tr style="vertical-align: top;">
<td class="thead1 right_text_align">
{$lang['Subject']}</td>
<td class="thead1 right_text_align">
&nbsp;&nbsp;{$lang['subject_visitor']}&nbsp;&nbsp;</td>
</tr>
{Des::while}{TopSubjectVisitor}
<tr style="vertical-align: top;">
<td class="row2 right_text_align">
<i class="fa fa-sticky-note"></i>&nbsp;
<a href="index.php?page=topic&amp;show=1&amp;id={$TopSubjectVisitor['id']}">
<span class="smallfont">
<?php
$num ='35';
$text = $PowerBB->_CONF['template']['while']['TopSubjectVisitor'][$this->x_loop]['title'];
echo $PowerBB->Powerparse->_wordwrap($text,$num);
?>
</span>
</a>
</td>
<td class="row2 center_text_align wd1" title="{$lang['SubjectVisitor']}">
{$TopSubjectVisitor['visitor']}</td>
</tr>
{/Des::while}
{else}
{$lang['No_Visits']}
{/if}
</table>
</div>
<div id='topsections_table'>
<table class="wd100 brd0 clp0" style="border-collapse: collapse">
{if {$TopSectionListNum} >0}
<tr style="vertical-align: top;">
<td class="thead1 right_text_align">
{$lang['forum']}</td>
<td class="thead1 right_text_align">
&nbsp;&nbsp;{$lang['reply_num']}&nbsp;&nbsp;</td>
</tr>
{Des::while}{TopSectionsList}
<tr style="vertical-align: top;">
<td class="row2 right_text_align" title="{$TopSectionsList['title']}">
<span class="smallfont">
<a href="index.php?page=forum&amp;show=1&amp;id={$TopSectionsList['id']}">
<?php
$num ='35';
$text = $PowerBB->_CONF['template']['while']['TopSectionsList'][$this->x_loop]['title'];
echo $PowerBB->Powerparse->_wordwrap($text,$num);
?>
</a>
</span>
</td>
<td class="row2 center_text_align wd1" title="{$lang['reply_num']}">
{$TopSectionsList['reply_num']}</td>
</tr>
{/Des::while}
{else}
{$lang['No_Posts']}
{/if}
</table>
</div>
</div>
<div style="clear:both;"></div>
</div>
<script type='text/javascript'>
$("#pp-topparticipants").attr('style', 'background-color:white;');
function ShowTopParticipantsTable()
{

if ($("#pp-topparticipants").click)
{
$("#topparticipants_table").fadeIn();
$("#reputation_table").hide();
$("#register_table").hide();
$("#invite_table").hide();
$("#pp-topparticipants").attr('style', 'background-color:white;');
$("#pp-reputation").attr('style', 'background-color:#d4f0f1;');
$("#pp-register").attr('style', 'background-color:#d4f0f1;');
$("#pp-invite").attr('style', 'background-color:#d4f0f1;');
}
else
{
$("#topparticipants_table").fadeOut();
}
}
function ShowTopReputationTable()
{		if ($("#pp-reputation").click)
{
$("#topparticipants_table").hide();
$("#reputation_table").fadeIn();
$("#register_table").hide();
$("#invite_table").hide();
$("#pp-topparticipants").attr('style', 'background-color:#d4f0f1;');
$("#pp-reputation").attr('style', 'background-color:white;');
$("#pp-register").attr('style', 'background-color:#d4f0f1;');
$("#pp-invite").attr('style', 'background-color:#d4f0f1;');
}
else
{
$("#reputation_table").fadeOut();
}
}
function ShowRegisterTable()
{
if ($("#pp-register").click)
{
$("#topparticipants_table").hide();
$("#reputation_table").hide();
$("#register_table").fadeIn();
$("#invite_table").hide();
$("#pp-topparticipants").attr('style', 'background-color:#d4f0f1;');
$("#pp-reputation").attr('style', 'background-color:#d4f0f1;');
$("#pp-register").attr('style', 'background-color:white;');
$("#pp-invite").attr('style', 'background-color:#d4f0f1;');
}
else
{
$("#register_table").fadeOut();
}
}
function ShowInviteTable()
{
if ($("#pp-invite").click)
{
$("#topparticipants_table").hide();
$("#reputation_table").hide();
$("#register_table").hide();
$("#invite_table").fadeIn();
$("#pp-topparticipants").attr('style', 'background-color:#d4f0f1;');
$("#pp-reputation").attr('style', 'background-color:#d4f0f1;');
$("#pp-register").attr('style', 'background-color:#d4f0f1;');
$("#pp-invite").attr('style', 'background-color:white;');
}
else
{
$("#invite_table").fadeOut();
}
}
function ShowLastsubjectTable()
{
if ($("#pp-lastsubject").click)
{
$("#lastsubject_table").fadeIn();
$("#topsubjects_table").hide();
$("#topsections_table").hide();
document.getElementById('pp-lastsubject').className="pp-tabon3";
document.getElementById('pp-topsubjects').className="pp-taboff3";
document.getElementById('pp-topsections').className="pp-taboff3";
}
else
{
$("#lastsubject_table").fadeOut();
}
}
function ShowTopsubjectsTable()
{
if ($("#pp-topsubjects").click)
{
$("#lastsubject_table").hide();
$("#topsubjects_table").fadeIn();
$("#topsections_table").hide();
document.getElementById('pp-lastsubject').className="pp-taboff3";
document.getElementById('pp-topsubjects').className="pp-tabon3";
document.getElementById('pp-topsections').className="pp-taboff3";
}
else
{
$("#lastsubject_table").fadeOut();
}
}
function ShowTopsectionsTable()
{
if ($("#pp-topsections").click)
{
$("#lastsubject_table").hide();
$("#topsubjects_table").hide();
$("#topsections_table").fadeIn();
document.getElementById('pp-lastsubject').className="pp-taboff3";
document.getElementById('pp-topsubjects').className="pp-taboff3";
document.getElementById('pp-topsections').className="pp-tabon3";
}
else
{
$("#topsections_table").fadeOut();
}
}
function Ready()
{
$("#pp-topparticipants").click(ShowTopParticipantsTable);
$("#pp-reputation").click(ShowTopReputationTable);
$("#reputation_table").hide();
$("#pp-register").click(ShowRegisterTable);
$("#register_table").hide();
$("#pp-invite").click(ShowInviteTable);
$("#invite_table").hide();
$("#pp-lastsubject").click(ShowLastsubjectTable);
document.getElementById('pp-lastsubject').className="pp-tabon3";
$("#pp-topsubjects").click(ShowTopsubjectsTable);
$("#topsubjects_table").hide();
$("#pp-topsections").click(ShowTopsectionsTable);
$("#topsections_table").hide();
}
$(document).ready(Ready);
</script>
</div>
<br />
{/if}
