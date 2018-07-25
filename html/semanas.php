<?php
header ("Content-type: text/html; charset=UTF-8");
require ("../php/php_cuentas.php");

if (isset($_GET["y"]) == true){
	$vS = array();
	$wF = array();
	$sucs = array("arista", "carranza", "el_dorado", "obregon", "othon", "zaragoza");
	$fS = mktime(12,0,0,1,1,intval($_GET["y"]));
	$iW = date("N", $fS);
	$nW = 0;
	while (true){ //weeks
		$tW = array("arista"=>0, "carranza"=>0, "el_dorado"=>0, "obregon"=>0, "othon"=>0, "zaragoza"=>0);
		for ($j=$iW;$j<=7;$j++){ //week days
			if ($j == $iW) $wF[$nW][0] = date("j", $fS)."/".cuentas_sp3m(date("n", $fS));
			if ($j == 7) $wF[$nW][1] = date("j", $fS)."/".cuentas_sp3m(date("n", $fS));
			$fD = date("Y-m-d", $fS);
			$fDA = explode("-", $fD);
			for ($i=0;$i<=5;$i++){ //sucs
				$tW[$sucs[$i]] += dayCash($sucs[$i], $fDA[2], $fDA[1], $fDA[0], $iW);
				}
			$fS += (24*3600);
			}
		for ($i=0;$i<=5;$i++) $vS[$nW][$sucs[$i]] = $tW[$sucs[$i]];
		$nW++;
		$iW = 1;
		if (date("Y", $fS) > intval($_GET["y"])) break;
		}
	$sucXML = xml_loadTiendasFile();
	}
?>

<!DOCTYPE html>

<html>
<head>
<meta charset="UTF-8"/>
<title>Ventas por Semana</title>
<link rel="stylesheet" type="text/css" href="../css/css_cuentas.css"/>
</head>

<body>
<div class="d-body">
	Sistema de Inventarios de Eclipse Shop v0.4
	<br/>
	Reporte de Ventas por Semana
	<br/><br/>
	<table width="100%">
		<tr style="background-color:rgb(191,228,118);">
			<?php
			if (isset($_GET["y"]) == true){
				echo "<th>Semana</th>";
				for ($i=0;$i<=5;$i++){
					echo "<th>".utf8_encode($sucXML[$sucs[$i]])."</th>\n";
					}
				echo "</tr>";
				for ($i=0;$i<=count($vS)-1;$i++){
					if ($i % 2 != 0) $tdbc = "rgb(224,243,176)";
					else $tdbc = "white";
					echo "<tr style=\"text-align:center;background-color:".$tdbc.";\"><td>".$wF[$i][0]." - ".$wF[$i][1]."</td>";
					for ($j=0;$j<=5;$j++){
						echo "<td>";
						if (max($vS[$i]) == $vS[$i][$sucs[$j]]) echo "<span style=\"color:blue;\">";
						if (min($vS[$i]) == $vS[$i][$sucs[$j]]) echo "<span style=\"color:red;\">";
						echo "$".$vS[$i][$sucs[$j]];
						if (max($vS[$i]) == $vS[$i][$sucs[$j]]) echo "</span>";
						if (min($vS[$i]) == $vS[$i][$sucs[$j]]) echo "</span>";
						echo "</td>";
						}
					echo "</tr>";
					}
				}
			?>
	</table>
</div>
</body>

</html>