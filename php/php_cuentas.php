<?php

require ("php_general.php");
require ("php_xmlDOM.php");

function cuentas_sp3m($m){
	$sp3m = "";
	switch($m){
		case 1: $sp3m = "Ene"; break;
		case 2: $sp3m = "Feb"; break;
		case 3: $sp3m = "Mar"; break;
		case 4: $sp3m = "Abr"; break;
		case 5: $sp3m = "May"; break;
		case 6: $sp3m = "Jun"; break;
		case 7: $sp3m = "Jul"; break;
		case 8: $sp3m = "Ago"; break;
		case 9: $sp3m = "Sep"; break;
		case 10: $sp3m = "Oct"; break;
		case 11: $sp3m = "Nov"; break;
		case 12: $sp3m = "Dec"; break;
		}
	return $sp3m;
	}

function dayCash($suc, $dy, $mth, $yr, $nd){
	$libDate = date("Y-m-d", mktime(12, 0, 0, $mth, $dy, $yr));
	$dC = 0;
	if (file_exists("../xml/" . $suc . "/libreta/" . $libDate . ".xml") == true){
		$libXML = xml_loadLibFile($suc, $libDate);
		for ($i=0;$i<=count($libXML["ventas"])-1;$i++){
			if ($libXML["ventas"][$i]["type"] == "corte") $dC += $libXML["ventas"][$i]["precio"];
			}
		//sumar sobrantes o faltantes
		$dC += $libXML["cortes"];
		return $dC;
		}
	}

function drawMonth($suc, $mth, $yr){
	$tiendXML = xml_loadTiendasFile();
	$sucName = utf8_encode($tiendXML[$suc]);
	?>
	<div class="d-mes">
	<?php echo $sucName;?>
	 - 
	<?php echo sp_month($mth) . " de " . $yr;?>
	<br/><br/>
	<table class="t-mes">
	<tr> <!-- encabezado lunes - domingo -->
	<?php
	for ($i=0;$i<=6;$i++){
		?>
		<td><?php echo sp_weekDay($i);?></td>
		<?php
		}
	?>
	</tr>
	<tr> <!-- primera línea -->
	<?php
	$fstamp = mktime(12,0,0,$mth,1,$yr);
	$f = date("w", $fstamp);
	$d = 1;
	$tD = 0; $tm = 0;
	$nd = date("t", $fstamp);
	for ($i=0;$i<=6;$i++){
		if ($i < $f) echo "<td></td>";
		else {
			$tD = dayCash($suc, $d, $mth, $yr, $nd);
			?>
			<td class="td-b1">
			<span class="sp-gris"><?php echo $d;?></span><br/>
			$<?php echo number_format($tD);?>
			</td>
			<?php
			$d++;
			$tm += $tD;
			}
		}
	?>
	</tr>
	<!-- resto de las líneas -->
	<?php
	while (true){
		echo "<tr>";
		for ($i=0;$i<=6;$i++){
			$tD = dayCash($suc, $d, $mth, $yr, $nd);
			?>
			<td class="td-b1">
			<span class="sp-gris"><?php echo $d;?></span><br/>
			$<?php echo number_format($tD);?>
			</td>
			<?php
			$d++;
			$tm += $tD;
			if ($d > $nd) break;
			}
		echo "</tr>";
		if ($d > $nd) break;
		}
	?>
	<tr>
	<?php
	for ($i=0;$i<=5;$i++) echo "<td></td>";
	?>
	<td class="td-b1">
	<span class="sp-gris">Total</span><br/>
	$<?php echo number_format($tm);?>
	</td>
	</tr>
	</table>
	</div>
	<?php
	}

if (isset($_GET["action"]) == true) switch ($_GET["action"]){
	case "drawSelected":
		drawMonth($_GET["suc"], $_GET["mth"], $_GET["yr"]);
		break;
	}

?>