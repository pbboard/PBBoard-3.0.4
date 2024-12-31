<script src="includes/js/jquery.js"></script>
<script language="javascript">
function OrderChange()
{
	value = $("#order_type_id").val();

	if (value == 'manual')
	{
		$("#sort_id").show();
	}
	else
	{
		$("#sort_id").hide();
	}
}

function Ready()
{
	value = $("#order_type_id").val();

	if (value == 'manual')
	{
		$("#sort_id").show();
	}
	else
	{
		$("#sort_id").hide();
	}

	$("#order_type_id").change(OrderChange);
}

$(document).ready(Ready);

-->
</script>
	<script type="text/javascript" src="../look/jscolor/jscolor.js"></script>

<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=forums&amp;control=1&amp;main=1">{$lang['Forums']}</a> &raquo;
<a href="index.php?page=forums&amp;add=1&amp;main=1"> {$lang['Add_new_Forum']}</a></div>
<br />

<form action="index.php?page=forums&amp;add=1&amp;start=1" method="post">
	<table width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
		<tr>
			<td class="main1" colspan="2">
			{$lang['Basic_information']}
			</td>
		</tr>
		<tr>
			<td class="row1">
			{$lang['Forum_Name']}
			</td>
			<td class="row1">
				<input type="text" name="name" id="input_name" value="" size="30" />
				<label for="forum_title_color">{$lang['forum_title_color']}:</label>
              <input class="colors" name="forum_title_color" type="text" size="10" />
			</td>
		</tr>
		<tr>
			<td class="row2">
			{$lang['Forum_Order']}
			</td>
			<td class="row2">
				<select name="order_type" id="order_type_id">
					<option value="auto" selected="selected">{$lang['auto_order']}</option>
					<option value="manual">{$lang['manual_order']}</option>
				</select>
				<input type="text" name="sort" id="sort_id" size="5" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^1-9]/g, '');">
			</td>
		</tr>
		<tr>
			<td class="row2">
			{$lang['Forum_Follow_to']}
			</td>
			<td class="row2">
					{$DoJumpList}
			</td>
		</tr>
		<tr>
			<td class="row2">
			{$lang['Forum_describe']}
			</td>
			<td class="row2">
			<textarea name="describe" id="textarea_describe" rows="2" cols="50" dir="{$_CONF['info_row']['content_dir']}"></textarea>
			</td>
		</tr>
	</table>
	<br />

	<table width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
		<tr>
			<td class="main1" colspan="2">
			{$lang['Forum_image']}
			</td>
		</tr>
		<tr>
			<td class="row1">
			{$lang['use_section_picture']}
			</td>
			<td class="row1">
			<label for="use_section_picture">
			<input name="use_section_picture" value="1" tabindex="1" type="radio">{$lang['yes']}
			<input name="use_section_picture" value="0" tabindex="1" type="radio" checked>{$lang['no']}
			</label>
			</td>
		</tr>
		<tr>
			<td class="row2">
			{$lang['Forum_image']}
			</td>
			<td class="row2">
				<input type="text" name="section_picture" size="40" />
			</td>
		</tr>
		<tr>
			<td class="row1">
			{$lang['sectionpicture_type']}
			</td>
			<td class="row1">
			    <label for="sectionpicture_type">
				<input name="sectionpicture_type" value="1" tabindex="1" checked type="radio">{$lang['Place_icon_Forum']}
				<br />
			    <input name="sectionpicture_type" value="2" tabindex="1" type="radio">{$lang['Above_description_Forum']}
			    </label>
			</td>
		</tr>
	</table>

	<br />

	<table width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
		<tr>
			<td class="main1" colspan="2">
			{$lang['Type_Forum']}
			</td>
		</tr>
		<tr>
			<td class="row1">
			{$lang['Forum_as_a_Link']}
			</td>
			<td class="row1">
				<select name="linksection">
					<option value="1">{$lang['yes']}</option>
					<option value="0" selected="selected">{$lang['no']}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td class="row2">
			{$lang['link']}
			</td>
			<td class="row2">
				 <input type="text" name="linksite" size="50" dir="ltr" />
			</td>
		</tr>
	</table>

	<br />
<table width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
		<tr>
			<td class="main1" colspan="2">
			{$lang['prefix_subject']}
			</td>
		</tr>
