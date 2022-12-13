<link rel="stylesheet" href="{$ForumAdress}look/sceditor/minified/themes/default.min.css" id="theme-style" />
<script src="{$ForumAdress}look/sceditor/minified/formats/bbcode.js"></script>
<script src="{$ForumAdress}look/sceditor/minified/PBB_bbcode.js"></script>
<script src="look/sceditor/languages/{$_CONF['info_row']['content_language']}.js"></script>

 <textarea cols="50" id="box_text" name="text" style="height:200px;width:100%;" dir="{$_CONF['info_row']['content_dir']}"> </textarea>
	<?php
if($PowerBB->functions->mention_permissions())
{
if ($PowerBB->_GET['page'] == "topic")
{
$PowerBB->_CONF['template']['mention'] 	= 	'mention,';
}
}
	$PowerBB->functions->GetEditorTools();
	 $Smiles = array();
	$counter = 0;
	?>
	{Des::while}{SmileRows}
	<?php
	$counter++;
	$Smiles[] = "|".$PowerBB->_CONF['template']['while']['SmileRows'][$this->x_loop]['smile_short']."^||".$PowerBB->_CONF['template']['while']['SmileRows'][$this->x_loop]['smile_path']."|!";
	if ( $counter >= $PowerBB->_CONF['info_row']['smiles_nm'] ) {
	 break;
	}
	?>
	{/Des::while}
	<?php
	$SmilesAll = array();
	 ?>
	{Des::while}{SmlList}
	<?php
	$SmilesAll[] = "|".$PowerBB->_CONF['template']['while']['SmlList'][$this->x_loop]['smile_short']."^||".$PowerBB->_CONF['template']['while']['SmlList'][$this->x_loop]['smile_path']."|!";
	?>
	{/Des::while}
	<?php
	 $Sm = "
	 ";
	$RowSmilePath 	= 	implode(",",$Smiles);
	$RowSmilePath 	= 	str_replace('||','" : "',$RowSmilePath);
	$RowSmilePath 	= 	str_replace('|','"',$RowSmilePath);
	$RowSmilePath 	= 	str_replace('^','',$RowSmilePath);
	$RowSmilePath 	= 	str_replace(',',"",$RowSmilePath);
	$RowSmilePath 	= 	str_replace('!',",!",$RowSmilePath);
	$RowSmilePath 	= 	str_replace("!",$Sm,$RowSmilePath);
	$PowerBB->_CONF['template']['RowSmilePath'] 	= 	$RowSmilePath;

	$RowSmilePathAll 	= 	implode(",",$SmilesAll);
	$RowSmilePathAll 	= 	str_replace('||','" : "',$RowSmilePathAll);
	$RowSmilePathAll 	= 	str_replace('|','"',$RowSmilePathAll);
	$RowSmilePathAll 	= 	str_replace('^','',$RowSmilePathAll);
	$RowSmilePathAll 	= 	str_replace(',',"",$RowSmilePathAll);
	$RowSmilePathAll 	= 	str_replace('!',",!",$RowSmilePathAll);
	$RowSmilePathAll 	= 	str_replace("!",$Sm,$RowSmilePathAll);
	$PowerBB->_CONF['template']['RowSmilePathAll'] 	= 	$RowSmilePathAll;
	?>
<script type="text/javascript">
			var textarea = document.getElementById('box_text');
			var partialmode = 0;
			sceditor.create(textarea, {
			    plugins: 'undo',
				format: 'bbcode',
				bbcodeTrim: false,
				style: "{$ForumAdress}look/sceditor/minified/themes/content/default.min.css",
			    fonts: "Arial,Arial Black,Tahoma,Droid Arabic Kufi,Georgia,Impact,Sans-serif,Serif,Times New Roman,Comic Sans MS,Courier New,Trebuchet MS,Verdana",
				{$_CONF['info_row']['content_dir']}: 'bool',
				width: "100%",
				enablePasteFiltering: true,
				emoticonsEnabled: true,

				emoticonsRoot: "{$ForumAdress}",
				emoticons:{
				    dropdown: {
				    	{$RowSmilePath}
				    	},
				    // Emoticons to be included in the more section
				    more: {
				        {$RowSmilePathAll}
				    },
				    // Emoticons that are not shown in the dropdown but will still
				    // be converted. Can be used for things like aliases
				    hidden: {
				        ':aliasforalien:': 'emoticons/alien.png',
				        ':aliasforblink:': 'emoticons/blink.png'
				    }
				},
              emoticonsCompat: true,
			    toolbar: "bold,italic,underline,strike|right,center,left,justify|font,size,color,removeformat|cut,copy,pastetext|bulletlist,orderedlist|table|codebrush,quote|horizontalrule,{$mention}image,email,link,unlink|emoticon,youtube,video|ltr,rtl|print,maximize,source",

});
$(function() {
	$("#box_text").sceditor(textarea);
	PBBEditor = $("#box_text").sceditor("instance");

});
</script>
<br />

