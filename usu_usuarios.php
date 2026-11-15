<?
	include_once __DIR__."/seguranca.php";
	include_once __DIR__."/class/conexao.class.php";
	include_once __DIR__."/class/usuario.class.php";
	include_once __DIR__."/funcoes.php";

?>
<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
	<?
		include "global_css.php";
	?>
    <link href="//cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.3.1/css/buttons.dataTables.min.css">
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
			.dataTables_wrapper .dataTables_processing {
				position: absolute;
				top: 30%;
				left: 50%;
				width: 50%;
				height: 150px;
				margin-left: -20%;
				margin-top: -25px;
				padding-top: 20px;
				text-align: center;
				font-size: 1.2em;
				background: white;
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
                                        <h1><a href="javascript:document.location='agenda.php';" class="btn btn-circle blue-dark" title="Voltar para a lista de CRFs" alt="Voltar para a lista de CRFs"><i class="fa fa-arrow-circle-left"></i></a> <?=traduzir("CADASTRO DE USUÁRIOS", $_SESSION['lang']);?> </h1>
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
                                            <span><?=traduzir("Cadastro de Usuários", $_SESSION['lang']);?></span>
                                        </li>
                                    </ul>

										<div class="page-content-inner">
											<div class="row widget-row">
												<div class="col-md-1"></div>

                                                <div class="col-md-3">
													<div class="portlet light">
														<div class="portlet-title">
                                                            <div class="caption">
																<i class="icon-speech"></i>
																<span class="caption-subject bold uppercase"> <?=traduzir("Lista de Usuários", $_SESSION['lang']);?> </span>
															</div>

														</div>
														<div class="portlet-body">
															<div class="scroller" style="height:450px" data-rail-visible="1" data-rail-color="yellow" data-handle-color="#a1b2bd">
																<table width="100%" class="table table-hover table-condensed" style="background-color:white !important">
																	<thead class="header_formularios">
																		<th><center><?=traduzir("Ativo?");?></center></th>
																		<th><?=traduzir("Usuário");?></th>
																	</thead>
																	<tbody id="retornoListaUsuario">

																	</tbody>
																</table>
															</div>
														</div>
													</div>
												</div>

												<div class="col-md-7">
													<div class="portlet light">
														<div class="portlet-title">
															<div class="caption">
																<i class="icon-speech"></i>
																<span class="caption-subject bold uppercase"> <?=traduzir("USUÁRIO", $_SESSION['lang']);?> </span>
															</div>
															<div class="actions">
																    <a href="javascript:limpaAtv();" class="btn btn-circle btn-default"><i class="fa fa-plus" ></i> Novo Usuário</a>

															</div>
														</div>
														<div class="portlet-body form">
															<!-- BEGIN FORM-->
															<form action="#" class="form-horizontal form-row-seperated">
																<div class="form-body">
																	<div class="form-group">
																		<label class="control-label col-md-3">Nome de usuário</label>
																		<div class="col-md-6">
																			<input type="text" class="form-control" id="form_usuario">
																		</div>
																	</div>
																	<div class="form-group">
																		<label class="control-label col-md-3">Nome</label>
																		<div class="col-md-9">
																			<input type="text" class="form-control" id="form_nome">
																		</div>
																	</div>
																	<div class="form-group">
																		<label class="control-label col-md-3">Email</label>
																		<div class="col-md-9">
																			<input type="text" class="form-control" id="form_email">
																		</div>
																	</div>
																	<div class="form-group">
																		<label class="control-label col-md-3">Celular</label>
																		<div class="col-md-9">
																			<input type="text" class="form-control" id="form_celular">
																		</div>
																	</div>
																	<div class="form-group">
																		<label class="control-label col-md-3">Tipo de usuário</label>
																		<div class="col-md-6">
																			<select class="form-control" id="form_tipo_usuario">
																				<option value=""></option>
																				<?php
																					$query = "select * from usu_usuario_tipo where registro_valido = 1 order by nome";
																					$resTipoUsuario = $varConexao->pesquisa($query);
																					while($rowTipoUsuario = mysqli_fetch_assoc($resTipoUsuario)){
																						echo "<option value='".$rowTipoUsuario['id']."'>".$rowTipoUsuario['nome']."</option>";
																					}
																				?>
																			</select>
																		</div>
																	</div>
																	<div class="form-group">
																		<label class="control-label col-md-3">Ativo?</label>
																		<div class="col-md-6">
																			<select class="form-control" id="form_ativo">
																				<option value=""></option>
																				<option value="on">Sim</option>
																				<option value="off">Não</option>
																			</select>
																		</div>
																	</div>

																</div>
																<div class="form-actions">
																	<div class="row">
																		<div class="col-md-offset-3 col-md-9">
																			<button type="submit" class="btn green" onclick="salvaUsuario()">
																				<i class="fa fa-pencil"></i> Salvar</button>
																		</div>
																	</div>
																</div>
															</form>
															<!-- END FORM-->
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
    <div class="modal fade" id="modalData" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="overflow-y:auto;">
		  <div class="modal-dialog " style="overflow-y:auto;">
			<div class="modal-content" style="overflow-y:auto;">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel">Cadastro de Data e Horário</h4>
			  </div>
			  <div class="modal-body" style="overflow-y:auto;">
				  <a class="custom-close"> Fechar </a>
			  </div>
			  <div class="modal-footer">
              <button type="button" class="btn btn-primary" id="modal_form_salvar" onclick="salvaData()">Salvar</button>
				<button type="button" class="btn btn-default" data-dismiss="modal" id="modal_form_fechar">Fechar</button>
			  </div>
			</div>
		  </div>
	</div>
    <div class="modal fade" id="modalAtv" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="overflow-y:auto;">
		  <div class="modal-dialog " style="overflow-y:auto;">
			<div class="modal-content" style="overflow-y:auto;">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel">Atividade</h4>
			  </div>
			  <div class="modal-body" style="overflow-y:auto;">
				  <a class="custom-close"> Fechar </a>
			  </div>
			  <div class="modal-footer">
              <button type="button" class="btn btn-primary" id="modal_form_salvar" onclick="salvaAtv()">Salvar</button>
				<button type="button" class="btn btn-default" data-dismiss="modal" id="modalAtv_form_fechar">Fechar</button>
			  </div>
			</div>
		  </div>
	</div>
	<script src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js" type="text/javascript"></script>
	<script src="php_js/js_ecrf.js" type="text/javascript"></script>
	<script>
        var id_usuario = 0;

        function limpaAtv(){
            $('#form_usuario').val('');
            $('#form_nome').val('');
            $('#form_email').val('');
            $('#form_celular').val('');
            $('#form_tipo_usuario').val('');
            $('#form_ativo').val('');
            id_usuario = 0;
        }


        function salvaUsuario(){

            let usuario          = $('#form_usuario').val();
            let nome             = $('#form_nome').val();
            let email            = $('#form_email').val();
            let celular          = $('#form_celular').val();
            let tipo_usuario     = $('#form_tipo_usuario').val();
            let ativo            = $('#form_ativo').val();
			if(usuario == '' || nome == '' || email == '' || celular == '' || tipo_usuario == '' || ativo == ''){
				alert("ATENÇÃO! Preencha todos os campos obrigatórios");
				return;
			}
			mostraCarregando();
			$.ajax({
				url: 'php_php/php_usu_usuarios.php?act=SALVA_USUARIO',
				type: 'POST',
				data: {usuario: usuario, nome: nome, email: email, celular: celular, tipo_usuario: tipo_usuario, ativo: ativo, id: id_usuario},
				success: function success(result){
					console.log(result);
					alert("Usuário salvo com sucesso");
					listaUsuario();
					removeCarregando();
				},
				error: function error(result){
					alert("Erro ao salvar registro");
					removeCarregando();
				}
			});
		}
        function selecionaUsuario(id){
            console.log("selecionarUsuario", id);
			id_usuario = id;
			$.ajax({
				url: 'php_php/php_usu_usuarios.php?act=MOSTRA_USUARIO',
				type: 'POST',
				data: {id: id},
				success: function success(result){
					console.log("success", result);
					let obj = JSON.parse(result);
					if(obj.status == 'success'){
						document.getElementById('form_usuario').value = obj.usuario;
						document.getElementById('form_nome').value = obj.nome;
						document.getElementById('form_email').value = obj.email;
						document.getElementById('form_celular').value = obj.celular;
						document.getElementById('form_tipo_usuario').value = obj.tipo_usuario;
						document.getElementById('form_ativo').value = obj.ativo;
					}
					else{
						alert(obj.message);
					}
				},
				error: function error(result){
					alert("Erro ao salvar registro");
				}
			});
        }
        function listaUsuario(){
            document.getElementById('retornoListaUsuario').innerHTML = '';
            $.ajax({
                url: 'php_php/php_usu_usuarios.php?act=NOVO_LISTA_USUARIO',
                type: 'POST',
                success: function success(result){
                    document.getElementById('retornoListaUsuario').innerHTML = result;
                },
                error: function error(result){
                    alert("Erro ao salvar registro");
                }
            });
        }
        function listaAtv(){
            document.getElementById('retornoListaAtv').innerHTML = '';
            $.ajax({
                url: 'php_php/php_agenda.php?act=AGENDA_ATIVIDADES_ATV_LISTA',
                type: 'POST',
                data: {id_data: id_data},
                success: function success(result){
                    console.log(result);
                    document.getElementById('retornoListaAtv').innerHTML = result;
                },
                error: function error(result){
                    alert("Erro ao salvar registro");
                }
            });
        }
        function excluirAtv(id){
            if(confirm("Deseja realmente excluir esta atividade?")){
                $.ajax({
                    url: 'php_php/php_agenda.php?act=AGENDA_ATIVIDADES_ATV_EXCLUIR',
                    type: 'POST',
                    data: {id: id},
                    success: function success(result){
                        alert("Atividade excluída com sucesso");
                        selecionarData(id_data);
                    },
                    error: function error(result){
                        alert("Erro ao excluir atividade");
                    }
                });
            }
        }

        $(document).ready(function(){
            listaUsuario();
        });
	</script>
</html>