<div class="nav_header_bar-top">
<nav id="nav_header_bar">
<ul class="p-navgroup">
<!-- aiction_find_adidons_2 -->
{if !{$_CONF['member_permission']}}
<li class="tabsup">
<a href="index.php?page=register&amp;index=1" title="{$lang['register']}"><i class="g_icon fa fa-user-plus"></i>  {$lang['register']} </a>
</li>
<!-- Start login Box-->
<li class="tabsup">
<a title="{$lang['login']}" id="login-trigger" href="#"><i class="g_icon fa fa-sign-in"></i> {$lang['login']} </a>
<div id="login-content">
<form method="post" action="index.php?page=login&amp;login=1">
<div id="inputs">
<input id="username" type="text" name="username" placeholder="{$lang['username']}" />
<input id="password" type="password" name="password" placeholder="{$lang['password']}" />
</div>
<div id="actions">
<input type="submit" class="submit-id" value="{$lang['login']}" />
<label><input type="checkbox" name="temporary" value="on" class="fp1" checked="checked" />  {$lang['Temp_login']}</label>
</div>
</form>
</div>
</li>
<!-- end login Box-->
{else}
<li id="userbar">
{if {$avater_change}}
<a href="index.php?page=usercp&amp;control=1&amp;avatar=1&amp;main=1" title="{$lang['Change_avatar']}">
{else}
<a href="index.php?page=profile&amp;show=1&amp;id={$_CONF['rows']['member_row']['id']}" title="{$lang['view_profile']}">
{/if}
<span class="UserPhoto_tiny" style="background-image: url({$avater_path});"></span>
</a>
<a id="userlink-trigger" href="#">
{$_CONF['rows']['member_row']['username']}  <i class="usermenu fa fa-caret-down"></i>
</a>
</li>

