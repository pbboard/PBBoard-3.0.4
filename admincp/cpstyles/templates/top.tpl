<header>
<ul class="top">
<li class="welcome {$aligndir}">
<b> {$_CONF['rows']['member_row']['username']}</b>  <span class="img-circle" style="background-image: url({$avater_path_admin});"></span>
</li>
<a href="index.php?page=index&amp;left=1">
<li style="float:{$align};margin-top:-40px;">
<img src="cpstyles/<?php echo $PowerBB->_CONF['info_row']['cssprefs'];?>/logo.png" width="200" height="80">
</li>
</a>
</ul>
</header>
<div id="primary_nav">
<div class="{$aligndir}">
<ul>
<li class="logout"> <a href="index.php?page=logout" target="_top">{$lang['logout_admin']}</a></li>
</ul>
</div>
<ul style="display:inline;">
<li class=""> <a target="_blank" href="../index.php">{$lang['home_forum']}</a></li>
<li class=""> <a href="index.php">{$lang['home_admin']}</a></li>
<li class=""> <a href="index.php?page=options&amp;index=1">{$lang['mange_forum']}</a></li>
<li class=""> <a href="index.php?page=forums&amp;control=1&amp;main=1">{$lang['mange_Forums']}</a></li>
<li class=""> <a href="index.php?page=style&amp;control=1&amp;main=1">{$lang['mange_styles']}</a></li>
<li class=""><a href="index.php?page=member&amp;control=1&amp;main=1">{$lang['mange_members']}</a></li>
<li class=""><a href="index.php?page=groups&amp;control=1&amp;main=1">{$lang['mange_groups']}</a></li>
</ul>
</div>