baseUrl = "php_php/php_inc_paciente.php?";
function iniciaPagina(){
	document.getElementById("divMain").innerHTML = "<img src='imagens/loading.gif' width='150'>";
	varUrl = baseUrl + "act=INICIA_PAGINA";
	var formData = {}
	$.ajax({
		url: varUrl,
		type: "POST",
		data : formData,
		async : true,
		success: function(response, textStatus, jqXHR) {
			document.getElementById("divMain").innerHTML = response;
		},
		error: function (jqXHR, textStatus, errorThrown) {
			exibe_mensagem("ERRO!", "Erro ao cadastrar o centro!", "error");
		}
	});
}

function idade(ano_aniversario, mes_aniversario, dia_aniversario) {
	var d = new Date,
	ano_atual = d.getFullYear(),
	mes_atual = d.getMonth() + 1,
	dia_atual = d.getDate(),
	ano_aniversario = +ano_aniversario,
	mes_aniversario = +mes_aniversario,
	dia_aniversario = +dia_aniversario,
	quantos_anos = ano_atual - ano_aniversario;
	if (mes_atual < mes_aniversario || mes_atual == mes_aniversario && dia_atual < dia_aniversario) {
		quantos_anos--;
	}
	return quantos_anos < 0 ? 0 : quantos_anos;
}

function validaData(data) {
    // Expressão regular para validar o formato dd/mm/aaaa
    const regex = /^(\d{2})\/(\d{2})\/(\d{4})$/;

    if (!regex.test(data)) {
        return false; // formato inválido
    }

    const [, diaStr, mesStr, anoStr] = data.match(regex);
    const dia = parseInt(diaStr, 10);
    const mes = parseInt(mesStr, 10);
    const ano = parseInt(anoStr, 10);

    // Verifica se mês está entre 1 e 12
    if (mes < 1 || mes > 12) {
        return false;
    }

    // Último dia do mês (considerando anos bissextos automaticamente)
    const ultimoDiaMes = new Date(ano, mes, 0).getDate();

    // Verifica se o dia é válido dentro do mês
    if (dia < 1 || dia > ultimoDiaMes) {
        return false;
    }

    return true;
}

function valida(){
	var varCodigo = document.getElementById("form_codigo").value;
	var varCentro	= document.getElementById("form_centro").value;


	if(varCentro==""){continua = false; exibe_mensagem("Atenção", "É obrigatório o preenchimento do campo [Centro]", "error"); remove_loading(); remove_loading(); return;}
	if(varCodigo==""){
		continua = false; exibe_mensagem("Atenção", "É obrigatório o preenchimento do campo [Código do Participante]", "error"); remove_loading(); remove_loading(); return;
	}



	varUrl = baseUrl + "act=PESQUISA_CODIGO";
	var formData = {codigo:	varCodigo,
					centro: varCentro
				}
	$.ajax({
		url: varUrl,
		type: "POST",
		data : formData,
		async : true,
		success: function(response, textStatus, jqXHR) {
			console.log(response);
			if(response!=""){
				var json = JSON.parse(response);
				if(json.resp=="FALHA"){
					exibe_mensagem("ERRO", json.descricao, "error");
				}
				if(json.resp=="OK"){
					insere(varCentro, "", varCodigo, "", "");
				}
			}
		},
		error: function (jqXHR, textStatus, errorThrown) {
			exibe_mensagem("ERRO!", "Ocorreu algum erro no processo de inclusÃ£o! Por favor, revise suas informações!", "error");
			remove_loading();
			remove_loading();
		}
	});

}
function insere(varCentro, varNasc, varCodigo, varSexo, varMeses){
		var continua = true;
		if(varCentro==""){continua = false; exibe_mensagem("Atenção", "É obrigatório o preenchimento do campo [Centro]", "error"); remove_loading(); remove_loading(); iniciaPagina(); return;}
		if(varCodigo==""){continua = false; exibe_mensagem("Atenção", "É obrigatório o preenchimento do campo [Código do Participante]", "error"); remove_loading(); remove_loading(); iniciaPagina(); return;}



		if(continua==true){
			document.getElementById("divMain").innerHTML = "<img src='imagens/loading.gif'>";
			varUrl = baseUrl + "act=INCLUI_PACIENTE";
			var formData = {centro:	varCentro,
							//nasc  : varNasc,
							cod   : varCodigo,
							//sexo  : varSexo
							//meses : varMeses
						}
			$.ajax({
				url: varUrl,
				type: "POST",
				data : formData,
				async : true,
				success: function(response, textStatus, jqXHR) {
					varUrl = baseUrl + "act=MOSTRA_RECIBO";
					var formData = {centro:	varCentro,
									//nasc  : varNasc,
									codigo  : varCodigo}
					$.ajax({
						url: varUrl,
						type: "POST",
						data : formData,
						async : true,
						success: function(response, textStatus, jqXHR) {
							console.log(response);
							retorno = JSON.parse(response);

							exibeCRF(retorno.participante, retorno.centro);

						},
						error: function (jqXHR, textStatus, errorThrown) {
							exibe_mensagem("ERRO!", "Ocorreu algum erro no processo de inclusão! Por favor, revise suas informações!", "error");
							remove_loading();
						}
					});
					document.getElementById("divMain").innerHTML = response;
				},
				error: function (jqXHR, textStatus, errorThrown) {
					exibe_mensagem("ERRO!", "Ocorreu algum erro no processo de inclusÃ£o! Por favor, revise suas informaÃ§Ãµes!", "error");
					remove_loading();
				}
			});
		}else{
			iniciaPagina();
			remove_loading();
			remove_loading();
		}


		remove_loading();
		remove_loading();
	}
