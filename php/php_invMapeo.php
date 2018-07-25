<?php
require("php_xmlDOM.php");
require("php_general.php");

switch($_GET["action"]){
	case "show_mC":
		?>
		<center>
		Mapeo de Inventario
		</center>
		<br/>
		<form>
		<center>
		<input type="button" value="Contar Mapeo y Ordenar Lista" onclick="map_contabilizar()"/> 
		</center>
		<br/>
		<div id="div_display"></div><br/>
		<div id="div_capturar"></div><br/>
		</form>
		<?php
	break;
	case "display":
		$invXML = xml_loadInvFile($_GET["suc"], $_GET["date"]);
		if (count($invXML["mapeo"]) > 0){
			?>
			<table width="600" align="center">
			<tr><td><ul>
			<?php
			for ($i=0;$i<=count($invXML["mapeo"])-1;$i++){
				?>
				<li>
				<a href="javascript:map_modifySectSub('<?php echo $i;?>', '-1', 'del')">
				<img src="../jpg/x.jpg" style="vertical-align:-2px" width="15" height="15" title="Eliminar"/></a> - 
				<a href="javascript:map_modifySectSub('<?php echo $i;?>', '-1', 'cn')">
				<img src="../jpg/cn.jpg" style="vertical-align:-2px" width="15" height="15" title="Cambiar nombre"/></a> - 
				<a href="javascript:map_showHideSection('<?php echo $i;?>')">Secci&oacute;n: <?php echo $invXML["mapeo"][$i][1];?></a>
				</li>
				<span id="sect_<?php echo $i;?>"></span>
				<?php
				}
			?>
			</ul></td></tr>
			</table>
			<?php
			}
	break;
	case "captSect":
		?>
		<table width="400" align="center">
		<tr><td>
		Nueva Secci&oacute;n:<br/>
		Nombre: <input type="text" id="text_sectName"/> 
		<input type="button" value="Aceptar" onclick="map_crearSeccion()"/>
		</td></tr>
		</table>
		<?php
	break;
	case "createSect":
		$invXML = xml_loadInvFile($_GET["suc"], $_GET["date"]);
		$sect_number = count($invXML["mapeo"]);
		$invXML["mapeo"][$sect_number] = array();
		$invXML["mapeo"][$sect_number][0] = gen_createId($_GET["name"]);
		$invXML["mapeo"][$sect_number][1] = utf8_encode($_GET["name"]);
		xml_saveInvFile($invXML, $_GET["suc"], $_GET["date"]);
	break;
	case "showSect":
		$invXML = xml_loadInvFile($_GET["suc"], $_GET["date"]);
		$sect = intval($_GET["sect"]);
		if (count($invXML["mapeo"][$sect]) > 2){
			?>
			<ul>
			<?php
			for ($i=2;$i<=count($invXML["mapeo"][$sect])-1;$i++){
				?>
				<li>
				<a href="javascript:map_modifySectSub('<?php echo $_GET["sect"];?>', '<?php echo $i;?>', 'del')">
				<img src="../jpg/x.jpg" style="vertical-align:-2px" width="15" height="15" title="Eliminar"/></a> - 
				<a href="javascript:map_modifySectSub('<?php echo $_GET["sect"];?>', '<?php echo $i;?>', 'cn')">
				<img src="../jpg/cn.jpg" style="vertical-align:-2px" width="15" height="15" title="Cambiar nombre"/></a> - 
				<a href="javascript:map_showHideSub('<?php echo $_GET["sect"];?>', '<?php echo $i;?>')">
				<?php echo $invXML["mapeo"][$sect][$i][1];?></a>
				</li>
				<span id="<?php echo "sect" . $_GET["sect"] . "_sub" . $i;?>"></span>
				<?php
				}
			?>
			</ul>
			<?php
			}
		else {
			?>
			<ul><li>Secci&oacute;n Vac&iacute;a</li></ul>
			<?php
			}
		?>
		<br/>
		<span id="span_captSub"></span>
		<?php
	break;
	case "captSub":
		?>
		<table width="400" align="center">
		<tr><td>
		Nueva Subsecci&oacute;n:<br/>
		Nombre: <input type="text" id="text_subName"/> 
		<input type="button" value="Aceptar" onclick="map_crearSub('<?php echo $_GET["sect"];?>')"/>
		</td></tr>
		</table>
		<?php
	break;
	case "createSub":
		$invXML = xml_loadInvFile($_GET["suc"], $_GET["date"]);
		$sect_number = intval($_GET["sect"]);
		$sub_number = count($invXML["mapeo"][$sect_number]);
		$invXML["mapeo"][$sect_number][$sub_number] = array();
		$invXML["mapeo"][$sect_number][$sub_number][0] = gen_createId($_GET["name"]);
		$invXML["mapeo"][$sect_number][$sub_number][1] = utf8_encode($_GET["name"]);
		xml_saveInvFile($invXML, $_GET["suc"], $_GET["date"]);
	break;
	case "showSub":
		$listXML = xml_loadListFile($_GET["suc"], $_GET["date"]);
		$invXML = xml_loadInvFile($_GET["suc"], $_GET["date"]);
		$sect = intval($_GET["sect"]);
		$sub = intval($_GET["sub"]);
		if (count($invXML["mapeo"][$sect][$sub]) > 2){
			?>
			<ul>
			<?php
			for ($i=2;$i<=count($invXML["mapeo"][$sect][$sub])-1;$i++){
				?>
				<li>
				<a href="javascript:map_modifyReg('<?php echo $sect;?>', '<?php echo $sub;?>', '<?php echo $i;?>', 'del')">
				<img src="../jpg/x.jpg" style="vertical-align:-2px" width="15" height="15" title="Eliminar"/></a> - 
				<a href="javascript:map_modifyReg('<?php echo $sect;?>', '<?php echo $sub;?>', '<?php echo $i;?>', 'cn')">
				<img src="../jpg/cn.jpg" style="vertical-align:-2px" width="15" height="15" title="Cambiar nombre"/></a> - 
				<a href="javascript:list_display('inv', '<?php echo $sect;?>,<?php echo $sub;?>,<?php echo $i;?>')">
				<img style="vertical-align:-2px" width="15" height="15" src="../jpg/lt.jpg" title="Cambiar Art&iacute;culo"/></a> - 
				<a href="javascript:map_modifyReg('<?php echo $sect;?>', '<?php echo $sub;?>', '<?php echo $i;?>', 'cc')">
				<?php echo $invXML["mapeo"][$sect][$sub][$i]["cantidad"];?></a> - 
				<?php echo $listXML[$invXML["mapeo"][$sect][$sub][$i]["id"]]["name"];?>
				</li>
				<?php
				}
			?>
			</ul>
			<?php
			}
		else {
			?>
			<ul><li>Subsecci&oacute;n Vac&iacute;a</li></ul>
			<?php
			}
		?>
		<br/>
		<span id="span_captArt"></span>
		<?php
	break;
	case "captArt":
		?>
		<table width="400" align="center">
		<tr><td>
		Capturar Art&iacute;culo<br/>
		Cantidad: <input type="text" size="2" id="text_artCant"/> 
		<select id="select_artList" onchange="map_regArt('<?php echo $_GET["sect"];?>', '<?php echo $_GET["sub"];?>')">
		<option> - seleccione - </option>
		<?php xml_showSelectListOptions($_GET["suc"], $_GET["date"]);?>
		</select>
		<br/>
		Nombre: <input type="text" id="text_artName"/> 
		<input type="button" value="Aceptar" onclick="map_regArt('<?php echo $_GET["sect"];?>', '<?php echo $_GET["sub"];?>')"/>
		</td></tr>
		</table>
		<?php
	break;
	case "regArt":
		$artId = "";
			if ($_GET["new"] == "true") $artId = gen_createId($_GET["art"]);
			else $artId = $_GET["art"];
		$invXML = xml_loadInvFile($_GET["suc"], $_GET["date"]);
			$sect_number = intval($_GET["sect"]);
			$sub_number = intval($_GET["sub"]);
			$art_number = count($invXML["mapeo"][$sect_number][$sub_number]);
			$invXML["mapeo"][$sect_number][$sub_number][$art_number] = array();
			$invXML["mapeo"][$sect_number][$sub_number][$art_number]["id"] = $artId;
			$invXML["mapeo"][$sect_number][$sub_number][$art_number]["cantidad"] = $_GET["cant"];
			xml_saveInvFile($invXML, $_GET["suc"], $_GET["date"]);
		if ($_GET["new"] == "true"){
			$listXML = xml_loadListFile($_GET["suc"], $_GET["date"]);
				$listXML[$artId] = array("name" => utf8_encode($_GET["art"]), "precio" => 0);
				xml_saveListFile($listXML, $_GET["suc"], $_GET["date"]);
			}
	break;
	case "modifyReg":
		$invXML = xml_loadInvFile($_GET["suc"], $_GET["date"]);
		if ($_GET["modify"] != "del"){
			switch($_GET["modify"]){
				case "cc":
					$invXML["mapeo"][$_GET["sect"]][$_GET["sub"]][$_GET["art"]]["cantidad"] = intval($_GET["newData"]);
					xml_saveInvFile($invXML, $_GET["suc"], $_GET["date"]);
					break;
				case "cn":
					$artId = $invXML["mapeo"][$_GET["sect"]][$_GET["sub"]][$_GET["art"]]["id"];
					$listXML = xml_loadListFile($_GET["suc"], $_GET["date"]);
					$listXML[$artId]["name"] = utf8_encode($_GET["newData"]);
					xml_saveListFile($listXML, $_GET["suc"], $_GET["date"]);
					break;
				}
			}
		else {
			$tempSub = array();
			$k = 0;
			for ($i=0;$i<=count($invXML["mapeo"][intval($_GET["sect"])][intval($_GET["sub"])])-1;$i++){
				if ($i == intval($_GET["art"])) continue;
				else {
					$tempSub[$k] = $invXML["mapeo"][intval($_GET["sect"])][intval($_GET["sub"])][$i];
					$k++;
					}
				}
			$invXML["mapeo"][intval($_GET["sect"])][intval($_GET["sub"])] = $tempSub;
			xml_saveInvFile($invXML, $_GET["suc"], $_GET["date"]);
			}
	break;
	case "modifySectSub":
		$invXML = xml_loadInvFile($_GET["suc"], $_GET["date"]);
		if ($_GET["modify"] == "actionDel"){
			$tempSectSub = array();
			$k = 0;
			if ($_GET["sub"] != "-1"){
				for ($i=0;$i<=count($invXML["mapeo"][intval($_GET["sect"])])-1;$i++){
					if($i == intval($_GET["sub"])) continue;
					else {
						$tempSectSub[$k] = $invXML["mapeo"][intval($_GET["sect"])][$i];
						$k++;
						}
					}
				$invXML["mapeo"][intval($_GET["sect"])] = $tempSectSub;
				}
			else {
				for ($i=0;$i<=count($invXML["mapeo"])-1;$i++){
					if($i == intval($_GET["sect"])) continue;
					else {
						$tempSectSub[$k] = $invXML["mapeo"][$i];
						$k++;
						}
					}
				$invXML["mapeo"] = $tempSectSub;
				}
			}
		else {
			if ($_GET["sub"] == "-1") $invXML["mapeo"][intval($_GET["sect"])][1] = utf8_encode($_GET["modify"]);
			else $invXML["mapeo"][intval($_GET["sect"])][intval($_GET["sub"])][1] = utf8_encode($_GET["modify"]);
			}
		xml_saveInvFile($invXML, $_GET["suc"], $_GET["date"]);
	break;
	case "contabilizar":
		xml_ordenaLista($_GET["suc"], $_GET["date"]);
		$invXML = xml_loadInvFile($_GET["suc"], $_GET["date"]);
		$listXML = xml_loadListFile($_GET["suc"], $_GET["date"]);
		$lK = array_keys($listXML);
		//$listExist = false;
		//if (count($invXML["lista"]) > 0) $listExist = true;
		for ($i=0;$i<=count($lK)-1;$i++){
			//registrar el artículo en la lista (si no existe la lista)
			if (array_key_exists($lK[$i], $invXML["lista"]) == false){
				$invXML["lista"][$lK[$i]] = array();
				$invXML["lista"][$lK[$i]]["anterior"] = 0;
				$invXML["lista"][$lK[$i]]["entradas"] = 0;
				$invXML["lista"][$lK[$i]]["salidas"] = 0;
				$invXML["lista"][$lK[$i]]["existencia"] = 0;
				$invXML["lista"][$lK[$i]]["diferencia"] = 0;
				}
			//borrar el disponible
			$invXML["lista"][$lK[$i]]["disponible"] = 0;
			//contar en mapeo
			for ($j=0;$j<=count($invXML["mapeo"])-1;$j++){ //secciones
				for ($k=2;$k<=count($invXML["mapeo"][$j])-1;$k++){ //subsecciones
					for ($m=2;$m<=count($invXML["mapeo"][$j][$k])-1;$m++){ //artículos
						if ($invXML["mapeo"][$j][$k][$m]["id"] == $lK[$i]){
							$invXML["lista"][$lK[$i]]["disponible"] += $invXML["mapeo"][$j][$k][$m]["cantidad"];
							}
						}
					}
				}
			}
		//ordenar lista de inventario y guardar
		xml_saveInvFile($invXML, $_GET["suc"], $_GET["date"]);
		echo "Conteo y ordenado terminados";
	break;
	}
?>