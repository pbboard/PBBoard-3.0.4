<table class="border right_text_align usercp_menu brd1 clpc0">
<tr class="center_text_align">
<td class="tcat">
<a href="index.php?page=usercp&amp;index=1">{$lang['usercp']}</a>
</td>
</tr>
{if {$_CONF['info_row']['pm_feature']}}
<tr class="center_text_align">
<td class="thead rows_space">
{$lang['Private_Messages']}
</td>
</tr>     	<tr>
<td class="row1">
<a href="index.php?page=pm_send&amp;send=1&amp;index=1">{$lang['Send_PM']}</a>
</td>
</tr>     	<tr>
<td class="row1">
<a href="index.php?page=pm_list&amp;list=1&amp;folder=inbox">{$lang['Contained_Messages']}</a>
</td>
</tr>     	<tr>
<td class="row1">
<a href="index.php?page=pm_list&amp;list=1&amp;folder=sent">{$lang['Outgoing_Messages']}</a>
</td>
</tr>
<tr>
<td class="row1">
<a href="index.php?page=pm_setting&amp;setting=1&amp;index=1">{$lang['Settings_Private_Messages']}</a>
</td>
</tr>
{/if}
<tr>
<td class="thead center_text_align">
{$lang['Management_Profile']}
</td>
</tr>     	<tr>
<td class="row1">
<a href="index.php?page=usercp&amp;control=1&amp;info=1&amp;main=1">{$lang['Your_Personal_Information']}</a>
</td>
</tr>     	<tr>
<td class="row1">
<a href="index.php?page=usercp&amp;control=1&amp;setting=1&amp;main=1">{$lang['Your_Options']}</a>
</td>
</tr>     	<tr>
<td class="row1">
<a href="index.php?page=usercp&amp;control=1&amp;sign=1&amp;main=1">{$lang['Change_Signature']}</a>
</td>
</tr>     	<tr>
<td class="row1">
<a href="index.php?page=usercp&amp;control=1&amp;password=1&amp;main=1">{$lang['Change_password']}</a>
</td>
</tr>     	<tr>
<td class="row1">
<a href="index.php?page=usercp&amp;control=1&amp;email=1&amp;main=1">{$lang['Change_email']}</a>
</td>
</tr>     	<tr>
<td class="row1">
<a href="index.php?page=usercp&amp;control=1&amp;avatar=1&amp;main=1">{$lang['Change_avatar']}</a>
</td>
</tr>
{if {$_CONF['info_row']['users_security']} == '1'}
<tr>
<td class="thead center_text_align">
{$lang['security_settings']}
</td>
</tr>
<tr>
<td class="row1">
<a href="index.php?page=privacy&amp;infosecurity=1&amp;main=1">{$lang['account_security']}</a>
</td>
</tr>
{/if}
<tr>
<td class="thead center_text_align">
{$lang['Other_options']}
</td>
</tr>
<tr>
<td class="row1">
<a href="index.php?page=usercp&amp;options=1&amp;subject=1&amp;main=1">{$lang['Your_Subjects']}</a>
</td>
</tr>
<tr>
<td class="row1">
<a href="index.php?page=usercp&amp;options=1&amp;attach=1&amp;main=1">{$lang['Your_Attachments']}</a>
</td>
</tr>
<tr>
<td class="row1">
<a href="index.php?page=usercp&amp;options=1&amp;emailed=1&amp;main=1">{$lang['Your_emailed']}</a>
</td>
</tr>
{if {$_CONF['info_row']['active_friend']} == '1'}
<tr>
<td class="row1">
<a href="index.php?page=usercp&amp;options=1&amp;friends=1&amp;main=1">{$lang['friends_list']}</a>
</td>
</tr>
{/if}
{if {$_CONF['info_row']['mention_active']} == '1'}
<tr>
<td class="row1">
<a href="index.php?page=usercp&amp;options=1&amp;mention=1&amp;main=1">{$lang['mention']}</a>
</td>
</tr>
{/if}
<tr>
<td class="row1">
<a href="index.php?page=usercp&amp;options=1&amp;reputation=1&amp;main=1">{$lang['usercp_reputations']}</a>
</td>
</tr>
</table>
<br />
