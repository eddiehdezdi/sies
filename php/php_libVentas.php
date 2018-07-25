<?php
require("php_xmlDOM.php");
require("php_general.php");

function lV_calculaCorte($ventXML, $r){
	$corte = 0;
	for ($i=$r-1;$i>=0;$i--){
		$r_tipo = $ventXML[$i]["type"];
		if ($r_tipo == "articulo" || $r_tipo == "apartado") $corte += $ventXML[$i]["precio"];
		else if ($r_tipo == "corte") break;
		}
	return $corte;
	}

switch($_GET["action"]){
	case "show_vC":
		?>
		<center>
		Ventas del D&iacute;a
		<br/><br/>
		<input type="button" value="Recalcular Cortes" onclick="ventas_reCortes()"/>
		</center>
		<br/><br/>
		<div id="div_display"></div><br/>
		<div id="div_capturar"></div><br/>
		<?php
		break;
	case "display_sheet":
		$libXML = xml_loadLibFile($_GET["suc"], $_GET["date"]);
		if (count($libXML["ventas"]) > 0){
			$listXML = xml_loadListFile($_GET["suc"]);
			?>
			<table align="center">
			<?php
			$tdClass = "";
			for ($i=0;$i<=count($libXML["ventas"])-1;$i++){
				if ($i % 2 == 0) $tdClass = "blurbluebg";
				else $tdClass = "whitebg";
				$td1 = ""; $td2 = ""; $td_align = ""; $f_weight = "";
				switch($libXML["ventas"][$i]["type"]){
					case "articulo":
						if (isset($listXML[$libXML["ventas"][$i]["id"]]) == true){
							$td1 = "1 - " . $listXML[$libXML["ventas"][$i]["id"]]["name"];
							}
						else $td1 = "1 - id = " . $libXML["ventas"][$i]["id"];
						$td_align = "left";
						break;
					default:
						$td1 = $libXML["ventas"][$i]["id"];
						if ($td1 == "Inventario" || $td1 == "Corte") $td1 = strtoupper($td1);
						else $td1 = "Apartado - Abono";
						if ($libXML["ventas"][$i]["type"] == "apartado") $td_align = "left";
						else $td_align = "center";
						if ($libXML["ventas"][$i]["type"] == "corte") $f_weight = "bold";
						else $f_weight = "normal";
						break;
					}
				if ($libXML["ventas"][$i]["precio"] != -1) $td2 = "$" . strval($libXML["ventas"][$i]["precio"]);
				?>
				<tr>
				<td width="550" align="<?php echo $td_align;?>" class="<?php echo $tdClass;?>">
				<!-- concepto del renglón -->
				<a href="javascript:ventas_modReng('del','<?php echo $i;?>')">
				<img style="vertical-align:-2px" width="15" height="15" src="../jpg/x.jpg" title="Eliminar"/></a> - 
				<a href="javascript:ventas_modReng('up','<?php echo $i;?>')">
				<img style="vertical-align:-2px" src="../jpg/up.jpg" title="Mover Arriba"/></a> - 
				<a href="javascript:ventas_modReng('down','<?php echo $i;?>')">
				<img style="vertical-align:-2px" src="../jpg/dn.jpg" title="Mover Abajo"/></a> - 
				<a href="javascript:ventas_chName('<?php echo $libXML["ventas"][$i]["id"];?>', '<?php echo $libXML["ventas"][$i]["type"];?>')">
				<img style="vertical-align:-2px" width="15" height="15" src="../jpg/cn.jpg" title="Cambiar Nombre"/></a> - 
				<?php
				if ($libXML["ventas"][$i]["type"] == "articulo"){
					?>
					<a href="javascript:list_display('lib', 'ventas,hoja,<?php echo $i;?>')">
					<img style="vertical-align:-2px" width="15" height="15" src="../jpg/lt.jpg" title="Cambiar Art&iacute;culo"/></a> - 
					<?php
					}
				if ($td1 != "INVENTARIO") echo $td1;
				else {
					?>
					<a href="javascript:ventas_goToInventario()"><?php echo $td1;?></a>
					<?php
					}
				?>
				</td>
				<td width="80" class="<?php echo $tdClass;?>" style="font-weight:<?php echo $f_weight;?>">
				<!-- precio -->
					<?php 
					$r_tipo = $libXML["ventas"][$i]["type"];
					if($r_tipo == "articulo" || $r_tipo == "apartado"){
						?>
						<a href="javascript:ventas_cambiaPrecio('<?php echo $i;?>')">
						<?php 
						}
					echo $td2;
					if($r_tipo == "articulo" || $r_tipo == "apartado"){
						?>
						</a>
						<?php
						}
					if ($r_tipo == "articulo"){
						echo " - ";
						?>
						<a href="javascript:ventas_actPrecio('<?php echo $libXML["ventas"][$i]["id"];?>', '<?php echo $i;?>')">
						<img style="vertical-align:-2px" width="15" height="15" src="../jpg/rt.jpg" title="Actualiar Precio"/></a>
						<?php
						}
					?>
				</td>
				</tr>
				<?php
				}
			?>
			</table>
			<?php
			}
		break;
	case "captArt":
		?>
		<table width="400" align="center">
		<tr><td>
		Capturar Art&iacute;culo<br/>
		<select id="select_artList" onchange="ventas_regArt()">
		<option> - seleccione - </option>
		<option value="corte"> - Corte - </option>
		<option value="inventario"> - Inventario - </option>
		<option value="apartado"> - Apartado - </option>
		<?php xml_showSelectListOptions($_GET["suc"]);?>
		</select>
		</td></tr>
		</table>
		<?php
		break;
	case "regArt":
		$libXML = xml_loadLibFile($_GET["suc"], $_GET["date"]);
		$i = count($libXML["ventas"]);
		$libXML["ventas"][$i] = array();
		if ($_GET["regId"] != "corte" && $_GET["regId"] != "inventario" && $_GET["regId"] != "apartado"){
			$listXML = xml_loadListFile($_GET["suc"]);
			$libXML["ventas"][$i]["type"] = "articulo";
			$libXML["ventas"][$i]["id"] = $_GET["regId"];
			$libXML["ventas"][$i]["precio"] = $listXML[$_GET["regId"]]["precio"];
			}
		else { // <-- si no es artículo
			$libXML["ventas"][$i]["type"] = $_GET["regId"];
			if ($_GET["regId"] == "corte") $libXML["ventas"][$i]["precio"] = lV_calculaCorte($libXML["ventas"], $i);
			else {
				if ($_GET["regId"] == "apartado") $libXML["ventas"][$i]["precio"] = 0; // <-- si es apartado
				else $libXML["ventas"][$i]["precio"] = -1; // <-- si es inventario
				}
			switch($_GET["regId"]){
				case "corte": $libXML["ventas"][$i]["id"] = "Corte"; break;
				case "inventario": $libXML["ventas"][$i]["id"] = "Inventario"; break;
				case "apartado": $libXML["ventas"][$i]["id"] = "Apartado"; break;
				}
			}
		xml_saveLibFile($libXML, $_GET["suc"], $_GET["date"]);
		break;
	case "cambiaPrecio":
		$libXML = xml_loadLibFile($_GET["suc"], $_GET["date"]);
		$libXML["ventas"][intval($_GET["rn"])]["precio"] = intval($_GET["pr"]);
		xml_saveLibFile($libXML, $_GET["suc"], $_GET["date"]);
		break;
	case "recalcularCortes":
		$libXML = xml_loadLibFile($_GET["suc"], $_GET["date"]);
		for ($i=0;$i<=count($libXML["ventas"])-1;$i++){
			if ($libXML["ventas"][$i]["type"] == "corte"){
				$libXML["ventas"][$i]["precio"] = lV_calculaCorte($libXML["ventas"], $i);
				}
			}
		xml_saveLibFile($libXML, $_GET["suc"], $_GET["date"]);
		break;
	case "modReng":
		$libXML = xml_loadLibFile($_GET["suc"], $_GET["date"]);
		if ($_GET["sub_action"] == "del"){
			$newXML = array();
			$k = 0;
			for ($i=0;$i<=count($libXML["ventas"])-1;$i++){
				if ($i != intval($_GET["r"])){
					$newXML[$k] = $libXML["ventas"][$i];
					$k++;
					}
				}
			$libXML["ventas"] = $newXML;
			}
		else {
			$tempXML = $libXML["ventas"][intval($_GET["r"])];
			if ($_GET["sub_action"] == "up"){
				$libXML["ventas"][intval($_GET["r"])] = $libXML["ventas"][intval($_GET["r"])-1];
				$libXML["ventas"][intval($_GET["r"])-1] = $tempXML;
				}
			else {
				$libXML["ventas"][intval($_GET["r"])] = $libXML["ventas"][intval($_GET["r"])+1];
				$libXML["ventas"][intval($_GET["r"])+1] = $tempXML;
				}
			}
		xml_saveLibFile($libXML, $_GET["suc"], $_GET["date"]);
		break;
	case "crear_inventario":
		$invPath = "../xml/" . $_GET["suc"] . "/inventarios/" . $_GET["date"] . ".xml";
		if (file_exists($invPath) == false){
			//crear el archivo
			xml_createFile("inventarios", $_GET["suc"], $_GET["date"]);
			//registrar en inventarios.xml
			$invXMLPath = "../xml/" . $_GET["suc"] . "/inventarios.xml";
			if (file_exists($invXMLPath) == false) xml_createInventariosXML($_GET["suc"]);
			$invXMLList = xml_loadInventariosXML($_GET["suc"]);
			$n = count($invXMLList);
			$invXMLList[$n] = $_GET["date"];
			xml_saveInventariosXML($invXMLList, $_GET["suc"]);
			//guardar copia de lista en carpeta listas
			$listXML = xml_loadListFile($_GET["suc"]);
			xml_saveListFile($listXML, $_GET["suc"], $_GET["date"]);
			}
		break;
	case "actualiza_precio":
		$libXML = xml_loadLibFile($_GET["suc"], $_GET["date"]);
		$listXML = xml_loadListFile($_GET["suc"]);
		$listXML[$libXML["ventas"][$_GET["rn"]]["id"]]["precio"] = $libXML["ventas"][$_GET["rn"]]["precio"];
		xml_saveListFile($listXML, $_GET["suc"]);
		echo "Precio Actualizado";
		break;
	case "cambia_nombre":
		$listXML = xml_loadListFile($_GET["suc"]);
		$listXML[$_GET["art"]]["name"] = utf8_encode($_GET["new"]);
		xml_saveListFile($listXML, $_GET["suc"]);
		break;
	}
?>