{if {$_CONF['rows']['member_row']['usergroup']} == 5}
<li class="userbar">
<a title="{$lang['send_active_code']}" href="index.php?page=forget&amp;active_member=1&amp;send_active_code=1"><i class="userbar_icon fa fa-key"></i></a>
</li>
{/if}
{if {$_CONF['info_row']['pm_feature']}}
<li class="userbar">
{if {$_CONF['rows']['group_info']['use_pm']} == 1}
<a title="{$lang['Private_Messages']}"  href="index.php?page=pm_list&amp;list=1&amp;folder=inbox"><i class="userbar_icon fa fa-envelope-o"></i><sup id="sup"><b>{$pm_num}</b></sup></a>
{/if}
</li>
{/if}
<li class="userbar">
<a title="{$lang['alerts']}" id="alerts-trigger" href="#"><i class="userbar_icon fa fa-bell-o"></i><sup id="sup"><b>{$all_alerts_num}</b></sup></a>
</li>
<li class="userlogout">
<a href="index.php?page=logout&amp;index=1" onclick="return confirm('{$lang['confirm']}')" title="{$lang['logout']}">
<i class="userbar_icon fa fa-sign-out"></i>
</a>
</li>
{/if}
<!-- actikon_find_addokns_nav -->
</ul>
</nav>
{if {$_CONF['member_permission']}}
{template}alerts{/template}
<!-- userlink_menu_start -->
<div id="userlink_menu">
<div class="PBB-WBS-Menu"></div>
<ul class="element_menu">
<li class="rof2 elm">
{if {$avater_change}}
<a href="index.php?page=usercp&amp;control=1&amp;avatar=1&amp;main=1" title="{$lang['Change_avatar']}">
{else}
<a href="index.php?page=profile&amp;show=1&amp;id={$_CONF['rows']['member_row']['id']}" title="{$lang['view_profile']}">
{/if}
<span class="UserPhoto_Menu r-right" style="background-image: url({$avater_path});background-size: 66px;"></span>
</a>
<div class="view_profile l-left">
<b>{$username_style}</b>
<br />
<span class="user_title">{$_CONF['rows']['member_row']['user_title']}</span>
</div>
</li>
<br />
<li class="Menu_title"> {$lang['Management_Profile']} </li>
<li class="Menu_item" data-menuitem="my profile"><a href="index.php?page=profile&amp;show=1&amp;id={$_CONF['rows']['member_row']['id']}" title="{$lang['view_profile']}">{$lang['view_profile']}</a></li>
<li class="Menu_item" data-menuitem="Your Personal Information"><a href="index.php?page=usercp&amp;control=1&amp;info=1&amp;main=1" title="{$lang['Your_Personal_Information']}">{$lang['Your_Personal_Information']}</a></li>
<li class="Menu_item" data-menuitem="user cp"><a href="index.php?page=usercp&amp;control=1&amp;setting=1&amp;main=1" title="{$lang['usercp']}">{$lang['usercp']}</a></li>
<li class="Menu_item" data-menuitem="setting security"><a href="index.php?page=privacy&amp;infosecurity=1&amp;main=1" title="{$lang['account_security_settings']}">{$lang['account_security_settings']}</a></li>
<li class="Menu_item" data-menuitem="setting email"><a href="index.php?page=usercp&amp;control=1&amp;email=1&amp;main=1" title="{$lang['Change_email']}">{$lang['Change_email']}</a></li>
<li class="Menu_item" data-menuitem="Change_Password"><a href="index.php?page=usercp&amp;control=1&amp;password=1&amp;main=1" title="{$lang['Change_password']}">{$lang['Change_password']}</a></li>
<li class="Menu_item" data-menuitem="Change avatar"><a href="index.php?page=usercp&amp;control=1&amp;avatar=1&amp;main=1" title="{$lang['Change_avatar']}">{$lang['Change_avatar']}</a></li>
<!-- user_Menu_item -->
<li class="Menu_item Menu_sep">
<i class="fa fa-sign-out"></i>
<a href="index.php?page=logout&amp;index=1" onclick="return confirm('{$lang['confirm']}')" title="{$lang['logout']}">
{$lang['logout']}</a>
</li>
</ul>
</div>
<!-- userlink_menu_end -->
{/if}
<script type="text/javascript">
$(document).ready(function(){
    $("#icon-mobile-menu").click(function(){
        $("#flip").slideToggle("slow");
    });
});
</script>
<div class="icon-mobile-men-top-right">
<div id="icon-mobile-menu" class=" open_menu_fixed">
<a title="Open Mobile Menu" href="#" aria-label="Open Menu" class="fa fa-bars fa-3x"> </a>
</div>
<div id="flip">
<ul style="display:inline;">
<li>
<span class="fa fa-search"></span>
<a href="index.php?page=search&amp;index=1" title="{$lang['search']}">
{$lang['search']}</a>
</li>
<li>
<span class="fa fa-home"></span>
<a href="index.php" title="{$_CONF['info_row']['title']}">
{$lang['forum']}</a></li>
{if {$_CONF['info_row']['active_portal']} == '1'}
<li>
<span class="fa fa-newspaper-o"></span>
<a href="index.php?page=portal" title="{$lang['portal']}">
{$_CONF['info_row']['title_portal']}</a></li>
{/if}
{if !{$_CONF['member_permission']}}
<li class="tabsup">
<a href="index.php?page=register&amp;index=1" title="{$lang['register']}"><i class="fa fa-user-plus"></i>
{$lang['register']} </a></li>
<!-- Start login Box-->
<li class="tabsup">
<a title="{$lang['login']}" href="index.php?page=login&amp;sign=1"><i class="fa fa-sign-in"></i>
{$lang['login']}</a></li>
<li>
<span class="fa fa-user-o"></span>
<a href="index.php?page=forget&amp;active_member=1&amp;send_active_code=1">
{$lang['send_active_code']}</a></li>
{/if}
<li>
<span class="fa fa-clipboard"></span>
<a href="index.php?page=latest_reply&amp;today=1" title="{$lang['latest_reply']}">{$lang['latest_reply']}</a></li>
{if {$_CONF['info_row']['active_team']} == '1'}
<li>
<span class="fa fa-hand-rock-o"></span>
<a href="index.php?page=team&amp;show=1" title="{$lang['team']}">
{$lang['team']}</a></li>
{/if}
{if {$_CONF['info_row']['active_send_admin_message']} == '1'}
<li>
<span class="fa fa-envelope-o"></span>
<a href="index.php?page=send&amp;sendmessage=1" title="{$lang['send_message']}">
{$lang['send_message']}</a></li>
{/if}


