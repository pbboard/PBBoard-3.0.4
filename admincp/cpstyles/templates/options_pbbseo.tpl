<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=options&amp;index=1">{$lang['mange_forum']}</a> &raquo;
 <a href="index.php?page=options&amp;pbb_seo=1&amp;main=1">{$lang['Settings_Seo']}</a></div>

<br />

<form action="index.php?page=options&amp;pbb_seo=1&amp;update=1" method="post">
<table cellpadding="3" cellspacing="1" width="90%" class="t_style_b" border="0" align="center">
		<tr align="center">
			<td class="main1" colspan="2">
{$lang['Settings_Seo']}
			</td>
		</tr>
		<tr>
			<td class="row1">
{$lang['rewriterule']}
			</td>
			<td class="row1">
				<select name="rewriterule" id="select_rewriterule">
					{if {$_CONF['info_row']['rewriterule']}}
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
			<td class="row1" colspan="2" align="center">
<input type="submit" value="{$lang['acceptable']}" name="submit" /></td>
		</tr>
	</table>
	<br />
<table cellpadding="3" cellspacing="1" width="90%" class="t_style_b" border="0" align="center">
		<tr align="center">
			<td class="main1">
Rewrite Engine
			</td>
				<tr>
			<td class="row1">
{$lang['Rewrite_Engine']}
<br />
{$lang['RewriteEnginetextarea']}
<br />
<textarea name="context" id="textarea_context" rows="32" cols="50" dir="ltr">
{$context}

</textarea>
			</td>
		</tr>
		<tr>
			<td class="row1"align="center">
<input type="submit" value="{$lang['acceptable']}" name="submit" /></td>
		</tr>
	</table>
	<br />
	</form>
<form action="index.php?page=options&amp;pbb_seo=1&amp;update_sitemap=1" method="post">
<table cellpadding="3" cellspacing="1" width="90%" class="t_style_b" border="0" align="center">
		<tr align="center">
			<td class="main1" colspan="2">
{$lang['SiteMap']}
			</td>
				<tr>
			<td class="row1">
{$lang['activate_sitemap']}
			</td>
			<td class="row1">
				<select name="sitemap" id="select_sitemap">
					{if {$_CONF['info_row']['sitemap']}}
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
			<td class="row1" colspan="2" align="center">
<input type="submit" value="{$lang['acceptable']}" name="submit" /></td>
		</tr>
	</table>
	<br />

	</form>
<br />