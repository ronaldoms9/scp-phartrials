<?php
	include_once "seguranca.php";
	include_once "class/projeto.class.php";
	include_once "class/checklist.class.php";

	$varProjeto = new Projeto($_SESSION['projeto']);
	$varCheckList = new CheckList($_SESSION['projeto']);

?>
<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<meta http-equiv="content-type" content="application/vnd.ms-excel; charset=UTF-8">
<head>
	<?php
		include "global_css.php";
	?>
    <link href="//cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.3.1/css/buttons.dataTables.min.css">
	<link href="https://fonts.googleapis.com/css?family=Abel|Archivo+Narrow|PT+Sans+Narrow:700|Pragati+Narrow" rel="stylesheet">
	<link href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/r/bs-3.3.5/jq-2.1.4,dt-1.10.8/datatables.min.css"/>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.4.2/css/buttons.dataTables.min.css"/>
	<style>
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
			tableFixHead          { overflow: auto; height: 100px; }
			.tableFixHead thead th { position: sticky; top: 0; z-index: 1; }

			/* Just common table stuff. Really. */
			table  { border-collapse: collapse; width: 100%; }
			th, td { padding: 8px 16px; }
			th     { background:#eee; }

    </style>
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
                                        <h1><?=traduzir("ANEXOS", $_SESSION['lang']);?> </h1>
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
                                                            <span class="caption-subject bold uppercase"> VALORES</span>
                                                        </div>
                                                    </div>
                                                    <div class="portlet-body">


															<hr>
															<div class="row">
																<div class="col-md-4 column">
																	<div class="search-filter ">
																		<div class="search-label uppercase"><span class="caption-subject font-green-sharp sbold">CENTRO</span>
																		</div>
																		<div class="input-icon right">
																			<select id="centro" name="centro" class="form-control" onchange="mudouCentro(this.value)">
																				<option value="">SELECIONE O CENTRO</option>
																				<?php
																				$query = "select * from cfg_centro where projeto = '".$_SESSION['projeto']."' order by nome";
																				$res   = $conexao->pesquisa($query);
																				while($row = mysqli_fetch_assoc($res)){
																					?>
																						<option value="<?=$row['id']?>" <?php if($_GET['centro']==$row['id']){echo ' selected ';}?> ><?=$row['nome']?></option>
																					<?php
																				}
																				?>
																			</select>
																		</div>
																	</div>
																</div>
															</div>
															<?php
															if($_GET['centro']!=""){
															?>
																<div class="portlet-body">
																	<div>
																		<div class="tableFixHead">
																			<table class="table table-hover table-striped table-bordered" name="tabela" id="tabela" >
																				<thead>
																					<tr>
																						<th style="font-size: 12px !important;"><center>#<br>Linha</center></th>
																						<?php
																							$query = "select * from agenda_cobranca_fase order by id";
																							$res   = $varConexao->pesquisa($query);
																							while($rowFase = mysqli_fetch_assoc($res)){
																								echo '<th style="font-size: 12px !important;"><center>'.$rowFase['fase'].'</center></th>';
																							}
																						?>
																					</tr>
																				</thead>
																				<tbody id="tabela_retorno" name="tabela_retorno">
																					<div id="tabela" style=" width: 100% !important; overflow-x: auto;    white-space: nowrap;">
																					<?php
																					$query  = "select * from agenda_cobranca_linha order by id";
																					$resLin = $varConexao->pesquisa($query);
																					while($rowLin = mysqli_fetch_assoc($resLin)){
																						?>
																							<tr>
																								<td style="font-size: 10px !important;">
																									<?=$rowLin['nome'];?>
																								</td>
																								<?php
																									$query = "select * from agenda_cobranca_fase order by id";
																									$res   = $varConexao->pesquisa($query);
																									while($rowFase = mysqli_fetch_assoc($res)){
																										$query = "SELECT agenda_cobranca_itens.id,
																													agenda_check.id as idCampo,
																													agenda_check_centro_valor.valor,
																													agenda_check_centro_valor.valor6
																													FROM agenda_cobranca_itens
																													left join agenda_check on agenda_cobranca_itens.campo_id = agenda_check.id
																													left join agenda_check_centro_valor on agenda_check_centro_valor.centro =  agenda_cobranca_itens.centro and agenda_check_centro_valor.campo = agenda_cobranca_itens.id

																													where
																															agenda_cobranca_itens.centro = ".$_GET['centro']."
																															and agenda_cobranca_itens.linha = '".$rowLin['id']."'
																															and agenda_cobranca_itens.fase = '".$rowFase['id']."' ";
																										$resCampo = $varConexao->pesquisa($query);
																										if(mysqli_num_rows($resCampo)>0){
																											$rowCampo = mysqli_fetch_assoc($resCampo);
																											$valor =  $rowCampo['idCampo']."|".$rowCampo['valor']."|".$rowCampo['valor6'];
																										}else{
																											$query = "insert agenda_cobranca_itens (centro, linha, fase) values ('".$_GET['centro']."', '".$rowLin['id']."', '".$rowFase['id']."');";
																											$varConexao->pesquisa($query);
																										}
																									?>
																										<td>
																											<input width="80" type='text' class='form-control' style='text-align:center' onchange='javascvript:mudou(this.value,<?=$rowLin["id"];?>,<?=$rowFase["id"];?>,<?=$rowCampo["id"];?>)'
																											value='<?=$valor;?>'>
																										</td>
																									<?php
																									}
																								?>

																							</tr>
																						<?php
																					}
																					?>
																					</div>
																				</tbody>
																			</table>
																		</div>
																	</div>
																</div>
															<?php
																}
															?>

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
	<?php
		include "rodape.php";
		include "global_js.php";
	?>
	</body>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
	<script type="text/javascript" src="php_js/js_mon_checklist.js"></script>
	<script>
        function mudou(campo,linha,fase,idCampo){
            varUrl = "php_php/php_mon_checklist.php?act=MUDA_VALOR&centro=<?=$_GET['centro'];?>";
            var formData = {varCampo: campo,varLinha: linha,varFase: fase, varIdCampo: idCampo}
			$.ajax({
				url: varUrl,
				type: "POST",
				data : formData,
				async : true,
				beforeSend: function(){
					mostraCarregando();
				},
				success: function(response, textStatus, jqXHR) {
					console.log(response);
					removeCarregando();
				},
				error: function (jqXHR, textStatus, errorThrown) {
					exibe_mensagem("ERRO!", "Erro ao inserir grupo!", "error");
					$.unblockUI();
				}
			});
        }
		function mudouCentro(centro){
			document.location = "mon_fin_valores.php?centro="+centro;
		}
	</script>
</html>