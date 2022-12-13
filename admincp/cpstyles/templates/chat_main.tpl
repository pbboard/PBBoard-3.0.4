<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=chat&amp;control=1&amp;main=1">{$lang['mange_chat']} </a></div>

<br />

<form action="index.php?page=chat&amp;del_all=1&amp;del=1" method="post">

<table width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
<tr valign="top">
	<td class="main1">{$lang['delet_all_chatMessages']}</td>
</tr>
	<tr align="center">
		<td class="row1">
			<input type="submit" class="button" value="{$lang['delet_all_chatMessages']}" name="submit1" />
		</td>
	</tr>
</table>
</form>
<br />

<table width="90%" class="t_style_b" border="0" cellspacing="1" align="center">
<tr valign="top">
	<td class="main1" colspan="4">{$lang['CahtMessagesNumber']} :
	({$CahtMessagesNumber})</td>
</tr>
	<tr align="center">
		<td class="main2">
{$lang['chatMessage']}
		</td>
		<td class="main2">
		{$lang['edit']}
		</td>
		<td class="main2">
		{$lang['Delet']}
		</td>
		<td class="main2">
{$lang['writer']}
		</td>
	</tr>
	{Des::while}{MessagesList}
<?php $PowerBB->_CONF['template']['while']['MessagesList'][$this->x_loop]['country'] = $PowerBB->Powerparse->feltr_words($PowerBB->_CONF['template']['while']['MessagesList'][$this->x_loop]['country']); ?>
<?php $PowerBB->_CONF['template']['while']['MessagesList'][$this->x_loop]['message'] = $PowerBB->Powerparse->bb_common($PowerBB->_CONF['template']['while']['MessagesList'][$this->x_loop]['message']); ?>
<?php $PowerBB->_CONF['template']['while']['MessagesList'][$this->x_loop]['message'] = $PowerBB->Powerparse->replace($PowerBB->_CONF['template']['while']['MessagesList'][$this->x_loop]['message']); ?>
<?php $PowerBB->Powerparse->replace_smiles($PowerBB->_CONF['template']['while']['MessagesList'][$this->x_loop]['message']); ?>

	<tr align="center">
		<td class="row1">
			{$MessagesList['message']}
		</td>
		<td class="row1">
			<a href="index.php?page=chat&amp;edit=1&amp;main=1&amp;id={$MessagesList['id']}">{$lang['edit']}</a>
		</td>
		<td class="row1">
			<a href="index.php?page=chat&amp;del=1&amp;start=1&amp;id={$MessagesList['id']}">{$lang['Delet']}</a>
		</td>
			<td class="row1">
			<a target="_blank" href="../index.php?page=profile&amp;show=1&amp;id={$MessagesList['user_id']}">{$MessagesList['username']}</a>
		</td>
	</tr>
	{/Des::while}
</table>
<span class="pager-left">{$pager} </span>