<tr>
			<td class="row1">
			{$lang['active_prefix_subject']}
			</td>
			<td class="row1">
				<select name="active_prefix_subject">
					<option value="1">{$lang['yes']}</option>
					<option value="0" selected="selected">{$lang['no']}</option>
				</select>
			</td>
		</tr>
<tr valign="top">
<td class="row1">{$lang['Make_every_prefix_subject_in_a_line']}</td>
		<td class="row1">
<textarea name="prefix_subject" id="textarea_prefix_subject" rows="4" cols="50" wrap="virtual" dir="{$_CONF['info_row']['censorwords']}"></textarea>&nbsp;
</td>
</tr>		</tr>
	</table>
<br />
	<table width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
		<tr>
			<td class="main1" colspan="2">
			{$lang['options']}
			</td>
		</tr>
		<tr>
			<td class="row1">
			{$lang['Secret_password_for_the_Forum']}
			</td>
			<td class="row1">
				<input type="text" name="section_password" size="10" dir="ltr" />
			</td>
		</tr>
		<tr>
			<td class="row2">
			{$lang['Display_the_signatures_in_the_Forum']}
			</td>
			<td class="row2">
				<select name="show_sig">
					<option value="1" selected="selected">{$lang['yes']}</option>
					<option value="0">{$lang['no']}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td class="row2">
{$lang['Allow_the_use_BBcode']}
			</td>
			<td class="row2">
				<select name="use_power_code_allow">
					<option value="1" selected="selected">{$lang['yes']}</option>
					<option value="0">{$lang['no']}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td class="row1">
			{$lang['subject_order']}
			</td>
			<td class="row1">
				<select name="subject_order">
					<option value="1" selected="selected">{$lang['His_replies_in_the_new_Supreme']}</option>
					<option value="2">{$lang['New_topic_in_the_Supreme']}</option>
					<option value="3">{$lang['Old_topic_in_the_Supreme']}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td class="row2">
			{$lang['hide_subject']}
			</td>
			<td class="row2">
				<select name="hide_subject">
					<option value="1">{$lang['yes']}</option>
					<option value="0" selected="selected">{$lang['no']}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td class="row1">
			{$lang['sec_section']}
			</td>
			<td class="row1">
				<select name="sec_section">
					<option value="1">{$lang['yes']}</option>
					<option value="0" selected="selected">{$lang['no']}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td class="row1">
			{$lang['review_subject']}
						</td>
			<td class="row1">
				<select name="review_subject">
					<option value="1">{$lang['yes']}</option>
					<option value="0" selected="selected">{$lang['no']}</option>
				</select>
			</td>
		</tr>
	</table>

	<br />

	<table width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
		<tr>
			<td class="main1" colspan="2">
			{$lang['Text_that_appears_top_of_the_page_Forum']}
			 {$lang['you_can_use_HTML']}
			</td>
		</tr>
		<tr align="center">
			<td class="row1" colspan="2">
				<textarea name="head" rows="6" cols="50" wrap="virtual" dir="{$_CONF['info_row']['content_dir']}"></textarea>
			</td>
		</tr>
		<tr>
			<td class="main1" colspan="2">
			{$lang['Text_appears_next_to_the_Forum']}
			{$lang['you_can_use_HTML']}
			</td>
		</tr>
		<tr align="center">
			<td class="row1" colspan="2">
				<textarea name="foot" rows="6" cols="50" wrap="virtual" dir="{$_CONF['info_row']['content_dir']}"></textarea>
			</td>
		</tr>
	</table>

	<br />

	<table width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
		<tr align="center">
			<td class="main1" colspan="5">
			{$lang['Powers_short']}
			</td>
		</tr>
		<tr align="center">
			<td class="main2">
			{$lang['Group']}
			</td>
			<td class="main2">
			{$lang['View_forums']}
			</td>
			<td class="main2">
			{$lang['ViewSubject']}
			</td>
			<td class="main2">
			{$lang['Write_subjects']}
			</td>
			<td class="main2">
			{$lang['write_reply']}
			</td>
		</tr>
		{Des::while}{groups}
		<tr align="center">
			<td class="row1">
				{$groups['title']}
			</td>
			<td class="row2">
			<label for="groups[{$groups['id']}][view_section]">
			{if {$groups['id']} == '6'}
			<input name="groups[{$groups['id']}][view_section]" id="input_view_section" value="1" tabindex="1" type="radio">{$lang['yes']}
			<input name="groups[{$groups['id']}][view_section]" id="input_view_section" value="0" tabindex="1" checked type="radio">{$lang['no']}
			{else}
			<input name="groups[{$groups['id']}][view_section]" id="input_view_section" value="1" tabindex="1" checked type="radio">{$lang['yes']}
			<input name="groups[{$groups['id']}][view_section]" id="input_view_section" value="0" tabindex="1" type="radio">{$lang['no']}
			{/if}
            </label>
			</td>
			<td class="row2">
			<label for="groups[{$groups['id']}][view_subject]">
			{if {$groups['id']} == '6'}
			<input name="groups[{$groups['id']}][view_subject]" id="input_view_subject" value="1" tabindex="1" type="radio">{$lang['yes']}
			<input name="groups[{$groups['id']}][view_subject]" id="input_view_subject" value="0" tabindex="1" checked type="radio">{$lang['no']}
			{else}
			<input name="groups[{$groups['id']}][view_subject]" id="input_view_subject" value="1" tabindex="1" checked type="radio">{$lang['yes']}
			<input name="groups[{$groups['id']}][view_subject]" id="input_view_subject" value="0" tabindex="1" type="radio">{$lang['no']}
			{/if}
            </label>
			</td>
			<td class="row1">
			<label for="groups[{$groups['id']}][write_subject]">
			{if {$groups['id']} == '6'}
			<input name="groups[{$groups['id']}][write_subject]" id="input_write_subject" value="1" tabindex="1" type="radio">{$lang['yes']}
			<input name="groups[{$groups['id']}][write_subject]" id="input_write_subject" value="0" tabindex="1" checked type="radio">{$lang['no']}
		{elseif {$groups['id']} == '7'}
			<input name="groups[{$groups['id']}][write_subject]" id="input_write_subject" value="1" tabindex="1" type="radio">{$lang['yes']}
			<input name="groups[{$groups['id']}][write_subject]" id="input_write_subject" value="0" tabindex="1" checked type="radio">{$lang['no']}
		{elseif {$groups['id']} == '5'}
			<input name="groups[{$groups['id']}][write_subject]" id="input_write_subject" value="1" tabindex="1" type="radio">{$lang['yes']}
			<input name="groups[{$groups['id']}][write_subject]" id="input_write_subject" value="0" tabindex="1" checked type="radio">{$lang['no']}
		{else}
			<input name="groups[{$groups['id']}][write_subject]" id="input_write_subject" value="1" tabindex="1" checked type="radio">{$lang['yes']}
			<input name="groups[{$groups['id']}][write_subject]" id="input_write_subject" value="0" tabindex="1" type="radio">{$lang['no']}
			{/if}
            </label>
			</td>
			<td class="row2">
			<label for="groups[{$groups['id']}][write_reply]">
			{if {$groups['id']} == '6'}
			<input name="groups[{$groups['id']}][write_reply]" id="input_write_reply" value="1" tabindex="1" type="radio">{$lang['yes']}
			<input name="groups[{$groups['id']}][write_reply]" id="input_write_reply" value="0" tabindex="1" checked type="radio">{$lang['no']}
		{elseif {$groups['id']} == '7'}
			<input name="groups[{$groups['id']}][write_reply]" id="input_write_reply" value="1" tabindex="1" type="radio">{$lang['yes']}
			<input name="groups[{$groups['id']}][write_reply]" id="input_write_reply" value="0" tabindex="1" checked type="radio">{$lang['no']}
		{elseif {$groups['id']} == '5'}
			<input name="groups[{$groups['id']}][write_reply]" id="input_write_reply" value="1" tabindex="1" type="radio">{$lang['yes']}
			<input name="groups[{$groups['id']}][write_reply]" id="input_write_reply" value="0" tabindex="1" checked type="radio">{$lang['no']}
		{else}
			<input name="groups[{$groups['id']}][write_reply]" id="input_write_reply" value="1" tabindex="1" checked type="radio">{$lang['yes']}
			<input name="groups[{$groups['id']}][write_reply]" id="input_write_reply" value="0" tabindex="1" type="radio">{$lang['no']}
			{/if}
            </label>
			</td>
		</tr>
		{/Des::while}
		<tr align="center">
		 <td class="row2" colspan="5">
		 <br />
		 <br />
		<input type="submit" value="{$lang['acceptable']}" name="submit" />
		 </td>
		</tr>
	</table>
	<br />
</form>
