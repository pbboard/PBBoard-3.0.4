
<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=groups&amp;control=1&amp;main=1">{$lang['groups']}</a> &raquo;
<a href="index.php?page=groups&amp;edit=1&amp;main=1&amp;id={$Inf['id']}">
{$lang['edit']} :
 {$Inf['title']}</a></div>

<br />
 <!-- hook1 -->
<form action="index.php?page=groups&amp;edit=1&amp;start=1&amp;id={$Inf['id']}" method="post">
    <table width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
      <tr>
        <td class="main1" colspan="2"> {$lang['edit']} :
 {$Inf['title']}</td>
      </tr>
      <tr>
        <td class="row1">{$lang['Group_name']} *</td>
        <td class="row1"><input type="text" name="name" value="{$Inf['title']}" size="40"></td>
      </tr>

      <tr>
        <td class="row2">{$lang['username_color']} *<br><br>
        <a target="_blank" href="index.php?page=options&mods=1&main=1#25">
<b><u>{$lang['view_group_username_style']}</u></b></a>
        </td>
        <td class="row2">
        الحد الأقصى 			100 حرف
<br />
       <textarea name="style" dir="ltr" class="inputbox" tabindex="3" rows="3" cols="40" >{$group_inf['username_style']}</textarea>
        </td>
      </tr>
      <tr>
        <td class="row1">{$lang['usertitle']} *</td>
        <td class="row1">
الحد الأقصى 			100 حرف
<br />
         <textarea name="usertitle" class="inputbox" tabindex="3" rows="3" cols="40" >{$Inf['user_title']}</textarea>
        </td>
      </tr>
		<tr>
			<td class="row2">
			{$lang['group_order']} *
			</td>
			<td class="row2">
				<input type="text" name="group_order" id="group_order_id" value="{$Inf['group_order']}" size="1" min="1" max="1" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');">
			</td>
		</tr>
