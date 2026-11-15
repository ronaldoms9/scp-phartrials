	var base = "../php_php/php_agenda.php?";
	//var base = "php_php/php_agenda.php?";

	
	function enviado(id, valor){
		if(valor==true){temp = 1;}
		if(valor==false){temp = 0;}
		if (window.XMLHttpRequest) {
		xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				retorno = document.getElementById(id+"_enviado").innerHTML = this.responseText;
			}
		};
		xmlhttp.open("POST","ajax.php?act=AGENDA_MUDA_ENVIADO&id="+id+"&valor="+temp);
		xmlhttp.send();
	}
	function assinado(id, valor){
		if(valor==true){temp = 1;}
		if(valor==false){temp = 0;}
		if (window.XMLHttpRequest) {
		xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				retorno = document.getElementById(id+"_assinado").innerHTML = this.responseText;
			}
		};
		xmlhttp.open("POST","ajax.php?act=AGENDA_MUDA_ASSINADO&id="+id+"&valor="+temp);
		xmlhttp.send();
	}
	function respondido(id, valor){
		if(valor==true){temp = 1;}
		if(valor==false){temp = 0;}
		if (window.XMLHttpRequest) {
		xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {

			}
		};
		xmlhttp.open("POST","ajax.php?act=AGENDA_MUDA_RESPONDIDO&id="+id+"&valor="+temp);
		xmlhttp.send();
	}