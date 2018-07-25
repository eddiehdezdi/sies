<?php

$suc = $_GET["suc"];
$date = $_GET["ini"];
$term = $_GET["term"];
$id = $_GET["art"];

$conteo = 0;

while(true){
	$libPath = "../xml/".$suc."/libreta/".$date.".xml";
	if (file_exists($libPath) == true){
		$libXML = simplexml_load_file($libPath);
		$matchArray = $libXML->ventas->xpath("renglon[id='".$id."']");
		$conteo += count($matchArray);
		}
	if ($date == $term) break;
	else {
		$da = explode("-", $date);
		$dateSt = mktime(0,0,0,$da[1],$da[2],$da[0]) + 90000;
		$date = date("Y-m-d", $dateSt);
		}
	}
echo $conteo;

?>