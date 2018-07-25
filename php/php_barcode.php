<?php
	switch($_GET["action"]){
		case "regArt":
			$invPath = "../xml/".$_GET["suc"]."/inventarios/".$_GET["date"].".xml";
			if (file_exists($invPath) == true) {
				$invXML = simplexml_load_file($invPath);
				$sub = $invXML->xpath("mapeo/seccion/sub[1]")[0];
				}
			else {
				$invXML = new SimpleXMLElement("<inventario/>");
				$mapeo = $invXML->addChild("mapeo");
				$seccion = $mapeo->addChild("seccion");
				$seccion->addChild("id", "tienda");
				$seccion->addChild("name", "Tienda");
				$sub = $seccion->addChild("sub");
				$sub->addChild("id", "toda");
				$sub->addChild("name", "Toda");
				}
			if (count($sub->xpath("articulo[id='".$_GET["id"]."']")) != 0){
				$sub->xpath("articulo[id='".$_GET["id"]."']")[0]->cantidad += 1;
				}
			else {
				$articulo = $sub->addChild("articulo");
				$articulo->addChild("id", $_GET["id"]);
				$articulo->addChild("cantidad", "1");
				}
			echo "<span style='font-weight:bold;'>".$sub->xpath("articulo[id='".$_GET["id"]."']")[0]->cantidad."</span> - ".$_GET["id"]."<br><br>";
			$invXML->asXML($invPath);
			
			$listPath = "../xml/".$_GET["suc"]."/listas/".$_GET["date"].".xml";
			if (file_exists($listPath) == true) $listXML = simplexml_load_file($listPath);
			else $listXML = new SimpleXMLElement("<lista/>");
			if (count($listXML->xpath("articulo[id='".$_GET["id"]."']")) == 0) {
				$articulo = $listXML->addChild("articulo");
				$articulo->addChild("id", $_GET["id"]);
				$articulo->addChild("name", $_GET["id"]);
				$articulo->addChild("precio", "0");
				$listXML->asXML($listPath);
				echo "<span style='font-weight:bold'>".$_GET["id"]."</span> registrado en lista";
				}
			else echo "";
		break;
	}
?>