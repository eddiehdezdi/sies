<!DOCTYPE html>

<?php
require("../php/php_general.php");
require("../php/php_xmlDOM.php");

$tiendXML = xml_loadTiendasFile();
$invXML = xml_loadInvFile($_GET["suc"], $_GET["date"]);
$listXML = xml_loadListFile($_GET["suc"], $_GET["date"]);

//ordenar lista de inventario
ksort($invXML["lista"]);
?>

<html>
<head>
<title>SIES - v0.4 - REPORTE</title>

<style type="text/css">
.whitebg {background-color:white;}

<?php
switch ($_GET["suc"]){
	case "alhondiga": //Aqua
		$cbg = "125,255,215";
		$bcb = "195,255,225";
		break;
	case "arista": //Indigo (morado)
		$cbg = "193,179,215";
		$bcb = "221,212,232";
		break;
	case "boutique_othon": //Salmón (naranja)
		$cbg = "253,202,162";
		$bcb = "254,235,201";
		break;
	case "obregon": //Rosa
		$cbg = "251,182,209";
		$bcb = "253,222,238";
		break;
	case "carranza": //Indigo (morado)
		$cbg = "193,179,215";
		$bcb = "221,212,232";
		break;
	case "othon": //Azul
		$cbg = "148,168,208";
		$bcb = "191,213,232";
		break;
	case "zaragoza": //Verde
		$cbg = "175,255,145";
		$bcb = "225,255,195";
		break;
	case "el_dorado": //Verde
		$cbg = "175,255,145";
		$bcb = "225,255,195";
		break;
	case "b_anaya": //Aqua
		$cbg = "125,255,215";
		$bcb = "195,255,225";
		break;
	}

echo ".colorbg {background-color:rgb(".$cbg.");}\n";
echo ".blurcolorbg {background-color:rgb(".$bcb.");}\n";

?>

</style>

<script type="text/javascript" src="../js/js_general.js"></script>
</head>
<body>
<center>
Sistema de Inventarios de Eclipse Shop
<br/>
Tienda: <?php echo utf8_encode($tiendXML[$_GET["suc"]]);?> > 
Inventario > Reporte de Inventario > 
<?php echo utf8_decode($invXML["firmas"]["entrega"]);
if ($invXML["firmas"]["entrega"] != $invXML["firmas"]["recibe"]) echo " >> " . utf8_decode($invXML["firmas"]["recibe"]);
?>
<br/>
Fecha: <?php echo gen_fullDate($_GET["date"]);?>
</center>
<br/><br/>
<table align="center"> <!-- tabla del inventario -->
<tr style="height:40px;">
	<th width="440" align="left" class="colorbg">Art&iacute;culo</td>
	<th width="60" align="center" class="colorbg">Anterior</td>
	<th width="60" align="center" class="colorbg">Entradas</td>
	<th width="60" align="center" class="colorbg">Salidas</td>
	<th width="60" align="center" class="colorbg">Existencia</td>
	<th width="60" align="center" class="colorbg">Diferencia</td>
	<th width="60" align="center" class="colorbg">Disponible</td>
</tr>
<?php
$lK = array_keys($invXML["lista"]);
$css_bg = "";
for ($i=0;$i<=count($lK)-1;$i++){
	$css_bg = "blurcolorbg";
	if ($i % 2 == 0) $css_bg = "whitebg";
	?>
	<tr align="center" class="<?php echo $css_bg;?>">
	<td align="left"><?php echo $listXML[$lK[$i]]["name"];?></td>
	<td><?php echo $invXML["lista"][$lK[$i]]["anterior"];?></td>
	<td><?php echo $invXML["lista"][$lK[$i]]["entradas"];?></td>
	<td><?php echo $invXML["lista"][$lK[$i]]["salidas"];?></td>
	<td><?php echo $invXML["lista"][$lK[$i]]["existencia"];?></td>
	<td><?php echo gen_showRedBlueCant($invXML["lista"][$lK[$i]]["diferencia"]);?></td>
	<td><?php echo $invXML["lista"][$lK[$i]]["disponible"];?></td>
	</tr>
	<?php
	}
