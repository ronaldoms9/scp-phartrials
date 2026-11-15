	function exibe_loading(msg){
		$.blockUI({ message: '<img src="imagens/carregando.gif" width="100">'+msg+'...', css: {
			border: 'none',
			padding: '15px',
			backgroundColor: '#000',
			'-webkit-border-radius': '10px',
			'-moz-border-radius': '10px',
			opacity: .5,
			color: '#fff'
		} });
	}
	function remove_loading(){
		$.unblockUI();
	}
	function exibe_mensagem(titulo, msg, tipo){
		if(tipo==""){
			Swal.fire({
			  title: titulo,
			  text: msg,
			  icon: "warning",
			  buttons: true,
			  dangerMode: true
			})
		}else{
			/*Swal.fire({
			  title: titulo,
			  text: msg	,

			})*/
			Swal.fire(titulo, msg, tipo);

		}
	}

	function mascara(t, mask){
		var i = t.value.length;
		var saida = mask.substring(1,0);
		var mascara = mask.substring(i)
		if(event.keyCode!=8){
			if(t.value.substr(i-1,1)!=mask.substr(i,1)){
				if (mascara.substring(0,1) != saida){
				t.value += mascara.substring(0,1);
				}
			}
		}
	}

	function exporta_tabela_excel(tabela){
		var tab_text="<table border='2px'><tr bgcolor='#87AFC6'>";
		var textRange; var j=0;
		tab = document.getElementById(tabela); // id of table

		for(j = 0 ; j < tab.rows.length ; j++) {
			tab_text=tab_text+tab.rows[j].innerHTML+"</tr>";
		}

		tab_text=tab_text+"</table>";
		tab_text= tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
		tab_text= tab_text.replace(/<img[^>]*>/gi,""); // remove if u want images in your table
		tab_text= tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params

		var ua = window.navigator.userAgent;
		var msie = ua.indexOf("MSIE ");

		if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
		{
			txtArea1.document.open("txt/html","replace");
			txtArea1.document.write(tab_text);
			txtArea1.document.close();
			txtArea1.focus();
			sa=txtArea1.document.execCommand("SaveAs",true,"export.xls");
		}
		else                 //other browser not tested on IE 11
			sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));

		return (sa);
	}