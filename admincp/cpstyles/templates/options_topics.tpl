<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=options&amp;index=1">{$lang['mange_forum']}</a> &raquo;
 <a href="index.php?page=options&amp;topics=1&amp;main=1">{$lang['Settings_threads_and_replies']}</a></div>

<br />

<form action="index.php?page=options&amp;topics=1&amp;update=1"  name="myform" method="post">
<table cellpadding="3" cellspacing="1" width="80%" class="t_style_b" border="0" align="center">
<tr valign="top" align="center">
	<td class="main1" colspan="2">{$lang['Settings_threads_and_replies']}</td>
</tr>
<tr valign="top">
		<td class="row1">{$lang['post_text_min']}</td>
		<td class="row1">
<input type="text" name="post_text_min" id="input_post_text_min" value="{$_CONF['info_row']['post_text_min']}" size="2" maxlength="3" />&nbsp;
</td>
</tr>
<tr valign="top">

		<td class="row2">{$lang['post_text_max']}</td>
		<td class="row2">
<input type="text" name="post_text_max" id="input_post_text_max" value="{$_CONF['info_row']['post_text_max']}" size="9" maxlength="9" />&nbsp;
</td>
</tr>
<tr valign="top">
		<td class="row1">{$lang['post_title_min']}</td>
		<td class="row1">
<input type="text" name="post_title_min" id="input_post_title_min" value="{$_CONF['info_row']['post_title_min']}" size="2" maxlength="1" />&nbsp;
</td>
</tr>

<tr valign="top">
		<td class="row2">{$lang['post_title_max']}</td>
		<td class="row2">
<input type="text" name="post_title_max" id="input_post_title_max" value="{$_CONF['info_row']['post_title_max']}" size="2" maxlength="2" />&nbsp;
</td>
</tr>
<tr valign="top">
		<td class="row1">{$lang['time_out']}</td>
		<td class="row1">
<input type="text" name="time_out" id="input_time_out" value="{$_CONF['info_row']['time_out']}" size="4" maxlength="6" />&nbsp;
</td>

</tr>
<tr valign="top">
		<td class="row2">{$lang['floodctrl']}</td>
		<td class="row2">
<input type="text" name="floodctrl" id="input_floodctrl" value="{$_CONF['info_row']['floodctrl']}" size="4" maxlength="6" />&nbsp;
</td>
</tr>
<tr valign="top">
		<td class="row1">{$lang['samesubject_show']}</td>
		<td class="row1">
<select name="samesubject_show" id="select_samesubject_show">
	{if {$_CONF['info_row']['samesubject_show']}}
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
		<td class="row1">{$lang['subject_describe_show']}</td>
		<td class="row1">
<select name="subject_describe_show" id="select_subject_describe_show">
	{if {$_CONF['info_row']['subject_describe_show']}}
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
		<td class="row2">{$lang['show_subject_all']}</td>
		<td class="row2">
<select name="show_subject_all" id="select_show_subject_all">
	{if {$_CONF['info_row']['show_subject_all']}}
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
		<td class="row1">{$lang['resize_imagesAllow']}</td>
		<td class="row1">
<select name="resize_imagesAllow" id="select_resize_imagesAllow">
	{if {$_CONF['info_row']['resize_imagesAllow']}}
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
		<td class="row2">{$lang['default_imagesW']}</td>
		<td class="row2">
<input type="text" name="default_imagesW" id="input_default_imagesW" value="{$_CONF['info_row']['default_imagesW']}" size="5" />&nbsp;
</td>
</tr>
<tr valign="top">
		<td class="row1">{$lang['default_imagesH']}</td>
		<td class="row1">

<input type="text" name="default_imagesH" id="input_default_imagesH" value="{$_CONF['info_row']['default_imagesH']}" size="5" />&nbsp;
</td>
</tr>
<tr valign="top">
		<td class="row2">{$lang['wordwrap']}</td>
		<td class="row2">
<input type="text" name="wordwrap" id="input_wordwrap" value="{$_CONF['info_row']['wordwrap']}" size="5" />&nbsp;
</td>
</tr>

<tr valign="top">
		<td class="row1">{$lang['rating_show']}</td>
		<td class="row1">
