<!DOCTYPE html>

<?php
require("../php/php_general.php");
require("../php/php_xmlDOM.php");

$tiendasXML = xml_loadTiendasFile();
?>

<html>
<head>
<title>SIES - Estadisticas</title>
</head>
<body>
<form>
Sistema de Inventarios de Eclipse Shop v0.4
<br/>
Estad&iacute;sticas
<br/>
<center>
<input type="button" value="Generar Estadisticas del Año"/>
<br/>
Ultimo archivo generado: <span id="span_lastStats"></span>
</center>
<br/><br/>
<table align="center">
<tr>
	<th width="150">Tiendas</th>
	<th width="150">Rangos de Tiempo</th>
	<th width="500">Seleccione..</th>
</tr>
<tr valign="top">
	<td>
	<br/>
	<input type="checkbox" id="chbx_todas"/> <span style="font-weight:bold;">Todas</span>
	<br/>
	<?php
	$tK = array_keys($tiendasXML);
	for ($i=0;$i<=count($tK)-1;$i++){
		?>
		<input type="checkbox" id="chbx_<?php echo $tK[$i];?>"/> <?php echo $tiendasXML[$tK[$i]];?>
		<br/>
		<?php
		}
	?>
	</td>
	<td>
	<br/>
	<input type="radio" checked/> Mes
	<br/>
	<input type="radio"/> D&iacute;a
	<br/>
	<input type="radio"/> Año
	<br/>
	</td>
	<td align="center">
	<br/>
	<select id="select_day">
	<option>23</option>
	</select>
	 de 
	<select id="select_month">
	<option>Marzo</option>
	</select>, 
	<select id="select_year">
	<option>2013</option>
	</select>
	</td>
</tr>
</table>
</form>
</body>
</html>