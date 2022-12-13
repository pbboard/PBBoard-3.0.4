{if {$_CONF['info_row']['sidebar_list_active']} and {$on_sidebar_list_thes_page}}
</div>
<div class="sbp_show">
<a class="sbp_buton sbp_foot" style="margin-top: -17px; float: {$_CONF['info_row']['sidebar_list_align']};" title="{$lang['sbplus_sidebarac']}">{$lang['hide_blocks']}</a>
<a class="sbp_tbuton sbp_tfoot" style="margin-top: -17px; float: {$_CONF['info_row']['sidebar_list_align']};" title="{$lang['sbplus_sidebarkapat']}">{$lang['show_blocks']}</a>
</div>
<div class="sbp_sbar" style="float: {$_CONF['info_row']['sidebar_list_align']};width: {$_CONF['info_row']['sidebar_list_width']}%;">
{info_row}sidebar_list_content{/info_row}
</div>
{/if}
<br />
<br />
<!-- acqqtion_find_adeedons_1 -->
<!-- AdsensesList_Start -->
{Des::while}{AdsensesList}
{if {$AdsensesList['downfoot']} == '1'}
<div class="adsense_downfoot center_text_align">
{$AdsensesList['adsense']}
<br />
</div>
{/if}
{/Des::while}
<!-- AdsensesList_End -->
<div class="time">
<i class="fa fa-clock-o" aria-hidden="true"></i>
{$lang['timer']}
{$timer}
</div>
{get_hook}footer_top{/get_hook}
<br />
<ul class="footer_bar">
<!-- actilon_find_alddons_3 -->
{if {$_CONF['info_row']['active_rss']} == '1'}
<li class="l-left">
<a class="farss" href="index.php?page=rss&subject=1" title="{$lang['rss_subject']}">
<i class="fa fa-rss fa-fw"></i></a>
</li>
{/if}
{if {$_CONF['rows']['group_info']['admincp_allow']}}
<li class="l-left">
<a href="{$admincpdir}">
<i class="fa fa-cogs"></i> {$lang['cp_admin']}</a>
</li>
{/if}
{if {$_CONF['info_row']['active_team']} == '1'}
<li class="l-left">
<a href="index.php?page=team&show=1"><i class="fa fa-hand-rock-o"></i> {$lang['team']}</a>
</li>
{/if}
{if {$_CONF['info_row']['active_send_admin_message']} == '1'}
<li class="l-left">
<a href="index.php?page=send&sendmessage=1"> <i class="fa fa-envelope-o fa-fw"></i> {$lang['send_message']}</a>
</li>
{/if}
{if {$_CONF['info_row']['active_subject_today']} == '1'}
<li class="l-left">
<a href="index.php?page=latest&today=1"><i class="fa fa-comments-o" aria-hidden="true"></i> {$lang['subject_today']}</a>
({$subject_today_nm})
</li>
{/if}
{if {$_CONF['info_row']['sitemap']} == '1'}
<li class="l-left">
<a href="index.php?page=sitemap&subject=1"><i class="fa fa fa-map-o"></i> XML Sitemap</a>
</li>
{/if}

<li class="l-left">
<a class="faarrow" title="{$lang['UP']}" onclick="self.scrollTo(0, 0); return false;" href="#top"> <i class="fa fa-arrow-circle-o-up fa-2x"></i> </a>
</li>
{if {$LangsNo} > 1}
<li class="r-right">
<select onchange="location='index.php?page=change_lang&change=1&id=' + this.value" size="1" name="lang">
{Des::while}{LangList}
<option value="{$LangList['id']}" {if {$LangList['id']} == {$lang_id}}selected="selected" style="background : #EEEEEE"{/if}>{$LangList['lang_title']}</option>
{/Des::while}
</select>
</li>
{/if}
{if {$StylesNo} > 1}
<li class="r-right">
<select onchange="location='index.php?page=change_style&change=1&id=' + this.value" size="1" name="style">
{Des::while}{StyleList}
<option value="{$StyleList['id']}" {if {$StyleList['id']} == {$_CONF['rows']['style']['id']}}selected="selected" style="background : #EEEEEE"{/if}>{$StyleList['style_title']}</option>
{/Des::while}
</select>
</li>
{/if}
<!-- action_finhd_addons_4 -->
</ul>

{get_hook}footer_bottom{/get_hook}
<!-- end_pbb_main -->
</div>
<!-- end_pbboard_body -->
</div>
<!-- end_pbboard_content -->
</div>
<div id="copyright">
<!--copyright-->
</div>

<br />
<!-- actiodfn_find_addons_5 -->
</body>
</html>
