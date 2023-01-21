<div class="pbb-menu">
<div class="hide_m expand">
<div class="expcol"><a id="expandAll" href="#top" onclick="self.scrollTo(0, 0); return false;"> {$lang['expand_all']} <i class="fa fa-angle-double-down" aria-hidden="true"></i></a></div>
<div class="expcol"><a id="collapseAll" href="#top" onclick="self.scrollTo(0, 0); return false;"> {$lang['collapse_all']} <i class="fa fa-angle-double-up" aria-hidden="true"></i></a></div>
</div>
<br />

<nav id="main-nav">

<ul id="treeview" class="treeview-black">

<li><a href="index.php"><i class="fa fa-cogs" aria-hidden="true"></i> {$lang['home_admin']}</a></li>
<li><a href="../index.php" target="_blank"><i class="fa fa-home" aria-hidden="true"></i> {$lang['home_forum']}</a></li>
<li><a href="index.php?page=logout"><i class="fa fa-sign-out" aria-hidden="true"></i> {$lang['logout_admin']}</a></li>

<!-- action_find_addons_1 -->
{if {$group_info['admincp_option']} == 1}
<li><span class="headerbar"><a onClick="return false;" href="#"><span class="fa fa-bars" style="float:{$align};"></span>
{$lang['Settings']}
<img  alt=""  style="float:{$desalign};" src="{$admincpdir_cssprefs}/collapse_b.gif" class="hide_m" /></a></span>
<ul>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=options&amp;index=1">
{$lang['mange_forum']}</a></li>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=options&amp;human_verification=1&amp;main=1">
{$lang['manage_human_verification']}</a></li>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=options&amp;sidebar_list=1&amp;main=1&amp;left=1">
{$lang['sidebar_list_settings']}</a></li>
<!-- action_find_addons_2 -->
<!--
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=cp_options&amp;index=1">
{$lang['Settings_for_the_Admin_Control_Panel']}
</a></li>
-->
<!-- action_find_addons_cp -->
     </ul>
    </li>
{/if}


<!-- action_find_addons_3 -->
{if {$_CONF['rows']['member_row']['usergroup']} == 1}
<?php $PowerBB->functions->get_hooks_template("menu_cp")?>
<li><span class="headerbar"><a onClick="return false;" href="#"><span class="fa fa-bars" style="float:{$align};"></span>
{$lang['portal']}
<img  alt=""  style="float:{$desalign};" src="{$admincpdir_cssprefs}/collapse_b.gif" class="hide_m" /></a></span>
      <ul>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=portal&amp;control=1&amp;main=1">
{$lang['settings_portal']}
</a></li>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=portal&amp;add_block=1&amp;main=1">
{$lang['add_block']}
</a></li>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=portal&amp;control_blocks=1&amp;main=1">
{$lang['control_blocks']}
</a></li>
     </ul>
    </li>
{/if}

{if {$group_info['admincp_section']} == 1}
<li><span class="headerbar"><a onClick="return false;" href="#"><span class="fa fa-bars" style="float:{$align};"></span>
{$lang['Sections_Mains']}
<img  alt=""  style="float:{$desalign};" src="{$admincpdir_cssprefs}/collapse_b.gif" class="hide_m" /></a></span>
      <ul>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=sections&amp;add=1&amp;main=1">
{$lang['Add_new_Main_section']}
</a></li>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=sections&amp;control=1&amp;main=1">
{$lang['mange_sections']}
</a></li>
     </ul>
    </li>

<li><span class="headerbar"><a onClick="return false;" href="#"><span class="fa fa-bars" style="float:{$align};"></span>
{$lang['Forums']}
<img  alt=""  style="float:{$desalign};" src="{$admincpdir_cssprefs}/collapse_b.gif" class="hide_m" /></a></span>

      <ul>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=forums&amp;add=1&amp;main=1">
{$lang['Add_new_Forum']}
</a></li>

