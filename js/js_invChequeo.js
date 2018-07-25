function chequeo_display(){
	var sucDate_getStr = "suc=" + document.getElementById("hidden_suc").value + "&date=" + document.getElementById("hidden_date").value;
	document.getElementById("div_subCont").innerHTML = make_XMLHttpRequest("../php/php_invChequeo.php", "action=show_chkCont&" + sucDate_getStr);
	chk_showAll();
	}

function chk_showCant(cant){ //optimizar!!
	//cant puede ser 'anterior', 'entradas', 'salidas', 'existencia', 'diferencia' ó 'disponible'
	var sucDate_getStr = "suc=" + document.getElementById("hidden_suc").value + "&date=" + document.getElementById("hidden_date").value;
	sucDate_getStr = sucDate_getStr + "&cant=" + cant;
	var cant_str = make_XMLHttpRequest("../php/php_invChequeo.php", "action=get_cantStr&" + sucDate_getStr);
	var cant_array = cant_str.split(";");
	var reng = new Array(); var id = ""; var cant_value = "";
	for (i=0;i<=cant_array.length-1;i++){
		reng = cant_array[i].split(":");
		id = reng[0]; cant_value = reng[1];
		if (cant != "diferencia") document.getElementById("span_" + id + "_" + cant).innerHTML = cant_value;
		else document.getElementById("span_" + id + "_" + cant).innerHTML = make_XMLHttpRequest("../php/php_invChequeo.php", "action=show_redBlueCant&cant=" + cant_value);
		}
	}

/*function chk_showAll(){ // <-- no entiendo por qué no quiso con el for!!!
	chk_showCant("anterior");
	chk_showCant("entradas");
	chk_showCant("salidas");
	chk_showCant("existencia");
	chk_showCant("diferencia");
	chk_showCant("disponible");
	}*/

function chk_showAll(){ //optimizar!!
	var sucDate_getStr = "suc=" + document.getElementById("hidden_suc").value + "&date=" + document.getElementById("hidden_date").value;
	var cant_str = make_XMLHttpRequest("../php/php_invChequeo.php", "action=get_allCantStr&" + sucDate_getStr);
	var cant_array = cant_str.split(";");
	var cK = new Array("anterior", "entradas", "salidas", "existencia", "diferencia", "disponible");
	for (i=0;i<=cant_array.length-1;i++){
		var reng = cant_array[i].split(":");
		var id = reng[0]; var cant_values = reng[1].split(",");
		for (j=0;j<=cK.length-1;j++){
			if (j != 4) document.getElementById("span_" + id + "_" + cK[j]).innerHTML = cant_values[j];
			else document.getElementById("span_" + id + "_" + cK[j]).innerHTML = make_XMLHttpRequest("../php/php_invChequeo.php", "action=show_redBlueCant&cant=" + cant_values[j]);
			}
		}
	}

function chk_showArtInfo(id){
	var sucDate_getStr = "suc=" + document.getElementById("hidden_suc").value + "&date=" + document.getElementById("hidden_date").value;
	var final_getStr = sucDate_getStr + "&art=" + id;
	var w_artInfo = window.open("");
	w_artInfo.document.write(make_XMLHttpRequest("../php/php_artInfo.php", "action=build_artInfo&" + final_getStr));
	}

function chk_loadPrevInv(){
	var sucDate_getStr = "suc=" + document.getElementById("hidden_suc").value + "&date=" + document.getElementById("hidden_date").value;
	make_XMLHttpRequest("../php/php_invChequeo.php", "action=load_prevInv&" + sucDate_getStr);
	chk_showCant("anterior");
	}

function chk_contarES(){
	//también cuenta los cortes
	var sucDate_getStr = "suc=" + document.getElementById("hidden_suc").value + "&date=" + document.getElementById("hidden_date").value;
	make_XMLHttpRequest("../php/php_invChequeo.php", "action=contar_es&" + sucDate_getStr);
	chk_showCant("entradas");
	chk_showCant("salidas");
	}

function chk_calcExist(){
	var sucDate_getStr = "suc=" + document.getElementById("hidden_suc").value + "&date=" + document.getElementById("hidden_date").value;
	make_XMLHttpRequest("../php/php_invChequeo.php", "action=calcular_exist&" + sucDate_getStr);
	chk_showCant("existencia");
	}

function chk_calcDif(){
	var sucDate_getStr = "suc=" + document.getElementById("hidden_suc").value + "&date=" + document.getElementById("hidden_date").value;
	make_XMLHttpRequest("../php/php_invChequeo.php", "action=calcular_dif&" + sucDate_getStr);
	chk_showCant("diferencia");
	}