?>
</table> <!-- fin de tabla del inventario-->
<br/><br/>
<table width="800" align="center"> <!-- tabla de compensado -->
<tr style="height:40px;" class="colorbg">
<th width="47%">Faltante compensado con..</td>
<th width="6%" align="center"><br/></td>
<th width="47%">Sobrante</td>
</tr>
<?php //condensar por favor!!!
$n = 0;
	for ($i=0;$i<=count($invXML["faltantes"])-1;$i++){
		$css_bg = "whitebg";
		if ($n % 2 != 0) $css_bg = "blurcolorbg";
		if (count($invXML["faltantes"][$i]["compensan"]) > 0){
			?>
			<tr class="<?php echo $css_bg;?>">
			<td align="left">
			<?php
			$jcant = 0;
			for ($j=0;$j<=count($invXML["faltantes"][$i]["compensan"])-1;$j++){
				$jcant += $invXML["faltantes"][$i]["compensan"][$j]["cantidad"];
				}
			echo $jcant;
			?>
			 - 
			<?php echo $listXML[$invXML["faltantes"][$i]["id"]]["name"];?>
			</td>
			<td align="center">con</td>
			<td align="left">
			<?php
			for ($j=0;$j<=count($invXML["faltantes"][$i]["compensan"])-1;$j++){
				echo $invXML["faltantes"][$i]["compensan"][$j]["cantidad"];
				?>
				 - 
				<?php echo $listXML[$invXML["faltantes"][$i]["compensan"][$j]["id"]]["name"];?>
				<br/>
				<?php
				}
			?>
			</td>
			</tr>
			<?php
			$n++;
			}
		}
	?>
</table> <!-- fin de tabla de compensado -->

<br/><br/>

<table width="600" align="center"> <!-- tabla del faltante --> <!-- condensar por el amor de Dios!!! -->
<tr style="height:40px;" class="colorbg">
<?php
$hay_faltante = "";
if ($invXML["totales"]["total"] >= 0){
	$hay_faltante = "No Hay ";
	}
?>
<th width="75%"><?php echo $hay_faltante;?>Faltante</th>
<th width="25%"><br/></th>
</tr>
<?php
$faltante_articulo = 0;
$n = 0; $cantidad = 0;
for ($i=0;$i<=count($invXML["faltantes"])-1;$i++){
	$cantidad = $invXML["faltantes"][$i]["cantidad"];
	for ($j=0;$j<=count($invXML["faltantes"][$i]["compensan"])-1;$j++){
		$cantidad -= $invXML["faltantes"][$i]["compensan"][$j]["cantidad"];
		}
	$nombre = $listXML[$invXML["faltantes"][$i]["id"]]["name"];
	$faltante_articulo = $cantidad * $listXML[$invXML["faltantes"][$i]["id"]]["precio"];	
	if ($faltante_articulo > 0){
		$css_bg = "whitebg";
		if ($n % 2 != 0) $css_bg = "blurcolorbg";
		?>
		<tr class="<?php echo $css_bg;?>">
		<td align="left">
		<?php
		echo $cantidad . " - " . $nombre;
		?>
		</td>
		<td align="right">
		<?php
		echo "$" . $faltante_articulo;
		?>
		</td>
		</tr>
		<?php
		$n++;
		}
	}

$css_bg = "whitebg";
if ($n % 2 != 0) $css_bg = "blurcolorbg";
?>
<tr class="<?php echo $css_bg;?>">

<td align="right">Faltante </td>
<td align="right">
<?php
$cortes = $invXML["totales"]["faltante"] + $invXML["totales"]["compensado"];
$signo = "";
if ($cortes > 0) $signo = "-";
echo $signo . "$" . -1 * $cortes;
?>
</td>
</tr>
<?php $n++;

$css_bg = "whitebg";
if ($n % 2 != 0) $css_bg = "blurcolorbg";
?>
<tr class="<?php echo $css_bg;?>">

<td align="right">Diferencias en Cortes </td>
<td align="right">
<?php
$cortes = $invXML["totales"]["cortes"];
$signo = "";
if ($cortes > 0) $signo = "-";
echo $signo . "$" . $cortes;
?>
</td>
</tr>
<?php $n++;

$css_bg = "whitebg";
if ($n % 2 != 0) $css_bg = "blurcolorbg";
?>
<tr class="<?php echo $color;?>">
<td align="right">Faltante total </td>
<td style="color:red" align="right">
<?php
$total = $invXML["totales"]["total"];
echo "$" . abs($total);
?>
</td>
</tr>

</table> <!-- termina tabla de faltantes -->

<br/><br/>

<table width="600" align="center">
<tr style="height:75px;" class="blurcolorbg"><td width="33%"></td><td width="33%"></td><td width="33%"></td></tr>
<tr>
<td align="center">
Entrega: 
<?php
echo utf8_decode($invXML["firmas"]["entrega"]);
?>
</td>
<td align="center">
Recibe: 
<?php
echo utf8_decode($invXML["firmas"]["recibe"]);
?>
</td>
<td align="center">
Realiza: 
<?php
echo utf8_decode($invXML["firmas"]["realiza"]);
?>
</td>
</tr>
</table>

</body>
</html>