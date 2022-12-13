{if {$SectionGroup['write_reply']} == '1'}
{if {$_CONF['info_row']['fastreply_allow']} == 1}
{if {$_CONF['info_row']['ajax_freply']}}
<script type='text/javascript'>
  function AjaxReply()
  {
	var textarea = document.getElementById("box_text");
	var instance = sceditor.instance(textarea);
	if ($.trim(instance.val())){
	$( document ).ajaxSend(function() {
	$("#status").html("<div class='info_bar'> <img class='brd0' src='{$image_path}/loading.gif' alt='' /></div>");
	 $("#fast_reply_id").slideToggle("slow");
	});
	var data = {};
	data['title']    =    $("#title_id").val();
    data['text']     =    instance.val();
	data['icon']    =    $(".fp1").val();
	data['count']    =    $("#count_id").val();
	data['guest_name']    =    $("#guest_name").val();
	data['code']    =    $("#code_confirm").val();
	data['code_answer']    =    $("#code_answer").val();
	data['icon']    =    $(".fp1").val();
	data['ajax']    =    1;
	$.post("index.php?page=new_reply&start=1&id={$id}{$password}",data,
	function Success(xml) {
	$("#status").html(xml);
    });
	} else {
	alert("{$lang['Please_fill_in_all_the_information']}");
	}

  }
</script>
{/if}
<br />
<div id="fast_reply_id">
<form name="topic" method="post" action="index.php?page=new_reply&amp;start=1&amp;id={$id}{$password}">
<!-- table -->
<div style="width:98%; border-spacing:1px;" class="table mrgTable">
<dl>
<dt></dt>
<dd class="tcat">
{$lang['fast_reply']}
</dd>
</dl>
</div>
<!-- /table -->
<!-- table -->
<div style="width:98%; border-spacing:1px;" class="table mrgTable">
{if !{$_CONF['member_permission']}}
<dl>
<dt></dt>
<dd class="row1 rows_space">
{$lang['Your_name']}
<input name="guest_name" id="guest_name" type="text" size="35"/>
</dd>
</dl>
{if {$_CONF['info_row']['captcha_type']} == 'captcha_IMG'}
<dl class="center_text_align">
<dt></dt>
<dd class="row1 rows_space">
{$lang['Image_Verification']}:
<span style="font-size:10px;">
{$lang['Verification']}
</span>
<br />
<input name="code" id="code_confirm" type="text" size="7" />
<img alt="" id="turing" src="includes/captcha.php" width="87" height="17" />
<a onclick="updateImg();">
{$lang['Image_replacement']}
</a>
<script type='text/javascript'>
var clicks = 0;
function updateImg()
{
clicks++
var doc = document.getElementById("turing");
doc.src = "includes/captcha.php" + "?act=" + clicks;
}
</script>
</dd>
</dl>
{else}
<dl>
<dt></dt>
<dd class="row1 rows_space">
{$lang['random_question']}
<span class="smallfont" dir="{$_CONF['info_row']['content_dir']}" title="{$lang['question']}">
{$question}
<u title="{$lang['correct_answer']}">
{$answer}
</u>
</span>
<input name="code" id="code_confirm" type="text" size="40" dir="{$_CONF['info_row']['content_dir']}" />
<input value="{$answer}" type="hidden" name="code_answer" id="code_answer" />
</dd>
</dl>
{/if}
{/if}
</div><!-- /table -->
<!-- table -->
<div style="width:98%; border-spacing:1px;" class="table mrgTable">
<dl class="right_text_align">
<dt></dt>
<dd class="row1">
{if {$_CONF['info_row']['title_quote']} == 1}
<input TYPE="hidden"  name="title" id="title_id" value="{$reply_title}" />
{else}
<input TYPE="hidden"  name="title" id="title_id" value="" />
{/if}
</dd>
</dl>
<dl class="right_text_align">
<dt></dt>
<dd class="row1 rows_space">
<!-- table -->
<div style="border-spacing:1px;border-collapse: collapse;" class="table mrgTable cked_fast">
<dl>
<dt></dt>
<dd class="row1 right_text_align va-t">
{if {$_CONF['info_row']['toolbox_show']} == 1}
{template}fast_reply_js{/template}
{/if}
{if {$_CONF['info_row']['ajax_freply']} == '0'}
{if {$_CONF['info_row']['allowed_emailed']} == '1' AND {$_CONF['member_permission']}}
<input name="emailed" type="checkbox" />
{$lang['Enabled_to_be_notified_by_the_existence_of_new_replies']}</a>
{/if}
{/if}
<input TYPE="hidden" value="{$_CONF['info_row']['icon_path']}i1.gif" name="icon" class="fp1" />
</dd>
</dl>
</div><!-- /table -->
</dd>
</dl>
<dl class="right_text_align">
<dt></dt>
<dd class="row1 center_text_align va-t">
{if {$_CONF['info_row']['ajax_freply']}}
<input name="insert" class="button button_b" type="button" id="reply_id" OnClick="AjaxReply()" value="{$lang['Add_fast_reply']}" />
{else}
<input class="button button_b" name="insert" type="submit" value="{$lang['Add_fast_reply']}" />
{/if}
<input class="button button_b" type="submit" value="{$lang['Jump_to_advanced_editor']}" name="preview" />
</dd>
</dl>
</div><!-- /table -->
<br />
{if {$_CONF['info_row']['ajax_freply']} == '0'}
{if {$_CONF['info_row']['activate_closestick']} == 1}
{if {$Admin}}
<!-- table --><div style="width:98%; border-spacing:1px;" class="border table mrgTable">
<dl>
<dt></dt>
<dd class="tcat">
{$lang['Management_options_subject']}
</dd>
</dl>
<dl>
<dt></dt>
<dd class="row2">
{if {$Info_row['stick']}}
<input name="unstick" id="stick_id" type="checkbox" />
<label for="stick_id">
{$lang['unstick_Subject']}
</label>
{else}
<input name="stick" id="stick_id" type="checkbox" />
<label for="stick_id">
{$lang['Sticky_Topic']}
</label>
{/if}
<br />
{if {$close}}
<input name="unclose" id="close_id" type="checkbox" />
<label for="close_id">
{$lang['open_Subject']}
</label>
{else}
<input name="close" id="close_id" type="checkbox" />
<label for="close_id">
{$lang['locked_Topic']}
</label>
{/if}
</dd>
</dl>
</div><!-- /table -->
{/if}
{/if}
{/if}
<br />
</form>
</div>
{/if}
{/if}
<br />