<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=forums&amp;control=1&amp;main=1">
{$lang['mange_Forums']}
</a></li>
     </ul>
    </li>
{/if}


{if {$group_info['admincp_style']} == 1}
<li><span class="headerbar"><a onClick="return false;" href="#"><span class="fa fa-bars" style="float:{$align};"></span>
{$lang['styles']}
<img  alt=""  style="float:{$desalign};" src="{$admincpdir_cssprefs}/collapse_b.gif" class="hide_m" /></a></span>
      <ul>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=style&amp;control=1&amp;main=1">
{$lang['mange_styles']}
</a></li>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=style&amp;add=1&amp;main=1">
{$lang['add_new_style']}
</a></li>
{if {$group_info['admincp_template']} == 1}
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=template&amp;control=1&amp;main=1">
{$lang['mange_templates']}
</a></li>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=template&amp;search=1&amp;main=1">
{$lang['search_templates']}
</a></li>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=template&amp;add=1&amp;main=1">
{$lang['add_new_template']}
</a></li>
{/if}
     </ul>
    </li>
{/if}

{if {$group_info['admincp_member']} == 1}
<li><span class="headerbar"><a onClick="return false;" href="#"><span class="fa fa-bars" style="float:{$align};"></span>
{$lang['members']}
<img  alt=""  style="float:{$desalign};" src="{$admincpdir_cssprefs}/collapse_b.gif" class="hide_m" /></a></span>
      <ul>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=member&amp;add=1&amp;main=1">
{$lang['Add_new_member']}
</a></li>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=member&amp;control=1&amp;main=1">
{$lang['mange_members']}
</a></li>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=member&amp;merge=1&amp;main=1">
{$lang['merge_members']}
</a></li>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=member&amp;active_member=1&amp;main=1">
{$lang['active_member']}
</a></li>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=member&amp;search=1&amp;main=1">
{$lang['search_members']}
</a></li>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=emailsend&amp;mail=1&amp;main=1">
{$lang['send_email_members']}
</a></li>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=pm&amp;send_pm=1&amp;pm=1">
{$lang['send_pm_members']}
</a></li>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=pm&amp;pm=1&amp;main=1">
{$lang['View_private_messages']}
</a></li>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=member&amp;warnings=1&amp;main=1">
{$lang['View_warnings']}
</a></li>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=banned&amp;banning=1&amp;main=1">
{$lang['banning_ip']}
</a></li>
     </ul>
    </li>
{/if}

{if {$group_info['admincp_lang']} == 1}
<li><span class="headerbar"><a onClick="return false;" href="#"><span class="fa fa-bars" style="float:{$align};"></span>
{$lang['langs']}
<img  alt=""  style="float:{$desalign};" src="{$admincpdir_cssprefs}/collapse_b.gif" class="hide_m" /></a></span>
      <ul>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=lang&amp;control=1&amp;main=1">
{$lang['mange_langs']}
</a></li>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=lang&amp;control_fieldname=1&amp;main=1">
{$lang['phrase_manager']}
</a></li>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=lang&amp;add=1&amp;main=1">
{$lang['add_new_lang']}
</a></li>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=lang&amp;add_fieldname=1&amp;main=1">
{$lang['add_new_phrase']}
</a></li>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=lang&amp;search_fieldname=1&amp;main=1">
{$lang['search_in_phrases']}
</a></li>
     </ul>
    </li>
{/if}


{if {$group_info['admincp_adminads']} == 1}
<li><span class="headerbar"><a onClick="return false;" href="#"><span class="fa fa-bars" style="float:{$align};"></span>
{$lang['announcement']}
<img  alt=""  style="float:{$desalign};" src="{$admincpdir_cssprefs}/collapse_b.gif" class="hide_m" /></a></span>
      <ul>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=announcement&amp;add=1&amp;main=1">
{$lang['Add_new_announcement']}
</a></li>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=announcement&amp;control=1&amp;main=1">
{$lang['mange_announcement']}
</a></li>
     </ul>
    </li>

