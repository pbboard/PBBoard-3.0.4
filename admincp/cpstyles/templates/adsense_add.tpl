<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=adsense&amp;add=1&amp;start=1">{$lang['adsense']}</a>
&raquo; {$lang['add_new_adsense']} </div>

<br />

<form action="index.php?page=adsense&amp;add=1&amp;start=1" method="post">
	<table width="98%" class="t_style_b" border="0" cellspacing="1" align="center">
		<tr align="center">
			<td class="main1" colspan="2">
{$lang['add_new_adsense']}
			</td>
		</tr>
<tr>
			<td class="row2">
			{$lang['adsense_name']}
			</td>
			<td class="row2">
				<input name="name" id="input_name" value size="30" type="text">
			</td>
		</tr>
		<tr>
			<td class="row1" colspan="2">
{$lang['Enter_the_ad_code']}
<br />
<b>{$lang['Writable_Bocuad_Html']}</b>
<br />
<textarea name="text" id="textarea_text" rows="15" cols="50" wrap="virtual" dir="ltr"></textarea>

			</td>

		</tr>
		<tr>
			<td class="row2">
			{$lang['Seen_in_the_top_of_the_page_Forum']}
			</td>
			<td class="row2">
				<select name="home" id="select_home">
				<option value="1">{$lang['yes']}</option>
				<option value="0" selected="selected">{$lang['no']}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td class="row2">
{$lang['downfoot']}
			</td>
			<td class="row2">
				<select name="downfoot" id="select_down">
				<option value="1">{$lang['yes']}</option>
				<option value="0" selected="selected">{$lang['no']}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td class="row2">
{$lang['all_page']}
			</td>
			<td class="row2">
				<select name="all_page" id="select_allpage">
				<option value="1">{$lang['yes']}</option>
				<option value="0" selected="selected">{$lang['no']}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td class="row2">
{$lang['between_replys']}
			</td>
			<td class="row2">
				<select name="between_replys" id="select_between_reply">
				<option value="1">{$lang['yes']}</option>
				<option value="0" selected="selected">{$lang['no']}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td class="row2">
{$lang['down_topic']}
			</td>
			<td class="row2">
				<select name="down_topic" id="select_between_top">
				<option value="1">{$lang['yes']}</option>
				<option value="0" selected="selected">{$lang['no']}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td class="row2">
{$lang['specific_page']}
			</td>
			<td class="row2">
{$lang['enter_the_page_link']}
			<br />
				<input name="in_page" id="input_onepage" value="" size="70" type="text">
			</td>
		</tr>
		<tr>
			<td class="row2">
{$lang['mid_topic']}
			</td>
			<td class="row2">
				<select name="mid_topic" id="select_mid_topic">
				<option value="1">{$lang['yes']}</option>
				<option value="0" selected="selected">{$lang['no']}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td class="row1">
			{$lang['Seen_in_the_Inside_of_the_page_Forums']}
			</td>
			<td class="row1">
				<select name="forum" id="select_forum">
				<option value="1">{$lang['yes']}</option>
				<option value="0" selected="selected">{$lang['no']}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td class="row2">
			{$lang['Seen_in_the_Inside_of_the_page_subjects']}
						</td>
			<td class="row2">
				<select name="topic" id="select_topic">
				<option value="1">{$lang['yes']}</option>
				<option value="0" selected="selected">{$lang['no']}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td class="row2" colspan="2" align="center">
			<input type="submit" value="{$lang['acceptable']}" name="submit" /></td>
		</tr>
		</table>

	<br />



	<br />

</form>