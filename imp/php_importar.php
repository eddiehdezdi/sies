<?php
require("php_loadFunctions.php");

switch ($_GET["action"]){
	case "importar_list":
		$old_listXML = lF_loadListFile($_GET["suc"], $_GET["date"]);
		xml_saveListFile($old_listXML, $_GET["suc"]);
		break;
	case "importar_inv":
		$old_invXML = lF_loadInvFile($_GET["suc"], $_GET["date"]);
		xml_saveInvFile($old_invXML, $_GET["suc"], $_GET["date"]);
		break;
	case "importar_lib":
		$date = $_GET["inicia"];
		while (true){
			$old_libXML = lF_loadLibFile($_GET["suc"], $date);
			xml_saveLibFile($old_libXML, $_GET["suc"], $date);
			if ($date == $_GET["termina"]) break;
			else {
				$dA = explode("-", $date);
				$date = date("Y-m-d", mktime(12, 0, 0, $dA[1], $dA[2], $dA[0]) + (24*3600));
				}
			}
		break;
	}
?>