<li><span class="headerbar"><a onClick="return false;" href="#"><span class="fa fa-bars" style="float:{$align};"></span>
{$lang['Pages']}
<img  alt=""  style="float:{$desalign};" src="{$admincpdir_cssprefs}/collapse_b.gif" class="hide_m" /></a></span>
      <ul>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=pages&amp;add=1&amp;main=1">
{$lang['Add_new_Page']}
</a></li>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=pages&amp;control=1&amp;main=1">
{$lang['mange_Pages']}
</a></li>
     </ul>
    </li>

<li><span class="headerbar"><a onClick="return false;" href="#"><span class="fa fa-bars" style="float:{$align};"></span>
{$lang['Ads']}
<img  alt=""  style="float:{$desalign};" src="{$admincpdir_cssprefs}/collapse_b.gif" class="hide_m" /></a></span>
      <ul>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=ads&amp;add=1&amp;main=1">
{$lang['Add_new_Ads']}
</a></li>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=ads&amp;control=1&amp;main=1">
{$lang['mange_Ads']}
</a></li>
     </ul>
    </li>

<li><span class="headerbar"><a onClick="return false;" href="#"><span class="fa fa-bars" style="float:{$align};"></span>
{$lang['adsense']}
<img  alt=""  style="float:{$desalign};" src="{$admincpdir_cssprefs}/collapse_b.gif" class="hide_m" /></a></span>
      <ul>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=adsense&amp;add=1&amp;main=1">
{$lang['add_new_adsense']}
</a></li>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=adsense&amp;control=1&amp;main=1">
{$lang['control_adsense']}
</a></li>
     </ul>
    </li>
{/if}


{if {$group_info['admincp_chat']} == 1}
<li><span class="headerbar"><a onClick="return false;" href="#"><span class="fa fa-bars" style="float:{$align};"></span>
{$lang['chat']}
<img  alt=""  style="float:{$desalign};" src="{$admincpdir_cssprefs}/collapse_b.gif" class="hide_m" /></a></span>
      <ul>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=chat&amp;control=1&amp;main=1">
{$lang['mange_chat']}
</a></li>
     </ul>
    </li>
{/if}



{if {$group_info['admincp_membergroup']} == 1}
<li><span class="headerbar"><a onClick="return false;" href="#"><span class="fa fa-bars" style="float:{$align};"></span>
{$lang['groups']}
<img  alt=""  style="float:{$desalign};" src="{$admincpdir_cssprefs}/collapse_b.gif" class="hide_m" /></a></span>
      <ul>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=groups&amp;add=1&amp;main=1">
{$lang['Add_new_group']}
</a></li>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=groups&amp;control=1&amp;main=1">
{$lang['mange_groups']}
</a></li>
     </ul>
    </li>
{/if}


{if {$group_info['admincp_extrafield']} == 1}
<li><span class="headerbar"><a onClick="return false;" href="#"><span class="fa fa-bars" style="float:{$align};"></span>
{$lang['extrafields']}
<img  alt=""  style="float:{$desalign};" src="{$admincpdir_cssprefs}/collapse_b.gif" class="hide_m" /></a></span>
      <ul>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=extrafield&amp;add=1&amp;main=1">
{$lang['Add_new_extrafield']}
</a></li>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=extrafield&amp;control=1&amp;main=1">
{$lang['mange_extrafields']}
</a></li>
     </ul>
    </li>
{/if}


{if {$group_info['admincp_membertitle']} == 1}
<li><span class="headerbar"><a onClick="return false;" href="#"><span class="fa fa-bars" style="float:{$align};"></span>
{$lang['usertitles']}
<img  alt=""  style="float:{$desalign};" src="{$admincpdir_cssprefs}/collapse_b.gif" class="hide_m" /></a></span>
      <ul>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=usertitle&amp;add=1&amp;main=1">
{$lang['Add_new_usertitle']}
</a></li>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=usertitle&amp;control=1&amp;main=1">
{$lang['mange_usertitles']}
</a></li>
     </ul>
    </li>


