 {if {$_CONF['info_row']['active_add_this']} == '1'}
<!-- AddToAny BEGIN -->
<div class="a2a_kit a2a_kit_size_24 a2a_default_style">
<a class="a2a_button_facebook a2a_counter"></a>
<a class="a2a_button_twitter a2a_counter"></a>
<a class="a2a_button_google_plus a2a_counter"></a>
<a class="a2a_dd" href="https://www.addtoany.com/share"></a>
</div>
<script type="text/javascript">
var a2a_config = a2a_config || {};
a2a_config.locale = "{$_CONF['info_row']['content_language']}";
a2a_config.num_services = 10;
a2a_config.show_title = 1;
a2a_config.exclude_services = ["email"];
</script>
<script type="text/javascript" src="//static.addtoany.com/menu/page.js"></script>
<!-- AddToAny END -->
{/if}