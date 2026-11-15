<?
	include_once "seguranca.php";
	include_once "class/agenda.class.php";
	if(isset($_GET[base64_encode('act')])){$act = strtoupper($_GET[base64_encode('act')]);}else{$act="";}
	if(isset($_GET[base64_encode('pac')])){$paciente = $_GET[base64_encode('pac')];}else{$paciente="";}
	if(isset($_GET[base64_encode('cam')])){$formularioP = $_GET[base64_encode('cam')];}else{$formularioP="";}

	$varAgendamento = new Agenda($_GET['agendamento']);
	$_SESSION['agendaFormulario'] = $varAgendamento->id;
	if($formularioP>0){
		$varFormulario = new Formulario($formularioP);
		$formTEXTO_CRF_pre = "";
		$formTEXTO_CRF_pos = "";
		$formTEXTO_CRF_pre = "<span class='textoFORMULARIO_PRE'>[FORMULÁRIO " . $varFormulario->classFormulario->ordem . "]</span> - ";
		$formTEXTO_CRF_pos = "<span class='textoFORMULARIO_POS'>" . $varFormulario->classFormulario->texto_crf . "</span>";
		$formTEXTO_CRF = $formTEXTO_CRF_pre . $formTEXTO_CRF_pos;

	}
	$varOrdem = 0;
	$listaHTML = array();
	$valorHTML = array();

	$msg_retorno = "";
?>
<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<script>var time = new Date().getTime();</script>
<head>
        <?
			include "global_css.php";
		?>
		<link href="https://fonts.googleapis.com/css?family=Abel|Archivo+Narrow|PT+Sans+Narrow:700|Pragati+Narrow" rel="stylesheet">
        <link href="assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
	    <link href="https://fonts.googleapis.com/css?family=Abel|Archivo+Narrow|PT+Sans+Narrow:700|Pragati+Narrow" rel="stylesheet">
		<link href="assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap-markdown/css/bootstrap-markdown.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap-summernote/summernote.css" rel="stylesheet" type="text/css" />
		<link href="assets/global/plugins/bootstrap-toastr/toastr.min.css" rel="stylesheet" type="text/css" />
		<style>
			 .lista_formularios{
				font-size: 13px !important;
				/*font-family: 'Pragati Narrow', sans-serif;*/
				/*font-family: 'Archivo Narrow', sans-serif;*/
				font-family: 'Abel', sans-serif;
			}
			.formulario_en{
				font-size: 10px !important;
				font-family: 'Abel', sans-serif;
				font-style: italic;
			}
			.texto_en{
				font-size: 10px !important;
				font-family: 'Abel', sans-serif;
				font-style: italic;
			}
			.textoFORMULARIO_PRE{
				font-size: 14px !important;
				/*font-family: 'Pragati Narrow', sans-serif;*/
				/*font-family: 'Archivo Narrow', sans-serif;*/
				font-family: 'Abel', sans-serif;
			}
			.textoFORMULARIO_POS{
				font-size: 16px !important;
				font-family: 'Abel', sans-serif;
				font-weight: bold !important;
			}
			.tabela-head{
				font-weight: bold !important;
				font-family: 'Abel', sans-serif !important;
				font-size: 14px !important;
				background-color:#f7f7f7 !important;
				border-bottom: 2px solid #2C3E50 !important;
				border-top: 2px solid #2C3E50 !important;
			}
			.tabela-divisao{
				font-weight: bold !important;
				font-family: 'Abel', sans-serif !important;
				font-size: 12px !important;
				/*E9EDEF*/
				background-color:#f7f7f7 !important;
				border-bottom: 2px solid #2C3E50 !important;
				border-top: 1px solid #2C3E50 !important;
				font-weight: bold !important;
			}
			.tabela-body{
				font-family: 'Abel', sans-serif !important;
				font-size: 14px !important;
			}
			.controle{
				font-family: 'Abel', sans-serif !important;
				font-size: 13px !important;
				padding: 8px 8px !important;
				line-height: 20px !important;
				text-align: center;
				text-align-last:center;
			}
			.controle_caixa{
				font-family: 'Abel', sans-serif !important;
				font-size: 13px !important;
				padding: 8px 8px !important;
				line-height: 20px !important;
				text-align: left;
			}
			.legenda{
				font-family: 'Abel', sans-serif !important;
				font-size: 10px !important;
				font-style: italic !important;
			}
			.fixo {
			}
			.coluna{
				color: black;
				vertical-align: center;
				background-color: white;
				color: black;
				border: 1px solid #e7e7e7;
				padding: 0.2px;
				width:25px !important;
			}
			.modal-backdrop {
			  z-index: -1;
			}
			#modalHistorico.modal {
				overflow: hidden !important;
			}
			#modalHistorico .modal-dialog {
				margin: 20px auto;
			}
			#modalHistorico .modal-content {
				max-height: calc(100vh - 40px);
				overflow-y: scroll !important;
			}
			#modalHistorico .scroller {
				height: auto !important;
				max-height: none !important;
				overflow: visible !important;
			}
		</style>
		<script>
			listaHTML = ""; //Aqui estou zerando o valor dessa variável. Pensar em como mudar o valor para não usar variável global
			<?
				if(isset($msg)){echo $msg;}
				if($msg_retorno!=""){ echo "alert('".$msg_retorno."')";}
			?>

		</script>
