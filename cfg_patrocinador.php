<?php
	include_once "seguranca.php";
?>
<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<?php
		include "global_css.php";
	?>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="//cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.3.1/css/buttons.dataTables.min.css">
	<link href="https://fonts.googleapis.com/css?family=Abel|Archivo+Narrow|PT+Sans+Narrow:700|Pragati+Narrow" rel="stylesheet">
	<style>
		.modal {
		  overflow-y:auto;
		}
		.lista_formularios{
			font-size: 13px !important;
			font-family: 'Abel', sans-serif;
		}
		.lista_formularios_desabilitado{
			font-size: 13px !important;
			font-family: 'Abel', sans-serif;
			color: #c0c0c0 !important;
		}
		.textoFORMULARIO_PRE{
			font-size: 14px !important;
			/*font-family: 'Pragati Narrow', sans-serif;*/
			/*font-family: 'Archivo Narrow', sans-serif;*/
			font-family: 'Abel', sans-serif;
		}
		.textoFORMULARIO_POS{
			font-size: 16px !important;
			font-family: 'Abel', sans-serif;
			font-weight: bold !important;
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
			/*E9EDEF*/
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
		.content-novo{
		   max-height: 100%; /*altura da modal*/
		}
		.dialog-novo{
		   height: 100%; /*altura da view da modal*/
		   margin-top: 0;
		}
		.body-novo{
		   overflow: auto; /*habilita o overflow no corpo da modal*/
		}
		.divMsg{
			color: red;
			font-size: 14px;
			font-weight: bold;
		}
		.control-label{
			font-family: 'Abel';
			font-size: 15px;
		}
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
                                        <h1><?=traduzir("CADASTRO DE PATROCINADORES", $_SESSION['lang']);?> </h1>
                                    </div>
                                </div>
                            </div>
                            <div class="page-content">
                                <div class="container-fluid">

										<div class="page-content-inner">
											<div class="row widget-row">
												<div class="col-md-2"></div>

												<div class="col-md-8">


												<div class="portlet light" style="width:100%">
													<div class="portlet-title fixo">
														<div class="caption font-blue-dark">
															<span><i class="fa fa-th font-blue-dark" title="Formulários"></i></span>
															<span class="caption-subject">&nbsp;&nbsp;<b>DADOS DO PATROCINADOR</b></span>
														</div>
													</div>
													<div class="portlet-body">
														<form class="form-horizontal" role="form" action="#" method="post" id="form_info" name="form_info" autocomplete="off">
															<input type="hidden" class="form-control" id="form_id" name="form_id" value="0">

															<div class="form-body">
																<div class="form-group">
																	<label class="control-label col-md-1">Nome</label>
																	<div class="col-md-7">
																		<input type="text" class="form-control" id="form_nome" name="form_nome">
																	</div>
																</div>
																<div class="form-group">
																	<label class="control-label col-md-1">Endereço</label>
																	<div class="col-md-11">
																		<textarea class="form-control" id="form_endereco" name="form_endereco" rows="5" cols="30"></textarea>
																	</div>
																</div>
																<div class="form-group">
																	<label class="control-label col-md-1">Responsável</label>
																	<div class="col-md-7">
																		<select name="form_pessoa_responsavel" id="form_pessoa_responsavel" class="form-control">
																			<option value="">Selecione</option>
																			<?php
																			$query = "select patrocinador from proj_projeto where id = ".$_SESSION['projeto'];
																			$res = $conexao->pesquisa($query);
																			$row = mysqli_fetch_assoc($res);
																			$varPatrocinador = $row['patrocinador'];
																			$query = "select * from cad_patrocinador_pessoa where patrocinador = ".$varPatrocinador;
																			$res = $conexao->pesquisa($query);
																			while($row = mysqli_fetch_assoc($res)){
																				?>
																					<option value="<?=$row['id'];?>"><?=$row['nome'];?></option>
																				<?php
																			}
																			?>
																		</select>
																	</div>
																</div>
																<div class="form-group">
																	<label class="control-label col-md-1">Pessoa 1</label>
																	<div class="col-md-7">
																		<select name="form_pessoa_1" id="form_pessoa_1" class="form-control">
																			<option value="">Selecione</option>
																			<?php
																				$query = "select * from cad_patrocinador_pessoa where patrocinador = ".$varPatrocinador;
																				$res = $conexao->pesquisa($query);
																				while($row = mysqli_fetch_assoc($res)){
																					?>
																						<option value="<?=$row['id'];?>"><?=$row['nome'];?></option>
																					<?php
																				}
																			?>

																		</select>
																	</div>

																</div>
																<div class="form-group">
																	<label class="control-label col-md-1">Pessoa 2</label>
																	<div class="col-md-7">
																		<select name="form_pessoa_2" id="form_pessoa_2" class="form-control">
																			<option value="">Selecione</option>
																			<?php
																				$query = "select * from cad_patrocinador_pessoa where patrocinador = ".$varPatrocinador;
																				$res = $conexao->pesquisa($query);
																				while($row = mysqli_fetch_assoc($res)){
																					?>
																						<option value="<?=$row['id'];?>"><?=$row['nome'];?></option>
																					<?php
																				}
																			?>

																		</select>
																	</div>

																</div>
															</div>
														</form>
													</div><hr>
													<div class="portlet-footer" style="text-align: center">
                                                        <button class="btn btn-primary" onclick="javascript:buscarDadosPatrocinador();"> <i class="fa fa-search"></i> Buscar dados </button>
														<button class="btn btn-primary" onclick="javascript:salvarPatrocinador();"> <i class="fa fa-save"></i> Salvar </button>
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
	<script src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js" type="text/javascript"></script>
	<script src="assets/global/plugins/jquery.min.js" type="text/javascript"></script>
	<script src="js/funcoes.js" type="text/javascript"></script>

	<?php
		include "rodape.php";
		include "global_js.php";
	?>


	<script>

        function buscarDadosPatrocinador(){
            mostraCarregando();
            $.ajax({
                url: "php_php/php_cad_patrocinador.php?act=BUSCAR_DADOS",
                type: "POST",
                data: $("#form_info").serialize(),
                success: function(data){
                    console.log("Retorno->"+data);
                    removeCarregando();
                    retorno = JSON.parse(data);
                    if(retorno.resultado == "OK"){
                        document.getElementById("form_id").value          = retorno.id;
                        document.getElementById("form_nome").value        = retorno.nome;
						document.getElementById("form_endereco").value    = retorno.endereco;
                        /*document.getElementById("form_responsavel").value = retorno.responsavel;
                        document.getElementById("form_pessoa1").value     = retorno.pessoa1;
                        document.getElementById("form_pessoa2").value     = retorno.pessoa2;*/

                    }
                },
                error: function(data){
                    removeCarregando();
                    alert("Erro ao buscar dados do patrocinador");
                }
            });
        }
		function salvarPatrocinador(){
			mostraCarregando();
			$.ajax({
				url: "php_php/php_cad_patrocinador.php?act=SALVA_PATROCINADOR_PROJETO",
				type: "POST",
				data: $("#form_info").serialize(),
				success: function(data){
					console.log("Retorno->"+data);
					alert("Operação realizada com sucesso");
					removeCarregando();
				},
				error: function(data){
					removeCarregando();
					alert("Erro ao salvar centro");
				}
			});

		}
        function iniciaFormulario(){
            mostraCarregando();
            $.ajax({
                url: "php_php/php_cad_patrocinador.php?act=BUSCAR_DADOS_PROJETO",
                type: "POST",
                data: $("#form_info").serialize(),
                success: function(data){
                    console.log("Retorno->"+data);
                    removeCarregando();
                    retorno = JSON.parse(data);
                    if(retorno.resultado == "OK"){
                        document.getElementById("form_id").value        = retorno.id;
                        document.getElementById("form_nome").value        = retorno.nome;
                        document.getElementById("form_endereco").value    = retorno.endereco;
						document.getElementById("form_pessoa_responsavel").value = retorno.pessoa_responsavel;
						document.getElementById("form_pessoa_1").value     = retorno.pessoa_1;
						document.getElementById("form_pessoa_2").value     = retorno.pessoa_2;
                    }
                },
                error: function(data){
                    removeCarregando();
                    alert("Erro ao buscar dados do patrocinador do projeto");
                }
            });
        }
        $(document).ready(function(){
            iniciaFormulario();
        });


	</script>
</html>