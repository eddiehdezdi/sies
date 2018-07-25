<?php

$sucPath = "../xml/sucursales.xml";

function xml_createElement(&$xmlDoc, &$parent, $name, $text=""){
	$element = array();
	$element["element"] = $xmlDoc->createElement($name);
	$parent->appendChild($element["element"]);
	if ($text != ""){
		$element["text"] = $xmlDoc->createTextNode($text);
		$element["element"]->appendChild($element["text"]);
		}
	return $element;
	}

function xml_childText($xmlElement, $child){
	return $xmlElement->getElementsByTagName($child)->item(0)->firstChild->data;
	}

function xml_showSelectListOptions($suc, $date = ""){
	$listPath = "";
	if ($date == "") $listPath = "../xml/" . $suc . "/lista.xml";
	else $listPath = "../xml/" . $suc . "/listas/" . $date . ".xml";
	$xmlDoc = new DOMDocument();
	$xmlDoc->load($listPath);
	$elements_articulo = $xmlDoc->getElementsByTagName("articulo");
	for ($i=0;$i<=$elements_articulo->length-1;$i++){
		$element_articulo = $elements_articulo->item($i);
		?>
		<option value="<?php echo xml_childText($element_articulo, "id");?>"><?php echo xml_childText($element_articulo, "name");?></option>
		<?php
		}
	}

function xml_createSucFiles($suc, $file){
	//file puede ser ´lista´ o ´inventarios´
	$filesPath = "../xml/" . $suc . "/";
	$xmlDoc = new DOMDocument();
	xml_createElement($xmlDoc, $xmlDoc, $file);
	$xmlDoc->formatOutput = true;
	$xmlDoc->save($filesPath . $file . ".xml");
	}

function xml_loadSucFile(){ // <-- eliminar en próxima actualización
global $sucPath;
	$xmlDoc = new DOMDocument();
	if (file_exists($sucPath)) $xmlDoc->load($sucPath);
  	$array = array();
		$elements_suc = $xmlDoc->getElementsByTagName("sucursal");
			for ($i=0; $i<=$elements_suc->length-1; $i++){
				$array[$i]["id"] = xml_childText($elements_suc->item($i), "id");
				$array[$i]["name"] = xml_childText($elements_suc->item($i), "name");
				}
	return $array;
	}

function xml_loadTiendasFile(){
	global $sucPath;
	$xmlDoc = new DOMDocument();
	if (file_exists($sucPath)) $xmlDoc->load($sucPath);
  	$array = array();
		$elements_suc = $xmlDoc->getElementsByTagName("sucursal");
			for ($i=0; $i<=$elements_suc->length-1; $i++){
				$id = xml_childText($elements_suc->item($i), "id");
				$array[$id] = utf8_decode(xml_childText($elements_suc->item($i), "name"));
				}
	return $array;
	}

function xml_saveTiendasFile(&$array){
	global $sucPath;
	$xmlDoc = new DOMDocument();
	$root = xml_createElement($xmlDoc, $xmlDoc, "plaza");
	$lK = array_keys($array);
	for ($i=0; $i<=count($lK)-1; $i++){
		$element = xml_createElement($xmlDoc, $root["element"], "sucursal");
		$child = xml_createElement($xmlDoc, $element["element"], "id", $lK[$i]);
		$child = xml_createElement($xmlDoc, $element["element"], "name", utf8_encode($array[$lK[$i]]));
		}
	if (file_exists($sucPath)) unlink($sucPath);
	$xmlDoc->formatOutput = true;
	$xmlDoc->save($sucPath);
	}

