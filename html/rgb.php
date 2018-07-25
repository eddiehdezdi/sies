<!DOCTYPE html>

<html>
<head>
<title>Colores RGB</title>
</head>
<body>
<table align="center" border="0"><tr>
<?php
$rgb = "";

for ($i=255;$i>=0;$i-=10){
	for ($j=255;$j>=0;$j-=10){
		?><tr><?php
		for ($k=255;$k>=0;$k-=10){
			$rgb = $i . "," . $j . "," . $k;
			?>
			<td align="center" style="background-color:rgba(<?php echo $rgb;?>,1.0);"><?php echo $rgb;?></td>
			<?php
			}
		?></tr><?php
		}
	}

?>
</tr>
</table>
</body>
</html>