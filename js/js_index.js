function index_display(){
	if (document.getElementById("hidden_suc").value != ""){
		var sucDate_getStr = "suc=" + document.getElementById("hidden_suc").value + "&date=" + document.getElementById("hidden_date").value;
		if (document.getElementById("hidden_show").value == "lib"){
			if (make_XMLHttpRequest("../php/php_index.php", "action=exists_reg&type=lib&" + sucDate_getStr) == "true"){
				switch (document.getElementById("hidden_display").value){
					case "ventas": ventas_display(); break;
					case "es": es_display(); break;
					case "cortes": cortes_display(); break;
					}
				}
			else document.getElementById("div_subCont").innerHTML = "<br/><br/>" + make_XMLHttpRequest("../php/php_index.php", "action=show_createButton&" + sucDate_getStr);
			}
		if (document.getElementById("hidden_show").value == "inv") {
			if (make_XMLHttpRequest("../php/php_index.php", "action=exists_reg&type=inv&" + sucDate_getStr) == "true"){
				switch (document.getElementById("hidden_display").value){
					case "mapeo": mapeo_display(); break;
					case "chequeo": chequeo_display(); break;
					case "reporte": index_gotoReport(); break;
					}
				}
			else document.getElementById("div_subCont").innerHTML = "<br/><br/><center>No hay inventario en esta fecha</center>";
			}
		}
	}

function index_menu(){ 
	//mostrar el menú de libreta o de inventario
	var getStr = "show=" + document.getElementById("hidden_show").value;
	document.getElementById("div_subMenu").innerHTML = make_XMLHttpRequest("../php/php_index.php", "action=show_subMenu&" + getStr);
	index_display();
	}

function index_selectSL(){
	var sl = document.getElementById("select_sucList").value;
	if (sl == "nueva"){
		sl = prompt("Nombre de nueva sucursal:");
		sl = make_XMLHttpRequest("../php/php_index.php", "action=new_suc&suc=" + sl);
		document.getElementById("span_sucList").innerHTML = make_XMLHttpRequest("../php/php_index.php", "action=show_sL");
		document.getElementById("select_sucList").selectedIndex = sl;
		sl = document.getElementById("select_sucList").value;
		}
	document.getElementById("hidden_suc").value = sl;
	//document.getElementById("button_delSuc").disabled = false;
	document.getElementById("hidden_show").value = "lib";
	document.getElementById("hidden_display").value = "ventas";
	index_menu();
	}

function index_setDate(type){
	var getStr = "";
	switch (type){
		case "today": 
			document.getElementById("hidden_date").value = make_XMLHttpRequest("../php/php_index.php", "action=get_today");
			break;
		case "user": 
			var date = document.getElementById("hidden_date").value.split("-");
			cal_drawMonth(date[1], date[0]);
			break;
		case "previous":
			getStr = "action=arrow&dir=prev&date=" + document.getElementById("hidden_date").value;
			document.getElementById("hidden_date").value = make_XMLHttpRequest("../php/php_index.php", getStr);
			break;
		case "next":
			getStr = "action=arrow&dir=next&date=" + document.getElementById("hidden_date").value;
			document.getElementById("hidden_date").value = make_XMLHttpRequest("../php/php_index.php", getStr);
			break;
		}
	if (type != "user") gen_setFullDate();
	index_display();
	}

function index_setShow(type){
	document.getElementById("hidden_show").value = type;
	if (type == "lib") document.getElementById("hidden_display").value = "ventas";
	else document.getElementById("hidden_display").value = "mapeo";
	index_menu();
	}

function index_init(){
	document.getElementById("span_sucList").innerHTML = make_XMLHttpRequest("../php/php_index.php", "action=show_sL");
	index_setDate("today");
	}

function index_delSuc(){
	//pendiente
	}

function index_setSubMenu(n){
	var display = "";
	if (document.getElementById("hidden_show").value == "lib"){
		switch(n){
			case "0": display = "ventas"; break;
			case "1": display = "es"; break;
			case "2": display = "cortes"; break;
			}
		}
	else {
		switch(n){
			case "0": display = "mapeo"; break;
			case "1": display = "chequeo"; break;
			case "2": display = "reporte"; break;
			}
		}
	document.getElementById("hidden_display").value = display;
	index_display();
	}

function index_crearHoja(){
	var sucDate_getStr = "suc=" + document.getElementById("hidden_suc").value + "&date=" + document.getElementById("hidden_date").value;
	make_XMLHttpRequest("../php/php_index.php", "action=crear_hojaLib&" + sucDate_getStr);
	index_display();
	}

function index_gotoReport(){
	var sucDate_getStr = "suc=" + document.getElementById("hidden_suc").value + "&date=" + document.getElementById("hidden_date").value;
	window.open("reporte.php?" + sucDate_getStr);
	}

function index_ordenaTiendas(){
	make_XMLHttpRequest("../php/php_index.php", "action=ordena_tiendas");
	index_init();
	}