function xml_saveSucFile(&$array){ // <-- eliminar en próxima actualización
global $sucPath;
	$xmlDoc = new DOMDocument();
	$root = xml_createElement($xmlDoc, $xmlDoc, "plaza");
	for ($i=0; $i<=count($array)-1; $i++){
		$element = xml_createElement($xmlDoc, $root["element"], "sucursal");
		$child = xml_createElement($xmlDoc, $element["element"], "id", $array[$i]["id"]);
		$child = xml_createElement($xmlDoc, $element["element"], "name", $array[$i]["name"]);
		}
	if (file_exists($sucPath)) unlink($sucPath);
	$xmlDoc->formatOutput = true;
	$xmlDoc->save($sucPath);
	}

function xml_createFile($type, $suc, $fecha){
	$xmlDoc = new DOMDocument();
	$rootName = "";
	switch ($type){
		case "inventarios":
			$rootName = "inventario";
		break;
		case "libreta":
			$rootName = "hoja";
		break;
		}
	xml_createElement($xmlDoc, $xmlDoc, $rootName);
	$xmlDoc->save("../xml/" . $suc . "/" . $type . "/" . $fecha . ".xml");
	}

function xml_loadListFile($suc, $date = ""){
	$listPath = "";
	if ($date == "") $listPath = "../xml/" . $suc . "/lista.xml";
	else $listPath = "../xml/" . $suc . "/listas/" . $date . ".xml";
	$array = array();
	$xmlDoc = new DOMDocument();
	$xmlDoc->load($listPath);
	$elements_articulo = $xmlDoc->getElementsByTagName("articulo");
	if ($elements_articulo->length > 0){
		for ($i=0;$i<=$elements_articulo->length-1;$i++){
			$element_articulo = $elements_articulo->item($i);
			$array[xml_childText($element_articulo, "id")] = array();
			$array[xml_childText($element_articulo, "id")]["name"] = xml_childText($element_articulo, "name");
			$array[xml_childText($element_articulo, "id")]["precio"] = intval(xml_childText($element_articulo, "precio"));
			}
		}
	return $array;
	}

function xml_saveListFile(&$array, $suc, $date = ""){
	if ($date == "") $listPath = "../xml/" . $suc . "/lista.xml";
	else $listPath = "../xml/" . $suc . "/listas/" . $date . ".xml";
	$xmlDoc = new DOMDocument();
	$rootElement = xml_createElement($xmlDoc, $xmlDoc, "lista");
	$a_keys = array_keys($array);
	for ($i=0;$i<=count($a_keys)-1;$i++){
		$element = xml_createElement($xmlDoc, $rootElement["element"], "articulo");
		xml_createElement($xmlDoc, $element["element"], "id", $a_keys[$i]);
		xml_createElement($xmlDoc, $element["element"], "name", $array[$a_keys[$i]]["name"]);
		xml_createElement($xmlDoc, $element["element"], "precio", strval($array[$a_keys[$i]]["precio"]));
		}
	if (file_exists($listPath)) unlink($listPath);
	$xmlDoc->formatOutput = true;
	$xmlDoc->save($listPath);
	}

function xml_saveListCopyFile(&$array, $suc){
	$listPath = "../xml/" . $suc . "/listas/" . $date . ".xml";
	$xmlDoc = new DOMDocument();
	$rootElement = xml_createElement($xmlDoc, $xmlDoc, "lista");
	$a_keys = array_keys($array);
	for ($i=0;$i<=count($a_keys)-1;$i++){
		$element = xml_createElement($xmlDoc, $rootElement["element"], "articulo");
		xml_createElement($xmlDoc, $element["element"], "id", $a_keys[$i]);
		xml_createElement($xmlDoc, $element["element"], "name", $array[$a_keys[$i]]["name"]);
		xml_createElement($xmlDoc, $element["element"], "precio", strval($array[$a_keys[$i]]["precio"]));
		}
	if (file_exists($listPath)) unlink($listPath);
	$xmlDoc->formatOutput = true;
	$xmlDoc->save($listPath);
	}

