
	<tr align="center">
		<td class="row1">
		{if {$groups_id} == '7'}
		{$groups_title}
		{else}
			<a href="index.php?page=groups&amp;shwo=1&amp;main=1&amp;id={$groups_id}">
			{$groups_title}</a>
		{/if}
		</td>
		<td class="row1">
		{if {$groups_id} == '7'}
		{$group_member_nm}
		{else}
			<a href="index.php?page=groups&amp;shwo=1&amp;main=1&amp;id={$groups_id}">
			{$group_member_nm}</a>
		{/if}
		</td>
		<td class="row1">
			<a href="index.php?page=groups&amp;edit=1&amp;main=1&amp;id={$groups_id}">
			{$lang['edit']}</a>
		</td>
		<td class="row1">
			<a href="index.php?page=groups&amp;del=1&amp;main=1&amp;id={$groups_id}">
			{$lang['Delet']}</a>
		</td>
		<td class="row1">
			<a href="index.php?page=groups&amp;move=1&amp;main=1&amp;id={$groups_id}">
			{$lang['move']}</a>
		</td>
				<td class="row1">
			({$groups_id})
		</td>
	<td class="row1"><input type="text" name="order-{$groups_id}" id="input_order-{$groups_id}" value="{$group_order}" style="max-width: 45%;" size="2" oninput="this.value = this.value.replace(/[^0-9]/g, '');" /></td>
	</tr>


