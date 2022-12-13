{if {$_CONF['info_row']['sidebar_list_active']} and {$on_sidebar_list_thes_page}}
<script type="text/javascript" src="{$ForumAdress}includes/js/sidebar.js"></script>
{if {$_CONF['info_row']['sidebar_list_align']} == 'left'}
<style>
.sbp_buton,.sbp_tbuton
{
left: 15px;
}
</style>
{elseif {$_CONF['info_row']['sidebar_list_align']} == 'right'}
<style>
.sbp_buton,.sbp_tbuton
{
right: -7px;
}
</style>
{/if}
<a class="sbp_buton sb_btn" style="text-align: {$_CONF['info_row']['sidebar_list_align']};" title="{$lang['sbplus_sidebarac']}"></a>
<a class="sbp_tbuton sb_tbtn" style="text-align: {$_CONF['info_row']['sidebar_list_align']};" title="{$lang['sbplus_sidebarkapat']}"></a>
<div class="sbp_forum" id="resizable" style="float:{$opposite_direction}; width:{$sidebar_list_width}">
{/if}
