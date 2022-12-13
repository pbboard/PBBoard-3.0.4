{template}address_bar_part1{/template}
{if {$section_parent} == '0'}
<a href="index.php?page=forum&amp;show=1&amp;id={$sec_address_id}">
{$sec_address_title}
</a>
<div class="btn-nav"></div>
{/if}
{if !{$section_parent} == '0'}
<div class="btn-nav"></div>
<a href="index.php?page=forum&amp;show=1&amp;id={$section_info['parent']}">
{$sec_address_title}
</a>
<div class="btn-nav"></div>
{/if}	<a href="index.php?page=forum&amp;show=1&amp;id={$SECTION_ID}">
{$section_info['title']}
</a>
{template}address_bar_part2{/template}<br />
<br />
<div class="h_tag">
<?php
$num = '88';
$PowerBB->_CONF['template']['section_info']['section_describe'] = $PowerBB->functions->words_count_replace_strip_tags_html2bb($PowerBB->_CONF['template']['section_info']['section_describe'],$num);
$PowerBB->_CONF['template']['section_info']['title'] = 	$PowerBB->Powerparse->censor_words($PowerBB->_CONF['template']['section_info']['title']);
?>
<h1>
<a href="index.php?page=forum&amp;show=1&amp;id={$SECTION_ID}"
title="{$section_info['title']}">
{$section_info['title']}</a></h1>
<h3>{$section_info['section_describe']}</h3>
</div>
<br />{if {$SHOW_SUB_SECTIONS}}
{template}sections_list{/template}
{/if} <br />
{if {$section_info['parent']} != '0'}
<br />
{if {$section_info['header']} != ''}
<table class="border wd98 brd1 clpc0 a-center">
<tr class="center_text_align">
<td class="row1">
{$section_info['header']}
</td>
</tr>
</table>
<br />
<br />
<br />
{/if}
{template}forum_subject_table{/template}
{if {$section_info['footer']} != ''}
<br />
<table class="border wd98 brd1 clpc0 a-center">
<tr class="center_text_align">
<td class="row1">
{$section_info['footer']}
</td>
</tr>
</table>
<br />
{/if}
{/if}
<br />