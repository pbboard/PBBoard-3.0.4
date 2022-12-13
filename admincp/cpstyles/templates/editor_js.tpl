
	<?php
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
	if($PowerBB->_GET['page'] == 'pm')
	{	$PowerBB->_CONF['template']['format'] 	= 	'bbcode';
	}
	elseif($PowerBB->_GET['page'] == 'announcement')
	{
	$PowerBB->_CONF['template']['format'] 	= 	'bbcode';
	}
	elseif($PowerBB->_GET['page'] == 'topic_mod')
	{
	$PowerBB->_CONF['template']['format'] 	= 	'bbcode';
	}
	elseif($PowerBB->_GET['page'] == 'options')
	{
	$PowerBB->_CONF['template']['format'] 	= 	'bbcode';
	}
	else
	{
	$PowerBB->_CONF['template']['format'] 	= 	'xhtml';
	$PowerBB->_CONF['template']['sourceMode'] 	= 	'sceditor.instance(textarea).toggleSourceMode(false);';
	}
	?>
<script type="text/javascript">
{$sourceMode}

			var textarea = document.getElementById('text');
			sceditor.create(textarea, {
				format: '{$format}',
				style: "../look/sceditor/minified/themes/content/default.min.css",
			    fonts: "Arial,Arial Black,Tahoma,Droid Arabic Kufi,Georgia,Impact,Sans-serif,Serif,Times New Roman,Comic Sans MS,Courier New,Trebuchet MS,Verdana",
				{$_CONF['info_row']['content_dir']}: 'bool',
			toolbar: "bold,italic,underline,strike|right,center,left,justify|font,size,color,removeformat|bulletlist,orderedlist,table|horizontalrule,image,link,unlink,source",
				emoticonsCompat: 'bool',
				emoticonsRoot : "../",
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
				}

});



</script>


