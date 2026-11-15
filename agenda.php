<?
	include_once __DIR__."/seguranca.php";
	include_once __DIR__."/class/conexao.class.php";
	include_once __DIR__."/class/agenda.class.php";
	include_once __DIR__."/class/projeto.class.php";
	include_once __DIR__."/class/centro.class.php";
	include_once __DIR__."/class/cep.class.php";

	$conexao = new Conexao();
	$varConexao = new Conexao();
	$varProjeto = new Projeto($_SESSION['projeto']);

	if(isset($_GET['act'])){$act = $_GET['act'];}else{ $act = ""; }
	$id_usuario = $_SESSION['user_id'];
	$log_usuario = $_SESSION['user_id'];
	$qtd = 0;


	if($act=="I"){
		$centro      		= $_POST['form_centro'];
		$varCentro = new CentroPesquisa($centro);

		$monitora 			= $_POST['form_monitora'];
		$nro_visita 		= $_POST['form_nro_visita'];
		$data      			= $_POST['form_data'];
		$datafinal 			= $_POST['form_datafinal'];
		$data_caption 		= $data;
		if($datafinal!=$data){$data_caption = $data . " à " . $datafinal;}
		$hora      			= $_POST['form_hora'];

		$atividades      	= nl2br(trim($_POST['form_atividades']));
		$presentes      	= nl2br(trim($_POST['form_presentes']));
		$tipo 				= $_POST['form_tipo_monitoria'];
		$data 				= explode("/",$data);
		$data 				= $data[2].$data[1].$data[0];
		$contato				= $_POST['form_contato'];

		$datafinal 			= explode("/",$datafinal);
		$datafinal 			= $datafinal[2].$datafinal[1].$datafinal[0];

		$revisor = $_POST['form_revisor'];
		$titulo = $_POST['form_titulo'];
		$grupo = $_POST['form_grupo'];
		$periodo = $_POST['form_periodo'];

		$query = "select * from agenda where projeto = ".$_SESSION['projeto']." and nro_visita = $nro_visita and centro = '".$centro."'";
		$res = $conexao->pesquisa($query);
		//Insiro o registro do agendamento propriamente dito
		$query = "insert into agenda (datafinal, versao, periodo, grupo_periodo, titulo, revisor, monitora, data_caption, data, centro, nro_visita, hora,
										atividades, presentes, tipo_monitoria, log_usuario, projeto, rel_enviado_data, rel_enviado_quem, rel_respondido, rel_enviado,
										horas, hora_fim, contato)
					values ('$datafinal', '1', '$periodo', '$grupo', '$titulo', '$revisor', '$monitora', '$data_caption', '$data', '$centro', '$nro_visita', '$hora','$atividades', '$presentes', '$tipo', '$id_usuario', '".$_SESSION['projeto']."', '', '', '0', '0', '', '','$contato')";
		$conexao->pesquisa($query);
		$query = "select id from agenda where  projeto = ".$_SESSION['projeto']." and nro_visita = $nro_visita and centro = '".$centro."'";		;
		$res = $conexao->pesquisa($query);
		if(mysqli_num_rows($res)>0){
			$row_visita = mysqli_fetch_assoc($res);
			$idVisita = $row_visita['id'];
			$query = "select * from cfg_visitas where tipo = '".$tipo."' and registro_valido = 1 order by ordem";
			$res_forms = $conexao->pesquisa($query);
			while($row_forms = mysqli_fetch_assoc($res_forms)){
				$query = "insert into crf_agenda_forms (agendamento, form_id, log_usuario) values (".$row_visita['id'].", ".$row_forms['id'].", '$id_usuario')";
				$conexao->pesquisa($query);
				$query = "select id from crf_agenda_forms where agendamento = ".$row_visita['id']." and form_id = " . $row_forms['id'];
				$res_forms_new = $conexao->pesquisa($query);
				$row_forms_new = mysqli_fetch_assoc($res_forms_new);
				$query = "select * from cfg_visitas_campos where formulario = ".$row_forms['id']." order by ordem";
				$res_campo = $conexao->pesquisa($query);
				while($row_campo = mysqli_fetch_assoc($res_campo)){
					$varCampoPre = $row_campo['campo_preenchido'];
					if($varCampoPre!=""){
						$varCampoPre = explode(";",$varCampoPre);
						switch($varCampoPre[0]){
							case "cfg_centro":
								$queryCampoPre = "select " . $varCampoPre[1] . " from " . $varCampoPre[0];
								$queryCampoPre .= " where id = " . $centro;
								$res_campo_pre = $conexao->pesquisa($queryCampoPre);
								$row_campo_pre = mysqli_fetch_assoc($res_campo_pre);
								$varCampoPre =  $row_campo_pre[$varCampoPre[1]];
								break;
							case "cad_cep":
								$campoAprocurar = "";
								$campoAprocurar = $varCampoPre[1];
								//Já tenho o CENTRO. vou pegar o CEP
								$varCep = new CEP($varCentro->cep);
								$varCampoPre = $varCep->$campoAprocurar;
								break;
						}

					}
					$varValor = $row_campo['padrao'];
					if($varCampoPre!=""){
						$varValor = $varCampoPre;
					}
					/*if($varValor==""){
						$varValor = $row_campo['valor_padrao'];
					}*/
					$query = "insert into crf_agenda_campos (form_id, campo_id, log_usuario, valor) values (".$row_forms_new['id'].", ".$row_campo['id'].", '$log_usuario', '".$varValor."');";
					$conexao->pesquisa($query);
				}
			}
		}
    }

