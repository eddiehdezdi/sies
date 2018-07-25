<?php

require("php_xmlDOM.php");

switch($_GET["action"]){
	case "show_cc":
		?>
		<center>
		Diferencia en el Corte
		<br/><br/>
		<?php
		$libXML = xml_loadLibFile($_GET["suc"], $_GET["date"]);
		if ($libXML["cortes"] == 0){
			?>
			<form>
			Capturar
			<br/><br/>
			Tipo: 
			<select id="select_cType">
			<option> - seleccionar - </option>
			<option value="faltante">Faltante</option>
			<option value="sobrante">Sobrante</option>
			</select> - $
			<input type="text" id="text_cant" size="3"/>
			<br/><br/>
			<input type="button" value="Registrar" onclick="cortes_regCorte()"/>
			</form>
			<?php
			}
		else {
			$diferencia = ""; $cantidad = 0;
			if ($libXML["cortes"] < 0){
				$diferencia = "Faltante";
				$cantidad = -1 * $libXML["cortes"];
				}
			else {
				$diferencia = "Sobrante";
				$cantidad = $libXML["cortes"];
				}
			?>
			<a href="javascript:cortes_changeRow('del')">
			<img src="../jpg/x.jpg" width="15" height="15" style="vertical-align:-2px"/></a> - 
			<?php echo $diferencia;?> - 
			<a href="javascript:cortes_changeRow('cant')">
			$<?php echo $cantidad;?></a>
			</center>
			<?php
			}
		break;
	
	case "reg_corte":
		$libXML = xml_loadLibFile($_GET["suc"], $_GET["date"]);
		if ($_GET["type"] == "faltante") $libXML["cortes"] = -1 * intval($_GET["cant"]);
		else $libXML["cortes"] = intval($_GET["cant"]);
		xml_saveLibFile($libXML, $_GET["suc"], $_GET["date"]);
		break;
	
	case "mod_cortes":
		$libXML = xml_loadLibFile($_GET["suc"], $_GET["date"]);
		if ($_GET["type"] == "del") $libXML["cortes"] = 0;
		else {
			if ($libXML["cortes"] < 0) $libXML["cortes"] = -1 * intval($_GET["cant"]);
			else $libXML["cortes"] = intval($_GET["cant"]);
			}
		xml_saveLibFile($libXML, $_GET["suc"], $_GET["date"]);
		break;
	
	}
?>