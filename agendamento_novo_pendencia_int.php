<?
	include_once "seguranca.php";

	$agendamentoId = $_GET['id'];
	$query = "select
		agenda.*
		from agenda
			where id = " . $agendamentoId;
	$res_c = $conexao->pesquisa($query);
	$row_agendamento = mysqli_fetch_assoc($res_c);
	$dataAgendamento = $row_agendamento['data'];
	$dataAgendamento = substr($dataAgendamento,6,2) . "/" . substr($dataAgendamento,4,2) . "/" . substr($dataAgendamento,0,4);

	if($_GET['opc']=="E"){
		$id = $_GET['registro'];
		$query = "select * from agenda_lista_pendencia where id = " . $id;
		$res   = $conexao->pesquisa($query);
		$row   = mysqli_fetch_assoc($res);

	}
?>
<html>
<head>
<meta http-equiv="Content-Language" content="pt-br">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>New Page 2</title>
<meta http-equiv="Content-Language" content="pt-br">
<?
	include "global_css.php";
?>
</head>
<script >
		<?=$msg;?>
		function mascara(t, mask){
				 var i = t.value.length;
				 var saida = mask.substring(1,0);
				 var mascara = mask.substring(i)
				 if(event.keyCode!=8){
					 if(t.value.substr(i-1,1)!=mask.substr(i,1)){
						 if (mascara.substring(0,1) != saida){

							t.value += mascara.substring(0,1);
						 }
					 }
				 }
		 }
