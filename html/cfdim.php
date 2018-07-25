<!DOCTYPE html>

<html>
<head>
<title>CFDI Mensual</title>
</head>

<body>
<?php
$dA = explode("-", $_GET["ym"]);
$st = mktime(12,0,0,$dA[1],1,$dA[0]);
$ventas = array();
while (true){
	$libPath = "../xml/".$_GET["suc"]."/libreta/".date("Y-m-d", $st).".xml";
	if (file_exists($libPath) == true){
		$hoja = simplexml_load_file($libPath);
		foreach ($hoja->ventas->renglon as $renglon){
			if (strval($renglon->type) == "articulo"){
				$id = strval($renglon->id);
				$precio = strval($renglon->precio);
				if (isset($ventas[$id]) == false) $ventas[$id] = array();
				if (isset($ventas[$id][$precio]) == false) $ventas[$id][$precio] = 1;
				else $ventas[$id][$precio] += 1;
				}
			}
		}
	ksort($ventas);
	$st += 86400;
	if (date("Y-m", $st) != $_GET["ym"]) break;
	}
?>
<table>
<?php
foreach (array_keys($ventas) as $id){
	?>
	<tr><td><?php echo $id;?></td></tr>
	<?php
	foreach (array_keys($ventas[$id]) as $price){
		?>
		<tr><td></td><td>$<?php echo $price;?> (<?php echo round($price / 1.16, 2);?>)</td><td><?php echo $ventas[$id][$price];?></td></tr>
		<?php
		}
	}
?>
</table>
</body>

</html>