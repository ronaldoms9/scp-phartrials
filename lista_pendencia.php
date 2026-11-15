<?php
	include "seguranca.php";
	$act = $_GET['act'];
	$id_usuario = $_SESSION['user_id'];
	$log_usuario = $_SESSION['user_id'];
	$qtd = 0;
	$campos = array("paciente", "monitora", "tipo", "achado", "acao", "data_abertura", "status", "data_fechamento", "centro", "resolvido_por", "responsavel");
	$tabela = "agenda_lista_pendencia";
	if($act=='A'){
	  $id = $_GET['id'];
      $query = "update $tabela set id = id, ";
      $sql_campos = "";
      for($i=0;$i<=count($campos)-1;$i++){
          if($pega>0){$sql_campos .= ", ";}else{$pega++;}
          $sql_campos .= $campos[$i] . " = '" . $_POST['form_'.$campos[$i]] . "'";
      }
      $query .= $sql_campos . " where id = ".$id;
      pesquisa($query);
      $msg=1;
	}
	if($act=='X'){
      $query = "delete from $tabela where id = " . $_GET['id'];
      pesquisa($query);
	}
	if($act=='I'){
      $sql_campos = "";
      $sql_valores = "";
      $pega = 0;

	  $varVisita = $_POST['form_visita'];
	  $query = "select * from agenda where nro_visita = " . $varVisita;
	  $resVi = pesquisa($query);
	  $rowVi = mysqli_fetch_assoc($resVi);
	  $varVisita = $rowVi['id'];

      for($i=0;$i<=count($campos)-1;$i++){
          if($pega>0){$sql_campos .= ", ";$sql_valores .= ", ";}else{$pega++;}
          $sql_campos  .= $campos[$i];
          $sql_valores .= "'".$_POST['form_'.$campos[$i]]."'";
      }
      $query = "insert into $tabela ($sql_campos, projeto, visita) values ($sql_valores, ".$_SESSION['projeto'].", '$varVisita');";
      //echo $query;
	  pesquisa($query);
	}

