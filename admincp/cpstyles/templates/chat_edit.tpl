 <script type='text/javascript'>
//<![CDATA[
//path of images
		var path="../look/images/images_bbcode_editor/";
// show prompt or not 1 or 0
var prompt_bbcode = 1 ;
var cpaletc= 5 ;
var wbg= "#fff";
var wcolor= "#000";
// lang of the editor
		var l_undo="{$lang['l_undo']}";
		var l_redo="{$lang['l_redo']}";
		var l_b="{$lang['l_b']}";
		var l_u="{$lang['l_u']}";
		var l_i="{$lang['l_i']}";
		var l_remove="{$lang['l_remove']}";
		var l_p="{$lang['l_p']}";
		var l_link="{$lang['l_link']}";
		var l_email="{$lang['l_email']}";
		var l_flash="{$lang['l_flash']}";
		var l_media="{$lang['l_media']}";
		var l_ram="{$lang['l_ram']}";
		var l_link_p= 1;
		var l_youtube_p= 1;
		var l_email_p= 1;
		var l_flash_p= 1;
		var l_media_p= 1;
		var l_ram_p= 1;
		var url_enter_desc = "{$lang['url_enter_desc']}";
		var email_enter_desc = "{$lang['email_enter_desc']}";
		var l_unlink="{$lang['l_unlink']}";
		var l_youtube="{$lang['l_youtube']}";
		var l_image="{$lang['l_image']}";
		var l_jr="{$lang['l_jr']}";
		var l_jl="{$lang['l_jl']}";
		var l_jc="{$lang['l_jc']}";
		var l_ol="{$lang['l_ol']}";
		var l_ul="{$lang['l_ul']}";
		var l_quote="{$lang['l_quote']}";
		var l_code="{$lang['l_code']}";
		var l_phpcode="{$lang['l_phpcode']}";
		var l_rf="{$lang['l_rf']}";
		var l_out="{$lang['l_out']}";
		var l_ind="{$lang['l_ind']}";
		var l_size="{$lang['l_size']}";
		var l_font="{$lang['l_font']}";
		var l_para="{$lang['l_para']}";
		var l_ex="{$lang['l_ex']}";
		var l_con="{$lang['l_con']}";
		var url_enter="{$lang['url_enter']}";
		var email_enter="{$lang['email_enter']}";
		var image_enter="{$lang['image_enter']}";
	    var fontsarr=['Arial','Arial Black','Arial Narrow','Traditional Arabic','Book Antiqua','Century Gothic','Comic Sans MS','Courier New','Fixedsys','Franklin Gothic Medium','Garamond','Georgia','Impact','Lucida Console','Lucida Sans Unicode','Microsoft Sans Serif','Palatino Linotype','System','Tahoma','Times New Roman','Trebuchet MS','Verdana'];
        var direction="{$lang['direction']}";
		var l_exp="{$lang['l_exp']}";
		var l_s="{$lang['l_s']}";
		var l_hr="{$lang['l_hr']}";
		var l_sub="{$lang['l_sub']}";
		var l_sup="{$lang['l_sup']}";
        var l_sent ={$lang['l_sent']};
        var l_sent_value ={$lang['l_sent_value']};
        var l_flash_url="{$lang['l_flash_url']}";
        var l_flash_width="{$lang['l_flash_height']}";
        var l_flash_height="{$lang['l_flash_width']}";
        var l_media_url="{$lang['l_media_url']}";
        var l_ram_url="{$lang['l_ram_url']}";
        var size1="{$lang['size1']}";
        var size2="{$lang['size2']}";
        var size3="{$lang['size3']}";
        var size4="{$lang['size4']}";
        var size5="{$lang['size5']}";
        var change_editor="{$lang['change_editor']}";
        var must_disabled_bbcode_mode="{$lang['must_disabled_bbcode_mode']}";
        var insert_table="{$lang['insert_table']}";
		var rows_number="{$lang['rows_number']}";
		var columns_number="{$lang['columns_number']}";
		var l_frame="{$lang['l_frame']}";
		var l_gradient="{$lang['l_gradient']}";
		var l_keyboard="{$lang['l_keyboard']}";
		var smiles="{$lang['smiles']}";
        var should_mislead_or_select_text_first="{$lang['should_mislead_or_select_text_first']}";
		var l_poem="{$lang['l_poem']}";

//]]>
</script>
<script type="text/javascript" src="../includes/js/wseditor.js"></script>
<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
 <a href="index.php?page=chat&amp;control=1&amp;main=1">{$lang['chat']}</a> &raquo;
  {$lang['edit_chatMessage_Num']} :
  {$chatEdit['id']}</div>

<br />

<form action="index.php?page=chat&amp;edit=1&amp;start=1&amp;id={$chatEdit['id']}" method="post">
	<table width="80%" class="t_style_b" border="0" cellspacing="1" align="center">
		<tr align="center">
			<td class="main1" colspan="2">
  {$lang['edit_chatMessage_Num']} :
  {$chatEdit['id']}
			</td>
		</tr>
				<tr>
			<td class="row1" align="center" >
{$lang['writer']} : <input type="text" name="username" value="{$chatEdit['username']}" size="10" />
			</td>
			<td class="row1" align="center">
{$lang['country']} : <input type="text" name="country" value="{$chatEdit['country']}" size="10" />
</td>
					</tr>
		<tr>
			<td class="row1" align="center" colspan="2">
  {template}editor_simple{/template}
<textarea name="text" id="box_text" style="display:block;" class="editoriframe" tabindex="3" onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);" onchange="storeCaret(this)">
{$message}
</textarea>

   <script type="text/javascript">
   //<![CDATA[
    comm._toggle();
    comm._toggle();
//]]>
</script>
			</td>
		</tr>
			<tr>
				<td class="tbar_writer_info" colspan="2" align="center">
				{Des::while}{SmileRows}
				<img src="../{$SmileRows['smile_path']}" OnClick="AddSmileyIcon(' {$SmileRows['smile_short']} ');" alt="../{$SmileRows['smile_path']}" border="0" cellspacing="1" />
			{/Des::while}
				</td>
			</tr>
		<tr>
			<td class="row1" align="center" colspan="2">
			<input type="submit" value="{$lang['acceptable']}" name="submit" onclick="comm._submit();" /></td>

		</tr>
	</table>

	<br />

</form>