<?
	include_once __DIR__."/seguranca.php";
	include_once __DIR__."/class/projeto.class.php";
	$varConexao = new Conexao();
?>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
	<script src="js/pace.js"></script>
	<link href="css/pace.css" rel="stylesheet" />
	<?
		include "global_css.php";
	?>
	<link href="assets/global/plugins/morris/morris.css" rel="stylesheet" type="text/css" />
	<link href="assets/global/plugins/jqvmap/jqvmap/jqvmap.css" rel="stylesheet" type="text/css" />
	<link rel="shortcut icon" href="favicon.ico" />
	<link href="assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet" type="text/css" />
	<link href="assets/global/plugins/morris/morris.css" rel="stylesheet" type="text/css" />
	<style>
		.conteudo{
			font-size: 10px;
		}
		.cabecalhoPendencia{
			font-size: 11px;
		}
		.tblHeader{
			font-size: 11px;
		}
		.tblItem{
			font-size: 9px !important;
		}

	</style>
</head>
    <body class="page-container-bg-solid page-header-menu-fixed page-footer-fixed">
        <div class="page-wrapper">
            <?
               include_once "menu_principal.php";
            ?>
            <div class="page-wrapper-row full-height">
                <div class="page-wrapper-middle">
                    <div class="page-container">
                        <div class="page-content-wrapper">
                            <div class="page-head">
                                <div class="container-fluid">
                                    <div class="page-title">
                                        <h1><?=traduzir("DASHBOARD", $_SESSION['lang']);?> </h1>
                                    </div>
                                </div>
                            </div>
							<div class="page-content">
                                <div class="container-fluid">
									<?php
									if(isset($_SESSION['projeto'])){
										$varProjeto = new Projeto($_SESSION['projeto']);
										if($varProjeto->dashproprio==1){
											?>
											<div class="row">
											<?
											include_once "dash_".$_SESSION['projeto'].".php";
											echo "</div><hr><div class='row'>";
										}else{




										if( ($_SESSION['centro']==40) || ($_SESSION['centro']==0) ){
										if(isset($_SESSION['projeto'])){
											$query = "SELECT
														count(*) as qtd
															FROM agenda
																inner join crf_agenda_forms on agenda.id = crf_agenda_forms.agendamento
																inner join crf_agenda_campos on crf_agenda_forms.id = crf_agenda_campos.form_id
																inner join cfg_visitas_campos on crf_agenda_campos.campo_id = cfg_visitas_campos.id
																WHERE agenda.projeto = " . $_SESSION['projeto'] . "
																		and cfg_visitas_campos.tipo <> 'divisao'
																		and cfg_visitas_campos.tipo <> 'json'
																		and cfg_visitas_campos.tipo <> 'json_vertical' ";
											$resQtd = $conexao->pesquisa($query);
											$rowQtd = mysqli_fetch_assoc($resQtd);
											$qtdTot  = $rowQtd['qtd'];

											$query = "SELECT
														count(*) as qtd
															FROM agenda
																inner join crf_agenda_forms on agenda.id = crf_agenda_forms.agendamento
																inner join crf_agenda_campos on crf_agenda_forms.id = crf_agenda_campos.form_id
																inner join cfg_visitas_campos on crf_agenda_campos.campo_id = cfg_visitas_campos.id
																WHERE ( (crf_agenda_campos.valor IS NULL) or (crf_agenda_campos.valor = '') )
																		and agenda.projeto = " . $_SESSION['projeto'] . "
																		and cfg_visitas_campos.tipo <> 'divisao'
																		and cfg_visitas_campos.tipo <> 'json'
																		and cfg_visitas_campos.tipo <> 'json_vertical'";
											//echo $query;
											$resQtd = $conexao->pesquisa($query);
											$rowQtd = mysqli_fetch_assoc($resQtd);
											$qtdBranco  = $rowQtd['qtd'];

											$query = "SELECT
														count(*) as qtd
															FROM agenda
																inner join crf_agenda_forms on agenda.id = crf_agenda_forms.agendamento
																inner join crf_agenda_campos on crf_agenda_forms.id = crf_agenda_campos.form_id
																WHERE agenda.projeto = " . $_SESSION['projeto'] . "
																		and crf_agenda_campos.monitoria_status = 1";
											$resQtd = $conexao->pesquisa($query);
											$rowQtd = mysqli_fetch_assoc($resQtd);
											$qtdMon  = $rowQtd['qtd'];

											$query = "SELECT
														count(*) as qtd
															FROM agenda
																inner join crf_agenda_forms on agenda.id = crf_agenda_forms.agendamento
																inner join crf_agenda_campos on crf_agenda_forms.id = crf_agenda_campos.form_id
																WHERE agenda.projeto = " . $_SESSION['projeto'] . "
																		and crf_agenda_campos.erro_status = 1";
											$resQtd = $conexao->pesquisa($query);
											$rowQtd = mysqli_fetch_assoc($resQtd);
											$qtdErr  = $rowQtd['qtd'];

											$query = "SELECT
														count(*) as qtd
															FROM agenda
																inner join crf_agenda_forms on agenda.id = crf_agenda_forms.agendamento
																inner join crf_agenda_campos on crf_agenda_forms.id = crf_agenda_campos.form_id
																WHERE agenda.projeto = " . $_SESSION['projeto'] . "
																		and crf_agenda_campos.comentario_revisor_status = 'ABERTO'";
											$resQtd = $conexao->pesquisa($query);
											$rowQtd = mysqli_fetch_assoc($resQtd);
											$qtdRevAberto  = $rowQtd['qtd'];


											?>
												<div class="row">
													<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
														<div class="dashboard-stat2 ">
															<div class="display">
																<div class="number">
																	<h3 class="font-green-sharp">
																		<span data-counter="counterup" data-value="<?=$qtdTot;?>"><?=$qtdTot;?></span>
																		<small class="font-green-sharp"></small>
																	</h3>
																	<small><?=traduzir("TOTAL DE CAMPOS");?></small>
																</div>
																<div class="icon">
																	<i class="icon-pie-chart"></i>
																</div>
															</div>
															<div class="progress-info">
																<div class="progress">
																	<span style="width: 100%;" class="progress-bar progress-bar-success green-sharp">
																		<span class="sr-only">100% progress</span>
																	</span>
																</div>
																<div class="status">
																	<div class="status-title">  </div>
																	<div class="status-number"> 100% </div>
																</div>
															</div>
														</div>
													</div>
													<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
														<div class="dashboard-stat2 ">
															<div class="display">
																<div class="number">
																	<h3 class="font-red-haze">
																		<span data-counter="counterup" data-value="<?=$qtdMon;?>"><?=$qtdMon;?></span>
																	</h3>
																	<small><?=traduzir("MONITORADOS");?></small>
																</div>
																<div class="icon">
																	<i class="icon-like"></i>
																</div>
															</div>
															<div class="progress-info">
																<div class="progress">
																	<?
																		if($qtdTot>0){
																			$porcMon = ($qtdMon*100)/$qtdTot;
																		}else{
																			$porcMon = 0;
																		}
																	?>
																	<span style="width: <?=$porcMon;?>%;" class="progress-bar progress-bar-success red-haze">
																		<span class="sr-only"></span>
																	</span>
																</div>
																<div class="status">
																	<div class="status-title"><a href="dash_campos.php?act=monitorados"><b><?=traduzir("LISTA DE CAMPOS");?></b></a></div>
																	<div class="status-number"> <?=number_format($porcMon,2);?>% </div>
																</div>
															</div>
														</div>
													</div>
													<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
														<div class="dashboard-stat2 ">
															<div class="display">
																<div class="number">
																	<h3 class="font-blue-sharp">
																		<span data-counter="counterup" data-value="<?=intval($qtdErr);?>"><?=intval($qtdErr);?></span>
																	</h3>
																	<small><?=traduzir("COM ERRO");?></small>
																</div>
																<div class="icon">
																	<i class="fa fa-times" aria-hidden="true"></i>
																</div>
															</div>
															<div class="progress-info">
																<div class="progress">
																			<?
																				if($qtdTot>0){
																					$porcErr = number_format((($qtdErr*100)/$qtdTot),2);
																				}else{$porcErr = 0;}
																			?>
																	<span style="width: <?=$porcErr;?>%;" class="progress-bar progress-bar-success blue-sharp">
																		<span class="sr-only"><?=$porcErr;?>% <?=traduzir("com erro");?></span>
																	</span>
																</div>
																<div class="status">
																	<div class="status-title"><a href="dash_campos.php?act=erros"><b><?=traduzir("LISTA DE CAMPOS");?></b></a></div>
																	<div class="status-number"> <?=$porcErr;?>% </div>
																</div>
															</div>
														</div>
													</div>

													<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
														<div class="dashboard-stat2 ">
															<div class="display">
																<div class="number">
																	<h3 class="font-blue-sharp">
																		<span data-counter="counterup" data-value="<?=intval($qtdBranco);?>"><?=intval($qtdBranco);?></span>
																	</h3>
																	<small><?=traduzir("EM BRANCO");?></small>
																</div>
																<div class="icon">
																	<i class="fa fa-clone" aria-hidden="true"></i>
																</div>
															</div>
															<div class="progress-info">
																<div class="progress">
																			<?
																				if($qtdTot>0){
																					$porcBra = number_format((($qtdBranco*100)/$qtdTot),2);
																				}else{$porcBra = 0;}
																			?>
																	<span style="width: <?=$porcBra;?>%;" class="progress-bar progress-bar-success blue-sharp">
																		<span class="sr-only"><?=$porcBra;?>% <?=traduzir("com erro");?></span>
																	</span>
																</div>
																<div class="status">
																	<div class="status-title"><a href="dash_campos.php?act=branco"><b><?=traduzir("LISTA DE CAMPOS");?></b></a></div>
																	<div class="status-number"> <?=$porcBra;?>% </div>
																</div>
															</div>
														</div>
													</div>
													<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
														<div class="dashboard-stat2 ">
															<div class="display">
																<div class="number">
																	<h3 class="font-blue-sharp">
																		<span data-counter="counterup" data-value="<?=intval($qtdRevAberto);?>"><?=intval($qtdRevAberto);?></span>
																	</h3>
																	<small><?=traduzir("COMENTÁRIOS DO REVISOR");?></small>
																</div>
																<div class="icon">
																	<i class="icon-user"></i>
																</div>
															</div>
															<div class="progress-info">
																<div class="progress">
																			<?
																				if($qtdTot>0){
																					$porcBra = number_format((($qtdRevAberto*100)/$qtdTot),2);
																				}else{$porcBra=0;}
																			?>
																	<span style="width: <?=$porcBra;?>%;" class="progress-bar progress-bar-success blue-sharp">
																		<span class="sr-only"><?=$porcBra;?>% <?=traduzir("com erro");?></span>
																	</span>
																</div>
																<div class="status">
																	<div class="status-title"><a href="dash_campos.php?act=revisor"><b><?=traduzir("VER LISTA DE CAMPOS");?></b></a></div>
																	<div class="status-number"> <?=$porcBra;?>% </div>
																</div>
															</div>
														</div>
													</div>
													<!-- Itens com revisão de TMV aberto -->
													<?
														$query = "select
																	count(*) as qtd
																		from arq_arquivos
																		where comentario_revisor_status in ('1','2')
																		and projeto_id = " . $_SESSION['projeto'];
														$res = $varConexao->pesquisa($query);
														$rowTMF = mysqli_fetch_assoc($res);
														$qtdRevAberto = $rowTMF['qtd'];
													?>
													<!--<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
														<div class="dashboard-stat2 ">
															<div class="display">
																<div class="number">
																	<h3 class="font-blue-sharp">
																		<span data-counter="counterup" data-value="<?=intval($qtdRevAberto);?>"><?=intval($qtdRevAberto);?></span>
																	</h3>
																	<small><?=traduzir("TMF COM REVISÃO");?></small>
																</div>
																<div class="icon">
																	<i class="fa fa-file-image-o" aria-hidden="true"></i>
																</div>
															</div>
															<div class="progress-info">
																<div class="progress">
																			<?
																				if($qtdTot>0){
																					$porcBra = number_format((($qtdRevAberto*100)/$qtdTot),2);
																				}else{$porcBra=0;}
																			?>
																	<span style="width: <?=$porcBra;?>%;" class="progress-bar progress-bar-success blue-sharp">
																		<span class="sr-only"><?=$porcBra;?>% <?=traduzir("com erro");?></span>
																	</span>
																</div>
																<div class="status">
																	<div class="status-title"><a href="dash_campos_tmf.php?act=revisor"><b><?=traduzir("VER LISTA DE ARQUIVOS");?></b></a></div>
																	<div class="status-number"> </div>
																</div>
															</div>
														</div>
													</div>-->
												</div>
												<br><br>
										<?
										}
										}

											if(isset($_SESSION['projeto'])){

											if($_SESSION['projeto']==256){
											?>

												<div class="tabbable-custom nav-justified">
													<ul class="nav nav-tabs nav-justified">
														<li class="active">
															<a href="#tab_1" data-toggle="tab"><i class="fa fa-address-book-o" aria-hidden="true"></i> <b>Dados gerais</b> </a>
														</li>
														<li>
															<a href="#tab_2" data-toggle="tab"><i class="fa fa-envelope-o" aria-hidden="true"></i> <b>Pendências</b> </a>
														</li>
														<li>
															<a href="#tab_3" data-toggle="tab"><i class="fa fa-files-o" aria-hidden="true"></i> <b>TMF</b> </a>
														</li>
													</ul>
													<div class="tab-content">
														<div class="tab-pane active" id="tab_1">

															<br>
														</div>
														<div class="tab-pane" id="tab_2">

															<br>
														</div>
														<div class="tab-pane" id="tab_3">

															<div class="row">
																<div class="col-md-5">
																	<table id="tableDash" name="tableDash" width="100%" class="table table-bordered table-hover">
																		<thead>
																			<tr>
																				<th class="cabecalhoPendencia"><span class="cabecalhoPendencia"><center>#</center></span></th>
																				<th class="cabecalhoPendencia"><span class="cabecalhoPendencia"><center>Item</center></span></th>
																				<th class="cabecalhoPendencia"><span class="cabecalhoPendencia"><center>#Total<br>Files</center></span></th>
																				<th class="cabecalhoPendencia"><span class="cabecalhoPendencia"><center>Status</center></span></th>
																			</tr>
																		</thead>
																		<tbody>
																			<?
																				include_once "class/tmf.class.php";
																				$query = "select * from arq_tmf
																							where pai = 0
																								 and projeto = " . $_SESSION['projeto'] . "
																								 	order by id";
																				$resNvl1 = $varConexao->pesquisa($query);
																				$indNvl1 = 1;
																				while($rowNvl1 = mysqli_fetch_assoc($resNvl1)){
																					$varNodeNvl1 = new Node($rowNvl1['id']);
																			?>
																					<tr>
																						<td><center><b><span class="tblHeader"><?=$indNvl1;?></span></b></center></td>
																						<td><b><span class="tblHeader"><?=$rowNvl1['nome'];?></span></b></td>
																						<td><center><b><span class="tblHeader"><?=$varNodeNvl1->totalArquivos;?></span></b></center></td>
																						<td></td>
																					</tr>

																					<?php
																						$query = "select * from arq_tmf where pai = '".$rowNvl1['id']."' order by ordem";
																						$resNvl2 = $varConexao->pesquisa($query);
																						$indNvl2 = 1;
																						while($rowNvl2 = mysqli_fetch_assoc($resNvl2)){
																							$varNodeNvl2 = new Node($rowNvl2['id']);
																							?>
																								<tr>
																									<td class="tblItem" style="text-align: right"><?=$indNvl1;?>.<?=$indNvl2;?></td>
																									<td class="tblItem"><?=$varNodeNvl2->nomeOriginal;?></td>
																									<td class="tblItem"><center><?=$varNodeNvl2->totalArquivos;?></center></td>
																									<td class="tblItem" style="background-color: <?=$varNodeNvl2->classCor();?>"><center><?=$varNodeNvl2->status();?></center></td>
																								</tr>

																								<?php
																									$query = "select * from arq_tmf where pai = '".$rowNvl2['id']."' order by ordem";
																									$resNvl3 = $varConexao->pesquisa($query);
																									$indNvl3 = 1;
																									while($rowNvl3 = mysqli_fetch_assoc($resNvl3)){
																										$varNodeNvl3 = new Node($rowNvl3['id']);
																										?>
																											<tr>
																												<td class="tblItem" style="text-align: right"><?=$indNvl1;?>.<?=$indNvl2;?>.<?=$indNvl3;?></td>
																												<td class="tblItem"><?=$varNodeNvl3->nomeOriginal;?></td>
																												<td class="tblItem"><center><?=$varNodeNvl3->totalArquivos;?></center></td>
																												<td class="tblItem" style="background-color: <?=$varNodeNvl3->classCor();?>"><center><?=$varNodeNvl3->status();?></center></td>
																											</tr>
																											<?php
																													$query = "select * from arq_tmf where pai = '".$rowNvl3['id']."' order by ordem";
																													$resNvl4 = $varConexao->pesquisa($query);
																													$indNvl4 = 1;
																													while($rowNvl4 = mysqli_fetch_assoc($resNvl4)){
																														$varNodeNvl4 = new Node($rowNvl4['id']);
																														?>
																															<tr>
																																<td class="tblItem" style="text-align: right"><?=$indNvl1;?>.<?=$indNvl2;?>.<?=$indNvl3;?>.<?=$indNvl4;?></td>
																																<td class="tblItem"><?=$varNodeNvl4->nomeOriginal;?></td>
																																<td class="tblItem"><center><?=$varNodeNvl4->totalArquivos;?></center></td>
																																<td class="tblItem" style="background-color: <?=$varNodeNvl4->classCor();?>"><center><?=$varNodeNvl4->status();?></center></td>
																															</tr>

																														<?php
																														$indNvl4++;
																													}
																													$indNvl3++;
																												}
																							$indNvl2++;
																						}
																					?>
																				<?php
																					$indNvl1++;
																				}
																			?>
																		</tbody>
																	</table>
																</div>
																<div class="col-md-7">
																		2
																</div>

															</div>


														</div>

												</div>



											<br><br>
											<?
											}
											?>
											<div class="row">
												<div class="col-lg-5 col-xs-5 col-sm-5">
													<div class="portlet light portlet-fit ">
														<div class="portlet-title">
															<div class="caption">
																<i class="icon-bubble font-dark"></i>
																<span class="caption-subject font-green-steel bold uppercase"><?=traduzir("Pendências");?></span>
															</div>
														</div>
														<div class="portlet-body">
															<div class="table-scrollable">
																<table id="tableDash" name="tableDash" width="100%" class="table table-bordered table-hover">
																	<thead>
																		<tr>
																			<th class="cabecalhoPendencia"><span class="cabecalhoPendencia"><?=traduzir("Centro");?></span></th>
																			<th class="cabecalhoPendencia"><span class="cabecalhoPendencia"><center><?=traduzir("Tipo");?></span></center></th>
																			<th class="cabecalhoPendencia"><span class="cabecalhoPendencia"><center>#<?=traduzir("Geradas");?></span></center></th>
																			<th class="cabecalhoPendencia"><span class="cabecalhoPendencia"><center>#<?=traduzir("Abertas");?></span></center></th>
																			<th class="cabecalhoPendencia"><span class="cabecalhoPendencia"><center>#<?=traduzir("Resolvidas");?></span></center></th>
																		</tr>
																	</thead>
																	<tbody>
																		<?
																			//Centros
																			if( ($_SESSION['centro']==40) || ($_SESSION['centro']==0) ){
																				$query = "select * from cfg_centro where projeto = ".$_SESSION['projeto']." order by nome";
																			}else{
																				$query = "select * from cfg_centro where projeto = ".$_SESSION['projeto']." and cfg_centro.id = ".$_SESSION['centro']." order by nome";
																			}
																			$resCentro = $conexao->pesquisa($query);

																			$totGer = 0;
																			$totAbe = 0;
																			$totRes = 0;





																			while($rowCentro = mysqli_fetch_assoc($resCentro)){
																		?>
																				<tr>
																					<td rowspan="4"><span class="conteudo"><?=$rowCentro['nome'];?></span></td>
																					<td><span class="conteudo"><?=traduzir("Desvio");?></span></td>
																					<td> <!--# Geradas -->
																						<span class="conteudo"><center>
																							<?
																								$query = "SELECT
																									count(agenda_lista_pendencia.id) as qtd
																									FROM agenda_lista_pendencia
																										where
																											agenda_lista_pendencia.centro = '".$rowCentro['nome']."'
																											and agenda_lista_pendencia.tipo = 'DESVIO'
																											and agenda_lista_pendencia.projeto = " . $_SESSION['projeto'] . "
																								";
																								$resQtd = $conexao->pesquisa($query);
																								$rowQtd = mysqli_fetch_assoc($resQtd);
																								echo $rowQtd['qtd'];
																								$totGer = $totGer + intval($rowQtd['qtd']);
																							?>

																						</center></span>
																					</td>
																					<td> <!--# Abertas -->
																						<span class="conteudo"><center>
																							<?
																								$query = "SELECT
																									count(agenda_lista_pendencia.id) as qtd
																									FROM agenda_lista_pendencia
																										where
																											agenda_lista_pendencia.centro = '".$rowCentro['nome']."'
																											and agenda_lista_pendencia.tipo = 'DESVIO' and status = 'PENDENTE'
																											and agenda_lista_pendencia.projeto = " . $_SESSION['projeto'] . "
																								";
																								$resQtd = $conexao->pesquisa($query);
																								$rowQtd = mysqli_fetch_assoc($resQtd);
																								echo $rowQtd['qtd'];

																								$totAbe = $totAbe + intval($rowQtd['qtd']);

																							?>

																						</center></span>
																					</td>
																					<td> <!--# Resolvidas -->
																						<span class="conteudo"><center>
																							<?
																								$query = "SELECT
																									count(agenda_lista_pendencia.id) as qtd
																									FROM agenda_lista_pendencia
																										where
																											agenda_lista_pendencia.centro = '".$rowCentro['nome']."'
																											and agenda_lista_pendencia.tipo = 'DESVIO' and status = 'RESOLVIDO'
																											and agenda_lista_pendencia.projeto = " . $_SESSION['projeto'] . "
																								";
																								$resQtd = $conexao->pesquisa($query);
																								$rowQtd = mysqli_fetch_assoc($resQtd);
																								echo $rowQtd['qtd'];
																								$totRes = $totRes + intval($rowQtd['qtd']);

																							?>

																						</center></span>
																					</td>
																				</tr>
																				<tr>
																					<td><span class="conteudo"><?=traduzir("Achado");?></span></td>
																					<td> <!--# Gerada -->
																						<span class="conteudo"><center>
																							<?
																								$query = "SELECT
																									count(agenda_lista_pendencia.id) as qtd
																									FROM agenda_lista_pendencia
																										where
																											agenda_lista_pendencia.centro = '".$rowCentro['nome']."'
																											and agenda_lista_pendencia.tipo = 'ACHADO'
																											and agenda_lista_pendencia.projeto = " . $_SESSION['projeto'] . "
																								";
																								$resQtd = $conexao->pesquisa($query);
																								$rowQtd = mysqli_fetch_assoc($resQtd);
																								if(isset($totNeg)){
																									$totNeg = $totNeg + intval($rowQtd['qtd']);
																								}else{
																									$totNeg = 0;
																								}
																								echo $rowQtd['qtd'];
																								$totGer = $totGer + intval($rowQtd['qtd']);
																							?>

																						</center></span>
																					</td>
																					<td> <!--# Abertas -->
																						<span class="conteudo"><center>
																							<?
																								$query = "SELECT
																									count(agenda_lista_pendencia.id) as qtd
																									FROM agenda_lista_pendencia
																										where
																											agenda_lista_pendencia.centro = '".$rowCentro['nome']."'
																											and agenda_lista_pendencia.tipo = 'ACHADO' and status = 'PENDENTE'
																											and agenda_lista_pendencia.projeto = " . $_SESSION['projeto'] . "
																								";
																								$resQtd = $conexao->pesquisa($query);
																								$rowQtd = mysqli_fetch_assoc($resQtd);
																								$totNeg = $totNeg + intval($rowQtd['qtd']);
																								echo $rowQtd['qtd'];

																								$totAbe = $totAbe + intval($rowQtd['qtd']);

																							?>

																						</center></span>
																					</td>
																					<td> <!--# Resolvidas -->
																						<span class="conteudo"><center>
																							<?
																								$query = "SELECT
																									count(agenda_lista_pendencia.id) as qtd
																									FROM agenda_lista_pendencia
																										where
																											agenda_lista_pendencia.centro = '".$rowCentro['nome']."'
																											and agenda_lista_pendencia.tipo = 'ACHADO' and status = 'RESOLVIDO'
																											and agenda_lista_pendencia.projeto = " . $_SESSION['projeto'] . "
																								";
																								$resQtd = $conexao->pesquisa($query);
																								$rowQtd = mysqli_fetch_assoc($resQtd);
																								$totNeg = $totNeg + intval($rowQtd['qtd']);
																								echo $rowQtd['qtd'];
																								$totRes =$totRes + intval($rowQtd['qtd']);
																							?>

																						</center></span>
																					</td>
																				</tr>
																				<tr>
																					<td><span class="conteudo"><?=traduzir("Desvio de GCP");?></span></td>
																					<td> <!--# Gerada -->
																						<span class="conteudo"><center>
																							<?
																								$query = "SELECT
																									count(agenda_lista_pendencia.id) as qtd
																									FROM agenda_lista_pendencia
																										where
																											agenda_lista_pendencia.centro = '".$rowCentro['nome']."'
																											and agenda_lista_pendencia.tipo = 'DESVIO DE GCP'
																											and agenda_lista_pendencia.projeto = " . $_SESSION['projeto'] . "
																								";
																								$resQtd = $conexao->pesquisa($query);
																								$rowQtd = mysqli_fetch_assoc($resQtd);
																								$totNeg = $totNeg + intval($rowQtd['qtd']);
																								echo $rowQtd['qtd'];
																								$totGer = $totGer + intval($rowQtd['qtd']);
																							?>

																						</center></span>
																					</td>
																					<td> <!--# Abertas -->
																						<span class="conteudo"><center>
																							<?
																								$query = "SELECT
																									count(agenda_lista_pendencia.id) as qtd
																									FROM agenda_lista_pendencia
																										where
																											agenda_lista_pendencia.centro = '".$rowCentro['nome']."'
																											and agenda_lista_pendencia.tipo = 'DESVIO DE GCP' and status = 'PENDENTE'
																											and agenda_lista_pendencia.projeto = " . $_SESSION['projeto'] . "
																								";
																								$resQtd = $conexao->pesquisa($query);
																								$rowQtd = mysqli_fetch_assoc($resQtd);
																								$totNeg = $totNeg + intval($rowQtd['qtd']);
																								echo $rowQtd['qtd'];
																								$totAbe = $totAbe + intval($rowQtd['qtd']);
																							?>

																						</center></span>
																					</td>
																					<td> <!--# Resolvidas -->
																						<span class="conteudo"><center>
																							<?
																								$query = "SELECT
																									count(agenda_lista_pendencia.id) as qtd
																									FROM agenda_lista_pendencia
																										where
																											agenda_lista_pendencia.centro = '".$rowCentro['nome']."'
																											and agenda_lista_pendencia.tipo = 'DESVIO DE GCP' and status = 'RESOLVIDO'
																											and agenda_lista_pendencia.projeto = " . $_SESSION['projeto'] . "
																								";
																								$resQtd = $conexao->pesquisa($query);
																								$rowQtd = mysqli_fetch_assoc($resQtd);
																								$totNeg = $totNeg + intval($rowQtd['qtd']);
																								echo $rowQtd['qtd'];
																								$totRes = $totRes + intval($rowQtd['qtd']);
																							?>

																						</center></span>
																					</td>
																				</tr>
																				<tr>
																					<td><span class="conteudo"><?=traduzir("Eventos adversos");?></span></td>
																					<td> <!--# Gerada -->
																						<span class="conteudo"><center>
																							<?
																								$query = "SELECT
																											count(*) as qtd
																												FROM adv_evento
																													WHERE
																														centro =  ".$rowCentro['id']."
																														and adv_evento.projeto = " . $_SESSION['projeto'];

																								$resQtd = $conexao->pesquisa($query);
																								$rowQtd = mysqli_fetch_assoc($resQtd);
																								$totNeg = $totNeg + intval($rowQtd['qtd']);
																								echo $rowQtd['qtd'];
																								$totGer = $totGer + intval($rowQtd['qtd']);
																							?>

																						</center></span>
																					</td>
																					<td> <!--# Abertas -->
																						<span class="conteudo"><center>
																							<?
																								$query = "SELECT
																											count(*) as qtd
																												FROM adv_evento
																													WHERE desfecho <> '2 - Resolvido'
																													and centro =  ".$rowCentro['id']."
																													and adv_evento.projeto = " . $_SESSION['projeto'];

																								$resQtd = $conexao->pesquisa($query);
																								$rowQtd = mysqli_fetch_assoc($resQtd);
																								$totNeg = $totNeg + intval($rowQtd['qtd']);
																								echo $rowQtd['qtd'];
																								$totAbe = $totAbe + intval($rowQtd['qtd']);
																							?>

																						</center></span>
																					</td>
																					<td> <!--# Resolvidas -->
																						<span class="conteudo"><center>
																							<?
																								$query = "SELECT
																											count(*) as qtd
																												FROM adv_evento
																													WHERE
																														desfecho = '2 - Resolvido'
																														and centro =  ".$rowCentro['id']."
																														and adv_evento.projeto = " . $_SESSION['projeto'];

																								$resQtd = $conexao->pesquisa($query);
																								$rowQtd = mysqli_fetch_assoc($resQtd);
																								$totNeg = $totNeg + intval($rowQtd['qtd']);
																								echo $rowQtd['qtd'];
																								$totRes = $totRes + intval($rowQtd['qtd']);
																							?>

																						</center></span>
																					</td>
																				</tr>
																		<?
																			}
																		?>
																	</tbody>
																	<tfooter>
																		<tr>
																			<td style="text-align: right"><b></b></td>
																			<td style="text-align: right"><b><span class="cabecalhoPendencia"><?=traduzir("Total");?></span></b></td>
																			<td><center><b><span class="cabecalhoPendencia"><?=$totGer;?></span></b></center></td>
																			<td><center><b><span class="cabecalhoPendencia"><?=$totAbe;?></span></b></center></td>
																			<td><center><b><span class="cabecalhoPendencia"><?=$totRes;?></span></b></center></td>
																		</tr>
																	</tfooter>
																</table>
															</div>
														</div>
													</div>
												</div>

											</div>
										<?
										}
										}

									}
									?>



								</div>
							</div>
                        </div>
                    </div>
                </div>
            </div>
			<iframe id="txtArea1" style="display:none"></iframe>

			<?
				include "rodape.php";
			?>
        </div>
        <?
			include "global_js.php";
		?>


		<script src="assets/global/plugins/moment.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/morris/morris.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/morris/raphael-min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/counterup/jquery.waypoints.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/counterup/jquery.counterup.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/amcharts/amcharts/amcharts.js" type="text/javascript"></script>
        <script src="assets/global/plugins/amcharts/amcharts/serial.js" type="text/javascript"></script>
        <script src="assets/global/plugins/amcharts/amcharts/pie.js" type="text/javascript"></script>
        <script src="assets/global/plugins/amcharts/amcharts/radar.js" type="text/javascript"></script>
        <script src="assets/global/plugins/amcharts/amcharts/themes/light.js" type="text/javascript"></script>
        <script src="assets/global/plugins/amcharts/amcharts/themes/patterns.js" type="text/javascript"></script>
        <script src="assets/global/plugins/amcharts/amcharts/themes/chalk.js" type="text/javascript"></script>
        <script src="assets/global/plugins/amcharts/ammap/ammap.js" type="text/javascript"></script>
        <script src="assets/global/plugins/amcharts/ammap/maps/js/worldLow.js" type="text/javascript"></script>
        <script src="assets/global/plugins/amcharts/amstockcharts/amstock.js" type="text/javascript"></script>
        <script src="assets/global/plugins/fullcalendar/fullcalendar.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/horizontal-timeline/horizontal-timeline.js" type="text/javascript"></script>
        <script src="assets/global/plugins/flot/jquery.flot.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/flot/jquery.flot.resize.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/flot/jquery.flot.categories.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jquery-easypiechart/jquery.easypiechart.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jquery.sparkline.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jqvmap/jqvmap/jquery.vmap.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.russia.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.world.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.europe.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.germany.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.usa.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jqvmap/jqvmap/data/jquery.vmap.sampledata.js" type="text/javascript"></script>


		<script src="assets/pages/scripts/dashboard.min.js" type="text/javascript"></script>
		<script src="assets/pages/scripts/ui-confirmations.min.js" type="text/javascript"></script>	<script src="assets/global/plugins/bootstrap-multiselect/js/bootstrap-multiselect.js" type="text/javascript"></script>
		<script src="assets/pages/scripts/components-bootstrap-multiselect.min.js" type="text/javascript"></script>
		<script type="text/javascript">
			$(document).ready(function() {
				$('#filtroPacientes').multiselect({
					enableFiltering: true,
					includeSelectAllOption: true,
					maxHeight: 300,
					width: 200,
					maxWidth:200,
					dropUp: true,
					resizable: true
				});
			});
		</script>
		<script>
			function muda_pagina(entrada){
				$.blockUI({ timeout: 500, message: '<img src="imagens/carregando.gif" width="100">Aguarde, carregando informações...', css: {
					border: 'none',
					padding: '15px',
					backgroundColor: '#000',
					'-webkit-border-radius': '10px',
					'-moz-border-radius': '10px',
					opacity: .5,
					color: '#fff'
				} });
				document.location = entrada;
			}
		</script>
		<?
			if(isset($_SESSION['projeto'])){
				if($_SESSION['projeto'] == "7"){
			?>



			<script>
			am4core.ready(function() {
			var chart = am4core.create("chartdivPacientexDia", am4charts.XYChart);

			// Add data
			chart.data = [
			<?
			$query = "SELECT
				substring(log_alteracoes.data_hora, 1, 10) as data,
				count(crf_entrada_forms.paciente) as qtd
				FROM log_alteracoes
					inner join crf_entrada_campos on log_alteracoes.id_campo = crf_entrada_campos.campo_id
												 and log_alteracoes.id_tabela = crf_entrada_campos.form_id
					inner join crf_entrada_forms on crf_entrada_campos.form_id = crf_entrada_forms.id
					where campo = 'VALIDADO'
				group by substring(log_alteracoes.data_hora, 1, 10)
				order by substring(log_alteracoes.data_hora, 1, 10)";
			//$resDatas = pesquisa($query);
			$ind = 0;
			while($rowDatas = mysqli_fetch_assoc($resDatas)){
				if($ind>0){echo ",";}
				echo '{
					  "dia": "'.$rowDatas['data'].'",
					  "monitorados": '.$rowDatas['qtd'].'
					}';
				$ind++;
			}
			?>

			];

			// Create axes

			var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
			categoryAxis.dataFields.category = "dia";
			categoryAxis.renderer.grid.template.location = 0;
			categoryAxis.renderer.minGridDistance = 30;

			categoryAxis.renderer.labels.template.adapter.add("dy", function(dy, target) {
			  if (target.dataItem && target.dataItem.index & 2 == 2) {
				return dy + 25;
			  }
			  return dy;
			});

			var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

			// Create series
			var series = chart.series.push(new am4charts.ColumnSeries());
			series.dataFields.valueY = "monitorados";
			series.dataFields.categoryX = "dia";
			series.name = "Monitorados";
			series.columns.template.tooltipText = "{categoryX}: [bold]{valueY}[/]";
			series.columns.template.fillOpacity = .8;

			var columnTemplate = series.columns.template;
			columnTemplate.strokeWidth = 2;
			columnTemplate.strokeOpacity = 1;

			}); // end am4core.ready()
			</script>



			<?
			}
		}
		?>

</body>
		<?
	    	$query = "select * from usu_pagina_atual where sessao = " . $_SESSION['sessao'];
	    	$res_se = $conexao->pesquisa($query);
	    	if(mysqli_num_rows($res_se)<1) {
	    		$query = "insert into usu_pagina_atual (sessao, pagina) values (".$_SESSION['sessao'].",'".str_replace("/", "",$_SERVER['REQUEST_URI'])."')";
	    	}else{
				$query = "update usu_pagina_atual set pagina = '".str_replace("/", "",$_SERVER['REQUEST_URI'])."' where sessao = " . $_SESSION['sessao'];
	    	}
	    	$conexao->pesquisa($query);
			unset($_SESSION['ult_pagina']);
		    $_SESSION['ult_pagina'] = str_replace("/", "",$_SERVER['REQUEST_URI']);
		?>
</html>
