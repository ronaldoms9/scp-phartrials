	statusColapse = true;
	var baseUrl = "../php_php/php_arq_arquivos.php?";
	var baseUrl = "php_php/php_arq_arquivos.php?";

	function pesquisaArquivo(coluna, arquivo){
		rows 	= tree.rows;
		for (i = 1; i < (rows.length); i++) {
			x = rows[i].getElementsByTagName("TD")[coluna];	//Pego o valor do campo nome
			x = x.innerHTML.toLowerCase();
			if(x.includes(arquivo.toLowerCase())==false){
				tree.deleteRow(i);
				pesquisaArquivo(coluna, arquivo);
				break;
			}
		}
	}
	function filtraArquivos(node, filtro){
		if(filtro=="TODOS"){
			selecionou(node);
		}else{
			pesquisaArquivo(6, filtro);
		}
	}
	function mostraGrupoVersao(grupo){
		$('.grupo_'+grupo).show(); // Shows
	}
	$(document).ready(function() {
		$("#btnPesquisa").click(function () {
			$("#jstree1").jstree("search",document.getElementById("txtPesquisa").value);
		});
		$('#jstree1').jstree({
            //'plugins' : [ 'contextmenu', 'state', 'search', 'adv_search'],
			'plugins' : [ 'contextmenu', 'state', 'search', 'adv_search'],
			'contextmenu': {
				'items': function ($node) {
					return {
						'create': {
							'separator_before': false,
							'separator_after': false,
							'label': 'Novo',
							'action': function (obj) {
								var node = $('#jstree1').jstree(true).get_node($node);
								criar_novo(node.id);
							}
						},
						'delete': {
							'separator_before': false,
							'separator_after': false,
							'label': 'Excluir',
							'action': function (obj) {
								var node = $('#jstree1').jstree(true).get_node($node);
								deletar(node.id);
							}
						},
						'rename': {
							'separator_before': false,
							'separator_after': false,
							'label': 'Renomear',
							'action': function (obj) {
								var node = $('#jstree1').jstree(true).get_node($node);
								renomear(node.id, node.text);
							}
						}
					};
				}
			},

			'search': {
				'case_insensitive': true,
				'show_only_matches' : true
			},
			'core': {
				maxFilesize: 800,
				expand_selected_onload : false,
				'themes': {
					'name': 'proton',
					'responsive': true
				},
				'check_callback' : true,
                'data': {
                    'url': baseUrl + "act=LISTAGEM_TOTAL",
                    'type': 'POST',
                    'dataType': 'JSON',
                    'contentType':'application/json',
                    'data': function (node) {
                        return { "id": this.id };
                    }
                }
            },
			expand_selected_onload : false
        });
		$("#jstree1").on("click", ".jstree-anchor", function(evt){
			var link = $(evt.target).attr("href");
			selecionou(link);
		});
	});
	function criar_novo(id, text){
		(async () => {
		const { value: nome } = await Swal.fire({
		  title: 'Criar novo diretório',
		  input: 'text',
		  inputLabel: 'Digite o novo nome',
		  inputPlaceholder: ''
		})

		if (nome) {
		  //Chamo PHP para renomear
		  varUrl = baseUrl + "act=NOVO&id="+id+"&nome="+nome;
		  var formData = {
			id			:  	id,
			nome 		: 	nome
		}
		  $.ajax({
			url: varUrl,
			type: "POST",
			data : formData,
			async : false,
			success: function(response, textStatus, jqXHR) {
				//if(response=="OK"){
					exibe_mensagem("Sucesso!", "Registro criado com sucesso!", "success");
					$('#jstree1').jstree(true).refresh();

				/*}else{
					exibe_mensagem("Falha!", "Falha ao criar o registro!", "error");
				}*/
			},
			error: function (jqXHR, textStatus, errorThrown) {
				exibe_mensagem("ERRO!", "Erro ao criar o registro!", "error");
			}
		});
		}
		})()
	}
	function renomear(id, text){
		(async () => {
		const { value: nome } = await Swal.fire({
		  title: 'Renomear',
		  input: 'text',
		  inputLabel: 'Digite o novo nome',
		  inputPlaceholder: ''
		})
		if (nome) {
		  //Chamo PHP para renomear
		  varUrl = baseUrl + "act=RENOMEAR&id="+id+"&nome="+nome;
		  var formData = {
			id			:  	id,
			nome 		: 	nome
		  }
		  $.ajax({
			url: varUrl,
			type: "POST",
			data : formData,
			async : false,
			success: function(response, textStatus, jqXHR) {
				//if(response=="OK"){
					exibe_mensagem("Sucesso!", "Registro renomeado com sucesso!", "success");
					$('#jstree1').jstree(true).refresh();
					selecionou(document.getElementById("nodeId").value);
				/*}else{
					exibe_mensagem("Falha!", "Falha ao renomear o registro!", "error");
				}*/
			},
			error: function (jqXHR, textStatus, errorThrown) {
				exibe_mensagem("ERRO!", "Erro ao salvar o registro!", "error");
			}
		});
		}
		})()
	}
	function deletaArquivo(varMotivo, varId){
		$("#static_modal").modal('hide');
		Swal.fire({
			title: 'Tem certeza que deseja DELETAR esse registro?',
			showDenyButton: false,
			showCancelButton: true,
			confirmButtonText: `Deletar - [CUIDADO]`,
			cancelButtonText: `Cancelar`,
		  }).then((result) => {
			/* Read more about isConfirmed, isDenied below */
			if (result.isConfirmed) {
			  varUrl = baseUrl + "act=DELETA_ARQUIVO&id="+varId+"&motivo="+varMotivo;
			  var formData = {id:varId, motivo:varMotivo				}
			  $.ajax({
				  url: varUrl,
				  type: "POST",
				  data : formData,
				  async : false,
				  success: function(response, textStatus, jqXHR) {
					  console.log(response);
					  exibe_mensagem("Sucesso!", "Arquivo deletado com sucesso!", "success");
					  selecionou(response);
				  },
				  error: function (jqXHR, textStatus, errorThrown) {
					  exibe_mensagem("ERRO!", "Erro ao deletar o arquivo!", "error");
				  }
			  });
			} else if (result.isDenied) {
			  Swal.fire('Exclusão cancelada', '', 'info')
			}
		  })
	}
	function salvaComentario(id){
		varComentario = document.getElementById("txtComentario").value;
		varUrl = baseUrl + "act=SALVA_COMENTARIO&id="+id;
		var formData = {id:id				}
		$.ajax({
			url: varUrl,
			type: "POST",
			data : formData,
			async : false,
			success: function(response, textStatus, jqXHR) {
				document.getElementById("divArquivosLista").innerHTML = response;
			},
			error: function (jqXHR, textStatus, errorThrown) {
				exibe_mensagem("ERRO!", "Erro ao carregar os arquivos!", "error");
			}
		});
	}
	function deletar(id){
		Swal.fire({
		  title: 'Tem certeza que deseja EXCLUIR esse registro?',
		  showDenyButton: false,
		  showCancelButton: true,
		  confirmButtonText: `Deletar - [CUIDADO]`,
		  cancelButtonText: `Cancelar`,
		}).then((result) => {
		  /* Read more about isConfirmed, isDenied below */
		  if (result.isConfirmed) {
			varUrl = baseUrl + "act=DELETA&id="+id;
			var formData = {id:id				}
			$.ajax({
				url: varUrl,
				type: "POST",
				data : formData,
				async : false,
				success: function(response, textStatus, jqXHR) {
					exibe_mensagem("Sucesso!", "Registro deletado com sucesso!", "success");
					$('#jstree1').jstree(true).refresh();
				},
				error: function (jqXHR, textStatus, errorThrown) {
					exibe_mensagem("ERRO!", "Erro ao deletar o registro!", "error");
				}
			});
		  } else if (result.isDenied) {
			Swal.fire('Exclusão cancelada', '', 'info')
		  }
		})
	}
	function selecionou(id){
		mostraCarregando();
		document.getElementById("nodeId").value = id;
		document.getElementById("divArquivos").innerHTML = "<img src='imagens/loading.gif'>";
		varUrl = baseUrl + "act=ARQUIVOS&id="+id;
		var formData = {id:id				}
		$.ajax({
			url: varUrl,
			type: "POST",
			data : formData,
			async : false,
			success: function(response, textStatus, jqXHR) {
				removeCarregando();
				document.getElementById("divArquivos").innerHTML = response;
				Dropzone.autoDiscover = false;
				$("#dZUpload").dropzone({
					url: "upload_dropzone.php?grupo="+id,
					addRemoveLinks: true,
					success: function (file, response) {
						console.log("Response->" + response);
						removeCarregando();
						var imgName = response;
						file.previewElement.classList.add("dz-success");
						terminouUpload(id);
					},
					error: function (file, response) {
						console.log("Erroxx-> Response->" + response);
						removeCarregando();
						file.previewElement.classList.add("dz-error");
					}
				});
			},
			error: function (jqXHR, textStatus, errorThrown) {
				removeCarregando();
				exibe_mensagem("ERRO!", "Erro ao carregar os arquivos!", "error");
			}
		});
	}
	function terminouUpload(id){
		document.getElementById("nodeId").value = id;
		document.getElementById("divArquivosLista").innerHTML = "<img src='imagens/loading.gif'>";
		varUrl = baseUrl + "act=ARQUIVOS_IND&id="+id;
		var formData = {id:id				}
		$.ajax({
			url: varUrl,
			type: "POST",
			data : formData,
			async : false,
			success: function(response, textStatus, jqXHR) {
				document.getElementById("divArquivosLista").innerHTML = response;
			},
			error: function (jqXHR, textStatus, errorThrown) {
				exibe_mensagem("ERRO!", "Erro ao carregar os arquivos!", "error");
			}
		});
	}
	function deleteFile(id){
		document.getElementById("divArquivos").innerHTML = "<img src='imagens/loading.gif'>";
		varUrl = baseUrl + "act=DELETA_ARQUIVO&id="+id;
		var formData = {id:id}
		$.ajax({
			url: varUrl,
			type: "POST",
			data : formData,
			async : false,
			success: function(response, textStatus, jqXHR) {
				exibe_mensagem("Sucesso", "Arquivo deletado com sucesso!", "success");
				document.getElementById("divArquivos").innerHTML = response;
				$('#jstree1').jstree(true).refresh();
				selecionou(document.getElementById("nodeId").value);
			},
			error: function (jqXHR, textStatus, errorThrown) {
				exibe_mensagem("ERRO!", "Erro ao carregar os arquivos!", "error");
			}
		});
	}
	function modalUpload(grupoId, nome){
		document.getElementById("form_id").value = grupoId;
		document.getElementById("form_tipo").value = nome;
		document.getElementById("form_arquivo").value = "";
		document.getElementById("form_descricao").value = "";
		$("form_descricao").val('');
		$("#static").modal('show');
	}
	function novaVersao(id, nome){
		document.getElementById("form_versao_tipo").value = nome;
		document.getElementById("form_versao_id").value = id;
		document.getElementById("form_versao_descricao").value = "";
		$("#modalNovaVersao").modal('show');
	}
	function validaSenha(id, grupoId){
		document.getElementById("form_valida_id").value = id;
		document.getElementById("form_valida_grupo_id").value = grupoId;
		document.getElementById("form_valida_senha").value = "";
		document.getElementById("form_valida_comentarios").value = "";
		$("form_valida_comentarios").val('');
		$("#modalValida").modal('show');
	}
	function desvalidaSenha(id, grupoId){
		document.getElementById("form_desvalida_id").value = id;
		document.getElementById("form_desvalida_grupo_id").value = grupoId;
		document.getElementById("form_desvalida_senha").value = "";
		document.getElementById("form_desvalida_comentarios").value = "";
		$("form_desvalida_comentarios").val('');
		$("#modaldesValida").modal('show');
	}
	function validaArquivo(grupo){
		varId			= document.getElementById("form_valida_id").value;
		varComentarios 	= document.getElementById("form_valida_comentarios").value;
		varSenha		= document.getElementById("form_valida_senha").value;
		if(varSenha==""){
			exibe_mensagem("ERRO", "Senha não digitada! Validação cancelada", "error");
			$("#modalValida").modal('hide');
			selecionou(grupo);
		}else{
			varUrl = baseUrl + "act=VALIDA_ARQUIVO&id="+varId;
			var formData = {id:varId, comentarios: varComentarios, senha: varSenha}
			$.ajax({
				url: varUrl,
				type: "POST",
				data : formData,
				async : false,
				success: function(response, textStatus, jqXHR) {
					$("#modalValida").modal('hide');
					console.log(response);
					if(response=="OK"){
						exibe_mensagem("Sucesso", "Arquivo validado com sucesso!", "success");
						selecionou(grupo);
					}else{
						exibe_mensagem("ERRO", "Senha não confere. Validação cancelada!", "error");
						selecionou(grupo);
					}
					selecionou(grupo);
				},
				error: function (jqXHR, textStatus, errorThrown) {
					$("#modalValida").modal('hide');
					exibe_mensagem("ERRO!", "Erro ao carregar os arquivos!", "error");
					selecionou(grupo);
				}
			});
		}
	}
	function desvalidaArquivo(grupo){
		varId			= document.getElementById("form_desvalida_id").value;
		varComentarios 	= document.getElementById("form_desvalida_comentarios").value;
		varSenha		= document.getElementById("form_desvalida_senha").value;
		if(varSenha==""){
			exibe_mensagem("ERRO", "Senha não digitada! Validação cancelada", "error");
			$("#modaldesValida").modal('hide');
			selecionou(grupo);
		}else{
			varUrl = baseUrl + "act=DESVALIDA_ARQUIVO&id="+varId;
			var formData = {id:varId, comentarios: varComentarios, senha: varSenha}
			$.ajax({
				url: varUrl,
				type: "POST",
				data : formData,
				async : false,
				success: function(response, textStatus, jqXHR) {
					$("#modaldesValida").modal('hide');
						exibe_mensagem("Sucesso", "Arquivo desvalidado com sucesso!", "success");
						selecionou(grupo);
				},
				error: function (jqXHR, textStatus, errorThrown) {
					$("#modaldesValida").modal('hide');
					exibe_mensagem("ERRO!", "Erro ao carregar os arquivos!", "error");
					selecionou(grupo);
				}
			});
		}
	}
	function abreHistorico(id){
		$('.modal-Historico').load('arq_detalhe.php?act=E&acw='+id+'&acg='+id,function(){
			$('#modalHistorico').modal({show:true});
		});
	}
	function abreComentario(id){
		$('.modal-Comentario').load('arq_comentario.php?id='+id,function(){
			$('#modalComentario').modal({show:true});
		});
	}
	function metaDados(id){
		$('.modal-Meta').load('arq_meta.php?id='+id,function(){
			$('#modalMeta').modal({show:true});
		});
	}
	function mudouStatusDir(varStatus, varNode){
		mostraCarregando();
		varUrl = "php_php/php_arq_arquivos.php?act=MUDA_STATUS_DIR";
		var formData = {id:varNode,
						status			: varStatus}
		$.ajax({
			url: varUrl,
			type: "POST",
			data : formData,
			async : false,
			success: function(response, textStatus, jqXHR) {
				removeCarregando();
				exibe_mensagem("Sucesso!", "Status alterado com sucesso!", "success");
			},
			error: function (jqXHR, textStatus, errorThrown) {
				removeCarregando();
			}
		});
	}
	function salvaMeta(){
		var varId				= document.getElementById("form_meta_id").value;
		var varZona 			= document.getElementById("form_zona").value;
		var varSessao 			= document.getElementById("form_sessao").value;
		var varArtefato 		= document.getElementById("form_artefato").value;
		var varClassificacao 	= document.getElementById("form_classificacao").value;
		var varTipo 			= document.getElementById("form_tipo_meta").value;
		var varDocumento		= document.getElementById("form_documento").value;
		var varMeta				= document.getElementById("form_meta_dados").value;
		var varData				= document.getElementById("form_meta_data").value;
		var varEmissor			= document.getElementById("form_meta_emissor").value;
		var varDono				= document.getElementById("form_meta_dono").value;


		varUrl = "php_php/php_arq_arquivos.php?act=META&id="+varId;
		var formData = {id:varId,
						zona			: varZona,
						sessao 			: varSessao,
						artefato 		: varArtefato,
						classificacao 	: varClassificacao,
						tipo			: varTipo,
						documento		: varDocumento,
						meta			: varMeta,
						data			: varData,
						emissor			: varEmissor,
						dono			: varDono}
		$.ajax({
			url: varUrl,
			type: "POST",
			data : formData,
			async : false,
			success: function(response, textStatus, jqXHR) {
				$("#modalMeta").modal('hide');
				removeCarregando();
			},
			error: function (jqXHR, textStatus, errorThrown) {
				removeCarregando();
				exibe_mensagem("ERRO!", "Erro ao carregar os arquivos!", "error");
			}
		});
	}
	function salvaComentarioPasta(varId){
		mostraCarregando();
		varComentario 	= document.getElementById("txtComentario").value;
		varUrl = baseUrl + "act=SALVA_COMENTARIO_PASTA&id="+varId;
		var formData = {id:varId, comentario: varComentario}
		$.ajax({
			url: varUrl,
			type: "POST",
			data : formData,
			async : false,
			success: function(response, textStatus, jqXHR) {
				removeCarregando();
			},
			error: function (jqXHR, textStatus, errorThrown) {
				removeCarregando();
				exibe_mensagem("ERRO!", "Erro ao carregar os arquivos!", "error");
			}
		});
	}
	function salvaComentario(varId){
		varComentario 	= document.getElementById("form_comentarios_comentarios").value;
		varUrl = baseUrl + "act=SALVA_COMENTARIO&id="+varId;
		var formData = {id:varId, comentario: varComentario}
		$.ajax({
			url: varUrl,
			type: "POST",
			data : formData,
			async : false,
			success: function(response, textStatus, jqXHR) {
				$("#modalComentario").modal('hide');
			},
			error: function (jqXHR, textStatus, errorThrown) {
				$("#modalValida").modal('hide');
				exibe_mensagem("ERRO!", "Erro ao carregar os arquivos!", "error");
				selecionou(grupo);
			}
		});
	}
	function abreRenomear(id){
		$('.modal-Renomear').load('arq_renomear.php?id='+id,function(){
			$('#modalRenomear').modal({show:true});
		});
	}
	function salvaRenomear(varId){
		varNome 	= document.getElementById("form_renomear_nome").value;
		varUrl = baseUrl + "act=RENOMEAR_ARQUIVO&id="+varId;
		var formData = {id:varId, nome: varNome}
		$.ajax({
			url: varUrl,
			type: "POST",
			data : formData,
			async : false,
			success: function(response, textStatus, jqXHR) {
				$("#modalRenomear").modal('hide');
			},
			error: function (jqXHR, textStatus, errorThrown) {
				$("#modalRenomear").modal('hide');
				exibe_mensagem("ERRO!", "Erro!", "error");
			}
		});
	}
	function realizarUpload(){
		mostraCarregando();
		var form_data = new FormData();
		form_data.append('file', $('#form_arquivo').prop('files')[0]);
		 $.ajax({
			url:baseUrl + 'act=UPLOAD&grupo='+document.getElementById("form_id").value+'&descricao='+document.getElementById("form_descricao").value+'&email='+document.getElementById("form_email").value,
			dataType: 'text',
			cache: false,
			contentType: false,
			processData: false,
			data: form_data,
			type: 'post',
			success: function(data){
				removeCarregando();
				console.log("Volta->" + data);
				$("form_descricao").val('');
				$("#static").modal('hide');
				$('#jstree1').jstree(true).refresh();
				if(data!="ERRO"){
					exibe_mensagem("Sucesso!", "Seu upload foi realizado com sucesso", "success");
				}else{
					exibe_mensagem("Falha!", "Seu upload NÃO foi realizado", "error");
				}
				selecionou(document.getElementById("form_id").value);
			}
		});
	}
	function realizarUploadNovaVersao(){
		var form_data = new FormData();
		form_data.append('file', $('#form_versao_arquivo').prop('files')[0]);
		console.log("URL -> " + baseUrl + 'act=UPLOAD_VERSAO&versao='+document.getElementById("form_versao_id").value+'&descricao='+document.getElementById("form_versao_descricao").value+'&email='+document.getElementById("form_email").value);
		 $.ajax({
			url:baseUrl + 'act=UPLOAD_VERSAO&versao='+document.getElementById("form_versao_id").value+'&descricao='+document.getElementById("form_versao_descricao").value+'&email='+document.getElementById("form_email").value,
			dataType: 'text',
			cache: false,
			contentType: false,
			processData: false,
			data: form_data,
			type: 'post',
			success: function(data){
				console.log(data);
				$("form_versao_descricao").val('');
				$("#modalNovaVersao").modal('hide');
				$('#jstree1').jstree(true).refresh();
				if(data=="ERRO"){
					exibe_mensagem("Falha!", "Seu upload NÃO foi realizado", "error");

				}else{
					exibe_mensagem("Sucesso!", "Seu upload foi realizado com sucesso", "success");
					selecionou(document.getElementById("nodeId").value);
				}

			}
		});
	}
	function rejeitaArquivo(varMotivo, varId){
		varUrl = baseUrl + "act=REJEITA_ARQUIVO";
		var formData = {id:varId,
						motivo: varMotivo}
		$.ajax({
			url: varUrl,
			type: "POST",
			data : formData,
			async : false,
			success: function(response, textStatus, jqXHR) {
				$("#modaldesValida").modal('hide');
					exibe_mensagem("Sucesso", "Arquivo rejeitado com sucesso!", "success");
					selecionou(document.getElementById("nodeId").value);
			}

		});
	}
	function colapse(){
		if(statusColapse==true){
			statusColapse = false;
			document.getElementById("iconColapse").innerHTML = '<i class="fa fa-chevron-down" aria-hidden="true"></i>';
			$('#jstree1').jstree('close_all');
		}else{
			statusColapse = true;
			document.getElementById("iconColapse").innerHTML = '<i class="fa fa-chevron-up" aria-hidden="true"></i>';
			//20241210 - Desabilitei o open_all para não abrir as pastas
			//$('#jstree1').jstree('open_all');
		}
	}
	function sort(ordem){
		var table, rows, switching, i, x, y, shouldSwitch;
		table = document.getElementById("tree");
		switching = true;
		if(ordem=="nome"){
			var coluna = 1;
		}
		if(ordem=="data"){
			var coluna = 3;
		}
		while (switching) {
			switching = false;
			rows = table.rows;
			for (i = 1; i < (rows.length - 1); i++) {
			  shouldSwitch = false;
			  x = rows[i].getElementsByTagName("TD")[coluna];
			  y = rows[i + 1].getElementsByTagName("TD")[coluna];
			 //Tratar data. Jogar para LIS primeiramente

			 if(coluna==3){//Tratar como se fosse data (Transformar para LIS)
				valorX = x.innerHTML.toLowerCase();
				valorY = y.innerHTML.toLowerCase();

				valorX = valorX.substr(6,4) + valorX.substr(4,2) + valorX.substr(0,2);
				valorY = valorY.substr(6,4) + valorY.substr(4,2) + valorY.substr(0,2);

			 }else{
				 valorX = x.innerHTML.toLowerCase();
				 valorY = y.innerHTML.toLowerCase();
			 }



			  if (valorX > valorY) {
				shouldSwitch = true;
				break;
			  }
			}
			if (shouldSwitch) {
			  rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
			  switching = true;
			}
		}

	}
