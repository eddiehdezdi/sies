<!DOCTYPE html>

<?php
require("../php/php_xmlDOM.php");
?>

<html>
<head>
<title>
Lista
</title>
</head>

<body>
<table border="0" width="1000">
<tr>
<th>Art&iacute;culo</th>
<th>Art&iacute;culo</th>
</tr>
<?php
$listXML = xml_loadListFile($_GET["suc"], $_GET["date"]);
$invXML = xml_loadInvFile($_GET["suc"], $_GET["date"]);
$aK = array_keys($listXML);
$i = 0;
while ($i <= count($aK)-1){
	?>
	<tr>
	<?php
	$k = 0;
	while (true){
		if ($i > count($aK)-1) break;
		?>
		<td>
		<?php
		echo $invXML["lista"][$aK[$i]]["disponible"]." - ".utf8_decode($listXML[$aK[$i]]["name"]);
		$i++;
		$k++;
		if ($k > 1) break;
		?>
		</td>
		<?php
		}
	?>
	</tr>
	<?php
	}
?>
</table>
</body>
</html>