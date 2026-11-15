
	function limparCampos(){
		document.getElementById("form_data").value = "";
		document.getElementById("form_qtd").value = "";
		document.getElementById("form_centro").value = "";
		document.getElementById("form_recebido_por").value = "";
	}
	function registrar(){		
		varUrl = "../php_php/php_med_saida.php?act=REGISTRAR";
		var formData = {
				data:document.getElementById("form_data").value, 								
				qtd:document.getElementById("form_qtd").value, 
				centro:document.getElementById("form_centro").value, 
				recebido_por:document.getElementById("form_recebido_por").value
				}
		$.ajax({
			url: varUrl,
			type: "POST", 
			data : formData, 
			async : false, 
			success: function(response, textStatus, jqXHR) {								
				alert(response);
				limparCampos();
			},
			error: function (jqXHR, textStatus, errorThrown) {				
			}
		});
	}