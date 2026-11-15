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
                                        <h1><?=traduzir("CADASTRO DE PROJETOS", $_SESSION['lang']);?> </h1>
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
															<div style="padding-bottom: 10px;">
																<input type="text" class="form-control" id="filtroListaProjetos" placeholder="Filtrar por nome" oninput="filtraListaProjetos()" autocomplete="off">
															</div>
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
																<a href="javascript:novoProjeto();" class="btn btn-circle btn-default"><i class="fa fa-plus"></i> Incluir <B>NOVO</B> projeto</a>
															</div>
														</div>
													</div>
													<div class="portlet-body">
														<form class="form-horizontal" role="form" action="#" method="post" id="form_info" name="form_info" autocomplete="off">

															<div class="form-body">
																<div class="form-group">
																	<label class="control-label col-md-2">Código da Monitoria</label>
																	<div class="col-md-4">
																		<input type="hidden" class="form-control" id="form_id" name="form_id" value="0">
																		<input type="text" class="form-control" id="form_numero" name="form_numero">
																	</div>
																	<label class="control-label col-md-1">Tipo</label>
																	<div class="col-md-5">
																		<select class="form-control" id="form_tipo" name="form_tipo">
																			<option value=""></option>
																			<option value="BIO">Bioequivalência</option>
																			<option value="BIOD">Biodisponibilidade</option>
																			<option value="PESQ">Pesquisa Clínica</option>
																			<option value="REL">Relatório</option>
																			<option value="PROT">Protocolo</option>
																			<option value="PAPER">Paper</option>
																		</select>
																	</div>
																</div>

																<div class="form-group">
																	<label class="control-label col-md-2">Patrocinador</label>
																	<div class="col-md-10">
																		<select name="form_patrocinador" id="form_patrocinador" class="form-control">
																			<option value=""></option>
																			<?php
																				$query = "select * from cad_patrocinador order by nome";
																				$res_p   = $conexao->pesquisa($query);
																				while($row_p = mysqli_fetch_assoc($res_p)){
																					echo "<option value='".$row_p['id']."' ";
																					echo ">".$row_p['nome']."</option>";
																				}
																			?>
																		</select>
																	</div>
																</div>
																<div class="form-group">
																	<label class="control-label col-md-2">Código Prot</label>
																	<div class="col-md-3">
																		<input type="text" class="form-control" id="form_codigo" name="form_codigo" >
																	</div>
																	<label class="control-label col-md-1">Versão Prot</label>
																	<div class="col-md-2">
																		<input type="text" class="form-control" id="form_versao" name="form_versao" style="text-align: center">
																	</div>
																	<label class="control-label col-md-1">Data Prot</label>
																	<div class="col-md-3">
																		<input type="text" class="form-control" id="form_data_protocolo" name="form_data_protocolo" style="text-align: center" onkeyup="mascara(this, '##/##/####')">
																	</div>
																</div>

																<div class="form-group">
																	<label class="control-label col-md-2">Nome do projeto</label>
																	<div class="col-md-10">
																		<input type="text" class="form-control" id="form_nome" name="form_nome">
																	</div>
																</div>

																<div class="form-group">
																	<label class="control-label col-md-2">Períodos</label>
																	<div class="col-md-7">
																		<input type="text" class="form-control" id="form_periodos" name="form_periodos" >
																	</div>
																</div>
																<div class="form-group">
																	<label class="control-label col-md-2">Grupos</label>
																	<div class="col-md-7">
																		<input type="text" class="form-control" id="form_grupos" name="form_grupos" >
																	</div>
																</div>
																<div class="form-group">
																	<label class="control-label col-md-2">N Participantes</label>
																	<div class="col-md-7">
																		<input type="text" class="form-control" id="form_n" name="form_n">
																	</div>
																</div>
																<div class="form-group">
																	<label class="control-label col-md-2">Idade mínima</label>
																	<div class="col-md-4">
																		<input type="text" class="form-control" id="form_idade_minima" name="form_idade_minima" style="text-align: center">
																	</div>
																	<label class="control-label col-md-2">Idade máxima</label>
																	<div class="col-md-4">
																		<input type="text" class="form-control" id="form_idade_maxima" name="form_idade_maxima" style="text-align: center">
																	</div>
																</div>
																<div class="form-group">
																	<label class="control-label col-md-2">Coletas por período</label>
																	<div class="col-md-10">
																		<textarea class="form-control"
																			id="form_coletas"
																			name="form_coletas"
																			rows="3"></textarea>
																	</div>
																</div>
																<div class="form-group">
																	<label class="control-label col-md-2">Descrição do Projeto</label>
																	<div class="col-md-10">
																		<textarea class="form-control" id="form_descricao" name="form_descricao" rows="7" cols="30"></textarea>
																	</div>
																</div>
																<div class="form-group">
																	<label class="control-label col-md-2">Comentários Adicionais</label>
																	<div class="col-md-10">
																		<textarea class="form-control"
																			id="form_comentarios"
																			name="form_comentarios"
																			rows="3"></textarea>
																	</div>
																</div>
																<hr>
																<h4>Plano de Monitoria</h4>


																<div class="form-group">
																	<label class="control-label col-md-2">Versão Plano Monitoria</label>
																	<div class="col-md-3">
																		<input type="text" class="form-control" id="versao_plano" name="versao_plano" style="text-align: center">
																	</div>
																</div>

																<div class="form-group" >
                                                                    <label class="control-label col-md-2">Atividades do Plano de Monitoria</label>
                                                                    <div class="col-md-9">
                                                                        <div class="mt-checkbox-list">
                                                                            <label class="mt-checkbox mt-checkbox-outline">
                                                                                <input type="checkbox" id="chkOpc1" name="chkOpc1" value="1"> Arquivo do Investigador
                                                                                <span></span>
                                                                            </label>
                                                                            <label class="mt-checkbox mt-checkbox-outline">
																				<input type="checkbox" id="chkOpc2" name="chkOpc2" value="1"> Recrutamento e seleção
																				<span></span>
                                                                            </label>
                                                                            <label class="mt-checkbox mt-checkbox-outline">
																				<input type="checkbox" id="chkOpc3" name="chkOpc3" value="1"> Medicamentos
																				<span></span>
                                                                            </label>
																			<label class="mt-checkbox mt-checkbox-outline">
																				<input type="checkbox" id="chkOpc4" name="chkOpc4" value="1"> Pré-Internação
																				<span></span>
                                                                            </label>
																			<label class="mt-checkbox mt-checkbox-outline">
																				<input type="checkbox" id="chkOpc5" name="chkOpc5" value="1"> Administração e coletas
																				<span></span>
                                                                            </label>
																			<label class="mt-checkbox mt-checkbox-outline">
																				<input type="checkbox" id="chkOpc6" name="chkOpc6" value="1"> Pós Estudo
																				<span></span>
                                                                            </label>
																			<label class="mt-checkbox mt-checkbox-outline">
																				<input type="checkbox" id="chkOpc7" name="chkOpc7" value="1"> Encerramento
																				<span></span>
                                                                            </label>
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

			document.getElementById("chkOpc1").checked = true;
			document.getElementById("chkOpc2").checked = true;
			document.getElementById("chkOpc3").checked = true;
			document.getElementById("chkOpc4").checked = true;
			document.getElementById("chkOpc5").checked = true;
			document.getElementById("chkOpc6").checked = true;
			document.getElementById("chkOpc7").checked = true;


			document.getElementById("form_numero").focus();


		}
		function salvarProjeto(){
			if(document.getElementById("form_idade_minima").value == ""){
				alert("O campo Idade mínima é obrigatório");
				return;
			}
			if(document.getElementById("form_idade_maxima").value == ""){
				alert("O campo Idade máxima é obrigatório");
				return;
			}
			if(document.getElementById("form_idade_minima").value > document.getElementById("form_idade_maxima").value){
				alert("A idade mínima não pode ser maior que a idade máxima");
				return;
			}
			mostraCarregando();
			var dados = $("#form_info").serializeArray().filter(function(campo) {
				return !/^chkOpc[1-7]$/.test(campo.name);
			});
			for (var i = 1; i <= 7; i++) {
				dados.push({
					name: "chkOpc" + i,
					value: document.getElementById("chkOpc" + i).checked ? "1" : "0"
				});
			}
			$.ajax({
				url: "php_php/cfg_projeto.php?act=SALVA&tipo=" + varEstado,
				type: "POST",
				data: $.param(dados),
				success: function(data){
					console.log("Retorno->"+data);
					alert("Operação realizada com sucesso");
					//if(varEstado == "NOVO"){
						iniciaLista();
						carrega_projeto();
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
		function filtraListaProjetos(){
			var termo = document.getElementById("filtroListaProjetos").value.toLowerCase().trim();
			var linhas = document.querySelectorAll("#retornoLista tr");
			for (var i = 0; i < linhas.length; i++) {
				var texto = (linhas[i].textContent || "").toLowerCase();
				linhas[i].style.display = (termo === "" || texto.indexOf(termo) !== -1) ? "" : "none";
			}
		}
		function iniciaLista(){
			mostraCarregando();
			$.ajax({
				url: "php_php/cfg_projeto.php?act=LISTA",
				type: "POST",
				success: function(data){
					removeCarregando();
					document.getElementById("retornoLista").innerHTML = data;
					filtraListaProjetos();
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
				url: "php_php/cfg_projeto.php?act=CONSULTA&id=" + id,
				type: "POST",
				success: function(data){
					removeCarregando();
					retorno = JSON.parse(data);
					if(retorno.resultado == "OK"){
						document.getElementById("form_id").value           = retorno.id;
						document.getElementById("form_numero").value       = retorno.numero;
						document.getElementById("form_tipo").value         = retorno.tipo;
						document.getElementById("form_nome").value         = retorno.nome;
						document.getElementById("form_descricao").value    = retorno.descricao;
						document.getElementById("form_patrocinador").value = retorno.patrocinador;
						document.getElementById("form_periodos").value     = retorno.periodos;
						document.getElementById("form_grupos").value       = retorno.grupos;
						document.getElementById("form_n").value            = retorno.n;
						document.getElementById("form_versao").value       = retorno.versao;
						document.getElementById("form_codigo").value       = retorno.codigo;
						document.getElementById("versao_plano").value       = retorno.versao_plano;
						document.getElementById("form_data_protocolo").value = retorno.data_protocolo;
						document.getElementById("form_coletas").value       = retorno.coletas;
						document.getElementById("form_comentarios").value   = retorno.comentarios;
						document.getElementById("form_idade_minima").value = retorno.idade_minima;
						document.getElementById("form_idade_maxima").value = retorno.idade_maxima;
						document.getElementById("chkOpc1").checked = retorno.chkOpc1 == "1";
						document.getElementById("chkOpc2").checked = retorno.chkOpc2 == "1";
						document.getElementById("chkOpc3").checked = retorno.chkOpc3 == "1";
						document.getElementById("chkOpc4").checked = retorno.chkOpc4 == "1";
						document.getElementById("chkOpc5").checked = retorno.chkOpc5 == "1";
						document.getElementById("chkOpc6").checked = retorno.chkOpc6 == "1";
						document.getElementById("chkOpc7").checked = retorno.chkOpc7 == "1";
					}
				},
				error: function(data){
					removeCarregando();
					alert("Erro ao selecionar projeto");
				}
			});
		}
		$(document).ready(function(){
			iniciaLista();
		});
	</script>
</html>