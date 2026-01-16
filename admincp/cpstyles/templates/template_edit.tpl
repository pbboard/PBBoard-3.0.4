<link rel="stylesheet" href="../includes/js/codemirror/codemirror.min.css">
<link rel="stylesheet" href="../includes/js/codemirror/addon/dialog/dialog.css">
<link rel="stylesheet" href="../includes/js/codemirror/addon/search/matchesonscrollbar.css">
<link rel="stylesheet" href="../includes/js/codemirror/theme/neat.css">
<link rel="stylesheet" href="../includes/js/codemirror/addon/display/fullscreen.css">

<script src="../includes/js/codemirror/codemirror.min.js"></script>

<script src="../includes/js/codemirror/addon/search/search.js"></script>
<script src="../includes/js/codemirror/addon/search/searchcursor.js"></script>
<script src="../includes/js/codemirror/addon/search/jump-to-line.js"></script>
<script src="../includes/js/codemirror/addon/dialog/dialog.js"></script>
<script src="../includes/js/codemirror/addon/display/fullscreen.js"></script>

<script src="../includes/js/codemirror/mode/html/html.js"></script>
<script src="../includes/js/codemirror/mode/php/php.js"></script>
<script src="../includes/js/codemirror/mode/css/css.js"></script>
<script src="../includes/js/codemirror/mode/clike/clike.js"></script>
<script src="../includes/js/codemirror/mode/xml/xml.js"></script>
<script src="../includes/js/codemirror/mode/javascript/javascript.js"></script>

<br />

<div class="address_bar">
{$lang['Control_Panel']}
<a href="index.php?page=template&amp;control=1&amp;main=1">{$lang['templates']}</a> &raquo;
<a href="index.php?page=template&amp;control=1&amp;show=1&amp;id={$StyleInfo['id']}">{$StyleInfo['style_title']}</a> &raquo;
<a href="index.php?page=template&amp;edit=1&main=1&amp;templateid={$TemplateEdit['templateid']}&amp;styleid={$StyleInfo['id']}">
{$lang['edit']} : {$filename}</a>
</div>

<br />

<form action="index.php?page=template&amp;edit=1&amp;start=1&amp;templateid={$TemplateEdit['templateid']}" method="post">
<table cellpadding="3" cellspacing="1" width="100%" style="max-width:95%; margin:auto;" class="t_style_b" border="0" align="center">
<tr valign="top" align="center">
    <td class="main1" colspan="2">{$lang['edit']} : {$filename}</td>
</tr>
<tr valign="top">
    <td class="row1">{$lang['Template_name']}</td>
    <td class="row1">{$filename}</td>
</tr>
<tr valign="top">
    <td class="row1">{$lang['TemplateForStyle']}</td>
    <td class="row1">{$StyleInfo['style_title']}</td>
</tr>
<tr valign="top">
    <td class="row1">{$lang['Free_on']}</td>
    <td class="row1">{$last_edit}</td>
</tr>
<tr valign="top">
    <td class="row1" colspan="2">

        <div style="
            width: 100%;
            max-width: 100%;
            margin: auto;
            padding: 8px 12px;
            background-color: #e7f3fe;
            border-left: 4px solid #2196F3;
            color: #0c5460;
            font-size: 13px;
            border-radius: 4px;
            margin-bottom: 5px;
        ">
    <b>ℹ️ تلميح:</b> اضغط على <b>زر البحث 🔍</b> للبحث داخل المحرر، أو على <b>زر الاستبدال ♻️</b> لاستبدال النصوص.
    بعد الضغط على زر الاستبدال، اكتب النص المراد تغييره ثم اضغط Enter لإظهار حقل النص البديل،
    بعدها ستظهر خيارات الاستبدال الفردي أو الكلي.
        </div>

<div style="text-align:right; width:100%; max-width:100%; margin:auto; margin-bottom:5px;">
    <button type="button" id="openSearch" style="padding:3px 6px; font-size:12px;">🔍 البحث</button>
    <button type="button" id="openReplace" style="padding:3px 6px; font-size:12px;">♻️ استبدال</button>
    <button type="button" id="toggleFullscreen" style="padding:3px 6px; font-size:12px;">🖥 تكبير المحرر</button>
    <button type="button" id="copyEditor" style="padding:3px 6px; font-size:12px;">📋 نسخ المحتوى</button>
<button type="button" id="exitFullscreen"
style="
display:none;
position:fixed;
top:10px;
right:10px;
z-index:9999;
padding:6px 10px;
font-size:13px;
background:#c0392b;
color:#fff;
border:0;
border-radius:4px;
cursor:pointer;
">
❌ إغلاق
</button>
</div>

        <div id="editorWrapper" style="width: 100%; max-width: 100%; margin:auto; border:1px solid #ccc;">
            <textarea style="width: 100%; max-width: 100%; margin:auto; border:1px solid #ccc;" rows="25" cols="50" name="context" dir="ltr" id="templateContent">{$context}</textarea>
        </div>
    </td>
</tr>
<tr valign="top" align="center">
    <td class="row2" colspan="2">
        <input type="button" onClick="location.href='index.php?page=template&amp;view=1&main=1&amp;templateid={$TemplateEdit['templateid']}&amp;styleid={$StyleInfo['id']}'" value='{$lang['ViewOrginaltemplate']}' />
    </td>
</tr>
<tr valign="top">
    <td class="row1" colspan="2" align="center">
        <input type="submit" value="{$lang['Save_and_reload']}" name="submit" />
    </td>
</tr>
</table>
</form>

<style>
#editorWrapper .CodeMirror {
    width: 100% !important;
    max-width: 100%;
    height: 500px;
    margin: auto;
    overflow: hidden !important;
}
.CodeMirror .cm-comment {
    background: transparent !important;
}
.CodeMirror-fullscreen {
    z-index: 9998;
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    var textarea = document.getElementById("templateContent");

    var editor = CodeMirror.fromTextArea(textarea, {
        lineNumbers: true,
        mode: "application/x-httpd-php",
        matchBrackets: true,
        theme: "neat",
        tabSize: 4,
        lineWrapping: true,
        extraKeys: {
            "Ctrl-F": "findPersistent",
            "Ctrl-G": "findNext",
            "Shift-Ctrl-G": "findPrev",
            "Ctrl-H": "replace"
        }
    });

    document.getElementById("openSearch").addEventListener("click", function() {
        editor.execCommand("findPersistent");
    });

    document.getElementById("openReplace").addEventListener("click", function() {
        editor.execCommand("replace");
    });

 var fullscreenBtn = document.getElementById("toggleFullscreen");
var exitBtn = document.getElementById("exitFullscreen");

fullscreenBtn.addEventListener("click", function() {
    editor.setOption("fullScreen", true);
    exitBtn.style.display = "block";
});

exitBtn.addEventListener("click", function() {
    editor.setOption("fullScreen", false);
    exitBtn.style.display = "none";
});

    document.getElementById("copyEditor").addEventListener("click", function() {
        navigator.clipboard.writeText(editor.getValue())
        .then(() => alert("تم نسخ المحتوى!"))
        .catch(() => alert("حدث خطأ أثناء النسخ."));
    });

    textarea.form.onsubmit = function() {
        editor.save();
    };
});
</script>