function xml_loadLibFile($suc, $fecha){
	$libPath = "../xml/" . $suc . "/libreta/" . $fecha . ".xml";
	$array = array();
	$xmlDoc = new DOMDocument();
	$xmlDoc->load($libPath);
	//ventas
	$array["ventas"] = array();
	if ($xmlDoc->getElementsByTagName("ventas")->length > 0){
	$elements_renglon = $xmlDoc->getElementsByTagName("ventas")->item(0)->getElementsByTagName("renglon");
		if ($elements_renglon->length > 0){
			for ($i=0;$i<=$elements_renglon->length-1;$i++){
				$element_renglon = $elements_renglon->item($i);
				$array["ventas"][$i] = array();
				$array["ventas"][$i]["type"] = xml_childText($element_renglon, "type");
				$array["ventas"][$i]["id"] = xml_childText($element_renglon, "id");
				$array["ventas"][$i]["precio"] = intval(xml_childText($element_renglon, "precio"));
				}
			}
		}
	//entradas y salidas
	$array["es"] = array();
	$array["es"]["entradas"] = array();
	$array["es"]["salidas"] = array();
	if ($xmlDoc->getElementsByTagName("es")->length > 0){
		if ($xmlDoc->getElementsByTagName("entradas")->length > 0){
			$elements_entrada = $xmlDoc->getElementsByTagName("entrada");
			if ($elements_entrada->length > 0){
				for ($i=0;$i<=$elements_entrada->length-1;$i++){
					$element_entrada = $elements_entrada->item($i);
					$array["es"]["entradas"][$i] = array();
					$array["es"]["entradas"][$i]["id"] = xml_childText($element_entrada, "id");
					$array["es"]["entradas"][$i]["cantidad"] = intval(xml_childText($element_entrada, "cantidad"));
					}
				}
			}
		if ($xmlDoc->getElementsByTagName("salidas")->length > 0){
			$elements_salida = $xmlDoc->getElementsByTagName("salida");
			if ($elements_salida->length > 0){
				for ($i=0;$i<=$elements_salida->length-1;$i++){
					$element_salida = $elements_salida->item($i);
					$array["es"]["salidas"][$i] = array();
					$array["es"]["salidas"][$i]["id"] = xml_childText($element_salida, "id");
					$array["es"]["salidas"][$i]["cantidad"] = intval(xml_childText($element_salida, "cantidad"));
					}
				}
			}
		}
	//cortes
	$array["cortes"] = 0;
	$elements_cortes = $xmlDoc->getElementsByTagName("cortes");
	if ($elements_cortes->length > 0) $array["cortes"] = intval($elements_cortes->item(0)->firstChild->data);
	return $array;
	}

function xml_saveLibFile(&$array, $suc, $fecha){
	$libPath = "../xml/" . $suc . "/libreta/" . $fecha . ".xml";
	$xmlDoc = new DOMDocument();
	$rootElement = xml_createElement($xmlDoc, $xmlDoc, "hoja");
	//ventas
	if (count($array["ventas"]) > 0){
		$element_ventas = xml_createElement($xmlDoc, $rootElement["element"], "ventas");
		for ($i=0;$i<=count($array["ventas"])-1;$i++){
			$element = xml_createElement($xmlDoc, $element_ventas["element"], "renglon");
			xml_createElement($xmlDoc, $element["element"], "type", $array["ventas"][$i]["type"]);
			xml_createElement($xmlDoc, $element["element"], "id", $array["ventas"][$i]["id"]);
			xml_createElement($xmlDoc, $element["element"], "precio", strval($array["ventas"][$i]["precio"]));
			}
		}
	//entradas y salidas
	if (count($array["es"]["entradas"]) > 0 || count($array["es"]["salidas"]) > 0){
		$element_es = xml_createElement($xmlDoc, $rootElement["element"], "es");
		if (count($array["es"]["entradas"]) > 0){
			$element_entradas = xml_createElement($xmlDoc, $element_es["element"], "entradas");
			for ($i=0;$i<=count($array["es"]["entradas"])-1;$i++){
				$element = xml_createElement($xmlDoc, $element_entradas["element"], "entrada");
				xml_createElement($xmlDoc, $element["element"], "id", $array["es"]["entradas"][$i]["id"]);
				xml_createElement($xmlDoc, $element["element"], "cantidad", strval($array["es"]["entradas"][$i]["cantidad"]));
				}
			}
		if (count($array["es"]["salidas"]) > 0){
			$element_salidas = xml_createElement($xmlDoc, $element_es["element"], "salidas");
			for ($i=0;$i<=count($array["es"]["salidas"])-1;$i++){
				$element = xml_createElement($xmlDoc, $element_salidas["element"], "salida");
				xml_createElement($xmlDoc, $element["element"], "id", $array["es"]["salidas"][$i]["id"]);
				xml_createElement($xmlDoc, $element["element"], "cantidad", strval($array["es"]["salidas"][$i]["cantidad"]));
				}
			}
		}
	//cortes
	if ($array["cortes"] != 0) xml_createElement($xmlDoc, $rootElement["element"], "cortes", strval($array["cortes"]));
	//guardar
	if (file_exists($libPath)) unlink($libPath);
	$xmlDoc->formatOutput = true;
	$xmlDoc->save($libPath);
	}

