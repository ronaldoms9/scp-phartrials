<?php
	include_once "seguranca.php";
	$CENTRO = $_GET['centro'];
?>
<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
	<?php
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
                                        <h1><?=traduzir("DESVIOS GERAIS DO ESTUDO", $_SESSION['lang']);?> </h1>
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
                                            <span><?=traduzir("Desvios Gerais do Estudo", $_SESSION['lang']);?></span>
                                        </li>
                                    </ul>

										<div class="page-content-inner">
											<div class="row widget-row">
												<div class="col-md-1"></div>
												<div class="col-md-10">
													<div class="portlet light">
														<div class="portlet-title">
															<div class="caption">
																<i class="icon-speech"></i>
																<span class="caption-subject bold uppercase"> <?=traduzir("Tabela de Desvios Gerais do Estudo", $_SESSION['lang']);?> </span>
															</div>
															<div class="actions">
																<a href="javascript:btnRegistro('modal_tab_geral.php?id=0');" class="btn btn-circle btn-default"><i class="fa fa-plus"></i> Novo</a>
																<a href="javascript:excel();" class="btn btn-circle btn-default"><i class="fa fa-file-excel-o"></i> Exportar</a>
                                                                <a href="javascript:atualizar();" class="btn btn-circle btn-default"><i class="fa fa-refresh"></i> Atualizar</a>
															</div>
														</div>
														<div class="portlet-body">
															<div class="row">
																<div class="col-md-10 column">
																	<div class="row">
																			<div class="col-md-2 column">
																				<div class="search-filter ">
																					<div class="search-label uppercase"><span class="caption-subject font-green-sharp sbold">Participante</span>
																					</div>
																					<div class="input-icon right">
																						<select name="form_participante" id="form_participante" class="form-control">
																							<option value="">TODOS</option>
																							<?php
																								$query = "select
																											distinct participante
																												from agenda_desvio_dieta
																													where projeto = ".$_SESSION['projeto']."
																														order by 1";
																								$res = $conexao->pesquisa($query);
																								while($row = mysqli_fetch_assoc($res)){
																									$selected = ($row['participante'] == $_GET['participante']) ? 'selected' : '';
																									echo '<option value="'.$row['participante'].'" '.$selected.'>'.$row['participante'].'</option>';
																								}
																							?>
																						</select>
																					</div>
																				</div>
																			</div>
																			<div class="col-md-2 column">
																				<div class="search-filter ">
																					<div class="search-label uppercase"><span class="caption-subject font-green-sharp sbold">Grupo</span>
																					</div>
																					<div class="input-icon right">
																						<select name="form_grupo" id="form_grupo" class="form-control">
																							<option value="">TODOS</option>
																							<?php

																								for($i=1; $i<=6; $i++){
																									$selected = ($i == $_GET['grupo']) ? 'selected' : '';
																									echo '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
																								}
																							?>
																						</select>
																					</div>
																				</div>
																			</div>

																	</div>
																</div>
																<div class="col-md-2 column">
																	<br><button class="btn green bold uppercase btn-block" onclick="pesquisa();" name="btnFiltrar" id="btnFiltrar"><i class="fa fa-search"></i>&nbsp;&nbsp;&nbsp; Filtrar</button>
																</div>
															</div>
															<hr>
															<div class="scroller" style="height:450px" data-rail-visible="1" data-rail-color="yellow" data-handle-color="#a1b2bd">
																<table width="100%" class="table table-hover table-condensed" style="background-color:white !important" id="tablePacientes" name="tablePacientes">
																	<thead class="header_formularios">
																		<tr>
																			<th width="250px"><?=traduzir("Centro");?></th>
																			<th width="100px"><?=traduzir("Data");?></th>
																			<th width="100px"><?=traduzir("Participante");?></th>
																			<th width="100px"><?=traduzir("Código do Participante");?></th>
																			<th width="100px"><?=traduzir("Grupo");?></th>
																			<th width="100px"><?=traduzir("Período");?></th>
																			<th width="100px"><?=traduzir("Tipo de Desvio");?></td>
																			<th width="100px"><?=traduzir("Comentários");?></td>
                                                                            <th width="60px"><?=traduzir("Ações");?></th>
																		</tr>
																	</thead>
																	<tbody class="lista_formularios">

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
	<?php
		include "rodape.php";
		include "global_js.php";
	?>
	</body>
    <div class="modal fade" id="modalRegistro" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="overflow-y:auto;">
		  <div class="modal-dialog modal-lg" style="overflow-y:auto;">
			<div class="modal-content" style="overflow-y:auto;">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel">Desvio</h4>
			  </div>
			  <div class="modal-body" style="overflow-y:auto;">
				  <a class="custom-close"> Fechar </a>
			  </div>
			  <div class="modal-footer">
              <button type="button" class="btn btn-primary" id="modal_form_salvar" onclick="salvarRegistro()">Salvar</button>
				<button type="button" class="btn btn-default" data-dismiss="modal" id="modal_form_fechar">Fechar</button>
			  </div>
			</div>
		  </div>
		</div>
	<script src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js" type="text/javascript"></script>
	<script>
		var table;
        function excel(){
			participante = $('#form_participante').val();
			grupo        = $('#form_grupo').val();
            popup('exportacoes_desvio_geral.php?participante='+participante+'&grupo='+grupo);
        }
		function popup(url){
			params  = 'width=700';
			params += ', height=500';
			params += ', top=0, left=0'
			params += ', fullscreen=yes';
			newwin=window.open(url,'windowname4', params);
			if (window.focus) {newwin.focus()}
			return false;
		}
        function atualizar(){
            document.location.reload();
            //table.ajax.reload(null, false);
        }
		function pesquisa(){
			document.location = "mon_tab_geral.php?participante="+$('#form_participante').val()+"&grupo="+$('#form_grupo').val();
		}
		$(function myFunction(){
			participante = $('#form_participante').val();
			grupo        = $('#form_grupo').val();

			console.log("URL->"+'php_php/tabelas.php?act=GERAL_LISTA&participante='+participante+'&grupo='+grupo);
			$.ajax({
				url: 'php_php/tabelas.php?act=GERAL_LISTA&participante='+participante+'&grupo='+grupo,
				type: 'GET',
				dom: 'Brtip',
				buttons: [
					'copy', 'csv', 'excel', 'pdf', 'print'
				],
				dataType: 'json',
				success: function success(result){
                    console.log("RES->"+result);
					table = $('#tablePacientes').DataTable({
						data: result,
						order: [0, 'asc'],
						processing: true,
						columns: [
							{ "data": "centro" },
							{ "data": "data" },
							{ "data": "participante" },
							{ "data": "codigo_participante" },
							{ "data": "grupo" },
							{ "data": "periodo" },
							{ "data": "tipo_desvio" },
							{ "data": "comentarios" },
							{ "data": "botao" }
						],
						language: {
							"sEmptyTable": "Nenhum registro encontrado",
							"sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
							"sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
							"sInfoFiltered": "(Filtrados de _MAX_ registros)",
							"sInfoPostFix": "",
							"sInfoThousands": ".",
							"sLengthMenu": "_MENU_ resultados por página",
							"sLoadingRecords": "Carregando...",
							"sProcessing": "Processando...",
							"sZeroRecords": "Nenhum registro encontrado",
							"sSearch": "Pesquisar",
							"oPaginate": {
								"sNext": "Próximo",
								"sPrevious": "Anterior",
								"sFirst": "Primeiro",
								"sLast": "Último"
							},
							"oAria": {
								"sSortAscending": ": Ordenar colunas de forma ascendente",
								"sSortDescending": ": Ordenar colunas de forma descendente"
							},
							"select": {
								"rows": {
									"_": "Selecionado %d linhas",
									"0": "Nenhuma linha selecionada",
									"1": "Selecionado 1 linha"
								}
							},
							"buttons": {
								"copy": "Copiar para a área de transferência",
								"copyTitle": "Cópia bem sucedida",
								"copySuccess": {
									"1": "Uma linha copiada com sucesso",
									"_": "%d linhas copiadas com sucesso"
								}
							},
							"processing": "<br><center><img src='imagens/loading.gif' width='250'></center>"
						}

					});
				},
				error: function error(result){
					console.log("ERRO->"+result);
				}
			})
		});
        function btnRegistro(endereco){
			$('#modalRegistro').modal('show').find('.modal-body').load(endereco);
		}
        function salvarRegistro(){
            let idModal             = $('#modal_form_id').val();
            let centro              = $('#modal_form_centro').val();
            let participante        = $('#modal_form_participante').val();
            let codigo_participante = $('#modal_form_codigo_participante').val();
            let tipo_desvio         = $('#modal_form_tipo_desvio').val();
            let comentarios         = $('#modal_form_comentarios').val();
            let id                  = $('#modal_form_id').val();
            let data                = $('#modal_form_data').val();
            let periodo             = $('#modal_form_periodo').val();
            let grupo               = $('#modal_form_grupo').val();

            if(centro == '' || participante == '' || codigo_participante == '' || tipo_desvio == '' || comentarios == '' || data == '' || periodo == '' || grupo == ''){
                alert("ATENÇÃO! Preencha todos os campos obrigatórios");
                return;
            }

            $.ajax({
                url: 'php_php/tabelas.php?act=GERAL_SALVAR',
                type: 'POST',
                data: {id: idModal, centro: centro, participante: participante, codigo_participante: codigo_participante, periodo: periodo, grupo: grupo, tipo_desvio: tipo_desvio, comentarios: comentarios, id: id, data: data},
                success: function success(result){
                    console.log(result);
                    alert("Registro salvo com sucesso");
                    $('#modal_form_fechar').click();
                    atualizar();
                },
                error: function error(result){
                    alert("Erro ao salvar registro");
                }
            })


            console.log("fim");


            $('#modal_form_fechar').click();

        }
		function excluir(id){
			Swal.fire({
				title: 'Tem certeza que deseja excluir este registro?',
				showDenyButton: true,
				confirmButtonText: 'Sim',
				cancelButtonText: 'Não',
			}).then((result) => {
				if (result.isConfirmed) {
					$.ajax({
						url: 'php_php/tabelas.php?act=GERAL_EXCLUIR',
						type: 'POST',
						data: {id: id},
						success: function success(result){
							console.log(result);
							atualizar();
							Swal.fire('Registro excluído com sucesso!', '', 'success');
						},
						error: function error(result){
							alert("Erro ao excluir registro");
						}
					})
				} else if (result.isDenied) {
					Swal.fire('Operação cancelada', '', 'info');
				}
			})
		}
	</script>
</html>