<!DOCTYPE html>

<?php
require ("../php/php_bests.php");
?>

<html>
<head>
<title>SIES - Art&iacute;culos m&aacute;s vendidos</title>
<script type="text/javascript" src="../js/js_general.js"></script>
<script type="text/javascript" src="../js/js_bests.js"></script>
<link rel="stylesheet" type="text/css" href="../css/css_cuentas.css"/>
</head>

<body>
<div class="d-body">
	Sistema de Inventarios de Eclipse Shop v0.4
	<br/>
	Reporte de Art&iacute;culos m&aacute;s vendidos<br/><br/>
	<form> <!-- div de suc-date -->
	<div class="d-suc-date">
	Tienda: <span id="span_sucList">
		<select id="select_sucList" onchange="bests_drawTable();"> <!-- select de tiendas -->
		<option value="no"> - seleccionar - </option>
		<?php
		$sucList_array = xml_loadSucFile();
		for ($i=0; $i<=count($sucList_array)-1; $i++){
			?>
			<option value="<?php echo $sucList_array[$i]["id"]?>"><?php echo $sucList_array[$i]["name"]?></option>
			<?php
			}
		?>
		</select>
	</span>
	</div>
	<div class="d-suc-date">
	Año: <select id="select_year" onchange="bests_drawTable();"> <!-- select de años -->
	<?php
	$yr = date("Y");
	for ($i=$yr-5;$i<=$yr;$i++){
		?>
		<option value="<?php echo $i;?>" <?php if($i == $yr) echo "selected";?>><?php echo $i;?></option>
		<?php
		}
	?>
	</select>
	</div>
	</form> <!-- fin de suc-date -->
	<br/>
	<div id="div_drawArea" class="d-area"></div> <!-- div_drawArea -->
</div>

<form>
<input type="button" value="Generar" onclick="bests_g()"/>
</form>

</body>

</html>