{if {$_CONF['info_row']['activate_special_bar']} == 1}
{template}special{/template}
{/if}
{template}address_bar_part1{/template}
{template}address_bar_part2{/template}
<!-- action_find_addons_1 -->
{template}sections_list{/template}
<!-- action_find_addons_2 -->
{template}main_static_table{/template}
<!-- action_find_addons_3 -->
{if {$_CONF['info_row']['pm_feature']} == 1}
{if {$_CONF['rows']['member_row']['pm_window']} == 1}
{if {$_CONF['rows']['member_row']['unread_pm']} > 0}
{template}pm_popup{/template}
{/if}
{/if}
{/if}
<br />
<br />
<!-- actirrWon_find_ddaddons_4 -->