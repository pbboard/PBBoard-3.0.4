<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
 <a href="">{$lang['subjects_move']}</a></div>

<br />

<form action="index.php?page=subject&amp;mass_move=1&amp;start=1" method="post">
	<table width="70%" class="t_style_b" border="0" cellspacing="1" align="center">
		<tr align="center">
			<td class="main1" colspan="2">
			{$lang['Section_for']}
			</td>
		</tr>
		<tr align="center">
			<td class="row1" colspan="2">
				<select name="from" id="select_from">
         		{Des::foreach}{forums_list}{forum}
        		{if {$forum['parent']} == 0}
				<option value="{$forum['id']}" disabled="disabled">- {$forum['title']}</option>
				{else}
				<option value="{$forum['id']}" selected="selected">-- {$forum['title']}</option>
				{/if}
				{if {$forum['parent']} != 0}
		       {if {$forum['linksection']} != '1'}
				 {if {$forum['is_sub']}}
					{$forum['sub']}
				 {if {$forum['is_sub_sub']}}
					{$forum['sub_sub']}
				{/if}
				{/if}
					{/if}
	            {/if}
				{/Des::foreach}
				</select>
			</td>
		</tr>
	</table>

	<br />

	<table width="70%" class="t_style_b" border="0" cellspacing="1" align="center">
		<tr align="center">
			<td class="main1" colspan="2">
			{$lang['Section_To']}
			</td>
		</tr>
		<tr align="center">
			<td class="row1" colspan="2">
				<select name="to" id="select_to">
         		{Des::foreach}{forums_list}{forum}
        		{if {$forum['parent']} == 0}
				<option value="{$forum['id']}" disabled="disabled">- {$forum['title']}</option>
				{else}
				<option value="{$forum['id']}" selected="selected">-- {$forum['title']}</option>
				{/if}
				{if {$forum['parent']} != 0}
		       {if {$forum['linksection']} != '1'}
				 {if {$forum['is_sub']}}
					{$forum['sub']}
				 {if {$forum['is_sub_sub']}}
					{$forum['sub_sub']}
				{/if}
				{/if}
					{/if}
	            {/if}
				{/Des::foreach}
				</select>
			</td>
		</tr>
	</table>

	<br />

	<div align="center">
		<input type="submit" value="{$lang['acceptable']}" name="submit" />
	</div>

	<br />
</form>
