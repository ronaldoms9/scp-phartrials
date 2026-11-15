<?php		
	include "seguranca.php";	
?>

<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
	<?php
		include "global_css.php";
	?>	
    
<style>
    .window{
        display:none;
        width:600px;
        height:600px;
        position:absolute;
        left:0;
        top:0;
        background:#FFF;
        z-index:9900;
        padding:10px;
        border-radius:10px;
    }

    .float-right {
	  float: right;
	}
</style>
</head>
<div id="mascara"></div>

    <body class="page-container-bg-solid page-footer-fixed">
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
                                        <h1><?=traduzir("Cadastro de Pesquisas", $_SESSION['lang']);?> </h1>
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
                                            <span><?=traduzir("Cadastro de Pesquisas", $_SESSION['lang']);?></span>
                                        </li>
                                    </ul>

                                </div>
                                <div class="row">
                                        <div class="col-md-2">
                                            <h4><center>Pesquisas</center></h4>
                                            <fieldset style="height: 600px;overflow-y: scroll;">
                                                <ul id="divPesquisa" class="sidebar-nav margin-bottom-30px">                                                    
                        						</ul>
                                           </fieldset>
                    					</div>
                                            
										<div class="col-md-2">
											<h4><center>Formulários</center></h4>
											<fieldset style="height: 600px;overflow-y: scroll;">
												<ul id="divFormulario" class="sidebar-nav margin-bottom-30px">
													
												</ul>
										   </fieldset>
										</div>
										<div class="col-md-2">
											<input type="hidden" id="formFormularioId">
											<h4><center>Campos</center></h4>
											<fieldset style="height: 600px;overflow-y: scroll;">
												<ul id="divCampos"class="sidebar-nav margin-bottom-30px">												
													
												</ul>
										   </fieldset>
										</div>

									<br><br>
									<div class="col-md-6" style="background-color:#FFF">										
										<br>										
										<div id="divCamposDet">
											<div class="portlet-body form">
												<!-- BEGIN FORM-->
												<form action="#" class="form-horizontal form-bordered">
													<div class="form-body">
														<div class="form-group">
															<label class="control-label col-md-2">ID</label>
															<div class="col-md-3">
																<input disabled title="Identificador do campo" <?=$centralizado;?> type="text" class="form-control" id="form_id" name="form_id" value="<?=$rowUsu['id'];?>">
															</div>
															<label class="control-label col-md-2">Ordem</label>
															<div class="col-md-3">
																<input <?=$centralizado;?> title="A ordem em que o campo será exibido no formulário" type="text" class="form-control" id="form_ordem" name="form_ordem" value="<?=$rowUsu['ordem'].$PROXIMA_ORDEM;?>">
															</div>
														</div>
														
														<div class="form-group">
															<label class="col-md-2 control-label">Tipo</label>
															<div class="col-sm-3">
																  <select title="Tipo de campo. A formatação na tela será exibida de acordo com a opção selecionada." class="form-control" name="form_tipo" id="form_tipo" >
																			<option value=""></option>																			  
																			<option value="select" <?php if($rowUsu['tipo']=="select"){echo " selected ";}?>>Caixa de seleção</option>																			  
																			<option value="caixa" <?php if($rowUsu['tipo']=="caixa"){echo " selected ";}?>>Caixa de texto</option>																			  
																			<option value="data" <?php if($rowUsu['tipo']=="data"){echo " selected ";}?>>Data</option>																			  
																			<option value="numero"  <?php if($rowUsu['tipo']=="numero"){echo " selected ";}?>>Número</option>																			  
																			<option value="texto" <?php if($rowUsu['tipo']=="texto"){echo " selected ";}?>>Texto</option>		
																			<option value="checkbox" >Check-Box</option>
																  </select>
															</div>
															<label class="col-md-2 control-label">Variável</label>
															<div class="col-sm-4">
																<input title="Nome do campo" type="text" class="form-control" id="form_nome" name="form_nome" value="<?=$rowUsu['nome'];?>">
															</div>			 
														</div>
														<div class="form-group">
															<label class="col-md-2 control-label">Texto no CRF</label>
															<div class="col-sm-9">
																<textarea title="Nome que será exibido no CRF" class="form-control" id="form_texto" name="form_texto" rows="2" cols="30"><?=$rowUsu['texto'];?></textarea>
															</div>
														</div>
														<div class="form-group">
															<label class="col-md-2 control-label">Texto no CRF (EN)</label>
															<div class="col-sm-9">
																<textarea title="Nome que será exibido no CRF" class="form-control" id="form_texto_en" name="form_texto_en" rows="2" cols="30"><?=$rowUsu['texto_en'];?></textarea>
															</div>
														</div>
														<div class="form-group">
															<label class="col-md-2 control-label">Formatação</label>
															<div class="col-sm-3">
																<input title="Formato do campo. Será utilizado a função Mascara" type="text" class="form-control" id="form_formato" name="form_formato" value="<?=$rowUsu['formato'];?>">
															</div>
															<label class="col-md-2 control-label">Legenda</label>
															<div class="col-sm-3">
																<input title="Legenda do campo" type="text" class="form-control" id="form_legenda" name="form_legenda" value="<?=$rowUsu['legenda'];?>">
															</div>												 
														</div>
														<div class="form-group">
															<label class="col-md-2 control-label">
																<input title="Se o preenchimento é obrigatório. Caso sim, a borda do campo ficará em vermelho" type="checkbox" id="form_obrigatorio" name="form_obrigatorio" <?php if(strtoupper($rowUsu['obrigatorio'])=="ON"){ echo " checked "; }?>> Obrigatório?
															</label>
															<label class="col-md-2 control-label">
																<input title="Somente irá permitir a entrada de valores numéricos" type="checkbox" id="form_so_numero" name="form_so_numero" <?php if(strtoupper($rowUsu['so_numero'])=="ON"){ echo " checked "; }?>> Só número?
															</label>
															<label class="col-md-2 control-label">															
																<input title="Somente irá permitir a entrada de valores alphanuméricos" type="checkbox" id="form_so_letra" name="form_so_letra" <?php if(strtoupper($rowUsu['so_letra'])=="ON"){ echo " checked "; }?>> Só letras?															
															</label>
															<label class="col-md-2 control-label">															
																<input title="Campo somente leitura" type="checkbox" id="form_so_leitura" name="form_so_leitura" <?php if(strtoupper($rowUsu['so_leitura'])=="ON"){ echo " checked "; }?>> ReadOnly															
															<br>
															</label>
															
														</div>
														
													</div>
												</form>
											</div>
																									
												<br>
												<div class="form-group">																											 
														 <label class="col-md-3 control-label">Valores aceitos</label>
														 <div class="col-sm-2">
															  <textarea rows="5" style="width:500px" class="form-control" id="form_selecao_valores" name="form_selecao_valores"><?=str_replace("<br />","",$rowUsu['selecao_valores']);?></textarea>
														 </div>
														 
													</div>
												</div>
												<br><br>
												<div class="form-group">
													<div class="col-sm-12"><center><br><br>
														  <button type="button" class="btn btn-default" onclick="javascript:salvarCampo();">  Salvar </button>														  
														  <button type="button" class="btn btn-default" onclick="javascript:cancelar();">  Cancelar  </button>
														  </center>
													</div>
												</div>
										</div>
									</div>
                        					
                				</div>
                			</div>
                		</div>
                  <br><br>
            	</div>
				</div>        
	<div id="modalNovaPesquisa" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h4 class="modal-title">Nova pesquisa</h4>
				</div>
				<div class="modal-body">									
					<form class="form-horizontal form-bordered" 
						name="form_upload" id="form_upload" enctype="multipart/form-data" method="POST">
						<div class="form-body">
						
							<div class="form-group">
								<label class="control-label col-md-3">Nome</label>
								<div class="col-md-9">
									<input type="hidden" id="form_modalNovaPesquisa_pesquisaId" class="form-control" >									
									<input type="text" id="form_modalNovaPesquisa_nome" class="form-control" >
								</div>
							</div>							
						</div>	
					</form>
				</div>
				<div class="modal-footer">					
					<a href="#" onclick="novaPesquisaEXE();"><button type="button"  class="btn green">Cadastrar</button></a>
					<button type="button" data-dismiss="modal" class="btn dark btn-outline">Cancelar</button>
				</div>
			</div>
		</div>
	</div>
	<div id="modalNovoFormulario" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h4 class="modal-title">Nova pesquisa</h4>
				</div>
				<div class="modal-body">									
					<form class="form-horizontal form-bordered" 
						name="form_upload" id="form_upload" enctype="multipart/form-data" method="POST">
						<div class="form-body">
						
							<div class="form-group">
								<label class="control-label col-md-3">Nome</label>
								<div class="col-md-9">
									<input type="hidden" id="modalNovoFormulario_pesquisaId" class="form-control" >									
									<input type="text" id="modalNovoFormulario_nome" class="form-control" >
								</div>
							</div>							
						</div>	
					</form>
				</div>
				<div class="modal-footer">					
					<a href="#" onclick="novoFormularioEXE();"><button type="button"  class="btn green">Cadastrar</button></a>
					<button type="button" data-dismiss="modal" class="btn dark btn-outline">Cancelar</button>
				</div>
			</div>
		</div>
	</div>
</body>
	<?php
		include "rodape.php";
		include "global_js.php";
	?>	
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
	<script src="php_js/js_cfg_cad_pesquisa.js" type="text/javascript"></script>	
</html>
