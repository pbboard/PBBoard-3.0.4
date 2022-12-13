function set_smile(X)
{var form=document.topic.text;form.value=form.value+" "+X+" ";form.focus();}
function stopError(){return true;}
window.onerror=stopError;function checkAll(form){for(var i=0;i<form.elements.length;i++){eval("form.elements["+i+"].checked = form.elements[0].checked");}}
function CopyCode(Code)
{CodeShow.Code.select(Code)
CodeShow.Code.focus(Code)}
function switchMenuNone(obj){var el=document.getElementById(obj);if(el.style.display!="none"){el.style.display='none';}
else{el.style.display='';}}
function collapse_toggle(obj){var el=document.getElementById(obj);var cname="pbboard_collapse_forumid_"+obj+"";var idv="collapseimg_forumbit_"+obj+"";var idforumbit=document.getElementById(idv);var forumCookie=Get_Cookie(cname);var img="collapseimage_"+obj+"";var imgChange=document.getElementById(img);var se="last_collapse_"+obj+"";var las=document.getElementById(se);if(obj!=forumCookie){$.cookie(cname,obj,{expires:365});$(this).next("#collapseimg_forumbit_"+obj+"").slideToggle("slow");$("#collapseimg_forumbit_"+obj+"").hide("slow");imgChange.className="collapsed";}
else
{$.cookie(cname,0,{expires:0});deleteCookie(cname);$(this).next("#collapseimg_forumbit_"+obj+"").slideToggle("slow");$("#collapseimg_forumbit_"+obj+"").show("slow");imgChange.className="expanded";}}
function Set_Cookie(name,value,expires,path,domain,secure)
{var today=new Date();today.setTime(today.getTime());if(expires)
{expires=expires*1000*60*60*24;}
var expires_date=new Date(today.getTime()+(expires));document.cookie=name+"="+escape(value)+((expires)?";expires="+expires_date.toGMTString():"")+((path)?";path="+path:"")+((domain)?";domain="+domain:"")+((secure)?";secure":"");}
function Get_Cookie(name){var start=document.cookie.indexOf(name+"=");var len=start+name.length+1;if((!start)&&(name!=document.cookie.substring(0,name.length)))
{return null;}
if(start==-1)return null;var end=document.cookie.indexOf(";",len);if(end==-1)end=document.cookie.length;return unescape(document.cookie.substring(len,end));}
function deleteCookie(name){Set_Cookie(name,'',-1);}
function switchMenuWriter(obj){var el=document.getElementById(obj);if(el.style.display!="none"){el.style.display='none';}
else{el.style.display='';}}
function switchMenuBlock(){if(document.getElementById('pages'))
{var el=document.getElementById('pages');el.style.display='none';}
if(document.getElementById('usercptools'))
{var el=document.getElementById('usercptools');el.style.display='none';}
if(document.getElementById("pagenav.1"))
{var el=document.getElementById("pagenav.1");el.style.display='none';}
if(document.getElementById("pagenav.2"))
{var el=document.getElementById("pagenav.2");el.style.display='none';}
if(document.getElementById("pagenav.3"))
{var el=document.getElementById("pagenav.3");el.style.display='none';}
if(document.getElementById("pagenav.4"))
{var el=document.getElementById("pagenav.4");el.style.display='none';}
if(document.getElementById("pagenav.5"))
{var el=document.getElementById("pagenav.5");el.style.display='none';}
if(document.getElementById("pagenav.6"))
{var el=document.getElementById("pagenav.6");el.style.display='none';}
if(document.getElementById("pagenav.7"))
{var el=document.getElementById("pagenav.7");el.style.display='none';}
if(document.getElementById("pagenav.8"))
{var el=document.getElementById("pagenav.8");el.style.display='none';}
if(document.getElementById("pagenav.9"))
{var el=document.getElementById("pagenav.9");el.style.display='none';}
if(document.getElementById("pagenav.0"))
{var el=document.getElementById("pagenav.0");el.style.display='none';}
if(document.getElementById('pager_top'))
{var el=document.getElementById('pager_top');el.style.display='none';}
if(document.getElementById('pager_duwn'))
{var el=document.getElementById('pager_duwn');el.style.display='none';}
if(document.getElementById('section_searchs_witch'))
{var el=document.getElementById('section_searchs_witch');el.style.display='none';}
if(document.getElementById('alerts'))
{var el=document.getElementById('alerts');el.style.display='none';}
if(document.getElementById('[forum_tools]'))
{var el=document.getElementById('[forum_tools]');el.style.display='none';}
if(document.getElementById('subject_tools'))
{var el=document.getElementById('subject_tools');el.style.display='none';}
if(document.getElementById('pm_switch'))
{var el=document.getElementById('pm_switch');el.style.display='none';}
if(document.getElementById('subjectswitch'))
{var el=document.getElementById('subjectswitch');el.style.display='none';}
if(document.getElementById("replyswitch"))
{var el=document.getElementById("replyswitch");el.style.display='none';}}
window.onclick=document.onclick=function(){switchMenuBlock();};function switchBlock(obj){var el=document.getElementById(obj);if(el.style.display!="block"){el.style.display='none';}
else{el.style.display='block';}}
function switchABlock(obj){var el=document.getElementById(obj);el.style.display='block';}
function sBlock(){window.onclick=document.onclick=function(){szBlock();};}
function szBlock(){if(document.getElementById('section_searchs_witch'))
{var el=document.getElementById('section_searchs_witch');el.style.display='block';}}
function pagerBlock(){window.onclick=document.onclick=function(){sXBlock();};}
function sXBlock(){if(document.getElementById('pager_top'))
{var el=document.getElementById('pager_top');el.style.display='block';}
if(document.getElementById('pager_duwn'))
{var el=document.getElementById('pager_duwn');el.style.display='block';}}