<li><span class="headerbar"><a onClick="return false;" href="#"><span class="fa fa-bars" style="float:{$align};"></span>
{$lang['userrating']}
<img  alt=""  style="float:{$desalign};" src="{$admincpdir_cssprefs}/collapse_b.gif" class="hide_m" /></a></span>
      <ul>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=userrating&amp;add=1&amp;main=1">
{$lang['add_new_userrating']}
</a></li>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=userrating&amp;control=1&amp;main=1">
{$lang['control_userrating']}
</a></li>
     </ul>
    </li>
{/if}

{if {$group_info['admincp_admin']} == 1}
<li><span class="headerbar"><a onClick="return false;" href="#"><span class="fa fa-bars" style="float:{$align};"></span>
{$lang['moderators']}
<img  alt=""  style="float:{$desalign};" src="{$admincpdir_cssprefs}/collapse_b.gif" class="hide_m" /></a></span>
      <ul>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=moderators&amp;add=1&amp;main=1">
{$lang['Add_new_moderator']}
</a></li>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=moderators&amp;control=1&amp;main=1">
{$lang['mange_moderators']}
</a></li>
{if {$group_info['admincp_adminstep']} == 1}
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=moderators&amp;modaction=1&amp;main=1">
{$lang['shwo_modaction']}
</a></li>
     </ul>
    </li>
{/if}


{if {$group_info['admincp_subject']} == 1}
<li><span class="headerbar"><a onClick="return false;" href="#"><span class="fa fa-bars" style="float:{$align};"></span>
{$lang['trash']}
<img  alt=""  style="float:{$desalign};" src="{$admincpdir_cssprefs}/collapse_b.gif" class="hide_m" /></a></span>
      <ul>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=trash&amp;subject=1&amp;main=1">
{$lang['trash_subjects']}
</a></li>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=trash&amp;reply=1&amp;main=1">
{$lang['trash_reply']}
</a></li>
     </ul>
    </li>
{/if}

<li><span class="headerbar"><a onClick="return false;" href="#"><span class="fa fa-bars" style="float:{$align};"></span>
{$lang['subjects']}
<img  alt=""  style="float:{$desalign};" src="{$admincpdir_cssprefs}/collapse_b.gif" class="hide_m" /></a></span>
      <ul>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=subject&amp;close=1&amp;main=1">
{$lang['subjects_close']}
</a></li>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=subject&amp;review=1&amp;main=1">
{$lang['review_subjects']}
</a></li>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=subject&amp;attach=1&amp;main=1">
{$lang['subjects_attach']}
</a></li>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=subject&amp;mass_del=1&amp;main=1">
{$lang['subjects_del']}
</a></li>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=subject&amp;mass_move=1&amp;main=1">
{$lang['subjects_move']}
</a></li>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=subject&amp;deleted_subject=1&amp;main=1">
{$lang['deleted_subject']}
</a></li>
     </ul>
    </li>

<li><span class="headerbar"><a onClick="return false;" href="#"><span class="fa fa-bars" style="float:{$align};"></span>
{$lang['custom_bbcodes']}
<img  alt=""  style="float:{$desalign};" src="{$admincpdir_cssprefs}/collapse_b.gif" class="hide_m" /></a></span>
      <ul>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=custom_bbcode&amp;control=1&amp;main=1">
{$lang['control_custom_bbcodes']}
</a></li>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=custom_bbcode&amp;add=1&amp;main=1">
{$lang['add_custom_bbcode']}
</a></li>
     </ul>
    </li>

<li><span class="headerbar"><a onClick="return false;" href="#"><span class="fa fa-bars" style="float:{$align};"></span>
{$lang['feed_rss']}
<img  alt=""  style="float:{$desalign};" src="{$admincpdir_cssprefs}/collapse_b.gif" class="hide_m" /></a></span>
      <ul>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=feeder&amp;control=1&amp;main=1">
{$lang['postr_rss']}
</a></li>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=feeder&amp;add=1&amp;main=1">
{$lang['feed']}
</a></li>
     </ul>
    </li>
{/if}