</head>
    <body class="page-container-bg-solid">
    <a name="TOPO"></a>
        <div class="page-wrapper">
            <?
               include "menu_principal.php";
            ?>
            <div class="page-wrapper-row full-height">
                <div class="page-wrapper-middle">
                    <div class="page-container">
                        <div class="page-content-wrapper">
                            <div class="page-head">
                                <div class="container-fluid">
                                    <div class="page-title">
                                        <h1>
											<a href="javascript:volta_lista();" class="btn btn-circle blue-dark" title="Voltar para a lista de Monitorias" alt="Voltar para a lista de Monitorias"><i class="fa fa-arrow-circle-left"></i></a>
											<?=traduzir("Monitoria", $_SESSION['lang']);?>
											-
											<?
												echo " <b>Agendamento " . $varAgendamento->nro_visita . "</b> - " . $varAgendamento->getDataInicialDATA();
											?>
										</h1>
                                    </div>
                                </div>
                            </div>
                            <div class="page-content">
                                <div class="container-fluid">
										<div class="row">
											<div class="col-md-3 sidebar-outer" id="lista" >
												<div class="col-md-12 fundo_branco">
													<div class="portlet light">
														<div class="portlet-title fixo">
															<div class="caption font-blue-dark">
																<span><i class="fa fa-th font-blue-dark" title="Formulários"></i></span>
																<span class="caption-subject">&nbsp;&nbsp;<?="<b>FORMULÁRIOS</b>";?>
															</div>
														</div>


														<div class="portlet-body" style="height: 500px;overflow-y: scroll;">
															<div id="divRetornoFormularios">
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="col-md-9" id="divFormularioRetorno">
											</div>
										</div>

								</div>
									<br><br>
								</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
	<?
		include "rodape.php";
		include "global_js.php";
	?>
        <script src="assets/global/plugins/moment.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
		<script src="assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
		<script src="assets/global/plugins/bootstrap-wysihtml5/wysihtml5-0.3.0.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-markdown/lib/markdown.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-markdown/js/bootstrap-markdown.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-summernote/summernote.min.js" type="text/javascript"></script>
		<script src="assets/global/plugins/bootstrap-toastr/toastr.min.js" type="text/javascript"></script>
</body>

