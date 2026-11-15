	var baseURL = "../php_php/php_dash_bio.php?";
	var baseURL = "php_php/php_dash_bio.php?";
	
	function muda(projeto){
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
	function filtro(){			
		document.getElementById("divRetorno").innerHTML = "<img src='imagens/loading.gif'>";
		varUrl = baseURL + "act=EXIBE";
		var formData = {
				patrocinador  : document.getElementById('form_patrocinador').value,
				finalizado  : document.getElementById('form_finalizado').value				
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
	function finalizar(id){		
		varUrl = baseURL + "act=FINALIZA";
		var formData = {
				projeto  : id
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
				filtro();
			}
		});
	}
	function reabrir(id){		
		varUrl = baseURL + "act=REABRE";
		var formData = {
				projeto  : id
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
				filtro();
			}
		});
	
	}