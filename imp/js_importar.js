function imp_importarList(){
	var dateStr = document.getElementById("text_listDate").value;
	if (dateStr == "") dateStr = "existencias";
	var sucDate_getStr = "suc=" + document.getElementById("text_listSuc").value + "&date=" + dateStr;
	alert(make_XMLHttpRequest("php_importar.php", "action=importar_list&" + sucDate_getStr));
	}

function imp_importarInv(){
	var sucDate_getStr = "suc=" + document.getElementById("text_invSuc").value + "&date=" + document.getElementById("text_invDate").value;
	alert(make_XMLHttpRequest("php_importar.php", "action=importar_inv&" + sucDate_getStr));
	}

function imp_importarLib(){
	var sucDate_getStr = "suc=" + document.getElementById("text_libSuc").value + "&inicia=" + document.getElementById("text_libInicia").value + "&termina=" + document.getElementById("text_libTermina").value;
	alert(make_XMLHttpRequest("php_importar.php", "action=importar_lib&" + sucDate_getStr));
	}