function xml_createInventariosXML($suc){
	$xmlDoc = new DOMDocument();
	xml_createElement($xmlDoc, $xmlDoc, "inventarios");
	$xmlDoc->save("../xml/" . $suc . "/inventarios.xml");
	}

function xml_loadInventariosXML($suc){
	$xmlDoc = new DOMDocument();
	$xmlDoc->load("../xml/" . $suc . "/inventarios.xml");
	$elements_inventario = $xmlDoc->getElementsByTagName("inventario");
	$array = array();
	for ($i=0;$i<=$elements_inventario->length-1;$i++){	
		$array[$i] = $elements_inventario->item($i)->firstChild->data;
		}
	return $array;
	}

function xml_saveInventariosXML(&$array, $suc){
	$xmlDoc = new DOMDocument();
	$rootElement = xml_createElement($xmlDoc, $xmlDoc, "inventarios");
	for ($i=0;$i<=count($array)-1;$i++){
		xml_createElement($xmlDoc, $rootElement["element"], "inventario", $array[$i]);
		}
	$invXMLPath = "../xml/" . $suc . "/inventarios.xml";
	if (file_exists($invXMLPath)) unlink($invXMLPath);
	$xmlDoc->formatOutput = true;
	$xmlDoc->save($invXMLPath);
	}

