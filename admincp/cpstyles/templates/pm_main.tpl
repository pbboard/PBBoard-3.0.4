<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
 <a href="index.php?page=pm&amp;pm=1&amp;main=1">{$lang['Views_Private_Messages']}</a></div>

<br />
<form action="index.php?page=pm&amp;showuserpm=1&amp;pm=1" method="post">
	<table width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
		<tr>
<td class="main1" colspan="2" align="center"><b>{$lang['Find_messages_member']}</b></td>
		</tr>
		<tr valign="top">
			<td class="row1">{$lang['Enter_the_name_of_a_member']}</td>
			<td class="row1">
				<input type="text" name="username" id="input_username" size="35" />
			</td>
		</tr>
		<tr>
			<td class="row1" colspan="2" align="center">
			<input type="submit" class="button" value="{$lang['Read_the_messages_sent']}" name="submit1" />
			</td>
		</tr>
	</table>
</form>
		<br />
&nbsp;
<form action="index.php?page=pm&amp;show=1&amp;pm=1" method="post">
<table width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
		<tr>
			<td class="main1"align="center"><b>
			{$lang['Read_all_messages']}
			</b></td>
		</tr>
		<tr>
			<td class="row1" colspan="2" align="center">
			<input type="submit" class="button" value="{$lang['Click_here_to_see_all_messages_sent_and_received']}" name="submit2" />
			</td>
		</tr>
	</table>

</form>
 <br />
<form action="index.php?page=pm&amp;delet_all_pm=1&amp;pm=1" method="post">
<table width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
		<tr>
			<td class="main1"align="center"><b>
{$lang['delet_messages']}
			</b></td>
		</tr>
		<tr>
			<td class="row1" colspan="2" align="center">
			 <fieldset style="width: 300px;" >
<legend>{$lang['annotation']}</legend>
&nbsp;{$lang['If_you_execute_the_command_you_can_not_retreat']}</fieldset>
			 <br />
			<input type="submit" class="button" value="{$lang['delet_all_messages_pm']}" name="submit2" />
			 <br />
			 <br />
			</td>
		</tr>
	</table>

</form>

<br />