{if {$_CONF['info_row']['resize_imagesAllow']} == 1}
<script type="text/javascript" src="applications/core/colorbox-master/i18n/jquery.colorbox-{$_CONF['info_row']['content_language']}.js"></script>
<script type="text/javascript">
$(document).ready(function(){
$(".resize_img").colorbox({rel:'resize_img', width:"80%", height:"85%", scrolling:true});
$("#colorbox").draggable(true);
});
</script>
<script type="text/javascript">
<?php
echo "lang_Resize='".$PowerBB->_CONF['template']['lang']['resize_image_w_h']."';
";
?>
</script>
<script type="text/javascript" src="{$ForumAdress}includes/js/resize_images.js"></script>
{/if}
