<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=options&amp;index=1">{$lang['mange_forum']}</a> &raquo;
 <a href="index.php?page=options&amp;mods=1&amp;main=1">{$lang['Settings_Mods']}</a></div>
<br />
<form action="index.php?page=options&amp;mods=1&amp;update=1" method="post">
<!-- action_find_addons_1 -->
<table cellpadding="3" cellspacing="1" width="80%" class="t_style_b" border="0" align="center">
<!-- action_find_addons_2 -->
		<tr align="center">
			<td class="main1" colspan="2">
{$lang['Settings_last_static']}
			</td>
		</tr>
		<tr>
			<td class="row1">
{$lang['activate_last_static_list']}
<div class="smallfont">
{$lang['server_resource_consumption']}
 </div>
			</td>
			<td class="row1">
				<select name="activate_last_static_list" id="select_activate_last_static_list">
					{if {$_CONF['info_row']['activate_last_static_list']}}
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
{$lang['last_static_num']}
			</td>
			<td class="row2">
<input type="text" name="last_static_num" id="input_last_static_num" value="{$_CONF['info_row']['last_static_num']}" size="2" min="1" max="5" v-model="form.availability" dir="ltr" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');">
			</td>
		</tr>
		<tr>
			<td class="row1">
{$lang['last_posts_static_num']}
			</td>
			<td class="row1">
<input type="text" name="last_posts_static_num" id="input_last_posts_static_num" value="{$_CONF['info_row']['last_posts_static_num']}" dir="ltr" size="2" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');">
			</td>
		</tr>
		<tr align="center">
			<td class="main1" colspan="2">
{$lang['lasts_posts_bar_num']}
			</td>
		</tr>
		<tr>
			<td class="row1">
{$lang['activate_lasts_posts_bar']}
			</td>
			<td class="row1">
				<select name="activate_lasts_posts_bar" id="select_activate_lasts_posts_bar">
					{if {$_CONF['info_row']['activate_lasts_posts_bar']}}
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
{$lang['lasts_posts_num_bar']}
			</td>
			<td class="row2">
<input type="text" name="lasts_posts_bar_num" id="input_lasts_posts_bar_num" value="{$_CONF['info_row']['lasts_posts_bar_num']}" dir="ltr" size="2" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');">
			</td>
		</tr>
		<tr>
			<td class="row1">
{$lang['lasts_posts_bar_dir']}
			</td>
		<td class="row1">
      <select name="lasts_posts_bar_dir" id="select_lasts_posts_bar_dir">
	{if {$_CONF['info_row']['lasts_posts_bar_dir']} == 'right'}
	<option value="right" selected="selected">{$lang['From_right_to_left']}</option>
	<option value="left">{$lang['From_left_to_right']}</option>
	{else}
	<option value="right">{$lang['From_right_to_left']}</option>
	<option value="left" selected="selected">{$lang['From_left_to_right']}</option>
	{/if}
		</tr>
		<tr align="center">
			<td class="main1" colspan="2">
{$lang['Settings_special_bar']}
			</td>
		</tr>
		<tr>
			<td class="row1">
{$lang['activate_special_bar']}
			</td>
			<td class="row1">
				<select name="activate_special_bar" id="select_activate_special_bar">
					{if {$_CONF['info_row']['activate_special_bar']}}
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
{$lang['special_bar_dir']}
			</td>
		<td class="row1">
      <select name="special_bar_dir" id="select_special_bar_dir">
		{if {$_CONF['info_row']['special_bar_dir']} == 'right'}
	<option value="right" selected="selected">{$lang['From_right_to_left']}</option>
	<option value="left">{$lang['From_left_to_right']}</option>
	{else}
	<option value="right">{$lang['From_right_to_left']}</option>
	<option value="left" selected="selected">{$lang['From_left_to_right']}</option>
	{/if}
	</select>
	</td>
		</tr>
		<tr align="center">
			<td class="main1" colspan="2">
{$lang['Settings_ads']}
			</td>
		</tr>
<!--
		<tr>
			<td class="row1">
تنشيط ظهور الإعلان التجاري العشوائي
			</td>
			<td class="row1">
				<select name="random_ads" id="select_random_ads">
					{if {$_CONF['info_row']['random_ads']}}
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
-->
			<td class="row1">
{$lang['show_ads']}
			</td>
		<td class="row1">
