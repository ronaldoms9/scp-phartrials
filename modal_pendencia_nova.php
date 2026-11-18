<?php
	include_once "seguranca.php";
	$campo = $_GET['id'];
?>
<html>
<head>
<meta http-equiv="Content-Language" content="pt-br">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>New Page 2</title>
<meta http-equiv="Content-Language" content="pt-br">
	<style>
		.datepicker.dropdown-menu {z-index:10000;}
		.datepicker {
			z-index: 99999 !important;
		}
	</style>
<?php
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
		<div class="row">
			<div class="col-md-12">
				<div class="portlet light">
					<div class="portlet-title fixo">
						<div class="caption font-blue-dark">
							<span><i class="fa fa-th font-blue-dark" title="Formulários"></i></span>
							Nova pendência
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
												<option value=""></option>
												<?php
													$query = "select * from cfg_centro where projeto = " . $_SESSION['projeto'];
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
										<td class="tabela-body"># Número da Visita</td>
										<td style="vertical-align:middle" >
											<input type="text" class="form-control" id="form_visita" name="form_visita<?=rand(rand(1,530),rand(10,15478));?>" <?=$centralizado;?> value="" >
										</td>
									</tr>
									<tr>
										<td class="tabela-body">Data da Visita</td>
										<td style="vertical-align:middle" >
											<input type="text" class="form-control" id="form_visita_data" name="form_visita_data<?=rand(rand(1,530),rand(10,15478));?>" <?=$centralizado;?> value="" onkeyup="mascara(this, '##/##/####');" >
										</td>
									</tr>
									<tr>
										<td class="tabela-body"># Participante<br><i>(Se aplicável)</i></td>
										<td style="vertical-align:middle" >
											<input type="text" class="form-control" id="form_paciente" name="form_paciente" <?=$centralizado;?> value="<?=$varPaciente;?>">
										</td>
									</tr>
									<tr>
										<td class="tabela-body">Monitora</td>
										<td style="vertical-align:middle" >
											<select id="form_monitora" name="form_monitora" class="form-control">
												<option value=""></option>
												<?php
													//$query 	  = "select id, nome from usu_usuario where tipo_usuario = 2 order by nome";
													$query 	  = "select id, nome from usu_usuario where ativo = 'on' order by nome";
													$res_moni = $conexao->pesquisa($query);
													while($row_moni = mysqli_fetch_assoc($res_moni)){
													?>
														<option value="<?=$row_moni['id'];?>" ><?=$row_moni['nome'];?></option>
													<?php
													}
												?>
											</select>
										</td>
									</tr>
									<tr>
										<td class="tabela-body">Tipo</td>
										<td style="vertical-align:middle" >
											<select id="form_tipo" name="form_tipo" class="form-control">
												<option value=""></option>
												<option value="ACHADO" >ACHADO</option>
												<option value="DESVIO" >DESVIO DE PROTOCOLO</option>
												<option value="DESVIO DE GCP" >DESVIO DE GCP</option>
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
												<option value="ERRO DE PREENCHIMENTO" >Erro de preenchimento</option>
												<option value="FALTA DE DOCUMENTO" >Falta de Documento</option>
												<option value="FALTA DE DOCUMENTO FONTE" >Falta de Documento Fonte</option>
											</select>
										</td>
									</tr>
									<tr>
										<td class="tabela-body">Classificação da pendência</td>
										<td>
											<select id="form_class_pendencia" name="form_class_pendencia" class="form-control">
												<option value=""></option>
												<option value="CRITICO"  <?php if($row['class_pendencia']=="CRITICO"){echo " selected ";}?>>Crítico</option>
												<option value="MAIOR"  <?php if($row['class_pendencia']=="MAIOR"){echo " selected ";}?> >Maior</option>
												<option value="MENOR"  <?php if($row['class_pendencia']=="MENOR"){echo " selected ";}?> >Menor</option>
												<option value="NÃO CONFORMIDADE"  <?php if($row['class_pendencia']=="NÃO CONFORMIDADE"){echo " selected ";}?> >Não conformidade</option>
												<option value="OBSERVAÇÃO"  <?php if($row['class_pendencia']=="OBSERVAÇÃO"){echo " selected ";}?> >Observação</option>
												<option value="OPORTUNIDADE DE MELHORIA"  <?php if($row['class_pendencia']=="OPORTUNIDADE DE MELHORIA"){echo " selected ";}?> >Oportunidade de melhoria</option>
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
										<td class="tabela-body">Formulário do CRF (Se aplicável)</td>
										<td style="vertical-align:middle" >
											<input type="text" class="form-control" id="form_formulario" name="form_formulario" <?=$centralizado;?> value="<?=$row['formulario'];?>">
										</td>
									</tr>
									<tr>
										<td class="tabela-body">Data de início</td>
										<td style="vertical-align:middle" >
											<input type="text" class="form-control" id="form_data_inicio" name="form_data_inicio" <?=$centralizado;?> onkeyup="mascara(this, '##/##/####');" value="<?=$row['data_abertura'];?>">
										</td>
									</tr>


									<tr>
										<td class="tabela-body">Achado</td>
										<td style="vertical-align:middle" >
											<textarea name="form_achado" id="form_achado" rows="5" width="100%" style="width:100%"></textarea>
										</td>
									</tr>
									<tr>
										<td class="tabela-body">Responsável</td>
										<td style="vertical-align:middle" >
											<select id="form_responsavel" name="form_responsavel" class="form-control">
													<option value=""></option>
													<option value="LABORATÓRIO EXAMES" <?php if($row['responsavel']=="LABORATÓRIO EXAMES"){echo " selected ";}?>>LABORATÓRIO EXAMES</option>
													<option value="CENTRO" <?php if($row['responsavel']=="CENTRO"){echo " selected ";}?>>CENTRO</option>
													<option value="MONITORA" <?php if($row['responsavel']=="MONITORA"){echo " selected ";}?>>MONITORA</option>
													<option value="PATROCINADOR" <?php if($row['responsavel']=="PATROCINADOR"){echo " selected ";}?>>PATROCINADOR</option>
													<option value="LABORATORIO" <?php if($row['responsavel']=="LABORATORIO"){echo " selected ";}?>>Laboratório</option>
													<option value="EDC" <?php if($row['responsavel']=="EDC"){echo " selected ";}?>>EDC (Sistema)</option>

												</select>
										</td>
									</tr>
									<tr>
										<td class="tabela-body">Data de abertura</td>
										<td style="vertical-align:middle" >
											<input onkeypress='validate(event)' type="text" class="form-control" id="form_data_abertura" name="form_data_abertura" <?=$centralizado;?> onkeyup="mascara(this, '##/##/####');" value="<?=date('d/m/Y');?>">
										</td>
									</tr>

									<tr>
										<td colspan="2" class="tabela-head" style="text-align:center">
											<button type="button" class="btn blue-chambray" onclick="javascript:salvarPendencia();">Salvar informações</button>
											<button type="button" class="btn btn-default" data-dismiss="modal" id="btnFechar" name="btnFechar">Fechar</button>
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
	<?php
		include "global_js.php";
	?>
	<script src="js/ui.datepicker-pt-BR.js" type="text/javascript"></script>