<tr>
<td class="row1">
تغيير صلاحيات جميع المنتديات لجعلها مطابقة لمجموعة:
</td>
<td class="row1">
<select name="usergroup" id="select_usergroup">
{Des::while}{GroupList}
{if {$GroupList['id']} == {$group_inf['id']}}
<option value="{$GroupList['id']}" selected="selected">{$GroupList['title']}</option>
{else}
<option value="{$GroupList['id']}">{$GroupList['title']}</option>
{/if}
{/Des::while}
</select>
</td>
</tr>
      <tr>
        <td class="row1">{$lang['forum_team']}</td>
        <td class="row1">
         <select size="1" name="forum_team">
         {if {$Inf['forum_team']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}
         </select>
        </td>
      </tr>
      <tr>
        <td class="row2">{$lang['group_is_banned']}</td>

        <td class="row2">
         <select size="1" name="banned">
         {if {$Inf['banned']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}
         </select>
        </td>
      </tr>
      <tr>

        <td class="main1" colspan="2">{$lang['forums_Properties']}</td>
      </tr>
	  <tr>
		<td class="row1">{$lang['view_sections']}</td>
		<td class="row1">
			<select size="1" name="view_section">
         {if {$Inf['view_section']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}
			</select>
		</td>
	  </tr>
	  <tr>
		<td class="row1">{$lang['view_subject']}</td>
		<td class="row1">
			<select size="1" name="view_subject">
         {if {$Inf['view_subject']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}
			</select>
		</td>
	  </tr>
      <tr>
        <td class="row2">{$lang['download_attach']}</td>
        <td class="row2">
         <select size="1" name="download_attach">
         {if {$Inf['download_attach']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}
         </select>
        </td>
      </tr>
      <tr>
        <td class="row1">{$lang['user_download_attach_number']}</td>

        <td class="row1"><input name="download_attach_number" type="text" value="{$Inf['download_attach_number']}" size="1" min="1" max="1" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');"></td>
      </tr>
      <tr>
        <td class="row2">{$lang['upload_attach']}</td>
        <td class="row2">
         <select size="1" name="upload_attach">
         {if {$Inf['upload_attach']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}

         </select>
        </td>
      </tr>
      <tr>
        <td class="row1">{$lang['upload_attach_num']}</td>
        <td class="row1"><input name="upload_attach_num" type="text" value="{$Inf['upload_attach_num']}" size="1" min="1" max="1" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');"></td>
      </tr>
      <tr>
        <td class="row2">{$lang['Write_subjects']}</td>
        <td class="row2">
         <select size="1" name="write_subject">
         {if {$Inf['write_subject']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}
         </select>
        </td>

      </tr>
      <tr>
        <td class="row1">{$lang['write_reply']}</td>
        <td class="row1">
         <select size="1" name="write_reply">
         {if {$Inf['write_reply']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}

         </select>
        </td>
      </tr>
      <tr>
        <td class="row2">{$lang['edit_own_subject']}</td>
        <td class="row2">
         <select size="1" name="edit_own_subject">
         {if {$Inf['edit_own_subject']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}
         </select>
        </td>
      </tr>
      <tr>
        <td class="row1">{$lang['edit_own_reply']}</td>
        <td class="row1">
         <select size="1" name="edit_own_reply">
         {if {$Inf['edit_own_reply']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}
         </select>
        </td>
      </tr>
      <tr>
        <td class="row2">{$lang['del_own_subject']}</td>

        <td class="row2">
         <select size="1" name="del_own_subject">
         {if {$Inf['del_own_subject']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}
         </select>
        </td>
      </tr>
      <tr>

        <td class="row1">{$lang['del_own_reply']}</td>
        <td class="row1">
         <select size="1" name="del_own_reply">
         {if {$Inf['del_own_reply']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}
         </select>
        </td>

      </tr>
      <tr>
        <td class="row2">{$lang['write_poll']}</td>
        <td class="row2">
         <select size="1" name="write_poll">
         {if {$Inf['write_poll']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}
         </select>
        </td>
      </tr>
      <tr>
        <td class="row1">{$lang['vote_poll']}</td>
        <td class="row1">
         <select size="1" name="vote_poll">
         {if {$Inf['vote_poll']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}
         </select>
        </td>
      </tr>
        <tr>
       <td class="row2">{$lang['see_who_on_topic']}</td>
       <td class="row2">
        <select size="1" name="see_who_on_topic">
        {if {$Inf['see_who_on_topic']}}
        <option selected="selected" value="1">{$lang['yes']}</option>
        <option value="0">{$lang['no']}</option>
        {else}
        <option value="1">{$lang['yes']}</option>
        <option selected="selected" value="0">{$lang['no']}</option>
        {/if}
        </select>
       </td>
     </tr>
      <tr>
        <td class="row1">{$lang['user_topic_day_number']}</td>
        <td class="row1"><input name="topic_day_number" type="text" value="{$Inf['topic_day_number']}" size="1" min="1" max="1" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');"></td>
      </tr>
      <tr>
        <td class="main1" colspan="2">{$lang['Properties_Private_Messages']}</td>
      </tr>
      <tr>
        <td class="row1">{$lang['use_pm']}</td>
        <td class="row1">
         <select size="1" name="use_pm">
         {if {$Inf['use_pm']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}
         </select>
         <input TYPE="hidden" name="send_pm" value="{$Inf['use_pm']}" />
         <input TYPE="hidden" name="resive_pm" value="{$Inf['use_pm']}" />
        </td>
      <tr>
        <td class="row2">{$lang['max_pm']}
        <br />
{$lang['Set_0_for_an_unlimited_number_of_messages']}
        </td>
        <td class="row2"><input type="text" name="max_pm" value="{$Inf['max_pm']}" size="1" min="1" max="1" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');"></td>
      </tr>

      <tr>
        <td class="row1">{$lang['min_send_pm']}</td>
        <td class="row1"><input type="text" name="min_send_pm" value="{$Inf['min_send_pm']}" size="1" min="1" max="1" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');"></td>
      </tr>
      <tr>
        <td class="main1" colspan="2">{$lang['Properties_sig']}</td>
      </tr>
      <tr>

        <td class="row1">{$lang['sig_allow']}</td>
        <td class="row1">
         <select size="1" name="sig_allow">
         {if {$Inf['sig_allow']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}
         </select>
        </td>

      </tr>
      <tr>
        <td class="row2">{$lang['sig_len']}</td>
        <td class="row2"><input type="text" name="sig_len" value="{$Inf['sig_len']}" size="1" min="1" max="1" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');"></td>
      </tr>

      <tr>
        <td class="main1" colspan="2">{$lang['Properties_mod']}</td>

      </tr>
      <tr>
        <td class="row1">{$lang['group_mod']}</td>
        <td class="row1">
         <select size="1" name="group_mod">
         {if {$Inf['group_mod']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}
         </select>
        </td>
      </tr>
      <tr>
        <td class="row2">{$lang['group_vice']}</td>
        <td class="row2">
         <select size="1" name="vice">
         {if {$Inf['vice']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}
         </select>
        </td>
      </tr>
      <tr>
        <td class="row1">{$lang['edit_subject']}</td>
        <td class="row1">
         <select size="1" name="edit_subject">
         {if {$Inf['edit_subject']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}
         </select>
        </td>
      </tr>
      <tr>
        <td class="row2">{$lang['edit_reply']}</td>

        <td class="row2">
         <select size="1" name="edit_reply">
         {if {$Inf['edit_reply']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}
         </select>
        </td>
      </tr>
      <tr>

        <td class="row1">{$lang['del_subject']}</td>
        <td class="row1">
         <select size="1" name="del_subject">
         {if {$Inf['del_subject']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}
         </select>
        </td>

      </tr>
      <tr>
        <td class="row2">{$lang['del_reply']}</td>
        <td class="row2">
         <select size="1" name="del_reply">
         {if {$Inf['del_reply']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}
         </select>
        </td>
      </tr>
      <tr>
        <td class="row1">{$lang['stick_subject']}</td>
        <td class="row1">
         <select size="1" name="stick_subject">
         {if {$Inf['stick_subject']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}
         </select>
        </td>
      </tr>
      <tr>
        <td class="row2">{$lang['unstick_subject']}</td>
        <td class="row2">
         <select size="1" name="unstick_subject">
         {if {$Inf['unstick_subject']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}
         </select>
        </td>
      </tr>
      <tr>
        <td class="row1">{$lang['move_subject']}</td>

        <td class="row1">
         <select size="1" name="move_subject">
         {if {$Inf['move_subject']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}
         </select>
        </td>
      </tr>
      <tr>

        <td class="row2">{$lang['close_subject']}</td>
        <td class="row2">
         <select size="1" name="close_subject">
         {if {$Inf['close_subject']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}
         </select>
        </td>

      </tr>
      <tr>

        <td class="row2">{$lang['send_warning']}</td>
        <td class="row2">
         <select size="1" name="send_warning">
         {if {$Inf['send_warning']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}
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
         {if {$Inf['admincp_allow']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}
         </select>
        </td>
      </tr>

      <tr>
        <td class="row2">{$lang['admincp_section']}</td>

        <td class="row2">
         <select size="1" name="admincp_section">
         {if {$Inf['admincp_section']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}
         </select>
        </td>
      </tr>
      <tr>

        <td class="row1">{$lang['admincp_option']}</td>
        <td class="row1">
         <select size="1" name="admincp_option">
         {if {$Inf['admincp_option']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}
         </select>
        </td>

      </tr>
      <tr>
        <td class="row2">{$lang['admincp_member']}</td>
        <td class="row2">
         <select size="1" name="admincp_member">
         {if {$Inf['admincp_member']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}
         </select>
        </td>
      </tr>
      <tr>
        <td class="row1">{$lang['admincp_membergroup']}</td>
        <td class="row1">
         <select size="1" name="admincp_membergroup">
         {if {$Inf['admincp_membergroup']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}
         </select>
        </td>
      </tr>
      <tr>
        <td class="row2">{$lang['admincp_membertitle']}</td>
        <td class="row2">
         <select size="1" name="admincp_membertitle">
         {if {$Inf['admincp_membertitle']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}
         </select>
        </td>
      </tr>

      <tr>
        <td class="row1">{$lang['admincp_admin']}</td>

        <td class="row1">
         <select size="1" name="admincp_admin">
         {if {$Inf['admincp_admin']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}
         </select>
        </td>
      </tr>
      <tr>

        <td class="row2">{$lang['admincp_adminstep']}</td>
        <td class="row2">
         <select size="1" name="admincp_adminstep">
         {if {$Inf['admincp_adminstep']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}
         </select>
        </td>

      </tr>
      <tr>
        <td class="row1">{$lang['admincp_subject']}</td>
        <td class="row1">
         <select size="1" name="admincp_subject">
         {if {$Inf['admincp_subject']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}

         </select>
        </td>
      </tr>
      <tr>
        <td class="row2">{$lang['admincp_database']}</td>
        <td class="row2">
         <select size="1" name="admincp_database">
         {if {$Inf['admincp_database']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}
         </select>
        </td>
      </tr>
      <tr>
        <td class="row1">{$lang['admincp_fixup']}</td>
        <td class="row1">
         <select size="1" name="admincp_fixup">
         {if {$Inf['admincp_fixup']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}
         </select>
        </td>
      </tr>
      <tr>
        <td class="row2">{$lang['admincp_ads']}</td>

        <td class="row2">
         <select size="1" name="admincp_ads">
         {if {$Inf['admincp_ads']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}
         </select>
        </td>
      </tr>
      <tr>

        <td class="row1">{$lang['admincp_template']}</td>
        <td class="row1">
         <select size="1" name="admincp_template">
         {if {$Inf['admincp_template']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}
         </select>
        </td>

      </tr>
      <tr>
        <td class="row2">{$lang['admincp_adminads']}</td>
        <td class="row2">
         <select size="1" name="admincp_adminads">
         {if {$Inf['admincp_adminads']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}
         </select>
        </td>
      </tr>
      <tr>
        <td class="row1">{$lang['admincp_attach']}</td>
        <td class="row1">
         <select size="1" name="admincp_attach">
         {if {$Inf['admincp_attach']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}
         </select>
        </td>
      </tr>
      <tr>
        <td class="row2">{$lang['admincp_page']}</td>
        <td class="row2">
         <select size="1" name="admincp_page">
         {if {$Inf['admincp_page']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}
         </select>
        </td>
      </tr>
      <tr>
        <td class="row1">{$lang['admincp_block']}</td>

        <td class="row1">
         <select size="1" name="admincp_block">
         {if {$Inf['admincp_block']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}
         </select>
        </td>
      </tr>
      <tr>

        <td class="row2">{$lang['admincp_style']}</td>
        <td class="row2">
         <select size="1" name="admincp_style">
         {if {$Inf['admincp_style']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}
         </select>
        </td>

      </tr>
      <tr>
        <td class="row1">{$lang['admincp_toolbox']}</td>
        <td class="row1">
         <select size="1" name="admincp_toolbox">
         {if {$Inf['admincp_toolbox']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}

         </select>
        </td>
      </tr>
      <tr>
        <td class="row2">{$lang['admincp_smile']}</td>
        <td class="row2">
         <select size="1" name="admincp_smile">
         {if {$Inf['admincp_smile']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}
         </select>
        </td>
      </tr>
      <tr>
        <td class="row1">{$lang['admincp_icon']}</td>
        <td class="row1">
         <select size="1" name="admincp_icon">
         {if {$Inf['admincp_icon']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}
         </select>
        </td>
      </tr>
      <tr>
        <td class="row2">{$lang['admincp_avater']}</td>
        <td class="row2">
         <select size="1" name="admincp_avater">
         {if {$Inf['admincp_avater']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}
         </select>
        </td>
      </tr>
      <tr>
        <td class="row1">{$lang['admincp_contactus']}</td>
        <td class="row1">
         <select size="1" name="admincp_contactus">
         {if {$Inf['admincp_contactus']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}
         </select>
        </td>
      </tr>
     <tr>
        <td class="row1">{$lang['admincp_chat']}</td>
        <td class="row1">
         <select size="1" name="admincp_chat">
         {if {$Inf['admincp_chat']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}
         </select>
        </td>
      </tr>
      <tr>
        <td class="row1">{$lang['admincp_extrafield']}</td>
        <td class="row1">
         <select size="1" name="admincp_extrafield">
         {if {$Inf['admincp_extrafield']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}
         </select>
        </td>
      </tr>
      <tr>
        <td class="row1">{$lang['admincp_lang']}</td>
        <td class="row1">
         <select size="1" name="admincp_lang">
         {if {$Inf['admincp_lang']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}
         </select>
        </td>
      </tr>
      <tr>
        <td class="row1">{$lang['admincp_emailed']}</td>
        <td class="row1">
         <select size="1" name="admincp_emailed">
         {if {$Inf['admincp_emailed']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}
         </select>
        </td>
      </tr>
      <tr>
        <td class="row1">{$lang['admincp_warn']}</td>
        <td class="row1">
         <select size="1" name="admincp_warn">
         {if {$Inf['admincp_warn']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}
         </select>
        </td>
      </tr>
      <tr>
        <td class="row1">{$lang['admincp_award']}</td>
        <td class="row1">
         <select size="1" name="admincp_award">
         {if {$Inf['admincp_award']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}
         </select>
        </td>
      </tr>
      <tr>
        <td class="row1">{$lang['admincp_multi_moderation']}</td>
        <td class="row1">
         <select size="1" name="admincp_multi_moderation">
         {if {$Inf['admincp_multi_moderation']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}
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
         {if {$Inf['groups_security']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}
         </select>
        </td>
      </tr>
       <tr>
        <td class="row1">{$lang['group_use_profile_photo']}</td>
        <td class="row1">
         <select size="1" name="profile_photo">
         {if {$Inf['profile_photo']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}
         </select>
        </td>
      </tr>
      <tr>
        <td class="row1">{$lang['search_allow']}</td>
        <td class="row1">
         <select size="1" name="search_allow">
         {if {$Inf['search_allow']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}
         </select>
        </td>
      </tr>
      <tr>
        <td class="row2">{$lang['memberlist_allow']}</td>
        <td class="row2">
         <select size="1" name="memberlist_allow">
         {if {$Inf['memberlist_allow']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}
         </select>
        </td>
      </tr>
      <tr>
        <td class="row1">{$lang['show_hidden']}</td>
        <td class="row1">
         <select size="1" name="show_hidden">
         {if {$Inf['show_hidden']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}
         </select>
        </td>
      </tr>
        <tr>

        <td class="row2">{$lang['hide_allow']}</td>
        <td class="row2">
		{if {$Inf['id']} == '7'}
		<input TYPE="hidden" name="hide_allow" value="0" />
		{else}
         <select size="1" name="hide_allow">
         {if {$Inf['hide_allow']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}
         </select>
           {/if}
        </td>
      </tr>
      <tr>
      <tr>
        <td class="row2">{$lang['view_usernamestyle']}</td>
        <td class="row2">
         <select size="1" name="view_usernamestyle">
         {if {$Inf['view_usernamestyle']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}

         </select>
        </td>
      </tr>
      <tr>
       {if {$Inf['id']} == '7'}
      <input TYPE="hidden" name="usertitle_change" value="0" />
      {else}
        <td class="row1">{$lang['usertitle_change']}</td>
        <td class="row1">
         <select size="1" name="usertitle_change">
         {if {$Inf['usertitle_change']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}
         </select>
        </td>
      </tr>
      {/if}
      <tr>
        <td class="row2">{$lang['onlinepage_allow']}</td>
        <td class="row2">
         <select size="1" name="onlinepage_allow">
         {if {$Inf['onlinepage_allow']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}
         </select>
        </td>
      </tr>
      <tr>
        <td class="row1">{$lang['allow_see_offstyles']}</td>

        <td class="row1">
         <select size="1" name="allow_see_offstyles">
         {if {$Inf['allow_see_offstyles']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}
         </select>
        </td>
      </tr>
            <tr>
        <td class="row1">{$lang['can_warned']}</td>
        <td class="row1">
         <select size="1" name="can_warned">
         {if {$Inf['can_warned']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}
         </select>
        </td>
      </tr>
            <tr>
        <td class="row1">{$lang['can_write_visitormessage']}</td>
        <td class="row1">
         <select size="1" name="visitormessage">
         {if {$Inf['visitormessage']}}
          <option selected="selected" value="1">{$lang['yes']}</option>
          <option value="0">{$lang['no']}</option>
         {else}
          <option value="1">{$lang['yes']}</option>
          <option selected="selected" value="0">{$lang['no']}</option>
         {/if}
         </select>
        </td>
      </tr>
<tr>
        <td class="row2">{$lang['reputation_number']}</td>
        <td class="row2"><input type="text" name="reputation_number" value="{$Inf['reputation_number']}" size="1" min="1" max="1" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');"></td>
      </tr>
	<tr>
		<td class="row1">
		{$lang['user_review_subject']}
		</td>
		<td class="row1">
			<select name="review_subject" id="select_review_subject">
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
		<tr>
		<td class="row1">
		{$lang['user_review_reply']}
		</td>
		<td class="row1">
			<select name="review_reply" id="select_review_reply">
				{if {$Inf['review_reply']}}
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
		{$lang['view_action_edit']}
		</td>
		<td class="row1">
			<select name="view_action_edit" id="select_view_action_edit">
				{if {$Inf['view_action_edit']}}
				<option value="1" selected="selected">{$lang['yes']}</option>
				<option value="0">{$lang['no']}</option>
				{else}
				<option value="1">{$lang['yes']}</option>
				<option value="0" selected="selected">{$lang['no']}</option>
				{/if}
			</select>
		</td>
	</tr>
	<!-- hook2 -->
    </table>

	<div align="center">
		<input type="submit" value="{$lang['acceptable']}" name="submit" />
	</div>
</form>
<!-- hook3 -->