{if {$group_info['admincp_multi_moderation']} == 1}
<li><span class="headerbar"><a onClick="return false;" href="#"><span class="fa fa-bars" style="float:{$align};"></span>
{$lang['multi_moderation']}
<img  alt=""  style="float:{$desalign};" src="{$admincpdir_cssprefs}/collapse_b.gif" class="hide_m" /></a></span>
      <ul>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=topic_mod&amp;add=1&amp;main=1">
{$lang['add_new_multi_moderation']}
</a></li>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=topic_mod&amp;control=1&amp;main=1">
{$lang['mange_multi_moderation']}
</a></li>
     </ul>
    </li>
{/if}

{if {$group_info['admincp_attach']} == 1}
<li><span class="headerbar"><a onClick="return false;" href="#"><span class="fa fa-bars" style="float:{$align};"></span>
{$lang['extensions']}
<img  alt=""  style="float:{$desalign};" src="{$admincpdir_cssprefs}/collapse_b.gif" class="hide_m" /></a></span>
      <ul>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=extension&amp;add=1&amp;main=1">
{$lang['add_new_extension']}
</a></li>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=extension&amp;control=1&amp;main=1">
{$lang['mange_extensions']}
</a></li>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=extension&amp;search=1&amp;main=1">
{$lang['search_extension']}
</a></li>
     </ul>
    </li>
{/if}

{if {$group_info['admincp_smile']} == 1}
<li><span class="headerbar"><a onClick="return false;" href="#"><span class="fa fa-bars" style="float:{$align};"></span>
{$lang['smiles']}
<img  alt=""  style="float:{$desalign};" src="{$admincpdir_cssprefs}/collapse_b.gif" class="hide_m" /></a></span>
      <ul>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=smile&amp;add=1&amp;main=1">
{$lang['add_new_smile']}
</a></li>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=smile&amp;control=1&amp;main=1">
{$lang['mange_smiles']}
</a></li>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=smile&amp;upload_smiles=1&amp;main=1">
{$lang['upload_smiles']}
</a></li>
     </ul>
    </li>
{/if}

{if {$group_info['admincp_icon']} == 1}
<li><span class="headerbar"><a onClick="return false;" href="#"><span class="fa fa-bars" style="float:{$align};"></span>
{$lang['icons']}
<img  alt=""  style="float:{$desalign};" src="{$admincpdir_cssprefs}/collapse_b.gif" class="hide_m" /></a></span>
      <ul>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=icon&amp;add=1&amp;main=1">
{$lang['add_new_icon']}
</a></li>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=icon&amp;control=1&amp;main=1">
{$lang['mange_icons']}
</a></li>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=icon&amp;upload_icons=1&amp;main=1">
{$lang['upload_icons']}
</a></li>
     </ul>
    </li>
{/if}


{if {$group_info['admincp_avater']} == 1}
<li><span class="headerbar"><a onClick="return false;" href="#"><span class="fa fa-bars" style="float:{$align};"></span>
{$lang['avatars']}
<img  alt=""  style="float:{$desalign};" src="{$admincpdir_cssprefs}/collapse_b.gif" class="hide_m" /></a></span>
      <ul>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=avatar&amp;add=1&amp;main=1">
{$lang['add_new_avatar']}
</a></li>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=avatar&amp;control=1&amp;main=1">
{$lang['mange_avatars']}
</a></li>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=avatar&amp;upload_avatars=1&amp;main=1">
{$lang['upload_avatars']}
</a></li>
     </ul>
    </li>

