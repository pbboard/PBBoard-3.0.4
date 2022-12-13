<br />

<div class="address_bar">{$lang['Control_Panel']}
 &raquo; <a href="index.php?page=adsense&amp;control=1&amp;main=1">{$lang['adsense']}</a>
  &raquo; <a href="index.php?page=adsense&edit=1&main=1&id={$AdsenseEdit['id']}">{$lang['adsense']}
  &raquo;{$lang['edit']} :
 {$AdsenseEdit['name']}</a></div>

<br />

<form action="index.php?page=adsense&amp;edit=1&amp;start=1&amp;id={$AdsenseEdit['id']}" method="post">
	<table width="98%" class="t_style_b" border="0" cellspacing="1" align="center">
		<tr align="center">
			<td class="main1" colspan="2">
			{$lang['edit']} :
			{$AdsenseEdit['name']}
			</td>
		</tr>
<tr>
			<td class="row2">
			{$lang['adsense_name']}
			 </td>
			<td class="row2">
				<input name="name" id="input_name" value="{$AdsenseEdit['name']}" size="30" type="text">
			</td>
		</tr>
		<tr>
			<td class="row1" colspan="2">
{$lang['Enter_the_ad_code']}
<br />
<b>{$lang['Writable_Bocuad_Html']}</b>
<br />
<textarea name="text" id="textarea_text" rows="15" cols="50" wrap="virtual" dir="ltr">{$AdsenseEdit['adsense']}</textarea>

			</td>
		</tr>
		<tr>
			<td class="row2">
			{$lang['Seen_in_the_top_of_the_page_Forum']}
			</td>
			<td class="row2">
				<select name="home" id="select_home">
		     {if {$AdsenseEdit['home']}}
			<option value="1" selected="selected">{$lang['yes']}</option>
			<option value="0">{$lang['no']}</option>
		    {else}
			<option value="1">{$lang['yes']}</option>
			<option value="0" selected="selected">{$lang['no']}</option>
		    {/if}
				</select>
			</td>
		</tr>
		<tr>
			<td class="row2">
{$lang['downfoot']}
			</td>
			<td class="row2">
				<select name="downfoot" id="select_downfoot">
			     {if {$AdsenseEdit['downfoot']}}
				<option value="1" selected="selected">{$lang['yes']}</option>
				<option value="0">{$lang['no']}</option>
			    {else}
				<option value="1">{$lang['yes']}</option>
				<option value="0" selected="selected">{$lang['no']}</option>
			    {/if}
				</select>
			</td>
		</tr>
		<tr>
			<td class="row2">
{$lang['all_page']}
			</td>
			<td class="row2">
				<select name="all_page" id="select_all_page">
			     {if {$AdsenseEdit['all_page']}}
				<option value="1" selected="selected">{$lang['yes']}</option>
				<option value="0">{$lang['no']}</option>
			    {else}
				<option value="1">{$lang['yes']}</option>
				<option value="0" selected="selected">{$lang['no']}</option>
			    {/if}
				</select>
			</td>
		</tr>
		<tr>
			<td class="row2">
{$lang['between_replys']}
			</td>
			<td class="row2">
				<select name="between_replys" id="select_between_replys">
			     {if {$AdsenseEdit['between_replys']}}
				<option value="1" selected="selected">{$lang['yes']}</option>
				<option value="0">{$lang['no']}</option>
			    {else}
				<option value="1">{$lang['yes']}</option>
				<option value="0" selected="selected">{$lang['no']}</option>
			    {/if}
				</select>
			</td>
		</tr>
		<tr>
			<td class="row2">
{$lang['down_topic']}
			</td>
			<td class="row2">
				<select name="down_topic" id="select_down_topic">
			     {if {$AdsenseEdit['down_topic']}}
				<option value="1" selected="selected">{$lang['yes']}</option>
				<option value="0">{$lang['no']}</option>
			    {else}
				<option value="1">{$lang['yes']}</option>
				<option value="0" selected="selected">{$lang['no']}</option>
			    {/if}
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
			     {if {$AdsenseEdit['in_page']} !=''}
				<input name="in_page" id="input_in_page" value="{$AdsenseEdit['in_page']}" size="70" type="text">
			    {else}
				<input name="in_page" id="input_in_page" value="" size="70" type="text">
			    {/if}

			</td>
		</tr>
		<tr>
			<td class="row2">
              {$lang['mid_topic']}
			</td>
			<td class="row2">
				<select name="mid_topic" id="select_mid_topic">
			     {if {$AdsenseEdit['mid_topic']}}
				<option value="1" selected="selected">{$lang['yes']}</option>
				<option value="0">{$lang['no']}</option>
			    {else}
				<option value="1">{$lang['yes']}</option>
				<option value="0" selected="selected">{$lang['no']}</option>
			    {/if}
				</select>
			</td>
		</tr>
		<tr>
			<td class="row1">
			{$lang['Seen_in_the_Inside_of_the_page_Forums']}
			</td>
			<td class="row1">
				<select name="forum" id="select_forum">
		     {if {$AdsenseEdit['forum']}}
			<option value="1" selected="selected">{$lang['yes']}</option>
			<option value="0">{$lang['no']}</option>
		    {else}
			<option value="1">{$lang['yes']}</option>
			<option value="0" selected="selected">{$lang['no']}</option>
		    {/if}
				</select>
			</td>
		</tr>
		<tr>
			<td class="row2">
			{$lang['Seen_in_the_Inside_of_the_page_subjects']}
			</td>
			<td class="row2">
				<select name="topic" id="select_topic">
			     {if {$AdsenseEdit['topic']}}
				<option value="1" selected="selected">{$lang['yes']}</option>
				<option value="0">{$lang['no']}</option>
			    {else}
				<option value="1">{$lang['yes']}</option>
				<option value="0" selected="selected">{$lang['no']}</option>
			    {/if}
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