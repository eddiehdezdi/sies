<!DOCTYPE html>

<?php
require ("../php/php_xmlDOM.php");
?>

<html>

<head>
<title>Detalle mensual</title>
</head>

<body>
<h2>Ventas - <?php echo $_GET["suc"]?> - <?php echo $_GET["ym"]?></h2>
<br><br>
<table align="center" border="1">
<tr>
	<th style="width:100px;">Cantidad</th>
	<th style="width:400px;">Art&iacute;culo</th>
	<th style="width:100px;">Precio</th>
	<th style="width:100px;">Unitario</th>
	<th style="width:100px;">Monto</th>
	<th style="width:100px;">Neto</th>
	<th style="width:100px;">Monto Orig</th>
</tr>
<?php
$dA = explode("-", $_GET["ym"]);
$articulos = array();
$dStamp = mktime(12,0,0,$dA[1],1,$dA[0])-86400;
$listXML = xml_loadListFile($_GET["suc"]);
while (true){
	$date = date("Y-m-d", $dStamp);
	$libXML = xml_loadLibFile($_GET["suc"], $date);
	foreach ($libXML["ventas"] as $venta){
		if ($venta["type"] == "articulo" || $venta["type"] == "apartado"){
			if (array_key_exists($venta["id"], $articulos) == false){
				$articulos[$venta["id"]] = array();
				$articulos[$venta["id"]]["cantidad"] = 0;
				$articulos[$venta["id"]]["monto"] = 0;
				$articulos[$venta["id"]]["neto"] = 0;
				$articulos[$venta["id"]]["monto_orig"] = 0;
				}
			$articulos[$venta["id"]]["cantidad"]++;
			if (array_key_exists($venta["id"], $listXML) == true){
				$articulos[$venta["id"]]["precio"] = $listXML[$venta["id"]]["precio"];
				}
			else {
				$articulos[$venta["id"]]["precio"] = $venta["precio"];
				}
			$articulos[$venta["id"]]["unitario"] = round($articulos[$venta["id"]]["precio"] / 1.16, 2);
			$desc_art = $articulos[$venta["id"]]["precio"] - $venta["precio"];
			$articulos[$venta["id"]]["monto"] += round($venta["precio"] / 1.16, 2);
			$articulos[$venta["id"]]["neto"] += $venta["precio"];
			$articulos[$venta["id"]]["monto_orig"] += $articulos[$venta["id"]]["unitario"];
			}
		}
	//control de while
	$dStamp += 86400;
	if (intval(date("j", $dStamp)) == intval(date("t", $dStamp))) break;
	}
$total = array("original"=>0, "real"=>0);
foreach (array_keys($articulos) as $artId){
	if ($artId != "corte" && $artId != "inventario"){
		?>
		<tr>
			<td><?php echo $articulos[$artId]["cantidad"];?></td>
			<td><?php
			if (array_key_exists($artId, $listXML) == true) echo $listXML[$artId]["name"];
			else echo $artId;
			?></td>
			<td><?php echo $articulos[$artId]["precio"];?></td>
			<td><?php echo $articulos[$artId]["unitario"];?></td>
			<td><?php echo $articulos[$artId]["monto"];?></td>
			<td><?php echo $articulos[$artId]["neto"];?></td>
			<td><?php echo $articulos[$artId]["monto_orig"];?></td>
		</tr>
		<?php
		$total["original"] += $articulos[$artId]["monto_orig"];
		$total["real"] += $articulos[$artId]["monto"];
		}
	}
?>
<tr>
<td></td><td></td><td></td><td></td>
<td><?php echo $total["real"];?></td>
<td><?php echo $total["original"] - $total["real"];?></td>
<td><?php echo $total["original"];?></td>
</tr>
</table>
</body>


</html>