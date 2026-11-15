<?
	include_once "seguranca.php";
?>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
        <?
			include "global_css.php";
		?>
<script>
	if($_GET['msg']==1){alert('ERRO ao realizar upload');}
	if($_GET['msg']==0){alert('Upload realizado com sucesso');}
</script>
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
                                        <h1><?=traduzir("IMPORTAÇÃO DE PLANILHA DE CONFIGURAÇÃO DO CRF", $_SESSION['lang']);?> </h1>
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
                                            <span><?=traduzir("Importação de planilha de configuração do CRF", $_SESSION['lang']);?></span>
                                        </li>
                                    </ul>

                                </div>
                                
					                 <div class="col-md-3"></div>
                                	 <div class="col-md-6" style="background-color:#FFF">   
											<div class="form-group">
												<div class="alert alert-danger">
													<strong>ATENÇÃO!</strong> TODAS AS CONFIGURAÇÕES ANTERIORES SERÃO APAGADAS
												</div>
											</div>
											<div id="upload_versao" name="upload_versao"  tabindex="-1" data-backdrop="static" data-keyboard="false">
												 <div class="row">
													  <div class="">	
														<br><br>
														   <div class="row">
																<form action="upload_excel.php" class="form-horizontal form-bordered" name="form_upload_versao" id="form_upload_versao" enctype="multipart/form-data" method="POST">
																	<div class="form-body">
																		<div class="form-group">
																			<label class="control-label col-md-3">Arquivo</label>
																			<div class="col-md-9">
																				<div class="fileinput fileinput-new" data-provides="fileinput">
																					<div class="input-group input-large">
																						<div class="form-control uneditable-input input-fixed input-medium" data-trigger="fileinput">
																							<i class="fa fa-file fileinput-exists"></i>&nbsp;
																							<span class="fileinput-filename"> </span>
																						</div>
																						<span class="input-group-addon btn default btn-file">																						
																						<input type="file" name="form_arquivo" id="form_arquivo"> </span>
																					</div>
																				</div>
																			</div>
																		</div>
																		
																		
																		<div id="progressbox_novo" style="display:none">
																			<label class="control-label col-md-3"></label>
																			<div class="col-md-9">
																				<center><img src="imagens/carregando.gif" width="70"></center>
																			</div>
																		</div>
																		<br><br>
																	</div>
																</form>
														   </div>
														   <div class="modal-footer">
																<button type="button" data-dismiss="modal" class="btn dark btn-outline">Cancelar</button>
																<a href="#" onclick="document.form_upload_versao.submit();"><button type="button"  class="btn green">Realizar o Upload</button></a>
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
<br><br>                      
        </div>
        <?
                include "rodape.php";
        ?>
</body>
<?
	include "global_js.php";
?>
</html>

