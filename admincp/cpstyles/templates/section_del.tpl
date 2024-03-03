<script src="includes/js/jquery.js">
-->
</script>

<script language="javascript">

function OrderChange()
{
	value = $("#choose_id").val();

	if (value == 'move')
	{
		$("#move").fadeIn();
	}
	else
	{
		$("#move").fadeOut();
	}
}

function Ready()
{
	value = $("#choose_id").val();

	if (value == 'move')
	{
		$("#move").show();
	}
	else
	{
		$("#move").hide();
	}

	$("#choose_id").change(OrderChange);
}

$(document).ready(Ready);

-->
</script>

<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
 <a href="index.php?page=sections&amp;control=1&amp;main=1">{$lang['Sections_Mains']}</a> &raquo;
  {$lang['Confirm_the_deletion']} :
  {$Inf['title']}</div>

<br />

<form action="index.php?page=sections&amp;del=1&amp;start=1&amp;id={$Inf['id']}" method="post">
<p align="center">{$lang['You_are_now_a_key_to_delete_the_section']}</p>

		<table width="60%" class="t_style_b" border="0" cellspacing="1" align="center">
			<tr>
				<td class="main1">
				{$lang['What_do_you_want_to_do']}
				</td>
			</tr>
			<tr>
				<td class="row1">
					<select name="choose" id="choose_id">
						<option value="move">{$lang['The_transfer_of_all_forums_of_this_section']}</option>
						<option value="del">{$lang['Delete_all_forums_of_this_section']}</option>
					</select>
				</td>
			</tr>
		</table>

		<br />

		<div id="move">
			<table width="60%" class="t_style_b" border="0" cellspacing="1" align="center">
				<tr>
					<td class="main1">
				{$lang['Transferred_to_the']}
					</td>
				</tr>
					<td class="row2">
{$DoJumpList}
					</td>
				</tr>
			</table>
		</div>

		<br />
{template}forums_am{/template}
		<div align="center">
			<input type="submit" value="{$lang['acceptable']}" name="submit" />
		</div>

		<br />
</form>
