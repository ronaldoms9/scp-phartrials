<?php
	include_once "seguranca.php";
?>
<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<?php
		include "global_css.php";
	?>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="//cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.3.1/css/buttons.dataTables.min.css">
	<link href="https://fonts.googleapis.com/css?family=Abel|Archivo+Narrow|PT+Sans+Narrow:700|Pragati+Narrow" rel="stylesheet">
	<style>
		.modal {
		  overflow-y:auto;
		}
		.lista_formularios{
			font-size: 13px !important;
			font-family: 'Abel', sans-serif;
		}
		.lista_formularios_desabilitado{
			font-size: 13px !important;
			font-family: 'Abel', sans-serif;
			color: #c0c0c0 !important;
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
		.content-novo{
		   max-height: 100%; /*altura da modal*/
		}
		.dialog-novo{
		   height: 100%; /*altura da view da modal*/
		   margin-top: 0;
		}
		.body-novo{
		   overflow: auto; /*habilita o overflow no corpo da modal*/
		}
		.divMsg{
			color: red;
			font-size: 14px;
			font-weight: bold;
		}
		.control-label{
			font-family: 'Abel';
			font-size: 15px;
		}
	</style>

</head>
    <body class="page-container-bg-solid">
        <div class="page-wrapper">
            <?php
               include "menu_principal.php";
            ?>
            <div class="page-wrapper-row full-height">
                <div class="page-wrapper-middle">
                    <div class="page-container">
                        <div class="page-content-wrapper">
                            <div class="page-head">
                                <div class="container-fluid">
                                    <div class="page-title">
                                        <h1><?=traduzir("CADASTRO DE CENTROS", $_SESSION['lang']);?> </h1>
                                    </div>
                                </div>
                            </div>
                            <div class="page-content">
                                <div class="container-fluid">

										<div class="page-content-inner">
											<div class="row widget-row">
												<div class="col-md-3">
													<div class="portlet light">
														<div class="portlet-title">


														</div>
														<div class="portlet-body">
															<div class="scroller" style="height:450px" data-rail-visible="1" data-rail-color="yellow" data-handle-color="#a1b2bd">
																<table width="100%" class="table table-hover table-condensed" style="background-color:white !important">
																	<thead class="header_formularios">
																		<th><center><?=traduzir("NRO");?></center></th>
																		<th><?=traduzir("NOME");?></th>
																	</thead>
																	<tbody id="retornoLista">

																	</tbody>
																</table>
															</div>
														</div>
													</div>
												</div>

												<div class="col-md-9">


												<div class="portlet light" style="width:100%">
													<div class="portlet-title fixo">
														<div class="caption font-blue-dark">
															<span><i class="fa fa-th font-blue-dark" title="Formulários"></i></span>
															<span class="caption-subject">&nbsp;&nbsp;<b>DADOS CADASTRAIS</b></span>
														</div>
														<div class="actions">
															<div class="btn-group">
																<a href="javascript:novoProjeto();" class="btn btn-circle btn-default"><i class="fa fa-plus"></i> Incluir <B>NOVO</B> centro</a>
															</div>
														</div>
													</div>
													<div class="portlet-body">
														<form class="form-horizontal" role="form" action="#" method="post" id="form_info" name="form_info" autocomplete="off">
                                                            <input type="hidden" class="form-control" id="form_id" name="form_id" value="0">
															<div class="form-body">
																<div class="form-group">
																	<label class="control-label col-md-2">Nome do Centro</label>
																	<div class="col-md-10">
																		<input type="text" class="form-control" id="form_nome" name="form_nome">
																	</div>
																</div>
																<div class="form-group">
																	<label class="control-label col-md-2">Email</label>
																	<div class="col-md-5">
																		<input type="text" class="form-control" id="form_email" name="form_email" >
																	</div>
																	<label class="control-label col-md-1">Telefone</label>
																	<div class="col-md-4">
																		<input type="text" class="form-control" id="form_telefone" name="form_telefone" >
																	</div>
																</div>
                                                                <div class="form-group">
																	<label class="control-label col-md-2">Endereço</label>
																	<div class="col-md-10">
																		<textarea class="form-control" id="form_endereco" name="form_endereco" rows="7" cols="30"></textarea>
																	</div>
																</div>
                                                                <div class="form-group">
																	<label class="control-label col-md-2">Investigador</label>
																	<div class="col-md-10">
																		<input type="text" class="form-control" id="form_investigador" name="form_investigador" >
																	</div>
                                                                </div>
                                                                <div class="form-group">
																	<label class="control-label col-md-2">Coordenador</label>
																	<div class="col-md-10">
																		<input type="text" class="form-control" id="form_coordenador" name="form_coordenador" >
																	</div>
																</div>
																<div class="form-group">
																	<label class="control-label col-md-2"></label>
																	<ul class="nav nav-tabs">
																		<li class="active">
																			<a href="#tab_1_1" data-toggle="tab" aria-expanded="true"> Dados do TR </a>
																		</li>
																		<li class="">
																			<a href="#tab_1_2" data-toggle="tab" aria-expanded="false"> Dados do CV </a>
																		</li>
																		<li class="">
																			<a href="#tab_1_3" data-toggle="tab" aria-expanded="false"> Dados dos Laboratórios </a>
																		</li>
																		<li class="">
																			<a href="#tab_1_5" data-toggle="tab" aria-expanded="false"> Dados do Corpo de Bombeiros </a>
																		</li>
																		<li class="">
																			<a href="#tab_1_4" data-toggle="tab" aria-expanded="false"> Dados do Centro de Pesquisa </a>
																		</li>
																	</ul>
																	<div class="tab-content">
																		<div class="tab-pane fade active in" id="tab_1_1">
																			<div class="form-group">
																				<label class="control-label col-md-2">Dados do TR</label>
																				<div class="col-md-10">
																					<textarea class="form-control" id="form_dados_tr" name="form_dados_tr" rows="7" cols="30"></textarea>
																				</div>
																			</div>
																		</div>
																		<div class="tab-pane fade" id="tab_1_2">
																			<div class="form-group">
																				<label class="control-label col-md-2">Dados do CV</label>
																				<div class="col-md-10">
																					<textarea class="form-control" id="form_dados_cv" name="form_dados_cv" rows="7" cols="30"></textarea>
																				</div>
																			</div>
																		</div>
																		<div class="tab-pane fade" id="tab_1_3">
																			<div class="form-group">
																				<label class="control-label col-md-2">Dados dos Laboratórios</label>
																				<div class="col-md-10">
																					<textarea class="form-control" id="form_dados_lab" name="form_dados_lab" rows="7" cols="30"></textarea>
																				</div>
																			</div>
																		</div>
																		<div class="tab-pane fade" id="tab_1_4">
																			<div class="form-group">
																				<label class="control-label col-md-2">Dados do Centro de Pesquisa</label>
																				<div class="col-md-10">
																					<textarea class="form-control" id="form_dados_centro" name="form_dados_centro" rows="7" cols="30"></textarea>
																				</div>
																			</div>
																		</div>
																		<div class="tab-pane fade" id="tab_1_5">
																			<div class="form-group">
																				<label class="control-label col-md-2">Dados do Corpo de Bombeiros</label>
																				<div class="col-md-10">
																					<textarea class="form-control" id="form_dados_bombeiros" name="form_dados_bombeiros" rows="7" cols="30"></textarea>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														</form>
													</div><hr>

													<div class="portlet-footer" style="text-align: center">
														<button class="btn btn-primary" onclick="javascript:salvarCentro();"> <i class="fa fa-save"></i> Salvar </button>
													</div>
												</div>
											</div>
										</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

	</body>
	<script src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js" type="text/javascript"></script>
	<script src="assets/global/plugins/jquery.min.js" type="text/javascript"></script>
	<script src="js/funcoes.js" type="text/javascript"></script>

	<?php
		include "rodape.php";
		include "global_js.php";
	?>


	<script>
		varEstado = "NOVO";
		function limpaCampos(){
			document.getElementById("form_info").reset();
			varEstado = "NOVO";
		}
		function novoProjeto(){
			mostraCarregando();
			limpaCampos();
			removeCarregando();
			varEstado = "NOVO";
		}
		function salvarCentro(){
			mostraCarregando();
			$.ajax({
				url: "php_php/php_centro.php?act=SALVA&tipo=" + varEstado,
				type: "POST",
				data: $("#form_info").serialize(),
				success: function(data){
					console.log("Retorno->"+data);
					alert("Operação realizada com sucesso");
					//if(varEstado == "NOVO"){
						iniciaLista();
					//}
					removeCarregando();
				},
				error: function(data){
					removeCarregando();
					alert("Erro ao salvar centro");
				}
			});
			if(varEstado == "NOVO"){
				varEstado = "ALTERAR";
			}else{
				varEstado = "ALTERAR";
			}
		}
		function iniciaLista(){
			mostraCarregando();
			$.ajax({
				url: "php_php/php_centro.php?act=LISTA",
				type: "POST",
				success: function(data){
					removeCarregando();
					document.getElementById("retornoLista").innerHTML = data;
				},
				error: function(data){
					removeCarregando();
					alert("Erro ao carregar lista de centros");
				}
			});
		}
		function selecionaCentro(id){
			varEstado = "ALTERAR";
			mostraCarregando();
			$.ajax({
				url: "php_php/php_centro.php?act=CONSULTA&id=" + id,
				type: "POST",
				success: function(data){
					removeCarregando();
					retorno = JSON.parse(data);
					if(retorno.resultado == "OK"){
                        document.getElementById("form_id").value           = retorno.id;
                        document.getElementById("form_nome").value         = retorno.nome;
                        document.getElementById("form_email").value        = retorno.email;
                        document.getElementById("form_telefone").value     = retorno.telefone;
                        document.getElementById("form_endereco").value     = retorno.endereco;
                        document.getElementById("form_investigador").value = retorno.investigador;
                        document.getElementById("form_coordenador").value  = retorno.coordenador;
                        document.getElementById("form_dados_tr").value     = retorno.dados_tr;
                        document.getElementById("form_dados_cv").value     = retorno.dados_cv;
                        document.getElementById("form_dados_lab").value    = retorno.dados_lab;
                        document.getElementById("form_dados_centro").value = retorno.dados_centro;
                        document.getElementById("form_dados_bombeiros").value = retorno.dados_bombeiros;
					}
				},
				error: function(data){
					removeCarregando();
					alert("Erro ao selecionar centro");
				}
			});
		}
		$(document).ready(function(){
			iniciaLista();
		});
	</script>
</html>