function ventas_display(){
	var sucDate_getStr = "suc=" + document.getElementById("hidden_suc").value + "&date=" + document.getElementById("hidden_date").value;
	document.getElementById("div_subCont").innerHTML = make_XMLHttpRequest("../php/php_libVentas.php", "action=show_vC&" + sucDate_getStr);
	document.getElementById("div_display").innerHTML = make_XMLHttpRequest("../php/php_libVentas.php", "action=display_sheet&" + sucDate_getStr);
	document.getElementById("div_capturar").innerHTML = make_XMLHttpRequest("../php/php_libVentas.php", "action=captArt&" + sucDate_getStr);
	}

function ventas_regArt(){
	var sucDate_getStr = "suc=" + document.getElementById("hidden_suc").value + "&date=" + document.getElementById("hidden_date").value;
	sucDate_getStr = sucDate_getStr + "&regId=" + document.getElementById("select_artList").value;
	make_XMLHttpRequest("../php/php_libVentas.php", "action=regArt&" + sucDate_getStr);
	ventas_display();
	window.scrollBy(0, 200);
	}

function ventas_modReng(f_action, r){
	var sucDate_getStr = "suc=" + document.getElementById("hidden_suc").value + "&date=" + document.getElementById("hidden_date").value;
	var final_getStr = sucDate_getStr + "&action=modReng&sub_action=" + f_action + "&r=" + r;
	make_XMLHttpRequest("../php/php_libVentas.php", final_getStr);
	ventas_display();
	}

function ventas_goToInventario(){
	var sucDate_getStr = "suc=" + document.getElementById("hidden_suc").value + "&date=" + document.getElementById("hidden_date").value;
	make_XMLHttpRequest("../php/php_libVentas.php", "action=crear_inventario&" + sucDate_getStr);
	index_setShow("inv");
	}

function ventas_cambiaPrecio(rN){
	var sucDate_getStr = "suc=" + document.getElementById("hidden_suc").value + "&date=" + document.getElementById("hidden_date").value;
	var final_getStr = sucDate_getStr + "&action=cambiaPrecio&rn=" + rN + "&pr=" + prompt("Nuevo precio:").toString();
	make_XMLHttpRequest("../php/php_libVentas.php", final_getStr);
	ventas_display();
	}

function ventas_reCortes(){
	var sucDate_getStr = "suc=" + document.getElementById("hidden_suc").value + "&date=" + document.getElementById("hidden_date").value;
	var final_getStr = sucDate_getStr + "&action=recalcularCortes";
	make_XMLHttpRequest("../php/php_libVentas.php", final_getStr);
	ventas_display();
	}

function ventas_actPrecio(art, rN){
	var sucDate_getStr = "suc=" + document.getElementById("hidden_suc").value + "&date=" + document.getElementById("hidden_date").value;
	var final_getStr = sucDate_getStr + "&action=actualiza_precio&art=" + art + "&rn=" + rN;
	alert(make_XMLHttpRequest("../php/php_libVentas.php", final_getStr));
	ventas_display();
	}

function ventas_chName(id, type){
	if(type == "articulo"){
		var sucDate_getStr = "suc=" + document.getElementById("hidden_suc").value;
		var final_getStr = sucDate_getStr + "&action=cambia_nombre&art=" + id + "&new=" + prompt("Nuevo nombre:");
		make_XMLHttpRequest("../php/php_libVentas.php", final_getStr)
		ventas_display();
		}
	}