<select name="rating_show" id="select_rating_show">
	{if {$_CONF['info_row']['rating_show']}}
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
		<td class="row2">{$lang['show_rating_num_max1']}</td>
		<td class="row2">
{$lang['show_rating_num_max2']}
<input type="text" name="show_rating_num_max" id="input_show_rating_num_max" value="{$_CONF['info_row']['show_rating_num_max']}" size="2" maxlength="2" />
{$lang['show_rating_num_max3']}
</td>
</tr>
<tr valign="top">
			<td class="row1">
{$lang['input_smiles_nm']}
			</td>
			<td class="row1">
<input type="text" name="smiles_nm" id="input_smiles_nm" value="{$_CONF['info_row']['smiles_nm']}" size="2" maxlength="3" />
			</td>
		</tr>
<tr valign="top">
			<td class="row1">
{$lang['input_icons_nm']}
			</td>
			<td class="row1">
<input type="text" name="icons_numbers" id="icons_numbers" value="{$_CONF['info_row']['icons_numbers']}" size="2" maxlength="3" />
			</td>
		</tr>
<tr valign="top">
			<td class="row1">
{$lang['smil_columns_number']}
			</td>
			<td class="row1">
<input type="text" name="smil_columns_number" id="smil_columns_number" value="{$_CONF['info_row']['smil_columns_number']}" size="2" maxlength="3" />
			</td>
		</tr>
<tr valign="top">
			<td class="row1">
{$lang['icon_columns_number']}
			</td>
			<td class="row1">
<input type="text" name="icon_columns_number" id="icon_columns_number" value="{$_CONF['info_row']['icon_columns_number']}" size="2" maxlength="2" />
			</td>
		</tr>
<tr valign="top">
			<td class="row1">
{$lang['active_like_facebook']}
			</td>
<td class="row1">
{if {$_CONF['info_row']['active_like_facebook']}}
<input name="active_like_facebook" value="1" id="active_like_facebook" type="radio" checked="checked">{$lang['yes']}
&nbsp;&nbsp;<input name="active_like_facebook" value="0" id="active_like_facebook" type="radio">{$lang['no']}
{else}
<input name="active_like_facebook" value="1" id="active_like_facebook" type="radio">{$lang['yes']}
&nbsp;&nbsp;<input name="active_like_facebook" value="0" id="active_like_facebook" type="radio" checked="checked">{$lang['no']}
{/if}
</td>
</tr>
<tr valign="top">
			<td class="row1">
{$lang['active_add_this']}
			</td>
<td class="row1">
{if {$_CONF['info_row']['active_add_this']}}
<input name="active_add_this" value="1" id="active_add_this" type="radio" checked="checked">{$lang['yes']}
&nbsp;&nbsp;<input name="active_add_this" value="0" id="active_add_this" type="radio">{$lang['no']}
{else}
<input name="active_add_this" value="1" id="active_add_this" type="radio">{$lang['yes']}
&nbsp;&nbsp;<input name="active_add_this" value="0" id="active_add_this" type="radio" checked="checked">{$lang['no']}
{/if}
</td>
</tr>
<tr valign="top">
			<td class="row1">
{$lang['username_addthis']}
			</td>
			<td class="row1">
<input type="text" name="use_list" id="icon_columns_number" value="{$_CONF['info_row']['use_list']}" size="35" />
			</td>
</tr>
<tr valign="top">
		<td class="row1">{$lang['active']}
		{$lang['Download_Subject']}؟</td>
		<td class="row1">
<select name="download_subject" id="select_rating_show">
	{if {$_CONF['info_row']['download_subject']}}
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
		<td class="row1">{$lang['active']}
		{$lang['print_Subject']}؟</td>
		<td class="row1">
<select name="print_subject" id="select_rating_show">
	{if {$_CONF['info_row']['print_subject']}}
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
		<td class="row1">{$lang['active']}
		{$lang['sendsubjecttofriend']}؟</td>
		<td class="row1">
<select name="send_subject_to_friend" id="select_rating_show">
	{if {$_CONF['info_row']['send_subject_to_friend']}}
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
		<td class="row2" colspan="2" align="center"><input class="submit" type="submit" value="{$lang['acceptable']}" name="submit" accesskey="s" /></td>
</tr>

</table><br />
</form>