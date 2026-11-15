<?
	include_once "seguranca.php";
	$CENTRO = $_GET['centro'];
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
                                        <h1><a href="javascript:document.location='agenda.php';" class="btn btn-circle blue-dark" title="Voltar para a lista de CRFs" alt="Voltar para a lista de CRFs"><i class="fa fa-arrow-circle-left"></i></a> <?=traduzir("AGENDA DE ATIVIDADES", $_SESSION['lang']);?> </h1>
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
                                            <span><?=traduzir("Desvios de Coleta", $_SESSION['lang']);?></span>
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
																<span class="caption-subject bold uppercase"> <?=traduzir("Datas e Horários", $_SESSION['lang']);?> </span>
															</div>
                                                            <div class="actions">
																    <a href="javascript:btnRegistro('modal_ag_atividade_data.php');" class="btn btn-circle btn-default"><i class="fa fa-plus" ></i> Novo</a>

															</div>
														</div>
														<div class="portlet-body">
															<div class="scroller" style="height:450px" data-rail-visible="1" data-rail-color="yellow" data-handle-color="#a1b2bd">
																<table width="100%" class="table table-hover table-condensed" style="background-color:white !important">
																	<thead class="header_formularios">
																		<th><center><?=traduzir("Data");?></center></th>
																		<th><?=traduzir("Período");?></th>
																	</thead>
																	<tbody id="retornoListaData">

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
																<span class="caption-subject bold uppercase"> <?=traduzir("Atividades do horário selecionado", $_SESSION['lang']);?> </span>
															</div>
															<div class="actions">
                                                                <div id="btn_registro_atv" style="display: none;">
																    <a href="javascript:btnRegistroAtv('modal_ag_atividade_atv.php?id=0');" class="btn btn-circle btn-default"><i class="fa fa-plus"></i> Novo</a>
                                                                </div>
															</div>
														</div>
														<div class="portlet-body">
															<div class="scroller" style="height:450px" data-rail-visible="1" data-rail-color="yellow" data-handle-color="#a1b2bd">
																<table width="100%" class="table table-hover table-condensed" style="background-color:white !important" id="tablePacientes" name="tablePacientes">
																	<thead class="header_formularios">
																		<tr>
                                                                            <th width="100px" style="text-align: center;"><?=traduzir("Data");?></th>
																			<th><?=traduzir("Atividade");?></th>
																		</tr>
																	</thead>
																	<tbody class="lista_formularios" id="retornoListaAtv">

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
              <button type="button" class="btn btn-primary" id="modal_form_salvar" onclick="salvaAtvNovo()">Salvar</button>
				<button type="button" class="btn btn-default" data-dismiss="modal" id="modalAtv_form_fechar">Fechar</button>
			  </div>
			</div>
		  </div>
	</div>
	<div class="modal fade" id="modalEditarData" tabindex="-1" role="dialog" aria-labelledby="modalEditarDataLabel" aria-hidden="true" style="overflow-y:auto;">
		  <div class="modal-dialog" style="overflow-y:auto;">
			<div class="modal-content" style="overflow-y:auto;">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="modalEditarDataLabel"><?=traduzir("Editar Data e Horário", $_SESSION['lang']);?></h4>
			  </div>
			  <div class="modal-body" style="overflow-y:auto;">
				<input type="hidden" id="edit_id_data" value="">
				<div class="form-group">
					<label class="control-label"><?=traduzir("Data");?></label>
					<input type="text" class="form-control input-inline input-medium" style="text-align: center;" id="edit_modal_form_data" onkeyUp="mascara(this,'##/##/####');">
				</div>
				<div class="form-group">
					<label class="control-label"><?=traduzir("Período");?></label>
					<input type="text" class="form-control input-inline input-medium" style="text-align: center;" id="edit_modal_form_periodo">
				</div>
			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-primary" onclick="salvaDataEdicao()"><?=traduzir("Salvar");?></button>
				<button type="button" class="btn btn-default" data-dismiss="modal"><?=traduzir("Fechar");?></button>
			  </div>
			</div>
		  </div>
	</div>
	<script src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js" type="text/javascript"></script>
	<script src="php_js/js_ecrf.js" type="text/javascript"></script>
	<script>
        var id_data = 0;
        function limpaAtv(){
            document.getElementById('retornoListaAtv').innerHTML = '<tr><td colspan="4" style="text-align: left; font-size: 14px; font-weight: bold;">Nenhuma data selecionada</td></tr>';
            $('#btn_registro_atv').hide();
        }

        function btnRegistro(endereco){
			$('#modalData').modal('show').find('.modal-body').load(endereco);
		}
        function btnRegistroAtv(endereco){
			$('#modalAtv').modal('show').find('.modal-body').load(endereco);
		}

        function salvaAtvNovo(){
            let atv              = $('#modal_form_atividade').val();
            if(atv == ''){
                alert("ATENÇÃO! Preencha todos os campos obrigatórios");
                return;
            }
            $.ajax({
                url: 'php_php/php_agenda.php?act=AGENDA_ATIVIDADES_ATV_INCLUIR',
                type: 'POST',
                data: {atv: atv},
                success: function success(result){
                    alert("Atividade salva com sucesso");
                    $('#modalAtv_form_fechar').click();
                    selecionarData(id_data);

                },
                error: function error(result){
                    alert("Erro ao salvar registro");
                }
            });
            $('#modal_form_fechar').click();
        }
        function salvaAtv(){
            let atv              = $('#modal_form_atividade').val();
            if(atv == ''){
                alert("ATENÇÃO! Preencha todos os campos obrigatórios");
                return;
            }
            $.ajax({
                url: 'php_php/php_agenda.php?act=AGENDA_ATIVIDADES_ATV_SALVAR',
                type: 'POST',
                data: {id_data: id_data, atv: atv},
                success: function success(result){
                    alert("Atividade salva com sucesso");
                    $('#modalAtv_form_fechar').click();
                    selecionarData(id_data);

                },
                error: function error(result){
                    alert("Erro ao salvar registro");
                }
            });
            $('#modal_form_fechar').click();
        }
        function salvaData(){
            let agenda           = <?=$_GET['id'];?>;
            let data             = $('#modal_form_data').val();
            let periodo          = $('#modal_form_periodo').val();
            if(data == '' || periodo == ''){
                alert("ATENÇÃO! Preencha todos os campos obrigatórios");
                return;
            }
            $.ajax({
                url: 'php_php/php_agenda.php?act=AGENDA_ATIVIDADES_DATA_SALVAR',
                type: 'POST',
                data: {agenda: agenda, data: data, periodo: periodo},
                success: function success(result){
                    alert("Data/Período salvo com sucesso");
                    $('#modal_form_fechar').click();
                    listaData();
                    limpaAtv();
                },
                error: function error(result){
                    alert("Erro ao salvar registro");
                }
            });
            $('#modal_form_fechar').click();
        }
        function editarData(id, data, periodo){
            $('#edit_id_data').val(id);
            $('#edit_modal_form_data').val(data);
            $('#edit_modal_form_periodo').val(periodo);
            $('#modalEditarData').modal('show');
        }
        function salvaDataEdicao(){
            let id = $('#edit_id_data').val();
            let data = $('#edit_modal_form_data').val();
            let periodo = $('#edit_modal_form_periodo').val();
            if(data == '' || periodo == ''){
                alert("ATENÇÃO! Preencha todos os campos obrigatórios");
                return;
            }
            $.ajax({
                url: 'php_php/php_agenda.php?act=AGENDA_ATIVIDADES_DATA_ATUALIZAR',
                type: 'POST',
                data: {id: id, data: data, periodo: periodo},
                success: function success(result){
                    alert("Data/Período atualizado com sucesso");
                    $('#modalEditarData').modal('hide');
                    listaData();
                    limpaAtv();
                },
                error: function error(result){
                    alert("Erro ao atualizar registro");
                }
            });
        }
        function excluirData(id){
            if(confirm("Deseja realmente excluir esta data?")){
                $.ajax({
                    url: 'php_php/php_agenda.php?act=AGENDA_ATIVIDADES_DATA_EXCLUIR',
                    type: 'POST',
                    data: {id: id},
                    success: function success(result){
                        alert("Data excluída com sucesso");
                        listaData();
                        limpaAtv();
                    },
                    error: function error(result){
                        alert("Erro ao excluir data");
                    }
                });
            }
        }
        function selecionarData(id){
            id_data = id;
            mostraCarregando();
            $.ajax({
                url: 'php_php/php_agenda.php?act=AGENDA_ATIVIDADES_ATV_LISTA',
                type: 'POST',
                data: {id: id},
                success: function success(result){
                    removeCarregando();
                    document.getElementById('retornoListaAtv').innerHTML = result;
                    $('#btn_registro_atv').show();

                },
                error: function error(result){
                    removeCarregando();
                    alert("Erro ao selecionar registro");
                }
            });
            $('#modal_form_fechar').click();
        }
        function listaData(){
            document.getElementById('retornoListaData').innerHTML = '';
            let agenda           = <?=$_GET['id'];?>;
            $.ajax({
                url: 'php_php/php_agenda.php?act=AGENDA_ATIVIDADES_DATA_LISTA',
                type: 'POST',
                data: {agenda: agenda},
                success: function success(result){
                    document.getElementById('retornoListaData').innerHTML = result;
                    limpaAtv();
                },
                error: function error(result){
                    alert("Erro ao salvar registro");
                }
            });
            $('#modal_form_fechar').click();
        }
        function listaAtv(){
            document.getElementById('retornoListaAtv').innerHTML = '';
            let id_data = <?=$_GET['id'];?>;
            $.ajax({
                url: 'php_php/php_agenda.php?act=AGENDA_ATIVIDADES_ATV_LISTA',
                type: 'POST',
                data: {id_data: id_data},
                success: function success(result){
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
        function salvaAtv(marcado, id){
            var marcado = marcado;
            if(marcado == true){
                marcado = 1;
            }else{
                marcado = 0;
            }
            console.log(id);
            id_atv = id.split('_')[0];
            id_atividade = id.split('_')[1];
            console.log(id_atv);
            console.log(id);
            $.ajax({
                url: 'php_php/php_agenda.php?act=AGENDA_ATIVIDADES_ATV_SALVAR',
                type: 'POST',
                data: {id_atividade: id_atividade,
                        id_atv: id_atv,
                        marcado: marcado},
                success: function success(result){
                    console.log(result);
                }
            });
        }

        $(document).ready(function(){
            listaData();
            $(document).on('click', '.btn-editar-data', function(e){
                e.preventDefault();
                var id = $(this).attr('data-id');
                var data = $(this).attr('data-data');
                var periodo = $(this).attr('data-periodo') || '';
                editarData(id, data, periodo);
            });
        });
	</script>
</html>