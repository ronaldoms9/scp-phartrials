	baseUrl = "../php_php/"
	baseUrl = "php_php/";
	function exportar(tipo){
		mostraCarregando();
		varUrl = "php_php/exportacoes.php?act=MONITORIA_CHECK_LIST";
		var formData = {fase:	document.getElementById("form_fase").value,
						grupo  : document.getElementById("form_grupo").value,
						periodo  : document.getElementById("form_periodo").value,
						participante: document.getElementById("form_participante").value,
						sexo: document.getElementById("form_sexo").value,
						status: document.getElementById("form_status").value,
						varTipo: tipo}
		$.ajax({
			url: varUrl,
			type: "POST",
			data : formData,
			async : true,
			success: function(response, textStatus, jqXHR) {
				removeCarregando();
				window.location.href = "php_php/export.xlsx";
			},
			error: function (jqXHR, textStatus, errorThrown) {
				//exibe_mensagem("ERRO!", "Erro ao carregar os arquivos!", "error");
				removeCarregando();
			}
		});

		//removeCarregando();
	}
	function exportaV2(tipo){
		mostraCarregando();
		varUrl = "php_php/exportacoes.php?act=MONITORIA_CHECK_LIST_V2_2";
		var formData = {fase:	document.getElementById("form_fase").value,
						grupo  : document.getElementById("form_grupo").value,
						participante: document.getElementById("form_participante").value,
						sexo: document.getElementById("form_sexo").value,
						status: document.getElementById("form_status").value,
						varTipo: tipo}
		$.ajax({
			url: varUrl,
			type: "POST",
			data : formData,
			async : true,
			success: function(response, textStatus, jqXHR) {
				removeCarregando();
				window.location.href = "php_php/export.xlsx";
			},
			error: function (jqXHR, textStatus, errorThrown) {
				//exibe_mensagem("ERRO!", "Erro ao carregar os arquivos!", "error");
				removeCarregando();
			}
		});

		//removeCarregando();
	}

	function inclui(){
		varUrl = baseUrl + "php_mon_checklist.php?act=INCLUI";
		document.getElementById("form_grupo").value = "";
		var formData = {}
		$.ajax({
			url: varUrl,
			type: "POST",
			data : formData,
			async : true,
			success: function(response, textStatus, jqXHR) {
				pesquisa();
			},
			error: function (jqXHR, textStatus, errorThrown) {
			}
		});
	}
	function incluiV2(){
		varUrl = baseUrl + "php_mon_checklist.php?act=INCLUI_V2";
		document.getElementById("form_grupo").value = "";
		var formData = {}
		$.ajax({
			url: varUrl,
			type: "POST",
			data : formData,
			async : true,
			success: function(response, textStatus, jqXHR) {
				pesquisa();
			},
			error: function (jqXHR, textStatus, errorThrown) {
			}
		});
	}
	function deletaPart_v2(hash){
		Swal.fire({
			title: 'Tem certeza que deseja EXCLUIR esse participante?',
			showDenyButton: false,
			showCancelButton: true,
			confirmButtonText: `Deletar - [CUIDADO]`,
			cancelButtonText: `Cancelar`,
		  }).then((result) => {
			if (result.isConfirmed) {
			  varUrl = baseUrl + "php_mon_checklist.php?act=DELETA_V2&hash="+hash;
			  var formData = {hash:hash				}
			  $.ajax({
				  url: varUrl,
				  type: "POST",
				  data : formData,
				  async : false,
				  success: function(response, textStatus, jqXHR) {
					  exibe_mensagem("Sucesso!", "Registro deletado com sucesso!", "success");
					  pesquisa();
				  },
				  error: function (jqXHR, textStatus, errorThrown) {
					  exibe_mensagem("ERRO!", "Erro ao deletar o registro!", "error");
					  pesquisa();
				  }
			  });
			} else if (result.isDenied) {
			  Swal.fire('Exclusão cancelada', '', 'info')
			}
		  })
	}
	function deletaPart(id){
		Swal.fire({
		  title: 'Tem certeza que deseja EXCLUIR esse participante?',
		  showDenyButton: false,
		  showCancelButton: true,
		  confirmButtonText: `Deletar - [CUIDADO]`,
		  cancelButtonText: `Cancelar`,
		}).then((result) => {
		  if (result.isConfirmed) {
			varUrl = baseUrl + "php_mon_checklist.php?act=DELETA&id="+id;
			var formData = {id:id				}
			$.ajax({
				url: varUrl,
				type: "POST",
				data : formData,
				async : false,
				success: function(response, textStatus, jqXHR) {
					exibe_mensagem("Sucesso!", "Registro deletado com sucesso!", "success");
					pesquisa();
				},
				error: function (jqXHR, textStatus, errorThrown) {
					exibe_mensagem("ERRO!", "Erro ao deletar o registro!", "error");
					pesquisa();
				}
			});
		  } else if (result.isDenied) {
			Swal.fire('Exclusão cancelada', '', 'info')
		  }
		})
	}
	function salvaFUp(){
		var varTexto = document.getElementById('txtFollowUp').value;

		Swal.fire({
		  title: 'Tem certeza que deseja SALVAR as informaÃ§Ãµes?',
		  showDenyButton: false,
		  showCancelButton: true,
		  confirmButtonText: `Salvar`,
		  cancelButtonText: `Cancelar`,
		}).then((result) => {
		  if (result.isConfirmed) {
			//var formData = { dados: item }
			var formData = {texto : varTexto}
			varUrl = baseUrl + "php_mon_checklist.php?act=SALVA_FOLLOW";
			$.ajax({
				url: varUrl,
				type: "POST",
				data : formData,
				async : false,
				success: function(response, textStatus, jqXHR) {
					exibe_mensagem("Sucesso!", "FollowUp salvo com sucesso!", "success");
					//pesquisa();
				},
				error: function (jqXHR, textStatus, errorThrown) {
					exibe_mensagem("ERRO!", "Erro ao deletar o registro!", "error");
					pesquisa();
				}
			});

		  } else if (result.isDenied) {
			//Swal.fire('ExclusÃ£o cancelada', '', 'info')
		  }
		})


	}
	function salvaPart(varLinha){
		Swal.fire({
		  title: 'Tem certeza que deseja SALVAR as informações	?',
		  showDenyButton: false,
		  showCancelButton: true,
		  confirmButtonText: `Salvar`,
		  cancelButtonText: `Cancelar`,
		}).then((result) => {
		  if (result.isConfirmed) {
			var SQL = "";
			var campos = document.getElementsByTagName("*");
			for(i=0;i<campos.length;i++){
				nome = campos[i].name;
				if(nome!=undefined){
					if(varLinha<10){
						if(nome.substring(0, 7) == "linha_"+varLinha){
							antigo = campos[i].getAttribute("antigo")
							SQL = SQL + "&"+campos[i].name+"="+campos[i].value+"|"+antigo;
						}
					}else{
						if(varLinha<=99){
							if(nome.substring(0, 8) == "linha_"+varLinha){
								antigo = campos[i].getAttribute("antigo")
								SQL = SQL + "&"+campos[i].name+"="+campos[i].value+"|"+antigo;
							}
						}else{
							if(nome.substring(0, 9) == "linha_"+varLinha){
								antigo = campos[i].getAttribute("antigo")
								SQL = SQL + "&"+campos[i].name+"="+campos[i].value+"|"+antigo;
							}
						}
					}
				}
			}

			//var formData = { dados: item }
			var formData = {texto : SQL,
							id    : document.getElementById("linha_"+varLinha+"_id").value}
			varUrl = baseUrl + "php_mon_checklist.php?act=SALVA";
			$.ajax({
				url: varUrl,
				type: "POST",
				data : formData,
				async : false,
				success: function(response, textStatus, jqXHR) {
					exibe_mensagem("Sucesso!", "Registro salvo com sucesso!", "success");
					//pesquisa();
				},
				error: function (jqXHR, textStatus, errorThrown) {
					exibe_mensagem("ERRO!", "Erro ao deletar o registro!", "error");
					pesquisa();
				}
			});

		  } else if (result.isDenied) {
			//Swal.fire('ExclusÃ£o cancelada', '', 'info')
		  }
		})
	}


	function mascara(t, mask){
		var i = t.value.length;
		var saida = mask.substring(1,0);
		var mascara = mask.substring(i)
		if(event.keyCode!=8){
			if(t.value.substr(i-1,1)!=mask.substr(i,1)){
				if (mascara.substring(0,1) != saida){
					t.value += mascara.substring(0,1);
				}
			}
		}
	}





