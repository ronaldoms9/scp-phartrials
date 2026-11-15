<?php
	include_once __DIR__."/../seguranca.php";
	include_once __DIR__."/../class/crf.class.php";
	include_once __DIR__."/../class/centro.class.php";
	include_once __DIR__."/../class/participante.class.php";
	include_once __DIR__."/../class/seguranca.class.php";
	include_once __DIR__."/../class/log.class.php";
	include_once __DIR__."/../funcoes.php";
	include_once __DIR__."/../class/usuario.class.php";

	$varSeguranca = new AcessoFormulario(basename($_SERVER['PHP_SELF']));
	$varUsuario   = new Usuario($_SESSION['user_id']);

	$varConexao = new Conexao();

	switch($_GET['act']){
		case "APAGA_CAMPO":
			$campo  = $_POST['idCampo'];
			$motivo = $_POST['motivo'];
			$usuario= $_SESSION['user_id'];
			$varCampo = new Campo($campo);

			if(trim($motivo)!=""){
				$query = "update crf_entrada_campos set log_usuario = $usuario, motivo_alteracao = '$motivo', valor = '' where id = " . $campo;
				$varConexao->pesquisa($query);
				echo $varCampo->form_id;
			}else{
				echo "BRANCO";
			}
		break;
		case "DELETA_COMENTARIO":
			$query = "delete from crf_entrada_campos_comentarios where id = " . $_POST['varId'];
			$varConexao->pesquisa($query);
		break;
		case "FECHA_QUERY_QUESTIONAMENTOS":
			$id    = $_POST['varId'];
			$query = "update crf_entrada_campos_query
						set   status              = 3,
							datahora_fechamento = '".date('YmdHi')."',
							usuario_fechamento  = '".$_SESSION['user_id']."'
						where id                  = " . $id;
			$conexao->pesquisa($query);
			echo "Query finalizada com sucesso!";
		break;
		case "QUERY_RETORNA_STATUS":
			$id    = $_POST['varId'];
			$query = "select status from crf_entrada_campos_query where id = " . $id;
			//echo $query;
			$resSt = $conexao->pesquisa($query);
			$rowSt = mysqli_fetch_assoc($resSt);
			if($rowSt['status']=="1"){
				echo "Aberto";
			}
			if($rowSt['status']=="2"){
				echo "Respondido";
			}
			if($rowSt['status']=="3"){
				echo "Fechado/Concluído";
			}
		break;
	}

	if($_GET['act']=="FORM_FILES_SHOW"){
		$query = "select * from crf_entrada_forms_arquivos where formulario = " . $_SESSION['formulario_upload'] . " order by datahora_lis, id";
		$resArquivos = $varConexao->pesquisa($query);
		if(mysqli_num_rows($resArquivos)==0){
			echo "<tr>
				<td colspan='4'><span class='linha_files'>Nenhum arquivo encontrado para esse formulário</span></td>
			</tr>
			";
		}else{
			while($rowArq = mysqli_fetch_assoc($resArquivos)){
				?>
				<tr>
					<td><span class='linha_files'><?=$rowArq['nome'];?></span></td>
					<td><span class='linha_files'><?=tamanhoArquivo($rowArq['tamanho']);?></span></td>
					<td><span class='linha_files'><?=$rowArq['datahora'];?></span></td>
					<td><span class='linha_files'><?=$rowArq['usuario_nome'];?></span></td>
					<td>
						<center>
							<a href="arq_action.php?act=DF&acw=<?=$rowArq['id']."&aci=".$rowArq['id'];?>"
								data-toggle="tooltip"
								class="btn btn-xs  btn-outline btn-circle blue btn-sm blue"
								title="Download">
								<i class="fa fa-download"></i></a>
						</center>
					</td>
				</tr>
				<?php
			}
		}
	}
	if($_GET['act']=="RAND_PRE"){
		$varCentro = new CentroPesquisa($_SESSION['crfCentro']);
		$varParticipantePessoa = new Participante($varCentro->id, $_SESSION['crfParticipante']);
		$varTCLE	  = $varParticipantePessoa->retornaTCLE("TRI_TCLE_TERMO_DATA");
		if($varTCLE==""){
			$retorno = array('ret' => '1', 'mensagem' => utf8_encode('Campo [DATA DO TCLE] nao preenchido. '));
			echo json_encode($retorno);
			return;
		}
		/*if($_SESSION['user_tipo']!=14){
			$retorno = array('ret' => '1', 'mensagem' => utf8_encode('O usuário atual não possui autorização para essa ação!'));
			echo json_encode($retorno);
			return;
		}*/
		$retorno = array('ret' => '0', 'mensagem' => utf8_encode('OK'));
		echo json_encode($retorno);
	}
	if($_GET['act']=="NOVA_QUERY"){
		$texto  = $_POST['varTexto'];
		$id		= $_POST['varId'];
		$varCampo 		= new Campo($id);
		$varFormulario 	= new Formulario($varCampo->form_id);
		$query	= "select max(id)+1 as ult from crf_entrada_campos_query";
		$res	= $conexao->pesquisa($query);
		$row	= mysqli_fetch_assoc($res);
		$idQuery = $row['ult'];
		if($idQuery<1){$idQuery = 1;}
		$conexao->pesquisa("insert into crf_entrada_campos_query (centro, campo_id, datahora_abertura, usuario_abertura, status) values ('".$varFormulario->centro."', '".$varCampo->id."', '".DataHoraAtualToLIS()."', '".$_SESSION['user_id']."', '1')");
		$conexao->pesquisa("insert into crf_entrada_campos_query_questionamentos (usuario_id, query_id, datahora, tipo, mensagem, log_usuario) values ( ".$_SESSION['user_id'].", '$idQuery', '".DataHoraAtualToLIS()."', '1', '".utf8_decode($texto)."', '".$_SESSION['user_id']."') ");
		echo "OK";
	}
	if($_GET['act']=="FECHA_QUERY"){
		$id		= $_POST['varId'];
		$varQuery = new Query($id);
		$conexao->pesquisa("update
								crf_entrada_campos_query
									set status = 3,
										datahora_fechamento = '".date('YmdHi')."',
											usuario_fechamento = '".$_SESSION['user_id']."'
												where id = " . $id);
		echo $varQuery->campo_id;
	}
	if($_GET['act']=="QUERY_RESPOSTA"){
		$texto  = $_POST['varTexto'];
		$id		= $_POST['varId'];

		$varQueryResposta = new QueryItem($id);
		$varQuery = new Query($varQueryResposta->query_id);

		//Aqui insiro a resposta na tabela crf_entrada_campos_query_questionamentos
		$query	= "insert into crf_entrada_campos_query_questionamentos (usuario_id, query_id, datahora, tipo, mensagem, log_usuario) ";
		$query .= " values ( ".$_SESSION['user_id'].", '$id', '".DataHoraAtualToLIS()."', '2', '".utf8_decode($texto)."', '".$_SESSION['user_id']."') ";
		$conexao->pesquisa($query);
		//Aqui sinalizo a tabela de query que já tem resposta
		$varQuery->setTemResposta();
		echo "OK";
	}
	if($_GET['act']=="CARREGA_QUERIES"){
		$campoID = $_POST['varId'];
		$query	= "SELECT
					crf_entrada_campos_query.id
					from crf_entrada_campos_query
						where campo_id = $campoID
						order by id
		";
		$res	= $conexao->pesquisa($query);
		if(mysqli_num_rows($res)==0){
			echo "<h6>Nenhum questionamento cadastrado....</h6>";
		}else{
			echo "<div id='divQueries'>";
			$indice = 1;
			while($row_q = mysqli_fetch_assoc($res)){
				$varQuery = new Query($row_q['id']);
				?>
					<table width="100%" style="border-collapse: collapse; border: 1px solid #DCDCDC;">

							<tr>
								<td class="headerTh" width="8%"><center><?="#".$indice;?></center></td>
								<td class="headerTh" width="20%"><center><?=listoDATA($varQuery->datahora_abertura);?></center></td>
								<td class="headerTh" width="*"><?=$varQuery->classUsuarioAbertura->nome;?></td>
								<td class="headerTh" width="20%" style="text-align:right">[
									<?php
										if($varQuery->status=="1"){
											echo "PENDENTE";
										}
										if($varQuery->status=="2"){
											echo "RESPONDIDO";
										}
										if($varQuery->status=="3"){
											echo "CONCLUÍDO";
										}
									?>]
								</td>
								<?php
									if( (($varSeguranca->monitora=="ON")||($varSeguranca->fechaquery=="ON")||($varSeguranca->fechaquery=="1"))&&(($varQuery->status=="1")||($varQuery->status=="2"))){
									?>
										<td class="headerTh" width="10%" style="text-align:left">

											<div class="btn-group dropleft">
												<a class="btn btn-default dropdown-toggle" data-toggle="dropdown" href="javascript:;"><i class="fa fa-angle-down"></i></a>
												<ul class="dropdown-menu">
													<li>
														<a href="javascript:fechaQuery(<?=$varQuery->id;?>);"> Encerrar Query</a>
													</li>
												</ul>
											</div>
										</td>
									<?php
									}
								?>
							</tr>
							<tr>
								<td colspan="5" style="padding-top: 8px; padding-bottom: 15px;">
									<?php
										$query = "SELECT
													crf_entrada_campos_query_questionamentos.id
														FROM crf_entrada_campos_query_questionamentos
															where tipo = 1
																and query_id = " . $varQuery->id;

										$res_item = $conexao->pesquisa($query);
										while($rowItem = mysqli_fetch_assoc($res_item)){
											$varQueryItem = new QueryItem($rowItem['id']);
											?>
												<table border="0" width="100%">
													<tbody>
														<tr>
															<td width="28%">
																<center>
																	<img class="item-pic rounded" src="imagens/ico_avatar.png" width="25">
																	<br>
																	<a href="" class="item-name primary-link"><span class="respUsuario"><?=$varQueryItem->classUsuario->nome;?></span></a>
																	<br>
																	<span style="font-size:11px"><?=listoDATAHORA($varQueryItem->datahora);?></span>
																</center>
															</td>
															<td width="*">
																<span class="respMensagem"><?=$varQueryItem->mensagem;?></span>
															</td>
															<td width="50">
																<center>
																	<?php
																		if( ($varQuery->status!=3)&&($varQueryItem->temResposta()==false)){
																			?>
																			<button class="btn green btn-sm" type="button" data-toggle="dropdown" onclick="novaResposta(<?=$varQueryItem->id;?>, <?=$varQueryItem->query_id;?>);"><i class="fa fa-reply" aria-hidden="true"></i> Responder</button>
																			<?php
																		}
																	?>
																</center>
															</td>
														</tr>
													</tbody>
												</table>
												<?php
												if($varQueryItem->temResposta()==false){
												?>
													<div class="row" id="divResp_<?=$varQueryItem->id;?>" style="display: none">
														<div class="row">
															<hr>
															<div class="col-md-10"  style="padding-left: 80px !important">
																<textarea rows="3" class="form-control" id="form_texto_<?=$varQueryItem->id;?>"></textarea>
															</div>
															<div class="col-md-2"><br><br>
																<button
																	class="btn green btn-sm"
																	type="button"
																	data-toggle="dropdown"
																	onclick="salvaResposta(<?=$varQueryItem->id;?>, <?=$campoID;?>);"><i class="fa fa-floppy-o" ></i> Salvar</button>
															</div>
														</div>
														<br>
													</div>
												<?php
												}
												?>
											<?php
											if($varQueryItem->temResposta()==true){		//tem respostas.....
												$query   = "select id from crf_entrada_campos_query_questionamentos where query_id = " . $varQueryItem->id . " and tipo = 2 order by id";
												$resResp = $conexao->pesquisa($query);
												$indResp = 0;
												while($rowResp = mysqli_fetch_assoc($resResp)){
													$varQueryItemResp = new QueryItem($rowResp['id']);
													if(mysqli_num_rows($resResp)==($indResp+1)){$eUltimo=true;}else{$eUltimo = false;}

													?>
														<table width="100%">
															<tbody>
																<tr>
																	<td width="28%" style="padding-top: 10px; padding-bottom: 10px; border-top: 1px solid #C0C0C0">
																		<center>
																			<img class="item-pic rounded" src="imagens/ico_avatar.png" width="25">
																			<br>
																			<a href="" class="item-name primary-link"><span class="respUsuario"><?=$varQueryItemResp->classUsuario->nome;?></span></a>
																			<br>
																			<span style="font-size:11px"><?=listoDATAHORA($varQueryItemResp->datahora);?></span>
																		</center>
																	</td>
																	<td width="*" style="padding-top: 10px; padding-bottom: 10px; border-top: 1px solid #C0C0C0">
																		<span class="respMensagem"><?=$varQueryItemResp->mensagem;?></span>
																	</td>
																	<td width="50" style="padding-top: 10px; padding-bottom: 10px; border-top: 1px solid #C0C0C0">
																		<center>
																			<?php
																				/*if( ($varQueryItemResp->status==0)&&($eUltimo==true)){
																					?>
																					<button class="btn green btn-sm" type="button" data-toggle="dropdown" onclick="novaResposta(<?=$varQueryItem->id;?>, <?=$varQueryItem->query_id;?>);"><i class="fa fa-reply" aria-hidden="true"></i> Responder</button>
																					<?php
																				}*/
																			?>
																		</center>
																	</td>
																</tr>
															</tbody>
														</table>
														<div class="row" id="divResp_<?=$varQueryItem->id;?>" style="display: none">
															<div class="row">
																<hr>
																<div class="col-md-10"  style="padding-left: 80px !important">
																	<textarea rows="3" class="form-control" id="form_texto_<?=$varQueryItem->id;?>"></textarea>
																</div>
																<div class="col-md-2"><br><br>
																	<button
																		class="btn green btn-sm"
																		type="button"
																		data-toggle="dropdown"
																		onclick="salvaResposta(<?=$varQueryItem->id;?>, <?=$campoID;?>);"><i class="fa fa-floppy-o" ></i> Salvar</button>
																</div>
															</div>
															<br>
														</div>
													<?php
													$indResp++;
												}
											}
										}
									?>
								</td>
							</tr>
						</table>
						<div style="background-color:white; height:4px"></div>
				<?php
				$indice++;
			}
			echo "</div>";
		}
	}
	if($_GET['act']=="ADD_FIELDS"){
		$varCampo = new Campo($_POST['varCampo']);
		$qtdJson  = $varCampo->qtd_json + 1;
		$query    = "update crf_entrada_campos set qtd_json = $qtdJson where id = " . $varCampo->id;
		$conexao->pesquisa($query);
		//$qtdJson  = intval($qtdJson) - 1;
		//Campos a inserir
		$newCampos = trim($varCampo->TipoCampo->selecao_valores);
		$newCampos = explode(",", $newCampos);
		//$ordemInicial = intval($ordemInicial) +count($newCampos)+2;
		if($varCampo->qtd_json==0){
			$ordemInicial = $varCampo->ordem + (count($newCampos)*$qtdJson);
		}else{
			$ordemInicial = $varCampo->ordem + (count($newCampos)*$qtdJson)+$varCampo->qtd_json;
		}
		$query  = "select id, ordem from crf_entrada_campos where form_id = " . $varCampo->form_id . " and ordem > " . $ordemInicial;
		$resOrd = $conexao->pesquisa($query);
		while($rowOrd = mysqli_fetch_assoc($resOrd)){
			$ordem = $rowOrd['ordem'] + ((count($newCampos)*$qtdJson)+1);
			$query = "update crf_entrada_campos set ordem = " . $ordem . " where id = " . $rowOrd['id'];
			$res   = $conexao->pesquisa($query);
		}
		$countI = 1;
		$query 		= "insert into crf_entrada_campos
								(form_id, campo_id, log_usuario, projeto, ordem)
								values
								('".$varCampo->form_id."',
								'0',
								'".$_SESSION['user_id']."',
								'".$_SESSION['projeto']."',
								'".($ordemInicial+$countI)."');";
		$conexao->pesquisa($query);
		$countI++;
		foreach ($newCampos as $nome){
			$query 		= "select * from crf_forms_campos where nome = '".$nome."';";

			$resCampo 	= $conexao->pesquisa($query);
			$rowCampo	= mysqli_fetch_assoc($resCampo);

			$query 		= "insert into crf_entrada_campos
								(form_id, campo_id, log_usuario, projeto, ordem)
								values
								('".$varCampo->form_id."',
								'".$rowCampo['id']."',
								'".$_SESSION['user_id']."',
								'".$_SESSION['projeto']."',
								'".($ordemInicial+$countI)."');";
			$conexao->pesquisa($query);
			$countI++;
		}
		echo $varCampo->form_id;
	}
	if($_GET['act']=='ADD_SUB_PAGINA'){
		$pagina = $_POST['varPagina'];
		$queryF 		= "select * from crf_forms where id = '$pagina'
							order by ordem";
		$resF			= $conexao->pesquisa($queryF);
		$rowF			= mysqli_fetch_assoc($resF);
		$idReferencia 	= $rowF['id'];
		$varOrdem		= $rowF['ordem'];
		$varPagina 		= $rowF['pagina'];
		//Pegar o ID da Pagina que irá ser criada
		$query 			=	"select * from crf_forms where nome = '$varPagina'";
		$resPagina		=	$conexao->pesquisa($query);
		$rowPagina		=	mysqli_fetch_assoc($resPagina);
		$idPagina		=	$rowPagina['id'];
		//Verificar qts páginas desse tipo já se tem
		$query  		= "select max(ordem) as qtd from crf_entrada_forms
							where projeto = " . $_SESSION['projeto'] . "
								and	form_id 	= $idPagina
								and centro  	= " . $_SESSION['crfCentro'] . "
								and paciente 	= " . $_SESSION['crfParticipante'];
		$resMax 		= $conexao->pesquisa($query);
		$rowMax			= mysqli_fetch_assoc($resMax);
		$qtdAnterior 	= $rowMax['qtd']; if($qtdAnterior==""){$qtdAnterior=0;}
		$qtdProximo		= $qtdAnterior+1;
		$queryF = "select * from crf_forms
					where id = $idPagina
								order by ordem";
		$resF   = $conexao->pesquisa($queryF);

		$id_usuario      = $_SESSION['user_id'];
		$centro          = $_SESSION['crfCentro'];
		$randPaciente    = $_SESSION['crfParticipante'];
		$varParticipante = new Participante($centro, $randPaciente);

		while($rowF = mysqli_fetch_assoc($resF)){
			$query = "insert into crf_entrada_forms (log_usuario,
													paciente,
													form_id,
													centro,
													projeto, ordem) values
													('$id_usuario',
													'$randPaciente',
													'$idPagina',
													'$centro',
													'1',
													$qtdProximo
													);";
			$conexao->pesquisa($query);
			//Pego o FORM criado
			$query = "select id from crf_entrada_forms
						where  paciente = '".$randPaciente."'
							and centro = '$centro'
							and ordem = '$qtdProximo'
							and form_id = '".$rowF['id']."'";
			$res   = $conexao->pesquisa($query);
			$row   = mysqli_fetch_assoc($res);
			$query = "select * from crf_forms_campos where formulario = " . $rowF['id'];
			echo $row['id'];
			$resC  = $conexao->pesquisa($query);
			while($rowC = mysqli_fetch_assoc($resC)){
				$query = "insert into crf_entrada_campos (form_id, campo_id, monitoria_status, log_usuario, log_usuario_dupla, ordem)
							values
								('".$row['id']."', '".$rowC['id']."', 0, '$id_usuario', 0, '".$rowC['ordem']."');";
				$conexao->pesquisa($query);
			}
		}
		echo $rowF['id'];
	}
	if($_GET['act']=="ADD_COMENTARIO"){
		$conexao = new Conexao();
		$varId 			= $_POST['idCampo'];
		$varComentario 	= utf8_decode($_POST['comentario']);

		$query 		= "insert into crf_entrada_campos_comentarios (campo_id, comentario, data_hora, data_hora_lis, log_usuario) values ($varId, '$varComentario', '".date("d/m/Y H:i")."', '".date("YmdHi")."', '".$_SESSION['user_id']."');";
		$conexao->pesquisa($query);
		echo $varId;
	}
	if($_GET['act']=="VERIFICA_SENHA"){
		$ret = "ERRADA";
		$query 	= "select id from usu_usuario where id = ".$_SESSION['user_id'] ." and senha = md5('".$_POST['varSenha']."');";
		$res 	= $conexao->pesquisa($query);
		if(mysqli_num_rows($res)>0){$ret ="CERTA";}else{$ret = "ERRADA";}
		echo $ret;
	}
	if($_GET['act']=="FORM_EXIBE"){
		if(!isset($_SESSION)){  session_start();    }
		if($_SESSION['valido']<1){echo "<script>window.location.replace('expirado.php');</script>";};

		$centro 			= $_SESSION['crfCentro'];
		$participante		= $_SESSION['crfParticipante'];
		$varParticipante    = new Participante($centro, $participante);
		$varCRFParticipante = new CRFPaciente($_SESSION['projeto'], $centro, $participante);
		$formularioP		= $_POST['varForm'];
		$sexoParticipante  = $varParticipante->sexo;
		//
		?>
			<table width="100%" class="table table-hover table-condensed">
				<tbody>
					<?php


						foreach($varCRFParticipante->CRFs as $CRF){
							if($CRF->naoVeFormulario($_SESSION['tipo_usuario'])==false){continue;}
							if($CRF->tipoForm->sexo!=""){
								if($CRF->tipoForm->sexo!=$sexoParticipante){continue;}
							}
							echo $CRF->exibeNode($formularioP);
						}
					?>
				</tbody>
			</table>
		<?php
	}
	if($_GET['act']=='FORM_CLICOU'){
		if(!isset($_SESSION)){  session_start();    }
		if($_SESSION['valido']<1){echo "<script>window.location.replace('expirado.php');</script>";};

		$varConexao = new Conexao();
		$idForm = $_POST['varForm'];
		$varFormulario = new Formulario($idForm);

		//Incluir um registo no audittrail de quem acessou, qual formulario, qual participante e quando o formulário em questão
		$query = "insert into log_alteracoes (
													tabela,
													id_tabela,
													campo,
													id_campo,
													acao,
													usuario,
													data_hora,
													antigo,
													novo,
													usuario_nome,
													motivo_alteracao,
													data_hora_lis
												)
												values
												(
													'ACESSO_FORMULARIO',
													'".$varFormulario->id."',
													'".$varFormulario->getNomeFormulario()."',
													'".$varFormulario->id."',
													'ACESSO',
													'".$_SESSION['user_id']."',
													'".date("d/m/Y H:i")."',
													'',
													'',
													'".$_SESSION['user_nome']."',
													'".date("YmdHi")."',
													''
												);";
		$varConexao->pesquisa($query);
		echo $varFormulario->exibeNode($idForm);
	}
	if($_GET['act']=="NOVA_QUERYDM"){
		$texto  = $_POST['varTexto'];
		$usuario= $_POST['varUsuario'];
		$regra  = $_POST['varRegra'];

		$id		= $_POST['varId'];
		$varCampo 		= new Campo($id);
		$varFormulario 	= new Formulario($varCampo->form_id);
		$query	= "select max(id)+1 as ult from crf_entrada_campos_query";
		$res	= $conexao->pesquisa($query);
		$row	= mysqli_fetch_assoc($res);
		$idQuery = $row['ult'];
		if($idQuery<1){$idQuery = 1;}
		$conexao->pesquisa("insert into crf_entrada_campos_query (centro, campo_id, datahora_abertura, usuario_abertura, status) values ('".$varFormulario->centro."', '".$varCampo->id."', '".DataHoraAtualToLIS()."', '".$usuario."', '1')");
		$conexao->pesquisa("insert into crf_entrada_campos_query_questionamentos (usuario_id, query_id, datahora, tipo, mensagem, log_usuario) values ( ".$usuario.", '$idQuery', '".DataHoraAtualToLIS()."', '1', '".utf8_decode($texto)."', '".$usuario."') ");


		//Gerar query DM
		if($regra=="DATA_INVALIDA"){$msg = "Campo com o formato da data incorreto!";}
        if($regra=="DATA_COM_00"){$msg = "Campo com o a data com algum 00. Por favor, rever se é necessário abrir query!";}
        if($regra=="DATA_TCLE_ANTES"){$msg = "Data com valor ANTERIOR a assinatura da TCLE! [$tcle]";}
        if($regra=="DATA_TCLE_DEPOIS"){$msg = "Data com valor POSTERIOR a assinatura da TCLE!  [$tcle]";}
        if($regra=="TCLE_SEM_TCLE"){$msg = "Participante sem TCLE!";}
        if($regra=="CAMPO_OBRIGATORIO"){$msg = "Campo obrigatório não preenchido";}
        if($regra=="EA_FINAL_MENOR_INICIO"){$msg = "Encerramento do EA menor que o início";}

		$query      = "insert into dm_query (campo_id, tipo, valor, msg_query, datageracao, datageracaolis, status, campo_tem_query, form_tipo, corte)
		values
		('$id',
		'$regra',
		'$varCampo->valor',
		'$msg',
		'".date("d/m/Y H:i")."',
		'".date("Ymd")."',
		'5',
		'".$varCampo->getTemQuery()."',
		'".$varCampo->TipoCampo->formulario."',
		'$corte'
		)
		";
		$varConexao->pesquisa($query);


		//Tirar o flag de MONITORADO/SDV
		if($varCampo->monitoria_status==1){
			$tinha_monitoria = 1;
			$query = "update crf_entrada_campos set log_usuario = $usuario, monitoria_status = 0, tinha_monitoria = 1 where id = " . $varCampo->id;
			$varConexao->pesquisa($query);
		}



		echo "OK";
	}
	if($_GET['act']=='MONITORADM'){
		$varCampo = new Campo($_POST['idCampo']);
		if($varCampo->monitorado_dm==0){ //Se não estiver monitorado, monitorar
			//Verifico se existe questionamento aberto para o campo em questão

				$varCampo->monitorado_dm = 1;
				$varCampo->monitoraDM();
				$volta = 'OK|'.traduzir('Item marcado como monitorado com sucesso', $_SESSION['lang']).'|<i id="divMonitoraDM'.$campo.'" name="divMonitoraDM'.$campo.'" class="fa fa-check-square-o fa-sm" title="'.traduzir('Monitorado', $_SESSION['lang']).'"></i>|1|'.$_SESSION['seg_edita'];

		}else{	//Já estava monitorado, vou tirar o flag de monitorado
			$varCampo->monitorado_dm = 0;
			$varCampo->monitoraDM();
			$volta = 'OK|'.traduzir('Item desmarcado como monitorado com sucesso', $_SESSION['lang']).'|<i id="divMonitoraDM'.$campo.'" name="divMonitoraDM'.$campo.'" class="fa fa-square-o fa-sm" title="'.traduzir('Nao Monitorado', $_SESSION['lang']).'"></i>|0|'.$_SESSION['seg_edita'];
		}
		echo $volta;
	}
	if($_GET['act']=='MONITORA'){
		$varCampo = new Campo($_POST['idCampo']);
		if($varCampo->monitoria_status==0){ //Se não estiver monitorado, monitorar
			//Verifico se existe questionamento aberto para o campo em questão
			if($varCampo->getQueriesAbertas()==0){
				$varCampo->monitoria_status = 1;
				$varCampo->monitora();
				$volta = 'OK|'.traduzir('Item marcado como monitorado com sucesso', $_SESSION['lang']).'|<i id="divMonitora'.$campo.'" name="divMonitora'.$campo.'" class="fa fa-check-square-o fa-sm" title="'.traduzir('Monitorado', $_SESSION['lang']).'"></i>|1|'.$_SESSION['seg_edita'];
			}else{
				//Nada pendente, bora mudar o status de monitorado
				$volta = 'FALHA|'.traduzir('ATENÇÃO! Existem QUESTIONAMENTOS não finalizados para esse campo. Monitoria NÃO realizada', $_SESSION['lang']).'.|<i id="divMonitora'.$campo.'" name="divMonitora'.$campo.'" class="fa fa-square-o fa-sm" title="'.traduzir('Nao Monitorado', $_SESSION['lang']).'"></i>|0';
			}
		}else{	//Já estava monitorado, vou tirar o flag de monitorado
			$varCampo->monitoria_status = 0;
			$varCampo->monitora();
			$volta = 'OK|'.traduzir('Item desmarcado como monitorado com sucesso', $_SESSION['lang']).'|<i id="divMonitora'.$campo.'" name="divMonitora'.$campo.'" class="fa fa-square-o fa-sm" title="'.traduzir('Nao Monitorado', $_SESSION['lang']).'"></i>|0|'.$_SESSION['seg_edita'];
		}
		echo $volta;
	}
	if($_GET['act']=='SALVA'){
		//Verificar sessão
		if($_SESSION['valido']<1){echo "<script>window.location.replace('expirado.php');</script>";};

		$varFormulario = new Formulario($_SESSION['crfFormAtual']);
		$varCentro = new CentroPesquisa($_SESSION['crfCentro']);
		$varParticipante = new CRFPaciente($_SESSION['projeto'], $varCentro->id, $_SESSION['crfParticipante']);

		if($varFormulario->form_id==29){
			$varFormularioJaSalvo 	= $varFormulario->digitado;
			$varFormularioEEAG		= $varFormulario->eEAG(270,"1 - Sim");
		}

		$temSim=false;
		if($_SESSION['user_tipo']!=5){$campoValor = "valor";}else{$campoValor = "valor_dupla_digitacao";}
		foreach($_POST as $nome_campo => $valor){
			if(substr($nome_campo,0,5)=="form_"){	//Se for textedit, combobox, textarea, etc
				$valor=nl2br(trim($valor));
				if($valor=="1 - Sim"){$temSim = true;}
				$id = quebra_linha_ao_contrario($nome_campo,"_",1);
				$id = trim($id);

				$varCampo = new Campo($id);
				$varCampo->log_usuario  = $_SESSION['user_id'];
				$varCampoOriginal = $varCampo->valor;
				if($varCampo->valor!=$valor){$varCampo->motivoAlteracao = $_GET['motivo'];}else{$varCampo->motivoAlteracao = "";}
				$varCampo->valor = $valor;
				$varCampo->salvar();
			}
			if(substr($nome_campo,0,5)=="chec_"){
				$valor_ant = $valor;
				$zerar = quebra_linha_ao_contrario($nome_campo,"_",1);
				if($zerar=="H"){	//É hidden
					$id = quebra_linha_ao_contrario($nome_campo,"_",2);
					$id = trim($id);
					$varCampo = new Campo($id);
					if($valor=="DELETAR"){
						$query = "update crf_entrada_campos set log_usuario = " . $_SESSION['user_id'] . ",valor = '' where id = ".$id;
						$conexao->pesquisa($query);
					}
				}
			}

			//Verificar se o campo é tipo DOSE
			//Campos do tipo DOSE
			$arrayDose = array(116, 117, 569);
			if(in_array($varCampo->TipoCampo->id, $arrayDose)){
				if($varCampoOriginal != $varCampo->valor){

					$varTuboAPesquisa = trim(strtoupper($varCampo->valor));
					$query 		= "select * from tubos where nro_psi = '".$varTuboAPesquisa."' and centro = '".$_SESSION['centro']."'";
					$resTubo 	= $varConexao->pesquisa($query);
					$rowTubo	= mysqli_fetch_assoc($resTubo);
					$idSeq		= $rowTubo['id'];

					$query		= "update tubos set data_hora_uso = '".date("YmdHi")."',
										status = 'UTILIZADO',
										participante_alocado = " . $varParticipante->nro_paciente . "
											where id = " . $idSeq;
					$varConexao->pesquisa($query);

				}
			}


			//Vejo se o campo é para alertar via email. Se sim, vejo se o campo esta diferente. Se sim, disparo o email
			if(strtoupper($varCampo->TipoCampo->envia_email)=="ON"){	//É para enviar

				if((($varCampoOriginal != $varCampo->valor)&&($varCampo->TipoCampo->valor_email==$varCampo->valor))||(($varCampoOriginal != $varCampo->valor)&&($varCampoOriginal==$varCampo->TipoCampo->valor_email_anterior))){	//Esta diferente e é igual ao flag de valor_email, disparo o email
					//Enviar email
					include_once "../class/email.class.php";
					$varParticipanteEmail = new Participante($varCentro->id, $varParticipante->nro_triagem);
					$varFormulario 	 = new Formulario($_SESSION['crfFormAtual']);
					$varPara = trim($varCampo->TipoCampo->emails);
					if($varPara==""){
						$varEmail = new EnvioEmail("envio@sistemadepesquisaclinica.com.br", "Sistema de Pesquisa Clínica - SPC", "eduqsb@gmail.com", "Luis Bueno", 1);
					}else{
						$varEmail = new EnvioEmail("envio@sistemadepesquisaclinica.com.br", "Sistema de Pesquisa Clínica - SPC", $varPara, "Alerta SPC", 1);
					}
					$message = utf8_encode(file_get_contents('../modelos/modelo_campo.html'));
					$message = str_replace('%centro%', utf8_encode($varParticipanteEmail->centroNome),$message);
					$message = str_replace('%participante%', $varParticipanteEmail->retornaNroFormatado(), $message);
					$message = str_replace('%formulario%', utf8_encode($varFormulario->getNomeFormulario()), $message);
					$message = str_replace('%campo%', utf8_encode($varCampo->TipoCampo->texto), $message);
					$message = str_replace('%usuario%', utf8_encode($_SESSION['nome_usuario']),$message);
					$message = str_replace('%data_hora%', date('d/m/Y H:i'),$message);
					$message = str_replace('%valor_anterior%', utf8_encode($varCampoOriginal),$message);
					$message = str_replace('%valor_novo%', $valor,$message);
					$varEmail->enviar(utf8_encode($varFormulario->getNomeFormulario() . " - Alteração de campos"), $message);
				}
			}
			//Verificar se é EA. Aqui vai ter um tratamento exclusivo
			if($varFormulario->form_id == 29){
				if($varFormularioJaSalvo==1){
					if($varFormularioEEAG==true){
						//Já tinha sido salvo anteriormente
						include_once "../class/log.class.php";
						include_once "../class/email.class.php";

						if(($varCampoOriginal != utf8_decode($varCampo->valor))){	//Esta diferente, disparo o email



							$varLog = new Log();
							$varLog->escreve("php_ecrf_fo", "ValorOriginal-> $varCampoOriginal | Valor Campo Atual -> ".utf8_decode($varCampo->valor));
							//Enviar email
							$varParticipanteEmail = new Participante($varCentro->id, $varParticipante->nro_triagem);
							$varFormulario 	 = new Formulario($_SESSION['crfFormAtual']);
							$varPara = trim($varCampo->TipoCampo->emails);

							if($varPara==""){
								$varEmail = new EnvioEmail("envio@sistemadepesqusiaclinica.com.br", "Sistema de Pesquisa Clínica - SPC", "eduqsb@gmail.com", "Alerta SPC", 1);
							}else{
								$varEmail = new EnvioEmail("envio@sistemadepesqusiaclinica.com.br", "Sistema de Pesquisa Clínica - SPC", "eduqsb@gmail.com", "Alerta SPC", 1);
							}

							$message = utf8_encode(file_get_contents('../modelos/modelo_campo.html'));
							$message = str_replace('%centro%', utf8_encode($varParticipanteEmail->centroNome),$message);
							$message = str_replace('%participante%', $varParticipanteEmail->retornaNroFormatado(), $message);
							$message = str_replace('%formulario%', utf8_encode($varFormulario->getNomeFormulario()), $message);
							$message = str_replace('%campo%', utf8_encode($varCampo->TipoCampo->texto), $message);
							$message = str_replace('%usuario%', utf8_encode($_SESSION['nome_usuario']),$message);
							$message = str_replace('%data_hora%', date('d/m/Y H:i'),$message);
							$message = str_replace('%valor_anterior%', utf8_encode($varCampoOriginal),$message);
							$message = str_replace('%valor_novo%', $varCampo->valor,$message);
							$varEmail->enviar(utf8_encode("Alteração de campos"), $message);
						}
					}
				}
			}
		}
		foreach($_POST as $nome_campo => $valor){
			if(substr($nome_campo,0,5)=="chec_"){
				$valor_ant = $valor;
				$zerar = quebra_linha_ao_contrario($nome_campo,"_",1);
				if($zerar!="H"){	//nÃO É hIDDEN
					$id = quebra_linha_ao_contrario($nome_campo,"_",1);
					$id = trim($id);
					//É campo normal
					$valores_check = "";
					foreach($_POST[$nome_campo] as $selected){
						if($valores_check==""){
							$valores_check = $selected;
						}else{
							$valores_check .= "!NL!".$selected;
						}
					}
					$valores_check = trim($valores_check);
					if($valores_check!=""){
						$query = "update crf_entrada_campos
									set log_usuario = " . $_SESSION['user_id'] . ",
										valor = '".utf8_decode($valores_check)."'
											where id = ".$id;
						$conexao->pesquisa($query);
					}
				}
			}
		}
		if( ($temSim==true)&&($varFormulario->tipoForm->acao=="RANDOMIZACAO") ) {
			$varPart = new Participante($varCentro->id, $varParticipante->nro_triagem);
			$varPart->randomizarParticipante();
		}
		echo $varFormulario->id; //Retorno o id do formulário em questão, para mudar o list de form para digitado
	}
	if($_GET['act']=="GET_PACIENTE"){
		$varCentro = new CentroPesquisa($_SESSION['crfCentro']);
		$varParticipante = new Participante($varCentro->id, $_SESSION['crfParticipante']);
		//echo $varParticipante->retornaNroFormatado() . " - ".traduzir("Código no Centro", $_SESSION['lang']).": " . $varParticipante->iniciais;
		echo $varParticipante->retornaNroFormatado();
	}
	if($_GET['act']=="EXIBE_FORMULARIO"){
		$formulario = $_POST['varForm'];
		$varForm = new Formulario($formulario);
		$varCentro = new CentroPesquisa($_SESSION['crfCentro']);
		$varParticipante = new CRFPaciente($_SESSION['projeto'], $varCentro->id, $_SESSION['crfParticipante']);
		$varParticipantePessoa = new Participante($varCentro->id, $_SESSION['crfParticipante']);
		$_SESSION['crfFormAtual'] = $formulario;

		if($varForm->tipoForm->acao=="PAGINA"){
			?>
			<div class="col-md-9 fundo_branco" style="overflow-y: scroll; height:680px !important">
				<iframe frameborder="0"  src="<?=$varForm->tipoForm->pagina."?".gera_parametro("pac",$varParticipante->nro_triagem)."&".gera_parametro("cam",$formularioP)."&xxpt=".$varCentro->id;?>" style="width: 100%;height:150%"></iframe>
			</div>
			<?php
		}else{
				$langLabel = "";
				if($_SESSION['lang']=='p'){$langLabel = $varForm->tipoForm->texto_crf;}
				if($_SESSION['lang']=='i'){$langLabel = $varForm->tipoForm->texto_crf_en;}
				if($_SESSION['lang']=='e'){$langLabel = $varForm->tipoForm->texto_crf_es;}
				if($langLabel==""){$langLabel = $varForm->tipoForm->texto_crf;}
				$formTEXTO_CRF_pre = "<span class='textoFORMULARIO_PRE'>[" . strtoupper(traduzir("FORMULÁRIO", $_SESSION['lang'])) . " " . $varForm->tipoForm->ordem. "]</span> - ";
				$formTEXTO_CRF_pre = "";
				?>
				<div class="portlet light" style="width:100%">
					<div class="portlet-title fixo">
						<div class="caption font-blue-dark">
							<span><i class="fa fa-th font-blue-dark" title="Formulários"></i></span>
							<span class="caption-subject">&nbsp;&nbsp;<?=$formTEXTO_CRF_pre . "<b>" . $varForm->getNomeFormulario() . "</b>";?></b></span>
						</div>
						<div class="actions">
							<div class="btn-group">
								<a href="ecrf_crf_preenchido_parcial.php?id=<?=$formulario;?>" target="_blank"><i class="fa fa-file-pdf-o btn dark btn-outline btn-sm" aria-hidden="true"></i></a>
							</div>

						</div>
					</div>
					<div class="portlet-body" >
						<form
							class="form-horizontal"
							role="form"
							action="#"
							method="post"
							id="form_info"
							name="form_info"
							autocomplete="off"
							>
							<input autocomplete="off" name="hidden" type="text" style="display:none;">
							<table width="100%" class="table table-striped" style="font-family: 'Abel';font-size: 22px;">
								<thead>
									<th class="tabela-head" width="10%"><?=traduzir("Ítem");?></th>
									<th class="tabela-head" ><center><?=traduzir("Valor");?></center></th>
								   <th class="tabela-head" width="10%"><center></center></th>
								</thead>
							   <tbody>
								<?php
									$query = "select
												CEC.campo_id as tipocampo,
												CEC.id as id_campo,
												CEC.valor,
												CEC.valor_dupla_digitacao,
												CEC.monitoria_status,
												CEC.alterado,
												CEC.erro_status,
												CFC.id as idTipoCampo,
												CFC.*
													from crf_entrada_campos CEC
														left join crf_forms_campos CFC on CEC.campo_id = CFC.id
												where CEC.form_id = ".$varForm->id;
									if($varForm->tipoForm->id==29){
										$query .= "  order by CFC.ordem";
									}else{
										$query .= "  order by CEC.ordem";
									}

									$resItems = $conexao->pesquisa($query);
									$select_falsos = "";
									$select_falsos_texto = "";
									$indItems = 1;
									while($rowItems = mysqli_fetch_assoc($resItems)){					//Loop em todos os campos
										$varCampo	  = new Campo($rowItems['id_campo']);
										$nomeCampoAleatorio = rand(rand(2124,3324354),rand(3333,2029938))."nomeCampo";

										$varClass = "";
										//Se o participante nao for do sexo do campo, passo o campo
										if($varCampo->TipoCampo->sexo_habilita!=""){
											if($varParticipantePessoa->sexo!=$varCampo->TipoCampo->sexo_habilita){
												continue; //Passo para o próximo campo
											}
										}

										if($rowItems['tipocampo']==0){
											echo "<tr style='line-height: 1px !important;'><td class='tabela-divisao' colspan=3></td></tr>";
										}else{

											$varTCLE	  = $varParticipantePessoa->retornaTCLE("TRI_TCLE_TERMO_DATA");
											//Caso o tipo do usuário não tenha direito de olhar o campo, eu passo para o próximo
											if($varCampo->TipoCampo->veCampo($_SESSION['user_tipo'])==false){continue;}

											$incrementaInd = false;

											$enabled = "";
											$mascara = "";
											$eventos = "";

											$monitoriaStatus = $rowItems['monitoria_status'];
											$calcTipo        = $rowItems['calculo_tipo'];
											$calcCampo1      = $rowItems['calculo_campo1'];
											$calcCampo2      = $rowItems['calculo_campo2'];
											if($calcCampo1!=""){
											   $query_campo = "select FORMULARIO_CAMPO_ID as id from vw_valor_campo
												where nome = '".$calcCampo1."'
														and entrada_forms_centro = ".$varCentro->id."
														and entrada_forms_paciente = " . $varParticipante->nro_triagem;
											   $res_campo   = $conexao->pesquisa($query_campo);
											   $row_campo   = mysqli_fetch_assoc($res_campo);
											   $calcCampo1_id = $row_campo['id'];
											}
											if($calcCampo2!=""){
											   $query_campo = "select FORMULARIO_CAMPO_ID as id from vw_valor_campo where nome = '".$calcCampo2."' and entrada_forms_centro = ".$varCentro->id." and entrada_forms_paciente = " . $varParticipante->nro_triagem;
											   $res_campo   = $conexao->pesquisa($query_campo);
											   $row_campo   = mysqli_fetch_assoc($res_campo);
											   $calcCampo2_id = $row_campo['id'];
											}
											//Busco os valores pa os calculos
											//Buscar nro
											if(substr($calcCampo1,0,1)!="!"){	// Se não for função, busco o valor do campo
												$query = "select id from crf_forms_campos where nome = '".$calcCampo1."'";
												$res_id = $conexao->pesquisa($query);
												$row_id = mysqli_fetch_assoc($res_id);
												$id_campo = $row_id['id'];
												$query = "select crf_entrada_campos.valor from crf_entrada_campos inner join crf_entrada_forms on crf_entrada_campos.form_id = crf_entrada_forms.id where crf_entrada_forms.paciente = ".$varParticipante->nro_triagem." and crf_entrada_forms.centro = ".$varCentro->id." and crf_entrada_campos.campo_id = ".$id_campo;
												$res_valor = $conexao->pesquisa($query);
												$row_valor = mysqli_fetch_assoc($res_valor);
												$Valor_calcCampo1 = $row_valor['valor'];
											}else{
											   if(quebra_linha($calcCampo1,"!",2)=="HOJE"){
												   $Valor_calcCampo1 = date("dd/mm/yyyy");
											   }
											}
											if(substr($calcCampo2,0,1)!="!"){	// Se não for função, busco o valor do campo
												$Valor_calcCampo2 = $varParticipantePessoa->buscaValor($calcCampo2);
											}else{
												if(quebra_linha($calcCampo2,"!",2)=="HOJE"){
													$Valor_calcCampo2 = date("d/m/Y");
												}
												if(quebra_linha($calcCampo2,"!",2)=="ASSINATURA_TCLE_TALE"){
													$Valor_calcCampo2 = '08/09/2024';
													//Buscar o valor do campo de assinatura do tcle
													$Valor_calcCampo2 = LIStoData($varParticipantePessoa->retornaTCLE(""));
												}
											}

											$obrigatorio 				= $varCampo->TipoCampo->obrigatorio;
											$so_numero 					= $varCampo->TipoCampo->so_numero;
											$so_letra 					= $varCampo->TipoCampo->so_letra;
											$campoID 					= $rowItems['id'];
											$condicional_vermelho 		= $varCampo->TipoCampo->condicional_vermelho;
											$condicional_alerta 		= $varCampo->TipoCampo->condicional_alerta;
											$condicional_regra_alerta 	= str_replace("\n", '', $varCampo->TipoCampo->condicional_regra_alerta);
											$condicional_regra_alerta 	= str_replace("<br />", "|", $condicional_regra_alerta);
											//Pegar os valores das variáveis da condicional alerta, para já mandar para a função
											//Condicional alerta são os alertas que são dadas de acordo com os critérios configurados.
											//Além do alerta, o campo fica com borda marrom.
											$var_valores = explode("|", $condicional_regra_alerta);
											$valores_condicional = "";
											for($iV=0;$iV<count($var_valores);$iV++){ 	// Loop em todos as regras
												$regra_atual = explode(";", $var_valores[$iV]);
												$var_temp = $regra_atual[1];
												//Buscar no banco de dados o valor do campo em questão
												$query = "select valor from vw_valor_campo where entrada_forms_centro = ".$varCentro->id." and entrada_forms_paciente = ".$varParticipante->nro_triagem." and nome = '".$var_temp."' ";
												$res_var_temp = $conexao->pesquisa($query);
												$row_var_temp = mysqli_fetch_assoc($res_var_temp);
												$valores_condicional .= $row_var_temp['valor'] . "|";
											}
											//Condicional. Habilia ou não o campo de acordo com o valor de outro campo.
											$condicional = $rowItems['condicional'];
											if($condicional!=""){
												$condicional_quebrado = explode(";", $condicional);
												$campo = $condicional_quebrado[1];

												$query = "select valor from vw_valor_campo where entrada_forms_centro = ".$varCentro->id." and entrada_forms_paciente = ".$varParticipante->nro_triagem." and nome = '".$campo."' ";
												$res_var_temp = $conexao->pesquisa($query);
												$row_var_temp = mysqli_fetch_assoc($res_var_temp);
												$condicional_valor = $row_var_temp['valor'];
												$query_campo = "select FORMULARIO_CAMPO_ID as id from vw_valor_campo where nome = '".$campo."' and entrada_forms_centro = ".$varCentro->id." and entrada_forms_paciente = ".$varParticipante->nro_triagem;
												$res_campo   = $conexao->pesquisa($query_campo);
												$row_campo   = mysqli_fetch_assoc($res_campo);
												$condicional_id = $row_campo['id'];
											}
											$alertas="";
											if($condicional_regra_alerta!=""){$alertas = "alertas(this,'".$condicional_regra_alerta."')";}else{$alertas="";}
											$valorRef = "";
											$nomeRef = "LUIS";
											if($varCampo->TipoCampo->tem_referencia=='on'){
												$nomeRef 		= $varCampo->TipoCampo->nome;
												$nomeRef 	= str_replace("_RESULTADO", "_REFERENCIA", $nomeRef);
												$valorRef   = $varParticipante->retornaValor($nomeRef);
											}
											if(($varCampo->monitoria_status=="1")||($varSeguranca->monitora=="ON")){
												if($_SESSION['user_tipo']!='1'){
												$enabled = " disabled ";
												}
											}else{
												$enabled = "";
												if( ($varCampo->TipoCampo->naoVeCampo($_SESSION['user_tipo'], $varUsuario->especifico)==false) ){
													$enabledFinal = "1";
												}else{
													$enabledFinal = "0";
												}
											}

											$tagatt = " tipo-de-campo='".$rowItems['tipo']."'
														data-valores-condicional='$valores_condicional'
														data-dupla_digitacao_campo='form_".$rowItems['id_campo']."_dupla_digitacao'
														data-dupla_digitacao_original='form_".$rowItems['id_campo']."'
														data-condicional-vermelho='".$condicional_vermelho."'
														data-condicional-vermelho-alerta='".$condicional_alerta."'
														data-condicional-alerta='".$condicional_regra_alerta."'
														data-qtd-decimal= '".$rowItems['qtd_decimal']."'
														data-tipo-validar= '".$rowItems['tipo_campo']."'
														data-condicional='$condicional'
														data-condicional-valor='$condicional_valor'
														data-condicional-id='$condicional_id'
														data-dezoito-anos='".$rowItems['data_dezoito_anos']."' data-menor-termo='".$rowItems['data_menor_termo']."'
														data-igual-menor='".$rowItems['data_menor_hoje']."'
														data-igual-maior='".$rowItems['data_maior_hoje']."'
														data-termo='".$rowItems['termo']."'
														data-data_termo='".$varTCLE."'
														e_horario='".$varCampo->TipoCampo->e_horario."'
														data-tipo='".$rowItems['tipo']."'
														data-alerta-termo='".$rowItems['data_menor_termo_alerta']."'
														data-obrigatorio='$obrigatorio'
														data-readOnly='".$varCampo->TipoCampo->so_leitura."'
														data-Senha='".$varCampo->TipoCampo->senha."'
														data-calc-tipo='$calcTipo'
														data-calc-field1='$calcCampo1'
														data-calc-field1_valor='$Valor_calcCampo1'
														data-calc-field1-id='$calcCampo1_id'
														data-calc-field2='$calcCampo2'
														data-calc-field2_valor='$Valor_calcCampo2'
														data-calc-field2-id='$calcCampo2_id'
														data-campo='$campoID'
														data-enFinal='$enabledFinal'
														indice='$indItems'
														monitoria-status='$monitoriaStatus'
														alterado-status='".$rowItems['alterado']."'
														valor-original='".$rowItems['valor']."'
														v2Condicional='".$varCampo->TipoCampo->condicional."'.
														v2NomeCampo='".$varCampo->TipoCampo->nome."',
														v2Campo1='".$varCampo->TipoCampo->calculo_campo1."'
														v2Campo2='".$varCampo->TipoCampo->calculo_campo2."'
														data-referencia-e='".$varCampo->TipoCampo->tem_referencia."'
														data-referencia-valor='".$valorRef."'
														texto1='".$varCampo->TipoCampo->texto."'
														texto2='".$varCampo->TipoCampo->texto2."'
														condtext2='".$varCampo->TipoCampo->regra_texto2."'
														";
											$incrementaInd=true;
											$mascara = trim($rowItems['formato']);
											$keyUp = "\"";
											if($mascara!=""){ $keyUp .= "mascara(this, '$mascara');"; }
											$eventos .= " onkeyUp=" . $keyUp . "\"";
											$keypress = "\"";
											if($so_numero!=""){ $keypress .= " NumerOnly();";}
											if($so_letra!=""){ $keypress .= " Onlychars();";}
											$eventos .= " onkeypress= ". $keypress . "\"";

											if($varCampo->TipoCampo->bloqueado=="on"){
												$eventos .= " onkeydown='javascript:ignorarTecla(event);'";
											}

											//Tratar evento onChange
											$evOnChange = 'onChange="javascript:alteraMudanca();valida_data(this,\''.$rowItems['tipo'].'\');validaRef(this);validaTexto2();';
											$eventos .= 'OnBlur="valida(this.value,this.min,this.max,\''.$rowItems['texto'].'\',this);'.$alertas.';" ';
											if($varCampo->TipoCampo->calculo_tipo!=""){
												$evOnChange .= 'validaComparadoCampo(this,\'input\');';
											}
											$evOnChange .= '"';
											$eventos .= $evOnChange;
											//Tratar evento onInput
											$evOnInput = 'onInput="';
											if($varCampo->TipoCampo->qtd_decimal!=""){
												$evOnInput .= 'qtdMaxCaracteres(this,'.$varCampo->TipoCampo->qtd_decimal.');';
											}

											$evOnInput .= '"';
											$eventos .= $evOnInput;

											//Aqui estou verificando a questão de ser feminino/masculino ou não.
											if($varCampo->TipoCampo->sexo_habilita!=""){	//Tem a sinalização
												if($varParticipante->sexo!=$varCampo->TipoCampo->sexo_habilita){ $enabled = " disabled "; }
											}
											if(trim($varCampo->TipoCampo->senha)!=""){$varClass = " senha ";}
											if(trim($varCampo->TipoCampo->obrigatorio)!=""){$varClass .= " obrigatorio ";}
											if(trim($varCampo->TipoCampo->condicional_vermelho)!=""){$varClass .= " condicional-vermelho ";}
											if(trim($varCampo->TipoCampo->calculo_tipo)!=""){$varClass .= " calculo ";}
											if(trim($varCampo->TipoCampo->regra_texto2)!=""){$varClass .= " validaTexto2 ";}
											if(trim($varCampo->TipoCampo->calculo_tipo)!=""){$varClass .= " calculo-tipo ";}
											if(trim($varCampo->TipoCampo->condicional)!=""){$varClass .= " v2HabilitaCondicional ";}

											if(($rowItems['tipo']!="divisao_mais")&&($rowItems['tipo']!="divisao")&&($rowItems['tipo']!="escondido")&&($rowItems['tipo']!="pagina")&&($rowItems['tipo']!="botao")) {
												//Aqui vou verificar se vai ser uma DIV escondida
												if(($varCampo->TipoCampo->tipo!="inicio_agrupamento")&&($varCampo->TipoCampo->tipo!="fim_agrupamento")){
													//Verificação se pode mostrar ou não de acordo com a randomização
													if(
														( ( $varParticipante->randomizado!="1") && ($varCampo->TipoCampo->apos_randomizado!="on") ) ||
														( ( $varParticipante->randomizado=="1") && ($varCampo->TipoCampo->apos_randomizado=="on") ) ||
														( ( $varParticipante->randomizado=="1") && ($varCampo->TipoCampo->esconde_randomizado!="on") )
													){
														if($varCampo->TipoCampo->senha=="on"){
															echo "<input type='text' id='senha' name='senha' data-Senha='on' style='display: none'>";
														}else{
															?>
															<tr>
																	<td>
																	  <table width="100%">
																		<tr>
																			<td class="tabela-body">
																				<div id="divTexto_<?=$varCampo->TipoCampo->nome;?>">
																					<?php
																						$langLabel = "";
																						if($_SESSION['lang']=='p'){$langLabel = $varCampo->TipoCampo->texto;}
																						if($_SESSION['lang']=='i'){$langLabel = $varCampo->TipoCampo->texto_en;}
																						if($_SESSION['lang']=='e'){$langLabel = $varCampo->TipoCampo->texto_es;}
																						if($langLabel==""){$langLabel = $varCampo->TipoCampo->texto;}
																						echo $langLabel;
																					?>
																				</div>
																			</td>
																		</tr>
																	  </table>
																	</td>
																	<!-- Início do campo de 1 digitação -->
																	<?php
																	if($_SESSION['user_tipo']!=5){   //Se não for 2 digitador, consegue ver o campo valor. Se não, nao exibe
																	?>
																		<td style="vertical-align:middle" width="22%">
																		  <?php
																		  		switch(strtoupper($varCampo->TipoCampo->tipo)){
																					case "BOTAO":
																						$acao_botao = $rowItems['botao_acao'];
																						echo '<input type="hidden" name="botao_acao" id="botao_acao" value="'.$acao_botao.'">';
																						echo '<button type="button" class="btn-group-xs btn-default" onclick="'.$acao_botao.'()">eCRF</button>';
																					break;
																					case "NUMERO":
																						echo "<input autocomplete='new-password' autocomplete='off' $eventos $tagatt $enabled width='100%' type='number'";
																						if(strlen($rowItems['numero_min'])>0){   echo " min='".$rowItems['numero_min']."' ";	}
																						if(strlen($rowItems['numero_max'])>0){   echo " max='".$rowItems['numero_max']."' ";    }
																						echo "class='form-control controle $varClass'
																							id='form_".$rowItems['id_campo']."'
																							name='".$nomeCampoAleatorio."'
																							value='".$rowItems['valor']."' >";
																					break;
																					case "HORA":
																						echo "<input
																								type='time'
																								autocomplete='new-password'
																								autocomplete='off'
																								$eventos
																								$tagatt
																								$enabled
																								width='100%'
																								name='".$nomeCampoAleatorio."'
																								class='form-control controle $varClass'
																								id='form_".$rowItems['id_campo']."'
																								value='".$rowItems['valor']."'>
																							";
																					break;
																					case "LEITURA":
																						echo "<input
																								autocomplete='new-password'
																								autocomplete='off'
																								v2NomeCampo='".$varCampo->TipoCampo->nome."',
																								disabled
																								width='100%'
																								name='".$nomeCampoAleatorio."'
																								type='text'
																								class='form-control controle $varClass'
																								value='".$rowItems['valor']."'>";
																					break;
																				}
																				if( $rowItems['tipo']=="modal" ){
																					?>
																					<div class="input-group">
																					<?php
																						echo '<input
																								id="form_'.$varCampo->id.'"
																								name="'.$nomeCampoAleatorio.'"
																								type="text"
																								class="form-control controle $varClass"
																								autocomplete="new-password"
																								autocomplete="off"
																								v2NomeCampo="'.$varCampo->TipoCampo->nome.'",
																								$eventos
																								$tagatt
																								$enabled
																								width="100%"
																								value="'.$varCampo->valor.'"
																								>';
																					?>
																						<span class="input-group-btn">
																							<button class="btn blue" type="button" onclick="btnModal(<?=$varCampo->id;?>, '<?=$varCampo->TipoCampo->modal_pagina;?>')">Procurar</button>
																						</span>
																					</div>
																					<?php
																				}
																				if( ($rowItems['tipo']=="texto")||( ($rowItems['tipo']=="randomizado") && ($varParticipante->randomizado==1) ) ){    //SE o tipo for texto
																					echo "<input autocomplete='new-password'
																								autocomplete='off'
																								$eventos
																								$tagatt
																								$enabled
																								width='100%'
																								name='".$nomeCampoAleatorio."'
																					";
																					if($varCampo->TipoCampo->senha=="on"){
																						echo	"type='password' ";
																					}else{
																						echo 	"type='text' ";
																					}
																					echo "		class='form-control controle $varClass'
																								id='form_".$rowItems['id_campo']."'
																								value='".$rowItems['valor']."'>";
																				}

																				if($rowItems['tipo']=="select_falso"){    //SE o tipo for select_falso
																					echo "<input  $eventos $tagatt $enabled width='100%'
																						   name='".$nomeCampoAleatorio."'
																						   type='text' $centralizado class='form-control controle $varClass'
																						   id='form_".$rowItems['id_campo']."'
																						   value='".$rowItems['valor']."'>";
																					$select_falsos[] =  $rowItems['id_campo'];
																					$select_falsos_texto[] = $rowItems['select_falso'];
																				}
																				if($rowItems['tipo']=="caixa"){    //SE o tipo for caixa de texto
																					echo "<textarea $eventos $tagatt $enabled rows='6'
																						   style='width:100%'
																						   name='".$nomeCampoAleatorio."'
																						   class='form-control $varClass'
																						   id='form_".$rowItems['id_campo']."'
																						   >".str_replace('<br />','',$rowItems['valor'])."</textarea>";
																				}

																				if($rowItems['tipo']=="calculo"){    //Se for calculo
																				   $calcTipo = $rowItems['calculo_tipo'];
																				   $calcCampo1 = $rowItems['calculo_campo1'];
																				   $calcCampo2 = $rowItems['calculo_campo2'];
																				   //Busco os valores pa os calculos
																				   //Buscar nro
																				   if(substr($calcCampo1,0,1)!="!"){	// Se não for função, busco o valor do campo
																						$query = "select id from crf_forms_campos where nome = '".$calcCampo1."'";
																						$res_id = $conexao->pesquisa($query);
																						$row_id = mysqli_fetch_assoc($res_id);
																						$id_campo = $row_id['id'];
																						$query = "select crf_entrada_campos.valor from crf_entrada_campos inner join crf_entrada_forms on crf_entrada_campos.form_id = crf_entrada_forms.id where crf_entrada_forms.paciente = ".$varParticipante->nro_triagem." and crf_entrada_forms.centro = ".$varCentro->id." and crf_entrada_campos.campo_id = ".$id_campo;
																						$res_valor = $conexao->pesquisa($query);
																						$row_valor = mysqli_fetch_assoc($res_valor);
																						$calcCampo1Valor = $row_valor['valor'];
																				   }else{
																					   if(quebra_linha($calcCampo1,"!",2)=="HOJE"){
																						   $calcCampo1Valor = date("dd/mm/yyyy");
																					   }
																				   }
																				   if(substr($calcCampo2,0,1)!="!"){	// Se não for função, busco o valor do campo
																						$query = "select id from crf_forms_campos where nome = '".$calcCampo2."'";
																						$res_id = $conexao->pesquisa($query);
																						$row_id = mysqli_fetch_assoc($res_id);
																						$id_campo = $row_id['id'];
																						$query = "select crf_entrada_campos.valor from crf_entrada_campos inner join crf_entrada_forms  on crf_entrada_campos.form_id = crf_entrada_forms.id where crf_entrada_forms.paciente = ".$varParticipante->nro_triagem." and crf_entrada_forms.centro = ".$varCentro->id." and crf_entrada_campos.campo_id = ".$id_campo;
																						$res_valor = $conexao->pesquisa($query);
																						$row_valor = mysqli_fetch_assoc($res_valor);
																						//20190301 - > $calcCampo1Valor = $row_valor['valor'];
																						$calcCampo2Valor = $row_valor['valor'];
																				   }else{
																					   if(quebra_linha($calcCampo2,"!",2)=="HOJE"){
																						   $calcCampo2Valor = date("d/m/Y");
																					   }
																				   }
																					if($calcTipo=="-"){$final = $calcCampo1Valor-$calcCampo2Valor;}
																					if($calcTipo=="+"){$final = $calcCampo1Valor+$calcCampo2Valor;}
																					if($calcTipo=="Idade"){$final = calc_idade_base($calcCampo1Valor,$calcCampo2Valor);}
																					if($calcTipo=="IMC"){$final = IMC($calcCampo2Valor,$calcCampo1Valor);}
																					if($calcTipo=="data_atual"){$final = date("d/m/Y");}
																					if($calcTipo=="hora_atual"){$final = date("H:i");}
																					if($rowItems['valor']==""){
																						echo "<input
																									$eventos
																									$tagatt
																									readonly
																									width='100%'
																									name='".$nomeCampoAleatorio."'
																									type='text' ".$centralizado."
																									class='form-control $varClass'
																									value='".$final."'
																									id='form_".$rowItems['id_campo']."'
																									>";
																					}else{
																						echo "<input disabled $eventos $tagatt readonly  width='100%' type='text' name='".$nomeCampoAleatorio."' ".$centralizado."
																									class='form-control $varClass'
																									value='".$rowItems['valor']."'
																									id='form_".$rowItems['id_campo']."'
																									>";
																					}
																				}
																				if($rowItems['tipo']=="data"){    //SE o tipo for data
																					echo "<input  autocomplete='new-password'
																							autocomplete='off' maxlength='10'
																							$eventos $tagatt $enabled
																							width='100%' type='text' name='".$nomeCampoAleatorio."' $centralizado
																							class='form-control form-control-inline date-picker controle $varClass'
																							id='form_".$rowItems['id_campo']."'
																							value='".$rowItems['valor']."'>";
																					$datas[] = "form_".$rowItems['id_campo'];
																					//array_push ( $datas , "form_".$rowItems['id_campo'] );
																				}
																				if($rowItems['tipo']=="select"){    //SE o tipo for select
																					$opcoes = "";
																					$opcoes = str_replace("<br />","¼",$rowItems['selecao_valores']);
																					$opcoes = str_replace("\n","",$opcoes);
																					echo "<select $eventos $tagatt $enabled
																							class='form-control controle $varClass'
																							name='".$nomeCampoAleatorio."'
																							id='form_".$rowItems['id_campo']."'>";
																					echo "      <option value=''></option>";
																					$combo = "";
																					$temp  = "";
																					for($z=0;$z<strlen($opcoes);$z++){
																					   if(substr($opcoes,$z,1)!="¼"){$temp.=substr($opcoes,$z,1);}
																					   else{
																							$temp=trim($temp);
																							$temp_selected = "";
																							if(trim($rowItems['valor'])==$temp){ $temp_selected = " selected "; }
																							echo '<option '.$temp_selected.' value="'.$temp.'">'.$temp.'</option>';
																							$temp="";
																					   }
																					}
																					if(strlen($temp)>0){
																							$temp=trim($temp);
																							$temp_selected = "";
																							if(trim($rowItems['valor'])==$temp){ $temp_selected = " selected "; }
																							echo '<option '.$temp_selected.' value="'.$temp.'">'.$temp.'</option>';
																							$temp="";
																					}
																					echo '</select>';
																				}
																				if($rowItems['tipo']=="checkbox"){    //SE o tipo for Check-Box
																					$opcoes = "";
																					$opcoes = str_replace("<br />","¼",$rowItems['selecao_valores']);
																					$opcoes = str_replace("\n","",$opcoes);
																					$acao_botao = $rowItems['botao_acao'];
																					$temp = "";
																					echo "<left>";
																					for($z=0;$z<strlen($opcoes);$z++){
																						if(substr($opcoes,$z,1)!="¼"){$temp.=substr($opcoes,$z,1);}
																						else{
																							echo "<input onChange='javascript:carrega_condicional();' $tagatt type='checkbox' id='chec_".$rowItems['id_campo']."[]' name='chec_".$rowItems['id_campo']."[]' class='btn-group-xs btn-default' value='".$temp."' ";
																							$tags  = $rowItems['valor'];
																								/*
																								//20231128
																								*/
																								$tags = str_replace("(", "", $tags);
																								$tags = str_replace(")", "", $tags);
																								$temp = str_replace("(", "", $temp);
																								$temp = str_replace(")", "", $temp);



																							$termo = trim($temp);

																							$pattern = '/' . $termo . '/';//Padrão a ser encontrado na string $tags
																							if (preg_match($pattern, $tags)) {echo " checked ";}
																							echo "> " .$temp ."<br>";
																							echo "<input type='hidden' id='chec_".$rowItems['id_campo']."_H' name='chec_".$rowItems['id_campo']."_H' value='DELETAR' ";
																							if (preg_match($pattern, $tags)) {echo " checked ";}
																							echo "> ";
																							$temp="";
																						}
																					}
																					if(strlen($temp)>0){
																						$temp =trim($temp);
																						echo "<input onChange='javascript:carrega_condicional();' $tagatt type='checkbox' id='chec_".$rowItems['id_campo']."[]' name='chec_".$rowItems['id_campo']."[]' class='btn-group-xs btn-default' value='".$temp."' ";
																						$tags  = $rowItems['valor'];
																						$termo = trim($temp);
																						$pattern = '/' . $termo . '/';//Padrão a ser encontrado na string $tags
																						if (preg_match($pattern, $tags)) {echo " checked ";}
																						echo "> ". $temp;
																						echo "<input type='hidden' id='chec_".$rowItems['id_campo']."_H' name='chec_".$rowItems['id_campo']."_H' value='DELETAR' ";
																						if (preg_match($pattern, $tags)) {echo " checked ";}
																						echo "> ";
																					}
																					echo "</left>";

																					echo "<center></center>";
																					}
																					?><center><span class="legenda"><?php if($rowItems['legenda']!=""){ echo "(".$rowItems['legenda'].")";}?></span></center>
																					<div id="divMsg_<?=$rowItems['id_campo'];?>" class="divMsg"></div>

																		</td>
																  <?php
																  }
															if($varCampo->TipoCampo->senha!="on"){
															?>
																<!-- Coluna de botões -->

																<td style="vertical-align:middle;align:right; text-align:right !important"><center>
																<?php
																	$acao_mon = "javascript:monitora(".$varCampo->id.");";
																	$acao_his = "javascript:muda_pagina('ecrf_formularios_historico.php?".gera_parametro("pac",$varParticipante->nro_triagem)."&".gera_parametro("cam",$formularioP)."&".gera_parametro("act","M")."&".gera_parametro("cod",$rowItems['id_campo'])."&xxpt=".$varCentro->id."');";
																	$acao_com = "javascript:muda_pagina('ecrf_formularios_comentarios.php?".gera_parametro("pac",$varParticipante->nro_triagem)."&".gera_parametro("cam",$formularioP)."&".gera_parametro("act","M")."&".gera_parametro("cod",$rowItems['id_campo'])."&xxpt=". $varCentro->id."');";
																	$acao_err = "javascript:muda_erro(".$rowItems['id_campo'].");";
																?>
																	<div class="btn-group" style="align:right; text-align:right !important">
																		<!--Botão Monitora-->
																		<?php
																		/*if(($varSeguranca->monitora=="ON")&&($varCampo->TipoCampo->tem_sdv==1)){
																		?>
																			<button type="button" class="btn btn-default btn-sm"
																			<?php
																				if(($_SESSION['user_tipo']==15)||($_SESSION['user_tipo']==2)||($_SESSION['user_tipo']==1)){ echo 'onclick="'.$acao_mon.'" ';};
																				echo " >";
																				echo '<span id="divMonitora'.$rowItems['id_campo'].'" name="divMonitora'.$rowItems['id_campo'].'">';
																				if($rowItems['monitoria_status']=="0"){
																					echo '<i class="fa fa-square-o fa-sm" title="'.traduzir("Não monitorado").'"></i>';
																				}else{
																					  echo '<i class="fa fa-check-square-o fa-sm" title="'.traduzir("Monitorado").'"></i>';
																				}
																				echo "</span>";
																			?>
																			</button>
																		<?php
																		}*/
																		?>
																		<!--Botão Histórico (Audit Trail)-->
																		<button  type="button"
																				class="btn btn-default btn-sm btnHistorico"
																				data-toggle="modal"
																				data-target="#modalHistoricoNovo"
																				href="modal_ecrf_historico.php?id=<?=$varCampo->id;?>"
																			<?php
																				echo " >";
																				echo '<i class="fa fa-history fa-sm" title="'.traduzir("Histórico [AuditTrail]").'"></i>';
																			?>
																		</button>

																		<a name="anc_<?=$varCampo->id;?>"></a>
																		<button  type="button"
																				class="btn btn-default btn-sm btnHistorico"
																				data-toggle="modal"
																				data-target="#modalComentarios"
																				href="modal_ecrf_comentarios.php?id=<?=$varCampo->id;?>"
																			<?php
																				echo " >";
																				if($varCampo->getQtdComentarios()==0){
																					echo '<div id="divComentario_'.$varCampo->id.'"><i class="fa fa-commenting-o fa-sm " title="'.traduzir("Comentários").'"></i></div>';
																				}else{
																					echo '<div id="divComentario_'.$varCampo->id.'"><i class="fa fa-commenting fa-sm font-green-steel" title="'.traduzir("Comentários").'"></div></i>';
																				}
																			?>
																		</button>
																	</div>
																</td>
															<?php
															}
														}

														?>
														</tr>
													<?php
													}
													if($incrementaInd==true){
														$indItems++;
													}
												}else{
													echo "<tr><td colspan=3 class='tabela-divisao' style='height:1px !important'></td></tr>";
												}
											}else{	//Se for Divisão de campo
												if($rowItems['tipo']=="escondido"){
													$acao_botao = $rowItems['botao_acao'];
													$nome_escondido = $rowItems['nome'];
													if($nome_escondido=="RANDOMIZAR"){$mostra_randomizacao=true;}else{$mostra_randomizacao=false; };
													echo '<input type="hidden" name="escondido" id="escondido" value="'.$acao_botao.'">';
												}
												if($rowItems['tipo']=="divisao"){    //SE for divisão de campo
													if(
														( ( $varParticipante->randomizado!="1") && ($varCampo->TipoCampo->apos_randomizado!="on") ) ||
														( ( $varParticipante->randomizado=="1") && ($varCampo->TipoCampo->apos_randomizado=="on") ) ||
														( ( $varParticipante->randomizado=="1") && ($varCampo->TipoCampo->esconde_randomizado!="on") )
													){
												?>
													<tr>
														<td colspan="4" class="tabela-divisao">
														<?php
														$langLabel = "";
														if($_SESSION['lang']=='p'){$langLabel = $rowItems['texto'];}
														if($_SESSION['lang']=='i'){$langLabel = $rowItems['texto_en'];}
														if($_SESSION['lang']=='e'){$langLabel = $rowItems['texto_es'];}
														if($langLabel==""){$langLabel = $rowItems['texto'];}
														echo $langLabel;
														?>
														</td>
													</tr>
												<?php
													}
												}
												if($rowItems['tipo']=="divisao_mais"){    //SE for divisão de campo
												?>
													<tr>
														<td colspan="2" class="tabela-divisao">
															<?php
															$langLabel = "";
															if($_SESSION['lang']=='p'){$langLabel = $rowItems['texto'];}
															if($_SESSION['lang']=='i'){$langLabel = $rowItems['texto_en'];}
															if($_SESSION['lang']=='e'){$langLabel = $rowItems['texto_es'];}
															if($langLabel==""){$langLabel = $rowItems['texto'];}
															echo $langLabel;
															?>
														</td>
														<td class="tabela-divisao" style="text-align: right">
															<?php
															//Verificar se pode editar
															if($_SESSION['seg_edita']=="ON"){
															?>
																<a href="javascript:javascript:addFields('<?=$varCampo->id;?>');" class="btn btn-xs default"><i class="fa fa-plus"></i></a>
															<?php
															}
															?>
														</td>
													</tr>
												<?php
												}
												if($rowItems['tipo']=="pagina"){    //SE o tipo for página
												?>
													<tr>
														<td colspan="3">
															<div style="display:none;" name="<?=nomeAleatorio();?>form_pagina" id="form_pagina" <?=$tagatt;?> >
																<iframe frameborder="0"  src="<?=$rowItems['pagina']."?".gera_parametro("pac",$varParticipante->nro_triagem)."&".gera_parametro("cam",$formularioP)."&xxpt=".$varCentro->id;?>" style="width: 100%;height:<?=$rowItems['pagina_altura']?>px"></iframe>
															</div>
														</td>
													</tr>
												<?php
												}
												if($rowItems['tipo']=="botao"){
													$condicional = $rowItems['condicional'];
													$tagatt = "data-condicional='$condicional' data-campo='".$rowItems['id']."' ";
													?>
													<tr>
														<td colspan="3" align="center">
															<button type="button" <?="id='form_".$rowItems['id_campo']."' name='form_".$rowItems['id_campo']."' "; echo $tagatt;?> class="btn btn-default" onclick="javascript:<?=$rowItems['botao_acao'];?>;"><?=$rowItems['texto'];?></button>
														<td>
													</tr>
													<?php
												}
											}
										} //TIPO 0
									}
								   ?>
									<td colspan="4" class="tabela-head">
										<div class="row">
											<div class="col-md-4"></div>
											<div class="col-md-3">
												   <center>
														<?php

															if ($varSeguranca->edita!="ON"){$btnDisable = " disabled ";}

															if($varForm->tipoForm->acao=="RANDOMIZACAO"){	//É o formulário de Randomização
																if( ($_SESSION['tipo_usuario']==1)||($_SESSION['tipo_usuario']==14) ){
																	//Verificar se tem alocação disponível. Se tiver, deixo randomizar. Se não tiver, bloqueio
																	$query 		= "SELECT * FROM `crf_rand_semente` where paciente_alocado < 1";
																	$resAloq 	= $varConexao->pesquisa($query);
																	$qtdAloq	= mysqli_num_rows($resAloq);
																	if($qtdAloq>0){
																		$acaoBtn = "RANDOMIZACAO";
																		if($varParticipante->randomizado==0){
																			$btnSalvarLabel = "Salvar / Randomizar";
																		}else{
																			$btnSalvarLabel = "Salvar";
																		}
																	}else{
																		$acaoBtn = "NADA";
																		if($varParticipante->randomizado==0){
																			$btnSalvarLabel = "SEM ALOCAÇÃO DISPONÍVEL<br>ENTRE EM CONTATO COM O SUPORTE";
																		}else{
																			$btnSalvarLabel = "";
																		}
																		$btnDisable = ' disabled ';
																	}

																}else{
																	//Não pode randomizar
																	$acaoBtn = "NADA";
																	if($varParticipante->randomizado==0){
																		$btnSalvarLabel = "SEM PERMISSÃO PARA RANDOMIZAR";
																	}else{
																		$btnSalvarLabel = "";
																	}
																	$btnDisable = ' disabled ';
																}

																/*$acaoBtn = "NADA";
																$btnSalvarLabel = "RANDOMIZAÇÃO PARALISADA";
																$btnDisable = " disabled ";
																if($varParticipante->randomizado==1){
																	$acaoBtn = "SALVA";
																	$btnSalvarLabel = "Salvar";
																	$btnDisable = " ";
																}*/
															}else{
																$acaoBtn = "SALVA";
																$btnSalvarLabel = "Salvar";
															}


															$btnDisable = " ";

														?>
														<button
															style="padding: 3.0% 0;"
															type="button"
															<?php
																echo $btnDisable;
															?>
															class="btn btn-block btn-default "
															onclick="javascript:valida_dados('<?=$acaoBtn;?>');">
																<i class="fa fa-floppy-o"></i>&nbsp&nbsp&nbsp <?=traduzir($btnSalvarLabel);?>
														</button>
														<br>
												   </center>
											</div>
											<!--
											<div class="col-md-3">
												   <center>
														   <?php
																/*
																$form_id = $formularioP;

																$queryP = "SELECT	crf_entrada_forms.id, crf_entrada_forms.paciente, crf_entrada_forms.form_id, crf_entrada_forms.centro, ";
																$queryP .= "  crf_forms.nome, crf_forms.ordem    from crf_entrada_forms  	inner join crf_forms on crf_entrada_forms.form_id = crf_forms.id ";
																$queryP .= "  where crf_entrada_forms.id = " . $form_id;
																$resP    = $conexao->pesquisa($queryP);
																$rowP	 = mysqli_fetch_assoc($resP);
																$ordemP = $rowP['ordem'];

																//pego o proximo form na tabela crf_forms
																$queryP = "select * from crf_forms where ordem > ".$ordemP." order by ordem";
																$resP	= $conexao->pesquisa($queryP);
																if(mysqli_num_rows($resP)>0){
																	$rowP = mysqli_fetch_assoc($resP);
																	$proxP =  $rowP['id'];

																	$queryP = "SELECT	crf_entrada_forms.id, crf_entrada_forms.paciente, crf_entrada_forms.form_id, crf_entrada_forms.centro,  ";
																	$queryP .="   crf_forms.nome, crf_forms.ordem    from crf_entrada_forms  	  ";
																	$queryP .=" inner join crf_forms on crf_entrada_forms.form_id = crf_forms.id   ";
																	$queryP .=" where crf_forms.id = ".$proxP." and crf_entrada_forms.paciente = ".$varParticipante->nro_triagem." and crf_entrada_forms.centro = " . $varCentro->id;
																	$resP = $conexao->pesquisa($queryP);
																	$rowP = mysqli_fetch_assoc($resP);
																	//echo '<button style="padding: 3.0% 0;"  type="button" class="btn btn-block btn-default " onclick="javascript:valida_dados('.$rowP['id'].');"><i class="fa fa-floppy-o"></i>&nbspSalvar e próximo</button><br>';
																	if($PAGedita=="ON"){
																		//echo '<button style="width:200px !important; padding: 3.0% 0;" type="button" class="btn btn-default " onclick="javascript:valida_dados('.$rowP['id'].');"><i class="fa fa-floppy-o"></i>&nbspSalvar e próximo</button>';
																	}
																}
																*/
														   ?>
												   </center>
											</div>
											-->
											<?php
												//Mostra o recibo de randomização!
											?>
											<div class="col-md-3">
												<?php
													if( ($varParticipante->randomizado=="1")&&($varForm->tipoForm->acao=="RANDOMIZACAO") ){
														echo '<button style="width:100px !important; padding: 3.0% 0;" type="button" class="btn btn-default " onclick="javascript:recibo('.$varParticipante->centro.','.$varParticipante->nro_triagem.');"><i class="fa fa-print"></i>&nbspRecibo</button>';
													}
												?>
											</div>
										</div>
									</td>
							   </tr>
							   <tr>

							   </tr>
							   <!--<tr>
								   <td colspan="4" align="right">
									   <right>
									   <button  type="button"
												class="btn btn-default btn-sm btnHistorico"
												data-toggle="modal"
												data-target="#modalHistoricoNovo"
												href="modal_ecrf_historico_form.php?id=<?=$varCampo->form_id;?>"
											<?php
												echo " >";
												echo '<i class="fa fa-history fa-sm" title="Histórico"> Histórico de Modificações</i>';
											?>
										</button>
								   </td>
							   </tr>-->
							   </tbody>
							</table>
						</form>
					</div>
				</div>
		<?php
		}
	}