{if {$_CONF['info_row']['active_calendar']} == '1'}
<li>
<span class="fa fa-calendar"></span>
<a href="index.php?page=calendar&amp;show=1" title="calendar pkr">
{$lang['Calendar']}</a></li>
{/if}
<li>
<span class="fa fa-balance-scale"></span>
<a href="index.php?page=misc&amp;rules=1&amp;show=1" title="{$lang['rules']}">{$lang['rules']}</a></li>

<!-- actikon_find_addokns_1 -->
{if {$subject_special_nm} > '0'}
<li>
<span class="fa fa-star"></span>
<a href="index.php?page=special_subject&amp;index=1" title="{$lang['Special_Subject']}">{$lang['Special_Subject']}</a></li>
{/if}
<!-- acation_fidnd_adwdons_2 -->
{get_hook}main_bar_1{/get_hook}

{if {$_CONF['member_permission']}}

<li><span class="fa fa-user"></span>
<a href="index.php?page=profile&amp;show=1&amp;username={$_CONF['member_row']['username']}">
{$lang['Your_profile']}</a></li>

<li>
<span class="fa fa-arrow-circle-left"></span>
<a href="index.php?page=usercp&control=1&info=1&main=1">
{$lang['Your_Personal_Information']}</a></li>
<li>
<span class="fa fa-comments"></span>
<a href="index.php?page=usercp&amp;options=1&amp;subject=1&main=1">
{$lang['Your_Subjects']}</a></li>

{/if}
<!-- actiown_finwd_addons_6 -->
<!-- action_find_addosans_1 -->
{if {$_CONF['group_info']['memberlist_allow']} == '1'}
<li>
<span class="fa fa-users"></span>
<a href="index.php?page=member_list&amp;index=1&show=1" title="{$lang['memberlist']}">{$lang['memberlist']}</a></li>
{/if}
{if {$_CONF['member_permission']}}
<li>
<span class="fa fa-cogs"></span>
<a href="index.php?page=usercp&amp;control=1&amp;setting=1&amp;main=1" title="{$lang['usercp']}">{$lang['usercp']}</a></li>
<li>
<span class="fa fa-file-image-o"></span>
<a href="index.php?page=usercp&amp;control=1&amp;sign=1&main=1">
{$lang['Change_Signature']}</a></li>
<li>
<span class="fa fa-picture-o"></span>
<a href="index.php?page=usercp&amp;control=1&amp;avatar=1&main=1">
{$lang['Change_avatar']}</a></li>

{if {$_CONF['info_row']['pm_feature']}}
{if {$_CONF['rows']['group_info']['use_pm']} == 1}
<li>
<span class="fa fa-envelope"></span>
<a href="index.php?page=pm_list&list=1&amp;folder=inbox">
{$lang['Private_Messages']}</a></li>
{/if}
{/if}

{if {$_CONF['group_info']['onlinepage_allow']} == '1'}
<li>
<span class="fa fa-chain-broken"></span>
<a href="index.php?page=online&amp;show=1">
{$lang['Whos_Online']}</a></li>
{/if}

<li>
<span class="fa fa-sign-out"></span>
<a href="index.php?page=logout&amp;index=1" onclick="return confirm('{$lang['confirm']}')" title="{$lang['logout']}">
{$lang['logout']}</a>
</li>

{/if}

<!-- actioddn_find_addons_4 -->
{get_hook}main_bar_2{/get_hook}
</ul>
</div>
</div>
</div>
<!-- header_bar -->