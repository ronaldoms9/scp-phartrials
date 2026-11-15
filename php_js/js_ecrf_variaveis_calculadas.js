	function exibirVariaveis(){
		document.location = "ecrf_variaveis_calculadas.php?exibe=sim";
	}
	function gerarVariaveis(){
		$.blockUI({ message: '<img src="imagens/carregando.gif" width="100">Aguarde, carregando informações...', css: {			
			border: 'none', 
			padding: '15px', 
			backgroundColor: '#000', 
			'-webkit-border-radius': '10px', 
			'-moz-border-radius': '10px', 
			opacity: .5, 
			color: '#fff' 
		} });  
		varUrl = "../php_php/php_ecrf_variaveis_calculadas.php?act=GERAR";
		//varUrl = "php_php/php_ecrf_variaveis_calculadas.php?act=GERAR";
		var formData = {}
		$.ajax({
			url: varUrl,
			type: "POST", 
			data : formData, 
			async : true, 
			success: function(response, textStatus, jqXHR) {								
				$.unblockUI();							
			},
			error: function (jqXHR, textStatus, errorThrown) {				
				$.unblockUI();
			}
		});
	}