	baseUrl = "php_php/php_ecrf_formularios.php?";
	function exibe_mensagem(titulo, msg, tipo){
		alert(msg);
	}

	function validaRef(campo){
		//Valida valores de refer�ncia
		campo.style.border="";
		if(campo.getAttribute("data-referencia-e")=="on"){
			valor 		= campo.value;
			referencia 	= campo.getAttribute("data-referencia-valor");
			if( (referencia!="")&&(valor!="")){

				referencia = referencia.split("-");
				campo1 	= referencia[0].trim();
				campo2 	= referencia[1].trim();
				valor	= valor.replace(",", ".");
				campo1	= campo1.replace(",", ".");
				campo2	= campo2.replace(",", ".");
				if( (parseFloat(valor)<parseFloat(campo1))||(parseFloat(valor)>parseFloat(campo2)) ){
					//campo.style.border="3px solid #660000";
					//exibe_mensagem("Atenção!", "O valor digitado se encontra fora da faixa de referência.!", "error");
					alert("O valor digitado se encontra fora da faixa de referência.!");
				}
			}
		}
	}
	function monitoraDM(campo){
		document.getElementById("divMonitoraDM"+campo).innerHTML = '<img class="icone" src="imagens/carregando.gif" width="30">';
		var dados = {idCampo: campo}
        $.ajax({
            url: baseUrl + "act=MONITORADM",
            dataType: 'html',
            type: 'POST',
            data: dados,
            beforeSend: function() {
                document.getElementById("divMonitoraDM"+campo).innerHTML = '<img class="icone" src="imagens/carregando.gif" width="30">';
            },
            complete: function() {
                removeCarregando();
            },
            success: function(data, textStatus) {
				retorno = data;
				var tratado = retorno.split("|");
				document.getElementById("divMonitoraDM"+campo).innerHTML = tratado[2];
				exibe_notificacao(tratado[0].trim(), tratado[1]);
				if(tratado[3]=="1"){	//Desabilita
					try{document.getElementById("form_"+campo).disabled = true;}catch(err){}
				}else{ //Habilita
					try{
						if(tratado[4]=="ON"){
							document.getElementById("form_"+campo).disabled = false;
						}
					}catch(err){}
				}
            },
            error: function(xhr,er) {
				alert("ERRO! " + xhr.status + ' - ' + xhr.statusText + 'Tipo de erro: ' + er);
            }
        });
	};
	function montaForm(divRet, formulario){
		document.getElementById(divRet).innerHTML = "<img src='imagens/loading.gif' width='150'>";
		varUrl = baseUrl + "act=FORM_EXIBE";
		var formData = {varForm: formulario}
		$.ajax({
			url: varUrl,
			type: "POST",
			data : formData,
			async : true,
			success: function(response, textStatus, jqXHR) {
				document.getElementById(divRet).innerHTML = response;
				scrollTo(formulario);
			},
			error: function (jqXHR, textStatus, errorThrown) {
				exibe_mensagem("ERRO!", "Erro ao carregar formulários!", "error");
			}
		});
	}
	function getPaciente(){
		document.getElementById("divPacNumero").innerHTML = "<img src='imagens/loading.gif' width='150'>";
		varUrl = baseUrl + "act=GET_PACIENTE";
		var formData = {varForm: "teste"}
		$.ajax({
			url: varUrl,
			type: "POST",
			data : formData,
			async : true,
			success: function(response, textStatus, jqXHR) {
				document.getElementById("divPacNumero").innerHTML = response;
			},
			error: function (jqXHR, textStatus, errorThrown) {
			}
		});
	}
	function alteraMudanca(){
		houveMudancas = true;
	}
	function scrollTo(hash) {
		//location.hash = "#TOPO";
		location.hash = "#ANCORA_" + hash;
		//location.hash = "#TOPO";
	}
	function scrollToTopo() {
		location.hash = "#TOPO";
	}
	function mudaForm(formulario, acionaForm, mostra){

		if (houveMudancas) {
			if(mostra==true){
				if(confirm("Você fez alterações e ainda não salvou o formulário. Tem certeza que quer sair?")==false){
					return;
				}
			}
		}

		limpaNgr();
		varUrl = baseUrl + "act=FORM_CLICOU";
		var formData = {varForm: formulario}
		mostraCarregando();
		$.ajax({
			url: varUrl,
			type: "POST",
			data : formData,
			async : true,
			success: function(response, textStatus, jqXHR) {
				try{
					document.getElementById("listaForm_"+formulario).innerHTML = response;
					scrollTo(formulario);
				}catch(e){
				}
				if(acionaForm==true){carregaFormulario(formulario);}
				removeCarregando();

			},
			error: function (jqXHR, textStatus, errorThrown) {
				exibe_mensagem("ERRO!", "Erro ao carregar formulários!", "error");
				removeCarregando()
			}
		});
	}
	function carregaFormulario(formulario){
		houveMudancas = false;
		criaElemento("divFormulario", "divFormularioRetorno");
		mostraCarregando();
		varUrl = baseUrl + "act=EXIBE_FORMULARIO";
		var formData = {varForm: formulario}
		$.ajax({
			url: varUrl,
			type: "POST",
			data : formData,
			async : true,
			success: function(response, textStatus, jqXHR) {
				document.getElementById("divFormularioRetorno").innerHTML = response;
			},
			error: function (jqXHR, textStatus, errorThrown) {
				exibe_mensagem("ERRO!", "Erro ao carregar formulários!", "error");
				carrega_condicional('INICIO');
				removeCarregando();
				getPaciente();
			},
			complete: function(){
				removeCarregando();
				scrollToTopo();
				carrega_condicional('INICIO');
				//calcula_campo();
				getPaciente();
				//calcula_dupla_digitacao(null);
				valida_condicional_vermelho(null,1, 0);
				validaTexto2();

				$("body").on("focusin", ".date-picker", function(){	$(this).datepicker();	}); //Habilito DatePicker nos campos
				$("body").on("change", "input, select", function(){
															v2HabilitaCondicional('PAGINA');
															houveMudanca();
															valida_condicional_vermelho(this,1, 1);
															valida_condicional_vermelho(null,1, 0);
															HabilitaMonitorado();
															alertas(this);
															validaTexto2();
															validaBisnaga(this);
															//calcula_campo(this);
														}); //Evendo onChange dos
				$("body").on("blur", "input, select", function(){
															valida_campos_onBlur(this);calcula_campo(this);

														});
				v2HabilitaCondicional('PAGINA');
				HabilitaMonitorado();
				verificaEn();
				calcula_campo();
			}
		});
	}
	function limpaNgr(){
		var listaForm = document.getElementById("divListaForm").innerHTML;
		listaForm = listaForm.replace("<b>", "");
		listaForm = listaForm.replace("</b>", "");
		document.getElementById("divListaForm").innerHTML = listaForm;
	}
	function criaElemento(pai, nome){
		var Principal 	= document.getElementById(pai);
		try{
		var Filho 		= document.getElementById(nome);
		Filho.parentNode.removeChild(Filho);
		var divNova = document.createElement("div");
		divNova.id = nome;
		Principal.appendChild(divNova);
		}catch(e){
		}
	}
	function houveMudanca(){houveMudancas=true;}
	function verificaAssinatura(){
		var saida = "OK";
		var divRetorno = document.getElementById("divFormularioRetorno");
		var all = divRetorno.getElementsByTagName("*");
		var tem = false;
		for(i=0;i<all.length;i++){
			try {
				if(all[i].getAttribute("data-Senha")=="on"){
					tem = true;
					swal({
					  text: "Confirme sua senha",
					  content: {
						element: "input",
						attributes: {
						  placeholder: "Confirme sua senha",
						  type: "password",
						},
					  },
					   button: {
						text: "Confirmar",
						closeModal: true,
					  },
					})
					.then(name => {
					  if (!name) throw null;
						varUrl = "php_php/php_ecrf_formularios.php?act=VERIFICA_SENHA";
						var formData = {varSenha: name}
						$.ajax({
							url: varUrl,
							type: "POST",
							data : formData,
							async : true,
							success: function(response, textStatus, jqXHR) {
								varResp = response.trim();
								if(varResp=="ERRADA"){
									swal({
											title: "ERRO!!",
											text: "Senha digitada invalida. Por favor, revise sua senha e tente novamente.",
											icon: "error",
											buttons: true,
											dangerMode: false,
											html: true
										});
										saida = "ERRO";
								}else{
									var existe = critica_campo_obrigatorio();
									if(existe){
										swal({
											title: "ATENÇÃO",
											text: "Ao menos um campo OBRIGATÓRIO não esta preenchido. De qualquer forma, os dados serão salvos.",
											icon: "warning",
											buttons: true,
											dangerMode: false
										}).then((botaoOk) => {salvaCRF(tipo);})
									}else{
										salvaCRF(tipo);
										return;
									}
								}
							},
							error: function (jqXHR, textStatus, errorThrown) {
								saida = "ERRO";
								removeCarregando()
							}
						});
					})
				}
			}catch(err){}
		}
		if(tem==false){
			var existe = critica_campo_obrigatorio();
			if(existe){
				swal({
					title: "ATENÇÃO",
					text: "Ao menos um campo OBRIGATÓRIO não esta preenchido. De qualquer forma, os dados serão salvos.",
					icon: "warning",
					buttons: true,
					dangerMode: false
				}).then((botaoOk) => {salvaCRF(tipo);})
			}else{
				salvaCRF(tipo);
				return;
			}
		}
		return saida;
	}
	function salvaCRF(){
		//Verificar se algum valor foi alterado no formulÃ¡rio. Se sim, perguntar, em lote, qual o motivo.
		var temAlteracao = false;
		form = document.getElementById("form_info");
		for (const element of form.elements){
			valor_antigo = element.getAttribute("valor-original");
			if(valor_antigo == null) {valor_antigo = "";}
			valor = element.value;
			if(valor == null) {valor = "";}
			alteradoStatus = element.getAttribute("alterado-status");
			tipoCampo      = element.getAttribute("tipo-de-campo");
			if( (valor_antigo!=element.value)&&(alteradoStatus==1)&&(tipoCampo!="calculo") ){var temAlteracao = true;}
		}
		if(temAlteracao==false){
			//NÃ£o teve alteraÃ§Ã£o. Nesse caso, o certo, era nem enviar para salvar no banco.
			//Vou mandar mesmo assim.. vai saber........
			salvaCRFnoBanco("");
		}else{
			$('#modalMotivoAlteracao').modal('show');
		}
	}
	function validaPreRandomizacao(){
		//Verifico TCLE
		$.ajax({
            url: baseUrl + "act=RAND_PRE",
            dataType: 'html',
            type: 'POST',
            data: {id: 0},
            beforeSend: function() {
                //mostraCarregando();
            },
            complete: function() {
                //removeCarregando();
            },
            success: function(data, textStatus) {
				var Resp = JSON.parse(data);
				var statusRet = Resp.ret;
				if(statusRet.trim()=="0"){
					verificaAssinatura();
				}else{
					alert(Resp.mensagem + " RANDOMIZAÇÃO cancelada!");
					//document.location = 'ecrf_formularios.php';
				}

            },
            error: function(xhr,er) {
				alert("ERRO! " + xhr.status + ' - ' + xhr.statusText + 'Tipo de erro: ' + er);
            }
        });
	}
	function salvarComMotivo(){
		var motivo = document.getElementById("txtMotivoAlteracao").value;
		if(motivo.trim()==""){
			swal({
				title: "ATENÇÃO",
				text: "Você fez uma alteração e não justificou o motivo. Por favor, salve novamente e justifique o motivo da alteração.",
				icon: "warning",
				buttons: true,
				showCancelButton: false,
				html: true,
				dangerMode: false
			});
		}else{
			document.getElementById("txtMotivoAlteracao").value = "";
			salvaCRFnoBanco(motivo);
		}
	}
	function putName(){
		form = document.getElementById("form_info");
		for (const element of form.elements){element.name = element.id;}
	}
	function salvaCRFnoBanco(motivoAlteracao){
		//ver a QuestÃ£o da assinatura
		putName();
		var serializeDados = $('#form_info').serialize();
        $.ajax({
            url: baseUrl + "act=SALVA&motivo="+motivoAlteracao,
            dataType: 'html',
            type: 'POST',
            data: serializeDados,
            beforeSend: function() {
                mostraCarregando();
            },
            complete: function() {
                removeCarregando();
            },
            success: function(data, textStatus) {
				console.log("Volta->formulario: " + data);
				montaForm("divListaForm", data.trim());
				//Aqui vou "remontar" o formul�rio para corrigir a quest�o do AT que a viviane levantou.
				carregaFormulario(data.trim());
				//scrollToTopo();
				scrollTo(data.trim());
				sleep(1000);
				scrollTo(data.trim());
				scrollTo(data.trim());
				//carregaFormulario(data.trim());
            },
            error: function(xhr,er) {
				alert("ERRO! " + xhr.status + ' - ' + xhr.statusText + 'Tipo de erro: ' + er);
            }
        });
	}
	function addSubPage(pagina){
		document.getElementById("divListaForm").innerHTML = '<img class="icone" src="imagens/carregando.gif" width="150">';
		var dados = {varPagina: pagina}
        $.ajax({
            url: baseUrl + "act=ADD_SUB_PAGINA",
            dataType: 'html',
            type: 'POST',
            data: dados,
            beforeSend: function() {
                document.getElementById("divListaForm").innerHTML = '<img class="icone" src="imagens/carregando.gif" width="150">';
            },
            complete: function() {
				//montaForm("divListaForm", "");
            },
            success: function(data, textStatus) {
				//scrollTo(data);
				console.log("Volta->addSubPage: " + data);
				montaForm("divListaForm", data);
				carregaFormulario(data);
            },
            error: function(xhr,er) {
				alert("ERRO! " + xhr.status + ' - ' + xhr.statusText + 'Tipo de erro: ' + er);
            }
        });
		scrollTo(pagina);
	}
	function addFields(campo){
		var dados = {varCampo: campo}
        $.ajax({
            url: baseUrl + "act=ADD_FIELDS",
            dataType: 'html',
            type: 'POST',
            data: dados,
            beforeSend: function() {
                valida_dados('SALVA');
				//mostraCarregando();
            },
            complete: function() {
				removeCarregando();
            },
            success: function(data, textStatus) {
				carregaFormulario(data.trim());
            },
            error: function(xhr,er) {
				alert("ERRO! " + xhr.status + ' - ' + xhr.statusText + 'Tipo de erro: ' + er);
				removeCarregando();
            }
        });
	}
	function monitora(campo){
		document.getElementById("divMonitora"+campo).innerHTML = '<img class="icone" src="imagens/carregando.gif" width="30">';
		var dados = {idCampo: campo}
        $.ajax({
            url: baseUrl + "act=MONITORA",
            dataType: 'html',
            type: 'POST',
            data: dados,
            beforeSend: function() {
                document.getElementById("divMonitora"+campo).innerHTML = '<img class="icone" src="imagens/carregando.gif" width="30">';
            },
            complete: function() {
                removeCarregando();
            },
            success: function(data, textStatus) {
				retorno = data;
				var tratado = retorno.split("|");
				document.getElementById("divMonitora"+campo).innerHTML = tratado[2];
				exibe_notificacao(tratado[0].trim(), tratado[1]);
				if(tratado[3]=="1"){	//Desabilita
					try{document.getElementById("form_"+campo).disabled = true;}catch(err){}
				}else{ //Habilita
					try{
						if(tratado[4]=="ON"){
							document.getElementById("form_"+campo).disabled = false;
						}
					}catch(err){}
				}
            },
            error: function(xhr,er) {
				alert("ERRO! " + xhr.status + ' - ' + xhr.statusText + 'Tipo de erro: ' + er);
            }
        });
	}
	function registaComentario(campo){
		mostraCarregando();
		var dados = {idCampo: campo,
					comentario: document.getElementById("formComentario").value}
        $.ajax({
            url: baseUrl + "act=ADD_COMENTARIO",
            dataType: 'html',
            type: 'POST',
            data: dados,
            beforeSend: function() {

            },
            complete: function() {
                removeCarregando();
            },
            success: function(data, textStatus) {
				document.getElementById("divComentario_"+data.trim()).innerHTML = '<i class="fa fa-commenting fa-sm font-green-steel" title="ComentÃ¡rios"></i>';
            },
            error: function(xhr,er) {
				alert("ERRO! " + xhr.status + ' - ' + xhr.statusText + 'Tipo de erro: ' + er);
				removeCarregando();
            }
        });
	}
	function salvarApaga(campo){
		mostraCarregando();
		var dados = {idCampo: campo,
					motivo: document.getElementById("motivoApaga_"+campo).value}
        $.ajax({
            url: baseUrl + "act=APAGA_CAMPO",
            dataType: 'html',
            type: 'POST',
            data: dados,
            beforeSend: function() {
            },
            complete: function() {
                removeCarregando();
            },
            success: function(data, textStatus) {
				if(data=="BRANCO"){
					alert("Motivo da modifica��o n�o pode ser em branco. Cancelando opera��o...");
				}else{
					carregaFormulario(data.trim());
				}
            },
            error: function(xhr,er) {
				alert("ERRO! " + xhr.status + ' - ' + xhr.statusText + 'Tipo de erro: ' + er);
				removeCarregando();
            }
        });
	}
	function ignorarTecla(evento) {
		// Previne o comportamento padr�o da tecla
		evento.preventDefault();
		evento.stopPropagation();

		// Retorna false para garantir que a tecla seja ignorada
		return false;
	}