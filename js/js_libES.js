function es_display(){
	var sucDate_getStr = "suc=" + document.getElementById("hidden_suc").value + "&date=" + document.getElementById("hidden_date").value;
	document.getElementById("div_subCont").innerHTML = make_XMLHttpRequest("../php/php_libES.php", "action=show_EScont&" + sucDate_getStr);
	document.getElementById("div_esDisplay").innerHTML = make_XMLHttpRequest("../php/php_libES.php", "action=show_display&" + sucDate_getStr);
	}

function es_regNewEntrada(type){
	var sucDate_getStr = "suc=" + document.getElementById("hidden_suc").value + "&date=" + document.getElementById("hidden_date").value;
	var art = "";
		if (type == "new") art = document.getElementById("text_newEntradaName").value;
		else art = document.getElementById("select_newEntrada").value;
	var cant = document.getElementById("text_newEntradaCant").value;
	var final_getStr = sucDate_getStr + "&action=reg_newEntrada&type=" + type + "&art=" + art + "&cant=" + cant;
	make_XMLHttpRequest("../php/php_libES.php", final_getStr);
	document.getElementById("form_newEntrada").reset();
	es_display();
	//window.scrollTo(0, window.innerHeight);
	}

function es_regNewSalida(){
	var sucDate_getStr = "suc=" + document.getElementById("hidden_suc").value + "&date=" + document.getElementById("hidden_date").value;
	var art = document.getElementById("select_newSalida").value;
	var cant = document.getElementById("text_newSalidaCant").value;
	var final_getStr = sucDate_getStr + "&action=reg_newSalida&art=" + art + "&cant=" + cant;
	make_XMLHttpRequest("../php/php_libES.php", final_getStr);
	document.getElementById("form_newSalida").reset();
	es_display();
	}

function es_changeRow(es, action, i, id){
	var sucDate_getStr = "suc=" + document.getElementById("hidden_suc").value + "&date=" + document.getElementById("hidden_date").value;
	var final_getStr = sucDate_getStr + "&action=change_row&es=" + es + "&rowAction=" + action + "&row=" + i;
	if (action == "cantidad") final_getStr = final_getStr + "&cant=" + prompt("Nueva cantidad:");
	if (action == "precio") final_getStr = final_getStr + "&id=" + id + "&precio=" + prompt("Nuevo precio:");
	make_XMLHttpRequest("../php/php_libES.php", final_getStr);
	es_display();
	}