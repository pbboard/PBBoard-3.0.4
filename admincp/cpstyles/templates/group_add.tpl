<script src="includes/js/jquery.js"></script>
<script language="javascript">
function OrderChange()
{
	value = $("#order_type_id").val();

	if (value == 'manual')
	{
		$("#group_order_id").show();
	}
	else
	{
		$("#group_order_id").hide();
	}
}

function Ready()
{
	value = $("#order_type_id").val();

	if (value == 'manual')
	{
		$("#group_order_id").show();
	}
	else
	{
		$("#group_order_id").hide();
	}

	$("#order_type_id").change(OrderChange);
}

$(document).ready(Ready);

</script>
	<script type="text/javascript" src="../look/jscolor/jscolor.js"></script>
<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=groups&amp;control=1&amp;main=1">{$lang['groups']}</a> &raquo;
{$lang['Add_new_group']}</div>

<br />

<form action="index.php?page=groups&amp;add=1&amp;start=1" method="post">
	<table width="70%" class="t_style_b" border="0" cellspacing="1" align="center">
		<tr>
			<td class="main1" colspan="2">
			{$lang['Add_new_group']}
			</td>
		</tr>
		<tr>
			<td class="row1">
			{$lang['Group_name']} *
			</td>
			<td class="row1">
				<input type="text" name="name" size="40" />
			</td>
		</tr>
		<tr>
			<td class="row2">
			{$lang['username_color']} <br />
{$lang['Replace_color_to']}
<br><br>
        <a target="_blank" href="index.php?page=options&mods=1&main=1#25">
<b><u>{$lang['view_group_username_style']}</u></b></a>
			</td>
			<td class="row2">
الحد الأقصى 			100 حرف
<br />
			<textarea name="style" dir="ltr" class="inputbox" tabindex="3" rows="3" cols="40" ><span style="color: #800000;">[username]</span></textarea>
			</td>
		</tr>
		<tr>
			<td class="row1">
			{$lang['usertitle']} *
			</td>
			<td class="row1">
الحد الأقصى 			100 حرف
<br />
			<textarea name="usertitle" class="inputbox" tabindex="3" rows="3" cols="40" ></textarea>
			</td>
		</tr>
		<tr>
			<td class="row2">
			{$lang['group_order']} *
			<td class="row2">
				<select name="order_type" id="order_type_id">
					<option value="auto" selected="selected">{$lang['auto_order']}</option>
					<option value="manual">{$lang['manual_order']}</option>
				</select>
				<input type="text" name="group_order" id="group_order_id" size="1" min="1" max="1" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');">

			</td>

		</tr>

