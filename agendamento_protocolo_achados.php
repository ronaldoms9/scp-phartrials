<?php
	include_once "seguranca.php";	
	include_once "php_php/php_agendamento_protocolo_achados.php";
	
	$agendamento 	 = $_GET["agendamento"];	
	$varFormulario	 = $_GET[base64_encode('cam')];
?>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
	<?php
		include "global_css.php";
	?>	
	<link href="https://fonts.googleapis.com/css?family=Merriweather+Sans" rel="stylesheet">
	<link rel="shortcut icon" href="favicon.png">    
	<head>
		<style>
			.lista_formularios{
				font-size: 13px !important;
				font-family: 'Abel', sans-serif;
			}
			.label{
				font-weight: bold !important; 
				font-family: 'Abel', sans-serif !important; 
				font-size: 14px !important;
				font-color: black !important;
				color: black;
			}
			.tabela-head{
				font-weight: bold !important; 
				font-family: 'Abel', sans-serif !important; 
				font-size: 14px !important;
				background-color:#f7f7f7 !important; 
				border-bottom: 2px solid #2C3E50 !important; 
				border-top: 2px solid #2C3E50 !important; 
			}
			.tabela-divisao{
				font-weight: bold !important; 
				font-family: 'Abel', sans-serif !important; 
				font-size: 12px !important;
				background-color:#f7f7f7 !important; 
				border-bottom: 2px solid #2C3E50 !important; 
				border-top: 1px solid #2C3E50 !important; 
				font-weight: bold !important; 
			}

			.tabela-body{
				font-family: 'Abel', sans-serif !important; 
				font-size: 14px !important;			
			}
			.controle{
				font-family: 'Abel', sans-serif !important; 
				font-size: 13px !important;			
				padding: 8px 8px !important;			
				line-height: 20px !important;	
				text-align: center;			
				text-align-last:center;
			}
			.legenda{
				font-family: 'Abel', sans-serif !important; 
				font-size: 10px !important;		
				font-style: italic !important;
			}
			.fixo {
			}
			.coluna{
				color: black;
				vertical-align: center;
				background-color: white;
				color: black;
				border: 1px solid #e7e7e7;		
				padding: 0.2px;
				width:25px !important;
			}
		</style>
	</head>
</head>
<body>
       <div class="page-wrapper">
            <?php
            ?>
            <div class="page-wrapper-row full-height">
                <div class="page-wrapper-middle">
                    <div class="page-container">
                        <div class="page-content-wrapper">                            
                            <div class="page-content">									
									<div class="col-md-12">
										<div class="portlet light portlet-fit ">											
											<div class="portlet-body">														
												<div class="row">
													<div class="portlet light">
														<div class="portlet-body">	
															<div class="row">													
																<div style="text-align:center">					
																	<table width="100%">
																		<tr>														
																			<td align="right">
																				<a href="javascript:document.location='agendamento_protocolo_edit.php?agendamento=<?=$agendamento;?>&tipo=N&formulario=<?=$varFormulario;?>'" class="btn btn-circle btn-default btn-sm">
																					<i class="fa fa-plus"></i> <?=traduzir("Novo achado", $_SESSION['lang']);?></a>				
																			</td>
																		</tr>
																	</table>
																</div>
																<br>
															</div>	
															<table id="listagem" name="listagem" width="100%" class="table table-striped" style="font-family: 'Abel';font-size: 22px;">
																<thead>
																	
																		<th class="tabela-head" width="10%">#</th>
																		<th class="tabela-head" width="10%"><center><?=traduzir("Página");?></center></th>	
																		<th class="tabela-head"><?=traduzir("Trecho do texto");?></th>	
																		<th class="tabela-head" width="40%"><?=traduzir("Achado/Questionamento");?></th>																								
																		<th class="tabela-head"><?=traduzir("Crítico");?>?</th>
																		<th class="tabela-head"><center><?=traduzir("Resposta");?></center></th>
																		<th class="tabela-head"><centeR><?=traduzir("Respondido por");?></center></th>
																		<th class="tabela-head"><center><?=traduzir("Finalizado");?>?</center></th>
																		<th class="tabela-head"></th>
																</thead>
																<tbody id="retornoAchados">
																	
																</tbody>
															</table>				
														</div>	
														<div class="portlet-footer" style="text-align:center">
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
		</div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div>
	<?php		
		include "global_js.php";				
	?>
	<script src="js/moment.min.js"></script>	
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>	
	<script src="php_js/js_agendamento_protocolo_edit.js"></script>
	<script src="php_js/js_agendamento_protocolo_achados.js"></script>
	<script>
		mostraPendencias(<?=$agendamento;?>);
	</script>
</html>