<br />

<div class="address_bar">{$lang['Control_Panel']}
 &raquo; <a href="index.php?page=portal&amp;control=1&amp;main=1">{$lang['settings_portal']}</a>
{$lang['control_blocks']}</div>

<br />

<form action="index.php?page=portal&amp;control_blocks=1&amp;start=1" method="post">
<br />
<br />

<table width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
	<tr align="center">
		<td class="main1">
{$lang['block_title']}
		</td>
		<td class="main1">
{$lang['block_active']}
			</td>
		<td class="main1">
{$lang['block_edit']}
		</td>
		<td class="main1">
{$lang['block_delet']}
		</td>
		<td class="main1">
{$lang['block_place']}
		</td>
		<td class="main1">
{$lang['block_order']}
		</td>
	</tr>
<?php
      $blocks_info = $PowerBB->DB->sql_query("SELECT  *   FROM " . $PowerBB->prefix."blocks" . " ORDER BY id ASC");
       while ($get_blocks_row = $PowerBB->DB->sql_fetch_array($blocks_info))
      {
       ?>
	<tr align="center">
		<td class="row1">
		<input name="title-<?php echo $get_blocks_row['id'] ?>" id="input_title" value="<?php echo $get_blocks_row['title'] ?>" size="30" type="text">
		</td>
		<td class="row1">
      <select name="active-<?php echo $get_blocks_row['id'] ?>" id="select_active">
<?php
		if ($get_blocks_row['active'] == '1')
		{
			 ?>
			<option value="0">{$lang['Disabled']}</option>
			<option value="1" selected="selected">{$lang['activating']}</option>
		<?php
		}
		elseif ($get_blocks_row['active'] == '0')
		{
			 ?>
			<option value="0" selected="selected">{$lang['Disabled']}</option>
			<option value="1">{$lang['activating']}</option>
		<?php			}
 ?>
 				</select>
		</td>
		<td class="row1">
			<a href="index.php?page=portal&amp;edit_block=1&amp;main=1&amp;id=<?php echo $get_blocks_row['id'] ?>">{$lang['edit']}</a>
		</td>
		<td class="row1">
			<a href="index.php?page=portal&amp;del_block=1&amp;start=1&amp;id=<?php echo $get_blocks_row['id'] ?>">{$lang['Delet']}</a>
		</td>
		<td class="row1">

				<select name="place_block-<?php echo $get_blocks_row['id'] ?>" id="select_place_block">
<?php
		if ($get_blocks_row['place_block'] == 'left')
		{
?>
			<option value="left" selected="selected">{$lang['left']}</option>
			<option value="center">{$lang['center']}</option>
			<option value="right">{$lang['right']}</option>
<?php
        }
		if ($get_blocks_row['place_block'] == 'center')
		{
		?>
			<option value="left" selected="selected">{$lang['left']}</option>
			<option value="center" selected="selected">{$lang['center']}</option>
			<option value="right">{$lang['right']}</option>
<?php
		}
		if ($get_blocks_row['place_block'] == 'right')
		{
		?>
			<option value="left" selected="selected">{$lang['left']}</option>
			<option value="center">{$lang['center']}</option>
			<option value="right" selected="selected">{$lang['right']}</option>
<?php
		}
 ?>
 				</select>

		</td>
		<td class="row1">
				<input type="text" name="sort-<?php echo $get_blocks_row['id'] ?>" id="sort_id" value="<?php echo $get_blocks_row['sort'] ?>" size="1" />
		</td>
	</tr>
		<?php
		}
 ?>
		<tr>
			<td class="row2" colspan="6" align="center">
			<input type="submit" value="{$lang['acceptable']}" name="submit" /></td>
		</tr>
</table>

</form>