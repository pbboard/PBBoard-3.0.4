<br />

<div class="address_bar">{$lang['subjects']} &raquo;
<a href="index.php?page=subject&amp;deleted_subject=1&amp;main=1">{$lang['deleted_subject']}</a></div>

<br />
<form action="index.php?page=subject&amp;deleted_subject=1&amp;start=1" method="post">
<table width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
	<tr align="center">
		<td class="main1">
        {$lang['Delete_the_control_subjects']}
		</td>
	</tr>
	<tr>
		<td class="row1">
        {$lang['Warning_deleted_subject']}
		</td>
	</tr>
</table>
<br />
<table width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
	<tr align="center">
		<td class="main1" colspan="2">
        {$lang['deletedSubjectDateOptions']}
		</td>
		</tr>
		<tr>
		<td class="row1">
          {$lang['SubjectDeleteOptions_1']}
		</td>
		<td class="row1">
<input type="text" name="options_1" value="0" size="4" />
		</td>
		</tr>
		<tr>
		<td class="row1">
{$lang['SubjectDeleteOptions_2']}
		</td>
		<td class="row1">
<input type="text" name="options_2" value="0" size="4" />
		</td>
		</tr>
		<tr>
		<td class="row1" colspan="2" align="center">
		<input type="submit" value=" {$lang['deleted_subject']} " name="submit1" />

</td>
		</tr>
</table>
<br />
<table width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
	<tr align="center">
		<td class="main1" colspan="2">
        {$lang['deleted_subject_Options_reply_View']}
		</td>
		</tr>
		<tr>
		<td class="row1">
        {$lang['deleted_subject_Options_reply_View_Options_1']}
		</td>
		<td class="row1">
<input type="text" name="posts_num" size="4" />
		</td>
		</tr>
		<tr>
		<td class="row1">
        {$lang['deleted_subject_Options_reply_View_Options_2']}
		</td>
		<td class="row1">
<input type="text" name="view_num" size="4" />
		</td>
	</tr>
		<tr>
		<td class="row1" colspan="2" align="center">
		<input type="submit" value=" {$lang['deleted_subject']} " name="submit2" />

</td>
</tr>
</table>
<br />
<table width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
	<tr align="center">
		<td class="main1" colspan="2">
        {$lang['deleted_subject_by_User_Name']}
		</td>
		</tr>
		<tr>
		<td class="row1">
        {$lang['by_User_Name']}
		</td>
		<td class="row1">
<input type="text" name="user_name"  size="35" />
		</td>
				</tr>
		<tr>
		<td class="row1">
        {$lang['by_Forum']}
		</td>
		<td class="row1">
           <select name="forum" id="select_forum">
           	<option value="all" selected="selected">-- الجميع --</option>
				{Des::foreach}{forums_list}{forum}
					{if {$forum['parent']} == 0}
					<option value="{$forum['id']}" disabled="disabled">- {$forum['title']}</option>
					{else}
					<option value="{$forum['id']}">-- {$forum['title']}</option>
					{/if}
					{if {$forum['parent']} != 0}
		       {if {$forum['linksection']} != '1'}
				 {if {$forum['is_sub']}}
					{$forum['sub']}
				{/if}
				{/if}
	            {/if}
				{/Des::foreach}
			</select>
			</td>
	</tr>
		<tr>
		<td class="row1" colspan="2" align="center">
		<input type="submit" value=" {$lang['deleted_subject']} " name="submit3" />

</td>
</tr>
</table>
</form>
<br />