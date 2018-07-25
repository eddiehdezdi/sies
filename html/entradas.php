<!DOCTYPE html>

<?php
require ("../php/php_general.php");
?>

<html>
<head>
<title>Diario de Entradas</title>
</head>

<body>
Sistema de Inventarios de Eclipse Shop v0.4
<br>
Diario de Entradas y Salidas
<br><br>
<?php
echo gen_fullDate($_GET["date"]);
$tiendas = array('bodega', 'alhondiga', 'carranza', 'othon', 'boutique_othon', 'zaragoza');
$sucXML = simplexml_load_file("../xml/sucursales.xml");
?>
<br><br>
<table>
<tr>
<?php
foreach ($tiendas as $suc){
	?>
	<th><?php echo utf8_decode($sucXML->xpath("sucursal[id='".$suc."']")[0]->name);?></th>
	<?php
	}
?>
</tr>
<tr>
<?php
foreach ($tiendas as $suc){
	?>
	<td>
	<?php
	$libPath = "../xml/".$suc."/libreta/".$_GET["date"].".xml";
	if (file_exists($libPath) == true){
		$libXML = simplexml_load_file($libPath);
		
		}
	?>
	</td>
	<?php
	}
?>
</tr>
</table>
</body>

</html>