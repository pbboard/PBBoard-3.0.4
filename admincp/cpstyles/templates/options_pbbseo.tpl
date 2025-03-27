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
{get_hook}options_pbbseo{/get_hook}
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
</form>
	<br />
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
			<td class="row1">
جعل خرائط XML للمنتديات مضغوطة <b>gzip</b>
<br />
 <small>يمكن ضغط خرائط المنتديات باستخدام gzip (سيصبح اسم الملف مشابهًا لـ sitemap_forum_13.xml.gz) لتوفير موارد خادمك.</small>
			</td>
			<td class="row1">
				<select name="sitemap_gzip" id="sitemap_gzip">
					{if {$_CONF['info_row']['sitemap_gzip']}}
					<option value="1" selected="selected">{$lang['yes']}</option>
					<option value="0">{$lang['no']}</option>
					{else}
					<option value="1">{$lang['yes']}</option>
					<option value="0" selected="selected">{$lang['no']}</option>
					{/if}
				</select>
			</td>
		</tr>
<tr valign="top">

		<td class="row2">الحد الأقصى لعدد عناوين URL في خريطة XML
		<br />
		<small>لا يجب أن تحتوي على أكثر من 50.000 عنوان URL كحد أقصى.
يقتصر حجم ملفهم على 50 ميجا بايت عند عدم ضغطه.</small>
		</td>
		<td class="row2">
<input type="text" name="sitemap_url_max" id="input_sitemap_url_max" value="{$_CONF['info_row']['sitemap_url_max']}" dir="ltr" size="10" min="3" max="7" v-model="form.availability" oninput="this.value = this.value.replace(/[^\d.-]+/g, '');">
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
<form action="index.php?page=options&amp;pbb_seo=1&amp;update_rewrite=1" method="post">
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
</form>
<br />

