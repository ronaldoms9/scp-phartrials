	var baseURL = "../php_php/php_dash_revisao.php?";
	var baseURL = "php_php/php_dash_revisao.php?";
	function filtro(){			
		document.getElementById("divRetorno").innerHTML = "<img src='imagens/loading.gif'>";
		varUrl = baseURL + "act=EXIBE";
		var formData = {
				centro  : document.getElementById('form_centro').value,
				dataIn  : document.getElementById('form_data_inicial').value,
				dataFi  : document.getElementById('form_data_final').value,
				reviso  : document.getElementById('form_revisor').value,
				liberado: document.getElementById('form_liberado').value,
				tipo: document.getElementById('form_tipo').value,
				varstatus	: document.getElementById('form_status').value
				}
		$.ajax({
			url: varUrl,
			type: "POST", 
			data : formData, 
			async : true, 
			success: function(response, textStatus, jqXHR) {
				document.getElementById("divRetorno").innerHTML = response;				
			},
			error: function (jqXHR, textStatus, errorThrown) {
				exibe_mensagem("ERRO!", "Erro ao carregar as Pesquisas!", "error");				
			}
		});
	}
	function mudaStatus(tipo, projeto, status){
		var varUrl = baseURL + "act=MUDA_STATUS";
		var formData = {
			varTipo: tipo,
			varProj: projeto,
			varStatus: status
		}
		$.ajax({
			url: varUrl,
			type: "POST", 
			data : formData, 
			async : true, 
			success: function(response, textStatus, jqXHR) {
				filtro();
			},
			error: function (jqXHR, textStatus, errorThrown) {
				exibe_mensagem("ERRO!", "Erro ao salvar a mudança de status!", "error");				
			}
		});
	}
	function abreLinha(projeto){
		if(projeto>0){
			var situacaoAtual = document.getElementById("projeto_"+projeto).style.display;
			if(situacaoAtual=="block"){ document.getElementById("projeto_"+projeto).style.display = 'none'; }else{
				document.getElementById("projeto_"+projeto).style.display = 'block';
			}
		}else{
			//Espandir TODOS os divs			
			for(i=50;i<500;i++){			
				try{
					var situacaoAtual = document.getElementById("projeto_"+i).style.display;
					if(situacaoAtual=="block"){ document.getElementById("projeto_"+i).style.display = 'none'; }else{
						document.getElementById("projeto_"+i).style.display = 'block';
					}			
				}catch(err){}			
			}			
		}
	}