?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
	<?php
		include "global_css.php";
	?>
	<!--<link href="css/fullcalendar.min.css" rel="stylesheet" type="text/css">-->
	<link href="plugin/css/fullcalendar.min.css" rel="stylesheet" type="text/css">
	<link href="plugin/css/fullcalendar.print.css" rel='stylesheet' media='print' />
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<link href="https://fonts.googleapis.com/css?family=Merriweather+Sans" rel="stylesheet">
	<link rel="shortcut icon" href="favicon.png">

	<head>
		<style>

			.lista_formularios{
				font-size: 13px !important;
				font-family: 'Abel', sans-serif;
			}
			.label{
				font-weight: bold !important;
				font-family: 'Abel', sans-serif !important;
				font-size: 14px !important;
				font-color: black !important;
				color: black;
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
		</style>
	</head>
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
                                        <h1><a href="javascript:volta_lista();" class="btn btn-circle blue-dark" title="Voltar para a lista de Monitorias" alt="Voltar para a lista de Monitorias"><i class="fa fa-arrow-circle-left"></i></a> <?=traduzir("Lista de Pendências", $_SESSION['lang']);?> </h1>
                                    </div>
                                </div>
                            </div>
                            <div class="page-content">
									<div class="col-md-1"></div>
									<div class="col-md-10">
										<!-- BEGIN BORDERED TABLE PORTLET-->
										<div class="portlet light portlet-fit ">
											<div class="portlet-body">
														<div class="row">
															<div class="portlet light">
																<div class="portlet-body">
																	<div class="row">
																		<div style="text-align:center">

																			<table width="100%">
																				<tr>
																					<td align="right">
																						<?php
																						//if($_GET['visita']!=""){
																						?>
																							<a href="javascript:document.location='novo_pendencia.php?id=<?=$agendamentoId;?>&visita=<?=$_GET['visita'];?>';" class="btn btn-circle btn-default btn-sm">
																							<i class="fa fa-plus"></i> Nova pendência </a>
																						<?php
																						//}
																						?>
																					</td>
																				</tr>
																			</table>
																		</div>
																		<br>
																	</div>
																	<div style="overflow-y: scroll; height:500px !important">
																		<table id="listagem" name="listagem" width="100%" class="table table-striped" style="font-family: 'Abel';font-size: 22px;">
																			<thead>
																					<th class="tabela-head" width="10%"><center>Centro<br>
																						<div class="table-filters">
																							<select id="filter-city" data-filter-col="0" style="min-width:60px" class="label">
																								<option value="">TODOS</option>
																								<?php
																									$query = "select nome from cfg_centro order by nome";
																									$res_c = pesquisa($query);
																									while($row_c=mysqli_fetch_assoc($res_c)){
																										echo '<option value="'.$row_c['nome'].'">'.$row_c['nome'].'</option>';
																									}
																								?>
																							</select>
																							</center>
																						</div>
																					</th>
																					<th class="tabela-head" width="10%"><center>Participante</center></th>
																					<th class="tabela-head"><center>Tipo<br>
																						<div class="table-filters">
																							<select id="filter-city" data-filter-col="2" style="min-width:60px" class="label">
																								<option value="">TODOS</option>
																								<option value="DESVIO">DESVIO</option>
																								<option value="ACHADO">ACHADO</option>
																								<option value="SUGESTÃO">SUGESTÃO</option>
																							</select></center>
																						</div>
																					</th>
																					<th class="tabela-head" width="40%">Achado</th>
																					<th class="tabela-head">Responsável</th>
																					<th class="tabela-head">Ação</th>
																					<th class="tabela-head">Data de Abertura</th>
																					<th class="tabela-head"><centeR>Status<br>
																						<div class="table-filters">
																							<select id="filter-city" data-filter-col="4" style="min-width:60px" class="label">
																								<option value="">TODOS</option>
																								<option value="RESOLVIDO">RESOLVIDO</option>
																								<option value="PENDENTE">PENDENTE</option>
																							</select>
																						</div>
																					</center></th>
																					<th class="tabela-head">Data de Fechamento</th>
																					<th class="tabela-head">Resolvido por</th>
																				</div>
																			</thead>
																			<tbody style="overflow-y: scroll; height:800px !important">
																				<?php

																					$query = "SELECT
																						agenda.id as agendamento_id,
																						agenda.centro,
																						agenda.nro_visita,
																						cfg_centro.nome as centro_nome,
																						agenda_lista_pendencia.*
																						from agenda
																							inner join agenda_lista_pendencia on agenda.centro = agenda_lista_pendencia.centro and agenda.id = agenda_lista_pendencia.visita
																							inner join cfg_centro on agenda.centro = cfg_centro.id
																							where agenda_lista_pendencia.projeto = " . $_SESSION['projeto'];
																					if($_GET['visita']!=""){
																						$query .= " and agenda.id = " . $_GET['visita'];
																					}
																					$res_pend = pesquisa($query);
																					//echo $query;
																					if(mysqli_num_rows($res_pend)==0){
																						echo "<tr><td colspan='7'>Nenhuma pendência encontrada....</td></tr>";
																					}else{
																						while($row_pendencias = mysqli_fetch_assoc($res_pend)){
																				?>
																						<tr onclick="edita(<?=$row_pendencias['id'].",".$row_pendencias['visita'];?>);" style="cursor: pointer;">
																							<td><?=$row_pendencias['centro_nome'];?></td>
																							<td><?=$row_pendencias['paciente'];?></td>
																							<td><?=$row_pendencias['tipo'];?></td>
																							<td>
																								<font size="2"><?=$row_pendencias['achado'];?></font>
																							</td>
																							<td><?=$row_pendencias['responsavel'];?></td>
																							<td><?=$row_pendencias['acao'];?></td>
																							<td><?=$row_pendencias['data_abertura'];?></td>
																							<td><?=$row_pendencias['status'];?></td>
																							<td><?=$row_pendencias['data_fechamento'];?></td>
																							<td><?=$row_pendencias['resolvido_por'];?></td>
																						</tr>
																					<?php
																						}
																					}
																					?>
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
	</body>
	<?php
		include "rodape.php";
		include "global_js.php";
	?>
	<script src="js/moment.min.js"></script>
<script>
	function excluir(ID){
		var resp = confirm("Tem certeza que deseja excluir essa pendência?");
		if(resp==true){
			window.location = "agendamentos_lista_pendencia.php?act=X&id="+ID;
		}
	}
	function edita(id, visita){
		window.location = "novo_pendencia.php?act=E&id="+id+"&visita="+visita;
	}
	function ExportarEXCEL(tabela){
		var tab_text="<table border='2px'><tr bgcolor='#87AFC6'>";
		var textRange; var j=0;
		tab = document.getElementById(tabela); // id of table

		console.log("Tamanho -> " + tab.rows.length);
		for(j = 0 ; j < tab.rows.length ; j++) {
			tab_text=tab_text+tab.rows[j].innerHTML+"</tr>";
		}

		tab_text=tab_text+"</table>";
		tab_text= tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
		tab_text= tab_text.replace(/<img[^>]*>/gi,""); // remove if u want images in your table
		tab_text= tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params

		var ua = window.navigator.userAgent;
		var msie = ua.indexOf("MSIE ");

		if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)){      // If Internet Explorer
			txtArea1.document.open("txt/html","replace");
			txtArea1.document.write(tab_text);
			txtArea1.document.close();
			txtArea1.focus();
			sa=txtArea1.document.execCommand("SaveAs",true,"Say Thanks to Sumit.xls");
		}
		else                 //other browser not tested on IE 11
			sa = window.open('data:application/vnd.ms-excel,' + escape(tab_text));
		return (sa);
	}

	function pdf(){
		var centro = prompt("Qual o centro a imprimir?", "Escolha o centro");
		popup('rep_pendencias2.php?centro='+centro);
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
	function volta_lista(){
			muda_pagina('agenda.php?volta=listagem');
		}

</script>
	<script src="js/filtable.js?dev"></script>
	<script>
		$(function(){
			// Basic Filtable usage - pass in a div with the filters and the plugin will handle it
			$('#listagem').filtable({ controlPanel: $('.table-filters')});
		});
	</script>
</html>