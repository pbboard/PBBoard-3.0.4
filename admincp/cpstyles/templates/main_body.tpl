<br />
<table width="50%" class="t_style_b" border="0" cellspacing="10">
	<tr align="center">
		<td class="main2" colspan="2">{$lang['Quick_Stats']}</td>
	</tr>
	<tr align="center">
		<td class="foot">
<table border="0" width="100px" cellpadding="0" style="border-collapse: collapse">
<tr>
<td>
<span class="statsl">
{$_CONF['info_row']['MySBB_version']}
</span>
<br />
<span class="stats2">
{$lang['version_number']}
</span>
</td>
<td class="dot active">
<i class="fa fa-code-fork fa-5x" aria-hidden="true"></i>
</td>
</tr>
</table>
		</td>
		<td class="foot">
<table border="0" width="100px" cellpadding="0" style="border-collapse: collapse">
	<tr>
		<td>
		<span class="statsl">
           {$lang['check_version']}
			</span><br />
			<span class="stats2">
	      <!--versioncheck-->
		  <!--versionnotification-->
		</span>
		</td>
		<td class="dot active">
		<i class="fa fa-check-circle-o fa-5x" aria-hidden="true"></i>
		</td>
	</tr>
</table>

		</td>
	</tr>

	<tr align="center">
		<td class="foot">
<table border="0" width="100px" cellpadding="0" style="border-collapse: collapse">
<tr>
<td>
<span class="statsl">
{$MemberNumber}
</span>
<br />
<span class="stats2">
{$lang['Number_of_Members']}
</span>
</td>
<td class="dot active">
<i class="fa fa-users fa-4x" aria-hidden="true"></i>
</td>
</tr>
</table>
		</td>
		<td class="foot">
<table border="0" width="100px" cellpadding="0" style="border-collapse: collapse">
<tr>
<td>
<span class="statsl">
{$ActiveMember}
</span>
<br />
<span class="stats2">
{$lang['ActiveMember']}
</span>
</td>
<td class="dot active">
<i class="ve fa fa-users fa-4x" aria-hidden="true"></i>
</td>
</tr>
</table>
		</td>
	</tr>
		<tr align="center">
		<td class="foot">
<table border="0" width="100px" cellpadding="0" style="border-collapse: collapse">
<tr>
<td>
<span class="statsl">
{$ForumsNumber}
</span>
<br />
<span class="stats2">
{$lang['ForumsNumber']}
</span>
</td>
<td class="dot active">
<i class="fa fa-comments fa-4x" aria-hidden="true"></i>
</td>
</tr>
</table>
		</td>
		<td class="foot">
<table border="0" width="100px" cellpadding="0" style="border-collapse: collapse">
<tr>
<td>
<span class="statsl">
{$SubjectNumber}
</span>
<br />
<span class="stats2">
{$lang['SubjectNumber']}
</span>
</td>
<td class="dot active">
<i class="fa fa-pencil-square fa-4x" aria-hidden="true"></i>
</td>
</tr>
</table>
		</td>
	</tr>
	<tr align="center">
<td class="foot">
<table border="0" width="100px" cellpadding="0" style="border-collapse: collapse">
<tr>
<td>
<span class="statsl">
{$ReplyNumber}
</span>
<br />
<span class="stats2">
{$lang['ReplyNumber']}
</span>
</td>
<td class="dot active">
<i class="fa fa-pencil-square-o fa-4x" aria-hidden="true"></i>
</td>
</tr>
</table>
</td>
<td class="foot">
<table border="0" width="100px" cellpadding="0" style="border-collapse: collapse">
<tr>
<td>
<span class="statsl">
{$TodayMemberNumber}
</span>
<br />
<span class="stats2">
{$lang['TodayMemberNumber']}
</span>
</td>
<td class="dot active">
<i class="fa fa-user-plus fa-4x" aria-hidden="true"></i>
</td>
</tr>
</table>
</td>
</tr>

	<tr align="center">