?>
<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<?
		include "global_css.php";
	?>
	<style>
		@media (min-width: 1200px) {
			.modal-xl {
				width: 90%;
				max-width:1400px;
			}
		}
	</style>
	<link rel="shortcut icon" href="favicon.png">
    <script >
		<?
			if(isset($msg)){echo $msg;}
		?>
    	function excluir(entrada){
			document.location = "agenda.php?act=D&id="+entrada;
    	}
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
		function popup(url){
			params  = 'width=700';
			params += ', height=500';
			params += ', top=0, left=0'
			params += ', fullscreen=yes';
			newwin=window.open(url,'windowname4', params);
			if (window.focus) {newwin.focus()}
			return false;
		}
    </script>
	<style>
		.scrollable-menu {
			height: auto;
			max-height: 200px;
			overflow-x: hidden;
			width: 300px;
		}
	</style>
</head>
<body>

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
                                        <h1><?=traduzir("Visitas de monitoria", $_SESSION['lang']);?> </h1>
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
                                            <span><?=traduzir("Visitas de monitoria", $_SESSION['lang']);?></span>
                                        </li>
                                    </ul>
                                </div>
								<br>
								<div class="row" style="background-color:white">
									<div class="col-md-1"></div>
									<div class="col-md-10">
										<div class="custom-tabs-line tabs-line-bottom">
											<ul class="nav nav-tabs">
												<?
													if(isset($_GET['volta'])){$volta = $_GET['volta'];}else{$volta="";}
												?>
												<li
												 <?if( ($volta=="listagem")||($volta=="") ){echo ' class="active" ';} ?>
												 ><a href="#tab-top2" role="tab" data-toggle="tab"> <?=traduzir("Listagem de Visitas", $_SESSION['lang']);?></a></li>
												 <?
												 //if( ($_SESSION['tipo_usuario']!=5)&&($_SESSION['seg_edita']=="ON") ){
												 ?>
												 <li><a href="#tab-top3" role="tab" data-toggle="tab"> <?=traduzir("Nova visita", $_SESSION['lang']);?></a></li>
												 <?
												 //}
												 ?>
											</ul>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="tab-content">
										<div class="tab-pane
										<?if(($volta=="listagem")||($volta=="")){echo ' active';} ?>
										" id="tab-top2">
											<div class="row">
											<br>

												<div class="col-md-12">
													<div class="col-md-12">
														<div class="portlet light portlet-fit ">
															<div class="portlet-title">
																<div class="caption">
																	<i class="icon-bubble font-dark"></i>
																	<span class="caption-subject font-dark bold uppercase">Listagem de visitas</span>
																</div>
																<div class="actions">
																	<div class="btn-group">
																		<a class="btn dark btn-outline btn-circle btn-sm" href="#" data-toggle="dropdown" data-hover="dropdown" data-close-others="true"> Exportar
																			<i class="fa fa-angle-down"></i>
																		</a>
																		<ul class="dropdown-menu pull-right">
																			<li>
																				<a href="javascript:exporta_tabela_excel('agenda_listagem');"> Excel</a>
																			</li>
																			<li>
																				<a href="javascript:retornaLista();"> Atualizar</a>
																			</li>
																		</ul>
																	</div>
																	<div class="btn-group">
																		<a class="btn dark btn-outline btn-circle btn-sm"
																				href="javascript:retornaLista();"> Atualizar
																			<i class="fa fa-refresh" aria-hidden="true"></i>
																		</a>

																	</div>
																</div>
															</div>
															<div class="portlet-body">
																<div class="row" style="text-align: right">
																	<input type="text" id="myInput" onkeyup="myFunction()" placeholder="Digite para pesquisar...">
																</div>
																<!--<div class="scroller" style="height:400px" data-rail-visible="1" data-rail-color="yellow" data-handle-color="#a1b2bd">-->
																	<div class="table-scrollable">
																		<table class="table table-bordered table-hover" id="agenda_listagem" name="agenda_listagem">
																			<thead style="cursor: pointer;">
																				<tr>
																					<th></th>
																					<th style="vertical-align: middle;" onclick="ordena(1);"> Centro <br><br></th>
																					<th style="vertical-align: middle;" onclick="ordena(2);"><center>#Visita</center></th>
																					<th style="vertical-align: middle;" onclick="ordena(2);"><center>Versão</center></th>
																					<th style="vertical-align: middle;" onclick=""><center>Período/Grupo</center></th>
																					<th style="vertical-align: middle;" onclick="ordena(3);">Tipo de monitoria</th>
																					<th style="vertical-align: middle;" onclick="ordena(5);"><center>Data Inicial<br>Data Final</center></th>
																					<th style="vertical-align: middle; display: none"></th>
																					<th style="vertical-align: middle;" onclick="ordena(7);">Monitor /<br>Revisor <br><br></th>
																					<th style="vertical-align: middle;" ><center>Revisor<br>alertado?</center><br></p></th>
																					<th style="vertical-align: middle;" ><center>Revisão<br>Finalizada</center><br></p></th>
																					<th style="vertical-align: middle;" ><center>Enviado<br>ao patrocinador?</center><br></p></th>
																					<!--<th style="vertical-align: middle;" ><center>Patrocinador<br>assinou?</center><br></p></th>-->
																					<th style="vertical-align: middle;">Açoes</th>
																				</tr>
																			</thead>
																			<tbody id="agenda_listagem_retorno">
																			</tbody>
																		</table>
																	</div>
																<!--</div>-->
															</div>
														</div>
													</div>
												</div>
										   </div>
										</div>
										<div class="tab-pane " id="tab-top3">
											<div class="row">
												<br>
												<div class="col-md-2"></div>
												<div class="col-md-8">
													<div class="portlet light">
														<div class="portlet-title">
															<div class="caption">
																<i class="icon-paper-plane font-green-sharp"></i>
																<span class="caption-subject bold font-green-sharp uppercase"> Inserir nova visita </span>
															</div>
														</div>
														<div class="portlet-body form">
															<form autocomplete="off" class="form-horizontal form-bordered" role="form" action="agenda.php?act=I" method="post" id="formulario" name="formulario">
																<input autocomplete="false" name="hidden" type="text" style="display:none;">
																<div class="form-body">
																	<div class="form-group">
																		<label class="control-label col-md-3">Centro</label>
																		<div class="col-md-4">
																			<select required id="form_centro" name="form_centro" class="form-control" onchange="traz_prox_visita(this.value);">
																				<option value=""></option>
																			  <?
																				  $query = "select id, nome from cfg_centro where numero <> 'CRO' and projeto = ".$_SESSION['projeto']." order by nome";
																				  $res = $conexao->pesquisa($query);
																				  while($row = mysqli_fetch_assoc($res) ){
																					  echo "<option value='".$row['id']."'>".$row['nome']."</option>";
																				  }
																			  ?>
																			</select>
																		</div>
																	</div>

																	<div class="form-group">
																		<label class="control-label col-md-3"># Visita</label>
																		<div class="col-md-4">
																			<input id="form_nro_visita" readonly name="form_nro_visita" type="text" placeholder="" class="form-control input-md" required="" <?=$centralizado;?>>
																			<span class="help-block"> (Númeraçao automática) </span>
																		</div>
																	</div>
																	<div class="form-group">
																		<label class="control-label col-md-3">Tipo de visita</label>
																		<div class="col-md-4">
																			<select id="form_tipo_monitoria" name="form_tipo_monitoria" class="form-control" onchange="mudou_tipo(this.value)">
																				<option value=""></option>
																				<?
																				  $query = "select
																				  				* from cfg_visitas_tipo
																								where ativo = 1
																									order by ordem, tipo";
																				  $resTipo   = $conexao->pesquisa($query);
																				  while($rowTipo = mysqli_fetch_assoc($resTipo)){
																					echo '<option value="'.$rowTipo['tipo'].'" ';
																					echo '>'.$rowTipo['tipo'].'</option>';
																				  }
																				?>
																			</select>
																		</div>
																	</div>

																	<div class="form-group">
																		<label class="control-label col-md-3">Data Inicial</label>
																		<div class="col-md-4">
																			<input id="form_data" name="form_data" type="text" placeholder="" class="form-control input-md" required="" <?=$centralizado;?> onkeyup="mascara(this, '##/##/####');" autocomplete="off">
																		</div>
																	</div>
																	<div style="display: none;">
																		<div class="form-group">
																			<label class="control-label col-md-3">Data Final</label>
																			<div class="col-md-4">
																				<input id="form_datafinal" name="form_datafinal" type="text" placeholder="" class="form-control input-md" required="" <?=$centralizado;?> onkeyup="mascara(this, '##/##/####');" autocomplete="off">
																			</div>
																		</div>
																	</div>
																	<div class="form-group">
																		<label class="control-label col-md-3">Período</label>
																		<div class="col-md-4">
																			<select id="form_periodo" name="form_periodo" class="form-control input-md" required="">
																				<option value=""></option>
																				<!--<option value="Inicial">Inicial</option>-->
																				<?
																					for($i=1; $i<=9; $i++){
																						echo '<option value="'.$i.'">'.$i.'</option>';
																					}
																				?>
																				<option value="Pos-Estudo">Pos-Estudo</option>
																			</select>
																		</div>
																	</div>
																	<div class="form-group">
																		<label class="control-label col-md-3">Grupo</label>
																		<div class="col-md-4">
																			<input id="form_grupo" name="form_grupo" type="text" placeholder="" class="form-control input-md" <?=$centralizado;?> autocomplete="off">
																		</div>
																	</div>
																	<div style="display: none;">
																		<div class="form-group">
																			<label class="control-label col-md-3">Período previsto</label>
																			<div class="col-md-4">
																				<input
																					id="form_hora"
																					name="form_hora"
																					type="text" placeholder=""
																					class="form-control input-md" <?=$centralizado;?>>
																				<span class="help-block"> Ex: 08:00 às 12:00 </span>
																			</div>
																		</div>
																	</div>
																	<div class="form-group" style="display: none;">
																		<label class="control-label col-md-3">Título no relatório</label>
																		<div class="col-md-9">
																			<textarea id="form_titulo" name="form_titulo" class="form-control input-md" rows="5"></textarea>
																		</div>
																	</div>
																	<div class="form-group">
																		<label class="control-label col-md-3">Monitor</label>
																		<div class="col-md-4">
																			<select id="form_monitora" name="form_monitora" class="form-control" required="" onchange="escolheDadosMonitor(this.value);">
																				<option value=""></option>
																				<?
																					$query = "select id, nome from usu_usuario where
																								tipo_usuario in (1,2,6,11,12)
																							and registro_valido = 1
																							order by nome";
																					//$query = "select id, nome from usu_usuario order by nome";
																					$res_mon = $conexao->pesquisa($query);
																					while($row_mon = mysqli_fetch_assoc($res_mon)){
																						echo '<option value="'.$row_mon['id'].'">'.$row_mon['nome'].'</option>';
																					}
																				?>
																			</select>
																		</div>
																	</div>
																	<div class="form-group">
																		<label class="control-label col-md-3">Contato do monitor</label>
																		<div class="col-md-4">
																			<input id="form_contato" name="form_contato" type="text" placeholder="" class="form-control input-md" <?=$centralizado;?>>
																			<span class="help-block"> Ex: (62) 8258-9783 e monitoria@phartrials.com.br </span>
																		</div>
																	</div>
																	<div class="form-group">
																		<label class="control-label col-md-3">Revisor</label>
																		<div class="col-md-4">
																			<select id="form_revisor" name="form_revisor" class="form-control" required="">
																				<option value=""></option>
																				<?
																					//$query = "select id, nome from usu_usuario where tipo_usuario = 2 order by nome";
																					//$query = "select id, nome from usu_usuario order by nome";
																					$query = "select id, nome from usu_usuario where
																							tipo_usuario in (1,2,6,11,12)
																							and registro_valido = 1
																							order by nome";
																					$res_mon = $conexao->pesquisa($query);
																					while($row_mon = mysqli_fetch_assoc($res_mon)){
																						echo '<option value="'.$row_mon['id'].'">'.$row_mon['nome'].'</option>';
																					}
																				?>
																			</select>
																		</div>
																	</div>
																	<div style="display: none;" id="divAtividades">
																		<div class="form-group">
																			<label class="control-label col-md-3">Atividades</label>
																			<div class="col-md-9">
																				<textarea name="form_atividades" id="form_atividades" class="form-control" rows="6"></textarea>
																			</div>
																		</div>
																	</div>
																	<div id="divPresentes">
																		<div class="form-group">
																			<label class="control-label col-md-3">Presentes / Participantes</label>
																			<div class="col-md-9">
																				<textarea name="form_presentes" id="form_presentes" class="form-control" rows="6"></textarea>
																			</div>
																		</div>
																	</div>
																	<div id="divRelatorio" style="display: none;">
																		<div class="form-group">
																			<label class="control-label col-md-3">1. OBJETIVO</label>
																			<div class="col-md-9">
																				<textarea name="form_rel_pre_objetivo" id="form_rel_pre_objetivo" class="form-control" rows="9"></textarea>
																			</div>
																		</div>
																		<div class="form-group">
																			<label class="control-label col-md-3">2.1. TIPO DE DOCUMENTO</label>
																			<div class="col-md-9">
																				<textarea name="form_rel_pre_tipo_documento" id="form_rel_pre_tipo_documento" class="form-control" rows="9"></textarea>
																			</div>
																		</div>
																		<div class="form-group">
																			<label class="control-label col-md-3">3.1. CHECKLIST DE REVISÃO</label>
																			<div class="col-md-9">
																				<textarea name="form_rel_pre_checklist" id="form_rel_pre_checklist" class="form-control" rows="9"></textarea>
																			</div>
																		</div>
																		<div class="form-group">
																			<label class="control-label col-md-3">3.2. APONTAMENTOS DE ACHADOS GERAIS</label>
																			<div class="col-md-9">
																				<textarea name="form_rel_pre_apontamentos" id="form_rel_pre_apontamentos" class="form-control" rows="9"></textarea>
																			</div>
																		</div>
																	</div>
																</div>
																<div class="form-actions">
																	<div class="row">
																		<div class="col-md-12">
																			<div class="row">
																				<div class="col-md-offset-3 col-md-9">
																					<button type="submit" class="btn green" id="btnCadastrarVisita" onclick="javascript:valida();">
																						<i class="fa fa-check"></i> Cadastrar visita</button>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
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
		</div>
	</body>
		<?
			include "rodape.php";
			include "global_js.php";
		?>
		<script src="php_js/js_agenda.js"></script>
		<script src="js/moment.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</html>
		<div class="modal fade" id="modalDatas" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-lg">
			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel">Modal title</h4>
			  </div>
			  <div class="modal-body">
				  <a class="custom-close"> My Custom Close Link </a>
			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary">Save changes</button>
			  </div>
			</div>
		  </div>
		</div>
		<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-lg">
			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel">Modal title</h4>
			  </div>
			  <div class="modal-body">
				  <a class="custom-close"> My Custom Close Link </a>
			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary">Save changes</button>
			  </div>
			</div>
		  </div>
		</div>
		<div id="modalData" name="modalData" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
		</div>
		<div id="staticModal" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title">Data da carta</h4>
					</div>
					<div class="modal-body">
						<p> Por favor, confirme a data de emissão da carta </p>
					</div>
					<div class="modal-footer">
						<button type="button" data-dismiss="modal" class="btn dark btn-outline">Cancel</button>
						<button type="button" data-dismiss="modal" class="btn green">Continue Task</button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="modalHistorico" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-lg">
			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel">Modal title</h4>
			  </div>
			  <div class="modal-body">
				  <a class="custom-close"> My Custom Close Link </a>
			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Fechar (SEM SALVAR)</button>
			  </div>
			</div>
		  </div>
		</div>
		<div class="modal fade " id="modalComentarios" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-lg">
			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel">Comentários do revisor</h4>
			  </div>
			  <div class="modal-body">
				  <a class="custom-close"> My Custom Close Link </a>
			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Fechar (SEM SALVAR)</button>
			  </div>
			</div>
		  </div>
		</div>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
	$(document).ready(function () {<?=geraDate("form_data");?>});
	$(document).ready(function () {<?=geraDate("form_datafinal");?>});
	function myFunction(){
		// Declare variables
	  var input, filter, table, tr, td, i, txtValue;
	  input = document.getElementById("myInput");
	  filter = input.value.toUpperCase();
	  table = document.getElementById("agenda_listagem");
	  tr = table.getElementsByTagName("tr");

	  // Loop through all table rows, and hide those who don't match the search query
	  for (i = 0; i < tr.length; i++) {
		td = tr[i].getElementsByTagName("td")[7];
		if (td) {
		  txtValue = td.textContent || td.innerText;
		  if (txtValue.toUpperCase().indexOf(filter) > -1) {
			tr[i].style.display = "";
		  } else {
			tr[i].style.display = "none";
		  }
		}
	  }
	}
	function excluir_agendamento(id){
		swal.fire({
		  title: 'Tem certeza?',
		  text: "Tem certeza que deseja DELETAR esse agentamento?",
		  icon: 'warning',
		  showCancelButton: true,
		  confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  confirmButtonText: 'Sim, deletar! [CUIDADO]',
		  cancelButtonText: 'CANCELAR',
		}).then((result) => {
		  if (result.value) {
			deletar(id);
		  }
		})
	}
	function deletar(id){
		if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		//document.getElementById("form_nro_visita").innerHTML = "Pesquisando....";
		xmlhttp = new XMLHttpRequest();
		} else {
			// code for IE6, IE5
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				//document.getElementById("form_nro_visita").value = this.responseText;
				document.location = "agenda.php?volta=listagem";
			}
		};
		xmlhttp.open("POST","ajax.php?act=AGENDA_DELETAR&id="+id);
		xmlhttp.send();
	}
	function imprimir_branco(id){ popup("rep_visita_vazio.php?id=" + id);}
	function imprimir_branco_siv(id){ popup("rep_quali_vazio.php?id=" + id);}
	function imprimir_branco_inic(id){ popup("rep_inic_vazio.php?id=" + id);}
	function imprimir_tabela_pendencia(id){
		var centro = prompt("Qual o centro a imprimir?", "Escolha o centro");
		popup("rep_pendencias.php?id=" + id + "&centro="+centro);
	}
	function imprimir_carta_visita(id){
		var data = prompt("Informe a data a ser exibido", "");
		popup("rep_carta_visita.php?id=" + id + "&data="+data);
	}
	function imprimir_fup(id){
		var data = prompt("Informe a data a ser exibido", "");
		popup("rep_fup.php?id=" + id + "&data="+data);
	}
	function imprimir_carta_iniciacao(id){	 popup("rep_carta_iniciacao.php?id=" + id);}
	function imprimir_rm(id){
		var data = prompt("Informe a data de assinatura", "");
		popup("rep_rm.php?id=" + id + "&data="+data);
	}
	function chama_pagina(relatorio, id, data){popup(relatorio+"?id="+id+"&data="+data);}
	function traz_prox_visita(centro){
		if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		document.getElementById("form_nro_visita").innerHTML = "Pesquisando....";
		xmlhttp = new XMLHttpRequest();
		} else {
			// code for IE6, IE5
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				document.getElementById("form_nro_visita").value = this.responseText;
			}
		};
		xmlhttp.open("POST","ajax.php?act=TRAZ_PROX_VISITA&centro="+centro);
		xmlhttp.send();
	};
	function valida(){
		document.getElementById("btnCadastrarVisita").disabled = true;
		function mostraCarregando(){$.LoadingOverlay("show");}
		var processa = true;
		var campo = document.getElementById('form_monitora').value;
		if(campo==""){exibe_mensagem("Atenção", "É obrigatório o preenchimento do campo monitora", "error");processa=false;}
		var campo = document.getElementById('form_data').value;
		if(campo==""){exibe_mensagem("Atenção", "É obrigatório o preenchimento do campo data", "error");processa=false;}
		if(processa==true){document.formulario.submit();}else{document.getElementById("btnCadastrarVisita").disabled = false;}

	}
	function fechar(){
		$('#myModal').modal('hide');
		$('.modal-backdrop').remove();
	}
	function ordena(coluna){
		var table, rows, switching, i, x, y, shouldSwitch;
		table = document.getElementById("agenda_listagem");
		switching = true;
		while (switching) {
			switching = false;
			rows = table.rows;
			for (i = 1; i < (rows.length - 1); i++) {
				shouldSwitch = false;
				x = rows[i].getElementsByTagName("TD")[coluna];
				y = rows[i + 1].getElementsByTagName("TD")[coluna];
				if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
					shouldSwitch = true;
					break;
				}
			}
			if (shouldSwitch) {
				rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
				switching = true;
			}
		}
	}
	function muda_check(id, valor, agenda){
			varValor = 0;
			if(valor == true){ varValor = 1; }
			if(valor == false){ varValor = 0; }
			varRetorno = document.getElementById("check_"+id);
			if (window.XMLHttpRequest) {
				varRetorno.innerHTML = '<img class="icone" src="imagens/carregando.gif" width="30">'
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					varRetorno.innerHTML = this.responseText;
				}
			};
			xmlhttp.open("POST","ajax.php?act=MUDA_CHECKLIST&campo="+id+"&valor="+varValor+"&agenda="+agenda);
			xmlhttp.send();

	}
	function agendaEdita(){
		varUrl = "php_php/php_agenda.php?act=EDITA_AGENDAMENTO";
		var formData = {varId 		: document.getElementById("form_edit_id").value,
						varCentro	: document.getElementById("form_edita_centro").value,
						varVersao	: document.getElementById("form_edita_versao").value,
						varGrupo 	: document.getElementById("form_edita_grupo_periodo").value,
						varPeriodo	: document.getElementById("form_edita_periodo").value,
						varDataIni  : document.getElementById("form_edita_data").value,
						varDataFim  : document.getElementById("form_edita_datafinal").value,
						varHora		: document.getElementById("form_edita_hora").value,
						varMonitor  : document.getElementById("form_edita_monitor").value,
						varRevisor	: document.getElementById("form_edita_revisor").value,
						varTitulo	: document.getElementById("form_edita_titulo").value,
						varAtividade: document.getElementById("form_edita_atividades").value,
						varPresentes: document.getElementById("form_edita_presentes").value,
						varContato	: document.getElementById("form_edita_contato").value,
						varDataImpressao: document.getElementById("form_edita_data_impressao").value
						/*,
						varRelObjetivo 	: document.getElementById("form_modal_rel_pre_objetivo").value,
						varRelTipo 		: document.getElementById("form_modal_rel_pre_tipo_documento").value,
						varRelChecklist : document.getElementById("form_modal_rel_pre_checklist").value,
						varRelApontamento : document.getElementById("form_modal_rel_pre_apontamentos").value*/
						}
		$.ajax({
			url: varUrl,
			type: "POST",
			data : formData,
			async : true,
			success: function(response, textStatus, jqXHR) {
				$('#myModal').modal('hide');
				retornaLista();
				alert("Agendamento editado com sucesso!");

			},
			error: function (jqXHR, textStatus, errorThrown) {
				alert("Erro no processo");
			}
		});

	}
	function novaVersao(id){
		swal.fire({
		  title: 'Confirmação?',
		  text: "Tem certeza que quer gerar uma nova versão?",
		  icon: 'warning',
		  showCancelButton: true,
		  confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  confirmButtonText: 'Sim, gerar uma nova versão!',
		  cancelButtonText: 'Cancelar',
		}).then((result) => {
		  if (result.value) {

			mostraCarregando();
			var motivo = "";
			while(motivo == ""){
				motivo = prompt("Informe o motivo da nova versão", "");
				if(motivo == null){
					alert("Motivo não informado");
					removeCarregando();
					return;
				}
			}
			varUrl = "php_php/php_agenda.php?act=NOVA_VERSAO";
			var formData = {varId	:id, varMotivo: motivo}
			$.ajax({
				url: varUrl,
				type: "POST",
				data : formData,
				async : false,
				success: function(response, textStatus, jqXHR) {
					removeCarregando();
					document.location = document.location;
				},
				error: function (jqXHR, textStatus, errorThrown) {
					removeCarregando();
					alert("Erro no processo");
				}
			});
		  }
		})
	}
	function escolheDadosMonitor(id){
		if(id!=""){
			//Buscar dados do monitor
			varUrl = "php_php/php_agenda.php?act=BUSCAR_DADOS_MONITOR";
			var formData = {varId : id}
			mostraCarregando();
			$.ajax({
				url: varUrl,
				type: "POST",
				data : formData,
				async : false,
				success: function(response, textStatus, jqXHR) {
					removeCarregando();
					console.log("Resposta: " + response);
					let obj = JSON.parse(response);
						document.getElementById("form_contato").value = obj.celular + " e " + obj.email;

				},
				error: function (jqXHR, textStatus, errorThrown) {
					removeCarregando();
					alert("Erro no processo");
				}
			});
		}
	}
	function EnviaEmailRevisor(agendamento){
		swal.fire({
		  title: 'Confirmação?',
		  text: "Confirma o envio do alerta ao revisor?",
		  icon: 'warning',
		  showCancelButton: true,
		  confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  confirmButtonText: 'Sim, enviar!',
		  cancelButtonText: 'Cancelar',
		}).then((result) => {
		  if (result.value) {
			$.blockUI({ message: '<img src="imagens/carregando.gif" width="100">Aguarde, enviando email...', css: {
				border: 'none', padding: '15px', backgroundColor: '#000', '-webkit-border-radius': '10px', '-moz-border-radius': '10px', opacity: .5, color: '#fff'
			} });
			varUrl = "php_php/php_agenda.php?act=ENVIAR_EMAIL";
			var formData = {id: agendamento}
			$.ajax({
				url: varUrl,
				type: "POST",
				data : formData,
				async : true,
				success: function(response, textStatus, jqXHR) {
					exibe_mensagem("Sucesso!", "Email enviado com sucesso!", "success");
					document.getElementById(agendamento+"_alertado").innerHTML = response;
					$.unblockUI();
				},
				error: function (jqXHR, textStatus, errorThrown) {
					exibe_mensagem("ERRO!", "Erro ao enviar email para o Revisor!", "error");
					$.unblockUI();
				}
			});
		  }
		})
	}
	function EnviaEmailTodos(agendamento){
		swal.fire({
		  title: 'Confirmação!',
		  text: "Confirma o envio do alerta sobre o término da revisão?",
		  icon: 'warning',
		  showCancelButton: true,
		  confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  confirmButtonText: 'Sim, enviar!',
		  cancelButtonText: 'Cancelar',
		}).then((result) => {
		  if (result.value) {
			$.blockUI({ message: '<img src="imagens/carregando.gif" width="100">Aguarde, enviando email...', css: {
				border: 'none', padding: '15px', backgroundColor: '#000', '-webkit-border-radius': '10px', '-moz-border-radius': '10px', opacity: .5, color: '#fff'
			} });

			varUrl = "php_php/php_agenda.php?act=ENVIAR_EMAIL_TERMINO";
			var formData = {id: agendamento}
			$.ajax({
				url: varUrl,
				type: "POST",
				data : formData,
				async : true,
				success: function(response, textStatus, jqXHR) {
					console.log(response);
					exibe_mensagem("Sucesso!", "Email enviado com sucesso!", "success");
					document.getElementById(agendamento+"_revisao_ok").innerHTML = response;
					$.unblockUI();
				},
				error: function (jqXHR, textStatus, errorThrown) {
					exibe_mensagem("ERRO!", "Erro ao cadastrar o centro!", "error");
					$.unblockUI();
				}
			});
		  }
		})
	}
	function retornaLista(){
		mostraCarregando();
		document.getElementById("agenda_listagem_retorno").innerHTML = "<img src='imagens/carregando.gif' width='120'>";
		varUrl = "php_php/php_agenda.php?act=LISTAGEM";
		var formData = {}
		$.ajax({
			url: varUrl,
			type: "POST",
			data : formData,
			async : true,
			success: function(response, textStatus, jqXHR) {
				document.getElementById("agenda_listagem_retorno").innerHTML = response;
				removeCarregando();
			},
			error: function (jqXHR, textStatus, errorThrown) {
				alert("Erro no processo");
			}
		});
		removeCarregando();
	}
	function mudou_tipo(tipo){

		//Mostro ou oculto de acordo com o tipo
		document.getElementById("divAtividades").style.display = 'none';
		document.getElementById("divPresentes").style.display = 'none';
		document.getElementById("divRelatorio").style.display = 'none';

		if(tipo=="Relatorio"){
			document.getElementById("divRelatorio").style.display = 'block';
			<?php
				$descricao = trim(preg_replace('/\s+/', ' ', $varProjeto->descricao));
			?>
			document.getElementById("form_rel_pre_objetivo").value = 'Este relatório resume os achados durante o processo de revisão de Relatórios de Estudo e abre espaço para o autor do documento efetuar correções, esclarecimentos ou justificativas sobre esses achados.\nA revisão se assenta primordialmente em uma visão técnico-científica, e seguindo a resolução da ANVISA RESOLUÇÃO-RE Nº 895, DE 29 DE MAIO DE 2003.\nA presente revisão se refere apenas ao Relatório de Validação Analítica do estudo abaixo.\nDeclaramos que o Protocolo de Estudo foi revisado de acordo com os Procedimentos da PHARTRIALS, à luz da legislação vigente no Brasil - ANVISA e seguindo as principais diretrizes de escrita médica (Declaração SPIRIT) e Boas Práticas Clínicas.';
			document.getElementById("form_rel_pre_tipo_documento").value = 'RELATÓRIO DE ESTUDO XXXXXXX -<?=$varProjeto->numero;?> \nPROTOCOLO DE ESTUDO: <?=$descricao;?>';
			document.getElementById("form_rel_pre_checklist").value = 'O checklist baseado no roteiro da resolução da ANVISA: RESOLUÇÃO-RE Nº 895, DE 29 DE MAIO DE 2003, foi aplicado ao documento revisado como uma primeira verificação de aderência e indica o nível de conformidade da estrutura do documento.\n\nDurante o processo de revisão foram encontradas inconsistências e apontadas para correção e/ou esclarecimentos por parte do autor do documento. Os itens identificados são mostrados nas tabelas a seguir que deverá ser respondida pelo Centro neste mesmo documento, através do recurso “Comentários” do leitor de pdf em cada item.';
			document.getElementById("form_rel_pre_apontamentos").value = 'Durante o processo de revisão foram encontradas inconsistências e apontadas para correção e/ou esclarecimentos por parte do autor do documento. Os itens identificados são mostrados na tabela a seguir que deverá ser preenchida pelo Centro';

		}else{
			document.getElementById("divRelatorio").style.display = 'none';
			document.getElementById("divAtividades").style.display = 'none';
			document.getElementById("divPresentes").style.display = 'block';
		}


	}
	$(document).ready(function () {retornaLista();});
</script>

</html>