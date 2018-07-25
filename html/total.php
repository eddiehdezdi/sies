<!DOCTYPE html>

<html>
<head>
<title>Inventario Total</title>
</head>

<body>
<?php
require("../php/php_general.php");
require("../php/php_xmlDOM.php");

$listXML = xml_loadListFile($_GET["suc"], $_GET["date"]);
$invXML = xml_loadInvFile($_GET["suc"], $_GET["date"]);
$aK = array_keys($listXML);
?>

<table>
<tr>
	<th></th>
	<th>Art&iacute;culo</th>
	<th>Cantidad</th>
	<!--
	<th>Precio</th>
	-->
</tr>
<tr>
<?php
$total = 0;
for ($i=0;$i<=count($aK)-1;$i++){
	?>
	<tr>
	<td><?php echo $i+1;?></td>
	<td><?php echo $listXML[$aK[$i]]["name"];?></td>
	<td><?php echo $invXML["lista"][$aK[$i]]["disponible"];?></td>
	<!--
	<td>$<?php echo $listXML[$aK[$i]]["precio"];?></td>
	-->
	</tr>
	<?php
	$total += $invXML["lista"][$aK[$i]]["disponible"] * $listXML[$aK[$i]]["precio"];
	}
?>
</tr>
</table>

<br/><br/>
<!--
Total: $<?php echo $total;?>
-->
</body>
</html>