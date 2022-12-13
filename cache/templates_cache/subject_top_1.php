{template}address_bar_part1{/template}
<?php
if($PowerBB->_CONF['template']['sec_main_id']){
$SecSubject3 			= 	array();
$SecSubject3['where'] 	= 	array('id',$PowerBB->_CONF['template']['sec_main_id']);
$Section_rwo3 = $PowerBB->core->GetInfo($SecSubject3,'section');
}
if($Section_rwo3['parent']){
$SecSubject4 			= 	array();
$SecSubject4['where'] 	= 	array('id',$Section_rwo3['parent']);

$Section_rwo4 = $PowerBB->core->GetInfo($SecSubject4,'section');
}

$SecSubject5 			= 	array();
$SecSubject5['where'] 	= 	array('id',$Section_rwo4['parent']);

$Section_rwo5 = $PowerBB->core->GetInfo($SecSubject5,'section');
if($Section_rwo5['id'])
{
?>
<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
<a href="index.php?page=forum&amp;show=1&amp;id=<?php echo $Section_rwo5['id']; ?>">
<span itemprop="title">
<?php echo $Section_rwo5['title']; ?>
</span>
</a>
</span>
<div class="btn-nav"></div>
<?php
}
if($Section_rwo4['id'])
{
?>
<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
<a href="index.php?page=forum&amp;show=1&amp;id=<?php echo $Section_rwo4['id']; ?>">
<span itemprop="title">
<?php echo $Section_rwo4['title']; ?>
</span>
</a>
</span>
<div class="btn-nav"></div>
<?php
}
?>
{if {$Section_rwo2['id']}}
<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
<a href="index.php?page=forum&amp;show=1&amp;id={$Section_rwo2['id']}">
<span itemprop="title">
{$Section_rwo2['title']}
</span>
</a>
</span>
<div class="btn-nav"></div>
{/if}

{if {$sec_main_id}}
<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
<a href="index.php?page=forum&amp;show=1&amp;id={$sec_main_id}">
<span itemprop="title">
{$sec_main_title}
</span>
</a>
</span>
<div class="btn-nav"></div>
{/if}
<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
<a href="index.php?page=forum&amp;show=1&amp;id={$section_info['id']}{$password}">
<span itemprop="title">
{$section_info['title']}
</span>
</a>
</span>
<div class="btn-nav"></div>
<span dir="{$_CONF['info_row']['content_dir']}">
{$Info['prefix_subject']}
</span>
<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
<a href="index.php?page=topic&amp;show=1&amp;id={$subject_id}">
<span itemprop="title">
{$subject_title}
</span>
</a>
</span>
{template}address_bar_part2{/template}

<!--
<br />
<br />
<div class="h_tag">
<?php
$num = '400';
$PowerBB->_CONF['template']['Subjectinfo']['text']   = 	$PowerBB->Powerparse->deletedalltags($PowerBB->_CONF['template']['Subjectinfo']['text'],$num);
$PowerBB->_CONF['template']['Subjectinfo']['text'] = $PowerBB->functions->Getkeywords($PowerBB->_CONF['template']['Subjectinfo']['text']);

$PowerBB->_CONF['template']['Subjectinfo']['text'] = $PowerBB->functions->CleanText($PowerBB->_CONF['template']['Subjectinfo']['text']);
$PowerBB->_CONF['template']['Subjectinfo']['text'] = $PowerBB->Powerparse->_wordwrap($PowerBB->_CONF['template']['Subjectinfo']['text'],$num);

$PowerBB->_CONF['template']['subject_title']   = 	$PowerBB->functions->CleanText($PowerBB->_CONF['template']['subject_title']);
$PowerBB->_CONF['template']['subject_title']   = 	$PowerBB->Powerparse->censor_words($PowerBB->_CONF['template']['subject_title']);

?>
<h1>{$subject_title}</h1>
 {if {$Subjectinfo['text']} !=''}
<h2>{$Subjectinfo['text']}</h2>
{/if}
</div>
<br />
-->
<!-- table -->
<div style="width:98%;" class="table center_text_align collapse">
<dl>
<dt></dt>
<dd class="right_text_align">
{if {$Subjectinfo['close']}}
<div class="r-right">
<div id="buttons_close">
{$lang['s_close']}
</div>
</div>
{if !{$mod_toolbar}}
{template}add_reply_link{/template}
{template}add_subject_link{/template}
{/if}
{else}
{template}add_reply_link{/template}
{template}add_subject_link{/template}
{/if}
</dd>
</dl>
<br />
<dl class="pages_mulet">
<dt></dt>
<dd class="pager_reply right_text_align">
{if {$pager_reply}}
{$pager_reply}
{/if}
</dd>
</dl>
</div>
<!-- /table -->
<br />
<script type="text/javascript" src="{$ForumAdress}includes/js/pbboard_topic.js"></script>
<script type="text/javascript">
var addquotebutton     = "img-submit";
var removequotebutton  = "img-quote-on";
</script>