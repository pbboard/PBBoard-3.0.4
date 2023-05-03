<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
{$lang['Maintenance']} &raquo;
<a href="index.php?page=fixup&amp;update_meter=1&amp;main=1"> {$lang['fixup']}</a></div>

<br />

<form action="index.php?page=fixup&amp;update_meter=1&amp;start=1" method="post">
	<table width="80%" class="t_style_b" border="0" cellspacing="1" align="center">
		<tr align="center">
			<td class="main1" colspan="2">
{$lang['Update_counters_Forums']}
			</td>
		</tr>
		<tr align="center">
			<td class="row1">
{$lang['Updated_sections_individually']}
			</td>
			<td class="row1">
					{$DoJumpList}
			<div style="visibility:hidden;position:absolute;">
							{Des::while}{groups}
		<input type="text" name="groups" value="{$groups['id']}" size="30" /> {$groups['title']}
		<br />
		{/Des::while}
		</div>
			</td>
		</tr>
				<tr align="center">
			<td class="row1" colspan="2">
<input type="submit" value="{$lang['Start_Updated']}" name="submit" />
			</td>
		</tr>
	</table>
</form>
<br />
<form action="index.php?page=fixup&amp;update_meter=1&amp;all_cache=1" method="post">
	<table width="80%" class="t_style_b" border="0" cellspacing="1" align="center">
		<tr align="center">
			<td class="main1" colspan="1">
{$lang['Update_all_Forums_at_once']}
			</td>
		</tr>
				<tr align="center">
			<td class="row1" colspan="2">
<input type="submit" value="{$lang['Start_Updated']}" name="submit" />
			</td>
		</tr>
	</table>
</form>
<br />


<form action="index.php?page=fixup&amp;update_static=1" method="post">
	<table width="80%" class="t_style_b" border="0" cellspacing="1" align="center">
		<tr align="center">
			<td class="main1" colspan="1">
{$lang['update_static']}
			</td>
		</tr>
				<tr align="center">
			<td class="row1">
<input type="submit" value="{$lang['Start_Updated']}" name="submit" />
			</td>
		</tr>
	</table>
</form>
<br />

<form action="index.php?page=fixup&amp;update_posts=1" method="post">
	<table width="80%" class="t_style_b" border="0" cellspacing="1" align="center">
		<tr align="center">
			<td class="main1" colspan="1">
{$lang['update_posts']}
			</td>
		</tr>
				<tr align="center">
			<td class="row1">
<input type="submit" value="{$lang['Start_Updated']}" name="submit" />
			</td>
		</tr>
	</table>
</form>
<br />


<form action="index.php?page=fixup&amp;update_username_members=1" method="post">
	<table width="80%" class="t_style_b" border="0" cellspacing="1" align="center">
		<tr align="center">
			<td class="main1" colspan="1">
{$lang['update_username_members']}
			</td>
		</tr>
				<tr align="center">
			<td class="row1">
<input type="submit" value="{$lang['Start_Updated']}" name="submit" />
			</td>
		</tr>
	</table>
</form>

<br />

<form action="index.php?page=fixup&amp;update_users_ratings=1" method="post">
	<table width="80%" class="t_style_b" border="0" cellspacing="1" align="center">
		<tr align="center">
			<td class="main1" colspan="1">
{$lang['update_users_ratings']}
			</td>
		</tr>
				<tr align="center">
			<td class="row1">
<input type="submit" value="{$lang['Start_Updated']}" name="submit" />
			</td>
		</tr>
	</table>
</form>

<br />

<form action="index.php?page=fixup&amp;update_users_titles=1" method="post">
	<table width="80%" class="t_style_b" border="0" cellspacing="1" align="center">
		<tr align="center">
			<td class="main1" colspan="1">
{$lang['update_users_titles']}
			</td>
		</tr>
				<tr align="center">
			<td class="row1">
<input type="submit" value="{$lang['Start_Updated']}" name="submit" />
			</td>
		</tr>
	</table>
</form>

<br />


<form action="index.php?page=fixup&amp;repair_mem_posts=1" method="post">
	<table width="80%" class="t_style_b" border="0" cellspacing="1" align="center">
		<tr align="center">
			<td class="main1" colspan="1">
{$lang['RepairMemberPosts']}
			</td>
		</tr>
				<tr>
			<td class="row1" colspan="2">
{$lang['RepairMemberPosts_not']}
<br />
			</td>
		</tr>
				<tr align="center">
			<td class="row1">
<input type="text" name="perpage" size="35" value="200">
			</td>
		</tr>
				<tr align="center">
			<td class="row1">
<input type="submit" value="{$lang['Start_Updated']}" name="submit" />
			</td>
		</tr>
	</table>
</form>



<br />

<form action="index.php?page=fixup&amp;update_meter=1&amp;groups=1" method="post">
	<table width="80%" class="t_style_b" border="0" cellspacing="1" align="center">
		<tr align="center">
			<td class="main1" colspan="2">
{$lang['Update_all_groups']}
			</td>

				<tr align="center">
			<td class="row1" colspan="2">
{$lang['annotation_Update_all_groups']}
<br />
<input type="submit" value="{$lang['Start_Updated']}" name="submit" />
			</td>
		</tr>
	</table>
</form>