<br />
<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=options&amp;index=1">{$lang['mange_forum']}</a></div>
<br />
 <!-- action_find_addons_1 -->
<form name="mange_styles" id="mange_styles_frm" action="../">
<table cellpadding="3" cellspacing="1" width="80%" class="t_style_b" border="0" align="center">
 <!-- action_find_addons_2 -->
<tr valign="top" align="center">
<td class="main3" colspan="2">{$lang['mange_forum']}</td>
</tr>
<tr valign="top" align="center">
<td class="row1">
 <div class="pbb-menu" style="width:160px;">
<ul style="display: block;"><li><span class="headerbar">
<a href="index.php?page=options&amp;allgeneral=1&amp;main=1">{$lang['shwo_all_generals']}</a>
</span></li>
</ul>
 </div>
</td>
<td class="row1">
<select name="myDestination" tabindex="1" class="bginput" multiple size="12" style="width:350px" ondblclick="window.location.href=this.options[this.selectedIndex].value">
<option value="index.php?page=options&amp;allgeneral=1&amp;main=1">-- {$lang['shwo_all_generals']} --</option>
<option value="index.php?page=options&amp;close=1&amp;main=1">{$lang['board_close']}</option>
<!-- action_find_addons_1 -->
<option value="index.php?page=options&amp;general=1&amp;main=1">{$lang['General_Settings']}</option>
<option value="index.php?page=options&amp;human_verification=1&amp;main=1">{$lang['manage_human_verification']}</option>
<option value="index.php?page=cp_options&amp;worms_pbb=1&amp;main=1">{$lang['worms_pbb']}</option>
<option value="index.php?page=cp_options&amp;cpstyle=1&amp;main=1">{$lang['cpstyle_folder']}</option>
<option value="index.php?page=options&amp;email_msg=1&amp;main=1">{$lang['mail_messages']}</option>
<option value="index.php?page=options&amp;time=1&amp;main=1">{$lang['Time_Settings']}</option>
 <!-- action_find_addons_2 -->
<option value="index.php?page=options&amp;pages=1&amp;main=1">{$lang['pages_Settings']}</option>
<option value="index.php?page=options&amp;register=1&amp;main=1">{$lang['reg_Settings']}</option>
<option value="index.php?page=options&amp;topics=1&amp;main=1">{$lang['Settings_threads_and_replies']}</option>
<option value="index.php?page=options&amp;fast_reply=1&amp;main=1">{$lang['Settings_fastreply']}</option>
<option value="index.php?page=options&amp;member=1&amp;main=1">{$lang['Settings_Members']}</option>
<option value="index.php?page=options&amp;avatar=1&amp;main=1">{$lang['Settings_Avatars']}</option>
<option value="index.php?page=options&amp;close_days=1&amp;main=1">{$lang['Days_allowed_for_visitors_to_login']}</option>
<option value="index.php?page=options&amp;ajax=1&amp;main=1">{$lang['mange_ajax']}</option>
<option value="index.php?page=options&amp;mailer=1&amp;main=1">{$lang['settings_mailer']}</option>
<option value="index.php?page=options&amp;warning=1&amp;main=1">{$lang['mange_warnings']}</option>
<option value="index.php?page=options&amp;reputation=1&amp;main=1">{$lang['Settings_reputation']}</option>
<!-- action_find_addons_3 -->
<option value="index.php?page=options&amp;mods=1&amp;main=1">{$lang['Settings_Mods']}</option>
<option value="index.php?page=options&amp;mention=1&amp;mainmention=1&amp;mention_main=1">{$lang['a_mention']}</option>
<option value="index.php?page=options&amp;pbb_seo=1&amp;main=1">{$lang['Settings_Seo']}</option>
<option value="index.php?page=options&amp;sidebar_list=1&amp;main=1">{$lang['sidebar_list_settings']}</option>
 <!-- action_find_addons_4 -->
 <?php $PowerBB->functions->get_hooks_template("options_main")?>
</select>
</td>
</tr>
<tr valign="top" align="center">
<td class="row1" colspan="2">
<input type="button" class="submit" value="{$lang['editstyle']}" onclick="ob=this.form.myDestination;window.open(ob.options[ob.selectedIndex].value,'_self')" >
</td>
</tr>
 <!-- action_find_addons_5 -->
</table>
</form>

 <!-- action_find_addons_4 -->