</script>
	<div class="page-wrapper">
	<?
		$parametro = gera_parametro("cam",$agendamentoId);
		$agendamento = $agendamentoId;
		if($id>0){
			$acao = "agendamento_lista_pendencia_int.php?agendamento=$agendamento&$parametro&act=A&id=".$id;
		}else{
			$acao = "agendamento_lista_pendencia_int.php?agendamento=$agendamento&$parametro&act=I";
		}
	?>
		<div class="row">
			<div class="col-md-12">
				<div class="portlet light">
					<div class="portlet-title fixo">
						<div class="caption font-blue-dark">
							<span><i class="fa fa-th font-blue-dark" title="Formulários"></i></span>
							<?
								if($_GET['id']>0){$label = "Editar Pendência";}else{$label = "Nova Pendência";}
							?>
							<span class="caption-subject"><?=$label;?></span>
						</div>
					</div>
					<div class="portlet-body">
						<form class="form-horizontal" role="form" action="<?=$acao;?>" method="post" id="form_info" name="form_info">
							<table width="100%" class="table table-striped" style="font-family: 'Abel';font-size: 22px;">

							   <tbody>
									<tr>
										<td class="tabela-body">Centro</td>
										<td style="vertical-align:middle" >
											<select id="form_centro" name="form_centro" class="form-control">
												<?
													$query = "select * from cfg_centro where id = " . $row_agendamento['centro'];
													$res_c = $conexao->pesquisa($query);
													while($row_c = mysqli_fetch_assoc($res_c) ){
														echo "<option value='".$row_c['nome']."' ";
														echo ">".$row_c['nome']."</option>";
													}
												?>
											</select>
										</td>
									</tr>
									<tr>
										<td class="tabela-body"># Visita</td>
										<td style="vertical-align:middle" >
											<input disabled type="text" class="form-control" <?=$centralizado;?> value="<?if($act!="E"){echo $dataAgendamento;}?>">
											<input type="hidden" class="form-control" id="form_visita" name="form_visita" <?=$centralizado;?> value="<?if($act!="E"){echo $dataAgendamento;}?>">
										</td>
									</tr>
									<tr>
										<td class="tabela-body"># Participante<br><i>(Se aplicável)</i></td>
										<td style="vertical-align:middle" >
											<input type="text" class="form-control" id="form_paciente" name="form_paciente" <?=$centralizado;?> value="<?=$row['paciente'];?>">
										</td>
									</tr>
									<tr>
										<td class="tabela-body">Monitor</td>
										<td style="vertical-align:middle" >
											<select id="form_monitora" name="form_monitora" class="form-control">
												<option value=""></option>
												<?
													//$query 	  = "select id, nome from usu_usuario where tipo_usuario = 2 order by nome";
													$query 	  = "select id, nome from usu_usuario order by nome";
													$res_moni = $conexao->pesquisa($query);
													while($row_moni = mysqli_fetch_assoc($res_moni)){
													?>
														<option value="<?=$row_moni['id'];?>" <?if($row['monitora']==$row_moni['id']){echo " selected ";}?>><?=$row_moni['nome'];?></option>
													<?
													}
												?>
											</select>
										</td>
									</tr>
									<tr>
										<td class="tabela-body">Achado</td>
										<td style="vertical-align:middle" >
											<textarea name="form_achado" id="form_achado" rows="5" width="100%" style="width:100%"><?=$row['achado'];?></textarea>
										</td>
									</tr>
									<tr>
										<td class="tabela-body">Tipo</td>
										<td style="vertical-align:middle" >
											<select id="form_tipo" name="form_tipo" class="form-control">
												<option value="ACHADO" <?if($row['tipo']=="ACHADO"){echo " selected ";}?>>ACHADO</option>
											<option value="DESVIO" <?if($row['tipo']=="DESVIO"){echo " selected ";}?>>DESVIO</option>
											<option value="DESVIO DE GCP" <?if($row['tipo']=="DESVIO DE GCP"){echo " selected ";}?>>DESVIO DE GCP</option>
											</select>
										</td>
									</tr>
									<tr>
										<td class="tabela-body">Categoria</td>
										<td>
											<select id="form_categoria" name="form_categoria" class="form-control">
												<option value=""></option>
												<option value="TCLE" >TCLE</option>
												<option value="DATA" >Erros de data</option>
												<option value="EM BRANCO" >Campo em branco</option>
											</select>
										</td>
									</tr>
									<tr>
										<td class="tabela-body">Classificação da pendência</td>
										<td>
											<select id="form_class_pendencia" name="form_class_pendencia" class="form-control">
												<option value=""></option>
												<option value="CRITICO" >Crítico</option>
												<option value="MAIOR" >Maior</option>
												<option value="MENOR" >Menor</option>
												<option value="NÃO CONFORMIDADE" >Não conformidade</option>
												<option value="OBSERVAÇÃO" >Observação</option>
												<option value="OPORTUNIDADE DE MELHORIA" >Oportunidade de melhoria</option>
											</select>
										</td>
									</tr>
									<tr>
										<td class="tabela-body">Criticidade</td>
										<td style="vertical-align:middle" >
											<select id="form_criticidade" name="form_criticidade" class="form-control">
												<option value=""></option>
												<option value="NAO" >Não crítico</option>
												<option value="CRITICO" >Crítico</option>
											</select>
										</td>
									</tr>
									<tr>
										<td class="tabela-body">Responsável<?=$row['form_responsavel'];?></td>
										<td style="vertical-align:middle" >

											<select id="form_responsavel" name="form_responsavel" class="form-control">
												<option value="CENTRO" <?if($row['responsavel']=="CENTRO"){echo " selected ";}?>>Centro</option>
												<option value="MONITOR" <?if($row['responsavel']=="MONITOR"){echo " selected ";}?>>Monitor</option>
												<option value="PATROCINADOR" <?if($row['responsavel']=="PATROCINADOR"){echo " selected ";}?>>Patrocinador</option>
												<option value="LABORATORIO" <?if($row['responsavel']=="LABORATORIO"){echo " selected ";}?>>Laboratório</option>
												<option value="EDC" <?if($row['responsavel']=="EDC"){echo " selected ";}?>>EDC (Sistema)</option>
											</select>
										</td>
									</tr>
									<tr>
										<td class="tabela-body">Data de abertura</td>
										<td style="vertical-align:middle" >
											<input onkeypress='validate(event)' type="text" class="form-control" id="form_data_abertura" name="form_data_abertura" <?=$centralizado;?> onkeyup="mascara(this, '##/##/####');" value="<?if($row['data_abertura']==""){echo date("d/m/Y");}else{echo $row['data_abertura'];}?>">
										</td>
									</tr>

									<tr>
										<td class="tabela-body">Ação tomada</td>
										<td style="vertical-align:middle" >
											<textarea name="form_acao" id="form_acao" rows="4" width="100%" style="width:100%"><?=$row['acao'];?></textarea>
										</td>
									</tr>
									<tr>
										<td class="tabela-body">Status</td>
										<td style="vertical-align:middle" >
											<select id="form_status" name="form_status" class="form-control" onchange="mudou_status(this.value)">
												<option value="PENDENTE" <?if($row['status']=="PENDENTE"){echo " selected ";}?>>PENDENTE</option>
												<option value="RESOLVIDO" <?if($row['status']=="RESOLVIDO"){echo " selected ";}?>>RESOLVIDO</option>
											</select>
										</td>
									</tr>
									<tr>
										<td class="tabela-body">Data de desfecho</td>
										<td style="vertical-align:middle" >
											<input onkeypress='validate(event)' type="text" class="form-control" id="form_data_fechamento" name="form_data_fechamento" <?=$centralizado;?> onkeyup="mascara(this, '##/##/####');" value="<?=$row['data_fechamento'];?>">
										</td>
									</tr>
									<tr>
										<td class="tabela-body">Resolvido por</td>
										<td style="vertical-align:middle" >
											<input type="text" class="form-control" id="form_resolvido_por" name="form_resolvido_por" <?=$centralizado;?> onkeyup="mascara(this, '##/##/####');" value="<?=$row['resolvido_por'];?>">
										</td>
									</tr>
									<tr>
										<td colspan="2" class="tabela-head" style="text-align:center">
											<button type="button" class="btn blue-chambray" onclick="salvar();">Salvar informações</button>
										</td>
									</tr>
								</tbody>
							</table>
					</div>
				</div>
			</div>
		</div>

	</div>
