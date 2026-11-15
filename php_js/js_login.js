	function exibeMensagem(titulo, msg){		
		var box = bootbox.alert({
			title: titulo,
			centerVertical: true,
			message: msg				
		});
	}
	$(document).ready(function() {
		$('#btnRegistro').click(function(){
			var ant			= document.getElementById("btnRegistro").innerHTML;
			document.getElementById("btnRegistro").innerHTML = "<img src='imagens/carregando.gif' width='50'>Enviando...";
			var nome 		= document.getElementById("form_sol_nome").value;
			var email 		= document.getElementById("form_sol_email").value;
			var cpf 		= document.getElementById("form_sol_cpf").value;
			var centro 		= document.getElementById("form_sol_centro").value;
			if(nome==""){exibeMensagem("ERRO", "Campo [NOME] não preenchido. Por favor, revise as informações");document.getElementById("btnRegistro").innerHTML = ant; return;}
			if(email==""){exibeMensagem("ERRO", "Campo [EMAIL] não preenchido. Por favor, revise as informações");document.getElementById("btnRegistro").innerHTML = ant; return;}
			if(cpf==""){exibeMensagem("ERRO", "Campo [CPF] não preenchido. Por favor, revise as informações");document.getElementById("btnRegistro").innerHTML = ant; return;}
			if(centro==""){exibeMensagem("ERRO", "Campo [CENTRO] não preenchido. Por favor, revise as informações");document.getElementById("btnRegistro").innerHTML = ant; return;}
			varUrl = "php_php/php_login.php?act=SOLICITA";
			var formData = {
				nome	: document.getElementById("form_sol_nome").value,
				email	: document.getElementById("form_sol_email").value,
				cpf		: document.getElementById("form_sol_cpf").value,
				centro	: document.getElementById("form_sol_centro").value
			}
			$.ajax({
				url: varUrl,
				type: "POST", 
				data : formData, 
				async : false, 
				success: function(response, textStatus, jqXHR) {					
					exibeMensagem("SUCESSO!", response);						
					document.getElementById("btnRegistro").innerHTML = ant;
				},
				error: function (jqXHR, textStatus, errorThrown) {
					exibeMensagem("ERRO!", "Erro ao processar sua solicitação!");	
					document.getElementById("btnRegistro").innerHTML = ant;
				}
			});
		});
		$('#btnResetar').click(function(){
			var ant			= document.getElementById("btnResetar").innerHTML;
			document.getElementById("btnResetar").innerHTML = "<img src='imagens/carregando.gif' width='50'>Enviando...";			
			var email 		= document.getElementById("form_reset_email").value;
			
			if(email==""){exibeMensagem("ERRO", "Campo [EMAIL] não preenchido. Por favor, revise as informações");document.getElementById("btnLogin").innerHTML = ant; return;}			
			varUrl = "php_php/php_login.php?act=RESETAR";
			var formData = {email	: document.getElementById("form_reset_email").value}
			$.ajax({
				url: varUrl,
				type: "POST", 
				data : formData, 
				async : false, 
				success: function(response, textStatus, jqXHR) {										
					exibeMensagem("SUCESSO!", response);									
					document.getElementById("btnResetar").innerHTML = ant;
				},
				error: function (jqXHR, textStatus, errorThrown) {
					exibeMensagem("ERRO!", "Erro ao processar sua solicitação!");	
					document.getElementById("btnResetar").innerHTML = ant;
				}
			});
		});
		$('#btnLogin').click(function(){
			var ant			= document.getElementById("btnLogin").innerHTML;
			document.getElementById("btnLogin").innerHTML = "<img src='imagens/carregando.gif' width='50'>Enviando...";			
			var usuario 		= document.getElementById("form_usuario").value;
			var senha 			= document.getElementById("form_senha").value;
			
			if(usuario==""){exibeMensagem("ERRO", "Campo [USUÁRIO] não preenchido. Por favor, revise as informações");document.getElementById("btnLogin").innerHTML = ant; return;}			
			if(senha==""){exibeMensagem("ERRO", "Campo [SENHA] não preenchido. Por favor, revise as informações");document.getElementById("btnLogin").innerHTML = ant; return;}			
			
			varUrl = "php_php/php_login.php?act=LOGIN";
			var formData = {usuario	: document.getElementById("form_usuario").value,
							senha   : document.getElementById("form_senha").value}
			$.ajax({
				url: varUrl,
				type: "POST", 
				data : formData, 
				async : false, 
				success: function(response, textStatus, jqXHR) {															
					if(response=="ERRO"){
						document.getElementById("divRet").style.display = 'block';
						document.getElementById("divResposta").innerHTML = response;
					}else{
						document.location = response;
					}
					document.getElementById("btnLogin").innerHTML = ant;
				},
				error: function (jqXHR, textStatus, errorThrown) {
					exibeMensagem("ERRO!", "Erro ao processar sua solicitação!");	
					document.getElementById("btnLogin").innerHTML = ant;
				}
			});
		});

	});