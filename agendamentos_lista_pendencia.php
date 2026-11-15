<?php
	session_start();
	include_once "seguranca.php";




	$act = strtoupper($_GET['act']);
	$agendamentoId = $_GET[base64_encode('cam')];

	$campos = array("visita", "paciente", "monitora", "tipo", "achado", "acao", "data_abertura", "status", "data_fechamento", "centro", "resolvido_por", "categoria", "criticidade", "visita_data", "data_visita_fechamento", "responsavel", "formulario", "class_pendencia");
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
      $conexao->pesquisa($query);
      $msg=1;
	}
	if($act=='X'){
      $query = "delete from $tabela where id = " . $_GET['id'];
      $conexao->pesquisa($query);
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
      $query = "insert into $tabela ($sql_campos, projeto) values ($sql_valores, '".$_SESSION['projeto']."');";

      $conexao->pesquisa($query);
	}


?>
<html>

<head>
<meta http-equiv="Content-Language" content="pt-br">
<link href="//cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
<?php
	include "global_css.php";
?>
<title>New Page 1</title>


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
<body>
	<div class="row">
		<div class="portlet light">
			<div class="portlet-title fixo">

			<div class="row">




				<div class="portlet light ">
					<div class="portlet-title">
						<div class="caption">
							<i class="icon-bubble font-green-sharp"></i>
							<span class="caption-subject font-green-sharp sbold">Lista de Pendências </span>
						</div>
						<div class="actions">
							<div class="btn-group">
								<a class="btn green-haze btn-outline btn-circle btn-sm" href="javascript:;" data-toggle="dropdown" data-hover="dropdown" data-close-others="true"> Ações
									<i class="fa fa-angle-down"></i>
								</a>
								<ul class="dropdown-menu pull-right">
									<li>
										<a  href="javascript:document.location='novo_pendencia.php?id=<?=$agendamentoId;?>';">Nova Pendência</a>
									</li>
									<li>
										<a  href="javascript:exporta('tabela');">Exportar para EXCEL</a>
									</li>
									<li>
										<a  href="javascript:pdf();">Exportar para PDF</a>
									</li>



								</ul>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-11 column">

							<div class="row">
									<div class="col-md-3 column">
										<table width="100%">
											<tr>
												<td>
													<div class="search-filter ">
														<div class="search-label uppercase"><span class="caption-subject font-green-sharp sbold"><font size="2"><b>Centro</b></font></span>
														</div>
														<select name="form_centro" id="form_centro" class="form-control">
															<option value=""></option>
															<?php
															$query = "SELECT id, nome from cfg_centro where projeto = ".$_SESSION['projeto']." order by nome";
															$resResp = $conexao->pesquisa($query);
															while($rowResp = mysqli_fetch_assoc($resResp)){
																echo "<option value='".$rowResp['nome']."'>".$rowResp['nome']."</option>";
															}
															?>
														</select>
													</div>
												</td>
											</tr>
												<td>
													<div class="search-filter ">
														<div class="search-label uppercase"><span class="caption-subject font-green-sharp sbold"><font size="2"><b>Participante</b></font></span>
														</div>
														<select name="form_paciente" id="form_paciente" class="form-control">
															<option value=""></option>
															<?php
															$query = "SELECT distinct paciente FROM agenda_lista_pendencia where projeto = ".$_SESSION['projeto']."  order by substr(paciente, 1,2), substr(paciente, 4,3)";
															$resResp = $conexao->pesquisa($query);
															while($rowResp = mysqli_fetch_assoc($resResp)){
																echo "<option value='".$rowResp['paciente']."'>".$rowResp['paciente']."</option>";
															}
															?>
														</select>
													</div>
												</td>
											<tr>
											</tr>
										</table>
									</div>
									<div class="col-md-1 column">
										<div class="search-filter ">
											<div class="search-label uppercase"><span class="caption-subject font-green-sharp sbold">#Visita</span>
											</div>
											<div class="input-icon right">
												<select name="form_visita" id="form_visita" class="form-control">
													<option value=""></option>
													<?php
													$query = "SELECT nro_visita FROM agenda where projeto = " . $_SESSION['projeto'] . " order by nro_visita";
													$resResp = $conexao->pesquisa($query);
													while($rowResp = mysqli_fetch_assoc($resResp)){
														echo "<option value='".$rowResp['nro_visita']."'>".$rowResp['nro_visita']."</option>";
													}
													?>
												</select>
											</div>
										</div>
									</div>
									<div class="col-md-2 column">
										<div class="search-filter ">
											<div class="search-label uppercase"><span class="caption-subject font-green-sharp sbold">TIPO</span>
											</div>
											<div class="input-icon right">
												<select name="form_tipo" id="form_tipo" class="form-control">
													<option value="">TODOS</option>
													<option value="ACHADO">ACHADO</option>
													<option value="DESVIO">DESVIO DE PROTOCOLO</option>
													<option value="DESVIO DE GCP">DESVIO DE GCP</option>
													<option value="NÃO CONFORMIDADE">NÃO CONFORMIDADE</option>
												</select>
											</div>
										</div>
									</div>


									<div class="col-md-2">
										<table width="100%">
											<tr>
												<td>
													<div class="search-filter ">
														<div class="search-label uppercase"><span class="caption-subject font-green-sharp sbold"><font size="2"><b>Data Inicial</b></font></span>
														</div>
														<input type="text" class="form-control" id="form_data_inicial" name="form_data_inicial" style='text-align: center' onkeyUp="mascara(this, '##/##/####');">
													</div>
												</td>
											</tr>
												<td>
													<div class="search-filter ">
														<div class="search-label uppercase"><span class="caption-subject font-green-sharp sbold"><font size="2"><b>Data final</b></font></span>
														</div>
														<input type="text" class="form-control" id="form_data_final" name="form_data_final" style='text-align: center' onkeyUp="mascara(this, '##/##/####');">
													</div>
												</td>
											<tr>
											</tr>
										</table>

									</div>


									<div class="col-md-2 column">




										<table width="100%">
											<tr>
												<td>
													<div class="search-filter ">
													<div class="search-label uppercase"><span class="caption-subject font-green-sharp sbold"><font size="2"><b>Status</b></font></span>
														</div>
														<div class="input-icon right">
																<select name="form_status" id="form_status" class="form-control">
																	<option value=""></option>
																	<option value="PENDENTE">PENDENTE</option>
																	<option value="RESOLVIDO">RESOLVIDO</option>

																</select>
														</div>
													</div>
												</td>
											</tr>
												<td>
													<div class="search-filter ">
													<div class="search-label uppercase"><span class="caption-subject font-green-sharp sbold"><font size="2"><b>Monitor</b></font></span>
														</div>
														<div class="input-icon right">
																<select name="form_monitor" id="form_monitor" class="form-control">
																	<option value=""></option>
																	<?php
																		$query  = "select id, nome from usu_usuario where ativo = 'on' and tipo_usuario = 2";
																		$resMon = $conexao->pesquisa($query);
																		while($rowMon = mysqli_fetch_assoc($resMon)){
																			echo '<option value="'.$rowMon['id'].'">'.$rowMon['nome'].'</option>';
																		}
																	?>

																</select>
														</div>
													</div>
												</td>
											<tr>
											</tr>
										</table>
									</div>


									<div class="col-md-2 column">
										<div class="search-filter ">
											<div class="search-label uppercase"><span class="caption-subject font-green-sharp sbold">ORDEM</span>
											</div>
											<div class="input-icon right">
													<select name="form_ordem" id="form_ordem" class="form-control">
														<option value="#">#SEQ</option>
														<option value="PAC" selected>PARTICIPANTE</option>
														<option value="DATA">DATA</option>
													</select>
											</div>
										</div>
									</div>

							</div>
						</div>
						<div class="col-md-1 column">
							<br><button class="btn green bold uppercase btn-block" onclick="filtro();" name="btnFiltrar" id="btnFiltrar">Filtrar</button>
						</div>
					</div>
					<hr>
					<div class="portlet-body">
						<table class="table table-hover table-striped table-bordered" name="tabela" id="tabela" >
							<thead>
								<tr>
									<th style="font-size: 12px !important;"><center># Pendência</center></th>
									<th style="font-size: 12px !important;"><center>Nro da Visita</center></th>
									<th style="font-size: 12px !important;">Data da Visita<br></th>
									<th style="font-size: 12px !important;">Centro<br></th>
									<th style="font-size: 12px !important;">Participante<br></th>
									<th style="font-size: 12px !important;">Tipo</th>
									<th style="font-size: 12px !important;">Achado</th>
									<th style="font-size: 12px !important;">Responsável</th>
									<th style="font-size: 12px !important;">Data de Abertura</th>
									<th style="font-size: 12px !important;">STATUS</th>
									<th style="font-size: 12px !important;">Data de Fechamento</th>
									<th style="font-size: 12px !important;">Criticidade</th>
									<th style="font-size: 12px !important;">Categoria</th>
									<th style="font-size: 12px !important;" width="30"><center>Detalhes..</center></th>
									<th style="font-size: 12px !important;">Excluir</th>
								</tr>
							</thead>

							<tbody id="tabela_retorno" name="tabela_retorno">
								<tr>
									<td colspan="12">Nenhum dado a exibir...</td>
								</tr>
							</tbody>

						</table>



					</div>
				</div>
				<!-- END PORTLET-->


			</div>



		</div>
	</div>
	<?php
		include "global_js.php";
	?>