</body>
	<?
		include "global_js.php";
	?>
<script>
	function salvar(){
		var ok = true;
		var tipo = document.getElementById("form_tipo").value;
		if(tipo==""){ok=false;alert("Tipo de pendência não selectionado. Por favor, revise as informações");return;}
		var monitora = document.getElementById("form_monitora").value;
		if(monitora==""){ok=false;alert("Monitor não selectionado. Por favor, revise as informações");return;}
		var status = document.getElementById("form_status").value;
		if(status==""){ok=false;alert("Status da pendência não selectionado. Por favor, revise as informações");return;}
		var form_data_abertura = document.getElementById("form_data_abertura").value;
		if(form_data_abertura==""){ok=false;alert("Data de abertura não preenchida. Por favor, revise as informações");}

		//
		if(CheckDate(document.getElementById("form_data_abertura"))==false){ok=false;alert("Data de abertura inválida. Por favor, revise as informações");}
		if(document.getElementById("form_data_fechamento").value!=""){
			if(CheckDate(document.getElementById("form_data_fechamento"))==false){ok=false;alert("Data de desfecho inválida. Por favor, revise as informações");}
		}
		var achado = document.getElementById("form_achado").value;
		if(form_achado==""){ok=false;alert("Achado não preenchido. Por favor, revise as informações");return;}
		if(ok==true){document.form_info.submit();}
	}


	function CheckDate(pObj) {
	  var expReg = /^((0[1-9]|[12]\d)\/(0[1-9]|1[0-2])|30\/(0[13-9]|1[0-2])|31\/(0[13578]|1[02]))\/(19|20)?\d{2}$/;
	  var aRet = true;
	  if ((pObj) && (pObj.value.match(expReg)) && (pObj.value != '')) {
		var dia = pObj.value.substring(0,2);
		var mes = pObj.value.substring(3,5);
		var ano = pObj.value.substring(6,10);
		if ((mes == 4 || mes == 6 || mes == 9 || mes == 11 ) && dia > 30)
		  aRet = false;
		else
		  if ((ano % 4) != 0 && mes == 2 && dia > 28)
			aRet = false;
		  else
			if ((ano%4) == 0 && mes == 2 && dia > 29)
			  aRet = false;
	  }  else
		aRet = false;
	  return aRet;
	}
	function validate(evt) {
	  var theEvent = evt || window.event;
	  // Handle paste
	  if (theEvent.type === 'paste') {
		  key = event.clipboardData.getData('text/plain');
	  } else {
	  // Handle key press
		  var key = theEvent.keyCode || theEvent.which;
		  key = String.fromCharCode(key);
	  }
	  var regex = /[0-9]|\./;
	  if( !regex.test(key) ) {
		theEvent.returnValue = false;
		if(theEvent.preventDefault) theEvent.preventDefault();
	  }
	}
	function mudou_status(status){
		if(status=="PENDENTE"){
			document.getElementById("form_data_fechamento").disabled = true;
			document.getElementById("form_resolvido_por").disabled = true;
			document.getElementById("form_data_fechamento").value = "";
			document.getElementById("form_resolvido_por").value = "";
		}else{
			document.getElementById("form_data_fechamento").disabled = false;
			document.getElementById("form_resolvido_por").disabled = false;
		}
	}


	mudou_status(document.getElementById("form_status").value);

</script>

</html>