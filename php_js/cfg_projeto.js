	var urlBase = '../php_php/cfg_projeto.php?';
	var urlBase = 'php_php/cfg_projeto.php?';
	function limpaCampos(){
		//habilita(false);
		document.getElementById("form_id").value = "0";
		document.getElementById("form_numero").value =  "";
		document.getElementById("form_nome").value = "";
		document.getElementById("form_descricao").value = "";
		document.getElementById("form_patrocinador").value = "";
		document.getElementById("form_centro").value = "";
		document.getElementById("form_centro_analitico").value = "";
		document.getElementById("form_centro_estatistico").value = "";
		document.getElementById("form_periodos").value = "";
		document.getElementById("form_grupos").value = "";
		document.getElementById("form_n").value = "";
		document.getElementById("form_versao").value = "";
		document.getElementById("form_dashproprio").value = "";
		document.getElementById("form_codigo").value = "";
		document.getElementById("form_pais").value = "";
		document.getElementById("form_tipo").value = "";
		document.getElementById("form_data_recebimento").value = "";
		document.getElementById("form_formulacao").value = "";
		document.getElementById("form_data_finalizacao").value = "";
	}
	function habilita(status){
		if(status==false){status=true;}else{status=false;}
		document.getElementById("form_numero").disabled 			= status;
		document.getElementById("form_nome").disabled 				= status;
		document.getElementById("form_descricao").disabled 			= status;
		document.getElementById("form_patrocinador").disabled 		= status;
		document.getElementById("form_centro").disabled 			= status;
		document.getElementById("form_centro_analitico").disabled 	= status;
		document.getElementById("form_centro_estatistico").disabled = status;
		document.getElementById("form_periodos").disabled 			= status;
		document.getElementById("form_grupos").disabled 			= status;
		document.getElementById("form_n").disabled 					= status;
		document.getElementById("form_versao").disabled 			= status;
		document.getElementById("form_dash").disabled 				= status;
		document.getElementById("form_dashproprio").disabled 		= status;
		document.getElementById("form_codigo").disabled 			= status;
		document.getElementById("form_pais").disabled 				= status;
		document.getElementById("form_tipo").disabled 				= status;
		document.getElementById("form_data_recebimento").disabled 				= status;
		document.getElementById("form_formulacao").disabled 				= status;
		document.getElementById("form_data_finalizacao").disabled = status;
	}
	function selecionaProjeto(id){
		if(id>0){
			limpaCampos();
			if (window.XMLHttpRequest) {
				$.blockUI({ timeout: 500, message: '<img src="imagens/carregando.gif" width="100">Carregando informações...', css: {
					border: 'none',
					padding: '15px',
					backgroundColor: '#000',
					'-webkit-border-radius': '10px',
					'-moz-border-radius': '10px',
					opacity: .5,
					color: '#fff'
				} });
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					var retorno = JSON.parse(this.responseText);
					if(retorno.resultado=="OK"){
						document.getElementById("form_id").value	 		= retorno.id;
						document.getElementById("form_numero").value	 	= retorno.numero;
						document.getElementById("form_nome").value		 	= retorno.nome;
						document.getElementById("form_descricao").value 	= retorno.descricao;
						document.getElementById("form_patrocinador").value 	= retorno.patrocinador;
						document.getElementById("form_centro").value 		= retorno.centro;
						document.getElementById("form_centro_analitico").value 		= retorno.centro_analitico;
						document.getElementById("form_centro_estatistico").value 		= retorno.centro_estatistico;
						document.getElementById("form_periodos").value 			= retorno.periodos;
						document.getElementById("form_grupos").value 			= retorno.grupos;
						document.getElementById("form_n").value 				= retorno.n;
						document.getElementById("form_versao").value 			= retorno.versao;
						document.getElementById("form_dashproprio").value 		= retorno.dashproprio;
						document.getElementById("form_dash").value 				= retorno.dash;
						document.getElementById("form_codigo").value 			= retorno.codigo;
						document.getElementById("form_pais").value 				= retorno.pais;
						document.getElementById("form_tipo").value 				= retorno.tipo;
						document.getElementById("form_data_recebimento").value 				= retorno.data_recebimento;
						document.getElementById("form_formulacao").value 				= retorno.formulacao;
						document.getElementById("form_data_finalizacao").value 				= retorno.data_finalizacao;
						habilita(true);
					}else{
						exibe_mensagem("ERRO", "Algum erro ocorreu ao tentar carregar o estudo", "error");
					}
				}
			};
			console.log(urlBase+"act=CONSULTA&id="+id);
			xmlhttp.open("POST",urlBase+"act=CONSULTA&id="+id);
			xmlhttp.send();
		}
	}
	function salvar(){
		$.blockUI({ timeout: 500, message: '<img src="imagens/carregando.gif" width="100">Carregando informações...', css: {
			border: 'none',
			padding: '15px',
			backgroundColor: '#000',
			'-webkit-border-radius': '10px',
			'-moz-border-radius': '10px',
			opacity: .5,
			color: '#fff'
		} });
		//Verificações / Validações
		var formData = {
			'id'			:  	document.getElementById("form_id").value,
			'numero' 		: 	document.getElementById("form_numero").value,
			'nome'		: 	document.getElementById("form_nome").value,
			'descricao'	:	document.getElementById("form_descricao").value,
			'patrocinador':	document.getElementById("form_patrocinador").value,
			'centro'		:	document.getElementById("form_centro").value,
			'centro_analitico'		:	document.getElementById("form_centro_analitico").value,
			'centro_estatistico'		:	document.getElementById("form_centro_estatistico").value,
			'periodos'	:	document.getElementById("form_periodos").value,
			'grupos'		:	document.getElementById("form_grupos").value,
			'n'			:	document.getElementById("form_n").value,
			'versao'		:	document.getElementById("form_versao").value,
			'dash'		:	document.getElementById("form_dash").value,
			'dashproprio'	:	document.getElementById("form_dashproprio").value,
			'codigo'		:	document.getElementById("form_codigo").value,
			'tipo'		:	document.getElementById("form_tipo").value,
			'data_recebimento'		:	document.getElementById("form_data_recebimento").value,
			'formulacao'		:	document.getElementById("form_formulacao").value,
			'data_finalizacao'		:	document.getElementById("form_data_finalizacao").value,
			'pais'		:	document.getElementById("form_pais").value
		}
		if(document.getElementById("form_id").value>0){
			varUrl = urlBase+'act=SALVA';
		}else{
			varUrl = urlBase+'act=NOVO';
		}
		$.ajax({
			url: varUrl,
			type: "POST",
			data : formData,
			async : false,
			success: function(response, textStatus, jqXHR) {
				console.log("Response->" + response);
				exibe_mensagem("Sucesso!", "Registro salvo com sucesso!", "success");
			},
			error: function (jqXHR, textStatus, errorThrown) {
				exibe_mensagem("ERRO!", "Erro ao salvar o registro!", "error");
			}
		});
	}
	function novo(){
		limpaCampos();
		habilita(true);
		document.getElementById("form_id").value="0";
		console.log("Fim");
	}
	function gerarTmf(){
		swal({
		  title: "Confirmação [CUIDADO]",
		  text: "Tem certeza que deseja gerar o TMF novamente? (isso irá apagar os dados anteriores)",
		  icon: "warning",
		  buttons: true,
		  dangerMode: true,
		})
		.then((willDelete) => {
		  if (willDelete) {
			var formData = {id:document.getElementById("form_id").value	}
			$.ajax({
				url: urlBase+'act=GERA_TMF',
				type: "POST",
				data : formData,
				async : false,
				success: function(response, textStatus, jqXHR) {
					console.log("Resposta-> " + response);
					exibe_mensagem("Sucesso!", "TMF carregado com sucesso!", "success");
				},
				error: function (jqXHR, textStatus, errorThrown) {
					exibe_mensagem("ERRO!", "Erro ao salvar o registro!", "error");
				}
			});
		  } else {
			swal("Ok. Cancelado!");
		  }
		});
	}
	function gerarTmfResumido(){
			var formData = {id:document.getElementById("form_id").value	}
			$.ajax({
				url: urlBase + 'act=GERA_TMF_RESUMIDO',
				type: "POST",
				data : formData,
				async : false,
				success: function(response, textStatus, jqXHR) {
					console.log(response);
					exibe_mensagem("Sucesso!", "TMF carregado com sucesso!", "success");
				},
				error: function (jqXHR, textStatus, errorThrown) {
					exibe_mensagem("ERRO!", "Erro ao salvar o registro!", "error");
				}
			});
	}
	function gerarTmfBio(){
			var formData = {id:document.getElementById("form_id").value	}
			$.ajax({
				url: urlBase + 'act=GERA_TMF_BIO',
				type: "POST",
				data : formData,
				async : false,
				success: function(response, textStatus, jqXHR) {
					console.log(response);
					exibe_mensagem("Sucesso!", "TMF carregado com sucesso!", "success");
				},
				error: function (jqXHR, textStatus, errorThrown) {
					exibe_mensagem("ERRO!", "Erro ao salvar o registro!", "error");
				}
			});
	}
	function zerar(){
		swal({
		  title: "Confirmação [CUIDADO]",
		  text: "Tem certeza que deseja ZERAR o CRF? (isso irá apagar os dados anteriores)",
		  icon: "warning",
		  buttons: true,
		  dangerMode: true,
		})
		.then((willDelete) => {
		  if (willDelete) {
			console.log("130");
			var formData = {id:document.getElementById("form_id").value	}
			$.ajax({
				url: urlBase+'act=ZERA_CRF',
				type: "POST",
				data : formData,
				async : false,
				success: function(response, textStatus, jqXHR) {
					swal("SUCESSO!");
				},
				error: function (jqXHR, textStatus, errorThrown) {
					swal("ERRO!");
				}
			});
		  } else {
			swal("Ok. Cancelado!");
		  }
		});
	}
