<div class="center_text_align" id="status"></div>
{if {$close}}
{if !{$mod_toolbar}}
{template}topic_end_fast_reply{/template}
{/if}
{else}
{template}topic_end_fast_reply{/template}
{/if}
<br />

<!-- table -->
<form action="index.php?page=managementreply&amp;do_replys=1&amp;subject_id={$id}" method="post">

<div style="width:98%; border-spacing:0px;border-collapse: collapse;" class="table mrgTable">

{if !{$mod_toolbar}}
<script type='text/javascript'>
function checkAll(form){
for (var i = 0; i < form.elements.length; i++){
eval("form.elements[" + i + "].checked = form.elements[0].checked");
}
}
function AjaxDoReplys(){
const inputs = document.getElementsByName('checkd[]');
const key_values = [];
for(let i=0; i<inputs.length; i++) {
let input = inputs[i];
if (input.type=='checkbox' && input.checked) {
 key_values.push('<input type="hidden" id="docheck" name="check[]" value="'+input.value+'" checked="checked" />');
}
}
return $("#docheck_status").html(key_values.join());
};

</script>
<div style="display: none;" id="docheck_status"></div>
<dl>
<dt></dt>
<dd class="right_text_align mod_box">
{if {$close}}
{if !{$mod_toolbar}}
{template}add_reply_link{/template}
{template}add_subject_link{/template}
{/if}
{else}
{template}add_reply_link{/template}
{template}add_subject_link{/template}
{/if}
</dd>
<dd class="right_text_align mod_box" style="padding-bottom:10px;">
{if {$Info['reply_id']}}
<div class="l-left">
<div class="smallfont" style="text-align: right; white-space: nowrap; float: left;">
<strong>
{$lang['Options_mod']}:
</strong>
<br />
<select name="do" class="dropdown">
<option value="moveposts">{$lang['moveposts']}</option>
<option value="deleteposts">{$lang['del_posts']}</option>
<option value="approveposts">{$lang['approveposts']}</option>
<option value="unapproveposts">{$lang['unapproveposts']}</option>
</select>
<input name="update" class="button button_b" type="submit" id="do_replys" value="{$lang['Count']}" />
</div>
</form>
</div>
{/if}
</dd>
</dl>
{/if}
<dl>
<dt></dt>
<dd class="right_text_align topic_links">

</dd>
<dd class="right_text_align pager_box">
{if {$pager_reply}}
{$pager_reply}
{/if}
</dd>
</dl>
</div>
<!-- /table -->
<br />
{if {$_CONF['info_row']['samesubject_show']}}
{if !{$NO_SAME}}
{template}topic_end_same_subject{/template}
{/if}
{/if}
<br />
{if {$see_who_on_topic}}
{template}show_onlain_topic{/template}
<br />
{/if}
{template}show_tags_topic{/template}
<br />
<div class="smallfont center_text_align">
«
{if {$getpersubject_row} > 0}
<a title="{$getper_title_subject_row}" href="index.php?page=topic&amp;show=1&amp;id={$getpersubject_row}">
{$getper_title_subject_row}</a>
{else}
{$lang['Not_Available']}
{/if}
|
{if {$getnextsubject_row} > 0}
<a title="{$getnext_title_subject_row}" href="index.php?page=topic&amp;show=1&amp;id={$getnextsubject_row}">
{$getnext_title_subject_row}</a>
{else}
{$lang['Not_Available']}
{/if}
»
</div>
<br />
<!-- table --><div style="width:98%; border-spacing:1px;" class="table mrgTable border">
<dl class="center_text_align">
<dt></dt>
<dd class="row1">
{if !{$mod_toolbar}}
{if {$Multi_Moderation}}
<div style="float: right;">
<form action="index.php?page=management&amp;multimod=1&amp;subject_id={$subject_id}" method="post">
<select name="mod_id" class="dropdown">
<option value="-1">
{$lang['Multi_Moderation']}
</option>
{Des::while}{TopicModsList}
<option value="{$TopicModsList['id']}">
{$TopicModsList['title']}
</option>
{/Des::while}
</select>
<input type="submit" value=" {$lang['Count']} " />
</form>
</div>
{/if}
{/if}
{template}jump_forums_list{/template}
</dd>
</dl>
</div><!-- /table -->
<br />
<br />
<br />