<script>
    var user_type = <?=$_SESSION['tipo_usuario'];?>;
    location.hash = "#ANCORA_<?=$formularioP;?>";
	location.hash = "#TOPO";


	//Tratando o tipo HTML
	$('.summernote').each(function(i, obj) {		$(this).summernote({height: 300});	});
	<?php
		if(count($listaHTML)>0){	//Tem tipo HTML
			for($countHTML=0;$countHTML<count($listaHTML);$countHTML++){
				?>
					valor = "";
					valor = decodeURIComponent("<?=$valorHTML[$countHTML];?>");
					$('#<?=$listaHTML[$countHTML]?>').summernote('code', valor);
				<?
			}
		}
	?>

	<?
		if(isset($datas)){
			if($datas!=""){
			 for($i=0;$i<count($datas);$i++){
			 ?>
				  $(document).ready(function () {
					$('#<?=$datas[$i];?>').datepicker({
						format: "dd/mm/yyyy",
						language: "pt-BR"
					});
				  });
			 <?
			 }
			}
		}
	?>
	function escondeColuna(acao){
		var display = document.getElementById("lista").style.display;
        if(display == "none"){
            document.getElementById("lista").style.display = 'block';
			 $(window).trigger('resize');
		}else{
            document.getElementById("lista").style.display = 'none';
			$(window).trigger('resize');
		}
	}
	function muda_comentario(campo){
		if (window.XMLHttpRequest) {
			document.getElementById("btnComentario"+campo).innerHTML = '<img class="icone" src="imagens/carregando.gif" width="30">'
			xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				retorno = this.responseText;
				document.getElementById("btnComentario"+campo).innerHTML = retorno;
			}
		};
		xmlhttp.open("POST","ajax.php?act=MUDA_COMENTARIO&campo="+campo);
		xmlhttp.send();
	}
	function muda_erro(campo){
		if (window.XMLHttpRequest) {
			document.getElementById("retorno_erro"+campo).innerHTML = '<img class="icone" src="imagens/carregando.gif" width="30">'
			xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				retorno = this.responseText;
				var tratado = retorno.split("|");
				document.getElementById("retorno_erro"+campo).innerHTML = tratado[2];
				exibe_notificacao(tratado[0].trim(), tratado[1]);
				if(tratado[3]=="1"){	//Desabilita
					try{
						//document.getElementById("form_"+campo).disabled = true;
					}catch(err){
					}
				}else{ //Habilita
					try{
						//document.getElementById("form_"+campo).disabled = false;
					}catch(err){
					}
				}
			}
		};
		xmlhttp.open("POST","ajax.php?act=MUDA_ERRO_AGENDA&campo="+campo);
		xmlhttp.send();
	};
	function exibe_notificacao(tipo, mensagem){
		if(tipo=="OK"){
			$.bootstrapGrowl(mensagem, {
				ele: 'body', // which element to append to
				type: 'info', // (null, 'info', 'danger', 'success')
				offset: {from: 'top', amount: 20}, // 'top', or 'bottom'
				align: 'right', // ('left', 'right', or 'center')
				width: 290, // (integer, or 'auto')
				delay:1000, // Time while the message will be displayed. It's not equivalent to the *demo* timeOut!
				allow_dismiss: true, // If true then will display a cross to close the popup.
				stackup_spacing: 10 // spacing between consecutively stacked growls.
			});
		}
		if(tipo=="FALHA"){
			$.bootstrapGrowl(mensagem, {
				ele: 'body', // which element to append to
				type: 'danger', // (null, 'info', 'danger', 'success')
				offset: {from: 'top', amount: 20}, // 'top', or 'bottom'
				align: 'right', // ('left', 'right', or 'center')
				width: 290, // (integer, or 'auto')
				delay:2000, // Time while the message will be displayed. It's not equivalent to the *demo* timeOut!
				allow_dismiss: true, // If true then will display a cross to close the popup.
				stackup_spacing: 10 // spacing between consecutively stacked growls.
			});
		}
	}
	function salvarRevisor(id){
		var varComentario = document.getElementById("form_modal_comentarios_comentario_revisor").value;
		var varStatus 	  = document.getElementById("form_modal_comentarios_status_revisor").value;
		if (window.XMLHttpRequest) {
		document.getElementById("btnCarregando").innerHTML = '<img class="icone" src="imagens/carregando.gif" width="30">'
		xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				document.getElementById("btnComentarioRevisor"+id).innerHTML = this.responseText;
				//muda_comentarioRevisor(id);
			}
		};
		xmlhttp.open("POST","ajax.php?act=AGENDA_MUDA_COMENTARIO_REVISOR&id="+id);
		xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");
		xmlhttp.send("comentario=" + encodeURIComponent(varComentario) + "&status=" + encodeURIComponent(varStatus));
	}
	function EnviaEmailTodos(agendamento){
		swal.fire({
		  title: 'Confirmação!',
		  text: "Confirma o envio do alerta sobre o término da revisão?",
		  icon: 'warning',
		  showCancelButton: true,
		  confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  confirmButtonText: 'Sim, enviar!',
		  cancelButtonText: 'Cancelar',
		}).then((result) => {
		  if (result.value) {
			$.blockUI({ message: '<img src="imagens/carregando.gif" width="100">Aguarde, enviando email...', css: {
				border: 'none', padding: '15px', backgroundColor: '#000', '-webkit-border-radius': '10px', '-moz-border-radius': '10px', opacity: .5, color: '#fff'
			} });

			varUrl = "../php_php/php_agenda.php?act=ENVIAR_EMAIL_TERMINO";
			var formData = {id: agendamento}
			$.ajax({
				url: varUrl,
				type: "POST",
				data : formData,
				async : true,
				success: function(response, textStatus, jqXHR) {
					exibe_mensagem("Sucesso!", "Email enviado com sucesso!", "success");
					$.unblockUI();
				},
				error: function (jqXHR, textStatus, errorThrown) {
					exibe_mensagem("ERRO!", "Erro ao cadastrar o centro!", "error");
					$.unblockUI();
				}
			});
		  }
		})
	}
	function monitora(campo){
		if (window.XMLHttpRequest) {
			document.getElementById("retorno"+campo).innerHTML = '<img class="icone" src="imagens/carregando.gif" width="30">'
			xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				retorno = this.responseText;
				var tratado = retorno.split("|");
				document.getElementById("retorno"+campo).innerHTML = tratado[2];
				exibe_notificacao(tratado[0].trim(), tratado[1]);
				if(tratado[3]=="1"){	//Desabilita
					try{
						document.getElementById("form_"+campo).disabled = true;
					}catch(err){
					}
				}else{ //Habilita
					try{
						document.getElementById("form_"+campo).disabled = false;
					}catch(err){
					}
				}

			}
		};
		xmlhttp.open("POST","ajax.php?act=MUDA_MONITORIA_AGENDA&campo="+campo);
		xmlhttp.send();
	};
	function setaNA(id){
		var element = document.getElementById("form_"+id);
		if(element.tagName == 'SELECT'){
			document.getElementById("form_"+id).value = "9 - N/A";
			valida_dados_pontual(element);
		}else{
			document.getElementById("form_"+id).value = "N/A";
			valida_dados_pontual(element);
		}
	}
	function setaNV(id){
		var element = document.getElementById("form_"+id);
		if(element.tagName == 'SELECT'){
			document.getElementById("form_"+id).value = "8 - N/V";
			valida_dados_pontual(element);
		}else{
			document.getElementById("form_"+id).value = "N/V";
			valida_dados_pontual(element);
		}
	}
	function volta_lista(){muda_pagina('agenda.php?volta=listagem');}
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
	function valida(valor,min,max,nome,campo){
			 campo.style.backgroundColor ="white";
			 if(min>0){
				 if((parseInt(valor)<parseInt(min))&&(parseInt(valor)>0)){
					  campo.style.backgroundColor ="#ffe6e6";
					  alert("Valor do campo "+nome+" é menor que o mínimo permitido");
				 }
			 }
			 if(max>0){
				 if((parseInt(valor)>parseInt(max))&&(parseInt(valor)>0)){
					  alert("Valor do campo "+nome+" é maior que o máximo permitido");
				 }
			 }
	}
	function valida_dados_pontual(campo){
		if(campo.value!=campo.getAttribute("valor-original")){
			salvaCRF();
			campo.setAttribute("valor-original", campo.value);
		}
	}
	function valida_dados(){

		var container = document.getElementById("form_info");
		$('.summernote').each(function(i, obj) {
			valor = "";
			valor = encodeURIComponent($(this).summernote('code'));
			nome    = $(this).attr("referente");
			var input = document.createElement("input");
			input.type = "hidden";
			input.name = nome;
			input.value = valor;
			container.appendChild(input);
		});
		salvaCRF("GERAL");
		//document.form_info.submit();
	}
	function salvaCRF(origem=""){
		//Verificar se algum valor foi alterado no formulário. Se sim, perguntar, em lote, qual o motivo.
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
		//if(temAlteracao==false){
		if(temAlteracao==temAlteracao){	//Aqui estou desativando o motivo da alteracao
			//Não teve alteração. Nesse caso, o certo, era nem enviar para salvar no banco.
			//Vou mandar mesmo assim.. vai saber........
			salvaCRFnoBanco("",origem);
		}else{
			$('#modalMotivoAlteracao').modal('show');
		}
	}
	function salvarComMotivo(){
		var motivo = document.getElementById("txtMotivoAlteracao").value;
		if(motivo.trim()==""){
			Swal.fire(
			  'ATENÇÃO!',
			  'Você fez uma alteração e não justificou o motivo. Por favor, salve novamente e justifique o motivo da alteração.',
			  'WARNING'
			);
		}else{
			document.getElementById("txtMotivoAlteracao").value = "";
			salvaCRFnoBanco(motivo, "");
		}
	}
	function putName(){
		form = document.getElementById("form_info");
		for (const element of form.elements){element.name = element.id;}
	}
	function salvaCRFnoBanco(motivoAlteracao,origem="GERAL"){
		//ver a Questão da assinatura
		putName();
		var serializeDados = $('#form_info').serialize();
		$.ajax({
			url: "php_php/php_agendamentos.php?act=SALVA&motivo="+motivoAlteracao,
			dataType: 'html',
			type: 'POST',
			data: serializeDados,
			beforeSend: function() {
				mostraCarregando();
			},
			complete: function() {
				removeCarregando();
				if(origem=="GERAL"){
					window.location.href = "#TOPO";
				}
			},
			success: function(data, textStatus) {
				removeCarregando();
				if(origem=="GERAL"){
					window.location.href = "#TOPO";
					console.log("SALVEI");
				}else{
					toastr.success("Alteração salva com sucesso!");
				}
			},
			error: function(xhr,er) {
				alert("ERRO! " + xhr.status + ' - ' + xhr.statusText + 'Tipo de erro: ' + er);
				removeCarregando();
			}
		});
	}
	function deleta_campo(id){
		alert("DELETANTO");
		mostraCarregando();
		varUrl = "php_php/php_agendamentos.php?act=DELETA_CAMPO&id="+id;
		$.ajax({
			url: varUrl,
			type: "POST",
			beforeSend: function() {
				mostraCarregando();
			},
			complete: function() {
				removeCarregando();
			},
			success: function(response, textStatus, jqXHR) {
				removeCarregando();
				console.log(response);

			}
		});
	}
	function mostra_div(entrada){
		var display = document.getElementById(entrada).style.display;
		if(display == "none")
			document.getElementById(entrada).style.display = 'block';
		else
			document.getElementById(entrada).style.display = 'none';
	}
	function listaFormularios(){
		document.getElementById("divRetornoFormularios").innerHTML = "<img src='imagens/carregando.gif' width='50'>";
		varUrl = "php_php/php_agendamentos.php?act=LISTA_FORMULARIOS";
		var formData = {}
		$.ajax({
			url: varUrl,
			type: "POST",
			data : formData,
			async : true,
			success: function(response, textStatus, jqXHR) {
				document.getElementById("divRetornoFormularios").innerHTML = response;
				removeCarregando()
			},
			error: function (jqXHR, textStatus, errorThrown) {
				saida = "ERRO";
				removeCarregando();
			}
		});
	}
	function montaForm(formulario, campo){
		mostraCarregando();
		varUrl = "php_php/php_agendamentos.php?act=MONTA_FORMULARIO";
		var formData = {formId : formulario}
		$.ajax({
			url: varUrl,
			type: "POST",
			data : formData,
			async : true,
			success: function(response, textStatus, jqXHR) {
				document.getElementById("divFormularioRetorno").innerHTML = response;
				removeCarregando()
			},
			complete: function(){
				window.location.href = "#form_"+campo;
			},
			error: function (jqXHR, textStatus, errorThrown) {
				saida = "ERRO";
				removeCarregando();
			}
		});
	}
	function salvarComentario(id){
		var varComentario = document.getElementById("form_modal_comentarios_comentario").value;
		if (window.XMLHttpRequest) {
		document.getElementById("btnCarregando").innerHTML = '<img class="icone" src="imagens/carregando.gif" width="30">'
		xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				muda_comentario(id);
			}
		};
		xmlhttp.open("POST","ajax.php?act=AGENDA_MUDA_COMENTARIO&id="+id);
		xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");
		xmlhttp.send("comentario=" + encodeURIComponent(varComentario));
	}
	<?
		if($_GET['formulario']>0){
			?>
				$( document ).ready(function() {
					montaForm(<?=$_GET['formulario'];?>, <?=$_GET['campo'];?>);
					UIToastr.init();
				});
			<?
		}
	?>

	$('.btnComentario').on('click', function(e){	  e.preventDefault();	  $('#modalComentarios').modal('show').find('.modal-body').load($(this).attr('href'));	});
	$('.btnComentarioRevisor').on('click', function(e){	  e.preventDefault();	  $('#modalComentariosRevisor').modal('show').find('.modal-body').load($(this).attr('href'));	});
	$('.btnComentarioRevisor').on("shown.bs.modal", function () {		if ($(".modal-backdrop").length > 1) {			$(".modal-backdrop").not(':first').remove();		}		element.css('display', 'block');	});
	listaFormularios();
