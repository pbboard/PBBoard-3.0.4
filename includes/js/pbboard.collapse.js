function toggle_collapse(B, E)
{var is_regexp=(window.RegExp)?true:false;
if(!is_regexp)
{
return false
}
var D=fetch_object("collapseobj_"+B);
var A=fetch_object("collapseimg_"+B);
var C=fetch_object("collapsecel_"+B);
if(!D){
if(A){A.style.display="none"
}
return false
}
if(D.style.display=="none"||"open"==E){
D.style.display="";
if(!E){
save_collapsed(B,false)
}
if(A){
img_re=new RegExp("_collapsed\\.gif$");
A.src=A.src.replace(img_re,".gif")
}
if(C){
cel_re=new RegExp("^(categorys|sub_forums)(_collapsed)$");
C.className=C.className.replace(cel_re,"$1")
}
}
else
{
if(D.style.display!="none"||"closed"==E)
{
D.style.display="none";
if(!E)
{
save_collapsed(B,true)
}
if(A)
{
img_re=new RegExp("\\.gif$");
A.src=A.src.replace(img_re,"_collapsed.gif")
}
if(C)
{
cel_re=new RegExp("^(categorys|sub_forums)$");
C.className=C.className.replace(cel_re,"$1_collapsed")
}
}
}
return false
}


function fetch_cookie(A)
{
	cookie_name=A+"=";
	cookie_length=document.cookie.length;
	cookie_begin=0;
	while(cookie_begin<cookie_length)
	{
	value_begin=cookie_begin+cookie_name.length;
	if(document.cookie.substring(cookie_begin,value_begin)==cookie_name)
	{
	var C=document.cookie.indexOf(";",value_begin);
	if(C==-1)
	{
	C=cookie_length
	}
	var B=unescape(document.cookie.substring(value_begin,C));
	console.log("Fetch Cookie :: %s = '%s'",A,B);
	return B
	}
	cookie_begin=document.cookie.indexOf(" ",cookie_begin)+1;
	if(cookie_begin==0)
	{
	break
	}
	}
	console.log("Fetch Cookie :: %s (null)",A);
	return null
}

function save_collapsed(A,E)
{
	var D=fetch_cookie("pbboard_collapse");
	var C=new Array();
	if(D!=null)
	{
	D=D.split("%s");
		for(var B in D)
		{
		if(YAHOO.lang.hasOwnProperty(D,B)&&D[B]!=A&&D[B]!="")
		{
	    C[C.length]=D[B]
	    }
	   }
	}
	if(E)
	{
	C[C.length]=A
	}
	expires=new Date();
	expires.setTime(expires.getTime()+(1000*86400*365));
	set_cookie("pbboard_collapse",C.join("%s"),expires)
	}

	function fetch_object(A)
	{
	if(document.getElementById)
	{
	return document.getElementById(A)
	}
	else
	{
	if(document.all)
	{
	return document.all[A]
	}
	else
	{
	if(document.layers)
	{
	return document.layers[A]
	}
	else
	{
	return null
	}
	}
	}
}

function set_cookie(B,C,A)
{
console.log("Set Cookie :: %s = '%s'",B,C);
document.cookie=B+"="+escape(C)+"; path=/"+(typeof A!="undefined"?"; expires="+A.toGMTString():"")
}


