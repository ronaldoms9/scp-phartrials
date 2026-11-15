	function incluirPesquisa(){		
		$("#modalNovaPesquisa").modal('show');		
	}
	function cadastraPesquisaEXE(varCentro, varPesquisa){
		varUrl = "../php_php/php_pesq_centro_pesquisas.php?act=CADASTRA";
		var formData = {
				centro	: varCentro,
				pesquisa: varPesquisa
		}
		$.ajax({
			url: varUrl,
			type: "POST", 
			data : formData, 
			async : false, 
			success: function(response, textStatus, jqXHR) {				
				$("#modalNovaPesquisa").modal('hide');
				listaPesquisas(varCentro);
				exibe_mensagem("Sucesso!", "Centro cadastrado com sucesso!", "success");								
			},
			error: function (jqXHR, textStatus, errorThrown) {
				exibe_mensagem("ERRO!", "Erro ao cadastrar o centro!", "error");				
			}
		});
	}
	function listaPesquisas(varCentro){
		varUrl = "../php_php/php_pesq_centro_pesquisas.php?act=LISTA_PESQUISAS";		
		var formData = {
			centro	: varCentro				
		}
		$.ajax({
			url: varUrl,
			type: "POST", 
			data : formData, 
			async : false, 
			success: function(response, textStatus, jqXHR) {
				document.getElementById("tablePesquisas").innerHTML = response;
			},
			error: function (jqXHR, textStatus, errorThrown) {
				exibe_mensagem("ERRO!", "ERRO!", "error");				
			}
		});
	}
	function selecionaPesq(varId, varCentro){
		
		document.getElementById("divListaForm").style.display = 'block'; 
		varUrl = "../php_php/php_pesq_centro_pesquisas.php?act=LISTA_FORMULARIOS";		
		var formData = {
			id	: varId,
			centro	:	varCentro
		}
		$.ajax({
			url: varUrl,
			type: "POST", 
			data : formData, 
			async : false, 
			success: function(response, textStatus, jqXHR) {
				document.getElementById("tableForms").innerHTML = response;
			},
			error: function (jqXHR, textStatus, errorThrown) {
				exibe_mensagem("ERRO!", "ERRO!", "error");				
			}
		});
	}
	function selecionaForm(varId){
		
		document.getElementById("divListaCampos").style.display = 'block'; 
		document.getElementById("tableCampos").innerHTML = "<img src='imagens/loading.gif'>";		
		varUrl = "../php_php/php_pesq_centro_pesquisas.php?act=LISTA_CAMPOS";		
		var formData = {
			id	: varId				
		}
		$.ajax({
			url: varUrl,
			type: "POST", 
			data : formData, 
			async : false, 
			success: function(response, textStatus, jqXHR) {
				console.log(response);
				document.getElementById("tableCampos").innerHTML = response;
			},
			error: function (jqXHR, textStatus, errorThrown) {
				exibe_mensagem("ERRO!", "ERRO!", "error");				
			}
		});
	}
	function enviaEmail(varPesquisa, varCentro){
		Swal.fire({
		  title: 'Tem certeza que quer enviar o email com essa pesquisa?',
		  showDenyButton: true,
		  showCancelButton: false,
		  confirmButtonText: `ENVIAR`,
		  denyButtonText: `[CANCELAR]`,
		}).then((result) => {		  
		  if (result.isConfirmed) {
			
			varUrl = "../php_php/php_pesq_centro_pesquisas.php?act=ENVIA_EMAIL";		
			var formData = {
				centro	: varCentro,
				pesquisa: varPesquisa
			}
			$.ajax({
				url: varUrl,
				type: "POST", 
				data : formData, 
				async : false, 
				success: function(response, textStatus, jqXHR) {
					console.log(response);					
					exibe_mensagem("Sucesso!", "Email enviado com sucesso!", "info");
				},
				error: function (jqXHR, textStatus, errorThrown) {
					exibe_mensagem("ERRO!", "ERRO!", "error");				
				}
			});
			
			
		  } else if (result.isDenied) {
			Swal.fire('Email cancelado', '', 'info')
		  }
		})
	}