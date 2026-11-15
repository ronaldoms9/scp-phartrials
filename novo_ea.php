<?
  include_once "seguranca.php";

  $paciente    = $_GET[base64_encode('pac')];
  $formularioP = $_GET[base64_encode('cam')];
  $xxpt        = $_GET['xxpt'];
  $opc         = $_GET['opc'];
  $modal       = (isset($_GET['modal']) && $_GET['modal'] == '1');
  $url_orig    = "novo_ea.php";
  $tabela      = "adv_evento";
  $pagina      = "novo_ea.php";


  $varEstado = $opc;
  if($varEstado==""){
	$varEstado = "I";
  }

	$msg = "";
	if($opc=='X'){
	  $query = "delete from $tabela where id = " . $_GET['id'];
	  $conexao->pesquisa($query);
	  $msg="<script>alert('Registro deletado com sucesso!');</script>";
	}
?>
<!DOCTYPE html>
<html lang="en">
	<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
	<link href="assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<head>
			<?
				include "global_css.php";
			?>
		<style>
			   .menu {
				background:#DCDCDC;
				margin: auto;
				padding:10px;
				text-align:center;
				-moz-border-radius:7px;
				-webkit-border-radius:7px;
				 border-radius:7px;
				}
		</style>
		<?=$msg;?>
		<script>
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
				function valida(continuar){
					var processa = true;
					var nome = document.getElementById('form_nome').value;
					/*if(nome==""){processa = false; alert("É obrigatório o preenchimento do campo 'Nome do evento adverso'");}else{
					   var grau = document.getElementById('form_grau').value;
					   if(grau==""){processa = false; alert("É obrigatório o preenchimento do campo 'Grau'");}else{
							var relacao = document.getElementById('form_relacao').value;
							if(relacao==""){processa = false; alert("É obrigatório o preenchimento do campo 'Relação com a medicação'");}else{
							   var acao = document.getElementById('form_acao_tomada').value;
							   if(acao == ""){processa = false; alert("É obrigatório o preenchimento do campo 'Ação tomada'");}else{
								   //if(acao=="3 - Administração de medicação concomitante"){alert("Atenção. Não se esqueça de preencher o formulário de medicação concomitante");}
								   if(acao=="5 - Hospitalização / prolongamento da hospitalização"){alert("Atenção. Para esse caso, é necessário o preenchimento do formulário de Evento Adverso Grave");}
								   var valor = document.getElementById('form_acao_tomada').value;
								   if( (valor=="2 - Dosagem da medicação do estudo ajustada/interrupção temporária da medicação estudo")|| (valor=="4 - Terapia sem uso de medicação") ){
								   }
								   var desfecho = document.getElementById('form_desfecho').value;
								   if(desfecho==""){processa=false;alert("É obrigatório o preenchimento do campo 'Desfecho'");}else{
									  if(desfecho=="4 - Causou ameaça a vida"){alert("Atenção. Para esse caso, é necessário o preenchimento do formulário de Evento Adverso Grave");}
								   }
							   }
							}
					   }
					}*/
					if(processa==true){
						 salvarEvento(continuar);
					}
				}
				function excluir(ID,NOME){
						  var resp = confirm("Tem certeza que deseja excluir o registro do medicamento '"+NOME+"'?");
						  if(resp==true){
							   window.location = "<?=$url_orig."?opc=X&id=";?>"+ID;
						  }
				 }
				function mudou(entrada){
					 /*valor = entrada.value;
					 if(valor!="1 - Em curso"){document.getElementById('form_data_termino').disabled = false;}else{document.getElementById('form_data_termino').disabled = true;document.getElementById('form_data_termino').value="";}
					 */
					if(entrada.value=="6 - Outros"){
						document.getElementById('div_desfecho_outros').style.display = "block";
					}else{
						document.getElementById('div_desfecho_outros').style.display = "none";
					}
				}
				function mudou2(){
					 //var campo = document.getElementById('form_acao_descricao');
					 var valor = document.getElementById('form_acao_tomada').value;
					 var divDescricao = document.getElementById('div_descricao');
					 //divDescricao.style.display = "none";
					 var divLabel = document.getElementById('div_label');
					 //divLabel.style.display = "none";
					 //campo.disabled = true;
					 //var label_html = document.getElementById('label_html').innerHTML;
					 if(valor=="2 - Dosagem da medicação do estudo ajustada/interrupção temporária da medicação estudo"){
						  //label_html = "Dose ajustada";
						  //campo.disabled = false;
					 }
					 if(valor=="4 - Terapia sem uso de medicação"){
						  //label_html = "Descreva a terapia";
						  //campo.disabled = false;
					 }
				}
		</script>
	</head>
	<body<?=$modal ? ' style="background:#fff;"' : '';?>>
		   <? if(!$modal){ ?>
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
											<h1><?=traduzir("Cadastro de Eventos Adversos", $_SESSION['lang']);?> </h1>
										</div>
									</div>
								</div>
								<div class="page-content">
									<div class="container-fluid">
										<ul class="page-breadcrumb breadcrumb">
											<li>
												<a href="inicial.php"><?=traduzir("Início", $_SESSION['lang']);?></a>
												<i class="fa fa-circle"></i>
											</li>
											<li>
												<span><?=traduzir("Cadastro de Eventos adversos", $_SESSION['lang']);?></span>
											</li>
										</ul>
									</div>
		   <? } else { ?>
		   <div class="container-fluid" style="padding:15px;">
		   <? } ?>
									<div class="row">
										<? if(!$modal){ ?><div class="col-md-1"></div><? } ?>
										<div class="<?=$modal ? 'col-md-12' : 'col-md-10';?>">
											  <?
												 if(($_GET['id']>0)&&($opc=="E")){
													$query = "select * from $tabela where id = " . $_GET['id'];
													$res_med = $conexao->pesquisa($query);
													$row_med = mysqli_fetch_assoc($res_med);
												 }
												 ?>
												 <br>
												<div class="portlet box grey-mint ">
													<div class="portlet-title">
														<div class="caption">
															<i class="fa fa-gift"></i>Evento adverso </div>
													</div>
													<div class="portlet-body form">
														<form class="form-horizontal form-bordered form-row-stripped" role="form" id="form_info" name="form_info" onsubmit="return false;">
														<div class="form-body">

															<div class="form-group">
																<label class="control-label col-md-3"># Número</label>
																<div class="col-md-5">
																	<input type="text"
																			class="form-control"
																			id="form_nro_evento"
																			readonly
																			name="form_nro_evento" <?=$centralizado;?>
																			value="<?=$row_med['nro_evento'];?>">
																</div>
															</div>

															<div class="form-group">
																<label class="control-label col-md-3">Centro de pesquisa</label>
																<div class="col-md-9">
																	<select class="form-control" name="form_centro" id="form_centro">
																	  <option value=""></option>
																	  <?
																		$query = "select * from cfg_centro where projeto = " . $_SESSION['projeto'];
																		$resCentro = $conexao->pesquisa($query);
																		while($rowCentro = mysqli_fetch_assoc($resCentro)){
																			echo '<option value="'.$rowCentro['id'].'" ';
																			if($row_med['centro']==$rowCentro['id']){echo " selected ";}
																			echo '>'.$rowCentro['nome'].'</option>';
																		}
																	  ?>
																	</select>
																</div>
															</div>
															<div class="form-group">
																<label class="control-label col-md-3">Nro do Participante</label>
																<div class="col-md-5">
																	<input type="text"
																			class="form-control"
																			id="form_nro_paciente"
																			name="form_nro_paciente" <?=$centralizado;?>
																			value="<?=$row_med['nro_paciente'];?>">
																</div>
															</div>
															<div class="form-group">
																<label class="control-label col-md-3">Código do Participante</label>
																<div class="col-md-5">
																	<input type="text"
																			class="form-control"
																			id="form_codigo_paciente"
																			name="form_codigo_paciente" <?=$centralizado;?>
																			value="<?=$row_med['codigo_paciente'];?>">
																</div>
															</div>
															<div class="form-group">
																<label class="control-label col-md-3">Grupo</label>
																<div class="col-md-5">
																	<input type="text"
																			class="form-control"
																			id="form_grupo"
																			name="form_grupo" <?=$centralizado;?>
																			value="<?=$row_med['grupo'];?>">
																</div>
															</div>
															<div class="form-group">
																<label class="control-label col-md-3">Período</label>
																<div class="col-md-5">
																	<select class="form-control" name="form_periodo" id="form_periodo">
																		<option value=""></option>
																		<option value="1" <?if($row_med['periodo']=="1"){echo " selected ";}?>>1</option>
																		<option value="2" <?if($row_med['periodo']=="2"){echo " selected ";}?>>2</option>
																		<option value="3" <?if($row_med['periodo']=="3"){echo " selected ";}?>>3</option>
																		<option value="4" <?if($row_med['periodo']=="4"){echo " selected ";}?>>4</option>
																		<option value="5" <?if($row_med['periodo']=="5"){echo " selected ";}?>>5</option>
																		<option value="6" <?if($row_med['periodo']=="6"){echo " selected ";}?>>6</option>
																		<option value="Pós Estudo" <?if($row_med['periodo']=="Pós Estudo"){echo " selected ";}?>>Pós Estudo</option>
																		</select>
																</div>
															</div>
															<div class="form-group">
																<label class="control-label col-md-3"># Número do EA</label>
																<div class="col-md-5">
																	<input type="text"
																			class="form-control"
																			id="form_nro_evento_participante"
																			name="form_nro_evento_participante" <?=$centralizado;?>
																			value="<?=$row_med['nro_evento_participante'];?>">
																</div>
															</div>
															<div class="form-group">
																<label class="control-label col-md-3">Nome/Descrição do evento</label>
																<div class="col-md-9">
																	<input
																		type="text"
																		class="form-control"
																		id="form_nome"
																		name="form_nome"
																		value="<?=$row_med['nome'];?>">
																</div>
															</div>
															<div class="form-group">
																<label class="control-label col-md-3">Data da abertura do EA</label>
																<div class="col-md-5">
																	<input
																		type="text" <?=$centralizado;?>
																		class="form-control"
																		id="form_data_abertura"
																		name="form_data_abertura"
																		onkeyup="mascara(this,'##/##/####');"
																		value="<?=$row_med['data_abertura'];?>">
																</div>
															</div>
															<div class="form-group">
																<label class="control-label col-md-3">Hora de abertura</label>
																<div class="col-md-5">
																	<input
																		type="text" <?=$centralizado;?>
																		class="form-control"
																		id="form_hora_abertura"
																		name="form_hora_abertura"
																		value="<?=$row_med['hora_abertura'];?>"
																		onkeyup="mascara(this,'##:##');">
																</div>
															</div>


															<div class="form-group">
																<label class="control-label col-md-3">Data de início</label>
																<div class="col-md-5">
																	<input
																		<?=$centralizado;?>
																		type="text"
																		class="form-control"
																		id="form_data_inicio"
																		name="form_data_inicio"
																		onkeyup="mascara(this,'##/##/####');"
																		value="<?=$row_med['data_inicio'];?>">
																</div>
															</div>
															<div class="form-group">
																<label class="control-label col-md-3">Hora de início</label>
																<div class="col-md-5">
																	<input
																		<?=$centralizado;?>
																		type="text"
																		class="form-control"
																		id="form_hora_inicio"
																		name="form_hora_inicio"
																		onkeyup="mascara(this,'##:##');"
																		value="<?=$row_med['hora_inicio'];?>">
																</div>
															</div>
															<div class="form-group">
																<label class="control-label col-md-3">Data de término</label>
																<div class="col-md-5">
																	<input
																		<?=$centralizado;?>
																		type="text"
																		class="form-control"
																		onkeyup="mascara(this,'##/##/####');"
																		id="form_data_termino"
																		<?if($row_med['desfecho']=="1 - Em curso"){echo " disabled ";}?>
																		name="form_data_termino"
																		value="<?=$row_med['data_termino'];?>">
																</div>
															</div>
															<div class="form-group">
																<label class="control-label col-md-3">Hora de término</label>
																<div class="col-md-5">
																	<input
																		<?=$centralizado;?>
																		type="text"
																		class="form-control"
																		id="form_hora_fim"
																		name="form_hora_fim"
																		onkeyup="mascara(this,'##:##');"
																		value="<?=$row_med['hora_fim'];?>">
																</div>
															</div>
															<div class="form-group last">
																<label class="control-label col-md-3">O EA foi classificado como Grave?</label>
																<div class="col-md-5">
																	<select
																		class="form-control"
																		name="form_classificado_grave"
																		id="form_classificado_grave"
																		onchange="mudou(this);">
																			<option value=""></option>
																			<option value="1 - Sim" <?if($row_med['classificado_grave']=="1 - Sim"){echo " selected ";}?> >1 - Sim</option>
																			<option value="2 - Não" <?if($row_med['classificado_grave']=="2 - Não"){echo " selected ";}?> >2 - Não</option>
																	</select>
																</div>
															</div>
															<div class="form-group">
																<label class="control-label col-md-3">Evento relatado ao patrocinador?</label>
																<div class="col-md-5">
																	<select class="form-control" name="form_relatado_patrocinador" id="form_relatado_patrocinador">
																		<option value=""></option>
																		<option value="Sim" <?if($row_med['relatado_patrocinador']=="Sim"){echo " selected ";}?>>Sim</option>
																		<option value="Não" <?if($row_med['relatado_patrocinador']=="Não"){echo " selected ";}?>>Não</option>
																		<option value="NA" <?if($row_med['relatado_patrocinador']=="NA"){echo " selected ";}?>>Não aplicável</option>
																	</select>
																</div>
															</div>
															<div class="form-group">
																<label class="control-label col-md-3">Evento relatado ao CEP?</label>
																<div class="col-md-5">
																	<select class="form-control"
																			name="form_relatado_cep"
																			id="form_relatado_cep">
																		<option value=""></option>
																		<option value="Sim" <?if($row_med['relatado_cep']=="Sim"){echo " selected ";}?>>Sim</option>
																		<option value="Não" <?if($row_med['relatado_cep']=="Não"){echo " selected ";}?>>Não</option>
																		<option value="NA" <?if($row_med['relatado_cep']=="NA"){echo " selected ";}?>>Não aplicável</option>
																	</select>
																</div>
															</div>
															<div class="form-group">
																<label class="control-label col-md-3">Previsibilidade</label>
																<div class="col-md-5">
																	<select class="form-control"
																			name="form_previsibilidade"
																			id="form_previsibilidade">
																		<option value=""></option>
																		<option value="Esperado" <?if($row_med['previsibilidade']=="Esperado"){echo " selected ";}?>>Esperado</option>
																		<option value="Inesperado" <?if($row_med['previsibilidade']=="Inesperado"){echo " selected ";}?>>Inesperado</option>
																	</select>
																</div>
															</div>
															<div class="form-group">
																<label class="control-label col-md-3">Intensidade</label>
																<div class="col-md-5">
																	<select class="form-control" name="form_grau" id="form_grau">
																		<option value=""></option>
																		<option value="0 - N/A" <?if($row_med['grau']=="0 - N/A"){echo " selected ";}?>>Não especificado</option>
																		<option value="1 - Leve" <?if($row_med['grau']=="1 - Leve"){echo " selected ";}?>>1 - Leve</option>
																		<option value="2 - Moderado" <?if($row_med['grau']=="2 - Moderado"){echo " selected ";}?>>2 - Moderado</option>
																		<option value="3 - Intenso"  <?if($row_med['grau']=="3 - Intenso"){echo " selected ";}?>>3 - Severo</option>
																		<option value="4 - Fatal"  <?if($row_med['grau']=="4 - Fatal"){echo " selected ";}?>>4 - Comprometimento completo/Fatal</option>
																	</select>
																</div>
															</div>
															<div class="form-group">
																<label class="control-label col-md-3">Relação com a medicação?</label>
																<div class="col-md-5">
																	<select class="form-control" name="form_relacao" id="form_relacao" >
																		<option value=""></option>
																		<option value="1 - Definida" <?if($row_med['relacao']=="1 - Definida"){echo " selected ";}?>>1 - Definida</option>
																		<option value="2 - Provável" <?if($row_med['relacao']=="2 - Provável"){echo " selected ";}?>>2 - Provável</option>
																		<option value="3 - Possível" <?if($row_med['relacao']=="3 - Possível"){echo " selected ";}?>>3 - Possível</option>
																		<option value="4 - Improvável" <?if($row_med['relacao']=="4 - Improvável"){echo " selected ";}?>>4 - Improvável</option>
																		<option value="5 - Condicional" <?if($row_med['relacao']=="5 - Condicional / Não classificada"){echo " selected ";}?>>5 - Condicional / Não classificada</option>
																		<option value="6 - Não relacionada" <?if($row_med['relacao']=="6 - Não relacionada"){echo " selected ";}?>>6 - Não relacionada</option>
																		<option value="7 - Relacionado" <?if($row_med['relacao']=="7 - Relacionado"){echo " selected ";}?>>7 - Relacionado</option>
																	</select>
																</div>
															</div>
															<div class="form-group">
																<label class="control-label col-md-3">Ação tomada</label>
																<div class="col-md-5">
																	<select class="form-control" name="form_acao_tomada" id="form_acao_tomada">
																		<option value=""></option>
																		<option value="1 - Nenhuma ação tomada" <?if($row_med['acao_tomada']=="1 - Nenhuma ação tomada"){echo " selected ";}?> >1 - Nenhuma ação tomada</option>
																		<option value="2 - Dosagem da medicação do estudo ajustada/interrupção temporária da medicação estudo" <?if($row_med['acao_tomada']=="2 - Dosagem da medicação do estudo ajustada/interrupção temporária da medicação estudo"){echo " selected ";}?> >2 - Dosagem da medicação do estudo ajustada/interrupção temporária da medicação estudo</option>
																		<option value="3 - Administração de medicação concomitante" <?if($row_med['acao_tomada']=="3 - Administração de medicação concomitante"){echo " selected ";}?> >3 - Administração de medicação concomitante</option>
																		<option value="4 - Terapia sem uso de medicação" <?if($row_med['acao_tomada']=="4 - Terapia sem uso de medicação"){echo " selected ";}?> >4 - Terapia sem uso de medicação</option>
																		<option value="5 - Hospitalização / prolongamento da hospitalização" <?if($row_med['acao_tomada']=="5 - Hospitalização / prolongamento da hospitalização"){echo " selected ";}?> >5 - Hospitalização / prolongamento da hospitalização</option>
																		<option value="6 - Vide comentário" <?if($row_med['acao_tomada']=="6 - Vide comentário"){echo " selected ";}?> >6 - Vide comentário</option>
																		<option value="7 - Avaliação Clínica" <?if($row_med['acao_tomada']=="7 - Avaliação Clínica"){echo " selected ";}?> >7 - Avaliação Clínica</option>
																	</select>
																</div>
															</div>
															<div class="form-group">
																<label class="control-label col-md-3">Conduta/Evolução</label>
																<div class="col-md-9">
																	<textarea
																		class="form-control"
																		id="form_descricao"
																		name="form_descricao"
																		rows="6"><?=$row_med['descricao'];?></textarea>
																</div>
															</div>
															<div class="form-group">
																<label class="control-label col-md-3">Desfecho</label>
																<div class="col-md-5">
																	<select class="form-control" name="form_desfecho" id="form_desfecho" onchange="mudou(this);">
																		<option value=""></option>
																		<option value="1 - Em recuperação" <?if($row_med['desfecho']=="1 - Em recuperação"){echo " selected ";}?> >1 - Em recuperação</option>
																		<option value="2 - Recuperado" <?if($row_med['desfecho']=="2 - Recuperado"){echo " selected ";}?> >2 - Recuperado</option>
																		<option value="3 - Recuperado com sequela" <?if($row_med['desfecho']=="3 - Recuperado com sequela"){echo " selected ";}?> >3 - Recuperado com sequela</option>
																		<option value="4 - Não recuperado" <?if($row_med['desfecho']=="4 - Não recuperado"){echo " selected ";}?> >4 - Não recuperado</option>
																		<option value="5 - Óbito" <?if($row_med['desfecho']=="5 - Óbito"){echo " selected ";}?> >5 - Óbito</option>
																		<option value="6 - Outros" <?if($row_med['desfecho']=="6 - Outros"){echo " selected ";}?> >6 - Outros</option>
																	</select>
																</div>
															</div>
															<div class="form-group" id="div_desfecho_outros" style="display: none;">
																<label class="control-label col-md-3">Outros, especifique</label>
																<div class="col-md-9">
																	<textarea
																		class="form-control"
																		id="form_desfecho_outros"
																		name="form_desfecho_outros"
																		rows="6"><?=$row_med['desfecho_outros'];?></textarea>
																</div>
															</div>

														</div>
														<div class="form-actions">
															<div class="row">
																<div class="col-md-offset-3 col-md-9">
																	<?
																		if($_GET['opc']!="E"){
																			?>
																			<button type="button" class="btn green" onclick="valida(1);">
																				Incluir e Continuar</button>
																			<?
																		}

																		 if($_SESSION['tipo_usuario']!=5){
																		 ?>
																		 <button type="button" class="btn green" onclick="valida(0);">
																			<?if($_GET['opc']!="E"){echo "  Incluir e fechar ";}else{echo "   Salvar modificações   ";}?></button>
																	<?
																		 }

																	?>
																		<button
																			type="button"
																			class="btn default"
																			onclick="cancelarEA();">Cancelar</button>
																</div>
															</div>
														</div>
														</form>
													</div>
												</div>
										</div>
									</div>
		   <? if(!$modal){ ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		   <? } else { ?>
		   </div>
		   <? } ?>
	</body>
