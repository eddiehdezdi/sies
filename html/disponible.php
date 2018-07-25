<!DOCTYPE html>

<?php
require ("../php/php_general.php");
?>

<html>
<head>
<title>Lista Disponible</title>
</head>

<body>
<?php
$suc = $_GET["suc"];
$date = $_GET["date"];

$listPath = "../xml/".$suc."/listas/".$date.".xml";
$invPath = "../xml/".$suc."/inventarios/".$date.".xml";
$listXML = simplexml_load_file($listPath);
$invXML = simplexml_load_file($invPath);
$sucXML = simplexml_load_file("../xml/sucursales.xml");
?>
<h2>Inventario Disponible de <?php echo utf8_decode($sucXML->xpath("sucursal[id='".$suc."']")[0]->name);?></h2>
<h3><?php echo utf8_decode(gen_fullDate($date));?></h3>
<ul>
<?php
foreach ($invXML->lista->articulo as $articulo){
	if (intval($articulo->disponible) != 0){
		?>
		<li>
		<?php
		echo $articulo->disponible . " - " . utf8_decode($listXML->xpath("articulo[id='".$articulo->id."']")[0]->name);
		?>
		</li>
		<?php
		}
	}
?>
</ul>
</body>

</html>