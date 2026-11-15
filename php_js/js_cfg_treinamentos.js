	function montaPesquisa(){
		document.getElementById("divFormulario").innerHTML = "";
		document.getElementById("divPesquisa").innerHTML = "<img src='imagens/loading.gif'>";
		varUrl = "../php_php/php_cfg_treinamentos.php?act=LISTA";
		var formData = {id:0}
		$.ajax({
			url: varUrl,
			type: "POST", 
			data : formData, 
			async : false, 
			success: function(response, textStatus, jqXHR) {
				document.getElementById("divPesquisa").innerHTML = response;				
			},
			error: function (jqXHR, textStatus, errorThrown) {
				exibe_mensagem("ERRO!", "Erro ao carregar as Pesquisas!", "error");				
			}
		});
	}

	function selecionaTreinamento(id){
		document.getElementById("modalNovoFormulario_pesquisaId").value = id;
		document.getElementById("divFormulario").innerHTML = "<img src='imagens/loading.gif'>";		
		varUrl = "../php_php/php_cfg_treinamentos.php?act=LISTA_TELAS&id="+id;
		var formData = {id:0}
		$.ajax({
			url: varUrl,
			type: "POST", 
			data : formData, 
			async : false, 
			success: function(response, textStatus, jqXHR) {				
				document.getElementById("divFormulario").innerHTML = response;				
			},
			error: function (jqXHR, textStatus, errorThrown) {
				exibe_mensagem("ERRO!", "Erro ao carregar as Pesquisas!", "error");				
			}
		});
	}



	montaPesquisa();