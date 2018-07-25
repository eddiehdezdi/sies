<?php
require("php_general.php");

function prevMonth($current){
	$prev = array("m" => $current["m"] - 1, "y" => $current["y"]);
	if ($prev["m"] < 1){
		$prev["m"] = 12;
		$prev["y"] -= 1;
		}
	return $prev;
	}

function nextMonth($current){
	$nextM = array("m" => $current["m"] + 1, "y" => $current["y"]);
	if ($nextM["m"] > 12){
		$nextM["m"] = 1;
		$nextM["y"] += 1;
		}
	return $nextM;
	}
?>

<center>
<br/>
<?php
echo $_GET["y"] . " - " . strtoupper(sp_month(intval($_GET["m"])));
?>
<br/><br/>
<table width="100%">
<tr>
<?php
$current_month = array("m" => intval($_GET["m"]), "y" => intval($_GET["y"]));
$prev_month = prevMonth($current_month);
$next_month = nextMonth($current_month);
$prevStr = $prev_month["m"] . ", " . $prev_month["y"];
$nextStr = $next_month["m"] . ", " . $next_month["y"];

?>
<td align="center" valign="middle"><a href="javascript:cal_drawMonth(<?php echo $prevStr?>)">
<img src="../jpg/lt.jpg" style="vertical-align:-2px"/>
</a></td>
<td>
<table align="center">
<tr>
<?php
$week_array = array("D","L","M","M","J","V","S");
for ($i=0;$i<=6;$i++){
	?>
	<td width="20" align="center"><?php echo $week_array[$i];?></td>
	<?php
	}
?>
</tr>
<tr>
<?php
for ($i=0;$i<=6;$i++) $week_array[$i] = "";
$firstStamp = mktime(0,0,0,intval($_GET["m"]),1,intval($_GET["y"]));
$numDias = date("t", $firstStamp);
$firstWeekDay = date("w", $firstStamp);
$d = 1;
for ($i=$firstWeekDay;$i<=6;$i++){
	$week_array[$i] = $d;
	$d++;
	}
for ($i=0;$i<=6;$i++){
	?>
	<td width="20" align="center">
	<a href="javascript:set_hiddenDate(<?php echo $week_array[$i];?>, <?php echo $_GET["m"];?>, '<?php echo $_GET["y"];?>')">
	<?php echo $week_array[$i];?>
	</a></td>
	<?php
	}
?>
</tr>
<?php
while (true){
	?>
	<tr>
	<?php
	for ($i=0;$i<=6;$i++){
		?>
		<td width="20" align="center">
		<a href="javascript:set_hiddenDate(<?php echo $d;?>, <?php echo $_GET["m"];?>, '<?php echo $_GET["y"];?>')">
		<?php echo $d;?>
		</a></td>
		<?php
		if ($d >= $numDias) break;
		$d++;
		}
	?>
	</tr>
	<?php
	if ($d >= $numDias) break;
	}
?>
</table>
</td>
<td align="center" valign="middle"><a href="javascript:cal_drawMonth(<?php echo $nextStr?>)">
<img src="../jpg/rt.jpg" style="vertical-align:-2px"/>
</a></td>
</tr>
</table>
</center>