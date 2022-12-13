<script type='text/javascript'>
function AjaxSubjectRating()
{

    var AjaxState = {ajaxSend : $("#rating_status").html("<div class='info_bar'> <img border='0' alt='' src='{$image_path}/loading.gif'></div>")};

    var data = {};

    data['by_username']    =    $("#by_username").val();
    data['subject_title']    =    $("#subject_title").val();
    data['subject_id']    =    $("#subject_id").val();
    data['username']    =    $("#username").val();
    data['vote']    =    $("#vote").val();

    $.post("index.php?page=misc&rating=1",data,function Success(xml) { $("#rating_status").html(xml); });
}

function Wait()
{
    $("#rating_status").html("{$lang['Ongoing_process']}");
}

function Ready()
{
    $("#rating_id").click(AjaxSubjectRating);
}

$(document).ready(Ready);

</script>
<!-- table -->
<div style="width:98%; border-spacing:0px;" class="table mrgTable border_radius">
<dl>
<dt></dt>
<dd id="top_topic_bar" class="nwrp">
<ul class="pbbList_inline">
<li class="r-right">
{if {$Info['stick']} or {$Info['close']} or {$Info['delete_topic']}}
<strong>
{$lang['case_of_Subject']}
</strong>
{else}
<span dir="{$_CONF['info_row']['content_dir']}">
{$Info['prefix_subject']}
</span>
{/if}
{if {$Info['stick']}}
{$lang['sticky']}
{/if}
{if {$Info['close']}}
{if {$Info['stick']}}
,
{/if}
{$lang['Closed']}
{/if}
{if {$Info['delete_topic']}}
{if {$Info['stick']} or {$Info['close']}}
,
{/if}
{$lang['Deleted']}
{/if}
</li>
<!-- Code subject_tools Menu start -->
{if !{$mod_toolbar}}
<!-- Code Options_mods Menu start -->
<li class="l-left">
<a href="javascript:switchMenuNone('Options_mods')" class="popmenubutton button_b">
{$lang['Options_mod']}
<img alt="" class="brd0" src="{$image_path}/menu_open.gif" />
</a>
<div style="display:none;" id="Options_mods" class="drop_menu">
<!-- table --><div style="border-spacing:1px;" class="table border">
<dl class="center_text_align">
<dt></dt>
<dd class="thead">
{$lang['Options_mod']}
</dd>
</dl>
<dl class="center_text_align">
<dt></dt>
<dd class="menu_popup">
{template}show_subject_control{/template}
</dd>
</dl>
</div><!-- /table -->
</div>
<!-- Code Options_mods Menu End -->
</li>
{/if}
{if {$_CONF['member_permission']}}
{if {$_CONF['member_row']['username']} != {$Info['username']}}
{if {$_CONF['info_row']['rating_show']} == 1}
<!-- Code subject_rating Menu start -->
<li class="l-left">
<a href="javascript:switchMenuNone('subject_rating')" class="popmenubutton button_b">
{$lang['subject_rating']}
<img class="brd0" alt="" src="{$image_path}/menu_open.gif" />
</a>
<div style="display:none;" id="subject_rating" class="drop_menu">
<form name="misc" method="post" action="index.php?page=misc&amp;rating=1">
<!-- table --><div style="width:160px; border-spacing:1px;" class="table border">
<dl class="center_text_align">
<dt></dt>
<dd class="thead">
{$lang['subject_rating']}
</dd>
</dl>
<dl class="center_text_align">
<dt></dt>
<dd class="menu_popup right_text_align">
<div>
<img title="{$lang['Excellent']}" class="brd0"
src="{$image_path}/rating/rating_5.gif"
alt="{$lang['Excellent']}" />
<label for="vote5">
<input name="vote" id="vote" value="5" type="radio" />
{$lang['Excellent']}
</label>
</div>
<div>
<img title="{$lang['Good']}" class="brd0"
src="{$image_path}/rating/rating_4.gif"
alt="{$lang['Good']}" />
<label for="vote4">
<input name="vote" id="vote" value="4" type="radio" />
{$lang['Good']}
</label>
</div>
<div>
<img title="{$lang['Average']}" class="brd0"
src="{$image_path}/rating/rating_3.gif"
alt="{$lang['Average']}" />
<label for="vote3">
<input name="vote" id="vote" value="3" type="radio" />
{$lang['Average']}
</label>
</div>
<div>
<img title="{$lang['Bad']}" class="brd0"
src="{$image_path}/rating/rating_2.gif"
alt="{$lang['Bad']}" />
<label for="vote2">
<input name="vote" id="vote" value="2" type="radio" />
{$lang['Bad']}
</label>
</div>
<div>
<img title="{$lang['Terrible']}" class="brd0"
src="{$image_path}/rating/rating1.gif"
alt="{$lang['Terrible']}" />
<label for="vote1">
<input name="vote" id="vote" value="1" type="radio" />
{$lang['Terrible']}
</label>
</div>
<input TYPE="hidden" id="subject_title" name="subject_title" value="{$subject_title}" size="40" />
<input TYPE="hidden" id="subject_id" name="subject_id" value="{$subject_id}" size="40" />
<input TYPE="hidden" id="by_username" name="by_username" value="{$_CONF['member_row']['username']}" size="40" />
<input TYPE="hidden" id="username" name="username" value="{$Info['username']}" size="40" />
</dd>
</dl>
<dl class="center_text_align">
<dt></dt>
<dd class="menu_popup">
<input type="button" name="rating" id="rating_id" value="{$lang['Vote_now']}" onclick="comm._submit();"><div class="center_text_align" id="rating_status"></div>
</dd>
</dl>
</div><!-- /table -->
</form>
</div>
<!-- Code subject_rating Menu End -->
</li>
{/if}
{/if}
{/if}
{if {$_CONF['member_permission']}}
<li class="l-left sub_tools">
<a href="javascript:switchMenuNone('subject_tools')" class="popmenubutton button_b" onclick="switchBlock('subject_rating');">
{$lang['Thread_Tools']}
<img alt="" class="brd0" src="{$image_path}/menu_open.gif" />
</a>
<div style="display:none;" id="subject_tools" class="drop_menu">
<!-- table --><div style="width:200px; border-spacing:1px;" class="table border">
<dl class="center_text_align">
<dt></dt>
<dd class="thead">
{$lang['Thread_Tools']}
</dd>
</dl>
<dl>
<dt></dt>
<dd class="menu_popup">
<a href="index.php?page=download&amp;subject=1&amp;id={$subject_id}">
<img src="{$image_path}/icon1.gif"
title="{$lang['Download_Subject']}" class="brd0" alt="" />
{$lang['Download_Subject']}
</a>
</dd>
</dl>
<dl>
<dt></dt>
<dd class="menu_popup">
<a href="index.php?page=print&amp;show=1&amp;id={$subject_id}">
<img alt="" class="brd0" src="{$image_path}/printer.gif"
title="{$lang['print_Subject']}" />
{$lang['print_Subject']}
</a>
</dd>
</dl>
<dl>
<dt></dt>
<dd class="menu_popup">
<a href="index.php?page=misc&amp;sendtofriend=1&amp;id={$subject_id}">
<img src="{$image_path}/sendtofriend.gif"
title="{$lang['sendsubjecttofriend']}" class="brd0" alt="" />
{$lang['sendsubjecttofriend']}
</a>
</dd>
</dl>
{if {$_CONF['info_row']['allowed_emailed']} == '1' AND {$_CONF['member_permission']}}
<dl>
<dt></dt>
<dd class="menu_popup">
<a href="index.php?page=misc&amp;addsubscription=1&amp;id={$subject_id}">
<img src="{$image_path}/subscribe.gif"
title="{$lang['addsubscription']}" class="brd0" alt="" />
{$lang['addsubscription']}
</a>
</dd>
</dl>
{/if}
</div>
<!-- /table -->
</div>
<!-- Code subject_tools Menu End -->
</li>
{/if}
</ul>
</dd>
</dl>
</div><!-- /table -->

