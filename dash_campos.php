<?php
  include_once "seguranca.php";


  $act = strtoupper($_GET['act']);
  if($act=="MONITORADOS"){
	  $label = "CAMPOS <B>AINDA NÃO</B> MONITORADOS ";
	  $filtro = " and crf_agenda_campos.monitoria_status = 0 ";
  }
  if($act=="ERROS"){
	  $label = "CAMPOS <B>COM</B> ERRO ";
	  $filtro = " and crf_agenda_campos.erro_status = 1 ";
  }
  if($act=="BRANCO"){
	  $label = "CAMPOS <B>EM</B> BRANCO ";
	  $filtro = " and ( (crf_agenda_campos.valor IS NULL) or (crf_agenda_campos.valor = '') )  ";
  }
  if($act=="REVISOR"){
	  $label = "CAMPOS <B>COM COMENTÁRIOS DO REVISOR</B> SEM FINALIZAR ";
	  $filtro = " and ( (crf_agenda_campos.comentario_revisor_status = 'ABERTO') or (crf_agenda_campos.comentario_revisor_status = 'FINALIZADO') or (crf_agenda_campos.comentario_revisor_status = 'FECHADO') ) ";
  }

?>
<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
	<?php
		include "global_css.php";
	?>
	<link href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/r/bs-3.3.5/jq-2.1.4,dt-1.10.8/datatables.min.css"/>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.4.2/css/buttons.dataTables.min.css"/>
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
                                        <h1>
											<a href="inicial.php" class="btn btn-circle blue-dark" title="Voltar para o Dashboard" alt="Voltar para o Dashboard"><i class="fa fa-arrow-circle-left"></i></a>
											<?=traduzir($label, $_SESSION['lang']);?> </h1>

                                    </div>
                                </div>
                            </div>
                            <br><br>
							<div class="row">
								<div class="col-md-12 column">
                                                <div class="portlet light ">
                                                    <div class="portlet-title">
                                                        <div class="caption">
                                                            <i class="icon-bubble font-green-sharp"></i>
                                                            <span class="caption-subject font-green-sharp sbold"><?=$label;?> </span>
                                                        </div>
                                                    </div>
                                                    <div class="portlet-body">

														<div class="caixa" style="overflow:auto; height:500px">
															<br><br><br>
															<table class="table table-hover table-striped table-bordered" name="tabela" id="tabela" >
																<thead>
																		<th><center># AGENDAMENTO</center></th>
																		<th>FORMULÁRIO</th>
																		<th>CAMPO</th>
																		<th>Valor atual</th>
																		<th><center>MONITORADO?</center></th>
																		<th><center>CAMPO COM ERRO?</center></th>
																		<th><center>Comentário do revisor?</center></th>
																		<th><center>Status</center></th>
																		<th><center>Ir</center></th>
																</thead>
																<tbody>
																	<?php

																		$query = "SELECT
																						agenda.id,
																						agenda.nro_visita,
																						crf_agenda_forms.id as form_id,
																						cfg_visitas.texto_crf as form_nome,
																						crf_agenda_campos.id as campo_id,
																						cfg_visitas_campos.texto as campo_nome,
																						crf_agenda_campos.valor as campo_valor,
																						crf_agenda_campos.monitoria_status,
																						crf_agenda_campos.erro_status,
																						crf_agenda_campos.comentario_revisor,
																						comentario_revisor_status
																							FROM agenda
																							inner join crf_agenda_forms on agenda.id = crf_agenda_forms.agendamento
																							inner join cfg_visitas on crf_agenda_forms.form_id = cfg_visitas.id
																							inner join crf_agenda_campos on crf_agenda_forms.id = crf_agenda_campos.form_id
																							inner join cfg_visitas_campos on crf_agenda_campos.campo_id = cfg_visitas_campos.id
																						where ( (cfg_visitas_campos.tipo <> 'divisao')
																						and (cfg_visitas_campos.tipo <> 'json')
																						and (cfg_visitas_campos.tipo <> 'json_vertical'))
																							and agenda.projeto = ".$_SESSION['projeto'].$filtro . "
																								order by comentario_revisor_status,agenda.nro_visita";

																		$res1  = $conexao->pesquisa($query);
																		while($row = mysqli_fetch_assoc($res1)){
																			?>
																				<tr>
																					<td><font size="1px"><center><?=$row['nro_visita'];?></center></font></td>
																					<td><font size="1px"><?=$row['form_nome'];?></font></td>
																					<td><font size="1px"><?=$row['campo_nome'];?></font></td>
																					<td><font size="1px"><?=$row['campo_valor'];?></font></td>
																					<td><font size="1px"><center><?php if($row['monitoria_status']=="1"){echo "SIM";}else{echo "NÃO";}?></font></center></td>
																					<td><font size="1px"><center><?php if($row['erro_status']=="1"){echo "SIM";}else{echo "NÃO";}?></centeR></font></td>
																					<td><font size="1px"><?=$row['comentario_revisor'];?></font></td>
																					<td>
																						<center><font size="1px"><?=$row['comentario_revisor_status'];?></font>
																						</center>
																					</td>
																					<td>
																						<font size="1px">
																							<?php
																								$agendamento = $row['id'];
																								$formulario	 = $row['form_id'];

																							?>
																							<a href="agendamentos.php?agendamento=<?=$row['id'];?>&formulario=<?=$row['form_id'];?>&campo=<?=$row['campo_id'];?>"
																								class="btn dark btn-sm btn-outline sbold uppercase"
																								title="Ir até..."><i class="fa fa-address-book-o"></i></a>
																						</font>
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
							</div>
						</div>
						<br><br>
					</div>
				</div>
			</div>
        </div>
		<?php
			include "rodape.php";
			include "global_js.php";
		?>
</body>
	<script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js" type="text/javascript"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.4.2/js/dataTables.buttons.min.js"></script>
	<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
	<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
	<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
	<script type="text/javascript" src="//cdn.datatables.net/buttons/1.4.2/js/buttons.html5.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>

</html>