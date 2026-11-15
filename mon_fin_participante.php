<?
	include_once "seguranca.php";
	include_once "class/projeto.class.php";
	$varProjeto = new Projeto($_SESSION['projeto']);
?>
<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<meta http-equiv="content-type" content="application/vnd.ms-excel; charset=UTF-8">
<head>
	<?
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
                                        <h1><?=traduzir("CONTROLE FINANCEIRO", $_SESSION['lang']);?> </h1>
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
                                                            <span class="caption-subject bold uppercase"> Participantes</span>
                                                        </div>
                                                    </div>
                                                    <div class="portlet-body">
                                                        <div class="scroller" style="height:600px" data-rail-visible="1" data-rail-color="yellow" data-handle-color="#a1b2bd">
															<div class="row">
																<div class="col-md-6 column">

																	<div class="col-md-6 column">
																		<div class="search-filter ">
																			<div class="search-label uppercase"><span class="caption-subject font-green-sharp sbold">Participante</span></div>
																			<div class="input-icon right">																				
																				<select 
																					class="form-control select_pesquisa " 
																					data-original-title="ESCOLHA O PROJETO" 
																					data-container="body" data-placement="left"
																					onchange="javascript:montaGrid(this.value);"
																					>											
																					<?																																											
																						echo "<option value=''>PARTICIPANTE</option>";
																						$query = "select distinct id, numero, codigo, status from
																									agenda_check_participante
																										where projeto = " . $_SESSION['projeto'] . " and periodo = 1 order by numero, codigo ";
																						$resPart = $conexao->pesquisa($query);
																						while($rowPart = mysqli_fetch_assoc($resPart)){
																							$status = $rowPart['status'];
																							if($status=="D"){$status = "[Descontinuado]";}
																							if($status=="F"){$status = "[Randomizado]";}
																							echo '<option value="'.$rowPart['id'].'">'.$rowPart['numero'] . ' - ' . $rowPart['codigo'] .' - ' . $status . '</option>';
																						}
																					?>
																				</select> 
																			</div>
																		</div>
																	</div>																	
																</div>																
															</div>
															<hr>
															<div class="portlet-body">
																<div style="height:500px;
																			overflow: auto;
																			white-space: nowrap" >
																	<div class="tableFixHead">
																		<table class="table table-hover table-striped table-bordered" name="tabela" id="tabela" >
																			<thead>
																				<tr>
																					<th style="font-size: 12px !important;"><center>#<br>Participante</center></th>
																					<th style="font-size: 12px !important;"><center>Código</center></th>
																					<th style="font-size: 12px !important;"><center>Status</center></th>																					
																				</tr>
																			</thead>

																			<tbody id="tabela_retorno" name="tabela_retorno">
																				<tr>
																					<td>Nenhum dado a exibir...</td>
																					<td></td>
																					<td></td>
																				</tr>
																			</tbody>
																		</table>
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
				</div>
			</div>
		</div>
	<?
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
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
	<script>

	</script>
	<script type="text/javascript" src="php_js/js_mon_fin_participante.js"></script>
</html>