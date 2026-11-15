<?
	include_once "seguranca.php";
    include_once "php_php/php_cad_patrocinador.php";
?>
<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<?
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
                                        <h1><?=traduzir("CADASTRO DE ATIVIDADES", $_SESSION['lang']);?> </h1>
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
                                                                <span class="caption-subject">&nbsp;&nbsp;<b>CADASTRADOS</b></span>
                                                            </div>
                                                            <div class="actions">
                                                                <div class="btn-group">
                                                                    <a href="javascript:novoAtividade();" class="btn btn-circle btn-default"><i class="fa fa-plus"></i> Incluir <B>NOVO</B></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="portlet-body">
                                                            <table id="tabelaAtividades" class="table table-striped table-bordered table-hover">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Atividade</th>
                                                                        <th width="10%"><center>Ativo?</center></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="divRetorno">
                                                                    <tr>
                                                                        <td colspan="4"></td>
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

		<!-- Modal Nova Atividade -->
		<div class="modal fade" id="modalNovaAtividade" tabindex="-1" role="dialog" aria-labelledby="modalNovaAtividadeLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title" id="modalNovaAtividadeLabel">Nova Atividade</h4>
					</div>
					<div class="modal-body">
						<form id="formNovaAtividade">
							<input type="hidden" id="atividadeId" name="atividadeId">
							<div class="form-group">
								<label for="atividadeNome" class="control-label">Atividade <span style="color: red;">*</span></label>
								<input type="text" class="form-control" id="atividadeNome" name="atividadeNome" required>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button id="modalNovaAtividade_form_fechar" type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
						<button type="button" class="btn btn-primary" onclick="salvarAtividade()">Salvar</button>
					</div>
				</div>
			</div>
		</div>

	</body>
	<script src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js" type="text/javascript"></script>
	<script src="assets/global/plugins/jquery.min.js" type="text/javascript"></script>
	<?
		include "rodape.php";
		include "global_js.php";
	?>


    <script>
        function novoAtividade(){
            // Limpa os campos do formulário
            $('#formNovaAtividade')[0].reset();
            // Abre o modal
            $('#modalNovaAtividade').modal('show');
            $('#atividadeId').val(0);
        }

        function salvarAtividade(){
            var atividadeNome = $('#atividadeNome').val();
            var atividadeId = $('#atividadeId').val();

            // Validação básica
            if(atividadeNome.trim() == ''){
                alert('Por favor, preencha o campo Atividade.');
                return;
            }
            // Aqui você pode adicionar a lógica para salvar a atividade
            // Por exemplo, fazer uma chamada AJAX para o servidor

            $.ajax({
                url: "php_php/php_cad_atividades.php?act=SALVA_ATIVIDADE",
                type: "POST",
                data: {
                    atividadeNome : atividadeNome,
                    atividadeId   : atividadeId
                },
                success: function(data){
                    alert("Operação realizada com sucesso");
                    iniciaLista();
                    $('#modalNovaAtividade_form_fechar').click();
                }
            });

            // Fecha o modal após salvar
            $('#modalNovaAtividade_form_fechar').click();
        }
        function iniciaLista(){
            $.ajax({
                url: "php_php/php_cad_atividades.php?act=LISTA_ATIVIDADES",
                type: "POST",
                success: function(data){
                    document.getElementById("divRetorno").innerHTML = data;
                }
            });
        }
        function editarAtividade(id){
            $.ajax({
                url: "php_php/php_cad_atividades.php?act=BUSCAR_DADOS_ATIVIDADE",
                type: "POST",
                data: {id: id},
                success: function(data){
                    console.log("Retorno->"+data);
                    retorno = JSON.parse(data);
                    if(retorno.resultado == "OK"){
                        $('#modalNovaAtividade').modal('show');
                        $('#atividadeId').val(retorno.id);
                        $('#atividadeNome').val(retorno.atividade);
                    }
                },
                error: function(data){
                    alert("Erro ao editar pessoa");
                }
            });
        }
        function salvarAtivo(ativo, id){
            ativo = ativo?"1":"0";
            $.ajax({
                url: "php_php/php_cad_atividades.php?act=SALVAR_ATIVO",
                type: "POST",
                data: {ativo: ativo, id: id},
                success: function(data){
                    console.log("Retorno->"+data);
                    iniciaLista();
                },
                error: function(data){
                    console.log("Erro->"+data);
                }
            });
        }
        $(document).ready(function(){
            iniciaLista();
        });

    </script>


</html>