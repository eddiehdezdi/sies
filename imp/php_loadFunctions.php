<?php
require("../php/php_general.php");
require("../php/php_xmlDOM.php");

function lF_loadListFile($suc, $date){
	$array = array();
	$xmlDoc = new DOMDocument();
	if ($date == "existencias") $xmlDoc->load("../../v0_1/san_luis/" . $suc . "/existencias.xml");
	else $xmlDoc->load("../../v0_1/san_luis/" . $suc . "/inventarios/existencias/" . $date . ".xml");
	$elements_articulo = $xmlDoc->getElementsByTagName("articulo");
	for ($i=0;$i<=$elements_articulo->length-1;$i++){
		$element_articulo = $elements_articulo->item($i);
		$array[xml_childText($element_articulo, "system")] = array();
		$array[xml_childText($element_articulo, "system")]["name"] = xml_childText($element_articulo, "nombre");
		$array[xml_childText($element_articulo, "system")]["precio"] = intval(xml_childText($element_articulo, "precio"));
		}
	return $array;
	}

function lF_loadInvFile($suc, $date){
	$array = array();
	$xmlDoc = new DOMDocument();
	//firmas
	$xmlDoc->load("../../v0_1/san_luis/" . $suc . "/inventarios/" . $date . ".xml");
	$array["firmas"] = array();
	if ($xmlDoc->getElementsByTagName("entrega")->length > 0){
		$array["firmas"]["entrega"] = xml_childText($xmlDoc, "entrega");
		}
	else $array["firmas"]["entrega"] = "Eduardo";
	$array["firmas"]["recibe"] = xml_childText($xmlDoc, "recibe");
	$array["firmas"]["realiza"] = "Eduardo";
	//lista
	$array["lista"] = array();
	$elements_articulo = $xmlDoc->getElementsByTagName("articulo");
	for ($i=0;$i<=$elements_articulo->length-1;$i++){
		$element_listArt = $elements_articulo->item($i);
		$list_artId = xml_childText($element_listArt, "system");
		$array["lista"][$list_artId] = array();
		$array["lista"][$list_artId]["anterior"] = 0;
		$array["lista"][$list_artId]["entradas"] = 0;
		$array["lista"][$list_artId]["salidas"] = 0;
		$array["lista"][$list_artId]["existencia"] = 0;
		$array["lista"][$list_artId]["diferencia"] = 0;
		$array["lista"][$list_artId]["disponible"] = intval(xml_childText($element_listArt, "disponible"));
		}
	//mapeo
	$array["mapeo"] = array();
	$mapPath = "../../v0_1/san_luis/" . $suc . "/inventarios/mapeos/" . $date . ".xml";
	if (file_exists($mapPath)){
		$xmlDoc->load($mapPath);
		$elements_seccion = $xmlDoc->getElementsByTagName("seccion");
		for ($i=0;$i<=$elements_seccion->length-1;$i++){
			$sect_name = xml_childText($elements_seccion->item($i), "nombre");
			$array["mapeo"][$i] = array();
			$array["mapeo"][$i][0] = gen_createId($sect_name);
			$array["mapeo"][$i][1] = $sect_name;
			$array["mapeo"][$i][2] = array();
			$array["mapeo"][$i][2][0] = gen_createId($sect_name);
			$array["mapeo"][$i][2][1] = $sect_name;
			$elements_articulo = $elements_seccion->item($i)->getElementsByTagName("articulo");
			for ($k=0;$k<=$elements_articulo->length-1;$k++){
				$element_articulo = $elements_articulo->item($k);
				$array["mapeo"][$i][2][$k+2] = array();
				$array["mapeo"][$i][2][$k+2]["id"] = xml_childText($element_articulo, "system");
				$array["mapeo"][$i][2][$k+2]["cantidad"] = intval(xml_childText($element_articulo, "cantidad"));
				}
			}
		}
	//faltantes y sobrantes no es necesario cargarlos, estos datos se calculan!!
	$array["faltantes"] = array();
	$array["totales"] = array();
	$array["totales"]["faltante"] = 0;
	$array["totales"]["cortes"] = 0;
	$array["totales"]["compensado"] = 0;
	$array["totales"]["total"] = 0;
	return $array;
	}

function lF_loadLibFile($suc, $date){
	$array = array();
	$xmlDoc = new DOMDocument();
	$xmlDoc->load("../../v0_1/san_luis/" . $suc . "/libreta/" . $date . ".xml");
	//ventas
	$array["ventas"] = array();
	$elements_movimiento = $xmlDoc->getElementsByTagName("ventas")->item(0)->getElementsByTagName("movimiento");
	if ($elements_movimiento->length > 0){
		for ($i=0;$i<=$elements_movimiento->length-1;$i++){
			$element_movimiento = $elements_movimiento->item($i);
			$array["ventas"][$i] = array();
			if (xml_childText($element_movimiento, "tipo") == "inventario"){
				$array["ventas"][$i]["type"] = "inventario";
				$array["ventas"][$i]["id"] = "Inventario";
				$array["ventas"][$i]["precio"] = -1;
				}
			else {
				$array["ventas"][$i]["type"] = "articulo";
				$array["ventas"][$i]["id"] = xml_childText($element_movimiento, "system");
				$array["ventas"][$i]["precio"] = intval(xml_childText($element_movimiento, "precio"));
				}
			}
		}
	//entradas y salidas
	$array["es"] = array();
	$array["es"]["entradas"] = array();
	$array["es"]["salidas"] = array();
	$elements_movimiento = $xmlDoc->getElementsByTagName("entradas_salidas")->item(0)->getElementsByTagName("movimiento");
	if ($elements_movimiento->length > 0){
		$es_type = ""; $ie = 0; $is = 0;
		for ($i=0;$i<=$elements_movimiento->length-1;$i++){
			$element_movimiento = $elements_movimiento->item($i);
			if (xml_childText($element_movimiento, "tipo") == "entrada"){
				$array["es"]["entradas"][$ie] = array();
				$array["es"]["entradas"][$ie]["id"] = xml_childText($element_movimiento, "system");
				$array["es"]["entradas"][$ie]["cantidad"] = intval(xml_childText($element_movimiento, "cantidad"));
				$ie++;
				}
			else {
				$array["es"]["salidas"][$is] = array();
				$array["es"]["salidas"][$is]["id"] = xml_childText($element_movimiento, "system");
				$array["es"]["salidas"][$is]["cantidad"] = intval(xml_childText($element_movimiento, "cantidad"));
				$is++;
				}
			}
		}
	//cortes
	$array["cortes"] = 0;
	$element_fs = $xmlDoc->getElementsByTagName("faltante_sobrante")->item(0);
	if ($element_fs->getElementsByTagName("tipo")->length > 0){
		if (xml_childText($element_movimiento, "tipo") == "faltante"){
			$array["cortes"] = -1 * intval(xml_childText($element_movimiento, "cantidad"));
			}
		else $array["cortes"] = intval(xml_childText($element_movimiento, "cantidad"));
		}
	return $array;
	}

?>