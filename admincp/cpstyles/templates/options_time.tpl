<br />

<div class="address_bar">{$lang['Control_Panel']} &raquo;
<a href="index.php?page=options&amp;index=1">{$lang['mange_forum']}</a> &raquo;
<a href="index.php?page=options&amp;time=1&amp;main=1">{$lang['Time_Settings']}</a></div>

<br />

<form action="index.php?page=options&amp;time=1&amp;update=1" name="myform" method="post">
<table cellpadding="3" cellspacing="1" width="99%" class="t_style_b" border="0" align="center">
<tr valign="top" align="center">
	<td class="main1" colspan="2">{$lang['Time_Settings']}</td>
</tr>
<tr valign="top">
		<td class="row1">{$lang['Forum_Time']}</td>
		<td class="row1">
<select name="time_stamp" id="select_time_stamp">
	<option {if {$_CONF['info_row']['timestamp']} == '-43200'} selected="selected" {/if} value="-43200" >GMT - 12</option>
	<option {if {$_CONF['info_row']['timestamp']} == '-39600'} selected="selected" {/if} value="-39600" >GMT - 11</option>
	<option {if {$_CONF['info_row']['timestamp']} == '-36000'} selected="selected" {/if} value="-36000" >GMT - 10</option>
	<option {if {$_CONF['info_row']['timestamp']} == '-32400'} selected="selected" {/if} value="-32400" >GMT - 9</option>
	<option {if {$_CONF['info_row']['timestamp']} == '-28800'} selected="selected" {/if} value="-28800" >GMT - 8</option>
	<option {if {$_CONF['info_row']['timestamp']} == '-25200'} selected="selected" {/if} value="-25200" >GMT - 7</option>
	<option {if {$_CONF['info_row']['timestamp']} == '-21600'} selected="selected" {/if} value="-21600" >GMT - 6</option>
	<option {if {$_CONF['info_row']['timestamp']} == '-18000'} selected="selected" {/if} value="-18000" >GMT - 5</option>
	<option {if {$_CONF['info_row']['timestamp']} == '-14400'} selected="selected" {/if} value="-14400" >GMT - 4</option>
	<option {if {$_CONF['info_row']['timestamp']} == '-10800'} selected="selected" {/if} value="-10800" >GMT - 3</option>
	<option {if {$_CONF['info_row']['timestamp']} == '-7200'} selected="selected" {/if} value="-7200" >GMT - 2</option>
	<option {if {$_CONF['info_row']['timestamp']} == '-3600'} selected="selected" {/if} value="-3600" >GMT - 1</option>
	<option {if {$_CONF['info_row']['timestamp']} == '0000'} selected="selected" {/if} value="0000">GMT 0</option>
	<option {if {$_CONF['info_row']['timestamp']} == '+3600'} selected="selected" {/if} value="+3600" >GMT + 1</option>
	<option {if {$_CONF['info_row']['timestamp']} == '+7200'} selected="selected" {/if} value="+7200" >GMT + 2</option>
	<option {if {$_CONF['info_row']['timestamp']} == '+10800'} selected="selected" {/if} value="+10800" >GMT + 3</option>
	<option {if {$_CONF['info_row']['timestamp']} == '+14400'} selected="selected" {/if} value="+14400" >GMT + 4</option>
	<option {if {$_CONF['info_row']['timestamp']} == '+18000'} selected="selected" {/if} value="+18000" >GMT + 5</option>
	<option {if {$_CONF['info_row']['timestamp']} == '+21600'} selected="selected" {/if} value="+21600" >GMT + 6</option>
	<option {if {$_CONF['info_row']['timestamp']} == '+25200'} selected="selected" {/if} value="+25200" >GMT + 7</option>
	<option {if {$_CONF['info_row']['timestamp']} == '+28800'} selected="selected" {/if} value="+28800" >GMT + 8</option>
	<option {if {$_CONF['info_row']['timestamp']} == '+32400'} selected="selected" {/if} value="+32400" >GMT + 9</option>
	<option {if {$_CONF['info_row']['timestamp']} == '+36000'} selected="selected" {/if} value="+36000" >GMT + 10</option>
	<option {if {$_CONF['info_row']['timestamp']} == '+39600'} selected="selected" {/if} value="+39600" >GMT + 11</option>
	<option {if {$_CONF['info_row']['timestamp']} == '+43200'} selected="selected" {/if} value="+43200" >GMT + 12</option>
</select>
</td>
</tr>
<tr valign="top">
		<td class="row1">{$lang['Forum_timeoffset']}</td>
		<td class="row2">
<input type="text" name="time_offset" id="select_time_offset" value="{$_CONF['info_row']['timeoffset']}" size="30" />
</td>
</tr>
<tr valign="top">
		<td class="row2">{$lang['Forum_timeformat']}
				<br />
{$lang['Example']}: <b>h:i A</b>
		</td>
		<td class="row2">
<input type="text" name="time_system" id="select_time_system" value="{$_CONF['info_row']['timesystem']}" size="10" />
</td>
</tr>
<tr valign="top">
		<td class="row2">{$lang['Forum_dateformat']}
<br />
{$lang['Example']}: <b>d-m-Y</b>
		</td>
		<td class="row2">
<input type="text" name="date_system" id="select_date_system" value="{$_CONF['info_row']['datesystem']}" size="10" /></td>
</tr>

<tr valign="top">
		<td class="row2" colspan="2" align="center">
		<input class="submit" type="submit" value="{$lang['acceptable']}" name="submit" accesskey="s" />
		</td>
</tr>
</table><br />
<br />
</form>