<td class="foot">
<table border="0" width="100px" cellpadding="0" style="border-collapse: collapse">
<tr>
<td>
<span class="statsl">
{$TodaySubjectNumber}
</span>
<br />
<span class="stats2">
{$lang['TodaySubjectNumber']}
</span>
</td>
<td class="dot active">
<i class="fa fa-commenting-o fa-4x" aria-hidden="true"></i>
</td>
</tr>
</table>
</td>
<td class="foot">
<table border="0" width="100px" cellpadding="0" style="border-collapse: collapse">
<tr>
<td>
<span class="statsl">
{$TodayReplyNumber}
</span>
<br />
<span class="stats2">
{$lang['TodayReplyNumber']}
</span>
</td>
<td class="dot active">
<i class="fa fa-commenting fa-4x" aria-hidden="true"></i>
</td>
</tr>
</table>
</td>
</tr>


<tr align="center">
<td class="foot">
<table border="0" width="100px" cellpadding="0" style="border-collapse: collapse">
<tr>
<td>
<span class="statsl">
{$ModeratorsNumber}
</span>
<br />
<span class="stats2">
<a href="index.php?page=groups&amp;edit=1&amp;main=1&amp;id=3"> {$lang['moderators']}</a>
</span>
</td>
<td class="dot active">
<i class="gav fa fa-gavel fa-4x" aria-hidden="true"></i>
</td>
</tr>
</table>
</td>
<td class="foot">
<table border="0" width="100px" cellpadding="0" style="border-collapse: collapse">
<tr>
<td>
<span class="statsl">
{$MembersActiveList}
		 {if {$MembersActiveList} > '0'}
		(<a href="index.php?page=member&amp;active_member=1&amp;main=1" target="main2"> {$lang['active_member']}</a>  )
		{/if}
</span>
<br />
<span class="stats2">
{$lang['MembersActiveList']}
</span>
</td>
<td class="dot active">
<i class="ne fa fa-users fa-4x" aria-hidden="true"></i>
</td>
</tr>
</table>
</td>
</tr>

</table>

<br />
<table width="90%" class="t_style_b" border="0" cellspacing="5" align="center">
	<tr>
		<td class="main2" colspan="2" align="center">{$lang['pbboard_last_updates']}</td>
	</tr>
<tr>
<td class="foot" width="80%" colspan="2">
<i class="dot active2 fa fa-wrench fa-3x" aria-hidden="true"></i>
  <big><!--PBBoard_Updates--></big>
</td>
	</tr>
</table>
<br />
<table width="90%" class="t_style_b" border="0" cellspacing="5" align="center">
	<tr>
		<td class="main2" colspan="2" align="center">{$lang['license']} - {$lang['Programmers_program_PBBoard']}</td>
	</tr>
	<tr>
		<td class="foot" width="20%">{$lang['Management_and_program_development']}</td>
<td class="foot" width="60%">SULAIMAN DAWOD</td>
	</tr>
		<tr>
		<td class="foot" width="20%">{$lang['license']}</td>
<td class="foot" width="60%">
PBBoard Is Free Software , Falls under the license GNU GPL General Public
</td>
	</tr>
		<tr>
		<td class="foot" width="20%">{$lang['Program_Version']}</td>
<td class="foot" width="60%">Version {$_CONF['info_row']['MySBB_version']}</td>
	</tr>
</table>
<br />

<table width="90%" class="t_style_b" border="0" cellspacing="5" align="center">
	<tr align="center">
		<td class="main2">{$lang['Administrators_Note']}</td>
	</tr>
	<tr align="center">
		<td class="foot">
			<form method="post" action="index.php?page=note">
				<textarea name="note" rows="9" cols="50" style="width: 100%;">{$_CONF['info_row']['admin_notes']}</textarea>
				<br />
				<input type="submit" value="{$lang['acceptable']}" name="submit" />
			</form>
		</td>
	</tr>
</table>
<br />
<br />
<br />
		<p id="copyright" align="center">
		<!--copyright-->
		</b>