<?
	if(!$modal){
		include "rodape.php";
	}
	include "global_js.php";
?>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script>
		$(document).ready(function () {<?=geraDate("form_data_abertura");?>});
		$(document).ready(function () {<?=geraDate("form_data_inicio");?>});
		$(document).ready(function () {<?=geraDate("form_data_termino");?>});
		$(document).ready(function () {
			<?php
			if($_GET['opc']!="E"){
				echo "gerarNroEvento();";
			}
			?>
			mudou(document.getElementById('form_desfecho'));
		});

		var eaModal = <?=$modal ? 'true' : 'false';?>;
		var eaOpc = '<?=($opc=="E" ? "A" : "I");?>';
		var eaId = <?=(int)$_GET['id'];?>;
		var eaSalvando = false;
		var eaSalvou = false;

		function gerarNroEvento(callback){
			$.ajax({
				url: 'php_php/php_ea.php?act=GERAR_NRO_EVENTO',
				type: 'POST',
				data: {
					projeto: <?=$_SESSION['projeto'];?>
				},
				success: function(response){
					document.getElementById('form_nro_evento').value = $.trim(response);
					if(typeof callback === 'function'){ callback(); }
				},
				error: function(){
					if(typeof callback === 'function'){ callback(); }
				}
			});
		}

		function deveManterAoContinuar(nome){
			return nome=='form_centro'
				|| nome=='form_nro_paciente'
				|| nome=='form_codigo_paciente'
				|| nome=='form_grupo'
				|| nome=='form_periodo';
		}

		function limparCamposContinuar(){
			$('#form_info').find('input, select, textarea').each(function(){
				if(!this.name || deveManterAoContinuar(this.name)){
					return;
				}
				if($(this).hasClass('hasDatepicker')){
					$(this).datepicker('setDate', null);
				}
				this.value = '';
			});
			mudou(document.getElementById('form_desfecho'));
		}

		function cancelarEA(){
			if(eaModal){
				if(window.parent && window.parent.fecharModalEA){
					window.parent.fecharModalEA(eaSalvou);
				}
			}else{
				document.location = 'ecrf_ea.php';
			}
		}

		function salvarEvento(continuar){
			if(eaSalvando){
				return;
			}
			eaSalvando = true;
			$.ajax({
				url: 'php_php/php_ea.php?act=SALVAR',
				type: 'POST',
				data: $('#form_info').serialize() + '&opc=' + encodeURIComponent(eaOpc) + '&id=' + eaId,
				success: function(response){
					if($.trim(response) != 'OK'){
						eaSalvando = false;
						alert('Erro ao salvar o registro.');
						return;
					}
					eaSalvou = true;
					if(parseInt(continuar, 10) === 1){
						alert('Registro inserido com sucesso!');
						limparCamposContinuar();
						gerarNroEvento(function(){
							eaSalvando = false;
						});
					}else{
						eaSalvando = false;
						if(eaOpc == 'A'){
							alert('Registro alterado com sucesso!');
						}else{
							alert('Registro inserido com sucesso!');
						}
						if(eaModal){
							if(window.parent && window.parent.fecharModalEA){
								window.parent.fecharModalEA(true);
							}
						}else{
							document.location = 'ecrf_ea.php';
						}
					}
				},
				error: function(){
					eaSalvando = false;
					alert('Erro ao salvar o registro.');
				}
			});
		}
	</script>
</html>
</html>