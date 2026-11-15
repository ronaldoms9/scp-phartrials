
	function limparCampos(){
		document.getElementById("form_data").value = "";
		document.getElementById("form_lote").value = "";
		document.getElementById("form_validade").value = "";
		document.getElementById("form_qtd").value = "";
		document.getElementById("form_recebido_por").value = "";
	}
	function registrar(){		
		varUrl = "../php_php/php_med_entrada.php?act=REGISTRAR";
		var formData = {
				data:document.getElementById("form_data").value, 
				lote:document.getElementById("form_lote").value, 
				validade:document.getElementById("form_validade").value, 
				qtd:document.getElementById("form_qtd").value, 
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