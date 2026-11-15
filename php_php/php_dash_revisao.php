<?php
	header('Content-Type:text/html; charset=ISO-8859-1');

	include_once "../class/conexao.class.php";
	include_once "../class/projeto.class.php";
	include_once "../class/agenda.class.php";
	$varConexao = new Conexao();

	error_reporting(~E_ALL);
	ini_set('display_errors', 0);

	if($_GET['act']=="MUDA_STATUS"){
		$tipo 		= $_POST['varTipo'];
		$projeto 	= $_POST['varProj'];
		$status		= $_POST['varStatus'];

		if($tipo=="RC"){ $campo = "rev_rc_status";}
		if($tipo=="RA"){ $campo = "rev_ra_status";}
		if($tipo=="RE"){ $campo = "rev_re_status";}
		if($tipo=="RF"){ $campo = "rev_rf_status";}

		$query = "update proj_projeto
						set " . $campo . " = '".$status."'
					where id = " . $projeto;
		$varConexao->pesquisa($query);
	}
	if($_GET['act']=="SALVA"){
		$conexao = new Conexao();
		$query = "update proj_projeto set
					data_recebimento = '".$_POST['data_recebimento']."',
					data_aviso_revisor = '".$_POST['data_aviso_revisor']."',
					data_resposta_revisor = '".$_POST['data_resposta_revisor']."',
					data_envio_centro = '".$_POST['data_envio_centro']."',
					data_retorno_centro = '".$_POST['data_retorno_centro']."',
					data_finalizacao = '".$_POST['data_finalizacao']."',
					data_limite = '".$_POST['data_limite']."',
					followup = '".$_POST['followup']."',
					status = '".$_POST['status']."'
						where id = ".$_GET['projeto'].";";
		echo $query;
		$conexao->pesquisa($query);
	}
	if($_GET['act']=="EXIBE"){
		$varCentro   = $_POST['centro'];
		$varDataIn   = $_POST['dataIn'];
		$varDataFim  = $_POST['dataFi'];
		$varRevisor  = $_POST['reviso'];
		$varLiberado = $_POST['liberado'];
		if(isset($_POST['status'])){
			$varStatus	 = $_POST['status'];
		}else{
			$varStatus = "";
		}
		$varTipo	 = $_POST['tipo'];


		?>
			<table class="table table-striped table-hover" id="tblProjetos">
				<thead class="header">
					<tr>
						<th style="font-size: 12px !important;" width="30px"></th>
						<th><center># PHARTRIALS</center></th>
						<th><center># Patrocinador</center></th>
						<th>Nome</th>
						<th>Tipo</th>
						<th><?=utf8_decode("País/Registro");?></th>
						<th>Centro</th>


						<th><center>RC</center></th>
						<th><center>RA</center></th>
						<th><center>RE</center></th>
						<th><center>RF</center></th>


						<th><center><?=utf8_decode("Data da última<br>atualização");?></center></th>
						<th><center><?=utf8_decode("STATUS");?></center></th>
						<th><center>Data LIMITE</center></th>
						<th><center><?=utf8_decode("Liberação");?> final</center></th>
						<th><center>...</center></th>
					</tr>
				</thead>
				<tbody class="corpo">
					<?php
						$query 		= "select id from proj_projeto
											where id > 0 and tipo <> 'BIO' and tipo <> 'PESQ' ";

						if($varCentro!=""){$query .= " and centro = '".$varCentro."'";}
						if($varDataIn!=""){
							$varDataIn = substr($varDataIn,6,4) . substr($varDataIn,3,2) . substr($varDataIn,0,2);
							$query .= " and concat(substr(proj_projeto.data_recebimento, 7,4), substr(proj_projeto.data_recebimento, 4,2), substr(proj_projeto.data_recebimento, 1,2)) >= '$varDataIn' ";
						}
						if($varDataFim!=""){
							$varDataFim = substr($varDataFim,6,4) . substr($varDataFim,3,2) . substr($varDataFim,0,2);
							$query .= " and concat(substr(proj_projeto.data_recebimento, 7,4), substr(proj_projeto.data_recebimento, 4,2), substr(proj_projeto.data_recebimento, 1,2)) <= '$varDataFim' ";
						}
						if($varRevisor!=""){
							if($varRevisor=="1"){
								$query .= " and LENGTH(data_aviso_revisor) > 0" ;
							}else{
								$query .= " and data_aviso_revisor = ''" ;
							}
						}
						if($varLiberado!=""){
							if($varLiberado=="2"){
								$query .= " and LENGTH(data_finalizacao) > 0" ;
							}else{
								$query .= " and data_finalizacao = ''" ;
							}
						}
						if($varStatus!=""){$query .= " and status = '".$varStatus."'";}
						if($varTipo!=""){$query .= " and tipo = '".$varTipo."'";}
						$query 		.= "  order by id ";
						//echo $query;
						$resProj 	= $varConexao->pesquisa($query);
						$intSeq = 0;
						while($rowProj = mysqli_fetch_assoc($resProj)){
							$varProjeto = new Projeto($rowProj['id']);
							?>
								<tr>
									<td style="valign: middle; font-size: 11px !important;"><center>
										<?php
											if($varProjeto->temAgendamento()>0){
											?>
												<a href="javascript:abreLinha(<?=$varProjeto->id;?>)"><i class="fa fa-plus-square-o" aria-hidden="true"></i></a></center>
											<?php
											}
										?>
									</td>
									<td class="dados"><span class="dados"><center><?=$varProjeto->numero;?></center></span></td>
									<td><span class="dados"><center><?=$varProjeto->codigo;?></center></td>
									<td><span class="dados"><?=$varProjeto->nome;?></td>
									<td><span class="dados"><center><?=$varProjeto->tipo;?></center></td>
									<td><span class="dados"><?=$varProjeto->pais;?></td>
									<td><span class="dados"><?=$varProjeto->centro;?></td>

									<td>
										<center>
											<div class="btn-group">
												<?php
													if($varProjeto->rev_rc_status==1){
														$btnCor 	= "red";
														$btnLabel 	= "N/D";
													}else{
														$btnCor 	= "green";
														$query 		= "select * from dash_tab_status where id = " . $varProjeto->rev_rc_status;
														$varConexao = new Conexao();
														$resStatus  = $varConexao->pesquisa($query);
														$rowStatus  = mysqli_fetch_assoc($resStatus);
														$btnLabel 	= $rowStatus["nome"];
													}
												?>
												<button class="btn <?=$btnCor?> btn-sm dropdown-toggle" type="button" data-toggle="dropdown"> <?=$btnLabel;?>
													<i class="fa fa-angle-down"></i>
												</button>
												<ul class="dropdown-menu" role="menu">
													<?php
														$query 		= "select * from dash_tab_status";
														$varConexao = new Conexao();
														$resStatus 	= $varConexao->pesquisa($query);
														while($rowStatus = mysqli_fetch_assoc($resStatus)){
															?>
																<li>
																	<a href="javascript:mudaStatus('RC', <?=$varProjeto->id;?>, <?=$rowStatus["id"];?>);"> <?=$rowStatus["nome"];?> </a>
																</li>
															<?php
														}
													?>
												</ul>
											</div>
										</center>
									</td>
									<td>
										<center>
											<div class="btn-group">
												<?php
													if($varProjeto->rev_ra_status==1){
														$btnCor 	= "red";
														$btnLabel 	= "N/D";
													}else{
														$btnCor 	= "green";
														$query 		= "select * from dash_tab_status where id = " . $varProjeto->rev_ra_status;
														$varConexao = new Conexao();
														$resStatus  = $varConexao->pesquisa($query);
														$rowStatus  = mysqli_fetch_assoc($resStatus);
														$btnLabel 	= $rowStatus["nome"];
													}
												?>
												<button class="btn <?=$btnCor?> btn-sm dropdown-toggle" type="button" data-toggle="dropdown"> <?=$btnLabel;?>
													<i class="fa fa-angle-down"></i>
												</button>
												<ul class="dropdown-menu" role="menu">
													<?php
														$query 		= "select * from dash_tab_status";
														$varConexao = new Conexao();
														$resStatus 	= $varConexao->pesquisa($query);
														while($rowStatus = mysqli_fetch_assoc($resStatus)){
															?>
																<li>
																	<a href="javascript:mudaStatus('RA', <?=$varProjeto->id;?>, <?=$rowStatus["id"];?>);"> <?=$rowStatus["nome"];?> </a>
																</li>
															<?php
														}
													?>
												</ul>
											</div>
										</center>
									</td>
									<td>
										<center>
											<div class="btn-group">
												<?php
													if($varProjeto->rev_re_status==1){
														$btnCor 	= "red";
														$btnLabel 	= "N/D";
													}else{
														$btnCor 	= "green";
														$query 		= "select * from dash_tab_status where id = " . $varProjeto->rev_re_status;
														$varConexao = new Conexao();
														$resStatus  = $varConexao->pesquisa($query);
														$rowStatus  = mysqli_fetch_assoc($resStatus);
														$btnLabel 	= $rowStatus["nome"];
													}
												?>
												<button class="btn <?=$btnCor?> btn-sm dropdown-toggle" type="button" data-toggle="dropdown"> <?=$btnLabel;?>
													<i class="fa fa-angle-down"></i>
												</button>
												<ul class="dropdown-menu" role="menu">
													<?php
														$query 		= "select * from dash_tab_status";
														$varConexao = new Conexao();
														$resStatus 	= $varConexao->pesquisa($query);
														while($rowStatus = mysqli_fetch_assoc($resStatus)){
															?>
																<li>
																	<a href="javascript:mudaStatus('RE', <?=$varProjeto->id;?>, <?=$rowStatus["id"];?>);"> <?=$rowStatus["nome"];?> </a>
																</li>
															<?php
														}
													?>
												</ul>
											</div>
										</center>
									</td>
									<td>
										<center>
											<div class="btn-group">
												<?php
													if($varProjeto->rev_rf_status==1){
														$btnCor 	= "red";
														$btnLabel 	= "N/D";
													}else{
														$btnCor 	= "green";
														$query 		= "select * from dash_tab_status where id = " . $varProjeto->rev_rf_status;
														$varConexao = new Conexao();
														$resStatus  = $varConexao->pesquisa($query);
														$rowStatus  = mysqli_fetch_assoc($resStatus);
														$btnLabel 	= $rowStatus["nome"];
													}
												?>
												<button class="btn <?=$btnCor?> btn-sm dropdown-toggle" type="button" data-toggle="dropdown"> <?=$btnLabel;?>
													<i class="fa fa-angle-down"></i>
												</button>
												<ul class="dropdown-menu" role="menu">
													<?php
														$query 		= "select * from dash_tab_status";
														$varConexao = new Conexao();
														$resStatus 	= $varConexao->pesquisa($query);
														while($rowStatus = mysqli_fetch_assoc($resStatus)){
															?>
																<li>
																	<a href="javascript:mudaStatus('RF', <?=$varProjeto->id;?>, <?=$rowStatus["id"];?>);"> <?=$rowStatus["nome"];?> </a>
																</li>
															<?php
														}
													?>
												</ul>
											</div>
										</center>
									</td>


									<td><span class="dados">
										<center>
											<?php

													echo $varProjeto->data_ult_atualizacao;

											?>
										</center>
									</td>
									<td><span class="dados">
										<center>
											<?php
												$status = $varProjeto->status;
												if($status==""){
													echo "N/D";
												}else{
													if($status=="Aguardando 1 Revisor"){ echo "Aguardando 1 Revisor";}
													if($status=="Aguardando 2 Revisor"){ echo "Aguardando 2 Revisor";}
													if($status=="centro"){ echo utf8_decode("Aguardando Revisão I - Centro");}
													if($status=="atcgen2"){ echo utf8_decode("Em Revisão II - PHARTRIALS");}
													if($status=="centro2"){ echo utf8_decode("Aguardando Revisão II - Centro");}
													if($status=="centro3"){ echo utf8_decode("Aguardando retorno do centro");}
													if($status=="agpatrocinador"){ echo utf8_decode("Aguardando Patrocinador");}
													if($status=="patrocinador"){ echo utf8_decode("Aguardando decisão Patrocinador");}
													if($status=="assinatura"){ echo utf8_decode("Aguardando assinatura do patrocinador");}
													if($status=="finalizado"){ echo "Finalizado - Doc assinado - Patrocinador";}
													if($status=="cancelado"){ echo "Cancelado";}
												}

											?>
										</center>
									</td>
									<td><span class="dados"><center><?=$varProjeto->data_limite;?></center></td>
									<td><span class="dados">
										<center>
											<?php
												if($varProjeto->data_finalizacao!=""){
													echo '<span class="label label-sm label-success"> '.$varProjeto->data_finalizacao.' </span>';
												}

											?>
										</center>
									</td>

									<td>
										<center>
											<div class="btn-group dropleft">
												<?php $randomico = rand(1233,235423423); ?>
												<button type="button" class="btn btn-default btn-circle-right dropdown-toggle font-green-sharp" data-toggle="modal" data-target="#modalDatas" href="agenda_edita_datas.php?rand=<?=$randomico;?>&id=<?=$varProjeto->id;?>">
													<i class="fa fa-calendar"></i>
												</button>

											</div>
										</center>
									</td>
								</tr>

								<?php
									$query = "select id from agenda where projeto = ".$varProjeto->id." order by nro_visita";
									$resAg = $varConexao->pesquisa($query);
									if(mysqli_num_rows($resAg)>0){
										?>
											<tr >
													<td colspan="16">
														<div name="projeto_<?=$varProjeto->id;?>" id="projeto_<?=$varProjeto->id;?>" style="display: none">
															<table class="table table-bordered">
																<thead>
																	<tr>
																		<th><span class="dadosInternoTH"><center># Agendamento</center></span></th>
																		<th><span class="dadosInternoTH">Centro</span></th>
																		<th><span class="dadosInternoTH">Presentes<br>/Monitores</span></th>
																		<th><span class="dadosInternoTH"><center>Data</center></span></th>
																		<th><span class="dadosInternoTH"><center><?=utf8_decode("Período");?></center></span></th>
																		<th><span class="dadosInternoTH"><center>Grupo</center></span></th>
																		<th><span class="dadosInternoTH"><center>Enviado p/<br><?=utf8_decode("correção");?></center></span></th>
																		<th><span class="dadosInternoTH"><center>Retornado <br>da <?=utf8_decode("correção");?></center></span></th>
																		<th><span class="dadosInternoTH"><center></>Enviado<br>patrocinador</center></span></th>
																	</tr>
																</thead>
																<tbody>
																	<?php
																		while($rowAg = mysqli_fetch_assoc($resAg)){
																			$varAgendamento = new Agenda($rowAg['id']);
																			?>
																				<tr>
																					<td valign="middle" style="valign: middle; font-size: 11px !important;"><center><?=$varAgendamento->nro_visita;?></center></td>
																					<td valign="middle" style="valign: middle; font-size: 11px !important;"><?=$varAgendamento->centro->nome;?></td>
																					<td valign="middle" style="valign: middle; font-size: 11px !important;"><?=$varAgendamento->monitor->nome;?><br><?=$varAgendamento->revisor->nome;?></td>
																					<td valign="middle" style="valign: middle; font-size: 11px !important;"><center><?=$varAgendamento->data_caption;?></center></td>
																					<td valign="middle" style="valign: middle; font-size: 11px !important;"><center><?=$varAgendamento->periodo;?></center></td>
																					<td valign="middle" style="valign: middle; font-size: 11px !important;"><center><?=$varAgendamento->grupo_periodo;?></center></td>
																					<td valign="middle" style="valign: middle; font-size: 11px !important;"><center><?=$varAgendamento->getEnvCorrecao();?></center></td>
																					<td valign="middle" style="valign: middle; font-size: 11px !important;"><center><?=$varAgendamento->getEnvRetornado();?></center></td>
																					<td valign="middle" style="valign: middle; font-size: 11px !important;"><center><?=$varAgendamento->getEnvPatrocinador();?></center></td>
																				</tr>
																			<?php
																		}
																	?>
																</tbody>
															</table>
														</div>
													</td>
												</tr>
										<?php

									}
								?>





							<?php
							$intSeq++;
						}
					?>
						<tr>
							<td colspan="17" style="text-align: right"><b>Total de registros:</b></td>
							<td><b><?=$intSeq;?></b></td>
						</tr>
				</tbody>
			</table>
		<?php
	}