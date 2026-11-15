<?
	error_reporting(E_ALL);
	ini_set("display_errors", 1);
	
	//grupo_periodo
	include "seguranca.php";
	include "class/conexao.class.php";
	include "class/pesq_centro.class.php";
	
	$varCentro = new Centro($_GET['id']);
	
	
?>
<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
	<script src="js/pace.js"></script>
	<link href="css/pace.css" rel="stylesheet" />
	<?
		include "global_css.php";
	?>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<link href="https://fonts.googleapis.com/css?family=Merriweather+Sans" rel="stylesheet">
	<link rel="shortcut icon" href="favicon.png">    
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
                                        <h1><?=traduzir("Pesquisas do centro ", $_SESSION['lang']); echo $varCentro->nome;?> </h1>
                                    </div>
                                </div>
                            </div>
                            
							<div class="page-content">
                                <div class="container-fluid">
										<div class="row">										
											<div class="col-md-3 sidebar-outer" id="lista" >
													<div class="col-md-12 fundo_branco">
														<div class="portlet light">
															<div class="portlet-title">
																<div class="caption font-blue-dark">
																	<span><i class="fa fa-list font-blue-dark" title="Formulários"></i></span>
																	<span class="caption-subject">&nbsp;&nbsp;Pesquisas</span>
																</div>																
																<div class="actions">
																	<div class="btn-group">
																		 <a href="javascript:incluirPesquisa();" class="btn btn-circle btn-default btn-sm">
																				<i class="fa fa-plus"></i> Incluir </a>
																	</div>
																</div>
															</div>
															<div class="portlet-body" style="height: 500px;overflow-y: scroll;">
																<table width="100%" class="table table-hover table-condensed">	
																	<thead>
																		<th><font size="2px">Pesquisa</font></th>
																		<th><font size="2px"><center>Enviar email</center></font></th>
																	</thead>
																	<tbody id="tablePesquisas">										
																	</tbody>
																</table>
															</div>
														</div>
													</div>																		
											</div>	
											<div class="col-md-2 sidebar-outer" id="divListaForm" style="display:none">
													<div class="col-md-12 fundo_branco">
														<div class="portlet light">
															<div class="portlet-title">
																<div class="caption font-blue-dark">
																	<span><i class="fa fa-list font-blue-dark" title="Formulários"></i></span>
																	<span class="caption-subject">&nbsp;&nbsp;Formulários</span>
																</div>																																
															</div>
															<div class="portlet-body" style="height: 500px;overflow-y: scroll;">
																<table width="100%" class="table table-hover table-condensed">																		
																	<tbody id="tableForms">										
																	</tbody>
																</table>
															</div>
														</div>
													</div>																		
											</div>	
											<div class="col-md-7 sidebar-outer" id="divListaCampos" style="display:none">
													<div class="col-md-12 fundo_branco">
														<div class="portlet light">
															<div class="portlet-title">
																<div class="caption font-blue-dark">
																	<span><i class="fa fa-list font-blue-dark" title="Formulários"></i></span>
																	<span class="caption-subject">&nbsp;&nbsp;Campos</span>
																</div>																																
															</div>
															<div class="portlet-body" style="height: 500px;overflow-y: scroll;">
																<table width="100%" class="table table-hover table-condensed">																		
																	<tbody id="tableCampos">										
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
	<div id="modalNovaPesquisa" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h4 class="modal-title">Pesquisas</h4>
				</div>
				<div class="modal-body">									
					<form class="form-horizontal form-bordered" 
						name="form_upload" id="form_upload" enctype="multipart/form-data" method="POST">
						<div class="form-body">
							<table id="tblPesquisas" class="table table-condensed">
								<thead>
									<th>Pesquisa</th>
									<th width="20%">Selecionar</th>
								</thead>
								<tbody>
									<?
										$conexao = new Conexao();
										$query 		= "select * from pesq_pesquisa order by nome";
										$resPesq 	=  $conexao->pesquisa($query);
										while($rowPesq = mysqli_fetch_assoc($resPesq)){
											?>
											<tr>
												<td><?=$rowPesq['nome'];?></td>
												<td><a href="javascript:cadastraPesquisaEXE(<?=$_GET['id'];?>, <?=$rowPesq['id'];?>);"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i></a></td>
											</tr>
											<?											
										}
									?>
								</tbody>
							</table>
							
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
	<?
		include "rodape.php";
		include "global_js.php";				
	?>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
	<script src="php_js/js_pesq_centro_pesquisas.js" type="text/javascript"></script>
	<script>
		listaPesquisas(<?=$_GET['id'];?>);
	</script>
</html>