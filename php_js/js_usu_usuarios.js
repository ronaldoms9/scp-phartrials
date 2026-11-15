	function listaDivUsuarios(){
		document.getElementById("divListagem").innerHTML = "<img src='imagens/loading.gif' width='150'>";
		varUrl = "../php_php/php_usu_usuarios.php?act=LISTA_USUARIO";
		var formData = {}
		$.ajax({
			url: varUrl,
			type: "POST", 
			data : formData, 
			async : false, 
			success: function(response, textStatus, jqXHR) {				
				document.getElementById("divListagem").innerHTML = response;
				//pesquisaCentros();
			},
			error: function (jqXHR, textStatus, errorThrown) {
				exibe_mensagem("ERRO!", "Erro ao cadastrar o centro!", "error");				
			}
		});
	}
	function selecionaUsuario(varId){
		document.getElementById("divDados").innerHTML = "<img src='imagens/loading.gif' width='150'>";
		varUrl = "../php_php/php_usu_usuarios.php?act=MOSTRA_USUARIO";
		var formData = {id: varId}
		$.ajax({
			url: varUrl,
			type: "POST", 
			data : formData, 
			async : false, 
			success: function(response, textStatus, jqXHR) {				
				document.getElementById("divDados").innerHTML = response;
				//pesquisaCentros();
			},
			error: function (jqXHR, textStatus, errorThrown) {
				exibe_mensagem("ERRO!", "Erro ao cadastrar o centro!", "error");				
			}
		});
	}
	function limparCampos(){
		document.getElementById("form_id").value = "";
		document.getElementById("form_usuario").value = "";
		document.getElementById("form_senha").value = "";
		document.getElementById("form_nome").value = "";
		document.getElementById("form_email").value = "";
		document.getElementById("form_tipo_usuario").value = "";
		document.getElementById("form_centro").value = "";
		document.getElementById("form_patrocinador").value = "";
		document.getElementById("form_descricao").value = "";
		document.getElementById("form_ativo").checked = false;
		document.getElementById("form_troca_senha").checked = false;
		document.getElementById("form_envia_email").checked = false;
	}
	function novoUsuario(){
		limparCampos();
	}
	
	function salvarDados(){
		varId 			= document.getElementById("form_id").value;
		varUsuario 		= document.getElementById("form_usuario").value;
		varSenha		= document.getElementById("form_senha").value;
		varNome			= document.getElementById("form_nome").value;
		varEmail		= document.getElementById("form_email").value;
		varTipo			= document.getElementById("form_tipo_usuario").value;
		varCentro		= document.getElementById("form_centro").value;
		varPatrocinador = document.getElementById("form_patrocinador").value;
		varDescricao	= document.getElementById("form_descricao").value;
		varAtivo		= document.getElementById("form_ativo").checked;
		varTroca		= document.getElementById("form_troca_senha").checked;
		varEnviaEmail	= document.getElementById("form_envia_email").checked;
		
		varUrl = "../php_php/php_usu_usuarios.php?act=SALVA_USUARIO";		
		var formData = {id			 :  varId,
						usuario		 :	varUsuario,
						senha  		 :	varSenha,
						nome   		 :	varNome,
						email  		 :	varEmail,
						tipo   		 :	varTipo,
						centro 		 :  varCentro,
						patrocinador :  varPatrocinador,
						descricao    :  varDescricao,
						ativo		 :  varAtivo,
						troca		 :  varTroca,
						enviaEmail   :  varEnviaEmail
					}
		$.ajax({
			url: varUrl,
			type: "POST", 
			data : formData, 
			async : false, 
			success: function(response, textStatus, jqXHR) {				
				document.getElementById("divDados").innerHTML = response;
				//pesquisaCentros();
			},
			error: function (jqXHR, textStatus, errorThrown) {
				exibe_mensagem("ERRO!", "Erro ao cadastrar o centro!", "error");				
			}
		});
		
	}
	
	
	listaDivUsuarios();