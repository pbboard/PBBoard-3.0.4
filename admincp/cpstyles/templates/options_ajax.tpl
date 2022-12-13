<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=options&amp;index=1">{$lang['mange_forum']}</a> &raquo;
<a href="index.php?page=options&amp;ajax=1&amp;main=1">{$lang['mange_ajax']}</a></div>

<br />

<form action="index.php?page=options&amp;ajax=1&amp;update=1" method="post">
<input TYPE="hidden" name="ajax_search" id="select_ajax_search" value="0" />
	<table cellpadding="3" cellspacing="1" width="80%" class="t_style_b" border="0" align="center">
		<tr align="center">
			<td class="main1" colspan="2">
			{$lang['mange_ajax']}
			</td>
		</tr>
<!--
		<tr>
			<td class="row1">
			تنشيطها في صفحة البحث
			</td>
			<td class="row1">
				<select name="ajax_search" id="select_ajax_search">
					{if {$_CONF['info_row']['ajax_search']}}
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
			{$lang['activate_ajax_register']}
			</td>
			<td class="row2">
				<select name="ajax_register" id="select_ajax_register">
					{if {$_CONF['info_row']['ajax_register']}}
					<option value="1" selected="selected">{$lang['yes']}</option>
					<option value="0">{$lang['no']}</option>
					{else}
					<option value="1">{$lang['yes']}</option>
					<option value="0" selected="selected">{$lang['no']}</option>
					{/if}
				</select>
			</td>
		</tr>
-->
		<tr>
			<td class="row1">
			{$lang['ajax_freply']}
			</td>
			<td class="row1">
				<select name="ajax_freply" id="select_ajax_freply">
					{if {$_CONF['info_row']['ajax_freply']}}
					<option value="1" selected="selected">{$lang['yes']}</option>
					<option value="0">{$lang['no']}</option>
					{else}
					<option value="1">{$lang['yes']}</option>
					<option value="0" selected="selected">{$lang['no']}</option>
					{/if}
				</select>
			</td>
		</tr>
		<!--
		<tr>
			<td class="row1">
			{$lang['ajax_moderator_options']}
			</td>
			<td class="row1">
				<select name="ajax_moderator_options" id="select_ajax_moderator_options">
					{if {$_CONF['info_row']['ajax_moderator_options']}}
					<option value="1" selected="selected">{$lang['yes']}</option>
					<option value="0">{$lang['no']}</option>
					{else}
					<option value="1">{$lang['yes']}</option>
					<option value="0" selected="selected">{$lang['no']}</option>
					{/if}
				</select>
			</td>
		</tr>
		-->
		<tr>
			<td class="row1" colspan="2" align="center">
			<input type="submit" value="{$lang['acceptable']}" name="submit" /></td>
		</tr>
	</table>

	<br />
</form>
<br />