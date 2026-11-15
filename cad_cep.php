<?
	include_once "seguranca.php";
?>
<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<?
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
                                        <h1><?=traduzir("CADASTRO DE CEP", $_SESSION['lang']);?> </h1>
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
																<a href="javascript:novoCEP();" class="btn btn-circle btn-default"><i class="fa fa-plus"></i> Incluir <B>NOVO</B> CEP</a>
															</div>
														</div>
													</div>
													<div class="portlet-body">
														<form class="form-horizontal" role="form" action="#" method="post" id="form_info" name="form_info" autocomplete="off">
                                                            <input type="hidden" class="form-control" id="form_id" name="form_id" value="0">
															<div class="form-body">
																<div class="form-group">
																	<label class="control-label col-md-2">Nome do CEP</label>
																	<div class="col-md-10">
																		<input type="text" class="form-control" id="form_nome" name="form_nome">
																	</div>
																</div>
																<div class="form-group">
																	<label class="control-label col-md-2">Aprovacao</label>
																	<div class="col-md-5">
                                                                        <select id="form_aprovacao" name="form_aprovacao" class="form-control">
                                                                            <option value=""></option>
                                                                            <option value="1 - Sim">Sim</option>
                                                                            <option value="2 - Não">Não</option>
                                                                        </select>
																	</div>
																</div>
                                                                <div class="form-group">
																	<label class="control-label col-md-2">Validade da Aprovação</label>
																	<div class="col-md-10">
																		<input type="text" class="form-control" id="form_validade" name="form_validade" onkeypress="mascara(this, '##/##/####');">
																	</div>
																</div>
                                                                <div class="form-group">
																	<label class="control-label col-md-2">Composicao</label>
																	<div class="col-md-10">
																		<select class="form-control" id="form_composicao" name="form_composicao">
																			<option value=""></option>
																			<option value="1 - Sim">Sim</option>
                                                                            <option value="2 - Não">Não</option>
																		</select>
																	</div>
																</div>
																<div class="form-group">
																	<label class="control-label col-md-2">Validade da Composicao</label>
																	<div class="col-md-10">
																		<input type="text" class="form-control" id="form_comp_validade" name="form_comp_validade" onkeypress="mascara(this, '##/##/####');">
																	</div>

																</div>
															</div>
														</form>
													</div><hr>

													<div class="portlet-footer" style="text-align: center">
														<button class="btn btn-primary" onclick="javascript:salvarCEP();"> <i class="fa fa-save"></i> Salvar </button>
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

	<?
		include "rodape.php";
		include "global_js.php";
	?>


	<script>
		varEstado = "NOVO";
		function limpaCampos(){
			document.getElementById("form_info").reset();
			varEstado = "NOVO";
		}
		function novoCEP(){
			mostraCarregando();
			limpaCampos();
			removeCarregando();
			varEstado = "NOVO";
		}
		function salvarCEP(){
			mostraCarregando();
            console.log("php_php/php_cep.php?act=SALVA&tipo=" + varEstado);
			$.ajax({
				url: "php_php/php_cep.php?act=SALVA&tipo=" + varEstado + "&id=" + document.getElementById("form_id").value,
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
					alert("Erro ao salvar CEP");
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
				url: "php_php/php_cep.php?act=LISTA",
				type: "POST",
				success: function(data){
					removeCarregando();
					document.getElementById("retornoLista").innerHTML = data;
				},
				error: function(data){
					removeCarregando();
					alert("Erro ao carregar lista de CEPs");
				}
			});
		}
		function selecionaCEP(id){
			varEstado = "ALTERAR";
			mostraCarregando();
			$.ajax({
				url: "php_php/php_cep.php?act=CONSULTA&id=" + id,
				type: "POST",
				success: function(data){
					removeCarregando();
					retorno = JSON.parse(data);
					if(retorno.resultado == "OK"){
                        document.getElementById("form_id").value           = retorno.id;
                        document.getElementById("form_nome").value         = retorno.nome;
                        document.getElementById("form_aprovacao").value    = retorno.aprovacao;
                        document.getElementById("form_validade").value     = retorno.validade;
                        document.getElementById("form_composicao").value  = retorno.composicao;
                        document.getElementById("form_comp_validade").value = retorno.comp_validade;
					}
				},
				error: function(data){
					removeCarregando();
					alert("Erro ao selecionar CEP");
				}
			});
		}
		$(document).ready(function(){
			iniciaLista();
		});
	</script>
</html>