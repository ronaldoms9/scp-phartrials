	function mostraPendencias(varAgendamento){
		document.getElementById("retornoAchados").innerHTML = "<img src='imagens/loading.gif' width='150'>";
		varUrl = "php_php/php_agendamento_protocolo_achados.php?act=EXIBE";
		var formData = {agendamento: varAgendamento}
		$.ajax({
			url: varUrl,
			type: "POST", 
			data : formData, 
			async : true, 
			success: function(response, textStatus, jqXHR) {								
				document.getElementById('retornoAchados').innerHTML = response;
			},
			error: function (jqXHR, textStatus, errorThrown) {				
			}
		});
	}
	function mudaCima(varAchado, varAgendamento){
		document.getElementById("retornoAchados").innerHTML = "<img src='imagens/loading.gif' width='150'>";
		varUrl = "php_php/php_agendamento_protocolo_achados.php?act=MUDA_CIMA";
		var formData = {agendamento: varAgendamento,
						achado: varAchado}
		$.ajax({
			url: varUrl,
			type: "POST", 
			data : formData, 
			async : true, 
			success: function(response, textStatus, jqXHR) {								
				mostraPendencias(varAgendamento);
			},
			error: function (jqXHR, textStatus, errorThrown) {				
			}
		});
	}
	function mudaBaixo(varAchado, varAgendamento){
		document.getElementById("retornoAchados").innerHTML = "<img src='imagens/loading.gif' width='150'>";
		varUrl = "php_php/php_agendamento_protocolo_achados.php?act=MUDA_BAIXO";
		var formData = {agendamento: varAgendamento,
						achado: varAchado}
		$.ajax({
			url: varUrl,
			type: "POST", 
			data : formData, 
			async : true, 
			success: function(response, textStatus, jqXHR) {								
				mostraPendencias(varAgendamento);
			},
			error: function (jqXHR, textStatus, errorThrown) {				
			}
		});
	}