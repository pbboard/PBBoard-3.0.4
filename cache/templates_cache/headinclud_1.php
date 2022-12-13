<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="{$_CONF['info_row']['content_dir']}" lang="{$_CONF['info_row']['content_language']}">
<head>
<meta http-equiv="Content-Type" content="text/html; charset={$_CONF['info_row']['charset']}" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />

<meta name="keywords" content="{$keywords}" />
<meta name="description" content="{$description}" />
<meta itemprop="image" content="{$ForumAdress}look/images/pbboard-icon.png" />
<link rel="shortcut icon" href="{$ForumAdress}favicon.ico" type="image/x-icon" />

<!-- Start Social Media Meta Tags -->
<link rel="canonical" href="{$GetPageUrl}" />
<meta name="theme-color" content="#185886" />
<meta name="mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black" />
<meta name="apple-mobile-web-app-title" content="{$title}" />
<link rel="apple-touch-icon" href="{$ForumAdress}look/images/pbboard-icon.png" />

<meta property="og:description" content="{$description}" />
<meta property="og:url" content="{$GetPageUrl}" />
<meta property="og:site_name" content="{$_CONF['info_row']['title']}" />
<meta property="og:type" content="website" />
<meta property="og:title" content="{$title}" />
<meta property="og:image" content="{$ForumAdress}look/images/pbboard-icon.png" />

<meta property="twitter:title" content="{$title}" />
<meta property="twitter:description" content="{$description}" />
<meta property="twitter:image" content="{$ForumAdress}look/images/pbboard-icon.png" />
<meta property="twitter:card" content="summary" />

<!-- End Social Media Meta Tags -->
<title>{$title}</title>

<link rel="stylesheet" href="{$ForumAdress}look/fonts/font-awesome.min.css" />
<link rel="stylesheet" href="{$ForumAdress}look/fonts/fonts.css" />
<link rel="stylesheet" href="{$ForumAdress}{$style_path}" />
<link rel="stylesheet" href="{$ForumAdress}applications/core/colorbox-master/colorbox.css" />
<!-- CSS Stylesheet -->
<link rel="alternate" type="application/rss+xml" title="{$lang['rss_subject']}" href="
{$ForumAdress}index.php?page=rss&subject=1" />
<script type="text/javascript" src="{$ForumAdress}includes/js/jquery-1.10.2.js"></script>
<script type="text/javascript" src="{$ForumAdress}includes/js/jquery-ui.js"></script>
<script type="text/javascript" src="{$ForumAdress}includes/js/jquery.save.js"></script>
<script type="text/javascript" src="{$ForumAdress}applications/core/colorbox-master/jquery.colorbox.js"></script>
{if {$_CONF['info_row']['resize_imagesAllow']} == 1}
{template}imgs_resize{/template}
{/if}


<script type="text/javascript" src="{$ForumAdress}includes/js/pbboardCode.js"></script>
<script type="text/javascript" src="{$ForumAdress}includes/js/pbboard_global.js"></script>
<script type="text/javascript" src="{$ForumAdress}includes/js/pbboard_toggle.js"></script>


</head>
<body>
{template}header{/template}