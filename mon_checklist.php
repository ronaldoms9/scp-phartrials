<?
	include_once "seguranca.php";
	include_once "class/projeto.class.php";
	include_once "class/checklist.class.php";

	$varProjeto 	= new Projeto($_SESSION['projeto']);
	$varCheckList 	= new CheckList($_SESSION['projeto']);
	$varConexao		= new Conexao();

?>
<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<meta http-equiv="content-type" content="application/vnd.ms-excel; charset=UTF-8">
<head>
	<?
		include "global_css.php";
	?>
    <link href="//cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.3.1/css/buttons.dataTables.min.css">
	<link href="https://fonts.googleapis.com/css?family=Abel|Archivo+Narrow|PT+Sans+Narrow:700|Pragati+Narrow" rel="stylesheet">
	<link href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/r/bs-3.3.5/jq-2.1.4,dt-1.10.8/datatables.min.css"/>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.4.2/css/buttons.dataTables.min.css"/>
	<style>
			.fase_diferente {
				color: red;
				background-color:rgb(189, 189, 189);
				width: 300px !important;
			}
            .icone:hover {
                width:25px;
            }
		    .lista_formularios{
				font-size: 10px !important;
				font-family: 'Abel', sans-serif;
			}
			.header_formularios{
				font-size: 12px !important;
				font-family: 'Abel', sans-serif;
				font-weight: bold;
			}
			input[type="text"]{
				font-size:10px;
			}
			tableFixHead { overflow: auto; height: 100px; }
			.tableFixHead thead th {
				position: sticky;
				top: 0;
				z-index: 100;
				background: #eee !important;
				box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);
			}
			.tableFixHead tbody input,
			.tableFixHead tbody select {
				position: relative;
				z-index: 1;
			}

			/* Just common table stuff. Really. */
			table  { border-collapse: collapse; width: 100%; }
			th, td { padding: 8px 16px; }
			th     { background:#eee; }

			/* Congelar as cinco primeiras colunas da tabela */
			.tableFixHead th,
			.tableFixHead td {
				white-space: nowrap;
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
                                        <h1><?=traduzir("CHECK-LIST DOS PARTICIPANTES", $_SESSION['lang']);?> </h1>
                                    </div>
                                </div>
                            </div>
                            <div class="page-content">
                                <div class="container-fluid">
									<div class="page-content-inner">
										<div class="row widget-row">
											<div class="col-md-12">
                                                <!-- BEGIN Portlet PORTLET-->
                                                <div class="portlet light">
                                                    <div class="portlet-title">
                                                        <div class="caption">
                                                            <i class="icon-speech"></i>
															<iframe id="my_iframe" style="display:none;"></iframe>
                                                            <span class="caption-subject bold uppercase"> CheckList de Participantes</span>
                                                        </div>
                                                        <div class="actions">
															<a href="javascript:inclui();" class="btn btn-default">
                                                                <i class="fa fa-plus"></i> Incluir um participante</a>
															<!--<a href="javascript:incluiParticipante();"
																data-toggle="tooltip"
																class="btn btn-xs btn-outline black btn-sm yellow"
																title="Incluir Linhas/Participantes"> <i class="fa fa-plus"></i> Incluir Grupo</a>-->
                                                        </div>
                                                    </div>
                                                    <div class="portlet-body">



															<div class="row">
																<div class="col-md-10 column">
																	<div class="row">
																			<div class="col-md-2 column">
																				<div class="search-filter ">
																					<div class="search-label uppercase"><span class="caption-subject font-green-sharp sbold">Fase</span>
																					</div>
																					<div class="input-icon right">
																						<select name="form_fase" id="form_fase" class="form-control">
																							<option value="TODOS">Todos</option>
																							<?php
																								if($varCheckList->temEspecifico()==1){
																									$query = "select id, item from agenda_check_fase where projeto = " . $_SESSION['projeto'] . " order by id";

																								}else{
																									$query = "select id, item from agenda_check_fase where projeto = 0 order by id";
																								}
																								$resCheck = $varConexao->pesquisa($query);
																								while($rowCheck = mysqli_fetch_assoc($resCheck)){
																									echo '<option value="'.$rowCheck['id'].'">'.$rowCheck['item'].'</option>';
																								}
																							?>
																						</select>
																					</div>
																				</div>
																			</div>
																			<div class="col-md-1 column">
																				<div class="search-filter ">
																					<div class="search-label uppercase"><span class="caption-subject font-green-sharp sbold">Grupo</span>
																					</div>
																					<div class="input-icon right">
																							<input
																								type="text"
																								style="text-align:center"
																								name="form_periodo<?=rand(324,324566);?>"
																								id="form_grupo"
																								class="form-control"
																								value="TODOS">
																							<div class="input-group-append">
																								<span class="input-group-text" id="basic-addon2"><button class="btn btn-sm" onclick="javascript:btnTodos();">TODOS</button></span>
																							</div>
																					</div>
																				</div>
																			</div>
																			<div class="col-md-1 column">
																				<div class="search-filter ">
																					<?
																					switch($_SESSION['projeto']){
																						case 256:
																							?>
																							<div class="search-label uppercase"><span class="caption-subject font-green-sharp sbold">Protocolo</span>
																							</div>
																							<div class="input-icon right">
																								<select name="form_periodo" id="form_periodo" class="form-control">
																									<option value="TODOS">Todos</option>
																									<option value="5">5</option>
																									<option value="6">6</option>
																								</select>
																							</div>
																							<div class="search-label uppercase"><span class="caption-subject font-green-sharp sbold">Centro</span>
																							</div>
																							<div class="input-icon right">
																								<select name="form_centro" id="form_centro" class="form-control">
																									<option value="TODOS">Todos</option>
																									<?
																									$queryCentro 	= "SELECT * FROM cfg_centro where projeto = " . $_SESSION['projeto'];
																									echo $queryCentro;
																									$resCentro		= $varConexao->pesquisa($queryCentro);
																									while($rowCentro = mysqli_fetch_assoc($resCentro)){
																										echo '<option value="'.$rowCentro['id'].'">'.$rowCentro['apelido'].'</option>';
																									}
																									?>
																								</select>
																							</div>
																							<?php
																						break;
																						default:
																							?>
																							<div class="search-label uppercase"><span class="caption-subject font-green-sharp sbold">Período</span>
																							</div>
																							<div class="input-icon right">
																								<select name="form_periodo" id="form_periodo" class="form-control">
																									<option value="TODOS">Todos</option>
																									<option value="1">1</option>
																									<option value="2">2</option>
																									<option value="3">3</option>
																									<option value="4">4</option>
																									<option value="5">5</option>
																									<option value="6">6</option>
																									<option value="7">7</option>
																								</select>
																							</div>
																							<?php
																						break;
																					}
																					?>

																				</div>
																			</div>
																			<div class="col-md-2 column">
																				<div class="search-filter ">
																					<div class="search-label uppercase"><span class="caption-subject font-green-sharp sbold">Sexo</span>
																					</div>
																					<div class="input-icon right">
																						<select name="form_sexo" id="form_sexo" class="form-control">
																							<option value="" selected>Todos</option>
																							<option value="Masc">Masculino</option>
																							<option value="Femin">Feminino</option>
																						</select>
																					</div>
																				</div>
																			</div>
																			<div class="col-md-2 column">
																				<div class="search-filter ">
																					<div class="search-label uppercase"><span class="caption-subject font-green-sharp sbold">Status</span>
																					</div>
																					<div class="input-icon right">
																						<select name="form_status" id="form_status" class="form-control">
																							<option value="TODOS" selected>Todos</option>
																							<option value="T">Titular</option>
																							<option value="R">Reserva</option>
																							<option value="E">Excluído</option>
																							<option value="D">Desistente</option>
																							<option value="F">Falha de seleção</option>
																						</select>
																					</div>
																				</div>
																			</div>
																			<div class="col-md-2 column">
																				<div class="search-filter ">
																					<div class="search-label uppercase"><span class="caption-subject font-green-sharp sbold"># Participante</span>
																					</div>
																					<div class="input-icon right">
																						<select name="form_participante" id="form_participante" class="form-control">
																							<option value="">Todos</option>
																							<?php
																								$queryParticipante = "SELECT distinct numero FROM agenda_check_participante WHERE projeto = " . $_SESSION['projeto'];
																								$resParticipante = $varConexao->pesquisa($queryParticipante);
																								while($rowParticipante = mysqli_fetch_assoc($resParticipante)){
																									echo '<option value="'.$rowParticipante['numero'].'">'.$rowParticipante['numero'].'</option>';
																								}
																							?>
																						</select>
																					</div>
																				</div>
																			</div>
																			<div class="col-md-2 column">
																				<div class="search-filter ">
																					<div class="search-label uppercase"><span class="caption-subject font-green-sharp sbold">Cód Participante</span>
																					</div>
																					<div class="input-icon right">
																						<input
																							type="text"
																							name="form_codigo"
																							id="form_codigo"
																							class="form-control"
																							style="text-align:center">
																					</div>
																				</div>
																			</div>
																	</div>
																</div>
																<div class="col-md-2 column">
																	<br><button class="btn green bold uppercase btn-block" onclick="pesquisa();" name="btnFiltrar" id="btnFiltrar"><i class="fa fa-search"></i>&nbsp;&nbsp;&nbsp; Filtrar</button>
																</div>
															</div>
															<hr>
															<div class="row">
																<div class="col-md-8 column">
																	<div class="search-filter ">
																		<div class="search-label uppercase"><span class="caption-subject font-green-sharp sbold">FOLLOW-UP DAS INTERNAÇÕES</span>
																		</div>
																		<div class="input-icon right">
																			<textarea  rows="4" id="txtFollowUp" value="" class="form-control"><?=$varProjeto->txtfollowup;?></textarea>
																		</div>
																	</div>
																</div>
																<div class="col-md-1 column">
																	<br><button class="btn green bold uppercase btn-block" onclick="salvaFUp();" name="btnFiltrar" id="btnFiltrar"><i class="fa fa-save"></i>&nbsp; Salvar</button>
																</div>
															</div>
															<div class="portlet-body">
																<div>
																	<div class="tableFixHead">
																		<table class="table table-hover table-striped table-bordered" name="tabela" id="tabela" >
																			<thead>
																				<tr>
																					<th style="font-size: 12px !important;"><center>#<br>Participante</center></th>
																					<th style="font-size: 12px !important;"><center>Código</center></th>
																					<th style="font-size: 12px !important;"><center>Status</center></th>
																					<th style="font-size: 12px !important;"><center>Grupo</center></th>
																					<th style="font-size: 12px !important;"><center>Período</center></th>
																				</tr>
																			</thead>
																			<tbody id="tabela_retorno" name="tabela_retorno">
																				<tr>
																					<td colspan="5">Nenhum dado a exibir...</td>
																				</tr>
																			</tbody>
																		</table>
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
				</div>
			</div>
		</div>
	<?
		include "rodape.php";
		include "global_js.php";
	?>
	</body>
	<div id="modalInclui" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h4 class="modal-title">Incluir Participante/Linha</h4>
				</div>
				<div class="modal-body modal-Historico">
					<div class="row">
						<div class="col-md-1 column"></div>
						<div class="col-md-10 column">

							<div class="form-group">
								<label for="text">Período</label>
								<input id="form_gp_periodo" name="form<?=rand(132123,3423234);?>" type="text" class="form-control" <?=$centralizado;?>>

							</div>
							<div class="form-group">
								<label for="text1">Grupo</label>
								<input id="form_gp_grupo" name="form<?=rand(132123,3423234);?>" type="text" class="form-control" <?=$centralizado;?>>
							</div>
							<div class="form-group">
								<label for="text2">Participante inicial</label>
								<input id="form_gp_inicio" name="form<?=rand(132123,3423234);?>" type="text" class="form-control" <?=$centralizado;?>>
							</div>
							<div class="form-group">
								<label for="text3">Participante Final</label>
								<input id="form_gp_fim" name="form<?=rand(132123,3423234);?>" type="text" class="form-control" <?=$centralizado;?>>
							</div>
							<div class="form-group">
								<button onclick="javascript:insereGrupo();" type="submit" class="btn btn-primary">Inserir</button>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" data-dismiss="modal" class="btn dark btn-outline">Cancelar</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="modalInfo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="overflow-y:auto;">
		  <div class="modal-dialog modal-lg" style="overflow-y:auto;">
			<div class="modal-content" style="overflow-y:auto;">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel">Informações</h4>
			  </div>
			  <div class="modal-body" style="overflow-y:auto;">
				  <a class="custom-close"> Fechar </a>
			  </div>
			  <div class="modal-footer">
              <button type="button" class="btn btn-primary" id="modal_form_salvar" onclick="salvarRegistro()">Salvar</button>
				<button type="button" class="btn btn-default" data-dismiss="modal" id="modal_form_fechar">Fechar</button>
			  </div>
			</div>
		  </div>
	</div>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
	<script type="text/javascript" src="php_js/js_mon_checklist.js"></script>
	<script>
		function btnTodos(){
			document.getElementById("form_grupo").value = "TODOS";
		}
		function incluiParticipante(){
		//$('.modal-Inclui').load('modal_check_list_inclui.php',function(){
			$('#modalInclui').modal({show:true});
		//});
		}

		function insereGrupo(){
			varUrl = "php_php/php_mon_checklist.php?act=INSERE_GRUPO";
			if(document.getElementById("form_gp_periodo").value==""){alert("Ao menos um campo obrigatório não foi preenchido. Por favor, revise o formulário.");return;}
			if(document.getElementById("form_gp_grupo").value==""){alert("Ao menos um campo obrigatório não foi preenchido. Por favor, revise o formulário.");return;}
			if(document.getElementById("form_gp_inicio").value==""){alert("Ao menos um campo obrigatório não foi preenchido. Por favor, revise o formulário.");return;}
			if(document.getElementById("form_gp_fim").value==""){alert("Ao menos um campo obrigatório não foi preenchido. Por favor, revise o formulário.");return;}

			var formData = {varPeriodo: document.getElementById("form_gp_periodo").value,
							varGrupo: 	document.getElementById("form_gp_grupo").value,
							varIni:		document.getElementById("form_gp_inicio").value,
							varFim:		document.getElementById("form_gp_fim").value
							}
			$.ajax({
				url: varUrl,
				type: "POST",
				data : formData,
				async : true,
				beforeSend: function(){
					$('#modalInclui').modal('hide');
					mostraCarregando();
					document.getElementById("form_gp_periodo").value = "";
					document.getElementById("form_gp_grupo").value   = "";
					document.getElementById("form_gp_inicio").value  = "";
					document.getElementById("form_gp_fim").value     = "";
				},
				success: function(response, textStatus, jqXHR) {
					console.log(response);
					removeCarregando();
					pesquisa();
				},
				error: function (jqXHR, textStatus, errorThrown) {
					exibe_mensagem("ERRO!", "Erro ao inserir grupo!", "error");
					$.unblockUI();
				}
			});
		}
		function pesquisa(){
			mostraCarregando();
			document.getElementById("tabela").innerHTML = "<img src='imagens/carregando.gif' width='85'>Carregando...";
			varUrl = baseUrl + "php_mon_checklist.php?act=PESQUISA";
			var formData = {fase		: document.getElementById("form_fase").value,
							grupo       : document.getElementById("form_grupo").value,
							sexo        : document.getElementById("form_sexo").value,
							status      : document.getElementById("form_status").value,
							periodo     : document.getElementById("form_periodo").value,
							participante: document.getElementById("form_participante").value,
							codigo      : document.getElementById("form_codigo").value}
			$.ajax({
				url: varUrl,
				type: "POST",
				data : formData,
				async : true,
				success: function(response, textStatus, jqXHR) {
					removeCarregando();
					document.getElementById("tabela").innerHTML = response;
					fixaColunasTabela();
				},
				error: function (jqXHR, textStatus, errorThrown) {
					removeCarregando();
				}
			});
		}
		function exibeInfo($id){
			$('#modalInfo').modal('show').find('.modal-body').load('modal_checklist_info.php?id=' + $id);
		}
		function salvarRegistro(){
			varUrl = "php_php/php_mon_checklist.php?act=SALVA_INFO";
			var formData = {id: document.getElementById("modal_form_id").value,
							observacoes: document.getElementById("modal_form_observacoes").value}
			$.ajax({
				url: varUrl,
				type: "POST",
				data : formData,
				async : true,
				success: function(response, textStatus, jqXHR) {
					console.log("484:-:"+response);
					removeCarregando();
					$('#modalInfo').modal('hide');
					pesquisa();
				},
				error: function (jqXHR, textStatus, errorThrown) {
					removeCarregando();
					$('#modalInfo').modal('hide');
				}
			});
		}
		function naoAplicavel(linha){
			if(confirm("Deseja realmente marcar como não aplicável os campos em branco?")){
				var campos = document.getElementsByClassName("linha_" + linha);
				for(i=0;i<campos.length;i++){
					if(campos[i].value==""){
						campos[i].value = "N/A";
					}
				}
			}else{
				alert("Operação cancelada");
			}
		}
		function calculaCampos(campo){
			var nomeCampo = campo.name;
			var linhaId = nomeCampo.split("_")[1];

			//Pegar todos os campos da linha
			var campos = document.getElementsByClassName("linha_" + linhaId);

			for(i=0;i<campos.length;i++){
				if(campos[i].getAttribute("varTemCalculo")==1){
					//Estou dentro do campo que tem calculo. Agora vou ver o tipo de calculo
					var tipoCalculo = campos[i].getAttribute("varTipoCalculo");
					var camposCalculo = campos[i].getAttribute("varCamposCalculo");
					var camposCalculo = camposCalculo.split(",");

					if(tipoCalculo.toLowerCase()=="idade"){
						//Vou pegar a idade do paciente
						//No caso de idade, só preciso pegar o valor do campo CamposCalculo e calcular a idade com a data atual
						nomeCampo1 = camposCalculo[0];
						nomeCompletoCampo1 = "linha_" + linhaId + "_campo_" + nomeCampo1;
						varAssinatura = document.getElementsByClassName(nomeCompletoCampo1);
						varAssinatura = varAssinatura[0].value;
						if(varAssinatura==""){continue;}

						nomeCampo2 = camposCalculo[1];
						nomeCompletoCampo2 = "linha_" + linhaId + "_campo_" + nomeCampo2;
						varNascimento = document.getElementsByClassName(nomeCompletoCampo2);
						varNascimento = varNascimento[0].value;
						if(varNascimento==""){continue;}

						varAssinatura = varAssinatura.split("/");
						varAssinatura = varAssinatura[2] + "-" + varAssinatura[1] + "-" + varAssinatura[0];
						varNascimento = varNascimento.split("/");
						varNascimento = varNascimento[2] + "-" + varNascimento[1] + "-" + varNascimento[0];
						var dataAtual = new Date();
						var dataAssinatura = new Date(varAssinatura);
						var dataNascimento = new Date(varNascimento);
						var idade = dataAssinatura.getFullYear() - dataNascimento.getFullYear();
						if(dataAssinatura.getMonth() < dataNascimento.getMonth() || (dataAssinatura.getMonth() == dataNascimento.getMonth() && dataAssinatura.getDate() < dataNascimento.getDate())){
							idade--;
						}
						campos[i].value = idade;


						if(campos[i].getAttribute("varEIdade")=="1"){

							corFundo = "";
							if(idade < campos[i].getAttribute("varIdadeMinima")){
								corFundo = "red";
							}
							if(idade > campos[i].getAttribute("varIdadeMaxima")){
								corFundo = "red";
							}
							campos[i].style.backgroundColor = corFundo;
						}
					}
					if(tipoCalculo.toLowerCase()=="imc"){
						//Vou pegar o peso e a altura do paciente
						//No caso de imc, só preciso pegar o valor do campo CamposCalculo e calcular o imc

						console.log("tipoCalculo: " + tipoCalculo);

						nomeCampo1 = camposCalculo[0];
						nomeCompletoCampo1 = "linha_" + linhaId + "_campo_" + nomeCampo1;
						varAltura = document.getElementsByClassName(nomeCompletoCampo1);
						varAltura = varAltura[0].value;
						if(varAltura==""){continue;}

						nomeCampo2 = camposCalculo[1];
						nomeCompletoCampo2 = "linha_" + linhaId + "_campo_" + nomeCampo2;
						varPeso = document.getElementsByClassName(nomeCompletoCampo2);
						varPeso = varPeso[0].value;
						if(varPeso==""){continue;}

						console.log("varAltura: " + varAltura);
						console.log("varPeso: " + varPeso);

						varAltura = varAltura.replace(",", ".");
						varPeso = varPeso.replace(",", ".");
						varImc = varPeso / (varAltura * varAltura);
						console.log("varImc: " + varImc);
						varImc = varImc.toFixed(2);
						campos[i].value = varImc;


						corFundo = "";
						if(varImc < campos[i].getAttribute("varminimo")){
							corFundo = "red";
						}
						if(varImc > campos[i].getAttribute("varmaximo")){
							corFundo = "red";
						}
						campos[i].style.backgroundColor = corFundo;



					}

				}
			}
			return;
		}
		function salvaPartDire(varLinha){
			var SQL = "";
			var campos = document.getElementsByClassName("linha_" + varLinha);
			console.log("599->: " + varLinha);
			for(i=0;i<campos.length;i++){
				nome = campos[i].name;
				if(nome!=undefined){
					if(varLinha<10){
						if(nome.substring(0, 7) == "linha_"+varLinha){
							antigo = campos[i].getAttribute("antigo")
							SQL = SQL + "&"+campos[i].name+"="+campos[i].value+"|"+antigo;
						}
					}else{
						if(varLinha<=99){
							if(nome.substring(0, 8) == "linha_"+varLinha){
								antigo = campos[i].getAttribute("antigo")
								SQL = SQL + "&"+campos[i].name+"="+campos[i].value+"|"+antigo;
							}
						}else{
							if(nome.substring(0, 9) == "linha_"+varLinha){
								antigo = campos[i].getAttribute("antigo")
								SQL = SQL + "&"+campos[i].name+"="+campos[i].value+"|"+antigo;
							}
						}
					}
				}
			}
			console.log("SQL: " + SQL);
			//var formData = { dados: item }
			var formData = {texto : SQL,
							id    : document.getElementById("linha_"+varLinha+"_id").value}
			varUrl = baseUrl + "php_mon_checklist.php?act=SALVA";
			$.ajax({
				url: varUrl,
				type: "POST",
				data : formData,
				async : false,
				success: function(response, textStatus, jqXHR) {
					//pesquisa();
					console.log("634->: " + response);
				},
				error: function (jqXHR, textStatus, errorThrown) {
					exibe_mensagem("ERRO!", "Erro ao deletar o registro!", "error");
					pesquisa();
				}
			});
		}

		function fixaColunasTabela(){
			var tabela = document.getElementById("tabela");
			if(!tabela){ return; }

			var numColunas = 5;
			var offsetLeft = 0;

			for(var col = 1; col <= numColunas; col++){
				var celulas = tabela.querySelectorAll("th:nth-child(" + col + "), td:nth-child(" + col + ")");
				if(celulas.length === 0){
					return;
				}

				var largura = celulas[0].offsetWidth;
				var zIndex = 100 + (numColunas - col + 1);

				for(var i = 0; i < celulas.length; i++){
					celulas[i].style.position   = "sticky";
					celulas[i].style.left       = offsetLeft + "px";
					celulas[i].style.zIndex     = String(zIndex);
					celulas[i].style.background = "#fff";
				}

				offsetLeft += largura;
			}
		}

		document.addEventListener("DOMContentLoaded", function(){
			fixaColunasTabela();
		});
	</script>
</html>