<li><span class="headerbar"><a onClick="return false;" href="#"><span class="fa fa-bars" style="float:{$align};"></span>
{$lang['Subscriptions_postal']}
<img  alt=""  style="float:{$desalign};" src="{$admincpdir_cssprefs}/collapse_b.gif" class="hide_m" /></a></span>
      <ul>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=emailed&amp;main=1">
{$lang['mange_postal']}
</a></li>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=emailed&amp;main_del=1">
{$lang['Subscriptions_del']}
</a></li>
     </ul>
    </li>
{/if}

{if {$group_info['admincp_warn']} == 1}
<li><span class="headerbar"><a onClick="return false;" href="#"><span class="fa fa-bars" style="float:{$align};"></span>
{$lang['warns']}
<img  alt=""  style="float:{$desalign};" src="{$admincpdir_cssprefs}/collapse_b.gif" class="hide_m" /></a></span>
      <ul>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=warn&amp;main=1">
{$lang['View_warns']}
</a></li>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i>
<a href="index.php?page=warn&amp;del=1" onclick="return confirm('{$lang['confirm']}')">
{$lang['warn_del']}
</a></li>
     </ul>
    </li>
{/if}


{if {$group_info['admincp_award']} == 1}
<li><span class="headerbar"><a onClick="return false;" href="#"><span class="fa fa-bars" style="float:{$align};"></span>
{$lang['awards']}
<img  alt=""  style="float:{$desalign};" src="{$admincpdir_cssprefs}/collapse_b.gif" class="hide_m" /></a></span>
      <ul>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=award&amp;add=1&amp;main=1">
{$lang['add_new_award']}
</a></li>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=award&control=1&main=1">
{$lang['control_awards']}
</a></li>
     </ul>
    </li>
{/if}


{if {$group_info['admincp_fixup']} == 1}
<li><span class="headerbar"><a onClick="return false;" href="#"><span class="fa fa-bars" style="float:{$align};"></span>
{$lang['addons_pbb']}
<img  alt=""  style="float:{$desalign};" src="{$admincpdir_cssprefs}/collapse_b.gif" class="hide_m" /></a></span>
      <ul>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=addons&amp;add=1&amp;main=1">
{$lang['add_addons']}
</a></li>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=addons&amp;control=1&amp;main=1">
{$lang['control_addons']}
</a></li>
<!--
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=auto_addons&amp;add=1&amp;main=1">
PBBoard Auto Add-ons
</a></li>
-->
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=addons&amp;writing_addon=1&amp;main=1">
{$lang['writing_addon']} (Hook)
</a></li>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=addons&amp;control_hooks=1&amp;main=1">
{$lang['control_hooks']} (Hooks)
</a></li>

     </ul>
    </li>

<li><span class="headerbar"><a onClick="return false;" href="#"><span class="fa fa-bars" style="float:{$align};"></span>
{$lang['Maintenance']}
<img  alt=""  style="float:{$desalign};" src="{$admincpdir_cssprefs}/collapse_b.gif" class="hide_m" /></a></span>
      <ul>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=backup&amp;backup=1&amp;main=1">
{$lang['backup']}
</a></li>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=fixup&amp;repair=1&amp;main=1">
{$lang['repair']}
</a></li>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=sql&amp;sql=1&amp;main=1">
{$lang['sql']}
</a></li>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=fixup&amp;update_meter=1&amp;main=1">
{$lang['fixup']}
</a></li>
<li><i class="fa fa-dot-circle-o" aria-hidden="true" style="float:{$align};"></i><a href="index.php?page=fixup&amp;info=1">
{$lang['phpinfo']}
</a></li>
     </ul>
    </li>
{/if}

<!-- action_find_addons_4 -->

<!--
    <li>
      <span class="headerbar"><a onClick="return false;" href="#">Services</a>
      <ul>
            <li><a onClick="return false;" href="#">Private Server</a></li>
            <li><a onClick="return false;" href="#">Managed Hosting</a></li>
     </ul>
    </li>
-->


  </ul>
</nav>


</div>



<div id="treecontrol">
<a href="#"></a><a href="#"></a><a href="#"></a>
</div>



