<?
	include "seguranca.php";
	$act = $_GET['act'];
	$id_usuario = $_SESSION['user_id'];
	$log_usuario = $_SESSION['user_id'];
	$agendamento = $_GET["agendamento"];

	$qtd = 0;
	$campos = array("visita", "paciente", "monitora", "tipo", "achado", "acao", "data_abertura", "status", "data_fechamento", "centro", "responsavel", "criticidade", "categoria", "class_pendencia");
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
      for($i=0;$i<=count($campos)-1;$i++){
          if($pega>0){$sql_campos .= ", ";$sql_valores .= ", ";}else{$pega++;}
          $sql_campos  .= $campos[$i];
          $sql_valores .= "'".$_POST['form_'.$campos[$i]]."'";
      }
      $query = "insert into $tabela ($sql_campos, projeto) values ($sql_valores, ".$_SESSION['projeto'].");";
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
	<?
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
            <?
            ?>
            <div class="page-wrapper-row full-height">
                <div class="page-wrapper-middle">
                    <div class="page-container">
                        <div class="page-content-wrapper">
                            <div class="page-content">
									<div class="col-md-12">
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
																				<a href="javascript:document.location='agendamento_novo_pendencia_int.php?id=<?=$agendamento;?>'" class="btn btn-circle btn-default btn-sm">
																					<i class="fa fa-plus"></i> Nova pendência </a>

																			</td>
																		</tr>
																	</table>
																</div>
																<br>
															</div>
															<table id="listagem" name="listagem" width="100%" class="table table-striped" style="font-family: 'Abel';font-size: 22px;">
																<thead>

																		<th class="tabela-head" width="10%"><center>Centro</th>
																		<th class="tabela-head" width="10%"><center>Paciente</center></th>
																		<th class="tabela-head"><center>Tipo</th>
																		<th class="tabela-head" width="40%">Achado</th>
																		<th class="tabela-head">Ação</th>
																		<th class="tabela-head"><center>Data de Abertura</center></th>
																		<th class="tabela-head"><centeR>Status</center></th>
																		<th class="tabela-head"><center>Data de Fechamento</center></th>
																	</div>
																</thead>
																<tbody>
																	<?
																		//Pego a data dessa visita
																		$query = "select agenda.*, cfg_centro.nome as nome_centro from agenda inner join cfg_centro on agenda.centro = cfg_centro.id where agenda.id = " . $_GET['agendamento'];
																		$resAgenda = pesquisa($query);
																		$rowAgenda = mysqli_fetch_assoc($resAgenda);
																		$dataAgendamento = $rowAgenda['data'];
																		$dataAgendamento = substr($dataAgendamento,6,2) . "/" . substr($dataAgendamento,4,2) . "/" . substr($dataAgendamento,0,4);
																		$centroAgendamento = $rowAgenda['nome_centro'];
																		$query = "SELECT agenda_lista_pendencia.*, usu_usuario.nome as monitora_nome
																					from agenda_lista_pendencia
																						left join usu_usuario on  agenda_lista_pendencia.monitora = usu_usuario.id
																					where agenda_lista_pendencia.projeto = " . $_SESSION['projeto'] . " and agenda_lista_pendencia.visita = '$dataAgendamento'
																						and agenda_lista_pendencia.centro = '".$centroAgendamento."'
																					order by cast(paciente as int)";
																		//echo $query;
																		$res_pend = pesquisa($query);
																		if(mysqli_num_rows($res_pend)==0){
																			echo "<tr><td colspan='7'>Nenhuma pend&ecirc;ncia encontrada....</td></tr>";
																		}else{
																			while($row_pendencias = mysqli_fetch_assoc($res_pend)){
																	?>
																			<tr>
																				<td><?=$row_pendencias['centro'];?></td>
																				<td><center><?=$row_pendencias['paciente'];?></center></td>
																				<td><?=$row_pendencias['tipo'];?></td>
																				<td>
																					<font size="2"><?=$row_pendencias['achado'];?></font>
																				</td>
																				<td><?=$row_pendencias['acao'];?></td>
																				<td><center><?=$row_pendencias['data_abertura'];?></center></td>
																				<td><?=$row_pendencias['status'];?></td>
																				<td><center><?=$row_pendencias['data_fechamento'];?></center></td>
																			</tr>
																		<?
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
	</body>
	<?

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
</html>