<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);

	include_once "../class/conexao.class.php";
	include_once "../class/projeto.class.php";
	include_once "../class/agenda.class.php";
	$varConexao = new Conexao();

	if($_GET['act']=="VISITAR"){
		$varId = $_GET['id'];
		$varConexao = new Conexao();

		$_SESSION['projeto']=$varId;
		$query 	 = "select * from proj_projeto where id = " . $varId;
		$resProj = $varConexao->pesquisa($query);
		$rowProj = mysqli_fetch_assoc($resProj);
		$_SESSION['projeto_nome']=$rowProj['nome'];

		$volta = "../agenda.php";
		$volta = "Location: ". $volta;
		header($volta);
	}
	if($_GET['act']=="FINALIZA"){
		$query = "update proj_projeto set finalizado = 1 where id = " . $_POST['projeto'];
		$varConexao->pesquisa($query);
	}
	if($_GET['act']=="REABRE"){
		$query = "update proj_projeto set finalizado = 0 where id = " . $_POST['projeto'];
		$varConexao->pesquisa($query);
	}
	if($_GET['act']=="EXIBE"){
		$varPatrocinador   = $_POST['patrocinador'];
		$varFinalizado	   = $_POST['finalizado'];
		?>

			<table class="table table-hover table-striped table-bordered" name="tabela" id="tabela">
				<thead>
					<tr>
						<th style="font-size: 12px !important;" width="30px"></th>
						<th style="font-size: 12px !important;">Patrocinador</th>
						<th style="font-size: 12px !important;"><center>#PHARTRIALS</center></th>
						<th style="font-size: 12px !important;">#Patrocinador</th>
						<th style="font-size: 12px !important;">Projeto</th>
						<th style="font-size: 12px !important;"><center>Status Envio<br><?=utf8_decode("Correção");?></center></th>
						<th style="font-size: 12px !important;"><center>Status Retorno<br><?=utf8_decode("Correção");?></center></th>
						<th style="font-size: 12px !important;"><center>Status Envio<br>Patrocinador</center></th>
						<th style="font-size: 12px !important;"><center>Patrocinador<br>Assinou?</center></th>
						<th style="font-size: 12px !important;"><center>Finalizado?</center></th>
					</tr>
				</thead>
				<tbody class="corpo">
					<?php
						$query 		= "select proj_projeto.id, cad_patrocinador.nome from proj_projeto
										inner join cad_patrocinador on proj_projeto.patrocinador = cad_patrocinador.id
											where tipo = 'BIO' ";
						if($varPatrocinador!=""){$query .= " and patrocinador = '".$varPatrocinador."'";}
						if($varFinalizado!=""){$query .= " and finalizado = '".$varFinalizado."'";}
						$query 		.= "  order by proj_projeto.id desc limit 300000";
						$resProj 	= $varConexao->pesquisa($query);
						while($rowProj = mysqli_fetch_assoc($resProj)){
							$varProjeto = new Projeto($rowProj['id']);
							?>
								<tr >
									<td style="valign: middle; font-size: 11px !important;"><center>
										<?php
											if($varProjeto->temAgendamento()>0){
											?>
												<a href="javascript:muda(<?=$varProjeto->id;?>)"><i class="fa fa-plus-square-o" aria-hidden="true"></i></a></center>
											<?php
											}
										?>
									</td>
									<td style="valign: middle; font-size: 11px !important;"><?=$varProjeto->patrocinador->nome;?></td>
									<td valign="middle" style="valign: middle; font-size: 11px !important;"><center><center><a href="php_php/php_dash_bio.php?act=VISITAR&id=<?=$varProjeto->id;?>"><?=$varProjeto->numero;?></a></center></td>
									<td style="valign: middle; font-size: 11px !important;"><center><center><?=$varProjeto->codigo;?></center></td>
									<td style="valign: middle; font-size: 11px !important;"><?=$varProjeto->nome;?></td>
									<td style="valign: middle; font-size: 11px !important;"><center><b><center>
										<?php
											$qtd = intval($varProjeto->getStatusEnvCorrecao());
											if($qtd>0){
												echo '<span class="vermelho"> Pendentes: '.$qtd.' </span>';
											}else{
												echo '<span class="verde">OK</span>';
											}
										?>
									</td>
									<td style="valign: middle; font-size: 11px !important;"><center><span class="dados"><b><center>
										<?php
											$qtd = intval($varProjeto->getStatusEnvRetornado());
											if($qtd>0){
												echo '<span class="vermelho"> Pendentes: '.$qtd.' </span>';
											}else{
												echo '<span class="verde">OK</span>';
											}
										?>
									</td>
									<td style="valign: middle; font-size: 11px !important;"><center><span class="dados"><b><center>
										<?php
											$qtd = intval($varProjeto->getStatusEnvPatrocinador());
											if($qtd>0){
												echo '<span class="vermelho"> Pendentes: '.$qtd.' </span>';
											}else{
												echo '<span class="verde">OK</span>';
											}
										?>
									</td>
									<td style="valign: middle; font-size: 11px !important;"><center><span class="dados"><b><center>
										<?php
											$qtd = intval($varProjeto->getStatusAssPatrocinador());
											if($qtd>0){
												echo '<span class="vermelho"> Pendentes: '.$qtd.' </span>';
											}else{
												echo '<span class="verde">OK</span>';
											}
										?>
									</td>
									<td style="valign: middle; font-size: 11px !important;"><center><span class="dados"><b><center>
										<?php
											echo $varProjeto->getFinalizado();
											if($varProjeto->getFinalizado()!="Sim"){
												//echo '&nbsp;&nbsp;<i class="fa fa-exchange" aria-hidden="true"></i>';
											}
										?>
											&nbsp;&nbsp;&nbsp;
											<div class="btn-group dropup">
												<button type="button" class="btn btn-default dropdown-toggle btn-sm" data-toggle="dropdown">
													<i class="fa fa-exchange"></i>
													<i class="fa fa-angle-down"></i>
												</button>
												<ul class="dropdown-menu dropup">
													<li>
														<a href="javascript:finalizar(<?=$varProjeto->id;?>)"><i class="fa fa-window-close"></i> Finalizar </a>
													</li>
													<li>
														<a href="javascript:reabrir(<?=$varProjeto->id;?>)"><i class="fa fa-bookmark-o"></i> Reabrir </a>
													</li>
												</ul>
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
													<td colspan="9">
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
																		<th><span class="dadosInternoTH"><center></>Patrocinador<br>Assinou?</center></span></th>
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
																					<td valign="middle" style="valign: middle; font-size: 11px !important;"><?=$varAgendamento->presentes;?></td>
																					<td valign="middle" style="valign: middle; font-size: 11px !important;"><center><?=$varAgendamento->data_caption;?></center></td>
																					<td valign="middle" style="valign: middle; font-size: 11px !important;"><center><?=$varAgendamento->periodo;?></center></td>
																					<td valign="middle" style="valign: middle; font-size: 11px !important;"><center><?=$varAgendamento->grupo_periodo;?></center></td>
																					<td valign="middle" style="valign: middle; font-size: 11px !important;"><center><?=$varAgendamento->getEnvCorrecao();?></center></td>
																					<td valign="middle" style="valign: middle; font-size: 11px !important;"><center><?=$varAgendamento->getEnvRetornado();?></center></td>
																					<td valign="middle" style="valign: middle; font-size: 11px !important;"><center><?=$varAgendamento->getEnvPatrocinador();?></center></td>
																					<td valign="middle" style="valign: middle; font-size: 11px !important;"><center><?=$varAgendamento->getAssPatrocinador();?></center></td>
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
						}
					?>
				</tbody>
			</table>

		<?php
	}