function teste(){
	varUrl = baseUrl + "act=MOSTRA_RECIBO";
	var formData = {centro:	35,
					nasc  : '13/03/1984',
					sexo  : 'M'}
	$.ajax({
		url: varUrl,
		type: "POST",
		data : formData,
		async : true,
		success: function(response, textStatus, jqXHR) {
			document.getElementById("divMain").innerHTML = response;
		},
		error: function (jqXHR, textStatus, errorThrown) {
			//exibe_mensagem("ERRO!", "Erro ao carregar os arquivos!", "error");Pecrf
		}
	});
}
function recibo(centro, paciente){
	popup('rep_recibo_inclusao.php?centro='+centro+'&paciente='+paciente);
}
function mudou_paciente(centro, paciente){
	baseUrl = "php_php/php_ecrf.php?";
	varUrl = baseUrl + "act=CRF_EXIBE";
	var formData = {varCentro : centro,varPart : paciente}
	$.ajax({
		url: varUrl,
		type: "POST",
		data : formData,
		async : true,
		success: function(response, textStatus, jqXHR) {
			if(response.trim()=="OK"){
				document.location = "ecrf_formularios.php";
			}else{
				exibe_mensagem("ERRO!", "Erro ao carregar formulÃ¡rios!", "error");
			}
		},
		error: function (jqXHR, textStatus, errorThrown) {
			exibe_mensagem("ERRO!", "Erro ao carregar formulÃ¡rios!", "error");
		}
	});

 }

	function exibeCRF(pac, centro){
		varUrl = "php_php/php_ecrf.php?act=CRF_EXIBE";
		var formData = {varCentro : centro,varPart : pac}
		$.ajax({
			url: varUrl,
			type: "POST",
			data : formData,
			async : true,
			success: function(response, textStatus, jqXHR) {
				if(response.trim()=="OK"){
					document.location = "ecrf_formularios.php";
				}else{
					exibe_mensagem("ERRO!", "Erro ao carregar formulÃ¡rios!", "error");
				}
			},
			error: function (jqXHR, textStatus, errorThrown) {
				exibe_mensagem("ERRO!", "Erro ao carregar formulÃ¡rios!", "error");
			}
		});
	}

iniciaPagina();
//teste();