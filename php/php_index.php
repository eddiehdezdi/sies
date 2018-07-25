<?php
require("php_general.php");
require("php_xmlDOM.php");

switch ($_GET["action"]){
	case "show_sL":
		?>
		<select id="select_sucList" onchange="index_selectSL()">
		<option> - seleccionar - </option>
		<?php
		$sucList_array = xml_loadSucFile();
		for ($i=0; $i<=count($sucList_array)-1; $i++){
			?>
			<option value="<?php echo $sucList_array[$i]["id"]?>"><?php echo $sucList_array[$i]["name"]?></option>
			<?php
			}
		?>
		<option value="nueva"> - Nueva Sucursal - </option>
		</select>
		<?php
		break;
	case "new_suc":
		//registrar en sucursales.xml
		$sucList_array = xml_loadSucFile();
		$i = count($sucList_array);
		$sucList_array[$i]["id"] = gen_createId($_GET["suc"]);
		$sucList_array[$i]["name"] = utf8_encode($_GET["suc"]);
		xml_saveSucFile($sucList_array);
		//crear archivos y carpetas de sucursal
		gen_createFolderFiles($sucList_array[$i]["id"]);
		//devolver número de índice del select_sucList
		echo ($i + 1);
		break;
	case "get_today":
		echo date("Y-m-d");
		break;
	case "set_fullDate":
		echo gen_fullDate($_GET["date"]);
		break;
	case "arrow":
		$uDate = explode("-", $_GET["date"]);
		$tS = mktime(0, 0, 0, $uDate[1], $uDate[2], $uDate[0]);
		if ($_GET["dir"] == "prev") echo date("Y-m-d", $tS - (24*3600));
		else echo date("Y-m-d", $tS + (24*3600));
		break;
	case "show_subMenu":
		$display = array();
		if ($_GET["show"] == "lib") $display = array("Ventas", "Entradas / Salidas", "Cortes");
		else $display = array("Mapeo", "Chequeo", "Reporte");
		?>
		<table width="75%" align="center">
			<tr align="center">
				<?php
				for ($i=0;$i<=2;$i++){
				?>
				<td width="33%"><a href="javascript:index_setSubMenu('<?php echo $i;?>')"><?php echo $display[$i];?></a></td>
				<?php
				}
				?>
			</tr>
		</table>
		<?php
		break;
	case "exists_reg":
		$resp = "";
		if ($_GET["type"] == "lib"){
			if(file_exists("../xml/" . $_GET["suc"] . "/libreta/" . $_GET["date"] . ".xml") == true) $resp = "true";
			else $resp = false;
			}
		else {
			if(file_exists("../xml/" . $_GET["suc"] . "/inventarios/" . $_GET["date"] . ".xml") == true) $resp = "true";
			else $resp = false;
			}
		echo $resp;
		break;
	case "show_createButton":
		?>
		<form>
		<center>
		<input type="button" value="Crear Hoja" onclick="index_crearHoja()"/>
		</center>
		</form>
		<?php
		break;
	case "crear_hojaLib":
		xml_createFile("libreta", $_GET["suc"], $_GET["date"]);
		break;
	case "ordena_tiendas":
		$tiendasXML = xml_loadTiendasFile();
		ksort($tiendasXML);
		xml_saveTiendasFile($tiendasXML);
		break;
	}

?>