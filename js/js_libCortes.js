function cortes_display(){
	var sucDate_getStr = "suc=" + document.getElementById("hidden_suc").value + "&date=" + document.getElementById("hidden_date").value;
	document.getElementById("div_subCont").innerHTML = make_XMLHttpRequest("../php/php_libCortes.php", "action=show_cc&" + sucDate_getStr);
	}

function cortes_regCorte(){
	var sucDate_getStr = "suc=" + document.getElementById("hidden_suc").value + "&date=" + document.getElementById("hidden_date").value;
	var type = document.getElementById("select_cType").value;
	var ncant = document.getElementById("text_cant").value;
	make_XMLHttpRequest("../php/php_libCortes.php", "action=reg_corte&type=" + type + "&cant=" + ncant + "&" + sucDate_getStr);
	cortes_display();
	}

function cortes_changeRow(type){
	var sucDate_getStr = "suc=" + document.getElementById("hidden_suc").value + "&date=" + document.getElementById("hidden_date").value;
	var ncant = "0";
	if (type == "cant") ncant = prompt("Nueva cantidad:");
	make_XMLHttpRequest("../php/php_libCortes.php", "action=mod_cortes&type=" + type + "&cant=" + ncant + "&" + sucDate_getStr);
	cortes_display();
	}