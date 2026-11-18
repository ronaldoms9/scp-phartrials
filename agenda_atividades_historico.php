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
                                        <h1><?=traduzir("HISTÓRICO DE VERSÕES", $_SESSION['lang']);?> </h1>
                                    </div>
                                </div>
                            </div>
                            <div class="page-content">
                                <div class="container-fluid">

										<div class="page-content-inner">
											<div class="row widget-row">
												<div class="col-md-3"></div>
												<div class="col-md-6">
                                                    <div class="portlet light" style="width:100%">
                                                        <div class="portlet-title fixo">
                                                            <div class="caption font-blue-dark">
                                                                <span><i class="fa fa-th font-blue-dark" title="Formulários"></i></span>
                                                                <span class="caption-subject">&nbsp;&nbsp;<b>HISTÓRICO DE VERSÕES</b></span>
                                                            </div>
                                                            <div class="actions">
                                                                <div class="btn-group">
                                                                    <a href="javascript:novoPessoa();" class="btn btn-circle btn-default"><i class="fa fa-plus"></i> Incluir <B>NOVA VERSÃO</B></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="portlet-body">
                                                            <table id="tabelaPessoas" class="table table-striped table-bordered table-hover">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Versão</th>
                                                                        <th>Data</th>
                                                                        <th>Motivo</th>
                                                                        <th>Ações</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="divRetorno">
                                                                    <tr>
                                                                        <td colspan="4"></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>

                                                        </div><hr>
                                                        <div class="portlet-footer" style="text-align: center">
                                                            <button class="btn btn-primary" onclick="javascript:voltar();"> <i class="fa fa-save"></i> Voltar </button>
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

		<!-- Modal Nova Pessoa -->
		<div class="modal fade" id="modalNovaPessoa" tabindex="-1" role="dialog" aria-labelledby="modalNovaPessoaLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title" id="modalNovaPessoaLabel">Versão</h4>
					</div>
					<div class="modal-body">
						<form id="formNovaPessoa">
							<input type="hidden" id="versaoId" name="versaoId">
							<div class="form-group">
								<label for="pessoaNome" class="control-label">Versão <span style="color: red;">*</span></label>
								<input type="text" class="form-control" id="versaoNr" name="versaoNr" required>
							</div>
                            <div class="form-group">
								<label for="pessoaNome" class="control-label">Data <span style="color: red;">*</span></label>
								<input type="text" class="form-control" id="versaoData" name="versaoData" required>
							</div>
							<div class="form-group">
								<label for="versaoMotivo" class="control-label">Motivo</label>
                                <textarea class="form-control" id="versaoMotivo" name="versaoMotivo"></textarea>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
						<button type="button" class="btn btn-primary" onclick="salvarVersao()">Salvar</button>
					</div>
				</div>
			</div>
		</div>

	</body>
	<script src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js" type="text/javascript"></script>
	<script src="assets/global/plugins/jquery.min.js" type="text/javascript"></script>
	<?php
		include "rodape.php";
		include "global_js.php";
	?>


    <script>
        function novoPessoa(){
            // Limpa os campos do formulário
            $('#formNovaPessoa')[0].reset();
            // Abre o modal
            $('#modalNovaPessoa').modal('show');
            $('#pessoaId').val(0);
        }

        function salvarVersao(){
            var versaoNr     = $('#versaoNr').val();
            var versaoMotivo = $('#versaoMotivo').val();
            var versaoId     = $('#versaoId').val();
            var agendaId     = <?=$_GET['id'];?>;
            var versaoData   = $('#versaoData').val();

            // Validação básica
            if ((versaoNr.trim() == '')||(versaoMotivo.trim() == '')){
                alert('Por favor, preencha o campo Versão e Motivo.');
                return;
            }
            // Aqui você pode adicionar a lógica para salvar a pessoa
            // Por exemplo, fazer uma chamada AJAX para o servidor

            $.ajax({
                url: "php_php/php_agenda.php?act=SALVA_VERSAO",
                type: "POST",
                data: {
                    versaoNr    : versaoNr,
                    versaoMotivo : versaoMotivo,
                    versaoId     : versaoId,
                    agendaId     : agendaId,
                    versaoData   : versaoData
                },
                success: function(data){
                    console.log("Retorno->"+data);
                    alert("Operação realizada com sucesso");
                    iniciaLista();
                }
            });

            // Fecha o modal após salvar
            $('#modalNovaPessoa').modal('hide');
        }

        function voltar(){
            window.location.href = "agenda.php";
        }
        function iniciaLista(){
            $.ajax({
                url: "php_php/php_agenda.php?act=LISTA_VERSOES",
                type: "POST",
                data: {
                    agendaId: <?=$_GET['id'];?>
                },
                success: function(data){
                    document.getElementById("divRetorno").innerHTML = data;
                }
            });
        }
        function excluirVersao(id){
            if(confirm("Deseja realmente excluir esta versão?")){
                $.ajax({
                    url: "php_php/php_agenda.php?act=EXCLUIR_VERSAO&id="+id,
                    type: "POST",
                    data: {id: id},
                success: function(data){
                    alert("Versão excluída com sucesso");
                    console.log("Retorno->"+data);
                    iniciaLista();
                },
                error: function(data){
                    alert("Erro ao excluir versão");
                }
            });
            }
        }
        $(document).ready(function(){
            iniciaLista();
        });
        function editarPessoa(id){
            $.ajax({
                url: "php_php/php_cad_patrocinador.php?act=BUSCAR_DADOS_PESSOA",
                type: "POST",
                data: {id: id},
                success: function(data){
                    console.log("Retorno->"+data);
                    retorno = JSON.parse(data);
                    if(retorno.resultado == "OK"){
                        $('#modalNovaPessoa').modal('show');
                        $('#pessoaId').val(retorno.id);
                        $('#pessoaNome').val(retorno.nome);
                        $('#pessoaEmail').val(retorno.email);
                        $('#pessoaTelefone').val(retorno.telefone);
                        $('#modalNovaPessoa').modal('show');
                    }
                },
                error: function(data){
                    alert("Erro ao editar pessoa");
                }
            });
        }
    </script>


</html>