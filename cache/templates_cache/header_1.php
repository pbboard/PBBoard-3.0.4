<!-- action_find_addons_1 -->
{template}header_bar{/template}
<!--pbb.logo.start-->
<header>
<div class="r-right">
<a title="{$_CONF['info_row']['title']}" href="index.php" style="margin-right:15px;margin-top: 12px;">
<img src="{$ForumAdress}styles/style_default/images/logo.png" width="200" height="80">
</a>
</div>
<div class="l-center"></div>
<div class="pbbsearch l-left">
<!-- Code search Menu start -->
{if {$_CONF['group_info']['search_allow']} == '1'}
<div id="submit_search">
<form name="search" action="index.php?page=search" method="get">
<input type="hidden" name="page" value="search" />
<input type="hidden" name="start" value="1"/>
<input type="hidden" name="search_only" value="1" />
<input type="hidden" name="sort_order" value="desc" />
<input type="hidden" name="section" value="all" />
<ul>
<li><input type="text" name="keyword" class="searchfield" value="{$lang['search_keyword']}" onclick="my_search()" onfocus="if (this.value == '{$lang['search_keyword']}') this.value = '';"
dir="{$_CONF['info_row']['content_dir']}" /></li>
<li><input type="submit" name="submit" class="submit_id" title="{$lang['start_search']}" /></li>
</ul>
<script type='text/javascript'>
function my_search() {
 $('#advsearch').show();
}
</script>
<div id="advsearch">
<select name="search_only" id="search_only_id">
<option value="1">{$lang['search_in_topics']}</option>
<option value="2">{$lang['Search_in_replies']}</option>
<option value="3">{$lang['Search_in_Titles']}</option>
</select>
<div class="advancedsearch">
<ul>
<li><input type="submit" value="{$lang['search']}" class="advancse" name="search"></li>
<li><input title="{$lang['advanced_search']}" type="button" class="advanc" onclick="window.open('index.php?page=search&amp;index=1','mywindow','')" value="{$lang['advanced_search']}" /></li>
</ul>
</div>
</div>
</form>
</div>
{/if}
</div>
<!-- Code search Menu End -->

</header>
{template}main_bar{/template}
<!-- action_find_addons_2 -->