</script>
	<div class="modal fade" id="modalHistorico" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog modal-lg">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title" id="myModalLabel">Modal title</h4>
		  </div>
		  <div class="modal-body">
			  <a class="custom-close"> My Custom Close Link </a>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Fechar (SEM SALVAR)</button>
		  </div>
		</div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div>
	<div class="modal fade" id="modalComentarios" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog modal-lg">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title" id="myModalLabel">Comentários</h4>
		  </div>
		  <div class="modal-body">
			  <a class="custom-close"> My Custom Close Link </a>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Fechar (SEM SALVAR)</button>
		  </div>
		</div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div>
	<div class="modal fade" id="modalComentariosRevisor" role="dialog" data-dismiss="modal">
	  <div class="modal-dialog modal-lg">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title" id="myModalLabel">Comentários do revisor</h4>
		  </div>
		  <div class="modal-body">
			  <a class="custom-close"> My Custom Close Link </a>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Fechar (SEM SALVAR)</button>
		  </div>
		</div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div>
	<div id="modalMotivoAlteracao" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h4 class="modal-title">Motivo da alteração do(s) campo(s)</h4>
				</div>
				<div class="modal-body">
					<p> Descreva o motivo que o fez alterar o(s) campo(s) no campo abaixo e clique em Salvar</p>
					<textarea id="txtMotivoAlteracao" class='form-control' rows="5"></textarea>
				</div>
				<div class="modal-footer">
					<button type="button" data-dismiss="modal" class="btn dark btn-outline">Cancelar</button>
					<button type="button" data-dismiss="modal" class="btn green" onclick="salvarComMotivo();">Salvar</button>
				</div>
			</div>
		</div>
	</div>
</html>