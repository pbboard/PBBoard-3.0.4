{get_hook}header{/get_hook}
{if {$_CONF['info_row']['show_ads']}}
{template}ads{/template}
{/if}
{Des::while}{AdsensesList}
{if {$AdsensesList['all_page']}}
<div class="center_text_align">
{$AdsensesList['adsense']}
<br />
</div>
{/if}
{if {$AdsensesList['in_page']} != ''}
<?php
$Adsensesonepage = 'http://' . $PowerBB->_SERVER['HTTP_HOST'].$PowerBB->_SERVER['REQUEST_URI'];
if ($PowerBB->_CONF['template']['while']['AdsensesList'][$this->x_loop]['in_page'] == $Adsensesonepage)
{
?>
<div class="center_text_align">
{$AdsensesList['adsense']}
<br />
</div>
{/if}
{/if}
{/Des::while}
{if {$page} == 'index'}
{template}adsense_home{/template}
{/if}
{if {$_CONF['info_row']['activate_lasts_posts_bar']}}
{template}lasts_posts_bar{/template}
{/if}

{if !{$_CONF['member_permission']}}
<br />
<div class="visitors_bar">
<div class="vis_content">
{$lang['visitors_bar_message']}
</div>
</div>
<br />
{/if}
