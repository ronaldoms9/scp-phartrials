<?php
	include_once "seguranca.php";
	$CENTRO = $_GET['centro'];
?>
<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
	<?php
		include "global_css.php";
	?>
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
															<div class="scroller" style="height:450px" data-rail-visible="1" data-rail-color="yellow" data-handle-color="#a1b2bd">
																<table width="100%" class="table table-hover table-condensed" style="background-color:white !important">
																	<thead class="header_formularios">
																		<th><center><?=traduzir("NRO");?></center></th>
																		<th><?=traduzir("NOME");?></th>
																	</thead>
																	<tbody>
																		<?php
																			$query = "select
																						id,
																						numero,
																						nome
																							from proj_projeto
																						order by numero";
																			$res   = $conexao->pesquisa($query);
																			while($row = mysqli_fetch_assoc($res)){
																		?>
																				<tr onclick='javascript:selecionaEstudo(<?=$row['id'];?>)' class='lista_formularios' style="cursor: pointer";>
																					<td width="40"><center><b><?=$row['numero'];?></center></b></td>
																					<td>
																						<?php
																							echo $row['nome'];

																						?>
																					</td>
																				</tr>
																		<?php
																			}
																		?>
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
																	<label class="control-label col-md-2">Número do projeto</label>
																	<div class="col-md-3">
																		<input type="hidden" class="form-control" id="form_id" name="form_id">
																		<input type="text" class="form-control" id="form_numero" name="form_numero">
																	</div>
																	<label class="control-label col-md-1">Tipo</label>
																	<div class="col-md-3">
																		<select class="form-control" id="form_tipo" name="form_tipo">
																			<option value=""></option>
																			<option value="BIO">Bioequivalência</option>
																			<option value="PESQ">Pesquisa Clínica</option>
																			<option value="REL">Relatório</option>
																			<option value="PROT">Protocolo</option>
																			<option value="PAPER">Paper</option>
																		</select>
																	</div>
																</div>
																<div class="form-group">
																	<label class="control-label col-md-2">Nome do projeto</label>
																	<div class="col-md-7">
																		<input type="text" class="form-control" id="form_nome" name="form_nome">
																	</div>
																</div>
																<div class="form-group">
																	<label class="control-label col-md-2">Descrição do Projeto</label>
																	<div class="col-md-10">
																		<textarea class="form-control" id="form_descricao" name="form_descricao" rows="7" cols="30"></textarea>
																	</div>
																</div>
															</div>

														</form>


														<form class="form-horizontal" role="form" action="#" method="post" id="form_info" name="form_info" autocomplete="off">
															<input autocomplete="off" name="hidden" type="text" style="display:none;">
															<table width="100%" class="table table-striped" style="font-family: 'Abel';font-size: 22px;">
																<tbody>
																															<tr>
																									<td>
																									<table width="100%">
																										<tbody><tr>
																											<td class="tabela-body">
																												<div id="divTexto_TRI_EAG_SN_REAL">Foi realizado o Teste de Gravidez?</div>
																											</td>
																										</tr>
																									</tbody></table>
																									</td>
																									<!-- Início do campo de 1 digitação -->
																																											<td style="vertical-align:middle" width="22%">
																										<select onkeyup="" onkeypress="" onblur="valida(this.value,this.min,this.max,'Foi realizado o Teste de Gravidez?',this);;" onchange="javascript:alteraMudanca();valida_data(this,'select');validaRef(this);validaTexto2();" oninput="" tipo-de-campo="select" data-valores-condicional="|" data-dupla_digitacao_campo="form_544_dupla_digitacao" data-dupla_digitacao_original="form_544" data-condicional-vermelho="" data-condicional-vermelho-alerta="" data-condicional-alerta="" data-qtd-decimal="" data-tipo-validar="" data-condicional="" data-condicional-valor="" data-condicional-id="" data-dezoito-anos="" data-menor-termo="" data-igual-menor="" data-igual-maior="" data-termo="" data-data_termo="20250907" e_horario="" data-tipo="select" data-alerta-termo="" data-obrigatorio="on" data-readonly="" data-senha="" data-calc-tipo="" data-calc-field1="" data-calc-field1_valor="" data-calc-field1-id="" data-calc-field2="" data-calc-field2_valor="" data-calc-field2-id="" data-campo="73" data-enfinal="" indice="1" monitoria-status="0" alterado-status="0" valor-original="" v2condicional="" .="" v2nomecampo="TRI_EAG_SN_REAL" ,="" v2campo1="" v2campo2="" data-referencia-e="" data-referencia-valor="" texto1="Foi realizado o Teste de Gravidez?" texto2="" condtext2="" class="form-control controle  obrigatorio " name="717756nomeCampo" id="form_544" style="border: 1.5px solid rgb(219, 105, 105);">      <option value=""></option><option value="1 - Sim">1 - Sim</option><option value="2 - Não">2 - Não</option></select><center><span class="legenda"></span></center>
																													<div id="divMsg_544" class="divMsg"></div>

																										</td>
																																								<!-- Coluna de botões -->
																								<td style="vertical-align:middle;align:right; text-align:right !important"><center>
																																									<div class="btn-group" style="align:right; text-align:right !important">
																										<!--Botão Monitora-->
																																													<button type="button" class="btn btn-default btn-sm" onclick="javascript:monitora(544);"><span id="divMonitora544" name="divMonitora544"><i class="fa fa-square-o fa-sm" title="Não monitorado"></i></span>																			</button>
																																												<!--Botão Histórico (Audit Trail)-->
																										<button type="button" class="btn btn-default btn-sm btnHistorico" data-toggle="modal" data-target="#modalHistoricoNovo" href="modal_ecrf_historico.php?id=544"><i class="fa fa-history fa-sm" title="Histórico [AuditTrail]"></i>																		</button>
																										<!--Botão Queries-->
																										<a name="anc_544"></a>

																										<button type="button" class="btn btn-default btn-sm  btnQuery" onclick="btnQueryClick('ecrf_formularios_query.php?id=544')" id="btnQuery_544" href="ecrf_formularios_query.php?id=544">
																										<i class="fa fa-question-circle-o fa-sm" title="Queries/Questionamentos"></i>																		</button>

																										<button type="button" class="btn btn-default btn-sm btnHistorico" data-toggle="modal" data-target="#modalComentarios" href="modal_ecrf_comentarios.php?id=544"><div id="divComentario_544"><i class="fa fa-commenting-o fa-sm " title="Comentários"></i></div>																		</button>
																																														<button type="button" class="btn btn-default btn-sm" onclick="javascript:monitoraDM(544);"><span id="divMonitoraDM544" name="divMonitoraDM544"><i class="fa fa-square-o fa-sm" title="Não monitorado DM"></i></span>																				</button>

																												<button type="button" class="btn btn-default btn-sm  btnQuery" onclick="btnQueryDMClick('ecrf_formularios_query_dm.php?id=544')" id="btnQueryDM_544" href="ecrf_formularios_query_dm.php?id=544">
																												<i class="fa fa-question-circle-o fa-sm" title="Queries/Questionamentos"></i>



																																												</button></div>
																								</center></td>
																																					</tr>
																																				<tr>
																									<td>
																									<table width="100%">
																										<tbody><tr>
																											<td class="tabela-body">
																												<div id="divTexto_TRI_EAG_SN_REAL_N_PQ">Porque o Teste de Gravidez não foi realizado?</div>
																											</td>
																										</tr>
																									</tbody></table>
																									</td>
																									<!-- Início do campo de 1 digitação -->
																																											<td style="vertical-align:middle" width="22%">
																										<textarea onkeyup="" onkeypress="" onblur="valida(this.value,this.min,this.max,'Porque o Teste de Gravidez não foi realizado?',this);;" onchange="javascript:alteraMudanca();valida_data(this,'caixa');validaRef(this);validaTexto2();" oninput="" tipo-de-campo="caixa" data-valores-condicional="|" data-dupla_digitacao_campo="form_545_dupla_digitacao" data-dupla_digitacao_original="form_545" data-condicional-vermelho="" data-condicional-vermelho-alerta="" data-condicional-alerta="" data-qtd-decimal="" data-tipo-validar="" data-condicional="IF;TRI_EAG_SN_REAL;=;2 - Não" data-condicional-valor="" data-condicional-id="544" data-dezoito-anos="" data-menor-termo="" data-igual-menor="" data-igual-maior="" data-termo="" data-data_termo="20250907" e_horario="" data-tipo="caixa" data-alerta-termo="" data-obrigatorio="on" data-readonly="" data-senha="" data-calc-tipo="" data-calc-field1="" data-calc-field1_valor="" data-calc-field1-id="" data-calc-field2="" data-calc-field2_valor="" data-calc-field2-id="" data-campo="74" data-enfinal="" indice="2" monitoria-status="0" alterado-status="0" valor-original="" v2condicional="IF;TRI_EAG_SN_REAL;=;2 - Não" .="" v2nomecampo="TRI_EAG_SN_REAL_N_PQ" ,="" v2campo1="" v2campo2="" data-referencia-e="" data-referencia-valor="" texto1="Porque o Teste de Gravidez não foi realizado?" texto2="" condtext2="" rows="6" style="width:100%" name="1529570nomeCampo" class="form-control  obrigatorio  v2HabilitaCondicional " id="form_545" disabled=""></textarea><center><span class="legenda"></span></center>
																													<div id="divMsg_545" class="divMsg"></div>

																										</td>
																																								<!-- Coluna de botões -->
																								<td style="vertical-align:middle;align:right; text-align:right !important"><center>
																																									<div class="btn-group" style="align:right; text-align:right !important">
																										<!--Botão Monitora-->
																																													<button type="button" class="btn btn-default btn-sm" onclick="javascript:monitora(545);"><span id="divMonitora545" name="divMonitora545"><i class="fa fa-square-o fa-sm" title="Não monitorado"></i></span>																			</button>
																																												<!--Botão Histórico (Audit Trail)-->
																										<button type="button" class="btn btn-default btn-sm btnHistorico" data-toggle="modal" data-target="#modalHistoricoNovo" href="modal_ecrf_historico.php?id=545"><i class="fa fa-history fa-sm" title="Histórico [AuditTrail]"></i>																		</button>
																										<!--Botão Queries-->
																										<a name="anc_545"></a>

																										<button type="button" class="btn btn-default btn-sm  btnQuery" onclick="btnQueryClick('ecrf_formularios_query.php?id=545')" id="btnQuery_545" href="ecrf_formularios_query.php?id=545">
																										<i class="fa fa-question-circle-o fa-sm" title="Queries/Questionamentos"></i>																		</button>

																										<button type="button" class="btn btn-default btn-sm btnHistorico" data-toggle="modal" data-target="#modalComentarios" href="modal_ecrf_comentarios.php?id=545"><div id="divComentario_545"><i class="fa fa-commenting-o fa-sm " title="Comentários"></i></div>																		</button>
																																														<button type="button" class="btn btn-default btn-sm" onclick="javascript:monitoraDM(545);"><span id="divMonitoraDM545" name="divMonitoraDM545"><i class="fa fa-square-o fa-sm" title="Não monitorado DM"></i></span>																				</button>

																												<button type="button" class="btn btn-default btn-sm  btnQuery" onclick="btnQueryDMClick('ecrf_formularios_query_dm.php?id=545')" id="btnQueryDM_545" href="ecrf_formularios_query_dm.php?id=545">
																												<i class="fa fa-question-circle-o fa-sm" title="Queries/Questionamentos"></i>



																																												</button></div>
																								</center></td>
																																					</tr>
																																				<tr>
																									<td>
																									<table width="100%">
																										<tbody><tr>
																											<td class="tabela-body">
																												<div id="divTexto_TRI_EAG_DT_DATA">Data da realização do exame</div>
																											</td>
																										</tr>
																									</tbody></table>
																									</td>
																									<!-- Início do campo de 1 digitação -->
																																											<td style="vertical-align:middle" width="22%">
																										<input autocomplete="new-password" maxlength="10" onkeyup="mascara(this, '##/##/####');" onkeypress=" NumerOnly();" onblur="valida(this.value,this.min,this.max,'Data da realização do exame',this);;" onchange="javascript:alteraMudanca();valida_data(this,'data');validaRef(this);validaTexto2();" oninput="qtdMaxCaracteres(this,10);" tipo-de-campo="data" data-valores-condicional="|" data-dupla_digitacao_campo="form_546_dupla_digitacao" data-dupla_digitacao_original="form_546" data-condicional-vermelho="" data-condicional-vermelho-alerta="" data-condicional-alerta="" data-qtd-decimal="10" data-tipo-validar="" data-condicional="IF;TRI_EAG_SN_REAL;=;1 - Sim" data-condicional-valor="" data-condicional-id="544" data-dezoito-anos="" data-menor-termo="" data-igual-menor="on" data-igual-maior="" data-termo="on" data-data_termo="20250907" e_horario="" data-tipo="data" data-alerta-termo="" data-obrigatorio="on" data-readonly="" data-senha="" data-calc-tipo="" data-calc-field1="" data-calc-field1_valor="" data-calc-field1-id="" data-calc-field2="" data-calc-field2_valor="" data-calc-field2-id="" data-campo="75" data-enfinal="" indice="3" monitoria-status="0" alterado-status="0" valor-original="" v2condicional="IF;TRI_EAG_SN_REAL;=;1 - Sim" .="" v2nomecampo="TRI_EAG_DT_DATA" ,="" v2campo1="" v2campo2="" data-referencia-e="" data-referencia-valor="" texto1="Data da realização do exame" texto2="" condtext2="" width="100%" type="text" name="889784nomeCampo" style="text-align: center;" class="form-control form-control-inline date-picker controle  obrigatorio  v2HabilitaCondicional " id="form_546" value="" disabled=""><center><span class="legenda">(dd/mm/aaaa)</span></center>
																													<div id="divMsg_546" class="divMsg"></div>

																										</td>
																																								<!-- Coluna de botões -->
																								<td style="vertical-align:middle;align:right; text-align:right !important"><center>
																																									<div class="btn-group" style="align:right; text-align:right !important">
																										<!--Botão Monitora-->
																																													<button type="button" class="btn btn-default btn-sm" onclick="javascript:monitora(546);"><span id="divMonitora546" name="divMonitora546"><i class="fa fa-square-o fa-sm" title="Não monitorado"></i></span>																			</button>
																																												<!--Botão Histórico (Audit Trail)-->
																										<button type="button" class="btn btn-default btn-sm btnHistorico" data-toggle="modal" data-target="#modalHistoricoNovo" href="modal_ecrf_historico.php?id=546"><i class="fa fa-history fa-sm" title="Histórico [AuditTrail]"></i>																		</button>
																										<!--Botão Queries-->
																										<a name="anc_546"></a>

																										<button type="button" class="btn btn-default btn-sm  btnQuery" onclick="btnQueryClick('ecrf_formularios_query.php?id=546')" id="btnQuery_546" href="ecrf_formularios_query.php?id=546">
																										<i class="fa fa-question-circle-o fa-sm" title="Queries/Questionamentos"></i>																		</button>

																										<button type="button" class="btn btn-default btn-sm btnHistorico" data-toggle="modal" data-target="#modalComentarios" href="modal_ecrf_comentarios.php?id=546"><div id="divComentario_546"><i class="fa fa-commenting-o fa-sm " title="Comentários"></i></div>																		</button>
																																														<button type="button" class="btn btn-default btn-sm" onclick="javascript:monitoraDM(546);"><span id="divMonitoraDM546" name="divMonitoraDM546"><i class="fa fa-square-o fa-sm" title="Não monitorado DM"></i></span>																				</button>

																												<button type="button" class="btn btn-default btn-sm  btnQuery" onclick="btnQueryDMClick('ecrf_formularios_query_dm.php?id=546')" id="btnQueryDM_546" href="ecrf_formularios_query_dm.php?id=546">
																												<i class="fa fa-question-circle-o fa-sm" title="Queries/Questionamentos"></i>



																																												</button></div>
																								</center></td>
																																					</tr>
																																				<tr>
																									<td>
																									<table width="100%">
																										<tbody><tr>
																											<td class="tabela-body">
																												<div id="divTexto_TRI_EAG_RES">Resultado</div>
																											</td>
																										</tr>
																									</tbody></table>
																									</td>
																									<!-- Início do campo de 1 digitação -->
																																											<td style="vertical-align:middle" width="22%">
																										<select onkeyup="" onkeypress="" onblur="valida(this.value,this.min,this.max,'Resultado',this);alertas(this,'VALOR;1 - Positivo;*Por favor preencher formulário de EA de gestação');" onchange="javascript:alteraMudanca();valida_data(this,'select');validaRef(this);validaTexto2();" oninput="" tipo-de-campo="select" data-valores-condicional="|" data-dupla_digitacao_campo="form_547_dupla_digitacao" data-dupla_digitacao_original="form_547" data-condicional-vermelho="" data-condicional-vermelho-alerta="" data-condicional-alerta="VALOR;1 - Positivo;*Por favor preencher formulário de EA de gestação" data-qtd-decimal="" data-tipo-validar="" data-condicional="IF;TRI_EAG_SN_REAL;=;1 - Sim" data-condicional-valor="" data-condicional-id="544" data-dezoito-anos="" data-menor-termo="" data-igual-menor="" data-igual-maior="" data-termo="" data-data_termo="20250907" e_horario="" data-tipo="select" data-alerta-termo="" data-obrigatorio="" data-readonly="" data-senha="" data-calc-tipo="" data-calc-field1="" data-calc-field1_valor="" data-calc-field1-id="" data-calc-field2="" data-calc-field2_valor="" data-calc-field2-id="" data-campo="76" data-enfinal="" indice="4" monitoria-status="0" alterado-status="0" valor-original="" v2condicional="IF;TRI_EAG_SN_REAL;=;1 - Sim" .="" v2nomecampo="TRI_EAG_RES" ,="" v2campo1="" v2campo2="" data-referencia-e="" data-referencia-valor="" texto1="Resultado" texto2="" condtext2="" class="form-control controle  v2HabilitaCondicional " name="2604289nomeCampo" id="form_547" disabled="">      <option value=""></option><option value="1 - Positivo">1 - Positivo</option><option value="2 - Negativo">2 - Negativo</option><option value="3 - Indeterminado">3 - Indeterminado</option><option value="8 - Não Disponível">8 - Não Disponível</option><option value="9 - Não Aplicável">9 - Não Aplicável</option></select><center><span class="legenda"></span></center>
																													<div id="divMsg_547" class="divMsg"></div>

																										</td>
																																								<!-- Coluna de botões -->
																								<td style="vertical-align:middle;align:right; text-align:right !important"><center>
																																									<div class="btn-group" style="align:right; text-align:right !important">
																										<!--Botão Monitora-->
																																													<button type="button" class="btn btn-default btn-sm" onclick="javascript:monitora(547);"><span id="divMonitora547" name="divMonitora547"><i class="fa fa-square-o fa-sm" title="Não monitorado"></i></span>																			</button>
																																												<!--Botão Histórico (Audit Trail)-->
																										<button type="button" class="btn btn-default btn-sm btnHistorico" data-toggle="modal" data-target="#modalHistoricoNovo" href="modal_ecrf_historico.php?id=547"><i class="fa fa-history fa-sm" title="Histórico [AuditTrail]"></i>																		</button>
																										<!--Botão Queries-->
																										<a name="anc_547"></a>

																										<button type="button" class="btn btn-default btn-sm  btnQuery" onclick="btnQueryClick('ecrf_formularios_query.php?id=547')" id="btnQuery_547" href="ecrf_formularios_query.php?id=547">
																										<i class="fa fa-question-circle-o fa-sm" title="Queries/Questionamentos"></i>																		</button>

																										<button type="button" class="btn btn-default btn-sm btnHistorico" data-toggle="modal" data-target="#modalComentarios" href="modal_ecrf_comentarios.php?id=547"><div id="divComentario_547"><i class="fa fa-commenting-o fa-sm " title="Comentários"></i></div>																		</button>
																																														<button type="button" class="btn btn-default btn-sm" onclick="javascript:monitoraDM(547);"><span id="divMonitoraDM547" name="divMonitoraDM547"><i class="fa fa-square-o fa-sm" title="Não monitorado DM"></i></span>																				</button>

																												<button type="button" class="btn btn-default btn-sm  btnQuery" onclick="btnQueryDMClick('ecrf_formularios_query_dm.php?id=547')" id="btnQueryDM_547" href="ecrf_formularios_query_dm.php?id=547">
																												<i class="fa fa-question-circle-o fa-sm" title="Queries/Questionamentos"></i>



																																												</button></div>
																								</center></td>
																																					</tr>
																																				<tr>
																									<td>
																									<table width="100%">
																										<tbody><tr>
																											<td class="tabela-body">
																												<div id="divTexto_TRI_EAG_OBS">Observações</div>
																											</td>
																										</tr>
																									</tbody></table>
																									</td>
																									<!-- Início do campo de 1 digitação -->
																																											<td style="vertical-align:middle" width="22%">
																										<textarea onkeyup="" onkeypress="" onblur="valida(this.value,this.min,this.max,'Observações',this);;" onchange="javascript:alteraMudanca();valida_data(this,'caixa');validaRef(this);validaTexto2();" oninput="" tipo-de-campo="caixa" data-valores-condicional="|" data-dupla_digitacao_campo="form_548_dupla_digitacao" data-dupla_digitacao_original="form_548" data-condicional-vermelho="" data-condicional-vermelho-alerta="" data-condicional-alerta="" data-qtd-decimal="" data-tipo-validar="" data-condicional="" data-condicional-valor="" data-condicional-id="544" data-dezoito-anos="" data-menor-termo="" data-igual-menor="" data-igual-maior="" data-termo="" data-data_termo="20250907" e_horario="" data-tipo="caixa" data-alerta-termo="" data-obrigatorio="" data-readonly="" data-senha="" data-calc-tipo="" data-calc-field1="" data-calc-field1_valor="" data-calc-field1-id="" data-calc-field2="" data-calc-field2_valor="" data-calc-field2-id="" data-campo="77" data-enfinal="" indice="5" monitoria-status="0" alterado-status="0" valor-original="" v2condicional="" .="" v2nomecampo="TRI_EAG_OBS" ,="" v2campo1="" v2campo2="" data-referencia-e="" data-referencia-valor="" texto1="Observações" texto2="" condtext2="" rows="6" style="width:100%" name="723022nomeCampo" class="form-control " id="form_548"></textarea><center><span class="legenda"></span></center>
																													<div id="divMsg_548" class="divMsg"></div>

																										</td>
																																								<!-- Coluna de botões -->
																								<td style="vertical-align:middle;align:right; text-align:right !important"><center>
																																									<div class="btn-group" style="align:right; text-align:right !important">
																										<!--Botão Monitora-->
																																													<button type="button" class="btn btn-default btn-sm" onclick="javascript:monitora(548);"><span id="divMonitora548" name="divMonitora548"><i class="fa fa-square-o fa-sm" title="Não monitorado"></i></span>																			</button>
																																												<!--Botão Histórico (Audit Trail)-->
																										<button type="button" class="btn btn-default btn-sm btnHistorico" data-toggle="modal" data-target="#modalHistoricoNovo" href="modal_ecrf_historico.php?id=548"><i class="fa fa-history fa-sm" title="Histórico [AuditTrail]"></i>																		</button>
																										<!--Botão Queries-->
																										<a name="anc_548"></a>

																										<button type="button" class="btn btn-default btn-sm  btnQuery" onclick="btnQueryClick('ecrf_formularios_query.php?id=548')" id="btnQuery_548" href="ecrf_formularios_query.php?id=548">
																										<i class="fa fa-question-circle-o fa-sm" title="Queries/Questionamentos"></i>																		</button>

																										<button type="button" class="btn btn-default btn-sm btnHistorico" data-toggle="modal" data-target="#modalComentarios" href="modal_ecrf_comentarios.php?id=548"><div id="divComentario_548"><i class="fa fa-commenting-o fa-sm " title="Comentários"></i></div>																		</button>
																																														<button type="button" class="btn btn-default btn-sm" onclick="javascript:monitoraDM(548);"><span id="divMonitoraDM548" name="divMonitoraDM548"><i class="fa fa-square-o fa-sm" title="Não monitorado DM"></i></span>																				</button>

																												<button type="button" class="btn btn-default btn-sm  btnQuery" onclick="btnQueryDMClick('ecrf_formularios_query_dm.php?id=548')" id="btnQueryDM_548" href="ecrf_formularios_query_dm.php?id=548">
																												<i class="fa fa-question-circle-o fa-sm" title="Queries/Questionamentos"></i>



																																												</button></div>
																								</center></td>
																																					</tr>
																														<tr><td colspan="4" class="tabela-head">
																		<div class="row">
																			<div class="col-md-4"></div>
																			<div class="col-md-3">
																				<center>
																																				<button style="padding: 3.0% 0;" type="button" class="btn btn-block btn-default " onclick="javascript:valida_dados('SALVA');">
																								<i class="fa fa-floppy-o"></i>&nbsp;&nbsp;&nbsp; Salvar														</button>
																						<br>
																				</center>
																			</div>
																			<!--
																			<div class="col-md-3">
																				<center>
																																		</center>
																			</div>
																			-->
																														<div class="col-md-3">
																															</div>
																		</div>
																	</td>
															</tr>
															<tr>
																									<td colspan="4" align="right">
																	<right>
																	<a href="ecrf_historico_queries.php?id=38" target="_blank" type="button" class="btn btn-default btn-sm btnHistorico"><i class="fa fa-question-circle fa-sm " title="Queries/Questionamentos">Histórico de Queries</i>										</a>
																	<button type="button" class="btn btn-default btn-sm btnHistorico" data-toggle="modal" data-target="#modalHistoricoNovo" href="modal_ecrf_historico_form.php?id=38"><i class="fa fa-history fa-sm" title="Histórico"> Histórico de Modificações</i>										</button>
																</right></td>
																							</tr>
															<!--<tr>
																<td colspan="4" align="right">
																	<right>
																	<button  type="button"
																				class="btn btn-default btn-sm btnHistorico"
																				data-toggle="modal"
																				data-target="#modalHistoricoNovo"
																				href="modal_ecrf_historico_form.php?id=38"
																			><i class="fa fa-history fa-sm" title="Histórico"> Histórico de Modificações</i>										</button>
																</td>
															</tr>-->
															</tbody>
															</table>
														</form>
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
	<?php
		include "rodape.php";
		include "global_js.php";
	?>
	</body>
	<script src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js" type="text/javascript"></script>
	<script src="php_js/js_ecrf.js" type="text/javascript"></script>
	<script>

	</script>
</html>