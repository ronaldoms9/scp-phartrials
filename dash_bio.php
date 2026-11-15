<?php	
	include_once "seguranca.php";	
	$varConexao = new Conexao();
?>			
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="content-type" content="text/html;charset=UTF-8" />	
		<?		
			include "global_css.php";	
		?>    

		<link href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/r/bs-3.3.5/jq-2.1.4,dt-1.10.8/datatables.min.css"/>
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.4.2/css/buttons.dataTables.min.css"/>
		<style>
			@import url('https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700');
			$base-spacing-unit: 24px;
			$half-spacing-unit: $base-spacing-unit / 2;
			$color-alpha: black;
			$color-form-highlight: #EEEEEE;
			*, *:before, *:after {
				box-sizing:border-box;
			}
			.table {				width:100%;				border:1px solid $color-form-highlight;			}
			.dados{				font-size: 9pt;			}
			.dadosInternoTH{				font-size: 9pt;			}
			.dadosInterno{				font-size: 9pt;			}
			.verde{
				text-align: center;
				border-style: solid;
				border-width: 1px;
				border-radius: 10px;
				border-color: black;
				background-color: #69B969;
			    padding: 5px;
				font-color: black;
				color: white;
			}
			.vermelho{
				text-align: center;
				border-style: solid;
				border-width: 1px;
				border-radius: 10px;
				border-color: black;
				background-color: #C73F3F;
			    padding: 5px;
				font-color: black;
				color: white;				
			}
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
										<h1><?=traduzir("DASHBOARD REVISÕES", $_SESSION['lang']);?></h1>
									</div>
								</div>
							</div>
							<div class="page-content">
								<div class="container-fluid">
									<div class="row">
										<div class="col-md-12">
											<div class="portlet light">
												<div class="portlet-title">
													<div class="caption">
														<span class="caption-subject font-green-sharp bold uppercase"> Projetos</span>  	
													</div>														
												</div>
												<div class="portlet-body">
													<div class="row">
														<div class="col-md-11 column">
															<br>
															<div class="row">
																	<div class="col-md-2 column">
																		<div class="search-filter ">
																			<div class="search-label uppercase"><span class="caption-subject font-green-sharp sbold">Patrocinador</span>
																			</div>
																			<div class="input-icon right">
																				<select name="form_centro" id="form_patrocinador" class="form-control">
																					<option value="">TODOS</option>
																					<?
																						$query = "SELECT distinct proj_projeto.patrocinador, 
																											cad_patrocinador.nome 	
																											FROM proj_projeto     	
																												inner join cad_patrocinador on proj_projeto.patrocinador = cad_patrocinador.id        
																												order by cad_patrocinador.nome ";
																						$resCentro = $varConexao->pesquisa($query);
																						while($rowCentro = mysqli_fetch_assoc($resCentro)){
																							if($rowCentro['nome']!=""){
																								echo '<option value="'.$rowCentro['patrocinador'].'">'.$rowCentro['nome'].'</option>';
																							}
																						}
																					?>																					
																				</select>
																			</div>
																		</div>
																	</div>																
																	<div class="col-md-2 column">
																		<div class="search-filter ">
																			<div class="search-label uppercase"><span class="caption-subject font-green-sharp sbold">Finalizado</span>
																			</div>
																			<div class="input-icon right">
																					<select id="form_finalizado" class="form-control">
																						<option value="">TUDO</option>
																						<option value="0">PENDENTES</option>
																						<option value="1">FINALIZADOS</option>
																					</select>
																			</div>
																		</div>
																	</div>																	
																	
															</div>
														</div>
														<div class="col-md-1 column">
															<br><button class="btn green bold uppercase btn-block" onclick="filtro();" name="btnFiltrar" id="btnFiltrar">Filtrar</button>
														</div>
													</div>
													<hr>												
													<div id="divRetorno">														
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
	</body>
	<?
		include "rodape.php";
		include "global_js.php";
	?>
	<script src="php_js/js_dash_bio.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
	<script>
	$(document).ready(function() {
		$('.classPesquisa').select2();
	});
	</script>	
</html>