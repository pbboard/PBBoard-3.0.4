<script type="text/javascript">
$(document).ready(function(){
$("#emenu_{$Info['id']}_{$Info['reply_id']}").click(function(){
$(this).next("#menue_{$Info['id']}_{$Info['reply_id']}").slideToggle("slow");
})

  $('.text').click(function(){
	$("#menue_{$Info['id']}_{$Info['reply_id']}").hide("fast");
	$("#menue_{$Info['id']}_{$Info['reply_id']}").css('display', 'none');
    })

});
</script>
<!-- table -->
<div class="writers_info">
<div style="vertical-align: top;" class="center_text_align w_photo">
<div class="UserPhoto_large center_text_align">
{if {$_CONF['info_row']['allow_avatar']} == '1'}
{if {$ReplierInfo['avater_path']} != ''}
<a rel="nofollow" href="index.php?page=profile&amp;show=1&amp;id={$ReplierInfo['id']}">
<img src="{$ReplierInfo['avater_path']}" class="brd0" title="{$lang['Picture']} {$ReplierInfo['username']} {$lang['Personal']}" alt="{$lang['Picture']}{$ReplierInfo['username']}{$lang['Personal']}" />
</a>
{else}
<a rel="nofollow" href="index.php?page=profile&amp;show=1&amp;id={$ReplierInfo['id']}">
<img src="{$image_path}/
{$_CONF['info_row']['default_avatar']}" class="brd0" alt="{$lang['no_photo']}" title="{$lang['no_photo']}"  />
</a>
{/if}
{/if}
<span class="user_online">
{if {$user_online}}
<i title="Online" class="Online fa fa-dot-circle-o" aria-hidden="true"></i>
{else}
 <i title="Offline" class="Offline fa fa-dot-circle-o" aria-hidden="true"></i>
{/if}
</span>
</div>
</div>
<div class="w_name_rate">
<!-- Code switch Menu start -->
<div id="emenu_{$Info['id']}_{$Info['reply_id']}">
{if {$ReplierInfo['username']} !=''}
<b class="bigusername">{$ReplierInfo['username']}</b>
{else}
<b class="bigusername">{$lang['Guest_']}</b>
{/if}
<span class="info-userArrow"></span>
</div>
<div class="border user_menue" style="display:none;position: absolute;z-index: 999999;" id="menue_{$Info['id']}_{$Info['reply_id']}">
<div class="thead">
{if {$ReplierInfo['username']} !=''}
<a href="index.php?page=profile&amp;show=1&amp;id={$ReplierInfo['id']}">
{$ReplierInfo['display_username']}</a>
{else}
<b>{$lang['Guest_']}</b>
{/if}
</div>
<div class="row1">
<a rel="nofollow" href="index.php?page=profile&amp;show=1&amp;id={$ReplierInfo['id']}">
{$lang['view_profile']}
</a>
</div>
<div class="row1">
<a href="index.php?page=search&amp;option=3&amp;username={$ReplierInfo['username']}&amp;starteronly=0&amp;section=all&amp;exactname=1&amp;sort_order=DESC">
{$lang['search_for_all_posts']}
{$ReplierInfo['username']}</a>
</div>
<div class="row1">
<a href="index.php?page=search&amp;option=4&amp;username={$ReplierInfo['username']}&amp;starteronly=0&amp;section=all&amp;exactname=1&amp;sort_order=DESC">
{$lang['search_for_all_replys']}
{$ReplierInfo['username']}</a>
</div>
{if {$_CONF['member_permission']}}
<div class="row1">
<a href="index.php?page=pm_send&amp;send=1&amp;index=1&amp;username={$ReplierInfo['username']}">
{$lang['send_a_private_message_to']}
{$ReplierInfo['username']} </a>
</div>
{/if}
{if {$_CONF['member_permission']}}
<div class="row1">
<a href="index.php?page=send&amp;member=1&amp;index=1&amp;id={$ReplierInfo['id']}">
{$lang['send_a_message_to_the_mailing']}
{$ReplierInfo['username']} </a>
</div>
{/if}
{if {$mod_edit_member}}
{if {$_CONF['member_permission']}}
<div class="row1">
<a target="_blank" href="{$admincpdir}?page=member&amp;edit=1&amp;main=1&amp;id=
{$ReplierInfo['id']}">
{$lang['edit_member_data']}
{$ReplierInfo['username']} </a>
</div>
{/if}
{/if}
</div>
<!-- Code switch Menu End -->
<!-- action_find_addons_1 -->
{if {$ReplierInfo['username']} !=''}
<div class="smallfont center_text_align">
{if {$GroupInfo['usertitle_change']} == 1}
{if {$Usertitle} != ''}
{$Usertitle}
{else}
{$ReplierInfo['user_title']}
{/if}
{else}
{if !{$ReplierInfo['user_title']}}
{$GroupInfo['user_title']}
{else}
{$ReplierInfo['user_title']}
{/if}
{/if}
</div>
<div class="center_text_align" style="margin-top:10px">
<img class="rating brd0" alt="rating" src="{$RatingInfo['rating']}" />
</div>
{/if}
<!-- action_find_addons_8 -->
</div>
<div class="w_others">
<!-- action_find_addons_7 -->
</div>
<div class="w_infos">
<!-- action_find_addons_2 -->
{get_hook}writer_info_top{/get_hook}
{if {$ReplierInfo['username']} !=''}
<div class="w_toggle" title="{$lang['information_writer']}">
<i class="w_toggle_writer fa fa-toggle-on fa-1x"><span class="w_writer"> {$lang['information_writer']} ▼</span></i>
</div>
<div class="writer_info right_text_align">
{$lang['join_date']} :
{$ReplierInfo['register_date']}
</div>
<div class="writer_info right_text_align">
{$lang['user_num']} :
{$ReplierInfo['id']}
</div>
<div class="writer_info right_text_align">
{$lang['posts']} :
{$ReplierInfo['posts']}
</div>
{if {$ReplierInfo['user_country']} != ''}
<div class="writer_info right_text_align">
{$lang['user_country']} :
{$ReplierInfo['user_country']}
</div>
{/if}
<div class="writer_info right_text_align">
{$lang['user_gender']} :
{if {$ReplierInfo['user_gender']} == 'm'}
<i title="{$lang['gender_m']}" class="fa fa-mars" aria-hidden="true"></i>
{else}
<i title="{$lang['gender_f']}" class="fa fa-venus" aria-hidden="true"></i>
{/if}
</div>
{if {$ReplierInfo['bday_year']} != ''}
{if {$ReplierInfo['bday_year']} != '0'}
<div class="writer_info right_text_align">
{$lang['Birth_date']} :
{$ReplierInfo['bday_day']}-{$ReplierInfo['bday_month']}-{$ReplierInfo['bday_year']}
</div>
{/if}
{/if}
{if {$ReplierInfo['invite_num']} != '0'}
<div class="writer_info right_text_align">
{$lang['Invites']} :
{$ReplierInfo['invite_num']}
</div>
{/if}
{if {$ReplierInfo['warnings']} > 0}
<div class="writer_info right_text_align">
{$lang['user_warnings']} :
{$ReplierInfo['warnings']}
</div>
{/if}
{if {$ReplierInfo['reputation']} != ''}
<div class="writer_info right_text_align">
{$lang['user_reputation']} :
{$ReplierInfo['reputation']}
</div>
<!-- action_find_addons_3 -->
{/if}
{if !empty({$ReplierInfo['user_website']}) and {$ReplierInfo['user_website']} != 'http://'}
<div class="writer_info right_text_align">
{$lang['UserWebsite']} :
<?php if (strstr($PowerBB->_CONF['template']['ReplierInfo']['user_website'],'http://')) {  ?>
<a target="_blank" href="{$ReplierInfo['user_website']}">{$lang['Visit_my_website']}</a>
<?php }else{ ?>
<a target="_blank" href="http://{$ReplierInfo['user_website']}">{$lang['Visit_my_website']}</a>
{/if}
</div>
{/if}
{if is_array({$while['extrafield']})==true }
{Des::while}{extrafield}
<?php if( $PowerBB->_CONF['template']['ReplierInfo'][ $PowerBB->_CONF['template']['while']['extrafield'][$this->x_loop]['name_tag'] ]!='' ){ ?>
<div class="writer_info right_text_align">
{$extrafield['name']} :
<?php $PowerBB->_CONF['template']['ReplierInfo'][ $PowerBB->_CONF['template']['while']['extrafield'][$this->x_loop]['name_tag'] ] = $PowerBB->functions->CleanVariable($PowerBB->_CONF['template']['ReplierInfo'][ $PowerBB->_CONF['template']['while']['extrafield'][$this->x_loop]['name_tag'] ],'html'); ?>
<?php echo $PowerBB->_CONF['template']['ReplierInfo'][ $PowerBB->_CONF['template']['while']['extrafield'][$this->x_loop]['name_tag'] ] ?>
</div>
<?php }else{ ?>
<?php } ?>
{/Des::while}
{/if}
<!-- action_find_addons_5 -->
{if {$Awards_nm} > '0'}
<div class="w_awards">
<div class="user_awards">
{template}awards{/template}
</div>
</div>
{/if}
<div class="center_text_align w_lasts">
<!-- action_find_addons_6 -->
{if !{$Info['reply_id']}}
{if {$_CONF['info_row']['show_list_last_5_posts_member']} == 1}
{template}last_subject_writer{/template}
{/if}
{/if}
</div>
<!-- action_find_addons_4 -->
{get_hook}writer_info_down{/get_hook}
</div>
{/if}
<!-- action_find_addons_6 -->
{if {$_CONF['info_row']['allow_apsent']} == '1'}
{if {$ReplierInfo['away']}}
<div class="w_absent">
<fieldset>
<legend><span class="smallfont">{$lang['user_Absent']}</span></legend>
<span class="smallfont">
{$ReplierInfo['away_msg']}
</span>
</fieldset>
</div>
{/if}
{/if}

<div class="center_text_align">
{if !{$admin_mod_toolbar}}
<a title="{$lang['send_warn_to_mem']}
{$ReplierInfo['username']}" href="index.php?page=warn&amp;index=1&amp;id={$ReplierInfo['id']}">
<i style="padding-left:10px;color: #CC9900;" class="fa fa-exclamation-triangle" aria-hidden="true"></i></a>
{/if}
{if {$_CONF['rows']['group_info']['admincp_allow']}}
{if {$ReplierInfo['member_ip']} != ''}
<i style="padding-left:10px;color: #008080;" title="User IP({$ReplierInfo['member_ip']})" class="fa fa-exclamation-circle" aria-hidden="true"></i>
{/if}
{/if}
</div>
</div>
<!-- /table -->