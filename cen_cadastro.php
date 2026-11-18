<?php
	//grupo_periodo
	include_once "seguranca.php";	
	
?>
<!DOCTYPE html>

<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
	<script src="js/pace.js"></script>
	<link href="css/pace.css" rel="stylesheet" />
	<?php
		include "global_css.php";
	?>	
	<link href="https://fonts.googleapis.com/css?family=Merriweather+Sans" rel="stylesheet">
	<link rel="shortcut icon" href="favicon.png">    	
</head>
<body>
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
                                        <h1><?=traduzir("Cadastro de centros de pesquisa", $_SESSION['lang']);?> </h1>
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
                                            <span><?=traduzir("Cadastro de centros de pesquisa", $_SESSION['lang']);?></span>
                                        </li>
                                    </ul>
                                </div>								
								<br>
								
								<div class="row">									
										
									
											
									<div class="col-md-12">
										<div class="portlet light portlet-fit ">
											<div class="portlet-title">
												<div class="caption">
													<i class="icon-bubble font-dark"></i>
													<span class="caption-subject font-dark bold uppercase">Listagem de centros</span>
												</div>
												<div class="actions">
													<div class="btn-group">
														 <a href="javascript:incluirCentro();" class="btn btn-circle btn-default btn-sm">
                                                                <i class="fa fa-plus"></i> Incluir novo Centro </a>
														
														<a class="btn btn-circle btn-default btn-sm" href="javascript:;" data-toggle="dropdown" data-hover="dropdown" data-close-others="true"> Exportar
															<i class="fa fa-angle-down"></i>
														</a>
														&nbsp;&nbsp;
														<ul class="dropdown-menu pull-right">
															<li>
																<a href="javascript:exporta_tabela_excel('agenda_listagem');"> Excel</a>
															</li>																		
														</ul>
													</div>
												</div>
											</div>
											<div class="portlet-body">												
												<div class="scroller" style="height:400px" data-rail-visible="1" data-rail-color="yellow" data-handle-color="#a1b2bd">
													<div class="table-scrollable">
														<table id="tblListagem" name="tblListagem" class="table table-bordered table-hover">
															<thead>
																<th><center>#ID</center></th>
																<th>Nome</th>																
																<th>Cidade</th>
																<th>Coordenador</th>
																<th>Email</th>
																<th><center>Ações</center></th>
															</thead>
															<tbody id="divCentros">
																
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
	</body>
	<div id="modalNovoCentro" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h4 class="modal-title">Cadastro de Centros</h4>
				</div>
				<div class="modal-body">									
					<form class="form-horizontal form-bordered" 
						name="form_upload" id="form_upload" enctype="multipart/form-data" method="POST">
						<div class="form-body">
						
							<div class="form-group">
								<label class="control-label col-md-3">Nome</label>
								<div class="col-md-9">									
									<input type="text" id="modalNovoCentro_nome" class="form-control" >
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3">Cidade</label>
								<div class="col-md-9">									
									<input type="text" id="modalNovoCentro_cidade" class="form-control" >
								</div>
							</div>							
							<div class="form-group">
								<label class="control-label col-md-3">Coordenador</label>
								<div class="col-md-9">									
									<input type="text" id="modalNovoCentro_coordenador" class="form-control" >
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3">Email</label>
								<div class="col-md-9">									
									<input type="text" id="modalNovoCentro_email" class="form-control" >
								</div>
							</div>
						</div>	
					</form>
				</div>
				<div class="modal-footer">					
					<a href="#" onclick="cadastraEXE();"><button type="button"  class="btn green">Cadastrar</button></a>
					<button type="button" data-dismiss="modal" class="btn dark btn-outline">Cancelar</button>
				</div>
			</div>
		</div>
	</div>
	<?php
		include "rodape.php";
		include "global_js.php";				
	?>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
	<script src="php_js/js_cen_cadastro.js" type="text/javascript"></script>

</html>