 <script type='text/javascript'>
//<![CDATA[
function set_smile(X)
{
	var form = document.topic.text;

	form.value = form.value + " " + X + " ";
	form.focus();
}

function stopError() {
return true;
}

window.onerror = stopError;

function checkAll(form){
  for (var i = 0; i < form.elements.length; i++){
    eval("form.elements[" + i + "].checked = form.elements[0].checked");
  }
}

function delete_post(theURL)
{
       if (confirm("{$lang['confirm']}")) {
          window.location.href=theURL;
       }
       else {
          alert ("{$lang['alert_confirm']}");
       }
    }

function CopyCode(Code)
{
	CodeShow.Code.select(Code)
    CodeShow.Code.focus(Code)
}

function logout(theURL)
{
       if (confirm("{$lang['confirm']}")) {
          window.location.href=theURL;
       }
       else {
          alert ("{$lang['alert_confirm']}");
          return

       }
    }

function switchMenuNone(obj) {
	var el = document.getElementById(obj);
	if ( el.style.display != "none" ) {
		el.style.display = 'none';
	}
	else {
		el.style.display = '';
	}
}


function switchMenuWriter(obj) {
	var el = document.getElementById(obj);
	if ( el.style.display != "none" ) {
		el.style.display = 'none';
	}
	else {
		el.style.display = '';
	}
}



function switchMenuBlock() {
	if (document.getElementById('pages'))
	{	var el = document.getElementById('pages');
		el.style.display = 'none';
	}
	if (document.getElementById('usercptools'))
	{
	var el = document.getElementById('usercptools');
		el.style.display = 'none';
	}
	if (document.getElementById('alerts'))
	{
	var el = document.getElementById('alerts');
		el.style.display = 'none';
	}
	if (document.getElementById('[forum_tools]'))
	{
	var el = document.getElementById('[forum_tools]');
		el.style.display = 'none';
	}
	if (document.getElementById('subject_tools'))
	{
	var el = document.getElementById('subject_tools');
		el.style.display = 'none';
	}
	if (document.getElementById('pm_switch'))
	{
	var el = document.getElementById('pm_switch');
		el.style.display = 'none';
	}
	if (document.getElementById('{$subject_id}subjectswitch'))
	{
	var el = document.getElementById('{$subject_id}subjectswitch');
		el.style.display = 'none';
	}
	if (document.getElementById('{$Info['reply_id']}replyswitch'))
	{
	var el = document.getElementById('{$Info['reply_id']}replyswitch');
		el.style.display = 'none';
	}
}


window.onclick =   document.onclick=function() { switchMenuBlock(); };

function switchBlock(obj) {
	var el = document.getElementById(obj);
	if ( el.style.display != "block" ) {
		el.style.display = 'none';
			}
	else {
		el.style.display = 'block';
	}
}

//]]>
</script>

