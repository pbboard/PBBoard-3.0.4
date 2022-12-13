{template}address_bar_part1{/template}
<a href="{$Adress}
index.php?page=portal">{$_CONF['info_row']['title_portal']}</a>
{template}address_bar_part2{/template}
{if {$active_right} and !{$active_left}}
<?php
$column = "80%" ;
?>
{elseif {$active_right} and {$active_left}}
<?php
$column = "60%" ;
?>
{else}
<?php
$column = "100%" ;
?>
{/if}
<style type="text/css">
.wd-col{
width:{$columns};
}
</style>
<br />
<!-- table -->
<div style="width:100%; border-spacing: 3px; direction:rtl;" class="table center_text_align">
<dl class="center_text_align">
<dt></dt>
<dd class="v-align-t p-center">
<?php
$center_blocks_info = $PowerBB->DB->sql_query("SELECT  *   FROM " . $PowerBB->prefix."blocks" . " WHERE place_block = 'center' AND active = '1' ORDER BY sort ASC");
while ($get_center_blocks_row = $PowerBB->DB->sql_fetch_array($center_blocks_info))
{
$center_blocks = $get_center_blocks_row['text'];
?>
<table class="border_radius wd100 brd1 clpc0">
<tr>
<tr class="center_text_align">
<td class="tcat wd-col">
<?php echo $get_center_blocks_row['title'] ?></td>
</tr>
<td class="blocks_info va-t">
<?php
if (strstr($center_blocks,"{template}"))
{
$center_blocks = str_replace('{template}',"",$center_blocks);
$center_blocks = str_replace('{/template}',"",$center_blocks);
$PowerBB->template->display($center_blocks);
//eval($center_blocks);
}
else
{
echo($center_blocks);
}
?>
</td>
</tr>
</table>
<br />
<?php
}
?>
</dd>
{if {$active_right}}
<dd class="v-align-t p-right">
<?php
$right_blocks_info = $PowerBB->DB->sql_query("SELECT  *   FROM " . $PowerBB->prefix."blocks" . " WHERE place_block = 'right' AND active = '1' ORDER BY sort ASC");
while ($get_right_blocks_row = $PowerBB->DB->sql_fetch_array($right_blocks_info))
{
$right_blocks = $get_right_blocks_row['text'];
?>
<table class="border_radius wd100 brd1 clpc0">
<tr>
<tr class="center_text_align">
<td class="tcat wd20">
<?php echo $get_right_blocks_row['title'] ?></td>
</tr>
<td class="blocks_info">
<?php
if (strstr($right_blocks,"{template}"))
{
$right_blocks = str_replace('{template}',"",$right_blocks);
$right_blocks = str_replace('{/template}',"",$right_blocks);
$PowerBB->template->display($right_blocks);
// eval($right_blocks);
}
else
{
echo($right_blocks);
}
?>
</td>
</tr>
</table>
<br />
<?php
}
?>
</dd>
{/if}
{if {$active_left}}
<dd  class="v-align-t p-left">
<?php
$left_blocks_info = $PowerBB->DB->sql_query("SELECT  *   FROM " . $PowerBB->prefix."blocks" . " WHERE place_block = 'left' AND active = '1' ORDER BY sort ASC");
while ($get_left_blocks_row = $PowerBB->DB->sql_fetch_array($left_blocks_info))
{
$left_blocks = $get_left_blocks_row['text'];
?>
<table class="border_radius wd100 brd1 clpc0">
<tr>
<tr class="center_text_align">
<td class="tcat wd20">
<?php echo $get_left_blocks_row['title'] ?></td>
</tr>		<td class="blocks_info">
<?php
if (strstr($left_blocks,"{template}"))
{
$left_blocks = str_replace('{template}',"",$left_blocks);
$left_blocks = str_replace('{/template}',"",$left_blocks);
$PowerBB->template->display($left_blocks);

//eval($left_blocks);
}
else
{
echo($left_blocks);
}		?>
</td>
</tr>
</table>
<br />
<?php
}
?>
</dd>
{/if}
</dl>
</div><!-- /table -->
<br />
<br />
<br />
<br />
<br />

{if {$active_right} and {$active_left}}
<style type="text/css">
.p-left{
width: 20%;
}
.p-right{
width: 25%;
float: right;
margin : 0px;
}
.p-center{
width: 75%;
float: left;
margin : 0px;
}
@media (max-width:980px) {
.p-left{
width: 30%;
}
.p-right{
width: 40%;
}
.p-center{
width: 60%;
}
}
@media (max-width:790px) {
.p-right, .p-center, .p-left{
display: inline-block !important;
margin : 0px;
float: none;
}
.p-right, .p-left{
width: 49%;
}
.p-center{
width: 100% !important;
}
}
@media screen and (max-width: 480px) {
.p-right, .p-left{
width: 100%;
}
}
</style>
{elseif !{$active_right} and {$active_left}}
<style type="text/css">
.p-left{
width: 25%;
}
.p-center{
width: 75%;
}
@media (max-width:980px) {
.p-left{
width: 30%;
}
.p-center{
width: 70%;
}
}
@media (max-width:790px) {
.p-left{
width: 40%;
}
.p-center{
width: 60%;
}
}
@media (max-width:600px) {
.p-center, .p-left{
display: inline-block !important;
margin : 0px;
float: none;
}
.p-center, .p-left{
width: 100% !important;
}
}
</style>
{elseif {$active_right} and !{$active_left}}
<style type="text/css">
.p-right{
width: 25%;
}
.p-center{
width: 75%;
}
@media (max-width:980px) {
.p-right{
width: 30%;
}
.p-center{
width: 70%;
}
}
@media (max-width:790px) {
.p-right{
width: 40%;
}
.p-center{
width: 60%;
}
}
@media (max-width:600px) {
.p-center, .p-right{
display: inline-block !important;
margin : 0px;
float: none;
}
.p-center, .p-right{
width: 100% !important;
}
}
</style>
{/if}