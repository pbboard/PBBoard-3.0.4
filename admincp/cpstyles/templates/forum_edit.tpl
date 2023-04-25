<br />
	<script type="text/javascript" src="../look/jscolor/jscolor.js"></script>

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=forums&amp;control=1&amp;main=1">{$lang['Forums']}</a> &raquo;
<a href="index.php?page=forums&amp;edit=1&amp;main=1&amp;id={$Inf['id']}">
 {$lang['edit']} :
 {$Inf['title']}</a></div>
<br />

<form action="index.php?page=forums&amp;edit=1&amp;start=1&amp;id={$Inf['id']}" method="post">
	<table width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
		<tr>
			<td class="main1" colspan="2">
			{$lang['Basic_information']}
			</td>
		</tr>
		<tr>
			<td class="row1">
			{$lang['Forum_title']}
			</td>
			<td class="row1">
				<input type="text" name="name" value="{$Inf['title']}" size="50" />
				<label for="forum_title_color">{$lang['forum_title_color']}:</label>
              <input class="colors" name="forum_title_color" type="text" value="{$Inf['forum_title_color']}" size="10" />
			</td>
		</tr>
		<tr>
			<td class="row2">
			{$lang['Forum_Order']}
			</td>
			<td class="row2">
				<input type="text" name="sort" value="{$Inf['sort']}" size="5" />
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
			<textarea name="describe" id="textarea_describe" rows="2" cols="50" dir="{$_CONF['info_row']['content_dir']}">{$Inf['section_describe']}</textarea>
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
{if {$Inf['use_section_picture']}}
            <label for="use_section_picture">
			<input name="use_section_picture" value="1" tabindex="1" checked type="radio">{$lang['yes']}
			<input name="use_section_picture" value="0" tabindex="1" type="radio">{$lang['no']}
			</label>
			{else}
			<label for="use_section_picture">
			<input name="use_section_picture" value="1" tabindex="1" type="radio">{$lang['yes']}
			<input name="use_section_picture" value="0" tabindex="1" type="radio" checked>{$lang['no']}
			</label>
			{/if}
			</td>
		</tr>
		<tr>
			<td class="row2">
			{$lang['Forum_image']}
			</td>
			<td class="row2">
				<input type="text" name="section_picture" value="{$Inf['section_picture']}" size="40" />
			</td>
		</tr>
		<tr>
			<td class="row1">
			{$lang['sectionpicture_type']}
			</td>
			<td class="row1">
				{if {$Inf['sectionpicture_type']} == '1'}
			    <label for="sectionpicture_type">
				<input name="sectionpicture_type" value="1" tabindex="1" checked type="radio">{$lang['Place_icon_Forum']}
				<br />
			    <input name="sectionpicture_type" value="2" tabindex="1" type="radio">{$lang['Above_description_Forum']}
			    </label>
				{elseif {$Inf['sectionpicture_type']} == 2}
			    <label for="sectionpicture_type">
				<input name="sectionpicture_type" value="1" tabindex="1" type="radio">{$lang['Place_icon_Forum']}
				<br />
			    <input name="sectionpicture_type" value="2" tabindex="1" checked type="radio">{$lang['Above_description_Forum']}
			    </label>
			    {/if}
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
				{if {$Inf['linksection']}}
					<option value="1" selected="selected">{$lang['yes']}</option>
					<option value="0">{$lang['no']}</option>
				{else}
					<option value="1">{$lang['yes']}</option>
					<option value="0" selected="selected">{$lang['no']}</option>
				{/if}
				</select>
			</td>
		</tr>
		<tr>
			<td class="row2">
			{$lang['link']}
			</td>
			<td class="row2">
				 <input type="text" name="linksite" value="{$Inf['linksite']}" size="50" dir="ltr" />
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
				{if {$Inf['active_prefix_subject']}}
					<option value="1" selected="selected">{$lang['yes']}</option>
					<option value="0">{$lang['no']}</option>
				{else}
					<option value="1">{$lang['yes']}</option>
					<option value="0" selected="selected">{$lang['no']}</option>
				{/if}
				</select>
			</td>
		</tr>
<tr valign="top">
<td class="row1">{$lang['Make_every_prefix_subject_in_a_line']}</td>
		<td class="row1">
