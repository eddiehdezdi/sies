function make_XMLHttpRequest(php_file, get_str){
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.open("GET", php_file + "?" + get_str, false);
	xmlhttp.send();
	return xmlhttp.responseText;
	}

function gen_setFullDate(){
	var getStr = "action=set_fullDate&date=" + document.getElementById("hidden_date").value;
	document.getElementById("text_date").value = make_XMLHttpRequest("../php/php_index.php", getStr);
	index_display();
	}