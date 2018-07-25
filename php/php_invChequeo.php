<?php
require("php_xmlDOM.php");
require("php_general.php");

switch($_GET["action"]){
	case "show_chkCont":
		?>
		<center>
		Chequeo de Inventario
		</center>
		<br/>
		<form>
		<table align="center"><!-- botones -->
			<tr valign="middle">
				<td width="120" align="center">Inventario<br/>Anterior</td>
				<td width="120" align="center">Entradas y<br/>Salidas</td>
				<td width="120" align="center">Existencias</td>
				<td width="120" align="center">Diferencias</td>
			</tr>
			<tr>
				<td align="center"><input type="button" value="Cargar" onclick="chk_loadPrevInv()"/></td>
				<td align="center"><input type="button" value="Contar" onclick="chk_contarES()"/></td>
				<td align="center"><input type="button" value="Calcular" onclick="chk_calcExist()"/></td>
				<td align="center"><input type="button" value="Calcular" onclick="chk_calcDif()"/></td>
			</tr>
		</table>
		<br/><br/>
		<?php
		$listXML = xml_loadListFile($_GET["suc"], $_GET["date"]);
		?>
		<table align="center">
		<tr>
			<th width="440" class="bluebg">Ar&iacute;tculo</td>
			<th width="60" align="center" class="bluebg">Anterior</td>
			<th width="60" align="center" class="bluebg">Entradas</td>
			<th width="60" align="center" class="bluebg">Salidas</td>
			<th width="60" align="center" class="bluebg">Existencia</td>
			<th width="60" align="center" class="bluebg">Diferencia</td>
			<th width="60" align="center" class="bluebg">Disponible</td>
		</tr>
		<?php
		$aK = array_keys($listXML);
		for ($i=0;$i<=count($aK)-1;$i++){
			$css_class = "blurbluebg";
			if ($i % 2 == 0) $css_class = "whitebg";
			?>
			<tr>
			<td class="<?php echo $css_class;?>">
				<table><tr><td width="360">
				<span id="<?php echo "span_" . $aK[$i] . "_name";?>">
				<a href="javascript:chk_showArtInfo('<?php echo $aK[$i];?>')">
				<?php echo $listXML[$aK[$i]]["name"];?></a></span></td>
				<td><a href="javascript:chk_modifPrecio('<?php echo $aK[$i];?>')">
				$<span id="<?php echo "span_" . $aK[$i] . "_precio";?>"><?php echo $listXML[$aK[$i]]["precio"];?>
				</span></a></td></tr></table>
				</td>
			<!-- Nomenclatura de las celdas (ejemplo): span_lentes_disponible -->
			<td align="center" class="<?php echo $css_class;?>"><span id="<?php echo "span_" . $aK[$i] . "_anterior";?>"></span></td>
			<td align="center" class="<?php echo $css_class;?>"><span id="<?php echo "span_" . $aK[$i] . "_entradas";?>"></span></td>
			<td align="center" class="<?php echo $css_class;?>"><span id="<?php echo "span_" . $aK[$i] . "_salidas";?>"></span></td>
			<td align="center" class="<?php echo $css_class;?>"><span id="<?php echo "span_" . $aK[$i] . "_existencia";?>"></span></td>
			<td align="center" class="<?php echo $css_class;?>"><span id="<?php echo "span_" . $aK[$i] . "_diferencia";?>"></span></td>
			<td align="center" class="<?php echo $css_class;?>"><span id="<?php echo "span_" . $aK[$i] . "_disponible";?>"></span></td>
			</tr>
			<?php
			}
		?>
		</table>
		<br/><br/>
		<center>
		<input type="button" value="Iniciar Ajustes" onclick="chk_realizarAjustes()"/> 
		<input type="button" value="Mostrar Ajustes" onclick="chk_mostrarAjustes()"/>
		</center>
		<br/>
		<div id="div_chkAjustes"></div>
		</form>
		<?php
		break;
	case "get_cantStr":
		$invXML = xml_loadInvFile($_GET["suc"], $_GET["date"]);
		$aK = array_keys($invXML["lista"]);
		$cantStr = "";
		for ($i=0;$i<=count($aK)-2;$i++){ // <-- pendiente por chechar!!! por qué -2 ????????
			$cantStr .= $aK[$i] . ":" . $invXML["lista"][$aK[$i]][$_GET["cant"]];
			if ($i != count($aK)-2) $cantStr .= ";";
			}
		echo $cantStr;
		//lentes:10;lentes_nino:2 (el número depende de la columna, en este caso 10 y 2 podrían ser las salidas)
		break;
	case "get_allCantStr":
		$invXML = xml_loadInvFile($_GET["suc"], $_GET["date"]);
		$aK = array_keys($invXML["lista"]);
		$cantStr = "";
		$cK = array("anterior", "entradas", "salidas", "existencia", "diferencia", "disponible");
		for ($i=0;$i<=count($aK)-1;$i++){
			$cantStr .= $aK[$i] . ":";
			for ($j=0;$j<=count($cK)-1;$j++){
				$cantStr .= $invXML["lista"][$aK[$i]][$cK[$j]];
				if ($j != count($cK)-1) $cantStr .= ",";
				}
			if ($i != count($aK)-1) $cantStr .= ";";
			}
		echo $cantStr;
		//id:ant,ent,sal,exist,dif,disp;id: ...
		break;
	case "load_prevInv":
		$prevInv = xml_loadInvFile($_GET["suc"], xml_prevInvDate($_GET["suc"], $_GET["date"]));
		$invXML = xml_loadInvFile($_GET["suc"], $_GET["date"]);
		$pK = array_keys($prevInv["lista"]);
		for ($i=0;$i<=count($pK)-1;$i++){
			$invXML["lista"][$pK[$i]]["anterior"] = $prevInv["lista"][$pK[$i]]["disponible"];
			}
		xml_saveInvFile($invXML, $_GET["suc"], $_GET["date"]);
		break;
	case "contar_es":
		$invXML = xml_loadInvFile($_GET["suc"], $_GET["date"]);
		$date = xml_prevInvDate($_GET["suc"], $_GET["date"]);
		//borrar las entradas y salidas
		$lK = array_keys($invXML["lista"]);
		for ($i=0;$i<=count($lK)-1;$i++){
			$invXML["lista"][$lK[$i]]["entradas"] = 0;
			$invXML["lista"][$lK[$i]]["salidas"] = 0;
			}
		//borrar los cortes
		$invXML["totales"]["cortes"] = 0;
		//contar la hoja del día del inventario anterior
		$libXML = xml_loadLibFile($_GET["suc"], $date);
		$invFound = false;
		for ($i=0;$i<=count($libXML["ventas"])-1;$i++){
			if ($invFound == true){
				if ($libXML["ventas"][$i]["type"] == "articulo"){
					$invXML["lista"][$libXML["ventas"][$i]["id"]]["salidas"] += 1;
					}
				}
			else if ($libXML["ventas"][$i]["type"] == "inventario") $invFound = true;
			}
		//recorrer la libreta
		while (true){
			//control del while
			$dA = explode("-", $date);
			$date = date("Y-m-d", mktime(12, 0, 0, $dA[1], $dA[2], $dA[0]) + (24*3600));
			if ($date == $_GET["date"]) break;
			//ventas
			$libXML = xml_loadLibFile($_GET["suc"], $date);
			if (count($libXML["ventas"]) > 0) for ($i=0;$i<=count($libXML["ventas"])-1;$i++){
				if ($libXML["ventas"][$i]["type"] == "articulo"){
					$invXML["lista"][$libXML["ventas"][$i]["id"]]["salidas"] += 1;
					}
				}
			//entradas y salidas
			if (count($libXML["es"]["entradas"]) > 0) for ($i=0;$i<=count($libXML["es"]["entradas"])-1;$i++){
				$invXML["lista"][$libXML["es"]["entradas"][$i]["id"]]["entradas"] += $libXML["es"]["entradas"][$i]["cantidad"];
				}
			if (count($libXML["es"]["salidas"]) > 0) for ($i=0;$i<=count($libXML["es"]["salidas"])-1;$i++){
				$invXML["lista"][$libXML["es"]["salidas"][$i]["id"]]["salidas"] += $libXML["es"]["salidas"][$i]["cantidad"];
				}
			//cortes
			$invXML["totales"]["cortes"] += $libXML["cortes"];
			}
		//contar la hoja del día del inventario actual
		$libXML = xml_loadLibFile($_GET["suc"], $date);
			if (count($libXML["ventas"]) > 0) for ($i=0;$i<=count($libXML["ventas"])-1;$i++){
				if ($libXML["ventas"][$i]["type"] == "articulo"){
					$invXML["lista"][$libXML["ventas"][$i]["id"]]["salidas"] += 1;
					}
				else if ($libXML["ventas"][$i]["type"] == "inventario") break;
				}
		xml_saveInvFile($invXML, $_GET["suc"], $_GET["date"]);
		break;
	case "calcular_exist":
		$invXML = xml_loadInvFile($_GET["suc"], $_GET["date"]);
		$lK = array_keys($invXML["lista"]);
		for ($i=0;$i<=count($lK)-1;$i++){
			$invXML["lista"][$lK[$i]]["existencia"] = $invXML["lista"][$lK[$i]]["anterior"] + $invXML["lista"][$lK[$i]]["entradas"] - $invXML["lista"][$lK[$i]]["salidas"];
			}
		xml_saveInvFile($invXML, $_GET["suc"], $_GET["date"]);
		break;
	case "calcular_dif":
		$invXML = xml_loadInvFile($_GET["suc"], $_GET["date"]);
		$lK = array_keys($invXML["lista"]);
		for ($i=0;$i<=count($lK)-1;$i++){
			$invXML["lista"][$lK[$i]]["diferencia"] = $invXML["lista"][$lK[$i]]["disponible"] - $invXML["lista"][$lK[$i]]["existencia"];
			}
		xml_saveInvFile($invXML, $_GET["suc"], $_GET["date"]);
		break;
	case "make_ajustes":
		$invXML = xml_loadInvFile($_GET["suc"], $_GET["date"]);
		$invXML["faltantes"] = array();
		$lK = array_keys($invXML["lista"]);
		$k = 0;
		$sobrantes = array(); $s = 0;
		for ($i=0;$i<=count($lK)-1;$i++){
			if ($invXML["lista"][$lK[$i]]["diferencia"] < 0){
				$invXML["faltantes"][$k] = array();
				$invXML["faltantes"][$k]["id"] = $lK[$i];
				$invXML["faltantes"][$k]["cantidad"] = -1 * $invXML["lista"][$lK[$i]]["diferencia"];
				$invXML["faltantes"][$k]["compensan"] = array();
				$k++;
				}
			}
		xml_saveInvFile($invXML, $_GET["suc"], $_GET["date"]);
		break;
	case "show_ajustes":
		$invXML = xml_loadInvFile($_GET["suc"], $_GET["date"]);
		if(count($invXML["faltantes"]) > 0){
			$listXML = xml_loadListFile($_GET["suc"], $_GET["date"]);
			$sobrantes = array(); $s = 0;
			$lK = array_keys($invXML["lista"]);
			for ($i=0;$i<=count($lK)-1;$i++){
				if ($invXML["lista"][$lK[$i]]["diferencia"] > 0){
					$sobrantes[$s] = array();
					$sobrantes[$s]["id"] = $lK[$i];
					$sobrantes[$s]["cantidad"] = $invXML["lista"][$lK[$i]]["diferencia"];
					$s++;
					}
				}
			?>
			<table align="center">
			<tr><td>
			<table width="100%" align="center"><tr><td class="bluebg">
			<center>Compensar Faltantes con Sobrantes</center>
			</td></tr></table>
			<table> <!-- tabla de faltantes y sobrantes que compensan -->
			<?php
			$r = 0;
			for ($i=0;$i<=count($invXML["faltantes"])-1;$i++){
			$css_class = "whitebg";
			if ($r % 2 == 0) $css_class = "blurbluebg";
				?>
				<tr class="<?php echo $css_class;?>">
				<!-- mostrar nombre del artículo faltante -->
				<td width="440">
				<?php echo $invXML["faltantes"][$i]["cantidad"];?> - 
				<?php echo $listXML[$invXML["faltantes"][$i]["id"]]["name"];?> 
				($<?php echo $listXML[$invXML["faltantes"][$i]["id"]]["precio"];?>)
				<?php
				if (count($sobrantes) > 0){
					?>
					<!-- mostrar "compensar con:" -->
					</td><td width="120" align="center">
					compensar con:
					<!-- mostrar el select de los sobrantes -->
					</td><td width="440">
					<input type="text" id="text_comp_<?php echo $i;?>" size="2"/>
					<select id="select_comp_<?php echo $i;?>" onchange="chk_regCompensa('<?php echo $i;?>')">
					<option> - seleccionar - </option>
					<?php
					for ($k=0;$k<=count($sobrantes)-1;$k++){
						?>
						<option value="<?php echo $sobrantes[$k]["id"];?>">
						<?php echo $listXML[$sobrantes[$k]["id"]]["name"];?> - 
						(<?php echo $sobrantes[$k]["cantidad"];?>)
						</option>
						<?php
						}
					?>
					</select></td></tr>
					<?php
					}
				$r++;
				if (count($invXML["faltantes"][$i]["compensan"]) > 0){
					$css_class = "whitebg";
					if ($r % 2 == 0) $css_class = "blurbluebg";
					for ($j=0;$j<=count($invXML["faltantes"][$i]["compensan"])-1;$j++){
						?>
						<tr class="<?php echo $css_class;?>">
						<!-- mostrar los sobrantes que compensan al artículo -->
						<td>
						<a href="javascript:chk_delCompensa('<?php echo $i;?>', '<?php echo $j;?>')">
						<img src="../jpg/x.jpg" style="vertical-align:-2px" width="15" height="15" title="Eliminar"/></a> - 
						<?php echo $invXML["faltantes"][$i]["compensan"][$j]["cantidad"];?> - 
						<?php echo $listXML[$invXML["faltantes"][$i]["compensan"][$j]["id"]]["name"];?> 
						($<?php echo $listXML[$invXML["faltantes"][$i]["compensan"][$j]["id"]]["precio"];?>)
						</td><td></td><td></td></tr>
						<?php
						$r++;
						}
					}
				}
			?>
			</table>
			</td></tr>
			</table>
			<?php
			}
		else {
			?>
			<center>No hay faltantes que compensar</center>
			<?php
			}
		?>
		<form>
		<br/><br(>
		<table align="center">
		<tr class="blurbluebg"><td width="400" align="right">Total de faltante:</td>
		<td width="200"><span id="span_ajustesFaltante"></span></td></tr>
		<tr class="whitebg"><td align="right">Diferencias en cortes:</td>
		<td><span id="span_ajustesCortes"></span></td></tr>
		<tr class="blurbluebg"><td align="right">Faltante compensado:</td>
		<td><span id="span_ajustesCompensado"></span></td></tr>
		<tr class="whitebg"><th align="right">Total:</td>
		<th align="left"><span id="span_ajustesTotal"></span></td></tr>
		<tr class="blurbluebg"><td align="right"><input type="button" value="Calcular" onclick="chk_calcularTotales()"/></td>
		<td></td></tr>
		</table>
		<br/><br/>
		<table align="center"> <!-- nombres -->
		<tr>
		<td>Entrega: <input type="text" id="text_entrega"/></td>
		<td>Recibe: <input type="text" id="text_recibe"/></td>
		<td>Realiza: <input type="text" id="text_realiza"/></td>
		</tr>
		</table>
		<br/><br/>
		<center>
		<input type="button" value="Registrar Nombres" onclick="chk_regFirmas()"/> 
		<!-- botón de actualizar archivo lista.xml -->
		<input type="button" value="Actualizar Lista" onclick="chk_actLista()"/>
		</center>
		<br/><br/>
		</form>
		<?php
		break;
	case "reg_compensa":
		$invXML = xml_loadInvFile($_GET["suc"], $_GET["date"]);
		$i = count($invXML["faltantes"][intval($_GET["faltN"])]["compensan"]);
		$invXML["faltantes"][intval($_GET["faltN"])]["compensan"][$i] = array();
		$invXML["faltantes"][intval($_GET["faltN"])]["compensan"][$i]["id"] = $_GET["comp"];
		$invXML["faltantes"][intval($_GET["faltN"])]["compensan"][$i]["cantidad"] = intval($_GET["cant"]);
		xml_saveInvFile($invXML, $_GET["suc"], $_GET["date"]);
		break;
	case "del_compensa":
		$invXML = xml_loadInvFile($_GET["suc"], $_GET["date"]);
		$tempComp = array();
		$k = 0;
		for ($i=0;$i<=count($invXML["faltantes"][intval($_GET["faltN"])]["compensan"])-1;$i++){
			if ($i == intval($_GET["sobN"])) continue;
			else {
				$tempComp[$k] = $invXML["faltantes"][intval($_GET["faltN"])]["compensan"][$i];
				$k++;
				}
			}
		$invXML["faltantes"][intval($_GET["faltN"])]["compensan"] = $tempComp;
		xml_saveInvFile($invXML, $_GET["suc"], $_GET["date"]);
		break;
	case "calcular_totales": //también manda los nombres de quienes firmarán
		$invXML = xml_loadInvFile($_GET["suc"], $_GET["date"]);
		$listXML = xml_loadListFile($_GET["suc"], $_GET["date"]);
		$total_array = array(0,0,0,0); //faltante, cortes, compensado, total
		if (count($invXML["faltantes"]) > 0){
			for ($i=0;$i<=count($invXML["faltantes"])-1;$i++){
				$total_array[0] += -1 * ($invXML["faltantes"][$i]["cantidad"] * $listXML[$invXML["faltantes"][$i]["id"]]["precio"]);
				if (count($invXML["faltantes"][$i]["compensan"]) > 0){
					for ($j=0;$j<=count($invXML["faltantes"][$i]["compensan"])-1;$j++){
						$total_array[2] += ($invXML["faltantes"][$i]["compensan"][$j]["cantidad"] * $listXML[$invXML["faltantes"][$i]["id"]]["precio"]);
						}
					}
				}
			}
		$total_array[1] = $invXML["totales"]["cortes"];
		$total_array[3] = $total_array[0] + $total_array[1] + $total_array[2];
		//registrar totales en invXML
		$invXML["totales"]["faltante"] = $total_array[0];
		$invXML["totales"]["compensado"] = $total_array[2];
		$invXML["totales"]["total"] = $total_array[3];
		xml_saveInvFile($invXML, $_GET["suc"], $_GET["date"]);
		//recuperar firmas
		$firmas = "no"; $fA = array();
		if (count($invXML["firmas"]) > 0){
			$fA[0] = $invXML["firmas"]["entrega"];
			$fA[1] = $invXML["firmas"]["recibe"];
			$fA[2] = $invXML["firmas"]["realiza"];
			$firmas = implode(",", $fA);
			}
		//mandar cadena de totales y firmas
		$totales = implode(",", $total_array);
		echo "totales:" . $totales . ";firmas:" . $firmas;
		break;
	case "show_moneyCant":
		echo gen_showMoneyCant(intval($_GET["cant"]));
		break;
	case "show_redBlueCant":
		echo gen_showRedBlueCant(intval($_GET["cant"]));
		break;
	case "reg_firmas":
		$invXML = xml_loadInvFile($_GET["suc"], $_GET["date"]);
		$invXML["firmas"]["entrega"] = utf8_encode($_GET["ent"]);
		$invXML["firmas"]["recibe"] = utf8_encode($_GET["rec"]);
		$invXML["firmas"]["realiza"] = utf8_encode($_GET["rea"]);
		xml_saveInvFile($invXML, $_GET["suc"], $_GET["date"]);
		break;
	case "modificar_precio":
		xml_modPrecio($_GET["suc"], $_GET["art"], $_GET["precio"], $_GET["date"]);
		echo $_GET["precio"];
		break;
	case "actualizar_lista":
		$listXML = xml_loadListFile($_GET["suc"], $_GET["date"]);
		$invXML = xml_loadInvFile($_GET["suc"], $_GET["date"]);
		$lK = array_keys($listXML);
		$newList = array();
		for ($i=0;$i<=count($lK)-1;$i++){
			if ($invXML["lista"][$lK[$i]]["disponible"] != 0) $newList[$lK[$i]] = $listXML[$lK[$i]];
			}
		xml_saveListFile($newList, $_GET["suc"]);
		break;
	}
?>