<script>
	$( function() {		$( "#form_visita_data" ).datepicker();	  } );
	$( function() {		$( "#form_data_abertura" ).datepicker();	  } );
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


	function salvarPendencia(){



		var varCentro 	= document.getElementById("form_centro").value;
		var varVisita 	= document.getElementById("form_visita").value;
		var varPaciente	= document.getElementById("form_paciente").value;
		var varMonitor	= document.getElementById("form_monitora").value;
		var varAchado	= document.getElementById("form_achado").value;
		var varTipo		= document.getElementById("form_tipo").value;
		var varResponsavel=document.getElementById("form_responsavel").value;
		var varData		= document.getElementById("form_data_abertura").value;
		var categoria = document.getElementById("form_categoria").value;
		var criticidade = document.getElementById("form_criticidade").value;
		var dataVisita = document.getElementById("form_visita_data").value;
		var class_pendencia = document.getElementById("form_class_pendencia").value;
		var formulario = document.getElementById("form_formulario").value;
		var data_abertura = document.getElementById("form_data_abertura").value;
		if(class_pendencia==""){ok=false;alert("Classificação da pendência não selectionada. Por favor, revise as informações");return;}
		if(formulario==""){ok=false;alert("Formulário do CRF não preenchido. Por favor, revise as informações");return;}
		if(data_abertura==""){ok=false;alert("Data de início não preenchida. Por favor, revise as informações");return;}
		if(varResponsavel==""){ok=false;alert("Responsável não selectionado. Por favor, revise as informações");return;}
		if(varAchado==""){ok=false;alert("Achado não preenchido. Por favor, revise as informações");return;}
		if(varTipo==""){ok=false;alert("Tipo de pendência não selectionado. Por favor, revise as informações");return;}
		if(varMonitor==""){ok=false;alert("Monitor não selectionado. Por favor, revise as informações");return;}
		if(varVisita==""){alert("Visita não pode ser em branco");return;}
		if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest();
		} else {
			// code for IE6, IE5
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				alert("Pendência salva com sucesso");
				document.getElementById("btnFechar").click();

			}
		};
		query = "centro="+varCentro+"&visita="+varVisita+"&paciente="+varPaciente;
		query = query + "&monitor="+varMonitor+"&achado="+varAchado+"&tipo="+varTipo+"&responsavel="+varResponsavel+"&data="+varData+"&categoria="+categoria+"&criticidade="+criticidade+"&visita_data="+dataVisita+"&class_pendencia="+class_pendencia+"&formulario="+formulario+"&data_abertura="+data_abertura+"&responsavel="+varResponsavel;
		xmlhttp.open("POST","ajax.php?act=MODAL_PENDENCIA&"+query);
		xmlhttp.send();
	}
</script>

</html>