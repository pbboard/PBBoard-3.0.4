<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=adsense&amp;control=1&amp;main=1">{$lang['adsense']}</a></div>

<br />

<table width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
	<tr align="center">
		<td class="main1">
    {$lang['adsense_name']}
		</td>
		<td class="main1">
{$lang['adsense_placead']}
		</td>
		<td class="main1">
		{$lang['edit']}
		</td>
		<td class="main1">
		{$lang['Delet']}
		</td>
	</tr>
	{Des::while}{AdsensesList}
	<tr align="center">
		<td class="row1">
        {$AdsensesList['name']}
		</td>
		<td class="row1">
		{if {$AdsensesList['home']} == 1}
{$lang['Seen_in_the_top_of_the_page_Forum']}
<br />
        {/if}
		{if {$AdsensesList['forum']} == 1}
		{$lang['Seen_in_the_Inside_of_the_page_Forums']}
		<br />
        {/if}
		{if {$AdsensesList['topic']} == 1}
		{$lang['Seen_in_the_Inside_of_the_page_subjects']}
		<br />
        {/if}
		{if {$AdsensesList['downfoot']} == 1}
{$lang['downfoot']}
<br />
        {/if}
		{if {$AdsensesList['mid_topic']} == 1}
{$lang['mid_topic']}
<br />
        {/if}
		{if {$AdsensesList['all_page']} == 1}
{$lang['all_page']}
<br />
        {/if}
		{if {$AdsensesList['between_replys']} == 1}
{$lang['between_replys']}
<br />
        {/if}
		{if {$AdsensesList['down_topic']} == 1}
{$lang['down_topic']}
<br />
        {/if}
		{if {$AdsensesList['in_page']} !=''}
{$lang['specific_page']}
<br />
        {/if}
		</td>
		<td class="row1">
			<a href="index.php?page=adsense&amp;edit=1&amp;main=1&amp;id={$AdsensesList['id']}">{$lang['edit']}</a>
		</td>
		<td class="row1">
			<a href="index.php?page=adsense&amp;del=1&amp;start=1&amp;id={$AdsensesList['id']}">{$lang['Delet']}</a>
		</td>
	</tr>
	{/Des::while}
</table>

<br />

<form action="index.php?page=adsense&amp;limited_sections=1&amp;limited=1" method="post">
	<table width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
		<tr align="center">
			<td class="main1" colspan="2">
{$lang['adsense_limit_sections']}
			</td>
		</tr>
<tr>
			<td class="row2">
{$lang['adsense_limit_sections_id']}
			 </td>
			<td class="row2">
				<input name="adsense_limited_sections" id="input_adsense_limited_sections" value="{$_CONF['info_row']['adsense_limited_sections']}" size="40" type="text">
			</td>
		</tr>
		<tr>
			<td class="row2" colspan="2" align="center">
					<input type="submit" value="{$lang['acceptable']}" name="submit" /></td>
		</tr>
</table>
</form>
<br />