function chk_calcularTotales(){ //optimizar!!
	var sucDate_getStr = "suc=" + document.getElementById("hidden_suc").value + "&date=" + document.getElementById("hidden_date").value;
	var totales = make_XMLHttpRequest("../php/php_invChequeo.php", "action=calcular_totales&" + sucDate_getStr).split(";")[0].split(":")[1].split(",");
	document.getElementById("span_ajustesFaltante").innerHTML = make_XMLHttpRequest("../php/php_invChequeo.php", "action=show_moneyCant&cant=" + totales[0]);
	document.getElementById("span_ajustesCortes").innerHTML = make_XMLHttpRequest("../php/php_invChequeo.php", "action=show_moneyCant&cant=" + totales[1]);
	document.getElementById("span_ajustesCompensado").innerHTML = make_XMLHttpRequest("../php/php_invChequeo.php", "action=show_moneyCant&cant=" + totales[2]);
	document.getElementById("span_ajustesTotal").innerHTML = make_XMLHttpRequest("../php/php_invChequeo.php", "action=show_moneyCant&cant=" + totales[3]);
	var firmas = make_XMLHttpRequest("../php/php_invChequeo.php", "action=calcular_totales&" + sucDate_getStr).split(";")[1].split(":")[1].split(",");
	document.getElementById("text_entrega").value = firmas[0];
	document.getElementById("text_recibe").value = firmas[1];
	document.getElementById("text_realiza").value = firmas[2];
	}

function chk_mostrarAjustes(){
	var sucDate_getStr = "suc=" + document.getElementById("hidden_suc").value + "&date=" + document.getElementById("hidden_date").value;
	document.getElementById("div_chkAjustes").innerHTML = make_XMLHttpRequest("../php/php_invChequeo.php", "action=show_ajustes&" + sucDate_getStr);
	chk_calcularTotales();
	}

function chk_realizarAjustes(){
	var sucDate_getStr = "suc=" + document.getElementById("hidden_suc").value + "&date=" + document.getElementById("hidden_date").value;
	make_XMLHttpRequest("../php/php_invChequeo.php", "action=make_ajustes&" + sucDate_getStr);
	chk_mostrarAjustes();
	}

function chk_regCompensa(faltN){
	var sucDate_getStr = "suc=" + document.getElementById("hidden_suc").value + "&date=" + document.getElementById("hidden_date").value;
	var compensa = document.getElementById("select_comp_" + faltN).value;
	var cantidad = document.getElementById("text_comp_" + faltN).value;
	make_XMLHttpRequest("../php/php_invChequeo.php", "action=reg_compensa&faltN=" + faltN + "&cant=" + cantidad + "&comp=" + compensa + "&" + sucDate_getStr);
	chk_mostrarAjustes();
	}

function chk_delCompensa(faltN, sobN){
	var sucDate_getStr = "suc=" + document.getElementById("hidden_suc").value + "&date=" + document.getElementById("hidden_date").value;
	make_XMLHttpRequest("../php/php_invChequeo.php", "action=del_compensa&faltN=" + faltN + "&sobN=" + sobN + "&" + sucDate_getStr);
	chk_mostrarAjustes();
	}

function chk_regFirmas(){
	var sucDate_getStr = "suc=" + document.getElementById("hidden_suc").value + "&date=" + document.getElementById("hidden_date").value;
	var ent = document.getElementById("text_entrega").value;
	var rec = document.getElementById("text_recibe").value;
	var rea = document.getElementById("text_realiza").value;
	make_XMLHttpRequest("../php/php_invChequeo.php", "action=reg_firmas&ent=" + ent + "&rec=" + rec + "&rea=" + rea + "&" + sucDate_getStr);
	}

function chk_modifPrecio(art){
	var sucDate_getStr = "suc=" + document.getElementById("hidden_suc").value + "&date=" + document.getElementById("hidden_date").value;
	var precio = prompt("Nuevo precio:");
	document.getElementById("span_" + art + "_precio").innerHTML = make_XMLHttpRequest("../php/php_invChequeo.php", "action=modificar_precio&art=" + art + "&precio=" + precio + "&" + sucDate_getStr);
	}

function chk_actLista(){
	var sucDate_getStr = "suc=" + document.getElementById("hidden_suc").value + "&date=" + document.getElementById("hidden_date").value;
	make_XMLHttpRequest("../php/php_invChequeo.php", "action=actualizar_lista&" + sucDate_getStr);
	alert("Lista actualizada");
	}