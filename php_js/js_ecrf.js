	baseUrl = "php_php/php_ecrf.php?";
	function exibeCRF(pac, centro){
		mostraCarregando();
		varUrl = baseUrl + "act=CRF_EXIBE";
		var formData = {varCentro : centro,varPart : pac}
		$.ajax({
			url: varUrl,
			type: "POST", 
			data : formData, 
			async : true, 
			success: function(response, textStatus, jqXHR) {				
				if(response.trim()=="OK"){
					document.location = "ecrf_formularios.php";
				}else{
					exibe_mensagem("ERRO!", "Erro ao carregar formulários!", "error");				
				}
			},
			error: function (jqXHR, textStatus, errorThrown) {
				exibe_mensagem("ERRO!", "Erro ao carregar formulários!", "error");				
			}
		});		
	}