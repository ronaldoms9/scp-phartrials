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
                                        <h1><?=traduzir("CADASTRO DE PATROCINADORES", $_SESSION['lang']);?> </h1>
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
																		<th><?=traduzir("Patrocinador");?></th>
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
																<a href="javascript:novoProjeto();" class="btn btn-circle btn-default"><i class="fa fa-plus"></i> Incluir <B>NOVO</B> patrocinador</a>
																<a href="javascript:pessoas();" class="btn btn-circle btn-default" id="btn_pessoas"><i class="fa fa-users"></i> Pessoas</a>
															</div>
														</div>
													</div>
													<div class="portlet-body">
														<form class="form-horizontal" role="form" action="#" method="post" id="form_info" name="form_info" autocomplete="off">
															<input type="hidden" class="form-control" id="form_id" name="form_id" value="0">

															<div class="form-body">
																<div class="form-group">
																	<label class="control-label col-md-1">Nome</label>
																	<div class="col-md-7">
																		<input type="text" class="form-control" id="form_nome" name="form_nome">
																	</div>
																</div>
																<div class="form-group" style="display:none">
																	<label class="control-label col-md-1">Responsável</label>
																	<div class="col-md-7">
																		<input type="text" class="form-control" id="form_responsavel" name="form_responsavel">
																	</div>
																</div>
																<div class="form-group">
																	<label class="control-label col-md-1">Endereço</label>
																	<div class="col-md-11">
																		<textarea class="form-control" id="form_endereco" name="form_endereco" rows="5" cols="30"></textarea>
																	</div>
																</div>
																<div style="display:none">
																	<div class="form-group">
																		<label class="control-label col-md-1">Email 1</label>
																		<div class="col-md-5">
																			<input type="text" class="form-control" id="form_email1" name="form_email1" style="text-align: center">
																		</div>
																		<label class="control-label col-md-2">Telefone 1</label>
																		<div class="col-md-2">
																			<input type="text" class="form-control" id="form_telefone1" name="form_telefone1" style="text-align: center">
																		</div>
																	</div>
																	<div class="form-group">
																		<label class="control-label col-md-1">Email 2</label>
																		<div class="col-md-5">
																			<input type="text" class="form-control" id="form_email2" name="form_email2" style="text-align: center">
																		</div>
																		<label class="control-label col-md-2">Telefone 2</label>
																		<div class="col-md-2">
																			<input type="text" class="form-control" id="form_telefone2" name="form_telefone2" style="text-align: center">
																		</div>
																	</div>
																</div>
															</div>
														</form>
													</div><hr>
													<div class="portlet-footer" style="text-align: center">
														<button class="btn btn-primary" onclick="javascript:salvarProjeto();"> <i class="fa fa-save"></i> Salvar </button>
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
	<?php
		include "rodape.php";
		include "global_js.php";
	?>


	<script>
		function pessoas(){
			if(varEstado == "NOVO"){
				alert("Selecione um patrocinador para ver as pessoas");
			}else{
				window.location.href = "cfg_patrocinador_pessoas.php?id=" + document.getElementById("form_id").value;
			}
		}
		varEstado = "NOVO";
		function limpaCampos(){
			document.getElementById("form_info").reset();
			varEstado = "NOVO";
		}
		function novoProjeto(){
			mostraCarregando();
			limpaCampos();
			removeCarregando();
		}
		function salvarProjeto(){
			mostraCarregando();
			$.ajax({
				url: "php_php/php_cad_patrocinador.php?act=SALVA&tipo=" + varEstado,
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
					alert("Erro ao salvar projeto");
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
				url: "php_php/php_cad_patrocinador.php?act=LISTA",
				type: "POST",
				success: function(data){
					removeCarregando();
					document.getElementById("retornoLista").innerHTML = data;
				},
				error: function(data){
					removeCarregando();
					alert("Erro ao carregar lista de projetos");
				}
			});
		}
		function selecionaEstudo(id){
			varEstado = "ALTERAR";
			mostraCarregando();
			$.ajax({
				url: "php_php/php_cad_patrocinador.php?act=CONSULTA&id=" + id,
				type: "POST",
				success: function(data){
					console.log("Retorno->"+data);
					removeCarregando();
					retorno = JSON.parse(data);
					if(retorno.resultado == "OK"){
						document.getElementById("form_id").value           = retorno.id;
						document.getElementById("form_nome").value         = retorno.nome;
						document.getElementById("form_responsavel").value  = retorno.responsavel;
						document.getElementById("form_endereco").value    = retorno.endereco;
					}
				},
				error: function(data){
					removeCarregando();
					alert("Erro ao selecionar patrocinador");
				}
			});
		}
		$(document).ready(function(){
			varEstado = "NOVO";
			iniciaLista();
			<?php
			if($_GET['id'] != 0){
				echo "selecionaEstudo(".$_GET['id'].");";
			}
			?>
		});
	</script>
</html>