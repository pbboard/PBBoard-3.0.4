<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
 <a href="index.php?page=sections&amp;control=1&amp;main=1">{$lang['Sections_Mains']}</a> &raquo;
  {$lang['Section_control_group']} :
  {$Inf['title']}</div>

<br />

<form action="index.php?page=sections&amp;groups=1&amp;control_group=1&amp;start=1&amp;id={$Inf['id']}"  name="myform" method="post">

{Des::while}{SecGroupList}
<table cellpadding="3" cellspacing="1" width="60%" class="t_style_b" border="0" cellspacing="1" align="center">
<tr valign="top" align="center">
	<td class="row1" colspan="2"><strong>{$SecGroupList['group_name']}</strong></td>
</tr>
<tr valign="top">
		<td class="row1">{$lang['View_forums']}</td>
		<td class="row1">
		<input TYPE="hidden" type="text" name="group_id" value="{$SecGroupList['group_id']}" size="30" />
			<label for="groups[{$SecGroupList['group_id']}][view_section]">
			{if {$SecGroupList['view_section']}}
			<input name="groups[{$SecGroupList['group_id']}][view_section]" id="select_view_section" value="1" tabindex="1" checked type="radio">{$lang['yes']}
			<input name="groups[{$SecGroupList['group_id']}][view_section]" id="select_view_section" value="0" tabindex="1" type="radio">{$lang['no']}
			{else}
			<input name="groups[{$SecGroupList['group_id']}][view_section]" id="select_view_section" value="1" tabindex="1" type="radio">{$lang['yes']}
			<input name="groups[{$SecGroupList['group_id']}][view_section]" id="select_view_section" value="0" tabindex="1" checked type="radio">{$lang['no']}
			{/if}
            </label>
</td>
</tr>
</table><br />
{/Des::while}
<tr>
	<td class="submit-buttons" colspan="2" align="center">
	<input class="submit" type="submit" value="{$lang['acceptable']}" name="submit" accesskey="s" /></td>
</tr>
</table><br />
</form>
