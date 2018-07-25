function info_regresar(date){
	if (opener.document.getElementById("hidden_display").value != "mapeo"){
		opener.document.getElementById("hidden_date").value = date;
		opener.gen_setFullDate();
		opener.document.getElementById("hidden_show").value = "inv";
		}
	opener.document.getElementById("hidden_display").value = "chequeo";
	opener.index_menu();
	}

function info_gotoMap(date){
	if (opener.document.getElementById("hidden_display").value != "chequeo"){
		opener.document.getElementById("hidden_date").value = date;
		opener.gen_setFullDate();
		opener.document.getElementById("hidden_show").value = "inv";
		}
	opener.document.getElementById("hidden_display").value = "mapeo";
	opener.index_menu();
	}

function info_gotoLib(date){
	opener.document.getElementById("hidden_date").value = date;
	opener.gen_setFullDate();
	opener.document.getElementById("hidden_show").value = "lib";
	opener.document.getElementById("hidden_display").value = "ventas";
	opener.index_menu();
	}