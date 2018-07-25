
function mapeo_display(){
	var sucDate_getStr = "suc=" + document.getElementById("hidden_suc").value + "&date=" + document.getElementById("hidden_date").value;
	document.getElementById("div_subCont").innerHTML = make_XMLHttpRequest("../php/php_invMapeo.php", "action=show_mC&" + sucDate_getStr);
	document.getElementById("div_display").innerHTML = make_XMLHttpRequest("../php/php_invMapeo.php", "action=display&" + sucDate_getStr);
	document.getElementById("div_capturar").innerHTML = make_XMLHttpRequest("../php/php_invMapeo.php", "action=captSect&" + sucDate_getStr);
	}

function map_crearSeccion(){
	var sect_name = document.getElementById("text_sectName").value;
	var sucDate_getStr = "suc=" + document.getElementById("hidden_suc").value + "&date=" + document.getElementById("hidden_date").value;
	make_XMLHttpRequest("../php/php_invMapeo.php", "action=createSect&name=" + sect_name + "&" + sucDate_getStr);
	mapeo_display();
	}

function map_showSection(sect_num){
	var sucDate_getStr = "suc=" + document.getElementById("hidden_suc").value + "&date=" + document.getElementById("hidden_date").value;
	sucDate_getStr = sucDate_getStr + "&sect=" + sect_num;
	//inv_mapeo(date_string); // <-- para qué???
	document.getElementById("sect_" + sect_num).innerHTML = make_XMLHttpRequest("../php/php_invMapeo.php", "action=showSect&" + sucDate_getStr);
	document.getElementById("span_captSub").innerHTML = make_XMLHttpRequest("../php/php_invMapeo.php", "action=captSub&" + sucDate_getStr);
	document.getElementById("div_capturar").innerHTML = "";
	}

function map_showHideSection(sect_num){
	if (document.getElementById("sect_" + sect_num).innerHTML == "") map_showSection(sect_num);
	else mapeo_display();
	}

function map_crearSub(sect_num){
	var sub_name = document.getElementById("text_subName").value;
	var sucDate_getStr = "suc=" + document.getElementById("hidden_suc").value + "&date=" + document.getElementById("hidden_date").value;
	sucDate_getStr = sucDate_getStr + "&sect=" + sect_num + "&name=" + sub_name;
	make_XMLHttpRequest("../php/php_invMapeo.php", "action=createSub&" + sucDate_getStr);
	map_showSection(sect_num);
	}

function map_showSub(sect_num, sub_num){
	var sucDate_getStr = "suc=" + document.getElementById("hidden_suc").value + "&date=" + document.getElementById("hidden_date").value;
	sucDate_getStr = sucDate_getStr + "&sect=" + sect_num + "&sub=" + sub_num;
	map_showSection(sect_num);
	document.getElementById("sect" + sect_num + "_sub" + sub_num).innerHTML = make_XMLHttpRequest("../php/php_invMapeo.php", "action=showSub&" + sucDate_getStr);
	document.getElementById("span_captArt").innerHTML = make_XMLHttpRequest("../php/php_invMapeo.php", "action=captArt&" + sucDate_getStr);
	document.getElementById("span_captSub").innerHTML = "";
	}

function map_showHideSub(sect_num, sub_num){
	if (document.getElementById("sect" + sect_num + "_sub" + sub_num).innerHTML == "") map_showSub(sect_num, sub_num);
	else map_showSection(sect_num);
	}

function map_regArt(sect_num, sub_num){
	var newStr = "";
	var artName = "";
		if (document.getElementById("text_artName").value == ""){
			artName = document.getElementById("select_artList").value;
			newStr = "false";
			}
		else {
			artName = document.getElementById("text_artName").value;
			newStr = "true";
			}
	var artCant = document.getElementById("text_artCant").value;
	var sucDate_getStr = "suc=" + document.getElementById("hidden_suc").value + "&date=" + document.getElementById("hidden_date").value;
	sucDate_getStr = sucDate_getStr + "&sect=" + sect_num + "&sub=" + sub_num + "&art=" + artName + "&cant=" + artCant + "&new=" + newStr;
	make_XMLHttpRequest("../php/php_invMapeo.php", "action=regArt&" + sucDate_getStr);
	map_showSub(sect_num, sub_num);
	window.scrollBy(0, 50);
	}

function map_modifyReg(sect_num, sub_num, art_num, action){
	var sucDate_getStr = "suc=" + document.getElementById("hidden_suc").value + "&date=" + document.getElementById("hidden_date").value;
	sucDate_getStr = sucDate_getStr + "&sect=" + sect_num + "&sub=" + sub_num + "&art=" + art_num;
	sucDate_getStr = sucDate_getStr + "&modify=" + action;
	switch(action){
		case "cn":
			sucDate_getStr = sucDate_getStr + "&newData=" + prompt("Nuevo nombre:");
			break;
		case "cc":
			sucDate_getStr = sucDate_getStr + "&newData=" + prompt("Nueva cantidad:");
			break;
		}
	make_XMLHttpRequest("../php/php_invMapeo.php", "action=modifyReg&" + sucDate_getStr);
	map_showSub(sect_num, sub_num);
	}

function map_modifySectSub(sect_num, sub_num, action){
	//action puede tomar los valores "del" ó "cn"
	var sucDate_getStr = "suc=" + document.getElementById("hidden_suc").value + "&date=" + document.getElementById("hidden_date").value;
	sucDate_getStr = sucDate_getStr + "&sect=" + sect_num + "&sub=" + sub_num;
		if (action == "del") sucDate_getStr = sucDate_getStr + "&modify=actionDel";
		else sucDate_getStr = sucDate_getStr + "&modify=" + prompt("Nuevo nombre:");
	make_XMLHttpRequest("../php/php_invMapeo.php", "action=modifySectSub&" + sucDate_getStr);
		if (sub_num == "-1") mapeo_display();
		else map_showSection(sect_num);              
	}

function map_contabilizar(){
	var sucDate_getStr = "suc=" + document.getElementById("hidden_suc").value + "&date=" + document.getElementById("hidden_date").value;
	alert(make_XMLHttpRequest("../php/php_invMapeo.php", "action=contabilizar&" + sucDate_getStr));
	}