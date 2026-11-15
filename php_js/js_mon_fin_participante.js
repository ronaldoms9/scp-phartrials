	baseUrl = "php_php/";
	
	function montaGrid(id){
		mostraCarregando();
		document.getElementById("tabela").innerHTML = "<img src='imagens/carregando.gif' width='85'>Carregando...";
		varUrl = baseUrl + "php_mon_fin_participante.php?act=PESQUISA";
		var formData = {varId:	id}
		$.ajax({
			url: varUrl,
			type: "POST", 
			data : formData, 
			async : true, 
			success: function(response, textStatus, jqXHR) {
				removeCarregando();
				document.getElementById("tabela").innerHTML = response;				
			},
			error: function (jqXHR, textStatus, errorThrown) {
				//exibe_mensagem("ERRO!", "Erro ao carregar os arquivos!", "error");				
				removeCarregando();
			}
		});		
	}
	function mudaStatus(id, status){
		mostraCarregando();		
		varUrl = baseUrl + "php_mon_fin_participante.php?act=MUDA_STATUS";
		var formData = {varId:	id,varStatus : status}
		$.ajax({
			url: varUrl,
			type: "POST", 
			data : formData, 
			async : true, 
			success: function(response, textStatus, jqXHR) {
				removeCarregando();
			},
			error: function (jqXHR, textStatus, errorThrown) {
				//exibe_mensagem("ERRO!", "Erro ao carregar os arquivos!", "error");				
				removeCarregando();
			}
		});		
	}
	function filtro(){
		mostraCarregando();
		document.getElementById("tabela_retorno").innerHTML = "<img src='imagens/carregando.gif' width='85'>Carregando...";
		var formData = {dataInicial : document.getElementById("form_data_inicial").value,
						dataFinal   : document.getElementById("form_data_final").value}
		varUrl = baseUrl + "php_mon_fin_cobranca.php?act=PESQUISA_GERADOS";
						
		$.ajax({
			url: varUrl,
			type: "POST", 
			data : formData, 
			async : true, 
			success: function(response, textStatus, jqXHR) {
				removeCarregando();
				document.getElementById("tabela_retorno").innerHTML = response;				
			},
			error: function (jqXHR, textStatus, errorThrown) {
				//exibe_mensagem("ERRO!", "Erro ao carregar os arquivos!", "error");				
				removeCarregando();
			}
		});		
	}
	function geraEspelho(data){
		popup("mon_fin_cobranca_espelho.php?data="+data);
	}
	