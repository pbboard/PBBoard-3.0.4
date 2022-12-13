<table style="border-collapse: collapse" dir="ltr" class="wd98 brd0 clp0">
<tr>
<td class="left_text_align">
{if !{$mod_toolbar}}
<span class="pbbmenu button_b">
<a href="javascript:switchMenuNone('[forum_tools]')"><img alt="menu" class="brd0" src="{$image_path}/menu_open.gif"
title="{$lang['Management_tools']}" />
{$lang['Options_mod']}
</a>
</span>
<div style="display:none;position:absolute;z-index:100;" id="[forum_tools]">
<table class="border wd100n brd1 clpc0">
<tr class="center_text_align">
<td class="thead">
{$lang['Management_tools']}
</td>
</tr>
<tr>
<td class="menu_popup">
<div class="abso-relative" id="[forum_tools]">
<table class="wd100n brd0 clp0" style="border-collapse: collapse">
<tr>
<td class="row1 menu_t">
<input class="menu_popup" name="deletethread" type="submit" value="{$lang['deletethread']}" /><br />
</td>
</tr>
<tr>
<td class="row1 menu_t">
<input class="menu_popup" name="undeletethread" type="submit" value="{$lang['undeletethread']}" /><br />
</td>
</tr>
<tr>
<td class="row1 menu_t">
<input class="menu_popup" name="openthread" type="submit" value="{$lang['openthread']}" /><br />
</td>
</tr>
<tr>
<td class="row1 menu_t">
<input class="menu_popup" name="closethread" type="submit" value="{$lang['closethread']}" /><br />
</td>
</tr>
<tr>
<td class="row1 menu_t">
<input class="menu_popup" name="approvethread" type="submit" value="{$lang['approvethread']}" /><br />
</td>
</tr>
<tr>
<td class="row1 menu_t">
<input class="menu_popup" name="unapprovethread" type="submit" value="{$lang['unapprovethread']}" /><br />
</td>
</tr>
<tr>
<td class="row1 menu_t">
<input class="menu_popup" name="stickthread" type="submit" value="{$lang['stickthread']}" /><br />
</td>
</tr>
<tr>
<td class="row1 menu_t">
<input class="menu_popup" name="unstickthread" type="submit" value="{$lang['unstickthread']}" /><br />
</td>
</tr>
<tr>
<td class="row1 menu_t">
<input class="menu_popup" name="movethread" type="submit" value="{$lang['movethread']}" />
</td>
</tr>
</table></div>
</td>
</tr>
</table>
</div>
{/if}
</td>
</tr>
</table>
</form>