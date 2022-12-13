{if {$_CONF['info_row']['ajax_moderator_options']}}
<script language="javascript">
function AjaxModerator()
{
var AjaxState = {ajaxSend : $("#statusmod").html("<div class='info_bar'> <img class='brd0' src='{$image_path}/loading.gif' alt='' /></div>")};
var data = {};
data['section']		=	$("#section_i").val();
data['subject']		=	$("#subject_i").val();
data['oper']		=	$("#operator_i").val();	$.post("index.php?page=ajax&management=1",data,function Success(xml) { $("#statusmod").html(xml); });
}
function AjaxClose()
{
var AjaxState = {ajaxSend : $("#statusmod").html("<div class='info_bar'> <img class='brd0' src='{$image_path}/loading.gif' alt='' /></div>")};
var data = {};
data['section']		=	$("#section_i").val();
data['subject']		=	$("#subject_i").val();
data['reason']		=	$("#reason").val();
data['oper']		=	'close';	$.post("index.php?page=ajax&management=1&second=1",data,function Success(xml) { $("#statusmod").html(xml); });
}
function Ready()
{
$("#control_id").click(AjaxModerator);
$("#close_id").click(AjaxClose);
}
$(document).ready(Ready);
</script>
{/if}
{if !{$_CONF['info_row']['ajax_moderator_options']}}
<form method="get" action="index.php">
{/if}
<input type="hidden" name="page" value="management" />
<input type="hidden" name="subject" value="1" />
<input type="hidden" name="section" id="section_i" value="{$Subjectinfo['section']}" />
<input type="hidden" name="subject_id" id="subject_i" value="{$Subjectinfo['id']}" />
<!-- table --><div style="border-collapse: collapse;" class="table">
<dl>
<dt></dt>
<dd>
<select name="operator" id="operator_i" size="12" tabindex="1">
{if {$Subjectinfo['stick']} == 0}
<option value="stick">
{$lang['Sticky_Topic']}
</option>
{/if}
{if {$Subjectinfo['stick']} == 1}
<option value="unstick">
{$lang['unstick_Subject']}
</option>
{/if}
{if {$Subjectinfo['close']} == 0}
<option value="close">
{$lang['locked_Topic']}
</option>
{/if}
{if {$Subjectinfo['close']} == 1}
<option value="open">
{$lang['open_Subject']}
</option>
{/if}
{if {$_CONF['rows']['group_info']['del_subject']}}
<option value="delete">
{$lang['delete_subject']}
</option>
{/if}
<option value="move">
{$lang['move_Subject']}
</option>
<option value="edit">
{$lang['edit_Subject']}
</option>
<option value="repeated">
{$lang['repeated_Subject']}
</option>
<option value="up">
{$lang['up_Subject']}
</option>
<option value="down">
{$lang['down_Subject']}
</option>
{if {$SubjectInfo['review_subject']} == 1}
<option value="unreview_subject">
{$lang['unreview_subject']}
</option>
{/if}
{if {$SubjectInfo['review_subject']} == 0}
<option value="review_subject">
{$lang['review_subject']}
</option>
{/if}
{if {$Subjectinfo['special']} == 0}
<option value="special">
{$lang['special_subject']}
</option>
{/if}
{if {$Subjectinfo['special']} == 1}
<option value="nospecial">
{$lang['nospecial_subject']}
</option>
{/if}
<option value="merge">{$lang['Merge_topics']}</option>
</select>
</dd>
</dl>
<dl>
<dt></dt>
<dd class="center_text_align">
{if {$_CONF['info_row']['ajax_moderator_options']}}
<input type="button" name="control" id="control_id" value="{$lang['Count']}" />
{else}
<input type="submit" value="{$lang['Count']}" />
{/if}
</dd>
</dl>
</div><!-- /table -->
{if {$_CONF['info_row']['ajax_moderator_options']}}
<br />
<div class="center_text_align" id="statusmod"></div>
{/if}
</form>