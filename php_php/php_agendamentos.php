<?php
	include_once "../seguranca.php";
	include_once "../class/agenda.class.php";

	if($_GET['act']=='SALVA'){
		$varFormulario = new Formulario($_SESSION['agendaFormulario']);
		$temSim=false;
		$campoValor = "valor";

		foreach($_POST as $nome_campo => $valor){
			if(substr($nome_campo,0,5)=="form_"){	//Se for textedit, combobox, textarea, etc
				$valor=nl2br(trim($valor));
				if($valor=="1 - Sim"){$temSim = true;}
				$id = quebra_linha_ao_contrario($nome_campo,"_",1);
				$id = trim($id);

				$varCampo = new Campo($id);
				$varCampo->log_usuario  = $_SESSION['user_id'];
				if($varCampo->valor!=$valor){$varCampo->motivoAlteracao = $_GET['motivo'];}else{$varCampo->motivoAlteracao = "";}
				$varCampo->valor = $valor;
				echo $varCampo->salvar();
			}
		}
		echo "FIM";
	}
	if($_GET['act']=="DELETA_CAMPO"){
		$id = $_GET['id'];
		$varCampo = new Campo($id);
		$query = "delete from crf_agenda_campos where id = " . $id;
		$conexao->pesquisa($query);
		echo $query;
	}
	if($_GET['act']=="LISTA_FORMULARIOS"){
		?>
			<table width="100%" class="table table-hover table-condensed" style="background-color:white !important">
				<tbody>
					<?
						//Monto a lista de Formulários
						$query = "select
									crf_agenda_forms.id,
									crf_agenda_forms.agendamento,
									crf_agenda_forms.form_id,
									cfg_visitas.nome,
									cfg_visitas.texto_crf,
									cfg_visitas.texto_crf_en,
									cfg_visitas.ordem,
									cfg_visitas.id as formulario_id
									from crf_agenda_forms
										inner join cfg_visitas on crf_agenda_forms.form_id = cfg_visitas.id
									where cfg_visitas.acao <> 'PAGINA_BRANCO' and cfg_visitas.acao <> 'CABECALHO' and crf_agenda_forms.agendamento = " . $_SESSION['agendaFormulario'] . "
										order by cast(cfg_visitas.ordem as DECIMAL)";
						$resUsua = $conexao->pesquisa($query);
						while($rowUsua = mysqli_fetch_assoc($resUsua)){
							?>
								<tr class="lista_formularios"
									onclick="montaForm(<?=$rowUsua['id'];?>, 0);"
										style="cursor: pointer;font-family: 'Abel';font-size: 22px;">
										<td class="lista_formularios">
												<a name="ANCORA_<?=$rowUsua['id'];?>"></a>
											<?
											if($rowUsua['id']==$formularioP){ echo "<b>"; }
											if(($rowUsua['ordem']!="Z")&&($rowUsua['ordem']!="99999")){
												echo $rowUsua['ordem'] . " - ";
											}
											echo  $rowUsua['texto_crf'];
											if($rowUsua['id']==$formularioP){ echo "</b>"; }
											//Aqui vou colocar uma segunda linha caso seja em EN
											if($_SESSION['lang']=="i"){
												echo "<br><span class='formulario_en'>[" . $rowUsua['texto_crf_en'] . "]</span>";
											}
											echo '
										</td>
								</tr>';
						}
						?>
				</tbody>
			</table>
		<?php
	}
	if($_GET['act']=="MONTA_FORMULARIO"){
		$formId 		= $_POST['formId'];
		$varFormulario 	= new Formulario($formId);
		$varAgendamento = new Agenda($varFormulario->agendamento);

		$PAGedita		=	true;
		?>
			<div id="TOPO"></div>
		<?
		if($varFormulario->classFormulario->acao=="PAGINA"){
			?>
			<div class="col-md-12">
				 <h4 align="left">
						<?
						if($formTEXTO_CRF!=""){
							echo "<b>&nbsp;&nbsp;&nbsp;" .$formTEXTO_CRF . "</b>";
						}
						?>
						</h4>
				 <iframe style="width: 100%;height:600px" height="500px" frameborder="0"  src="<?=$varFormulario->classFormulario->pagina."?agendamento=".$varFormulario->agendamento."&".gera_parametro("pac",$paciente)."&".gera_parametro("cam",$formularioP)."&luis=teste";?>" ></iframe>
			</div>
			<?
		}else{
			?>
			<div class="col-md-12 fundo_branco">
				<div class="portlet light">
					<div class="portlet-title fixo">
						<div class="caption font-blue-dark">
							<span><i class="fa fa-th font-blue-dark" title="Formulários"></i></span>
							<span class="caption-subject"><b><?=$varFormulario->classFormulario->ordem;?>&nbsp;-&nbsp;<?=$varFormulario->classFormulario->texto_crf;?></b>
						</div>
						<div class="actions">
							<a class="btn btn-circle btn-icon-only btn-default fullscreen" href="javascript:;"> </a>
						</div>
					</div>
					<div class="portlet-body" >
				<?
				   $acao = "agendamentos.php?agendamento=$varAgendamento->id&".gera_parametro("cam",$varFormulario->id)."&".gera_parametro("act","I");
				?><br>
				<form class="form-horizontal" role="form" action="<?=$acao;?>" method="post" id="form_info" name="form_info">
				<table width="100%" class="table table-striped">
					<thead>
						<th class="tabela-head" width="350">Ítem</th>
						<th class="tabela-head"><center>Valor</center></th>
						<th class="tabela-head"><center>Pré</center></th>
						<th class="tabela-head" width="150"><center>Ações</center></th>
					</thead>
				   <tbody id="divRetorno">
				   <?
						$query = "select
										crf_agenda_campos.id
									from crf_agenda_campos
										inner join cfg_visitas_campos on crf_agenda_campos.campo_id = cfg_visitas_campos.id
											where crf_agenda_campos.form_id = " . $varFormulario->id . "
												and cfg_visitas_campos.tipo not in ('esp_tratamento')
												order by cfg_visitas_campos.ordem ";
						$resItems = $conexao->pesquisa($query);
						$datas = "";
						$select_falsos = "";
						$select_falsos_texto = "";
						while($rowItems = mysqli_fetch_assoc($resItems)){
							$varCampo = new Campo($rowItems['id']);
							$enabled = " disabled ";
							  if(($varCampo->classTipoCampo->tipo!="divisao")&&($varCampo->classTipoCampo->tipo!="escondido")&&($varCampo->classTipoCampo->tipo!="pagina")&&($varCampo->classTipoCampo->tipo!="botao")) {
									//Aqui vou verificar se vai ser uma DIV escondida
									if(($varCampo->classTipoCampo->tipo!="inicio_agrupamento")&&($varCampo->classTipoCampo->tipo!="fim_agrupamento"))
									{
							  ?>
							  <tr>
								<?
								if(($varCampo->classTipoCampo->tipo=="json")||($varCampo->classTipoCampo->tipo=="json_vertical")){
								?>
									<td colspan="4" width="100%">
										<?
											//$pagina_agen = "agendamentos_json.php?id=".$varCampo->id."&tipo=" . $varCampo->id."&tabela=crf_agenda_campos";
											$pagina_agen = "agendamentos_json.php?id=".$varCampo->id."&tipo=" . $varCampo->campo_id."&tabela=crf_agenda_campos";
										?>
										<iframe frameborder="0"  src="<?=$pagina_agen;?>" style="width: 100%;height:500px !important"></iframe>
									</td>
								<?
								}else{
									$varOrdem = $varOrdem +1;
								?>
								  <td width="*">
									  <table width="100%">
										 <tr>
											<?
											if($varCampo->classTipoCampo->ordem!="999"){
											?>
										   <td class="tabela-body">
											<span style="font-size: 0.9em"><?="[".$varOrdem . "] - " . $varCampo->classTipoCampo->texto;?></span>
											<?
												if($_SESSION['lang']=="i"){
													echo "<br><span class='texto_en'>[" . $varCampo->classTipoCampo->texto_en . "]</span>";
												}
											?>
											</td>
										   <?
											}else{
											?>
											<td class=".text-muted"><span style="font-size: 0.9em"><?=$varCampo->classTipoCampo->texto;?></span>
											<?
												if($_SESSION['lang']=="i"){
													echo "<br><span class='texto_en'>[" . $varCampo->classTipoCampo->texto_en . "]</span>";
												}
											?>
											</td>
											<?
											}
										   ?>
										 </tr>
									  </table>
								  </td>
								  <td style="vertical-align:middle">
								  <?
									$mascara = "";
									$campoID = $varCampo->id;
									$mascara = trim($varCampo->classTipoCampo->formato);
									$keyUp = "\"";
									$keypress = "\"";
									if($mascara!=""){ $keyUp .= "mascara(this, '$mascara');"; }
									$eventos = " onkeyUp=" . $keyUp . "\"";
									$eventos .= " onkeypress= ". $keypress . "\"";
									$eventos .= 'OnBlur="javascript:valida(this.value,this.min,this.max,\''.$varCampo->classTipoCampo->texto.'\',this);valida_dados_pontual(this);" ';

									$tagatt = " tipo-de-campo='".$varCampo->classTipoCampo->tipo."'
												data-readOnly='".$varCampo->TipoCampo->so_leitura."'
												valor-original='".$varCampo->valor."'
												alterado-status='".$varCampo->alterado."'
									";


									   $enabled = "";
										if($varCampo->classTipoCampo->tipo=="numero"){    //SE o tipo for número
											echo "<input $tagatt $eventos $enabled width='100%' type='text' style='text-align: center; '";
											if(strlen($varCampo->classTipoCampo->numero_min)>0){
											  echo " min='".$varCampo->classTipoCampo->numero_min."' ";
											}
											if(strlen($varCampo->classTipoCampo->numero_max)>0){
											  echo " max='".$varCampo->classTipoCampo->numero_max."' ";
											}
											echo "class='form-control controle'
													id='form_".$varCampo->id."'
													name='form_".$varCampo->id."'
													value='".$varCampo->valor."' >";
										}
										if($varCampo->classTipoCampo->tipo=="texto"){    //SE o tipo for texto
											echo "<input $tagatt $eventos $enabled width='100%' type='text' style='text-align: center; '
													class='form-control controle'
													id='form_".$varCampo->id."'
													name='form_".$varCampo->id."'
													value='".$varCampo->valor."'>";
										}
										if($varCampo->classTipoCampo->tipo=="select_falso"){    //SE o tipo for select_falso
											echo "<input  $tagatt $eventos $enabled width='100%' type='text' $centralizado class='form-control controle'
														id='form_".$varCampo->id."'
														name='form_".$varCampo->id."'
														value='".$varCampo->valor."'>";
														$select_falsos[] =  $varCampo->id;
														$select_falsos_texto[] = $varCampo->classTipoCampo->select_falso;
										}
										if($varCampo->classTipoCampo->tipo=="caixa"){    //SE o tipo for caixa de texto
											echo "<textarea $tagatt $eventos $enabled rows='9'
													style='width:100%'
													class='form-control controle_caixa'
													id='form_".$varCampo->id."'
													name='form_".$varCampo->id."'>".str_replace('<br />','',$varCampo->valor)."</textarea>";
										}
										if($varCampo->classTipoCampo->tipo=="data"){    //SE o tipo for data
											echo "<input  $tagatt maxlength='10' $eventos $enabled width='100%'
													type='text' $centralizado
													class='form-control form-control-inline date-picker  controle'
													id='form_".$varCampo->id."'
													name='form_".$varCampo->id."'
													value='".$varCampo->valor."'>";
											$datas[] = "form_".$varCampo->id;
										}
										if($varCampo->classTipoCampo->tipo=="textoHTML"){    //SE o tipo for caixa de texto
											echo "<div $tagatt id='form_".$varCampo->id."'
														name='form_".$varCampo->id."'
														class='summernote'
														referente='form_".$varCampo->id."'></div>";
											$listaHTML[] = 	"form_".$varCampo->id;
											$valorHTML[] =  $varCampo->valor;
										}
										if( ($varCampo->classTipoCampo->tipo=="select")||($varCampo->classTipoCampo->tipo=="select_coluna")){    //SE o tipo for data
											$opcoes = "";
											$opcoes = str_replace("<br />","%",$varCampo->classTipoCampo->selecao_valores);
											$opcoes = str_replace("\n","",$opcoes);
											echo "<select $tagatt $eventos $enabled class='form-control  controle'
													name='form_".$varCampo->id."'
													id='form_".$varCampo->id."'>";
											echo "      <option value=''></option>";
											$combo = "";
											$temp  = "";
											//echo $opcoes;
											for($z=0;$z<strlen($opcoes);$z++){
											   if(substr($opcoes,$z,1)!="%"){$temp.=substr($opcoes,$z,1);}
											   else{
													$temp=trim($temp);
													$temp_selected = "";
													if(trim($varCampo->valor)==$temp){ $temp_selected = " selected "; }
													echo '<option '.$temp_selected.' value="'.$temp.'">'.$temp.'</option>';
													$temp="";
											   }
											}
											if(strlen($temp)>0){
													$temp=trim($temp);
													$temp_selected = "";
													if(trim($varCampo->valor)==$temp){ $temp_selected = " selected "; }
													echo '<option '.$temp_selected.' value="'.$temp.'">'.$temp.'</option>';
													$temp="";
											}
											echo '</select>';
										}
										if($varCampo->classTipoCampo->tipo=="checkbox"){    //SE o tipo for Check-Box
										   $opcoes = "";
										   $opcoes = str_replace("<br />","%",$varCampo->classTipoCampo->selecao_valores);
										   $opcoes = str_replace("\n","",$opcoes);
										   $acao_botao = $varCampo->classTipoCampo->botao_acao;
										   $temp = "";
										   echo "<left>";
										   for($z=0;$z<strlen($opcoes);$z++){
											   if(substr($opcoes,$z,1)!="%"){$temp.=substr($opcoes,$z,1);}
											   else{
												   echo "<input  $tagatt type='checkbox'
															id='form_".$varCampo->id."'
															name='form_".$varCampo->id."'
															class='btn-group-xs btn-default' value='".$temp."' ";
												   if( trim($varCampo->valor) == trim($temp) ){ echo " checked ";}
												   echo "> " .$temp ."<br>";
												   $temp="";
											   }
										   }
										   if(strlen($temp)>0){
											   $temp =trim($temp);
											   echo "<input $tagatt type='checkbox'
														id='form_".$varCampo->id."'
														name='form_".$varCampo->id."'
														class='btn-group-xs btn-default' value='".$temp."' ";
											   if( trim($varCampo->valor) == trim($temp) ){ echo " checked ";}
											   echo "> ". $temp;
										   }
										   echo "</left>";
										   echo "<center></center>";
									   }
								  ?><center><span class="legenda"><i><?if($varCampo->classTipoCampo->legenda!=""){ echo "(".$varCampo->classTipoCampo->legenda.")";}?></i></class></center>
								  </span>
								  </td>
								  <td width="50"><center>
										<div class="clearfix">
											<a href="javascript:setaNA(<?=$varCampo->id;?>);" class="btn default red-stripe"> N/A </a>
											<a href="javascript:setaNV(<?=$varCampo->id;?>);;" class="btn default blue-stripe"> N/V </a>
										</div></center>
								  </td>
								  <td style="vertical-align:middle;align:center" class="cssColunaBotao"><center>
										<?
											$acao_mon = "javascript:monitora(".$varCampo->id.");";
											$acao_his = "javascript:muda_pagina('ecrf_formularios_historico.php?".gera_parametro("pac",$paciente)."&".gera_parametro("cam",$formularioP)."&".gera_parametro("act","M")."&".gera_parametro("cod",$varCampo->id)."');";
											$acao_com = "javascript:muda_pagina('ecrf_formularios_comentarios.php?".gera_parametro("pac",$paciente)."&".gera_parametro("cam",$formularioP)."&".gera_parametro("act","M")."&".gera_parametro("cod",$varCampo->id)."');";
											$acao_err = "javascript:muda_erro(".$varCampo->id.");";
										?>
											<div class="btn-group">

												<button type="button" class="btn btn-default btn-sm"
												<?
													if(($_SESSION['user_tipo']==2)||($_SESSION['user_tipo']==1)){ echo 'onclick="'.$acao_mon.'" ';};
													echo " >";
													echo '<span id="retorno'.$varCampo->id.'" name="retorno'.$varCampo->id.'">';
													if($varCampo->monitoria_status=="0"){
														echo '<i class="fa fa-square-o fa-sm" title="Não monitorado"></i>';
													}else{
														  echo '<i class="fa fa-check-square-o fa-sm" title="Monitorado"></i>';
													}
													echo "</span>";
												?>
												</button>
												<!--BOTAO HISTORICO-->
												<button  type="button" class="btn btn-default btn-sm"
													data-toggle="modal" data-target="#modalHistorico" href="modal_agenda_historico.php?id=<?=$varCampo->id;?>"
													<?
														echo " >";
														echo '<i class="fa fa-history fa-sm" title="Histórico"></i>';
													?>
												</button>
												<!--BOTAO COMENTÁRIOS-->
												<button  type="button" class="btn btn-default btn-sm"
													data-toggle="modal"
													data-target="#modalHistorico"
													href="modal_agenda_comentario.php?id=<?=$varCampo->id;?>"
													<?
														echo " >";
														echo '<span id="btnComentario'.$varCampo->id.'" name="btnComentario'.$varCampo->id.'">';
														if(trim($varCampo->comentario)==""){
															echo '<i class="fa fa-commenting-o fa-sm " title="Comentários"></i>';
														}else{
															echo '<i class="fa fa-commenting fa-sm font-green-steel" title="Comentários"></i>';
														}
														echo "</span>";
													?>
												</button>
												<br>
												<!--BOTAO SE TEM ERRO-->
												<button  type="button" class="btn btn-default btn-sm"
													<?

														if(($_SESSION['user_tipo']==2)||($_SESSION['user_tipo']==1)){ echo 'onclick="'.$acao_err.'" ';};
														echo " >";
														echo '<span id="retorno_erro'.$varCampo->id.'" name="retorno_erro'.$varCampo->id.'">';
														if($varCampo->erro_status=="0"){
															echo '<i class="fa fa-thumbs-o-up fa-sm" title="Sem erro"></i>';
														}else{
															echo '<i class="fa fa-thumbs-down fa-sm" title="Com erro"></i>';
														}
														echo "</span>";
													?>
												</button>
												<!--BOTAO ABRIR PENDENCIA-->
												<button  type="button" class="btn btn-default btn-sm"
													data-toggle="modal" data-target="#modalHistorico"
													href="modal_pendencia_nova.php?id=<?=$varCampo->id;?>"
													<?
														echo " >";
														echo '<i class="fa fa-medkit fa-sm" title="Abrir pendência"></i>';
													?>
												</button>

												<!--BOTAO COMENTÁRIOS REVISOR-->
												<button  type="button" class="btn btn-default btn-sm"
													data-toggle="modal"
													data-target="#modalHistorico"
													href="modal_agenda_comentario_revisor.php?id=<?=$varCampo->id;?>"
													<?
														echo " >";
														echo '<span id="btnComentarioRevisor'.$varCampo->id.'" name="btnComentarioRevisor'.$varCampo->id.'">';
														if(trim($varCampo->comentario_revisor)==""){
															echo '<i class="fa fa-share-square-o fa-sm " title="Comentários do Revisor"></i>';
														}else{
															echo '<i class="fa fa-share-square fa-sm font-green-steel" title="Comentários do Revisor"></i>';
														}
														echo "</span>";
													?>
												</button>
												<?php
												if($_SESSION['user_id']==1){
													?><br>
													<button  type="button" class="btn btn-default btn-sm"
													onclick="javascript:deleta_campo(<?=$varCampo->id;?>);"
													<?
														echo " >";
														echo '<i class="fa fa-trash fa-sm" title="Deletar campo"></i>';
													?>
													<?
												}
												?>
											</div>
									</td>
								  <?
									   }
								}
								  ?>
							  </tr>
							  <?
							  }else{
								  if($varCampo->classTipoCampo->tipo=="escondido"){
											   $acao_botao = $varCampo->classTipoCampo->botao_acao;
											   $nome_escondido = $varCampo->classTipoCampo->nome;
											   if($nome_escondido=="RANDOMIZAR"){$mostra_randomizacao=true;}else{$mostra_randomizacao=false; };
											   if($nome_escondido=="ASSINATURA"){$mostra_assinado=true;}else{$mostra_assinado=false;};
											   echo '<input type="hidden" name="escondido" id="escondido" value="'.$acao_botao.'">';
								  }
								  if($varCampo->classTipoCampo->tipo=="divisao"){    //SE o tipo for número
								  ?>
								  <tr>
									   <td style="height:0.1px !important;background-color:#EEEEEE !important;" colspan="4"><span style="font-size: 1.1em"><b><?=$varCampo->classTipoCampo->texto;?></b></span></td>
								  </tr>
								  <?
								  }
								  if($varCampo->classTipoCampo->tipo=="pagina"){    //SE o tipo for página
								  ?>
								  <tr>
									  <td colspan="4">
										  <iframe frameborder="0"  src="<?=$varCampo->classTipoCampo->pagina."?".gera_parametro("pac",$paciente)."&".gera_parametro("cam",$formularioP);?>" style="width: 100%;height:<?=$varCampo->classTipoCampo->pagina_altura?>px"></iframe>
									  <td>
								  </tr>
								  <?
								  }
								  if($varCampo->classTipoCampo->tipo=="botao"){
								  ?>
									<tr>
									  <td colspan="4" align="center">
										  <button type="button" <?="id='form_".$varCampo->id."' name='form_".$varCampo->id."' "; ?> class="btn btn-default" onclick="javascript:<?=$varCampo->classTipoCampo->botao_acao;?>;"><?=$varCampo->classTipoCampo->texto;?></button>
									  <td>
									</tr>
								  <?
								  }
							  }
						}
					   ?>
						<td colspan="4">
						   <br>
						   <center>
								<?if($varAgendamento->ativo==1){?><button type="button" class="btn btn-default" onclick="javascript:valida_dados();">  Salvar informações </button><?}?>
						   </center>
						</td>
				   </tr>
				   </tbody>
				</table>
				</form>
			</div>
			<?
		}//End tipo de formulario
	}
