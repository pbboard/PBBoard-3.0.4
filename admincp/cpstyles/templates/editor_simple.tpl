<script type="text/javascript" src="../includes/js/IEprompt.js"></script>
 <script type='text/javascript'>
{if {$_CONF['info_row']['content_language']} != 'ar'}
 function iePrompt(str){
   var settings = "dialogWidth: 290px; dialogHeight: 160px; center: yes; edge: raised; scroll: no; status: no;";
   return window.showModalDialog("../includes/js/iePrompt_en.htm", str, settings);
}
	{else}
 function iePrompt(str){
   var settings = "dialogWidth: 290px; dialogHeight: 160px; center: yes; edge: raised; scroll: no; status: no;";
   return window.showModalDialog("../includes/js/iePrompt_ar.htm", str, settings);
}
	{/if}
 function cbPrompt(str){
   try {
      if(window.showModalDialog){ return iePrompt(str); }
         else { return prompt(str, ""); }
   } catch (e) {
         return iePrompt(str);
   }
}

</script>

<script language="JavaScript">
//<![CDATA[
bbcode._Print("mini",100);
//]]>
</script>