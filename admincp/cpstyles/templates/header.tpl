<!doctype html>
<html dir="{$_CONF['info_row']['content_dir']}" itemscope="" itemtype="http://schema.org/WebPage" lang="{$_CONF['info_row']['content_language']}">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="shortcut icon" href="../favicon.ico" />
<title>{$lang['Control_Panel']}
{if {$_CONF['info_row']['allowed_powered']} == 1}
- {$lang['powered']}
{/if}</title>
<!-- action_find_addons_1 -->
<link rel="stylesheet" href="../{$admincpdir}/cpstyles/<?php echo $PowerBB->_CONF['info_row']['cssprefs'];?>/style.css" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset={$_CONF['info_row']['charset']}" />
<link rel="stylesheet" href="../look/fonts/font-awesome.min.css" />
<link rel="stylesheet" href="../look/fonts/fonts.css" />

<!-- action_find_addons_2 -->
<script  src="../includes/js/jquery-1.10.2.js"></script>
<link rel="stylesheet" href="cpstyles/<?php echo $PowerBB->_CONF['info_row']['cssprefs'];?>/hc-offcanvas-nav.css" />
<script src="../includes/js/hc-admin.js"></script>
<script  src="../includes/js/jquery.save.js"></script>
<script  src="../includes/js/jquery.treeview.js"></script>
<script  src="../includes/js/dev.js"></script>

<style>
.hc-nav-trigger{position:fixed;top:10px}
.hc-offcanvas-nav li:not(.custom-content) a{font-size: 13px; line-height: 13px; font-family:'Droid Arabic Kufi', 'Microsoft Sans Serif'}
.hc-offcanvas-nav.nav-levels-expand .nav-container ul ul .nav-item,.hc-offcanvas-nav.nav-levels-none .nav-container ul ul .nav-item{font-size: 13px; line-height: 13px; font-family:'Droid Arabic Kufi', 'Microsoft Sans Serif'}
.hc-offcanvas-nav ul,.hc-offcanvas-nav li {float: none;}
.hc-offcanvas-nav.rtl .nav-next span,.hc-offcanvas-nav.rtl .nav-back span,.hc-offcanvas-nav.rtl .nav-close span{left:0;right:220px;}
.hc-offcanvas-nav h2{font-size:16px;font-weight:normal;text-align:center;padding:20px 17px;color:#FFFFFF}
</style>

<script>
jQuery(document).ready(function($) {
$('#main-nav').hcOffcanvasNav({
disableAt: 1000,
{if {$_CONF['info_row']['content_dir']} == 'rtl'}
position : 'right',
rtl: 1,
{else}
position : 'left',
rtl: 0,
{/if}
levelOpen: 'overlap', // expand, overlap
disableBody: 1,
levelSpacing: 15,
closeActiveLevel: 1,
closeOpenLevels: 1,
closeOnClick: 1,
labelClose : 'إغلاق',
labelBack: 'عودة',
width: '290',
height: 'auto',
removeOriginalNav: 1,
});
 });


</script>

<style>
{if {$_CONF['info_row']['content_dir']} == 'rtl'}
.headerbar, li{text-align: right}
{else}
.headerbar, li{text-align: left}
{/if}
</style>

</head>
<body>

{if {$_CONF['member_permission']}}
{template}top{/template}

{if {$_CONF['info_row']['content_dir']} == 'rtl'}
<div class="use-sidebar sidebar-at-right" id="main">
{else}
<div class="use-sidebar sidebar-at-left" id="main">
{/if}


<div id="content">

{/if}

<!-- action_find_addons_3 -->