function xml_loadInvFile($suc, $date){
	$invPath = "../xml/" . $suc . "/inventarios/" . $date . ".xml";
	$xmlDoc = new DOMDocument();
	$xmlDoc->load($invPath);
	$array = array();
	//firmas
	$elements_firmas = $xmlDoc->getElementsByTagName("firmas");
	$array["firmas"] = array();
	if ($elements_firmas->length > 0){
		$element_firmas = $elements_firmas->item(0);
		$array["firmas"]["entrega"] = xml_childText($element_firmas, "entrega");
		$array["firmas"]["recibe"] = xml_childText($element_firmas, "recibe");
		$array["firmas"]["realiza"] = xml_childText($element_firmas, "realiza");
		}
	//mapeo
	$elements_mapeo = $xmlDoc->getElementsByTagName("mapeo");
	$array["mapeo"] = array();
	if ($elements_mapeo->length > 0){
		$elements_seccion = $elements_mapeo->item(0)->getElementsByTagName("seccion");
		if ($elements_seccion->length > 0){
			for ($i=0;$i<=$elements_seccion->length-1;$i++){
				$array["mapeo"][$i] = array();
				$array["mapeo"][$i][0] = xml_childText($elements_seccion->item($i), "id");
				$array["mapeo"][$i][1] = xml_childText($elements_seccion->item($i), "name");
				$elements_sub = $elements_seccion->item($i)->getElementsByTagName("sub");
				if ($elements_sub->length > 0){
					for ($j=0;$j<=$elements_sub->length-1;$j++){
						$array["mapeo"][$i][$j+2] = array();
						$array["mapeo"][$i][$j+2][0] = xml_childText($elements_sub->item($j), "id");
						$array["mapeo"][$i][$j+2][1] = xml_childText($elements_sub->item($j), "name");
						$elements_articulo = $elements_sub->item($j)->getElementsByTagName("articulo");
						if ($elements_articulo->length > 0){
							for ($k=0;$k<=$elements_articulo->length-1;$k++){
								$element_articulo = $elements_articulo->item($k);
								$array["mapeo"][$i][$j+2][$k+2] = array();
								$array["mapeo"][$i][$j+2][$k+2]["id"] = xml_childText($element_articulo, "id");
								$array["mapeo"][$i][$j+2][$k+2]["cantidad"] = intval(xml_childText($element_articulo, "cantidad"));
								}
							}
						}
					}
				}
			}
		}
	//lista
	$elements_lista = $xmlDoc->getElementsByTagName("lista");
	$array["lista"] = array();
	if ($elements_lista->length > 0){
		$elements_listArt = $elements_lista->item(0)->getElementsByTagName("articulo");
		if ($elements_listArt->length > 0){
			for ($i=0;$i<=$elements_listArt->length-1;$i++){
				$element_listArt = $elements_listArt->item($i);
				$list_artId = xml_childText($element_listArt, "id");
				$array["lista"][$list_artId] = array();
				$array["lista"][$list_artId]["anterior"] = intval(xml_childText($element_listArt, "anterior"));
				$array["lista"][$list_artId]["entradas"] = intval(xml_childText($element_listArt, "entradas"));
				$array["lista"][$list_artId]["salidas"] = intval(xml_childText($element_listArt, "salidas"));
				$array["lista"][$list_artId]["existencia"] = intval(xml_childText($element_listArt, "existencia"));
				$array["lista"][$list_artId]["diferencia"] = intval(xml_childText($element_listArt, "diferencia"));
				$array["lista"][$list_artId]["disponible"] = intval(xml_childText($element_listArt, "disponible"));
				}
			}
		}
	//faltantes y sobrantes
	$elements_faltantes = $xmlDoc->getElementsByTagName("faltantes");
	$array["faltantes"] = array();
	if ($elements_faltantes->length > 0){
		//faltantes y compensados
		$elements_faltante = $elements_faltantes->item(0)->getElementsByTagName("faltante");
		if ($elements_faltante->length > 0){
			for ($i=0;$i<=$elements_faltante->length-1;$i++){
				$element_faltante = $elements_faltante->item($i);
				$array["faltantes"][$i] = array();
				$array["faltantes"][$i]["id"] = xml_childText($element_faltante, "id");
				$array["faltantes"][$i]["cantidad"] = intval(xml_childText($element_faltante, "cantidad"));
				$elements_compensan = $element_faltante->getElementsByTagName("compensan");
				$array["faltantes"][$i]["compensan"] = array();
				if ($elements_compensan->length > 0){
					$elements_compensa = $elements_compensan->item(0)->getElementsByTagName("compensa");
					if ($elements_compensa->length > 0){	
						for ($j=0;$j<=$elements_compensa->length-1;$j++){
							$element_compensa = $elements_compensa->item($j);
							$array["faltantes"][$i]["compensan"][$j] = array();
							$array["faltantes"][$i]["compensan"][$j]["id"] = xml_childText($element_compensa, "id");
							$array["faltantes"][$i]["compensan"][$j]["cantidad"] = intval(xml_childText($element_compensa, "cantidad"));
							}
						}
					}
				}
			}
		}
	//totales
	$array["totales"] = array();
	$elements_totales = $xmlDoc->getElementsByTagName("totales");
	if ($elements_totales->length > 0){
		$array["totales"]["faltante"] = intval(xml_childText($elements_totales->item(0), "faltante"));
		$array["totales"]["cortes"] = intval(xml_childText($elements_totales->item(0), "cortes"));
		$array["totales"]["compensado"] = intval(xml_childText($elements_totales->item(0), "compensado"));
		$array["totales"]["total"] = intval(xml_childText($elements_totales->item(0), "total"));
		}
	else {
		$array["totales"]["faltante"] = 0;
		$array["totales"]["cortes"] = 0;
		$array["totales"]["compensado"] = 0;
		$array["totales"]["total"] = 0;
		}
	return $array;
	}

