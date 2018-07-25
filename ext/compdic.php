<?php

require ("../php/php_xmlDOM.php");

$sucL = array("arista", "b_anaya", "carranza", "el_dorado", "obregon", "othon", "zaragoza");

function dayCash($suc, $dy, $mth, $yr){
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

?>

<html>
<head>
<title>Comparativa Dic 2012-2013</title>
</head>
<body>
<h2>Comparativa Diciembre 2012 - 2013</h2>
<table>
<tr>
	<th></th>
	<?php
	for ($i=1;$i<=31;$i++){
		echo "<th>" . $i . "</th>\n";
		}
	?>
	<th>Total</th>
</tr>
<?php
$tiendXML = xml_loadTiendasFile();
for ($i=0;$i<=6;$i++){
	$sucId = $tiendXML[$sucL[$i]]
	$sucName = utf8_encode($sucId);
	
	}
?>
</table>
</body>
</html>