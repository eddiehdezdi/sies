<?php
require("php_xmlDOM.php");
require("php_general.php");

switch($_GET["action"]){
	case "show_EScont":
		?>
		<center>
		Entradas y Salidas del D&iacute;a
		<br/><br/>
		</center>
		<table width="800" align="center">
		<tr><td width="50%" align="center">Entradas</td><td width="50%" align="center">Salidas</td></tr>
		</table>
		<br/>
		<div id="div_esDisplay"></div>
		<br/><br/>
		<table align="center">
		<tr valign="top">
			<td id="td_newEntrada">
			<table align="center" width="400">
			<tr><td align="center">
			<form id="form_newEntrada">
			Entrada: <input type="text" size="2" id="text_newEntradaCant"/> 
			<select id="select_newEntrada" onchange="es_regNewEntrada('old')">
			<option> - seleccionar - </option>
			<?php  xml_showSelectListOptions($_GET["suc"]);?>
			</select><br/><br/>
			Nombre: <input type="text" id="text_newEntradaName"/> 
			<input type="button" value="Aceptar" onclick="es_regNewEntrada('new')"/>
			</form>
			</td></tr>
			</table>
		</td>
		<td id="td_newSalida">
			<table align="center" width="400">
			<tr><td align="center">
			<form id="form_newSalida">
			Salida: <input type="text" size="2" id="text_newSalidaCant"/> 
			<select id="select_newSalida" onchange="es_regNewSalida()">
			<option> - seleccionar - </option>
			<?php  xml_showSelectListOptions($_GET["suc"]);?>
			</select>
			</form>
			</td></tr>
			</table>
		</td></tr>
		</table>
		<br/><br/>
		<?php
		break;
	case "show_display":
		$libXML = xml_loadLibFile($_GET["suc"], $_GET["date"]);
		$listXML = xml_loadListFile($_GET["suc"]);
		?>
		<table align="center">
		<tr valign="top">
			<td width="400"><table width="100%">
			<?php
			for ($i=0;$i<=count($libXML["es"]["entradas"])-1;$i++){
				?>
				<tr>
				<td width="20%" align="right">
				<a href="javascript:es_changeRow('entradas', 'del', '<?php echo $i;?>', 'id')">
				<img src="../jpg/x.jpg" width="15" height="15" style="vertical-align:-2px"/></a> - 
				<a href="javascript:es_changeRow('entradas', 'cantidad', '<?php echo $i;?>', 'id')">
				<?php echo $libXML["es"]["entradas"][$i]["cantidad"];?></a> - 
				</td>
				<td width="60%">
				<?php
				if (isset($listXML[$libXML["es"]["entradas"][$i]["id"]]) == TRUE){
					echo $listXML[$libXML["es"]["entradas"][$i]["id"]]["name"];
					}
				else echo $libXML["es"]["entradas"][$i]["id"];
				?>
				</td>
				<td width="20%">
				<a href="javascript:es_changeRow('entradas', 'precio', '<?php echo $i;?>', '<?php echo $libXML["es"]["entradas"][$i]["id"];?>')">
				$<?php
				if (isset($listXML[$libXML["es"]["entradas"][$i]["id"]]) == TRUE){
					echo $listXML[$libXML["es"]["entradas"][$i]["id"]]["precio"];
					}
				else echo " -";
				?>
				</a>
				</td>
				</tr>
				<?php
				}
			?>
			</table></td>
			<td width="400"><table width="100%">
			<?php
			for ($i=0;$i<=count($libXML["es"]["salidas"])-1;$i++){
				?>
				<tr>
				<td width="20%" align="right">
				<a href="javascript:es_changeRow('salidas', 'del', '<?php echo $i;?>', 'id')">
				<img src="../jpg/x.jpg"/ width="15" height="15"></a> - 
				<a href="javascript:es_changeRow('salidas', 'cantidad', '<?php echo $i;?>', 'id')">
				<?php echo $libXML["es"]["salidas"][$i]["cantidad"];?></a> - 
				</td>
				<td width="80%">
				<?php
				if (isset($listXML[$libXML["es"]["salidas"][$i]["id"]]) == TRUE){
					echo $listXML[$libXML["es"]["salidas"][$i]["id"]]["name"];
					}
				else echo $libXML["es"]["salidas"][$i]["id"];
				?>
				</td>
				</tr>
				<?php
				}
			?>
			</table></td>
		</tr>
		</table>
		<?php
		break;
	case "reg_newEntrada":
		$artId = "";
		$libXML = xml_loadLibFile($_GET["suc"], $_GET["date"]);
		if ($_GET["type"] == "new") {
			$artId = gen_createId($_GET["art"]);
			xml_newListArt($_GET["suc"], utf8_encode($_GET["art"]), $artId);
			xml_ordenaLista($_GET["suc"]);
			}
		else $artId = $_GET["art"];
		$i = count($libXML["es"]["entradas"]);
		$libXML["es"]["entradas"][$i] = array();
		$libXML["es"]["entradas"][$i]["id"] = $artId;
		$libXML["es"]["entradas"][$i]["cantidad"] = intval($_GET["cant"]);
		xml_saveLibFile($libXML, $_GET["suc"], $_GET["date"]);
		break;
	case "reg_newSalida":
		$libXML = xml_loadLibFile($_GET["suc"], $_GET["date"]);
		$i = count($libXML["es"]["salidas"]);
		$libXML["es"]["salidas"][$i] = array();
		$libXML["es"]["salidas"][$i]["id"] = $_GET["art"];
		$libXML["es"]["salidas"][$i]["cantidad"] = intval($_GET["cant"]);
		xml_saveLibFile($libXML, $_GET["suc"], $_GET["date"]);
		break;
	case "change_row":
		if ($_GET["rowAction"] == "precio"){
			$listXML = xml_loadListFile($_GET["suc"]);
			$listXML[$_GET["id"]]["precio"] = intval($_GET["precio"]);
			xml_saveListFile($listXML, $_GET["suc"]);
			}
		else {
			$libXML = xml_loadLibFile($_GET["suc"], $_GET["date"]);
			switch($_GET["rowAction"]){
				case "cantidad":
					$libXML["es"][$_GET["es"]][$_GET["row"]]["cantidad"] = intval($_GET["cant"]);
					break;
				case "del":
					$newLib = array();
					$newLib["ventas"] = $libXML["ventas"];
					$newLib["cortes"] = $libXML["cortes"];
					$newLib["es"] = array();
					if ($_GET["es"] == "salidas"){
						$newLib["es"]["entradas"] = $libXML["es"]["entradas"];
						$newLib["es"]["salidas"] = array();
						}
					else {
						$newLib["es"]["salidas"] = $libXML["es"]["salidas"];
						$newLib["es"]["entradas"] = array();
						}
					$k = 0;
					for ($i=0;$i<=count($libXML["es"][$_GET["es"]])-1;$i++){
						if ($i != intval($_GET["row"])){
							$newLib["es"][$_GET["es"]][$k] = $libXML["es"][$_GET["es"]][$i];
							$k++;
							}
						}
					$libXML = $newLib;
					break;
				}
			xml_saveLibFile($libXML, $_GET["suc"], $_GET["date"]);
			}
		break;
	}
?>