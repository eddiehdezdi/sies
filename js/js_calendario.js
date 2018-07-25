function cal_drawMonth(m, y){
	var getStr = "m=" + m.toString() + "&y=" + y.toString();
	document.getElementById("div_calendario").innerHTML = make_XMLHttpRequest("../php/php_calendario.php", getStr);
	document.getElementById("div_calendario").setAttribute("class", "css_calendario");
	}

function set_hiddenDate(d, m, y){
	var day = ""; var month = "";
	if (d < 10) day = "0" + d.toString();
	else day = d.toString();
	if (m < 10) month = "0" + m.toString();
	else month = m.toString();
	hD = y + "-" + month + "-" + day;
	document.getElementById("hidden_date").value = hD;
	gen_setFullDate();
	document.getElementById("div_calendario").setAttribute("class", "css_noCalendario");
	}