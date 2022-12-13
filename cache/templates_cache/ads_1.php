{if !{$No_Ads}}
<br />
<div class="center_text_align">
<a href="index.php?page=ads&amp;goto=1&amp;id={$AdsInfo['id']}" target="_blank">
<img src="{$AdsInfo['picture']}"
{if {$AdsInfo['width']} == '0'}
{else}
width="{$AdsInfo['width']}"
{/if}
{if {$AdsInfo['height']} == '0'}
{else}
height="{$AdsInfo['height']}"
{/if}
title="{$AdsInfo['sitename']}  {$lang['clicks_ads_num']}: {$AdsInfo['clicks']}" alt="{$AdsInfo['sitename']}" class="center_text_align brd0" />&nbsp;
</a><br /></div>
<br />
{/if}
