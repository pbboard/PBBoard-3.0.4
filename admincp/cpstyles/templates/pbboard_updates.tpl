
<script type='text/javascript'>
function AjaxUpdated()
{
var AjaxState = {ajaxSend : $("#status").html("<div class='row1'> <img border='0' src='{$admincpdir_cssprefs}/loading5.gif'></div>")};
var data = {};
data['ajax']	=	1;

$.post("index.php?page=fixup&pbboard_updates=1&start=1",data,function Success(xml) { $("#status").html(xml); });
if ($.post){
var field = document.getElementById('pbboard_new_updates');
field.style.display = 'none';
}

}


function Ready()
{
$("#Start_Updated").click(AjaxUpdated);
}

$(document).ready(Ready);

</script>
<br />

<div id="pbboard_new_updates">
{$lang['pbboard_new_updates']}
<br />
 <br />
<input type="button" class="button" id="Start_Updated" value="{$lang['Start_Updated']} >>" name="Start_Updated" />
</div>
<div id="status"> </div>
<br />