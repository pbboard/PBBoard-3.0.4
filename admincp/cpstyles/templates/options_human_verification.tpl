<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=options&amp;index=1">{$lang['mange_forum']}</a> &raquo;
<a href="index.php?page=options&amp;human_verification=1&amp;main=1">{$lang['manage_human_verification']}</a></div>

<br />

<form action="index.php?page=options&amp;human_verification=1&amp;update=1" method="post">
<table cellpadding="3" cellspacing="1" width="90%" class="t_style_b" border="0" align="center">
		<tr align="center">
			<td class="main1" colspan="2">
{$lang['manage_human_verification']}
			</td>
		</tr>
<tr valign="top">
		<td class="row2">{$lang['activate_captcha_o']}</td>
		<td class="row2">
<select name="captcha_o" id="select_captcha_o">
	{if {$_CONF['info_row']['captcha_o']}}
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
		<td class="row2">{$lang['choose_type_verify_human']}
<br />
<br />
{$lang['help_verification_question_and_answer']}
<br />
		</td>
		<td class="row2">
<select name="captcha_type" id="select_captcha_type">
	{if {$_CONF['info_row']['captcha_type']} == 'captcha_IMG'}
	<option value="captcha_IMG" selected="selected">{$lang['image_verification']}</option>
	<option value="captcha_Q_A">{$lang['verification_question_and_answer']}</option>
	{else}
	<option value="captcha_Q_A" selected="selected">{$lang['verification_question_and_answer']}</option>
	<option value="captcha_IMG">{$lang['image_verification']}</option>
	{/if}
</select>
</td>
</tr>
<tr valign="top">
		<td class="row2" colspan="2">
{$lang['each_question_offset_answer']}
	<br />
	<br />
<table border="0" width="80%" cellpadding="0" style="border-collapse: collapse" align="center">
	<tr>
		<td>
		<div align="center">{$lang['questions']}<div/>
<textarea name="questions" rows="5" cols="50">{$_CONF['info_row']['questions']}</textarea>
<br />
<div align="center">{$lang['answers']}<div/>
<textarea name="answers" rows="5" cols="50">{$_CONF['info_row']['answers']}</textarea>
</td>
	</tr>
</table>
<br />
</td>
</tr>
		<tr>
			<td class="row1" colspan="2" align="center">
<input type="submit" value="{$lang['acceptable']}" name="submit" /></td>
		</tr>
	</table>
	<br />

	</form>
<br />