<?php
	include_once "seguranca.php";
	include_once "class/projeto.class.php";
	$varProjeto = new Projeto($_SESSION['projeto']);
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
                                        <h1><?=traduzir("CONTROLE FINANCEIRO", $_SESSION['lang']);?> </h1>
                                    </div>
                                </div>
                            </div>
                            <div class="page-content">
                                <div class="container-fluid">
									<div class="page-content-inner">
										<div class="row widget-row">
											<div class="col-md-1"></div>
											<div class="col-md-10">                                                
												<div class="portlet light ">
													<div class="portlet-title">
														<div class="caption">
															<i class="icon-bubble font-green-sharp"></i>
															<span class="caption-subject font-green-sharp sbold">Espelhos de Cobrança </span>
														</div>	
														<div class="actions">
															<div class="btn-group">
																<a class="btn green-haze btn-outline btn-circle btn-sm" href="javascript:;" data-toggle="dropdown" data-hover="dropdown" data-close-others="true"> Ações
																	<i class="fa fa-angle-down"></i>
																</a>
																<ul class="dropdown-menu pull-right">
																	<li>
																		<a  href="mon_fin_cobranca_novo.php">Gerar Cobrança</a>
																	</li>      
																</ul>
															</div>
														</div>														
													</div>													
													<div class="row">
														<div class="col-md-12 column">
															<div class="col-md-6 column">																
																<div class="row">																																																
																	<div class="col-md-5 column">
																		<div class="search-filter ">
																			<div class="search-label uppercase"><span class="caption-subject font-green-sharp sbold">Data Inicial</span></div>
																			<div class="input-icon right">
																				<input type="text" class="form-control" id="form_data_inicial" name="form_data_inicial<?=rand(123131,4535434);?>" <?=$centralizado;?> onkeyUp="mascara(this, '##/##/####');">
																			</div>
																		</div>
																	</div>	
																	<div class="col-md-5 column">
																		<div class="search-filter ">
																			<div class="search-label uppercase"><span class="caption-subject font-green-sharp sbold">Data Final</span></div>
																			<div class="input-icon right">
																				<input type="text" class="form-control" id="form_data_final" name="form_data_inicial<?=rand(123131,4535434);?>" <?=$centralizado;?> onkeyUp="mascara(this, '##/##/####');">
																			</div>
																		</div>
																	</div>
																</div>
															</div>
															<div class="col-md-2 column">
																<br><button class="btn green bold uppercase btn-block" onclick="filtro();" name="btnFiltrar" id="btnFiltrar">Filtrar</button>
															</div>
														</div>									
													</div>
													<hr>
													<div class="portlet-body">
														<table class="table table-hover table-striped table-bordered" name="tabela" id="tabela" >
															<thead>
																<tr>
																	<th style="font-size: 12px !important;"><center>#</center></th>
																	<th style="font-size: 12px !important;"><center>Data da cobrança</center><br></th>																	
																	<th style="font-size: 12px !important;">Valor Total</th>
																	<th style="font-size: 12px !important;"></th>																	
																</tr>
															</thead>										
															<tbody id="tabela_retorno" name="tabela_retorno">
																<tr>
																	<td colspan="4">Nenhum dado a exibir...</td>
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
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
	<script>
		$(document).ready(function () {<?=geraDate("form_data_inicial");?>});
		$(document).ready(function () {<?=geraDate("form_data_final");?>});
	</script>
	<script type="text/javascript" src="php_js/js_mon_fin_participante.js"></script>
</html>