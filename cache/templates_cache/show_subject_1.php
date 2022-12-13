<!-- action_find_addons_1 -->
{template}show_subject_information{/template}
<!-- table -->
<div style="width:98%; border-spacing:0px;" class="table mrgTable border subcontent" onclick="switchBlock('Options_mods')">
<dl class="center_text_align">
<dt></dt>
<dd class="wd20 subtr"></dd>
<dd class="thead">
<div class="r-right">
<img src="{$Info['icon']}" alt="subject icon" />
<span dir="{$_CONF['info_row']['content_dir']}">
{$Info['prefix_subject']}
</span>
<a href="index.php?page=topic&amp;show=1&amp;id={$subject_id}">
{$subject_title}
</a>
</div>
<div class="l-left">
{$Info['native_write_time']}
</div>
</dd>
</dl>
<dl>
<dt></dt>
<dd class="va-t r-nfo center_text_align">
<div onclick="switchBlock('menu_{$subject_id}');">
{template}writer_info{/template}
</div>
</dd>
<dd class="tbar_writer_info special_{$Info['special']} wd80">
{template}add_this{/template}
{if !{$STOP_ADSENSES_TEMPLATE}}
{template}adsense_topic{/template}
{/if}
{get_hook}show_subject_text{/get_hook}
<div id="status_{$subject_id}">
<div class="text">{$Info['text']}</div>
</div>
<div id="status_rename_{$subject_id}" style="display: none;"></div>
<div class="bottom_text"></div>
{if {$Info['close_reason']} != '' and {$Info['close']} }
<br />
<br />
<i>
<span style="color:#FF0000;">
{$lang['reason_for_closure']}
{$Info['close_reason']}
</span>
</i>
{/if}
{if {$Info['attach_subject']}}
{template}attach_show{/template}
{/if}

{if {$Info['action_by']} != ''}

<div id="action_by_{$subject_id}" class="action_by center_text_align">
<i>
{$lang['action_by_subject']}
<b><a title="{$Info['action_by']}" href="index.php?page=profile&show=1&username={$Info['action_by']}">{$Info['action_by']}</a></b>
{$lang['last_date']}
{$Info['actiondate']}
</i>
</div>
{/if}

{if {$Info['reason_edit']} != ''}
<div class="reason_edit">
<b>
{$lang['reason_edit']}
{$Info['reason_edit']}
</b>
</div>
<br />
{/if}
{get_hook}show_subject_down_text{/get_hook}
{if {$Info['user_sig']} != ''}
{template}signature_show{/template}
{/if}


