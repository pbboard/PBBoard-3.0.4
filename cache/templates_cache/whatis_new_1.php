<ul class="sidebar">
<li class="tcat">
<span>{$lang['whatis_new']}</span>
</li>
</ul>
<div class="row_sidebar">
<ul class="whatis_new">
{Des::while}{lastPostsList}
{if {$lastPostsList['title']} != ''}
{if {$lastPostsList['review_subject']} == 0 and {$lastPostsList['sec_subject']} == 0}
<?php
$PowerBB->_CONF['template']['while']['lastPostsList'][$this->x_loop]['title'] =  $PowerBB->Powerparse->_wordwrap($PowerBB->_CONF['template']['while']['lastPostsList'][$this->x_loop]['title'],'30');
if ($PowerBB->_CONF['template']['while']['lastPostsList'][$this->x_loop]['last_replier']!='')
{
$PowerBB->_CONF['template']['while']['lastPostsList'][$this->x_loop]['writer'] = $PowerBB->_CONF['template']['while']['lastPostsList'][$this->x_loop]['last_replier'];
}

?>
<li class="last_posts" style="padding: 0px 6px 10px 0px; margin: 1px; border-bottom: 0px dotted #CFCFCF !important;">
<ul class="PBBList_reset">
{if {$_CONF['info_row']['allow_avatar']}}
<li class="Photo_lastPoster" style="display:inline-block; margin-top:-3px; padding:2px; border: 1px solid rgb(165,227,228); width: 32px; height: 32px; -webkit-border-radius: 4px; -moz-border-radius: 4px; -khtml-border-radius: 4px; border-radius: 4px;">
<a title="{$lang['last_writer']}
{$lastPostsList['writer']}" href="index.php?page=profile&amp;show=1&amp;id={$lastPostsList['last_writer_id']}">
{if {$lastPostsList['avater_path']} != ''}
<?php
if (@!strstr($PowerBB->_CONF['template']['while']['lastPostsList'][$this->x_loop]['avater_path'],'http')
	or @!strstr($PowerBB->_CONF['template']['while']['lastPostsList'][$this->x_loop]['avater_path'],'www.'))
{
	if (@strstr($PowerBB->_CONF['template']['while']['lastPostsList'][$this->x_loop]['avater_path'],'download/avatar/')
	or @strstr($PowerBB->_CONF['template']['while']['lastPostsList'][$this->x_loop]['avater_path'],'look/images/avatar/'))
	{
	$PowerBB->_CONF['template']['while']['lastPostsList'][$this->x_loop]['avater_path'] = $PowerBB->_CONF['template']['ForumAdress'].$PowerBB->_CONF['template']['while']['lastPostsList'][$this->x_loop]['avater_path'];
    $PowerBB->_CONF['template']['while']['lastPostsList'][$this->x_loop]['avater_path'] = str_replace($PowerBB->_CONF['template']['ForumAdress'].$PowerBB->_CONF['template']['ForumAdress'], $PowerBB->_CONF['template']['ForumAdress'], $PowerBB->_CONF['template']['while']['lastPostsList'][$this->x_loop]['avater_path']);
	}
}

if ($PowerBB->functions->GetServerProtocol() == 'https://')
{
$https_  = "https://".$PowerBB->_SERVER['HTTP_HOST'];
$httpswww_  = "https://www.".$PowerBB->_SERVER['HTTP_HOST'];
$http_  = "http://".$PowerBB->_SERVER['HTTP_HOST'];
$http_www_  = "http://www.".$PowerBB->_SERVER['HTTP_HOST'];

$PowerBB->_CONF['template']['while']['lastPostsList'][$this->x_loop]['avater_path'] = str_replace($http_, $https_, $PowerBB->_CONF['template']['while']['lastPostsList'][$this->x_loop]['avater_path']);
$PowerBB->_CONF['template']['while']['lastPostsList'][$this->x_loop]['avater_path'] = @str_ireplace($http_, $https_, $PowerBB->_CONF['template']['while']['lastPostsList'][$this->x_loop]['avater_path']);
$PowerBB->_CONF['template']['while']['lastPostsList'][$this->x_loop]['avater_path'] = str_replace($http_www_, $httpswww_, $PowerBB->_CONF['template']['while']['lastPostsList'][$this->x_loop]['avater_path']);
$PowerBB->_CONF['template']['while']['lastPostsList'][$this->x_loop]['avater_path'] = @str_ireplace($http_www_, $httpswww_, $PowerBB->_CONF['template']['while']['lastPostsList'][$this->x_loop]['avater_path']);
}
?>
<span class="brd0" style="width: 32px; height: 32px; background-image: url({$lastPostsList['avater_path']});width: 32px; height: 32px; background-position: center; background-repeat: no-repeat; background-size: 32px; display: inline-block; vertical-align: middle;"></span>
{else}
<span class="brd0" style="background-image: url(<?php echo $PowerBB->_CONF['template']['ForumAdress'].$PowerBB->_CONF['template']['image_path'];?>/{$_CONF['info_row']['default_avatar']}); width: 32px; height: 32px; background-position: center; background-repeat: no-repeat; background-size: 32px; display: inline-block; vertical-align: middle;"></span>
{/if}
</a>
</li>
{else}
<li class="last_posts" style="display:inline-block; margin:2px; padding:3px;">
<span class="last_p">&nbsp;</span>&nbsp;
</li>
{/if}
<li class="last_subjectid" style="position:relative; vertical-align:middle; margin-top: -4px; display:inline-block;">
<a title="{$lastPostsList['title']}" href="index.php?page=topic&amp;show=1&amp;id={$lastPostsList['id']}">
{$lastPostsList['title']}</a>
<div>
<span class="last_time_ago"><?php echo $PowerBB->functions->_date($PowerBB->_CONF['template']['while']['lastPostsList'][$this->x_loop]['write_time']); ?></span>
</div>
</li>
</ul>
</li>
{/if}
{/if}
{/Des::while}
</ul>
</div>