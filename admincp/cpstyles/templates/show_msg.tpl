<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html dir="{$_CONF['info_row']['content_dir']}" xmlns="http://www.w3.org/1999/xhtml" xml:lang="{$_CONF['info_row']['content_language']}" lang="{$_CONF['info_row']['content_language']}">
<head>
<meta http-equiv="Content-Type" content="text/html; charset={$_CONF['info_row']['charset']}" />
<meta http-equiv="Content-Language" content="{$_CONF['info_row']['content_language']}" />
   <link rel="shortcut icon" href="favicon.ico" />
	<link rel="stylesheet" href="../{$admincpdir}/cpstyles/{$_CONF['info_row']['cssprefs']}/style.css" type="text/css" />
     <title>{$lang['Message_Forum']}</title>
</head>
	<body>
	<div id="border_msg" style="margin-{$align}: 110px;">
		<div class="main1" height="23">
<b>{$lang['Message_Forum']} </b>
		</div>
		<div class="row1" align="center">
		<p> {$msg} </p>
		</div>
	</div>
</body>
</html>