function xml_saveInvFile(&$array, $suc, $date){
	$invPath = "../xml/" . $suc . "/inventarios/" . $date . ".xml";
	$xmlDoc = new DOMDocument();
	$rootElement = xml_createElement($xmlDoc, $xmlDoc, "inventario");
	//firmas
	if (count($array["firmas"]) > 0){
		$element = xml_createElement($xmlDoc, $rootElement["element"], "firmas");
		xml_createElement($xmlDoc, $element["element"], "entrega", $array["firmas"]["entrega"]);
		xml_createElement($xmlDoc, $element["element"], "recibe", $array["firmas"]["recibe"]);
		xml_createElement($xmlDoc, $element["element"], "realiza", $array["firmas"]["realiza"]);
		}
	//mapeo
	if (count($array["mapeo"]) > 0){
		$element = xml_createElement($xmlDoc, $rootElement["element"], "mapeo");
		for ($i=0;$i<=count($array["mapeo"])-1;$i++){
			$element_seccion = xml_createElement($xmlDoc, $element["element"], "seccion");
			xml_createElement($xmlDoc, $element_seccion["element"], "id", $array["mapeo"][$i][0]);
			xml_createElement($xmlDoc, $element_seccion["element"], "name", $array["mapeo"][$i][1]);
			if (count($array["mapeo"][$i]) > 2){
				for ($j=2;$j<=count($array["mapeo"][$i])-1;$j++){
					$element_sub = xml_createElement($xmlDoc, $element_seccion["element"], "sub");
					xml_createElement($xmlDoc, $element_sub["element"], "id", $array["mapeo"][$i][$j][0]);
					xml_createElement($xmlDoc, $element_sub["element"], "name", $array["mapeo"][$i][$j][1]);
					if (count($array["mapeo"][$i][$j]) > 2){
						for ($k=2;$k<=count($array["mapeo"][$i][$j])-1;$k++){
							$element_articulo = xml_createElement($xmlDoc, $element_sub["element"], "articulo");
							xml_createElement($xmlDoc, $element_articulo["element"], "id", $array["mapeo"][$i][$j][$k]["id"]);
							xml_createElement($xmlDoc, $element_articulo["element"], "cantidad", strval($array["mapeo"][$i][$j][$k]["cantidad"]));
							}
						}
					}
				}
			}
		}
	//lista
	if (count($array["lista"]) > 0){
		$element = xml_createElement($xmlDoc, $rootElement["element"], "lista");
		$lK = array_keys($array["lista"]);
		for ($i=0;$i<=count($array["lista"])-1;$i++){
			$element_articulo = xml_createElement($xmlDoc, $element["element"], "articulo");
			xml_createElement($xmlDoc, $element_articulo["element"], "id", $lK[$i]);
			xml_createElement($xmlDoc, $element_articulo["element"], "anterior", strval($array["lista"][$lK[$i]]["anterior"]));
			xml_createElement($xmlDoc, $element_articulo["element"], "entradas", strval($array["lista"][$lK[$i]]["entradas"]));
			xml_createElement($xmlDoc, $element_articulo["element"], "salidas", strval($array["lista"][$lK[$i]]["salidas"]));
			xml_createElement($xmlDoc, $element_articulo["element"], "existencia", strval($array["lista"][$lK[$i]]["existencia"]));
			xml_createElement($xmlDoc, $element_articulo["element"], "diferencia", strval($array["lista"][$lK[$i]]["diferencia"]));
			xml_createElement($xmlDoc, $element_articulo["element"], "disponible", strval($array["lista"][$lK[$i]]["disponible"]));
			}
		}
	//faltantes y sobrantes
	if (count($array["faltantes"]) > 0){
		$element_faltantes = xml_createElement($xmlDoc, $rootElement["element"], "faltantes");
		for ($i=0;$i<=count($array["faltantes"])-1;$i++){
			$element = xml_createElement($xmlDoc, $element_faltantes["element"], "faltante");
			$child = xml_createElement($xmlDoc, $element["element"], "id", $array["faltantes"][$i]["id"]);
			$child = xml_createElement($xmlDoc, $element["element"], "cantidad", strval($array["faltantes"][$i]["cantidad"]));
			if (count($array["faltantes"][$i]["compensan"]) > 0){
				$element_compensan = xml_createElement($xmlDoc, $element["element"], "compensan");
				for ($j=0;$j<=count($array["faltantes"][$i]["compensan"])-1;$j++){
					$element = xml_createElement($xmlDoc, $element_compensan["element"], "compensa");
					$child = xml_createElement($xmlDoc, $element["element"], "id", $array["faltantes"][$i]["compensan"][$j]["id"]);
					$child = xml_createElement($xmlDoc, $element["element"], "cantidad", strval($array["faltantes"][$i]["compensan"][$j]["cantidad"]));
					}
				}
			}
		}
	//totales
	$element_totales = xml_createElement($xmlDoc, $rootElement["element"], "totales");
	xml_createElement($xmlDoc, $element_totales["element"], "faltante", strval($array["totales"]["faltante"]));
	xml_createElement($xmlDoc, $element_totales["element"], "cortes", strval($array["totales"]["cortes"]));
	xml_createElement($xmlDoc, $element_totales["element"], "compensado", strval($array["totales"]["compensado"]));
	xml_createElement($xmlDoc, $element_totales["element"], "total", strval($array["totales"]["total"]));
	//guardar
	if (file_exists($invPath)) unlink($invPath);
	$xmlDoc->formatOutput = true;
	$xmlDoc->save($invPath);
	}

