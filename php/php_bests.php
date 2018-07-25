<?php

require ("../php/php_xmlDOM.php");

function bests_getMonthList($suc, $m, $y){
	$array = array();
	$d_stamp = mktime(12,0,0,$m,1,$y);
	//llenar el array
	while (true){
		$d_str = date("Y-m-d", $d_stamp);
		if (file_exists("../xml/" . $suc . "/libreta/" . $d_str . ".xml") == true){
			$xmlLib = xml_loadLibFile($suc, $d_str);
			for ($i=0;$i<=count($xmlLib["ventas"])-1;$i++){
				if ($xmlLib["ventas"][$i]["type"] == "articulo"){
					if (array_key_exists($xmlLib["ventas"][$i]["id"], $array) == false){
						$array[$xmlLib["ventas"][$i]["id"]] = 1;
						}
					else $array[$xmlLib["ventas"][$i]["id"]] += 1;
					}
				}
			}
		$d_stamp += 86400;
		if ((date("n", $d_stamp) - $m) != 0) break;
		}
	//ordenar y devolver array
	arsort($array);
	return $array;
	}

function bests_loadFile($suc, $y){
	$f_path = "../xml/" . $suc . "/bests";
	if (file_exists($f_path) == false) mkdir($f_path);
	$array = array();
	$f_path .= "/" . strval($y) . ".xml";
	if (file_exists($f_path) == true){
		$xmlDoc = new DOMDocument();
		$xmlDoc->load($f_path);
		$elements_mes = $xmlDoc->getElementsByTagName("mes");
		for ($i=0;$i<=$elements_mes->length-1;$i++){
			$mes_s = ucfirst(xml_childText($elements_mes->item($i), "mes_s"));
			$array[$mes_s] = array();
			$elements_articulo = $elements_mes->item($i)->getElementsByTagName("articulo");
			for ($j=0;$j<=$elements_articulo->length-1;$j++){
				$array[$mes_s][xml_childText($elements_articulo->item($j), "id")] = xml_childText($elements_articulo->item($j), "cantidad");
				}
			}
		}
	return $array;
	}

function bests_saveFile($suc, $y, $array){
	$f_path = "../xml/" . $suc . "/bests";
	if (file_exists($f_path) == false) mkdir($f_path);
	$xmlDoc = new DOMDocument();
	$root = xml_createElement($xmlDoc, $xmlDoc, "bests");
	$aK = array_keys($array);
	for ($i=0;$i<=count($aK)-1;$i++){
		$element_mes = xml_createElement($xmlDoc, $root["element"], "mes");
		$element_m_str = xml_createElement($xmlDoc, $element_mes["element"], "mes_s", strtolower($aK[$i]));
		$element_lista = xml_createElement($xmlDoc, $element_mes["element"], "lista");
		$iK = array_keys($array[$aK[$i]]);
		for ($j=0;$j<=count($iK)-1;$j++){
			$element_articulo = xml_createElement($xmlDoc, $element_lista["element"], "articulo");
			$element_id = xml_createElement($xmlDoc, $element_articulo["element"], "id", $iK[$j]);
			$element_cantidad = xml_createElement($xmlDoc, $element_articulo["element"], "cantidad", $array[$aK[$i]][$iK[$j]]);
			}
		}
	$f_path .= "/" . strval($y) . ".xml";
	if (file_exists($f_path) == true) unlink($f_path);
	$xmlDoc->formatOutput = true;
	$xmlDoc->save($f_path);
	}

if (isset($_GET["action"]) == true) switch ($_GET["action"]){
	case "drawTable":
		$meses_a = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
		?>
		<table style="width:100%;">
		<?php
		for ($k=0;$k<=3;$k++){
		?>
		<tr>
			<?php
			for ($i=($k*3);$i<=(2+($k*3));$i++) echo "<th>" . $meses_a[$i] . "</th>\n";
			?>
			</tr>
		<tr style="text-align:center;">
			<form>
			<?php
			for ($i=($k*3);$i<=(2+($k*3));$i++) echo "<td><input id=\"button_" . $meses_a[$i] . "\" type=\"button\" value=\"Calcular\"/></td>\n";
			?>
			</form>
			</tr>
		
		<?php
			}
			?>
		</table>
		<?php
		break;
	case "generate":
		$meses_a = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
		$g_suc = "el_dorado";
		$g_y = 2013;
		$g_m = 11;
		$m_list = bests_loadFile($g_suc, $g_y);
		$m_list[$meses_a[$g_m - 1]] = bests_getMonthList($g_suc, $g_m, $g_y);
		bests_saveFile($g_suc, $g_y, $m_list);
		for ($i=0;$i<=11;$i++){
			if (array_key_exists($meses_a[$i], $m_list) == true){
				$aK = array_keys($m_list[$meses_a[$i]]);
				echo "<h2>" . $meses_a[$i] . "</h2><ol>";
				for ($j=0;$j<=count($m_list[$meses_a[$i]])-1;$j++){
					echo "<li>" . $m_list[$meses_a[$i]][$aK[$j]] . " - " . $aK[$j] . "</li>";
					}
				echo "</ol><br/><br/>";
				}
			}
		break;
	}

?>