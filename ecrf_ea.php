<?php
	include_once "seguranca.php";

	$tabela = "adv_evento";
	$pagina = "ecrf_ea.php";
	if($_GET['id']>0){
		//Pegar o nro atual
		$query = "select nro_evento from $tabela where id = " . $_GET['id'];
		$res = $conexao->pesquisa($query);
		$row = mysqli_fetch_assoc($res);
		$nro_evento = $row['nro_evento'];
		$query = "delete from $tabela where id = " . $_GET['id'];
		$conexao->pesquisa($query);
		//Atualizar o nro de todos os eventos adversos posteriores ao deletado
		$query = "update $tabela set nro_evento = nro_evento - 1 where nro_evento > " . $nro_evento;
		$conexao->pesquisa($query);
		$msg="<script>alert('Registro deletado com sucesso!');</script>";
	}
?>
<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="application/vnd.ms-excel; charset=UTF-8">
<head>
        <?php
			include "global_css.php";
		?>
    <style>
           .menu {
            background:#F5F5F5;
            margin: auto;
            padding:10px;
            text-align:center;
            border: 1px solid #888;

            -moz-border-radius:7px;
            -webkit-border-radius:7px;
             border-radius:7px;
            }
    </style>
    <script>
    function excluir(ID,NOME){
                      var resp = confirm("Tem certeza que deseja excluir o registro do medicamento '"+NOME+"'?");
                      if(resp==true){
                           window.location = "ecrf_ea.php?opc=X&id="+ID;
                      }
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
    </script>
    <?=$msg;?>

</head>
<body>
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
                                        <h1><?=traduzir("Eventos Adversos", $_SESSION['lang']);?> </h1>
                                    </div>
                                </div>
                            </div>
                            <div class="page-content">
								<div class="row">
									<div class="col-md-1"></div>
									<div class="col-md-10" style="background-color: white;">

										<div class="portlet light">
											<div class="portlet-title">
												<div class="caption font-green-sharp">
													<i class="icon-share font-green-sharp"></i>
													<span class="caption-subject bold uppercase"> Eventos adversos</span>
												</div>

												<div class="actions">
													<div class="btn-group">
														<?php
															if($_SESSION['tipo_usuario']!=5){
																?>
																<a href="javascript:novoEvento();" class="btn btn-circle btn-default">
																	<i class="fa fa-plus"></i> Novo Evento </a>

																<?php
															}
														?>
														<a class="btn dark btn-outline btn-circle btn-sm" href="#" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" aria-expanded="true"> Exportar
															<i class="fa fa-angle-down"></i>
														</a>
														<ul class="dropdown-menu pull-right">
															<!--<li>
																<a href="javascript:exporta_tabela_excel('data');"> Excel</a>
															</li>-->
															<li>
																<a href="javascript:imprimir();"> PDF</a>
															</li>
															<li>
																<a href="javascript:exporta_excel();"> Resumo/Excel</a>
															</li>
														</ul>
													</div>
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
																				<option value="" selected>TODOS</option>
																				<?php
																					$query = "select distinct nro_paciente from $tabela where projeto = ".$_SESSION['projeto']." order by 1";
																					$res = $conexao->pesquisa($query);
																					while($row = mysqli_fetch_assoc($res)){
																						echo '<option value="'.$row['nro_paciente'].'">'.$row['nro_paciente'].'</option>';
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
																				<option value="" selected>TODOS</option>
																				<option value="1">1</option>
																				<option value="2">2</option>
																				<option value="3">3</option>
																				<option value="4">4</option>
																				<option value="5">5</option>
																				<option value="6">6</option>
																			</select>
																		</div>
																	</div>
																</div>
																<div class="col-md-2 column">
																	<div class="search-filter ">
																		<div class="search-label uppercase"><span class="caption-subject font-green-sharp sbold">Período</span>
																		</div>
																		<div class="input-icon right">
																			<select name="form_periodo" id="form_periodo" class="form-control">
																				<option value="" selected>TODOS</option>
																				<option value="1">1</option>
																				<option value="2">2</option>
																				<option value="3">3</option>
																				<option value="4">4</option>
																				<option value="5">5</option>
																				<option value="6">6</option>
																				<option value="Pós Estudo">Pós Estudo</option>
																			</select>
																		</div>
																	</div>
																</div>
																<div class="col-md-2 column">
																	<div class="search-filter ">
																		<div class="search-label uppercase"><span class="caption-subject font-green-sharp sbold">Gravidade</span>
																		</div>
																		<div class="input-icon right">
																			<select name="form_gravidade" id="form_gravidade" class="form-control">
																				<option value="" selected>TODOS</option>
																				<option value="2 - Não">Não Grave</option>
																				<option value="1 - Sim">EA Grave</option>
																			</select>
																		</div>
																	</div>
																</div>
																<div class="col-md-2 column">
																	<div class="search-filter ">
																		<div class="search-label uppercase"><span class="caption-subject font-green-sharp sbold">Status</span>
																		</div>
																		<div class="input-icon right">
																			<select name="form_status" id="form_status" class="form-control">
																				<option value="" selected>Todos</option>
																				<option value="1">Em curso</option>
																				<option value="2">Finalizado</option>
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
												<div class="row">
													<div class="scroller" style="height:400px" data-rail-visible="1" data-rail-color="yellow" data-handle-color="#a1b2bd">
														<?php
															$query = "select * from $tabela where projeto = ".$_SESSION['projeto']." order by cast(nro_paciente as int), nro_evento";
															$res = $conexao->pesquisa($query);
															if(mysqli_num_rows($res)<1){
																?>
																<table
																	class="table table-striped table-hover"
																	id="data"
																	name="data">
																		<thead>
																			<th class="tabela-head"><center># EA</center></th>
																			<th class="tabela-head"><center>Nro do Participante</center></th>
																			<th class="tabela-head"><center>Código do Participante</center></th>
																			<th class="tabela-head"><center>Status do Participante</center></th>
																			<th class="tabela-head"><?=traduzir("Nome do evento");?></th>
																			<th class="tabela-head">Intensidade</th>
																			<th class="tabela-head">EA Grave?</th>
																			<th class="tabela-head"><center><?=traduzir("Data de início");?></center></th>
																			<th class="tabela-head"><center><?=traduzir("Relação com a medicação");?></center></th>
																			<th class="tabela-head"><?=traduzir("Ação tomada");?></th>
																			<th class="tabela-head"><?=traduzir("Desfecho");?></th>
																			<th class="tabela-head"><center><?=traduzir("Data do término");?></center></th>
																			<th class="tabela-head"><center><?=traduzir("Comentários");?></center></th>
																			<th class="tabela-head"></th>
																		</thead>
																		<tbody id="divRetorno">
																			<tr><td colspan="13">Nenhum evento adverso cadastrado</td></tr>
																		</tbody>
																</table>
																<?php
															}
															else{
																?>
														<table
															class="table table-striped table-hover"
															id="data"
															name="data">
																<thead>
																	<th class="tabela-head"><center></center></th>
																	<th class="tabela-head"><center># EA</center></th>
																	<th class="tabela-head"><center>Grupo</center></th>
																	<th class="tabela-head"><center>Período</center></th>
																	<th class="tabela-head"><center>Nro do Participante</center></th>
																	<th class="tabela-head"><center>Código do Participante</center></th>
																	<th class="tabela-head"><center>Status do Participante</center></th>
																	<th class="tabela-head"><?=traduzir("Nome do evento");?></th>
																	<th class="tabela-head">Intensidade</th>
																	<th class="tabela-head">EA Grave?</th>
																	<th class="tabela-head"><center><?=traduzir("Data de início");?></center></th>
																	<th class="tabela-head"><center><?=traduzir("Relação com a medicação");?></center></th>
																	<th class="tabela-head"><?=traduzir("Ação tomada");?></th>
																	<th class="tabela-head"><?=traduzir("Desfecho");?></th>
																	<th class="tabela-head"><center><?=traduzir("Data do término");?></center></th>
																	<th class="tabela-head"><center><?=traduzir("Comentários");?></center></th>
																	<th class="tabela-head"></th>
																</thead>
																<tbody id="divRetorno">
																</tbody>
														</table>
														<?php
														}
														?>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">  <!-- Quadro de botões -->
									 <div class="col-md-1"></div>
									 <div class="col-md-10">
										 <?php
										 //if($_SESSION['user_tipo']<5){
										 ?>
										  <center>
												  <?php
												 if($_SESSION['tipo_usuario']!=5){
												 ?>
												  <button type="button" style="" class="btn btn-default" onclick="novoEvento();">  Cadastrar novo<br>evento </button>
												 <?php
												 }
												 ?>
												  <button type="button" style="" class="btn btn-default" onclick="imprimir();">  Imprimir<br>eventos </button>
										  </center>
										 <?php
										 //}
										 ?>
									 </div>
									 <div class="col-md-3"></div>
								</div>
			</div>
		</div>
	</div></div></div>
            <?php
                include "rodape.php";
            ?>
        </div>
        <?php
			include "global_js.php";
		?>
		<div class="modal fade" id="modalEditarEA" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-ea">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title">Editar evento adverso</h4>
					</div>
					<div class="modal-body">
						<iframe id="iframeEditarEA" title="Editar evento adverso" src="about:blank"></iframe>
					</div>
				</div>
			</div>
		</div>
		<style>
			#modalEditarEA { z-index: 10070; }
			#modalEditarEA .modal-ea { width: 92%; max-width: 1100px; }
			#modalEditarEA .modal-body { padding: 0; }
			#modalEditarEA iframe { width: 100%; height: 75vh; border: 0; display: block; background: #fff; }
		</style>
</body>
	<script>
		function abrirModalEA(url, titulo){
			var frame = document.getElementById('iframeEditarEA');
			frame.src = url;
			frame.title = titulo;
			$('#modalEditarEA .modal-title').text(titulo);
			$('#modalEditarEA').modal('show');
		}
		function editarEvento(id){
			abrirModalEA('novo_ea.php?opc=E&id=' + id + '&modal=1', 'Editar evento adverso');
		}
		function novoEvento(){
			abrirModalEA('novo_ea.php?modal=1', 'Novo evento adverso');
		}
		function fecharModalEA(recarregar){
			$('#modalEditarEA').modal('hide');
			if(recarregar){
				pesquisa();
			}
		}
		$('#modalEditarEA').on('show.bs.modal', function(){
			$(this).appendTo('body');
			setTimeout(function(){
				$('.modal-backdrop').last().css('z-index', 10060);
			}, 0);
		});
		$('#modalEditarEA').on('hidden.bs.modal', function(){
			document.getElementById('iframeEditarEA').src = 'about:blank';
		});
		function imprimir(){
			//var centro = prompt("Qual o centro a imprimir?", "Escolha o centro");
			//popup('rep_ea.php?centro='+centro);
			var participante = $('#form_participante').val();
			var grupo = $('#form_grupo').val();
			var periodo = $('#form_periodo').val();
			var gravidade = $('#form_gravidade').val();
			var status = $('#form_status').val();
			popup('rep_ea.php?participante='+participante+'&grupo='+grupo+'&periodo='+periodo+'&gravidade='+gravidade+'&status='+status);
		}
		function pesquisa(){
			mostraCarregando();
			$.ajax({
				url: 'php_php/php_ea.php?act=LISTAGEM',
				type: 'POST',
				data: {participante: $('#form_participante').val(),
						grupo: $('#form_grupo').val(),
						periodo: $('#form_periodo').val(),
						gravidade: $('#form_gravidade').val(),
						status: $('#form_status').val()
				},
				success: function(response){
					document.getElementById('divRetorno').innerHTML = response;
					removeCarregando();
				},
				error: function(xhr, status, error){
					document.getElementById('divRetorno').innerHTML = '<div class="alert alert-danger">Erro ao carregar os eventos adversos</div>';
					removeCarregando();
				}
			});
		}
		function exporta_excel(){
			var participante = $('#form_participante').val();
			var grupo = $('#form_grupo').val();
			var periodo = $('#form_periodo').val();
			var gravidade = $('#form_gravidade').val();
			var status = $('#form_status').val();

			mostraCarregando();

		var formData = {participante: participante,
						grupo    : grupo,
						periodo  : periodo,
						gravidade: gravidade,
						status   : status}
		varUrl = "php_php/exportacoes.php?act=EA_RESUMO";
		$.ajax({
			url: varUrl,
			type: "POST",
			data : formData,
			async : true,
			success: function(response, textStatus, jqXHR) {
				console.log(response);
				arquivo = response;
				window.location.href = "php_php/"+arquivo;
				removeCarregando();
				alert("Por favor, selecionar a planilha inteira e clicar em [QUEBRAR TEXTO AUTOMATICAMENTE] para ajustar a formatação.")
			},
			error: function (jqXHR, textStatus, errorThrown) {
				removeCarregando();
				alert("ERRO!");
			}
		});



		}
	</script>
	<script src="js/filtable.js?dev"></script>
	<script>
		$(function(){
			// Basic Filtable usage - pass in a div with the filters and the plugin will handle it
			$('#data').filtable({ controlPanel: $('.table-filters')});
		});
	</script>
</html>