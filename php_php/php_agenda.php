<?php
	header('Content-Type:text/html; charset=ISO-8859-1');
	header("Pragma: no-cache");
	session_start();

	include_once __DIR__."/../class/conexao.class.php";
	include_once __DIR__."/../class/email.class.php";
	include_once __DIR__."/../class/agenda.class.php";
	include_once __DIR__."/../class/projeto.class.php";
	include_once __DIR__."/../class/usuario.class.php";
	include_once __DIR__."/../funcoes.php";

	$varConexao = new Conexao();


	switch($_GET['act']){
		case	'SALVA_VERSAO':
			$agendaId     = $_POST['agendaId'];
			$versaoId     = $_POST['versaoId'];
			$versaoNr     = $_POST['versaoNr'];
			$versaoMotivo = $_POST['versaoMotivo'];
			$versaoData   = $_POST['versaoData'];

			if($versaoId<1){
				$query = "insert into agenda_plano_versoes (agenda, versao, motivo, data) values ('".$agendaId."','".$versaoNr."','".$versaoMotivo."','".$versaoData."')";
			}else{
				$query = "update agenda_plano_versoes set versao = '".$versaoNr."', motivo = '".$versaoMotivo."', data = '".$versaoData."' where id = ".$versaoId;
			}
			$varConexao->pesquisa(formata_utf8_decode($query));
			echo $query;
			return;
		break;
		case	'LISTA_VERSOES':
			$varAgenda = new Agenda($_POST['agendaId']);
			echo "<tr>";
			echo "<td><center>1</center></td>";
			echo "<td>Emissão do documento</td>";
			echo "<td><center>".listoDATA($varAgenda->data)."</center></td>";
			echo "<td></td>";
			echo "</tr>";

			$query = "select * from agenda_plano_versoes where agenda = ".$_POST['agendaId'];
			$res = $varConexao->pesquisa($query);
			while($row = mysqli_fetch_assoc($res)){
				echo "<tr>";
				echo "<td><center>".$row['versao']."</center></td>";
				echo "<td>".$row['motivo']."</td>";
				echo "<td><center>".$row['data']."</center></td>";
				echo "<td><center><a href='javascript:excluirVersao(".$row['id'].")'><img src='imagens/img_x.png' alt='Excluir Versão' width='15'></a></center></td>";
				echo "</tr>";
			}
			return;
		break;
		case	'EXCLUIR_VERSAO':
			$query = "delete from agenda_plano_versoes where id = ".$_GET['id'];
			$varConexao->pesquisa(formata_utf8_decode($query));
			echo $query;
			return;
		break;
		case	'BUSCAR_DADOS_MONITOR':
			$query = "select * from usu_usuario where id = ".$_POST['varId'];
			$res = $varConexao->pesquisa($query);
			$row = mysqli_fetch_assoc($res);
			$dados = array(
				"email" => $row['email'],
				"celular" => $row['celular']
			);
			$dados = array_map('utf8_encode', $dados);
			echo json_encode($dados);
			return;
		break;
		case	'AGENDA_ATIVIDADES_ATV_SALVAR':
			if($_POST['marcado']==1){
				$query = "insert into agenda_atividade_atv (id_atividade, id_tab_atividade, marcado) values (".$_POST['id_atv'].",".$_POST['id_atividade'].",".$_POST['marcado'].")";
				$varConexao->pesquisa(formata_utf8_decode($query));
				$query = "update  agenda_atividade_atv set marcado = '".$_POST['marcado']."' where id_atividade = '".$_POST['id_atv']."' and id_tab_atividade = '".$_POST['id_atividade']."';";
				$varConexao->pesquisa(formata_utf8_decode($query));
			}else{
				$query = "update agenda_atividade_atv set marcado = 0 where id_atividade = ".$_POST['id_atv']." and id_tab_atividade = ".$_POST['id_atividade'];
				$varConexao->pesquisa(formata_utf8_decode($query));
			}
			echo $query;
			return;
		break;
		/*	$query = "update agenda_atividade_atv set marcado = ".$_POST['marcado']." where id_atividade = ".$_POST['id']." and id_tab_atividade = ".$_POST['id_atv'];
			$varConexao->pesquisa(formata_utf8_decode($query));
			return;
		break;*/
		case	'AGENDA_ATIVIDADES_ATV_INCLUIR':
			$query = "insert into tab_agenda_atividades (atividade) values ('".$_POST['atv']."')";
			$varConexao->pesquisa(formata_utf8_decode($query));
			echo $query;
			return;
		break;
		case	'AGENDA_ATIVIDADES_ATV_EXCLUIR':
			$query = "delete from agenda_atividade_atv where id = ".$_POST['id'];
			$varConexao->pesquisa(formata_utf8_decode($query));
			return;
		break;
		case	'AGENDA_ATIVIDADES_ATV_LISTA':

			$query   = "select * from agenda_atividade where id = ".$_POST['id'];
			$res     = $varConexao->pesquisa($query);
			$row     = mysqli_fetch_assoc($res);
			$data    = $row['data'];
			$periodo = $row['periodo'];

			$query = "select
						agenda_atividade.data,
						tab_agenda_atividades.id as id_atv,
						tab_agenda_atividades.atividade,
						agenda_atividade_atv.marcado
						from tab_agenda_atividades
							left join agenda_atividade_atv on tab_agenda_atividades.id = agenda_atividade_atv.id_tab_atividade
								and agenda_atividade_atv.id_atividade = ".$_POST['id']."
							left join agenda_atividade on agenda_atividade_atv.id_atividade = agenda_atividade.id
							where tab_agenda_atividades.ativo = 1";
			$res = $varConexao->pesquisa($query);
			while($row = mysqli_fetch_assoc($res)){
				?>
					<tr>
						<td style="text-align: center;">
							<?php
							if($row['marcado']==1){
								?>
									<input onchange="salvaAtv(this.checked,'<?=$_POST['id']?>_<?=$row['id_atv']?>')" type="checkbox" name="marcado" id="marcado" checked>
							<?php
							}else{
								?>
									<input onchange="salvaAtv(this.checked,'<?=$_POST['id']?>_<?=$row['id_atv']?>')" type="checkbox" name="marcado" id="marcado">
								<?php
							}

							?>
						</td>
						<td><?=$row['atividade'];?></td>

					</tr>
				<?
			}
			return;
		break;
		/*case	'AGENDA_ATIVIDADES_ATV_SALVAR':
			$query = "insert into agenda_atividade_atv (id_atividade, atividade) values (".$_POST['id_data'].", '".addslashes($_POST['atv'])."')";
			$varConexao->pesquisa(formata_utf8_decode($query));
			echo $query;
			return;
		break;*/
		case	'AGENDA_ATIVIDADES_DATA_SALVAR':
			$query = "insert into agenda_atividade (agenda, data, periodo) values (".$_POST['agenda'].", '".$_POST['data']."', '".$_POST['periodo']."')";
			$varConexao->pesquisa(formata_utf8_decode($query));
			echo $query;
			return;
		break;
		case	'AGENDA_ATIVIDADES_DATA_ATUALIZAR':
			$query = "update agenda_atividade set data = '".$_POST['data']."', periodo = '".$_POST['periodo']."' where id = ".$_POST['id'];
			$varConexao->pesquisa(formata_utf8_decode($query));
			return;
		break;
		case	'AGENDA_ATIVIDADES_DATA_LISTA':
			$query = "select * from agenda_atividade where agenda = ".$_POST['agenda']." order by id";
			$res = $varConexao->pesquisa($query);
			while($row = mysqli_fetch_assoc($res)){
				$data_attr = htmlspecialchars($row['data'], ENT_QUOTES, 'UTF-8');
				$periodo_attr = htmlspecialchars($row['periodo'], ENT_QUOTES, 'UTF-8');
				?>
					<tr>
						<td><?=$row['data'];?></td>
						<td><?=$row['periodo'];?></td>
						<td>
							<a href="javascript:void(0)" class="btn-editar-data" data-id="<?=$row['id'];?>" data-data="<?=$data_attr;?>" data-periodo="<?=$row['periodo'];?>" title="Editar data/horário"><i class="fa fa-edit"></i></a>
							&nbsp;
							<a href='javascript:excluirData(<?=$row['id'];?>)'><img src='imagens/img_x.png' alt='Excluir Data' width='15'></a>
							&nbsp;
							<a href='javascript:selecionarData(<?=$row['id'];?>)'><img src='imagens/seta_direita.png' alt='Selecionar Data' width='20px'></a>
						</td>
					</tr>
				<?
			}
			return;
		break;
		case	'AGENDA_ATIVIDADES_DATA_EXCLUIR':
			$query = "delete from agenda_atividade where id = ".$_POST['id'];
			$varConexao->pesquisa(formata_utf8_decode($query));
			$varConexao->pesquisa("delete from agenda_atividade_atv where id_atividade = ".$_POST['id']);
			return;
		break;
		case	'AGENDA_ATIVIDADES_DATA_SELECIONAR':
			$query = "select
						*
							from agenda_atividade_atv
								where id_atividade = ".$_POST['id'];
			$res = $varConexao->pesquisa($query);
			while($row = mysqli_fetch_assoc($res)){
				?>
					<tr>
						<td><?=$row['atividade'];?></td>
						<td>
							<a href='javascript:excluirAtv(<?=$row['id'];?>)'><img src='imagens/img_x.png' alt='Excluir Atividade' width='15'></a>
						</td>
					</tr>
				<?
			}
			return;
		break;
		case	'AGENDA_ATIVIDADES':

			$data[] = array(
				"data"=>"",
				"horario"=>"",
				"atividade"=>"",
				"botao"=>""
			);
			$response = array(
				"iTotalRecords" => 0,
				"iTotalDisplayRecords" => 0,
				"data" => $data
			  );
			echo json_encode($response);

			$query = "
                select
                    *
                    from agenda_desvio_horario
                        where projeto = ".$_SESSION['projeto']."
                            order by centro, participante, id
            ";
            $res = $varConexao->pesquisa($query);
            if(mysqli_num_rows($res)==0){
                $data[] = array(
                    "centro"=>"",
                    "participante"=>"",
                    "horario_coleta"=>"",
                    "horario_previsto"=>"",
                    "horario_realizado"=>"",
                    "tempo_atraso"      => "",
                    "motivo"=>"",
                    "botao"=>""
                );
                $response = array(
                    "iTotalRecords" => 0,
                    "iTotalDisplayRecords" => 0,
                    "data" => $data
                  );
                echo json_encode($response);
            }else{
                while($row = mysqli_fetch_assoc($res)){
                    $link = "javascript:btnRegistro('modal_tab_devio.php?id=".$row['id']."');";
                    $btn = '<a alt="Visualizar CRF" href="'.$link.'" class="btn btn-circle btn-sm blue">
									<i class="fa fa-search" alt="Visualizar CRF" ></i>
								</a>';
                    $tempo_atraso = retornaDiferencaHorario($row['horario_previsto'], $row['horario_realizado']);
                    $data[] = array(
                        "centro"            => $row['centro'],
                        "participante"      => $row['participante'],
                        "horario_coleta"    => $row['horario_coleta'],
                        "horario_previsto"  => $row['horario_previsto'],
                        "horario_realizado" => $row['horario_realizado'],
                        "tempo_atraso"      => "<center>$tempo_atraso</center>",
                        "motivo"            => formata_utf8_encode($row['motivo']),
                        "botao"             => "$btn"
                    );
                }
                echo json_encode($data);
                return;
            }
			return;
		break;
		case	'SALVA_CFG':
			$varId = $_POST['varId'];
			$varConsoResumo 	= $_POST['varConsoResumo'];
			$varDesvio 			= utf8_decode($_POST['varDesvio']);
			$varNotificacao 	= utf8_decode($_POST['varNotificacao']);
			$varConclusao 		= utf8_decode($_POST['varConclusao']);
			$query = "update proj_projeto set conso_desvio = '$varDesvio', conso_notificacao = '$varNotificacao', conso_conclusao = '$varConclusao', conso_resumo = '".utf8_decode($varConsoResumo)."' where id = " . $_SESSION['projeto'];
			$varConexao->pesquisa($query);
			return;
		break;
		case	'SALVA_DATA':
			$query = "update agenda set conso_data_inicial = '".$_POST['varInicio']."', conso_data_final = '".$_POST['varFinal']."' where id = "  . $_POST['varId'];
			echo $query;
			$varConexao->pesquisa($query);
			return;
		break;
	}

	if($_GET['act']=="ENVIAR_EMAIL"){
		$id = $_POST['id'];
		$varAgendamento = new Agenda($id);
		$varProjeto 	= new Projeto($_SESSION['projeto']);
		echo $varAgendamento->registrarAlerta();
		$Date = date('Y-m-d');
		$varEmail = new EnvioEmail("envio@sistemadepesquisaclinica.com.br", "Sistema de Pesquisa Clinica - SPC", "grupophartrials@phartrials.com.br", $varAgendamento->revisor->nome, 1);
		//$varEmail = new EnvioEmail("envio@sistemadepesquisaclinica.com.br", "Sistema de Pesquisa Clinica - SPC", "eduqsb@gmail.com", $varAgendamento->monitor->nome, 0);
		$message = utf8_encode(file_get_contents('../modelos/modelo_liberado_revisor.html'));
		$message = str_replace('%projeto%', utf8_encode($varProjeto->numero . " - " . $varProjeto->nome),$message);
		$message = str_replace('%agendamento%', $varAgendamento->nro_visita, $message);
		$message = str_replace('%data%', $varAgendamento->data_caption, $message);
		$message = str_replace('%liberado_por%', $_SESSION['nome_usuario'],$message);
		$message = str_replace('%revisor%', $varAgendamento->revisor->nome,$message);
		$message = str_replace('%data_hora%', date('d/m/Y H:i'),$message);
		$message = str_replace('%prazo_liberar%', date('Y-m-d', strtotime($Date. ' + 2 days')),$message);
		$varEmail->enviar("Liberação de arquivo para Revisão", $message);
	}
	if($_GET['act']=="ENVIAR_EMAIL_TERMINO"){
		$varAgendamento = new Agenda($_POST['id']);
		$varProjeto 	= new Projeto($_SESSION['projeto']);
		echo $varAgendamento->registrarRevisado();

		$varEmail = new EnvioEmail("envio@sistemadepesquisaclinica.com.br", "Sistema de Pesquisa Clinica - SPC", "grupophartrials@phartrials.com.br", $varAgendamento->monitor->nome, 1);
		$message = utf8_encode(file_get_contents('../modelos/modelo_liberado_revisao.html'));
		$message = str_replace('%projeto%', utf8_encode($varProjeto->numero . " - " . $varProjeto->nome),$message);
		$message = str_replace('%agendamento%', $varAgendamento->nro_visita, $message);
		$message = str_replace('%data%', $varAgendamento->data_caption, $message);
		$message = str_replace('%liberado_por%', $_SESSION['nome_usuario'],$message);
		$message = str_replace('%data_hora%', date('d/m/Y H:i'),$message);
		$varEmail->enviar("Término de revisão de documento", $message);
	}
	if($_GET['act']=="NOVA_VERSAO"){
		$varAgendamento = new Agenda($_POST['varId']);
		echo $varAgendamento->novaVersao(formata_utf8_decode($_POST['varMotivo']));
	}
	if($_GET['act']=="EDITA_AGENDAMENTO"){
		$conexao = new Conexao();
		$dataIni = DataToLIS(utf8_decode($_POST['varDataIni']));
		$dataFim = DataToLIS(utf8_decode($_POST['varDataFim']));
		if($dataIni==$dataFim){
			$dataCaption = listoDATA($dataIni);
		}else{
			$dataCaption = listoDATA($dataIni) . " ? " . listoDATA($dataFim);
		}

		$query   = "update agenda set centro = '".$_POST['varCentro']."',
										versao = '".utf8_decode($_POST['varVersao'])."',
										grupo_periodo ='".utf8_decode($_POST['varGrupo'])."',
										periodo = '".utf8_decode($_POST['varPeriodo'])."',
										data = '".$dataIni."',
										datafinal = '".$dataFim."',
										data_caption = '".$dataCaption."',
										hora = '".utf8_decode($_POST['varHora'])."',
										monitora = '".utf8_decode($_POST['varMonitor'])."',
										revisor = '".utf8_decode($_POST['varRevisor'])."',
										titulo = '".utf8_decode($_POST['varTitulo'])."',
										atividades = '".utf8_decode(nl2br($_POST['varAtividade']))."',
										presentes = '".utf8_decode($_POST['varPresentes'])."',
										rel_pre_objetivo = '".utf8_decode(nl2br($_POST['varRelObjetivo']))."',
										rel_pre_apontamentos = '".utf8_decode(nl2br($_POST['varRelApontamento']))."',
										rel_pre_tipo_documento = '".utf8_decode(nl2br($_POST['varRelTipo']))."',
										rel_pre_checklist = '".utf8_decode(nl2br($_POST['varRelChecklist']))."',
										contato = '".utf8_decode($_POST['varContato'])."',
										data_impressao = '".utf8_decode(DataToLIS($_POST['varDataImpressao']))."'
											where id = " . $_POST['varId'];
		echo $query;
		$conexao->pesquisa($query);
	}
	if($_GET['act']=="LISTAGEM"){
		$conexao = new Conexao();
		$query = "select id from agenda where projeto = ".$_SESSION['projeto']."
						order by nro_visita";
		$resAgendamento = $conexao->pesquisa($query);
		while($rowAgendamento=mysqli_fetch_assoc($resAgendamento)) {
			$varAgenda = new Agenda($rowAgendamento['id']);
		 ?>
			<tr style="font-size: 1pt !important">
				<td style="vertical-align: middle;text-align:center">
				<?php
					//if($_SESSION['tipo_usuario']==1){
						?><a href="javascript:excluir_agendamento(<?=$varAgenda->id;?>)"><i class="fa fa-ban"></i></a>
						<?php
					//}
					// if($_SESSION['tipo_usuario']!=5){
					?>
					<center>
						<?
							$randomico = rand(45,9985547);
						?>
						<a data-toggle="modal" data-target="#myModal" href="agenda_edita.php?rand=<?=$randomico;?>&id=<?=$varAgenda->id;?>">
							<i class="fa fa-edit"></i></a>
						<br>
					</center>
					<?
					//}
						//if($_SESSION['user_id']==1){
						?>
						<!--<a data-toggle="modal" data-target="#modalDatas" href="agenda_edita_datas.php?rand=<?=$randomico;?>&id=<?=$varAgenda->id;?>">
							<i class="fa fa-edit"></i></a>-->
							<?
						//}
						?>
				</td>
				<td style="vertical-align: middle;"><font size="1pt"><?=$varAgenda->centro->nome;?></font></td>
				<td style="vertical-align: middle;text-align:center"><font size="1pt"><center><b><?=$varAgenda->nro_visita . "</b>"?></center></font></td>
				<td style="vertical-align: middle;text-align:center"><font size="1pt"><center><b><?="Versão " . $varAgenda->versao;?><br></b><i>[<?=$varAgenda->versao_motivo;?>]</i></center></font></td>
				<td style="vertical-align: middle;text-align:center"><font size="1pt"><center>Período: <?=$varAgenda->periodo . "<br>Grupo: ".$varAgenda->grupo_periodo;?></center></font></td>
				<td style="vertical-align: middle;"><font size="1pt"><?=strtoupper($varAgenda->tipo);?></font></td>
				<td style="vertical-align: middle;text-align:center"><font size="1pt"><center><?
					echo listoDATA($varAgenda->data);
				?></center></font></td>
				<td style="vertical-align: middle;">
					<font size="1pt">
						<?=$varAgenda->monitor->nome;?>
						<br>
						<?=$varAgenda->revisor->nome;?>
					</font>
				</td>
				<td style="vertical-align: middle;text-align:center">
					<center>
						<span name="<?=$varAgenda->id;?>_alertado" id="<?=$varAgenda->id;?>_alertado">
							<input disabled type="checkbox" name="check" id="check" <?if($varAgenda->rel_alertado==1){echo " checked ";}?>></input>
							<font style="font-size:8px"><br><?=$varAgenda->rel_alertado_quem;?><br><?=$varAgenda->rel_alertado_data;?></font>

						</span>
					</center>
				</td>
				<td style="vertical-align: middle;text-align:center">
					<center>
						<span name="<?=$varAgenda->id;?>_revisao_ok" id="<?=$varAgenda->id;?>_revisao_ok">
							<input disabled type="checkbox" name="check" id="check" <?if($varAgenda->rel_revisado_data!=""){echo " checked ";}?>></input>
							<font style="font-size:8px"><br><?=$varAgenda->rel_revisado_quem;?><br><?=$varAgenda->rel_revisado_data;?></font>

						</span>
					</center>
				</td>
				<td style="vertical-align: middle;text-align:center">
					<center>
						<span name="<?=$varAgenda->id;?>_enviado" id="<?=$varAgenda->id;?>_enviado">
							<input
							<?
							if($varAgenda->ativo==0){echo " disabled ";}
							?>
							type="checkbox" name="check" id="check" onclick="javascript:enviado(<?=$varAgenda->id?>, this.checked);" <?if($varAgenda->rel_enviado==1){echo " checked ";}?>></input>
							<font style="font-size:8px"><br><?=$varAgenda->rel_enviado_quem;?><br><?=$varAgenda->rel_enviado_data;?></font>

						</span>
					</center>
				</td>
				<!--<td style="vertical-align: middle;text-align:center">
					<center>
						<span name="<?=$varAgenda->id;?>_assinado" id="<?=$varAgenda->id;?>_assinado">
							<input
							<?
							if($varAgenda->ativo==0){echo " disabled ";}
							?>
							type="checkbox" name="check" id="check" onclick="javascript:assinado(<?=$varAgenda->id?>, this.checked);" <?if($varAgenda->rel_assinado==1){echo " checked ";}?>></input>
							<font style="font-size:8px"><br><?=$varAgenda->rel_assinado_quem;?><br><?=$varAgenda->rel_assinado_data;?></font>

						</span>
					</center>
				</td>-->
				<td width="*" style="vertical-align: middle;text-align:right">
					<a href="agendamentos.php?agendamento=<?=$varAgenda->id;?>" class="btn dark btn-xs btn-outline sbold uppercase" title="Visualizar visita"><i class="fa fa-address-book-o"></i></a>

					<?
						$botoes = array(
							"btnPlano"		=> 0,
							"btnCarta" 		=> 0,
							"btnBranco" 	=> 0,
							"btnFup"   		=> 0,
							"btnPreenchido" => 0,
							"btnFinal"		=> 0,
							"btnRelCompleto"=> 0,
							"btnRelSimp"	=> 0,
							"btnCheck"		=> 0,
							"btnComentarios"=> 0,
							"btnDatas"		=> 0,
							"btnResumo"		=> 0
						);
						if((strtoupper($varAgenda->tipo)=="VISITA INICIAL")||(strtoupper($varAgenda->tipo)=="INICIAL")){
							$botoes['btnPlano'] = 1;
						}
						if(strtoupper($varAgenda->tipo)=="RELATÓRIO CONSOLIDADO DO ESTUDO"){
							$botoes['btnResumo'] = 1;
						}
						if($varAgenda->tipo=="Visita Regular"){
							$botoes['btnDatas'] = 1;
						}
						if(($varAgenda->tipo=="Visita Inicial")||($varAgenda->tipo=="Visita Regular")||($varAgenda->tipo=="Inicial")){
							$botoes["btnCarta"] 		= 1;
							$botoes["btnBranco"] 		= 1;
							$botoes["btnFup"] 			= 1;
							$botoes["btnPreenchido"] 	= 1;
							$botoes["btnCheck"] 		= 0;
							$botoes['btnComentarios']	= 0;
						}
						if(($varAgenda->tipo=="Visita Final")||($varAgenda->tipo=="[PC] - VISITA DE ENCERRAMENTO")){
							$botoes["btnCarta"] 		= 1;
							$botoes["btnBranco"] 		= 1;
							$botoes["btnFup"] 			= 1;
							$botoes["btnPreenchido"] 	= 1;
							$botoes["btnFinal"] 		= 1;
							$botoes["btnCheck"] 		= 0;
							$botoes['btnComentarios']	= 0;
						}
						if($botoes['btnPlano']==1){
							$retorno = "rep_mon_plano.php";
							$rand = rand(rand(98,883787),3696985);
							?>

							<div class="btn-group dropup">
								<button type="button" title="Plano de Monitoria" class="btn dark btn-xs btn-outline"><i class="fa fa-handshake-o"></i></button>
								<button type="button" title="Plano de Monitoria" class="btn dark btn-xs btn-outline dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
									<i class="fa fa-angle-down"></i>
								</button>
								<ul class="dropdown-menu" role="menu">
									<li>
										<a href="agenda_atividades_historico.php?id=<?=$varAgenda->id;?>"> Histórico de versões</a>
									</li>
									<li>
										<a href="modal_digita_data.php?rand=<?=$rand;?>&id=<?=$varAgenda->id;?>&volta=<?=$retorno;?>"> Imprimir </a>
									</li>
								</ul>
							</div>&ensp;



							<?php
						}
						if($botoes['btnCarta']==1){	//Carta de acompanhamento
							$retorno = "rep_mon_confirmacao.php";
							$rand = rand(rand(98,883787),3696985);
							?>
							<div class="btn-group dropup">
								<button type="button" title="Agenda de monitoria" class="btn dark btn-xs btn-outline"><i class="fa fa-print"></i></button>
								<button type="button" title="Agenda de monitoria" class="btn dark btn-xs btn-outline dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
									<i class="fa fa-angle-down"></i>
								</button>
								<ul class="dropdown-menu" role="menu">
									<li>
										<a href="agenda_atividades.php?id=<?=$varAgenda->id;?>"> Atividades</a>
									</li>
									<li>
										<a href="modal_digita_data.php?rand=<?=$rand;?>&id=<?=$varAgenda->id;?>&volta=<?=$retorno;?>"> Imprimir </a>
									</li>
								</ul>
							</div>&ensp;
							<?php
						}
						if($botoes['btnBranco']==1){
							?>
							<a href="javascript:imprimir_branco(<?=$varAgenda->id;?>);" class="btn dark btn-xs btn-outline sbold uppercase" title="Imprimir relatório em Branco"><i class="fa fa-pencil-square-o"></i></a>
							<?
						}
						if($botoes['btnFup']==1){
								//Carta de acompanhamento FUP
								$retorno = "rep_mon_fup.php";
								$rand = rand(rand(98,883787),3696985);
							?>
							<a
								title="Carta de Acompanhamento (FUP)"
								type="button"
								class="btn dark btn-xs btn-outline sbold uppercase" aria-label="Left Align"
								href="modal_digita_data.php?rand=<?=$rand;?>&id=<?=$varAgenda->id;?>&volta=<?=$retorno;?>"><i class="fa fa-arrow-circle-right"></i>
							</a>
							<?php
						}
						if($botoes['btnPreenchido']==1){
								//Relatório preenchido
								$retorno = "rep_mon_preenchido.php";
								$rand = rand(rand(98,883787),3696985);
							?>
							<a
								title="Imprimir Relatório preenchido"
								type="button"
								class="btn dark btn-xs btn-outline sbold uppercase" aria-label="Left Align"
								href="modal_digita_data.php?rand=<?=$rand;?>&id=<?=$varAgenda->id;?>&volta=<?=$retorno;?>"><i class="fa fa-pencil-square"></i>
							</a>
						<?
						}
						if($botoes['btnFinal']==1){
								//Relatório Final FINALMESMO
								$retorno = "rep_mon_final.php";
								$rand = rand(rand(98,883787),3696985);
							?>
							<a
								title="Imprimir Relatório FINAL"
								type="button"
								class="btn dark btn-xs btn-outline sbold uppercase"
								aria-label="Left Align"
								href="modal_digita_data.php?rand=<?=$rand;?>&id=<?=$varAgenda->id;?>&volta=<?=$retorno;?>"><i class="fa fa-building"></i>
							</a>
							<?
						}
						if($botoes['btnRelCompleto']==1){
							//Relatório preenchido
							$retorno = "rep_prot_completo.php";
							$rand = rand(rand(98,883787),3696985);
							?>
							<a title="Relatório completo"
									type="button"
									class="btn dark btn-xs btn-outline sbold uppercase"
									aria-label="Left Align"
									href="modal_digita_data.php?rand=<?=$rand;?>&id=<?=$varAgenda->id;?>&volta=<?=$retorno;?>"><i class="fa fa-pencil-square"></i>
							</a>
							<?php
						}
						if($botoes['btnRelSimp']==1){
							//Relatório preenchido
							$retorno = "rep_prot_pendencias.php";
							$rand = rand(rand(98,883787),3696985);
							?>
							<a title="Imprimir Relatório Simplificado"
										type="button"
										class="btn dark btn-xs btn-outline sbold uppercase"
										aria-label="Left Align"
										href="modal_digita_data.php?rand=<?=$rand;?>&id=<?=$varAgenda->id;?>&volta=<?=$retorno;?>"><i class="fa fa-bars"></i>
								</a>
							<?php
						}
						if($botoes['btnCheck']==1){
							?>
							<div class="btn-group dropup">
								<button type="button"
										title="Checklist desse agendamento"
										class="btn dark btn-xs btn-outline sbold uppercase"
										data-toggle="modal"
										data-target="#modalHistorico"
										href="modal_agenda_checklist.php?id=<?=$varAgenda->id;?>"
										alt="CHECK-LIST">
									<i class="fa fa-list-ol" alt="CHECK-LIST"></i>
								</button>
							</div>
							<?php
						}
						if($botoes['btnComentarios']==1){
							?>
							<div class="btn-group dropup">
								<button type="button"
										title="Coment?rios do revisor"
										class="btn dark btn-xs btn-outline sbold uppercase"
										data-toggle="modal"
										data-target="#modalComentarios"
										href="modal_agenda_comentarios.php?id=<?=$varAgenda->id;?>"
										alt="CHECK-LIST">
										<i class="fa fa-id-card-o" aria-hidden="true"></i>
								</button>
							</div>
							<?php
						}
						if($botoes['btnDatas']==1){
							?>
							<a
								href="agenda_datas.php?rand=<?=$randomico;?>&id=<?=$varAgenda->id;?>"
								class="btn dark btn-xs btn-outline sbold uppercase"
								title="Datas de in?cio e fim"
								data-toggle="modal"
								data-target="#myModal">
								<i class="fa fa-calendar"></i>
							</a>
							<?
						}
						if($botoes['btnResumo']==1){
							?>
							<div class="btn-group dropup">
								<a 	title="Imprimir relatório consolidado"
									type="button"
									class="btn dark btn-xs btn-outline sbold uppercase"
									aria-label="Left Align"
									href="agenda_resumo.php?id=<?=$varAgenda->id;?>" target="_blank">
										<i class="fa fa-print"></i>
								</a>
							</div>
							<?php
						}
						if($varAgenda->ativo==1){
							?>
							<a title="Gerar nova Versão dessa visita"
									type="button"
									class="btn dark btn-xs btn-outline sbold uppercase"
									aria-label="Left Align"
									href="javascript: novaVersao(<?=$varAgenda->id;?>);"><i class="fa fa-database" aria-hidden="true" alt="Nova versão"></i>
							</a>
							<div class="btn-group dropup">
								<button type="button" class="btn btn-xs btn-circle-right dropdown-toggle red" data-toggle="dropdown">
									<i class="fa fa-envelope-open-o"></i>
									<i class="fa fa-angle-down"></i>
								</button>
								<ul class="dropdown-menu dropup">
									<li>
										<a href="javascript:EnviaEmailRevisor(<?=$varAgenda->id;?>)"><i class="fa fa-envelope-open-o"></i> Alertar Revisor </a>
									</li>
									<li>
										<a href="javascript:EnviaEmailTodos(<?=$varAgenda->id;?>)"><i class="fa fa-envelope-open-o"></i> Revisão completa </a>
									</li>
								</ul>
							</div>
							<?php
						}
					?>
				</td>
			</tr>
		<?
		}
		?>
			<!--<tr style="font-size: 1pt !important">
				<td style="vertical-align: middle;text-align:center"></td>
				<td style="vertical-align: middle;" colspan="12"><font size="1pt">RELATÓRIO CONSOLIDADO DO ESTUDO</font></td>

				<td width="*" style="vertical-align: middle;text-align:right">
					<a 	title="Configurar"
						type="button"
						data-toggle="modal"
						data-target="#myModal"
						class="btn dark btn-xs btn-outline sbold uppercase"
						aria-label="Left Align"
						href="agenda_configuracao.php?rand=<?=$randomico;?>&id=<?=$varAgenda->id;?>">
							<i class="fa fa-bars"></i>
					</a>

				</td>
			</tr>-->
		<?

	}