</body>
	<script src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script>

	function excluir(ID){
		var resp = confirm("Tem certeza que deseja excluir essa pendência?");
		if(resp==true){
			window.location = "agendamentos_lista_pendencia.php?act=X&id="+ID;
		}
	}
	function exporta(){
		mostraCarregando();

		var formData = {}
		varUrl = "php_php/exportacoes.php?act=PENDENCIAS&filtro="+retornaFiltro();
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
	function ExportarEXCEL(tabela){
		var tab_text="<table border='2px'><tr bgcolor='#87AFC6'>";
		var textRange; var j=0;
		tab = document.getElementById(tabela); // id of table

		for(j = 0 ; j < tab.rows.length ; j++) {
			temp = tab.rows[j].innerHTML;
			temp = temp.replace('”', '"');
			temp= temp.replace('%u201', '"');
			tab_text=tab_text+temp+"</tr>";
		}

		tab_text=tab_text+"</table>";
		tab_text= tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
		tab_text= tab_text.replace(/<img[^>]*>/gi,""); // remove if u want images in your table
		tab_text= tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params
		tab_text= tab_text.replace('”', '"');
		tab_text= tab_text.replace('%u201', '"');


		var ua = window.navigator.userAgent;
		var msie = ua.indexOf("MSIE ");

		if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)){      // If Internet Explorer
			txtArea1.document.open("txt/html","replace");
			txtArea1.document.write(tab_text);
			txtArea1.document.close();
			txtArea1.focus();
			sa=txtArea1.document.execCommand("SaveAs",true,"download.xls");
		}
		else
			sa = window.open('data:application/vnd.ms-excel,' + escape(tab_text));
			//sa = window.open('data:application/vnd.ms-excel;charset=utf-8,' + escape(tab_text));
		return (sa);
	}

	function pdf(){
		/*var data = prompt("Qual a data a imprimir?", "Escolha a data");
		popup('rep_pendencias2.php?data='+data);*/
		var visita = document.getElementById("form_visita").value;
		var centro		= document.getElementById("form_centro").value;
		var data_inicial= document.getElementById("form_data_inicial").value;
		var data_final	= document.getElementById("form_data_final").value;

		var status		= document.getElementById("form_status").value;
		var paciente	= document.getElementById("form_paciente").value;

		if(data_inicial!=""){temp_inicial = data_inicial.split("/");data_inicial = temp_inicial[2]+temp_inicial[1]+temp_inicial[0];}
		if(data_final!=""){temp_final = data_final.split("/");data_final = temp_final[2]+temp_final[1]+temp_final[0];}

		popup('rep_pendencias2.php?visita='+visita+"&centro="+centro+"&data_inicial="+data_inicial+"&data_final="+data_final+"&status="+status+"&paciente="+paciente);
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

		function retornaFiltro(){
			qtd_where = 0;

			var visita = document.getElementById("form_visita").value;
			var centro		= document.getElementById("form_centro").value;
			var data_inicial= document.getElementById("form_data_inicial").value;
			var data_final	= document.getElementById("form_data_final").value;

			var status		= document.getElementById("form_status").value;
			var paciente	= document.getElementById("form_paciente").value;
			var monitora	= document.getElementById("form_monitor").value;

			var tipo 		= document.getElementById("form_tipo").value;

			var where = "";

			if(paciente!=""){where = where + " and paciente = '"+paciente+"' ";}
			if(visita!=""){ where = where + " and visita = '"+visita+"' ";}
			if(centro!=""){ where = where + " and agenda_lista_pendencia.centro = '"+centro+"'";}
			if(monitora!=""){ where = where + " and monitora = '"+monitora+"' ";}
			if(tipo!=""){ where = where + " and tipo = '"+tipo+"' ";}

			if(data_inicial!=""){
				temp_inicial = data_inicial.split("/");
				temp_inicial = temp_inicial[2]+temp_inicial[1]+temp_inicial[0];
				where  = where + " and (data_abertura_lis >= '" + temp_inicial + "')";
			}

			if(data_final!=""){
				temp_final = data_final.split("/");
				temp_final = temp_final[2]+temp_final[1]+temp_final[0];
				where  = where + " and (data_abertura_lis <= '" + temp_final + "')";
			}

			if(status!=""){ where = where + " and status = '"+status+"' ";}


			var ordem = document.getElementById("form_ordem").value;
			var sqlOrdem = "";
			if(ordem=="#"){
				sqlOrdem = " order by id "
			}
			if(ordem=="PAC"){
				sqlOrdem = " order by cast(paciente as integer) "
			}
			if(ordem=="DATA"){
				sqlOrdem = " order by STR_TO_DATE(visita, '%d/%m/%Y') "
			}

			return where + sqlOrdem;
		}

		function filtro(){

			if (window.XMLHttpRequest) {
			// code for IE7+, Firefox, Chrome, Opera, Safari
			document.getElementById("tabela_retorno").innerHTML = "<center><img src='imagens/carregando.gif' width='180'></centeR>";
			xmlhttp = new XMLHttpRequest();
			} else {
				// code for IE6, IE5
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					document.getElementById("tabela_retorno").innerHTML = this.responseText;
				}
			};
			xmlhttp.open("POST","ajax.php?act=LISTA_PENDENCIA_LISTA&filtro="+retornaFiltro());
			xmlhttp.send();
		};
</script>
</html>