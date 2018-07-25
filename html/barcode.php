<!DOCTYPE html>

<html>
	<head>
		<title>Captura Lector C&oacute;digos</title>
		<script src="../js/js_general.js"></script>
		<script>
			function barcode_regArt(id){
				var sucDate_getStr = "suc=afrika_el_dorado&date=2016-02-19";
				sucDate_getStr = sucDate_getStr + "&id=" + id;
				document.getElementById("div_lista").innerHTML = make_XMLHttpRequest("../php/php_barcode.php", "action=regArt&" + sucDate_getStr);
				document.getElementById('input_codigo').value = "";
				document.getElementById('input_codigo').focus();
			}
		</script>
	</head>
	<body onload="document.getElementById('input_codigo').focus();">
		<div>
			<form onsubmit="barcode_regArt(document.getElementById('input_codigo').value); return false;">
				Escanee C&oacute;digo
				<input id="input_codigo" type="text" size="50">
			</form>
		</div>
		<br>
		<div id="div_lista"></div>
	</body>
</html>