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
	<link href="https://fonts.googleapis.com/css?family=Abel|Archivo+Narrow|PT+Sans+Narrow:700|Pragati+Narrow" rel="stylesheet">
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
											<div class="col-md-1"></div>
											<div class="col-md-10">
												<div class="portlet light ">
													<div class="portlet-title">
														<div class="caption">
															<i class="icon-bubble font-green-sharp"></i>
															<span class="caption-subject font-green-sharp sbold">Gerar Cobrança </span>
														</div>
													</div>
													<div class="row">
														<div class="col-md-12 column">
															<!--<div class="col-md-6 column">
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
															</div>-->
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
																	<th style="font-size: 12px !important;"><center>#</center><br></th>
																	<th style="font-size: 12px !important;"><center># Participante</center><br></th>
																	<th style="font-size: 12px !important;"><center>Codigo<br>Participante</center><br></th>
																	<th style="font-size: 12px !important;"><center>Visita</center></th>
																	<th style="font-size: 12px !important;">Item</th>
																	<th style="font-size: 12px !important;"><center>Valor</center></th>

																</tr>
															</thead>
															<tbody id="tabela_retorno" name="tabela_retorno">
																<tr>
																	<td colspan="5">Nenhum dado a exibir...</td>
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
	<?
		include "rodape.php";
		include "global_js.php";
	?>
	</body>
	<script type="text/javascript" src="php_js/js_mon_fin_cobranca.js"></script>
	<script>
		$(document).ready(function () {<?=geraDate("form_data_inicial");?>});
		$(document).ready(function () {<?=geraDate("form_data_final");?>});
	</script>
</html>