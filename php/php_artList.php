<?php
require("php_xmlDOM.php");

switch ($_GET["action"]){
	case "show_list":
		?>
		<form>
		<br/>
		<center>
		Nuevo Art&iacute;culo:
		<br/>
		<select id="list_artList" onchange="list_changeArt('<?php echo $_GET["libinv"];?>', '<?php echo $_GET["inst"];?>')">
		<option> - seleccione - </option>
		<?php xml_showSelectListOptions($_GET["suc"]);?>
		</select>
		</center>
		</form>
		<?php
		break;
	case "make_libChange":
		$libXML = xml_loadLibFile($_GET["suc"], $_GET["date"]);
		$htr = explode(",", $_GET["inst"]);
		if ($htr[0] == "ventas") $libXML["ventas"][intval($htr[2])]["id"] = $_GET["art"];
		else $libXML["es"][$htr[1]][intval($htr[2])]["id"] = $_GET["art"];
		xml_saveLibFile($libXML, $_GET["suc"], $_GET["date"]);
		break;
	case "make_invChange":
		$invXML = xml_loadInvFile($_GET["suc"], $_GET["date"]);
		$htr = explode(",", $_GET["inst"]);
		$invXML["mapeo"][intval($htr[0])][intval($htr[1])][intval($htr[2])]["id"] = $_GET["art"];
		xml_saveInvFile($invXML, $_GET["suc"], $_GET["date"]);
		break;
	}
?>