	/*function formSalvar(varRegistro){
		//document.getElementById("btnSalvar").innerHTML = "<img src='imagens/loading.gif' width='90'>";	
		var varAchado = document.getElementById("form_achado").value;		
		var formData = {
			registro		: varRegistro,
			agendamento		: document.getElementById("form_agendamentoId").value,
			pagina			: document.getElementById("form_pagina").value,
			trecho			: document.getElementById("form_trecho").value,
			critico			: document.getElementById("form_critico").value,
			achado			: varAchado,
			resposta		: document.getElementById("form_resposta").value,
			respondido_por	: document.getElementById("form_respondido_por").value,
			finalizado		: document.getElementById("form_finalizado").value
		}
		varUrl = "../php_php/php_agendamento_protocolo_edit.php?act=SALVA";
		$.ajax({
			url: varUrl,
			type: "POST", 
			data : formData, 
			async : false, 
			success: function(response, textStatus, jqXHR) {				
				exibe_mensagem("Sucesso!", "Centro cadastrado com sucesso!", "success");
				document.getElementById("form_agendamentoId").value
				
			},
			error: function (jqXHR, textStatus, errorThrown) {
				exibe_mensagem("ERRO!", "Erro ao cadastrar o centro!", "error");				
			}
		});
	}
	*/
    
	function deleta(indice, registro){
		Swal.fire({
		  title: '[CUIDADO]. Você tem certeza que quer deletar o registro [' + indice + ']?',
		  showDenyButton: true,
		  showCancelButton: false,
		  confirmButtonText: `Sim`,
		  denyButtonText: `Não`,
		}).then((result) => {
		  if (result.isConfirmed) {			
				var formData = {					varRegistro		: registro				}
				varUrl = "../php_php/php_agendamento_protocolo_edit.php?act=DELETA";
				$.ajax({
					url: varUrl,
					type: "POST", 
					data : formData, 
					async : false, 
					success: function(response, textStatus, jqXHR) {				
						alert("Sucesso! Registro deletado com sucesso!");						
						document.location.reload(true);
					},
					error: function (jqXHR, textStatus, errorThrown) {						
					}
				});
			
			
		  } else if (result.isDenied) {
			Swal.fire('Operação cancelada', '', 'info')
		  }
		})
	}
	function ExportarEXCEL(tabela){
		var tab_text="<table border='2px'><tr bgcolor='#87AFC6'>";
		var textRange; var j=0;
		tab = document.getElementById(tabela); // id of table

		console.log("Tamanho -> " + tab.rows.length);
		for(j = 0 ; j < tab.rows.length ; j++) {     
			tab_text=tab_text+tab.rows[j].innerHTML+"</tr>";
		}

		tab_text=tab_text+"</table>";
		tab_text= tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
		tab_text= tab_text.replace(/<img[^>]*>/gi,""); // remove if u want images in your table
		tab_text= tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params

		var ua = window.navigator.userAgent;
		var msie = ua.indexOf("MSIE "); 

		if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)){      // If Internet Explorer
			txtArea1.document.open("txt/html","replace");
			txtArea1.document.write(tab_text);
			txtArea1.document.close();
			txtArea1.focus(); 
			sa=txtArea1.document.execCommand("SaveAs",true,"Say Thanks to Sumit.xls");
		}  
		else                 //other browser not tested on IE 11
			sa = window.open('data:application/vnd.ms-excel,' + escape(tab_text));  
		return (sa);
	}
		   
	function pdf(){
		var centro = prompt("Qual o centro a imprimir?", "Escolha o centro");
		popup('rep_pendencias2.php?centro='+centro);
	}
	
	function popup(url){
		params  = 'width=700';
		params += ', height=500';
		params += ', top=0, left=0'
		params += ', fullscreen=yes';

		newwin=window.open(url,'windowname4', params);
		if (window.focus) {newwin.focus()}
		return false;
	}