	function limpaCampos(){
		document.getElementById("modalNovoCentro_nome").value = "";
		document.getElementById("modalNovoCentro_cidade").value = "";
		document.getElementById("modalNovoCentro_coordenador").value = "";
		document.getElementById("modalNovoCentro_email").value = "";
	}
	function incluirCentro(){
		limpaCampos();
		$("#modalNovoCentro").modal('show');		
	}
	function cadastraEXE(){
		document.getElementById("divCentros").innerHTML = "<img src='imagens/loading.gif'>";
		
		varUrl = "../php_php/php_cen_cadastro.php?act=CADASTRA";
		var formData = {
							nome: document.getElementById("modalNovoCentro_nome").value,
							cidade: document.getElementById("modalNovoCentro_cidade").value,
							coordenador: document.getElementById("modalNovoCentro_coordenador").value,
							email: document.getElementById("modalNovoCentro_email").value
		}
		$.ajax({
			url: varUrl,
			type: "POST", 
			data : formData, 
			async : false, 
			success: function(response, textStatus, jqXHR) {
				$("#modalNovoCentro").modal('hide');
				exibe_mensagem("Sucesso!", "Centro cadastrado com sucesso!", "success");				
				pesquisaCentros();
			},
			error: function (jqXHR, textStatus, errorThrown) {
				exibe_mensagem("ERRO!", "Erro ao cadastrar o centro!", "error");				
			}
		});
	}
	function pesquisaCentros(){
		document.getElementById("divCentros").innerHTML = "<img src='imagens/loading.gif'>";
		varUrl = "../php_php/php_cen_cadastro.php?act=LISTA";
		var formData = {id:0}
		$.ajax({
			url: varUrl,
			type: "POST", 
			data : formData, 
			async : false, 
			success: function(response, textStatus, jqXHR) {				
				document.getElementById("divCentros").innerHTML = response;
			},
			error: function (jqXHR, textStatus, errorThrown) {
				//exibe_mensagem("ERRO!", "Erro ao cadastrar o centro!", "error");				
			}
		});
	}
	pesquisaCentros();