<input TYPE="hidden" name="random_ads" id="select_random_ads" value="0" />
      <select name="show_ads" id="select_show_ads">
	{if {$_CONF['info_row']['show_ads']}}
					<option value="1" selected="selected">{$lang['yes']}</option>
					<option value="0">{$lang['no']}</option>
					{else}
					<option value="1">{$lang['yes']}</option>
					<option value="0" selected="selected">{$lang['no']}</option>
					{/if}
				</select>
		</tr>
		<tr align="center">
			<td class="main1" colspan="2">
{$lang['Settings_online_today']}
			</td>
		</tr>
		<tr>
			<td class="row1">
{$lang['show_online_list_today']}
			</td>
			<td class="row1">
				<select name="show_online_list_today" id="select_show_online_list_today">
					{if {$_CONF['info_row']['show_online_list_today']}}
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
{$lang['mor_hours_online_today']}
			</td>
			<td class="row1">
<input type="text" name="mor_hours_online_today" id="input_mor_hours_online_today" value="{$_CONF['info_row']['mor_hours_online_today']}" dir="ltr" size="2" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');">
			</td>
		</tr>
		<tr align="center">
			<td class="main1" colspan="2">
{$lang['Settings_online']}
			</td>
		</tr>
<tr valign="top">
		<td class="row2">{$lang['shwo_guest_online']}</td>
		<td class="row2">
			<select name="show_onlineguest" id="select_show_onlineguest">
				{if {$_CONF['info_row']['show_onlineguest']}}
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
{$lang['mor_seconds_online']}
			</td>
			<td class="row1">
<input type="text" name="mor_seconds_online" id="input_mor_seconds_online" value="{$_CONF['info_row']['mor_seconds_online']}" dir="ltr" size="2" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');">
			</td>
		</tr>
<tr valign="top">
<td class="row1">{$lang['search_engine_spiders']}</td>
		<td class="row1">
<textarea name="search_engine_spiders" id="textarea_search_engine_spiders" rows="6" cols="50" wrap="virtual" dir="ltr">{$_CONF['info_row']['search_engine_spiders']}</textarea>&nbsp;
</td>
</tr>
		<tr align="center">
			<td class="main1" colspan="2">
{$lang['Settings_subject_writer']}
			</td>
		</tr>
		<tr>
			<td class="row1">
{$lang['show_list_last_5_posts_member']}
<div class="smallfont">
{$lang['server_resource_consumption']}
 </div>
			</td>
			<td class="row1">
				<select name="show_list_last_5_posts_member" id="select_show_list_last_5_posts_member">
					{if {$_CONF['info_row']['show_list_last_5_posts_member']}}
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
{$lang['last_subject_writer_nm']}
			</td>
			<td class="row2">
<input type="text" name="last_subject_writer_nm" id="input_last_subject_writer_nm" value="{$_CONF['info_row']['last_subject_writer_nm']}" dir="ltr" size="2" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');">
			</td>
		</tr>
		<tr align="center">
			<td class="main1" colspan="2">
{$lang['Settings_chat_message_bar']}
			</td>
		</tr>
		<tr>
			<td class="row1">
{$lang['activate_chat_bar']}
			</td>
			<td class="row1">
				<select name="activate_chat_bar" id="select_activate_chat_bar">
					{if {$_CONF['info_row']['activate_chat_bar']}}
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
{$lang['chat_message_num']}
			</td>
			<td class="row2">
<input type="text" name="chat_message_num" id="input_chat_message_num" value="{$_CONF['info_row']['chat_message_num']}" dir="ltr" size="2" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');">
			</td>
		</tr>
		<tr>
				<tr>
			<td class="row1">
{$lang['chat_num_mem_posts']}
			</td>
			<td class="row1">
<input type="text" name="chat_num_mem_posts" id="input_chat_num_mem_posts" value="{$_CONF['info_row']['chat_num_mem_posts']}" dir="ltr" size="2" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');">

			</td>
		</tr>
			<tr>
			<td class="row1">
{$lang['chat_num_characters']}
			</td>
			<td class="row1">
