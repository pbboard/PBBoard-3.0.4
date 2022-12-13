<br />

<div class="address_bar">
{$lang['Control_Panel']}
&raquo;
{$lang['addons_pbb']}
&raquo;
<a href="index.php?page=auto_addons&amp;add=1&amp;main=1">PBBoard Auto Add-ons</a></div>


 <style>
 .buttons_link {
    color: #006793;
    width: 100px;
    font-family: Tahoma;
    font-size: 11px;
    background: #FFFFFF;
    padding: 6px 0px 6px 0px;
    border: 1px;
    border: 1px solid #006793;
    border-top-left-radius: 3px;
    border-top-right-radius: 3px;
    border-bottom-right-radius: 3px;
    border-bottom-left-radius: 3px;
    text-align: center;
    margin-left: 3px;
    margin-right: 8px;
}
 #sut_link {
    color: #FFFFFF;
    width: 100px;
    font-family: Tahoma;
    font-size: 11px;
    background: #489AD4;
    padding: 6px 33px 6px 33px;
    border: 1px;
    border: 1px solid #438BCB;
    border-top-left-radius: 3px;
    border-top-right-radius: 3px;
    border-bottom-right-radius: 3px;
    border-bottom-left-radius: 3px;
    text-align: center;
    margin-left: 3px;
    margin-right: 8px;
}
#deladdon_link {
    color: #FFFFFF;
    width: 100px;
    font-family: Tahoma;
    font-size: 11px;
    background: #333300;
    padding: 6px 25px 6px 25px;
    border: 1px;
    border: 1px solid #000;
    border-top-left-radius: 3px;
    border-top-right-radius: 3px;
    border-bottom-right-radius: 3px;
    border-bottom-left-radius: 3px;
    text-align: center;
    margin-left: 3px;
    margin-right: 8px;
}

	.row9 {
	border-bottom: 1px dotted #C9C9C9;
	clear: both;
    padding: 6px;
    line-height:18px;
	font-family: "Droid Arabic Kufi","tahoma",sans-serif;
	font-size:11px;
	font-weight:bold;
	}
.isaddon {
    opacity: 0.5;
    filter: alpha(opacity=50);
	border-bottom: 1px dotted #C9C9C9;
	clear: both;
    padding: 6px;
    line-height:18px;
	font-family: "Droid Arabic Kufi","tahoma",sans-serif;
	font-size:11px;
	font-weight:bold;}

.isaddon:hover {
    opacity: 1.0;
    filter: alpha(opacity=100); /* For IE8 and earlier */
	border-bottom: 1px dotted #C9C9C9;
	clear: both;
    padding: 6px;
    line-height:18px;
	font-family: "Droid Arabic Kufi","tahoma",sans-serif;
	font-size:11px;
	font-weight:bold;}

.tablerow1 img {
    border-radius: 12px;
    border: solid 1px #B4B4B4
}
.tablerow1{
    border-radius: 6px;
    border: solid 1px #B4B4B4
}
/* The Modal (background) */
.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    padding-top: 100px; /* Location of the box */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
    background-color: #fefefe;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
}

/* The Close Button */
.close {
    color: #aaaaaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}
 </style>
<br />
<!-- Trigger/Open The Modal -->



<script type="text/javascript">
function open_info(obj)
{
var el=document.getElementById(obj);
el.style.display = "block";
}
function close_info(obj)
{
var el=document.getElementById(obj);
el.style.display = "none";
}

</script>
<?php
 $AddonSDir = $PowerBB->_CONF['template']['AddonSDir'];
$t=0;
?>

<table border="0" cellspacing="1" width="35%" align="center">
<tr valign="top" align="center">
	<td class="main1" colspan="2">PBBoard Auto Add-ons</td>
</tr>
	<tr valign="top">
 {Des::foreach}{addonsList}{foldrs}
 <?php
$infoUrl = ($AddonSDir.'/'.$foldrs['filename']);
$infoTxt = @file_get_contents($infoUrl."/info.txt");
$arr = explode('|',$infoTxt);
		if ($PowerBB->addons->IsAddons(array('where' => array('name',$arr['0']))))
		{
		 $IsAddons = 'isaddon';

		}
		else
		{
		 $IsAddons = 'row9';

		}
if($t== '2'){
$t=0;
echo "</tr><tr valign='top'>";
}
?>
<td valign='top' align="center" valign="middle" class="<?php echo $IsAddons;?>">
<!-- The Modal -->
<div class="modal" id="<?php echo $arr['5']; ?>">
  <!-- Modal content -->
  <div class="modal-content">
    <span class="close" onclick="return close_info('<?php echo $arr["5"]; ?>')">&times;</span>
    <p><?php echo $arr['2']; ?></p>
  </div>

</div>
<table border="0" cellspacing="4" width="35%" class="tablerow1" align="center">
	<tr>
		<td width="35%" valign='top' class="row1" colspan="2"><img width="308" height="205" src="<?php echo $infoUrl."/".$arr['4'];?>"></td>
	</tr>
	<tr>
		<td align="center" class="row1" width="35%" colspan="2">
<textarea dir="rtl" readonly="readonly" cols="27" rows="3" style="font-size: 11px;width:100%;"><?php echo $arr['1'];?></textarea>
		</td>
	</tr>
	<tr>
		<td class="row1" width="150" align="center"><button class="buttons_link" onclick="return open_info('<?php echo $arr["5"]; ?>')">Details</button></td>
<?php
		if ($PowerBB->addons->IsAddons(array('where' => array('name',$arr['0']))))
		{
           ?>
		<td class="row1" width="150" align="center"><a id="deladdon_link" href="index.php?page=auto_addons&amp;deladdon=1&amp;filename=<?php echo $arr['5']; ?>">Uninstall</a></td>
         <?php
		}
		else
		{
           ?>
		<td class="row1" width="150" align="center"><a id="sut_link" href="index.php?page=auto_addons&amp;installation=1&amp;filename=<?php echo $arr['5']; ?>">Install</a></td>
         <?php
		}
?>
	</tr>
</table>
 		</td>
		<?php $t=$t+1;?>
	{/Des::foreach}
 	</tr>
</table>
 <br />
