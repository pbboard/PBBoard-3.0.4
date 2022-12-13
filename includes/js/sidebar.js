$(document).ready(function() {
var boxWidth = $('#resizable').css('width');
    $(".sbp_tbuton").hide();
    $(".sbp_buton").click(function() {
        $(".sbp_sbar").animate({height: "hide", opacity: 0}, 500,
            function() {
                $(".sbp_forum").animate({width: "100%"}, 500);
            });
        $(this).hide();
        $(".sbp_tbuton").show();
        $.cookie("sbp_sbar","collapsed", {expires: 365});
        return false;
    });
    $(".sbp_tbuton").click(function() {
        $(".sbp_forum").animate({width: boxWidth}, 500,
            function() {
                $(".sbp_sbar").animate({height: "show", opacity: 1}, 500);
            });
        $(this).hide();
        $(".sbp_buton").show();
        $.cookie("sbp_sbar","expanded", {expires: 365});
        return false;
    });
    if($.cookie("sbp_sbar") == "collapsed") {
        $(".sbp_buton").hide();
        $(".sbp_tbuton").show();
        $(".sbp_forum").css("width","100%");
        $(".sbp_sbar").hide();
    };
});