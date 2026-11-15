<?php
<?
	include_once "seguranca.php";	
	include_once "php_php/cfg_projeto.php";
			
	
	$conexao = new Conexao();
?>
<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
	<?
		include "global_css.php";
	?>
	<link href="assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />		
	
</head>
    <body class="page-container-bg-solid page-footer-fixed">
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
                                        <h1><?=traduzir("CONFIGURAÇÕES DO PROJETO", $_SESSION['lang']);?> </h1>
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
                                            <span><a href="cfg_menu.php"><?=traduzir("Configurações do Projeto", $_SESSION['lang']);?></a></span>
                                        </li>
                                    </ul>				
								</div>
								<div class="container-fluid">								
									<div class="col-md-3" style="background-color:white">
										<fieldset style="height: 510px;overflow-y: scroll;">
											<ul id="sidebar-nav" class="sidebar-nav margin-bottom-30px">
												<?
													 $query = "select * from proj_projeto where registro_valido = 1 order by numero";
													 $resUsua = $conexao->pesquisa($query);
													 while($rowUsua = mysqli_fetch_assoc($resUsua)){
														 echo '<li class="list-group-item "><a href="javascript:selecionaProjeto('.$rowUsua['id'].');">'.$rowUsua['numero'].'- '.$rowUsua['nome'].'</a></li>';
													 }
												?>
											</ul>
									   </fieldset>
									</div>
									<?
									//if($_GET['id']>0){
									?>
									<div class="col-md-9">
									
									
										<div class="portlet box blue ">
											<div class="portlet-title">
												<div class="caption">
													<i class="fa fa-gift"></i>Dados cadastrais </div>												
											</div>
											<div class="portlet-body form">
												<form action="#" class="form-horizontal form-bordered form-row-stripped">
													<div class="form-body">
														




														
														<div class="form-group">
															<label class="control-label col-md-2">Formulação</label>
															<div class="col-md-8">
																<input type="text" class="form-control" id="form_formulacao" name="form_formulacao">																		
															</div>
														</div>
														

														
														<div class="form-group">
															<label class="control-label col-md-2">Centro Clínico</label>
															<div class="col-md-8">
																<input type="text" class="form-control" id="form_centro" name="form_centro">
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-md-2">Centro Analítico</label>
															<div class="col-md-8">
																<input type="text" class="form-control" id="form_centro_analitico" name="form_centro_analitico">
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-md-2">Centro Estatístico</label>
															<div class="col-md-8">
																<input type="text" class="form-control" id="form_centro_estatistico" name="form_centro_estatistico">
															</div>
														</div>
														




                                                        
														<div class="form-group">
															<label class="control-label col-md-2">Versão</label>
															<div class="col-md-2">
																<input type="text" class="form-control" id="form_versao" name="form_versao">
															</div>		
															<label class="control-label col-md-1">Dash?</label>
															<div class="col-md-2">
																<select class="form-control" id="form_dash" name="form_dash">
																	<option value=""></option>
																	<option value="1">Sim</option>
																	<option value="0">Não</option>
																</select>																
															</div>
															<label class="control-label col-md-1">País</label>
															<div class="col-md-3">
																<input type="text" class="form-control" id="form_pais" name="form_pais">
															</div>		
														</div>
														<div class="form-group">																			
															<label class="control-label col-md-2">Dashboard Proprio?</label>
															<div class="col-md-3">
																<select class="form-control" id="form_dashproprio" name="form_dashproprio">
																	<option value=""></option>
																	<option value="1">Sim</option>
																	<option value="0">Não</option>
																</select>																
															</div>
															<label class="control-label col-md-3">Código</label>
															<div class="col-md-3">
																<input type="text" class="form-control" id="form_codigo" name="form_codido">
															</div>	
														</div>
														<div class="form-group">																			
															<label class="control-label col-md-2">Data de Recebimento</label>
															<div class="col-md-3">
																<input type="text" 
																		class="form-control" 
																		id="form_data_recebimento" 
																		name="form_data_recebimento">
															</div>
															<label class="control-label col-md-2">Data de Finalização</label>
															<div class="col-md-3">
																<input 
																	type="text" 
																	class="form-control" 
																	id="form_data_finalizacao">
															</div>
														</div>
													</div>
												</form>
											</div>
											<div class="portlet-footer">
												<div class="row">
													<br>
												<center>
													<div class="row centered">																				
														<button class="btn btn-default" onclick="javascript:novo();">Novo Estudo</button>
														<button class="btn btn-default" onclick="javascript:limpaCampos();">Limpar campos</button>
														<button class="btn btn-default" onclick="javascript:salvar();">Salvar</button>	
														<?
														if($_SESSION['user_id']=="1"){
														?>
															<button class="btn btn-default"><a href="javascript:gerarTmf();">Gerar TMF</a></button>
															<button class="btn btn-default"><a href="javascript:gerarTmfResumido();">TMF Prot/Rep</a></button>
															<button class="btn btn-default"><a href="javascript:gerarTmfBio();">TMF BIO</a></button> -->
														<?
														}
														?>
														<!--<button class="btn btn-default"><a href="javascript:zerar();">ZERAR CRF</a></button>-->
													</div>																																																											
													<br>																			
												</center>
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
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
	<script src="php_js/cfg_projeto.js" type="text/javascript"></script>
		
</html>
