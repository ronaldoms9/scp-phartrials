	function montaPesquisa(){
		document.getElementById("divFormulario").innerHTML = "";
		document.getElementById("divPesquisa").innerHTML = "<img src='imagens/loading.gif'>";
		varUrl = "../php_php/php_cfg_cad_pesquisa.php?act=LISTA_PESQUISA";
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
	function selecionaPesq(id){
		document.getElementById("modalNovoFormulario_pesquisaId").value = id;
		document.getElementById("divFormulario").innerHTML = "<img src='imagens/loading.gif'>";		
		varUrl = "../php_php/php_cfg_cad_pesquisa.php?act=LISTA_FORMULARIOS&id="+id;
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
	function deletaPesq(varId){		
		varUrl = "../php_php/php_cfg_cad_pesquisa.php?act=DELETA_PESQUISA&id="+varId;
		var formData = {id:varId}
		$.ajax({
			url: varUrl,
			type: "POST", 
			data : formData, 
			async : false, 
			success: function(response, textStatus, jqXHR) {
				exibe_mensagem("Sucesso!", "Pesquisa deletada com sucesso!", "success");				
				montaPesquisa();
			},
			error: function (jqXHR, textStatus, errorThrown) {
				exibe_mensagem("ERRO!", "Erro ao carregar as Pesquisas!", "error");				
			}
		});
	}
	function novaPesquisa(){
		document.getElementById("form_modalNovaPesquisa_pesquisaId").value = "";
		document.getElementById("form_modalNovaPesquisa_nome").value = "";				
		$("#modalNovaPesquisa").modal('show');		
	}
	function novaPesquisaEXE(){
		varNome = document.getElementById("form_modalNovaPesquisa_nome").value;
		if(varNome!=""){
			varUrl = "../php_php/php_cfg_cad_pesquisa.php?act=CAD_PESQUISA&pesquisaid="+document.getElementById("form_modalNovaPesquisa_pesquisaId").value;
			console.log(varUrl);
			var formData = {nome:varNome}
			$.ajax({
				url: varUrl,
				type: "POST", 
				data : formData, 
				async : false, 
				success: function(response, textStatus, jqXHR) {
					console.log(response);
					$("#modalNovaPesquisa").modal('hide');
					exibe_mensagem("Sucesso", "Pesquisa cadastrada com sucesso!", "success");
					montaPesquisa();
				},
				error: function (jqXHR, textStatus, errorThrown) {
					exibe_mensagem("ERRO!", "Erro ao carregar as Pesquisas!", "error");				
				}
			});
		}
	}
	
	
	function novoFormulario(){		
		document.getElementById("modalNovoFormulario_nome").value = "";				
		$("#modalNovoFormulario").modal('show');		
	}
	function novoFormularioEXE(){
		varNome = document.getElementById("modalNovoFormulario_nome").value;
		if(varNome!=""){
			varUrl = "../php_php/php_cfg_cad_pesquisa.php?act=CADASTRA_FORMULARIO&pesquisaid="+document.getElementById("modalNovoFormulario_pesquisaId").value;
			console.log(varUrl);
			var formData = {nome:varNome}
			$.ajax({
				url: varUrl,
				type: "POST", 
				data : formData, 
				async : false, 
				success: function(response, textStatus, jqXHR) {					
					$("#modalNovoFormulario").modal('hide');
					exibe_mensagem("Sucesso", "Pesquisa cadastrada com sucesso!", "success");
					selecionaPesq(document.getElementById("modalNovoFormulario_pesquisaId").value);
				},
				error: function (jqXHR, textStatus, errorThrown) {
					exibe_mensagem("ERRO!", "Erro ao carregar as Pesquisas!", "error");				
				}
			});
		}
	}
	function deletaForm(varId){		
		varUrl = "../php_php/php_cfg_cad_pesquisa.php?act=DELETA_FORMULARIO&id="+varId;
		var formData = {id:varId}
		$.ajax({
			url: varUrl,
			type: "POST", 
			data : formData, 
			async : false, 
			success: function(response, textStatus, jqXHR) {
				exibe_mensagem("Sucesso!", "Formulário deletada com sucesso!", "success");				
				selecionaPesq(response);
			},
			error: function (jqXHR, textStatus, errorThrown) {
				exibe_mensagem("ERRO!", "Erro ao carregar as Pesquisas!", "error");				
			}
		});
	}
	
	function selecionaFormulario(id){
		limpaCampos();
		habilitaDesabilita(false);
		document.getElementById("formFormularioId").value = id;		
		document.getElementById("divCampos").innerHTML = "<img src='imagens/loading.gif'>";		
		varUrl = "../php_php/php_cfg_cad_pesquisa.php?act=LISTA_CAMPOS&id="+id;
		var formData = {id:0}
		$.ajax({
			url: varUrl,
			type: "POST", 
			data : formData, 
			async : false, 
			success: function(response, textStatus, jqXHR) {				
				document.getElementById("divCampos").innerHTML = response;				
			},
			error: function (jqXHR, textStatus, errorThrown) {
				exibe_mensagem("ERRO!", "Erro ao carregar as Pesquisas!", "error");				
			}
		});
	}
	
	function limpaCampos(){
		document.getElementById("form_selecao_valores").value = "";
		
		document.getElementById("form_so_leitura").checked = false;
		document.getElementById("form_so_letra").checked = false;
		document.getElementById("form_so_numero").checked = false;
		document.getElementById("form_obrigatorio").checked = false;
		
		document.getElementById("form_legenda").value = "";
		document.getElementById("form_formato").value = "";
		document.getElementById("form_texto_en").value = "";
		document.getElementById("form_texto").value = "";
		document.getElementById("form_nome").value = "";
		document.getElementById("form_tipo").value = "";
		document.getElementById("form_ordem").value = "";
		
	}
	function habilitaDesabilita(oquefazer){
		if(oquefazer==true){oquefazer=false;}else{oquefazer=true;}
		document.getElementById("form_selecao_valores").disabled = oquefazer;
		
		document.getElementById("form_so_leitura").disabled = oquefazer;
		document.getElementById("form_so_letra").disabled = oquefazer;
		document.getElementById("form_so_numero").disabled = oquefazer;
		document.getElementById("form_obrigatorio").disabled = oquefazer;
		
		document.getElementById("form_legenda").disabled = oquefazer;
		document.getElementById("form_formato").disabled = oquefazer;
		document.getElementById("form_texto_en").disabled = oquefazer;
		document.getElementById("form_texto").disabled = oquefazer;
		document.getElementById("form_nome").disabled = oquefazer;
		document.getElementById("form_tipo").disabled = oquefazer;
		document.getElementById("form_ordem").disabled = oquefazer;
	}
	function novoCampo(){
		limpaCampos();
		habilitaDesabilita(true);		
	}
	function cancelar(){
		limpaCampos();
		habilitaDesabilita(false);
	}
	function salvarCampo(id){						
		varUrl = "../php_php/php_cfg_cad_pesquisa.php?act=SALVA_CAMPO";
		var formData = {formId: document.getElementById("formFormularioId").value,
						form_ordem: document.getElementById("form_ordem").value,
						form_tipo: document.getElementById("form_tipo").value,
						form_nome: document.getElementById("form_nome").value,
						form_texto: document.getElementById("form_texto").value,
						form_texto_en: document.getElementById("form_texto_en").value,
						form_formato: document.getElementById("form_formato").value,
						form_legenda: document.getElementById("form_legenda").value,						
						form_obrigatorio: document.getElementById("form_obrigatorio").checked,						
						form_selecao_valores: document.getElementById("form_selecao_valores").value
						}
		$.ajax({
			url: varUrl,
			type: "POST", 
			data : formData, 
			async : false, 
			success: function(response, textStatus, jqXHR) {				
				if(response=="OK"){
					exibe_mensagem("Sucesso!", "Campo criado com sucesso", "sucess");
					selecionaFormulario(document.getElementById("formFormularioId").value);
				}else{
					exibe_mensagem("ERRO!", "Algum erro aconteceu ao criar o campo", "error");
				}
			},
			error: function (jqXHR, textStatus, errorThrown) {
				exibe_mensagem("ERRO!", "Erro ao carregar as Pesquisas!", "error");				
			}
		});
	}
	habilitaDesabilita(false);
	montaPesquisa();