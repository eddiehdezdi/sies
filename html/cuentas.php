<!DOCTYPE html>

<?php
require ("../php/php_cuentas.php");
?>

<html>
<head>
<meta charset="UTF-8"/>
<title>SIES - Cuentas</title>
<script type="text/javascript" src="../js/js_general.js"></script>
<script type="text/javascript" src="../js/js_cuentas.js"></script>
<link rel="stylesheet" type="text/css" href="../css/css_cuentas.css"/>
</head>

<body>
<div class="d-body">
	Sistema de Inventarios de Eclipse Shop v0.4
	<br/>
	Reporte de Ventas por Mes
	<br/><br/>
	<form> <!-- div de suc-date -->
	<div class="d-suc-date">
	Tienda: <span id="span_sucList">
		<select id="select_sucList" onchange="cuentas_drawMonth()"> <!-- select de tiendas -->
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
	Mes: <select id="select_month" onchange="cuentas_drawMonth()"> <!-- select de meses -->
	<?php
	$mth = date("m");
	for ($i=1;$i<=12;$i++){
		?>
		<option value="<?php echo $i;?>" <?php if($i == $mth) echo "selected";?>><?php echo sp_month($i);?></option>
		<?php
		}
	?>
	</select>
	de: <select id="select_year" onchange="cuentas_drawMonth()"> <!-- select de años -->
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
</body>

</html>