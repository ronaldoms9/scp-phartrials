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
                                        <h1>Gerador de Auditoria</h1>
                                    </div>
                                </div>
                            </div>
                            <div class="page-content">
                                <div class="container-fluid">
									<div class="page-content-inner">
										<div class="row widget-row">
											<div class="col-md-4">
												<div class="portlet light">
													<div class="portlet-title">
														<div class="caption font-blue-dark">
															<span><i class="fa fa-table font-blue-dark"></i></span>
															<span class="caption-subject">&nbsp;&nbsp;<b>TABELAS DO BANCO</b></span>
														</div>
													</div>
													<div class="portlet-body">
														<div class="scroller" style="height:600px" data-rail-visible="1" data-rail-color="yellow" data-handle-color="#a1b2bd">
															<table width="100%" class="table table-hover table-condensed" style="background-color:white !important">
																<thead class="header_formularios">
																	<th><center>Nome da Tabela</center></th>
																</thead>
																<tbody id="retornoListaTabelas">
																</tbody>
															</table>
														</div>
													</div>
												</div>
											</div>

											<div class="col-md-8">
												<div class="portlet light" style="width:100%">
													<div class="portlet-title fixo">
														<div class="caption font-blue-dark">
															<span><i class="fa fa-columns font-blue-dark" title="Campos"></i></span>
															<span class="caption-subject">&nbsp;&nbsp;<b>CAMPOS DA TABELA: <span id="nomeTabelaSelecionada">-</span></b></span>
														</div>
													</div>
													<div class="portlet-body">
														<div class="scroller" style="height:500px" data-rail-visible="1" data-rail-color="yellow" data-handle-color="#a1b2bd">
															<table width="100%" class="table table-striped table-bordered table-hover" id="tabelaCampos">
																<thead>
																	<tr>
																		<th>Nome do Campo</th>
																		<th>Tipo</th>
																		<th>Nulo</th>
																		<th>Chave</th>
																	</tr>
																</thead>
																<tbody id="retornoListaCampos">
																	<tr>
																		<td colspan="4" style="text-align: center;">Selecione uma tabela para visualizar os campos</td>
																	</tr>
																</tbody>
															</table>
														</div>
													</div>
													<div class="portlet-footer" style="text-align: center; padding: 15px;">
														<input type="hidden" id="tabelaSelecionada" value="">
														<button class="btn btn-success" onclick="javascript:criarCampoLogUsuario();" id="btnCriarCampo" disabled>
															<i class="fa fa-plus"></i> CRIAR campo log_usuario
														</button>
														&nbsp;&nbsp;
														<button class="btn btn-primary" onclick="javascript:criarTriggers();" id="btnCriarTrigger" disabled>
															<i class="fa fa-cog"></i> Criar Trigger
														</button>
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
		var tabelaSelecionada = "";

		function carregarTabelas(){
			mostraCarregando();
			$.ajax({
				url: "php_php/php_auditoria.php?act=LISTA_TABELAS",
				type: "POST",
				success: function(data){
					removeCarregando();
					document.getElementById("retornoListaTabelas").innerHTML = data;
				},
				error: function(data){
					removeCarregando();
					alert("Erro ao carregar lista de tabelas");
				}
			});
		}

		function selecionarTabela(nomeTabela){
			tabelaSelecionada = nomeTabela;
			document.getElementById("tabelaSelecionada").value = nomeTabela;
			document.getElementById("nomeTabelaSelecionada").innerHTML = nomeTabela;
			document.getElementById("btnCriarCampo").disabled = false;
			document.getElementById("btnCriarTrigger").disabled = false;
			
			mostraCarregando();
			$.ajax({
				url: "php_php/php_auditoria.php?act=LISTA_CAMPOS&tabela=" + nomeTabela,
				type: "POST",
				success: function(data){
					removeCarregando();
					document.getElementById("retornoListaCampos").innerHTML = data;
				},
				error: function(data){
					removeCarregando();
					alert("Erro ao carregar campos da tabela");
				}
			});
		}

		function criarCampoLogUsuario(){
			if(tabelaSelecionada == ""){
				alert("Selecione uma tabela primeiro");
				return;
			}

			if(!confirm("Deseja criar o campo log_usuario (integer) na tabela " + tabelaSelecionada + "?")){
				return;
			}

			mostraCarregando();
			$.ajax({
				url: "php_php/php_auditoria.php?act=CRIAR_CAMPO&tabela=" + tabelaSelecionada,
				type: "POST",
				success: function(data){
					removeCarregando();
					var retorno = JSON.parse(data);
					if(retorno.resultado == "OK"){
						alert(retorno.mensagem);
						selecionarTabela(tabelaSelecionada); // Recarrega os campos
					} else {
						alert(retorno.mensagem);
					}
				},
				error: function(data){
					removeCarregando();
					alert("Erro ao criar campo");
				}
			});
		}

		function criarTriggers(){
			if(tabelaSelecionada == ""){
				alert("Selecione uma tabela primeiro");
				return;
			}

			if(!confirm("Deseja criar triggers de auditoria para todos os campos da tabela " + tabelaSelecionada + "?\n\nIsso criará triggers que registram alterações na tabela log_alteracoes.")){
				return;
			}

			mostraCarregando();
			$.ajax({
				url: "php_php/php_auditoria.php?act=CRIAR_TRIGGERS&tabela=" + tabelaSelecionada,
				type: "POST",
				success: function(data){
					removeCarregando();
					var retorno = JSON.parse(data);
					if(retorno.resultado == "OK"){
						alert(retorno.mensagem);
					} else {
						alert(retorno.mensagem);
					}
				},
				error: function(data){
					removeCarregando();
					alert("Erro ao criar triggers");
				}
			});
		}

		$(document).ready(function(){
			carregarTabelas();
		});
	</script>

</html>