<?php
require("php_xmlDOM.php");
require("php_general.php");

switch($_GET["action"]){
	case "build_artInfo":
	$invXML = xml_loadInvFile($_GET["suc"], $_GET["date"]);
	$listXML = xml_loadListFile($_GET["suc"], $_GET["date"]);
	$libXML = array();
		?>
		<!DOCTYPE html>
		<html>
		<head>
		<title id="doc_title">SIES - <?php echo $listXML[$_GET["art"]]["name"];?></title>
		<script type="text/javascript" src="../js/js_general.js"></script>
		<script type="text/javascript" src="../js/js_invMapeo.js"></script>
		<script type="text/javascript" src="../js/js_artInfo.js"></script>
		</head>
		<body>
		Sistema de Inventarios de Eclipse Shop v0.4
		<br/><br/>
		<form>
		<center>
		<br/>
		<input type="button" value="Ir a Mapeo" onclick="info_gotoMap('<?php echo $_GET["date"];?>')"/> 
		<input type="button" value="Regresar a Chequeo" onclick="info_regresar('<?php echo $_GET["date"];?>')"/>
		<br/><br/>
		Informaci&oacute;n de Art&iacute;culo: 
		<span style="color:blue;">
		<?php echo $listXML[$_GET["art"]]["name"];?>
		</span> - 
		$<?php echo $listXML[$_GET["art"]]["precio"];?>
		<br/><br/>
		</center>
		<table align="center" border="0">
		<tr>
		<td width="500" valign="top">
			<table width="100%">
			<tr><td>Libreta</td></tr>
			<?php
			$date = xml_prevInvDate($_GET["suc"], $_GET["date"]);
			//contar la hoja del día del inventario anterior
			$libXML = xml_loadLibFile($_GET["suc"], $date);
			$invFound = false;
			$ventas = 0; $entradas = 0; $salidas = 0;
			for ($i=0;$i<=count($libXML["ventas"])-1;$i++){
				if ($invFound == true){
					if ($libXML["ventas"][$i]["id"] == $_GET["art"]){
						$ventas++;
						}
					}
				else if ($libXML["ventas"][$i]["type"] == "inventario") $invFound = true;
				}
			if ($ventas > 0){
				?>
				<tr><td>
					<table><tr><td width="225">
					<a href="javascript:info_gotoLib('<?php echo $date;?>')">
					<?php echo gen_fullDate($date);?></a>:</td>
					<td><?php echo $ventas;?></td><td>ventas,</td>
					<td>0</td><td>entradas,</td>
					<td>0</td><td>salidas</td>
					</tr></table>
				</td></tr>
				<?php
				}
			//recorrer la libreta
			while (true){
				$ventas = 0; $entradas = 0; $salidas = 0;
				//control del while
				$dA = explode("-", $date);
				$date = date("Y-m-d", mktime(12, 0, 0, $dA[1], $dA[2], $dA[0]) + (24*3600));
				if ($date == $_GET["date"]) break;
				//ventas
				$libXML = xml_loadLibFile($_GET["suc"], $date);
				if (count($libXML["ventas"]) > 0) for ($i=0;$i<=count($libXML["ventas"])-1;$i++){
					if ($libXML["ventas"][$i]["id"] == $_GET["art"]){
						$ventas += 1;
						}
					}
				//entradas y salidas
				if (count($libXML["es"]["entradas"]) > 0) for ($i=0;$i<=count($libXML["es"]["entradas"])-1;$i++){
					if($libXML["es"]["entradas"][$i]["id"] == $_GET["art"]){
						$entradas += $libXML["es"]["entradas"][$i]["cantidad"];
						}
					}
				if (count($libXML["es"]["salidas"]) > 0) for ($i=0;$i<=count($libXML["es"]["salidas"])-1;$i++){
					if($libXML["es"]["salidas"][$i]["id"] == $_GET["art"]){
						$salidas += $libXML["es"]["salidas"][$i]["cantidad"];
						}
					}
				if ($ventas > 0 || $entradas > 0 || $salidas > 0){
					?>
					<tr><td>
						<table><tr><td width="225" align="right">
						<a href="javascript:info_gotoLib('<?php echo $date;?>')">
						<?php echo gen_fullDate($date);?></a>:</td>
						<td><?php echo $ventas;?></td><td>ventas,</td>
						<td><?php echo $entradas;?></td><td>entradas,</td>
						<td><?php echo $salidas;?></td><td>salidas</td>
						</tr></table>
					</td></tr>
					<?php
					}
				}
			//contar la hoja del día del inventario actual
			$ventas = 0;
			$libXML = xml_loadLibFile($_GET["suc"], $date);
			if (count($libXML["ventas"]) > 0) for ($i=0;$i<=count($libXML["ventas"])-1;$i++){
				if ($libXML["ventas"][$i]["id"] == $_GET["art"]){
					$ventas += 1;
					}
				else if ($libXML["ventas"][$i]["type"] == "inventario") break;
				}
			if ($ventas > 0){
				?>
				<tr><td>
					<table><tr><td width="225">
					<a href="javascript:info_gotoLib('<?php echo $date;?>')">
					<?php echo gen_fullDate($date);?></a>:</td>
					<td><?php echo $ventas;?></td><td>ventas,</td>
					<td>0</td><td>entradas,</td>
					<td>0</td><td>salidas</td>
					</tr></table>
				</td></tr>
				<?php
				}
			?>
			</table>
		</td>
		<td width="500" valign="top">
			<table width="100%">
			<tr><td>
			Mapeo
			</td></tr>
			<?php
			for ($i=0;$i<=count($invXML["mapeo"])-1;$i++){ //secciones
				for ($j=2;$j<=count($invXML["mapeo"][$i])-1;$j++){ //subsecciones
					for ($k=2;$k<=count($invXML["mapeo"][$i][$j])-1;$k++){ //artículos
						if ($invXML["mapeo"][$i][$j][$k]["id"] == $_GET["art"]){
							?>
							<tr>
							<td>
							<table><tr>
								<td width="35" align="center"><span  style="color:blue;"><?php echo $invXML["mapeo"][$i][$j][$k]["cantidad"];?></span></td>
								<td>en: <?php echo $invXML["mapeo"][$i][1] . " - " . $invXML["mapeo"][$i][$j][1];?></td>
							</tr></table>
							</td>
							</tr>
							<?php
							}
						}
					}
				}
			?>
			</table>
		</td>
		</tr>
		</table>
		<!--  elementos ocultos -->
		<input type="hidden" id="hidden_suc" value="<?php echo $_GET["suc"];?>"/>
		<input type="hidden" id="hidden_date" value="<?php echo $_GET["date"];?>"/>
		</form>
		</body>
		</html>
		<?php
		break;
	case "cambia_precio":
		$listXML = xml_loadListFile($_GET["suc"], $_GET["date"]);
		$listXML[$_GET["art"]]["precio"] = intval($_GET["precio"]);
		xml_saveListFile($listXML, $_GET["suc"]);
		echo $_GET["precio"];
		break;
	}
?>