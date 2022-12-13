{if {$_CONF['info_row']['style_block_latest_news']} =='1'}
{Des::while}{LastNews_subjectList}
<table class="border wd100 clp0" style="border-collapse: collapse;table-layout: fixed;">
<?php
$writer= $PowerBB->_CONF['template']['while']['LastNews_subjectList'][$this->x_loop]['writer'];
$Info_writer = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['member'] . " WHERE username = '$writer'");
$InfoWriter = $PowerBB->DB->sql_fetch_array($Info_writer);
$avater_path = $InfoWriter['avater_path'];
$PowerBB->_CONF['template']['while']['LastNews_subjectList'][$this->x_loop]['native_write_time'] = $PowerBB->sys_functions->time_ago($PowerBB->_CONF['template']['while']['LastNews_subjectList'][$this->x_loop]['native_write_time']);

?>
<tr>
<td class="thead">
<?php $PowerBB->_CONF['template']['while']['LastNews_subjectList'][$this->x_loop]['title'] = $PowerBB->Powerparse->censor_words($PowerBB->_CONF['template']['while']['LastNews_subjectList'][$this->x_loop]['title']);?>
<span class="r-right">
<img class="brd0" alt="" src="{$LastNews_subjectList['icon']}" />
<a href="index.php?page=topic&amp;show=1&amp;id={$LastNews_subjectList['id']}">{$LastNews_subjectList['title']}</a>
</span>
<span class="l-left">
<span class="smallfont">
{$lang['SubjectVisitor']}:
({$LastNews_subjectList['visitor']})
&nbsp;<span class="smallfont">{$LastNews_subjectList['native_write_time']}</span>
</span>
</span>
</td>
</tr>
<tr>
<td class="blocks_info">
<span class="l-left">
<span class="smallfont">
{$LastNews_subjectList['write_date']}
</span>
</span>
<span class="r-right">
<img class="brd0" src="look/portal/images/news_icon.gif" alt="Writer" />
{$lang['LastsPostsWriter']}
<a title="{$LastNews_subjectList['writer']}" href="index.php?page=profile&amp;show=1&amp;id=<?php echo $InfoWriter['id']; ?>">{$LastNews_subjectList['writer']}</a>
</span>		</td>
</tr>
<tr>
<td class="blocks_info va-t">
<span class="l-left UserPhoto_portal_last_news">
<?php if ($avater_path){ ?>
<a title="{$LastNews_subjectList['writer']}" href="index.php?page=profile&amp;show=1&amp;id=<?php echo $InfoWriter['id']; ?>">
<img src='<?php echo $avater_path; ?>' alt="Photo" />
</a>
<?php if ($avater_path ==''){ ?>
<a title="{$LastNews_subjectList['writer']}" href="index.php?page=profile&amp;show=1&amp;id=<?php echo $InfoWriter['id']; ?>">
<img src="{$image_path}/
{$_CONF['info_row']['default_avatar']}" class="brd0" alt="Photo" />
</a>
{/if}
{else}
<a title="{$LastNews_subjectList['writer']}" href="index.php?page=profile&amp;show=1&amp;id=<?php echo $InfoWriter['id']; ?>">
<img alt="" src="{$image_path}/
{$_CONF['info_row']['default_avatar']}" class="brd0" />
</a>
{/if}
</span>
<?php
$num =$PowerBB->_CONF['template']['_CONF']['info_row']['portal_news_along'];
$PowerBB->_CONF['template']['while']['LastNews_subjectList'][$this->x_loop]['text'] = $PowerBB->Powerparse->deletedalltags($PowerBB->_CONF['template']['while']['LastNews_subjectList'][$this->x_loop]['text'],$num);
?>
{$LastNews_subjectList['text']}
<br />
<br />
<br />
<div class="r-right"><a title="{$lang['read_more']}" href="index.php?page=topic&amp;show=1&amp;id={$LastNews_subjectList['id']}"><span class="read_more_button"> &nbsp; </span></a>
</div>
</td>
</tr>
</table>
<br />
{/Des::while}
{if {$PagerLastNews}}
<div class="center_text_align">
{$PagerLastNews}
</div>
{/if}

{else}
<?php $t= 0;?>
<!-- table --><div style="width:100%; border-collapse: collapse;" class="table center_text_align lasts-news">
<dl>
<dt></dt>
{Des::while}{LastNews_subjectList}
<?php
if ($PowerBB->_CONF['info_row']['portal_columns'] == '1' )
{
$columns_News = "3";
}
else
{
$columns_News = "2";
}
if($t== $columns_News){
$t=0;
echo "</dl><dl><dt></dt>";
}?>
<dd class="v-align-t">
<table class="border wd100n brd0 clp0 a-center" style="border-collapse: collapse">
<tr>
<td class="LastNews-tabclear va-t"><?php
$PowerBB->_CONF['template']['while']['LastNews_subjectList'][$this->x_loop]['title'] = $PowerBB->Powerparse->censor_words($PowerBB->_CONF['template']['while']['LastNews_subjectList'][$this->x_loop]['title']);
$title = $PowerBB->_CONF['template']['while']['LastNews_subjectList'][$this->x_loop]['title'];
$num = "25";
$title = $PowerBB->Powerparse->_wordwrap($title,$num);?>
<a href="index.php?page=topic&amp;show=1&amp;id={$LastNews_subjectList['id']}" title="<?php echo $title;?>"><?php echo $title;?></a>
</td>
</tr>
<tr>
<td class="rowthumb va-t">
<?php
$x = 1;
$images = array();
$images_sizes = $PowerBB->Powerparse->find_images_sizes($PowerBB->_CONF['template']['while']['LastNews_subjectList'][$this->x_loop]['text'],$images);
foreach ($images_sizes as $src)
{
if($src)
{
echo '<a href="index.php?page=topic&amp;show=1&amp;id='.$PowerBB->_CONF['template']['while']['LastNews_subjectList'][$this->x_loop]['id'].'" title="'.$title.'">
<img class="brd0" width="200" height="200" src="'.$src.'" alt="'.$PowerBB->_CONF['template']['while']['LastNews_subjectList'][$this->x_loop]['title'].'" /></a>';
}
break;
}
if(!$images_sizes)
{
echo '<a href="index.php?page=topic&amp;show=1&amp;id='.$PowerBB->_CONF['template']['while']['LastNews_subjectList'][$this->x_loop]['id'].'" title="'.$title.'">
<img class="brd0" width="200" height="200" src="look/portal/images/traffic_cone.png" title="'.$title.'" alt="'.$title.'" /></a>';
}

$num =$PowerBB->_CONF['template']['_CONF']['info_row']['portal_news_along'];
$text = $PowerBB->_CONF['template']['while']['LastNews_subjectList'][$this->x_loop]['text'];
$text = $PowerBB->Powerparse->deletedalltags($text,$num);
?>
<textarea dir="{$_CONF['info_row']['content_dir']}" class="r-right" style="height:100px" readonly="readonly"><?php echo $text; ?></textarea>
</td>
</tr>
<tr>
<td class="theadv va-t">
<a href="index.php?page=topic&amp;show=1&amp;id={$LastNews_subjectList['id']}" title="{$lang['read_more']}">
<div id="read_more_button">
{$lang['SubjectVisitor']}:
({$LastNews_subjectList['visitor']})
</div>
</a>
</td>
</tr>
</table>
<br />
</dd>
<?php $t= $t+1;?>
{/Des::while}
</dl>
</div><!-- /table -->
{if {$PagerLastNews}}
<table class="wd100 brd0 clp3" style="border-collapse: collapse"><tr>
<tr>
<td class="row3">
<span class="p-lasts-pager">
{$PagerLastNews}
</span>
</td>
</tr>
</table>
{/if}
{/if}