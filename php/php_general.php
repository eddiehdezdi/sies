<?php

function gen_createId($str){
	$id = strtolower($str);
	$id = strtr($id, " -áéíóúñ", "__aeioun");
	return $id;
	}

function sp_month($m){
	$sp = "";
	switch($m){
		case 1: $sp = "Enero"; break;
		case 2: $sp = "Febrero"; break;
		case 3: $sp = "Marzo"; break;
		case 4: $sp = "Abril"; break;
		case 5: $sp = "Mayo"; break;
		case 6: $sp = "Junio"; break;
		case 7: $sp = "Julio"; break;
		case 8: $sp = "Agosto"; break;
		case 9: $sp = "Septiembre"; break;
		case 10: $sp = "Octubre"; break;
		case 11: $sp = "Noviembre"; break;
		case 12: $sp = "Diciembre"; break;
		}
	return $sp;
	}

function sp_weekDay($d){
	$sp = "";
	switch($d){
		case 0: $sp = "Domingo"; break;
		case 1: $sp = "Lunes"; break;
		case 2: $sp = "Martes"; break;
		case 3: $sp = utf8_encode("Miércoles"); break;
		case 4: $sp = "Jueves"; break;
		case 5: $sp = "Viernes"; break;
		case 6: $sp = utf8_encode("Sábado"); break;
		}
	return $sp;
	}

function gen_fullDate($dateStr){
	$dateA = explode("-", $dateStr);
	$dateST = mktime(0,0,0,intval($dateA[1]),intval($dateA[2]),intval($dateA[0]));
	$dayW = sp_weekDay(intval(date("w", $dateST)));
	$Month = sp_month(intval($dateA[1]));
	return $dayW . " " . $dateA[2] . " de " . $Month . ", " . $dateA[0];
	}

function gen_createFolderFiles($sucId){
	mkdir("../xml/" . $sucId);
	mkdir("../xml/" . $sucId . "/inventarios");
	mkdir("../xml/" . $sucId . "/libreta");
	mkdir("../xml/" . $sucId . "/listas");
	xml_createSucFiles($sucId, "inventarios");
	xml_createSucFiles($sucId, "lista");
	}

function gen_showMoneyCant($cant){
	$cantStr = "";
	if ($cant < 0) $cantStr = "<span style=\"color:red;\">-$" . -1 * $cant . "</span>";
	if ($cant == 0) $cantStr = "<span>$" . $cant . "</span>";
	if ($cant > 0) $cantStr = "<span style=\"color:blue;\">+$" . $cant . "</span>";
	echo $cantStr;
	}

function gen_showRedBlueCant($cant){
	$cantStr = "";
	if ($cant < 0) $cantStr = "<span style=\"color:red;\">" . $cant . "</span>";
	if ($cant == 0) $cantStr = "<span>" . $cant . "</span>";
	if ($cant > 0) $cantStr = "<span style=\"color:blue;\">+" . $cant . "</span>";
	echo $cantStr;
	}

?>