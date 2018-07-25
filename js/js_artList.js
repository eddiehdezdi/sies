function list_display(libinv, inst){
	var sucDate_getStr = "suc=" + document.getElementById("hidden_suc").value + "&date=" + document.getElementById("hidden_date").value;
	document.getElementById("div_indexArtList").innerHTML = make_XMLHttpRequest("../php/php_artList.php", "action=show_list&libinv=" + libinv + "&inst=" + inst + "&" + sucDate_getStr);
	document.getElementById("div_indexArtList").setAttribute("class", "css_list");
	}

function list_changeArt(libinv, inst){
	var sucDate_getStr = "suc=" + document.getElementById("hidden_suc").value + "&date=" + document.getElementById("hidden_date").value;
	var art = document.getElementById("list_artList").value;
	if(libinv == "lib") make_XMLHttpRequest("../php/php_artList.php", "action=make_libChange&inst=" + inst + "&art=" + art + "&" + sucDate_getStr);
	else make_XMLHttpRequest("../php/php_artList.php", "action=make_invChange&inst=" + inst + "&art=" + art + "&" + sucDate_getStr);
	document.getElementById("div_indexArtList").setAttribute("class", "css_noList");
	if (libinv == "lib") ventas_display();
	else {
		var htr = inst.split(",");
		map_showSub(htr[0], htr[1]);
		}
	}