<input type="text" name="chat_num_characters" id="input_chat_num_characters" value="{$_CONF['info_row']['chat_num_characters']}" dir="ltr" size="2" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');">
			</td>
		</tr>
				<tr>
			<td class="row1">
{$lang['chat_hide_country']}
			</td>
			<td class="row1">
				<select name="chat_hide_country" id="select_chat_hide_country">
					{if {$_CONF['info_row']['chat_hide_country']}}
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
		<!--
		<tr>
			<td class="row1">
{$lang['chat_bar_dir']}
			</td>
		<td class="row1">
      <select name="chat_bar_dir" id="select_chat_bar_dir">
	{if {$_CONF['info_row']['chat_bar_dir']} == 'out'}
	<option value="out" selected="selected">{$lang['chat_place_out']}</option>
	<option value="inside">{$lang['chat_place_inside']}</option>
	{else}
	<option value="out">{$lang['chat_place_out']}</option>
	<option value="inside" selected="selected">{$lang['chat_place_inside']}</option>
	{/if}
	</select>
	</td>
		</tr>
		!-->
		<tr align="center">
			<td class="main1" colspan="2">
{$lang['Hide_links_for_visitors']}
			</td>
		</tr>
		<tr>
			<td class="row1">
{$lang['Activation_of_hidden_links_on_visitors']}
			</td>
			<td class="row1">
				<select name="haid_links_for_guest" id="haid_links_for_guest">
					{if {$_CONF['info_row']['haid_links_for_guest']}}
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
<td class="row1">{$lang['guest_message_for_haid_links']}</td>
		<td class="row1">
<textarea name="guest_message_for_haid_links" id="textarea_guest_message_for_haid_links" rows="4" cols="50" wrap="virtual" dir="{$_CONF['info_row']['guest_message_for_haid_links']}">{$_CONF['info_row']['guest_message_for_haid_links']}</textarea>&nbsp;
</td>
</tr>
		</tr>
		<tr align="center">
			<td class="main1" colspan="2">
{$lang['tags_automatic']}
			</td>
		</tr>
		<tr>
			<td class="row1">
{$lang['add_tags_automatic']}
			</td>
			<td class="row1">
				<select name="add_tags_automatic" id="select_add_tags_automatic">
					{if {$_CONF['info_row']['add_tags_automatic']}}
					<option value="1" selected="selected">{$lang['yes']}</option>
					<option value="0">{$lang['no']}</option>
					{else}
					<option value="1">{$lang['yes']}</option>
					<option value="0" selected="selected">{$lang['no']}</option>
					{/if}
				</select>
			</td>
		</tr>

		<tr align="center">
			<td class="main1" colspan="2">
{$lang['color_groups_of_members']}
			</td>
		</tr>
		<tr>
			<td class="row1" name="25">
<a id="25" name="25"></a>
{$lang['view_group_username_style']}
<div class="smallfont" name="25">
{$lang['server_resource_consumption']}
 </div>
			</td>
			<td class="row1">
				<select name="get_group_username_style" id="select_get_group_username_style">
					{if {$_CONF['info_row']['get_group_username_style']}}
					<option value="1" selected="selected">{$lang['yes']}</option>
					<option value="0">{$lang['no']}</option>
					{else}
					<option value="1">{$lang['yes']}</option>
					<option value="0" selected="selected">{$lang['no']}</option>
					{/if}
				</select>
			</td>
		</tr>
		<tr align="center">
			<td class="main1" colspan="2">
{$lang['how_many_entries_error_num']}
			</td>
		</tr>
			<tr>
			<td class="row1">
{$lang['how_many_entries_error_num']}
			</td>
			<td class="row1">
<input type="text" name="num_entries_error" id="input_num_entries_error" value="{$_CONF['info_row']['num_entries_error']}" dir="ltr" size="2" min="1" max="5" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');">
			</td>
		</tr>
<!-- action_find_addons_3 -->
<?php $PowerBB->functions->get_hooks_template("options_mods_1")?>
</table>
<!-- action_find_addons_4 -->
<?php $PowerBB->functions->get_hooks_template("options_mods_2")?>
<br />
<table cellpadding="3" cellspacing="1" width="80%" class="t_style_b" border="0" align="center">
	<tr valign="top">
		<td class="row2" colspan="2" align="center">
		<input class="submit" type="submit" value="   {$lang['Save']}  " name="mods_form_submit" accesskey="s" />
		</td>
	</tr>
</table>
<br />

</form>