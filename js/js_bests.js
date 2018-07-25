function bests_drawTable(){
	var suc = document.getElementById("select_sucList").value;
	if (suc != "no"){
		var y = document.getElementById("select_year").value;
		var get_str = "action=drawTable&suc=" + suc + "&y=" + y;
		document.getElementById("div_drawArea").innerHTML = make_XMLHttpRequest("../php/php_bests.php", get_str);
		}
	}

function bests_g(){
	var get_str = "action=generate";
		document.getElementById("div_drawArea").innerHTML = make_XMLHttpRequest("../php/php_bests.php", get_str);
	}