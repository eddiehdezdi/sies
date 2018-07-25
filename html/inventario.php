<!DOCTYPE html>

<?php
require("../php/php_general.php");
require("../php/php_xmlDOM.php");

$tiendasXML = xml_loadTiendasFile();
$listXML = xml_loadListFile($_GET["suc"], $_GET["date"]);
$invXML = xml_loadInvFile($_GET["suc"], $_GET["date"]);
$aK = array_keys($listXML);
?>

<html>
<head>
<title>Inventario - <?php echo $tiendasXML[$_GET["suc"]];?></title>
<link rel="stylesheet" type="text/css" href="../css/css_general.css"/>
</head>

<body>
Sistema de Inventarios de Eclipse Shop v0.4
<br/>
Inventario de Tienda > 
<span class="blurbluebg"><?php echo $tiendasXML[$_GET["suc"]];?> </span>
> 
<span class="blurbluebg"><?php echo gen_fullDate($_GET["date"]);?></span>
<br/><br/>

<table>
<tr class="bluebg">
	<th align="left">Art&iacute;culo</th>
	<th>Cantidad</th>
	<th>Precio</th>
	<th>Total de Art&iacute;culo</th>
</tr>
<tr>
<?php
$total = 0;
$cc = "";
for ($i=0;$i<=count($aK)-1;$i++){
	$totalArt = $invXML["lista"][$aK[$i]]["disponible"] * $listXML[$aK[$i]]["precio"];
	if ($i % 2 == 0) $cc = "whitebg";
	else $cc = "blurbluebg";
	?>
	<tr class="<?php echo $cc;?>">
	<td width="400"><?php echo utf8_decode($listXML[$aK[$i]]["name"]);?></td>
	<td width="100" align="center"><?php echo $invXML["lista"][$aK[$i]]["disponible"];?></td>
	<td width="100" align="center">$<?php echo $listXML[$aK[$i]]["precio"];?></td>
	<td width="200" align="center">$<?php echo $totalArt;?></td>
	</tr>
	<?php
	$total += $totalArt;
	}
if ($cc == "whitebg") $cc = "blurbluebg";
else $cc = "whitebg";
?>
</tr>
<tr class="<?php echo $cc;?>">
<td></td><td></td>
<td align="center">Total: </td>
<td align="center"><b>$<?php echo $total;?></b></td>
</tr>
</table>

</body>
</html>