</dd>
</dl>
{if {$_CONF['group_info']['write_reply']}}
<dl class="subc center_text_align">
<dt></dt>
<dd class="r-subc1"></dd>
<dd class="r-subc2">
<!-- table -->
<div class="wd100 clpc0 table r-subc">
<dl>
<dt></dt>
<dd>
<div class="r-right add_reputation">
{if {$_CONF['member_permission']}}
{if {$ReplierInfo['username']}}
{if {$_CONF['member_row']['username']} != {$ReplierInfo['username']}}
{if {$_CONF['info_row']['reputationallw']} == 1}
<a href="javascript:switchMenuNone('{$subject_id}reputation')"
title="{$lang['add_reputation_to']}
{$ReplierInfo['username']}">
<i title="{$lang['add_reputation_to']}
{$ReplierInfo['username']}"class="fa fa-thumbs-o-up fa-2" aria-hidden="true"></i>
</a>
{template}subject_reputation{/template}
{/if}
{/if}
{/if}
<a class="for_post" title="{$lang['reporting_for_post']}"
href="index.php?page=report&amp;index=1&amp;subject_id={$subject_id}">
<i title="{$lang['reporting_for_post']}"class="fa fa-bell-o" aria-hidden="true"></i>
</a>
{/if}
<!-- like facebook -->
{if {$_CONF['info_row']['active_like_facebook']} == '1'}
<span class="addthis_toolbox addthis_default_style">
<a style="border-bottom:none" class="addthis_button_facebook_like"></a>
<a style="border-bottom:none" class="addthis_button_tweet"></a>
</span>
<script type="text/javascript">
var addthis_config = {"data_track_clickback":true};
</script>
<script type="text/javascript" src="{$ForumAdress}includes/js/addthis_widget.js#username=
{$_CONF['info_row']['use_list']}"></script>
{/if}
<!-- / like facebook -->
<!-- action_find_addons_2 -->
</div>
<div class="l-left">
<!-- action_find_addons_3 -->
<ul class="post_controls">
{if {$_CONF['member_permission']}}
{if !{$mod_toolbar}}
<li>
<a title="{$lang['edit_subject']}" OnClick="edit_slide_Down({$subject_id});return false;" class="Button_secondary">
{$lang['edit_subject']}
</a>
<div class="edit_to_post" id="edit_{$subject_id}" style="display:none;">
<option class="Button_ss" title="{$lang['fastedit']}" onclick="edit_subject('{$subject_id}')">
+&nbsp;{$lang['fastedit']}</option>
<a title="{$lang['fulliedit']}"
href="index.php?page=management&amp;subject=1&amp;section={$Info['section']}&amp;subject_id={$subject_id}&amp;operator=edit" class="Button_ss">
{$lang['fulliedit']}
</a>
</li>
<li>
<a href="index.php?page=management&amp;subject=1&amp;section={$Info['section']}&amp;subject_id={$subject_id}&amp;operator=delete"
title="{$lang['delete_subject']}" class="Button_secondary">
{$lang['delete_subject']}
</a>
</li>
{else}
{if {$Info['close']} == '0'}
{if {$timeout}}
{if {$_CONF['member_row']['username']} == {$Info['username']}}
{if {$_CONF['group_info']['edit_own_subject']}}
<li>
<a title="{$lang['edit_subject']}" OnClick="edit_slide_Down({$subject_id});return false;" class="Button_secondary">
{$lang['edit_subject']}
</a>
<div class="edit_to_post" id="edit_{$subject_id}" style="display:none;">
<option class="Button_ss" title="{$lang['fastedit']}" onclick="edit_subject('{$subject_id}')">
+&nbsp;{$lang['fastedit']}</option>
<a title="{$lang['fulliedit']}"
href="index.php?page=management&amp;subject=1&amp;section={$Info['section']}&amp;subject_id={$subject_id}&amp;operator=edit" class="Button_ss">
{$lang['fulliedit']}
</a>
</li>
{/if}
{if {$_CONF['group_info']['del_own_subject']}}
<li>
<a href="index.php?page=management&amp;subject=1&amp;section={$Info['section']}&amp;subject_id={$subject_id}&amp;operator=delete"
title="{$lang['delete_subject']}" class="Button_secondary">
{$lang['delete_subject']}
</a>
</li>
{/if}
{/if}
{/if}
{/if}
{/if}
{/if}
<li>
<a href="index.php?page=new_reply&amp;index=1&amp;id={$subject_id}{$password}
&amp;qu_Subject={$subject_id}&amp;
user={$Info['username']}" title="{$lang['qu_subject']}" class="Button_secondary">
{$lang['quote']}
</a>
</li>
<li>
<?php $post_id = $PowerBB->_CONF['template']['subject_id']; ?>
<a class="img-submit" id="mad_<?php echo "$post_id"; ?>" name="mad_<?php echo "$post_id"; ?>" onclick="multiquote_add(<?php echo "$post_id"; ?>); return false;" href="#">
{$lang['multiquote']}
</a>
</li>
<!-- action_find_addons_4 -->
</ul>
</div>
</dd>
</dl>
</div><!-- /table -->
</dd>
</dl>
{/if}
</div>
<!-- /table -->
{Des::while}{AdsensesList}
{if {$AdsensesList['down_topic']} == '1'}
<div class="center_text_align">
{$AdsensesList['adsense']}
<br />
</div>
{/if}
{/Des::while}
<!-- action_find_addons_5 -->
<br />
<script type='text/javascript'>
 function edit_slide_Down(id) {
 $("#edit_"+id).slideToggle("slow");
}
function edit_subject(valu)
{
    $("#status_rename_"+valu+"").show();
    $.post("index.php?page=topic&fastEdit=1&subject=1&id="+valu, function(data, status){
     $("#status_rename_"+valu+"").html(data);

    });
$("#status_"+valu+"").hide();
}

</script>