<textarea name="prefix_subject" id="textarea_prefix_subject" rows="4" cols="50" wrap="virtual" dir="{$_CONF['info_row']['censorwords']}">{$Inf['prefix_subject']}</textarea>&nbsp;
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
				<input type="text" name="section_password" value="{$Inf['section_password']}" size="10" dir="ltr" />
			</td>
		</tr>
		<tr>
			<td class="row2">
			{$lang['Display_the_signatures_in_the_Forum']}
			</td>
			<td class="row2">
				<select name="show_sig">
				{if {$Inf['show_sig']}}
					<option value="1" selected="selected">{$lang['yes']}</option>
					<option value="0">{$lang['no']}</option>
				{else}
					<option value="1">{$lang['yes']}</option>
					<option value="0" selected="selected">{$lang['no']}</option>
				{/if}
				</select>
			</td>
		</tr>
		<tr>
			<td class="row2">
{$lang['Allow_the_use_BBcode']}
			</td>
			<td class="row2">
				<select name="use_power_code_allow">
				{if {$Inf['use_power_code_allow']}}
					<option value="1" selected="selected">{$lang['yes']}</option>
					<option value="0">{$lang['no']}</option>
				{else}
					<option value="1">{$lang['yes']}</option>
					<option value="0" selected="selected">{$lang['no']}</option>
				{/if}
				</select>
			</td>
		</tr>
		<tr>
			<td class="row1">
			{$lang['subject_order']}
			</td>
			<td class="row1">
				<select name="subject_order">
				{if {$Inf['subject_order']} == 1}
					<option value="1" selected="selected">{$lang['His_replies_in_the_new_Supreme']}</option>
					<option value="2">{$lang['New_topic_in_the_Supreme']}</option>
					<option value="3">{$lang['Old_topic_in_the_Supreme']}</option>
				{elseif {$Inf['subject_order']} == 2}
					<option value="1">{$lang['His_replies_in_the_new_Supreme']}</option>
					<option value="2" selected="selected">{$lang['New_topic_in_the_Supreme']}</option>
					<option value="3">{$lang['Old_topic_in_the_Supreme']}</option>
				{elseif {$Inf['subject_order']} == 3}
					<option value="1">{$lang['His_replies_in_the_new_Supreme']}</option>
					<option value="2">{$lang['New_topic_in_the_Supreme']}</option>
					<option value="3" selected="selected">{$lang['Old_topic_in_the_Supreme']}</option>
				{/if}
				</select>
			</td>
		</tr>
		<tr>
			<td class="row2">
			{$lang['hide_subject']}
			</td>
			<td class="row2">
				<select name="hide_subject">
				{if {$Inf['hide_subject']}}
					<option value="1" selected="selected">{$lang['yes']}</option>
					<option value="0">{$lang['no']}</option>
				{else}
					<option value="1">{$lang['yes']}</option>
					<option value="0" selected="selected">{$lang['no']}</option>
				{/if}
				</select>
			</td>
		</tr>
		<tr>
			<td class="row1">
			{$lang['sec_section']}
			</td>
			<td class="row1">
				<select name="sec_section">
				{if {$Inf['sec_section']}}
					<option value="1" selected="selected">{$lang['yes']}</option>
					<option value="0">{$lang['no']}</option>
				{else}
					<option value="1">{$lang['yes']}</option>
					<option value="0" selected="selected">{$lang['no']}</option>
				{/if}
				</select>
			</td>
		</tr>
		<tr>
			<td class="row1">
			{$lang['review_subject']}
						</td>
			<td class="row1">
				<select name="review_subject">
				{if {$Inf['review_subject']}}
					<option value="1" selected="selected">{$lang['yes']}</option>
					<option value="0">{$lang['no']}</option>
				{else}
					<option value="1">{$lang['yes']}</option>
					<option value="0" selected="selected">{$lang['no']}</option>
				{/if}
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
				<textarea name="head" rows="6" cols="50" wrap="virtual" dir="{$_CONF['info_row']['content_dir']}">{$Inf['header']}</textarea>
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
				<textarea name="foot" rows="6" cols="50" wrap="virtual" dir="{$_CONF['info_row']['content_dir']}">{$Inf['footer']}</textarea>
			</td>
		</tr>
           <tr align="center">
		 <td class="row2" colspan="2">
		 <br />
		 <br />
		<input type="submit" value="{$lang['acceptable']}" name="submit" />
		 </td>
		</tr>
	</table>
</form>

<br />
<br />
