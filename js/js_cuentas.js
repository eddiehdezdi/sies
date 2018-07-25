function cuentas_drawMonth(){
	var suc = document.getElementById("select_sucList").value;
	if (suc != "no"){
		var m = document.getElementById("select_month").value;
		var y = document.getElementById("select_year").value;
		var get_str = "action=drawSelected&suc=" + suc + "&mth=" + m + "&yr=" + y;
		document.getElementById("div_drawArea").innerHTML = make_XMLHttpRequest("../php/php_cuentas.php", get_str);
		}
	}