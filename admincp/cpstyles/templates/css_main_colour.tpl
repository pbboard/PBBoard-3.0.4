<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=style&amp;colouredit=1&amp;main=1&amp;id={$Inf['id']}"> {$lang['edit']} :
{$Inf['style_title']} &raquo;
{$lang['colouredit_css']}</a> </div>

<br />
<form action="index.php?page=style&amp;colouredit=1&amp;start=1&amp;id={$Inf['id']}&amp;style_path={$Inf['style_path']}" method="post">
	<table cellpadding="3" cellspacing="1" width="100%" class="t_style_b" border="0" align="center" dir="{$_CONF['info_row']['content_dir']}">
<tr valign="top">
    <td class="main1">{$lang['CSS_Colours']}</td>
</tr>
<tr valign="top">
<td>
	<script type="text/javascript" src="../look/jscolor/jscolor.js"></script>