<tr>
<td class="row1">
اجعل صلاحيات وتراخيص جميع المنتديات والاقسام لهذه المجموعة مطابقة لمجموعة
</td>
<td class="row1">
<select name="usergroup" id="select_usergroup">
{Des::while}{GroupList}
{if {$GroupList['id']} == '4' }
<option value="{$GroupList['id']}" selected="selected">{$GroupList['title']}</option>
{else}
<option value="{$GroupList['id']}">{$GroupList['title']}</option>
{/if}
{/Des::while}
</select>
</td>
</tr>

		<tr>
			<td class="row1">
			{$lang['forum_team']}
			</td>
			<td class="row1">
				<select size="1" name="forum_team">
					<option value="1">{$lang['yes']}</option>
					<option selected="selected" value="0">{$lang['no']}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td class="row2">
			{$lang['group_is_banned']}
			</td>
			<td class="row2">
				<select size="1" name="banned">
					<option  value="1">{$lang['yes']}</option>
					<option selected="selected" value="0">{$lang['no']}</option>
				</select>

			</td>
		</tr>
		<tr>
			<td class="main1" colspan="2">
			{$lang['forums_Properties']}
			</td>
		</tr>
		<tr>
			<td class="row1">
			{$lang['view_sections']}
			</td>
			<td class="row1">
				<select size="1" name="view_section">
					<option selected="selected" value="1">{$lang['yes']}</option>
					<option value="0">{$lang['no']}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td class="row1">
			{$lang['view_subject']}
			</td>
			<td class="row1">
				<select size="1" name="view_subject">
					<option selected="selected" value="1">{$lang['yes']}</option>
					<option value="0">{$lang['no']}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td class="row2">
			{$lang['download_attach']}
			</td>
			<td class="row2">
		 		<select size="1" name="download_attach">
					<option selected="selected" value="1">{$lang['yes']}</option>
					<option value="0">{$lang['no']}</option>
		 		</select>
			</td>
		</tr>
		<tr>
			<td class="row1">
			{$lang['user_download_attach_number']}
			</td>
			<td class="row1">
				<input name="download_attach_number" type="text" value="0" size="1" min="1" max="1" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');" />
			</td>
		</tr>
		<tr>
			<td class="row2">
			{$lang['upload_attach']}
			</td>
			<td class="row2">
				<select size="1" name="upload_attach">
					<option selected="selected" value="1">{$lang['yes']}</option>
					<option value="0">{$lang['no']}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td class="row1">
{$lang['upload_attach_num']}
			</td>
			<td class="row1">
				<input name="upload_attach_num" type="text" value="0" size="1" min="1" max="1" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');">
			</td>
		</tr>
		<tr>
			<td class="row2">
			{$lang['Write_subjects']}
			</td>
			<td class="row2">
				<select size="1" name="write_subject">
					<option selected="selected" value="1">{$lang['yes']}</option>
					<option value="0">{$lang['no']}</option>
				</select>
			</td>

		</tr>
		<tr>
		<td class="row1">{$lang['write_reply']}</td>
		<td class="row1">
		 <select size="1" name="write_reply">
		  <option selected="selected" value="1">{$lang['yes']}</option>
		  <option value="0">{$lang['no']}</option>

		 </select>
		</td>
		</tr>
		<tr>
		<td class="row2">{$lang['edit_own_subject']}</td>
		<td class="row2">
		 <select size="1" name="edit_own_subject">
		  <option value="1">{$lang['yes']}</option>
		  <option selected="selected" value="0">{$lang['no']}</option>
		 </select>
		</td>
		</tr>
		<tr>
		<td class="row1">{$lang['edit_own_reply']}</td>
		<td class="row1">
		 <select size="1" name="edit_own_reply">

		  <option selected="selected" value="1">{$lang['yes']}</option>
		  <option value="0">{$lang['no']}</option>
		 </select>
		</td>
		</tr>
		<tr>
		<td class="row2">{$lang['del_own_subject']}</td>

		<td class="row2">
		 <select size="1" name="del_own_subject">
		  <option value="1">{$lang['yes']}</option>
		  <option selected="selected" value="0">{$lang['no']}</option>
		 </select>
		</td>
		</tr>
		<tr>

		<td class="row1">{$lang['del_own_reply']}</td>
		<td class="row1">
		 <select size="1" name="del_own_reply">
		  <option value="1">{$lang['yes']}</option>
		  <option selected="selected" value="0">{$lang['no']}</option>
		 </select>
		</td>

		</tr>
		<tr>
		<td class="row2">{$lang['write_poll']}</td>
		<td class="row2">
		 <select size="1" name="write_poll">
		  <option selected="selected" value="1">{$lang['yes']}</option>
		  <option value="0">{$lang['no']}</option>
		 </select>
		</td>
		</tr>
		<tr>
		<td class="row1">{$lang['vote_poll']}</td>
		<td class="row1">
		 <select size="1" name="vote_poll">
		  <option selected="selected" value="1">{$lang['yes']}</option>
		  <option value="0">{$lang['no']}</option>
		 </select>
		</td>
		</tr>
     <tr>
       <td class="row2">{$lang['see_who_on_topic']}</td>
       <td class="row2">
        <select size="1" name="see_who_on_topic">
        <option selected="selected" value="1">{$lang['yes']}</option>
        <option value="0">{$lang['no']}</option>
        </select>
       </td>
     </tr>
      <tr>
        <td class="row1">{$lang['user_topic_day_number']}</td>
        <td class="row1"><input name="topic_day_number" type="text" value="0" size="1" min="1" max="1" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');"></td>
      </tr>
		<tr>
		<td class="main1" colspan="2">{$lang['Properties_Private_Messages']}</td>
		</tr>
		<tr>

		<td class="row1">{$lang['use_pm']}</td>
		<td class="row1">
		 <select size="1" name="use_pm">
		  <option selected="selected" value="1">{$lang['yes']}</option>
		  <option value="0">{$lang['no']}</option>
		 </select>
		</td>
		<tr>
		<td class="row2">{$lang['max_pm']}
        <br />
{$lang['Set_0_for_an_unlimited_number_of_messages']}
		</td>
		<td class="row2"><input type="text" name="max_pm" value="0" size="1" min="1" max="1" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');"></td>
		</tr>

		<tr>
		<td class="row1">{$lang['min_send_pm']}</td>
		<td class="row1"><input type="text" name="min_send_pm" value="0" size="1" min="1" max="1" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');"></td>
		</tr>
		<tr>
		<td class="main1" colspan="2">{$lang['Properties_sig']}</td>
		</tr>
		<tr>

		<td class="row1">{$lang['sig_allow']}</td>
		<td class="row1">
		 <select size="1" name="sig_allow">
		  <option selected="selected" value="1">{$lang['yes']}</option>
		  <option value="0">{$lang['no']}</option>
		 </select>
		</td>

		</tr>
		<tr>
		<td class="row2">{$lang['sig_len']}</td>
		<td class="row2"><input type="text" name="sig_len" value="1000" size="1" min="1" max="1" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');"></td>
		</tr>

		<tr>
		<td class="main1" colspan="2">{$lang['Properties_mod']}</td>

		</tr>
		<tr>
		<td class="row1">{$lang['group_mod']}</td>
		<td class="row1">
		 <select size="1" name="group_mod">
		  <option value="1">{$lang['yes']}</option>
		  <option selected="selected" value="0">{$lang['no']}</option>

		 </select>
		</td>
		</tr>
		<tr>
		<td class="row2">{$lang['group_vice']}</td>
		<td class="row2">
		 <select size="1" name="vice">
		  <option value="1">{$lang['yes']}</option>

		  <option selected="selected" value="0">{$lang['no']}</option>
		 </select>
		</td>
		</tr>
		<tr>
		<td class="row1">{$lang['edit_subject']}</td>
		<td class="row1">
		 <select size="1" name="edit_subject">

		  <option value="1">{$lang['yes']}</option>
		  <option selected="selected" value="0">{$lang['no']}</option>
		 </select>
		</td>
		</tr>
		<tr>
		<td class="row2">{$lang['edit_reply']}</td>

		<td class="row2">
		 <select size="1" name="edit_reply">
		  <option value="1">{$lang['yes']}</option>
		  <option selected="selected" value="0">{$lang['no']}</option>
		 </select>
		</td>
		</tr>
		<tr>

		<td class="row1">{$lang['del_subject']}</td>
		<td class="row1">
		 <select size="1" name="del_subject">
		  <option value="1">{$lang['yes']}</option>
		  <option selected="selected" value="0">{$lang['no']}</option>
		 </select>
		</td>

		</tr>
		<tr>
		<td class="row2">{$lang['del_reply']}</td>
		<td class="row2">
		 <select size="1" name="del_reply">
		  <option value="1">{$lang['yes']}</option>
		  <option selected="selected" value="0">{$lang['no']}</option>

		 </select>
		</td>
		</tr>
		<tr>
		<td class="row1">{$lang['stick_subject']}</td>
		<td class="row1">
		 <select size="1" name="stick_subject">
		  <option value="1">{$lang['yes']}</option>
		  <option selected="selected" value="0">{$lang['no']}</option>
		 </select>
		</td>
		</tr>
		<tr>
		<td class="row2">{$lang['unstick_subject']}</td>
		<td class="row2">
		 <select size="1" name="unstick_subject">

		  <option value="1">{$lang['yes']}</option>
		  <option selected="selected" value="0">{$lang['no']}</option>
		 </select>
		</td>
		</tr>
		<tr>
		<td class="row1">{$lang['move_subject']}</td>

		<td class="row1">
		 <select size="1" name="move_subject">
		  <option value="1">{$lang['yes']}</option>
		  <option selected="selected" value="0">{$lang['no']}</option>
		 </select>
		</td>
		</tr>
		<tr>

		<td class="row2">{$lang['close_subject']}</td>
		<td class="row2">
		 <select size="1" name="close_subject">
		  <option value="1">{$lang['yes']}</option>
		  <option selected="selected" value="0">{$lang['no']}</option>
		 </select>
		</td>

		</tr>
		<tr>

		<td class="row2">{$lang['send_warning']}</td>
		<td class="row2">
		 <select size="1" name="send_warning">
		  <option value="1">{$lang['yes']}</option>
		  <option selected="selected" value="0">{$lang['no']}</option>
		 </select>
		</td>

		</tr>

		<tr>
		<td class="main1" colspan="2">{$lang['Properties_admincp']}</td>
		</tr>
		<tr>
		<td class="row1">{$lang['admincp_allow']}</td>
		<td class="row1">
		 <select size="1" name="admincp_allow">

		  <option value="1">{$lang['yes']}</option>
		  <option selected="selected" value="0">{$lang['no']}</option>
		 </select>
		</td>
		</tr>

		<tr>
		<td class="row2">{$lang['admincp_section']}</td>

		<td class="row2">
		 <select size="1" name="admincp_section">
		  <option value="1">{$lang['yes']}</option>
		  <option selected="selected" value="0">{$lang['no']}</option>
		 </select>
		</td>
		</tr>
		<tr>

		<td class="row1">{$lang['admincp_option']}</td>
		<td class="row1">
		 <select size="1" name="admincp_option">
		  <option value="1">{$lang['yes']}</option>
		  <option selected="selected" value="0">{$lang['no']}</option>
		 </select>
		</td>

		</tr>
		<tr>
		<td class="row2">{$lang['admincp_member']}</td>
		<td class="row2">
		 <select size="1" name="admincp_member">
		  <option value="1">{$lang['yes']}</option>
		  <option selected="selected" value="0">{$lang['no']}</option>

		 </select>
		</td>
		</tr>
		<tr>
		<td class="row1">{$lang['admincp_membergroup']}</td>
		<td class="row1">
		 <select size="1" name="admincp_membergroup">
		  <option value="1">{$lang['yes']}</option>
		  <option selected="selected" value="0">{$lang['no']}</option>
		 </select>
		</td>
		</tr>
		<tr>
		<td class="row2">{$lang['admincp_membertitle']}</td>
		<td class="row2">
		 <select size="1" name="admincp_membertitle">
		  <option value="1">{$lang['yes']}</option>
		  <option selected="selected" value="0">{$lang['no']}</option>
		 </select>
		</td>
		</tr>

		<tr>
		<td class="row1">{$lang['admincp_admin']}</td>
		<td class="row1">
		 <select size="1" name="admincp_admin">
		  <option value="1">{$lang['yes']}</option>
		  <option selected="selected" value="0">{$lang['no']}</option>
		 </select>
		</td>
		</tr>
		<tr>

		<td class="row2">{$lang['admincp_adminstep']}</td>
		<td class="row2">
		 <select size="1" name="admincp_adminstep">
		  <option value="1">{$lang['yes']}</option>
		  <option selected="selected" value="0">{$lang['no']}</option>
		 </select>
		</td>

		</tr>
		<tr>
		<td class="row1">{$lang['admincp_subject']}</td>
		<td class="row1">
		 <select size="1" name="admincp_subject">
		  <option value="1">{$lang['yes']}</option>
		  <option selected="selected" value="0">{$lang['no']}</option>

		 </select>
		</td>
		</tr>
		<tr>
		<td class="row2">{$lang['admincp_database']}</td>
		<td class="row2">
		 <select size="1" name="admincp_database">
		  <option value="1">{$lang['yes']}</option>
		  <option selected="selected" value="0">{$lang['no']}</option>
		 </select>
		</td>
		</tr>
		<tr>
		<td class="row1">{$lang['admincp_fixup']}</td>
		<td class="row1">
		 <select size="1" name="admincp_fixup">
		  <option value="1">{$lang['yes']}</option>
		  <option selected="selected" value="0">{$lang['no']}</option>
		 </select>
		</td>
		</tr>
		<tr>
		<td class="row2">{$lang['admincp_ads']}</td>

		<td class="row2">
		 <select size="1" name="admincp_ads">
		  <option value="1">{$lang['yes']}</option>
		  <option selected="selected" value="0">{$lang['no']}</option>
		 </select>
		</td>
		</tr>
		<tr>

		<td class="row1">{$lang['admincp_template']}</td>
		<td class="row1">
		 <select size="1" name="admincp_template">
		  <option value="1">{$lang['yes']}</option>
		  <option selected="selected" value="0">{$lang['no']}</option>
		 </select>
		</td>

		</tr>
		<tr>
		<td class="row2">{$lang['admincp_adminads']}</td>
		<td class="row2">
		 <select size="1" name="admincp_adminads">
		  <option value="1">{$lang['yes']}</option>
		  <option selected="selected" value="0">{$lang['no']}</option>

		 </select>
		</td>
		</tr>
		<tr>
		<td class="row1">{$lang['admincp_attach']}</td>
		<td class="row1">
		 <select size="1" name="admincp_attach">
		  <option value="1">{$lang['yes']}</option>
		  <option selected="selected" value="0">{$lang['no']}</option>
		 </select>
		</td>
		</tr>
		<tr>
		<td class="row2">{$lang['admincp_page']}</td>
		<td class="row2">
		 <select size="1" name="admincp_page">
		  <option value="1">{$lang['yes']}</option>
		  <option selected="selected" value="0">{$lang['no']}</option>
		 </select>
		</td>
		</tr>
		<tr>
		<td class="row1">{$lang['admincp_block']}</td>
		<td class="row1">
		 <select size="1" name="admincp_block">
		  <option value="1">{$lang['yes']}</option>
		  <option selected="selected" value="0">{$lang['no']}</option>
		 </select>
		</td>
		</tr>
		<tr>

		<td class="row2">{$lang['admincp_style']}</td>
		<td class="row2">
		 <select size="1" name="admincp_style">
		  <option value="1">{$lang['yes']}</option>
		  <option selected="selected" value="0">{$lang['no']}</option>
		 </select>
		</td>

		</tr>
		<tr>
		<td class="row1">{$lang['admincp_toolbox']}</td>
		<td class="row1">
		 <select size="1" name="admincp_toolbox">
		  <option value="1">{$lang['yes']}</option>
		  <option selected="selected" value="0">{$lang['no']}</option>
		 </select>
		</td>
		</tr>
		<tr>
		<td class="row2">{$lang['admincp_smile']}</td>
		<td class="row2">
		 <select size="1" name="admincp_smile">
		  <option value="1">{$lang['yes']}</option>
		  <option selected="selected" value="0">{$lang['no']}</option>
		 </select>
		</td>
		</tr>
		<tr>
		<td class="row1">{$lang['admincp_icon']}</td>
		<td class="row1">
		 <select size="1" name="admincp_icon">
		  <option value="1">{$lang['yes']}</option>
		  <option selected="selected" value="0">{$lang['no']}</option>
		 </select>
		</td>
		</tr>
		<tr>
		<td class="row2">{$lang['admincp_avater']}</td>
		<td class="row2">
		 <select size="1" name="admincp_avater">
		  <option value="1">{$lang['yes']}</option>
		  <option selected="selected" value="0">{$lang['no']}</option>
		 </select>
		</td>
		</tr>
		<tr>
		<td class="row1">{$lang['admincp_contactus']}</td>
		<td class="row1">
		 <select size="1" name="admincp_contactus">
		  <option value="1">{$lang['yes']}</option>
		  <option selected="selected" value="0">{$lang['no']}</option>
		 </select>
		</td>
		</tr>
     <tr>
        <td class="row1">{$lang['admincp_chat']}</td>
        <td class="row1">
         <select size="1" name="admincp_chat">
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option
         </select>
        </td>
      </tr>
 		<tr>
		<td class="row1">{$lang['admincp_extrafield']}</td>
		<td class="row1">
		 <select size="1" name="admincp_extrafield">
		  <option value="1">{$lang['yes']}</option>
		  <option selected="selected" value="0">{$lang['no']}</option>
		 </select>
		</td>
		</tr>
		<tr>
		<td class="row1">{$lang['admincp_lang']}</td>
		<td class="row1">
		 <select size="1" name="admincp_lang">
		  <option value="1">{$lang['yes']}</option>
		  <option selected="selected" value="0">{$lang['no']}</option>
		 </select>
		</td>
		</tr>
		<tr>
		<td class="row1">{$lang['admincp_emailed']}</td>
		<td class="row1">
		 <select size="1" name="admincp_emailed">
		  <option value="1">{$lang['yes']}</option>
		  <option selected="selected" value="0">{$lang['no']}</option>
		 </select>
		</td>
		</tr>
		<tr>
		<td class="row1">{$lang['admincp_warn']}</td>
		<td class="row1">
		 <select size="1" name="admincp_warn">
		  <option value="1">{$lang['yes']}</option>
		  <option selected="selected" value="0">{$lang['no']}</option>
		 </select>
		</td>
		</tr>
		<tr>
		<td class="row1">{$lang['admincp_award']}</td>
		<td class="row1">
		 <select size="1" name="admincp_award">
		  <option value="1">{$lang['yes']}</option>
		  <option selected="selected" value="0">{$lang['no']}</option>
		 </select>
		</td>
		</tr>
		<tr>
		<td class="row1">{$lang['admincp_multi_moderation']}</td>
		<td class="row1">
		 <select size="1" name="admincp_multi_moderation">
		  <option value="1">{$lang['yes']}</option>
		  <option selected="selected" value="0">{$lang['no']}</option>
		 </select>
		</td>
		</tr>
		<tr>
		<td class="main1" colspan="2">{$lang['Properties_Other']}</td>
		</tr>
		<tr>
        <td class="row1">{$lang['group_use_the_account_security_settings']}</td>
		<td class="row1">
		 <select size="1" name="groups_security">
		  <option selected="selected" value="1">{$lang['yes']}</option>
		  <option value="0">{$lang['no']}</option>
		 </select>
		</td>
		</tr>
		<tr>
        <td class="row1">{$lang['group_use_profile_photo']}</td>
		<td class="row1">
		 <select size="1" name="profile_photo">
		  <option selected="selected" value="1">{$lang['yes']}</option>
		  <option value="0">{$lang['no']}</option>
		 </select>
		</td>
		</tr>
		<tr>
		<td class="row1">{$lang['search_allow']}</td>
		<td class="row1">
		 <select size="1" name="search_allow">
		  <option selected="selected" value="1">{$lang['yes']}</option>
		  <option value="0">{$lang['no']}</option>
		 </select>
		</td>
		</tr>
		<tr>
		<td class="row2">{$lang['memberlist_allow']}</td>
		<td class="row2">
		 <select size="1" name="memberlist_allow">
		  <option selected="selected" value="1">{$lang['yes']}</option>
		  <option value="0">{$lang['no']}</option>
		 </select>
		</td>
		</tr>
		<tr>
		<td class="row1">{$lang['show_hidden']}</td>
		<td class="row1">
		 <select size="1" name="show_hidden">
		  <option  value="1">{$lang['yes']}</option>
		  <option selected="selected" value="0">{$lang['no']}</option>
		 </select>
		</td>
		</tr>
		<tr>
		<td class="row2">{$lang['hide_allow']}</td>
		<td class="row2">
		 <select size="1" name="hide_allow">
		  <option selected="selected" value="1">{$lang['yes']}</option>
		  <option value="0">{$lang['no']}</option>
		 </select>
		</td>
		</tr>
		<tr>
		<td class="row2">{$lang['view_usernamestyle']}</td>
		<td class="row2">
		 <select size="1" name="view_usernamestyle">
		  <option selected="selected" value="1">{$lang['yes']}</option>
		  <option value="0">{$lang['no']}</option>

		 </select>
		</td>
		</tr>
		<tr>
		<td class="row1">{$lang['usertitle_change']}</td>
		<td class="row1">
		 <select size="1" name="usertitle_change">
		  <option value="1">{$lang['yes']}</option>
		  <option selected="selected" value="0">{$lang['no']}</option>
		 </select>
		</td>
		</tr>
		<tr>
		<td class="row2">{$lang['onlinepage_allow']}</td>
		<td class="row2">
		 <select size="1" name="onlinepage_allow">
		  <option value="1">{$lang['yes']}</option>
		  <option selected="selected" value="0">{$lang['no']}</option>
		 </select>
		</td>
		</tr>
		<tr>
		<td class="row1">{$lang['allow_see_offstyles']}</td>

		<td class="row1">
		 <select size="1" name="allow_see_offstyles">
		  <option value="1">{$lang['yes']}</option>
		  <option selected="selected" value="0">{$lang['no']}</option>
		 </select>
		</td>
		</tr>
		<tr>
		<td class="row1">{$lang['can_warned']}</td>
		<td class="row1">
		 <select size="1" name="can_warned">
		  <option value="1">{$lang['yes']}</option>
		  <option selected="selected" value="0">{$lang['no']}</option>
		 </select>
		</td>
		</tr>
        <tr>
		<td class="row1">{$lang['can_write_visitormessage']}</td>
		<td class="row1">
		 <select size="1" name="visitormessage">
		  <option selected="selected" value="1">{$lang['yes']}</option>
		  <option value="0">{$lang['no']}</option>
		 </select>
		</td>
		</tr>
        <tr>
        <td class="row2">{$lang['reputation_number']}</td>
        <td class="row2"><input type="text" name="reputation_number" value="10" size="1" min="1" max="1" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');"></td>
      </tr>
		<tr>
		<td class="row1">{$lang['user_review_subject']}</td>
		<td class="row1">
		 <select size="1" name="review_subject">
		  <option value="1">{$lang['yes']}</option>
		  <option selected="selected" value="0">{$lang['no']}</option>
		 </select>
		</td>
		</tr>
		<tr>
		<td class="row1">{$lang['user_review_reply']}</td>
		<td class="row1">
		 <select size="1" name="review_reply">
		  <option value="1">{$lang['yes']}</option>
		  <option selected="selected" value="0">{$lang['no']}</option>
		 </select>
		</td>
		</tr>
		<tr>
		<td class="row1">{$lang['view_action_edit']}</td>
		<td class="row1">
		 <select size="1" name="view_action_edit">
		  <option selected="selected" value="1">{$lang['yes']}</option>
		  <option value="0">{$lang['no']}</option>
		 </select>
		</td>
		</tr>
    </table>

	<div align="center">
		<input type="submit" value="{$lang['acceptable']}" name="submit" />
	</div>
</form>