function xml_ordenaLista($suc, $date){
	$listXML = xml_loadListFile($suc, $date);
	$ids = array_keys($listXML);
	$tempList = array();
	for ($i=0;$i<=count($ids)-1;$i++) $tempList[$ids[$i]] = $listXML[$ids[$i]]["name"];
	ksort($tempList);
	$ids = array_keys($tempList);
	$newList = array();
	for ($i=0;$i<=count($ids)-1;$i++){
		$newList[$ids[$i]] = array("name"=>$listXML[$ids[$i]]["name"], "precio"=>$listXML[$ids[$i]]["precio"]);
		}
	xml_saveListFile($newList, $suc, $date);
	}
/*
function xml_ordenaLista($suc){
	$listXML = xml_loadListFile($suc);
	
	xml_saveListFile($newList, $suc);
	}
*/
function xml_newListArt($suc, $artName, $artId){
	$listXML = xml_loadListFile($suc);
	$listXML[$artId] = array();
	$listXML[$artId]["name"] = $artName;
	$listXML[$artId]["precio"] = 0;
	xml_saveListFile($listXML, $suc);
	}

function xml_prevInvDate($suc, $date){
	$invHist = xml_loadInventariosXML($suc);
	$prev = "";
	for ($i=0;$i<=count($invHist)-2;$i++){
		if ($invHist[$i+1] == $date){
			$prev = $invHist[$i];
			break;
			}
		}
	return $prev;
	}

function xml_modPrecio($suc, $art, $precio, $date = ""){
	$listXML = xml_loadListFile($suc, $date);
	$listXML[$art]["precio"] = $precio;
	xml_saveListFile($listXML, $suc, $date);
	}
?>