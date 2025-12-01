<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=award&amp;control=1&amp;main=1">{$lang['awards']}</a> &raquo;
 {$lang['edit']} :
 {$AwardEdit['award']}</div>

<br />

<form action="index.php?page=award&amp;edit=1&amp;start=1&amp;id={$AwardEdit['id']}" method="post" enctype="multipart/form-data">

	<table width="70%" class="t_style_b" border="0" cellspacing="1" align="center">
		<tr align="center">
			<td class="main1" colspan="2">
{$lang['edit']} : {$AwardEdit['award']}
			</td>
		</tr>
		<tr>
			<td class="row1">
			{$lang['title_award']}</td>
			<td class="row1">
				<input type="text" name="award" value="{$AwardEdit['award']}" size="24" />
			</td>
		</tr>
		<tr>
			<td class="row1">{$lang['mem_name']}</td>
			<td class="row1">
				<input type="text" name="username" value="{$AwardEdit['username']}" size="24" />
			</td>
		</tr>
		<tr>
			<td class="row1">
			{$lang['image_award']}</td>
			<td class="row1">
			<img border="0" src="{$AwardEdit['award_path']}" width="150" height="150">
			<br />
				<input name="award_path" style="border: 1px solid #666;" type="file" value="{$AwardEdit['award_path']}" accept=".jpg, .jpeg, .png, .gif" size="25" dir="ltr" /><br /> النوع (mime) <b dir='ltr'><u> .jpg .jpeg .png .gif .bmp</u></b>
				</td>
		</tr>
		<tr>
			<td class="row1" colspan="2" align="center">
			<input type="submit" value="{$lang['acceptable']}" name="submit" /></td>
		</tr>
		</table>

	<br />

</form>